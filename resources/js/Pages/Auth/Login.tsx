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
            title="Welcome Back"
            description="Sign in to your account to continue"
        >
            <Head title="Login - Beacon Leadership Institute" />

            {status && (
                <div className="mb-4 text-sm font-medium text-green-600">
                    {status}
                </div>
            )}

            <form onSubmit={submit} className="space-y-6">
                {/* Email Field */}
                <Input
                    type="email"
                    name="email"
                    label="Email Address"
                    icon="fas fa-envelope"
                    placeholder="Enter your email address"
                    value={data.email}
                    onChange={(e) => setData('email', e.target.value)}
                    error={errors.email}
                    required
                />

                {/* Password Field */}
                <div className="space-y-2">
                    <label className="block text-sm font-medium text-gray-700 font-lato">
                        Password
                    </label>
                    <div className="relative">
                        <div className="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <i className="fas fa-lock h-5 w-5 text-gray-400" />
                        </div>
                        <input
                            type={showPassword ? 'text' : 'password'}
                            name="password"
                            value={data.password}
                            onChange={(e) => setData('password', e.target.value)}
                            required
                            className={`
                                block w-full pl-10 pr-12 py-3
                                border border-gray-300 rounded-xl
                                text-gray-900 placeholder-gray-500
                                focus:outline-none focus:ring-2 focus:border-primary
                                transition-all duration-200
                                bg-gray-50 focus:bg-white
                                ${errors.password ? 'border-red-500 focus:ring-red-500' : 'focus:ring-primary'}
                            `}
                            style={errors.password ? {} : { '--tw-ring-color': '#002147' } as any}
                            placeholder="Enter your password"
                        />
                        <button
                            type="button"
                            onClick={() => setShowPassword(!showPassword)}
                            className="absolute inset-y-0 right-0 pr-3 flex items-center text-gray-400 hover:text-gray-600 transition-colors"
                        >
                            <i className={`fas ${showPassword ? 'fa-eye-slash' : 'fa-eye'} h-5 w-5`} />
                        </button>
                    </div>
                    {errors.password && (
                        <p className="text-sm text-red-600 font-lato">{errors.password}</p>
                    )}
                </div>

                {/* Remember Me & Forgot Password */}
                <div className="flex items-center justify-between">
                    <label className="inline-flex items-center">
                        <input
                            type="checkbox"
                            name="remember"
                            checked={data.remember}
                            onChange={(e) => setData('remember', e.target.checked)}
                            className="rounded border-gray-300 focus:ring-offset-0"
                            style={{ color: '#002147' }}
                        />
                        <span className="ml-2 text-sm text-gray-600 font-lato">Remember me</span>
                    </label>

                    <Link
                        href={route('password.request')}
                        className="text-sm font-medium transition-colors font-lato"
                        style={{ color: '#002147' }}
                    >
                        Forgot password?
                    </Link>
                </div>

                {/* Submit Button */}
                <div className="pt-2">
                    <Button
                        type="submit"
                        variant="primary"
                        fullWidth
                        isLoading={processing}
                        icon={!processing ? 'fas fa-sign-in-alt' : undefined}
                        className="shadow-lg hover:shadow-xl font-montserrat"
                    >
                        Sign In
                    </Button>
                </div>
            </form>

            {/* Divider */}
            <div className="relative my-6">
                <div className="absolute inset-0 flex items-center">
                    <div className="w-full border-t border-gray-200"></div>
                </div>
                <div className="relative flex justify-center text-sm">
                    <span className="px-2 bg-white text-gray-500 font-lato">or</span>
                </div>
            </div>

            {/* Social Login */}
            <div className="space-y-3">
                <button
                    type="button"
                    className="w-full flex items-center justify-center gap-3 border border-gray-300 bg-white text-gray-700 py-3 px-4 rounded-xl font-medium hover:bg-gray-50 transition-all duration-200 font-lato"
                >
                    <svg className="w-5 h-5" viewBox="0 0 24 24" fill="currentColor">
                        <path d="M22.56 12.25c0-.78-.07-1.53-.2-2.25H12v4.26h5.92c-.26 1.37-1.04 2.53-2.21 3.31v2.77h3.57c2.08-1.92 3.28-4.74 3.28-8.09z" />
                        <path d="M12 23c2.97 0 5.46-.98 7.28-2.66l-3.57-2.77c-.98.66-2.23 1.06-3.71 1.06-2.86 0-5.29-1.93-6.16-4.53H2.18v2.84C3.99 20.53 7.7 23 12 23z" />
                        <path d="M5.84 14.09c-.22-.66-.35-1.36-.35-2.09s.13-1.43.35-2.09V7.07H2.18C1.43 8.55 1 10.22 1 12s.43 3.45 1.18 4.93l2.85-2.22.81-.62z" />
                        <path d="M12 5.38c1.62 0 3.06.56 4.21 1.64l3.15-3.15C17.45 2.09 14.97 1 12 1 7.7 1 3.99 3.47 2.18 7.07l3.66 2.84c.87-2.6 3.3-4.53 6.16-4.53z" />
                    </svg>
                    <span>Continue with Google</span>
                </button>
            </div>

            {/* Register Link */}
            <div className="text-center pt-6">
                <p className="text-gray-600 font-lato">
                    Don't have an account?{' '}
                    <Link
                        href={route('register')}
                        className="font-semibold transition-colors font-montserrat"
                        style={{ color: '#002147' }}
                    >
                        Sign up
                    </Link>
                </p>
            </div>
        </AuthLayout>
    );
}
