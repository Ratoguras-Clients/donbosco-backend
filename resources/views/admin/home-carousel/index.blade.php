@extends('layouts.app')

@push('styles')
    <script src="https://cdn.jsdelivr.net/npm/quill@2.0.3/dist/quill.js"></script>
    <link href="https://cdn.jsdelivr.net/npm/quill@2.0.3/dist/quill.snow.css" rel="stylesheet">
    <style>
        .ql-container {
            min-height: 120px;
        }

        .ql-editor {
            min-height: 120px;
        }
    </style>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/lightbox2/2.11.5/css/lightbox.css" rel="stylesheet">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/lightbox2/2.11.5/js/lightbox.min.js"></script>
@endpush

@section('content')
    @include('components.breadcrumb', [
        'breadcrumbs' => [['title' => 'News', 'url' => null]],
    ])

    @if (session('success'))
        <div id="success-message" data-message="{{ session('success') }}" class="hidden"></div>
    @endif

    <div class="premium-card bg-white dark:bg-gray-800 rounded-xl shadow-md border border-gray-200 dark:border-gray-700">
        <div
            class="premium-card-header flex flex-col md:flex-row justify-between items-center p-4 border-b border-gray-200 dark:border-gray-700">
            <div class="premium-card-title flex items-center mb-4 md:mb-0">
                <div
                    class="h-12 w-12 flex items-center justify-center rounded-md bg-gradient-to-br from-slate-500 to-slate-700 mr-3">
                    <i class="fas fa-folder text-white text-md"></i>
                </div>
                <div class="header-text">
                    <h4 class="text-lg font-semibold text-gray-800 dark:text-white">Home Carousel Management</h4>
                    <p class="text-sm text-gray-500 dark:text-gray-400">Manage all Home Carousel in the system</p>
                </div>
            </div>
            <div class="mt-4 md:mt-0 flex flex-col sm:flex-row space-y-3 sm:space-y-0 sm:space-x-3">
                <button id="create-btn"
                    class="block text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800">
                    Upload Image
                </button>
            </div>
        </div>

        <div class="premium-card-body">
            <div class="premium-table-container">
                <table class="premium-table" id="home-carousel-table">
                    <thead>
                        <tr>
                            <th width="30"></th>
                            <th width="10">SN</th>
                            <th width="200">Title</th>
                            <th width="200">Sub Title</th>
                            <th width="150">Featured Image</th>
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
    <div id="add-modal" class="z-10 bg-black/50 fixed inset-0 flex items-center justify-center overflow-auto p-4"
        style="display: none;">
        <div
            class="bg-white dark:bg-gray-700 rounded-lg shadow-lg w-full max-w-sm sm:max-w-md md:max-w-lg lg:max-w-2xl xl:max-w-3xl p-4 md:p-5 relative">
            <!-- Modal Header -->
            <div class="flex items-center justify-between border-b border-gray-200 dark:border-gray-600 pb-3">
                <h3 id="model-title" class="text-lg font-semibold text-gray-900 dark:text-white">Create home carousel</h3>
                <span id="" class="closeModal iconify cursor-pointer" data-icon="ic:baseline-close"
                    data-width="24"></span>
            </div>

            <!-- Modal Body -->
            <form class="mt-4 mb-0" id="staff-role-form" method="POST">
                @csrf
                <div class="grid gap-4 mb-4 grid-cols-1 md:grid-cols-2">
                    <div class="col-span-2">
                        <label for="title" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">
                            Title
                        </label>
                        <input type="text" id="title" name="title" placeholder="Enter Title"
                            class="bg-gray-50 border border-gray-300 text-gray-900 rounded-lg text-sm focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-600 dark:border-gray-500 dark:text-white"
                            required>
                    </div>

                    <div class="col-span-2">
                        <label for="sub_title" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">
                            Sub Title
                        </label>

                        <div class="border border-gray-300 dark:border-gray-500 rounded-lg bg-gray-50 dark:bg-gray-600">
                            <div id="sub_title_editor" class="min-h-[120px]"></div>
                        </div>

                        <input type="hidden" name="sub_title" id="sub_title">
                    </div>
                </div>

                <!-- Cover Image -->
                <div class="mb-6">
                    <h3 class="text-lg font-medium text-gray-800 dark:text-white mb-4 flex items-center">
                        <span class="iconify mr-2" data-icon="tabler:photo" data-width="20"></span>
                        Featured Image
                    </h3>
                    <div
                        class="flex flex-col items-center justify-center p-4 border border-dashed border-gray-300 rounded-md">
                        <input type="hidden" name="media_id" id="media_id" value="{{ old('media_id') }}">

                        <!-- Old preview (for validation errors) -->
                        <div id="featured-image-preview" class="mb-4"
                            style="{{ old('media_id') ? '' : 'display:none;' }}">
                            @if (old('media_id'))
                                @php
                                    $selectedMedia = $medias->firstWhere('id', old('media_id'));
                                @endphp
                                @if ($selectedMedia)
                                    <img src="{{ $selectedMedia->url }}" alt="{{ $selectedMedia->alt_text }}"
                                        class="max-w-full h-48 object-contain rounded-md shadow-sm">
                                @endif
                            @endif
                        </div>

                        <!-- Dynamic preview (new selection) -->
                        <div id="image-preview" class="mb-4 w-full">
                            <!-- Will be filled by JS -->
                        </div>

                        <button type="button" id="select-featured-image"
                            class="btn-secondary select-image-btn px-4 py-2 bg-gray-100 dark:bg-gray-700 text-gray-700 dark:text-gray-200 rounded-md border border-gray-300 dark:border-gray-600 flex items-center justify-center hover:bg-gray-200 dark:hover:bg-gray-600 transition">
                            <span class="iconify mr-2" data-icon="tabler:upload" data-width="20"></span>
                            {{ old('media_id') ? 'Change Featured Image' : 'Select Featured Image' }}
                        </button>

                        <button type="button" id="remove-featured-image"
                            class="text-red-600 hover:text-red-800 text-sm mt-2 {{ old('media_id') ? '' : 'hidden' }}">
                            Remove Featured Image
                        </button>
                    </div>

                    @error('media_id')
                        <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                    @enderror
                </div>

                <div class="flex justify-between gap-3 py-3">
                    <button type="button"
                        class="closeModal text-white bg-red-700 hover:bg-red-800 rounded-lg text-sm px-5 py-2.5 inline-flex items-center">
                        Close
                    </button>

                    <button type="submit" id="submit-button"
                        class="text-white bg-blue-700 hover:bg-blue-800 rounded-lg text-sm px-5 py-2.5 inline-flex items-center">
                        Save Service
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Media Picker -->
    <x-media-piker modalId="cover-image-media-picker" title="Select or Upload Cover Image" :allowMultiple="false"
        :allowUpload="true" :allowView="true" triggerSelector="#select-cover-image, .select-image-btn"
        onSelect="handleCoverImageSelection"
        acceptedTypes="image/jpeg,image/jpg,image/png,image/gif,image/webp,image/svg+xml" maxFileSize="10MB" />
