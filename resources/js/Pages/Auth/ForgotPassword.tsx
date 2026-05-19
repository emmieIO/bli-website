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
            title="Forgot Password"
            description="Enter your email and we'll send a reset link."
        >
            <Head title="Forgot Password" />

            {status && (
                <div className="mb-6 flex items-start gap-3 rounded-lg border border-green-200 bg-green-50 p-4 text-sm text-green-800">
                    <i className="fas fa-check-circle w-5 h-5 mt-0.5 text-green-600"></i>
                    <div>
                        <p className="font-semibold font-montserrat">Email Sent Successfully!</p>
                        <p className="font-lato">{status}</p>
                    </div>
                </div>
            )}

            <div className="mb-6 rounded-lg border border-gray-200 bg-gray-50 p-4 text-sm leading-6 text-gray-600">
                Use the email address attached to your account.
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
                    className="font-montserrat py-3.5"
                >
                    Send Password Reset Link
                </Button>
            </form>

            <div className="mt-8 border-t border-gray-100 pt-4 text-center">
                <Link
                    href={route('login')}
                    className="inline-flex items-center gap-2 text-sm font-lato text-gray-600 transition-colors"
                    style={{ color: '#002147' }}
                >
                    <i className="fas fa-arrow-left w-4 h-4"></i>
                    Back to sign in
                </Link>
            </div>
        </AuthLayout>
    );
}
