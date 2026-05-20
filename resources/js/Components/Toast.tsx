import { useEffect } from 'react';
import toast, { Toaster } from 'react-hot-toast';
import { usePage } from '@inertiajs/react';
import { X } from 'lucide-react';

export function ToastContainer() {
    return (
        <Toaster
            position="bottom-center"
            reverseOrder={false}
            gutter={8}
            toastOptions={{
                duration: 5000,
                style: {
                    background: '#ffffff',
                    color: '#0f172a',
                    padding: '12px 14px',
                    borderRadius: '10px',
                    border: '1px solid #e2e8f0',
                    boxShadow: '0 10px 30px -18px rgba(15, 23, 42, 0.28)',
                },
                success: {
                    iconTheme: {
                        primary: '#16a34a',
                        secondary: '#fff',
                    },
                    style: {
                        border: '1px solid #86efac',
                        background: '#f0fdf4',
                        color: '#14532d',
                    },
                },
                error: {
                    iconTheme: {
                        primary: '#ef4444',
                        secondary: '#fff',
                    },
                    style: {
                        border: '1px solid #fecaca',
                        background: '#fef2f2',
                        color: '#7f1d1d',
                    },
                },
            }}
        />
    );
}

function renderToastMessage(message: string, id: string) {
    return (
        <div className="flex items-start gap-3">
            <div className="min-w-0 flex-1 text-sm font-medium leading-5">
                {message}
            </div>
            <button
                type="button"
                onClick={() => toast.dismiss(id)}
                className="inline-flex h-5 w-5 shrink-0 items-center justify-center rounded text-slate-400 transition hover:text-slate-700"
                aria-label="Dismiss notification"
            >
                <X className="h-4 w-4" />
            </button>
        </div>
    );
}

export function useToastNotifications() {
    const { flash } = usePage().props as any;

    useEffect(() => {
        if (flash?.message) {
            const type = flash.type || 'info';
            const id = `${type}-${flash.message}`;

            switch (type) {
                case 'success':
                    toast.success((t) => renderToastMessage(flash.message, t.id), { id });
                    break;
                case 'error':
                    toast.error((t) => renderToastMessage(flash.message, t.id), { id });
                    break;
                case 'warning':
                    toast((t) => renderToastMessage(flash.message, t.id), {
                        id,
                        icon: '⚠️',
                        style: {
                            border: '1px solid #fcd34d',
                            background: '#fffbeb',
                            color: '#78350f',
                        },
                    });
                    break;
                case 'info':
                    toast((t) => renderToastMessage(flash.message, t.id), {
                        id,
                        icon: 'ℹ️',
                        style: {
                            border: '1px solid #93c5fd',
                            background: '#eff6ff',
                            color: '#1e3a8a',
                        },
                    });
                    break;
                default:
                    toast((t) => renderToastMessage(flash.message, t.id), { id });
            }
        }
    }, [flash]);
}
