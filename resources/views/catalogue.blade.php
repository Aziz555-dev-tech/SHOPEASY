@extends('layouts.app')

@section('title', 'Catalogue de Biens')

@section('content')

<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css"/>


<section class="hero text-center py-3">
    <h1 class="fw-bold animate__animated animate__fadeInDown pt-5 mb-0">Découvrez nos biens de luxe</h1>
    <p class="animate__animated animate__fadeInUp mb-0">Recherchez et filtrez vos biens pour en savoir plus...</p>
    <div class="container mt-4">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="search-box position-relative">
                    <i class="bi bi-search position-absolute top-50 translate-middle-y ms-3"></i>
                    <input type="text" id="searchInput" placeholder="Rechercher un bien..." class="form-control ps-5 rounded-pill chic-input">
                </div>
            </div>
        </div>
    </div>
</section>

<section class="filtres py-5">
    <div class="container">
        <div class="card shadow-sm border-0 rounded-4 p-3 mb-4">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <h6><a href="{{ route('catalogue') }}" class="btn btn-sm btn-dark text-warning fw-semibold rounded rounded-3">Tout les produits tendances</a></h6>
                <div class="btn-group" role="group">
                    <button type="button" class="btn btn-warning text-dark rounded rounded-pill" id="btn-panier"><i class="bi bi-cart-plus-fill"></i></button>&nbsp;&nbsp;&nbsp;
                    <button type="button" class="btn btn-outline-dark text-warning active" id="btn-grid"><i class="bi bi-grid-3x3-gap-fill"></i></button>
                    <button type="button" class="btn btn-outline-dark text-warning" id="btn-list"><i class="bi bi-list-ul"></i></button>
                </div>
            </div>
        </div>

        <div class="alert alert-info" id="cart-summary">
            <strong>Panier :</strong> Aucun article sélectionné.
        </div>
     
        
        <div class="d-flex flex-column flex-md-row gap-2 py-3">

            {{-- fedapay --}}
            <form action="{{ route('client.fedapay.panier.initier') }}" method="POST" id="fedapay-btn" >
                @csrf
            
                <!-- Tous les articles du panier en JSON -->
                <input type="hidden" name="cart_data" id="cart-data-fedapay">
            
                <button type="submit"
                    class="btn btn-sm btn-primary fw-bold rounded-pill d-flex align-items-center justify-content-center py-2 px-3">
                    <i class="bi bi-phone fw-bold text-light"></i>&nbsp;&nbsp;
                    <span class="fw-bold fs-6 text-light">Mobile Money</span>
                </button>
            </form>

            <form id="btn-virement">
                <button type="button" 
                    class="btn btn-sm btn-dark fw-bold rounded-pill d-flex align-items-center justify-content-center py-2 px-3"
                    data-bs-toggle="modal"
                    data-bs-target="#virementModal">
                    <i class="bi bi-wallet-fill text-warning"></i>&nbsp;&nbsp;
                    <span class="fw-bold fs-6 text-warning">Virement bancaire</span>
                </button>
            </form>
            
        </div>

        <div class="modal fade modal-lg" id="virementModal" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
              <div class="modal-content rounded-4">
                <div class="modal-header bg-dark fw-semibold text-warning">
                  <h5 class="modal-title">
                    <img src="{{ asset('assets/images/logo_sahashop.png') }}" width="30px;height:30px;" alt="">
                    Virement Bancaire pour SHOPEASY
                  </h5>
                  <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Fermer"></button>
                </div>
                <div class="modal-body">
                  <p>Pour effectuer votre virement, utilisez les informations bancaires suivantes :</p>
                  <ul>
                    <li>Banque : Bank Of Africa </li>
                    <li>Nom du compte : Shopeasy SARL</li>
                    <li>Numéro de compte : 1234567890</li>
                    <li>Code SWIFT : XYZABCD</li>
                  </ul>
                  <p>Après votre virement, veuillez envoyer la preuve de paiement à notre service client.</p>
                </div>
              </div>
            </div>
        </div>
              
        @if(session('success'))
            <div class="alert alert-success">
                {{ session('success') }}
            </div>
        @endif

        @if(session('error'))
            <div class="alert alert-danger">
                {{ session('error') }}
            </div>
        @endif

        @if($errors->any())
            <div class="alert alert-danger">
                <ul class="mb-0">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <div id="contentArea" class="row g-4">
          @forelse($biens as $bien)
                {{-- Vue CARD --}}
                <div class="col-md-3 col-sm-6 mix {{ $bien->categorie }} animate__animated animate__fadeIn bien-grid" data-title="{{ strtolower($bien->titre) }}">
                    <div class="card shadow-sm border-0 rounded-4">
                        <div class="row card-media g-1 p-2">
                            @php
                                $medias = $bien->medias ?? collect();
                                $mediasPreview = $medias->take(4);
                            @endphp
    

                            @if($bien->image)
                                <img src="{{ asset('storage/'.$bien->image) }}" 
                                    class="card-img-top rounded-top" 
                                    alt="Image du bien" 
                                    style="height:200px; object-fit:cover; width:100%;">
                            @elseif($mediasPreview->count() > 0)
                                @foreach($mediasPreview as $media)
                                    <div class="col-6">
                                        @if($media->type === 'image')
                                            <img src="{{ asset('storage/' . $media->path) }}" 
                                                class="img-fluid rounded shadow-sm"
                                                style="height:100px; object-fit:cover; width:100%;">
                                        @elseif($media->type === 'video')
                                            <video class="img-fluid rounded shadow-sm" 
                                                style="height:100px; object-fit:cover; width:100%;" muted>
                                                <source src="{{ asset('storage/' . $media->path) }}" type="video/mp4">
                                            </video>
                                        @endif
                                    </div>
                                @endforeach
                                @if($medias->count() > 4)
                                    <div class="col-6 d-flex align-items-center justify-content-center bg-dark text-white rounded" style="height:100px;">
                                        +{{ $medias->count() - 4 }}
                                    </div>
                                @endif
                            @else
                                <img src="https://via.placeholder.com/400x250?text=Aucune+image" style="width: 150px;height=150px;"
                                    class="card-img-top rounded-top" 
                                    alt="Aucune image">
                            @endif
                        </div>

                        <div class="card-body">
                            <h5 class="card-title text-dark">{{ $bien->titre }}</h5>
                            <p class="card-text text-muted mb-0">
                                <span class="badge bg-dark text-warning fw-semibold">{{ ucfirst($bien->categorie?->name ?? '-') }}</span>
                            </p>
                            <p class="fw-semibold">
                                <span class="fw-bold"><i class="bi bi-cash-coin"></i> {{ number_format($bien->prix, 0, ',', ' ') }} FCFA</span>
                                <br>
                                <i class="bi bi-shop"></i> Boutique : {{ $bien->proprietaire->name }}
                                <br>
                                @if ($bien->stock >= 5)
                                    <i class="bi bi-bag-check-fill"></i> Stocke : <span class="text-success">{{ $bien->stock }} disponibles</span>
                                @elseif ($bien->stock <= 1)
                                    <i class="bi bi-bag-check-fill"></i> Stocke : <span class="text-danger">{{ $bien->stock }} disponible</span>
                                @else
                                <i class="bi bi-bag-check-fill"></i> Stocke : <span class="text-danger">{{ $bien->stock }} disponibles</span>
                                @endif                                
                            </p>
                            
                            <div class="mt-auto d-flex justify-content-between gap-1">

                                <div class="cart-action" data-id="{{ $bien->id }}">
                                    <button 
                                        class="btn btn-warning btn-sm add-to-cart-btn"
                                        data-id="{{ $bien->id }}"
                                        data-title="{{ $bien->titre }}"
                                        data-price="{{ $bien->prix }}"
                                        data-stock="{{ $bien->stock }}">
                                        <i class="bi bi-cart-plus"></i> Panier
                                    </button>
                                
                                
                                    <div class="qty-box d-none align-items-center gap-2">
                                        <button class="btn btn-dark btn-sm qty-minus">−</button>
                                        <span class="qty-value fw-bold">1</span>
                                        <button class="btn btn-dark btn-sm qty-plus">+</button>
                                    </div>
                                </div>
                                
                            
                                <button class="btn btn-outline-info rounded rounded-2 btn-sm flex-fill" data-bs-toggle="modal" data-bs-target="#bienModal{{ $bien->id }}">
                                    <i class="bi bi-eye-fill"></i> Voir
                                </button>
                            </div>                            

                        </div>
                    </div>
                </div>

                {{-- Vue LISTE --}}
                <div class="col-12 bien-list d-none mix {{ $bien->categorie }} animate__animated animate__fadeIn" data-title="{{ strtolower($bien->titre) }}">
                    <div class="d-flex justify-content-between align-items-center p-3 mb-3 shadow-sm rounded-4 bg-white">
                        <div>
                            <strong>{{ $bien->titre }}</strong><br>
                            <small class="text-muted">
                                <i class="bi bi-cash-coin"></i> {{ ucfirst($bien->categorie?->name ?? '-') }} - {{ ucfirst($bien->description) }}
                            </small>

                            @if ($bien->stock >= 5)
                                <i class="bi bi-bag-check-fill"></i> Stocke : <span class="text-success">{{ $bien->stock }} disponibles</span>
                            @elseif ($bien->stock <= 1)
                                <i class="bi bi-bag-check-fill"></i> Stocke : <span class="text-danger">{{ $bien->stock }} disponible</span>
                            @else
                            <i class="bi bi-bag-check-fill"></i> Stocke : <span class="text-danger">{{ $bien->stock }} disponibles</span>
                            @endif    

                        </div>

                        <div class="d-flex align-items-center gap-1">

                            <div class="mt-auto d-flex justify-content-between gap-1">
                                <div class="cart-action" data-id="{{ $bien->id }}">
                                    <button 
                                        class="btn btn-warning btn-sm add-to-cart-btn"
                                        data-id="{{ $bien->id }}"
                                        data-title="{{ $bien->titre }}"
                                        data-price="{{ $bien->prix }}"
                                        data-stock="{{ $bien->stock }}">
                                        <i class="bi bi-cart-plus"></i> Panier
                                    </button>
                                
                                
                                    <div class="qty-box d-none align-items-center gap-2">
                                        <button class="btn btn-dark btn-sm qty-minus">−</button>
                                        <span class="qty-value fw-bold">1</span>
                                        <button class="btn btn-dark btn-sm qty-plus">+</button>
                                    </div>
                                </div>
                                
                            
                                <button class="btn btn-outline-info rounded rounded-2 btn-sm flex-fill" data-bs-toggle="modal" data-bs-target="#bienModal{{ $bien->id }}">
                                    <i class="bi bi-eye-fill"></i> Voir
                                </button>
                            </div>  

                        </div>
                    </div>
                </div>
          @empty
              <div class="col-12 text-center">
                <img src="https://cdn-icons-png.flaticon.com/512/4076/4076549.png" style="width:180px;height:180px;" alt="Aucun article" class="empty-img">
                <p class="text-muted">Aucun bien disponible.</p>
              </div>
          @endforelse
        </div>
    </div>

    <div class="container mt-4">
        <div class="d-flex justify-content-center mt-4 custom-pagination">
            {{ $biens->links('pagination::bootstrap-5') }}
        </div>        
    </div>
