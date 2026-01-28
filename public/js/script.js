// ===== VARIABLES GLOBALES ===== 
let currentUser = null;
let currentTestimonial = 0;
let heroCarouselIndex = 0;
let products = [];
let orders = [];
let reviews = [];
let shopData = {};
let allShops = [];
let statsAnimated = false;
let cart = [];
let currentProductEditor = null;
let selectedRating = 0;
let selectedProductReviewRating = 0;
let currentViewingShop = null;
let currentViewingProduct = null;
let productImages = [];
let navigationHistory = [];

// ===== SÉCURITÉ - FONCTIONS DE VALIDATION ET SANITIZATION =====

/**
 * Sanitize HTML pour prévenir les attaques XSS
 */
function sanitizeHTML(html) {
    const temp = document.createElement('div');
    temp.textContent = html;
    return temp.innerHTML;
}

/**
 * Valider le format email
 */
function validateEmail(email) {
    const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
    return emailRegex.test(email);
}

/**
 * Valider le numéro de téléphone (format international ou local)
 */
function validatePhone(phone) {
    // Accepte les formats: +229 XX XX XX XX, 00229XXXXXXXX, XXXXXXXX
    const phoneRegex = /^(\+|00)?[0-9]{8,15}$/;
    return phoneRegex.test(phone.replace(/\s/g, ''));
}

/**
 * Valider la force du mot de passe
 * Retourne un objet avec {isValid, strength, message}
 */
function validatePasswordStrength(password) {
    const result = {
        isValid: false,
        strength: 0,
        message: ''
    };

    if (password.length < 8) {
        result.message = 'Le mot de passe doit contenir au moins 8 caractères';
        return result;
    }

    let strength = 0;
    
    // Vérifier la présence de différents types de caractères
    if (/[a-z]/.test(password)) strength++;
    if (/[A-Z]/.test(password)) strength++;
    if (/[0-9]/.test(password)) strength++;
    if (/[^a-zA-Z0-9]/.test(password)) strength++;

    result.strength = strength;

    if (strength < 2) {
        result.message = 'Mot de passe faible. Ajoutez des majuscules, chiffres ou caractères spéciaux';
    } else if (strength === 2) {
        result.message = 'Mot de passe moyen';
        result.isValid = true;
    } else if (strength === 3) {
        result.message = 'Mot de passe fort';
        result.isValid = true;
    } else {
        result.message = 'Mot de passe très fort';
        result.isValid = true;
    }

    return result;
}

/**
 * Hacher le mot de passe (simulation - en production utiliser bcrypt côté serveur)
 */
function hashPassword(password) {
    // En production, ceci doit être fait côté serveur avec bcrypt
    // Ici on utilise une simple simulation pour la démo
    let hash = 0;
    for (let i = 0; i < password.length; i++) {
        const char = password.charCodeAt(i);
        hash = ((hash << 5) - hash) + char;
        hash = hash & hash;
    }
    return 'hashed_' + Math.abs(hash).toString(16);
}

/**
 * Valider les entrées de formulaire
 */
function validateFormInput(input, type) {
    const value = input.value.trim();
    const errorElement = input.parentElement.querySelector('.error-message');
    
    // Supprimer l'ancien message d'erreur
    if (errorElement) {
        errorElement.remove();
    }

    let isValid = true;
    let errorMessage = '';

    switch(type) {
        case 'email':
            if (!value) {
                errorMessage = 'L\'email est requis';
                isValid = false;
            } else if (!validateEmail(value)) {
                errorMessage = 'Format d\'email invalide';
                isValid = false;
            }
            break;

        case 'phone':
            if (!value) {
                errorMessage = 'Le numéro de téléphone est requis';
                isValid = false;
            } else if (!validatePhone(value)) {
                errorMessage = 'Format de téléphone invalide (ex: +229 XX XX XX XX)';
                isValid = false;
            }
            break;

        case 'password':
            const passwordValidation = validatePasswordStrength(value);
            if (!passwordValidation.isValid) {
                errorMessage = passwordValidation.message;
                isValid = false;
            }
            break;

        case 'required':
            if (!value) {
                errorMessage = 'Ce champ est requis';
                isValid = false;
            }
            break;
    }

    // Afficher le message d'erreur
    if (!isValid) {
        const error = document.createElement('div');
        error.className = 'error-message';
        error.textContent = errorMessage;
        error.style.color = 'var(--danger-red)';
        error.style.fontSize = '0.875rem';
        error.style.marginTop = '0.25rem';
        error.setAttribute('role', 'alert');
        input.parentElement.appendChild(error);
        input.setAttribute('aria-invalid', 'true');
        input.setAttribute('aria-describedby', 'error-' + input.id);
        error.id = 'error-' + input.id;
    } else {
        input.removeAttribute('aria-invalid');
        input.removeAttribute('aria-describedby');
    }

    return isValid;
}

/**
 * Afficher l'indicateur de force du mot de passe
 */
function showPasswordStrength(input, strengthIndicator) {
    const password = input.value;
    const validation = validatePasswordStrength(password);
    
    if (!strengthIndicator) return;

    strengthIndicator.style.display = password ? 'block' : 'none';
    
    const colors = ['var(--danger-red)', 'var(--warning)', 'var(--success-green)', 'var(--success-green)'];
    const widths = ['25%', '50%', '75%', '100%'];
    
    const bar = strengthIndicator.querySelector('.strength-bar');
    const text = strengthIndicator.querySelector('.strength-text');
    
    if (bar && text && password) {
        bar.style.width = widths[validation.strength - 1] || '0%';
        bar.style.backgroundColor = colors[validation.strength - 1] || 'var(--danger-red)';
        text.textContent = validation.message;
    }
}

// ===== DONNÉES SIMULÉES ===== 
const testimonials = [
    {
        name: "Aminata Traoré",
        location: "Bamako, Mali",
        text: "ShopEasy m'a permis de créer ma boutique de vêtements en ligne en quelques minutes. Mes ventes ont augmenté de 300% !",
        avatar: "AT"
    },
    {
        name: "Jean-Claude Kouassi",
        location: "Abidjan, Côte d'Ivoire",
        text: "Interface très intuitive et support client exceptionnel. Je recommande vivement ShopEasy à tous les entrepreneurs.",
        avatar: "JK"
    },
    {
        name: "Fatou Ndiaye",
        location: "Dakar, Sénégal",
        text: "Grâce à ShopEasy, j'ai pu développer mon business de cosmétiques naturels à travers toute l'Afrique de l'Ouest.",
        avatar: "FN"
    },
    {
        name: "Mohamed Hassan",
        location: "Casablanca, Maroc",
        text: "Excellent service ! La plateforme est rapide, sécurisée et parfaitement adaptée au marché africain.",
        avatar: "MH"
    }
];

// Sample shops data
const sampleShops = [
    {
        id: '1',
        name: 'Mode Africaine Elegance',
        category: 'mode',
        description: 'Boutique spécialisée dans les vêtements traditionnels africains',
        address: 'Cotonou, Bénin',
        phone: '+229 XX XX XX XX',
        logo: 'https://images.unsplash.com/photo-1560472354-b33ff0c44a43?ixlib=rb-4.0.3&auto=format&fit=crop&w=600&q=80',
        banner: 'https://images.unsplash.com/photo-1560472354-b33ff0c44a43?ixlib=rb-4.0.3&auto=format&fit=crop&w=1200&q=80',
        rating: 4.5,
        ratingCount: 245,
        published: true,
        footer: '© 2025 Mode Africaine Elegance. Tous droits réservés.'
    },
    {
        id: '2',
        name: 'TechAfrique Store',
        category: 'electronique',
        description: 'Votre destination pour les derniers gadgets électroniques',
        address: 'Bohicon, Bénin',
        phone: '+229 XX XX XX XX',
        logo: 'https://images.unsplash.com/photo-1585155770447-2f66e2a397b5?ixlib=rb-4.0.3&auto=format&fit=crop&w=600&q=80',
        banner: 'https://images.unsplash.com/photo-1585155770447-2f66e2a397b5?ixlib=rb-4.0.3&auto=format&fit=crop&w=1200&q=80',
        rating: 4.0,
        ratingCount: 189,
        published: true,
        footer: '© 2025 TechAfrique Store. Tous droits réservés.'
    }
];

// ===== TOAST NOTIFICATIONS =====
function showToast(message, type = 'info', title = '') {
    const container = document.getElementById('toastContainer');
    if (!container) return;

    const toast = document.createElement('div');
    toast.className = `toast ${type}`;
    toast.setAttribute('role', 'alert');
    toast.setAttribute('aria-live', 'assertive');
    toast.setAttribute('aria-atomic', 'true');
    
    const icons = {
        success: 'fa-check-circle',
        error: 'fa-exclamation-circle',
        warning: 'fa-exclamation-triangle',
        info: 'fa-info-circle'
    };

    const titles = {
        success: title || 'Succès',
        error: title || 'Erreur',
        warning: title || 'Attention',
        info: title || 'Information'
    };

    toast.innerHTML = `
        <div class="toast-icon" aria-hidden="true">
            <i class="fas ${icons[type]}"></i>
        </div>
        <div class="toast-content">
            <div class="toast-title">${sanitizeHTML(titles[type])}</div>
            <div class="toast-message">${sanitizeHTML(message)}</div>
        </div>
        <button class="toast-close" onclick="closeToast(this)" aria-label="Fermer la notification">
            <i class="fas fa-times" aria-hidden="true"></i>
        </button>
    `;

    container.appendChild(toast);

    // Auto remove after 5 seconds
    setTimeout(() => {
        closeToast(toast.querySelector('.toast-close'));
    }, 5000);
}

function closeToast(button) {
    const toast = button.closest('.toast');
    if (toast) {
        toast.classList.add('removing');
        setTimeout(() => {
            toast.remove();
        }, 300);
    }
}

// ===== FONCTIONS D'INITIALISATION ===== 
document.addEventListener('DOMContentLoaded', function() {
    initializeApp();
    loadTestimonials();
    startHeroCarousel();
    initializeAnimations();
    loadUserData();
    setupEventListeners();
    loadShops();
    loadPublicProducts();
    initializeTheme();
    setupAccessibility();
});

function initializeApp() {
    // Vérifier si l'utilisateur est connecté
    const userData = localStorage.getItem('currentUser');
    if (userData) {
        currentUser = JSON.parse(userData);
        updateUserInterface();
    }

    // Charger les données sauvegardées
    loadProducts();
    loadOrders();
    loadReviews();
    loadShopData();
    loadCart();
    loadAllShops();
    updateDashboardStats();
}

function setupEventListeners() {
    // Gestion des formulaires
    setupFormEventListeners();

    // Gestion du redimensionnement de la fenêtre
    window.addEventListener('resize', handleWindowResize);

    // Gestion des touches clavier
    document.addEventListener('keydown', handleKeyDown);

    // Gestion des clics extérieurs pour fermer les menus/modales
    document.addEventListener('click', handleOutsideClick);
}

// ===== ACCESSIBILITÉ (A11Y) =====

function setupAccessibility() {
    // Ajouter des attributs ARIA aux éléments interactifs
    setupARIAAttributes();
    
    // Gérer le focus dans les modales
    setupModalFocusManagement();
    
    // Gérer la navigation au clavier
    setupKeyboardNavigation();
}

