@extends('layouts.guest')

@section('content')
<section class="w-full h-[60vh] sm:h-[75vh] lg:h-[70vh] max-h-[100svh] overflow-hidden">
    <div class="h-full bg-center bg-cover flex flex-col items-center justify-center sm:px-6 lg:px-8 w-full"
        style="background-image: linear-gradient(rgba(0,0,0,0.2), rgba(0,0,0,0.2)), url('{{ asset('images/about.png') }}');">

        <h1 class="mb-4 text-3xl font-bold sm:text-4xl md:text-6xl text-white">
            About Us
        </h1>

        <p class="max-w-2xl text-sm sm:text-base md:text-lg text-white/90">
            CNI Koshi Province: Advancing provincial industrial development. </p>
    </div>
</section>

<!-- OUR STORY -->
<section class="py-12 bg-gradient-to-br from-white via-[#F5F9FF] to-light-primary sm:py-16">
    <div class="w-full px-4 sm:px-6 lg:px-8">

        <div class="grid place-items-center">
            <div class="w-full max-w-7xl">

                <!-- Section Title -->
                <h1 class="mb-12 text-3xl font-semibold text-center text-primary sm:text-4xl">
                    Our Story
                </h1>

                <div class="grid items-center gap-10 md:grid-cols-2 md:gap-14">

                    <!-- LEFT IMAGE -->
                    <div class="overflow-hidden rounded-2xl shadow-md group">
                        <img
                            src="{{ asset('images/Frame1.png') }}"
                            alt="Our Story"
                            class="object-cover w-full h-64 sm:h-80 lg:h-[28rem] transition-transform duration-700 ease-in-out group-hover:scale-110">
                    </div>


                    <!-- RIGHT TEXT -->
                    <div class="p-6 rounded-2xl sm:p-8 ">
                        <h3 class="mb-4 text-3xl font-semibold text-primary sm:text-2xl">
                            Founded on Vision
                        </h3>

                        <p class="text-lg leading-relaxed text-text-muted">
                            CNI Koshi Province was established as a regional chapter of the Confederation of Nepalese
                            Industries to promote industrial growth and entrepreneurship in Koshi Province.Recognizing
                            the need for a dedicated platform to support local industries, a group of visionary business
                            leaders came together to create CNI Koshi.
                            <br> Since its inception, the organization has focused on fostering investment, facilitating
                            business collaboration, and advocating for policies that strengthen the industrial sector.
                        </p>
                    </div>

                </div>

                <!-- Mission & Vision -->
                <div class="p-8 mt-16 rounded-3xl bg-light-primary sm:p-10">

                    <h1 class="mb-12 text-3xl font-semibold text-center text-primary sm:text-4xl">
                        Our Mission & Vision
                    </h1>

                    <div class="grid gap-6 md:grid-cols-2 md:gap-8">

                        <!-- Mission -->
                        <div class="flex items-start gap-5 p-6 bg-white rounded-2xl shadow-sm sm:p-8">
                            <div
                                class="flex items-center justify-center p-3 text-primary rounded-full bg-light-primary">
                                <span class="text-2xl iconify" data-icon="mdi:rocket-launch-outline"></span>
                            </div>

                            <div>
                                <h4 class="mb-2 text-lg font-semibold text-primary sm:text-xl">
                                    Mission
                                </h4>
                                <p class="text-base leading-relaxed text-text-muted">
                                    To promote a dynamic, competitive, and innovation-driven
                                    industrial environment within Koshi Province.
                                </p>
                            </div>
                        </div>

                        <!-- Vision -->
                        <div class="flex items-start gap-5 p-6 bg-white rounded-2xl shadow-sm sm:p-8">
                            <div
                                class="flex items-center justify-center p-3 text-primary rounded-full bg-light-primary">
                                <span class="text-2xl iconify" data-icon="mdi:eye-outline"></span>
                            </div>

                            <div>
                                <h4 class="mb-2 text-lg font-semibold text-primary sm:text-xl">
                                    Vision
                                </h4>
                                <p class="text-base leading-relaxed text-text-muted">
                                    A prosperous Koshi Province with sustainable industrial
                                    development and empowered businesses.
                                </p>
                            </div>
                        </div>

                    </div>

                </div>

            </div>
        </div>

    </div>
</section>


<!-- OUR JOURNEY -->
<section class="py-12 bg-white sm:py-16">
    <div class="w-full px-4 sm:px-6 lg:px-8">

        <div class="grid place-items-center">
            <div class="w-full max-w-7xl">

                <h2 class="mb-20 text-3xl font-semibold text-center text-primary sm:text-4xl">
                    Our Journey
                </h2>

                <div class="relative w-full  mx-auto space-y-12">
                    @foreach($data['journeys'] as $index => $journey)
                    @php
                    $isLeft = $index % 2 === 0;
                    @endphp

                    <div class="relative flex flex-col lg:flex-row lg:items-start">

                        <!-- Spacer for alternating sides -->
                        <div class="{{ $isLeft ? 'hidden lg:block lg:w-1/2' : '' }}"></div>

                        <!-- Dot & Line (always center) -->
                        <div class="absolute top-0 left-0 flex flex-col items-center h-full lg:left-1/2 lg:-translate-x-1/2">
                            <span class="w-4 h-4 rounded-full bg-primary"></span>
                            <div class="flex-1 w-1 mt-1 bg-blue-400"></div>
                        </div>

                        <!-- Content -->
                        <div class="w-full pt-6 lg:w-1/2 lg:pt-0
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

