@extends('layouts.app')

@section('content')
    <div class="bg-white p-6 rounded-lg shadow-sm mb-6 dark:bg-slate-800">
        <!-- Page Header -->
        <div class="flex justify-between items-center mb-6">
            <h1 class="text-xl font-semibold text-gray-800 dark:text-white">Create Other Organization</h1>
            <div class="flex space-x-2">
                <a href="{{ route('otherorganizations.index', $organization->slug) }}"
                    class="px-4 py-2 bg-gray-200 dark:bg-gray-900 text-gray-700 dark:text-white rounded-md hover:bg-gray-300 flex items-center">
                    <span class="iconify mr-2" data-icon="tabler:arrow-left" data-width="18"></span>
                    Back
                </a>
            </div>
        </div>

        <form action="{{ route('otherorganizations.store', $organization->slug) }}" method="POST" enctype="multipart/form-data">
            @csrf

            <div class="mb-8">
                <div class="flex items-center mb-4">
                    <span class="iconify mr-2 text-blue-800" data-icon="tabler:info-circle" data-width="20"></span>
                    <h2 class="text-lg font-medium text-gray-800 dark:text-white">Basic Information</h2>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Organization Name -->
                    <div class="md:col-span-2">
                        <label for="name" class="block text-sm font-medium text-gray-700 mb-1 dark:text-slate-100">
                            Organization Name <span class="text-red-500">*</span>
                        </label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <span class="iconify text-gray-400" data-icon="tabler:building" data-width="18"></span>
                            </div>
                            <input type="text" name="name" id="name" value="{{ old('name') }}"
                                placeholder="Enter organization name"
                                class="w-full pl-10 pr-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-1 focus:ring-blue-800 focus:border-blue-800 dark:text-black">
                        </div>
                        @error('name')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Short Name -->
                    <div>
                        <label for="short_name" class="block text-sm font-medium text-gray-700 mb-1 dark:text-slate-100">
                            Short Name
                        </label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <span class="iconify text-gray-400" data-icon="tabler:signature" data-width="18"></span>
                            </div>
                            <input type="text" name="short_name" id="short_name"
                                value="{{ old('short_name') ? strtoupper(old('short_name')) : '' }}"
                                placeholder="e.g. ABC CORP"
                                class="w-full pl-10 pr-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-1 focus:ring-blue-800 focus:border-blue-800 dark:text-black">
                        </div>
                        @error('short_name')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Established Date -->
                    <div>
                        <label for="established_date"
                            class="block text-sm font-medium text-gray-700 mb-1 dark:text-slate-100">
                            Established Date
                        </label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <span class="iconify text-gray-400" data-icon="tabler:calendar-event"
                                    data-width="18"></span>
                            </div>
                            <input type="text" name="established_date" id="established_date"
                                value="{{ old('established_date') }}" placeholder="Select date"
                                class="date w-full pl-10 pr-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-1 focus:ring-blue-800 focus:border-blue-800 dark:text-black cursor-pointer"
                                readonly>
                        </div>
                        @error('established_date')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Website URL -->
                    <div class="md:col-span-2">
                        <label for="url" class="block text-sm font-medium text-gray-700 mb-1 dark:text-slate-100">
                            Website URL
                        </label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <span class="iconify text-gray-400" data-icon="tabler:world" data-width="18"></span>
                            </div>
                            <input type="url" name="url" id="url" value="{{ old('url') }}"
                                placeholder="https://example.com"
                                class="w-full pl-10 pr-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-1 focus:ring-blue-800 focus:border-blue-800 dark:text-black">
                        </div>
                        @error('url')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <!-- Mission -->
                <div class="mt-6">
                    <label for="mission" class="block text-sm font-medium text-gray-700 mb-1 dark:text-slate-100">
                        Mission
                    </label>
                    <textarea name="mission" id="mission" rows="3" placeholder="Organization mission statement..."
                        class="w-full px-4 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-1 focus:ring-blue-800 focus:border-blue-800 dark:text-black">{{ old('mission') }}</textarea>
                    @error('mission')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Description -->
                <div class="mt-6">
                    <label for="description" class="block text-sm font-medium text-gray-700 mb-1 dark:text-slate-100">
                        Description
                    </label>
                    <textarea name="description" id="description" rows="4" placeholder="Brief description about the organization..."
                        class="w-full px-4 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-1 focus:ring-blue-800 focus:border-blue-800 dark:text-black">{{ old('description') }}</textarea>
                    @error('description')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Featured Media Section -->
                <div class="bg-gray-50 dark:bg-gray-700/50 rounded-lg p-4 mt-6">
                    <h3 class="text-lg font-medium text-gray-800 dark:text-white mb-4 flex items-center">
                        <span class="iconify mr-2" data-icon="tabler:photo" data-width="20"></span>
                        Featured Image
                    </h3>
                    <div
                        class="flex flex-col items-center justify-center p-4 border border-dashed border-gray-300 rounded-md">
                        <input type="hidden" name="image" id="image"
                            value="{{ old('image') }}">

                        <!-- Old preview (for validation errors) -->
                        <div id="featured-image-preview" class="mb-4"
                            style="{{ old('image') ? '' : 'display:none;' }}">
                        </div>

                        <!-- Dynamic preview (new selection) -->
                        <div id="image-preview" class="mb-4 w-full">
                            <!-- Will be filled by JS -->
                        </div>

                        <button type="button" id="select-featured-image"
                            class="btn-secondary select-image-btn px-4 py-2 bg-gray-100 dark:bg-gray-700 text-gray-700 dark:text-gray-200 rounded-md border border-gray-300 dark:border-gray-600 flex items-center justify-center hover:bg-gray-200 dark:hover:bg-gray-600 transition">
                            <span class="iconify mr-2" data-icon="tabler:upload" data-width="20"></span>
                            {{ old('image') ? 'Change Featured Image' : 'Select Featured Image' }}
                        </button>

                        <button type="button" id="remove-featured-image"
                            class="text-red-600 hover:text-red-800 text-sm mt-2 {{ old('image') ? '' : 'hidden' }}">
                            Remove Featured Image
                        </button>
                    </div>

                    @error('image')
                        <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <!-- Form Footer -->
            <div class="mt-8 pt-6 border-t border-gray-200 dark:border-gray-700">
                <div class="flex justify-end space-x-3">
                    <button type="reset"
                        class="px-4 py-2 bg-gray-200 text-gray-700 rounded-md hover:bg-gray-300 flex items-center dark:bg-gray-700 dark:text-white dark:hover:bg-gray-600">
                        <span class="iconify mr-2" data-icon="tabler:refresh" data-width="18"></span>
                        Reset
                    </button>
                    <a href="{{ route('otherorganizations.index', $organization->slug) }}"
                        class="px-4 py-2 bg-white border border-gray-300 text-gray-700 rounded-md hover:bg-gray-50 flex items-center dark:bg-gray-800 dark:border-gray-600 dark:text-white dark:hover:bg-gray-700">
                        <span class="iconify mr-2" data-icon="tabler:x" data-width="18"></span>
                        Cancel
                    </a>
                    <button type="submit"
                        class="px-6 py-2 bg-blue-800 text-white rounded-md hover:bg-blue-900 flex items-center">
                        <span class="iconify mr-2" data-icon="tabler:building-plus" data-width="18"></span>
                        Create
                    </button>
                </div>
            </div>
        </form>
    </div>

    <x-media-piker modalId="featured-image-media-picker" title="Select or Upload Featured Image" :allowMultiple="false"
        :allowUpload="true" :allowView="true" triggerSelector=".select-image-btn" onSelect="handleImageSelection"
        acceptedTypes="image/jpeg,image/jpg,image/png,image/gif,image/webp,image/svg+xml" maxFileSize="10MB" />
