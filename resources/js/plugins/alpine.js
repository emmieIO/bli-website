import Alpine from 'alpinejs';
import collapse from '@alpinejs/collapse'

window.Alpine = Alpine;
Alpine.plugin(collapse)


document.addEventListener('DOMContentLoaded', function(){
    Alpine.start();
})
