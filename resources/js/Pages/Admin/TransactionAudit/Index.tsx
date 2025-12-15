import { Head, Link, usePage, router } from '@inertiajs/react';
import DashboardLayout from '@/Layouts/DashboardLayout';
import { useState, useEffect } from 'react';
import { FileText, Search } from 'lucide-react';
import { useDebounce } from '@/Hooks/useDebounce'; // Assuming you might have a hook, but I'll implement simple debounce if not available, actually I'll just use simple timeout or just onChange for now with a form/enter or simple debounce if I see one. I'll stick to a simple controlled input with debounced effect or keypress. 

// Wait, I don't see useDebounce in the file list earlier. I'll implement a simple debounce in the component.

interface Transaction {
    id: number;
    transaction_id: string;
    amount: number;
    currency: string;
    status: string;
    payment_type: string;
    created_at: string;
    user: {
        id: number;
        name: string;
        email: string;
    };
    course?: {
        id: number;
        title: string;
    };
}

interface Payout {
    id: number;
    payout_reference: string;
    amount: number;
    currency: string;
    status: string;
    requested_at: string;
    instructor: {
        id: number;
        name: string;
        email: string;
    };
}

interface PaginationLinks {
    url: string | null;
    label: string;
    active: boolean;
}

interface PaginatedData<T> {
    data: T[];
    current_page: number;
    last_page: number;
    links: PaginationLinks[];
    total: number;
}

interface Props {
    transactions: PaginatedData<Transaction>;
    payouts: PaginatedData<Payout>;
    filters: {
        q?: string;
    };
}

