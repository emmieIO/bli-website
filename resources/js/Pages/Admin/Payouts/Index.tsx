import DashboardLayout from '@/Layouts/DashboardLayout';
import { Head, Link, router } from '@inertiajs/react';
import { useState } from 'react';

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
        hour: '2-digit',
        minute: '2-digit',
    });
};

interface Instructor {
    id: number;
    name: string;
    email: string;
}

interface Payout {
    id: number;
    payout_reference: string;
    amount: number;
    currency: string;
    status: 'pending' | 'processing' | 'completed' | 'failed' | 'cancelled';
    payout_method: string;
    requested_at: string;
    instructor: Instructor;
}

interface Props {
    payouts: {
        data: Payout[];
        current_page: number;
        last_page: number;
        per_page: number;
        total: number;
    };
    counts: {
        all: number;
        pending: number;
        processing: number;
        completed: number;
        failed: number;
    };
    currentStatus: string;
}

export default function PayoutsIndex({ payouts, counts, currentStatus }: Props) {
    const getStatusColor = (status: string) => {
        switch (status) {
            case 'pending':
                return 'bg-yellow-100 text-yellow-800';
            case 'processing':
                return 'bg-purple-100 text-purple-800';
            case 'completed':
                return 'bg-green-100 text-green-800';
            case 'failed':
                return 'bg-red-100 text-red-800';
            case 'cancelled':
                return 'bg-gray-100 text-gray-800';
            default:
                return 'bg-gray-100 text-gray-800';
        }
    };

    const filterByStatus = (status: string) => {
        router.get('/admin/payouts', { status }, { preserveState: true });
    };

    return (
        <DashboardLayout>
            <Head title="Instructor Payouts" />

            {/* Page Header */}
            <div className="mb-6">
                <h1 className="font-montserrat text-2xl font-bold text-primary">Instructor Payouts</h1>
                <p className="font-lato text-sm text-gray-500">Manage instructor payout requests</p>
            </div>

            {/* Filter Tabs */}
            <div className="mb-6">
                <div className="border-b border-gray-200">
                    <nav className="-mb-px flex space-x-8">
                        <button
                            onClick={() => filterByStatus('all')}
                            className={`border-b-2 px-1 py-4 text-sm font-medium ${
                                currentStatus === 'all'
                                    ? 'border-indigo-500 text-indigo-600'
                                    : 'border-transparent text-gray-500 hover:border-gray-300 hover:text-gray-700'
                            }`}
                        >
                            All ({counts.all})
                        </button>
                        <button
                            onClick={() => filterByStatus('pending')}
                            className={`border-b-2 px-1 py-4 text-sm font-medium ${
                                currentStatus === 'pending'
                                    ? 'border-indigo-500 text-indigo-600'
                                    : 'border-transparent text-gray-500 hover:border-gray-300 hover:text-gray-700'
                            }`}
                        >
                            Pending ({counts.pending})
                        </button>
                        <button
                            onClick={() => filterByStatus('processing')}
                            className={`border-b-2 px-1 py-4 text-sm font-medium ${
                                currentStatus === 'processing'
                                    ? 'border-indigo-500 text-indigo-600'
                                    : 'border-transparent text-gray-500 hover:border-gray-300 hover:text-gray-700'
                            }`}
                        >
                            Processing ({counts.processing})
                        </button>
                        <button
                            onClick={() => filterByStatus('completed')}
                            className={`border-b-2 px-1 py-4 text-sm font-medium ${
                                currentStatus === 'completed'
                                    ? 'border-indigo-500 text-indigo-600'
                                    : 'border-transparent text-gray-500 hover:border-gray-300 hover:text-gray-700'
                            }`}
                        >
                            Completed ({counts.completed})
                        </button>
                        <button
                            onClick={() => filterByStatus('failed')}
                            className={`border-b-2 px-1 py-4 text-sm font-medium ${
                                currentStatus === 'failed'
                                    ? 'border-indigo-500 text-indigo-600'
                                    : 'border-transparent text-gray-500 hover:border-gray-300 hover:text-gray-700'
                            }`}
                        >
                            Failed ({counts.failed})
                        </button>
                    </nav>
                </div>
            </div>

            {/* Payouts Table */}
            <div className="overflow-hidden rounded-lg bg-white shadow">
                <div className="overflow-x-auto">
                    <table className="min-w-full divide-y divide-gray-200">
                        <thead className="bg-gray-50">
                            <tr>
                                <th className="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500">
                                    Reference
                                </th>
                                <th className="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500">
                                    Instructor
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
                                <th className="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500">
                                    Actions
                                </th>
                            </tr>
                        </thead>
                        <tbody className="divide-y divide-gray-200 bg-white">
                            {payouts.data.length === 0 ? (
                                <tr>
                                    <td colSpan={7} className="px-6 py-12 text-center text-gray-500">
                                        <p className="text-sm">No payout requests found</p>
                                        <p className="mt-1 text-xs">
                                            {currentStatus === 'all'
                                                ? 'No instructors have requested payouts yet'
                                                : `No ${currentStatus} payout requests`}
                                        </p>
                                    </td>
                                </tr>
                            ) : (
                                payouts.data.map((payout) => (
                                    <tr key={payout.id} className="hover:bg-gray-50">
                                        <td className="whitespace-nowrap px-6 py-4 text-sm font-mono text-gray-900">
                                            {payout.payout_reference}
                                        </td>
                                        <td className="px-6 py-4">
                                            <div className="text-sm font-medium text-gray-900">
                                                {payout.instructor.name}
                                            </div>
                                            <div className="text-sm text-gray-500">{payout.instructor.email}</div>
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
                                        <td className="whitespace-nowrap px-6 py-4 text-sm">
                                            <Link
                                                href={`/admin/payouts/${payout.id}`}
                                                className="text-indigo-600 hover:text-indigo-900"
                                            >
                                                View Details
                                            </Link>
                                        </td>
                                    </tr>
                                ))
                            )}
                        </tbody>
                    </table>
                </div>

                {/* Pagination */}
                {payouts.last_page > 1 && (
                    <div className="border-t border-gray-200 bg-white px-4 py-3 sm:px-6">
                        <div className="flex items-center justify-between">
                            <div className="text-sm text-gray-700">
                                Showing page {payouts.current_page} of {payouts.last_page}
                            </div>
                            <div className="flex gap-2">
                                {payouts.current_page > 1 && (
                                    <button
                                        onClick={() =>
                                            router.get(`/admin/payouts?page=${payouts.current_page - 1}&status=${currentStatus}`)
                                        }
                                        className="rounded-md border border-gray-300 bg-white px-4 py-2 text-sm font-medium text-gray-700 hover:bg-gray-50"
                                    >
                                        Previous
                                    </button>
                                )}
                                {payouts.current_page < payouts.last_page && (
                                    <button
                                        onClick={() =>
                                            router.get(`/admin/payouts?page=${payouts.current_page + 1}&status=${currentStatus}`)
                                        }
                                        className="rounded-md border border-gray-300 bg-white px-4 py-2 text-sm font-medium text-gray-700 hover:bg-gray-50"
                                    >
                                        Next
                                    </button>
                                )}
                            </div>
                        </div>
                    </div>
                )}
            </div>
        </DashboardLayout>
    );
}
