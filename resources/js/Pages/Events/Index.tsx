import { Head, Link, router } from '@inertiajs/react';
import GuestLayout from '@/Layouts/GuestLayout';
import { useState, FormEvent } from 'react';
import { CalendarDays, Globe, MapPin, Search } from 'lucide-react';

interface Event {
    id: number;
    slug: string;
    title: string;
    theme: string;
    program_cover: string;
    start_date: string;
    end_date: string;
    mode?: 'online' | 'offline' | 'hybrid';
    physical_address?: string;
    entry_fee: number;
    slots_remaining?: number | null;
    public_segment: 'live_now' | 'open_registration' | 'waitlist_open' | 'announced' | 'registration_closed' | 'completed';
    public_status_label: string;
    availability_note: string;
}

interface PaginatedEvents {
    data: Event[];
    links: Array<{ url: string | null; label: string; active: boolean; }>;
}

interface EventsIndexProps {
    events: PaginatedEvents;
    searchQuery?: string | null;
}

const segmentBadgeStyles: Record<Event['public_segment'], string> = {
    live_now: 'bg-accent text-white',
    open_registration: 'bg-lime-500 text-primary',
    waitlist_open: 'bg-primary text-white',
    announced: 'bg-slate-700 text-white',
    registration_closed: 'bg-zinc-600 text-white',
    completed: 'bg-slate-500 text-white',
};

