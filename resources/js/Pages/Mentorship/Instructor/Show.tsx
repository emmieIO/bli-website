import { Head, Link, useForm, router } from '@inertiajs/react';
import DashboardLayout from '@/Layouts/DashboardLayout';
import { usePage } from '@inertiajs/react';
import { FormEventHandler, useState } from 'react';

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
    message: string;
    goals: string | null;
    status: string;
    duration_type: string;
    duration_value: number;
    instructor_response: string | null;
    instructor_approved_at: string | null;
    admin_response: string | null;
    admin_approved_at: string | null;
    meeting_link: string | null;
    meeting_schedule: any | null;
    next_session_at: string | null;
    created_at: string;
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

interface Resource {
    id: number;
    file_name: string;
    file_type: string | null;
    file_size: number | null;
    description: string | null;
    category: string;
    created_at: string;
    uploader: {
        name: string;
    };
}

interface Milestone {
    id: number;
    title: string;
    description: string | null;
    due_date: string | null;
    order: number;
    completed_at: string | null;
    completed_by: {
        name: string;
    } | null;
    created_at: string;
}

interface Props {
    mentorshipRequest: MentorshipRequest;
    sessions: Session[];
    resources: Resource[];
    milestones: Milestone[];
}

type TabType = 'overview' | 'sessions' | 'resources' | 'milestones';