function setupARIAAttributes() {
    // Navigation
    const nav = document.querySelector('.nav');
    if (nav) {
        nav.setAttribute('role', 'navigation');
        nav.setAttribute('aria-label', 'Navigation principale');
    }

    // Boutons
    document.querySelectorAll('button:not([aria-label])').forEach(button => {
        const text = button.textContent.trim() || button.querySelector('i')?.className;
        if (text && !button.getAttribute('aria-label')) {
            button.setAttribute('aria-label', text);
        }
    });

    // Liens
    document.querySelectorAll('a[onclick]').forEach(link => {
        link.setAttribute('role', 'button');
        if (!link.getAttribute('aria-label')) {
            link.setAttribute('aria-label', link.textContent.trim());
        }
    });

    // Formulaires
    document.querySelectorAll('input, select, textarea').forEach(input => {
        const label = input.previousElementSibling;
        if (label && label.tagName === 'LABEL' && !input.id) {
            const id = 'input-' + Math.random().toString(36).substr(2, 9);
            input.id = id;
            label.setAttribute('for', id);
        }
        
        if (input.hasAttribute('required')) {
            input.setAttribute('aria-required', 'true');
        }
    });

    // Images
    document.querySelectorAll('img:not([alt])').forEach(img => {
        img.setAttribute('alt', '');
    });

    // Sections
    document.querySelectorAll('section').forEach(section => {
        if (!section.getAttribute('aria-label') && !section.getAttribute('aria-labelledby')) {
            const heading = section.querySelector('h1, h2, h3');
            if (heading) {
                const id = 'heading-' + Math.random().toString(36).substr(2, 9);
                heading.id = id;
                section.setAttribute('aria-labelledby', id);
            }
        }
    });
}

function setupModalFocusManagement() {
    document.querySelectorAll('.modal').forEach(modal => {
        modal.addEventListener('shown', function() {
            // Sauvegarder l'élément qui avait le focus
            modal.dataset.previousFocus = document.activeElement;
            
            // Trouver le premier élément focusable
            const focusableElements = modal.querySelectorAll(
                'button, [href], input, select, textarea, [tabindex]:not([tabindex="-1"])'
            );
            
            if (focusableElements.length > 0) {
                focusableElements[0].focus();
            }
            
            // Piéger le focus dans la modale
            trapFocus(modal);
        });
        
        modal.addEventListener('hidden', function() {
            // Restaurer le focus
            const previousFocus = document.querySelector(`[data-modal-trigger="${modal.id}"]`) || 
                                document.activeElement;
            if (previousFocus) {
                previousFocus.focus();
            }
        });
    });
}

function trapFocus(element) {
    const focusableElements = element.querySelectorAll(
        'button:not([disabled]), [href], input:not([disabled]), select:not([disabled]), textarea:not([disabled]), [tabindex]:not([tabindex="-1"])'
    );
    
    const firstFocusable = focusableElements[0];
    const lastFocusable = focusableElements[focusableElements.length - 1];
    
    element.addEventListener('keydown', function(e) {
        if (e.key === 'Tab') {
            if (e.shiftKey) {
                if (document.activeElement === firstFocusable) {
                    lastFocusable.focus();
                    e.preventDefault();
                }
            } else {
                if (document.activeElement === lastFocusable) {
                    firstFocusable.focus();
                    e.preventDefault();
                }
            }
        }
    });
}

function setupKeyboardNavigation() {
    // Navigation dans les carousels avec les flèches
    document.addEventListener('keydown', function(e) {
        if (e.target.closest('.carousel-indicator')) {
            const indicators = document.querySelectorAll('.carousel-indicator');
            const currentIndex = Array.from(indicators).indexOf(e.target);
            
            if (e.key === 'ArrowLeft' && currentIndex > 0) {
                indicators[currentIndex - 1].focus();
                indicators[currentIndex - 1].click();
            } else if (e.key === 'ArrowRight' && currentIndex < indicators.length - 1) {
                indicators[currentIndex + 1].focus();
                indicators[currentIndex + 1].click();
            }
        }
    });
    
    // Rendre les éléments cliquables accessibles au clavier
    document.querySelectorAll('[onclick]:not(button):not(a)').forEach(element => {
        if (!element.hasAttribute('tabindex')) {
            element.setAttribute('tabindex', '0');
        }
        
        element.addEventListener('keydown', function(e) {
            if (e.key === 'Enter' || e.key === ' ') {
                e.preventDefault();
                element.click();
            }
        });
    });
}

// ===== THEME MANAGEMENT ===== 
function initializeTheme() {
    const savedTheme = localStorage.getItem('theme') || 'light';
    document.documentElement.setAttribute('data-theme', savedTheme);
    updateThemeIcon(savedTheme);
}

function toggleTheme() {
    const currentTheme = document.documentElement.getAttribute('data-theme');
    const newTheme = currentTheme === 'dark' ? 'light' : 'dark';
    
    document.documentElement.setAttribute('data-theme', newTheme);
    localStorage.setItem('theme', newTheme);
    updateThemeIcon(newTheme);
    
    // Annoncer le changement pour les lecteurs d'écran
    const announcement = document.createElement('div');
    announcement.setAttribute('role', 'status');
    announcement.setAttribute('aria-live', 'polite');
    announcement.className = 'sr-only';
    announcement.textContent = `Thème changé en mode ${newTheme === 'dark' ? 'sombre' : 'clair'}`;
    document.body.appendChild(announcement);
    setTimeout(() => announcement.remove(), 1000);
}

function updateThemeIcon(theme) {
    const icon = document.getElementById('themeIcon');
    const button = icon?.closest('button');
    if (icon) {
        icon.className = theme === 'dark' ? 'fas fa-sun' : 'fas fa-moon';
    }
    if (button) {
        button.setAttribute('aria-label', theme === 'dark' ? 'Activer le mode clair' : 'Activer le mode sombre');
    }
}

// ===== NAVIGATION HISTORY =====
function pushNavigation(page, data = {}) {
    navigationHistory.push({ page, data });
}

function popNavigation() {
    if (navigationHistory.length > 0) {
        navigationHistory.pop();
        const previous = navigationHistory[navigationHistory.length - 1];
        if (previous) {
            return previous;
        }
    }
    return null;
}

// ===== FONCTIONS DE NAVIGATION ===== 
function showSection(section) {
    const homePage = document.getElementById('homePage');
    const dashboardPage = document.getElementById('dashboardPage');
    const allShopsPage = document.getElementById('allShopsPage');
    const allProductsPage = document.getElementById('allProductsPage');
    const shopViewPage = document.getElementById('shopViewPage');
    const header = document.getElementById('mainHeader');
    const footer = document.querySelector('.footer');

    // Hide all pages
    homePage.classList.add('hidden');
    dashboardPage.classList.add('hidden');
    allShopsPage.classList.add('hidden');
    allProductsPage.classList.add('hidden');
    shopViewPage.classList.add('hidden');

    if (section === 'home') {
        homePage.classList.remove('hidden');
        header.classList.remove('hidden');
        footer.style.display = 'block';
        navigationHistory = [];
        
        // Annoncer le changement de page
        announcePageChange('Page d\'accueil');
    } else if (section === 'dashboard') {
        if (!currentUser) {
            openLoginModal();
            return;
        }
        if (currentUser.userType !== 'vendor') {
            showToast('Accès refusé. Seuls les vendeurs peuvent accéder au tableau de bord.', 'error');
            return;
        }
        dashboardPage.classList.remove('hidden');
        header.classList.add('hidden');
        footer.style.display = 'none';
        navigationHistory = [{ page: 'dashboard', data: {} }];
        
        announcePageChange('Tableau de bord');
    }
}

function announcePageChange(pageName) {
    const announcement = document.createElement('div');
    announcement.setAttribute('role', 'status');
    announcement.setAttribute('aria-live', 'polite');
    announcement.className = 'sr-only';
    announcement.textContent = `Navigation vers ${pageName}`;
    document.body.appendChild(announcement);
    setTimeout(() => announcement.remove(), 1000);
}

function scrollToSection(sectionId) {
    const element = document.getElementById(sectionId);
    if (element) {
        element.scrollIntoView({ behavior: 'smooth' });
        
        // Mettre le focus sur la section pour l'accessibilité
        element.setAttribute('tabindex', '-1');
        element.focus();
    }
}

function showAllShops() {
    const allShopsPage = document.getElementById('allShopsPage');
    const homePage = document.getElementById('homePage');
    const header = document.getElementById('mainHeader');
    
    homePage.classList.add('hidden');
    allShopsPage.classList.remove('hidden');
    header.classList.remove('hidden');
    
    pushNavigation('allShops');
    loadAllShopsGrid();
    announcePageChange('Toutes les boutiques');
}

function showAllProducts() {
    const allProductsPage = document.getElementById('allProductsPage');
    const homePage = document.getElementById('homePage');
    const header = document.getElementById('mainHeader');
    
    homePage.classList.add('hidden');
    allProductsPage.classList.remove('hidden');
    header.classList.remove('hidden');
    
    pushNavigation('allProducts');
    loadAllProductsGrid();
    announcePageChange('Tous les produits');
}

// ===== FONCTIONS DE MENU MOBILE ===== 
function toggleMobileMenu() {
    const mobileMenu = document.getElementById('mobileMenu');
    const hamburger = document.querySelector('.hamburger');
    
    mobileMenu.classList.toggle('active');
    hamburger.classList.toggle('active');

    // Empêcher le scroll du body quand le menu est ouvert
    if (mobileMenu.classList.contains('active')) {
        document.body.style.overflow = 'hidden';
    } else {
        document.body.style.overflow = 'auto';
    }

    // Mettre à jour les attributs d'accessibilité
    const isActive = mobileMenu.classList.contains('active');
    hamburger.setAttribute('aria-expanded', isActive);
    hamburger.setAttribute('aria-label', isActive ? 'Fermer le menu' : 'Ouvrir le menu');
    mobileMenu.setAttribute('aria-hidden', !isActive);
}

function handleWindowResize() {
    const mobileMenu = document.getElementById('mobileMenu');
    const hamburger = document.querySelector('.hamburger');

    // Fermer le menu mobile si on passe en mode desktop
    if (window.innerWidth > 768 && mobileMenu.classList.contains('active')) {
        mobileMenu.classList.remove('active');
        hamburger.classList.remove('active');
        document.body.style.overflow = 'auto';
    }
}

function handleKeyDown(e) {
    const mobileMenu = document.getElementById('mobileMenu');
    const hamburger = document.querySelector('.hamburger');

    // Fermer le menu avec la touche Escape
    if (e.key === 'Escape') {
        if (mobileMenu.classList.contains('active')) {
            mobileMenu.classList.remove('active');
            hamburger.classList.remove('active');
            document.body.style.overflow = 'auto';
            hamburger.focus();
        }
        
        // Fermer les modales ouvertes
        document.querySelectorAll('.modal.active').forEach(modal => {
            closeModal(modal.id);
        });
    }
}

function handleOutsideClick(e) {
    const mobileMenu = document.getElementById('mobileMenu');
    const hamburger = document.querySelector('.hamburger');
    const nav = document.querySelector('.nav');

    // Fermer le menu mobile si on clique en dehors
    if (mobileMenu.classList.contains('active') && !nav.contains(e.target)) {
        mobileMenu.classList.remove('active');
        hamburger.classList.remove('active');
        document.body.style.overflow = 'auto';
    }

    // Fermer les modales si on clique en dehors
    if (e.target.classList.contains('modal')) {
        e.target.classList.remove('active');
        document.body.style.overflow = 'auto';
    }
}

