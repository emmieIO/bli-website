import { Head, Link, usePage } from '@inertiajs/react';
import DashboardLayout from '@/Layouts/DashboardLayout';
import type { ReactNode } from 'react';

interface Event {
  id: number;
  title: string;
  slug: string;
  location?: string | null;
  mode: 'online' | 'offline' | 'hybrid';
  start_date: string;
  end_date: string;
  status: 'draft' | 'review' | 'published' | 'registration_open' | 'registration_closed' | 'live' | 'completed' | 'cancelled' | 'archived';
  is_featured: boolean;
  speakers_count?: number;
  attendees_count?: number;
  speaker_applications_count?: number;
  successful_transactions_count?: number;
  program_cover?: string | null;
}

interface EventsProps {
  events: { data: Event[]; };
  capabilities: { canCreate: boolean; canUpdateAny: boolean; canViewPayments: boolean; };
}

const statusTabs = [
  { label: 'All', value: 'all' },
  { label: 'Draft', value: 'draft' },
  { label: 'Review', value: 'review' },
  { label: 'Published', value: 'published' },
  { label: 'Reg. Open', value: 'registration_open' },
  { label: 'Live', value: 'live' },
  { label: 'Completed', value: 'completed' },
  { label: 'Cancelled', value: 'cancelled' },
];

const statusLabelMap: Record<Event['status'], string> = {
  draft: 'Draft', review: 'Review', published: 'Published',
  registration_open: 'Registration Open', registration_closed: 'Reg. Closed',
  live: 'Live', completed: 'Completed', cancelled: 'Cancelled', archived: 'Archived',
};

const statusColorMap: Record<Event['status'], string> = {
  draft: 'bg-slate-100 text-slate-600',
  review: 'bg-violet-50 text-violet-700',
  published: 'bg-primary-50 text-primary',
  registration_open: 'bg-lime-100 text-lime-700',
  registration_closed: 'bg-amber-50 text-amber-700',
  live: 'bg-accent-50 text-accent',
  completed: 'bg-emerald-50 text-emerald-700',
  cancelled: 'bg-rose-50 text-rose-700',
  archived: 'bg-zinc-100 text-zinc-600',
};

