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
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class PaiementController extends Controller
{

    public function index()
    {
        $clientId = Auth::id();

        $paiements = Paiement::where('client_id', $clientId)
            ->with([
                'attribution.bien.proprietaire',
                'attribution',
            ])
            ->latest()
            ->get();

        return view('client.paiement.index', compact('paiements'));
    }

    // 1. Payer le panier via PAYPAL (multi-biens)

    public function payerPanier(Request $request)
    {
        $request->validate([
            'cart_data' => 'required'
        ]);

        $cart = json_decode($request->cart_data, true);

        if (!is_array($cart) || count($cart) === 0) {
            return back()->with('error', 'Panier vide.');
        }

        // Calculs identiques à Fedapay
        $total = array_sum(array_column($cart, 'price'));
        $commission = round($total * 0.05, 2);
        $montantTotal = $total + $commission;

        // Conversion FCFA -> USD (Paypal)
        $amountUSD = number_format($montantTotal / 600, 2, '.', '');


        // Transaction interne identique à Fedapay

        $transaction = Transaction::create([
            'user_id'          => auth()->id(),
            'reference'        => 'trx_' . Str::random(12),
            'montant_original' => $total,
            'montant'          => $montantTotal,
            'mode_paiement'    => 'paypal',
            'statut'           => 'en_attente',
            'cart_json'        => $cart,
            'details'          => [
                'commission' => $commission,
                'taux_conversion' => 600,
                'montant_usd' => $amountUSD
            ]
        ]);


        // Lien de redirection Paypal Standard

        $paypalUrl = "https://www.paypal.com/cgi-bin/webscr?" . http_build_query([
            'cmd'           => '_xclick',
            'business'      => config('services.paypal.email'),
            'item_name'     => "Paiement Panier ShopEasy",
            'amount'        => $amountUSD,
            'currency_code' => 'USD',
            'custom'        => $transaction->id,
            'return'        => route('client.paypal.callback', ['internal_id' => $transaction->id]),
            'cancel_return' => route('client.paypal.panier.cancel'),
        ]);

        return redirect()->away($paypalUrl);
    }



    // 2. CALLBACK PAYPAL (success)

    public function paypalCallback(Request $request)
    {
        $transaction = Transaction::find($request->internal_id);

        if (!$transaction) {
            return redirect()->route('catalogue')
                ->with('error', 'Transaction introuvable.');
        }

        if ($transaction->statut === 'reussi') {
            return redirect()->route('catalogue')
                ->with('info', 'Paiement déjà traité.');
        }

        // Paypal retourne ?st=Completed ou success=1 selon le setup
        $status = $request->input('st') ?? $request->input('status');

        if (!in_array($status, ['Completed', 'completed', 'success', 'Success'])) {
            $transaction->update(['statut' => 'echoue']);
            return redirect()->route('catalogue')
                ->with('error', 'Paiement Paypal non confirmé.');
        }

        // Paiement validé

        $transaction->update(['statut' => 'reussi']);

        $cart = $transaction->cart_json;

        foreach ($cart as $item) {

            $bien = Bien::find($item['id']);
            if (!$bien) continue;

            // marquer attribué
            $bien->update(['statut' => 'attribue']);

            // créer attribution
            $attribution = Attribution::create([
                'bien_id'          => $bien->id,
                'client_id'        => $transaction->user_id,
                'prix'             => $item['price'],
                'proprietaire_id'  => $bien->proprietaire_id,
                'date_attribution' => now()->toDateString(),
                'statut_paiement'  => 'paye',
            ]);

            // paiement
            $paiement = Paiement::create([
                'attribution_id'  => $attribution->id,
                'client_id'       => $transaction->user_id,
                'montant'         => $item['price'] * 1.05,
                'reference'       => 'pay_' . Str::uuid(),
                'mode'            => 'paypal',
                'status_paiement' => 'paye',
                'date_paiement'   => now(),
                'details'         => [
                    'commission' => round($item['price'] * 0.05, 2),
                    'paypal_transaction' => $request->all()
                ]
            ]);


            // Notifications identiques à Fedapay

            $client  = $attribution->client;
            $proprio = $bien->proprietaire;
            $admin   = User::where('role', 'admin')->first();

            if ($client)  $client->notify(new PaiementReussiClient($paiement));
            if ($proprio) $proprio->notify(new PaiementRecuProprietaire($paiement));
            if ($admin)   $admin->notify(new PaiementEffectueNotification($paiement));
        }

        return redirect()->route('catalogue')
            ->with('success', 'Paiement confirmé via PayPal.');
    }


    // 3. CANCEL PAYPAL

    public function paypalCancel()
    {
        return redirect()->route('catalogue')
            ->with('error', 'Le paiement Paypal a été annulé.');
    }
}
