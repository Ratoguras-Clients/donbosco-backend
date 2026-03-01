@extends('layouts.app')

@push('styles')
<script src="https://cdn.jsdelivr.net/npm/quill@2.0.3/dist/quill.js"></script>
<link href="https://cdn.jsdelivr.net/npm/quill@2.0.3/dist/quill.snow.css" rel="stylesheet">
<style>
    .ql-container {
        min-height: 120px;
    }

    .ql-editor {
        min-height: 120px;
    }
</style>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/lightbox2/2.11.5/css/lightbox.css" rel="stylesheet">
<script src="https://cdnjs.cloudflare.com/ajax/libs/lightbox2/2.11.5/js/lightbox.min.js"></script>
@endpush

@section('content')
@include('components.breadcrumb', [
'breadcrumbs' => [
['title' => $organization->name, 'url' => null],
['title' => 'News Create', 'url' => null],
],
])
@if (session('success'))
<div id="success-message" data-message="{{ session('success') }}" class="hidden"></div>
@endif

@if (session('error'))
<div id="error-message" data-message="{{ session('error') }}" class="hidden"></div>
@endif

<div class="bg-white p-6 rounded-lg shadow-sm mb-6">
    <!-- Page Header -->
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-xl font-semibold text-gray-800">Create News</h1>
        <div class="flex space-x-2">
            <a href="{{ route('news.index', $organization->slug)}}"
                class="px-4 py-2 bg-gray-200 dark:bg-gray-900 text-gray-700 rounded-md hover:bg-gray-300 flex items-center">
                <span class="iconify mr-2" data-icon="tabler:arrow-left" data-width="18"></span>
                Back
            </a>
        </div>
    </div>

    <form action="{{ route('news.store', $organization->slug) }}" method="POST" enctype="multipart/form-data">
        @csrf
        <div class="space-y-3">
            <!-- News Title -->
            <div class="md:col-span-2">
                <label for="title" class="block text-sm font-medium text-gray-700 mb-1">
                    Title <span class="text-red-500">*</span>
                </label>
                <div class="relative">
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                        <span class="iconify text-gray-400" data-icon="tabler:pencil" data-width="18"></span>
                    </div>
                    <input type="text" name="title" id="title" value="{{ old('title') }}"
                        placeholder="Enter News title"
                        class="w-full pl-10 pr-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-1 focus:ring-blue-800 focus:border-blue-800">
                </div>
                @error('title')
                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <div>
                    <label for="summary" class="block text-sm font-medium text-gray-700 mb-1">
                        Summary
                    </label>
                    <div class="border border-gray-300 dark:border-gray-500 rounded-lg bg-gray-50 dark:bg-gray-600">
                        <div id="summary_editor" class="min-h-[120px]"></div>
                    </div>

                    <input type="hidden" name="summary" id="summary">
                </div>

                <div>
                    <label for="content" class="block text-sm font-medium text-gray-700 mb-1">
                        Content
                    </label>
                    <div class="border border-gray-300 dark:border-gray-500 rounded-lg bg-gray-50 dark:bg-gray-600">
                        <div id="content_editor" class="min-h-[120px]"></div>
                    </div>

                    <input type="hidden" name="content" id="content">
                </div>
                <div>
                    <label for="published_date"
                        class="block text-sm font-medium text-gray-700 mb-1 dark:text-slate-100">
                        Published Date
                    </label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <span class="iconify text-gray-400" data-icon="tabler:calendar-event"
                                data-width="18"></span>
                        </div>
                        <input type="text" name="published_date" id="published_date"
                            value="{{ old('published_date') }}" placeholder="Select date"
                            class="date w-full pl-10 pr-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-1 focus:ring-blue-800 focus:border-blue-800 dark:text-black cursor-pointer"
                            readonly>
                    </div>
                    @error('published_date')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>
            </div>
        </div>

        <!-- Image Upload Section -->
        <div class="bg-gray-50 dark:bg-gray-700/50 rounded-lg p-4 mb-6">
            <h3 class="text-lg font-medium text-gray-800 mb-4 flex items-center">
                <span class="iconify mr-2" data-icon="tabler:photo" data-width="20"></span>
                Image
            </h3>
            <div class="flex flex-col items-center justify-center p-4 border border-dashed border-gray-300 rounded-md">
                <!-- This hidden input sends the selected image ID to the server -->
                <input type="hidden" name="image" id="image" value="">

                <!-- Preview for validation errors (when form fails) -->
                <div id="featured-image-preview" class="mb-4" style="'display:none;">
                </div>

                <!-- Dynamic preview (when user selects new image) -->
                <div id="image-preview" class="mb-4 w-full"></div>

                <!-- Button to open media picker -->
                <button type="button" id="select-featured-image"
                    class="select-image-btn px-4 py-2 bg-gray-100 dark:bg-gray-700 text-gray-700 dark:text-gray-200 rounded-md border border-gray-300 dark:border-gray-600 flex items-center justify-center hover:bg-gray-200 dark:hover:bg-gray-600 transition">
                    <span class="iconify mr-2" data-icon="tabler:upload" data-width="20"></span>
                    {{ 'Select Image' }}
                </button>

                <!-- Remove button -->
                <button type="button" id="remove-featured-image"
                    class="text-red-600 hover:text-red-800 text-sm mt-2  hidden ">
                    Remove Image
                </button>
            </div>

            <!-- Validation error for image -->
            @error('image')
            <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
            @enderror
        </div>
        <!-- Publish Options -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div class="flex items-center">
                <input type="checkbox" name="is_published" id="is_published" value="1"
                    class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 rounded focus:ring-blue-500 dark:focus:ring-blue-600 dark:ring-offset-gray-800 focus:ring-2 dark:bg-gray-700 dark:border-gray-600">
                <label for="is_published" class="ml-2 block text-sm font-medium text-gray-700">
                    Publish Now
                </label>
            </div>
            <div class="flex items-center">
                <input type="checkbox" name="is_home" id="is_home" value="1"
                    class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 rounded focus:ring-blue-500 dark:focus:ring-blue-600 dark:ring-offset-gray-800 focus:ring-2 dark:bg-gray-700 dark:border-gray-600">
                <label for="is_home" class="ml-2 block text-sm font-medium text-gray-700">
                    Show on Home
                </label>
            </div>
        </div>
