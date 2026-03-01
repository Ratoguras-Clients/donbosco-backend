@extends('layouts.guest')

@section('content')
    @if (!empty($data['homecarousel']) && count($data['homecarousel']) > 0)
        <section class="w-full h-[60vh] sm:h-[75vh] lg:h-[70vh] max-h-[100svh] overflow-hidden relative">
            <div class="h-full swiper heroSwiper">
                <div class="h-full swiper-wrapper">

                    @foreach ($data['homecarousel'] as $slide)
                        <div class="h-full bg-center bg-cover swiper-slide transition-transform duration-700"
                            style="
                        background-image:
                        linear-gradient(rgba(0,0,0,0.5), rgba(0,0,0,0.5)),
                        url('{{ $slide['image'] ?? asset('images/default-banner.png') }}');
                    ">

                            <div class="flex items-center h-full pt-10 sm:pt-0">
                                <div class="w-full px-4 mx-auto text-white sm:px-6 lg:px-8 max-w-7xl">

                                    <h1 class="mb-4 text-3xl font-bold sm:text-4xl md:text-6xl drop-shadow-lg">
                                        {{ $slide['title'] }}
                                    </h1>

                                    @if (!empty($slide['subtitle']))
                                        <h2 class="mb-6 text-lg font-semibold sm:text-2xl md:text-3xl drop-shadow-md">
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

    @if (!empty($data['messages']) && count($data['messages']) > 0)
        <section class="py-16 lg:py-20 bg-white">
            <div class="max-w-7xl px-4 mx-auto sm:px-6 lg:px-8">

                <div class="grid gap-12 md:grid-cols-2 items-center">
                    @foreach ($data['messages'] as $message)
                        <!-- Image -->
                        <div class="w-full">
                            <img src="{{ asset('images/person 1.png') }}" alt="President"
                                class="w-full h-auto rounded-lg object-cover shadow-lg transition-transform hover:scale-105">
                        </div>

                        <!-- Text Content -->
                        <div class="space-y-6">
                            <h2 class="text-3xl font-bold text-primary sm:text-4xl lg:text-5xl">
                                {{ $message['title'] }}
                            </h2>

                            <p class="text-gray-700 leading-relaxed text-base lg:text-lg">
                                {{ $message['content'] }}
                            </p>

                            <!-- Footer -->
                            <div class="flex flex-wrap items-center justify-between gap-4 pt-4 border-t border-gray-200">
                                <a href="{{ route('message') }}"
                                    class="inline-flex items-center gap-2 text-sm font-medium text-primary transition hover:text-primary/80 hover">
                                    View Past Messages <span aria-hidden="true">→</span>
                                </a>

                                <span class="text-lg font-medium text-primary whitespace-nowrap">
                                    {{ $message['staff_name'] }}
                                </span>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </section>
    @endif


    <section class="py-10 bg-light-primary sm:py-14">
        <div class="w-full px-4 sm:px-6 lg:px-8">

            <div class="grid place-items-center">
                <div class="w-full max-w-7xl">

                    {{-- Top Section --}}
                    <div class="grid items-center gap-16 lg:grid-cols-2">

                        {{-- Left Content --}}
                        <div>
                            <h2 class="mb-6 text-3xl font-semibold text-gray-900 sm:text-4xl">
                                OUR MISSION
                            </h2>

                            <p class="max-w-lg text-lg leading-relaxed text-primary">
                                As CNI Koshi Province, our mission is to promote a dynamic,
                                competitive, and innovation-driven industrial environment
                                within Koshi Province.
                            </p>
                        </div>

                        {{-- Image Collage --}}
                        <div class="grid grid-cols-2 gap-4">

                            <div class="flex flex-col gap-4">

                                <!-- Image 1 -->
                                <div class="overflow-hidden group rounded-tr-[20px] rounded-bl-[20px]">
                                    <img src="{{ asset('images/home3.png') }}"
                                        class="object-cover w-full h-auto transition-transform duration-700 ease-in-out group-hover:scale-110"
                                        alt="">
                                </div>

                                <!-- Image 2 -->
                                <div class="overflow-hidden group rounded-br-[20px] rounded-tl-[20px]">
                                    <img src="{{ asset('images/home4.png') }}"
                                        class="object-cover w-full h-auto transition-transform duration-700 ease-in-out group-hover:scale-110"
                                        alt="">
                                </div>

                            </div>

                            <!-- Image 3 -->
                            <div class="overflow-hidden group rounded-tl-[20px] rounded-br-[20px]">
                                <img src="{{ asset('images/home5.png') }}"
                                    class="object-cover w-full h-full transition-transform duration-700 ease-in-out group-hover:scale-110"
                                    alt="">
                            </div>

                        </div>


                    </div>

                    {{-- Bottom Values --}}
                    <div class="grid gap-8 mt-20 md:grid-cols-3">
                        @foreach ($data['missions'] as $mission)
                            {{-- Card --}}
                            <div class="px-8 py-10 text-center bg-white border rounded-lg">
                                <div class="mb-4 text-3xl text-primary">
                                    <span class="inline-block mb-4 text-3xl text-primary iconify"
                                        data-icon="{{ $mission->icon }}"></span>
                                </div>

                                <h3 class="mb-3 text-lg font-semibold tracking-widest text-primary">
                                    {{ $mission->title }}
                                </h3>


                                <p class="text-sm leading-relaxed text-text-muted">
                                    {{ $mission->description }}
                                </p>
                            </div>
                        @endforeach
                    </div>

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

                        <a href="{{ route('notices') }}"
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
                                <span class="mt-1 text-xl text-red-600 iconify" data-icon="mdi:bell-outline"></span>

                                {{-- Content --}}
                                <div>
                                    <a href="{{ route('notices.show', $notice['uuid']) }}"
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


    <section class="py-12 bg-light-primary sm:py-16">
        <div class="w-full px-4 sm:px-6 lg:px-8">
            <div class="grid place-items-center">
                <div class="w-full max-w-7xl">

                    <!-- Header -->
                    <div class="flex flex-wrap items-center justify-between gap-6 mb-12">
                        <div>
                            <div class="flex items-center gap-2 mb-2">
                                <span class="text-xl text-primary iconify" data-icon="mdi:shield-outline"></span>
                                <h2 class="text-3xl font-semibold text-primary sm:text-4xl">
                                    Our Sister Organizations
                                </h2>
                            </div>
                            <p class="text-base text-text-muted">
                                Shaping brighter futures together
                            </p>
                        </div>

                        <a href="{{ route('organization') }}"
                            class="inline-flex items-center px-5 py-2.5 text-sm font-medium text-white transition rounded-md bg-primary hover:bg-primary/90">
                            View All
                        </a>
                    </div>

                    <!-- Logos -->
                    <div class="grid grid-cols-2 gap-6 sm:grid-cols-3 md:grid-cols-5">
                        @foreach ($data['sisterOrganizations'] as $sisterOrganization)
                            <a href="{{ route('sister', $sisterOrganization->slug) }}"
                                class="group flex items-center justify-center p-6 bg-white border rounded-lg transition-transform duration-300 ease-out hover:scale-105 hover:shadow-2xl">

                                <img src="{{ $sisterOrganization->media->filepath ?? 'No image' }}"
                                    alt="Sister Organization logo"
                                    class="object-contain h-24 transition-transform duration-300 ease-out group-hover:scale-110">

                            </a>
                        @endforeach
                    </div>

                </div>
            </div>
        </div>
    </section>

    <section class="py-12 bg-white sm:py-16">
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

                        <a href="{{ route('news') }}"
                            class="inline-flex items-center gap-2 px-5 py-2.5 text-sm font-medium text-white transition rounded-md bg-primary hover:bg-primary/90">
                            View All
                            <span class="iconify" data-icon="mdi:arrow-right"></span>
                        </a>
                    </div>

                    <!-- Cards -->
                    <div class="owl-carousel news-carousel ">

                        @foreach ($data['news'] as $news)
                            <div
                                class="flex flex-col h-full mb-4 bg-white border rounded-lg transition-all duration-300 ease-in-out
                  hover:shadow-lg hover:-translate-y-1 ">

                                <div class="overflow-hidden rounded-lg group">
                                    <img src="{{ $news['image'] }}" alt="News Image"
                                        class="object-cover w-full h-48 transition-transform duration-700 ease-in-out group-hover:scale-110">
                                </div>


                                <div class="flex flex-col flex-1 p-6">

                                    <h3 class="mb-3 text-lg font-semibold text-gray-800">
                                        {{ $news['title'] }}
                                    </h3>

                                    <div class="mb-6 text-sm leading-relaxed text-text-muted ql-editor line-clamp-2">
                                        {!! $news['summary'] !!}
                                    </div>


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

                        <a href="{{ route('faq.show') }}"
                            class="inline-flex items-center gap-2 px-5 py-2.5 text-sm font-medium text-white transition rounded-md bg-primary hover:bg-primary/90">
                            View All
                            <span class="iconify" data-icon="mdi:arrow-right"></span>
                        </a>
                    </div>

                    <!-- FAQ Items -->
                    <div class="space-y-4">

                        @foreach ($data['faqs'] as $faq)
                            <details class="faq-item px-6 py-5 bg-white border rounded-lg group">
                                <summary class="flex items-center justify-between list-none cursor-pointer">
                                    <span class="text-base font-medium text-gray-800">
                                        {{ $faq->question }}
                                    </span>
                                    <span class="transition text-text-muted iconify group-open:rotate-180"
                                        data-icon="mdi:chevron-down"></span>
                                </summary>

                                <p class="mt-4 text-base leading-relaxed text-text-muted ">
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
        const slideCount = {{ count($data['homecarousel']) }};

        new Swiper('.heroSwiper', {
            loop: slideCount > 1,
            speed: 1200,
            autoplay: slideCount > 1 ? {
                delay: 4000,
                disableOnInteraction: false,
            } : false,
            pagination: {
                el: '.swiper-pagination',
                dynamicBullets: true,
                clickable: true,
            },
            effect: 'slide',
        });

        document.addEventListener('DOMContentLoaded', () => {
            gsap.utils.toArray('.faq-item').forEach((faq, i) => {
                gsap.from(faq, {
                    scrollTrigger: {
                        trigger: faq,
                        start: "top 90%",
                        toggleActions: "play none none none"
                    },
                    opacity: 0,
                    y: 30,
                    duration: 0.6,
                    delay: i * 0.1
                });
            });
        });

        gsap.registerPlugin(ScrollTrigger);

        /* ========== Messages Section ========== */
        document.querySelectorAll('section.bg-white > .max-w-7xl').forEach((section) => {
            const messages = section.querySelectorAll('h2, p, .flex');
            gsap.from(messages, {
                scrollTrigger: {
                    trigger: section,
                    start: 'top 80%',
                    toggleActions: 'play none none none',
                },
                y: 60,
                opacity: 0,
                duration: 1,
                stagger: 0.2,
                ease: 'power3.out',
            });
        });

        /* ========== OUR MISSION Section ========== */
        const missionSection = document.querySelector('section.bg-light-primary');
        if (missionSection) {
            // Left text
            gsap.from(missionSection.querySelectorAll('h2, p'), {
                scrollTrigger: {
                    trigger: missionSection,
                    start: 'top 80%',
                    toggleActions: 'play none none none',
                },
                y: 60,
                opacity: 0,
                duration: 1,
                stagger: 0.2,
                ease: 'power3.out',
            });

            // Image collage
            gsap.from(missionSection.querySelectorAll('.grid.grid-cols-2 img'), {
                scrollTrigger: {
                    trigger: missionSection,
                    start: 'top 80%',
                    toggleActions: 'play none none none',
                },
                y: 40,
                opacity: 0,
                duration: 1,
                stagger: 0.15,
                ease: 'power3.out',
            });

            // Bottom cards
            gsap.from(missionSection.querySelectorAll('.bg-white.border.rounded-lg'), {
                scrollTrigger: {
                    trigger: missionSection,
                    start: 'top 80%',
                    toggleActions: 'play none none none',
                },
                y: 60,
                opacity: 0,
                duration: 1,
                stagger: 0.2,
                ease: 'power3.out',
            });
        }
        $(document).ready(function() {
            $('.news-carousel').owlCarousel({
                loop: {{ count($data['news']) > 3 ? 'true' : 'false' }},
                margin: 24,
                nav: true,
                dots: false,
                autoplay: true,
                autoplayTimeout: 5000,
                autoplayHoverPause: true,
                smartSpeed: 700,
                navText: [
                    '<span class="owl-nav-circle"><span class="iconify" data-icon="mdi:chevron-left"></span></span>',
                    '<span class="owl-nav-circle"><span class="iconify" data-icon="mdi:chevron-right"></span></span>'
                ],


                responsive: {
                    0: {
                        items: 1
                    },
                    640: {
                        items: 2
                    },
                    1024: {
                        items: 3
                    }
                }
            });
        });
    </script>
@endpush
@push('styles')
    <style>
        /* Owl Nav Position */
        .news-carousel .owl-nav {
            position: absolute;
            top: 42%;
            left: 0;
            right: 0;
            transform: translateY(-50%);
            pointer-events: none;
        }

        .news-carousel .owl-nav button {
            position: absolute;
            pointer-events: all;
        }

        /* Left arrow */
        .news-carousel .owl-nav .owl-prev {
            left: -20px;
        }

        /* Right arrow */
        .news-carousel .owl-nav .owl-next {
            right: -20px;
        }

        /* Circle style */
        .owl-nav-circle {
            width: 48px;
            height: 48px;
            background: #ffffff;
            border-radius: 9999px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: #1e40af;
            /* primary-ish */
            font-size: 26px;
            box-shadow: 0 0px 15px rgba(0, 0, 0, 0.10);
            transition: all 0.3s ease;
        }

        .owl-nav-circle:hover {
            background: #1e40af;
            color: #ffffff;
            transform: scale(1.08);
        }

        @media (max-width: 640px) {
            .news-carousel .owl-nav {
                display: none;
            }
        }
    </style>
@endpush
