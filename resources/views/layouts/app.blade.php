<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="scroll-smooth light">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <link rel="icon" href="{{ asset('images/logo.jpg') }}" type="image/jpeg">

    <title>{{ config('app.name', 'Laravel') }}</title>
    <script src="https://code.iconify.design/3/3.1.0/iconify.min.js"></script>
    <!-- Select2 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />

    {{-- <script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script> --}}
    {{--  --}}
    <!-- Fonts -->
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap');

        body {
            font-family: 'Poppins', sans-serif;
        }
    </style>

    <!-- Sidebar CSS -->

    {{-- Datatables --}}
    <link rel="stylesheet" href="{{ asset('css/Datatable.css') }}">
    <link rel="stylesheet" href="{{ asset('css/table.css') }}">

    <script>
        // simple toggle based on localStorage or system preference
        const savedTheme = localStorage.getItem('theme');

        if (savedTheme === 'dark') {
            document.documentElement.classList.add('dark');
        } else {
            document.documentElement.classList.remove('dark');
        }
    </script>

    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            darkMode: 'class',
            theme: {
                extend: {}
            }
        }
    </script>
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    {{-- Quill CSS CDN --}}
    <link href="https://cdn.jsdelivr.net/npm/quill@2.0.3/dist/quill.snow.css" rel="stylesheet" />

    {{-- Date range picker CSS CDN --}}
    <link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.css" />

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">

    {{-- Lightbox CSS CDN --}}
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/lightbox2/2.11.5/css/lightbox.css" />

    {{-- Select2 --}}
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <link rel="stylesheet"
        href="https://cdnjs.cloudflare.com/ajax/libs/jqueryui/1.14.1/themes/base/jquery-ui.min.css" />

    <style>
        /* Hide scrollbar for all elements */
        * {
            scrollbar-width: none;
            /* Firefox */
            -ms-overflow-style: none;
            /* IE and Edge */
        }

        *::-webkit-scrollbar {
            display: none;
            /* Chrome, Safari, Opera */
        }

        .premium-table {
            width: 100% !important;
        }


        .page-item {
            background-color: transparent !important;
        }

        input::-webkit-outer-spin-button,
        input::-webkit-inner-spin-button {
            -webkit-appearance: none;
            margin: 0;
        }

        /* Firefox */
        input[type=number] {
            -moz-appearance: textfield;
        }

        /* Remove default arrow for all select elements */
        select {
            -webkit-appearance: none;
            /* Chrome, Safari, Opera */
            -moz-appearance: none;
            /* Firefox */
            appearance: none;
            /* Standard */
            background: transparent;
            /* Optional: remove default background */
            border: 1px solid #ccc;
            /* Optional styling */
            padding: 5px 10px;
            /* Optional spacing */
        }

        select::-ms-expand {
            display: none;
        }
    </style>
    {{-- dtatable buttons css --}}
    <link rel="stylesheet" href="https://cdn.datatables.net/buttons/2.4.2/css/buttons.dataTables.min.css">

    @stack('styles')
</head>

