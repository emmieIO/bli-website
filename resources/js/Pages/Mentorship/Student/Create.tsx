import { Head, Link, useForm } from '@inertiajs/react';
import DashboardLayout from '@/Layouts/DashboardLayout';
import { usePage } from '@inertiajs/react';
import { FormEventHandler } from 'react';

interface Instructor {
    id: number;
    name: string;
    email: string;
}

interface Props {
    instructors: Instructor[];
}

export default function Create({ instructors }: Props) {
    const { sideLinks } = usePage().props as any;
    const { data, setData, post, processing, errors } = useForm({
        instructor_id: '',
        message: '',
        goals: '',
        duration_type: 'monthly',
        duration_value: 1,
    });

    const submit: FormEventHandler = (e) => {
        e.preventDefault();
        post(route('student.mentorship.store'));
    };

    return (
        <DashboardLayout sideLinks={sideLinks}>
            <Head title="Request Mentorship" />

            <div className="py-8">
                <div className="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8">
                    {/* Header Section */}
                    <div className="mb-8">
                        <Link
                            href={route('student.mentorship.index')}
                            className="inline-flex items-center text-sm font-medium text-primary hover:text-primary/80 transition-colors mb-4"
                        >
                            <svg className="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path strokeLinecap="round" strokeLinejoin="round" strokeWidth="2" d="M15 19l-7-7 7-7" />
                            </svg>
                            Back to My Requests
                        </Link>

                        <div className="flex items-center space-x-3 mb-3">
                            <div className="flex-shrink-0">
                                <div className="w-12 h-12 bg-gradient-to-br from-primary to-accent rounded-lg flex items-center justify-center">
                                    <svg className="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path strokeLinecap="round" strokeLinejoin="round" strokeWidth="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
                                    </svg>
                                </div>
                            </div>
                            <div>
                                <h1 className="text-3xl font-bold text-gray-900">Request Mentorship</h1>
                                <p className="mt-1 text-sm text-gray-600">
                                    Connect with an expert instructor for personalized guidance
                                </p>
                            </div>
                        </div>

                        {/* Info Banner */}
                        <div className="bg-blue-50 border-l-4 border-blue-400 p-4 rounded-r-lg">
                            <div className="flex">
                                <div className="flex-shrink-0">
                                    <svg className="h-5 w-5 text-blue-400" fill="currentColor" viewBox="0 0 20 20">
                                        <path fillRule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clipRule="evenodd" />
                                    </svg>
                                </div>
                                <div className="ml-3">
                                    <p className="text-sm text-blue-700">
                                        <span className="font-semibold">Approval Process:</span> Your request will first be reviewed by the instructor. If approved, it will be sent to administration for final approval before your mentorship begins.
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>

                    {/* Form Card */}
                    <div className="bg-white shadow-xl rounded-xl border border-gray-100 overflow-hidden">
                        <div className="px-8 py-6 bg-gradient-to-r from-gray-50 to-white border-b border-gray-200">
                            <h2 className="text-xl font-semibold text-gray-900">Mentorship Details</h2>
                            <p className="mt-1 text-sm text-gray-600">Fill in the information below to submit your request</p>
                        </div>

                        <form onSubmit={submit} className="px-8 py-6 space-y-8">
                            {/* Instructor Selection */}
                            <div className="space-y-2">
                                <label htmlFor="instructor_id" className="block text-sm font-semibold text-gray-900">
                                    Select Instructor <span className="text-red-500">*</span>
                                </label>
                                <select
                                    id="instructor_id"
                                    value={data.instructor_id}
                                    onChange={(e) => setData('instructor_id', e.target.value)}
                                    className="mt-1 block w-full rounded-lg border-gray-300 shadow-sm focus:border-primary focus:ring-2 focus:ring-primary focus:ring-opacity-50 transition-all text-base py-2.5"
                                    required
                                >
                                    <option value="">Choose an instructor...</option>
                                    {instructors.map((instructor) => (
                                        <option key={instructor.id} value={instructor.id}>
                                            {instructor.name} ({instructor.email})
                                        </option>
                                    ))}
                                </select>
                                {errors.instructor_id && <p className="mt-2 text-sm text-red-600 flex items-center">
                                    <svg className="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                        <path fillRule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clipRule="evenodd" />
                                    </svg>
                                    {errors.instructor_id}
                                </p>}
                            </div>

                            {/* Message */}
                            <div className="space-y-2">
                                <label htmlFor="message" className="block text-sm font-semibold text-gray-900">
                                    Message to Instructor <span className="text-red-500">*</span>
                                </label>
                                <textarea
                                    id="message"
                                    value={data.message}
                                    onChange={(e) => setData('message', e.target.value)}
                                    rows={5}
                                    className="mt-1 block w-full rounded-lg border-gray-300 shadow-sm focus:border-primary focus:ring-2 focus:ring-primary focus:ring-opacity-50 transition-all text-base"
                                    placeholder="Introduce yourself and explain why you're seeking mentorship from this instructor. Share your background, interests, and what you hope to learn..."
                                    required
                                />
                                <p className="mt-2 text-sm text-gray-500 flex items-center">
                                    <svg className="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path strokeLinecap="round" strokeLinejoin="round" strokeWidth="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                    </svg>
                                    Minimum 10 characters - Be specific and genuine
                                </p>
                                {errors.message && <p className="mt-2 text-sm text-red-600 flex items-center">
                                    <svg className="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                        <path fillRule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clipRule="evenodd" />
                                    </svg>
                                    {errors.message}
                                </p>}
                            </div>

                            {/* Goals */}
                            <div className="space-y-2">
                                <label htmlFor="goals" className="block text-sm font-semibold text-gray-900">
                                    Learning Goals <span className="text-gray-400 font-normal">(Optional)</span>
                                </label>
                                <textarea
                                    id="goals"
                                    value={data.goals}
                                    onChange={(e) => setData('goals', e.target.value)}
                                    rows={4}
                                    className="mt-1 block w-full rounded-lg border-gray-300 shadow-sm focus:border-primary focus:ring-2 focus:ring-primary focus:ring-opacity-50 transition-all text-base"
                                    placeholder="What specific skills, knowledge, or outcomes do you hope to achieve through this mentorship?"
                                />
                                <p className="mt-2 text-sm text-gray-500 flex items-center">
                                    <svg className="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path strokeLinecap="round" strokeLinejoin="round" strokeWidth="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                    </svg>
                                    Helps the instructor understand your expectations
                                </p>
                                {errors.goals && <p className="mt-2 text-sm text-red-600 flex items-center">
                                    <svg className="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                        <path fillRule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clipRule="evenodd" />
                                    </svg>
                                    {errors.goals}
                                </p>}
                            </div>

                            {/* Duration Section */}
                            <div>
                                <h3 className="text-sm font-semibold text-gray-900 mb-4">Mentorship Duration</h3>
                                <div className="grid grid-cols-1 md:grid-cols-2 gap-6">
                                    <div className="space-y-2">
                                        <label htmlFor="duration_type" className="block text-sm font-medium text-gray-700">
                                            Duration Type <span className="text-red-500">*</span>
                                        </label>
                                        <select
                                            id="duration_type"
                                            value={data.duration_type}
                                            onChange={(e) => setData('duration_type', e.target.value)}
                                            className="mt-1 block w-full rounded-lg border-gray-300 shadow-sm focus:border-primary focus:ring-2 focus:ring-primary focus:ring-opacity-50 transition-all text-base py-2.5"
                                        >
                                            <option value="monthly">Monthly Sessions</option>
                                            <option value="weekly">Weekly Sessions</option>
                                            <option value="one-time">One-time Consultation</option>
                                        </select>
                                        {errors.duration_type && <p className="mt-2 text-sm text-red-600">{errors.duration_type}</p>}
                                    </div>

                                    <div className="space-y-2">
                                        <label htmlFor="duration_value" className="block text-sm font-medium text-gray-700">
                                            Number of Sessions <span className="text-red-500">*</span>
                                        </label>
                                        <input
                                            type="number"
                                            id="duration_value"
                                            value={data.duration_value}
                                            onChange={(e) => setData('duration_value', parseInt(e.target.value))}
                                            min="1"
                                            max="12"
                                            className="mt-1 block w-full rounded-lg border-gray-300 shadow-sm focus:border-primary focus:ring-2 focus:ring-primary focus:ring-opacity-50 transition-all text-base py-2.5"
                                            required
                                        />
                                        <p className="mt-2 text-sm text-gray-500">Between 1 and 12 sessions</p>
                                        {errors.duration_value && <p className="mt-2 text-sm text-red-600">{errors.duration_value}</p>}
                                    </div>
                                </div>
                            </div>

                            {/* Form Actions */}
                            <div className="flex items-center justify-between pt-6 border-t border-gray-200">
                                <Link
                                    href={route('student.mentorship.index')}
                                    className="inline-flex items-center px-6 py-3 border border-gray-300 rounded-lg text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary transition-all"
                                >
                                    <svg className="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path strokeLinecap="round" strokeLinejoin="round" strokeWidth="2" d="M6 18L18 6M6 6l12 12" />
                                    </svg>
                                    Cancel
                                </Link>
                                <button
                                    type="submit"
                                    disabled={processing}
                                    className="inline-flex items-center px-8 py-3 border border-transparent rounded-lg shadow-sm text-sm font-medium text-white bg-gradient-to-r from-primary to-accent hover:from-primary/90 hover:to-accent/90 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary disabled:opacity-50 disabled:cursor-not-allowed transition-all transform hover:scale-105"
                                >
                                    {processing ? (
                                        <>
                                            <svg className="animate-spin -ml-1 mr-2 h-4 w-4 text-white" fill="none" viewBox="0 0 24 24">
                                                <circle className="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" strokeWidth="4"></circle>
                                                <path className="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                            </svg>
                                            Submitting...
                                        </>
                                    ) : (
                                        <>
                                            <svg className="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path strokeLinecap="round" strokeLinejoin="round" strokeWidth="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8" />
                                            </svg>
                                            Submit Request
                                        </>
                                    )}
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </DashboardLayout>
    );
}
