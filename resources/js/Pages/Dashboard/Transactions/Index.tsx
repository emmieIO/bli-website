import { Head, Link, usePage } from '@inertiajs/react';
import DashboardLayout from '@/Layouts/DashboardLayout';
import { Receipt, CreditCard, Package, CheckCircle, XCircle, Clock, Wallet, FileText } from 'lucide-react';
import { useState } from 'react';

interface Course {
    id: number;
    title: string;
    slug: string;
    thumbnail_path: string | null;
}

interface CartItem {
    course_id: number;
    course_title: string;
    price: number;
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
    type: 'course' | 'cart';
    course: Course | null;
    items: CartItem[] | null;
    item_count: number;
}

interface Payout {
    id: number;
    payout_reference: string;
    amount: number;
    currency: string;
    status: string;
    requested_at: string;
    completed_at: string | null;
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
    payouts: PaginatedData<Payout>;
}

export default function Index({ transactions, payouts }: Props) {
    const { sideLinks } = usePage().props as any;
    const [activeTab, setActiveTab] = useState<'purchases' | 'payouts'>('purchases');

    const formatCurrency = (amount: number, currency: string) => {
        const currencySymbols: { [key: string]: string } = {
            'USD': '$',
            'NGN': '₦',
            'GBP': '£',
            'EUR': '€',
        };
        const symbol = currencySymbols[currency] || currency;
        return `${symbol}${Number(amount).toFixed(2)}`;
    };

    const formatDate = (dateString: string) => {
        return new Date(dateString).toLocaleDateString('en-US', {
            year: 'numeric',
            month: 'short',
            day: 'numeric',
        });
    };

    const getStatusBadge = (status: string) => {
        switch (status) {
            case 'successful':
            case 'completed':
                return (
                    <span className="inline-flex items-center gap-1 px-3 py-1 bg-green-100 text-green-800 text-xs font-medium rounded-full">
                        <CheckCircle className="w-3 h-3" />
                        Successful
                    </span>
                );
            case 'failed':
            case 'cancelled':
                return (
                    <span className="inline-flex items-center gap-1 px-3 py-1 bg-red-100 text-red-800 text-xs font-medium rounded-full">
                        <XCircle className="w-3 h-3" />
                        {status}
                    </span>
                );
            case 'pending':
            case 'processing':
                return (
                    <span className="inline-flex items-center gap-1 px-3 py-1 bg-yellow-100 text-yellow-800 text-xs font-medium rounded-full">
                        <Clock className="w-3 h-3" />
                        {status}
                    </span>
                );
            default:
                return null;
        }
    };

    return (
        <DashboardLayout sideLinks={sideLinks}>
            <Head title="Transaction History" />

            <div className="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
                {/* Header */}
                <div className="mb-8">
                    <div className="flex items-center gap-3 mb-2">
                        <Receipt className="w-8 h-8 text-primary" />
                        <h1 className="text-3xl font-bold text-gray-900 font-montserrat">
                            Transaction History
                        </h1>
                    </div>
                    <p className="text-gray-600 font-lato">
                        View your course purchases and payout history
                    </p>
                </div>

                {/* Tabs */}
                {payouts.data.length > 0 && (
                    <div className="mb-6 border-b border-gray-200">
                        <nav className="-mb-px flex space-x-8">
                            <button
                                onClick={() => setActiveTab('purchases')}
                                className={`py-4 px-1 border-b-2 font-medium text-sm font-montserrat flex items-center gap-2 ${
                                    activeTab === 'purchases'
                                        ? 'border-primary text-primary'
                                        : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300'
                                }`}
                            >
                                <CreditCard className="w-4 h-4" />
                                My Purchases
                            </button>
                            <button
                                onClick={() => setActiveTab('payouts')}
                                className={`py-4 px-1 border-b-2 font-medium text-sm font-montserrat flex items-center gap-2 ${
                                    activeTab === 'payouts'
                                        ? 'border-primary text-primary'
                                        : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300'
                                }`}
                            >
                                <Wallet className="w-4 h-4" />
                                Instructor Payouts
                            </button>
                        </nav>
                    </div>
                )}

                {activeTab === 'purchases' && (
                    <>
                        {transactions.data.length === 0 ? (
                            /* Empty State */
                            <div className="bg-white rounded-xl shadow-sm border border-gray-200 p-12 text-center">
                                <div className="max-w-md mx-auto">
                                    <div className="mb-6">
                                        <Receipt className="w-24 h-24 text-gray-300 mx-auto" strokeWidth={1} />
                                    </div>
                                    <h2 className="text-2xl font-bold text-gray-900 mb-3 font-montserrat">
                                        No transactions yet
                                    </h2>
                                    <p className="text-gray-600 mb-6 font-lato">
                                        Your purchase history will appear here once you enroll in courses.
                                    </p>
                                    <Link
                                        href={route('courses.index')}
                                        className="inline-flex items-center gap-2 px-6 py-3 bg-linear-to-r from-primary to-primary-600 text-white rounded-lg font-semibold font-montserrat hover:from-primary-600 hover:to-primary-700 transition-all duration-300 shadow-md hover:shadow-lg"
                                    >
                                        Browse Courses
                                    </Link>
                                </div>
                            </div>
                        ) : (
                            <div className="space-y-4">
                                {transactions.data.map((transaction) => (
                                    <div
                                        key={transaction.id}
                                        className="bg-white rounded-xl shadow-sm border border-gray-200 p-6 hover:shadow-md transition-shadow"
                                    >
                                        <div className="flex flex-col md:flex-row md:items-center justify-between gap-4">
                                            {/* Transaction Info */}
                                            <div className="flex-1">
                                                <div className="flex items-start gap-4">
                                                    {/* Icon */}
                                                    <div className="shrink-0">
                                                        {transaction.type === 'cart' ? (
                                                            <Package className="w-10 h-10 text-primary" />
                                                        ) : (
                                                            <CreditCard className="w-10 h-10 text-primary" />
                                                        )}
                                                    </div>

                                                    {/* Details */}
                                                    <div className="grow">
                                                        <h3 className="text-lg font-bold text-gray-900 font-montserrat mb-1">
                                                            {transaction.type === 'cart'
                                                                ? `Cart Purchase - ${transaction.item_count} Course${transaction.item_count > 1 ? 's' : ''}`
                                                                : transaction.course?.title || 'Course Purchase'}
                                                        </h3>

                                                        {/* Cart Items */}
                                                        {transaction.type === 'cart' && transaction.items && (
                                                            <div className="mt-2 space-y-1">
                                                                {transaction.items.map((item, idx) => (
                                                                    <p key={idx} className="text-sm text-gray-600 font-lato">
                                                                        • {item.course_title} - {formatCurrency(item.price, transaction.currency)}
                                                                    </p>
                                                                ))}
                                                            </div>
                                                        )}

                                                        {/* Single Course Link */}
                                                        {transaction.type === 'course' && transaction.course && (
                                                            <Link
                                                                href={route('courses.show', transaction.course.slug)}
                                                                className="text-sm text-primary hover:text-primary-600 font-lato"
                                                            >
                                                                View Course →
                                                            </Link>
                                                        )}

                                                        <div className="mt-3 flex flex-wrap items-center gap-4 text-sm text-gray-600 font-lato">
                                                            <span>ID: {transaction.transaction_id}</span>
                                                            <span>•</span>
                                                            <span>{transaction.created_at}</span>
                                                            {transaction.payment_type && (
                                                                <>
                                                                    <span>•</span>
                                                                    <span className="capitalize">{transaction.payment_type}</span>
                                                                </>
                                                            )}
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>

                                            {/* Amount & Status */}
                                            <div className="shrink-0 text-right space-y-2">
                                                <p className="text-2xl font-bold text-gray-900 font-montserrat mb-2">
                                                    {formatCurrency(transaction.amount, transaction.currency)}
                                                </p>
                                                {getStatusBadge(transaction.status)}

                                                {/* Complete Payment Button for Pending Transactions */}
                                                {transaction.status === 'pending' && transaction.payment_ref && (
                                                    <Link
                                                        href={route('payment.verify', transaction.payment_ref)}
                                                        className="inline-flex items-center gap-2 px-4 py-2 bg-primary text-white text-sm font-medium rounded-lg hover:bg-primary-600 transition-colors shadow-sm"
                                                    >
                                                        <CreditCard className="w-4 h-4" />
                                                        Complete Payment
                                                    </Link>
                                                )}
                                                {transaction.status === 'successful' && (
                                                    <Link
                                                        href={route('transactions.show-receipt', transaction.id)}
                                                        target="_blank"
                                                        className="inline-flex items-center gap-2 px-4 py-2 bg-blue-500 text-white text-sm font-medium rounded-lg hover:bg-blue-600 transition-colors shadow-sm"
                                                    >
                                                        <FileText className="w-4 h-4" />
                                                        View Receipt
                                                    </Link>
                                                )}
                                            </div>
                                        </div>
                                    </div>
                                ))}

                                {/* Pagination */}
                                {transactions.last_page > 1 && (
                                    <div className="flex justify-center mt-8">
                                        <nav className="flex items-center gap-2">
                                            {transactions.links.map((link, index) => (
                                                <Link
                                                    key={index}
                                                    href={link.url || '#'}
                                                    preserveScroll
                                                    className={`px-4 py-2 text-sm font-medium rounded-lg transition-colors ${
                                                        link.active
                                                            ? 'bg-primary text-white'
                                                            : link.url
                                                            ? 'bg-white text-gray-700 border border-gray-300 hover:bg-gray-50'
                                                            : 'bg-gray-100 text-gray-400 cursor-not-allowed'
                                                    }`}
                                                    dangerouslySetInnerHTML={{ __html: link.label }}
                                                />
                                            ))}
                                        </nav>
                                    </div>
                                )}
                            </div>
                        )}
                    </>
                )}

                {activeTab === 'payouts' && (
                    <div className="space-y-4">
                        {payouts.data.map((payout) => (
                             <div
                                key={payout.id}
                                className="bg-white rounded-xl shadow-sm border border-gray-200 p-6 hover:shadow-md transition-shadow"
                            >
                                <div className="flex items-center justify-between">
                                    <div>
                                        <h3 className="text-lg font-bold text-gray-900 font-montserrat mb-1">
                                            Payout Request
                                        </h3>
                                        <div className="text-sm text-gray-600 font-lato">
                                            Ref: {payout.payout_reference}
                                        </div>
                                        <div className="text-sm text-gray-500 font-lato mt-1">
                                            Requested: {formatDate(payout.requested_at)}
                                        </div>
                                    </div>
                                    <div className="text-right space-y-2">
                                        <p className="text-2xl font-bold text-gray-900 font-montserrat">
                                            {formatCurrency(payout.amount, payout.currency)}
                                        </p>
                                        {getStatusBadge(payout.status)}
                                    </div>
                                </div>
                            </div>
                        ))}

                        {/* Pagination for Payouts */}
                        {payouts.last_page > 1 && (
                            <div className="flex justify-center mt-8">
                                <nav className="flex items-center gap-2">
                                    {payouts.links.map((link, index) => (
                                        <Link
                                            key={index}
                                            href={link.url || '#'}
                                            preserveScroll
                                            className={`px-4 py-2 text-sm font-medium rounded-lg transition-colors ${
                                                link.active
                                                    ? 'bg-primary text-white'
                                                    : link.url
                                                    ? 'bg-white text-gray-700 border border-gray-300 hover:bg-gray-50'
                                                    : 'bg-gray-100 text-gray-400 cursor-not-allowed'
                                            }`}
                                            dangerouslySetInnerHTML={{ __html: link.label }}
                                        />
                                    ))}
                                </nav>
                            </div>
                        )}

                        {payouts.data.length === 0 && (
                             <div className="bg-white rounded-xl shadow-sm border border-gray-200 p-12 text-center">
                                <p className="text-gray-600 font-lato">No payout history available.</p>
                             </div>
                        )}
                    </div>
                )}
            </div>
        </DashboardLayout>
    );
}
