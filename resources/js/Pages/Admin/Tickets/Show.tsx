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
    user: {
        name: string;
    }
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
    const { data: replyData, setData: setReplyData, post: postReply, processing: processingReply, errors: replyErrors, reset: resetReply } = useForm({
        message: '',
    });

    const { data: statusData, setData: setStatusData, post: postStatus, processing: processingStatus, errors: statusErrors } = useForm({
        status: ticket.status,
    });

    function submitReply(e: React.FormEvent<HTMLFormElement>) {
        e.preventDefault();
        postReply(route('admin.tickets.reply', ticket.id), {
            onSuccess: () => resetReply('message'),
        });
    }

    function submitStatus(e: React.FormEvent<HTMLFormElement>) {
        e.preventDefault();
        postStatus(route('admin.tickets.status', ticket.id));
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
                                <Link href={route('admin.tickets.index')} className="inline-flex items-center px-4 py-2 bg-gray-200 text-gray-700 rounded-md">
                                    <ArrowLeft className="w-4 h-4 mr-2" />
                                    Back to Tickets
                                </Link>
                            </div>
                            <div className="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                                <div>
                                    <p><strong>User:</strong> {ticket.user.name}</p>
                                    <p><strong>Status:</strong> {ticket.status}</p>
                                    <p><strong>Priority:</strong> {ticket.priority}</p>
                                    <p><strong>Created:</strong> {new Date(ticket.created_at).toLocaleString()}</p>
                                </div>
                                <div>
                                    <form onSubmit={submitStatus}>
                                        <label htmlFor="status" className="block text-sm font-medium text-gray-700">Change Status</label>
                                        <div className="mt-1 flex rounded-md shadow-sm">
                                            <select
                                                id="status"
                                                value={statusData.status}
                                                onChange={e => setStatusData('status', e.target.value)}
                                                className="block w-full rounded-none rounded-l-md border-gray-300 focus:border-primary-500 focus:ring-primary-500"
                                                disabled={ticket.status === 'closed'}
                                            >
                                                <option value="open">Open</option>
                                                <option value="in_progress">In Progress</option>
                                                <option value="closed">Closed</option>
                                            </select>
                                            <button type="submit" className="inline-flex items-center px-4 py-2 border border-l-0 border-gray-300 rounded-r-md bg-gray-50 text-sm font-medium text-gray-700 hover:bg-gray-100" disabled={processingStatus || ticket.status === 'closed'}>
                                                {processingStatus ? 'Saving...' : 'Save'}
                                            </button>
                                        </div>
                                        {statusErrors.status && <p className="mt-2 text-sm text-red-600">{statusErrors.status}</p>}
                                    </form>
                                </div>
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
                                <form onSubmit={submitReply}>
                                    <div className="mb-4">
                                        <label htmlFor="message" className="block text-sm font-medium text-gray-700">Your Reply</label>
                                        <textarea
                                            id="message"
                                            rows={4}
                                            value={replyData.message}
                                            onChange={e => setReplyData('message', e.target.value)}
                                            className="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-primary-500 focus:ring-primary-500"
                                            disabled={ticket.status === 'closed'}
                                        ></textarea>
                                        {replyErrors.message && <p className="mt-2 text-sm text-red-600">{replyErrors.message}</p>}
                                    </div>
                                    <div className="flex items-center justify-end">
                                        <button type="submit" className="inline-flex items-center px-4 py-2 bg-primary text-white rounded-md" disabled={processingReply || ticket.status === 'closed'}>
                                            <Send className="w-4 h-4 mr-2" />
                                            {processingReply ? 'Sending...' : 'Send Reply'}
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
