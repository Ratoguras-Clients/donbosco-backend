@extends('layouts.app')

@section('content')
    @include('components.breadcrumb', [
        'breadcrumbs' => [['title' => 'Admission & Fee Settings', 'url' => null]],
    ])

    @if (session('success'))
        <div id="success-message" data-message="{{ session('success') }}" class="hidden"></div>
    @endif

    {{-- Tab Navigation --}}
    <div class="mb-4 border-b border-gray-200 dark:border-gray-700">
        <ul class="flex flex-wrap -mb-px text-sm font-medium text-center" id="type-tabs">
            @php
                $tabs = [
                    'admission_class' => ['label' => 'Admission Classes', 'icon' => 'fa-graduation-cap'],
                    'annual_fee' => ['label' => 'Annual Fee', 'icon' => 'fa-calendar'],
                    'monthly_fee_class' => ['label' => 'Monthly Fee (Class)', 'icon' => 'fa-money-bill'],
                    'monthly_fee_other' => ['label' => 'Monthly Fee (Other)', 'icon' => 'fa-receipt'],
                    'proposed_monthly' => ['label' => 'Proposed Monthly', 'icon' => 'fa-file-invoice'],
                    'proposed_annual' => ['label' => 'Proposed Annual', 'icon' => 'fa-file-invoice-dollar'],
                ];
            @endphp
            @foreach ($tabs as $key => $tab)
                <li class="mr-2">
                    <button
                        class="tab-btn inline-flex items-center gap-2 p-4 border-b-2 rounded-t-lg {{ $type === $key ? 'text-blue-600 border-blue-600 dark:text-blue-500 dark:border-blue-500' : 'border-transparent hover:text-gray-600 hover:border-gray-300 dark:hover:text-gray-300' }}"
                        data-type="{{ $key }}">
                        <i class="fas {{ $tab['icon'] }}"></i>
                        {{ $tab['label'] }}
                    </button>
                </li>
            @endforeach
        </ul>
    </div>

    {{-- DataTable --}}
    <div class="premium-card bg-white dark:bg-gray-800 rounded-xl shadow-md border border-gray-200 dark:border-gray-700">
        <div
            class="premium-card-header flex flex-col md:flex-row justify-between items-center p-4 border-b border-gray-200 dark:border-gray-700">
            <div class="premium-card-title flex items-center mb-4 md:mb-0">
                <div
                    class="h-12 w-12 flex items-center justify-center rounded-md bg-gradient-to-br from-slate-500 to-slate-700 mr-3">
                    <i class="fas fa-cog text-white text-md"></i>
                </div>
                <div class="header-text">
                    <h4 id="table-title" class="text-lg font-semibold text-gray-800 dark:text-white">
                        {{ $tabs[$type]['label'] ?? 'Settings' }} Management
                    </h4>
                    <p class="text-sm text-gray-500 dark:text-gray-400">Manage admission & fee settings</p>
                </div>
            </div>
            <div class="mt-4 md:mt-0 flex flex-col sm:flex-row space-y-3 sm:space-y-0 sm:space-x-3">
                <button id="openModal"
                    class="block text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800">
                    Add New
                </button>
            </div>
        </div>

        <div class="premium-card-body">
            <div class="premium-table-container">
                <table class="premium-table" id="settings-table">
                    <thead>
                        <tr id="table-head-row">
                            {{-- Columns will be set dynamically by JS --}}
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

