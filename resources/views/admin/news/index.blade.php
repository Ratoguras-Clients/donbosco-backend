@extends('layouts.app')

@section('content')
    @include('components.breadcrumb', [
        'breadcrumbs' => [['title' => 'News', 'url' => null]],
    ])

    <div class="premium-card bg-white dark:bg-gray-800 rounded-xl shadow-md border border-gray-200 dark:border-gray-700">
        <div
            class="premium-card-header flex flex-col md:flex-row justify-between items-center p-4 border-b border-gray-200 dark:border-gray-700">
            <div class="premium-card-title flex items-center mb-4 md:mb-0">
                <div
                    class="h-12 w-12 flex items-center justify-center rounded-md bg-gradient-to-br from-slate-500 to-slate-700 mr-3">
                    <i class="fas fa-folder text-white text-md"></i>
                </div>
                <div class="header-text">
                    <h4 class="text-lg font-semibold text-gray-800 dark:text-white">News Management</h4>
                    <p class="text-sm text-gray-500 dark:text-gray-400">Manage all News in the system</p>
                </div>
            </div>
            <div class="mt-4 md:mt-0 flex flex-col sm:flex-row space-y-3 sm:space-y-0 sm:space-x-3">
                <a href="{{ route('news.create', $organization->slug) }}"
                    class="block text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800">
                    Create news
                </a>
            </div>
        </div>

        <div class="premium-card-body">
            <div class="premium-table-container">
                <table class="premium-table" id="news-table">
                    <thead>
                        <tr>
                            <th width="10">SN</th>
                            <th width="100">Title</th>
                            <th width="150">Summary</th>
                            <th width="150">Content</th>
                            <th width="150">Featured Image</th>
                            <th width="150">Published Date</th>
                            <th width="100">Created By</th>
                            <th width="100">Actions</th>
                        </tr>
                    </thead>
                    <tbody></tbody>
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
                <div class="flex items-center gap-2 text-sm text-gray-500 dark:text-gray-400">
                    <label for="per-page-select" class="whitespace-nowrap">Rows per page:</label>
                    <div class="relative">
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
            <div class="pagination-container" id="custom-pagination"></div>
        </div>
    </div>

    <!-- Full Content / Summary Modal -->
    <div id="content-modal" class="fixed inset-0 z-50 hidden items-center justify-center bg-black bg-opacity-50">
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-xl w-full max-w-2xl mx-4 p-6">
            <div class="flex justify-between items-center mb-4 border-b border-gray-200 dark:border-gray-700 pb-3">
                <h3 id="content-modal-title" class="text-lg font-semibold text-gray-800 dark:text-white"></h3>
                <button id="close-content-modal"
                    class="text-gray-500 hover:text-gray-700 dark:text-gray-400 dark:hover:text-white transition-colors">
                    <i class="fas fa-times text-xl"></i>
                </button>
            </div>
            <div id="content-modal-body"
                class="text-gray-700 dark:text-gray-300 text-sm leading-relaxed max-h-96 overflow-y-auto prose dark:prose-invert max-w-none">
            </div>
        </div>
    </div>

    <!-- Image Preview Modal -->
    <div id="image-modal" class="fixed inset-0 z-50 hidden items-center justify-center bg-black bg-opacity-75">
        <div class="relative mx-4">
            <button id="close-image-modal"
                class="absolute -top-10 right-0 text-white hover:text-gray-300 transition-colors">
                <i class="fas fa-times text-2xl"></i>
            </button>
            <img id="image-modal-img" src="" alt="Featured Image"
                class="max-w-full max-h-[85vh] rounded-xl shadow-2xl object-contain" />
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        $(document).ready(function() {

            // Store full content here, keyed by row id
            var fullContentStore = {};

            var table_listing_table = $('#news-table').DataTable({
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
                        d.getData = 1;
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
                            var plainText = $('<div>').html(row.title).text();
                            var truncated = plainText.length > 20 ? plainText.substring(0, 20) +
                                '...' : plainText;
                            return row.title ? `
                                <div class="flex items-center">
                                    <span class="font-semibold">${truncated}</span>
                                </div>` : 'N/A';
                        },
                        "name": "title",
                        "orderable": false
                    },
                    {
                        "data": function(row) {
                            // Store full summary in JS object
                            fullContentStore['summary_' + row.id] = row.summary;

                            var plainText = $('<div>').html(row.summary).text();
                            var truncated = plainText.length > 20 ? plainText.substring(0, 20) +
                                '...' : plainText;
                            return row.summary ? `
                                <div class="flex items-center">
                                    <span class="font-semibold cursor-pointer hover:underline view-full-content"
                                        data-type="Summary"
                                        data-key="summary_${row.id}">${truncated}</span>
                                </div>` : 'N/A';
                        },
                        "name": "summary",
                        "orderable": false
                    },
                    {
                        "data": function(row) {
                            // Store full content in JS object
                            fullContentStore['content_' + row.id] = row.content;

                            var plainText = $('<div>').html(row.content).text();
                            var truncated = plainText.length > 20 ? plainText.substring(0, 20) +
                                '...' : plainText;
                            return row.content ? `
                                <div class="flex items-center">
                                    <span class="font-semibold cursor-pointer  hover:underline view-full-content"
                                        data-type="Content"
                                        data-key="content_${row.id}">${truncated}</span>
                                </div>` : 'N/A';
                        },
                        "name": "content",
                        "orderable": false
                    },
                    {
                        "data": function(row) {
                            return row.image ?
                                `<img src="${row.image}" class="h-12 rounded cursor-pointer hover:opacity-75 transition-opacity preview-image" data-src="${row.image}" title="Click to preview" />` :
                                '<span class="text-gray-500">N/A</span>';
                        },
                        "name": "image",
                        "orderable": false
                    },
                    {
                        "data": function(row) {
                            return `
                                <div class="flex items-center">
                                    <span class="font-semibold">${row.published_date}</span>
                                </div>`;
                        },
                        "name": "published_date",
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
                            var editUrl = "{{ route('news.edit', ':id') }}".replace(':id', row.id);
                            var statusBtn = `
                                <button type="button" class="toggle-status-btn rounded-md border border-transparent p-2 text-center text-sm transition-all ${row.is_published ? 'text-green-600 hover:bg-green-50' : 'text-red-600 hover:bg-red-50'}" data-id="${row.id}" data-name="${row.title}" data-status="${row.is_published}">
                                    <i class="fas ${row.is_published ? 'fa-toggle-on' : 'fa-toggle-off'} w-5 h-5"></i>
                                </button>
                            `;
                            return `
                                <div class="flex space-x-2">
                                    <a href="${editUrl}"
                                        class="rounded-md border border-transparent p-2 text-center text-sm transition-all text-slate-600 hover:bg-slate-100 focus:bg-slate-100 active:bg-slate-100">
                                        <i class="fas fa-edit w-4 h-4"></i>
                                    </a>
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

            // Per page selector
            $('#per-page-select').on('change', function() {
                var newLength = parseInt($(this).val());
                table_listing_table.page.len(newLength).draw();
            });

            $('#per-page-select').val(table_listing_table.page.len());

            // Pagination click
            $('#custom-pagination').on('click', '.page-link:not(.pointer-events-none)', function() {
                var page = $(this).data('page');
                table_listing_table.page(page).draw('page');
            });

            // ── Content / Summary Modal ──────────────────────────────────────

            $(document).on('click', '.view-full-content', function() {
                var type = $(this).data('type');
                var key = $(this).data('key');
                var content = fullContentStore[key] || '<p class="text-gray-400">No content available.</p>';
                $('#content-modal-title').text(type);
                $('#content-modal-body').html(content);
                $('#content-modal').removeClass('hidden').addClass('flex');
            });

            $('#close-content-modal').on('click', function() {
                $('#content-modal').removeClass('flex').addClass('hidden');
                $('#content-modal-body').html('');
            });

            $('#content-modal').on('click', function(e) {
                if ($(e.target).is('#content-modal')) {
                    $('#content-modal').removeClass('flex').addClass('hidden');
                    $('#content-modal-body').html('');
                }
            });

            // ── Image Preview Modal ──────────────────────────────────────────

            $(document).on('click', '.preview-image', function() {
                var src = $(this).data('src');
                $('#image-modal-img').attr('src', src);
                $('#image-modal').removeClass('hidden').addClass('flex');
            });

            $('#close-image-modal').on('click', function() {
                $('#image-modal').removeClass('flex').addClass('hidden');
                $('#image-modal-img').attr('src', '');
            });

            $('#image-modal').on('click', function(e) {
                if ($(e.target).is('#image-modal')) {
                    $('#image-modal').removeClass('flex').addClass('hidden');
                    $('#image-modal-img').attr('src', '');
                }
            });

            // ── ESC closes both modals ───────────────────────────────────────

            $(document).on('keydown', function(e) {
                if (e.key === 'Escape') {
                    $('#content-modal').removeClass('flex').addClass('hidden');
                    $('#content-modal-body').html('');
                    $('#image-modal').removeClass('flex').addClass('hidden');
                    $('#image-modal-img').attr('src', '');
                }
            });

            // ── Success message ──────────────────────────────────────────────

            const successMessage = $('#success-message').data('message');
            if (successMessage) {
                nepalToast.success('Success', successMessage);
            }

            // ── Toggle status ────────────────────────────────────────────────

            $(document).on('click', '.toggle-status-btn', function() {
                const id = $(this).data('id');
                $.ajax({
                    type: "GET",
                    url: "?status",
                    data: {
                        id: id
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

            // ── Delete ───────────────────────────────────────────────────────

            $(document).on('click', '.delete-btn', function() {
                var id = $(this).data('id');
                nepalConfirm.show({
                    title: 'Delete News',
                    message: `Are you sure you want to delete this news? This action cannot be undone.`,
                    type: 'danger',
                    confirmText: 'Delete News',
                    cancelText: 'Cancel',
                    confirmIcon: '<i class="fas fa-trash w-4.5 h-4.5"></i>'
                }).then(() => {
                    const url = "{{ route('news.destroy', [':slug', ':id']) }}"
                        .replace(':slug', "{{ $organization->slug }}")
                        .replace(':id', id);
                    $.ajax({
                        url: url,
                        type: "DELETE",
                        data: {
                            _token: "{{ csrf_token() }}"
                        },
                        success: function(response) {
                            nepalToast.success('Success', response.message ||
                                'News deleted successfully!');
                            table_listing_table.ajax.reload();
                        },
                        error: function(xhr) {
                            nepalToast.error('Error', xhr.responseJSON?.message ||
                                'An error occurred. Please try again.');
                        }
                    });
                }).catch(() => {
                    nepalToast.info('Action Canceled', 'News deletion was canceled.');
                });
            });

        });
    </script>
@endpush
