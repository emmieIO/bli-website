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
    slots_remaining?: number;
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

interface EventsIndexProps {
    events: PaginatedEvents;
    upcomingEvents: number;
    ongoingEvents: number;
    expiredEvents: number;
}

export default function EventsIndex({ events, upcomingEvents, ongoingEvents, expiredEvents }: EventsIndexProps) {
    const [searchQuery, setSearchQuery] = useState('');

    const handleSearch = (e: FormEvent) => {
        e.preventDefault();
        router.get(route('events.index'), { q: searchQuery }, { preserveState: true });
    };

    const formatDate = (dateString: string) => {
        const date = new Date(dateString);
        return date.toLocaleDateString('en-US', { month: 'short', day: 'numeric', year: 'numeric' });
    };

    const getEventStatus = (startDate: string, endDate: string) => {
        const now = new Date();
        const start = new Date(startDate);
        const end = new Date(endDate);

        if (now >= start && now <= end) {
            return 'live';
        } else if (now < start) {
            return 'upcoming';
        } else {
            return 'past';
        }
    };

    const getModeIcon = (mode?: string) => {
        switch (mode) {
            case 'online':
                return <i className="fas fa-globe text-sm text-accent"></i>;
            case 'offline':
                return <i className="fas fa-map-marker-alt text-sm text-accent"></i>;
            default:
                return <i className="fas fa-laptop text-sm text-accent"></i>;
        }
    };

    return (
        <GuestLayout>
            <Head title="Upcoming Events" />

            {/* Hero Section */}
            <section className="py-16 md:py-20 bg-linear-to-br from-white to-gray-50">
                <div className="container mx-auto px-6 text-center">
                    {/* Badge */}
                    <div className="inline-flex items-center gap-2 border rounded-full px-6 py-3 mb-8 bg-accent/10 border-accent">
                        <i className="fas fa-calendar-alt text-sm text-accent"></i>
                        <span className="font-medium font-montserrat text-sm tracking-wide text-primary">
                            Transformational Gatherings
                        </span>
                    </div>

                    {/* Main Heading */}
                    <h1 className="font-bold font-montserrat text-4xl md:text-5xl lg:text-6xl mb-6 leading-tight text-primary">
                        Upcoming <span className="text-accent">Events</span>
                    </h1>

                    <p className="text-xl text-gray-600 mb-12 max-w-3xl mx-auto leading-relaxed font-lato">
                        Join our transformational programs, prophetic workshops, and kingdom-focused gatherings designed to equip and
                        empower leaders.
                    </p>

                    {/* Search */}
                    <form onSubmit={handleSearch} className="max-w-2xl mx-auto mb-12">
                        <div className="bg-white rounded-2xl shadow-lg p-6 border border-gray-100">
                            <div className="flex flex-col md:flex-row gap-4">
                                <div className="flex-1 relative">
                                    <i className="fas fa-search absolute left-4 top-1/2 transform -translate-y-1/2 text-gray-400"></i>
                                    <input
                                        type="search"
                                        value={searchQuery}
                                        onChange={(e) => setSearchQuery(e.target.value)}
                                        placeholder="Search events, topics, or locations..."
                                        className="w-full pl-12 pr-4 py-3 rounded-xl border border-gray-200 focus:outline-none focus:ring-2 focus:ring-accent focus:border-transparent font-lato"
                                    />
                                </div>
                                <button
                                    type="submit"
                                    className="px-8 py-3 rounded-xl font-semibold transition-all duration-300 shadow-md hover:shadow-lg transform hover:scale-105 text-white font-montserrat bg-accent hover:bg-accent-700"
                                >
                                    <i className="fas fa-search mr-2"></i>
                                    Search
                                </button>
                            </div>
                        </div>
                    </form>

                    {/* Stats */}
                    <div className="grid grid-cols-1 md:grid-cols-3 gap-6 max-w-4xl mx-auto">
                        <div className="bg-white rounded-2xl shadow-lg border border-gray-100 p-6 hover:shadow-xl transition-all duration-300">
                            <div className="flex items-center justify-center gap-3 mb-4">
                                <div className="w-12 h-12 rounded-2xl flex items-center justify-center bg-secondary/10">
                                    <i className="fas fa-broadcast-tower text-xl text-secondary"></i>
                                </div>
                            </div>
                            <div className="font-bold text-3xl mb-2 font-montserrat text-secondary">{ongoingEvents}</div>
                            <div className="text-gray-600 font-lato font-semibold">Happening Now</div>
                        </div>

                        <div className="bg-white rounded-2xl shadow-lg border border-gray-100 p-6 hover:shadow-xl transition-all duration-300">
                            <div className="flex items-center justify-center gap-3 mb-4">
                                <div className="w-12 h-12 rounded-2xl flex items-center justify-center bg-accent/10">
                                    <i className="fas fa-calendar-alt text-xl text-accent"></i>
                                </div>
                            </div>
                            <div className="font-bold text-3xl mb-2 font-montserrat text-accent">{upcomingEvents}</div>
                            <div className="text-gray-600 font-lato font-semibold">Coming Soon</div>
                        </div>

                        <div className="bg-white rounded-2xl shadow-lg border border-gray-100 p-6 hover:shadow-xl transition-all duration-300">
                            <div className="flex items-center justify-center gap-3 mb-4">
                                <div className="w-12 h-12 rounded-2xl flex items-center justify-center bg-primary/10">
                                    <i className="fas fa-history text-xl text-primary"></i>
                                </div>
                            </div>
                            <div className="font-bold text-3xl mb-2 font-montserrat text-primary">{expiredEvents}</div>
                            <div className="text-gray-600 font-lato font-semibold">Past Events</div>
                        </div>
                    </div>
                </div>
            </section>

            {/* Events Grid Section */}
            <section className="py-20 bg-white">
                <div className="container mx-auto px-6">
                    {events.data && events.data.length > 0 ? (
                        <div className="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                            {events.data.map((event, index) => {
                                const status = getEventStatus(event.start_date, event.end_date);

                                return (
                                    <div
                                        key={event.id}
                                        className="group bg-white rounded-2xl shadow-lg hover:shadow-2xl transition-all duration-500 overflow-hidden border border-gray-100 hover:border-accent/20"
                                    >
                                        {/* Event Image */}
                                        <div className="relative h-48 overflow-hidden">
                                            <img
                                                src={`/storage/${event.program_cover}`}
                                                alt={event.title}
                                                className="w-full h-full object-cover group-hover:scale-110 transition-transform duration-500"
                                            />

                                            {/* Event Status Badge */}
                                            <div className="absolute top-4 left-4">
                                                {status === 'live' && (
                                                    <span className="px-3 py-1.5 text-xs font-semibold rounded-full text-white font-montserrat flex items-center gap-2 bg-secondary">
                                                        <span className="w-2 h-2 bg-white rounded-full animate-pulse"></span>
                                                        Live Now
                                                    </span>
                                                )}
                                                {status === 'upcoming' && (
                                                    <span className="px-3 py-1.5 text-xs font-semibold rounded-full text-white font-montserrat bg-accent">
                                                        Coming Soon
                                                    </span>
                                                )}
                                                {status === 'past' && (
                                                    <span className="px-3 py-1.5 text-xs font-semibold rounded-full bg-gray-600 text-white font-montserrat">
                                                        Past Event
                                                    </span>
                                                )}
                                            </div>

                                            {/* Price Badge */}
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

                                        {/* Event Content */}
                                        <div className="p-6">
                                            {/* Event Title */}
                                            <h3 className="text-xl font-bold mb-3 font-montserrat line-clamp-2 group-hover:text-accent transition-colors duration-300 text-primary">
                                                <Link href={route('events.show', event.slug)}>{event.title}</Link>
                                            </h3>

                                            {/* Event Theme */}
                                            <p className="font-semibold text-sm mb-4 line-clamp-2 font-montserrat text-accent">
                                                {event.theme}
                                            </p>

                                            {/* Event Details */}
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
                                                        <span className="font-lato">{event.slots_remaining} slots remaining</span>
                                                    </div>
                                                )}
                                            </div>

                                            {/* Action Button */}
                                            <Link
                                                href={route('events.show', event.slug)}
                                                className="w-full flex items-center justify-center gap-2 font-semibold py-3 px-4 rounded-xl transition-all duration-300 shadow-md hover:shadow-lg transform hover:scale-105 text-white font-montserrat bg-primary hover:bg-primary"
                                            >
                                                <span>View Details</span>
                                                <i className="fas fa-arrow-right text-sm group-hover:translate-x-1 transition-transform"></i>
                                            </Link>
                                        </div>
                                    </div>
                                );
                            })}
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
                                    className="inline-flex items-center gap-2 font-semibold px-6 py-3 rounded-xl transition-all duration-300 shadow-md hover:shadow-lg text-white font-montserrat bg-accent"
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
