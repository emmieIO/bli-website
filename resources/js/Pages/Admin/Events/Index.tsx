import { Head, Link, usePage } from '@inertiajs/react';
import DashboardLayout from '@/Layouts/DashboardLayout';
import type { ReactNode } from 'react';

interface Event {
    id: number;
    title: string;
    slug: string;
    location?: string | null;
    mode: 'online' | 'offline' | 'hybrid';
    start_date: string;
    end_date: string;
    status: 'draft' | 'review' | 'published' | 'registration_open' | 'registration_closed' | 'live' | 'completed' | 'cancelled' | 'archived';
    is_featured: boolean;
    speakers_count?: number;
    attendees_count?: number;
    speaker_applications_count?: number;
    successful_transactions_count?: number;
    program_cover?: string | null;
}

interface EventsProps {
    events: {
        data: Event[];
    };
    capabilities: {
        canCreate: boolean;
        canUpdateAny: boolean;
        canViewPayments: boolean;
    };
}

const statusTabs = [
    { label: 'All', value: 'all' },
    { label: 'Draft', value: 'draft' },
    { label: 'Review', value: 'review' },
    { label: 'Published', value: 'published' },
    { label: 'Registration Open', value: 'registration_open' },
    { label: 'Live', value: 'live' },
    { label: 'Completed', value: 'completed' },
    { label: 'Cancelled', value: 'cancelled' },
];

const statusLabelMap: Record<Event['status'], string> = {
    draft: 'Draft',
    review: 'Review',
    published: 'Published',
    registration_open: 'Registration Open',
    registration_closed: 'Registration Closed',
    live: 'Live',
    completed: 'Completed',
    cancelled: 'Cancelled',
    archived: 'Archived',
};

const statusColorMap: Record<Event['status'], string> = {
    draft: 'bg-slate-100 text-slate-700',
    review: 'bg-violet-100 text-violet-700',
    published: 'bg-blue-100 text-blue-700',
    registration_open: 'bg-green-100 text-green-700',
    registration_closed: 'bg-amber-100 text-amber-700',
    live: 'bg-red-100 text-red-700',
    completed: 'bg-emerald-100 text-emerald-700',
    cancelled: 'bg-rose-100 text-rose-700',
    archived: 'bg-zinc-100 text-zinc-700',
};

