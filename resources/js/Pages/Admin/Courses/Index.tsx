import { Head, Link, router, usePage } from '@inertiajs/react';
import DashboardLayout from '@/Layouts/DashboardLayout';
import { useState } from 'react';
import ConfirmModal from '@/Components/ConfirmModal';

interface Instructor {
    id: number;
    name: string;
}

interface Category {
    id: number;
    name: string;
}

interface Course {
    id: number;
    slug: string;
    title: string;
    description?: string;
    price: number;
    level: string;
    thumbnail_path?: string;
    status: 'draft' | 'pending' | 'under_review' | 'approved' | 'rejected';
    instructor: Instructor;
    category: Category;
}

interface CoursesProps {
    courses: Course[];
    categories: Category[];
}

export default function CoursesIndex({ courses, categories }: CoursesProps) {
    const { sideLinks } = usePage().props as any;
    const [showDeleteModal, setShowDeleteModal] = useState(false);
    const [showApproveModal, setShowApproveModal] = useState(false);
    const [showRejectModal, setShowRejectModal] = useState(false);
    const [selectedCourse, setSelectedCourse] = useState<Course | null>(null);
    const [isDeleting, setIsDeleting] = useState(false);
    const [isApproving, setIsApproving] = useState(false);
    const [isRejecting, setIsRejecting] = useState(false);
    const [rejectionReason, setRejectionReason] = useState('');

    const openDeleteModal = (course: Course) => {
        setSelectedCourse(course);
        setShowDeleteModal(true);
    };

    const openRejectModal = (course: Course) => {
        setSelectedCourse(course);
        setRejectionReason('');
        setShowRejectModal(true);
    };

    const handleDelete = () => {
        if (!selectedCourse) return;

        setIsDeleting(true);
        router.delete(route('admin.courses.destroy', selectedCourse.slug), {
            preserveScroll: true,
            onFinish: () => {
                setIsDeleting(false);
                setShowDeleteModal(false);
                setSelectedCourse(null);
            },
        });
    };

    const openApproveModal = (course: Course) => {
        setSelectedCourse(course);
        setShowApproveModal(true);
    };

    const handleApprove = () => {
        if (!selectedCourse) return;

        setIsApproving(true);
        router.post(route('admin.courses.approve', selectedCourse.slug), {}, {
            preserveScroll: true,
            onFinish: () => {
                setIsApproving(false);
                setShowApproveModal(false);
                setSelectedCourse(null);
            },
        });
    };

    const handleReject = () => {
        if (!selectedCourse) return;

        setIsRejecting(true);
        router.post(route('admin.courses.reject', selectedCourse.slug), {
            rejection_reason: rejectionReason,
        }, {
            preserveScroll: true,
            onFinish: () => {
                setIsRejecting(false);
                setShowRejectModal(false);
                setSelectedCourse(null);
                setRejectionReason('');
            },
        });
    };

    const getStatusBadge = (status: string) => {
        const statusColors: Record<string, string> = {
            draft: 'bg-gray-100 text-gray-800',
            pending: 'bg-yellow-100 text-yellow-800',
            under_review: 'bg-blue-100 text-blue-800',
            approved: 'bg-green-100 text-green-800',
            rejected: 'bg-red-100 text-red-800',
        };
        const colorClass = statusColors[status.toLowerCase()] || 'bg-gray-100 text-gray-800';

        const statusLabels: Record<string, string> = {
            draft: 'Draft',
            pending: 'Pending Review',
            under_review: 'Under Review',
            approved: 'Approved',
            rejected: 'Rejected',
        };
        const label = statusLabels[status.toLowerCase()] || status;

        return (
            <span className={`inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium font-montserrat ${colorClass}`}>
                {label}
            </span>
        );
    };

    return (
        <DashboardLayout sideLinks={sideLinks}>
            <Head title="Course Management - Beacon Leadership Institute" />

            {/* Header */}
            <div className="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 mb-8">
                <div>
                    <h1 className="text-3xl font-bold text-gray-800 mb-2 font-montserrat">Course Management</h1>
                    <p className="text-gray-600 font-lato">
                        Manage all available courses, add new ones, or update existing course details.
                    </p>
                </div>
                <div className="flex items-center gap-3">
                    <Link
                        href={route('admin.courses.create')}
                        className="inline-flex items-center gap-2 bg-primary hover:bg-primary-600 py-3 px-6 rounded-lg font-medium text-white transition-all duration-200 shadow-sm font-montserrat"
                    >
                        <i className="fas fa-plus w-4 h-4"></i>
                        <span>Create Course</span>
                    </Link>
                </div>
            </div>

            {/* Courses Table */}
            <div className="relative overflow-x-auto shadow-lg rounded-xl bg-white border border-gray-200">
                <table className="w-full text-sm text-left text-gray-600">
                    <thead className="text-xs text-gray-600 uppercase bg-gray-50 border-b border-gray-200">
                        <tr>
                            <th scope="col" className="px-6 py-4 font-semibold font-montserrat">
                                Course Details
                            </th>
                            <th scope="col" className="px-6 py-4 font-semibold font-montserrat">
                                Thumbnail
                            </th>
                            <th scope="col" className="px-6 py-4 font-semibold font-montserrat">
                                Price
                            </th>
                            <th scope="col" className="px-6 py-4 font-semibold font-montserrat">
                                Instructor
                            </th>
                            <th scope="col" className="px-6 py-4 font-semibold font-montserrat">
                                Category
                            </th>
                            <th scope="col" className="px-6 py-4 font-semibold font-montserrat">
                                Status
                            </th>
                            <th scope="col" className="px-6 py-4 font-semibold font-montserrat">
                                Actions
                            </th>
                        </tr>
                    </thead>
                    <tbody>
                        {courses.length > 0 ? (
                            courses.map((course) => (
                                <tr
                                    key={course.id}
                                    className="bg-white border-b border-gray-100 hover:bg-gray-50 transition-colors duration-150"
                                >
                                    {/* Course Details */}
                                    <td className="px-6 py-4">
                                        <div className="flex items-start gap-3">
                                            <div className="flex-1 min-w-0">
                                                <h3 className="font-semibold text-gray-900 text-base leading-tight font-montserrat">
                                                    {course.title}
                                                </h3>
                                                <p className="text-sm text-gray-500 mt-1 line-clamp-2 font-lato">
                                                    {course.description
                                                        ? course.description.substring(0, 80) +
                                                          (course.description.length > 80 ? '...' : '')
                                                        : 'No description available'}
                                                </p>
                                            </div>
                                        </div>
                                    </td>

                                    {/* Thumbnail */}
                                    <td className="px-6 py-4">
                                        {course.thumbnail_path ? (
                                            <img
                                                className="w-16 h-12 rounded-lg object-cover shadow-sm border border-gray-200"
                                                src={`/storage/${course.thumbnail_path}`}
                                                alt={course.title}
                                            />
                                        ) : (
                                            <div className="w-16 h-12 bg-gray-100 rounded-lg flex items-center justify-center">
                                                <i className="fas fa-image w-5 h-5 text-gray-400"></i>
                                            </div>
                                        )}
                                    </td>

                                    {/* Price */}
                                    <td className="px-6 py-4">
                                        {course.price === 0 ? (
                                            <span className="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800 font-montserrat">
                                                Free
                                            </span>
                                        ) : (
                                            <span className="font-semibold text-gray-900 font-lato">
                                                ₦{course.price.toLocaleString()}
                                            </span>
                                        )}
                                    </td>

                                    {/* Instructor */}
                                    <td className="px-6 py-4">
                                        <div className="flex items-center gap-2">
                                            <div className="w-8 h-8 bg-primary-100 rounded-full flex items-center justify-center">
                                                <span className="text-xs font-semibold text-primary-700 font-montserrat">
                                                    {course.instructor.name.charAt(0).toUpperCase()}
                                                </span>
                                            </div>
                                            <span className="font-medium text-gray-700 font-lato">
                                                {course.instructor.name.substring(0, 15)}
                                                {course.instructor.name.length > 15 ? '...' : ''}
                                            </span>
                                        </div>
                                    </td>

                                    {/* Category */}
                                    <td className="px-6 py-4">
                                        <span className="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-800 font-montserrat">
                                            {course.category.name.charAt(0).toUpperCase() + course.category.name.slice(1)}
                                        </span>
                                    </td>

                                    {/* Status */}
                                    <td className="px-6 py-4">{getStatusBadge(course.status)}</td>

                                    {/* Actions */}
                                    <td className="px-6 py-4">
                                        <div className="flex items-center gap-2">
                                            <Link
                                                href={route('admin.courses.edit', course.slug)}
                                                title="Edit Course"
                                                className="inline-flex items-center justify-center w-8 h-8 text-primary-600 hover:text-primary-700 hover:bg-primary-50 rounded-lg transition-colors duration-200"
                                            >
                                                <i className="fas fa-edit w-4 h-4"></i>
                                            </Link>
                                            <Link
                                                href={route('admin.courses.builder', course.slug)}
                                                title="Course Builder"
                                                className="inline-flex items-center justify-center w-8 h-8 text-blue-600 hover:text-blue-700 hover:bg-blue-50 rounded-lg transition-colors duration-200"
                                            >
                                                <i className="fas fa-cog w-4 h-4"></i>
                                            </Link>
                                            {course.status === 'draft' && (
                                                <button
                                                    onClick={() => openApproveModal(course)}
                                                    disabled={isApproving || isRejecting}
                                                    title="Publish Course"
                                                    className="inline-flex items-center justify-center w-8 h-8 text-green-600 hover:text-green-700 hover:bg-green-50 rounded-lg transition-colors duration-200 disabled:opacity-50"
                                                >
                                                    <i className="fas fa-rocket w-4 h-4"></i>
                                                </button>
                                            )}
                                            {(course.status === 'pending' || course.status === 'under_review') && (
                                                <>
                                                    <button
                                                        onClick={() => openApproveModal(course)}
                                                        disabled={isApproving || isRejecting}
                                                        title="Approve & Publish Course"
                                                        className="inline-flex items-center justify-center w-8 h-8 text-green-600 hover:text-green-700 hover:bg-green-50 rounded-lg transition-colors duration-200 disabled:opacity-50"
                                                    >
                                                        <i className="fas fa-check w-4 h-4"></i>
                                                    </button>
                                                    <button
                                                        onClick={() => openRejectModal(course)}
                                                        disabled={isApproving || isRejecting}
                                                        title="Reject Course"
                                                        className="inline-flex items-center justify-center w-8 h-8 text-orange-600 hover:text-orange-700 hover:bg-orange-50 rounded-lg transition-colors duration-200 disabled:opacity-50"
                                                    >
                                                        <i className="fas fa-times w-4 h-4"></i>
                                                    </button>
                                                </>
                                            )}
                                            <button
                                                onClick={() => openDeleteModal(course)}
                                                title="Delete Course"
                                                className="inline-flex items-center justify-center w-8 h-8 text-red-600 hover:text-red-700 hover:bg-red-50 rounded-lg transition-colors duration-200"
                                            >
                                                <i className="fas fa-trash w-4 h-4"></i>
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                            ))
                        ) : (
                            <tr>
                                <td colSpan={7} className="px-6 py-16 text-center">
                                    <div className="flex flex-col items-center justify-center space-y-4 text-gray-400">
                                        <i className="fas fa-book-open w-12 h-12 text-gray-300"></i>
                                        <h3 className="text-lg font-medium text-gray-900 font-montserrat">
                                            No courses found
                                        </h3>
                                        <p className="max-w-md text-center font-lato">
                                            Get started by creating your first course.
                                        </p>
                                        <Link
                                            href={route('admin.courses.create')}
                                            className="mt-4 inline-flex items-center px-4 py-2 bg-primary text-white text-sm font-medium rounded-lg hover:bg-primary-600 focus:ring-4 focus:ring-primary-300 transition shadow-sm font-montserrat"
                                        >
                                            <i className="fas fa-plus w-4 h-4 mr-2"></i>
                                            Create Course
                                        </Link>
                                    </div>
                                </td>
                            </tr>
                        )}
                    </tbody>
                </table>
            </div>

            {/* Delete Confirmation Modal */}
            <ConfirmModal
                show={showDeleteModal && !!selectedCourse}
                onClose={() => {
                    setShowDeleteModal(false);
                    setSelectedCourse(null);
                }}
                onConfirm={handleDelete}
                title={`Delete Course: ${selectedCourse?.title || ''}`}
                message={
                    <div className="space-y-3 text-left">
                        <p>Are you sure you want to permanently delete this course?</p>
                        <div className="bg-amber-50 border border-amber-200 rounded-lg p-3">
                            <p className="text-sm text-amber-800 font-semibold mb-2">
                                <i className="fas fa-exclamation-triangle mr-2"></i>
                                This will permanently delete:
                            </p>
                            <ul className="text-sm text-amber-700 space-y-1 ml-6 list-disc">
                                <li>All course modules and lessons</li>
                                <li>All video content from Vimeo (including preview videos)</li>
                                <li>All course files (PDFs, thumbnails, etc.)</li>
                                <li>Student enrollment records</li>
                                <li>Course requirements and outcomes</li>
                            </ul>
                        </div>
                        <p className="text-sm font-semibold text-red-600">
                            This action cannot be undone!
                        </p>
                    </div>
                }
                confirmText="Delete Course"
                cancelText="Cancel"
                confirmButtonClass="bg-red-600 hover:bg-red-700 text-white"
                icon={
                    <div className="mx-auto flex items-center justify-center h-12 w-12 rounded-full bg-red-100">
                        <i className="fas fa-trash text-red-600 text-xl"></i>
                    </div>
                }
                isProcessing={isDeleting}
                processingText="Deleting Course..."
            />

            {/* Approve Modal */}
            <ConfirmModal
                show={showApproveModal && selectedCourse !== null}
                onClose={() => {
                    setShowApproveModal(false);
                    setSelectedCourse(null);
                }}
                onConfirm={handleApprove}
                title="Approve & Publish Course"
                message={
                    <>
                        <p>
                            Approve <span className="font-semibold text-gray-900">"{selectedCourse?.title}"</span> and publish it for students to enroll?
                        </p>
                        <p className="mt-2 text-green-600">
                            Once approved, this course will be visible to all students and they can enroll.
                        </p>
                    </>
                }
                confirmText="Approve & Publish"
                confirmButtonClass="bg-green-600 hover:bg-green-700 text-white"
                icon={
                    <div className="w-12 h-12 bg-green-100 rounded-full flex items-center justify-center">
                        <i className="fas fa-check-circle w-6 h-6 text-green-600"></i>
                    </div>
                }
                isProcessing={isApproving}
                processingText="Approving..."
            />

            {/* Reject Modal */}
            {showRejectModal && selectedCourse && (
                <div className="fixed inset-0 z-50 flex items-center justify-center bg-black/30 backdrop-blur-sm">
                    <div className="relative bg-white rounded-xl shadow-xl p-6 w-full max-w-md mx-4 border border-gray-200">
                        <div className="flex items-center justify-between pb-4 mb-4 border-b border-gray-200">
                            <h3 className="text-lg font-semibold text-gray-900 font-montserrat">
                                Reject Course
                            </h3>
                            <button
                                type="button"
                                onClick={() => setShowRejectModal(false)}
                                className="text-gray-400 bg-transparent hover:bg-gray-200 hover:text-gray-900 rounded-lg text-sm w-8 h-8 inline-flex justify-center items-center transition-colors"
                            >
                                <i className="fas fa-times w-4 h-4"></i>
                            </button>
                        </div>
                        <div className="space-y-4">
                            <div className="flex flex-col text-center">
                                <div className="w-12 h-12 bg-orange-100 rounded-full flex items-center justify-center mb-4 mx-auto">
                                    <i className="fas fa-times-circle w-6 h-6 text-orange-600"></i>
                                </div>
                                <h3 className="text-lg font-medium text-gray-900 font-montserrat mb-2">
                                    Reject "{selectedCourse.title}"
                                </h3>
                                <p className="text-sm text-gray-500 font-lato mb-4">
                                    Provide a reason for rejection (optional). The instructor will be notified.
                                </p>
                            </div>
                            <div>
                                <label htmlFor="rejection_reason" className="block text-sm font-medium text-gray-700 mb-2 font-lato">
                                    Rejection Reason
                                </label>
                                <textarea
                                    id="rejection_reason"
                                    rows={4}
                                    value={rejectionReason}
                                    onChange={(e) => setRejectionReason(e.target.value)}
                                    className="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-transparent font-lato"
                                    placeholder="E.g., Content quality needs improvement, missing learning objectives, etc."
                                />
                            </div>
                        </div>
                        <div className="flex items-center justify-end pt-4 mt-4 border-t border-gray-200 gap-3">
                            <button
                                onClick={() => setShowRejectModal(false)}
                                type="button"
                                className="py-2.5 px-5 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-lg hover:bg-gray-100 transition-colors font-lato"
                            >
                                Cancel
                            </button>
                            <button
                                onClick={handleReject}
                                disabled={isRejecting}
                                className="px-5 py-2.5 text-sm font-medium text-white bg-orange-600 hover:bg-orange-700 rounded-lg transition-colors inline-flex items-center font-montserrat disabled:opacity-50 disabled:cursor-not-allowed"
                            >
                                {isRejecting ? (
                                    <>
                                        <i className="fas fa-spinner fa-spin w-4 h-4 mr-2"></i>
                                        Rejecting...
                                    </>
                                ) : (
                                    <>
                                        <i className="fas fa-times w-4 h-4 mr-2"></i>
                                        Reject Course
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
