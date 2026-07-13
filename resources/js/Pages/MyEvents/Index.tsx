import { FormEvent, useMemo, useState } from 'react';
import { Head, Link, router, usePage } from '@inertiajs/react';
import DashboardLayout from '@/Layouts/DashboardLayout';
import { CalendarDays, Search, X, ArrowRight, ChevronDown } from 'lucide-react';

interface Event {
    id: number;
    title: string;
    slug: string;
    start_date: string;
    end_date: string;
    location?: string | null;
    mode?: string | null;
    journey_status: 'upcoming' | 'ongoing' | 'ended';
    registration_status: 'registered' | 'attended' | 'no_show';
    latest_transaction?: { status: string; amount: string; } | null;
    pivot?: { revoke_count?: number; };
}

interface MyEventsProps { events: Event[]; }

const registrationTone: Record<string, string> = {
    registered: 'bg-lime-50 text-lime-700',
    attended: 'bg-slate-100 text-slate-600',
    no_show: 'bg-red-50 text-red-600',
};

const registrationLabel: Record<string, string> = {
    registered: 'Confirmed',
    attended: 'Attended',
    no_show: 'No show',
};

const journeyColor: Record<string, string> = {
    upcoming: 'bg-slate-100 text-slate-600',
    ongoing: 'bg-primary-50 text-primary',
    ended: 'bg-zinc-100 text-zinc-600',
};

