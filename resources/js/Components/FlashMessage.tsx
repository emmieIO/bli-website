import { usePage } from '@inertiajs/react';
import { useEffect, useState } from 'react';
import { X } from 'lucide-react';

interface FlashMessage {
    success?: string;
    danger?: string;
    warning?: string;
    info?: string;
}

export default function FlashMessage() {
    const { props } = usePage();
    const [visible, setVisible] = useState(true);
    const [flash, setFlash] = useState<FlashMessage>({});

    useEffect(() => {
        const { success, danger, warning, info } = (props.flash as FlashMessage) || {};
        setFlash({ success, danger, warning, info });
        setVisible(true);

        if (success || danger || warning || info) {
            const timer = setTimeout(() => {
                setVisible(false);
            }, 5000); // Hide after 5 seconds
            return () => clearTimeout(timer);
        }
    }, [props.flash]);

    if (!visible || (!flash.success && !flash.danger && !flash.warning && !flash.info)) {
        return null;
    }

    const message = flash.success || flash.danger || flash.warning || flash.info;
    const type = flash.success ? 'success' : flash.danger ? 'danger' : flash.warning ? 'warning' : 'info';

    const baseClasses = 'fixed top-5 right-5 z-50 px-6 py-4 rounded-lg shadow-lg flex items-center';
    const typeClasses = {
        success: 'bg-green-500 text-white',
        danger: 'bg-red-500 text-white',
        warning: 'bg-yellow-500 text-black',
        info: 'bg-blue-500 text-white',
    };

    return (
        <div className={`${baseClasses} ${typeClasses[type]}`}>
            <span className="mr-4">{message}</span>
            <button onClick={() => setVisible(false)} className="ml-auto">
                <X size={20} />
            </button>
        </div>
    );
}
