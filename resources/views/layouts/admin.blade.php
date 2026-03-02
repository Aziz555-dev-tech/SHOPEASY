<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <meta name="csrf-token" content="{{ csrf_token() }}">
  <title>ShopEasy | Admin - @yield('title')</title>

  <link rel="icon" href="{{ asset('assets/images/logo_sahashop.png') }}">

  {{-- AOS --}}
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/aos@2.3.4/dist/aos.css" />
  <!-- Bootstrap -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600;700&display=swap" rel="stylesheet">

  <style>
      :root{
      --yellow-main: #ffda45;
      --yellow-hover: #ffc933;
      --purple-main: #8a5cf6;
      --pink-main: #ff66b3;
      --blue-main: #4daafc;
      --bg-100: #f9fafc;
      --panel: #ffffff;
      --muted: #6b7280;

      --gradient-sidebar: linear-gradient(180deg, #0d2847, #153b57, #1d4e89);
      --gradient-soft: linear-gradient(180deg, #ffffff, #f6f7fb);
      --glow: 0 8px 22px rgba(138, 92, 246, .18);
    }

    /* GLOBAL */
    body{
      font-family: 'Poppins', sans-serif;
      background: linear-gradient(160deg, #eef2f6, #f8f9fc, #f7f8fd);
      margin:0; color:#1f1f1f;
    }

    /* -------------- SIDEBAR ---------------- */
    .admin-sidebar{
      position:fixed; top:0; left:0; bottom:0;
      width:250px;
      background: var(--gradient-sidebar);
      padding:24px 16px;
      display:flex; flex-direction:column;
      border-right:1px solid rgba(255,255,255,0.12);
      box-shadow:0 8px 30px rgba(0,0,0,0.28);
      border-radius:0 16px 16px 0;
      transition:.25s ease;
    }

    /* Sidebar compact */
    .admin-sidebar.compact{
      width:80px;
    }

    .admin-sidebar .brand{
      display:flex; align-items:center; gap:14px;
      padding-bottom:14px;
      border-bottom:1px solid rgba(255,255,255,0.12);
      margin-bottom:12px;
    }

    .admin-sidebar .brand img{
      width:50px; height:50px;
      border-radius:14px;
      box-shadow:0 8px 22px rgba(0,0,0,0.35);
    }

    .admin-sidebar .brand h4{
      color:#ffda45;
      font-size:19px; font-weight:700; margin:0;
    }

    /* Nav links */
    .nav-link-admin{
      display:flex; align-items:center; gap:14px;
      color:#e8eef5;
      padding:12px 12px;
      border-radius:12px;
      text-decoration:none;
      font-weight:600;
      transition:all .25s ease;
    }

    .nav-link-admin:hover{
      background:rgba(255,255,255,0.18);
      transform:translateX(8px) scale(1.02);
      box-shadow:0 6px 18px rgba(255,255,255,0.15);
    }

    .nav-link-admin.active{
      background:linear-gradient(90deg, #ffda45, #ffc933);
      color:#000;
      box-shadow:0 6px 22px rgba(255,217,86,0.25);
      transform:translateX(8px) scale(1.02);
    }

    /* -------------- TOPBAR ---------------- */
    .topbar{
      position:sticky;
      top:0;
      z-index:1030;
      margin-left:250px;
      padding:12px 22px;
      background:rgba(255,255,255,0.85);
      backdrop-filter:blur(14px);
      border-bottom:1px solid rgba(0,0,0,0.06);
      display:flex; align-items:center; justify-content:space-between;
      transition:.25s ease;
    }

    .topbar.compact{
      margin-left:80px;
    }

    /* Profile */
    .avatar-sm{
      width:42px; height:42px;
      border-radius:12px;
      object-fit:cover;
      box-shadow:var(--glow);
    }

    /* -------------- MAIN ---------------- */
    .main-content{
      margin-left:250px;
      padding:30px;
      min-height:calc(100vh - 60px);
      transition:.25s ease;
    }

    .main-content.compact{
      margin-left:80px;
    }

    /* Cards avec effet glossy */
    .card-soft{
      background:var(--gradient-soft);
      border-radius:20px;
      padding:26px;
      box-shadow:0 10px 26px rgba(0,0,0,0.05),
                0 8px 18px rgba(138,92,246,0.06);
      border:1px solid rgba(0,0,0,0.03);
      transition:.25s ease;
    }

    .card-soft:hover{
      box-shadow:0 16px 32px rgba(0,0,0,0.06),
                0 12px 24px rgba(138,92,246,0.12);
      transform:scale(1.01);
    }

    /* Petite pastilles pastel */
    .badge-dot{
      width:10px; height:10px;
      border-radius:50%;
      background:#ff66b3;
      box-shadow:0 4px 12px rgba(255,102,179,0.45);
    }

    /* -------------- MOBILE ---------------- */
    @media(max-width:992px){
      .admin-sidebar{
        transform:translateX(-120%);
      }
      .admin-sidebar.hidden{
        transform:translateX(-120%);
      }
      .admin-sidebar:not(.hidden){
        transform:translateX(0);
      }
      .topbar,
      .main-content{
          margin-left: 0 !important;   /* ❗ Correction principale */
      }
      .admin-sidebar{
        z-index: 9999;   /* Très important */
    }
    }


    /* Cacher les labels lorsque la sidebar est compacte */
    .admin-sidebar.compact .nav-label {
      display: none;
    }

    /* Centrer les icônes dans le mode compact */
    .admin-sidebar.compact .nav-link-admin {
      justify-content: center;
      padding: 12px 0;
    }

    /* Ajuster la hauteur de la zone "brand" en mode compact */
    .admin-sidebar.compact .brand h4 {
      display: none;
    }

    .admin-sidebar.compact .brand {
      justify-content: center;
    }

    .admin-sidebar.compact .brand img {
      width: 40px;
      height: 40px;
    }


    .modal-xl {
        max-width: 90vw;
    }

    /* Empêche le clignotement au chargement */
    .carousel-inner .carousel-item {
        visibility: hidden;
    }

    .carousel-inner .carousel-item.active {
        visibility: visible;
    }




  </style>
</head>
<body>

  <!-- SIDEBAR -->
  <aside id="adminSidebar" class="admin-sidebar" role="navigation" aria-label="Barre latérale principale">


    <div class="brand">
      <img src="{{ asset('assets/images/logo_sahashop.png') }}" alt="logo">
      <h4>ShopEasy</h4>
    </div>

    <nav class="mt-2 d-flex flex-column" aria-label="Menu principal">
      <a href="{{ route('admin.dashboard.index') }}" class="nav-link-admin {{ request()->routeIs('admin.dashboard.*') ? 'active' : '' }}">
        <i class="bi bi-speedometer2"></i><span class="nav-label">Tableau de bord</span>
      </a>

      <a href="{{ route('admin.users.index') }}" class="nav-link-admin {{ request()->routeIs('admin.users.index*') ? 'active' : '' }}">
        <i class="bi bi-people"></i><span class="nav-label">Utilisateurs</span>
      </a>

      <a href="{{ route('admin.biens.index') }}" class="nav-link-admin {{ request()->routeIs('admin.biens.*') ? 'active' : '' }}">
        <i class="bi bi-building"></i><span class="nav-label">Biens</span>
      </a>

      <a href="{{ route('admin.boutiques.index') }}" class="nav-link-admin {{ request()->routeIs('admin.boutiques.*') ? 'active' : '' }}">
        <i class="bi bi-shop"></i>Boutiques</span>
      </a>

      <a href="{{ route('admin.attributions.index') }}" class="nav-link-admin {{ request()->routeIs('admin.attributions.*') ? 'active' : '' }}">
        <i class="bi bi-house-add"></i> <span class="nav-label">Attributions</span>
      </a>

      <a href="{{ route('admin.paiements') }}" class="nav-link-admin {{ request()->routeIs('admin.paiements') }}">
        <i class="bi bi-currency-dollar"></i><span class="nav-label">Paiements</span>
      </a>

      {{-- <a href="{{route('admin.livreur.localisation') }}" class="nav-link-admin {{ request()->routeIs('admin.livreur.localisation') ? 'active' : '' }}">
        <i class="bi bi-geo-fill"></i><span class="nav-label">Localisation</span>
      </a> --}}

      <a href="{{ route('admin.posts.index') }}" class="nav-link-admin {{ request()->routeIs('admin.posts.index') ? 'active' : '' }}">
        <i class="bi bi-stickies"></i><span class="nav-label">Articles</span>
      </a>  

      <a href="{{ route('admin.contacts.index') }}" class="nav-link-admin {{ request()->routeIs('admin.contacts.index') ? 'active' : '' }}">
        <i class="bi bi-person"></i> <span class="nav-label">Prestations</span>
      </a>

      @include('components.messages-badge')

      <div class="mt-auto">

        <a href="{{route('parametres.index') }}" class="nav-link-admin {{ request()->routeIs('parametres.index') ? 'active' : '' }}">
          <i class="bi bi-gear"></i><span class="nav-label">Paramètres</span>
        </a>

        <a href="{{ route('admin.logout') }}" class="nav-link-admin"
           onclick="event.preventDefault();document.getElementById('logout-form').submit();" class="nav-link-admin">
          <i class="bi bi-box-arrow-right"></i><span class="nav-label">Déconnexion</span>
        </a>
        <form id="logout-form" action="{{ route('admin.logout') }}" method="POST" style="display:none;">@csrf</form>
      </div>
    </nav>
  </aside>

  <!-- TOPBAR -->
  <header id="topbar" class="topbar d-flex align-items-center">
    <div class="d-flex align-items-center gap-2">
      <button id="toggleSidebar" class="btn btn-sm btn-light d-flex align-items-center" aria-label="Basculer la sidebar">
        <i class="bi bi-list"></i>
      </button>

      <!-- Profile -->
      <div class="dropdown">
        <a class="d-flex align-items-center text-decoration-none" id="profileMenu" data-bs-toggle="dropdown" aria-expanded="false" href="#">
          <div class="d-none d-md-block text-start">
            <div style="font-weight:700">{{ auth()->user()->name.'('.auth()->user()->role.')' ?? 'Admin' }}</div>
            <small class="small-muted">{{ auth()->user()->email ?? '' }}</small>
          </div>
        </a>
        <ul class="dropdown-menu dropdown-menu-end shadow" aria-labelledby="profileMenu">
          <li><a class="dropdown-item" href=""><i class="bi bi-person me-2"></i>Mon profil</a></li>
          <li><a class="dropdown-item" href=""><i class="bi bi-gear me-2"></i>Paramètres</a></li>
          <li><hr class="dropdown-divider"></li>
          <li>
            <a class="dropdown-item text-danger" href="{{ route('admin.logout') }}"
                onclick="event.preventDefault();document.getElementById('logout-form').submit();">
                <i class="bi bi-box-arrow-right me-2"></i>Déconnexion
            </a>
          </li>
        </ul>
      </div>

      @include('components.notifications')

    </div>

    <div class="d-flex align-items-center actions">
      <div class="d-flex align-items-center gap-2">

        <!-- Ajouter Bien -->
        <form action="{{ route('admin.biens.create') }}" method="get" class="mb-0">
            <button class="btn btn-light shadow-sm px-3 py-2 rounded-circle"
                    type="submit"
                    title="Ajouter un bien">
                <i class="bi bi-house-add fs-5 text-warning"></i>
            </button>
        </form>
    
        <!-- Ajouter Propriétaire -->
        <button class="btn btn-light shadow-sm px-3 py-2 rounded-circle"
                data-bs-toggle="modal" data-bs-target="#modalAddProprietaire"
                title="Ajouter un propriétaire">
            <i class="bi bi-person-plus fs-5 text-primary"></i>
        </button>

        <!-- Ajouter Livreur -->
        <button class="btn btn-light shadow-sm px-3 py-2 rounded-circle"
                data-bs-toggle="modal" data-bs-target="#modalAddLivreur"
                title="Ajouter un livreur">
            <i class="bi bi-truck fs-5 text-success"></i>
        </button>
            
        <!-- Reset Password -->
        <button class="btn btn-light shadow-sm px-3 py-2 rounded-circle"
                data-bs-toggle="modal" data-bs-target="#modalResetPassword"
                title="Reset mot de passe">
            <i class="bi bi-arrow-counterclockwise fs-5 text-danger"></i>
        </button>
    
    </div>
    
      
   
      <!-- Notifications -->
      {{-- <div class="dropdown me-2">
        <button class="btn btn-sm btn-outline-secondary position-relative" id="notifToggle" data-bs-toggle="dropdown" aria-expanded="false" aria-label="Notifications">
          <i class="bi bi-bell"></i>
          @if(auth()->user() && auth()->user()->unreadNotifications->count())
            <span class="badge-dot" aria-hidden="true"></span>
          @endif
        </button>
        <ul class="dropdown-menu dropdown-menu-end p-2 shadow" aria-labelledby="notifToggle" style="min-width:300px;">
          <li class="mb-2"><strong>Notifications</strong></li>
          @if(auth()->user() && auth()->user()->notifications->count())
            @foreach(auth()->user()->notifications->take(8) as $note)
              <li class="dropdown-item py-2">
                <div class="d-flex align-items-start gap-2">
                  <i class="bi bi-info-circle text-warning"></i>
                  <div>
                    <div class="small-muted">{{ Str::limit($note->data['message'] ?? 'Notification', 80) }}</div>
                    <small class="text-muted">{{ $note->created_at->diffForHumans() }}</small>
                  </div>
                </div>
              </li>
              <li><hr class="dropdown-divider"></li>
            @endforeach
            <li><a class="dropdown-item text-center small-muted" href="{{ route('admin.notifications.index') }}">Voir toutes les notifications</a></li>
          @else
            <li class="dropdown-item text-muted small">Aucune notification</li>
          @endif
        </ul>
      </div> --}}

      <!-- Profile -->
      {{-- <div class="dropdown">
        <a class="d-flex align-items-center text-decoration-none" id="profileMenu" data-bs-toggle="dropdown" aria-expanded="false" href="#">
          <img src="{{ asset(auth()->user()->avatar ?? 'assets/images/default-avatar.png') }}" alt="avatar" class="avatar-sm me-2">
          <div class="d-none d-md-block text-start">
            <div style="font-weight:700">{{ auth()->user()->name ?? 'Admin' }}</div>
            <small class="small-muted">{{ auth()->user()->email ?? '' }}</small>
          </div>
        </a>
        <ul class="dropdown-menu dropdown-menu-end shadow" aria-labelledby="profileMenu">
          <li><a class="dropdown-item" href=""><i class="bi bi-person me-2"></i>Mon profil</a></li>
          <li><a class="dropdown-item" href=""><i class="bi bi-gear me-2"></i>Paramètres</a></li>
          <li><hr class="dropdown-divider"></li>
          <li>
            <a class="dropdown-item text-danger" href="{{ route('admin.logout') }}"
               onclick="event.preventDefault();document.getElementById('logout-form').submit();">
               <i class="bi bi-box-arrow-right me-2"></i>Déconnexion
            </a>
          </li>
        </ul>
      </div> --}}
    </div>
  </header>

  <!-- MAIN -->
  <main id="mainContent" class="main-content">

    {{-- content box --}}
    <div class="card-soft">
      @yield('content')      
    </div>
  
    <!-- ================= Modal Ajouter Propriétaire ================= -->
    <div class="modal fade" id="modalAddProprietaire" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <form method="POST" action="{{ route('admin.users.store') }}">
                    @csrf
                    <div class="modal-header">
                        <h5 class="modal-title">Ajouter un propriétaire</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        <label for="name">Nom</label>
                        <input type="text" id="name" name="name" class="form-control @error('name') is-invalid @enderror" value="{{ old('name') }}" required>
                        @error('name') <div class="invalid-feedback">{{ $message }}</div> @enderror

                        <label for="surname" class="mt-2">Prénom</label>
                        <input type="text" id="surname" name="surname" class="form-control @error('surname') is-invalid @enderror" value="{{ old('surname') }}" required>
                        @error('surname') <div class="invalid-feedback">{{ $message }}</div> @enderror

                        <label for="telephone" class="mt-2">Téléphone</label>
                        <input type="number" id="telephone" name="telephone" class="form-control @error('telephone') is-invalid @enderror" value="{{ old('telephone') }}" required>
                        @error('telephone') <div class="invalid-f                                                  eedback">{{ $message }}</div> @enderror

                        <label for="email" class="mt-2">Email</label>
                        <input type="email" id="email" name="email" class="form-control @error('email') is-invalid @enderror" required value="{{ old('email') }}">
                        @error('email') <div class="invalid-feedback">{{ $message }}</div> @enderror

                        <input type="hidden" id="role" name="role" value="proprietaire">
                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-success">Enregistrer</button>
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- ================= Modal Ajouter Livreur ================= -->
    <div class="modal fade" id="modalAddLivreur" tabindex="-1" aria-hidden="true">
      <div class="modal-dialog">
          <div class="modal-content">
              <form method="POST" action="{{ route('admin.users.store') }}">
                  @csrf
                  <div class="modal-header">
                      <h5 class="modal-title">Ajouter un livreur</h5>
                      <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                  </div>
                  <div class="modal-body">
                      <label for="name">Nom</label>
                      <input type="text" id="name" name="name" class="form-control @error('name') is-invalid @enderror" value="{{ old('name') }}" required>
                      @error('name') <div class="invalid-feedback">{{ $message }}</div> @enderror

                      <label for="surname" class="mt-2">Prénom</label>
                      <input type="text" id="surname" name="surname" class="form-control @error('surname') is-invalid @enderror" value="{{ old('surname') }}" required>
                      @error('surname') <div class="invalid-feedback">{{ $message }}</div> @enderror

                      <label for="telephone" class="mt-2">Téléphone</label>
                      <input type="number" id="telephone" name="telephone" class="form-control @error('telephone') is-invalid @enderror" value="{{ old('telephone') }}" required>
                      @error('telephone') <div class="invalid-f                                                  eedback">{{ $message }}</div> @enderror

                      <label for="email" class="mt-2">Email</label>
                      <input type="email" id="email" name="email" class="form-control @error('email') is-invalid @enderror" required value="{{ old('email') }}">
                      @error('email') <div class="invalid-feedback">{{ $message }}</div> @enderror

                      <input type="hidden" id="role" name="role" value="livreur">
                  </div>
                  <div class="modal-footer">
                      <button type="submit" class="btn btn-success">Enregistrer</button>
                      <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
                  </div>
              </form>
          </div>
      </div>
    </div>

    <!-- ================= Modal Ajouter Bien ================= -->
    <div class="modal fade" id="modalAddBien" tabindex="-1" aria-hidden="true">
      <div class="modal-dialog modal-lg modal-dialog-centered">
          <div class="modal-content rounded-3 shadow-lg">

              <div class="modal-header bg-primary text-white">
                  <h5 class="modal-title">Ajouter un nouveau bien</h5>
                  <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
              </div>

              <form action="{{ route('admin.biens.store') }}" method="POST" enctype="multipart/form-data">
                  @csrf

                  <div class="modal-body">

                      <!-- TITRE -->
                      <div class="mb-3">
                          <label for="titre" class="form-label">Titre du bien</label>
                          <input type="text" name="titre" id="titre" class="form-control" required>
                      </div>

                      <!-- DESCRIPTION -->
                      <div class="mb-3">
                          <label for="description" class="form-label">Description</label>
                          <textarea name="description" id="description" class="form-control"></textarea>
                      </div>

                      <!-- PROPRIETAIRE -->
                      <div class="mb-3">
                          <label for="proprietaire_id" class="form-label">Propriétaire</label>
                          <select name="proprietaire_id" id="proprietaire_id" class="form-select" required>
                              <option value="" disabled selected>-- Sélectionnez un propriétaire --</option>
                              @foreach($proprietaires as $proprio)
                                  <option value="{{ $proprio->id }}">{{ $proprio->name ." ". $proprio->surname }}</option>
                              @endforeach
                          </select>
                      </div>

                      <select id="categorySelect" name="categorie_id" class="form-control">
                        <option value="">-- Choisir une catégorie --</option>
                        @foreach ($categories as $cat)
                            <option value="{{ $cat->id }}">{{ $cat->name }}</option>
                        @endforeach
                      </select>
                      
                      <select id="sousCategorySelect" name="sous_categorie_id" class="form-control" disabled>
                          <option value="">-- Sélectionnez une catégorie d'abord --</option>
                      </select>
                      
                      <select id="subTypeSelect" name="sub_type_id" class="form-control" disabled>
                          <option value="">-- Sélectionnez une sous-catégorie d'abord --</option>
                      </select>
                                                             
                    
                    <div class="mb-3">
                      <label for="prix" class="form-label">Prix</label>
                      <input type="number" step="0.01" name="prix" id="prix" class="form-control">
                    </div>

                    <div class="mb-3">
                      <label for="adresse" class="form-label">Adresse</label>
                      <input type="text" name="adresse" class="form-control @error('adresse') is-invalid @enderror" value="{{ old('adresse') }}" required>
                      @error('adresse')
                          <span class="invalid-feedback">{{ $message }}</span>
                      @enderror
                  </div>
                    
                      <!-- MEDIA -->
                      <div class="mb-3">
                          <label for="medias" class="form-label">Médias du bien (images / vidéos)</label>
                          <input type="file" name="medias[]" id="medias" class="form-control" accept="image/*,video/*" multiple>
                          <small class="text-muted">Formats acceptés : jpg, png, mp4...</small>
                      </div>

                  </div>

                  <div class="modal-footer">
                      <button type="submit" class="btn btn-success">Enregistrer</button>
                      <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">⬅ Annuler</button>
                  </div>

              </form>
          </div>
      </div>
    </div>  
      
  </main>



    
  <script>  // Script pour afficher automatiquement les subCategories lors de createBien
    document.addEventListener("DOMContentLoaded", () => {
    
        // Chargement des categories au chargement
        fetch("/api/categories")
            .then(res => res.json())
            .then(data => {
                const cat = document.getElementById("categorie");
                data.forEach(c => {
                    cat.innerHTML += `<option value="${c.id}">${c.nom}</option>`;
                });
            });
    
        // Changer categorie
        document.getElementById("categorie").addEventListener("change", function () {
            const id = this.value;
    
            const sub = document.getElementById("sousCategorie");
            sub.innerHTML = `<option>Chargement...</option>`;
    
            fetch(`/api/categories/${id}/subcategories`)
                .then(res => res.json())
                .then(data => {
                    sub.innerHTML = `<option value="">-- Choisir --</option>`;
                    data.forEach(s => {
                        sub.innerHTML += `<option value="${s.id}">${s.nom}</option>`;
                    });
                });
    
            // Vide les types
            document.getElementById("typeBien").innerHTML =
                `<option>-- Sélectionnez une sous-catégorie --</option>`;
        });
    
        // Changer sous-catégorie
        document.getElementById("sousCategorie").addEventListener("change", function () {
            const id = this.value;
    
            const types = document.getElementById("typeBien");
            types.innerHTML = `<option>Chargement...</option>`;
    
            fetch(`/api/subcategories/${id}/types`)
                .then(res => res.json())
                .then(data => {
                    types.innerHTML = `<option value="">-- Choisir --</option>`;
                    data.forEach(t => {
                        types.innerHTML += `<option value="${t.id}">${t.nom}</option>`;
                    });
                });
        });
    
    });
  </script>      
    
  <!-- SCRIPTS -->
  <script>  // Script pour gérer la sidebar 
    (function(){
      const sidebar = document.getElementById('adminSidebar');
      const topbar = document.getElementById('topbar');
      const main = document.getElementById('mainContent');
      const toggle = document.getElementById('toggleSidebar');

      // mobile initial state
      let hidden = false;
      let compact = false;

      function setCompact(state){
        compact = state;
        if(compact){
          sidebar.classList.add('compact');
          topbar.classList.add('compact');
          main.classList.add('compact');
        } else {
          sidebar.classList.remove('compact');
          topbar.classList.remove('compact');
          main.classList.remove('compact');
        }
      }

      // Desktop toggle: compact (small) / normal
      toggle && toggle.addEventListener('click', function(e){
        // on small screens, toggle full hide
        if(window.innerWidth < 992){
          hidden = !hidden;
          sidebar.classList.toggle('hidden', hidden);
        } else {
          setCompact(!compact);
        }
      });

      // close sidebar when clicking outside on mobile
      document.addEventListener('click', function(e){
        if(window.innerWidth < 992){
          if(!sidebar.contains(e.target) && !toggle.contains(e.target)){
            sidebar.classList.add('hidden');
          }
        }
      });

      // preserve compact on page load if small width
      window.addEventListener('resize', function(){
        if(window.innerWidth < 992){
          sidebar.classList.remove('compact');
          topbar.classList.remove('compact');
          main.classList.remove('compact');
        }
      });
    })();

    // Masquer la sidebar par défaut sur mobile
    if (window.innerWidth < 992) {
        document.getElementById('adminSidebar').classList.add('hidden');
    }

  </script>  

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

    {{-- Compter et afficher les nouveaux messages non lus --}}
    <script>
      document.addEventListener('DOMContentLoaded', function () {
      
          const badge = document.getElementById('messagesBadge');
          if (!badge) return;
      
          async function loadUnreadCount() {
              try {
                  const response = await fetch('/messages/unread-count', {
                      headers: {
                          'X-Requested-With': 'XMLHttpRequest'
                      }
                  });
      
                  if (!response.ok) return;
      
                  const data = await response.json();
      
                  if (data.count > 0) {
                      badge.textContent = data.count;
                      badge.classList.remove('d-none');
                  } else {
                      badge.classList.add('d-none');
                  }
      
              } catch (error) {
                  console.error('Erreur badge messages:', error);
              }
          }
      
          // Chargement initial
          loadUnreadCount();
      
          // Rafraîchissement toutes les 5 secondes (style Messenger)
          setInterval(loadUnreadCount, 5000);
      });
    </script>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/js/bootstrap.bundle.min.js"></script>


  
</body>
</html>
