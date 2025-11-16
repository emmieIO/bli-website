import { Head, Link, router, usePage } from '@inertiajs/react';
import DashboardLayout from '@/Layouts/DashboardLayout';
import { useState } from 'react';

interface User {
    id: number;
    name: string;
    email: string;
    phone?: string;
    headline: string;
}

interface Instructor {
    id: number;
    application_id: string;
    created_at: string;
    deleted_at?: string;
    user: User;
}

interface PaginatedInstructors {
    data: Instructor[];
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

interface InstructorsProps {
    instructors: PaginatedInstructors;
}

export default function InstructorsIndex({ instructors }: InstructorsProps) {
    const { sideLinks } = usePage().props as any;
    const [selectedInstructors, setSelectedInstructors] = useState<number[]>([]);
    const [showDeleteModal, setShowDeleteModal] = useState(false);
    const [showRestoreModal, setShowRestoreModal] = useState(false);
    const [selectedInstructor, setSelectedInstructor] = useState<Instructor | null>(null);
    const [isProcessing, setIsProcessing] = useState(false);

    const handleSelectAll = (e: React.ChangeEvent<HTMLInputElement>) => {
        if (e.target.checked) {
            setSelectedInstructors(instructors.data.map((i) => i.id));
        } else {
            setSelectedInstructors([]);
        }
    };

    const handleSelectOne = (id: number) => {
        if (selectedInstructors.includes(id)) {
            setSelectedInstructors(selectedInstructors.filter((i) => i !== id));
        } else {
            setSelectedInstructors([...selectedInstructors, id]);
        }
    };

    const openDeleteModal = (instructor: Instructor) => {
        setSelectedInstructor(instructor);
        setShowDeleteModal(true);
    };

    const openRestoreModal = (instructor: Instructor) => {
        setSelectedInstructor(instructor);
        setShowRestoreModal(true);
    };

    const handleDelete = () => {
        if (!selectedInstructor) return;

        setIsProcessing(true);
        router.delete(route('admin.instructors.destroy', selectedInstructor.id), {
            preserveScroll: true,
            onFinish: () => {
                setIsProcessing(false);
                setShowDeleteModal(false);
                setSelectedInstructor(null);
            },
        });
    };

    const handleRestore = () => {
        if (!selectedInstructor) return;

        setIsProcessing(true);
        router.post(
            route('admin.instructors.restore', selectedInstructor.id),
            {},
            {
                preserveScroll: true,
                onFinish: () => {
                    setIsProcessing(false);
                    setShowRestoreModal(false);
                    setSelectedInstructor(null);
                },
            }
        );
    };

    const formatDate = (dateString: string) => {
        const date = new Date(dateString);
        return date.toLocaleDateString('en-US', {
            year: 'numeric',
            month: 'short',
        });
    };

    const formatDateFull = (dateString?: string) => {
        if (!dateString) return '—';
        const date = new Date(dateString);
        return date.toLocaleDateString('en-US', {
            year: 'numeric',
            month: 'short',
            day: 'numeric',
        });
    };

    return (
        <DashboardLayout sideLinks={sideLinks}>
            <Head title="Instructor Management - Beacon Leadership Institute" />

            {/* Header and Controls */}
            <div className="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 mb-6">
                <div>
                    <h2 className="text-2xl font-bold text-primary font-montserrat">Instructor Management</h2>
                    <p className="text-sm text-gray-500 mt-1 font-lato">
                        Manage all instructor profiles and applications
                    </p>
                </div>
            </div>

            {/* Instructor Tabs - Placeholder for future tabs */}
            <div className="mb-6 flex gap-2 border-b border-gray-200">
                <Link
                    href={route('admin.instructors.index')}
                    className="px-4 py-2 text-sm font-medium border-b-2 border-primary text-primary font-montserrat"
                >
                    Active Instructors
                </Link>
                <Link
                    href={route('admin.instructors.applications')}
                    className="px-4 py-2 text-sm font-medium text-gray-500 hover:text-gray-700 border-b-2 border-transparent font-montserrat"
                >
                    Applications
                </Link>
            </div>

            <div className="space-y-6">
                {/* Mass Actions Bar */}
                {selectedInstructors.length > 0 && (
                    <div className="bg-primary-50 px-6 py-3 border-b border-primary-200 rounded-t-lg">
                        <div className="flex items-center justify-between">
                            <p className="text-sm text-primary-700 font-lato">
                                {selectedInstructors.length} instructor{selectedInstructors.length !== 1 ? 's' : ''}{' '}
                                selected
                            </p>
                            <button
                                onClick={() => {
                                    /* Mass delete functionality */
                                }}
                                className="text-red-600 hover:text-red-700 text-sm font-medium inline-flex items-center gap-2 font-lato transition-colors"
                            >
                                <i className="fas fa-trash w-4 h-4"></i> Delete Selected
                            </button>
                        </div>
                    </div>
                )}

                {/* Instructors Table */}
                <div className="bg-white rounded-lg shadow border border-gray-200 overflow-hidden">
                    <div className="overflow-x-auto">
                        <table className="min-w-full divide-y divide-gray-200 text-sm">
                            <thead className="bg-gray-50">
                                <tr>
                                    <th
                                        scope="col"
                                        className="px-6 py-3 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider font-montserrat w-4"
                                    >
                                        <input
                                            type="checkbox"
                                            checked={
                                                instructors.data.length > 0 &&
                                                selectedInstructors.length === instructors.data.length
                                            }
                                            onChange={handleSelectAll}
                                            className="rounded border-gray-300 text-primary focus:ring-primary"
                                        />
                                    </th>
                                    <th
                                        scope="col"
                                        className="px-6 py-3 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider font-montserrat"
                                    >
                                        Profile ID
                                    </th>
                                    <th
                                        scope="col"
                                        className="px-6 py-3 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider font-montserrat"
                                    >
                                        Instructor
                                    </th>
                                    <th
                                        scope="col"
                                        className="px-6 py-3 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider font-montserrat"
                                    >
                                        Contact
                                    </th>
                                    <th
                                        scope="col"
                                        className="px-6 py-3 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider font-montserrat"
                                    >
                                        Headline
                                    </th>
                                    <th
                                        scope="col"
                                        className="px-6 py-3 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider font-montserrat"
                                    >
                                        Deleted At
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
                                {instructors.data.length > 0 ? (
                                    instructors.data.map((instructor) => (
                                        <tr key={instructor.id} className="hover:bg-gray-50 transition-colors">
                                            {/* Checkbox */}
                                            <td className="px-6 py-4">
                                                <input
                                                    type="checkbox"
                                                    checked={selectedInstructors.includes(instructor.id)}
                                                    onChange={() => handleSelectOne(instructor.id)}
                                                    className="rounded border-gray-300 text-primary focus:ring-primary"
                                                />
                                            </td>

                                            {/* Profile ID */}
                                            <td className="px-6 py-4 font-medium text-primary whitespace-nowrap font-montserrat">
                                                <span className="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-primary-100 text-primary-800 font-montserrat">
                                                    {instructor.application_id}
                                                </span>
                                            </td>

                                            {/* Instructor Info */}
                                            <td className="px-6 py-4">
                                                <div className="flex items-center gap-3">
                                                    <div>
                                                        <div className="font-medium text-gray-900 font-montserrat">
                                                            {instructor.user.name}
                                                        </div>
                                                        <div className="text-xs text-gray-500 font-lato">
                                                            Joined {formatDate(instructor.created_at)}
                                                        </div>
                                                    </div>
                                                </div>
                                            </td>

                                            {/* Contact Info */}
                                            <td className="px-6 py-4">
                                                <div className="text-sm text-gray-900 font-montserrat">
                                                    {instructor.user.email}
                                                </div>
                                                <div className="text-sm text-gray-500 font-lato">
                                                    {instructor.user.phone || 'No phone'}
                                                </div>
                                            </td>

                                            {/* Headline */}
                                            <td className="px-6 py-4">
                                                <div className="text-sm text-gray-900 font-montserrat line-clamp-2">
                                                    {instructor.user.headline}
                                                </div>
                                            </td>

                                            {/* Deleted At */}
                                            <td className="px-6 py-4">
                                                <div className="text-sm text-gray-900 font-montserrat">
                                                    {formatDateFull(instructor.deleted_at)}
                                                </div>
                                            </td>

                                            {/* Actions */}
                                            <td className="px-6 py-4 text-right">
                                                <div className="inline-flex gap-2">
                                                    {instructor.deleted_at ? (
                                                        <button
                                                            type="button"
                                                            onClick={() => openRestoreModal(instructor)}
                                                            className="p-1.5 text-accent hover:text-accent-600 rounded-full hover:bg-accent-100 transition-colors"
                                                            title="Restore instructor"
                                                        >
                                                            <i className="fas fa-undo w-4 h-4"></i>
                                                        </button>
                                                    ) : (
                                                        <>
                                                            <Link
                                                                href={route('admin.instructors.edit', instructor.id)}
                                                                title="Edit"
                                                                className="p-1.5 text-primary hover:text-primary-600 rounded-full hover:bg-primary-100 transition-colors"
                                                            >
                                                                <i className="fas fa-edit w-4 h-4"></i>
                                                            </Link>
                                                            <Link
                                                                href={route('admin.instructors.view', instructor.id)}
                                                                title="View"
                                                                className="p-1.5 text-primary hover:text-primary-600 rounded-full hover:bg-primary-100 transition-colors"
                                                            >
                                                                <i className="fas fa-eye w-4 h-4"></i>
                                                            </Link>
                                                            <button
                                                                onClick={() => openDeleteModal(instructor)}
                                                                title="Delete"
                                                                className="p-1.5 text-red-600 hover:text-red-700 rounded-full hover:bg-red-100 transition-colors"
                                                            >
                                                                <i className="fas fa-trash w-4 h-4"></i>
                                                            </button>
                                                        </>
                                                    )}
                                                </div>
                                            </td>
                                        </tr>
                                    ))
                                ) : (
                                    <tr>
                                        <td colSpan={7} className="px-6 py-12 text-center">
                                            <div className="flex flex-col items-center justify-center space-y-4 text-gray-400">
                                                <i className="fas fa-users w-12 h-12 text-gray-300"></i>
                                                <h3 className="text-lg font-medium text-gray-900 font-montserrat">
                                                    No instructors found
                                                </h3>
                                                <p className="max-w-md text-center font-lato">
                                                    Add your first instructor to get started
                                                </p>
                                            </div>
                                        </td>
                                    </tr>
                                )}
                            </tbody>
                        </table>
                    </div>

                    {/* Pagination */}
                    {instructors.last_page > 1 && (
                        <div className="px-6 py-4 border-t border-gray-200 bg-gray-50">
                            <Pagination links={instructors.links} />
                        </div>
                    )}
                </div>
            </div>

            {/* Delete Modal */}
            {showDeleteModal && selectedInstructor && (
                <Modal
                    title="Delete Instructor"
                    icon="exclamation-triangle"
                    iconColor="text-red-600"
                    onClose={() => setShowDeleteModal(false)}
                >
                    <p className="text-gray-700 mb-6 font-lato">
                        Are you sure you want to delete{' '}
                        <span className="font-semibold">{selectedInstructor.user.name}</span>? This action can be
                        reversed later.
                    </p>
                    <div className="flex justify-end gap-3">
                        <button
                            onClick={() => setShowDeleteModal(false)}
                            className="py-2.5 px-5 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-lg hover:bg-gray-100 transition-colors font-lato"
                        >
                            Cancel
                        </button>
                        <button
                            onClick={handleDelete}
                            disabled={isProcessing}
                            className="px-5 py-2.5 text-sm font-medium text-white bg-red-600 hover:bg-red-700 rounded-lg transition-colors inline-flex items-center font-montserrat disabled:opacity-50"
                        >
                            {isProcessing ? (
                                <>
                                    <i className="fas fa-spinner fa-spin w-4 h-4 mr-2"></i>
                                    Deleting...
                                </>
                            ) : (
                                <>
                                    <i className="fas fa-trash w-4 h-4 mr-2"></i>
                                    Delete
                                </>
                            )}
                        </button>
                    </div>
                </Modal>
            )}

            {/* Restore Modal */}
            {showRestoreModal && selectedInstructor && (
                <Modal
                    title="Restore Instructor"
                    icon="undo"
                    iconColor="text-accent"
                    onClose={() => setShowRestoreModal(false)}
                >
                    <p className="text-gray-700 mb-6 font-lato">
                        Are you sure you want to restore{' '}
                        <span className="font-semibold">{selectedInstructor.user.name}</span>?
                    </p>
                    <div className="flex justify-end gap-3">
                        <button
                            onClick={() => setShowRestoreModal(false)}
                            className="py-2.5 px-5 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-lg hover:bg-gray-100 transition-colors font-lato"
                        >
                            Cancel
                        </button>
                        <button
                            onClick={handleRestore}
                            disabled={isProcessing}
                            className="px-5 py-2.5 text-sm font-medium text-white bg-accent hover:bg-accent-600 rounded-lg transition-colors inline-flex items-center font-montserrat disabled:opacity-50"
                        >
                            {isProcessing ? (
                                <>
                                    <i className="fas fa-spinner fa-spin w-4 h-4 mr-2"></i>
                                    Restoring...
                                </>
                            ) : (
                                <>
                                    <i className="fas fa-undo w-4 h-4 mr-2"></i>
                                    Restore
                                </>
                            )}
                        </button>
                    </div>
                </Modal>
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

function Modal({
    title,
    icon,
    iconColor,
    children,
    onClose,
}: {
    title: string;
    icon: string;
    iconColor: string;
    children: React.ReactNode;
    onClose: () => void;
}) {
    return (
        <div className="fixed inset-0 z-50 flex items-center justify-center bg-black/30 backdrop-blur-sm">
            <div className="relative bg-white rounded-xl shadow-xl p-6 w-full max-w-md mx-4 border border-gray-200">
                <button
                    type="button"
                    onClick={onClose}
                    className="absolute top-3 right-3 text-gray-400 hover:bg-gray-200 hover:text-gray-900 rounded-lg text-sm w-8 h-8 inline-flex justify-center items-center"
                >
                    <i className="fas fa-times w-3 h-3"></i>
                </button>

                <div className="text-center mb-6">
                    <i className={`fas fa-${icon} w-12 h-12 ${iconColor} mx-auto mb-4`}></i>
                    <h3 className="text-lg font-semibold text-gray-800 font-montserrat">{title}</h3>
                </div>

                {children}
            </div>
        </div>
    );
}
