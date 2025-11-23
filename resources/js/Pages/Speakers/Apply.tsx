import { Head, useForm } from '@inertiajs/react';
import GuestLayout from '@/Layouts/GuestLayout';
import { FormEvent } from 'react';

interface User {
    id: number;
    name: string;
    email: string;
    phone?: string;
}

interface Speaker {
    id: number;
    title?: string;
    organization?: string;
    bio?: string;
    photo?: string;
    linkedin?: string;
    website?: string;
    email?: string;
    phone?: string;
}

interface Event {
    id: number;
    slug: string;
    title: string;
    start_date: string;
    max_attendees?: number;
    speaker_deadline?: string | null;
}

interface Application {
    id?: number;
    topic_title?: string;
    topic_description?: string;
    session_format?: {
        value: string;
    };
    notes?: string;
    speaker?: Speaker;
}

interface ApplyProps {
    event: Event;
    application?: Application | null;
    auth?: {
        user: User;
    };
}

const sessionFormats = [
    { value: 'presentation', label: 'Presentation' },
    { value: 'panel', label: 'Panel Discussion' },
    { value: 'workshop', label: 'Interactive Workshop' },
    { value: 'lightning_talk', label: 'Lightning Talk' },
];

export default function Apply({ event, application, auth }: ApplyProps) {
    const form = useForm({
        title: application?.speaker?.title || '',
        organization: application?.speaker?.organization || '',
        bio: application?.speaker?.bio || '',
        photo: null as File | null,
        linkedin: application?.speaker?.linkedin || '',
        website: application?.speaker?.website || '',
        topic_title: application?.topic_title || '',
        topic_description: application?.topic_description || '',
        session_format: application?.session_format?.value || '',
        notes: application?.notes || '',
    });

    const handleSubmit = (e: FormEvent) => {
        e.preventDefault();
        form.post(route('event.speakers.store', event.id), {
            forceFormData: true,
        });
    };

    const formatDate = (dateString: string) => {
        const date = new Date(dateString);
        return date.toLocaleDateString('en-US', { year: 'numeric', month: 'short', day: 'numeric' });
    };

    return (
        <GuestLayout>
            <Head title={`Apply to Speak - ${event.title}`} />

            {/* Hero Section */}
            <section
                className="relative py-20 overflow-hidden"
                style={{ background: 'linear-gradient(135deg, #002147 0%, #003875 50%, #002147 100%)' }}
            >
                {/* Animated Background Elements */}
                <div className="absolute inset-0 opacity-10">
                    <div
                        className="absolute top-10 left-10 w-32 h-32 rounded-full animate-pulse"
                        style={{ background: 'linear-gradient(135deg, #00a651, #ed1c24)' }}
                    ></div>
                    <div
                        className="absolute bottom-10 right-10 w-24 h-24 rounded-full animate-bounce"
                        style={{ background: 'linear-gradient(135deg, #ed1c24, #00a651)' }}
                    ></div>
                    <div
                        className="absolute top-1/2 left-1/4 w-16 h-16 rounded-full animate-ping"
                        style={{ background: 'rgba(0, 166, 81, 0.3)' }}
                    ></div>
                </div>

                <div className="relative z-10 container mx-auto px-4">
                    <div className="text-center max-w-5xl mx-auto">
                        <div className="mb-8" data-aos="fade-down">
                            <div
                                className="inline-flex items-center px-4 py-2 rounded-full text-sm font-medium mb-6"
                                style={{
                                    background: 'rgba(0, 166, 81, 0.2)',
                                    color: '#00a651',
                                    border: '1px solid rgba(0, 166, 81, 0.3)',
                                }}
                            >
                                <i className="fas fa-microphone mr-2"></i>
                                Speaker Application
                            </div>
                        </div>

                        <h1
                            className="text-4xl lg:text-6xl font-bold text-white mb-6 font-montserrat leading-tight"
                            data-aos="fade-up"
                        >
                            Apply to Speak at <br />
                            <span className="bg-gradient-to-r from-yellow-300 to-orange-400 bg-clip-text text-transparent">
                                {event.title}
                            </span>
                        </h1>

                        <p className="text-gray-200 text-xl max-w-3xl mx-auto leading-relaxed mb-8 font-lato" data-aos="fade-up" data-aos-delay="200">
                            Share your expertise with our audience and make an impact. Complete the application below to join our distinguished speaker lineup.
                        </p>

                        {/* Event Info Cards */}
                        <div className="grid grid-cols-1 md:grid-cols-3 gap-4 mt-12 max-w-4xl mx-auto" data-aos="fade-up" data-aos-delay="400">
                            <div
                                className="p-4 rounded-2xl backdrop-filter backdrop-blur-lg"
                                style={{ background: 'rgba(255, 255, 255, 0.1)', border: '1px solid rgba(255, 255, 255, 0.2)' }}
                            >
                                <div
                                    className="flex items-center justify-center w-12 h-12 rounded-xl mx-auto mb-3"
                                    style={{ background: 'rgba(0, 166, 81, 0.2)' }}
                                >
                                    <i className="fas fa-calendar text-xl" style={{ color: '#00a651' }}></i>
                                </div>
                                <h3 className="font-semibold text-white mb-1 font-montserrat">Event Date</h3>
                                <p className="text-gray-300 text-sm font-lato">
                                    {event.start_date ? formatDate(event.start_date) : 'TBD'}
                                </p>
                            </div>

                            <div
                                className="p-4 rounded-2xl backdrop-filter backdrop-blur-lg"
                                style={{ background: 'rgba(255, 255, 255, 0.1)', border: '1px solid rgba(255, 255, 255, 0.2)' }}
                            >
                                <div
                                    className="flex items-center justify-center w-12 h-12 rounded-xl mx-auto mb-3"
                                    style={{ background: 'rgba(237, 28, 36, 0.2)' }}
                                >
                                    <i className="fas fa-users text-xl" style={{ color: '#ed1c24' }}></i>
                                </div>
                                <h3 className="font-semibold text-white mb-1 font-montserrat">Expected Audience</h3>
                                <p className="text-gray-300 text-sm font-lato">{event.max_attendees || '500+'} Professionals</p>
                            </div>

                            <div
                                className="p-4 rounded-2xl backdrop-filter backdrop-blur-lg"
                                style={{ background: 'rgba(255, 255, 255, 0.1)', border: '1px solid rgba(255, 255, 255, 0.2)' }}
                            >
                                <div
                                    className="flex items-center justify-center w-12 h-12 rounded-xl mx-auto mb-3"
                                    style={{ background: 'rgba(0, 33, 71, 0.3)' }}
                                >
                                    <i className="fas fa-clock text-xl" style={{ color: '#ffffff' }}></i>
                                </div>
                                <h3 className="font-semibold text-white mb-1 font-montserrat">Application Deadline</h3>
                                <p className="text-gray-300 text-sm font-lato">
                                    {event.speaker_deadline ? formatDate(event.speaker_deadline) : 'Rolling'}
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            </section>

            {/* Application Form Section */}
            <section className="py-16" style={{ background: 'linear-gradient(135deg, #f8fafc 0%, #e2e8f0 100%)' }}>
                <div className="container mx-auto px-4">
                    <div className="bg-white rounded-3xl shadow-2xl border border-gray-100 overflow-hidden" data-aos="fade-up" data-aos-delay="100">
                        <div className="bg-gradient-to-r from-primary to-blue-700 p-8 text-white text-center">
                            <div
                                className="w-16 h-16 rounded-2xl flex items-center justify-center mx-auto mb-4"
                                style={{ background: 'rgba(255, 255, 255, 0.2)', backdropFilter: 'blur(10px)' }}
                            >
                                <i className="fas fa-file-alt text-2xl"></i>
                            </div>
                            <h2 className="text-2xl font-bold font-montserrat mb-2">Speaker Application Form</h2>
                            <p className="text-blue-100 font-lato">Please complete all sections to submit your application</p>
                        </div>

                        <form onSubmit={handleSubmit} className="p-10">
                            {/* Personal Information Section */}
                            <div className="mb-12" data-aos="fade-up" data-aos-delay="200">
                                <div className="flex items-center mb-8">
                                    <div
                                        className="w-14 h-14 rounded-2xl flex items-center justify-center mr-4"
                                        style={{ background: 'linear-gradient(135deg, #00a651 0%, #15803d 100%)' }}
                                    >
                                        <i className="fas fa-user text-white text-xl"></i>
                                    </div>
                                    <div>
                                        <h3 className="text-2xl font-bold font-montserrat" style={{ color: '#002147' }}>
                                            Personal Information
                                        </h3>
                                        <p className="text-gray-500 font-lato">Your basic details and contact information</p>
                                    </div>
                                </div>

                                <div className="grid grid-cols-1 md:grid-cols-2 gap-6">
                                    {/* Name */}
                                    <div>
                                        <label htmlFor="name" className="block text-gray-700 font-medium mb-2">
                                            Full Name
                                        </label>
                                        <input
                                            type="text"
                                            id="name"
                                            value={auth?.user?.name || ''}
                                            className="w-full px-4 py-3 border border-gray-300 rounded-lg bg-gray-50 text-gray-500 cursor-not-allowed"
                                            disabled
                                        />
                                    </div>

                                    {/* Title */}
                                    <div>
                                        <label htmlFor="title" className="block text-gray-700 font-medium mb-2">
                                            Professional Title
                                        </label>
                                        <input
                                            type="text"
                                            id="title"
                                            value={form.data.title}
                                            onChange={(e) => form.setData('title', e.target.value)}
                                            placeholder="E.g., CEO, Senior Developer, etc."
                                            className="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-primary transition-colors"
                                        />
                                        {form.errors.title && <p className="mt-2 text-sm text-red-600 font-medium">{form.errors.title}</p>}
                                    </div>

                                    {/* Organization */}
                                    <div>
                                        <label htmlFor="organization" className="block text-gray-700 font-medium mb-2">
                                            Organization
                                        </label>
                                        <input
                                            type="text"
                                            id="organization"
                                            value={form.data.organization}
                                            onChange={(e) => form.setData('organization', e.target.value)}
                                            placeholder="Your company or affiliation"
                                            className="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-primary transition-colors"
                                        />
                                        {form.errors.organization && (
                                            <p className="mt-2 text-sm text-red-600 font-medium">{form.errors.organization}</p>
                                        )}
                                    </div>

                                    {/* Email */}
                                    <div>
                                        <label className="block text-gray-700 font-medium mb-2">Email</label>
                                        <div className="px-4 py-3 bg-gray-100 rounded-lg text-gray-600 border border-gray-200">
                                            {application?.speaker?.email || auth?.user?.email}
                                        </div>
                                    </div>

                                    {/* Phone */}
                                    <div>
                                        <label className="block text-gray-700 font-medium mb-2">Phone Number</label>
                                        <div className="px-4 py-3 bg-gray-100 rounded-lg text-gray-600 border border-gray-200">
                                            {application?.speaker?.phone || auth?.user?.phone}
                                        </div>
                                    </div>

                                    {/* Photo */}
                                    <div className="md:col-span-2">
                                        <label htmlFor="photo" className="flex items-center text-gray-700 font-medium mb-3">
                                            <i className="fas fa-image w-5 h-5 mr-2 text-primary"></i>
                                            Profile Photo
                                        </label>
                                        <div className="mt-4 flex flex-col sm:flex-row items-center gap-6 p-6 bg-gray-50 rounded-xl border border-gray-200">
                                            <div className="flex-shrink-0">
                                                <div className="w-20 h-20 rounded-full bg-gray-200 flex items-center justify-center overflow-hidden border-2 border-gray-300">
                                                    {application?.speaker?.photo ? (
                                                        <img
                                                            src={`/storage/${application.speaker.photo}`}
                                                            alt="Profile"
                                                            className="w-full h-full object-cover rounded-full"
                                                        />
                                                    ) : (
                                                        <i className="fas fa-user w-8 h-8 text-gray-400"></i>
                                                    )}
                                                </div>
                                            </div>
                                            <div className="flex-grow">
                                                <input
                                                    type="file"
                                                    id="photo"
                                                    accept="image/*"
                                                    onChange={(e) => form.setData('photo', e.target.files?.[0] || null)}
                                                    className="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-primary transition-colors"
                                                />
                                                <p className="mt-2 text-sm text-gray-500">JPEG or PNG, max 2MB. Square images work best.</p>
                                                {form.errors.photo && <p className="mt-1 text-sm text-red-600 font-medium">{form.errors.photo}</p>}
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            {/* Professional Information Section */}
                            <div className="mb-12" data-aos="fade-up" data-aos-delay="300">
                                <div className="flex items-center mb-8">
                                    <div
                                        className="w-14 h-14 rounded-2xl flex items-center justify-center mr-4"
                                        style={{ background: 'linear-gradient(135deg, #ed1c24 0%, #dc2626 100%)' }}
                                    >
                                        <i className="fas fa-briefcase text-white text-xl"></i>
                                    </div>
                                    <div>
                                        <h3 className="text-2xl font-bold font-montserrat" style={{ color: '#002147' }}>
                                            Professional Information
                                        </h3>
                                        <p className="text-gray-500 font-lato">Share your background and expertise</p>
                                    </div>
                                </div>

                                {/* Bio */}
                                <div className="mb-8">
                                    <label htmlFor="bio" className="flex items-center text-gray-700 font-medium mb-3">
                                        <i className="fas fa-file-text w-5 h-5 mr-2 text-primary"></i>
                                        Professional Bio
                                    </label>
                                    <p className="text-gray-500 text-sm mb-4">
                                        This will be displayed in the event program if you're selected.
                                    </p>
                                    <textarea
                                        id="bio"
                                        value={form.data.bio}
                                        onChange={(e) => form.setData('bio', e.target.value)}
                                        rows={5}
                                        className="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-primary transition-colors resize-none"
                                        placeholder="Tell us about your professional background, achievements, and areas of expertise..."
                                    ></textarea>
                                    {form.errors.bio && <p className="mt-2 text-sm text-red-600 font-medium">{form.errors.bio}</p>}
                                </div>

                                <div className="grid grid-cols-1 md:grid-cols-2 gap-6">
                                    {/* LinkedIn */}
                                    <div>
                                        <label htmlFor="linkedin" className="flex items-center text-gray-700 font-medium mb-2">
                                            <i className="fab fa-linkedin w-5 h-5 mr-2 text-primary"></i>
                                            LinkedIn Profile
                                        </label>
                                        <input
                                            type="url"
                                            id="linkedin"
                                            value={form.data.linkedin}
                                            onChange={(e) => form.setData('linkedin', e.target.value)}
                                            placeholder="https://linkedin.com/in/yourprofile"
                                            className="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-primary transition-colors"
                                        />
                                        {form.errors.linkedin && <p className="mt-2 text-sm text-red-600 font-medium">{form.errors.linkedin}</p>}
                                    </div>

                                    {/* Website */}
                                    <div>
                                        <label htmlFor="website" className="flex items-center text-gray-700 font-medium mb-2">
                                            <i className="fas fa-globe w-5 h-5 mr-2 text-primary"></i>
                                            Website/Blog
                                        </label>
                                        <input
                                            type="url"
                                            id="website"
                                            value={form.data.website}
                                            onChange={(e) => form.setData('website', e.target.value)}
                                            placeholder="https://yourwebsite.com"
                                            className="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-primary transition-colors"
                                        />
                                        {form.errors.website && <p className="mt-2 text-sm text-red-600 font-medium">{form.errors.website}</p>}
                                    </div>
                                </div>
                            </div>

                            {/* Session Proposal Section */}
                            <div className="mb-12" data-aos="fade-up" data-aos-delay="400">
                                <div className="flex items-center mb-8">
                                    <div
                                        className="w-14 h-14 rounded-2xl flex items-center justify-center mr-4"
                                        style={{ background: 'linear-gradient(135deg, #002147 0%, #003875 100%)' }}
                                    >
                                        <i className="fas fa-microphone text-white text-xl"></i>
                                    </div>
                                    <div>
                                        <h3 className="text-2xl font-bold font-montserrat" style={{ color: '#002147' }}>
                                            Session Proposal
                                        </h3>
                                        <p className="text-gray-500 font-lato">Tell us about your proposed session</p>
                                    </div>
                                </div>

                                {/* Topic Title */}
                                <div className="mb-8">
                                    <label htmlFor="topic_title" className="block text-gray-700 font-medium mb-2">
                                        Proposed Topic Title
                                    </label>
                                    <input
                                        type="text"
                                        id="topic_title"
                                        value={form.data.topic_title}
                                        onChange={(e) => form.setData('topic_title', e.target.value)}
                                        placeholder="An engaging title that describes your session"
                                        className="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-primary transition-colors"
                                        required
                                    />
                                    {form.errors.topic_title && <p className="mt-2 text-sm text-red-600 font-medium">{form.errors.topic_title}</p>}
                                </div>

                                {/* Topic Description */}
                                <div className="mb-8">
                                    <label htmlFor="topic_description" className="flex items-center text-gray-700 font-medium mb-3">
                                        <i className="fas fa-align-left w-5 h-5 mr-2 text-primary"></i>
                                        Session Description
                                    </label>
                                    <p className="text-gray-500 text-sm mb-4">
                                        What will attendees learn from your session? Be specific and compelling.
                                    </p>
                                    <textarea
                                        id="topic_description"
                                        value={form.data.topic_description}
                                        onChange={(e) => form.setData('topic_description', e.target.value)}
                                        rows={8}
                                        className="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-primary transition-colors resize-none"
                                        placeholder="Describe your proposed session in detail, including key takeaways, target audience, and why this topic matters now..."
                                        required
                                    ></textarea>
                                    {form.errors.topic_description && (
                                        <p className="mt-2 text-sm text-red-600 font-medium">{form.errors.topic_description}</p>
                                    )}
                                </div>

                                {/* Session Format */}
                                <div className="mb-8">
                                    <label className="flex items-center text-gray-700 font-medium mb-3">
                                        <i className="fas fa-grid w-5 h-5 mr-2 text-primary"></i>
                                        Preferred Session Format
                                    </label>
                                    <div className="grid grid-cols-1 md:grid-cols-3 gap-4 mt-4">
                                        {sessionFormats.map((format) => (
                                            <label
                                                key={format.value}
                                                className="flex items-center p-4 rounded-xl border border-gray-300 bg-white shadow-sm cursor-pointer hover:border-primary hover:shadow-md transition-all duration-200"
                                            >
                                                <input
                                                    type="radio"
                                                    name="session_format"
                                                    value={format.value}
                                                    checked={form.data.session_format === format.value}
                                                    onChange={(e) => form.setData('session_format', e.target.value)}
                                                    className="form-radio h-4 w-4 text-primary border-gray-300 focus:ring-primary mr-3"
                                                />
                                                <span className="text-gray-700 text-sm font-medium">{format.label}</span>
                                            </label>
                                        ))}
                                    </div>
                                    {form.errors.session_format && (
                                        <p className="mt-2 text-sm text-red-600 font-medium">{form.errors.session_format}</p>
                                    )}
                                </div>

                                {/* Additional Notes */}
                                <div>
                                    <label htmlFor="notes" className="flex items-center text-gray-700 font-medium mb-3">
                                        <i className="fas fa-sticky-note w-5 h-5 mr-2 text-primary"></i>
                                        Additional Notes
                                    </label>
                                    <p className="text-gray-500 text-sm mb-4">
                                        Special requirements, equipment needs, accessibility requests, or other information.
                                    </p>
                                    <textarea
                                        id="notes"
                                        value={form.data.notes}
                                        onChange={(e) => form.setData('notes', e.target.value)}
                                        rows={3}
                                        className="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-primary transition-colors resize-none"
                                        placeholder="Any special requirements or additional information"
                                    ></textarea>
                                    {form.errors.notes && <p className="mt-2 text-sm text-red-600 font-medium">{form.errors.notes}</p>}
                                </div>
                            </div>

                            {/* Form Actions */}
                            <div className="pt-8 border-t border-gray-200" data-aos="fade-up" data-aos-delay="500">
                                <div className="bg-gray-50 rounded-2xl p-6 mb-6">
                                    <div className="flex items-start gap-4">
                                        <div
                                            className="w-10 h-10 rounded-xl flex items-center justify-center flex-shrink-0"
                                            style={{ background: 'rgba(0, 166, 81, 0.1)' }}
                                        >
                                            <i className="fas fa-info-circle text-lg" style={{ color: '#00a651' }}></i>
                                        </div>
                                        <div>
                                            <h4 className="font-semibold font-montserrat mb-2" style={{ color: '#002147' }}>
                                                Next Steps
                                            </h4>
                                            <p className="text-sm text-gray-600 font-lato leading-relaxed">
                                                After submitting your application, our team will review your proposal within 3-5 business days.
                                                Selected speakers will be notified via email with further details about the event.
                                            </p>
                                        </div>
                                    </div>
                                </div>

                                <div className="flex flex-col sm:flex-row justify-end gap-4">
                                    <a
                                        href={route('events.show', event.slug)}
                                        className="px-8 py-3 border-2 border-gray-300 text-gray-700 rounded-xl hover:bg-gray-50 hover:border-gray-400 transition-all duration-300 text-center font-medium font-montserrat flex items-center justify-center"
                                    >
                                        <i className="fas fa-arrow-left mr-2"></i>
                                        Cancel
                                    </a>
                                    <button
                                        type="submit"
                                        disabled={form.processing}
                                        className="px-8 py-4 text-white rounded-xl font-semibold font-montserrat transition-all duration-300 transform hover:scale-[1.02] shadow-lg hover:shadow-xl flex items-center justify-center disabled:opacity-50 disabled:cursor-not-allowed"
                                        style={{ background: 'linear-gradient(135deg, #002147 0%, #ed1c24 100%)' }}
                                    >
                                        {form.processing && <i className="fas fa-spinner animate-spin mr-2"></i>}
                                        <i className="fas fa-paper-plane mr-2"></i>
                                        {form.processing ? 'Submitting...' : 'Submit Application'}
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </section>
        </GuestLayout>
    );
}
