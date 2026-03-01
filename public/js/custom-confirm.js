/**
 * Custom Confirmation Dialog Component
 * For Nepal Government Appointment System
 */

class NepalConfirm {
    constructor() {
        this.overlay = null;
        this.dialog = null;
        this.resolvePromise = null;
        this.rejectPromise = null;
        this.animationDuration = 300; // ms
        this.initialized = false;
        
        // Initialize the component
        this.init();
    }
    
    init() {
        // Create overlay
        this.overlay = document.createElement('div');
        this.overlay.className = 'nepal-confirm-overlay';
        
        // Create dialog
        this.dialog = document.createElement('div');
        this.dialog.className = 'nepal-confirm-dialog';
        this.dialog.setAttribute('role', 'dialog');
        this.dialog.setAttribute('aria-modal', 'true');
        
        // Add dialog to overlay
        this.overlay.appendChild(this.dialog);
        
        // Add to body
        document.body.appendChild(this.overlay);
        
        // Add event listener for ESC key
        document.addEventListener('keydown', (e) => {
            if (e.key === 'Escape' && this.isVisible()) {
                this.cancel();
            }
        });
        
        // Add click handler to overlay for dismissing
        this.overlay.addEventListener('click', (e) => {
            if (e.target === this.overlay) {
                this.cancel();
            }
        });
        
        // Add styles
        this.addStyles();
        
        this.initialized = true;
    }
    
