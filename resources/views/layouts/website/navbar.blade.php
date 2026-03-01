<header class="relative z-50 bg-white border-b shadow-sm">
    <div class="px-4 mx-auto max-w-7xl sm:px-6">
        <div class="flex items-center justify-between h-16">

            {{-- Logo --}}
            <div class="flex items-center gap-3">
                <img src="{{ asset('images/cni.png') }}" alt="CNI" class="w-auto h-12">
            </div>

            {{-- Desktop Nav --}}
            <nav class="items-center hidden gap-6 text-sm lg:gap-8 md:flex md:text-base lg:text-lg">
                @php
                    $active = 'text-primary font-semibold';
                    $inactive = 'text-gray-700 hover:text-primary transition';
                @endphp

                <a href="{{ route('home') }}"
                   class="{{ request()->routeIs('home') ? $active : $inactive }}">
                    Home
                </a>

                <a href="{{ route('about') }}"
                   class="{{ request()->routeIs('about') ? $active : $inactive }}">
                    About Us
                </a>

                <a href="{{ route('team') }}"
                   class="{{ request()->routeIs('team') ? $active : $inactive }}">
                    Team
                </a>

                {{-- Media Dropdown --}}
                <div class="relative group">
                    <button
                        class="flex items-center gap-1 {{ request()->is('gallery*','events*','blog*','news*','organization*') ? $active : $inactive }}">
                        Media
                        <span class="text-sm iconify" data-icon="mdi:chevron-down"></span>
                    </button>

                    <div
                        class="absolute left-0 invisible w-56 mt-3 transition-all duration-200 bg-white border rounded-md shadow-lg opacity-0 group-hover:visible group-hover:opacity-100">

                        <a href="{{ route('gallery') }}"
                           class="block px-4 py-2 hover:bg-gray-100 {{ request()->routeIs('gallery') ? $active : 'text-gray-700' }}">
                            Gallery
                        </a>

                        <a href="{{ route('events') }}"
                           class="block px-4 py-2 hover:bg-gray-100 {{ request()->routeIs('events') ? $active : 'text-gray-700' }}">
                            Events
                        </a>

                        <a href="{{ route('blog') }}"
                           class="block px-4 py-2 hover:bg-gray-100 {{ request()->routeIs('blog') ? $active : 'text-gray-700' }}">
                            Blog
                        </a>

                        <a href="{{ route('news') }}"
                           class="block px-4 py-2 hover:bg-gray-100 {{ request()->routeIs('news') ? $active : 'text-gray-700' }}">
                            News  
                        </a>

                        <a href="{{ route('notices') }}"
                           class="block px-4 py-2 hover:bg-gray-100 {{ request()->routeIs('notices') ? $active : 'text-gray-700' }}">
                            Notices 
                        </a>

                        <a href="{{ route('organization') }}"
                           class="block px-4 py-2 hover:bg-gray-100 {{ request()->routeIs('organization') ? $active : 'text-gray-700' }}">
                            Sister Organization
                        </a>
                    </div>
                </div>

                <a href="{{ route('contact') }}"
                   class="{{ request()->routeIs('contact') ? $active : $inactive }}">
                    Contact
                </a>
            </nav>

            {{-- Mobile Menu Button --}}
            <button id="mobileMenuButton" class="md:hidden">
                <span class="text-3xl text-gray-700 iconify" data-icon="mdi:menu"></span>
            </button>
        </div>
    </div>

    {{-- Mobile Menu --}}
    <div id="mobileMenu"
         class="absolute left-0 hidden w-full bg-white border-t shadow-lg md:hidden">

        <nav class="flex flex-col text-lg font-medium">
            <a href="{{ route('home') }}" class="px-6 py-4 hover:bg-gray-100">Home</a>
            <a href="{{ route('about') }}" class="px-6 py-4 hover:bg-gray-100">About Us</a>
            <a href="{{ route('team') }}" class="px-6 py-4 hover:bg-gray-100">Team</a>

            {{-- Mobile Media --}}
            <button id="mobileMediaButton"
                class="flex items-center justify-between px-6 py-4 hover:bg-gray-100">
                Media
                <span class="iconify" data-icon="mdi:chevron-down"></span>
            </button>

            <div id="mobileMediaDropdown" class="hidden bg-gray-50">
                <a href="{{ route('gallery') }}" class="block px-10 py-3">Gallery</a>
                <a href="{{ route('events') }}" class="block px-10 py-3">Events</a>
                <a href="{{ route('blog') }}" class="block px-10 py-3">Blog</a>
                <a href="{{ route('news') }}" class="block px-10 py-3">News</a>
                 <a href="{{ route('notices') }}" class="block px-10 py-3"> Notices</a>
                <a href="{{ route('organization') }}" class="block px-10 py-3">Sister Organization</a>
            </div>

            <a href="{{ route('contact') }}" class="px-6 py-4 border-t hover:bg-gray-100">
                Contact
            </a>
        </nav>
    </div>
</header>

<script>
    const mobileMenuBtn = document.getElementById('mobileMenuButton');
    const mobileMenu = document.getElementById('mobileMenu');
    const mobileMediaBtn = document.getElementById('mobileMediaButton');
    const mobileMediaDropdown = document.getElementById('mobileMediaDropdown');

    mobileMenuBtn.addEventListener('click', () => {
        mobileMenu.classList.toggle('hidden');
    });

    mobileMediaBtn.addEventListener('click', () => {
        mobileMediaDropdown.classList.toggle('hidden');
    });
</script>
