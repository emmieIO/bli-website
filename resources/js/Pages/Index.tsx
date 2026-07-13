import { Head, Link } from '@inertiajs/react';
import GuestLayout from '@/Layouts/GuestLayout';
import {
    ArrowRight,
    CalendarDays,
    CheckCircle2,
    Globe2,
    GraduationCap,
    MapPin,
    ShieldCheck,
    Users,
    Sparkles,
    TrendingUp,
} from 'lucide-react';

interface Stats {
    total_students: number;
    total_instructors: number;
    active_events: number;
}

interface Event {
    id: number;
    slug: string;
    title: string;
    theme?: string;
    program_cover: string | null;
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
    stats: Stats;
}

const valueProps = [
    { title: 'Leadership formation', description: 'Programs designed to strengthen judgment, conviction, and practical execution.', icon: ShieldCheck, color: 'navy' as const },
    { title: 'Live formation', description: 'Gatherings and guided experiences that keep leadership development practical and relational.', icon: CalendarDays, color: 'lime' as const },
    { title: 'Real-world application', description: 'Teaching that translates into ministry, business, education, and public impact.', icon: Globe2, color: 'red' as const },
];

const learningSteps = [
    { title: 'Join a leadership gathering', description: 'Start with an event or formation experience that matches the kind of leadership growth you need right now.' },
    { title: 'Engage with clarity', description: 'Participate in guided teaching, conversations, and resources that feel focused rather than overwhelming.' },
    { title: 'Apply it in real life', description: 'Use events, community, and practical instruction to turn ideas into stronger leadership habits.' },
];

const formationPaths = [
    {
        label: 'Start together',
        title: 'Events and live programs',
        description: 'Enter through a public gathering, focused track, or leadership conversation built around a clear outcome.',
        href: 'events.index',
        action: 'Explore events',
        icon: CalendarDays,
    },
    {
        label: 'Go deeper',
        title: 'Goal-based mentorship',
        description: 'Turn insight into a personal development plan with sessions, milestones, shared resources, and accountability.',
        href: 'mentorship.index',
        action: 'Meet the mentors',
        icon: Users,
    },
];

