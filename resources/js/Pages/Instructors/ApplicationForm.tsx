import { Head, useForm } from '@inertiajs/react';
import GuestLayout from '@/Layouts/GuestLayout';
import { useState, FormEvent } from 'react';
import { GraduationCap, FileText, Upload, CheckCircle2 } from 'lucide-react';

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

const steps = [
    { label: 'Personal', icon: 'fa-user' },
    { label: 'Experience', icon: 'fa-graduation-cap' },
    { label: 'Documents', icon: 'fa-file-upload' },
    { label: 'Submit', icon: 'fa-paper-plane' },
];

export default function ApplicationForm({ user, profile }: ApplicationFormProps) {
    const personalForm = useForm({
        name: user.name || '',
        phone: user.phone || '',
        headline: user.headline || '',
        bio: profile.bio || '',
    });

    const experienceForm = useForm({
        experience: profile.teaching_history || '',
        experience_years: profile.experience_years?.toString() || '',
        expertise: profile.area_of_expertise || '',
        linkedin: user.linkedin || '',
        website: user.website || '',
    });

    const documentsForm = useForm({
        resume: null as File | null,
        video_url: profile.intro_video_url || '',
    });

    const finalForm = useForm({
        terms: false,
    });

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

            {/* Hero */}
            <section className="relative overflow-hidden border-b border-slate-200 bg-gradient-to-br from-primary via-primary-800 to-primary-900 py-16 text-white md:py-20">
                <div className="absolute -right-20 -top-20 h-80 w-80 rounded-full bg-accent/10 blur-3xl" />
                <div className="absolute -bottom-20 -left-20 h-60 w-60 rounded-full bg-accent/8 blur-3xl" />
                <div className="section-shell relative z-10 text-center">
                    <div className="mx-auto mb-5 flex h-14 w-14 items-center justify-center rounded-full bg-accent/20">
                        <GraduationCap className="h-7 w-7 text-accent-300" />
                    </div>
                    <h1 className="text-3xl font-bold tracking-tight md:text-4xl">
                        Instructor Application
                    </h1>
                    <p className="mx-auto mt-4 max-w-xl text-base leading-relaxed text-slate-300">
                        Complete all sections below to submit your instructor profile for review.
                    </p>

                    <div className="mt-10 flex flex-wrap items-center justify-center gap-3">
                        {steps.map((step, i) => (
                            <div
                                key={step.label}
                                className="flex items-center gap-2 rounded-full border border-white/10 bg-white/5 px-4 py-2 text-xs font-semibold backdrop-blur-sm"
                            >
                                <span className="flex h-5 w-5 items-center justify-center rounded-full bg-accent text-[10px] font-bold text-white">
                                    {i + 1}
                                </span>
                                <span>{step.label}</span>
                            </div>
                        ))}
                    </div>
                </div>
            </section>

            {/* Main Form */}
            <section className="bg-gradient-to-b from-slate-50 to-white py-12 md:py-16">
                <div className="section-shell">
                    <div className="grid gap-8 lg:grid-cols-2">

                        {/* Left Column */}
                        <div className="space-y-6">

                            {/* Personal Information */}
                            <div className="overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-sm">
                                <div className="border-b border-slate-100 bg-gradient-to-r from-primary to-primary-800 px-6 py-4">
                                    <div className="flex items-center gap-3">
                                        <div className="flex h-9 w-9 items-center justify-center rounded-lg bg-white/10">
                                            <i className="fas fa-user text-sm" />
                                        </div>
                                        <div>
                                            <h3 className="text-lg font-bold">Personal Information</h3>
                                            <p className="text-xs text-slate-300">Step 1 of 4</p>
                                        </div>
                                    </div>
                                </div>
                                <form onSubmit={handlePersonalSubmit} className="space-y-5 p-6">
                                    <div>
                                        <label className="block text-sm font-semibold mb-1.5">
                                            <i className="fas fa-user mr-2 text-accent" />Full Name
                                        </label>
                                        <div className="relative">
                                            <input type="text" readOnly value={personalForm.data.name}
                                                className="w-full rounded-xl border border-slate-200 bg-slate-50 px-4 py-3 text-slate-500 cursor-not-allowed" />
                                        </div>
                                    </div>

                                    <div>
                                        <label className="block text-sm font-semibold mb-1.5">
                                            <i className="fas fa-phone mr-2 text-accent" />Phone Number
                                        </label>
                                        <input type="tel" value={personalForm.data.phone}
                                            onChange={(e) => personalForm.setData('phone', e.target.value)}
                                            className="w-full rounded-xl border border-slate-200 bg-white px-4 py-3 text-sm placeholder-slate-400 transition focus:border-primary focus:ring-2 focus:ring-primary/10 focus:outline-none"
                                            placeholder="+234 803 123 4567" />
                                        {personalForm.errors.phone && (
                                            <p className="mt-1.5 text-xs text-accent-600 flex items-center gap-1">
                                                <i className="fas fa-exclamation-circle" />{personalForm.errors.phone}
                                            </p>
                                        )}
                                    </div>

                                    <div>
                                        <label className="block text-sm font-semibold mb-1.5">
                                            <i className="fas fa-briefcase mr-2 text-accent" />Professional Headline
                                        </label>
                                        <input type="text" value={personalForm.data.headline}
                                            onChange={(e) => personalForm.setData('headline', e.target.value)}
                                            className="w-full rounded-xl border border-slate-200 bg-white px-4 py-3 text-sm placeholder-slate-400 transition focus:border-primary focus:ring-2 focus:ring-primary/10 focus:outline-none"
                                            placeholder="e.g. Senior Web Developer & Tech Educator" />
                                        {personalForm.errors.headline && (
                                            <p className="mt-1.5 text-xs text-accent-600 flex items-center gap-1">
                                                <i className="fas fa-exclamation-circle" />{personalForm.errors.headline}
                                            </p>
                                        )}
                                    </div>

                                    <div>
                                        <label className="block text-sm font-semibold mb-1.5">
                                            <i className="fas fa-edit mr-2 text-accent" />Professional Bio
                                        </label>
                                        <textarea rows={4} value={personalForm.data.bio}
                                            onChange={(e) => personalForm.setData('bio', e.target.value)}
                                            className="w-full rounded-xl border border-slate-200 bg-white px-4 py-3 text-sm placeholder-slate-400 transition focus:border-primary focus:ring-2 focus:ring-primary/10 focus:outline-none resize-none"
                                            placeholder="Share your professional background and achievements..." />
                                        {personalForm.errors.bio && (
                                            <p className="mt-1.5 text-xs text-accent-600 flex items-center gap-1">
                                                <i className="fas fa-exclamation-circle" />{personalForm.errors.bio}
                                            </p>
                                        )}
                                    </div>

                                    <button type="submit" disabled={personalForm.processing}
                                        className="inline-flex w-full items-center justify-center gap-2 rounded-xl bg-primary px-6 py-3 text-sm font-semibold text-white transition hover:bg-primary-700 disabled:opacity-50">
                                        <i className="fas fa-save" />
                                        {personalForm.processing ? 'Saving...' : 'Save Personal Information'}
                                    </button>
                                </form>
                            </div>

                            {/* Documents */}
                            <div className="overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-sm">
                                <div className="border-b border-slate-100 bg-gradient-to-r from-primary to-primary-800 px-6 py-4">
                                    <div className="flex items-center gap-3">
                                        <div className="flex h-9 w-9 items-center justify-center rounded-lg bg-white/10">
                                            <i className="fas fa-file-upload text-sm" />
                                        </div>
                                        <div>
                                            <h3 className="text-lg font-bold">Documents & Media</h3>
                                            <p className="text-xs text-slate-300">Step 3 of 4</p>
                                        </div>
                                    </div>
                                </div>
                                <form onSubmit={handleDocumentsSubmit} className="space-y-5 p-6">
                                    <div>
                                        <label className="block text-sm font-semibold mb-1.5">
                                            <i className="fas fa-file-alt mr-2 text-accent" />Resume/CV
                                        </label>
                                        <label htmlFor="resume-file"
                                            className="flex cursor-pointer flex-col items-center gap-3 rounded-xl border-2 border-dashed border-slate-200 bg-slate-50/50 px-6 py-8 transition hover:border-primary hover:bg-primary/5">
                                            <div className="flex h-14 w-14 items-center justify-center rounded-full bg-accent/10">
                                                <Upload className="h-6 w-6 text-accent" />
                                            </div>
                                            <div className="text-center">
                                                <span className="text-sm font-semibold text-primary">Click to upload</span>
                                                <p className="mt-1 text-xs text-slate-400">PDF, DOC, or DOCX</p>
                                            </div>
                                            <input type="file" id="resume-file" accept=".pdf,.doc,.docx"
                                                className="hidden"
                                                onChange={(e) => documentsForm.setData('resume', e.target.files?.[0] || null)} />
                                        </label>
                                        {documentsForm.data.resume && (
                                            <div className="mt-3 flex items-center gap-2 rounded-lg border border-accent-100 bg-accent-50 px-3 py-2 text-sm">
                                                <CheckCircle2 className="h-4 w-4 text-accent" />
                                                <span className="text-accent-700 font-medium">{documentsForm.data.resume.name}</span>
                                            </div>
                                        )}
                                        {profile.resume_path && (
                                            <p className="mt-2 text-xs text-slate-400">
                                                <i className="fas fa-file-check mr-1 text-accent" />
                                                Current: {profile.resume_path.split('/').pop()}
                                            </p>
                                        )}
                                        {documentsForm.errors.resume && (
                                            <p className="mt-1.5 text-xs text-accent-600 flex items-center gap-1">
                                                <i className="fas fa-exclamation-circle" />{documentsForm.errors.resume}
                                            </p>
                                        )}
                                    </div>

                                    <div>
                                        <label className="block text-sm font-semibold mb-1.5">
                                            <i className="fas fa-video mr-2 text-accent" />Introduction Video URL
                                        </label>
                                        <input type="url" value={documentsForm.data.video_url}
                                            onChange={(e) => documentsForm.setData('video_url', e.target.value)}
                                            className="w-full rounded-xl border border-slate-200 bg-white px-4 py-3 text-sm placeholder-slate-400 transition focus:border-primary focus:ring-2 focus:ring-primary/10 focus:outline-none"
                                            placeholder="https://youtube.com/watch?v=..." />
                                        <div className="mt-3 rounded-xl border border-accent-100 bg-accent-50/50 px-4 py-3">
                                            <p className="text-xs text-accent-800">
                                                <i className="fas fa-lightbulb mr-1.5 text-accent" />
                                                Create a 1-2 minute video on YouTube or Loom showing your teaching style.
                                            </p>
                                        </div>
                                        {documentsForm.errors.video_url && (
                                            <p className="mt-1.5 text-xs text-accent-600 flex items-center gap-1">
                                                <i className="fas fa-exclamation-circle" />{documentsForm.errors.video_url}
                                            </p>
                                        )}
                                    </div>

                                    <button type="submit" disabled={documentsForm.processing}
                                        className="inline-flex w-full items-center justify-center gap-2 rounded-xl bg-primary px-6 py-3 text-sm font-semibold text-white transition hover:bg-primary-700 disabled:opacity-50">
                                        <i className="fas fa-save" />
                                        {documentsForm.processing ? 'Saving...' : 'Save Documents & Media'}
                                    </button>
                                </form>
                            </div>
                        </div>

                        {/* Right Column */}
                        <div className="space-y-6">

                            {/* Experience */}
                            <div className="overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-sm">
                                <div className="border-b border-slate-100 bg-gradient-to-r from-primary to-primary-800 px-6 py-4">
                                    <div className="flex items-center gap-3">
                                        <div className="flex h-9 w-9 items-center justify-center rounded-lg bg-white/10">
                                            <i className="fas fa-graduation-cap text-sm" />
                                        </div>
                                        <div>
                                            <h3 className="text-lg font-bold">Experience & Expertise</h3>
                                            <p className="text-xs text-slate-300">Step 2 of 4</p>
                                        </div>
                                    </div>
                                </div>
                                <form onSubmit={handleExperienceSubmit} className="space-y-5 p-6">
                                    <div>
                                        <label className="block text-sm font-semibold mb-1.5">
                                            <i className="fas fa-chalkboard-teacher mr-2 text-accent" />Teaching Experience
                                        </label>
                                        <textarea rows={4} value={experienceForm.data.experience}
                                            onChange={(e) => experienceForm.setData('experience', e.target.value)}
                                            className="w-full rounded-xl border border-slate-200 bg-white px-4 py-3 text-sm placeholder-slate-400 transition focus:border-primary focus:ring-2 focus:ring-primary/10 focus:outline-none resize-none"
                                            placeholder="Describe your teaching or mentorship background..." />
                                        {experienceForm.errors.experience && (
                                            <p className="mt-1.5 text-xs text-accent-600 flex items-center gap-1">
                                                <i className="fas fa-exclamation-circle" />{experienceForm.errors.experience}
                                            </p>
                                        )}
                                    </div>

                                    <div>
                                        <label className="block text-sm font-semibold mb-1.5">
                                            <i className="fas fa-calendar-alt mr-2 text-accent" />Years of Experience
                                        </label>
                                        <select value={experienceForm.data.experience_years}
                                            onChange={(e) => experienceForm.setData('experience_years', e.target.value)}
                                            className="w-full rounded-xl border border-slate-200 bg-white px-4 py-3 text-sm transition focus:border-primary focus:ring-2 focus:ring-primary/10 focus:outline-none">
                                            <option value="">Select years</option>
                                            {Array.from({ length: 31 }, (_, i) => (
                                                <option key={i} value={i}>{i} {i === 1 ? 'year' : 'years'}</option>
                                            ))}
                                        </select>
                                        {experienceForm.errors.experience_years && (
                                            <p className="mt-1.5 text-xs text-accent-600 flex items-center gap-1">
                                                <i className="fas fa-exclamation-circle" />{experienceForm.errors.experience_years}
                                            </p>
                                        )}
                                    </div>

                                    <div>
                                        <label className="block text-sm font-semibold mb-1.5">
                                            <i className="fas fa-tags mr-2 text-accent" />Areas of Expertise
                                        </label>
                                        <div className="flex flex-wrap gap-1.5 mb-2">
                                            {expertiseTags.map((tag, i) => (
                                                <div key={i} className="flex items-center gap-1 rounded-lg border border-accent-200 bg-accent-50 px-3 py-1 text-xs font-medium text-accent-800">
                                                    {tag}
                                                    <button type="button" onClick={() => removeExpertiseTag(i)}
                                                        className="ml-1 flex h-4 w-4 items-center justify-center rounded-full bg-accent text-[10px] text-white hover:bg-accent-700">x</button>
                                                </div>
                                            ))}
                                        </div>
                                        <input type="text" value={expertiseInput}
                                            onChange={(e) => setExpertiseInput(e.target.value)}
                                            onKeyDown={handleExpertiseKeyDown}
                                            className="w-full rounded-xl border border-slate-200 bg-white px-4 py-3 text-sm placeholder-slate-400 transition focus:border-primary focus:ring-2 focus:ring-primary/10 focus:outline-none"
                                            placeholder="Type and press Enter or comma to add..." />
                                        <div className="mt-3 rounded-xl border border-slate-100 bg-slate-50 px-4 py-3">
                                            <p className="text-xs text-slate-500">
                                                <i className="fas fa-lightbulb mr-1.5 text-accent" />
                                                Examples: Web Development, Data Science, Digital Marketing, UI/UX Design
                                            </p>
                                        </div>
                                        {experienceForm.errors.expertise && (
                                            <p className="mt-1.5 text-xs text-accent-600 flex items-center gap-1">
                                                <i className="fas fa-exclamation-circle" />{experienceForm.errors.expertise}
                                            </p>
                                        )}
                                    </div>

                                    <div className="grid gap-4 md:grid-cols-2">
                                        <div>
                                            <label className="block text-sm font-semibold mb-1.5">
                                                <i className="fab fa-linkedin mr-2 text-blue-600" />LinkedIn
                                            </label>
                                            <input type="url" value={experienceForm.data.linkedin}
                                                onChange={(e) => experienceForm.setData('linkedin', e.target.value)}
                                                className="w-full rounded-xl border border-slate-200 bg-white px-4 py-3 text-sm placeholder-slate-400 transition focus:border-primary focus:ring-2 focus:ring-primary/10 focus:outline-none"
                                                placeholder="https://linkedin.com/in/you" />
                                        </div>
                                        <div>
                                            <label className="block text-sm font-semibold mb-1.5">
                                                <i className="fas fa-globe mr-2 text-accent" />Website
                                            </label>
                                            <input type="url" value={experienceForm.data.website}
                                                onChange={(e) => experienceForm.setData('website', e.target.value)}
                                                className="w-full rounded-xl border border-slate-200 bg-white px-4 py-3 text-sm placeholder-slate-400 transition focus:border-primary focus:ring-2 focus:ring-primary/10 focus:outline-none"
                                                placeholder="https://yourwebsite.com" />
                                            {experienceForm.errors.website && (
                                                <p className="mt-1.5 text-xs text-accent-600 flex items-center gap-1">
                                                    <i className="fas fa-exclamation-circle" />{experienceForm.errors.website}
                                                </p>
                                            )}
                                        </div>
                                    </div>

                                    <button type="submit" disabled={experienceForm.processing}
                                        className="inline-flex w-full items-center justify-center gap-2 rounded-xl bg-primary px-6 py-3 text-sm font-semibold text-white transition hover:bg-primary-700 disabled:opacity-50">
                                        <i className="fas fa-save" />
                                        {experienceForm.processing ? 'Saving...' : 'Save Experience & Expertise'}
                                    </button>
                                </form>
                            </div>

                            {/* Submit */}
                            <div className="overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-sm">
                                <div className="border-b border-slate-100 bg-gradient-to-r from-primary to-primary-800 px-6 py-4">
                                    <div className="flex items-center gap-3">
                                        <div className="flex h-9 w-9 items-center justify-center rounded-lg bg-white/10">
                                            <i className="fas fa-paper-plane text-sm" />
                                        </div>
                                        <div>
                                            <h3 className="text-lg font-bold">Submit Application</h3>
                                            <p className="text-xs text-slate-300">Step 4 of 4</p>
                                        </div>
                                    </div>
                                </div>
                                <form onSubmit={handleFinalSubmit} className="space-y-5 p-6">
                                    <label className="flex items-start gap-3 rounded-xl border border-slate-100 bg-slate-50 p-4">
                                        <input type="checkbox" checked={finalForm.data.terms}
                                            onChange={(e) => finalForm.setData('terms', e.target.checked)}
                                            className="mt-0.5 rounded border-slate-300 text-primary focus:ring-primary" />
                                        <div>
                                            <span className="text-sm font-semibold">I agree to the terms</span>
                                            <p className="text-xs text-slate-500 mt-1">
                                                By checking this box, you agree to our{' '}
                                                <a href="#" className="font-semibold text-primary hover:text-accent">Instructor Terms</a>,{' '}
                                                <a href="#" className="font-semibold text-primary hover:text-accent">Privacy Policy</a>, and{' '}
                                                <a href="#" className="font-semibold text-primary hover:text-accent">Code of Conduct</a>.
                                            </p>
                                            {finalForm.errors.terms && (
                                                <p className="mt-1.5 text-xs text-accent-600 flex items-center gap-1">
                                                    <i className="fas fa-exclamation-circle" />{finalForm.errors.terms}
                                                </p>
                                            )}
                                        </div>
                                    </label>

                                    <div className="rounded-xl border border-accent-100 bg-accent-50/50 px-4 py-4">
                                        <h4 className="text-sm font-bold flex items-center gap-2 mb-2">
                                            <i className="fas fa-clipboard-list text-accent" />
                                            Review Process
                                        </h4>
                                        <ul className="space-y-1.5 text-xs text-slate-600">
                                            <li className="flex items-center gap-2">
                                                <CheckCircle2 className="h-3.5 w-3.5 text-accent" /> Confirmation email sent
                                            </li>
                                            <li className="flex items-center gap-2">
                                                <i className="fas fa-clock text-xs text-slate-400" /> Review within 5-7 business days
                                            </li>
                                            <li className="flex items-center gap-2">
                                                <i className="fas fa-envelope text-xs text-slate-400" /> Updates via email
                                            </li>
                                        </ul>
                                    </div>

                                    <button type="submit" disabled={!finalForm.data.terms || finalForm.processing}
                                        className="inline-flex w-full items-center justify-center gap-2 rounded-xl bg-primary px-6 py-3.5 text-base font-semibold text-white transition hover:bg-primary-700 disabled:opacity-50">
                                        <i className="fas fa-rocket" />
                                        {finalForm.processing ? 'Submitting...' : 'Submit My Application'}
                                    </button>
                                    <p className="text-center text-xs text-slate-400">
                                        <i className="fas fa-shield-alt mr-1 text-accent" />
                                        Your information is secure and only used for application review
                                    </p>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </section>
        </GuestLayout>
    );
}
