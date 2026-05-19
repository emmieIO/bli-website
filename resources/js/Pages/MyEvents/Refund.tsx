import { Head, Link, useForm, usePage } from '@inertiajs/react';
import DashboardLayout from '@/Layouts/DashboardLayout';
import Button from '@/Components/Button';

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
    start_date: string;
    end_date: string;
    location?: string | null;
    physical_address?: string | null;
    journey_status: 'upcoming' | 'ongoing' | 'ended';
    registration_status: 'registered' | 'waitlisted' | 'refunded' | 'attended' | 'no_show';
    latest_transaction?: Transaction | null;
    refund_request?: RefundRequest | null;
}

interface Props {
    event: Event;
}

export default function Refund({ event }: Props) {
    const { sideLinks } = usePage().props as any;
    const { data, setData, post, processing, errors } = useForm({
        reason: event.refund_request?.reason ?? '',
    });

    const paymentAmount = event.latest_transaction
        ? `${event.latest_transaction.currency} ${Number(event.latest_transaction.amount).toLocaleString()}`
        : 'No payment found';

    const canSubmitRequest = Boolean(
        event.latest_transaction?.status === 'successful'
        && event.registration_status !== 'refunded'
        && event.refund_request?.status !== 'pending'
        && event.refund_request?.status !== 'approved',
    );

    const headerTone = event.refund_request?.status === 'approved'
        ? 'border-emerald-200 bg-emerald-50'
        : event.refund_request?.status === 'declined'
            ? 'border-rose-200 bg-rose-50'
            : 'border-amber-200 bg-amber-50';

    const formatDate = (value?: string | null) => {
        if (!value) {
            return 'Not available yet';
        }

        return new Intl.DateTimeFormat('en-US', {
            weekday: 'long',
            month: 'long',
            day: 'numeric',
            year: 'numeric',
            hour: 'numeric',
            minute: '2-digit',
        }).format(new Date(value));
    };

    const submitRefundRequest = () => {
        post(route('user.request-event-refund', event.slug));
    };

    return (
        <DashboardLayout sideLinks={sideLinks}>
            <Head title={`Refund - ${event.title}`} />

            <div className="space-y-8">
                <section className={`overflow-hidden rounded-[28px] border shadow-sm ${headerTone}`}>
                    <div className="grid gap-0 lg:grid-cols-[minmax(0,1.4fr)_minmax(320px,0.8fr)]">
                        <div className="space-y-5 px-6 py-7 lg:px-8 lg:py-9">
                            <Link href={route('user.events.show', event.slug)} className="inline-flex items-center gap-2 text-sm font-medium text-slate-500 transition hover:text-primary">
                                <i className="fas fa-arrow-left text-xs"></i>
                                Back to event workspace
                            </Link>

                            <div className="space-y-3">
                                <div className="flex flex-wrap items-center gap-2">
                                    <span className="inline-flex rounded-full bg-white/80 px-3 py-1 text-xs font-semibold uppercase tracking-[0.12em] text-slate-600">
                                        Refund Center
                                    </span>
                                    <span className="inline-flex rounded-full bg-slate-900 px-3 py-1 text-xs font-semibold capitalize text-white">
                                        {event.refund_request?.status ?? 'not requested'}
                                    </span>
                                </div>
                                <h1 className="max-w-2xl text-3xl font-bold text-primary md:text-4xl">
                                    {event.title}
                                </h1>
                                <p className="max-w-2xl text-sm leading-7 text-slate-600 md:text-base">
                                    {event.refund_request?.status === 'pending'
                                        ? 'Your request is in review. The event team will confirm remittance before they approve or decline it.'
                                        : event.refund_request?.status === 'approved'
                                            ? 'This refund request has been approved and your registration has been moved into a refunded state.'
                                            : event.refund_request?.status === 'declined'
                                                ? 'This request was declined. You can review the note below and contact the event team if you need clarification.'
                                                : 'Use this page to request a refund without overloading the event workspace. Share a short reason and we will route it to the event team.'}
                                </p>
                            </div>

                            <div className="grid gap-3 sm:grid-cols-3">
                                <MetricCard label="Amount" value={paymentAmount} />
                                <MetricCard label="Paid on" value={formatDate(event.latest_transaction?.paid_at)} />
                                <MetricCard label="Registration" value={event.registration_status === 'registered' ? 'confirmed' : event.registration_status.replace('_', ' ')} />
                            </div>
                        </div>

                        <div className="border-t border-white/70 bg-white/75 px-6 py-7 backdrop-blur-sm lg:border-l lg:border-t-0 lg:px-8 lg:py-9">
                            <p className="text-[11px] font-semibold uppercase tracking-[0.18em] text-slate-500">
                                How It Works
                            </p>
                            <div className="mt-4 space-y-4">
                                <JourneyStep
                                    number="1"
                                    title="Request submitted"
                                    description="You add context for the cancellation so the team can review the case quickly."
                                />
                                <JourneyStep
                                    number="2"
                                    title="Admin review"
                                    description="The event team checks policy and confirms remittance before changing anything."
                                />
                                <JourneyStep
                                    number="3"
                                    title="Final outcome"
                                    description="You receive a clear update once the request is approved or declined."
                                />
                            </div>
                        </div>
                    </div>
                </section>

                <section className="grid gap-6 lg:grid-cols-[minmax(0,1.2fr)_360px]">
                    <div className="space-y-6">
                        {canSubmitRequest && (
                            <div className="rounded-[28px] border border-slate-200 bg-white p-6 shadow-sm lg:p-8">
                                <div className="flex flex-col gap-3 sm:flex-row sm:items-start sm:justify-between">
                                    <div>
                                        <p className="text-[11px] font-semibold uppercase tracking-[0.18em] text-slate-500">
                                            Refund Request
                                        </p>
                                        <h2 className="mt-2 text-2xl font-semibold text-primary">
                                            Tell us why you need the refund
                                        </h2>
                                        <p className="mt-2 max-w-2xl text-sm leading-6 text-slate-600">
                                            A short note helps the event team process the request faster. Approval still happens manually after remittance is confirmed.
                                        </p>
                                    </div>
                                    <div className="rounded-2xl border border-amber-200 bg-amber-50 px-4 py-3 text-sm leading-6 text-amber-800">
                                        Your seat stays under review until the request is resolved.
                                    </div>
                                </div>

                                <form
                                    onSubmit={(e) => {
                                        e.preventDefault();
                                        submitRefundRequest();
                                    }}
                                    className="mt-6 space-y-5"
                                >
                                    <div className="space-y-2">
                                        <label htmlFor="reason" className="block text-sm font-medium text-slate-700">
                                            Reason for request
                                        </label>
                                        <textarea
                                            id="reason"
                                            value={data.reason}
                                            onChange={(e) => setData('reason', e.target.value)}
                                            rows={6}
                                            maxLength={1000}
                                            placeholder="Example: I have an unavoidable schedule conflict and will not be able to attend. Please review my refund request."
                                            className="w-full rounded-3xl border border-slate-200 bg-slate-50 px-5 py-4 text-sm leading-6 text-slate-700 outline-none transition placeholder:text-slate-400 focus:border-primary focus:bg-white focus:ring-2 focus:ring-primary/10"
                                        />
                                        <div className="flex items-center justify-between text-xs text-slate-500">
                                            <span>Optional, but recommended for quicker review.</span>
                                            <span>{data.reason.length}/1000</span>
                                        </div>
                                        {errors.reason && (
                                            <p className="text-sm text-red-600">{errors.reason}</p>
                                        )}
                                    </div>

                                    <div className="flex flex-col-reverse gap-3 border-t border-slate-100 pt-5 sm:flex-row sm:justify-end">
                                        <Link
                                            href={route('user.events.show', event.slug)}
                                            className="inline-flex items-center justify-center rounded-lg border border-slate-300 px-6 py-3 text-sm font-medium text-slate-700 transition hover:bg-slate-50"
                                        >
                                            Not now
                                        </Link>
                                        <Button type="submit" loading={processing} className="justify-center">
                                            Submit refund request
                                        </Button>
                                    </div>
                                </form>
                            </div>
                        )}

                        {event.refund_request && (
                            <div className="rounded-[28px] border border-slate-200 bg-white p-6 shadow-sm lg:p-8">
                                <div className="flex flex-col gap-3 sm:flex-row sm:items-start sm:justify-between">
                                    <div>
                                        <p className="text-[11px] font-semibold uppercase tracking-[0.18em] text-slate-500">
                                            Request Status
                                        </p>
                                        <h2 className="mt-2 text-2xl font-semibold text-primary">
                                            Refund timeline
                                        </h2>
                                    </div>
                                    <span className={`inline-flex rounded-full px-3 py-1 text-xs font-semibold capitalize ${
                                        event.refund_request.status === 'approved'
                                            ? 'bg-emerald-100 text-emerald-700'
                                            : event.refund_request.status === 'declined'
                                                ? 'bg-rose-100 text-rose-700'
                                                : 'bg-amber-100 text-amber-700'
                                    }`}>
                                        {event.refund_request.status}
                                    </span>
                                </div>

                                <div className="mt-6 grid gap-4 md:grid-cols-2">
                                    <StatusBlock
                                        label="Requested on"
                                        value={formatDate(event.refund_request.requested_at)}
                                    />
                                    <StatusBlock
                                        label="Reviewed on"
                                        value={formatDate(event.refund_request.reviewed_at)}
                                    />
                                    <StatusBlock
                                        label="Your note"
                                        value={event.refund_request.reason || 'No reason was provided with this request.'}
                                    />
                                    <StatusBlock
                                        label="Admin note"
                                        value={event.refund_request.admin_note || 'No admin note has been added yet.'}
                                    />
                                </div>
                            </div>
                        )}
                    </div>

                    <aside className="space-y-6">
                        <div className="rounded-[28px] border border-slate-200 bg-white p-6 shadow-sm">
                            <p className="text-[11px] font-semibold uppercase tracking-[0.18em] text-slate-500">
                                Event Snapshot
                            </p>
                            <div className="mt-5 space-y-4">
                                <SidebarRow label="Event" value={event.title} />
                                <SidebarRow label="Start" value={formatDate(event.start_date)} />
                                <SidebarRow label="End" value={formatDate(event.end_date)} />
                                <SidebarRow label="Venue" value={event.physical_address || event.location || 'Shared later'} />
                                <SidebarRow label="Payment ref" value={event.latest_transaction?.payment_ref || 'Not available'} />
                            </div>
                        </div>

                        <div className="rounded-[28px] border border-slate-200 bg-slate-950 p-6 text-white shadow-sm">
                            <p className="text-[11px] font-semibold uppercase tracking-[0.18em] text-slate-400">
                                Need Help?
                            </p>
                            <h3 className="mt-3 text-xl font-semibold">
                                Questions about the refund process
                            </h3>
                            <p className="mt-3 text-sm leading-6 text-slate-300">
                                If your case is urgent, reach out to the event team from the main workspace after you submit the request.
                            </p>
                            <Link
                                href={route('user.events.show', event.slug)}
                                className="mt-5 inline-flex items-center gap-2 text-sm font-semibold text-white transition hover:text-secondary"
                            >
                                Return to workspace
                                <i className="fas fa-arrow-right text-xs"></i>
                            </Link>
                        </div>
                    </aside>
                </section>
            </div>
        </DashboardLayout>
    );
}

