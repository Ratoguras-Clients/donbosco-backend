@extends('layouts.app')

@section('content')
    @include('components.breadcrumb', [
        'breadcrumbs' => [
            ['title' => $collection->organization->name ?? 'Organization', 'url' => route('collections.index', $collection->organization->slug ?? '')],
            ['title' => 'Edit Collection', 'url' => null],
        ],
    ])

    @if (session('success'))
        <div id="success-message" data-message="{{ session('success') }}" class="hidden"></div>
    @endif

    @if (session('error'))
        <div id="error-message" data-message="{{ session('error') }}" class="hidden"></div>
    @endif

    <div class="bg-white p-6 rounded-lg shadow-sm mb-6 dark:bg-slate-800">
        <div class="flex justify-between items-center mb-6">
            <h1 class="text-xl font-semibold text-gray-800 dark:text-white">Edit Collection</h1>
            <a href="{{ route('collections.index', $collection->organization->slug ?? url()->previous()) }}"
               class="px-4 py-2 bg-gray-200 dark:bg-gray-900 text-gray-700 dark:text-white rounded-md hover:bg-gray-300 dark:hover:bg-gray-700 flex items-center">
                <span class="iconify mr-2" data-icon="tabler:arrow-left" data-width="18"></span>
                Back
            </a>
        </div>

        <form action="{{ route('collections.update', $collection->id) }}" method="POST">
            @csrf
            @method('PUT')

            <div class="space-y-6">
                <!-- Title -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-slate-100 mb-1">
                        Title <span class="text-red-500">*</span>
                    </label>
                    <input type="text" name="title" value="{{ old('title', $collection->title) }}"
                           class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md focus:ring-blue-800 focus:border-blue-800 dark:bg-slate-700 dark:text-white"
                           placeholder="Enter collection title" required>
                    @error('title')
                        <p class="text-xs text-red-500 mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Description -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-slate-100 mb-1">
                        Description
                    </label>
                    <textarea name="description" rows="4"
                              class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md focus:ring-blue-800 focus:border-blue-800 dark:bg-slate-700 dark:text-white"
                              placeholder="Collection description">{{ old('description', $collection->description) }}</textarea>
                </div>

                <!-- Status -->
                <div class="flex items-center gap-2">
                    <input type="checkbox" name="is_active" value="1"
                           {{ old('is_active', $collection->is_active ? 1 : 0) ? 'checked' : '' }}
                           class="rounded border-gray-300 dark:border-gray-600">
                    <label class="text-sm text-gray-700 dark:text-slate-100">Active</label>
                </div>
            </div>

            <!-- Actions -->
            <div class="mt-8 flex justify-end gap-3">
                <a href="{{ route('collections.index', $collection->organization->slug ?? url()->previous()) }}"
                   class="px-4 py-2 bg-gray-200 dark:bg-gray-700 text-gray-700 dark:text-gray-200 rounded-md hover:bg-gray-300 dark:hover:bg-gray-600">
                    Cancel
                </a>
                <button type="submit"
                        class="px-6 py-2 bg-blue-800 text-white rounded-md hover:bg-blue-900">
                    Update Collection
                </button>
            </div>
        </form>
    </div>
@endsection

@push('scripts')
<script>
$(document).ready(function () {
    const successMessage = $('#success-message').data('message');
    if (successMessage) nepalToast.success('Success', successMessage);

    const errorMessage = $('#error-message').data('message');
    if (errorMessage) nepalToast.error('Error', errorMessage);
});
</script>
@endpush