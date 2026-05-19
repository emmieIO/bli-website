import { FormEvent, useMemo, useState } from 'react';
import { Head, Link, router, usePage } from '@inertiajs/react';
import DashboardLayout from '@/Layouts/DashboardLayout';
import Button from '@/Components/Button';

interface Event {
    id: number;
    title: string;
    slug: string;
    start_date: string;
    end_date: string;
    location?: string | null;
    mode?: string | null;
    journey_status: 'upcoming' | 'ongoing' | 'ended';
    registration_status: 'registered' | 'waitlisted' | 'attended' | 'no_show';
    latest_transaction?: {
        status: string;
        amount: string;
    } | null;
    pivot?: {
        revoke_count?: number;
    };
}

interface MyEventsProps {
    events: Event[];
}

const registrationTone: Record<string, string> = {
    registered: 'border-emerald-200 bg-emerald-50 text-emerald-800',
    waitlisted: 'border-amber-200 bg-amber-50 text-amber-800',
    attended: 'border-slate-200 bg-slate-50 text-slate-700',
    no_show: 'border-rose-200 bg-rose-50 text-rose-700',
};

const registrationLabel: Record<string, string> = {
    registered: 'confirmed',
    waitlisted: 'waitlisted',
    attended: 'attended',
    no_show: 'no show',
};

const journeyTone: Record<string, string> = {
    upcoming: 'border-slate-200 bg-slate-50 text-slate-700',
    ongoing: 'border-blue-200 bg-blue-50 text-blue-800',
    ended: 'border-zinc-200 bg-zinc-50 text-zinc-700',
};

