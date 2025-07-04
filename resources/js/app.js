import './bootstrap';
import { createIcons, icons } from 'lucide';
import { Notyf } from 'notyf';
import 'notyf/notyf.min.css';
import Alpine from 'alpinejs'

createIcons({ icons });

// Create an instance of Notyf
window.notyf = new Notyf({
    duration: 4000,
    ripple: true,
    position: { x: 'right', y: 'top' }
});
window.Alpine = Alpine

document.addEventListener('DOMContentLoaded', function(){
    Alpine.start();
    const dashboardLayout = document.querySelector(".dashboard_layout");
    const menuToggleBtn = document.querySelector('.toggle-btn');

    if (menuToggleBtn) {
        menuToggleBtn.addEventListener('click', function () {
            dashboardLayout.classList.toggle("toggle-sidebar")
        });
    } 

})
