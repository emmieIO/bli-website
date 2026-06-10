import { Head, Link, usePage } from '@inertiajs/react';
import DashboardLayout from '@/Layouts/DashboardLayout';
import { CalendarDays, CheckCircle, Clock, CreditCard, FileText, Receipt, XCircle } from 'lucide-react';

interface EventItem {
    id: number;
    title: string;
    slug: string;
}

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
    type: 'event' | 'payment';
    event: EventItem | null;
}

interface PaginatedData<T> {
    data: T[];
    current_page: number;
    last_page: number;
    per_page: number;
    total: number;
    links: Array<{
        url: string | null;
        label: string;
        active: boolean;
    }>;
}

interface Props {
    transactions: PaginatedData<Transaction>;
}

export default function Index({ transactions }: Props) {
    const { sideLinks } = usePage().props as any;

    const formatCurrency = (amount: number, currency: string) => {
        const currencySymbols: { [key: string]: string } = {
            USD: '$',
            NGN: '₦',
            GBP: '£',
            EUR: '€',
        };
        const symbol = currencySymbols[currency] || currency;
        return `${symbol}${Number(amount).toFixed(2)}`;
    };

    const getStatusBadge = (status: string) => {
        switch (status) {
            case 'successful':
                return (
                    <span className="inline-flex items-center gap-1 rounded-md bg-lime-100 px-2.5 py-1 text-xs font-medium text-lime-700">
                        <CheckCircle className="w-3 h-3" />
                        Successful
                    </span>
                );
            case 'failed':
                return (
                    <span className="inline-flex items-center gap-1 rounded-md bg-accent-50 px-2.5 py-1 text-xs font-medium text-accent">
                        <XCircle className="w-3 h-3" />
                        Failed
                    </span>
                );
            case 'pending':
                return (
                    <span className="inline-flex items-center gap-1 rounded-md bg-amber-50 px-2.5 py-1 text-xs font-medium text-amber-700">
                        <Clock className="w-3 h-3" />
                        Pending
                    </span>
                );
            default:
                return null;
        }
    };

    return (
        <DashboardLayout sideLinks={sideLinks}>
            <Head title="Transaction History" />

            <div className="space-y-6">
                <div>
                    <h1 className="text-xl font-semibold tracking-tight text-slate-900">Transaction History</h1>
                    <p className="mt-1 text-sm text-slate-500">View your event payment history and receipts.</p>
                </div>

                {transactions.data.length === 0 ? (
                    <div className="rounded-lg border border-slate-200 bg-white p-12 text-center">
                        <div className="mx-auto max-w-md">
                            <div className="mb-5 flex h-16 w-16 items-center justify-center rounded-lg bg-slate-100 text-slate-400 mx-auto">
                                <Receipt size={32} strokeWidth={1.5} />
                            </div>
                            <h2 className="text-lg font-semibold text-slate-900">No transactions yet</h2>
                            <p className="mt-2 text-sm text-slate-500">
                                Your payment history will appear here after you register for an event.
                            </p>
                            <Link
                                href={route('events.index')}
                                className="mt-5 inline-flex items-center gap-2 rounded-lg bg-primary px-4 py-2.5 text-sm font-medium text-white transition hover:bg-primary-600 shadow-sm"
                            >
                                <CalendarDays size={15} />
                                Browse Events
                            </Link>
                        </div>
                    </div>
                ) : (
                    <div className="space-y-3">
                        {transactions.data.map((transaction) => (
                            <div
                                key={transaction.id}
                                className="rounded-lg border border-slate-200 bg-white p-5 transition hover:border-slate-300 hover:shadow-sm"
                            >
                                <div className="flex flex-col gap-4 md:flex-row md:items-center md:justify-between">
                                    <div className="flex items-start gap-4">
                                        <span className="flex h-10 w-10 shrink-0 items-center justify-center rounded-lg bg-primary-50 text-primary">
                                            <CreditCard size={18} />
                                        </span>
                                        <div>
                                            <h3 className="text-sm font-semibold text-slate-900">
                                                {transaction.event?.title || 'Event Payment'}
                                            </h3>
                                            {transaction.event && (
                                                <Link
                                                    href={route('events.show', transaction.event.slug)}
                                                    className="text-[13px] text-primary hover:text-primary-600"
                                                >
                                                    View Event
                                                </Link>
                                            )}
                                            <div className="mt-2 flex flex-wrap items-center gap-2 text-[13px] text-slate-500">
                                                <span className="font-mono text-xs">{transaction.transaction_id}</span>
                                                <span className="text-slate-300">|</span>
                                                <span>{transaction.created_at}</span>
                                                {transaction.payment_type && (
                                                    <>
                                                        <span className="text-slate-300">|</span>
                                                        <span className="capitalize">{transaction.payment_type}</span>
                                                    </>
                                                )}
                                            </div>
                                        </div>
                                    </div>

                                    <div className="flex items-center gap-4 md:flex-col md:items-end md:gap-2">
                                        <p className="text-lg font-semibold tracking-tight text-slate-900">
                                            {formatCurrency(transaction.amount, transaction.currency)}
                                        </p>
                                        {getStatusBadge(transaction.status)}

                                        <div className="flex items-center gap-2">
                                            {transaction.status === 'pending' && transaction.payment_ref && (
                                                <Link
                                                    href={route('payment.verify', transaction.payment_ref)}
                                                    className="inline-flex items-center gap-1.5 rounded-md bg-primary px-3 py-1.5 text-xs font-medium text-white transition hover:bg-primary-600"
                                                >
                                                    <CreditCard size={13} />
                                                    Complete Payment
                                                </Link>
                                            )}
                                            {transaction.status === 'successful' && (
                                                <Link
                                                    href={route('transactions.show-receipt', transaction.id)}
                                                    target="_blank"
                                                    className="inline-flex items-center gap-1.5 rounded-md border border-slate-200 bg-white px-3 py-1.5 text-xs font-medium text-slate-700 transition hover:bg-slate-50"
                                                >
                                                    <FileText size={13} />
                                                    Receipt
                                                </Link>
                                            )}
                                        </div>
                                    </div>
                                </div>
                            </div>
                        ))}

                        {transactions.last_page > 1 && (
                            <div className="flex justify-center pt-4">
                                <nav className="flex items-center gap-1.5">
                                    {transactions.links.map((link, index) => (
                                        <Link
                                            key={index}
                                            href={link.url || '#'}
                                            preserveScroll
                                            className={`rounded-md px-3 py-1.5 text-xs font-medium transition ${
                                                link.active
                                                    ? 'bg-primary text-white'
                                                    : link.url
                                                        ? 'border border-slate-200 bg-white text-slate-700 hover:bg-slate-50'
                                                        : 'bg-slate-50 text-slate-300 cursor-not-allowed'
                                            }`}
                                            dangerouslySetInnerHTML={{ __html: link.label }}
                                        />
                                    ))}
                                </nav>
                            </div>
                        )}
                    </div>
                )}
            </div>
        </DashboardLayout>
    );
}
