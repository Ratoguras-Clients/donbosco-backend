@php
    use App\Helper\ColorHelper;
    use App\Helper\Helper;

    $sidebarColor = 'orange';
@endphp

<!-- Desktop Sidebar Only - Completely Hidden on Mobile -->
<div class="hidden md:fixed md:left-0 md:top-0 md:h-screen md:flex md:z-40">
    <!-- Icon Sidebar (First Layer) -->
    @php $iconSidebarColors = ColorHelper::getColorClasses($sidebarColor); @endphp
    <div
        class="icon-sidebar bg-gradient-to-b {{ $iconSidebarColors['gradient'] }} w-16 flex-shrink-0 flex flex-col items-center py-6 {{ $iconSidebarColors['border'] }} border-r h-full shadow-xl">
        <!-- Logo/Brand -->
        <div class="mb-8">
            <a href="{{ $helper->generateRoute($sidebarConfig['brand']['route']) }}" class="block">
                <div
                    class="w-10 h-10 bg-white/20 rounded-lg flex items-center justify-center hover:bg-white/30 transition-all duration-200">
                    <span class="iconify w-6 h-6 text-white" data-icon="{{ $sidebarConfig['brand']['icon'] }}"></span>
                </div>
            </a>
        </div>

        <!-- Icon Menu -->
        <nav class="flex-1 w-full overflow-y-auto overflow-x-hidden custom-scrollbar flex flex-col items-center">
            <ul class="space-y-3 h-10 w-10">
                @foreach ($mainMenus as $menu)
                    @can($menu['permissions'])
                        <li>
                            <button data-menu="{{ $menu['id'] }}" data-tooltip="{{ $menu['tooltip'] }}"
                                class="sidebar-icon-btn tooltip flex justify-center h-10 w-10 items-center rounded-md transition-all duration-200 group
                            {{ $helper->isActiveRoute($menu['route_pattern'], $menu['active_routes'] ?? []) ? 'bg-white/20 text-white shadow-lg scale-105' : 'text-red-100 hover:bg-white/10 hover:text-white hover:scale-105' }}">
                                <span class="iconify w-5 h-5 group-hover:scale-110 transition-transform duration-200"
                                    data-icon="{{ $menu['icon'] }}"></span>
                            </button>
                        </li>
                    @endcan
                @endforeach
            </ul>
        </nav>

        <!-- Bottom Menu Items (Profile, etc.) -->
        <div class="mt-auto space-y-3">
            @foreach ($bottomMenus as $menu)
                <button data-menu="{{ $menu['id'] }}" data-tooltip="{{ $menu['tooltip'] }}"
                    class="sidebar-icon-btn tooltip w-full flex justify-center px-2 py-3 rounded-md transition-all duration-200 group text-red-100 hover:bg-white/10 hover:text-white hover:scale-105">
                    <span class="iconify w-6 h-6 group-hover:scale-110 transition-transform duration-200"
                        data-icon="{{ $menu['icon'] }}"></span>
                </button>
            @endforeach
        </div>
    </div>

    <!-- Full Sidebar (Second Layer) -->
    <div id="fullSidebar"
        class="full-sidebar bg-white dark:bg-gray-800 w-0 overflow-hidden transition-all duration-300 ease-in-out border-r border-gray-200 dark:border-gray-700 h-full shadow-2xl">
        <div class="w-72 h-full overflow-y-auto overflow-x-hidden custom-scrollbar">
            @foreach ($sidebarConfig['menus'] as $menu)
                @php $menuColors = ColorHelper::getColorClasses($menu['color']); @endphp
                <div data-menu-content="{{ $menu['id'] }}"
                    class="menu-content w-full p-6 {{ $helper->isActiveRoute($menu['route_pattern'], $menu['active_routes'] ?? []) ? '' : 'hidden' }}">

                    @foreach ($menu['sections'] as $section)
                        <div class="mb-8">
                            <div class="flex items-center mb-6">
                                <div
                                    class="{{ $menuColors['icon_bg'] }} w-8 h-8 rounded-lg flex items-center justify-center mr-3">
                                    <span class="iconify w-5 h-5 {{ $menuColors['icon_text'] }}"
                                        data-icon="{{ $section['icon'] }}"></span>
                                </div>
                                <h2 class="text-xl font-bold text-gray-900 dark:text-white">{{ $section['title'] }}
                                </h2>
                            </div>

                            {{-- Show user info if specified --}}
                            @if ($helper->shouldShowUserInfo($section))
                                <div class="bg-gray-50 dark:bg-gray-700/50 rounded-md p-4 mb-6">
                                    <div class="flex items-center">
                                        <div
                                            class="w-12 h-12 bg-gradient-to-br from-indigo-500 to-purple-600 rounded-full flex items-center justify-center mr-4">
                                            <span
                                                class="text-white font-bold text-lg">{{ substr(Auth::user()->name ?? 'U', 0, 1) }}</span>
                                        </div>
                                        <div>
                                            <h3 class="font-semibold text-gray-900 dark:text-white">
                                                {{ Auth::user()->name ?? 'User' }}</h3>
                                            <p class="text-sm text-gray-500 dark:text-gray-400">
                                                {{ Auth::user()->email ?? 'user@example.com' }}</p>
                                        </div>
                                    </div>
                                </div>
                            @endif

                            {{-- Handle dynamic content --}}
                            @if (isset($section['type']) && $section['type'] === 'dynamic_roles')
                                <ul class="space-y-1">
                                    @foreach ($allRoles as $role)
                                        <li>
                                            <a href="{{ $helper->generateRoute($section['route_template'], [$section['dynamic_type'] => $role]) }}"
                                                class="flex items-center px-4 py-2 rounded-md transition-all duration-200 group {{ ColorHelper::getLinkClasses($currentRoleId === $role->id, $menuColors) }}">
                                                <span
                                                    class="iconify w-5 h-5 mr-3 group-hover:scale-110 transition-transform duration-200"
                                                    data-icon="{{ $section['icon_template'] }}"></span>
                                                <span class="truncate font-medium">{{ $role->name }}</span>
                                            </a>
                                        </li>
                                    @endforeach
                                </ul>
                            @elseif (isset($section['type']) && $section['type'] === 'dynamic_pages')
                                <ul class="space-y-1">
                                    {{-- @foreach ($allPages as $page)
                                        <li>
                                            <a href="{{ $helper->generateRoute($section['route_template'], [$section['dynamic_type'] => $page]) }}"
                                                class="flex items-center px-4 py-2 rounded-md transition-all duration-200 group {{ ColorHelper::getLinkClasses(false, $menuColors) }}">
                                                <span
                                                    class="iconify w-5 h-5 mr-3 group-hover:scale-110 transition-transform duration-200"
                                                    data-icon="{{ $section['icon_template'] }}"></span>
                                                <span class="truncate font-medium">{{ $page->post_title }}</span>
                                            </a>
                                        </li>
                                    @endforeach --}}
                                </ul>
                            @else
                                {{-- Regular items --}}
                                <ul class="space-y-2">
                                    @foreach ($section['items'] as $item)
                                        @if ($helper->hasPermission($item['permission'] ?? null))
                                            <li>
                                                @if (isset($item['type']) && $item['type'] === 'dropdown')
                                                    {{-- Dropdown item --}}
                                                    <div class="relative">
                                                        <button
                                                            class="dropdown-toggle w-full flex items-center justify-between px-4 py-3 rounded-md transition-all duration-200 group {{ $menuColors['base_text'] }} {{ $menuColors['hover'] }}">
                                                            <div class="flex items-center">
                                                                <span
                                                                    class="iconify w-5 h-5 mr-3 flex-shrink-0 group-hover:scale-110 transition-transform duration-200"
                                                                    data-icon="{{ $item['icon'] }}"></span>
                                                                <span
                                                                    class="truncate font-medium">{{ $item['title'] }}</span>
                                                            </div>
                                                            <span
                                                                class="iconify w-4 h-4 transition-transform duration-200 dropdown-arrow"
                                                                data-icon="lucide:chevron-down"></span>
                                                        </button>
                                                        <div class="dropdown-menu hidden mt-2 ml-4 space-y-1">
                                                            @foreach ($item['items'] as $subItem)
                                                                <a href="{{ $helper->generateRoute($subItem['route'], $subItem['route_params'] ?? []) }}"
                                                                    class="flex items-center px-4 py-2 text-sm rounded-lg transition-all duration-200 group text-gray-600 dark:text-gray-300 {{ $menuColors['hover'] }}">
                                                                    <span
                                                                        class="iconify w-4 h-4 mr-2 group-hover:scale-110 transition-transform duration-200"
                                                                        data-icon="{{ $subItem['icon'] }}"></span>
                                                                    <span
                                                                        class="truncate">{{ $subItem['title'] }}</span>
                                                                </a>
                                                            @endforeach
                                                        </div>
                                                    </div>
                                                @else
                                                    {{-- Regular item --}}
                                                    @php
                                                        $itemColor = $helper->getItemColor($item, $menu['color']);
                                                        $itemColors = ColorHelper::getColorClasses($itemColor);
                                                        $isActive = $helper->isActiveRoute($item['route']);
                                                        $method = $helper->getItemMethod($item);
                                                    @endphp

                                                    @if ($method === 'POST')
                                                        <form method="POST"
                                                            action="{{ $helper->generateRoute($item['route'], $item['route_params'] ?? []) }}"
                                                            class="w-full">
                                                            @csrf
                                                            <button type="submit"
                                                                class="w-full flex items-center px-4 py-3 rounded-md transition-all duration-200 group {{ ColorHelper::getLinkClasses($isActive, $itemColors) }}">
                                                                <span
                                                                    class="iconify w-5 h-5 mr-3 flex-shrink-0 group-hover:scale-110 transition-transform duration-200"
                                                                    data-icon="{{ $item['icon'] }}"></span>
                                                                <span
                                                                    class="truncate font-medium">{{ $item['title'] }}</span>
                                                            </button>
                                                        </form>
                                                    @else
                                                        <a href="{{ $helper->generateRoute($item['route'], $item['route_params'] ?? []) }}"
                                                            class="flex items-center px-4 py-3 rounded-md transition-all duration-200 group {{ ColorHelper::getLinkClasses($isActive, $itemColors) }}">
                                                            <span
                                                                class="iconify w-5 h-5 mr-3 flex-shrink-0 group-hover:scale-110 transition-transform duration-200"
                                                                data-icon="{{ $item['icon'] }}"></span>
                                                            <span
                                                                class="truncate font-medium">{{ $item['title'] }}</span>
                                                        </a>
                                                    @endif
                                                @endif
                                            </li>
                                        @endif
                                    @endforeach
                                </ul>
                            @endif
                        </div>
                    @endforeach
                </div>
            @endforeach
        </div>
    </div>
