@extends('layouts.app')

@section('content')
    @include('components.breadcrumb', [
        'breadcrumbs' => [
            ['title' => $organization->name, 'url' => null],
            ['title' => 'Staff Create', 'url' => null],
        ],
    ])

    @if (session('success'))
        <div id="success-message" data-message="{{ session('success') }}" class="hidden"></div>
    @endif

    @if (session('error'))
        <div id="error-message" data-message="{{ session('error') }}" class="hidden"></div>
    @endif

    <div class="bg-white p-6 rounded-lg shadow-sm mb-6">
        <!-- Page Header -->
        <div class="flex justify-between items-center mb-6">
            <h1 class="text-xl font-semibold text-gray-800">Create Staff for {{ $organization->name }}</h1>
            <div class="flex space-x-2">
                <a href="{{ route('organization-staff.index', ['slug' => $organization->slug])}}"
                    class="px-4 py-2 bg-gray-200 dark:bg-gray-900 text-gray-700 rounded-md hover:bg-gray-300 flex items-center">
                    <span class="iconify mr-2" data-icon="tabler:arrow-left" data-width="18"></span>
                    Back
                </a>
            </div>
        </div>

        <form action="{{ route('organization-staff.store', $organization->slug) }}" method="POST"
            enctype="multipart/form-data">
            @csrf
            <div class="space-y-3">
                <!-- Staff Name -->
                <div class="md:col-span-2">
                    <label for="staff_name" class="block text-sm font-medium text-gray-700 mb-1">
                        Staff Name <span class="text-red-500">*</span>
                    </label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <span class="iconify text-gray-400" data-icon="wpf:name" data-width="18"></span>
                        </div>
                        <input type="text" name="name" id="staff_name" value="{{ old('name') }}"
                            placeholder="Enter Staff name"
                            class="w-full pl-10 pr-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-1 focus:ring-blue-800 focus:border-blue-800">
                    </div>
                    @error('name')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                    <div>
                        <label for="email" class="block text-sm font-medium text-gray-700 mb-1">
                            Email
                        </label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <span class="iconify text-gray-400" data-icon="line-md:email-twotone"
                                    data-width="18"></span>
                            </div>
                            <input type="text" name="email" id="email" value="{{ old('email') }}"
                                placeholder="Enter your Email address"
                                class="w-full pl-10 pr-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-1 focus:ring-blue-800 focus:border-blue-800">
                        </div>
                        @error('email')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="phone" class="block text-sm font-medium text-gray-700 mb-1">
                            Phone
                        </label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <span class="iconify text-gray-400" data-icon="line-md:phone-twotone"
                                    data-width="18"></span>
                            </div>
                            <input type="text" name="phone" id="phone" value="{{ old('phone') }}"
                                placeholder="Enter your Phone number"
                                class="w-full pl-10 pr-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-1 focus:ring-blue-800 focus:border-blue-800">
                        </div>
                        @error('phone')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="staffRoleTrigger" class="block text-sm font-medium text-gray-700 mb-1 cursor-pointer">
                            Staff Role <span class="text-red-500">*</span>
                        </label>

                        <!-- Hidden input: actual value sent to server -->
                        <input type="hidden" name="staff_role_id" id="staff_role_id">

                        <!-- Custom Dropdown -->
                        <div class="relative">
                            <button type="button" id="staffRoleTrigger"
                                class="w-full pl-10 pr-3 py-2 border border-gray-300 rounded-md bg-white text-left focus:outline-none focus:ring-1 focus:ring-blue-800 flex justify-between items-center">
                                <span id="selectedRoleText" class="text-gray-700">Select Staff Role</span>
                                <span class="iconify text-gray-400" data-icon="tabler:chevron-down" data-width="18"></span>
                            </button>

                            <!-- Left icon -->
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <span class="iconify text-gray-400" data-icon="tabler:user-check" data-width="18"></span>
                            </div>

                            <!-- Dropdown menu -->
                            <div id="staffRoleDropdown"
                                class="hidden absolute z-50 w-full bg-white border border-gray-200 rounded-md shadow-lg mt-1">
                                <div class="p-2 border-b">
                                    <input type="text" id="staffRoleSearch" placeholder="Search role..."
                                        class="w-full px-3 py-2 border rounded-md focus:ring-1 focus:ring-blue-600">
                                </div>
                                <ul id="staffRoleOptions" class="max-h-48 overflow-y-auto"></ul>
                                <div class="border-t p-2">
                                    <button type="button" id="createStaffRoleBtn"
                                        class="w-full flex items-center justify-center gap-1 px-3 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 text-sm">
                                        <span class="iconify" data-icon="lucide:plus" data-width="20"></span>
                                        Create New Role
                                    </button>
                                </div>
                            </div>
                        </div>

                        @error('staff_role_id')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <!-- Bio -->
                <div>
                    <label for="bio" class="block text-sm font-medium text-gray-700 mb-1">
                        Bio
                    </label>
                    <div class="border border-gray-300 dark:border-gray-500 rounded-lg bg-gray-50 dark:bg-gray-600">
                        <div id="bio_editor" class="min-h-[120px]"></div>
                    </div>

                    <input type="hidden" name="bio" id="bio">
                    @error('bio')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Image Upload Section -->
                <div class="bg-gray-50 dark:bg-gray-700/50 rounded-lg p-4 mb-6">
                    <h3 class="text-lg font-medium text-gray-800 mb-4 flex items-center">
                        <span class="iconify mr-2" data-icon="tabler:photo" data-width="20"></span>
                        Image
                    </h3>
                    <div
                        class="flex flex-col items-center justify-center p-4 border border-dashed border-gray-300 rounded-md">
                        <input type="hidden" name="image" id="image" value="">

                        <!-- Dynamic preview -->
                        <div id="image-preview" class="mb-4 w-full"></div>

                        <!-- Button to open media picker -->
                        <button type="button" id="select-featured-image"
                            class="select-image-btn px-4 py-2 bg-gray-100 dark:bg-gray-700 text-gray-700 dark:text-gray-200 rounded-md border border-gray-300 dark:border-gray-600 flex items-center justify-center hover:bg-gray-200 dark:hover:bg-gray-600 transition">
                            <span class="iconify mr-2" data-icon="tabler:upload" data-width="20"></span>
                            Select Image
                        </button>

                        <!-- Remove button -->
                        <button type="button" id="remove-featured-image"
                            class="text-red-600 hover:text-red-800 text-sm mt-2 hidden">
                            Remove Image
                        </button>
                    </div>

                    @error('image')
                        <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                    @enderror
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
                    <a href="{{ route('dashboard') }}"
                        class="px-4 py-2 bg-white border border-gray-300 text-gray-700 rounded-md hover:bg-gray-50 flex items-center dark:bg-gray-800 dark:border-gray-600 dark:hover:bg-gray-700">
                        <span class="iconify mr-2" data-icon="tabler:x" data-width="18"></span>
                        Cancel
                    </a>
                    <button type="submit"
                        class="px-6 py-2 bg-blue-800 text-white rounded-md hover:bg-blue-900 flex items-center">
                        <span class="iconify mr-2" data-icon="tabler:building-plus" data-width="18"></span>
                        Create
                    </button>
                </div>
            </div>
        </form>
    </div>

    <!-- Media Picker Component -->
    <x-media-piker modalId="featured-image-media-picker" title="Select or Upload Image" :allowMultiple="false"
        :allowUpload="true" :allowView="true" triggerSelector=".select-image-btn" onSelect="handleImageSelection"
        acceptedTypes="image/jpeg,image/jpg,image/png,image/gif,image/webp,image/svg+xml" maxFileSize="10MB" />
