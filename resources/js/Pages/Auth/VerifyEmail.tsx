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
            description="We've sent you a verification link. Check your inbox to activate your account."
        >
            <Head title="Email Verification" />

            {status === 'verification-link-sent' && (
                <div className="bg-green-50 border border-green-200 text-green-800 text-sm rounded-xl p-4 mb-6 flex items-start gap-3">
                    <i className="fas fa-check-circle w-5 h-5 mt-0.5 text-green-600"></i>
                    <div>
                        <p className="font-semibold font-montserrat">Email Sent Successfully!</p>
                        <p className="font-lato">A new verification link has been sent to your email address.</p>
                    </div>
                </div>
            )}

            {/* Email Status */}
            <div className="mb-6 p-6 bg-yellow-50 rounded-xl border border-yellow-200">
                <div className="flex items-center gap-3 mb-4">
                    <div className="w-10 h-10 rounded-full bg-yellow-100 border-2 border-yellow-300 flex items-center justify-center">
                        <i className="fas fa-envelope w-5 h-5 text-yellow-600"></i>
                    </div>
                    <div>
                        <h3 className="font-semibold text-yellow-800 font-montserrat">Verification Required</h3>
                        <p className="text-sm text-yellow-700 font-lato">
                            Check your email: <strong>{user?.email || ''}</strong>
                        </p>
                    </div>
                </div>

                {/* Steps */}
                <div className="grid gap-2 text-sm font-lato">
                    <div className="flex items-center gap-2 text-yellow-700">
                        <span className="w-5 h-5 rounded-full bg-yellow-200 flex items-center justify-center text-xs font-semibold text-yellow-800">
                            1
                        </span>
                        <span>Open your email application</span>
                    </div>
                    <div className="flex items-center gap-2 text-yellow-700">
                        <span className="w-5 h-5 rounded-full bg-yellow-200 flex items-center justify-center text-xs font-semibold text-yellow-800">
                            2
                        </span>
                        <span>Look for our verification email</span>
                    </div>
                    <div className="flex items-center gap-2 text-yellow-700">
                        <span className="w-5 h-5 rounded-full bg-yellow-200 flex items-center justify-center text-xs font-semibold text-yellow-800">
                            3
                        </span>
                        <span>Click the "Verify Email Address" button</span>
                    </div>
                </div>
            </div>

            {/* Troubleshooting */}
            <div className="mb-6 p-4 bg-blue-50 rounded-xl border border-blue-200">
                <h4 className="text-sm font-semibold text-blue-800 mb-3 font-montserrat flex items-center gap-2">
                    <i className="fas fa-question-circle w-4 h-4"></i>
                    Can't find the email?
                </h4>
                <ul className="text-xs text-blue-700 space-y-1 font-lato">
                    <li>• Check your spam or junk mail folder</li>
                    <li>• Make sure you entered the correct email address</li>
                    <li>• Wait a few minutes - emails can be delayed</li>
                    <li>• Add our email to your safe senders list</li>
                </ul>
            </div>

            {/* Resend Form */}
            <form onSubmit={submit}>
                <Button
                    type="submit"
                    variant="primary"
                    fullWidth
                    disabled={cooldown > 0}
                    isLoading={processing}
                    className="shadow-lg hover:shadow-xl font-montserrat py-3.5"
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

            {/* Alternative Actions */}
            <div className="mt-8 space-y-4">
                {/* Update Email Address */}
                <div className="text-center">
                    <p className="text-sm text-gray-600 font-lato mb-2">Wrong email address?</p>
                    <Link
                        href={route('profile.edit')}
                        className="inline-flex items-center gap-2 transition-colors font-lato text-sm"
                        style={{ color: '#002147' }}
                    >
                        <i className="fas fa-edit w-4 h-4"></i>
                        Update Email Address
                    </Link>
                </div>

                <div className="relative">
                    <div className="absolute inset-0 flex items-center">
                        <div className="w-full border-t border-gray-200"></div>
                    </div>
                    <div className="relative flex justify-center text-sm">
                        <span className="px-2 bg-white text-gray-500 font-lato">or</span>
                    </div>
                </div>

                {/* Logout */}
                <form onSubmit={logout} className="text-center">
                    <button
                        type="submit"
                        className="inline-flex items-center gap-2 text-gray-500 hover:text-gray-700 transition-colors font-lato text-sm"
                    >
                        <i className="fas fa-sign-out-alt w-4 h-4"></i>
                        Sign out and try a different account
                    </button>
                </form>
            </div>

            {/* Contact Support */}
            <div className="mt-8 text-center p-4 bg-gray-50 rounded-xl border border-gray-200">
                <p className="text-sm text-gray-600 font-lato mb-2">Still having issues?</p>
                <a
                    href="mailto:support@beaconleadershipinstitute.org"
                    className="inline-flex items-center gap-2 font-semibold transition-colors"
                    style={{ color: '#002147' }}
                >
                    <i className="fas fa-envelope w-4 h-4"></i>
                    Contact Support
                </a>
            </div>
        </AuthLayout>
    );
}
