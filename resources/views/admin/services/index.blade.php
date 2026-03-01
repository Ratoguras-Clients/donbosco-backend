@extends('layouts.app')

@section('content')
    @include('components.breadcrumb', [
        'breadcrumbs' => [['title' => 'Service', 'url' => null]],
    ])

    @if (session('success'))
        <div id="success-message" data-message="{{ session('success') }}" class="hidden"></div>
    @endif

    <div class="premium-card bg-white dark:bg-gray-800 rounded-xl shadow-md border border-gray-200 dark:border-gray-700">
        <div class="premium-card-header flex flex-col md:flex-row justify-between items-center p-4 border-b border-gray-200 dark:border-gray-700">
            <div class="premium-card-title flex items-center mb-4 md:mb-0">
                <div class="h-12 w-12 flex items-center justify-center rounded-md bg-gradient-to-br from-slate-500 to-slate-700 mr-3">
                    <i class="fas fa-folder text-white text-md"></i>
                </div>
                <div class="header-text">
                    <h4 class="text-lg font-semibold text-gray-800 dark:text-white">Service Management</h4>
                    <p class="text-sm text-gray-500 dark:text-gray-400">Manage all Service in the system</p>
                </div>
            </div>
            <div class="mt-4 md:mt-0 flex flex-col sm:flex-row space-y-3 sm:space-y-0 sm:space-x-3">
                <button id="openModal"
                    class="block text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800">
                    Create Service
                </button>
            </div>
        </div>

        <div class="premium-card-body">
            <div class="premium-table-container">
                <table class="premium-table" id="staff-role-table">
                    <thead>
                        <tr>
                            <th width="10">SN</th>
                            <th width="200">Name</th>
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

        <div class="premium-card-footer flex flex-col sm:flex-row justify-between items-center p-4 border-t border-gray-200 dark:border-gray-700 gap-4">
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
            <div class="pagination-container" id="custom-pagination">
                </div>
        </div>
    </div>
@endsection

