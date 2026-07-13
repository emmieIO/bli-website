import { Head, Link, usePage } from '@inertiajs/react';
import DashboardLayout from '@/Layouts/DashboardLayout';
import {
    ArrowRight,
    CalendarDays,
    CheckCircle2,
    Clock3,
    CreditCard,
    MessageSquareText,
    Send,
    Sparkles,
    UserRoundCheck,
    Users,
} from 'lucide-react';

interface AdminStats {
    totalUsers: number;
    totalUsersBadge: string;
    totalUsersBadgeColor: string;
    activeUsers: number;
    activeUsersBadge: string;
    eventsScheduled: number;
    eventsScheduledBadge: string;
    eventsScheduledBadgeColor: string;
    totalAttendees: number;
    totalAttendeesBadge: string;
    totalAttendeesBadgeColor: string;
}

interface InstructorStats {
    upcomingSessions: number;
    upcomingSessionsDescription: string;
    feedbackReceived: number;
    feedbackReceivedDescription: string;
}

interface DashboardProps {
    stats?: {
        myEvents?: number;
        upcomingEvents?: number;
        speakerInvitations?: number;
    };
    adminStats?: AdminStats | null;
    instructorStats?: InstructorStats | null;
}

export default function Dashboard({ stats, adminStats, instructorStats }: DashboardProps) {
    const { auth, sideLinks } = usePage().props as any;
    const user = auth?.user;
    const isAdmin = user?.roles?.some((role: string) => ['admin', 'super-admin'].includes(role));
    const isInstructor = user?.roles?.includes('instructor');

    return (
        <DashboardLayout sideLinks={sideLinks}>
            <Head title="Dashboard" />

            <div className="space-y-6">
                <section className="overflow-hidden rounded-lg border border-slate-200 bg-white">
                    <div className="grid gap-0 lg:grid-cols-[1fr_320px]">
                        <div className="p-6 lg:p-8">
                            <div className="inline-flex items-center gap-1.5 rounded-md bg-lime-100 px-2.5 py-1 text-[11px] font-semibold uppercase tracking-wider text-lime-700">
                                <Sparkles size={12} />
                                Overview
                            </div>
                            <h1 className="mt-4 text-2xl font-semibold tracking-tight text-slate-900">
                                Welcome back{user?.name ? `, ${user.name.split(' ')[0]}` : ''}
                            </h1>
                            <p className="mt-2 max-w-xl text-sm leading-relaxed text-slate-500">
                                {isAdmin
                                    ? 'Monitor people, events, payments, and operational tasks from one clean control surface.'
                                    : 'Keep your leadership journey, invitations, and upcoming programs easy to scan and act on.'}
                            </p>
                            <div className="mt-5 flex flex-wrap gap-2.5">
                                <ActionLink href={route('events.index')} icon={CalendarDays} variant="primary">
                                    Browse events
                                </ActionLink>
                                <ActionLink href={route('transactions.index')} icon={CreditCard} variant="secondary">
                                    Transactions
                                </ActionLink>
                            </div>
                        </div>

                        <div className="border-l border-slate-100 bg-gradient-to-br from-slate-50 to-primary-50/30 p-6 lg:p-8">
                            <p className="text-[10px] font-semibold uppercase tracking-[0.2em] text-slate-400">Today at a glance</p>
                            <div className="mt-5 space-y-4">
                                <StatusRow icon={CheckCircle2} label="Platform" value="Operational" status="ok" />
                                <StatusRow icon={Clock3} label="Focus" value={isAdmin ? 'Review event activity' : 'Check upcoming events'} />
                                <StatusRow icon={Send} label="Invitations" value={`${stats?.speakerInvitations ?? 0} pending`} />
                            </div>
                        </div>
                    </div>
                </section>

                {isAdmin ? (
                    <AdminDashboard adminStats={adminStats} />
                ) : isInstructor ? (
                    <InstructorDashboard instructorStats={instructorStats} />
                ) : (
                    <StudentDashboard stats={stats} />
                )}
            </div>
        </DashboardLayout>
    );
}

