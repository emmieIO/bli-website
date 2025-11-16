import { useState, FormEvent, ChangeEvent } from 'react';
import { Head, Link, router, usePage } from '@inertiajs/react';
import DashboardLayout from '@/Layouts/DashboardLayout';

interface User {
    id: number;
    name: string;
    email: string;
    phone: string;
}

interface Speaker {
    id: number;
    name: string;
    title: string;
    organization: string;
    bio: string;
    photo: string;
    linkedin: string;
    website: string;
}

interface Event {
    id: number;
    title: string;
    start_date: string;
}

interface SpeakerApplication {
    id: number;
    user: User;
    speaker: Speaker;
    event?: Event;
    topic_title: string;
    topic_description: string;
    session_format: {
        value: string;
    };
    status: 'pending' | 'approved' | 'rejected';
    created_at: string;
    updated_at: string;
    approved_at?: string;
    rejected_at?: string;
}

interface ReviewApplicationProps {
    application: SpeakerApplication;
}

export default function ReviewApplication({ application }: ReviewApplicationProps) {
    const { sideLinks, errors } = usePage().props as any;
    const [showApproveModal, setShowApproveModal] = useState(false);
    const [showRejectModal, setShowRejectModal] = useState(false);
    const [feedback, setFeedback] = useState('');
    const [isSubmitting, setIsSubmitting] = useState(false);

    const handleApprove = (e: FormEvent) => {
        e.preventDefault();
        setIsSubmitting(true);
        router.post(route('admin.speakers.application.approve', application.id), {}, {
            preserveScroll: true,
            onFinish: () => {
                setIsSubmitting(false);
                setShowApproveModal(false);
            },
        });
    };

    const handleReject = (e: FormEvent) => {
        e.preventDefault();
        setIsSubmitting(true);
        router.patch(route('admin.speakers.application.reject', application.id), {
            feedback,
        }, {
            preserveScroll: true,
            onFinish: () => {
                setIsSubmitting(false);
            },
            onSuccess: () => {
                setShowRejectModal(false);
                setFeedback('');
            },
        });
    };

    const handleRevoke = (e: FormEvent) => {
        e.preventDefault();
        setIsSubmitting(true);
        router.patch(route('admin.speakers.application.revoke', application.id), {
            feedback,
        }, {
            preserveScroll: true,
            onFinish: () => {
                setIsSubmitting(false);
            },
            onSuccess: () => {
                setShowRejectModal(false);
                setFeedback('');
            },
        });
    };

    const submittedDate = new Date(application.created_at).toLocaleDateString('en-US', {
        year: 'numeric',
        month: 'short',
        day: 'numeric',
    });

    const formatDate = (dateString: string) => {
        return new Date(dateString).toLocaleDateString('en-US', {
            year: 'numeric',
            month: 'short',
            day: 'numeric',
        });
    };

    const getStatusBadge = () => {
        switch (application.status) {
            case 'approved':
                return (
                    <span className="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                        <i className="fas fa-check w-3 h-3 mr-1"></i>
                        Approved
                    </span>
                );
            case 'rejected':
                return (
                    <span className="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800">
                        <i className="fas fa-times w-3 h-3 mr-1"></i>
                        Rejected
                    </span>
                );
            default:
                return (
                    <span className="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">
                        <i className="fas fa-clock w-3 h-3 mr-1"></i>
                        Pending
                    </span>
                );
        }
    };

    const speakerPhoto = application.speaker?.photo
        ? `/storage/${application.speaker.photo}`
        : null;

    const speakerInitial = application.user?.name?.charAt(0).toUpperCase() || 'S';

    return (
        <DashboardLayout sideLinks={sideLinks}>
            <Head title="Review Speaker Application" />

            <div className="px-4 mx-auto max-w-7xl py-8">
                {/* Header Section */}
                <div className="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 mb-8">
                    <div className="flex items-center gap-3">
                        <Link
                            href={route('admin.speakers.index')}
                            className="inline-flex items-center justify-center overflow-hidden w-10 h-10 bg-white border rounded-full border-gray-300 text-gray-700 text-sm font-medium hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-[#002147] transition shadow-sm"
                            title="Back to Speakers"
                        >
                            <i className="fas fa-arrow-left w-5 h-5"></i>
                        </Link>
                        <div className="p-2.5 rounded-lg bg-[#002147]/10">
                            <i className="fas fa-clipboard-list w-6 h-6 text-[#002147]"></i>
                        </div>
                        <div>
                            <h1 className="text-2xl font-extrabold text-[#002147]">Review Speaker Application</h1>
                            <p className="text-sm text-gray-500">Review speaker session proposal details</p>
                        </div>
                    </div>
                </div>

                <div className="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
                    {/* Application Header */}
                    <div className="px-6 py-5 border-b border-gray-200 bg-gray-50">
                        <div className="flex flex-col md:flex-row md:items-center justify-between gap-4">
                            <div>
                                <h2 className="text-xl font-bold text-gray-900">
                                    {application.topic_title || 'Untitled Session'}
                                </h2>
                                <div className="flex flex-wrap items-center gap-3 mt-2">
                                    <span className="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-800">
                                        Submitted {submittedDate}
                                    </span>
                                    {getStatusBadge()}
                                    {application.event && (
                                        <span className="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                            <i className="fas fa-calendar w-3 h-3 mr-1"></i>
                                            {application.event.title || 'Unknown Event'}
                                        </span>
                                    )}
                                </div>
                            </div>
                        </div>
                    </div>

                    <div className="grid grid-cols-1 lg:grid-cols-3 gap-6 p-6">
                        {/* Session Details */}
                        <div className="lg:col-span-2 space-y-6">
                            {/* Session Information Card */}
                            <div className="border border-gray-200 rounded-xl p-6 bg-gray-50">
                                <h3 className="text-lg font-semibold text-gray-900 mb-4 flex items-center">
                                    <i className="fas fa-presentation-screen w-5 h-5 mr-2 text-[#002147]"></i>
                                    Session Information
                                </h3>
                                <div className="space-y-5">
                                    <div>
                                        <label className="block text-sm font-medium text-gray-700 mb-1">Topic Title</label>
                                        <p className="text-gray-900 text-base font-medium">
                                            {application.topic_title || 'N/A'}
                                        </p>
                                    </div>

                                    <div>
                                        <label className="block text-sm font-medium text-gray-700 mb-1">Description</label>
                                        <div className="prose prose-sm max-w-none text-gray-800 bg-white p-3 rounded-lg border">
                                            {application.topic_description ? (
                                                <p className="whitespace-pre-line">{application.topic_description}</p>
                                            ) : (
                                                <span className="text-gray-400 italic">No description provided.</span>
                                            )}
                                        </div>
                                    </div>

                                    <div className="grid grid-cols-1 md:grid-cols-2 gap-4">
                                        <div>
                                            <label className="block text-sm font-medium text-gray-700 mb-1">Session Format</label>
                                            <span className="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-medium bg-blue-100 text-blue-800 capitalize">
                                                {application.session_format?.value || 'N/A'}
                                            </span>
                                        </div>
                                        <div>
                                            <label className="block text-sm font-medium text-gray-700 mb-1">Application Status</label>
                                            {getStatusBadge()}
                                        </div>
                                    </div>

                                    {application.event && (
                                        <div className="border-t border-gray-200 pt-4">
                                            <label className="block text-sm font-medium text-gray-700 mb-1">Event Information</label>
                                            <div className="flex flex-col sm:flex-row sm:items-center gap-2 text-sm text-gray-600">
                                                <div className="flex items-center">
                                                    <i className="fas fa-calendar w-4 h-4 mr-2 text-gray-400"></i>
                                                    <span>{application.event.title || 'Unknown Event'}</span>
                                                </div>
                                                {application.event.start_date && (
                                                    <div className="flex items-center">
                                                        <span className="mx-2 hidden sm:inline">•</span>
                                                        <i className="fas fa-clock w-4 h-4 mr-2 text-gray-400"></i>
                                                        <span>{formatDate(application.event.start_date)}</span>
                                                    </div>
                                                )}
                                            </div>
                                        </div>
                                    )}
                                </div>
                            </div>

                            {/* Speaker Information Card */}
                            <div className="border border-gray-200 rounded-xl p-6 bg-gray-50">
                                <h3 className="text-lg font-semibold text-gray-900 mb-4 flex items-center">
                                    <i className="fas fa-microphone w-5 h-5 mr-2 text-[#002147]"></i>
                                    Speaker Information
                                </h3>
                                <div className="flex flex-col sm:flex-row gap-6">
                                    <div className="flex-shrink-0">
                                        {speakerPhoto ? (
                                            <img
                                                className="h-20 w-20 rounded-full object-cover border-2 border-gray-200 shadow-sm"
                                                src={speakerPhoto}
                                                alt={application.user?.name || 'Speaker'}
                                            />
                                        ) : (
                                            <div className="h-20 w-20 rounded-full bg-gray-200 flex items-center justify-center border-2 border-dashed border-gray-300">
                                                <span className="text-lg font-bold text-gray-500">
                                                    {speakerInitial}
                                                </span>
                                            </div>
                                        )}
                                    </div>
                                    <div className="flex-grow">
                                        <h4 className="text-xl font-bold text-gray-900 mb-1">
                                            {application.user?.name || 'Unknown Speaker'}
                                        </h4>
                                        <p className="text-[#002147] font-medium mb-4">
                                            {application.speaker?.title || 'N/A'}
                                            {application.speaker?.organization && (
                                                <> at <span className="text-gray-700">{application.speaker.organization}</span></>
                                            )}
                                        </p>

                                        <div className="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                                            <div className="flex items-center text-sm text-gray-600 p-2 bg-white rounded-lg border">
                                                <i className="fas fa-envelope w-4 h-4 mr-2 text-[#002147]"></i>
                                                <span>{application.user?.email || 'No email provided'}</span>
                                            </div>
                                            <div className="flex items-center text-sm text-gray-600 p-2 bg-white rounded-lg border">
                                                <i className="fas fa-phone w-4 h-4 mr-2 text-[#002147]"></i>
                                                <span>{application.user?.phone || 'No phone provided'}</span>
                                            </div>
                                        </div>

                                        <div className="mb-4">
                                            <label className="block text-sm font-medium text-gray-700 mb-2">Bio</label>
                                            <div className="prose prose-sm max-w-none text-gray-800 bg-white p-3 rounded-lg border">
                                                {application.speaker?.bio ? (
                                                    <p className="whitespace-pre-line">{application.speaker.bio}</p>
                                                ) : (
                                                    <span className="text-gray-400 italic">No bio provided.</span>
                                                )}
                                            </div>
                                        </div>

                                        <div className="flex flex-wrap gap-2">
                                            {application.speaker?.linkedin && (
                                                <a
                                                    href={application.speaker.linkedin}
                                                    target="_blank"
                                                    rel="noopener noreferrer"
                                                    className="inline-flex items-center px-3 py-1.5 border border-gray-300 text-xs font-medium rounded-lg text-gray-700 bg-white hover:bg-gray-50 hover:text-[#002147] transition"
                                                >
                                                    <i className="fab fa-linkedin w-3 h-3 mr-1.5"></i>
                                                    LinkedIn
                                                </a>
                                            )}

                                            {application.speaker?.website && (
                                                <a
                                                    href={application.speaker.website}
                                                    target="_blank"
                                                    rel="noopener noreferrer"
                                                    className="inline-flex items-center px-3 py-1.5 border border-gray-300 text-xs font-medium rounded-lg text-gray-700 bg-white hover:bg-gray-50 hover:text-[#002147] transition"
                                                >
                                                    <i className="fas fa-globe w-3 h-3 mr-1.5"></i>
                                                    Website
                                                </a>
                                            )}
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        {/* Review Panel */}
                        <div className="space-y-6">
                            {/* Review Actions Card */}
                            <div className="border border-gray-200 rounded-xl p-6 bg-gray-50">
                                <h3 className="text-lg font-semibold text-gray-900 mb-4 flex items-center">
                                    <i className="fas fa-clipboard-check w-5 h-5 mr-2 text-[#002147]"></i>
                                    Review Actions
                                </h3>
                                <div className="space-y-3">
                                    {application.status === 'pending' && (
                                        <>
                                            {/* Approve */}
                                            <button
                                                onClick={() => setShowApproveModal(true)}
                                                className="w-full inline-flex items-center justify-center px-4 py-2.5 border border-transparent text-sm font-medium rounded-lg shadow-sm text-white bg-[#002147] hover:bg-primary-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-[#002147] transition"
                                            >
                                                <i className="fas fa-check-circle w-4 h-4 mr-2"></i>
                                                Approve Application
                                            </button>

                                            {/* Reject */}
                                            <button
                                                onClick={() => setShowRejectModal(true)}
                                                className="w-full inline-flex items-center justify-center px-4 py-2.5 border border-red-300 text-sm font-medium rounded-lg shadow-sm text-white bg-red-600 hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500 transition"
                                            >
                                                <i className="fas fa-times-circle w-4 h-4 mr-2"></i>
                                                Reject Application
                                            </button>
                                        </>
                                    )}

                                    {application.status === 'approved' && (
                                        <button
                                            onClick={() => setShowRejectModal(true)}
                                            className="w-full inline-flex items-center justify-center px-4 py-2.5 border border-red-300 text-sm font-medium rounded-lg shadow-sm text-white bg-red-600 hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500 transition"
                                        >
                                            <i className="fas fa-undo w-4 h-4 mr-2"></i>
                                            Revoke Approval
                                        </button>
                                    )}

                                    {application.status === 'rejected' && (
                                        <div className="text-center py-4 text-gray-500 text-sm">
                                            This application was previously rejected.
                                            <br /><br />
                                            To reconsider, ask the speaker to resubmit or update their application.
                                        </div>
                                    )}
                                </div>
                            </div>

                            {/* Application Metadata */}
                            <div className="border border-gray-200 rounded-xl p-6 bg-gray-50">
                                <h3 className="text-lg font-semibold text-gray-900 mb-4">Application Timeline</h3>
                                <div className="space-y-3 text-sm">
                                    <div className="flex justify-between">
                                        <span className="text-gray-600">Submitted</span>
                                        <span className="font-medium text-gray-900">{formatDate(application.created_at)}</span>
                                    </div>
                                    {application.approved_at && (
                                        <div className="flex justify-between">
                                            <span className="text-gray-600">Approved</span>
                                            <span className="font-medium text-green-700">{formatDate(application.approved_at)}</span>
                                        </div>
                                    )}
                                    {application.rejected_at && (
                                        <div className="flex justify-between">
                                            <span className="text-gray-600">Rejected</span>
                                            <span className="font-medium text-red-700">{formatDate(application.rejected_at)}</span>
                                        </div>
                                    )}
                                    {application.updated_at && application.updated_at !== application.created_at && (
                                        <div className="flex justify-between">
                                            <span className="text-gray-600">Last Updated</span>
                                            <span className="font-medium text-gray-900">{formatDate(application.updated_at)}</span>
                                        </div>
                                    )}
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {/* Approve Modal */}
            {showApproveModal && (
                <div className="fixed inset-0 bg-gray-600/40 bg-opacity-50 flex items-center justify-center z-50 px-4">
                    <div className="bg-white rounded-xl shadow-xl w-full max-w-md">
                        <div className="p-6">
                            <div className="flex justify-between items-center mb-4">
                                <h3 className="text-xl font-semibold text-gray-900">Approve Speaker Application</h3>
                                <button
                                    onClick={() => setShowApproveModal(false)}
                                    className="text-gray-400 cursor-pointer hover:text-gray-500"
                                >
                                    <i className="fas fa-times w-6 h-6"></i>
                                </button>
                            </div>

                            <div className="mb-6">
                                <div className="mx-auto mb-4 text-green-400 w-12 h-12 flex items-center justify-center">
                                    <i className="fas fa-check-circle w-12 h-12"></i>
                                </div>
                                <p className="text-center text-lg text-gray-700">
                                    Please ensure you have thoroughly reviewed this application before approving.
                                </p>
                            </div>

                            <div className="flex justify-center gap-3">
                                <button
                                    onClick={handleApprove}
                                    disabled={isSubmitting}
                                    className="text-white bg-[#002147] hover:bg-primary-600 focus:ring-4 focus:outline-none focus:ring-[#002147] font-medium rounded-lg text-sm px-5 py-2.5 disabled:opacity-50 disabled:cursor-not-allowed"
                                >
                                    {isSubmitting ? 'Approving...' : 'Approve'}
                                </button>
                                <button
                                    onClick={() => setShowApproveModal(false)}
                                    className="py-2.5 px-5 text-sm font-medium text-gray-900 bg-white rounded-lg border border-gray-200 hover:bg-gray-100"
                                >
                                    Cancel
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            )}

            {/* Reject/Revoke Modal */}
            {showRejectModal && (
                <div className="fixed inset-0 bg-gray-600/40 bg-opacity-50 flex items-center justify-center z-50 px-4">
                    <div className="bg-white rounded-xl shadow-xl w-full max-w-md">
                        <div className="p-6">
                            <div className="flex justify-between items-center mb-4">
                                <h3 className="text-xl font-semibold text-gray-900">
                                    {application.status === 'approved' ? 'Revoke Speaker Approval' : 'Reject Speaker Application'}
                                </h3>
                                <button
                                    onClick={() => setShowRejectModal(false)}
                                    className="text-gray-400 cursor-pointer hover:text-gray-500"
                                >
                                    <i className="fas fa-times w-6 h-6"></i>
                                </button>
                            </div>

                            <form onSubmit={application.status === 'approved' ? handleRevoke : handleReject}>
                                <div className="mb-6">
                                    <label htmlFor="feedback" className="block text-sm font-medium text-gray-700 mb-2">
                                        Please provide a reason for {application.status === 'approved' ? 'revoking approval' : `rejecting ${application.user?.name}'s application`}:
                                    </label>
                                    <textarea
                                        id="feedback"
                                        name="feedback"
                                        rows={4}
                                        value={feedback}
                                        onChange={(e: ChangeEvent<HTMLTextAreaElement>) => setFeedback(e.target.value)}
                                        className="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-[#002147] focus:border-[#002147]"
                                        placeholder="Minimum 50 characters required..."
                                        required
                                        minLength={50}
                                        maxLength={1000}
                                    />
                                    {errors?.feedback && (
                                        <p className="text-sm text-red-500 mt-1">{errors.feedback}</p>
                                    )}
                                    <p className="mt-1 text-xs text-gray-500">
                                        {feedback.length}/1000 characters (min: 50)
                                    </p>
                                </div>

                                <div className="flex justify-center gap-3">
                                    <button
                                        type="submit"
                                        disabled={isSubmitting || feedback.length < 50}
                                        className="text-white bg-red-600 hover:bg-red-700 focus:ring-4 focus:outline-none focus:ring-red-300 font-medium rounded-lg text-sm px-5 py-2.5 disabled:opacity-50 disabled:cursor-not-allowed"
                                    >
                                        {isSubmitting ? 'Processing...' : (application.status === 'approved' ? 'Revoke Approval' : 'Reject Application')}
                                    </button>
                                    <button
                                        type="button"
                                        onClick={() => setShowRejectModal(false)}
                                        className="py-2.5 px-5 text-sm font-medium text-gray-900 bg-white rounded-lg border border-gray-200 hover:bg-gray-100"
                                    >
                                        Cancel
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