    addStyles() {
        // Check if styles already exist
        if (document.getElementById('nepal-confirm-styles')) {
            return;
        }
        
        const style = document.createElement('style');
        style.id = 'nepal-confirm-styles';
        style.textContent = `
            .nepal-confirm-overlay {
                position: fixed;
                top: 0;
                left: 0;
                right: 0;
                bottom: 0;
                background-color: rgba(0, 0, 0, 0.5);
                backdrop-filter: blur(4px);
                display: flex;
                align-items: center;
                justify-content: center;
                z-index: 9999;
                opacity: 0;
                visibility: hidden;
                transition: opacity ${this.animationDuration}ms ease, visibility ${this.animationDuration}ms ease;
            }
            
            .nepal-confirm-overlay.visible {
                opacity: 1;
                visibility: visible;
            }
            
            .nepal-confirm-dialog {
                background-color: white;
                border-radius: 0.75rem;
                box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04);
                width: 90%;
                max-width: 450px;
                max-height: 90vh;
                overflow-y: auto;
                transform: translateY(20px) scale(0.95);
                opacity: 0;
                transition: transform ${this.animationDuration}ms ease, opacity ${this.animationDuration}ms ease;
                border: 1px solid rgba(0, 0, 0, 0.1);
            }
            
            .nepal-confirm-overlay.visible .nepal-confirm-dialog {
                transform: translateY(0) scale(1);
                opacity: 1;
            }
            
            .nepal-confirm-header {
                padding: 1.25rem 1.5rem;
                border-bottom: 1px solid rgba(0, 0, 0, 0.1);
                display: flex;
                align-items: center;
                justify-content: space-between;
            }
            
            .nepal-confirm-title {
                font-size: 1.25rem;
                font-weight: 600;
                color: #1F2937;
                margin: 0;
                padding: 0;
            }
            
            .nepal-confirm-close {
                background: transparent;
                border: none;
                cursor: pointer;
                padding: 0.5rem;
                margin: -0.5rem;
                border-radius: 0.375rem;
                color: #6B7280;
                transition: background-color 0.2s ease, color 0.2s ease;
            }
            
            .nepal-confirm-close:hover {
                background-color: #F3F4F6;
                color: #1F2937;
            }
            
            .nepal-confirm-body {
                padding: 1.5rem;
                color: #4B5563;
                font-size: 1rem;
                line-height: 1.5;
            }
            
            .nepal-confirm-icon {
                display: flex;
                align-items: center;
                justify-content: center;
                width: 3rem;
                height: 3rem;
                border-radius: 9999px;
                margin: 0 auto 1rem auto;
            }
            
            .nepal-confirm-icon.danger {
                background-color: #FEE2E2;
                color: #DC2626;
            }
            
            .nepal-confirm-icon.warning {
                background-color: #FEF3C7;
                color: #D97706;
            }
            
            .nepal-confirm-icon.info {
                background-color: #DBEAFE;
                color: #2563EB;
            }
            
            .nepal-confirm-icon.success {
                background-color: #D1FAE5;
                color: #059669;
            }
            
            .nepal-confirm-footer {
                padding: 1rem 1.5rem;
                display: flex;
                justify-content: flex-end;
                gap: 0.75rem;
                border-top: 1px solid rgba(0, 0, 0, 0.1);
                background-color: #F9FAFB;
                border-bottom-left-radius: 0.75rem;
                border-bottom-right-radius: 0.75rem;
            }
            
            .nepal-confirm-btn {
                padding: 0.5rem 1rem;
                border-radius: 0.375rem;
                font-weight: 500;
                font-size: 0.875rem;
                cursor: pointer;
                transition: all 0.2s ease;
                display: flex;
                align-items: center;
                justify-content: center;
                min-width: 5rem;
            }
            
            .nepal-confirm-btn-cancel {
                background-color: white;
                color: #4B5563;
                border: 1px solid #D1D5DB;
            }
            
            .nepal-confirm-btn-cancel:hover {
                background-color: #F3F4F6;
                border-color: #9CA3AF;
            }
            
            .nepal-confirm-btn-confirm {
                background-color: #003893;
                color: white;
                border: 1px solid #003893;
            }
            
            .nepal-confirm-btn-confirm:hover {
                background-color: #002d74;
            }
            
            .nepal-confirm-btn-danger {
                background-color: #DC2626;
                color: white;
                border: 1px solid #DC2626;
            }
            
            .nepal-confirm-btn-danger:hover {
                background-color: #B91C1C;
            }
            
            .nepal-confirm-btn-warning {
                background-color: #D97706;
                color: white;
                border: 1px solid #D97706;
            }
            
            .nepal-confirm-btn-warning:hover {
                background-color: #B45309;
            }
            
            /* Dark mode support */
            @media (prefers-color-scheme: dark) {
                .nepal-confirm-dialog {
                    background-color: #1F2937;
                    border-color: rgba(255, 255, 255, 0.1);
                }
                
                .nepal-confirm-header {
                    border-bottom-color: rgba(255, 255, 255, 0.1);
                }
                
                .nepal-confirm-title {
                    color: #F9FAFB;
                }
                
                .nepal-confirm-close {
                    color: #9CA3AF;
                }
                
                .nepal-confirm-close:hover {
                    background-color: #374151;
                    color: #F9FAFB;
                }
                
                .nepal-confirm-body {
                    color: #D1D5DB;
                }
                
                .nepal-confirm-footer {
                    border-top-color: rgba(255, 255, 255, 0.1);
                    background-color: #111827;
                }
                
                .nepal-confirm-btn-cancel {
                    background-color: #374151;
                    color: #F9FAFB;
                    border-color: #4B5563;
                }
                
                .nepal-confirm-btn-cancel:hover {
                    background-color: #4B5563;
                    border-color: #6B7280;
                }
            }
            
            /* Animation keyframes */
            @keyframes nepal-confirm-shake {
                0%, 100% { transform: translateX(0); }
                10%, 30%, 50%, 70%, 90% { transform: translateX(-5px); }
                20%, 40%, 60%, 80% { transform: translateX(5px); }
            }
            
            .nepal-confirm-shake {
                animation: nepal-confirm-shake 0.6s cubic-bezier(.36,.07,.19,.97) both;
            }
        `;
        
        document.head.appendChild(style);
    }
    