@push('modals')
    <div id="custom-modal" class="z-50 bg-black/50 fixed inset-0 flex items-center justify-center overflow-y-auto"
        style="display: none;">
        <div class="bg-white dark:bg-gray-700 rounded-lg shadow-lg w-full max-w-4xl p-4 md:p-5 relative my-8">
            <div class="flex items-center justify-between border-b border-gray-200 dark:border-gray-600 pb-3">
                <h3 id="modal-title" class="text-lg font-semibold text-gray-900 dark:text-white">Create New</h3>
                <span class="closeModal iconify cursor-pointer" data-icon="ic:baseline-close" data-width="24"></span>
            </div>

            <form class="mt-4 mb-0" id="settings-form">
                @csrf
                <input type="hidden" name="type" id="form-type" value="{{ $type }}">

                {{-- Admission Class Fields --}}
                <div id="fields-admission_class" class="type-fields hidden">
                    <div class="grid gap-4 mb-4 grid-cols-2">
                        <div class="col-span-1">
                            <label class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Class</label>
                            <input type="text" name="class" placeholder="e.g. Nursery, LKG, Class I"
                                class="form-input bg-gray-50 border border-gray-300 text-gray-900 rounded-lg text-sm focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-600 dark:border-gray-500 dark:text-white">
                        </div>
                        <div class="col-span-1">
                            <label class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">DOB Not Later
                                Than</label>
                            <input type="text" name="dob_not_later_than" placeholder="e.g. 2079-11-30"
                                class="form-input bg-gray-50 border border-gray-300 text-gray-900 rounded-lg text-sm focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-600 dark:border-gray-500 dark:text-white">
                        </div>
                        <div class="col-span-1">
                            <label class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Min Age</label>
                            <input type="number" name="min_age" placeholder="e.g. 3"
                                class="form-input bg-gray-50 border border-gray-300 text-gray-900 rounded-lg text-sm focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-600 dark:border-gray-500 dark:text-white">
                        </div>
                        <div class="col-span-1">
                            <label class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Required
                                Age</label>
                            <input type="text" name="required_age" placeholder="e.g. 3½"
                                class="form-input bg-gray-50 border border-gray-300 text-gray-900 rounded-lg text-sm focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-600 dark:border-gray-500 dark:text-white">
                        </div>
                        <div class="col-span-1">
                            <label class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Qualification
                                (EN)</label>
                            <textarea name="qualification_en" rows="2" placeholder="Qualification in English"
                                class="form-input block w-full p-2.5 text-sm text-gray-900 bg-gray-50 rounded-lg border border-gray-300 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-600 dark:border-gray-500 dark:text-white"></textarea>
                        </div>
                        <div class="col-span-1">
                            <label class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Qualification
                                (NP)</label>
                            <textarea name="qualification_np" rows="2" placeholder="योग्यता नेपालीमा"
                                class="form-input block w-full p-2.5 text-sm text-gray-900 bg-gray-50 rounded-lg border border-gray-300 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-600 dark:border-gray-500 dark:text-white"></textarea>
                        </div>
                        <div class="col-span-1">
                            <label class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Selection
                                (EN)</label>
                            <textarea name="selection_en" rows="2" placeholder="Selection process in English"
                                class="form-input block w-full p-2.5 text-sm text-gray-900 bg-gray-50 rounded-lg border border-gray-300 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-600 dark:border-gray-500 dark:text-white"></textarea>
                        </div>
                        <div class="col-span-1">
                            <label class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Selection
                                (NP)</label>
                            <textarea name="selection_np" rows="2" placeholder="छनोट प्रक्रिया नेपालीमा"
                                class="form-input block w-full p-2.5 text-sm text-gray-900 bg-gray-50 rounded-lg border border-gray-300 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-600 dark:border-gray-500 dark:text-white"></textarea>
                        </div>
                        <div class="col-span-1">
                            <label class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Documents
                                (EN) <small class="text-gray-400">one per line</small></label>
                            <textarea name="documents_en" rows="3" placeholder="Date of Birth Certificate (1 Photocopy)&#10;Passed Result..."
                                class="form-input block w-full p-2.5 text-sm text-gray-900 bg-gray-50 rounded-lg border border-gray-300 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-600 dark:border-gray-500 dark:text-white"></textarea>
                        </div>
                        <div class="col-span-1">
                            <label class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Documents
                                (NP) <small class="text-gray-400">one per line</small></label>
                            <textarea name="documents_np" rows="3" placeholder="जन्म दर्ता प्रमाणपत्र (१ प्रतिलिपि)&#10;..."
                                class="form-input block w-full p-2.5 text-sm text-gray-900 bg-gray-50 rounded-lg border border-gray-300 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-600 dark:border-gray-500 dark:text-white"></textarea>
                        </div>
                    </div>
                </div>

                {{-- Annual Fee Fields --}}
                <div id="fields-annual_fee" class="type-fields hidden">
                    <div class="grid gap-4 mb-4 grid-cols-2">
                        <div class="col-span-1">
                            <label class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Item</label>
                            <input type="text" name="item" placeholder="e.g. Maintenance / Development"
                                class="form-input bg-gray-50 border border-gray-300 text-gray-900 rounded-lg text-sm focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-600 dark:border-gray-500 dark:text-white">
                        </div>
                        <div class="col-span-1">
                            <label class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Amount
                                (NPR)</label>
                            <input type="number" step="0.01" name="amount_npr" placeholder="e.g. 955"
                                class="form-input bg-gray-50 border border-gray-300 text-gray-900 rounded-lg text-sm focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-600 dark:border-gray-500 dark:text-white">
                        </div>
                        <div class="col-span-2">
                            <label class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Frequency</label>
                            <input type="text" name="frequency" placeholder="e.g. Once a Year"
                                class="form-input bg-gray-50 border border-gray-300 text-gray-900 rounded-lg text-sm focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-600 dark:border-gray-500 dark:text-white">
                        </div>
                    </div>
                </div>

                {{-- Monthly Fee Class Fields --}}
                <div id="fields-monthly_fee_class" class="type-fields hidden">
                    <div class="grid gap-4 mb-4 grid-cols-2">
                        <div class="col-span-1">
                            <label class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Classes</label>
                            <input type="text" name="classes" placeholder="e.g. Nursery, LKG & UKG"
                                class="form-input bg-gray-50 border border-gray-300 text-gray-900 rounded-lg text-sm focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-600 dark:border-gray-500 dark:text-white">
                        </div>
                        <div class="col-span-1">
                            <label class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Amount
                                (NPR)</label>
                            <input type="number" step="0.01" name="amount_npr" placeholder="e.g. 1700"
                                class="form-input bg-gray-50 border border-gray-300 text-gray-900 rounded-lg text-sm focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-600 dark:border-gray-500 dark:text-white">
                        </div>
                    </div>
                </div>

                {{-- Monthly Fee Other Fields --}}
                <div id="fields-monthly_fee_other" class="type-fields hidden">
                    <div class="grid gap-4 mb-4 grid-cols-2">
                        <div class="col-span-1">
                            <label class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Item</label>
                            <input type="text" name="item"
                                placeholder="e.g. Computer Fee (From Class I onward)"
                                class="form-input bg-gray-50 border border-gray-300 text-gray-900 rounded-lg text-sm focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-600 dark:border-gray-500 dark:text-white">
                        </div>
                        <div class="col-span-1">
                            <label class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Amount
                                (NPR)</label>
                            <input type="number" step="0.01" name="amount_npr" placeholder="e.g. 150"
                                class="form-input bg-gray-50 border border-gray-300 text-gray-900 rounded-lg text-sm focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-600 dark:border-gray-500 dark:text-white">
                        </div>
                    </div>
                </div>

                {{-- Proposed Monthly Fields --}}
                <div id="fields-proposed_monthly" class="type-fields hidden">
                    <div class="grid gap-4 mb-4 grid-cols-2">
                        <div class="col-span-1">
                            <label class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Class
                                Type</label>
                            <input type="text" name="class_type" placeholder="e.g. Nursery"
                                class="form-input bg-gray-50 border border-gray-300 text-gray-900 rounded-lg text-sm focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-600 dark:border-gray-500 dark:text-white">
                        </div>
                        <div class="col-span-1">
                            <label class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Amount
                                (NPR)</label>
                            <input type="number" step="0.01" name="amount_npr" placeholder="e.g. 1700"
                                class="form-input bg-gray-50 border border-gray-300 text-gray-900 rounded-lg text-sm focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-600 dark:border-gray-500 dark:text-white">
                        </div>
                        <div class="col-span-2">
                            <label class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Frequency</label>
                            <input type="text" name="frequency" value="Monthly" placeholder="Monthly"
                                class="form-input bg-gray-50 border border-gray-300 text-gray-900 rounded-lg text-sm focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-600 dark:border-gray-500 dark:text-white">
                        </div>
                    </div>
                </div>

                {{-- Proposed Annual Fields --}}
                <div id="fields-proposed_annual" class="type-fields hidden">
                    <div class="grid gap-4 mb-4 grid-cols-2">
                        <div class="col-span-1">
                            <label class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Item</label>
                            <input type="text" name="item" placeholder="e.g. Examination Fee for all"
                                class="form-input bg-gray-50 border border-gray-300 text-gray-900 rounded-lg text-sm focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-600 dark:border-gray-500 dark:text-white">
                        </div>
                        <div class="col-span-1">
                            <label class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Amount
                                (NPR)</label>
                            <input type="number" step="0.01" name="amount_npr" placeholder="e.g. 450"
                                class="form-input bg-gray-50 border border-gray-300 text-gray-900 rounded-lg text-sm focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-600 dark:border-gray-500 dark:text-white">
                        </div>
                        <div class="col-span-2">
                            <label class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Frequency</label>
                            <input type="text" name="frequency" placeholder="e.g. Thrice a year"
                                class="form-input bg-gray-50 border border-gray-300 text-gray-900 rounded-lg text-sm focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-600 dark:border-gray-500 dark:text-white">
                        </div>
                    </div>
                </div>

                {{-- Common: Order Index --}}
                <div class="grid gap-4 mb-4 grid-cols-2">
                    <div class="col-span-2">
                        <label class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Sort Order</label>
                        <input type="number" name="order_index" value="0" placeholder="0"
                            class="bg-gray-50 border border-gray-300 text-gray-900 rounded-lg text-sm focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-600 dark:border-gray-500 dark:text-white">
                    </div>
                </div>

                <div class="flex justify-between gap-3">
                    <button type="button"
                        class="closeModal text-white bg-red-700 hover:bg-red-800 rounded-lg text-sm px-5 py-2.5 inline-flex items-center">
                        Close
                    </button>
                    <button type="submit" id="submit-btn"
                        class="text-white bg-blue-700 hover:bg-blue-800 rounded-lg text-sm px-5 py-2.5 inline-flex items-center">
                        Create
                    </button>
                </div>
            </form>
        </div>
    </div>
