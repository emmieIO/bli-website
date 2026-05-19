import { Head, Link } from '@inertiajs/react';
import DashboardLayout from '@/Layouts/DashboardLayout';
import { usePage } from '@inertiajs/react';

interface MentorshipRequest {
    id: number;
    student_id: number;
    instructor_id: number;
    message: string;
    goals: string | null;
    duration_type: string;
    duration_value: number;
    status: string;
    instructor_response: string | null;
    admin_response: string | null;
    rejection_reason: string | null;
    created_at: string;
    student: {
        id: number;
        name: string;
        email: string;
    };
    instructor: {
        id: number;
        name: string;
        email: string;
    };
    status_label: string;
    status_color: string;
    formatted_duration: string;
}

interface Statistics {
    total: number;
    pending: number;
    instructor_approved: number;
    admin_approved: number;
    active: number;
    rejected: number;
}

interface PaginatedRequests {
    data: MentorshipRequest[];
    current_page: number;
    last_page: number;
    per_page: number;
    total: number;
}

interface Props {
    requests: PaginatedRequests;
    pending: MentorshipRequest[];
    statistics: Statistics;
    filters: {
        status?: string;
        search?: string;
    };
}

export default function Index({ requests, statistics }: Props) {
    const { sideLinks } = usePage().props as any;

    const getStatusBadgeClass = (color: string) => {
        const colors: { [key: string]: string } = {
            yellow: 'bg-yellow-100 text-yellow-800',
            blue: 'bg-blue-100 text-blue-800',
            green: 'bg-green-100 text-green-800',
            red: 'bg-red-100 text-red-800',
            gray: 'bg-gray-100 text-gray-800',
        };
        return colors[color] || colors.gray;
    };

    return (
        <DashboardLayout sideLinks={sideLinks}>
            <Head title="Mentorship Manager" />

            <div className="workspace-stack">
                <section className="workspace-header-card px-6 py-6 lg:px-8">
                    <div className="max-w-3xl">
                        <p className="workspace-muted-label">Mentorship Operations</p>
                        <h1 className="mt-3 text-3xl font-semibold tracking-tight text-slate-900">Mentorship Manager</h1>
                        <p className="mt-3 text-sm leading-7 text-slate-600">
                            Manage approvals, supervise active pairings, and keep the mentorship pipeline easy to review.
                        </p>
                    </div>
                </section>

                <div className="grid grid-cols-1 gap-4 md:grid-cols-3 xl:grid-cols-6">
                    <MetricCard label="Total" value={statistics.total} tone="text-slate-900" />
                    <MetricCard label="Pending" value={statistics.pending} tone="text-yellow-600" />
                    <MetricCard label="Instructor Approved" value={statistics.instructor_approved} tone="text-blue-600" />
                    <MetricCard label="Admin Approved" value={statistics.admin_approved} tone="text-green-600" />
                    <MetricCard label="Active" value={statistics.active} tone="text-emerald-700" />
                    <MetricCard label="Rejected" value={statistics.rejected} tone="text-red-600" />
                </div>

                <section className="workspace-card overflow-hidden">
                    {requests.data.length === 0 ? (
                        <div className="px-6 py-14 text-center">
                            <h3 className="text-sm font-medium text-slate-900">No mentorship requests</h3>
                            <p className="mt-1 text-sm text-slate-500">All mentorship requests will appear here.</p>
                        </div>
                    ) : (
                        <table className="min-w-full divide-y divide-slate-200">
                            <thead className="bg-slate-50/90">
                                <tr>
                                    <th className="px-6 py-3 text-left text-xs font-semibold uppercase tracking-[0.14em] text-slate-500">Student</th>
                                    <th className="px-6 py-3 text-left text-xs font-semibold uppercase tracking-[0.14em] text-slate-500">Instructor</th>
                                    <th className="px-6 py-3 text-left text-xs font-semibold uppercase tracking-[0.14em] text-slate-500">Duration</th>
                                    <th className="px-6 py-3 text-left text-xs font-semibold uppercase tracking-[0.14em] text-slate-500">Status</th>
                                    <th className="px-6 py-3 text-left text-xs font-semibold uppercase tracking-[0.14em] text-slate-500">Requested</th>
                                    <th className="px-6 py-3 text-right text-xs font-semibold uppercase tracking-[0.14em] text-slate-500">Actions</th>
                                </tr>
                            </thead>
                            <tbody className="divide-y divide-slate-100 bg-white">
                                {requests.data.map((request) => (
                                    <tr key={request.id}>
                                        <td className="px-6 py-4 whitespace-nowrap">
                                            <div className="text-sm font-medium text-slate-900">{request.student.name}</div>
                                            <div className="text-sm text-slate-500">{request.student.email}</div>
                                        </td>
                                        <td className="px-6 py-4 whitespace-nowrap">
                                            <div className="text-sm font-medium text-slate-900">{request.instructor.name}</div>
                                            <div className="text-sm text-slate-500">{request.instructor.email}</div>
                                        </td>
                                        <td className="px-6 py-4 whitespace-nowrap text-sm text-slate-900">
                                            {request.formatted_duration}
                                        </td>
                                        <td className="px-6 py-4 whitespace-nowrap">
                                            <span className={`inline-flex rounded-full px-2.5 py-1 text-xs font-semibold ${getStatusBadgeClass(request.status_color)}`}>
                                                {request.status_label}
                                            </span>
                                        </td>
                                        <td className="px-6 py-4 whitespace-nowrap text-sm text-slate-500">
                                            {new Date(request.created_at).toLocaleDateString()}
                                        </td>
                                        <td className="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                            <Link
                                                href={route('admin.mentorship.show', request.id)}
                                                className="text-primary hover:text-primary/90"
                                            >
                                                View Details
                                            </Link>
                                        </td>
                                    </tr>
                                ))}
                            </tbody>
                        </table>
                    )}
                </section>
            </div>
        </DashboardLayout>
    );
}

function MetricCard({ label, value, tone }: { label: string; value: number; tone: string }) {
    return (
        <div className="workspace-card p-4">
            <p className="text-sm text-slate-500">{label}</p>
            <p className={`mt-2 text-2xl font-bold ${tone}`}>{value}</p>
        </div>
    );
}
