import { Head, usePage } from '@inertiajs/react';
import DashboardLayout from '@/Layouts/DashboardLayout';

interface DashboardProps {
    stats?: {
        totalCourses?: number;
        inProgress?: number;
        completed?: number;
        hoursSpent?: number;
    };
}

export default function Dashboard({ stats }: DashboardProps) {
    const { auth, sideLinks } = usePage().props as any;
    const user = auth?.user;
    const isAdmin = user?.roles?.some((role: string) => ['admin', 'super-admin'].includes(role));
    const isInstructor = user?.roles?.includes('instructor');

    return (
        <DashboardLayout sideLinks={sideLinks}>
            <Head title="Dashboard - Beacon Leadership Institute" />

            <section className="min-h-screen bg-gradient-to-br from-gray-50 to-primary-50/30">
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
                        <AdminDashboard />
                    ) : isInstructor ? (
                        <InstructorDashboard />
                    ) : (
                        <StudentDashboard stats={stats} />
                    )}
                </div>
            </section>
        </DashboardLayout>
    );
}

function AdminDashboard() {
    const adminStats = [
        { title: 'Total Users', value: '1,842', icon: 'fa-users', color: '#002147', badge: '+12.5% this month', badgeColor: '#00a651' },
        { title: 'Active Users', value: '1,294', icon: 'fa-chart-line', color: '#00a651', badge: '72% engagement' },
        { title: 'Total Courses', value: '86', icon: 'fa-book-open', color: '#002147', badge: '24 in development' },
        { title: 'Events Scheduled', value: '32', icon: 'fa-calendar', color: '#002147', badge: '5 happening today', badgeColor: '#002147' },
        { title: 'Total Attendees', value: '4,217', icon: 'fa-user-check', color: '#00a651', badge: '+827 this month', badgeColor: '#00a651' },
    ];

    return (
        <div className="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            {adminStats.map((stat, index) => (
                <StatCard key={index} {...stat} />
            ))}
        </div>
    );
}

function InstructorDashboard() {
    const instructorStats = [
        { title: 'Courses Taught', value: '12', icon: 'fa-book-open', color: '#002147', description: '2 new courses this month' },
        { title: 'Active Students', value: '158', icon: 'fa-users', color: '#00a651', description: '+15 active this week' },
        { title: 'Assignments Graded', value: '47', icon: 'fa-check-square', color: '#002147', description: '8 graded this week' },
        { title: 'Upcoming Sessions', value: '3', icon: 'fa-calendar', color: '#002147', description: 'Next session: Friday 10am' },
        { title: 'Feedback Received', value: '21', icon: 'fa-comment', color: '#00a651', description: '5 new feedbacks this month' },
    ];

    return (
        <div className="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            {instructorStats.map((stat, index) => (
                <StatCard key={index} {...stat} />
            ))}
        </div>
    );
}

function StudentDashboard({ stats }: DashboardProps) {
    const studentStats = [
        { title: 'Total Courses', value: stats?.totalCourses?.toString() || '24', icon: 'fa-book-open', color: '#002147', description: '+2 from last month' },
        { title: 'In Progress', value: stats?.inProgress?.toString() || '5', icon: 'fa-clock', color: '#00a651', description: '2 active this week' },
        { title: 'Completed', value: stats?.completed?.toString() || '14', icon: 'fa-check-circle', color: '#00a651', description: '3 certifications earned' },
        { title: 'Hours Spent', value: stats?.hoursSpent?.toString() || '42.5', icon: 'fa-stopwatch', color: '#002147', description: '+8.2h this month' },
    ];

    return (
        <div className="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
            {studentStats.map((stat, index) => (
                <StatCard key={index} {...stat} />
            ))}
        </div>
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
