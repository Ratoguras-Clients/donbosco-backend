@extends('layouts.app')

@section('content')
    @include('components.breadcrumb', [
        'breadcrumbs' => [['title' => 'Collections', 'url' => null]],
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
                    <h4 class="text-lg font-semibold text-gray-800 dark:text-white">Collection Management</h4>
                    <p class="text-sm text-gray-500 dark:text-gray-400">Manage all collections in the system</p>
                </div>
            </div>
            <div class="mt-4 md:mt-0 flex flex-col sm:flex-row space-y-3 sm:space-y-0 sm:space-x-3">
                <a href="{{ route('collections.create', $organization->slug) }}"
                    class="block text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800">
                    Create Collection
                </a>
            </div>
        </div>

        <div class="premium-card-body">
            <div class="premium-table-container">
                <table class="premium-table" id="collection-table">
                    <thead>
                        <tr>
                            <th width="10">SN</th>
                            <th width="200">Title</th>
                            <th width="150">Description</th>
                            <th width="220">Cover Images</th>
                            <th width="150">Status</th>
                            <th width="150">Created By</th>
                            <th width="150">Updated By</th>
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
@endsection

@push('scripts')
    <script>
        $(document).ready(function () {
            const routes = {
                destroy: id => "{{ route('collections.destroy', [$organization->slug, ':id']) }}".replace(':id', id),
            };

            var table_listing_table = $('#collection-table').DataTable({
                dom: "<'row'<'col-sm-12'tr>>",
                aLengthMenu: [[10, 20, 50, 100], [10, 20, 50, "100"]],
                iDisplayLength: 10,
                language: {
                    lengthMenu: "_MENU_",
                    emptyTable: "<div class='py-2 flex items-center justify-center flex-col'><p class='text-gray-500 dark:text-gray-400 text-md font-bold'>No records found</p></div>",
                    zeroRecords: "<div class='py-2 flex items-center justify-center flex-col'><p class='text-gray-500 dark:text-gray-400 text-md font-bold'>No records found</p></div>",
                    search: "",
                    searchPlaceholder: "{{ __('Search') }}",
                    processing: "<div class='shimmer p-4'><div class='animate-pulse rounded-full h-8 w-8 bg-gray-300 dark:bg-gray-600 mx-auto'></div><span class='block text-center mt-2 text-gray-500 dark:text-gray-400'>Loading...</span></div>",
                    info: ""
                },
                bSort: false,
                processing: true,
                serverSide: true,
                ordering: false,
                searching: false,
                stateSave: true,
                paging: true,
                ajax: {
                    url: "?getData",
                    data: function (d) { d.ajax = 1; },
                    error: function (xhr) {
                        nepalToast.error('Error', xhr.responseJSON?.message || 'Failed to load collection data.');
                    }
                },
                columns: [
                    { data: 'sn', name: 'sn', orderable: false },
                    { data: 'title', name: 'title', orderable: false },
                    { data: 'description', name: 'description', orderable: false },
                    {
                        data: 'cover_images',
                        name: 'cover_images',
                        orderable: false,
                        render: function (data) {
                            if (!data || !data.length) {
                                return '<span class="text-gray-400 text-xs">No images</span>';
                            }

                            const imgs = data.map(url => `
                                <img src="${url}" alt="cover"
                                     class="h-10 w-12 object-cover rounded border border-gray-200 dark:border-gray-600 flex-shrink-0"
                                     onerror="this.src='/images/placeholder.png'">
                            `).join('');

                            return `<div class="flex flex-wrap gap-1">${imgs}</div>`;
                        }
                    },
                    {
                        data: 'is_active',
                        name: 'is_active',
                        orderable: false,
                        render: function (data, type, row) {
                            const statusClass = data == 1
                                ? 'bg-green-100 text-green-800 dark:bg-green-900/40 dark:text-green-300'
                                : 'bg-red-100 text-red-800 dark:bg-red-900/40 dark:text-red-300';
                            const statusText = data == 1 ? 'Active' : 'Inactive';

                            return `<span class="toggle-status px-2 py-1 text-xs font-semibold rounded-full cursor-pointer ${statusClass}"
                                        data-id="${row.id}" data-status="${data}">
                                        ${statusText}
                                    </span>`;
                        }
                    },
                    {
                        data: 'created_by',
                        name: 'created_by',
                        orderable: false,
                        render: function (data) {
                            return data
                                ? `<span class="px-2 py-1 text-xs rounded-full bg-blue-100 dark:bg-blue-900/50 text-blue-800 dark:text-blue-300">${data}</span>`
                                : '<span class="text-gray-500">N/A</span>';
                        }
                    },
                    {
                        data: 'updated_by',
                        name: 'updated_by',
                        orderable: false,
                        render: function (data) {
                            return data
                                ? `<span class="px-2 py-1 text-xs rounded-full bg-blue-100 dark:bg-blue-900/50 text-blue-800 dark:text-blue-300">${data}</span>`
                                : '<span class="text-gray-500">N/A</span>';
                        }
                    },
                    {
                        data: function (row) {
                            const editUrl    = "{{ route('collections.edit', ['id' => ':id']) }}".replace(':id', row.id);
                            const galleryUrl = "{{ route('gallery-items.index', ['id' => ':id']) }}".replace(':id', row.id);

                            return `
                                <div class="flex space-x-2">
                                    <a href="${galleryUrl}"
                                       class="rounded-md border border-transparent p-2 text-center text-sm transition-all text-slate-600 hover:bg-slate-100"
                                       title="Manage Gallery">
                                        <i class="fas fa-images w-4 h-4"></i>
                                    </a>
                                    <a href="${editUrl}"
                                       class="rounded-md border border-transparent p-2 text-center text-sm transition-all text-slate-600 hover:bg-slate-100"
                                       title="Edit">
                                        <i class="fas fa-edit w-4 h-4"></i>
                                    </a>
                                    <button type="button"
                                            class="delete-btn rounded-md border border-transparent p-2 text-center text-sm transition-all text-red-600 hover:bg-red-100"
                                            data-id="${row.id}">
                                        <i class="fas fa-trash w-4 h-4"></i>
                                    </button>
                                </div>
                            `;
                        },
                        name: 'actions',
                        orderable: false
                    }
                ],
                drawCallback: function (settings) {
                    const api      = this.api();
                    const pageInfo = api.page.info();

                    $('#showing-entries').text(
                        `Showing ${pageInfo.start + 1} to ${pageInfo.end} of ${pageInfo.recordsDisplay} entries`
                    );

                    let paginationHtml = '';

                    paginationHtml += `
                        <div class="page-item ${pageInfo.page === 0 ? 'opacity-50 cursor-not-allowed' : ''}">
                            <button class="page-link px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-l-md ${pageInfo.page === 0 ? 'pointer-events-none' : ''}"
                                    data-page="${pageInfo.page - 1}">
                                <i class="fas fa-chevron-left w-4 h-4"></i>
                            </button>
                        </div>`;

                    const startPage = Math.max(0, pageInfo.page - 2);
                    const endPage   = Math.min(pageInfo.pages - 1, pageInfo.page + 2);

                    for (let i = startPage; i <= endPage; i++) {
                        paginationHtml += `
                            <div class="page-item ${pageInfo.page === i ? 'bg-blue-600 text-white' : 'bg-white dark:bg-gray-800 text-gray-700 dark:text-gray-300'}">
                                <button class="page-link px-3 py-2 border border-gray-300 dark:border-gray-600"
                                        data-page="${i}">${i + 1}</button>
                            </div>`;
                    }

                    paginationHtml += `
                        <div class="page-item ${pageInfo.page === pageInfo.pages - 1 ? 'opacity-50 cursor-not-allowed' : ''}">
                            <button class="page-link px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-r-md ${pageInfo.page === pageInfo.pages - 1 ? 'pointer-events-none' : ''}"
                                    data-page="${pageInfo.page + 1}">
                                <i class="fas fa-chevron-right w-4 h-4"></i>
                            </button>
                        </div>`;

                    $('#custom-pagination').html(paginationHtml);
                }
            });

            $('#per-page-select').on('change', function () {
                table_listing_table.page.len(parseInt($(this).val())).draw();
            });
            $('#per-page-select').val(table_listing_table.page.len());

            $('#custom-pagination').on('click', '.page-link:not(.pointer-events-none)', function () {
                table_listing_table.page($(this).data('page')).draw('page');
            });

            $(document).on('click', '.delete-btn', function () {
                const id = $(this).data('id');

                nepalConfirm.show({
                    title: 'Delete Collection',
                    message: 'Are you sure you want to delete this collection? This action cannot be undone.',
                    type: 'danger',
                    confirmText: 'Delete',
                    confirmIcon: '<i class="fas fa-trash w-4.5 h-4.5"></i>'
                }).then(() => {
                    $.ajax({
                        url: routes.destroy(id),
                        type: 'POST',
                        data: { _token: "{{ csrf_token() }}", _method: 'DELETE' },
                        success: function (response) {
                            nepalToast.success('Success', response.message || 'Collection deleted successfully.');
                            table_listing_table.ajax.reload(null, false);
                        },
                        error: function (xhr) {
                            nepalToast.error('Error', xhr.responseJSON?.message || 'Failed to delete collection.');
                        }
                    });
                }).catch(() => {
                    nepalToast.info('Canceled', 'Collection deletion was canceled.');
                });
            });

            $(document).on('click', '.toggle-status', function () {
                const span         = $(this);
                const id           = span.data('id');
                const currentStatus = span.data('status');

                $.ajax({
                    url: "{{ route('collections.toggleStatus', $organization->slug) }}",
                    type: 'POST',
                    data: { _token: "{{ csrf_token() }}", id: id, status: currentStatus },
                    success: function (response) {
                        span.data('status', response.new_status);

                        if (response.new_status == 1) {
                            span.removeClass('bg-red-100 text-red-800 dark:bg-red-900/40 dark:text-red-300')
                                .addClass('bg-green-100 text-green-800 dark:bg-green-900/40 dark:text-green-300')
                                .text('Active');
                        } else {
                            span.removeClass('bg-green-100 text-green-800 dark:bg-green-900/40 dark:text-green-300')
                                .addClass('bg-red-100 text-red-800 dark:bg-red-900/40 dark:text-red-300')
                                .text('Inactive');
                        }

                        nepalToast.success('Success', response.message);
                    },
                    error: function (xhr) {
                        nepalToast.error('Error', xhr.responseJSON?.message || 'Failed to update status.');
                    }
                });
            });

            const successMessage = $('#success-message').data('message');
            if (successMessage) nepalToast.success('Success', successMessage);
        });
    </script>
@endpush