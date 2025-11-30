import { Head, Link, useForm } from '@inertiajs/react';
import DashboardLayout from '@/Layouts/DashboardLayout';
import { ArrowLeft } from 'lucide-react';

export default function Create() {
    const { data, setData, post, processing, errors } = useForm({
        subject: '',
        message: '',
        priority: 'low',
    });

    function submit(e: React.FormEvent<HTMLFormElement>) {
        e.preventDefault();
        post(route('user.tickets.store'));
    }

    return (
        <DashboardLayout>
            <Head title="Create New Ticket" />

            <div className="py-12">
                <div className="max-w-7xl mx-auto sm:px-6 lg:px-8">
                    <div className="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                        <div className="p-6 bg-white border-b border-gray-200">
                            <div className="flex justify-between items-center mb-4">
                                <h1 className="text-2xl font-bold">Create New Ticket</h1>
                                <Link href={route('user.tickets.index')} className="inline-flex items-center px-4 py-2 bg-gray-200 text-gray-700 rounded-md">
                                    <ArrowLeft className="w-4 h-4 mr-2" />
                                    Back to Tickets
                                </Link>
                            </div>
                            <form onSubmit={submit}>
                                <div className="mb-4">
                                    <label htmlFor="subject" className="block text-sm font-medium text-gray-700">Subject</label>
                                    <input
                                        id="subject"
                                        type="text"
                                        value={data.subject}
                                        onChange={e => setData('subject', e.target.value)}
                                        className="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-primary-500 focus:ring-primary-500"
                                    />
                                    {errors.subject && <p className="mt-2 text-sm text-red-600">{errors.subject}</p>}
                                </div>
                                <div className="mb-4">
                                    <label htmlFor="priority" className="block text-sm font-medium text-gray-700">Priority</label>
                                    <select
                                        id="priority"
                                        value={data.priority}
                                        onChange={e => setData('priority', e.target.value)}
                                        className="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-primary-500 focus:ring-primary-500"
                                    >
                                        <option value="low">Low</option>
                                        <option value="medium">Medium</option>
                                        <option value="high">High</option>
                                    </select>
                                    {errors.priority && <p className="mt-2 text-sm text-red-600">{errors.priority}</p>}
                                </div>
                                <div className="mb-4">
                                    <label htmlFor="message" className="block text-sm font-medium text-gray-700">Message</label>
                                    <textarea
                                        id="message"
                                        rows={6}
                                        value={data.message}
                                        onChange={e => setData('message', e.target.value)}
                                        className="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-primary-500 focus:ring-primary-500"
                                    ></textarea>
                                    {errors.message && <p className="mt-2 text-sm text-red-600">{errors.message}</p>}
                                </div>
                                <div className="flex items-center justify-end">
                                    <button type="submit" className="px-4 py-2 bg-primary text-white rounded-md" disabled={processing}>
                                        {processing ? 'Submitting...' : 'Submit Ticket'}
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </DashboardLayout>
    );
}
