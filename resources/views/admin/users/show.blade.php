@extends('layouts.app')

@push('styles')
    <style>
        .animate-fade-in {
            animation: fadeIn 0.5s ease-in-out;
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

        .permission-card:hover {
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06);
            transform: translateY(-2px);
            transition: all 0.3s ease;
        }
    </style>
@endpush

@section('content')
    @include('components.breadcrumb', [
        'breadcrumbs' => [
            ['title' => 'Users', 'url' => route('users.index')],
            ['title' => $user->name, 'url' => null],
        ],
    ])

    <!-- Page Header -->
    <div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-6">
        <div class="flex flex-row gap-2 justify-center items-center">
            <span class="iconify text-nepal-blue" data-icon="tabler:users" data-width="22"></span>
            <h1 class="text-2xl font-bold text-gray-800 dark:text-white mb-4 md:mb-0 relative pl-3">
                <span class="absolute left-0 top-1/2 transform -translate-y-1/2 w-0.5 h-6 bg-nepal-blue rounded"></span>
                User Details
            </h1>
        </div>

        <div class="mt-4 md:mt-0 flex flex-col sm:flex-row space-y-3 sm:space-y-0 sm:space-x-3">
            <a href="{{ route('users.index') }}"
                class="bg-gray-200 hover:bg-gray-300 text-gray-700 dark:bg-gray-700 dark:hover:bg-gray-600 dark:text-gray-200 px-4 py-2 rounded-lg shadow-sm flex items-center justify-center transition-colors">
                <span class="iconify mr-2" data-icon="tabler:arrow-left" data-width="20"></span>
                Back to User
            </a>
        </div>
    </div>

    @if (session('success'))
        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4 flex items-center dark:bg-green-900 dark:border-green-700 dark:text-green-300"
            role="alert">
            <span class="iconify mr-2" data-icon="tabler:check-circle"></span>
            <span class="block sm:inline">{{ session('success') }}</span>
        </div>
    @endif

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- User Profile Card -->
        <div class="bg-white rounded-lg shadow-md overflow-hidden animate-fade-in dark:bg-gray-800 h-fit sticky top-5">
            <div class="relative bg-gradient-to-r from-nepal-blue/90 to-nepal-blue/70 p-6 text-center">
                <div
                    class="w-24 h-24 rounded-full bg-white mx-auto mb-4 flex items-center justify-center text-3xl font-bold text-[#003893] shadow-lg">
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
                                    <img src="{{ $url }}" alt="{{ $user->name }}" class="w-24 h-24 rounded-md">
                                </a>
                            </div>
                        </div>
                    @else
                        <div class="flex justify-start items-center gap-3">
                            {{ strtoupper(substr($user->name, 0, 2)) }}
                        </div>
                    @endif
                </div>
                <h2 class="text-xl font-bold text-white">{{ $user->name }}</h2>
                <p class="text-blue-100 flex items-center justify-center mt-1">
                    <span class="iconify mr-1" data-icon="tabler:mail"></span> {{ $user->email }}
                </p>
                <div class="mt-3 flex flex-wrap justify-center gap-1">
                    @foreach ($user->roles as $role)
                        <span class="px-2 py-1 text-xs rounded-full bg-blue-100 text-blue-800 flex items-center">
                            <span class="iconify mr-1" data-icon="tabler:shield"></span> {{ $role->name }}
                        </span>
                    @endforeach
                </div>
                <div
                    class="absolute inset-0 opacity-10 bg-[url('data:image/svg+xml;base64,PHN2ZyB3aWR0aD0iNjAiIGhlaWdodD0iNjAiIHZpZXdCb3g9IjAgMCA2MCA2MCIgeG1sbnM9Imh0dHA6Ly93d3cudzMub3JnLzIwMDAvc3ZnIj48ZyBmaWxsPSJ3aGl0ZSIgZmlsbC1ydWxlPSJldmVub2RkIj48cGF0aCBkPSJNMzYgMzRoLTJ2LTRoLTR2LTJoNHYtNGgydjRoNHYyaC00djR6TTMwIDZoMnYyaC0yVjZ6bTAgNGgydjJoLTJWMTB6bTAgMThoMnYyaC0ydi0yek0zNCA2aDJ2MmgtMlY2em0wIDRoMnYyaC0yVjEwem0wIDE4aDJ2MmgtMnYtMnpNMzggNmgydjJoLTJWNnptMCA0aDJ2MmgtMlYxMHptMCAxOGgydjJoLTJ2LTJ6TTMwIDI2aDJ2MmgtMnYtMnptNCAwaDF2MmgtMXYtMnptNCAwaDJ2MmgtMnYtMnoiLz48L2c+PC9zdmc+')]">
                </div>
            </div>
            <div class="p-6 dark:text-gray-200">
                <div class="mb-4 flex items-start">
                    <span class="iconify mr-3 text-gray-500 mt-1 dark:text-gray-400" data-icon="tabler:phone"></span>
                    <div>
                        <h3 class="text-sm font-medium text-gray-500 dark:text-gray-400">Phone Number</h3>
                        <p class="text-gray-800 dark:text-gray-200">{{ $user->phone ?? 'Not provided' }}</p>
                    </div>
                </div>
                <div class="mb-4 flex items-start">
                    <span class="iconify mr-3 text-gray-500 mt-1 dark:text-gray-400" data-icon="tabler:building"></span>
                    <div>
                        <h3 class="text-sm font-medium text-gray-500 dark:text-gray-400">Ministry</h3>
                        <p class="text-gray-800 dark:text-gray-200">{{ $user->ministry->name ?? 'Not assigned' }}</p>
                    </div>
                </div>
                <div class="mb-4 flex items-start">
                    <span class="iconify mr-3 text-gray-500 mt-1 dark:text-gray-400"
                        data-icon="tabler:circle-{{ $user->is_active ? 'check' : 'x' }}-filled"></span>
                    <div>
                        <h3 class="text-sm font-medium text-gray-500 dark:text-gray-400">Status</h3>
                        <p class="text-gray-800 dark:text-gray-200">{{ $user->is_active ? 'Active' : 'In active' }}</p>
                    </div>
                </div>
                <div class="mb-4 flex items-start">
                    <span class="iconify mr-3 text-gray-500 mt-1 dark:text-gray-400" data-icon="tabler:calendar"></span>
                    <div>
                        <h3 class="text-sm font-medium text-gray-500 dark:text-gray-400">Created At</h3>
                        <p class="text-gray-800 dark:text-gray-200 nepali-date"
                            data-eng-date="{{ $user->created_at->format('Y-m-d') }}"></p>
                    </div>
                </div>
                <div class="mb-4 flex items-start">
                    <span class="iconify mr-3 text-gray-500 mt-1 dark:text-gray-400" data-icon="tabler:refresh"></span>
                    <div>
                        <h3 class="text-sm font-medium text-gray-500 dark:text-gray-400">Last Updated</h3>
                        <p class="text-gray-800 dark:text-gray-200 nepali-date"
                            data-eng-date="{{ $user->updated_at->format('Y-m-d') }}"></p>
                    </div>
                </div>
                <div class="grid grid-cols-2 gap-2 mt-6">
                    <a href="{{ route('users.edit', $user) }}"
                        class="bg-blue-500 hover:bg-blue-600 text-white text-center py-2 px-3 rounded flex items-center justify-center transition-colors text-sm">
                        <span class="iconify mr-1" data-icon="tabler:edit" data-width="16"></span> Edit
                    </a>

                    @if ($user->employee)
                        {{-- <a href="{{ route('salary.history', [
                            'employee_id' => $user->employee->id,
                            'back' => route('users.show', $user->id),
                        ]) }}"
                            class="bg-green-500 hover:bg-green-600 text-white text-center py-2 px-3 rounded flex items-center justify-center transition-colors text-sm">
                            <span class="iconify mr-1" data-icon="tabler:currency-dollar" data-width="16"></span> Salary
                        </a> --}}
                    @else
                        <button type="button" disabled
                            class="bg-gray-400 text-white text-center py-2 px-3 rounded flex items-center justify-center cursor-not-allowed opacity-50 text-sm"
                            title="This user is not an employee">
                            <span class="iconify mr-1" data-icon="tabler:currency-dollar" data-width="16"></span> Salary
                            </a>
                    @endif

                    <form action="{{ route('users.update.status', $user) }}" method="POST">
                        @csrf
                        <input type="hidden" name="id" value="{{ $user->id }}">
                        <button type="button"
                            class="w-full bg-yellow-500 hover:bg-yellow-600 text-white py-2 px-3 rounded change-status flex items-center justify-center transition-colors text-sm"
                            data-message="Are you sure you want to change status of this user?">
                            <span class="iconify mr-1" data-icon="tabler:arrows-exchange" data-width="16"></span>Status
                        </button>
                    </form>

                    <form action="{{ route('users.destroy', $user) }}" method="POST">
                        @csrf
                        @method('DELETE')
                        <button type="button"
                            class="w-full bg-red-500 hover:bg-red-600 text-white py-2 px-3 rounded confirm-delete-user flex items-center justify-center transition-colors text-sm"
                            data-message="Are you sure you want to delete this user?">
                            <span class="iconify mr-1" data-icon="tabler:trash" data-width="16"></span> Delete
                        </button>
                    </form>
                </div>
            </div>
        </div>

        <!-- User Permissions and Details -->
        <div class="lg:col-span-2">
            <!-- Roles and Permissions -->
            <div class="bg-white rounded-lg shadow-md overflow-hidden mb-6 animate-fade-in dark:bg-gray-800"
                style="animation-delay: 0.1s">
                <div class="border-b border-gray-200 p-4 flex items-center dark:border-gray-700">
                    <span class="iconify mr-2 text-[#003893] dark:text-blue-400" data-icon="tabler:shield"></span>
                    <h3 class="text-lg font-medium text-gray-800 dark:text-white">Roles & Permissions</h3>
                </div>
                <div class="p-6">
                    <h4 class="text-md font-medium text-gray-700 mb-3 flex items-center dark:text-gray-300">
                        <span class="iconify mr-2 text-[#003893] dark:text-blue-400" data-icon="tabler:users"></span>
                        Assigned Roles
                    </h4>
                    <div class="flex flex-wrap gap-2 mb-6">
                        @forelse($user->roles as $role)
                            <div
                                class="bg-blue-50 text-blue-700 px-3 py-2 rounded-lg flex items-center dark:bg-blue-900 dark:text-blue-200">
                                <span class="iconify mr-2" data-icon="tabler:shield"></span>
                                {{ $role->name }}
                            </div>
                        @empty
                            <p class="text-gray-500 italic flex items-center dark:text-gray-400">
                                <span class="iconify mr-2" data-icon="tabler:alert-circle"></span> No roles assigned
                            </p>
                        @endforelse
                    </div>

                    <h4 class="text-md font-medium text-gray-700 mb-3 flex items-center dark:text-gray-300">
                        <span class="iconify mr-2 text-[#003893] dark:text-blue-400" data-icon="tabler:key"></span>
                        Permissions
                    </h4>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
                        @php
                            $allPermissions = $user->getAllPermissions();
                            $permissionsByGroup = $allPermissions->groupBy('group');
                        @endphp

                        @forelse($permissionsByGroup as $group => $permissions)
                            <div
                                class="bg-gray-50 h-fit p-3 choose-me rounded-lg border border-gray-200 hover:shadow-sm transition-shadow permission-card dark:bg-gray-700 dark:border-gray-600 ">
                                <div class="flex items-center justify-between">
                                    <h5
                                        class="font-medium text-gray-700 capitalize mb-2 flex items-center dark:text-gray-200 ">
                                        <span class="iconify mr-2 text-[#003893] dark:text-blue-400"
                                            data-icon="tabler:folder"></span> {{ $group }}
                                    </h5>
                                    <span
                                        class="iconify transform transition-transform group-expanded:rotate-180 toggle-permission-option dark:text-gray-200"
                                        data-icon="tabler:chevron-down" data-width="20"></span>
                                </div>

                                <ul class="space-y-1 hidden permission-show-option">
                                    @foreach ($permissions as $permission)
                                        <li class="text-sm text-gray-600 flex items-center dark:text-gray-300">
                                            <span class="iconify mr-2 text-green-500" data-icon="tabler:check"></span>
                                            <span
                                                class="capitalize">{{ explode('.', $permission->name)[1] ?? $permission->name }}</span>
                                        </li>
                                    @endforeach
                                </ul>
                            </div>
                        @empty
                            <p class="text-gray-500 italic col-span-2 flex items-center dark:text-gray-400">
                                <span class="iconify mr-2" data-icon="tabler:alert-circle"></span> No permissions
                                assigned
                            </p>
                        @endforelse
                    </div>
                </div>
            </div>

            <!-- Recent Activity -->
            <div class="bg-white rounded-lg shadow-md overflow-hidden dark:bg-gray-800 mb-6">
                <div class="border-b border-gray-200 p-4 flex items-center justify-start dark:border-gray-700">
                    <span class="iconify mr-2 text-[#003893] dark:text-blue-400" data-icon="tabler:clock-play"></span>
                    <h3 class="text-lg font-medium text-gray-800 dark:text-white">Login Sessionss</h3>
                </div>

                <div class="relative pb-6">
                    <!-- Main Timeline vertical line -->
                    <div class="absolute top-0 bottom-0 left-10 w-0.5 bg-indigo-100 dark:bg-indigo-900/30"></div>

                    @foreach ($loginSessions as $index => $session)
                        <div class="relative {{ $index > 0 ? 'mt-8' : 'mt-4' }}">
                            <!-- Session Timeline Node -->
                            <div class="absolute left-10 top-3 -translate-x-1/2 flex items-center justify-center">
                                <div
                                    class="w-8 h-8 rounded-full bg-indigo-50 dark:bg-indigo-900/20 flex items-center justify-center z-10">
                                    <div class="w-3 h-3 rounded-full bg-indigo-600 dark:bg-indigo-400"></div>
                                </div>
                            </div>

                            <!-- Login Session Header -->
                            <div
                                class="ml-16 mr-6 bg-white dark:bg-gray-800 rounded-lg border border-gray-100 dark:border-gray-700 shadow-sm">
                                <div class="px-6 py-3 flex items-center justify-between">
                                    <div class="flex items-center space-x-2">
                                        <div
                                            class="flex items-center bg-blue-50 text-blue-700 dark:bg-blue-900/20 dark:text-blue-300 px-3 py-1.5 rounded-full text-sm">
                                            <span class="iconify mr-1.5" data-icon="tabler:login"></span>
                                            <span class="font-medium">Login:</span> &nbsp; <span class="nepali-date"
                                                data-eng-date="{{ \Carbon\Carbon::parse($session->logged_in_at)->format('Y-m-d') }}">
                                            </span> &nbsp;
                                            &nbsp;{{ \Carbon\Carbon::parse($session->logged_in_at)->format('g:i:s A') }}<span></span>
                                        </div>

                                        @if ($session->logged_out_at)
                                            <div
                                                class="flex items-center bg-red-50 text-red-700 dark:bg-red-900/20 dark:text-red-300 px-3 py-1.5 rounded-full text-sm">
                                                <span class="iconify mr-1.5" data-icon="tabler:logout"></span>
                                                <span class="font-medium">Logout:</span></span> &nbsp; <span
                                                    class="nepali-date"
                                                    data-eng-date="{{ \Carbon\Carbon::parse($session->logged_out_at)->format('Y-m-d') }}">
                                                </span> &nbsp;
                                                &nbsp;{{ \Carbon\Carbon::parse($session->logged_out_at)->format('g:i:s A') }}<span></span>
                                            </div>
                                        @else
                                            <div
                                                class="flex items-center bg-green-50 text-green-700 dark:bg-green-900/20 dark:text-green-300 px-3 py-1.5 rounded-full text-sm">
                                                <span class="iconify mr-1.5" data-icon="tabler:circle-check"></span>
                                                Currently Active
                                            </div>
                                        @endif
                                    </div>

                                    <div class="flex items-center">
                                        @if ($session->logged_out_at)
                                            <div
                                                class="text-xs text-gray-500 dark:text-gray-400 mr-3 flex gap-1 items-center">
                                                <span class="iconify mr-0.5" data-icon="tabler:clock"></span>
                                                <span>
                                                    {{ \Carbon\Carbon::parse($session->logged_in_at)->diffForHumans(\Carbon\Carbon::parse($session->logged_out_at), ['parts' => 2]) }}
                                                </span>
                                            </div>
                                        @endif

                                        <button
                                            class="text-gray-400 hover:text-gray-600 dark:hover:text-gray-200 transition-colors focus:outline-none"
                                            onclick="toggleActivities('session-{{ $session->id }}')">
                                            <span class="iconify w-5 h-5" data-icon="tabler:chevron-down"
                                                id="chevron-down-{{ $session->id }}"></span>
                                            <span class="iconify w-5 h-5 hidden" data-icon="tabler:chevron-up"
                                                id="chevron-up-{{ $session->id }}"></span>
                                        </button>
                                    </div>
                                </div>
                            </div>



                            <!-- Activities Timeline (Children) -->
                            <div id="session-{{ $session->id }}" class=" ml-16 pl-12 relative hidden">
                                @if ($session->activities->isEmpty())
                                    <div class="relative group">
                                        <!-- Child Timeline Node -->

                                        <div
                                            class="absolute -left-12 top-0 -translate-x-1/2 flex items-center justify-center">
                                            <div class="  w-6 h-0.5 bg-gray-200 dark:bg-gray-700">
                                            </div>
                                            <div class=" flex items-center justify-center">
                                                <div
                                                    class="w-6 h-6 rounded-full bg-gray-100 dark:bg-gray-700 flex items-center justify-center z-10">
                                                    <div class="w-2 h-2 rounded-full bg-gray-500 dark:bg-gray-400">
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <div
                                            class="text-center p-2 my-3 text-gray-500 dark:text-gray-400 italic bg-gray-50 dark:bg-gray-800/30 rounded-lg w-fit">
                                            No activities recorded during this session.
                                        </div>

                                    </div>
                                @else
                                    <div class="space-y-6 py-4 ">
                                        @foreach ($session->activities as $activity)
                                            <div class="relative group">
                                                <!-- Child Timeline Node -->

                                                <div
                                                    class="absolute -left-12 top-0 -translate-x-1/2 flex items-center justify-center">
                                                    <div class="  w-6 h-0.5 bg-gray-200 dark:bg-gray-700">
                                                    </div>
                                                    <div class=" flex items-center justify-center">
                                                        <div
                                                            class="w-6 h-6 rounded-full bg-gray-100 dark:bg-gray-700 flex items-center justify-center z-10">
                                                            <div class="w-2 h-2 rounded-full bg-gray-500 dark:bg-gray-400">
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <!-- Activity content -->
                                                <div class="w-fit">
                                                    <!-- Activity timestamp -->
                                                    <div
                                                        class="text-sm text-gray-500 dark:text-gray-400 mb-1 flex items-center">
                                                        <span class="iconify mr-1.5" data-icon="tabler:clock"></span>
                                                        {{ \Carbon\Carbon::parse($activity->created_at)->format('Y-m-d H:i:s') }}
                                                    </div>

                                                    <div
                                                        class="w-full bg-gray-50 dark:bg-gray-800/30 rounded-lg p-3 border border-gray-100 dark:border-gray-700 group-hover:border-indigo-200 dark:group-hover:border-indigo-800/30 transition-colors">
                                                        <div class="flex items-start">
                                                            <div class="flex-1">
                                                                <div
                                                                    class="font-medium text-gray-900 dark:text-white flex items-center">
                                                                    @if (strtolower($activity->action) == 'created')
                                                                        <span
                                                                            class="iconify mr-1.5 text-green-600 dark:text-green-400"
                                                                            data-icon="tabler:plus"></span>
                                                                    @elseif (strtolower($activity->action) == 'updated')
                                                                        <span
                                                                            class="iconify mr-1.5 text-blue-600 dark:text-blue-400"
                                                                            data-icon="tabler:edit"></span>
                                                                    @elseif (strtolower($activity->action) == 'deleted')
                                                                        <span
                                                                            class="iconify mr-1.5 text-red-600 dark:text-red-400"
                                                                            data-icon="tabler:trash"></span>
                                                                    @else
                                                                        <span
                                                                            class="iconify mr-1.5 text-gray-600 dark:text-gray-400"
                                                                            data-icon="tabler:file"></span>
                                                                    @endif

                                                                    {{ ucfirst($activity->action) }}
                                                                    {{ class_basename($activity->model_type) }}
                                                                    <span
                                                                        class="text-gray-500 dark:text-gray-400 font-normal ml-1">(ID:
                                                                        {{ $activity->model_id }})</span>
                                                                </div>

                                                                <div class="mt-2 flex flex-wrap gap-3">
                                                                    <div
                                                                        class="flex items-center text-xs text-gray-600 dark:text-gray-400 bg-gray-100 dark:bg-gray-700/50 px-2 py-1 rounded">
                                                                        <span class="iconify mr-1"
                                                                            data-icon="tabler:world"></span>
                                                                        {{ $activity->ip_address }}
                                                                    </div>

                                                                    <div
                                                                        class="flex items-center text-xs text-gray-600 dark:text-gray-400 bg-gray-100 dark:bg-gray-700/50 px-2 py-1 rounded max-w-md">
                                                                        <span class="iconify mr-1"
                                                                            data-icon="tabler:browser"></span>
                                                                        <span
                                                                            class="truncate">{{ $activity->user_agent }}</span>
                                                                    </div>
                                                                </div>
                                                            </div>

                                                            <button
                                                                class="ml-2 text-gray-400 hover:text-gray-600 dark:hover:text-gray-300 p-1 rounded-full hover:bg-gray-100 dark:hover:bg-gray-700/50 transition-colors">
                                                                <span class="iconify w-4 h-4"
                                                                    data-icon="tabler:dots-vertical"></span>
                                                            </button>
                                                        </div>
                                                    </div>

                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                @endif
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>

            <div class="bg-white rounded-lg shadow-md overflow-hidden dark:bg-gray-800 mb-6 animate-fade-in">
                <div class="border-b border-gray-200 p-4 flex items-center dark:border-gray-700">
                    <span class="iconify mr-2 text-[#003893] dark:text-blue-400" data-icon="tabler:star"></span>
                    <h3 class="text-lg font-medium text-gray-800 dark:text-white">Performance Reviews</h3>
                </div>
                <div class="p-6">

                </div>
            </div>

            <!-- Additional Information -->
            <div class="bg-white rounded-lg shadow-md overflow-hidden animate-fade-in dark:bg-gray-800"
                style="animation-delay: 0.3s">
                <div class="border-b border-gray-200 p-4 flex items-center dark:border-gray-700">
                    <span class="iconify mr-2 text-[#003893] dark:text-blue-400" data-icon="tabler:info-circle"></span>
                    <h3 class="text-lg font-medium text-gray-800 dark:text-white">Additional Information</h3>
                </div>
                <div class="p-6">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div class="p-4 bg-gray-50 rounded-lg dark:bg-gray-700">
                            <h4 class="text-md font-medium text-gray-700 mb-2 flex items-center dark:text-gray-300">
                                <span class="iconify mr-2 text-[#003893] dark:text-blue-400"
                                    data-icon="tabler:lock"></span> Account Status
                            </h4>
                            <div class="flex items-center">
                                <span
                                    class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-{{ $user->is_active ? 'green' : 'red' }}-100 text-{{ $user->is_active ? 'green' : 'red' }}-800 dark:bg-{{ $user->is_active ? 'green' : 'red' }}-900 dark:text-{{ $user->is_active ? 'green' : 'red' }}-200">
                                    <span class="iconify mr-1" data-icon="tabler:check-circle"></span>
                                    {{ $user->is_active ? 'Active' : 'In active' }}
                                </span>
                            </div>
                        </div>

                        <div class="p-4 bg-gray-50 rounded-lg dark:bg-gray-700">
                            <h4 class="text-md font-medium text-gray-700 mb-2 flex items-center dark:text-gray-300">
                                <span class="iconify mr-2 text-[#003893] dark:text-blue-400"
                                    data-icon="tabler:login"></span> Last Login
                            </h4>
                            <p class="text-gray-600 dark:text-gray-300">
                                @if (isset($user->last_login_at))
                                    {{ \Carbon\Carbon::parse($user->last_login_at)->format('F d, Y h:i A') }}
                                @else
                                    Never logged in
                                @endif
                            </p>
                        </div>

                        <div class="p-4 bg-gray-50 rounded-lg dark:bg-gray-700">
                            <h4 class="text-md font-medium text-gray-700 mb-2 flex items-center dark:text-gray-300">
                                <span class="iconify mr-2 text-[#003893] dark:text-blue-400" data-icon="tabler:id"></span>
                                User ID
                            </h4>
                            <p class="text-gray-600 dark:text-gray-300">{{ $user->id }}</p>
                        </div>

                        <div class="p-4 bg-gray-50 rounded-lg dark:bg-gray-700">
                            <h4 class="text-md font-medium text-gray-700 mb-2 flex items-center dark:text-gray-300">
                                <span class="iconify mr-2 text-[#003893] dark:text-blue-400"
                                    data-icon="tabler:mail-opened"></span> Email Verified
                            </h4>
                            <p class="text-gray-600 dark:text-gray-300">
                                @if ($user->email_verified_at)
                                    <span class="inline-flex items-center text-green-600 dark:text-green-400">
                                        <span class="iconify mr-1" data-icon="tabler:check"></span>
                                        Verified on
                                        {{ \Carbon\Carbon::parse($user->email_verified_at)->format('F d, Y') }}
                                    </span>
                                @else
                                    <span class="inline-flex items-center text-yellow-600 dark:text-yellow-400">
                                        <span class="iconify mr-1" data-icon="tabler:alert-triangle"></span>
                                        Not verified
                                    </span>
                                @endif
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    </div>
@endsection

