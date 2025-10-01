/**
 * Sidenav Burger Menu Handler
 * Handles the responsive behavior of the sidebar navigation
 */
(function() {
    'use strict';

    // Wait for DOM to be fully loaded
    document.addEventListener('DOMContentLoaded', function() {
        // Get required DOM elements
        const sidenav = document.querySelector("aside");
        const sidenavTrigger = document.querySelector("[sidenav-trigger]");
        const sidenavCloseButton = document.querySelector("[sidenav-close]");

        // Check if required elements exist
        if (!sidenav || !sidenavTrigger) {
            console.warn('Sidenav elements not found, skipping initialization');
            return;
        }

        // Get burger menu elements
        const burger = sidenavTrigger.firstElementChild;
        if (!burger) {
            console.warn('Burger menu element not found');
            return;
        }

        const topBread = burger.firstElementChild;
        const bottomBread = burger.lastElementChild;

        /**
         * Toggle sidenav visibility
         */
        function toggleSidenav() {
            sidenavCloseButton.classList.toggle("hidden");
            sidenav.classList.toggle("translate-x-0");
            sidenav.classList.toggle("shadow-soft-xl");

            // Animate burger menu
            if (topBread && bottomBread) {
                topBread.classList.toggle("translate-x-[5px]");
                bottomBread.classList.toggle("translate-x-[5px]");
            }
        }

        // Attach event listeners
        sidenavTrigger.addEventListener("click", function(e) {
            e.preventDefault();
            toggleSidenav();
        });

        sidenavCloseButton.addEventListener("click", function() {
            toggleSidenav();
        });

        // Close sidenav when clicking outside
        document.addEventListener("click", function(e) {
            if (!sidenav.contains(e.target) && !sidenavTrigger.contains(e.target)) {
                if (sidenav.classList.contains("translate-x-0")) {
                    toggleSidenav();
                }
            }
        });

        // Close sidenav on escape key
        document.addEventListener("keydown", function(e) {
            if (e.key === "Escape" && sidenav.classList.contains("translate-x-0")) {
                toggleSidenav();
            }
        });
    });
})();