</section>


@if(session('clear_cart'))
    {{-- Supression du localeStorage après paiement réussi --}}
    <script>
        localStorage.removeItem("cart");
        location.reload(); // recharge avec stock à jour
    </script>
@endif



<script>    // Gesion CARD + LISTE 

    const items = document.querySelectorAll('.mix');

    // Recherche par titre
    const searchInput = document.getElementById('searchInput');
    searchInput.addEventListener('input', () => {
        const value = searchInput.value.toLowerCase();
        items.forEach(item => {
            const title = item.getAttribute('data-title');
            item.style.display = title.includes(value) ? '' : 'none';
        });
    });

    // Switch Grid/List
    const btnGrid = document.getElementById('btn-grid');
    const btnList = document.getElementById('btn-list');
    const gridItems = document.querySelectorAll('.bien-grid');
    const listItems = document.querySelectorAll('.bien-list');

    btnGrid.addEventListener('click', () => {
        btnGrid.classList.add('active');
        btnList.classList.remove('active');
        gridItems.forEach(item => item.classList.remove('d-none'));
        listItems.forEach(item => item.classList.add('d-none'));
    });

    btnList.addEventListener('click', () => {
        btnList.classList.add('active');
        btnGrid.classList.remove('active');
        gridItems.forEach(item => item.classList.add('d-none'));
        listItems.forEach(item => item.classList.remove('d-none'));
    });

