<div class="items-center hidden space-x-8 md:flex">
    @foreach ($pages as $page)
        @if(isset($page['children']) && count($page['children']) > 0)
            <!-- Page with children - dropdown menu -->
            <div class="relative group">
                <a href="{{ url('/'.$page['post_name']) }}"
                   class="text-white hover:text-red-500 transition-colors flex items-center {{ request()->path() == $page['post_name'] ? 'font-medium border-b-2 border-red-500 pb-1' : '' }}">
                   {{ $page['post_title'] }}
                   <svg class="w-4 h-4 ml-1 transition-transform group-hover:rotate-180" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                       <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                   </svg>
                </a>
                
                <!-- Dropdown menu -->
                <div class="absolute left-0 mt-2 w-64 bg-black/90 backdrop-blur-sm rounded-lg shadow-xl border border-gray-700 opacity-0 invisible group-hover:opacity-100 group-hover:visible transition-all duration-200 z-50">
                    <div class="py-2">
                        @foreach($page['children'] as $child)
                            <a href="{{ route('detail-page.render',[$page['post_name'],$child['post_name']]) }}" 
                               class="block px-4 py-3 text-white hover:bg-red-500/20 hover:text-red-400 transition-colors border-b border-gray-700 last:border-b-0">
                                <div class="font-medium">{{ $child['post_title'] }}</div>
                                {{-- @if($child['post_excerpt'])
                                    <div class="text-sm text-gray-400 mt-1">{{ $child['post_excerpt'] }}</div>
                                @endif --}}
                            </a>
                        @endforeach
                    </div>
                </div>
            </div>
        @else
            <!-- Page without children - regular link -->
            <a href="{{ url('/'.$page['post_name']) }}"
               class="text-white hover:text-red-500 transition-colors {{ request()->path() == $page['post_name'] ? 'font-medium border-b-2 border-red-500 pb-1' : '' }}">
               {{ $page['post_title'] }}
            </a>
        @endif
    @endforeach
</div>
