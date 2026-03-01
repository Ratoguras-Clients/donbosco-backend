@extends('layouts.app')

@push('styles')
    <style>
        .user-card {
            transition: all 0.3s ease;
        }

        .user-card:hover {
            transform: translateY(-5px);
        }

        .animate-fade-in {
            animation: fadeIn 0.3s ease-out forwards;
            opacity: 0;
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
            transition: all 0.2s ease;
        }

        .role-badge:hover {
            transform: translateY(-2px);
        }

        .shimmer {
            position: relative;
            overflow: hidden;
        }

        .shimmer::after {
            content: '';
            position: absolute;
            top: -50%;
            left: -50%;
            width: 200%;
            height: 200%;
            background: linear-gradient(to right,
                    rgba(255, 255, 255, 0) 0%,
                    rgba(255, 255, 255, 0.3) 50%,
                    rgba(255, 255, 255, 0) 100%);
            transform: rotate(30deg);
            animation: shimmer 2s infinite;
        }

        @keyframes shimmer {
            0% {
                transform: translateX(-100%) rotate(30deg);
            }

            100% {
                transform: translateX(100%) rotate(30deg);
            }
        }
    </style>
@endpush

@section('content')
    @include('components.breadcrumb', [
        'breadcrumbs' => [['title' => 'Users', 'url' => null]],
    ])
    <!-- Page Header -->
    <div class="flex flex-col md:flex-row justify-between items-center mb-6">
        <div class="flex flex-row gap-2 justify-center items-center">
            <span class="iconify text-nepal-blue" data-icon="mdi:user-outline" data-width="24"></span>
            <h1 class="text-2xl font-bold text-gray-800 dark:text-white mb-4 md:mb-0 relative pl-3">
                <span class="absolute left-0 top-1/2 transform -translate-y-1/2 w-0.5 h-6 bg-nepal-blue rounded"></span>
                User Dashboard
            </h1>
        </div>

        <div class="mt-4 md:mt-0 flex flex-col sm:flex-row space-y-3 sm:space-y-0 sm:space-x-3">
            <div class="relative">
                <input type="text" id="search-users" placeholder="Search users..."
                    class="pl-10 pr-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg shadow-sm focus:ring-nepal-blue focus:border-nepal-blue dark:bg-gray-800 dark:text-white">
                <span class="absolute left-3 top-2.5 text-gray-400">
                    <span class="iconify" data-icon="tabler:search" data-width="20"></span>
                </span>
            </div>

            @can('user-management-create')
                <a href="{{ route('users.create') }}"
                    class="bg-green-500 hover:bg-green-600/90 text-white px-4 py-2 rounded-lg shadow-sm flex items-center justify-center transition-colors">
                    <span class="iconify mr-2" data-icon="tabler:plus" data-width="20"></span>
                    Add New User
                </a>
            @endcan


        </div>
    </div>

    <!-- Success Message -->
    @if (session('success'))
        <div id="success-message" data-message="{{ session('success') }}" class="hidden"></div>
    @endif

    <!-- No Results Message -->
    <div id="no-results"
        class="hidden bg-yellow-100 border-l-4 border-yellow-500 text-yellow-700 p-4 mb-6 rounded-md shadow-sm dark:bg-yellow-900/30 dark:text-yellow-300 dark:border-yellow-500">
        <div class="flex items-center">
            <span class="iconify mr-2" data-icon="tabler:alert-circle" data-width="24"></span>
            <p>No users found matching your search.</p>
        </div>
    </div>

    <!-- Users Grid -->
    <div id="users-grid" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
        @forelse($users as $index => $user)
            <div class="border-b-2 cursor-pointer border-b-nepal-dark user-card bg-white dark:bg-gray-800 rounded-xl shadow-md overflow-hidden border border-gray-200 dark:border-gray-700 hover:shadow-lg transition-shadow duration-300"
                data-name="{{ strtolower($user->name) }}" data-email="{{ strtolower($user->email) }}"
                data-ministry="{{ strtolower($user->ministry->name ?? '') }}" style="animation-delay: {{ $index * 0.05 }}s">

                <div class="p-4 flex flex-col h-full">
                    <!-- User Info -->
                    <div>
                        <div class="flex gap-2 items-start mb-4">
                            <div
                                class="min-w-14 min-h-14 rounded-md flex items-center justify-center text-white font-bold bg-gradient-to-r from-nepal-blue to-blue-600">
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
                                                    class="max-w-14 max-h-14 rounded-md">
                                            </a>
                                        </div>
                                    </div>
                                @else
                                    <div class="flex justify-start items-center gap-3">
                                        <div
                                            class="min-w-14 min-h-14 rounded-md flex items-center justify-center text-white font-bold bg-gradient-to-r from-nepal-blue to-blue-600">
                                            {{ strtoupper(substr($user->name, 0, 2)) }}
                                        </div>
                                    </div>
                                @endif
                            </div>
                            <div class="">
                                <h3 class="text-lg font-bold text-gray-900 dark:text-white leading-tight">
                                    {{ $user->name }}
                                </h3>
                                <p class="text-sm text-gray-500 dark:text-gray-400">{{ $user->email }}</p>
                            </div>
                        </div>

                        <div class="space-y-3 mb-4">
                            <div class="flex items-start">
                                <span class="iconify text-gray-500 dark:text-gray-400 mt-0.5 mr-2" data-icon="tabler:phone"
                                    data-width="16"></span>
                                <span class="text-sm text-gray-700 dark:text-gray-300">{{ $user->phone ?? 'N/A' }}</span>
                            </div>

                            <div class="flex items-start">
                                <span class="iconify text-gray-500 dark:text-gray-400 mt-0.5 mr-2"
                                    data-icon="tabler:building" data-width="16"></span>
                                <span
                                    class="text-sm text-gray-700 dark:text-gray-300">{{ $user->ministry->name ?? 'N/A' }}</span>
                            </div>
                        </div>

                        <div class="mb-4">
                            <p class="text-xs text-gray-500 dark:text-gray-400 mb-2">Roles:</p>
                            <div class="flex flex-wrap gap-2">
                                @forelse($user->roles as $role)
                                    <span
                                        class="role-badge px-2 py-1 text-xs rounded-full bg-blue-100 dark:bg-blue-900/50 text-blue-800 dark:text-blue-300">
                                        {{ $role->name }}
                                    </span>
                                @empty
                                    <span class="text-xs text-gray-500 dark:text-gray-400 italic">No roles assigned</span>
                                @endforelse
                            </div>
                        </div>
                    </div>

                    <!-- Sticky Footer Buttons -->
                    <div class="mt-auto border-t border-gray-200 dark:border-gray-700 pt-4">
                        <div class="flex justify-between w-full">
                            <a href="{{ route('users.show', $user) }}"
                                class="text-nepal-blue hover:text-nepal-blue/80 flex items-center text-sm font-medium">
                                <span class="iconify mr-1" data-icon="tabler:eye" data-width="16"></span>
                                View
                            </a>
                            @can('user-management-update')
                                <a href="{{ route('users.edit', $user) }}"
                                    class="text-indigo-600 hover:text-indigo-800 flex items-center text-sm font-medium">
                                    <span class="iconify mr-1" data-icon="tabler:edit" data-width="16"></span>
                                    Edit
                                </a>
                            @endcan
                            @can('user-management-delete')
                                <form action="{{ route('users.destroy', $user) }}" method="POST"
                                    class="inline delete-user-form">
                                    @csrf
                                    @method('DELETE')
                                    <button type="button"
                                        class="delete-user-btn text-red-600 hover:text-red-800 flex items-center text-sm font-medium">
                                        <span class="iconify mr-1" data-icon="tabler:trash" data-width="16"></span>
                                        Delete
                                    </button>
                                </form>
                            @endcan
                        </div>
                    </div>
                </div>

            </div>
        @empty
            @include('components.no-data-found', [
                'title' => 'No Users found',
                'description' =>
                    'Your user dashboard is empty. Create your first user to get started with management and tracking.',
                'icon' => 'clarity:user-line',
                'button' => [
                    'showbtn' => true,
                    'text' => 'Add New User',
                    'url' => route('users.create'),
                    'icon' => 'tabler:plus',
                ],
                'permission' => 'user-management-create',
                'permissionMessage' => 'You do not have permission to create a user.',
                'permissionIcon' => 'tabler:lock',
                'hidden' => false,
            ])
        @endforelse
    </div>

    <!-- Pagination -->
    <div class="mt-6">
        {{ $users->links() }}
    </div>
