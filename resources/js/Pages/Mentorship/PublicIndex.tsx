import { Head, Link } from '@inertiajs/react';
import { ArrowRight, CheckCircle2, Compass, Search, Star, Target, UsersRound } from 'lucide-react';
import { useMemo, useState } from 'react';
import GuestLayout from '@/Layouts/GuestLayout';

interface Mentor {
    id: number;
    user_id: number;
    name: string;
    headline?: string | null;
    photo?: string | null;
    bio?: string | null;
    area_of_expertise?: string | null;
    experience_years?: string | null;
    average_rating: number;
    ratings_count: number;
}

interface Props {
    mentors: Mentor[];
}

const journey = [
    { icon: Target, label: 'Define your goal', text: 'Describe the outcome or area where you need focused guidance.' },
    { icon: UsersRound, label: 'Choose a mentor', text: 'Review approved mentors and request the person whose experience fits.' },
    { icon: CheckCircle2, label: 'Work the plan', text: 'Use sessions, milestones, and shared resources to track meaningful progress.' },
];

const focusAreas = [
    'Leadership clarity',
    'Character and discipline',
    'Career direction',
    'Ministry development',
    'Team and organizational growth',
    'Personal execution',
];

export default function PublicMentorshipIndex({ mentors }: Props) {
    const [query, setQuery] = useState('');
    const normalizedQuery = query.trim().toLowerCase();
    const visibleMentors = useMemo(() => mentors.filter((mentor) => {
        if (!normalizedQuery) return true;

        return [mentor.name, mentor.headline, mentor.area_of_expertise, mentor.bio]
            .some((value) => value?.toLowerCase().includes(normalizedQuery));
    }), [mentors, normalizedQuery]);

    return (
        <GuestLayout>
            <Head title="Mentorship" />

            <section className="border-b border-slate-200 bg-white py-9 sm:py-12">
                <div className="section-shell grid items-center gap-8 lg:grid-cols-[minmax(0,0.85fr)_minmax(420px,1.15fr)]">
                    <div className="max-w-3xl">
                        <div className="inline-flex items-center gap-2 rounded-md bg-accent-50 px-2.5 py-1 text-[11px] font-semibold uppercase text-accent-700">
                            <Compass size={13} /> Guided development
                        </div>
                        <h1 className="mt-4 text-3xl font-bold leading-tight text-primary sm:text-4xl">Mentorship built around a clear goal</h1>
                        <p className="mt-4 max-w-2xl text-base leading-7 text-slate-600">
                            Work with an approved mentor through focused conversations, practical milestones, and accountable follow-through.
                        </p>
                        <div className="mt-7 border-l-2 border-accent pl-5">
                            <p className="text-sm font-semibold text-slate-900">Mentorship is not another course.</p>
                            <p className="mt-2 text-sm leading-6 text-slate-600">It is the space where learning becomes a personal plan, action, and reviewed progress.</p>
                        </div>
                    </div>
                    {/* Generated stand-in: replace with photography from an approved BLI mentorship session. */}
                    <figure className="overflow-hidden rounded-md bg-slate-100">
                        <img src="/assets/img/mentorship-session.png" alt="A mentor listening to an emerging leader during a focused session" className="aspect-[16/10] w-full object-cover" />
                        <figcaption className="border-t border-slate-200 px-4 py-3 text-xs text-slate-500">
                            Focused guidance built around a defined leadership goal.
                        </figcaption>
                    </figure>
                </div>
            </section>

            <section className="border-b border-slate-200 bg-slate-50 py-8">
                <div className="section-shell grid gap-px overflow-hidden rounded-md border border-slate-200 bg-slate-200 md:grid-cols-3">
                    {journey.map(({ icon: Icon, label, text }, index) => (
                        <div key={label} className="bg-white p-5 sm:p-6">
                            <div className="flex items-center justify-between">
                                <Icon size={19} className="text-accent" />
                                <span className="text-xs font-semibold text-slate-400">0{index + 1}</span>
                            </div>
                            <h2 className="mt-4 text-base font-semibold text-slate-950">{label}</h2>
                            <p className="mt-2 text-sm leading-6 text-slate-600">{text}</p>
                        </div>
                    ))}
                </div>
            </section>

            <section className="border-b border-slate-200 bg-primary py-10 text-white sm:py-12">
                <div className="section-shell grid gap-8 lg:grid-cols-[0.72fr_1.28fr] lg:items-center">
                    <div>
                        <p className="text-xs font-bold uppercase text-accent-300">Areas of focus</p>
                        <h2 className="mt-3 text-2xl font-bold">Bring a real goal, not a vague wish.</h2>
                        <p className="mt-3 text-sm leading-6 text-primary-200">A strong request gives the mentor a specific outcome to help you clarify, plan, and pursue.</p>
                    </div>
                    <div className="grid grid-cols-2 gap-px overflow-hidden rounded-md border border-white/15 bg-white/15 sm:grid-cols-3">
                        {focusAreas.map((area) => (
                            <div key={area} className="flex min-h-20 items-center bg-primary px-4 py-4 text-sm font-semibold text-white">
                                <CheckCircle2 size={15} className="mr-2 shrink-0 text-accent-300" /> {area}
                            </div>
                        ))}
                    </div>
                </div>
            </section>

            <section id="mentors" className="bg-white py-10 sm:py-14">
                <div className="section-shell">
                    <div className="flex flex-col gap-5 border-b border-slate-200 pb-6 sm:flex-row sm:items-end sm:justify-between">
                        <div>
                            <p className="text-xs font-semibold uppercase text-accent-700">Approved mentors</p>
                            <h2 className="mt-2 text-2xl font-bold text-primary">Find the right guide</h2>
                            <p className="mt-2 text-sm text-slate-600">Search by name, expertise, or the outcome you want to develop.</p>
                        </div>
                        <label className="relative block w-full sm:max-w-sm">
                            <span className="sr-only">Search mentors</span>
                            <Search size={16} className="absolute left-3.5 top-1/2 -translate-y-1/2 text-slate-400" />
                            <input
                                type="search"
                                value={query}
                                onChange={(event) => setQuery(event.target.value)}
                                placeholder="Search mentors or expertise"
                                className="h-11 w-full rounded-md border border-slate-300 bg-white pl-10 pr-3 text-sm outline-none focus:border-primary focus:ring-2 focus:ring-primary/10"
                            />
                        </label>
                    </div>

                    <p className="py-5 text-sm text-slate-500">{visibleMentors.length} mentor{visibleMentors.length === 1 ? '' : 's'} available</p>

                    {visibleMentors.length > 0 ? (
                        <div className="grid gap-5 md:grid-cols-2 xl:grid-cols-3">
                            {visibleMentors.map((mentor) => (
                                <article key={mentor.id} className="flex min-w-0 flex-col rounded-md border border-slate-200 bg-white p-5 transition hover:border-slate-300 hover:shadow-sm">
                                    <div className="flex min-w-0 items-start gap-4">
                                        {mentor.photo ? (
                                            <img src={`/storage/${mentor.photo}`} alt="" className="h-16 w-16 shrink-0 rounded-md object-cover" />
                                        ) : (
                                            <div className="flex h-16 w-16 shrink-0 items-center justify-center rounded-md bg-primary-50 text-lg font-bold text-primary">
                                                {initials(mentor.name)}
                                            </div>
                                        )}
                                        <div className="min-w-0 flex-1">
                                            <h3 className="wrap-break-word text-lg font-semibold text-slate-950">{mentor.name}</h3>
                                            <p className="mt-1 line-clamp-2 text-sm leading-5 text-slate-500">{mentor.headline || 'Approved BLI mentor'}</p>
                                            {mentor.ratings_count > 0 && (
                                                <p className="mt-2 flex items-center gap-1.5 text-xs font-semibold text-slate-600">
                                                    <Star size={13} className="fill-accent text-accent" /> {mentor.average_rating} <span className="font-normal text-slate-400">({mentor.ratings_count})</span>
                                                </p>
                                            )}
                                        </div>
                                    </div>

                                    <div className="mt-5 flex-1 border-t border-slate-100 pt-4">
                                        <p className="text-xs font-semibold uppercase text-slate-400">Focus</p>
                                        <p className="mt-2 wrap-break-word text-sm font-medium leading-6 text-primary">{mentor.area_of_expertise || 'Leadership and personal development'}</p>
                                        {mentor.bio && <p className="mt-3 line-clamp-3 text-sm leading-6 text-slate-600">{mentor.bio}</p>}
                                    </div>

                                    <div className="mt-5 flex items-center justify-between gap-3 border-t border-slate-100 pt-4">
                                        <span className="text-xs text-slate-500">{mentor.experience_years ? `${mentor.experience_years} experience` : 'Profile verified'}</span>
                                        <Link
                                            href={route('student.mentorship.create', { instructor: mentor.user_id })}
                                            className="inline-flex shrink-0 items-center gap-2 rounded-md bg-primary px-3.5 py-2 text-sm font-semibold text-white hover:bg-primary-600"
                                        >
                                            Request <ArrowRight size={15} />
                                        </Link>
                                    </div>
                                </article>
                            ))}
                        </div>
                    ) : (
                        <div className="border-y border-slate-200 py-12 text-center">
                            <UsersRound size={24} className="mx-auto text-slate-300" />
                            <h3 className="mt-3 font-semibold text-slate-900">{mentors.length ? 'No mentors match that search' : 'Mentor profiles are being prepared'}</h3>
                            <p className="mt-2 text-sm text-slate-500">{mentors.length ? 'Try a different name or area of expertise.' : 'Approved mentors will appear here as they become available.'}</p>
                        </div>
                    )}

                    <div className="mt-10 flex flex-col gap-4 border-t border-slate-200 pt-8 sm:flex-row sm:items-center sm:justify-between">
                        <div>
                            <p className="font-semibold text-slate-950">Want to guide developing leaders?</p>
                            <p className="mt-1 text-sm text-slate-600">Mentors currently enter through BLI’s approved instructor process.</p>
                        </div>
                        <Link href={route('instructors.become-an-instructor')} className="inline-flex items-center gap-2 text-sm font-semibold text-primary hover:text-accent-700">
                            Apply as an instructor <ArrowRight size={15} />
                        </Link>
                    </div>
                </div>
            </section>

            {/* Replace with an approved mentor/mentee outcome once consent and copy are available. */}
            <section className="border-t border-slate-200 bg-slate-50 py-12 sm:py-16" data-content-placeholder="mentorship-outcome-story">
                <div className="section-shell grid gap-8 lg:grid-cols-[1fr_0.9fr] lg:items-center">
                    <div>
                        <p className="text-xs font-bold uppercase text-accent-700">Outcome story reserved</p>
                        <h2 className="mt-3 max-w-xl text-3xl font-bold text-primary">Show what accountable guidance changes.</h2>
                        <p className="mt-4 max-w-2xl text-sm leading-7 text-slate-600">
                            This space is ready for a verified mentorship story: the original goal, milestones completed, lessons applied, and the practical result.
                        </p>
                    </div>
                    {/* This image keeps the outcome section human until a consented mentor/mentee story is available. */}
                    <figure className="overflow-hidden rounded-md bg-white shadow-sm">
                        <img src="/assets/img/participant-outcome.png" alt="A leader reviewing the goals in her development plan" className="aspect-[4/3] w-full object-cover" />
                        <figcaption className="border-t border-slate-200 px-4 py-3 text-xs leading-5 text-slate-500">
                            Replace with approved photography, quotation, names, and engagement period.
                        </figcaption>
                    </figure>
                </div>
            </section>
        </GuestLayout>
    );
}

function initials(name: string): string {
    return name.split(' ').filter(Boolean).slice(0, 2).map((part) => part[0]).join('').toUpperCase();
}
