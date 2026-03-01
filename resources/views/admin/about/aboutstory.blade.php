@extends('layouts.app')

@push('styles')
    <script src="https://cdn.jsdelivr.net/npm/quill@2.0.3/dist/quill.js"></script>
    <link href="https://cdn.jsdelivr.net/npm/quill@2.0.3/dist/quill.snow.css" rel="stylesheet">
    <style>
        .ql-container,
        .ql-editor {
            min-height: 120px;
        }

        /* Custom check icon for bullet lists in features editor */
        #features_editor .ql-editor ul li::before {
            content: '';
            display: inline-block;
            width: 18px;
            height: 18px;
            margin-right: 8px;
            margin-left: -26px;
            background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='18' height='18' viewBox='0 0 24 24' fill='none' stroke='%234ade80' stroke-width='2' stroke-linecap='round' stroke-linejoin='round'%3E%3Ccircle cx='12' cy='12' r='10'/%3E%3Cpath d='m9 12 2 2 4-4'/%3E%3C/svg%3E");
            background-size: contain;
            background-repeat: no-repeat;
            flex-shrink: 0;
            vertical-align: middle;
        }

        #features_editor .ql-editor ul li {
            list-style: none;
            padding-left: 26px;
        }

        #features_editor .ql-editor ul {
            padding-left: 0;
        }
    </style>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/lightbox2/2.11.5/css/lightbox.css">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/lightbox2/2.11.5/js/lightbox.min.js"></script>
@endpush

