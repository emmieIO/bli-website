import { useState, FormEvent } from 'react';
import { Head, Link, router, usePage } from '@inertiajs/react';
import DashboardLayout from '@/Layouts/DashboardLayout';

interface User {
    id: number;
    name: string;
    email: string;
    phone: string;
    headline: string;
    linkedin: string;
    website: string;
    photo: string;
}

interface Speaker {
    id: number;
    user: User;
    bio: string;
    organization: string;
    status: string;
    is_featured: boolean;
    created_at: string;
    updated_at: string;
}

interface ViewSpeakerProps {
    speaker: Speaker;
}

export default function ViewSpeaker({ speaker }: ViewSpeakerProps) {
    const { sideLinks } = usePage().props as any;
    const [showDeleteModal, setShowDeleteModal] = useState(false);
    const [isDeleting, setIsDeleting] = useState(false);

    const handleDelete = (e: FormEvent) => {
        e.preventDefault();
        setIsDeleting(true);
        router.delete(route('admin.speakers.destroy', speaker.id), {
            preserveScroll: true,
            onFinish: () => {
                setIsDeleting(false);
                setShowDeleteModal(false);
            },
        });
    };

    const createdDate = new Date(speaker.created_at).toLocaleDateString('en-US', {
        year: 'numeric',
        month: 'short',
        day: 'numeric',
    });

    const updatedDate = new Date(speaker.updated_at).toLocaleDateString('en-US', {
        year: 'numeric',
        month: 'short',
        day: 'numeric',
    });

    const photoUrl = speaker.user.photo
        ? `/storage/${speaker.user.photo}`
        : `https://ui-avatars.com/api/?name=${encodeURIComponent(speaker.user.name)}&background=00275E&color=fff`;

    return (
        <DashboardLayout sideLinks={sideLinks}>
            <Head title={`Speaker - ${speaker.user.name}`} />

            <div className="py-8 px-4 sm:px-6 lg:px-8 max-w-7xl mx-auto">
                {/* Header with Back Button and Actions */}
                <div className="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 mb-8">
                    <div className="flex items-center gap-4">
                        <Link
                            href={route('admin.speakers.index')}
                            className="inline-flex items-center justify-center w-10 h-10 bg-white border border-gray-300 rounded-full text-gray-700 hover:bg-gray-50 hover:text-[#002147] focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-[#002147] transition shadow-sm"
                            title="Back to Speakers"
                        >
                            <i className="fas fa-arrow-left w-5 h-5"></i>
                        </Link>
                        <div>
                            <h1 className="text-2xl md:text-3xl font-extrabold text-[#002147]">{speaker.user.name}</h1>
                            <p className="text-sm text-gray-500">Speaker Profile Details</p>
                        </div>
                    </div>

                    <div className="flex items-center gap-3">
                        <Link
                            href={route('admin.speakers.edit', speaker.id)}
                            className="inline-flex items-center px-4 py-2.5 bg-white border border-gray-300 rounded-lg shadow-sm text-sm font-medium text-[#002147] hover:bg-gray-50 hover:text-[#FF0000] focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-[#002147] transition"
                        >
                            <i className="fas fa-edit w-4 h-4 mr-2"></i>
                            Edit Profile
                        </Link>

                        <button
                            onClick={() => setShowDeleteModal(true)}
                            className="inline-flex items-center px-4 py-2.5 bg-red-600 text-white text-sm font-medium rounded-lg hover:bg-red-700 focus:ring-4 focus:ring-red-300 focus:outline-none transition shadow-sm"
                        >
                            <i className="fas fa-trash w-4 h-4 mr-2"></i>
                            Delete Speaker
                        </button>
                    </div>
                </div>

                <div className="grid grid-cols-1 lg:grid-cols-3 gap-6">
                    {/* Left Column: Speaker Info */}
                    <div className="lg:col-span-2 space-y-6">
                        {/* Speaker Bio & Profile Card */}
                        <div className="bg-white border border-gray-100 rounded-xl shadow-sm overflow-hidden">
                            <div className="p-6 flex flex-col sm:flex-row items-start sm:items-center gap-6">
                                <div className="shrink-0">
                                    <img
                                        src={photoUrl}
                                        className="w-24 h-24 rounded-full object-cover border-2 border-gray-200 shadow-sm"
                                        alt={speaker.user.name}
                                    />
                                </div>
                                <div className="flex-1 min-w-0">
                                    <h2 className="text-xl font-bold text-gray-900">{speaker.user.name}</h2>
                                    {(speaker.user.headline || speaker.organization) && (
                                        <p className="text-sm text-gray-600 mt-1">
                                            {speaker.user.headline || '—'} @ {speaker.organization || '—'}
                                        </p>
                                    )}
                                    {speaker.user.email && (
                                        <p className="mt-2 flex items-center gap-1.5 text-sm text-[#002147] font-medium">
                                            <i className="fas fa-envelope w-4 h-4"></i>
                                            {speaker.user.email}
                                        </p>
                                    )}
                                    {speaker.user.phone && (
                                        <p className="mt-1 flex items-center gap-1.5 text-sm text-gray-600">
                                            <i className="fas fa-phone w-4 h-4"></i>
                                            {speaker.user.phone}
                                        </p>
                                    )}
                                </div>
                            </div>

                            <div className="px-6 pb-6 border-t border-gray-100">
                                <h3 className="text-sm font-semibold text-gray-700 mb-3 flex items-center">
                                    <i className="fas fa-file-alt w-4 h-4 mr-2 text-[#002147]"></i>
                                    Speaker Bio
                                </h3>
                                <div className="prose prose-sm max-w-none text-gray-700 leading-relaxed">
                                    {speaker.bio ? (
                                        <p className="whitespace-pre-line">{speaker.bio}</p>
                                    ) : (
                                        <p className="text-gray-400">No bio available.</p>
                                    )}
                                </div>
                            </div>
                        </div>

                        {/* Contact & Social Links */}
                        <div className="bg-white border border-gray-100 rounded-xl shadow-sm p-6">
                            <h3 className="text-lg font-semibold text-gray-900 mb-4 flex items-center">
                                <i className="fas fa-link w-5 h-5 mr-2 text-[#002147]"></i>
                                Contact & Social Profiles
                            </h3>
                            <div className="grid grid-cols-1 md:grid-cols-2 gap-4">
                                {speaker.user.phone && (
                                    <div className="flex items-center gap-3 p-3 bg-gray-50 rounded-lg hover:bg-gray-100 transition">
                                        <div className="p-2 bg-[#002147]/10 rounded-lg">
                                            <i className="fas fa-phone w-4 h-4 text-[#002147]"></i>
                                        </div>
                                        <div>
                                            <div className="text-xs text-gray-500">Phone</div>
                                            <div className="font-medium text-gray-900">{speaker.user.phone}</div>
                                        </div>
                                    </div>
                                )}

                                {speaker.user.linkedin && (
                                    <div className="flex items-center gap-3 p-3 bg-gray-50 rounded-lg hover:bg-gray-100 transition">
                                        <div className="p-2 bg-blue-50 rounded-lg">
                                            <i className="fab fa-linkedin w-4 h-4 text-blue-600"></i>
                                        </div>
                                        <div>
                                            <div className="text-xs text-gray-500">LinkedIn</div>
                                            <a
                                                href={speaker.user.linkedin}
                                                target="_blank"
                                                rel="noopener noreferrer"
                                                className="font-medium text-blue-600 hover:underline hover:text-blue-800 truncate block max-w-[200px]"
                                            >
                                                {speaker.user.linkedin.substring(0, 30)}
                                            </a>
                                        </div>
                                    </div>
                                )}

                                {speaker.user.website && (
                                    <div className="flex items-center gap-3 p-3 bg-gray-50 rounded-lg hover:bg-gray-100 transition">
                                        <div className="p-2 bg-gray-100 rounded-lg">
                                            <i className="fas fa-globe w-4 h-4 text-gray-600"></i>
                                        </div>
                                        <div>
                                            <div className="text-xs text-gray-500">Website</div>
                                            <a
                                                href={speaker.user.website}
                                                target="_blank"
                                                rel="noopener noreferrer"
                                                className="font-medium text-gray-900 hover:underline hover:text-[#002147] truncate block max-w-[200px]"
                                            >
                                                {speaker.user.website.substring(0, 30)}
                                            </a>
                                        </div>
                                    </div>
                                )}

                                {!speaker.user.phone && !speaker.user.linkedin && !speaker.user.website && (
                                    <div className="col-span-full text-center py-6 text-gray-400">
                                        <i className="fas fa-unlink w-8 h-8 mx-auto mb-2"></i>
                                        <p>No contact or social links added yet.</p>
                                    </div>
                                )}
                            </div>
                        </div>
                    </div>

                    {/* Right Column: Status & Quick Actions */}
                    <div className="space-y-6">
                        {/* Status & Metadata */}
                        <div className="bg-white border border-gray-100 rounded-xl shadow-sm p-6">
                            <h3 className="text-lg font-semibold text-gray-900 mb-4">Profile Status</h3>
                            <div className="space-y-4">
                                <div className="flex justify-between items-center">
                                    <span className="text-sm text-gray-600">Status</span>
                                    {speaker.status === 'active' ? (
                                        <span className="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                            Active
                                        </span>
                                    ) : (
                                        <span className="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-800">
                                            Inactive
                                        </span>
                                    )}
                                </div>
                                <div className="flex justify-between items-center">
                                    <span className="text-sm text-gray-600">Created</span>
                                    <span className="text-sm font-medium text-gray-900">{createdDate}</span>
                                </div>
                                <div className="flex justify-between items-center">
                                    <span className="text-sm text-gray-600">Last Updated</span>
                                    <span className="text-sm font-medium text-gray-900">{updatedDate}</span>
                                </div>
                                {speaker.is_featured && (
                                    <div className="flex justify-between items-center">
                                        <span className="text-sm text-gray-600">Featured</span>
                                        <span className="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-amber-100 text-amber-800">
                                            <i className="fas fa-star w-3 h-3 mr-1"></i>
                                            Yes
                                        </span>
                                    </div>
                                )}
                            </div>
                        </div>

                        {/* Future Features Placeholder */}
                        <div className="bg-white border border-gray-100 rounded-xl shadow-sm p-6">
                            <h3 className="text-lg font-semibold text-gray-900 mb-4 flex items-center">
                                <i className="fas fa-rocket w-5 h-5 mr-2 text-[#002147]"></i>
                                Upcoming Features
                            </h3>
                            <p className="text-sm text-gray-600 mb-4">This space can be used for:</p>
                            <ul className="text-sm text-gray-600 space-y-2 list-disc list-inside">
                                <li>Session history & recordings</li>
                                <li>Speaker documents & contracts</li>
                                <li>Event participation timeline</li>
                                <li>Attendee feedback & ratings</li>
                            </ul>
                        </div>

                        {/* Quick Actions */}
                        <div className="bg-white border border-gray-100 rounded-xl shadow-sm p-6">
                            <h3 className="text-lg font-semibold text-gray-900 mb-4">Quick Actions</h3>
                            <div className="space-y-3">
                                <Link
                                    href={route('admin.speakers.edit', speaker.id)}
                                    className="flex items-center gap-3 w-full p-3 text-sm text-[#002147] bg-gray-50 rounded-lg hover:bg-gray-100 hover:text-[#FF0000] transition"
                                >
                                    <i className="fas fa-edit w-4 h-4"></i>
                                    Edit Speaker Profile
                                </Link>
                                <Link
                                    href={route('admin.speakers.index')}
                                    className="flex items-center gap-3 w-full p-3 text-sm text-gray-700 bg-gray-50 rounded-lg hover:bg-gray-100 transition"
                                >
                                    <i className="fas fa-list w-4 h-4"></i>
                                    View All Speakers
                                </Link>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {/* Delete Confirmation Modal */}
            {showDeleteModal && (
                <div className="fixed inset-0 bg-gray-600/40 bg-opacity-50 flex items-center justify-center z-50 px-4">
                    <div className="bg-white rounded-xl shadow-xl w-full max-w-md">
                        <div className="p-6">
                            <div className="flex justify-between items-center mb-4">
                                <h3 className="text-xl font-semibold text-gray-900">Delete Speaker</h3>
                                <button
                                    onClick={() => setShowDeleteModal(false)}
                                    className="text-gray-400 cursor-pointer hover:text-gray-500"
                                >
                                    <i className="fas fa-times w-6 h-6"></i>
                                </button>
                            </div>

                            <div className="mb-6">
                                <div className="mx-auto mb-4 text-red-400 w-12 h-12 flex items-center justify-center">
                                    <i className="fas fa-exclamation-triangle w-12 h-12"></i>
                                </div>
                                <p className="text-center text-lg text-gray-700">
                                    Are you sure you want to permanently delete <strong>{speaker.user.name}</strong>? This action cannot be undone.
                                </p>
                            </div>

                            <div className="flex justify-center gap-3">
                                <button
                                    onClick={handleDelete}
                                    disabled={isDeleting}
                                    className="text-white bg-red-600 hover:bg-red-700 focus:ring-4 focus:outline-none focus:ring-red-300 font-medium rounded-lg text-sm px-5 py-2.5 disabled:opacity-50 disabled:cursor-not-allowed"
                                >
                                    {isDeleting ? 'Deleting...' : 'Yes, Delete'}
                                </button>
                                <button
                                    onClick={() => setShowDeleteModal(false)}
                                    className="py-2.5 px-5 text-sm font-medium text-gray-900 bg-white rounded-lg border border-gray-200 hover:bg-gray-100"
                                >
                                    Cancel
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            )}
        </DashboardLayout>
    );
}