    /**
     * Show the confirmation dialog
     * @param {Object} options - Configuration options
     * @param {string} options.title - Dialog title
     * @param {string} options.message - Dialog message
     * @param {string} options.type - Dialog type (danger, warning, info, success)
     * @param {string} options.confirmText - Text for confirm button
     * @param {string} options.cancelText - Text for cancel button
     * @param {string} options.confirmIcon - Icon for confirm button
     * @param {string} options.icon - Icon for the dialog
     * @returns {Promise} - Resolves with true if confirmed, rejects if canceled
     */
    show(options = {}) {
        const defaults = {
            title: 'Confirm Action',
            message: 'Are you sure you want to proceed?',
            type: 'info',
            confirmText: 'Confirm',
            cancelText: 'Cancel',
            confirmIcon: '',
            icon: ''
        };
        
        const settings = { ...defaults, ...options };
        
        // Set dialog content
        this.dialog.innerHTML = `
            <div class="nepal-confirm-header">
                <h3 class="nepal-confirm-title">${settings.title}</h3>
                <button type="button" class="nepal-confirm-close" aria-label="Close">
                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <line x1="18" y1="6" x2="6" y2="18"></line>
                        <line x1="6" y1="6" x2="18" y2="18"></line>
                    </svg>
                </button>
            </div>
            <div class="nepal-confirm-body">
                ${this.getIconHtml(settings.type, settings.icon)}
                <p>${settings.message}</p>
            </div>
            <div class="nepal-confirm-footer">
                <button type="button" class="nepal-confirm-btn nepal-confirm-btn-cancel" data-action="cancel">
                    ${settings.cancelText}
                </button>
                <button type="button" class="nepal-confirm-btn nepal-confirm-btn-${settings.type === 'danger' ? 'danger' : settings.type === 'warning' ? 'warning' : 'confirm'}" data-action="confirm">
                    ${settings.confirmIcon ? `<span class="mr-2">${settings.confirmIcon}</span>` : ''}
                    ${settings.confirmText}
                </button>
            </div>
        `;
        
        // Add event listeners
        const closeBtn = this.dialog.querySelector('.nepal-confirm-close');
        const cancelBtn = this.dialog.querySelector('[data-action="cancel"]');
        const confirmBtn = this.dialog.querySelector('[data-action="confirm"]');
        
        closeBtn.addEventListener('click', () => this.cancel());
        cancelBtn.addEventListener('click', () => this.cancel());
        confirmBtn.addEventListener('click', () => this.confirm());
        
        // Show the dialog
        this.overlay.classList.add('visible');
        
        // Focus the confirm button
        setTimeout(() => {
            confirmBtn.focus();
        }, this.animationDuration);
        
        // Return a promise
        return new Promise((resolve, reject) => {
            this.resolvePromise = resolve;
            this.rejectPromise = reject;
        });
    }
    
    getIconHtml(type, customIcon) {
        if (customIcon) {
            return `<div class="nepal-confirm-icon ${type}">${customIcon}</div>`;
        }
        
        let iconSvg = '';
        
        switch (type) {
            case 'danger':
                iconSvg = `<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M10.29 3.86L1.82 18a2 2 0 0 0 1.71 3h16.94a2 2 0 0 0 1.71-3L13.71 3.86a2 2 0 0 0-3.42 0z"></path>
                    <line x1="12" y1="9" x2="12" y2="13"></line>
                    <line x1="12" y1="17" x2="12.01" y2="17"></line>
                </svg>`;
                break;
            case 'warning':
                iconSvg = `<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <circle cx="12" cy="12" r="10"></circle>
                    <line x1="12" y1="8" x2="12" y2="12"></line>
                    <line x1="12" y1="16" x2="12.01" y2="16"></line>
                </svg>`;
                break;
            case 'success':
                iconSvg = `<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"></path>
                    <polyline points="22 4 12 14.01 9 11.01"></polyline>
                </svg>`;
                break;
            case 'info':
            default:
                iconSvg = `<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <circle cx="12" cy="12" r="10"></circle>
                    <line x1="12" y1="16" x2="12" y2="12"></line>
                    <line x1="12" y1="8" x2="12.01" y2="8"></line>
                </svg>`;
        }
        
        return `<div class="nepal-confirm-icon ${type}">${iconSvg}</div>`;
    }
    
    confirm() {
        if (this.resolvePromise) {
            this.resolvePromise(true);
        }
        this.hide();
    }
    
    cancel() {
        if (this.rejectPromise) {
            this.rejectPromise(false);
        }
        this.hide();
    }
    
    hide() {
        this.overlay.classList.remove('visible');
        
        // Reset promises
        setTimeout(() => {
            this.resolvePromise = null;
            this.rejectPromise = null;
        }, this.animationDuration);
    }
    
    isVisible() {
        return this.overlay.classList.contains('visible');
    }
    
    shake() {
        this.dialog.classList.add('nepal-confirm-shake');
        
        setTimeout(() => {
            this.dialog.classList.remove('nepal-confirm-shake');
        }, 600);
    }
}

// Initialize the confirmation dialog
const nepalConfirm = new NepalConfirm();

// Make it globally available
window.nepalConfirm = nepalConfirm;