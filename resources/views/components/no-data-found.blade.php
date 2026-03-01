<div id="{{ $hidden ? 'no-results' : '' }}"
    class="{{ $hidden ? 'hidden' : '' }} col-span-full bg-white dark:bg-gray-800 rounded-xl shadow-md p-8 text-center mb-4 border border-gray-100 dark:border-gray-700 transition-all duration-300 hover:shadow-lg border-b-4 border-b-nepal-blue">
    <div class="flex flex-col items-center justify-center py-4">
        <div class="bg-gray-50 dark:bg-gray-700 rounded-full p-6 mb-6 shadow-inner">
            <span class="iconify text-nepal-blue dark:text-nepal-blue/90" data-icon="{{ $icon }}"
                data-width="64"></span>
            {{-- <span class="iconify text-nepal-blue dark:text-nepal-blue/90" data-icon="{{ $permissionIcon }}"
                data-width="64"></span> --}}
        </div>

        <h3 class="text-xl font-semibold text-gray-900 dark:text-white mb-3">{{ $title }}</h3>

        <p class="text-gray-600 dark:text-gray-300 mb-6 max-w-md mx-auto">
            {{ $description }}
        </p>

        @if ($button['showbtn'])
            <a href="{{ $button['url'] }}"
                class="bg-nepal-blue hover:bg-nepal-blue/80 text-white px-6 py-3 rounded-lg shadow-sm flex items-center justify-center transition-all duration-200 hover:shadow-md transform hover:-translate-y-0.5">
                <span class="iconify mr-2" data-icon="{{ $button['icon'] }}" data-width="20"></span>
                {{ $button['text'] }}
            </a>
        @endif

        {{-- <p class="text-gray-600 dark:text-gray-300 mb-6 max-w-md mx-auto">
            {{ $permissionMessage }}
        </p> --}}
    </div>
</div>
