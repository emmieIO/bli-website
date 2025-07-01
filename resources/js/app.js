import './bootstrap';
import { createIcons, icons } from 'lucide';
import { Notyf } from 'notyf';
import 'notyf/notyf.min.css';

// Create an instance of Notyf
window.notyf = new Notyf({
    duration:4000,
    ripple: true,
    position:{x:'right', y:'top'}
});



createIcons({ icons });
