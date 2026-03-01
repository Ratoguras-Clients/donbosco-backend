@extends('layouts.app')

@section('content')
    <div class="">
        @include('components.breadcrumb', [
            'breadcrumbs' => [['title' => 'Media', 'url' => route('dashboard.media.index')]],
        ])

        <div class="flex justify-between items-center mb-6">
            <div class="flex items-center gap-6">
                <h1 class="text-3xl font-bold text-gray-900 dark:text-white">Media Library</h1>

                <!-- Toggle between normal and trashed media -->
                <div class="flex items-center gap-3">
                    <label class="flex items-center cursor-pointer">
                        <input type="checkbox" id="trashed-toggle" class="sr-only" {{ $trashed ? 'checked' : '' }}>
                        <div class="relative w-16 h-8">
                            <!-- Background track with gradient -->
                            <div
                                class="block w-full h-full rounded-full transition-all duration-300 {{ $trashed ? 'bg-gradient-to-r from-red-400 to-red-500 shadow-red-200' : 'bg-gradient-to-r from-green-400 to-green-500 shadow-green-200' }} shadow-lg">
                            </div>

                            <!-- Icons in the track -->
                            <div class="absolute inset-0 flex items-center justify-between px-2">
                                <!-- Active icon (left side) -->
                                <span
                                    class="iconify text-white transition-opacity duration-300 {{ $trashed ? 'opacity-40' : 'opacity-100' }}"
                                    data-icon="tabler:check" data-width="14"></span>
                                <!-- Trash icon (right side) -->
                                <span
                                    class="iconify text-white transition-opacity duration-300 {{ $trashed ? 'opacity-100' : 'opacity-40' }}"
                                    data-icon="tabler:trash" data-width="14"></span>
                            </div>

                            <!-- Sliding dot/handle -->
                            <div
                                class="dot absolute top-0.5 left-0.5 bg-white w-7 h-7 rounded-full shadow-lg transition-all duration-300 flex items-center justify-center {{ $trashed ? 'transform translate-x-8' : '' }}">
                                <!-- Icon inside the handle -->
                                <span
                                    class="iconify transition-all duration-300 {{ $trashed ? 'text-red-500' : 'text-green-500' }}"
                                    data-icon="{{ $trashed ? 'tabler:trash' : 'tabler:check' }}" data-width="16"
                                    id="toggle-handle-icon"></span>
                            </div>
                        </div>
                        <div class="ml-3 text-gray-700 dark:text-gray-300 font-medium">
                            <span id="toggle-label">{{ $trashed ? 'Trashed Media' : 'Active Media' }}</span>
                        </div>
                    </label>
                </div>
            </div>
            <div class="flex items-center gap-3">
                <button type="button" id="refresh-media-button"
                    class="bg-gray-600 hover:bg-gray-700 px-4 py-2 text-white rounded-md flex items-center justify-center transition-colors"
                    title="Refresh media library">
                    <span class="iconify mr-2" data-icon="tabler:refresh" data-width="20"></span>
                    Refresh
                </button>
                <button type="button" id="add-new-media-button"
                    class="bg-blue-600 select-media-btn hover:bg-blue-700 px-4 py-2 text-white rounded-md flex items-center justify-center transition-colors {{ $trashed ? 'hidden' : '' }}">
                    <span class="iconify mr-2" data-icon="tabler:plus" data-width="20"></span>
                    Add New Media
                </button>
            </div>
        </div>

        <!-- Filter and Search Section -->
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow-md p-4 mb-6">
            <div class="flex flex-col lg:flex-row items-start lg:items-center justify-between gap-4">
                <div class="flex flex-col sm:flex-row items-start sm:items-center gap-4">
                    <label class="text-sm font-medium text-gray-700 dark:text-gray-300">Filter media</label>
                    <div class="flex flex-col sm:flex-row items-start sm:items-center gap-3">
                        <select id="type-filter"
                            class="px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 bg-white dark:bg-gray-700 text-gray-700 dark:text-gray-300">
                            <option value="">All Media</option>
                            <option value="image">Images</option>
                            <option value="video">Videos</option>
                            <option value="audio">Audio</option>
                            <option value="document">Documents</option>
                        </select>
                        <select id="date-filter"
                            class="px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 bg-white dark:bg-gray-700 text-gray-700 dark:text-gray-300">
                            <option value="">All dates</option>
                            <option value="today">Today</option>
                            <option value="week">This week</option>
                            <option value="month">This month</option>
                            <option value="year">This year</option>
                        </select>
                    </div>
                </div>
                <div class="flex items-center gap-4 w-full lg:w-auto">
                    <label class="text-sm font-medium text-gray-700 dark:text-gray-300">Search media</label>
                    <div class="relative flex-1 lg:w-64">
                        <input type="text" id="search-input" placeholder="Search media items..."
                            class="w-full px-3 py-2 pl-10 border border-gray-300 dark:border-gray-600 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 bg-white dark:bg-gray-700 text-gray-700 dark:text-gray-300 placeholder-gray-400 dark:placeholder-gray-500">
                        <span
                            class="iconify absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-400 dark:text-gray-500"
                            data-icon="tabler:search" data-width="16"></span>
                    </div>
                    <button type="button" id="clear-filters"
                        class="px-4 py-2 text-sm text-gray-600 hover:text-gray-800 dark:text-gray-400 dark:hover:text-gray-200 border border-gray-300 dark:border-gray-600 rounded-lg hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors"
                        title="Clear all filters">
                        <span class="iconify" data-icon="tabler:x" data-width="16"></span>
                    </button>
                </div>
            </div>
        </div>

        <div class="bg-white dark:bg-gray-800 rounded-lg shadow-md p-6">
            <div id="media-library-content"
                class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 xl:grid-cols-5 2xl:grid-cols-6 gap-4">
                {{-- Media items will be loaded here by JS --}}
            </div>
            <div class="text-center mt-6">
                <button type="button" id="load-more-media"
                    class="px-4 py-2 text-gray-600 hover:text-gray-800 border border-gray-300 rounded-lg transition-colors hidden">Load
                    More</button>
                <p id="no-media-found" class="text-gray-500 hidden">No media found.</p>
            </div>
        </div>
    </div>

    <x-media-piker modalId="job-poster-picker" title="Select or Upload Job Poster Image" :allowMultiple="false" :allowUpload="true"
        :allowView="true" triggerSelector=".select-media-btn" onSelect="handlePosterImageSelection" acceptedTypes="image/*"
        maxFileSize="10MB" />
