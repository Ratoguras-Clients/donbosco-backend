@extends('layouts.app')

@push('styles')
    <script src="https://cdn.jsdelivr.net/npm/quill@2.0.3/dist/quill.js"></script>
    <link href="https://cdn.jsdelivr.net/npm/quill@2.0.3/dist/quill.snow.css" rel="stylesheet">
    <style>
        .ql-container,
        .ql-editor {
            min-height: 120px;
        }
    </style>
@endpush

@section('content')
    @include('components.breadcrumb', [
        'breadcrumbs' => [
            ['title' => $organization->name, 'url' => null],
            ['title' => 'Edit Organization Hero', 'url' => null],
        ],
    ])


    <div class="bg-white p-6 rounded-lg shadow-sm mb-6">
        <div class="flex justify-between items-center mb-6">
            <h1 class="text-xl font-semibold text-gray-800">Edit Organization Hero</h1>
            <div class="flex space-x-2">
                <a href="{{ url()->previous() }}"
                    class="px-4 py-2 bg-gray-200 dark:bg-gray-900 text-gray-700 rounded-md hover:bg-gray-300 flex items-center">
                    <span class="iconify mr-2" data-icon="tabler:arrow-left" data-width="18"></span>
                    Back
                </a>
            </div>
        </div>

        <form action="{{ route('organizationhero.update', [$organization->slug, $organizationhero->id]) }}" method="POST">
            @csrf
            @method('PUT')

            <div class="space-y-3">
                <div class="md:col-span-2">
                    <label for="title" class="block text-sm font-medium text-gray-700 mb-1">
                        Title <span class="text-red-500">*</span>
                    </label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <span class="iconify text-gray-400" data-icon="tabler:pencil" data-width="18"></span>
                        </div>
                        <input type="text" name="title" id="title" value="{{ old('title', $organizationhero->title) }}"
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
                        <input type="hidden" name="content" id="content"
                            value="{{ old('content', $organizationhero->content) }}">
                    </div>
                </div>
            </div>

            <div class="flex items-center mt-6">
                <input type="checkbox" name="is_home" id="is_home" value="1" @checked(old('is_home', $organizationhero->is_home))
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
                        <span class="iconify mr-2" data-icon="tabler:device-floppy" data-width="18"></span> Update
                    </button>
                </div>
            </div>
        </form>
    </div>
@endsection

@push('scripts')
    <script>
        // Content Quill Editor
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

        const existingContent = document.getElementById('content').value;
        if (existingContent) {
            contentQuill.root.innerHTML = existingContent;
        }

        contentQuill.on('text-change', function() {
            document.getElementById('content').value = contentQuill.root.innerHTML;
        });
    </script>
@endpush