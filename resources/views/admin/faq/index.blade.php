@extends('layouts.app')

@section('content')
    @include('components.breadcrumb', [
        'breadcrumbs' => [['title' => 'FAQ', 'url' => null]],
    ])

    @if (session('success'))
        <div id="success-message" data-message="{{ session('success') }}" class="hidden"></div>
    @endif

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
                    <h4 class="text-lg font-semibold text-gray-800 dark:text-white">FAQ Management</h4>
                    <p class="text-sm text-gray-500 dark:text-gray-400">Manage all FAQ in the system</p>
                </div>
            </div>
            <div class="mt-4 md:mt-0 flex flex-col sm:flex-row space-y-3 sm:space-y-0 sm:space-x-3">
                <button id="openModal"
                    class="block text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800">
                    Create FAQ
                </button>
            </div>
        </div>

        <div class="premium-card-body">
            <div class="premium-table-container">
                <table class="premium-table" id="staff-role-table">
                    <thead>
                        <tr>
                            <th width="10">SN</th>
                            <th width="200">Question</th>
                            <th width="200">Answer</th>
                            <th width="150">Created By</th>
                            <th width="150">Updated By</th>
                            <th width="100">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                    </tbody>
                </table>
            </div>
        </div>

        <div
            class="premium-card-footer flex flex-col sm:flex-row justify-between items-center p-4 border-t border-gray-200 dark:border-gray-700 gap-4">
            <div class="flex items-center gap-4 flex-wrap">
                <div class="table-info flex items-center text-sm text-gray-500 dark:text-gray-400">
                    <i class="fas fa-info-circle mr-1 w-4 h-4"></i>
                    <span id="showing-entries"></span>
                </div>
                <!-- Per Page Selector -->
                <div class="flex items-center gap-2 text-sm text-gray-500 dark:text-gray-400">
                    <label for="per-page-select" class="whitespace-nowrap">Rows per page:</label>
                    <div class="relative">
                        {{-- Added inline style to force remove default arrow across all browsers --}}
                        <select id="per-page-select"
                            style="-webkit-appearance: none; -moz-appearance: none; appearance: none;"
                            class="px-3 py-1.5 pr-8 border border-gray-300 dark:border-gray-600 rounded-md bg-white dark:bg-gray-800 text-gray-700 dark:text-gray-300 focus:ring-1 focus:ring-blue-500 focus:border-blue-500 text-sm cursor-pointer">
                            <option value="10">10</option>
                            <option value="20">20</option>
                            <option value="50">50</option>
                            <option value="100">100</option>
                        </select>
                        <div class="absolute inset-y-0 right-0 flex items-center pr-2 pointer-events-none">
                            <i class="fas fa-chevron-down text-gray-400 text-xs"></i>
                        </div>
                    </div>
                </div>
            </div>
            <div class="pagination-container" id="custom-pagination">
                <!-- Pagination will be rendered here -->
            </div>
        </div>
    </div>
@endsection

@push('modals')
    <div id="custom-modal" class="z-50 bg-black/50 fixed inset-0 flex items-center justify-center" style="display: none;">
        <div class="bg-white dark:bg-gray-700 rounded-lg shadow-lg w-full max-w-4xl p-4 md:p-5 relative">
            <!-- Modal Header -->
            <div class="flex items-center justify-between border-b border-gray-200 dark:border-gray-600 pb-3">
                <h3 id="model-title" class="text-lg font-semibold text-gray-900 dark:text-white">Create New FAQ</h3>
                <span id="" class=" closeModal iconify cursor-pointer" data-icon="ic:baseline-close"
                    data-width="24"></span>
            </div>

            <!-- Modal Body -->
            <form class="mt-4 mb-0" id="staff-role-form">
                @csrf
                <div class="grid gap-4 mb-4 grid-cols-2">
                    <div class="col-span-2">
                        <label for="question"
                            class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Question</label>
                        <input type="text" id="question" name="question" placeholder="Enter the question"
                            class="bg-gray-50 border border-gray-300 text-gray-900 rounded-lg text-sm focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-600 dark:border-gray-500 dark:text-white"
                            required>
                    </div>

                    <div class="col-span-2">
                        <label for="answer" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">
                            Answer
                        </label>
                        <textarea id="answer" rows="4" placeholder="Enter the answer" name="answer"
                            class="block w-full p-2.5 text-sm text-gray-900 bg-gray-50 rounded-lg border border-gray-300 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-600 dark:border-gray-500 dark:text-white"></textarea>
                    </div>
                </div>

                <div class="flex justify-between gap-3">
                    <button type="button"
                        class="closeModal text-white bg-red-700 hover:bg-red-800 rounded-lg text-sm px-5 py-2.5 inline-flex items-center">
                        Close
                    </button>

                    <button type="submit" id="submit-faq-btn"
                        class="text-white bg-blue-700 hover:bg-blue-800 rounded-lg text-sm px-5 py-2.5 inline-flex items-center">
                        Create New FAQ
                    </button>
                </div>
            </form>
        </div>
    </div>
