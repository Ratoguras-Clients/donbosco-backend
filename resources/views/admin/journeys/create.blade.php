@extends('layouts.app')

@section('content')
    @include('components.breadcrumb', [
        'breadcrumbs' => [
            ['title' => $organization->name, 'url' => null],
            ['title' => 'Create Journeys', 'url' => null],
        ],
    ])

    <div class="bg-white p-6 rounded-lg shadow-sm mb-6">
        <div class="flex justify-between items-center mb-6">
            <h1 class="text-xl font-semibold text-gray-800">Create Journey</h1>
            <div class="flex space-x-2">
                <a href="{{ url()->previous() }}"
                    class="px-4 py-2 bg-gray-200 dark:bg-gray-900 text-gray-700 rounded-md hover:bg-gray-300 flex items-center">
                    <span class="iconify mr-2" data-icon="tabler:arrow-left" data-width="18"></span>
                    Back
                </a>
            </div>
        </div>

        <form action="{{ route('journeys.store', $organization->slug) }}" method="POST" enctype="multipart/form-data">
            @csrf

            <div class="space-y-6">
                <!-- Journey Title -->
                <div>
                    <label for="title" class="block text-sm font-medium text-gray-700 mb-1">
                        Title <span class="text-red-500">*</span>
                    </label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <span class="iconify text-gray-400" data-icon="tabler:pencil" data-width="18"></span>
                        </div>
                        <input type="text" name="title" id="title" value="{{ old('title') }}"
                            placeholder="Enter journey title"
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
                    <textarea name="description" id="description" rows="5" placeholder="Detailed description of the journey"
                        class="block w-full p-3 text-sm text-gray-900 bg-gray-50 rounded-lg border border-gray-300 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-600 dark:border-gray-500 dark:text-white">{{ old('description') }}</textarea>
                    @error('description')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Date -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label for="start_date" class="block text-sm font-medium text-gray-700 mb-1 dark:text-slate-100">
                            Start Date <span class="text-red-500">*</span>
                        </label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <span class="iconify text-gray-400" data-icon="tabler:calendar" data-width="18"></span>
                            </div>
                            <input type="text" name="start_date" id="start_date" value="{{ old('start_date') }}" required
                                class="date w-full pl-10 pr-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-1 focus:ring-blue-800 focus:border-blue-800">
                        </div>
                        @error('start_date')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="end_date" class="block text-sm font-medium text-gray-700 mb-1 dark:text-slate-100">
                            End Date
                        </label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <span class="iconify text-gray-400" data-icon="tabler:calendar" data-width="18"></span>
                            </div>
                            <input type="text" name="end_date" id="end_date" value="{{ old('end_date') }}"
                                class="date w-full pl-10 pr-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-1 focus:ring-blue-800 focus:border-blue-800">
                        </div>
                        @error('end_date')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <!-- Active -->
                <div class="flex items-center pb-3">
                    <input type="checkbox" name="is_active" value="1" {{ old('is_active', true) ? 'checked' : '' }}
                        class="w-4 h-4 text-blue-600 border-gray-300 rounded">
                    <label class="ml-2 text-sm text-gray-700">Active</label>
                </div>

                <!-- Cover Images -->
                <div class="bg-gray-50 dark:bg-gray-700/50 rounded-lg p-4 mb-6">
                    <h3 class="text-lg font-medium text-gray-800 mb-4 flex items-center">
                        <span class="iconify mr-2" data-icon="tabler:photo" data-width="20"></span>
                        Cover Images
                    </h3>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

                        {{-- Cover Image 1 --}}
                        <div>
                            <p class="text-sm font-medium text-gray-700 mb-2">Cover Image 1</p>
                            <div class="flex flex-col items-center justify-center p-4 border border-dashed border-gray-300 rounded-md">
                                <input type="hidden" name="media_id" id="media_id" value="{{ old('media_id') }}">
                                <div id="image-preview-1" class="mb-4 w-full">
                                    @if (old('media_id'))
                                        <img src="{{ \App\Models\Website\Media::find(old('media_id'))?->url ?? '' }}"
                                            class="max-h-48 rounded-md shadow mx-auto">
                                    @endif
                                </div>
                                <button type="button" class="select-image-btn px-4 py-2 bg-gray-100 dark:bg-gray-700 text-gray-700 dark:text-gray-200 rounded-md border border-gray-300 dark:border-gray-600 flex items-center justify-center hover:bg-gray-200 dark:hover:bg-gray-600 transition"
                                    data-image-number="1">
                                    <span class="iconify mr-2" data-icon="tabler:upload" data-width="20"></span>
                                    Select Image 1
                                </button>
                                <button type="button" class="remove-image-btn text-red-600 hover:text-red-800 text-sm mt-3 {{ old('media_id') ? '' : 'hidden' }}"
                                    data-image-number="1">
                                    Remove Image
                                </button>
                            </div>
                            @error('media_id')
                                <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        {{-- Cover Image 2 --}}
                        <div>
                            <p class="text-sm font-medium text-gray-700 mb-2">Cover Image 2</p>
                            <div class="flex flex-col items-center justify-center p-4 border border-dashed border-gray-300 rounded-md">
                                <input type="hidden" name="media_id_2" id="media_id_2" value="{{ old('media_id_2') }}">
                                <div id="image-preview-2" class="mb-4 w-full">
                                    @if (old('media_id_2'))
                                        <img src="{{ \App\Models\Website\Media::find(old('media_id_2'))?->url ?? '' }}"
                                            class="max-h-48 rounded-md shadow mx-auto">
                                    @endif
                                </div>
                                <button type="button" class="select-image-btn px-4 py-2 bg-gray-100 dark:bg-gray-700 text-gray-700 dark:text-gray-200 rounded-md border border-gray-300 dark:border-gray-600 flex items-center justify-center hover:bg-gray-200 dark:hover:bg-gray-600 transition"
                                    data-image-number="2">
                                    <span class="iconify mr-2" data-icon="tabler:upload" data-width="20"></span>
                                    Select Image 2
                                </button>
                                <button type="button" class="remove-image-btn text-red-600 hover:text-red-800 text-sm mt-3 {{ old('media_id_2') ? '' : 'hidden' }}"
                                    data-image-number="2">
                                    Remove Image
                                </button>
                            </div>
                            @error('media_id_2')
                                <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

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
                    <a href="{{ route('journeys.index', $organization->slug) }}"
                        class="px-4 py-2 bg-white border border-gray-300 text-gray-700 rounded-md hover:bg-gray-50 flex items-center dark:bg-gray-800 dark:border-gray-600 dark:hover:bg-gray-700">
                        <span class="iconify mr-2" data-icon="tabler:x" data-width="18"></span>
                        Cancel
                    </a>
                    <button type="submit"
                        class="px-6 py-2 bg-blue-800 text-white rounded-md hover:bg-blue-900 flex items-center">
                        <span class="iconify mr-2" data-icon="tabler:calendar-plus" data-width="18"></span>
                        Create Journey
                    </button>
                </div>
            </div>
        </form>
    </div>

    {{-- Single picker, triggerSelector empty, opened manually --}}
    <x-media-piker modalId="featured-image-media-picker" title="Select Cover Image"
        :allowMultiple="false" :allowUpload="true" :allowView="true"
        triggerSelector=".select-image-btn" onSelect="handleImageSelection"
        acceptedTypes="image/jpeg,image/jpg,image/png,image/gif,image/webp,image/svg+xml" maxFileSize="10MB" />
