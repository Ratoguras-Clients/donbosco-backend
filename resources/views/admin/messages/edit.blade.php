@extends('layouts.app')

@section('content')
    @include('components.breadcrumb', [
        'breadcrumbs' => [
            ['title' => $organization->name, 'url' => null],
            ['title' => 'Edit Message', 'url' => null],
        ],
    ])
{{-- 
    @if (session('success'))
        <div id="success-message" data-message="{{ session('success') }}" class="hidden"></div>
    @endif

    @if (session('error'))
        <div id="error-message" data-message="{{ session('error') }}" class="hidden"></div>
    @endif --}}

    <div class="bg-white p-6 rounded-lg shadow-sm mb-6">
        <!-- Page Header -->
        <div class="flex justify-between items-center mb-6">
            <h1 class="text-xl font-semibold text-gray-800">Edit Message</h1>
            <div class="flex space-x-2">
                <a href="{{ url()->previous() }}"
                    class="px-4 py-2 bg-gray-200 dark:bg-gray-900 text-gray-700 rounded-md hover:bg-gray-300 flex items-center">
                    <span class="iconify mr-2" data-icon="tabler:arrow-left" data-width="18"></span>
                    Back
                </a>
            </div>
        </div>

        <form action="{{ route('messages.update', ['slug' => $organization->slug, 'id' => $message->id]) }}" method="POST"
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
                        <input type="text" name="title" id="title" value="{{ old('title', $message->title) }}"
                            placeholder="Enter message title"
                            class="w-full pl-10 pr-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-1 focus:ring-blue-800 focus:border-blue-800">
                    </div>
                    @error('title')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Content -->
                <div>
                    <label for="content" class="block text-sm font-medium text-gray-700 mb-1">
                        Content
                    </label>
                     <div class="border border-gray-300 dark:border-gray-500 rounded-lg bg-gray-50 dark:bg-gray-600">
                        <div id="content_editor" class="min-h-[120px]"></div>
                    </div>

                    <input type="hidden" name="content" id="content">
                    @error('content')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Staff Dropdown -->
                <div>
                    <label for="staffRoleTrigger" class="block text-sm font-medium text-gray-700 mb-1 cursor-pointer">
                        Staff <span class="text-red-500">*</span>
                    </label>

                    <!-- Hidden input -->
                    <input type="hidden" name="organization_staff_id" id="organization_staff_id"
                        value="{{ old('organization_staff_id', $message->organization_staff_id) }}">

                    <div class="relative">
                        <button type="button" id="staffRoleTrigger"
                            class="w-full pl-10 pr-3 py-2 border border-gray-300 rounded-md bg-white text-left focus:outline-none focus:ring-1 focus:ring-blue-800 flex justify-between items-center">
                            <span id="selectedRoleText" class="text-gray-700">
                                {{ $message->organizationStaff?->name ?? 'Select Staff' }}
                            </span>
                            <span class="iconify text-gray-400" data-icon="tabler:chevron-down" data-width="18"></span>
                        </button>

                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <span class="iconify text-gray-400" data-icon="tabler:user-check" data-width="18"></span>
                        </div>

                        <div id="staffRoleDropdown"
                            class="hidden absolute z-50 w-full bg-white border border-gray-200 rounded-md shadow-lg mt-1">
                            <div class="p-2 border-b">
                                <input type="text" id="staffRoleSearch" placeholder="Search role..."
                                    class="w-full px-3 py-2 border rounded-md focus:ring-1 focus:ring-blue-600">
                            </div>
                            <ul id="staffRoleOptions" class="max-h-48 overflow-y-auto"></ul>
                            <div class="border-t p-2">
                                <a href="{{ route('organization-staff.create', $organization->slug) }}" type="button"
                                    class="w-full flex items-center justify-center gap-1 px-3 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 text-sm">
                                    <span class="iconify" data-icon="lucide:plus" data-width="20"></span>
                                    Create Staff
                                </a>
                            </div>
                        </div>
                    </div>
                    @error('organization_staff_id')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Date and Tenure -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Date -->
                    <div>
                        <label for="date" class="block text-sm font-medium text-gray-700 mb-1">
                            Date
                        </label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <span class="iconify text-gray-400" data-icon="tabler:calendar" data-width="18"></span>
                            </div>
                            <input type="date" name="date" id="date" value="{{ old('date', $message->date) }}"
                                class="w-full pl-10 pr-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-1 focus:ring-blue-800 focus:border-blue-800">
                        </div>
                        @error('date')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Tenure -->
                    <div>
                        <label for="tenure" class="block text-sm font-medium text-gray-700 mb-1">
                            Tenure
                        </label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <span class="iconify text-gray-400" data-icon="tabler:clock" data-width="18"></span>
                            </div>
                            <input type="text" name="tenure" id="tenure" value="{{ old('tenure', $message->tenure) }}"
                                placeholder="e.g., 2020-2024"
                                class="w-full pl-10 pr-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-1 focus:ring-blue-800 focus:border-blue-800">
                        </div>
                        @error('tenure')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <!-- Active Options -->
                <div class="grid grid-cols-1 md:grid-cols-2 pb-4 gap-6">
                    <div class="flex items-center">
                        <input type="checkbox" name="is_active" id="is_active" value="1"
                            {{ old('is_active', $message->is_active) ? 'checked' : '' }}
                            class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 rounded focus:ring-blue-500 dark:focus:ring-blue-600 dark:ring-offset-gray-800 focus:ring-2 dark:bg-gray-700 dark:border-gray-600">
                        <label for="is_active" class="ml-2 block text-sm font-medium text-gray-700">
                            Active
                        </label>
                    </div>
                    <div class="flex items-center">
                        <input type="checkbox" name="is_home" id="is_home" value="1"
                            {{ old('is_home', $message->is_home) ? 'checked' : '' }}
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
                    <a href="{{ route('messages.index', $organization->slug) }}"
                        class="px-4 py-2 bg-white border border-gray-300 text-gray-700 rounded-md hover:bg-gray-50 flex items-center dark:bg-gray-800 dark:border-gray-600 dark:hover:bg-gray-700">
                        <span class="iconify mr-2" data-icon="tabler:x" data-width="18"></span>
                        Cancel
                    </a>
                    <button type="submit"
                        class="px-6 py-2 bg-blue-800 text-white rounded-md hover:bg-blue-900 flex items-center">
                        <span class="iconify mr-2" data-icon="tabler:device-floppy" data-width="18"></span>
                        Update Message
                    </button>
                </div>
            </div>
        </form>
    </div>
