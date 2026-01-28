<div class="modal fade" id="bienModal{{ $bien->id }}" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
      <div class="modal-content shadow-lg border-0 rounded-3">
        
        <!-- Header -->
        <div class="modal-header bg-primary text-white">
          <h5 class="modal-title">Détails du Bien : {{ $bien->titre }}</h5>
          <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
        </div>
        
        <!-- Body -->
        <div class="modal-body">
          
          <!-- Carousel -->
          <div id="carouselBien{{ $bien->id }}" class="carousel slide" data-bs-ride="carousel">
            <div class="carousel-inner">
  
              @php $first = true; @endphp
  
              <!-- Image principale -->
              @if($bien->image)
                <div class="carousel-item active">
                  <img src="{{ asset('storage/'.$bien->image) }}"
                       class="d-block w-100 rounded"
                       style="max-height:400px; object-fit:cover;">
                </div>
                @php $first = false; @endphp
              @endif
  
              <!-- Médias -->
              @if($bien->medias)
                @foreach($bien->medias as $media)
                  <div class="carousel-item {{ $first ? 'active' : '' }}">
                    @if($media->type === 'image')
                      <img src="{{ asset('storage/' . $media->path) }}" 
                           class="d-block w-100 rounded" 
                           style="max-height:400px; object-fit:cover;">
                    @elseif($media->type === 'video')
                    <video class="d-block w-100 rounded" 
                          style="max-height:400px; object-fit:cover;" 
                          controls 
                          preload="metadata" 
                          playsinline>
                    <source src="{{ asset('storage/' . $media->path) }}" type="video/mp4">
                    Votre navigateur ne supporte pas la vidéo.
                  </video>           
                   @endif
                  </div>
                  @php $first = false; @endphp
                @endforeach
              @endif
  
            </div>
  
            <!-- Controls -->
            <button class="carousel-control-prev" type="button" data-bs-target="#carouselBien{{ $bien->id }}" data-bs-slide="prev">
              <span class="carousel-control-prev-icon"></span>
            </button>
            <button class="carousel-control-next" type="button" data-bs-target="#carouselBien{{ $bien->id }}" data-bs-slide="next">
              <span class="carousel-control-next-icon"></span>
            </button>
          </div>
  
          <!-- Infos du bien -->
          <div class="mt-4">
            <h5 class="card-title">{{ $bien->titre }}</h5>
            <p class="card-text">{{ $bien->description }}</p>
            <p class=""><strong>Catégorie: </strong>{{ $bien->categorie->name ?? '' }}</p>
            <p><strong>Adresse :</strong> {{ $bien->adresse }}</p>
            <p><strong>Type :</strong> {{ ucfirst($bien->type) }}</p>
            <p><strong>Prix :</strong> {{ number_format($bien->prix, 2, ',', ' ') }} FCFA</p>
            <p><strong>Propriétaire :</strong> {{ $bien->proprietaire->name }} {{ $bien->proprietaire->surname ?? '—' }}</p>
            <p><strong>Date d’ajout :</strong> {{ $bien->created_at->format('d/m/Y H:i') }}</p>
          </div>
        </div>
  
        <!-- Footer -->
        <div class="modal-footer">
          <button class="btn btn-secondary" data-bs-dismiss="modal">Fermer</button>
        </div>
      </div>
    </div>
  </div>
  
  <!-- Script pour gérer les vidéos dans le carousel -->
  <script>
  document.addEventListener('DOMContentLoaded', function () {
      var carousels = document.querySelectorAll('.carousel');
  
      carousels.forEach(function(carousel) {
          carousel.querySelectorAll('.carousel-item').forEach(function(item, idx) {
              if (!item.classList.contains('active')) {
                  item.style.visibility = 'hidden';
              }
          });
  
          carousel.addEventListener('slide.bs.carousel', function(e) {
              carousel.querySelectorAll('.carousel-item').forEach(function(item) {
                  item.style.visibility = 'hidden';
                  item.querySelectorAll('video').forEach(v => v.pause());
              });
              e.relatedTarget.style.visibility = 'visible';
          });
      });
  });
  
  </script>
  