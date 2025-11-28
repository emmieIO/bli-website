import { Head, Link, usePage } from '@inertiajs/react';
import DashboardLayout from '@/Layouts/DashboardLayout';

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
    status: 'pending' | 'accepted' | 'declined';
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
        accepted: invitations.filter((inv) => inv.status === 'accepted').length,
        declined: invitations.filter((inv) => inv.status === 'declined').length,
        pending: invitations.filter((inv) => inv.status === 'pending').length,
    };

    return (
        <DashboardLayout sideLinks={sideLinks}>
            <Head title="My Invitations - Beacon Leadership Institute" />

            <div className="max-w-7xl mx-auto py-10">
                {/* Header Section */}
                <div className="mb-12 text-center">
                    <h1 className="text-4xl font-bold text-primary font-montserrat tracking-tight">
                        Your Event Invitations
                    </h1>
                    <p className="mt-4 text-lg text-gray-600 max-w-2xl mx-auto font-lato leading-relaxed">
                        Review and manage the exclusive event invitations you've received from Beacon Leadership
                        Institute
                    </p>
                </div>

                {invitations.length === 0 ? (
                    <EmptyState />
                ) : (
                    <>
                        {/* Invitations Grid */}
                        <div className="grid gap-8 sm:grid-cols-2 lg:grid-cols-3">
                            {invitations.map((invitation, index) => (
                                <InvitationCard key={invitation.id} invitation={invitation} delay={index * 100} />
                            ))}
                        </div>

                        {/* Footer Stats */}
                        <div className="mt-12 bg-white rounded-2xl shadow-sm border border-primary-100 p-6">
                            <div className="grid grid-cols-1 md:grid-cols-4 gap-6 text-center">
                                <StatCard label="Total Invitations" value={stats.total} color="text-primary" />
                                <StatCard label="Accepted" value={stats.accepted} color="text-accent" />
                                <StatCard label="Declined" value={stats.declined} color="text-red-600" />
                                <StatCard label="Pending" value={stats.pending} color="text-primary" />
                            </div>
                        </div>
                    </>
                )}
            </div>
        </DashboardLayout>
    );
}

