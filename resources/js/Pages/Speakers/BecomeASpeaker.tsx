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
            <section className="min-h-screen py-12 bg-gradient-to-br from-gray-50 via-white to-gray-100">
                <div className="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                    {/* Header Section */}
                    <div className="text-center mb-16" data-aos="fade-up">
                        <div
                            className="inline-flex items-center px-4 py-2 rounded-full mb-6"
                            style={{ background: 'rgba(237, 28, 36, 0.1)' }}
                        >
                            <i className="fas fa-microphone mr-2" style={{ color: '#ed1c24' }}></i>
                            <span className="text-sm font-semibold font-montserrat" style={{ color: '#ed1c24' }}>
                                Speaker Community
                            </span>
                        </div>

                        <h1
                            className="text-4xl md:text-5xl lg:text-6xl font-bold mb-6 font-montserrat"
                            style={{ color: '#002147' }}
                        >
                            Become a{' '}
                            <span className="relative">
                                <span className="relative z-10" style={{ color: '#ed1c24' }}>
                                    Speaker
                                </span>
                                <div
                                    className="absolute -bottom-2 left-0 w-full h-4 opacity-30"
                                    style={{ background: '#ed1c24', transform: 'skew(-12deg)' }}
                                ></div>
                            </span>
                        </h1>

                        <p className="text-xl md:text-2xl mb-8 text-gray-600 leading-relaxed font-lato max-w-3xl mx-auto">
                            Join our prestigious speaker community and share your expertise with industry leaders,{' '}
                            <strong style={{ color: '#00a651' }}>inspiring the next generation</strong> of professionals.
                        </p>

                        {/* Trust Indicators */}
                        <div className="flex flex-col sm:flex-row items-center justify-center space-y-4 sm:space-y-0 sm:space-x-8 mb-8">
                            <div className="flex items-center">
                                <i className="fas fa-users mr-2" style={{ color: '#00a651' }}></i>
                                <span className="text-sm font-medium text-gray-600 font-lato">Join 200+ Expert Speakers</span>
                            </div>
                            <div className="flex items-center">
                                <i className="fas fa-globe mr-2" style={{ color: '#ed1c24' }}></i>
                                <span className="text-sm font-medium text-gray-600 font-lato">Global Audience Reach</span>
                            </div>
                            <div className="flex items-center">
                                <i className="fas fa-award mr-2" style={{ color: '#002147' }}></i>
                                <span className="text-sm font-medium text-gray-600 font-lato">Premium Events Platform</span>
                            </div>
                        </div>
                    </div>

                    {/* Registration Form */}
                    <div
                        className="bg-white rounded-3xl shadow-2xl border border-gray-100 overflow-hidden"
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
                                            <div
                                                className="w-12 h-12 rounded-2xl flex items-center justify-center mr-4"
                                                style={{ background: 'linear-gradient(135deg, #00a651 0%, #15803d 100%)' }}
                                            >
                                                <i className="fas fa-user text-white text-lg"></i>
                                            </div>
                                            <div>
                                                <h3 className="text-xl font-bold font-montserrat" style={{ color: '#002147' }}>
                                                    Personal Information
                                                </h3>
                                                <p className="text-sm text-gray-500 font-lato">Tell us about yourself</p>
                                            </div>
                                        </div>

                                        <div className="grid grid-cols-1 md:grid-cols-2 gap-6">
                                            <div>
                                                <label
                                                    className="block text-sm font-semibold mb-2 font-montserrat"
                                                    style={{ color: '#002147' }}
                                                >
                                                    <i className="fas fa-user mr-2" style={{ color: '#00a651' }}></i>Full Name
                                                </label>
                                                <input
                                                    type="text"
                                                    value={form.data.name}
                                                    onChange={(e) => form.setData('name', e.target.value)}
                                                    className="w-full px-4 py-3 border-2 border-gray-200 rounded-xl focus:outline-none focus:border-[#00a651] transition-all duration-300 font-lato"
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
                                                    className="block text-sm font-semibold mb-2 font-montserrat"
                                                    style={{ color: '#002147' }}
                                                >
                                                    <i className="fas fa-envelope mr-2" style={{ color: '#ed1c24' }}></i>Email Address
                                                </label>
                                                <input
                                                    type="email"
                                                    value={form.data.email}
                                                    onChange={(e) => form.setData('email', e.target.value)}
                                                    className="w-full px-4 py-3 border-2 border-gray-200 rounded-xl focus:outline-none focus:border-[#ed1c24] transition-all duration-300 font-lato"
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
                                                    className="block text-sm font-semibold mb-2 font-montserrat"
                                                    style={{ color: '#002147' }}
                                                >
                                                    <i className="fas fa-phone mr-2" style={{ color: '#002147' }}></i>Phone Number
                                                </label>
                                                <input
                                                    type="tel"
                                                    value={form.data.phone}
                                                    onChange={(e) => form.setData('phone', e.target.value)}
                                                    className="w-full px-4 py-3 border-2 border-gray-200 rounded-xl focus:outline-none focus:border-[#002147] transition-all duration-300 font-lato"
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
                                                    className="block text-sm font-semibold mb-2 font-montserrat"
                                                    style={{ color: '#002147' }}
                                                >
                                                    <i className="fas fa-briefcase mr-2" style={{ color: '#00a651' }}></i>
                                                    Professional Title
                                                </label>
                                                <input
                                                    type="text"
                                                    value={form.data.headline}
                                                    onChange={(e) => form.setData('headline', e.target.value)}
                                                    className="w-full px-4 py-3 border-2 border-gray-200 rounded-xl focus:outline-none focus:border-[#00a651] transition-all duration-300 font-lato"
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
                                                className="block text-sm font-semibold mb-2 font-montserrat"
                                                style={{ color: '#002147' }}
                                            >
                                                <i className="fas fa-building mr-2" style={{ color: '#ed1c24' }}></i>Organization
                                            </label>
                                            <input
                                                type="text"
                                                value={form.data.organization}
                                                onChange={(e) => form.setData('organization', e.target.value)}
                                                className="w-full px-4 py-3 border-2 border-gray-200 rounded-xl focus:outline-none focus:border-[#ed1c24] transition-all duration-300 font-lato"
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
                                            <div
                                                className="w-12 h-12 rounded-2xl flex items-center justify-center mr-4"
                                                style={{ background: 'linear-gradient(135deg, #ed1c24 0%, #dc2626 100%)' }}
                                            >
                                                <i className="fas fa-lock text-white text-lg"></i>
                                            </div>
                                            <div>
                                                <h3 className="text-xl font-bold font-montserrat" style={{ color: '#002147' }}>
                                                    Account Security
                                                </h3>
                                                <p className="text-sm text-gray-500 font-lato">Set up your account credentials</p>
                                            </div>
                                        </div>

                                        <div className="grid grid-cols-1 md:grid-cols-2 gap-6">
                                            <div>
                                                <label
                                                    className="block text-sm font-semibold mb-2 font-montserrat"
                                                    style={{ color: '#002147' }}
                                                >
                                                    <i className="fas fa-key mr-2" style={{ color: '#ed1c24' }}></i>Password
                                                </label>
                                                <input
                                                    type="password"
                                                    value={form.data.password}
                                                    onChange={(e) => form.setData('password', e.target.value)}
                                                    className="w-full px-4 py-3 border-2 border-gray-200 rounded-xl focus:outline-none focus:border-[#ed1c24] transition-all duration-300 font-lato"
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
                                                    className="block text-sm font-semibold mb-2 font-montserrat"
                                                    style={{ color: '#002147' }}
                                                >
                                                    <i className="fas fa-check-double mr-2" style={{ color: '#00a651' }}></i>
                                                    Confirm Password
                                                </label>
                                                <input
                                                    type="password"
                                                    value={form.data.password_confirmation}
                                                    onChange={(e) => form.setData('password_confirmation', e.target.value)}
                                                    className="w-full px-4 py-3 border-2 border-gray-200 rounded-xl focus:outline-none focus:border-[#00a651] transition-all duration-300 font-lato"
                                                    placeholder="Confirm your password"
                                                    required
                                                />
                                            </div>
                                        </div>
                                    </div>

                                    {/* Professional Profile */}
                                    <div className="space-y-4" data-aos="fade-up" data-aos-delay="300">
                                        <div className="flex items-center mb-6">
                                            <div
                                                className="w-12 h-12 rounded-2xl flex items-center justify-center mr-4"
                                                style={{ background: 'linear-gradient(135deg, #002147 0%, #003875 100%)' }}
                                            >
                                                <i className="fas fa-briefcase text-white text-lg"></i>
                                            </div>
                                            <div>
                                                <h3 className="text-xl font-bold font-montserrat" style={{ color: '#002147' }}>
                                                    Professional Profile
                                                </h3>
                                                <p className="text-sm text-gray-500 font-lato">Share your expertise and background</p>
                                            </div>
                                        </div>

                                        <div>
                                            <label htmlFor="bio" className="block text-sm font-medium text-gray-700 mb-2 font-lato">
                                                Professional Bio
                                            </label>
                                            <textarea
                                                id="bio"
                                                value={form.data.bio}
                                                onChange={(e) => form.setData('bio', e.target.value)}
                                                rows={4}
                                                className="block w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-primary focus:border-primary transition-all duration-200 font-lato placeholder-gray-400"
                                                placeholder="Tell us about your expertise, experience, and speaking background..."
                                                required
                                            ></textarea>
                                            {form.errors.bio && (
                                                <small className="text-xs text-secondary">{form.errors.bio}</small>
                                            )}
                                        </div>

                                        {/* Profile Photo */}
                                        <div className="space-y-3">
                                            <label htmlFor="photo" className="block text-sm font-medium text-gray-700 font-lato">
                                                Profile Photo
                                            </label>
                                            <input
                                                id="photo"
                                                type="file"
                                                accept="image/*"
                                                onChange={(e) => form.setData('photo', e.target.files?.[0] || null)}
                                                className="block w-full px-4 border border-gray-300 rounded-xl file:bg-primary focus:ring-2 focus:ring-primary focus:border-primary transition-all duration-200 font-lato placeholder-gray-400"
                                            />
                                            <p className="text-xs text-gray-500 mt-1">JPG, PNG up to 2MB</p>
                                            {form.errors.photo && (
                                                <small className="text-xs text-secondary">{form.errors.photo}</small>
                                            )}
                                        </div>

                                        {/* Social Links */}
                                        <div className="grid grid-cols-1 md:grid-cols-2 gap-4">
                                            <div>
                                                <label className="block text-sm font-medium text-gray-700 mb-2 font-lato">
                                                    LinkedIn Profile
                                                </label>
                                                <input
                                                    type="url"
                                                    value={form.data.linkedin}
                                                    onChange={(e) => form.setData('linkedin', e.target.value)}
                                                    placeholder="https://linkedin.com/in/yourprofile"
                                                    className="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-primary focus:border-primary transition-all duration-200 font-lato"
                                                />
                                                {form.errors.linkedin && (
                                                    <small className="text-xs text-secondary">{form.errors.linkedin}</small>
                                                )}
                                            </div>
                                            <div>
                                                <label className="block text-sm font-medium text-gray-700 mb-2 font-lato">
                                                    Website
                                                </label>
                                                <input
                                                    type="url"
                                                    value={form.data.website}
                                                    onChange={(e) => form.setData('website', e.target.value)}
                                                    placeholder="https://yourwebsite.com"
                                                    className="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-primary focus:border-primary transition-all duration-200 font-lato"
                                                />
                                                {form.errors.website && (
                                                    <small className="text-xs text-secondary">{form.errors.website}</small>
                                                )}
                                            </div>
                                        </div>
                                    </div>

                                    {/* Terms and Submit */}
                                    <div className="space-y-4 pt-6 border-t border-gray-200" data-aos="fade-up" data-aos-delay="400">
                                        <div
                                            className="flex items-start gap-3 p-4 rounded-xl"
                                            style={{ background: 'rgba(0, 33, 71, 0.05)' }}
                                        >
                                            <input
                                                id="agree_terms"
                                                type="checkbox"
                                                checked={form.data.agree_terms}
                                                onChange={(e) => form.setData('agree_terms', e.target.checked)}
                                                className="h-4 w-4 border-gray-300 rounded focus:ring-2 transition-all duration-300 mt-1"
                                                style={{ color: '#002147' }}
                                                required
                                            />
                                            <label htmlFor="agree_terms" className="text-sm text-gray-700 font-lato leading-relaxed">
                                                I agree to the{' '}
                                                <a
                                                    href={route('terms-of-service')}
                                                    className="font-medium hover:underline transition-colors duration-300"
                                                    style={{ color: '#002147' }}
                                                >
                                                    Terms of Service
                                                </a>{' '}
                                                and{' '}
                                                <a
                                                    href={route('privacy-policy')}
                                                    className="font-medium hover:underline transition-colors duration-300"
                                                    style={{ color: '#002147' }}
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
                                            className="w-full text-white font-semibold py-4 px-6 rounded-xl transition-all duration-300 transform hover:scale-[1.02] shadow-lg hover:shadow-xl font-montserrat flex items-center justify-center gap-3 disabled:opacity-50 disabled:cursor-not-allowed"
                                            style={{
                                                background: 'linear-gradient(135deg, #002147 0%, #ed1c24 100%)',
                                                border: 'none',
                                            }}
                                        >
                                            {form.processing && <i className="fas fa-spinner animate-spin text-lg"></i>}
                                            <i className="fas fa-paper-plane"></i>
                                            {form.processing ? 'Submitting...' : 'Register as Speaker'}
                                        </button>
                                    </div>
                                </form>
                            </div>

                            {/* Right Side - Benefits */}
                            <div
                                className="p-10 md:p-12"
                                style={{ background: 'linear-gradient(135deg, #002147 0%, #003875 100%)' }}
                            >
                                <div className="space-y-8">
                                    <div className="text-center">
                                        <div
                                            className="w-16 h-16 rounded-2xl flex items-center justify-center mx-auto mb-6"
                                            style={{ background: 'rgba(0, 166, 81, 0.2)', backdropFilter: 'blur(10px)' }}
                                        >
                                            <i className="fas fa-star text-2xl" style={{ color: '#00a651' }}></i>
                                        </div>
                                        <h3 className="text-2xl font-bold text-white mb-4 font-montserrat">Speaker Benefits</h3>
                                        <p className="text-gray-200 font-lato">Join our exclusive community of thought leaders</p>
                                    </div>

                                    <div className="space-y-6">
                                        <div className="group p-4 rounded-2xl transition-all duration-300 hover:bg-white/10">
                                            <div className="flex items-start gap-4">
                                                <div
                                                    className="w-12 h-12 rounded-xl flex items-center justify-center flex-shrink-0 transition-all duration-300 group-hover:scale-110"
                                                    style={{ background: 'rgba(0, 166, 81, 0.2)' }}
                                                >
                                                    <i className="fas fa-users text-lg" style={{ color: '#00a651' }}></i>
                                                </div>
                                                <div>
                                                    <h4 className="font-bold text-white mb-2 font-montserrat">Global Audience Reach</h4>
                                                    <p className="text-sm text-gray-200 font-lato leading-relaxed">
                                                        Share your expertise with thousands of passionate learners and industry leaders
                                                        worldwide.
                                                    </p>
                                                </div>
                                            </div>
                                        </div>

                                        <div className="group p-4 rounded-2xl transition-all duration-300 hover:bg-white/10">
                                            <div className="flex items-start gap-4">
                                                <div
                                                    className="w-12 h-12 rounded-xl flex items-center justify-center flex-shrink-0 transition-all duration-300 group-hover:scale-110"
                                                    style={{ background: 'rgba(237, 28, 36, 0.2)' }}
                                                >
                                                    <i className="fas fa-award text-lg" style={{ color: '#ed1c24' }}></i>
                                                </div>
                                                <div>
                                                    <h4 className="font-bold text-white mb-2 font-montserrat">Build Your Brand</h4>
                                                    <p className="text-sm text-gray-200 font-lato leading-relaxed">
                                                        Enhance your professional reputation and establish yourself as a thought leader in
                                                        your industry.
                                                    </p>
                                                </div>
                                            </div>
                                        </div>

                                        <div className="group p-4 rounded-2xl transition-all duration-300 hover:bg-white/10">
                                            <div className="flex items-start gap-4">
                                                <div
                                                    className="w-12 h-12 rounded-xl flex items-center justify-center flex-shrink-0 transition-all duration-300 group-hover:scale-110"
                                                    style={{ background: 'rgba(0, 166, 81, 0.2)' }}
                                                >
                                                    <i className="fas fa-handshake text-lg" style={{ color: '#00a651' }}></i>
                                                </div>
                                                <div>
                                                    <h4 className="font-bold text-white mb-2 font-montserrat">Premium Networking</h4>
                                                    <p className="text-sm text-gray-200 font-lato leading-relaxed">
                                                        Connect with other experts, C-level executives, and thought leaders in exclusive
                                                        events.
                                                    </p>
                                                </div>
                                            </div>
                                        </div>

                                        <div className="group p-4 rounded-2xl transition-all duration-300 hover:bg-white/10">
                                            <div className="flex items-start gap-4">
                                                <div
                                                    className="w-12 h-12 rounded-xl flex items-center justify-center flex-shrink-0 transition-all duration-300 group-hover:scale-110"
                                                    style={{ background: 'rgba(237, 28, 36, 0.2)' }}
                                                >
                                                    <i className="fas fa-microphone-alt text-lg" style={{ color: '#ed1c24' }}></i>
                                                </div>
                                                <div>
                                                    <h4 className="font-bold text-white mb-2 font-montserrat">Exclusive Opportunities</h4>
                                                    <p className="text-sm text-gray-200 font-lato leading-relaxed">
                                                        Get priority access to high-profile speaking engagements and premium corporate events.
                                                    </p>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    {/* Stats */}
                                    <div className="grid grid-cols-2 gap-4 pt-6 border-t border-white/20">
                                        <div className="text-center">
                                            <div className="text-2xl font-bold text-white mb-1 font-montserrat" style={{ color: '#00a651' }}>
                                                200+
                                            </div>
                                            <div className="text-xs text-gray-300 font-lato">Expert Speakers</div>
                                        </div>
                                        <div className="text-center">
                                            <div className="text-2xl font-bold text-white mb-1 font-montserrat" style={{ color: '#ed1c24' }}>
                                                50K+
                                            </div>
                                            <div className="text-xs text-gray-300 font-lato">Audience Reached</div>
                                        </div>
                                    </div>

                                    {/* Already have account */}
                                    <div className="pt-6 border-t border-white/20">
                                        <p className="text-sm text-gray-200 font-lato text-center mb-4">Already have an account?</p>
                                        <a
                                            href={route('login')}
                                            className="w-full inline-flex items-center justify-center px-6 py-3 rounded-xl font-semibold transition-all duration-300 hover:shadow-lg font-montserrat border-2 border-white/30 text-white hover:bg-white/10"
                                        >
                                            <i className="fas fa-sign-in-alt mr-2"></i>
                                            Sign In Here
                                        </a>
                                    </div>

                                    {/* Support Info */}
                                    <div className="bg-white/10 backdrop-blur-sm rounded-2xl p-6 border border-white/20">
                                        <div className="flex items-center gap-4">
                                            <div
                                                className="w-12 h-12 rounded-xl flex items-center justify-center"
                                                style={{ background: 'rgba(0, 166, 81, 0.2)' }}
                                            >
                                                <i className="fas fa-headset text-lg" style={{ color: '#00a651' }}></i>
                                            </div>
                                            <div>
                                                <h4 className="font-bold text-white text-sm font-montserrat">Need Help?</h4>
                                                <p className="text-xs text-gray-200 font-lato">Contact our speaker success team</p>
                                                <a
                                                    href="mailto:speakers@beaconleadership.org"
                                                    className="text-xs font-medium hover:underline font-montserrat"
                                                    style={{ color: '#00a651' }}
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