<body class="bg-gray-100 dark:bg-gray-900 min-h-screen flex flex-col">

    <main class="flex">
        <div class=" top-0 min-h-[100dvh] sidebar-container">
            <x-dynamic-sidebar />
        </div>

        <div class="flex flex-col w-full  p-6">
            <div class="space-y-6">
                @include('layouts.include.navbar')
                <main class="w-full">
                    @yield('content')
                </main>
            </div>
        </div>
    </main>

    @stack('modals')


    <script src="https://cdnjs.cloudflare.com/ajax/libs/iconify/2.0.0/iconify.min.js"></script>


    <script src="https://code.jquery.com/jquery-3.7.1.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jqueryui/1.14.1/jquery-ui.min.js"></script>
    <script src="{{ asset('js/Datatables.js') }}"></script>
    <script src="{{ asset('js/Datatable_tailwind.js') }}"></script>


    <script src="{{ asset('js/custom-confirm.js') }}"></script>
    <script src="{{ asset('js/nepal-toast.js') }}"></script>
    <script src="{{ asset('js/user-management.js') }}"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.30.1/moment.min.js"></script>

    {{-- Quill Js CDN --}}
    <script src="https://cdn.jsdelivr.net/npm/quill@2.0.3/dist/quill.js"></script>

    {{-- Date range picker js CDN --}}
    <script type="text/javascript" src="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.min.js"></script>


    <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
    <script src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js" defer></script>

    <!-- Select2 JS (after jQuery) -->
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

    {{-- Lightbox JS CDN --}}
    <script src="https://cdnjs.cloudflare.com/ajax/libs/lightbox2/2.11.5/js/lightbox.min.js"></script>
    <script src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js" defer></script>

    {{-- Select2 --}}
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

    {{-- datatable Buttons --}}
    <script src="https://cdnjs.cloudflare.com/ajax/libs/datatables.net-buttons/3.2.5/js/dataTables.buttons.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/datatables.net-buttons/3.2.5/js/buttons.colVis.min.js"></script>

    @if (session('success'))
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                if (typeof nepalToast !== 'undefined') {
                    nepalToast.success('Success', @json(session('success')));
                }
            });
        </script>
    @endif

    @if (session('error'))
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                if (typeof nepalToast !== 'undefined') {
                    nepalToast.error('Error', @json(session('error')));
                }
            });
        </script>
    @endif

    <script>
        $(document).off('click', '.dropdown-toggle').on('click', '.dropdown-toggle', function(e) {
            e.preventDefault();
            const dropdownMenu = $(this).siblings('.dropdown-menu');
            const arrow = $(this).find('.dropdown-arrow');
            dropdownMenu.slideToggle();
            arrow.toggleClass('rotate-180');
        });

        $(document).ready(function() {
            if (window.innerWidth >= 768) {
                let currentActiveMenu = null;

                $('.sidebar-icon-btn').on('click', function() {
                    const menuType = $(this).data('menu');
                    const fullSidebar = $('#fullSidebar');
                    const currentContent = $(`[data-menu-content="${menuType}"]`);

                    $('.sidebar-icon-btn').removeClass('bg-white/20 text-white shadow-lg scale-105')
                        .addClass('text-red-100 hover:bg-white/10 hover:text-white hover:scale-105');

                    if (currentActiveMenu === menuType && fullSidebar.hasClass('w-72')) {
                        fullSidebar.removeClass('w-72').addClass('w-0');
                        $('.menu-content').addClass('hidden');
                        $('body').removeClass('sidebar-open');
                        currentActiveMenu = null;
                    } else {
                        $('.menu-content').addClass('hidden');
                        currentContent.removeClass('hidden');
                        $(this).removeClass(
                                'text-red-100 hover:bg-white/10 hover:text-white hover:scale-105')
                            .addClass('bg-white/20 text-white shadow-lg scale-105');

                        if (!fullSidebar.hasClass('w-72')) {
                            fullSidebar.removeClass('w-0').addClass('w-72');
                            $('body').addClass('sidebar-open');
                        }
                        currentActiveMenu = menuType;
                    }
                });

                // Close sidebar when clicking outside
                // $(document).on('click', function(e) {
                // if (!$(e.target).closest('.hidden.md\\:fixed').length) {
                // $('#fullSidebar').removeClass('w-72').addClass('w-0');
                // $('.menu-content').addClass('hidden');
                // $('.dropdown-menu').slideUp();
                // $('.dropdown-arrow').removeClass('rotate-180');
                // $('body').removeClass('sidebar-open');
                // $('.sidebar-icon-btn').removeClass('bg-white/20 text-white shadow-lg scale-105')
                // .addClass('text-red-100 hover:bg-white/10 hover:text-white hover:scale-105');
                // currentActiveMenu = null;
                // }
                // });
            }
        });
    </script>
    @stack('scripts')
</body>

</html>