</script>

<script>
    let cart = JSON.parse(localStorage.getItem("cart")) || [];
    
    const cartSummary = document.getElementById('cart-summary');
    const fedapayInput = document.getElementById('cart-data-fedapay');
    
    function updateCartUI() {

        if (cart.length === 0) {
            cartSummary.innerHTML = "<strong>Panier :</strong> Aucun article sélectionné.";

            document.querySelectorAll(
                '#fedapay-btn, #btn-virement'
            ).forEach(el => el.style.display = 'none');

            return;
        }

        let total = cart.reduce((sum, item) => sum + (item.price * item.qte), 0);

        let names = cart.map(item =>
            `• ${item.title} (x${item.qte})`
        ).join("<br>");

        cartSummary.innerHTML = `
            <strong>Articles :</strong><br>
            ${names}
            <hr>
            <div class="d-flex justify-content-between align-items-center">
                <strong>Total :</strong>
                <span>${total.toLocaleString()} FCFA</span>
            </div>
            <div class="d-flex justify-content-end mt-2">
                <button id="clear-cart-btn" class="btn btn-sm btn-outline-danger rounded-pill">
                    <i class="bi bi-trash-fill"></i> Vider le panier
                </button>
            </div>
        `;

        // Animation ici
        cartSummary.classList.remove('animate__pulse');
        void cartSummary.offsetWidth; // reset animation
        cartSummary.classList.add('animate__animated', 'animate__pulse');

        document.querySelectorAll('#fedapay-btn, #btn-virement').forEach(el => el.style.display = '');

        fedapayInput.value = JSON.stringify(cart);

        localStorage.setItem("cart", JSON.stringify(cart));

        bindClearCartBtn(); // important
    }

    function bindClearCartBtn() {
        const btn = document.getElementById('clear-cart-btn');
        if (!btn) return;

        btn.addEventListener('click', () => {
            if (confirm("Voulez-vous vraiment vider le panier ?")) {
                clearCart();
            }
        });
    }

    function clearCart() {
        cart = [];
        localStorage.removeItem("cart");

        // Reset UI
        document.querySelectorAll('.cart-action').forEach(wrapper => {
            wrapper.querySelector('.add-to-cart-btn')?.classList.remove('d-none');
            wrapper.querySelector('.qty-box')?.classList.add('d-none');
            wrapper.querySelector('.qty-value').textContent = 1;
        });

        updateCartUI();
    }

    function syncUIWithCart() {
        document.querySelectorAll('.cart-action').forEach(wrapper => {
            const addBtn   = wrapper.querySelector('.add-to-cart-btn');
            const qtyBox   = wrapper.querySelector('.qty-box');
            const qtyValue = wrapper.querySelector('.qty-value');
            const plus     = wrapper.querySelector('.qty-plus');

            const id    = addBtn.dataset.id;
            const stock = parseInt(addBtn.dataset.stock);

            const item = cart.find(i => i.id === id);

            if (item) {
                addBtn.classList.add('d-none');
                qtyBox.classList.remove('d-none');
                qtyValue.textContent = item.qte;

                // verrou stock après refresh
                plus.disabled = (item.qte >= stock);
            } else {
                addBtn.classList.remove('d-none');
                qtyBox.classList.add('d-none');
                qtyValue.textContent = 1;
                plus.disabled = false;
            }
        });
    }


    document.querySelectorAll('.cart-action').forEach(wrapper => {

        const addBtn   = wrapper.querySelector('.add-to-cart-btn');
        const qtyBox   = wrapper.querySelector('.qty-box');
        const minus    = wrapper.querySelector('.qty-minus');
        const plus     = wrapper.querySelector('.qty-plus');
        const qtyValue = wrapper.querySelector('.qty-value');

        const id    = addBtn.dataset.id;
        const title = addBtn.dataset.title;
        const price = parseInt(addBtn.dataset.price);
        const stock = parseInt(addBtn.dataset.stock);

        // AJOUT AU PANIER
        addBtn.addEventListener('click', () => {

            if (stock <= 0) {
                alert("Produit en rupture de stock");
                return;
            }

            cart.push({ id, title, price, qte: 1, stock });

            addBtn.classList.add('d-none');
            qtyBox.classList.remove('d-none');
            qtyValue.textContent = 1;

            plus.disabled = (1 >= stock);

            updateCartUI();
        });

        // ➕ AUGMENTER QUANTITÉ
        plus.addEventListener('click', () => {

            const item = cart.find(i => i.id === id);
            if (!item) return;

            if (item.qte >= item.stock) {
                cartSummary.classList.remove('animate__shakeX');
                void cartSummary.offsetWidth;
                cartSummary.classList.add('animate__animated', 'animate__shakeX');
                return;
            }

            item.qte++;
            qtyValue.textContent = item.qte;

            plus.disabled = (item.qte >= item.stock);

            updateCartUI();
        });

        //DIMINUER QUANTITÉ
        minus.addEventListener('click', () => {

            const item = cart.find(i => i.id === id);
            if (!item) return;

            item.qte--;

            if (item.qte <= 0) {
                cart = cart.filter(i => i.id !== id);
                qtyBox.classList.add('d-none');
                addBtn.classList.remove('d-none');
                qtyValue.textContent = 1;
            } else {
                qtyValue.textContent = item.qte;
            }

            plus.disabled = false;
            updateCartUI();
        });
    });

    syncUIWithCart();
    updateCartUI();
    
