@extends('layouts.app')

@section('content')
    @include('components.breadcrumb', [
        'breadcrumbs' => [
            ['title' => $organization->name, 'url' => null],
            ['title' => 'Staff Management', 'url' => null],
        ],
    ])

    <!-- DataTable -->
    <div class="premium-card bg-white dark:bg-gray-800 rounded-xl shadow-md border border-gray-200 dark:border-gray-700">
        <div
            class="premium-card-header flex flex-col md:flex-row justify-between items-center p-4 border-b border-gray-200 dark:border-gray-700">
            <div class="premium-card-title flex items-center mb-4 md:mb-0">
                <div
                    class="h-12 w-12 flex items-center justify-center rounded-md bg-gradient-to-br from-slate-500 to-slate-700 mr-3">
                    <i class="fas fa-folder text-white text-md"></i>
                </div>
                <div class="header-text">
                    <h4 class="text-lg font-semibold text-gray-800 dark:text-white">Staff Management</h4>
                    <p class="text-sm text-gray-500 dark:text-gray-400">
                        Manage all Staff in the {{ $organization->name }}
                    </p>
                </div>
            </div>
            <div class="mt-4 md:mt-0 flex flex-col sm:flex-row space-y-3 sm:space-y-0 sm:space-x-3">
                <a href="{{ route('organization-staff.create', $organization->slug) }}"
                    class="block text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800">
                    Add Staff
                </a>
                <a href="{{ route('dashboard') }}"
                    class="px-4 py-2 bg-gray-200 dark:bg-gray-900 text-gray-700 rounded-md hover:bg-gray-300 flex items-center">
                    <span class="iconify mr-2" data-icon="tabler:arrow-left" data-width="18"></span>
                    Back
                </a>
            </div>
        </div>

        <div class="premium-table-container px-3 flex gap-3">
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6 p-4 w-full">
                @foreach ($staffs as $staff)
                    <div
                        class="relative bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-xl shadow-md overflow-hidden">

                        <!-- Top Color Strip -->
                        <div class="h-20 bg-gradient-to-r from-blue-600 to-indigo-700"></div>

                        <!-- Avatar -->
                        <div class="flex justify-center -mt-10">
                            <div
                                class="h-20 w-20 rounded-full bg-white dark:bg-gray-700 flex items-center justify-center border-4 border-white dark:border-gray-800 shadow-md">
                                <span class="text-2xl font-bold text-blue-600 dark:text-blue-400">
                                    {{ strtoupper(substr($staff->name, 0, 1)) }}
                                </span>
                            </div>
                        </div>

                        <!-- Content -->
                        <div class="text-center px-4 py-4">
                            <h5 class="text-lg font-semibold text-gray-800 dark:text-white">
                                {{ $staff->name }}
                            </h5>

                            <p class="text-sm text-gray-500 dark:text-gray-400">
                                {{ $staff->getStaffRole->staffRole->name ?? 'Staff Member' }}
                            </p>

                            <div class="mt-3 text-sm text-gray-600 dark:text-gray-300">
                                <p>{{ $staff->email }}</p>
                                <p class="mt-1 font-medium">
                                    {{ $organization->name }}
                                </p>
                            </div>
                        </div>

                        <!-- Actions -->
                        <div
                            class="flex justify-between items-center px-4 py-3 border-t border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-900">
                            <a href="{{ route('organization-staff.edit', [
                                'slug' => $organization->slug,
                                'id' => $staff->id,
                            ]) }}"
                                class="text-sm text-blue-600 hover:underline font-medium">
                                Edit
                            </a>

                            {{-- <button type="button" data-staff_id="{{ $staff->id }}"
                                class="message-btn text-sm text-red-600 hover:underline font-medium">
                                Message
                            </button> --}}

                            <button type="button" data-id="{{ $staff->id }}"
                                class="delete-btn text-sm text-red-600 hover:underline font-medium">
                                Delete
                            </button>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>
@endsection

@push('modals')
    <div id="custom-modal" class="z-50 bg-black/50 fixed inset-0 flex items-center justify-center" style="display: none;">
        <div class="bg-white dark:bg-gray-700 rounded-lg shadow-lg w-full max-w-4xl p-4 md:p-5 relative">
            <!-- Modal Header -->
            <div class="flex items-center justify-between border-b border-gray-200 dark:border-gray-600 pb-3">
                <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Create New Staff Role</h3>
                <span id="" class=" closeModal iconify cursor-pointer" data-icon="ic:baseline-close"
                    data-width="24"></span>
            </div>

            <!-- Modal Body -->
            <form class="mt-4 mb-0" id="staff-role-form">
                @csrf
                <div class="grid gap-4 mb-4 grid-cols-2">
                    <div class="col-span-2">
                        <label for="name"
                            class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Name</label>
                        <input type="text" id="name" name="name" placeholder="Type product name"
                            class="bg-gray-50 border border-gray-300 text-gray-900 rounded-lg text-sm focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-600 dark:border-gray-500 dark:text-white"
                            required>
                    </div>

                    <div class="col-span-2">
                        <label for="description" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">
                            Description
                        </label>
                        <textarea id="description" rows="4" placeholder="Write product description here" name="description"
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

    <div id="message-modal" class="z-50 bg-black/50 fixed inset-0 flex items-center justify-center" style="display: none;">
        <div class="bg-white dark:bg-gray-700 rounded-lg shadow-lg w-full max-w-4xl p-4 md:p-5 relative">
            <!-- Modal Header -->
            <div class="flex items-center justify-between border-b border-gray-200 dark:border-gray-600 pb-3">
                <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Create New Staff Role</h3>
                <span id="" class=" closeModal iconify cursor-pointer" data-icon="ic:baseline-close"
                    data-width="24"></span>
            </div>

            <!-- Modal Body -->
            <form class="mt-4 mb-0" id="staff-message-form">
                @csrf
                <input type="hidden" id="staff_id" name="staff_id">
                <input type="hidden" id="id" name="id">

                <div class="col-span-2">
                    <label for="title" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Name</label>
                    <input type="text" id="title" name="title" placeholder="Enter title"
                        class="bg-gray-50 border border-gray-300 text-gray-900 rounded-lg text-sm focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-600 dark:border-gray-500 dark:text-white"
                        required>
                </div>

                <div class="grid gap-4 mb-4 grid-cols-2">
                    <div class="col-span-2">
                        <label for="message" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">
                            Message
                        </label>
                        <textarea id="message" rows="4" placeholder="Write message here" name="message"
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


