import { Head, Link, usePage, router } from '@inertiajs/react';
import DashboardLayout from '@/Layouts/DashboardLayout';

interface Course {
    id: number;
    title: string;
    slug: string;
    thumbnail_path: string | null;
    instructor: {
        name: string;
    };
    category: {
        name: string;
    };
    total_lessons: number;
    completed_lessons: number;
    completion_percentage: number;
    status: 'not_started' | 'in_progress' | 'completed';
    next_lesson: {
        id: number;
        title: string;
        slug: string;
    } | null;
}

interface AdminStats {
    totalUsers: number;
    totalUsersBadge: string;
    totalUsersBadgeColor: string;
    activeUsers: number;
    activeUsersBadge: string;
    totalCourses: number;
    totalCoursesBadge: string;
    eventsScheduled: number;
    eventsScheduledBadge: string;
    eventsScheduledBadgeColor: string;
    totalAttendees: number;
    totalAttendeesBadge: string;
    totalAttendeesBadgeColor: string;
}

interface InstructorStats {
    coursesTaught: number;
    coursesTaughtDescription: string;
    activeStudents: number;
    activeStudentsDescription: string;
    feedbackReceived: number;
    feedbackReceivedDescription: string;
}

interface DashboardProps {
    stats?: {
        totalCourses?: number;
        inProgress?: number;
        completed?: number;
        hoursSpent?: number;
        totalLessons?: number;
        completedLessons?: number;
    };
    courses?: Course[];
    adminStats?: AdminStats | null;
    instructorStats?: InstructorStats | null;
}

export default function Dashboard({ stats, courses, adminStats, instructorStats }: DashboardProps) {
    const { auth, sideLinks } = usePage().props as any;
    const user = auth?.user;
    const isAdmin = user?.roles?.some((role: string) => ['admin', 'super-admin'].includes(role));
    const isInstructor = user?.roles?.includes('instructor');

    return (
        <DashboardLayout sideLinks={sideLinks}>
            <Head title="Dashboard - Beacon Leadership Institute" />

            <section className="min-h-screen bg-linear-to-br from-gray-50 to-primary-50/30">
                <div className="space-y-8">
                    {/* Header */}
                    <div className="mb-8">
                        <h1 className="text-3xl font-bold font-montserrat" style={{ color: '#002147' }}>
                            Dashboard
                        </h1>
                        {isAdmin ? (
                            <p className="text-gray-600 mt-2 font-lato leading-relaxed">
                                As an admin, you can manage users, courses, and view platform analytics from this dashboard.
                            </p>
                        ) : (
                            <p className="text-gray-600 mt-2 font-lato leading-relaxed">
                                Welcome to your dashboard. Here you can track your course progress and continue your learning journey.
                            </p>
                        )}
                    </div>

                    {/* Stats Cards */}
                    {isAdmin ? (
                        <AdminDashboard adminStats={adminStats} />
                    ) : isInstructor ? (
                        <InstructorDashboard instructorStats={instructorStats} />
                    ) : (
                        <StudentDashboard stats={stats} courses={courses} />
                    )}
                </div>
            </section>
        </DashboardLayout>
    );
}

function AdminDashboard({ adminStats }: { adminStats?: AdminStats | null }) {
    if (!adminStats) {
        return (
            <div className="text-center py-12">
                <p className="text-gray-600 font-lato">Loading admin statistics...</p>
            </div>
        );
    }

    const statsArray = [
        {
            title: 'Total Users',
            value: adminStats.totalUsers.toLocaleString(),
            icon: 'fa-users',
            color: '#002147',
            badge: adminStats.totalUsersBadge,
            badgeColor: adminStats.totalUsersBadgeColor
        },
        {
            title: 'Active Users',
            value: adminStats.activeUsers.toLocaleString(),
            icon: 'fa-chart-line',
            color: '#00a651',
            badge: adminStats.activeUsersBadge
        },
        {
            title: 'Total Courses',
            value: adminStats.totalCourses.toLocaleString(),
            icon: 'fa-book-open',
            color: '#002147',
            badge: adminStats.totalCoursesBadge
        },
        {
            title: 'Events Scheduled',
            value: adminStats.eventsScheduled.toLocaleString(),
            icon: 'fa-calendar',
            color: '#002147',
            badge: adminStats.eventsScheduledBadge,
            badgeColor: adminStats.eventsScheduledBadgeColor
        },
        {
            title: 'Total Attendees',
            value: adminStats.totalAttendees.toLocaleString(),
            icon: 'fa-user-check',
            color: '#00a651',
            badge: adminStats.totalAttendeesBadge,
            badgeColor: adminStats.totalAttendeesBadgeColor
        },
    ];

    return (
        <div className="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            {statsArray.map((stat, index) => (
                <StatCard key={index} {...stat} />
            ))}
        </div>
    );
}

