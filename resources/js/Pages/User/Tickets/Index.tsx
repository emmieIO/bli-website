import { Head, Link, router } from '@inertiajs/react';
import DashboardLayout from '@/Layouts/DashboardLayout';
import { Plus, Search } from 'lucide-react';
import { useState } from 'react';

interface Ticket {
    id: number;
    reference_code: string;
    subject: string;
    status: string;
    priority: string;
    created_at: string;
}

interface TicketsProps {
    tickets: {
        data: Ticket[];
        links: any[];
    };
}

export default function Index({ tickets }: TicketsProps) {
    const [search, setSearch] = useState('');

    const handleSearch = (e: React.FormEvent) => {
        e.preventDefault();
        router.get(route('user.tickets.index'), { search }, { preserveState: true });
    };

    return (
        <DashboardLayout>
            <Head title="My Support Tickets" />

            <div className="workspace-stack">
                <section className="workspace-header-card px-6 py-6 lg:px-8">
                    <div className="flex flex-col gap-5 lg:flex-row lg:items-end lg:justify-between">
                        <div className="max-w-3xl">
                            <p className="workspace-muted-label">Support</p>
                            <h1 className="mt-3 text-3xl font-semibold tracking-tight text-slate-900">My Support Tickets</h1>
                            <p className="mt-3 text-sm leading-7 text-slate-600">
                                Track open issues, follow responses, and keep all support conversations in one clean queue.
                            </p>
                        </div>
                        <Link href={route('user.tickets.create')} className="inline-flex items-center gap-2 rounded-2xl bg-slate-900 px-4 py-3 text-sm font-semibold text-white transition hover:bg-slate-800">
                            <Plus className="h-4 w-4" />
                            New Ticket
                        </Link>
                    </div>
                </section>

                <section className="workspace-card p-5 lg:p-6">
                    <form onSubmit={handleSearch}>
                        <div className="relative max-w-xl">
                            <input
                                type="text"
                                value={search}
                                onChange={(e) => setSearch(e.target.value)}
                                placeholder="Search by reference code or subject..."
                                className="w-full border border-slate-200 bg-slate-50 py-3 pl-10 pr-4 text-sm text-slate-900 focus:border-emerald-300 focus:bg-white focus:outline-none focus:ring-4 focus:ring-emerald-100"
                            />
                            <Search className="absolute left-3 top-3.5 h-4 w-4 text-slate-400" />
                        </div>
                    </form>
                </section>

                <section className="workspace-card overflow-hidden">
                    <div className="overflow-x-auto">
                        <table className="min-w-full divide-y divide-slate-200">
                            <thead className="bg-slate-50/90">
                                <tr>
                                    <th scope="col" className="px-6 py-3 text-left text-xs font-semibold uppercase tracking-[0.14em] text-slate-500">
                                        Reference
                                    </th>
                                    <th scope="col" className="px-6 py-3 text-left text-xs font-semibold uppercase tracking-[0.14em] text-slate-500">
                                        Subject
                                    </th>
                                    <th scope="col" className="px-6 py-3 text-left text-xs font-semibold uppercase tracking-[0.14em] text-slate-500">
                                        Status
                                    </th>
                                    <th scope="col" className="px-6 py-3 text-left text-xs font-semibold uppercase tracking-[0.14em] text-slate-500">
                                        Priority
                                    </th>
                                    <th scope="col" className="px-6 py-3 text-left text-xs font-semibold uppercase tracking-[0.14em] text-slate-500">
                                        Created
                                    </th>
                                    <th scope="col" className="px-6 py-3 text-right text-xs font-semibold uppercase tracking-[0.14em] text-slate-500">
                                        Actions
                                    </th>
                                </tr>
                            </thead>
                            <tbody className="divide-y divide-slate-100 bg-white">
                                {tickets.data.map(ticket => (
                                    <tr key={ticket.id}>
                                        <td className="px-6 py-4 whitespace-nowrap">
                                            <span className="font-mono text-sm text-slate-900">
                                                {ticket.reference_code}
                                            </span>
                                        </td>
                                        <td className="px-6 py-4 whitespace-nowrap">
                                            <Link href={route('user.tickets.show', ticket.id)} className="text-sm font-medium text-primary hover:underline">
                                                {ticket.subject}
                                            </Link>
                                        </td>
                                        <td className="px-6 py-4 whitespace-nowrap">
                                            <span className={`inline-flex rounded-full px-2.5 py-1 text-xs font-semibold ${
                                                ticket.status === 'open' ? 'bg-green-100 text-green-800' :
                                                ticket.status === 'in_progress' ? 'bg-yellow-100 text-yellow-800' :
                                                'bg-red-100 text-red-800'
                                            }`}>
                                                {ticket.status}
                                            </span>
                                        </td>
                                        <td className="px-6 py-4 whitespace-nowrap text-sm capitalize text-slate-500">
                                            {ticket.priority}
                                        </td>
                                        <td className="px-6 py-4 whitespace-nowrap text-sm text-slate-500">
                                            {new Date(ticket.created_at).toLocaleDateString()}
                                        </td>
                                        <td className="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                            <Link href={route('user.tickets.show', ticket.id)} className="text-primary hover:text-primary-700">
                                                View
                                            </Link>
                                        </td>
                                    </tr>
                                ))}
                            </tbody>
                        </table>
                        {tickets.data.length === 0 && (
                            <div className="px-6 py-14 text-center text-slate-500">
                                No support tickets yet.
                            </div>
                        )}
                    </div>
                </section>
            </div>
        </DashboardLayout>
    );
}
