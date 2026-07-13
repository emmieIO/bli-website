import { Head, Link, router, usePage } from '@inertiajs/react';
import DashboardLayout from '@/Layouts/DashboardLayout';
import { useMemo, useState } from 'react';
import type { ReactNode } from 'react';

interface Speaker {
  id: number;
  organization?: string | null;
  user: {
    id: number;
    name: string;
    email: string;
  };
}

interface Resource {
  id: number;
  title: string;
  description?: string | null;
  file_path?: string | null;
  external_link?: string | null;
  type: string;
  is_downloadable: boolean;
}

interface Attendee {
  id: number;
  name: string;
  email: string;
  pivot: {
    status: string;
    created_at: string;
  };
}

interface SpeakerApplication {
  id: number;
  topic_title: string;
  status: string;
  created_at: string;
  user?: {
    id: number;
    name: string;
    email: string;
  } | null;
  speaker?: {
    id: number;
    user?: {
      id: number;
      name: string;
      email: string;
    } | null;
  } | null;
}

interface Transaction {
  id: number;
  amount: string;
  currency: string;
  status: string;
  created_at: string;
  paid_at?: string | null;
  payment_ref?: string | null;
  transaction_id?: string | null;
  user?: {
    id: number;
    name: string;
    email: string;
  } | null;
}

