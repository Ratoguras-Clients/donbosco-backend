@extends('layouts.guest')

@section('content')
    <section class="w-full h-[60vh] sm:h-[75vh] lg:h-[70vh] max-h-[100svh] overflow-hidden">
        <div class="h-full bg-center bg-cover flex flex-col items-center justify-center sm:px-6 lg:px-8 w-full"
            style="background-image: linear-gradient(rgba(0,0,0,0.2), rgba(0,0,0,0.2)), url('{{ asset('images/contact.png') }}');">

            <h1 class="mb-4 text-3xl font-bold sm:text-4xl md:text-6xl text-white">
                Get In Touch
            </h1>

            <p class="max-w-2xl text-sm sm:text-base md:text-lg text-white/90">
                Have questions? We're here to help and answer any questions you might have.
            </p>
        </div>
    </section>

    <section class="py-10 bg-light-primary sm:py-16">
        <div class="w-full px-4 sm:px-6 lg:px-8">

            <div class="grid place-items-center">
                <div class="w-full max-w-7xl">

                    {{-- Top Info Cards --}}
                    <div class="grid grid-cols-1 gap-4 md:grid-cols-3">

                        {{-- Address --}}
                        <div class="px-8 py-10 text-center bg-white border rounded-lg">
                            <span class="inline-block mb-4 text-3xl iconify text-[#D4AF37]"
                                data-icon="mdi:map-marker-outline"></span>

                            <h3 class="mb-2 font-semibold text-gray-800">Address</h3>
                            <p class="mb-1 text-sm text-gray-500">
                                We're here to help with any inquiries.
                            </p>
                            <p class="text-sm font-medium text-[#D4AF37]">
                                Bargachhi Chowk, Biratnagar
                            </p>
                        </div>

                        {{-- Call Us --}}
                        <div class="px-8 py-10 text-center bg-[#f3fff8] border rounded-lg">
                            <span class="inline-block mb-4 text-3xl iconify text-[#12A205]"
                                data-icon="mdi:phone-outline"></span>

                            <h3 class="mb-2 font-semibold text-gray-800">Call Us</h3>
                            <p class="mb-1 text-sm text-gray-500">
                                We're here to help with any inquiries.
                            </p>
                            <p class="text-sm font-medium text-[#12A205]">
                                021-590567
                            </p>
                        </div>

                        {{-- Email --}}
                        <div class="px-8 py-10 text-center bg-[#f8fbff] border rounded-lg">
                            <span class="inline-block mb-4 text-3xl text-primary iconify"
                                data-icon="mdi:email-outline"></span>

                            <h3 class="mb-2 font-semibold text-gray-800">Email</h3>
                            <p class="mb-1 text-sm text-gray-500">
                                We're here to help with any inquiries.
                            </p>
                            <p class="text-sm font-medium text-primary">
                                info@nicb.org.np
                            </p>
                        </div>

                    </div>

                </div>
            </div>

        </div>
    </section>


    <section class="py-10 bg-white sm:py-16">
        <div class="px-4 sm:px-6 lg:px-8">

            <div class="max-w-7xl mx-auto">
                <div class="grid items-stretch gap-12 lg:grid-cols-2">

                    <!-- Form -->
                    <div class="flex flex-col">
                        <h3 class="mb-2 text-xl font-semibold text-primary">
                            Send us a Message
                        </h3>
                        <p class="mb-8 text-sm text-text-muted">
                            Fill out the form below and we'll get back to you shortly.
                        </p>

                        <form action="{{ route('contact.store') }}" method="POST">
                            @csrf
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1 cursor-pointer">
                                    Organization <span class="text-red-500">*</span>
                                </label>

                                <select name="organization_id" id=""
                                    class="w-full px-4 py-2 mt-1 border rounded-md focus:ring-primary">
                                    @foreach ($organizations as $organization)
                                        <option value="{{ $organization->id }}"
                                            @if ($organization->parent_id === null) selected @endif>{{ $organization->name }}
                                        </option>
                                    @endforeach
                                </select>

                                @error('organization_id')
                                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label class="text-sm text-gray-600">Full Name</label>
                                <input type="text" name="name" required
                                    class="w-full px-4 py-2 mt-1 border rounded-md focus:ring-primary">
                            </div>

                            <div>
                                <label class="text-sm text-gray-600">Email Address</label>
                                <input type="email" name="email" required
                                    class="w-full px-4 py-2 mt-1 border rounded-md focus:ring-primary">
                            </div>

                            <div>
                                <label class="text-sm text-gray-600">Phone Number</label>
                                <input type="text" name="phone" required
                                    class="w-full px-4 py-2 mt-1 border rounded-md focus:ring-primary">
                            </div>

                            <div>
                                <label class="text-sm text-gray-600">Message</label>
                                <textarea name="message" rows="4" required class="w-full px-4 py-2 mt-1 border rounded-md focus:ring-primary"></textarea>
                            </div>
                            

                            <div class="pt-2">
                                <input type="hidden" name="form_token" value="{{ Str::uuid() }}">
                                <button type="submit" class="px-6 py-2 text-white rounded-md bg-primary">
                                    Send Query
                                </button>
                            </div>
                        </form>
                    </div>

                    <!-- Map -->
                    <div class="flex flex-col h-full">
                        <h3 class="mb-2 text-xl font-semibold text-primary">
                            Visit us
                        </h3>
                        <p class="mb-6 text-sm text-text-muted">
                            Located in the heart of Biratnagar, easily accessible.
                        </p>

                        <!-- Map fills remaining height -->
                        <div class="flex-1 overflow-hidden border rounded-lg">
                            <iframe src="https://maps.google.com/maps?q=Biratnagar&t=&z=15&ie=UTF8&iwloc=&output=embed"
                                class="w-full h-full min-h-[300px] border-0" loading="lazy">
                            </iframe>
                        </div>

                        <p class="mt-6 text-sm leading-relaxed text-text-muted">
                            Our office is located in the industrial area of Koshi i.e. Biratnagar.
                            You can easily reach us via phone or email, or visit in person during
                            our office hours. We look forward to collaborating with you!
                        </p>
                    </div>

                </div>
            </div>

        </div>
    </section>
    <section class="py-10 bg-[#eaf4ff] sm:py-16">
        <div class="w-full px-4 sm:px-6 lg:px-8">

            <div class="grid place-items-center">
                <div class="w-full max-w-7xl">

                    {{-- Header --}}
                    <div class="flex flex-col gap-6 mb-10 md:flex-row md:items-center md:justify-between">

                        <div>
                            <div class="flex items-center gap-2 mb-2">
                                <span class="px-2 py-1 text-xs font-semibold text-white rounded bg-primary">
                                    FAQ
                                </span>
                                <h2 class="text-3xl font-semibold text-primary">
                                    Frequently Asked Questions
                                </h2>
                            </div>

                            <p class="max-w-xl text-sm text-text-muted">
                                Our FAQ section provides quick answers to common questions about CNI Koshi Province.
                            </p>
                        </div>

                        <a href="{{route('faq.show')}}"
                            class="inline-flex items-center gap-2 px-4 py-2 text-sm font-medium text-white transition rounded-md bg-primary hover:bg-primary/90">
                            View All
                            <span class="iconify" data-icon="mdi:arrow-right"></span>
                        </a>

                    </div>

                    {{-- FAQ Accordion --}}
                    <div class="space-y-4">

                        <details class="px-6 py-4 bg-white border rounded-lg group border-primary/30">
                            <summary class="flex items-center justify-between cursor-pointer list-none">
                                <span class="font-medium text-gray-800">
                                    What does CNI Koshi do?
                                </span>
                                <span class="transition iconify group-open:rotate-180" data-icon="mdi:chevron-down"></span>
                            </summary>

                            <p class="mt-4 text-sm leading-relaxed text-text-muted">
                                CNI Koshi works to promote industrial growth, represent business interests,
                                and support policy advocacy in the Koshi Province.
                            </p>
                        </details>

                        <details class="px-6 py-4 bg-white border rounded-lg group border-primary/30">
                            <summary class="flex items-center justify-between cursor-pointer list-none">
                                <span class="font-medium text-gray-800">
                                    Who can become a member of CNI Koshi?
                                </span>
                                <span class="transition iconify group-open:rotate-180" data-icon="mdi:chevron-down"></span>
                            </summary>

                            <p class="mt-4 text-sm leading-relaxed text-text-muted">
                                Any registered industry or business operating in Koshi Province
                                can apply for membership.
                            </p>
                        </details>

                        <details class="px-6 py-4 bg-white border rounded-lg group border-primary/30">
                            <summary class="flex items-center justify-between cursor-pointer list-none">
                                <span class="font-medium text-gray-800">
                                    What benefits do members receive?
                                </span>
                                <span class="transition iconify group-open:rotate-180"
                                    data-icon="mdi:chevron-down"></span>
                            </summary>

                            <p class="mt-4 text-sm leading-relaxed text-text-muted">
                                Members gain access to advocacy support, networking events,
                                industry insights, and policy representation.
                            </p>
                        </details>

                    </div>

                </div>
            </div>

        </div>
    </section>
