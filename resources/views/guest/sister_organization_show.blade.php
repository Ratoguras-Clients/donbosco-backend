@extends('layouts.guest')

@section('content')
@if(!empty($data['homecarousel']) && count($data['homecarousel']) > 0)
<section class="w-full h-[60vh] sm:h-[75vh] lg:h-[70vh] max-h-[100svh] overflow-hidden">
    <div class="h-full swiper heroSwiper">
        <div class="h-full swiper-wrapper">

            @foreach ($data['homecarousel'] as $slide)
            <div class="h-full bg-center bg-cover swiper-slide"
                style="
                        background-image:
                        linear-gradient(rgba(0,0,0,0.5), rgba(0,0,0,0.5)),
                        url('{{ $slide['image'] ?? asset('images/default-banner.png') }}');
                    ">

                <div class="flex items-center h-full pt-10 sm:pt-0">
                    <div class="w-full px-4 mx-auto text-white sm:px-6 lg:px-8 max-w-7xl">

                        <h1 class="mb-4 text-3xl font-bold sm:text-4xl md:text-6xl">
                            {{ $slide['title'] }}
                        </h1>

                        @if (!empty($slide['subtitle']))
                        <h2 class="mb-6 text-lg font-semibold sm:text-2xl md:text-3xl">
                            {!! $slide['subtitle'] !!}
                        </h2>
                        @endif

                    </div>
                </div>
            </div>
            @endforeach

        </div>

        <!-- Pagination -->
        <div class="mb-2 swiper-pagination"></div>
    </div>
</section>
@endif



<section class="bg-light-primary py-10">
    <div class="mx-auto max-w-7xl px-6 lg:px-20">
        <div class="grid lg:grid-cols-2 gap-12 items-center">

            <div>
                <p class="text-sm text-text-muted mb-2">Know</p>

                <h2 class="text-4xl font-bold ">
                    About <span class="text-primary">{{$data['organization']->short_name}}</span>
                </h2>

                <p class="text-primary  font-bold text-3xl mb-6">
                    {{$data['organization']->name}}
                </p>

                <p class="text-text-muted leading-relaxed mb-8">
                    {{$data['organization']->description}}
                </p>


            </div>

            <div class="relative flex justify-center">
                <div
                    class="absolute top-[-12px] left-1/2 -translate-x-1/2
                           w-5 h-5 bg-primary rounded">
                </div>

                <div class="bg-light-primary rounded-xl p-2">
                    <img
                        src="{{ optional($data['organization']->media)->url ?? 'https://placehold.co/600x400' }}"
                        alt="{{ $data['organization']->name }}"
                        class="rounded-lg shadow-lg w-full max-w-md object-cover">

                </div>
            </div>

        </div>
    </div>
</section>

{{-- journey --}}
<section class="py-12 bg-white sm:py-16">
    <div class="w-full px-4 sm:px-6 lg:px-8">

        <div class="grid place-items-center">
            <div class="w-full max-w-7xl">

                <h2 class="mb-20 text-3xl font-semibold text-center text-primary sm:text-4xl">
                    Our Journey
                </h2>

                <div class="relative w-full  mx-auto space-y-12">
                    @foreach ($data['journeys'] as $index => $journey)
                    @php
                    $isLeft = $index % 2 === 0;
                    @endphp

                    <div class="relative flex flex-col lg:flex-row lg:items-start">

                        <!-- Spacer for alternating sides -->
                        <div class="{{ $isLeft ? 'hidden lg:block lg:w-1/2' : '' }}"></div>

                        <!-- Dot & Line (always center) -->
                        <div
                            class="absolute top-0 left-0 flex flex-col items-center h-full lg:left-1/2 lg:-translate-x-1/2">
                            <span class="w-4 h-4 rounded-full bg-primary"></span>
                            <div class="flex-1 w-1 mt-1 bg-blue-400"></div>
                        </div>

                        <!-- Content -->
                        <div
                            class="w-full pt-6 lg:w-1/2 lg:pt-0
                    {{ $isLeft ? 'lg:pr-20 lg:text-left p-10' : 'lg:pl-20 lg:text-right p-10' }}">
                            <span class="font-semibold text-primary">
                                {{ $journey['start_date']->format('d M Y') }}
                            </span>
                            <h3 class="mt-2 text-2xl font-bold text-gray-800">
                                {{ $journey['title'] }}
                            </h3>
                            <p class="mt-2 text-lg text-gray-500 ">
                                {{ $journey['description'] }}
                            </p>
                        </div>

                    </div>
                    @endforeach

                </div>

            </div>
        </div>

    </div>
