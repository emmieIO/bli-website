import { Head, Link } from '@inertiajs/react';
import GuestLayout from '@/Layouts/GuestLayout';
import React from 'react';
import {
    ArrowRight,
    BookOpen,
    CalendarDays,
    CheckCircle2,
    Globe2,
    GraduationCap,
    MapPin,
    ShieldCheck,
    Star,
    Users,
} from 'lucide-react';

interface Category {
    id: number;
    name: string;
    image: string;
    courses_count?: number;
    courses?: any[];
}

interface Stats {
    total_courses: number;
    total_students: number;
    total_instructors: number;
    total_enrollments: number;
    active_events: number;
}

interface Event {
    id: number;
    slug: string;
    title: string;
    theme?: string;
    program_cover: string;
    start_date: string;
    end_date: string;
    mode?: 'online' | 'offline' | 'hybrid';
    physical_address?: string;
    location?: string;
    entry_fee: number;
    slots_remaining?: number;
    is_registered?: boolean;
}

interface IndexProps {
    events: Event[];
    categories: Category[];
    stats: Stats;
}

const valueProps = [
    {
        title: 'Leadership formation',
        description: 'Programs designed to strengthen judgment, conviction, and practical execution.',
        icon: ShieldCheck,
    },
    {
        title: 'Structured learning',
        description: 'Clear course paths and guided teaching that make progress easy to follow.',
        icon: BookOpen,
    },
    {
        title: 'Real-world application',
        description: 'Teaching that translates into ministry, business, education, and public impact.',
        icon: Globe2,
    },
];

const learningSteps = [
    {
        title: 'Choose a learning track',
        description: 'Start with a course category that matches the kind of leadership growth you need right now.',
    },
    {
        title: 'Learn with clarity',
        description: 'Move through lessons, resources, and guided teaching in a format that feels focused rather than overwhelming.',
    },
    {
        title: 'Apply it in real life',
        description: 'Use events, community, and practical instruction to turn ideas into stronger leadership habits.',
    },
];

