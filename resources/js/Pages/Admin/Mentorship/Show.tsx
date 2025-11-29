import { Head, Link, useForm } from '@inertiajs/react';
import DashboardLayout from '@/Layouts/DashboardLayout';
import { usePage } from '@inertiajs/react';
import { FormEventHandler, useState } from 'react';

interface MentorshipRequest {
    id: number;
    message: string;
    goals: string | null;
    duration_type: string;
    duration_value: number;
    status: string;
    instructor_response: string | null;
    admin_response: string | null;
    rejection_reason: string | null;
    created_at: string;
    student: {
        name: string;
        email: string;
    };
    instructor: {
        name: string;
        email: string;
    };
    status_label: string;
    status_color: string;
    formatted_duration: string;
}

interface Props {
    mentorshipRequest: MentorshipRequest;
}

export default function Show({ mentorshipRequest }: Props) {
    const { sideLinks } = usePage().props as any;
    const [showApproveModal, setShowApproveModal] = useState(false);
    const [showRejectModal, setShowRejectModal] = useState(false);

    const approveForm = useForm({
        response: '',
    });

    const rejectForm = useForm({
        reason: '',
    });

    const handleApprove: FormEventHandler = (e) => {
        e.preventDefault();
        approveForm.post(route('admin.mentorship.approve', mentorshipRequest.id), {
            onSuccess: () => {
                setShowApproveModal(false);
            },
        });
    };

    const handleReject: FormEventHandler = (e) => {
        e.preventDefault();
        rejectForm.post(route('admin.mentorship.reject', mentorshipRequest.id), {
            onSuccess: () => {
                setShowRejectModal(false);
            },
        });
    };

    const getStatusBadgeClass = (color: string) => {
        const colors: { [key: string]: string } = {
            yellow: 'bg-yellow-100 text-yellow-800',
            blue: 'bg-blue-100 text-blue-800',
            green: 'bg-green-100 text-green-800',
            red: 'bg-red-100 text-red-800',
            gray: 'bg-gray-100 text-gray-800',
        };
        return colors[color] || colors.gray;
    };

    return (
        <DashboardLayout sideLinks={sideLinks}>
            <Head title="Mentorship Request Details" />

            <div className="py-12">
                <div className="max-w-4xl mx-auto sm:px-6 lg:px-8">
                    <div className="mb-8">
                        <Link href={route('admin.mentorship.index')} className="text-primary hover:text-primary/90 flex items-center">
                            <svg className="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path strokeLinecap="round" strokeLinejoin="round" strokeWidth="2" d="M15 19l-7-7 7-7" />
                            </svg>
                            Back to Mentorship Manager
                        </Link>
                        <div className="flex justify-between items-start mt-4">
                            <div>
                                <h1 className="text-3xl font-bold text-gray-900">Mentorship Request</h1>
                                <p className="mt-2 text-sm text-gray-600">Request ID: #{mentorshipRequest.id}</p>
                            </div>
                            <span className={`px-3 py-1 inline-flex text-sm leading-5 font-semibold rounded-full ${getStatusBadgeClass(mentorshipRequest.status_color)}`}>
                                {mentorshipRequest.status_label}
                            </span>
                        </div>
                    </div>

                    <div className="space-y-6">
                        {/* Student & Instructor Info */}
                        <div className="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div className="bg-white shadow rounded-lg p-6">
                                <h2 className="text-lg font-semibold mb-4">Student</h2>
                                <div>
                                    <p className="text-gray-900 font-medium">{mentorshipRequest.student.name}</p>
                                    <p className="text-sm text-gray-600">{mentorshipRequest.student.email}</p>
                                </div>
                            </div>
                            <div className="bg-white shadow rounded-lg p-6">
                                <h2 className="text-lg font-semibold mb-4">Instructor</h2>
                                <div>
                                    <p className="text-gray-900 font-medium">{mentorshipRequest.instructor.name}</p>
                                    <p className="text-sm text-gray-600">{mentorshipRequest.instructor.email}</p>
                                </div>
                            </div>
                        </div>

                        {/* Request Details */}
                        <div className="bg-white shadow rounded-lg p-6">
                            <h2 className="text-lg font-semibold mb-4">Request Details</h2>
                            <div className="space-y-4">
                                <div>
                                    <label className="block text-sm font-medium text-gray-700">Duration</label>
                                    <p className="mt-1 text-gray-900">{mentorshipRequest.formatted_duration}</p>
                                </div>
                                <div>
                                    <label className="block text-sm font-medium text-gray-700">Student's Message</label>
                                    <p className="mt-1 text-gray-900 whitespace-pre-wrap">{mentorshipRequest.message}</p>
                                </div>
                                {mentorshipRequest.goals && (
                                    <div>
                                        <label className="block text-sm font-medium text-gray-700">Learning Goals</label>
                                        <p className="mt-1 text-gray-900 whitespace-pre-wrap">{mentorshipRequest.goals}</p>
                                    </div>
                                )}
                            </div>
                        </div>

                        {/* Instructor Response */}
                        {mentorshipRequest.instructor_response && (
                            <div className="bg-blue-50 border border-blue-200 rounded-lg p-6">
                                <h2 className="text-lg font-semibold text-blue-900 mb-2">Instructor's Response</h2>
                                <p className="text-blue-800 whitespace-pre-wrap">{mentorshipRequest.instructor_response}</p>
                            </div>
                        )}

                        {/* Admin Response */}
                        {mentorshipRequest.admin_response && (
                            <div className="bg-green-50 border border-green-200 rounded-lg p-6">
                                <h2 className="text-lg font-semibold text-green-900 mb-2">Admin's Response</h2>
                                <p className="text-green-800 whitespace-pre-wrap">{mentorshipRequest.admin_response}</p>
                            </div>
                        )}

                        {/* Rejection Reason */}
                        {mentorshipRequest.rejection_reason && (
                            <div className="bg-red-50 border border-red-200 rounded-lg p-6">
                                <h2 className="text-lg font-semibold text-red-900 mb-2">Rejection Reason</h2>
                                <p className="text-red-800 whitespace-pre-wrap">{mentorshipRequest.rejection_reason}</p>
                            </div>
                        )}

                        {/* Actions */}
                        {mentorshipRequest.status === 'instructor_approved' && (
                            <div className="bg-white shadow rounded-lg p-6">
                                <h2 className="text-lg font-semibold mb-4">Admin Actions</h2>
                                <div className="flex space-x-3">
                                    <button
                                        onClick={() => setShowApproveModal(true)}
                                        className="px-4 py-2 bg-green-600 text-white rounded-md hover:bg-green-700"
                                    >
                                        Approve Mentorship
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
                                    placeholder="Add a message for the student and instructor..."
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
                                    placeholder="Explain why this request is being rejected..."
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
        </DashboardLayout>
    );
}
