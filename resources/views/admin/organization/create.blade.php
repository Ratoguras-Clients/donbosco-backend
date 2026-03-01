@extends('layouts.app')

@section('content')
<div class="bg-white p-6 rounded-lg shadow-sm mb-6 dark:bg-slate-800">
    <!-- Page Header -->
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-xl font-semibold text-gray-800 dark:text-white">Create Sister Organization</h1>
        <div class="flex space-x-2">
            <a href="{{ route('dashboard') }}"
                class="px-4 py-2 bg-gray-200 dark:bg-gray-900 text-gray-700 dark:text-white rounded-md hover:bg-gray-300 flex items-center">
                <span class="iconify mr-2" data-icon="tabler:arrow-left" data-width="18"></span>
                Back
            </a>
        </div>
    </div>

    <form action="{{ route('organizations.store') }}" method="POST" enctype="multipart/form-data">
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

                <div class="md:col-span-2">
                    <label for="mission" class="block text-sm font-medium text-gray-700 mb-1 dark:text-slate-100">
                        Organization Mission <span class="text-red-500">*</span>
                    </label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <span class="iconify text-gray-400" data-icon="tabler:building" data-width="18"></span>
                        </div>
                        <input type="text" name="mission" id="mission" value="{{ old('mission') }}"
                            placeholder="Enter organization mission"
                            class="w-full pl-10 pr-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-1 focus:ring-blue-800 focus:border-blue-800 dark:text-black">
                    </div>
                    @error('mission')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

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
            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700 mb-2">Logo</label>
                <div class="flex flex-col items-center justify-center p-4 border border-dashed border-gray-300 rounded-md">
                    <input type="hidden" name="image_1" id="image_1" value="">
                    <div id="image-preview-1" class="mb-4 w-full"></div>

                    <button type="button" class="select-image-btn px-4 py-2 bg-gray-100 dark:bg-gray-700 text-gray-700 dark:text-gray-200 rounded-md border border-gray-300 dark:border-gray-600 flex items-center justify-center hover:bg-gray-200 dark:hover:bg-gray-600 transition" data-image-number="1">
                        <span class="iconify mr-2" data-icon="tabler:upload" data-width="20"></span>
                        Select Logo
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
                <label class="block text-sm font-medium text-gray-700 mb-2">Image </label>
                <div class="flex flex-col items-center justify-center p-4 border border-dashed border-gray-300 rounded-md">
                    <input type="hidden" name="image_2" id="image_2" value="">
                    <div id="image-preview-2" class="mb-4 w-full"></div>

                    <button type="button" class="select-image-btn px-4 py-2 bg-gray-100 dark:bg-gray-700 text-gray-700 dark:text-gray-200 rounded-md border border-gray-300 dark:border-gray-600 flex items-center justify-center hover:bg-gray-200 dark:hover:bg-gray-600 transition" data-image-number="2">
                        <span class="iconify mr-2" data-icon="tabler:upload" data-width="20"></span>
                        Select Image 
                    </button>

                    <button type="button" class="remove-image-btn text-red-600 hover:text-red-800 text-sm mt-2 hidden" data-image-number="2">
                        Remove Image
                    </button>
                </div>
                @error('image_2')
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
                <a href="{{ route('dashboard') }}"
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

        const shortNameInput = $('#short_name')[0]; // Get DOM element
        if (shortNameInput) {
            shortNameInput.value = shortNameInput.value.toUpperCase();

            $(shortNameInput).on('input paste', function() {
                this.value = this.value.toUpperCase();
            });
        }

        const uploadArea = document.getElementById('image-upload-area');
        if (uploadArea) {
            const fileInput = document.createElement('input');
            fileInput.type = 'file';
            fileInput.accept = 'image/*';
            fileInput.classList.add('hidden');
            document.body.appendChild(fileInput);

            const noImage = uploadArea.querySelector('.no-image-state');
            const preview = uploadArea.querySelector('.image-preview-state');
            const img = preview.querySelector('.selected-image-preview');
            const nameDisplay = preview.querySelector('#selected-image-name');

            uploadArea.querySelectorAll('.select-media-btn').forEach(btn => {
                btn.addEventListener('click', () => fileInput.click());
            });

            uploadArea.querySelectorAll('.remove-logo-btn').forEach(btn => {
                btn.addEventListener('click', () => {
                    document.getElementById('logo').value = '';
                    noImage.classList.remove('hidden');
                    preview.classList.add('hidden');
                });
            });

            fileInput.addEventListener('change', function() {
                if (this.files && this.files[0]) {
                    const reader = new FileReader();
                    reader.onload = function(e) {
                        img.src = e.target.result;
                        nameDisplay.textContent = this.files[0].name;
                        noImage.classList.add('hidden');
                        preview.classList.remove('hidden');
                    }.bind(this);
                    reader.readAsDataURL(this.files[0]);
                }
            });
        }
    });
    $('#select-featured-image').on('click', function() {
        if (window.featuredImagePicker) {
            window.featuredImagePicker.open();
        } else {
            console.error('Media picker not initialized');
        }
    });
    $(document).ready(function() {
        // Handle media selection from the picker
        let currentImageNumber = null;

        // 2. Image Selection logic
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

        // 3. Select Image Button Click
        $(document).on('click', '.select-image-btn', function() {
            currentImageNumber = $(this).data('image-number');
            if (window.featuredImagePicker) {
                window.featuredImagePicker.open();
            }
        });

        // 4. Remove Image logic
        $(document).on('click', '.remove-image-btn, .remove-image-inline', function() {
            const imageNumber = $(this).data('image-number');
            $(`#image_${imageNumber}`).val('');
            $(`#image-preview-${imageNumber}`).empty();
            $(`.remove-image-btn[data-image-number="${imageNumber}"]`).addClass('hidden');
        });

        // 5. Media Picker Init
        try {
            window.featuredImagePicker = new MediaPickerClass('featured-image-media-picker', {
                onSelect: window.handleImageSelection
            });
        } catch (e) {
            console.error('Media Picker failed', e);
        }
    });
</script>
@endpush