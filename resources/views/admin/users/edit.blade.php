@extends('layouts.app')

@push('styles')
    <style>
        .toggle-checkbox:checked {
            @apply right-0 border-blue-800;
        }

        .toggle-checkbox:checked+.toggle-label {
            @apply bg-blue-800;
        }
    </style>
@endpush

@section('content')
    @include('components.breadcrumb', [
        'breadcrumbs' => [
            ['title' => 'Users', 'url' => route('users.index')],
            ['title' => 'Edit User', 'url' => null],
        ],
    ])


    <div class="bg-white p-6 rounded-lg shadow-sm mb-6 dark:bg-slate-800">
        <!-- Page Header -->
        <div class="flex justify-between items-center mb-6 ">
            <h1 class="text-xl font-semibold text-gray-800 dark:text-white">Edit User: {{ $user->name }}</h1>
            <div class="flex space-x-2">
                <a href="{{ route('users.index') }}"
                    class="px-4 py-2 bg-gray-200 dark:bg-gray-900 text-gray-700 dark:text-white rounded-md hover:bg-gray-300 flex items-center">
                    <span class="iconify mr-2" data-icon="tabler:arrow-left" data-width="18"></span>
                    Back
                </a>
            </div>
        </div>

        <form id="user-form" action="{{ route('users.update', $user->id) }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')

            <!-- Basic Information Section -->
            <div class="mb-8">
                <div class="flex items-center mb-4">
                    <span class="iconify mr-2 text-blue-800" data-icon="tabler:info-circle" data-width="20"></span>
                    <h2 class="text-lg font-medium text-gray-800 dark:text-white">Basic Information</h2>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Name -->
                    <div>
                        <label for="name" class="block text-sm font-medium text-gray-700 mb-1 dark:text-slate-100">
                            Name <span class="text-red-500">*</span>
                        </label>
                        <input type="text" name="name" id="name" value="{{ old('name', $user->name) }}" required
                            class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-1 focus:ring-blue-800 focus:border-blue-800 dark:bg-gray-800 dark:text-white">
                        @error('name')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Email -->
                    <div>
                        <label for="email" class="block text-sm font-medium text-gray-700 mb-1 dark:text-slate-100">
                            Email <span class="text-red-500">*</span>
                        </label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <span class="iconify text-gray-400" data-icon="tabler:mail" data-width="18"></span>
                            </div>
                            <input type="email" name="email" id="email" value="{{ old('email', $user->email) }}"
                                required
                                class="w-full pl-10 pr-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-1 focus:ring-blue-800 focus:border-blue-800 dark:bg-gray-800 dark:text-white">
                        </div>
                        @error('email')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Password -->
                    <div>
                        <label for="password" class="block text-sm font-medium text-gray-700 mb-1 dark:text-slate-100">
                            Password <span class="text-gray-500 text-xs">(leave blank to keep current)</span>
                        </label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <span class="iconify text-gray-400" data-icon="tabler:lock" data-width="18"></span>
                            </div>
                            <input type="password" name="password" id="password"
                                class="w-full pl-10 pr-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-1 focus:ring-blue-800 focus:border-blue-800 dark:bg-gray-800 dark:text-white">
                        </div>
                        @error('password')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Password Confirmation -->
                    <div>
                        <label for="password_confirmation"
                            class="block text-sm font-medium text-gray-700 mb-1 dark:text-slate-100">
                            Confirm Password
                        </label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <span class="iconify text-gray-400" data-icon="tabler:check" data-width="18"></span>
                            </div>
                            <input type="password" name="password_confirmation" id="password_confirmation"
                                class="w-full pl-10 pr-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-1 focus:ring-blue-800 focus:border-blue-800 dark:bg-gray-800 dark:text-white">
                        </div>
                    </div>

                    <!-- Phone -->
                    <div>
                        <label for="phone" class="block text-sm font-medium text-gray-700 mb-1 dark:text-slate-100">Phone
                            Number</label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <span class="iconify text-gray-400" data-icon="tabler:phone" data-width="18"></span>
                            </div>
                            <input type="text" name="phone" id="phone" value="{{ old('phone', $user->phone) }}"
                                class="w-full pl-10 pr-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-1 focus:ring-blue-800 focus:border-blue-800 dark:bg-gray-800 dark:text-white">
                        </div>
                        @error('phone')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>


                    <!-- Status -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1 dark:text-slate-100">Status</label>
                        <div class="flex items-center gap-1">
                            <label class="relative inline-flex items-center cursor-pointer">
                                <input type="hidden" name="status" value="0">
                                <input type="checkbox" class="sr-only peer" id="toggle" name="status" value="1"
                                    {{ $user->is_active ? 'checked' : '' }}>
                                <div
                                    class="w-11 h-6 bg-gray-300 peer-checked:bg-blue-600 rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all">
                                </div>
                            </label>
                            <span class="text-sm text-gray-700 dark:text-slate-300">Active</span>
                        </div>
                        @error('status')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                </div>
            </div>

            <!-- Roles Section -->
            <div class="mb-8">
                <div class="flex items-center mb-4">
                    <span class="iconify mr-2 text-blue-800" data-icon="tabler:shield" data-width="20"></span>
                    <h2 class="text-lg font-medium text-gray-800 dark:text-white">Assign Roles</h2>
                </div>

                <div class="bg-gray-50 p-4 rounded-lg border border-gray-200 dark:bg-gray-700 dark:border-gray-600">
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                        @foreach ($roles as $role)
                            <div
                                class="flex items-center p-2 hover:bg-gray-100 rounded-md transition-colors dark:hover:bg-gray-600">
                                <input type="checkbox" name="roles[]" id="role_{{ $role->id }}"
                                    value="{{ $role->id }}"
                                    class="h-4 w-4 text-blue-800 focus:ring-blue-800 border-gray-300 rounded"
                                    {{ is_array(old('roles', $userRoles)) && in_array($role->id, old('roles', $userRoles)) ? 'checked' : '' }}>
                                <label for="role_{{ $role->id }}"
                                    class="ml-2 block text-sm text-gray-700 cursor-pointer dark:text-white">
                                    {{ $role->name }}
                                </label>
                            </div>
                        @endforeach
                    </div>
                    @error('roles')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>
            </div>


            <!-- Form Footer -->
            <div class="mt-8 pt-6 border-t border-gray-200">
                <div class="flex justify-end space-x-3">
                    <button type="button" id="reset-form"
                        class="px-4 py-2 bg-gray-200 text-gray-700 rounded-md hover:bg-gray-300 flex items-center dark:bg-gray-700 dark:text-white dark:hover:bg-gray-600">
                        <span class="iconify mr-2" data-icon="tabler:refresh" data-width="18"></span>
                        Reset
                    </button>
                    <button type="button" id="cancel-form"
                        class="px-4 py-2 bg-white border border-gray-300 text-gray-700 rounded-md hover:bg-gray-50 flex items-center dark:bg-gray-800 dark:border-gray-600 dark:text-white dark:hover:bg-gray-700">
                        <span class="iconify mr-2" data-icon="tabler:x" data-width="18"></span>
                        Cancel
                    </button>
                    <button type="submit"
                        class="px-4 py-2 bg-blue-800 text-white rounded-md hover:bg-blue-900 flex items-center">
                        <span class="iconify mr-2" data-icon="tabler:user-check" data-width="18"></span>
                        Update User
                    </button>
                </div>
            </div>
        </form>
    </div>