<section class="bg-gray-100 py-12">
    <div class="max-w-7xl mx-auto px-6">

        <!-- Section Heading -->
        <div class="text-center mb-12">
            <h2 class="text-3xl sm:text-4xl font-extrabold text-primary">Our Services</h2>
        </div>
        @php
        $cardColors = [
        'from-indigo-500 to-blue-600',
        'from-amber-500 to-orange-500',
        'from-emerald-500 to-teal-500',
        ];
        @endphp


        <!-- Cards Grid -->
        <div class="grid gap-8 sm:grid-cols-2 lg:grid-cols-3 feature-cards">
            @foreach($data['services'] as $service)

        @php
            $color = $cardColors[$loop->index % count($cardColors)];
        @endphp

            <!-- Card 1 -->
            <div class="feature-card relative overflow-hidden rounded-2xl bg-white shadow-lg p-7 pt-9">
                <div class="absolute inset-x-0 top-0 h-1.5 bg-gradient-to-r {{ $color }}"></div>

                <span class="inline-block mb-4 text-3xl text-primary iconify"
                    data-icon="{{ $service['icon'] }}"></span>
                <h3 class="mb-3 text-2xl font-bold text-gray-800">{{$service['title']}}</h3>
                <p class="text-gray-600 leading-relaxed">
                    {{$service['description']}}
                </p>
            </div>
            @endforeach

            

           

           

        </div>
    </div>
</section>







<section class="py-12 bg-white sm:py-16">
    <div class="w-full px-4 sm:px-6 lg:px-8">

        <div class="grid place-items-center">
            <div class="w-full max-w-7xl">

                <div class="grid items-center gap-12 lg:grid-cols-2 ">

                    <!-- LEFT IMAGE -->
                    <div class="relative flex justify-center w-full">
                        <div class="absolute w-5 h-5 -translate-x-1/2 rounded bg-primary -top-3 left-1/2">
                        </div>

                        <div class="p-2 rounded-2xl bg-primary/20 shadow-lg w-full">
                            <img src="{{ asset('images/Rectangle 2.png') }}" alt="Events"
                                class="object-cover w-full rounded-sm">
                        </div>
                    </div>

                    <!-- RIGHT CONTENT -->
                    <div>
                        <p class="mb-2 text-md text-text-muted">
                            Know
                        </p>

                        <h2 class="mb-6 text-5xl font-bold text-gray-900">
                            About <span class="text-primary">EVENTS</span>
                        </h2>

                        <p class="mb-8 text-base leading-relaxed text-text-muted">
                            CNI Koshi organizes impactful events such as the CNI Koshi Expo 2023,
                            the 1st Koshi IT Summit, and the Koshi Province Investment Summit to
                            boost entrepreneurship, innovation, and provincial industrial growth.
                            These events bring together industry leaders, innovators, investors,
                            and government stakeholders.
                        </p>

                        <a href="events"
                            class="btn-animate inline-flex items-center gap-2 px-6 py-3 text-sm font-medium text-white transition rounded-lg bg-primary hover:bg-primary/90">
                            Learn More
                            <span class="w-4 h-4 iconify" data-icon="heroicons:chevron-right"></span>
                        </a>
                    </div>

                </div>

            </div>
        </div>

    </div>
</section>
@endsection

@push('scripts')
<!-- GSAP -->



<script>
    gsap.registerPlugin(ScrollTrigger)

    /* HERO */
    const tl = gsap.timeline()

    tl.from("section:first-child h1", {
            y: 60,
            opacity: 0,
            duration: 1,
            ease: "power3.out"
        })
        .from("section:first-child p", {
            y: 60,
            opacity: 0,
            duration: 1,
            ease: "power3.out"
        }, "+=0.2") // starts 0.2s after h1

    /* SECTION TITLES */
    gsap.utils.toArray("h1, h2").forEach(title => {
        gsap.from(title, {
            scrollTrigger: {
                trigger: title,
                start: "top 85%",
            },
            y: 40,
            opacity: 0,
            duration: 0.8,
            ease: "power3.out"
        })
    })

    /* IMAGES */
    // gsap.utils.toArray("img").forEach(img => {
    //     gsap.from(img, {
    //         scrollTrigger: {
    //             trigger: img,
    //             start: "top 85%",
    //         },
    //         scale: 0.9,
    //         opacity: 0,
    //         duration: 1,
    //         ease: "power3.out"
    //     })
    // })

    /* TEXT */
    gsap.utils.toArray("p").forEach(p => {
        gsap.from(p, {
            scrollTrigger: {
                trigger: p,
                start: "top 90%",
            },
            y: 20,
            opacity: 0,
            duration: 0.6,
            ease: "power2.out"
        })
    })

    /* MISSION / VISION CARDS */
    gsap.from(".bg-white.rounded-2xl", {
        scrollTrigger: {
            trigger: ".bg-white.rounded-2xl",
            start: "top 80%",
        },
        y: 40,
        opacity: 0,
        stagger: 0.2,
        duration: 0.8,
        ease: "power3.out"
    })

    /* JOURNEY TIMELINE */
    gsap.utils.toArray(".relative.flex").forEach((item, i) => {
        gsap.from(item, {
            scrollTrigger: {
                trigger: item,
                start: "top 85%",
            },
            x: i % 2 === 0 ? -80 : 80,
            opacity: 0,
            duration: 0.8,
            ease: "power3.out"
        })
    })

    /* BUTTON HOVER */
    document.querySelectorAll(".btn-animate").forEach(btn => {
        btn.addEventListener("mouseenter", () => {
            gsap.to(btn, {
                scale: 1.05,
                duration: 0.2,
                transformOrigin: "center"
            })
        })

        btn.addEventListener("mouseleave", () => {
            gsap.to(btn, {
                scale: 1,
                duration: 0.2
            })
        })
    })
</script>
@endpush