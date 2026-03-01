@extends('layouts.app')

@push('styles')
    <style>
        .form-card {
            transition: all 0.3s ease;
        }

        .form-card:hover {
            box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1), 0 4px 6px -2px rgba(0, 0, 0, 0.05);
        }

        .animated-bg {
            background: linear-gradient(-45deg, #003893, #0052cc, #0066ff, #003893);
            background-size: 400% 400%;
            animation: gradient 15s ease infinite;
        }

        @keyframes gradient {
            0% {
                background-position: 0% 50%;
            }

            50% {
                background-position: 100% 50%;
            }

            100% {
                background-position: 0% 50%;
            }
        }


        .permission-group {
            transition: all 0.2s ease;
        }

        .permission-group:hover {
            background-color: rgba(0, 0, 0, 0.02);
        }

        .permission-checkbox {
            display: grid;
            grid-template-columns: 1em auto;
            gap: 0.5em;
        }


        .role-badge {
            position: relative;
            overflow: hidden;
        }

        .role-badge::after {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: linear-gradient(45deg, rgba(255, 255, 255, 0.2) 0%, rgba(255, 255, 255, 0) 100%);
            z-index: 1;
        }

        .action-tag {
            transition: all 0.2s ease;
            cursor: pointer;
        }

        .action-tag.active {
            transform: scale(1.05);
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06);
        }

        .filter-section {
            border-radius: 0.5rem;
            background-color: rgba(0, 0, 0, 0.02);
            padding: 1rem;
            margin-bottom: 1.5rem;
        }

        .group-name-tag.active {
            background-color: #0ea9fc !important;
            color: #ffffff !important;
        }
    </style>
@endpush