</script>
    
 
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

<style>

    /* Pousser la modale vers le bas*/

    .modal-dialog {
        margin-top: 100px !important; /* Ajuste selon la hauteur de ta navbar */
    }

    .card-media {
        height: 320px;
        overflow: hidden;
    }

    .card-media img,
    .card-media video {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }


    /*Bouton peronalisé, style shopeasy */

    .btn-custom {
        background-color: #005078;
        color: white;
        border-radius: 10px;
    }
    .btn-custom:hover {
        background-color: #003d5c;
        color: white;
    }
    .bg-custom { background-color: #005078 !important; }

    /*pagination */

    /* Container pagination */
        .custom-pagination .page-item {
            margin: 0 5px !important; /* espace entre les cercles */
        }

        /* Boutons */
        .custom-pagination .page-link {
            background: #000;               /* noir */
            color: #FFD700;                 /* or */
            border-radius: 50% !important;  /* cercle */
            width: 45px;
            height: 45px;
            line-height: 45px;
            text-align: center;
            padding: 0;
            border: 2px solid #FFD700;      /* bordure dorée */
            font-weight: bold;
            transition: 0.3s ease;
            box-shadow: 0 0 10px rgba(255, 215, 0, 0.4);
        }

        

        /* Hover */
        .custom-pagination .page-link:hover {
            background: #FFD700 !important;
            color: #000 !important;         /* inverser noir ⇄ or */
            border-color: #FFD700;
        }

        /* Page active */
        .custom-pagination .active .page-link {
            background: #FFD700 !important;
            color: #000 !important;
            border-color: #FFD700;
        }

        /* Désactivé */
        .custom-pagination .disabled .page-link {
            opacity: 0.5;
        }

        .pagination-status, .pagination .small, .text-muted {
            display: none !important;
        }

        /* Responsive */
        @media (max-width: 576px) {
            .custom-pagination .page-link {
                width: 38px;
                height: 38px;
                line-height: 38px;
                font-size: 0.85rem;
            }
        }

        /* Barre de recherche chic */
        .search-box input#searchInput {
            background-color: #000 !important;      /* fond noir */
            color: #FFD700 !important;              /* texte doré */
            border: 2px solid #FFD700 !important;   /* bordure dorée */
            padding: 12px 20px 12px 40px;           /* espace pour l'icône */
            border-radius: 50px;                     /* arrondi complet */
            font-weight: bold;
            transition: 0.3s ease;
            box-shadow: 0 0 15px rgba(255, 215, 0, 0.3);
        }

        /* Focus */
        .search-box input#searchInput:focus {
            outline: none;
            background-color: #111;                 /* légèrement plus clair au focus */
            box-shadow: 0 0 20px rgba(255, 215, 0, 0.6);
        }

        /* Icône de recherche */
        .search-box .bi-search {
            color: #FFD700;
            font-size: 1.2rem;
        }

        /* Placeholder doré */
        .search-box input::placeholder {
            color: #FFD700;
            opacity: 0.7;
        }

        .search-box {
            position: relative;
        }

        /* Icône de recherche */
        .search-box .bi-search {
            position: absolute;
            top: 50%;             /* centre verticalement */
            left: 10px;           /* distance du bord gauche */
            transform: translateY(-50%); /* correction pour centrer parfaitement */
            color: #FFD700;
            font-size: 1.2rem;
            pointer-events: none; /* pour que le clic sur l'icône focus l'input */
        }

        /* Input : ajouter du padding à gauche pour laisser de la place à l'icône */
        .search-box input#searchInput {
            padding-left: 45px; /* ajuster selon la taille de l'icône */
        }

        .search-box input#searchInput {
            width: 100%;
            max-width: 100%;
        }

        .add-to-cart-btn {
            transition: 0.3s ease;
        }

        .add-to-cart-btn:active {
            transform: scale(0.9);
        }

        .header-gold {
            background-color: #D4AF37;
            color: white;
        }

        .qty-box {
            background: #000;
            border: 1px solid #FFD700;
            border-radius: 30px;
            padding: 5px 10px;
        }

        .qty-box button {
            border-radius: 50%;
            width: 32px;
            height: 32px;
            font-weight: bold;
        }

        .qty-value {
            color: #FFD700;
            min-width: 20px;
            text-align: center;
        }

        #clear-cart-btn {
            border-width: 2px;
            font-weight: bold;
            transition: 0.3s ease;
        }

        #clear-cart-btn:hover {
            background: #dc3545;
            color: #fff;
        }




</style>


@foreach($biens as $bien)
    @include('admin.biens.partials._show_modal', ['bien' => $bien])
@endforeach

@endsection