function InstructorDashboard({ instructorStats }: { instructorStats?: InstructorStats | null }) {
    if (!instructorStats) {
        return (
            <div className="text-center py-12">
                <p className="text-gray-600 font-lato">Loading instructor statistics...</p>
            </div>
        );
    }

    const statsArray = [
        {
            title: 'Courses Taught',
            value: instructorStats.coursesTaught.toString(),
            icon: 'fa-book-open',
            color: '#002147',
            description: instructorStats.coursesTaughtDescription
        },
        {
            title: 'Active Students',
            value: instructorStats.activeStudents.toLocaleString(),
            icon: 'fa-users',
            color: '#00a651',
            description: instructorStats.activeStudentsDescription
        },

        {
            title: 'Feedback Received',
            value: instructorStats.feedbackReceived.toString(),
            icon: 'fa-comment',
            color: '#00a651',
            description: instructorStats.feedbackReceivedDescription
        },
    ];

    return (
        <div className="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            {statsArray.map((stat, index) => (
                <StatCard key={index} {...stat} />
            ))}
        </div>
    );
}

function StudentDashboard({ stats, courses }: DashboardProps) {
    const totalCourses = stats?.totalCourses || 0;
    const inProgress = stats?.inProgress || 0;
    const completed = stats?.completed || 0;
    const hoursSpent = stats?.hoursSpent || 0;
    const totalLessons = stats?.totalLessons || 0;
    const completedLessons = stats?.completedLessons || 0;

    const completionRate = totalLessons > 0 ? Math.round((completedLessons / totalLessons) * 100) : 0;
    const notStarted = totalCourses - inProgress - completed;

    const studentStats = [
        {
            title: 'Enrolled Courses',
            value: totalCourses.toString(),
            icon: 'fa-book-open',
            color: '#002147',
            description: totalCourses > 0 ? `${notStarted} not started` : 'Browse courses to get started'
        },
        {
            title: 'In Progress',
            value: inProgress.toString(),
            icon: 'fa-clock',
            color: '#00a651',
            description: inProgress > 0 ? 'Keep up the momentum!' : 'Start a course today'
        },
        {
            title: 'Completed',
            value: completed.toString(),
            icon: 'fa-check-circle',
            color: '#00a651',
            description: completed > 0 ? `${completionRate}% overall completion` : 'Complete your first course'
        },
        {
            title: 'Hours Spent',
            value: hoursSpent.toFixed(1),
            icon: 'fa-stopwatch',
            color: '#002147',
            description: hoursSpent > 0 ? 'Time invested in learning' : 'Start learning today'
        },
    ];

    return (
        <>
            <div className="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
                {studentStats.map((stat, index) => (
                    <StatCard key={index} {...stat} />
                ))}
            </div>

            {/* Continue Learning Section */}
            {courses && courses.length > 0 ? (
                <div className="mt-8 bg-white rounded-2xl shadow-lg border border-primary-100 p-8">
                    <div className="flex items-center justify-between mb-6">
                        <div>
                            <h2 className="text-2xl font-bold font-montserrat" style={{ color: '#002147' }}>
                                Continue Learning
                            </h2>
                            <p className="text-gray-600 mt-1 font-lato">
                                Pick up where you left off
                            </p>
                        </div>
                    </div>

                    <div className="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                        {courses.slice(0, 6).map((course) => (
                            <CourseCard key={course.id} course={course} />
                        ))}
                    </div>

                    {courses.length > 6 && (
                        <div className="mt-6 text-center">
                            <Link
                                href={route('courses.index')}
                                className="inline-flex items-center gap-2 text-sm font-medium font-lato"
                                style={{ color: '#002147' }}
                            >
                                View All Courses
                                <i className="fas fa-arrow-right"></i>
                            </Link>
                        </div>
                    )}
                </div>
            ) : null}

            {/* Progress Summary */}
            {totalCourses > 0 && (
                <div className="mt-8 bg-white rounded-2xl shadow-lg border border-primary-100 p-8">
                    <div className="flex items-center justify-between mb-6">
                        <div>
                            <h2 className="text-2xl font-bold font-montserrat" style={{ color: '#002147' }}>
                                Learning Progress
                            </h2>
                            <p className="text-gray-600 mt-1 font-lato">
                                Track your course completion and achievements
                            </p>
                        </div>
                        <Link
                            href="/courses"
                            className="px-6 py-2.5 text-white rounded-lg font-medium font-lato transition-all duration-300 hover:shadow-md"
                            style={{ backgroundColor: '#00a651' }}
                        >
                            <i className="fas fa-plus mr-2"></i>
                            Browse Courses
                        </Link>
                    </div>

                    <div className="grid grid-cols-1 md:grid-cols-3 gap-6">
                        {/* Overall Progress */}
                        <div className="bg-linear-to-br from-primary-50 to-white rounded-xl p-6 border border-primary-100">
                            <div className="flex items-center justify-between mb-4">
                                <h3 className="text-lg font-bold font-montserrat" style={{ color: '#002147' }}>
                                    Overall Progress
                                </h3>
                                <span className="text-3xl font-bold font-montserrat" style={{ color: '#00a651' }}>
                                    {completionRate}%
                                </span>
                            </div>
                            <div className="w-full bg-gray-200 rounded-full h-3 overflow-hidden">
                                <div
                                    className="h-3 rounded-full transition-all duration-500"
                                    style={{
                                        width: `${completionRate}%`,
                                        backgroundColor: '#00a651'
                                    }}
                                ></div>
                            </div>
                            <p className="text-sm text-gray-600 mt-3 font-lato">
                                {completedLessons} of {totalLessons} lessons completed
                            </p>
                        </div>

                        {/* Course Status Breakdown */}
                        <div className="bg-linear-to-br from-green-50 to-white rounded-xl p-6 border border-green-100">
                            <h3 className="text-lg font-bold font-montserrat mb-4" style={{ color: '#002147' }}>
                                Course Status
                            </h3>
                            <div className="space-y-3">
                                <div className="flex items-center justify-between">
                                    <span className="text-sm font-lato text-gray-600">Completed</span>
                                    <span className="font-bold font-montserrat" style={{ color: '#00a651' }}>
                                        {completed}
                                    </span>
                                </div>
                                <div className="flex items-center justify-between">
                                    <span className="text-sm font-lato text-gray-600">In Progress</span>
                                    <span className="font-bold font-montserrat text-yellow-600">
                                        {inProgress}
                                    </span>
                                </div>
                                <div className="flex items-center justify-between">
                                    <span className="text-sm font-lato text-gray-600">Not Started</span>
                                    <span className="font-bold font-montserrat text-gray-500">
                                        {notStarted}
                                    </span>
                                </div>
                            </div>
                        </div>

                        {/* Learning Time */}
                        <div className="bg-linear-to-br from-blue-50 to-white rounded-xl p-6 border border-blue-100">
                            <h3 className="text-lg font-bold font-montserrat mb-4" style={{ color: '#002147' }}>
                                Learning Time
                            </h3>
                            <div className="flex items-center justify-center py-4">
                                <div className="text-center">
                                    <div className="text-4xl font-bold font-montserrat" style={{ color: '#002147' }}>
                                        {hoursSpent.toFixed(1)}
                                    </div>
                                    <div className="text-sm text-gray-600 font-lato mt-1">
                                        hours invested
                                    </div>
                                </div>
                            </div>
                            <p className="text-xs text-gray-500 text-center mt-2 font-lato">
                                Keep learning to unlock more achievements
                            </p>
                        </div>
                    </div>
                </div>
            )}

            {/* Empty State */}
            {totalCourses === 0 && (
                <div className="mt-8 bg-white rounded-2xl shadow-lg border border-primary-100 p-12 text-center">
                    <div className="max-w-md mx-auto">
                        <div className="w-20 h-20 rounded-full mx-auto mb-6 flex items-center justify-center" style={{ backgroundColor: '#00265115' }}>
                            <i className="fas fa-graduation-cap text-4xl" style={{ color: '#002147' }}></i>
                        </div>
                        <h3 className="text-2xl font-bold font-montserrat mb-3" style={{ color: '#002147' }}>
                            Start Your Learning Journey
                        </h3>
                        <p className="text-gray-600 mb-6 font-lato">
                            You haven't enrolled in any courses yet. Browse our catalog and start learning today!
                        </p>
                        <Link
                            href="/courses"
                            className="inline-flex items-center gap-2 px-8 py-3 text-white rounded-lg font-medium font-lato transition-all duration-300 hover:shadow-lg"
                            style={{ backgroundColor: '#00a651' }}
                        >
                            <i className="fas fa-search"></i>
                            Browse Courses
                        </Link>
                    </div>
                </div>
            )}
        </>
    );
}