// ===== FONCTIONS DE CARROUSEL HÉROS ===== 
function startHeroCarousel() {
    const carousel = document.getElementById('heroCarousel');
    if (!carousel) return;

    setInterval(() => {
        heroCarouselIndex = (heroCarouselIndex + 1) % 3;
        updateCarousel();
    }, 5000);
}

function goToSlide(index) {
    heroCarouselIndex = index;
    updateCarousel();
}

function updateCarousel() {
    const carousel = document.getElementById('heroCarousel');
    const indicators = document.querySelectorAll('.carousel-indicator');
    
    if (!carousel) return;

    carousel.style.transform = `translateX(-${heroCarouselIndex * 100}%)`;
    
    indicators.forEach((indicator, index) => {
        const isActive = index === heroCarouselIndex;
        indicator.classList.toggle('active', isActive);
        indicator.setAttribute('aria-current', isActive ? 'true' : 'false');
        indicator.setAttribute('aria-label', `Aller à la diapositive ${index + 1}`);
    });
}

// ===== FONCTIONS D'ANIMATION ===== 
function initializeAnimations() {
    // Animation au scroll
    const observerOptions = {
        threshold: 0.1,
        rootMargin: '0px 0px -50px 0px'
    };

    const observer = new IntersectionObserver((entries) => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                entry.target.classList.add('visible');
                
                // Animation spéciale pour la section résultats
                if (entry.target.closest('.results') && !statsAnimated) {
                    animateStats();
                    statsAnimated = true;
                }
            }
        });
    }, observerOptions);

    document.querySelectorAll('.fade-in').forEach(el => {
        observer.observe(el);
    });
}

function animateStats() {
    const stats = [
        { id: 'statShops', target: 12500, suffix: '' },
        { id: 'statSales', target: 8200, suffix: '' },
        { id: 'statCountries', target: 54, suffix: '' },
        { id: 'statRevenue', target: 47, suffix: '' }
    ];

    stats.forEach(stat => {
        animateNumber(stat.id, stat.target, stat.suffix);
    });
}

function animateNumber(elementId, target, suffix = '') {
    const element = document.getElementById(elementId);
    if (!element) return;

    let current = 0;
    const increment = target / 60;
    const duration = 2000;
    const stepTime = duration / 60;

    element.classList.add('counting');

    const timer = setInterval(() => {
        current += increment;
        if (current >= target) {
            current = target;
            clearInterval(timer);
            element.classList.remove('counting');
        }
        const displayValue = Math.floor(current).toLocaleString();
        element.textContent = displayValue + suffix;
    }, stepTime);
}

// ===== FONCTIONS DE TÉMOIGNAGES ===== 
function loadTestimonials() {
    const container = document.getElementById('testimonialsContainer');
    if (!container) return;

    container.innerHTML = '';
    
    testimonials.forEach((testimonial, index) => {
        const testimonialElement = document.createElement('div');
        testimonialElement.className = 'testimonial-card';
        testimonialElement.setAttribute('role', 'article');
        testimonialElement.setAttribute('aria-label', `Avis de ${testimonial.name}`);
        testimonialElement.innerHTML = `
            <div class="testimonial-avatar" aria-hidden="true">${sanitizeHTML(testimonial.avatar)}</div>
            <div class="testimonial-name">${sanitizeHTML(testimonial.name)}</div>
            <div class="testimonial-location">${sanitizeHTML(testimonial.location)}</div>
            <div class="testimonial-text">"${sanitizeHTML(testimonial.text)}"</div>
        `;
        container.appendChild(testimonialElement);
    });
}

function nextTestimonial() {
    const container = document.getElementById('testimonialsContainer');
    if (!container) return;

    currentTestimonial = (currentTestimonial + 1) % testimonials.length;
    container.style.transform = `translateX(-${currentTestimonial * 100}%)`;
    
    announceCarouselChange('Avis suivant');
}

function previousTestimonial() {
    const container = document.getElementById('testimonialsContainer');
    if (!container) return;

    currentTestimonial = currentTestimonial === 0 ? testimonials.length - 1 : currentTestimonial - 1;
    container.style.transform = `translateX(-${currentTestimonial * 100}%)`;
    
    announceCarouselChange('Avis précédent');
}

function announceCarouselChange(message) {
    const announcement = document.createElement('div');
    announcement.setAttribute('role', 'status');
    announcement.setAttribute('aria-live', 'polite');
    announcement.className = 'sr-only';
    announcement.textContent = message;
    document.body.appendChild(announcement);
    setTimeout(() => announcement.remove(), 1000);
}

// ===== FAQ FUNCTIONS ===== 
function toggleFaq(element) {
    const faqItem = element.parentElement;
    const isActive = faqItem.classList.contains('active');

    // Close all FAQ items
    document.querySelectorAll('.faq-item').forEach(item => {
        item.classList.remove('active');
        const question = item.querySelector('.faq-question');
        question.setAttribute('aria-expanded', 'false');
    });

    // Toggle current item
    if (!isActive) {
        faqItem.classList.add('active');
        element.setAttribute('aria-expanded', 'true');
    }
}

// ===== FONCTIONS DE MODALES ===== 
function openModal(modalId) {
    const modal = document.getElementById(modalId);
    if (!modal) return;

    modal.classList.add('active');
    document.body.style.overflow = 'hidden';
    
    // Gestion du focus
    modal.dispatchEvent(new Event('shown'));
    
    // Annoncer l'ouverture de la modale
    const title = modal.querySelector('.modal-header h3')?.textContent || 'Modale';
    const announcement = document.createElement('div');
    announcement.setAttribute('role', 'status');
    announcement.setAttribute('aria-live', 'polite');
    announcement.className = 'sr-only';
    announcement.textContent = `${title} ouvert`;
    document.body.appendChild(announcement);
    setTimeout(() => announcement.remove(), 1000);
}

function closeModal(modalId) {
    const modal = document.getElementById(modalId);
    if (!modal) return;

    modal.classList.remove('active');
    document.body.style.overflow = 'auto';
    
    // Gestion du focus
    modal.dispatchEvent(new Event('hidden'));
}

function openLoginModal() {
    openModal('loginModal');
}

function openRegisterModal() {
    openModal('registerModal');
}

function openReviewModal() {
    // Reset rating
    selectedRating = 0;
    document.querySelectorAll('.star-rating i').forEach(star => {
        star.classList.remove('fas');
        star.classList.add('far');
    });
    document.getElementById('ratingLabel').textContent = 'Sélectionnez une note';
    
    openModal('reviewModal');
}

function openProductModal(productId = null) {
    const modal = document.getElementById('productModal');
    const title = document.getElementById('productModalTitle');
    const form = document.getElementById('productForm');
    
    if (!modal || !title || !form) return;

    // Initialize Quill editor if not already initialized
    if (!currentProductEditor) {
        currentProductEditor = new Quill('#productDescriptionEditor', {
            theme: 'snow',
            modules: {
                toolbar: [
                    [{ 'header': [1, 2, 3, false] }],
                    ['bold', 'italic', 'underline'],
                    ['link', 'image'],
                    [{ 'list': 'ordered'}, { 'list': 'bullet' }],
                    ['clean']
                ]
            }
        });
    }

    if (productId) {
        const product = products.find(p => p.id === productId);
        if (product) {
            title.textContent = 'Modifier le produit';
            document.getElementById('productId').value = product.id;
            document.getElementById('productName').value = product.name;
            document.getElementById('productPrice').value = product.price;
            document.getElementById('productStock').value = product.stock;
            document.getElementById('productCategory').value = product.category;
            currentProductEditor.root.innerHTML = product.description;

            // Preview existing images
            productImages = product.images || [];
            displayProductImages();
        }
    } else {
        title.textContent = 'Ajouter un produit';
        form.reset();
        document.getElementById('productId').value = '';
        currentProductEditor.root.innerHTML = '';
        productImages = [];
        document.getElementById('productImagesPreview').innerHTML = '';
    }

    openModal('productModal');
}

function openCartModal() {
    loadCartItems();
    openModal('cartModal');
}

function openProductReviewModal() {
    if (!currentViewingProduct) return;
    
    // Reset rating
    selectedProductReviewRating = 0;
    const stars = document.querySelectorAll('#productReviewStars i');
    stars.forEach(star => {
        star.classList.remove('fas');
        star.classList.add('far');
    });
    document.getElementById('productReviewRatingLabel').textContent = 'Sélectionnez une note';
    
    openModal('productReviewModal');
}

// ===== STAR RATING FUNCTIONS ===== 
function setRating(rating) {
    selectedRating = rating;
    document.getElementById('reviewRating').value = rating;
    updateStarDisplay(rating);
    updateRatingLabel(rating);
}

function hoverRating(rating) {
    updateStarDisplay(rating);
    updateRatingLabel(rating);
}

function resetRating() {
    updateStarDisplay(selectedRating);
    if (selectedRating > 0) {
        updateRatingLabel(selectedRating);
    } else {
        document.getElementById('ratingLabel').textContent = 'Sélectionnez une note';
    }
}

function updateStarDisplay(rating) {
    const stars = document.querySelectorAll('.star-rating i');
    stars.forEach((star, index) => {
        if (index < rating) {
            star.classList.remove('far');
            star.classList.add('fas');
        } else {
            star.classList.remove('fas');
            star.classList.add('far');
        }
        star.setAttribute('aria-label', `${index + 1} étoile${index > 0 ? 's' : ''}`);
    });
}

function updateRatingLabel(rating) {
    const labels = {
        1: '⭐ Insatisfait',
        2: '⭐⭐ Pas mal',
        3: '⭐⭐⭐ Moyen',
        4: '⭐⭐⭐⭐ Bien',
        5: '⭐⭐⭐⭐⭐ Excellent'
    };
    document.getElementById('ratingLabel').textContent = labels[rating] || 'Sélectionnez une note';
}

// Product Review Rating Functions
function setProductReviewRating(rating) {
    selectedProductReviewRating = rating;
    document.getElementById('productReviewRating').value = rating;
    updateProductReviewStarDisplay(rating);
    updateProductReviewRatingLabel(rating);
}

function hoverProductReviewRating(rating) {
    updateProductReviewStarDisplay(rating);
    updateProductReviewRatingLabel(rating);
}

function resetProductReviewRating() {
    updateProductReviewStarDisplay(selectedProductReviewRating);
    if (selectedProductReviewRating > 0) {
        updateProductReviewRatingLabel(selectedProductReviewRating);
    } else {
        document.getElementById('productReviewRatingLabel').textContent = 'Sélectionnez une note';
    }
}

function updateProductReviewStarDisplay(rating) {
    const stars = document.querySelectorAll('#productReviewStars i');
    stars.forEach((star, index) => {
        if (index < rating) {
            star.classList.remove('far');
            star.classList.add('fas');
        } else {
            star.classList.remove('fas');
            star.classList.add('far');
        }
    });
}

function updateProductReviewRatingLabel(rating) {
    const labels = {
        1: '⭐ Insatisfait',
        2: '⭐⭐ Pas mal',
        3: '⭐⭐⭐ Moyen',
        4: '⭐⭐⭐⭐ Bien',
        5: '⭐⭐⭐⭐⭐ Excellent'
    };
    document.getElementById('productReviewRatingLabel').textContent = labels[rating] || 'Sélectionnez une note';
}

// ===== GOOGLE AUTH (Interface ready for backend) ===== 
function loginWithGoogle() {
    showToast('Connexion avec Google sera disponible prochainement. Cette fonctionnalité nécessite une intégration backend.', 'info');
}

