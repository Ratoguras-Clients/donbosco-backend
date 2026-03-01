@extends('layouts.app')

@section('content')
@include('components.breadcrumb', [
'breadcrumbs' => [
['title' => $organization->name, 'url' => null],
['title' => 'Create Blog', 'url' => null],
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
        <h1 class="text-xl font-semibold text-gray-800">Create Blog</h1>
        <div class="flex space-x-2">
            <a href="{{ route('blog.index', $organization->slug) }}"
                class="px-4 py-2 bg-gray-200 dark:bg-gray-900 text-gray-700 rounded-md hover:bg-gray-300 flex items-center">
                <span class="iconify mr-2" data-icon="tabler:arrow-left" data-width="18"></span>
                Back
            </a>
        </div>
    </div>

    <form action="{{ route('blog.store', $organization->slug) }}" method="POST" enctype="multipart/form-data">
        @csrf

        <div class="space-y-6">
            <!-- blog Title -->
            <div>
                <label for="title" class="block text-sm font-medium text-gray-700 mb-1">
                    Title <span class="text-red-500">*</span>
                </label>
                <div class="relative">
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-blog-none">
                        <span class="iconify text-gray-400" data-icon="tabler:pencil" data-width="18"></span>
                    </div>
                    <input type="text" name="title" id="title" value="{{ old('title') }}"
                        placeholder="Enter blog title"
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



            <!-- Date and name Row -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <div>
                    <label for="name" class="block text-sm font-medium text-gray-700 mb-1">
                        Author Name <span class="text-red-500">*</span>
                    </label>
                    <input type="text" name="name" id="name" value="{{ old('name') }}"
                        placeholder="Author Name"
                        class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-1 focus:ring-blue-800 focus:border-blue-800">
                    @error('name')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="start_date" class="block text-sm font-medium text-gray-700 mb-1 dark:text-slate-100">
                        Start Date <span class="text-red-500">*</span>
                    </label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <span class="iconify text-gray-400" data-icon="tabler:calendar" data-width="18"></span>
                        </div>
                        <input type="text" name="start_date" id="start_date" value="{{ old('start_date') }}" required
                            class="date w-full pl-10 pr-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-1 focus:ring-blue-800 focus:border-blue-800 ">
                    </div>
                    @error('start_date')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>




            </div>

            <!-- Flags -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div class="flex items-center">
                    <input type="checkbox" name="is_published" id="is_published" value="1"
                        {{ old('is_published',1) ? 'checked' : '' }}
                        class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 rounded focus:ring-blue-500">
                    <label for="is_published" class="ml-2 text-sm font-medium text-gray-700">
                        Publish immediately
                    </label>
                </div>

                <div class="flex items-center">
                    <input type="checkbox" name="is_home" id="is_home" value="1"
                        {{ old('is_home',1) ? 'checked' : '' }}
                        class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 rounded focus:ring-blue-500">
                    <label for="is_home" class="ml-2 text-sm font-medium text-gray-700">
                        Feature on homepage
                    </label>
                </div>
            </div>

            <!-- Cover Image Upload -->
            <div class="bg-gray-50 dark:bg-gray-700/50 rounded-lg p-4 mb-6">
                <h3 class="text-lg font-medium text-gray-800 mb-4 flex items-center">
                    <span class="iconify mr-2" data-icon="tabler:photo" data-width="20"></span>
                    Cover Image
                </h3>

                <div
                    class="flex flex-col items-center justify-center p-4 border border-dashed border-gray-300 rounded-md">
                    <!-- Hidden input to store selected media ID -->
                    <input type="hidden" name="media_id" id="media_id" value="{{ old('media_id') }}">

                    <!-- Preview for old input (validation fail) -->
                    <div id="featured-image-preview" class="mb-4">
                        @if (old('media_id'))
                        <img src="{{ \App\Models\Website\Media::find(old('media_id'))?->url ?? '' }}"
                            alt="Current cover" class="max-h-64 rounded-md shadow">
                        @endif
                    </div>

                    <!-- Dynamic preview -->
                    <div id="image-preview" class="mb-4 w-full max-w-md"></div>

                    <button type="button" id="select-featured-image"
                        class="select-image-btn px-4 py-2 bg-gray-100 dark:bg-gray-700 text-gray-700 dark:text-gray-200 rounded-md border border-gray-300 dark:border-gray-600 flex items-center justify-center hover:bg-gray-200 dark:hover:bg-gray-600 transition">
                        <span class="iconify mr-2" data-icon="tabler:upload" data-width="20"></span>
                        Select Cover Image
                    </button>

                    <button type="button" id="remove-featured-image"
                        class="text-red-600 hover:text-red-800 text-sm mt-3 hidden">
                        Remove Image
                    </button>
                </div>

                @error('media_id')
                <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                @enderror
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
                <a href="{{ route('blog.index', $organization->slug) }}"
                    class="px-4 py-2 bg-white border border-gray-300 text-gray-700 rounded-md hover:bg-gray-50 flex items-center dark:bg-gray-800 dark:border-gray-600 dark:hover:bg-gray-700">
                    <span class="iconify mr-2" data-icon="tabler:x" data-width="18"></span>
                    Cancel
                </a>
                <button type="submit"
                    class="px-6 py-2 bg-blue-800 text-white rounded-md hover:bg-blue-900 flex items-center">
                    <span class="iconify mr-2" data-icon="tabler:calendar-plus" data-width="18"></span>
                    Create blog
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
    window.handleImageSelection = function(mediaId, picker) {
        if (!mediaId) return;

        // Find the selected media element inside the picker modal
        const modalItem = document.querySelector(`#${picker.modalId}-grid .media-item[data-media-id="${mediaId}"]`);
        if (!modalItem) return;

        const mediaUrl = modalItem.dataset.mediaUrl;

        // Update hidden input
        document.getElementById('media_id').value = mediaId;

        // Update preview
        document.getElementById('image-preview').innerHTML = `
        <div class="relative bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-600 rounded-lg shadow-sm overflow-hidden">
            <div class="flex items-center justify-between p-3 bg-gray-50 dark:bg-gray-700 border-b border-gray-200 dark:border-gray-600">
                <div class="flex items-center gap-2">
                    <span class="iconify text-green-500" data-icon="tabler:photo" data-width="18"></span>
                    <span class="text-sm font-medium text-gray-700 dark:text-gray-300 truncate max-w-[240px]">
                        ${mediaUrl.split('/').pop()}
                    </span>
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

        document.getElementById('remove-featured-image').classList.remove('hidden');
    };



    // Main remove button
    document.getElementById('remove-featured-image').addEventListener('click', function() {
        document.getElementById('media_id').value = '';
        document.getElementById('image-preview').innerHTML = '';
        this.classList.add('hidden');
    });

    // Inline remove button inside preview
    document.addEventListener('click', function(e) {
        if (e.target && e.target.id === 'remove-image-inline') {
            document.getElementById('media_id').value = '';
            document.getElementById('image-preview').innerHTML = '';
            document.getElementById('remove-featured-image').classList.add('hidden');
        }
    });

    $(function() {
        $('#start_date').daterangepicker({
            singleDatePicker: true,
            autoApply: true,
            locale: {
                format: 'YYYY-MM-DD'
            }
        });







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

    descriptionQuill.on('text-change', function() {
        document.getElementById('description').value = descriptionQuill.root.innerHTML;
    });
</script>
@endpush