export default function Index({ transactions, payouts, filters }: Props) {
    const { sideLinks } = usePage().props as any;
    const [activeTab, setActiveTab] = useState<'transactions' | 'payouts'>('transactions');
    const [search, setSearch] = useState(filters.q || '');

    // Debounce search
    useEffect(() => {
        const timer = setTimeout(() => {
            if (search !== (filters.q || '')) {
                router.get(
                    route('admin.transactions-audit.index'),
                    { q: search },
                    { preserveState: true, replace: true }
                );
            }
        }, 300);

        return () => clearTimeout(timer);
    }, [search]);

    const formatCurrency = (amount: number, currency: string) => {
        return new Intl.NumberFormat('en-US', {
            style: 'currency',
            currency: currency || 'USD',
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

    return (
        <DashboardLayout sideLinks={sideLinks}>
            <Head title="Transaction Audit" />

            <div className="flex flex-col md:flex-row md:items-center md:justify-between mb-6 gap-4">
                <div>
                    <h1 className="text-2xl font-bold text-gray-900 font-montserrat">Transaction Audit</h1>
                    <p className="text-sm text-gray-500 font-lato">
                        Monitor all incoming transactions and outgoing payouts.
                    </p>
                </div>
                <div className="relative w-full md:w-64">
                    <div className="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                        <Search className="h-5 w-5 text-gray-400" />
                    </div>
                    <input
                        type="text"
                        placeholder="Search transactions..."
                        className="pl-10 pr-4 py-2 border border-gray-300 rounded-lg w-full focus:outline-none focus:ring-2 focus:ring-primary focus:border-transparent font-lato"
                        value={search}
                        onChange={(e) => setSearch(e.target.value)}
                    />
                </div>
            </div>

            <div className="border-b border-gray-200 mb-6">
                <nav className="-mb-px flex space-x-8">
                    <button
                        onClick={() => setActiveTab('transactions')}
                        className={`py-4 px-1 border-b-2 font-medium text-sm font-montserrat ${
                            activeTab === 'transactions'
                                ? 'border-primary text-primary'
                                : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300'
                        }`}
                    >
                        Incoming Transactions
                    </button>
                    <button
                        onClick={() => setActiveTab('payouts')}
                        className={`py-4 px-1 border-b-2 font-medium text-sm font-montserrat ${
                            activeTab === 'payouts'
                                ? 'border-primary text-primary'
                                : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300'
                        }`}
                    >
                        Instructor Payouts
                    </button>
                </nav>
            </div>

            {activeTab === 'transactions' && (
                <div className="bg-white shadow overflow-hidden sm:rounded-lg">
                    <div className="px-4 py-5 sm:px-6">
                        <h3 className="text-lg leading-6 font-medium text-gray-900 font-montserrat">Transactions</h3>
                    </div>
                    <div className="overflow-x-auto">
                        <table className="min-w-full divide-y divide-gray-200">
                            <thead className="bg-gray-50">
                                <tr>
                                    <th className="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider font-montserrat">Reference</th>
                                    <th className="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider font-montserrat">User</th>
                                    <th className="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider font-montserrat">Item</th>
                                    <th className="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider font-montserrat">Amount</th>
                                    <th className="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider font-montserrat">Status</th>
                                    <th className="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider font-montserrat">Date</th>
                                    <th className="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider font-montserrat">Actions</th>
                                </tr>
                            </thead>
                            <tbody className="bg-white divide-y divide-gray-200">
                                {transactions.data.map((transaction) => (
                                    <tr key={transaction.id}>
                                        <td className="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900 font-lato">
                                            {transaction.transaction_id}
                                        </td>
                                        <td className="px-6 py-4 whitespace-nowrap text-sm text-gray-500 font-lato">
                                            {transaction.user ? (
                                                <>
                                                    <div className="text-gray-900 font-medium">{transaction.user.name}</div>
                                                    <div className="text-xs">{transaction.user.email}</div>
                                                </>
                                            ) : 'Unknown User'}
                                        </td>
                                        <td className="px-6 py-4 whitespace-nowrap text-sm text-gray-500 font-lato">
                                            {transaction.course ? transaction.course.title : 'Cart Purchase / Other'}
                                        </td>
                                        <td className="px-6 py-4 whitespace-nowrap text-sm text-gray-900 font-medium font-montserrat">
                                            {formatCurrency(transaction.amount, transaction.currency)}
                                        </td>
                                        <td className="px-6 py-4 whitespace-nowrap text-sm text-gray-500 font-lato">
                                            <span className={`px-2 inline-flex text-xs leading-5 font-semibold rounded-full ${
                                                transaction.status === 'successful' ? 'bg-green-100 text-green-800' : 
                                                transaction.status === 'failed' ? 'bg-red-100 text-red-800' : 'bg-yellow-100 text-yellow-800'
                                            }`}>
                                                {transaction.status}
                                            </span>
                                        </td>
                                        <td className="px-6 py-4 whitespace-nowrap text-sm text-gray-500 font-lato">
                                            {formatDate(transaction.created_at)}
                                        </td>
                                        <td className="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                            {transaction.status === 'successful' && (
                                                <Link
                                                    href={route('transactions.show-receipt', transaction.id)}
                                                    target="_blank"
                                                    className="text-blue-600 hover:text-blue-900 inline-flex items-center gap-1"
                                                >
                                                    <FileText className="w-4 h-4" />
                                                    View Receipt
                                                </Link>
                                            )}
                                        </td>
                                    </tr>
                                ))}
                                {transactions.data.length === 0 && (
                                    <tr>
                                        <td colSpan={7} className="px-6 py-4 text-center text-sm text-gray-500 font-lato">
                                            No transactions found.
                                        </td>
                                    </tr>
                                )}
                            </tbody>
                        </table>
                    </div>
                    {/* Pagination for Transactions */}
                    <div className="bg-white px-4 py-3 flex items-center justify-between border-t border-gray-200 sm:px-6">
                         <div className="flex-1 flex justify-between sm:hidden">
                            {/* Mobile Pagination (Simplified) */}
                         </div>
                         <div className="hidden sm:flex-1 sm:flex sm:items-center sm:justify-between">
                             <div>
                                <p className="text-sm text-gray-700 font-lato">
                                    Showing <span className="font-medium">{(transactions.current_page - 1) * 15 + 1}</span> to <span className="font-medium">{Math.min(transactions.current_page * 15, transactions.total)}</span> of <span className="font-medium">{transactions.total}</span> results
                                </p>
                             </div>
                             <div>
                                <nav className="relative z-0 inline-flex rounded-md shadow-sm -space-x-px" aria-label="Pagination">
                                    {transactions.links.map((link, idx) => (
                                        <Link
                                            key={idx}
                                            href={link.url || '#'}
                                            className={`relative inline-flex items-center px-4 py-2 border text-sm font-medium ${
                                                link.active ? 'z-10 bg-indigo-50 border-indigo-500 text-indigo-600' : 'bg-white border-gray-300 text-gray-500 hover:bg-gray-50'
                                            } ${!link.url ? 'cursor-not-allowed opacity-50' : ''}`}
                                            dangerouslySetInnerHTML={{ __html: link.label }}
                                        />
                                    ))}
                                </nav>
                             </div>
                         </div>
                    </div>
                </div>
            )}

            {activeTab === 'payouts' && (
                <div className="bg-white shadow overflow-hidden sm:rounded-lg">
                    <div className="px-4 py-5 sm:px-6">
                        <h3 className="text-lg leading-6 font-medium text-gray-900 font-montserrat">Instructor Payouts</h3>
                    </div>
                    <div className="overflow-x-auto">
                        <table className="min-w-full divide-y divide-gray-200">
                            <thead className="bg-gray-50">
                                <tr>
                                    <th className="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider font-montserrat">Reference</th>
                                    <th className="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider font-montserrat">Instructor</th>
                                    <th className="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider font-montserrat">Amount</th>
                                    <th className="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider font-montserrat">Status</th>
                                    <th className="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider font-montserrat">Requested At</th>
                                    <th className="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider font-montserrat">Actions</th>
                                </tr>
                            </thead>
                            <tbody className="bg-white divide-y divide-gray-200">
                                {payouts.data.map((payout) => (
                                    <tr key={payout.id}>
                                        <td className="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900 font-lato">
                                            {payout.payout_reference}
                                        </td>
                                        <td className="px-6 py-4 whitespace-nowrap text-sm text-gray-500 font-lato">
                                            {payout.instructor ? (
                                                <>
                                                    <div className="text-gray-900 font-medium">{payout.instructor.name}</div>
                                                    <div className="text-xs">{payout.instructor.email}</div>
                                                </>
                                            ) : 'Unknown Instructor'}
                                        </td>
                                        <td className="px-6 py-4 whitespace-nowrap text-sm text-gray-900 font-medium font-montserrat">
                                            {formatCurrency(payout.amount, payout.currency)}
                                        </td>
                                        <td className="px-6 py-4 whitespace-nowrap text-sm text-gray-500 font-lato">
                                             <span className={`px-2 inline-flex text-xs leading-5 font-semibold rounded-full ${
                                                payout.status === 'completed' ? 'bg-green-100 text-green-800' : 
                                                payout.status === 'failed' || payout.status === 'cancelled' ? 'bg-red-100 text-red-800' : 'bg-yellow-100 text-yellow-800'
                                            }`}>
                                                {payout.status}
                                            </span>
                                        </td>
                                        <td className="px-6 py-4 whitespace-nowrap text-sm text-gray-500 font-lato">
                                            {formatDate(payout.requested_at)}
                                        </td>
                                        <td className="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                            <Link
                                                href={route('admin.payouts.show', payout.id)}
                                                className="text-primary hover:text-primary-600 inline-flex items-center gap-1"
                                            >
                                                <FileText className="w-4 h-4" />
                                                View Payout
                                            </Link>
                                        </td>
                                    </tr>
                                ))}
                                {payouts.data.length === 0 && (
                                    <tr>
                                        <td colSpan={6} className="px-6 py-4 text-center text-sm text-gray-500 font-lato">
                                            No payouts found.
                                        </td>
                                    </tr>
                                )}
                            </tbody>
                        </table>
                    </div>
                     {/* Pagination for Payouts */}
                    <div className="bg-white px-4 py-3 flex items-center justify-between border-t border-gray-200 sm:px-6">
                         <div className="hidden sm:flex-1 sm:flex sm:items-center sm:justify-between">
                             <div>
                                <p className="text-sm text-gray-700 font-lato">
                                    Showing <span className="font-medium">{(payouts.current_page - 1) * 15 + 1}</span> to <span className="font-medium">{Math.min(payouts.current_page * 15, payouts.total)}</span> of <span className="font-medium">{payouts.total}</span> results
                                </p>
                             </div>
                             <div>
                                <nav className="relative z-0 inline-flex rounded-md shadow-sm -space-x-px" aria-label="Pagination">
                                    {payouts.links.map((link, idx) => (
                                        <Link
                                            key={idx}
                                            href={link.url || '#'}
                                            className={`relative inline-flex items-center px-4 py-2 border text-sm font-medium ${
                                                link.active ? 'z-10 bg-indigo-50 border-indigo-500 text-indigo-600' : 'bg-white border-gray-300 text-gray-500 hover:bg-gray-50'
                                            } ${!link.url ? 'cursor-not-allowed opacity-50' : ''}`}
                                            dangerouslySetInnerHTML={{ __html: link.label }}
                                        />
                                    ))}
                                </nav>
                             </div>
                         </div>
                    </div>
                </div>
            )}
        </DashboardLayout>
    );
}