function registerWithGoogle() {
    showToast('Inscription avec Google sera disponible prochainement. Cette fonctionnalité nécessite une intégration backend.', 'info');
}

// ===== USER TYPE SELECTION =====
function selectUserType(type) {
    const pendingUser = JSON.parse(localStorage.getItem('pendingUser'));
    if (!pendingUser) return;

    pendingUser.userType = type;
    currentUser = pendingUser;
    
    localStorage.setItem('currentUser', JSON.stringify(currentUser));
    localStorage.removeItem('pendingUser');
    
    closeModal('userTypeModal');
    updateUserInterface();
    
    if (type === 'vendor') {
        showToast('Compte vendeur créé avec succès ! Vous pouvez maintenant créer votre boutique.', 'success');
        showSection('dashboard');
    } else {
        showToast('Compte client créé avec succès ! Vous pouvez maintenant acheter des produits.', 'success');
        showSection('home');
    }
}

// ===== FONCTIONS D'AUTHENTIFICATION ===== 
function setupFormEventListeners() {
    // Formulaire de connexion
    const loginForm = document.getElementById('loginForm');
    if (loginForm) {
        loginForm.addEventListener('submit', handleLogin);
        
        // Validation en temps réel
        const loginEmail = document.getElementById('loginEmail');
        const loginPassword = document.getElementById('loginPassword');
        
        if (loginEmail) {
            loginEmail.addEventListener('blur', () => validateFormInput(loginEmail, 'email'));
        }
        if (loginPassword) {
            loginPassword.addEventListener('blur', () => validateFormInput(loginPassword, 'required'));
        }
    }

    // Formulaire d'inscription
    // const registerForm = document.getElementById('registerForm');
    // if (registerForm) {
    //     registerForm.addEventListener('submit', handleRegister);
        
    //     // Validation en temps réel et indicateur de force du mot de passe
    //     const registerEmail = document.getElementById('registerEmail');
    //     const registerPassword = document.getElementById('registerPassword');
    //     const registerConfirmPassword = document.getElementById('registerConfirmPassword');
        
    //     if (registerEmail) {
    //         registerEmail.addEventListener('blur', () => validateFormInput(registerEmail, 'email'));
    //     }
        
    //     if (registerPassword) {
    //         // Créer l'indicateur de force du mot de passe
    //         const strengthIndicator = document.createElement('div');
    //         strengthIndicator.className = 'password-strength';
    //         strengthIndicator.style.display = 'none';
    //         strengthIndicator.innerHTML = `
    //             <div class="strength-bar-container" style="height: 4px; background: var(--light-gray); border-radius: 2px; margin-top: 0.5rem;">
    //                 <div class="strength-bar" style="height: 100%; width: 0%; transition: all 0.3s; border-radius: 2px;"></div>
    //             </div>
    //             <div class="strength-text" style="font-size: 0.875rem; margin-top: 0.25rem; color: var(--medium-gray);"></div>
    //         `;
    //         registerPassword.parentElement.appendChild(strengthIndicator);
            
    //         registerPassword.addEventListener('input', () => {
    //             showPasswordStrength(registerPassword, strengthIndicator);
    //         });
    //         registerPassword.addEventListener('blur', () => validateFormInput(registerPassword, 'password'));
    //     }
        
    //     if (registerConfirmPassword) {
    //         registerConfirmPassword.addEventListener('blur', function() {
    //             if (this.value !== registerPassword.value) {
    //                 const errorElement = this.parentElement.querySelector('.error-message');
    //                 if (errorElement) errorElement.remove();
                    
    //                 const error = document.createElement('div');
    //                 error.className = 'error-message';
    //                 error.textContent = 'Les mots de passe ne correspondent pas';
    //                 error.style.color = 'var(--danger-red)';
    //                 error.style.fontSize = '0.875rem';
    //                 error.style.marginTop = '0.25rem';
    //                 this.parentElement.appendChild(error);
    //                 this.setAttribute('aria-invalid', 'true');
    //             } else {
    //                 const errorElement = this.parentElement.querySelector('.error-message');
    //                 if (errorElement) errorElement.remove();
    //                 this.removeAttribute('aria-invalid');
    //             }
    //         });
    //     }
    // }

    // Formulaire d'avis
    const reviewForm = document.getElementById('reviewForm');
    if (reviewForm) {
        reviewForm.addEventListener('submit', handleReviewSubmit);
    }

    // Formulaire d'avis produit
    const productReviewForm = document.getElementById('productReviewForm');
    if (productReviewForm) {
        productReviewForm.addEventListener('submit', handleProductReviewSubmit);
    }

    // Formulaire de produit
    const productForm = document.getElementById('productForm');
    if (productForm) {
        productForm.addEventListener('submit', handleProductSubmit);
    }

    // Formulaire de boutique
    const shopForm = document.getElementById('shopForm');
    if (shopForm) {
        shopForm.addEventListener('submit', handleShopSubmit);
        
        // Validation en temps réel
        const shopPhone = document.getElementById('shopPhone');
        if (shopPhone) {
            shopPhone.addEventListener('blur', () => validateFormInput(shopPhone, 'phone'));
        }
    }

    // Formulaire de paramètres
    const settingsForm = document.getElementById('settingsForm');
    if (settingsForm) {
        settingsForm.addEventListener('submit', handleSettingsSubmit);
    }

    // Payment forms
    const paymentForm = document.getElementById('paymentForm');
    if (paymentForm) {
        paymentForm.addEventListener('submit', handlePayment);
        
        const paymentPhone = document.getElementById('paymentPhone');
        if (paymentPhone) {
            paymentPhone.addEventListener('blur', () => validateFormInput(paymentPhone, 'phone'));
        }
    }

    const planPaymentForm = document.getElementById('planPaymentForm');
    if (planPaymentForm) {
        planPaymentForm.addEventListener('submit', handlePlanPayment);
        
        const planPaymentPhone = document.getElementById('planPaymentPhone');
        if (planPaymentPhone) {
            planPaymentPhone.addEventListener('blur', () => validateFormInput(planPaymentPhone, 'phone'));
        }
    }
}

// function handleLogin(e) {
//     e.preventDefault();
    
//     const emailInput = document.getElementById('loginEmail');
//     const passwordInput = document.getElementById('loginPassword');444
    
//     const email = emailInput.value.trim();
//     const password = passwordInput.value;

//     // Validation
//     const emailValid = validateFormInput(emailInput, 'email');
//     const passwordValid = validateFormInput(passwordInput, 'required');
    
//     if (!emailValid || !passwordValid) {
//         showToast('Veuillez corriger les erreurs dans le formulaire.', 'error');
//         return;
//     }

//     if (email && password) {
//         // Backend will validate credentials
//         // For now, check if user exists in localStorage
//         const existingUsers = JSON.parse(localStorage.getItem('allUsers') || '[]');
//         const user = existingUsers.find(u => u.email === email);
        
//         if (user && user.passwordHash === hashPassword(password)) {
//             currentUser = user;
//             localStorage.setItem('currentUser', JSON.stringify(currentUser));
//             updateUserInterface();
//             closeModal('loginModal');
            
//             if (currentUser.userType === 'vendor') {
//                 showSection('dashboard');
//                 showToast('Connexion réussie ! Bienvenue sur votre tableau de bord.', 'success');
//             } else {
//                 showSection('home');
//                 showToast('Connexion réussie ! Bienvenue sur ShopEasy.', 'success');
//             }
//         } else {
//             showToast('Email ou mot de passe incorrect.', 'error');
//         }
//     }
// }

function handleRegister(e) {
    e.preventDefault();
    
    const firstNameInput = document.getElementById('registerFirstName');
    const lastNameInput = document.getElementById('registerLastName');
    const emailInput = document.getElementById('registerEmail');
    const passwordInput = document.getElementById('registerPassword');
    const confirmPasswordInput = document.getElementById('registerConfirmPassword');
    
    const firstName = firstNameInput.value.trim();
    const lastName = lastNameInput.value.trim();
    const email = emailInput.value.trim();
    const password = passwordInput.value;
    const confirmPassword = confirmPasswordInput.value;

    // Validation
    const emailValid = validateFormInput(emailInput, 'email');
    const passwordValid = validateFormInput(passwordInput, 'password');
    
    if (!emailValid || !passwordValid) {
        showToast('Veuillez corriger les erreurs dans le formulaire.', 'error');
        return;
    }

    if (password !== confirmPassword) {
        showToast('Les mots de passe ne correspondent pas.', 'error');
        return;
    }

    // Check if email already exists
    const existingUsers = JSON.parse(localStorage.getItem('allUsers') || '[]');
    if (existingUsers.find(u => u.email === email)) {
        showToast('Un compte avec cet email existe déjà.', 'error');
        return;
    }

    // Create pending user with hashed password
    const pendingUser = {
        id: Date.now(),
        email: email,
        firstName: firstName,
        lastName: lastName,
        passwordHash: hashPassword(password),
        plan: 'free'
    };

    localStorage.setItem('pendingUser', JSON.stringify(pendingUser));
    closeModal('registerModal');
    openModal('userTypeModal');
}

function logout() {
    currentUser = null;
    localStorage.removeItem('currentUser');
    showSection('home');
    
    const userNameElement = document.getElementById('userName');
    if (userNameElement) {
        userNameElement.textContent = 'Utilisateur';
    }

    // Hide cart button
    const cartBtn = document.getElementById('cartBtn');
    if (cartBtn) {
        cartBtn.classList.add('hidden');
    }
    
    showToast('Déconnexion réussie.', 'info');
}

function updateUserInterface() {
    if (currentUser) {
        const userNameElement = document.getElementById('userName');
        if (userNameElement) {
            userNameElement.textContent = `${currentUser.firstName} ${currentUser.lastName}`;
        }

        // Show cart button only for customers
        const cartBtn = document.getElementById('cartBtn');
        if (cartBtn && currentUser.userType === 'customer') {
            cartBtn.classList.remove('hidden');
            updateCartCount();
        }

        // Save user to all users list
        const existingUsers = JSON.parse(localStorage.getItem('allUsers') || '[]');
        const userIndex = existingUsers.findIndex(u => u.id === currentUser.id);
        if (userIndex === -1) {
            existingUsers.push(currentUser);
            localStorage.setItem('allUsers', JSON.stringify(existingUsers));
        }
    }
}

// ===== IMAGE PREVIEW FUNCTIONS ===== 
function previewImage(input, previewId) {
    const preview = document.getElementById(previewId);
    if (!preview) return;

    preview.innerHTML = '';

    if (input.files && input.files[0]) {
        const reader = new FileReader();
        reader.onload = function(e) {
            const img = document.createElement('img');
            img.src = e.target.result;
            img.alt = 'Aperçu de l\'image';
            preview.appendChild(img);
        };
        reader.readAsDataURL(input.files[0]);
    }
}

function previewProductImages(input) {
    const preview = document.getElementById('productImagesPreview');
    if (!preview) return;

    if (input.files) {
        const newFiles = Array.from(input.files);
        
        // Check total images limit (8 max)
        if (productImages.length + newFiles.length > 8) {
            showToast('Vous ne pouvez ajouter que 8 images maximum.', 'warning');
            input.value = '';
            return;
        }

        newFiles.forEach(file => {
            const reader = new FileReader();
            reader.onload = function(e) {
                productImages.push(e.target.result);
                displayProductImages();
            };
            reader.readAsDataURL(file);
        });

        // Clear input
        input.value = '';
    }
}

