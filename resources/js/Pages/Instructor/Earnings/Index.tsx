import DashboardLayout from '@/Layouts/DashboardLayout';
import { Head, Link } from '@inertiajs/react';
import { useState } from 'react';

// Utility functions
const formatCurrency = (amount: number, currency: string = 'NGN'): string => {
    return new Intl.NumberFormat('en-NG', {
        style: 'currency',
        currency: currency,
        minimumFractionDigits: 2,
    }).format(amount);
};

const formatDate = (dateString: string): string => {
    return new Date(dateString).toLocaleDateString('en-US', {
        year: 'numeric',
        month: 'short',
        day: 'numeric',
    });
};

interface Balance {
    available: number;
    pending: number;
    total_earned: number;
    total_paid: number;
}

interface Earning {
    id: number;
    gross_amount: number;
    platform_fee: number;
    net_amount: number;
    platform_fee_percentage: number;
    currency: string;
    status: 'pending' | 'available' | 'paid' | 'refunded';
    available_at: string;
    paid_at: string | null;
    created_at: string;
    course: {
        id: number;
        title: string;
    } | null;
    transaction: {
        id: number;
        transaction_id: string;
    };
}

interface Payout {
    id: number;
    payout_reference: string;
    amount: number;
    currency: string;
    status: 'pending' | 'processing' | 'completed' | 'failed' | 'cancelled';
    payout_method: string;
    requested_at: string;
    processed_at: string | null;
    completed_at: string | null;
}

interface Config {
    platform_commission: number;
    holding_period_days: number;
    minimum_payout: number;
}

interface Props {
    balance: Balance;
    earnings: {
        data: Earning[];
        current_page: number;
        last_page: number;
        per_page: number;
        total: number;
    };
    payouts: {
        data: Payout[];
        current_page: number;
        last_page: number;
    };
    config: Config;
}

