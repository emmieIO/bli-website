import { Head, Link, usePage } from '@inertiajs/react';
import DashboardLayout from '@/Layouts/DashboardLayout';

interface Event {
    id: number;
    slug: string;
    title: string;
    description?: string | null;
    start_date: string;
    end_date: string;
    mode?: string | null;
    physical_address?: string | null;
    location?: string | null;
    contact_email?: string | null;
}

interface Speaker {
    id: number;
    title?: string | null;
    organization?: string | null;
    bio?: string | null;
}

interface Application {
    id: number;
    topic_title?: string | null;
    topic_description?: string | null;
    session_format?: string | null;
    notes?: string | null;
    status: string;
    feedback?: string | null;
    reviewed_at?: string | null;
    speaker?: Speaker | null;
}

interface Invite {
    id: number;
    status: string;
    expires_at?: string | null;
    responded_at?: string | null;
    suggested_topic?: string | null;
    suggested_duration?: number | null;
    audience_expectations?: string | null;
    expected_format?: string | null;
    special_instructions?: string | null;
    admin_feedback?: string | null;
    user_feedback?: string | null;
}

interface Props {
    event: Event;
    application?: Application | null;
    invite?: Invite | null;
    stage: 'invited' | 'applied' | 'under_review' | 'approved' | 'rejected' | 'withdrawn';
    workspaceApplyUrl: string;
}

const stageTone: Record<Props['stage'], string> = {
    invited: 'bg-blue-100 text-blue-700',
    applied: 'bg-primary/10 text-primary',
    under_review: 'bg-amber-100 text-amber-700',
    approved: 'bg-emerald-100 text-emerald-700',
    rejected: 'bg-rose-100 text-rose-700',
    withdrawn: 'bg-slate-100 text-slate-600',
};

