<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use App\Models\Attribution;
use App\Models\Bien;
use App\Models\Paiement;
use App\Models\Transaction;
use App\Models\User;
use App\Notifications\Admin\PaiementEffectueNotification;
use App\Notifications\Client\PaiementReussiClient;
use App\Notifications\Proprietaire\PaiementRecuProprietaire;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class ClientPanierController extends Controller
{
    public function initierFedaPay(Request $request)
    {
        $request->validate([
            'cart_data' => 'required'
        ]);
    
        $cart = json_decode($request->cart_data, true);
    
        if (!is_array($cart) || count($cart) === 0) {
            return back()->with('error', 'Panier vide.');
        }
    
        $amount = collect($cart)->sum(function ($item) {
            return $item['price'] * $item['qte'];
        });
        
        $montantAvecCommission = $amount * 1.05; // ajoute 5% de commission
        $montantCentimes = (int) round($montantAvecCommission); // Fedapay exige un entier
    
        if ($montantCentimes >= 300000) {
            return redirect()->route('catalogue')
                ->with('error', 'Pour les paiements via Momo (Fedapay), le montant ne doit pas dépasser 300000 FCFA. Veuillez essayer Paypal ou un virement bancaire.');
        }
    
        // Transaction interne locale bdd
        $transaction = Transaction::create([
            'user_id'          => auth()->id(),
            'reference'        => 'trx_' . Str::random(12),
            'montant_original' => $amount,                  
            'montant'          => $montantCentimes,         
            'mode_paiement'    => 'fedapay',
            'statut'           => 'en_attente',

            // Le panier est un tableau, conformité avec cast JSON
            'cart_json'        => $cart,

            // Tableau libre des détails (doit rester un array)
            'details'          => [
                'commission'  => round($amount * 0.05, 2),
                'total_avec_commission' => $montantAvecCommission,
            ],
        ]);

    
        // Config FedaPay
        \FedaPay\FedaPay::setApiKey(config('services.fedapay.api_key'));
        \FedaPay\FedaPay::setEnvironment(config('services.fedapay.mode')); 

        // Transaction Fedapay
        $fpTrx = \FedaPay\Transaction::create([
            'description'  => 'Achat ShopEasy',
            'amount'       => $montantCentimes,  
            'currency'     => ['iso' => 'XOF'],
            'metadata'     => ['transaction_id' => $transaction->id],
            'callback_url' => route('client.fedapay.callback', [
                'internal_id' => $transaction->id
            ])
        ]);
    
        // Redirection vers le paiement
        return redirect()->away($fpTrx->payment_url);
    }
    
    
    /*
    |--------------------------------------------------------------------------
    | CALLBACK FEDAPAY
    |--------------------------------------------------------------------------
    */

    public function fedapayCallback(Request $request)
    {
        $transaction = Transaction::find($request->internal_id);

        if (!$transaction) {
            return redirect()->route('client.dashboard')
                ->with('error', 'Transaction introuvable.');
        }

        // Lire statut renvoyé par Fedapay
        $status = $request->input('status');

        if ($status !== 'approved') {
            $transaction->update(['statut' => 'echoue']);
            return redirect()->route('catalogue')
                ->with('error', "Paiement échoué.");
        }

        if ($transaction->statut === 'reussi') {
            return redirect()->route('catalogue')
                ->with('info', 'Paiement déjà traité.');
        }

        // Succès
        $transaction->update(['statut' => 'reussi']);

        $cart = $transaction->cart_json;

        foreach ($cart as $item) {
            $bien = Bien::find($item['id']);
            if (!$bien) continue;

            $bien->update(['statut' => 'attribue']);

            $attribution = Attribution::create([
                'bien_id'          => $bien->id,
                'client_id'        => $transaction->user_id,
                'prix' => $item['price'] * $item['qte'],
                'proprietaire_id'  => $bien->proprietaire_id,
                'date_attribution' => now()->toDateString(),
                'statut_paiement'  => 'paye',
            ]);

            $paiement = Paiement::create([
                'attribution_id'  => $attribution->id,
                'client_id'       => $transaction->user_id,
                'montant'         => $item['price'] * 1.05,
                'reference'       => 'pay_' . Str::uuid(),
                'mode'            => 'mobile_money',
                'status_paiement' => 'paye',
                'date_paiement'   => now(),
                'details'         => [
                    'commission' => round($item['price'] * 0.05, 2),
                    'quantite' => $item['qte'],
                ]
            ]);

            // Notifications
            $client = $attribution->client;
            $proprio = $bien->proprietaire;
            
            $admin = User::where('role', 'admin')->first();

            if ($client) $client->notify(new PaiementReussiClient($paiement));
            if ($proprio) $proprio->notify(new PaiementRecuProprietaire($paiement));
            if ($admin) $admin->notify(new PaiementEffectueNotification($paiement));
        }

        return redirect()->route('catalogue')
            ->with('success', "Paiement confirmé via FedaPay.");
    }
    
}
