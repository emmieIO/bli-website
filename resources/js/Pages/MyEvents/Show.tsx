import { FormEvent, ReactNode, useState } from 'react';
import { Head, Link, router, usePage } from '@inertiajs/react';
import {
    AlertTriangle,
    ArrowLeft,
    ArrowUpRight,
    CalendarDays,
    CheckCircle2,
    Clock3,
    Download,
    ExternalLink,
    FileText,
    Mail,
    MapPin,
    MonitorPlay,
    Users,
    X,
} from 'lucide-react';
import DashboardLayout from '@/Layouts/DashboardLayout';
import type {
    AttendeeEventProgramProfile,
    AttendeeEventResource,
    AttendeeWorkspaceEvent,
} from '@/types/events';

interface Props {
    event: AttendeeWorkspaceEvent;
}

const registrationLabels: Record<AttendeeWorkspaceEvent['registration_status'], string> = {
    registered: 'Registration confirmed',
    attended: 'Attendance recorded',
    no_show: 'Marked as no-show',
};

const registrationTones: Record<AttendeeWorkspaceEvent['registration_status'], string> = {
    registered: 'border-emerald-200 bg-emerald-50 text-emerald-700',
    attended: 'border-slate-200 bg-slate-50 text-slate-700',
    no_show: 'border-red-200 bg-red-50 text-red-700',
};

const journeyLabels: Record<AttendeeWorkspaceEvent['journey_status'], string> = {
    upcoming: 'Upcoming',
    ongoing: 'In progress',
    ended: 'Completed',
};

