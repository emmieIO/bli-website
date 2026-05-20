import { Head, Link, router } from '@inertiajs/react';
import GuestLayout from '@/Layouts/GuestLayout';
import { useState, FormEvent } from 'react';

interface User {
    id: number;
    name: string;
    email: string;
}

interface BecomeInstructorProps {
    auth?: {
        user?: User;
    };
}

export default function BecomeInstructor({ auth }: BecomeInstructorProps) {
    const [formData, setFormData] = useState({
        name: '',
        email: auth?.user?.email || '',
    });
    const [isSubmitting, setIsSubmitting] = useState(false);
    const [expandedFAQ, setExpandedFAQ] = useState<number | null>(null);

    const handleSubmit = (e: FormEvent) => {
        e.preventDefault();
        setIsSubmitting(true);

        router.post(route('instructors.start-application'), formData, {
            onFinish: () => setIsSubmitting(false),
        });
    };

    const toggleFAQ = (index: number) => {
        setExpandedFAQ(expandedFAQ === index ? null : index);
    };

    const faqData = [
        {
            q: 'What qualifications do I need?',
            a: 'We look for clear subject knowledge, practical experience, and the ability to explain your topic well. Formal certificates can help, but they are not the only factor in our review.',
        },
        {
            q: 'How does payment work?',
            a: 'If your application is approved, any payout structure or commercial terms will be shared with you during onboarding or in a separate agreement.',
        },
        {
            q: 'How long is the review process?',
            a: 'Review time varies depending on application volume and the completeness of your submission. If we need anything else, we will reach out by email.',
        },
        {
            q: 'What if I\'m not approved?',
            a: 'You may apply again later after improving your materials or experience. Reapplication details depend on the reason your application was not accepted.',
        },
    ];

    return (
        <GuestLayout>
            <Head title="Become an Instructor" />

            <section className="bg-white">
                <div className="mx-auto max-w-6xl">
                    <div className="grid lg:grid-cols-2 min-h-[calc(100vh-80px)]">
                        <div className="flex flex-col justify-center px-8 py-16 lg:px-16">
                            <div className="max-w-lg">
                                <p className="text-sm font-semibold uppercase tracking-widest text-accent">
                                    Instructor Program
                                </p>
                                <h1 className="mt-4 text-4xl font-bold leading-tight text-primary md:text-5xl">
                                    Become an instructor
                                </h1>
                                <p className="mt-4 text-lg leading-relaxed text-gray-500">
                                    Share your knowledge with a growing community of learners. Submit your details and
                                    we'll guide you through the rest.
                                </p>

                                <div className="mt-10 space-y-4">
                                    <div className="flex items-start gap-4">
                                        <div className="mt-1 flex h-6 w-6 shrink-0 items-center justify-center rounded-full bg-primary">
                                            <span className="text-xs font-bold text-white">1</span>
                                        </div>
                                        <div>
                                            <p className="font-semibold text-primary">Fill in your details</p>
                                            <p className="text-sm text-gray-500">Name and email to get started.</p>
                                        </div>
                                    </div>
                                    <div className="flex items-start gap-4">
                                        <div className="mt-1 flex h-6 w-6 shrink-0 items-center justify-center rounded-full bg-primary">
                                            <span className="text-xs font-bold text-white">2</span>
                                        </div>
                                        <div>
                                            <p className="font-semibold text-primary">Check your email</p>
                                            <p className="text-sm text-gray-500">We'll send you a link to continue your application.</p>
                                        </div>
                                    </div>
                                    <div className="flex items-start gap-4">
                                        <div className="mt-1 flex h-6 w-6 shrink-0 items-center justify-center rounded-full bg-primary">
                                            <span className="text-xs font-bold text-white">3</span>
                                        </div>
                                        <div>
                                            <p className="font-semibold text-primary">Complete your profile</p>
                                            <p className="text-sm text-gray-500">Share your experience, materials, and submit for review.</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div className="flex items-center justify-center bg-primary px-8 py-16 lg:px-16">
                            <div className="w-full max-w-md">
                                <form onSubmit={handleSubmit} className="space-y-6">
                                    <h2 className="text-2xl font-bold text-white">Start your application</h2>

                                    {auth?.user ? (
                                        <div className="rounded-lg border border-white/20 bg-white/10 p-5">
                                            <p className="text-center text-white/90">
                                                Logged in as <span className="font-semibold text-white">{auth.user.email}</span>
                                            </p>
                                        </div>
                                    ) : (
                                        <div className="space-y-5">
                                            <div>
                                                <label className="mb-1.5 block text-sm font-medium text-white/80">
                                                    Full name
                                                </label>
                                                <input
                                                    type="text"
                                                    required
                                                    value={formData.name}
                                                    onChange={(e) => setFormData({ ...formData, name: e.target.value })}
                                                    className="w-full rounded-lg border border-white/20 bg-white/10 px-4 py-3 text-white placeholder-white/40 outline-none transition focus:border-white/50 focus:bg-white/15"
                                                    placeholder="Your full name"
                                                />
                                            </div>
                                            <div>
                                                <label className="mb-1.5 block text-sm font-medium text-white/80">
                                                    Email address
                                                </label>
                                                <input
                                                    type="email"
                                                    required
                                                    value={formData.email}
                                                    onChange={(e) => setFormData({ ...formData, email: e.target.value })}
                                                    className="w-full rounded-lg border border-white/20 bg-white/10 px-4 py-3 text-white placeholder-white/40 outline-none transition focus:border-white/50 focus:bg-white/15"
                                                    placeholder="you@example.com"
                                                />
                                            </div>
                                        </div>
                                    )}

                                    <button
                                        type="submit"
                                        disabled={isSubmitting}
                                        className="w-full rounded-lg bg-accent px-6 py-3 font-semibold text-white transition hover:bg-accent-600 disabled:cursor-not-allowed disabled:opacity-50"
                                    >
                                        {isSubmitting ? (
                                            <span className="flex items-center justify-center gap-2">
                                                <svg className="h-5 w-5 animate-spin" fill="none" viewBox="0 0 24 24">
                                                    <circle className="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" strokeWidth="4" />
                                                    <path className="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v8z" />
                                                </svg>
                                                Sending...
                                            </span>
                                        ) : (
                                            'Send application link'
                                        )}
                                    </button>

                                    <p className="text-center text-sm text-white/50">
                                        By applying, you agree to our{' '}
                                        <Link href={route('terms-of-service')} className="text-white/80 underline transition hover:text-white">
                                            terms
                                        </Link>{' '}
                                        and{' '}
                                        <Link href={route('privacy-policy')} className="text-white/80 underline transition hover:text-white">
                                            privacy policy
                                        </Link>.
                                    </p>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </section>

            <section id="faq" className="border-t border-gray-100 bg-white py-20">
                <div className="mx-auto max-w-3xl px-8">
                    <h2 className="text-2xl font-bold text-primary">Frequently asked questions</h2>
                    <p className="mt-2 text-gray-500">Common questions about the instructor application process.</p>

                    <div className="mt-10 space-y-4">
                        {faqData.map((faq, index) => (
                            <div key={index} className="overflow-hidden rounded-lg border border-gray-200">
                                <button
                                    className="flex w-full items-center justify-between px-6 py-5 text-left transition hover:bg-gray-50"
                                    onClick={() => toggleFAQ(index)}
                                >
                                    <span className="font-medium text-primary">{faq.q}</span>
                                    <svg
                                        className={`h-5 w-5 shrink-0 text-gray-400 transition ${expandedFAQ === index ? 'rotate-180' : ''}`}
                                        fill="none"
                                        viewBox="0 0 24 24"
                                        stroke="currentColor"
                                    >
                                        <path strokeLinecap="round" strokeLinejoin="round" strokeWidth={2} d="M19 9l-7 7-7-7" />
                                    </svg>
                                </button>
                                {expandedFAQ === index && (
                                    <div className="border-t border-gray-100 px-6 pb-5 pt-4 text-sm leading-relaxed text-gray-500">
                                        {faq.a}
                                    </div>
                                )}
                            </div>
                        ))}
                    </div>
                </div>
            </section>
        </GuestLayout>
    );
}
