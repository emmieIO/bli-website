import { FormEventHandler, useState, useEffect } from 'react';
import { Head, useForm } from '@inertiajs/react';
import AuthLayout from '@/Layouts/AuthLayout';

interface PasswordRequirement {
    id: string;
    label: string;
    test: (password: string) => boolean;
    met: boolean;
}

export default function ResetPassword({ token, email }: { token: string; email: string }) {
    const { data, setData, post, processing, errors } = useForm({
        token: token,
        email: email,
        password: '',
        password_confirmation: '',
    });

    const [showPassword, setShowPassword] = useState(false);
    const [passwordStrength, setPasswordStrength] = useState(0);
    const [requirements, setRequirements] = useState<PasswordRequirement[]>([
        { id: 'length', label: 'At least 8 characters', test: (p) => p.length >= 8, met: false },
        { id: 'lower', label: 'Lowercase letter', test: (p) => /[a-z]/.test(p), met: false },
        { id: 'upper', label: 'Uppercase letter', test: (p) => /[A-Z]/.test(p), met: false },
        { id: 'symbol', label: 'Number or symbol', test: (p) => /[0-9!@#$%^&*]/.test(p), met: false },
    ]);

    useEffect(() => {
        const updated = requirements.map((r) => ({ ...r, met: r.test(data.password) }));
        setRequirements(updated);
        setPasswordStrength(updated.filter((r) => r.met).length * 25);
    }, [data.password]);

    const strengthColor = passwordStrength < 50 ? 'bg-accent' : passwordStrength < 75 ? 'bg-amber-500' : 'bg-lime-500';
    const strengthLabel = passwordStrength < 50 ? 'Weak' : passwordStrength < 75 ? 'Good' : 'Strong';
    const isValid = passwordStrength >= 75 && data.password === data.password_confirmation && data.password.length > 0;

    const submit: FormEventHandler = (e) => {
        e.preventDefault();
        post(route('password.update'));
    };

    return (
        <AuthLayout title="Reset Password" description="Choose a new password for your account.">
            <Head title="Password Reset" />

            <div className="mb-4 rounded-md bg-slate-50 border border-slate-200 px-4 py-3 text-sm text-slate-600">
                Resetting password for <strong className="text-slate-900">{email}</strong>
            </div>

            <form onSubmit={submit} className="space-y-4">
                <div>
                    <label htmlFor="password" className="block text-[13px] font-medium text-slate-700 mb-1.5">New Password</label>
                    <div className="relative">
                        <input type={showPassword ? 'text' : 'password'} id="password" value={data.password} onChange={(e) => setData('password', e.target.value)} required placeholder="Enter your new password"
                            className="w-full rounded-md border border-slate-300 px-3.5 py-2.5 pr-10 text-sm text-slate-900 placeholder:text-slate-400 outline-none transition focus:border-primary-300 focus:ring-2 focus:ring-primary-500/10" />
                        <button type="button" onClick={() => setShowPassword(!showPassword)} className="absolute inset-y-0 right-0 flex items-center pr-3 text-slate-400 hover:text-slate-600">
                            <i className={`fas ${showPassword ? 'fa-eye-slash' : 'fa-eye'} text-sm`} />
                        </button>
                    </div>
                    {errors.password && <p className="mt-1.5 text-[13px] text-accent">{errors.password}</p>}

                    {data.password.length > 0 && (
                        <div className="mt-2.5">
                            <div className="flex items-center gap-2 mb-2">
                                <div className="h-1.5 flex-1 rounded-full bg-slate-200 overflow-hidden">
                                    <div className={`h-full rounded-full transition-all duration-300 ${strengthColor}`} style={{ width: `${passwordStrength}%` }} />
                                </div>
                                <span className="text-[11px] font-semibold uppercase tracking-wider text-slate-400">{strengthLabel}</span>
                            </div>
                            <div className="grid grid-cols-2 gap-1.5">
                                {requirements.map((req) => (
                                    <div key={req.id} className={`flex items-center gap-1.5 text-[11px] ${req.met ? 'text-lime-600' : 'text-slate-400'}`}>
                                        <i className={`fas fa-${req.met ? 'check' : 'times'} text-[10px]`} />
                                        {req.label}
                                    </div>
                                ))}
                            </div>
                        </div>
                    )}
                </div>

                <div>
                    <label htmlFor="password_confirmation" className="block text-[13px] font-medium text-slate-700 mb-1.5">Confirm Password</label>
                    <input type={showPassword ? 'text' : 'password'} id="password_confirmation" value={data.password_confirmation} onChange={(e) => setData('password_confirmation', e.target.value)} required placeholder="Re-enter your new password"
                        className="w-full rounded-md border border-slate-300 px-3.5 py-2.5 text-sm text-slate-900 placeholder:text-slate-400 outline-none transition focus:border-primary-300 focus:ring-2 focus:ring-primary-500/10" />
                    {errors.password_confirmation && <p className="mt-1.5 text-[13px] text-accent">{errors.password_confirmation}</p>}
                </div>

                <button type="submit" disabled={processing || !isValid} className="w-full rounded-md bg-accent px-4 py-3 text-sm font-semibold text-white transition hover:bg-accent-600 disabled:opacity-50 shadow-sm">
                    {processing ? 'Updating...' : 'Update Password'}
                </button>
            </form>
        </AuthLayout>
    );
}
