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
    milestones: Milestone[];
}

export default function Index({ mentorshipRequest, milestones }: Props) {
    const { sideLinks } = usePage().props as any;
    const [showCreateModal, setShowCreateModal] = useState(false);

    const createForm = useForm({
        title: '',
        description: '',
        due_date: '',
        order: milestones.length,
    });

    const handleCreate: FormEventHandler = (e) => {
        e.preventDefault();
        createForm.post(route('mentorship.milestones.store', mentorshipRequest.id), {
            onSuccess: () => {
                setShowCreateModal(false);
                createForm.reset();
            },
        });
    };

    const toggleComplete = (milestoneId: number) => {
        router.post(route('mentorship.milestones.toggle', [mentorshipRequest.id, milestoneId]));
    };

    return (
        <DashboardLayout sideLinks={sideLinks}>
            <Head title="Mentorship Milestones" />

            <div className="py-12">
                <div className="max-w-7xl mx-auto sm:px-6 lg:px-8">
                    <div className="mb-8">
                        <div className="flex justify-between items-center">
                            <div>
                                <h1 className="text-3xl font-bold text-gray-900">Mentorship Milestones</h1>
                                <p className="mt-2 text-sm text-gray-600">
                                    Track learning goals and progress
                                </p>
                            </div>
                            <button
                                onClick={() => setShowCreateModal(true)}
                                className="px-4 py-2 bg-primary text-white rounded-md hover:bg-primary/90"
                            >
                                Add Milestone
                            </button>
                        </div>
                    </div>

                    <div className="bg-white shadow rounded-lg overflow-hidden">
                        {milestones.length === 0 ? (
                            <div className="text-center py-12">
                                <svg className="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path strokeLinecap="round" strokeLinejoin="round" strokeWidth="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                                <h3 className="mt-2 text-sm font-medium text-gray-900">No milestones yet</h3>
                                <p className="mt-1 text-sm text-gray-500">Create your first learning milestone.</p>
                                <div className="mt-6">
                                    <button
                                        onClick={() => setShowCreateModal(true)}
                                        className="inline-flex items-center px-4 py-2 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-primary hover:bg-primary/90"
                                    >
                                        Add First Milestone
                                    </button>
                                </div>
                            </div>
                        ) : (
                            <div className="p-6">
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
                                                        onClick={() => toggleComplete(milestone.id)}
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
                            </div>
                        )}
                    </div>
                </div>
            </div>

            {/* Create Modal */}
            {showCreateModal && (
                <div className="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50">
                    <div className="bg-white rounded-lg p-6 max-w-md w-full mx-4">
                        <h3 className="text-lg font-semibold mb-4">Create Milestone</h3>
                        <form onSubmit={handleCreate}>
                            <div className="mb-4">
                                <label className="block text-sm font-medium text-gray-700 mb-2">
                                    Title <span className="text-red-500">*</span>
                                </label>
                                <input
                                    type="text"
                                    value={createForm.data.title}
                                    onChange={(e) => createForm.setData('title', e.target.value)}
                                    className="w-full rounded-md border-gray-300 shadow-sm focus:border-primary focus:ring-primary"
                                    placeholder="E.g., Complete React fundamentals"
                                    required
                                />
                                {createForm.errors.title && (
                                    <p className="mt-1 text-sm text-red-600">{createForm.errors.title}</p>
                                )}
                            </div>
                            <div className="mb-4">
                                <label className="block text-sm font-medium text-gray-700 mb-2">
                                    Description
                                </label>
                                <textarea
                                    value={createForm.data.description}
                                    onChange={(e) => createForm.setData('description', e.target.value)}
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
                                    value={createForm.data.due_date}
                                    onChange={(e) => createForm.setData('due_date', e.target.value)}
                                    className="w-full rounded-md border-gray-300 shadow-sm focus:border-primary focus:ring-primary"
                                />
                            </div>
                            <div className="flex justify-end space-x-3">
                                <button
                                    type="button"
                                    onClick={() => setShowCreateModal(false)}
                                    className="px-4 py-2 border border-gray-300 rounded-md text-gray-700 hover:bg-gray-50"
                                >
                                    Cancel
                                </button>
                                <button
                                    type="submit"
                                    disabled={createForm.processing}
                                    className="px-4 py-2 bg-primary text-white rounded-md hover:bg-primary/90 disabled:opacity-50"
                                >
                                    {createForm.processing ? 'Creating...' : 'Create'}
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            )}
        </DashboardLayout>
    );
}
