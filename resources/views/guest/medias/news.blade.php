@extends('layouts.guest')

@section('content')


<!-- Loader: Add this at the very top -->
<div id="loader" class="fixed inset-0 z-50 flex items-center justify-center bg-white">
    <div class="w-16 h-16 border-4 border-primary border-t-transparent rounded-full animate-spin"></div>
</div>

<section class="w-full bg-primary h-[30vh] sm:h-[20vh] lg:h-[20vh] max-h-[100svh] flex flex-col items-center justify-center">

    <div>
        <h1 class="mb-2 text-3xl font-bold sm:text-4xl md:text-6xl text-white">
            News 
        </h1>

        <p class="max-w-2xl text-sm sm:text-base md:text-lg text-white/90">
            Stay updated with latest news & announcements.
        </p>
    </div>

</section>

<section class="bg-white py-12">
    <div class="mx-auto max-w-7xl px-6 lg:px-20">

        <!-- Header -->
        <div class="flex items-center gap-3 mb-10">
            <span class="w-9 h-9 flex items-center justify-center rounded-lg bg-primary text-white">
                <span class="iconify text-xl" data-icon="mdi:newspaper-variant-outline"></span>
            </span>

            <h2 class="text-2xl md:text-3xl font-semibold text-primary">
                News & Press Releases
            </h2>
        </div>
        <div class="grid gap-6 sm:grid-cols-2 lg:grid-cols-3">
            @foreach ($data['news'] as $news)
            <div class="border rounded-xl overflow-hidden hover:shadow-lg transition flex flex-col h-full bg-white">

                <img
                    src="{{ $news['image'] }}"
                    alt=""
                    class="w-full h-48 object-cover transition-transform duration-700 ease-in-out hover:scale-110">

                <div class="p-5 flex flex-col h-full">

                    {{-- CONTENT --}}
                    <div class="flex-1">
                        <h3 class="font-semibold text-gray-900 line-clamp-2">
                            {{ $news['title'] }}
                        </h3>

                        <div class="ql-editor text-sm text-text-muted mt-1 line-clamp-2">
                            {!! $news['summary'] !!}
                        </div>


                        <div class="flex items-center gap-2 text-xs text-text-muted mt-3">
                            <span class="iconify" data-icon="mdi:calendar-month-outline"></span>
                            {{ $news['published_date']->format('d M Y') }}
                        </div>
                    </div>

                    {{-- BUTTON --}}
                    <a href="{{ route('news.show', $news['uuid']) }}"
                        class="mt-4 inline-flex items-center justify-center gap-2
                  border border-primary text-primary
                  text-sm py-2.5 rounded-md
                  hover:bg-primary hover:text-white transition">
                        Read More
                        <span class="iconify" data-icon="mdi:arrow-right"></span>
                    </a>

                </div>
            </div>
            @endforeach


        </div>
    </div>
</section>


@endsection

@push('scripts')
<script>
    window.addEventListener('load', function() {
        const loader = document.getElementById('loader');
        if (loader) {
            loader.classList.add('opacity-0');
            setTimeout(() => loader.remove(), 500);
        }
    });
</script>
@endpush