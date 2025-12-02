import DashboardLayout from '@/Layouts/DashboardLayout';
import { Head, useForm } from '@inertiajs/react';
import { FormEventHandler } from 'react';
import { route } from 'ziggy-js';

interface Balance {
    available: number;
    pending: number;
    total_earned: number;
    total_paid: number;
}

interface Props {
    balance: Balance;
    minimumPayout: number;
}

export default function RequestPayout({ balance, minimumPayout }: Props) {
    const { data, setData, post, processing, errors } = useForm({
        payout_method: 'bank_transfer',
        bank_name: '',
        account_number: '',
        account_name: '',
        bank_code: '',
        payout_email: '',
        additional_details: '',
    });

    const submit: FormEventHandler = (e) => {
        e.preventDefault();
        post(route('instructor.earnings.payout.store'));
    };

    const formatCurrency = (amount: number) => {
        return new Intl.NumberFormat('en-NG', {
            style: 'currency',
            currency: 'NGN',
        }).format(amount);
    };

    return (
        <DashboardLayout>
            <Head title="Request Payout" />

            <div className="mx-auto max-w-2xl">
                <div className="mb-8">
                    <h1 className="text-2xl font-bold text-gray-900">Request Payout</h1>
                    <p className="mt-1 text-sm text-gray-500">
                        Withdraw your available earnings to your preferred payment method.
                    </p>
                </div>

                <div className="mb-8 rounded-lg bg-indigo-50 p-6">
                    <div className="flex items-center justify-between">
                        <div>
                            <p className="text-sm font-medium text-indigo-600">Available for withdrawal</p>
                            <p className="mt-1 text-3xl font-bold text-indigo-900">
                                {formatCurrency(balance.available)}
                            </p>
                        </div>
                        <div className="text-right">
                            <p className="text-sm text-gray-500">Minimum payout</p>
                            <p className="font-medium text-gray-900">{formatCurrency(minimumPayout)}</p>
                        </div>
                    </div>
                </div>

                <div className="rounded-lg bg-white p-6 shadow">
                    <form onSubmit={submit} className="space-y-6">
                        <div>
                            <label htmlFor="payout_method" className="block text-sm font-medium text-gray-700">
                                Payout Method
                            </label>
                            <select
                                id="payout_method"
                                value={data.payout_method}
                                onChange={(e) => setData('payout_method', e.target.value)}
                                className="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm"
                            >
                                <option value="bank_transfer">Bank Transfer</option>
                                <option value="payoneer">Payoneer</option>
                                <option value="manual">Manual / Other</option>
                            </select>
                            {errors.payout_method && (
                                <p className="mt-1 text-sm text-red-600">{errors.payout_method}</p>
                            )}
                        </div>

                        {data.payout_method === 'bank_transfer' && (
                            <div className="space-y-4">
                                <div>
                                    <label htmlFor="bank_name" className="block text-sm font-medium text-gray-700">
                                        Bank Name
                                    </label>
                                    <input
                                        type="text"
                                        id="bank_name"
                                        value={data.bank_name}
                                        onChange={(e) => setData('bank_name', e.target.value)}
                                        className="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm"
                                    />
                                    {errors.bank_name && (
                                        <p className="mt-1 text-sm text-red-600">{errors.bank_name}</p>
                                    )}
                                </div>

                                <div>
                                    <label htmlFor="account_number" className="block text-sm font-medium text-gray-700">
                                        Account Number
                                    </label>
                                    <input
                                        type="text"
                                        id="account_number"
                                        value={data.account_number}
                                        onChange={(e) => setData('account_number', e.target.value)}
                                        className="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm"
                                    />
                                    {errors.account_number && (
                                        <p className="mt-1 text-sm text-red-600">{errors.account_number}</p>
                                    )}
                                </div>

                                <div>
                                    <label htmlFor="account_name" className="block text-sm font-medium text-gray-700">
                                        Account Name
                                    </label>
                                    <input
                                        type="text"
                                        id="account_name"
                                        value={data.account_name}
                                        onChange={(e) => setData('account_name', e.target.value)}
                                        className="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm"
                                    />
                                    {errors.account_name && (
                                        <p className="mt-1 text-sm text-red-600">{errors.account_name}</p>
                                    )}
                                </div>

                                <div>
                                    <label htmlFor="bank_code" className="block text-sm font-medium text-gray-700">
                                        Bank Code (Optional)
                                    </label>
                                    <input
                                        type="text"
                                        id="bank_code"
                                        value={data.bank_code}
                                        onChange={(e) => setData('bank_code', e.target.value)}
                                        className="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm"
                                    />
                                    {errors.bank_code && (
                                        <p className="mt-1 text-sm text-red-600">{errors.bank_code}</p>
                                    )}
                                </div>
                            </div>
                        )}

                        {data.payout_method === 'payoneer' && (
                            <div>
                                <label htmlFor="payout_email" className="block text-sm font-medium text-gray-700">
                                    Payoneer Email
                                </label>
                                <input
                                    type="email"
                                    id="payout_email"
                                    value={data.payout_email}
                                    onChange={(e) => setData('payout_email', e.target.value)}
                                    className="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm"
                                />
                                {errors.payout_email && (
                                    <p className="mt-1 text-sm text-red-600">{errors.payout_email}</p>
                                )}
                            </div>
                        )}

                        <div>
                            <label htmlFor="additional_details" className="block text-sm font-medium text-gray-700">
                                Additional Details (Optional)
                            </label>
                            <textarea
                                id="additional_details"
                                rows={3}
                                value={data.additional_details}
                                onChange={(e) => setData('additional_details', e.target.value)}
                                className="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm"
                            />
                            {errors.additional_details && (
                                <p className="mt-1 text-sm text-red-600">{errors.additional_details}</p>
                            )}
                        </div>

                        <div className="flex items-center justify-end space-x-4">
                            <a
                                href={route('instructor.earnings.index')}
                                className="text-sm font-medium text-gray-700 hover:text-gray-900"
                            >
                                Cancel
                            </a>
                            <button
                                type="submit"
                                disabled={processing}
                                className="inline-flex justify-center rounded-md border border-transparent bg-indigo-600 px-4 py-2 text-sm font-medium text-white shadow-sm hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 disabled:opacity-50"
                            >
                                {processing ? 'Submitting...' : 'Submit Request'}
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </DashboardLayout>
    );
}
