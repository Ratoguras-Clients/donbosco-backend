@extends('layouts.guest')

@section('content')
<section class="w-full bg-primary min-h-[15vh] sm:min-h-[20vh] lg:min-h-[30vh] flex flex-col items-center justify-center px-4 sm:px-6 lg:px-12">
    <div class="max-w-7xl">
        <h1 class="text-xl sm:text-2xl md:text-3xl lg:text-5xl font-bold text-white leading-snug">
            {{$notices['title']}}
        </h1>
    </div>
</section>


<section class="pb-12 mt-4">
    <div class="container mx-auto px-4">
        <div class="grid lg:grid-cols-3 gap-8">

            <!-- Main Notice Content -->
            <div class="lg:col-span-2 rounded-xl p-6">
                @if($notices['image'])
                <img class="w-full rounded-lg mb-4"
                    src="{{ $notices['image'] ?? 'N/A' }}"
                    alt="{{ $notices['title'] }}">
                @endif

                <div class="flex flex-wrap gap-6 mb-6 text-gray-600 text-sm">
                    <div class="flex items-center gap-2">
                        <span class="iconify" data-icon="bx:calendar"></span>
                        Published Date: {{ \Carbon\Carbon::parse($notices['notice_date'])->format('F d, Y') }}
                    </div>
                    <div class="flex items-center gap-2">
                        <span class="iconify" data-icon="bx:pen"></span>
                        Author: {{ $organization->name ?? 'Unknown' }}
                    </div>
                </div>

                <p class="mb-4">{!! $notices['description'] !!}</p>
            </div>

            <!-- Sidebar -->
            <aside class="space-y-8">
                <!-- Recent Updates -->
                <div class="rounded-xl bg-gray-100 shadow-lg p-6">
                    <h6 class="text-lg font-semibold mb-4">Recent Updates</h6>

                    <div class="space-y-4">
                        @forelse($otherNotice as $item)
                        <div class="flex gap-3">
                            <img class="w-16 h-16 object-cover rounded"
                                src="{{ $item['image'] ?? 'N/A' }}"
                                alt="{{ $item['title'] }}">
                            <div>
                                <a href="{{ route('notices.show', $item['uuid']) }}"
                                    class="block text-sm font-medium text-gray-900 hover:underline">
                                    {{ $item['title'] }}
                                </a>
                                <span class="flex items-center gap-1 text-gray-500 text-xs mt-1">
                                    <span class="iconify" data-icon="fa-regular:calendar"></span>
                                    {{ \Carbon\Carbon::parse($item['notice_date'])->format('F d, Y') }}
                                </span>
                            </div>
                        </div>
                        @empty
                        <p class="text-gray-500 text-sm">No recent updates available.</p>
                        @endforelse
                    </div>
                </div>
            </aside>

        </div>
    </div>
</section>

@endsection