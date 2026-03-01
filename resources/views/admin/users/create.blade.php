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
            ['title' => 'Create User', 'url' => null],
        ],
    ])


    <div class="bg-white p-6 rounded-lg shadow-sm mb-6 dark:bg-slate-800">
        <!-- Page Header -->
        <div class="flex justify-between items-center mb-6 ">
            <h1 class="text-xl font-semibold text-gray-800 dark:text-white">Create User</h1>
            <div class="flex space-x-2">
                <a href="{{ route('users.index') }}"
                    class="px-4 py-2 bg-gray-200 dark:bg-gray-900 text-gray-700 dark:text-white rounded-md hover:bg-gray-300 flex items-center">
                    <span class="iconify mr-2" data-icon="tabler:arrow-left" data-width="18"></span>
                    Back
                </a>
            </div>
        </div>

        <form id="user-form" action="{{ route('users.store') }}" method="POST" enctype="multipart/form-data">
            @csrf

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
                        <input type="text" name="name" id="name" value="{{ old('name') }}" required
                            placeholder="Enter full name"
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
                            <input type="email" name="email" id="email" value="{{ old('email') }}" required
                                placeholder="Enter email address"
                                class="w-full pl-10 pr-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-1 focus:ring-blue-800 focus:border-blue-800 dark:bg-gray-800 dark:text-white">
                        </div>
                        @error('email')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Password -->
                    {{-- <div>
                        <label for="password" class="block text-sm font-medium text-gray-700 mb-1 dark:text-slate-100">
                            Password <span class="text-red-500">*</span>
                        </label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <span class="iconify text-gray-400" data-icon="tabler:lock" data-width="18"></span>
                            </div>
                            <input type="password" name="password" id="password" required placeholder="Enter password"
                                class="w-full pl-10 pr-10 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-1 focus:ring-blue-800 focus:border-blue-800 dark:bg-gray-800 dark:text-white">
                            <button type="button" class="absolute inset-y-0 right-0 pr-3 flex items-center"
                                onclick="togglePassword('password')">
                                <span
                                    class="iconify text-gray-400 hover:text-gray-600 dark:hover:text-gray-300 transition-colors cursor-pointer"
                                    data-icon="tabler:eye-off" data-width="18" id="password-eye"></span>
                            </button>
                        </div>
                        @error('password')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div> --}}

                    <!-- Password Confirmation -->
                    {{-- <div>
                        <label for="password_confirmation"
                            class="block text-sm font-medium text-gray-700 mb-1 dark:text-slate-100">
                            Confirm Password <span class="text-red-500">*</span>
                        </label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <span class="iconify text-gray-400" data-icon="tabler:check" data-width="18"></span>
                            </div>
                            <input type="password" name="password_confirmation" id="password_confirmation" required
                                placeholder="Re-enter password"
                                class="w-full pl-10 pr-10 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-1 focus:ring-blue-800 focus:border-blue-800 dark:bg-gray-800 dark:text-white">
                            <button type="button" class="absolute inset-y-0 right-0 pr-3 flex items-center"
                                onclick="togglePassword('password_confirmation')">
                                <span
                                    class="iconify text-gray-400 hover:text-gray-600 dark:hover:text-gray-300 transition-colors cursor-pointer"
                                    data-icon="tabler:eye-off" data-width="18" id="password_confirmation-eye"></span>
                            </button>
                        </div>
                        @error('password_confirmation')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div> --}}

                    <!-- Phone -->
                    <div>
                        <label for="phone" class="block text-sm font-medium text-gray-700 mb-1 dark:text-slate-100">Phone
                            Number</label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <span class="iconify text-gray-400" data-icon="tabler:phone" data-width="18"></span>
                            </div>
                            <input type="text" name="phone" id="phone" value="{{ old('phone') }}"
                                placeholder="Enter phone number"
                                class="w-full pl-10 pr-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-1 focus:ring-blue-800 focus:border-blue-800 dark:bg-gray-800 dark:text-white">
                        </div>
                        @error('phone')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                </div>
            </div>

            <!-- Roles Section -->
            {{-- <div class="mb-8">
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
                                    {{ is_array(old('roles')) && in_array($role->id, old('roles')) ? 'checked' : '' }}>
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
            </div> --}}

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
                        <span class="iconify mr-2" data-icon="tabler:user-plus" data-width="18"></span>
                        Create User
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
                    }
                    reader.readAsDataURL(file);
                }
            });

            $('#remove-avatar').on('click', function() {
                $('#avatar').val('');
                $('#avatar-preview-container').addClass('hidden');
                $(this).addClass('hidden');
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

        function togglePassword(fieldId) {
            const passwordField = document.getElementById(fieldId);
            const eyeIcon = document.getElementById(fieldId + '-eye');

            if (!passwordField || !eyeIcon) {
                console.error(`Element not found: ${fieldId} or ${fieldId}-eye`);
                return;
            }

            if (passwordField.type === 'password') {
                passwordField.type = 'text';
                eyeIcon.setAttribute('data-icon', 'tabler:eye');
                eyeIcon.title = "Hide password";
            } else {
                passwordField.type = 'password';
                eyeIcon.setAttribute('data-icon', 'tabler:eye-off');
                eyeIcon.title = "Show password";
            }
        }
    </script>
@endpush