@endsection

@push('scripts')
    <script>
        window.handlePosterImageSelection = function(selectedMedia, picker) {
            if (!selectedMedia) return;

            let mediaUrl = null;
            const mediaId = selectedMedia;

            // Try to find the media URL using multiple methods
            const mediaItem = $(`#${picker.modalId} .media-item[data-media-id="${mediaId}"]`);
            if (mediaItem.length) {
                mediaUrl = mediaItem.data('media-url');
            }
            if (!mediaUrl) {
                const anyMediaItem = $(`.media-item[data-media-id="${mediaId}"]`);
                if (anyMediaItem.length) {
                    mediaUrl = anyMediaItem.first().data('media-url');
                }
            }

            if (mediaUrl) {
                // Update the hidden input
                $('#poster_img').val(mediaId);

                // Show the image preview
                showImagePreview(mediaUrl);

                // Show success message
                nepalToast.success('Success', 'Job poster image selected successfully!');
            }
        };


        // Function to show image preview
        function showImagePreview(imageUrl) {
            const container = $('.image-field-container');
            const uploadArea = container.find('.image-upload-area');
            const noImageState = uploadArea.find('.no-image-state');
            const imagePreviewState = uploadArea.find('.image-preview-state');
            const previewImg = imagePreviewState.find('.selected-image-preview');
            const imageName = imagePreviewState.find('#selected-image-name');

            // Update preview image
            previewImg.attr('src', imageUrl);

            // Extract and show filename
            const fileName = imageUrl.split('/').pop();
            imageName.text(fileName);

            // Switch states
            noImageState.addClass('hidden');
            imagePreviewState.removeClass('hidden');

            // Update upload area styling
            uploadArea.removeClass('border-dashed border-gray-300 dark:border-gray-600')
                .addClass(
                    'border-solid border-green-300 dark:border-green-600 bg-green-50 dark:bg-green-900/20');
        }

        // Function to hide image preview
        function hideImagePreview() {
            const container = $('.image-field-container');
            const uploadArea = container.find('.image-upload-area');
            const noImageState = uploadArea.find('.no-image-state');
            const imagePreviewState = uploadArea.find('.image-preview-state');

            // Clear the hidden input
            $('#poster_img').val('');

            // Switch states
            imagePreviewState.addClass('hidden');
            noImageState.removeClass('hidden');

            // Reset upload area styling
            uploadArea.removeClass(
                    'border-solid border-green-300 dark:border-green-600 bg-green-50 dark:bg-green-900/20')
                .addClass('border-dashed border-gray-300 dark:border-gray-600');
        }

        // Remove poster image handler
        $(document).on('click', '.remove-poster-image-btn', function(e) {
            e.stopPropagation();
            hideImagePreview();
            nepalToast.info('Info', 'Job poster image removed');
        });

        // Initialize image preview if there's an old value
        const oldImageValue = $('#poster_img').val();
        if (oldImageValue) {
            showImagePreview(oldImageValue);
        }

        try {
            window.jobPosterPicker = new MediaPickerClass('job-poster-picker', {
                allowMultiple: false,
                allowUpload: true,
                allowView: true,
                onSelect: window.handlePosterImageSelection,
                triggerSelector: '.select-media-btn',
                uploadRoute: '{{ route('dashboard.media.upload') }}',
                mediaRoute: '{{ route('dashboard.media.index') }}'
            });

            console.log('MediaPicker instances initialized successfully');
        } catch (error) {
            console.error('Error initializing MediaPicker instances:', error);
        }
        // Global variable for trashed state
        let showTrashed = {{ $trashed ? 'true' : 'false' }};

        $(document).ready(function() {
            let currentPage = 1;
            let isLoading = false;
            let hasMore = true;
            // let mediaIdToDelete = null;
            window.window.mediaIdToDelete = null;

            // Filter variables
            let currentFilters = {
                type_filter: '',
                date_filter: '',
                search: ''
            };
            let searchTimeout = null;

            // Toggle handler for switching between active and trashed media
            $('#trashed-toggle').on('change', function() {
                showTrashed = this.checked;

                // Update the toggle switch styling
                const $toggleContainer = $(this).parent().find('.relative');
                const $track = $toggleContainer.find('.block');
                const $dot = $toggleContainer.find('.dot');
                const $handleIcon = $('#toggle-handle-icon');
                const $label = $('#toggle-label');
                const $addButton = $('#add-new-media-button');

                if (showTrashed) {
                    // Switch to trashed state
                    $track.removeClass('bg-gradient-to-r from-green-400 to-green-500 shadow-green-200')
                        .addClass('bg-gradient-to-r from-red-400 to-red-500 shadow-red-200');
                    $dot.addClass('transform translate-x-8');
                    $handleIcon.removeClass('text-green-500').addClass('text-red-500')
                        .attr('data-icon', 'tabler:trash');
                    $label.text('Trashed Media');
                    $addButton.addClass('hidden');
                } else {
                    // Switch to active state
                    $track.removeClass('bg-gradient-to-r from-red-400 to-red-500 shadow-red-200')
                        .addClass('bg-gradient-to-r from-green-400 to-green-500 shadow-green-200');
                    $dot.removeClass('transform translate-x-8');
                    $handleIcon.removeClass('text-red-500').addClass('text-green-500')
                        .attr('data-icon', 'tabler:check');
                    $label.text('Active Media');
                    $addButton.removeClass('hidden');
                }

                // Reset filters when switching between active and trashed
                currentFilters = {
                    type_filter: '',
                    date_filter: '',
                    search: ''
                };

                // Reset form elements
                $('#type-filter').val('');
                $('#date-filter').val('');
                $('#search-input').val('');

                // Reset pagination and reload media
                currentPage = 1;
                hasMore = true;
                loadMediaLibrary(false, showTrashed);
            });

            // Load media for main library
            function loadMediaLibrary(append = false, trashed = false) {
                if (isLoading || !hasMore) return;

                isLoading = true;
                $('#load-more-media').prop('disabled', true).html(
                    '<span class="iconify animate-spin" data-icon="tabler:loader" data-width="20"></span> Loading...'
                );

                // Prepare request data with filters
                const requestData = {
                    page: currentPage,
                    trashed: trashed
                };

                // Add filters if they have values
                if (currentFilters.type_filter && currentFilters.type_filter.trim() !== '') {
                    requestData.type_filter = currentFilters.type_filter;
                }
                if (currentFilters.date_filter && currentFilters.date_filter.trim() !== '') {
                    requestData.date_filter = currentFilters.date_filter;
                }
                if (currentFilters.search && currentFilters.search.trim() !== '') {
                    requestData.search = currentFilters.search;
                }

                $.ajax({
                    url: '{{ route('dashboard.media.index') }}',
                    method: 'GET',
                    data: requestData,
                    success: function(response) {
                        if (append) {
                            $('#media-library-content').append(response.html);
                        } else {
                            $('#media-library-content').html(response.html);
                        }

                        hasMore = response.has_more;
                        if (hasMore) {
                            $('#load-more-media').show().prop('disabled', false).html('Load More');
                            $('#no-media-found').hide();
                        } else {
                            $('#load-more-media').hide();
                            if ($('#media-library-content').is(':empty')) {
                                // Show appropriate message based on filters
                                let message;
                                if (currentFilters.type_filter || currentFilters.date_filter ||
                                    currentFilters.search) {
                                    message = 'No media found matching your criteria.';
                                } else {
                                    message = trashed ? 'No trashed media found.' :
                                        'No media available.';
                                }
                                $('#no-media-found').text(message).show();
                            } else {
                                $('#no-media-found').text('No more media to load.').show();
                            }
                        }
                    },
                    error: function() {
                        if (typeof nepalToast !== 'undefined') {
                            nepalToast.error('Error', 'Failed to load media.');
                        }
                        $('#load-more-media').hide();
                        $('#no-media-found').text('Failed to load media.').show();
                    },
                    complete: function() {
                        isLoading = false;
                    }
                });
            }

            // Initial load
            loadMediaLibrary(false, showTrashed);

            // Filter event handlers
            function applyFilters() {
                currentPage = 1;
                hasMore = true;
                loadMediaLibrary(false, showTrashed);
            }

            // Type filter change handler
            $('#type-filter').on('change', function() {
                currentFilters.type_filter = $(this).val();
                applyFilters();
            });

            // Date filter change handler
            $('#date-filter').on('change', function() {
                currentFilters.date_filter = $(this).val();
                applyFilters();
            });

            // Search input with debounce
            $('#search-input').on('input', function() {
                const searchValue = $(this).val();
                currentFilters.search = searchValue;

                // Clear existing timeout
                if (searchTimeout) {
                    clearTimeout(searchTimeout);
                }

                // Set new timeout for debounced search
                searchTimeout = setTimeout(function() {
                    applyFilters();
                }, 300);
            });

            // Clear filters button
            $('#clear-filters').on('click', function() {
                nepalConfirm.show({
                    title: 'Clear All Filters',
                    message: 'Are you sure you want to clear all filters? This will reset your media search and filter selections.',
                    type: 'warning',
                    confirmText: 'Clear Filters',
                    cancelText: 'Cancel',
                    confirmIcon: '<span class="iconify" data-icon="tabler:filter-off" data-width="18"></span>'
                }).then(() => {
                    // Reset all filters
                    currentFilters = {
                        type_filter: '',
                        date_filter: '',
                        search: ''
                    };

                    // Reset form elements
                    $('#type-filter').val('');
                    $('#date-filter').val('');
                    $('#search-input').val('');

                    // Apply filters (which will be empty now)
                    applyFilters();

                    if (typeof nepalToast !== 'undefined') {
                        nepalToast.success('Success', 'Filters cleared.');
                    }
                }).catch(() => {
                    if (typeof nepalToast !== 'undefined') {
                        nepalToast.info('Canceled', 'Clear filters action was canceled.');
                    }
                });
            });

            // Load more button
            $('#load-more-media').on('click', function() {
                currentPage++;
                loadMediaLibrary(true, showTrashed);
            });

            // Refresh button
            $('#refresh-media-button').on('click', function() {
                const $btn = $(this);
                const originalHtml = $btn.html();

                // Show loading state
                $btn.prop('disabled', true).html(
                    '<span class="iconify animate-spin mr-2" data-icon="tabler:loader" data-width="20"></span>Refreshing...'
                );

                // Reset pagination and reload media
                currentPage = 1;
                hasMore = true;

                // Add a small delay to show the loading state
                // setTimeout(function() {
                loadMediaLibrary(false, showTrashed);

                // Re-enable button after load completes
                // setTimeout(function() {
                $btn.prop('disabled', false).html(originalHtml);

                // Show success message
                if (typeof nepalToast !== 'undefined') {
                    nepalToast.success('Success', 'Media library refreshed.');
                }
                //     }, 500);
                // }, 300);
            });




        });

        function confirmRestoreMedia(mediaId, filename) {
            nepalConfirm.show({
                title: 'Confirm Restore Media',
                message: `Are you sure you want to restore "<span class="font-medium">${filename}</span>"? This will move the media back to the active library.`,
                type: 'success',
                confirmText: 'Restore Media',
                cancelText: 'Cancel',
                confirmIcon: '<span class="iconify" data-icon="tabler:refresh" data-width="18"></span>'
            }).then(() => {
                const mediaRoute = `{{ route('dashboard.media.restore', '__id__') }}`;
                const restoreMediaUrl = mediaRoute.replace('__id__', mediaId);

                $.ajax({
                    url: restoreMediaUrl,
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    success: function(response) {
                        if (typeof nepalToast !== 'undefined') {
                            nepalToast.success('Success', 'Media restored successfully.');
                        }
                        // Refresh the media library
                        currentPage = 1;
                        hasMore = true;
                        loadMediaLibrary(false, showTrashed);
                    },
                    error: function(xhr) {
                        let errorMessage = 'Failed to restore media.';
                        if (xhr.responseJSON && xhr.responseJSON.message) {
                            errorMessage = xhr.responseJSON.message;
                        }
                        if (typeof nepalToast !== 'undefined') {
                            nepalToast.error('Error', errorMessage);
                        } else {
                            alert('Error: ' + errorMessage);
                        }
                    }
                });
            }).catch(() => {
                if (typeof nepalToast !== 'undefined') {
                    nepalToast.info('Action Canceled', 'Media restore was canceled.');
                }
            });
        }
        // Global function to show delete confirmation using nepalConfirm
        function confirmDeleteMedia(mediaId, filename) {
            let isPermanent = showTrashed; // If in trashed view, delete is permanent

            nepalConfirm.show({
                title: isPermanent ? 'Confirm Permanent Delete' : 'Confirm Move to Trash',
                message: isPermanent ?
                    `Are you sure you want to permanently delete "<span class="font-medium">${filename}</span>"? This action cannot be undone and the file will be removed from the server.` :
                    `Are you sure you want to move "<span class="font-medium">${filename}</span>" to trash? You can restore it later if needed.`,
                type: isPermanent ? 'danger' : 'warning',
                confirmText: isPermanent ? 'Delete Permanently' : 'Move to Trash',
                cancelText: 'Cancel',
                confirmIcon: `<span class="iconify" data-icon="${isPermanent ? 'tabler:trash' : 'tabler:archive'}" data-width="18"></span>`
            }).then(() => {
                // AJAX call to delete media
                const mediaRoute = `{{ route('dashboard.media.destroy', '__id__') }}`;
                const deleteMediaUrl = mediaRoute.replace('__id__', mediaId);

                $.ajax({
                    url: deleteMediaUrl,
                    method: 'DELETE',
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    success: function(response) {
                        if (typeof nepalToast !== 'undefined') {
                            nepalToast.success('Success', isPermanent ? 'Media deleted permanently.' :
                                'Media moved to trash.');
                        }
                        // Refresh the media library
                        currentPage = 1;
                        hasMore = true;
                        loadMediaLibrary(false, showTrashed);
                    },
                    error: function(xhr) {
                        let errorMessage = 'Failed to delete media.';
                        if (xhr.responseJSON && xhr.responseJSON.message) {
                            errorMessage = xhr.responseJSON.message;
                        }
                        if (typeof nepalToast !== 'undefined') {
                            nepalToast.error('Error', errorMessage);
                        } else {
                            alert('Error: ' + errorMessage);
                        }
                    }
                });
            }).catch(() => {
                if (typeof nepalToast !== 'undefined') {
                    nepalToast.info('Action Canceled', 'Media deletion was canceled.');
                }
            });
        }



        // Global function to restore media (called from media grid) - DEPRECATED, use confirmRestoreMedia instead
        function restoreMedia(mediaId, filename) {
            // Redirect to use the modal instead
            confirmRestoreMedia(mediaId, filename);
        }

        // Custom handler for media library selection (called from MediaPicker component)
        function handleMediaLibrarySelection(selectedMedia, picker) {
            // For the main media library page, we don't need to do anything special
            // Just refresh the main library to show any newly uploaded media
            if (selectedMedia) {
                // If a media was uploaded or selected, refresh the main library
                window.location.reload();
            }
        }
    </script>
@endpush