export default function EventsIndex({ events, capabilities }: EventsProps) {
    const { sideLinks } = usePage().props as any;
    const currentStatus = new URLSearchParams(window.location.search).get('status') || 'all';
    const eventsList = events.data || [];

    const totals = {
        total: eventsList.length,
        live: eventsList.filter((event) => event.status === 'live').length,
        open: eventsList.filter((event) => event.status === 'registration_open').length,
        completed: eventsList.filter((event) => event.status === 'completed').length,
    };

    return (
        <DashboardLayout sideLinks={sideLinks}>
            <Head title="Events Management" />

            <div className="workspace-stack">
                <section className="workspace-header-card px-6 py-6 lg:px-8">
                    <div className="flex flex-col gap-5 lg:flex-row lg:items-end lg:justify-between">
                        <div className="max-w-3xl">
                            <p className="workspace-muted-label">Event Operations</p>
                            <h1 className="mt-3 text-3xl font-semibold tracking-tight text-slate-900">Events</h1>
                            <p className="mt-3 text-sm leading-7 text-slate-600">
                                Plan, publish, and supervise event delivery without the dashboard feeling like a control room.
                            </p>
                        </div>

                        {capabilities.canCreate && (
                            <div className="flex flex-wrap gap-3">
                                <Link
                                    href={route('admin.events.create')}
                                    className="inline-flex items-center gap-2 whitespace-nowrap rounded-2xl bg-slate-900 px-4 py-3 text-sm font-semibold text-white transition hover:bg-slate-800"
                                >
                                    <i className="fas fa-plus w-4 h-4"></i>
                                    New Event
                                </Link>
                            </div>
                        )}
                    </div>
                </section>

                <div className="grid gap-4 sm:grid-cols-2 xl:grid-cols-4">
                    <SummaryCard label="Visible in view" value={totals.total} hint="Current result set" />
                    <SummaryCard label="Live now" value={totals.live} hint="In progress" />
                    <SummaryCard label="Open for registration" value={totals.open} hint="Accepting attendees" />
                    <SummaryCard label="Completed" value={totals.completed} hint="Delivered events" />
                </div>

                <div className="workspace-card flex flex-wrap gap-2 p-2">
                    {statusTabs.map((tab) => {
                        const isActive = currentStatus === tab.value;

                        return (
                            <Link
                                key={tab.value}
                                href={`${route('admin.events.index')}?status=${tab.value}`}
                                className={`rounded-xl px-3 py-2.5 text-sm font-medium transition ${
                                    isActive ? 'bg-slate-900 text-white' : 'text-slate-600 hover:bg-slate-100'
                                }`}
                            >
                                {tab.label}
                            </Link>
                        );
                    })}
                </div>

                <div className="workspace-card overflow-hidden">
                    <div className="overflow-x-auto">
                        <table className="min-w-[960px] divide-y divide-slate-200">
                            <thead className="bg-slate-50">
                                <tr>
                                    <HeaderCell>Event</HeaderCell>
                                    <HeaderCell>Schedule</HeaderCell>
                                    <HeaderCell>Status</HeaderCell>
                                    <HeaderCell>Speakers</HeaderCell>
                                    <HeaderCell>Registrations</HeaderCell>
                                    {capabilities.canViewPayments && <HeaderCell>Revenue</HeaderCell>}
                                    <HeaderCell align="right">Actions</HeaderCell>
                                </tr>
                            </thead>
                            <tbody className="divide-y divide-slate-200">
                                {eventsList.length > 0 ? (
                                    eventsList.map((event) => (
                                        <tr key={event.id} className="hover:bg-slate-50/80">
                                            <td className="px-6 py-4">
                                                <div className="flex items-center gap-3">
                                                    <div className="h-12 w-12 shrink-0 overflow-hidden rounded-lg bg-slate-100">
                                                        {event.program_cover ? (
                                                            <img
                                                                src={`/storage/${event.program_cover}`}
                                                                alt={event.title}
                                                                className="h-full w-full object-cover"
                                                            />
                                                        ) : (
                                                            <div className="flex h-full w-full items-center justify-center text-slate-400">
                                                                <i className="fas fa-calendar-alt"></i>
                                                            </div>
                                                        )}
                                                    </div>
                                                    <div className="min-w-0">
                                                        <p className="truncate text-sm font-semibold text-primary font-montserrat">{event.title}</p>
                                                        <p className="truncate text-xs text-slate-500 font-lato">
                                                            {event.mode === 'online' ? 'Online' : event.location || 'Location pending'}
                                                        </p>
                                                    </div>
                                                </div>
                                            </td>
                                            <td className="whitespace-nowrap px-6 py-4 text-sm text-slate-700 font-lato">
                                                <p>{formatDate(event.start_date)}</p>
                                                <p className="text-xs text-slate-500">Ends {formatDate(event.end_date)}</p>
                                            </td>
                                            <td className="px-6 py-4">
                                                <div className="space-y-2">
                                                    <span className={`inline-flex whitespace-nowrap rounded-full px-2.5 py-1 text-xs font-semibold ${statusColorMap[event.status]}`}>
                                                        {statusLabelMap[event.status]}
                                                    </span>
                                                    {event.is_featured && (
                                                        <div className="whitespace-nowrap text-xs font-medium text-amber-700 font-lato">Featured event</div>
                                                    )}
                                                </div>
                                            </td>
                                            <td className="whitespace-nowrap px-6 py-4 text-sm text-slate-700 font-lato">
                                                <p>{event.speakers_count || 0} assigned</p>
                                                <p className="text-xs text-slate-500">{event.speaker_applications_count || 0} applications</p>
                                            </td>
                                            <td className="whitespace-nowrap px-6 py-4 text-sm text-slate-700 font-lato">
                                                <p>{event.attendees_count || 0} attendees</p>
                                            </td>
                                            {capabilities.canViewPayments && (
                                                <td className="whitespace-nowrap px-6 py-4 text-sm text-slate-700 font-lato">
                                                    <p>{event.successful_transactions_count || 0} paid orders</p>
                                                </td>
                                            )}
                                            <td className="px-6 py-4">
                                                <div className="flex items-center justify-end gap-3 whitespace-nowrap text-sm">
                                                    <Link href={route('admin.events.show', event.slug)} className="font-medium text-primary hover:text-primary-600">
                                                        Open
                                                    </Link>
                                                    {capabilities.canUpdateAny && (
                                                        <Link href={route('admin.events.edit', event.slug)} className="font-medium text-slate-600 hover:text-slate-900">
                                                            Edit
                                                        </Link>
                                                    )}
                                                </div>
                                            </td>
                                        </tr>
                                    ))
                                ) : (
                                    <tr>
                                        <td colSpan={capabilities.canViewPayments ? 7 : 6} className="px-6 py-14 text-center">
                                            <div className="space-y-3">
                                                <div className="mx-auto flex h-14 w-14 items-center justify-center rounded-full bg-slate-100 text-slate-400">
                                                    <i className="fas fa-calendar-times"></i>
                                                </div>
                                                <div>
                                                    <p className="text-base font-semibold text-slate-900 font-montserrat">No events found</p>
                                                    <p className="text-sm text-slate-500 font-lato">Create your next event or switch filters.</p>
                                                </div>
                                            </div>
                                        </td>
                                    </tr>
                                )}
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </DashboardLayout>
    );
}

function SummaryCard({ label, value, hint }: { label: string; value: number; hint: string }) {
    return (
        <div className="workspace-card p-5">
            <p className="text-xs font-medium uppercase tracking-[0.14em] text-slate-500">{label}</p>
            <p className="mt-3 text-3xl font-bold text-primary font-montserrat">{value}</p>
            <p className="mt-2 text-sm text-slate-500 font-lato">{hint}</p>
        </div>
    );
}

function HeaderCell({ children, align = 'left' }: { children: ReactNode; align?: 'left' | 'right' }) {
    return (
        <th
            className={`px-6 py-3 text-xs font-semibold uppercase tracking-[0.14em] text-slate-500 font-montserrat ${
                align === 'right' ? 'text-right' : 'text-left'
            }`}
        >
            {children}
        </th>
    );
}

function formatDate(dateString: string) {
    return new Date(dateString).toLocaleString('en-US', {
        year: 'numeric',
        month: 'short',
        day: 'numeric',
        hour: 'numeric',
        minute: '2-digit',
    });
}
