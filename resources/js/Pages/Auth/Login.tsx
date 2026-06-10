import { FormEventHandler, useState } from 'react';
import { Head, Link, useForm } from '@inertiajs/react';
import AuthLayout from '@/Layouts/AuthLayout';

export default function Login({ status }: { status?: string }) {
    const { data, setData, post, processing, errors } = useForm({
        email: '',
        password: '',
        remember: false,
    });

    const [showPassword, setShowPassword] = useState(false);

    const submit: FormEventHandler = (e) => {
        e.preventDefault();
        post(route('login.store'));
    };

    return (
        <AuthLayout title="Sign in" description="Welcome back — enter your credentials to continue.">
            <Head title="Login" />

            {status && (
                <div className="mb-5 rounded-md bg-primary-50 px-4 py-3 text-sm text-primary">
                    {status}
                </div>
            )}

            <form onSubmit={submit} className="space-y-4">
                <div>
                    <label htmlFor="email" className="block text-[13px] font-medium text-slate-700 mb-1.5">Email Address</label>
                    <input type="email" id="email" value={data.email} onChange={(e) => setData('email', e.target.value)} required placeholder="you@example.com"
                        className="w-full rounded-md border border-slate-300 px-3.5 py-2.5 text-sm text-slate-900 placeholder:text-slate-400 outline-none transition focus:border-primary-300 focus:ring-2 focus:ring-primary-500/10" />
                    {errors.email && <p className="mt-1.5 text-[13px] text-accent">{errors.email}</p>}
                </div>

                <div>
                    <div className="flex items-center justify-between mb-1.5">
                        <label htmlFor="password" className="text-[13px] font-medium text-slate-700">Password</label>
                        <Link href={route('password.request')} className="text-[13px] font-medium text-primary hover:text-accent">Forgot?</Link>
                    </div>
                    <div className="relative">
                        <input type={showPassword ? 'text' : 'password'} id="password" value={data.password} onChange={(e) => setData('password', e.target.value)} required placeholder="Enter your password"
                            className="w-full rounded-md border border-slate-300 px-3.5 py-2.5 pr-10 text-sm text-slate-900 placeholder:text-slate-400 outline-none transition focus:border-primary-300 focus:ring-2 focus:ring-primary-500/10" />
                        <button type="button" onClick={() => setShowPassword(!showPassword)} className="absolute inset-y-0 right-0 flex items-center pr-3 text-slate-400 hover:text-slate-600">
                            <i className={`fas ${showPassword ? 'fa-eye-slash' : 'fa-eye'} text-sm`} />
                        </button>
                    </div>
                    {errors.password && <p className="mt-1.5 text-[13px] text-accent">{errors.password}</p>}
                </div>

                <label className="flex items-center gap-2.5 cursor-pointer">
                    <input type="checkbox" checked={data.remember} onChange={(e) => setData('remember', e.target.checked)}
                        className="h-4 w-4 rounded border-slate-300 text-primary focus:ring-primary-500/20" />
                    <span className="text-[13px] text-slate-600">Remember me</span>
                </label>

                <button type="submit" disabled={processing} className="w-full rounded-md bg-accent px-4 py-3 text-sm font-semibold text-white transition hover:bg-accent-600 disabled:opacity-50 shadow-sm">
                    {processing ? 'Signing in...' : 'Sign in'}
                </button>
            </form>

            <p className="mt-6 text-center text-sm text-slate-500">
                Don't have an account?{' '}
                <Link href={route('register')} className="font-semibold text-primary hover:text-accent">Create account</Link>
            </p>
        </AuthLayout>
    );
}