@section('content')
    @include('components.breadcrumb', [
        'breadcrumbs' => [
            ['title' => 'Roles', 'url' => route('roles.index')],
            ['title' => $role->name, 'url' => route('roles.show', $role)],
            ['title' => 'Manage Permissions', 'url' => null],
        ],
    ])

    <!-- Page Header -->
    <div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-6">

        <div class="flex flex-row gap-2 justify-center items-center">
            <span class="iconify text-nepal-blue" data-icon="lucide:user-cog" data-width="22"></span>
            <h1 class="text-2xl font-bold text-gray-800 dark:text-white mb-4 md:mb-0 relative pl-3">
                <span class="absolute left-0 top-1/2 transform -translate-y-1/2 w-0.5 h-6 bg-nepal-blue rounded"></span>
                Configure permissions for role: <span class="font-medium">{{ $role->name }}</span>
            </h1>
        </div>

        <div class="mt-4 md:mt-0">
            <a href="{{ route('roles.show', $role) }}"
                class="bg-gray-200 hover:bg-gray-300 text-gray-700 dark:bg-gray-700 dark:hover:bg-gray-600 dark:text-gray-200 px-4 py-2 rounded-lg shadow-sm flex items-center transition-colors">
                <span class="iconify mr-2" data-icon="tabler:arrow-left" data-width="20"></span>
                Back to Role
            </a>
        </div>
    </div>

    <!-- Success Message -->
    @if (session('success'))
        <div id="success-message" data-message="{{ session('success') }}" class="hidden"></div>
    @endif

    <!-- Error Message -->
    @if (session('error'))
        <div id="error-message" data-message="{{ session('error') }}" class="hidden"></div>
    @endif

    <!-- Permissions Form Card -->
    <div class="">
        <div
            class="form-card bg-white dark:bg-gray-800 rounded-md shadow-md overflow-hidden border border-gray-200 dark:border-gray-700">

            <!-- Form Content -->
            <form id="permissions-form" action="{{ route('roles.permissions.update', $role) }}" method="POST">
                @csrf
                @method('PUT')
                <div class="p-6">
                    <!-- Quick Actions -->
                    <div class="mb-6 flex flex-wrap gap-4">
                        <button type="button" id="select-all"
                            class="px-4 py-2 bg-blue-100 hover:bg-blue-200 text-blue-800 dark:bg-blue-900/30 dark:text-blue-300 dark:hover:bg-blue-900/50 rounded-lg flex items-center transition-colors">
                            <span class="iconify mr-2" data-icon="tabler:check-circle" data-width="20"></span>
                            Select All
                        </button>
                        <button type="button" id="deselect-all"
                            class="px-4 py-2 bg-gray-100 hover:bg-gray-200 text-gray-800 dark:bg-gray-700 dark:text-gray-300 dark:hover:bg-gray-600 rounded-lg flex items-center transition-colors">
                            <span class="iconify mr-2" data-icon="tabler:square" data-width="20"></span>
                            Deselect All
                        </button>

                        <!-- Group Selectors -->
                        @foreach ($permissionGroups as $group => $permissions)
                            <button type="button" data-group="{{ $group }}"
                                class="group-name-tag  px-4 py-2 bg-indigo-100 hover:bg-indigo-200 text-indigo-800 dark:bg-indigo-900/30 dark:text-indigo-300 dark:hover:bg-indigo-900/50 rounded-lg flex items-center transition-colors">
                                <span class="iconify mr-2" data-icon="tabler:folder" data-width="20"></span>
                                {{ ucwords(str_replace('-', ' ', $group)) }}
                            </button>
                        @endforeach
                    </div>

                    {{-- {Filter By Group Names --}}
                    {{-- <div class="filter-section mb-6 dark:bg-gray-700/30">
                        <h4 class="text-sm font-medium text-gray-700 dark:text-gray-300 mb-3">Filter by Group Name:</h4>
                        <div class="flex flex-wrap gap-2" id="group-name-filters">
                            @foreach ($permissionGroups as $group => $permissions)
                                <span
                                    class="group-name-tag px-3 py-1.5 hover:bg-sky-200 bg-sky-100 text-sky-800 dark:bg-sky-900/30 dark:text-sky-300 rounded-full flex items-center cursor-pointer transition-all"
                                    data-group="{{ $group }}">
                                    <span class="iconify mr-1.5" data-icon="tabler:folder" data-width="16"></span>
                                    {{ ucwords(str_replace('-', ' ', $group)) }}
                                </span>
                            @endforeach
                            <span
                                class="group-name-tag px-3 py-1.5 bg-gray-200 hover:bg-gray-300 text-gray-700 dark:bg-gray-800 dark:text-gray-300 rounded-full flex items-center cursor-pointer transition-all"
                                data-group="clear">
                                <span class="iconify mr-1.5" data-icon="tabler:filter-off" data-width="16"></span>
                                Clear Filter
                            </span>
                        </div>
                    </div> --}}


                    <!-- Action Tags Filter -->
                    <div class="filter-section mb-6 dark:bg-gray-700/30">
                        <h4 class="text-sm font-medium text-gray-700 dark:text-gray-300 mb-3">Filter by Action:</h4>
                        <div class="flex flex-wrap gap-2">
                            <span
                                class="action-tag px-3 py-1.5 bg-green-100 text-green-800 dark:bg-green-900/30 dark:text-green-300 rounded-full flex items-center"
                                data-action="view">
                                <span class="iconify mr-1.5" data-icon="tabler:eye" data-width="16"></span>
                                View
                            </span>
                            <span
                                class="action-tag px-3 py-1.5 bg-blue-100 text-blue-800 dark:bg-blue-900/30 dark:text-blue-300 rounded-full flex items-center"
                                data-action="create">
                                <span class="iconify mr-1.5" data-icon="tabler:plus" data-width="16"></span>
                                Create
                            </span>
                            <span
                                class="action-tag px-3 py-1.5 bg-amber-100 text-amber-800 dark:bg-amber-900/30 dark:text-amber-300 rounded-full flex items-center"
                                data-action="update">
                                <span class="iconify mr-1.5" data-icon="tabler:edit" data-width="16"></span>
                                Update
                            </span>
                            <span
                                class="action-tag px-3 py-1.5 bg-red-100 text-red-800 dark:bg-red-900/30 dark:text-red-300 rounded-full flex items-center"
                                data-action="delete">
                                <span class="iconify mr-1.5" data-icon="tabler:trash" data-width="16"></span>
                                Delete
                            </span>
                            <span
                                class="action-tag px-3 py-1.5 bg-purple-100 text-purple-800 dark:bg-purple-900/30 dark:text-purple-300 rounded-full flex items-center"
                                data-action="clear-filters">
                                <span class="iconify mr-1.5" data-icon="tabler:filter-off" data-width="16"></span>
                                Clear Filters
                            </span>
                        </div>
                    </div>

                    <!-- Search Box -->
                    <div class="mb-6">
                        <div class="relative">
                            <span class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <span class="iconify text-gray-400" data-icon="tabler:search" data-width="20"></span>
                            </span>
                            <input type="text" id="search-permissions" placeholder="Search permissions..."
                                class="w-full pl-10 pr-3 py-2 border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white rounded-lg focus:outline-none focus:ring-2 focus:ring-nepal-blue focus:border-transparent">
                        </div>
                    </div>

                    <!-- Permissions List -->
                    <div class="space-y-6">
                        @foreach ($permissionGroups as $group => $permissions)
                            <div class="permission-group-container" data-group="{{ $group }}">
                                <div
                                    class="flex items-center justify-between mb-2 pb-2 border-b border-gray-200 dark:border-gray-700">
                                    <h3 class="text-lg font-medium text-gray-800 dark:text-white flex items-center">
                                        <span class="iconify mr-2" data-icon="tabler:folder" data-width="20"></span>
                                        {{ ucwords(str_replace('-', ' ', $group)) }}
                                    </h3>
                                    <div class="select-all-checkbox px-3 py-1 rounded-lg">
                                        <label class="permission-checkbox inline-flex items-center">
                                            <input type="checkbox" class="permission-checkbox--input group-select-all"
                                                data-group="{{ $group }}">
                                            <span class="ml-2 text-sm text-gray-700 dark:text-gray-300">Select All
                                                {{ ucwords(str_replace('-', ' ', $group)) }}</span>
                                        </label>
                                    </div>
                                </div>

                                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                                    @foreach ($permissions as $permission)
                                        <div class="permission-item p-3 rounded-lg permission-group"
                                            data-name="{{ strtolower($permission->name) }}"
                                            data-action="{{ substr(strrchr($permission->name, '-'), 1) }}">
                                            <label class="permission-checkbox flex items-center">
                                                <input type="checkbox" name="permissions[]"
                                                    value="{{ $permission->id }}"
                                                    class="permission-checkbox--input permission-checkbox-item"
                                                    data-group="{{ $group }}"
                                                    {{ in_array($permission->id, $rolePermissions) ? 'checked' : '' }}>
                                                <div class="ml-2">
                                                    <span class="text-sm font-medium text-gray-700 dark:text-gray-300">
                                                        {{ ucwords(str_replace('-', ' ', $permission->name)) }}
                                                    </span>
                                                    <p class="text-xs text-gray-500 dark:text-gray-400">
                                                        {{ $permission->description }}</p>
                                                </div>
                                            </label>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        @endforeach
                    </div>

                    <!-- No Results Message -->
                    <div id="no-results"
                        class="hidden bg-yellow-100 border-l-4 border-yellow-500 text-yellow-700 p-4 my-6 rounded-md shadow-sm dark:bg-yellow-900/30 dark:text-yellow-300 dark:border-yellow-500">
                        <div class="flex items-center">
                            <span class="iconify mr-2" data-icon="tabler:alert-circle" data-width="24"></span>
                            <p>No permissions found matching your search.</p>
                        </div>
                    </div>

                    <!-- Form Actions -->
                    <div
                        class="flex flex-col sm:flex-row justify-end space-y-3 sm:space-y-0 sm:space-x-3 mt-8 pt-6 border-t border-gray-200 dark:border-gray-700">
                        <button type="button" id="cancel-permissions"
                            class="px-4 py-2 bg-gray-100 dark:bg-gray-700 text-gray-700 dark:text-gray-200 rounded-lg border border-gray-300 dark:border-gray-600 flex items-center justify-center transition-colors">
                            <span class="iconify mr-2" data-icon="tabler:x" data-width="20"></span>
                            Cancel
                        </button>
                        <button type="submit"
                            class="px-4 py-2 bg-nepal-blue hover:bg-nepal-blue/90 text-white rounded-lg flex items-center justify-center transition-colors">
                            <span class="iconify mr-2" data-icon="tabler:device-floppy" data-width="20"></span>
                            Save Permissions
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>
    <!-- Modal -->
    <div id="permissionModal" class="fixed inset-0 z-50 hidden bg-black bg-opacity-50 flex justify-center items-center">
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow-lg w-full max-w-3xl p-6 relative">
            <h2 id="modalGroupTitle" class="text-xl font-semibold text-gray-800 dark:text-white mb-4">
                Manage Permissions
            </h2>

            <form id="modal-permissions-form">
                @csrf
                @method('PUT')
                <div id="modalPermissionList" class="grid grid-cols-1 md:grid-cols-2 gap-4 max-h-[400px] overflow-y-auto">
                    <!-- Permissions will be injected here dynamically -->
                </div>

                <div class="flex justify-end mt-6 gap-4">
                    <button type="button" id="closePermissionModal"
                        class="bg-gray-200 hover:bg-gray-300 px-4 py-2 rounded-lg text-gray-700 dark:bg-gray-700 dark:text-gray-200">
                        Cancel
                    </button>
                    <button type="submit" class="bg-nepal-blue hover:bg-nepal-blue/90 text-white px-4 py-2 rounded-lg">
                        Save Permissions
                    </button>
                </div>
            </form>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        $(document).ready(function() {
            // Store role permissions for checking against modal checkboxes
            const rolePermissions = @json($rolePermissions);

            // Variable to store current group being edited in modal
            let currentModalGroup = null;

            $('#modal-permissions-form').submit(function(e) {
                e.preventDefault();
                const data = $(this).serializeArray();

                // Add the permission_group parameter to indicate partial update
                if (currentModalGroup) {
                    data.push({
                        name: "permission_group",
                        value: currentModalGroup
                    });
                }

                data.push({
                    name: "ajax",
                    value: true
                });

                console.log(data);
                $.ajax({
                    type: "PUT",
                    url: "{{ route('roles.permissions.update', $role) }}",
                    data: data,
                    success: function(response) {
                        console.log(response);
                        if (response.success) {
                            nepalToast.success('Success', response.message);
                            // Reload the page to reflect the changes
                            setTimeout(function() {
                                location.reload();
                            }, 1500);
                        }
                    },
                    error: function(xhr) {
                        nepalToast.error('Error', 'Failed to update permissions');
                    }
                });
            });

            $('.group-name-tag').on('click', function() {
                const selectedGroup = $(this).data('group');
                const modalPermissionList = $('#modalPermissionList');

                if (selectedGroup === 'clear') {
                    $('.group-name-tag').removeClass('active');
                    $('.permission-group-container').removeClass('hidden');
                    return;
                }

                // Store the current group being edited
                currentModalGroup = selectedGroup;

                modalPermissionList.html('');
                $.ajax({
                    type: "GET",
                    url: "?getPermissionDetail",
                    data: {
                        groupname: selectedGroup
                    },
                    success: function(response) {
                        $('#modalGroupTitle').text('Manage Permissions: ' + selectedGroup
                            .replace(/-/g, ' ')
                            .replace(/\b\w/g, l => l.toUpperCase()));

                        console.log(response);
                        response.permission.forEach(element => {
                            // Check if this permission is already assigned to the role
                            const isChecked = rolePermissions.includes(element.id);

                            modalPermissionList.append(
                                '<div class="permission-item p-3 rounded-lg permission-group" data-name="' +
                                element.name + '" data-action="' + element.name +
                                '"><label class="permission-checkbox flex items-center"><input type="checkbox" name="permissions[]" value="' +
                                element.id +
                                '" class="permission-checkbox--input permission-checkbox-item" data-group="' +
                                selectedGroup + '" ' + (isChecked ? 'checked' :
                                '') +
                                '><div class="ml-2"><span class="text-sm font-medium text-gray-700 dark:text-gray-300">' +
                                element.name +
                                '</span></div></label></div>');
                        });
                    }
                });
                $('#permissionModal').removeClass('hidden');
            });


            $('#closePermissionModal').on('click', function() {
                $('#permissionModal').addClass('hidden');
                currentModalGroup = null;
            });




            // Show success toast if there's a success message
            const successMessage = $('#success-message').data('message');
            if (successMessage) {
                nepalToast.success('Success', successMessage);
            }

            // Show error toast if there's an error message
            const errorMessage = $('#error-message').data('message');
            if (errorMessage) {
                nepalToast.error('Error', errorMessage);
            }

            // Select All button
            $('#select-all').on('click', function() {
                $('.permission-checkbox-item').prop('checked', true);
                $('.group-select-all').prop('checked', true);
            });

            // Deselect All button
            $('#deselect-all').on('click', function() {
                $('.permission-checkbox-item').prop('checked', false);
                $('.group-select-all').prop('checked', false);
            });

            // Group selector buttons
            $('.group-selector').on('click', function() {
                const group = $(this).data('group');
                const checkboxes = $(`.permission-checkbox-item[data-group="${group}"]`);
                const allChecked = checkboxes.length === checkboxes.filter(':checked').length;

                checkboxes.prop('checked', !allChecked);
                $(`.group-select-all[data-group="${group}"]`).prop('checked', !allChecked);
            });

            // Group select all checkboxes
            $('.group-select-all').on('change', function() {
                const group = $(this).data('group');
                const isChecked = $(this).prop('checked');

                $(`.permission-checkbox-item[data-group="${group}"]`).prop('checked', isChecked);
            });

            // Update group select all checkbox when individual permissions change
            $('.permission-checkbox-item').on('change', function() {
                const group = $(this).data('group');
                const groupCheckboxes = $(`.permission-checkbox-item[data-group="${group}"]`);
                const allChecked = groupCheckboxes.length === groupCheckboxes.filter(':checked').length;

                $(`.group-select-all[data-group="${group}"]`).prop('checked', allChecked);
            });

            // Action tag filtering
            let activeFilters = [];

            $('.action-tag').on('click', function() {
                const action = $(this).data('action');

                // Clear all filters
                if (action === 'clear-filters') {
                    activeFilters = [];
                    $('.action-tag').removeClass('active');
                    applyFilters();
                    return;
                }

                // Toggle active state
                $(this).toggleClass('active');

                // Update active filters
                if ($(this).hasClass('active')) {
                    if (!activeFilters.includes(action)) {
                        activeFilters.push(action);
                    }
                } else {
                    activeFilters = activeFilters.filter(filter => filter !== action);
                }

                applyFilters();
            });

            // Search functionality
            $("#search-permissions").on("keyup", function() {
                applyFilters();
            });

            // Apply both search and action filters
            function applyFilters() {
                const searchValue = $("#search-permissions").val().toLowerCase();
                let visibleItems = 0;

                $(".permission-item").each(function() {
                    const name = $(this).data("name");
                    const action = $(this).data("action");

                    // Check if matches search
                    const matchesSearch = searchValue === "" || name.indexOf(searchValue) > -1;

                    // Check if matches action filter
                    const matchesAction = activeFilters.length === 0 || activeFilters.includes(action);

                    // Show/hide based on both conditions
                    if (matchesSearch && matchesAction) {
                        $(this).removeClass("hidden");
                        visibleItems++;
                    } else {
                        $(this).addClass("hidden");
                    }
                });

                // Show or hide the "no results" message
                if (visibleItems === 0 && (searchValue !== "" || activeFilters.length > 0)) {
                    $("#no-results").removeClass("hidden");
                } else {
                    $("#no-results").addClass("hidden");
                }

                // Show/hide group containers based on whether they have visible items
                $(".permission-group-container").each(function() {
                    const visibleInGroup = $(this).find('.permission-item:not(.hidden)').length;
                    if (visibleInGroup === 0) {
                        $(this).addClass('hidden');
                    } else {
                        $(this).removeClass('hidden');
                    }
                });
            }

            // Cancel button
            $('#cancel-permissions').on('click', function() {
                nepalConfirm.show({
                    title: 'Cancel Changes',
                    message: 'Are you sure you want to cancel? Any unsaved changes will be lost.',
                    type: 'warning',
                    confirmText: 'Yes, Cancel',
                    cancelText: 'No, Continue Editing',
                    confirmIcon: '<span class="iconify" data-icon="tabler:x" data-width="18"></span>'
                }).then(() => {
                    // Redirect back to role details
                    window.location.href = "{{ route('roles.show', $role) }}";
                }).catch(() => {
                    // Show a toast notification if canceled
                    nepalToast.nepal('Action Canceled', 'You can continue editing permissions.');
                });
            });
        });
    </script>
@endpush
