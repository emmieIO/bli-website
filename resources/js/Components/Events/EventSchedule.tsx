import { useEffect, useState } from 'react';
import {
    CalendarDays,
    CheckCircle2,
    ChevronDown,
    Clock3,
    ExternalLink,
    MapPin,
    MonitorPlay,
    Radio,
} from 'lucide-react';
import type { EventDayForm } from '@/types/events';

type DayState = 'completed' | 'live' | 'next' | 'scheduled';

export default function EventSchedule({ days, showJoinLinks = false }: {
    days?: EventDayForm[];
    showJoinLinks?: boolean;
}) {
    const [now, setNow] = useState(() => Date.now());

    useEffect(() => {
        const timer = window.setInterval(() => setNow(Date.now()), 60_000);
        return () => window.clearInterval(timer);
    }, []);

    const schedule = [...(days ?? [])].sort((left, right) => (
        new Date(left.start_at).getTime() - new Date(right.start_at).getTime()
    ));
    const nextFutureIndex = schedule.findIndex((day) => new Date(day.start_at).getTime() > now);
    const states = schedule.map((day, index): DayState => {
        const start = new Date(day.start_at).getTime();
        // A following program day closes an open-ended day for timeline purposes.
        const end = day.end_at
            ? new Date(day.end_at).getTime()
            : schedule[index + 1]
                ? new Date(schedule[index + 1].start_at).getTime()
                : null;

        if (now >= start && (end === null || now < end)) return 'live';
        if (end !== null && now >= end) return 'completed';
        if (index === nextFutureIndex) return 'next';
        return 'scheduled';
    });
    const liveIndex = states.findIndex((state) => state === 'live');
    const nextIndex = states.findIndex((state) => state === 'next');
    const focusedIndex = liveIndex >= 0 ? liveIndex : Math.max(nextIndex, 0);
    const [expandedDay, setExpandedDay] = useState<number | null>(focusedIndex);

    if (!schedule.length) return null;

    const venueCount = new Set(
        schedule
            .filter((day) => day.mode !== 'online')
            .map((day) => day.venue_name || day.physical_address)
            .filter(Boolean),
    ).size;
    const modes = Array.from(new Set(schedule.map((day) => day.mode)));
    const timezone = Intl.DateTimeFormat().resolvedOptions().timeZone;

    return (
        <section aria-labelledby="event-schedule-heading">
            <div className="flex flex-col gap-4 border-b border-slate-200 pb-5 sm:flex-row sm:items-end sm:justify-between">
                <div>
                    <p className="text-xs font-semibold uppercase text-slate-500">Program agenda</p>
                    <h2 id="event-schedule-heading" className="mt-1 text-xl font-semibold text-slate-950 sm:text-2xl">
                        Day-by-day schedule
                    </h2>
                    <p className="mt-1 text-sm text-slate-500">Times shown in {timezone}</p>
                </div>
                <div className="flex flex-wrap gap-x-4 gap-y-2 text-sm font-medium text-slate-600">
                    <span>{schedule.length} program {schedule.length === 1 ? 'day' : 'days'}</span>
                    {venueCount > 0 && <span>{venueCount} {venueCount === 1 ? 'venue' : 'venues'}</span>}
                    <span className="capitalize">{modes.join(' + ')}</span>
                </div>
            </div>

            <div className="divide-y divide-slate-200">
                {schedule.map((day, index) => {
                    const venue = [day.venue_name, day.physical_address].filter(Boolean).join(', ');
                    const state = states[index];
                    const isExpanded = expandedDay === index;

                    return (
                        <article
                            key={day.id ?? `${day.start_at}-${index}`}
                            className={`relative py-1 sm:py-5 ${state === 'live' ? 'bg-emerald-50/70' : state === 'next' ? 'bg-amber-50/60' : ''}`}
                        >
                            <button
                                type="button"
                                className="grid w-full grid-cols-[42px_minmax(0,1fr)_auto] items-start gap-3 px-2 py-4 text-left sm:cursor-default sm:grid-cols-[52px_minmax(0,1fr)] sm:px-3 sm:py-0"
                                onClick={() => setExpandedDay(isExpanded ? null : index)}
                                aria-expanded={isExpanded}
                            >
                                <DayMarker number={index + 1} state={state} />
                                <div className="min-w-0">
                                    <div className="flex flex-wrap items-center gap-2">
                                        <h3 className="text-base font-semibold text-slate-950">
                                            {day.title || `Program day ${index + 1}`}
                                        </h3>
                                        <StateLabel state={state} />
                                    </div>
                                    <p className="mt-1 text-sm text-slate-600">
                                        {formatDate(day.start_at)} · {day.end_at
                                            ? `${formatTime(day.start_at)} - ${formatTime(day.end_at)}`
                                            : `Starts ${formatTime(day.start_at)}`}
                                    </p>
                                </div>
                                <ChevronDown
                                    size={18}
                                    className={`mt-1 text-slate-400 transition sm:hidden ${isExpanded ? 'rotate-180' : ''}`}
                                />
                            </button>

                            <div className={`${isExpanded ? 'block' : 'hidden'} px-2 pb-4 pl-[57px] sm:block sm:px-3 sm:pb-0 sm:pl-[67px]`}>
                                {day.theme && <p className="mb-3 text-sm font-semibold text-amber-800">{day.theme}</p>}
                                <div className="grid gap-2 text-sm text-slate-600 sm:grid-cols-2">
                                    <ScheduleFact icon={<CalendarDays size={16} />} value={formatDate(day.start_at)} />
                                    <ScheduleFact
                                        icon={<Clock3 size={16} />}
                                        value={day.end_at
                                            ? `${formatTime(day.start_at)} - ${formatTime(day.end_at)}`
                                            : `Starts at ${formatTime(day.start_at)}`}
                                    />
                                    {venue && <ScheduleFact icon={<MapPin size={16} />} value={venue} />}
                                    <ScheduleFact
                                        icon={<MonitorPlay size={16} />}
                                        value={day.mode === 'offline' ? 'In-person session' : day.mode === 'online' ? 'Online session' : 'Hybrid session'}
                                    />
                                </div>
                                {day.notes && <p className="mt-3 text-sm leading-6 text-slate-600">{day.notes}</p>}
                                {showJoinLinks && day.meeting_link && state !== 'completed' && (
                                    <a
                                        href={day.meeting_link}
                                        target="_blank"
                                        rel="noreferrer"
                                        className="mt-4 inline-flex min-h-10 items-center gap-2 rounded-md bg-primary px-4 text-sm font-semibold text-white transition hover:bg-primary-600"
                                    >
                                        <ExternalLink size={16} />
                                        {state === 'live' ? 'Join session now' : 'Open session link'}
                                    </a>
                                )}
                            </div>
                        </article>
                    );
                })}
            </div>
        </section>
    );
}

