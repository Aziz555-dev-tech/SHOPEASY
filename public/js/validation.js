// ===== VALIDATION ET SÉCURITÉ =====

/**
 * Validation et sanitization des entrées utilisateur
 */

// ===== VALIDATION EMAIL =====
function validateEmail(email) {
    const emailRegex = /^[a-zA-Z0-9._-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/;
    return emailRegex.test(email);
}

function getEmailError(email) {
    if (!email || email.trim() === '') {
        return 'L\'adresse email est requise.';
    }
    if (!validateEmail(email)) {
        return 'Veuillez entrer une adresse email valide (exemple: utilisateur@domaine.com).';
    }
    return '';
}

// ===== VALIDATION TÉLÉPHONE =====
function validatePhone(phone) {
    // Format accepté: +XXX XX XX XX XX ou variantes
    const phoneRegex = /^\+?[0-9]{1,4}[\s-]?[0-9]{2,4}[\s-]?[0-9]{2,4}[\s-]?[0-9]{2,4}[\s-]?[0-9]{0,4}$/;
    return phoneRegex.test(phone.trim());
}

function getPhoneError(phone) {
    if (!phone || phone.trim() === '') {
        return 'Le numéro de téléphone est requis.';
    }
    if (!validatePhone(phone)) {
        return 'Veuillez entrer un numéro de téléphone valide (exemple: +229 XX XX XX XX).';
    }
    return '';
}

// ===== VALIDATION MOT DE PASSE =====
function validatePasswordStrength(password) {
    const minLength = 8;
    const hasUpperCase = /[A-Z]/.test(password);
    const hasLowerCase = /[a-z]/.test(password);
    const hasNumbers = /\d/.test(password);
    const hasSpecialChar = /[!@#$%^&*()_+\-=\[\]{};':"\\|,.<>\/?]/.test(password);
    
    return {
        isValid: password.length >= minLength && hasUpperCase && hasLowerCase && hasNumbers,
        length: password.length >= minLength,
        hasUpperCase,
        hasLowerCase,
        hasNumbers,
        hasSpecialChar,
        strength: calculatePasswordStrength(password, hasUpperCase, hasLowerCase, hasNumbers, hasSpecialChar)
    };
}

function calculatePasswordStrength(password, hasUpperCase, hasLowerCase, hasNumbers, hasSpecialChar) {
    let strength = 0;
    
    if (password.length >= 8) strength += 1;
    if (password.length >= 12) strength += 1;
    if (hasUpperCase) strength += 1;
    if (hasLowerCase) strength += 1;
    if (hasNumbers) strength += 1;
    if (hasSpecialChar) strength += 1;
    
    if (strength <= 2) return 'faible';
    if (strength <= 4) return 'moyen';
    return 'fort';
}

function getPasswordError(password) {
    if (!password || password.trim() === '') {
        return 'Le mot de passe est requis.';
    }
    
    const validation = validatePasswordStrength(password);
    
    if (!validation.length) {
        return 'Le mot de passe doit contenir au moins 8 caractères.';
    }
    if (!validation.hasUpperCase) {
        return 'Le mot de passe doit contenir au moins une lettre majuscule.';
    }
    if (!validation.hasLowerCase) {
        return 'Le mot de passe doit contenir au moins une lettre minuscule.';
    }
    if (!validation.hasNumbers) {
        return 'Le mot de passe doit contenir au moins un chiffre.';
    }
    
    return '';
}

function getPasswordStrengthMessage(password) {
    if (!password) return '';
    
    const validation = validatePasswordStrength(password);
    const messages = {
        'faible': { text: 'Mot de passe faible', color: '#DC3545' },
        'moyen': { text: 'Mot de passe moyen', color: '#FFC107' },
        'fort': { text: 'Mot de passe fort', color: '#28A745' }
    };
    
    return messages[validation.strength];
}

// ===== SANITIZATION HTML =====
function sanitizeHTML(html) {
    const temp = document.createElement('div');
    temp.textContent = html;
    return temp.innerHTML;
}

function sanitizeInput(input) {
    if (typeof input !== 'string') return input;
    
    // Supprimer les caractères dangereux
    return input
        .replace(/[<>]/g, '') // Supprimer < et >
        .replace(/javascript:/gi, '') // Supprimer javascript:
        .replace(/on\w+=/gi, '') // Supprimer les événements onclick, onload, etc.
        .trim();
}

function sanitizeDescription(description) {
    // Pour les descriptions riches (Quill editor), on garde certaines balises HTML sûres
    const temp = document.createElement('div');
    temp.innerHTML = description;
    
    // Supprimer les scripts et événements
    const scripts = temp.querySelectorAll('script');
    scripts.forEach(script => script.remove());
    
    // Supprimer les attributs d'événements
    const allElements = temp.querySelectorAll('*');
    allElements.forEach(element => {
        const attributes = Array.from(element.attributes);
        attributes.forEach(attr => {
            if (attr.name.startsWith('on') || attr.value.includes('javascript:')) {
                element.removeAttribute(attr.name);
            }
        });
    });
    
    return temp.innerHTML;
}

// ===== HACHAGE MOT DE PASSE (Simulation simple) =====
// Note: En production, le hachage doit être fait côté serveur
async function hashPassword(password) {
    // Simulation d'un hachage simple pour la démo
    // En production, utiliser bcrypt côté serveur
    const encoder = new TextEncoder();
    const data = encoder.encode(password + 'SALT_SECRET_KEY');
    const hashBuffer = await crypto.subtle.digest('SHA-256', data);
    const hashArray = Array.from(new Uint8Array(hashBuffer));
    const hashHex = hashArray.map(b => b.toString(16).padStart(2, '0')).join('');
    return hashHex;
}

// ===== VALIDATION FORMULAIRES =====
function validateFormField(input, validationType) {
    const value = input.value;
    let errorMessage = '';
    
    switch (validationType) {
        case 'email':
            errorMessage = getEmailError(value);
            break;
        case 'phone':
            errorMessage = getPhoneError(value);
            break;
        case 'password':
            errorMessage = getPasswordError(value);
            break;
        case 'required':
            if (!value || value.trim() === '') {
                errorMessage = 'Ce champ est requis.';
            }
            break;
        case 'text':
            if (value && value.length < 2) {
                errorMessage = 'Ce champ doit contenir au moins 2 caractères.';
            }
            break;
    }
    
    return errorMessage;
}

function showFieldError(input, errorMessage) {
    // Supprimer l'ancien message d'erreur s'il existe
    const existingError = input.parentElement.querySelector('.field-error');
    if (existingError) {
        existingError.remove();
    }
    
    if (errorMessage) {
        // Ajouter la classe d'erreur à l'input
        input.classList.add('input-error');
        
        // Créer et afficher le message d'erreur
        const errorDiv = document.createElement('div');
        errorDiv.className = 'field-error';
        errorDiv.textContent = errorMessage;
        errorDiv.style.color = '#DC3545';
        errorDiv.style.fontSize = '0.875rem';
        errorDiv.style.marginTop = '0.25rem';
        input.parentElement.appendChild(errorDiv);
    } else {
        // Supprimer la classe d'erreur
        input.classList.remove('input-error');
    }
}

function clearFieldError(input) {
    input.classList.remove('input-error');
    const existingError = input.parentElement.querySelector('.field-error');
    if (existingError) {
        existingError.remove();
    }
}

// ===== INDICATEUR DE FORCE DU MOT DE PASSE =====
function createPasswordStrengthIndicator(passwordInput) {
    const container = passwordInput.parentElement;
    
    // Créer l'indicateur s'il n'existe pas
    let indicator = container.querySelector('.password-strength-indicator');
    if (!indicator) {
        indicator = document.createElement('div');
        indicator.className = 'password-strength-indicator';
        indicator.style.marginTop = '0.5rem';
        
        const bar = document.createElement('div');
        bar.className = 'strength-bar';
        bar.style.height = '4px';
        bar.style.backgroundColor = '#e0e0e0';
        bar.style.borderRadius = '2px';
        bar.style.overflow = 'hidden';
        
        const fill = document.createElement('div');
        fill.className = 'strength-fill';
        fill.style.height = '100%';
        fill.style.width = '0%';
        fill.style.transition = 'all 0.3s ease';
        bar.appendChild(fill);
        
        const text = document.createElement('div');
        text.className = 'strength-text';
        text.style.fontSize = '0.875rem';
        text.style.marginTop = '0.25rem';
        
        indicator.appendChild(bar);
        indicator.appendChild(text);
        container.appendChild(indicator);
    }
    
    return indicator;
}

function updatePasswordStrengthIndicator(passwordInput) {
    const password = passwordInput.value;
    const indicator = passwordInput.parentElement.querySelector('.password-strength-indicator');
    
    if (!indicator || !password) {
        if (indicator) {
            indicator.style.display = 'none';
        }
        return;
    }
    
    indicator.style.display = 'block';
    const validation = validatePasswordStrength(password);
    const strengthInfo = getPasswordStrengthMessage(password);
    
    const fill = indicator.querySelector('.strength-fill');
    const text = indicator.querySelector('.strength-text');
    
    const widths = {
        'faible': '33%',
        'moyen': '66%',
        'fort': '100%'
    };
    
    fill.style.width = widths[validation.strength];
    fill.style.backgroundColor = strengthInfo.color;
    text.textContent = strengthInfo.text;
    text.style.color = strengthInfo.color;
}

// ===== VALIDATION EN TEMPS RÉEL =====
function setupRealtimeValidation(formId, fieldConfigs) {
    const form = document.getElementById(formId);
    if (!form) return;
    
    fieldConfigs.forEach(config => {
        const input = document.getElementById(config.id);
        if (!input) return;
        
        // Validation à la perte de focus
        input.addEventListener('blur', () => {
            const errorMessage = validateFormField(input, config.type);
            showFieldError(input, errorMessage);
        });
        
        // Effacer l'erreur lors de la saisie
        input.addEventListener('input', () => {
            if (input.classList.contains('input-error')) {
                const errorMessage = validateFormField(input, config.type);
                if (!errorMessage) {
                    clearFieldError(input);
                }
            }
            
            // Mise à jour de l'indicateur de force pour les mots de passe
            if (config.type === 'password') {
                updatePasswordStrengthIndicator(input);
            }
        });
        
        // Créer l'indicateur de force pour les champs mot de passe
        if (config.type === 'password') {
            createPasswordStrengthIndicator(input);
        }
    });
}

// ===== VALIDATION COMPLÈTE DU FORMULAIRE =====
function validateForm(formId, fieldConfigs) {
    const form = document.getElementById(formId);
    if (!form) return false;
    
    let isValid = true;
    
    fieldConfigs.forEach(config => {
        const input = document.getElementById(config.id);
        if (!input) return;
        
        const errorMessage = validateFormField(input, config.type);
        if (errorMessage) {
            showFieldError(input, errorMessage);
            isValid = false;
        }
    });
    
    return isValid;
}

// Export des fonctions pour utilisation dans script.js
if (typeof module !== 'undefined' && module.exports) {
    module.exports = {
        validateEmail,
        validatePhone,
        validatePasswordStrength,
        sanitizeHTML,
        sanitizeInput,
        sanitizeDescription,
        hashPassword,
        setupRealtimeValidation,
        validateForm,
        getEmailError,
        getPhoneError,
        getPasswordError
    };
}