import DashboardLayout from '@/Layouts/DashboardLayout';
import { Head, Link, useForm, router } from '@inertiajs/react';
import { FormEventHandler, useState } from 'react';
import { route } from 'ziggy-js';
import {
    User,
    Calendar,
    CheckCircle,
    XCircle,
    AlertCircle,
    FileText,
    Clock,
    Banknote
} from 'lucide-react';
import Modal from '@/Components/Modal';

interface Instructor {
    id: number;
    name: string;
    email: string;
    photo: string | null;
}

interface Earning {
    id: number;
    gross_amount: number;
    platform_fee: number;
    net_amount: number;
    currency: string;
    created_at: string;
    course: {
        id: number;
        title: string;
    } | null;
}

interface Payout {
    id: number;
    payout_reference: string;
    amount: number;
    currency: string;
    status: 'pending' | 'processing' | 'completed' | 'failed' | 'cancelled';
    payout_method: string;
    bank_name?: string;
    account_number?: string;
    account_name?: string;
    bank_code?: string;
    payout_email?: string;
    payout_details?: any;
    additional_details?: string;
    notes?: string;
    failure_reason?: string;
    external_reference?: string;
    requested_at: string;
    processed_at: string | null;
    completed_at: string | null;
    instructor: Instructor;
    earnings: Earning[];
}

interface Props {
    payout: Payout;
}

