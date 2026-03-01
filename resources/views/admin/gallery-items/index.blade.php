@extends('layouts.app')

@section('content')
    @include('components.breadcrumb', [
        'breadcrumbs' => [['title' => 'Gallery Items', 'url' => null]],
    ])

    @if (session('success'))
        <div id="success-message" data-message="{{ session('success') }}" class="hidden"></div>
    @endif

    <div class="premium-card bg-white dark:bg-gray-800 rounded-xl shadow-md border border-gray-200 dark:border-gray-700">
        <div
            class="premium-card-header flex flex-col md:flex-row justify-between items-center p-4 border-b border-gray-200 dark:border-gray-700">
            <div class="premium-card-title flex items-center mb-4 md:mb-0">
                <div
                    class="h-12 w-12 flex items-center justify-center rounded-md bg-gradient-to-br from-slate-500 to-slate-700 mr-3">
                    <i class="fas fa-folder text-white text-md"></i>
                </div>
                <div class="header-text">
                    <h4 class="text-lg font-semibold text-gray-800 dark:text-white">Gallery Items Management</h4>
                    <p class="text-sm text-gray-500 dark:text-gray-400">Manage all items inside the collection
                        {{ $collection->title }}</p>
                </div>
            </div>
            
            <div class="mt-4 md:mt-0 flex flex-col sm:flex-row space-y-3 sm:space-y-0 sm:space-x-3">
                <a href="{{ url()->previous() }}"
                class="px-4 py-2 bg-gray-200 dark:bg-gray-900 text-gray-700 dark:text-white rounded-md hover:bg-gray-300 dark:hover:bg-gray-700 flex items-center">
                <span class="iconify mr-2" data-icon="tabler:arrow-left" data-width="18"></span>
                Back
            </a>
                <button id="create-btn"
                    class="block text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800">
                    Add Gallery Item
                </button>
            </div>
        </div>

        <div class="max-w-full mx-auto px-4 py-6">

            <!-- Cover count indicator -->
            <div id="cover-count-badge"
                class="flex items-center text-sm text-yellow-600 font-medium mb-4 bg-yellow-50 dark:bg-yellow-900/20 border border-yellow-200 dark:border-yellow-700 rounded-lg px-4 py-2 w-fit">
            </div>

            <div id="gallery-grid" class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6">
                {{-- Gallery items will be injected here --}}
            </div>
        </div>

    </div>

    <!-- Media Picker -->
    <x-media-piker modalId="cover-image-media-picker" title="Select or Upload Image" :allowMultiple="false" :allowUpload="true"
        :allowView="true" triggerSelector=".select-image-btn" onSelect="handleCoverImageSelection"
        acceptedTypes="image/jpeg,image/jpg,image/png,image/gif,image/webp,image/svg+xml" maxFileSize="10MB" />
@endsection

