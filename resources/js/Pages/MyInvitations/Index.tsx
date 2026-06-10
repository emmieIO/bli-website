import { Head, Link, usePage } from '@inertiajs/react';
import DashboardLayout from '@/Layouts/DashboardLayout';
import { CalendarDays, MapPin, Clock, Globe, Inbox, ArrowRight, CheckCircle2, XCircle, AlertCircle } from 'lucide-react';

interface Event {
    id: number;
    title: string;
    slug: string;
    mode: 'online' | 'offline';
    location?: string;
    physical_address?: string;
    event_date: string;
}

interface Invitation {
    id: number;
    status: 'pending' | 'accepted' | 'rejected';
    expires_at: string;
    suggested_topic?: string;
    event: Event;
}

interface MyInvitationsProps {
    invitations: Invitation[];
}

export default function MyInvitations({ invitations }: MyInvitationsProps) {
    const { sideLinks } = usePage().props as any;

    const stats = {
        total: invitations.length,
        pending: invitations.filter((inv) => inv.status === 'pending').length,
        accepted: invitations.filter((inv) => inv.status === 'accepted').length,
        declined: invitations.filter((inv) => inv.status === 'rejected').length,
    };

    return (
        <DashboardLayout sideLinks={sideLinks}>
            <Head title="My Invitations" />

            <div className="space-y-6">
                <div>
                    <h1 className="text-xl font-semibold tracking-tight text-slate-900">Your Event Invitations</h1>
                    <p className="mt-1 text-sm text-slate-500">Review and respond to exclusive event invitations you've received.</p>
                </div>

                {invitations.length === 0 ? (
                    <div className="rounded-lg border border-slate-200 bg-white p-16 text-center">
                        <div className="mx-auto flex h-14 w-14 items-center justify-center rounded-lg bg-slate-100">
                            <Inbox size={24} className="text-slate-300" />
                        </div>
                        <h3 className="mt-4 text-sm font-semibold text-slate-900">No invitations yet</h3>
                        <p className="mt-1 text-sm text-slate-500">You'll see exclusive event invitations here when organizers send them your way.</p>
                        <Link href={route('events.index')} className="mt-5 inline-flex items-center gap-2 rounded-md bg-primary px-4 py-2.5 text-sm font-medium text-white transition hover:bg-primary-600 shadow-sm">
                            Browse Public Events
                        </Link>
                    </div>
                ) : (
                    <>
                        <div className="grid grid-cols-4 gap-4">
                            <SummaryPill label="Total" value={stats.total} active />
                            <SummaryPill label="Pending" value={stats.pending} color="amber" />
                            <SummaryPill label="Accepted" value={stats.accepted} color="lime" />
                            <SummaryPill label="Declined" value={stats.declined} color="accent" />
                        </div>

                        <div className="grid gap-4 sm:grid-cols-2 lg:grid-cols-3">
                            {invitations.map((invitation) => (
                                <InvitationCard key={invitation.id} invitation={invitation} />
                            ))}
                        </div>
                    </>
                )}
            </div>
        </DashboardLayout>
    );
}

function SummaryPill({ label, value, color, active }: { label: string; value: number; color?: string; active?: boolean }) {
    const barColors: Record<string, string> = {
        amber: 'bg-amber-500',
        lime: 'bg-lime-500',
        accent: 'bg-accent',
    };

    return (
        <div className={`rounded-lg border border-slate-200 bg-white p-3 ${active ? 'ring-1 ring-primary-200' : ''}`}>
            <div className="flex items-center gap-2.5">
                {color && <span className={`h-1.5 w-1.5 rounded-full ${barColors[color] || 'bg-primary'}`} />}
                <span className="text-[11px] font-semibold uppercase tracking-wider text-slate-400">{label}</span>
            </div>
            <p className="mt-1 text-xl font-semibold tracking-tight text-slate-900">{value}</p>
        </div>
    );
}

