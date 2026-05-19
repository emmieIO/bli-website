import { FormEventHandler, useState, useEffect } from 'react';
import { Head, useForm } from '@inertiajs/react';
import AuthLayout from '@/Layouts/AuthLayout';
import Button from '@/Components/UI/Button';

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
    const [showPasswordConfirmation, setShowPasswordConfirmation] = useState(false);
    const [passwordStrength, setPasswordStrength] = useState(0);

    const [requirements, setRequirements] = useState<PasswordRequirement[]>([
        { id: 'min-length', label: 'At least 8 characters', test: (pwd) => pwd.length >= 8, met: false },
        { id: 'lowercase', label: 'Lowercase letter', test: (pwd) => /[a-z]/.test(pwd), met: false },
        { id: 'uppercase', label: 'Uppercase letter', test: (pwd) => /[A-Z]/.test(pwd), met: false },
        { id: 'number-symbol', label: 'Number or symbol', test: (pwd) => /[0-9!@#$%^&*(),.?":{}|<>]/.test(pwd), met: false },
    ]);

    useEffect(() => {
        const updatedRequirements = requirements.map(req => ({
            ...req,
            met: req.test(data.password),
        }));
        setRequirements(updatedRequirements);

        const strength = updatedRequirements.filter(req => req.met).length * 25;
        setPasswordStrength(strength);
    }, [data.password]);

    const getStrengthColor = () => {
        if (passwordStrength < 50) return 'bg-red-500';
        if (passwordStrength < 75) return 'bg-yellow-500';
        return 'bg-green-500';
    };

    const getStrengthText = () => {
        if (passwordStrength < 50) return 'Weak';
        if (passwordStrength < 75) return 'Good';
        return 'Strong';
    };

    const isFormValid = passwordStrength >= 100 &&
        data.password === data.password_confirmation &&
        data.password.length > 0;

    const submit: FormEventHandler = (e) => {
        e.preventDefault();
        post(route('password.update'));
    };

    return (
        <AuthLayout
            title="Reset Password"
            description="Choose a new password for your account."
        >
            <Head title="Password Reset" />

            <div className="mb-6 rounded-lg border border-gray-200 bg-gray-50 p-4">
                <div className="flex items-center gap-2 text-sm font-lato text-gray-700">
                    <i className="fas fa-envelope w-4 h-4"></i>
                    <span>Resetting password for: <strong>{email}</strong></span>
                </div>
            </div>

            <form onSubmit={submit} className="space-y-6">
                {/* New Password */}
                <div className="space-y-2">
                    <label className="block text-sm font-medium text-gray-700 font-lato">
                        New Password
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
                                border border-gray-300 rounded-lg
                                text-gray-900 placeholder-gray-500
                                focus:outline-none focus:ring-2 focus:border-primary
                                transition-all duration-200
                                bg-gray-50 focus:bg-white
                                ${errors.password ? 'border-red-500 focus:ring-red-500' : 'focus:ring-primary'}
                            `}
                            style={errors.password ? {} : { '--tw-ring-color': '#002147' } as any}
                            placeholder="Enter your new password"
                        />
                        <button
                            type="button"
                            onClick={() => setShowPassword(!showPassword)}
                            className="absolute inset-y-0 right-0 pr-3 flex items-center text-gray-400 hover:text-gray-600 transition-colors"
                        >
                            <i className={`fas ${showPassword ? 'fa-eye-slash' : 'fa-eye'} h-5 w-5`} />
                        </button>
                    </div>

                    {/* Password Strength Indicator */}
                    {data.password.length > 0 && (
                        <div className="mt-2">
                            <div className="flex items-center gap-2">
                                <div className="flex-1 bg-gray-200 rounded-full h-2">
                                    <div
                                        className={`h-2 rounded-full transition-all duration-300 ${getStrengthColor()}`}
                                        style={{ width: `${passwordStrength}%` }}
                                    />
                                </div>
                                <span className="text-xs text-gray-600 font-lato">{getStrengthText()}</span>
                            </div>
                        </div>
                    )}

                    {errors.password && (
                        <p className="text-sm text-red-600 font-lato">{errors.password}</p>
                    )}
                </div>

                {/* Confirm Password */}
                <div className="space-y-2">
                    <label className="block text-sm font-medium text-gray-700 font-lato">
                        Confirm New Password
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
                                border border-gray-300 rounded-lg
                                text-gray-900 placeholder-gray-500
                                focus:outline-none focus:ring-2 focus:border-primary
                                transition-all duration-200
                                bg-gray-50 focus:bg-white
                                ${errors.password_confirmation ? 'border-red-500 focus:ring-red-500' : 'focus:ring-primary'}
                            `}
                            style={errors.password_confirmation ? {} : { '--tw-ring-color': '#002147' } as any}
                            placeholder="Confirm your new password"
                        />
                        <button
                            type="button"
                            onClick={() => setShowPasswordConfirmation(!showPasswordConfirmation)}
                            className="absolute inset-y-0 right-0 pr-3 flex items-center text-gray-400 hover:text-gray-600 transition-colors"
                        >
                            <i className={`fas ${showPasswordConfirmation ? 'fa-eye-slash' : 'fa-eye'} h-5 w-5`} />
                        </button>
                    </div>
                    {errors.password_confirmation && (
                        <p className="text-sm text-red-600 font-lato">{errors.password_confirmation}</p>
                    )}
                </div>

                {/* Password Requirements */}
                <div className="rounded-lg border border-gray-200 bg-gray-50 p-4">
                    <h4 className="text-sm font-semibold text-gray-800 mb-3 font-montserrat flex items-center gap-2">
                        <i className="fas fa-shield-alt w-4 h-4"></i>
                        Password Requirements
                    </h4>
                    <div className="grid grid-cols-1 md:grid-cols-2 gap-2 text-xs font-lato">
                        {requirements.map((req) => (
                            <div
                                key={req.id}
                                className={`flex items-center gap-2 ${req.met ? 'text-green-600' : 'text-gray-500'}`}
                            >
                                <i className={`fas ${req.met ? 'fa-check' : 'fa-times'} w-3 h-3`}></i>
                                <span>{req.label}</span>
                            </div>
                        ))}
                    </div>
                </div>

                {/* Submit Button */}
                <Button
                    type="submit"
                    variant="primary"
                    fullWidth
                    isLoading={processing}
                    disabled={!isFormValid}
                    icon={!processing ? 'fas fa-shield-alt' : undefined}
                    className="font-montserrat py-3.5"
                >
                    Update Password
                </Button>
            </form>
        </AuthLayout>
    );
}
