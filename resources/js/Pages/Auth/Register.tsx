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

            <form onSubmit={submit} className="space-y-7">
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

                {/* Phone Number */}
                <div className="space-y-2">
                    <Input
                        type="text"
                        name="phone"
                        label="Phone Number (Optional)"
                        icon="fas fa-phone"
                        placeholder="+234 801 234 5678"
                        value={data.phone}
                        onChange={(e) => setData('phone', e.target.value)}
                        error={errors.phone}
                    />
                    <p className="text-xs text-gray-500 font-lato">We may use this to contact you about important updates</p>
                </div>

                {/* Password */}
                <div className="space-y-2">
                    <label className="block text-sm font-medium text-gray-700 font-lato">
                        Password <span className="text-red-500">*</span>
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
                    <p className="text-xs text-gray-500 font-lato">Use at least 8 characters with a mix of letters and numbers</p>
                </div>

                {/* Confirm Password */}
                <div className="space-y-2">
                    <label className="block text-sm font-medium text-gray-700 font-lato">
                        Confirm Password <span className="text-red-500">*</span>
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
                            className="rounded border-gray-300 focus:ring-offset-0 mt-1 shrink-0"
                            style={{ color: '#002147' }}
                        />
                        <label htmlFor="agree_terms" className="text-sm text-gray-600 font-lato">
                            I agree to the{' '}
                            <a
                                href={route('privacy-policy')}
                                target="_blank"
                                className="font-semibold underline underline-offset-2 transition-colors hover:text-primary"
                                style={{ color: '#002147' }}
                            >
                                Privacy Policy
                            </a>{' '}
                            and{' '}
                            <a
                                href={route('terms-of-service')}
                                target="_blank"
                                className="font-semibold underline underline-offset-2 transition-colors hover:text-primary"
                                style={{ color: '#002147' }}
                            >
                                Terms of Service
                            </a>
                            {' '}<span className="text-red-500">*</span>
                        </label>
                    </div>

                    <div className="flex items-start space-x-3">
                        <input
                            type="checkbox"
                            name="marketing_consent"
                            id="marketing_consent"
                            checked={data.marketing_consent}
                            onChange={(e) => setData('marketing_consent', e.target.checked)}
                            className="rounded border-gray-300 focus:ring-offset-0 mt-1 shrink-0"
                            style={{ color: '#00a651' }}
                        />
                        <label htmlFor="marketing_consent" className="text-sm text-gray-600 font-lato">
                            Send me updates about new programs, courses, and events
                        </label>
                    </div>
                    {errors.agree_terms && (
                        <p className="text-sm text-red-600 font-lato">{errors.agree_terms}</p>
                    )}
                </div>

                {/* Submit Button */}
                <div className="pt-4">
                    <Button
                        type="submit"
                        variant="primary"
                        fullWidth
                        isLoading={processing}
                        icon={!processing ? 'fas fa-user-plus' : undefined}
                        className="shadow-lg hover:shadow-xl font-montserrat py-4 text-base"
                    >
                        {processing ? 'Creating Your Account...' : 'Create Account'}
                    </Button>
                </div>
            </form>

            {/* Login Link */}
            <div className="text-center pt-6 border-t border-gray-100 mt-8">
                <p className="text-gray-600 font-lato text-base">
                    Already have an account?{' '}
                    <Link
                        href={route('login')}
                        className="font-semibold transition-colors font-montserrat ml-1 hover:underline"
                        style={{ color: '#002147' }}
                    >
                        Sign in here
                    </Link>
                </p>
            </div>
        </AuthLayout>
    );
}
