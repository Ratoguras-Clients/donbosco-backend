/**
 * Nepal Modal - A custom modal component for the Ministry Appointment Management System
 *
 * This component provides a flexible modal dialog that can be customized with
 * different content, sizes, and callbacks.
 */

// Initialize the nepalModal object
window.nepalModal = (function() {
    // Modal container ID
    const MODAL_CONTAINER_ID = 'nepal-modal-container';

    // Modal backdrop ID
    const MODAL_BACKDROP_ID = 'nepal-modal-backdrop';

    // Current modal instance
    let currentModal = null;

    /**
     * Create the modal container if it doesn't exist
     */
    function createModalContainer() {
        if (!document.getElementById(MODAL_CONTAINER_ID)) {
            const container = document.createElement('div');
            container.id = MODAL_CONTAINER_ID;
            container.style.display = 'none';
            container.style.position = 'fixed';
            container.style.top = '0';
            container.style.left = '0';
            container.style.width = '100%';
            container.style.height = '100%';
            container.style.zIndex = '1050';
            container.setAttribute('aria-hidden', 'true');

            document.body.appendChild(container);
        }

        if (!document.getElementById(MODAL_BACKDROP_ID)) {
            const backdrop = document.createElement('div');
            backdrop.id = MODAL_BACKDROP_ID;
            backdrop.style.position = 'fixed';
            backdrop.style.top = '0';
            backdrop.style.left = '0';
            backdrop.style.width = '100%';
            backdrop.style.height = '100%';
            backdrop.style.backgroundColor = 'rgba(0, 0, 0, 0.5)';
            backdrop.style.zIndex = '1040';
            backdrop.style.display = 'none';

            document.body.appendChild(backdrop);
        }
    }

    /**
     * Show the modal with the specified options
     *
     * @param {Object} options - Modal options
     * @param {string} options.title - Modal title
     * @param {string} options.body - Modal body content (HTML)
     * @param {string} options.footer - Modal footer content (HTML)
     * @param {string} options.size - Modal size (sm, md, lg, xl)
     * @param {Function} options.onShown - Callback when modal is shown
     * @param {Function} options.onHidden - Callback when modal is hidden
     * @param {boolean} options.backdrop - Whether to show backdrop
     * @param {boolean} options.closeButton - Whether to show close button
     */
    function show(options) {
        // Create container if it doesn't exist
        createModalContainer();

        // Default options
        const defaults = {
            title: 'Modal Title',
            body: '',
            footer: '',
            size: 'md',
            onShown: null,
            onHidden: null,
            backdrop: true,
            closeButton: true
        };

        // Merge options with defaults
        const settings = { ...defaults, ...options };

        // Get container
        const container = document.getElementById(MODAL_CONTAINER_ID);

        // Get backdrop
        const backdrop = document.getElementById(MODAL_BACKDROP_ID);

        // Set size class
        let sizeClass = 'max-w-md';
        switch (settings.size) {
            case 'sm':
                sizeClass = 'max-w-sm';
                break;
            case 'md':
                sizeClass = 'max-w-md';
                break;
            case 'lg':
                sizeClass = 'max-w-lg';
                break;
            case 'xl':
                sizeClass = 'max-w-xl';
                break;
            case '2xl':
                sizeClass = 'max-w-2xl';
                break;
            case '3xl':
                sizeClass = 'max-w-3xl';
                break;
            case '4xl':
                sizeClass = 'max-w-4xl';
                break;
            case '5xl':
                sizeClass = 'max-w-5xl';
                break;
            case 'full':
                sizeClass = 'max-w-full mx-4';
                break;
        }

        // Create modal HTML
        const modalHTML = `
            <div class="fixed inset-0 z-50 flex items-center justify-center p-4 nepal-modal-wrapper">
                <div class="bg-white dark:bg-gray-800 rounded-lg shadow-xl overflow-hidden w-full ${sizeClass} transform transition-all">
                    <div class="border-b border-gray-200 dark:border-gray-700">
                        <div class="px-4 py-3 flex justify-between items-center">
                            <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100">${settings.title}</h3>
                            ${settings.closeButton ? `
                                <button type="button" class="text-gray-400 hover:text-gray-500 focus:outline-none" data-dismiss="modal">
                                    <span class="iconify" data-icon="tabler:x" data-width="20"></span>
                                </button>
                            ` : ''}
                        </div>
                    </div>
                    <div class="overflow-y-auto" style="max-height: calc(100vh - 200px);">
                        ${settings.body}
                    </div>
                    ${settings.footer ? `
                        <div class="border-t border-gray-200 dark:border-gray-700 px-4 py-3 flex justify-end space-x-2">
                            ${settings.footer}
                        </div>
                    ` : ''}
                </div>
            </div>
        `;

        // Set container content
        container.innerHTML = modalHTML;

        // Show container and backdrop
        container.style.display = 'block';
        container.setAttribute('aria-hidden', 'false');

        if (settings.backdrop) {
            backdrop.style.display = 'block';
        }

        // Add event listeners for close buttons
        const closeButtons = container.querySelectorAll('[data-dismiss="modal"]');
        closeButtons.forEach(button => {
            button.addEventListener('click', hide);
        });

        // Add click event to backdrop for closing
        if (settings.backdrop) {
            backdrop.addEventListener('click', hide);
        }

        // Add click event to modal wrapper for closing (only if clicking outside the modal)
        const modalWrapper = container.querySelector('.nepal-modal-wrapper');
        if (modalWrapper) {
            modalWrapper.addEventListener('click', function(e) {
                if (e.target === modalWrapper) {
                    hide();
                }
            });
        }

        // Add escape key handler
        document.addEventListener('keydown', handleEscapeKey);

        // Store current modal
        currentModal = {
            container,
            backdrop,
            settings
        };

        // Call onShown callback
        if (typeof settings.onShown === 'function') {
            settings.onShown();
        }

        // Prevent body scrolling
        document.body.style.overflow = 'hidden';
    }

    /**
     * Hide the current modal
     */
    function hide() {
        if (!currentModal) {
            return;
        }

        // Hide container and backdrop
        currentModal.container.style.display = 'none';
        currentModal.container.setAttribute('aria-hidden', 'true');
        currentModal.backdrop.style.display = 'none';

        // Remove escape key handler
        document.removeEventListener('keydown', handleEscapeKey);

        // Call onHidden callback
        if (typeof currentModal.settings.onHidden === 'function') {
            currentModal.settings.onHidden();
        }

        // Clear current modal
        currentModal = null;

        // Restore body scrolling
        document.body.style.overflow = '';
    }

    /**
     * Handle escape key press
     *
     * @param {KeyboardEvent} e - Keyboard event
     */
    function handleEscapeKey(e) {
        if (e.key === 'Escape' && currentModal) {
            hide();
        }
    }

    // Return public API
    return {
        show,
        hide
    };
})();