@push('modals')
    <div id="custom-modal" class="z-50 bg-black/50 fixed inset-0 flex items-center justify-center" style="display: none;">
        <div class="bg-white dark:bg-gray-700 rounded-lg shadow-lg w-full max-w-4xl p-4 md:p-5 relative">
            <div class="flex items-center justify-between border-b border-gray-200 dark:border-gray-600 pb-3">
                <h3 id="model-title" class="text-lg font-semibold text-gray-900 dark:text-white">Create Service</h3>
                <span class="closeModal iconify cursor-pointer" data-icon="ic:baseline-close" data-width="24"></span>
            </div>

            <form action="{{ route('services.store', $slug) }}" class="mt-4 mb-0" id="staff-role-form">
                @csrf
                <div class="grid gap-4 mb-4 grid-cols-2">
                    <div class="col-span-2">
                        <label for="title" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Title</label>
                        <input type="text" id="title" name="title" placeholder="Type Title"
                            class="bg-gray-50 border border-gray-300 text-gray-900 rounded-lg text-sm focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-600 dark:border-gray-500 dark:text-white" required>
                    </div>

                    <div class="col-span-2">
                        <label for="icon-search" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">
                            Search Icon (Type e.g. 'star', 'user', 'gear')
                        </label>
                        <div class="relative">
                            <div class="flex gap-2">
                                <div class="relative flex-1">
                                    <input type="text" id="icon-search" placeholder="Search icons..."
                                        class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-600 dark:border-gray-500 dark:text-white">
                                </div>
                                
                                <input type="hidden" id="icon" name="icon">

                                <div class="w-11 h-11 flex-shrink-0 flex items-center justify-center border border-gray-300 dark:border-gray-500 rounded-lg bg-gray-50 dark:bg-gray-600">
                                    <span id="icon-preview" class="iconify text-2xl text-blue-600" data-icon="lucide:search"></span>
                                </div>
                            </div>

                            <div id="icon-suggestions" class="hidden absolute z-50 w-full mt-1 max-h-48 overflow-y-auto bg-white dark:bg-gray-800 border border-gray-300 dark:border-gray-600 rounded-lg shadow-xl grid grid-cols-8 gap-1 p-2">
                                </div>
                        </div>
                    </div>

                    <div class="col-span-2">
                        <label for="description" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Description</label>
                        <textarea id="description" rows="4" placeholder="Write service description here" name="description"
                            class="block w-full p-2.5 text-sm text-gray-900 bg-gray-50 rounded-lg border border-gray-300 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-600 dark:border-gray-500 dark:text-white"></textarea>
                    </div>
                </div>

                <div class="flex justify-between gap-3 py-3">
                    <button type="button" class="closeModal text-white bg-red-700 hover:bg-red-800 rounded-lg text-sm px-5 py-2.5 inline-flex items-center">
                        Close
                    </button>
                    <button type="submit" class="text-white bg-blue-700 hover:bg-blue-800 rounded-lg text-sm px-5 py-2.5 inline-flex items-center">
                        Save Service
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
            $('#title').val('').focus();
            $('#model-title').text('Create Service'); // Fixed: use .text() instead of .val()
            $('#staff-role-form').removeData('id');
            $('#description').val('');
            $('#icon').val('');
            $('#icon-search').val('');
            $('#icon-preview').attr('data-icon', 'lucide:search');
            $('#icon-suggestions').addClass('hidden').empty();
        }

        function getId() {
            return $('#staff-role-form').data('id');
        }

        $(document).ready(function() {
            // --- Iconify Search Engine ---
            let searchTimer;
            $('#icon-search').on('input', function() {
                const query = $(this).val();
                clearTimeout(searchTimer);
                if (query.length < 2) { $('#icon-suggestions').addClass('hidden'); return; }

                searchTimer = setTimeout(() => {
                    fetch(`https://api.iconify.design/search?query=${query}&limit=32`)
                        .then(res => res.json())
                        .then(data => {
                            let html = '';
                            if (data.icons.length > 0) {
                                data.icons.forEach(icon => {
                                    html += `<div class="icon-option p-2 hover:bg-blue-50 dark:hover:bg-gray-600 rounded cursor-pointer flex justify-center transition-colors" data-id="${icon}">
                                                <span class="iconify text-xl" data-icon="${icon}"></span>
                                             </div>`;
                                });
                                $('#icon-suggestions').html(html).removeClass('hidden');
                                Iconify.scan();
                            }
                        });
                }, 300);
            });

            $(document).on('click', '.icon-option', function() {
                const iconId = $(this).data('id');
                $('#icon').val(iconId);
                $('#icon-search').val(iconId);
                $('#icon-preview').attr('data-icon', iconId);
                $('#icon-suggestions').addClass('hidden');
                Iconify.scan();
            });

            // Close suggestions when clicking away
            $(document).on('click', function(e) {
                if (!$(e.target).closest('#icon-suggestions, #icon-search').length) {
                    $('#icon-suggestions').addClass('hidden');
                }
            });

            var table_listing_table = $('#staff-role-table').DataTable({
                "dom": "<'row'<'col-sm-12'tr>>",
                "aLengthMenu": [[10, 20, 50, 100], [10, 20, 50, "100"]],
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
                    "data": function(d) { d.ajax = 1; },
                    "error": function(xhr) {
                        nepalToast.error('Error', xhr.responseJSON?.message || 'Failed to load batch data.');
                    }
                },
                "columns": [
                    {
                        "data": function(row) { return `<span class="font-semibold">${row.sn}</span>`; },
                        "name": "sn",
                        "orderable": false
                    },
                    {
                        "data": function(row) {
                            let iconName = row.icon ? row.icon : 'lucide:package';
                            return `
                                <div class="flex items-center">
                                    <div class="h-8 w-8 flex items-center justify-center rounded bg-gray-100 dark:bg-gray-700 mr-3">
                                        <span class="iconify text-blue-600" data-icon="${iconName}"></span>
                                    </div>
                                    <span class="font-semibold">${row.title}</span>
                                    ${row.is_active ? '<span class="inline-block w-3 h-3 rounded-full bg-green-500 ml-2"></span>' : '<span class="inline-block w-3 h-3 rounded-full bg-red-500 ml-2"></span>'}
                                </div>`;
                        },
                        "name": "title",
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
                            var statusBtn = `
                                <button type="button" class="toggle-status-btn rounded-md border border-transparent p-2 text-center text-sm transition-all ${row.is_active ? 'text-green-600 hover:bg-green-50' : 'text-red-600 hover:bg-red-50'}" data-id="${row.id}">
                                    <i class="fas ${row.is_active ? 'fa-toggle-on' : 'fa-toggle-off'} w-5 h-5"></i>
                                </button>`;

                            return `
                                <div class="flex space-x-2">
                                    <button type="button" class="edit-btn rounded-md border border-transparent p-2 text-slate-600 hover:bg-slate-100 transition-all"
                                        data-id="${row.id}" data-description="${row.description}"
                                        data-title="${row.title}" data-icon="${row.icon}">
                                        <i class="fas fa-edit w-4 h-4"></i>
                                    </button>
                                    <button type="button" class="delete-btn rounded-md border border-transparent p-2 text-slate-600 hover:bg-slate-100 transition-all" data-id="${row.id}">
                                        <i class="fas fa-trash w-4 h-4"></i>
                                    </button>
                                    ${statusBtn}
                                </div>`;
                        },
                        "name": "actions",
                        "orderable": false
                    }
                ],
                "drawCallback": function(settings) {
                    // Trigger Iconify to render SVG icons in the table
                    Iconify.scan();

                    var api = this.api();
                    var pageInfo = api.page.info();
                    $('#showing-entries').text(`Showing ${pageInfo.start + 1} to ${pageInfo.end} of ${pageInfo.recordsDisplay} entries`);

                    var paginationHtml = '';
                    paginationHtml += `<div class="page-item ${pageInfo.page === 0 ? 'opacity-50 cursor-not-allowed' : ''}">
                        <button class="page-link px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-l-md" data-page="${pageInfo.page - 1}">
                            <i class="fas fa-chevron-left w-4 h-4"></i>
                        </button></div>`;

                    for (var i = Math.max(0, pageInfo.page - 2); i <= Math.min(pageInfo.pages - 1, pageInfo.page + 2); i++) {
                        paginationHtml += `<div class="page-item ${pageInfo.page === i ? 'bg-blue-600 text-white' : 'bg-white dark:bg-gray-800 text-gray-700 dark:text-gray-300'}">
                            <button class="page-link px-3 py-2 border border-gray-300 dark:border-gray-600" data-page="${i}">${i + 1}</button></div>`;
                    }

                    paginationHtml += `<div class="page-item ${pageInfo.page === pageInfo.pages - 1 ? 'opacity-50 cursor-not-allowed' : ''}">
                        <button class="page-link px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-r-md" data-page="${pageInfo.page + 1}">
                            <i class="fas fa-chevron-right w-4 h-4"></i>
                        </button></div>`;

                    $('#custom-pagination').html(paginationHtml);
                }
            });

            // Pagination Click
            $('#custom-pagination').on('click', '.page-link', function() {
                var page = $(this).data('page');
                if (page >= 0) table_listing_table.page(page).draw('page');
            });

            // Rows per page
            $('#per-page-select').on('change', function() {
                table_listing_table.page.len(parseInt($(this).val())).draw();
            });

            $('#openModal').click(function() { modelOpen(); });

            $(document).on('click', '.closeModal', function() { $('#custom-modal').hide(); });

            // Form Submit
            $('#staff-role-form').on('submit', function(e) {
                e.preventDefault();
                let formData = new FormData(this);
                const id = getId();
                if (id) formData.append('id', id);

                $.ajax({
                    type: "POST",
                    url: "{{ route('services.store', $slug) }}",
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
                    }
                });
            });

            // Edit Action
            $(document).on('click', '.edit-btn', function() {
                const id = $(this).data('id');
                const title = $(this).data('title');
                const description = $(this).data('description');
                const icon = $(this).data('icon') || '';
                
                modelOpen(); // Reset modal first
                $('#model-title').text('Edit Service');
                $('#staff-role-form').data('id', id);
                $('#title').val(title).focus();
                $('#description').val(description);
                $('#icon').val(icon);
                $('#icon-search').val(icon);
                $('#icon-preview').attr('data-icon', icon || 'lucide:search');
                Iconify.scan();
            });

            // Toggle Status
            $(document).on('click', '.toggle-status-btn', function() {
                $.ajax({
                    type: "GET",
                    url: "?status",
                    data: { id: $(this).data('id') },
                    success: function(response) {
                        table_listing_table.ajax.reload(null, false);
                        nepalToast.success('Success', response.message);
                    }
                });
            });

            // Delete Action
            $(document).on('click', '.delete-btn', function() {
                var id = $(this).data('id');
                nepalConfirm.show({
                    title: 'Delete Service',
                    message: `Are you sure?`,
                    type: 'danger'
                }).then(() => {
                    $.ajax({
                        url: "{{ route('services.destroy', ':id') }}".replace(':id', id),
                        type: "DELETE",
                        data: { _token: "{{ csrf_token() }}" },
                        success: function(response) {
                            table_listing_table.ajax.reload();
                            nepalToast.success('Success', response.message);
                        }
                    });
                });
            });
        });
    </script>
@endpush