import { Notyf } from 'notyf';
import 'notyf/notyf.min.css';

// Create an instance of Notyf
window.notyf = new Notyf({
    duration: 3000,
    ripple: true,
    dismissible: true,
    position: { x: 'right', y: 'top' },
    types: [
        {
            type: 'warning',
            className: 'text-xs',
            background: 'orange',
            icon: {
                className: 'material-icons',
                tagName: 'i',
            }
        },
    ]
    // className: 'bg-red-200'
});