@endsection
@push('scripts')
    <script>
        let searchTimeout;

        function getOrganizations(search = '') {
            $.ajax({
                url: "#",
                type: "GET",
                data: {
                    search
                },
                beforeSend() {
                    $('#orgOptions').html(`<li class="px-4 py-2 text-gray-400 text-sm">Loading...</li>`);
                },
                success(data) {
                    console.log(data);
                    renderOrganizations(data);
                }

                error() {
                    $('#orgOptions').html(
                        `<li class="px-4 py-2 text-red-500 text-sm">Failed to load organizations</li>`
                    );
                }
            });
        }


        function renderOrganizations(data) {
            const list = $('#orgOptions');
            list.empty();

            if (!data.length) {
                list.append(`<li class="px-4 py-2 text-gray-500 text-sm">No organization found</li>`);
                return;
            }

            data.forEach(org => {
                list.append(`
            <li class="px-4 py-2 hover:bg-blue-50 cursor-pointer org-item"
                data-id="${org.id}" data-name="${org.name}">
                ${org.name}
            </li>
        `);
            });
        }

        getOrganizations();

        $('#orgTrigger').on('click', function() {
            $('#orgDropdown').toggleClass('hidden');
            if (!$('#orgDropdown').hasClass('hidden')) {
                $('#orgSearch').focus();
            }
        });

        $('#orgSearch').on('keyup', function() {
            clearTimeout(searchTimeout);
            searchTimeout = setTimeout(() => {
                getOrganizations($(this).val());
            }, 300);
        });

        $(document).on('click', '.org-item', function() {
            const id = $(this).data('id');
            const name = $(this).data('name');

            $('#organization_id').val(id);
            $('#selectedOrgText').text(name);
            $('#orgDropdown').addClass('hidden');
        });
    </script>
@endpush