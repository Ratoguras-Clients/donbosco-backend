<!-- Breadcrumb Section -->
<div class="bg-gray-50 dark:bg-slate-800 rounded-md border-b border-gray-200 dark:border-gray-700 mb-6">
    <div class="max-w-[1600px] w-full px-6 py-2">
        <nav class="py-3 flex items-center text-sm" aria-label="Breadcrumb">
            <ol class="inline-flex items-center space-x-1 md:space-x-2">
                <li class="inline-flex items-center">
                    <a href="{{ route('dashboard') }}"
                        class="inline-flex items-center text-gray-500  hover:text-nepal-blue ">
                        <span class="iconify mr-2" data-icon="tabler:home" data-width="16"></span>
                        {{ __('lang.dashboard') }}
                    </a>
                </li>

                @foreach ($breadcrumbs as $key => $breadcrumb)
                    <li>
                        <div class="flex items-center">
                            @if ($key == 0)
                                |
                            @else
                                <span class="iconify text-gray-400" data-icon="tabler:chevron-right"
                                    data-width="16"></span>
                            @endif
                            @if (isset($breadcrumb['url']) && !$loop->last)
                                <a href="{{ $breadcrumb['url'] }}"
                                    class="ml-1 md:ml-2 text-gray-500 dark:text-gray-400 hover:text-nepal-blue dark:hover:text-white">
                                    {{ $breadcrumb['title'] }}
                                </a>
                            @else
                                <span class="ml-1 md:ml-2 text-nepal-blue  font-medium">
                                    {{ $breadcrumb['title'] }}
                                </span>
                            @endif
                        </div>
                    </li>
                @endforeach
            </ol>
        </nav>
    </div>
</div>
