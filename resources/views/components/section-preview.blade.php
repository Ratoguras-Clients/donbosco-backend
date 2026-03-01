<div id="{{ $modalId }}"
    class="fixed inset-0 bg-black bg-opacity-50 items-center justify-center z-[1000] hidden {{ implode(' ', $customClasses) }}">
    <div class="bg-white rounded-lg shadow-xl w-[85vw] h-[90vh] max-h-[90vh] flex flex-col">
        {{-- Modal Header --}}
        <div class="flex justify-between items-center p-6 border-b border-gray-200">
            <h2 class="text-xl font-semibold text-gray-800 flex items-center">
                <span class="iconify mr-2 text-nepal-blue" data-icon="tabler:eye" data-width="24"></span>
                Section Preview
            </h2>
            <div class="flex items-center gap-2">
                <button type="button"
                    class="fullscreen-preview-modal text-gray-500 hover:text-gray-700 transition-colors duration-200 rounded-full p-2 hover:bg-gray-100"
                    data-modal-id="{{ $modalId }}"
                    title="Toggle Fullscreen">
                    <span class="iconify" data-icon="tabler:arrows-maximize" data-width="20"></span>
                    <span class="sr-only">Fullscreen</span>
                </button>
                <button type="button"
                    class="close-preview-modal text-gray-500 hover:text-gray-700 transition-colors duration-200 rounded-full p-2 hover:bg-gray-100"
                    data-modal-id="{{ $modalId }}">
                    <span class="iconify" data-icon="tabler:x" data-width="24"></span>
                </button>
            </div>
        </div>

        {{-- Modal Body --}}
        <div class="flex-1 overflow-hidden">
            <div class="preview-content-container h-full w-full overflow-auto p-6 bg-slate-50">
                {{-- Loading State --}}
                <div class="preview-loading flex items-center justify-center h-full">
                    <div class="text-center">
                        <span class="iconify animate-spin text-nepal-blue mb-2" data-icon="tabler:loader"
                            data-width="32"></span>
                        <p class="text-gray-600">Loading preview...</p>
                    </div>
                </div>

                {{-- Preview Content --}}
                <div class="preview-content hidden bg-white rounded-lg shadow-sm min-h-full">
                    {{-- Dynamic content will be loaded here --}}
                </div>

                {{-- Error State --}}
                <div class="preview-error hidden">
                    <div class="flex items-center justify-center h-full">
                        <div class="text-center bg-white rounded-lg p-8 shadow-sm max-w-md mx-auto">
                            <span class="iconify text-red-500 mb-4" data-icon="tabler:alert-circle"
                                data-width="48"></span>
                            <h3 class="text-lg font-semibold text-gray-800 mb-2">Preview Error</h3>
                            <p class="text-red-600 mb-4 preview-error-message">Failed to load preview</p>
                            <button type="button"
                                class="retry-preview inline-flex items-center px-4 py-2 bg-nepal-blue text-white rounded-md hover:bg-nepal-blue-dark transition-colors"
                                data-modal-id="{{ $modalId }}">
                                <span class="iconify mr-2" data-icon="tabler:refresh" data-width="16"></span>
                                Retry
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>
</div>

