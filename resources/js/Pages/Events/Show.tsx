import { Head, Link, router } from '@inertiajs/react';
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
    program_cover: string | null;
    start_date: string;
    end_date: string;
    mode?: 'online' | 'offline' | 'hybrid';
    physical_address?: string;
    location?: string;
    entry_fee: number;
    require_sign_up: boolean;
    slots_remaining?: number | null;
    is_allowing_application: boolean;
    speakers?: Speaker[];
    resources?: Resource[];
    is_registered?: boolean;
    registration_status?: 'registered' | 'cancelled' | null;
    revoke_count?: number;
    attendee_workspace_url?: string | null;
    program_profile?: {
        program_type: 'general_event' | 'discipleship_track';
        program_code?: string | null;
        registration_mode: 'open' | 'selective';
        requires_screening: boolean;
        screening_note?: string | null;
        cohort_duration_weeks?: number | null;
        group_model?: string | null;
        central_teaching_schedule?: string | null;
        group_meeting_schedule?: string | null;
        weekly_prayer_target_minutes?: number | null;
        weekly_evangelism_target_min?: number | null;
        weekly_evangelism_target_max?: number | null;
        weekly_discipleship_target_min?: number | null;
        weekly_discipleship_target_max?: number | null;
        meeting_link?: string | null;
        access_notes?: string | null;
    };
}

interface EventShowProps {
    event: Event;
    auth?: {
        user?: User;
    };
    primary_cta: {
        key: string;
        kind: 'action' | 'status';
        label: string;
        description: string;
        href?: string | null;
        method: 'get' | 'post';
        requires_auth: boolean;
        requires_confirmation: boolean;
        requires_email?: boolean;
    };
}

