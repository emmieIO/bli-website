import { Head, useForm } from '@inertiajs/react';
import GuestLayout from '@/Layouts/GuestLayout';
import { FormEvent } from 'react';

export default function BecomeASpeaker() {
    const form = useForm({
        name: '',
        email: '',
        phone: '',
        headline: '',
        organization: '',
        password: '',
        password_confirmation: '',
        bio: '',
        photo: null as File | null,
        linkedin: '',
        website: '',
        agree_terms: false,
    });

    const handleSubmit = (e: FormEvent) => {
        e.preventDefault();
        form.post(route('become-a-speaker.store'), {
            forceFormData: true,
            onSuccess: () => {
                form.reset();
            },
        });
    };

    return (
        <GuestLayout>
            <Head title="Become a Speaker" />

            {/* Hero Section */}
            <section className="public-section min-h-screen">
                <div className="section-shell">
                    {/* Header Section */}
                    <div className="text-center mb-16" data-aos="fade-up">
                        <div className="mb-6 inline-flex items-center rounded-full bg-accent/5 px-4 py-2">
                            <i className="fas fa-microphone mr-2 text-accent"></i>
                            <span className="text-sm font-semibold text-accent">
                                Speaker Community
                            </span>
                        </div>

                        <h1 className="public-hero-title mb-6">
                            Become a{' '}
                            <span className="text-accent">Speaker</span>
                        </h1>

                        <p className="public-hero-copy mx-auto mb-8 text-xl md:text-2xl">
                            Join our prestigious speaker community and share your expertise with industry leaders,{' '}
                            <strong className="text-primary">inspiring the next generation</strong> of professionals.
                        </p>

                        {/* Trust Indicators */}
                        <div className="flex flex-col sm:flex-row items-center justify-center space-y-4 sm:space-y-0 sm:space-x-8 mb-8">
                            <div className="flex items-center">
                                <i className="fas fa-users mr-2 text-primary"></i>
                                <span className="text-sm font-medium text-gray-600">Join 200+ Expert Speakers</span>
                            </div>
                            <div className="flex items-center">
                                <i className="fas fa-globe mr-2 text-accent"></i>
                                <span className="text-sm font-medium text-gray-600">Global Audience Reach</span>
                            </div>
                            <div className="flex items-center">
                                <i className="fas fa-award mr-2 text-primary"></i>
                                <span className="text-sm font-medium text-gray-600">Premium Events Platform</span>
                            </div>
                        </div>
                    </div>

                    {/* Registration Form */}
                    <div
                        className="public-card overflow-hidden"
                        data-aos="fade-up"
                        data-aos-delay="200"
                    >
                        <div className="grid grid-cols-1 lg:grid-cols-3">
                            {/* Left Side - Form */}
                            <div className="lg:col-span-2 p-10 md:p-12">
                                <form onSubmit={handleSubmit} className="space-y-6">
                                    {/* Personal Information */}
                                    <div className="space-y-6" data-aos="fade-up" data-aos-delay="100">
                                        <div className="flex items-center mb-6">
                                            <div className="mr-4 flex h-12 w-12 items-center justify-center rounded-lg bg-primary">
                                                <i className="fas fa-user text-white text-lg"></i>
                                            </div>
                                            <div>
                                                <h3 className="text-xl font-bold text-primary">
                                                    Personal Information
                                                </h3>
                                                <p className="text-sm text-gray-500">Tell us about yourself</p>
                                            </div>
                                        </div>

                                        <div className="grid grid-cols-1 md:grid-cols-2 gap-6">
                                            <div>
                                                <label
                                                    className="mb-2 block text-sm font-semibold text-primary"
                                                >
                                                    <i className="fas fa-user mr-2 text-primary"></i>Full Name
                                                </label>
                                                <input
                                                    type="text"
                                                    value={form.data.name}
                                                    onChange={(e) => form.setData('name', e.target.value)}
                                                    className="public-input"
                                                    placeholder="Enter your full name"
                                                    required
                                                />
                                                {form.errors.name && (
                                                    <small className="text-xs mt-1 block" style={{ color: '#ed1c24' }}>
                                                        {form.errors.name}
                                                    </small>
                                                )}
                                            </div>
                                            <div>
                                                <label
                                                    className="mb-2 block text-sm font-semibold text-primary"
                                                >
                                                    <i className="fas fa-envelope mr-2 text-accent"></i>Email Address
                                                </label>
                                                <input
                                                    type="email"
                                                    value={form.data.email}
                                                    onChange={(e) => form.setData('email', e.target.value)}
                                                    className="public-input"
                                                    placeholder="your.email@example.com"
                                                    required
                                                />
                                                {form.errors.email && (
                                                    <small className="text-xs mt-1 block" style={{ color: '#ed1c24' }}>
                                                        {form.errors.email}
                                                    </small>
                                                )}
                                            </div>
                                        </div>

                                        <div className="grid grid-cols-1 md:grid-cols-2 gap-6">
                                            <div>
                                                <label
                                                    className="mb-2 block text-sm font-semibold text-primary"
                                                >
                                                    <i className="fas fa-phone mr-2 text-primary"></i>Phone Number
                                                </label>
                                                <input
                                                    type="tel"
                                                    value={form.data.phone}
                                                    onChange={(e) => form.setData('phone', e.target.value)}
                                                    className="public-input"
                                                    placeholder="+234 803 123 4567"
                                                    required
                                                />
                                                {form.errors.phone && (
                                                    <small className="text-xs mt-1 block" style={{ color: '#ed1c24' }}>
                                                        {form.errors.phone}
                                                    </small>
                                                )}
                                            </div>
                                            <div>
                                                <label
                                                    className="mb-2 block text-sm font-semibold text-primary"
                                                >
                                                    <i className="fas fa-briefcase mr-2 text-primary"></i>
                                                    Professional Title
                                                </label>
                                                <input
                                                    type="text"
                                                    value={form.data.headline}
                                                    onChange={(e) => form.setData('headline', e.target.value)}
                                                    className="public-input"
                                                    placeholder="e.g. Senior Developer, CEO, Consultant"
                                                    required
                                                />
                                                {form.errors.headline && (
                                                    <small className="text-xs mt-1 block" style={{ color: '#ed1c24' }}>
                                                        {form.errors.headline}
                                                    </small>
                                                )}
                                            </div>
                                        </div>

                                        <div>
                                            <label
                                                className="mb-2 block text-sm font-semibold text-primary"
                                            >
                                                <i className="fas fa-building mr-2 text-accent"></i>Organization
                                            </label>
                                            <input
                                                type="text"
                                                value={form.data.organization}
                                                onChange={(e) => form.setData('organization', e.target.value)}
                                                className="public-input"
                                                placeholder="Your company or organization (optional)"
                                            />
                                            {form.errors.organization && (
                                                <small className="text-xs mt-1 block" style={{ color: '#ed1c24' }}>
                                                    {form.errors.organization}
                                                </small>
                                            )}
                                        </div>
                                    </div>

                                    {/* Account Security */}
                                    <div className="space-y-6" data-aos="fade-up" data-aos-delay="200">
                                        <div className="flex items-center mb-6">
                                            <div className="mr-4 flex h-12 w-12 items-center justify-center rounded-lg bg-accent">
                                                <i className="fas fa-lock text-white text-lg"></i>
                                            </div>
                                            <div>
                                                <h3 className="text-xl font-bold text-primary">
                                                    Account Security
                                                </h3>
                                                <p className="text-sm text-gray-500">Set up your account credentials</p>
                                            </div>
                                        </div>

                                        <div className="grid grid-cols-1 md:grid-cols-2 gap-6">
                                            <div>
                                                <label
                                                    className="mb-2 block text-sm font-semibold text-primary"
                                                >
                                                    <i className="fas fa-key mr-2 text-accent"></i>Password
                                                </label>
                                                <input
                                                    type="password"
                                                    value={form.data.password}
                                                    onChange={(e) => form.setData('password', e.target.value)}
                                                    className="public-input"
                                                    placeholder="Create a strong password"
                                                    required
                                                />
                                                {form.errors.password && (
                                                    <small className="text-xs mt-1 block" style={{ color: '#ed1c24' }}>
                                                        {form.errors.password}
                                                    </small>
                                                )}
                                            </div>
                                            <div>
                                                <label
                                                    className="mb-2 block text-sm font-semibold text-primary"
                                                >
                                                    <i className="fas fa-check-double mr-2 text-primary"></i>
                                                    Confirm Password
                                                </label>
                                                <input
                                                    type="password"
                                                    value={form.data.password_confirmation}
                                                    onChange={(e) => form.setData('password_confirmation', e.target.value)}
                                                    className="public-input"
                                                    placeholder="Confirm your password"
                                                    required
                                                />
                                            </div>
                                        </div>
                                    </div>

                                    {/* Professional Profile */}
                                    <div className="space-y-4" data-aos="fade-up" data-aos-delay="300">
                                        <div className="flex items-center mb-6">
                                            <div className="mr-4 flex h-12 w-12 items-center justify-center rounded-lg bg-primary">
                                                <i className="fas fa-briefcase text-white text-lg"></i>
                                            </div>
                                            <div>
                                                <h3 className="text-xl font-bold text-primary">
                                                    Professional Profile
                                                </h3>
                                                <p className="text-sm text-gray-500">Share your expertise and background</p>
                                            </div>
                                        </div>

                                        <div>
                                            <label htmlFor="bio" className="mb-2 block text-sm font-medium text-gray-700">
                                                Professional Bio
                                            </label>
                                            <textarea
                                                id="bio"
                                                value={form.data.bio}
                                                onChange={(e) => form.setData('bio', e.target.value)}
                                                rows={4}
                                                className="public-input"
                                                placeholder="Tell us about your expertise, experience, and speaking background..."
                                                required
                                            ></textarea>
                                            {form.errors.bio && (
                                                <small className="text-xs text-secondary">{form.errors.bio}</small>
                                            )}
                                        </div>

                                        {/* Profile Photo */}
                                        <div className="space-y-3">
                                            <label htmlFor="photo" className="block text-sm font-medium text-gray-700">
                                                Profile Photo
                                            </label>
                                            <input
                                                id="photo"
                                                type="file"
                                                accept="image/*"
                                                onChange={(e) => form.setData('photo', e.target.files?.[0] || null)}
                                                className="block w-full rounded-lg border border-gray-300 px-4 file:mr-4 file:border-0 file:bg-primary file:px-4 file:py-3 file:text-white"
                                            />
                                            <p className="text-xs text-gray-500 mt-1">JPG, PNG up to 2MB</p>
                                            {form.errors.photo && (
                                                <small className="text-xs text-secondary">{form.errors.photo}</small>
                                            )}
                                        </div>

                                        {/* Social Links */}
                                        <div className="grid grid-cols-1 md:grid-cols-2 gap-4">
                                            <div>
                                                <label className="mb-2 block text-sm font-medium text-gray-700">
                                                    LinkedIn Profile
                                                </label>
                                                <input
                                                    type="url"
                                                    value={form.data.linkedin}
                                                    onChange={(e) => form.setData('linkedin', e.target.value)}
                                                    placeholder="https://linkedin.com/in/yourprofile"
                                                    className="public-input"
                                                />
                                                {form.errors.linkedin && (
                                                    <small className="text-xs text-secondary">{form.errors.linkedin}</small>
                                                )}
                                            </div>
                                            <div>
                                                <label className="mb-2 block text-sm font-medium text-gray-700">
                                                    Website
                                                </label>
                                                <input
                                                    type="url"
                                                    value={form.data.website}
                                                    onChange={(e) => form.setData('website', e.target.value)}
                                                    placeholder="https://yourwebsite.com"
                                                    className="public-input"
                                                />
                                                {form.errors.website && (
                                                    <small className="text-xs text-secondary">{form.errors.website}</small>
                                                )}
                                            </div>
                                        </div>
                                    </div>

                                    {/* Terms and Submit */}
                                    <div className="space-y-4 pt-6 border-t border-gray-200" data-aos="fade-up" data-aos-delay="400">
                                        <div className="flex items-start gap-3 rounded-lg bg-primary/5 p-4">
                                            <input
                                                id="agree_terms"
                                                type="checkbox"
                                                checked={form.data.agree_terms}
                                                onChange={(e) => form.setData('agree_terms', e.target.checked)}
                                                className="h-4 w-4 border-gray-300 rounded focus:ring-2 transition-all duration-300 mt-1"
                                                required
                                            />
                                            <label htmlFor="agree_terms" className="text-sm leading-relaxed text-gray-700">
                                                I agree to the{' '}
                                                <a
                                                    href={route('terms-of-service')}
                                                    className="font-medium text-primary transition-colors duration-300 hover:underline"
                                                >
                                                    Terms of Service
                                                </a>{' '}
                                                and{' '}
                                                <a
                                                    href={route('privacy-policy')}
                                                    className="font-medium text-primary transition-colors duration-300 hover:underline"
                                                >
                                                    Privacy Policy
                                                </a>
                                                . I understand that my profile will be reviewed before being added to the speaker
                                                directory.
                                            </label>
                                        </div>

                                        <button
                                            type="submit"
                                            disabled={form.processing}
                                            className="enterprise-button enterprise-button-primary w-full justify-center py-4 disabled:cursor-not-allowed disabled:opacity-50"
                                        >
                                            {form.processing && <i className="fas fa-spinner animate-spin text-lg"></i>}
                                            <i className="fas fa-paper-plane"></i>
                                            {form.processing ? 'Submitting...' : 'Register as Speaker'}
                                        </button>
                                    </div>
                                </form>
                            </div>

                            {/* Right Side - Benefits */}
                            <div className="border-l border-gray-200 bg-gray-50 p-10 md:p-12">
                                <div className="space-y-8">
                                    <div className="text-center">
                                        <div className="mx-auto mb-6 flex h-16 w-16 items-center justify-center rounded-lg bg-primary/10">
                                            <i className="fas fa-star text-2xl text-primary"></i>
                                        </div>
                                        <h3 className="mb-4 text-2xl font-bold text-primary">Speaker Benefits</h3>
                                        <p className="text-gray-600">Join our speaker community and contribute to flagship events.</p>
                                    </div>

                                    <div className="space-y-6">
                                        <div className="rounded-lg border border-gray-200 bg-white p-4">
                                            <div className="flex items-start gap-4">
                                                <div className="flex h-12 w-12 shrink-0 items-center justify-center rounded-lg bg-primary/10">
                                                    <i className="fas fa-users text-lg text-primary"></i>
                                                </div>
                                                <div>
                                                    <h4 className="mb-2 font-bold text-primary">Global Audience Reach</h4>
                                                    <p className="text-sm leading-relaxed text-gray-600">
                                                        Share your expertise with thousands of passionate learners and industry leaders
                                                        worldwide.
                                                    </p>
                                                </div>
                                            </div>
                                        </div>

                                        <div className="rounded-lg border border-gray-200 bg-white p-4">
                                            <div className="flex items-start gap-4">
                                                <div className="flex h-12 w-12 shrink-0 items-center justify-center rounded-lg bg-accent/10">
                                                    <i className="fas fa-award text-lg text-accent"></i>
                                                </div>
                                                <div>
                                                    <h4 className="mb-2 font-bold text-primary">Build Your Brand</h4>
                                                    <p className="text-sm leading-relaxed text-gray-600">
                                                        Enhance your professional reputation and establish yourself as a thought leader in
                                                        your industry.
                                                    </p>
                                                </div>
                                            </div>
                                        </div>

                                        <div className="rounded-lg border border-gray-200 bg-white p-4">
                                            <div className="flex items-start gap-4">
                                                <div className="flex h-12 w-12 shrink-0 items-center justify-center rounded-lg bg-primary/10">
                                                    <i className="fas fa-handshake text-lg text-primary"></i>
                                                </div>
                                                <div>
                                                    <h4 className="mb-2 font-bold text-primary">Premium Networking</h4>
                                                    <p className="text-sm leading-relaxed text-gray-600">
                                                        Connect with other experts, C-level executives, and thought leaders in exclusive
                                                        events.
                                                    </p>
                                                </div>
                                            </div>
                                        </div>

                                        <div className="rounded-lg border border-gray-200 bg-white p-4">
                                            <div className="flex items-start gap-4">
                                                <div className="flex h-12 w-12 shrink-0 items-center justify-center rounded-lg bg-accent/10">
                                                    <i className="fas fa-microphone-alt text-lg text-accent"></i>
                                                </div>
                                                <div>
                                                    <h4 className="mb-2 font-bold text-primary">Exclusive Opportunities</h4>
                                                    <p className="text-sm leading-relaxed text-gray-600">
                                                        Get priority access to high-profile speaking engagements and premium corporate events.
                                                    </p>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    {/* Stats */}
                                    <div className="grid grid-cols-2 gap-4 border-t border-gray-200 pt-6">
                                        <div className="text-center">
                                            <div className="mb-1 text-2xl font-bold text-primary">
                                                200+
                                            </div>
                                            <div className="text-xs text-gray-500">Expert Speakers</div>
                                        </div>
                                        <div className="text-center">
                                            <div className="mb-1 text-2xl font-bold text-accent">
                                                50K+
                                            </div>
                                            <div className="text-xs text-gray-500">Audience Reached</div>
                                        </div>
                                    </div>

                                    {/* Already have account */}
                                    <div className="border-t border-gray-200 pt-6">
                                        <p className="mb-4 text-center text-sm text-gray-600">Already have an account?</p>
                                        <a
                                            href={route('login')}
                                            className="enterprise-button enterprise-button-outline w-full justify-center"
                                        >
                                            <i className="fas fa-sign-in-alt mr-2"></i>
                                            Sign In Here
                                        </a>
                                    </div>

                                    {/* Support Info */}
                                    <div className="rounded-lg border border-gray-200 bg-white p-6">
                                        <div className="flex items-center gap-4">
                                            <div className="flex h-12 w-12 items-center justify-center rounded-lg bg-primary/10">
                                                <i className="fas fa-headset text-lg text-primary"></i>
                                            </div>
                                            <div>
                                                <h4 className="text-sm font-bold text-primary">Need Help?</h4>
                                                <p className="text-xs text-gray-600">Contact our speaker success team</p>
                                                <a
                                                    href="mailto:speakers@beaconleadership.org"
                                                    className="text-xs font-medium text-accent hover:underline"
                                                >
                                                    speakers@beaconleadership.org
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </section>
        </GuestLayout>
    );
}
