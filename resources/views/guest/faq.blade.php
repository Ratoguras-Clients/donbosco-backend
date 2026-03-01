@extends('layouts.guest')

@section('content')

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

                    <a href="{{ route('home') }}"
                        class="inline-flex items-center gap-2 px-5 py-2.5 text-sm font-medium text-white transition rounded-md bg-primary hover:bg-primary/90">
                        Back to Home
                        <span class="iconify" data-icon="mdi:arrow-right"></span>
                    </a>
                </div>

                <!-- FAQ Items -->
                <div class="space-y-4">

                    @foreach ($data['faqs'] as $faq)
                    <details class="faq-item px-6 py-5 bg-white border rounded-lg group overflow-hidden"
                        x-data="{ open: false }" x-init="$el.addEventListener('toggle', () => open = $el.open)">
                        <summary
                            class="flex items-center justify-between list-none cursor-pointer text-base font-medium text-gray-800 select-none">
                            {{ $faq->question }}
                            <span class="iconify transition-transform duration-500"
                                :class="{ 'rotate-180': open }" data-icon="mdi:chevron-down"></span>
                        </summary>

                        <div class="faq-content mt-4 text-base text-text-muted" x-ref="content"
                            x-show="open" x-transition:enter="transition ease-out duration-500"
                            x-transition:enter-start="opacity-0 max-h-0" x-transition:enter-end="opacity-100 max-h-96"
                            x-transition:leave="transition ease-in duration-300"
                            x-transition:leave-start="opacity-100 max-h-96" x-transition:leave-end="opacity-0 max-h-0">
                            {!! $faq->answer !!}
                        </div>
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
    // GSAP Animation for FAQ fade-in on scroll
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
</script>
@endpush
