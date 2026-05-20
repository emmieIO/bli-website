import { Head, Link, router } from '@inertiajs/react';
import GuestLayout from '@/Layouts/GuestLayout';
import { useState, FormEvent } from 'react';

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
    current_page: number;
    last_page: number;
    per_page: number;
    total: number;
    links: Array<{
        url: string | null;
        label: string;
        active: boolean;
    }>;
}

interface EventSection {
    key: Event['public_segment'];
    title: string;
    description: string;
    events: Event[];
}

interface EventsIndexProps {
    events: PaginatedEvents;
    searchQuery?: string | null;
    segmentCounts: Record<Event['public_segment'], number>;
    sections: EventSection[];
}

export default function EventsIndex({ events, searchQuery: initialSearchQuery = '', segmentCounts, sections }: EventsIndexProps) {
    const [searchQuery, setSearchQuery] = useState(initialSearchQuery);
    const [isSearching, setIsSearching] = useState(false);

    const handleSearch = (e: FormEvent) => {
        e.preventDefault();
        setIsSearching(true);
        router.get(route('events.index'), { q: searchQuery }, {
            preserveState: true,
            onFinish: () => setIsSearching(false),
        });
    };

    const formatDate = (dateString: string) => {
        const date = new Date(dateString);
        return date.toLocaleDateString('en-US', { month: 'short', day: 'numeric', year: 'numeric' });
    };

    const getModeIcon = (mode?: string) => {
        switch (mode) {
            case 'online':
                return <i className="fas fa-globe text-sm text-accent"></i>;
            case 'offline':
            case 'onsite':
                return <i className="fas fa-map-marker-alt text-sm text-accent"></i>;
            default:
                return <i className="fas fa-laptop text-sm text-accent"></i>;
        }
    };

    return (
        <GuestLayout>
            <Head title="Events" />

            {/* Hero Section */}
            <section className="public-hero">
                <div className="section-shell text-center">
                    {/* Badge */}
                    <div className="mb-8 inline-flex items-center gap-2 rounded-full border border-accent/15 bg-accent/5 px-6 py-3">
                        <i className="fas fa-calendar-alt text-sm text-accent"></i>
                        <span className="text-sm font-medium tracking-wide text-primary">
                            Public Event Registry
                        </span>
                    </div>

                    {/* Main Heading */}
                    <h1 className="public-hero-title mb-6">
                        Explore <span className="text-accent">Events</span>
                    </h1>

                    <p className="public-hero-copy mx-auto mb-12">
                        Browse public events by actual availability: live now, open registration, waitlist, announced, closed, and completed visibility.
                    </p>

                    {/* Search */}
                    <form onSubmit={handleSearch} className="max-w-2xl mx-auto mb-12">
                        <div className="public-card p-6">
                            <div className="flex flex-col md:flex-row gap-4">
                                <div className="flex-1 relative">
                                    <i className="fas fa-search absolute left-4 top-1/2 transform -translate-y-1/2 text-gray-400"></i>
                                    <input
                                        type="search"
                                        value={searchQuery}
                                        onChange={(e) => setSearchQuery(e.target.value)}
                                        placeholder="Search events, topics, or locations..."
                                        className="public-input pl-12"
                                    />
                                </div>
                                <button
                                    type="submit"
                                    disabled={isSearching}
                                    className="enterprise-button enterprise-button-primary disabled:cursor-not-allowed disabled:opacity-50"
                                >
                                    <i className={`mr-2 fas ${isSearching ? 'fa-spinner animate-spin' : 'fa-search'}`}></i>
                                    {isSearching ? 'Searching...' : 'Search'}
                                </button>
                            </div>
                        </div>
                    </form>

                    {/* Summary */}
                    <div className="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-4 gap-6 max-w-6xl mx-auto">
                        <PublicSummary title="Live Now" value={segmentCounts.live_now} tone="secondary" />
                        <PublicSummary title="Open Registration" value={segmentCounts.open_registration} tone="accent" />
                        <PublicSummary title="Waitlist Open" value={segmentCounts.waitlist_open} tone="primary" />
                        <PublicSummary title="Closed / Completed" value={segmentCounts.registration_closed + segmentCounts.completed} tone="slate" />
                    </div>
                </div>
            </section>

            {/* Events Grid Section */}
            <section className="public-section bg-white">
                <div className="section-shell">
                    {events.data && events.data.length > 0 ? (
                        <div className="space-y-14">
                            {sections.map((section) => (
                                <div key={section.key} className="space-y-6">
                                    <div className="flex flex-col gap-3 md:flex-row md:items-end md:justify-between">
                                        <div>
                                            <h2 className="text-2xl font-bold font-montserrat text-primary">{section.title}</h2>
                                            <p className="mt-2 max-w-3xl text-sm leading-7 text-gray-600 font-lato">
                                                {section.description}
                                            </p>
                                        </div>
                                        <span className="inline-flex rounded-full border border-slate-200 bg-slate-50 px-4 py-2 text-xs font-semibold uppercase tracking-[0.16em] text-slate-600">
                                            {section.events.length} event{section.events.length === 1 ? '' : 's'}
                                        </span>
                                    </div>

                                    <div className="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                                        {section.events.map((event) => (
                                            <div
                                                key={event.id}
                                                className="public-card group overflow-hidden transition-all duration-500 hover:-translate-y-1 hover:border-accent/20 hover:shadow-2xl"
                                            >
                                                <div className="relative h-48 overflow-hidden">
                                                    <img
                                                        src={`/storage/${event.program_cover}`}
                                                        alt={event.title}
                                                        className="w-full h-full object-cover group-hover:scale-110 transition-transform duration-500"
                                                        onError={(e) => {
                                                            (e.target as HTMLImageElement).src = 'https://placehold.co/600x400?text=Cover+Image+Missing';
                                                        }}
                                                    />

                                                    <div className="absolute top-4 left-4">
                                                        <span className={`px-3 py-1.5 text-xs font-semibold rounded-full text-white font-montserrat ${segmentBadgeClass(event.public_segment)}`}>
                                                            {event.public_status_label}
                                                        </span>
                                                    </div>

                                                    <div className="absolute top-4 right-4">
                                                        {event.entry_fee > 0 ? (
                                                            <span className="px-3 py-1.5 text-sm font-bold rounded-full bg-white shadow-md font-montserrat text-primary">
                                                                ₦{event.entry_fee.toLocaleString()}
                                                            </span>
                                                        ) : (
                                                            <span className="px-3 py-1.5 text-sm font-bold rounded-full bg-white shadow-md font-montserrat text-secondary">
                                                                FREE
                                                            </span>
                                                        )}
                                                    </div>
                                                </div>

                                                <div className="p-6">
                                                    <h3 className="mb-3 line-clamp-2 text-xl font-bold text-primary transition-colors duration-300 group-hover:text-accent">
                                                        <Link href={route('events.show', event.slug)}>{event.title}</Link>
                                                    </h3>

                                                    <p className="mb-3 line-clamp-2 text-sm font-semibold text-accent">
                                                        {event.theme}
                                                    </p>
                                                    <p className="mb-5 text-sm leading-6 text-gray-600 font-lato">
                                                        {event.availability_note}
                                                    </p>

                                                    <div className="space-y-3 mb-6">
                                                        <div className="flex items-center gap-3 text-sm text-gray-600">
                                                            <i className="fas fa-calendar text-sm text-accent"></i>
                                                            <span className="font-lato">
                                                                {formatDate(event.start_date)} - {formatDate(event.end_date)}
                                                            </span>
                                                        </div>

                                                        <div className="flex items-center gap-3 text-sm text-gray-600">
                                                            {getModeIcon(event.mode)}
                                                            <span className="font-lato capitalize">{event.mode || 'Hybrid'}</span>
                                                        </div>

                                                        {event.slots_remaining !== undefined && (
                                                            <div className="flex items-center gap-3 text-sm text-gray-600">
                                                                <i className="fas fa-users text-sm text-accent"></i>
                                                                <span className="font-lato">
                                                                    {event.slots_remaining === null ? 'Unlimited seating' : event.slots_remaining === 0 ? 'Currently full' : `${event.slots_remaining} slots remaining`}
                                                                </span>
                                                            </div>
                                                        )}
                                                    </div>

                                                    <Link
                                                        href={route('events.show', event.slug)}
                                                        className="enterprise-button enterprise-button-primary w-full"
                                                    >
                                                        <span>View Details</span>
                                                        <i className="fas fa-arrow-right text-sm group-hover:translate-x-1 transition-transform"></i>
                                                    </Link>
                                                </div>
                                            </div>
                                        ))}
                                    </div>
                                </div>
                            ))}
                        </div>
                    ) : (
                        /* Empty State */
                        <div className="text-center py-20">
                            <div className="max-w-md mx-auto">
                                <div className="mb-8">
                                    <i className="fas fa-calendar-alt text-6xl text-gray-300"></i>
                                </div>
                                <h3 className="text-2xl font-bold mb-4 font-montserrat text-primary">No Events Available</h3>
                                <p className="text-gray-600 font-lato mb-8 leading-relaxed">
                                    We're preparing new transformative gatherings and kingdom-focused events. Check back soon for exciting
                                    opportunities to grow and connect!
                                </p>
                                <Link
                                    href={route('homepage')}
                                    className="enterprise-button enterprise-button-primary"
                                >
                                    <i className="fas fa-home"></i>
                                    <span>Back to Home</span>
                                </Link>
                            </div>
                        </div>
                    )}
                </div>
            </section>
        </GuestLayout>
    );
}