export default function ShowMyEvent({ event }: Props) {
    const { sideLinks } = usePage().props as any;
    const [showCancelModal, setShowCancelModal] = useState(false);
    const [isProcessing, setIsProcessing] = useState(false);

    const availableResources = event.resources.filter((resource) => resource.is_downloadable);
    const canJoin = Boolean(event.meeting_link) && event.journey_status !== 'ended';
    const canCancelRegistration = event.registration_status === 'registered' && event.journey_status === 'upcoming';
    const venue = event.physical_address || event.location || 'Details will be shared here';
    const eventFee = Number(event.entry_fee ?? 0);
    const paymentSummary = event.latest_transaction
        ? `${toTitleCase(event.latest_transaction.status)} · ${event.latest_transaction.currency} ${Number(event.latest_transaction.amount).toLocaleString()}`
        : eventFee > 0
            ? `NGN ${eventFee.toLocaleString()} · no payment record`
            : 'No payment required';

    const handleCancelEvent = (submitEvent: FormEvent) => {
        submitEvent.preventDefault();
        setIsProcessing(true);
        router.delete(route('user.revoke.event', event.slug), {
            onFinish: () => {
                setIsProcessing(false);
                setShowCancelModal(false);
            },
        });
    };

    return (
        <DashboardLayout sideLinks={sideLinks}>
            <Head title={event.title} />

            <div className="mx-auto w-full max-w-7xl space-y-5">
                <Link
                    href={route('user.events')}
                    className="inline-flex items-center gap-2 text-sm font-medium text-slate-500 transition hover:text-primary"
                >
                    <ArrowLeft size={16} />
                    My events
                </Link>

                <header className="overflow-hidden border border-slate-200 bg-white">
                    <div className="relative min-h-[260px] sm:min-h-[300px]">
                        <img
                            src={assetUrl(event.program_cover, '/assets/img/leadership-workshop.png')}
                            alt=""
                            className="absolute inset-0 h-full w-full object-cover"
                            onError={(imageEvent) => {
                                imageEvent.currentTarget.src = '/assets/img/leadership-workshop.png';
                            }}
                        />
                        <div className="absolute inset-0 bg-slate-950/65" />
                        <div className="relative flex min-h-[260px] flex-col justify-end px-5 py-6 text-white sm:min-h-[300px] sm:px-8 sm:py-8 lg:px-10">
                            <div className="flex flex-wrap gap-2">
                                <span className={`border px-2.5 py-1 text-xs font-semibold ${registrationTones[event.registration_status]}`}>
                                    {registrationLabels[event.registration_status]}
                                </span>
                                <span className="border border-white/25 bg-slate-950/40 px-2.5 py-1 text-xs font-semibold text-white">
                                    {journeyLabels[event.journey_status]}
                                </span>
                            </div>
                            {event.theme && <p className="mt-5 text-sm font-semibold text-accent-300">{event.theme}</p>}
                            <h1 className="mt-2 max-w-4xl text-2xl font-bold leading-tight text-white sm:text-3xl lg:text-4xl">
                                {event.title}
                            </h1>
                            <p className="mt-3 max-w-3xl text-sm leading-6 text-slate-200 sm:text-base">
                                Your attendee workspace for joining details, preparation, resources, and event updates.
                            </p>
                        </div>
                    </div>

                    <div className="grid border-t border-slate-200 lg:grid-cols-[minmax(0,1fr)_auto] lg:items-center">
                        <div className="grid divide-y divide-slate-200 sm:grid-cols-3 sm:divide-x sm:divide-y-0">
                            <HeaderFact icon={<CalendarDays size={17} />} label="Starts" value={formatDate(event.start_date)} />
                            <HeaderFact icon={<Clock3 size={17} />} label="Time" value={formatTimeRange(event.start_date, event.end_date)} />
                            <HeaderFact icon={<MapPin size={17} />} label="Format" value={event.mode ? toTitleCase(event.mode) : 'Event'} />
                        </div>
                        <div className="flex flex-col gap-2 border-t border-slate-200 p-4 sm:flex-row lg:border-l lg:border-t-0">
                            {canJoin && (
                                <a
                                    href={event.meeting_link ?? '#'}
                                    target="_blank"
                                    rel="noreferrer"
                                    className="inline-flex min-h-10 items-center justify-center gap-2 bg-primary px-4 text-sm font-semibold text-white transition hover:bg-primary-600"
                                >
                                    <MonitorPlay size={17} />
                                    Join event
                                </a>
                            )}
                            <a
                                href={route('events.calendar', event.slug)}
                                className="inline-flex min-h-10 items-center justify-center gap-2 border border-slate-300 bg-white px-4 text-sm font-semibold text-slate-700 transition hover:bg-slate-50"
                            >
                                <CalendarDays size={17} />
                                Add to calendar
                            </a>
                        </div>
                    </div>
                </header>

                <NextStep event={event} canJoin={canJoin} />

                <div className="grid items-start gap-5 lg:grid-cols-[minmax(0,1.55fr)_minmax(300px,0.75fr)]">
                    <div className="space-y-5">
                        <WorkspaceSection eyebrow="Event brief" title="Overview">
                            <div
                                className="rich-content"
                                dangerouslySetInnerHTML={{ __html: event.description || '<p>No additional description has been published.</p>' }}
                            />
                        </WorkspaceSection>

                        {event.program_profile?.program_type === 'discipleship_track' && (
                            <Commitments profile={event.program_profile} />
                        )}

                        <WorkspaceSection eyebrow="Attendee materials" title={`Resources (${availableResources.length})`}>
                            {availableResources.length > 0 ? (
                                <div className="divide-y divide-slate-200 border-y border-slate-200">
                                    {availableResources.map((resource) => (
                                        <ResourceRow key={resource.id} resource={resource} />
                                    ))}
                                </div>
                            ) : (
                                <EmptyState
                                    icon={<FileText size={20} />}
                                    title="No resources yet"
                                    description="Published slides, links, and preparation materials will appear here."
                                />
                            )}
                        </WorkspaceSection>
                    </div>

                    <aside className="space-y-5 lg:sticky lg:top-20">
                        <WorkspaceSection eyebrow="Practical details" title="Event information">
                            <div className="divide-y divide-slate-100">
                                <InfoRow icon={<CalendarDays size={17} />} label="Schedule" value={`${formatDate(event.start_date)}, ${formatTimeRange(event.start_date, event.end_date)}`} />
                                <InfoRow icon={<MapPin size={17} />} label="Venue" value={venue} />
                                <InfoRow icon={<CheckCircle2 size={17} />} label="Payment" value={paymentSummary} />
                                <InfoRow
                                    icon={<Mail size={17} />}
                                    label="Contact"
                                    value={event.contact_email || 'Beacon support team'}
                                    href={event.contact_email ? `mailto:${event.contact_email}` : undefined}
                                />
                            </div>
                        </WorkspaceSection>

                        <WorkspaceSection eyebrow="Joining instructions" title="Access notes">
                            <p className="text-sm leading-6 text-slate-600">
                                {event.access_notes || 'Final joining instructions will be published here before the event starts.'}
                            </p>
                        </WorkspaceSection>

                        <WorkspaceSection eyebrow="People" title={`Speakers (${event.speakers.length})`}>
                            {event.speakers.length > 0 ? (
                                <div className="divide-y divide-slate-100">
                                    {event.speakers.map((speaker) => {
                                        const name = speaker.user?.name || speaker.name;

                                        return (
                                            <div key={speaker.id} className="flex items-center gap-3 py-3 first:pt-0 last:pb-0">
                                                <div className="flex h-10 w-10 shrink-0 items-center justify-center bg-primary text-sm font-bold text-white">
                                                    {initials(name)}
                                                </div>
                                                <div className="min-w-0">
                                                    <p className="truncate text-sm font-semibold text-slate-900">{name}</p>
                                                    <p className="truncate text-sm text-slate-500">{speaker.title || 'Guest speaker'}</p>
                                                </div>
                                            </div>
                                        );
                                    })}
                                </div>
                            ) : (
                                <EmptyState
                                    icon={<Users size={20} />}
                                    title="Lineup coming soon"
                                    description="Confirmed speakers will appear here."
                                />
                            )}
                        </WorkspaceSection>

                        {canCancelRegistration && (
                            <button
                                type="button"
                                onClick={() => setShowCancelModal(true)}
                                className="inline-flex w-full items-center justify-center gap-2 border border-red-200 bg-white px-4 py-2.5 text-sm font-semibold text-red-700 transition hover:bg-red-50"
                            >
                                <X size={16} />
                                Cancel registration
                            </button>
                        )}
                    </aside>
                </div>
            </div>

            {showCancelModal && (
                <div className="fixed inset-0 z-50 flex items-center justify-center bg-slate-950/50 px-4">
                    <div role="dialog" aria-modal="true" aria-labelledby="cancel-registration-title" className="w-full max-w-lg border border-slate-200 bg-white shadow-xl">
                        <div className="flex items-start gap-4 border-b border-slate-200 px-5 py-5 sm:px-6">
                            <div className="flex h-10 w-10 shrink-0 items-center justify-center bg-red-50 text-red-700">
                                <AlertTriangle size={20} />
                            </div>
                            <div>
                                <h2 id="cancel-registration-title" className="text-lg font-semibold text-slate-900">Cancel your registration?</h2>
                                <p className="mt-1 text-sm leading-6 text-slate-600">
                                    This removes the event from your workspace immediately. Registering again may depend on remaining seats.
                                </p>
                            </div>
                        </div>
                        <form onSubmit={handleCancelEvent} className="flex flex-col-reverse gap-3 px-5 py-5 sm:flex-row sm:justify-end sm:px-6">
                            <button
                                type="button"
                                onClick={() => setShowCancelModal(false)}
                                className="border border-slate-300 bg-white px-4 py-2 text-sm font-semibold text-slate-700 hover:bg-slate-50"
                            >
                                Keep registration
                            </button>
                            <button
                                type="submit"
                                disabled={isProcessing}
                                className="bg-red-700 px-4 py-2 text-sm font-semibold text-white hover:bg-red-800 disabled:cursor-not-allowed disabled:opacity-50"
                            >
                                {isProcessing ? 'Cancelling...' : 'Confirm cancellation'}
                            </button>
                        </form>
                    </div>
                </div>
            )}
        </DashboardLayout>
    );
}

