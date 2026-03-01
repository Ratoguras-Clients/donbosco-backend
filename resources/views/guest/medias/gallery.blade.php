@extends('layouts.guest')

@section('content')
    <section class="w-full h-[60vh] sm:h-[75vh] lg:h-[70vh] max-h-[100svh] overflow-hidden">
        <div class="h-full bg-center bg-cover flex flex-col items-center justify-center sm:px-6 lg:px-8 w-full"
            style="background-image: linear-gradient(rgba(0,0,0,0.2), rgba(0,0,0,0.2)), url('{{ asset('images/gallery.png') }}');">

            <h1 class="mb-4 text-3xl font-bold sm:text-4xl md:text-6xl text-white">
                Our Gallery
            </h1>

            <p class="max-w-2xl text-sm sm:text-base md:text-lg text-white/90">
                Showcasing our journey and achievements through a visual story. </p>
        </div>
    </section>

    <section class="py-12 bg-light-primary sm:py-16">
        <div class="w-full px-4 sm:px-6 lg:px-8">
            <div class="mx-auto max-w-7xl">

                <div class="grid grid-cols-1 gap-4 lg:grid-cols-6">
                    @foreach ($data['collections'] as $collection)
                        <div
                            class="overflow-hidden bg-white shadow-md rounded-xl
                             {{ $loop->index < 3 ? 'lg:col-span-3 lg:row-span-2' : 'lg:col-span-1' }}">

                            <img src="{{ $collection['cover_image'] }}"
                                class="object-cover w-full h-full {{ $loop->index < 3 ? 'min-h-[16rem]' : 'h-32' }}"
                                alt="{{ $collection['description'] }}">
                        </div>
                    @endforeach
                </div>


                <!-- Medium Image -->
                {{-- <div class="overflow-hidden bg-white shadow-md rounded-xl lg:col-span-3">
                        <img src="{{ asset('images/gallary1.png') }}" class="object-cover w-full h-64" alt="">
                    </div> --}}

                <!-- Medium Image -->
                {{-- <div class="overflow-hidden bg-white shadow-md rounded-xl lg:col-span-3">
                        <img src="{{ asset('images/gallary2.png') }}" class="object-cover w-full h-64" alt="">
                    </div> --}}

                <!-- Small Images -->
                {{-- @foreach ($data['collections'] as $collection)
                        <div class="overflow-hidden bg-white shadow-md rounded-xl">
                            <img src="{{ $collection['cover_image'] }}" class="object-cover w-full h-56" alt="">
                        </div>
                    @endforeach --}}

                {{-- <div class="overflow-hidden bg-white shadow-md rounded-xl">
                        <img src="{{ asset('images/gallary4.png') }}" class="object-cover w-full h-56" alt="">
                    </div>

                    <div class="overflow-hidden bg-white shadow-md rounded-xl">
                        <img src="{{ asset('images/gallary2.png') }}" class="object-cover w-full h-56" alt="">
                    </div>

                    <div class="overflow-hidden bg-white shadow-md rounded-xl">
                        <img src="{{ asset('images/gallary1.png') }}" class="object-cover w-full h-56" alt="">
                    </div> --}}

            </div>

        </div>
        </div>
    </section>


    {{-- <section class="py-12 bg-light-primary sm:py-16">
        <div class="w-full px-4 sm:px-6 lg:px-8">

            <div class="grid place-items-center">
                <div class="w-full max-w-7xl">

                    <div class="grid grid-cols-1 gap-8 sm:grid-cols-2 lg:grid-cols-3">

                        <div class="overflow-hidden bg-white shadow-md rounded-xl object-cover w-full h-60" style="background-image: url('{{ asset('images/gallary1.png') }}');">
                            <img src="{{ asset('images/gallary1.png') }}" class="object-cover w-full h-60" alt="">
                        </div>

                        <div class="overflow-hidden bg-white shadow-md rounded-xl object-cover" style="background-image: url('{{ asset('images/gallary2.png') }}');">
                            <img src="{{ asset('images/gallary2.png') }}" class="object-cover w-full h-60" alt="">
                        </div>

                        <div class="overflow-hidden bg-white shadow-md rounded-xl">
                            <img src="{{ asset('images/gallary3.png') }}" class="object-cover w-full h-60" alt="">
                        </div>

                        <div class="overflow-hidden bg-white shadow-md rounded-xl sm:col-span-2 lg:col-span-3">
                            <img src="{{ asset('images/gallary5.png') }}" class="object-cover w-full h-96" alt="">
                        </div>

                        <div class="overflow-hidden bg-white shadow-md rounded-xl">
                            <img src="{{ asset('images/gallary2.png') }}" class="object-cover w-full h-60" alt="">
                        </div>

                        <div class="overflow-hidden bg-white shadow-md rounded-xl">
                            <img src="{{ asset('images/gallary1.png') }}" class="object-cover w-full h-60" alt="">
                        </div>

                        <div class="overflow-hidden bg-white shadow-md rounded-xl">
                            <img src="{{ asset('images/gallary4.png') }}" class="object-cover w-full h-60" alt="">
                        </div>

                    </div>

                </div>
            </div>

        </div>
    </section> --}}
@endsection
