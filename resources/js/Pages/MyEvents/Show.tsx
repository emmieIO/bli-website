import { FormEvent, ReactNode, useMemo, useState } from 'react';
import { Head, Link, router, usePage } from '@inertiajs/react';
import DashboardLayout from '@/Layouts/DashboardLayout';

interface Speaker {
    id: number;
    name: string;
    title?: string | null;
    user?: { name: string; } | null;
}

interface Resource {
    id: number;
    title: string;
    description?: string | null;
    type: 'file' | 'link';
    file_path?: string | null;
    external_link?: string | null;
    is_downloadable?: boolean;
}

interface Transaction {
    id: number;
    amount: string;
    currency: string;
    status: string;
    paid_at?: string | null;
    payment_ref?: string | null;
}

interface Event {
    id: number;
    title: string;
    slug: string;
    description?: string | null;
    start_date: string;
    end_date: string;
    location?: string | null;
    physical_address?: string | null;
    mode?: string | null;
    contact_email?: string | null;
    status: string;
    journey_status: 'upcoming' | 'ongoing' | 'ended';
    registration_status: 'registered' | 'waitlisted' | 'attended' | 'no_show';
    meeting_link?: string | null;
    access_notes?: string | null;
    latest_transaction?: Transaction | null;
    resources: Resource[];
    speakers: Speaker[];
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
    };
    pivot?: { revoke_count?: number; };
}

interface Props { event: Event; }

