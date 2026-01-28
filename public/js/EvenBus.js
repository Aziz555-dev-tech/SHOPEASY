/**
 * Event Bus Module
 * Custom event system for decoupled component communication
 */

class EventBus {
    constructor() {
        this.events = {};
    }

    /**
     * Subscribe to an event
     * @param {string} event - Event name
     * @param {Function} callback - Callback function
     * @returns {Function} Unsubscribe function
     */
    on(event, callback) {
        if (!this.events[event]) {
            this.events[event] = [];
        }
        this.events[event].push(callback);
        
        // Return unsubscribe function
        return () => this.off(event, callback);
    }

    /**
     * Subscribe to an event (one-time)
     * @param {string} event - Event name
     * @param {Function} callback - Callback function
     */
    once(event, callback) {
        const wrapper = (...args) => {
            callback(...args);
            this.off(event, wrapper);
        };
        this.on(event, wrapper);
    }

    /**
     * Unsubscribe from an event
     * @param {string} event - Event name
     * @param {Function} callback - Callback function
     */
    off(event, callback) {
        if (!this.events[event]) return;
        
        this.events[event] = this.events[event].filter(cb => cb !== callback);
        
        if (this.events[event].length === 0) {
            delete this.events[event];
        }
    }

    /**
     * Emit an event
     * @param {string} event - Event name
     * @param {any} data - Event data
     */
    emit(event, data) {
        if (!this.events[event]) return;
        
        this.events[event].forEach(callback => {
            try {
                callback(data);
            } catch (error) {
                console.error(`Error in event handler for "${event}":`, error);
            }
        });
    }

    /**
     * Remove all event listeners
     */
    clear() {
        this.events = {};
    }

    /**
     * Get all registered events
     * @returns {string[]} Array of event names
     */
    getEvents() {
        return Object.keys(this.events);
    }

    /**
     * Get listener count for an event
     * @param {string} event - Event name
     * @returns {number} Number of listeners
     */
    listenerCount(event) {
        return this.events[event] ? this.events[event].length : 0;
    }
}

// Create singleton instance
const eventBus = new EventBus();

// Define application events
export const EVENTS = {
    // Auth events
    USER_LOGGED_IN: 'user:logged_in',
    USER_LOGGED_OUT: 'user:logged_out',
    USER_REGISTERED: 'user:registered',
    
    // Product events
    PRODUCT_ADDED: 'product:added',
    PRODUCT_UPDATED: 'product:updated',
    PRODUCT_DELETED: 'product:deleted',
    PRODUCT_VIEWED: 'product:viewed',
    
    // Cart events
    CART_ITEM_ADDED: 'cart:item_added',
    CART_ITEM_REMOVED: 'cart:item_removed',
    CART_ITEM_UPDATED: 'cart:item_updated',
    CART_CLEARED: 'cart:cleared',
    
    // Order events
    ORDER_PLACED: 'order:placed',
    ORDER_UPDATED: 'order:updated',
    ORDER_CANCELLED: 'order:cancelled',
    
    // Shop events
    SHOP_CREATED: 'shop:created',
    SHOP_UPDATED: 'shop:updated',
    SHOP_PUBLISHED: 'shop:published',
    
    // Review events
    REVIEW_ADDED: 'review:added',
    REVIEW_UPDATED: 'review:updated',
    REVIEW_DELETED: 'review:deleted',
    
    // Notification events
    NOTIFICATION_RECEIVED: 'notification:received',
    NOTIFICATION_READ: 'notification:read',
    NOTIFICATION_CLEARED: 'notification:cleared',
    
    // UI events
    MODAL_OPENED: 'modal:opened',
    MODAL_CLOSED: 'modal:closed',
    TOAST_SHOWN: 'toast:shown',
    THEME_CHANGED: 'theme:changed',
    LANGUAGE_CHANGED: 'language:changed',
    
    // Search events
    SEARCH_PERFORMED: 'search:performed',
    FILTER_APPLIED: 'filter:applied',
    SORT_CHANGED: 'sort:changed',
};

export default eventBus;