export default function Index({ events, categories, stats }: IndexProps) {
    const featuredEvents = events.slice(0, 3);
    const featuredCategories = categories.slice(0, 6);
    const proofStats = [
        { label: 'Learners Reached', value: formatCompact(stats.total_students) },
        { label: 'Courses Published', value: formatCompact(stats.total_courses) },
        { label: 'Active Events', value: formatCompact(stats.active_events) },
        { label: 'Instructor Network', value: formatCompact(stats.total_instructors) },
    ];

    return (
        <GuestLayout>
            <Head title="Beacon Leadership Institute" />

            {/* Hero */}
            <section className="relative overflow-hidden bg-[linear-gradient(180deg,_#f4f8fc_0%,_#ffffff_50%,_#f8fafc_100%)]">
                <div className="absolute inset-x-0 top-0 h-[32rem] bg-[radial-gradient(circle_at_20%_20%,_rgba(0,33,71,0.06),_transparent_50%),radial-gradient(circle_at_80%_10%,_rgba(201,146,0,0.08),_transparent_40%)]" />
                <div className="section-shell relative flex min-h-[calc(100vh-80px)] items-center py-8 md:py-10 lg:py-12">
                    <div className="grid items-center gap-10 lg:grid-cols-[1.05fr_0.95fr] lg:gap-12">
                        <div className="max-w-3xl">
                            <div className="inline-flex items-center gap-3 border border-accent-300/30 bg-accent-50/80 px-5 py-2">
                                <div className="school-crest">
                                    <GraduationCap className="h-5 w-5" />
                                </div>
                                <span className="section-label text-[11px]">Beacon Leadership Institute</span>
                            </div>

                            <h1 className="academic-heading mt-6 text-4xl font-bold leading-[1.05] tracking-[-0.03em] text-primary md:text-5xl lg:text-6xl">
                                Forming leaders with
                                <span className="text-accent-500"> spiritual depth</span>
                                {' '}and practical clarity.
                            </h1>

                            <p className="mt-5 max-w-2xl text-base leading-7 text-slate-600 md:text-lg">
                                Beacon Leadership Institute provides structured courses, live events, and applied learning
                                experiences for emerging and established leaders across ministry, business, education, and
                                public service.
                            </p>

                            <div className="mt-8 flex flex-col gap-3 sm:flex-row">
                                <Link
                                    href={route('register')}
                                    className="inline-flex items-center justify-center gap-2 bg-primary px-7 py-4 text-sm font-semibold text-white transition hover:bg-primary-600"
                                >
                                    Enroll now
                                    <ArrowRight className="h-4 w-4" />
                                </Link>
                                <Link
                                    href={route('courses.index')}
                                    className="inline-flex items-center justify-center gap-2 border border-slate-200 bg-white px-7 py-4 text-sm font-semibold text-slate-700 transition hover:border-slate-300 hover:bg-slate-50"
                                >
                                    Browse programs
                                    <BookOpen className="h-4 w-4" />
                                </Link>
                            </div>
                        </div>

                        <div className="relative">
                            <div className="absolute -left-6 top-8 hidden h-32 w-32 rounded-full bg-accent-200/30 blur-3xl lg:block" />
                            <div className="absolute -right-8 bottom-8 hidden h-40 w-40 rounded-full bg-primary-100/30 blur-3xl lg:block" />

                            <div className="border border-slate-200/80 bg-white p-2 shadow-[0_24px_70px_rgba(15,23,42,0.07)]">
                                <div className="overflow-hidden bg-slate-100">
                                    <img
                                        src="/images/learning-platform/banner.png"
                                        alt="Beacon Leadership Institute"
                                        className="h-[220px] w-full object-cover md:h-[300px] lg:h-[360px]"
                                    />
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </section>

            {/* At a Glance */}
            <section className="bg-white py-16 md:py-20">
                <div className="section-shell">
                    <div className="grid gap-10 lg:grid-cols-[0.8fr_1.2fr] lg:items-start">
                        <div className="max-w-xl">
                            <p className="section-label">At a Glance</p>
                            <h2 className="academic-heading mt-3 text-3xl font-bold tracking-tight text-primary md:text-4xl">
                                Trusted learning for leaders.
                            </h2>
                            <p className="mt-4 text-sm leading-7 text-slate-600 md:text-base">
                                A clear view of the scale, structure, and focus behind the institute.
                            </p>
                        </div>

                        <div className="grid gap-4 md:grid-cols-2">
                            {proofStats.map((stat) => (
                                <HeroMetric key={stat.label} label={stat.label} value={stat.value} />
                            ))}
                        </div>
                    </div>

                    <div className="mt-12 grid gap-5 lg:grid-cols-3">
                        {valueProps.map(({ title, description, icon: Icon }) => (
                            <div
                                key={title}
                                className="border border-slate-200 bg-white p-7 shadow-[0_12px_30px_rgba(15,23,42,0.04)]"
                            >
                                <div className="inline-flex bg-accent-50 p-3 text-accent-500">
                                    <Icon className="h-5 w-5" />
                                </div>
                                <h2 className="academic-heading mt-5 text-xl font-bold tracking-tight text-primary">{title}</h2>
                                <p className="mt-3 text-sm leading-7 text-slate-600">{description}</p>
                            </div>
                        ))}
                    </div>
                </div>
            </section>

            {/* Academic Programs */}
            <section className="bg-slate-50 py-16 md:py-20">
                <div className="section-shell">
                    <div className="flex flex-col gap-6 lg:flex-row lg:items-end lg:justify-between">
                        <div className="max-w-3xl">
                            <p className="section-label">Academic Programs</p>
                            <h2 className="academic-heading mt-3 text-3xl font-bold tracking-tight text-primary md:text-4xl">
                                Explore learning tracks.
                            </h2>
                            <p className="mt-4 max-w-2xl text-sm leading-7 text-slate-600 md:text-base">
                                Browse the core categories and enter the learning path that fits your growth.
                            </p>
                        </div>

                        <Link
                            href={route('courses.index')}
                            className="inline-flex items-center gap-2 text-sm font-semibold text-primary transition hover:text-accent-500"
                        >
                            See all courses
                            <ArrowRight className="h-4 w-4" />
                        </Link>
                    </div>

                    <div className="mt-10 grid gap-4 md:grid-cols-2 xl:grid-cols-3">
                        {featuredCategories.length > 0 ? (
                            featuredCategories.map((category, index) => {
                                const count = category.courses?.length || category.courses_count || 0;

                                return (
                                    <Link
                                        key={category.id}
                                        href={route('courses.index')}
                                        className="group border border-slate-200 bg-white p-6 shadow-sm transition hover:-translate-y-1 hover:shadow-md"
                                    >
                                        <div className="flex items-start justify-between gap-4">
                                            <div className="inline-flex bg-primary p-3 text-accent-400">
                                                <BookOpen className="h-5 w-5" />
                                            </div>
                                            <span className="bg-accent-50 px-3 py-1 text-xs font-bold uppercase tracking-[0.16em] text-accent-600">
                                                Track {String(index + 1).padStart(2, '0')}
                                            </span>
                                        </div>

                                        <h3 className="academic-heading mt-6 text-xl font-bold tracking-tight text-primary">
                                            {capitalize(category.name)}
                                        </h3>
                                        <p className="mt-3 text-sm leading-7 text-slate-600">
                                            {count > 0
                                                ? `${count} course${count === 1 ? '' : 's'} available in this track.`
                                                : 'A structured track prepared for focused leadership development.'}
                                        </p>

                                        <div className="mt-6 inline-flex items-center gap-2 text-sm font-semibold text-slate-500 transition group-hover:text-primary">
                                            Explore learning track
                                            <ArrowRight className="h-4 w-4" />
                                        </div>
                                    </Link>
                                );
                            })
                        ) : (
                            <div className="col-span-full border border-dashed border-slate-300 bg-white px-6 py-14 text-center">
                                <p className="text-lg font-semibold text-slate-800">Learning tracks are being prepared</p>
                                <p className="mt-2 text-sm text-slate-500">New course categories will appear here as they are published.</p>
                            </div>
                        )}
                    </div>
                </div>
            </section>

            {/* How It Works */}
            <section className="bg-white py-16 md:py-20">
                <div className="section-shell">
                    <div className="grid gap-12 lg:grid-cols-[0.82fr_1.18fr] lg:items-start">
                        <div>
                            <p className="section-label">Your Journey</p>
                            <h2 className="academic-heading mt-3 text-3xl font-bold tracking-tight text-primary md:text-4xl">
                                A simple path to start.
                            </h2>
                            <p className="mt-4 text-sm leading-7 text-slate-600 md:text-base">
                                Pick a learning track, study with clarity, and apply what you learn in real settings.
                            </p>
                        </div>

                        <div className="grid gap-4">
                            {learningSteps.map((step, index) => (
                                <div
                                    key={step.title}
                                    className="border border-slate-200 bg-slate-50/70 p-6"
                                >
                                    <div className="flex flex-col gap-4 md:flex-row md:items-start md:justify-between">
                                        <div>
                                            <span className="section-label text-accent-600">
                                                Step {index + 1}
                                            </span>
                                            <h3 className="academic-heading mt-2 text-xl font-bold text-primary">{step.title}</h3>
                                            <p className="mt-3 text-sm leading-7 text-slate-600">{step.description}</p>
                                        </div>
                                        <CheckCircle2 className="h-6 w-6 shrink-0 text-accent-500" />
                                    </div>
                                </div>
                            ))}
                        </div>
                    </div>
                </div>
            </section>

            {/* Upcoming Events / Gatherings */}
            <section className="bg-primary py-16 text-white md:py-20">
                <div className="section-shell">
                    <div className="flex flex-col gap-6 lg:flex-row lg:items-end lg:justify-between">
                        <div className="max-w-3xl">
                            <p className="section-label text-accent-300">Upcoming Gatherings</p>
                            <h2 className="academic-heading mt-3 text-3xl font-bold tracking-tight !text-white md:text-4xl">
                                Leadership events worth showing up for.
                            </h2>
                            <p className="mt-4 text-sm leading-7 text-slate-300 md:text-base">
                                Join live experiences that extend learning beyond the classroom.
                            </p>
                        </div>

                        <Link
                            href={route('events.index')}
                            className="inline-flex items-center gap-2 text-sm font-semibold text-white transition hover:text-accent-300"
                        >
                            View all events
                            <ArrowRight className="h-4 w-4" />
                        </Link>
                    </div>

                    <div className="mt-10 grid gap-6 lg:grid-cols-3">
                        {featuredEvents.length > 0 ? (
                            featuredEvents.map((event) => (
                                <article
                                    key={event.id}
                                    className="group flex flex-col overflow-hidden border border-white/10 bg-white/5 shadow-sm transition hover:-translate-y-1 hover:bg-white/[0.07] hover:shadow-xl"
                                >
                                    <div className="relative h-48 overflow-hidden bg-slate-900/60">
                                        <img
                                            src={`/storage/${event.program_cover}`}
                                            alt={event.title}
                                            className="h-full w-full object-cover transition duration-500 group-hover:scale-105"
                                            onError={(e) => {
                                                (e.target as HTMLImageElement).src = 'https://placehold.co/800x500?text=Event';
                                            }}
                                        />
                                        <div className="absolute left-3 top-3 inline-flex items-center gap-1.5 rounded bg-white/10 px-2.5 py-1 text-[10px] font-bold uppercase tracking-[0.16em] text-slate-200 backdrop-blur-sm">
                                            <CalendarDays className="h-3 w-3" />
                                            {event.mode || 'Event'}
                                        </div>
                                    </div>

                                    <div className="flex flex-1 flex-col p-5">
                                        <h3 className="academic-heading text-lg font-bold leading-snug text-white">
                                            <Link href={route('events.show', event.slug)} className="transition hover:text-accent-300 line-clamp-2">
                                                {event.title}
                                            </Link>
                                        </h3>

                                        {event.theme ? (
                                            <p className="mt-2 text-sm leading-relaxed text-slate-400 line-clamp-2">{event.theme}</p>
                                        ) : (
                                            <div className="mt-2" />
                                        )}

                                        <div className="mt-auto space-y-2 pt-4 text-sm text-slate-400">
                                            <div className="flex items-start gap-2">
                                                <CalendarDays className="mt-0.5 h-3.5 w-3.5 shrink-0 text-accent-400" />
                                                <span>{formatDate(event.start_date)} to {formatDate(event.end_date)}</span>
                                            </div>
                                            <div className="flex items-start gap-2">
                                                <MapPin className="mt-0.5 h-3.5 w-3.5 shrink-0 text-accent-400" />
                                                <span className="line-clamp-1">{event.physical_address || event.location || 'TBD'}</span>
                                            </div>
                                        </div>

                                        <div className="mt-4 flex items-center justify-between border-t border-white/10 pt-4">
                                            <span className="text-sm font-bold text-white">
                                                {event.entry_fee > 0 ? formatCurrency(event.entry_fee) : 'Free'}
                                            </span>
                                            <Link
                                                href={route('events.show', event.slug)}
                                                className="inline-flex items-center gap-1.5 text-sm font-semibold text-accent-300 transition hover:text-white"
                                            >
                                                Details
                                                <ArrowRight className="h-3.5 w-3.5" />
                                            </Link>
                                        </div>
                                    </div>
                                </article>
                            ))
                        ) : (
                            <div className="col-span-full border border-dashed border-white/20 bg-white/5 px-6 py-14 text-center">
                                <p className="text-lg font-semibold text-white">No featured events yet</p>
                                <p className="mt-2 text-sm text-slate-300">Upcoming gatherings will appear here as soon as they are published.</p>
                            </div>
                        )}
                    </div>
                </div>
            </section>

            {/* CTA */}
            <section className="bg-white py-16 md:py-20">
                <div className="section-shell">
                    <div className="border border-slate-200 bg-[linear-gradient(135deg,_#f8fafc_0%,_#fef9e7_100%)] p-8 md:p-12">
                        <div className="grid gap-8 lg:grid-cols-[1fr_auto] lg:items-center">
                            <div className="max-w-3xl">
                                <p className="section-label">Get Started</p>
                                <h2 className="academic-heading mt-3 text-3xl font-bold tracking-tight text-primary md:text-4xl">
                                    Start your learning journey.
                                </h2>
                                <p className="mt-4 text-sm leading-7 text-slate-600 md:text-base">
                                    Create an account, explore courses, and join leadership experiences designed for lasting growth.
                                </p>
                            </div>

                            <div className="flex flex-col gap-3 sm:flex-row lg:flex-col">
                                <Link
                                    href={route('register')}
                                    className="inline-flex items-center justify-center gap-2 bg-primary px-6 py-4 text-sm font-semibold text-white transition hover:bg-primary-600"
                                >
                                    Create account
                                </Link>
                                <Link
                                    href={route('events.index')}
                                    className="inline-flex items-center justify-center gap-2 border border-slate-200 bg-white px-6 py-4 text-sm font-semibold text-slate-700 transition hover:bg-slate-50"
                                >
                                    Explore events
                                </Link>
                            </div>
                        </div>
                    </div>
                </div>
            </section>
        </GuestLayout>
    );
}

function HeroMetric({ label, value }: { label: string; value: string }) {
    return (
        <div className="border-b border-slate-200 pb-5">
            <p className="academic-heading text-3xl font-bold tracking-tight text-primary">{value}</p>
            <p className="mt-2 text-xs font-bold uppercase tracking-[0.14em] text-slate-500">{label}</p>
        </div>
    );
}

function capitalize(value: string) {
    return value.charAt(0).toUpperCase() + value.slice(1);
}

function formatDate(dateString: string) {
    const date = new Date(dateString);
    return date.toLocaleDateString('en-US', {
        month: 'short',
        day: 'numeric',
        year: 'numeric',
    });
}

function formatCurrency(amount: number) {
    return `₦${amount.toLocaleString('en-NG', { minimumFractionDigits: 2 })}`;
}

function formatCompact(value: number) {
    return new Intl.NumberFormat('en', {
        notation: 'compact',
        maximumFractionDigits: 1,
    }).format(value);
}
