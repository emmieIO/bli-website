import { useState, FormEvent, ChangeEvent } from 'react';
import { Head, Link, router, usePage } from '@inertiajs/react';
import DashboardLayout from '@/Layouts/DashboardLayout';
import Input from '@/Components/Input';
import Textarea from '@/Components/Textarea';

interface User {
    id: number;
    name: string;
    email: string;
    phone: string;
    headline: string;
    linkedin_url: string;
    website: string;
    resume_path: string;
}

interface InstructorProfile {
    id: number;
    user: User;
    bio: string;
    experience_years: number;
    area_of_expertise: string;
    teaching_history: string;
    intro_video_url: string;
    status: string;
}

interface FormData {
    name: string;
    email: string;
    phone: string;
    headline: string;
    bio: string;
    experience_years: string;
    area_of_expertise: string;
    teaching_history: string;
    intro_video_url: string;
    linkedin_url: string;
    website: string;
    application_status: string;
}

interface ApplicationStatus {
    value: string;
    name: string;
}

interface EditInstructorProps {
    instructor: InstructorProfile;
    applicationStatuses: ApplicationStatus[];
}

export default function EditInstructor({ instructor, applicationStatuses }: EditInstructorProps) {
    const { sideLinks, errors } = usePage().props as any;
    const [isSubmitting, setIsSubmitting] = useState(false);
    const [formData, setFormData] = useState<FormData>({
        name: instructor.user.name || '',
        email: instructor.user.email || '',
        phone: instructor.user.phone || '',
        headline: instructor.user.headline || '',
        bio: instructor.bio || '',
        experience_years: instructor.experience_years?.toString() || '0',
        area_of_expertise: instructor.area_of_expertise || '',
        teaching_history: instructor.teaching_history || '',
        intro_video_url: instructor.intro_video_url || '',
        linkedin_url: instructor.user.linkedin_url || '',
        website: instructor.user.website || '',
        application_status: instructor.status || '',
    });
    const [resumeFile, setResumeFile] = useState<File | null>(null);

    const handleInputChange = (e: ChangeEvent<HTMLInputElement | HTMLTextAreaElement | HTMLSelectElement>) => {
        const { name, value } = e.target;
        setFormData(prev => ({ ...prev, [name]: value }));
    };

    const handleFileChange = (e: ChangeEvent<HTMLInputElement>) => {
        if (e.target.files && e.target.files[0]) {
            setResumeFile(e.target.files[0]);
        }
    };

    const handleSubmit = (e: FormEvent) => {
        e.preventDefault();
        setIsSubmitting(true);

        const data = new FormData();

        // Append all form fields
        Object.entries(formData).forEach(([key, value]) => {
            if (value !== '') {
                data.append(key, value);
            }
        });

        // Append resume file if selected
        if (resumeFile) {
            data.append('resume_path', resumeFile);
        }

        router.post(route('admin.instructors.update', instructor.id), data, {
            preserveScroll: true,
            onFinish: () => {
                setIsSubmitting(false);
            },
        });
    };

    return (
        <DashboardLayout sideLinks={sideLinks}>
            <Head title="Edit Instructor Profile" />

            <div className="max-w-4xl mx-auto p-6">
                {/* Header Section */}
                <div className="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 mb-8">
                    <div className="flex items-center gap-3">
                        <div className="p-3 rounded-lg bg-[#002147]/10">
                            <i className="fas fa-user w-6 h-6 text-[#002147]"></i>
                        </div>
                        <div>
                            <h1 className="text-2xl md:text-3xl font-extrabold text-gray-900">Edit Instructor Profile</h1>
                            <p className="text-sm text-gray-500 mt-1">Update instructor details and manage their profile information</p>
                        </div>
                    </div>

                    {/* Back Button */}
                    <Link
                        href={route('admin.instructors.index')}
                        className="inline-flex items-center px-4 py-2 border border-gray-300 rounded-lg text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-[#002147] transition"
                    >
                        <i className="fas fa-arrow-left w-4 h-4 mr-2"></i>
                        Back
                    </Link>
                </div>

                {/* Form Card */}
                <div className="bg-white rounded-xl shadow-sm border border-gray-200 p-6 md:p-8">
                    <form onSubmit={handleSubmit} className="space-y-8">
                        {/* Section: User Information */}
                        <div>
                            <h2 className="text-lg font-semibold text-gray-900 mb-4 pb-2 border-b border-gray-200">
                                👤 User Information
                            </h2>
                            <div className="grid md:grid-cols-2 gap-6">
                                <Input
                                    label="Full Name"
                                    name="name"
                                    value={formData.name}
                                    onChange={handleInputChange}
                                    error={errors?.name}
                                    icon="user"
                                    required
                                />
                                <Input
                                    label="Email Address"
                                    name="email"
                                    type="email"
                                    value={formData.email}
                                    onChange={handleInputChange}
                                    error={errors?.email}
                                    icon="envelope"
                                    required
                                />
                                <Input
                                    label="Phone Number"
                                    name="phone"
                                    type="tel"
                                    value={formData.phone}
                                    onChange={handleInputChange}
                                    error={errors?.phone}
                                    icon="phone"
                                    required
                                />
                                <Input
                                    label="Professional Headline"
                                    name="headline"
                                    value={formData.headline}
                                    onChange={handleInputChange}
                                    error={errors?.headline}
                                    icon="briefcase"
                                    required
                                />
                            </div>
                        </div>

                        {/* Section: Professional Bio */}
                        <div>
                            <h2 className="text-lg font-semibold text-gray-900 mb-4 pb-2 border-b border-gray-200">
                                📝 Professional Bio
                            </h2>
                            <Textarea
                                label="Bio (Tell us about yourself)"
                                name="bio"
                                value={formData.bio}
                                onChange={handleInputChange}
                                error={errors?.bio}
                                rows={5}
                                placeholder="Write a short professional bio..."
                                required
                            />
                            <p className="mt-1 text-xs text-gray-500">Max 500 characters recommended.</p>
                        </div>

                        {/* Section: Teaching & Experience */}
                        <div>
                            <h2 className="text-lg font-semibold text-gray-900 mb-4 pb-2 border-b border-gray-200">
                                🎓 Teaching & Experience
                            </h2>
                            <div className="grid md:grid-cols-2 gap-6">
                                <Input
                                    label="Years of Experience"
                                    name="experience_years"
                                    type="number"
                                    min="0"
                                    max="30"
                                    value={formData.experience_years}
                                    onChange={handleInputChange}
                                    error={errors?.experience_years}
                                    required
                                />
                                <div>
                                    <Input
                                        label="Areas of Expertise"
                                        name="area_of_expertise"
                                        value={formData.area_of_expertise}
                                        onChange={handleInputChange}
                                        error={errors?.area_of_expertise}
                                        placeholder="e.g. JavaScript, Python, UI/UX Design"
                                        required
                                    />
                                    <p className="mt-1 text-xs text-gray-500">
                                        Separate with commas (e.g. "React, Node.js, Agile")
                                    </p>
                                </div>
                            </div>

                            <div className="mt-6">
                                <Textarea
                                    label="Teaching Philosophy & History"
                                    name="teaching_history"
                                    value={formData.teaching_history}
                                    onChange={handleInputChange}
                                    error={errors?.teaching_history}
                                    rows={5}
                                    placeholder="Describe your teaching approach, past experience, and educational background..."
                                    required
                                />
                            </div>
                        </div>

                        {/* Section: Media & Links */}
                        <div>
                            <h2 className="text-lg font-semibold text-gray-900 mb-4 pb-2 border-b border-gray-200">
                                🔗 Media & Links
                            </h2>
                            <div className="grid md:grid-cols-2 gap-6">
                                <Input
                                    label="Intro Video URL (YouTube/Vimeo)"
                                    name="intro_video_url"
                                    type="url"
                                    value={formData.intro_video_url}
                                    onChange={handleInputChange}
                                    error={errors?.intro_video_url}
                                    placeholder="https://youtube.com/..."
                                    required
                                />
                                <Input
                                    label="LinkedIn Profile URL"
                                    name="linkedin_url"
                                    type="url"
                                    value={formData.linkedin_url}
                                    onChange={handleInputChange}
                                    error={errors?.linkedin_url}
                                    placeholder="https://linkedin.com/in/..."
                                />
                                <Input
                                    label="Personal Website"
                                    name="website"
                                    type="url"
                                    value={formData.website}
                                    onChange={handleInputChange}
                                    error={errors?.website}
                                    placeholder="https://yourwebsite.com"
                                />
                            </div>
                        </div>

                        {/* Section: Documents & Status */}
                        <div>
                            <h2 className="text-lg font-semibold text-gray-900 mb-4 pb-2 border-b border-gray-200">
                                📄 Documents & Status
                            </h2>
                            <div className="grid md:grid-cols-2 gap-6">
                                <div>
                                    <label htmlFor="resume_path" className="block mb-2 text-sm font-medium text-gray-900">
                                        Resume (PDF, DOCX)
                                    </label>
                                    <input
                                        type="file"
                                        name="resume_path"
                                        id="resume_path"
                                        accept=".pdf,.doc,.docx"
                                        onChange={handleFileChange}
                                        className="block w-full text-sm text-gray-900 bg-gray-50 rounded-lg border border-gray-300 focus:ring-2 focus:ring-[#002147] focus:border-[#002147]
                                            file:mr-4 file:py-2 file:px-4 file:rounded-md file:border-0 file:text-sm file:font-medium
                                            file:bg-[#002147] file:text-white hover:file:bg-[#001a44] transition"
                                    />
                                    {instructor.resume_path && (
                                        <p className="mt-2 text-xs text-gray-600">
                                            📄 Current:{' '}
                                            <a
                                                href={`/storage/${instructor.resume_path}`}
                                                target="_blank"
                                                className="underline hover:text-[#002147]"
                                            >
                                                View Resume
                                            </a>
                                        </p>
                                    )}
                                    {errors?.resume_path && (
                                        <p className="text-sm text-red-500 mt-1 font-lato">{errors.resume_path}</p>
                                    )}
                                </div>

                                <div>
                                    <label htmlFor="application_status" className="block mb-2 text-sm font-medium text-gray-900">
                                        Application Status
                                    </label>
                                    <select
                                        id="application_status"
                                        name="application_status"
                                        value={formData.application_status}
                                        onChange={handleInputChange}
                                        className="block w-full p-3 text-sm text-gray-900 bg-gray-50 rounded-lg border border-gray-300 focus:ring-2 focus:ring-[#002147] focus:border-[#002147] transition"
                                    >
                                        {applicationStatuses.map((status) => (
                                            <option key={status.value} value={status.value}>
                                                {status.name.replace(/_/g, ' ').replace(/\b\w/g, (l) => l.toUpperCase())}
                                            </option>
                                        ))}
                                    </select>
                                    {errors?.application_status && (
                                        <p className="text-sm text-red-500 mt-1 font-lato">{errors.application_status}</p>
                                    )}
                                    <p className="mt-1 text-xs text-gray-500">Changing status may trigger notifications.</p>
                                </div>
                            </div>
                        </div>

                        {/* Submit Button */}
                        <div className="pt-6 border-t border-gray-200 flex flex-col sm:flex-row justify-end gap-3">
                            <Link
                                href={route('admin.instructors.index')}
                                className="px-5 py-2.5 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-lg hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-300 transition text-center"
                            >
                                Cancel
                            </Link>
                            <button
                                type="submit"
                                disabled={isSubmitting}
                                className="px-6 py-2.5 text-sm font-medium text-white bg-[#002147] rounded-lg hover:bg-[#001a44] focus:ring-4 focus:ring-blue-300 focus:outline-none transition flex items-center justify-center disabled:opacity-50 disabled:cursor-not-allowed"
                            >
                                <i className="fas fa-check w-4 h-4 mr-2"></i>
                                {isSubmitting ? 'Updating...' : 'Update Instructor Profile'}
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </DashboardLayout>
    );
}