@push('scripts')
    <script>
        function openMessageModal(staff_id) {
            $('#staff_id').val(staff_id);
            $('#id').val('');
            $('#title').val('');
            $('#message').val('');
            $('#message-modal').show();
        }

        function closeModal() {
            $('#message-modal').hide();
        }

        function checkMessage(staff_id, organizationId) {
            const url =
                "{{ route('message.exists', ['staffId' => '__STAFF__', 'organizationId' => '__ORG__']) }}"
                .replace('__STAFF__', staff_id)
                .replace('__ORG__', organizationId);

            $.ajax({
                type: "POST", // (GET would be better, but POST is fine for now)
                url: url,
                data: {
                    _token: "{{ csrf_token() }}",
                },
                success: function(response) {
                    if (response.exists && response.data) {
                        $('#staff_id').val(staff_id);
                        $('#id').val(response.data.id);
                        $('#title').val(response.data.title);
                        $('#message').val(response.data.content);
                        $('#message-modal').show();
                    } else {
                        openMessageModal(staff_id);
                    }
                },
                error: function(xhr) {
                    nepalToast.error(
                        'Error',
                        xhr.responseJSON?.message || 'An error occurred. Please try again.'
                    );
                }
            });
        }

        $(document).ready(function() {
            var organizationId = @json($organization->id);

            $('.message-btn').on('click', function() {
                const staff_id = $(this).data('staff_id');
                checkMessage(staff_id, organizationId);
            });

            $('.closeModal').on('click', function() {
                closeModal();
            });

            $('#staff-message-form').on('submit', function(e) {
                e.preventDefault();

                const url = "{{ route('organization-staff.storeMessage', ':slug') }}".replace(':slug',
                    '{{ $organization->slug }}');
                console.log(url);
                const formData = new FormData(this);

                $.ajax({
                    url: url,
                    type: "POST",
                    data: formData,
                    processData: false,
                    contentType: false,
                    success: function(response) {
                        if (response.success) {
                            nepalToast.success('Success', response.message ||
                                'Message sent successfully.');
                            $('#message-modal').hide();
                        } else {
                            nepalToast.error('Error', response.message);
                        }
                    },
                    error: function(xhr) {
                        nepalToast.error('Error', xhr.responseJSON?.message ||
                            'An error occurred. Please try again.');
                    }
                });
            });

            const successMessage = $('#success-message').data('message');
            if (successMessage) {
                nepalToast.success('Success', successMessage);
            }

            $(document).on('click', '.toggle-status-btn', function() {
                const id = $(this).data('id');

                $.ajax({
                    type: "GET",
                    url: "?status",
                    data: {
                        id: id,
                    },
                    success: function(response) {
                        if (response.status) {
                            nepalToast.success('Success', response.message ||
                                'Status updated successfully.');
                            table_listing_table.ajax.reload();
                        } else {
                            nepalToast.error('Error', response.message ||
                                'An error occurred. Please try again.');
                        }
                    },
                    error: function(xhr) {
                        nepalToast.error('Error', xhr.responseJSON?.message ||
                            'An error occurred. Please try again.');
                    }
                });
            });

            $(document).on('click', '.delete-btn', function() {
                var id = $(this).data('id');

                nepalConfirm.show({
                    title: 'Delete Staff Role',
                    message: `Are you sure you want to delete this staff role? This action cannot be undone.`,
                    type: 'danger',
                    confirmText: 'Delete Staff Role',
                    cancelText: 'Cancel',
                    confirmIcon: '<i class="fas fa-trash w-4.5 h-4.5"></i>'
                }).then(() => {
                    const url = "{{ route('organization-staff.delete', ':id') }}".replace(':id',
                        id);
                    $.ajax({
                        url: url,
                        type: "DELETE",
                        data: {
                            _token: "{{ csrf_token() }}"
                        },
                        success: function(response) {
                            nepalToast.success('Success', response.message ||
                                'Staff role deleted successfully!');
                            $('[data-id="' + id + '"]').closest('.relative.bg-white')
                                .fadeOut(500, function() {
                                    $(this).remove();
                                });
                        },
                        error: function(xhr) {
                            nepalToast.error('Error', xhr.responseJSON?.message ||
                                'An error occurred. Please try again.');
                        }
                    });
                }).catch(() => {
                    nepalToast.info('Action Canceled', 'Staff role deletion was canceled.');
                });
            });
        });
    </script>
@endpush
