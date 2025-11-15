import { Head, router, usePage } from '@inertiajs/react';
import DashboardLayout from '@/Layouts/DashboardLayout';
import { useState, FormEvent } from 'react';
import Button from '@/Components/Button';
import Textarea from '@/Components/Textarea';

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
    status: 'pending' | 'accepted' | 'declined';
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

    const handleAccept = (e: FormEvent) => {
        e.preventDefault();
        setIsProcessing(true);
        router.post(
            route('invitations.accept', { event: event.id, invite: invite.id }),
            {},
            {
                preserveScroll: true,
                onFinish: () => {
                    setIsProcessing(false);
                    setShowAcceptModal(false);
                },
            }
        );
    };

    const handleDecline = (e: FormEvent) => {
        e.preventDefault();
        if (!declineFeedback.trim()) return;

        setIsProcessing(true);
        router.patch(
            route('invitations.accept', { event: event.id, invite: invite.id }),
            { feedback: declineFeedback },
            {
                preserveScroll: true,
                onFinish: () => {
                    setIsProcessing(false);
                    setShowDeclineModal(false);
                    setDeclineFeedback('');
                },
            }
        );
    };

    return (
        <DashboardLayout sideLinks={sideLinks}>
            <Head title={`Invitation: ${event.title} - Beacon Leadership Institute`} />

            <div className="py-10">
                {/* Event Card */}
                <div className="bg-white rounded-3xl shadow-xl border border-gray-50 overflow-hidden mb-10 transition-all duration-300 hover:shadow-2xl">
                    <div className="p-8">
                        <div className="flex items-start sm:items-center space-x-4 mb-6">
                            <div className="p-3 bg-primary/10 rounded-xl">
                                <i className="fas fa-calendar w-6 h-6 text-primary"></i>
                            </div>
                            <div>
                                <h1 className="text-3xl font-extrabold text-gray-900 tracking-tight leading-tight font-montserrat">
                                    {event.title}
                                </h1>
                                <span
                                    className={`inline-flex items-center mt-2 px-3 py-1 rounded-full text-xs font-medium font-montserrat ${
                                        event.mode === 'offline'
                                            ? 'bg-blue-100 text-blue-800'
                                            : 'bg-purple-100 text-purple-800'
                                    }`}
                                >
                                    <i
                                        className={`fas fa-${
                                            event.mode === 'offline' ? 'map-marker-alt' : 'globe'
                                        } w-3 h-3 mr-1`}
                                    ></i>
                                    {event.mode === 'offline' ? 'In-Person Event' : 'Online Event'}
                                </span>
                            </div>
                        </div>

                        {event.description && (
                            <p className="text-gray-700 leading-relaxed mb-8 text-lg font-lato">
                                {event.description.substring(0, 280)}
                                {event.description.length > 280 ? '...' : ''}
                            </p>
                        )}

                        <div className="grid sm:grid-cols-2 lg:grid-cols-3 gap-5 text-sm">
                            <div className="flex items-start space-x-3 p-4 bg-gray-50 rounded-xl">
                                <i className="fas fa-map-marker-alt w-5 h-5 text-gray-500 mt-0.5 flex-shrink-0"></i>
                                <div>
                                    <p className="font-medium text-gray-900 font-montserrat">Location</p>
                                    <p className="text-gray-600 mt-1 font-lato">
                                        {event.mode === 'offline' ? event.physical_address : event.location}
                                    </p>
                                </div>
                            </div>

                            {event.contact_email && (
                                <div className="flex items-start space-x-3 p-4 bg-gray-50 rounded-xl">
                                    <i className="fas fa-envelope w-5 h-5 text-gray-500 mt-0.5 flex-shrink-0"></i>
                                    <div>
                                        <p className="font-medium text-gray-900 font-montserrat">Contact</p>
                                        <p className="text-gray-600 mt-1 break-all font-lato">{event.contact_email}</p>
                                    </div>
                                </div>
                            )}

                            <div className="flex items-start space-x-3 p-4 bg-gray-50 rounded-xl">
                                <i className="fas fa-clock w-5 h-5 text-gray-500 mt-0.5 flex-shrink-0"></i>
                                <div>
                                    <p className="font-medium text-gray-900 font-montserrat">Date & Time</p>
                                    <p className="text-gray-600 mt-1 font-lato">
                                        {formatDate(event.start_date)}
                                        <br />
                                        <span className="text-xs text-gray-500">to</span>
                                        <br />
                                        {formatDate(event.end_date)}
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                {/* Invitation Card */}
                <div className="bg-white rounded-3xl shadow-xl border border-gray-50 overflow-hidden transition-all duration-300 hover:shadow-2xl">
                    <div className="p-8">
                        <div className="flex items-start sm:items-center space-x-4 mb-8">
                            <div className="p-3 bg-primary/10 rounded-xl">
                                <i className="fas fa-user w-6 h-6 text-primary"></i>
                            </div>
                            <div>
                                <h2 className="text-2xl font-bold text-gray-900 font-montserrat">
                                    Your Invitation Details
                                </h2>
                                <p className="text-gray-600 mt-1 font-lato">
                                    Please review the details below before responding.
                                </p>
                            </div>
                        </div>

                        <div className="space-y-6 mb-8">
                            {invite.suggested_topic && (
                                <InviteDetail label="Suggested Topic" value={invite.suggested_topic} />
                            )}
                            {invite.suggested_duration && (
                                <InviteDetail
                                    label="Suggested Duration"
                                    value={`${invite.suggested_duration} minutes`}
                                />
                            )}
                            {invite.audience_expectations && (
                                <InviteDetail label="Audience Expectations" value={invite.audience_expectations} />
                            )}
                            {invite.expected_format && (
                                <InviteDetail label="Expected Format" value={invite.expected_format} />
                            )}
                            {invite.special_instructions && (
                                <InviteDetail label="Special Instructions" value={invite.special_instructions} />
                            )}

                            <div className="pt-4 border-t border-gray-100">
                                <div className="flex items-center space-x-2">
                                    <i className="fas fa-hourglass-half w-4 h-4 text-amber-500"></i>
                                    <span className="text-sm font-medium text-gray-700 font-lato">
                                        Expires {getExpiresIn(invite.expires_at)}
                                    </span>
                                    {isExpired && (
                                        <span className="ml-2 inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800 font-montserrat">
                                            Expired
                                        </span>
                                    )}
                                </div>
                            </div>
                        </div>

                        {/* Action Buttons */}
                        {isExpired ? (
                            <div className="bg-amber-50 border border-amber-200 rounded-xl p-5 text-center">
                                <i className="fas fa-exclamation-triangle w-10 h-10 text-amber-500 mx-auto mb-3"></i>
                                <h3 className="font-semibold text-amber-800 font-montserrat">
                                    This invitation has expired
                                </h3>
                                <p className="text-amber-700 mt-1 text-sm font-lato">
                                    You can no longer respond to this invitation.
                                </p>
                            </div>
                        ) : (
                            <div className="flex flex-col sm:flex-row justify-center sm:justify-start gap-4 pt-6 border-t border-gray-100">
                                <Button
                                    onClick={() => setShowAcceptModal(true)}
                                    className="bg-green-600 hover:bg-green-700 px-8 py-3.5"
                                    icon="check"
                                >
                                    Accept Invitation
                                </Button>

                                <Button
                                    onClick={() => setShowDeclineModal(true)}
                                    variant="danger"
                                    className="px-8 py-3.5"
                                    icon="times"
                                >
                                    Decline Invitation
                                </Button>
                            </div>
                        )}
                    </div>
                </div>
            </div>

            {/* Accept Modal */}
            {showAcceptModal && (
                <Modal
                    title="Accept Invitation"
                    icon="check-circle"
                    iconColor="text-green-600"
                    onClose={() => setShowAcceptModal(false)}
                >
                    <p className="text-gray-700 mb-6 font-lato">
                        Are you sure you want to accept this invitation? This action cannot be undone.
                    </p>
                    <form onSubmit={handleAccept} className="flex justify-end gap-3">
                        <Button type="button" variant="secondary" onClick={() => setShowAcceptModal(false)}>
                            Cancel
                        </Button>
                        <Button type="submit" className="bg-green-600 hover:bg-green-700" loading={isProcessing}>
                            Accept Invitation
                        </Button>
                    </form>
                </Modal>
            )}

            {/* Decline Modal */}
            {showDeclineModal && (
                <Modal
                    title="Decline Invitation"
                    icon="times-circle"
                    iconColor="text-red-600"
                    onClose={() => setShowDeclineModal(false)}
                >
                    <p className="text-gray-700 mb-4 font-lato">
                        We appreciate your consideration. Kindly share your reason for declining this invitation:
                    </p>
                    <form onSubmit={handleDecline} className="space-y-4">
                        <Textarea
                            rows={4}
                            value={declineFeedback}
                            onChange={(e) => setDeclineFeedback(e.target.value)}
                            placeholder="Please provide your reason..."
                            required
                        />
                        <div className="flex justify-end gap-3">
                            <Button type="button" variant="secondary" onClick={() => setShowDeclineModal(false)}>
                                Cancel
                            </Button>
                            <Button type="submit" variant="danger" loading={isProcessing}>
                                Decline Invitation
                            </Button>
                        </div>
                    </form>
                </Modal>
            )}
        </DashboardLayout>
    );
}