interface Event {
  id: number;
  title: string;
  slug: string;
  description: string;
  theme: string;
  status: 'draft' | 'review' | 'published' | 'registration_open' | 'registration_closed' | 'live' | 'completed' | 'cancelled' | 'archived';
  mode: 'online' | 'offline' | 'hybrid';
  location?: string | null;
  physical_address?: string | null;
  attendee_slots?: number | null;
  start_date: string;
  end_date: string;
  contact_email?: string | null;
  program_cover?: string | null;
  entry_fee: string;
  is_allowing_application: boolean;
  is_featured: boolean;
  speakers: Speaker[];
  resources: Resource[];
  attendees: Attendee[];
  speaker_applications: SpeakerApplication[];
  transactions: Transaction[];
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

interface ViewEventProps {
  event: Event;
  capabilities: {
    canUpdate: boolean;
    canDelete: boolean;
    canManageSpeakers: boolean;
    canManageAttendees: boolean;
    canManageResources: boolean;
    canViewPayments: boolean;
  };
}

const statusStyles: Record<Event['status'], string> = {
  draft: 'bg-slate-100 text-slate-700',
  review: 'bg-violet-100 text-violet-700',
  published: 'bg-blue-100 text-blue-700',
  registration_open: 'bg-green-100 text-green-700',
  registration_closed: 'bg-amber-100 text-amber-700',
  live: 'bg-red-100 text-red-700',
  completed: 'bg-emerald-100 text-emerald-700',
  cancelled: 'bg-rose-100 text-rose-700',
  archived: 'bg-zinc-100 text-zinc-700',
};

const statusLabels: Record<Event['status'], string> = {
  draft: 'Draft',
  review: 'Review',
  published: 'Published',
  registration_open: 'Registration Open',
  registration_closed: 'Registration Closed',
  live: 'Live',
  completed: 'Completed',
  cancelled: 'Cancelled',
  archived: 'Archived',
};

export default function ViewEvent({ event, capabilities }: ViewEventProps) {
  const { sideLinks } = usePage().props as any;
  const [showDeleteModal, setShowDeleteModal] = useState(false);
  const [isDeleting, setIsDeleting] = useState(false);
  const [activeTab, setActiveTab] = useState<'overview' | 'speakers' | 'registrations' | 'resources' | 'payments'>('overview');

  const registrationStats = useMemo(() => {
    const registered = event.attendees.filter((attendee) => attendee.pivot.status === 'registered').length;
    const cancelled = event.attendees.filter((attendee) => attendee.pivot.status === 'cancelled').length;
    return { registered, cancelled };
  }, [event.attendees]);
  const registrationLabel = (status: string) => status === 'registered' ? 'confirmed' : status.replace('_', ' ');

  const successfulPayments = event.transactions.filter((transaction) => transaction.status === 'successful');
  const pendingPayments = event.transactions.filter((transaction) => transaction.status === 'pending');
  const revenue = successfulPayments.reduce((total, transaction) => total + Number(transaction.amount || 0), 0);
  const pendingAmount = pendingPayments.reduce((total, transaction) => total + Number(transaction.amount || 0), 0);
  const tabs = [
    ['overview', 'Overview'],
    ['speakers', 'Speakers'],
    ['registrations', 'Registrations'],
    ['resources', 'Resources'],
    ...(capabilities.canViewPayments ? [['payments', 'Payments']] : []),
  ] as const;
  const programProfile = event.program_profile;
  const prayerTargetLabel = programProfile?.weekly_prayer_target_minutes
    ? `${Math.floor(programProfile.weekly_prayer_target_minutes / 60)}h ${programProfile.weekly_prayer_target_minutes % 60}m weekly`
    : 'Not set';
  const evangelismTargetLabel = programProfile?.weekly_evangelism_target_min
    ? `${programProfile.weekly_evangelism_target_min}-${programProfile.weekly_evangelism_target_max ?? programProfile.weekly_evangelism_target_min} weekly`
    : 'Not set';
  const discipleshipTargetLabel = programProfile?.weekly_discipleship_target_min
    ? `${programProfile.weekly_discipleship_target_min}-${programProfile.weekly_discipleship_target_max ?? programProfile.weekly_discipleship_target_min} weekly`
    : 'Not set';

  const handleDelete = () => {
    setIsDeleting(true);
    router.delete(route('admin.events.destroy', event.slug), {
      onFinish: () => {
        setIsDeleting(false);
        setShowDeleteModal(false);
      },
    });
  };

  return (
    <DashboardLayout sideLinks={sideLinks}>
      <Head title={`${event.title} - Event Workspace`} />

      <div className="space-y-5 sm:space-y-6">
        <div className="flex flex-col gap-5 xl:flex-row xl:items-start xl:justify-between">
          <div className="min-w-0 space-y-3">
            <Link href={route('admin.events.index')} className="inline-flex items-center gap-2 text-sm font-medium text-slate-500 hover:text-primary">
              <i className="fas fa-arrow-left w-4 h-4"></i>
              Back to events
            </Link>
            <div className="space-y-2">
              <div className="flex flex-wrap items-center gap-2">
                <span className={`inline-flex rounded-full px-2.5 py-1 text-xs font-semibold ${statusStyles[event.status]}`}>
                  {statusLabels[event.status]}
                </span>
                {event.is_featured && (
                  <span className="inline-flex rounded-full bg-amber-100 px-2.5 py-1 text-xs font-semibold text-amber-700">
                    Featured
                  </span>
                )}
                {event.is_allowing_application && (
                  <span className="inline-flex rounded-full bg-primary-100 px-2.5 py-1 text-xs font-semibold text-primary">
                    Speaker applications enabled
                  </span>
                )}
              </div>
              <div>
                <h1 className="wrap-break-word text-2xl font-bold leading-tight text-primary sm:text-3xl">{event.title}</h1>
                <p className="wrap-break-word text-sm leading-6 text-slate-600">{event.theme}</p>
              </div>
            </div>
          </div>

          <div className="grid grid-cols-2 gap-2 sm:flex sm:flex-wrap sm:gap-3">
            {capabilities.canUpdate && (
              <Link
                href={route('admin.events.edit', event.slug)}
                className="inline-flex min-w-0 items-center justify-center gap-2 rounded-md border border-slate-300 bg-white px-3 py-2.5 text-sm font-semibold text-slate-700 transition hover:bg-slate-50 sm:px-4"
              >
                <i className="fas fa-pen w-4 h-4"></i>
                Edit Event
              </Link>
            )}
            {capabilities.canManageResources && (
              <Link
                href={route('admin.events.resources.create', event.slug)}
                className="inline-flex min-w-0 items-center justify-center gap-2 rounded-md border border-primary bg-white px-3 py-2.5 text-sm font-semibold text-primary transition hover:bg-primary-50 sm:px-4"
              >
                <i className="fas fa-folder-plus w-4 h-4"></i>
                Add Resource
              </Link>
            )}
            {capabilities.canDelete && (
              <button
                type="button"
                onClick={() => setShowDeleteModal(true)}
                className="col-span-2 inline-flex min-w-0 items-center justify-center gap-2 rounded-md bg-red-600 px-3 py-2.5 text-sm font-semibold text-white transition hover:bg-red-700 sm:col-span-1 sm:px-4"
              >
                <i className="fas fa-trash w-4 h-4"></i>
                Delete
              </button>
            )}
          </div>
        </div>

        <div className="grid grid-cols-2 gap-3 sm:gap-4 xl:grid-cols-4">
          <MetricCard label="Confirmed" value={registrationStats.registered} hint={`${registrationStats.cancelled} cancelled`} />
          <MetricCard label="Speakers" value={event.speakers.length} hint={`${event.speaker_applications.length} applications`} />
          <MetricCard label={capabilities.canViewPayments ? 'Paid Orders' : 'Registrations'} value={capabilities.canViewPayments ? successfulPayments.length : registrationStats.registered} hint={capabilities.canViewPayments ? (event.entry_fee === '0.00' || Number(event.entry_fee) === 0 ? 'Free event' : `₦${revenue.toLocaleString()}`) : 'Confirmed attendees'} />
          <MetricCard label="Capacity" value={event.attendee_slots ?? 0} hint={event.attendee_slots ? 'Configured seats' : 'Unlimited'} />
        </div>

        <div className="-mx-4 overflow-x-auto border-y border-slate-200 bg-white px-4 sm:mx-0 sm:rounded-lg sm:border sm:p-2">
          <div className="flex w-max min-w-full gap-1 py-2 sm:py-0">
            {tabs.map(([key, label]) => (
            <button
              key={key}
              type="button"
              onClick={() => setActiveTab(key as typeof activeTab)}
              className={`shrink-0 rounded-md px-3 py-2 text-sm font-medium transition ${
                activeTab === key ? 'bg-primary text-white' : 'text-slate-600 hover:bg-slate-100'
              }`}
            >
              {label}
            </button>
            ))}
          </div>
        </div>

        <div className="grid gap-6 xl:grid-cols-[1.4fr_0.9fr]">
          <div className="space-y-6">
            {activeTab === 'overview' && (
              <>
                <WorkspacePanel title="Event Brief">
                  {event.program_cover && (
                    <div className="mb-5 aspect-video overflow-hidden rounded-md border border-slate-200 sm:aspect-2/1">
                      <img src={`/storage/${event.program_cover}`} alt={event.title} className="h-full w-full object-cover" />
                    </div>
                  )}
                  <div
                    className="rich-content"
                    dangerouslySetInnerHTML={{ __html: event.description }}
                  />
                </WorkspacePanel>

                <WorkspacePanel title="Operations Snapshot">
                  <dl className="grid gap-4 sm:grid-cols-2">
                    <MetaItem label="Mode" value={event.mode} />
                    <MetaItem label="Entry fee" value={Number(event.entry_fee) > 0 ? `₦${Number(event.entry_fee).toLocaleString()}` : 'Free'} />
                    <MetaItem label="Starts" value={formatDate(event.start_date)} />
                    <MetaItem label="Ends" value={formatDate(event.end_date)} />
                    <MetaItem label="Meeting link" value={event.location || 'Not set'} />
                    <MetaItem label="Physical address" value={event.physical_address || 'Not set'} />
                    <MetaItem label="Contact email" value={event.contact_email || 'Not set'} />
                    <MetaItem label="Capacity" value={event.attendee_slots ? event.attendee_slots.toString() : 'Unlimited'} />
                  </dl>
                </WorkspacePanel>

                {programProfile && (
                  <WorkspacePanel title="Program Readiness Snapshot">
                    <dl className="grid gap-4 sm:grid-cols-2">
                      <MetaItem label="Program type" value={programProfile.program_type === 'discipleship_track' ? 'Discipleship Track' : 'General Event'} />
                      <MetaItem label="Program code" value={programProfile.program_code || 'Not set'} />
                      <MetaItem label="Admission" value={programProfile.registration_mode === 'selective' || programProfile.requires_screening ? 'Selective screening' : 'Open registration'} />
                      <MetaItem label="Duration" value={programProfile.cohort_duration_weeks ? `${programProfile.cohort_duration_weeks} weeks` : 'Not set'} />
                      <MetaItem label="Central teaching" value={programProfile.central_teaching_schedule || 'Not set'} />
                      <MetaItem label="Group meetings" value={programProfile.group_meeting_schedule || 'Not set'} />
                      <MetaItem label="Group model" value={programProfile.group_model || 'Not set'} />
                      <MetaItem label="Prayer target" value={prayerTargetLabel} />
                      <MetaItem label="Evangelism target" value={evangelismTargetLabel} />
                      <MetaItem label="Multiplication target" value={discipleshipTargetLabel} />
                      <MetaItem label="Meeting link" value={programProfile.meeting_link ? 'Configured' : 'Not set'} />
                      <MetaItem label="Access notes" value={programProfile.access_notes ? 'Configured' : 'Not set'} />
                    </dl>

                    {programProfile.screening_note && (
                      <div className="mt-5 rounded-lg border border-amber-200 bg-amber-50 p-4 text-sm leading-6 text-amber-900">
                        <span className="font-semibold">Screening note:</span> {programProfile.screening_note}
                      </div>
                    )}
                  </WorkspacePanel>
                )}
              </>
            )}

            {activeTab === 'speakers' && (
              <WorkspacePanel title={`Speakers (${event.speakers.length})`}>
                <div className="space-y-3">
                  {event.speakers.length > 0 ? (
                    event.speakers.map((speaker) => (
                      <div key={speaker.id} className="flex flex-col gap-2 rounded-md border border-slate-200 px-3 py-3 sm:flex-row sm:items-center sm:justify-between sm:px-4">
                        <div className="min-w-0">
                          <p className="text-sm font-semibold text-primary">{speaker.user.name}</p>
                          <p className="break-all text-xs text-slate-500">{speaker.user.email}</p>
                        </div>
                        <p className="text-xs text-slate-500">{speaker.organization || 'Independent speaker'}</p>
                      </div>
                    ))
                  ) : (
                    <EmptyText text="No speakers assigned yet." />
                  )}
                </div>

                <div className="mt-6 border-t border-slate-200 pt-6">
                  <div className="mb-3 flex items-center justify-between">
                    <h3 className="text-sm font-semibold text-slate-900">Applications</h3>
                    <span className="text-xs text-slate-500">{event.speaker_applications.length} total</span>
                  </div>
                  <div className="space-y-3">
                    {event.speaker_applications.length > 0 ? (
                      event.speaker_applications.map((application) => (
                        <div key={application.id} className="rounded-lg border border-slate-200 px-4 py-3">
                          <div className="flex flex-wrap items-center justify-between gap-2">
                            <div className="min-w-0">
                              <p className="wrap-break-word text-sm font-semibold text-primary">{application.topic_title}</p>
                              <p className="text-xs text-slate-500">
                                {application.user?.name || application.speaker?.user?.name || 'Applicant'}
                              </p>
                            </div>
                            <span className="rounded-full bg-slate-100 px-2.5 py-1 text-xs font-semibold capitalize text-slate-700">
                              {application.status}
                            </span>
                          </div>
                        </div>
                      ))
                    ) : (
                      <EmptyText text="No speaker applications yet." />
                    )}
                  </div>
                </div>
              </WorkspacePanel>
            )}

            {activeTab === 'registrations' && (
              <WorkspacePanel title={`Registrations (${event.attendees.length})`}>
                <div className="space-y-3">
                  {event.attendees.length > 0 ? (
                    event.attendees.map((attendee) => (
                      <div key={attendee.id} className="flex flex-col gap-3 rounded-md border border-slate-200 px-3 py-3 sm:flex-row sm:items-center sm:justify-between sm:px-4">
                        <div className="min-w-0">
                          <p className="text-sm font-semibold text-primary">{attendee.name}</p>
                          <p className="break-all text-xs text-slate-500">{attendee.email}</p>
                        </div>
                        <div className="flex items-center justify-between gap-3 sm:justify-end">
                          <div className="text-right">
                          <span className={`inline-flex rounded-full px-2.5 py-1 text-xs font-semibold capitalize ${
                            attendee.pivot.status === 'registered'
                              ? 'bg-green-100 text-green-700'
                              : 'bg-rose-100 text-rose-700'
                          }`}>
                            {registrationLabel(attendee.pivot.status)}
                          </span>
                          <p className="mt-1 text-xs text-slate-500">{formatShortDate(attendee.pivot.created_at)}</p>
                          </div>
                        </div>
                      </div>
                    ))
                  ) : (
                    <EmptyText text="No attendees yet." />
                  )}
                </div>
              </WorkspacePanel>
            )}

            {activeTab === 'resources' && (
              <WorkspacePanel title={`Resources (${event.resources.length})`}>
                <div className="space-y-3">
                  {event.resources.length > 0 ? (
                    event.resources.map((resource) => (
                      <div key={resource.id} className="flex flex-col gap-3 rounded-md border border-slate-200 px-3 py-3 sm:flex-row sm:items-center sm:justify-between sm:px-4">
                        <div className="min-w-0">
                          <p className="text-sm font-semibold text-primary">{resource.title}</p>
                          <p className="text-xs text-slate-500">
                            {resource.description || resource.type}
                          </p>
                        </div>
                        <div className="flex items-center gap-4 text-sm">
                          {resource.external_link ? (
                            <a href={resource.external_link} target="_blank" rel="noreferrer" className="font-medium text-primary hover:text-primary-600">
                              Open
                            </a>
                          ) : resource.file_path ? (
                            <a href={`/storage/${resource.file_path}`} target="_blank" rel="noreferrer" className="font-medium text-primary hover:text-primary-600">
                              Download
                            </a>
                          ) : null}
                          {capabilities.canManageResources && (
                            <button
                              onClick={() => {
                                if (confirm('Delete this resource?')) {
                                  router.delete(route('admin.events.resources.destroy', [event.slug, resource.id]));
                                }
                              }}
                              className="font-medium text-red-600 hover:text-red-700"
                            >
                              Delete
                            </button>
                          )}
                        </div>
                      </div>
                    ))
                  ) : (
                    <EmptyText text="No resources uploaded yet." />
                  )}
                </div>
              </WorkspacePanel>
            )}

            {activeTab === 'payments' && (
              <WorkspacePanel title={`Payments (${event.transactions.length})`}>
                <div className="space-y-6">
                  <div className="rounded-lg border border-slate-200 bg-slate-50/70 p-5">
                    <div className="flex flex-col gap-4 xl:flex-row xl:items-start xl:justify-between">
                      <div className="max-w-2xl">
                        <p className="text-[11px] font-semibold uppercase tracking-[0.18em] text-slate-500">Payment Summary</p>
                        <p className="mt-2 text-sm leading-6 text-slate-600">
                          This view is for quick operational review: what has been collected and what is still pending.
                        </p>
                      </div>
                      <div className="text-sm text-slate-600">
                        <span className="font-semibold text-slate-900">{event.transactions.length}</span> transaction records
                      </div>
                    </div>

                    <div className="mt-5 space-y-3">
                      <SummaryStrip
                        label="Successful payments"
                        primary={`${successfulPayments.length} records`}
                        secondary={`₦${revenue.toLocaleString()} collected`}
                        tone="emerald"
                      />
                      <SummaryStrip
                        label="Pending payments"
                        primary={`${pendingPayments.length} records`}
                        secondary={`₦${pendingAmount.toLocaleString()} awaiting verification`}
                        tone="amber"
                      />
                    </div>
                  </div>

                  <div className="space-y-3 border-t border-slate-200 pt-6">
                  <div className="flex items-center justify-between gap-3">
                    <div>
                      <h3 className="text-sm font-semibold text-slate-900">Transaction ledger</h3>
                      <p className="mt-1 text-xs text-slate-500">
                        Latest payment activity for this event, including pending checkouts.
                      </p>
                    </div>
                    <span className="rounded-full bg-slate-100 px-3 py-1 text-xs font-semibold text-slate-600">
                      {event.transactions.length} records
                    </span>
                  </div>
                  {event.transactions.length > 0 ? (
                    event.transactions.map((transaction) => (
                      <div key={transaction.id} className="rounded-lg border border-slate-200 bg-white p-4">
                        <div className="flex flex-col gap-4 lg:flex-row lg:items-start lg:justify-between">
                          <div className="space-y-2">
                            <p className="text-sm font-semibold text-primary">
                              {transaction.user?.name || 'User'} • {transaction.currency} {Number(transaction.amount).toLocaleString()}
                            </p>
                            <p className="text-xs text-slate-500">{transaction.user?.email || 'No email available'}</p>
                            <div className="flex flex-wrap gap-2">
                              {transaction.payment_ref && (
                              <span className="max-w-full break-all rounded-md bg-slate-100 px-2.5 py-1 text-xs font-semibold text-slate-600">
                                  Ref {transaction.payment_ref}
                                </span>
                              )}
                              {transaction.transaction_id && (
                              <span className="max-w-full break-all rounded-md bg-slate-100 px-2.5 py-1 text-xs font-semibold text-slate-600">
                                  Txn {transaction.transaction_id}
                                </span>
                              )}
                            </div>
                          </div>
                          <div className="space-y-2 text-left lg:text-right">
                            <span className={`inline-flex rounded-full px-2.5 py-1 text-xs font-semibold capitalize ${
                              transaction.status === 'successful'
                                ? 'bg-green-100 text-green-700'
                                : transaction.status === 'pending'
                                  ? 'bg-amber-100 text-amber-700'
                                  : 'bg-slate-100 text-slate-700'
                            }`}>
                              {transaction.status}
                            </span>
                            <p className="text-xs text-slate-500">
                              Created {formatShortDate(transaction.created_at)}
                            </p>
                            <p className="text-xs text-slate-500">
                              {transaction.paid_at ? `Paid ${formatShortDate(transaction.paid_at)}` : 'Not paid yet'}
                            </p>
                          </div>
                        </div>
                      </div>
                    ))
                  ) : (
                    <EmptyText text="No payments recorded for this event yet." />
                  )}
                  </div>
                </div>
              </WorkspacePanel>
            )}
          </div>

          <div className="flex flex-col gap-6">
            <div className="order-2 xl:order-1">
            <WorkspacePanel title="Control Notes">
              <div className="space-y-4 text-sm text-slate-600">
                <p>The status should tell operators exactly what the public can do right now.</p>
                <ul className="space-y-2">
                  <li><strong className="text-primary">Draft:</strong> internal only</li>
                  <li><strong className="text-primary">Published:</strong> visible, not yet in active intake</li>
                  <li><strong className="text-primary">Registration Open:</strong> accepting attendees</li>
                  <li><strong className="text-primary">Live:</strong> event in delivery window</li>
                  <li><strong className="text-primary">Completed:</strong> post-event access and reporting</li>
                </ul>
              </div>
            </WorkspacePanel>
            </div>

            <div className="order-1 xl:order-2">
            <WorkspacePanel title="Quick Links">
              <div className="space-y-3">
                {capabilities.canUpdate && <QuickLink href={route('admin.events.edit', event.slug)} icon="fa-pen" label="Update schedule and status" />}
                {capabilities.canManageResources && <QuickLink href={route('admin.events.resources.create', event.slug)} icon="fa-folder-plus" label="Upload attendee resources" />}
                {capabilities.canManageSpeakers && <QuickLink href={route('admin.events.assign-speakers', event.slug)} icon="fa-microphone" label="Open speaker pipeline" />}
                {capabilities.canViewPayments && <QuickLink href={route('admin.transactions-audit.index')} icon="fa-receipt" label="Open transaction audit" />}
              </div>
            </WorkspacePanel>
            </div>
          </div>
        </div>
      </div>

      {showDeleteModal && capabilities.canDelete && (
        <div className="fixed inset-0 z-50 flex items-center justify-center bg-black/30 px-4">
          <div className="w-full max-w-md rounded-lg bg-white p-6 shadow-xl">
            <div className="space-y-4">
              <div className="flex h-12 w-12 items-center justify-center rounded-full bg-red-100 text-red-600">
                <i className="fas fa-trash"></i>
              </div>
              <div>
                <h2 className="text-lg font-semibold text-slate-900">Delete event</h2>
                <p className="mt-2 text-sm text-slate-600">
                  This will remove <strong>{event.title}</strong> and its related records.
                </p>
              </div>
              <div className="flex justify-end gap-3 pt-2">
                <button type="button" onClick={() => setShowDeleteModal(false)} className="rounded-lg border border-slate-300 px-4 py-2 text-sm font-medium text-slate-700 hover:bg-slate-50">
                  Cancel
                </button>
                <button
                  type="button"
                  onClick={handleDelete}
                  disabled={isDeleting}
                  className="rounded-lg bg-red-600 px-4 py-2 text-sm font-semibold text-white hover:bg-red-700 disabled:opacity-60"
                >
                  {isDeleting ? 'Deleting...' : 'Delete Event'}
                </button>
              </div>
            </div>
          </div>
        </div>
      )}
    </DashboardLayout>
  );
}

function MetricCard({ label, value, hint }: { label: string; value: number; hint: string }) {
  return (
    <div className="min-w-0 rounded-md border border-slate-200 bg-white p-3 sm:p-5">
      <p className="truncate text-[11px] font-semibold uppercase text-slate-500">{label}</p>
      <p className="mt-2 text-2xl font-bold text-primary sm:mt-3 sm:text-3xl">{value}</p>
      <p className="mt-1 line-clamp-2 text-xs leading-5 text-slate-500 sm:mt-2 sm:text-sm">{hint}</p>
    </div>
  );
}

function SummaryStrip({
  label,
  primary,
  secondary,
  tone,
}: {
  label: string;
  primary: string;
  secondary: string;
  tone: 'amber' | 'emerald' | 'rose' | 'slate';
}) {
  const toneClasses = tone === 'emerald'
    ? 'border-emerald-200 bg-emerald-50/70'
    : tone === 'rose'
      ? 'border-rose-200 bg-rose-50/70'
      : tone === 'amber'
        ? 'border-amber-200 bg-amber-50/70'
        : 'border-slate-200 bg-white';

  return (
    <div className={`rounded-lg border px-4 py-3 ${toneClasses}`}>
      <div className="flex flex-col gap-2 sm:flex-row sm:items-center sm:justify-between">
        <p className="text-[11px] font-semibold uppercase tracking-[0.16em] text-slate-500">{label}</p>
        <p className="text-sm font-semibold text-slate-900">{primary}</p>
      </div>
      <p className="mt-1 text-sm text-slate-600">{secondary}</p>
    </div>
  );
}

function WorkspacePanel({ title, children }: { title: string; children: ReactNode }) {
  return (
    <section className="rounded-md border border-slate-200 bg-white p-4 sm:p-6">
      <h2 className="mb-4 wrap-break-word text-base font-semibold text-slate-900 sm:text-lg">{title}</h2>
      {children}
    </section>
  );
}

function MetaItem({ label, value }: { label: string; value: string }) {
  return (
    <div className="min-w-0 rounded-md border border-slate-200 p-3 sm:p-4">
      <dt className="text-[11px] font-semibold uppercase text-slate-500">{label}</dt>
      <dd className="mt-2 wrap-break-word text-sm text-slate-800">{value}</dd>
    </div>
  );
}

function EmptyText({ text }: { text: string }) {
  return <p className="rounded-md border border-dashed border-slate-200 px-3 py-6 text-center text-sm text-slate-500 sm:px-4 sm:py-8">{text}</p>;
}

function QuickLink({ href, icon, label }: { href: string; icon: string; label: string }) {
  return (
    <Link href={href} className="flex min-w-0 items-center justify-between gap-3 rounded-md border border-slate-200 px-3 py-3 text-sm font-medium text-slate-700 transition hover:border-primary hover:text-primary sm:px-4">
      <span className="flex min-w-0 items-center gap-3">
        <i className={`fas ${icon} w-4 h-4`}></i>
        <span className="wrap-break-word">{label}</span>
      </span>
      <i className="fas fa-arrow-right w-4 h-4"></i>
    </Link>
  );
}

function formatDate(dateString: string) {
  return new Date(dateString).toLocaleString('en-US', {
    year: 'numeric',
    month: 'short',
    day: 'numeric',
    hour: 'numeric',
    minute: '2-digit',
  });
}

function formatShortDate(dateString: string) {
  return new Date(dateString).toLocaleDateString('en-US', {
    year: 'numeric',
    month: 'short',
    day: 'numeric',
  });
}