export default function PayoutShow({ payout }: Props) {
    const [showCompleteModal, setShowCompleteModal] = useState(false);
    const [showFailModal, setShowFailModal] = useState(false);

    const {
        data: completeData,
        setData: setCompleteData,
        post: postComplete,
        processing: completeProcessing,
        errors: completeErrors,
        reset: resetComplete
    } = useForm({
        external_reference: '',
        notes: '',
    });

    const {
        data: failData,
        setData: setFailData,
        post: postFail,
        processing: failProcessing,
        errors: failErrors,
        reset: resetFail
    } = useForm({
        failure_reason: '',
    });

    const formatCurrency = (amount: number, currency: string) => {
        return new Intl.NumberFormat('en-NG', {
            style: 'currency',
            currency: currency,
        }).format(amount);
    };

    const formatDate = (dateString: string) => {
        return new Date(dateString).toLocaleString();
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

    const markAsProcessing = () => {
        if (confirm('Are you sure you want to mark this payout as processing?')) {
            router.post(route('admin.payouts.mark-processing', payout.id));
        }
    };

    const handleComplete = (e: React.FormEvent) => {
        e.preventDefault();
        postComplete(route('admin.payouts.mark-completed', payout.id), {
            onSuccess: () => {
                setShowCompleteModal(false);
                resetComplete();
            },
        });
    };

    const handleFail = (e: React.FormEvent) => {
        e.preventDefault();
        postFail(route('admin.payouts.mark-failed', payout.id), {
            onSuccess: () => {
                setShowFailModal(false);
                resetFail();
            },
        });
    };

    return (
        <DashboardLayout>
            <Head title={`Payout ${payout.payout_reference}`} />

            <div className="mb-6">
                <div className="flex items-center gap-4">
                    <Link
                        href={route('admin.payouts.index')}
                        className="text-gray-500 hover:text-gray-700"
                    >
                        &larr; Back to Payouts
                    </Link>
                </div>
                <div className="mt-4 flex items-start justify-between">
                    <div>
                        <h1 className="text-2xl font-bold text-gray-900">
                            Payout Request: {payout.payout_reference}
                        </h1>
                        <p className="mt-1 text-sm text-gray-500">
                            Requested on {formatDate(payout.requested_at)}
                        </p>
                    </div>
                    <span
                        className={`inline-flex items-center rounded-full px-3 py-1 text-sm font-medium ${getStatusColor(
                            payout.status
                        )}`}
                    >
                        {payout.status.toUpperCase()}
                    </span>
                </div>
            </div>

            <div className="grid grid-cols-1 gap-6 lg:grid-cols-3">
                {/* Main Info */}
                <div className="space-y-6 lg:col-span-2">
                    {/* Instructor Info */}
                    <div className="overflow-hidden rounded-lg bg-white shadow">
                        <div className="border-b border-gray-200 px-6 py-4">
                            <h3 className="text-lg font-medium text-gray-900">Instructor Details</h3>
                        </div>
                        <div className="p-6">
                            <div className="flex items-center">
                                <img
                                    className="h-12 w-12 rounded-full object-cover"
                                    src={
                                        payout.instructor.photo
                                            ? `/storage/${payout.instructor.photo}`
                                            : `https://ui-avatars.com/api/?name=${encodeURIComponent(
                                                payout.instructor.name
                                            )}&background=random`
                                    }
                                    alt={payout.instructor.name}
                                />
                                <div className="ml-4">
                                    <h4 className="text-lg font-bold text-gray-900">
                                        {payout.instructor.name}
                                    </h4>
                                    <p className="text-sm text-gray-500">{payout.instructor.email}</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    {/* Payment Details */}
                    <div className="overflow-hidden rounded-lg bg-white shadow">
                        <div className="border-b border-gray-200 px-6 py-4">
                            <h3 className="text-lg font-medium text-gray-900">Payment Information</h3>
                        </div>
                        <div className="p-6">
                            <dl className="grid grid-cols-1 gap-x-4 gap-y-6 sm:grid-cols-2">
                                <div>
                                    <dt className="text-sm font-medium text-gray-500">Total Amount</dt>
                                    <dd className="mt-1 text-2xl font-bold text-gray-900">
                                        {formatCurrency(payout.amount, payout.currency)}
                                    </dd>
                                </div>
                                <div>
                                    <dt className="text-sm font-medium text-gray-500">Payout Method</dt>
                                    <dd className="mt-1 text-lg font-medium text-gray-900 capitalize">
                                        {payout.payout_method.replace('_', ' ')}
                                    </dd>
                                </div>

                                {payout.payout_method === 'bank_transfer' && (
                                    <>
                                        <div className="sm:col-span-2">
                                            <dt className="text-sm font-medium text-gray-500">Bank Details</dt>
                                            <dd className="mt-1 rounded-md bg-gray-50 p-4 text-sm text-gray-900">
                                                <p><span className="font-medium">Bank Name:</span> {payout.bank_name}</p>
                                                <p className="mt-1"><span className="font-medium">Account Number:</span> {payout.account_number}</p>
                                                <p className="mt-1"><span className="font-medium">Account Name:</span> {payout.account_name}</p>
                                                {payout.bank_code && (
                                                    <p className="mt-1"><span className="font-medium">Bank Code:</span> {payout.bank_code}</p>
                                                )}
                                            </dd>
                                        </div>
                                    </>
                                )}

                                {payout.payout_method === 'payoneer' && (
                                    <div className="sm:col-span-2">
                                        <dt className="text-sm font-medium text-gray-500">Payoneer Email</dt>
                                        <dd className="mt-1 text-sm text-gray-900">{payout.payout_email}</dd>
                                    </div>
                                )}

                                {payout.additional_details && (
                                    <div className="sm:col-span-2">
                                        <dt className="text-sm font-medium text-gray-500">Additional Details</dt>
                                        <dd className="mt-1 text-sm text-gray-900">{payout.additional_details}</dd>
                                    </div>
                                )}
                            </dl>
                        </div>
                    </div>

                    {/* Included Earnings */}
                    <div className="overflow-hidden rounded-lg bg-white shadow">
                        <div className="border-b border-gray-200 px-6 py-4">
                            <h3 className="text-lg font-medium text-gray-900">Included Earnings</h3>
                        </div>
                        <div className="overflow-x-auto">
                            <table className="min-w-full divide-y divide-gray-200">
                                <thead className="bg-gray-50">
                                    <tr>
                                        <th className="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500">
                                            Date
                                        </th>
                                        <th className="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500">
                                            Course
                                        </th>
                                        <th className="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500">
                                            Amount
                                        </th>
                                    </tr>
                                </thead>
                                <tbody className="divide-y divide-gray-200 bg-white">
                                    {payout.earnings.map((earning) => (
                                        <tr key={earning.id}>
                                            <td className="whitespace-nowrap px-6 py-4 text-sm text-gray-500">
                                                {formatDate(earning.created_at)}
                                            </td>
                                            <td className="px-6 py-4 text-sm text-gray-900">
                                                {earning.course?.title || 'Unknown Course'}
                                            </td>
                                            <td className="whitespace-nowrap px-6 py-4 text-sm font-medium text-gray-900">
                                                {formatCurrency(earning.net_amount, earning.currency)}
                                            </td>
                                        </tr>
                                    ))}
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

                {/* Sidebar Actions */}
                <div className="space-y-6">
                    <div className="rounded-lg bg-white p-6 shadow">
                        <h3 className="text-lg font-medium text-gray-900">Actions</h3>
                        <div className="mt-4 space-y-3">
                            {payout.status === 'pending' && (
                                <button
                                    onClick={markAsProcessing}
                                    className="w-full rounded-md bg-blue-600 px-4 py-2 text-sm font-medium text-white hover:bg-blue-700"
                                >
                                    Mark as Processing
                                </button>
                            )}

                            {(payout.status === 'pending' || payout.status === 'processing') && (
                                <>
                                    <button
                                        onClick={() => setShowCompleteModal(true)}
                                        className="w-full rounded-md bg-green-600 px-4 py-2 text-sm font-medium text-white hover:bg-green-700"
                                    >
                                        Mark as Completed
                                    </button>
                                    <button
                                        onClick={() => setShowFailModal(true)}
                                        className="w-full rounded-md border border-red-300 bg-white px-4 py-2 text-sm font-medium text-red-700 hover:bg-red-50"
                                    >
                                        Mark as Failed
                                    </button>
                                </>
                            )}
                        </div>
                    </div>

                    <div className="rounded-lg bg-white p-6 shadow">
                        <h3 className="text-lg font-medium text-gray-900">Timeline</h3>
                        <div className="mt-4 flow-root">
                            <ul className="-mb-8">
                                <li>
                                    <div className="relative pb-8">
                                        {payout.processed_at && (
                                            <span
                                                className="absolute top-4 left-4 -ml-px h-full w-0.5 bg-gray-200"
                                                aria-hidden="true"
                                            />
                                        )}
                                        <div className="relative flex space-x-3">
                                            <div>
                                                <span className="flex h-8 w-8 items-center justify-center rounded-full bg-green-500 ring-8 ring-white">
                                                    <Clock className="h-5 w-5 text-white" />
                                                </span>
                                            </div>
                                            <div className="flex min-w-0 flex-1 justify-between space-x-4 pt-1.5">
                                                <div>
                                                    <p className="text-sm text-gray-500">Requested</p>
                                                </div>
                                                <div className="whitespace-nowrap text-right text-sm text-gray-500">
                                                    <time dateTime={payout.requested_at}>
                                                        {formatDate(payout.requested_at)}
                                                    </time>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </li>
                                {payout.processed_at && (
                                    <li>
                                        <div className="relative pb-8">
                                            {payout.completed_at || payout.failure_reason ? (
                                                <span
                                                    className="absolute top-4 left-4 -ml-px h-full w-0.5 bg-gray-200"
                                                    aria-hidden="true"
                                                />
                                            ) : null}
                                            <div className="relative flex space-x-3">
                                                <div>
                                                    <span className="flex h-8 w-8 items-center justify-center rounded-full bg-blue-500 ring-8 ring-white">
                                                        <CheckCircle className="h-5 w-5 text-white" />
                                                    </span>
                                                </div>
                                                <div className="flex min-w-0 flex-1 justify-between space-x-4 pt-1.5">
                                                    <div>
                                                        <p className="text-sm text-gray-500">Processing Started</p>
                                                    </div>
                                                    <div className="whitespace-nowrap text-right text-sm text-gray-500">
                                                        <time dateTime={payout.processed_at}>
                                                            {formatDate(payout.processed_at)}
                                                        </time>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </li>
                                )}
                                {payout.completed_at && (
                                    <li>
                                        <div className="relative pb-8">
                                            <div className="relative flex space-x-3">
                                                <div>
                                                    <span className="flex h-8 w-8 items-center justify-center rounded-full bg-green-500 ring-8 ring-white">
                                                        <Banknote className="h-5 w-5 text-white" />
                                                    </span>
                                                </div>
                                                <div className="flex min-w-0 flex-1 justify-between space-x-4 pt-1.5">
                                                    <div>
                                                        <p className="text-sm text-gray-500">Payment Completed</p>
                                                        {payout.external_reference && (
                                                            <p className="text-xs text-gray-500">
                                                                Ref: {payout.external_reference}
                                                            </p>
                                                        )}
                                                    </div>
                                                    <div className="whitespace-nowrap text-right text-sm text-gray-500">
                                                        <time dateTime={payout.completed_at}>
                                                            {formatDate(payout.completed_at)}
                                                        </time>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </li>
                                )}
                                {payout.failure_reason && (
                                    <li>
                                        <div className="relative pb-8">
                                            <div className="relative flex space-x-3">
                                                <div>
                                                    <span className="flex h-8 w-8 items-center justify-center rounded-full bg-red-500 ring-8 ring-white">
                                                        <XCircle className="h-5 w-5 text-white" />
                                                    </span>
                                                </div>
                                                <div className="flex min-w-0 flex-1 justify-between space-x-4 pt-1.5">
                                                    <div>
                                                        <p className="text-sm text-gray-500">Failed</p>
                                                        <p className="text-xs text-red-500">
                                                            Reason: {payout.failure_reason}
                                                        </p>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </li>
                                )}
                            </ul>
                        </div>
                    </div>
                </div>
            </div>

            {/* Complete Modal */}
            <Modal show={showCompleteModal} onClose={() => setShowCompleteModal(false)}>
                <div className="p-6">
                    <h2 className="text-lg font-medium text-gray-900">Mark Payout as Completed</h2>
                    <p className="mt-1 text-sm text-gray-600">
                        Enter the transaction reference from your payment provider.
                    </p>

                    <form onSubmit={handleComplete} className="mt-6">
                        <div className="space-y-4">
                            <div>
                                <label className="block text-sm font-medium text-gray-700">
                                    External Reference / Transaction ID
                                </label>
                                <input
                                    type="text"
                                    className="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm"
                                    value={completeData.external_reference}
                                    onChange={(e) => setCompleteData('external_reference', e.target.value)}
                                    placeholder="e.g., TXN_123456789"
                                />
                            </div>
                            <div>
                                <label className="block text-sm font-medium text-gray-700">Notes (Optional)</label>
                                <textarea
                                    className="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm"
                                    rows={3}
                                    value={completeData.notes}
                                    onChange={(e) => setCompleteData('notes', e.target.value)}
                                />
                            </div>
                        </div>

                        <div className="mt-6 flex justify-end gap-3">
                            <button
                                type="button"
                                onClick={() => setShowCompleteModal(false)}
                                className="rounded-md border border-gray-300 bg-white px-4 py-2 text-sm font-medium text-gray-700 hover:bg-gray-50"
                            >
                                Cancel
                            </button>
                            <button
                                type="submit"
                                disabled={completeProcessing}
                                className="rounded-md bg-green-600 px-4 py-2 text-sm font-medium text-white hover:bg-green-700 disabled:opacity-50"
                            >
                                {completeProcessing ? 'Completing...' : 'Confirm Completion'}
                            </button>
                        </div>
                    </form>
                </div>
            </Modal>

            {/* Fail Modal */}
            <Modal show={showFailModal} onClose={() => setShowFailModal(false)}>
                <div className="p-6">
                    <h2 className="text-lg font-medium text-gray-900">Mark Payout as Failed</h2>
                    <p className="mt-1 text-sm text-gray-600">
                        Please provide a reason for the failure. The earnings will be returned to the instructor's
                        available balance.
                    </p>

                    <form onSubmit={handleFail} className="mt-6">
                        <div>
                            <label className="block text-sm font-medium text-gray-700">
                                Failure Reason <span className="text-red-500">*</span>
                            </label>
                            <textarea
                                className="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-red-500 focus:ring-red-500 sm:text-sm"
                                rows={3}
                                value={failData.failure_reason}
                                onChange={(e) => setFailData('failure_reason', e.target.value)}
                                required
                            />
                            {failErrors.failure_reason && (
                                <p className="mt-1 text-sm text-red-600">{failErrors.failure_reason}</p>
                            )}
                        </div>

                        <div className="mt-6 flex justify-end gap-3">
                            <button
                                type="button"
                                onClick={() => setShowFailModal(false)}
                                className="rounded-md border border-gray-300 bg-white px-4 py-2 text-sm font-medium text-gray-700 hover:bg-gray-50"
                            >
                                Cancel
                            </button>
                            <button
                                type="submit"
                                disabled={failProcessing}
                                className="rounded-md bg-red-600 px-4 py-2 text-sm font-medium text-white hover:bg-red-700 disabled:opacity-50"
                            >
                                {failProcessing ? 'Mark as Failed' : 'Confirm Failure'}
                            </button>
                        </div>
                    </form>
                </div>
            </Modal>
        </DashboardLayout>
    );
}
