<!-- Notices Popup -->
@if (isset($todayNotices) && count($todayNotices) > 0)
    @foreach ($todayNotices as $index => $notice)
        <div id="noticeModal{{ $index }}"
            class="fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-80 opacity-0 transition-opacity duration-300 {{ $index === 0 ? '' : 'hidden' }}">

            <!-- Modal Content -->
            <div
                class="relative w-[80vw] h-[80vh] transform scale-95 transition-transform duration-300 flex items-center justify-center">
                <div
                    class="bg-white rounded-3xl shadow-xl overflow-hidden max-w-4xl w-full mx-4 relative h-full max-h-[600px]">

                    <!-- Background Image -->
                    @if (isset($notice['image']) && $notice['image'])
                        <div class="absolute inset-0 bg-cover bg-center bg-no-repeat"
                            style="background-image: url('{{ $notice['image'] }}');">
                            <!-- Strong dark overlay for better text readability -->
                            <div class="absolute inset-0 bg-gradient-to-b from-black/60 via-black/50 to-black/70"></div>
                        </div>
                    @endif

                    <!-- Content Overlay -->
                    <div class="relative z-10 h-full flex flex-col justify-center items-center p-8 md:p-12 text-center">

                        <!-- Close Button - Inside Modal -->
                        <button onclick="closeCurrentNotice(event, {{ $index }})"
                            class="absolute top-4 right-4 z-20 w-10 h-10 bg-white hover:bg-gray-100 rounded-full flex items-center justify-center text-gray-800 hover:text-black transition-colors  border border-gray-200">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M6 18L18 6M6 6l12 12"></path>
                            </svg>
                        </button>

                        <!-- Notice Counter - Inside Modal -->
                        @if (count($todayNotices) > 1)
                            <div
                                class="absolute top-4 left-4 z-20 bg-white text-gray-800 px-3 py-1 rounded-full text-sm font-medium  border border-gray-200">
                                {{ $index + 1 }} / {{ count($todayNotices) }}
                            </div>
                        @endif

                        <!-- Notice Title with enhanced visibility -->
                        <h3 class="text-xl sm:text-2xl md:text-3xl lg:text-4xl font-bold text-white mb-6 leading-tight">
                            {{ $notice['title'] }}
                        </h3>


                        <!-- Action Button with strong contrast -->
                        <a href="{{ route('page.show', 'notice') }}"
                            class="inline-block bg-white text-gray-800 font-bold py-2 md:py-3 px-8 md:px-5 text-xl md:text-2xl rounded-2xl  hover:bg-gray-100 transition-all transform hover:scale-105 border-2 border-gray-200 ">
                            See Full Notice
                        </a>
                    </div>

                    <!-- Additional text background for extreme cases -->
                    <style>
                        .text-backdrop {
                            backdrop-filter: blur(2px);
                            background: linear-gradient(135deg, rgba(0, 0, 0, 0.3) 0%, rgba(0, 0, 0, 0.5) 100%);
                            border-radius: 15px;
                            padding: 20px;
                        }
                    </style>
                </div>
            </div>
        </div>
    @endforeach

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const totalNotices = {{ count($todayNotices) }};
            let currentNoticeIndex = 0;
            let autoAdvanceInterval;

            function showNoticeModal(index) {
                const modal = document.getElementById('noticeModal' + index);
                if (modal) {
                    modal.classList.remove('hidden');
                    setTimeout(() => {
                        modal.classList.remove('opacity-0');
                        modal.querySelector('.transform').classList.remove('scale-95');
                        modal.querySelector('.transform').classList.add('scale-100');
                    }, 100);
                }
            }

            function hideNoticeModal(index) {
                const modal = document.getElementById('noticeModal' + index);
                if (modal) {
                    modal.classList.add('opacity-0');
                    modal.querySelector('.transform').classList.remove('scale-100');
                    modal.querySelector('.transform').classList.add('scale-95');
                    setTimeout(() => {
                        modal.classList.add('hidden');
                    }, 300);
                }
            }

            function startAutoAdvance() {
                clearInterval(autoAdvanceInterval); // Clear any existing interval
                autoAdvanceInterval = setInterval(() => {
                    const modal = document.getElementById('noticeModal' + currentNoticeIndex);
                    if (modal && !modal.classList.contains('hidden')) {
                        let nextIndex = currentNoticeIndex + 1;
                        if (nextIndex < totalNotices) {
                            closeCurrentNotice({
                                stopPropagation: () => {}
                            }, currentNoticeIndex);
                        } else {
                            closeCurrentNotice({
                                stopPropagation: () => {}
                            }, currentNoticeIndex);
                            clearInterval(autoAdvanceInterval); // Stop after one full loop
                        }
                    }
                }, 7000);
            }

            // Global function to close current notice and show next
            window.closeCurrentNotice = function(event, index) {
                event.stopPropagation();
                hideNoticeModal(index);

                let nextIndex = index + 1;
                currentNoticeIndex = nextIndex;

                // Show next notice immediately without delay
                if (nextIndex < totalNotices) {
                    showNoticeModal(nextIndex);
                } else {
                    clearInterval(autoAdvanceInterval);
                }
            };

            // Initial load
            if (totalNotices > 0) {
                showNoticeModal(0);
                startAutoAdvance();
            }

            // Handle escape key for all modals
            document.addEventListener('keydown', function(e) {
                if (e.key === 'Escape') {
                    for (let i = 0; i < totalNotices; i++) {
                        const modal = document.getElementById('noticeModal' + i);
                        if (modal && !modal.classList.contains('hidden')) {
                            clearInterval(autoAdvanceInterval);
                            closeCurrentNotice({
                                stopPropagation: () => {}
                            }, i);
                            break;
                        }
                    }
                }
            });

            // Handle click outside modal for all modals
            for (let i = 0; i < totalNotices; i++) {
                const modal = document.getElementById('noticeModal' + i);
                if (modal) {
                    modal.addEventListener('click', function(e) {
                        if (e.target === modal) {
                            clearInterval(autoAdvanceInterval);
                            closeCurrentNotice({
                                stopPropagation: () => {}
                            }, i);
                        }
                    });
                }
            }
        });
    </script>
@endif