export default function Show({ mentorshipRequest, sessions, resources, milestones }: Props) {
    const { sideLinks } = usePage().props as any;
    const [activeTab, setActiveTab] = useState<TabType>('overview');
    const [showApproveModal, setShowApproveModal] = useState(false);
    const [showRejectModal, setShowRejectModal] = useState(false);
    const [showSessionModal, setShowSessionModal] = useState(false);
    const [showResourceModal, setShowResourceModal] = useState(false);
    const [showMilestoneModal, setShowMilestoneModal] = useState(false);

    // Approve/Reject Forms
    const approveForm = useForm({
        response: '',
    });

    const rejectForm = useForm({
        reason: '',
    });

    // Session Form
    const sessionForm = useForm({
        session_date: '',
        duration: 60,
        notes: '',
        topics_covered: '',
        recording_link: '',
    });

    // Resource Form
    const resourceForm = useForm({
        file: null as File | null,
        description: '',
        category: 'general',
    });

    // Milestone Form
    const milestoneForm = useForm({
        title: '',
        description: '',
        due_date: '',
        order: milestones.length,
    });

    const handleApprove: FormEventHandler = (e) => {
        e.preventDefault();
        approveForm.post(route('instructor.mentorship.approve', mentorshipRequest.id), {
            onSuccess: () => {
                setShowApproveModal(false);
            },
        });
    };

    const handleReject: FormEventHandler = (e) => {
        e.preventDefault();
        rejectForm.post(route('instructor.mentorship.reject', mentorshipRequest.id), {
            onSuccess: () => {
                setShowRejectModal(false);
            },
        });
    };

    const handleSessionSubmit: FormEventHandler = (e) => {
        e.preventDefault();
        sessionForm.post(route('mentorship.sessions.store', mentorshipRequest.id), {
            onSuccess: () => {
                setShowSessionModal(false);
                sessionForm.reset();
            },
        });
    };

    const handleResourceSubmit: FormEventHandler = (e) => {
        e.preventDefault();
        resourceForm.post(route('mentorship.resources.store', mentorshipRequest.id), {
            onSuccess: () => {
                setShowResourceModal(false);
                resourceForm.reset();
            },
        });
    };

    const handleMilestoneSubmit: FormEventHandler = (e) => {
        e.preventDefault();
        milestoneForm.post(route('mentorship.milestones.store', mentorshipRequest.id), {
            onSuccess: () => {
                setShowMilestoneModal(false);
                milestoneForm.reset();
            },
        });
    };

    const toggleMilestoneComplete = (milestoneId: number) => {
        router.post(route('mentorship.milestones.toggle', [mentorshipRequest.id, milestoneId]));
    };

    const formatFileSize = (bytes: number | null): string => {
        if (!bytes) return 'Unknown';
        const units = ['B', 'KB', 'MB', 'GB'];
        let size = bytes;
        let unitIndex = 0;
        while (size >= 1024 && unitIndex < units.length - 1) {
            size /= 1024;
            unitIndex++;
        }
        return `${size.toFixed(2)} ${units[unitIndex]}`;
    };

    const getStatusBadge = (status: string) => {
        const badges: Record<string, { bg: string; text: string; label: string }> = {
            pending: { bg: 'bg-yellow-100', text: 'text-yellow-800', label: 'Pending Your Review' },
            instructor_approved: { bg: 'bg-blue-100', text: 'text-blue-800', label: 'Awaiting Admin Approval' },
            admin_approved: { bg: 'bg-green-100', text: 'text-green-800', label: 'Active' },
            instructor_rejected: { bg: 'bg-red-100', text: 'text-red-800', label: 'You Rejected' },
            admin_rejected: { bg: 'bg-red-100', text: 'text-red-800', label: 'Admin Rejected' },
            cancelled: { bg: 'bg-gray-100', text: 'text-gray-800', label: 'Cancelled' },
            ended: { bg: 'bg-gray-100', text: 'text-gray-800', label: 'Ended' },
        };
        const badge = badges[status] || { bg: 'bg-gray-100', text: 'text-gray-800', label: status };
        return (
            <span className={`inline-flex items-center px-3 py-1 rounded-full text-sm font-medium ${badge.bg} ${badge.text}`}>
                {badge.label}
            </span>
        );
    };

    return (
        <DashboardLayout sideLinks={sideLinks}>
            <Head title="Mentorship Details" />

            <div className="py-12">
                <div className="max-w-7xl mx-auto sm:px-6 lg:px-8">
                    {/* Header */}
                    <div className="mb-8">
                        <Link
                            href={route('instructor.mentorship.index')}
                            className="text-primary hover:text-primary/90 mb-4 inline-flex items-center"
                        >
                            <svg className="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path strokeLinecap="round" strokeLinejoin="round" strokeWidth={2} d="M15 19l-7-7 7-7" />
                            </svg>
                            Back to Mentorship Requests
                        </Link>
                        <div className="flex justify-between items-center mt-4">
                            <div>
                                <h1 className="text-3xl font-bold text-gray-900">Mentorship with {mentorshipRequest.student.name}</h1>
                                <p className="mt-2 text-sm text-gray-600">
                                    Requested on {new Date(mentorshipRequest.created_at).toLocaleDateString()}
                                </p>
                            </div>
                            <div>
                                {getStatusBadge(mentorshipRequest.status)}
                            </div>
                        </div>
                    </div>

                    {/* Tabs */}
                    <div className="bg-white shadow rounded-lg mb-6">
                        <div className="border-b border-gray-200">
                            <nav className="-mb-px flex space-x-8 px-6" aria-label="Tabs">
                                <button
                                    onClick={() => setActiveTab('overview')}
                                    className={`${
                                        activeTab === 'overview'
                                            ? 'border-primary text-primary'
                                            : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300'
                                    } whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm transition-colors`}
                                >
                                    Overview
                                </button>
                                {mentorshipRequest.status === 'admin_approved' && (
                                    <>
                                        <button
                                            onClick={() => setActiveTab('sessions')}
                                            className={`${
                                                activeTab === 'sessions'
                                                    ? 'border-primary text-primary'
                                                    : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300'
                                            } whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm transition-colors`}
                                        >
                                            Sessions ({sessions.length})
                                        </button>
                                        <button
                                            onClick={() => setActiveTab('resources')}
                                            className={`${
                                                activeTab === 'resources'
                                                    ? 'border-primary text-primary'
                                                    : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300'
                                            } whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm transition-colors`}
                                        >
                                            Resources ({resources.length})
                                        </button>
                                        <button
                                            onClick={() => setActiveTab('milestones')}
                                            className={`${
                                                activeTab === 'milestones'
                                                    ? 'border-primary text-primary'
                                                    : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300'
                                            } whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm transition-colors`}
                                        >
                                            Milestones ({milestones.filter(m => !m.completed_at).length}/{milestones.length})
                                        </button>
                                    </>
                                )}
                            </nav>
                        </div>

                        {/* Tab Content */}
                        <div className="p-6">
                            {/* Overview Tab */}
                            {activeTab === 'overview' && (
                                <div className="space-y-6">
                                    {/* Request Details */}
                                    <div>
                                        <h3 className="text-lg font-semibold text-gray-900 mb-4">Request Details</h3>
                                        <div className="bg-gray-50 rounded-lg p-4 space-y-3">
                                            <div>
                                                <label className="block text-sm font-medium text-gray-700">Student's Message</label>
                                                <p className="mt-1 text-sm text-gray-900">{mentorshipRequest.message}</p>
                                            </div>
                                            {mentorshipRequest.goals && (
                                                <div>
                                                    <label className="block text-sm font-medium text-gray-700">Student's Goals</label>
                                                    <p className="mt-1 text-sm text-gray-900">{mentorshipRequest.goals}</p>
                                                </div>
                                            )}
                                            <div className="grid grid-cols-2 gap-4">
                                                <div>
                                                    <label className="block text-sm font-medium text-gray-700">Duration Type</label>
                                                    <p className="mt-1 text-sm text-gray-900 capitalize">{mentorshipRequest.duration_type}</p>
                                                </div>
                                                <div>
                                                    <label className="block text-sm font-medium text-gray-700">Duration Value</label>
                                                    <p className="mt-1 text-sm text-gray-900">{mentorshipRequest.duration_value} {mentorshipRequest.duration_type}(s)</p>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    {/* Approval Status */}
                                    {mentorshipRequest.status !== 'pending' && (
                                        <div>
                                            <h3 className="text-lg font-semibold text-gray-900 mb-4">Approval Status</h3>
                                            <div className="space-y-4">
                                                {/* Instructor Review */}
                                                <div className="flex items-start">
                                                    <div className={`flex-shrink-0 h-10 w-10 rounded-full flex items-center justify-center ${
                                                        mentorshipRequest.instructor_approved_at ? 'bg-green-100' : 'bg-red-100'
                                                    }`}>
                                                        {mentorshipRequest.instructor_approved_at ? (
                                                            <svg className="h-6 w-6 text-green-600" fill="currentColor" viewBox="0 0 20 20">
                                                                <path fillRule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clipRule="evenodd" />
                                                            </svg>
                                                        ) : (
                                                            <svg className="h-6 w-6 text-red-600" fill="currentColor" viewBox="0 0 20 20">
                                                                <path fillRule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clipRule="evenodd" />
                                                            </svg>
                                                        )}
                                                    </div>
                                                    <div className="ml-4 flex-1">
                                                        <h4 className="text-sm font-medium text-gray-900">Your Review</h4>
                                                        <p className="mt-1 text-sm text-gray-500">
                                                            {mentorshipRequest.instructor_approved_at
                                                                ? `Approved on ${new Date(mentorshipRequest.instructor_approved_at).toLocaleDateString()}`
                                                                : 'Rejected'}
                                                        </p>
                                                        {mentorshipRequest.instructor_response && (
                                                            <p className="mt-2 text-sm text-gray-700 bg-white p-3 rounded border border-gray-200">
                                                                {mentorshipRequest.instructor_response}
                                                            </p>
                                                        )}
                                                    </div>
                                                </div>

                                                {/* Admin Review */}
                                                {mentorshipRequest.instructor_approved_at && (
                                                    <div className="flex items-start">
                                                        <div className={`flex-shrink-0 h-10 w-10 rounded-full flex items-center justify-center ${
                                                            mentorshipRequest.admin_approved_at ? 'bg-green-100' : 'bg-yellow-100'
                                                        }`}>
                                                            {mentorshipRequest.admin_approved_at ? (
                                                                <svg className="h-6 w-6 text-green-600" fill="currentColor" viewBox="0 0 20 20">
                                                                    <path fillRule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clipRule="evenodd" />
                                                                </svg>
                                                            ) : (
                                                                <svg className="h-6 w-6 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                    <path strokeLinecap="round" strokeLinejoin="round" strokeWidth={2} d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                                                </svg>
                                                            )}
                                                        </div>
                                                        <div className="ml-4 flex-1">
                                                            <h4 className="text-sm font-medium text-gray-900">Admin Review</h4>
                                                            <p className="mt-1 text-sm text-gray-500">
                                                                {mentorshipRequest.admin_approved_at
                                                                    ? `Approved on ${new Date(mentorshipRequest.admin_approved_at).toLocaleDateString()}`
                                                                    : 'Waiting for admin approval'}
                                                            </p>
                                                            {mentorshipRequest.admin_response && (
                                                                <p className="mt-2 text-sm text-gray-700 bg-white p-3 rounded border border-gray-200">
                                                                    {mentorshipRequest.admin_response}
                                                                </p>
                                                            )}
                                                        </div>
                                                    </div>
                                                )}
                                            </div>
                                        </div>
                                    )}

                                    {/* Meeting Info */}
                                    {mentorshipRequest.status === 'admin_approved' && mentorshipRequest.meeting_link && (
                                        <div>
                                            <h3 className="text-lg font-semibold text-gray-900 mb-4">Meeting Information</h3>
                                            <div className="bg-blue-50 rounded-lg p-4 space-y-3">
                                                <div>
                                                    <label className="block text-sm font-medium text-blue-900">Meeting Link</label>
                                                    <a
                                                        href={mentorshipRequest.meeting_link}
                                                        target="_blank"
                                                        rel="noopener noreferrer"
                                                        className="mt-1 text-sm text-blue-600 hover:text-blue-800 underline"
                                                    >
                                                        {mentorshipRequest.meeting_link}
                                                    </a>
                                                </div>
                                                {mentorshipRequest.next_session_at && (
                                                    <div>
                                                        <label className="block text-sm font-medium text-blue-900">Next Session</label>
                                                        <p className="mt-1 text-sm text-blue-800">
                                                            {new Date(mentorshipRequest.next_session_at).toLocaleString()}
                                                        </p>
                                                    </div>
                                                )}
                                            </div>
                                        </div>
                                    )}

                                    {/* Actions */}
                                    {mentorshipRequest.status === 'pending' && (
                                        <div>
                                            <h3 className="text-lg font-semibold text-gray-900 mb-4">Actions</h3>
                                            <div className="flex space-x-3">
                                                <button
                                                    onClick={() => setShowApproveModal(true)}
                                                    className="px-4 py-2 bg-green-600 text-white rounded-md hover:bg-green-700"
                                                >
                                                    Approve Request
                                                </button>
                                                <button
                                                    onClick={() => setShowRejectModal(true)}
                                                    className="px-4 py-2 bg-red-600 text-white rounded-md hover:bg-red-700"
                                                >
                                                    Reject Request
                                                </button>
                                            </div>
                                        </div>
                                    )}
                                </div>
                            )}

                            {/* Sessions Tab - Same as Student */}
                            {activeTab === 'sessions' && (
                                <div>
                                    <div className="flex justify-between items-center mb-6">
                                        <h3 className="text-lg font-semibold text-gray-900">Sessions</h3>
                                        <button
                                            onClick={() => setShowSessionModal(true)}
                                            className="px-4 py-2 bg-primary text-white rounded-md hover:bg-primary/90"
                                        >
                                            Add Session
                                        </button>
                                    </div>

                                    {sessions.length === 0 ? (
                                        <div className="text-center py-12 bg-gray-50 rounded-lg">
                                            <svg className="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path strokeLinecap="round" strokeLinejoin="round" strokeWidth="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                            </svg>
                                            <h3 className="mt-2 text-sm font-medium text-gray-900">No sessions yet</h3>
                                            <p className="mt-1 text-sm text-gray-500">Get started by creating a new session.</p>
                                        </div>
                                    ) : (
                                        <div className="overflow-hidden shadow ring-1 ring-black ring-opacity-5 rounded-lg">
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
                                                                {session.duration} min
                                                            </td>
                                                            <td className="px-6 py-4 text-sm text-gray-900">
                                                                <div className="max-w-xs truncate">
                                                                    {session.topics_covered || 'No topics listed'}
                                                                </div>
                                                            </td>
                                                            <td className="px-6 py-4 whitespace-nowrap">
                                                                <span className={`px-2 inline-flex text-xs leading-5 font-semibold rounded-full ${
                                                                    session.completed_at ? 'bg-green-100 text-green-800' : 'bg-yellow-100 text-yellow-800'
                                                                }`}>
                                                                    {session.completed_at ? 'Completed' : 'Scheduled'}
                                                                </span>
                                                            </td>
                                                            <td className="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                                                <Link
                                                                    href={route('mentorship.sessions.show', [mentorshipRequest.id, session.id])}
                                                                    className="text-primary hover:text-primary/90"
                                                                >
                                                                    View
                                                                </Link>
                                                            </td>
                                                        </tr>
                                                    ))}
                                                </tbody>
                                            </table>
                                        </div>
                                    )}
                                </div>
                            )}

                            {/* Resources Tab - Same as Student */}
                            {activeTab === 'resources' && (
                                <div>
                                    <div className="flex justify-between items-center mb-6">
                                        <h3 className="text-lg font-semibold text-gray-900">Resources</h3>
                                        <button
                                            onClick={() => setShowResourceModal(true)}
                                            className="px-4 py-2 bg-primary text-white rounded-md hover:bg-primary/90"
                                        >
                                            Upload Resource
                                        </button>
                                    </div>

                                    {resources.length === 0 ? (
                                        <div className="text-center py-12 bg-gray-50 rounded-lg">
                                            <svg className="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path strokeLinecap="round" strokeLinejoin="round" strokeWidth="2" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z" />
                                            </svg>
                                            <h3 className="mt-2 text-sm font-medium text-gray-900">No resources yet</h3>
                                            <p className="mt-1 text-sm text-gray-500">Upload your first learning resource.</p>
                                        </div>
                                    ) : (
                                        <div className="overflow-hidden shadow ring-1 ring-black ring-opacity-5 rounded-lg">
                                            <table className="min-w-full divide-y divide-gray-200">
                                                <thead className="bg-gray-50">
                                                    <tr>
                                                        <th className="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">File Name</th>
                                                        <th className="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Category</th>
                                                        <th className="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Size</th>
                                                        <th className="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Uploaded By</th>
                                                        <th className="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                                                    </tr>
                                                </thead>
                                                <tbody className="bg-white divide-y divide-gray-200">
                                                    {resources.map((resource) => (
                                                        <tr key={resource.id} className="hover:bg-gray-50">
                                                            <td className="px-6 py-4 whitespace-nowrap">
                                                                <div className="flex items-center">
                                                                    <svg className="h-5 w-5 text-gray-400 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                        <path strokeLinecap="round" strokeLinejoin="round" strokeWidth="2" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z" />
                                                                    </svg>
                                                                    <div>
                                                                        <div className="text-sm font-medium text-gray-900">{resource.file_name}</div>
                                                                        {resource.description && (
                                                                            <div className="text-sm text-gray-500">{resource.description}</div>
                                                                        )}
                                                                    </div>
                                                                </div>
                                                            </td>
                                                            <td className="px-6 py-4 whitespace-nowrap">
                                                                <span className="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-blue-100 text-blue-800">
                                                                    {resource.category}
                                                                </span>
                                                            </td>
                                                            <td className="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                                                {formatFileSize(resource.file_size)}
                                                            </td>
                                                            <td className="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                                                {resource.uploader.name}
                                                            </td>
                                                            <td className="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                                                <Link
                                                                    href={route('mentorship.resources.download', [mentorshipRequest.id, resource.id])}
                                                                    className="text-primary hover:text-primary/90"
                                                                >
                                                                    Download
                                                                </Link>
                                                            </td>
                                                        </tr>
                                                    ))}
                                                </tbody>
                                            </table>
                                        </div>
                                    )}
                                </div>
                            )}

                            {/* Milestones Tab - Same as Student */}
                            {activeTab === 'milestones' && (
                                <div>
                                    <div className="flex justify-between items-center mb-6">
                                        <h3 className="text-lg font-semibold text-gray-900">Milestones</h3>
                                        <button
                                            onClick={() => setShowMilestoneModal(true)}
                                            className="px-4 py-2 bg-primary text-white rounded-md hover:bg-primary/90"
                                        >
                                            Add Milestone
                                        </button>
                                    </div>

                                    {milestones.length === 0 ? (
                                        <div className="text-center py-12 bg-gray-50 rounded-lg">
                                            <svg className="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path strokeLinecap="round" strokeLinejoin="round" strokeWidth="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                            </svg>
                                            <h3 className="mt-2 text-sm font-medium text-gray-900">No milestones yet</h3>
                                            <p className="mt-1 text-sm text-gray-500">Create your first learning milestone.</p>
                                        </div>
                                    ) : (
                                        <div className="space-y-4">
                                            {milestones.map((milestone) => (
                                                <div
                                                    key={milestone.id}
                                                    className={`border rounded-lg p-4 ${
                                                        milestone.completed_at ? 'bg-green-50 border-green-200' : 'bg-white border-gray-200'
                                                    }`}
                                                >
                                                    <div className="flex items-start">
                                                        <div className="flex-shrink-0">
                                                            <button
                                                                onClick={() => toggleMilestoneComplete(milestone.id)}
                                                                className="mt-1"
                                                            >
                                                                {milestone.completed_at ? (
                                                                    <svg className="h-6 w-6 text-green-600" fill="currentColor" viewBox="0 0 20 20">
                                                                        <path fillRule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clipRule="evenodd" />
                                                                    </svg>
                                                                ) : (
                                                                    <svg className="h-6 w-6 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                        <circle cx="12" cy="12" r="10" strokeWidth="2" />
                                                                    </svg>
                                                                )}
                                                            </button>
                                                        </div>
                                                        <div className="ml-3 flex-1">
                                                            <div className="flex items-center justify-between">
                                                                <h3 className={`text-lg font-medium ${
                                                                    milestone.completed_at ? 'text-green-900 line-through' : 'text-gray-900'
                                                                }`}>
                                                                    {milestone.title}
                                                                </h3>
                                                                {milestone.due_date && (
                                                                    <span className={`text-sm ${
                                                                        milestone.completed_at
                                                                            ? 'text-green-600'
                                                                            : new Date(milestone.due_date) < new Date()
                                                                            ? 'text-red-600'
                                                                            : 'text-gray-500'
                                                                    }`}>
                                                                        Due: {new Date(milestone.due_date).toLocaleDateString()}
                                                                    </span>
                                                                )}
                                                            </div>
                                                            {milestone.description && (
                                                                <p className={`mt-1 text-sm ${
                                                                    milestone.completed_at ? 'text-green-700' : 'text-gray-600'
                                                                }`}>
                                                                    {milestone.description}
                                                                </p>
                                                            )}
                                                            {milestone.completed_at && milestone.completed_by && (
                                                                <p className="mt-2 text-xs text-green-600">
                                                                    Completed by {milestone.completed_by.name} on {new Date(milestone.completed_at).toLocaleDateString()}
                                                                </p>
                                                            )}
                                                        </div>
                                                    </div>
                                                </div>
                                            ))}
                                        </div>
                                    )}
                                </div>
                            )}
                        </div>
                    </div>
                </div>
            </div>

            {/* Approve Modal */}
            {showApproveModal && (
                <div className="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50">
                    <div className="bg-white rounded-lg p-6 max-w-md w-full mx-4">
                        <h3 className="text-lg font-semibold mb-4">Approve Mentorship Request</h3>
                        <form onSubmit={handleApprove}>
                            <div className="mb-4">
                                <label className="block text-sm font-medium text-gray-700 mb-2">
                                    Response (Optional)
                                </label>
                                <textarea
                                    value={approveForm.data.response}
                                    onChange={(e) => approveForm.setData('response', e.target.value)}
                                    rows={4}
                                    className="w-full rounded-md border-gray-300 shadow-sm focus:border-primary focus:ring-primary"
                                    placeholder="Add a message for the student..."
                                />
                            </div>
                            <div className="flex justify-end space-x-3">
                                <button
                                    type="button"
                                    onClick={() => setShowApproveModal(false)}
                                    className="px-4 py-2 border border-gray-300 rounded-md text-gray-700 hover:bg-gray-50"
                                >
                                    Cancel
                                </button>
                                <button
                                    type="submit"
                                    disabled={approveForm.processing}
                                    className="px-4 py-2 bg-green-600 text-white rounded-md hover:bg-green-700 disabled:opacity-50"
                                >
                                    {approveForm.processing ? 'Approving...' : 'Approve'}
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            )}

            {/* Reject Modal */}
            {showRejectModal && (
                <div className="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50">
                    <div className="bg-white rounded-lg p-6 max-w-md w-full mx-4">
                        <h3 className="text-lg font-semibold mb-4">Reject Mentorship Request</h3>
                        <form onSubmit={handleReject}>
                            <div className="mb-4">
                                <label className="block text-sm font-medium text-gray-700 mb-2">
                                    Reason for Rejection <span className="text-red-500">*</span>
                                </label>
                                <textarea
                                    value={rejectForm.data.reason}
                                    onChange={(e) => rejectForm.setData('reason', e.target.value)}
                                    rows={4}
                                    className="w-full rounded-md border-gray-300 shadow-sm focus:border-primary focus:ring-primary"
                                    placeholder="Explain why you cannot accept this mentorship request..."
                                    required
                                />
                                {rejectForm.errors.reason && (
                                    <p className="mt-1 text-sm text-red-600">{rejectForm.errors.reason}</p>
                                )}
                            </div>
                            <div className="flex justify-end space-x-3">
                                <button
                                    type="button"
                                    onClick={() => setShowRejectModal(false)}
                                    className="px-4 py-2 border border-gray-300 rounded-md text-gray-700 hover:bg-gray-50"
                                >
                                    Cancel
                                </button>
                                <button
                                    type="submit"
                                    disabled={rejectForm.processing}
                                    className="px-4 py-2 bg-red-600 text-white rounded-md hover:bg-red-700 disabled:opacity-50"
                                >
                                    {rejectForm.processing ? 'Rejecting...' : 'Reject'}
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            )}

            {/* Session Modal */}
            {showSessionModal && (
                <div className="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50">
                    <div className="bg-white rounded-lg p-6 max-w-md w-full mx-4">
                        <h3 className="text-lg font-semibold mb-4">Add Session</h3>
                        <form onSubmit={handleSessionSubmit}>
                            <div className="mb-4">
                                <label className="block text-sm font-medium text-gray-700 mb-2">
                                    Session Date <span className="text-red-500">*</span>
                                </label>
                                <input
                                    type="datetime-local"
                                    value={sessionForm.data.session_date}
                                    onChange={(e) => sessionForm.setData('session_date', e.target.value)}
                                    className="w-full rounded-md border-gray-300 shadow-sm focus:border-primary focus:ring-primary"
                                    required
                                />
                            </div>
                            <div className="mb-4">
                                <label className="block text-sm font-medium text-gray-700 mb-2">
                                    Duration (minutes) <span className="text-red-500">*</span>
                                </label>
                                <input
                                    type="number"
                                    value={sessionForm.data.duration}
                                    onChange={(e) => sessionForm.setData('duration', parseInt(e.target.value))}
                                    className="w-full rounded-md border-gray-300 shadow-sm focus:border-primary focus:ring-primary"
                                    min="1"
                                    max="480"
                                    required
                                />
                            </div>
                            <div className="mb-4">
                                <label className="block text-sm font-medium text-gray-700 mb-2">
                                    Topics Covered
                                </label>
                                <textarea
                                    value={sessionForm.data.topics_covered}
                                    onChange={(e) => sessionForm.setData('topics_covered', e.target.value)}
                                    rows={3}
                                    className="w-full rounded-md border-gray-300 shadow-sm focus:border-primary focus:ring-primary"
                                    placeholder="What topics will be covered..."
                                />
                            </div>
                            <div className="mb-4">
                                <label className="block text-sm font-medium text-gray-700 mb-2">
                                    Notes
                                </label>
                                <textarea
                                    value={sessionForm.data.notes}
                                    onChange={(e) => sessionForm.setData('notes', e.target.value)}
                                    rows={3}
                                    className="w-full rounded-md border-gray-300 shadow-sm focus:border-primary focus:ring-primary"
                                    placeholder="Additional notes..."
                                />
                            </div>
                            <div className="mb-4">
                                <label className="block text-sm font-medium text-gray-700 mb-2">
                                    Recording Link
                                </label>
                                <input
                                    type="url"
                                    value={sessionForm.data.recording_link}
                                    onChange={(e) => sessionForm.setData('recording_link', e.target.value)}
                                    className="w-full rounded-md border-gray-300 shadow-sm focus:border-primary focus:ring-primary"
                                    placeholder="https://..."
                                />
                            </div>
                            <div className="flex justify-end space-x-3">
                                <button
                                    type="button"
                                    onClick={() => setShowSessionModal(false)}
                                    className="px-4 py-2 border border-gray-300 rounded-md text-gray-700 hover:bg-gray-50"
                                >
                                    Cancel
                                </button>
                                <button
                                    type="submit"
                                    disabled={sessionForm.processing}
                                    className="px-4 py-2 bg-primary text-white rounded-md hover:bg-primary/90 disabled:opacity-50"
                                >
                                    {sessionForm.processing ? 'Adding...' : 'Add Session'}
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            )}

            {/* Resource Modal */}
            {showResourceModal && (
                <div className="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50">
                    <div className="bg-white rounded-lg p-6 max-w-md w-full mx-4">
                        <h3 className="text-lg font-semibold mb-4">Upload Resource</h3>
                        <form onSubmit={handleResourceSubmit}>
                            <div className="mb-4">
                                <label className="block text-sm font-medium text-gray-700 mb-2">
                                    File <span className="text-red-500">*</span>
                                </label>
                                <input
                                    type="file"
                                    onChange={(e) => resourceForm.setData('file', e.target.files?.[0] || null)}
                                    className="w-full"
                                    required
                                />
                            </div>
                            <div className="mb-4">
                                <label className="block text-sm font-medium text-gray-700 mb-2">
                                    Category
                                </label>
                                <select
                                    value={resourceForm.data.category}
                                    onChange={(e) => resourceForm.setData('category', e.target.value)}
                                    className="w-full rounded-md border-gray-300 shadow-sm focus:border-primary focus:ring-primary"
                                >
                                    <option value="general">General</option>
                                    <option value="assignment">Assignment</option>
                                    <option value="reference">Reference</option>
                                    <option value="material">Material</option>
                                </select>
                            </div>
                            <div className="mb-4">
                                <label className="block text-sm font-medium text-gray-700 mb-2">
                                    Description
                                </label>
                                <textarea
                                    value={resourceForm.data.description}
                                    onChange={(e) => resourceForm.setData('description', e.target.value)}
                                    rows={3}
                                    className="w-full rounded-md border-gray-300 shadow-sm focus:border-primary focus:ring-primary"
                                    placeholder="Brief description of this resource..."
                                />
                            </div>
                            <div className="flex justify-end space-x-3">
                                <button
                                    type="button"
                                    onClick={() => setShowResourceModal(false)}
                                    className="px-4 py-2 border border-gray-300 rounded-md text-gray-700 hover:bg-gray-50"
                                >
                                    Cancel
                                </button>
                                <button
                                    type="submit"
                                    disabled={resourceForm.processing}
                                    className="px-4 py-2 bg-primary text-white rounded-md hover:bg-primary/90 disabled:opacity-50"
                                >
                                    {resourceForm.processing ? 'Uploading...' : 'Upload'}
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            )}

            {/* Milestone Modal */}
            {showMilestoneModal && (
                <div className="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50">
                    <div className="bg-white rounded-lg p-6 max-w-md w-full mx-4">
                        <h3 className="text-lg font-semibold mb-4">Create Milestone</h3>
                        <form onSubmit={handleMilestoneSubmit}>
                            <div className="mb-4">
                                <label className="block text-sm font-medium text-gray-700 mb-2">
                                    Title <span className="text-red-500">*</span>
                                </label>
                                <input
                                    type="text"
                                    value={milestoneForm.data.title}
                                    onChange={(e) => milestoneForm.setData('title', e.target.value)}
                                    className="w-full rounded-md border-gray-300 shadow-sm focus:border-primary focus:ring-primary"
                                    placeholder="E.g., Complete React fundamentals"
                                    required
                                />
                            </div>
                            <div className="mb-4">
                                <label className="block text-sm font-medium text-gray-700 mb-2">
                                    Description
                                </label>
                                <textarea
                                    value={milestoneForm.data.description}
                                    onChange={(e) => milestoneForm.setData('description', e.target.value)}
                                    rows={3}
                                    className="w-full rounded-md border-gray-300 shadow-sm focus:border-primary focus:ring-primary"
                                    placeholder="Details about this milestone..."
                                />
                            </div>
                            <div className="mb-4">
                                <label className="block text-sm font-medium text-gray-700 mb-2">
                                    Due Date
                                </label>
                                <input
                                    type="date"
                                    value={milestoneForm.data.due_date}
                                    onChange={(e) => milestoneForm.setData('due_date', e.target.value)}
                                    className="w-full rounded-md border-gray-300 shadow-sm focus:border-primary focus:ring-primary"
                                />
                            </div>
                            <div className="flex justify-end space-x-3">
                                <button
                                    type="button"
                                    onClick={() => setShowMilestoneModal(false)}
                                    className="px-4 py-2 border border-gray-300 rounded-md text-gray-700 hover:bg-gray-50"
                                >
                                    Cancel
                                </button>
                                <button
                                    type="submit"
                                    disabled={milestoneForm.processing}
                                    className="px-4 py-2 bg-primary text-white rounded-md hover:bg-primary/90 disabled:opacity-50"
                                >
                                    {milestoneForm.processing ? 'Creating...' : 'Create'}
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            )}
        </DashboardLayout>
    );
}
