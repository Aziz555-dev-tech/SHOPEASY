@extends('layouts.app')


@section('content')


@endsection

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ShopEasy - Créez votre boutique en ligne</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/styleIndex.css') }}">
</head>
<body>
    <!-- Hero Section -->
    <section class="hero" id="home">
        <div class="container">
            <div class="hero-wrapper">
                <div class="hero-content">
                    <div class="hero-badge">
                        <i class="fas fa-award"></i> LEADER DU E-COMMERCE AFRICAIN
                    </div>
                    <h1>
                        Créez votre <span class="highlight">boutique en ligne</span> en quelques minutes
                    </h1>
                    <p>
                        La plateforme tout-en-un pour créer, vendre et encaisser vos produits. 
                        Rejoignez des milliers d'entrepreneurs qui ont transformé leur passion en entreprise prospère.
                    </p>
                    <div class="hero-buttons">
                        <a href="{{ route('contact') }}" class="btn btn-lg btn-warning">
                            <i class="fas fa-rocket"></i> Créer ma boutique
                        </a>
                        <a href="#pricing" class="btn btn-lg btn-outline-warning">
                            <i class="fas fa-play-circle"></i> Voir la tarification
                        </a>
                    </div>
                </div>
                <div class="hero-visual">
                    <div class="hero-carousel">
                        <div class="carousel-container" id="heroCarousel">
                            <div class="carousel-slide active" style="background-image: url('https://images.unsplash.com/photo-1460925895917-afdab827c52f?w=800');">
                                <div class="carousel-slide-content">
                                    <h3>Boutiques Modernes</h3>
                                    <p>Créez une boutique professionnelle adaptée à votre marque</p>
                                </div>
                            </div>
                            <div class="carousel-slide" style="background-image: url('https://images.unsplash.com/photo-1556742049-0cfed4f6a45d?w=800');">
                                <div class="carousel-slide-content">
                                    <h3>Commerce Simplifié</h3>
                                    <p>Gérez vos ventes avec des outils intuitifs et puissants</p>
                                </div>
                            </div>
                            <div class="carousel-slide" style="background-image: url('https://images.unsplash.com/photo-1542744173-8e7e53415bb0?w=800');">
                                <div class="carousel-slide-content">
                                    <h3>Marché Africain</h3>
                                    <p>Connectez-vous à des millions de clients potentiels</p>
                                </div>
                            </div>
                        </div>
                        <div class="carousel-indicators">
                            <div class="carousel-indicator active" onclick="goToSlide(0)"></div>
                            <div class="carousel-indicator" onclick="goToSlide(1)"></div>
                            <div class="carousel-indicator" onclick="goToSlide(2)"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Advantages Section - Redesigned -->
    <section class="advantages" id="advantages">
        <div class="container">
            <div class="section-header">
                <span class="section-label">AVANTAGES</span>
                <h3 class="section-title">La plateforme qui simplifie la vente de vos produits</h3>
                <p class="section-subtitle">
                    ShopEasy réunit simplicité, liberté et efficacité pour que vous puissiez vous concentrer sur l'essentiel : créer, vendre et encaisser.
                </p>
            </div>
            <div class="advantages-grid">
                <div class="advantage-card">
                    <div class="advantage-visual">
                        <img src="{{ asset('assets/images/simplicite.jpg') }}" alt="95% de vos ventes">
                        <div class="advantage-badge">95%</div>
                    </div>
                    <h3>Gardez 95% de vos ventes</h3>
                    <p>
                        Avec seulement 5% de commission, vous conservez l'essentiel de vos revenus et maximisez vos gains à chaque transaction.
                    </p>
                </div>
                <div class="advantage-card">
                    <div class="advantage-visual">
                        <img src="{{ asset('assets/images/marhetplace.jpg') }}" alt="Marketplace intégrée">
                    </div>
                    <h3>Accédez à la marketplace PLR</h3>
                    <p>
                        Des centaines de produits prêts à être personnalisés et revendus sous votre nom, afin de démarrer plus vite et élargir votre offre.
                    </p>
                </div>
                <div class="advantage-card">
                    <div class="advantage-visual">
                        <img src="{{ asset('assets/images/ecommerce1.png') }}" alt="Simplicité inégalée">
                    </div>
                    <h3>Une simplicité inégalée</h3>
                    <p>
                        Interface intuitive, paiements sécurisés et tableau de bord clair : tout est pensé pour vous simplifier la vie et booster votre activité.
                    </p>
                </div>
            </div>
        </div>
    </section>

    <!-- How It Works Section - Redesigned -->
    <section class="how-it-works" id="how-it-works">
        <div class="container">
            <div class="section-header">
                <span class="section-label">COMMENT ÇA MARCHE</span><br>
                <p class="section-subtitle">
                    Avec ShopEasy, créer votre boutique, partager vos liens et encaisser vos ventes devient un jeu d'enfant.
                </p>
            </div>
            <div class="steps-wrapper">
                <div class="steps-tabs">
                    <button class="step-tab active" onclick="switchStep(0)">
                        <i class="fas fa-rocket"></i> Lancer
                    </button>
                    <button class="step-tab" onclick="switchStep(1)">
                        <i class="fas fa-share-alt"></i> Diffuser
                    </button>
                    <button class="step-tab" onclick="switchStep(2)">
                        <i class="fas fa-dollar-sign"></i> Encaisser
                    </button>
                </div>
                
                <div class="steps-content">
                    <div class="step-content active" data-step="0">
                        <div class="step-info">
                            <h3>Lancez votre boutique en un instant</h3>
                            <p>
                                Inscrivez-vous gratuitement et mettez votre boutique en ligne en quelques clics. 
                                Vous pouvez y ajouter vos ebooks, formations, vidéos, templates... ou choisir parmi des 
                                centaines de produits PLR déjà prêts. Personnalisez, fixez vos prix et démarrez sans attendre.
                            </p>
                        </div>
                        <div class="step-visual">
                            <div class="step-mockup py-5">
                                <center><i class="bi bi-send-check-fill" style="font-size: 4rem; color: var(--primary-gold);"></i></center>
                                <h4 style="margin-top: 1.5rem; font-size: 1.5rem;" class="text-center">Lancez votre boutique</h4>
                                <p class="text-muted text-center">
                                    Partager votre produits et vos nouveautés pour élargir votre audience.
                                </p>
                            </div>
                        </div>
                    </div>

                    <div class="step-content" data-step="1">
                        <div class="step-info">
                            <h3>Diffusez vos produits</h3>
                            <i class="fas fa-share-nodes" style="font-size: 4rem; color: var(--primary-gold);"></i>
                            <p>
                                Partagez vos liens de vente sur vos réseaux sociaux, votre site web ou par email. 
                                Utilisez nos outils marketing pour maximiser votre visibilité et atteindre plus de clients potentiels.
                            </p>
                        </div>
                        <div class="step-visual">
                            <div class="step-mockup">
                                <div style="text-align: center; padding: 3rem;">
                                    <i class="fas fa-share-nodes" style="font-size: 4rem; color: var(--primary-gold);"></i>
                                    <h4 style="margin-top: 1.5rem; font-size: 1.5rem;">Partagez facilement</h4>
                                    <p style="margin-top: 1rem; color: var(--medium-gray);">Diffusez vos produits sur tous vos canaux</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="step-content" data-step="2">
                        <div class="step-info">
                            <h3>Encaissez vos revenus</h3>
                            <p>
                                Recevez vos paiements rapidement et en toute sécurité. 
                                Suivez vos ventes en temps réel et optimisez votre stratégie grâce à notre tableau de bord intuitif.
                            </p>
                        </div>
                        <div class="step-visual">
                            <div class="step-mockup">
                                <div style="text-align: center; padding: 3rem;">
                                    <i class="fas fa-money-bill-wave" style="font-size: 4rem; color: var(--primary-gold);"></i>
                                    <h4 style="margin-top: 1.5rem; font-size: 1.5rem;">Paiements sécurisés</h4>
                                    <p style="margin-top: 1rem; color: var(--medium-gray);">Recevez vos revenus rapidement</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Features Section - Redesigned -->
    <section class="features" id="features">
        <div class="container">
            <div class="section-header">
                <span class="section-label">FONCTIONNALITÉS</span>
                <h2 class="section-title">Les fonctionnalités qui font la différence</h2>
                <p class="section-subtitle">
                    ShopEasy vous offre tous les outils essentiels pour vendre efficacement, automatiser vos actions et développer votre activité sans limites.
                </p>
            </div>
            <div class="features-grid">
                <div class="feature-card">
                    <div class="feature-icon">
                        <i class="fas fa-robot"></i>
                    </div>
                    <div class="feature-content">
                        <h3>Workflows automatisés</h3>
                        <p>Automatisez vos ventes : emails après achat, offres complémentaires, relances…</p>
                    </div>
                </div>
                <div class="feature-card">
                    <div class="feature-icon">
                        <i class="fas fa-chart-line"></i>
                    </div>
                    <div class="feature-content">
                        <h3>Pixels de conversion</h3>
                        <p>Ajoutez vos pixels Facebook, Google, TikTok pour suivre vos résultats.</p>
                    </div>
                </div>
                <div class="feature-card">
                    <div class="feature-icon">
                        <i class="fas fa-palette"></i>
                    </div>
                    <div class="feature-content">
                        <h3>Personnalisation</h3>
                        <p>Choisissez vos couleurs, thèmes et créez une boutique à votre image.</p>
                    </div>
                </div>
                <div class="feature-card">
                    <div class="feature-icon">
                        <i class="fas fa-globe"></i>
                    </div>
                    <div class="feature-content">
                        <h3>Domaine personnalisé</h3>
                        <p>Hébergez votre boutique sur votre domaine ou sous-domaine dédié.</p>
                    </div>
                </div>
                <div class="feature-card">
                    <div class="feature-icon">
                        <i class="fas fa-download"></i>
                    </div>
                    <div class="feature-content">
                        <h3>Exportation des données</h3>
                        <p>Exportez vos ventes et vos clients pour analyser vos performances.</p>
                    </div>
                </div>
                <div class="feature-card">
                    <div class="feature-icon">
                        <i class="fas fa-money-bill-wave"></i>
                    </div>
                    <div class="feature-content">
                        <h3>Multi-devises</h3>
                        <p>Vendez partout au monde et laissez vos clients payer dans la devise qui leur convient.</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Testimonials Section -->
    <section class="testimonials">
        <div class="container">
            <div class="section-header">
                <span class="section-label">TÉMOIGNAGES</span>
                <h2 class="section-title">Ce que vous allez adorer chez ShopEasy</h2>
                <p class="section-subtitle">
                    Découvrez comment ShopEasy transforme la vie de nos entrepreneurs
                </p>
            </div>
            <div class="testimonials-grid">
                <div class="testimonial-card">
                    <p class="testimonial-quote">
                        "Je fais partie des tout premiers utilisateurs de ShopEasy et honnêtement c'est un outil exceptionnel. 
                        La simplicité d'utilisation et les fonctionnalités avancées m'ont permis de doubler mes ventes en 3 mois."
                    </p>
                    <div class="testimonial-author">
                        <div class="author-avatar">AF</div>
                        <div class="author-info">
                            <h4>Amadou FALL</h4>
                            <p>YouTubeur & Formateur en ligne</p>
                        </div>
                    </div>
                </div>
                <div class="testimonial-card">
                    <p class="testimonial-quote">
                        "ShopEasy a transformé la manière dont je vends mes produits. En quelques clics, ma boutique était en ligne 
                        et je touche mes revenus beaucoup plus vite qu'avant. C'est vraiment la solution qu'il me fallait."
                    </p>
                    <div class="testimonial-author">
                        <div class="author-avatar">NV</div>
                        <div class="author-info">
                            <h4>NoLimit Vousdv</h4>
                            <p>Coach & Entrepreneur Digital</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Stats Section -->
    <section class="stats-section">
        <div class="container">
            <div class="section-header">
                <span class="section-label">STATITSTIQUES</span>
                <h2 class="section-title">Des données statistiques remarquables </h2>
                <p class="section-subtitle">
                    Notre équipe s'est engagé pour vous faire touchés du doigts des clients cibles pour vos produit nous permettant d'atteidre des résultats remarquables.
                </p>
            </div>

            <div class="stats-grid">
                <div class="stat-card">
                    <div class="stat-icon">
                        <i class="fas fa-users"></i>
                    </div>
                    <div class="stat-number">15+</div>
                    <div class="stat-label">Créateurs qui lancent leur boutique chaque semaine</div>
                </div>
                <div class="stat-card">
                    <div class="stat-icon">
                        <i class="fas fa-chart-line"></i>
                    </div>
                    <div class="stat-number">60M+</div>
                    <div class="stat-label">FCFA encaissés par nos créateurs</div>
                </div>
                <div class="stat-card">
                    <div class="stat-icon">
                        <i class="fas fa-percentage"></i>
                    </div>
                    <div class="stat-number">95%</div>
                    <div class="stat-label">C'est la part que vous gardez sur chaque vente</div>
                </div>
                <div class="stat-card">
                    <div class="stat-icon">
                        <i class="fas fa-trophy"></i>
                    </div>
                    <div class="stat-number">5%</div>
                    <div class="stat-label">Seulement pour ShopEasy, le taux le plus bas</div>
                </div>
            </div>
        </div>
    </section>

    <!-- Pricing Section -->
    <section class="pricing" id="pricing">
        <div class="container">
            <div class="section-header">
                <span class="section-label">TARIFICATION</span>
                <h2 class="section-title">Une tarification simple et transparente</h2>
                <p class="section-subtitle">
                    Avec ShopEasy, pas d'abonnement ni de frais cachés. Vous ne payez que 5% sur vos ventes et vous gardez 95% de vos revenus.
                </p>
            </div>
           

           <!-- Pricing Section -->
        <section class="pricing" id="pricing">
            <div class="container">
                <div class="pricing-grid">
                <div class="pricing-card ">
                        <h3>Basic</h3>
                        <div class="price">0 F<span>/mois</span></div>
                        <ul class="features-list">
                            <li><i class="fas fa-check"></i> Produits illimités</li>
                            <li><i class="fas fa-check"></i> Paiements en ligne</li>
                            <li><i class="fas fa-check"></i> Domaine personnalisé</li>
                            <li><i class="fas fa-check"></i> Analytics avancées</li>
                            <li><i class="fas fa-check"></i> Support prioritaire</li>
                        </ul>
                        <button class="btn btn-warning" onclick="selectPlan('standard')">Choisir Basic</button>
                    </div>
                    
                    <div class="pricing-card featured">
                        <h3>Standard</h3>
                        <div class="price">20 000 F<span>/mois</span></div>
                        <ul class="features-list">
                            <li><i class="fas fa-check"></i> Produits illimités</li>
                            <li><i class="fas fa-check"></i> Paiements en ligne</li>
                            <li><i class="fas fa-check"></i> Domaine personnalisé</li>
                            <li><i class="fas fa-check"></i> Analytics avancées</li>
                            <li><i class="fas fa-check"></i> Support prioritaire</li>
                        </ul>
                        <button class="btn btn-warning" onclick="selectPlan('standard')">Choisir Standard</button>
                    </div>
                    <div class="pricing-card ">
                        <h3>Premium</h3>
                        <div class="price">50 000 F<span>/mois</span></div>
                        <ul class="features-list">
                            <li><i class="fas fa-check"></i> Produits illimités</li>
                            <li><i class="fas fa-check"></i> Paiements en ligne</li>
                            <li><i class="fas fa-check"></i> Domaine personnalisé</li>
                            <li><i class="fas fa-check"></i> Analytics avancées</li>
                            <li><i class="fas fa-check"></i> Support prioritaire</li>
                        </ul>
                        <button class="btn btn-warning" onclick="selectPlan('standard')">Choisir Premium</button>
                    </div>
                </div>
            </div>
        </section>
        </div>
    </section>

    <!-- CTA Section -->
    <section class="cta-section">
        <div class="container">
            <div class="cta-content">
                <h2>Prêt à lancer votre boutique en ligne ?</h2>
                <p>
                    Rejoignez dès aujourd'hui les créateurs et entrepreneurs qui transforment leurs compétences en revenus. 
                    Avec ShopEasy, vous gardez 95% de vos ventes et accédez à une marketplace intégrée.
                </p>
                <a href="{{ route('contact') }}" class="btn btn-warning" style="font-size: 1.1rem; padding: 1rem 2.5rem;">
                    <i class="fas fa-rocket"></i> Créer ma boutique gratuite
                </a>
            </div>
        </div>
    </section>

    <script>
        (function() {
            'use strict';

            let currentSlide = 0;
            const totalSlides = 3;
            let currentStepIndex = 0;

            // ===== NAVBAR SCROLL EFFECT =====
            window.addEventListener('scroll', () => {
                const navbar = document.querySelector('.navbar');
                if (window.scrollY > 50) {
                    navbar.classList.add('scrolled');
                } else {
                    navbar.classList.remove('scrolled');
                }
            });

            // ===== CAROUSEL HERO =====
            function goToSlide(index) {
                const slides = document.querySelectorAll('.carousel-slide');
                const indicators = document.querySelectorAll('.carousel-indicator');
                
                slides.forEach(slide => slide.classList.remove('active'));
                indicators.forEach(indicator => indicator.classList.remove('active'));
                
                currentSlide = index;
                slides[currentSlide].classList.add('active');
                indicators[currentSlide].classList.add('active');
            }

            function nextSlide() {
                currentSlide = (currentSlide + 1) % totalSlides;
                goToSlide(currentSlide);
            }

            // Auto-play carousel
            setInterval(nextSlide, 5000);

            // Make goToSlide available globally
            window.goToSlide = goToSlide;

            // ===== STEPS SWITCHING =====
            window.switchStep = function(index) {
                const tabs = document.querySelectorAll('.step-tab');
                const contents = document.querySelectorAll('.step-content');
                
                // Remove active class from all tabs and contents
                tabs.forEach(tab => tab.classList.remove('active'));
                contents.forEach(content => content.classList.remove('active'));
                
                // Add active class to selected tab and content
                tabs[index].classList.add('active');
                contents[index].classList.add('active');
                
                currentStepIndex = index;
            };

            // ===== FAQ TOGGLE =====
            window.toggleFaq = function(element) {
                const faqItem = element.parentElement;
                const isActive = faqItem.classList.contains('active');
                
                // Close all FAQ items
                document.querySelectorAll('.faq-item').forEach(item => {
                    item.classList.remove('active');
                });
                
                // Open clicked item if it wasn't active
                if (!isActive) {
                    faqItem.classList.add('active');
                }
            };

            // ===== SMOOTH SCROLL =====
            document.querySelectorAll('a[href^="#"]').forEach(anchor => {
                anchor.addEventListener('click', function (e) {
                    e.preventDefault();
                    const target = document.querySelector(this.getAttribute('href'));
                    if (target) {
                        target.scrollIntoView({
                            behavior: 'smooth',
                            block: 'start'
                        });
                    }
                });
            });
        })();
    </script>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>