@extends('layouts.client')

@section('content')
<div class="container py-5 d-flex flex-column">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h4 class="fw-bold">Acheter un bien</h4>

        <button class="btn btn-success" data-bs-toggle="modal" data-bs-target="#uploadProofModal">
            Enregistrer une preuve
        </button>
    </div>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif
    @if(session('error'))
        <div class="alert alert-danger">{{ session('error') }}</div>
    @endif

    <form id="payment-form" class="p-4 border rounded bg-white shadow-sm">
        @csrf

        <input type="hidden" id="bien_id" value="{{ $bien->id }}">
        
        <div class="mb-3">
            <label class="form-label fw-semibold">Montant original (Fcfa)</label>
            <input type="number" id="montant_original" class="form-control" value="{{ number_format($bien->prix, 0, '', '') }}" readonly>
        </div>
        
        <div class="mb-3">
            <label class="form-label fw-semibold">Montant à payer (Fcfa)</label>
            @php
                $montant_total = round($bien->prix * 1.05); // ajoute 5% commission et arrondit à l'entier le plus proche
            @endphp
            <input type="number" id="montant" class="form-control" value="{{ number_format($montant_total, 0, '', '') }}" readonly>
        </div>
        

        <div class="mb-3">
            <label class="form-label fw-semibold">Mode de paiement</label>
            <select id="mode_paiement" class="form-select" required>
                <option value="" selected disabled>-- Choisir un mode --</option>
                <option value="mobile_money">Mobile Money (FedaPay)</option>
                <option value="carte_credit">Carte bancaire (PayPal)</option>
                <option value="virement_bancaire">Virement bancaire</option>
            </select>
        </div>

        <!-- Infos virement -->
        <div id="virement-info" class="alert alert-info d-none transition mt-3">
            <h5 class="fw-bold mb-2">Informations pour le virement bancaire</h5>
            <ul class="list-unstyled">
                <li><strong>Banque :</strong> Bank of Africa</li>
                <li><strong>Titulaire :</strong> Société Shopeasy</li>
                <li><strong>Compte :</strong> 123 456 789 000</li>
                <li><strong>IBAN :</strong> BJ12BOA00128456789000</li>
                <li><strong>Montant :</strong> {{ $bien->prix }} Fcfa</li>
            </ul>
            <p class="mb-0">Effectuez le virement, puis envoyez la preuve.</p>
        </div>

        <button type="button" id="pay-btn" class="btn btn-primary mt-4 px-4">
            Procéder au paiement
        </button>
    </form>
</div>

<!-- Modal upload preuve -->
<div class="modal fade" id="uploadProofModal" tabindex="-1">
    <div class="modal-dialog">
        <form action="{{ route('client.proof.upload') }}"
              method="POST" enctype="multipart/form-data"
              class="modal-content">
            @csrf

            <div class="modal-header">
                <h5 class="modal-title">Preuve de paiement</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>

            <div class="modal-body">

                <div class="mb-3">
                    <label class="form-label">Bien concerné</label>
                    <select name="bien_id" class="form-select" required>
                        <option value="" disabled selected>-- Sélectionnez --</option>
                        @php
                            $biens = App\Models\Bien::whereIn('id', auth()->user()->transactions()->pluck('bien_id'))
                                    ->where('type', 'vente')
                                    ->where('statut', 'disponible')
                                    ->get();
                        @endphp
                        @foreach($biens as $b)
                            <option value="{{ $b->id }}">{{ $b->titre }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="mb-3">
                    <label class="form-label">Preuve (PDF uniquement)</label>
                    <input type="file" name="proof_file" class="form-control"
                           accept="application/pdf" required>
                </div>

            </div>

            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
                <button type="submit" class="btn btn-success">Uploader</button>
            </div>
        </form>
    </div>
</div>


{{-- Fedapay + logique dynamique --}}
<script src="https://cdn.fedapay.com/checkout.js"></script>
<script>
    const modeSelect = document.getElementById('mode_paiement');
    const virementDiv = document.getElementById('virement-info');
    const payBtn = document.getElementById('pay-btn');
    const montantInput = document.getElementById('montant');
    const bienId = document.getElementById('bien_id').value;

    modeSelect.addEventListener('change', function() {
        if (this.value === 'virement_bancaire') {
            virementDiv.classList.remove('d-none');
            virementDiv.style.opacity = 1;
        } else {
            virementDiv.style.opacity = 0;
            setTimeout(() => virementDiv.classList.add('d-none'), 400);
        }
    });

    // Bouton paiement
    payBtn.addEventListener('click', function () {
        const montant = montantInput.value;
        const mode = modeSelect.value;

        if (!mode) {
            return alert('Veuillez sélectionner un mode de paiement');
        }

        // Paiement mobile money — FedaPay
        if (mode === 'mobile_money') {
            fetch("{{ route('client.biens.payer') }}", {
                method: "POST",
                headers: {
                    "Content-Type": "application/json",
                    "X-CSRF-TOKEN": "{{ csrf_token() }}"
                },
                body: JSON.stringify({
                    bien_id: bienId,
                    montant: montant,
                    mode_paiement: mode
                })
            })
            .then(res => res.json())
            .then(data => {
                if (data.public_key) {
                    FedaPay.init('#pay-btn', {
                        public_key: data.public_key,
                        transaction: {
                            amount: data.montant,
                            description: data.description
                        },
                        onComplete: function(response) {
                            if (response.reason === 'CHECKOUT COMPLETE') {
                                window.location.href =
                                    "{{ route('client.paiement.callback') }}?transaction_id=" + data.transaction_id;
                            }
                        }
                    });
                } else {
                    alert('Erreur lors de l\'initialisation du paiement.');
                }
            })
            .catch(() => alert("Erreur réseau. Réessayez."));
        }

        // Paiement par carte — PayPal
        if (mode === 'carte_credit') {
            // Compte paypal-me de shopeasy
            window.location.href = "https://www.paypal.me/tonComptePaypal/" + montant;
        }

        // Paiement par virement
        if (mode === 'virement_bancaire') {
            virementDiv.scrollIntoView({ behavior: 'smooth' });
        }
    });
</script>

@endsection