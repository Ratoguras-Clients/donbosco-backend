@extends('layouts.guest')

@section('content')
<section class="w-full h-[60vh] sm:h-[75vh] lg:h-[70vh] max-h-[100svh] overflow-hidden">
    <div class="h-full bg-center bg-cover flex flex-col items-center justify-center sm:px-6 lg:px-8 w-full"
        style="background-image: linear-gradient(rgba(0,0,0,0.2), rgba(0,0,0,0.2)), url('{{ asset('images/blogs.png') }}');">

        <h1 class="mb-4 text-3xl font-bold sm:text-4xl md:text-6xl text-white">
            Blogs
        </h1>

        <p class="max-w-2xl text-sm sm:text-base md:text-lg text-white/90">
            Sharing insights, updates, and perspectives on industrial growth in Koshi Province.</p>
    </div>
</section>

<!-- BLOG LIST -->
<section class="py-12 bg-light-primary sm:py-16">
    <div class="w-full px-4 sm:px-6 lg:px-8">

        <div class="grid place-items-center">
            <div class="w-full max-w-7xl">

                <!-- Filter -->
                <div class="w-full mb-8">
                    <label class="block mb-2 text-sm font-medium text-primary">
                        Filter By Category
                    </label>

                    <select class="w-full rounded-md border-gray-300 text-sm focus:border-primary focus:ring-primary">
                        <option>All</option>
                        <option>Industry</option>
                        <option>Technology</option>
                    </select>
                </div>

                <!-- Featured Card -->
               @foreach($data['blogs']->slice(0, 1) as $blog)

                <div class="grid gap-6 p-5 mb-10 bg-white shadow-lg rounded-xl md:grid-cols-3">
                    <img src="{{ $blog['image'] ?? asset('images/news2.png') }}" class="object-cover w-full h-56 rounded-lg"
                        alt="$blog['title']">

                    <div class="md:col-span-2">
                        <h2 class="mb-3 text-2xl font-semibold text-primary">
                            {{$blog['title']}}
                        </h2>

                        <p class="mb-5 text-base leading-relaxed text-text-muted">
                            {{$blog['description']}}
                        </p>

                        <div class="flex flex-wrap items-center gap-6 text-sm text-text-muted">
                            <span class="flex items-center gap-2">
                                <span class="iconify" data-icon="mdi:account-outline"></span>
                                {{$blog['name']}}
                            </span>

                            <span class="flex items-center gap-2">
                                <span class="iconify" data-icon="mdi:calendar-outline"></span>
                                {{$blog['start_date']->format('d M Y')}}
                            </span>
                        </div>
                    </div>
                </div>
                @endforeach

                <!-- Grid Cards -->
                <div class="grid gap-8 md:grid-cols-3">
                    @foreach($data['blogs']->slice(1, 3) as $blog)

                    <div class="p-4 bg-white shadow-md rounded-xl">
                        <img src="{{ $blog['image'] ?? asset('images/news2.png')  }}" class="object-cover w-full h-44 mb-4 rounded-lg"
                            alt="$blog['title']">

                        <h3 class="mb-2 text-lg font-semibold text-primary">
                            {{ $blog['title'] }}
                        </h3>

                        <p class="mb-4 text-sm leading-relaxed text-text-muted">
                            {{ Str::limit($blog['description'], 100) }}
                        </p>

                        <div class="flex items-center justify-between text-sm text-text-muted">
                            <span class="flex items-center gap-2">
                                <span class="iconify" data-icon="mdi:account-outline"></span>
                                {{$blog['name']}}
                            </span>

                            <span class="flex items-center gap-2">
                                <span class="iconify" data-icon="mdi:calendar-outline"></span>
                                {{$blog['start_date']->format('d M Y')}}
                            </span>
                        </div>
                    </div>
                    @endforeach
                </div>

                <!-- Two Column Cards -->
                <div class="grid gap-8 pt-10 md:grid-cols-2">

                    @foreach($data['blogs']->slice(4) as $blog)

                    <div class="overflow-hidden bg-white shadow-lg rounded-xl">
                        <img src="{{ $blog['image'] ?? asset('images/news2.png') }}" class="object-cover w-full h-56" alt="">

                        <div class="p-5">
                            <h3 class="mb-3 text-lg font-semibold text-primary">
                                {{ $blog['title'] }}
                            </h3>

                            <p class="mb-5 text-sm leading-relaxed text-text-muted">
                                {{ Str::limit($blog['description'], 100) }}
                            </p>

                            <div class="flex items-center justify-between text-sm text-text-muted">
                                <span class="flex items-center gap-2">
                                    <span class="iconify" data-icon="mdi:account-outline"></span>
                                    {{$blog['name']}}
                                </span>

                                <span class="flex items-center gap-2">
                                    <span class="iconify" data-icon="mdi:calendar-outline"></span>
                                    {{$blog['start_date']->format('d M Y')}}
                                </span>
                            </div>
                        </div>
                    </div>
                    @endforeach

                </div>

                <!-- Pagination -->
                <div class="flex justify-center mt-12">
                    <div class="flex justify-center mt-12">
                        
                    </div>

                </div>

            </div>
        </div>

    </div>
</section>
@endsection