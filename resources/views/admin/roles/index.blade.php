@extends('layouts.app')

@push('styles')
    <style>
        .role-card {
            transition: all 0.3s ease;
        }

        .role-card:hover {
            transform: translateY(-5px);
        }

        .animate-fade-in {
            animation: fadeIn 0.3s ease-out forwards;
        }

        @keyframes fadeIn {
            from {
                opacity: 0;
                transform: translateY(10px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
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
            background: linear-gradient(45deg, rgba(255, 255, 255, 0.2) 0%, rgba(255, 255, 255, 0) 100%);
            z-index: 1;
        }
    </style>
@endpush

@section('content')
    @include('components.breadcrumb', [
        'breadcrumbs' => [['title' => 'Role Management', 'url' => null]],
    ])
    <!-- Page Header -->
    <div class="flex flex-col md:flex-row justify-between items-center mb-6">
        <div class="flex flex-row gap-2 justify-center items-center">
            <span class="iconify text-nepal-blue" data-icon="cuida:users-outline" data-width="22"></span>
            <h1 class="text-2xl font-bold text-gray-800 dark:text-white mb-4 md:mb-0 relative pl-3">
                <span class="absolute left-0 top-1/2 transform -translate-y-1/2 w-0.5 h-6 bg-nepal-blue rounded"></span>
                Role Management
            </h1>
        </div>

        <div class="mt-4 md:mt-0 flex flex-col sm:flex-row space-y-3 sm:space-y-0 sm:space-x-3">
            <div class="relative">
                <input type="text" id="search-roles" placeholder="Search roles..."
                    class="pl-10 pr-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg shadow-sm focus:ring-nepal-blue focus:border-nepal-blue dark:bg-gray-800 dark:text-white">
                <span class="absolute left-3 top-2.5 text-gray-400">
                    <span class="iconify" data-icon="tabler:search" data-width="20"></span>
                </span>
            </div>

            @can('role-management-create')
                <a href="{{ route('roles.create') }}"
                    class="bg-nepal-blue hover:bg-nepal-blue/90 text-white px-4 py-2 rounded-md shadow-sm flex items-center justify-center transition-colors">
                    <span class="iconify mr-2" data-icon="tabler:plus" data-width="20"></span>
                    Create New Role
                </a>
            @endcan
        </div>
    </div>

    <!-- Roles Overview Cards -->
    @can('role-management-dashboard')
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 mb-6">
            <div
                class="bg-white dark:bg-gray-800 rounded-lg shadow-md p-6 border-l-4 border-purple-500 hover:shadow-lg transition-shadow">
                <div class="flex justify-between items-start">
                    <div>
                        <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Total Roles</p>
                        <h3 class="text-3xl font-bold text-gray-800 dark:text-white mt-1">
                            {{ count($roles) }}
                        </h3>
                        <p class="text-xs text-purple-600 mt-2 flex items-center">
                            {{-- <span class="iconify mr-1" data-icon="tabler:users" data-width="14"></span>
                            {{ Spatie\Permission\Models\Role::where('created_at','>=' , Carbon\Carbon::now()->subWeek())->count() }} created this week --}}
                        </p>
                    </div>
                    <div class="bg-purple-100 p-3 rounded-lg">
                        <span class="iconify text-purple-600" data-icon="tabler:users" data-width="24"></span>
                    </div>
                </div>
            </div>

            <div
                class="bg-white dark:bg-gray-800 rounded-lg shadow-md p-6 border-l-4 border-green-500 hover:shadow-lg transition-shadow">
                <div class="flex justify-between items-start">
                    <div>
                        <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Active Roles</p>
                        <h3 class="text-3xl font-bold text-gray-800 dark:text-white mt-1">
                            {{ Spatie\Permission\Models\Role::count() }}
                        </h3>
                        {{-- <p class="text-xs text-green-600 mt-2 flex items-center">
                            <span class="iconify mr-1" data-icon="tabler:arrow-up-right" data-width="14"></span>
                            18% increase this week
                        </p> --}}
                    </div>
                    <div class="bg-green-100 p-3 rounded-lg">
                        <span class="iconify text-green-600" data-icon="tabler:shield-check" data-width="24"></span>
                    </div>
                </div>
            </div>

            <div
                class="bg-white dark:bg-gray-800 rounded-lg shadow-md p-6 border-l-4 border-blue-500 hover:shadow-lg transition-shadow">
                <div class="flex justify-between items-start">
                    <div>
                        <p class="text-sm font-medium text-gray-500 dark:text-gray-400">System Roles</p>
                        <h3 class="text-3xl font-bold text-gray-800 dark:text-white mt-1">3</h3>
                        {{-- <p class="text-xs text-blue-600 mt-2 flex items-center">
                            <span class="iconify mr-1" data-icon="tabler:user-shield" data-width="14"></span>
                            3 System roles
                        </p> --}}
                    </div>
                    <div class="bg-blue-100 p-3 rounded-lg">
                        <span class="iconify text-blue-600" data-icon="tabler:user-shield" data-width="24"></span>
                    </div>
                </div>
            </div>
        </div>
    @endcan

    <!-- Success Message -->
    @if (session('success'))
        <div id="success-message" data-message="{{ session('success') }}" class="hidden"></div>
    @endif

    <!-- No Results Message -->
    <div id="no-results"
        class="hidden bg-yellow-100 border-l-4 border-yellow-500 text-yellow-700 p-4 mb-6 rounded-md shadow-sm dark:bg-yellow-900/30 dark:text-yellow-300 dark:border-yellow-500">
        <div class="flex items-center">
            <span class="iconify mr-2" data-icon="tabler:alert-circle" data-width="24"></span>
            <p>No roles found matching your search.</p>
        </div>
    </div>

    <!-- Roles Grid -->
    <div id="roles-grid" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
        @forelse($roles as $index => $role)
            <div class="role-card bg-white dark:bg-gray-800 rounded-xl shadow-md overflow-hidden border border-gray-200 dark:border-gray-700 hover:shadow-lg transition-shadow duration-300"
                data-name="{{ strtolower($role->name) }}" style="animation-delay: {{ $index * 0.05 }}s">

                <div class="p-6">
                    <div class="flex items-center justify-between mb-4">
                        <div class="flex items-center">
                            <div
                                class="role-badge shimme w-10 h-10 rounded-full flex items-center justify-center text-white font-bold bg-gradient-to-r from-nepal-blue to-blue-600">
                                {{ strtoupper(substr($role->name, 0, 2)) }}
                            </div>
                            <h3 class="ml-3 text-lg font-bold text-gray-900 dark:text-white">
                                {{ $role->name }}
                            </h3>
                        </div>

                        <div class="flex space-x-1">
                            @if ($role->name === 'Admin' || $role->name === 'Super Admin')
                                <span
                                    class="px-2 py-1 bg-purple-100 text-purple-800 dark:bg-purple-900/50 dark:text-purple-300 text-xs rounded-full">
                                    System
                                </span>
                            @endif
                        </div>
                    </div>

                    <div class="mt-2 text-sm text-gray-600 dark:text-gray-400">
                        <p>Created {{ \Carbon\Carbon::parse($role->created_at)->diffForHumans() }}</p>
                    </div>

                    <div class="mt-6 pt-4 border-t border-gray-200 dark:border-gray-700 flex justify-between">
                        <a href="{{ route('roles.show', $role) }}"
                            class="text-nepal-blue hover:text-nepal-blue/80 flex items-center text-sm font-medium">
                            <span class="iconify mr-1" data-icon="tabler:eye" data-width="16"></span>
                            View
                        </a>
                        @can('role-management-update')
                            <a href="{{ route('roles.edit', $role) }}"
                                class="text-indigo-600 hover:text-indigo-800 flex items-center text-sm font-medium">
                                <span class="iconify mr-1" data-icon="tabler:edit" data-width="16"></span>
                                Edit
                            </a>
                        @endcan
                        @can('role-management-delete')
                            <form action="{{ route('roles.destroy', $role) }}" method="POST" class="inline delete-role-form">
                                @csrf
                                @method('DELETE')
                                <button type="button"
                                    class="delete-role-btn text-red-600 hover:text-red-800 flex items-center text-sm font-medium">
                                    <span class="iconify mr-1" data-icon="tabler:trash" data-width="16"></span>
                                    Delete
                                </button>
                            </form>
                        @endcan
                    </div>
                </div>
            </div>
        @empty
            <div class="col-span-full bg-gray-100 dark:bg-gray-800 rounded-lg p-8 text-center">
                <div class="flex flex-col items-center justify-center">
                    <div class="bg-gray-200 dark:bg-gray-700 rounded-full p-3 mb-4">
                        <span class="iconify text-gray-500 dark:text-gray-400" data-icon="tabler:user-shield"
                            data-width="48"></span>
                    </div>
                    <h3 class="text-lg font-medium text-gray-900 dark:text-white mb-1">No roles found</h3>
                    <p class="text-gray-500 dark:text-gray-400 mb-4">Get started by creating a new role</p>
                    <a href="{{ route('roles.create') }}"
                        class="bg-nepal-blue hover:bg-nepal-blue/90 text-white px-4 py-2 rounded-lg shadow-sm flex items-center justify-center transition-colors">
                        <span class="iconify mr-2" data-icon="tabler:plus" data-width="20"></span>
                        Create New Role
                    </a>
                </div>
            </div>
        @endforelse
    </div>
@endsection

@push('scripts')
    <script>
        $(document).ready(function() {
            // Add animation to cards
            $(".role-card").each(function() {
                $(this).addClass('animate-fade-in');
            });

            // Show success toast if there's a success message
            const successMessage = $('#success-message').data('message');
            if (successMessage) {
                nepalToast.success('Success', successMessage);
            }

            // Search functionality
            $("#search-roles").on("keyup", function() {
                var value = $(this).val().toLowerCase();
                var visibleCards = 0;

                $(".role-card").each(function() {
                    var name = $(this).data("name");
                    var matchFound = name.indexOf(value) > -1;

                    if (matchFound) {
                        $(this).removeClass("hidden");
                        visibleCards++;
                    } else {
                        $(this).addClass("hidden");
                    }
                });

                // Show or hide the "no results" message
                if (visibleCards === 0 && value !== "") {
                    $("#no-results").removeClass("hidden");
                } else {
                    $("#no-results").addClass("hidden");
                }
            });

            // Custom confirmation for delete
            $(".delete-role-btn").on("click", function(e) {
                e.preventDefault();
                const form = $(this).closest('form');
                const roleName = $(this).closest('.role-card').find('h3').text().trim();

                nepalConfirm.show({
                    title: 'Delete Role',
                    message: `Are you sure you want to delete <strong>${roleName}</strong>? This action cannot be undone.`,
                    type: 'danger',
                    confirmText: 'Delete Role',
                    cancelText: 'Cancel',
                    confirmIcon: '<span class="iconify" data-icon="tabler:trash" data-width="18"></span>'
                }).then(() => {
                    // Submit the form if confirmed
                    form.submit();

                    // Show a toast notification
                    nepalToast.info('Processing', 'Deleting role...');
                }).catch(() => {
                    // Show a toast notification if canceled
                    nepalToast.error('Action Canceled', 'Role deletion was canceled.');
                });
            });
        });
    </script>
@endpush