function NextStep({ event, canJoin }: { event: AttendeeWorkspaceEvent; canJoin: boolean }) {
    const content = event.journey_status === 'ongoing'
        ? {
            label: 'Happening now',
            title: canJoin ? 'Your session is ready to join' : 'The event is currently in progress',
            description: canJoin ? 'Use the meeting link to enter the live session.' : 'Check the access notes for the latest venue and joining instructions.',
        }
        : event.journey_status === 'ended'
            ? {
                label: 'Continue learning',
                title: 'Review the event materials',
                description: 'Use the resources below to revisit key ideas and continue your development.',
            }
            : {
                label: 'Next step',
                title: 'Prepare before the event begins',
                description: event.access_notes || 'Add the event to your calendar and return here for final access instructions.',
            };

    return (
        <section className="border-l-4 border-accent bg-white px-5 py-5 shadow-sm sm:px-6">
            <div>
                <p className="text-xs font-bold uppercase text-accent-700">{content.label}</p>
                <h2 className="mt-1 text-lg font-semibold text-slate-900">{content.title}</h2>
                <p className="mt-1 max-w-4xl text-sm leading-6 text-slate-600">{content.description}</p>
            </div>
        </section>
    );
}

function Commitments({ profile }: { profile: AttendeeEventProgramProfile }) {
    const commitments = [
        profile.cohort_duration_weeks ? ['Duration', `${profile.cohort_duration_weeks} weeks`] : null,
        profile.central_teaching_schedule ? ['Central teaching', profile.central_teaching_schedule] : null,
        profile.group_meeting_schedule ? ['Group meetings', profile.group_meeting_schedule] : null,
        profile.group_model ? ['Group model', profile.group_model] : null,
        profile.weekly_prayer_target_minutes ? ['Prayer', formatMinutes(profile.weekly_prayer_target_minutes)] : null,
        profile.weekly_evangelism_target_min ? ['Evangelism', `${formatRange(profile.weekly_evangelism_target_min, profile.weekly_evangelism_target_max)} people weekly`] : null,
        profile.weekly_discipleship_target_min ? ['Multiplication', `${formatRange(profile.weekly_discipleship_target_min, profile.weekly_discipleship_target_max)} disciples`] : null,
    ].filter((item): item is string[] => item !== null);

    return (
        <WorkspaceSection eyebrow={profile.program_code || 'Formation'} title="Track commitments">
            <div className="grid gap-x-8 gap-y-5 sm:grid-cols-2">
                {commitments.map(([label, value]) => (
                    <div key={label} className="border-l-2 border-accent pl-3">
                        <p className="text-xs font-semibold uppercase text-slate-400">{label}</p>
                        <p className="mt-1 text-sm leading-6 text-slate-700">{value}</p>
                    </div>
                ))}
            </div>
        </WorkspaceSection>
    );
}