@push('modals')
    <!-- Add Gallery Item Modal -->
    <div id="add-modal" class="z-10 bg-black/50 fixed inset-0 flex items-center justify-center" style="display: none;">
        <div class="bg-white dark:bg-gray-700 rounded-lg shadow-lg w-full max-w-4xl p-4 md:p-5 relative">
            <!-- Modal Header -->
            <div class="flex items-center justify-between border-b border-gray-200 dark:border-gray-600 pb-3">
                <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Add Gallery Item</h3>
                <span class="closeModal iconify cursor-pointer" data-icon="ic:baseline-close" data-width="24"></span>
            </div>

            <!-- Modal Body -->
            <form action="{{ route('gallery-items.store', $collection->id) }}" class="mt-4 mb-0" id="gallery-item-form"
                method="POST">
                @csrf

                <div class="grid gap-4 mb-4 grid-cols-2">
                    <div class="col-span-2">
                        <label for="title"
                            class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Title</label>
                        <input type="text" id="title" name="title" placeholder="Type Title"
                            class="bg-gray-50 border border-gray-300 text-gray-900 rounded-lg text-sm focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-600 dark:border-gray-500 dark:text-white">
                    </div>
                </div>

                <!-- Image -->
                <div class="bg-gray-50 dark:bg-gray-700/50 rounded-lg p-4 mb-6">
                    <h3 class="text-lg font-medium text-gray-800 dark:text-white mb-4 flex items-center">
                        <span class="iconify mr-2" data-icon="tabler:photo" data-width="20"></span>
                        Image
                    </h3>
                    <div
                        class="flex flex-col items-center justify-center p-4 border border-dashed border-gray-300 rounded-md">
                        <input type="hidden" name="media_id" id="media_id" value="{{ old('media_id') }}">

                        @if (old('media_id'))
                            <div id="featured-image-preview" class="mb-4">
                                @php $selectedMedia = $medias->firstWhere('id', old('media_id')); @endphp
                                @if ($selectedMedia)
                                    <img src="{{ $selectedMedia->url }}" alt="{{ $selectedMedia->alt_text }}"
                                        class="max-w-full h-48 object-contain rounded-md shadow-sm">
                                @endif
                            </div>
                        @endif

                        <div id="image-preview" class="mb-4 w-full"></div>

                        <button type="button" id="select-featured-image"
                            class="select-image-btn px-4 py-2 bg-gray-100 dark:bg-gray-700 text-gray-700 dark:text-gray-200 rounded-md border border-gray-300 dark:border-gray-600 flex items-center justify-center hover:bg-gray-200 dark:hover:bg-gray-600 transition">
                            <span class="iconify mr-2" data-icon="tabler:upload" data-width="20"></span>
                            {{ old('media_id') ? 'Change Image' : 'Select Image' }}
                        </button>

                        <button type="button" id="remove-featured-image"
                            class="text-red-600 hover:text-red-800 text-sm mt-2 {{ old('media_id') ? '' : 'hidden' }}">
                            Remove Image
                        </button>
                    </div>

                    @error('media_id')
                        <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                    @enderror
                </div>

                <div class="flex justify-between gap-3 py-3">
                    <button type="button"
                        class="closeModal text-white bg-red-700 hover:bg-red-800 rounded-lg text-sm px-5 py-2.5 inline-flex items-center">
                        Close
                    </button>
                    <button type="submit"
                        class="text-white bg-blue-700 hover:bg-blue-800 rounded-lg text-sm px-5 py-2.5 inline-flex items-center">
                        Save Item
                    </button>
                </div>
            </form>
        </div>
    </div>
@endpush