export default function ShowMyEvent({ event }: Props) {
    const { sideLinks } = usePage().props as any;
    const [showCancelModal, setShowCancelModal] = useState(false);
    const [isProcessing, setIsProcessing] = useState(false);

    const paymentSummary = useMemo(() => {
        if (!event.latest_transaction) return 'No payment required';
        return `${event.latest_transaction.status} • ${event.latest_transaction.currency} ${Number(event.latest_transaction.amount).toLocaleString()}`;
    }, [event.latest_transaction]);

    const registrationCopy = event.registration_status === 'waitlisted'
        ? 'You are currently on the waitlist. We will notify you if a seat opens up.'
        : 'Your attendee registration is confirmed. Use this workspace for access details, resources, and updates.';

    const registrationLabel = event.registration_status === 'registered' ? 'confirmed' : event.registration_status.replace('_', ' ');

    const statusTone = event.registration_status === 'waitlisted'
        ? 'border-amber-200 bg-amber-50 text-amber-700'
        : 'border-lime-200 bg-lime-50 text-lime-700';

    const journeyTone = event.journey_status === 'ongoing'
        ? 'border-primary-200 bg-primary-50 text-primary'
        : event.journey_status === 'upcoming'
            ? 'border-slate-200 bg-slate-50 text-slate-600'
            : 'border-zinc-200 bg-zinc-50 text-zinc-600';

    const formatDate = (value: string) => new Intl.DateTimeFormat('en-US', {
        weekday: 'long',
        month: 'long',
        day: 'numeric',
        year: 'numeric',
        hour: 'numeric',
        minute: '2-digit',
    }).format(new Date(value));

    const handleCancelEvent = (e: FormEvent) => {
        e.preventDefault();
        setIsProcessing(true);
        router.delete(route('user.revoke.event', event.slug), {
            onFinish: () => {
                setIsProcessing(false);
                setShowCancelModal(false);
            },
        });
    };

    const canCancelRegistration = event.registration_status === 'registered' || event.registration_status === 'waitlisted';
    const availableResources = event.resources.filter((resource) => resource.is_downloadable);
    const programProfile = event.program_profile;
    const prayerTargetLabel = programProfile?.weekly_prayer_target_minutes
        ? `${Math.floor(programProfile.weekly_prayer_target_minutes / 60)}h ${programProfile.weekly_prayer_target_minutes % 60}m weekly prayer target`
        : null;
    const evangelismTargetLabel = programProfile?.weekly_evangelism_target_min
        ? `${programProfile.weekly_evangelism_target_min}-${programProfile.weekly_evangelism_target_max ?? programProfile.weekly_evangelism_target_min} people reached weekly`
        : null;
    const discipleshipTargetLabel = programProfile?.weekly_discipleship_target_min
        ? `Raise ${programProfile.weekly_discipleship_target_min}-${programProfile.weekly_discipleship_target_max ?? programProfile.weekly_discipleship_target_min} disciples`
        : null;

    return (
        <DashboardLayout sideLinks={sideLinks}>
            <Head title={event.title} />

            <div className="space-y-6">
                <section className="overflow-hidden rounded-lg border border-slate-200 bg-white">
                    <div className="border-b border-slate-200 bg-gradient-to-br from-slate-50 via-white to-primary-50/20 px-6 py-7 lg:px-8 lg:py-8">
                        <div className="flex flex-col gap-6 xl:flex-row xl:items-start xl:justify-between">
                            <div className="max-w-4xl space-y-5">
                                <div className="flex flex-wrap items-center gap-2">
                                    <span className={`inline-flex rounded-md border px-2.5 py-1 text-[11px] font-semibold uppercase tracking-wider ${statusTone}`}>
                                        {registrationLabel}
                                    </span>
                                    <span className={`inline-flex rounded-md border px-2.5 py-1 text-[11px] font-semibold uppercase tracking-wider ${journeyTone}`}>
                                        {event.journey_status}
                                    </span>
                                </div>

                                <div>
                                    <Link href={route('user.events')} className="inline-flex items-center gap-1.5 text-[13px] font-medium text-slate-500 transition hover:text-primary">
                                        <i className="fas fa-arrow-left text-[11px]"></i>
                                        Back to My Events
                                    </Link>
                                    <h1 className="mt-2 text-2xl font-semibold tracking-tight text-slate-900">{event.title}</h1>
                                    <p className="mt-3 max-w-3xl text-sm leading-relaxed text-slate-500">{registrationCopy}</p>
                                </div>

                                <div className="grid gap-4 border-t border-slate-200 pt-5 md:grid-cols-3">
                                    <HeroFact label="Schedule" value={formatDate(event.start_date)} detail={event.journey_status === 'ended' ? `Ended ${formatDate(event.end_date)}` : `Ends ${formatDate(event.end_date)}`} />
                                    <HeroFact label="Venue" value={event.physical_address || event.location || 'Shared closer to the event'} detail={event.mode ? `${event.mode.charAt(0).toUpperCase() + event.mode.slice(1)} format` : 'Hybrid format'} />
                                    <HeroFact label="Payment" value={paymentSummary} detail={event.latest_transaction ? 'Payment recorded' : 'No payment record'} />
                                </div>
                            </div>

                            <div className="w-full max-w-sm rounded-lg border border-slate-200 bg-white/90 p-5 backdrop-blur-sm">
                                <p className="text-[11px] font-semibold uppercase tracking-[0.16em] text-slate-400">Attendee Actions</p>
                                <div className="mt-4 space-y-2.5">
                                    {event.meeting_link && event.journey_status !== 'ended' && event.registration_status !== 'waitlisted' && (
                                        <a href={event.meeting_link} target="_blank" rel="noreferrer"
                                            className="flex items-center justify-between rounded-md bg-primary px-4 py-2.5 text-sm font-medium text-white transition hover:bg-primary-600 shadow-sm">
                                            <span>Join event</span>
                                            <i className="fas fa-arrow-up-right-from-square text-[11px]"></i>
                                        </a>
                                    )}
                                    <a href={route('events.calendar', event.slug)}
                                        className="flex items-center justify-between rounded-md border border-slate-200 bg-white px-4 py-2.5 text-sm font-medium text-slate-700 transition hover:bg-slate-50">
                                        <span>Add to calendar</span>
                                        <i className="fas fa-calendar-plus text-[11px]"></i>
                                    </a>
                                    {canCancelRegistration && (
                                        <button type="button" onClick={() => setShowCancelModal(true)}
                                            className="flex w-full items-center justify-between rounded-md border border-slate-200 bg-white px-4 py-2.5 text-sm font-medium text-accent transition hover:bg-accent-50">
                                            <span>Cancel registration</span>
                                            <i className="fas fa-user-minus text-[11px]"></i>
                                        </button>
                                    )}
                                </div>
                            </div>
                        </div>
                    </div>

                    <div className="bg-white px-6 py-5 lg:px-8">
                        <div className="flex flex-col gap-4 lg:flex-row lg:items-center lg:justify-between">
                            <div>
                                <p className="text-[11px] font-semibold uppercase tracking-[0.16em] text-slate-400">Workspace Summary</p>
                                <p className="mt-1 text-sm leading-relaxed text-slate-500">
                                    This page is your single source for schedule, access instructions, speaker lineup, and approved attendee materials.
                                </p>
                            </div>
                            <div className="flex flex-wrap gap-6 text-sm text-slate-500">
                                <InlineStat label="Resources" value={availableResources.length.toString()} />
                                <InlineStat label="Speakers" value={event.speakers.length.toString()} />
                                <InlineStat label="Status" value={registrationLabel} />
                            </div>
                        </div>
                    </div>
                </section>

                <section className="grid gap-6 lg:grid-cols-[minmax(0,1.3fr)_minmax(320px,0.78fr)]">
                    <div className="space-y-5">
                        <WorkspaceSection title="Attendance Brief" eyebrow="Registration">
                            <div className="space-y-4">
                                <WorkspaceRow label="Registration" value={registrationLabel} />
                                <WorkspaceRow label="Payment" value={paymentSummary} />
                                <WorkspaceRow label="Format" value={event.mode ? event.mode.charAt(0).toUpperCase() + event.mode.slice(1) : 'Hybrid'} />
                            </div>
                        </WorkspaceSection>

                        <WorkspaceSection title="Schedule and Access" eyebrow="Operations">
                            <div className="space-y-4">
                                <WorkspaceRow label="Start" value={formatDate(event.start_date)} />
                                <WorkspaceRow label="End" value={formatDate(event.end_date)} />
                                <WorkspaceRow label="Venue" value={event.physical_address || event.location || 'Shared closer to the event'} />
                                <WorkspaceRow label="Contact" value={event.contact_email || 'Support team'} />
                                <WorkspaceRow label="Access notes" value={event.access_notes || (event.registration_status === 'waitlisted' ? 'Wait for confirmation before planning travel or session access.' : 'Any final joining instructions will appear here before the event starts.')} />
                            </div>
                        </WorkspaceSection>

                        {programProfile?.program_type === 'discipleship_track' && (
                            <WorkspaceSection title="Discipleship Track Commitments" eyebrow="Formation">
                                <div className="space-y-4">
                                    <WorkspaceRow label="Track" value={programProfile.program_code ? `${programProfile.program_code} discipleship track` : 'Discipleship track'} />
                                    <WorkspaceRow label="Duration" value={programProfile.cohort_duration_weeks ? `${programProfile.cohort_duration_weeks} weeks` : 'Configured by admin'} />
                                    <WorkspaceRow label="Central teaching" value={programProfile.central_teaching_schedule || 'Weekly teaching cadence to be announced'} />
                                    <WorkspaceRow label="Group meetings" value={programProfile.group_meeting_schedule || 'Weekly accountability meetings to be announced'} />
                                    <WorkspaceRow label="Group model" value={programProfile.group_model || 'Cluster-based discipleship group'} />
                                    {prayerTargetLabel && <WorkspaceRow label="Prayer" value={prayerTargetLabel} />}
                                    {evangelismTargetLabel && <WorkspaceRow label="Evangelism" value={evangelismTargetLabel} />}
                                    {discipleshipTargetLabel && <WorkspaceRow label="Multiplication" value={discipleshipTargetLabel} />}
                                    {programProfile.screening_note && <WorkspaceRow label="Screening note" value={programProfile.screening_note} />}
                                </div>
                            </WorkspaceSection>
                        )}

                        <WorkspaceSection title="Attendee Resources" eyebrow="Materials">
                            <div className="space-y-2.5">
                                {event.registration_status === 'waitlisted' ? (
                                    <p className="text-sm leading-relaxed text-slate-500">Attendee resources are unavailable while your registration is waitlisted.</p>
                                ) : availableResources.length > 0 ? (
                                    availableResources.map((resource) => (
                                        <div key={resource.id}>
                                            {resource.type === 'file' && resource.file_path && (
                                                <a href={`/storage/${resource.file_path}`} target="_blank" rel="noreferrer"
                                                    className="group flex items-center justify-between rounded-md border border-slate-200 bg-white px-4 py-3.5 transition hover:border-slate-300 hover:bg-slate-50">
                                                    <div>
                                                        <p className="text-sm font-medium text-slate-900">{resource.title}</p>
                                                        {resource.description && <p className="mt-0.5 text-[13px] text-slate-500">{resource.description}</p>}
                                                    </div>
                                                    <span className="text-[13px] font-medium text-slate-600 transition group-hover:text-slate-900">Download</span>
                                                </a>
                                            )}
                                            {resource.type === 'link' && resource.external_link && (
                                                <a href={resource.external_link} target="_blank" rel="noreferrer"
                                                    className="group flex items-center justify-between rounded-md border border-slate-200 bg-white px-4 py-3.5 transition hover:border-slate-300 hover:bg-slate-50">
                                                    <div>
                                                        <p className="text-sm font-medium text-slate-900">{resource.title}</p>
                                                        {resource.description && <p className="mt-0.5 text-[13px] text-slate-500">{resource.description}</p>}
                                                    </div>
                                                    <span className="text-[13px] font-medium text-slate-600 transition group-hover:text-slate-900">Open</span>
                                                </a>
                                            )}
                                        </div>
                                    ))
                                ) : (
                                    <p className="text-sm leading-relaxed text-slate-500">No attendee resources have been published yet.</p>
                                )}
                            </div>
                        </WorkspaceSection>
                    </div>

                    <aside className="space-y-5">
                        <WorkspaceSection title="Speaker Lineup" eyebrow="People">
                            <div className="space-y-2.5">
                                {event.speakers.length > 0 ? (
                                    event.speakers.map((speaker) => (
                                        <div key={speaker.id} className="rounded-lg border border-slate-200 bg-slate-50/70 px-4 py-3.5">
                                            <div className="flex items-start gap-3">
                                                <div className="flex h-10 w-10 shrink-0 items-center justify-center rounded-lg bg-primary text-sm font-semibold text-white">
                                                    {(speaker.user?.name || speaker.name).charAt(0)}
                                                </div>
                                                <div>
                                                    <p className="text-sm font-medium text-slate-900">{speaker.user?.name || speaker.name}</p>
                                                    {speaker.title && <p className="mt-0.5 text-[13px] text-slate-500">{speaker.title}</p>}
                                                </div>
                                            </div>
                                        </div>
                                    ))
                                ) : (
                                    <p className="text-sm text-slate-500">Speaker details will appear here once the lineup is published.</p>
                                )}
                            </div>
                        </WorkspaceSection>

                        <WorkspaceSection title="Event Description" eyebrow="Brief">
                            <div className="prose prose-slate max-w-none text-sm leading-7 prose-headings:text-slate-900 prose-p:text-slate-600" dangerouslySetInnerHTML={{ __html: event.description || '<p>No additional description available.</p>' }} />
                        </WorkspaceSection>
                    </aside>
                </section>
            </div>

            {showCancelModal && (
                <div className="fixed inset-0 z-50 flex items-center justify-center bg-slate-900/35 px-4 backdrop-blur-sm">
                    <div className="w-full max-w-xl overflow-hidden rounded-lg border border-slate-200 bg-white shadow-lg">
                        <div className="bg-accent-50 px-6 py-5">
                            <div className="flex items-start gap-4">
                                <div className="flex h-10 w-10 shrink-0 items-center justify-center rounded-lg bg-accent-100 text-accent">
                                    <i className="fas fa-user-minus"></i>
                                </div>
                                <div>
                                    <p className="text-[11px] font-semibold uppercase tracking-wider text-slate-500">Cancel Registration</p>
                                    <h3 className="mt-1 text-lg font-semibold text-slate-900">Cancel this registration?</h3>
                                    <p className="mt-1 text-sm leading-relaxed text-slate-500">This removes the event from your attendee workspace and may affect your remaining registration attempts.</p>
                                </div>
                            </div>
                        </div>

                        <form onSubmit={handleCancelEvent} className="space-y-5 px-6 py-6">
                            <div className="rounded-md border border-accent-200 bg-accent-50/60 p-4 text-sm leading-relaxed text-slate-600">
                                <p className="font-medium text-accent">Before you continue</p>
                                <p className="mt-1">This action cancels your registration immediately and removes this event from your attendee workspace.</p>
                            </div>

                            <div className="flex flex-col-reverse gap-3 border-t border-slate-100 pt-5 sm:flex-row sm:justify-end">
                                <button type="button" onClick={() => setShowCancelModal(false)}
                                    className="rounded-md border border-slate-200 bg-white px-4 py-2 text-sm font-medium text-slate-700 transition hover:bg-slate-50">
                                    Go back
                                </button>
                                <button type="submit" disabled={isProcessing}
                                    className="rounded-md bg-accent px-4 py-2 text-sm font-medium text-white transition hover:bg-accent-600 disabled:opacity-50">
                                    {isProcessing ? 'Cancelling...' : 'Confirm cancel'}
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            )}
        </DashboardLayout>
    );
}

