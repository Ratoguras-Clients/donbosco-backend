@extends('layouts.app')

@section('content')
    <section class="flex flex-col space-y-6">
        <div
            class="rounded-md w-full bg-white dark:bg-gray-900 text-gray-800 py-4 border-b border-gray-200 flex justify-center px-6">
            <div class="flex items-center w-full flex-wrap gap-2">

                {{-- Parent Organization --}}
                <a href="{{ route('dashboard') }}"
                    class="px-4 py-1 text-sm font-medium rounded-full transition {{ $activeOrganization->id === $parentOrganization->id
                        ? 'bg-blue-600 text-white'
                        : 'bg-gray-100 text-gray-700 hover:bg-gray-200' }}">
                    {{ $parentOrganization->short_name }}
                </a>
                {{-- |
                Sister Organizations
                @forelse ($sisterOrganizations as $organization)
                    <a href="{{ route('organization.dashboard', $organization->slug) }}"
                        class="px-4 py-1 text-sm font-medium rounded-full transition
                   {{ $activeOrganization->id === $organization->id
                       ? 'bg-blue-600 text-white'
                       : 'bg-gray-100 text-gray-700 hover:bg-gray-200' }}">
                        {{ $organization->short_name }}
                    </a>
                @empty
                    <span class="text-sm text-blue-800 ml-2">
                        No sister organizations found.
                    </span>
                @endforelse --}}

            </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">

            @php
                $actions = [];
                if ($activeOrganization->id === $parentOrganization->id) {
                    //  $actions[] = ['label' => 'Add Sister Organization', 'url' => route('organizations.create')];
                    $actions[] = ['label' => 'Manage Staff Roles', 'url' => route('staff-role.index')];
                    // $actions[] = [
                    //     'label' => 'Add Other Organization',
                    //     'url' => route('otherorganizations.index', $activeOrganization->slug),
                    // ];
                }
                $actions[] = [
                    'label' => 'Add School Staff',
                    'url' => route('organization-staff.index', $activeOrganization->slug),
                ];
            @endphp

            <button type="button"
                class="hub-trigger flex flex-col items-center justify-center w-full p-6 border-2 border-dashed border-gray-300 rounded-lg text-gray-600 hover:border-blue-500 hover:text-blue-600 hover:bg-blue-50 transition group"
                data-title="Organizations" data-actions="{{ json_encode($actions) }}">
                <span class="block text-lg font-bold group-hover:text-blue-600 transition">
                    School
                </span>
                <span class="text-sm text-gray-400">Manage Staff</span>
            </button>

            @php
                $contentActions = [
                    ['label' => 'Manage Blog', 'url' => route('blog.index', $activeOrganization->slug)],
                    ['label' => 'Manage News', 'url' => route('news.index', $activeOrganization->slug)],
                    ['label' => 'Manage Events', 'url' => route('events.index', $activeOrganization->slug)],
                ];
            @endphp

            <button type="button"
                class="hub-trigger flex flex-col items-center justify-center w-full p-6 border-2 border-dashed border-gray-300 rounded-lg text-gray-600 hover:border-blue-500 hover:text-blue-600 hover:bg-blue-50 transition group"
                data-title="Content Manager" data-actions="{{ json_encode($contentActions) }}">
                <span class="block text-lg font-bold group-hover:text-blue-600 transition">Content</span>
                <span class="text-sm text-gray-400">Manage Blog, News, Events</span>
            </button>

            @php
                $websiteActions = [
                    ['label' => 'Home Carousel', 'url' => route('home-carousel.index', $activeOrganization->slug)],
                    ['label' => 'Mission', 'url' => route('mission.index', $activeOrganization->slug)],
                    ['label' => 'FAQ', 'url' => route('faq.index', $activeOrganization->slug)],
                    ['label' => 'Notices', 'url' => route('notices.index', $activeOrganization->slug)],
                ];
            @endphp

            <button type="button"
                class="hub-trigger flex flex-col items-center justify-center w-full p-6 border-2 border-dashed border-gray-300 rounded-lg text-gray-600 hover:border-blue-500 hover:text-blue-600 hover:bg-blue-50 transition group"
                data-title="Website" data-actions="{{ json_encode($websiteActions) }}">
                <span class="block text-lg font-bold group-hover:text-blue-600 transition">Website</span>
                <span class="text-sm text-gray-400">Manage Home Carousel, Mission, FAQ, Notices</span>
            </button>

            @php
                $engagementActions = [
                    //  ['label' => 'Services', 'url' => route('services.index', $activeOrganization->slug)],
                    ['label' => 'Journeys', 'url' => route('journeys.index', $activeOrganization->slug)],
                    ['label' => 'Collections', 'url' => route('collections.index', $activeOrganization->slug)],
                ];
            @endphp

            <button type="button"
                class="hub-trigger flex flex-col items-center justify-center w-full p-6 border-2 border-dashed border-gray-300 rounded-lg text-gray-600 hover:border-blue-500 hover:text-blue-600 hover:bg-blue-50 transition group"
                data-title="Engagement" data-actions="{{ json_encode($engagementActions) }}">
                <span class="block text-lg font-bold group-hover:text-blue-600 transition">Engagement</span>
                <span class="text-sm text-gray-400">Manage Journeys, Collections</span>
            </button>

            @php
                $communicationActions = [
                    ['label' => 'Messages', 'url' => route('messages.index', $activeOrganization->slug)],
                    ['label' => 'Contact', 'url' => route('contact.index', $activeOrganization->slug)],
                ];
            @endphp

            <button type="button"
                class="hub-trigger flex flex-col items-center justify-center w-full p-6 border-2 border-dashed border-gray-300 rounded-lg text-gray-600 hover:border-blue-500 hover:text-blue-600 hover:bg-blue-50 transition group"
                data-title="Communication" data-actions="{{ json_encode($communicationActions) }}">
                <span class="block text-lg font-bold group-hover:text-blue-600 transition">Communication</span>
                <span class="text-sm text-gray-400">Manage Messages & Contact</span>
            </button>

            @unless ($activeOrganization->id === $parentOrganization->id)
                @php
                    $settingsActions = [
                        [
                            'label' => 'Edit Organization',
                            'url' => route('organizations.edit', $activeOrganization->slug),
                        ],
                    ];
                @endphp

                <button type="button"
                    class="hub-trigger flex flex-col items-center justify-center w-full p-6 border-2 border-dashed border-gray-300 rounded-lg text-gray-600 hover:border-blue-500 hover:text-blue-600 hover:bg-blue-50 transition group"
                    data-title="Organization Settings" data-actions="{{ json_encode($settingsActions) }}">
                    <span class="block text-lg font-bold group-hover:text-blue-600 transition">Settings</span>
                    <span class="text-sm text-gray-400">Edit Organization</span>
                </button>
            @endunless

        </div>
    </section>

    @if ($activeOrganization->id === $parentOrganization->id)
        <div class="py-5">
            <hr class="border-t-2 border-dashed border-gray-300">
        </div>
        <section class="flex flex-col space-y-6">
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">

                @php
                    $homeactions = [
                        [
                            'label' => 'Homemission',
                            'url' => route('mission.homemission', $activeOrganization->slug),
                        ],
                    ];
                @endphp

                <button type="button"
                    class="hub-trigger flex flex-col items-center justify-center w-full p-6 border-2 border-dashed border-gray-300 rounded-lg text-gray-600 hover:border-blue-500 hover:text-blue-600 hover:bg-blue-50 transition group"
                    data-title="Home page" data-actions="{{ json_encode($homeactions) }}">
                    <span class="block text-lg font-bold group-hover:text-blue-600 transition">
                        Home page
                    </span>
                    <span class="text-sm text-gray-400">Manage Homemission</span>
                </button>

                @php
                    $aboutactions = [
                        ['label' => 'About Hero', 'url' => route('about.abouthero', $activeOrganization->slug)],
                        ['label' => 'About Story', 'url' => route('about.aboutstory', $activeOrganization->slug)],
                    ];
                @endphp

                <button type="button"
                    class="hub-trigger flex flex-col items-center justify-center w-full p-6 border-2 border-dashed border-gray-300 rounded-lg text-gray-600 hover:border-blue-500 hover:text-blue-600 hover:bg-blue-50 transition group"
                    data-title="About page" data-actions="{{ json_encode($aboutactions) }}">
                    <span class="block text-lg font-bold group-hover:text-blue-600 transition">
                        About page
                    </span>
                    <span class="text-sm text-gray-400">Manage About Hero,About Story</span>
                </button>

                {{-- @php
                    $newsactions = [
                        ['label' => 'News Hero', 'url' => route('newsandnotice.newshero', $activeOrganization->slug)],
                    ];
                @endphp

                <button type="button"
                    class="hub-trigger flex flex-col items-center justify-center w-full p-6 border-2 border-dashed border-gray-300 rounded-lg text-gray-600 hover:border-blue-500 hover:text-blue-600 hover:bg-blue-50 transition group"
                    data-title="News and Notices" data-actions="{{ json_encode($newsactions) }}">
                    <span class="block text-lg font-bold group-hover:text-blue-600 transition">
                        News and Notices
                    </span>
                    <span class="text-sm text-gray-400">Manage News Hero</span>
                </button> --}}
                {{-- @php
                    $organizationactions = [
                        [
                            'label' => 'Organization Hero',
                            'url' => route('organizationhero', $activeOrganization->slug),
                        ],
                    ];
                @endphp

                <button type="button"
                    class="hub-trigger flex flex-col items-center justify-center w-full p-6 border-2 border-dashed border-gray-300 rounded-lg text-gray-600 hover:border-blue-500 hover:text-blue-600 hover:bg-blue-50 transition group"
                    data-title="Organization page" data-actions="{{ json_encode($organizationactions) }}">

                    <span class="block text-lg font-bold group-hover:text-blue-600 transition">
                        Organization page
                    </span>

                    <span class="text-sm text-gray-400">
                        Hero Section
                    </span>

                </button> --}}


                @php
                    $mediaactions = [['label' => 'Media Hero', 'url' => route('mediahero', $activeOrganization->slug)]];
                @endphp

                <button type="button"
                    class="hub-trigger flex flex-col items-center justify-center w-full p-6 border-2 border-dashed border-gray-300 rounded-lg text-gray-600 hover:border-blue-500 hover:text-blue-600 hover:bg-blue-50 transition group"
                    data-title="Media Hero" data-actions="{{ json_encode($mediaactions) }}">
                    <span class="00000000block text-lg font-bold group-hover:text-blue-600 transition">
                        Media Hero
                    </span>
                    <span class="text-sm text-gray-400">Manage Media Hero</span>
                </button>

                @php
                    $faqactions = [['label' => 'FAQ Hero', 'url' => route('faqhero', $activeOrganization->slug)]];
                @endphp

                <button type="button"
                    class="hub-trigger flex flex-col items-center justify-center w-full p-6 border-2 border-dashed border-gray-300 rounded-lg text-gray-600 hover:border-blue-500 hover:text-blue-600 hover:bg-blue-50 transition group"
                    data-title="FAQ Hero" data-actions="{{ json_encode($faqactions) }}">
                    <span class="00000000block text-lg font-bold group-hover:text-blue-600 transition">
                        FAQ Hero
                    </span>
                    <span class="text-sm text-gray-400">Manage FAQ Hero</span>
                </button>

                @php
                    $teamactions = [['label' => 'Team Hero', 'url' => route('teamhero', $activeOrganization->slug)]];
                @endphp

                <button type="button"
                    class="hub-trigger flex flex-col items-center justify-center w-full p-6 border-2 border-dashed border-gray-300 rounded-lg text-gray-600 hover:border-blue-500 hover:text-blue-600 hover:bg-blue-50 transition group"
                    data-title="Team Hero" data-actions="{{ json_encode($teamactions) }}">
                    <span class="00000000block text-lg font-bold group-hover:text-blue-600 transition">
                        Team Hero
                    </span>
                    <span class="text-sm text-gray-400">Manage Team Hero</span>
                </button>

                  @php
                    // $admissionactions = [['label' => 'Admission', 'url' => route('admission', $activeOrganization->slug)]];
                @endphp

                <button type="button"
                    class="hub-trigger flex flex-col items-center justify-center w-full p-6 border-2 border-dashed border-gray-300 rounded-lg text-gray-600 hover:border-blue-500 hover:text-blue-600 hover:bg-blue-50 transition group"
                    data-title="Admission" data-actions="{{ json_encode($teamactions) }}">
                    <span class="00000000block text-lg font-bold group-hover:text-blue-600 transition">
                        Admission
                    </span>
                    <span class="text-sm text-gray-400">Manage Admission</span>
                </button>

                {{-- @php
                    $messageactions = [
                        ['label' => 'Message Hero', 'url' => route('messagehero', $activeOrganization->slug)],
                    ];
                @endphp

                <button type="button"
                    class="hub-trigger flex flex-col items-center justify-center w-full p-6 border-2 border-dashed border-gray-300 rounded-lg text-gray-600 hover:border-blue-500 hover:text-blue-600 hover:bg-blue-50 transition group"
                    data-title="Message Hero" data-actions="{{ json_encode($messageactions) }}">
                    <span class="00000000block text-lg font-bold group-hover:text-blue-600 transition">
                        Message Hero
                    </span>
                    <span class="text-sm text-gray-400">Manage Message Hero</span>
                </button>

                @php
                    $sisteractions = [
                        ['label' => 'Sister Hero', 'url' => route('sisterhero', $activeOrganization->slug)],
                    ];
                @endphp

                <button type="button"
                    class="hub-trigger flex flex-col items-center justify-center w-full p-6 border-2 border-dashed border-gray-300 rounded-lg text-gray-600 hover:border-blue-500 hover:text-blue-600 hover:bg-blue-50 transition group"
                    data-title="Sister Hero" data-actions="{{ json_encode($sisteractions) }}">
                    <span class="00000000block text-lg font-bold group-hover:text-blue-600 transition">
                        Sister Hero
                    </span>
                    <span class="text-sm text-gray-400">Manage Sister Hero</span>
                </button> --}}
            </div>
        </section>
    @endif
