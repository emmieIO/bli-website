import { Head, router } from '@inertiajs/react';
import GuestLayout from '@/Layouts/GuestLayout';
import { FormEvent, useState } from 'react';

interface User {
    id: number;
    name: string;
    email: string;
}

interface Course {
    id: number;
    title: string;
    instructor?: {
        name: string;
    };
}

interface Certificate {
    id: number;
    certificate_number: string;
    completion_date: string;
    certificate_url?: string;
    user: User;
    course: Course;
}

interface VerifyProps {
    certificate: Certificate | null;
    error?: string | null;
}

export default function Verify({ certificate, error }: VerifyProps) {
    const [certificateNumber, setCertificateNumber] = useState('');

    const handleSubmit = (e: FormEvent) => {
        e.preventDefault();
        if (certificateNumber.trim()) {
            router.get(route('certificates.verify', certificateNumber.trim()));
        }
    };

    const formatDate = (dateString: string) => {
        const date = new Date(dateString);
        return date.toLocaleDateString('en-US', { year: 'numeric', month: 'long', day: 'numeric' });
    };

    const getCurrentDateTime = () => {
        const now = new Date();
        return now.toLocaleDateString('en-US', {
            year: 'numeric',
            month: 'long',
            day: 'numeric',
            hour: 'numeric',
            minute: '2-digit',
            hour12: true,
        });
    };

    return (
        <GuestLayout>
            <Head title="Certificate Verification" />

            <div className="min-h-screen bg-gray-50 py-12">
                <div className="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
                    {/* Header */}
                    <div className="text-center mb-8">
                        <h1 className="text-3xl font-bold text-gray-900 mb-4 font-montserrat">Certificate Verification</h1>
                        <p className="text-gray-600 font-lato">Verify the authenticity of BLI Academy certificates</p>
                    </div>

                    {/* Error Message */}
                    {error && (
                        <div className="bg-red-50 border border-red-200 rounded-md p-4 mb-6">
                            <div className="flex">
                                <div className="shrink-0">
                                    <i className="fas fa-exclamation-circle text-red-400"></i>
                                </div>
                                <div className="ml-3">
                                    <h3 className="text-sm font-medium text-red-800 font-montserrat">Certificate Not Found</h3>
                                    <div className="mt-2 text-sm text-red-700 font-lato">{error}</div>
                                </div>
                            </div>
                        </div>
                    )}

                    {/* Certificate Details */}
                    {certificate && (
                        <div className="bg-white shadow rounded-lg overflow-hidden mb-8">
                            {/* Success Header */}
                            <div className="px-6 py-4 bg-green-50 border-b border-green-200">
                                <div className="flex items-center">
                                    <svg
                                        className="w-5 h-5 text-green-600 mr-2"
                                        fill="currentColor"
                                        viewBox="0 0 20 20"
                                    >
                                        <path
                                            fillRule="evenodd"
                                            d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z"
                                            clipRule="evenodd"
                                        />
                                    </svg>
                                    <h3 className="text-lg font-medium text-green-800 font-montserrat">Certificate Verified</h3>
                                </div>
                            </div>

                            {/* Certificate Information Grid */}
                            <div className="p-6">
                                <div className="grid grid-cols-1 md:grid-cols-2 gap-6">
                                    {/* Student Information */}
                                    <div>
                                        <h4 className="font-medium text-gray-900 mb-2 font-montserrat">Student Information</h4>
                                        <div className="space-y-2 text-sm font-lato">
                                            <div>
                                                <strong>Name:</strong> {certificate.user.name}
                                            </div>
                                            <div>
                                                <strong>Email:</strong> {certificate.user.email}
                                            </div>
                                        </div>
                                    </div>

                                    {/* Course Information */}
                                    <div>
                                        <h4 className="font-medium text-gray-900 mb-2 font-montserrat">Course Information</h4>
                                        <div className="space-y-2 text-sm font-lato">
                                            <div>
                                                <strong>Course:</strong> {certificate.course.title}
                                            </div>
                                            <div>
                                                <strong>Instructor:</strong>{' '}
                                                {certificate.course.instructor?.name || 'Beacon Leadership Institute'}
                                            </div>
                                        </div>
                                    </div>

                                    {/* Certificate Details */}
                                    <div>
                                        <h4 className="font-medium text-gray-900 mb-2 font-montserrat">Certificate Details</h4>
                                        <div className="space-y-2 text-sm font-lato">
                                            <div>
                                                <strong>Certificate Number:</strong> {certificate.certificate_number}
                                            </div>
                                            <div>
                                                <strong>Issued Date:</strong> {formatDate(certificate.completion_date)}
                                            </div>
                                        </div>
                                    </div>

                                    {/* Verification */}
                                    <div>
                                        <h4 className="font-medium text-gray-900 mb-2 font-montserrat">Verification</h4>
                                        <div className="space-y-2 text-sm font-lato">
                                            <div>
                                                <strong>Status:</strong>{' '}
                                                <span className="text-green-600 font-medium">Valid</span>
                                            </div>
                                            <div>
                                                <strong>Verified:</strong> {getCurrentDateTime()}
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                {/* View Certificate Button */}
                                {certificate.certificate_url && (
                                    <div className="mt-6 pt-6 border-t border-gray-200">
                                        <a
                                            href={certificate.certificate_url}
                                            target="_blank"
                                            rel="noopener noreferrer"
                                            className="inline-flex items-center px-4 py-2 bg-primary text-white rounded-lg hover:bg-primary-700 transition-colors font-montserrat"
                                        >
                                            <svg
                                                className="w-4 h-4 mr-2"
                                                fill="none"
                                                stroke="currentColor"
                                                viewBox="0 0 24 24"
                                            >
                                                <path
                                                    strokeLinecap="round"
                                                    strokeLinejoin="round"
                                                    strokeWidth="2"
                                                    d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"
                                                />
                                            </svg>
                                            View Certificate
                                        </a>
                                    </div>
                                )}
                            </div>
                        </div>
                    )}

                    {/* Verification Form */}
                    <div className="bg-white shadow rounded-lg p-6">
                        <h2 className="text-xl font-semibold text-gray-900 mb-4 font-montserrat">
                            {certificate ? 'Verify Another Certificate' : 'Enter Certificate Number'}
                        </h2>
                        <form onSubmit={handleSubmit} className="flex flex-col sm:flex-row gap-4">
                            <div className="flex-1">
                                <label
                                    htmlFor="certificate_number"
                                    className="block text-sm font-medium text-gray-700 mb-2 font-montserrat"
                                >
                                    Certificate Number
                                </label>
                                <input
                                    type="text"
                                    id="certificate_number"
                                    value={certificateNumber}
                                    onChange={(e) => setCertificateNumber(e.target.value)}
                                    className="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-primary focus:border-transparent font-lato"
                                    placeholder="Enter certificate number (e.g., CERT-ABC123DEF4)"
                                    required
                                />
                            </div>
                            <div className="shrink-0">
                                <button
                                    type="submit"
                                    className="sm:mt-7 w-full sm:w-auto px-6 py-2 bg-primary text-white rounded-lg hover:bg-primary-700 transition-colors font-montserrat"
                                >
                                    Verify
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </GuestLayout>
    );
}