</section>

{{-- events --}}
<section class="py-12 bg-light-primary sm:py-16">
    <div class="w-full px-4 sm:px-6 lg:px-8">
        <div class="grid place-items-center">
            <div class="w-full max-w-7xl">
                <div class="grid gap-16 lg:grid-cols-2 lg:gap-24 mb-16 lg:mb-24">
                    <div class="pt-4">
                        <h2 class="mb-6 text-4xl font-semibold text-primary md:text-5xl">
                            About Events
                        </h2>
                        <p class="max-w-lg text-lg leading-relaxed text-text-muted">
                            CNIYEF actively organizes high-impact events that foster policy dialogue, entrepreneurship,
                            and leadership development within the wider CNI ecosystem.
                        </p>
                    </div>
                    <div class="space-y-12">
                        @if ($data['events']->isNotEmpty())
                        @php $firstEvent = $data['events']->shift(); @endphp
                        <div class="relative  flex justify-center lg:justify-end perspective-1000">
                            <div
                                class="absolute left-1/2 -translate-x-1/2 -top-2 w-8 h-9 rounded-sm bg-primary z-10">
                            </div>
                            <div class="w-full  max-w-2xl p-6 space-y-4">

                                <div class="overflow-hidden rounded-lg bg-light-primary mb-5 px-2 py-3 shadow-[0_-8px_20px_rgba(0,0,0,0.08),0_18px_30px_rgba(0,0,0,0.06)] "
                                    style="transform: rotate(-1deg) rotateY(-3deg) translateY(-3px);">
                                    <img src="{{ $firstEvent['image'] ?? '/images/Rectangle 1.png' }}"
                                        alt="{{ $firstEvent['summary'] }}"
                                        class="object-cover w-full h-64 rounded-xl">
                                </div>

                                <div class="ml-3">
                                    <p class="text-lg leading-relaxed text-text-muted">
                                        {{ $firstEvent['summary'] }}
                                    </p>

                                    <p class="mt-3 text-sm text-gray-500">
                                        {{ $firstEvent['start_date']->format('d M, Y') }}
                                    </p>
                                </div>

                            </div>
                        </div>
                        @endif
                    </div>
                </div>


                @if ($data['events']->count() > 0)
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6 md:gap-8">
                    @foreach ($data['events'] as $event)
                    <div class="relative group">
                        <div
                            class="absolute left-1/2 -translate-x-1/2 top-2 w-4 h-4 z-10 rounded-sm bg-primary">
                        </div>

                        <div class="p-5">

                            <div class="overflow-hidden rounded-lg bg-gray-200 mb-5 px-2 py-3 shadow-[0_-6px_20px_rgba(0,0,0,0.08),0_18px_30px_rgba(0,0,0,0.06)]"
                                style="transform: rotate(-1deg) rotateY(-3deg) translateY(-3px);">
                                <img src="{{ $event['image'] ?? '/images/Rectangle 1.png' }}"
                                    alt="{{ $event['summary'] }}" class="object-cover w-full h-64 rounded-xl">
                            </div>
                            <div class="ml-3">
                                <p class="text-base leading-relaxed text-text-muted line-clamp-3 mb-3">
                                    {{ $event['summary'] }}
                                </p>

                                <p class="text-sm text-gray-500">
                                    {{ $event['start_date']->format('d M, Y') }}
                                </p>
                            </div>
                        </div>
                    </div>
                    @endforeach


                </div>
                @endif

            </div>
        </div>
    </div>
</section>



