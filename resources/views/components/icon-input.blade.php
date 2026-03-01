<div id="icon-search-modal"
    class="fixed inset-0 z-[9999] flex items-center justify-center opacity-0 invisible transition-all duration-300 ease-in-out">
    <div class="absolute inset-0 bg-black/60 backdrop-blur-sm"></div>
    <div
        class="relative bg-white rounded-xl w-[90%] max-w-4xl max-h-[85vh] flex flex-col shadow-2xl transform scale-90 transition-transform duration-300 ease-in-out">
        <div class="flex items-center justify-between p-6 border-b border-gray-200">
            <h3 class="text-xl font-semibold text-gray-800 m-0">Select an Icon</h3>
            <button type="button"
                class="icon-modal-close bg-transparent border-none cursor-pointer p-2 rounded-md transition-colors duration-200 text-gray-500 hover:bg-gray-100 hover:text-gray-700">
                <span class="iconify" data-icon="tabler:x" data-width="20"></span>
            </button>
        </div>
        <div class="p-6 flex-1 overflow-hidden flex flex-col">
            <div class="mb-6">
                <div class="relative flex items-center">
                    <span class="iconify absolute left-4 text-gray-400 pointer-events-none" data-icon="tabler:search"
                        data-width="18"></span>
                    <input type="text" id="icon-search-input" placeholder="Search icons (e.g., apple, home, user)..."
                        class="w-full pl-12 pr-12 py-3 border-2 border-gray-200 rounded-lg text-sm outline-none transition-colors duration-200 focus:border-blue-600 focus:ring-4 focus:ring-blue-100">
                    <button type="button" id="icon-search-btn"
                        class="absolute right-4 bg-transparent border-none cursor-pointer text-blue-600 p-1 rounded transition-all duration-200 hover:bg-blue-50 hover:text-blue-700">
                        <span class="iconify" data-icon="tabler:search" data-width="18"></span>
                    </button>
                    <div class="icon-search-loading hidden absolute right-4 text-blue-600">
                        <span class="iconify animate-spin" data-icon="tabler:loader-2" data-width="18"></span>
                    </div>
                </div>
            </div>
            <div class="flex-1 overflow-hidden flex flex-col">
                <div
                    class="icon-empty-state flex flex-col items-center justify-center py-12 px-4 text-center text-gray-500">
                    <span class="iconify mb-4 text-gray-300" data-icon="tabler:search" data-width="48"></span>
                    <p>Search for icons to see results</p>
                </div>
                <div class="icon-results-grid hidden">
                    <!-- Will be populated with grid classes via JavaScript -->
                </div>
                <div class="icon-no-results hidden">
                    <div class="flex flex-col items-center justify-center py-12 px-4 text-center text-gray-500">
                        <span class="iconify mb-4 text-gray-300" data-icon="tabler:mood-sad" data-width="48"></span>
                        <p>No icons found for your search</p>
                    </div>
                </div>
                <div class="icon-pagination hidden">
                    <div class="flex flex-col gap-4 pt-4 border-t border-gray-200 mt-4">
                        <div class="pagination-info flex justify-center items-center gap-4">
                            <span class="pagination-text text-sm font-medium text-gray-700">Page <span
                                    class="current-page">1</span> of <span class="total-pages">1</span></span>
                            <span class="pagination-count text-xs text-gray-500">(<span class="current-count">0</span>
                                icons)</span>
                        </div>
                        <div class="pagination-controls flex justify-center items-center gap-2 flex-wrap">
                            <button type="button"
                                class="pagination-prev flex items-center gap-2 px-4 py-2 bg-white border border-gray-300 rounded-md text-sm cursor-pointer transition-all duration-200 text-gray-700 hover:bg-gray-50 hover:border-blue-600 hover:text-blue-600 disabled:opacity-50 disabled:cursor-not-allowed disabled:text-gray-400"
                                disabled>
                                <span class="iconify" data-icon="tabler:chevron-left" data-width="16"></span>
                                Previous
                            </button>
                            <div class="pagination-numbers flex gap-1 items-center flex-wrap">
                                <!-- Numbered page buttons will be dynamically generated here -->
                            </div>
                            <button type="button"
                                class="pagination-next flex items-center gap-2 px-4 py-2 bg-white border border-gray-300 rounded-md text-sm cursor-pointer transition-all duration-200 text-gray-700 hover:bg-gray-50 hover:border-blue-600 hover:text-blue-600 disabled:opacity-50 disabled:cursor-not-allowed disabled:text-gray-400">
                                Next
                                <span class="iconify" data-icon="tabler:chevron-right" data-width="16"></span>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    // Icon Search Functionality
    let iconSearchTimeout;
    let currentIconTarget = null;
    let currentPage = 1;
    let itemsPerPage = 30; // Number of icons per page (matches API length parameter)
    let currentSearchQuery = '';
    let totalPages = 1;
    let isSearching = false;

    // Open icon modal
    $(document).on('click', '.{{ $triggerButton }}', function(e) {
        e.preventDefault();
        e.stopPropagation();
        //console.log('Icon button clicked!');

        currentIconTarget = $(this).data('target');
        //console.log('Target field ID:', currentIconTarget);

        const $modal = $('#icon-search-modal');
        $modal.removeClass('opacity-0 invisible').find('.relative').removeClass('scale-90');

        // Prevent body scrolling
        $('body').addClass('overflow-hidden');

        setTimeout(function() {
            $('#icon-search-input').focus();
        }, 100);
    });

    // Close icon modal
    $(document).on('click', '.icon-modal-close, .icon-modal-overlay', function(e) {
        e.preventDefault();
        resetIconModal();
    });

    function resetIconModal() {
        const $modal = $('#icon-search-modal');
        $modal.addClass('opacity-0 invisible').find('.relative').addClass('scale-90');

        // Restore body scrolling
        $('body').removeClass('overflow-hidden');

        $('#icon-search-input').val('');
        $('.icon-results-grid').addClass('hidden').removeClass(
            'grid grid-cols-3 sm:grid-cols-4 md:grid-cols-6 lg:grid-cols-8 xl:grid-cols-10 gap-3 overflow-y-auto max-h-96 p-2'
        ).empty();
        $('.icon-empty-state').removeClass('hidden');
        $('.icon-no-results').addClass('hidden');
        $('.icon-search-loading').addClass('hidden');
        $('#icon-search-btn').removeClass('hidden');
        $('.icon-pagination').addClass('hidden');

        // Reset pagination state
        currentPage = 1;
        totalPages = 1;
        currentSearchQuery = '';
        isSearching = false;
    }

    // Icon search functionality
    $('#icon-search-input').on('keypress', function(e) {
        if (e.which === 13) { // Enter key
            e.preventDefault();
            performIconSearch(1); // Always start from page 1 for new searches
        }
    });

    $('#icon-search-btn').on('click', function(e) {
        e.preventDefault();
        performIconSearch(1); // Always start from page 1 for new searches
    });

    // Pagination navigation
    $(document).on('click', '.pagination-prev', function() {
        if (currentPage > 1) {
            performIconSearch(currentPage - 1);
        }
    });

    $(document).on('click', '.pagination-next', function() {
        if (currentPage < totalPages) {
            performIconSearch(currentPage + 1);
        }
    });

    // Numbered page navigation
    $(document).on('click', '.pagination-numbers button[data-page]', function() {
        const page = parseInt($(this).data('page'));
        if (page && page !== currentPage) {
            performIconSearch(page);
        }
    });

    function performIconSearch(page = 1) {
        const query = $('#icon-search-input').val().trim();

        if (query.length === 0) {
            $('.icon-results-grid').addClass('hidden').empty();
            $('.icon-empty-state').removeClass('hidden');
            $('.icon-no-results').addClass('hidden');
            $('.icon-search-loading').addClass('hidden');
            $('.icon-pagination').addClass('hidden');
            return;
        }

        currentSearchQuery = query;
        currentPage = page;

        $('.icon-search-loading').removeClass('hidden');
        $('#icon-search-btn').addClass('hidden');
        $('.icon-pagination').addClass('hidden');

        searchIcons(query, page);
    }

    function searchIcons(query, page = 1) {
        if (isSearching) return; // Prevent multiple simultaneous requests

        isSearching = true;
        const baseUrl = '{{ config("app.icon_api_url", "https://node.ratoguras.com") }}';
        const apiUrl =
            `${baseUrl}/api/search/icons?q=${encodeURIComponent(query)}&page=${page}&length=${itemsPerPage}`;

        $.ajax({
            url: apiUrl,
            method: 'GET',
            timeout: 10000,
            success: function(data) {
                $('.icon-search-loading').addClass('hidden');
                $('#icon-search-btn').removeClass('hidden');
                isSearching = false;
                displayIconResults(data, query, page);
            },
            error: function(xhr, status, error) {
                $('.icon-search-loading').addClass('hidden');
                $('#icon-search-btn').removeClass('hidden');
                isSearching = false;
                console.error('Icon search error:', error);

                $('.icon-results-grid').addClass('hidden').empty();
                $('.icon-empty-state').addClass('hidden');
                $('.icon-no-results').removeClass('hidden');
                $('.icon-pagination').addClass('hidden');
                $('.icon-no-results p').text('Error loading icons. Please try again.');
            }
        });
    }

    function displayIconResults(data, query, page) {
        const $grid = $('.icon-results-grid');
        const $emptyState = $('.icon-empty-state');
        const $noResults = $('.icon-no-results');
        const $pagination = $('.icon-pagination');

        // Handle the specific API response format
        let icons = [];
        let totalCount = 0;
        let totalPagesCount = 1;

        if (data && data.icons && Array.isArray(data.icons)) {
            // Expected API response format
            icons = data.icons;
            totalCount = data.totalItems || 0;
            totalPagesCount = data.totalPages || 1;
            currentPage = data.page || page;
        } else if (Array.isArray(data)) {
            // Fallback for simple array response
            icons = data;
            totalCount = data.length;
            totalPagesCount = Math.ceil(totalCount / itemsPerPage);
        } else {
            console.error('Unexpected API response format:', data);
        }

        if (!icons || icons.length === 0) {
            $grid.addClass('hidden').removeClass(
                'grid grid-cols-3 sm:grid-cols-4 md:grid-cols-6 lg:grid-cols-8 xl:grid-cols-10 gap-3 overflow-y-auto max-h-96 p-2'
            ).empty();
            $emptyState.addClass('hidden');
            $noResults.removeClass('hidden');
            $pagination.addClass('hidden');
            $noResults.find('p').text('No icons found for your search');
            return;
        }

        // Hide empty/error states and show results
        $emptyState.addClass('hidden');
        $noResults.addClass('hidden');
        $grid.removeClass('hidden').addClass(
            'grid grid-cols-3 sm:grid-cols-4 md:grid-cols-6 lg:grid-cols-8 xl:grid-cols-10 gap-3 overflow-y-auto max-h-96 p-2'
        ).empty();

        // Display icons
        icons.forEach(function(iconName) {
            const displayName = iconName.split(':')[1] || iconName;
            const $iconItem = $(`
                        <div class="flex flex-col items-center justify-center p-3 border-2 border-gray-200 rounded-lg cursor-pointer transition-all duration-200 bg-white h-20 hover:border-blue-600 hover:bg-blue-50 hover:-translate-y-0.5 hover:shadow-lg hover:shadow-blue-100" 
                             data-icon="${iconName}" 
                             title="${iconName}">
                            <span class="iconify mb-1 text-gray-700 flex-shrink-0" data-icon="${iconName}" data-width="20" data-height="20"></span>
                            <div class="text-xs text-gray-500 text-center leading-tight w-full truncate" title="${iconName}">${displayName}</div>
                        </div>
                    `);
            $grid.append($iconItem);
        });

        // Update pagination
        updatePaginationControls(currentPage, totalPagesCount, icons.length, totalCount);
    }

    function updatePaginationControls(page, totalPagesCount, currentPageCount, totalCount) {
        const $pagination = $('.icon-pagination');

        // Update global state
        currentPage = page;
        totalPages = totalPagesCount;

        // Show pagination if there are results
        if (totalCount > 0) {
            $pagination.removeClass('hidden');

            // Update pagination text
            $('.current-page').text(page);
            $('.total-pages').text(totalPagesCount);
            $('.current-count').text(totalCount);

            // Update button states
            const $prevBtn = $('.pagination-prev');
            const $nextBtn = $('.pagination-next');

            // Previous button
            if (page <= 1) {
                $prevBtn.prop('disabled', true);
            } else {
                $prevBtn.prop('disabled', false);
            }

            // Next button
            if (page >= totalPagesCount) {
                $nextBtn.prop('disabled', true);
            } else {
                $nextBtn.prop('disabled', false);
            }

            // Generate numbered pagination
            generateNumberedPagination(page, totalPagesCount);

            console.log(`Pagination: Page ${page} of ${totalPagesCount}, Total items: ${totalCount}`);
        } else {
            $pagination.addClass('hidden');
        }
    }

    function generateNumberedPagination(currentPage, totalPages) {
        const $numbersContainer = $('.pagination-numbers');
        $numbersContainer.empty();

        if (totalPages <= 1) return;

        const maxVisiblePages = 7; // Maximum number of page buttons to show
        let startPage = 1;
        let endPage = totalPages;

        if (totalPages > maxVisiblePages) {
            if (currentPage <= 4) {
                // Show pages 1-5 ... last
                endPage = 5;
            } else if (currentPage >= totalPages - 3) {
                // Show first ... last-4 to last
                startPage = totalPages - 4;
            } else {
                // Show first ... current-1, current, current+1 ... last
                startPage = currentPage - 1;
                endPage = currentPage + 1;
            }
        }

        // First page (if not in range)
        if (startPage > 1) {
            $numbersContainer.append(
                `<button type="button" class="px-3 py-2 bg-white border border-gray-300 rounded-md text-sm cursor-pointer transition-all duration-200 text-gray-700 min-w-[36px] text-center hover:bg-gray-50 hover:border-blue-600 hover:text-blue-600" data-page="1">1</button>`
            );
            if (startPage > 2) {
                $numbersContainer.append(`<span class="px-1 py-2 text-gray-400 text-sm">...</span>`);
            }
        }

        // Page range
        for (let i = startPage; i <= endPage; i++) {
            const activeClass = i === currentPage ? 'bg-blue-600 border-blue-600 text-white font-semibold' :
                'bg-white border-gray-300 text-gray-700 hover:bg-gray-50 hover:border-blue-600 hover:text-blue-600';
            $numbersContainer.append(
                `<button type="button" class="px-3 py-2 border rounded-md text-sm cursor-pointer transition-all duration-200 min-w-[36px] text-center ${activeClass}" data-page="${i}">${i}</button>`
            );
        }

        // Last page (if not in range)
        if (endPage < totalPages) {
            if (endPage < totalPages - 1) {
                $numbersContainer.append(`<span class="px-1 py-2 text-gray-400 text-sm">...</span>`);
            }
            $numbersContainer.append(
                `<button type="button" class="px-3 py-2 bg-white border border-gray-300 rounded-md text-sm cursor-pointer transition-all duration-200 text-gray-700 min-w-[36px] text-center hover:bg-gray-50 hover:border-blue-600 hover:text-blue-600" data-page="${totalPages}">${totalPages}</button>`
            );
        }
    }

    // Icon selection
    $(document).on('click', '.icon-results-grid > div[data-icon]', function() {
        const selectedIcon = $(this).data('icon');

        if (currentIconTarget) {
            const $targetInput = $(`#${currentIconTarget}`);
            $targetInput.val(selectedIcon);

            // Update preview
            updateIconPreview(currentIconTarget, selectedIcon);

            // Trigger change event for form handling
            $targetInput.trigger('change');
        }

        // Close modal
        resetIconModal();
    });

    function updateIconPreview(fieldId, iconValue) {
        const $container = $(`#${fieldId}`).closest('.{{ $inputContainer }}');

        // Remove existing preview
        $container.find('.{{ $previewDiv }}').remove();

        if (iconValue) {
            // Add new preview
            const previewHtml = `
                        <div class="{{ $previewDiv }} mt-3 p-3 bg-gray-50 rounded-lg border border-gray-200">
                            <div class="flex items-center gap-3">
                                <span class="iconify" data-icon="${iconValue}" data-width="32" data-height="32"></span>
                                <div class="flex-1">
                                    <p class="text-sm font-medium text-gray-900">Selected Icon</p>
                                    <p class="text-xs text-gray-500">${iconValue}</p>
                                </div>
                                <button type="button" class="{{ $removeIconBtn }} text-xs text-red-600 hover:text-red-800 transition-colors" data-target="${fieldId}">
                                    <span class="iconify" data-icon="tabler:trash" data-width="12"></span>
                                    Remove
                                </button>
                            </div>
                        </div>
                    `;
            $container.append(previewHtml);
        }
    }

    // Remove icon
    $(document).on('click', '.{{ $removeIconBtn }}', function() {
        const targetId = $(this).data('target');
        $(`#${targetId}`).val('').trigger('change');
        $(this).closest('.{{ $previewDiv }}').remove();
    });

    // Close modal on Escape key
    $(document).on('keydown', function(e) {
        if (e.key === 'Escape' && $('#icon-search-modal').hasClass('show')) {
            resetIconModal();
        }
    });
</script>