@endsection

@push('modals')
    <div id="dynamic-modal"
        class="hidden fixed inset-0 z-50 items-center justify-center bg-black/60 backdrop-blur-sm backdrop-fade">

        <div id="modal-content" class="bg-white rounded-3xl w-full max-w-2xl p-8 shadow-2xl relative">
            <h2 id="modal-org-title" class="text-2xl font-black mb-6 text-gray-800 tracking-tight text-center">
                Department
            </h2>

            <div id="actions-container" class="grid grid-cols-1 sm:grid-cols-3 gap-4">
            </div>

            <button id="close-modal"
                class="w-full mt-8 text-gray-400 hover:text-red-500 font-medium transition text-center uppercase text-xs tracking-widest">
                ✕ Close Menu
            </button>
        </div>
    </div>
@endpush

@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const modal = document.getElementById('dynamic-modal');
            const modalBox = document.getElementById('modal-content');
            const titleElement = document.getElementById('modal-org-title');
            const container = document.getElementById('actions-container');

            document.querySelectorAll('.hub-trigger').forEach(trigger => {
                trigger.addEventListener('click', () => {
                    const title = trigger.dataset.title;
                    const actions = JSON.parse(trigger.dataset.actions);

                    titleElement.innerText = title;
                    container.innerHTML = '';

                    actions.forEach(action => {
                        const btn = document.createElement('a');
                        btn.href = action.url;
                        btn.className =
                            `flex flex-col items-center justify-center text-center p-4 border-2 border-dashed border-gray-200 rounded-xl text-gray-600 hover:border-blue-500 hover:text-blue-600 hover:bg-blue-50 transition-all duration-200 group`;
                        btn.innerHTML =
                            `<span class="text-sm font-semibold mb-1">${action.label}</span>`;
                        container.appendChild(btn);
                    });

                    modal.classList.remove('hidden');
                    modal.classList.add('flex');
                    modalBox.classList.add('animate-pop');
                });
            });

            const closeMenu = () => {
                modalBox.classList.remove('animate-pop');
                modal.classList.add('hidden');
                modal.classList.remove('flex');
            };

            document.getElementById('close-modal').addEventListener('click', closeMenu);

            modal.addEventListener('click', (e) => {
                if (e.target === modal) closeMenu();
            });
        });
    </script>
@endpush

@push('styles')
    <style>
        @keyframes modalPop {
            0% {
                opacity: 0;
                transform: scale(0.9) translateY(10px);
            }

            70% {
                transform: scale(1.02) translateY(0);
            }

            100% {
                opacity: 1;
                transform: scale(1) translateY(0);
            }
        }

        .animate-pop {
            animation: modalPop 0.3s cubic-bezier(0.34, 1.56, 0.64, 1) forwards;
        }

        .backdrop-fade {
            transition: opacity 0.3s ease-in-out;
        }
    </style>
@endpush
