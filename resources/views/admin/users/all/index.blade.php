@extends('layouts.app')

@section('content')
    @include('components.breadcrumb', [
        'breadcrumbs' => [['title' => 'All Users', 'url' => null]],
    ])

    <div
        class="flex flex-col md:flex-row justify-between items-center mb-8 bg-white dark:bg-gray-800 p-6 rounded-md shadow-md">
        <div class="flex items-center">
            <span class="iconify text-nepal-blue dark:text-blue-400" data-icon="tabler:users" data-width="28"></span>
            <h1 class="text-2xl font-bold text-gray-800 dark:text-white ml-3 relative">
                <span class="ml-3">All Users</span>
            </h1>
        </div>
        <div class="flex flex-col sm:flex-row w-full md:w-auto space-y-3 sm:space-y-0 sm:space-x-3 mt-4 md:mt-0">
            <a href="{{ route('management.users.index') }}"
                class="bg-nepal-blue hover:bg-nepal-blue/90 text-white px-4 py-2.5 rounded-lg shadow-sm flex items-center justify-center transition-all duration-200 transform hover:scale-105">
                <span class="iconify mr-2" data-icon="tabler:arrow-left" data-width="20"></span>
                Back to Users
            </a>
        </div>
    </div>

    <div class="bg-white rounded-lg shadow mb-6">
        <div id="pastContent" class="rounded-md">
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200 rounded-md" id="all_table">
                    <thead class="bg-white">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Sn
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Details
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Ministry
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Roles
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                View
                            </th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection


@push('scripts')
    <script>
        $(document).ready(function() {
            const uploadBaseUrl = "{{ asset('uploads/users') }}/";

            var all_table = $('#all_table').DataTable({
                "dom": "<'row'<''tr>>" +
                    "<'sm:flex pagination justify-between items-center px-4 py-3 text-xs font-semibold tracking-wide text-gray-500 uppercase border-t dark:border-gray-700 bg-gray-50  dark:text-gray-400 dark:bg-gray-800'<''i><''p><''l>>",
                "aLengthMenu": [
                    [10, 20, 50, 100],
                    [10, 20, 50, "100"]
                ],
                "iDisplayLength": 10,
                "language": {
                    zeroRecords: "<div class='px-4 py-3 w-full flex justify-center space-x-1' role='alert'><i class='ti-info-alt'></i>No <b> Users &nbsp; </b> found.</div>",
                    search: "",
                    searchPlaceholder: "{{ __('Search') }}",
                    "processing": "<div class='square-box-loader'><div class='square-box-loader-container'><div class='square-box-loader-corner-top'></div><div class='square-box-loader-corner-bottom'></div></div><div class='square-box-loader-square'></div></div>",
                    paginate: {
                        'previous': '{{ __('Previous') }}',
                        'next': '{{ __('Next') }}'
                    }
                },

                "bSort": false,
                "processing": true,
                "serverSide": true,
                "ordering": false,
                "searching": false,
                "stateSave": true,
                "ajax": {
                    "url": "?dataTable",
                    "data": function(d) {
                        d.ajax = 1;
                    }
                },
                "columns": [{
                        "data": function(row, type, set, meta) {
                            return row.sn;
                        },
                        "name": "sn",
                        "orderable": true,
                    },
                    {
                        "data": function(row, type, set, meta) {
                            var image = null;
                            if (row.profile) {
                                var url = uploadBaseUrl + row.profile;
                                image = `
                                    <div class="preview_image">
                                        <a href="${url}" target="_blank">
                                        <img src="${url}" alt="${row.name}" class="max-w-14 max-h-14 rounded-md">
                                        </a>
                                        </div>
                                    `;
                            } else {
                                image = `
                                    <div class="min-w-14 min-h-14 rounded-md flex items-center justify-center text-white font-bold bg-gradient-to-r from-nepal-blue to-blue-600">
                                        ${row.name.substring(0, 2).toUpperCase()}
                                    </div>`;
                            }
                            var content = `
                                <div class="flex justify-start items-center gap-3">
                                    ${image}
                                    <div class="flex flex-col">
                                        <span>Name: ${row.name}</span>              
                                        <span>Email: ${row.email}</span>              
                                    </div>
                                </div>
                                `;
                            return content;
                        },
                        "name": "name",
                        "orderable": true,
                    },
                    {
                        "data": function(row, type, set, meta) {
                            return row.ministry;
                        },
                        "name": "ministry",
                        "orderable": true,
                    },
                    {
                        "data": function(row, type, set, meta) {
                            return row.roles;
                        },
                        "name": "roles",
                        "orderable": true,
                    },
                    {
                        "data": function(row, type, set, meta) {
                            var viewUrl =
                                "{{ route('management.users.show', '__id__') }}".replace(
                                    '__id__', row.id);
                            var view = `
                            <a href="${viewUrl}"
                                class="text-nepal-blue hover:text-nepal-blue/80 flex items-center text-sm font-medium">
                                <span class="iconify mr-1" data-icon="tabler:eye" data-width="16"></span>
                                View
                            </a>`
                            return view;
                        },
                        "name": "roles",
                        "orderable": true,
                    }
                ]
            });
        });
    </script>
@endpush