@endpush

@push('scripts')
    <script>
        function clearCoverImage() {
            $('#cover_image').val('');
            $('#image-preview').empty();
            $('#old-cover-preview').hide();
            $('#remove-cover-image').addClass('hidden');
            $('#select-cover-image').text('Select Cover Image');
        }

        function modalOpen() {
            $('#add-modal').show();
            $('#title').val('').focus();
            $('#sub_title').val('');
            $('#media_id').val('');
            $('#model-title').val('Create Home Carousel');
            $('#submit-button').text('Create');
            clearCoverImage();
        }

        $(document).ready(function() {
            const quill = new Quill('#sub_title_editor', {
                theme: 'snow',
                placeholder: 'Type sub title...',
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

            quill.on('text-change', function() {
                document.getElementById('sub_title').value = quill.root.innerHTML;
            });

            const routes = {
                destroy: id => "{{ route('home-carousel.destroy', ['id' => '__id__']) }}".replace('__id__', id),
            };

            var table_listing_table = $('#home-carousel-table').DataTable({
                dom: "<'row'<'col-sm-12'tr>>",
                aLengthMenu: [
                    [10, 20, 50, 100],
                    [10, 20, 50, "100"]
                ],
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
                    data: function(d) {
                        d.ajax = 1;
                    },
                    error: function(xhr) {
                        nepalToast.error('Error', xhr.responseJSON?.message ||
                            'Failed to load news data.');
                    }
                },
                createdRow: function(row, data, dataIndex) {
                    $(row).attr("data-id", data.id);
                },
                columns: [{
                        "data": function(row) {
                            return `<span class="drag-handle cursor-grab active:cursor-grabbing text-gray-400 hover:text-gray-600 px-2" title="Drag to reorder">
                    <i class="fas fa-grip-vertical"></i>
                </span>`;
                        },
                        "name": "drag",
                        "orderable": false
                    }, {
                        "data": function(row) {
                            return row.sn;
                        },
                        "name": "sn",
                        "orderable": true
                    },
                    {
                        "data": function(row) {
                            return row.title;
                        },
                        "name": "title",
                        "orderable": false
                    },
                    {
                        "data": function(row) {
                            var plainText = $('<div>').html(row.subtitle).text();
                            var truncated = plainText.length > 30 ? plainText.substring(0, 30) +
                                '...' : plainText;
                            return row.subtitle ? truncated : 'N/A';
                        },
                        "name": "subtitle",
                        "orderable": false
                    },
                    {
                        "data": function(row) {
                            return `
                                <a href="${row.media_path}" data-lightbox="example-set" >
                                    <img 
                                        src="${row.media_path}" 
                                        class="h-12 w-12 object-cover rounded cursor-pointer"
                                        alt="Image"
                                    />
                                </a>
                            `;
                        },
                        "name": "sub_title",
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
                            return `
                            <div class="flex space-x-2">
                                <button data-id="${row.id}" data-title="${row.title}" data-sub_title="${row.subtitle}" data-media_id="${row.media_id}" data-media_path="${row.media_path}"
                                    type="button"
                                    class="edit-btn rounded-md border border-transparent p-2 text-center text-sm transition-all text-slate-600 hover:bg-slate-100">
                                    <i class="fas fa-edit w-4 h-4"></i>
                                </button>
                                <button type="button" class="delete-btn rounded-md border border-transparent p-2 text-center text-sm transition-all text-red-600 hover:bg-red-100"
                                    data-id="${row.id}">
                                    <i class="fas fa-trash w-4 h-4"></i>
                                </button>
                            </div>`;
                        },
                        "name": "data",
                        "orderable": false
                    }
                ],
                drawCallback: function(settings) {
                    const api = this.api();
                    const pageInfo = api.page.info();

                    $('#showing-entries').text(
                        `Showing ${pageInfo.start + 1} to ${pageInfo.end} of ${pageInfo.recordsDisplay} entries`
                    );

                    let paginationHtml = '';
                    paginationHtml += `<div class="page-item ${pageInfo.page === 0 ? 'opacity-50 cursor-not-allowed' : ''}">
                            <button class="page-link px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-l-md ${pageInfo.page === 0 ? 'pointer-events-none' : ''}" data-page="${pageInfo.page - 1}">
                                <i class="fas fa-chevron-left w-4 h-4"></i>
                            </button>
                        </div>`;

                    const startPage = Math.max(0, pageInfo.page - 2);
                    const endPage = Math.min(pageInfo.pages - 1, pageInfo.page + 2);

                    for (let i = startPage; i <= endPage; i++) {
                        paginationHtml += `<div class="page-item ${pageInfo.page === i ? 'bg-blue-600 text-white' : 'bg-white dark:bg-gray-800 text-gray-700 dark:text-gray-300'}">
                            <button class="page-link px-3 py-2 border border-gray-300 dark:border-gray-600" data-page="${i}">${i + 1}</button>
                        </div>`;
                    }

                    paginationHtml += `<div class="page-item ${pageInfo.page === pageInfo.pages - 1 ? 'opacity-50 cursor-not-allowed' : ''}">
                            <button class="page-link px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-r-md ${pageInfo.page === pageInfo.pages - 1 ? 'pointer-events-none' : ''}" data-page="${pageInfo.page + 1}">
                                <i class="fas fa-chevron-right w-4 h-4"></i>
                            </button>
                        </div>`;

                    $('#custom-pagination').html(paginationHtml);
                }
            });

            $("#home-carousel-table tbody").sortable({
                helper: "clone",
                axis: "y",
                update: function(event, ui) {
                    var rowOrder = $(this).sortable("toArray", {
                        attribute: "data-id"
                    });
                    console.log(rowOrder);
                    $.ajax({
                        url: "?sortable",
                        method: "GET",
                        data: {
                            rowOrder: rowOrder,
                            _token: "{{ csrf_token() }}"
                        },
                        dataType: "json",
                        success: function(response) {
                            table_listing_table.draw();
                        },
                        error: function(xhr, status, error) {
                            table_listing_table.draw();
                        }
                    });
                }
            }).disableSelection();

            $('#per-page-select').on('change', function() {
                table_listing_table.page.len(parseInt($(this).val())).draw();
            });

            $('#per-page-select').val(table_listing_table.page.len());

            $('#custom-pagination').on('click', '.page-link:not(.pointer-events-none)', function() {
                table_listing_table.page($(this).data('page')).draw('page');
            });

            $(document).on('click', '.delete-btn', function() {
                const id = $(this).data('id');

                nepalConfirm.show({
                    title: 'Delete News',
                    message: 'Are you sure you want to delete this news article? This action cannot be undone.',
                    type: 'danger',
                    confirmText: 'Delete',
                    confirmIcon: '<i class="fas fa-trash w-4.5 h-4.5"></i>'
                }).then(() => {
                    $.ajax({
                        url: routes.destroy(id),
                        type: 'POST',
                        data: {
                            _token: "{{ csrf_token() }}",
                            _method: 'DELETE'
                        },
                        success: function(response) {
                            if (response.success) {
                                nepalToast.success('Success', response.message ||
                                    'News deleted successfully.');
                                table_listing_table.ajax.reload(null, false);
                            } else {
                                nepalToast.error('Error', response.message);
                            }
                        },
                        error: function(xhr) {
                            nepalToast.error('Error', xhr.responseJSON?.message ||
                                'Failed to delete news.');
                        }
                    });
                }).catch(() => {
                    nepalToast.info('Canceled', 'News deletion was canceled.');
                });
            });

            const successMessage = $('#success-message').data('message');
            if (successMessage) {
                nepalToast.success('Success', successMessage);
            }

            $('#create-btn').click(function() {
                modalOpen();
            });

            $(document).on('click', '.closeModal', function() {
                $('#add-modal').hide();
            });

            $(document).on('click', '.edit-btn', function() {
                const id = $(this).data('id');
                const title = $(this).data('title');
                const subTitle = $(this).data('sub_title');
                const media_path = $(this).data('media_path');
                const mediaId = $(this).data('media_id');

                $('#add-modal').show();
                $('#model-title').text('Edit Home Carousel');
                $('#submit-button').text('Update');
                $('#staff-role-form').data('id', id);
                $('#title').val(title);
                $('#sub_title').val(subTitle);
                $('#media_id').val(mediaId);
                updateCoverImagePreview(media_path);
                quill.root.innerHTML = subTitle;
            });


            window.handleCoverImageSelection = function(mediaId, picker) {
                if (!mediaId) return;

                let mediaUrl = null;

                // Try to get from current modal
                const modalItem = $(`#${picker.modalId}-grid .media-item[data-media-id="${mediaId}"]`);
                if (modalItem.length) {
                    mediaUrl = modalItem.data('media-url');
                }

                // Fallback
                if (!mediaUrl) {
                    const anyItem = $(`.media-item[data-media-id="${mediaId}"]`).first();
                    if (anyItem.length) mediaUrl = anyItem.data('media-url');
                }

                if (!mediaUrl) {
                    console.error('Cover Image: Media URL not found for ID', mediaId);
                    return;
                }

                $('#media_id').val(mediaId);

                updateCoverImagePreview(mediaUrl);
            };

            function updateCoverImagePreview(url) {
                const fileName = url.split('/').pop();
                const ext = fileName.split('.').pop().toLowerCase();
                const isImage = ['jpg', 'jpeg', 'png', 'gif', 'webp', 'svg'].includes(ext);

                const previewHtml = `
                    <div class="relative bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-600 rounded-lg shadow-sm overflow-hidden">
                        <!-- Header -->
                        <div class="flex items-center justify-between p-3 bg-gray-50 dark:bg-gray-700 border-b border-gray-200 dark:border-gray-600">
                            <div class="flex items-center gap-2">
                                <span class="iconify ${isImage ? 'text-green-500' : 'text-blue-500'}"
                                    data-icon="${isImage ? 'tabler:photo' : 'tabler:file'}" data-width="18"></span>
                                <span class="text-sm font-medium text-gray-700 dark:text-gray-300 truncate max-w-[240px]">
                                    ${fileName}
                                </span>
                            </div>
                            <button type="button" id="remove-cover-inline"
                                    class="flex items-center gap-1 px-2 py-1 text-red-600 hover:text-red-800
                                        dark:text-red-400 dark:hover:text-red-300 text-xs
                                        bg-red-50 dark:bg-red-900/20 rounded transition-colors">
                                <span class="iconify" data-icon="tabler:trash" data-width="14"></span>
                                Remove
                            </button>
                        </div>

                        <!-- Preview Content -->
                        <div class="flex items-center justify-center p-4 bg-gray-50 dark:bg-gray-700/50 w-full">
                            ${isImage
                                ? `<img src="${url}" alt="Cover Preview" class="max-w-full h-32 sm:h-40 md:h-48 w-auto object-contain rounded-md shadow-sm">`
                                : `<span class="iconify text-gray-400" data-icon="tabler:file-text" data-width="64"></span>`
                            }
                        </div>
                    </div>
                `;

                $('#image-preview').html(previewHtml);
                $('#old-cover-preview').hide();
                $('#remove-cover-image').removeClass('hidden');
                $('#select-cover-image').text('Change Cover Image');
            }



            $('#remove-cover-image').on('click', clearCoverImage);
            $(document).on('click', '#remove-cover-inline', clearCoverImage);

            // Initialize Media Picker
            try {
                window.coverImagePicker = new MediaPickerClass('cover-image-media-picker', {
                    onSelect: window.handleCoverImageSelection
                });
            } catch (e) {
                console.error('Cover Image Picker initialization failed', e);
            }

            // Open picker when clicking the button
            $('#select-cover-image').on('click', function() {
                if (window.coverImagePicker) {
                    window.coverImagePicker.open();
                } else {
                    console.error('Cover image picker not initialized');
                }
            });

            function getId() {
                return $('#staff-role-form').data('id');
            }

            $('#staff-role-form').on('submit', function(e) {
                e.preventDefault();
                const formData = new FormData(this);
                const id = getId();
                if (id) {
                    formData.append('id', id);
                }
                const url = "{{ route('home-carousel.store', $organization->slug) }}";
                $.ajax({
                    url: url,
                    type: 'POST',
                    data: formData,
                    processData: false,
                    contentType: false,
                    success: function(response) {
                        if (response.success) {
                            nepalToast.success('Success', response.message ||
                                'Staff role created successfully.');
                            $('#staff-role-form')[0].reset();
                            $('#add-modal').hide();
                            table_listing_table.ajax.reload();
                        } else {
                            nepalToast.error('Error', response.message);
                        }
                    },
                    error: function(xhr) {
                        nepalToast.error('Error', xhr.responseJSON?.message ||
                            'Failed to create staff role.');
                    }
                });
            });
        });
    </script>
@endpush
