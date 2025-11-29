import { Head, Link, useForm } from '@inertiajs/react';
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

interface Props {
    mentorshipRequest: MentorshipRequest;
    resources: Resource[];
}

export default function Index({ mentorshipRequest, resources }: Props) {
    const { sideLinks } = usePage().props as any;
    const [showUploadModal, setShowUploadModal] = useState(false);

    const uploadForm = useForm({
        file: null as File | null,
        description: '',
        category: 'general',
    });

    const handleUpload: FormEventHandler = (e) => {
        e.preventDefault();
        uploadForm.post(route('mentorship.resources.store', mentorshipRequest.id), {
            onSuccess: () => {
                setShowUploadModal(false);
                uploadForm.reset();
            },
        });
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

    return (
        <DashboardLayout sideLinks={sideLinks}>
            <Head title="Mentorship Resources" />

            <div className="py-12">
                <div className="max-w-7xl mx-auto sm:px-6 lg:px-8">
                    <div className="mb-8">
                        <div className="flex justify-between items-center">
                            <div>
                                <h1 className="text-3xl font-bold text-gray-900">Mentorship Resources</h1>
                                <p className="mt-2 text-sm text-gray-600">
                                    Share learning materials and documents
                                </p>
                            </div>
                            <button
                                onClick={() => setShowUploadModal(true)}
                                className="px-4 py-2 bg-primary text-white rounded-md hover:bg-primary/90"
                            >
                                Upload Resource
                            </button>
                        </div>
                    </div>

                    <div className="bg-white shadow rounded-lg overflow-hidden">
                        {resources.length === 0 ? (
                            <div className="text-center py-12">
                                <svg className="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path strokeLinecap="round" strokeLinejoin="round" strokeWidth="2" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z" />
                                </svg>
                                <h3 className="mt-2 text-sm font-medium text-gray-900">No resources yet</h3>
                                <p className="mt-1 text-sm text-gray-500">Upload your first learning resource.</p>
                                <div className="mt-6">
                                    <button
                                        onClick={() => setShowUploadModal(true)}
                                        className="inline-flex items-center px-4 py-2 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-primary hover:bg-primary/90"
                                    >
                                        Upload First Resource
                                    </button>
                                </div>
                            </div>
                        ) : (
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
                                                    className="text-primary hover:text-primary/90 mr-4"
                                                >
                                                    Download
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

            {/* Upload Modal */}
            {showUploadModal && (
                <div className="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50">
                    <div className="bg-white rounded-lg p-6 max-w-md w-full mx-4">
                        <h3 className="text-lg font-semibold mb-4">Upload Resource</h3>
                        <form onSubmit={handleUpload}>
                            <div className="mb-4">
                                <label className="block text-sm font-medium text-gray-700 mb-2">
                                    File <span className="text-red-500">*</span>
                                </label>
                                <input
                                    type="file"
                                    onChange={(e) => uploadForm.setData('file', e.target.files?.[0] || null)}
                                    className="w-full"
                                    required
                                />
                                {uploadForm.errors.file && (
                                    <p className="mt-1 text-sm text-red-600">{uploadForm.errors.file}</p>
                                )}
                            </div>
                            <div className="mb-4">
                                <label className="block text-sm font-medium text-gray-700 mb-2">
                                    Category
                                </label>
                                <select
                                    value={uploadForm.data.category}
                                    onChange={(e) => uploadForm.setData('category', e.target.value)}
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
                                    value={uploadForm.data.description}
                                    onChange={(e) => uploadForm.setData('description', e.target.value)}
                                    rows={3}
                                    className="w-full rounded-md border-gray-300 shadow-sm focus:border-primary focus:ring-primary"
                                    placeholder="Brief description of this resource..."
                                />
                            </div>
                            <div className="flex justify-end space-x-3">
                                <button
                                    type="button"
                                    onClick={() => setShowUploadModal(false)}
                                    className="px-4 py-2 border border-gray-300 rounded-md text-gray-700 hover:bg-gray-50"
                                >
                                    Cancel
                                </button>
                                <button
                                    type="submit"
                                    disabled={uploadForm.processing}
                                    className="px-4 py-2 bg-primary text-white rounded-md hover:bg-primary/90 disabled:opacity-50"
                                >
                                    {uploadForm.processing ? 'Uploading...' : 'Upload'}
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            )}
        </DashboardLayout>
    );
}
