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
            <Head title="Login" />

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
                <div className="pt-4">
                    <Button
                        type="submit"
                        variant="primary"
                        fullWidth
                        isLoading={processing}
                        icon={!processing ? 'fas fa-sign-in-alt' : undefined}
                        className="shadow-lg hover:shadow-xl font-montserrat py-4 text-base"
                    >
                        {processing ? 'Signing In...' : 'Sign In'}
                    </Button>
                </div>
            </form>

            {/* Register Link */}
            <div className="text-center pt-6 border-t border-gray-100 mt-8">
                <p className="text-gray-600 font-lato text-base">
                    Don't have an account?{' '}
                    <Link
                        href={route('register')}
                        className="font-semibold transition-colors font-montserrat ml-1 hover:underline"
                        style={{ color: '#002147' }}
                    >
                        Create one here
                    </Link>
                </p>
            </div>
        </AuthLayout>
    );
}