export default function MyEvents({ events }: MyEventsProps) {
    const { sideLinks } = usePage().props as any;
    const [showCancelModal, setShowCancelModal] = useState(false);
    const [selectedEvent, setSelectedEvent] = useState<Event | null>(null);
    const [isProcessing, setIsProcessing] = useState(false);

    const stats = useMemo(() => ({
        total: events.length,
        confirmed: events.filter((event) => event.registration_status === 'registered').length,
        waitlisted: events.filter((event) => event.registration_status === 'waitlisted').length,
        live: events.filter((event) => event.journey_status === 'ongoing').length,
    }), [events]);

    const formatDate = (value: string) => new Intl.DateTimeFormat('en-US', {
        month: 'short',
        day: 'numeric',
        year: 'numeric',
    }).format(new Date(value));

    const handleCancelClick = (event: Event) => {
        setSelectedEvent(event);
        setShowCancelModal(true);
    };

    const handleCancelEvent = (e: FormEvent) => {
        e.preventDefault();

        if (!selectedEvent) {
            return;
        }

        setIsProcessing(true);
        router.delete(route('user.revoke.event', selectedEvent.slug), {
            preserveScroll: true,
            onFinish: () => {
                setIsProcessing(false);
                setShowCancelModal(false);
                setSelectedEvent(null);
            },
        });
    };

    const getRemainingChances = (event: Event) => 5 - (event.pivot?.revoke_count ?? 0);

    return (
        <DashboardLayout sideLinks={sideLinks}>
            <Head title="My Events" />

            <div className="workspace-stack">
                <section className="workspace-header-card overflow-hidden">
                    <div className="border-b border-slate-200 px-6 py-7 lg:px-8 lg:py-8">
                        <div className="flex flex-col gap-6 xl:flex-row xl:items-start xl:justify-between">
                            <div className="max-w-2xl space-y-3">
                                <span className="inline-flex items-center rounded-full border border-slate-300 bg-white px-3 py-1 text-[11px] font-semibold uppercase tracking-[0.18em] text-slate-600">
                                    Event Registry
                                </span>
                                <div>
                                    <h1 className="text-3xl font-semibold tracking-tight text-slate-900 md:text-[2.35rem]">
                                        My Events
                                    </h1>
                                    <p className="mt-3 text-sm leading-6 text-slate-600 md:text-[15px]">
                                        Review active registrations, waitlist positions, payment context, and direct workspace access.
                                    </p>
                                </div>
                            </div>

                            <div className="min-w-full overflow-x-auto xl:min-w-[620px]">
                                <div className="grid min-w-[620px] grid-cols-4 divide-x divide-slate-200 rounded-2xl border border-slate-200 bg-white">
                                    <SummaryItem label="All Events" value={stats.total.toString()} context="Total registrations" />
                                    <SummaryItem label="Confirmed" value={stats.confirmed.toString()} context="Active attendee seats" />
                                    <SummaryItem label="Waitlisted" value={stats.waitlisted.toString()} context="Awaiting placement" />
                                    <SummaryItem label="Live Now" value={stats.live.toString()} context="Currently in session" />
                                </div>
                            </div>
                        </div>
                    </div>

                    <div className="bg-white px-6 py-4 lg:px-8">
                        <p className="text-sm leading-6 text-slate-600">
                            Open any event workspace for schedule, access instructions, resources, refund status, and speaker details.
                        </p>
                    </div>
                </section>

                {events.length > 0 ? (
                    <section className="workspace-card overflow-hidden">
                        <div className="overflow-x-auto">
                            <table className="min-w-full divide-y divide-slate-200">
                                <thead className="bg-slate-50/80">
                                    <tr>
                                        <TableHead>Event</TableHead>
                                        <TableHead>Registration</TableHead>
                                        <TableHead>Journey</TableHead>
                                        <TableHead>Schedule</TableHead>
                                        <TableHead>Format</TableHead>
                                        <TableHead>Payment</TableHead>
                                        <TableHead>Location</TableHead>
                                        <TableHead align="right">Actions</TableHead>
                                    </tr>
                                </thead>
                                <tbody className="divide-y divide-slate-100 bg-white">
                                    {events.map((event) => (
                                        <tr key={event.id} className="align-top transition hover:bg-slate-50/70">
                                            <td className="px-6 py-5">
                                                <div className="space-y-1">
                                                    <p className="text-sm font-semibold text-slate-900">{event.title}</p>
                                                    <p className="text-sm text-slate-500">
                                                        {event.registration_status === 'waitlisted'
                                                            ? 'Awaiting the next available seat.'
                                                            : 'Open workspace for access details and updates.'}
                                                    </p>
                                                </div>
                                            </td>
                                            <td className="px-6 py-5">
                                                <span className={`inline-flex rounded-full border px-3 py-1 text-[11px] font-semibold uppercase tracking-[0.16em] ${registrationTone[event.registration_status]}`}>
                                                    {registrationLabel[event.registration_status] ?? event.registration_status}
                                                </span>
                                            </td>
                                            <td className="px-6 py-5">
                                                <span className={`inline-flex rounded-full border px-3 py-1 text-[11px] font-semibold uppercase tracking-[0.16em] ${journeyTone[event.journey_status]}`}>
                                                    {event.journey_status}
                                                </span>
                                            </td>
                                            <td className="px-6 py-5 text-sm leading-6 text-slate-700">
                                                <div>{formatDate(event.start_date)}</div>
                                                <div className="text-slate-500">to {formatDate(event.end_date)}</div>
                                            </td>
                                            <td className="px-6 py-5 text-sm text-slate-700">
                                                {event.mode ? event.mode.charAt(0).toUpperCase() + event.mode.slice(1) : 'Hybrid'}
                                            </td>
                                            <td className="px-6 py-5 text-sm text-slate-700">
                                                {event.latest_transaction ? event.latest_transaction.status : 'No payment required'}
                                            </td>
                                            <td className="px-6 py-5 text-sm text-slate-700">
                                                {event.location || 'Shared in workspace'}
                                            </td>
                                            <td className="px-6 py-5">
                                                <div className="flex flex-col items-end gap-3">
                                                    <Link
                                                        href={route('user.events.show', event.slug)}
                                                        className="inline-flex items-center rounded-xl border border-slate-900 bg-slate-900 px-4 py-2.5 text-sm font-semibold text-white transition hover:bg-slate-800"
                                                    >
                                                        Open workspace
                                                    </Link>
                                                    <button
                                                        type="button"
                                                        onClick={() => handleCancelClick(event)}
                                                        disabled={event.journey_status === 'ended'}
                                                        className="text-sm font-medium text-rose-700 transition hover:text-rose-800 disabled:cursor-not-allowed disabled:text-slate-400"
                                                    >
                                                        Cancel registration
                                                    </button>
                                                </div>
                                            </td>
                                        </tr>
                                    ))}
                                </tbody>
                            </table>
                        </div>
                    </section>
                ) : (
                    <section className="workspace-card border-dashed p-10 text-center">
                        <div className="mx-auto max-w-xl space-y-4">
                            <div className="mx-auto flex h-16 w-16 items-center justify-center rounded-full border border-slate-200 bg-slate-50 text-slate-700">
                                <i className="fas fa-calendar-plus text-xl"></i>
                            </div>
                            <h2 className="text-2xl font-semibold text-slate-900">No event registrations yet</h2>
                            <p className="text-sm leading-7 text-slate-600">
                                Once you register for an event, this page becomes your event register for confirmations, reminders, and attendee resources.
                            </p>
                            <Link
                                href={route('events.index')}
                                className="inline-flex items-center rounded-xl border border-slate-900 bg-slate-900 px-5 py-3 text-sm font-semibold text-white transition hover:bg-slate-800"
                            >
                                Browse events
                            </Link>
                        </div>
                    </section>
                )}
            </div>

            {showCancelModal && selectedEvent && (
                <CancelEventModal
                    event={selectedEvent}
                    remainingChances={getRemainingChances(selectedEvent)}
                    isProcessing={isProcessing}
                    onClose={() => setShowCancelModal(false)}
                    onConfirm={handleCancelEvent}
                />
            )}
        </DashboardLayout>
    );
}