@endsection

@push('scripts')
    <script>
        let searchTimeout;

        if ($('#success-message').data('message')) nepalToast.success('Success', $('#success-message').data('message'));
        if ($('#error-message').data('message')) nepalToast.error('Error', $('#error-message').data('message'));

        function getStaffRoles(search = '') {
            $.ajax({
                url: "{{ route('messages.getMessageableStaff', $organization->slug) }}",
                method: 'GET',
                data: {
                    search: search
                },
                beforeSend: function() {
                    $('#staffRoleOptions').html(`<li class="px-4 py-2 text-gray-400 text-sm">Loading...</li>`);
                },
                success: function(data) {
                    renderStaffRoles(data);
                },
                error: function() {
                    $('#staffRoleOptions').html(
                        `<li class="px-4 py-2 text-red-500 text-sm">Failed to load staff</li>`);
                }
            });
        }

        function renderStaffRoles(data) {
            const list = $('#staffRoleOptions');
            list.empty();
            if (!data.length) {
                list.append(`<li class="px-4 py-2 text-gray-500 text-sm">No staff found</li>`);
                return;
            }

            const selectedId = $('#organization_staff_id').val();

            data.forEach(staff => {
                list.append(`
                <li class="px-4 py-2 hover:bg-blue-50 dark:hover:bg-slate-700 cursor-pointer staff-role-item"
                    data-id="${staff.id}" data-name="${staff.name}">
                    ${staff.name}
                </li>
            `);

                // Preselect staff in dropdown
                if (staff.id == selectedId) {
                    $('#selectedRoleText').text(staff.name);
                }
            });
        }

        function toggleRoleDropdown() {
            const dropdown = $('#staffRoleDropdown');
            const chevron = $('#staffRoleTrigger').find('.iconify[data-icon^="tabler:chevron"]');
            dropdown.toggleClass('hidden');
            chevron.attr('data-icon', dropdown.hasClass('hidden') ? 'tabler:chevron-down' : 'tabler:chevron-up');
            if (!dropdown.hasClass('hidden')) $('#staffRoleSearch').focus();
        }

        $(document).ready(function() {
            getStaffRoles();

            $('#staffRoleTrigger, label[for="staffRoleTrigger"]').on('click', function(e) {
                e.preventDefault();
                toggleRoleDropdown();
            });

            $('#staffRoleSearch').on('keyup', function() {
                clearTimeout(searchTimeout);
                const keyword = $(this).val();
                searchTimeout = setTimeout(() => getStaffRoles(keyword), 300);
            });

            $(document).on('click', '.staff-role-item', function() {
                const id = $(this).data('id');
                const name = $(this).data('name');
                $('#organization_staff_id').val(id);
                $('#selectedRoleText').text(name);
                toggleRoleDropdown();
            });

            $(document).on('click', function(e) {
                if (!$(e.target).closest(
                        '#staffRoleTrigger, #staffRoleDropdown, label[for="staffRoleTrigger"]').length) {
                    $('#staffRoleDropdown').addClass('hidden');
                    $('#staffRoleTrigger').find('.iconify[data-icon^="tabler:chevron"]').attr('data-icon',
                        'tabler:chevron-down');
                }
            });
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
    contentQuill.root.innerHTML = `{!! addslashes($message->content) !!}`;
    document.getElementById('content').value = contentQuill.root.innerHTML;
    contentQuill.on('text-change', function() {
        document.getElementById('content').value = contentQuill.root.innerHTML;
    });
    </script>
@endpush