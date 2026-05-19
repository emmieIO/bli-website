import { FormEventHandler, useState } from 'react';
import { Head, Link, useForm } from '@inertiajs/react';
import AuthLayout from '@/Layouts/AuthLayout';
import Input from '@/Components/UI/Input';
import Button from '@/Components/UI/Button';

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
    const [showPasswordConfirmation, setShowPasswordConfirmation] = useState(false);

    const submit: FormEventHandler = (e) => {
        e.preventDefault();
        post(route('register.store'));
    };

    return (
        <AuthLayout
            title="Create account"
            description="Set up your account to get started."
            wide
        >
            <Head title="Register" />

            <form onSubmit={submit} className="space-y-5">
                <div className="grid gap-5 lg:grid-cols-2">
                    <Input
                        type="text"
                        name="name"
                        label="Full Name"
                        icon="fas fa-user"
                        placeholder="Enter your full name"
                        value={data.name}
                        onChange={(e) => setData('name', e.target.value)}
                        error={errors.name}
                    />

                    <Input
                        type="email"
                        name="email"
                        label="Email Address"
                        icon="fas fa-envelope"
                        placeholder="you@example.com"
                        value={data.email}
                        onChange={(e) => setData('email', e.target.value)}
                        error={errors.email}
                    />

                    <div className="space-y-2">
                        <Input
                            type="text"
                            name="phone"
                            label="Phone Number"
                            icon="fas fa-phone"
                            placeholder="+234 801 234 5678"
                            value={data.phone}
                            onChange={(e) => setData('phone', e.target.value)}
                            error={errors.phone}
                        />
                        <p className="text-xs text-gray-400">Used for important account updates.</p>
                    </div>

                    <div className="space-y-2">
                        <label className="block text-sm font-medium text-gray-700">Password <span className="text-accent-500">*</span></label>
                        <div className="relative">
                            <div className="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
                                <i className="fas fa-lock text-gray-400" />
                            </div>
                            <input
                                type={showPassword ? 'text' : 'password'}
                                name="password"
                                value={data.password}
                                onChange={(e) => setData('password', e.target.value)}
                                className={`block w-full rounded-xl border py-3 pl-10 pr-12 text-gray-900 placeholder-gray-500 transition-all duration-200 bg-gray-50 focus:bg-white focus:outline-none focus:ring-2 ${
                                    errors.password
                                        ? 'border-accent-500 focus:ring-accent-500'
                                        : 'border-gray-300 focus:border-primary focus:ring-primary'
                                }`}
                                placeholder="Create a strong password"
                            />
                            <button
                                type="button"
                                onClick={() => setShowPassword(!showPassword)}
                                className="absolute inset-y-0 right-0 flex items-center pr-3 text-gray-400 hover:text-gray-600 transition-colors"
                            >
                                <i className={`fas ${showPassword ? 'fa-eye-slash' : 'fa-eye'}`} />
                            </button>
                        </div>
                        {errors.password && (
                            <p className="text-sm text-accent-600">{errors.password}</p>
                        )}
                        <p className="text-xs text-gray-400">At least 8 characters.</p>
                    </div>

                    <div className="space-y-2">
                        <label className="block text-sm font-medium text-gray-700">Confirm Password <span className="text-accent-500">*</span></label>
                        <div className="relative">
                            <div className="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
                                <i className="fas fa-lock text-gray-400" />
                            </div>
                            <input
                                type={showPasswordConfirmation ? 'text' : 'password'}
                                name="password_confirmation"
                                value={data.password_confirmation}
                                onChange={(e) => setData('password_confirmation', e.target.value)}
                                className={`block w-full rounded-xl border py-3 pl-10 pr-12 text-gray-900 placeholder-gray-500 transition-all duration-200 bg-gray-50 focus:bg-white focus:outline-none focus:ring-2 ${
                                    errors.password_confirmation
                                        ? 'border-accent-500 focus:ring-accent-500'
                                        : 'border-gray-300 focus:border-primary focus:ring-primary'
                                }`}
                                placeholder="Re-enter your password"
                            />
                            <button
                                type="button"
                                onClick={() => setShowPasswordConfirmation(!showPasswordConfirmation)}
                                className="absolute inset-y-0 right-0 flex items-center pr-3 text-gray-400 hover:text-gray-600 transition-colors"
                            >
                                <i className={`fas ${showPasswordConfirmation ? 'fa-eye-slash' : 'fa-eye'}`} />
                            </button>
                        </div>
                        {errors.password_confirmation && (
                            <p className="text-sm text-accent-600">{errors.password_confirmation}</p>
                        )}
                    </div>
                </div>

                <div className="space-y-3 rounded-lg border border-gray-200 bg-gray-50/50 p-4">
                    <label className="flex items-start gap-3">
                        <input
                            type="checkbox"
                            name="agree_terms"
                            checked={data.agree_terms}
                            onChange={(e) => setData('agree_terms', e.target.checked)}
                            className="mt-0.5 rounded border-gray-300 text-primary focus:ring-primary shrink-0"
                        />
                        <span className="text-sm text-gray-600 leading-5">
                            I agree to the{' '}
                            <a
                                href={route('privacy-policy')}
                                target="_blank"
                                className="font-semibold text-primary hover:text-accent transition-colors underline underline-offset-2"
                            >
                                Privacy Policy
                            </a>{' '}
                            and{' '}
                            <a
                                href={route('terms-of-service')}
                                target="_blank"
                                className="font-semibold text-primary hover:text-accent transition-colors underline underline-offset-2"
                            >
                                Terms of Service
                            </a>
                            {' '}<span className="text-accent-500">*</span>
                        </span>
                    </label>

                    <label className="flex items-start gap-3">
                        <input
                            type="checkbox"
                            name="marketing_consent"
                            checked={data.marketing_consent}
                            onChange={(e) => setData('marketing_consent', e.target.checked)}
                            className="mt-0.5 rounded border-gray-300 text-accent focus:ring-accent shrink-0"
                        />
                        <span className="text-sm text-gray-500 leading-5">
                            Send me occasional updates about courses and events.
                        </span>
                    </label>
                    {errors.agree_terms && (
                        <p className="text-sm text-accent-600">{errors.agree_terms}</p>
                    )}
                </div>

                <Button
                    type="submit"
                    variant="primary"
                    fullWidth
                    isLoading={processing}
                    className="py-3.5 text-base"
                >
                    {processing ? 'Creating your account...' : 'Create account'}
                </Button>
            </form>

            <div className="mt-6 border-t border-gray-100 pt-5 text-center">
                <p className="text-gray-600">
                    Already have an account?{' '}
                    <Link
                        href={route('login')}
                        className="font-semibold text-primary hover:text-accent transition-colors"
                    >
                        Sign in
                    </Link>
                </p>
            </div>
        </AuthLayout>
    );
}