</div>

<!-- Form Actions -->
<div class="mt-8 pt-6 border-t border-gray-200 dark:border-gray-700">
    <div class="flex justify-end space-x-3">
        <button type="reset"
            class="px-4 py-2 bg-gray-200 text-gray-700 rounded-md hover:bg-gray-300 flex items-center dark:bg-gray-700 dark:hover:bg-gray-600">
            <span class="iconify mr-2" data-icon="tabler:refresh" data-width="18"></span>
            Reset
        </button>
        <a href="{{ route('news.index', $organization->slug) }}"
            class="px-4 py-2 bg-white border border-gray-300 text-gray-700 rounded-md hover:bg-gray-50 flex items-center dark:bg-gray-800 dark:border-gray-600 dark:hover:bg-gray-700">
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

<!-- Media Picker Component -->
<x-media-piker modalId="featured-image-media-picker" title="Select or Upload Image" :allowMultiple="false"
    :allowUpload="true" :allowView="true" triggerSelector=".select-image-btn" onSelect="handleImageSelection"
    acceptedTypes="image/jpeg,image/jpg,image/png,image/gif,image/webp,image/svg+xml" maxFileSize="10MB" />
@endsection
@push('scripts')
<script>
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


    const summaryQuill = new Quill('#summary_editor', {
        theme: 'snow',
        placeholder: 'Type summary...',
        modules: {
            toolbar: [
                [{
                    header: [1, 2, 3, 4, 5, 6, false]
                }],
                ['bold', 'italic', 'underline'],
                ['link'],
                ['clean']
            ]
        }
    });

    summaryQuill.on('text-change', function() {
        document.getElementById('summary').value = summaryQuill.root.innerHTML;
    });


    const contentQuill = new Quill('#content_editor', {
        theme: 'snow',
        placeholder: 'Type content...',
        modules: {
            toolbar: [
                [{
                    header: [1, 2, 3, 4, 5, 6, false]
                }],
                ['bold', 'italic', 'underline'],
                ['link'],
                ['clean']
            ]
        }
    });

    contentQuill.on('text-change', function() {
        document.getElementById('content').value = contentQuill.root.innerHTML;
    });

 window.handleImageSelection = function(mediaId, picker) {
        if (!mediaId) return;

        let mediaUrl = null;

        // Get media URL from picker
        const modalItem = $(`#${picker.modalId}-grid .media-item[data-media-id="${mediaId}"]`);
        if (modalItem.length) {
            mediaUrl = modalItem.data('media-url');
        }

        if (!mediaUrl) {
            console.error('Media URL not found for ID', mediaId);
            return;
        }

        // Set hidden input
        $('#image').val(mediaId);

        // Update preview
        const fileName = mediaUrl.split('/').pop();
        const ext = fileName.split('.').pop().toLowerCase();
        const isImage = ['jpg','jpeg','png','gif','webp','svg'].includes(ext);

        const previewHtml = `
            <div class="relative bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-600 rounded-lg shadow-sm overflow-hidden">
                <div class="flex items-center justify-between p-3 bg-gray-50 dark:bg-gray-700 border-b border-gray-200 dark:border-gray-600">
                    <div class="flex items-center gap-2">
                        <span class="iconify ${isImage ? 'text-green-500' : 'text-blue-500'}" 
                              data-icon="${isImage ? 'tabler:photo' : 'tabler:file'}" data-width="18"></span>
                        <span class="text-sm font-medium text-gray-700 dark:text-gray-300 truncate max-w-[240px]">${fileName}</span>
                    </div>
                    <button type="button" id="remove-image-inline" 
                            class="text-red-600 dark:text-red-400 hover:text-red-800 text-xs px-2 py-1 rounded">
                        Remove
                    </button>
                </div>
                <div class="flex items-center justify-center p-4 bg-gray-50 dark:bg-gray-700/50 min-h-[120px]">
                    <img src="${mediaUrl}" alt="Preview" class="max-w-full h-48 object-contain rounded-md shadow-sm">
                </div>
            </div>
        `;

        $('#image-preview').html(previewHtml);
        $('#remove-featured-image').removeClass('hidden');
    };

    // -----------------------------
    // 4️⃣ Remove Image Buttons
    // -----------------------------
    $('#remove-featured-image').on('click', function() {
        $('#image').val('');
        $('#image-preview').empty();
        $(this).addClass('hidden');
    });

    $(document).on('click', '#remove-image-inline', function() {
        $('#image').val('');
        $('#image-preview').empty();
        $('#remove-featured-image').addClass('hidden');
    });

    // -----------------------------
    // 5️⃣ Initialize Media Picker
    // -----------------------------
    try {
        window.featuredImagePicker = new MediaPickerClass('featured-image-media-picker', {
            onSelect: window.handleImageSelection
        });
    } catch (e) {
        console.error('Media Picker initialization failed', e);
    }

    $('#select-featured-image').on('click', function() {
        if (window.featuredImagePicker) {
            window.featuredImagePicker.open();
        } else {
            console.error('Featured image picker not initialized');
        }
    });
</script>
@endpush