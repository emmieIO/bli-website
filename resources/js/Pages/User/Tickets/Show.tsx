import { Head, Link, useForm } from '@inertiajs/react';
import DashboardLayout from '@/Layouts/DashboardLayout';
import { ArrowLeft, Send } from 'lucide-react';

interface Ticket {
    id: number;
    subject: string;
    status: string;
    priority: string;
    created_at: string;
    replies: Reply[];
}

interface Reply {
    id: number;
    user: {
        name: string;
    };
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

            <div className="py-12">
                <div className="max-w-7xl mx-auto sm:px-6 lg:px-8">
                    <div className="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                        <div className="p-6 bg-white border-b border-gray-200">
                            <div className="flex justify-between items-center mb-4">
                                <h1 className="text-2xl font-bold">{ticket.subject}</h1>
                                <Link href={route('user.tickets.index')} className="inline-flex items-center px-4 py-2 bg-gray-200 text-gray-700 rounded-md">
                                    <ArrowLeft className="w-4 h-4 mr-2" />
                                    Back to Tickets
                                </Link>
                            </div>
                            <div className="mb-4">
                                <p><strong>Status:</strong> {ticket.status}</p>
                                <p><strong>Priority:</strong> {ticket.priority}</p>
                                <p><strong>Created:</strong> {new Date(ticket.created_at).toLocaleString()}</p>
                            </div>
                            <div className="space-y-4">
                                {ticket.replies.map(reply => (
                                    <div key={reply.id} className="p-4 rounded-lg bg-gray-100">
                                        <div className="flex justify-between items-center mb-2">
                                            <p className="font-bold">{reply.user.name}</p>
                                            <p className="text-sm text-gray-500">{new Date(reply.created_at).toLocaleString()}</p>
                                        </div>
                                        <p>{reply.message}</p>
                                    </div>
                                ))}
                            </div>
                            <div className="mt-8">
                                <form onSubmit={submit}>
                                    <div className="mb-4">
                                        <label htmlFor="message" className="block text-sm font-medium text-gray-700">Your Reply</label>
                                        <textarea
                                            id="message"
                                            rows={4}
                                            value={data.message}
                                            onChange={e => setData('message', e.target.value)}
                                            className="mt-1 block w-full rounded-md border-gray-.300 shadow-sm focus:border-primary-500 focus:ring-primary-500"
                                        ></textarea>
                                        {errors.message && <p className="mt-2 text-sm text-red-600">{errors.message}</p>}
                                    </div>
                                    <div className="flex items-center justify-end">
                                        <button type="submit" className="inline-flex items-center px-4 py-2 bg-primary text-white rounded-md" disabled={processing}>
                                            <Send className="w-4 h-4 mr-2" />
                                            {processing ? 'Sending...' : 'Send Reply'}
                                        </button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </DashboardLayout>
    );
}
