@extends('layouts.app')

@push('styles')
    <style>
        .role-card {
            transition: all 0.3s ease;
        }

        .role-card:hover {
            box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1), 0 4px 6px -2px rgba(0, 0, 0, 0.05);
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
    </style>
@endpush

@section('content')
    <!-- Breadcrumb -->
    @include('components.breadcrumb', [
        'breadcrumbs' => [
            ['title' => 'Roles', 'url' => route('roles.index')],
            ['title' => $role->name, 'url' => null],
        ],
    ])

    <!-- Page Header -->
    <div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-6">
        <div class="flex flex-row gap-2 justify-center items-center">
            <span class="iconify text-nepal-blue" data-icon="tabler:users" data-width="22"></span>
            <h1 class="text-2xl font-bold text-gray-800 dark:text-white mb-4 md:mb-0 relative pl-3">
                <span class="absolute left-0 top-1/2 transform -translate-y-1/2 w-0.5 h-6 bg-nepal-blue rounded"></span>
                Role Details
            </h1>
        </div>

        <div class="mt-4 md:mt-0 flex flex-col sm:flex-row space-y-3 sm:space-y-0 sm:space-x-3">
            <a href="{{ route('roles.index') }}"
                class="bg-gray-200 hover:bg-gray-300 text-gray-700 dark:bg-gray-700 dark:hover:bg-gray-600 dark:text-gray-200 px-4 py-2 rounded-lg shadow-sm flex items-center justify-center transition-colors">
                <span class="iconify mr-2" data-icon="tabler:arrow-left" data-width="20"></span>
                Back to Roles
            </a>

            @can('role-management-update')
                <a href="{{ route('roles.edit', $role) }}"
                    class="bg-indigo-600 hover:bg-indigo-700 text-white px-4 py-2 rounded-lg shadow-sm flex items-center justify-center transition-colors">
                    <span class="iconify mr-2" data-icon="tabler:edit" data-width="20"></span>
                    Edit Role
                </a>
            @endcan

            @can('role-management-permissions-manage')
                <a href="{{ route('roles.permissions', $role) }}"
                    class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-lg shadow-sm flex items-center justify-center transition-colors">
                    <span class="iconify mr-2" data-icon="tabler:lock" data-width="20"></span>
                    Manage Permissions
                </a>
            @endcan
            @can('role-management-delete')
                <button type="button" id="delete-role-btn"
                    class="bg-red-600 hover:bg-red-700 text-white px-4 py-2 rounded-lg shadow-sm flex items-center justify-center transition-colors">
                    <span class="iconify mr-2" data-icon="tabler:trash" data-width="20"></span>
                    Delete Role
                </button>

                <form id="delete-role-form" action="{{ route('roles.destroy', $role) }}" method="POST" class="hidden">
                    @csrf
                    @method('DELETE')
                </form>
            @endcan
        </div>
    </div>

    <!-- Role Details Card -->
    <div class="">
        <div
            class="role-card bg-white dark:bg-gray-800 rounded-xl shadow-md overflow-hidden border border-gray-200 dark:border-gray-700">
            <!-- Card Header -->
            <div class="relative bg-gradient-to-r from-nepal-blue/90 to-nepal-blue/70 p-6 text-white">
                <div class="flex items-center">
                    <div
                        class="role-badge w-16 h-16 rounded-full flex items-center justify-center bg-white text-nepal-blue text-xl font-bold mr-4">
                        {{ strtoupper(substr($role->name, 0, 2)) }}
                    </div>
                    <div>
                        <h2 class="text-2xl font-bold">{{ $role->name }}</h2>
                        <div class="flex items-center mt-1">
                            @if (in_array($role->name, ['Admin', 'Super Admin']))
                                <span class="px-2 py-1 bg-purple-100 text-purple-800 text-xs rounded-full mr-2">
                                    System Role
                                </span>
                            @endif
                            <span class="text-blue-100 text-sm">Created
                                {{ \Carbon\Carbon::parse($role->created_at)->diffForHumans() }}</span>
                        </div>
                    </div>
                </div>
                <div
                    class="absolute inset-0 opacity-10 bg-[url('data:image/svg+xml;base64,PHN2ZyB3aWR0aD0iNjAiIGhlaWdodD0iNjAiIHZpZXdCb3g9IjAgMCA2MCA2MCIgeG1sbnM9Imh0dHA6Ly93d3cudzMub3JnLzIwMDAvc3ZnIj48ZyBmaWxsPSJ3aGl0ZSIgZmlsbC1ydWxlPSJldmVub2RkIj48cGF0aCBkPSJNMzYgMzRoLTJ2LTRoLTR2LTJoNHYtNGgydjRoNHYyaC00djR6TTMwIDZoMnYyaC0yVjZ6bTAgNGgydjJoLTJWMTB6bTAgMThoMnYyaC0ydi0yek0zNCA2aDJ2MmgtMlY2em0wIDRoMnYyaC0yVjEwem0wIDE4aDJ2MmgtMnYtMnpNMzggNmgydjJoLTJWNnptMCA0aDJ2MmgtMlYxMHptMCAxOGgydjJoLTJ2LTJ6TTMwIDI2aDJ2MmgtMnYtMnptNCAwaDF2MmgtMXYtMnptNCAwaDJ2MmgtMnYtMnoiLz48L2c+PC9zdmc+')]">
                </div>
            </div>

            <!-- Role Information -->
            <div class="p-6">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Basic Information -->
                    <div class="bg-gray-50 dark:bg-gray-700/50 p-5 rounded-lg">
                        <h3 class="text-lg font-semibold text-gray-800 dark:text-white mb-4 flex items-center">
                            <span class="iconify mr-2" data-icon="tabler:info-circle" data-width="20"></span>
                            Basic Information
                        </h3>

                        <div class="space-y-4">
                            <div>
                                <h4 class="text-sm font-medium text-gray-500 dark:text-gray-400">Role Name</h4>
                                <p class="mt-1 text-gray-800 dark:text-gray-200">{{ $role->name }}</p>
                            </div>

                            <div>
                                <h4 class="text-sm font-medium text-gray-500 dark:text-gray-400">Role ID</h4>
                                <p class="mt-1 text-gray-800 dark:text-gray-200">{{ $role->id }}</p>
                            </div>

                            <div>
                                <h4 class="text-sm font-medium text-gray-500 dark:text-gray-400">Created At</h4>
                                <span class="mt-1 text-gray-800 dark:text-gray-200 nepali-date"
                                    data-eng-date="{{ \Carbon\Carbon::parse($role->created_at)->format('Y-m-d') }}"></span>&nbsp;&nbsp;<span>{{ \Carbon\Carbon::parse($role->created_at)->format('g:i A') }}</span>
                            </div>

                            <div>
                                <h4 class="text-sm font-medium text-gray-500 dark:text-gray-400">Last Updated</h4>
                                <span class="mt-1 text-gray-800 dark:text-gray-200 nepali-date"
                                    data-eng-date="{{ \Carbon\Carbon::parse($role->updated_at)->format('Y-m-d') }}"></span>&nbsp;&nbsp;<span>{{ \Carbon\Carbon::parse($role->updated_at)->format('g:i A') }}</span>
                            </div>
                        </div>
                    </div>

                    <!-- Role Status -->
                    <div class="bg-gray-50 dark:bg-gray-700/50 p-5 rounded-lg">
                        <h3 class="text-lg font-semibold text-gray-800 dark:text-white mb-4 flex items-center">
                            <span class="iconify mr-2" data-icon="tabler:shield" data-width="20"></span>
                            Role Status
                        </h3>

                        <div class="space-y-4">
                            <div>
                                <h4 class="text-sm font-medium text-gray-500 dark:text-gray-400">Type</h4>
                                <p class="mt-1 flex items-center">
                                    @if (in_array($role->name, ['Admin', 'Super Admin']))
                                        <span
                                            class="px-2 py-1 bg-purple-100 dark:bg-purple-900/50 text-purple-800 dark:text-purple-300 text-xs rounded-full">
                                            System Role
                                        </span>
                                    @else
                                        <span
                                            class="px-2 py-1 bg-green-100 dark:bg-green-900/50 text-green-800 dark:text-green-300 text-xs rounded-full">
                                            Custom Role
                                        </span>
                                    @endif
                                </p>
                            </div>

                            <div>
                                <h4 class="text-sm font-medium text-gray-500 dark:text-gray-400">Status</h4>
                                <p class="mt-1 flex items-center">
                                    <span
                                        class="px-2 py-1 bg-green-100 dark:bg-green-900/50 text-green-800 dark:text-green-300 text-xs rounded-full">
                                        Active
                                    </span>
                                </p>
                            </div>

                            <div>
                                <h4 class="text-sm font-medium text-gray-500 dark:text-gray-400">Editable</h4>
                                <p class="mt-1 flex items-center">
                                    @if (in_array($role->name, ['Admin', 'Super Admin']))
                                        <span
                                            class="px-2 py-1 bg-red-100 dark:bg-red-900/50 text-red-800 dark:text-red-300 text-xs rounded-full">
                                            No
                                        </span>
                                    @else
                                        <span
                                            class="px-2 py-1 bg-green-100 dark:bg-green-900/50 text-green-800 dark:text-green-300 text-xs rounded-full">
                                            Yes
                                        </span>
                                    @endif
                                </p>
                            </div>
                        </div>
                    </div>
                </div>

                @can('admin-management-permissions-view')
                    <!-- Roles Permission -->
                    <div
                        class="mt-6 bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700">
                        <div class="p-5">
                            <h3 class="text-lg font-semibold text-gray-800 dark:text-white flex items-center">
                                <span class="iconify mr-2 text-primary-600 dark:text-primary-400" data-icon="tabler:users"
                                    data-width="20"></span>
                                Roles Permissions
                            </h3>
                        </div>

                        <div class="mb-6 px-5 space-y-6">
                            @foreach ($permissionsGroup as $group => $permissions)
                                <div class="permission-group-container rounded-lg border border-gray-200 dark:border-gray-700 overflow-hidden"
                                    data-group="{{ $group }}">
                                    <div
                                        class="flex items-center justify-between p-4 bg-gray-50 dark:bg-gray-700/50 cursor-pointer group-toggle">
                                        <h3 class="text-base font-medium text-gray-800 dark:text-white flex items-center">
                                            <span class="iconify mr-2 text-gray-500 dark:text-gray-400"
                                                data-icon="tabler:folder" data-width="20"></span>
                                            {{ ucwords(str_replace('-', ' ', $group)) }}
                                        </h3>
                                        <span class="iconify transform transition-transform group-expanded:rotate-180"
                                            data-icon="tabler:chevron-down" data-width="20"></span>
                                    </div>

                                    <div class="p-4 group-content hidden">
                                        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                                            @foreach ($permissions as $permission)
                                                <div class="permission-item relative p-4 rounded-lg border border-gray-200 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-700/50 transition-colors"
                                                    data-name="{{ strtolower($permission->name) }}"
                                                    data-action="{{ substr(strrchr($permission->name, '-'), 1) }}">
                                                    <label class="permission-checkbox flex items-start cursor-pointer">

                                                        <div class="ml-3 text-sm">
                                                            <span class="font-medium text-gray-700 dark:text-gray-300">
                                                                {{ ucwords(str_replace('-', ' ', $permission->name)) }}
                                                            </span>
                                                            <p class="text-gray-500 dark:text-gray-400 mt-1">
                                                                {{ $permission->description }}
                                                            </p>
                                                        </div>
                                                    </label>
                                                </div>
                                            @endforeach
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endcan
                @can('admin-management-users')
                    <!-- Users with this Role -->
                    <div class="mt-6 bg-gray-50 dark:bg-gray-700/50 p-5 rounded-lg cursor-pointer">
                        <h3 class="text-lg font-semibold text-gray-800 dark:text-white mb-4 flex items-center">
                            <span class="iconify mr-2" data-icon="tabler:users" data-width="20"></span>
                            Users with this Role
                        </h3>

                        <div class="overflow-x-auto grid grid-cols-2 gap-4">
                            @forelse($role->users as $user)
                                <div class="user-card bg-white dark:bg-gray-800 rounded-xl shadow-md overflow-hidden border border-gray-200 dark:border-gray-700 hover:shadow-lg transition-shadow duration-300"
                                    data-name="{{ strtolower($user->name) }}" data-email="{{ strtolower($user->email) }}"
                                    data-ministry="{{ strtolower($user->ministry->name ?? '') }}">

                                    <div class="p-6">
                                        <div class="flex items-center mb-4">
                                            <div
                                                class="shimmer w-12 h-12 rounded-full flex items-center justify-center text-white font-bold bg-gradient-to-r from-nepal-blue to-blue-600">
                                                @php
                                                    $image = null;
                                                    $uploadBaseUrl = asset('uploads/users') . '/';
                                                @endphp

                                                @if (optional($user->description)->profile)
                                                    @php
                                                        $url = $uploadBaseUrl . $user->description->profile;
                                                    @endphp
                                                    <div class="flex justify-start items-center gap-3">
                                                        <div class="preview_image">
                                                            <a href="{{ $url }}" target="_blank">
                                                                <img src="{{ $url }}" alt="{{ $user->name }}"
                                                                    class="w-12 h-12 rounded-md">
                                                            </a>
                                                        </div>
                                                    </div>
                                                @else
                                                    {{ strtoupper(substr($user->name, 0, 2)) }}
                                                @endif
                                            </div>
                                            <div class="ml-4">
                                                <h3 class="text-lg font-bold text-gray-900 dark:text-white">
                                                    {{ $user->name }}
                                                </h3>
                                                <p class="text-sm text-gray-500 dark:text-gray-400">{{ $user->email }}
                                                </p>
                                            </div>
                                        </div>
                                        <div class="space-y-3 mb-4">
                                            <div class="flex items-start">
                                                <span class="iconify text-gray-500 dark:text-gray-400 mt-0.5 mr-2"
                                                    data-icon="tabler:phone" data-width="16"></span>
                                                <span
                                                    class="text-sm text-gray-700 dark:text-gray-300">{{ $user->phone ?? 'N/A' }}</span>
                                            </div>
                                            <div class="flex items-start">
                                                <span class="iconify text-gray-500 dark:text-gray-400 mt-0.5 mr-2"
                                                    data-icon="mdi:building" data-width="16"></span>
                                                <span class="text-sm text-gray-700 dark:text-gray-300">
                                                    {{ $user->ministry->name ?? 'N/A' }}
                                                </span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @empty
                                <div class="col-span-full bg-gray-100 dark:bg-gray-800 rounded-lg p-8 text-center">
                                    <div class="flex flex-col items-center justify-center">
                                        <div class="bg-gray-200 dark:bg-gray-700 rounded-full p-3 mb-4">
                                            <span class="iconify text-gray-500 dark:text-gray-400" data-icon="tabler:users"
                                                data-width="48"></span>
                                        </div>
                                        <h3 class="text-lg font-medium text-gray-900 dark:text-white mb-1">No users found
                                        </h3>
                                    </div>
                            @endforelse
                        </div>
                    </div>
                @endcan

            </div>

            <!-- Card Footer -->
            <div
                class="bg-gray-50 dark:bg-gray-800 border-t border-gray-200 dark:border-gray-700 px-6 py-4 flex justify-between items-center">
                <a href="{{ route('roles.index') }}"
                    class="text-gray-600 hover:text-gray-900 dark:text-gray-300 dark:hover:text-white flex items-center">
                    <span class="iconify mr-2" data-icon="tabler:arrow-left" data-width="18"></span>
                    Back to Roles
                </a>

                <div class="text-sm text-gray-500 dark:text-gray-400">
                    Last updated: {{ \Carbon\Carbon::parse($role->updated_at)->diffForHumans() }}
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const toggles = document.querySelectorAll('.group-toggle');
            toggles.forEach(toggle => {
                toggle.addEventListener('click', function() {
                    const container = this.closest('.permission-group-container');
                    const content = container.querySelector('.group-content');
                    const icon = this.querySelector('.iconify[data-icon="tabler:chevron-down"]');

                    content.classList.toggle('hidden');
                    if (!content.classList.contains('hidden')) {
                        icon.style.transform = 'rotate(180deg)';
                    } else {
                        icon.style.transform = 'rotate(0deg)';
                    }
                });
            });
        });

        $(document).ready(function() {
            // Delete role button handler
            $("#delete-role-btn").on("click", function(e) {
                e.preventDefault();

                @if (in_array($role->name, ['Admin', 'Super Admin']))
                    nepalToast.error('Error', 'System roles cannot be deleted.');
                    return false;
                @endif

                nepalConfirm.show({
                    title: 'Delete Role',
                    message: `Are you sure you want to delete <strong>{{ $role->name }}</strong>? This action cannot be undone.`,
                    type: 'danger',
                    confirmText: 'Delete Role',
                    cancelText: 'Cancel',
                    confirmIcon: '<span class="iconify" data-icon="tabler:trash" data-width="18"></span>'
                }).then(() => {
                    // Submit the form if confirmed
                    $("#delete-role-form").submit();

                    // Show a toast notification
                    nepalToast.info('Processing', 'Deleting role...');
                }).catch(() => {
                    // Show a toast notification if canceled
                    nepalToast.nepal('Action Canceled', 'Role deletion was canceled.');
                });
            });
        });
    </script>
@endpush