{{-- Component Styles --}}
@push('styles')
    <style>
        /* Preview Modal Specific Styles */
        #{{ $modalId }} {
            z-index: 1000;
        }

        #{{ $modalId }} .preview-content {
            position: relative;
            z-index: 10;
            overflow: visible;
            width: 100%;
            min-height: 100%;
        }

        /* Override fixed positioning in preview */
        #{{ $modalId }} .preview-content nav.fixed {
            position: relative !important;
            top: 0;
            left: 0;
            z-index: 1;
            width: 100%;
        }

        #{{ $modalId }} .preview-content-container {
            position: relative;
            overflow-y: auto;
            max-height: 100%;
        }

        .preview-content-wrapper {
            padding: 1rem;
            width: 100%;
            max-width: 1280px;
            margin-left: auto;
            margin-right: auto;
        }

        /* Fullscreen modal styles */
        #{{ $modalId }}.fullscreen {
            position: fixed !important;
            top: 0 !important;
            left: 0 !important;
            right: 0 !important;
            bottom: 0 !important;
            width: 100vw !important;
            height: 100vh !important;
            max-width: none !important;
            max-height: none !important;
            border-radius: 0 !important;
            z-index: 9999 !important;
        }

        #{{ $modalId }}.fullscreen .bg-white {
            border-radius: 0 !important;
            width: 100% !important;
            height: 100% !important;
        }

        /* Smooth transitions for states */
        #{{ $modalId }} .preview-loading,
        #{{ $modalId }} .preview-content,
        #{{ $modalId }} .preview-error {
            transition: all 0.3s ease-in-out;
        }

        /* Fullscreen mode */
        #{{ $modalId }}.fullscreen {
            z-index: 9999;
        }

        #{{ $modalId }}.fullscreen .bg-white {
            width: 100vw;
            height: 100vh;
            max-height: 100vh;
            border-radius: 0;
        }

        /* Responsive adjustments */
        @media (max-width: 768px) {
            #{{ $modalId }} .bg-white {
                width: 95vw;
                height: 95vh;
                max-height: 95vh;
            }

            .preview-content-wrapper {
                padding: 0.5rem;
            }
        }

        /* Loading animation enhancement */
        @keyframes pulse-subtle {

            0%,
            100% {
                opacity: 1;
            }

            50% {
                opacity: 0.7;
            }
        }

        #{{ $modalId }} .preview-loading {
            animation: pulse-subtle 2s ease-in-out infinite;
        }

        /* Error state styling */
        #{{ $modalId }} .preview-error .text-center {
            animation: fadeIn 0.3s ease-in-out;
        }

        @keyframes fadeIn {
            from {
                opacity: 0;
                transform: translateY(-10px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
    </style>
@endpush

{{-- Component JavaScript --}}
@push('scripts')
    <script>
        $(document).ready(function() {
            const modalId = '{{ $modalId }}';
            const $modal = $('#' + modalId);

            if (!$modal.length) return;

            const $closeBtn = $modal.find('.close-preview-modal');
            const $retryBtn = $modal.find('.retry-preview');
            const $fullscreenBtn = $modal.find('.fullscreen-preview-modal');

            let currentSectionId = null;
            let isFullscreen = false;
            let previewRoute = null;


            // Close modal handlers
            $closeBtn.on('click', closeModal);
            
            // Fullscreen toggle handler
            $fullscreenBtn.on('click', toggleFullscreen);
            $modal.on('click', function(e) {
                if (e.target === this) {
                    closeModal();
                }
            });

            // Escape key to close
            $(document).on('keydown', function(e) {
                if (e.key === 'Escape' && !$modal.hasClass('hidden')) {
                    closeModal();
                }
            });


            // Retry on error
            $retryBtn.on('click', function() {
                if (currentSectionId && previewRoute) {
                    loadPreview(currentSectionId, previewRoute);
                }
            });

            function closeModal() {
                $modal.addClass('hidden').removeClass('flex');
                currentSectionId = null;

                // Reset fullscreen if active
                if (isFullscreen) {
                    toggleFullscreen();
                }
            }

            function toggleFullscreen() {
                isFullscreen = !isFullscreen;
                $modal.toggleClass('fullscreen', isFullscreen);

                const $icon = $fullscreenBtn.find('.iconify');
                if ($icon.length) {
                    $icon.attr('data-icon', isFullscreen ? 'tabler:arrows-minimize' : 'tabler:arrows-maximize');
                }

                const $text = $fullscreenBtn.find('span:last-child');
                if ($text.length) {
                    $text.text(isFullscreen ? 'Exit Fullscreen' : 'Fullscreen');
                }
            }

            function showLoading() {
                $modal.find('.preview-loading').removeClass('hidden');
                $modal.find('.preview-content').addClass('hidden');
                $modal.find('.preview-error').addClass('hidden');
            }

            function showContent() {
                $modal.find('.preview-loading').addClass('hidden');
                $modal.find('.preview-content').removeClass('hidden');
                $modal.find('.preview-error').addClass('hidden');
            }

            function showError(message = 'Failed to load preview') {
                $modal.find('.preview-loading').addClass('hidden');
                $modal.find('.preview-content').addClass('hidden');
                $modal.find('.preview-error').removeClass('hidden');

                const $errorMessage = $modal.find('.preview-error-message');
                if ($errorMessage.length) {
                    $errorMessage.text(message);
                }
            }

            function loadPreview(sectionId, route) {
                showLoading();

                $.ajax({
                    url: route,
                    method: 'GET',
                    data: {
                        section_id: sectionId
                    },
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest',
                        'Accept': 'application/json'
                    },
                    success: function(data) {
                        if (data.success && data.html) {
                            const wrappedHtml = `
                                <div class="preview-content-wrapper max-w-7xl">
                                    ${data.html}
                                </div>
                            `;
                            $modal.find('.preview-content').html(wrappedHtml);
                            showContent();
                        } else {
                            showError(data.message || 'Failed to load preview');
                        }
                    },
                    error: function(xhr, status, error) {
                        console.error('Preview load error:', error);
                        let errorMessage = 'Failed to load preview';

                        if (xhr.responseJSON && xhr.responseJSON.message) {
                            errorMessage = xhr.responseJSON.message;
                        } else if (xhr.statusText) {
                            errorMessage = `HTTP ${xhr.status}: ${xhr.statusText}`;
                        } else if (error) {
                            errorMessage = error;
                        }

                        showError(errorMessage);
                    }
                });
            }

            // Public API for external usage
            window['SectionPreview_' + modalId.replace(/-/g, '_')] = {
                show: function(sectionId, route = null) {
                    currentSectionId = sectionId;
                    if (route) previewRoute = route;

                    $modal.removeClass('hidden').addClass('flex');

                    if (previewRoute && sectionId) {
                        loadPreview(sectionId, previewRoute);
                    }
                },

                hide: closeModal,

                refresh: function() {
                    if (currentSectionId && previewRoute) {
                        loadPreview(currentSectionId, previewRoute);
                    }
                },

                setRoute: function(route) {
                    previewRoute = route;
                },

                // Additional utility methods
                isVisible: function() {
                    return !$modal.hasClass('hidden');
                },

                getCurrentSectionId: function() {
                    return currentSectionId;
                },

                isFullscreen: function() {
                    return isFullscreen;
                }
            };

            // Initialize component on load
            console.log(`SectionPreview component "${modalId}" initialized`);
        });
    </script>
@endpush
