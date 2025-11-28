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
    const iconColors = ['#002147', '#ed1c24', '#00a651'];
    const bgColors = ['rgba(0, 33, 71, 0.1)', 'rgba(237, 28, 36, 0.1)', 'rgba(0, 166, 81, 0.1)'];

    return (
        <GuestLayout>
            <Head title={`Speaker Profile: ${speaker.user.name}`} />

            {/* Enhanced Hero Section */}
            <section
                className="relative py-16 overflow-hidden"
                style={{ background: 'linear-gradient(135deg, #002147 0%, #003875 50%, #002147 100%)' }}
            >
                <div className="absolute inset-0 opacity-10">
                    <div
                        className="absolute top-10 left-10 w-32 h-32 rounded-full animate-pulse"
                        style={{ background: 'linear-gradient(135deg, #00a651, #ed1c24)' }}
                    ></div>
                    <div
                        className="absolute bottom-10 right-10 w-24 h-24 rounded-full animate-bounce"
                        style={{ background: 'linear-gradient(135deg, #ed1c24, #00a651)' }}
                    ></div>
                </div>

                <div className="relative z-10 container mx-auto px-4">
                    <nav className="mb-8" data-aos="fade-down">
                        <ul className="flex items-center space-x-2 text-sm font-lato">
                            <li>
                                <Link href={route('homepage')} className="text-blue-200 hover:text-white transition-colors">
                                    Home
                                </Link>
                            </li>
                            <li><i className="fas fa-chevron-right text-blue-300 text-xs"></i></li>
                            <li>
                                <Link href={route('events.index')} className="text-blue-200 hover:text-white transition-colors">
                                    Events
                                </Link>
                            </li>
                            <li><i className="fas fa-chevron-right text-blue-300 text-xs"></i></li>
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
                                              )}&background=00a651&color=fff&size=320&bold=true`
                                    }
                                    alt={speaker.user.name}
                                    className="w-40 h-40 rounded-3xl object-cover border-4 border-white shadow-2xl"
                                />
                                <div
                                    className="absolute -bottom-2 -right-2 px-3 py-1 rounded-full text-xs font-semibold"
                                    style={{
                                        background: 'linear-gradient(135deg, #00a651 0%, #15803d 100%)',
                                        color: 'white',
                                    }}
                                >
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
                                <p className="text-2xl font-semibold mb-4 font-montserrat" style={{ color: '#00a651' }}>
                                    {speaker.user.headline}
                                </p>
                            )}

                            <div className="flex flex-col sm:flex-row gap-4 mb-6 justify-center lg:justify-start">
                                <div className="flex items-center gap-2 text-blue-200">
                                    <i className="fas fa-envelope"></i>
                                    <span className="font-lato">{speaker.user.email}</span>
                                </div>
                                {speaker.user.phone && (
                                    <div className="flex items-center gap-2 text-blue-200">
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
                                            className="w-12 h-12 rounded-2xl flex items-center justify-center transition-all duration-300 hover:scale-110"
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
                                            className="w-12 h-12 rounded-2xl flex items-center justify-center transition-all duration-300 hover:scale-110"
                                            style={{ background: 'rgba(0, 119, 181, 0.2)', color: '#0077b5' }}
                                        >
                                            <i className="fab fa-linkedin text-lg"></i>
                                        </a>
                                    )}
                                    {speaker.user.website && (
                                        <a
                                            href={speaker.user.website}
                                            target="_blank"
                                            rel="noopener noreferrer"
                                            className="w-12 h-12 rounded-2xl flex items-center justify-center transition-all duration-300 hover:scale-110"
                                            style={{ background: 'rgba(0, 166, 81, 0.2)', color: '#00a651' }}
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

            <section className="py-16" style={{ background: 'linear-gradient(135deg, #f8fafc 0%, #e2e8f0 100%)' }}>
                <div className="container mx-auto px-4">
                    <div className="grid grid-cols-1 lg:grid-cols-4 gap-8">
                        <div className="lg:col-span-1">
                            <div
                                className="bg-white rounded-3xl shadow-xl border border-gray-100 overflow-hidden sticky top-6"
                                data-aos="fade-right"
                            >
                                <div
                                    className="p-6 text-center"
                                    style={{ background: 'linear-gradient(135deg, #002147 0%, #003875 100%)' }}
                                >
                                    <div
                                        className="w-16 h-16 rounded-2xl flex items-center justify-center mx-auto mb-4"
                                        style={{ background: 'rgba(0, 166, 81, 0.2)' }}
                                    >
                                        <i className="fas fa-star text-2xl" style={{ color: '#00a651' }}></i>
                                    </div>
                                    <h3 className="text-lg font-bold text-white font-montserrat">Speaker Profile</h3>
                                    <p className="text-blue-200 text-sm font-lato">Professional Details</p>
                                </div>

                                <div className="p-6 space-y-6">
                                    <div className="text-center p-4 rounded-2xl" style={{ background: 'rgba(0, 33, 71, 0.05)' }}>
                                        <div className="text-3xl font-bold font-montserrat mb-1" style={{ color: '#002147' }}>
                                            {speaker.events.length}
                                        </div>
                                        <p className="text-sm text-gray-600 font-lato">Speaking Events</p>
                                    </div>

                                    {expertiseList.length > 0 && (
                                        <div
                                            className="text-center p-4 rounded-2xl"
                                            style={{ background: 'rgba(237, 28, 36, 0.05)' }}
                                        >
                                            <div className="text-3xl font-bold font-montserrat mb-1" style={{ color: '#ed1c24' }}>
                                                {expertiseList.length}
                                            </div>
                                            <p className="text-sm text-gray-600 font-lato">Areas of Expertise</p>
                                        </div>
                                    )}

                                    <div className="space-y-3">
                                        <a
                                            href={`mailto:${speaker.user.email}`}
                                            className="w-full py-3 px-4 rounded-xl text-white font-semibold font-montserrat transition-all duration-300 hover:scale-105 flex items-center justify-center"
                                            style={{ background: 'linear-gradient(135deg, #002147 0%, #ed1c24 100%)' }}
                                        >
                                            <i className="fas fa-envelope mr-2"></i>
                                            Contact Speaker
                                        </a>

                                        {speaker.user.phone && (
                                            <a
                                                href={`tel:${speaker.user.phone}`}
                                                className="w-full py-3 px-4 border-2 rounded-xl font-semibold font-montserrat transition-all duration-300 hover:bg-gray-50 flex items-center justify-center"
                                                style={{ borderColor: '#002147', color: '#002147' }}
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
                                <div
                                    className="bg-white rounded-3xl shadow-xl border border-gray-100 overflow-hidden"
                                    data-aos="fade-up"
                                >
                                    <div
                                        className="p-6 border-b border-gray-100"
                                        style={{ background: 'linear-gradient(135deg, #00a651 0%, #15803d 100%)' }}
                                    >
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
                                                <p className="text-green-100 font-lato">Professional Background & Experience</p>
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
                                <div
                                    className="bg-white rounded-3xl shadow-xl border border-gray-100 overflow-hidden"
                                    data-aos="fade-up"
                                    data-aos-delay="200"
                                >
                                    <div
                                        className="p-6 border-b border-gray-100"
                                        style={{ background: 'linear-gradient(135deg, #ed1c24 0%, #dc2626 100%)' }}
                                    >
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
                                                    className="group p-6 rounded-2xl border-2 border-gray-100 hover:border-gray-200 hover:shadow-lg transition-all duration-300"
                                                    style={{ background: 'linear-gradient(135deg, #ffffff 0%, #f8fafc 100%)' }}
                                                >
                                                    <div className="flex items-start gap-6">
                                                        <div className="shrink-0">
                                                            <img
                                                                src={`/storage/${event.program_cover}`}
                                                                alt={event.title}
                                                                className="w-20 h-20 rounded-2xl object-cover shadow-lg group-hover:scale-105 transition-transform duration-300"
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
                                                                <span
                                                                    className="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium"
                                                                    style={{
                                                                        background: 'rgba(0, 166, 81, 0.1)',
                                                                        color: '#00a651',
                                                                    }}
                                                                >
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
                                <div
                                    className="bg-white rounded-3xl shadow-xl border border-gray-100 overflow-hidden"
                                    data-aos="fade-up"
                                    data-aos-delay="400"
                                >
                                    <div
                                        className="p-6 border-b border-gray-100"
                                        style={{ background: 'linear-gradient(135deg, #002147 0%, #003875 100%)' }}
                                    >
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
                                                    className="group p-4 rounded-2xl border-2 border-gray-100 hover:border-blue-200 hover:shadow-lg transition-all duration-300 text-center"
                                                    style={{ background: 'linear-gradient(135deg, #ffffff 0%, #f8fafc 100%)' }}
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
