import { FormEventHandler, useState } from 'react';
import { Head, Link, useForm } from '@inertiajs/react';
import AuthLayout from '@/Layouts/AuthLayout';
import Input from '@/Components/UI/Input';
import Button from '@/Components/UI/Button';

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
        <AuthLayout
            title="Sign in"
            description="Welcome back — enter your credentials to continue."
        >
            <Head title="Login" />

            {status && (
                <div className="mb-6 flex items-start gap-2 rounded-lg border border-blue-100 bg-blue-50 px-4 py-3 text-sm text-blue-700">
                    <span>&#9432;</span>
                    <span>{status}</span>
                </div>
            )}

            <form onSubmit={submit} className="space-y-5">
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
                    <div className="flex items-center justify-between">
                        <label className="block text-sm font-medium text-gray-700">Password</label>
                        <Link
                            href={route('password.request')}
                            className="text-sm font-medium text-primary hover:text-accent transition-colors"
                        >
                            Forgot?
                        </Link>
                    </div>
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
                            placeholder="Enter your password"
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
                </div>

                <label className="flex items-center gap-2">
                    <input
                        type="checkbox"
                        name="remember"
                        checked={data.remember}
                        onChange={(e) => setData('remember', e.target.checked)}
                        className="rounded border-gray-300 text-primary focus:ring-primary"
                    />
                    <span className="text-sm text-gray-600">Remember me</span>
                </label>

                <Button
                    type="submit"
                    variant="primary"
                    fullWidth
                    isLoading={processing}
                    className="py-3.5 text-base"
                >
                    {processing ? 'Signing in...' : 'Sign in'}
                </Button>
            </form>

            <div className="mt-6 border-t border-gray-100 pt-5 text-center">
                <p className="text-gray-600">
                    Don't have an account?{' '}
                    <Link
                        href={route('register')}
                        className="font-semibold text-primary hover:text-accent transition-colors"
                    >
                        Create account
                    </Link>
                </p>
            </div>
        </AuthLayout>
    );
}
