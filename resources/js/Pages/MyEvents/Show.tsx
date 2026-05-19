import { FormEvent, ReactNode, useMemo, useState } from 'react';
import { Head, Link, router, usePage } from '@inertiajs/react';
import DashboardLayout from '@/Layouts/DashboardLayout';
import Button from '@/Components/Button';

interface Speaker {
    id: number;
    name: string;
    title?: string | null;
    user?: {
        name: string;
    } | null;
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

interface RefundRequest {
    id: number;
    status: 'pending' | 'approved' | 'declined';
    reason?: string | null;
    admin_note?: string | null;
    requested_at?: string | null;
    reviewed_at?: string | null;
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
    registration_status: 'registered' | 'waitlisted' | 'refunded' | 'attended' | 'no_show';
    meeting_link?: string | null;
    access_notes?: string | null;
    latest_transaction?: Transaction | null;
    refund_request?: RefundRequest | null;
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
    pivot?: {
        revoke_count?: number;
    };
}

interface Props {
    event: Event;
}

export default function ShowMyEvent({ event }: Props) {
    const { sideLinks } = usePage().props as any;
    const [showCancelModal, setShowCancelModal] = useState(false);
    const [isProcessing, setIsProcessing] = useState(false);

    const paymentSummary = useMemo(() => {
        if (!event.latest_transaction) {
            return 'No payment required';
        }

        return `${event.latest_transaction.status} • ${event.latest_transaction.currency} ${Number(event.latest_transaction.amount).toLocaleString()}`;
    }, [event.latest_transaction]);

    const registrationCopy = event.registration_status === 'waitlisted'
        ? 'You are currently on the waitlist. We will notify you if a seat opens up.'
        : event.registration_status === 'refunded'
            ? 'This registration has been refunded. This workspace remains available for status reference.'
            : event.refund_request?.status === 'pending'
                ? 'Your refund request is pending admin review. Your seat remains on hold until the request is resolved.'
                : 'Your attendee registration is confirmed. Use this workspace for access details, resources, and updates.';

    const registrationLabel = event.registration_status === 'registered'
        ? 'confirmed'
        : event.registration_status.replace('_', ' ');

    const statusTone = event.registration_status === 'waitlisted'
        ? 'border-amber-200 bg-amber-50 text-amber-800'
        : event.registration_status === 'refunded'
            ? 'border-rose-200 bg-rose-50 text-rose-700'
            : 'border-emerald-200 bg-emerald-50 text-emerald-800';

    const journeyTone = event.journey_status === 'ongoing'
        ? 'border-blue-200 bg-blue-50 text-blue-800'
        : event.journey_status === 'upcoming'
            ? 'border-slate-200 bg-slate-50 text-slate-700'
            : 'border-zinc-200 bg-zinc-50 text-zinc-700';

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

    const canRequestRefund = event.latest_transaction?.status === 'successful'
        && event.registration_status !== 'refunded';

    const canCancelRegistration = event.registration_status !== 'refunded'
        && event.refund_request?.status !== 'pending';

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

            <div className="space-y-8">
                <section className="overflow-hidden rounded-[30px] border border-slate-200 bg-white shadow-sm">
                    <div className="border-b border-slate-200 bg-[linear-gradient(135deg,#f7f4ed_0%,#ffffff_55%,#f3f0ea_100%)] px-6 py-8 lg:px-8 lg:py-10">
                        <div className="flex flex-col gap-8 xl:flex-row xl:items-start xl:justify-between">
                            <div className="max-w-4xl space-y-5">
                                <div className="flex flex-wrap items-center gap-2">
                                    <span className={`inline-flex rounded-full border px-3 py-1 text-[11px] font-semibold uppercase tracking-[0.16em] ${statusTone}`}>
                                        {registrationLabel}
                                    </span>
                                    <span className={`inline-flex rounded-full border px-3 py-1 text-[11px] font-semibold uppercase tracking-[0.16em] ${journeyTone}`}>
                                        {event.journey_status}
                                    </span>
                                </div>

                                <div className="space-y-3">
                                    <Link href={route('user.events')} className="inline-flex items-center gap-2 text-sm font-medium text-slate-500 transition hover:text-primary">
                                        <i className="fas fa-arrow-left text-xs"></i>
                                        Back to My Events
                                    </Link>
                                    <div>
                                        <h1 className="text-3xl font-semibold tracking-tight text-slate-900 md:text-[2.6rem]">
                                            {event.title}
                                        </h1>
                                        <p className="mt-4 max-w-3xl text-sm leading-7 text-slate-600 md:text-base">
                                            {registrationCopy}
                                        </p>
                                    </div>
                                </div>

                                <div className="grid gap-4 border-t border-slate-200 pt-5 md:grid-cols-3">
                                    <HeroFact
                                        label="Schedule"
                                        value={formatDate(event.start_date)}
                                        detail={event.journey_status === 'ended' ? `Ended ${formatDate(event.end_date)}` : `Ends ${formatDate(event.end_date)}`}
                                    />
                                    <HeroFact
                                        label="Venue"
                                        value={event.physical_address || event.location || 'Shared closer to the event'}
                                        detail={event.mode ? `${event.mode.charAt(0).toUpperCase() + event.mode.slice(1)} format` : 'Hybrid format'}
                                    />
                                    <HeroFact
                                        label="Payment"
                                        value={paymentSummary}
                                        detail={event.refund_request ? `Refund ${event.refund_request.status}` : 'No refund request'}
                                    />
                                </div>
                            </div>

                            <div className="w-full max-w-sm rounded-[24px] border border-slate-200 bg-white/90 p-5 backdrop-blur-sm">
                                <p className="text-[11px] font-semibold uppercase tracking-[0.18em] text-slate-500">
                                    Attendee Actions
                                </p>
                                <div className="mt-4 space-y-3">
                                    {event.meeting_link && event.journey_status !== 'ended' && event.registration_status !== 'waitlisted' && event.registration_status !== 'refunded' && event.refund_request?.status !== 'pending' && (
                                        <a
                                            href={event.meeting_link}
                                            target="_blank"
                                            rel="noreferrer"
                                            className="flex items-center justify-between rounded-2xl border border-slate-900 bg-slate-900 px-4 py-3 text-sm font-semibold text-white transition hover:bg-slate-800"
                                        >
                                            <span>Join event</span>
                                            <i className="fas fa-arrow-up-right-from-square text-xs"></i>
                                        </a>
                                    )}
                                    <a
                                        href={route('events.calendar', event.slug)}
                                        className="flex items-center justify-between rounded-2xl border border-slate-300 bg-white px-4 py-3 text-sm font-medium text-slate-700 transition hover:border-slate-400 hover:bg-slate-50"
                                    >
                                        <span>Add to calendar</span>
                                        <i className="fas fa-calendar-plus text-xs"></i>
                                    </a>
                                    {canRequestRefund && (
                                        <Link
                                            href={route('user.events.refund', event.slug)}
                                            className="flex items-center justify-between rounded-2xl border border-rose-200 bg-rose-50 px-4 py-3 text-sm font-medium text-rose-700 transition hover:bg-rose-100"
                                        >
                                            <span>{event.refund_request ? 'Manage refund' : 'Request refund'}</span>
                                            <i className="fas fa-wallet text-xs"></i>
                                        </Link>
                                    )}
                                    {!canRequestRefund && canCancelRegistration ? (
                                        <button
                                            type="button"
                                            onClick={() => setShowCancelModal(true)}
                                            className="flex w-full items-center justify-between rounded-2xl border border-slate-300 bg-white px-4 py-3 text-sm font-medium text-slate-700 transition hover:border-slate-400 hover:bg-slate-50"
                                        >
                                            <span>Cancel registration</span>
                                            <i className="fas fa-user-minus text-xs"></i>
                                        </button>
                                    ) : null}
                                </div>
                            </div>
                        </div>
                    </div>

                    <div className="bg-white px-6 py-5 lg:px-8">
                        <div className="flex flex-col gap-4 lg:flex-row lg:items-center lg:justify-between">
                            <div>
                                <p className="text-[11px] font-semibold uppercase tracking-[0.18em] text-slate-500">Workspace Summary</p>
                                <p className="mt-2 text-sm leading-6 text-slate-600">
                                    This page is your single source for schedule, access instructions, speaker lineup, and approved attendee materials.
                                </p>
                            </div>
                            <div className="flex flex-wrap gap-6 text-sm text-slate-600">
                                <InlineStat label="Resources" value={availableResources.length.toString()} />
                                <InlineStat label="Speakers" value={event.speakers.length.toString()} />
                                <InlineStat label="Status" value={registrationLabel} />
                            </div>
                        </div>
                    </div>
                </section>

                {event.refund_request && (
                    <section className="rounded-[26px] border border-amber-200 bg-[linear-gradient(135deg,#fffaf0_0%,#ffffff_100%)] p-5 shadow-sm">
                        <div className="flex flex-col gap-4 md:flex-row md:items-start md:justify-between">
                            <div className="space-y-2">
                                <div className="flex flex-wrap items-center gap-2">
                                    <span className={`inline-flex rounded-full px-2.5 py-1 text-xs font-semibold capitalize ${
                                        event.refund_request.status === 'approved'
                                            ? 'bg-emerald-100 text-emerald-700'
                                            : event.refund_request.status === 'declined'
                                                ? 'bg-rose-100 text-rose-700'
                                                : 'bg-amber-100 text-amber-700'
                                    }`}>
                                        Refund {event.refund_request.status}
                                    </span>
                                    {event.refund_request.requested_at && (
                                        <span className="text-xs font-medium text-slate-500">
                                            Requested {formatDate(event.refund_request.requested_at)}
                                        </span>
                                    )}
                                </div>
                                <h2 className="text-lg font-semibold text-slate-900">Refund request in progress</h2>
                                <p className="max-w-3xl text-sm leading-6 text-slate-600">
                                    Track the full refund status, notes, and next steps from the dedicated refund page.
                                </p>
                            </div>

                            <Link
                                href={route('user.events.refund', event.slug)}
                                className="inline-flex items-center justify-center rounded-2xl border border-slate-300 bg-white px-4 py-2.5 text-sm font-medium text-slate-700 transition hover:border-slate-400 hover:bg-slate-50"
                            >
                                Open refund page
                            </Link>
                        </div>
                    </section>
                )}

                <section className="grid gap-6 lg:grid-cols-[minmax(0,1.3fr)_minmax(320px,0.78fr)]">
                    <div className="space-y-6">
                        <WorkspaceSection title="Attendance Brief" eyebrow="Registration">
                            <div className="space-y-5">
                                <WorkspaceRow label="Registration" value={registrationLabel} />
                                <WorkspaceRow label="Payment" value={paymentSummary} />
                                <WorkspaceRow label="Format" value={event.mode ? event.mode.charAt(0).toUpperCase() + event.mode.slice(1) : 'Hybrid'} />
                                <WorkspaceRow label="Refund" value={event.refund_request?.status ?? 'not requested'} />
                            </div>
                        </WorkspaceSection>

                        <WorkspaceSection title="Schedule and Access" eyebrow="Operations">
                            <div className="space-y-5">
                                <WorkspaceRow label="Start" value={formatDate(event.start_date)} />
                                <WorkspaceRow label="End" value={formatDate(event.end_date)} />
                                <WorkspaceRow label="Venue" value={event.physical_address || event.location || 'Shared closer to the event'} />
                                <WorkspaceRow label="Contact" value={event.contact_email || 'Support team'} />
                                <WorkspaceRow
                                    label="Access notes"
                                    value={event.access_notes || (event.registration_status === 'waitlisted'
                                        ? 'Wait for confirmation before planning travel or session access.'
                                        : event.refund_request?.status === 'pending'
                                            ? 'Refund request is pending review. Final event access may change after admin review.'
                                        : 'Any final joining instructions will appear here before the event starts.')}
                                />
                                {event.refund_request && (
                                    <WorkspaceRow
                                        label="Refund request"
                                        value={`Status: ${event.refund_request.status}${event.refund_request.admin_note ? ` • Note: ${event.refund_request.admin_note}` : ''}`}
                                    />
                                )}
                            </div>
                        </WorkspaceSection>

                        {programProfile?.program_type === 'discipleship_track' && (
                            <WorkspaceSection title="Discipleship Track Commitments" eyebrow="Formation">
                                <div className="space-y-5">
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
                            <div className="space-y-3">
                                {event.registration_status === 'waitlisted' || event.registration_status === 'refunded' || event.refund_request?.status === 'pending' ? (
                                    <p className="text-sm leading-6 text-slate-600">
                                        Attendee resources are unavailable while your registration is not fully active.
                                    </p>
                                ) : availableResources.length > 0 ? (
                                    availableResources.map((resource) => (
                                        <div key={resource.id}>
                                            {resource.type === 'file' && resource.file_path && (
                                                <a
                                                    href={`/storage/${resource.file_path}`}
                                                    target="_blank"
                                                    rel="noreferrer"
                                                    className="group flex items-center justify-between rounded-2xl border border-slate-200 bg-white px-5 py-4 transition hover:border-slate-300 hover:bg-slate-50"
                                                >
                                                    <div>
                                                        <p className="font-medium text-slate-900">{resource.title}</p>
                                                        {resource.description && <p className="mt-1 text-sm leading-6 text-slate-600">{resource.description}</p>}
                                                    </div>
                                                    <span className="text-sm font-medium text-slate-700 transition group-hover:text-slate-900">Download</span>
                                                </a>
                                            )}
                                            {resource.type === 'link' && resource.external_link && (
                                                <a
                                                    href={resource.external_link}
                                                    target="_blank"
                                                    rel="noreferrer"
                                                    className="group flex items-center justify-between rounded-2xl border border-slate-200 bg-white px-5 py-4 transition hover:border-slate-300 hover:bg-slate-50"
                                                >
                                                    <div>
                                                        <p className="font-medium text-slate-900">{resource.title}</p>
                                                        {resource.description && <p className="mt-1 text-sm leading-6 text-slate-600">{resource.description}</p>}
                                                    </div>
                                                    <span className="text-sm font-medium text-slate-700 transition group-hover:text-slate-900">Open</span>
                                                </a>
                                            )}
                                        </div>
                                    ))
                                ) : (
                                    <p className="text-sm leading-6 text-slate-600">No attendee resources have been published yet.</p>
                                )}
                            </div>
                        </WorkspaceSection>
                    </div>

                    <aside className="space-y-6">
                        <WorkspaceSection title="Speaker Lineup" eyebrow="People">
                            <div className="space-y-3">
                                {event.speakers.length > 0 ? (
                                    event.speakers.map((speaker) => (
                                        <div key={speaker.id} className="rounded-2xl border border-slate-200 bg-slate-50/70 px-4 py-4">
                                            <div className="flex items-start gap-3">
                                                <div className="flex h-11 w-11 shrink-0 items-center justify-center rounded-full bg-slate-900 text-sm font-semibold text-white">
                                                    {(speaker.user?.name || speaker.name).charAt(0)}
                                                </div>
                                                <div>
                                                    <p className="font-medium text-slate-900">{speaker.user?.name || speaker.name}</p>
                                                    {speaker.title && <p className="mt-1 text-sm leading-6 text-slate-600">{speaker.title}</p>}
                                                </div>
                                            </div>
                                        </div>
                                    ))
                                ) : (
                                    <p className="text-sm leading-6 text-slate-600">Speaker details will appear here once the lineup is published.</p>
                                )}
                            </div>
                        </WorkspaceSection>

                        <WorkspaceSection title="Event Description" eyebrow="Brief">
                            <div className="prose prose-slate max-w-none text-sm leading-7 prose-headings:text-slate-900 prose-p:text-slate-700" dangerouslySetInnerHTML={{ __html: event.description || '<p>No additional description available.</p>' }} />
                        </WorkspaceSection>
                    </aside>
                </section>
            </div>

            {showCancelModal && (
                <div className="fixed inset-0 z-50 flex items-center justify-center bg-slate-950/45 px-4 backdrop-blur-sm">
                    <div className="w-full max-w-xl overflow-hidden rounded-3xl border border-slate-200 bg-white shadow-2xl">
                        <div className="bg-rose-50 px-6 py-5">
                            <div className="flex items-start gap-4">
                                <div className="flex h-12 w-12 shrink-0 items-center justify-center rounded-2xl bg-rose-100 text-rose-700">
                                    <i className="fas fa-user-minus"></i>
                                </div>
                                <div className="space-y-2">
                                    <p className="text-[11px] font-semibold uppercase tracking-[0.16em] text-slate-500">
                                        Cancel Registration
                                    </p>
                                    <h3 className="text-xl font-semibold text-slate-900">
                                        Cancel this registration?
                                    </h3>
                                    <p className="text-sm leading-6 text-slate-600">
                                        This removes the event from your attendee workspace and may affect your remaining registration attempts.
                                    </p>
                                </div>
                            </div>
                        </div>

                        <form onSubmit={handleCancelEvent} className="space-y-5 px-6 py-6">
                            <div className="rounded-2xl border border-rose-200 bg-rose-50/60 p-4 text-sm leading-6 text-slate-600">
                                <p className="font-medium text-rose-700">Before you continue</p>
                                <p className="mt-2">
                                    This action cancels your registration immediately. If you paid for the event and need money returned, use the dedicated refund page instead.
                                </p>
                            </div>

                            <div className="flex flex-col-reverse gap-3 border-t border-slate-100 pt-5 sm:flex-row sm:justify-end">
                                <Button
                                    type="button"
                                    variant="secondary"
                                    onClick={() => {
                                        setShowCancelModal(false);
                                    }}
                                    className="justify-center"
                                >
                                    Go back
                                </Button>
                                <Button
                                    type="submit"
                                    variant="danger"
                                    loading={isProcessing}
                                    className="justify-center"
                                >
                                    Confirm cancel
                                </Button>
                            </div>
                        </form>
                    </div>
                </div>
            )}
        </DashboardLayout>
    );
}

function HeroFact({
    label,
    value,
    detail,
}: {
    label: string;
    value: string;
    detail: string;
}) {
    return (
        <div className="space-y-2">
            <p className="text-[11px] font-semibold uppercase tracking-[0.16em] text-slate-500">{label}</p>
            <p className="text-sm font-medium leading-6 text-slate-900">{value}</p>
            <p className="text-sm leading-6 text-slate-500">{detail}</p>
        </div>
    );
}

function InlineStat({ label, value }: { label: string; value: string }) {
    return (
        <div className="space-y-1">
            <p className="text-[11px] font-semibold uppercase tracking-[0.16em] text-slate-500">{label}</p>
            <p className="text-sm font-medium capitalize text-slate-900">{value}</p>
        </div>
    );
}

function WorkspaceSection({
    eyebrow,
    title,
    children,
}: {
    eyebrow: string;
    title: string;
    children: ReactNode;
}) {
    return (
        <section className="rounded-[28px] border border-slate-200 bg-white p-6 shadow-sm lg:p-7">
            <div className="mb-5">
                <p className="text-[11px] font-semibold uppercase tracking-[0.18em] text-slate-500">{eyebrow}</p>
                <h2 className="mt-2 text-xl font-semibold text-slate-900">{title}</h2>
            </div>
            {children}
        </section>
    );
}

function WorkspaceRow({ label, value }: { label: string; value: string }) {
    return (
        <div className="grid gap-2 border-b border-slate-100 pb-4 last:border-b-0 last:pb-0 sm:grid-cols-[148px_minmax(0,1fr)]">
            <p className="text-sm font-medium text-slate-500">{label}</p>
            <p className="text-sm leading-7 text-slate-900">{value}</p>
        </div>
    );
}