export default function EventsIndex({ events, capabilities }: EventsProps) {
  const { sideLinks } = usePage().props as any;
  const currentStatus = typeof window !== 'undefined' ? new URLSearchParams(window.location.search).get('status') || 'all' : 'all';
  const eventsList = events.data || [];

  const totals = {
    total: eventsList.length,
    live: eventsList.filter((e) => e.status === 'live').length,
    open: eventsList.filter((e) => e.status === 'registration_open').length,
    completed: eventsList.filter((e) => e.status === 'completed').length,
  };

  return (
    <DashboardLayout sideLinks={sideLinks}>
      <Head title="Events Management" />

      <div className="space-y-5">
        <div className="flex flex-col gap-4 sm:flex-row sm:items-end sm:justify-between">
          <div>
            <h1 className="text-xl font-semibold tracking-tight text-slate-900">Events</h1>
            <p className="mt-1 text-sm text-slate-500">Plan, publish, and supervise event delivery.</p>
          </div>
          {capabilities.canCreate && (
            <Link href={route('admin.events.create')} className="inline-flex items-center gap-2 rounded-md bg-primary px-4 py-2.5 text-sm font-medium text-white transition hover:bg-primary-600 shadow-sm">
              <i className="fas fa-plus text-xs"></i> New Event
            </Link>
          )}
        </div>

        <div className="grid gap-3 sm:grid-cols-2 lg:grid-cols-4">
          <SummaryCard label="Visible" value={totals.total} hint="Current result set" />
          <SummaryCard label="Live now" value={totals.live} hint="In progress" />
          <SummaryCard label="Open for registration" value={totals.open} hint="Accepting attendees" />
          <SummaryCard label="Completed" value={totals.completed} hint="Delivered events" />
        </div>

        <div className="flex flex-wrap gap-1.5 rounded-lg border border-slate-200 bg-white p-1.5">
          {statusTabs.map((tab) => {
            const isActive = currentStatus === tab.value;
            return (
              <Link
                key={tab.value}
                href={`${route('admin.events.index')}?status=${tab.value}`}
                className={`rounded-md px-3 py-2 text-sm font-medium transition ${isActive ? 'bg-primary text-white' : 'text-slate-600 hover:bg-slate-100'}`}
              >
                {tab.label}
              </Link>
            );
          })}
        </div>

        <div className="overflow-hidden rounded-lg border border-slate-200 bg-white">
          <div className="overflow-x-auto">
            <table className="min-w-[960px] divide-y divide-slate-200">
              <thead className="bg-slate-50/80">
                <tr>
                  <HeaderCell>Event</HeaderCell>
                  <HeaderCell>Schedule</HeaderCell>
                  <HeaderCell>Status</HeaderCell>
                  <HeaderCell>Speakers</HeaderCell>
                  <HeaderCell>Registrations</HeaderCell>
                  {capabilities.canViewPayments && <HeaderCell>Revenue</HeaderCell>}
                  <HeaderCell align="right">Actions</HeaderCell>
                </tr>
              </thead>
              <tbody className="divide-y divide-slate-100">
                {eventsList.length > 0 ? (
                  eventsList.map((event) => (
                    <tr key={event.id} className="transition hover:bg-slate-50/70">
                      <td className="px-5 py-3.5">
                        <div className="flex items-center gap-3">
                          <div className="h-10 w-10 shrink-0 overflow-hidden rounded-md bg-slate-100">
                            {event.program_cover ? (
                              <img src={`/storage/${event.program_cover}`} alt={event.title} className="h-full w-full object-cover" />
                            ) : (
                              <div className="flex h-full w-full items-center justify-center text-slate-400"><i className="fas fa-calendar-alt text-sm"></i></div>
                            )}
                          </div>
                          <div className="min-w-0">
                            <p className="truncate text-sm font-semibold text-slate-900">{event.title}</p>
                            <p className="truncate text-xs text-slate-500">{event.mode === 'online' ? 'Online' : event.location || 'Location pending'}</p>
                          </div>
                        </div>
                      </td>
                      <td className="whitespace-nowrap px-5 py-3.5 text-sm text-slate-700">
                        <p>{formatDate(event.start_date)}</p>
                        <p className="text-xs text-slate-400">Ends {formatDate(event.end_date)}</p>
                      </td>
                      <td className="px-5 py-3.5">
                        <div className="space-y-1.5">
                          <span className={`inline-flex whitespace-nowrap rounded-md px-2 py-0.5 text-[11px] font-semibold ${statusColorMap[event.status]}`}>
                            {statusLabelMap[event.status]}
                          </span>
                          {event.is_featured && <div className="text-[11px] font-medium text-amber-600">Featured</div>}
                        </div>
                      </td>
                      <td className="whitespace-nowrap px-5 py-3.5 text-sm text-slate-700">
                        <p>{event.speakers_count || 0} assigned</p>
                        <p className="text-xs text-slate-400">{event.speaker_applications_count || 0} applications</p>
                      </td>
                      <td className="whitespace-nowrap px-5 py-3.5 text-sm text-slate-700">
                        <p>{event.attendees_count || 0} attendees</p>
                      </td>
                      {capabilities.canViewPayments && (
                        <td className="whitespace-nowrap px-5 py-3.5 text-sm text-slate-700">
                          <p>{event.successful_transactions_count || 0} paid orders</p>
                        </td>
                      )}
                      <td className="px-5 py-3.5">
                        <div className="flex items-center justify-end gap-2 whitespace-nowrap text-sm">
                          <Link href={route('admin.events.show', event.slug)} className="font-medium text-primary hover:text-primary-600">Open</Link>
                          {capabilities.canUpdateAny && (
                            <Link href={route('admin.events.edit', event.slug)} className="font-medium text-slate-600 hover:text-slate-900">Edit</Link>
                          )}
                        </div>
                      </td>
                    </tr>
                  ))
                ) : (
                  <tr>
                    <td colSpan={capabilities.canViewPayments ? 7 : 6} className="px-6 py-14 text-center">
                      <div className="mx-auto flex h-14 w-14 items-center justify-center rounded-lg bg-slate-100 text-slate-400 mb-3">
                        <i className="fas fa-calendar-times"></i>
                      </div>
                      <p className="text-sm font-semibold text-slate-900">No events found</p>
                      <p className="mt-1 text-sm text-slate-500">Create your next event or switch filters.</p>
                    </td>
                  </tr>
                )}
              </tbody>
            </table>
          </div>
        </div>
      </div>
    </DashboardLayout>
  );
}

function SummaryCard({ label, value, hint }: { label: string; value: number; hint: string }) {
  return (
    <div className="rounded-lg border border-slate-200 bg-white p-4">
      <p className="text-[11px] font-semibold uppercase tracking-[0.14em] text-slate-400">{label}</p>
      <p className="mt-2 text-2xl font-semibold tracking-tight text-primary">{value}</p>
      <p className="mt-1 text-[13px] text-slate-500">{hint}</p>
    </div>
  );
}

function HeaderCell({ children, align = 'left' }: { children: ReactNode; align?: 'left' | 'right' }) {
  return (
    <th className={`px-5 py-3 text-[11px] font-semibold uppercase tracking-[0.14em] text-slate-500 ${align === 'right' ? 'text-right' : 'text-left'}`}>
      {children}
    </th>
  );
}

function formatDate(dateString: string) {
  return new Date(dateString).toLocaleString('en-US', { year: 'numeric', month: 'short', day: 'numeric', hour: 'numeric', minute: '2-digit' });
}
