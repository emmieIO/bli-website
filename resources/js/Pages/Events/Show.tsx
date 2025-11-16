import { Head, Link, router, usePage } from '@inertiajs/react';
import GuestLayout from '@/Layouts/GuestLayout';
import { useState, useEffect, FormEvent } from 'react';

interface User {
    id: number;
    name: string;
    email: string;
    photo?: string;
    headline?: string;
}

interface Speaker {
    id: number;
    user: User;
    bio?: string;
}

interface Resource {
    id: number;
    title: string;
    type: 'file' | 'link';
    file_path?: string;
    external_link?: string;
}

interface Event {
    id: number;
    slug: string;
    title: string;
    theme: string;
    description: string;
    program_cover: string;
    start_date: string;
    end_date: string;
    mode?: 'online' | 'offline' | 'hybrid';
    physical_address?: string;
    location?: string;
    entry_fee: number;
    slots_remaining?: number;
    is_allowing_application: boolean;
    speakers?: Speaker[];
    resources?: Resource[];
    is_registered?: boolean;
    revoke_count?: number;
}

interface EventShowProps {
    event: Event;
    auth?: {
        user?: User;
    };
    signed_speaker_route?: string;
}

export default function EventShow({ event, auth, signed_speaker_route }: EventShowProps) {
    const [showModal, setShowModal] = useState(false);
    const [isSubmitting, setIsSubmitting] = useState(false);
    const [countdown, setCountdown] = useState('');

    const now = new Date();
    const startDate = new Date(event.start_date);
    const endDate = new Date(event.end_date);
    const isLive = now >= startDate && now <= endDate;
    const isUpcoming = now < startDate;
    const isPast = now > endDate;

    // Countdown timer for upcoming events
    useEffect(() => {
        if (!isUpcoming) return;

        const updateCountdown = () => {
            const now = new Date().getTime();
            const start = new Date(event.start_date).getTime();
            const distance = start - now;

            if (distance < 0) {
                setCountdown('Event has started!');
                return;
            }

            const days = Math.floor(distance / (1000 * 60 * 60 * 24));
            const hours = Math.floor((distance % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
            const minutes = Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60));
            const seconds = Math.floor((distance % (1000 * 60)) / 1000);

            setCountdown(`${days}d ${hours}h ${minutes}m ${seconds}s`);
        };

        updateCountdown();
        const interval = setInterval(updateCountdown, 1000);

        return () => clearInterval(interval);
    }, [event.start_date, isUpcoming]);

    const formatDate = (dateString: string) => {
        const date = new Date(dateString);
        return date.toLocaleDateString('en-US', { weekday: 'long', month: 'long', day: 'numeric', year: 'numeric' });
    };

    const formatTime = (dateString: string) => {
        const date = new Date(dateString);
        return date.toLocaleTimeString('en-US', { hour: 'numeric', minute: '2-digit', hour12: true });
    };

    const getModeIcon = (mode?: string) => {
        switch (mode) {
            case 'online':
                return <i className="fas fa-globe text-xs mr-1"></i>;
            case 'offline':
                return <i className="fas fa-map-marker-alt text-xs mr-1"></i>;
            default:
                return <i className="fas fa-laptop text-xs mr-1"></i>;
        }
    };

    const handleRegistration = (e: FormEvent) => {
        e.preventDefault();
        setIsSubmitting(true);

        router.post(route('events.join', event.slug), {}, {
            onFinish: () => {
                setIsSubmitting(false);
                setShowModal(false);
            },
        });
    };

    const slotsRemaining = event.slots_remaining ?? 0;
    const isRegistered = event.is_registered ?? false;
    const revokeCount = event.revoke_count ?? 0;

    return (
        <GuestLayout>
            <Head title={event.title} />

            {/* Breadcrumb Navigation */}
            <section className="py-6 bg-gradient-to-br from-gray-50 to-white border-b border-gray-100">
                <div className="container mx-auto px-6">
                    <nav className="breadcrumb">
                        <ul className="flex items-center space-x-3 text-sm font-lato">
                            <li className="inline">
                                <Link
                                    href={route('homepage')}
                                    className="flex items-center gap-2 font-semibold transition-colors duration-300 hover:scale-105 text-accent"
                                >
                                    <i className="fas fa-home text-xs"></i>
                                    <span>Home</span>
                                </Link>
                            </li>
                            <li className="inline">
                                <i className="fas fa-chevron-right text-xs text-gray-400"></i>
                            </li>
                            <li className="inline">
                                <Link
                                    href={route('events.index')}
                                    className="flex items-center gap-2 font-semibold transition-colors duration-300 hover:scale-105 text-accent"
                                >
                                    <i className="fas fa-calendar-alt text-xs"></i>
                                    <span>Events</span>
                                </Link>
                            </li>
                            <li className="inline">
                                <i className="fas fa-chevron-right text-xs text-gray-400"></i>
                            </li>
                            <li className="inline">
                                <span className="text-gray-600 truncate max-w-[200px] sm:max-w-[300px] font-medium">
                                    {event.title}
                                </span>
                            </li>
                        </ul>
                    </nav>
                </div>
            </section>

            {/* Event Details Section */}
            <section className="py-8 md:py-12">
                <div className="container mx-auto px-4">
                    <div className="grid grid-cols-1 lg:grid-cols-4 gap-6 lg:gap-8">
                        {/* Main Content */}
                        <div className="lg:col-span-3 space-y-6 md:space-y-8">
                            {/* Event Header */}
                            <div className="space-y-6 md:space-y-8">
                                <div className="space-y-4">
                                    {/* Event Title */}
                                    <h1 className="text-2xl md:text-4xl lg:text-5xl font-extrabold leading-tight font-montserrat text-primary">
                                        {event.title}
                                    </h1>

                                    {/* Event Theme */}
                                    <h2 className="text-lg md:text-xl lg:text-2xl font-bold font-montserrat text-accent">
                                        {event.theme}
                                    </h2>
                                </div>

                                {/* Event Status Section */}
                                <div className="flex flex-wrap items-center gap-4">
                                    {/* Status Badge */}
                                    {isLive && (
                                        <span className="px-4 py-2 rounded-full text-sm font-semibold flex items-center gap-2 font-montserrat shadow-lg text-white bg-secondary">
                                            <span className="w-2.5 h-2.5 bg-white rounded-full animate-pulse"></span>
                                            <i className="fas fa-broadcast-tower text-xs"></i>
                                            Live Now
                                        </span>
                                    )}
                                    {isUpcoming && (
                                        <span className="px-4 py-2 rounded-full text-sm font-semibold font-montserrat shadow-lg text-white bg-accent">
                                            <i className="fas fa-clock text-xs mr-1"></i>
                                            Coming Soon
                                        </span>
                                    )}
                                    {isPast && (
                                        <span className="px-4 py-2 rounded-full text-sm font-semibold font-montserrat shadow-lg bg-gray-500 text-white">
                                            <i className="fas fa-history text-xs mr-1"></i>
                                            Event Ended
                                        </span>
                                    )}

                                    {/* Event Mode Badge */}
                                    <span className="px-4 py-2 rounded-full text-sm font-semibold font-montserrat shadow-md border-2 capitalize text-primary border-primary bg-primary/10">
                                        {getModeIcon(event.mode)}
                                        {event.mode || 'Hybrid'}
                                    </span>

                                    {/* Countdown Timer */}
                                    {isUpcoming && countdown && (
                                        <div className="flex items-center gap-3 px-4 py-2 bg-white rounded-full shadow-md border border-gray-200">
                                            <i className="fas fa-stopwatch text-sm text-accent"></i>
                                            <span className="font-semibold text-sm font-lato text-primary">{countdown}</span>
                                        </div>
                                    )}
                                </div>
                            </div>

                            {/* Event Cover Image */}
                            <div className="relative rounded-2xl overflow-hidden shadow-2xl border border-gray-200 group">
                                <img
                                    src={`/storage/${event.program_cover}`}
                                    alt={event.title}
                                    className="w-full h-64 sm:h-96 lg:h-[32rem] object-cover group-hover:scale-105 transition-transform duration-700"
                                />

                                {/* Image Overlay */}
                                <div className="absolute inset-0 bg-gradient-to-t from-black/20 to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-500"></div>

                                {/* Image Caption */}
                                <div className="absolute bottom-4 left-4 right-4">
                                    <div className="bg-white/10 backdrop-blur-sm rounded-lg p-3 opacity-0 group-hover:opacity-100 transition-opacity duration-500">
                                        <p className="text-white text-sm font-lato">{event.title}</p>
                                    </div>
                                </div>
                            </div>

                            {/* Event Description */}
                            <div className="bg-white rounded-2xl shadow-lg border border-gray-100 p-8 md:p-10">
                                <div className="flex items-center gap-4 mb-6 md:mb-8">
                                    <div className="w-12 h-12 rounded-2xl flex items-center justify-center bg-gradient-to-br from-primary to-primary-dark">
                                        <i className="fas fa-info-circle text-white text-xl"></i>
                                    </div>
                                    <h2 className="text-2xl md:text-3xl font-bold font-montserrat text-primary">
                                        Event Description
                                    </h2>
                                </div>

                                <div className="prose max-w-none text-gray-700 font-lato whitespace-pre-wrap">
                                    {event.description}
                                </div>
                            </div>

                            {/* Event Speakers */}
                            {event.speakers && event.speakers.length > 0 && (
                                <div className="bg-white rounded-2xl shadow-lg border border-gray-100 p-8 md:p-10">
                                    <div className="flex items-center gap-4 mb-8">
                                        <div className="w-12 h-12 rounded-2xl flex items-center justify-center bg-gradient-to-br from-accent to-green-700">
                                            <i className="fas fa-microphone text-white text-xl"></i>
                                        </div>
                                        <h2 className="text-2xl md:text-3xl font-bold font-montserrat text-primary">
                                            Featured Speakers
                                        </h2>
                                    </div>

                                    <div className="grid grid-cols-1 md:grid-cols-2 gap-6 md:gap-8">
                                        {event.speakers.map((speaker) => (
                                            <div
                                                key={speaker.id}
                                                className="group bg-gradient-to-br from-gray-50 to-white rounded-2xl border border-gray-200 hover:border-accent/30 hover:shadow-xl transition-all duration-500 p-6"
                                            >
                                                <div className="flex items-start gap-5">
                                                    {/* Speaker Avatar */}
                                                    <div className="flex-shrink-0 relative">
                                                        <img
                                                            src={
                                                                speaker.user.photo
                                                                    ? `/storage/${speaker.user.photo}`
                                                                    : `https://ui-avatars.com/api/?name=${encodeURIComponent(speaker.user.name)}&background=002147&color=fff&size=128&font-size=0.35&bold=true`
                                                            }
                                                            alt={speaker.user.name}
                                                            className="w-16 h-16 md:w-20 md:h-20 rounded-2xl object-cover shadow-lg group-hover:scale-110 transition-transform duration-500"
                                                        />

                                                        {/* Speaker Badge */}
                                                        <div className="absolute -top-2 -right-2 w-6 h-6 rounded-full flex items-center justify-center shadow-sm bg-accent">
                                                            <i className="fas fa-star text-white text-xs"></i>
                                                        </div>
                                                    </div>

                                                    {/* Speaker Info */}
                                                    <div className="flex-1 min-w-0">
                                                        <Link
                                                            href={route('speakers.profile', speaker.id)}
                                                            className="font-bold text-lg md:text-xl mb-2 font-montserrat block group-hover:text-accent transition-colors duration-300 text-primary"
                                                        >
                                                            {speaker.user.name}
                                                        </Link>

                                                        {speaker.user.headline && (
                                                            <p className="font-semibold text-sm md:text-base mb-3 font-montserrat line-clamp-2 text-accent">
                                                                {speaker.user.headline}
                                                            </p>
                                                        )}

                                                        {speaker.bio ? (
                                                            <p className="text-gray-600 text-sm leading-relaxed line-clamp-3 font-lato">
                                                                {speaker.bio}
                                                            </p>
                                                        ) : !speaker.user.headline ? (
                                                            <p className="text-gray-400 text-sm italic font-lato">
                                                                Professional speaker and industry expert
                                                            </p>
                                                        ) : null}

                                                        {/* View Profile Link */}
                                                        <div className="mt-4">
                                                            <Link
                                                                href={route('speakers.profile', speaker.id)}
                                                                className="inline-flex items-center gap-2 text-sm font-semibold transition-all duration-300 hover:gap-3 text-accent"
                                                            >
                                                                <span>View Profile</span>
                                                                <i className="fas fa-arrow-right text-xs"></i>
                                                            </Link>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        ))}
                                    </div>
                                </div>
                            )}

                            {/* Event Resources (only for registered users) */}
                            {auth?.user && isRegistered && event.resources && event.resources.length > 0 && (
                                <div className="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 md:p-8">
                                    <h2 className="text-xl md:text-2xl font-bold text-primary mb-4 md:mb-6 flex items-center gap-3 font-montserrat">
                                        <div className="w-1 h-6 md:h-8 bg-secondary rounded-full"></div>
                                        Event Resources
                                    </h2>
                                    <ul className="space-y-3">
                                        {event.resources.map((resource) => (
                                            <li key={resource.id}>
                                                {resource.type === 'file' && resource.file_path && (
                                                    <a
                                                        href={`/storage/${resource.file_path}`}
                                                        target="_blank"
                                                        rel="noopener noreferrer"
                                                        className="flex items-center gap-3 p-3 bg-gray-50 rounded-lg border border-gray-200 hover:bg-gray-100 transition-all duration-300 font-lato"
                                                    >
                                                        <i className="fas fa-file-text text-secondary"></i>
                                                        <span className="font-medium">{resource.title}</span>
                                                    </a>
                                                )}
                                                {resource.type === 'link' && resource.external_link && (
                                                    <a
                                                        href={resource.external_link}
                                                        target="_blank"
                                                        rel="noopener noreferrer"
                                                        className="flex items-center gap-3 p-3 bg-gray-50 rounded-lg border border-gray-200 hover:bg-gray-100 transition-all duration-300 font-lato"
                                                    >
                                                        <i className="fas fa-link text-secondary"></i>
                                                        <span className="font-medium">{resource.title}</span>
                                                    </a>
                                                )}
                                            </li>
                                        ))}
                                    </ul>
                                </div>
                            )}
                        </div>

                        {/* Sidebar */}
                        <div className="space-y-8">
                            {/* Registration Card */}
                            <div className="relative overflow-hidden rounded-2xl shadow-2xl">
                                {/* Gradient Background */}
                                <div className="absolute inset-0 bg-gradient-to-br from-primary via-primary-dark to-accent"></div>

                                {/* Content */}
                                <div className="relative p-8 text-white">
                                    {/* Header */}
                                    <div className="text-center mb-8">
                                        <div className="w-16 h-16 bg-white/20 backdrop-blur-sm rounded-2xl flex items-center justify-center mx-auto mb-4">
                                            <i className="fas fa-ticket-alt text-2xl text-white"></i>
                                        </div>
                                        <h3 className="text-2xl font-bold font-montserrat">Event Registration</h3>
                                        <p className="text-white/80 font-lato mt-2">Secure your spot today</p>
                                    </div>

                                    {/* Event Details */}
                                    <div className="space-y-4 mb-8">
                                        <div className="bg-white/10 backdrop-blur-sm rounded-xl p-4 border border-white/20">
                                            <div className="flex justify-between items-center">
                                                <span className="text-white/90 font-medium font-lato flex items-center gap-2">
                                                    <i className="fas fa-users text-sm"></i>
                                                    Slots Remaining
                                                </span>
                                                <span className="font-bold text-xl text-white font-montserrat">{slotsRemaining}</span>
                                            </div>
                                        </div>

                                        <div className="bg-white/10 backdrop-blur-sm rounded-xl p-4 border border-white/20">
                                            <div className="flex justify-between items-center">
                                                <span className="text-white/90 font-medium font-lato flex items-center gap-2">
                                                    <i className="fas fa-tag text-sm"></i>
                                                    Registration Fee
                                                </span>
                                                {event.entry_fee > 0 ? (
                                                    <span className="font-bold text-xl text-white font-montserrat">
                                                        ₦{event.entry_fee.toLocaleString()}
                                                    </span>
                                                ) : (
                                                    <span className="font-bold text-xl text-white font-montserrat">FREE</span>
                                                )}
                                            </div>
                                        </div>
                                    </div>

                                    {/* Registration Button */}
                                    <button
                                        onClick={() => setShowModal(true)}
                                        disabled={isRegistered || revokeCount === 4}
                                        className="w-full bg-white text-primary py-4 px-6 rounded-xl font-bold text-lg hover:bg-gray-100 disabled:opacity-50 disabled:cursor-not-allowed transition-all duration-300 transform hover:scale-105 shadow-lg hover:shadow-xl flex items-center justify-center gap-3 font-montserrat mb-4"
                                    >
                                        {isRegistered ? (
                                            <>
                                                <i className="fas fa-check-circle text-xl text-accent"></i>
                                                <span>Already Registered</span>
                                            </>
                                        ) : revokeCount === 4 ? (
                                            <>
                                                <i className="fas fa-users text-xl text-gray-500"></i>
                                                <span>Max Registrations</span>
                                            </>
                                        ) : (
                                            <>
                                                <i className="fas fa-hand-paper text-xl text-primary"></i>
                                                <span>Register Now</span>
                                            </>
                                        )}
                                    </button>

                                    {/* Speaker Application */}
                                    {event.is_allowing_application && signed_speaker_route && (
                                        <a
                                            href={signed_speaker_route}
                                            className="w-full border-2 border-white text-white py-3 px-6 rounded-xl font-semibold hover:bg-white hover:text-primary transition-all duration-300 flex items-center justify-center gap-3 font-montserrat group"
                                        >
                                            <i className="fas fa-microphone group-hover:scale-110 transition-transform"></i>
                                            Apply as Speaker
                                        </a>
                                    )}

                                    {/* Login Notice */}
                                    {!auth?.user && (
                                        <div className="mt-6 p-4 bg-white/10 backdrop-blur-sm rounded-xl border border-white/20">
                                            <p className="text-white/90 text-sm text-center font-lato flex items-center justify-center gap-2">
                                                <i className="fas fa-info-circle"></i>
                                                You must be logged in to register for this event
                                            </p>
                                        </div>
                                    )}
                                </div>
                            </div>

                            {/* Event Info Card */}
                            <div className="bg-white rounded-2xl shadow-lg border border-gray-100 p-6 hover:shadow-xl transition-shadow duration-300">
                                <div className="flex items-center gap-3 mb-6">
                                    <div className="w-10 h-10 rounded-xl flex items-center justify-center bg-primary/10">
                                        <i className="fas fa-info-circle text-lg text-primary"></i>
                                    </div>
                                    <h4 className="font-bold text-xl font-montserrat text-primary">Event Details</h4>
                                </div>

                                <div className="space-y-5">
                                    {/* Start Time */}
                                    <div className="flex items-start gap-4 p-4 bg-gray-50 rounded-xl hover:bg-gray-100 transition-colors duration-300">
                                        <div className="w-12 h-12 rounded-xl flex items-center justify-center flex-shrink-0 bg-accent/10">
                                            <i className="fas fa-play-circle text-xl text-accent"></i>
                                        </div>
                                        <div className="flex-1 min-w-0">
                                            <p className="font-bold text-sm font-montserrat mb-1 text-primary">Event Starts</p>
                                            <p className="text-gray-800 font-semibold font-lato">{formatTime(event.start_date)}</p>
                                            <p className="text-gray-600 text-sm font-lato">{formatDate(event.start_date)}</p>
                                        </div>
                                    </div>

                                    {/* End Time */}
                                    <div className="flex items-start gap-4 p-4 bg-gray-50 rounded-xl hover:bg-gray-100 transition-colors duration-300">
                                        <div className="w-12 h-12 rounded-xl flex items-center justify-center flex-shrink-0 bg-secondary/10">
                                            <i className="fas fa-flag-checkered text-xl text-secondary"></i>
                                        </div>
                                        <div className="flex-1 min-w-0">
                                            <p className="font-bold text-sm font-montserrat mb-1 text-primary">Event Ends</p>
                                            <p className="text-gray-800 font-semibold font-lato">{formatTime(event.end_date)}</p>
                                            <p className="text-gray-600 text-sm font-lato">{formatDate(event.end_date)}</p>
                                        </div>
                                    </div>

                                    {/* Location (for registered users) */}
                                    {auth?.user && isRegistered && (
                                        <div className="flex items-start gap-3">
                                            <div className="w-8 h-8 bg-primary/10 rounded-lg flex items-center justify-center flex-shrink-0">
                                                <i className="fas fa-map-pin text-primary"></i>
                                            </div>
                                            <div className="flex-1 min-w-0">
                                                <p className="font-semibold text-primary text-sm font-montserrat">
                                                    {event.mode === 'offline'
                                                        ? 'Venue Address'
                                                        : event.mode === 'online'
                                                        ? 'Meeting Link'
                                                        : 'Location'}
                                                </p>
                                                {event.mode === 'offline' && event.physical_address && (
                                                    <p className="text-gray-700 text-sm break-words font-lato">{event.physical_address}</p>
                                                )}
                                                {event.mode === 'online' && event.location && (
                                                    <>
                                                        {isPast ? (
                                                            <span className="text-red-600 font-semibold text-sm font-lato">
                                                                Meeting link has expired.
                                                            </span>
                                                        ) : startDate <= now ? (
                                                            <a
                                                                href={event.location}
                                                                className="text-secondary font-semibold hover:underline break-all text-sm font-lato"
                                                                target="_blank"
                                                                rel="noopener noreferrer"
                                                            >
                                                                Click to Join Meeting
                                                            </a>
                                                        ) : (
                                                            <span className="text-gray-600 text-sm font-lato">
                                                                Meeting link will be available on {formatDate(event.start_date)} at{' '}
                                                                {formatTime(event.start_date)}.
                                                            </span>
                                                        )}
                                                    </>
                                                )}
                                                {event.mode === 'hybrid' && (
                                                    <>
                                                        {event.physical_address && (
                                                            <p className="text-gray-700 text-sm break-words font-lato mb-2">
                                                                {event.physical_address}
                                                            </p>
                                                        )}
                                                        {event.location && startDate <= now && (
                                                            <a
                                                                href={event.location}
                                                                className="text-secondary font-semibold hover:underline break-all text-sm font-lato"
                                                                target="_blank"
                                                                rel="noopener noreferrer"
                                                            >
                                                                Click to Join Meeting
                                                            </a>
                                                        )}
                                                    </>
                                                )}
                                            </div>
                                        </div>
                                    )}
                                </div>
                            </div>

                            {/* Quick Actions Card */}
                            <div className="relative overflow-hidden rounded-2xl shadow-lg">
                                {/* Gradient Background */}
                                <div className="absolute inset-0 bg-gradient-to-br from-accent to-green-700"></div>

                                {/* Content */}
                                <div className="relative p-6 text-white">
                                    <div className="flex items-center gap-3 mb-6">
                                        <div className="w-10 h-10 bg-white/20 backdrop-blur-sm rounded-xl flex items-center justify-center">
                                            <i className="fas fa-bolt text-lg text-white"></i>
                                        </div>
                                        <h4 className="font-bold text-xl text-white font-montserrat">Quick Actions</h4>
                                    </div>

                                    <div className="space-y-3">
                                        <Link
                                            href={route('events.index')}
                                            className="flex items-center gap-3 p-4 bg-white/10 backdrop-blur-sm rounded-xl hover:bg-white/20 transition-all duration-300 font-lato group border border-white/20"
                                        >
                                            <i className="fas fa-calendar-alt text-lg group-hover:scale-110 transition-transform duration-300 text-white"></i>
                                            <span className="font-semibold">View All Events</span>
                                            <i className="fas fa-arrow-right text-sm ml-auto opacity-0 group-hover:opacity-100 group-hover:translate-x-1 transition-all duration-300"></i>
                                        </Link>

                                        <Link
                                            href={route('homepage')}
                                            className="flex items-center gap-3 p-4 bg-white/10 backdrop-blur-sm rounded-xl hover:bg-white/20 transition-all duration-300 font-lato group border border-white/20"
                                        >
                                            <i className="fas fa-home text-lg group-hover:scale-110 transition-transform duration-300 text-white"></i>
                                            <span className="font-semibold">Back to Home</span>
                                            <i className="fas fa-arrow-right text-sm ml-auto opacity-0 group-hover:opacity-100 group-hover:translate-x-1 transition-all duration-300"></i>
                                        </Link>

                                        {auth?.user && (
                                            <Link
                                                href={route('user.events')}
                                                className="flex items-center gap-3 p-4 bg-white/10 backdrop-blur-sm rounded-xl hover:bg-white/20 transition-all duration-300 font-lato group border border-white/20"
                                            >
                                                <i className="fas fa-list text-lg group-hover:scale-110 transition-transform duration-300 text-white"></i>
                                                <span className="font-semibold">My Events</span>
                                                <i className="fas fa-arrow-right text-sm ml-auto opacity-0 group-hover:opacity-100 group-hover:translate-x-1 transition-all duration-300"></i>
                                            </Link>
                                        )}

                                        <Link
                                            href={route('courses.index')}
                                            className="flex items-center gap-3 p-4 bg-white/10 backdrop-blur-sm rounded-xl hover:bg-white/20 transition-all duration-300 font-lato group border border-white/20"
                                        >
                                            <i className="fas fa-graduation-cap text-lg group-hover:scale-110 transition-transform duration-300 text-white"></i>
                                            <span className="font-semibold">Browse Courses</span>
                                            <i className="fas fa-arrow-right text-sm ml-auto opacity-0 group-hover:opacity-100 group-hover:translate-x-1 transition-all duration-300"></i>
                                        </Link>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </section>

            {/* Registration Modal */}
            {showModal && (
                <div className="fixed inset-0 z-50 flex justify-center items-center bg-black/60 backdrop-blur-md">
                    <div className="relative p-4 w-full max-w-lg max-h-full">
                        <div className="relative bg-white rounded-2xl shadow-2xl border border-gray-100 overflow-hidden">
                            {/* Modal Header with Gradient */}
                            <div className="relative p-6 text-center bg-gradient-to-br from-primary to-primary-dark">
                                {/* Close Button */}
                                <button
                                    type="button"
                                    onClick={() => setShowModal(false)}
                                    className="absolute top-4 right-4 text-white/80 hover:text-white hover:bg-white/20 rounded-xl text-sm w-8 h-8 flex justify-center items-center transition-all duration-300"
                                >
                                    <i className="fas fa-times w-4 h-4"></i>
                                </button>

                                {/* Modal Icon */}
                                <div className="w-20 h-20 bg-white/20 backdrop-blur-sm rounded-2xl flex items-center justify-center mx-auto mb-4">
                                    <i className="fas fa-ticket-alt text-3xl text-white"></i>
                                </div>

                                {/* Modal Title */}
                                <h3 className="text-xl font-bold text-white font-montserrat mb-2">Event Registration</h3>
                                <p className="text-white/90 text-sm font-lato">Secure your spot at this transformational event</p>
                            </div>

                            {/* Modal Content */}
                            <div className="p-8 text-center">
                                <div className="mb-6">
                                    <h4 className="text-lg font-semibold mb-2 font-montserrat text-primary">
                                        You're about to register for:
                                    </h4>
                                    <div className="p-4 rounded-xl border-2 border-dashed mb-4 border-accent bg-accent/5">
                                        <span className="font-bold text-lg block font-montserrat text-primary">{event.title}</span>
                                        <span className="text-sm font-lato text-accent">{event.theme}</span>
                                    </div>
                                    <p className="text-gray-600 font-lato">
                                        We're excited to have you join us for this transformational experience!
                                    </p>
                                </div>

                                <form onSubmit={handleRegistration}>
                                    <div className="flex flex-col sm:flex-row gap-4 justify-center">
                                        <button
                                            type="submit"
                                            disabled={isSubmitting}
                                            className="text-white font-semibold rounded-xl text-sm px-6 py-3 text-center transition-all duration-300 font-montserrat flex items-center justify-center gap-2 min-w-[160px] shadow-lg hover:shadow-xl transform hover:scale-105 bg-accent hover:bg-green-700"
                                        >
                                            {isSubmitting ? (
                                                <>
                                                    <svg className="animate-spin h-4 w-4 text-white" fill="none" viewBox="0 0 24 24">
                                                        <circle
                                                            className="opacity-25"
                                                            cx="12"
                                                            cy="12"
                                                            r="10"
                                                            stroke="currentColor"
                                                            strokeWidth="4"
                                                        ></circle>
                                                        <path className="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v8z"></path>
                                                    </svg>
                                                    Registering...
                                                </>
                                            ) : (
                                                <>
                                                    <i className="fas fa-check"></i>
                                                    Yes, Register Me
                                                </>
                                            )}
                                        </button>
                                        <button
                                            type="button"
                                            onClick={() => setShowModal(false)}
                                            className="py-3 px-6 text-sm font-semibold border-2 rounded-xl transition-all duration-300 font-montserrat hover:bg-gray-50 text-primary border-primary"
                                        >
                                            <i className="fas fa-times mr-2"></i>
                                            Cancel
                                        </button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            )}
        </GuestLayout>
    );
}