@endsection

@push('scripts')
    <script>
        $(document).ready(function() {

            function initDatePickers() {
                $('.date').not('.daterangepicker-initialized').each(function() {
                    const $el = $(this);
                    const cfg = {
                        singleDatePicker: true,
                        autoApply: true,
                        locale: {
                            format: 'YYYY-MM-DD'
                        }
                    };
                    if ($el.val()) cfg.startDate = moment($el.val(), 'YYYY-MM-DD');
                    $el.daterangepicker(cfg).addClass('daterangepicker-initialized');
                });
            }

            initDatePickers();

            const shortNameInput = $('#short_name')[0];
            if (shortNameInput) {
                shortNameInput.value = shortNameInput.value.toUpperCase();

                $(shortNameInput).on('input paste', function() {
                    this.value = this.value.toUpperCase();
                });
            }

            $('#select-featured-image').on('click', function() {
                if (window.featuredImagePicker) {
                    window.featuredImagePicker.open();
                } else {
                    console.error('Media picker not initialized');
                }
            });

            // Handle media selection from the picker
            window.handleFeaturedImageSelect = function(mediaId, picker) {
                if (!mediaId) return;

                let mediaUrl = null;

                // Try to get URL from current modal
                const modalItem = $(`#${picker.modalId}-grid .media-item[data-media-id="${mediaId}"]`);
                if (modalItem.length) {
                    mediaUrl = modalItem.data('media-url');
                }

                // Fallback: search anywhere on page
                if (!mediaUrl) {
                    const anyItem = $(`.media-item[data-media-id="${mediaId}"]`).first();
                    if (anyItem.length) mediaUrl = anyItem.data('media-url');
                }

                if (!mediaUrl) {
                    console.error('Featured Image: Media URL not found for ID', mediaId);
                    return;
                }

                $('#image').val(mediaId);
                updateFeaturedImagePreview(mediaUrl);
            };

            // Update preview with rich card design
            function updateFeaturedImagePreview(url) {
                const fileName = url.split('/').pop();
                const ext = fileName.split('.').pop().toLowerCase();
                const isImage = ['jpg', 'jpeg', 'png', 'gif', 'webp', 'svg'].includes(ext);

                $('#featured-image-preview').hide();

                const previewHtml = `
                    <div class="relative bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-600 rounded-lg shadow-sm overflow-hidden w-full">
                        <!-- Header -->
                        <div class="flex items-center justify-between p-3 bg-gray-50 dark:bg-gray-700 border-b border-gray-200 dark:border-gray-600">
                            <div class="flex items-center gap-2">
                                <span class="iconify ${isImage ? 'text-green-500' : 'text-blue-500'}" data-icon="${isImage ? 'tabler:photo' : 'tabler:file'}" data-width="18"></span>
                                <span class="text-sm font-medium text-gray-700 dark:text-gray-300 truncate max-w-[200px]">${fileName}</span>
                            </div>
                            <button type="button" id="remove-featured-image-inline"
                                    class="flex items-center gap-1 px-2 py-1 text-red-600 hover:text-red-800 dark:text-red-400 dark:hover:text-red-300 text-xs bg-red-50 dark:bg-red-900/20 rounded transition-colors">
                                <span class="iconify" data-icon="tabler:trash" data-width="14"></span>
                                Remove
                            </button>
                        </div>

                        <!-- Content -->
                        <div class="flex items-center justify-center p-4 bg-gray-50 dark:bg-gray-700/50">
                            ${isImage
                                ? `<img src="${url}" alt="Featured Image Preview" class="max-w-full h-48 object-contain rounded-md shadow-sm border border-gray-200 dark:border-gray-600">`
                                : `<span class="iconify text-gray-400" data-icon="tabler:file-text" data-width="64"></span>`
                            }
                        </div>

                        <!-- Footer -->
                        ${isImage ? `
                            <div class="p-3 bg-gray-50 dark:bg-gray-700 border-t border-gray-200 dark:border-gray-600">
                                <div class="flex items-center justify-between text-xs text-gray-500 dark:text-gray-400">
                                    <span class="flex items-center gap-1">
                                        <span class="iconify" data-icon="tabler:check-circle" data-width="12"></span>
                                        Image selected successfully
                                    </span>
                                    <span class="uppercase font-mono bg-gray-200 dark:bg-gray-600 px-2 py-1 rounded">${ext}</span>
                                </div>
                            </div>` : ''}
                    </div>
                `;

                $('#image-preview').html(previewHtml);
                $('#remove-featured-image').removeClass('hidden');
                $('#select-featured-image').text('Change Featured Image');
            }

            // Clear featured image
            function clearFeaturedImage() {
                $('#image').val('');
                $('#image-preview').empty();
                $('#featured-image-preview').hide();
                $('#remove-featured-image').addClass('hidden');
                $('#select-featured-image').text('Select Featured Image');
            }

            $('#remove-featured-image').on('click', clearFeaturedImage);
            $(document).on('click', '#remove-featured-image-inline', clearFeaturedImage);

            // Initialize Media Picker
            try {
                window.featuredImagePicker = new MediaPickerClass('featured-image-media-picker', {
                    onSelect: window.handleFeaturedImageSelect
                });
            } catch (e) {
                console.error('Featured Image Picker init failed', e);
            }
        });
    </script>
@endpush