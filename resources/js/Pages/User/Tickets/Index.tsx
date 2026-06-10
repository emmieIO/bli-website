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

            <div className="space-y-5">
                <section className="flex flex-col gap-4 sm:flex-row sm:items-end sm:justify-between">
                    <div>
                        <h1 className="text-xl font-semibold tracking-tight text-slate-900">My Support Tickets</h1>
                        <p className="mt-1 text-sm text-slate-500">Track open issues, follow responses, and keep all support conversations in one clean queue.</p>
                    </div>
                    <Link href={route('user.tickets.create')} className="inline-flex items-center gap-2 rounded-md bg-primary px-4 py-2.5 text-sm font-medium text-white transition hover:bg-primary-600 shadow-sm">
                        <Plus size={15} />
                        New Ticket
                    </Link>
                </section>

                <section className="rounded-lg border border-slate-200 bg-white p-4">
                    <form onSubmit={handleSearch}>
                        <div className="relative max-w-md">
                            <input
                                type="text"
                                value={search}
                                onChange={(e) => setSearch(e.target.value)}
                                placeholder="Search by reference code or subject..."
                                className="h-9 w-full rounded-md border border-slate-200 bg-slate-50 pl-9 pr-3 text-sm text-slate-900 placeholder:text-slate-400 outline-none transition focus:border-primary-300 focus:bg-white focus:ring-2 focus:ring-primary-500/10"
                            />
                            <Search size={15} className="absolute left-3 top-1/2 -translate-y-1/2 text-slate-400" />
                        </div>
                    </form>
                </section>

                <section className="overflow-hidden rounded-lg border border-slate-200 bg-white">
                    <div className="overflow-x-auto">
                        <table className="min-w-full divide-y divide-slate-200">
                            <thead className="bg-slate-50/80">
                                <tr>
                                    <th className="px-5 py-3 text-left text-[11px] font-semibold uppercase tracking-[0.14em] text-slate-500">Reference</th>
                                    <th className="px-5 py-3 text-left text-[11px] font-semibold uppercase tracking-[0.14em] text-slate-500">Subject</th>
                                    <th className="px-5 py-3 text-left text-[11px] font-semibold uppercase tracking-[0.14em] text-slate-500">Status</th>
                                    <th className="px-5 py-3 text-left text-[11px] font-semibold uppercase tracking-[0.14em] text-slate-500">Priority</th>
                                    <th className="px-5 py-3 text-left text-[11px] font-semibold uppercase tracking-[0.14em] text-slate-500">Created</th>
                                    <th className="px-5 py-3 text-right text-[11px] font-semibold uppercase tracking-[0.14em] text-slate-500">Actions</th>
                                </tr>
                            </thead>
                            <tbody className="divide-y divide-slate-100 bg-white">
                                {tickets.data.map(ticket => (
                                    <tr key={ticket.id} className="transition hover:bg-slate-50/70">
                                        <td className="px-5 py-3.5 whitespace-nowrap">
                                            <span className="font-mono text-[13px] text-slate-900">{ticket.reference_code}</span>
                                        </td>
                                        <td className="px-5 py-3.5 whitespace-nowrap">
                                            <Link href={route('user.tickets.show', ticket.id)} className="text-[13px] font-medium text-primary hover:text-primary-600">
                                                {ticket.subject}
                                            </Link>
                                        </td>
                                        <td className="px-5 py-3.5 whitespace-nowrap">
                                            <span className={`inline-flex rounded-md px-2 py-0.5 text-[11px] font-semibold ${
                                                ticket.status === 'open' ? 'bg-lime-100 text-lime-700' :
                                                ticket.status === 'in_progress' ? 'bg-amber-100 text-amber-700' :
                                                'bg-accent-50 text-accent'
                                            }`}>
                                                {ticket.status}
                                            </span>
                                        </td>
                                        <td className="px-5 py-3.5 whitespace-nowrap text-[13px] capitalize text-slate-500">{ticket.priority}</td>
                                        <td className="px-5 py-3.5 whitespace-nowrap text-[13px] text-slate-500">
                                            {new Date(ticket.created_at).toLocaleDateString()}
                                        </td>
                                        <td className="px-5 py-3.5 whitespace-nowrap text-right">
                                            <Link href={route('user.tickets.show', ticket.id)} className="text-[13px] font-medium text-primary hover:text-primary-600">
                                                View
                                            </Link>
                                        </td>
                                    </tr>
                                ))}
                            </tbody>
                        </table>
                        {tickets.data.length === 0 && (
                            <div className="px-6 py-14 text-center text-sm text-slate-400">No support tickets yet.</div>
                        )}
                    </div>
                </section>
            </div>
        </DashboardLayout>
    );
}