function AdminDashboard({ adminStats }: { adminStats?: AdminStats | null }) {
    if (!adminStats) return <LoadingPanel label="Loading admin statistics..." />;

    return (
        <>
            <section className="grid gap-4 sm:grid-cols-2 lg:grid-cols-4">
                <MetricCard icon={Users} label="Total users" value={adminStats.totalUsers.toLocaleString()} meta={adminStats.totalUsersBadge} accent="navy" />
                <MetricCard icon={UserRoundCheck} label="Active users" value={adminStats.activeUsers.toLocaleString()} meta={adminStats.activeUsersBadge} accent="lime" />
                <MetricCard icon={CalendarDays} label="Events scheduled" value={adminStats.eventsScheduled.toLocaleString()} meta={adminStats.eventsScheduledBadge} accent="red" />
                <MetricCard icon={CheckCircle2} label="Total attendees" value={adminStats.totalAttendees.toLocaleString()} meta={adminStats.totalAttendeesBadge} accent="navy" />
            </section>

            <section className="space-y-4">
                <h2 className="text-sm font-semibold tracking-tight text-slate-900">Quick actions</h2>
                <div className="grid gap-4 sm:grid-cols-2 lg:grid-cols-3">
                    <FocusPanel
                        title="Events"
                        description="Review events, speaker assignments, and event management."
                        href={route('admin.events.index')}
                        action="Open event manager"
                        accent="navy"
                    />
                    <FocusPanel
                        title="Payments"
                        description="Audit paid event activity and receipt history."
                        href={route('admin.transactions-audit.index')}
                        action="View transaction audit"
                        accent="red"
                    />
                    <FocusPanel
                        title="People"
                        description="Manage applications, ratings, roles, and permissions."
                        href={route('admin.users.index')}
                        action="Manage users"
                        accent="lime"
                    />
                </div>
            </section>
        </>
    );
}

function InstructorDashboard({ instructorStats }: { instructorStats?: InstructorStats | null }) {
    if (!instructorStats) return <LoadingPanel label="Loading instructor statistics..." />;

    return (
        <>
            <section className="grid gap-4 sm:grid-cols-2">
                <MetricCard
                    icon={CalendarDays}
                    label="Upcoming sessions"
                    value={instructorStats.upcomingSessions.toString()}
                    meta={instructorStats.upcomingSessionsDescription}
                    accent="navy"
                />
                <MetricCard
                    icon={MessageSquareText}
                    label="Feedback received"
                    value={instructorStats.feedbackReceived.toString()}
                    meta={instructorStats.feedbackReceivedDescription}
                    accent="lime"
                />
            </section>

            <FocusPanel
                title="Mentorship"
                description="Review active mentorship sessions, milestones, and resources from one focused space."
                href={route('instructor.mentorship.index')}
                action="Open mentorship"
                accent="navy"
            />
        </>
    );
}

function StudentDashboard({ stats }: DashboardProps) {
    const myEvents = stats?.myEvents || 0;
    const upcomingEvents = stats?.upcomingEvents || 0;
    const speakerInvitations = stats?.speakerInvitations || 0;

    return (
        <>
            <section className="grid gap-4 sm:grid-cols-3">
                <MetricCard
                    icon={CalendarDays}
                    label="My events"
                    value={myEvents.toString()}
                    meta={myEvents > 0 ? 'Open your event workspace' : 'Join an event to get started'}
                    accent="navy"
                />
                <MetricCard
                    icon={Clock3}
                    label="Upcoming"
                    value={upcomingEvents.toString()}
                    meta={upcomingEvents > 0 ? 'Events ahead' : 'No upcoming events yet'}
                    accent="lime"
                />
                <MetricCard
                    icon={Send}
                    label="Invitations"
                    value={speakerInvitations.toString()}
                    meta={speakerInvitations > 0 ? 'Review pending invitations' : 'No invitations waiting'}
                    accent="red"
                />
            </section>

            <section className="rounded-lg border border-primary-100 bg-gradient-to-r from-primary-50/50 to-white p-6">
                <div className="flex flex-col gap-5 sm:flex-row sm:items-center sm:justify-between">
                    <div>
                        <h2 className="text-lg font-semibold tracking-tight text-primary">Join a leadership experience</h2>
                        <p className="mt-1.5 max-w-xl text-sm leading-relaxed text-slate-500">
                            Your workspace centers on live events, invitations, mentorship, and formation experiences.
                        </p>
                    </div>
                    <ActionLink href={route('events.index')} icon={CalendarDays} variant="primary">
                        Browse events
                    </ActionLink>
                </div>
            </section>
        </>
    );
}

