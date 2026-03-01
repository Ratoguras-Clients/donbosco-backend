@extends('layouts.guest')

@section('content')
<section class="w-full h-[60vh] sm:h-[75vh] lg:h-[70vh] max-h-[100svh] overflow-hidden">
    <div class="h-full bg-center bg-cover flex flex-col items-center justify-center sm:px-6 lg:px-8 w-full"
        style="background-image: linear-gradient(rgba(0,0,0,0.2), rgba(0,0,0,0.2)), url('{{ asset('images/blogs.png') }}');">

        <h1 class="mb-4 text-3xl font-bold sm:text-4xl md:text-6xl text-white">
            Sister Organizations
        </h1>

        <p class="max-w-2xl text-sm sm:text-base md:text-lg text-white/90">
            United with our sister organizations to advance sustainable progress and industry excellence.
        </p>
    </div>
</section>

<section class="bg-white py-16">
    <div class="mx-auto max-w-7xl px-6 grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-12">

        @foreach($data['sisterOrganizations'] as $org)
        <!-- Sister Organization Card -->
        <div class="bg-white rounded-3xl shadow-xl overflow-hidden hover:shadow-2xl transition-shadow duration-300 flex flex-col">

            <!-- Image -->
            @if($org->logo)
            <img src="{{ $org->media->filepath }}"
                alt="{{ $org->name }}"
                class="w-full h-auto sm:h-72 object-cover rounded-t-3xl transition-transform duration-500 hover:scale-105">

            @else
            <div class="w-full h-auto sm:h-72 flex items-center justify-center bg-gray-300 text-gray-500 font-semibold rounded-t-3xl">
                No Image Available
            </div>
            @endif

            <!-- Card Content -->
            <div class="p-6 flex flex-col flex-1 ">
                <!-- Organization Name -->
                <h3 class="text-2xl sm:text-3xl font-extrabold mb-3 bg-clip-text text-transparent bg-blue-700">
                    {{ $org->name }}
                </h3>

                <!-- Description & Key Info -->
                <div class="flex-1">
                    <!-- Description -->
                    <p class="text-gray-700 mb-4">
                        {{ Str::limit($org->description, 200, '...') }}
                    </p>

                    <!-- Key Info -->
                    <ul class="flex flex-wrap gap-2 text-gray-500 text-sm">
                        @if($org->short_name)
                        <li class="px-2 py-1 bg-gray-100 rounded-full">{{ $org->short_name }}</li>
                        @endif
                        @if($org->established_date)
                        <li class="px-2 py-1 bg-gray-100 rounded-full"><strong>Established:</strong> {{ $org->established_date }}</li>
                        @endif
                        @if($org->mission)
                        <li class="px-2 py-1 bg-gray-100 rounded-full"><strong>Mission:</strong> {{ $org->mission }}</li>
                        @endif
                    </ul>
                </div>

                <!-- Learn More Button -->
                <div class="mt-4">
                    <a href="{{ route('sister', $org->slug) }}"
                        class="inline-flex items-center justify-center gap-2 px-4 py-2 bg-primary text-white rounded-lg font-medium hover:bg-primary/90 transition">
                        Learn More
                        <span class="iconify h-4 w-4" data-icon="heroicons:chevron-right"></span>
                    </a>
                </div>
            </div>
        </div>

        @endforeach

    </div>
</section>


@endsection