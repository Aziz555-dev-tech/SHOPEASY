<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <title>ShopEasy - Grand Centre Commercial Africain en ligne</title>
    <link rel="icon" type="image/png" href="{{ asset('assets/images/logo_sahashop.png') }}">

    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">

    <!-- Quill Editor -->
    <link href="https://cdn.quilljs.com/1.3.6/quill.snow.css" rel="stylesheet">

    <!-- Custom CSS -->
    <link rel="stylesheet" href="{{ asset('css/styles.css') }}">
</head>

<style>

    /* ============================================
       HEADER FIX
    ============================================ */
    .header {
        height: 80px;
        display: flex;
        align-items: center;
        z-index: 10000; /* header toujours devant */
    }
    
    body.has-fixed-header {
        padding-top: 80px;
    }
    
    /* ============================================
       MEGA MENU – STYLE SHOPEASY
    ============================================ */
    .mega-menu {
        position: absolute;
        top: 100%;
        left: 0;
        width: 750px;
        padding: 25px;
        display: none;
        opacity: 0;
        visibility: hidden;
        pointer-events: none;
    
        background: #ffffff !important;
        border-radius: 12px;
        box-shadow: 0 10px 25px rgba(0,0,0,0.12);
    
        transition: opacity .2s ease;
        z-index: 9999;
    }
    
    .dropdown {
        position: relative;
    }
    
    .dropdown:hover > .mega-menu {
        display: flex !important;
        opacity: 1;
        visibility: visible;
        pointer-events: auto;
    }
    
    /* Colonnes */
    .mega-row {
        display: flex;
        justify-content: space-between;
        gap: 40px;
        width: 100%;
    }
    
    .mega-col {
        flex: 1;
    }
    
    .mega-col h6,
    .dropdown-title {
        font-weight: 700;
        margin-bottom: 8px;
        color: var(--primary-color);
    }
    
    .mega-col a,
    .dropdown-item {
        display: block;
        margin-bottom: 6px;
        color: var(--text-color);
        font-size: 14px;
        transition: .2s;
    }
    
    .mega-col a:hover,
    .dropdown-item:hover {
        color: var(--primary-color);
        transform: translateX(4px);
    }
    
    /* ============================================
       HIDE MEGA MENU IN MOBILE
    ============================================ */
    @media (max-width: 991px) {
        .dropdown:hover > .mega-menu,
        .mega-menu {
            display: none !important;
            opacity: 0 !important;
            visibility: hidden !important;
        }
    }
    
    /* ============================================
       MOBILE NAVIGATION
    ============================================ */
    .hamburger {
        display: none;
        cursor: pointer;
        z-index: 9999;
    }
    
    .hamburger span {
        width: 26px;
        height: 3px;
        background: #000;
        margin: 5px 0;
        display: block;
        transition: .3s;
    }
    
    /* MENU MOBILE */
    .mobile-menu {
        position: fixed;
        top: 65px;
        left: 0;
        width: 100%;
        height: calc(100vh - 65px);
        background: var(--card-bg);
        padding: 20px;
        box-shadow: 0 10px 25px rgba(0,0,0,0.15);
    
        display: none;
        overflow-y: auto;
        z-index: 9998;
    }
    
    .mobile-menu.active {
        display: block !important;
    }
    
    .mobile-menu a {
        display: block;
        padding: 12px 0;
        font-size: 17px;
        font-weight: 500;
        border-bottom: 1px solid rgba(0,0,0,0.06);
    }

    /* FIX MOBILE MENU BACKGROUND — STYLE SHOPEASY */
    .mobile-menu {
        background: #ffffff !important; /* blanc opaque */
        backdrop-filter: none !important; 
    }

    .mobile-dropdown-content,
    .mobile-subdropdown-content {
        background: #ffffff !important; 
    }

    
    /* Accordéons Boutiques / Produits */
    .mobile-dropdown {
        margin-bottom: 12px;
    }
    
    .mobile-dropdown-title {
        font-size: 17px;
        font-weight: 600;
        padding: 12px 0;
        cursor: pointer;
        display: flex;
        justify-content: space-between;
        border-bottom: 1px solid rgba(0,0,0,0.08);
    }
    
    .mobile-dropdown-content {
        display: none;
        padding-left: 15px;
    }
    
    .mobile-dropdown.open .mobile-dropdown-content {
        display: block;
    }
    
    /* ============================================
       MOBILE BREAKPOINT
    ============================================ */
    @media (max-width: 991px) {
    
        .header {
            height: 65px;
            padding: 0 15px;
        }
    
        /* cacher menu desktop */
        .nav-links {
            display: none !important;
        }
    
        /* afficher burger */
        .hamburger {
            display: block !important;
        }
    
        /* navbar alignée */
        .nav {
            display: flex;
            justify-content: space-between;
            width: 100%;
            align-items: center;
        }
    }

    /* HAMBURGER MOBILE */
    .hamburger span {
        width: 26px;
        height: 3px;
        background: #FFD700; /* jaune doré */
        margin: 5px 0;
        display: block;
        transition: .3s;
    }

    
