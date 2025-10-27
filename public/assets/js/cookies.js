/**
 * Cookie Consent Management
 */

(function() {
    'use strict';

    const COOKIE_CONSENT_KEY = 'pinkkiwi_cookie_consent';
    const CONSENT_EXPIRY_DAYS = 365;

    // Get consent status from localStorage
    function getConsent() {
        const consent = localStorage.getItem(COOKIE_CONSENT_KEY);
        return consent ? JSON.parse(consent) : null;
    }

    // Save consent to localStorage
    function saveConsent(preferences) {
        const consentData = {
            timestamp: new Date().toISOString(),
            preferences: preferences
        };
        localStorage.setItem(COOKIE_CONSENT_KEY, JSON.stringify(consentData));
    }

    // Show cookie banner
    function showBanner() {
        const banner = document.getElementById('cookieBanner');
        if (banner) {
            banner.style.display = 'block';
            setTimeout(() => banner.classList.add('show'), 100);
        }
    }

    // Hide cookie banner
    function hideBanner() {
        const banner = document.getElementById('cookieBanner');
        if (banner) {
            banner.classList.remove('show');
            setTimeout(() => banner.style.display = 'none', 300);
        }
    }

    // Show cookie settings modal
    function showModal() {
        const modal = document.getElementById('cookieModal');
        if (modal) {
            modal.style.display = 'block';
            setTimeout(() => modal.classList.add('show'), 100);
            document.body.style.overflow = 'hidden';
        }
    }

    // Hide cookie settings modal
    function hideModal() {
        const modal = document.getElementById('cookieModal');
        if (modal) {
            modal.classList.remove('show');
            setTimeout(() => {
                modal.style.display = 'none';
                document.body.style.overflow = '';
            }, 300);
        }
    }

    // Get preferences from modal
    function getModalPreferences() {
        return {
            essential: true, // Always true
            analytics: document.getElementById('analyticsToggle')?.checked || false,
            marketing: document.getElementById('marketingToggle')?.checked || false,
            functional: document.getElementById('functionalToggle')?.checked || false
        };
    }

    // Accept all cookies
    function acceptAll() {
        const preferences = {
            essential: true,
            analytics: true,
            marketing: true,
            functional: true
        };
        saveConsent(preferences);
        hideBanner();
        hideModal();
        applyCookiePreferences(preferences);
    }

    // Reject non-essential cookies
    function rejectNonEssential() {
        const preferences = {
            essential: true,
            analytics: false,
            marketing: false,
            functional: false
        };
        saveConsent(preferences);
        hideBanner();
        hideModal();
        applyCookiePreferences(preferences);
    }

    // Save custom preferences
    function saveCustomPreferences() {
        const preferences = getModalPreferences();
        saveConsent(preferences);
        hideBanner();
        hideModal();
        applyCookiePreferences(preferences);
    }

    // Apply cookie preferences (placeholder for actual implementation)
    function applyCookiePreferences(preferences) {
        console.log('Cookie preferences applied:', preferences);
        
        // Here you would implement actual cookie logic:
        // if (preferences.analytics) {
        //     // Initialize Google Analytics
        // }
        // if (preferences.marketing) {
        //     // Initialize marketing pixels
        // }
        // etc.
    }

    // Initialize on page load
    document.addEventListener('DOMContentLoaded', function() {
        const consent = getConsent();

        // Show banner if no consent recorded
        if (!consent) {
            showBanner();
        } else {
            // Apply saved preferences
            applyCookiePreferences(consent.preferences);
        }

        // Event listeners
        const acceptBtn = document.getElementById('cookieAccept');
        const rejectBtn = document.getElementById('cookieReject');
        const settingsBtn = document.getElementById('cookieSettings');
        const saveBtn = document.getElementById('savePreferences');
        const closeBtn = document.getElementById('cookieModalClose');
        const overlay = document.querySelector('.cookie-modal-overlay');

        if (acceptBtn) {
            acceptBtn.addEventListener('click', acceptAll);
        }

        if (rejectBtn) {
            rejectBtn.addEventListener('click', rejectNonEssential);
        }

        if (settingsBtn) {
            settingsBtn.addEventListener('click', function() {
                showModal();
                
                // Load current preferences if they exist
                if (consent) {
                    const prefs = consent.preferences;
                    document.getElementById('analyticsToggle').checked = prefs.analytics;
                    document.getElementById('marketingToggle').checked = prefs.marketing;
                    document.getElementById('functionalToggle').checked = prefs.functional;
                }
            });
        }

        if (saveBtn) {
            saveBtn.addEventListener('click', saveCustomPreferences);
        }

        if (closeBtn) {
            closeBtn.addEventListener('click', hideModal);
        }

        if (overlay) {
            overlay.addEventListener('click', hideModal);
        }

        // ESC key to close modal
        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape') {
                hideModal();
            }
        });
    });

    // Expose function to reopen cookie settings
    window.reopenCookieSettings = function() {
        showModal();
        const consent = getConsent();
        if (consent) {
            const prefs = consent.preferences;
            document.getElementById('analyticsToggle').checked = prefs.analytics;
            document.getElementById('marketingToggle').checked = prefs.marketing;
            document.getElementById('functionalToggle').checked = prefs.functional;
        }
    };
})();