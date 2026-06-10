import { FormEventHandler } from 'react';
import { Head, Link, useForm } from '@inertiajs/react';
import AuthLayout from '@/Layouts/AuthLayout';

export default function ForgotPassword({ status }: { status?: string }) {
    const { data, setData, post, processing, errors } = useForm({ email: '' });

    const submit: FormEventHandler = (e) => {
        e.preventDefault();
        post(route('password.email'));
    };

    return (
        <AuthLayout title="Forgot Password" description="Enter your email and we'll send a reset link.">
            <Head title="Forgot Password" />

            {status && (
                <div className="mb-5 rounded-md bg-lime-50 border border-lime-200 px-4 py-3 text-sm text-lime-700">
                    {status}
                </div>
            )}

            <form onSubmit={submit} className="space-y-4">
                <div>
                    <label htmlFor="email" className="block text-[13px] font-medium text-slate-700 mb-1.5">Email Address</label>
                    <input type="email" id="email" value={data.email} onChange={(e) => setData('email', e.target.value)} required autoFocus placeholder="Enter your registered email"
                        className="w-full rounded-md border border-slate-300 px-3.5 py-2.5 text-sm text-slate-900 placeholder:text-slate-400 outline-none transition focus:border-primary-300 focus:ring-2 focus:ring-primary-500/10" />
                    {errors.email && <p className="mt-1.5 text-[13px] text-accent">{errors.email}</p>}
                </div>

                <button type="submit" disabled={processing} className="w-full rounded-md bg-accent px-4 py-3 text-sm font-semibold text-white transition hover:bg-accent-600 disabled:opacity-50 shadow-sm">
                    {processing ? 'Sending...' : 'Send Reset Link'}
                </button>
            </form>

            <p className="mt-6 text-center text-sm text-slate-500">
                <Link href={route('login')} className="font-semibold text-primary hover:text-accent">Back to sign in</Link>
            </p>
        </AuthLayout>
    );
}