function MetricCard({ label, value }: { label: string; value: string }) {
    return (
        <div className="rounded-2xl border border-white/70 bg-white/85 px-4 py-4 backdrop-blur-sm">
            <p className="text-[11px] font-semibold uppercase tracking-[0.16em] text-slate-500">{label}</p>
            <p className="mt-2 text-sm font-medium text-primary">{value}</p>
        </div>
    );
}

function JourneyStep({
    number,
    title,
    description,
}: {
    number: string;
    title: string;
    description: string;
}) {
    return (
        <div className="rounded-2xl border border-slate-200 bg-white px-4 py-4">
            <div className="flex items-center gap-3">
                <span className="flex h-8 w-8 items-center justify-center rounded-full bg-primary/10 text-sm font-semibold text-primary">
                    {number}
                </span>
                <p className="text-sm font-semibold text-slate-900">{title}</p>
            </div>
            <p className="mt-2 text-sm leading-6 text-slate-600">{description}</p>
        </div>
    );
}

function StatusBlock({ label, value }: { label: string; value: string }) {
    return (
        <div className="rounded-3xl border border-slate-200 bg-slate-50 px-5 py-4">
            <p className="text-[11px] font-semibold uppercase tracking-[0.16em] text-slate-500">{label}</p>
            <p className="mt-3 text-sm leading-6 text-slate-700">{value}</p>
        </div>
    );
}

function SidebarRow({ label, value }: { label: string; value: string }) {
    return (
        <div className="grid gap-1 border-b border-slate-100 pb-4 last:border-b-0 last:pb-0">
            <p className="text-[11px] font-semibold uppercase tracking-[0.16em] text-slate-500">{label}</p>
            <p className="text-sm leading-6 text-primary">{value}</p>
        </div>
    );
}
