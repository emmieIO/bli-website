import { Head, Link, useForm } from '@inertiajs/react';
import DashboardLayout from '@/Layouts/DashboardLayout';
import { ArrowLeft, Send, Clock, MessageSquare } from 'lucide-react';

export default function Create() {
    const { data, setData, post, processing, errors } = useForm({
        subject: '',
        message: '',
        priority: 'low',
    });

    function submit(e: React.FormEvent<HTMLFormElement>) {
        e.preventDefault();
        post(route('user.tickets.store'));
    }

    return (
        <DashboardLayout>
            <Head title="Create New Ticket" />

            <div className="space-y-6">
                <div className="flex items-center justify-between">
                    <div>
                        <h1 className="text-xl font-semibold tracking-tight text-slate-900">Create New Ticket</h1>
                        <p className="mt-1 text-sm text-slate-500">Open a support request and our team will respond shortly.</p>
                    </div>
                    <Link href={route('user.tickets.index')} className="inline-flex items-center gap-1.5 rounded-md border border-slate-200 bg-white px-3.5 py-2 text-sm font-medium text-slate-600 transition hover:bg-slate-50">
                        <ArrowLeft size={16} /> Back
                    </Link>
                </div>

                <div className="grid gap-6 lg:grid-cols-[1fr_260px]">
                    <div className="rounded-lg border border-slate-200 bg-white">
                        <div className="border-b border-slate-100 px-6 py-4">
                            <h2 className="text-sm font-semibold tracking-tight text-slate-900">Ticket Details</h2>
                            <p className="mt-0.5 text-[13px] text-slate-500">Describe your issue so we can help.</p>
                        </div>

                        <form onSubmit={submit} className="p-6 space-y-5">
                            <div>
                                <label htmlFor="subject" className="block text-[13px] font-medium text-slate-700 mb-1.5">
                                    Subject <span className="text-accent">*</span>
                                </label>
                                <input
                                    type="text" id="subject" required
                                    value={data.subject}
                                    onChange={e => setData('subject', e.target.value)}
                                    placeholder="Brief summary of your issue"
                                    className="w-full rounded-md border border-slate-300 px-3.5 py-2.5 text-sm text-slate-900 placeholder:text-slate-400 outline-none transition focus:border-primary-300 focus:ring-2 focus:ring-primary-500/10"
                                />
                                {errors.subject && <p className="mt-1.5 text-[13px] text-accent">{errors.subject}</p>}
                            </div>

                            <div>
                                <label className="block text-[13px] font-medium text-slate-700 mb-1.5">
                                    Priority <span className="text-accent">*</span>
                                </label>
                                <div className="grid grid-cols-3 gap-2">
                                    {[
                                        { value: 'low', label: 'Low', desc: 'General inquiry', color: 'border-slate-200 bg-white text-slate-600', active: 'border-primary bg-primary-50 text-primary' },
                                        { value: 'medium', label: 'Medium', desc: 'Needs attention', color: 'border-slate-200 bg-white text-slate-600', active: 'border-amber-300 bg-amber-50 text-amber-700' },
                                        { value: 'high', label: 'High', desc: 'Urgent matter', color: 'border-slate-200 bg-white text-slate-600', active: 'border-accent-300 bg-accent-50 text-accent' },
                                    ].map(({ value, label, desc, color, active }) => (
                                        <button
                                            key={value}
                                            type="button"
                                            onClick={() => setData('priority', value)}
                                            className={`rounded-md border px-4 py-3 text-left transition ${
                                                data.priority === value ? active : color
                                            }`}
                                        >
                                            <p className="text-sm font-semibold">{label}</p>
                                            <p className="mt-0.5 text-[11px] opacity-70">{desc}</p>
                                        </button>
                                    ))}
                                </div>
                                {errors.priority && <p className="mt-1.5 text-[13px] text-accent">{errors.priority}</p>}
                            </div>

                            <div>
                                <label htmlFor="message" className="block text-[13px] font-medium text-slate-700 mb-1.5">
                                    Message <span className="text-accent">*</span>
                                </label>
                                <textarea
                                    id="message" rows={7} required
                                    value={data.message}
                                    onChange={e => setData('message', e.target.value)}
                                    placeholder="Describe your issue in detail — include any relevant context, steps to reproduce, or error messages..."
                                    className="w-full rounded-md border border-slate-300 px-3.5 py-2.5 text-sm text-slate-900 placeholder:text-slate-400 outline-none transition focus:border-primary-300 focus:ring-2 focus:ring-primary-500/10 resize-none"
                                />
                                {errors.message && <p className="mt-1.5 text-[13px] text-accent">{errors.message}</p>}
                            </div>

                            <div className="flex items-center justify-between pt-2">
                                <p className="text-[12px] text-slate-400">All fields marked with <span className="text-accent">*</span> are required.</p>
                                <button type="submit" disabled={processing}
                                    className="inline-flex items-center gap-2 rounded-md bg-primary px-5 py-2.5 text-sm font-medium text-white transition hover:bg-primary-600 disabled:opacity-50 shadow-sm">
                                    <Send size={15} />
                                    {processing ? 'Submitting...' : 'Submit Ticket'}
                                </button>
                            </div>
                        </form>
                    </div>

                    <aside className="space-y-4">
                        <div className="rounded-lg border border-slate-200 bg-white p-5">
                            <div className="flex items-center gap-2 mb-4">
                                <span className="flex h-8 w-8 items-center justify-center rounded-lg bg-primary-50 text-primary">
                                    <Clock size={15} />
                                </span>
                                <h3 className="text-sm font-semibold text-slate-900">What to expect</h3>
                            </div>
                            <ul className="space-y-3 text-[13px] text-slate-500 leading-relaxed">
                                <li className="flex gap-2">
                                    <span className="mt-0.5 h-1.5 w-1.5 shrink-0 rounded-full bg-lime-500" />
                                    Response within 24 hours
                                </li>
                                <li className="flex gap-2">
                                    <span className="mt-0.5 h-1.5 w-1.5 shrink-0 rounded-full bg-lime-500" />
                                    Track progress from your tickets list
                                </li>
                                <li className="flex gap-2">
                                    <span className="mt-0.5 h-1.5 w-1.5 shrink-0 rounded-full bg-lime-500" />
                                    Reply directly to keep the conversation going
                                </li>
                            </ul>
                        </div>

                        <div className="rounded-lg border border-slate-200 bg-slate-50 p-5">
                            <div className="flex items-center gap-2 mb-3">
                                <span className="flex h-8 w-8 items-center justify-center rounded-lg bg-lime-50 text-lime-600">
                                    <MessageSquare size={15} />
                                </span>
                                <h3 className="text-sm font-semibold text-slate-900">Tip</h3>
                            </div>
                            <p className="text-[13px] leading-relaxed text-slate-500">
                                Be as specific as possible. Include event names, error messages, or screenshots — the more context, the faster we can help.
                            </p>
                        </div>
                    </aside>
                </div>
            </div>
        </DashboardLayout>
    );
}