function HeaderFact({ icon, label, value }: { icon: ReactNode; label: string; value: string }) {
    return (
        <div className="flex min-w-0 gap-3 px-4 py-4 sm:px-5">
            <span className="mt-0.5 shrink-0 text-primary">{icon}</span>
            <div className="min-w-0">
                <p className="text-xs font-semibold uppercase text-slate-400">{label}</p>
                <p className="mt-1 break-words text-sm font-semibold text-slate-800">{value}</p>
            </div>
        </div>
    );
}

function WorkspaceSection({ eyebrow, title, children }: { eyebrow: string; title: string; children: ReactNode }) {
    return (
        <section className="border border-slate-200 bg-white px-5 py-5 sm:px-6 sm:py-6">
            <div className="mb-5 border-b border-slate-200 pb-4">
                <p className="text-xs font-semibold uppercase text-slate-400">{eyebrow}</p>
                <h2 className="mt-1 text-lg font-semibold text-slate-900">{title}</h2>
            </div>
            {children}
        </section>
    );
}

function InfoRow({ icon, label, value, href }: { icon: ReactNode; label: string; value: string; href?: string }) {
    const content = href ? (
        <a href={href} className="break-words text-sm font-medium text-primary hover:underline">{value}</a>
    ) : (
        <p className="break-words text-sm font-medium leading-5 text-slate-800">{value}</p>
    );

    return (
        <div className="flex gap-3 py-3 first:pt-0 last:pb-0">
            <span className="mt-0.5 shrink-0 text-slate-400">{icon}</span>
            <div className="min-w-0">
                <p className="text-xs font-semibold uppercase text-slate-400">{label}</p>
                <div className="mt-1">{content}</div>
            </div>
        </div>
    );
}