@section('content')
    @include('components.breadcrumb', [
        'breadcrumbs' => [
            ['title' => $organization->name, 'url' => null],
            ['title' => 'Create Our Story', 'url' => null],
        ],
    ])


    <div class="bg-white p-6 rounded-lg shadow-sm mb-6">
        <div class="flex justify-between items-center mb-6">
            <h1 class="text-xl font-semibold text-gray-800">Create Our Story</h1>
            <div class="flex space-x-2">
                <a href="{{ url()->previous() }}"
                    class="px-4 py-2 bg-gray-200 dark:bg-gray-900 text-gray-700 rounded-md hover:bg-gray-300 flex items-center">
                    <span class="iconify mr-2" data-icon="tabler:arrow-left" data-width="18"></span>
                    Back
                </a>
            </div>
        </div>

        <form action="{{ route('aboutstory.store', $organization->slug) }}" method="POST" enctype="multipart/form-data">
            @csrf
            <div class="space-y-3">
                <div class="md:col-span-2">
                    <label for="title" class="block text-sm font-medium text-gray-700 mb-1">
                        Title <span class="text-red-500">*</span>
                    </label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <span class="iconify text-gray-400" data-icon="tabler:pencil" data-width="18"></span>
                        </div>
                        <input type="text" name="title" id="title" value="{{ old('title') }}"
                            placeholder="Enter title"
                            class="w-full pl-10 pr-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-1 focus:ring-blue-800 focus:border-blue-800">
                    </div>
                    @error('title')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div class="grid grid-cols-1 gap-6">
                    <div>
                        <label for="content" class="block text-sm font-medium text-gray-700 mb-1">Content</label>
                        <div class="border border-gray-300 dark:border-gray-500 rounded-lg bg-gray-50 dark:bg-gray-600">
                            <div id="content_editor" class="min-h-[200px]"></div>
                        </div>
                        <input type="hidden" name="content" id="content">
                    </div>
                </div>

                <div class="grid grid-cols-1 gap-6">
                    <div>
                        <label for="features" class="block text-sm font-medium text-gray-700 mb-1">Features (List Format)</label>
                        <div class="border border-gray-300 dark:border-gray-500 rounded-lg bg-gray-50 dark:bg-gray-600">
                            <div id="features_editor" class="min-h-[200px]"></div>
                        </div>
                        <input type="hidden" name="features" id="features">
                        <p class="text-xs text-gray-500 mt-1">Use bullet points or numbered lists to organize features</p>
                    </div>
                </div>
            </div>

            <div class="bg-gray-50 dark:bg-gray-700/50 rounded-lg p-4 my-6">
                <h3 class="text-lg font-medium text-gray-800 mb-4 flex items-center">
                    <span class="iconify mr-2" data-icon="tabler:photo" data-width="20"></span>
                    Images (Upload up to 3 images)
                </h3>
                
                <!-- Image 1 -->
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Image 1</label>
                    <div class="flex flex-col items-center justify-center p-4 border border-dashed border-gray-300 rounded-md">
                        <input type="hidden" name="image_1" id="image_1" value="">
                        <div id="image-preview-1" class="mb-4 w-full"></div>

                        <button type="button" class="select-image-btn px-4 py-2 bg-gray-100 dark:bg-gray-700 text-gray-700 dark:text-gray-200 rounded-md border border-gray-300 dark:border-gray-600 flex items-center justify-center hover:bg-gray-200 dark:hover:bg-gray-600 transition" data-image-number="1">
                            <span class="iconify mr-2" data-icon="tabler:upload" data-width="20"></span>
                            Select Image 1
                        </button>

                        <button type="button" class="remove-image-btn text-red-600 hover:text-red-800 text-sm mt-2 hidden" data-image-number="1">
                            Remove Image
                        </button>
                    </div>
                    @error('image_1')
                        <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Image 2 -->
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Image 2</label>
                    <div class="flex flex-col items-center justify-center p-4 border border-dashed border-gray-300 rounded-md">
                        <input type="hidden" name="image_2" id="image_2" value="">
                        <div id="image-preview-2" class="mb-4 w-full"></div>

                        <button type="button" class="select-image-btn px-4 py-2 bg-gray-100 dark:bg-gray-700 text-gray-700 dark:text-gray-200 rounded-md border border-gray-300 dark:border-gray-600 flex items-center justify-center hover:bg-gray-200 dark:hover:bg-gray-600 transition" data-image-number="2">
                            <span class="iconify mr-2" data-icon="tabler:upload" data-width="20"></span>
                            Select Image 2
                        </button>

                        <button type="button" class="remove-image-btn text-red-600 hover:text-red-800 text-sm mt-2 hidden" data-image-number="2">
                            Remove Image
                        </button>
                    </div>
                    @error('image_2')
                        <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Image 3 -->
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Image 3</label>
                    <div class="flex flex-col items-center justify-center p-4 border border-dashed border-gray-300 rounded-md">
                        <input type="hidden" name="image_3" id="image_3" value="">
                        <div id="image-preview-3" class="mb-4 w-full"></div>

                        <button type="button" class="select-image-btn px-4 py-2 bg-gray-100 dark:bg-gray-700 text-gray-700 dark:text-gray-200 rounded-md border border-gray-300 dark:border-gray-600 flex items-center justify-center hover:bg-gray-200 dark:hover:bg-gray-600 transition" data-image-number="3">
                            <span class="iconify mr-2" data-icon="tabler:upload" data-width="20"></span>
                            Select Image 3
                        </button>

                        <button type="button" class="remove-image-btn text-red-600 hover:text-red-800 text-sm mt-2 hidden" data-image-number="3">
                            Remove Image
                        </button>
                    </div>
                    @error('image_3')
                        <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <div class="flex items-center">
                <input type="checkbox" name="is_home" id="is_home" value="1"
                    class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 rounded focus:ring-blue-500">
                <label for="is_home" class="ml-2 block text-sm font-medium text-gray-700">Show on Home</label>
            </div>

            <div class="mt-8 pt-6 border-t border-gray-200 dark:border-gray-700">
                <div class="flex justify-end space-x-3">
                    <button type="reset"
                        class="px-4 py-2 bg-gray-200 text-gray-700 rounded-md hover:bg-gray-300 flex items-center dark:bg-gray-700 dark:hover:bg-gray-600">
                        <span class="iconify mr-2" data-icon="tabler:refresh" data-width="18"></span> Reset
                    </button>
                    <a href="{{ route('dashboard') }}"
                        class="px-4 py-2 bg-white border border-gray-300 text-gray-700 rounded-md hover:bg-gray-50 flex items-center dark:bg-gray-800 dark:border-gray-600">
                        <span class="iconify mr-2" data-icon="tabler:x" data-width="18"></span> Cancel
                    </a>
                    <button type="submit"
                        class="px-6 py-2 bg-blue-800 text-white rounded-md hover:bg-blue-900 flex items-center">
                        <span class="iconify mr-2" data-icon="tabler:building-plus" data-width="18"></span> Create
                    </button>
                </div>
            </div>
        </form>
    </div>

    <x-media-piker modalId="featured-image-media-picker" title="Select or Upload Image" :allowMultiple="false" :allowUpload="true"
        :allowView="true" triggerSelector=".select-image-btn" onSelect="handleImageSelection"
        acceptedTypes="image/jpeg,image/jpg,image/png,image/gif,image/webp,image/svg+xml" maxFileSize="10MB" />
