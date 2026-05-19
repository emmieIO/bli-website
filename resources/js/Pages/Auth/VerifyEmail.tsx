import { FormEventHandler, useState, useEffect } from 'react';
import { Head, Link, useForm, usePage } from '@inertiajs/react';
import AuthLayout from '@/Layouts/AuthLayout';
import Button from '@/Components/UI/Button';

export default function VerifyEmail({ status }: { status?: string }) {
    const { auth } = usePage().props;
    const user = (auth as any)?.user;
    const { post, processing } = useForm({});

    const [cooldown, setCooldown] = useState(0);

    useEffect(() => {
        if (status === 'verification-link-sent') {
            setCooldown(60);
        }
    }, [status]);

    useEffect(() => {
        if (cooldown > 0) {
            const timer = setTimeout(() => setCooldown(cooldown - 1), 1000);
            return () => clearTimeout(timer);
        }
    }, [cooldown]);

    const submit: FormEventHandler = (e) => {
        e.preventDefault();
        post(route('verification.send'));
    };

    const logout: FormEventHandler = (e) => {
        e.preventDefault();
        post(route('logout'));
    };

    return (
        <AuthLayout
            title="Verify Your Email"
            description="Check your inbox and confirm your email address."
        >
            <Head title="Email Verification" />

            {status === 'verification-link-sent' && (
                <div className="mb-6 flex items-start gap-3 rounded-lg border border-green-200 bg-green-50 p-4 text-sm text-green-800">
                    <i className="fas fa-check-circle w-5 h-5 mt-0.5 text-green-600"></i>
                    <div>
                        <p className="font-semibold font-montserrat">Email Sent Successfully!</p>
                        <p className="font-lato">A new verification link has been sent to your email address.</p>
                    </div>
                </div>
            )}

            <div className="mb-6 rounded-lg border border-gray-200 bg-gray-50 p-4">
                <div className="flex items-center gap-3 mb-4">
                    <div className="flex h-10 w-10 items-center justify-center rounded-lg bg-primary/10">
                        <i className="fas fa-envelope w-5 h-5 text-primary"></i>
                    </div>
                    <div>
                        <h3 className="font-semibold text-primary font-montserrat">Verification Required</h3>
                        <p className="text-sm text-gray-600 font-lato">
                            Check your email: <strong>{user?.email || ''}</strong>
                        </p>
                    </div>
                </div>
                <p className="text-sm leading-6 text-gray-600 font-lato">
                    If the email is delayed, wait a moment and check your spam folder before requesting another link.
                </p>
            </div>

            {/* Resend Form */}
            <form onSubmit={submit}>
                <Button
                    type="submit"
                    variant="primary"
                    fullWidth
                    disabled={cooldown > 0}
                    isLoading={processing}
                    className="font-montserrat py-3.5"
                >
                    {cooldown > 0 ? (
                        <>
                            <i className="fas fa-clock w-5 h-5 mr-2"></i>
                            <span>Resend in {cooldown}s</span>
                        </>
                    ) : processing ? (
                        <>
                            <i className="fas fa-spinner fa-spin w-5 h-5 mr-2"></i>
                            <span>Sending Email...</span>
                        </>
                    ) : (
                        <>
                            <i className="fas fa-redo w-5 h-5 mr-2"></i>
                            <span>Resend Verification Email</span>
                        </>
                    )}
                </Button>
            </form>

            <div className="mt-8 space-y-4">
                <div className="text-center">
                    <p className="text-sm text-gray-600 font-lato mb-2">Wrong email address?</p>
                    <Link
                        href={route('profile.edit')}
                        className="inline-flex items-center gap-2 transition-colors font-lato text-sm"
                        style={{ color: '#002147' }}
                    >
                        <i className="fas fa-edit w-4 h-4"></i>
                        Update email address
                    </Link>
                </div>
                <form onSubmit={logout} className="text-center">
                    <button
                        type="submit"
                        className="inline-flex items-center gap-2 text-gray-500 hover:text-gray-700 transition-colors font-lato text-sm"
                    >
                        <i className="fas fa-sign-out-alt w-4 h-4"></i>
                        Sign out
                    </button>
                </form>
            </div>
        </AuthLayout>
    );
}