export default function EventsIndex({ events, searchQuery: initialSearchQuery = '' }: EventsIndexProps) {
    const [searchQuery, setSearchQuery] = useState(initialSearchQuery);
    const [filterStatus, setFilterStatus] = useState('');
    const [isSearching, setIsSearching] = useState(false);

    const handleSearch = (e: FormEvent) => {
        e.preventDefault();
        setIsSearching(true);
        router.get(route('events.index'), {
            q: searchQuery || undefined,
            status: filterStatus || undefined,
        }, {
            preserveState: true,
            onFinish: () => setIsSearching(false),
        });
    };

    const formatDate = (dateString: string) => {
        return new Date(dateString).toLocaleDateString('en-US', { month: 'short', day: 'numeric', year: 'numeric' });
    };

    const getModeIcon = (mode?: string) => {
        switch (mode) {
            case 'online': return <Globe size={14} className="text-accent" />;
            case 'offline': return <MapPin size={14} className="text-accent" />;
            default: return <Globe size={14} className="text-accent" />;
        }
    };

    return (
        <GuestLayout>
            <Head title="Events" />

            <section className="bg-white py-8 md:py-14">
                <div className="section-shell">
                    <div className="mb-14">
                        <div className="inline-flex items-center gap-1.5 rounded-md bg-accent-50 px-2.5 py-1 text-[11px] font-semibold uppercase tracking-wider text-accent">
                            <CalendarDays size={12} /> Public Event Registry
                        </div>
                        <h1 className="mt-3 text-3xl font-bold tracking-tight text-primary md:text-4xl">
                            Explore Events
                        </h1>
                        <p className="mt-3 max-w-xl text-sm leading-7 text-slate-500 md:text-base">
                            Browse public events by actual availability — live now, open registration, waitlist, and more.
                        </p>
                    </div>

                    <form onSubmit={handleSearch} className="mb-4">
                        <div className="flex gap-3">
                            <div className="relative flex-1">
                                <Search size={16} className="absolute left-3.5 top-1/2 -translate-y-1/2 text-slate-400" />
                                <input
                                    type="search"
                                    value={searchQuery}
                                    onChange={(e) => setSearchQuery(e.target.value)}
                                    placeholder="Search events, topics, or locations..."
                                    className="h-11 w-full rounded-md border border-slate-300 pl-10 pr-4 text-sm text-slate-900 placeholder:text-slate-400 outline-none transition focus:border-primary-300 focus:ring-2 focus:ring-primary-500/10"
                                />
                            </div>
                            <select
                                value={filterStatus}
                                onChange={(e) => {
                                    setFilterStatus(e.target.value);
                                    router.get(route('events.index'), {
                                        q: searchQuery || undefined,
                                        status: e.target.value || undefined,
                                    }, { preserveState: true });
                                }}
                                className="h-11 rounded-md border border-slate-300 bg-white px-3 text-sm text-slate-700 outline-none transition focus:border-primary-300 focus:ring-2 focus:ring-primary-500/10"
                            >
                                <option value="">All statuses</option>
                                <option value="live_now">Live Now</option>
                                <option value="open_registration">Open Registration</option>
                                <option value="waitlist_open">Waitlist Open</option>
                                <option value="announced">Announced</option>
                                <option value="registration_closed">Registration Closed</option>
                                <option value="completed">Completed</option>
                            </select>
                            <button type="submit" disabled={isSearching}
                                className="inline-flex items-center gap-2 rounded-md bg-primary px-5 py-2.5 text-sm font-semibold text-white transition hover:bg-primary-600 disabled:opacity-50 shadow-sm">
                                {isSearching ? 'Searching...' : 'Filter'}
                            </button>
                        </div>
                    </form>

                    <p className="mb-8 text-sm text-slate-400">
                        Showing <span className="font-semibold text-slate-700">{events.data.length}</span> event{events.data.length !== 1 ? 's' : ''}
                        {(initialSearchQuery || filterStatus) && <> matching your filters</>}
                    </p>

                    {events.data && events.data.length > 0 ? (
                        <div className="grid gap-5 md:grid-cols-2 lg:grid-cols-3">
                            {events.data.map((event) => (
                                <div key={event.id}
                                    className="group flex flex-col overflow-hidden rounded-lg border border-slate-200 bg-white transition hover:-translate-y-1 hover:border-slate-300 hover:shadow-sm">
                                    <div className="relative h-48 overflow-hidden bg-slate-100">
                                        <img
                                                        src={`/storage/${event.program_cover}`}
                                                        alt={event.title}
                                                        className="h-full w-full object-cover transition duration-500 group-hover:scale-105"
                                                        onError={(e) => { (e.target as HTMLImageElement).src = 'https://placehold.co/600x400?text=Cover'; }}
                                                    />
                                                    <div className="absolute left-3 top-3">
                                                        <span className={`inline-flex rounded-md px-2.5 py-1 text-[10px] font-bold uppercase tracking-wider ${segmentBadgeStyles[event.public_segment]}`}>
                                                            {event.public_status_label}
                                                        </span>
                                                    </div>
                                                    <div className="absolute right-3 top-3">
                                                        <span className={`inline-flex rounded-md px-2.5 py-1 text-xs font-bold ${event.entry_fee > 0 ? 'bg-white text-primary shadow-sm' : 'bg-accent text-white'}`}>
                                                            {event.entry_fee > 0 ? `₦${event.entry_fee.toLocaleString()}` : 'FREE'}
                                                        </span>
                                                    </div>
                                                </div>

                                                <div className="flex flex-1 flex-col p-6">
                                                    <div className="flex items-center gap-3 text-[11px] font-semibold uppercase tracking-wider text-slate-400 mb-3">
                                                        <span className="flex items-center gap-1.5">
                                                            <CalendarDays size={12} /> {formatDate(event.start_date)}
                                                        </span>
                                                        <span className="text-slate-300">·</span>
                                                        <span className="flex items-center gap-1.5 capitalize">
                                                            {getModeIcon(event.mode)} {event.mode || 'Hybrid'}
                                                        </span>
                                                    </div>

                                                    <h3 className="text-lg font-semibold leading-snug text-slate-900 transition group-hover:text-accent line-clamp-2">
                                                        <Link href={route('events.show', event.slug)}>{event.title}</Link>
                                                    </h3>

                                                    <p className="mt-1.5 text-sm font-medium text-accent line-clamp-1">{event.theme}</p>
                                                    <p className="mt-2.5 text-sm leading-relaxed text-slate-500 line-clamp-2">{event.availability_note}</p>

                                                    <div className="mt-auto flex items-center justify-between border-t border-slate-100 pt-4">
                                                        <span className="text-[13px] text-slate-500">
                                                            {event.slots_remaining === null ? 'Unlimited seating' :
                                                             event.slots_remaining === 0 ? 'Currently full' :
                                                             `${event.slots_remaining} slots remaining`}
                                                        </span>
                                                        <Link href={route('events.show', event.slug)}
                                                            className="text-[13px] font-medium text-accent transition group-hover:translate-x-1 inline-flex items-center gap-1">
                                                            View details &rarr;
                                                        </Link>
                                                    </div>
                                                </div>
                                            </div>
                                        ))}
                        </div>
                    ) : (
                        <div className="py-16 text-center">
                            <div className="mx-auto flex h-14 w-14 items-center justify-center rounded-lg bg-slate-100 text-slate-300">
                                <CalendarDays size={24} />
                            </div>
                            <h3 className="mt-4 text-lg font-semibold text-slate-900">No Events Available</h3>
                            <p className="mt-2 text-sm text-slate-500">
                                We're preparing new gatherings. Check back soon.
                            </p>
                            <Link href={route('homepage')} className="mt-5 inline-flex items-center gap-2 rounded-md bg-primary px-5 py-2.5 text-sm font-medium text-white transition hover:bg-primary-600 shadow-sm">
                                Back to Home
                            </Link>
                        </div>
                    )}
                </div>
            </section>
        </GuestLayout>
    );
}
