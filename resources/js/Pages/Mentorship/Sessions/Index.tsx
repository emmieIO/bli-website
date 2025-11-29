import { Head, Link } from '@inertiajs/react';
import DashboardLayout from '@/Layouts/DashboardLayout';
import { usePage } from '@inertiajs/react';

interface MentorshipRequest {
    id: number;
    student: {
        name: string;
        email: string;
    };
    instructor: {
        name: string;
        email: string;
    };
}

interface Session {
    id: number;
    session_date: string;
    duration: number;
    notes: string | null;
    topics_covered: string | null;
    recording_link: string | null;
    completed_at: string | null;
    created_at: string;
}

interface Props {
    mentorshipRequest: MentorshipRequest;
    sessions: Session[];
}

export default function Index({ mentorshipRequest, sessions }: Props) {
    const { sideLinks } = usePage().props as any;

    return (
        <DashboardLayout sideLinks={sideLinks}>
            <Head title="Mentorship Sessions" />

            <div className="py-12">
                <div className="max-w-7xl mx-auto sm:px-6 lg:px-8">
                    <div className="mb-8">
                        <div className="flex justify-between items-center">
                            <div>
                                <h1 className="text-3xl font-bold text-gray-900">Mentorship Sessions</h1>
                                <p className="mt-2 text-sm text-gray-600">
                                    Track all sessions between {mentorshipRequest.student.name} and {mentorshipRequest.instructor.name}
                                </p>
                            </div>
                            <Link
                                href={route('mentorship.sessions.create', mentorshipRequest.id)}
                                className="px-4 py-2 bg-primary text-white rounded-md hover:bg-primary/90"
                            >
                                Add New Session
                            </Link>
                        </div>
                    </div>

                    <div className="bg-white shadow rounded-lg overflow-hidden">
                        {sessions.length === 0 ? (
                            <div className="text-center py-12">
                                <svg className="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path strokeLinecap="round" strokeLinejoin="round" strokeWidth="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                </svg>
                                <h3 className="mt-2 text-sm font-medium text-gray-900">No sessions yet</h3>
                                <p className="mt-1 text-sm text-gray-500">Get started by creating a new session.</p>
                                <div className="mt-6">
                                    <Link
                                        href={route('mentorship.sessions.create', mentorshipRequest.id)}
                                        className="inline-flex items-center px-4 py-2 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-primary hover:bg-primary/90"
                                    >
                                        Add First Session
                                    </Link>
                                </div>
                            </div>
                        ) : (
                            <table className="min-w-full divide-y divide-gray-200">
                                <thead className="bg-gray-50">
                                    <tr>
                                        <th className="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Date</th>
                                        <th className="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Duration</th>
                                        <th className="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Topics</th>
                                        <th className="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                                        <th className="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                                    </tr>
                                </thead>
                                <tbody className="bg-white divide-y divide-gray-200">
                                    {sessions.map((session) => (
                                        <tr key={session.id} className="hover:bg-gray-50">
                                            <td className="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                                {new Date(session.session_date).toLocaleDateString()}
                                            </td>
                                            <td className="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                                {session.duration} minutes
                                            </td>
                                            <td className="px-6 py-4 text-sm text-gray-900">
                                                <div className="max-w-xs truncate">
                                                    {session.topics_covered || 'No topics listed'}
                                                </div>
                                            </td>
                                            <td className="px-6 py-4 whitespace-nowrap">
                                                <span className={`px-2 inline-flex text-xs leading-5 font-semibold rounded-full ${
                                                    session.completed_at
                                                        ? 'bg-green-100 text-green-800'
                                                        : 'bg-yellow-100 text-yellow-800'
                                                }`}>
                                                    {session.completed_at ? 'Completed' : 'Scheduled'}
                                                </span>
                                            </td>
                                            <td className="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                                <Link
                                                    href={route('mentorship.sessions.show', [mentorshipRequest.id, session.id])}
                                                    className="text-primary hover:text-primary/90"
                                                >
                                                    View Details
                                                </Link>
                                            </td>
                                        </tr>
                                    ))}
                                </tbody>
                            </table>
                        )}
                    </div>
                </div>
            </div>
        </DashboardLayout>
    );
}