function InvitationCard({ invitation, delay }: { invitation: Invitation; delay: number }) {
    const isExpired = new Date(invitation.expires_at) < new Date();
    const status = isExpired ? 'expired' : invitation.status;

    const formatDate = (dateString: string) => {
        const date = new Date(dateString);
        return date.toLocaleDateString('en-US', {
            year: 'numeric',
            month: 'short',
            day: 'numeric',
            hour: 'numeric',
            minute: '2-digit',
        });
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

    const getStatusConfig = (status: string) => {
        switch (status) {
            case 'accepted':
                return {
                    bg: 'bg-accent/10',
                    text: 'text-accent',
                    border: 'border-accent/20',
                    icon: 'check-circle',
                };
            case 'declined':
                return {
                    bg: 'bg-red-100',
                    text: 'text-red-700',
                    border: 'border-red-200',
                    icon: 'times-circle',
                };
            case 'expired':
                return {
                    bg: 'bg-gray-100',
                    text: 'text-gray-600',
                    border: 'border-gray-200',
                    icon: 'clock',
                };
            default:
                return {
                    bg: 'bg-primary/10',
                    text: 'text-primary',
                    border: 'border-primary/20',
                    icon: 'envelope',
                };
        }
    };

    const statusConfig = getStatusConfig(status);

    return (
        <div className="bg-white rounded-2xl shadow-lg border border-primary-100 overflow-hidden hover:shadow-2xl transition-all duration-300 hover:-translate-y-2 flex flex-col group">
            {/* Card Header */}
            <div className="p-6 grow">
                <div className="flex items-start justify-between mb-4">
                    {/* Event Type Icon */}
                    <div className="p-3 rounded-xl bg-primary/10 text-primary group-hover:bg-primary/20 transition-colors">
                        <i
                            className={`fas fa-${
                                invitation.event.mode === 'offline' ? 'map-marker-alt' : 'globe'
                            } w-5 h-5`}
                        ></i>
                    </div>

                    {/* Status Badge */}
                    <span
                        className={`inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold font-montserrat ${statusConfig.bg} ${statusConfig.text} border ${statusConfig.border}`}
                    >
                        <i className={`fas fa-${statusConfig.icon} w-3 h-3 mr-1.5`}></i>
                        {status.charAt(0).toUpperCase() + status.slice(1)}
                    </span>
                </div>

                {/* Event Title */}
                <h3 className="text-xl font-bold text-primary font-montserrat mb-3 leading-tight group-hover:text-primary-600 transition-colors">
                    {invitation.event.title.substring(0, 50)}
                    {invitation.event.title.length > 50 ? '...' : ''}
                </h3>

                {/* Event Date */}
                <div className="flex items-center text-sm text-gray-600 mb-3 font-lato">
                    <i className="fas fa-calendar w-4 h-4 mr-2 text-primary/60"></i>
                    {formatDate(invitation.event.event_date)}
                </div>

                {/* Event Location */}
                <div className="flex items-start text-sm text-gray-600 mb-4 font-lato">
                    <i className="fas fa-map-marker-alt w-4 h-4 mr-2 mt-0.5 shrink-0 text-primary/60"></i>
                    <span className="break-words leading-relaxed">
                        {invitation.event.mode === 'online'
                            ? invitation.event.location
                            : invitation.event.physical_address}
                    </span>
                </div>

                {/* Additional Info */}
                <div className="mt-auto space-y-3">
                    {/* Expiration */}
                    <div className="flex items-center text-xs text-gray-500 font-lato">
                        <i className="fas fa-hourglass-half w-3.5 h-3.5 mr-1.5 text-primary/50"></i>
                        Expires {getExpiresIn(invitation.expires_at)}
                    </div>

                    {/* Suggested Topic */}
                    {invitation.suggested_topic && (
                        <div className="text-sm text-gray-700 font-lato leading-relaxed bg-primary/5 rounded-lg p-3 border border-primary/10">
                            <span className="font-semibold text-primary font-montserrat">Suggested Topic:</span>
                            <span className="block mt-1">
                                {invitation.suggested_topic.substring(0, 100)}
                                {invitation.suggested_topic.length > 100 ? '...' : ''}
                            </span>
                        </div>
                    )}
                </div>
            </div>

            {/* Action Button */}
            <div className="px-6 pb-6 pt-4 border-t border-primary-100 bg-gray-50/50">
                <Link
                    href={route('invitations.show', invitation.id)}
                    className="w-full inline-flex items-center justify-center px-5 py-3 rounded-xl bg-primary hover:bg-primary-600 text-white font-semibold text-sm shadow-md hover:shadow-lg transition-all duration-300 transform hover:scale-105 font-montserrat group/btn"
                >
                    <i className="fas fa-eye w-4 h-4 mr-2 group-hover/btn:scale-110 transition-transform"></i>
                    View Invitation Details
                </Link>
            </div>
        </div>
    );
}

function StatCard({ label, value, color }: { label: string; value: number; color: string }) {
    return (
        <div>
            <div className={`text-2xl font-bold ${color} font-montserrat`}>{value}</div>
            <div className="text-sm text-gray-600 font-lato">{label}</div>
        </div>
    );
}

function EmptyState() {
    return (
        <div className="bg-white rounded-2xl shadow-sm border border-primary-100 p-16 text-center max-w-2xl mx-auto">
            <div className="w-20 h-20 bg-primary/10 rounded-2xl flex items-center justify-center mx-auto mb-6">
                <i className="fas fa-inbox w-10 h-10 text-primary"></i>
            </div>
            <h3 className="text-2xl font-bold text-primary font-montserrat mb-3">No invitations yet</h3>
            <p className="text-gray-600 font-lato leading-relaxed mb-6">
                You'll see exclusive event invitations here when organizers send them your way.
            </p>
            <Link
                href={route('events.index')}
                className="inline-flex items-center gap-2 bg-primary hover:bg-primary-600 text-white font-semibold px-6 py-3 rounded-xl transition-all duration-300 transform hover:scale-105 font-montserrat"
            >
                <i className="fas fa-calendar w-4 h-4"></i>
                Browse Public Events
            </Link>
        </div>
    );
}
