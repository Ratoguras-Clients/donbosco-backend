@extends('layouts.guest')

@section('content')
<section class="relative h-[65vh] min-h-[420px] bg-black">
    @if ($news['image'])
    <img src="{{ $news['image'] }}" alt="{{ $news['title'] }}"
        class="absolute inset-0 w-full h-full object-cover opacity-80">
    @endif

    <div class="absolute inset-0 bg-gradient-to-t from-black via-black/60 to-transparent"></div>

    <div class="relative h-full max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 flex items-end pb-10 sm:pb-14 lg:pb-16">
        <div class="text-white max-w-3xl">

            <h1 class="text-2xl sm:text-4xl lg:text-5xl font-bold leading-tight mb-4 sm:mb-5">
                {{ $news['title'] }}
            </h1>

            <div class="flex items-center gap-3 text-xs sm:text-sm text-white/80">
                <span class="iconify" data-icon="mdi:calendar-month-outline"></span>
                {{ \Carbon\Carbon::parse($news['published_date'])->format('d M Y') }}
            </div>

        </div>
    </div>
</section>



<section class="bg-gray-50">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-10 sm:py-14 lg:py-16">

        @if ($news['summary'])
        <blockquote class="border-l-4 border-primary pl-4 sm:pl-6 mb-8 sm:mb-12 max-w-5xl">
            <div class="ql-editor text-gray-700 font-semibold text-base sm:text-lg">
                {!! $news['summary'] !!}
            </div>
        </blockquote>
        @endif


        <article class="prose prose-base sm:prose-lg lg:prose-xl max-w-none">
            <div class="ql-editor">
                {!! $news['content'] !!}
            </div>
        </article>


        <div class="mt-12 sm:mt-16 pt-6 sm:pt-8 border-t">
            <span class="text-xs sm:text-sm text-gray-500">
                Published on {{ \Carbon\Carbon::parse($news['published_date'])->format('d M Y') }}
            </span>
        </div>

    </div>
</section>




<section class="bg-white py-10 sm:py-14 lg:py-16">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">

        <div class="flex flex-col gap-6 mb-8 sm:flex-row sm:items-center sm:justify-between sm:mb-10">

            <div>
                <div class="flex items-center gap-3 mb-2">
                    <span class="flex items-center justify-center text-white rounded-lg w-9 h-9 bg-primary shrink-0">
                        <span class="text-lg iconify" data-icon="mdi:newspaper-variant-outline"></span>
                    </span>

                    <h2 class="text-2xl font-semibold text-primary sm:text-3xl lg:text-4xl">
                        More News & Press Releases
                    </h2>
                </div>

                <p class="text-sm sm:text-base text-text-muted">
                    Stay informed with the latest industry updates
                </p>
            </div>

            <a href="{{ route('news') }}"
                class="inline-flex items-center justify-center gap-2 px-5 py-2.5 text-sm font-medium text-white transition rounded-md bg-primary hover:bg-primary/90 w-full sm:w-auto">
                View All
                <span class="iconify" data-icon="mdi:arrow-right"></span>
            </a>
        </div>

        <div class="grid gap-6 grid-cols-1 sm:grid-cols-2 lg:grid-cols-3">

            @foreach ($otherNews as $news)
            <div class="flex flex-col h-full overflow-hidden bg-white border rounded-lg transition hover:shadow-lg">

                <img src="{{ $news['image'] }}" alt="News Image" class="object-cover w-full h-44 sm:h-48 lg:h-52">

                <div class="flex flex-col flex-1 p-5 sm:p-6">

                    <h3 class="mb-3 text-base sm:text-lg font-semibold text-gray-800 line-clamp-2">
                        {{ $news['title'] }}
                    </h3>

                    <div class="mb-6 text-sm leading-relaxed text-text-muted line-clamp-3">
                        <div class="ql-editor">
                            {!! $news['summary'] !!}
                        </div>
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
</section>
@endsection