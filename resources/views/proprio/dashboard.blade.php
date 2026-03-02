@extends('layouts.proprio')

@section('title', 'Dashboard')

@section('content')
    {{-- small header --}}
    <div class="d-flex align-items-center justify-content-between mb-4">
        <div>
          <h2 class="mb-0">@yield('pageTitle', 'Tableau de bord')</h2>
          <div class="small-muted">@yield('pageSubtitle', 'Bienvenue sur votre espace cher vendeur')</div>
        </div>
        <div class="d-flex gap-2 align-items-center">
          @yield('headerActions')
        </div>
    </div>

    <div class="row g-4">
      <div class="col-md-3">
          <div class="card shadow-sm border-0 stat-card h-100">
              <div class="card-body text-center">
                  <div class="icon-circle bg-primary mb-3">
                      <i class="bi bi-people text-white"></i>
                  </div>
                  <h5 class="card-title">Mes clients</h5>
                  <h3 class="fw-bold">{{ $nombreclient  }}</h3>
              </div>
          </div>
      </div>
      <div class="col-md-3">
          <div class="card shadow-sm border-0 stat-card h-100">
              <div class="card-body text-center">
                  <div class="icon-circle bg-success mb-3">
                      <i class="bi bi-file-earmark-text text-white"></i>
                  </div>
                  <h5 class="card-title">Mes biens sur le marchés</h5>
                  <h3 class="fw-bold">{{ $nombreBiens }}</h3>
              </div>
          </div>
      </div>
      <div class="col-md-3">
          <div class="card shadow-sm border-0 stat-card h-100">
              <div class="card-body text-center">
                  <div class="icon-circle bg-info mb-3">
                      <i class="bi bi-person text-white"></i>
                  </div>
                  <h5 class="card-title">Biens déjà vendus</h5>
                  <h3 class="fw-bold">{{ $nombreBiensVendus }}</h3>
              </div>
          </div>
      </div>
      <div class="col-md-3">
          <div class="card shadow-sm border-0 stat-card h-100">
              <div class="card-body text-center">
                  <div class="icon-circle bg-warning mb-3">
                      <i class="bi bi-currency-dollar text-white"></i>
                  </div>
                  <h5 class="card-title">Transactions</h5>
                  <h3 class="fw-bold">{{ $nombreTransactions }}</h3>
              </div>
          </div>
      </div>
  </div>

   {{-- Graphique des montants encaissés par mois --}}
  <div class="row g-4 my-4">
      <div class="col-12">
          <div class="card shadow-sm border-0 h-100">
              <div class="card-header bg-light fw-bold">Montant encaissés par mois ({{ now()->year }})</div>
              <div class="card-body">
                  <canvas id="loyersChart" height="50"></canvas>
              </div>
          </div>
      </div>
  </div>
  
   {{-- paiement récentes --}}
  <div class="row g-4">
      <div class="col-md-6">
          <div class="card shadow-sm border-0 h-100">
              <div class="card-header bg-light d-flex justify-content-between align-items-center">
                  <span class="fw-bold">Achat de biens récents</span>
                  @if($transactionsRecentes->count() >= 5)
                      <div class="mt-2 text-end">
                          <button class="btn btn-sm btn-outline-primary" data-bs-toggle="modal" data-bs-target="#paiementsModal"><i class="bi bi-eye"></i>&nbsp;Voir plus</button>
                      </div>
                  @endif
              </div>
              <div class="card-body">
                  @if($transactionsRecentes->isEmpty())
                      <center>
                          <div class="empty-state">
                              <img src="https://cdn-icons-png.flaticon.com/512/4076/4076549.png" alt="Aucune transaction" class="empty-img">
                              <p>Aucun paiement récent</p>
                          </div>
                      </center>
                  @else
                      <ul class="list-group list-group-flush">
                          @foreach($transactionsRecentes as $paiement)
                              <li class="list-group-item d-flex justify-content-between align-items-center">
                                  {{ $paiement->attribution->client->name ?? '—' }} - {{ number_format($paiement->montant, 0, ',', ' ') }} FCFA
                                  <span class="badge bg-primary">{{ $paiement->created_at->format('d/m/Y') }}</span>
                              </li>
                          @endforeach
                      </ul>
                  @endif
              </div>                
          </div>
      </div>

      {{-- Attribution récentes --}}
      <div class="col-md-6">
          <div class="card shadow-sm border-0 h-100">
              <div class="card-header bg-light d-flex justify-content-between align-items-center">
                  <span class="fw-bold">Attributions récentes</span>
                  <a href="#" class="text-decoration-none small">
                  @if($attributionsRecentes->count() >= 5)
                      <div class="mt-2 text-end">
                          <button class="btn btn-sm btn-outline-primary" data-bs-toggle="modal" data-bs-target="#attributionsModal"><i class="bi bi-eye"></i>&nbsp;Voir plus</button>
                      </div>
                  @endif
              </div>
              <div class="card-body">
                  @if($attributionsRecentes->isEmpty())
                      <center>
                          <div class="empty-state">
                              <img src="https://cdn-icons-png.flaticon.com/512/4076/4076549.png" alt="Aucun article" class="empty-img">
                              <p>Aucune attribution récente</p>
                          </div>
                      </center>
                  @else
                      <ul class="list-group list-group-flush">
                          @foreach($attributionsRecentes as $attrib)
                              <li class="list-group-item d-flex justify-content-between align-items-center">
                                  <span class="fw-semibold" >{{ $attrib->client->name.' '.$attrib->client->surname ?? '—' }}</span> - <span class="fw-semibold">{{ $attrib->bien->titre ?? 'Bien inconnu' }}</span>
                                  <span class="badge bg-secondary">
                                    {{ $attrib->date_attribution ? $attrib->date_attribution->format('d/m/Y') : '—' }}
                                </span>    
                              </li>
                          @endforeach
                      </ul>
                  @endif
              </div>                
          </div>
      </div>
  </div> 
  
  <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
  <script>
      const ctx = document.getElementById('loyersChart').getContext('2d');
      new Chart(ctx, {
          type: 'line',    // Type du graphique : barres verticales |type : 'bar' => Histogramme et type : 'line' => courbe
          data: {
              labels: @json($moisLabels), // ["Jan", "Feb", ..., "Dec"]
              datasets: [{
                  label: 'Montant des loyers (FCFA)',
                  data: @json($loyersMois),   // [100000, 200000, ...] par mois
                  backgroundColor: '#0f3f58'
              }]
          },
          options: {
              responsive: true,
              plugins: { legend: { display: false } },
              scales: { y: { beginAtZero: true } }
          }
      });
  </script>