export default function Index({ events, stats }: IndexProps) {
    const featuredEvents = events.slice(0, 3);
    const proofStats = [
        { label: 'Learners Reached', value: formatCompact(stats.total_students), icon: Users },
        { label: 'Active Events', value: formatCompact(stats.active_events), icon: CalendarDays },
        { label: 'Instructor Network', value: formatCompact(stats.total_instructors), icon: GraduationCap },
    ];

    return (
        <GuestLayout>
            <Head title="Beacon Leadership Institute" />

            {/* Keep the hero image full bleed so approved campaign photography can be swapped without changing the layout. */}
            <section className="relative isolate min-h-[590px] overflow-hidden bg-primary sm:min-h-[640px]">
                <img
                    src="/assets/img/banner.png"
                    alt="A developing leader prepared for learning"
                    className="absolute inset-0 h-full w-full object-cover object-[64%_center] saturate-[0.85] contrast-[1.08] sm:object-center"
                />
                {/* A single tint unifies the image and brand palette while preserving the people in the photograph. */}
                <div className="absolute inset-0 bg-primary/75 mix-blend-multiply" />
                <div className="absolute inset-0 bg-slate-950/20" />

                <div className="section-shell relative flex min-h-[590px] items-center py-14 sm:min-h-[640px] sm:py-20">
                    <div className="max-w-[46rem]">
                        <p className="flex items-center gap-3 text-xs font-bold uppercase text-accent-300">
                            <span className="h-px w-8 bg-accent-300" aria-hidden="true" />
                            Beacon Leadership Institute
                        </p>
                        <h1 className="mt-5 max-w-[44rem] text-4xl font-bold leading-[1.08] text-white sm:text-5xl lg:text-[3.25rem]">
                            Leadership formation for people called to influence.
                        </h1>
                        <p className="mt-5 max-w-2xl text-base leading-7 text-slate-200 sm:text-lg sm:leading-8">
                            Build spiritual depth, practical clarity, and the capacity to lead well across ministry, business, education, and public service.
                        </p>

                        <div className="mt-8 flex flex-col gap-3 sm:flex-row">
                            <Link href={route('events.index')} className="inline-flex items-center justify-center gap-2 rounded-md bg-accent px-6 py-3.5 text-sm font-semibold text-white transition hover:bg-accent-600">
                                Explore current programs <ArrowRight size={16} />
                            </Link>
                            <Link href={route('mentorship.index')} className="inline-flex items-center justify-center gap-2 rounded-md border border-white/50 bg-white/10 px-6 py-3.5 text-sm font-semibold text-white backdrop-blur-sm transition hover:bg-white/20">
                                Find a mentor <Users size={16} />
                            </Link>
                        </div>

                        <dl className="mt-10 grid max-w-2xl grid-cols-3 gap-3 border-t border-white/25 pt-6 sm:gap-8">
                            {proofStats.map((stat) => (
                                <div key={stat.label} className="min-w-0">
                                    <dt className="text-[10px] font-semibold uppercase text-slate-300 sm:text-xs">{stat.label}</dt>
                                    <dd className="mt-1 text-2xl font-bold text-white sm:text-3xl">{stat.value}</dd>
                                </div>
                            ))}
                        </dl>
                    </div>
                </div>

                <div className="absolute inset-x-0 bottom-0 h-1 bg-accent" aria-hidden="true" />
            </section>

            {/* Value Props */}
            <section className="relative bg-white py-16 md:py-24">
                <div className="section-shell">
                    <div className="mx-auto max-w-2xl text-center mb-14">
                        <div className="inline-flex items-center gap-1.5 rounded-md bg-accent-50 px-2.5 py-1 text-[11px] font-semibold uppercase tracking-wider text-accent">
                            <TrendingUp size={12} /> Why BLI
                        </div>
                        <h2 className="mt-4 text-3xl font-bold tracking-tight text-primary md:text-4xl">
                            Trusted learning for leaders.
                        </h2>
                        <p className="mt-3 text-sm leading-7 text-slate-500 md:text-base">
                            A clear view of the scale, structure, and focus behind the institute.
                        </p>
                    </div>

                    <div className="grid gap-5 md:grid-cols-3">
                        {valueProps.map(({ title, description, icon: Icon, color }) => {
                            const colors = {
                                navy: { bg: 'bg-primary-50', text: 'text-primary', ring: 'ring-primary-100' },
                                lime: { bg: 'bg-lime-50', text: 'text-lime-600', ring: 'ring-lime-100' },
                                red:  { bg: 'bg-accent-50', text: 'text-accent', ring: 'ring-accent-100' },
                            };
                            const c = colors[color];
                            return (
                                <div key={title} className="group rounded-lg border border-slate-200 bg-white p-7 transition hover:border-slate-300 hover:shadow-sm">
                                    <div className={`inline-flex rounded-lg p-3 ${c.bg} ${c.text} ring-1 ring-inset ${c.ring}`}>
                                        <Icon size={20} />
                                    </div>
                                    <h2 className="mt-5 text-lg font-semibold tracking-tight text-slate-900">{title}</h2>
                                    <p className="mt-2 text-sm leading-7 text-slate-500">{description}</p>
                                </div>
                            );
                        })}
                    </div>
                </div>
            </section>

            {/* Public formation pathways */}
            <section className="border-y border-slate-200 bg-slate-50 py-14 md:py-20">
                <div className="section-shell">
                    <div className="grid gap-8 lg:grid-cols-[0.72fr_1.28fr] lg:items-start">
                        <div>
                            <p className="text-xs font-bold uppercase text-accent-700">Choose your next step</p>
                            <h2 className="mt-3 text-3xl font-bold text-primary">Formation should lead somewhere.</h2>
                            <p className="mt-4 max-w-md text-sm leading-7 text-slate-600">
                                Begin in community through an event, then deepen what matters through focused mentorship and accountable action.
                            </p>
                            {/* Replace with approved photography from a real BLI mentorship engagement when available. */}
                            <figure className="mt-8 overflow-hidden rounded-md bg-white shadow-sm">
                                <img
                                    src="/assets/img/mentorship-session.png"
                                    alt="A mentor helping an emerging leader clarify his development goal"
                                    className="aspect-[4/3] w-full object-cover"
                                />
                                <figcaption className="border-t border-slate-200 px-4 py-3 text-xs font-medium text-slate-500">
                                    Focused guidance turns insight into an accountable development plan.
                                </figcaption>
                            </figure>
                        </div>

                        <div className="divide-y divide-slate-200 border-y border-slate-200">
                            {formationPaths.map(({ label, title, description, href, action, icon: Icon }) => (
                                <div key={title} className="grid gap-5 py-6 sm:grid-cols-[48px_1fr_auto] sm:items-center">
                                    <span className="flex h-12 w-12 items-center justify-center rounded-md bg-primary text-white">
                                        <Icon size={20} />
                                    </span>
                                    <div>
                                        <p className="text-[11px] font-bold uppercase text-accent-700">{label}</p>
                                        <h3 className="mt-1 text-lg font-semibold text-slate-950">{title}</h3>
                                        <p className="mt-2 text-sm leading-6 text-slate-600">{description}</p>
                                    </div>
                                    <Link href={route(href)} className="inline-flex items-center gap-2 text-sm font-semibold text-primary hover:text-accent-700">
                                        {action} <ArrowRight size={15} />
                                    </Link>
                                </div>
                            ))}
                        </div>
                    </div>
                </div>
            </section>

            {/* How It Works */}
            <section className="bg-slate-50 py-16 md:py-24">
                <div className="section-shell">
                    <div className="grid gap-12 lg:grid-cols-[0.82fr_1.18fr] lg:items-start">
                        <div>
                            <div className="inline-flex items-center gap-1.5 rounded-md bg-accent-50 px-2.5 py-1 text-[11px] font-semibold uppercase tracking-wider text-accent">
                                <CheckCircle2 size={12} /> Your Journey
                            </div>
                            <h2 className="mt-4 text-3xl font-bold tracking-tight text-primary md:text-4xl">
                                A simple path to start.
                            </h2>
                            <p className="mt-3 text-sm leading-7 text-slate-500 md:text-base">
                                Join a gathering, engage with clarity, and apply what you learn in real settings.
                            </p>

                            <Link href={route('register')} className="mt-6 inline-flex items-center gap-2 rounded-lg bg-accent px-5 py-3 text-sm font-semibold text-white transition hover:bg-accent-600 shadow-sm">
                                Begin your journey <ArrowRight size={16} />
                            </Link>
                        </div>

                        <div className="grid gap-4">
                            {learningSteps.map((step, index) => (
                                <div key={step.title} className="group rounded-lg border border-slate-200 bg-white p-6 transition hover:border-accent-200 hover:shadow-sm">
                                    <div className="flex gap-4">
                                        <div className="flex h-8 w-8 shrink-0 items-center justify-center rounded-md bg-accent text-sm font-bold text-white mt-0.5">
                                            {index + 1}
                                        </div>
                                        <div>
                                            <h3 className="text-lg font-semibold tracking-tight text-slate-900">{step.title}</h3>
                                            <p className="mt-1.5 text-sm leading-7 text-slate-500">{step.description}</p>
                                        </div>
                                    </div>
                                </div>
                            ))}
                        </div>
                    </div>
                </div>
            </section>

            {/* Events */}
            <section className="bg-primary py-16 md:py-24">
                <div className="section-shell">
                    <div className="mb-10 grid gap-8 lg:grid-cols-[0.8fr_1.2fr] lg:items-end">
                        <div className="max-w-3xl">
                            <div className="inline-flex items-center gap-1.5 rounded-md bg-accent/20 px-2.5 py-1 text-[11px] font-semibold uppercase tracking-wider text-accent-200">
                                <CalendarDays size={12} /> Upcoming Gatherings
                            </div>
                            <h2 className="mt-4 text-3xl font-bold tracking-tight text-white md:text-4xl">
                                Leadership events worth showing up for.
                            </h2>
                            <p className="mt-3 text-sm leading-7 text-primary-200 md:text-base">
                                Join live experiences that extend learning beyond the classroom.
                            </p>
                        </div>
                        {/* This establishes the event atmosphere even before event-specific covers are uploaded. */}
                        <div className="relative overflow-hidden rounded-md border border-white/10">
                            <img src="/assets/img/leadership-workshop.png" alt="Leaders participating in a facilitated workshop" className="aspect-[16/7] w-full object-cover" />
                            <div className="absolute inset-x-0 bottom-0 flex items-center justify-between bg-primary/90 px-4 py-3">
                                <span className="text-xs font-semibold uppercase text-primary-100">Live formation in community</span>
                                <Link href={route('events.index')} className="inline-flex items-center gap-2 text-sm font-semibold text-accent-300 transition hover:text-accent-200">
                                    View all events <ArrowRight size={16} />
                                </Link>
                            </div>
                        </div>
                    </div>

                    <div className="grid gap-5 lg:grid-cols-3">
                        {featuredEvents.length > 0 ? (
                            featuredEvents.map((event) => (
                                <article key={event.id} className="group flex flex-col overflow-hidden rounded-lg border border-white/10 bg-white/[0.04] transition hover:bg-white/[0.08] hover:-translate-y-1 hover:shadow-xl">
                                    <div className="relative h-48 overflow-hidden">
                                        <img
                                            src={event.program_cover ? `/storage/${event.program_cover}` : '/assets/img/leadership-workshop.png'}
                                            alt={event.title}
                                            className="h-full w-full object-cover transition duration-700 group-hover:scale-105"
                                            onError={(e) => { (e.target as HTMLImageElement).src = '/assets/img/leadership-workshop.png'; }}
                                        />
                                        <div className="absolute inset-0 bg-gradient-to-t from-primary/70 to-transparent" />
                                        <div className="absolute left-3 top-3 inline-flex items-center gap-1.5 rounded-md bg-accent/30 px-2.5 py-1 text-[10px] font-bold uppercase tracking-[0.16em] text-white backdrop-blur-sm">
                                            <CalendarDays size={12} /> {event.mode || 'Event'}
                                        </div>
                                    </div>

                                    <div className="flex flex-1 flex-col p-5">
                                        <h3 className="text-lg font-semibold leading-snug text-white">
                                            <Link href={route('events.show', event.slug)} className="transition hover:text-accent-300 line-clamp-2">
                                                {event.title}
                                            </Link>
                                        </h3>
                                        {event.theme && <p className="mt-2 text-sm leading-relaxed text-primary-200 line-clamp-2">{event.theme}</p>}

                                        <div className="mt-auto space-y-2 pt-4 text-sm text-primary-200">
                                            <div className="flex items-start gap-2">
                                                <CalendarDays size={14} className="mt-0.5 shrink-0 text-accent-400/70" />
                                                <span>{formatDate(event.start_date)} &mdash; {formatDate(event.end_date)}</span>
                                            </div>
                                            <div className="flex items-start gap-2">
                                                <MapPin size={14} className="mt-0.5 shrink-0 text-accent-400/70" />
                                                <span className="line-clamp-1">{event.physical_address || event.location || 'TBD'}</span>
                                            </div>
                                        </div>

                                        <div className="mt-4 flex items-center justify-between border-t border-white/10 pt-4">
                                            <span className="text-sm font-bold text-white">
                                                {event.entry_fee > 0 ? formatCurrency(event.entry_fee) : 'Free'}
                                            </span>
                                            <Link href={route('events.show', event.slug)} className="inline-flex items-center gap-1.5 text-sm font-semibold text-accent-300 transition hover:text-accent-200">
                                                Details <ArrowRight size={14} />
                                            </Link>
                                        </div>
                                    </div>
                                </article>
                            ))
                        ) : (
                            <div className="col-span-full rounded-lg border border-dashed border-white/20 bg-white/[0.02] px-6 py-16 text-center">
                                <CalendarDays size={32} className="mx-auto text-white/20" />
                                <p className="mt-4 text-lg font-semibold text-white">No featured events yet</p>
                                <p className="mt-2 text-sm text-primary-200">Upcoming gatherings will appear here as soon as they are published.</p>
                            </div>
                        )}
                    </div>
                </div>
            </section>

            {/* Replace this reserved proof area with an approved participant story and field photograph. */}
            <section className="border-b border-slate-200 bg-white py-14 md:py-20" data-content-placeholder="participant-impact-story">
                <div className="section-shell grid gap-8 lg:grid-cols-[1.1fr_0.9fr] lg:items-center">
                    {/* Generated stand-in: replace with the participant featured in the approved story. */}
                    <figure className="overflow-hidden rounded-md bg-slate-100">
                        <img src="/assets/img/participant-outcome.png" alt="A developing leader reflecting on her action plan" className="aspect-[4/3] w-full object-cover" />
                        <figcaption className="border-t border-slate-200 bg-white px-4 py-3 text-xs text-slate-500">
                            Participant photography placeholder pending an approved BLI outcome story.
                        </figcaption>
                    </figure>

                    <div className="flex flex-col justify-center border-y border-slate-200 py-8 lg:px-4">
                        <p className="text-xs font-bold uppercase text-accent-700">Evidence of formation</p>
                        <h2 className="mt-3 text-3xl font-bold text-primary">Let outcomes carry the story.</h2>
                        <p className="mt-4 max-w-xl text-sm leading-7 text-slate-600">
                            This area is reserved for real accounts of changed leadership practice, stronger teams, clearer decisions, and measurable community impact.
                        </p>
                        <div className="mt-7 grid grid-cols-2 gap-px overflow-hidden rounded-md border border-slate-200 bg-slate-200" data-content-placeholder="partner-logos">
                            <div className="bg-slate-50 px-4 py-5 text-center text-xs font-semibold uppercase text-slate-400">Partner mark</div>
                            <div className="bg-slate-50 px-4 py-5 text-center text-xs font-semibold uppercase text-slate-400">Affiliation mark</div>
                        </div>
                    </div>
                </div>
            </section>

            {/* CTA */}
            <section className="bg-white py-16 md:py-24">
                <div className="section-shell">
                    <div className="relative overflow-hidden rounded-lg border border-accent-100 bg-gradient-to-br from-accent-50/30 via-white to-primary-50/30 p-10 md:p-14">
                        <div className="absolute -right-8 -top-8 h-40 w-40 rounded-full bg-accent-100/30 blur-3xl" />
                        <div className="relative grid gap-8 lg:grid-cols-[1fr_auto] lg:items-center">
                            <div className="max-w-2xl">
                                <div className="inline-flex items-center gap-1.5 rounded-md bg-accent-50 px-2.5 py-1 text-[11px] font-semibold uppercase tracking-wider text-accent">
                                    <Sparkles size={12} /> Get Started
                                </div>
                                <h2 className="mt-4 text-3xl font-bold tracking-tight text-primary md:text-4xl">
                                    Start your formation journey.
                                </h2>
                                <p className="mt-3 text-sm leading-7 text-slate-500 md:text-base">
                                    Create an account and join leadership experiences designed for lasting growth.
                                </p>
                            </div>
                            <div className="flex flex-col gap-3 sm:flex-row lg:flex-col lg:min-w-[200px]">
                                <Link href={route('register')} className="inline-flex items-center justify-center gap-2 rounded-lg bg-accent px-6 py-3.5 text-sm font-semibold text-white transition hover:bg-accent-600 shadow-lg shadow-accent/20">
                                    Create account
                                </Link>
                                <Link href={route('events.index')} className="inline-flex items-center justify-center gap-2 rounded-lg border border-slate-200 bg-white px-6 py-3.5 text-sm font-semibold text-slate-700 transition hover:bg-slate-50">
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

function formatDate(dateString: string) {
    return new Date(dateString).toLocaleDateString('en-US', { month: 'short', day: 'numeric', year: 'numeric' });
}
function formatCurrency(amount: number) {
    return `₦${amount.toLocaleString('en-NG', { minimumFractionDigits: 2 })}`;
}
function formatCompact(value: number) {
    return new Intl.NumberFormat('en', { notation: 'compact', maximumFractionDigits: 1 }).format(value);
}
