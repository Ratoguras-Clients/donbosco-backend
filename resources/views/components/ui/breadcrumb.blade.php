@props(['items' => []])

@php
    $dropdownId = uniqid('breadcrumb_dropdown_');
@endphp

<nav x-data="{ activeDropdown: null }"
    class="relative flex items-center justify-between space-x-1 py-4 mb-4  {{ $attributes->get('class') }}"
    aria-label="Breadcrumb">
    <!-- Decorative Background Elements -->
    <div class="">
        <h1 class="text-3xl font-bold tracking-tight">Projects</h1>
        <p class="text-muted-foreground">Manage and track all your projects in one place</p>
    </div>
    <ol class="relative flex items-center space-x-1 z-10">
        @foreach ($items as $index => $item)
            <li class="relative flex items-center">
                <div class="relative">
                    @if (isset($item['dropdown']))
                        <button
                            @click="activeDropdown = activeDropdown === {{ $index }} ? null : {{ $index }}"
                            class="flex items-center space-x-2 px-3 py-2 rounded-xl text-sm font-medium transition-all duration-300 
                                bg-white hover:bg-white/90 text-slate-700 hover:text-slate-900
                                border border-slate-200/50 hover:border-slate-300/70
                                shadow-sm hover:shadow-md
                                backdrop-blur-sm"
                            :class="{ 'bg-white/90 shadow-md scale-105': activeDropdown === {{ $index }} }"
                            aria-expanded="true">
                            @if (isset($item['icon']))
                                <span class="text-slate-500 transition-colors">
                                    <i class="{{ $item['icon'] }}"></i>
                                </span>
                            @endif
                            <span class="whitespace-nowrap">{{ $item['label'] }}</span>
                            <svg class="w-4 h-4 text-slate-400 transition-transform duration-200"
                                :class="{ 'rotate-180': activeDropdown === {{ $index }} }"
                                xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M19 9l-7 7-7-7" />
                            </svg>
                        </button>

                        <!-- Dropdown -->
                        <div x-show="activeDropdown === {{ $index }}" x-transition
                            class="absolute top-full left-0 mt-2 w-56 bg-white/95 backdrop-blur-md rounded-xl shadow-xl border border-slate-200/60 py-2 z-50">
                            <div
                                class="absolute -top-1 left-4 w-2 h-2 bg-white/95 border-l border-t border-slate-200/60 rotate-45">
                            </div>
                            @foreach ($item['dropdown'] as $dropdown)
                                <a href="{{ $dropdown['href'] }}"
                                    class="w-full flex items-center space-x-3 px-4 py-2.5 text-sm text-slate-700 hover:text-slate-900 hover:bg-slate-50/80 transition-colors duration-150">
                                    @if (isset($dropdown['icon']))
                                        <span class="text-slate-500 flex-shrink-0">{{ $dropdown['icon'] }}</span>
                                    @endif
                                    <span class="truncate">{{ $dropdown['label'] }}</span>
                                </a>
                            @endforeach
                        </div>
                    @else
                        <a href="{{ $item['href'] ?? '#' }}"
                            class="flex items-center space-x-2 px-3 py-2 rounded-xl text-sm font-medium transition-all duration-300 hover:scale-105
                            {{ $index === count($items) - 1
                                ? 'bg-gradient-to-r from-blue-500 to-blue-600 text-white shadow-lg hover:shadow-xl'
                                : 'bg-white hover:bg-white/90 text-slate-700 hover:text-slate-900 border border-slate-200/50 hover:border-slate-300/70 shadow-sm hover:shadow-md' }} backdrop-blur-sm">
                            @if (isset($item['icon']))
                                <span class="{{ $index === count($items) - 1 ? 'text-white/90' : 'text-slate-500' }}">
                                    <i class="{{ $item['icon'] }}"></i>
                                </span>
                            @endif
                            <span class="whitespace-nowrap">{{ $item['label'] }}</span>
                        </a>
                    @endif
                </div>

                @if ($index < count($items) - 1)
                    <svg class="w-4 h-4 text-slate-400 mx-2 flex-shrink-0" xmlns="http://www.w3.org/2000/svg"
                        fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                    </svg>
                @endif
            </li>
        @endforeach
    </ol>

    <!-- Outside Click -->
    <div x-show="activeDropdown !== null" @click="activeDropdown = null" class="fixed inset-0 z-40"></div>
</nav>
