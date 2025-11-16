import { useState, FormEvent, ChangeEvent } from 'react';
import { Head, Link, router, usePage } from '@inertiajs/react';
import DashboardLayout from '@/Layouts/DashboardLayout';
import Textarea from '@/Components/Textarea';

interface User {
    id: number;
    name: string;
    email: string;
    phone: string;
    headline: string;
    linkedin_url: string;
    website: string;
    resume_path: string;
}

interface InstructorProfile {
    id: number;
    application_id: string;
    user: User;
    bio: string;
    experience_years: number;
    area_of_expertise: string;
    teaching_history: string;
    intro_video_url: string;
    resume_path: string;
    status: string;
    is_approved: boolean;
    created_at: string;
}

interface ViewApplicationProps {
    application: InstructorProfile;
}

export default function ViewApplication({ application }: ViewApplicationProps) {
    const { sideLinks, errors } = usePage().props as any;
    const [showRejectModal, setShowRejectModal] = useState(false);
    const [showApproveModal, setShowApproveModal] = useState(false);
    const [rejectionReason, setRejectionReason] = useState('');
    const [isSubmitting, setIsSubmitting] = useState(false);

    const handleApprove = () => {
        setIsSubmitting(true);
        router.patch(route('admin.instructors.applications.approve', application.id), {}, {
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
        router.post(route('admin.instructors.applications.deny', application.id), {
            rejection_reason: rejectionReason,
        }, {
            preserveScroll: true,
            onFinish: () => {
                setIsSubmitting(false);
            },
            onSuccess: () => {
                setShowRejectModal(false);
                setRejectionReason('');
            },
        });
    };

    const expertiseArray = application.area_of_expertise?.split(',').map(s => s.trim()) || [];
    const skillsCount = expertiseArray.length;
    const submittedDate = new Date(application.created_at).toLocaleDateString('en-US', {
        year: 'numeric',
        month: 'short',
        day: 'numeric',
    });

    const getStatusColor = (status: string) => {
        switch (status) {
            case 'approved':
                return 'bg-green-100 text-green-800';
            case 'draft':
            case 'pending':
                return 'bg-yellow-100 text-yellow-800';
            default:
                return 'bg-red-100 text-red-800';
        }
    };

    return (
        <DashboardLayout sideLinks={sideLinks}>
            <Head title="Instructor Overview" />

            <div className="container mx-auto px-4 py-8">
                {/* Application Header with Stats */}
                <div className="flex flex-col md:flex-row justify-between items-start md:items-center mb-8 gap-4">
                    <div>
                        <h1 className="text-3xl font-bold text-[#002147]">Instructor Overview</h1>
                        <p className="text-gray-600">Submitted on {submittedDate}</p>
                    </div>

                    <div className="flex flex-col md:flex-row items-start md:items-center gap-4">
                        {/* Status Badge */}
                        <span className={`px-4 py-2 rounded-full text-sm font-semibold ${getStatusColor(application.status)}`}>
                            {application.status.charAt(0).toUpperCase() + application.status.slice(1)}
                        </span>

                        {/* Stats Cards */}
                        <div className="flex gap-2">
                            <div className="bg-[#f0f8ff] px-3 py-1 rounded-lg flex items-center">
                                <i className="fas fa-clock w-4 h-4 text-[#002147] mr-1"></i>
                                <span className="text-xs text-[#002147]">{application.experience_years} yrs exp</span>
                            </div>
                            <div className="bg-[#f0f8ff] px-3 py-1 rounded-lg flex items-center">
                                <i className="fas fa-lightbulb w-4 h-4 text-[#002147] mr-1"></i>
                                <span className="text-xs text-[#002147]">{skillsCount} skills</span>
                            </div>
                        </div>
                    </div>
                </div>

                {/* Main Application Card */}
                <div className="bg-white rounded-xl shadow-lg overflow-hidden mb-8 border border-gray-100">
                    <div className="bg-white p-4 rounded-lg shadow-sm border border-gray-200 w-full md:w-auto">
                        <div className="flex items-center space-x-4">
                            <div className="bg-[#e6f7ff] p-3 rounded-full">
                                <i className="fas fa-user w-6 h-6 text-[#002147]"></i>
                            </div>
                            <div>
                                <h3 className="font-medium text-gray-900">{application.user.name}</h3>
                                <div className="flex items-center text-sm text-gray-500 mt-1">
                                    <i className="fas fa-envelope w-4 h-4 mr-1"></i>
                                    {application.user.email}
                                </div>
                                {application.user.phone && (
                                    <div className="flex items-center text-sm text-gray-500 mt-1">
                                        <i className="fas fa-phone w-4 h-4 mr-1"></i>
                                        {application.user.phone}
                                    </div>
                                )}
                            </div>
                        </div>
                    </div>
                </div>

                {/* Basic Information Section */}
                <div className="border-b border-gray-200 px-6 py-4">
                    <h2 className="text-xl font-semibold text-[#002147] mb-4 flex items-center">
                        <i className="fas fa-user w-5 h-5 mr-2 text-[#002147]"></i>
                        Basic Information
                    </h2>
                    <div className="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                        <div>
                            <p className="text-sm text-gray-500 font-medium">Profile ID</p>
                            <p className="font-medium text-gray-800">{application.application_id}</p>
                        </div>
                        <div>
                            <p className="text-sm text-gray-500 font-medium">Professional Headline</p>
                            <p className="font-medium text-gray-800">{application.user.headline}</p>
                        </div>
                        <div>
                            <p className="text-sm text-gray-500 font-medium">Years of Experience</p>
                            <div className="flex items-center">
                                <div className="w-full bg-gray-200 rounded-full h-2.5 mr-2">
                                    <div
                                        className="bg-[#002147] h-2.5 rounded-full"
                                        style={{ width: `${Math.min(100, (application.experience_years / 10) * 100)}%` }}
                                    ></div>
                                </div>
                                <span className="font-medium text-gray-800">{application.experience_years}</span>
                            </div>
                        </div>
                    </div>
                </div>

                {/* Expertise Section */}
                <div className="border-b border-gray-200 px-6 py-4">
                    <h2 className="text-xl font-semibold text-[#002147] mb-4 flex items-center">
                        <i className="fas fa-lightbulb w-5 h-5 mr-2 text-[#002147]"></i>
                        Areas of Expertise
                    </h2>
                    <div className="flex flex-wrap gap-2">
                        {expertiseArray.map((expertise, index) => (
                            <span key={index} className="px-3 py-1 bg-[#e6f7ff] text-[#002147] text-sm rounded-full flex items-center">
                                <i className="fas fa-check w-3 h-3 mr-1 text-[#002147]"></i>
                                {expertise}
                            </span>
                        ))}
                    </div>
                </div>

                {/* Bio Section */}
                <div className="border-b border-gray-200 px-6 py-4">
                    <h2 className="text-xl font-semibold text-[#002147] mb-4 flex items-center">
                        <i className="fas fa-user w-5 h-5 mr-2 text-[#002147]"></i>
                        Professional Bio
                    </h2>
                    <p className="whitespace-pre-line text-gray-700 leading-relaxed">{application.bio}</p>
                </div>

                {/* Teaching History Section */}
                <div className="border-b border-gray-200 px-6 py-4">
                    <h2 className="text-xl font-semibold text-[#002147] mb-4 flex items-center">
                        <i className="fas fa-book w-5 h-5 mr-2 text-[#002147]"></i>
                        Teaching Philosophy & History
                    </h2>
                    <p className="whitespace-pre-line text-gray-700 leading-relaxed">{application.teaching_history}</p>
                </div>

                {/* Documents Section */}
                <div className="px-6 py-4">
                    <h2 className="text-xl font-semibold text-[#002147] mb-4 flex items-center">
                        <i className="fas fa-file-alt w-5 h-5 mr-2 text-[#002147]"></i>
                        Supporting Documents
                    </h2>
                    <div className="grid grid-cols-1 md:grid-cols-2 gap-4">
                        {application.resume_path && (
                            <div className="border border-gray-200 rounded-lg p-4 hover:border-[#002147] transition">
                                <div className="flex items-start">
                                    <div className="p-2 bg-[#e6f7ff] rounded-lg mr-4">
                                        <i className="fas fa-file-pdf w-6 h-6 text-[#002147]"></i>
                                    </div>
                                    <div>
                                        <h3 className="font-medium text-gray-800">Professional Resume</h3>
                                        <p className="text-sm text-gray-500 mb-2">PDF document</p>
                                        <a
                                            href={`/storage/${application.resume_path}`}
                                            download
                                            className="text-sm text-[#002147] hover:text-[#001a44] font-medium inline-flex items-center"
                                        >
                                            Download
                                            <i className="fas fa-download w-4 h-4 ml-1"></i>
                                        </a>
                                    </div>
                                </div>
                            </div>
                        )}

                        {application.intro_video_url && (
                            <div className="border border-gray-200 rounded-lg p-4 hover:border-[#002147] transition">
                                <div className="flex items-start">
                                    <div className="p-2 bg-[#e6f7ff] rounded-lg mr-4">
                                        <i className="fas fa-video w-6 h-6 text-[#002147]"></i>
                                    </div>
                                    <div>
                                        <h3 className="font-medium text-gray-800">Introduction Video</h3>
                                        <p className="text-sm text-gray-500 mb-2">YouTube link</p>
                                        <a
                                            href={application.intro_video_url}
                                            target="_blank"
                                            rel="noopener noreferrer"
                                            className="text-sm text-[#002147] hover:text-[#001a44] font-medium inline-flex items-center"
                                        >
                                            Watch Video
                                            <i className="fas fa-external-link-alt w-4 h-4 ml-1"></i>
                                        </a>
                                    </div>
                                </div>
                            </div>
                        )}
                    </div>
                </div>

                {/* Action Buttons */}
                <div className="flex flex-col sm:flex-row justify-end gap-4 mt-8">
                    <button
                        onClick={() => setShowRejectModal(true)}
                        className="px-6 py-3 border border-[#ff0000] text-[#ff0000] rounded-lg hover:bg-[#ffe6e6] transition flex items-center justify-center"
                    >
                        <i className="fas fa-times w-5 h-5 mr-2"></i>
                        Reject Application
                    </button>
                    {!application.is_approved && (
                        <button
                            onClick={() => setShowApproveModal(true)}
                            className="px-6 py-3 bg-[#002147] text-white rounded-lg hover:bg-[#001a44] transition flex items-center justify-center"
                        >
                            <i className="fas fa-check w-5 h-5 mr-2"></i>
                            Approve Application
                        </button>
                    )}
                </div>
            </div>

            {/* Rejection Modal */}
            {showRejectModal && (
                <div className="fixed inset-0 bg-gray-600/40 bg-opacity-50 flex items-center justify-center z-50 px-4">
                    <div className="bg-white rounded-xl shadow-xl w-full max-w-md">
                        <div className="p-6">
                            <div className="flex justify-between items-center mb-4">
                                <h3 className="text-xl font-semibold text-gray-900">Reject Application</h3>
                                <button
                                    onClick={() => setShowRejectModal(false)}
                                    className="text-gray-400 cursor-pointer hover:text-gray-500"
                                >
                                    <i className="fas fa-times w-6 h-6"></i>
                                </button>
                            </div>

                            <form onSubmit={handleReject}>
                                <div className="mb-6">
                                    <label htmlFor="rejection_reason" className="block text-sm font-medium text-gray-700 mb-2">
                                        Please provide a reason for rejection
                                    </label>
                                    <textarea
                                        id="rejection_reason"
                                        name="rejection_reason"
                                        rows={4}
                                        value={rejectionReason}
                                        onChange={(e: ChangeEvent<HTMLTextAreaElement>) => setRejectionReason(e.target.value)}
                                        className="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-[#002147] focus:border-[#002147]"
                                        required
                                    />
                                    {errors?.rejection_reason && (
                                        <p className="text-sm text-red-500 mt-1">{errors.rejection_reason}</p>
                                    )}
                                </div>

                                <div className="flex justify-end gap-3">
                                    <button
                                        type="button"
                                        onClick={() => setShowRejectModal(false)}
                                        className="px-4 py-2 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50"
                                    >
                                        Cancel
                                    </button>
                                    <button
                                        type="submit"
                                        disabled={isSubmitting}
                                        className="px-4 py-2 bg-[#ff0000] text-white rounded-lg hover:bg-[#cc0000] flex items-center disabled:opacity-50 disabled:cursor-not-allowed"
                                    >
                                        <i className="fas fa-times w-5 h-5 mr-2"></i>
                                        {isSubmitting ? 'Rejecting...' : 'Confirm Rejection'}
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            )}

            {/* Approval Modal */}
            {showApproveModal && (
                <div className="fixed inset-0 bg-gray-600/40 bg-opacity-50 flex items-center justify-center z-50 px-4">
                    <div className="bg-white rounded-xl shadow-xl w-full max-w-md">
                        <div className="p-6">
                            <div className="flex justify-between items-center mb-4">
                                <h3 className="text-xl font-semibold text-gray-900">Approve Application</h3>
                                <button
                                    onClick={() => setShowApproveModal(false)}
                                    className="text-gray-400 cursor-pointer hover:text-gray-500"
                                >
                                    <i className="fas fa-times w-6 h-6"></i>
                                </button>
                            </div>

                            <div className="mb-6">
                                <div className="mx-auto mb-4 text-gray-400 w-12 h-12 flex items-center justify-center">
                                    <i className="fas fa-exclamation-circle w-12 h-12"></i>
                                </div>
                                <p className="text-center text-lg text-gray-500">
                                    Are you sure you want to approve the application for {application.application_id}?
                                </p>
                            </div>

                            <div className="flex justify-center gap-3">
                                <button
                                    onClick={handleApprove}
                                    disabled={isSubmitting}
                                    className="text-white bg-[#002147] hover:bg-[#001a44] focus:ring-4 focus:outline-none focus:ring-[#002147] font-medium rounded-lg text-sm px-5 py-2.5 disabled:opacity-50 disabled:cursor-not-allowed"
                                >
                                    {isSubmitting ? 'Approving...' : "Yes, I'm sure"}
                                </button>
                                <button
                                    onClick={() => setShowApproveModal(false)}
                                    className="py-2.5 px-5 text-sm font-medium text-gray-900 bg-white rounded-lg border border-gray-200 hover:bg-gray-100"
                                >
                                    No, cancel
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            )}
        </DashboardLayout>
    );
}