function displayProductImages() {
    const preview = document.getElementById('productImagesPreview');
    if (!preview) return;

    preview.innerHTML = '';

    productImages.forEach((img, index) => {
        const imageItem = document.createElement('div');
        imageItem.className = 'image-item';
        imageItem.innerHTML = `
            <img src="${img}" alt="Image du produit ${index + 1}">
            <button type="button" class="remove-image" onclick="removeProductImage(${index})" aria-label="Supprimer l'image ${index + 1}">
                <i class="fas fa-times" aria-hidden="true"></i>
            </button>
        `;
        preview.appendChild(imageItem);
    });
}

function removeProductImage(index) {
    productImages.splice(index, 1);
    displayProductImages();
    showToast('Image supprimée.', 'info');
}

// ===== FONCTIONS DE SÉLECTION DE PLAN ===== 
function selectPlan(plan) {
    if (!currentUser) {
        openRegisterModal();
        return;
    }

    if (plan === 'free') {
        currentUser.plan = plan;
        localStorage.setItem('currentUser', JSON.stringify(currentUser));
        showToast('Plan Gratuit activé avec succès !', 'success');
        showSection('dashboard');
    } else {
        // Open payment modal for paid plans
        openPlanPaymentModal(plan);
    }
}

function openPlanPaymentModal(plan) {
    const planNames = {
        'standard': 'Standard',
        'premium': 'Premium'
    };
    const planPrices = {
        'standard': '9 000 F',
        'premium': '19 000 F'
    };

    document.getElementById('planPaymentName').textContent = planNames[plan];
    document.getElementById('planPaymentPlanName').textContent = planNames[plan];
    document.getElementById('planPaymentPrice').textContent = planPrices[plan];
    
    openModal('planPaymentModal');
}

function selectPlanPaymentMethod(method) {
    document.querySelectorAll('input[name="planPaymentMethod"]').forEach(radio => {
        radio.checked = radio.value === method;
    });
}

function handlePlanPayment(e) {
    e.preventDefault();
    
    const method = document.querySelector('input[name="planPaymentMethod"]:checked');
    const phoneInput = document.getElementById('planPaymentPhone');
    const phone = phoneInput.value.trim();

    if (!method) {
        showToast('Veuillez sélectionner un moyen de paiement.', 'warning');
        return;
    }
    
    if (!validateFormInput(phoneInput, 'phone')) {
        showToast('Veuillez entrer un numéro de téléphone valide.', 'error');
        return;
    }

    showToast(`Paiement en cours via ${method.value.toUpperCase()}...\nVous recevrez une notification de confirmation sur votre téléphone.`, 'info');
    closeModal('planPaymentModal');

    // Simulate successful payment
    setTimeout(() => {
        const plan = document.getElementById('planPaymentPlanName').textContent.toLowerCase();
        currentUser.plan = plan;
        localStorage.setItem('currentUser', JSON.stringify(currentUser));
        showToast('Paiement réussi ! Votre plan a été activé.', 'success');
    }, 2000);
}

// ===== FONCTIONS DE DASHBOARD ===== 
function showDashboardSection(section) {
    // Masquer toutes les sections
    document.querySelectorAll('.content-section').forEach(el => {
        el.classList.add('hidden');
        el.setAttribute('aria-hidden', 'true');
    });

    // Retirer la classe active de tous les liens
    document.querySelectorAll('.sidebar-menu a').forEach(el => {
        el.classList.remove('active');
        el.setAttribute('aria-current', 'false');
    });

    // Afficher la section sélectionnée
    const sectionElement = document.getElementById(section + 'Section');
    if (sectionElement) {
        sectionElement.classList.remove('hidden');
        sectionElement.removeAttribute('aria-hidden');
    }

    // Ajouter la classe active au lien sélectionné
    if (event && event.target) {
        event.target.classList.add('active');
        event.target.setAttribute('aria-current', 'page');
    }

    // Charger les données spécifiques à la section
    switch(section) {
        case 'products':
            loadProductsTable();
            break;
        case 'orders':
            loadOrdersTable();
            break;
        case 'reviews':
            loadReviewsList();
            break;
        case 'overview':
            updateDashboardStats();
            break;
    }
    
    // Annoncer le changement de section
    const sectionNames = {
        'overview': 'Vue d\'ensemble',
        'shop': 'Ma boutique',
        'products': 'Produits',
        'orders': 'Commandes',
        'reviews': 'Avis',
        'settings': 'Paramètres'
    };
    announcePageChange(sectionNames[section] || section);
}

function updateDashboardStats() {
    const totalProductsElement = document.getElementById('totalProducts');
    const totalOrdersElement = document.getElementById('totalOrders');
    const totalRevenueElement = document.getElementById('totalRevenue');
    const averageRatingElement = document.getElementById('averageRating');

    if (totalProductsElement) totalProductsElement.textContent = products.length;
    if (totalOrdersElement) totalOrdersElement.textContent = orders.length;
    
    if (totalRevenueElement) {
        const revenue = orders.reduce((sum, order) => sum + order.total, 0);
        totalRevenueElement.textContent = revenue.toLocaleString() + ' F';
    }
    
    if (averageRatingElement) {
        // Calculate average from product reviews
        let totalRating = 0;
        let totalReviews = 0;
        
        products.forEach(product => {
            if (product.reviews && product.reviews.length > 0) {
                product.reviews.forEach(review => {
                    totalRating += review.rating;
                    totalReviews++;
                });
            }
        });
        
        const avgRating = totalReviews > 0 ? (totalRating / totalReviews).toFixed(1) : '5.0';
        averageRatingElement.textContent = avgRating;
    }
}

// ===== FONCTIONS DE GESTION DES PRODUITS ===== 
function handleProductSubmit(e) {
    e.preventDefault();

    // Check if shop is created
    if (!shopData.name) {
        showToast('Veuillez d\'abord créer votre boutique avant d\'ajouter des produits.', 'warning');
        showDashboardSection('shop');
        return;
    }

    const productId = document.getElementById('productId').value;
    
    // Get description from Quill editor and sanitize
    const description = currentProductEditor.root.innerHTML;

    const productData = {
        id: productId || Date.now().toString(),
        name: sanitizeHTML(document.getElementById('productName').value),
        price: parseFloat(document.getElementById('productPrice').value),
        stock: parseInt(document.getElementById('productStock').value),
        category: document.getElementById('productCategory').value,
        description: description, // Already sanitized by Quill
        images: [...productImages],
        status: 'active',
        shopId: currentUser.id,
        shopName: shopData.name,
        reviews: productId ? (products.find(p => p.id === productId)?.reviews || []) : [],
        createdAt: productId ? (products.find(p => p.id === productId)?.createdAt) : new Date().toISOString()
    };

    if (productId) {
        const index = products.findIndex(p => p.id === productId);
        if (index !== -1) {
            products[index] = { ...products[index], ...productData };
        }
        showToast(`Produit "${productData.name}" modifié avec succès.`, 'success');
    } else {
        products.push(productData);
        showToast(`Produit "${productData.name}" ajouté avec succès.`, 'success');
        
        // Enable publish button if this is the first product
        if (products.length === 1) {
            enableShopPublish();
        }
    }

    saveProducts();
    loadProductsTable();
    updateDashboardStats();
    closeModal('productModal');
}

function loadProductsTable() {
    const tbody = document.getElementById('productsTable');
    if (!tbody) return;

    tbody.innerHTML = '';

    if (products.length === 0) {
        tbody.innerHTML = '<tr><td colspan="6" style="text-align: center; color: var(--medium-gray);">Aucun produit. Ajoutez votre premier produit !</td></tr>';
        return;
    }

    products.forEach(product => {
        const row = document.createElement('tr');
        row.innerHTML = `
            <td>
                <div style="width: 50px; height: 50px; background: var(--light-gray); border-radius: 8px; display: flex; align-items: center; justify-content: center;">
                    ${product.images && product.images.length > 0 
                        ? `<img src="${product.images[0]}" alt="${sanitizeHTML(product.name)}" style="width: 100%; height: 100%; object-fit: cover; border-radius: 8px;">` 
                        : '<i class="fas fa-image" style="color: var(--medium-gray);" aria-hidden="true"></i>'}
                </div>
            </td>
            <td>${sanitizeHTML(product.name)}</td>
            <td>${product.price.toLocaleString()} F</td>
            <td>${product.stock}</td>
            <td>
                <span style="padding: 4px 8px; border-radius: 4px; background: var(--success-green); color: white; font-size: 12px;">
                    ${product.status === 'active' ? 'Actif' : 'Inactif'}
                </span>
            </td>
            <td>
                <div class="action-buttons">
                    <button class="btn btn-sm btn-primary" onclick="openProductModal('${product.id}')" aria-label="Modifier ${sanitizeHTML(product.name)}">
                        <i class="fas fa-edit" aria-hidden="true"></i>
                    </button>
                    <button class="btn btn-sm btn-danger" onclick="deleteProduct('${product.id}')" aria-label="Supprimer ${sanitizeHTML(product.name)}">
                        <i class="fas fa-trash" aria-hidden="true"></i>
                    </button>
                </div>
            </td>
        `;
        tbody.appendChild(row);
    });
}

function deleteProduct(productId) {
    if (confirm('Êtes-vous sûr de vouloir supprimer ce produit ?')) {
        const product = products.find(p => p.id === productId);
        products = products.filter(p => p.id !== productId);
        saveProducts();
        loadProductsTable();
        updateDashboardStats();
        showToast(`Produit "${product.name}" supprimé avec succès.`, 'success');

        // Disable publish if no products left
        if (products.length === 0) {
            disableShopPublish();
        }
    }
}

function saveProducts() {
    localStorage.setItem('products', JSON.stringify(products));
}

function loadProducts() {
    const savedProducts = localStorage.getItem('products');
    if (savedProducts) {
        products = JSON.parse(savedProducts);
    }
}

// ===== SEARCH FUNCTIONS ===== 
function searchProducts() {
    const searchTerm = document.getElementById('productSearch').value.toLowerCase();
    const allProducts = getAllPublishedProducts();
    
    const filtered = allProducts.filter(product =>
        product.name.toLowerCase().includes(searchTerm) ||
        product.category.toLowerCase().includes(searchTerm)
    );
    
    displayProducts(filtered, 'productsGrid');
}

function searchShops() {
    const searchTerm = document.getElementById('shopSearch').value.toLowerCase();
    const publishedShops = allShops.filter(shop => shop.published);
    
    const filtered = publishedShops.filter(shop =>
        shop.name.toLowerCase().includes(searchTerm) ||
        shop.category.toLowerCase().includes(searchTerm) ||
        shop.address.toLowerCase().includes(searchTerm)
    );
    
    displayShops(filtered, 'allShopsGrid');
}

function searchAllProducts() {
    const searchTerm = document.getElementById('allProductSearch').value.toLowerCase();
    const allProducts = getAllPublishedProducts();
    
    const filtered = allProducts.filter(product =>
        product.name.toLowerCase().includes(searchTerm) ||
        product.category.toLowerCase().includes(searchTerm) ||
        product.shopName.toLowerCase().includes(searchTerm)
    );
    
    displayProducts(filtered, 'allProductsGrid');
}