@endsection

@push('scripts')
    <script>
        $(document).ready(function() {
            // Avatar upload preview
            $('#avatar').on('change', function() {
                const file = this.files[0];
                if (file) {
                    const reader = new FileReader();
                    reader.onload = function(e) {
                        $('#avatar-preview').attr('src', e.target.result);
                        $('#avatar-preview-container').removeClass('hidden');
                        $('#remove-avatar').removeClass('hidden');
                        $('#current-avatar-container').addClass('hidden');
                    }
                    reader.readAsDataURL(file);
                }
            });

            $('#remove-avatar').on('click', function() {
                $('#avatar').val('');
                $('#avatar-preview-container').addClass('hidden');
                $(this).addClass('hidden');
                $('#current-avatar-container').removeClass('hidden');
            });

            // Password validation
            const passwordInput = document.getElementById('password');
            const confirmPasswordInput = document.getElementById('password_confirmation');

            function validatePassword() {
                if (passwordInput.value !== confirmPasswordInput.value) {
                    confirmPasswordInput.setCustomValidity("Passwords don't match");
                } else {
                    confirmPasswordInput.setCustomValidity('');
                }
            }

            passwordInput.addEventListener('change', validatePassword);
            confirmPasswordInput.addEventListener('keyup', validatePassword);

            // Reset form button
            $('#reset-form').on('click', function() {
                nepalConfirm.show({
                    title: 'Reset Form',
                    message: 'Are you sure you want to reset all form fields? This action cannot be undone.',
                    type: 'warning',
                    confirmText: 'Reset Form',
                    cancelText: 'Cancel',
                    confirmIcon: '<span class="iconify" data-icon="tabler:refresh" data-width="18"></span>'
                }).then(() => {
                    // Reset the form
                    $('#user-form')[0].reset();

                    // Reset avatar preview
                    $('#avatar-preview-container').addClass('hidden');
                    $('#remove-avatar').addClass('hidden');
                    $('#current-avatar-container').removeClass('hidden');

                    // Show toast notification
                    nepalToast.info('Form Reset', 'The form has been reset to its default state.');
                }).catch(() => {
                    // Show a toast notification if canceled
                    nepalToast.nepal('Action Canceled', 'Form reset was canceled.');
                });
            });

            // Cancel button
            $('#cancel-form').on('click', function() {
                nepalConfirm.show({
                    title: 'Cancel Form',
                    message: 'Are you sure you want to cancel? Any unsaved changes will be lost.',
                    type: 'warning',
                    confirmText: 'Yes, Cancel',
                    cancelText: 'No, Continue Editing',
                    confirmIcon: '<span class="iconify" data-icon="tabler:x" data-width="18"></span>'
                }).then(() => {
                    // Redirect back to users index
                    window.location.href = "{{ route('users.index') }}";
                }).catch(() => {
                    // Show a toast notification if canceled
                    nepalToast.nepal('Action Canceled', 'You can continue editing the form.');
                });
            });
        });
    </script>
@endpush
