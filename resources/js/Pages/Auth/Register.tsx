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
            title="Create Account"
            description="Start your leadership journey with us today"
        >
            <Head title="Register - Beacon Leadership Institute" />

            <form onSubmit={submit} className="space-y-6">
                {/* Personal Information Grid */}
                <div className="grid grid-cols-1 lg:grid-cols-2 gap-6">
                    {/* Full Name */}
                    <Input
                        type="text"
                        name="name"
                        label="Full Name"
                        icon="fas fa-user"
                        placeholder="Enter your full name"
                        value={data.name}
                        onChange={(e) => setData('name', e.target.value)}
                        error={errors.name}
                        required
                    />

                    {/* Email */}
                    <Input
                        type="email"
                        name="email"
                        label="Email Address"
                        icon="fas fa-envelope"
                        placeholder="example@domain.com"
                        value={data.email}
                        onChange={(e) => setData('email', e.target.value)}
                        error={errors.email}
                        required
                    />
                </div>

                {/* Phone Number (Full Width) */}
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
                    <p className="text-xs text-gray-500 font-lato">Enter your phone number (optional)</p>
                </div>

                {/* Password Grid */}
                <div className="grid grid-cols-1 lg:grid-cols-2 gap-6">
                    {/* Password */}
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
                                placeholder="Enter a strong password"
                            />
                            <button
                                type="button"
                                onClick={() => setShowPassword(!showPassword)}
                                className="absolute inset-y-0 right-0 pr-3 flex items-center text-gray-400 hover:text-gray-600 transition-colors"
                            >
                                <i className={`fas ${showPassword ? 'fa-eye-slash' : 'fa-eye'} h-4 w-4`} />
                            </button>
                        </div>
                        {errors.password && (
                            <p className="text-sm text-red-600 font-lato">{errors.password}</p>
                        )}
                    </div>

                    {/* Confirm Password */}
                    <div className="space-y-2">
                        <label className="block text-sm font-medium text-gray-700 font-lato">
                            Confirm Password
                        </label>
                        <div className="relative">
                            <div className="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <i className="fas fa-lock h-5 w-5 text-gray-400" />
                            </div>
                            <input
                                type={showPasswordConfirmation ? 'text' : 'password'}
                                name="password_confirmation"
                                value={data.password_confirmation}
                                onChange={(e) => setData('password_confirmation', e.target.value)}
                                required
                                className={`
                                    block w-full pl-10 pr-12 py-3
                                    border border-gray-300 rounded-xl
                                    text-gray-900 placeholder-gray-500
                                    focus:outline-none focus:ring-2 focus:border-primary
                                    transition-all duration-200
                                    bg-gray-50 focus:bg-white
                                    ${errors.password_confirmation ? 'border-red-500 focus:ring-red-500' : 'focus:ring-primary'}
                                `}
                                style={errors.password_confirmation ? {} : { '--tw-ring-color': '#002147' } as any}
                                placeholder="Re-enter your password"
                            />
                            <button
                                type="button"
                                onClick={() => setShowPasswordConfirmation(!showPasswordConfirmation)}
                                className="absolute inset-y-0 right-0 pr-3 flex items-center text-gray-400 hover:text-gray-600 transition-colors"
                            >
                                <i className={`fas ${showPasswordConfirmation ? 'fa-eye-slash' : 'fa-eye'} h-4 w-4`} />
                            </button>
                        </div>
                        {errors.password_confirmation && (
                            <p className="text-sm text-red-600 font-lato">{errors.password_confirmation}</p>
                        )}
                    </div>
                </div>

                {/* Terms and Marketing */}
                <div className="space-y-4 bg-gray-50 p-6 rounded-xl border border-gray-200">
                    <div className="flex items-start space-x-3">
                        <input
                            type="checkbox"
                            name="agree_terms"
                            id="agree_terms"
                            checked={data.agree_terms}
                            onChange={(e) => setData('agree_terms', e.target.checked)}
                            required
                            className="rounded border-gray-300 focus:ring-offset-0 mt-1 flex-shrink-0"
                            style={{ color: '#002147' }}
                        />
                        <label htmlFor="agree_terms" className="text-sm text-gray-600 font-lato">
                            I agree to the{' '}
                            <a
                                href={route('privacy-policy')}
                                target="_blank"
                                className="font-semibold underline underline-offset-2 transition-colors"
                                style={{ color: '#002147' }}
                            >
                                Privacy Policy
                            </a>{' '}
                            and{' '}
                            <a
                                href={route('terms-of-service')}
                                target="_blank"
                                className="font-semibold underline underline-offset-2 transition-colors"
                                style={{ color: '#002147' }}
                            >
                                Terms of Service
                            </a>
                        </label>
                    </div>

                    <div className="flex items-start space-x-3">
                        <input
                            type="checkbox"
                            name="marketing_consent"
                            id="marketing_consent"
                            checked={data.marketing_consent}
                            onChange={(e) => setData('marketing_consent', e.target.checked)}
                            className="rounded border-gray-300 focus:ring-offset-0 mt-1 flex-shrink-0"
                            style={{ color: '#002147' }}
                        />
                        <label htmlFor="marketing_consent" className="text-sm text-gray-600 font-lato">
                            I would like to receive updates about new programs and events
                        </label>
                    </div>
                </div>

                {/* Submit Button */}
                <div className="pt-2">
                    <Button
                        type="submit"
                        variant="primary"
                        fullWidth
                        isLoading={processing}
                        icon={!processing ? 'fas fa-user-plus' : undefined}
                        className="shadow-lg hover:shadow-xl font-montserrat py-4"
                    >
                        Create Account
                    </Button>
                </div>
            </form>

            {/* Divider */}
            <div className="relative my-8">
                <div className="absolute inset-0 flex items-center">
                    <div className="w-full border-t border-gray-200"></div>
                </div>
                <div className="relative flex justify-center text-sm">
                    <span className="px-4 bg-white text-gray-500 font-lato">or continue with</span>
                </div>
            </div>

            {/* Social Login Grid */}
            <div className="grid grid-cols-1 lg:grid-cols-2 gap-4">
                <button
                    type="button"
                    className="flex items-center justify-center gap-3 border border-gray-300 bg-white text-gray-700 py-3.5 px-4 rounded-xl font-medium hover:bg-gray-50 hover:border-gray-400 transition-all duration-200 font-lato shadow-sm hover:shadow-md"
                >
                    <svg className="w-5 h-5" viewBox="0 0 24 24" fill="currentColor">
                        <path d="M22.56 12.25c0-.78-.07-1.53-.2-2.25H12v4.26h5.92c-.26 1.37-1.04 2.53-2.21 3.31v2.77h3.57c2.08-1.92 3.28-4.74 3.28-8.09z" />
                        <path d="M12 23c2.97 0 5.46-.98 7.28-2.66l-3.57-2.77c-.98.66-2.23 1.06-3.71 1.06-2.86 0-5.29-1.93-6.16-4.53H2.18v2.84C3.99 20.53 7.7 23 12 23z" />
                        <path d="M5.84 14.09c-.22-.66-.35-1.36-.35-2.09s.13-1.43.35-2.09V7.07H2.18C1.43 8.55 1 10.22 1 12s.43 3.45 1.18 4.93l2.85-2.22.81-.62z" />
                        <path d="M12 5.38c1.62 0 3.06.56 4.21 1.64l3.15-3.15C17.45 2.09 14.97 1 12 1 7.7 1 3.99 3.47 2.18 7.07l3.66 2.84c.87-2.6 3.3-4.53 6.16-4.53z" />
                    </svg>
                    <span className="hidden sm:inline">Continue with Google</span>
                    <span className="sm:hidden">Google</span>
                </button>

                <button
                    type="button"
                    className="flex items-center justify-center gap-3 border border-gray-300 bg-white text-gray-700 py-3.5 px-4 rounded-xl font-medium hover:bg-gray-50 hover:border-gray-400 transition-all duration-200 font-lato shadow-sm hover:shadow-md"
                >
                    <i className="fab fa-github w-5 h-5"></i>
                    <span className="hidden sm:inline">Continue with GitHub</span>
                    <span className="sm:hidden">GitHub</span>
                </button>
            </div>

            {/* Login Link */}
            <div className="text-center pt-8 border-t border-gray-100 mt-8">
                <p className="text-gray-600 font-lato">
                    Already have an account?{' '}
                    <Link
                        href={route('login')}
                        className="font-semibold transition-colors font-montserrat ml-1"
                        style={{ color: '#002147' }}
                    >
                        Sign in
                    </Link>
                </p>
            </div>
        </AuthLayout>
    );
}