function InviteDetail({ label, value }: { label: string; value: string }) {
    return (
        <div className="border-l-4 border-primary/20 pl-5 py-2">
            <p className="text-sm font-semibold text-gray-500 uppercase tracking-wide font-montserrat">{label}</p>
            <p className="text-gray-800 mt-1 leading-relaxed font-lato">{value}</p>
        </div>
    );
}

function Modal({
    title,
    icon,
    iconColor,
    children,
    onClose,
}: {
    title: string;
    icon: string;
    iconColor: string;
    children: React.ReactNode;
    onClose: () => void;
}) {
    return (
        <div className="fixed inset-0 z-50 flex items-center justify-center bg-black/30">
            <div className="relative bg-white rounded-lg shadow-xl p-6 w-full max-w-md mx-4">
                <button
                    type="button"
                    onClick={onClose}
                    className="absolute top-3 right-3 text-gray-400 hover:bg-gray-200 hover:text-gray-900 rounded-lg text-sm w-8 h-8 inline-flex justify-center items-center"
                >
                    <i className="fas fa-times w-3 h-3"></i>
                </button>

                <div className="text-center mb-6">
                    <i className={`fas fa-${icon} w-12 h-12 ${iconColor} mx-auto mb-4`}></i>
                    <h3 className="text-lg font-semibold text-gray-800 font-montserrat">{title}</h3>
                </div>

                {children}
            </div>
        </div>
    );
}
