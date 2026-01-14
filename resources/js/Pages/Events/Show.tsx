import { Head, Link, router} from '@inertiajs/react';
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
    description?: string;
    type: 'file' | 'link';
    file_path?: string;
    external_link?: string;
    is_downloadable: boolean;
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
    slots_remaining?: number | null;
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
            case 'onsite':
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

    const slotsRemaining = event.slots_remaining;
    const isRegistered = event.is_registered ?? false;
    const revokeCount = event.revoke_count ?? 0;

    return (
        <GuestLayout>
            <Head title={event.title} />

            {/* Breadcrumb Navigation */}
            <section className="py-6 bg-linear-to-br from-gray-50 to-white border-b border-gray-100">
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
                                className="w-full h-full object-cover"
                                onError={(e) => {
                                    (e.target as HTMLImageElement).src = 'https://placehold.co/600x400?text=Cover+Image+Missing';
                                }}
                            />

                                {/* Image Overlay */}
                                <div className="absolute inset-0 bg-linear-to-t from-black/20 to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-500"></div>

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
                                    <div className="w-12 h-12 rounded-2xl flex items-center justify-center bg-linear-to-br from-primary to-primary">
                                        <i className="fas fa-info-circle text-white text-xl"></i>
                                    </div>
                                    <h2 className="text-2xl md:text-3xl font-bold font-montserrat text-primary">
                                        Event Description
                                    </h2>
                                </div>

                                <div
                                    className="prose prose-lg max-w-none text-gray-700 font-lato
                                        prose-headings:font-montserrat prose-headings:text-primary prose-headings:font-bold
                                        prose-h1:text-3xl prose-h2:text-2xl prose-h3:text-xl prose-h4:text-lg
                                        prose-p:leading-relaxed prose-p:mb-4 prose-p:text-gray-700
                                        prose-a:text-accent prose-a:font-semibold prose-a:no-underline hover:prose-a:underline prose-a:transition-all
                                        prose-strong:text-primary prose-strong:font-bold
                                        prose-em:text-gray-600 prose-em:italic
                                        prose-ul:list-disc prose-ul:ml-6 prose-ul:mb-4
                                        prose-ol:list-decimal prose-ol:ml-6 prose-ol:mb-4
                                        prose-li:mb-2 prose-li:text-gray-700 prose-li:marker:text-accent
                                        prose-blockquote:border-l-4 prose-blockquote:border-accent prose-blockquote:pl-4 prose-blockquote:italic prose-blockquote:text-gray-600 prose-blockquote:bg-gray-50 prose-blockquote:py-2
                                        prose-code:bg-gray-100 prose-code:px-2 prose-code:py-1 prose-code:rounded prose-code:text-sm prose-code:text-primary
                                        prose-pre:bg-gray-900 prose-pre:text-gray-100 prose-pre:p-4 prose-pre:rounded-lg prose-pre:overflow-x-auto
                                        prose-img:rounded-lg prose-img:shadow-md
                                        prose-table:border-collapse prose-table:w-full
                                        prose-th:bg-primary prose-th:text-white prose-th:p-3 prose-th:text-left prose-th:font-semibold
                                        prose-td:border prose-td:border-gray-300 prose-td:p-3"
                                    dangerouslySetInnerHTML={{ __html: event.description }}
                                />
                            </div>

                            {/* Event Speakers */}
                            {event.speakers && event.speakers.length > 0 && (
                                <div className="bg-white rounded-2xl shadow-lg border border-gray-100 p-8 md:p-10">
                                    <div className="flex items-center gap-4 mb-8">
                                        <div className="w-12 h-12 rounded-2xl flex items-center justify-center bg-linear-to-br from-accent to-green-700">
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
                                                className="group bg-linear-to-br from-gray-50 to-white rounded-2xl border border-gray-200 hover:border-accent/30 hover:shadow-xl transition-all duration-500 p-6"
                                            >
                                                <div className="flex items-start gap-5">
                                                    {/* Speaker Avatar */}
                                                    <div className="shrink-0 relative">
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

                            {/* Event Resources (only for registered users with downloadable resources) */}
                            {auth?.user && isRegistered && event.resources && event.resources.filter(r => r.is_downloadable).length > 0 && (
                                <div className="bg-linear-to-br from-white to-green-50 rounded-2xl shadow-lg border-2 border-accent/20 p-6 md:p-8">
                                    <div className="flex items-center gap-3 mb-6">
                                        <div className="w-12 h-12 rounded-xl flex items-center justify-center" style={{ backgroundColor: '#e6f7ed' }}>
                                            <i className="fas fa-download text-2xl" style={{ color: '#00a651' }}></i>
                                        </div>
                                        <div>
                                            <h2 className="text-xl md:text-2xl font-bold text-primary font-montserrat">Event Resources</h2>
                                            <p className="text-sm text-gray-600 font-lato">Downloadable materials for attendees</p>
                                        </div>
                                    </div>
                                    <div className="grid grid-cols-1 gap-3">
                                        {event.resources.filter(r => r.is_downloadable).map((resource) => (
                                            <div key={resource.id}>
                                                {resource.type === 'file' && resource.file_path && (
                                                    <a
                                                        href={`/storage/${resource.file_path}`}
                                                        download
                                                        target="_blank"
                                                        rel="noopener noreferrer"
                                                        className="flex items-center gap-4 p-4 bg-white rounded-xl border-2 border-gray-200 hover:border-accent hover:shadow-md transition-all duration-300 font-lato group"
                                                    >
                                                        <div className="w-12 h-12 rounded-lg flex items-center justify-center bg-primary/10 group-hover:bg-accent/10 transition-colors shrink-0">
                                                            <i className="fas fa-file-pdf text-xl text-primary group-hover:text-accent transition-colors"></i>
                                                        </div>
                                                        <div className="flex-1 min-w-0">
                                                            <p className="font-semibold text-gray-900 font-montserrat truncate">{resource.title}</p>
                                                            {resource.description && (
                                                                <p className="text-sm text-gray-600 font-lato mt-1 line-clamp-1">{resource.description}</p>
                                                            )}
                                                        </div>
                                                        <div className="flex items-center gap-2 shrink-0">
                                                            <span className="text-xs font-medium text-accent bg-accent/10 px-3 py-1 rounded-full font-lato">File</span>
                                                            <i className="fas fa-download text-accent text-lg group-hover:scale-110 transition-transform"></i>
                                                        </div>
                                                    </a>
                                                )}
                                                {resource.type === 'link' && resource.external_link && (
                                                    <a
                                                        href={resource.external_link}
                                                        target="_blank"
                                                        rel="noopener noreferrer"
                                                        className="flex items-center gap-4 p-4 bg-white rounded-xl border-2 border-gray-200 hover:border-accent hover:shadow-md transition-all duration-300 font-lato group"
                                                    >
                                                        <div className="w-12 h-12 rounded-lg flex items-center justify-center bg-primary/10 group-hover:bg-accent/10 transition-colors shrink-0">
                                                            <i className="fas fa-link text-xl text-primary group-hover:text-accent transition-colors"></i>
                                                        </div>
                                                        <div className="flex-1 min-w-0">
                                                            <p className="font-semibold text-gray-900 font-montserrat truncate">{resource.title}</p>
                                                            {resource.description && (
                                                                <p className="text-sm text-gray-600 font-lato mt-1 line-clamp-1">{resource.description}</p>
                                                            )}
                                                        </div>
                                                        <div className="flex items-center gap-2 shrink-0">
                                                            <span className="text-xs font-medium text-accent bg-accent/10 px-3 py-1 rounded-full font-lato">Link</span>
                                                            <i className="fas fa-external-link-alt text-accent text-lg group-hover:scale-110 transition-transform"></i>
                                                        </div>
                                                    </a>
                                                )}
                                            </div>
                                        ))}
                                    </div>
                                </div>
                            )}
                        </div>

                        {/* Sidebar */}
                        <div className="space-y-8">
                            {/* Registration Card */}
                            <div className="relative overflow-hidden rounded-2xl shadow-2xl">
                                {/* Gradient Background */}
                                <div className="absolute inset-0 bg-linear-to-br from-primary via-primary to-accent"></div>

                                {/* Content */}
                                <div className="relative p-8 text-white">
                                    {/* Header */}
                                    <div className="text-center mb-8">
                                        <div className="w-16 h-16 bg-white/20 backdrop-blur-sm rounded-2xl flex items-center justify-center mx-auto mb-4">
                                            <i className="fas fa-ticket-alt text-2xl text-white"></i>
                                        </div>
                                        <h3 className="text-2xl font-bold font-montserrat">Event Registration</h3>
                                        <p className="text-white/80 font-lato mt-2">
                                            {isRegistered ? 'You\'re all set!' : 'Secure your spot today'}
                                        </p>
                                    </div>

                                    {/* Registration Status - Show when registered */}
                                    {isRegistered && (
                                        <div className="mb-6 p-5 bg-white/15 backdrop-blur-sm rounded-xl border-2 border-white/30">
                                            <div className="flex items-center gap-3 mb-3">
                                                <div className="w-12 h-12 bg-accent rounded-full flex items-center justify-center shrink-0">
                                                    <i className="fas fa-check text-white text-xl"></i>
                                                </div>
                                                <div className="flex-1">
                                                    <p className="font-bold text-white font-montserrat text-lg">Registration Confirmed</p>
                                                    <p className="text-white/90 text-sm font-lato">Your spot is secured</p>
                                                </div>
                                            </div>
                                            <div className="pt-3 border-t border-white/20">
                                                <p className="text-white/90 text-sm font-lato flex items-start gap-2">
                                                    <i className="fas fa-info-circle mt-0.5 shrink-0"></i>
                                                    <span>Check your email for confirmation and event details. You'll receive a reminder before the event starts.</span>
                                                </p>
                                            </div>
                                        </div>
                                    )}

                                    {/* Event Details */}
                                    <div className="space-y-4 mb-8">
                                        {/* Slots Remaining */}
                                        <div className="bg-white/10 backdrop-blur-sm rounded-xl p-5 border border-white/20">
                                            <div className="flex justify-between items-center mb-2">
                                                <span className="text-white/90 font-semibold font-lato flex items-center gap-2">
                                                    <i className="fas fa-users text-base"></i>
                                                    Available Slots
                                                </span>
                                                <span className="font-bold text-2xl text-white font-montserrat">
                                                    {event.slots_remaining === null ? 'Unlimited' : slotsRemaining}
                                                </span>
                                            </div>
                                            {typeof slotsRemaining === 'number' && slotsRemaining <= 10 && slotsRemaining > 0 && (
                                                <p className="text-white/80 text-xs font-lato mt-2">
                                                    <i className="fas fa-exclamation-circle mr-1"></i>
                                                    Limited slots available - Register soon!
                                                </p>
                                            )}
                                            {slotsRemaining === 0 && (
                                                <p className="text-red-200 text-xs font-lato mt-2 font-semibold">
                                                    <i className="fas fa-times-circle mr-1"></i>
                                                    Event is fully booked
                                                </p>
                                            )}
                                        </div>

                                        {/* Registration Fee */}
                                        <div className="bg-white/10 backdrop-blur-sm rounded-xl p-5 border border-white/20">
                                            <div className="flex justify-between items-start mb-2">
                                                <div className="flex-1">
                                                    <span className="text-white/90 font-semibold font-lato flex items-center gap-2 mb-1">
                                                        <i className="fas fa-tag text-base"></i>
                                                        Entry Fee
                                                    </span>
                                                    {event.entry_fee > 0 && (
                                                        <p className="text-white/70 text-xs font-lato">Per participant</p>
                                                    )}
                                                </div>
                                                <div className="text-right">
                                                    {event.entry_fee > 0 ? (
                                                        <>
                                                            <span className="font-bold text-3xl text-white font-montserrat block leading-none">
                                                                ₦{event.entry_fee.toLocaleString()}
                                                            </span>
                                                            <span className="text-white/70 text-xs font-lato mt-1 block">
                                                                Non-refundable
                                                            </span>
                                                        </>
                                                    ) : (
                                                        <>
                                                            <span className="font-bold text-3xl text-white font-montserrat block leading-none">FREE</span>
                                                            <span className="text-white/70 text-xs font-lato mt-1 block">
                                                                No payment required
                                                            </span>
                                                        </>
                                                    )}
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    {/* Registration Button */}
                                    {isPast ? (
                                        <div className="w-full bg-gray-500 text-white py-4 px-6 rounded-xl font-bold text-lg cursor-not-allowed shadow-lg flex items-center justify-center gap-3 font-montserrat mb-4 opacity-80">
                                            <i className="fas fa-history text-xl"></i>
                                            <span>Event has Ended</span>
                                        </div>
                                    ) : !isRegistered ? (
                                        <button
                                            onClick={() => {
                                                if (!auth?.user) {
                                                    router.get(route('login'));
                                                    return;
                                                }
                                                setShowModal(true);
                                            }}
                                            disabled={revokeCount === 4 || slotsRemaining === 0}
                                            className="w-full bg-white text-primary py-4 px-6 rounded-xl font-bold text-lg hover:bg-gray-100 disabled:opacity-50 disabled:cursor-not-allowed transition-all duration-300 transform hover:scale-105 shadow-lg hover:shadow-xl flex items-center justify-center gap-3 font-montserrat mb-4"
                                        >
                                            {revokeCount === 4 ? (
                                                <>
                                                    <i className="fas fa-ban text-xl text-gray-500"></i>
                                                    <span>Maximum Registrations Reached</span>
                                                </>
                                            ) : slotsRemaining === 0 ? (
                                                <>
                                                    <i className="fas fa-users-slash text-xl text-gray-500"></i>
                                                    <span>Event Full</span>
                                                </>
                                            ) : !auth?.user ? (
                                                <>
                                                    <i className="fas fa-sign-in-alt text-xl text-primary"></i>
                                                    <span>Login to Register</span>
                                                </>
                                            ) : (
                                                <>
                                                    <i className="fas fa-hand-paper text-xl text-primary"></i>
                                                    <span>Register Now</span>
                                                </>
                                            )}
                                        </button>
                                    ) : (
                                        <div className="w-full bg-white/20 backdrop-blur-sm border-2 border-white/40 text-white py-4 px-6 rounded-xl font-bold text-lg cursor-default shadow-lg flex items-center justify-center gap-3 font-montserrat mb-4">
                                            <i className="fas fa-check-circle text-2xl text-accent"></i>
                                            <span>You're Registered!</span>
                                        </div>
                                    )}

                                    {/* Speaker Application */}
                                    {event.is_allowing_application === true && signed_speaker_route && !isRegistered && (
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

                                    {/* Registered Users: Quick Actions */}
                                    {isRegistered && (
                                        <div className="mt-6 space-y-3">
                                            <div className="border-t border-white/20 pt-4">
                                                <p className="text-white/90 text-sm font-semibold font-montserrat mb-3">Quick Actions</p>
                                                <div className="space-y-2">
                                                    <Link
                                                        href={route('user.events')}
                                                        className="flex items-center gap-3 p-3 bg-white/10 backdrop-blur-sm rounded-lg hover:bg-white/20 transition-all duration-300 font-lato group border border-white/20"
                                                    >
                                                        <i className="fas fa-calendar-check text-white group-hover:scale-110 transition-transform"></i>
                                                        <span className="font-medium text-white text-sm">View My Events</span>
                                                        <i className="fas fa-arrow-right text-xs ml-auto opacity-0 group-hover:opacity-100 group-hover:translate-x-1 transition-all duration-300 text-white"></i>
                                                    </Link>
                                                </div>
                                            </div>
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
                                        <div className="w-12 h-12 rounded-xl flex items-center justify-center shrink-0 bg-accent/10">
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
                                        <div className="w-12 h-12 rounded-xl flex items-center justify-center shrink-0 bg-secondary/10">
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
                                            <div className="w-8 h-8 bg-primary/10 rounded-lg flex items-center justify-center shrink-0">
                                                <i className="fas fa-map-pin text-primary"></i>
                                            </div>
                                            <div className="flex-1 min-w-0">
                                                <p className="font-semibold text-primary text-sm font-montserrat">
                                                    {event.mode === 'offline'
                                                        ? 'Venue Address'
                                                        : event.mode === 'online'
                                                            ? 'Meeting Link'
                                                            : 'Location & Link'}
                                                </p>
                                                {/* Physical Address for Offline and Hybrid */}
                                                {(event.mode === 'offline' || event.mode === 'hybrid') && event.physical_address && (
                                                    <p className="text-gray-700 text-sm wrap-break-words font-lato mb-2">{event.physical_address}</p>
                                                )}
                                                
                                                {/* Meeting Link for Online and Hybrid */}
                                                {(event.mode === 'online' || event.mode === 'hybrid') && event.location && (
                                                    <>
                                                        {isPast ? (
                                                            <span className="text-red-600 font-semibold text-sm font-lato block">
                                                                Meeting link has expired.
                                                            </span>
                                                        ) : startDate <= now ? (
                                                            <a
                                                                href={event.location}
                                                                className="text-secondary font-semibold hover:underline break-all text-sm font-lato block"
                                                                target="_blank"
                                                                rel="noopener noreferrer"
                                                            >
                                                                Click to Join Meeting
                                                            </a>
                                                        ) : (
                                                            <span className="text-gray-600 text-sm font-lato block">
                                                                Meeting link will be available on {formatDate(event.start_date)} at{' '}
                                                                {formatTime(event.start_date)}.
                                                            </span>
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
                                <div className="absolute inset-0 bg-linear-to-br from-accent to-green-700"></div>

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
                            <div className="relative p-6 text-center bg-linear-to-br from-primary to-primary">
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
                                            className="text-white font-semibold rounded-xl text-sm px-6 py-3 text-center transition-all duration-300 font-montserrat flex items-center justify-center gap-2 min-w-40 shadow-lg hover:shadow-xl transform hover:scale-105 bg-accent hover:bg-green-700"
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