// ===== SHOP FUNCTIONS ===== 
function handleShopSubmit(e) {
    e.preventDefault();

    const logoInput = document.getElementById('shopLogo');
    const bannerInput = document.getElementById('shopBanner');
    const phoneInput = document.getElementById('shopPhone');
    
    // Validation
    if (!validateFormInput(phoneInput, 'phone')) {
        showToast('Veuillez entrer un numéro de téléphone valide.', 'error');
        return;
    }

    shopData = {
        id: currentUser.id,
        name: sanitizeHTML(document.getElementById('shopName').value),
        category: document.getElementById('shopCategory').value,
        description: sanitizeHTML(document.getElementById('shopDescription').value),
        address: sanitizeHTML(document.getElementById('shopAddress').value),
        phone: phoneInput.value.trim(),
        footer: sanitizeHTML(document.getElementById('shopFooter').value) || `© 2025 ${sanitizeHTML(document.getElementById('shopName').value)}. Tous droits réservés.`,
        logo: logoInput.files.length > 0 ? URL.createObjectURL(logoInput.files[0]) : shopData.logo,
        banner: bannerInput.files.length > 0 ? URL.createObjectURL(bannerInput.files[0]) : shopData.banner,
        published: shopData.published || false,
        rating: shopData.rating || 5.0,
        ratingCount: shopData.ratingCount || 0,
        updatedAt: new Date().toISOString()
    };

    saveShopData();
    enableShopPreview();

    // Enable publish only if products exist
    if (products.length > 0) {
        enableShopPublish();
        showToast('Boutique sauvegardée avec succès !', 'success');
    } else {
        showToast('Boutique sauvegardée ! Ajoutez au moins un produit pour pouvoir publier votre boutique.', 'warning');
        showDashboardSection('products');
    }
}

function enableShopPreview() {
    const previewBtn = document.getElementById('previewShopBtn');
    if (previewBtn) {
        previewBtn.disabled = false;
        previewBtn.setAttribute('aria-disabled', 'false');
    }
}

function enableShopPublish() {
    const publishBtn = document.getElementById('publishShopBtn');
    if (publishBtn && shopData.name && products.length > 0) {
        publishBtn.disabled = false;
        publishBtn.setAttribute('aria-disabled', 'false');
    }
}

function disableShopPublish() {
    const publishBtn = document.getElementById('publishShopBtn');
    if (publishBtn) {
        publishBtn.disabled = true;
        publishBtn.setAttribute('aria-disabled', 'true');
    }
}

function previewShop() {
    if (!shopData.name) {
        showToast('Veuillez d\'abord configurer votre boutique.', 'warning');
        return;
    }
    
    pushNavigation('shopPreview', { from: 'shop' });
    viewShop(shopData);
}

function publishShop() {
    if (!shopData.name) {
        showToast('Veuillez d\'abord configurer votre boutique.', 'warning');
        return;
    }

    if (products.length === 0) {
        showToast('Vous devez ajouter au moins un produit avant de publier votre boutique.', 'warning');
        return;
    }

    shopData.published = true;
    saveShopData();

    // Add to all shops
    const existingIndex = allShops.findIndex(s => s.id === shopData.id);
    if (existingIndex !== -1) {
        allShops[existingIndex] = shopData;
    } else {
        allShops.push(shopData);
    }
    saveAllShops();

    showToast('Félicitations ! Votre boutique est maintenant publiée et visible par tous les utilisateurs.', 'success');
}

function viewShop(shop) {
    currentViewingShop = shop;
    
    const shopViewPage = document.getElementById('shopViewPage');
    const homePage = document.getElementById('homePage');
    const dashboardPage = document.getElementById('dashboardPage');
    const allShopsPage = document.getElementById('allShopsPage');
    const header = document.getElementById('mainHeader');
    const footer = document.querySelector('.footer');

    // Hide all pages
    homePage.classList.add('hidden');
    dashboardPage.classList.add('hidden');
    allShopsPage.classList.add('hidden');
    header.classList.remove('hidden');
    footer.style.display = 'none';

    // Show shop view
    shopViewPage.classList.remove('hidden');

    // Populate shop details
    const banner = document.getElementById('shopViewBanner');
    const logo = document.getElementById('shopViewLogo');
    const name = document.getElementById('shopViewName');
    const category = document.getElementById('shopViewCategory');
    const address = document.getElementById('shopViewAddress');
    const phone = document.getElementById('shopViewPhone');
    const description = document.getElementById('shopViewDescription');
    const footerText = document.getElementById('shopViewFooterText');

    if (banner) banner.style.backgroundImage = shop.banner ? `url(${shop.banner})` : 'none';
    if (logo) {
        logo.src = shop.logo || 'https://via.placeholder.com/120';
        logo.alt = `Logo de ${shop.name}`;
    }
    if (name) name.textContent = shop.name;
    if (category) category.textContent = getCategoryName(shop.category);
    if (address) address.textContent = shop.address;
    if (phone) phone.textContent = shop.phone;
    if (description) description.textContent = shop.description;
    if (footerText) footerText.textContent = shop.footer;

    // Load shop products
    loadShopProducts(shop.id);
    
    announcePageChange(`Boutique ${shop.name}`);
}

function closeShopView() {
    const previous = popNavigation();
    
    if (previous && previous.page === 'shopPreview' && previous.data.from === 'shop') {
        // Return to dashboard shop section
        showSection('dashboard');
        showDashboardSection('shop');
    } else {
        // Return to home
        showSection('home');
    }
}

function loadShopProducts(shopId) {
    const container = document.getElementById('shopViewProducts');
    if (!container) return;

    const shopProducts = products.filter(p => p.shopId === shopId);

    if (shopProducts.length === 0) {
        container.innerHTML = '<p style="text-align: center; color: var(--medium-gray);">Aucun produit disponible pour le moment.</p>';
        return;
    }

    displayProducts(shopProducts, 'shopViewProducts');
}

function getCategoryName(category) {
    const categories = {
        'mode': 'Mode & Vêtements',
        'electronique': 'Électronique',
        'maison': 'Maison & Jardin',
        'beaute': 'Beauté & Santé',
        'sport': 'Sport & Loisirs',
        'alimentaire': 'Alimentaire'
    };
    return categories[category] || category;
}

function saveShopData() {
    localStorage.setItem('shopData', JSON.stringify(shopData));
}

function loadShopData() {
    const savedShopData = localStorage.getItem('shopData');
    if (savedShopData) {
        shopData = JSON.parse(savedShopData);

        // Remplir le formulaire avec les données sauvegardées
        if (shopData.name) {
            const shopNameElement = document.getElementById('shopName');
            const shopCategoryElement = document.getElementById('shopCategory');
            const shopDescriptionElement = document.getElementById('shopDescription');
            const shopAddressElement = document.getElementById('shopAddress');
            const shopPhoneElement = document.getElementById('shopPhone');
            const shopFooterElement = document.getElementById('shopFooter');

            if (shopNameElement) shopNameElement.value = shopData.name;
            if (shopCategoryElement) shopCategoryElement.value = shopData.category || '';
            if (shopDescriptionElement) shopDescriptionElement.value = shopData.description || '';
            if (shopAddressElement) shopAddressElement.value = shopData.address || '';
            if (shopPhoneElement) shopPhoneElement.value = shopData.phone || '';
            if (shopFooterElement) shopFooterElement.value = shopData.footer || '';

            // Preview images
            if (shopData.logo) {
                const logoPreview = document.getElementById('logoPreview');
                if (logoPreview) {
                    logoPreview.innerHTML = `<img src="${shopData.logo}" alt="Logo de la boutique">`;
                }
            }
            if (shopData.banner) {
                const bannerPreview = document.getElementById('bannerPreview');
                if (bannerPreview) {
                    bannerPreview.innerHTML = `<img src="${shopData.banner}" alt="Bannière de la boutique">`;
                }
            }

            enableShopPreview();
            if (products.length > 0) {
                enableShopPublish();
            }
        }
    }
}

// ===== ALL SHOPS FUNCTIONS ===== 
function saveAllShops() {
    localStorage.setItem('allShops', JSON.stringify(allShops));
}

function loadAllShops() {
    const saved = localStorage.getItem('allShops');
    if (saved) {
        allShops = JSON.parse(saved);
    } else {
        // Initialize with sample shops
        allShops = [...sampleShops];
        saveAllShops();
    }
}

function loadShops() {
    const publishedShops = allShops.filter(shop => shop.published).slice(0, 4);
    displayShops(publishedShops, 'shopsGrid');
}

function loadAllShopsGrid() {
    const publishedShops = allShops.filter(shop => shop.published);
    displayShops(publishedShops, 'allShopsGrid');
}

function displayShops(shops, containerId) {
    const container = document.getElementById(containerId);
    if (!container) return;

    container.innerHTML = '';

    if (shops.length === 0) {
        container.innerHTML = '<p style="grid-column: 1/-1; text-align: center; color: var(--medium-gray);">Aucune boutique disponible.</p>';
        return;
    }

    shops.forEach(shop => {
        const card = document.createElement('div');
        card.className = 'shop-card';
        card.setAttribute('role', 'article');
        card.setAttribute('aria-label', `Boutique ${shop.name}`);
        card.innerHTML = `
            <div class="shop-image" style="background-image: url('${shop.banner || shop.logo || 'https://via.placeholder.com/600x400'}');" role="img" aria-label="Image de ${sanitizeHTML(shop.name)}"></div>
            <div class="shop-info">
                <div style="display: flex; align-items: center; gap: 10px; margin-bottom: 10px;">
                    <img src="${shop.logo || 'https://via.placeholder.com/60'}" class="shop-logo-circle" alt="Logo de ${sanitizeHTML(shop.name)}">
                    <h3 class="shop-name">${sanitizeHTML(shop.name)}</h3>
                </div>
                <div class="shop-rating">
                    <div class="stars" aria-label="Note: ${shop.rating} sur 5">
                        ${generateStars(shop.rating)}
                    </div>
                    <span class="rating-count">(${shop.ratingCount})</span>
                </div>
                <div class="shop-category">${getCategoryName(shop.category)}</div>
                <div class="shop-location">
                    <i class="fas fa-map-marker-alt" aria-hidden="true"></i>
                    ${sanitizeHTML(shop.address)}
                </div>
                <div class="shop-actions">
                    <button class="btn btn-primary" onclick='viewShop(${JSON.stringify(shop).replace(/'/g, "&#39;")})' aria-label="Visiter la boutique ${sanitizeHTML(shop.name)}">
                        Visiter
                    </button>
                </div>
            </div>
        `;
        container.appendChild(card);
    });
}

function generateStars(rating) {
    let stars = '';
    const fullStars = Math.floor(rating);
    const hasHalfStar = rating % 1 >= 0.5;

    for (let i = 0; i < fullStars; i++) {
        stars += '<i class="fas fa-star" aria-hidden="true"></i>';
    }
    if (hasHalfStar) {
        stars += '<i class="fas fa-star-half-alt" aria-hidden="true"></i>';
    }
    const emptyStars = 5 - Math.ceil(rating);
    for (let i = 0; i < emptyStars; i++) {
        stars += '<i class="far fa-star" aria-hidden="true"></i>';
    }

    return stars;
}

// ===== PRODUCTS DISPLAY FUNCTIONS ===== 
function loadPublicProducts() {
    const allProducts = getAllPublishedProducts().slice(0, 4);
    displayProducts(allProducts, 'productsGrid');
}

function loadAllProductsGrid() {
    const allProducts = getAllPublishedProducts();
    displayProducts(allProducts, 'allProductsGrid');
}

function getAllPublishedProducts() {
    // Get products from published shops
    const publishedShopIds = allShops.filter(s => s.published).map(s => s.id);
    const allProducts = products.filter(p => publishedShopIds.includes(p.shopId));
    return allProducts;
}

