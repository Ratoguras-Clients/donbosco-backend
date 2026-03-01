@extends('layouts.guest')

@section('content')
<section class="w-full bg-primary h-[30vh] sm:h-[20vh] lg:h-[20vh] max-h-[100svh] flex flex-col items-center justify-center">

    <div>
        <h1 class="mb-4 text-3xl font-bold sm:text-4xl md:text-6xl text-white">
            Notices
        </h1>

        <p class="max-w-2xl text-sm sm:text-base md:text-lg text-white/90">
            Stay updated with latest notices & announcements.
        </p>
    </div>

</section>

<section class="py-12 bg-white sm:py-16">
    <div class="w-full px-4 sm:px-6 lg:px-8">

        <div class="grid place-items-center">
            <div class="w-full max-w-4xl">

                {{-- ================= Notices Header ================= --}}
                <div class="flex flex-wrap items-center justify-between gap-6 mb-10">
                    <div>
                        <div class="flex items-center gap-2 mb-2">
                            <span class="text-xl text-red-600 iconify" data-icon="mdi:bell-outline"></span>
                            <h2 class="text-3xl font-semibold sm:text-4xl text-gray-900">Notices</h2>
                        </div>
                        <p class="text-base text-gray-600">
                            Stay updated with the latest announcements
                        </p>
                    </div>

                   
                </div>

                {{-- ================= Notices Full-Width Cards ================= --}}
                <div class="space-y-6 ">

                    @foreach ($data['notices'] as $notice)
                    <div class="group  relative flex flex-col justify-between p-6 bg-gray-200 border border-gray-200 rounded-xl shadow-sm hover:shadow-lg transition-shadow duration-300">

                        {{-- Icon + Title --}}
                        <div class="flex items-start gap-3 mb-4">
                            <span class="mt-1 text-2xl text-red-600 iconify" data-icon="mdi:bell-outline"></span>
                            <div>
                                <a href="{{ route('notices.show', $notice['id']) }}"
                                    class="block text-xl font-semibold text-gray-900 hover:text-red-600 transition-colors">
                                    {{ $notice['title'] }}
                                </a>
                                <p class="mt-1 text-sm text-gray-500">
                                    {{ $notice['notice_date']->format('F d, Y') }}
                                </p>
                            </div>
                        </div>

                        {{-- Description --}}
                        <p class="text-gray-700 text-sm leading-relaxed">
                            {{ Str::limit($notice['description'], 150) }}
                        </p>

                        {{-- Read More Link --}}
                        <a href="{{ route('notices.show', $notice['uuid']) }}"
                            class="mt-4 inline-block text-sm font-medium text-primary hover:underline">
                            Read More →
                        </a>
                    </div>
                    @endforeach

                </div>

            </div>
        </div>
    </div>
</section>

@endsection