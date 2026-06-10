import { FormEventHandler, useState } from 'react';
import { Head, Link, useForm } from '@inertiajs/react';
import AuthLayout from '@/Layouts/AuthLayout';

export default function Register() {
    const { data, setData, post, processing, errors } = useForm({
        name: '',
        email: '',
        phone: '',
        password: '',
        password_confirmation: '',
        agree_terms: false,
        marketing_consent: false,
    });

    const [showPassword, setShowPassword] = useState(false);

    const submit: FormEventHandler = (e) => {
        e.preventDefault();
        post(route('register.store'));
    };

    return (
        <AuthLayout title="Create account" description="Set up your account to get started." wide>
            <Head title="Register" />

            <form onSubmit={submit} className="space-y-4">
                <div>
                    <label htmlFor="name" className="block text-[13px] font-medium text-slate-700 mb-1.5">Full Name</label>
                    <input type="text" id="name" value={data.name} onChange={(e) => setData('name', e.target.value)} required placeholder="Enter your full name"
                        className="w-full rounded-md border border-slate-300 px-3.5 py-2.5 text-sm text-slate-900 placeholder:text-slate-400 outline-none transition focus:border-primary-300 focus:ring-2 focus:ring-primary-500/10" />
                    {errors.name && <p className="mt-1.5 text-[13px] text-accent">{errors.name}</p>}
                </div>

                <div>
                    <label htmlFor="email" className="block text-[13px] font-medium text-slate-700 mb-1.5">Email Address</label>
                    <input type="email" id="email" value={data.email} onChange={(e) => setData('email', e.target.value)} required placeholder="you@example.com"
                        className="w-full rounded-md border border-slate-300 px-3.5 py-2.5 text-sm text-slate-900 placeholder:text-slate-400 outline-none transition focus:border-primary-300 focus:ring-2 focus:ring-primary-500/10" />
                    {errors.email && <p className="mt-1.5 text-[13px] text-accent">{errors.email}</p>}
                </div>

                <div>
                    <label htmlFor="phone" className="block text-[13px] font-medium text-slate-700 mb-1.5">Phone Number</label>
                    <input type="text" id="phone" value={data.phone} onChange={(e) => setData('phone', e.target.value)} placeholder="+234 801 234 5678"
                        className="w-full rounded-md border border-slate-300 px-3.5 py-2.5 text-sm text-slate-900 placeholder:text-slate-400 outline-none transition focus:border-primary-300 focus:ring-2 focus:ring-primary-500/10" />
                    {errors.phone && <p className="mt-1.5 text-[13px] text-accent">{errors.phone}</p>}
                </div>

                <div>
                    <label htmlFor="password" className="block text-[13px] font-medium text-slate-700 mb-1.5">Password</label>
                    <div className="relative">
                        <input type={showPassword ? 'text' : 'password'} id="password" value={data.password} onChange={(e) => setData('password', e.target.value)} required placeholder="At least 8 characters"
                            className="w-full rounded-md border border-slate-300 px-3.5 py-2.5 pr-10 text-sm text-slate-900 placeholder:text-slate-400 outline-none transition focus:border-primary-300 focus:ring-2 focus:ring-primary-500/10" />
                        <button type="button" onClick={() => setShowPassword(!showPassword)} className="absolute inset-y-0 right-0 flex items-center pr-3 text-slate-400 hover:text-slate-600">
                            <i className={`fas ${showPassword ? 'fa-eye-slash' : 'fa-eye'} text-sm`} />
                        </button>
                    </div>
                    {errors.password && <p className="mt-1.5 text-[13px] text-accent">{errors.password}</p>}
                </div>

                <div>
                    <label htmlFor="password_confirmation" className="block text-[13px] font-medium text-slate-700 mb-1.5">Confirm Password</label>
                    <input type={showPassword ? 'text' : 'password'} id="password_confirmation" value={data.password_confirmation} onChange={(e) => setData('password_confirmation', e.target.value)} required placeholder="Re-enter your password"
                        className="w-full rounded-md border border-slate-300 px-3.5 py-2.5 pr-10 text-sm text-slate-900 placeholder:text-slate-400 outline-none transition focus:border-primary-300 focus:ring-2 focus:ring-primary-500/10" />
                    {errors.password_confirmation && <p className="mt-1.5 text-[13px] text-accent">{errors.password_confirmation}</p>}
                </div>

                <div className="space-y-3 rounded-md border border-slate-200 bg-slate-50 p-4">
                    <label className="flex items-start gap-2.5 cursor-pointer">
                        <input type="checkbox" checked={data.agree_terms} onChange={(e) => setData('agree_terms', e.target.checked)}
                            className="mt-0.5 h-4 w-4 rounded border-slate-300 text-primary focus:ring-primary-500/20" />
                        <span className="text-[13px] leading-5 text-slate-600">
                            I agree to the{' '}
                            <a href={route('privacy-policy')} target="_blank" className="font-medium text-primary hover:text-accent underline underline-offset-2">Privacy Policy</a>
                            {' '}and{' '}
                            <a href={route('terms-of-service')} target="_blank" className="font-medium text-primary hover:text-accent underline underline-offset-2">Terms of Service</a>
                            {' '}<span className="text-accent">*</span>
                        </span>
                    </label>
                    {errors.agree_terms && <p className="text-[13px] text-accent">{errors.agree_terms}</p>}

                    <label className="flex items-start gap-2.5 cursor-pointer">
                        <input type="checkbox" checked={data.marketing_consent} onChange={(e) => setData('marketing_consent', e.target.checked)}
                            className="mt-0.5 h-4 w-4 rounded border-slate-300 text-primary focus:ring-primary-500/20" />
                        <span className="text-[13px] leading-5 text-slate-500">
                            Send me occasional updates about events and leadership opportunities.
                        </span>
                    </label>
                </div>

                <button type="submit" disabled={processing} className="w-full rounded-md bg-accent px-4 py-3 text-sm font-semibold text-white transition hover:bg-accent-600 disabled:opacity-50 shadow-sm">
                    {processing ? 'Creating your account...' : 'Create account'}
                </button>
            </form>

            <p className="mt-6 text-center text-sm text-slate-500">
                Already have an account?{' '}
                <Link href={route('login')} className="font-semibold text-primary hover:text-accent transition-colors">
                    Sign in
                </Link>
            </p>
        </AuthLayout>
    );
}