<!-- Styles pour effets hover -->
    <style>
      .stat-card {
          transition: transform 0.2s ease, box-shadow 0.2s ease;
      }
      .stat-card:hover {
          transform: translateY(-5px);
          box-shadow: 0 6px 18px rgba(0,0,0,0.1);
      }
      .icon-circle {
          width: 50px;
          height: 50px;
          border-radius: 50%;
          display: flex;
          align-items: center;
          justify-content: center;
          margin: 0 auto;
      }
      .empty-state img {
          width: 100px;
          opacity: 0.6;
      }
    </style>


@endsection


<div class="modal fade" id="paiementsModal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-lg">
      <div class="modal-content">
          <div class="modal-header">
              <h5 class="modal-title">Tous les paiements</h5>
              <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
          </div>
          <div class="modal-body">
              <ul class="list-group list-group-flush">
                  @foreach(\App\Models\Paiement::with('attribution.bien', 'attribution.client')
                      ->whereHas('attribution.bien', fn($q) => $q->where('proprietaire_id', $proprio->id))
                      ->latest()
                      ->get() as $paiement)
                      <li class="list-group-item d-flex justify-content-between align-items-center">
                          {{ $paiement->attribution->client->name ?? '—' }} - {{ number_format($paiement->montant, 0, ',', ' ') }} FCFA
                          <span class="badge bg-primary">{{ $paiement->created_at->format('d/m/Y') }}</span>
                      </li>
                  @endforeach
              </ul>
          </div>
      </div>
  </div>
</div>


<div class="modal fade" id="attributionsModal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-lg">
      <div class="modal-content">
          <div class="modal-header">
              <h5 class="modal-title">Toutes les attributions</h5>
              <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
          </div>
          <div class="modal-body">
              <ul class="list-group list-group-flush">
                  @foreach(\App\Models\Attribution::with(['bien', 'client'])
                      ->whereHas('bien', fn($q) => $q->where('proprietaire_id', $proprio->id))
                      ->latest('date_attribution')
                      ->get() as $attrib)
                      <li class="list-group-item d-flex justify-content-between align-items-center">
                          {{ $attrib->client->name ?? '—' }} - {{ $attrib->bien->titre ?? 'Bien inconnu' }}
                          <span class="badge bg-secondary">
                            {{ $attrib->date_attribution ? $attrib->date_attribution->format('d/m/Y') : '—' }}
                        </span>                            
                      </li>
                  @endforeach
              </ul>
          </div>
      </div>
  </div>
</div>