<section class="py-12 bg-white sm:py-16">
    <div class="w-full px-4 sm:px-6 lg:px-8">

        <div class="grid place-items-center">
            <div class="w-full max-w-7xl">

                {{-- ================= Notices Header ================= --}}
                <div class="flex flex-wrap items-center justify-between gap-6 mb-10">

                    <div>
                        <div class="flex items-center gap-2 mb-2">


                            <h2 class="text-3xl font-semibold text-primary sm:text-4xl">
                                Notices
                            </h2>
                        </div>

                        <p class="text-base text-text-muted">
                            Stay updated with latest announcements
                        </p>
                    </div>

                    <a href="{{ route('news') }}"
                        class="inline-flex items-center px-5 py-2.5 text-sm font-medium text-white
                              transition rounded-md bg-primary hover:bg-primary/90">
                        View All
                    </a>
                </div>

                {{-- ================= Notices List (Flat / Exact) ================= --}}
                <div class="max-w-3xl space-y-4">

                    @foreach ($data['notices'] as $notice)
                    <div class="flex items-start gap-3 pb-4 border-b border-gray-200 last:border-b-0">

                        {{-- Icon --}}
                        <span class="mt-1 text-xl text-red-600 iconify"
                            data-icon="mdi:bell-outline"></span>

                        {{-- Content --}}
                        <div>
                            <a href="{{ route('news.show', $notice['id']) }}"
                                class="block font-medium text-gray-900 leading-snug hover:underline">
                                {{ $notice['title'] }}
                            </a>

                            <p class="mt-1 text-sm text-text-muted">
                                {{ $notice['notice_date']->format('F d, Y') }}
                            </p>
                        </div>

                    </div>
                    @endforeach

                </div>

            </div>
        </div>
    </div>
</section>
<section class="py-12 bg- sm:py-16">
    <div class="w-full px-4 sm:px-6 lg:px-8">
        <div class="grid place-items-center">
            <div class="w-full max-w-7xl">

                <div class="flex flex-wrap items-center justify-between gap-6 mb-10">

                    <div>
                        <div class="flex items-center gap-3 mb-2">
                            <span class="flex items-center justify-center text-white rounded-lg w-9 h-9 bg-primary">
                                <span class="text-lg iconify" data-icon="mdi:newspaper-variant-outline"></span>
                            </span>
                            <h2 class="text-3xl font-semibold text-primary sm:text-4xl">
                                News & Press Releases
                            </h2>
                        </div>
                        <p class="text-base text-text-muted">
                            Stay informed with the latest industry updates
                        </p>
                    </div>

                    <a href="{{ route('sister.news', $data['organization']->slug) }}"
                        class="inline-flex items-center gap-2 px-5 py-2.5 text-sm font-medium text-white transition rounded-md bg-primary hover:bg-primary/90">
                        View All
                        <span class="iconify" data-icon="mdi:arrow-right"></span>
                    </a>
                </div>

                <!-- Cards -->
                <div class="grid gap-6 sm:grid-cols-2 lg:grid-cols-3">

                    @foreach ($data['news'] as $news)
                    <div class="flex flex-col h-full overflow-hidden bg-white border rounded-lg">

                        <img src="{{ $news['image'] }}"
                            alt="News Image"
                            class="object-cover w-full h-48 transition-transform duration-300 ease-in-out transform hover:scale-105">


                        <div class="flex flex-col flex-1 p-6">

                            <h3 class="mb-3 text-lg font-semibold text-gray-800">
                                {{ $news['title'] }}
                            </h3>

                            <p class="mb-6 text-sm leading-relaxed text-text-muted line-clamp-2">
                                {!! strip_tags($news['summary']) !!}
                            </p>


                            <div class="mt-auto">
                                <a href="{{ route('news.show', $news['uuid']) }}"
                                    class="inline-flex items-center gap-2 text-sm font-medium text-primary transition hover:text-primary/80">
                                    Read More
                                    <span class="iconify" data-icon="mdi:arrow-right"></span>
                                </a>
                            </div>

                        </div>
                    </div>
                    @endforeach

                </div>

            </div>
        </div>
    </div>
</section>