@push('scripts')
    <script>
        $(document).ready(function() {

            // ─── Load Gallery Items ───────────────────────────────────────────────
            function loadGalleryItems() {
                $.ajax({
                    url: "{{ route('gallery-items.data', $collection->id) }}",
                    method: 'GET',
                    success: function(response) {
                        const grid = $('#gallery-grid');
                        grid.empty();

                        if (!response.data || !response.data.length) {
                            grid.html(
                            '<p class="text-gray-500 col-span-4">No gallery items found.</p>');
                            $('#cover-count-badge').hide();
                            return;
                        }

                        const coverCount = response.data.filter(i => i.is_cover).length;
                        const remaining = 5 - coverCount;

                        $('#cover-count-badge').show().html(`
                            <span class="iconify mr-2" data-icon="tabler:star-filled" data-width="14"></span>
                            ${coverCount}/5 cover images selected
                            ${remaining === 0
                                ? '<span class="ml-2 text-red-500 font-semibold">(Limit reached)</span>'
                                : `<span class="ml-2 text-gray-500 dark:text-gray-400 font-normal">(${remaining} slot${remaining > 1 ? 's' : ''} remaining)</span>`
                            }
                        `);

                        response.data.forEach(item => {
                            const isCover = item.is_cover;
                            const atLimit = coverCount >= 5;
                            const canMark = isCover || !atLimit;

                            const card = `
                                <div class="bg-white dark:bg-gray-800 rounded-lg shadow hover:shadow-md transition overflow-hidden relative group" id="card-${item.id}">

                                    ${isCover ? `
                                            <span class="absolute top-2 left-2 z-10 bg-yellow-400 text-yellow-900 text-xs font-semibold px-2 py-0.5 rounded-full flex items-center gap-1 shadow">
                                                <span class="iconify" data-icon="tabler:star-filled" data-width="11"></span> Cover
                                            </span>
                                        ` : ''}

                                    <button
                                        type="button"
                                        class="set-cover-btn absolute top-2 right-2 z-10 p-1.5 rounded-full shadow transition
                                               ${isCover
                                                   ? 'bg-yellow-400 text-yellow-900 hover:bg-yellow-500'
                                                   : canMark
                                                       ? 'bg-white/80 text-gray-400 hover:text-yellow-500 hover:bg-white opacity-0 group-hover:opacity-100'
                                                       : 'bg-white/60 text-gray-300 cursor-not-allowed opacity-0 group-hover:opacity-100'}"
                                        data-item-id="${item.id}"
                                        data-collection-id="${item.collection_id}"
                                        ${!canMark ? 'disabled' : ''}
                                        title="${isCover ? 'Remove from covers' : atLimit ? 'Cover limit reached (max 5)' : 'Set as cover image'}">
                                        <span class="iconify" data-icon="${isCover ? 'tabler:star-filled' : 'tabler:star'}" data-width="16"></span>
                                    </button>

                                    <button
                                        type="button"
                                        class="delete-item-btn absolute bottom-16 right-2 z-10 p-1.5 rounded-full shadow bg-white/80 text-red-400 hover:text-red-600 hover:bg-white transition opacity-0 group-hover:opacity-100"
                                        data-item-id="${item.id}"
                                        data-collection-id="${item.collection_id}"
                                        title="Delete item">
                                        <span class="iconify" data-icon="tabler:trash" data-width="16"></span>
                                    </button>

                                    <div class="bg-gray-100 dark:bg-gray-700">
                                        <img
                                            src="${item.image ?? '/images/placeholder.png'}"
                                            alt="${item.title ?? 'Gallery Image'}"
                                            class="w-full h-48 object-cover"
                                            onerror="this.src='/images/placeholder.png'"
                                        >
                                    </div>

                                    <div class="p-4">
                                        <h3 class="text-sm font-semibold text-gray-800 dark:text-white truncate">
                                            ${item.title ?? 'Untitled'}
                                        </h3>
                                        <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">
                                            By ${item.created_by ?? '—'}
                                        </p>
                                        <p class="text-xs text-gray-400 dark:text-gray-500 mt-0.5">
                                            ${item.created_at}
                                        </p>
                                    </div>
                                </div>
                            `;

                            grid.append(card);
                        });

                        if (window.Iconify) Iconify.scan();
                    },
                    error: function() {
                        nepalToast.error('Error', 'Failed to load gallery items.');
                    }
                });
            }

            loadGalleryItems();


            // ─── Modal Open / Close ───────────────────────────────────────────────
            $('#create-btn').on('click', function() {
                $('#add-modal').show();
            });

            $(document).on('click', '.closeModal', function() {
                $('#add-modal').hide();
            });


            // ─── Cover Toggle ─────────────────────────────────────────────────────
            $(document).on('click', '.set-cover-btn', function() {
                const btn = $(this);
                const itemId = btn.data('item-id');
                const collectionId = btn.data('collection-id');

                $.ajax({
                    url: `/collections/${collectionId}/gallery/${itemId}/cover`,
                    method: 'POST',
                    data: {
                        _token: '{{ csrf_token() }}'
                    },
                    success: function() {
                        loadGalleryItems();
                    },
                    error: function(xhr) {
                        const msg = xhr.responseJSON?.message ??
                        'Failed to update cover image.';
                        nepalToast.error('Error', msg);
                    }
                });
            });


            // ─── Delete Item ──────────────────────────────────────────────────────
            $(document).on('click', '.delete-item-btn', function() {
                const itemId = $(this).data('item-id');
                const collectionId = $(this).data('collection-id');

                nepalConfirm.show({
                    title: 'Delete Gallery Item',
                    message: 'Are you sure you want to delete this gallery item? This action cannot be undone.',
                    type: 'danger',
                    confirmText: 'Delete',
                    cancelText: 'Cancel',
                    confirmIcon: '<i class="fas fa-trash w-4.5 h-4.5"></i>'
                }).then(() => {
                    $.ajax({
                        url: `/collections/${collectionId}/gallery/${itemId}`,
                        method: 'DELETE',
                        data: {
                            _token: '{{ csrf_token() }}'
                        },
                        success: function() {
                            nepalToast.success('Success',
                                'Gallery item deleted successfully.');
                            $(`#card-${itemId}`).fadeOut(300, function() {
                                $(this).remove();
                                loadGalleryItems();
                            });
                        },
                        error: function(xhr) {
                            nepalToast.error('Error', xhr.responseJSON?.message ??
                                'Failed to delete gallery item.');
                        }
                    });
                }).catch(() => {
                    nepalToast.info('Canceled', 'Gallery item deletion was canceled.');
                });
            });


            // ─── Media Picker — Image Selection ───────────────────────────────────
            window.handleCoverImageSelection = function(mediaId, picker) {
                if (!mediaId) return;

                let mediaUrl = null;

                const modalItem = $(`#${picker.modalId}-grid .media-item[data-media-id="${mediaId}"]`);
                if (modalItem.length) mediaUrl = modalItem.data('media-url');

                if (!mediaUrl) {
                    const anyItem = $(`.media-item[data-media-id="${mediaId}"]`).first();
                    if (anyItem.length) mediaUrl = anyItem.data('media-url');
                }

                if (!mediaUrl) {
                    console.error('Media URL not found for ID', mediaId);
                    return;
                }

                $('#media_id').val(mediaId);
                updateImagePreview(mediaUrl);
            };

            function updateImagePreview(url) {
                const fileName = url.split('/').pop();
                const ext = fileName.split('.').pop().toLowerCase();
                const isImage = ['jpg', 'jpeg', 'png', 'gif', 'webp', 'svg'].includes(ext);

                const previewHtml = `
                    <div class="relative bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-600 rounded-lg shadow-sm overflow-hidden">
                        <div class="flex items-center justify-between p-3 bg-gray-50 dark:bg-gray-700 border-b border-gray-200 dark:border-gray-600">
                            <div class="flex items-center gap-2">
                                <span class="iconify ${isImage ? 'text-green-500' : 'text-blue-500'}"
                                      data-icon="${isImage ? 'tabler:photo' : 'tabler:file'}" data-width="18"></span>
                                <span class="text-sm font-medium text-gray-700 dark:text-gray-300 truncate max-w-[240px]">
                                    ${fileName}
                                </span>
                            </div>
                            <button type="button" id="remove-image-inline"
                                    class="flex items-center gap-1 px-2 py-1 text-red-600 hover:text-red-800 dark:text-red-400 dark:hover:text-red-300 text-xs bg-red-50 dark:bg-red-900/20 rounded transition-colors">
                                <span class="iconify" data-icon="tabler:trash" data-width="14"></span>
                                Remove
                            </button>
                        </div>
                        <div class="flex items-center justify-center p-4 bg-gray-50 dark:bg-gray-700/50 min-h-[180px]">
                            ${isImage
                                ? `<img src="${url}" alt="Preview" class="max-w-full h-48 object-contain rounded-md shadow-sm">`
                                : `<span class="iconify text-gray-400" data-icon="tabler:file-text" data-width="64"></span>`
                            }
                        </div>
                    </div>
                `;

                $('#image-preview').html(previewHtml);
                $('#featured-image-preview').hide();
                $('#remove-featured-image').removeClass('hidden');
                $('#select-featured-image').text('Change Image');

                if (window.Iconify) Iconify.scan();
            }

            function clearImagePreview() {
                $('#media_id').val('');
                $('#image-preview').empty();
                $('#featured-image-preview').hide();
                $('#remove-featured-image').addClass('hidden');
                $('#select-featured-image').text('Select Image');
            }

            $('#remove-featured-image').on('click', clearImagePreview);
            $(document).on('click', '#remove-image-inline', clearImagePreview);


            // ─── Media Picker Init ────────────────────────────────────────────────
            try {
                window.galleryImagePicker = new MediaPickerClass('cover-image-media-picker', {
                    onSelect: window.handleCoverImageSelection
                });
            } catch (e) {
                console.error('Media Picker initialization failed', e);
            }

            $('#select-featured-image').on('click', function() {
                if (window.galleryImagePicker) {
                    window.galleryImagePicker.open();
                } else {
                    console.error('Gallery image picker not initialized');
                }
            });


            // ─── Success Toast on Page Load ───────────────────────────────────────
            const successEl = $('#success-message');
            if (successEl.length && successEl.data('message')) {
                nepalToast.success('Success', successEl.data('message'));
            }

        });
    </script>
@endpush