@endsection

@push('scripts')
    <script>
        // 1. Content Quill Editor
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

        // 2. Features Quill Editor with List Support
        const featuresQuill = new Quill('#features_editor', {
            theme: 'snow',
            placeholder: 'Type features in list format...',
            modules: {
                toolbar: [
                    ['bold', 'italic', 'underline'],
                    [{ 'list': 'ordered'}, { 'list': 'bullet' }],
                    ['clean']
                ]
            }
        });

        featuresQuill.on('text-change', function() {
            document.getElementById('features').value = featuresQuill.root.innerHTML;
        });

        // Track current image being selected
        let currentImageNumber = null;

        // 3. Image Selection logic
        window.handleImageSelection = function(mediaId, picker) {
            if (!mediaId || !currentImageNumber) return;
            
            let mediaUrl = $(`#${picker.modalId}-grid .media-item[data-media-id="${mediaId}"]`).data('media-url');
            if (!mediaUrl) return;

            $(`#image_${currentImageNumber}`).val(mediaId);
            const fileName = mediaUrl.split('/').pop();

            const previewHtml = `
            <div class="relative bg-white dark:bg-gray-800 border border-gray-200 rounded-lg shadow-sm overflow-hidden">
                <div class="flex items-center justify-between p-3 bg-gray-50 dark:bg-gray-700 border-b">
                    <span class="text-sm font-medium truncate max-w-[240px]">${fileName}</span>
                    <button type="button" class="remove-image-inline text-red-600 text-xs px-2 py-1" data-image-number="${currentImageNumber}">Remove</button>
                </div>
                <div class="flex items-center justify-center p-4 min-h-[120px]">
                    <img src="${mediaUrl}" alt="Preview" class="max-w-full h-48 object-contain">
                </div>
            </div>`;

            $(`#image-preview-${currentImageNumber}`).html(previewHtml);
            $(`.remove-image-btn[data-image-number="${currentImageNumber}"]`).removeClass('hidden');
        };

        // 4. Select Image Button Click
        $(document).on('click', '.select-image-btn', function() {
            currentImageNumber = $(this).data('image-number');
            if (window.featuredImagePicker) {
                window.featuredImagePicker.open();
            }
        });

        // 5. Remove Image logic
        $(document).on('click', '.remove-image-btn, .remove-image-inline', function() {
            const imageNumber = $(this).data('image-number');
            $(`#image_${imageNumber}`).val('');
            $(`#image-preview-${imageNumber}`).empty();
            $(`.remove-image-btn[data-image-number="${imageNumber}"]`).addClass('hidden');
        });

        // 6. Media Picker Init
        try {
            window.featuredImagePicker = new MediaPickerClass('featured-image-media-picker', {
                onSelect: window.handleImageSelection
            });
        } catch (e) {
            console.error('Media Picker failed', e);
        }
    </script>
@endpush