<section class="py-12 bg-light-primary sm:py-16">
    <div class="w-full px-4 sm:px-6 lg:px-8">
        <div class="grid place-items-center">
            <div class="w-full max-w-7xl">

                <!-- Header -->
                <div class="flex flex-wrap items-center justify-between gap-6 mb-12">

                    <div>
                        <div class="flex items-center gap-3 mb-2">
                            <span class="px-3 py-1 text-xs font-semibold text-white rounded bg-primary">
                                FAQ
                            </span>
                            <h2 class="text-3xl font-semibold text-primary sm:text-4xl">
                                Frequently Asked Questions
                            </h2>
                        </div>

                        <p class="max-w-xl text-base text-text-muted">
                            Our FAQ section provides quick answers to common questions about CNI Koshi Province.
                        </p>
                    </div>
                    <a href="{{ route('sister.faqs', $data['organization']->slug) }}"
                        class="inline-flex items-center gap-2 px-5 py-2.5 text-sm font-medium text-white transition rounded-md bg-primary hover:bg-primary/90">
                        View All
                        <span class="iconify" data-icon="mdi:arrow-right"></span>
                    </a>


                </div>

                <!-- FAQ Items -->
                <div class="space-y-4">

                    @foreach ($data['faqs'] as $faq)
                    <details class="px-6 py-5 bg-white border rounded-lg group">
                        <summary class="flex items-center justify-between list-none cursor-pointer">
                            <span class="text-base font-medium text-gray-800">
                                {{ $faq->question }}
                            </span>
                            <span class="transition text-text-muted iconify group-open:rotate-180"
                                data-icon="mdi:chevron-down"></span>
                        </summary>

                        <p class="mt-4 text-base leading-relaxed text-text-muted">
                            {{ $faq->answer }}
                        </p>
                    </details>
                    @endforeach

                </div>

            </div>
        </div>
    </div>
</section>
@endsection

@push('scripts')
<script>
    // Register ScrollTrigger
    gsap.registerPlugin(ScrollTrigger);

    // Hero Slide Animation
    const heroSlides = document.querySelectorAll('.heroSwiper .swiper-slide');
    heroSlides.forEach(slide => {
        const title = slide.querySelector('h1');
        const subtitle = slide.querySelector('h2');
        if (title) gsap.from(title, {
            y: 50,
            opacity: 0,
            duration: 1,
            ease: "power3.out"
        });
        if (subtitle) gsap.from(subtitle, {
            y: 50,
            opacity: 0,
            duration: 1,
            delay: 0.2,
            ease: "power3.out"
        });
    });

    // Journey Section Animation
    const journeyItems = document.querySelectorAll('section.py-12.bg-white .flex.flex-col.lg\\:flex-row');

    journeyItems.forEach((item, index) => {
        // Alternate direction based on index: even from left, odd from right
        const fromX = index % 2 === 0 ? -100 : 100;

        gsap.from(item, {
            scrollTrigger: {
                trigger: item,
                start: "top 80%",
                toggleActions: "play none none none"
            },
            x: fromX,
            opacity: 0,
            duration: 1,
            ease: "power3.out"
        });
    });


    // Events Cards Animation
    const eventCards = document.querySelectorAll('section.bg-light-primary .grid > div');
    eventCards.forEach(card => {
        gsap.from(card, {
            scrollTrigger: {
                trigger: card,
                start: "top 85%",
                toggleActions: "play none none none"
            },
            y: 50,
            opacity: 0,
            duration: 0.8,
            ease: "power3.out",
            stagger: 0.2
        });
    });

    // Notices / News fade-in
    gsap.utils.toArray('section.py-12.bg-white .grid > div').forEach(elem => {
        gsap.from(elem, {
            scrollTrigger: {
                trigger: elem,
                start: "top 90%",
            },
            opacity: 0,
            y: 30,
            duration: 0.6,
            ease: "power3.out"
        });
    });

    // Hero Swiper initialization (unchanged)
    new Swiper('.heroSwiper', {
        loop: true,
        speed: 1200,
        autoplay: {
            delay: 4000,
            disableOnInteraction: false,
        },
        pagination: {
            el: '.swiper-pagination',
            dynamicBullets: true,
            clickable: true,
        },
        effect: 'slide',
    });
</script>
@endpush