interface StatCardProps {
    title: string;
    value: string;
    icon: string;
    color: string;
    badge?: string;
    badgeColor?: string;
    description?: string;
}

function StatCard({ title, value, icon, color, badge, badgeColor, description }: StatCardProps) {
    return (
        <div className="bg-white rounded-2xl shadow-lg border border-primary-100 p-6 transition-all duration-300 hover:shadow-xl hover:border-primary-200 group">
            <div className="flex items-center justify-between">
                <div>
                    <p className="text-sm font-medium text-gray-500 font-lato">{title}</p>
                    <h3 className="text-2xl font-bold mt-1 font-montserrat" style={{ color }}>
                        {value}
                    </h3>
                </div>
                <div
                    className="p-3 rounded-xl group-hover:opacity-90 transition-all"
                    style={{ backgroundColor: `${color}15` }}
                >
                    <i className={`fas ${icon} w-6 h-6`} style={{ color }}></i>
                </div>
            </div>
            {badge && (
                <div className="mt-4 flex items-center">
                    <span
                        className="text-xs font-medium px-3 py-1.5 rounded-full flex items-center gap-1 font-lato"
                        style={{
                            backgroundColor: `${badgeColor || color}15`,
                            color: badgeColor || color,
                        }}
                    >
                        <i className="fas fa-arrow-up w-3 h-3"></i>
                        {badge}
                    </span>
                </div>
            )}
            {description && (
                <p className="text-xs text-gray-500 mt-3 font-lato">{description}</p>
            )}
        </div>
    );
}