function displayProducts(productsList, containerId) {
    const container = document.getElementById(containerId);
    if (!container) return;

    container.innerHTML = '';

    if (productsList.length === 0) {
        container.innerHTML = '<p style="grid-column: 1/-1; text-align: center; color: var(--medium-gray);">Aucun produit disponible.</p>';
        return;
    }

    productsList.forEach(product => {
        const card = document.createElement('div');
        card.className = 'product-card';
        card.setAttribute('role', 'article');
        card.setAttribute('aria-label', `Produit ${product.name}`);
        
        const imageUrl = product.images && product.images.length > 0 
            ? product.images[0] 
            : 'https://via.placeholder.com/400x300';

        // Calculate average rating from reviews
        let avgRating = 0;
        if (product.reviews && product.reviews.length > 0) {
            const totalRating = product.reviews.reduce((sum, review) => sum + review.rating, 0);
            avgRating = totalRating / product.reviews.length;
        }

        card.innerHTML = `
            <div class="product-image" style="background-image: url('${imageUrl}');" role="img" aria-label="Image de ${sanitizeHTML(product.name)}">
                ${product.badge ? `<div class="product-badge">${sanitizeHTML(product.badge)}</div>` : ''}
            </div>
            <div class="product-info">
                <h3 class="product-name">${sanitizeHTML(product.name)}</h3>
                <div class="product-price">${product.price.toLocaleString()} F</div>
                <div class="product-rating">
                    <div class="stars" aria-label="Note: ${avgRating || 5} sur 5">
                        ${generateStars(avgRating || 5)}
                    </div>
                    <span class="rating-count">(${product.reviews ? product.reviews.length : 0})</span>
                </div>
                <div class="product-actions">
                    <button class="btn btn-primary" onclick='openProductDescription(${JSON.stringify(product).replace(/'/g, "&#39;")})' aria-label="Voir la description de ${sanitizeHTML(product.name)}">
                        Description
                    </button>
                    <button class="btn btn-outline" onclick='buyProductDirect(${JSON.stringify(product).replace(/'/g, "&#39;")})' aria-label="Acheter ${sanitizeHTML(product.name)}">
                        Acheter
                    </button>
                </div>
            </div>
        `;
        container.appendChild(card);
    });
}

function openProductDescription(product) {
    currentViewingProduct = product;
    const modal = document.getElementById('productDescModal');

    // Set product details
    document.getElementById('productDescTitle').textContent = product.name;
    document.getElementById('productDescName').textContent = product.name;
    document.getElementById('productDescShop').textContent = product.shopName;
    document.getElementById('productDescPrice').textContent = product.price.toLocaleString() + ' F';

    // Set description (with responsive images)
    const descContent = document.getElementById('productDescText');
    descContent.innerHTML = product.description;
    
    // Make images responsive
    descContent.querySelectorAll('img').forEach(img => {
        img.style.maxWidth = '100%';
        img.style.height = 'auto';
        if (!img.alt) img.alt = 'Image du produit';
    });

    // Calculate and display rating
    let avgRating = 0;
    if (product.reviews && product.reviews.length > 0) {
        const totalRating = product.reviews.reduce((sum, review) => sum + review.rating, 0);
        avgRating = totalRating / product.reviews.length;
    }
    
    const ratingElement = document.getElementById('productDescRating');
    ratingElement.innerHTML = generateStars(avgRating || 5);
    ratingElement.setAttribute('aria-label', `Note: ${avgRating || 5} sur 5`);
    
    const ratingCountElement = document.getElementById('productDescRatingCount');
    ratingCountElement.textContent = `(${product.reviews ? product.reviews.length : 0})`;

    // Set images
    const mainImage = document.getElementById('productDescMainImage');
    const thumbnails = document.getElementById('productDescThumbnails');

    if (product.images && product.images.length > 0) {
        mainImage.innerHTML = `<img src="${product.images[0]}" alt="${sanitizeHTML(product.name)}">`;
        
        thumbnails.innerHTML = '';
        product.images.forEach((img, index) => {
            const thumb = document.createElement('img');
            thumb.src = img;
            thumb.alt = `${sanitizeHTML(product.name)} - Image ${index + 1}`;
            thumb.className = index === 0 ? 'active' : '';
            thumb.setAttribute('role', 'button');
            thumb.setAttribute('tabindex', '0');
            thumb.setAttribute('aria-label', `Voir l'image ${index + 1}`);
            thumb.onclick = () => {
                mainImage.innerHTML = `<img src="${img}" alt="${sanitizeHTML(product.name)}">`;
                thumbnails.querySelectorAll('img').forEach(t => t.classList.remove('active'));
                thumb.classList.add('active');
            };
            thumb.onkeydown = (e) => {
                if (e.key === 'Enter' || e.key === ' ') {
                    e.preventDefault();
                    thumb.click();
                }
            };
            thumbnails.appendChild(thumb);
        });
    } else {
        mainImage.innerHTML = '<img src="https://via.placeholder.com/400" alt="Aucune image disponible">';
        thumbnails.innerHTML = '';
    }

    // Load product reviews
    loadProductReviews(product);

    // Show/hide add to cart button based on user type
    const addToCartBtn = document.getElementById('addToCartBtn');
    if (addToCartBtn) {
        if (currentUser && currentUser.userType === 'customer') {
            addToCartBtn.style.display = 'flex';
        } else {
            addToCartBtn.style.display = 'none';
        }
    }

    // Store current product for purchase
    window.currentProduct = product;
    
    openModal('productDescModal');
}

function loadProductReviews(product) {
    const reviewsList = document.getElementById('productReviewsList');
    if (!reviewsList) return;

    reviewsList.innerHTML = '';

    if (!product.reviews || product.reviews.length === 0) {
        reviewsList.innerHTML = '<p style="text-align: center; color: var(--medium-gray); padding: 1rem;">Aucun avis pour ce produit.</p>';
        return;
    }

    product.reviews.forEach(review => {
        const reviewItem = document.createElement('div');
        reviewItem.className = 'product-review-item';
        reviewItem.setAttribute('role', 'article');
        reviewItem.setAttribute('aria-label', `Avis de ${review.name}`);
        reviewItem.innerHTML = `
            <div class="review-header">
                <div class="review-author">${sanitizeHTML(review.name)}</div>
                <div class="review-stars" aria-label="Note: ${review.rating} sur 5">${generateStars(review.rating)}</div>
            </div>
            <div class="review-date">${new Date(review.createdAt).toLocaleDateString()}</div>
            <div class="review-text">${sanitizeHTML(review.text)}</div>
        `;
        reviewsList.appendChild(reviewItem);
    });
}

function handleProductReviewSubmit(e) {
    e.preventDefault();

    if (selectedProductReviewRating === 0) {
        showToast('Veuillez sélectionner une note.', 'warning');
        return;
    }

    if (!currentViewingProduct) return;

    const reviewData = {
        id: Date.now().toString(),
        name: sanitizeHTML(document.getElementById('productReviewName').value),
        rating: selectedProductReviewRating,
        text: sanitizeHTML(document.getElementById('productReviewText').value),
        createdAt: new Date().toISOString()
    };

    // Find the product and add review
    const productIndex = products.findIndex(p => p.id === currentViewingProduct.id);
    if (productIndex !== -1) {
        if (!products[productIndex].reviews) {
            products[productIndex].reviews = [];
        }
        products[productIndex].reviews.push(reviewData);
        saveProducts();

        // Update current viewing product
        currentViewingProduct = products[productIndex];

        // Reload reviews
        loadProductReviews(currentViewingProduct);

        // Update rating display
        const totalRating = currentViewingProduct.reviews.reduce((sum, review) => sum + review.rating, 0);
        const avgRating = totalRating / currentViewingProduct.reviews.length;
        
        const ratingElement = document.getElementById('productDescRating');
        ratingElement.innerHTML = generateStars(avgRating);
        
        const ratingCountElement = document.getElementById('productDescRatingCount');
        ratingCountElement.textContent = `(${currentViewingProduct.reviews.length})`;

        closeModal('productReviewModal');
        showToast('Merci pour votre avis !', 'success');

        // Reset form
        document.getElementById('productReviewForm').reset();
        selectedProductReviewRating = 0;
    }
}

function buyProductDirect(product) {
    if (!currentUser) {
        // Open payment modal directly for guests
        window.currentProduct = product;
        openPaymentModal(product);
        return;
    }

    if (currentUser.userType === 'vendor') {
        showToast('Les vendeurs ne peuvent pas acheter de produits. Veuillez créer un compte client.', 'warning');
        return;
    }

    window.currentProduct = product;
    openPaymentModal(product);
}

function buyProduct() {
    if (!currentUser) {
        // Open payment modal directly for guests
        const product = window.currentProduct;
        if (product) {
            closeModal('productDescModal');
            openPaymentModal(product);
        }
        return;
    }

    if (currentUser.userType === 'vendor') {
        showToast('Les vendeurs ne peuvent pas acheter de produits. Veuillez créer un compte client.', 'warning');
        return;
    }

    const product = window.currentProduct;
    if (product) {
        closeModal('productDescModal');
        openPaymentModal(product);
    }
}

function addToCart() {
    if (!currentUser) {
        showToast('Veuillez vous connecter pour ajouter au panier.', 'warning');
        openLoginModal();
        return;
    }

    if (currentUser.userType !== 'customer') {
        showToast('Seuls les clients peuvent ajouter des produits au panier.', 'warning');
        return;
    }

    const product = window.currentProduct;
    if (product) {
        const existingItem = cart.find(item => item.id === product.id);
        if (existingItem) {
            existingItem.quantity += 1;
        } else {
            cart.push({ ...product, quantity: 1 });
        }
        
        saveCart();
        updateCartCount();
        closeModal('productDescModal');
        showToast('Produit ajouté au panier !', 'success');
    }
}

// ===== PAYMENT FUNCTIONS ===== 
function openPaymentModal(product) {
    document.getElementById('paymentProductName').textContent = product.name;
    document.getElementById('paymentProductPrice').textContent = product.price.toLocaleString() + ' F';
    document.getElementById('paymentTotal').textContent = product.price.toLocaleString() + ' F';
    
    window.currentPaymentProduct = product;
    
    openModal('paymentModal');
}

function selectPaymentMethod(method) {
    document.querySelectorAll('input[name="paymentMethod"]').forEach(radio => {
        radio.checked = radio.value === method;
    });
}

function handlePayment(e) {
    e.preventDefault();

    const method = document.querySelector('input[name="paymentMethod"]:checked');
    const phoneInput = document.getElementById('paymentPhone');
    const phone = phoneInput.value.trim();
    const name = sanitizeHTML(document.getElementById('paymentName').value);
    const address = sanitizeHTML(document.getElementById('paymentAddress').value);

    if (!method) {
        showToast('Veuillez sélectionner un moyen de paiement.', 'warning');
        return;
    }
    
    if (!validateFormInput(phoneInput, 'phone')) {
        showToast('Veuillez entrer un numéro de téléphone valide.', 'error');
        return;
    }

    const product = window.currentPaymentProduct;

    showToast(`Paiement en cours via ${method.value.toUpperCase()}...\nVous recevrez une notification de confirmation sur votre téléphone.`, 'info');
    closeModal('paymentModal');

    // Simulate successful payment
    setTimeout(() => {
        const order = {
            id: Date.now().toString(),
            customerName: name,
            customerPhone: phone,
            customerAddress: address,
            items: [{
                productId: product.id,
                productName: product.name,
                quantity: 1,
                price: product.price
            }],
            total: product.price,
            status: 'En cours',
            paymentMethod: method.value,
            createdAt: new Date().toISOString()
        };

        orders.push(order);
        saveOrders();
        
        showToast('Paiement réussi ! Votre commande a été enregistrée.', 'success');

        // Notify seller if current user is the seller
        if (currentUser && product.shopId === currentUser.id) {
            showToast(`Nouvelle commande de ${name} pour "${product.name}" - ${product.price.toLocaleString()} F`, 'success', 'Nouvelle commande');
        }
    }, 2000);
}