@endpush


@push('scripts')
    <script>
        var currentType = '{{ $type }}';
        var slug = '{{ $slug }}';

        var typeLabels = {
            admission_class: 'Admission Class',
            annual_fee: 'Annual Fee Component',
            monthly_fee_class: 'Monthly Fee (Class)',
            monthly_fee_other: 'Monthly Fee (Other)',
            proposed_monthly: 'Proposed Fee (Monthly)',
            proposed_annual: 'Proposed Fee (Annual)'
        };

        // Column definitions per type
        var typeColumns = {
            admission_class: [{
                    title: 'SN',
                    width: 10,
                    render: r => `<span class="font-semibold">${r.sn}</span>`
                },
                {
                    title: 'Class',
                    width: 100,
                    render: r => {
                        let t = r.data.class || 'N/A';
                        return `<div class="flex items-center"><span class="font-semibold">${t}</span>${r.is_active ? '<span class="inline-block w-3 h-3 rounded-full bg-green-500 ml-2"></span>' : '<span class="inline-block w-3 h-3 rounded-full bg-red-500 ml-2"></span>'}</div>`;
                    }
                },
                {
                    title: 'DOB Not Later Than',
                    width: 120,
                    render: r => r.data.dob_not_later_than || 'N/A'
                },
                {
                    title: 'Min Age',
                    width: 60,
                    render: r => r.data.min_age || 'N/A'
                },
                {
                    title: 'Required Age',
                    width: 80,
                    render: r => r.data.required_age || 'N/A'
                },
                {
                    title: 'Created By',
                    width: 100,
                    render: r => r.created_by ? `<span class="role-badge px-2 py-1 text-xs rounded-full bg-sky-200 dark:bg-blue-900/50 text-blue-800 dark:text-blue-300">${r.created_by}</span>` : '<span class="text-gray-500">N/A</span>'
                },
            ],
            annual_fee: [{
                    title: 'SN',
                    width: 10,
                    render: r => `<span class="font-semibold">${r.sn}</span>`
                },
                {
                    title: 'Item',
                    width: 200,
                    render: r => {
                        let t = r.data.item || 'N/A';
                        return `<div class="flex items-center"><span class="font-semibold">${t}</span>${r.is_active ? '<span class="inline-block w-3 h-3 rounded-full bg-green-500 ml-2"></span>' : '<span class="inline-block w-3 h-3 rounded-full bg-red-500 ml-2"></span>'}</div>`;
                    }
                },
                {
                    title: 'Amount (NPR)',
                    width: 100,
                    render: r => `Rs. ${r.data.amount_npr || 0}`
                },
                {
                    title: 'Frequency',
                    width: 120,
                    render: r => r.data.frequency || 'N/A'
                },
                {
                    title: 'Created By',
                    width: 100,
                    render: r => r.created_by ? `<span class="role-badge px-2 py-1 text-xs rounded-full bg-sky-200 dark:bg-blue-900/50 text-blue-800 dark:text-blue-300">${r.created_by}</span>` : '<span class="text-gray-500">N/A</span>'
                },
            ],
            monthly_fee_class: [{
                    title: 'SN',
                    width: 10,
                    render: r => `<span class="font-semibold">${r.sn}</span>`
                },
                {
                    title: 'Classes',
                    width: 200,
                    render: r => {
                        let t = r.data.classes || 'N/A';
                        return `<div class="flex items-center"><span class="font-semibold">${t}</span>${r.is_active ? '<span class="inline-block w-3 h-3 rounded-full bg-green-500 ml-2"></span>' : '<span class="inline-block w-3 h-3 rounded-full bg-red-500 ml-2"></span>'}</div>`;
                    }
                },
                {
                    title: 'Amount (NPR)',
                    width: 100,
                    render: r => `Rs. ${r.data.amount_npr || 0}`
                },
                {
                    title: 'Created By',
                    width: 100,
                    render: r => r.created_by ? `<span class="role-badge px-2 py-1 text-xs rounded-full bg-sky-200 dark:bg-blue-900/50 text-blue-800 dark:text-blue-300">${r.created_by}</span>` : '<span class="text-gray-500">N/A</span>'
                },
            ],
            monthly_fee_other: [{
                    title: 'SN',
                    width: 10,
                    render: r => `<span class="font-semibold">${r.sn}</span>`
                },
                {
                    title: 'Item',
                    width: 200,
                    render: r => {
                        let t = r.data.item || 'N/A';
                        return `<div class="flex items-center"><span class="font-semibold">${t}</span>${r.is_active ? '<span class="inline-block w-3 h-3 rounded-full bg-green-500 ml-2"></span>' : '<span class="inline-block w-3 h-3 rounded-full bg-red-500 ml-2"></span>'}</div>`;
                    }
                },
                {
                    title: 'Amount (NPR)',
                    width: 100,
                    render: r => `Rs. ${r.data.amount_npr || 0}`
                },
                {
                    title: 'Created By',
                    width: 100,
                    render: r => r.created_by ? `<span class="role-badge px-2 py-1 text-xs rounded-full bg-sky-200 dark:bg-blue-900/50 text-blue-800 dark:text-blue-300">${r.created_by}</span>` : '<span class="text-gray-500">N/A</span>'
                },
            ],
            proposed_monthly: [{
                    title: 'SN',
                    width: 10,
                    render: r => `<span class="font-semibold">${r.sn}</span>`
                },
                {
                    title: 'Class Type',
                    width: 200,
                    render: r => {
                        let t = r.data.class_type || 'N/A';
                        return `<div class="flex items-center"><span class="font-semibold">${t}</span>${r.is_active ? '<span class="inline-block w-3 h-3 rounded-full bg-green-500 ml-2"></span>' : '<span class="inline-block w-3 h-3 rounded-full bg-red-500 ml-2"></span>'}</div>`;
                    }
                },
                {
                    title: 'Amount (NPR)',
                    width: 100,
                    render: r => `Rs. ${r.data.amount_npr || 0}`
                },
                {
                    title: 'Frequency',
                    width: 120,
                    render: r => r.data.frequency || 'Monthly'
                },
                {
                    title: 'Created By',
                    width: 100,
                    render: r => r.created_by ? `<span class="role-badge px-2 py-1 text-xs rounded-full bg-sky-200 dark:bg-blue-900/50 text-blue-800 dark:text-blue-300">${r.created_by}</span>` : '<span class="text-gray-500">N/A</span>'
                },
            ],
            proposed_annual: [{
                    title: 'SN',
                    width: 10,
                    render: r => `<span class="font-semibold">${r.sn}</span>`
                },
                {
                    title: 'Item',
                    width: 200,
                    render: r => {
                        let t = r.data.item || 'N/A';
                        return `<div class="flex items-center"><span class="font-semibold">${t}</span>${r.is_active ? '<span class="inline-block w-3 h-3 rounded-full bg-green-500 ml-2"></span>' : '<span class="inline-block w-3 h-3 rounded-full bg-red-500 ml-2"></span>'}</div>`;
                    }
                },
                {
                    title: 'Amount (NPR)',
                    width: 100,
                    render: r => `Rs. ${r.data.amount_npr || 0}`
                },
                {
                    title: 'Frequency',
                    width: 120,
                    render: r => r.data.frequency || 'N/A'
                },
                {
                    title: 'Created By',
                    width: 100,
                    render: r => r.created_by ? `<span class="role-badge px-2 py-1 text-xs rounded-full bg-sky-200 dark:bg-blue-900/50 text-blue-800 dark:text-blue-300">${r.created_by}</span>` : '<span class="text-gray-500">N/A</span>'
                },
            ]
        };

        // Add actions column to all types
        Object.keys(typeColumns).forEach(function(key) {
            typeColumns[key].push({
                title: 'Actions',
                width: 100,
                render: function(row) {
                    var statusBtn = `
                        <button type="button" class="toggle-status-btn rounded-md border border-transparent p-2 text-center text-sm transition-all ${row.is_active ? 'text-green-600 hover:bg-green-50' : 'text-red-600 hover:bg-red-50'}" data-id="${row.id}" data-status="${row.is_active}">
                            <i class="fas ${row.is_active ? 'fa-toggle-on' : 'fa-toggle-off'} w-5 h-5"></i>
                        </button>`;
                    return `
                        <div class="flex space-x-2">
                            <button type="button" class="edit-btn rounded-md border border-transparent p-2 text-center text-sm transition-all text-slate-600 hover:bg-slate-100" data-id="${row.id}" data-row='${JSON.stringify(row)}'>
                                <i class="fas fa-edit w-4 h-4"></i>
                            </button>
                            <button type="button" class="delete-btn rounded-md border border-transparent p-2 text-center text-sm transition-all text-slate-600 hover:bg-slate-100" data-id="${row.id}">
                                <i class="fas fa-trash w-4 h-4"></i>
                            </button>
                            ${statusBtn}
                        </div>`;
                }
            });
        });

        function showTypeFields(type) {
            // Hide all type sections and DISABLE their inputs so they are excluded from FormData
            $('.type-fields').addClass('hidden');
            $('.type-fields').find('input, textarea, select').prop('disabled', true);

            // Show the active type section and ENABLE its inputs
            $('#fields-' + type).removeClass('hidden');
            $('#fields-' + type).find('input, textarea, select').prop('disabled', false);

            $('#form-type').val(type);
        }

        function modalOpen() {
            $('#custom-modal').show();
            $('#settings-form')[0].reset();
            $('#settings-form').data('id', null);
            $('#form-type').val(currentType);
            $('#modal-title').text('Create ' + typeLabels[currentType]);
            $('#submit-btn').text('Create');
            showTypeFields(currentType);
        }

        function buildTableColumns(type) {
            var cols = typeColumns[type] || [];
            var headHtml = '';
            cols.forEach(function(c) {
                headHtml += `<th width="${c.width}">${c.title}</th>`;
            });
            $('#table-head-row').html(headHtml);
            return cols;
        }

        var table_listing_table = null;

        function initTable(type) {
            if (table_listing_table) {
                table_listing_table.destroy();
                $('#settings-table tbody').empty();
            }

            var cols = buildTableColumns(type);

            table_listing_table = $('#settings-table').DataTable({
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
                "stateSave": false,
                "paging": true,
                "footer": true,
                "ajax": {
                    "url": "?getData&type=" + type,
                    "data": function(d) {
                        d.ajax = 1;
                    },
                    "error": function(xhr) {
                        nepalToast.error('Error', xhr.responseJSON?.message ||
                            'Failed to load data.');
                    }
                },
                "columns": cols.map(function(c) {
                    return {
                        "data": function(row) {
                            return c.render(row);
                        },
                        "orderable": false
                    };
                }),
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
        }

        function populateFormForEdit(row) {
            var d = row.data;
            var type = row.type;

            showTypeFields(type);
            $('#settings-form').data('id', row.id);
            $('input[name="order_index"]').val(row.order_index);
            $('#modal-title').text('Edit ' + typeLabels[type]);
            $('#submit-btn').text('Update');

            var fieldsContainer = $('#fields-' + type);

            switch (type) {
                case 'admission_class':
                    fieldsContainer.find('input[name="class"]').val(d.class || '');
                    fieldsContainer.find('input[name="dob_not_later_than"]').val(d.dob_not_later_than || '');
                    fieldsContainer.find('input[name="min_age"]').val(d.min_age || '');
                    fieldsContainer.find('input[name="required_age"]').val(d.required_age || '');
                    fieldsContainer.find('textarea[name="qualification_en"]').val(d.qualification_en || '');
                    fieldsContainer.find('textarea[name="qualification_np"]').val(d.qualification_np || '');
                    fieldsContainer.find('textarea[name="selection_en"]').val(d.selection_en || '');
                    fieldsContainer.find('textarea[name="selection_np"]').val(d.selection_np || '');
                    fieldsContainer.find('textarea[name="documents_en"]').val((d.documents_en || []).join(
                        "\n"));
                    fieldsContainer.find('textarea[name="documents_np"]').val((d.documents_np || []).join(
                        "\n"));
                    break;
                case 'annual_fee':
                    fieldsContainer.find('input[name="item"]').val(d.item || '');
                    fieldsContainer.find('input[name="amount_npr"]').val(d.amount_npr || '');
                    fieldsContainer.find('input[name="frequency"]').val(d.frequency || '');
                    break;
                case 'monthly_fee_class':
                    fieldsContainer.find('input[name="classes"]').val(d.classes || '');
                    fieldsContainer.find('input[name="amount_npr"]').val(d.amount_npr || '');
                    break;
                case 'monthly_fee_other':
                    fieldsContainer.find('input[name="item"]').val(d.item || '');
                    fieldsContainer.find('input[name="amount_npr"]').val(d.amount_npr || '');
                    break;
                case 'proposed_monthly':
                    fieldsContainer.find('input[name="class_type"]').val(d.class_type || '');
                    fieldsContainer.find('input[name="amount_npr"]').val(d.amount_npr || '');
                    fieldsContainer.find('input[name="frequency"]').val(d.frequency || 'Monthly');
                    break;
                case 'proposed_annual':
                    fieldsContainer.find('input[name="item"]').val(d.item || '');
                    fieldsContainer.find('input[name="amount_npr"]').val(d.amount_npr || '');
                    fieldsContainer.find('input[name="frequency"]').val(d.frequency || '');
                    break;
            }
        }

        $(document).ready(function() {
            initTable(currentType);

            // Per page selector
            $('#per-page-select').on('change', function() {
                var newLength = parseInt($(this).val());
                table_listing_table.page.len(newLength).draw();
            });
            $('#per-page-select').val(table_listing_table.page.len());

            // Pagination
            $('#custom-pagination').on('click', '.page-link:not(.pointer-events-none)', function() {
                var page = $(this).data('page');
                table_listing_table.page(page).draw('page');
            });

            // Tab switching
            $('.tab-btn').on('click', function() {
                var type = $(this).data('type');
                currentType = type;

                // Update URL without reload
                var newUrl = window.location.pathname + '?type=' + type;
                window.history.pushState({}, '', newUrl);

                // Update tab styling
                $('.tab-btn').removeClass(
                    'text-blue-600 border-blue-600 dark:text-blue-500 dark:border-blue-500');
                $('.tab-btn').addClass('border-transparent');
                $(this).addClass(
                    'text-blue-600 border-blue-600 dark:text-blue-500 dark:border-blue-500');
                $(this).removeClass('border-transparent');

                // Update title
                $('#table-title').text(typeLabels[type] + ' Management');

                // Rebuild table
                initTable(type);
            });

            // Open modal
            $('#openModal').click(function() {
                modalOpen();
            });

            // Close modal
            $(document).on('click', '.closeModal', function() {
                $('#custom-modal').hide();
            });
            $(document).on('click', '#custom-modal', function(e) {
                if ($(e.target).is('#custom-modal')) {
                    $('#custom-modal').hide();
                }
            });

            // Form submit
            $('#settings-form').on('submit', function(e) {
                e.preventDefault();
                let formData = new FormData(this);
                const id = $(this).data('id');
                if (id) {
                    formData.append('id', id);
                }
                formData.set('type', currentType);
                const url = "{{ route('admission-fee-settings.store', ':slug') }}".replace(
                    ':slug', slug);
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

            // Toggle status
            $(document).on('click', '.toggle-status-btn', function() {
                const id = $(this).data('id');
                $.ajax({
                    type: "GET",
                    url: "?status&type=" + currentType,
                    data: {
                        id: id,
                    },
                    success: function(response) {
                        if (response.status) {
                            nepalToast.success('Success', response.message);
                            table_listing_table.ajax.reload();
                        } else {
                            nepalToast.error('Error', response.message);
                        }
                    },
                    error: function(xhr) {
                        nepalToast.error('Error', xhr.responseJSON?.message ||
                            'An error occurred.');
                    }
                });
            });

            // Edit
            $(document).on('click', '.edit-btn', function() {
                var row = JSON.parse($(this).attr('data-row'));
                modalOpen();
                populateFormForEdit(row);
                $('#custom-modal').show();
            });

            // Delete
            $(document).on('click', '.delete-btn', function() {
                var id = $(this).data('id');
                nepalConfirm.show({
                    title: 'Delete Record',
                    message: 'Are you sure you want to delete this record? This action cannot be undone.',
                    type: 'danger',
                    confirmText: 'Delete',
                    cancelText: 'Cancel',
                    confirmIcon: '<i class="fas fa-trash w-4.5 h-4.5"></i>'
                }).then(() => {
                    const url =
                        "{{ route('admission-fee-settings.destroy', ':id') }}"
                        .replace(':id', id);
                    $.ajax({
                        url: url,
                        type: "DELETE",
                        data: {
                            _token: "{{ csrf_token() }}"
                        },
                        success: function(response) {
                            nepalToast.success('Success', response.message ||
                                'Deleted successfully!');
                            table_listing_table.ajax.reload();
                        },
                        error: function(xhr) {
                            nepalToast.error('Error', xhr.responseJSON
                                ?.message ||
                                'An error occurred.');
                        }
                    });
                }).catch(() => {
                    nepalToast.info('Action Canceled', 'Deletion was canceled.');
                });
            });

            // Success message from session
            const successMessage = $('#success-message').data('message');
            if (successMessage) {
                nepalToast.success('Success', successMessage);
            }
        });
    </script>
@endpush
