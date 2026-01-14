import { Head, Link, router, usePage } from '@inertiajs/react';
import DashboardLayout from '@/Layouts/DashboardLayout';
import { useState } from 'react';

interface User {
    id: number;
    name: string;
    email: string;
    phone?: string;
    photo?: string;
    headline?: string;
}

interface Speaker {
    id: number;
    user: User;
    organization?: string;
    title?: string;
    photo?: string;
    is_featured: boolean;
    status: {
        value: 'active' | 'inactive';
    };
    application_status?: 'pending' | 'approved' | 'rejected';
}

interface PaginatedSpeakers {
    data: Speaker[];
    current_page: number;
    last_page: number;
    per_page: number;
    total: number;
    from: number;
    to: number;
    links: Array<{
        url: string | null;
        label: string;
        active: boolean;
    }>;
}

interface SpeakersProps {
    speakers: PaginatedSpeakers;
}

export default function PendingSpeakers({ speakers }: SpeakersProps) {
    const { sideLinks } = usePage().props as any;
    const [showDeleteModal, setShowDeleteModal] = useState(false);
    const [selectedSpeaker, setSelectedSpeaker] = useState<Speaker | null>(null);
    const [isDeleting, setIsDeleting] = useState(false);
    const [processingId, setProcessingId] = useState<number | null>(null);
    const [sortBy, setSortBy] = useState('name-asc');

    const openDeleteModal = (speaker: Speaker) => {
        setSelectedSpeaker(speaker);
        setShowDeleteModal(true);
    };

    const handleDelete = () => {
        if (!selectedSpeaker) return;

        setIsDeleting(true);
        router.delete(route('admin.speakers.destroy', selectedSpeaker.id), {
            preserveScroll: true,
            onFinish: () => {
                setIsDeleting(false);
                setShowDeleteModal(false);
                setSelectedSpeaker(null);
            },
        });
    };

    const handleActivate = (speaker: Speaker) => {
        if (!confirm(`Activate ${speaker.user.name} as a speaker?`)) return;

        setProcessingId(speaker.id);
        router.post(
            route('admin.speakers.activate', speaker.id),
            {},
            {
                preserveScroll: true,
                onFinish: () => {
                    setProcessingId(null);
                },
            }
        );
    };

    const handleSortChange = (value: string) => {
        setSortBy(value);
        // In a real implementation, this would trigger a server request with sorting params
    };

    const isActive = (speaker: Speaker) => speaker.status.value === 'active';

    const getStatusBadge = (status: string) => {
        if (status === 'active') {
            return (
                <span className="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800 font-montserrat">
                    Active
                </span>
            );
        }
        return (
            <span className="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-800 font-montserrat">
                Inactive
            </span>
        );
    };

    return (
        <DashboardLayout sideLinks={sideLinks}>
            <Head title="Pending Speakers" />

            {/* Header Section */}
            <div className="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 mb-8">
                <div className="flex items-center gap-3">
                    <div>
                        <h1 className="text-2xl font-bold text-primary font-montserrat">Pending Speakers</h1>
                        <p className="text-sm text-gray-500 font-lato">
                            Manage all conference speakers and their details
                        </p>
                    </div>
                </div>
            </div>

            {/* Status Tabs */}
            <div className="mb-6 flex gap-2 border-b border-gray-200">
                <Link
                    href={route('admin.speakers.index')}
                    className="px-4 py-2 text-sm font-medium text-gray-500 hover:text-gray-700 border-b-2 border-transparent font-montserrat"
                >
                    Active Speakers
                </Link>
                <Link
                    href={route('admin.speakers.pending')}
                    className="px-4 py-2 text-sm font-medium border-b-2 border-primary text-primary font-montserrat"
                >
                    Pending Speakers
                </Link>
                <Link
                    href={route('admin.speakers.applications.pending')}
                    className="px-4 py-2 text-sm font-medium text-gray-500 hover:text-gray-700 border-b-2 border-transparent font-montserrat"
                >
                    Applications (Pending)
                </Link>
                <Link
                    href={route('admin.speakers.applications.approved')}
                    className="px-4 py-2 text-sm font-medium text-gray-500 hover:text-gray-700 border-b-2 border-transparent font-montserrat"
                >
                    Applications (Approved)
                </Link>
            </div>

            {/* Main Content */}
            <div className="bg-white shadow rounded-xl border border-gray-200 overflow-hidden">
                {/* Table Header with Stats */}
                <div className="px-6 py-4 border-b border-gray-200 bg-gray-50 flex flex-wrap items-center justify-between gap-4">
                    <div className="flex items-center gap-4">
                        <span className="text-sm font-medium text-gray-700 font-lato">
                            Showing <span className="font-semibold font-montserrat">{speakers.from}–{speakers.to}</span>{' '}
                            of <span className="font-semibold font-montserrat">{speakers.total}</span> speakers
                        </span>
                    </div>
                    <div className="flex items-center gap-2">
                        <label htmlFor="sort" className="text-sm font-medium text-gray-700 whitespace-nowrap font-lato">
                            Sort by:
                        </label>
                        <select
                            id="sort"
                            value={sortBy}
                            onChange={(e) => handleSortChange(e.target.value)}
                            className="block w-full pl-3 pr-10 py-2 text-sm border-gray-300 focus:outline-none focus:ring-2 focus:ring-primary focus:border-primary rounded-md font-lato"
                        >
                            <option value="name-asc">Name (A-Z)</option>
                            <option value="name-desc">Name (Z-A)</option>
                            <option value="recent">Recently Added</option>
                            <option value="updated">Recently Updated</option>
                        </select>
                    </div>
                </div>

                {/* Speakers Table */}
                <div className="overflow-x-auto">
                    <table className="min-w-full divide-y divide-gray-200">
                        <thead className="bg-gray-50">
                            <tr>
                                <th
                                    scope="col"
                                    className="px-6 py-3 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider font-montserrat"
                                >
                                    Speaker
                                </th>
                                <th
                                    scope="col"
                                    className="px-6 py-3 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider font-montserrat"
                                >
                                    Title & Organization
                                </th>
                                <th
                                    scope="col"
                                    className="px-6 py-3 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider font-montserrat"
                                >
                                    Status
                                </th>
                                <th
                                    scope="col"
                                    className="px-6 py-3 text-right text-xs font-semibold text-gray-700 uppercase tracking-wider font-montserrat"
                                >
                                    Actions
                                </th>
                            </tr>
                        </thead>
                        <tbody className="bg-white divide-y divide-gray-100">
                            {speakers.data.length > 0 ? (
                                speakers.data.map((speaker) => (
                                    <tr key={speaker.id} className="hover:bg-gray-50 transition-colors">
                                        {/* Speaker Column */}
                                        <td className="px-6 py-4">
                                            <div className="flex items-center gap-3">
                                                <div className="shrink-0 h-10 w-10 relative">
                                                    <img
                                                        className="h-10 w-10 rounded-full object-cover border border-gray-200"
                                                        src={
                                                            speaker.user.photo
                                                                ? `/storage/${speaker.user.photo}`
                                                                : `https://i.pravatar.cc/40?u=${speaker.user.email}`
                                                        }
                                                        alt={speaker.user.name}
                                                    />
                                                    {speaker.is_featured && (
                                                        <span className="absolute -top-1 -right-1 bg-accent text-white rounded-full p-0.5">
                                                            <i className="fas fa-star w-3 h-3"></i>
                                                        </span>
                                                    )}
                                                </div>
                                                <div>
                                                    <div className="font-medium text-gray-900 font-montserrat">
                                                        {speaker.user.name}
                                                    </div>
                                                    <div className="text-sm text-gray-500 flex items-center gap-1 font-lato">
                                                        <i className="fas fa-envelope w-3 h-3"></i>
                                                        {speaker.user.email}
                                                    </div>
                                                    {speaker.user.phone && (
                                                        <div className="text-sm text-gray-500 flex items-center gap-1 font-lato">
                                                            <i className="fas fa-phone w-3 h-3"></i>
                                                            {speaker.user.phone}
                                                        </div>
                                                    )}
                                                </div>
                                            </div>
                                        </td>

                                        {/* Title & Organization Column */}
                                        <td className="px-6 py-4">
                                            {speaker.user.headline || speaker.organization ? (
                                                <>
                                                    <div className="text-sm font-medium text-gray-900 font-montserrat">
                                                        {speaker.user.headline ?? '—'}
                                                    </div>
                                                    <div className="text-sm text-gray-500 font-lato">
                                                        {speaker.organization ?? '—'}
                                                    </div>
                                                </>
                                            ) : (
                                                <span className="text-sm text-gray-400 font-lato">Not specified</span>
                                            )}
                                        </td>

                                        {/* Status Column */}
                                        <td className="px-6 py-4">{getStatusBadge(speaker.status.value)}</td>

                                        {/* Actions Column */}
                                        <td className="px-6 py-4 text-right">
                                            <div className="inline-flex gap-2 items-center">
                                                <Link
                                                    href={route('admin.speakers.show', speaker.id)}
                                                    title="View details"
                                                    className="p-2 text-gray-500 hover:text-gray-700 rounded-full hover:bg-gray-100 transition-colors"
                                                >
                                                    <i className="fas fa-eye w-5 h-5"></i>
                                                </Link>
                                                <Link
                                                    href={route('admin.speakers.edit', speaker.id)}
                                                    title="Edit"
                                                    className="p-2 text-primary hover:text-primary-600 rounded-full hover:bg-primary-50 transition-colors"
                                                >
                                                    <i className="fas fa-edit w-5 h-5"></i>
                                                </Link>
                                                {!isActive(speaker) ? (
                                                    <button
                                                        onClick={() => handleActivate(speaker)}
                                                        title="Approve Speaker"
                                                        disabled={processingId === speaker.id}
                                                        className="p-2 text-accent hover:text-accent-600 rounded-full hover:bg-accent-50 transition-colors disabled:opacity-50 disabled:pointer-events-none"
                                                    >
                                                        <i className="fas fa-check-circle w-5 h-5"></i>
                                                    </button>
                                                ) : (
                                                    <button
                                                        title="Ban Speaker"
                                                        className="p-2 text-red-600 hover:text-red-700 rounded-full hover:bg-red-50 transition-colors"
                                                    >
                                                        <i className="fas fa-ban w-5 h-5"></i>
                                                    </button>
                                                )}
                                                <button
                                                    onClick={() => openDeleteModal(speaker)}
                                                    title="Delete"
                                                    className="p-2 text-red-600 hover:text-red-700 rounded-full hover:bg-red-50 transition-colors"
                                                >
                                                    <i className="fas fa-trash w-5 h-5"></i>
                                                </button>
                                            </div>
                                        </td>
                                    </tr>
                                ))
                            ) : (
                                <tr>
                                    <td colSpan={4} className="px-6 py-16 text-center">
                                        <div className="flex flex-col items-center justify-center space-y-4 text-gray-400">
                                            <i className="fas fa-microphone-slash w-12 h-12 text-gray-300"></i>
                                            <h3 className="text-lg font-medium text-gray-900 font-montserrat">
                                                No speakers found
                                            </h3>
                                            <p className="max-w-md text-center font-lato">
                                                Get started by adding your first speaker to the event.
                                            </p>
                                            <div className="flex flex-col sm:flex-row gap-3 mt-4">
                                                <Link
                                                    href={route('admin.speakers.applications.pending')}
                                                    className="inline-flex items-center px-4 py-2 bg-white border border-gray-300 text-gray-700 text-sm font-medium rounded-lg hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary transition shadow-sm font-montserrat"
                                                >
                                                    <i className="fas fa-list-check w-4 h-4 mr-2"></i>
                                                    View Pending Applications
                                                </Link>
                                                <Link
                                                    href={route('admin.speakers.create')}
                                                    className="inline-flex items-center px-4 py-2 bg-primary text-white text-sm font-medium rounded-lg hover:bg-primary-600 focus:ring-4 focus:ring-primary-300 transition shadow-sm font-montserrat"
                                                >
                                                    <i className="fas fa-plus w-4 h-4 mr-2"></i>
                                                    Add Speaker
                                                </Link>
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                            )}
                        </tbody>
                    </table>
                </div>

                {/* Pagination */}
                {speakers.last_page > 1 && (
                    <div className="px-6 py-4 border-t border-gray-200 bg-gray-50">
                        <Pagination links={speakers.links} />
                    </div>
                )}
            </div>

            {/* Delete Confirmation Modal */}
            {showDeleteModal && selectedSpeaker && (
                <div className="fixed inset-0 z-50 flex items-center justify-center bg-black/30 backdrop-blur-sm">
                    <div className="relative bg-white rounded-xl shadow-xl p-6 w-full max-w-md mx-4 border border-gray-200">
                        {/* Modal header */}
                        <div className="flex items-center justify-between pb-4 mb-4 border-b border-gray-200">
                            <h3 className="text-lg font-semibold text-gray-900 font-montserrat">
                                Confirm Speaker Deletion
                            </h3>
                            <button
                                type="button"
                                onClick={() => setShowDeleteModal(false)}
                                className="text-gray-400 bg-transparent hover:bg-gray-200 hover:text-gray-900 rounded-lg text-sm w-8 h-8 inline-flex justify-center items-center transition-colors"
                            >
                                <i className="fas fa-times w-4 h-4"></i>
                            </button>
                        </div>

                        {/* Modal body */}
                        <div className="space-y-4">
                            <div className="flex flex-col items-center text-center">
                                <div className="w-12 h-12 bg-red-100 rounded-full flex items-center justify-center mb-4">
                                    <i className="fas fa-exclamation-triangle w-6 h-6 text-red-600"></i>
                                </div>
                                <h3 className="text-lg font-medium text-gray-900 font-montserrat mb-2">
                                    Delete Speaker
                                </h3>
                                <div className="text-sm text-gray-500 font-lato">
                                    <p>
                                        Are you sure you want to delete{' '}
                                        <span className="font-semibold text-gray-900">
                                            {selectedSpeaker.user.name}
                                        </span>
                                        ?
                                    </p>
                                    <p className="mt-1">This action cannot be undone.</p>
                                </div>
                            </div>
                        </div>

                        {/* Modal footer */}
                        <div className="flex items-center justify-end pt-4 mt-4 border-t border-gray-200 gap-3">
                            <button
                                onClick={() => setShowDeleteModal(false)}
                                type="button"
                                className="py-2.5 px-5 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-lg hover:bg-gray-100 transition-colors font-lato"
                            >
                                Cancel
                            </button>
                            <button
                                onClick={handleDelete}
                                disabled={isDeleting}
                                className="px-5 py-2.5 text-sm font-medium text-white bg-red-600 hover:bg-red-700 rounded-lg transition-colors inline-flex items-center font-montserrat disabled:opacity-50 disabled:cursor-not-allowed"
                            >
                                {isDeleting ? (
                                    <>
                                        <i className="fas fa-spinner fa-spin w-4 h-4 mr-2"></i>
                                        Deleting...
                                    </>
                                ) : (
                                    <>
                                        <i className="fas fa-trash w-4 h-4 mr-2"></i>
                                        Delete Speaker
                                    </>
                                )}
                            </button>
                        </div>
                    </div>
                </div>
            )}
        </DashboardLayout>
    );
}

function Pagination({ links }: { links: Array<{ url: string | null; label: string; active: boolean }> }) {
    return (
        <nav className="flex gap-1">
            {links.map((link, index) => {
                const label = link.label.replace('&laquo; Previous', '«').replace('Next &raquo;', '»');

                if (!link.url) {
                    return (
                        <span key={index} className="px-3 py-2 text-sm text-gray-400 cursor-not-allowed font-lato">
                            {label}
                        </span>
                    );
                }

                return (
                    <Link
                        key={index}
                        href={link.url}
                        className={`px-3 py-2 text-sm rounded-lg transition-colors font-lato ${
                            link.active ? 'bg-primary text-white' : 'text-gray-700 hover:bg-gray-100'
                        }`}
                        preserveScroll
                    >
                        {label}
                    </Link>
                );
            })}
        </nav>
    );
}
