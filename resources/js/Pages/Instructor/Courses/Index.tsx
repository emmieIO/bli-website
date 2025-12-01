import { Head, Link, router } from '@inertiajs/react';
import DashboardLayout from '@/Layouts/DashboardLayout';
import { PageProps } from '@/types';
import { useState } from 'react';
import ConfirmModal from '@/Components/ConfirmModal';

// Define the structure of the props passed from the controller
interface Course {
    id: number;
    slug: string;
    title: string;
    status: string;
    students: any[];
    category: { name: string };
    created_at: string;
}

interface InstructorStats {
    total_courses: number;
    total_students: number;
    total_earnings: number;
    average_rating: number;
}

interface InstructorCoursesPageProps extends PageProps {
    courses: {
        data: Course[];
        links: any;
        meta: any;
    };
    instructorStats: InstructorStats;
    sideLinks: any[];
}

export default function InstructorCoursesIndex({ auth, courses, instructorStats, sideLinks }: InstructorCoursesPageProps) {
    const coursesList = courses.data || [];
    const [showDeleteModal, setShowDeleteModal] = useState(false);
    const [showSubmitModal, setShowSubmitModal] = useState(false);
    const [selectedCourse, setSelectedCourse] = useState<Course | null>(null);
    const [isDeleting, setIsDeleting] = useState(false);
    const [isSubmitting, setIsSubmitting] = useState(false);

    const openDeleteModal = (course: Course) => {
        setSelectedCourse(course);
        setShowDeleteModal(true);
    };

    const handleDelete = () => {
        if (!selectedCourse) return;

        setIsDeleting(true);
        router.delete(route('instructor.courses.destroy', selectedCourse.slug), {
            preserveScroll: true,
            onFinish: () => {
                setIsDeleting(false);
                setShowDeleteModal(false);
                setSelectedCourse(null);
            },
        });
    };

    const openSubmitModal = (course: Course) => {
        setSelectedCourse(course);
        setShowSubmitModal(true);
    };

    const handleSubmitForReview = () => {
        if (!selectedCourse) return;

        setIsSubmitting(true);
        router.post(route('instructor.courses.submit-for-review', selectedCourse.slug), {}, {
            preserveScroll: true,
            onFinish: () => {
                setIsSubmitting(false);
                setShowSubmitModal(false);
                setSelectedCourse(null);
            },
        });
    };

    const getStatusBadge = (status: string) => {
        const statusMap: { [key: string]: { label: string; className: string } } = {
            draft: { label: 'Draft', className: 'bg-gray-100 text-gray-800' },
            pending: { label: 'Pending Review', className: 'bg-yellow-100 text-yellow-800' },
            under_review: { label: 'Under Review', className: 'bg-blue-100 text-blue-800' },
            approved: { label: 'Approved', className: 'bg-green-100 text-green-800' },
            rejected: { label: 'Rejected', className: 'bg-red-100 text-red-800' },
        };
        return statusMap[status] || { label: 'Unknown', className: 'bg-gray-100 text-gray-800' };
    };

    const formatDate = (dateString: string) => {
        return new Date(dateString).toLocaleDateString('en-US', {
            year: 'numeric',
            month: 'short',
            day: 'numeric',
        });
    };

    return (
        <DashboardLayout user={auth.user} sideLinks={sideLinks}>
            <Head title="Instructor Dashboard" />

            {/* Page Header */}
            <div className="flex flex-col md:flex-row justify-between items-start md:items-center gap-4 mb-6">
                <div>
                    <h1 className="text-2xl font-bold text-primary font-montserrat">My Dashboard</h1>
                    <p className="text-sm text-gray-500 font-lato">Welcome back, {auth.user.name}!</p>
                </div>
                                    <Link
                                        href={route('instructor.courses.create')}
                                        className="inline-flex items-center gap-2 px-4 py-2 bg-primary text-white rounded-lg text-sm font-medium hover:bg-primary-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary"
                                    >
                                        <i className="fas fa-plus"></i>
                                        <span>Create New Course</span>
                                    </Link>
                                </div>
                
                                {/* Stats Cards */}
                                <div className="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
                                    <StatCard icon="fas fa-book" title="Total Courses" value={instructorStats.total_courses} />
                                    <StatCard icon="fas fa-users" title="Total Students" value={instructorStats.total_students} />
                                    <StatCard icon="fas fa-dollar-sign" title="Total Earnings" value={`$${instructorStats.total_earnings.toFixed(2)}`} />
                                    <StatCard icon="fas fa-star" title="Average Rating" value={instructorStats.average_rating.toFixed(1)} />
                                </div>
                
                                {/* Courses Table */}
                                <div className="bg-white rounded-lg shadow border border-primary-100 overflow-x-auto">
                                    <h2 className="text-lg font-bold text-primary p-4 font-montserrat">My Courses</h2>
                                    <table className="min-w-full divide-y divide-primary-100">
                                        <thead className="bg-primary-50">
                                            <tr>
                                                <th className="px-6 py-3 text-left text-xs font-semibold text-primary uppercase tracking-wider font-montserrat">Course</th>
                                                <th className="px-6 py-3 text-left text-xs font-semibold text-primary uppercase tracking-wider font-montserrat">Students</th>
                                                <th className="px-6 py-3 text-left text-xs font-semibold text-primary uppercase tracking-wider font-montserrat">Status</th>
                                                <th className="px-6 py-3 text-left text-xs font-semibold text-primary uppercase tracking-wider font-montserrat">Date Created</th>
                                                <th className="px-6 py-3 text-right text-xs font-semibold text-primary uppercase tracking-wider font-montserrat">Actions</th>
                                            </tr>
                                        </thead>
                                        <tbody className="bg-white divide-y divide-primary-100">
                                            {coursesList.length > 0 ? (
                                                coursesList.map((course) => {
                                                    const badge = getStatusBadge(course.status);
                                                    return (
                                                        <tr key={course.id} className="hover:bg-primary-50 transition-colors">
                                                            <td className="px-6 py-4">
                                                                <div className="font-bold text-sm text-primary font-montserrat">{course.title}</div>
                                                                <div className="text-sm text-gray-500 font-lato">{course.category.name}</div>
                                                            </td>
                                                            <td className="px-6 py-4">
                                                                <div className="text-sm text-gray-700 font-lato">{course.students.length}</div>
                                                            </td>
                                                            <td className="px-6 py-4 whitespace-nowrap">
                                                                <span className={`px-2 py-1 inline-flex text-xs font-semibold rounded-full ${badge.className} font-montserrat`}>
                                                                    {badge.label}
                                                                </span>
                                                            </td>
                                                            <td className="px-6 py-4 whitespace-nowrap text-sm text-gray-700 font-lato">
                                                                {formatDate(course.created_at)}
                                                            </td>
                                                            <td className="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                                                <div className="flex items-center justify-end gap-3">
                                                                    <Link href={route('instructor.courses.builder', course.slug)} className="text-primary hover:text-primary-700" title="Edit Content">
                                                                        <i className="fas fa-layer-group"></i>
                                                                    </Link>                                                {course.status === 'draft' && (
                                                    <>
                                                        <Link href={route('instructor.courses.edit', course.slug)} className="text-blue-600 hover:text-blue-800" title="Edit Settings">
                                                            <i className="fas fa-cog"></i>
                                                        </Link>
                                                        <button onClick={() => openSubmitModal(course)} className="text-green-600 hover:text-green-800" title="Submit for Review">
                                                            <i className="fas fa-paper-plane"></i>
                                                        </button>
                                                        <button onClick={() => openDeleteModal(course)} className="text-red-600 hover:text-red-800" title="Delete">
                                                            <i className="fas fa-trash"></i>
                                                        </button>
                                                    </>
                                                )}
                                                {course.status === 'rejected' && (
                                                    <Link href={route('instructor.courses.edit', course.slug)} className="text-blue-600 hover:text-blue-800" title="Edit & Resubmit">
                                                        <i className="fas fa-cog"></i>
                                                    </Link>
                                                )}
                                            </div>
                                        </td>
                                    </tr>
                                );
                            })
                        ) : (
                            <tr>
                                <td colSpan={5} className="px-6 py-12 text-center text-gray-500">
                                    <div className="flex flex-col items-center">
                                        <i className="fas fa-book-open w-12 h-12 text-gray-300 mb-4"></i>
                                        <p className="font-lato">You haven't created any courses yet.</p>
                                        <Link href={route('instructor.courses.create')} className="mt-4 text-sm text-primary hover:underline">
                                            Create your first course
                                        </Link>
                                    </div>
                                </td>
                            </tr>
                        )}
                    </tbody>
                </table>
            </div>

            {/* Delete Confirmation Modal */}
            {showDeleteModal && selectedCourse && (
                <div className="fixed inset-0 z-50 flex items-center justify-center bg-black/30 backdrop-blur-sm">
                    <div className="relative bg-white rounded-xl shadow-xl p-6 w-full max-w-md mx-4 border border-gray-200">
                        {/* Modal header */}
                        <div className="flex items-center justify-between pb-4 mb-4 border-b border-gray-200">
                            <h3 className="text-lg font-semibold text-gray-900 font-montserrat">
                                Confirm Course Deletion
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
                                    Delete Course
                                </h3>
                                <div className="text-sm text-gray-500 font-lato">
                                    <p>
                                        Are you sure you want to delete{' '}
                                        <span className="font-semibold text-gray-900">{selectedCourse.title}</span>?
                                    </p>
                                    <p className="mt-1">This action cannot be undone and will delete all modules, lessons, and progress.</p>
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
                                        Delete Course
                                    </>
                                )}
                            </button>
                        </div>
                    </div>
                </div>
            )}

            {/* Submit for Review Modal */}
            <ConfirmModal
                show={showSubmitModal && selectedCourse !== null}
                onClose={() => {
                    setShowSubmitModal(false);
                    setSelectedCourse(null);
                }}
                onConfirm={handleSubmitForReview}
                title="Submit Course for Review"
                message={
                    <>
                        <p>
                            Submit <span className="font-semibold text-gray-900">"{selectedCourse?.title}"</span> for admin review?
                        </p>
                        <p className="mt-2 text-yellow-600">
                            You won't be able to edit this course until it has been reviewed by an admin.
                        </p>
                    </>
                }
                confirmText="Submit for Review"
                confirmButtonClass="bg-green-600 hover:bg-green-700 text-white"
                icon={
                    <div className="w-12 h-12 bg-green-100 rounded-full flex items-center justify-center">
                        <i className="fas fa-paper-plane w-6 h-6 text-green-600"></i>
                    </div>
                }
                isProcessing={isSubmitting}
                processingText="Submitting..."
            />
        </DashboardLayout>
    );
}

// StatCard sub-component
function StatCard({ icon, title, value }: { icon: string; title: string; value: string | number }) {
    return (
        <div className="bg-white p-5 rounded-lg shadow-sm border border-gray-200 flex items-center gap-4">
            <div className="bg-primary-100 text-primary rounded-full w-12 h-12 flex items-center justify-center">
                <i className={`${icon} text-xl`}></i>
            </div>
            <div>
                <p className="text-sm text-gray-500 font-lato">{title}</p>
                <p className="text-2xl font-bold text-primary font-montserrat">{value}</p>
            </div>
        </div>
    );
}