function PublicSummary({ title, value, tone }: { title: string; value: number; tone: 'secondary' | 'accent' | 'primary' | 'slate' }) {
    const toneClass = tone === 'secondary'
        ? 'bg-secondary/10 text-secondary'
        : tone === 'accent'
            ? 'bg-accent/10 text-accent'
            : tone === 'primary'
                ? 'bg-primary/10 text-primary'
                : 'bg-slate-100 text-slate-700';

    return (
        <div className="public-stat-card transition-all duration-300 hover:shadow-xl">
            <div className="flex items-center justify-center gap-3 mb-4">
                <div className={`w-12 h-12 rounded-2xl flex items-center justify-center ${toneClass}`}>
                    <i className="fas fa-layer-group text-xl"></i>
                </div>
            </div>
            <div className="font-bold text-3xl mb-2 font-montserrat text-primary">{value}</div>
            <div className="text-gray-600 font-lato font-semibold">{title}</div>
        </div>
    );
}

function segmentBadgeClass(segment: Event['public_segment']) {
    return segment === 'live_now'
        ? 'bg-secondary'
        : segment === 'open_registration'
            ? 'bg-accent'
            : segment === 'waitlist_open'
                ? 'bg-primary'
                : segment === 'announced'
                    ? 'bg-slate-700'
                    : segment === 'registration_closed'
                        ? 'bg-zinc-700'
                        : 'bg-gray-600';
}