function HeroFact({ label, value, detail }: { label: string; value: string; detail: string }) {
    return (
        <div>
            <p className="text-[11px] font-semibold uppercase tracking-wider text-slate-400">{label}</p>
            <p className="mt-1 text-sm font-medium text-slate-900">{value}</p>
            <p className="mt-0.5 text-[13px] text-slate-500">{detail}</p>
        </div>
    );
}

function InlineStat({ label, value }: { label: string; value: string }) {
    return (
        <div>
            <p className="text-[11px] font-semibold uppercase tracking-wider text-slate-400">{label}</p>
            <p className="mt-0.5 text-sm font-medium capitalize text-slate-900">{value}</p>
        </div>
    );
}

function WorkspaceSection({ eyebrow, title, children }: { eyebrow: string; title: string; children: ReactNode }) {
    return (
        <section className="rounded-lg border border-slate-200 bg-white p-5 lg:p-6">
            <div className="mb-4">
                <p className="text-[11px] font-semibold uppercase tracking-wider text-slate-400">{eyebrow}</p>
                <h2 className="mt-1 text-lg font-semibold tracking-tight text-slate-900">{title}</h2>
            </div>
            {children}
        </section>
    );
}

function WorkspaceRow({ label, value }: { label: string; value: string }) {
    return (
        <div className="grid gap-2 border-b border-slate-100 pb-3.5 last:border-b-0 last:pb-0 sm:grid-cols-[148px_minmax(0,1fr)]">
            <p className="text-sm font-medium text-slate-500">{label}</p>
            <p className="text-sm leading-relaxed text-slate-900">{value}</p>
        </div>
    );
}
