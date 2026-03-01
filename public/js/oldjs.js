$(document).off('click', '.dropdown-toggle').on('click', '.dropdown-toggle', function (e) {
    e.preventDefault();
    const dropdownMenu = $(this).siblings('.dropdown-menu');
    const arrow = $(this).find('.dropdown-arrow');
    dropdownMenu.slideToggle();
    arrow.toggleClass('rotate-180');
});

$(document).ready(function () {
    if (window.innerWidth >= 768) {
        let currentActiveMenu = null;

        $('.sidebar-icon-btn').on('click', function () {
            const menuType = $(this).data('menu');
            const fullSidebar = $('#fullSidebar');
            const currentContent = $(`[data-menu-content="${menuType}"]`);

            $('.sidebar-icon-btn').removeClass('bg-white/20 text-white shadow-lg scale-105')
                .addClass('text-red-100 hover:bg-white/10 hover:text-white hover:scale-105');

            if (currentActiveMenu === menuType && fullSidebar.hasClass('w-72')) {
                fullSidebar.removeClass('w-72').addClass('w-0');
                $('.menu-content').addClass('hidden');
                $('body').removeClass('sidebar-open');
                currentActiveMenu = null;
            } else {
                $('.menu-content').addClass('hidden');
                currentContent.removeClass('hidden');
                $(this).removeClass(
                    'text-red-100 hover:bg-white/10 hover:text-white hover:scale-105')
                    .addClass('bg-white/20 text-white shadow-lg scale-105');

                if (!fullSidebar.hasClass('w-72')) {
                    fullSidebar.removeClass('w-0').addClass('w-72');
                    $('body').addClass('sidebar-open');
                }
                currentActiveMenu = menuType;
            }
        });

        // Close sidebar when clicking outside
        $(document).on('click', function (e) {
            if (!$(e.target).closest('.hidden.md\\:fixed').length) {
                $('#fullSidebar').removeClass('w-72').addClass('w-0');
                $('.menu-content').addClass('hidden');
                $('.dropdown-menu').slideUp();
                $('.dropdown-arrow').removeClass('rotate-180');
                $('body').removeClass('sidebar-open');
                $('.sidebar-icon-btn').removeClass('bg-white/20 text-white shadow-lg scale-105')
                    .addClass('text-red-100 hover:bg-white/10 hover:text-white hover:scale-105');
                currentActiveMenu = null;
            }
        });
    }
});