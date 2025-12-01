import { Head, Link } from '@inertiajs/react';
import DashboardLayout from '@/Layouts/DashboardLayout';
import { Plus } from 'lucide-react';

interface Ticket {
    id: number;
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
    return (
        <DashboardLayout>
            <Head title="My Support Tickets" />

            <div className="py-12">
                <div className="max-w-7xl mx-auto sm:px-6 lg:px-8">
                    <div className="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                        <div className="p-6 bg-white border-b border-gray-200">
                            <div className="flex justify-between items-center mb-4">
                                <h1 className="text-2xl font-bold">My Support Tickets</h1>
                                <Link href={route('user.tickets.create')} className="inline-flex items-center px-4 py-2 bg-primary text-white rounded-md">
                                    <Plus className="w-4 h-4 mr-2" />
                                    New Ticket
                                </Link>
                            </div>
                            <div className="overflow-x-auto">
                                <table className="min-w-full divide-y divide-gray-200">
                                    <thead className="bg-gray-50">
                                        <tr>
                                            <th scope="col" className="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                                Subject
                                            </th>
                                            <th scope="col" className="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                                Status
                                            </th>
                                            <th scope="col" className="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                                Priority
                                            </th>
                                            <th scope="col" className="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                                Created At
                                            </th>
                                            <th scope="col" className="relative px-6 py-3">
                                                <span className="sr-only">View</span>
                                            </th>
                                        </tr>
                                    </thead>
                                    <tbody className="bg-white divide-y divide-gray-200">
                                        {tickets.data.map(ticket => (
                                            <tr key={ticket.id}>
                                                <td className="px-6 py-4 whitespace-nowrap">
                                                    <Link href={route('user.tickets.show', ticket.id)} className="text-sm font-medium text-primary hover:underline">
                                                        {ticket.subject}
                                                    </Link>
                                                </td>
                                                <td className="px-6 py-4 whitespace-nowrap">
                                                    <span className={`px-2 inline-flex text-xs leading-5 font-semibold rounded-full ${
                                                        ticket.status === 'open' ? 'bg-green-100 text-green-800' :
                                                        ticket.status === 'in_progress' ? 'bg-yellow-100 text-yellow-800' :
                                                        'bg-red-100 text-red-800'
                                                    }`}>
                                                        {ticket.status}
                                                    </span>
                                                </td>
                                                <td className="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                                    {ticket.priority}
                                                </td>
                                                <td className="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
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
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </DashboardLayout>
    );
}
