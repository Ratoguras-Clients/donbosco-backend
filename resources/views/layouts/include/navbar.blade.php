<header
    class=" rounded-md w-full bg-white dark:bg-gray-900 text-gray-800 py-4 border-b border-gray-200 dark:border-gray-700 dark:text-gray-200 flex justify-center px-6">
    <div class="flex items-center justify-between w-full">

        <!-- Logo -->
        <div class="flex items-center">

            <a href="{{ url('/') }}" target="_blank" class="flex items-center">
                <img src="{{ url('/images/logo-bg-remove.png') }}" alt="CNI" class="h-12 w-auto mr-3">
            </a>

            <!-- Collaboration X Sign -->
            <div class="flex items-center justify-center mx-2">
                <span class="iconify text-gray-400 dark:text-gray-500" data-icon="lucide:x" data-width="16"></span>
            </div>

            <a href="https://ratoguras.com" target="_blank" class="flex items-center">
                <img src="{{ url('images/rg-logo.png') }}" alt="Rato Guras Technology Logo" class="h-12 w-auto mr-3">
            </a>
        </div>

        <!-- Right Side Items -->
        <div class="flex items-center gap-3">
            <!-- Date Display -->
            <div class="hidden md:block text-right pr-3 border-r border-gray-200 dark:border-gray-600">
                <p class="text-xs text-gray-600 dark:text-gray-400">{{ __('Date') }}:
                    <span class="font-medium">{{ date('M d, Y') }}</span>
                </p>
                <p class="text-xs text-gray-500 dark:text-gray-500">{{ date('l') }}</p>
            </div>

            <!-- User Menu -->
            <div class="relative ml-2">
                <button id="nav_userMenuToggle"
                    class="flex items-center gap-2 p-1 pl-3 rounded-full hover:bg-gray-100 dark:hover:bg-gray-700 transition-colors">
                    <div class="hidden md:block text-right">
                        <p class="text-xs font-medium leading-tight text-gray-800 dark:text-gray-200">
                            {{ Auth::user()->name }}</p>
                        <p class="text-xs text-gray-600 dark:text-gray-400">{{ Auth::user()->getRoleNames()->first() }}
                        </p>
                    </div>
                    <div class="h-8 w-8 rounded-full bg-gray-100 dark:bg-gray-700 flex items-center justify-center">
                        <span class="iconify text-gray-700 dark:text-gray-300" data-icon="tabler:user"
                            data-width="18"></span>
                    </div>
                </button>
                <div id="nav_userMenuDropdown"
                    class="absolute right-0 mt-1 bg-white dark:bg-gray-800 text-gray-800 dark:text-white rounded-md shadow-lg py-1 hidden min-w-[200px] border border-gray-200 dark:border-gray-700 z-50">
                    <div class="px-4 py-2 border-b border-gray-200 dark:border-gray-700">
                        <p class="font-medium">{{ Auth::user()->name }}</p>
                        <p class="text-xs text-gray-500 dark:text-gray-400">{{ Auth::user()->email }}</p>
                    </div>
                    <div class="border-t border-gray-200 dark:border-gray-700 mt-1"></div>
                    <a href="{{ route('logout') }}"
                        onclick="event.preventDefault(); document.getElementById('logout-form').submit();"
                        class="w-full text-left px-4 py-2 hover:bg-gray-100 dark:hover:bg-gray-700 flex items-center gap-2 text-red-600 dark:text-red-400">
                        <span class="iconify" data-icon="tabler:logout" data-width="16"></span>
                        Logout
                    </a>
                    <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                        @csrf
                    </form>
                </div>
            </div>

            <!-- Mobile Menu Toggle -->
            <button id="nav_mobileMenuToggle"
                class="md:hidden p-1.5 rounded-full hover:bg-gray-100 dark:hover:bg-gray-700 transition-colors text-gray-700 dark:text-gray-300">
                <span class="iconify" data-icon="tabler:menu-2" data-width="20"></span>
            </button>
        </div>
    </div>
</header>

