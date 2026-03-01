{{-- resources/views/guest/medias/events.blade.php --}}
@extends('layouts.guest')

@section('content')
<!-- Hero Section - unchanged -->
<section class="w-full h-[60vh] sm:h-[75vh] lg:h-[70vh] max-h-[100svh] overflow-hidden">
    <div class="h-full bg-center bg-cover flex flex-col items-center justify-center sm:px-6 lg:px-8"
        style="background-image: linear-gradient(rgba(0,0,0,0.2), rgba(0,0,0,0.2)), url('{{ asset('images/events.png') }}');">
        <h1 class="mb-4 text-3xl font-bold sm:text-4xl md:text-6xl text-white">
            Our Events
        </h1>
        <p class="max-w-2xl text-sm sm:text-base md:text-lg text-white/90">
            Highlighting key events that bring industry leaders and ideas together.
        </p>
    </div>
</section>

<section class="py-12 bg-white sm:py-16">
    <div class="w-full px-4 sm:px-6 lg:px-8">
        <div class="grid place-items-center">
            <div class="w-full max-w-7xl">
                <div class="grid gap-16 lg:grid-cols-2 lg:gap-24 mb-16 lg:mb-24">
                    <div class="pt-4">
                        <h2 class="mb-6 text-4xl font-semibold text-primary md:text-5xl">
                            About Events
                        </h2>
                        <p class="max-w-lg text-lg leading-relaxed text-text-muted">
                            CNI Koshi (and CNI more broadly) hosts a variety of
                            high-impact events aimed at driving policy dialogue,
                            entrepreneurship, and leadership development.
                        </p>
                    </div>
                    <div class="space-y-12">
                        @if ($events->isNotEmpty())
                        @php $firstEvent = $events->shift(); @endphp
                        <div class="relative  flex justify-center lg:justify-end perspective-1000">
                            <div
                                class="absolute left-1/2 -translate-x-1/2 -top-2 w-8 h-9 rounded-sm bg-primary z-10">
                            </div>
                            <div class="w-full  max-w-2xl p-6 space-y-4">

                                <div class="overflow-hidden rounded-lg bg-light-primary mb-5 px-2 py-3 shadow-[0_-8px_20px_rgba(0,0,0,0.08),0_18px_30px_rgba(0,0,0,0.06)] "
                                    style="transform: rotate(-1deg) rotateY(-3deg) translateY(-3px);">
                                    <img src="{{ $firstEvent['image'] ?? 'No image' }}"
                                        alt="{{ $firstEvent['summary'] }}"
                                        class="object-cover w-full h-64 rounded-xl">
                                </div>

                                <div class="ml-3">
                                    <p class="text-lg leading-relaxed text-text-muted">
                                        {{ $firstEvent['summary'] }}
                                    </p>
                                    <p class="text-lg leading-relaxed text-text-muted flex items-center gap-2">
                                        <span class="iconify" data-icon="tabler:map-pin" data-width="20"></span>
                                        {{ $firstEvent['location'] }}
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


                @if ($events->count() > 0)
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6 md:gap-8">
                    @foreach ($events as $event)
                    <div class="relative group">
                        <div
                            class="absolute left-1/2 -translate-x-1/2 top-2 w-4 h-4 z-10 rounded-sm bg-primary">
                        </div>

                        <div class="p-5">

                            <div class="overflow-hidden rounded-lg bg-gray-200 mb-5 px-2 py-3 shadow-[0_-6px_20px_rgba(0,0,0,0.08),0_18px_30px_rgba(0,0,0,0.06)]"
                                style="transform: rotate(-1deg) rotateY(-3deg) translateY(-3px);">
                                <img src="{{ $event['image'] ?? 'no image' }}"
                                    alt="{{ $event['summary'] }}" class="object-cover w-full h-64 rounded-xl">
                            </div>
                            <div class="ml-3 flex flex-col">
                                <!-- Event Summary -->
                                <p class="text-base leading-relaxed text-text-muted line-clamp-3 mb-1">
                                    {{ $event['summary'] }}
                                </p>

                                <!-- Location with Icon -->
                                @if(!empty($firstEvent['location']))
                                <p class="flex items-center text-base text-text-muted mb-1 gap-2">
                                    <span class="iconify text-primary" data-icon="tabler:map-pin" data-width="18"></span>
                                    {{ $firstEvent['location'] }}
                                </p>
                                @endif

                                <!-- Event Date -->
                                @if(!empty($event['start_date']))
                                <p class="text-base text-gray-500">
                                    {{ $event['start_date']->format('d M, Y') }}
                                </p>
                                @endif
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
@endsection