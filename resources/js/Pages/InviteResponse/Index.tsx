import { Head, router, usePage } from '@inertiajs/react';
import DashboardLayout from '@/Layouts/DashboardLayout';
import { useState, FormEvent } from 'react';

interface Event {
    id: number;
    title: string;
    slug: string;
    description?: string;
    mode: 'online' | 'offline';
    location?: string;
    physical_address?: string;
    contact_email?: string;
    start_date: string;
    end_date: string;
}

interface Invite {
    id: number;
    suggested_topic?: string;
    suggested_duration?: number;
    audience_expectations?: string;
    expected_format?: string;
    special_instructions?: string;
    expires_at: string;
    status: 'pending' | 'accepted' | 'rejected';
}

interface InviteResponseProps {
    event: Event;
    invite: Invite;
}

export default function InviteResponse({ event, invite }: InviteResponseProps) {
    const { sideLinks } = usePage().props as any;
    const [showAcceptModal, setShowAcceptModal] = useState(false);
    const [showDeclineModal, setShowDeclineModal] = useState(false);
    const [isProcessing, setIsProcessing] = useState(false);
    const [declineFeedback, setDeclineFeedback] = useState('');

    const isExpired = new Date(invite.expires_at) < new Date();

    const formatDate = (dateString: string) => {
        return new Date(dateString).toLocaleDateString('en-US', { year: 'numeric', month: 'short', day: 'numeric', hour: 'numeric', minute: '2-digit' });
    };

    const getExpiresIn = (dateString: string) => {
        const now = new Date();
        const expires = new Date(dateString);
        const diff = expires.getTime() - now.getTime();
        if (diff < 0) return 'expired';
        const days = Math.floor(diff / (1000 * 60 * 60 * 24));
        const hours = Math.floor((diff % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
        if (days > 0) return `in ${days} day${days !== 1 ? 's' : ''}`;
        if (hours > 0) return `in ${hours} hour${hours !== 1 ? 's' : ''}`;
        return 'soon';
    };

    const handleAccept = (e: FormEvent) => {
        e.preventDefault();
        setIsProcessing(true);
        router.post(route('invitations.accept', { event: event.slug, invite: invite.id }), {}, {
            preserveScroll: true,
            onFinish: () => { setIsProcessing(false); setShowAcceptModal(false); },
        });
    };

    const handleDecline = (e: FormEvent) => {
        e.preventDefault();
        if (!declineFeedback.trim()) return;
        setIsProcessing(true);
        router.patch(route('invitations.accept', { event: event.slug, invite: invite.id }), { feedback: declineFeedback }, {
            preserveScroll: true,
            onFinish: () => { setIsProcessing(false); setShowDeclineModal(false); setDeclineFeedback(''); },
        });
    };

    return (
        <DashboardLayout sideLinks={sideLinks}>
            <Head title={`Invitation: ${event.title}`} />

            <div className="space-y-5">
                <section className="rounded-lg border border-slate-200 bg-white p-6">
                    <div className="flex items-start gap-4 mb-5">
                        <span className="flex h-10 w-10 shrink-0 items-center justify-center rounded-lg bg-primary-50 text-primary">
                            <i className="fas fa-calendar text-sm"></i>
                        </span>
                        <div>
                            <h1 className="text-xl font-semibold tracking-tight text-slate-900">{event.title}</h1>
                            <span className={`mt-1.5 inline-flex items-center rounded-md px-2.5 py-0.5 text-[11px] font-semibold uppercase tracking-wider ${
                                event.mode === 'offline' ? 'bg-primary-50 text-primary' : 'bg-lime-50 text-lime-700'
                            }`}>
                                <i className={`fas fa-${event.mode === 'offline' ? 'map-marker-alt' : 'globe'} mr-1 text-[10px]`}></i>
                                {event.mode === 'offline' ? 'In-Person Event' : 'Online Event'}
                            </span>
                        </div>
                    </div>

                    {event.description && (
                        <p className="text-sm leading-relaxed text-slate-500 mb-5">
                            {event.description.length > 280 ? event.description.substring(0, 280) + '...' : event.description}
                        </p>
                    )}

                    <div className="grid gap-3 sm:grid-cols-2 lg:grid-cols-3">
                        <DetailItem icon="map-marker-alt" label="Location" value={event.mode === 'offline' ? event.physical_address : event.location} />
                        {event.contact_email && <DetailItem icon="envelope" label="Contact" value={event.contact_email} />}
                        <DetailItem icon="clock" label="Date & Time" value={`${formatDate(event.start_date)} to ${formatDate(event.end_date)}`} />
                    </div>
                </section>

                <section className="rounded-lg border border-slate-200 bg-white p-6">
                    <div className="flex items-start gap-4 mb-5">
                        <span className="flex h-10 w-10 shrink-0 items-center justify-center rounded-lg bg-primary-50 text-primary">
                            <i className="fas fa-user text-sm"></i>
                        </span>
                        <div>
                            <h2 className="text-lg font-semibold tracking-tight text-slate-900">Your Invitation Details</h2>
                            <p className="mt-1 text-sm text-slate-500">Please review the details below before responding.</p>
                        </div>
                    </div>

                    <div className="space-y-4">
                        {invite.suggested_topic && <InviteDetail label="Suggested Topic" value={invite.suggested_topic} />}
                        {invite.suggested_duration && <InviteDetail label="Suggested Duration" value={`${invite.suggested_duration} minutes`} />}
                        {invite.audience_expectations && <InviteDetail label="Audience Expectations" value={invite.audience_expectations} />}
                        {invite.expected_format && <InviteDetail label="Expected Format" value={invite.expected_format} />}
                        {invite.special_instructions && <InviteDetail label="Special Instructions" value={invite.special_instructions} />}

                        <div className="flex items-center gap-2 pt-4 border-t border-slate-100">
                            <i className="fas fa-hourglass-half text-xs text-amber-500"></i>
                            <span className="text-sm text-slate-600">Expires {getExpiresIn(invite.expires_at)}</span>
                            {isExpired && (
                                <span className="inline-flex items-center rounded-md bg-accent-50 px-2 py-0.5 text-[11px] font-semibold text-accent">Expired</span>
                            )}
                        </div>
                    </div>

                    {isExpired ? (
                        <div className="mt-5 rounded-md border border-amber-200 bg-amber-50 p-5 text-center">
                            <i className="fas fa-exclamation-triangle text-amber-500 text-lg mx-auto mb-2"></i>
                            <h3 className="text-sm font-semibold text-amber-800">This invitation has expired</h3>
                            <p className="mt-1 text-[13px] text-amber-700">You can no longer respond to this invitation.</p>
                        </div>
                    ) : (
                        <div className="flex flex-col sm:flex-row gap-3 pt-5 border-t border-slate-100">
                            <button onClick={() => setShowAcceptModal(true)}
                                className="inline-flex items-center justify-center gap-2 rounded-md bg-lime-600 px-6 py-2.5 text-sm font-medium text-white transition hover:bg-lime-700 shadow-sm">
                                <i className="fas fa-check text-xs"></i>
                                Accept Invitation
                            </button>
                            <button onClick={() => setShowDeclineModal(true)}
                                className="inline-flex items-center justify-center gap-2 rounded-md border border-accent-200 bg-white px-6 py-2.5 text-sm font-medium text-accent transition hover:bg-accent-50">
                                <i className="fas fa-times text-xs"></i>
                                Decline Invitation
                            </button>
                        </div>
                    )}
                </section>
            </div>

            {showAcceptModal && (
                <div className="fixed inset-0 z-50 flex items-center justify-center bg-slate-900/30 px-4 backdrop-blur-sm">
                    <div className="w-full max-w-md rounded-lg border border-slate-200 bg-white p-6 shadow-lg">
                        <button type="button" onClick={() => setShowAcceptModal(false)} className="absolute right-4 top-4 text-slate-400 hover:text-slate-600">
                            <i className="fas fa-times text-sm"></i>
                        </button>
                        <div className="text-center mb-5">
                            <i className="fas fa-check-circle text-lime-600 text-3xl mx-auto mb-3"></i>
                            <h3 className="text-base font-semibold text-slate-900">Accept Invitation</h3>
                        </div>
                        <p className="text-sm text-slate-500 text-center mb-5">Are you sure you want to accept this invitation? This action cannot be undone.</p>
                        <form onSubmit={handleAccept} className="flex justify-end gap-3">
                            <button type="button" onClick={() => setShowAcceptModal(false)} className="rounded-md border border-slate-200 bg-white px-4 py-2 text-sm font-medium text-slate-700 transition hover:bg-slate-50">Cancel</button>
                            <button type="submit" disabled={isProcessing} className="rounded-md bg-lime-600 px-4 py-2 text-sm font-medium text-white transition hover:bg-lime-700 shadow-sm disabled:opacity-50">
                                {isProcessing ? 'Accepting...' : 'Accept Invitation'}
                            </button>
                        </form>
                    </div>
                </div>
            )}

            {showDeclineModal && (
                <div className="fixed inset-0 z-50 flex items-center justify-center bg-slate-900/30 px-4 backdrop-blur-sm">
                    <div className="w-full max-w-md rounded-lg border border-slate-200 bg-white p-6 shadow-lg">
                        <button type="button" onClick={() => setShowDeclineModal(false)} className="absolute right-4 top-4 text-slate-400 hover:text-slate-600">
                            <i className="fas fa-times text-sm"></i>
                        </button>
                        <div className="text-center mb-5">
                            <i className="fas fa-times-circle text-accent text-3xl mx-auto mb-3"></i>
                            <h3 className="text-base font-semibold text-slate-900">Decline Invitation</h3>
                        </div>
                        <p className="text-sm text-slate-500 mb-4">We appreciate your consideration. Kindly share your reason for declining:</p>
                        <form onSubmit={handleDecline} className="space-y-4">
                            <textarea
                                rows={4}
                                value={declineFeedback}
                                onChange={(e) => setDeclineFeedback(e.target.value)}
                                placeholder="Please provide your reason..."
                                required
                                className="w-full rounded-md border border-slate-300 px-3.5 py-2.5 text-sm text-slate-900 placeholder:text-slate-400 outline-none transition focus:border-accent-300 focus:ring-2 focus:ring-accent-500/10 resize-none"
                            />
                            <div className="flex justify-end gap-3">
                                <button type="button" onClick={() => setShowDeclineModal(false)} className="rounded-md border border-slate-200 bg-white px-4 py-2 text-sm font-medium text-slate-700 transition hover:bg-slate-50">Cancel</button>
                                <button type="submit" disabled={isProcessing} className="rounded-md bg-accent px-4 py-2 text-sm font-medium text-white transition hover:bg-accent-600 shadow-sm disabled:opacity-50">
                                    {isProcessing ? 'Declining...' : 'Decline Invitation'}
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            )}
        </DashboardLayout>
    );
}

function InviteDetail({ label, value }: { label: string; value: string }) {
    return (
        <div className="border-l-2 border-primary/30 pl-4 py-1.5">
            <p className="text-[11px] font-semibold uppercase tracking-wider text-slate-400">{label}</p>
            <p className="mt-0.5 text-sm text-slate-700">{value}</p>
        </div>
    );
}

function DetailItem({ icon, label, value }: { icon: string; label: string; value?: string }) {
    if (!value) return null;
    return (
        <div className="flex items-start gap-3 rounded-md bg-slate-50 p-3.5">
            <i className={`fas fa-${icon} text-sm text-slate-400 mt-0.5 shrink-0`}></i>
            <div>
                <p className="text-xs font-medium text-slate-900">{label}</p>
                <p className="mt-0.5 text-[13px] text-slate-500 leading-relaxed">{value}</p>
            </div>
        </div>
    );
}
