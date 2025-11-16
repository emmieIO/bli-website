import { Head, Link } from '@inertiajs/react';
import DashboardLayout from '@/Layouts/DashboardLayout';
import { PageProps } from '@/types';

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

    const getStatusBadge = (status: string) => {
        const statusMap: { [key: string]: { label: string; className: string } } = {
            draft: { label: 'Draft', className: 'bg-gray-100 text-gray-800' },
            published: { label: 'Published', className: 'bg-green-100 text-green-800' },
            pending: { label: 'In Review', className: 'bg-yellow-100 text-yellow-800' },
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
                    className="inline-flex items-center gap-2 px-4 py-2 bg-primary text-white rounded-lg text-sm font-medium hover:bg-primary-dark focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary"
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
                                                <Link href={route('instructor.courses.builder', course.slug)} className="text-primary hover:text-primary-dark" title="Edit Content">
                                                    <i className="fas fa-layer-group"></i>
                                                </Link>
                                                <Link href={route('instructor.courses.edit', course.slug)} className="text-blue-600 hover:text-blue-800" title="Edit Settings">
                                                    <i className="fas fa-cog"></i>
                                                </Link>
                                                <button onClick={() => alert('Delete not implemented')} className="text-red-600 hover:text-red-800" title="Delete">
                                                    <i className="fas fa-trash"></i>
                                                </button>
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