export default function InstructorEarningsIndex({ balance, earnings, payouts, config }: Props) {
    const [activeTab, setActiveTab] = useState<'earnings' | 'payouts'>('earnings');

    const getStatusColor = (status: string) => {
        switch (status) {
            case 'available':
                return 'bg-green-100 text-green-800';
            case 'pending':
                return 'bg-yellow-100 text-yellow-800';
            case 'paid':
            case 'completed':
                return 'bg-blue-100 text-blue-800';
            case 'refunded':
            case 'failed':
            case 'cancelled':
                return 'bg-red-100 text-red-800';
            case 'processing':
                return 'bg-purple-100 text-purple-800';
            default:
                return 'bg-gray-100 text-gray-800';
        }
    };

    const canRequestPayout = balance.available >= config.minimum_payout;

    return (
        <DashboardLayout>
            <Head title="Earnings & Payouts" />

            {/* Page Header */}
            <div className="mb-6 flex flex-col items-start justify-between gap-4 md:flex-row md:items-center">
                <div>
                    <h1 className="font-montserrat text-2xl font-bold text-primary">Earnings & Payouts</h1>
                    <p className="font-lato text-sm text-gray-500">Track your earnings and request payouts</p>
                </div>
                {canRequestPayout && (
                    <Link
                        href="/instructor/earnings/payout"
                        className="rounded-md bg-indigo-600 px-4 py-2 text-sm font-medium text-white hover:bg-indigo-700"
                    >
                        Request Payout
                    </Link>
                )}
            </div>

            <div className="space-y-6">
                    {/* Balance Cards */}
                    <div className="mb-8 grid gap-6 md:grid-cols-4">
                        <div className="rounded-lg bg-white p-6 shadow">
                            <h3 className="text-sm font-medium text-gray-500">Available Balance</h3>
                            <p className="mt-2 text-3xl font-bold text-green-600">
                                {formatCurrency(balance.available, 'NGN')}
                            </p>
                            <p className="mt-1 text-xs text-gray-500">Ready to withdraw</p>
                        </div>

                        <div className="rounded-lg bg-white p-6 shadow">
                            <h3 className="text-sm font-medium text-gray-500">Pending</h3>
                            <p className="mt-2 text-3xl font-bold text-yellow-600">
                                {formatCurrency(balance.pending, 'NGN')}
                            </p>
                            <p className="mt-1 text-xs text-gray-500">
                                In {config.holding_period_days}-day holding period
                            </p>
                        </div>

                        <div className="rounded-lg bg-white p-6 shadow">
                            <h3 className="text-sm font-medium text-gray-500">Total Earned</h3>
                            <p className="mt-2 text-3xl font-bold text-gray-900">
                                {formatCurrency(balance.total_earned, 'NGN')}
                            </p>
                            <p className="mt-1 text-xs text-gray-500">
                                After {config.platform_commission}% commission
                            </p>
                        </div>

                        <div className="rounded-lg bg-white p-6 shadow">
                            <h3 className="text-sm font-medium text-gray-500">Total Paid Out</h3>
                            <p className="mt-2 text-3xl font-bold text-blue-600">
                                {formatCurrency(balance.total_paid, 'NGN')}
                            </p>
                            <p className="mt-1 text-xs text-gray-500">Lifetime withdrawals</p>
                        </div>
                    </div>

                    {/* Payout Notice */}
                    {!canRequestPayout && balance.available > 0 && (
                        <div className="mb-6 rounded-lg bg-yellow-50 p-4">
                            <div className="flex">
                                <div className="shrink-0">
                                    <svg
                                        className="h-5 w-5 text-yellow-400"
                                        viewBox="0 0 20 20"
                                        fill="currentColor"
                                    >
                                        <path
                                            fillRule="evenodd"
                                            d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z"
                                            clipRule="evenodd"
                                        />
                                    </svg>
                                </div>
                                <div className="ml-3">
                                    <p className="text-sm text-yellow-700">
                                        Minimum payout amount is {formatCurrency(config.minimum_payout, 'NGN')}.
                                        You need {formatCurrency(config.minimum_payout - balance.available, 'NGN')} more
                                        to request a payout.
                                    </p>
                                </div>
                            </div>
                        </div>
                    )}

                    {/* Tabs */}
                    <div className="mb-6">
                        <div className="border-b border-gray-200">
                            <nav className="-mb-px flex space-x-8">
                                <button
                                    onClick={() => setActiveTab('earnings')}
                                    className={`border-b-2 px-1 py-4 text-sm font-medium ${
                                        activeTab === 'earnings'
                                            ? 'border-indigo-500 text-indigo-600'
                                            : 'border-transparent text-gray-500 hover:border-gray-300 hover:text-gray-700'
                                    }`}
                                >
                                    Earnings History
                                </button>
                                <button
                                    onClick={() => setActiveTab('payouts')}
                                    className={`border-b-2 px-1 py-4 text-sm font-medium ${
                                        activeTab === 'payouts'
                                            ? 'border-indigo-500 text-indigo-600'
                                            : 'border-transparent text-gray-500 hover:border-gray-300 hover:text-gray-700'
                                    }`}
                                >
                                    Payout Requests
                                </button>
                            </nav>
                        </div>
                    </div>

                    {/* Earnings History */}
                    {activeTab === 'earnings' && (
                    <div className="overflow-hidden rounded-lg bg-white shadow">
                        <div className="px-6 py-4">
                            <h3 className="text-lg font-medium text-gray-900">Recent Earnings</h3>
                        </div>
                        <div className="overflow-x-auto">
                            <table className="min-w-full divide-y divide-gray-200">
                                <thead className="bg-gray-50">
                                    <tr>
                                        <th className="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500">
                                            Course
                                        </th>
                                        <th className="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500">
                                            Date
                                        </th>
                                        <th className="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500">
                                            Gross Amount
                                        </th>
                                        <th className="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500">
                                            Platform Fee
                                        </th>
                                        <th className="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500">
                                            Net Amount
                                        </th>
                                        <th className="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500">
                                            Status
                                        </th>
                                        <th className="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500">
                                            Available At
                                        </th>
                                    </tr>
                                </thead>
                                <tbody className="divide-y divide-gray-200 bg-white">
                                    {earnings.data.length === 0 ? (
                                        <tr>
                                            <td colSpan={7} className="px-6 py-12 text-center text-gray-500">
                                                <p className="text-sm">No earnings yet</p>
                                                <p className="mt-1 text-xs">
                                                    Earnings will appear here when students purchase your courses
                                                </p>
                                            </td>
                                        </tr>
                                    ) : (
                                        earnings.data.map((earning) => (
                                            <tr key={earning.id} className="hover:bg-gray-50">
                                                <td className="px-6 py-4 text-sm text-gray-900">
                                                    {earning.course?.title || 'Unknown Course'}
                                                </td>
                                                <td className="whitespace-nowrap px-6 py-4 text-sm text-gray-500">
                                                    {formatDate(earning.created_at)}
                                                </td>
                                                <td className="whitespace-nowrap px-6 py-4 text-sm text-gray-900">
                                                    {formatCurrency(earning.gross_amount, earning.currency)}
                                                </td>
                                                <td className="whitespace-nowrap px-6 py-4 text-sm text-gray-500">
                                                    {formatCurrency(earning.platform_fee, earning.currency)} (
                                                    {earning.platform_fee_percentage}%)
                                                </td>
                                                <td className="whitespace-nowrap px-6 py-4 text-sm font-medium text-gray-900">
                                                    {formatCurrency(earning.net_amount, earning.currency)}
                                                </td>
                                                <td className="whitespace-nowrap px-6 py-4">
                                                    <span
                                                        className={`inline-flex rounded-full px-2 py-1 text-xs font-semibold ${getStatusColor(earning.status)}`}
                                                    >
                                                        {earning.status}
                                                    </span>
                                                </td>
                                                <td className="whitespace-nowrap px-6 py-4 text-sm text-gray-500">
                                                    {earning.status === 'pending'
                                                        ? formatDate(earning.available_at)
                                                        : '-'}
                                                </td>
                                            </tr>
                                        ))
                                    )}
                                </tbody>
                            </table>
                        </div>
                    </div>
                    )}

                    {/* Payout History */}
                    {activeTab === 'payouts' && (
                        <div className="mt-8 overflow-hidden rounded-lg bg-white shadow">
                            <div className="px-6 py-4">
                                <h3 className="text-lg font-medium text-gray-900">Payout Requests</h3>
                            </div>
                            <div className="overflow-x-auto">
                                <table className="min-w-full divide-y divide-gray-200">
                                    <thead className="bg-gray-50">
                                        <tr>
                                            <th className="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500">
                                                Reference
                                            </th>
                                            <th className="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500">
                                                Amount
                                            </th>
                                            <th className="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500">
                                                Method
                                            </th>
                                            <th className="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500">
                                                Requested
                                            </th>
                                            <th className="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500">
                                                Status
                                            </th>
                                        </tr>
                                    </thead>
                                    <tbody className="divide-y divide-gray-200 bg-white">
                                        {payouts.data.length === 0 ? (
                                            <tr>
                                                <td colSpan={5} className="px-6 py-12 text-center text-gray-500">
                                                    <p className="text-sm">No payout requests yet</p>
                                                    <p className="mt-1 text-xs">
                                                        Request a payout when your available balance reaches the minimum amount
                                                    </p>
                                                </td>
                                            </tr>
                                        ) : (
                                            payouts.data.map((payout) => (
                                            <tr key={payout.id} className="hover:bg-gray-50">
                                                <td className="whitespace-nowrap px-6 py-4 text-sm font-mono text-gray-900">
                                                    {payout.payout_reference}
                                                </td>
                                                <td className="whitespace-nowrap px-6 py-4 text-sm font-medium text-gray-900">
                                                    {formatCurrency(payout.amount, payout.currency)}
                                                </td>
                                                <td className="whitespace-nowrap px-6 py-4 text-sm text-gray-500">
                                                    {payout.payout_method.replace('_', ' ')}
                                                </td>
                                                <td className="whitespace-nowrap px-6 py-4 text-sm text-gray-500">
                                                    {formatDate(payout.requested_at)}
                                                </td>
                                                <td className="whitespace-nowrap px-6 py-4">
                                                    <span
                                                        className={`inline-flex rounded-full px-2 py-1 text-xs font-semibold ${getStatusColor(payout.status)}`}
                                                    >
                                                        {payout.status}
                                                    </span>
                                                </td>
                                            </tr>
                                        ))
                                        )}
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    )}
            </div>
        </DashboardLayout>
    );
}
