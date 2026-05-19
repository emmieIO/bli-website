import { Head, router, useForm } from '@inertiajs/react';
import GuestLayout from '@/Layouts/GuestLayout';
import { useState, FormEvent } from 'react';

interface User {
    id: number;
    name: string;
    email: string;
    phone?: string;
    headline?: string;
    linkedin?: string;
    website?: string;
}

interface InstructorProfile {
    id: number;
    bio?: string;
    teaching_history?: string;
    experience_years?: number;
    area_of_expertise?: string;
    resume_path?: string;
    intro_video_url?: string;
    status: string;
}

interface ApplicationFormProps {
    user: User;
    profile: InstructorProfile;
}

export default function ApplicationForm({ user, profile }: ApplicationFormProps) {
    // Personal Info Form
    const personalForm = useForm({
        name: user.name || '',
        phone: user.phone || '',
        headline: user.headline || '',
        bio: profile.bio || '',
    });

    // Experience Form
    const experienceForm = useForm({
        experience: profile.teaching_history || '',
        experience_years: profile.experience_years?.toString() || '',
        expertise: profile.area_of_expertise || '',
        linkedin: user.linkedin || '',
        website: user.website || '',
    });

    // Documents Form
    const documentsForm = useForm({
        resume: null as File | null,
        video_url: profile.intro_video_url || '',
    });

    // Final Submit Form
    const finalForm = useForm({
        terms: false,
    });

    // Expertise tags state
    const [expertiseTags, setExpertiseTags] = useState<string[]>(
        profile.area_of_expertise ? profile.area_of_expertise.split(',').map((t) => t.trim()).filter(Boolean) : []
    );
    const [expertiseInput, setExpertiseInput] = useState('');

    const handlePersonalSubmit = (e: FormEvent) => {
        e.preventDefault();
        personalForm.post(route('instructors.save-personal-info', user.id));
    };

    const handleExperienceSubmit = (e: FormEvent) => {
        e.preventDefault();
        experienceForm.transform((data) => ({
            ...data,
            expertise: expertiseTags.join(','),
        }));
        experienceForm.post(route('instructors.save-experience', user.id));
    };

    const handleDocumentsSubmit = (e: FormEvent) => {
        e.preventDefault();
        documentsForm.post(route('instructors.save-documents', user.id));
    };

    const handleFinalSubmit = (e: FormEvent) => {
        e.preventDefault();
        finalForm.post(route('instructors.submit-application', user.id));
    };

    const addExpertiseTag = () => {
        const newTag = expertiseInput.trim().replace(/,$/, '');
        if (newTag && !expertiseTags.includes(newTag)) {
            setExpertiseTags([...expertiseTags, newTag]);
            setExpertiseInput('');
        }
    };

    const removeExpertiseTag = (index: number) => {
        setExpertiseTags(expertiseTags.filter((_, i) => i !== index));
    };

    const handleExpertiseKeyDown = (e: React.KeyboardEvent<HTMLInputElement>) => {
        if (e.key === ',' || e.key === 'Enter') {
            e.preventDefault();
            addExpertiseTag();
        }
    };

    return (
        <GuestLayout>
            <Head title="Instructor Application" />

            <section className="public-section min-h-screen bg-gray-50 font-lato">
                <div className="section-shell">
                    {/* Header */}
                    <div className="text-center mb-12">
                        <div className="mb-6 inline-flex items-center rounded-full bg-primary/10 px-4 py-2">
                            <i className="fas fa-file-alt mr-2 text-primary"></i>
                            <span className="text-sm font-semibold font-montserrat text-primary">Application Form</span>
                        </div>
                        <h1 className="mb-4 text-3xl font-bold font-montserrat text-primary md:text-4xl">
                            Instructor Application
                        </h1>
                        <p className="text-gray-600 text-lg max-w-2xl mx-auto font-lato">
                            Complete all sections below to submit your instructor profile for review
                        </p>

                        {/* Progress Indicator */}
                        <div className="mt-8 flex flex-wrap items-center justify-center gap-3">
                            <div className="flex items-center space-x-2 rounded-full bg-white px-3 py-2 shadow-sm border border-gray-200">
                                <div className="h-3 w-3 rounded-full bg-accent"></div>
                                <span className="text-sm font-medium text-accent">Personal Info</span>
                            </div>
                            <div className="flex items-center space-x-2 rounded-full bg-white px-3 py-2 shadow-sm border border-gray-200">
                                <div className="h-3 w-3 rounded-full bg-secondary"></div>
                                <span className="text-sm font-medium text-secondary">Experience</span>
                            </div>
                            <div className="flex items-center space-x-2 rounded-full bg-white px-3 py-2 shadow-sm border border-gray-200">
                                <div className="h-3 w-3 rounded-full bg-primary"></div>
                                <span className="text-sm font-medium text-primary">Documents</span>
                            </div>
                            <div className="flex items-center space-x-2 rounded-full bg-white px-3 py-2 shadow-sm border border-gray-200">
                                <div className="h-3 w-3 rounded-full bg-accent"></div>
                                <span className="text-sm font-medium text-accent">Submit</span>
                            </div>
                        </div>
                    </div>

                    {/* Main Form Container */}
                    <div className="overflow-hidden rounded-lg border border-gray-200 bg-white shadow-sm">
                        <div className="p-6 md:p-8 lg:p-10">
                            <div className="grid lg:grid-cols-2 gap-12">
                                {/* Left Column */}
                                <div className="space-y-8">
                                    {/* Personal Information Card */}
                                    <div className="overflow-hidden rounded-lg border border-gray-200 bg-white shadow-sm">
                                        <div className="border-b border-gray-200 bg-primary px-6 py-5">
                                            <h3 className="text-xl font-bold text-white flex items-center font-montserrat">
                                                <div className="mr-4 flex h-10 w-10 items-center justify-center rounded-lg bg-white/10">
                                                    <i className="fas fa-user text-lg"></i>
                                                </div>
                                                Personal Information
                                                <span className="ml-auto rounded-full bg-white/10 px-3 py-1 text-sm">
                                                    Step 1
                                                </span>
                                            </h3>
                                        </div>
                                        <form onSubmit={handlePersonalSubmit} className="space-y-6 p-6">
                                            {/* Full Name Field */}
                                            <div>
                                                <label htmlFor="name" className="block text-sm font-semibold mb-2 font-montserrat text-primary">
                                                    <i className="fas fa-user mr-2 text-accent"></i>Full Name
                                                </label>
                                                <div className="relative">
                                                    <input
                                                        type="text"
                                                        id="name"
                                                        readOnly
                                                        className="public-input bg-gray-50"
                                                        value={personalForm.data.name}
                                                    />
                                                    <div className="absolute right-3 top-1/2 transform -translate-y-1/2">
                                                        <i className="fas fa-lock text-gray-400"></i>
                                                    </div>
                                                </div>
                                            </div>

                                            {/* Phone Number Field */}
                                            <div>
                                                <label htmlFor="phone" className="block text-sm font-semibold mb-2 font-montserrat text-primary">
                                                    <i className="fas fa-phone mr-2 text-secondary"></i>Phone Number *
                                                </label>
                                                <input
                                                    type="tel"
                                                    id="phone"
                                                    value={personalForm.data.phone}
                                                    onChange={(e) => personalForm.setData('phone', e.target.value)}
                                                    className="public-input"
                                                    placeholder="e.g. +234 803 123 4567"
                                                />
                                                {personalForm.errors.phone && (
                                                    <p className="mt-2 text-sm flex items-center text-secondary">
                                                        <i className="fas fa-exclamation-circle mr-1"></i>
                                                        {personalForm.errors.phone}
                                                    </p>
                                                )}
                                            </div>

                                            {/* Professional Headline Field */}
                                            <div>
                                                <label htmlFor="headline" className="block text-sm font-semibold mb-2 font-montserrat text-primary">
                                                    <i className="fas fa-briefcase mr-2 text-accent"></i>Professional Headline *
                                                </label>
                                                <input
                                                    type="text"
                                                    id="headline"
                                                    value={personalForm.data.headline}
                                                    onChange={(e) => personalForm.setData('headline', e.target.value)}
                                                    className="public-input"
                                                    placeholder="e.g. Senior Web Developer & Tech Educator"
                                                />
                                                {personalForm.errors.headline && (
                                                    <p className="mt-2 text-sm flex items-center text-secondary">
                                                        <i className="fas fa-exclamation-circle mr-1"></i>
                                                        {personalForm.errors.headline}
                                                    </p>
                                                )}
                                            </div>

                                            {/* Bio Field */}
                                            <div>
                                                <label htmlFor="bio" className="block text-sm font-semibold mb-2 font-montserrat text-primary">
                                                    <i className="fas fa-edit mr-2 text-primary"></i>Professional Bio *
                                                </label>
                                                <textarea
                                                    id="bio"
                                                    rows={4}
                                                    value={personalForm.data.bio}
                                                    onChange={(e) => personalForm.setData('bio', e.target.value)}
                                                    className="public-input resize-none"
                                                    placeholder="Share your professional background, achievements, and what makes you a great instructor..."
                                                />
                                                {personalForm.errors.bio && (
                                                    <p className="mt-2 text-sm flex items-center text-secondary">
                                                        <i className="fas fa-exclamation-circle mr-1"></i>
                                                        {personalForm.errors.bio}
                                                    </p>
                                                )}
                                            </div>

                                            {/* Save Button */}
                                            <div className="pt-4">
                                                <button
                                                    type="submit"
                                                    disabled={personalForm.processing}
                                                    className="enterprise-button enterprise-button-primary w-full justify-center py-3 text-sm disabled:opacity-50"
                                                >
                                                    <i className="fas fa-save mr-2"></i>
                                                    {personalForm.processing ? 'Saving...' : 'Save Personal Information'}
                                                </button>
                                            </div>
                                        </form>
                                    </div>

                                    {/* Documents Card */}
                                    <div className="overflow-hidden rounded-lg border border-gray-200 bg-white shadow-sm">
                                        <div className="border-b border-gray-200 bg-primary px-6 py-5">
                                            <h3 className="text-xl font-bold text-white flex items-center font-montserrat">
                                                <div className="mr-4 flex h-10 w-10 items-center justify-center rounded-lg bg-white/10">
                                                    <i className="fas fa-file-upload text-lg"></i>
                                                </div>
                                                Documents & Media
                                                <span className="ml-auto rounded-full bg-white/10 px-3 py-1 text-sm">
                                                    Step 3
                                                </span>
                                            </h3>
                                        </div>
                                        <form onSubmit={handleDocumentsSubmit} className="space-y-6 p-6">
                                            {/* Resume/CV Upload */}
                                            <div>
                                                <label htmlFor="resume" className="block text-sm font-semibold mb-2 font-montserrat text-primary">
                                                    <i className="fas fa-file-alt mr-2 text-secondary"></i>Resume/CV *
                                                </label>
                                                <div className="rounded-lg border-2 border-dashed border-gray-300 p-6 transition-all duration-300">
                                                    <input
                                                        type="file"
                                                        id="resume"
                                                        accept=".pdf,.doc,.docx"
                                                        className="hidden"
                                                        onChange={(e) => documentsForm.setData('resume', e.target.files?.[0] || null)}
                                                    />
                                                    <div className="text-center">
                                                        <div className="mx-auto mb-4 flex h-16 w-16 items-center justify-center rounded-lg bg-secondary/10">
                                                            <i className="fas fa-cloud-upload-alt text-2xl text-secondary"></i>
                                                        </div>
                                                        <label
                                                            htmlFor="resume"
                                                            className="enterprise-button enterprise-button-outline cursor-pointer px-6 py-3"
                                                        >
                                                            <i className="fas fa-upload mr-2"></i>
                                                            Choose File
                                                        </label>
                                                        <p className="text-sm text-gray-500 mt-2 font-lato">PDF, DOC, or DOCX files only</p>
                                                    </div>
                                                    {documentsForm.data.resume && (
                                                        <div className="mt-4">
                                                            <div className="flex items-center justify-between rounded-lg bg-gray-50 p-3">
                                                                <div className="flex items-center">
                                                                    <i className="fas fa-file text-gray-600 mr-2"></i>
                                                                    <span className="text-sm font-medium">{documentsForm.data.resume.name}</span>
                                                                </div>
                                                                <i className="fas fa-check-circle text-accent"></i>
                                                            </div>
                                                        </div>
                                                    )}
                                                </div>
                                                {profile.resume_path && (
                                                    <div className="mt-3 rounded-lg bg-accent/10 p-3">
                                                        <p className="text-sm font-medium text-accent">
                                                            <i className="fas fa-file-check mr-2"></i>
                                                            Current file: {profile.resume_path.split('/').pop()}
                                                        </p>
                                                    </div>
                                                )}
                                                {documentsForm.errors.resume && (
                                                    <p className="mt-2 text-sm flex items-center text-secondary">
                                                        <i className="fas fa-exclamation-circle mr-1"></i>
                                                        {documentsForm.errors.resume}
                                                    </p>
                                                )}
                                            </div>

                                            {/* Video URL */}
                                            <div>
                                                <label htmlFor="video_url" className="block text-sm font-semibold mb-2 font-montserrat text-primary">
                                                    <i className="fas fa-video mr-2 text-accent"></i>Introduction Video URL *
                                                </label>
                                                <input
                                                    type="url"
                                                    id="video_url"
                                                    value={documentsForm.data.video_url}
                                                    onChange={(e) => documentsForm.setData('video_url', e.target.value)}
                                                    className="public-input"
                                                    placeholder="https://youtube.com/watch?v=your-video"
                                                />
                                                <div className="mt-3 rounded-lg bg-accent/10 p-4">
                                                    <p className="text-sm font-lato text-primary">
                                                        <i className="fas fa-lightbulb mr-2 text-accent"></i>
                                                        <strong>Pro Tip:</strong> Create a 1-2 minute introduction video on YouTube, Vimeo, or Loom.
                                                        This helps us understand your teaching style and communication skills.
                                                    </p>
                                                </div>
                                                {documentsForm.errors.video_url && (
                                                    <p className="mt-2 text-sm flex items-center text-secondary">
                                                        <i className="fas fa-exclamation-circle mr-1"></i>
                                                        {documentsForm.errors.video_url}
                                                    </p>
                                                )}
                                            </div>

                                            {/* Save Button */}
                                            <div className="pt-4">
                                                <button
                                                    type="submit"
                                                    disabled={documentsForm.processing}
                                                    className="enterprise-button enterprise-button-primary w-full justify-center py-3 text-sm disabled:opacity-50"
                                                >
                                                    <i className="fas fa-save mr-2"></i>
                                                    {documentsForm.processing ? 'Saving...' : 'Save Documents & Media'}
                                                </button>
                                            </div>
                                        </form>
                                    </div>
                                </div>

                                {/* Right Column */}
                                <div className="space-y-8">
                                    {/* Experience Card */}
                                    <div className="overflow-hidden rounded-lg border border-gray-200 bg-white shadow-sm">
                                        <div className="border-b border-gray-200 bg-secondary px-6 py-5">
                                            <h3 className="text-xl font-bold text-white flex items-center font-montserrat">
                                                <div className="mr-4 flex h-10 w-10 items-center justify-center rounded-lg bg-white/10">
                                                    <i className="fas fa-graduation-cap text-lg"></i>
                                                </div>
                                                Experience & Expertise
                                                <span className="ml-auto rounded-full bg-white/10 px-3 py-1 text-sm">
                                                    Step 2
                                                </span>
                                            </h3>
                                        </div>
                                        <form onSubmit={handleExperienceSubmit} className="space-y-6 p-6">
                                            {/* Teaching Experience */}
                                            <div>
                                                <label htmlFor="experience" className="block text-sm font-semibold mb-2 font-montserrat text-primary">
                                                    <i className="fas fa-chalkboard-teacher mr-2 text-secondary"></i>Teaching Experience *
                                                </label>
                                                <textarea
                                                    id="experience"
                                                    rows={4}
                                                    value={experienceForm.data.experience}
                                                    onChange={(e) => experienceForm.setData('experience', e.target.value)}
                                                    className="public-input resize-none"
                                                    placeholder="Describe your teaching or mentorship background. Include courses taught, institutions, duration, or informal mentoring roles..."
                                                />
                                                {experienceForm.errors.experience && (
                                                    <p className="mt-2 text-sm flex items-center text-secondary">
                                                        <i className="fas fa-exclamation-circle mr-1"></i>
                                                        {experienceForm.errors.experience}
                                                    </p>
                                                )}
                                            </div>

                                            {/* Years of Experience */}
                                            <div>
                                                <label htmlFor="experience_years" className="block text-sm font-semibold mb-2 font-montserrat text-primary">
                                                    <i className="fas fa-calendar-alt mr-2 text-accent"></i>Years of Experience *
                                                </label>
                                                <select
                                                    id="experience_years"
                                                    value={experienceForm.data.experience_years}
                                                    onChange={(e) => experienceForm.setData('experience_years', e.target.value)}
                                                    className="public-input"
                                                    required
                                                >
                                                    <option value="">Select your years of experience</option>
                                                    {Array.from({ length: 31 }, (_, i) => (
                                                        <option key={i} value={i}>
                                                            {i} {i === 1 ? 'year' : 'years'}
                                                        </option>
                                                    ))}
                                                </select>
                                                {experienceForm.errors.experience_years && (
                                                    <p className="mt-2 text-sm flex items-center text-secondary">
                                                        <i className="fas fa-exclamation-circle mr-1"></i>
                                                        {experienceForm.errors.experience_years}
                                                    </p>
                                                )}
                                            </div>

                                            {/* Areas of Expertise */}
                                            <div>
                                                <label className="block text-sm font-semibold mb-2 font-montserrat text-primary">
                                                    <i className="fas fa-tags mr-2 text-primary"></i>Areas of Expertise *
                                                </label>

                                                {/* Tags Display */}
                                                <div className="flex flex-wrap gap-2 mb-3">
                                                    {expertiseTags.map((tag, index) => (
                                                        <div
                                                            key={index}
                                                            className="group flex items-center rounded-lg border border-accent/30 bg-accent/5 px-4 py-2 text-sm font-medium text-primary"
                                                        >
                                                            <span className="font-montserrat">{tag}</span>
                                                            <button
                                                                type="button"
                                                                onClick={() => removeExpertiseTag(index)}
                                                                className="ml-2 flex h-5 w-5 items-center justify-center rounded-full bg-secondary text-xs font-bold text-white"
                                                            >
                                                                ×
                                                            </button>
                                                        </div>
                                                    ))}
                                                </div>

                                                {/* Input Field */}
                                                <div className="relative">
                                                    <input
                                                        type="text"
                                                        value={expertiseInput}
                                                        onChange={(e) => setExpertiseInput(e.target.value)}
                                                        onKeyDown={handleExpertiseKeyDown}
                                                        className="public-input"
                                                        placeholder="Type an expertise area and press Enter or comma..."
                                                    />
                                                    <div className="absolute right-4 top-1/2 transform -translate-y-1/2 pointer-events-none">
                                                        <span className="text-gray-400 text-sm font-lato">Press ⏎ or ,</span>
                                                    </div>
                                                </div>

                                                <div className="mt-3 rounded-lg bg-primary/10 p-4">
                                                    <p className="text-sm font-lato text-primary">
                                                        <i className="fas fa-lightbulb mr-2 text-accent"></i>
                                                        <strong>Examples:</strong> Web Development, Data Science, Digital Marketing, UI/UX Design,
                                                        Project Management
                                                    </p>
                                                </div>

                                                {experienceForm.errors.expertise && (
                                                    <p className="mt-2 text-sm flex items-center text-secondary">
                                                        <i className="fas fa-exclamation-circle mr-1"></i>
                                                        {experienceForm.errors.expertise}
                                                    </p>
                                                )}
                                            </div>

                                            {/* Social Links */}
                                            <div className="grid md:grid-cols-2 gap-6">
                                                <div>
                                                    <label htmlFor="linkedin" className="block text-sm font-semibold mb-2 font-montserrat text-primary">
                                                        <i className="fab fa-linkedin mr-2 text-blue-600"></i>LinkedIn Profile
                                                    </label>
                                                    <input
                                                        type="url"
                                                        id="linkedin"
                                                        value={experienceForm.data.linkedin}
                                                        onChange={(e) => experienceForm.setData('linkedin', e.target.value)}
                                                        className="public-input"
                                                        placeholder="https://linkedin.com/in/yourprofile"
                                                    />
                                                </div>

                                                <div>
                                                    <label htmlFor="website" className="block text-sm font-semibold mb-2 font-montserrat text-primary">
                                                        <i className="fas fa-globe mr-2 text-accent"></i>Personal Website
                                                    </label>
                                                    <input
                                                        type="url"
                                                        id="website"
                                                        value={experienceForm.data.website}
                                                        onChange={(e) => experienceForm.setData('website', e.target.value)}
                                                        className="public-input"
                                                        placeholder="https://yourwebsite.com"
                                                    />
                                                    {experienceForm.errors.website && (
                                                        <p className="mt-2 text-sm flex items-center text-secondary">
                                                            <i className="fas fa-exclamation-circle mr-1"></i>
                                                            {experienceForm.errors.website}
                                                        </p>
                                                    )}
                                                </div>
                                            </div>

                                            {/* Save Button */}
                                            <div className="pt-4">
                                                <button
                                                    type="submit"
                                                    disabled={experienceForm.processing}
                                                    className="enterprise-button enterprise-button-primary w-full justify-center py-3 text-sm disabled:opacity-50"
                                                >
                                                    <i className="fas fa-save mr-2"></i>
                                                    {experienceForm.processing ? 'Saving...' : 'Save Experience & Expertise'}
                                                </button>
                                            </div>
                                        </form>
                                    </div>

                                    {/* Enhanced Submission Card */}
                                    <div className="overflow-hidden rounded-lg border border-gray-200 bg-white shadow-sm">
                                        <div className="border-b border-gray-200 bg-secondary px-6 py-5">
                                            <h3 className="text-xl font-bold text-white flex items-center font-montserrat">
                                                <div className="mr-4 flex h-10 w-10 items-center justify-center rounded-lg bg-white/10">
                                                    <i className="fas fa-paper-plane text-lg"></i>
                                                </div>
                                                Submit Application
                                                <span className="ml-auto rounded-full bg-white/10 px-3 py-1 text-sm">
                                                    Final Step
                                                </span>
                                            </h3>
                                        </div>
                                        <form onSubmit={handleFinalSubmit} className="space-y-6 p-6">
                                            {/* Terms & Conditions */}
                                            <div className="rounded-lg border border-gray-200 bg-gray-50 p-6">
                                                <div className="flex items-start space-x-4">
                                                    <div className="flex items-center h-6">
                                                        <input
                                                            id="terms"
                                                            type="checkbox"
                                                            checked={finalForm.data.terms}
                                                            onChange={(e) => finalForm.setData('terms', e.target.checked)}
                                                            className="h-5 w-5 rounded border-2 transition-all duration-300 accent-accent border-gray-300"
                                                        />
                                                    </div>
                                                    <div className="flex-1">
                                                        <label htmlFor="terms" className="font-semibold text-gray-700 font-montserrat cursor-pointer">
                                                            I agree to the terms and conditions
                                                        </label>
                                                        <p className="text-sm text-gray-600 mt-2 font-lato">
                                                            By checking this box, you agree to our{' '}
                                                            <a href="#" className="font-semibold hover:underline text-primary">
                                                                Instructor Terms
                                                            </a>
                                                            ,{' '}
                                                            <a href="#" className="font-semibold hover:underline text-primary">
                                                                Privacy Policy
                                                            </a>
                                                            , and{' '}
                                                            <a href="#" className="font-semibold hover:underline text-primary">
                                                                Code of Conduct
                                                            </a>
                                                            .
                                                        </p>
                                                        {finalForm.errors.terms && (
                                                            <p className="mt-2 text-sm flex items-center text-secondary">
                                                                <i className="fas fa-exclamation-circle mr-1"></i>
                                                                {finalForm.errors.terms}
                                                            </p>
                                                        )}
                                                    </div>
                                                </div>
                                            </div>

                                            {/* Application Summary */}
                                            <div className="rounded-lg bg-accent/10 p-6">
                                                <h4 className="font-bold mb-3 font-montserrat flex items-center text-primary">
                                                    <i className="fas fa-clipboard-list mr-2 text-accent"></i>
                                                    Application Review Process
                                                </h4>
                                                <ul className="space-y-2 text-sm font-lato text-primary">
                                                    <li className="flex items-center">
                                                        <i className="fas fa-check-circle mr-2 text-xs text-accent"></i>
                                                        Immediate confirmation email sent
                                                    </li>
                                                    <li className="flex items-center">
                                                        <i className="fas fa-clock mr-2 text-xs text-secondary"></i>
                                                        Review within 5-7 business days
                                                    </li>
                                                    <li className="flex items-center">
                                                        <i className="fas fa-envelope mr-2 text-xs text-primary"></i>
                                                        Updates sent via email
                                                    </li>
                                                </ul>
                                            </div>

                                            {/* Submit Button */}
                                            <div className="pt-4">
                                                <button
                                                    type="submit"
                                                    disabled={!finalForm.data.terms || finalForm.processing}
                                                    className="enterprise-button enterprise-button-primary w-full justify-center py-4 text-lg disabled:cursor-not-allowed disabled:opacity-50"
                                                >
                                                    <i className="fas fa-rocket mr-2"></i>
                                                    {finalForm.processing ? 'Submitting...' : 'Submit My Application'}
                                                </button>
                                                <p className="text-center text-sm text-gray-500 mt-3 font-lato">
                                                    <i className="fas fa-shield-alt mr-1 text-accent"></i>
                                                    Your information is secure and will only be used for application review
                                                </p>
                                            </div>
                                        </form>
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
