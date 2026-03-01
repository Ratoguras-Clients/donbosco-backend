@extends('layouts.app')

@push('styles')
    <style>
        .form-card {
            transition: all 0.3s ease;
        }

        .form-card:hover {
            box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1), 0 4px 6px -2px rgba(0, 0, 0, 0.05);
        }

        .toggle-checkbox:checked {
            @apply right-0 border-nepal-blue;
        }

        .toggle-checkbox:checked+.toggle-label {
            @apply bg-nepal-blue;
        }

        .input-field {
            transition: all 0.2s ease;
        }

        .input-field:focus {
            border-color: #003893;
            box-shadow: 0 0 0 3px rgba(0, 56, 147, 0.2);
        }

        .btn-primary {
            background-color: #003893;
            transition: all 0.2s ease;
        }

        .btn-primary:hover {
            background-color: #002d74;
        }

        .btn-secondary {
            transition: all 0.2s ease;
        }

        .btn-secondary:hover {
            background-color: #f3f4f6;
        }

        .checkbox-nepal {
            display: grid;
            grid-template-columns: 1em auto;
            gap: 0.5em;
        }

        .checkbox-nepal--input {
            appearance: none;
            background-color: #fff;
            margin: 0;
            font: inherit;
            color: currentColor;
            width: 1.15em;
            height: 1.15em;
            border: 0.15em solid #003893;
            border-radius: 0.15em;
            transform: translateY(-0.075em);
            display: grid;
            place-content: center;
        }

        .checkbox-nepal--input::before {
            content: "";
            width: 0.65em;
            height: 0.65em;
            transform: scale(0);
            transition: 120ms transform ease-in-out;
            box-shadow: inset 1em 1em #003893;
            transform-origin: center;
            clip-path: polygon(14% 44%, 0 65%, 50% 100%, 100% 16%, 80% 0%, 43% 62%);
        }

        .checkbox-nepal--input:checked::before {
            transform: scale(1);
        }

        .checkbox-nepal--input:focus {
            outline: max(2px, 0.15em) solid rgba(0, 56, 147, 0.2);
            outline-offset: max(2px, 0.15em);
        }
    </style>
@endpush