@endsection

@push('scripts')
    <script>
        // Track which image slot is being filled
        let currentImageNumber = null;

        // Open picker and remember which slot was clicked
        $(document).on('click', '.select-image-btn', function () {
            currentImageNumber = $(this).data('image-number');
            if (window.featuredImagePicker) {
                window.featuredImagePicker.open();
            }
        });

        // Map slot number to hidden input ID
        function getInputId(num) {
            return num === 1 ? 'media_id' : 'media_id_2';
        }

        // Callback from media picker
        window.handleImageSelection = function (mediaId, picker) {
            if (!mediaId || !currentImageNumber) return;

            const mediaUrl = $(`#${picker.modalId}-grid .media-item[data-media-id="${mediaId}"]`).data('media-url');
            if (!mediaUrl) return;

            const inputId = getInputId(currentImageNumber);
            const num = currentImageNumber;

            // Set hidden input value
            $(`#${inputId}`).val(mediaId);

            // Build and inject preview
            const fileName = mediaUrl.split('/').pop();
            $(`#image-preview-${num}`).html(`
                <div class="relative bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-600 rounded-lg shadow-sm overflow-hidden">
                    <div class="flex items-center justify-between p-3 bg-gray-50 dark:bg-gray-700 border-b border-gray-200 dark:border-gray-600">
                        <div class="flex items-center gap-2">
                            <span class="iconify text-green-500" data-icon="tabler:photo" data-width="18"></span>
                            <span class="text-sm font-medium text-gray-700 dark:text-gray-300 truncate max-w-[240px]">${fileName}</span>
                        </div>
                        <button type="button" class="remove-image-inline text-red-600 dark:text-red-400 hover:text-red-800 text-xs px-2 py-1 rounded"
                            data-image-number="${num}">Remove</button>
                    </div>
                    <div class="flex items-center justify-center p-4 bg-gray-50 dark:bg-gray-700/50 min-h-[120px]">
                        <img src="${mediaUrl}" alt="Preview" class="max-w-full h-48 object-contain rounded-md shadow-sm">
                    </div>
                </div>
            `);

            // Show remove button
            $(`.remove-image-btn[data-image-number="${num}"]`).removeClass('hidden');
        };

        // Remove via main button
        $(document).on('click', '.remove-image-btn', function () {
            const num = $(this).data('image-number');
            $(`#${getInputId(num)}`).val('');
            $(`#image-preview-${num}`).empty();
            $(this).addClass('hidden');
        });

        // Remove via inline button inside preview
        $(document).on('click', '.remove-image-inline', function () {
            const num = $(this).data('image-number');
            $(`#${getInputId(num)}`).val('');
            $(`#image-preview-${num}`).empty();
            $(`.remove-image-btn[data-image-number="${num}"]`).addClass('hidden');
        });

        // Init media picker
        try {
            window.featuredImagePicker = new MediaPickerClass('featured-image-media-picker', {
                onSelect: window.handleImageSelection
            });
        } catch (e) {
            console.error('Media Picker failed', e);
        }

        // Date pickers
        $(function () {
            $('#start_date').daterangepicker({
                singleDatePicker: true,
                autoApply: true,
                locale: { format: 'YYYY-MM-DD' }
            });

            $('#end_date').daterangepicker({
                singleDatePicker: true,
                autoApply: true,
                autoUpdateInput: false,
                locale: { format: 'YYYY-MM-DD', cancelLabel: 'Clear' }
            });

            $('#end_date').on('apply.daterangepicker', function (ev, picker) {
                $(this).val(picker.startDate.format('YYYY-MM-DD'));
            });

            $('#end_date').on('cancel.daterangepicker', function () {
                $(this).val('');
            });
        });
    </script>
@endpush