export default function EventShow({ event, auth, primary_cta }: EventShowProps) {
    const [showModal, setShowModal] = useState(false);
    const [isSubmitting, setIsSubmitting] = useState(false);
    const [countdown, setCountdown] = useState('');
    const [guestEmail, setGuestEmail] = useState('');
    const [guestName, setGuestName] = useState('');

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

        router.post(route('events.join', event.slug), primary_cta.requires_email && !auth?.user ? {
            email: guestEmail,
            name: guestName || undefined,
        } : {}, {
            preserveScroll: true,
            onSuccess: () => {
                setGuestEmail('');
                setGuestName('');
                setShowModal(false);
            },
            onFinish: () => {
                setIsSubmitting(false);
            },
        });
    };

    const slotsRemaining = event.slots_remaining;
    const isRegistered = event.is_registered ?? false;
    const revokeCount = event.revoke_count ?? 0;
    const programProfile = event.program_profile;
    const modeLabel = event.mode ? event.mode.charAt(0).toUpperCase() + event.mode.slice(1) : 'Hybrid';
    const isFreeRegistrationFlow = primary_cta.key === 'register_now';
    const isPaidCheckoutFlow = primary_cta.key === 'buy_ticket';
    const requiresGuestEmail = Boolean(primary_cta.requires_email && !auth?.user);
    const showPrimaryCta = primary_cta.kind === 'action' && !(revokeCount === 4 && primary_cta.key === 'register_now');
    const ctaTone = primary_cta.key === 'view_attendee_workspace'
        ? 'border-emerald-200 bg-emerald-50'
        : primary_cta.key === 'view_speaker_workspace'
            ? 'border-slate-200 bg-slate-100'
            : 'border-slate-200 bg-slate-50';
    const programTypeLabel = programProfile?.program_type === 'discipleship_track' ? 'Discipleship Track' : 'General Event';
    const prayerTargetLabel = programProfile?.weekly_prayer_target_minutes
        ? `${Math.floor(programProfile.weekly_prayer_target_minutes / 60)}h ${programProfile.weekly_prayer_target_minutes % 60}m weekly prayer target`
        : null;
    const evangelismTargetLabel = programProfile?.weekly_evangelism_target_min
        ? `${programProfile.weekly_evangelism_target_min}-${programProfile.weekly_evangelism_target_max ?? programProfile.weekly_evangelism_target_min} people reached weekly`
        : null;
    const discipleshipTargetLabel = programProfile?.weekly_discipleship_target_min
        ? `Disciple ${programProfile.weekly_discipleship_target_min}-${programProfile.weekly_discipleship_target_max ?? programProfile.weekly_discipleship_target_min} people`
        : null;

    const handlePrimaryCta = () => {
        if (revokeCount === 4 && primary_cta.key === 'register_now') {
            return;
        }

        if (primary_cta.requires_auth && !auth?.user) {
            router.get(route('login'));
            return;
        }

        if (primary_cta.requires_confirmation) {
            setShowModal(true);
            return;
        }

        if (primary_cta.href) {
            router.visit(primary_cta.href);
        }
    };

    return (
        <GuestLayout>
            <Head title={event.title} />

            <section className="border-b border-slate-200 bg-white py-3 sm:py-5">
                <div className="section-shell">
                    <nav>
                        <ul className="flex min-w-0 items-center gap-2 text-sm sm:gap-3">
                            <li className="inline">
                                <Link href={route('events.index')} className="inline-flex items-center gap-2 font-medium text-slate-500 transition-colors hover:text-slate-950">
                                    <i className="fas fa-arrow-left text-xs"></i> Events
                                </Link>
                            </li>
                            <li className="inline text-xs text-slate-400"><i className="fas fa-chevron-right"></i></li>
                            <li className="min-w-0 truncate font-medium text-slate-900">{event.title}</li>
                        </ul>
                    </nav>
                </div>
            </section>

            <section className="public-section event-show-section bg-slate-50">
                <div className="section-shell">
                    <div className="grid gap-6 lg:grid-cols-[minmax(0,1.65fr)_minmax(320px,0.95fr)] lg:gap-8">
                        <div className="space-y-6 lg:space-y-8">
                            <section className="relative isolate overflow-hidden rounded-md border border-slate-200 bg-slate-900 text-white shadow-sm sm:rounded-lg">
                                <div className="absolute inset-0">
                                    <img
                                        src={event.program_cover ? `/storage/${event.program_cover}` : '/assets/img/leadership-workshop.png'}
                                        alt={event.title}
                                        className="h-full w-full object-cover"
                                        onError={(e) => {
                                            (e.target as HTMLImageElement).src = '/assets/img/leadership-workshop.png';
                                        }}
                                    />
                                    <div className="absolute inset-0 bg-slate-900/68"></div>
                                </div>

                                <div className="relative flex min-h-130 items-end sm:min-h-125 md:min-h-130">
                                    <div className="w-full px-4 pb-5 pt-10 sm:px-6 sm:pb-8 lg:px-10 lg:pb-10 lg:pt-16">
                                        <div className="max-w-4xl space-y-4 sm:space-y-6">
                                            <div className="flex flex-wrap items-center gap-3">
                                                <span
                                                    className="inline-flex items-center rounded-md bg-white/10 px-3 py-1.5 text-[11px] font-semibold uppercase tracking-[0.18em] text-white/92"
                                                    style={{ textShadow: '0 1px 2px rgba(0, 0, 0, 0.35)' }}
                                                >
                                                    {getModeIcon(event.mode)}
                                                    {modeLabel}
                                                </span>
                                                {isUpcoming && (
                                                    <span
                                                        className="inline-flex items-center rounded-md bg-emerald-600 px-3 py-1.5 text-[11px] font-semibold uppercase tracking-[0.18em] text-white"
                                                        style={{ textShadow: '0 1px 2px rgba(0, 0, 0, 0.28)' }}
                                                    >
                                                        <i className="fas fa-clock mr-2 text-[10px]"></i>
                                                        Upcoming
                                                    </span>
                                                )}
                                                {isLive && (
                                                    <span className="inline-flex items-center rounded-md bg-white px-3 py-1.5 text-[11px] font-semibold uppercase tracking-[0.18em] text-slate-900">
                                                        <span className="mr-2 h-2 w-2 rounded-full bg-emerald-600 animate-pulse"></span>
                                                        Live
                                                    </span>
                                                )}
                                                {isPast && (
                                                    <span
                                                        className="inline-flex items-center rounded-md bg-white/10 px-3 py-1.5 text-[11px] font-semibold uppercase tracking-[0.18em] text-white/92"
                                                        style={{ textShadow: '0 1px 2px rgba(0, 0, 0, 0.35)' }}
                                                    >
                                                        Ended
                                                    </span>
                                                )}
                                            </div>

                                            <div className="space-y-4">
                                                <h1
                                                    className="max-w-4xl wrap-break-word text-3xl font-semibold leading-tight text-white! sm:text-4xl md:text-5xl lg:text-6xl"
                                                    style={{ textShadow: '0 4px 18px rgba(0, 0, 0, 0.35)' }}
                                                >
                                                    {event.title}
                                                </h1>
                                                {event.theme && (
                                                    <p
                                                        className="max-w-3xl text-base leading-6 text-white/78 sm:text-lg sm:leading-8 md:text-xl"
                                                        style={{ textShadow: '0 2px 10px rgba(0, 0, 0, 0.28)' }}
                                                    >
                                                        {event.theme}
                                                    </p>
                                                )}
                                            </div>

                                            <div
                                                className="hidden max-w-3xl text-base leading-8 text-white/88 sm:block md:text-lg"
                                                style={{ textShadow: '0 2px 10px rgba(0, 0, 0, 0.28)' }}
                                            >
                                                <p>{primary_cta.description}</p>
                                            </div>

                                            <div className="grid max-w-4xl grid-cols-2 gap-x-4 gap-y-4 border-t border-white/14 pt-4 text-sm text-white/78 sm:gap-x-8 sm:gap-y-6 sm:pt-6 xl:grid-cols-4">
                                                <div>
                                                    <p className="text-[11px] font-semibold uppercase tracking-[0.18em] text-white/54">Starts</p>
                                                    <p
                                                        className="mt-1 text-sm font-semibold leading-5 text-white/96 sm:mt-2 sm:text-base"
                                                        style={{ textShadow: '0 2px 8px rgba(0, 0, 0, 0.22)' }}
                                                    >
                                                        {formatDate(event.start_date)}
                                                    </p>
                                                    <p className="mt-1 text-xs text-white/72 sm:text-sm">{formatTime(event.start_date)}</p>
                                                </div>
                                                <div>
                                                    <p className="text-[11px] font-semibold uppercase tracking-[0.18em] text-white/54">Ends</p>
                                                    <p
                                                        className="mt-1 text-sm font-semibold leading-5 text-white/96 sm:mt-2 sm:text-base"
                                                        style={{ textShadow: '0 2px 8px rgba(0, 0, 0, 0.22)' }}
                                                    >
                                                        {formatDate(event.end_date)}
                                                    </p>
                                                    <p className="mt-1 text-xs text-white/72 sm:text-sm">{formatTime(event.end_date)}</p>
                                                </div>
                                                <div className="hidden sm:block">
                                                    <p className="text-[11px] font-semibold uppercase tracking-[0.18em] text-white/54">Entry</p>
                                                    <p
                                                        className="mt-2 text-base font-semibold text-white/96"
                                                        style={{ textShadow: '0 2px 8px rgba(0, 0, 0, 0.22)' }}
                                                    >
                                                        {event.entry_fee > 0 ? `₦${event.entry_fee.toLocaleString()}` : 'Free'}
                                                    </p>
                                                </div>
                                                <div className="hidden sm:block">
                                                    <p className="text-[11px] font-semibold uppercase tracking-[0.18em] text-white/54">Seats</p>
                                                    <p
                                                        className="mt-2 text-base font-semibold text-white/96"
                                                        style={{ textShadow: '0 2px 8px rgba(0, 0, 0, 0.22)' }}
                                                    >
                                                        {slotsRemaining === null ? 'Unlimited' : slotsRemaining === 0 ? 'Full' : `${slotsRemaining} left`}
                                                    </p>
                                                </div>
                                                <div>
                                                    <p className="text-[11px] font-semibold uppercase tracking-[0.18em] text-white/54">Next action</p>
                                                    <p
                                                        className="mt-2 text-base font-semibold text-white/96"
                                                        style={{ textShadow: '0 2px 8px rgba(0, 0, 0, 0.22)' }}
                                                    >
                                                        {primary_cta.label}
                                                    </p>
                                                </div>
                                            </div>

                                            {isUpcoming && countdown && (
                                                <div className="border-l-2 border-accent pl-3 sm:pl-4">
                                                    <p className="text-[11px] font-semibold uppercase tracking-[0.18em] text-emerald-300">Countdown</p>
                                                    <p
                                                        className="mt-1 text-base font-semibold text-white/96 sm:mt-2 sm:text-lg"
                                                        style={{ textShadow: '0 2px 8px rgba(0, 0, 0, 0.22)' }}
                                                    >
                                                        {countdown}
                                                    </p>
                                                    <p className="mt-1 text-sm text-white/68">Until the event begins</p>
                                                </div>
                                            )}
                                        </div>
                                    </div>
                                </div>
                            </section>

                            <div className="rounded-md border border-slate-200 bg-white p-4 shadow-sm sm:p-5 lg:p-7">
                                <div className="mb-5 flex items-center gap-3">
                                    <div className="flex h-10 w-10 items-center justify-center rounded-md bg-slate-900 text-white">
                                        <i className="fas fa-align-left text-sm"></i>
                                    </div>
                                    <h2 className="text-xl font-semibold text-slate-950 md:text-2xl">Overview</h2>
                                </div>
                                <div
                                    className="rich-content"
                                    dangerouslySetInnerHTML={{ __html: event.description }}
                                />
                            </div>

                            {programProfile && (
                                <div className="rounded-md border border-slate-200 bg-white p-4 shadow-sm sm:p-5 lg:p-7">
                                    <div className="mb-5 flex items-center gap-3">
                                        <div className="flex h-10 w-10 items-center justify-center rounded-md bg-emerald-50 text-emerald-700">
                                            <i className="fas fa-route text-sm"></i>
                                        </div>
                                        <div>
                                            <h2 className="text-xl font-semibold text-slate-950 md:text-2xl">Program Structure</h2>
                                            <p className="text-sm text-slate-600">How this intake is organized and what participants are expected to carry.</p>
                                        </div>
                                    </div>

                                    <div className="grid gap-4 md:grid-cols-2">
                                        <ProgramFact label="Program type" value={programTypeLabel} />
                                        <ProgramFact label="Admission" value={programProfile.registration_mode === 'selective' || programProfile.requires_screening ? 'Selective screening' : 'Open registration'} />
                                        {programProfile.program_code && <ProgramFact label="Program code" value={programProfile.program_code} />}
                                        {programProfile.cohort_duration_weeks && <ProgramFact label="Cycle length" value={`${programProfile.cohort_duration_weeks} weeks`} />}
                                        {programProfile.central_teaching_schedule && <ProgramFact label="Central teaching" value={programProfile.central_teaching_schedule} />}
                                        {programProfile.group_meeting_schedule && <ProgramFact label="Group meetings" value={programProfile.group_meeting_schedule} />}
                                        {programProfile.group_model && <ProgramFact label="Group model" value={programProfile.group_model} />}
                                        {programProfile.meeting_link && <ProgramFact label="Meeting link" value="Shared after successful registration" />}
                                    </div>

                                    {(programProfile.screening_note || prayerTargetLabel || evangelismTargetLabel || discipleshipTargetLabel || programProfile.access_notes) && (
                                        <div className="mt-6 space-y-3 border-t border-slate-100 pt-5">
                                            {programProfile.screening_note && (
                                                <div className="rounded-xl border border-amber-200 bg-amber-50 p-4 text-sm leading-6 text-amber-900">
                                                    <span className="font-semibold">Screening note:</span> {programProfile.screening_note}
                                                </div>
                                            )}
                                            {(prayerTargetLabel || evangelismTargetLabel || discipleshipTargetLabel) && (
                                                <div className="rounded-xl border border-slate-200 bg-slate-50 p-4">
                                                    <p className="text-sm font-semibold text-slate-950">Weekly commitments</p>
                                                    <ul className="mt-3 space-y-2 text-sm leading-6 text-slate-700">
                                                        {prayerTargetLabel && <li>{prayerTargetLabel}</li>}
                                                        {evangelismTargetLabel && <li>{evangelismTargetLabel}</li>}
                                                        {discipleshipTargetLabel && <li>{discipleshipTargetLabel}</li>}
                                                    </ul>
                                                </div>
                                            )}
                                            {programProfile.access_notes && (
                                                <p className="text-sm leading-6 text-slate-600">{programProfile.access_notes}</p>
                                            )}
                                        </div>
                                    )}
                                </div>
                            )}

                            {event.speakers && event.speakers.length > 0 && (
                                <div className="rounded-md border border-slate-200 bg-white p-4 shadow-sm sm:p-5 lg:p-7">
                                    <div className="mb-5 flex items-center gap-3">
                                        <div className="flex h-10 w-10 items-center justify-center rounded-md bg-slate-900 text-white">
                                            <i className="fas fa-microphone text-sm"></i>
                                        </div>
                                        <h2 className="text-xl font-semibold text-slate-950 md:text-2xl">Speakers</h2>
                                    </div>

                                    <div className="grid gap-4 md:grid-cols-2">
                                        {event.speakers.map((speaker) => (
                                            <Link
                                                key={speaker.id}
                                                href={route('speakers.profile', speaker.id)}
                                                className="block rounded-md border border-slate-200 bg-slate-50 p-4 transition hover:border-slate-300 hover:bg-white hover:shadow-sm sm:p-5"
                                            >
                                                <div className="flex items-start gap-4">
                                                    <img
                                                        src={
                                                            speaker.user.photo
                                                                ? `/storage/${speaker.user.photo}`
                                                                : `https://ui-avatars.com/api/?name=${encodeURIComponent(speaker.user.name)}&background=002147&color=fff&size=128&font-size=0.35&bold=true`
                                                        }
                                                        alt={speaker.user.name}
                                                        className="h-14 w-14 shrink-0 rounded-md object-cover sm:h-16 sm:w-16"
                                                    />
                                                    <div className="min-w-0 flex-1">
                                                        <p className="text-base font-semibold text-slate-950 md:text-lg">{speaker.user.name}</p>
                                                        {speaker.user.headline && (
                                                            <p className="mt-1 text-sm font-medium text-emerald-700">{speaker.user.headline}</p>
                                                        )}
                                                        <p className="mt-3 line-clamp-3 text-sm leading-6 text-slate-600">
                                                            {speaker.bio || 'Professional speaker and industry expert.'}
                                                        </p>
                                                    </div>
                                                </div>
                                            </Link>
                                        ))}
                                    </div>
                                </div>
                            )}

                            {auth?.user && isRegistered && event.resources && event.resources.filter((r) => r.is_downloadable).length > 0 && (
                                <div className="rounded-md border border-slate-200 bg-white p-4 shadow-sm sm:p-5 lg:p-7">
                                    <div className="mb-5 flex items-center gap-3">
                                        <div className="flex h-10 w-10 items-center justify-center rounded-md bg-emerald-50 text-emerald-700">
                                            <i className="fas fa-download text-sm"></i>
                                        </div>
                                        <div>
                                            <h2 className="text-xl font-semibold text-slate-950 md:text-2xl">Attendee Resources</h2>
                                            <p className="text-sm text-slate-600">Downloads and links available after registration.</p>
                                        </div>
                                    </div>

                                    <div className="space-y-3">
                                        {event.resources.filter((r) => r.is_downloadable).map((resource) => (
                                            <div key={resource.id}>
                                                {resource.type === 'file' && resource.file_path && (
                                                    <a
                                                        href={`/storage/${resource.file_path}`}
                                                        download
                                                        target="_blank"
                                                        rel="noopener noreferrer"
                                                        className="flex items-center gap-4 rounded-xl border border-slate-200 bg-slate-50 p-4 transition hover:border-slate-300 hover:bg-white"
                                                    >
                                                        <div className="flex h-11 w-11 shrink-0 items-center justify-center rounded-md bg-slate-100 text-slate-900">
                                                            <i className="fas fa-file-pdf"></i>
                                                        </div>
                                                        <div className="min-w-0 flex-1">
                                                            <p className="truncate font-semibold text-slate-950">{resource.title}</p>
                                                            {resource.description && <p className="mt-1 line-clamp-1 text-sm text-slate-600">{resource.description}</p>}
                                                        </div>
                                                        <span className="text-sm font-medium text-emerald-700">Download</span>
                                                    </a>
                                                )}
                                                {resource.type === 'link' && resource.external_link && (
                                                    <a
                                                        href={resource.external_link}
                                                        target="_blank"
                                                        rel="noopener noreferrer"
                                                        className="flex items-center gap-4 rounded-xl border border-slate-200 bg-slate-50 p-4 transition hover:border-slate-300 hover:bg-white"
                                                    >
                                                        <div className="flex h-11 w-11 shrink-0 items-center justify-center rounded-md bg-slate-100 text-slate-900">
                                                            <i className="fas fa-link"></i>
                                                        </div>
                                                        <div className="min-w-0 flex-1">
                                                            <p className="truncate font-semibold text-slate-950">{resource.title}</p>
                                                            {resource.description && <p className="mt-1 line-clamp-1 text-sm text-slate-600">{resource.description}</p>}
                                                        </div>
                                                        <span className="text-sm font-medium text-emerald-700">Open</span>
                                                    </a>
                                                )}
                                            </div>
                                        ))}
                                    </div>
                                </div>
                            )}
                        </div>

                        <aside className="space-y-6 lg:sticky lg:top-24 lg:self-start">
                            <div className="rounded-md border border-slate-200 bg-white p-4 shadow-sm sm:p-5 lg:p-6">
                                <div className="border-b border-slate-200 pb-5">
                                    <p className="text-[11px] font-semibold uppercase text-slate-500">Next action</p>
                                    <h3 className="mt-2 text-xl font-semibold text-slate-950 md:text-2xl">{primary_cta.label}</h3>
                                    <p className="mt-2 text-sm leading-6 text-slate-600">{primary_cta.description}</p>
                                </div>

                                <div className="space-y-4 py-5">
                                    <div className={`rounded-xl border p-4 ${ctaTone}`}>
                                        <div className="flex items-start justify-between gap-4">
                                            <div>
                                                <p className="text-[11px] font-semibold uppercase tracking-wide text-slate-500">Path</p>
                                                <p className="mt-2 text-lg font-semibold text-slate-950">{primary_cta.label}</p>
                                                <p className="mt-2 text-sm leading-6 text-slate-600">{primary_cta.description}</p>
                                            </div>
                                            <div className="flex h-11 w-11 shrink-0 items-center justify-center rounded-lg bg-white text-slate-900 shadow-sm">
                                                {primary_cta.key === 'view_attendee_workspace' && <i className="fas fa-calendar-check"></i>}
                                                {primary_cta.key === 'view_speaker_workspace' && <i className="fas fa-microphone"></i>}
                                                {isPaidCheckoutFlow && <i className="fas fa-ticket-alt"></i>}
                                                {primary_cta.key === 'apply_to_speak' && <i className="fas fa-bullhorn"></i>}
                                                {(isFreeRegistrationFlow || primary_cta.kind === 'status') && <i className="fas fa-arrow-right"></i>}
                                            </div>
                                        </div>
                                    </div>

                                    <div className="flex items-start justify-between gap-4 border-b border-slate-100 pb-4">
                                        <div>
                                            <p className="text-sm font-medium text-slate-500">Entry Fee</p>
                                            <p className="mt-1 text-sm text-slate-600">{event.entry_fee > 0 ? 'Per participant' : 'No payment required'}</p>
                                        </div>
                                        <p className="text-xl font-semibold text-slate-950 md:text-2xl">
                                            {event.entry_fee > 0 ? `₦${event.entry_fee.toLocaleString()}` : 'Free'}
                                        </p>
                                    </div>

                                    <div className="flex items-start justify-between gap-4 border-b border-slate-100 pb-4">
                                        <div>
                                            <p className="text-sm font-medium text-slate-500">Availability</p>
                                            <p className="mt-1 text-sm text-slate-600">
                                                {slotsRemaining === null ? 'Open registration' : 'Remaining seats'}
                                            </p>
                                        </div>
                                        <p className="text-xl font-semibold text-slate-950 md:text-2xl">
                                            {slotsRemaining === null ? 'Unlimited' : slotsRemaining}
                                        </p>
                                    </div>

                                    <div className="flex items-start justify-between gap-4">
                                        <div>
                                            <p className="text-sm font-medium text-slate-500">Format</p>
                                            <p className="mt-1 text-sm text-slate-600">Delivery mode</p>
                                        </div>
                                        <p className="text-base font-semibold text-slate-950">{modeLabel}</p>
                                    </div>

                                    {typeof slotsRemaining === 'number' && slotsRemaining <= 10 && slotsRemaining > 0 && (
                                        <div className="rounded-xl border border-emerald-200 bg-emerald-50 px-4 py-3 text-sm font-medium text-emerald-700">
                                            Limited seats remaining.
                                        </div>
                                    )}

                                    {slotsRemaining === 0 && (
                                        <div className="rounded-xl border border-amber-200 bg-amber-50 px-4 py-3 text-sm font-medium text-amber-700">
                                            This event is full. Registration will reopen if a seat becomes available.
                                        </div>
                                    )}

                                    {isLive && (
                                        <div className="rounded-xl border border-emerald-200 bg-emerald-50 px-4 py-3 text-sm font-medium text-slate-900">
                                            This event is live right now.
                                        </div>
                                    )}

                                    {primary_cta.kind === 'status' || !showPrimaryCta ? (
                                        <div className="flex w-full items-center justify-center rounded-xl bg-slate-700 px-5 py-4 text-center text-base font-semibold text-white">
                                            {revokeCount === 4 && isFreeRegistrationFlow
                                                ? 'Registration limit reached'
                                                : primary_cta.label}
                                        </div>
                                    ) : (
                                        <button
                                            onClick={handlePrimaryCta}
                                            disabled={revokeCount === 4 && isFreeRegistrationFlow}
                                            className="inline-flex w-full items-center justify-center gap-2 rounded-xl bg-slate-950 px-5 py-4 text-base font-semibold text-white transition hover:bg-slate-800 disabled:cursor-not-allowed disabled:opacity-50"
                                        >
                                            {primary_cta.key === 'view_attendee_workspace' && <i className="fas fa-calendar-check"></i>}
                                            {primary_cta.key === 'view_speaker_workspace' && <i className="fas fa-microphone"></i>}
                                            {isPaidCheckoutFlow && <i className="fas fa-ticket-alt"></i>}
                                            {primary_cta.key === 'apply_to_speak' && <i className="fas fa-microphone"></i>}
                                            {(isFreeRegistrationFlow || primary_cta.key === 'live') && <i className="fas fa-check"></i>}
                                            <span>{primary_cta.label}</span>
                                        </button>
                                    )}
                                </div>

                                {!auth?.user && (
                                    <div className="rounded-xl border border-slate-200 bg-slate-50 p-4 text-sm leading-6 text-slate-600">
                                        Sign in to register and access attendee materials after confirmation.
                                    </div>
                                )}

                            </div>

                            <div className="rounded-md border border-slate-200 bg-white p-4 shadow-sm sm:p-5 lg:p-6">
                                <p className="text-[11px] font-semibold uppercase text-slate-500">Event Logistics</p>
                                <div className="mt-5 space-y-5">
                                    <div className="flex items-start gap-3">
                                        <div className="flex h-9 w-9 shrink-0 items-center justify-center rounded-md bg-slate-100 text-slate-900">
                                            <i className="fas fa-calendar-day text-sm"></i>
                                        </div>
                                        <div>
                                            <p className="text-sm font-semibold text-slate-950">Schedule</p>
                                            <p className="mt-1 text-sm text-slate-600">{formatDate(event.start_date)}</p>
                                            <p className="text-sm text-slate-600">
                                                {formatTime(event.start_date)} to {formatTime(event.end_date)}
                                            </p>
                                        </div>
                                    </div>

                                    {(event.mode === 'offline' || event.mode === 'hybrid') && event.physical_address && (
                                        <div className="flex items-start gap-3">
                                            <div className="flex h-9 w-9 shrink-0 items-center justify-center rounded-md bg-slate-100 text-slate-900">
                                                <i className="fas fa-map-marker-alt text-sm"></i>
                                            </div>
                                            <div>
                                                <p className="text-sm font-semibold text-slate-950">Venue</p>
                                                <p className="mt-1 text-sm leading-6 text-slate-600">{event.physical_address}</p>
                                            </div>
                                        </div>
                                    )}

                                    {(event.mode === 'online' || event.mode === 'hybrid') && (
                                        <div className="flex items-start gap-3">
                                            <div className="flex h-9 w-9 shrink-0 items-center justify-center rounded-md bg-slate-100 text-slate-900">
                                                <i className="fas fa-video text-sm"></i>
                                            </div>
                                            <div>
                                                <p className="text-sm font-semibold text-slate-950">Online access</p>
                                                {auth?.user && isRegistered && event.location ? (
                                                    isPast ? (
                                                        <p className="mt-1 text-sm text-slate-600">Meeting link has expired.</p>
                                                    ) : startDate <= now ? (
                                                        <a
                                                            href={event.location}
                                                            className="mt-1 inline-flex items-center text-sm font-medium text-emerald-700 hover:underline"
                                                            target="_blank"
                                                            rel="noopener noreferrer"
                                                        >
                                                            Join meeting
                                                        </a>
                                                    ) : (
                                                        <p className="mt-1 text-sm text-slate-600">
                                                            The meeting link will appear here at the event start time.
                                                        </p>
                                                    )
                                                ) : (
                                                    <p className="mt-1 text-sm text-slate-600">
                                                        Available to confirmed attendees.
                                                    </p>
                                                )}
                                            </div>
                                        </div>
                                    )}

                                    <div className="flex items-start gap-3">
                                        <div className="flex h-9 w-9 shrink-0 items-center justify-center rounded-md bg-slate-100 text-slate-900">
                                            <i className="fas fa-link text-sm"></i>
                                        </div>
                                        <div className="space-y-2">
                                            <Link href={route('events.index')} className="block text-sm font-medium text-slate-900 hover:text-emerald-700">
                                                View all events
                                            </Link>
                                            <Link href={route('homepage')} className="block text-sm font-medium text-slate-900 hover:text-emerald-700">
                                                Return to homepage
                                            </Link>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </aside>
                    </div>
                </div>
            </section>

            <div className="fixed inset-x-0 bottom-0 z-40 border-t border-slate-200 bg-white/95 px-4 py-3 shadow-[0_-8px_24px_rgba(15,23,42,0.10)] backdrop-blur lg:hidden">
                {primary_cta.kind === 'status' || !showPrimaryCta ? (
                    <div className="flex min-h-12 w-full items-center justify-center rounded-md bg-slate-700 px-4 text-center text-sm font-semibold text-white">
                        {revokeCount === 4 && isFreeRegistrationFlow ? 'Registration limit reached' : primary_cta.label}
                    </div>
                ) : (
                    <button
                        type="button"
                        onClick={handlePrimaryCta}
                        className="inline-flex min-h-12 w-full items-center justify-center gap-2 rounded-md bg-primary px-4 text-sm font-semibold text-white"
                    >
                        {isPaidCheckoutFlow && <i className="fas fa-ticket-alt"></i>}
                        {isFreeRegistrationFlow && <i className="fas fa-check"></i>}
                        <span>{primary_cta.label}</span>
                    </button>
                )}
            </div>

            {/* Registration Modal */}
            {showModal && (
                <div className="fixed inset-0 z-50 flex items-end justify-center bg-black/60 backdrop-blur-md sm:items-center">
                    <div className="relative max-h-[92vh] w-full max-w-lg p-2 sm:p-4">
                        <div className="relative max-h-[90vh] overflow-y-auto rounded-md border border-slate-200 bg-white shadow-2xl sm:rounded-lg">
                            <div className="border-b border-slate-200 bg-slate-950 p-5 text-center sm:p-6">
                                {/* Close Button */}
                                <button
                                    type="button"
                                    onClick={() => setShowModal(false)}
                                    className="absolute right-4 top-4 flex h-8 w-8 items-center justify-center rounded-md text-sm text-white/80 transition-all duration-300 hover:bg-white/20 hover:text-white"
                                >
                                    <i className="fas fa-times w-4 h-4"></i>
                                </button>

                                {/* Modal Icon */}
                                <div className="mx-auto mb-3 flex h-14 w-14 items-center justify-center rounded-md bg-white/10 sm:mb-4 sm:h-20 sm:w-20">
                                    <i className="fas fa-calendar-check text-2xl text-white sm:text-3xl"></i>
                                </div>

                                {/* Modal Title */}
                                <h3 className="mb-2 text-xl font-bold text-white">{requiresGuestEmail ? 'Register With Email' : 'Confirm Free Registration'}</h3>
                                <p className="text-sm text-white/90">
                                    {requiresGuestEmail ? 'No account needed. We will use your email for reminders.' : 'Reserve your seat and move directly into the attendee journey'}
                                </p>
                            </div>

                            {/* Modal Content */}
                            <div className="p-5 text-center sm:p-8">
                                <div className="mb-6">
                                    <h4 className="mb-2 text-lg font-semibold text-slate-950">
                                        You're about to confirm your registration for:
                                    </h4>
                                    <div className="mb-4 rounded-xl border border-emerald-200 bg-emerald-50 p-4">
                                        <span className="block text-lg font-semibold text-slate-950">{event.title}</span>
                                        <span className="text-sm text-emerald-700">{event.theme}</span>
                                    </div>
                                    <p className="text-slate-600">
                                        {requiresGuestEmail
                                            ? 'This is a free event. Add your email and we will include you in event reminders.'
                                            : 'This is a free event. Once confirmed, your attendee workspace becomes the home for access instructions, resources, and updates.'}
                                    </p>
                                </div>

                                <form onSubmit={handleRegistration}>
                                    {requiresGuestEmail && (
                                        <div className="mb-6 space-y-4 text-left">
                                            <div>
                                                <label htmlFor="guest_name" className="mb-1 block text-sm font-medium text-slate-700">
                                                    Name <span className="text-slate-400">(optional)</span>
                                                </label>
                                                <input
                                                    id="guest_name"
                                                    type="text"
                                                    value={guestName}
                                                    onChange={(e) => setGuestName(e.target.value)}
                                                    className="h-11 w-full rounded-lg border border-slate-300 px-3 text-sm text-slate-900 outline-none transition focus:border-primary-300 focus:ring-2 focus:ring-primary-500/10"
                                                    placeholder="Your name"
                                                />
                                            </div>
                                            <div>
                                                <label htmlFor="guest_email" className="mb-1 block text-sm font-medium text-slate-700">
                                                    Email address <span className="text-red-500">*</span>
                                                </label>
                                                <input
                                                    id="guest_email"
                                                    type="email"
                                                    required
                                                    value={guestEmail}
                                                    onChange={(e) => setGuestEmail(e.target.value)}
                                                    className="h-11 w-full rounded-lg border border-slate-300 px-3 text-sm text-slate-900 outline-none transition focus:border-primary-300 focus:ring-2 focus:ring-primary-500/10"
                                                    placeholder="you@example.com"
                                                />
                                                <p className="mt-1 text-xs text-slate-500">We will use this only for this event registration and reminders.</p>
                                            </div>
                                        </div>
                                    )}
                                    <div className="flex flex-col justify-center gap-4 sm:flex-row">
                                        <button
                                            type="submit"
                                            disabled={isSubmitting}
                                            className="inline-flex min-w-40 items-center justify-center gap-2 rounded-xl bg-slate-950 px-5 py-3 text-sm font-semibold text-white transition hover:bg-slate-800 disabled:cursor-not-allowed disabled:opacity-60"
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
                                                    {requiresGuestEmail ? 'Adding email...' : 'Confirming registration...'}
                                                </>
                                            ) : (
                                                <>
                                                    <i className="fas fa-check"></i>
                                                    {requiresGuestEmail ? 'Register Email' : 'Yes, Confirm Registration'}
                                                </>
                                            )}
                                        </button>
                                        <button
                                            type="button"
                                            onClick={() => setShowModal(false)}
                                            className="inline-flex items-center justify-center rounded-xl border border-slate-200 px-5 py-3 text-sm font-semibold text-slate-700 transition hover:bg-slate-50"
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

function ProgramFact({ label, value }: { label: string; value: string }) {
    return (
        <div className="rounded-xl border border-slate-200 bg-slate-50 p-4">
            <p className="text-[11px] font-semibold uppercase tracking-[0.18em] text-slate-500">{label}</p>
            <p className="mt-2 text-sm font-semibold text-slate-950">{value}</p>
        </div>
    );
}