function ResourceRow({ resource }: { resource: AttendeeEventResource }) {
    const href = resource.type === 'file' && resource.file_path
        ? assetUrl(resource.file_path)
        : resource.external_link;

    if (!href) return null;

    const action = resource.type === 'file' ? 'Download' : 'Open';

    return (
        <a href={href} target="_blank" rel="noreferrer" className="group flex items-start gap-3 py-4">
            <span className="flex h-9 w-9 shrink-0 items-center justify-center bg-slate-100 text-primary">
                {resource.type === 'file' ? <FileText size={18} /> : <ExternalLink size={18} />}
            </span>
            <div className="min-w-0 flex-1">
                <p className="text-sm font-semibold text-slate-900 group-hover:text-primary">{resource.title}</p>
                {resource.description && <p className="mt-1 text-sm leading-5 text-slate-500">{resource.description}</p>}
            </div>
            <span className="inline-flex shrink-0 items-center gap-1 text-sm font-semibold text-primary">
                {action}
                {resource.type === 'file' ? <Download size={15} /> : <ArrowUpRight size={15} />}
            </span>
        </a>
    );
}

function EmptyState({ icon, title, description }: { icon: ReactNode; title: string; description: string }) {
    return (
        <div className="flex items-start gap-3 bg-slate-50 px-4 py-4">
            <span className="text-slate-400">{icon}</span>
            <div>
                <p className="text-sm font-semibold text-slate-800">{title}</p>
                <p className="mt-1 text-sm leading-5 text-slate-500">{description}</p>
            </div>
        </div>
    );
}

function formatDate(value: string): string {
    return new Intl.DateTimeFormat('en-NG', {
        weekday: 'short',
        day: 'numeric',
        month: 'short',
        year: 'numeric',
    }).format(new Date(value));
}

function formatTimeRange(start: string, end: string): string {
    const formatter = new Intl.DateTimeFormat('en-NG', { hour: 'numeric', minute: '2-digit' });
    return `${formatter.format(new Date(start))} – ${formatter.format(new Date(end))}`;
}

function formatMinutes(minutes: number): string {
    const hours = Math.floor(minutes / 60);
    const remainder = minutes % 60;
    return `${hours ? `${hours}h` : ''}${hours && remainder ? ' ' : ''}${remainder ? `${remainder}m` : ''} weekly`;
}

function formatRange(minimum: number, maximum?: number | null): string {
    return maximum && maximum !== minimum ? `${minimum}–${maximum}` : minimum.toString();
}

function assetUrl(path?: string | null, fallback = ''): string {
    if (!path) return fallback;
    if (/^https?:\/\//i.test(path)) return path;
    return `/storage/${path.replace(/^\//, '')}`;
}

function initials(name: string): string {
    return name.split(' ').filter(Boolean).slice(0, 2).map((part) => part.charAt(0)).join('').toUpperCase();
}

function toTitleCase(value: string): string {
    return value.replaceAll('_', ' ').replace(/\b\w/g, (letter) => letter.toUpperCase());
}
