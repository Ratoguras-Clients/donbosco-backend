@extends('layouts.guest')
@section('content')
 @include('components.breadcrumb', [
        'breadcrumbs' => [['title' => 'Messages', 'url' => null]],
    ])

<section class=" relative py-16 bg-white lg:py-20">

    <div class="absolute top-4  right-4 lg:right-60">
        <a href="{{ route('home') }}"
            class="inline-flex items-center gap-2 px-3 py-2 text-sm font-medium text-primary bg-white rounded-lg shadow hover:bg-primary/10 transition">
            Back to home
            <span aria-hidden="true">→</span>
        </a>
    </div>

    <div class="max-w-7xl px-4 mx-auto sm:px-6 lg:px-8">

        <div class="grid items-center gap-12 md:grid-cols-2">
            @foreach ($data['messages'] as $message)
            <img src="{{ asset('images/person 1.png') }}" alt="President"
                class="w-full sm:max-w-md lg:max-w-full h-auto rounded-lg object-cover">

            <div>
                <h2 class="mb-4 text-3xl font-semibold text-primary sm:text-4xl lg:text-5xl">
                    {{$message['title']}}
                </h2>



                <p class="mb-6 text-sm leading-relaxed text-gray-600 sm:text-base lg:text-lg">
                    {{$message['content']}}
                </p>

                <!-- Footer -->
                <div class="flex flex-wrap items-center justify-between gap-4 pt-4 border-t">


                    <span class="text-lg font-medium text-primary whitespace-nowrap">
                        {{ $message['staff_name'] }}
                    </span>
                </div>

            </div>
            @endforeach


        </div>
        <div>

        </div>
    </div>

</section>
@endsection