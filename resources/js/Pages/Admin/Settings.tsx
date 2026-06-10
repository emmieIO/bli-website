import { Head, useForm, usePage } from '@inertiajs/react';
import DashboardLayout from '@/Layouts/DashboardLayout';
import { FormEvent } from 'react';
import { Save } from 'lucide-react';

interface SettingsProps {
    settings: {
        contact_email: string;
        contact_phone: string;
        contact_address: string;
    };
}

export default function Settings({ settings }: SettingsProps) {
    const { sideLinks, flash } = usePage().props as any;

    const { data, setData, post, processing, errors } = useForm({
        contact_email: settings.contact_email || '',
        contact_phone: settings.contact_phone || '',
        contact_address: settings.contact_address || '',
    });

    const handleSubmit = (e: FormEvent) => {
        e.preventDefault();
        post(route('admin.settings.update'));
    };

    return (
        <DashboardLayout sideLinks={sideLinks}>
            <Head title="Settings" />

            <div className="mx-auto max-w-2xl space-y-6">
                <div>
                    <h1 className="text-xl font-semibold tracking-tight text-slate-900">Settings</h1>
                    <p className="mt-1 text-sm text-slate-500">Manage application contact details.</p>
                </div>

                {flash?.message && (
                    <div className={`rounded-md border px-4 py-3 text-sm ${
                        flash.type === 'error' ? 'bg-accent-50 border-accent-200 text-accent' : 'bg-lime-50 border-lime-200 text-lime-700'
                    }`}>
                        {flash.message}
                    </div>
                )}

                <div className="rounded-lg border border-slate-200 bg-white">
                    <div className="border-b border-slate-100 px-6 py-4">
                        <h2 className="text-sm font-semibold tracking-tight text-slate-900">Contact Information</h2>
                        <p className="mt-0.5 text-[13px] text-slate-500">These details appear on the contact page, receipts, and notification emails.</p>
                    </div>

                    <form onSubmit={handleSubmit} className="p-6 space-y-5">
                        <div>
                            <label htmlFor="contact_email" className="block text-[13px] font-medium text-slate-700 mb-1.5">
                                Contact Email <span className="text-accent">*</span>
                            </label>
                            <input type="email" id="contact_email" required
                                value={data.contact_email}
                                onChange={(e) => setData('contact_email', e.target.value)}
                                placeholder="info@beaconleadership.org"
                                className="w-full rounded-md border border-slate-300 px-3.5 py-2.5 text-sm text-slate-900 placeholder:text-slate-400 outline-none transition focus:border-primary-300 focus:ring-2 focus:ring-primary-500/10"
                            />
                            {errors.contact_email && <p className="mt-1.5 text-[13px] text-accent">{errors.contact_email}</p>}
                            <p className="mt-1 text-[11px] text-slate-400">Contact form submissions and notifications will be sent here.</p>
                        </div>

                        <div>
                            <label htmlFor="contact_phone" className="block text-[13px] font-medium text-slate-700 mb-1.5">
                                Contact Phone <span className="text-accent">*</span>
                            </label>
                            <input type="text" id="contact_phone" required
                                value={data.contact_phone}
                                onChange={(e) => setData('contact_phone', e.target.value)}
                                placeholder="+234-706-442-5639"
                                className="w-full rounded-md border border-slate-300 px-3.5 py-2.5 text-sm text-slate-900 placeholder:text-slate-400 outline-none transition focus:border-primary-300 focus:ring-2 focus:ring-primary-500/10"
                            />
                            {errors.contact_phone && <p className="mt-1.5 text-[13px] text-accent">{errors.contact_phone}</p>}
                        </div>

                        <div>
                            <label htmlFor="contact_address" className="block text-[13px] font-medium text-slate-700 mb-1.5">
                                Address <span className="text-accent">*</span>
                            </label>
                            <textarea id="contact_address" rows={3} required
                                value={data.contact_address}
                                onChange={(e) => setData('contact_address', e.target.value)}
                                placeholder="123 Beacon Avenue, Lagos, Nigeria"
                                className="w-full rounded-md border border-slate-300 px-3.5 py-2.5 text-sm text-slate-900 placeholder:text-slate-400 outline-none transition focus:border-primary-300 focus:ring-2 focus:ring-primary-500/10 resize-none"
                            />
                            {errors.contact_address && <p className="mt-1.5 text-[13px] text-accent">{errors.contact_address}</p>}
                        </div>

                        <button type="submit" disabled={processing}
                            className="inline-flex items-center gap-2 rounded-md bg-primary px-5 py-2.5 text-sm font-medium text-white transition hover:bg-primary-600 disabled:opacity-50 shadow-sm">
                            <Save size={15} />
                            {processing ? 'Saving...' : 'Save Settings'}
                        </button>
                    </form>
                </div>
            </div>
        </DashboardLayout>
    );
}
