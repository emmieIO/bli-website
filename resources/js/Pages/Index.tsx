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

            {/* Hero */}
            <section className="relative overflow-hidden bg-primary">
                <div className="absolute inset-0 bg-[radial-gradient(ellipse_at_15%_25%,rgba(185,28,28,0.18),transparent_55%),radial-gradient(ellipse_at_75%_10%,rgba(132,204,22,0.1),transparent_45%),radial-gradient(ellipse_at_50%_90%,rgba(0,33,71,0.4),transparent_60%)]" />
                <div className="absolute right-0 top-0 h-full w-1/2 bg-[radial-gradient(ellipse_at_70%_20%,rgba(185,28,28,0.12),transparent_60%)]" />

                <div className="section-shell relative py-16 lg:py-28">
                    <div className="grid items-center gap-12 lg:grid-cols-[1fr_1fr] lg:gap-20">
                        <div className="max-w-3xl">
                            <h1 className="text-4xl font-bold leading-[1.06] tracking-tight text-white md:text-5xl lg:text-[3.5rem]">
                                Forming leaders with
                                <br />
                                <span className="text-accent-400">spiritual depth</span>
                                {' '}and practical clarity.
                            </h1>

                            <p className="mt-5 max-w-xl text-base leading-7 text-primary-200 md:text-lg">
                                Beacon Leadership Institute provides live events, guided formation, and applied learning
                                experiences for emerging and established leaders across ministry, business, education, and
                                public service.
                            </p>

                            <div className="mt-8 flex flex-col gap-3 sm:flex-row">
                                <Link href={route('register')} className="inline-flex items-center justify-center gap-2 rounded-lg bg-accent px-6 py-3.5 text-sm font-semibold text-white transition hover:bg-accent-600 shadow-lg shadow-accent/25">
                                    Enroll now
                                    <ArrowRight size={16} />
                                </Link>
                                <Link href={route('events.index')} className="inline-flex items-center justify-center gap-2 rounded-lg border border-white/20 bg-white/5 px-6 py-3.5 text-sm font-semibold text-white transition hover:bg-white/10 hover:border-white/30">
                                    Browse events
                                    <CalendarDays size={16} />
                                </Link>
                            </div>

                            <div className="mt-14 grid grid-cols-3 gap-6 border-t border-white/10 pt-8">
                                {proofStats.map((stat) => (
                                    <div key={stat.label}>
                                        <stat.icon size={16} className="text-accent-400/60 mb-1.5" />
                                        <p className="text-[1.75rem] font-bold tracking-tight text-white">{stat.value}</p>
                                        <p className="mt-0.5 text-xs font-medium uppercase tracking-wider text-primary-200">{stat.label}</p>
                                    </div>
                                ))}
                            </div>
                        </div>

                        <div className="relative lg:-mr-8">
                            <div className="absolute -inset-4 rounded-lg bg-accent/10 blur-2xl" />
                            <div className="relative rounded-lg border-2 border-white/10 bg-primary-800/50 p-1.5">
                                <div className="overflow-hidden rounded-md">
                                    <img
                                        src="/assets/img/banner.png"
                                        alt="Beacon Leadership Institute"
                                        className="h-[280px] w-full object-cover md:h-[380px] lg:h-[460px]"
                                    />
                                </div>
                                <div className="absolute bottom-4 left-4 right-4 rounded-md bg-primary-900/90 border border-accent/30 px-4 py-3 backdrop-blur-sm">
                                    <div className="flex items-center gap-3">
                                        <div className="flex -space-x-2">
                                            <div className="h-7 w-7 rounded-full bg-accent flex items-center justify-center text-[10px] font-bold text-white ring-2 ring-primary-900">B</div>
                                            <div className="h-7 w-7 rounded-full bg-accent-500 flex items-center justify-center text-[10px] font-bold text-white ring-2 ring-primary-900">L</div>
                                            <div className="h-7 w-7 rounded-full bg-accent-600 flex items-center justify-center text-[10px] font-bold text-white ring-2 ring-primary-900">I</div>
                                        </div>
                                        <div>
                                            <p className="text-xs font-semibold text-white">Join 500+ leaders</p>
                                            <p className="text-[11px] text-primary-300">Already enrolled this year</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
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
                    <div className="flex flex-col gap-6 lg:flex-row lg:items-end lg:justify-between mb-10">
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
                        <Link href={route('events.index')} className="inline-flex items-center gap-2 text-sm font-semibold text-accent-300 transition hover:text-accent-200">
                            View all events <ArrowRight size={16} />
                        </Link>
                    </div>

                    <div className="grid gap-5 lg:grid-cols-3">
                        {featuredEvents.length > 0 ? (
                            featuredEvents.map((event) => (
                                <article key={event.id} className="group flex flex-col overflow-hidden rounded-lg border border-white/10 bg-white/[0.04] transition hover:bg-white/[0.08] hover:-translate-y-1 hover:shadow-xl">
                                    <div className="relative h-48 overflow-hidden">
                                        <img
                                            src={`/storage/${event.program_cover}`}
                                            alt={event.title}
                                            className="h-full w-full object-cover transition duration-700 group-hover:scale-105"
                                            onError={(e) => { (e.target as HTMLImageElement).src = 'https://placehold.co/800x500?text=Event'; }}
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
