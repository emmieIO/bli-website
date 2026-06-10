import { Head, Link, router, usePage } from '@inertiajs/react';
import DashboardLayout from '@/Layouts/DashboardLayout';
import { useEffect, useState } from 'react';
import { FileText, Search } from 'lucide-react';

interface Transaction {
  id: number;
  transaction_id: string;
  amount: number;
  currency: string;
  status: string;
  payment_type: string;
  created_at: string;
  metadata?: {
    subject_title?: string;
    subject_type?: string;
  };
  payable?: {
    title?: string;
  };
  user: {
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
  filters: {
    q?: string;
  };
}

export default function Index({ transactions, filters }: Props) {
  const { sideLinks } = usePage().props as any;
  const [search, setSearch] = useState(filters.q || '');

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

  const itemLabel = (transaction: Transaction) => {
    return transaction.payable?.title
      ?? transaction.metadata?.subject_title
      ?? (transaction.metadata?.subject_type === 'event' ? 'Event Payment' : 'Payment');
  };

  return (
    <DashboardLayout sideLinks={sideLinks}>
      <Head title="Transaction Audit" />

      <div className="flex flex-col md:flex-row md:items-center md:justify-between mb-6 gap-4">
        <div>
          <h1 className="text-2xl font-bold text-slate-900">Transaction Audit</h1>
          <p className="text-sm text-slate-500">
            Monitor incoming event payments.
          </p>
        </div>
        <div className="relative w-full md:w-64">
          <div className="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
            <Search className="h-5 w-5 text-gray-400" />
          </div>
          <input
            type="text"
            placeholder="Search transactions..."
            className="pl-10 pr-4 py-2 border border-slate-300 rounded-lg w-full focus:outline-none focus:ring-2 focus:ring-primary focus:border-transparent"
            value={search}
            onChange={(e) => setSearch(e.target.value)}
          />
        </div>
      </div>

      <div className="bg-white shadow overflow-hidden sm:rounded-lg">
        <div className="px-4 py-5 sm:px-6">
          <h3 className="text-lg leading-6 font-medium text-slate-900">Transactions</h3>
        </div>
        <div className="overflow-x-auto">
          <table className="min-w-full divide-y divide-gray-200">
            <thead className="bg-slate-50">
              <tr>
                <th className="px-6 py-3 text-left text-xs font-medium text-slate-500 uppercase tracking-wider">Reference</th>
                <th className="px-6 py-3 text-left text-xs font-medium text-slate-500 uppercase tracking-wider">User</th>
                <th className="px-6 py-3 text-left text-xs font-medium text-slate-500 uppercase tracking-wider">Item</th>
                <th className="px-6 py-3 text-left text-xs font-medium text-slate-500 uppercase tracking-wider">Amount</th>
                <th className="px-6 py-3 text-left text-xs font-medium text-slate-500 uppercase tracking-wider">Status</th>
                <th className="px-6 py-3 text-left text-xs font-medium text-slate-500 uppercase tracking-wider">Date</th>
                <th className="px-6 py-3 text-right text-xs font-medium text-slate-500 uppercase tracking-wider">Actions</th>
              </tr>
            </thead>
            <tbody className="bg-white divide-y divide-gray-200">
              {transactions.data.map((transaction) => (
                <tr key={transaction.id}>
                  <td className="px-6 py-4 whitespace-nowrap text-sm font-medium text-slate-900">
                    {transaction.transaction_id}
                  </td>
                  <td className="px-6 py-4 whitespace-nowrap text-sm text-slate-500">
                    {transaction.user ? (
                      <>
                        <div className="text-slate-900 font-medium">{transaction.user.name}</div>
                        <div className="text-xs">{transaction.user.email}</div>
                      </>
                    ) : 'Unknown User'}
                  </td>
                  <td className="px-6 py-4 whitespace-nowrap text-sm text-slate-500">
                    {itemLabel(transaction)}
                  </td>
                  <td className="px-6 py-4 whitespace-nowrap text-sm text-slate-900 font-medium">
                    {formatCurrency(transaction.amount, transaction.currency)}
                  </td>
                  <td className="px-6 py-4 whitespace-nowrap text-sm text-slate-500">
                    <span className={`px-2 inline-flex text-xs leading-5 font-semibold rounded-full ${
                      transaction.status === 'successful' ? 'bg-green-100 text-green-800' :
                        transaction.status === 'failed' ? 'bg-red-100 text-red-800' : 'bg-yellow-100 text-yellow-800'
                    }`}>
                      {transaction.status}
                    </span>
                  </td>
                  <td className="px-6 py-4 whitespace-nowrap text-sm text-slate-500">
                    {formatDate(transaction.created_at)}
                  </td>
                  <td className="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                    <div className="flex items-center justify-end gap-2">
                    {transaction.status === 'successful' && (
                      <a
                        href={route('transactions.show-receipt', transaction.id)}
                        target="_blank"
                        rel="noopener noreferrer"
                        className="text-blue-600 hover:text-blue-900 inline-flex items-center gap-1"
                      >
                        <FileText className="w-4 h-4" />
                        View Receipt
                      </a>
                    )}
                    {transaction.status !== 'successful' && (
                      <Link
                        href={route('transactions-audit.resolve', transaction.id)}
                        method="post"
                        as="button"
                        preserveScroll
                        className="text-primary hover:text-primary-600 inline-flex items-center gap-1"
                      >
                        <i className="fas fa-sync-alt w-3.5 h-3.5" />
                        Resolve
                      </Link>
                    )}
                    </div>
                  </td>
                </tr>
              ))}
              {transactions.data.length === 0 && (
                <tr>
                  <td colSpan={7} className="px-6 py-4 text-center text-sm text-slate-500">
                    No transactions found.
                  </td>
                </tr>
              )}
            </tbody>
          </table>
        </div>

        {transactions.last_page > 1 && (
          <div className="bg-white px-4 py-3 flex items-center justify-between border-t border-slate-200 sm:px-6">
            <div className="hidden sm:flex-1 sm:flex sm:items-center sm:justify-between">
              <p className="text-sm text-slate-700">
                Showing <span className="font-medium">{(transactions.current_page - 1) * 15 + 1}</span> to <span className="font-medium">{Math.min(transactions.current_page * 15, transactions.total)}</span> of <span className="font-medium">{transactions.total}</span> results
              </p>
              <nav className="relative z-0 inline-flex rounded-md shadow-sm -space-x-px" aria-label="Pagination">
                {transactions.links.map((link, idx) => (
                  <Link
                    key={idx}
                    href={link.url || '#'}
                    className={`relative inline-flex items-center px-4 py-2 border text-sm font-medium ${
                      link.active ? 'z-10 bg-indigo-50 border-indigo-500 text-indigo-600' : 'bg-white border-slate-300 text-slate-500 hover:bg-slate-50'
                    } ${!link.url ? 'cursor-not-allowed opacity-50' : ''}`}
                    dangerouslySetInnerHTML={{ __html: link.label }}
                  />
                ))}
              </nav>
            </div>
          </div>
        )}
      </div>
    </DashboardLayout>
  );
}
