import { Head, Link, useForm } from '@inertiajs/react';
import DashboardLayout from '@/Layouts/DashboardLayout';
import { ArrowLeft, Send } from 'lucide-react';

interface Ticket {
    id: number;
    reference_code: string;
    subject: string;
    status: string;
    priority: string;
    created_at: string;
    replies: Reply[];
}

interface Reply {
    id: number;
    user: { name: string; };
    message: string;
    created_at: string;
}

interface ShowTicketProps {
    ticket: Ticket;
}

export default function Show({ ticket }: ShowTicketProps) {
    const { data, setData, post, processing, errors, reset } = useForm({
        message: '',
    });

    function submit(e: React.FormEvent<HTMLFormElement>) {
        e.preventDefault();
        post(route('user.tickets.reply', ticket.id), {
            onSuccess: () => reset('message'),
        });
    }

    return (
        <DashboardLayout>
            <Head title={`Ticket: ${ticket.subject}`} />

            <div className="mx-auto max-w-4xl space-y-5">
                <div className="flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
                    <div>
                        <p className="text-xs text-slate-400">Ticket #{ticket.reference_code}</p>
                        <h1 className="mt-0.5 text-xl font-semibold tracking-tight text-slate-900">{ticket.subject}</h1>
                    </div>
                    <Link href={route('user.tickets.index')} className="inline-flex items-center gap-1.5 rounded-md border border-slate-200 bg-white px-3.5 py-2 text-[13px] font-medium text-slate-700 transition hover:bg-slate-50">
                        <ArrowLeft size={15} />
                        Back to Tickets
                    </Link>
                </div>

                <div className="rounded-lg border border-slate-200 bg-white p-5">
                    <div className="grid grid-cols-2 sm:grid-cols-4 gap-4 text-sm">
                        <div>
                            <p className="text-[11px] font-semibold uppercase tracking-wider text-slate-400">Status</p>
                            <span className={`mt-0.5 inline-flex rounded-md px-2 py-0.5 text-[11px] font-semibold ${
                                ticket.status === 'open' ? 'bg-lime-100 text-lime-700' :
                                ticket.status === 'in_progress' ? 'bg-amber-100 text-amber-700' :
                                'bg-accent-50 text-accent'
                            }`}>{ticket.status}</span>
                        </div>
                        <div>
                            <p className="text-[11px] font-semibold uppercase tracking-wider text-slate-400">Priority</p>
                            <p className="mt-0.5 capitalize text-slate-700">{ticket.priority}</p>
                        </div>
                        <div className="col-span-2 sm:col-span-2">
                            <p className="text-[11px] font-semibold uppercase tracking-wider text-slate-400">Created</p>
                            <p className="mt-0.5 text-slate-700">{new Date(ticket.created_at).toLocaleString()}</p>
                        </div>
                    </div>
                </div>

                <div className="space-y-3">
                    {ticket.replies.map(reply => (
                        <div key={reply.id} className="rounded-lg border border-slate-200 bg-white p-4">
                            <div className="flex items-center justify-between mb-2">
                                <p className="text-sm font-semibold text-slate-900">{reply.user.name}</p>
                                <p className="text-[11px] text-slate-400">{new Date(reply.created_at).toLocaleString()}</p>
                            </div>
                            <p className="text-sm leading-relaxed text-slate-600">{reply.message}</p>
                        </div>
                    ))}
                </div>

                <div className="rounded-lg border border-slate-200 bg-white p-5">
                    <form onSubmit={submit} className="space-y-4">
                        <div>
                            <label htmlFor="message" className="block text-[13px] font-medium text-slate-700 mb-1.5">Your Reply</label>
                            <textarea
                                id="message"
                                rows={4}
                                value={data.message}
                                onChange={e => setData('message', e.target.value)}
                                className="w-full rounded-md border border-slate-300 px-3.5 py-2.5 text-sm text-slate-900 placeholder:text-slate-400 outline-none transition focus:border-primary-300 focus:ring-2 focus:ring-primary-500/10 resize-none"
                            />
                            {errors.message && <p className="mt-1.5 text-[13px] text-accent">{errors.message}</p>}
                        </div>
                        <div className="flex justify-end">
                            <button type="submit" disabled={processing} className="inline-flex items-center gap-2 rounded-md bg-primary px-4 py-2.5 text-sm font-medium text-white transition hover:bg-primary-600 disabled:opacity-50 shadow-sm">
                                <Send size={15} />
                                {processing ? 'Sending...' : 'Send Reply'}
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </DashboardLayout>
    );
}