export default function MyEvents({ events }: MyEventsProps) {
    const { sideLinks } = usePage().props as any;

    const [search, setSearch] = useState('');
    const [statusFilter, setStatusFilter] = useState('');
    const [journeyFilter, setJourneyFilter] = useState('');
    const [showCancelModal, setShowCancelModal] = useState(false);
    const [selectedEvent, setSelectedEvent] = useState<Event | null>(null);
    const [isProcessing, setIsProcessing] = useState(false);

    const filtered = useMemo(() => {
        return events.filter((event) => {
            if (search && !event.title.toLowerCase().includes(search.toLowerCase())) return false;
            if (statusFilter && event.registration_status !== statusFilter) return false;
            if (journeyFilter && event.journey_status !== journeyFilter) return false;
            return true;
        });
    }, [events, search, statusFilter, journeyFilter]);

    const formatDate = (value: string) => new Intl.DateTimeFormat('en-US', {
        month: 'short', day: 'numeric', year: 'numeric',
    }).format(new Date(value));

    const handleCancelClick = (event: Event) => { setSelectedEvent(event); setShowCancelModal(true); };
    const handleCancelEvent = (e: FormEvent) => {
        e.preventDefault();
        if (!selectedEvent) return;
        setIsProcessing(true);
        router.delete(route('user.revoke.event', selectedEvent.slug), {
            preserveScroll: true,
            onFinish: () => { setIsProcessing(false); setShowCancelModal(false); setSelectedEvent(null); },
        });
    };
    const getRemainingChances = (event: Event) => 5 - (event.pivot?.revoke_count ?? 0);

    return (
        <DashboardLayout sideLinks={sideLinks}>
            <Head title="My Events" />

            <div className="space-y-5">
                <div>
                    <h1 className="text-xl font-semibold tracking-tight text-slate-900">My Events</h1>
                    <p className="mt-1 text-sm text-slate-500">Your confirmed events and attendee workspaces.</p>
                </div>

                <div className="grid gap-3 sm:grid-cols-2 lg:flex lg:items-center">
                    <div className="relative sm:col-span-2 lg:col-span-1 lg:w-full lg:max-w-sm">
                        <Search size={15} className="absolute left-3 top-1/2 -translate-y-1/2 text-slate-400" />
                        <input
                            type="text" value={search} onChange={(e) => setSearch(e.target.value)}
                            placeholder="Search by title..."
                            className="h-9 w-full rounded-md border border-slate-200 bg-white pl-9 pr-3 text-sm text-slate-900 placeholder:text-slate-400 outline-none transition focus:border-primary-300 focus:ring-2 focus:ring-primary-500/10"
                        />
                    </div>
                    <div className="relative min-w-0">
                        <select value={statusFilter} onChange={(e) => setStatusFilter(e.target.value)}
                            className="h-9 w-full appearance-none rounded-md border border-slate-200 bg-white pl-3 pr-8 text-sm text-slate-700 outline-none transition focus:border-primary-300 focus:ring-2 focus:ring-primary-500/10">
                            <option value="">All statuses</option>
                            <option value="registered">Confirmed</option>
                            <option value="attended">Attended</option>
                            <option value="no_show">No show</option>
                        </select>
                        <ChevronDown size={14} className="pointer-events-none absolute right-2.5 top-1/2 -translate-y-1/2 text-slate-400" />
                    </div>
                    <div className="relative min-w-0">
                        <select value={journeyFilter} onChange={(e) => setJourneyFilter(e.target.value)}
                            className="h-9 w-full appearance-none rounded-md border border-slate-200 bg-white pl-3 pr-8 text-sm text-slate-700 outline-none transition focus:border-primary-300 focus:ring-2 focus:ring-primary-500/10">
                            <option value="">All stages</option>
                            <option value="upcoming">Upcoming</option>
                            <option value="ongoing">Ongoing</option>
                            <option value="ended">Ended</option>
                        </select>
                        <ChevronDown size={14} className="pointer-events-none absolute right-2.5 top-1/2 -translate-y-1/2 text-slate-400" />
                    </div>
                </div>

                {filtered.length === 0 ? (
                    <div className="rounded-lg border border-slate-200 bg-white px-5 py-12 text-center sm:p-16">
                        <div className="mx-auto flex h-14 w-14 items-center justify-center rounded-lg bg-slate-100 text-slate-300">
                            <CalendarDays size={24} />
                        </div>
                        <h3 className="mt-4 text-sm font-semibold text-slate-900">
                            {search || statusFilter || journeyFilter ? 'No events match your filters' : 'No event registrations yet'}
                        </h3>
                        <p className="mt-1 text-sm text-slate-500">
                            {search || statusFilter || journeyFilter ? 'Try adjusting your search or filters.' : 'Once you register for an event, it will appear here.'}
                        </p>
                    </div>
                ) : (
                    <>
                        <p className="text-[13px] text-slate-400">
                            Showing <span className="font-medium text-slate-700">{filtered.length}</span> of {events.length} event{events.length !== 1 ? 's' : ''}
                        </p>

                        <div className="grid gap-3 md:hidden">
                            {filtered.map((event) => (
                                <article key={event.id} className="rounded-lg border border-slate-200 bg-white p-4 shadow-sm">
                                    <div className="flex items-start justify-between gap-3">
                                        <div className="min-w-0 flex-1">
                                            <p className="text-sm font-semibold leading-5 text-slate-900">{event.title}</p>
                                            <p className="mt-1 text-[13px] leading-5 text-slate-500">
                                                {event.location || 'Shared in workspace'}
                                            </p>
                                        </div>
                                        {event.journey_status !== 'ended' && (
                                            <button type="button" onClick={() => handleCancelClick(event)}
                                                className="shrink-0 rounded-md p-2 text-slate-400 transition hover:bg-accent-50 hover:text-accent" title="Cancel">
                                                <X size={15} />
                                            </button>
                                        )}
                                    </div>

                                    <div className="mt-3 flex flex-wrap gap-2">
                                        <span className={`inline-flex rounded-md px-2 py-1 text-[11px] font-semibold uppercase tracking-wider ${registrationTone[event.registration_status]}`}>
                                            {registrationLabel[event.registration_status]}
                                        </span>
                                        <span className={`inline-flex rounded-md px-2 py-1 text-[11px] font-semibold uppercase tracking-wider ${journeyColor[event.journey_status]}`}>
                                            {event.journey_status}
                                        </span>
                                        <span className="inline-flex rounded-md bg-slate-100 px-2 py-1 text-[11px] font-semibold uppercase tracking-wider text-slate-600">
                                            {event.mode || 'Hybrid'}
                                        </span>
                                    </div>

                                    <div className="mt-4 flex items-start gap-2 rounded-md bg-slate-50 px-3 py-2 text-[13px] leading-5 text-slate-600">
                                        <CalendarDays size={14} className="mt-0.5 shrink-0 text-slate-400" />
                                        <span>{formatDate(event.start_date)} - {formatDate(event.end_date)}</span>
                                    </div>

                                    <Link href={route('user.events.show', event.slug)}
                                        className="mt-4 inline-flex w-full items-center justify-center gap-1 rounded-md bg-primary px-3.5 py-2.5 text-[13px] font-medium text-white shadow-sm transition hover:bg-primary-600">
                                        Open workspace <ArrowRight size={13} />
                                    </Link>
                                </article>
                            ))}
                        </div>

                        <div className="hidden overflow-hidden rounded-lg border border-slate-200 bg-white md:block">
                            <table className="min-w-full">
                                <thead className="bg-slate-50/80 border-b border-slate-200">
                                    <tr>
                                        <th className="px-5 py-3 text-left text-[11px] font-semibold uppercase tracking-[0.14em] text-slate-500">Event</th>
                                        <th className="px-5 py-3 text-left text-[11px] font-semibold uppercase tracking-[0.14em] text-slate-500">Registration</th>
                                        <th className="px-5 py-3 text-left text-[11px] font-semibold uppercase tracking-[0.14em] text-slate-500">Stage</th>
                                        <th className="px-5 py-3 text-left text-[11px] font-semibold uppercase tracking-[0.14em] text-slate-500">Schedule</th>
                                        <th className="px-5 py-3 text-right text-[11px] font-semibold uppercase tracking-[0.14em] text-slate-500"></th>
                                    </tr>
                                </thead>
                                <tbody className="divide-y divide-slate-100">
                                    {filtered.map((event) => (
                                        <tr key={event.id} className="transition hover:bg-slate-50/60">
                                            <td className="px-5 py-3.5">
                                                <div className="max-w-xs">
                                                    <p className="text-sm font-semibold text-slate-900 truncate">{event.title}</p>
                                                    <p className="mt-0.5 text-[13px] text-slate-500 truncate">
                                                        {event.location || 'Shared in workspace'} &middot; {event.mode ? event.mode.charAt(0).toUpperCase() + event.mode.slice(1) : 'Hybrid'}
                                                    </p>
                                                </div>
                                            </td>
                                            <td className="px-5 py-3.5 whitespace-nowrap">
                                                <span className={`inline-flex rounded-md px-2 py-0.5 text-[11px] font-semibold uppercase tracking-wider ${registrationTone[event.registration_status]}`}>
                                                    {registrationLabel[event.registration_status]}
                                                </span>
                                            </td>
                                            <td className="px-5 py-3.5 whitespace-nowrap">
                                                <span className={`inline-flex rounded-md px-2 py-0.5 text-[11px] font-semibold uppercase tracking-wider ${journeyColor[event.journey_status]}`}>
                                                    {event.journey_status}
                                                </span>
                                            </td>
                                            <td className="px-5 py-3.5 whitespace-nowrap">
                                                <div className="flex items-center gap-1.5 text-[13px] text-slate-600">
                                                    <CalendarDays size={13} className="text-slate-400 shrink-0" />
                                                    {formatDate(event.start_date)} - {formatDate(event.end_date)}
                                                </div>
                                            </td>
                                            <td className="px-5 py-3.5 whitespace-nowrap text-right">
                                                <div className="flex items-center justify-end gap-1.5">
                                                    <Link href={route('user.events.show', event.slug)}
                                                        className="inline-flex items-center gap-1 rounded-md bg-primary px-3.5 py-2 text-[13px] font-medium text-white transition hover:bg-primary-600 shadow-sm">
                                                        Open <ArrowRight size={13} />
                                                    </Link>
                                                    {event.journey_status !== 'ended' && (
                                                        <button type="button" onClick={() => handleCancelClick(event)}
                                                            className="rounded-md p-1.5 text-slate-400 transition hover:bg-accent-50 hover:text-accent" title="Cancel">
                                                            <X size={14} />
                                                        </button>
                                                    )}
                                                </div>
                                            </td>
                                        </tr>
                                    ))}
                                </tbody>
                            </table>
                        </div>
                    </>
                )}
            </div>

            {showCancelModal && selectedEvent && (
                <div className="fixed inset-0 z-50 flex items-center justify-center bg-slate-900/30 px-4 backdrop-blur-sm">
                    <div className="w-full max-w-md overflow-hidden rounded-lg border border-slate-200 bg-white shadow-lg p-6">
                        <div className="mx-auto flex h-12 w-12 items-center justify-center rounded-lg bg-accent-50 text-accent mb-4">
                            <X size={20} />
                        </div>
                        <h3 className="text-center text-base font-semibold text-slate-900">Cancel registration for {selectedEvent.title}?</h3>
                        <p className="mt-2 text-center text-sm text-slate-500">Cancelling removes you from the attendee journey.</p>
                        <p className="mt-2 text-center text-sm font-medium text-accent">
                            {getRemainingChances(selectedEvent)} cancellation{getRemainingChances(selectedEvent) !== 1 ? 's' : ''} remaining.
                        </p>
                        <form onSubmit={handleCancelEvent} className="mt-5 flex justify-center gap-3">
                            <button type="submit" disabled={isProcessing}
                                className="rounded-md bg-accent px-5 py-2.5 text-sm font-medium text-white transition hover:bg-accent-600 disabled:opacity-50">
                                {isProcessing ? 'Cancelling...' : 'Confirm cancel'}
                            </button>
                            <button type="button" onClick={() => setShowCancelModal(false)}
                                className="rounded-md border border-slate-200 bg-white px-5 py-2.5 text-sm font-medium text-slate-700 transition hover:bg-slate-50">
                                Keep registration
                            </button>
                        </form>
                    </div>
                </div>
            )}
        </DashboardLayout>
    );
}