@endsection

@push('scripts')
    <script>
        $(document).ready(function() {
            // Add animation to cards
            $(".user-card").each(function() {
                $(this).addClass('animate-fade-in');
            });

            // Show success toast if there's a success message
            const successMessage = $('#success-message').data('message');
            if (successMessage) {
                nepalToast.success('Success', successMessage);
            }

            // Search functionality
            $("#search-users").on("keyup", function() {
                var value = $(this).val().toLowerCase();
                var visibleCards = 0;

                $(".user-card").each(function() {
                    var name = $(this).data("name");
                    var email = $(this).data("email");
                    var ministry = $(this).data("ministry");

                    var matchFound = name.indexOf(value) > -1 ||
                        email.indexOf(value) > -1 ||
                        ministry.indexOf(value) > -1;

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
            $(".delete-user-btn").on("click", function(e) {
                e.preventDefault();
                const form = $(this).closest('form');
                const userName = $(this).closest('.user-card').find('h3').text().trim();

                nepalConfirm.show({
                    title: 'Delete User',
                    message: `Are you sure you want to delete <strong>${userName}</strong>? This action cannot be undone.`,
                    type: 'danger',
                    confirmText: 'Delete User',
                    cancelText: 'Cancel',
                    confirmIcon: '<span class="iconify" data-icon="tabler:trash" data-width="18"></span>'
                }).then(() => {
                    // Submit the form if confirmed
                    form.submit();

                    // Show a toast notification
                    nepalToast.info('Processing', 'Deleting user...');
                }).catch(() => {
                    // Show a toast notification if canceled
                    nepalToast.nepal('Action Canceled', 'User deletion was canceled.');
                });
            });
        });
    </script>
@endpush