function MetricCard({ icon: Icon, label, value, meta, accent = 'navy' }: { icon: any; label: string; value: string; meta?: string; accent?: 'navy' | 'red' | 'lime' }) {
    const accentColors = {
        navy: {
            hover: 'group-hover:bg-primary-50 group-hover:text-primary group-hover:border-primary-200',
            bar: 'bg-primary',
        },
        red: {
            hover: 'group-hover:bg-accent-50 group-hover:text-accent group-hover:border-accent-200',
            bar: 'bg-accent',
        },
        lime: {
            hover: 'group-hover:bg-lime-50 group-hover:text-lime-600 group-hover:border-lime-200',
            bar: 'bg-lime-500',
        },
    };

    const colors = accentColors[accent];

    return (
        <article className={`group relative overflow-hidden rounded-lg border border-slate-200 bg-white p-5 transition hover:border-slate-300 hover:shadow-sm`}>
            <div className={`absolute inset-x-0 top-0 h-0.5 ${colors.bar}`} />
            <div className="flex items-start justify-between">
                <div className="min-w-0">
                    <p className="text-xs font-medium uppercase tracking-wider text-slate-400">{label}</p>
                    <p className="mt-2 text-[28px] font-semibold tracking-tight text-slate-900">{value}</p>
                </div>
                <span className={`flex h-10 w-10 shrink-0 items-center justify-center rounded-lg bg-slate-100 text-slate-500 transition ${colors.hover}`}>
                    <Icon size={18} />
                </span>
            </div>
            {meta && (
                <p className="mt-3 text-[13px] leading-relaxed text-slate-500">{meta}</p>
            )}
        </article>
    );
}

function FocusPanel({ title, description, href, action, accent = 'navy' }: { title: string; description: string; href: string; action: string; accent?: 'navy' | 'red' | 'lime' }) {
    const accentColors = {
        navy: {
            border: 'border-l-primary',
            hover: 'group-hover:text-primary',
        },
        red: {
            border: 'border-l-accent',
            hover: 'group-hover:text-accent',
        },
        lime: {
            border: 'border-l-lime-500',
            hover: 'group-hover:text-lime-600',
        },
    };

    const colors = accentColors[accent];

    return (
        <article className={`group rounded-lg border border-l-[3px] border-slate-200 ${colors.border} bg-white p-5 transition hover:border-slate-300 hover:shadow-sm`}>
            <h3 className="text-sm font-semibold tracking-tight text-slate-900">{title}</h3>
            <p className="mt-1.5 text-[13px] leading-relaxed text-slate-500">{description}</p>
            <Link
                href={href}
                className={`mt-3 inline-flex items-center gap-1.5 text-[13px] font-medium text-slate-900 transition ${colors.hover}`}
            >
                {action}
                <ArrowRight size={14} />
            </Link>
        </article>
    );
}

function ActionLink({ href, icon: Icon, variant, children }: { href: string; icon: any; variant: 'primary' | 'secondary'; children: React.ReactNode }) {
    return (
        <Link
            href={href}
            className={`inline-flex items-center gap-2 rounded-lg px-4 py-2.5 text-sm font-medium transition ${
                variant === 'primary'
                    ? 'bg-primary text-white hover:bg-primary-600 shadow-sm'
                    : 'border border-slate-200 bg-white text-slate-700 hover:border-primary-200 hover:bg-primary-50 hover:text-primary'
            }`}
        >
            <Icon size={16} />
            {children}
        </Link>
    );
}

function StatusRow({ icon: Icon, label, value, status }: { icon: any; label: string; value: string; status?: 'ok' }) {
    return (
        <div className="flex items-center gap-3">
            <span className={`flex h-8 w-8 shrink-0 items-center justify-center rounded-lg ${status === 'ok' ? 'bg-lime-100 text-lime-600' : 'bg-white text-slate-500'}`}>
                <Icon size={15} />
            </span>
            <div className="min-w-0">
                <p className="text-[11px] font-medium uppercase tracking-wider text-slate-400">{label}</p>
                <p className="truncate text-[13px] font-medium text-slate-700">{value}</p>
            </div>
        </div>
    );
}

function LoadingPanel({ label }: { label: string }) {
    return (
        <div className="rounded-lg border border-slate-200 bg-white p-10 text-center text-sm text-slate-400">
            {label}
        </div>
    );
}
