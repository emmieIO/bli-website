import GuestLayout from '@/Layouts/GuestLayout';
import { Head, Link } from '@inertiajs/react';
import { useEffect } from 'react';
import AOS from 'aos';
import 'aos/dist/aos.css';

// Basic type definitions based on the Blade file.
// These should be refined or replaced with your global types if they exist.
interface User {
    name: string;
    photo?: string;
    headline?: string;
    email: string;
    phone?: string;
    twitter?: string;
    linkedin?: string;
    website?: string;
}

interface Event {
    slug: string;
    title: string;
    program_cover: string;
    start_date: string;
    description?: string;
    location?: string;
    max_attendees?: number;
}

interface Speaker {
    user: User;
    bio?: string;
    expertise?: string;
    events: Event[];
}

interface SpeakerProfileProps {
    speaker: Speaker;
}

export default function SpeakerProfile({ speaker }: SpeakerProfileProps) {
    useEffect(() => {
        AOS.init({
            duration: 800,
            easing: 'ease-out-cubic',
            once: true,
            offset: 100,
        });
    }, []);

    const expertiseList = speaker.expertise ? speaker.expertise.split(',') : [];
    const iconClasses = ['fa-briefcase', 'fa-chart-line', 'fa-code'];
    const iconColors = ['#002147', '#ed1c24', '#b42318'];
    const bgColors = ['rgba(0, 33, 71, 0.08)', 'rgba(237, 28, 36, 0.08)', 'rgba(180, 35, 24, 0.08)'];

    return (
        <GuestLayout>
            <Head title={`Speaker Profile: ${speaker.user.name}`} />

            <section className="public-hero-compact bg-primary">
                <div className="section-shell">
                    <nav className="mb-8" data-aos="fade-down">
                        <ul className="flex items-center space-x-2 text-sm font-lato">
                            <li>
                                <Link href={route('homepage')} className="text-white/70 hover:text-white transition-colors">
                                    Home
                                </Link>
                            </li>
                            <li><i className="fas fa-chevron-right text-white/40 text-xs"></i></li>
                            <li>
                                <Link href={route('events.index')} className="text-white/70 hover:text-white transition-colors">
                                    Events
                                </Link>
                            </li>
                            <li><i className="fas fa-chevron-right text-white/40 text-xs"></i></li>
                            <li><span className="text-white">Speaker Profile</span></li>
                        </ul>
                    </nav>

                    <div className="flex flex-col lg:flex-row items-center lg:items-start gap-8">
                        <div className="shrink-0" data-aos="fade-right">
                            <div className="relative">
                                <img
                                    src={
                                        speaker.user.photo
                                            ? `/storage/${speaker.user.photo}`
                                            : `https://ui-avatars.com/api/?name=${encodeURIComponent(
                                                  speaker.user.name
                                                  )}&background=002147&color=fff&size=320&bold=true`
                                    }
                                    alt={speaker.user.name}
                                    className="w-40 h-40 rounded-3xl object-cover border-4 border-white shadow-2xl"
                                />
                                <div className="absolute -bottom-2 -right-2 rounded-full bg-accent px-3 py-1 text-xs font-semibold text-white">
                                    <i className="fas fa-check-circle mr-1"></i>
                                    Verified Speaker
                                </div>
                            </div>
                        </div>

                        <div className="flex-1 text-center lg:text-left" data-aos="fade-left">
                            <h1 className="text-4xl lg:text-5xl font-bold text-white mb-4 font-montserrat">
                                {speaker.user.name}
                            </h1>

                            {speaker.user.headline && (
                                <p className="mb-4 text-2xl font-semibold font-montserrat text-red-200">
                                    {speaker.user.headline}
                                </p>
                            )}

                            <div className="flex flex-col sm:flex-row gap-4 mb-6 justify-center lg:justify-start">
                                        <div className="flex items-center gap-2 text-white/75">
                                    <i className="fas fa-envelope"></i>
                                    <span className="font-lato">{speaker.user.email}</span>
                                </div>
                                {speaker.user.phone && (
                                    <div className="flex items-center gap-2 text-white/75">
                                        <i className="fas fa-phone"></i>
                                        <span className="font-lato">{speaker.user.phone}</span>
                                    </div>
                                )}
                            </div>

                            {(speaker.user.twitter || speaker.user.linkedin || speaker.user.website) && (
                                <div className="flex justify-center lg:justify-start space-x-4">
                                    {speaker.user.twitter && (
                                        <a
                                            href={speaker.user.twitter}
                                            target="_blank"
                                            rel="noopener noreferrer"
                                            className="flex h-12 w-12 items-center justify-center rounded-lg bg-white/10 text-white transition-all duration-300 hover:bg-white/15"
                                            style={{ background: 'rgba(29, 161, 242, 0.2)', color: '#1da1f2' }}
                                        >
                                            <i className="fab fa-twitter text-lg"></i>
                                        </a>
                                    )}
                                    {speaker.user.linkedin && (
                                        <a
                                            href={speaker.user.linkedin}
                                            target="_blank"
                                            rel="noopener noreferrer"
                                            className="flex h-12 w-12 items-center justify-center rounded-lg bg-white/10 text-white transition-all duration-300 hover:bg-white/15"
                                        >
                                            <i className="fab fa-linkedin text-lg"></i>
                                        </a>
                                    )}
                                    {speaker.user.website && (
                                        <a
                                            href={speaker.user.website}
                                            target="_blank"
                                            rel="noopener noreferrer"
                                            className="flex h-12 w-12 items-center justify-center rounded-lg bg-white/10 text-white transition-all duration-300 hover:bg-white/15"
                                        >
                                            <i className="fas fa-globe text-lg"></i>
                                        </a>
                                    )}
                                </div>
                            )}
                        </div>
                    </div>
                </div>
            </section>

            <section className="public-section bg-gray-50">
                <div className="section-shell">
                    <div className="grid grid-cols-1 lg:grid-cols-4 gap-8">
                        <div className="lg:col-span-1">
                            <div className="sticky top-6 overflow-hidden rounded-lg border border-gray-200 bg-white shadow-sm" data-aos="fade-right">
                                <div className="bg-primary p-6 text-center">
                                    <div className="mx-auto mb-4 flex h-16 w-16 items-center justify-center rounded-lg bg-white/10">
                                        <i className="fas fa-star text-2xl text-white"></i>
                                    </div>
                                    <h3 className="text-lg font-bold text-white font-montserrat">Speaker Profile</h3>
                                    <p className="text-sm font-lato text-white/70">Professional Details</p>
                                </div>

                                <div className="p-6 space-y-6">
                                    <div className="rounded-lg bg-primary/5 p-4 text-center">
                                        <div className="text-3xl font-bold font-montserrat mb-1" style={{ color: '#002147' }}>
                                            {speaker.events.length}
                                        </div>
                                        <p className="text-sm text-gray-600 font-lato">Speaking Events</p>
                                    </div>

                                    {expertiseList.length > 0 && (
                                        <div className="rounded-lg bg-secondary/5 p-4 text-center">
                                            <div className="mb-1 text-3xl font-bold font-montserrat text-secondary">
                                                {expertiseList.length}
                                            </div>
                                            <p className="text-sm text-gray-600 font-lato">Areas of Expertise</p>
                                        </div>
                                    )}

                                    <div className="space-y-3">
                                        <a
                                            href={`mailto:${speaker.user.email}`}
                                            className="enterprise-button enterprise-button-primary w-full justify-center py-3"
                                        >
                                            <i className="fas fa-envelope mr-2"></i>
                                            Contact Speaker
                                        </a>

                                        {speaker.user.phone && (
                                            <a
                                                href={`tel:${speaker.user.phone}`}
                                                className="enterprise-button enterprise-button-outline w-full justify-center py-3"
                                            >
                                                <i className="fas fa-phone mr-2"></i>
                                                Call Speaker
                                            </a>
                                        )}
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div className="lg:col-span-3 space-y-8">
                            {speaker.bio && (
                                <div className="overflow-hidden rounded-lg border border-gray-200 bg-white shadow-sm" data-aos="fade-up">
                                    <div className="border-b border-gray-200 bg-primary p-6">
                                        <div className="flex items-center">
                                            <div
                                                className="w-12 h-12 rounded-2xl flex items-center justify-center mr-4"
                                                style={{ background: 'rgba(255, 255, 255, 0.2)' }}
                                            >
                                                <i className="fas fa-user text-white text-xl"></i>
                                            </div>
                                            <div>
                                                <h2 className="text-2xl font-bold text-white font-montserrat">
                                                    About {speaker.user.name}
                                                </h2>
                                                <p className="font-lato text-white/70">Professional Background & Experience</p>
                                            </div>
                                        </div>
                                    </div>

                                    <div className="p-8">
                                        <div className="prose max-w-none text-gray-700 leading-relaxed font-lato text-lg">
                                            {speaker.bio.split('\n').map((paragraph, index) => (
                                                <p key={index}>{paragraph}</p>
                                            ))}
                                        </div>
                                    </div>
                                </div>
                            )}

                            {speaker.events.length > 0 && (
                                <div className="overflow-hidden rounded-lg border border-gray-200 bg-white shadow-sm" data-aos="fade-up" data-aos-delay="200">
                                    <div className="border-b border-gray-200 bg-secondary p-6">
                                        <div className="flex items-center">
                                            <div
                                                className="w-12 h-12 rounded-2xl flex items-center justify-center mr-4"
                                                style={{ background: 'rgba(255, 255, 255, 0.2)' }}
                                            >
                                                <i className="fas fa-calendar text-white text-xl"></i>
                                            </div>
                                            <div>
                                                <h2 className="text-2xl font-bold text-white font-montserrat">Speaking Events</h2>
                                                <p className="text-red-100 font-lato">
                                                    {speaker.events.length} upcoming presentations
                                                </p>
                                            </div>
                                        </div>
                                    </div>

                                    <div className="p-8">
                                        <div className="grid gap-6">
                                            {speaker.events.map((event) => (
                                                <div
                                                    key={event.slug}
                                                    className="group rounded-lg border border-gray-200 bg-gray-50 p-6 transition-all duration-300 hover:border-gray-300 hover:bg-white hover:shadow-sm"
                                                >
                                                    <div className="flex items-start gap-6">
                                                        <div className="shrink-0">
                                                            <img
                                                                src={`/storage/${event.program_cover}`}
                                                                alt={event.title}
                                                                className="w-20 h-20 rounded-2xl object-cover shadow-lg group-hover:scale-105 transition-transform duration-300"
                                                                onError={(e) => {
                                                                    (e.target as HTMLImageElement).src = 'https://placehold.co/600x400?text=Cover+Image+Missing';
                                                                }}
                                                            />
                                                        </div>
                                                        <div className="flex-1">
                                                            <div className="flex flex-col sm:flex-row sm:items-center justify-between mb-3">
                                                                <h3
                                                                    className="text-xl font-bold font-montserrat group-hover:text-red-600 transition-colors"
                                                                    style={{ color: '#002147' }}
                                                                >
                                                                    <Link href={route('events.show', event.slug)}>
                                                                        {event.title}
                                                                    </Link>
                                                                </h3>
                                                                <span className="inline-flex items-center rounded-full bg-accent/10 px-3 py-1 text-xs font-medium text-accent">
                                                                    <i className="fas fa-calendar-check mr-1"></i>
                                                                    {new Date(event.start_date).toLocaleDateString('en-US', {
                                                                        month: 'short',
                                                                        day: 'numeric',
                                                                        year: 'numeric',
                                                                    })}
                                                                </span>
                                                            </div>

                                                            {event.description && (
                                                                <p className="text-gray-600 font-lato leading-relaxed mb-3">
                                                                    {event.description.substring(0, 120)}
                                                                    {event.description.length > 120 ? '...' : ''}
                                                                </p>
                                                            )}

                                                            <div className="flex items-center gap-4 text-sm text-gray-500">
                                                                {event.location && (
                                                                    <div className="flex items-center">
                                                                        <i className="fas fa-map-marker-alt mr-1"></i>
                                                                        <span className="font-lato">{event.location}</span>
                                                                    </div>
                                                                )}
                                                                <div className="flex items-center">
                                                                    <i className="fas fa-users mr-1"></i>
                                                                    <span className="font-lato">
                                                                        {event.max_attendees ?? '500+'} attendees
                                                                    </span>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            ))}
                                        </div>
                                    </div>
                                </div>
                            )}

                            {expertiseList.length > 0 && (
                                <div className="overflow-hidden rounded-lg border border-gray-200 bg-white shadow-sm" data-aos="fade-up" data-aos-delay="400">
                                    <div className="border-b border-gray-200 bg-primary p-6">
                                        <div className="flex items-center">
                                            <div
                                                className="w-12 h-12 rounded-2xl flex items-center justify-center mr-4"
                                                style={{ background: 'rgba(255, 255, 255, 0.2)' }}
                                            >
                                                <i className="fas fa-lightbulb text-white text-xl"></i>
                                            </div>
                                            <div>
                                                <h2 className="text-2xl font-bold text-white font-montserrat">
                                                    Areas of Expertise
                                                </h2>
                                                <p className="text-blue-200 font-lato">
                                                    Professional skills and knowledge domains
                                                </p>
                                            </div>
                                        </div>
                                    </div>

                                    <div className="p-8">
                                        <div className="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">
                                            {expertiseList.map((skill, index) => (
                                                <div
                                                    key={index}
                                                    className="group rounded-lg border border-gray-200 bg-gray-50 p-4 text-center transition-all duration-300 hover:border-gray-300 hover:bg-white hover:shadow-sm"
                                                >
                                                    <div
                                                        className="w-12 h-12 rounded-2xl flex items-center justify-center mx-auto mb-3 transition-all duration-300 group-hover:scale-110"
                                                        style={{ background: bgColors[index % 3] }}
                                                    >
                                                        <i
                                                            className={`fas ${iconClasses[index % 3]} text-lg`}
                                                            style={{ color: iconColors[index % 3] }}
                                                        ></i>
                                                    </div>
                                                    <h3 className="font-semibold font-montserrat mb-1" style={{ color: '#002147' }}>
                                                        {skill.trim()}
                                                    </h3>
                                                    <p className="text-xs text-gray-500 font-lato">Specialization</p>
                                                </div>
                                            ))}
                                        </div>
                                    </div>
                                </div>
                            )}
                        </div>
                    </div>
                </div>
            </section>
        </GuestLayout>
    );
}
