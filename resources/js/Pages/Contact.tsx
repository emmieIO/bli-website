import { Head, useForm, usePage } from '@inertiajs/react';
import GuestLayout from '@/Layouts/GuestLayout';
import { FormEvent } from 'react';
import { MapPin, Mail, Phone, Clock, Send, CheckCircle2 } from 'lucide-react';

export default function Contact() {
    const { flash, contact } = usePage().props as any;
    const { data, setData, post, processing, errors, reset } = useForm({
        name: '',
        email: '',
        message: '',
    });

    const handleSubmit = (e: FormEvent) => {
        e.preventDefault();
        post(route('contact.submit'), {
            onSuccess: () => reset(),
        });
    };

    return (
        <GuestLayout>
            <Head title="Contact Us" />

            <section className="bg-white py-8 md:py-14">
                <div className="section-shell">
                    <div className="mb-14">
                        <div className="inline-flex items-center gap-1.5 rounded-md bg-accent-50 px-2.5 py-1 text-[11px] font-semibold uppercase tracking-wider text-accent">
                            <Mail size={12} /> Get in touch
                        </div>
                        <h1 className="mt-3 text-3xl font-bold tracking-tight text-primary md:text-4xl">
                            Contact Us
                        </h1>
                        <p className="mt-3 max-w-xl text-sm leading-7 text-slate-500 md:text-base">
                            Have questions or want to get in touch? Fill out the form and our team will reach out shortly.
                        </p>
                    </div>

                    <div className="grid gap-8 md:grid-cols-2">
                        <div className="rounded-lg border border-slate-200 bg-white p-6 lg:p-8">
                            <h2 className="text-sm font-semibold tracking-tight text-slate-900 mb-6">Reach us</h2>

                            <div className="space-y-5">
                                {[
                                    { icon: MapPin, label: 'Address', value: contact?.address || '123 Beacon Avenue, Lagos, Nigeria' },
                                    { icon: Mail, label: 'Email', value: contact?.email || 'info@beaconleadership.org' },
                                    { icon: Phone, label: 'Phone', value: contact?.phone || '+234-706-442-5639' },
                                    { icon: Clock, label: 'Office Hours', value: 'Monday – Friday, 9:00 AM – 5:00 PM' },
                                ].map(({ icon: Icon, label, value }) => (
                                    <div key={label} className="flex items-start gap-3">
                                        <span className="flex h-8 w-8 shrink-0 items-center justify-center rounded-lg bg-accent-50 text-accent">
                                            <Icon size={15} />
                                        </span>
                                        <div>
                                            <p className="text-xs font-semibold uppercase tracking-wider text-slate-400">{label}</p>
                                            <p className="mt-0.5 text-sm text-slate-700">{value}</p>
                                        </div>
                                    </div>
                                ))}
                            </div>
                        </div>

                        <form onSubmit={handleSubmit} className="rounded-lg border border-slate-200 bg-white p-6 lg:p-8 space-y-5">
                            <h2 className="text-sm font-semibold tracking-tight text-slate-900 mb-6">Send a message</h2>

                            {flash?.message && (
                                <div className={`flex items-start gap-2.5 rounded-md border px-4 py-3 text-sm ${
                                    flash.type === 'error' ? 'bg-accent-50 border-accent-200 text-accent' : 'bg-lime-50 border-lime-200 text-lime-700'
                                }`}>
                                    <CheckCircle2 size={16} className="shrink-0 mt-0.5" />
                                    <span>{flash.message}</span>
                                </div>
                            )}

                            <div>
                                <label htmlFor="name" className="block text-[13px] font-medium text-slate-700 mb-1.5">Full Name</label>
                                <input type="text" id="name" required value={data.name} onChange={(e) => setData('name', e.target.value)}
                                    className="w-full rounded-md border border-slate-300 px-3.5 py-2.5 text-sm text-slate-900 placeholder:text-slate-400 outline-none transition focus:border-primary-300 focus:ring-2 focus:ring-primary-500/10" />
                                {errors.name && <p className="mt-1.5 text-[13px] text-accent">{errors.name}</p>}
                            </div>

                            <div>
                                <label htmlFor="email" className="block text-[13px] font-medium text-slate-700 mb-1.5">Email</label>
                                <input type="email" id="email" required value={data.email} onChange={(e) => setData('email', e.target.value)}
                                    className="w-full rounded-md border border-slate-300 px-3.5 py-2.5 text-sm text-slate-900 placeholder:text-slate-400 outline-none transition focus:border-primary-300 focus:ring-2 focus:ring-primary-500/10" />
                                {errors.email && <p className="mt-1.5 text-[13px] text-accent">{errors.email}</p>}
                            </div>

                            <div>
                                <label htmlFor="message" className="block text-[13px] font-medium text-slate-700 mb-1.5">Message</label>
                                <textarea id="message" rows={5} required value={data.message} onChange={(e) => setData('message', e.target.value)}
                                    className="w-full rounded-md border border-slate-300 px-3.5 py-2.5 text-sm text-slate-900 placeholder:text-slate-400 outline-none transition focus:border-primary-300 focus:ring-2 focus:ring-primary-500/10 resize-none" />
                                {errors.message && <p className="mt-1.5 text-[13px] text-accent">{errors.message}</p>}
                            </div>

                            <button type="submit" disabled={processing}
                                className="inline-flex items-center gap-2 rounded-md bg-primary px-5 py-2.5 text-sm font-semibold text-white transition hover:bg-primary-600 disabled:opacity-50 shadow-sm">
                                <Send size={15} />
                                {processing ? 'Sending...' : 'Send Message'}
                            </button>
                        </form>
                    </div>
                </div>
            </section>
        </GuestLayout>
    );
}
