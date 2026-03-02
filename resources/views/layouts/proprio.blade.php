<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <meta name="csrf-token" content="{{ csrf_token() }}">
  <title>ShopEasy | Propriétaire - @yield('title')</title>
  
  <link rel="icon" href="{{ asset('assets/images/logo_sahashop.png') }}">

  {{-- Leaflet CSS POUR LA LOCALISATION GEOGRAPHIQUE --}}
  <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />

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
      <a href="{{ route('proprietaire.dashboard') }}" class="nav-link-admin {{ request()->routeIs('proprietaire.dashboard.*') ? 'active' : '' }}">
        <i class="bi bi-speedometer2"></i><span class="nav-label">Tableau de bord</span>
      </a>

      <a href="{{ route('proprietaire.mesclients') }}" class="nav-link-admin {{ request()->routeIs('proprietaire.mesclients.*') ? 'active' : '' }}">
        <i class="bi bi-people"></i><span class="nav-label">Mes clients</span>
      </a>

      <a href="{{ route('proprietaire.biens.index') }}" class="nav-link-admin {{ request()->routeIs('proprietaire.biens.*') ? 'active' : '' }}">
        <i class="bi bi-building"></i><span class="nav-label">Biens</span>
      </a>

      <a href="{{ route('proprietaire.paiements') }}" class="nav-link-admin {{ request()->routeIs('proprietaire.paiements') ? 'active' : '' }}">
        <i class="bi bi-currency-dollar"></i><span class="nav-label">Paiements</span>
      </a>
      
      @include('components.messages-badge')

      <div class="mt-auto">

        <a href="{{route('proprietaire.boutique.localisation') }}" class="nav-link-admin {{ request()->routeIs('proprietaire.boutique.localisation') ? 'active' : '' }}">
          <i class="bi bi-geo-fill"></i><span class="nav-label">Localisation</span>
        </a>

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
      </button>&nbsp;&nbsp;

      <a href="{{ url('/catalogue') }}" class="btn btn-light shadow-sm px-3 py-2 rounded-circle"
        title="Visiter le catalogue">
        <i class="bi bi-shop fs-5 text-success"></i>
      </a>&nbsp;&nbsp;

    </div>

    <div class="d-flex align-items-center actions">  
   
      <!-- Notifications -->
      @include('components.notifications')

      &nbsp;&nbsp;
      <!-- Profile -->
      <div class="dropdown">
        <a class="d-flex align-items-center text-decoration-none" id="profileMenu" data-bs-toggle="dropdown" aria-expanded="false" href="#">
          <div class="me-3">
            @if($user->boutique?->logo)
                <img src="{{ asset('storage/' . $user->boutique->logo) }}" 
                     alt="Photo de profil" 
                     class="rounded-circle border" 
                     style="width: 50px; height: 50px; object-fit: cover;">
            @elseif ($user?->profil)
                <img src="{{ asset('storage/'. $user->profil) }}" 
                     alt="Photo de profil" 
                     class="rounded-circle border" 
                     style="width: 50px; height: 50px; object-fit: cover;">
            @else
              <img src="{{ asset('assets/images/default_user.png') }}" 
              alt="Photo de profil" 
              class="rounded-circle border" 
              style="width: 50px; height: 50px; object-fit: cover;">
            @endif
        </div>

        <div class="d-none d-md-block text-start">
          <small class="small-muted">
            {{ $user->boutique->email ?? $user->email }}
          </small>
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
    </div>
  </header>

  <!-- MAIN -->
  <main id="mainContent" class="main-content">

    {{-- content box --}}
    <div class="card-soft">
      @yield('content')
    </div>


    {{-- @stack('modals') --}}
  </main>

  <form id="logout-form" action="{{ route('admin.logout') }}" method="POST" style="display:none;">@csrf</form>



  <!-- SCRIPTS -->
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/js/bootstrap.bundle.min.js"></script>
  <script>
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


    {{-- Leaflet JS --}}
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>



    @yield('scripts')
</body>
</html>
