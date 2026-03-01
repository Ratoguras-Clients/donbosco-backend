@extends('layouts.app')

@section('content')
@include('components.breadcrumb', [
'breadcrumbs' => [
['title' => $organization->name, 'url' => null],
['title' => 'Edit Notice', 'url' => null],
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
        <h1 class="text-xl font-semibold text-gray-800">Edit Notices</h1>
        <div class="flex space-x-2">
            <a href="{{ route('notices.index', $organization->slug) }}"
                class="px-4 py-2 bg-gray-200 dark:bg-gray-900 text-gray-700 rounded-md hover:bg-gray-300 flex items-center">
                <span class="iconify mr-2" data-icon="tabler:arrow-left" data-width="18"></span>
                Back
            </a>
        </div>
    </div>

    <form action="{{ route('notices.update', ['slug' => $organization->slug, 'id' => $notice->id]) }}" method="POST"
        enctype="multipart/form-data">
        @csrf
        @method('PUT')

        <div class="space-y-6">
            <!-- Title -->
            <div>
                <label for="title" class="block text-sm font-medium text-gray-700 mb-1">
                    Title <span class="text-red-500">*</span>
                </label>
                <div class="relative">
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                        <span class="iconify text-gray-400" data-icon="tabler:pencil" data-width="18"></span>
                    </div>
                    <input type="text" name="title" id="title" value="{{ old('title', $notice->title) }}"
                        placeholder="Enter event title"
                        class="w-full pl-10 pr-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-1 focus:ring-blue-800 focus:border-blue-800">
                </div>
                @error('title')
                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- Description -->
            <div>
                <label for="description" class="block text-sm font-medium text-gray-700 mb-1">
                    Description
                </label>
                <div class="border border-gray-300 dark:border-gray-500 rounded-lg bg-gray-50 dark:bg-gray-600">
                    <div id="description_editor" class="min-h-[120px]"></div>
                </div>

                <input type="hidden" name="description" id="description">
                @error('description')
                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- Date Row -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label for="notice_date" class="block text-sm font-medium text-gray-700 mb-1">
                        Notice Date <span class="text-red-500">*</span>
                    </label>
                    <input type="date" name="notice_date" id="notice_date"
                        value="{{ old('notice_date', optional($notice->notice_date)->format('Y-m-d')) }}"
                        class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-1 focus:ring-blue-800 focus:border-blue-800">
                    @error('notice_date')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>
                <div>
                    <label for="priority" class="block text-sm font-medium text-gray-700 mb-1">
                        Priority <span class="text-red-500">*</span>
                    </label>
                    <select name="priority" id="priority"
                        class="form-input h-10 w-full px-3 border border-gray-300 rounded-md">
                        <option value="">Select priority level</option>
                        <option value="low" {{ old('priority', $notice->priority) == 'low' ? 'selected' : '' }}>Low
                        </option>
                        <option value="medium" {{ old('priority', $notice->priority) == 'medium' ? 'selected' : '' }}>
                            Medium</option>
                        <option value="high" {{ old('priority', $notice->priority) == 'high' ? 'selected' : '' }}>
                            High
                        </option>
                    </select>
                    @error('priority')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <!-- Active -->
            <div class="flex items-center pb-3">
                <input type="checkbox" name="is_published" value="1"
                    {{ old('is_published', $notice->is_published) ? 'checked' : '' }}
                    class="w-4 h-4 text-blue-600 border-gray-300 rounded">
                <label class="ml-2 text-sm text-gray-700">
                    Published
                </label>
            </div>

            <!-- Cover Image Upload -->
            <div class="bg-gray-50 dark:bg-gray-700/50 rounded-lg p-4 mb-6">
                <h3 class="text-lg font-medium text-gray-800 mb-4 flex items-center">
                    <span class="iconify mr-2" data-icon="tabler:photo" data-width="20"></span>
                    Attachment
                </h3>
                <div
                    class="flex flex-col items-center justify-center p-4 border border-dashed border-gray-300 rounded-md">
                    <!-- Hidden input for media ID -->
                    <input type="hidden" name="image" id="image" value="{{ $notice->attachment }}">

                    <!-- Existing image preview -->
                    @if ($notice->media)
                    <div id="featured-image-preview" class="mb-4 text-center">
                        <img src="{{ $notice->media->url }}" alt="Current image"
                            class="max-w-xs max-h-48 object-cover rounded-lg mx-auto shadow-md">
                        <p class="text-sm text-gray-600 mt-2">Current image</p>
                    </div>
                    @endif

                    <!-- Dynamic preview for new image -->
                    <div id="image-preview" class="mb-4 w-full"></div>

                    <!-- Button to open media picker -->
                    <button type="button" id="select-featured-image"
                        class="select-image-btn px-4 py-2 bg-gray-100 dark:bg-gray-700 text-gray-700 dark:text-gray-200 rounded-md border border-gray-300 dark:border-gray-600 flex items-center justify-center hover:bg-gray-200 dark:hover:bg-gray-600 transition">
                        <span class="iconify mr-2" data-icon="tabler:upload" data-width="20"></span>
                        {{ $notice->media ? 'Change Image' : 'Select Image' }}
                    </button>

                    <!-- Remove button (show if existing image) -->
                    <button
                        type="button"
                        id="remove-featured-image"
                        class="text-red-600 hover:text-red-800 text-sm mt-2 flex items-center {{ $notice->media ? '' : 'hidden' }}">
                        <span class="iconify mr-2" data-icon="tabler:trash" data-width="16"></span>
                        Remove Image
                    </button>

                </div>

                @error('image')
                <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                @enderror
            </div>

            <!-- Form Actions -->
            <div class="mt-8 pt-6 border-t border-gray-200 dark:border-gray-700">
                <div class="flex justify-end space-x-3">
                    <button type="reset"
                        class="px-4 py-2 bg-gray-200 text-gray-700 rounded-md hover:bg-gray-300 flex items-center dark:bg-gray-700 dark:hover:bg-gray-600">
                        <span class="iconify mr-2" data-icon="tabler:refresh" data-width="18"></span>
                        Reset
                    </button>
                    <a href="{{ route('notices.index', $organization->slug) }}"
                        class="px-4 py-2 bg-white border border-gray-300 text-gray-700 rounded-md hover:bg-gray-50 flex items-center dark:bg-gray-800 dark:border-gray-600 dark:hover:bg-gray-700">
                        <span class="iconify mr-2" data-icon="tabler:x" data-width="18"></span>
                        Cancel
                    </a>
                    <button type="submit"
                        class="px-6 py-2 bg-blue-800 text-white rounded-md hover:bg-blue-900 flex items-center">
                        <span class="iconify mr-2" data-icon="tabler:calendar-plus" data-width="18"></span>
                        Update Notice
                    </button>
                </div>
            </div>
    </form>
</div>

<!-- Media Picker Modal -->
<x-media-piker modalId="featured-image-media-picker" title="Select or Upload Cover Image" :allowMultiple="false"
    :allowUpload="true" :allowView="true" triggerSelector=".select-image-btn" onSelect="handleImageSelection"
    acceptedTypes="image/jpeg,image/jpg,image/png,image/gif,image/webp,image/svg+xml" maxFileSize="10MB" />
@endsection

@push('scripts')
<script>
    
    $(document).ready(function() {
        const prioritySelect = $('#priority');

        // Initialize Select2
        prioritySelect.select2({
            placeholder: "Select priority level",
            allowClear: true,
            minimumResultsForSearch: Infinity,
            width: '100%'
        });


        function applyPriorityColor() {
            const val = prioritySelect.val() || '';
            const container = prioritySelect.next('.select2-container');


            container.removeClass('priority-low priority-medium priority-high');


            if (val === 'low') {
                container.addClass('priority-low');
            } else if (val === 'medium') {
                container.addClass('priority-medium');
            } else if (val === 'high') {
                container.addClass('priority-high');
            }
        }


        applyPriorityColor();


        prioritySelect.on('change', applyPriorityColor);


        prioritySelect.on('select2:select', applyPriorityColor);


        prioritySelect.trigger('change');
    });

    const descriptionQuill = new Quill('#description_editor', {
        theme: 'snow',
        placeholder: 'Type description...',
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
    descriptionQuill.root.innerHTML = `{!! addslashes($notice->description) !!}`;
    document.getElementById('description').value = descriptionQuill.root.innerHTML;
    descriptionQuill.on('text-change', function() {
        document.getElementById('description').value = descriptionQuill.root.innerHTML;
    });

     document.addEventListener('DOMContentLoaded', function() {

        const imageInput = document.getElementById('image');
        const preview = document.getElementById('image-preview');
        const existingPreview = document.getElementById('featured-image-preview');
        const removeBtn = document.getElementById('remove-featured-image');
        const selectBtn = document.getElementById('select-featured-image');

        // ===== IMAGE SELECT =====
        window.handleImageSelection = function(mediaId, picker) {
    if (!mediaId) return;

    let mediaUrl = null;
    const modalItem = $(`#${picker.modalId}-grid .media-item[data-media-id="${mediaId}"]`);
    if (modalItem.length) {
        mediaUrl = modalItem.data('media-url');
    }

    if (!mediaUrl) {
        console.error('Media URL not found for ID', mediaId);
        return;
    }

    // Set hidden input
    imageInput.value = mediaId;

    // Update preview
    preview.innerHTML = `
        <div class="text-center">
            <img src="${mediaUrl}" class="max-w-xs max-h-48 object-cover rounded-lg mx-auto shadow-md">
            <p class="text-sm text-gray-600 mt-2">Selected image</p>
        </div>
    `;

    // Hide old image preview
    if (existingPreview) existingPreview.style.display = 'none';

    // Show remove button
    removeBtn.classList.remove('hidden');

    // Update button text
    selectBtn.innerHTML =
        '<span class="iconify mr-2" data-icon="tabler:upload" data-width="20"></span>Change Image';
};


        // ===== IMAGE REMOVE =====
        removeBtn.addEventListener('click', function() {
            imageInput.value = '';
            preview.innerHTML = '';

            if (existingPreview) existingPreview.style.display = 'none';

            this.classList.add('hidden');

            selectBtn.innerHTML =
                '<span class="iconify mr-2" data-icon="tabler:upload" data-width="20"></span>Select Image';
        });

    });
</script>
@endpush
@push('styles')
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />

<style>
    /* Make Select2 same height as normal input (h-10 = 40px) */
    .select2-container--default .select2-selection--single {
        height: 2.5rem !important;
        /* 40px */
        border: 1px solid #d1d5db !important;
        border-radius: 0.375rem !important;
    }

    .select2-container--default .select2-selection--single .select2-selection__rendered {
        line-height: 2.5rem !important;
        /* vertically center text */
        padding-left: 0.75rem !important;
        /* px-3 */
        padding-right: 2rem !important;
        /* space for arrow */
        color: #111827 !important;
    }

    /* Arrow container height */
    .select2-container--default .select2-selection--single .select2-selection__arrow {
        height: 2.5rem !important;
    }

    /* Your priority colors */
    .select2-container.priority-low .select2-selection--single {
        border-left: 4px solid #6b7280 !important;
        background-color: #f3f4f6 !important;
    }

    .select2-container.priority-medium .select2-selection--single {
        border-left: 4px solid #f59e0b !important;
        background-color: #fffbeb !important;
    }

    .select2-container.priority-high .select2-selection--single {
        border-left: 4px solid #dc2626 !important;
        background-color: #fee2e2 !important;
    }
</style>
@endpush