</div>

@push('scripts')
@endpush

<style>
    /* Desktop content spacing only */
    @media (min-width: 768px) {
        body {
            padding-left: 64px;
            /* Width of icon sidebar */
            transition: padding-left 0.5s ease-in-out;
        }

        body.sidebar-open {
            padding-left: 352px;
            /* Width of icon sidebar + full sidebar (64px + 288px) */
        }
    }

    /* Mobile - no sidebar spacing */
    @media (max-width: 767px) {
        body {
            padding-left: 0 !important;
        }
    }

    /* Custom Scrollbar Styles */
    .custom-scrollbar {
        scrollbar-width: thin;
        scrollbar-color: rgba(239, 68, 68, 0.2) transparent;
    }

    .custom-scrollbar::-webkit-scrollbar {
        width: 6px;
    }

    .custom-scrollbar::-webkit-scrollbar-track {
        background: transparent;
        border-radius: 3px;
    }

    .custom-scrollbar::-webkit-scrollbar-thumb {
        background: rgba(239, 68, 68, 0.2);
        border-radius: 3px;
        transition: background 0.2s ease;
    }

    .custom-scrollbar::-webkit-scrollbar-thumb:hover {
        background: rgba(239, 68, 68, 0.4);
    }

    /* Dark mode scrollbar */
    .dark .custom-scrollbar {
        scrollbar-color: rgba(156, 163, 175, 0.2) transparent;
    }

    .dark .custom-scrollbar::-webkit-scrollbar-thumb {
        background: rgba(156, 163, 175, 0.2);
    }

    .dark .custom-scrollbar::-webkit-scrollbar-thumb:hover {
        background: rgba(156, 163, 175, 0.4);
    }

    .icon-sidebar,
    .full-sidebar,
    .full-sidebar>div {
        overflow-x: hidden !important;
    }

    .tooltip {
        position: relative;
    }

    .tooltip:hover::after {
        content: attr(data-tooltip);
        position: absolute;
        left: 100%;
        top: 50%;
        transform: translateY(-50%);
        background: rgba(0, 0, 0, 0.9);
        color: white;
        padding: 8px 12px;
        border-radius: 8px;
        font-size: 12px;
        font-weight: 500;
        white-space: nowrap;
        z-index: 1000;
        margin-left: 12px;
        box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
    }

    .tooltip:hover::before {
        content: '';
        position: absolute;
        left: 100%;
        top: 50%;
        transform: translateY(-50%);
        border: 6px solid transparent;
        border-right-color: rgba(0, 0, 0, 0.9);
        z-index: 1000;
        margin-left: 6px;
    }

    @media (max-width: 768px) {

        .tooltip:hover::after,
        .tooltip:hover::before {
            display: none;
        }
    }

    * {
        transition-property: color, background-color, border-color, text-decoration-color, fill, stroke, opacity, box-shadow, transform, filter, backdrop-filter;
        transition-timing-function: cubic-bezier(0.4, 0, 0.2, 1);
        transition-duration: 200ms;
    }
</style>