@push('scripts')
    <script>
        $(document).ready(function() {

            // Toggle theme on click
            $('#nav_themeToggle').off('click').on('click', function() {
                const html = document.documentElement;
                const isDark = html.classList.contains('dark');
                if (isDark) {
                    html.classList.remove('dark');
                    localStorage.setItem('theme', 'light');
                } else {
                    html.classList.add('dark');
                    localStorage.setItem('theme', 'dark');
                }
                updateIcons();
            });

            // Language toggler functionality
            const languageToggle = $('#nav_languageToggle');
            const languageDropdown = $('#nav_languageDropdown');

            // Toggle language dropdown
            languageToggle.on('click', function(e) {
                e.preventDefault();
                e.stopPropagation();
                languageDropdown.toggleClass('hidden');
                $('#nav_userMenuDropdown').addClass('hidden');
            });

            // User menu functionality
            const userMenuToggle = $('#nav_userMenuToggle');
            const userMenuDropdown = $('#nav_userMenuDropdown');

            userMenuToggle.on('click', function(e) {
                e.preventDefault();
                e.stopPropagation();
                userMenuDropdown.toggleClass('hidden');
                languageDropdown.addClass('hidden');
            });

            // Mobile menu functionality
            const mobileMenuToggle = $('#nav_mobileMenuToggle');
            const mobileMenu = $('#nav_mobileMenu');

            mobileMenuToggle.on('click', function(e) {
                e.preventDefault();
                e.stopPropagation();
                mobileMenu.toggleClass('hidden');
            });

            // Mobile navigation section toggle functionality
            $('.nav-section-toggle').on('click', function(e) {
                e.preventDefault();
                const section = $(this).data('section');
                const content = $(`#nav-section-${section}`);
                const chevron = $(this).find('.chevron-icon');

                // Close all other sections
                $('.nav-section-content').not(content).addClass('hidden');
                $('.chevron-icon').not(chevron).removeClass('rotate-90');

                // Toggle current section
                content.toggleClass('hidden');
                chevron.toggleClass('rotate-90');
            });

            // Close dropdowns when clicking outside
            $(document).on('click', function(e) {
                if (!$(e.target).closest('#nav_languageToggle, #nav_languageDropdown').length) {
                    languageDropdown.addClass('hidden');
                }
                if (!$(e.target).closest('#nav_userMenuToggle, #nav_userMenuDropdown').length) {
                    userMenuDropdown.addClass('hidden');
                }
                if (!$(e.target).closest('#nav_mobileMenuToggle, #nav_mobileMenu').length) {
                    mobileMenu.addClass('hidden');
                }
            });
        });
    </script>
@endpush

<style>
    /* Smooth transitions for chevron rotation */
    .chevron-icon {
        transition: transform 0.2s ease-in-out;
    }

    .rotate-90 {
        transform: rotate(90deg);
    }

    /* Smooth slide animation for section content */
    .nav-section-content {
        transition: all 0.3s ease-in-out;
        overflow: hidden;
    }

    /* Active section styling */
    .nav-section-toggle[aria-expanded="true"] {
        background-color: rgba(239, 68, 68, 0.1);
        color: rgb(220, 38, 38);
    }

    /* Hover effects */
    .nav-section-toggle:hover {
        background-color: rgba(0, 0, 0, 0.05);
    }

    .dark .nav-section-toggle:hover {
        background-color: rgba(255, 255, 255, 0.05);
    }

    /* Sub-item indentation */
    .nav-section-content {
        border-left: 2px solid rgba(239, 68, 68, 0.2);
        margin-left: 1rem;
        padding-left: 0.5rem;
    }

    /* Mobile menu max height with smooth scrolling */
    #nav_mobileMenu {
        scrollbar-width: thin;
        scrollbar-color: rgba(156, 163, 175, 0.5) transparent;
    }

    #nav_mobileMenu::-webkit-scrollbar {
        width: 4px;
    }

    #nav_mobileMenu::-webkit-scrollbar-track {
        background: transparent;
    }

    #nav_mobileMenu::-webkit-scrollbar-thumb {
        background: rgba(156, 163, 175, 0.5);
        border-radius: 2px;
    }

    #nav_mobileMenu::-webkit-scrollbar-thumb:hover {
        background: rgba(156, 163, 175, 0.7);
    }
</style>
