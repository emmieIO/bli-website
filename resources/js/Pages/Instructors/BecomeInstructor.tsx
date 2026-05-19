import { Head, Link, router, usePage } from '@inertiajs/react';
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
            title: 'What qualifications do I need?',
            body: "We look for real-world expertise—whether from professional work, previous teaching experience, or significant projects. While degrees and certifications are helpful, they're not always required. What matters most is your ability to teach and share valuable knowledge.",
            icon: 'graduation-cap',
        },
        {
            title: 'How much can I earn?',
            body: 'Top instructors earn $10K+/month, but earnings vary based on course quality, demand, and marketing efforts. We offer a generous 70% revenue share, meaning you keep the majority of what you earn. Many instructors start part-time and grow into full-time income.',
            icon: 'dollar-sign',
        },
        {
            title: 'How long is the review process?',
            body: "We review applications within 5–7 business days. You'll receive email updates at every stage of the process, including detailed feedback if we need additional information. Our team reviews each application thoroughly to ensure the best fit.",
            icon: 'clock',
        },
        {
            title: 'What if I\'m not approved?',
            body: "Don't worry! We provide personalized feedback explaining areas for improvement. You can reapply after 30 days with updates based on our feedback. Many of our most successful instructors weren't accepted on their first try but improved and came back stronger.",
            icon: 'redo-alt',
        },
        {
            title: 'What support do you provide?',
            body: 'We offer comprehensive support including course creation guidance, marketing tools, technical assistance, and ongoing mentorship. Our instructor success team is available 24/7 to help you succeed and grow your teaching business.',
            icon: 'headset',
        },
        {
            title: 'Do I need technical skills?',
            body: 'Not at all! Our platform is designed to be user-friendly for instructors of all technical levels. We provide step-by-step guides, video tutorials, and personal support to help you create professional courses without any technical expertise.',
            icon: 'tools',
        },
    ];

    const steps = [
        {
            step: 1,
            title: 'Create Your Account',
            desc: 'No account needed to start—just enter your email below. Already have an account? Scroll to the application section and send your link!',
            icon: 'user-plus',
        },
        {
            step: 2,
            title: 'Start Instructor Application',
            desc: 'Click "Start Your Journey" or scroll down to the application form to begin.',
            icon: 'edit',
        },
        {
            step: 3,
            title: 'Check Your Email',
            desc: "We'll send a secure link to finish your profile.",
            icon: 'envelope',
        },
        {
            step: 4,
            title: 'Complete Your Profile',
            desc: 'Add your bio, expertise, and intro video.',
            icon: 'user-cog',
        },
        {
            step: 5,
            title: 'Submit & Get Confirmation',
            desc: "You'll get an email confirming receipt.",
            icon: 'check-circle',
        },
        {
            step: 6,
            title: 'Wait for Review',
            desc: 'Our team reviews in 5–7 business days.',
            icon: 'clock',
        },
    ];

    return (
        <GuestLayout>
            <Head title="Become an Instructor" />

            <section className="public-hero overflow-hidden bg-primary">
                <div className="section-shell">
                    <div className="relative z-10 grid items-start gap-8 lg:grid-cols-[minmax(0,1.2fr)_minmax(340px,0.8fr)] lg:gap-12">
                        <div className="space-y-7 text-white">
                            <div className="inline-flex items-center rounded-full border border-white/15 bg-white/10 px-4 py-2">
                                <i className="fas fa-chalkboard-teacher mr-2 text-red-600"></i>
                                <span className="text-sm font-semibold font-montserrat text-white">Instructor Applications</span>
                            </div>

                            <div className="space-y-4">
                                <p className="max-w-4xl text-3xl font-bold
                                text-white text-shadow-2xs font-montserrat md:text-3xl lg:text-[3rem]">
                                    Build and launch courses with a serious teaching platform
                                </p>
                                <p className="max-w-3xl text-lg leading-8 text-white/80 font-lato md:text-xl">
                                    Apply to teach, complete your profile, and get reviewed by our team. We support instructors with onboarding, course setup guidance, and a revenue model built for long-term growth.
                                </p>
                            </div>

                            <div className="flex flex-col gap-3 sm:flex-row">
                                <a href="#apply-now" className="enterprise-button enterprise-button-primary justify-center px-8 py-4">
                                    <i className="fas fa-paper-plane"></i>
                                    Start instructor application
                                </a>
                                <a href="#how-it-works" className="enterprise-button justify-center border border-white/20 bg-white/10 px-8 py-4 text-white hover:bg-white/15">
                                    <i className="fas fa-list-check"></i>
                                    Review process
                                </a>
                            </div>

                            <div className="grid gap-3 sm:grid-cols-3">
                                <div className="rounded-lg border border-white/15 bg-white/10 p-4">
                                    <p className="text-[11px] font-semibold uppercase text-white/60">Application Review</p>
                                    <p className="mt-2 text-base font-semibold text-white">5 to 7 business days</p>
                                </div>

                                <div className="rounded-lg border border-white/15 bg-white/10 p-4">
                                    <p className="text-[11px] font-semibold uppercase text-white/60">Instructor Support</p>
                                    <p className="mt-2 text-base font-semibold text-white">Guided onboarding</p>
                                </div>
                            </div>

                            <div className="grid gap-3 text-sm text-white/80 sm:grid-cols-3">
                                <div className="flex items-start gap-3 rounded-lg border border-white/10 bg-white/5 p-4">
                                    <i className="fas fa-check-circle mt-0.5 text-red-200"></i>
                                    <span>Structured review and approval process</span>
                                </div>
                                <div className="flex items-start gap-3 rounded-lg border border-white/10 bg-white/5 p-4">
                                    <i className="fas fa-check-circle mt-0.5 text-red-200"></i>
                                    <span>Course creation support from the platform team</span>
                                </div>
                                <div className="flex items-start gap-3 rounded-lg border border-white/10 bg-white/5 p-4">
                                    <i className="fas fa-check-circle mt-0.5 text-red-200"></i>
                                    <span>Scalable reach across a growing learner base</span>
                                </div>
                            </div>
                        </div>

                        <div className="overflow-hidden rounded-lg border border-white/15 bg-white text-primary shadow-sm">
                            <div className="border-b border-gray-200 bg-gray-50 px-6 py-5">
                                <p className="text-[11px] font-semibold uppercase text-gray-500">At a Glance</p>
                                <h2 className="mt-2 text-2xl font-bold font-montserrat text-primary">Instructor track summary</h2>
                                <p className="mt-2 text-sm leading-6 text-gray-600">
                                    A practical view of the opportunity before you begin the application.
                                </p>
                            </div>
                            <div className="space-y-5 p-6">
                                <div className="grid grid-cols-2 gap-4">
                                    <div className="rounded-lg bg-primary/5 p-4">
                                        <p className="text-[11px] font-semibold uppercase text-gray-500">Learner Reach</p>
                                        <p className="mt-2 text-2xl font-bold font-montserrat text-primary">50K+</p>
                                    </div>
                                    <div className="rounded-lg bg-secondary/5 p-4">
                                        <p className="text-[11px] font-semibold uppercase text-gray-500">Top Monthly Earnings</p>
                                        <p className="mt-2 text-2xl font-bold font-montserrat text-primary">$10K+</p>
                                    </div>
                                </div>

                                <div className="space-y-4 border-t border-gray-100 pt-5">
                                    <div className="flex items-start gap-3">
                                        <div className="mt-1 flex h-8 w-8 items-center justify-center rounded-lg bg-primary/10 text-primary">
                                            <i className="fas fa-envelope-open-text text-sm"></i>
                                        </div>
                                        <div>
                                            <p className="text-sm font-semibold text-primary">Secure application access</p>
                                            <p className="mt-1 text-sm leading-6 text-gray-600">
                                                Start with your email, receive your link, and complete the full profile in stages.
                                            </p>
                                        </div>
                                    </div>

                                    <div className="flex items-start gap-3">
                                        <div className="mt-1 flex h-8 w-8 items-center justify-center rounded-lg bg-primary/10 text-primary">
                                            <i className="fas fa-clipboard-check text-sm"></i>
                                        </div>
                                        <div>
                                            <p className="text-sm font-semibold text-primary">Manual quality review</p>
                                            <p className="mt-1 text-sm leading-6 text-gray-600">
                                                We assess your profile, expertise, and teaching readiness before approval.
                                            </p>
                                        </div>
                                    </div>
                                </div>

                                <div className="rounded-lg border border-gray-200 bg-gray-50 p-4">
                                    <p className="text-sm font-semibold text-primary">Included support</p>
                                    <p className="mt-2 text-sm leading-6 text-gray-600">
                                        Course setup guidance, review feedback, and operational support as you move toward launch.
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </section>

            {/* How It Works */}
            <section id="how-it-works" className="public-section bg-gray-50">
                <div className="section-shell">
                    <div className="text-center mb-16">
                        <div className="inline-flex items-center rounded-full bg-primary/10 px-4 py-2 mb-6">
                            <i className="fas fa-route mr-2 text-primary"></i>
                            <span className="text-sm font-semibold font-montserrat text-primary">Simple Process</span>
                        </div>
                        <h2 className="text-3xl md:text-4xl font-bold mb-4 font-montserrat text-primary">How the process works</h2>
                        <p className="text-gray-600 text-lg max-w-2xl mx-auto font-lato">
                            A straightforward application and review process, with clear communication at each stage.
                        </p>
                    </div>

                    <div className="grid grid-cols-1 gap-6 md:grid-cols-2 lg:grid-cols-3">
                        {steps.map((stepData) => (
                            <div key={stepData.step} className="rounded-lg border border-gray-200 bg-white p-6 shadow-sm">
                                <div className="mb-5 flex items-center justify-between">
                                    <div className="flex h-12 w-12 items-center justify-center rounded-lg bg-primary/10 text-primary font-bold">
                                        {stepData.step}
                                    </div>
                                    <div className="w-12 h-12 rounded-lg flex items-center justify-center bg-gray-50">
                                        <i className={`fas fa-${stepData.icon} text-2xl text-primary`}></i>
                                    </div>
                                </div>
                                <h3 className="text-xl font-bold mb-3 font-montserrat text-primary">{stepData.title}</h3>
                                <p className="text-gray-600 leading-relaxed font-lato">{stepData.desc}</p>
                            </div>
                        ))}
                    </div>

                    <div className="text-center mt-16">
                        <div className="mx-auto max-w-md rounded-lg border border-gray-200 bg-white p-8 shadow-sm">
                            <div className="mx-auto mb-4 flex h-16 w-16 items-center justify-center rounded-lg bg-secondary/10">
                                <i className="fas fa-question-circle text-2xl text-secondary"></i>
                            </div>
                            <p className="text-gray-600 mb-4 font-lato">Have questions about the process?</p>
                            <a href="#faq" className="enterprise-button enterprise-button-primary justify-center px-6 py-3">
                                <i className="fas fa-arrow-down mr-2"></i>
                                See our FAQ
                            </a>
                        </div>
                    </div>
                </div>
            </section>

            {/* Benefits */}
            <section className="public-section bg-primary">
                <div className="section-shell">
                    <div className="text-center mb-16">
                        <div className="inline-flex items-center rounded-full border border-white/15 bg-white/10 px-4 py-2 mb-6">
                            <i className="fas fa-heart mr-2 text-red-200"></i>
                            <span className="text-sm font-semibold font-montserrat text-white">Instructor Benefits</span>
                        </div>
                        <h2 className="text-3xl md:text-4xl font-bold text-white mb-4 font-montserrat">Why instructors choose this platform</h2>
                        <p className="text-gray-200 text-lg max-w-2xl mx-auto font-lato">
                            Join thousands of successful instructors who have transformed their expertise into thriving businesses
                        </p>
                    </div>

                    <div className="grid grid-cols-1 gap-6 md:grid-cols-3">
                        {/* Benefit 1 */}
                        <div className="benefit-card group">
                            <div className="rounded-lg border border-white/15 bg-white/10 p-8 text-center">
                                <div className="mx-auto mb-6 flex h-16 w-16 items-center justify-center rounded-lg bg-white/10">
                                    <i className="fas fa-dollar-sign text-3xl text-white"></i>
                                </div>
                                <h3 className="text-2xl font-bold text-white mb-4 font-montserrat">
                                    Earn Meaningful Income
                                </h3>
                                <p className="text-gray-200 leading-relaxed font-lato mb-6">
                                    Inspire learners, advance careers, and earn meaningful income by sharing your expertise with a global
                                    audience.
                                </p>
                                <div className="flex items-center justify-center space-x-4 text-sm">
                                    <div className="flex items-center">
                                        <i className="fas fa-percentage mr-1 text-red-200"></i>
                                        <span className="text-red-200 font-semibold">70% revenue share</span>
                                    </div>
                                </div>
                            </div>
                        </div>

                        {/* Benefit 2 */}
                        <div className="benefit-card group">
                            <div className="rounded-lg border border-white/15 bg-white/10 p-8 text-center">
                                <div className="mx-auto mb-6 flex h-16 w-16 items-center justify-center rounded-lg bg-white/10">
                                    <i className="fas fa-globe-americas text-3xl text-white"></i>
                                </div>
                                <h3 className="text-2xl font-bold text-white mb-4 font-montserrat">
                                    Global Audience
                                </h3>
                                <p className="text-gray-200 leading-relaxed font-lato mb-6">
                                    Reach learners in 190+ countries and build your personal brand while making a worldwide impact.
                                </p>
                                <div className="flex items-center justify-center space-x-4 text-sm">
                                    <div className="flex items-center">
                                        <i className="fas fa-users mr-1 text-red-200"></i>
                                        <span className="text-red-200 font-semibold">50K+ students</span>
                                    </div>
                                </div>
                            </div>
                        </div>

                        {/* Benefit 3 */}
                        <div className="benefit-card group">
                            <div className="rounded-lg border border-white/15 bg-white/10 p-8 text-center">
                                <div className="mx-auto mb-6 flex h-16 w-16 items-center justify-center rounded-lg bg-white/10">
                                    <i className="fas fa-headset text-3xl text-white"></i>
                                </div>
                                <h3 className="text-2xl font-bold text-white mb-4 font-montserrat">
                                    Full Support & Tools
                                </h3>
                                <p className="text-gray-200 leading-relaxed font-lato mb-6">
                                    From course design to marketing — we provide comprehensive support and cutting-edge tools for your
                                    success.
                                </p>
                                <div className="flex items-center justify-center space-x-4 text-sm">
                                    <div className="flex items-center">
                                        <i className="fas fa-tools mr-1 text-red-200"></i>
                                        <span className="text-red-200 font-semibold">24/7 support</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    {/* Additional Benefits Grid */}
                    <div className="grid grid-cols-2 md:grid-cols-4 gap-6 mt-16 max-w-4xl mx-auto">
                        <div className="text-center">
                            <div className="text-3xl font-bold text-accent mb-2 font-montserrat">95%</div>
                            <p className="text-gray-300 text-sm font-lato">Instructor satisfaction</p>
                        </div>
                        <div className="text-center">
                            <div className="text-3xl font-bold text-secondary mb-2 font-montserrat">24/7</div>
                            <p className="text-gray-300 text-sm font-lato">Support available</p>
                        </div>
                        <div className="text-center">
                            <div className="text-3xl font-bold text-accent mb-2 font-montserrat">$0</div>
                            <p className="text-gray-300 text-sm font-lato">Setup fees</p>
                        </div>
                        <div className="text-center">
                            <div className="text-3xl font-bold text-secondary mb-2 font-montserrat">∞</div>
                            <p className="text-gray-300 text-sm font-lato">Earning potential</p>
                        </div>
                    </div>
                </div>
            </section>

            {/* Application CTA */}
            <section id="apply-now" className="public-section bg-gray-50">
                <div className="section-shell">
                    <div className="max-w-5xl mx-auto">
                        <div className="overflow-hidden rounded-lg border border-gray-200 bg-white shadow-sm">
                            <div className="bg-primary p-8 text-center md:p-10">
                                <div className="relative z-10">
                                    <div className="mx-auto mb-6 flex h-16 w-16 items-center justify-center rounded-lg bg-white/10">
                                        <i className="fas fa-graduation-cap text-3xl text-white"></i>
                                    </div>

                                    <h2 className="text-3xl md:text-4xl font-bold text-white mb-4 font-montserrat">
                                        Start your instructor application
                                    </h2>
                                    <p className="text-white/80 text-lg max-w-2xl mx-auto font-lato">
                                        Submit your details to receive a secure link for the full instructor application.
                                    </p>
                                </div>
                            </div>

                            <div className="p-8 md:p-12">
                                <form onSubmit={handleSubmit} className="max-w-md mx-auto">
                                    {auth?.user ? (
                                        <div className="mb-8 rounded-lg border border-accent/20 bg-accent/5 p-6">
                                            <div className="flex items-center justify-center mb-3">
                                                <i className="fas fa-user-check text-2xl text-accent"></i>
                                            </div>
                                            <p className="text-center font-lato text-primary">
                                                You're logged in as <strong className="text-accent">{auth.user.email}</strong>
                                            </p>
                                        </div>
                                    ) : (
                                        <div className="mb-8 space-y-6">
                                            <div>
                                                <label className="block text-sm font-semibold mb-2 font-montserrat text-primary">
                                                    <i className="fas fa-user mr-2 text-accent"></i>Full Name
                                                </label>
                                                <input
                                                    type="text"
                                                    name="name"
                                                    required
                                                    value={formData.name}
                                                    onChange={(e) => setFormData({ ...formData, name: e.target.value })}
                                                    className="public-input"
                                                    placeholder="Enter your full name"
                                                />
                                            </div>

                                            <div>
                                                <label className="block text-sm font-semibold mb-2 font-montserrat text-primary">
                                                    <i className="fas fa-envelope mr-2 text-secondary"></i>Email Address
                                                </label>
                                                <input
                                                    type="email"
                                                    name="email"
                                                    required
                                                    value={formData.email}
                                                    onChange={(e) => setFormData({ ...formData, email: e.target.value })}
                                                    className="public-input"
                                                    placeholder="your.email@example.com"
                                                />
                                            </div>
                                        </div>
                                    )}

                                    <button
                                        type="submit"
                                        disabled={isSubmitting}
                                        className="enterprise-button enterprise-button-primary w-full justify-center py-4 text-lg disabled:opacity-50 disabled:cursor-not-allowed"
                                    >
                                        {isSubmitting ? (
                                            <>
                                                <svg className="animate-spin h-5 w-5 text-white" fill="none" viewBox="0 0 24 24">
                                                    <circle
                                                        className="opacity-25"
                                                        cx="12"
                                                        cy="12"
                                                        r="10"
                                                        stroke="currentColor"
                                                        strokeWidth="4"
                                                    ></circle>
                                                    <path className="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v8z"></path>
                                                </svg>
                                                Sending...
                                            </>
                                        ) : (
                                            <>
                                                <i className="fas fa-paper-plane"></i>
                                                Send My Application Link
                                            </>
                                        )}
                                    </button>

                                    <div className="mt-8 space-y-4">
                                        <div className="flex items-center justify-center space-x-6 text-sm text-gray-500">
                                            <div className="flex items-center">
                                                <i className="fas fa-shield-alt mr-1 text-accent"></i>
                                                <span>Secure Process</span>
                                            </div>
                                            <div className="flex items-center">
                                                <i className="fas fa-clock mr-1 text-secondary"></i>
                                                <span>5-7 Day Review</span>
                                            </div>
                                        </div>

                                        <p className="text-center text-gray-500 text-sm font-lato">
                                            By applying, you agree to our{' '}
                                            <a href="#" className="font-medium hover:underline text-primary">
                                                Instructor Terms
                                            </a>{' '}
                                            and{' '}
                                            <a href="#" className="font-medium hover:underline text-primary">
                                                Privacy Policy
                                            </a>
                                            .
                                            <br />
                                            Check your spam folder if you don't receive our email.
                                        </p>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </section>

            {/* Testimonials */}
            <section className="py-20 bg-white">
                <div className="container mx-auto px-4">
                    <div className="text-center mb-16">
                        <div className="inline-flex items-center px-4 py-2 rounded-full mb-6 bg-secondary/10">
                            <i className="fas fa-quote-left mr-2 text-secondary"></i>
                            <span className="text-sm font-semibold font-montserrat text-secondary">Success Stories</span>
                        </div>
                        <h2 className="text-3xl md:text-4xl lg:text-5xl font-bold mb-4 font-montserrat text-primary">
                            Instructor Success Stories
                        </h2>
                        <p className="text-gray-600 text-lg max-w-2xl mx-auto font-lato">
                            Hear from successful instructors who have transformed their expertise into thriving careers
                        </p>
                    </div>

                    <div className="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8 max-w-6xl mx-auto">
                        {/* Testimonial 1 */}
                        <div className="group">
                            <div className="bg-white p-8 rounded-2xl shadow-lg hover:shadow-2xl transition-all duration-500 border border-gray-100 group-hover:border-gray-200 h-full">
                                {/* Rating */}
                                <div className="flex items-center mb-4">
                                    {[...Array(5)].map((_, i) => (
                                        <i key={i} className="fas fa-star text-yellow-400"></i>
                                    ))}
                                </div>

                                {/* Quote */}
                                <blockquote className="text-gray-700 italic mb-6 font-lato leading-relaxed">
                                    "Teaching here changed my career completely. I now reach students in 30+ countries and earn more than
                                    my previous full-time corporate job. The support team is incredible!"
                                </blockquote>

                                {/* Author */}
                                <div className="flex items-center">
                                    <img
                                        src="https://randomuser.me/api/portraits/women/42.jpg"
                                        alt="Sarah Johnson"
                                        className="w-16 h-16 rounded-full object-cover mr-4 border-4 border-gray-100"
                                    />
                                    <div>
                                        <h4 className="text-lg font-bold font-montserrat text-primary">Sarah Johnson</h4>
                                        <div className="text-gray-500 text-sm font-lato">Data Science Instructor</div>
                                        <div className="flex items-center mt-1">
                                            <i className="fas fa-users text-xs mr-1 text-accent"></i>
                                            <span className="text-xs text-accent">12,000+ students</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        {/* Testimonial 2 */}
                        <div className="group">
                            <div className="bg-white p-8 rounded-2xl shadow-lg hover:shadow-2xl transition-all duration-500 border border-gray-100 group-hover:border-gray-200 h-full">
                                {/* Rating */}
                                <div className="flex items-center mb-4">
                                    {[...Array(5)].map((_, i) => (
                                        <i key={i} className="fas fa-star text-yellow-400"></i>
                                    ))}
                                </div>

                                {/* Quote */}
                                <blockquote className="text-gray-700 italic mb-6 font-lato leading-relaxed">
                                    "The platform made it incredibly easy to create professional courses. The tools are intuitive, and now I
                                    teach full-time doing what I'm passionate about."
                                </blockquote>

                                {/* Author */}
                                <div className="flex items-center">
                                    <img
                                        src="https://randomuser.me/api/portraits/men/32.jpg"
                                        alt="Michael Chen"
                                        className="w-16 h-16 rounded-full object-cover mr-4 border-4 border-gray-100"
                                    />
                                    <div>
                                        <h4 className="text-lg font-bold font-montserrat text-primary">Michael Chen</h4>
                                        <div className="text-gray-500 text-sm font-lato">Web Development Instructor</div>
                                        <div className="flex items-center mt-1">
                                            <i className="fas fa-users text-xs mr-1 text-accent"></i>
                                            <span className="text-xs text-accent">8,500+ students</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        {/* Testimonial 3 */}
                        <div className="group">
                            <div className="bg-white p-8 rounded-2xl shadow-lg hover:shadow-2xl transition-all duration-500 border border-gray-100 group-hover:border-gray-200 h-full">
                                {/* Rating */}
                                <div className="flex items-center mb-4">
                                    {[...Array(5)].map((_, i) => (
                                        <i key={i} className="fas fa-star text-yellow-400"></i>
                                    ))}
                                </div>

                                {/* Quote */}
                                <blockquote className="text-gray-700 italic mb-6 font-lato leading-relaxed">
                                    "From idea to launch in just 3 weeks! The community support and mentorship program helped me create my
                                    first course. Already planning my second one!"
                                </blockquote>

                                {/* Author */}
                                <div className="flex items-center">
                                    <img
                                        src="https://randomuser.me/api/portraits/women/68.jpg"
                                        alt="Priya Patel"
                                        className="w-16 h-16 rounded-full object-cover mr-4 border-4 border-gray-100"
                                    />
                                    <div>
                                        <h4 className="text-lg font-bold font-montserrat text-primary">Priya Patel</h4>
                                        <div className="text-gray-500 text-sm font-lato">Marketing Strategy Instructor</div>
                                        <div className="flex items-center mt-1">
                                            <i className="fas fa-users text-xs mr-1 text-accent"></i>
                                            <span className="text-xs text-accent">3,200+ students</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    {/* Call to Action */}
                    <div className="text-center mt-16">
                        <div className="bg-linear-to-r from-gray-50 to-gray-100 p-8 rounded-2xl max-w-2xl mx-auto">
                            <h3 className="text-2xl font-bold mb-4 font-montserrat text-primary">Ready to Share Your Success Story?</h3>
                            <p className="text-gray-600 mb-6 font-lato">
                                Join thousands of successful instructors who are making a difference
                            </p>
                            <a
                                href="#apply-now"
                                className="inline-flex items-center px-8 py-4 rounded-xl font-bold text-white transition-all duration-300 hover:shadow-lg transform hover:scale-105 font-montserrat bg-secondary hover:bg-red-700"
                            >
                                <i className="fas fa-arrow-up mr-2"></i>
                                Apply Now
                            </a>
                        </div>
                    </div>
                </div>
            </section>

            {/* FAQ */}
            <section id="faq" className="public-section bg-gray-50">
                <div className="section-shell max-w-4xl">
                    <div className="text-center mb-16">
                        <div className="inline-flex items-center px-4 py-2 rounded-full mb-6 bg-primary/10">
                            <i className="fas fa-question-circle mr-2 text-primary"></i>
                            <span className="text-sm font-semibold font-montserrat text-primary">Got Questions?</span>
                        </div>
                        <h2 className="text-3xl md:text-4xl lg:text-5xl font-bold mb-4 font-montserrat text-primary">
                            Frequently Asked Questions
                        </h2>
                        <p className="text-gray-600 text-lg max-w-2xl mx-auto font-lato">
                            Everything you need to know about becoming an instructor with us
                        </p>
                    </div>

                    <div className="space-y-6">
                        {faqData.map((faq, index) => (
                            <div key={index} className="faq-item group">
                                <div className="overflow-hidden rounded-lg border border-gray-200 bg-white shadow-sm">
                                    <button
                                        className="faq-question flex w-full items-center justify-between px-6 py-5 text-left font-semibold transition-all duration-300 hover:bg-gray-50 group"
                                        onClick={() => toggleFAQ(index)}
                                    >
                                        <div className="flex items-center">
                                            <div
                                                className={`mr-4 flex h-12 w-12 items-center justify-center rounded-lg transition-all duration-300 ${
                                                    expandedFAQ === index ? 'bg-secondary/10' : 'bg-accent/10'
                                                }`}
                                            >
                                                <i
                                                    className={`fas fa-${faq.icon} text-lg transition-all duration-300 ${
                                                        expandedFAQ === index ? 'text-secondary' : 'text-accent'
                                                    }`}
                                                ></i>
                                            </div>
                                            <span
                                                className={`text-base md:text-lg font-montserrat transition-colors duration-300 ${
                                                    expandedFAQ === index ? 'text-secondary' : 'text-primary'
                                                }`}
                                            >
                                                {faq.title}
                                            </span>
                                        </div>
                                        <i
                                            className={`fas fa-chevron-down text-lg transition-all duration-300 group-hover:scale-110 text-accent ${
                                                expandedFAQ === index ? 'rotate-180' : ''
                                            }`}
                                        ></i>
                                    </button>
                                    {expandedFAQ === index && (
                                        <div className="faq-answer border-t border-gray-100 px-6 pb-5 text-gray-600">
                                            <div className="pt-4 font-lato leading-relaxed">{faq.body}</div>
                                        </div>
                                    )}
                                </div>
                            </div>
                        ))}
                    </div>

                    {/* Additional Help */}
                    <div className="text-center mt-16">
                        <div className="bg-white p-8 rounded-2xl shadow-lg border border-gray-100 max-w-2xl mx-auto">
                            <div className="w-16 h-16 rounded-full flex items-center justify-center mx-auto mb-6 bg-secondary/10">
                                <i className="fas fa-comments text-2xl text-secondary"></i>
                            </div>
                            <h3 className="text-xl font-bold mb-4 font-montserrat text-primary">Still Have Questions?</h3>
                            <p className="text-gray-600 mb-6 font-lato">Our instructor success team is here to help you get started</p>
                            <div className="flex flex-col sm:flex-row gap-4 justify-center">
                                <a
                                    href="mailto:instructors@example.com"
                                    className="inline-flex items-center px-6 py-3 rounded-xl font-semibold transition-all duration-300 hover:shadow-lg font-montserrat bg-primary text-white hover:bg-primary"
                                >
                                    <i className="fas fa-envelope mr-2"></i>
                                    Email Us
                                </a>
                                <a
                                    href="#apply-now"
                                    className="inline-flex items-center px-6 py-3 rounded-xl font-semibold border-2 transition-all duration-300 hover:bg-gray-50 font-montserrat text-accent border-accent"
                                >
                                    <i className="fas fa-rocket mr-2"></i>
                                    Apply Now
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </section>
        </GuestLayout>
    );
}