function InvitationCard({ invitation }: { invitation: Invitation }) {
    const isExpired = new Date(invitation.expires_at) < new Date();
    const status = isExpired ? 'expired' : invitation.status;

    const formatDate = (dateString: string) => {
        return new Date(dateString).toLocaleDateString('en-US', { year: 'numeric', month: 'short', day: 'numeric', hour: 'numeric', minute: '2-digit' });
    };

    const getExpiresIn = (dateString: string) => {
        const now = new Date();
        const diff = new Date(dateString).getTime() - now.getTime();
        if (diff < 0) return 'Expired';
        const days = Math.floor(diff / (1000 * 60 * 60 * 24));
        const hours = Math.floor((diff % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
        if (days > 0) return `${days}d remaining`;
        if (hours > 0) return `${hours}h remaining`;
        return 'Expiring soon';
    };

    const statusConfig: Record<string, { bg: string; text: string; icon: any; label: string }> = {
        accepted: { bg: 'bg-lime-50', text: 'text-lime-700', icon: CheckCircle2, label: 'Accepted' },
        rejected: { bg: 'bg-accent-50', text: 'text-accent', icon: XCircle, label: 'Declined' },
        expired: { bg: 'bg-slate-100', text: 'text-slate-500', icon: Clock, label: 'Expired' },
        pending: { bg: 'bg-amber-50', text: 'text-amber-700', icon: AlertCircle, label: 'Pending' },
    };

    const sc = statusConfig[status] || statusConfig.expired;
    const StatusIcon = sc.icon;

    const isActionable = status === 'pending';

    return (
        <div className={`group flex flex-col rounded-lg border bg-white transition hover:shadow-sm ${
            isActionable ? 'border-amber-200 ring-1 ring-amber-100' : 'border-slate-200 hover:border-slate-300'
        }`}>
            <div className="flex-1 p-5">
                <div className="flex items-start justify-between mb-3">
                    <span className="flex h-9 w-9 items-center justify-center rounded-lg bg-primary-50 text-primary">
                        {invitation.event.mode === 'offline' ? <MapPin size={16} /> : <Globe size={16} />}
                    </span>
                    <span className={`inline-flex items-center gap-1 rounded-md px-2 py-0.5 text-[11px] font-semibold ${sc.bg} ${sc.text}`}>
                        <StatusIcon size={12} />
                        {sc.label}
                    </span>
                </div>

                <h3 className="text-sm font-semibold text-slate-900 leading-snug">
                    {invitation.event.title.length > 55 ? invitation.event.title.substring(0, 55) + '...' : invitation.event.title}
                </h3>

                <div className="mt-3 space-y-2 text-[13px] text-slate-500">
                    <div className="flex items-center gap-2">
                        <CalendarDays size={13} className="text-slate-400 shrink-0" />
                        <span>{formatDate(invitation.event.event_date)}</span>
                    </div>
                    <div className="flex items-start gap-2">
                        <MapPin size={13} className="text-slate-400 shrink-0 mt-0.5" />
                        <span className="leading-relaxed line-clamp-1">
                            {invitation.event.mode === 'online' ? invitation.event.location : invitation.event.physical_address}
                        </span>
                    </div>
                    <div className="flex items-center gap-2">
                        <Clock size={13} className={`shrink-0 ${isActionable ? 'text-amber-500' : 'text-slate-400'}`} />
                        <span className={isActionable ? 'text-amber-600 font-medium' : ''}>{getExpiresIn(invitation.expires_at)}</span>
                    </div>
                </div>

                {invitation.suggested_topic && (
                    <div className="mt-3 rounded-md bg-primary-50/50 border border-primary-100 p-3">
                        <p className="text-[11px] font-semibold uppercase tracking-wider text-primary">Suggested Topic</p>
                        <p className="mt-1 text-[13px] leading-relaxed text-slate-600">
                            {invitation.suggested_topic.length > 100 ? invitation.suggested_topic.substring(0, 100) + '...' : invitation.suggested_topic}
                        </p>
                    </div>
                )}
            </div>

            <div className={`border-t px-5 py-3.5 ${isActionable ? 'bg-amber-50/50 border-amber-100' : 'bg-slate-50/50 border-slate-100'}`}>
                <Link
                    href={route('speaker.events.show', invitation.event.slug)}
                    className={`flex w-full items-center justify-center gap-2 rounded-md px-4 py-2.5 text-sm font-medium transition shadow-sm ${
                        isActionable
                            ? 'bg-primary text-white hover:bg-primary-600'
                            : 'border border-slate-200 bg-white text-slate-600 hover:bg-slate-50'
                    }`}
                >
                    {isActionable ? (
                        <>Respond to invitation <ArrowRight size={14} /></>
                    ) : (
                        <>Open workspace</>
                    )}
                </Link>
            </div>
        </div>
    );
}
