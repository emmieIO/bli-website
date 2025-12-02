import DashboardLayout from '@/Layouts/DashboardLayout';
import { Head, Link, router } from '@inertiajs/react';
import { useState } from 'react';
import { route } from 'ziggy-js';
import {
    Search,
    Filter,
    ChevronLeft,
    ChevronRight,
    CheckCircle,
    Clock,
    XCircle,
    AlertCircle,
    Download
} from 'lucide-react';

interface Payout {
    id: number;
    payout_reference: string;
    amount: number;
    currency: string;
    status: 'pending' | 'processing' | 'completed' | 'failed' | 'cancelled';
    payout_method: string;
    requested_at: string;
    instructor: {
        id: number;
        name: string;
        email: string;
        photo: string | null;
    };
    earnings_count?: number;
}

interface Props {
    payouts: {
        data: Payout[];
        current_page: number;
        last_page: number;
        per_page: number;
        total: number;
        next_page_url: string | null;
        prev_page_url: string | null;
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
    const [search, setSearch] = useState('');

    const formatCurrency = (amount: number, currency: string) => {
        return new Intl.NumberFormat('en-NG', {
            style: 'currency',
            currency: currency,
        }).format(amount);
    };

    const formatDate = (dateString: string) => {
        return new Date(dateString).toLocaleDateString('en-US', {
            year: 'numeric',
            month: 'short',
            day: 'numeric',
            hour: '2-digit',
            minute: '2-digit',
        });
    };

    const getStatusColor = (status: string) => {
        switch (status) {
            case 'completed':
                return 'bg-green-100 text-green-800';
            case 'pending':
                return 'bg-yellow-100 text-yellow-800';
            case 'processing':
                return 'bg-blue-100 text-blue-800';
            case 'failed':
            case 'cancelled':
                return 'bg-red-100 text-red-800';
            default:
                return 'bg-gray-100 text-gray-800';
        }
    };

    const handleTabChange = (status: string) => {
        router.get(
            route('admin.payouts.index'),
            { status },
            { preserveState: true, preserveScroll: true }
        );
    };

    return (
        <DashboardLayout>
            <Head title="Payout Requests" />

            <div className="mb-6 flex flex-col justify-between gap-4 md:flex-row md:items-center">
                <div>
                    <h1 className="text-2xl font-bold text-gray-900">Payout Requests</h1>
                    <p className="mt-1 text-sm text-gray-500">
                        Manage instructor payout requests and withdrawals.
                    </p>
                </div>
                {/* <button className="flex items-center gap-2 rounded-md bg-white px-4 py-2 text-sm font-medium text-gray-700 shadow-sm hover:bg-gray-50 border border-gray-300">
                    <Download className="h-4 w-4" />
                    Export CSV
                </button> */}
            </div>

            {/* Stats/Tabs */}
            <div className="mb-6 border-b border-gray-200">
                <nav className="-mb-px flex space-x-8 overflow-x-auto">
                    {[
                        { id: 'all', label: 'All Requests', count: counts.all },
                        { id: 'pending', label: 'Pending', count: counts.pending },
                        { id: 'processing', label: 'Processing', count: counts.processing },
                        { id: 'completed', label: 'Completed', count: counts.completed },
                        { id: 'failed', label: 'Failed', count: counts.failed },
                    ].map((tab) => (
                        <button
                            key={tab.id}
                            onClick={() => handleTabChange(tab.id)}
                            className={`
                                whitespace-nowrap border-b-2 px-1 py-4 text-sm font-medium
                                ${
                                    currentStatus === tab.id
                                        ? 'border-indigo-500 text-indigo-600'
                                        : 'border-transparent text-gray-500 hover:border-gray-300 hover:text-gray-700'
                                }
                            `}
                        >
                            {tab.label}
                            <span
                                className={`ml-2 rounded-full px-2.5 py-0.5 text-xs font-medium ${
                                    currentStatus === tab.id
                                        ? 'bg-indigo-100 text-indigo-600'
                                        : 'bg-gray-100 text-gray-900'
                                }`}
                            >
                                {tab.count}
                            </span>
                        </button>
                    ))}
                </nav>
            </div>

            {/* Filters & Search (Optional - simpler for now) */}
            {/* <div className="mb-6 flex items-center gap-4">
                <div className="relative flex-1 max-w-md">
                    <div className="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-3">
                        <Search className="h-5 w-5 text-gray-400" />
                    </div>
                    <input
                        type="text"
                        className="block w-full rounded-md border-gray-300 pl-10 focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm"
                        placeholder="Search by reference or instructor..."
                        value={search}
                        onChange={(e) => setSearch(e.target.value)}
                    />
                </div>
            </div> */}

            {/* Table */}
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
                                    Status
                                </th>
                                <th className="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500">
                                    Requested Date
                                </th>
                                <th className="px-6 py-3 text-right text-xs font-medium uppercase tracking-wider text-gray-500">
                                    Action
                                </th>
                            </tr>
                        </thead>
                        <tbody className="divide-y divide-gray-200 bg-white">
                            {payouts.data.length === 0 ? (
                                <tr>
                                    <td colSpan={6} className="px-6 py-12 text-center text-gray-500">
                                        No payout requests found.
                                    </td>
                                </tr>
                            ) : (
                                payouts.data.map((payout) => (
                                    <tr key={payout.id} className="hover:bg-gray-50">
                                        <td className="whitespace-nowrap px-6 py-4 text-sm font-medium text-gray-900">
                                            {console.log('Payout ID:', payout.id, 'Generated URL:', route('admin.payouts.show', payout.id))}
                                            <Link
                                                href={route('admin.payouts.show', payout.id)}
                                                className="font-mono hover:text-indigo-600"
                                            >
                                                {payout.payout_reference}
                                            </Link>
                                        </td>
                                        <td className="px-6 py-4">
                                            <div className="flex items-center">
                                                <div className="h-8 w-8 shrink-0">
                                                    <img
                                                        className="h-8 w-8 rounded-full object-cover"
                                                        src={
                                                            payout.instructor.photo
                                                                ? `/storage/${payout.instructor.photo}`
                                                                : `https://ui-avatars.com/api/?name=${encodeURIComponent(
                                                                      payout.instructor.name
                                                                  )}&background=random`
                                                        }
                                                        alt=""
                                                    />
                                                </div>
                                                <div className="ml-4">
                                                    <div className="text-sm font-medium text-gray-900">
                                                        {payout.instructor.name}
                                                    </div>
                                                    <div className="text-sm text-gray-500">
                                                        {payout.instructor.email}
                                                    </div>
                                                </div>
                                            </div>
                                        </td>
                                        <td className="whitespace-nowrap px-6 py-4 text-sm font-bold text-gray-900">
                                            {formatCurrency(payout.amount, payout.currency)}
                                        </td>
                                        <td className="whitespace-nowrap px-6 py-4">
                                            <span
                                                className={`inline-flex rounded-full px-2 text-xs font-semibold leading-5 ${getStatusColor(
                                                    payout.status
                                                )}`}
                                            >
                                                {payout.status}
                                            </span>
                                        </td>
                                        <td className="whitespace-nowrap px-6 py-4 text-sm text-gray-500">
                                            {formatDate(payout.requested_at)}
                                        </td>
                                        <td className="whitespace-nowrap px-6 py-4 text-right text-sm font-medium">
                                            <Link
                                                href={route('admin.payouts.show', payout.id)}
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
                {payouts.total > payouts.per_page && (
                    <div className="border-t border-gray-200 px-4 py-3 sm:px-6">
                        <div className="flex items-center justify-between">
                            <div className="flex flex-1 justify-between sm:hidden">
                                <Link
                                    href={payouts.prev_page_url || '#'}
                                    className={`relative inline-flex items-center rounded-md border border-gray-300 bg-white px-4 py-2 text-sm font-medium text-gray-700 hover:bg-gray-50 ${
                                        !payouts.prev_page_url && 'pointer-events-none opacity-50'
                                    }`}
                                >
                                    Previous
                                </Link>
                                <Link
                                    href={payouts.next_page_url || '#'}
                                    className={`relative ml-3 inline-flex items-center rounded-md border border-gray-300 bg-white px-4 py-2 text-sm font-medium text-gray-700 hover:bg-gray-50 ${
                                        !payouts.next_page_url && 'pointer-events-none opacity-50'
                                    }`}
                                >
                                    Next
                                </Link>
                            </div>
                            <div className="hidden sm:flex sm:flex-1 sm:items-center sm:justify-between">
                                <div>
                                    <p className="text-sm text-gray-700">
                                        Showing{' '}
                                        <span className="font-medium">
                                            {(payouts.current_page - 1) * payouts.per_page + 1}
                                        </span>{' '}
                                        to{' '}
                                        <span className="font-medium">
                                            {Math.min(
                                                payouts.current_page * payouts.per_page,
                                                payouts.total
                                            )}
                                        </span>{' '}
                                        of <span className="font-medium">{payouts.total}</span> results
                                    </p>
                                </div>
                                <div>
                                    <nav
                                        className="isolate inline-flex -space-x-px rounded-md shadow-sm"
                                        aria-label="Pagination"
                                    >
                                        <Link
                                            href={payouts.prev_page_url || '#'}
                                            className={`relative inline-flex items-center rounded-l-md px-2 py-2 text-gray-400 ring-1 ring-inset ring-gray-300 hover:bg-gray-50 focus:z-20 focus:outline-offset-0 ${
                                                !payouts.prev_page_url && 'pointer-events-none opacity-50'
                                            }`}
                                        >
                                            <span className="sr-only">Previous</span>
                                            <ChevronLeft className="h-5 w-5" aria-hidden="true" />
                                        </Link>
                                        {/* Simple pagination for now */}
                                        <span className="relative inline-flex items-center px-4 py-2 text-sm font-semibold text-gray-900 ring-1 ring-inset ring-gray-300 focus:outline-offset-0">
                                            {payouts.current_page}
                                        </span>
                                        <Link
                                            href={payouts.next_page_url || '#'}
                                            className={`relative inline-flex items-center rounded-r-md px-2 py-2 text-gray-400 ring-1 ring-inset ring-gray-300 hover:bg-gray-50 focus:z-20 focus:outline-offset-0 ${
                                                !payouts.next_page_url && 'pointer-events-none opacity-50'
                                            }`}
                                        >
                                            <span className="sr-only">Next</span>
                                            <ChevronRight className="h-5 w-5" aria-hidden="true" />
                                        </Link>
                                    </nav>
                                </div>
                            </div>
                        </div>
                    </div>
                )}
            </div>
        </DashboardLayout>
    );
}