@push('scripts')
    <script>
        function toggleActivities(id) {
            const element = document.getElementById(id);
            const chevronDown = document.getElementById('chevron-down-' + id);
            const chevronUp = document.getElementById('chevron-up-' + id);

            if (element.classList.contains('hidden')) {
                element.classList.remove('hidden');
                chevronDown.classList.add('hidden');
                chevronUp.classList.remove('hidden');
            } else {
                element.classList.add('hidden');
                chevronDown.classList.remo

                ve('hidden');
                chevronUp.classList.add('hidden');
            }
        }
    </script>

    <script>
        $(document).on('click', '.toggle-permission-option', function() {
            if ($(this).closest('.choose-me').find('.permission-show-option').hasClass('hidden')) {
                $(this).closest('.choose-me').find('.permission-show-option').removeClass('hidden')
            } else {
                $(this).closest('.choose-me').find('.permission-show-option').addClass('hidden')
            }
        });
        document.addEventListener('DOMContentLoaded', function() {

            const deleteButtons = document.querySelectorAll('.confirm-delete-user');
            if (deleteButtons.length > 0) {
                deleteButtons.forEach(button => {
                    button.addEventListener('click', function(e) {
                        e.preventDefault();
                        const message = this.dataset.message ||
                            'Are you sure you want to delete this item?';
                        nepalConfirm.show({
                            title: 'Delete User',
                            message: message,
                            type: 'danger',
                            confirmText: 'Yes, Delete',
                            cancelText: 'Cancel',
                            confirmIcon: '<span class="iconify" data-icon="tabler:trash-2" data-width="18"></span>'
                        }).then(() => {
                            this.closest('form').submit();
                        }).catch(() => {
                            nepalToast.nepal('Action Canceled',
                                'User deletion was canceled.');
                        });
                    });
                });
            }

            const statusButton = document.querySelectorAll('.change-status');
            if (statusButton.length > 0) {
                statusButton.forEach(button => {
                    button.addEventListener('click', function(e) {
                        e.preventDefault();
                        const message = this.dataset.message ||
                            'Are you sure you want to change status of this item?';
                        nepalConfirm.show({
                            title: 'Change Status',
                            message: message,
                            type: 'danger',
                            confirmText: 'Yes, Change',
                            cancelText: 'Cancel',
                            confirmIcon: '<span class="iconify" data-icon="tabler:arrow-exchange" data-width="18"></span>'
                        }).then(() => {
                            this.closest('form').submit();
                        }).catch(() => {
                            nepalToast.nepal('Action Canceled',
                                'User status change was canceled.');
                        });
                    });
                });
            }

        });
    </script>
@endpush
