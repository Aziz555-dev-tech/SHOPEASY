<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/css/bootstrap.min.css" rel="stylesheet">
<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css"/>
<link rel="stylesheet" href="{{ asset('css/actualite.css') }}">
<link rel="stylesheet" href="{{ asset('css/styleIndex.css') }}">

@extends('layouts.app')

@section('title', 'Toutes les boutiques')

@section('content')

<div class="container py-5">

    <h2 class="fw-bold mb-4 text-center">Toutes les boutiques</h2>

    <div class="row g-4">

        @forelse ($boutiques as $boutique)
            <div class="col-lg-3 col-md-4 col-sm-6">
                <div class="card h-100 shadow-sm border-0 boutique-card">

                    <div class="image-wrapper">
                        <img
                            src="{{ $boutique->logo ? asset('storage/'.$boutique->logo) : asset('assets/images/boutique-default.png') }}"
                            class="card-img-top rounded-top"
                            style="height:300px;width:100%;object-fit:cover;"
                            alt="{{ $boutique->nom }}"
                        >
                
                        {{-- OVERLAY SHARE --}}
                        <div class="overlay-search">

                            <div class="search-icon" onclick="toggleShare(this)">
                                <i class="bi bi-share-fill"></i>
                            </div>
                        
                            <div class="share-menu">
                        
                                <a target="_blank"
                                   href="https://wa.me/?text={{ urlencode($boutique->nom.' '.route('boutiques.show',$boutique->slug)) }}"
                                   class="share-btn whatsapp">
                                    <i class="bi bi-whatsapp"></i>
                                </a>
                        
                                <a target="_blank"
                                   href="https://www.facebook.com/sharer/sharer.php?u={{ route('boutiques.show',$boutique->slug) }}"
                                   class="share-btn facebook">
                                    <i class="bi bi-facebook"></i>
                                </a>
                        
                                <a target="_blank"
                                   href="https://twitter.com/intent/tweet?url={{ route('boutiques.show',$boutique->slug) }}&text={{ $boutique->nom }}"
                                   class="share-btn twitter">
                                    <i class="bi bi-twitter-x"></i>
                                </a>
                        
                            </div>
                        
                        </div>
                        
                    </div>
                    
                    <div class="card-body text-center">
                        <h6 class="fw-bold mb-1">{{ $boutique->nom }}</h6>

                        <small class="text-muted d-block mb-2">
                            {{ $boutique->biens_count }} produit(s)
                        </small>

                        <a href="{{ route('boutiques.show', $boutique->slug) }}"
                           class="btn btn-dark text-warning fw-bold btn-sm">
                           <i class="bi bi-eye text-warning"></i>
                            Voir la boutique
                        </a>
                    </div>

                </div>
            </div>
        @empty
            <div class="col-12 text-center">
                <img src="https://cdn-icons-png.flaticon.com/512/4076/4076549.png" style="width:180px;height:180px;" alt="Aucun article" class="empty-img">
                <p class="text-muted">Aucune boutique disponible.</p>
            </div>
        @endforelse

    </div>

    <div class="mt-4 d-flex justify-content-center">
        {{ $boutiques->links() }}
    </div>

</div>


<style>
        .image-wrapper {
    position: relative;
    overflow: hidden;
}

.overlay-search {
    position: absolute;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background: rgba(0,0,0,0.55);
    display: flex;
    justify-content: center;
    align-items: center;
    flex-direction: column;
    opacity: 0;
    transition: 0.3s ease;
}

.image-wrapper:hover .overlay-search {
    opacity: 1;
}

.search-icon {
    width: 60px;
    height: 60px;
    border-radius: 50%;
    background: white;
    color: black;
    font-size: 26px;
    display: flex;
    justify-content: center;
    align-items: center;
    cursor: pointer;
    transition: 0.2s;
}

.search-icon:hover {
    transform: scale(1.1);
}

.share-menu {
    margin-top: 15px;
    display: none;
    gap: 10px;
}

.share-btn {
    width: 45px;
    height: 45px;
    border-radius: 50%;
    color: white;
    font-size: 20px;
    display: flex;
    justify-content: center;
    align-items: center;
}

.image-wrapper {
    position: relative;
    overflow: hidden;
}

.image-wrapper img {
    position: relative;
    z-index: 1;
}

.overlay-search {
    position: absolute;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background: rgba(0,0,0,0.55);

    display: flex;
    justify-content: center;
    align-items: center;
    flex-direction: column;

    opacity: 0;
    transition: 0.3s ease;

    z-index: 2; /* IMPORTANT */
}

.image-wrapper:hover .overlay-search {
    opacity: 1;
}


.whatsapp { background: #25D366; }
.facebook { background: #1877F2; }
.twitter { background: #000; }

</style>

<script>
    function toggleShare(el) {
        const menu = el.nextElementSibling;
        menu.style.display = menu.style.display === "flex" ? "none" : "flex";
    }
</script>

@endsection
