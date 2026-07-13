import { FormEvent, ReactNode, useMemo, useState } from 'react';
import { Head, Link, useForm, usePage } from '@inertiajs/react';
import { ArrowLeft, CheckCircle2, Clock3, Mail, Mic2, Send, UserRoundCheck, XCircle } from 'lucide-react';
import DashboardLayout from '@/Layouts/DashboardLayout';

interface User {
  id: number;
  name: string;
  email: string;
  photo?: string | null;
}

interface Speaker {
  id: number;
  user: User;
  organization?: string | null;
  title?: string | null;
}

interface SpeakerInvite {
  id: number;
  status: 'pending' | 'accepted' | 'rejected' | 'cancelled';
  suggested_topic?: string | null;
  expected_format?: string | null;
  expires_at?: string | null;
  speaker?: Speaker | null;
}

interface SpeakerApplication {
  id: number;
  status: string;
  topic_title: string;
  session_format: string;
  user?: User | null;
  speaker?: Speaker | null;
}

interface Event {
  id: number;
  slug: string;
  title: string;
  speakers: Speaker[];
  speaker_invites: SpeakerInvite[];
  speaker_applications: SpeakerApplication[];
}

interface Props {
  event: Event;
  speakers: Speaker[];
}

const statusStyles: Record<string, string> = {
  pending: 'bg-amber-50 text-amber-800 ring-amber-200',
  under_review: 'bg-blue-50 text-blue-800 ring-blue-200',
  accepted: 'bg-emerald-50 text-emerald-800 ring-emerald-200',
  approved: 'bg-emerald-50 text-emerald-800 ring-emerald-200',
  rejected: 'bg-red-50 text-red-700 ring-red-200',
  cancelled: 'bg-slate-100 text-slate-600 ring-slate-200',
};

function StatusBadge({ status }: { status: string }) {
  return (
    <span className={`inline-flex rounded-full px-2.5 py-1 text-xs font-semibold capitalize ring-1 ring-inset ${statusStyles[status] ?? statusStyles.cancelled}`}>
      {status.replace('_', ' ')}
    </span>
  );
}

function PersonRow({ speaker, detail, trailing }: { speaker: Speaker; detail?: string; trailing?: ReactNode }) {
  const initials = speaker.user.name.split(' ').map((part) => part[0]).slice(0, 2).join('').toUpperCase();

  return (
    <div className="flex min-w-0 items-center gap-3 border-b border-slate-100 py-4 last:border-b-0">
      {speaker.user.photo ? (
        <img src={`/storage/${speaker.user.photo}`} alt="" className="h-10 w-10 shrink-0 rounded-full object-cover" />
      ) : (
        <span className="flex h-10 w-10 shrink-0 items-center justify-center rounded-full bg-primary-50 text-xs font-bold text-primary">{initials}</span>
      )}
      <div className="min-w-0 flex-1">
        <p className="truncate text-sm font-semibold text-slate-900">{speaker.user.name}</p>
        <p className="truncate text-xs text-slate-500">{detail || speaker.user.email}</p>
      </div>
      {trailing}
    </div>
  );
}

