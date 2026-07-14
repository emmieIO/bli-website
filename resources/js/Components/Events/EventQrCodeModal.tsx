import { Check, Copy, Download, ExternalLink, QrCode, X } from 'lucide-react';
import QRCode from 'qrcode';
import { useEffect, useState } from 'react';

interface EventQrCodeModalProps {
    eventTitle: string;
    eventSlug: string;
    publicUrl: string;
    onClose: () => void;
}

export default function EventQrCodeModal({ eventTitle, eventSlug, publicUrl, onClose }: EventQrCodeModalProps) {
    const [qrCode, setQrCode] = useState('');
    const [error, setError] = useState('');
    const [copied, setCopied] = useState(false);

    useEffect(() => {
        let active = true;

        QRCode.toDataURL(publicUrl, {
            width: 1024,
            margin: 4,
            errorCorrectionLevel: 'H',
            color: {
                dark: '#0B2D6B',
                light: '#FFFFFF',
            },
        })
            .then((dataUrl) => {
                if (active) setQrCode(dataUrl);
            })
            .catch(() => {
                if (active) setError('The QR code could not be generated. Please close this window and try again.');
            });

        return () => {
            active = false;
        };
    }, [publicUrl]);

    const copyLink = async () => {
        await navigator.clipboard.writeText(publicUrl);
        setCopied(true);
        window.setTimeout(() => setCopied(false), 1800);
    };

    return (
        <div className="fixed inset-0 z-50 flex items-end justify-center bg-slate-950/60 px-3 py-3 sm:items-center sm:px-6 sm:py-8">
            <div
                role="dialog"
                aria-modal="true"
                aria-labelledby="event-qr-title"
                className="max-h-full w-full max-w-2xl overflow-y-auto rounded-lg bg-white shadow-2xl"
            >
                <div className="flex items-start justify-between gap-4 border-b border-slate-200 px-5 py-5 sm:px-7">
                    <div className="flex min-w-0 items-center gap-3">
                        <span className="flex h-10 w-10 shrink-0 items-center justify-center rounded-md bg-primary text-white">
                            <QrCode size={20} />
                        </span>
                        <div className="min-w-0">
                            <p className="text-xs font-semibold uppercase text-slate-400">Flyer asset</p>
                            <h2 id="event-qr-title" className="mt-1 truncate text-lg font-semibold text-slate-900">Event QR code</h2>
                        </div>
                    </div>
                    <button
                        type="button"
                        onClick={onClose}
                        title="Close QR code"
                        aria-label="Close QR code"
                        className="inline-flex h-9 w-9 shrink-0 items-center justify-center rounded-md text-slate-500 transition hover:bg-slate-100 hover:text-slate-900"
                    >
                        <X size={18} />
                    </button>
                </div>

                <div className="grid gap-6 px-5 py-6 sm:grid-cols-[minmax(0,280px)_minmax(0,1fr)] sm:px-7">
                    <div className="mx-auto flex aspect-square w-full max-w-[280px] items-center justify-center border border-slate-200 bg-white p-3">
                        {qrCode ? (
                            <img src={qrCode} alt={`QR code for ${eventTitle}`} className="h-full w-full object-contain" />
                        ) : error ? (
                            <p className="px-4 text-center text-sm leading-6 text-red-600">{error}</p>
                        ) : (
                            <div className="flex items-center gap-2 text-sm text-slate-500">
                                <span className="h-4 w-4 animate-spin rounded-full border-2 border-slate-300 border-t-primary" />
                                Generating code
                            </div>
                        )}
                    </div>

                    <div className="flex min-w-0 flex-col justify-between gap-6">
                        <div>
                            <p className="text-xs font-semibold uppercase text-slate-400">Destination</p>
                            <p className="mt-2 text-base font-semibold leading-6 text-slate-900">{eventTitle}</p>
                            <p className="mt-2 break-all text-sm leading-6 text-slate-500">{publicUrl}</p>
                        </div>

                        <div className="space-y-3">
                            <button
                                type="button"
                                onClick={copyLink}
                                className="inline-flex w-full items-center justify-center gap-2 rounded-md border border-slate-300 px-4 py-2.5 text-sm font-semibold text-slate-700 transition hover:bg-slate-50"
                            >
                                {copied ? <Check size={17} /> : <Copy size={17} />}
                                {copied ? 'Link copied' : 'Copy event link'}
                            </button>
                            <a
                                href={publicUrl}
                                target="_blank"
                                rel="noreferrer"
                                className="inline-flex w-full items-center justify-center gap-2 rounded-md border border-slate-300 px-4 py-2.5 text-sm font-semibold text-slate-700 transition hover:bg-slate-50"
                            >
                                <ExternalLink size={17} />
                                Test destination
                            </a>
                            {qrCode && (
                                <a
                                    href={qrCode}
                                    download={`${eventSlug}-event-qr.png`}
                                    className="inline-flex w-full items-center justify-center gap-2 rounded-md bg-primary px-4 py-2.5 text-sm font-semibold text-white transition hover:bg-primary-600"
                                >
                                    <Download size={17} />
                                    Download PNG
                                </a>
                            )}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    );
}
