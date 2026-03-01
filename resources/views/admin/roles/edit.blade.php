@extends('layouts.app')

@push('styles')
<style>
    .form-card {
        transition: all 0.3s ease;
    }

    .form-card:hover {
        box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1), 0 4px 6px -2px rgba(0, 0, 0, 0.05);
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
        background: linear-gradient(45deg, rgba(255,255,255,0.2) 0%, rgba(255,255,255,0) 100%);
        z-index: 1;
    }
</style>
@endpush

@section('content')

    <!-- Breadcrumb -->
    @include('components.breadcrumb', [
        'breadcrumbs' => [
            ['title' => 'Roles', 'url' => route('roles.index')],
            ['title' => 'Edit Role', 'url' => null]
        ]
    ])

    <!-- Page Header -->
    <div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-6">
        <div>
            <h1 class="text-2xl font-bold text-gray-800 dark:text-white mb-2">
                <span class="border-b-4 border-nepal-blue pb-1">Edit Role</span>
            </h1>
        </div>

        <div class="mt-4 md:mt-0">
            <a href="{{ route('roles.index') }}"
                class="bg-gray-300  hover:bg-gray-300 text-gray-700 dark:bg-gray-700 dark:hover:bg-gray-600 dark:text-gray-200 px-4 py-2 rounded-md shadow-sm flex items-center transition-colors">
                <span class="iconify mr-2" data-icon="tabler:arrow-left" data-width="20"></span>
                Back to Roles
            </a>
        </div>
    </div>

    <!-- Form Card -->
    <div class="">
        <div class="relative form-card bg-white dark:bg-gray-800 rounded-xl shadow-md overflow-hidden border border-gray-200 dark:border-gray-700">
            <!-- Card Header -->
            <div class="relative bg-gradient-to-r from-nepal-blue/90 to-nepal-blue/70 p-6 text-white">
                <div class="flex items-center">
                    <div class="role-badge w-10 h-10 rounded-full flex items-center justify-center bg-white text-nepal-blue font-bold mr-3">
                        {{ strtoupper(substr($role->name, 0, 2)) }}
                    </div>
                    <div>
                        <h2 class="text-xl font-bold">Edit Role: {{ $role->name }}</h2>
                        <p class="text-blue-100 text-sm">Created {{ \Carbon\Carbon::parse($role->created_at)->diffForHumans() }}</p>
                    </div>
                </div>
                <div
                class="absolute inset-0 opacity-10 bg-[url('data:image/svg+xml;base64,PHN2ZyB3aWR0aD0iNjAiIGhlaWdodD0iNjAiIHZpZXdCb3g9IjAgMCA2MCA2MCIgeG1sbnM9Imh0dHA6Ly93d3cudzMub3JnLzIwMDAvc3ZnIj48ZyBmaWxsPSJ3aGl0ZSIgZmlsbC1ydWxlPSJldmVub2RkIj48cGF0aCBkPSJNMzYgMzRoLTJ2LTRoLTR2LTJoNHYtNGgydjRoNHYyaC00djR6TTMwIDZoMnYyaC0yVjZ6bTAgNGgydjJoLTJWMTB6bTAgMThoMnYyaC0ydi0yek0zNCA2aDJ2MmgtMlY2em0wIDRoMnYyaC0yVjEwem0wIDE4aDJ2MmgtMnYtMnpNMzggNmgydjJoLTJWNnptMCA0aDJ2MmgtMlYxMHptMCAxOGgydjJoLTJ2LTJ6TTMwIDI2aDJ2MmgtMnYtMnptNCAwaDF2MmgtMXYtMnptNCAwaDJ2MmgtMnYtMnoiLz48L2c+PC9zdmc+')]">
            </div>
            </div>


            <!-- Form Content -->
            <form id="role-form" action="{{ route('roles.update', $role) }}" method="POST">
                @csrf
                @method('PUT')

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
                            <input type="text" name="name" id="name" value="{{ old('name', $role->name) }}" required
                                class="input-field w-full pl-10 pr-3 py-2 border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white rounded-md focus:outline-none"
                                placeholder="Enter role name (e.g., Editor, Viewer)"
                                {{ in_array($role->name, ['Admin', 'Super Admin']) ? 'disabled' : '' }}>
                        </div>
                        @error('name')
                            <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                        @enderror
                        <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">Role name should be unique and descriptive of the permissions it grants.</p>
                    </div>

                     <div class="mb-6">
                        <label for="level" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                            Role Level <span class="text-red-500">*</span>
                        </label>
                        <div class="relative">
                            <span class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <span class="iconify text-gray-400" data-icon="tabler:user-shield" data-width="20"></span>
                            </span>
                            <input type="number" min="2" name="level" id="level" value="{{ old('level',$role->level) }}"
                                required
                                class="input-field w-full pl-10 pr-3 py-2 border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white rounded-md focus:outline-none"
                                placeholder="Enter role level (e.g., 2, 3)" {{ in_array($role->name, ['Admin', 'Super Admin']) ? 'disabled' : '' }}>
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

                    @if(in_array($role->name, ['Admin', 'Super Admin']))
                        <div class="mb-6 bg-yellow-50 dark:bg-yellow-900/20 border border-yellow-200 dark:border-yellow-800 rounded-lg p-4">
                            <div class="flex">
                                <div class="flex-shrink-0">
                                    <span class="iconify text-yellow-800 dark:text-yellow-400" data-icon="tabler:alert-triangle" data-width="24"></span>
                                </div>
                                <div class="ml-3">
                                    <h3 class="text-sm font-medium text-yellow-800 dark:text-yellow-300">System Role</h3>
                                    <div class="mt-2 text-sm text-yellow-700 dark:text-yellow-400">
                                        This is a system role and cannot be modified or deleted. System roles are essential for the proper functioning of the application.
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endif

                    <!-- Form Actions -->
                    <div class=" flex flex-col sm:flex-row justify-end space-y-3 sm:space-y-0 sm:space-x-3 mt-8">
                        <button type="button" id="cancel-edit"
                            class="btn-secondary px-4 py-2 bg-gray-100 dark:bg-gray-700 text-gray-700 dark:text-gray-200 rounded-md border border-gray-300 dark:border-gray-600 flex items-center justify-center">
                            <span class="iconify mr-2" data-icon="tabler:x" data-width="20"></span>
                            Cancel
                        </button>
                        <button type="submit"
                            class="btn-primary px-4 py-2 bg-nepal-blue text-white rounded-md flex items-center justify-center"
                            {{ in_array($role->name, ['Admin', 'Super Admin']) ? 'disabled' : '' }}>
                            <span class="iconify mr-2" data-icon="tabler:device-floppy" data-width="20"></span>
                            Update Role
                        </button>
                    </div>
                </div>
            </form>
        </div>

        <!-- Last Updated Info -->
        <div class="text-right mt-4 text-sm text-gray-500 dark:text-gray-400">
            Last updated: {{ \Carbon\Carbon::parse($role->updated_at)->format('M d, Y H:i') }}
        </div>
    </div>

@endsection

@push('scripts')
<script>
    $(document).ready(function() {
        // Cancel button
        $('#cancel-edit').on('click', function() {
            nepalConfirm.show({
                title: 'Cancel Editing',
                message: 'Are you sure you want to cancel? Any unsaved changes will be lost.',
                type: 'warning',
                confirmText: 'Yes, Cancel',
                cancelText: 'No, Continue Editing',
                confirmIcon: '<span class="iconify" data-icon="tabler:x" data-width="18"></span>'
            }).then(() => {
                // Redirect back to roles index
                window.location.href = "{{ route('roles.index') }}";
            }).catch(() => {
                // Show a toast notification if canceled
                nepalToast.nepal('Action Canceled', 'You can continue editing the form.');
            });
        });

        // Form submission
        $('#role-form').on('submit', function(e) {
            @if(in_array($role->name, ['Admin', 'Super Admin']))
                e.preventDefault();
                nepalToast.error('Error', 'System roles cannot be modified.');
                return false;
            @endif
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
