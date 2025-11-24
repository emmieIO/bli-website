import { useState, FormEvent } from 'react';
import { Head, router, usePage } from '@inertiajs/react';
import DashboardLayout from '@/Layouts/DashboardLayout';

interface User {
    id: number;
    name: string;
}

interface ApplicationLog {
    id: number;
    application_id: number;
    action: string;
    comment: string;
    user: User;
}

interface ApplicationLogsProps {
    logs: ApplicationLog[];
}

export default function ApplicationLogs({ logs }: ApplicationLogsProps) {
    const { sideLinks } = usePage().props as any;
    const [searchQuery, setSearchQuery] = useState('');
    const [showDeleteModal, setShowDeleteModal] = useState(false);
    const [logToDelete, setLogToDelete] = useState<ApplicationLog | null>(null);
    const [isDeleting, setIsDeleting] = useState(false);

    // Filter logs based on search query
    const filteredLogs = logs.filter((log) => {
        const searchLower = searchQuery.toLowerCase();
        return (
            log.application_id.toString().includes(searchLower) ||
            log.action.toLowerCase().includes(searchLower) ||
            log.user.name.toLowerCase().includes(searchLower) ||
            log.comment?.toLowerCase().includes(searchLower)
        );
    });

    const openDeleteModal = (log: ApplicationLog) => {
        setLogToDelete(log);
        setShowDeleteModal(true);
    };

    const handleDelete = (e: FormEvent) => {
        e.preventDefault();
        if (!logToDelete) return;

        setIsDeleting(true);
        router.delete(route('admin.instructors.application-logs.delete', logToDelete.id), {
            preserveScroll: true,
            onSuccess: () => {
                setShowDeleteModal(false);
                setLogToDelete(null);
            },
            onFinish: () => {
                setIsDeleting(false);
            },
        });
    };

    return (
        <DashboardLayout sideLinks={sideLinks}>
            <Head title="Application Logs" />

            {/* Header and Controls */}
            <div className="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 mb-6">
                <div>
                    <h2 className="text-xl font-semibold text-gray-800">Application Logs</h2>
                    <p className="text-sm text-gray-500 mt-1">
                        Review all actions performed on instructor applications
                    </p>
                </div>
                <div className="flex flex-col sm:flex-row gap-3 w-full sm:w-auto">
                    <div className="relative w-full sm:w-64">
                        <input
                            type="text"
                            placeholder="Search logs..."
                            value={searchQuery}
                            onChange={(e) => setSearchQuery(e.target.value)}
                            className="w-full pl-10 pr-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 text-sm"
                        />
                        <i className="fas fa-search absolute left-3 top-2.5 text-gray-400 w-5 h-5"></i>
                    </div>
                </div>
            </div>

            {/* Logs Table */}
            <div className="relative overflow-x-auto shadow-md rounded-lg">
                <table className="w-full text-sm text-left text-gray-500">
                    <thead className="text-xs text-gray-700 uppercase bg-gray-50">
                        <tr>
                            <th scope="col" className="px-6 py-3">
                                Profile Id
                            </th>
                            <th scope="col" className="px-6 py-3">
                                Action
                            </th>
                            <th scope="col" className="px-6 py-3 whitespace-nowrap">
                                Performed By
                            </th>
                            <th scope="col" className="px-6 py-3 whitespace-nowrap">
                                Comment
                            </th>
                            <th scope="col" className="px-6 py-3">
                                Action
                            </th>
                        </tr>
                    </thead>
                    <tbody>
                        {filteredLogs.length > 0 ? (
                            filteredLogs.map((log) => (
                                <tr
                                    key={log.id}
                                    className="bg-white border-b border-gray-200 hover:bg-gray-50"
                                >
                                    <th
                                        scope="row"
                                        className="px-6 py-4 font-medium text-gray-900 whitespace-nowrap"
                                    >
                                        {log.application_id}
                                    </th>
                                    <td className="px-6 py-4">{log.action}</td>
                                    <td className="px-6 py-4">{log.user.name}</td>
                                    <td className="px-6 py-4">{log.comment || 'N/A'}</td>
                                    <td className="px-6 py-4">
                                        <button
                                            onClick={() => openDeleteModal(log)}
                                            className="font-medium text-red-600 hover:underline flex items-center cursor-pointer"
                                        >
                                            <i className="fas fa-trash w-4 h-4"></i>
                                        </button>
                                    </td>
                                </tr>
                            ))
                        ) : (
                            <tr>
                                <td colSpan={5} className="px-6 py-16 text-center">
                                    <div className="flex flex-col items-center justify-center space-y-4 text-gray-400">
                                        <i className="fas fa-clipboard-list w-12 h-12 text-gray-300"></i>
                                        <h3 className="text-lg font-medium text-gray-900">
                                            {searchQuery ? 'No logs found' : 'No application logs yet'}
                                        </h3>
                                        <p className="max-w-md text-center">
                                            {searchQuery
                                                ? 'Try adjusting your search query'
                                                : 'Application logs will appear here when actions are performed'}
                                        </p>
                                    </div>
                                </td>
                            </tr>
                        )}
                    </tbody>
                </table>
            </div>

            {/* Delete Confirmation Modal */}
            {showDeleteModal && logToDelete && (
                <div className="fixed inset-0 z-50 flex items-center justify-center bg-black/30 backdrop-blur-sm">
                    <div className="relative bg-white rounded-lg shadow-xl p-4 w-full max-w-md mx-4 border border-gray-200">
                        <button
                            type="button"
                            onClick={() => setShowDeleteModal(false)}
                            className="absolute top-3 right-3 text-gray-400 bg-transparent hover:bg-gray-100 hover:text-gray-600 rounded-lg text-sm w-8 h-8 inline-flex justify-center items-center transition-colors"
                        >
                            <i className="fas fa-times w-4 h-4"></i>
                        </button>
                        <div className="p-4 text-center">
                            <div className="mx-auto mb-4 w-12 h-12 text-gray-400 flex items-center justify-center">
                                <i className="fas fa-exclamation-circle w-12 h-12"></i>
                            </div>
                            <h3 className="mb-5 text-lg font-normal text-gray-500">
                                Are you sure you want to delete this log?
                            </h3>
                            <form onSubmit={handleDelete}>
                                <div className="flex items-center justify-center">
                                    <button
                                        type="submit"
                                        disabled={isDeleting}
                                        className="text-white bg-red-600 hover:bg-red-800 focus:ring-4 focus:outline-none focus:ring-red-300 font-medium rounded-lg text-sm inline-flex items-center px-5 py-2.5 text-center disabled:opacity-50 disabled:cursor-not-allowed"
                                    >
                                        {isDeleting ? 'Deleting...' : "Yes, I'm sure"}
                                    </button>
                                    <button
                                        type="button"
                                        onClick={() => setShowDeleteModal(false)}
                                        className="py-2.5 px-5 ml-3 text-sm font-medium text-gray-900 focus:outline-none bg-white rounded-lg border border-gray-200 hover:bg-gray-100 hover:text-blue-700 focus:z-10 focus:ring-4 focus:ring-gray-100"
                                    >
                                        No, cancel
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            )}
        </DashboardLayout>
    );
}
