import './bootstrap';
import { createIcons, icons } from 'lucide';
import { Notyf } from 'notyf';
import 'notyf/notyf.min.css';
import Alpine from 'alpinejs';
import 'flowbite';

createIcons({ icons });

// Create an instance of Notyf
window.notyf = new Notyf({
    duration: 4000,
    ripple: true,
    position: { x: 'right', y: 'bottom' },
    className: 'bg-red-200'
});
window.Alpine = Alpine;

// Start Alpine when DOM is loaded
document.addEventListener('DOMContentLoaded', function () {
    Alpine.start();

    // Handle menu toggle
    const dashboardLayout = document.querySelector(".dashboard_layout");
    const menuToggleBtn = document.querySelector('.toggle-btn');

    if (menuToggleBtn) {
        menuToggleBtn.addEventListener('click', function () {
            dashboardLayout.classList.toggle("toggle-sidebar");
        });
    }
});

// Handle preloader when everything is loaded
window.addEventListener('load', function () {
    const preload = document.querySelector('.preload');
    if (preload) {
        // Add hide class
        preload.classList.add('hide-preload');

        // Optional: Remove preload element from DOM after animation completes
        setTimeout(() => {
            preload.remove();
        }, 500); // Match this with your CSS transition duration
    }
});
