@extends('layouts.guest')

@section('content')
    <section class="w-full h-[60vh] sm:h-[75vh] lg:h-[70vh] max-h-[100svh] overflow-hidden">
        <div class="h-full bg-center bg-cover flex flex-col items-center justify-center sm:px-6 lg:px-8 w-full"
            style="background-image: linear-gradient(rgba(0,0,0,0.2), rgba(0,0,0,0.2)), url('{{ asset('images/team.png') }}');">

            <h1 class="mb-4 text-3xl font-bold sm:text-4xl md:text-6xl text-white">
                Our Team
            </h1>

            <p class="max-w-2xl text-sm sm:text-base md:text-lg text-white/90">
                Our team works together to strengthen Koshi Province’s industrial future. </p>
        </div>
    </section>


    {{-- <section class="py-10 bg-light-primary">
        <div class="w-full px-4 sm:px-6 lg:px-8">

            <div class="grid place-items-center">
                <div class="w-full max-w-7xl">

                    <div class="flex flex-row justify-evenly">

                        <div class="flex flex-col items-center">
                            <h3 class="text-3xl font-bold text-primary">
                                {{ $data['count'] }}
                            </h3>
                            <p class="mt-1 text-sm text-text-muted">
                                Team Members
                            </p>
                        </div>

                        <div class="flex flex-col items-center">
                            <h3 class="text-3xl font-bold text-primary">
                                {{ $data['experience_years'] }}
                            </h3>
                            <p class="mt-1 text-sm text-text-muted">
                                Years Combined Experience
                            </p>
                        </div>

                        <!-- <div>
                                                        <h3 class="text-3xl font-bold text-primary">
                                                            15+
                                                        </h3>
                                                        <p class="mt-1 text-sm text-text-muted">
                                                            Departments
                                                        </p>
                                                    </div> -->

                        <div class="flex flex-col items-center">
                            <h3 class="text-3xl font-bold text-primary">
                                100%
                            </h3>
                            <p class="mt-1 text-sm text-text-muted">
                                Commitment to Excellence
                            </p>
                        </div>

                    </div>

                </div>
            </div>

        </div>
    </section> --}}
    <section class="py-16 bg-light-primary">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">

            <div class="grid grid-cols-1 md:grid-cols-3 gap-8 md:gap-4 items-center">

                <div class="flex flex-col items-center text-center p-4">
                    <h3 class="text-4xl lg:text-5xl font-extrabold text-primary tracking-tight">
                        {{ $data['count'] }}
                    </h3>
                    <p class="mt-2 text-base font-medium text-text-muted uppercase tracking-wider">
                        Team Members
                    </p>
                </div>

                <div
                    class="flex flex-col items-center text-center p-4 border-y md:border-y-0 md:border-x border-gray-200/50">
                    <h3 class="text-4xl lg:text-5xl font-extrabold text-primary tracking-tight">
                        {{ $data['experience_years'] }}+
                    </h3>
                    <p class="mt-2 text-base font-medium text-text-muted uppercase tracking-wider">
                        Years Combined Experience
                    </p>
                </div>

                <div class="flex flex-col items-center text-center p-4">
                    <h3 class="text-4xl lg:text-5xl font-extrabold text-primary tracking-tight">
                        100%
                    </h3>
                    <p class="mt-2 text-base font-medium text-text-muted uppercase tracking-wider">
                        Commitment to Excellence
                    </p>
                </div>

            </div>
        </div>
    </section>


    <section class="py-16 bg-gray-100/20">
        <div class="w-full px-4 sm:px-6 lg:px-8">
            <div class="grid place-items-center">
                <div class="w-full max-w-7xl">

                    <div class="grid grid-cols-1 gap-8 sm:grid-cols-2 lg:grid-cols-3">

                        @forelse ($data['staffs'] as $staff)
                            <div class="overflow-hidden bg-white border rounded-xl shadow-sm">

                                {{-- Image --}}
                                <img src="{{ $staff['image'] ? asset($staff['image']) : asset('images/default-user.png') }}"
                                    alt="{{ $staff['name'] }}" class="object-cover w-full h-64">

                                <div class="p-5">
                                    {{-- Name --}}
                                    <h3 class="text-xl font-bold text-gray-900">
                                        {{ $staff['name'] }}
                                    </h3>

                                    {{-- Bio (acts as designation/role text) --}}
                                    @if (!empty($staff['bio']))
                                        <p class="mb-4 text-md text-text-muted">
                                            {{ $staff['bio'] }}
                                        </p>
                                    @endif

                                    <div class="space-y-2 text-sm text-text-muted">

                                        {{-- Email --}}
                                        @if (!empty($staff['email']))
                                            <div class="flex items-center gap-2">
                                                <span class="text-primary iconify" data-icon="mdi:email-outline"></span>
                                                {{ $staff['email'] }}
                                            </div>
                                        @endif

                                        {{-- Phone --}}
                                        @if (!empty($staff['phone']))
                                            <div class="flex items-center gap-2">
                                                <span class="text-primary iconify" data-icon="mdi:phone-outline"></span>
                                                {{ $staff['phone'] }}
                                            </div>
                                        @endif

                                    </div>
                                </div>
                            </div>
                        @empty
                            <p class="col-span-3 text-center text-text-muted">
                                No team members available at the moment.
                            </p>
                        @endforelse

                    </div>

                </div>
            </div>
        </div>
    </section>




    <section class="py-10 bg-light-primary sm:py-16">
        <div class="w-full px-4 sm:px-6 lg:px-8">

            <div class="grid place-items-center">
                <div class="w-full max-w-7xl text-center">

                    {{-- Heading --}}
                    <h2 class="text-4xl font-semibold text-gray-900 sm:text-5xl">
                        Our Team Values
                    </h2>

                    <p class="mt-3 text-text-muted">
                        Core principles that guide our team every day
                    </p>

                    {{-- Cards --}}
                    <div class="grid grid-cols-1 gap-6 mt-16 sm:grid-cols-2 lg:grid-cols-3">

                        {{-- Card --}}
                        <div class="px-8 py-10 bg-white border rounded-xl">
                            <div class="flex justify-center mb-6">
                                <span class="text-4xl text-primary iconify" data-icon="mdi:target"></span>
                            </div>

                            <h3 class="mb-4 text-sm font-semibold tracking-widest text-primary">
                                DEDICATED
                            </h3>

                            <p class="text-sm leading-relaxed text-text-muted">
                                Committed to achieving excellence in everything we do for our
                                members and stakeholders.
                            </p>
                        </div>

                        {{-- Card --}}
                        <div class="px-8 py-10 bg-white border rounded-xl">
                            <div class="flex justify-center mb-6">
                                <span class="text-4xl text-primary iconify" data-icon="mdi:account-group-outline"></span>
                            </div>

                            <h3 class="mb-4 text-sm font-semibold tracking-widest text-primary">
                                COLLABORATIVE
                            </h3>

                            <p class="text-sm leading-relaxed text-text-muted">
                                Working together across departments to deliver comprehensive
                                solutions and support.
                            </p>
                        </div>

                        {{-- Card --}}
                        <div class="px-8 py-10 bg-white border rounded-xl">
                            <div class="flex justify-center mb-6">
                                <span class="text-4xl text-primary iconify" data-icon="mdi:lightbulb-outline"></span>
                            </div>

                            <h3 class="mb-4 text-sm font-semibold tracking-widest text-primary">
                                INNOVATIVE
                            </h3>

                            <p class="text-sm leading-relaxed text-text-muted">
                                Constantly seeking new ways to improve services and adapt
                                to industry changes.
                            </p>
                        </div>

                    </div>

                </div>
            </div>

        </div>
    </section>
@endsection