@section('content')
    <!-- Breadcrumb -->
    @include('components.breadcrumb', [
        'breadcrumbs' => [
            ['title' => 'Roles', 'url' => route('roles.index')],
            ['title' => 'Create Role', 'url' => null],
        ],
    ])

    <!-- Form Card -->
    <div class="">
        <div
            class="form-card bg-white dark:bg-gray-800 rounded-xl shadow-md overflow-hidden border border-gray-200 dark:border-gray-700">

            <div class="flex flex-col md:flex-row justify-between items-start md:items-center p-6">
                <div>
                    <h1 class="text-xl font-semibold text-gray-800 dark:text-white mb-2">Create Role</h1>
                </div>

                <div class="mt-4 md:mt-0">
                    <a href="{{ route('roles.index') }}"
                        class="bg-gray-200 hover:bg-gray-300 text-gray-700 dark:bg-gray-700 dark:hover:bg-gray-600 dark:text-gray-200 px-4 py-2 rounded-md shadow-sm flex items-center transition-colors">
                        <span class="iconify mr-2" data-icon="tabler:arrow-left" data-width="20"></span>
                        Back to Roles
                    </a>
                </div>
            </div>
            <!-- Form Content -->
            <form id="role-form" action="{{ route('roles.store') }}" method="POST">
                @csrf

                <div class="p-6">
                    <!-- Name Field -->
                    <div class="mb-6">
                        <label for="name" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                            Role Name <span class="text-red-500">*</span>
                        </label>
                        <div class="relative">
                            <span class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <span class="iconify text-gray-400" data-icon="tabler:user-shield" data-width="20"></span>
                            </span>
                            <input type="text" name="name" id="name" value="{{ old('name') }}" required
                                class="input-field w-full pl-10 pr-3 py-2 border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white rounded-md focus:outline-none"
                                placeholder="Enter role name (e.g., Editor, Viewer)">
                        </div>
                        @error('name')
                            <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                        @enderror
                        <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">Role name should be unique and descriptive
                            of the permissions it grants.</p>
                    </div>

                    <div class="mb-6">
                        <label for="level" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                            Role Level <span class="text-red-500">*</span>
                        </label>
                        <div class="relative">
                            <span class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <span class="iconify text-gray-400" data-icon="tabler:user-shield" data-width="20"></span>
                            </span>
                            <input type="number" min="2" name="level" id="level" value="{{ old('level') }}"
                                required
                                class="input-field w-full pl-10 pr-3 py-2 border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white rounded-md focus:outline-none"
                                placeholder="Enter role level (e.g., 2, 3)">
                        </div>
                        @error('level')
                            <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                        @enderror
                        <p class="mt-1 text-xs text-gray-500 dark:text-gray-400 level-info">Role level should be greater
                            than 1.</p>

                        <div class="hidden flex-col mt-2 text-xs text-gray-500 dark:text-gray-400" id="levelInfo">
                            <div class="same-level-container border p-2 mb-2">
                                <h4 class="text-sm font-semibold">Same Level</h4>
                                <div class="same-level-list"></div>
                            </div>
                            <div class="higher-level-container border p-2">
                                <h4 class="text-sm font-semibold">Higher Level</h4>
                                <div class="higher-level-list"></div>
                            </div>
                        </div>
                    </div>

                    <!-- Stay on Page Checkbox -->
                    <div class="mb-6">
                        <div class="checkbox-nepal">
                            <input type="checkbox" id="stay_on_page" name="stay_on_page" class="checkbox-nepal--input">
                            <label for="stay_on_page" class="text-sm font-medium text-gray-700 dark:text-gray-300">
                                Stay on this page after submission to create another role
                            </label>
                        </div>
                    </div>

                    <!-- Form Actions -->
                    <div class="flex flex-col sm:flex-row justify-end space-y-3 sm:space-y-0 sm:space-x-3 mt-8">
                        <button type="button" id="reset-form"
                            class="btn-secondary px-4 py-2 bg-gray-100 dark:bg-gray-700 text-gray-700 dark:text-gray-200 rounded-md border border-gray-300 dark:border-gray-600 flex items-center justify-center">
                            <span class="iconify mr-2" data-icon="tabler:refresh" data-width="20"></span>
                            Reset Form
                        </button>
                        <button type="submit"
                            class="btn-primary px-4 py-2 bg-nepal-blue text-white rounded-md flex items-center justify-center">
                            <span class="iconify mr-2" data-icon="tabler:device-floppy" data-width="20"></span>
                            Save Role
                        </button>
                    </div>
                </div>
            </form>
        </div>

        <!-- Tips Card -->
        <div class="bg-blue-50 dark:bg-blue-900/20 border border-blue-200 dark:border-blue-800 rounded-lg p-4 mt-6">
            <div class="flex">
                <div class="flex-shrink-0">
                    <span class="iconify text-blue-800 dark:text-blue-400" data-icon="tabler:info-circle"
                        data-width="24"></span>
                </div>
                <div class="ml-3">
                    <h3 class="text-sm font-medium text-blue-800 dark:text-blue-300">Tips for creating roles</h3>
                    <div class="mt-2 text-sm text-blue-700 dark:text-blue-400">
                        <ul class="list-disc pl-5 space-y-1">
                            <li>Use clear, descriptive names for roles</li>
                            <li>Consider the specific permissions each role will need</li>
                            <li>Avoid creating too many roles with overlapping permissions</li>
                            <li>System roles (Admin, Super Admin) cannot be modified or deleted</li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        $(document).ready(function() {
            $('#reset-form').on('click', function() {
                nepalConfirm.show({
                    title: 'Reset Form',
                    message: 'Are you sure you want to reset the form? All entered data will be lost.',
                    type: 'warning',
                    confirmText: 'Yes, Reset',
                    cancelText: 'Cancel',
                    confirmIcon: '<span class="iconify" data-icon="tabler:refresh" data-width="18"></span>'
                }).then(() => {
                    $('#role-form')[0].reset();
                    nepalToast.info('Form Reset', 'The form has been reset.');
                }).catch(() => {
                    nepalToast.nepal('Action Canceled', 'Form reset was canceled.');
                });
            });

            $('#role-form').on('submit', function(e) {
                e.preventDefault();

                const form = $(this);
                const stayOnPage = $('#stay_on_page').is(':checked');
                const formData = form.serialize();

                $.ajax({
                    url: form.attr('action'),
                    type: 'POST',
                    data: formData,
                    success: function(response) {
                        nepalToast.success('Success', 'Role created successfully!');
                        if (stayOnPage) {
                            $('#name').val('');
                            $('#name').focus();
                        } else {
                            setTimeout(function() {
                                window.location.href = "{{ route('roles.index') }}";
                            }, 1000);
                        }
                    },
                    error: function(xhr) {

                        if (xhr.status === 422) {
                            const errors = xhr.responseJSON.errors;
                            if (errors.name) {
                                nepalToast.error('Validation Error', errors.name[0]);
                            } else {
                                nepalToast.error('Error',
                                    'There was an error creating the role.');
                            }
                        } else {
                            nepalToast.error('Error', 'There was an error creating the role.');
                        }
                    }
                });
            });

            $('#level').on('input', function() {
                const inputLevel = parseInt($(this).val());
                if (inputLevel < 2 || isNaN(inputLevel)) {
                    $('.level-info').removeClass('text-gray-500').addClass('text-red-500');
                    $('#levelInfo').addClass('hidden').removeClass('flex');
                } else {
                    $('.level-info').addClass('text-gray-500').removeClass('text-red-500');
                    $('#levelInfo').removeClass('hidden').addClass('flex');
                    $.ajax({
                        url: "{{ route('roles.get.role') }}",
                        type: "POST",
                        data: {
                            'inputLevel': inputLevel,
                            _token: "{{ csrf_token() }}",
                        },
                        success: function(response) {
                            console.log(response.role);
                            const sameLevelContainer = $('#levelInfo .same-level-list');
                            const higherLevelContainer = $('#levelInfo .higher-level-list');

                            // Clear previous results
                            sameLevelContainer.empty();
                            higherLevelContainer.empty();

                            // Group roles by their level
                            const sameLevelRoles = [];
                            const higherLevelRoles = {};

                            response.role.forEach(role => {
                                if (role.level === inputLevel) {
                                    sameLevelRoles.push(role.name);
                                } else if (role.level > inputLevel) {
                                    if (!higherLevelRoles[role.level]) {
                                        higherLevelRoles[role.level] = [];
                                    }
                                    higherLevelRoles[role.level].push(role.name);
                                }
                            });

                            // Display same level roles
                            if (sameLevelRoles.length > 0) {
                                sameLevelContainer.append(
                                    `<div>${inputLevel} => ${sameLevelRoles.join(', ')}</div>`
                                );
                            }

                            // Display higher level roles
                            Object.keys(higherLevelRoles).forEach(level => {
                                higherLevelContainer.append(
                                    `<div>${level} => ${higherLevelRoles[level].join(', ')}</div>`
                                );
                            });

                            // Show the container if any roles were found
                            if (sameLevelRoles.length > 0 || Object.keys(higherLevelRoles)
                                .length > 0) {
                                $('#levelInfo').removeClass('hidden');
                            } else {
                                $('#levelInfo').addClass('hidden');
                            }
                        },

                        error: function(xhr) {
                            if (xhr.status === 422) {
                                const errors = xhr.responseJSON.errors;
                                console.log(errors);
                            }
                        },
                    })
                }
            })

        });
    </script>
@endpush