@endsection

@push('scripts')
    <script>
        let searchTimeout = null;

        // ─── Icon-safe button label helpers ──────────────────────────────────────
        function setSelectBtnText(label) {
            $('#select-featured-image').html(
                `<span class="iconify mr-2" data-icon="tabler:upload" data-width="20"></span> ${label}`
            );
        }

        // ─── Staff Role AJAX ──────────────────────────────────────────────────────
        function getstaffRoles(search = '') {
            $.ajax({
                url: "{{ route('organization-staff.getstaffRoles') }}",
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
                        `<li class="px-4 py-2 text-red-500 text-sm">Failed to load roles</li>`);
                }
            });
        }

        function renderStaffRoles(data) {
            const list = $('#staffRoleOptions');
            list.empty();
            if (!data.length) {
                list.append(`<li class="px-4 py-2 text-gray-500 text-sm">No roles found</li>`);
                return;
            }
            data.forEach(role => {
                list.append(`
                    <li class="px-4 py-2 hover:bg-blue-50 dark:hover:bg-slate-700 cursor-pointer staff-role-item"
                        data-id="${role.id}" data-name="${role.name}">
                        ${role.name}
                    </li>
                `);
            });
        }

        // ─── Dropdown toggle ──────────────────────────────────────────────────────
        function toggleRoleDropdown() {
            const dropdown = $('#staffRoleDropdown');
            const chevron = $('#staffRoleTrigger').find('.iconify[data-icon^="tabler:chevron"]');
            dropdown.toggleClass('hidden');
            chevron.attr('data-icon', dropdown.hasClass('hidden') ? 'tabler:chevron-down' : 'tabler:chevron-up');
            if (!dropdown.hasClass('hidden')) $('#staffRoleSearch').focus();
        }

        // ─── Modal helpers ────────────────────────────────────────────────────────
        function modelOpen() {
            $('#custom-modal').show();
            $('#modal-role-name').val('').focus();
            $('#modal-role-description').val('');
        }

        function modelClose() {
            $('#custom-modal').hide();
        }

        // ─── Image helpers ────────────────────────────────────────────────────────
        function updateImagePreview(url) {
            const fileName = url.split('/').pop();
            const previewHtml = `
                <div class="relative bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-600 rounded-lg shadow-sm overflow-hidden w-full max-w-md mx-auto">
                    <div class="flex items-center justify-between p-3 bg-gray-50 dark:bg-gray-700 border-b">
                        <div class="flex items-center gap-2 truncate">
                            <span class="iconify text-green-500" data-icon="tabler:photo" data-width="18"></span>
                            <span class="text-sm font-medium truncate">${fileName}</span>
                        </div>
                        <button type="button" id="remove-image-inline" class="text-red-600 hover:text-red-800 text-xs">
                            Remove
                        </button>
                    </div>
                    <div class="p-4 bg-gray-50 dark:bg-gray-700/50 flex justify-center">
                        <img src="${url}" alt="Preview" class="max-h-64 object-contain rounded-md">
                    </div>
                </div>`;

            $('#image-preview').html(previewHtml);
            $('#remove-featured-image').addClass('hidden'); // inline button handles removal
            setSelectBtnText('Change Image');
        }

        function clearImage() {
            $('#image').val('');
            $('#image-preview').empty();
            $('#remove-featured-image').addClass('hidden');
            setSelectBtnText('Select Image');
        }

        // ─── Document ready ───────────────────────────────────────────────────────
        $(document).ready(function() {

            if ($('#success-message').data('message'))
                nepalToast.success('Success', $('#success-message').data('message'));
            if ($('#error-message').data('message'))
                nepalToast.error('Error', $('#error-message').data('message'));

            $('#staff_name').focus();
            getstaffRoles();

            // ── Role dropdown ─────────────────────────────────────────────────────
            $('#staffRoleTrigger, label[for="staffRoleTrigger"]').on('click', function(e) {
                e.preventDefault();
                toggleRoleDropdown();
            });

            $(document).on('click', '.staff-role-item', function() {
                $('#staff_role_id').val($(this).data('id'));
                $('#selectedRoleText').text($(this).data('name'));
                toggleRoleDropdown();
            });

            $('#staffRoleSearch').on('keyup', function() {
                clearTimeout(searchTimeout);
                searchTimeout = setTimeout(() => getstaffRoles($(this).val()), 300);
            });

            $(document).on('click', function(e) {
                if (!$(e.target).closest(
                        '#staffRoleTrigger, #staffRoleDropdown, label[for="staffRoleTrigger"]').length) {
                    $('#staffRoleDropdown').addClass('hidden');
                    $('#staffRoleTrigger').find('.iconify[data-icon^="tabler:chevron"]').attr('data-icon',
                        'tabler:chevron-down');
                }
            });

            $('#createStaffRoleBtn').on('click', function() {
                toggleRoleDropdown();
                modelOpen();
            });

            // ── Modal ─────────────────────────────────────────────────────────────
            $(document).on('click', '.closeModal', modelClose);

            $('#staff-role-form').on('submit', function(e) {
                e.preventDefault();
                $.ajax({
                    url: "{{ route('staff-role.store') }}",
                    method: 'POST',
                    data: new FormData(this),
                    contentType: false,
                    processData: false,
                    success: function(response) {
                        if (response.status) {
                            nepalToast.success('Success', response.message);
                            modelClose();
                            getstaffRoles();
                        } else {
                            nepalToast.error('Error', response.message);
                        }
                    },
                    error: function(xhr) {
                        nepalToast.error('Error', xhr.responseJSON?.message ||
                            'Something went wrong.');
                    }
                });
            });

            // ── Media picker / image ──────────────────────────────────────────────
            window.handleImageSelection = function(mediaId, picker) {
                if (!mediaId) return;

                const modalItem = $(`#${picker.modalId}-grid .media-item[data-media-id="${mediaId}"]`);
                let mediaUrl = modalItem.data('media-url') ||
                    $(`.media-item[data-media-id="${mediaId}"]`).first().data('media-url');

                if (!mediaUrl) {
                    console.error('Image URL not found for ID:', mediaId);
                    return;
                }

                $('#image').val(mediaId);
                updateImagePreview(mediaUrl);
            };

            $('#remove-featured-image').on('click', clearImage);
            $(document).on('click', '#remove-image-inline', clearImage);

            try {
                window.featuredImagePicker = new MediaPickerClass('featured-image-media-picker', {
                    onSelect: window.handleImageSelection
                });
            } catch (e) {
                console.error('Media picker failed to initialize:', e);
            }
        });

        const bioQuill = new Quill('#bio_editor', {
        theme: 'snow',
        placeholder: 'Type bio...',
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

    bioQuill.on('text-change', function() {
        document.getElementById('bio').value = bioQuill.root.innerHTML;
    });
    </script>
@endpush

@push('modals')
    <div id="custom-modal" class="z-50 bg-black/50 fixed inset-0 flex items-center justify-center"
        style="display: none;">
        <div class="bg-white dark:bg-gray-700 rounded-lg shadow-lg w-full max-w-4xl p-4 md:p-5 relative">
            <div class="flex items-center justify-between border-b border-gray-200 dark:border-gray-600 pb-3">
                <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Create New Staff Role</h3>
                <span class="closeModal iconify cursor-pointer" data-icon="ic:baseline-close" data-width="24"></span>
            </div>

            <form class="mt-4 mb-0" id="staff-role-form">
                @csrf
                <div class="grid gap-4 mb-4 grid-cols-2">
                    <div class="col-span-2">
                        {{-- Unique IDs — avoid clashing with main form --}}
                        <label for="modal-role-name"
                            class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Name</label>
                        <input type="text" id="modal-role-name" name="name" placeholder="Type role name"
                            class="bg-gray-50 border border-gray-300 text-gray-900 rounded-lg text-sm focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-600 dark:border-gray-500 dark:text-white"
                            required>
                    </div>

                    <div class="col-span-2">
                        <label for="modal-role-description"
                            class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">
                            Description
                        </label>
                        <textarea id="modal-role-description" rows="4" placeholder="Write role description here" name="description"
                            class="block w-full p-2.5 text-sm text-gray-900 bg-gray-50 rounded-lg border border-gray-300 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-600 dark:border-gray-500 dark:text-white"></textarea>
                    </div>
                </div>

                <div class="flex justify-between gap-3">
                    <button type="button"
                        class="closeModal text-white bg-red-700 hover:bg-red-800 rounded-lg text-sm px-5 py-2.5 inline-flex items-center">
                        Close
                    </button>
                    <button type="submit"
                        class="text-white bg-blue-700 hover:bg-blue-800 rounded-lg text-sm px-5 py-2.5 inline-flex items-center">
                        Add new staff role
                    </button>
                </div>
            </form>
        </div>
    </div>
@endpush
