import { Head, Link, router, usePage } from '@inertiajs/react';
import DashboardLayout from '@/Layouts/DashboardLayout';
import { useState } from 'react';

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
    title: string;
    description?: string;
    price: number;
    level: string;
    thumbnail_path?: string;
    status: {
        value: 'draft' | 'published' | 'archived';
    };
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
    const [selectedCourse, setSelectedCourse] = useState<Course | null>(null);
    const [isDeleting, setIsDeleting] = useState(false);

    const openDeleteModal = (course: Course) => {
        setSelectedCourse(course);
        setShowDeleteModal(true);
    };

    const handleDelete = () => {
        if (!selectedCourse) return;

        setIsDeleting(true);
        router.delete(route('admin.courses.destroy', selectedCourse.id), {
            preserveScroll: true,
            onFinish: () => {
                setIsDeleting(false);
                setShowDeleteModal(false);
                setSelectedCourse(null);
            },
        });
    };

    const getStatusBadge = (status: string) => {
        const statusColors: Record<string, string> = {
            draft: 'bg-yellow-100 text-yellow-800',
            published: 'bg-green-100 text-green-800',
            archived: 'bg-gray-100 text-gray-800',
        };
        const colorClass = statusColors[status.toLowerCase()] || 'bg-gray-100 text-gray-800';

        return (
            <span className={`inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium font-montserrat ${colorClass}`}>
                {status.charAt(0).toUpperCase() + status.slice(1)}
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
                                    <td className="px-6 py-4">{getStatusBadge(course.status.value)}</td>

                                    {/* Actions */}
                                    <td className="px-6 py-4">
                                        <div className="flex items-center gap-2">
                                            <Link
                                                href={route('admin.courses.edit', course.id)}
                                                title="Edit Course"
                                                className="inline-flex items-center justify-center w-8 h-8 text-primary-600 hover:text-primary-700 hover:bg-primary-50 rounded-lg transition-colors duration-200"
                                            >
                                                <i className="fas fa-edit w-4 h-4"></i>
                                            </Link>
                                            <Link
                                                href={route('admin.courses.builder', course.id)}
                                                title="Course Builder"
                                                className="inline-flex items-center justify-center w-8 h-8 text-blue-600 hover:text-blue-700 hover:bg-blue-50 rounded-lg transition-colors duration-200"
                                            >
                                                <i className="fas fa-cog w-4 h-4"></i>
                                            </Link>
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
                                        Delete Course
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