// ===== CART FUNCTIONS ===== 
function saveCart() {
    localStorage.setItem('cart', JSON.stringify(cart));
}

function loadCart() {
    const saved = localStorage.getItem('cart');
    if (saved) {
        cart = JSON.parse(saved);
        updateCartCount();
    }
}

function updateCartCount() {
    const countElement = document.getElementById('cartCount');
    if (countElement) {
        const totalItems = cart.reduce((sum, item) => sum + item.quantity, 0);
        countElement.textContent = totalItems;
        countElement.setAttribute('aria-label', `${totalItems} article${totalItems > 1 ? 's' : ''} dans le panier`);
    }
}

function loadCartItems() {
    const container = document.getElementById('cartItems');
    const subtotalElement = document.getElementById('cartSubtotal');
    const totalElement = document.getElementById('cartTotal');

    if (!container) return;

    if (cart.length === 0) {
        container.innerHTML = '<p class="empty-cart">Votre panier est vide</p>';
        if (subtotalElement) subtotalElement.textContent = '0 F';
        if (totalElement) totalElement.textContent = '0 F';
        return;
    }

    container.innerHTML = '';
    let subtotal = 0;

    cart.forEach(item => {
        subtotal += item.price * item.quantity;
        
        const cartItem = document.createElement('div');
        cartItem.className = 'cart-item';
        cartItem.setAttribute('role', 'article');
        cartItem.setAttribute('aria-label', `${item.name} dans le panier`);
        cartItem.innerHTML = `
            <div class="cart-item-image">
                <img src="${item.images && item.images[0] || 'https://via.placeholder.com/80'}" alt="${sanitizeHTML(item.name)}">
            </div>
            <div class="cart-item-info">
                <div class="cart-item-name">${sanitizeHTML(item.name)}</div>
                <div class="cart-item-price">${item.price.toLocaleString()} F</div>
                <div class="cart-item-quantity">
                    <button class="quantity-btn" onclick="updateCartQuantity('${item.id}', -1)" aria-label="Diminuer la quantité">-</button>
                    <span aria-label="Quantité: ${item.quantity}">${item.quantity}</span>
                    <button class="quantity-btn" onclick="updateCartQuantity('${item.id}', 1)" aria-label="Augmenter la quantité">+</button>
                </div>
            </div>
            <button class="cart-item-remove" onclick="removeFromCart('${item.id}')" aria-label="Retirer ${sanitizeHTML(item.name)} du panier">
                <i class="fas fa-trash" aria-hidden="true"></i>
            </button>
        `;
        container.appendChild(cartItem);
    });

    if (subtotalElement) subtotalElement.textContent = subtotal.toLocaleString() + ' F';
    if (totalElement) totalElement.textContent = subtotal.toLocaleString() + ' F';
}

function updateCartQuantity(productId, change) {
    const item = cart.find(i => i.id === productId);
    if (item) {
        item.quantity += change;
        if (item.quantity <= 0) {
            removeFromCart(productId);
        } else {
            saveCart();
            loadCartItems();
            updateCartCount();
        }
    }
}

function removeFromCart(productId) {
    cart = cart.filter(item => item.id !== productId);
    saveCart();
    loadCartItems();
    updateCartCount();
    showToast('Produit retiré du panier.', 'info');
}

function checkoutCart() {
    if (cart.length === 0) {
        showToast('Votre panier est vide.', 'warning');
        return;
    }

    showToast('Fonction de paiement du panier en cours de développement. Utilisez le bouton "Acheter" sur chaque produit individuellement.', 'info');
}

// ===== ORDERS FUNCTIONS ===== 
function loadOrdersTable() {
    const tbody = document.getElementById('ordersTable');
    if (!tbody) return;

    tbody.innerHTML = '';

    if (orders.length === 0) {
        tbody.innerHTML = '<tr><td colspan="7" style="text-align: center; color: var(--medium-gray);">Aucune commande pour le moment</td></tr>';
        return;
    }

    orders.forEach(order => {
        const row = document.createElement('tr');
        row.innerHTML = `
            <td>#${order.id}</td>
            <td>${sanitizeHTML(order.customerName)}</td>
            <td>${order.items.length} produit(s)</td>
            <td>${order.total.toLocaleString()} F</td>
            <td>${new Date(order.createdAt).toLocaleDateString()}</td>
            <td>
                <span style="padding: 4px 8px; border-radius: 4px; background: var(--primary-gold); color: var(--primary-black); font-size: 12px;">
                    ${order.status}
                </span>
            </td>
            <td>
                <div class="action-buttons">
                    <button class="btn btn-sm btn-primary" onclick="viewOrder('${order.id}')" aria-label="Voir la commande #${order.id}">
                        <i class="fas fa-eye" aria-hidden="true"></i>
                    </button>
                </div>
            </td>
        `;
        tbody.appendChild(row);
    });
}

function viewOrder(orderId) {
    const order = orders.find(o => o.id === orderId);
    if (order) {
        showToast(`Détails de la commande #${order.id}\nClient: ${order.customerName}\nTotal: ${order.total.toLocaleString()} F`, 'info', 'Commande');
    }
}

function saveOrders() {
    localStorage.setItem('orders', JSON.stringify(orders));
}

function loadOrders() {
    const savedOrders = localStorage.getItem('orders');
    if (savedOrders) {
        orders = JSON.parse(savedOrders);
    }
}

// ===== REVIEWS FUNCTIONS ===== 
function handleReviewSubmit(e) {
    e.preventDefault();

    if (selectedRating === 0) {
        showToast('Veuillez sélectionner une note.', 'warning');
        return;
    }

    const reviewData = {
        id: Date.now().toString(),
        name: sanitizeHTML(document.getElementById('reviewName').value),
        location: sanitizeHTML(document.getElementById('reviewLocation').value),
        rating: selectedRating,
        text: sanitizeHTML(document.getElementById('reviewText').value),
        createdAt: new Date().toISOString()
    };

    reviews.push(reviewData);
    saveReviews();
    
    closeModal('reviewModal');

    // Ajouter l'avis aux témoignages
    testimonials.push({
        name: reviewData.name,
        location: reviewData.location,
        text: reviewData.text,
        avatar: reviewData.name.split(' ').map(n => n[0]).join('')
    });
    loadTestimonials();

    // Show thank you modal
    openModal('thankYouModal');
}

function loadReviewsList() {
    const container = document.getElementById('reviewsList');
    if (!container) return;

    container.innerHTML = '';

    if (reviews.length === 0) {
        container.innerHTML = '<p style="text-align: center; color: var(--medium-gray);">Aucun avis pour le moment</p>';
        return;
    }

    reviews.forEach(review => {
        const reviewElement = document.createElement('div');
        reviewElement.style.cssText = 'background: var(--light-gray); padding: 1rem; border-radius: 8px; margin-bottom: 1rem;';
        reviewElement.setAttribute('role', 'article');
        reviewElement.setAttribute('aria-label', `Avis de ${review.name}`);
        reviewElement.innerHTML = `
            <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 0.5rem;">
                <strong>${sanitizeHTML(review.name)}</strong>
                <div aria-label="Note: ${review.rating} sur 5">${'⭐'.repeat(review.rating)}</div>
            </div>
            <div style="color: var(--medium-gray); font-size: 0.9rem; margin-bottom: 0.5rem;">${sanitizeHTML(review.location)}</div>
            <p>${sanitizeHTML(review.text)}</p>
            <div style="color: var(--medium-gray); font-size: 0.8rem;">${new Date(review.createdAt).toLocaleDateString()}</div>
        `;
        container.appendChild(reviewElement);
    });
}

function saveReviews() {
    localStorage.setItem('reviews', JSON.stringify(reviews));
}

function loadReviews() {
    const savedReviews = localStorage.getItem('reviews');
    if (savedReviews) {
        reviews = JSON.parse(savedReviews);
    }
}

// ===== SETTINGS FUNCTIONS ===== 
function handleSettingsSubmit(e) {
    e.preventDefault();

    if (currentUser) {
        currentUser.firstName = sanitizeHTML(document.getElementById('firstName').value);
        currentUser.lastName = sanitizeHTML(document.getElementById('lastName').value);
        currentUser.email = document.getElementById('email').value.trim();
        
        localStorage.setItem('currentUser', JSON.stringify(currentUser));
        updateUserInterface();
        
        showToast('Paramètres sauvegardés avec succès !', 'success');
    }
}

function loadUserData() {
    if (currentUser) {
        const firstNameElement = document.getElementById('firstName');
        const lastNameElement = document.getElementById('lastName');
        const emailElement = document.getElementById('email');

        if (firstNameElement) firstNameElement.value = currentUser.firstName || '';
        if (lastNameElement) lastNameElement.value = currentUser.lastName || '';
        if (emailElement) emailElement.value = currentUser.email || '';
    }
}

// ===== SIMULATION DE DONNÉES POUR DÉMONSTRATION ===== 
function generateSampleData() {
    // Générer quelques produits d'exemple
    if (products.length === 0) {
        const sampleProducts = [
            {
                id: '1',
                name: 'Robe Africaine Traditionnelle',
                price: 25000,
                stock: 15,
                category: 'mode',
                description: '<p>Belle robe traditionnelle en wax authentique</p>',
                images: ['https://images.unsplash.com/photo-1584917865442-de89df76afd3?ixlib=rb-4.0.3&auto=format&fit=crop&w=600&q=80'],
                status: 'active',
                shopId: '1',
                shopName: 'Mode Africaine Elegance',
                reviews: [],
                createdAt: new Date().toISOString()
            },
            {
                id: '2',
                name: 'Smartphone Android',
                price: 150000,
                stock: 8,
                category: 'electronique',
                description: '<p>Smartphone dernière génération avec double SIM</p>',
                images: ['https://images.unsplash.com/photo-1560769629-975ec94e6a86?ixlib=rb-4.0.3&auto=format&fit=crop&w=600&q=80'],
                status: 'active',
                shopId: '2',
                shopName: 'TechAfrique Store',
                reviews: [],
                createdAt: new Date().toISOString()
            }
        ];
        
        products = sampleProducts;
        saveProducts();
    }

    // Générer quelques commandes d'exemple
    if (orders.length === 0) {
        const sampleOrders = [
            {
                id: '1001',
                customerName: 'Marie Kouadio',
                items: [{ productId: '1', quantity: 1, price: 25000 }],
                total: 25000,
                status: 'En cours',
                createdAt: new Date(Date.now() - 86400000).toISOString()
            },
            {
                id: '1002',
                customerName: 'Ibrahim Diallo',
                items: [{ productId: '2', quantity: 1, price: 150000 }],
                total: 150000,
                status: 'Livré',
                createdAt: new Date(Date.now() - 172800000).toISOString()
            }
        ];
        
        orders = sampleOrders;
        saveOrders();
    }
}

// Générer des données d'exemple au premier chargement
setTimeout(generateSampleData, 1000);