import { Head, Link, usePage } from '@inertiajs/react';
import DashboardLayout from '@/Layouts/DashboardLayout';

interface User {
    id: number;
    name: string;
    email: string;
}

interface Event {
    id: number;
    title: string;
    start_date: string;
    end_date: string;
}

interface Application {
    id: number;
    topic_title: string;
    topic_description?: string;
    session_format: {
        value: string;
    };
    created_at: string;
    user: User;
    event: Event;
}

interface PendingApplicationsProps {
    applications: Application[];
}

export default function PendingApplications({ applications }: PendingApplicationsProps) {
    const { sideLinks } = usePage().props as any;

    const formatDate = (dateString: string) => {
        const date = new Date(dateString);
        return date.toLocaleDateString('en-US', {
            year: 'numeric',
            month: 'short',
            day: 'numeric',
            hour: 'numeric',
            minute: '2-digit',
        });
    };

    const formatSessionFormat = (format: string) => {
        return format
            .replace(/_/g, ' ')
            .split(' ')
            .map((word) => word.charAt(0).toUpperCase() + word.slice(1))
            .join(' ');
    };

    return (
        <DashboardLayout sideLinks={sideLinks}>
            <Head title="Pending Speaker Applications - Beacon Leadership Institute" />

            <div className="px-4 mx-auto max-w-7xl py-8">
                {/* Header Section */}
                <div className="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 mb-8">
                    <div className="flex items-center gap-3">
                        <Link
                            href={window.history.length > 1 ? window.location.href : route('admin.speakers.index')}
                            onClick={(e) => {
                                e.preventDefault();
                                window.history.back();
                            }}
                            className="inline-flex items-center justify-center overflow-hidden w-10 h-10 bg-white border rounded-full border-gray-300 text-gray-700 text-sm font-medium hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary transition shadow-sm"
                            title="Back"
                        >
                            <i className="fas fa-arrow-left w-5 h-5"></i>
                        </Link>
                        <div className="p-2.5 rounded-lg bg-primary/10">
                            <i className="fas fa-clock w-6 h-6 text-primary"></i>
                        </div>
                        <div>
                            <h1 className="text-2xl font-extrabold text-primary font-montserrat">
                                Pending Speaker Applications
                            </h1>
                            <p className="text-sm text-gray-500 font-lato">
                                Review and manage pending speaker applications
                            </p>
                        </div>
                    </div>
                </div>

                {/* Status Tabs */}
                <div className="mb-8 flex gap-2 border-b border-gray-200">
                    <Link
                        href={route('admin.speakers.index')}
                        className="px-4 py-2 text-sm font-medium text-gray-500 hover:text-gray-700 border-b-2 border-transparent font-montserrat"
                    >
                        Active Speakers
                    </Link>
                    <Link
                        href={route('admin.speakers.pending')}
                        className="px-4 py-2 text-sm font-medium text-gray-500 hover:text-gray-700 border-b-2 border-transparent font-montserrat"
                    >
                        Pending Speakers
                    </Link>
                    <Link
                        href={route('admin.speakers.applications.pending')}
                        className="px-4 py-2 text-sm font-medium border-b-2 border-primary text-primary font-montserrat"
                    >
                        Applications (Pending)
                    </Link>
                    <Link
                        href={route('admin.speakers.applications.approved')}
                        className="px-4 py-2 text-sm font-medium text-gray-500 hover:text-gray-700 border-b-2 border-transparent font-montserrat"
                    >
                        Applications (Approved)
                    </Link>
                </div>

                {/* Applications Table */}
                <div className="bg-white border border-gray-100 rounded-xl shadow-sm overflow-hidden">
                    <div className="px-6 py-4 border-b border-gray-200 bg-gray-50">
                        <h2 className="text-lg font-medium text-gray-900 font-montserrat">
                            Applications Awaiting Review
                        </h2>
                        <p className="text-sm text-gray-500 mt-1 font-lato">
                            Click "Review" to evaluate and approve or reject each application.
                        </p>
                    </div>

                    <div className="overflow-x-auto">
                        <table className="min-w-full divide-y divide-gray-200">
                            <thead className="bg-gray-50">
                                <tr className="whitespace-nowrap">
                                    <th
                                        scope="col"
                                        className="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider font-montserrat"
                                    >
                                        Topic Title
                                    </th>
                                    <th
                                        scope="col"
                                        className="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider font-montserrat"
                                    >
                                        Speaker
                                    </th>
                                    <th
                                        scope="col"
                                        className="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider font-montserrat"
                                    >
                                        Event
                                    </th>
                                    <th
                                        scope="col"
                                        className="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider font-montserrat"
                                    >
                                        Session Format
                                    </th>
                                    <th
                                        scope="col"
                                        className="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider font-montserrat"
                                    >
                                        Submitted At
                                    </th>
                                    <th
                                        scope="col"
                                        className="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider font-montserrat"
                                    >
                                        Action
                                    </th>
                                </tr>
                            </thead>
                            <tbody className="bg-white divide-y divide-gray-200">
                                {applications.length > 0 ? (
                                    applications.map((application) => (
                                        <tr key={application.id} className="hover:bg-gray-50 transition-colors">
                                            <td className="px-6 py-4 whitespace-nowrap">
                                                <div className="font-medium text-gray-900 font-montserrat">
                                                    {application.topic_title}
                                                </div>
                                                {application.topic_description && (
                                                    <div className="text-xs text-gray-500 mt-1 line-clamp-1 font-lato">
                                                        {application.topic_description.substring(0, 60)}
                                                        {application.topic_description.length > 60 ? '...' : ''}
                                                    </div>
                                                )}
                                            </td>
                                            <td className="px-6 py-4 whitespace-nowrap">
                                                <div className="flex items-center gap-2">
                                                    <div className="w-8 h-8 rounded-full bg-gray-200 flex items-center justify-center text-xs font-medium text-gray-700 font-montserrat">
                                                        {application.user.name.charAt(0).toUpperCase()}
                                                    </div>
                                                    <span className="text-sm text-gray-900 font-lato">
                                                        {application.user.name}
                                                    </span>
                                                </div>
                                            </td>
                                            <td className="px-6 py-4 whitespace-nowrap">
                                                <div className="flex flex-col items-start gap-2">
                                                    <span className="text-sm text-gray-900 font-montserrat">
                                                        {application.event.title}
                                                    </span>
                                                    <span className="text-xs text-gray-500 font-lato">
                                                        {application.event.start_date} – {application.event.end_date}
                                                    </span>
                                                </div>
                                            </td>
                                            <td className="px-6 py-4">
                                                <span className="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800 font-montserrat">
                                                    {formatSessionFormat(application.session_format.value)}
                                                </span>
                                            </td>
                                            <td className="px-6 py-4 text-sm text-gray-500 whitespace-nowrap font-lato">
                                                {formatDate(application.created_at)}
                                            </td>
                                            <td className="px-6 py-4 text-right">
                                                <Link
                                                    href={route('admin.speakers.application.review', application.id)}
                                                    className="inline-flex items-center px-3 py-1.5 bg-primary text-white text-xs font-medium rounded-lg hover:bg-primary-600 focus:ring-2 focus:ring-offset-2 focus:ring-primary transition shadow-sm font-montserrat"
                                                >
                                                    <i className="fas fa-clipboard-check w-4 h-4 mr-1.5"></i>
                                                    Review
                                                </Link>
                                            </td>
                                        </tr>
                                    ))
                                ) : (
                                    <tr>
                                        <td colSpan={6} className="px-6 py-16 text-center">
                                            <div className="flex flex-col items-center space-y-4 text-gray-400">
                                                <i className="fas fa-inbox w-12 h-12 text-gray-300"></i>
                                                <h3 className="text-lg font-medium text-gray-900 font-montserrat">
                                                    No pending applications
                                                </h3>
                                                <p className="max-w-md text-center text-gray-500 font-lato">
                                                    All speaker applications have been reviewed. Check back later or invite
                                                    new speakers.
                                                </p>
                                                <Link
                                                    href={route('admin.speakers.create')}
                                                    className="mt-4 inline-flex items-center px-4 py-2 bg-primary text-white text-sm font-medium rounded-lg hover:bg-primary-600 focus:ring-4 focus:ring-primary-300 transition shadow-sm font-montserrat"
                                                >
                                                    <i className="fas fa-plus w-4 h-4 mr-2"></i>
                                                    Invite New Speaker
                                                </Link>
                                            </div>
                                        </td>
                                    </tr>
                                )}
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </DashboardLayout>
    );
}
