<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use App\Models\Attribution;
use App\Models\Bien;
use App\Models\Transaction;
use FedaPay\FedaPay as FedaPaySDK;
use FedaPay\Transaction as FedaPayTransaction;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class ClientBienController extends Controller
{


    /**
     * Création automatique d'une attribution après paiement réussi
     */
    private function creerAttributionAutomatique($transaction)
    {
        $bien = $transaction->bien;
        $client = $transaction->user;

        // Déjà attribué ?
        if ($bien->statut === 'attribue') {
            return; 
        }

        // Mettre le bien en attribué
        $bien->update(['statut' => 'attribue']);

        // Créer l’attribution
        $attribution = Attribution::create([
            'bien_id'          => $bien->id,
            'client_id'        => $client->id,
            'proprietaire_id'  => $bien->proprietaire_id,
            'prix'             => $transaction->montant,
            'date_attribution' => now()->toDateString(),
            'statut_paiement'  => 'paye',
        ]);

        // Notifications
        if ($attribution->client) {
            $attribution->client->notify(new \App\Notifications\AttributionCreee($attribution));
        }
        if ($bien->proprietaire) {
            $bien->proprietaire->notify(new \App\Notifications\AttributionCreee($attribution));
        }
    }


    /**
     * Upload de preuve de paiement (PDF)
     */
    public function uploadProof(Request $request)
    {
        $request->validate([
            'bien_id'     => 'required|exists:biens,id',
            'proof_file'  => 'required|mimes:pdf|max:10240',
        ]);

        $file = $request->file('proof_file');

        $filename = 'preuve_' . auth()->id() . '_' . time() . '.' .
            $file->getClientOriginalExtension();

        $path = $file->storeAs('proofs', $filename, 'public');

        $transaction = Transaction::where('user_id', auth()->id())
            ->where('bien_id', $request->bien_id)
            ->latest()
            ->first();

        if ($transaction) {
            $transaction->update(['proof_path' => $path]);
        }

        return back()->with('success', 'Preuve de paiement envoyée avec succès !');
    }
}