</style>
    

    <body class="font-sans antialiased has-fixed-header">

        <!-- Header - Navigation principale -->

        <header class="header">
            <div class="container">
                <nav class="nav d-flex align-items-center justify-content-between">
        
                    <!-- LOGO -->
                    <a href="/" class="logo d-flex">
                        <div class="container">
                            <span class="d-flex">
                                <img src="{{ asset('assets/images/logo_sahashop.png') }}" style="width: 50px; height: 50px;" alt="">
                            </span>
                        </div>
                    </a>
        
                    <!-- NAVIGATION DESKTOP -->
                    <ul class="nav-links d-flex align-items-center">
        
                        <li><a class="nav-link {{ request()->is('accueil') ? 'active' : '' }}" href="/accueil">Accueil</a></li>
                        <li><a class="nav-link {{ request()->is('apropos') ? 'active' : '' }}" href="/apropos">A Propos</a></li>
        
                        <!-- BOUTIQUES (MEGA MENU) -->
                        <li class="dropdown">
                            <a class="nav-link dropdown-toggle">Boutiques</a>
                            <div class="mega-menu">
                                <div class="mega-row">
                                    <div class="mega-col">
                                        <h6>Mode & Beauté</h6>
                                        <a href="/boutiques/mode">Boutiques de Mode</a>
                                        <a href="/boutiques/beaute">Instituts Beauté</a>
                                        <a href="/boutiques/chaussures">Chaussures</a>
                                        <a href="/boutiques/accessoires">Accessoires</a>
                                    </div>
                                    <div class="mega-col">
                                        <h6>Technologie</h6>
                                        <a href="/boutiques/electronique">Électronique</a>
                                        <a href="/boutiques/telephonie">Téléphonie</a>
                                        <a href="/boutiques/informatique">Informatique</a>
                                        <a href="/boutiques/gaming">Gaming</a>
                                    </div>
                                    <div class="mega-col">
                                        <h6>Maison & Services</h6>
                                        <a href="/boutiques/meubles">Meubles</a>
                                        <a href="/boutiques/electromenager">Électroménager</a>
                                        <a href="/boutiques/decoration">Décoration</a>
                                        <a href="/boutiques/services">Services Divers</a>
                                    </div>
                                </div>
                            </div>
                        </li>                       

                        <li class="dropdown">
                            <a class="nav-link dropdown-toggle">Produits</a>
                            <div class="mega-menu">
                                <div class="mega-row">
                        
                                    @foreach(\App\Models\Category::with('sousCategories.subTypes')->get() as $category)
                                        <div class="mega-col">
                                            <h6>{{ $category->name }}</h6>
                        
                                            @foreach($category->sousCategories as $sous)
                                                <strong style="color: var(--primary-color);">{{ $sous->name }}</strong>
                                                
                                                @foreach($sous->subTypes as $sub)
                                                    <a href="{{ url('/catalogue?categorie=' . $category->slug . '&sous=' . $sous->slug . '&sub=' . $sub->slug) }}">
                                                        {{ $sub->name }}
                                                    </a>
                                                @endforeach
                        
                                                <br>
                                            @endforeach
                        
                                        </div>
                                    @endforeach
                        
                                </div>
                            </div>
                        </li>
                        
        
                        <li><a class="nav-link {{ request()->is('actualite') ? 'active' : '' }}" href="/actualite">Blog</a></li>
                        <li><a class="nav-link {{ request()->is('nos-partenaire') ? 'active' : '' }}" href="/nos-partenaire">Partenaires</a></li>
                        <li><a class="nav-link {{ request()->is('faq') ? 'active' : '' }}" href="/faq">FAQ</a></li>
                        <li><a class="nav-link {{ request()->is('contact') ? 'active' : '' }}" href="/contact">Contact</a></li>
        
                    </ul>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
        
                    <!-- ACTIONS DROITES -->
                    <div class="nav-buttons">       
                        @auth
                            <form action="{{ route('logout') }}" method="POST">
                                @csrf
                                <button type="submit" class="btn btn-light">Déconnexion</button>
                            </form>
                        @else
                            <a href="{{ route('client.login') }}" class="btn btn-light">Se connecter</a>
                            <a href="{{ route('register') }}" class="btn btn-outline-light">S’inscrire</a>
                        @endauth
                    </div>
        
                    <!-- HAMBURGER MOBILE -->
                    <div class="hamburger">
                        <span></span><span></span><span></span>
                    </div>
        
                </nav>
        
                <!-- MENU MOBILE COMPLET -->
                <div id="mobileMenu" class="mobile-menu">
        
                    <a href="/accueil">Accueil</a>
                    <a href="/apropos">A Propos</a>
        
                    <!-- BOUTIQUES MOBILE -->
                    <div class="mobile-dropdown">
                        <div class="mobile-dropdown-title">
                            Boutiques
                            <i class="fas fa-chevron-down"></i>
                        </div>
                        <div class="mobile-dropdown-content">
                            <!-- Mode & Beauté -->
                            <div class="mobile-subdropdown">
                                <div class="mobile-subdropdown-title">Mode & Beauté <i class="fas fa-chevron-down"></i></div>
                                <div class="mobile-subdropdown-content">
                                    <a href="/boutiques/mode">Boutiques de Mode</a>
                                    <a href="/boutiques/beaute">Instituts Beauté</a>
                                    <a href="/boutiques/chaussures">Chaussures</a>
                                    <a href="/boutiques/accessoires">Accessoires</a>
                                </div>
                            </div>
                            <!-- Technologie -->
                            <div class="mobile-subdropdown">
                                <div class="mobile-subdropdown-title">Technologie <i class="fas fa-chevron-down"></i></div>
                                <div class="mobile-subdropdown-content">
                                    <a href="/boutiques/electronique">Électronique</a>
                                    <a href="/boutiques/telephonie">Téléphonie</a>
                                    <a href="/boutiques/informatique">Informatique</a>
                                    <a href="/boutiques/gaming">Gaming</a>
                                </div>
                            </div>
                            <!-- Maison & Services -->
                            <div class="mobile-subdropdown">
                                <div class="mobile-subdropdown-title">Maison & Services <i class="fas fa-chevron-down"></i></div>
                                <div class="mobile-subdropdown-content">
                                    <a href="/boutiques/meubles">Meubles</a>
                                    <a href="/boutiques/electromenager">Électroménager</a>
                                    <a href="/boutiques/decoration">Décoration</a>
                                    <a href="/boutiques/services">Services Divers</a>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- PRODUITS MOBILE -->
                    <div class="mobile-dropdown">
                        <div class="mobile-dropdown-title">Produits <i class="fas fa-chevron-down"></i></div>
                        <div class="mobile-dropdown-content">

                            @foreach(\App\Models\Category::with('sousCategories.subTypes')->get() as $category)
                                @foreach($category->sousCategories as $sous)
                                    <div class="mobile-subdropdown">
                                        <div class="mobile-subdropdown-title">
                                            {{ $sous->name }} <i class="fas fa-chevron-down"></i>
                                        </div>
                                        <div class="mobile-subdropdown-content">
                                            @foreach($sous->subTypes as $sub)
                                                <a href="{{ url('/catalogue?categorie=' . $category->slug . '&sous=' . $sous->slug . '&sub=' . $sub->slug) }}">
                                                    {{ $sub->name }}
                                                </a>
                                            @endforeach
                                        </div>
                                    </div>
                                @endforeach
                            @endforeach

                        </div>
                    </div>

        
                    <a href="/actualite">Blog</a>
                    <a href="/nos-partenaire">Partenaires</a>
                    <a href="/faq">FAQ</a>
                    <a href="/contact">Contact</a>
        
                </div>
            </div>
        </header>
        
        
        

        <main>
            @yield('content')
        </main>

        <!-- Footer -->
        <footer class="footer" id="footer">
            <div class="container">
                <div class="footer-content">
                    <div class="footer-section">
                        <h3>ShopEasy</h3>
                        <p>Le Grand Centre Commercial Africain en ligne. Créez votre boutique et vendez vos produits facilement à travers toute l'Afrique.</p>
                        <div class="social-icons">
                            <a href="#"><i class="fab fa-facebook-f"></i></a>
                            <a href="#"><i class="fab fa-instagram"></i></a>
                            <a href="#"><i class="fab fa-twitter"></i></a>
                            <a href="#"><i class="fab fa-linkedin-in"></i></a>
                        </div>
                    </div>
                    <div class="footer-section">
                        <h3>Liens rapides</h3>
                        <ul>
                            <li><a href="#" onclick="scrollToSection('features')">Fonctionnalités</a></li>
                            <li><a href="#" onclick="scrollToSection('pricing')">Tarifs</a></li>
                            <li><a href="#">À propos</a></li>
                            <li><a href="#">Blog</a></li>
                            <li><a href="#">Aide</a></li>
                        </ul>
                    </div>
                    <div class="footer-section">
                        <h3>Support</h3>
                        <ul>
                            <li><a href="#">Centre d'aide</a></li>
                            <li><a href="#">Nous contacter</a></li>
                            <li><a href="#">Formation</a></li>
                            <li><a href="#">Documentation API</a></li>
                            <li><a href="#">Statut du service</a></li>
                        </ul>
                    </div>
                    <div class="footer-section">
                        <h3>Légal</h3>
                        <ul>
                            <li><a href="#">Conditions d'utilisation</a></li>
                            <li><a href="#">Politique de confidentialité</a></li>
                            <li><a href="#">Mentions légales</a></li>
                            <li><a href="#">RGPD</a></li>
                        </ul>
                    </div>
                </div>
                <div class="footer-bottom">
                    <p>© 2025 ShopEasy. Tous droits réservés. Fait avec ❤️ pour l'Afrique.</p>
                </div>
            </div>
        </footer>

        <!-- Scripts -->
       

        <script>
            // Mobile menu
            const mobileMenu = document.getElementById('mobileMenu');
            const hamburger = document.querySelector('.hamburger');
        
            hamburger.addEventListener('click', () => {
                mobileMenu.classList.toggle('active');
            });
        
            // Dropdown mobile
            document.querySelectorAll(".mobile-dropdown-title").forEach(title => {
                title.addEventListener("click", () => {
                    const parent = title.parentElement;
        
                    document.querySelectorAll(".mobile-dropdown").forEach(drop => {
                        if (drop !== parent) drop.classList.remove("open");
                    });
        
                    parent.classList.toggle("open");
                });
            });
        </script>


    </body>
</html>
