/**
 * Application Constants
 * Emplacement central pour toutes les valeurs magiques et la configuration
 */

export const APP_CONFIG = {
    APP_NAME: 'ShopEasy',
    VERSION: '2.0.0',
    DEFAULT_LANGUAGE: 'fr',
    SUPPORTED_LANGUAGES: ['fr', 'en'],
};

export const STORAGE_KEYS = {
    CURRENT_USER: 'currentUser',
    PENDING_USER: 'pendingUser',
    ALL_USERS: 'allUsers',
    PRODUCTS: 'products',
    ORDERS: 'orders',
    REVIEWS: 'reviews',
    SHOP_DATA: 'shopData',
    ALL_SHOPS: 'allShops',
    CART: 'cart',
    THEME: 'theme',
    LANGUAGE: 'language',
    NOTIFICATIONS: 'notifications',
    CHAT_MESSAGES: 'chatMessages',
    COUPONS: 'coupons',
};

export const USER_TYPES = {
    VENDOR: 'vendor',
    CUSTOMER: 'customer',
};

export const PRODUCT_CATEGORIES = {
    MODE: 'mode',
    ELECTRONIQUE: 'electronique',
    MAISON: 'maison',
    BEAUTE: 'beaute',
    SPORT: 'sport',
    ALIMENTAIRE: 'alimentaire',
};

export const ORDER_STATUS = {
    PENDING: 'En cours',
    PROCESSING: 'En traitement',
    SHIPPED: 'Expédié',
    DELIVERED: 'Livré',
    CANCELLED: 'Annulé',
};

export const PAYMENT_METHODS = {
    MOOV: 'moov',
    MTN: 'mtn',
    ORANGE: 'orange',
};

export const VALIDATION_RULES = {
    PASSWORD_MIN_LENGTH: 8,
    MAX_PRODUCT_IMAGES: 8,
    MAX_PRODUCTS_FREE_PLAN: 10,
    PHONE_REGEX: /^\+?[0-9]{1,4}[\s-]?[0-9]{2,4}[\s-]?[0-9]{2,4}[\s-]?[0-9]{2,4}[\s-]?[0-9]{0,4}$/,
    EMAIL_REGEX: /^[a-zA-Z0-9._-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/,
};

export const ANIMATION_DURATION = {
    FAST: 200,
    NORMAL: 300,
    SLOW: 500,
};

export const TOAST_TYPES = {
    SUCCESS: 'success',
    ERROR: 'error',
    WARNING: 'warning',
    INFO: 'info',
};

export const PLANS = {
    FREE: {
        name: 'Gratuit',
        price: 0,
        maxProducts: 10,
        features: ['Boutique basique', '10 produits maximum', 'Support par email', 'Sous-domaine ShopEasy'],
    },
    STANDARD: {
        name: 'Standard',
        price: 9000,
        maxProducts: -1, // unlimited
        features: ['Produits illimités', 'Paiements en ligne', 'Domaine personnalisé', 'Analytics avancées', 'Support prioritaire'],
    },
    PREMIUM: {
        name: 'Premium',
        price: 19000,
        maxProducts: -1,
        features: ['Tout du Standard', 'Multi-boutiques', 'API complète', 'Support téléphonique', 'Formation personnalisée'],
    },
};

export const CHART_COLORS = {
    PRIMARY: '#FFD700',
    SUCCESS: '#28A745',
    DANGER: '#DC3545',
    WARNING: '#FFC107',
    INFO: '#17a2b8',
};

export const API_ENDPOINTS = {
    // Ready for backend integration
    AUTH: '/api/auth',
    PRODUCTS: '/api/products',
    ORDERS: '/api/orders',
    SHOPS: '/api/shops',
    REVIEWS: '/api/reviews',
    CHAT: '/api/chat',
    NOTIFICATIONS: '/api/notifications',
};