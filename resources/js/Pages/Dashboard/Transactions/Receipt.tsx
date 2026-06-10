import { Head, usePage } from '@inertiajs/react';
import { PageProps } from '@/types';
import { PrinterIcon } from 'lucide-react';
import { useEffect } from 'react';

interface Transaction {
    id: number;
    transaction_id: string;
    payment_ref: string | null;
    amount: number;
    currency: string;
    status: 'pending' | 'successful' | 'failed';
    payment_type: string | null;
    created_at: string;
    paid_at: string | null;
    metadata: any;
    user: {
        name: string;
        email: string;
    };
    event: {
        title: string;
    } | null;
}

interface CompanyDetails {
    name: string;
    address: string;
    email: string;
    phone: string;
}

interface Props extends PageProps {
    transaction: Transaction;
    companyDetails: CompanyDetails;
}

export default function Receipt() {
    const { transaction, companyDetails } = usePage<Props>().props;

    const formatCurrency = (amount: number, currency: string) => {
        return new Intl.NumberFormat('en-US', {
            style: 'currency',
            currency: currency || 'USD',
        }).format(amount);
    };

    const formatDate = (dateString: string | null) => {
        if (!dateString) return 'N/A';
        return new Date(dateString).toLocaleDateString('en-US', {
            year: 'numeric',
            month: 'long',
            day: 'numeric',
            hour: '2-digit',
            minute: '2-digit',
            second: '2-digit',
            hour12: true,
        });
    };

    const itemTitle = transaction.event?.title ?? transaction.metadata?.subject_title ?? 'Event Payment';

    useEffect(() => {
        const style = document.createElement('style');
        style.innerHTML = `
            @media print {
                body { margin: 0; padding: 0; -webkit-print-color-adjust: exact; print-color-adjust: exact; }
                .no-print { display: none !important; }
                .receipt-container { box-shadow: none !important; border: none !important; margin: 0; padding: 0; }
            }
        `;
        document.head.appendChild(style);
        return () => { document.head.removeChild(style); };
    }, []);

    return (
        <>
            <Head title={`Receipt #${transaction.transaction_id}`} />
            <div className="min-h-screen bg-slate-100 p-4 print:bg-white">
                <div className="mx-auto max-w-3xl rounded-lg border border-slate-200 bg-white shadow-sm receipt-container print:shadow-none print:rounded-none">
                    <div className="flex justify-end p-4 no-print border-b border-slate-100">
                        <button
                            onClick={() => window.print()}
                            className="inline-flex items-center gap-2 rounded-md bg-primary px-4 py-2 text-xs font-semibold uppercase tracking-wider text-white transition hover:bg-primary-600"
                        >
                            <PrinterIcon size={15} />
                            Print Receipt
                        </button>
                    </div>

                    <div className="border-b border-slate-200 p-6 lg:p-8">
                        <div className="flex flex-col gap-6 sm:flex-row sm:items-start sm:justify-between">
                            <div>
                                <div className="flex items-center gap-3 mb-3">
                                    <img src="/assets/img/logo.jpg" alt="BLI" className="h-12 w-12 rounded-lg object-cover shadow-sm" />
                                    <div>
                                        <h2 className="text-base font-semibold text-slate-900">{companyDetails.name}</h2>
                                        <p className="text-xs text-slate-500">#{transaction.transaction_id}</p>
                                    </div>
                                </div>
                                <p className="text-xs text-slate-500">#{transaction.transaction_id}</p>
                            </div>
                            <div className="sm:text-right">
                                <p className="text-xs text-slate-500">{companyDetails.address}</p>
                                <p className="text-xs text-slate-500">{companyDetails.email}</p>
                                <p className="text-xs text-slate-500">{companyDetails.phone}</p>
                            </div>
                        </div>

                        <div className="mt-6 grid gap-4 sm:grid-cols-2">
                            <div>
                                <h3 className="text-sm font-semibold text-slate-900">Billed To</h3>
                                <p className="mt-1 text-sm text-slate-700">{transaction.user.name}</p>
                                <p className="text-xs text-slate-500">{transaction.user.email}</p>
                            </div>
                            <div className="sm:text-right">
                                <h3 className="text-sm font-semibold text-slate-900">Receipt Date</h3>
                                <p className="mt-1 text-sm text-slate-700">{formatDate(transaction.created_at)}</p>
                                {transaction.paid_at && (
                                    <>
                                        <h3 className="mt-3 text-sm font-semibold text-slate-900">Paid Date</h3>
                                        <p className="mt-1 text-sm text-slate-700">{formatDate(transaction.paid_at)}</p>
                                    </>
                                )}
                            </div>
                        </div>
                    </div>

                    <div className="p-6 lg:p-8">
                        <table className="min-w-full">
                            <thead>
                                <tr className="border-b border-slate-200">
                                    <th className="pb-3 text-left text-[11px] font-semibold uppercase tracking-wider text-slate-500">Event</th>
                                    <th className="pb-3 text-right text-[11px] font-semibold uppercase tracking-wider text-slate-500">Amount</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr className="border-b border-slate-100">
                                    <td className="py-3 text-sm font-medium text-slate-900">{itemTitle}</td>
                                    <td className="py-3 text-right text-sm text-slate-900">{formatCurrency(transaction.amount, transaction.currency)}</td>
                                </tr>
                            </tbody>
                            <tfoot>
                                <tr className="border-t-2 border-slate-900">
                                    <td className="pt-3 text-right text-sm font-semibold uppercase tracking-wider text-slate-900">Total</td>
                                    <td className="pt-3 text-right text-sm font-semibold text-slate-900">{formatCurrency(transaction.amount, transaction.currency)}</td>
                                </tr>
                            </tfoot>
                        </table>

                        <div className="mt-6 space-y-1 text-xs text-slate-500">
                            <p>
                                <strong className="text-slate-700">Payment Status:</strong>{' '}
                                <span className={`font-semibold ${
                                    transaction.status === 'successful' ? 'text-lime-600' :
                                    transaction.status === 'failed' ? 'text-accent' :
                                    'text-amber-600'
                                }`}>
                                    {transaction.status.toUpperCase()}
                                </span>
                            </p>
                            {transaction.payment_type && <p><strong className="text-slate-700">Payment Method:</strong> {transaction.payment_type}</p>}
                            {transaction.payment_ref && <p><strong className="text-slate-700">Payment Reference:</strong> {transaction.payment_ref}</p>}
                            <p className="pt-3 text-slate-400">Thank you for your business!</p>
                        </div>
                    </div>
                </div>
            </div>
        </>
    );
}