function CourseCard({ course }: { course: Course }) {
    const statusColors = {
        in_progress: { bg: '#fef3c7', text: '#f59e0b', icon: 'fa-clock' },
        completed: { bg: '#d1fae5', text: '#10b981', icon: 'fa-check-circle' },
        not_started: { bg: '#e5e7eb', text: '#6b7280', icon: 'fa-play-circle' },
    };

    const status = statusColors[course.status];

    // Always go to learn page with the next lesson if available
    const learnUrl = course.next_lesson
        ? `/courses/${course.slug}/learn/${course.next_lesson.slug}`
        : course.total_lessons > 0
            ? `/courses/${course.slug}/learn` // Will redirect to first lesson
            : `/courses/${course.slug}`; // No lessons, go to course detail page

    return (
        <div className="bg-white border border-gray-200 rounded-xl overflow-hidden hover:shadow-lg transition-all duration-300 group">
            {/* Thumbnail */}
            <div className="relative h-40 bg-linear-to-br from-primary-100 to-primary-50 overflow-hidden">
                {course.thumbnail_path ? (
                    <img
                        src={`/storage/${course.thumbnail_path}`}
                        alt={course.title}
                        className="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300"
                    />
                ) : (
                    <div className="w-full h-full flex items-center justify-center">
                        <i className="fas fa-book-open text-4xl" style={{ color: '#002147', opacity: 0.3 }}></i>
                    </div>
                )}

                {/* Status Badge */}
                <div className="absolute top-3 right-3">
                    <span
                        className="px-3 py-1 rounded-full text-xs font-medium font-lato flex items-center gap-1.5"
                        style={{ backgroundColor: status.bg, color: status.text }}
                    >
                        <i className={`fas ${status.icon}`}></i>
                        {course.status === 'in_progress' && 'In Progress'}
                        {course.status === 'completed' && 'Completed'}
                        {course.status === 'not_started' && 'Not Started'}
                    </span>
                </div>
            </div>

            {/* Content */}
            <div className="p-5">
                {/* Course Title */}
                <h3 className="font-bold font-montserrat text-lg mb-2 line-clamp-2" style={{ color: '#002147' }}>
                    {course.title}
                </h3>

                {/* Instructor & Category */}
                <div className="flex items-center gap-4 text-xs text-gray-500 mb-4 font-lato">
                    <span className="flex items-center gap-1">
                        <i className="fas fa-user"></i>
                        {course.instructor.name}
                    </span>
                    <span className="flex items-center gap-1">
                        <i className="fas fa-tag"></i>
                        {course.category.name}
                    </span>
                </div>

                {/* Progress Bar */}
                <div className="mb-4">
                    <div className="flex items-center justify-between text-xs text-gray-600 mb-2 font-lato">
                        <span>Progress</span>
                        <span className="font-bold" style={{ color: '#00a651' }}>
                            {course.completion_percentage}%
                        </span>
                    </div>
                    <div className="w-full bg-gray-200 rounded-full h-2 overflow-hidden">
                        <div
                            className="h-2 rounded-full transition-all duration-500"
                            style={{
                                width: `${course.completion_percentage}%`,
                                backgroundColor: '#00a651'
                            }}
                        ></div>
                    </div>
                    <p className="text-xs text-gray-500 mt-1.5 font-lato">
                        {course.completed_lessons} of {course.total_lessons} lessons completed
                    </p>
                </div>

                {/* Action Button */}
                {course.status === 'completed' ? (
                    <div className="flex space-x-2">
                        <button
                            onClick={() => router.post(route('certificates.generate', { course: course.slug }))}
                            className="w-full flex items-center justify-center gap-2 px-4 py-2.5 text-white rounded-lg font-medium font-lato transition-all duration-300 hover:shadow-md"
                            style={{ backgroundColor: '#00a651' }}
                        >
                            <i className="fas fa-certificate"></i>
                            Get Certificate
                        </button>
                        <Link
                            href={route('courses.show', { slug: course.slug })}
                            className="w-full flex items-center justify-center gap-2 px-4 py-2.5 text-white rounded-lg font-medium font-lato transition-all duration-300 hover:shadow-md"
                            style={{ backgroundColor: '#6b7280' }}
                        >
                            <i className="fas fa-star"></i>
                            Leave a Review
                        </Link>
                    </div>
                ) : course.status === 'in_progress' ? (                    <Link
                        href={learnUrl}
                        className="w-full flex items-center justify-center gap-2 px-4 py-2.5 text-white rounded-lg font-medium font-lato transition-all duration-300 hover:shadow-md"
                        style={{ backgroundColor: '#00a651' }}
                    >
                        <i className="fas fa-play"></i>
                        Continue: {course.next_lesson?.title}
                    </Link>
                ) : (
                    <Link
                        href={learnUrl}
                        className="w-full flex items-center justify-center gap-2 px-4 py-2.5 text-white rounded-lg font-medium font-lato transition-all duration-300 hover:shadow-md"
                        style={{ backgroundColor: '#00a651' }}
                    >
                        <i className="fas fa-play"></i>
                        Start Course
                    </Link>
                )}            </div>
        </div>
    );
}
