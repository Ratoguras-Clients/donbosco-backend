// public/js/sidebar.js
document.addEventListener('DOMContentLoaded', function () {
    console.log('Sidebar script loaded');

    // Get DOM elements
    const iconButtons = document.querySelectorAll('.sidebar-icon-btn');
    const fullSidebar = document.getElementById('fullSidebar');
    const menuContents = document.querySelectorAll('.menu-content');
    const mobileMenuToggle = document.getElementById('mobileMenuToggle');
    const mobileSidebarOverlay = document.getElementById('mobileSidebarOverlay');
    const sidebarContainer = document.querySelector('.sidebar-container');

    // Debug element existence
    console.log('Mobile menu toggle found:', !!mobileMenuToggle);
    console.log('Sidebar container found:', !!sidebarContainer);
    console.log('Mobile overlay found:', !!mobileSidebarOverlay);

    let activeMenu = null;
    let isMobileSidebarOpen = false;

    // Icon button click handler for desktop
    if (iconButtons.length > 0 && fullSidebar) {
        iconButtons.forEach(button => {
            button.addEventListener('click', function () {
                const menuName = this.dataset.menu;

                // If clicking the same menu that's already active, toggle the sidebar
                if (activeMenu === menuName && fullSidebar.classList.contains('w-56')) {
                    fullSidebar.classList.remove('w-56');
                    fullSidebar.classList.add('w-0');
                } else {
                    // Show the sidebar and set the active menu
                    activeMenu = menuName;
                    showMenu(activeMenu);

                    // Expand the sidebar if it's collapsed
                    if (!fullSidebar.classList.contains('w-56')) {
                        fullSidebar.classList.remove('w-0');
                        fullSidebar.classList.add('w-56');
                    }
                }
            });
        });
    }

    // Function to show the selected menu content
    function showMenu(menuName) {
        if (!menuName) return;

        menuContents.forEach(content => {
            if (content.dataset.menuContent === menuName) {
                content.classList.remove('hidden');
            } else {
                content.classList.add('hidden');
            }
        });
    }

    // Mobile menu toggle - FOCUS ON THIS PART

    if (mobileMenuToggle && sidebarContainer) {
        console.log('Setting up mobile menu toggle');

        $(document).on('click', '#mobileMenuToggle', function (e) {
            e.preventDefault();
            console.log('Mobile menu toggle clicked');

            isMobileSidebarOpen = !isMobileSidebarOpen;
            console.log('Mobile sidebar open:', isMobileSidebarOpen);

            if (isMobileSidebarOpen) {
                // Open the sidebar
                sidebarContainer.classList.add('active');
                mobileSidebarOverlay.classList.remove('hidden');
                mobileSidebarOverlay.classList.add('active');

                // Make sure the full sidebar is expanded
                if (fullSidebar) {
                    fullSidebar.classList.remove('w-0');
                    fullSidebar.classList.add('w-56');
                }

                // Change the toggle icon
                this.innerHTML = '<span class="iconify w-6 h-6" data-icon="lucide:x"></span>';

                // Prevent body scrolling
                document.body.style.overflow = 'hidden';
            } else {
                // Close the sidebar
                sidebarContainer.classList.remove('active');
                mobileSidebarOverlay.classList.add('hidden');
                mobileSidebarOverlay.classList.remove('active');

                // Change the toggle icon back
                this.innerHTML = '<span class="iconify w-6 h-6" data-icon="lucide:menu"></span>';

                // Restore body scrolling
                document.body.style.overflow = '';
            }
        });

    } else {
        // console.error('Mobile menu elements not found');
    }
});