function DayMarker({ number, state }: { number: number; state: DayState }) {
    const styles = {
        completed: 'border-slate-200 bg-slate-100 text-slate-500',
        live: 'border-emerald-600 bg-emerald-600 text-white',
        next: 'border-amber-400 bg-amber-50 text-amber-900',
        scheduled: 'border-primary bg-primary text-white',
    };

    return (
        <span className={`flex h-10 w-10 items-center justify-center rounded-md border text-xs font-bold sm:h-11 sm:w-11 ${styles[state]}`}>
            {state === 'completed' ? <CheckCircle2 size={18} /> : state === 'live' ? <Radio size={18} /> : number}
        </span>
    );
}

function StateLabel({ state }: { state: DayState }) {
    if (state === 'scheduled') return null;

    const labels = { completed: 'Completed', live: 'In progress', next: 'Up next' };
    const styles = {
        completed: 'bg-slate-100 text-slate-600',
        live: 'bg-emerald-100 text-emerald-800',
        next: 'bg-amber-100 text-amber-900',
    };

    return <span className={`rounded-md px-2 py-0.5 text-[11px] font-semibold uppercase ${styles[state]}`}>{labels[state]}</span>;
}

function ScheduleFact({ icon, value }: { icon: React.ReactNode; value: string }) {
    return <span className="flex min-w-0 items-start gap-2">{icon}<span className="wrap-break-word">{value}</span></span>;
}

function formatDate(value: string): string {
    return new Date(value).toLocaleDateString('en-NG', { weekday: 'short', day: 'numeric', month: 'short', year: 'numeric' });
}

function formatTime(value: string): string {
    return new Date(value).toLocaleTimeString('en-NG', { hour: 'numeric', minute: '2-digit' });
}