@endpush


@push('scripts')
    <script>
        function modelOpen() {
            $('#custom-modal').show();
            $('#question').val('').focus();
            $('#answer').val('');
            $('#model-title').val('');
            $('#staff-role-form').data('id', null);
            $('#model-title').text('Create New FAQ');
            $('#submit-faq-btn').text('Create New FAQ');
        }

        function getId() {
            return $('#staff-role-form').data('id');
        }

        $(document).ready(function() {
            var table_listing_table = $('#staff-role-table').DataTable({
                "dom": "<'row'<'col-sm-12'tr>>",
                "aLengthMenu": [
                    [10, 20, 50, 100],
                    [10, 20, 50, "100"]
                ],
                "iDisplayLength": 10,
                "language": {
                    "lengthMenu": "_MENU_",
                    emptyTable: "<div class='py-2 flex items-center justify-center flex-col'><p class='text-gray-500 dark:text-gray-400 text-md font-bold'>No records found</p></div>",
                    zeroRecords: "<div class='py-2 flex items-center justify-center flex-col'><p class='text-gray-500 dark:text-gray-400 text-md font-bold'>No records found</p></div>",
                    search: "",
                    searchPlaceholder: "{{ __('Search') }}",
                    "processing": "<div class='shimmer p-4'><div class='animate-pulse rounded-full h-8 w-8 bg-gray-300 dark:bg-gray-600 mx-auto'></div><span class='block text-center mt-2 text-gray-500 dark:text-gray-400'>Loading...</span></div>",
                    info: ""
                },
                "bSort": false,
                "processing": true,
                "serverSide": true,
                "ordering": false,
                "searching": false,
                "stateSave": true,
                "paging": true,
                "footer": true,
                "ajax": {
                    "url": "?getData",
                    "data": function(d) {
                        d.ajax = 1;
                    },
                    "error": function(xhr) {
                        nepalToast.error('Error', xhr.responseJSON?.message ||
                            'Failed to load batch data.');
                    }
                },
                "columns": [{
                        "data": function(row) {
                            return `<span class="font-semibold">${row.sn}</span>`;
                        },
                        "name": "sn",
                        "orderable": false
                    },
                    {
                        "data": function(row) {
                            var plainText = $('<div>').html(row.question).text();
                            var truncated = plainText.length > 30 ? plainText.substring(0, 30) +
                                '...' : plainText;
                            return row.question ? `
            <div class="flex items-center">
                <span class="font-semibold">${truncated}</span>
                ${row.is_active ? '<span class="inline-block w-3 h-3 rounded-full bg-green-500 ml-2"></span>' : '<span class="inline-block w-3 h-3 rounded-full bg-red-500 ml-2"></span>'}
            </div>` : 'N/A';
                        },
                        "name": "question",
                        "orderable": false
                    },
                    {
                        "data": function(row) {
                            var plainText = $('<div>').html(row.answer).text();
                            var truncated = plainText.length > 30 ? plainText.substring(0, 30) +
                                '...' : plainText;
                            return row.answer ? `<span class="font-semibold">${truncated}</span>` :
                                'N/A';
                        },
                        "name": "answer",
                        "orderable": false
                    },
                    {
                        "data": function(row) {
                            return row.created_by ?
                                `<span class="role-badge px-2 py-1 text-xs rounded-full bg-sky-200 dark:bg-blue-900/50 text-blue-800 dark:text-blue-300">${row.created_by}</span>` :
                                '<span class="text-gray-500">N/A</span>';
                        },
                        "name": "created_by",
                        "orderable": false
                    },
                    {
                        "data": function(row) {
                            return row.updated_by ?
                                `<span class="role-badge px-2 py-1 text-xs rounded-full bg-sky-200 dark:bg-blue-900/50 text-blue-800 dark:text-blue-300">${row.updated_by}</span>` :
                                '<span class="text-gray-500">N/A</span>';
                        },
                        "name": "updated_by",
                        "orderable": false
                    },
                    {
                        "data": function(row) {

                            var statusBtn =
                                `
                                    <button type="button" class="toggle-status-btn rounded-md border border-transparent p-2 text-center text-sm transition-all ${row.is_active ? 'text-green-600 hover:bg-green-50' : 'text-red-600 hover:bg-red-50'}" data-id="${row.id}" data-name="${row.name}" data-status="${row.is_active}">
                                        <i class="fas ${row.is_active ? 'fa-toggle-on' : 'fa-toggle-off'} w-5 h-5"></i>
                                    </button>
                                `;

                            return `
                                    <div class="flex space-x-2">
                                        <button type="button" class="rounded-md border border-transparent p-2 text-center text-sm transition-all text-slate-600 hover:bg-slate-100 focus:bg-slate-100 active:bg-slate-100 disabled:pointer-events-none disabled:opacity-50 disabled:shadow-none edit-btn"
                                            data-id="${row.id}" data-question="${row.question}"
                                            data-answer="${row.answer}">
                                            <i class="fas fa-edit w-4 h-4"></i>
                                        </button>
                                        <button type="button" class="rounded-md border border-transparent p-2 text-center text-sm transition-all text-slate-600 hover:bg-slate-100 focus:bg-slate-100 active:bg-slate-100 disabled:pointer-events-none disabled:opacity-50 disabled:shadow-none delete-btn" data-id="${row.id}">
                                            <i class="fas fa-trash w-4 h-4"></i>
                                        </button>
                                        ${statusBtn}
                                    </div>
                                `;
                        },
                        "name": "actions",
                        "orderable": false
                    }
                ],
                "drawCallback": function(settings) {
                    var api = this.api();
                    var pageInfo = api.page.info();

                    $('#showing-entries').text(
                        `Showing ${pageInfo.start + 1} to ${pageInfo.end} of ${pageInfo.recordsDisplay} entries`
                    );

                    var paginationHtml = '';
                    paginationHtml += `
                        <div class="page-item ${pageInfo.page === 0 ? 'opacity-50 cursor-not-allowed' : ''}">
                            <button class="page-link px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-l-md ${pageInfo.page === 0 ? 'pointer-events-none' : ''}" data-page="${pageInfo.page - 1}">
                                <i class="fas fa-chevron-left w-4 h-4"></i>
                            </button>
                        </div>`;

                    var startPage = Math.max(0, pageInfo.page - 2);
                    var endPage = Math.min(pageInfo.pages - 1, pageInfo.page + 2);

                    for (var i = startPage; i <= endPage; i++) {
                        paginationHtml += `
                            <div class="page-item ${pageInfo.page === i ? 'bg-blue-600 text-white' : 'bg-white dark:bg-gray-800 text-gray-700 dark:text-gray-300'}">
                                <button class="page-link px-3 py-2 border border-gray-300 dark:border-gray-600" data-page="${i}">${i + 1}</button>
                            </div>`;
                    }

                    paginationHtml += `
                        <div class="page-item ${pageInfo.page === pageInfo.pages - 1 ? 'opacity-50 cursor-not-allowed' : ''}">
                            <button class="page-link px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-r-md ${pageInfo.page === pageInfo.pages - 1 ? 'pointer-events-none' : ''}" data-page="${pageInfo.page + 1}">
                                <i class="fas fa-chevron-right w-4 h-4"></i>
                            </button>
                        </div>`;

                    $('#custom-pagination').html(paginationHtml);
                }
            });

            // Per page selector - add this after table initialization
            $('#per-page-select').on('change', function() {
                var newLength = parseInt($(this).val());
                table_listing_table.page.len(newLength).draw();
            });

            // Set initial value
            $('#per-page-select').val(table_listing_table.page.len());



            $('#custom-pagination').on('click', '.page-link:not(.pointer-events-none)', function() {
                var page = $(this).data('page');
                table_listing_table.page(page).draw('page');
            });

            $('#openModal').click(function() {
                modelOpen();
            });

            $(document).on('click', '.closeModal', function() {
                $('#custom-modal').hide();
            });

            $(document).on('click', '#custom-modal', function(e) {
                if ($(e.target).is('#custom-modal')) {
                    $('#custom-modal').hide();
                }
            });

            $('#staff-role-form').on('submit', function(e) {
                e.preventDefault();
                let formData = new FormData(this);
                const id = getId();
                if (id) {
                    formData.append('id', id);
                }
                const url = "{{ route('faq.store', $slug) }}";
                $.ajax({
                    type: "POST",
                    url: url,
                    data: formData,
                    contentType: false,
                    processData: false,
                    success: function(response) {
                        if (response.status) {
                            nepalToast.success('Success', response.message);
                            table_listing_table.ajax.reload();
                            $('#custom-modal').hide();
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

            $(document).on('click', '.edit-btn', function() {
                const id = $(this).data('id');
                const question = $(this).data('question');
                const answer = $(this).data('answer');
                modelOpen();
                $('#staff-role-form').data('id', id);
                $('#model-title').text('Edit FAQ');
                $('#submit-faq-btn').text('Update FAQ');
                $('#question').val(question).focus();
                $('#answer').val(answer);
            });

            $(document).on('click', '.delete-btn', function() {
                var id = $(this).data('id');

                nepalConfirm.show({
                    title: 'Delete FAQ',
                    message: `Are you sure you want to delete this FAQ? This action cannot be undone.`,
                    type: 'danger',
                    confirmText: 'Delete FAQ',
                    cancelText: 'Cancel',
                    confirmIcon: '<i class="fas fa-trash w-4.5 h-4.5"></i>'
                }).then(() => {
                    const url = "{{ route('faq.destroy', ':id') }}".replace(':id', id);
                    $.ajax({
                        url: url,
                        type: "DELETE",
                        data: {
                            _token: "{{ csrf_token() }}"
                        },
                        success: function(response) {
                            nepalToast.success('Success', response.message ||
                                'Staff role deleted successfully!');
                            table_listing_table.ajax.reload();
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
