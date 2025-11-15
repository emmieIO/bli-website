import { FormEventHandler } from 'react';
import { Head, Link, useForm } from '@inertiajs/react';
import AuthLayout from '@/Layouts/AuthLayout';
import Input from '@/Components/UI/Input';
import Button from '@/Components/UI/Button';

export default function ForgotPassword({ status }: { status?: string }) {
    const { data, setData, post, processing, errors } = useForm({
        email: '',
    });

    const submit: FormEventHandler = (e) => {
        e.preventDefault();
        post(route('password.email'));
    };

    return (
        <AuthLayout
            title="Forgot Password?"
            description="No worries! We'll help you reset your password in just a few steps."
        >
            <Head title="Forgot Password - Beacon Leadership Institute" />

            {status && (
                <div className="bg-green-50 border border-green-200 text-green-800 text-sm rounded-xl p-4 mb-6 flex items-start gap-3">
                    <i className="fas fa-check-circle w-5 h-5 mt-0.5 text-green-600"></i>
                    <div>
                        <p className="font-semibold font-montserrat">Email Sent Successfully!</p>
                        <p className="font-lato">{status}</p>
                    </div>
                </div>
            )}

            {/* Instructions */}
            <div className="mb-6 p-6 bg-blue-50 rounded-xl border border-blue-200">
                <div className="flex items-start gap-3">
                    <i className="fas fa-info-circle w-5 h-5 text-blue-600 mt-0.5"></i>
                    <div className="text-sm text-gray-700 font-lato">
                        <p className="font-semibold mb-2 text-gray-900">How it works:</p>
                        <ol className="list-decimal list-inside space-y-1 text-gray-600">
                            <li>Enter your email address below</li>
                            <li>Check your inbox for a reset link</li>
                            <li>Click the link to create a new password</li>
                            <li>Sign in with your new password</li>
                        </ol>
                    </div>
                </div>
            </div>

            <form onSubmit={submit} className="space-y-6">
                {/* Email Input */}
                <Input
                    type="email"
                    name="email"
                    label="Email Address"
                    icon="fas fa-envelope"
                    placeholder="Enter your registered email address"
                    value={data.email}
                    onChange={(e) => setData('email', e.target.value)}
                    error={errors.email}
                    required
                />

                {/* Submit Button */}
                <Button
                    type="submit"
                    variant="primary"
                    fullWidth
                    isLoading={processing}
                    icon={!processing ? 'fas fa-paper-plane' : undefined}
                    className="shadow-lg hover:shadow-xl font-montserrat py-3.5"
                >
                    Send Password Reset Link
                </Button>
            </form>

            {/* Alternative Options */}
            <div className="mt-8 space-y-6">
                {/* Security Tips */}
                <div className="p-4 bg-amber-50 rounded-xl border border-amber-200">
                    <h4 className="text-sm font-semibold text-amber-800 mb-2 font-montserrat flex items-center gap-2">
                        <i className="fas fa-shield-alt w-4 h-4"></i>
                        Security Tips
                    </h4>
                    <ul className="text-xs text-amber-700 space-y-1 font-lato">
                        <li>• The reset link will expire in 60 minutes</li>
                        <li>• Check your spam/junk folder if you don't see the email</li>
                        <li>• Use a strong, unique password when resetting</li>
                    </ul>
                </div>

                {/* Back to Login */}
                <div className="text-center pt-4 border-t border-gray-100">
                    <Link
                        href={route('login')}
                        className="inline-flex items-center gap-2 text-gray-600 transition-colors font-lato text-sm"
                        style={{ color: '#002147' }}
                    >
                        <i className="fas fa-arrow-left w-4 h-4"></i>
                        Back to Login
                    </Link>
                </div>
            </div>
        </AuthLayout>
    );
}
