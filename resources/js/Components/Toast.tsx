import { useEffect } from 'react';
import toast, { Toaster } from 'react-hot-toast';
import { usePage } from '@inertiajs/react';

export function ToastContainer() {
    return (
        <Toaster
            position="top-right"
            reverseOrder={false}
            gutter={8}
            toastOptions={{
                duration: 4000,
                style: {
                    background: '#fff',
                    color: '#363636',
                    padding: '16px',
                    borderRadius: '8px',
                    boxShadow: '0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06)',
                },
                success: {
                    iconTheme: {
                        primary: '#10b981',
                        secondary: '#fff',
                    },
                    style: {
                        border: '1px solid #10b981',
                    },
                },
                error: {
                    iconTheme: {
                        primary: '#ef4444',
                        secondary: '#fff',
                    },
                    style: {
                        border: '1px solid #ef4444',
                    },
                },
            }}
        />
    );
}

export function useToastNotifications() {
    const { flash } = usePage().props as any;

    useEffect(() => {
        if (flash?.message) {
            const type = flash.type || 'info';

            switch (type) {
                case 'success':
                    toast.success(flash.message);
                    break;
                case 'error':
                    toast.error(flash.message);
                    break;
                case 'warning':
                    toast(flash.message, {
                        icon: '⚠️',
                        style: {
                            border: '1px solid #f59e0b',
                        },
                    });
                    break;
                case 'info':
                    toast(flash.message, {
                        icon: 'ℹ️',
                        style: {
                            border: '1px solid #3b82f6',
                        },
                    });
                    break;
                default:
                    toast(flash.message);
            }
        }
    }, [flash]);
}
