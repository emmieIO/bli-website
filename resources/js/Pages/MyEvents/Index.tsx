import { Head, Link, router, usePage } from '@inertiajs/react';
import DashboardLayout from '@/Layouts/DashboardLayout';
import { useState, FormEvent } from 'react';
import Button from '@/Components/Button';

interface Event {
    id: number;
    title: string;
    slug: string;
    description?: string;
    start_date: string;
    end_date: string;
    location?: string;
    status: 'upcoming' | 'ongoing' | 'ended';
    pivot?: {
        revoke_count?: number;
    };
}

interface MyEventsProps {
    events: Event[];
}

export default function MyEvents({ events }: MyEventsProps) {
    const { sideLinks } = usePage().props as any;
    const [showCancelModal, setShowCancelModal] = useState(false);
    const [selectedEvent, setSelectedEvent] = useState<Event | null>(null);
    const [isProcessing, setIsProcessing] = useState(false);

    const handleCancelClick = (event: Event) => {
        setSelectedEvent(event);
        setShowCancelModal(true);
    };

    const handleCancelEvent = (e: FormEvent) => {
        e.preventDefault();
        if (!selectedEvent) return;

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

    const formatDate = (dateString: string) => {
        const date = new Date(dateString);
        return date.toLocaleDateString('en-US', {
            year: 'numeric',
            month: 'long',
            day: 'numeric',
        });
    };

    const getRemainingChances = (event: Event) => {
        return 5 - (event.pivot?.revoke_count ?? 0);
    };

    return (
        <DashboardLayout sideLinks={sideLinks}>
            <Head title="My Events - Beacon Leadership Institute" />

            <div className="space-y-8">
                {/* Header Section */}
                <div className="text-center space-y-4">
                    <h1 className="text-3xl font-bold text-primary font-montserrat">My Events</h1>
                    <p className="text-lg text-gray-600 max-w-2xl mx-auto font-lato">
                        Manage your event registrations and stay updated with your schedule
                    </p>
                </div>

                {/* Events Grid */}
                <div className="grid lg:grid-cols-2 xl:grid-cols-3 gap-8">
                    {events.length > 0 ? (
                        events.map((event) => (
                            <EventCard
                                key={event.id}
                                event={event}
                                formatDate={formatDate}
                                onCancelClick={handleCancelClick}
                            />
                        ))
                    ) : (
                        <EmptyState />
                    )}
                </div>

                {/* Browse More Events CTA */}
                {events.length > 0 && (
                    <div className="text-center pt-8 border-t border-primary-50">
                        <div className="max-w-md mx-auto space-y-4">
                            <p className="text-gray-600 font-lato">Looking for more events to join?</p>
                            <Link
                                href={route('events.index')}
                                className="inline-flex items-center gap-3 bg-gradient-to-r from-primary to-primary-600 hover:from-primary-600 hover:to-primary-700 text-white font-semibold px-8 py-4 rounded-xl transition-all duration-200 transform hover:scale-105 shadow-lg hover:shadow-xl font-montserrat"
                            >
                                <i className="fas fa-sparkles w-5 h-5"></i>
                                Explore More Events
                            </Link>
                        </div>
                    </div>
                )}
            </div>

            {/* Cancel Event Modal */}
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

function EventCard({
    event,
    formatDate,
    onCancelClick,
}: {
    event: Event;
    formatDate: (date: string) => string;
    onCancelClick: (event: Event) => void;
}) {
    const getStatusColor = (status: string) => {
        switch (status) {
            case 'upcoming':
                return 'bg-accent text-white shadow-md';
            case 'ongoing':
                return 'bg-blue-500 text-white shadow-md';
            default:
                return 'bg-gray-100 text-gray-700';
        }
    };

    const getStatusDotColor = (status: string) => {
        switch (status) {
            case 'upcoming':
                return 'bg-accent animate-pulse';
            case 'ongoing':
                return 'bg-blue-500 animate-pulse';
            default:
                return 'bg-gray-400';
        }
    };

    return (
        <div className="group bg-white border border-primary-50 shadow-sm rounded-2xl p-6 flex flex-col justify-between space-y-5 hover:shadow-2xl hover:border-primary-100 transition-all duration-300 transform hover:-translate-y-1">
            {/* Header with Status */}
            <div className="space-y-4">
                <div className="flex items-center justify-between">
                    <span
                        className={`text-xs font-semibold px-3 py-1.5 rounded-full font-montserrat capitalize ${getStatusColor(
                            event.status
                        )}`}
                    >
                        {event.status}
                    </span>
                    <div className={`w-2 h-2 rounded-full ${getStatusDotColor(event.status)}`}></div>
                </div>

                {/* Title */}
                <h3 className="text-xl font-bold text-primary font-montserrat leading-tight group-hover:text-primary-600 transition-colors">
                    {event.title}
                </h3>
            </div>

            {/* Event Details */}
            <div className="space-y-3">
                <div className="flex items-center gap-3 text-primary-700">
                    <i className="fas fa-calendar w-4 h-4 text-primary-500"></i>
                    <span className="font-lato font-medium">{formatDate(event.start_date)}</span>
                </div>
                <div className="flex items-center gap-3 text-primary-700">
                    <i className="fas fa-map-marker-alt w-4 h-4 text-primary-500"></i>
                    <span className="font-lato">{event.location || 'Location TBA'}</span>
                </div>
            </div>

            {/* Actions */}
            <div className="flex justify-between items-center pt-4 border-t border-primary-100 mt-2">
                <Link
                    href={route('events.show', event.slug)}
                    className="inline-flex items-center gap-2 text-sm text-primary hover:text-primary-600 font-medium font-lato transition-colors group-hover:underline"
                >
                    <i className="fas fa-external-link-alt w-4 h-4"></i>
                    View Details
                </Link>

                <button
                    onClick={() => onCancelClick(event)}
                    disabled={event.status === 'ended'}
                    className="inline-flex items-center gap-2 text-sm font-medium text-red-600 hover:text-red-700 disabled:text-gray-400 disabled:cursor-not-allowed transition-all duration-200 font-lato group-hover:scale-105"
                >
                    <i className="fas fa-times-circle w-4 h-4"></i>
                    Cancel
                </button>
            </div>
        </div>
    );
}

function EmptyState() {
    return (
        <div className="col-span-3 text-center py-16 px-8 bg-gradient-to-br from-primary-50 to-white rounded-2xl border-2 border-dashed border-primary-100">
            <div className="max-w-md mx-auto space-y-6">
                <div className="w-20 h-20 mx-auto bg-primary-100 rounded-2xl flex items-center justify-center">
                    <i className="fas fa-calendar-times w-10 h-10 text-primary-400"></i>
                </div>
                <div className="space-y-3">
                    <h3 className="text-2xl font-bold text-primary font-montserrat">No Events Yet</h3>
                    <p className="text-gray-600 font-lato leading-relaxed">
                        You haven't registered for any events yet. Explore our upcoming events and join the community!
                    </p>
                </div>
                <Link
                    href={route('events.index')}
                    className="inline-flex items-center gap-3 bg-primary hover:bg-primary-600 text-white font-semibold px-8 py-4 rounded-xl transition-all duration-200 transform hover:scale-105 shadow-lg hover:shadow-xl font-montserrat"
                >
                    <i className="fas fa-calendar-plus w-5 h-5"></i>
                    Browse All Events
                </Link>
            </div>
        </div>
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
        <div className="fixed inset-0 z-50 flex items-center justify-center bg-black/30">
            <div className="relative bg-white rounded-lg shadow-xl p-6 w-full max-w-md mx-4">
                <button
                    type="button"
                    onClick={onClose}
                    className="absolute top-3 right-3 text-gray-400 hover:bg-gray-200 hover:text-gray-900 rounded-lg text-sm w-8 h-8 inline-flex justify-center items-center"
                >
                    <i className="fas fa-times w-3 h-3"></i>
                </button>

                <div className="text-center">
                    <div className="mx-auto mb-4 w-12 h-12 text-red-600 flex items-center justify-center">
                        <i className="fas fa-exclamation-triangle w-12 h-12"></i>
                    </div>

                    <div className="mb-5 space-y-2">
                        <h3 className="text-lg text-gray-800 font-montserrat font-semibold">
                            Are you sure you want to unregister from:{' '}
                            <span className="font-bold text-primary">{event.title}</span>?
                        </h3>
                        <p className="font-lato text-sm text-accent font-bold">
                            Staying registered enables us send you notifications & reminders on this event.
                        </p>
                        {remainingChances < 5 && (
                            <p className="font-lato text-sm text-red-600 font-medium">
                                You have {remainingChances} cancellation{remainingChances !== 1 ? 's' : ''} remaining.
                            </p>
                        )}
                    </div>

                    <form onSubmit={onConfirm} className="flex justify-center gap-3">
                        <Button type="submit" variant="danger" loading={isProcessing}>
                            Yes, I'm sure
                        </Button>
                        <Button type="button" variant="secondary" onClick={onClose}>
                            No, cancel
                        </Button>
                    </form>
                </div>
            </div>
        </div>
    );
}