function SummaryItem({
    label,
    value,
    context,
}: {
    label: string;
    value: string;
    context: string;
}) {
    return (
        <div className="px-5 py-4">
            <p className="text-[11px] font-semibold uppercase tracking-[0.16em] text-slate-500">{label}</p>
            <p className="mt-3 text-2xl font-semibold tracking-tight text-slate-900">{value}</p>
            <p className="mt-1 text-xs leading-5 text-slate-500">{context}</p>
        </div>
    );
}

function TableHead({
    children,
    align = 'left',
}: {
    children: React.ReactNode;
    align?: 'left' | 'right';
}) {
    return (
        <th className={`px-6 py-4 text-[11px] font-semibold uppercase tracking-[0.18em] text-slate-500 ${align === 'right' ? 'text-right' : 'text-left'}`}>
            {children}
        </th>
    );
}

function CancelEventModal({
    event,
    remainingChances,
    isProcessing,
    onClose,
    onConfirm,
}: {
    event: Event;
    remainingChances: number;
    isProcessing: boolean;
    onClose: () => void;
    onConfirm: (e: FormEvent) => void;
}) {
    return (
        <div className="fixed inset-0 z-50 flex items-center justify-center bg-black/30 px-4">
            <div className="relative w-full max-w-md rounded-lg bg-white p-6 shadow-xl">
                <button
                    type="button"
                    onClick={onClose}
                    className="absolute right-3 top-3 inline-flex h-8 w-8 items-center justify-center rounded-lg text-gray-400 hover:bg-gray-100 hover:text-gray-900"
                >
                    <i className="fas fa-times"></i>
                </button>

                <div className="space-y-4 text-center">
                    <div className="mx-auto flex h-12 w-12 items-center justify-center rounded-full bg-red-50 text-red-600">
                        <i className="fas fa-exclamation-triangle"></i>
                    </div>

                    <div className="space-y-2">
                        <h3 className="text-lg font-semibold text-primary">Cancel registration for {event.title}?</h3>
                        <p className="text-sm leading-6 text-gray-600">
                            Cancelling removes you from the attendee journey and may affect your remaining registration attempts.
                        </p>
                        <p className="text-sm font-medium text-red-600">
                            {remainingChances} cancellation{remainingChances !== 1 ? 's' : ''} remaining.
                        </p>
                    </div>

                    <form onSubmit={onConfirm} className="flex justify-center gap-3">
                        <Button type="submit" variant="danger" loading={isProcessing}>
                            Confirm cancel
                        </Button>
                        <Button type="button" variant="secondary" onClick={onClose}>
                            Keep registration
                        </Button>
                    </form>
                </div>
            </div>
        </div>
    );
}