export default function SpeakerWorkspace({ event, application, invite, stage, workspaceApplyUrl }: Props) {
    const { sideLinks } = usePage().props as any;

    const stageOrder: Array<Props['stage']> = ['invited', 'applied', 'under_review', 'approved'];

    const stageCopy: Record<Props['stage'], string> = {
        invited: 'You are in the speaker invitation stage for this event. Review the invitation details and start your proposal when you are ready.',
        applied: 'Your invitation response is recorded. Complete your speaker application so the organizer can review your proposal.',
        under_review: 'Your speaking proposal is in review. We will update you here once the organizer makes a decision.',
        approved: 'You are approved to speak at this event. This workspace is now your home for speaking logistics and submission details.',
        rejected: 'Your proposal was reviewed and not selected. Feedback is available below.',
        withdrawn: 'Your speaker journey for this event has been closed. This workspace remains available for reference.',
    };

    const formatDate = (value: string) => new Intl.DateTimeFormat('en-US', {
        weekday: 'long',
        month: 'long',
        day: 'numeric',
        year: 'numeric',
        hour: 'numeric',
        minute: '2-digit',
    }).format(new Date(value));

    const formatShortDate = (value?: string | null) => {
        if (!value) {
            return 'Not available yet';
        }

        return new Intl.DateTimeFormat('en-US', {
            month: 'short',
            day: 'numeric',
            year: 'numeric',
        }).format(new Date(value));
    };

    return (
        <DashboardLayout sideLinks={sideLinks}>
            <Head title={`${event.title} Speaker Workspace`} />

            <div className="space-y-8">
                <section className="rounded-lg border border-gray-200 bg-white p-6 shadow-sm lg:p-8">
                    <div className="flex flex-col gap-5 lg:flex-row lg:items-start lg:justify-between">
                        <div className="space-y-4">
                            <div className="flex flex-wrap items-center gap-2">
                                <span className={`inline-flex rounded-full px-2.5 py-1 text-xs font-semibold capitalize ${stageTone[stage]}`}>
                                    {stage.replaceAll('_', ' ')}
                                </span>
                                <span className="inline-flex rounded-full bg-slate-100 px-2.5 py-1 text-xs font-semibold capitalize text-slate-600">
                                    speaker workspace
                                </span>
                            </div>
                            <div>
                                <h1 className="text-3xl font-bold text-primary md:text-4xl">{event.title}</h1>
                                <p className="mt-3 max-w-3xl text-sm leading-6 text-gray-600 md:text-base">
                                    {stageCopy[stage]}
                                </p>
                            </div>
                        </div>

                        {(stage === 'invited' || stage === 'applied') && (
                            <Link href={workspaceApplyUrl} className="enterprise-button enterprise-button-primary">
                                Continue application
                            </Link>
                        )}
                    </div>
                </section>

                <section className="grid gap-6 lg:grid-cols-[minmax(0,1.35fr)_minmax(320px,0.85fr)]">
                    <div className="space-y-6">
                        <div className="rounded-lg border border-gray-200 bg-white p-6 shadow-sm">
                            <h2 className="text-lg font-semibold text-primary">Speaker journey</h2>
                            <div className="mt-5 grid gap-3">
                                {stageOrder.map((step) => {
                                    const active = step === stage;
                                    const completed = stageOrder.indexOf(step) < stageOrder.indexOf(stage) || (stage === 'approved' && step === 'approved');

                                    return (
                                        <div
                                            key={step}
                                            className={`flex items-start gap-3 rounded-lg border px-4 py-3 ${
                                                active
                                                    ? 'border-primary/20 bg-primary/5'
                                                    : completed
                                                        ? 'border-emerald-200 bg-emerald-50'
                                                        : 'border-gray-200 bg-gray-50'
                                            }`}
                                        >
                                            <div
                                                className={`mt-0.5 flex h-6 w-6 items-center justify-center rounded-full text-xs font-bold ${
                                                    active
                                                        ? 'bg-primary text-white'
                                                        : completed
                                                            ? 'bg-emerald-600 text-white'
                                                            : 'bg-gray-200 text-gray-500'
                                                }`}
                                            >
                                                {completed ? <i className="fas fa-check"></i> : stageOrder.indexOf(step) + 1}
                                            </div>
                                            <div>
                                                <p className="text-sm font-semibold capitalize text-primary">{step.replaceAll('_', ' ')}</p>
                                                <p className="mt-1 text-sm text-gray-600">
                                                    {step === 'invited' && 'Invitation issued by the organizer.'}
                                                    {step === 'applied' && 'Speaker is preparing or has started the proposal stage.'}
                                                    {step === 'under_review' && 'Organizer is reviewing the application and session fit.'}
                                                    {step === 'approved' && 'Speaker is confirmed for the event program.'}
                                                </p>
                                            </div>
                                        </div>
                                    );
                                })}

                                {(stage === 'rejected' || stage === 'withdrawn') && (
                                    <div className="flex items-start gap-3 rounded-lg border border-rose-200 bg-rose-50 px-4 py-3">
                                        <div className="mt-0.5 flex h-6 w-6 items-center justify-center rounded-full bg-rose-600 text-xs font-bold text-white">
                                            <i className="fas fa-times"></i>
                                        </div>
                                        <div>
                                            <p className="text-sm font-semibold capitalize text-primary">{stage.replaceAll('_', ' ')}</p>
                                            <p className="mt-1 text-sm text-gray-600">
                                                {stage === 'rejected'
                                                    ? 'The proposal was reviewed and not selected for this event.'
                                                    : 'The invitation or application was withdrawn and the workspace is now read-only.'}
                                            </p>
                                        </div>
                                    </div>
                                )}
                            </div>
                        </div>

                        <div className="rounded-lg border border-gray-200 bg-white p-6 shadow-sm">
                            <h2 className="text-lg font-semibold text-primary">Event brief</h2>
                            <div className="mt-5 grid gap-4 sm:grid-cols-2">
                                <DetailCard label="Start" value={formatDate(event.start_date)} />
                                <DetailCard label="End" value={formatDate(event.end_date)} />
                                <DetailCard label="Format" value={event.mode ? event.mode.charAt(0).toUpperCase() + event.mode.slice(1) : 'Hybrid'} />
                                <DetailCard label="Contact" value={event.contact_email || 'Organizer'} />
                            </div>
                            <div className="mt-5 rounded-lg border border-gray-200 bg-gray-50 p-4">
                                <p className="text-sm leading-6 text-gray-700">
                                    {event.description || 'Full event details will be shared here as the organizer finalizes the speaking program.'}
                                </p>
                            </div>
                        </div>

                        {application && (
                            <div className="rounded-lg border border-gray-200 bg-white p-6 shadow-sm">
                                <h2 className="text-lg font-semibold text-primary">Proposal</h2>
                                <div className="mt-5 space-y-4">
                                    <WorkspaceRow label="Topic" value={application.topic_title || 'Not provided yet'} />
                                    <WorkspaceRow label="Session format" value={application.session_format || 'Not selected yet'} />
                                    <WorkspaceRow label="Description" value={application.topic_description || 'No topic description yet'} />
                                    <WorkspaceRow label="Notes" value={application.notes || 'No extra notes'} />
                                </div>
                            </div>
                        )}

                        {(application?.feedback || invite?.admin_feedback) && (
                            <div className="rounded-lg border border-gray-200 bg-white p-6 shadow-sm">
                                <h2 className="text-lg font-semibold text-primary">Organizer feedback</h2>
                                <div className="mt-5 rounded-lg border border-gray-200 bg-gray-50 p-4">
                                    <p className="text-sm leading-6 text-gray-700">
                                        {application?.feedback || invite?.admin_feedback}
                                    </p>
                                </div>
                            </div>
                        )}

                        <div className="rounded-lg border border-gray-200 bg-white p-6 shadow-sm">
                            <h2 className="text-lg font-semibold text-primary">Session package</h2>
                            <div className="mt-5 grid gap-4 sm:grid-cols-2">
                                <DetailCard
                                    label="Assigned slot"
                                    value={stage === 'approved' ? 'Organizer to confirm session timing' : 'Pending approval'}
                                />
                                <DetailCard
                                    label="Speaker assets"
                                    value="Deck and resource uploads are not enabled yet"
                                />
                                <DetailCard
                                    label="Reviewer decision"
                                    value={application?.reviewed_at ? formatShortDate(application.reviewed_at) : 'Awaiting review'}
                                />
                                <DetailCard
                                    label="Invitation response"
                                    value={invite?.responded_at ? formatShortDate(invite.responded_at) : 'Not responded yet'}
                                />
                            </div>
                            <div className="mt-5 rounded-lg border border-dashed border-gray-300 bg-gray-50 p-4 text-sm leading-6 text-gray-600">
                                This workspace is ready for speaker-specific logistics. The next backend step is adding upload support for decks, supporting links, and final session scheduling.
                            </div>
                        </div>
                    </div>

                    <aside className="space-y-6">
                        {invite && (
                            <div className="rounded-lg border border-gray-200 bg-white p-6 shadow-sm">
                                <h2 className="text-lg font-semibold text-primary">Invitation details</h2>
                                <div className="mt-5 space-y-4">
                                    <WorkspaceRow label="Status" value={invite.status} />
                                    <WorkspaceRow label="Suggested topic" value={invite.suggested_topic || 'No suggested topic'} />
                                    <WorkspaceRow label="Duration" value={invite.suggested_duration ? `${invite.suggested_duration} minutes` : 'Flexible'} />
                                    <WorkspaceRow label="Format" value={invite.expected_format || 'Open format'} />
                                    <WorkspaceRow label="Audience" value={invite.audience_expectations || 'Details to be shared'} />
                                    <WorkspaceRow label="Instructions" value={invite.special_instructions || 'No special instructions'} />
                                </div>
                            </div>
                        )}

                        <div className="rounded-lg border border-gray-200 bg-white p-6 shadow-sm">
                            <h2 className="text-lg font-semibold text-primary">Next steps</h2>
                            <div className="mt-5 space-y-3 text-sm leading-6 text-gray-700">
                                {stage === 'invited' && <p>Review the invitation, then accept or decline it from your invitation details page.</p>}
                                {stage === 'applied' && <p>Complete your speaker application so the organizer can review your topic and profile.</p>}
                                {stage === 'under_review' && <p>Hold tight while the organizer reviews your proposal. Check this workspace for feedback and status changes.</p>}
                                {stage === 'approved' && <p>Use this workspace as the reference point for session logistics, updates, and final organizer communication.</p>}
                                {stage === 'rejected' && <p>Review the feedback and decide whether you want to refine your proposal for a future event.</p>}
                                {stage === 'withdrawn' && <p>Your response has been recorded. No further action is required unless the organizer reaches out again.</p>}
                            </div>
                        </div>

                        <div className="rounded-lg border border-gray-200 bg-white p-6 shadow-sm">
                            <h2 className="text-lg font-semibold text-primary">Organizer packet</h2>
                            <div className="mt-5 space-y-4">
                                <WorkspaceRow label="Invitation expiry" value={formatShortDate(invite?.expires_at)} />
                                <WorkspaceRow label="Response date" value={formatShortDate(invite?.responded_at)} />
                                <WorkspaceRow label="Venue notes" value={event.physical_address || event.location || 'Organizer will share venue details later'} />
                                <WorkspaceRow label="Special instructions" value={invite?.special_instructions || 'No special instructions yet'} />
                            </div>
                        </div>
                    </aside>
                </section>
            </div>
        </DashboardLayout>
    );
}

function DetailCard({ label, value }: { label: string; value: string }) {
    return (
        <div className="rounded-lg border border-gray-200 bg-gray-50 px-4 py-3">
            <p className="text-[11px] font-semibold uppercase tracking-[0.16em] text-gray-500">{label}</p>
            <p className="mt-2 text-sm font-medium text-primary">{value}</p>
        </div>
    );
}

function WorkspaceRow({ label, value }: { label: string; value: string }) {
    return (
        <div className="grid gap-2 border-b border-gray-100 pb-4 last:border-b-0 last:pb-0 sm:grid-cols-[140px_minmax(0,1fr)]">
            <p className="text-sm font-medium text-gray-500">{label}</p>
            <p className="text-sm leading-6 text-primary">{value}</p>
        </div>
    );
}