export default function SpeakerPipeline({ event, speakers }: Props) {
  const { sideLinks } = usePage().props as { sideLinks?: unknown };
  const [showInviteForm, setShowInviteForm] = useState(false);
  const form = useForm({
    speaker_id: '',
    suggested_topic: '',
    suggested_duration: 45,
    audience_expectations: '',
    expected_format: 'Talk',
    special_instructions: '',
  });

  const pipeline = useMemo(() => ({
    invited: event.speaker_invites.filter((invite) => invite.status === 'pending'),
    declined: event.speaker_invites.filter((invite) => ['rejected', 'cancelled'].includes(invite.status)),
    applications: event.speaker_applications.filter((application) => !['approved', 'rejected'].includes(application.status)),
  }), [event.speaker_applications, event.speaker_invites]);
  const summary = [
    { icon: Clock3, label: 'Awaiting response', value: pipeline.invited.length },
    { icon: UserRoundCheck, label: 'Applications', value: pipeline.applications.length },
    { icon: CheckCircle2, label: 'Confirmed', value: event.speakers.length },
  ];

  const submitInvitation = (e: FormEvent) => {
    e.preventDefault();
    form.post(route('admin.events.invite-speaker', event.slug), {
      preserveScroll: true,
      onSuccess: () => {
        form.reset();
        setShowInviteForm(false);
      },
    });
  };

  return (
    <DashboardLayout sideLinks={sideLinks}>
      <Head title={`Speakers - ${event.title}`} />

      <div className="mx-auto max-w-6xl space-y-8 py-6 sm:py-8">
        <header className="flex flex-col gap-4 border-b border-slate-200 pb-6 sm:flex-row sm:items-end sm:justify-between">
          <div>
            <Link href={route('admin.events.show', event.slug)} className="mb-3 inline-flex items-center gap-2 text-sm font-medium text-slate-500 hover:text-primary">
              <ArrowLeft size={16} /> Back to event
            </Link>
            <h1 className="text-2xl font-bold text-slate-950">Speaker pipeline</h1>
            <p className="mt-1 text-sm text-slate-600">{event.title}</p>
          </div>
          <button type="button" onClick={() => setShowInviteForm((open) => !open)} className="inline-flex items-center justify-center gap-2 rounded-md bg-primary px-4 py-2.5 text-sm font-semibold text-white shadow-sm hover:bg-primary-600">
            <Mail size={17} /> Invite speaker
          </button>
        </header>

        {showInviteForm && (
          <section className="border-b border-slate-200 pb-8" aria-labelledby="invite-heading">
            <div className="mb-5">
              <h2 id="invite-heading" className="text-lg font-semibold text-slate-950">Create an invitation</h2>
              <p className="mt-1 text-sm text-slate-600">Send a clear session brief. The speaker has seven days to accept or decline.</p>
            </div>
            <form onSubmit={submitInvitation} className="grid gap-5 md:grid-cols-2">
              <label className="md:col-span-2">
                <span className="mb-1.5 block text-sm font-semibold text-slate-700">Speaker</span>
                <select value={form.data.speaker_id} onChange={(e) => form.setData('speaker_id', e.target.value)} className="w-full rounded-md border-slate-300 text-sm focus:border-primary focus:ring-primary" required>
                  <option value="">Select an existing speaker</option>
                  {speakers.map((speaker) => <option key={speaker.id} value={speaker.id}>{speaker.user.name} - {speaker.user.email}</option>)}
                </select>
                {form.errors.speaker_id && <p className="mt-1 text-xs text-red-600">{form.errors.speaker_id}</p>}
              </label>
              <label>
                <span className="mb-1.5 block text-sm font-semibold text-slate-700">Suggested topic</span>
                <input value={form.data.suggested_topic} onChange={(e) => form.setData('suggested_topic', e.target.value)} className="w-full rounded-md border-slate-300 text-sm focus:border-primary focus:ring-primary" required />
              </label>
              <label>
                <span className="mb-1.5 block text-sm font-semibold text-slate-700">Session format</span>
                <select value={form.data.expected_format} onChange={(e) => form.setData('expected_format', e.target.value)} className="w-full rounded-md border-slate-300 text-sm focus:border-primary focus:ring-primary">
                  <option>Talk</option><option>Panel</option><option>Workshop</option><option>Interview</option><option>Keynote</option>
                </select>
              </label>
              <label>
                <span className="mb-1.5 block text-sm font-semibold text-slate-700">Duration (minutes)</span>
                <input type="number" min="1" value={form.data.suggested_duration} onChange={(e) => form.setData('suggested_duration', Number(e.target.value))} className="w-full rounded-md border-slate-300 text-sm focus:border-primary focus:ring-primary" required />
              </label>
              <label>
                <span className="mb-1.5 block text-sm font-semibold text-slate-700">Audience expectations</span>
                <input value={form.data.audience_expectations} onChange={(e) => form.setData('audience_expectations', e.target.value)} className="w-full rounded-md border-slate-300 text-sm focus:border-primary focus:ring-primary" required />
              </label>
              <label className="md:col-span-2">
                <span className="mb-1.5 block text-sm font-semibold text-slate-700">Special instructions <span className="font-normal text-slate-400">(optional)</span></span>
                <textarea rows={3} value={form.data.special_instructions} onChange={(e) => form.setData('special_instructions', e.target.value)} className="w-full rounded-md border-slate-300 text-sm focus:border-primary focus:ring-primary" />
              </label>
              <div className="flex justify-end gap-3 md:col-span-2">
                <button type="button" onClick={() => setShowInviteForm(false)} className="rounded-md border border-slate-300 px-4 py-2.5 text-sm font-semibold text-slate-700 hover:bg-slate-50">Cancel</button>
                <button type="submit" disabled={form.processing} className="inline-flex items-center gap-2 rounded-md bg-accent px-4 py-2.5 text-sm font-semibold text-primary-900 hover:bg-accent-400 disabled:opacity-60">
                  <Send size={16} /> {form.processing ? 'Sending...' : 'Send invitation'}
                </button>
              </div>
            </form>
          </section>
        )}

        <section className="grid gap-px overflow-hidden rounded-lg border border-slate-200 bg-slate-200 sm:grid-cols-3">
          {summary.map(({ icon: Icon, label, value }) => (
            <div key={label} className="bg-white p-5">
              <Icon size={18} className="text-accent" />
              <p className="mt-3 text-2xl font-bold text-slate-950">{value}</p>
              <p className="text-xs font-medium text-slate-500">{label}</p>
            </div>
          ))}
        </section>

        <div className="grid gap-8 lg:grid-cols-2">
          <section>
            <div className="mb-2 flex items-center gap-2"><Clock3 size={18} className="text-amber-600" /><h2 className="font-semibold text-slate-950">Invited</h2></div>
            {pipeline.invited.length ? pipeline.invited.map((invite) => invite.speaker && <PersonRow key={invite.id} speaker={invite.speaker} detail={invite.suggested_topic || undefined} trailing={<StatusBadge status={invite.status} />} />) : <p className="border-t border-slate-100 py-5 text-sm text-slate-500">No invitations are awaiting a response.</p>}
          </section>

          <section>
            <div className="mb-2 flex items-center gap-2"><CheckCircle2 size={18} className="text-emerald-600" /><h2 className="font-semibold text-slate-950">Confirmed</h2></div>
            {event.speakers.length ? event.speakers.map((speaker) => <PersonRow key={speaker.id} speaker={speaker} detail={speaker.organization || speaker.user.email} trailing={<StatusBadge status="approved" />} />) : <p className="border-t border-slate-100 py-5 text-sm text-slate-500">No speakers have been confirmed.</p>}
          </section>

          <section>
            <div className="mb-2 flex items-center gap-2"><Mic2 size={18} className="text-primary" /><h2 className="font-semibold text-slate-950">Applications</h2></div>
            {pipeline.applications.length ? pipeline.applications.map((application) => (
              <div key={application.id} className="flex items-center gap-3 border-b border-slate-100 py-4 last:border-b-0">
                <div className="min-w-0 flex-1"><p className="truncate text-sm font-semibold text-slate-900">{application.user?.name || application.speaker?.user.name || 'Applicant'}</p><p className="truncate text-xs text-slate-500">{application.topic_title}</p></div>
                <StatusBadge status={application.status} />
                <Link href={route('admin.speakers.application.review', application.id)} className="text-xs font-semibold text-primary hover:underline">Review</Link>
              </div>
            )) : <p className="border-t border-slate-100 py-5 text-sm text-slate-500">No applications need review.</p>}
          </section>

          <section>
            <div className="mb-2 flex items-center gap-2"><XCircle size={18} className="text-red-600" /><h2 className="font-semibold text-slate-950">Declined</h2></div>
            {pipeline.declined.length ? pipeline.declined.map((invite) => invite.speaker && <PersonRow key={invite.id} speaker={invite.speaker} detail={invite.suggested_topic || undefined} trailing={<StatusBadge status={invite.status} />} />) : <p className="border-t border-slate-100 py-5 text-sm text-slate-500">No declined invitations.</p>}
          </section>
        </div>
      </div>
    </DashboardLayout>
  );
}
