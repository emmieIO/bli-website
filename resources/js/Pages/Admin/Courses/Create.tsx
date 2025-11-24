import { useState, FormEvent, ChangeEvent } from 'react';
import { Head, Link, router, usePage } from '@inertiajs/react';
import DashboardLayout from '@/Layouts/DashboardLayout';

interface Category {
    id: number;
    name: string;
}

interface Level {
    value: string;
    label: string;
}

interface CreateCourseProps {
    categories: Category[];
    levels: Level[];
}

interface FormData {
    title: string;
    subtitle: string;
    description: string;
    language: string;
    level: string;
    category_id: string;
    is_free: boolean;
    price: string;
}

export default function CreateCourse({ categories, levels }: CreateCourseProps) {
    const { sideLinks, errors } = usePage().props as any;
    const [isSubmitting, setIsSubmitting] = useState(false);
    const [formData, setFormData] = useState<FormData>({
        title: '',
        subtitle: '',
        description: '',
        language: 'English',
        level: '',
        category_id: '',
        is_free: true,
        price: '0',
    });
    const [thumbnailFile, setThumbnailFile] = useState<File | null>(null);
    const [previewVideoFile, setPreviewVideoFile] = useState<File | null>(null);

    const handleInputChange = (e: ChangeEvent<HTMLInputElement | HTMLTextAreaElement | HTMLSelectElement>) => {
        const { name, value, type } = e.target;

        if (type === 'checkbox') {
            const checked = (e.target as HTMLInputElement).checked;
            setFormData(prev => ({ ...prev, [name]: checked }));
        } else {
            setFormData(prev => ({ ...prev, [name]: value }));
        }
    };

    const handleThumbnailChange = (e: ChangeEvent<HTMLInputElement>) => {
        if (e.target.files && e.target.files[0]) {
            setThumbnailFile(e.target.files[0]);
        }
    };

    const handlePreviewVideoChange = (e: ChangeEvent<HTMLInputElement>) => {
        if (e.target.files && e.target.files[0]) {
            setPreviewVideoFile(e.target.files[0]);
        }
    };

    const handleSubmit = (e: FormEvent) => {
        e.preventDefault();
        setIsSubmitting(true);

        const data = new FormData();

        // Append all form fields
        Object.entries(formData).forEach(([key, value]) => {
            data.append(key, String(value));
        });

        // Append files
        if (thumbnailFile) {
            data.append('thumbnail', thumbnailFile);
        }

        if (previewVideoFile) {
            data.append('preview_video', previewVideoFile);
        }

        router.post(route('admin.courses.store'), data, {
            preserveScroll: true,
            onFinish: () => {
                setIsSubmitting(false);
            },
        });
    };

    return (
        <DashboardLayout sideLinks={sideLinks}>
            <Head title="Create New Course" />

            <div>
                <div className="lg:flex justify-between items-center">
                    <div>
                        <h2 className="text-3xl font-bold text-[#002147] leading-tight">
                            Create New Course
                        </h2>
                        <p className="text-sm text-gray-600 mt-2">Build engaging course content and start teaching students worldwide</p>
                    </div>
                    <div className="flex gap-3 mt-4 lg:mt-0">
                        <Link
                            href={route('admin.courses.index')}
                            className="bg-gray-100 hover:bg-gray-200 text-gray-700 px-4 py-2 rounded-lg flex items-center gap-2 transition duration-200"
                        >
                            <i className="fas fa-arrow-left w-4 h-4"></i>
                            Back to Courses
                        </Link>
                    </div>
                </div>
            </div>

            <div className="py-6">
                <div className="max-w-4xl mx-auto">
                    <form onSubmit={handleSubmit} className="bg-white overflow-hidden shadow-lg sm:rounded-xl border border-gray-200">
                        <div className="bg-[#002147] px-6 py-8 text-white">
                            <h3 className="text-xl font-bold">Course Information</h3>
                            <p className="text-blue-100 mt-2">Let's start with the basics - tell us about your course</p>
                        </div>

                        <div className="p-8 space-y-8">
                            {/* Course Title */}
                            <div>
                                <label htmlFor="title" className="block text-sm font-semibold text-gray-700 mb-3">
                                    Course Title *
                                </label>
                                <input
                                    type="text"
                                    id="title"
                                    name="title"
                                    value={formData.title}
                                    onChange={handleInputChange}
                                    className="w-full px-4 py-3 border-2 border-gray-200 rounded-xl shadow-sm focus:outline-none focus:ring-2 focus:ring-[#002147] focus:border-[#002147] transition-colors"
                                    placeholder="e.g., Complete Web Development with Laravel"
                                    maxLength={255}
                                    required
                                />
                                <p className="text-xs text-gray-500 mt-2">Create an engaging title that clearly describes what students will learn</p>
                                {errors?.title && (
                                    <p className="mt-2 text-sm text-red-600 flex items-center">
                                        <i className="fas fa-exclamation-circle w-4 h-4 mr-1"></i>
                                        {errors.title}
                                    </p>
                                )}
                            </div>

                            {/* Course Subtitle */}
                            <div>
                                <label htmlFor="subtitle" className="block text-sm font-semibold text-gray-700 mb-3">
                                    Course Subtitle
                                </label>
                                <input
                                    type="text"
                                    id="subtitle"
                                    name="subtitle"
                                    value={formData.subtitle}
                                    onChange={handleInputChange}
                                    className="w-full px-4 py-3 border-2 border-gray-200 rounded-xl shadow-sm focus:outline-none focus:ring-2 focus:ring-[#002147] focus:border-[#002147] transition-colors"
                                    placeholder="e.g., Build modern web applications from scratch"
                                    maxLength={500}
                                />
                                <p className="text-xs text-gray-500 mt-2">Optional tagline to give more context (max 500 characters)</p>
                                {errors?.subtitle && (
                                    <p className="mt-2 text-sm text-red-600">{errors.subtitle}</p>
                                )}
                            </div>

                            {/* Course Description */}
                            <div>
                                <label htmlFor="description" className="block text-sm font-semibold text-gray-700 mb-3">
                                    Course Description *
                                </label>
                                <textarea
                                    id="description"
                                    name="description"
                                    value={formData.description}
                                    onChange={handleInputChange}
                                    rows={8}
                                    className="w-full px-4 py-3 border-2 border-gray-200 rounded-xl shadow-sm focus:outline-none focus:ring-2 focus:ring-[#002147] focus:border-[#002147] transition-colors resize-none"
                                    placeholder="Provide a comprehensive description of what students will learn in this course. Include key topics, skills, outcomes, and target audience. Minimum 100 characters required."
                                    minLength={100}
                                    required
                                />
                                <p className="text-xs text-gray-500 mt-2">Detailed description helps students understand the value of your course (min. 100 characters)</p>
                                {errors?.description && (
                                    <p className="mt-2 text-sm text-red-600">{errors.description}</p>
                                )}
                            </div>

                            {/* Course Language */}
                            <div>
                                <label htmlFor="language" className="block text-sm font-semibold text-gray-700 mb-3">
                                    Course Language *
                                </label>
                                <select
                                    id="language"
                                    name="language"
                                    value={formData.language}
                                    onChange={handleInputChange}
                                    className="w-full px-4 py-3 border-2 border-gray-200 rounded-xl shadow-sm focus:outline-none focus:ring-2 focus:ring-[#002147] focus:border-[#002147] transition-colors"
                                    required
                                >
                                    <option value="English">English</option>
                                    <option value="Spanish">Spanish</option>
                                    <option value="French">French</option>
                                    <option value="German">German</option>
                                    <option value="Portuguese">Portuguese</option>
                                    <option value="Arabic">Arabic</option>
                                </select>
                                {errors?.language && (
                                    <p className="mt-2 text-sm text-red-600">{errors.language}</p>
                                )}
                            </div>

                            {/* Course Level */}
                            <div>
                                <label htmlFor="level" className="block text-sm font-semibold text-gray-700 mb-3">
                                    Course Level *
                                </label>
                                <select
                                    id="level"
                                    name="level"
                                    value={formData.level}
                                    onChange={handleInputChange}
                                    className="w-full px-4 py-3 border-2 border-gray-200 rounded-xl shadow-sm focus:outline-none focus:ring-2 focus:ring-[#002147] focus:border-[#002147] transition-colors"
                                    required
                                >
                                    <option value="">Select Course Level</option>
                                    {levels.map((level) => (
                                        <option key={level.value} value={level.value}>
                                            {level.label}
                                        </option>
                                    ))}
                                </select>
                                {errors?.level && (
                                    <p className="mt-2 text-sm text-red-600">{errors.level}</p>
                                )}
                            </div>

                            {/* Course Category */}
                            <div>
                                <label htmlFor="category_id" className="block text-sm font-semibold text-gray-700 mb-3">
                                    Course Category *
                                </label>
                                <select
                                    id="category_id"
                                    name="category_id"
                                    value={formData.category_id}
                                    onChange={handleInputChange}
                                    className="w-full px-4 py-3 border-2 border-gray-200 rounded-xl shadow-sm focus:outline-none focus:ring-2 focus:ring-[#002147] focus:border-[#002147] transition-colors"
                                    required
                                >
                                    <option value="">Select Course Category</option>
                                    {categories.map((category) => (
                                        <option key={category.id} value={category.id}>
                                            {category.name}
                                        </option>
                                    ))}
                                </select>
                                {errors?.category_id && (
                                    <p className="mt-2 text-sm text-red-600">{errors.category_id}</p>
                                )}
                            </div>

                            {/* Course Thumbnail */}
                            <div>
                                <label htmlFor="thumbnail" className="block text-sm font-semibold text-gray-700 mb-3">
                                    Course Thumbnail *
                                </label>
                                <input
                                    type="file"
                                    id="thumbnail"
                                    name="thumbnail"
                                    accept=".jpg,.jpeg,.png,.webp"
                                    onChange={handleThumbnailChange}
                                    className="w-full px-4 py-3 border-2 border-gray-200 rounded-xl file:mr-4 file:py-2 file:px-4 file:rounded-md file:border-0 file:text-sm file:font-medium file:bg-[#002147] file:text-white hover:file:bg-primary-600"
                                    required
                                />
                                <p className="text-xs text-gray-500 mt-2">JPG, PNG, or WEBP (max 2MB)</p>
                                {errors?.thumbnail && (
                                    <p className="mt-2 text-sm text-red-600">{errors.thumbnail}</p>
                                )}
                            </div>

                            {/* Preview Video (Optional) */}
                            <div>
                                <label htmlFor="preview_video" className="block text-sm font-semibold text-gray-700 mb-3">
                                    Preview Video (Optional)
                                </label>
                                <input
                                    type="file"
                                    id="preview_video"
                                    name="preview_video"
                                    accept=".mp4,.mov,.avi,.wmv"
                                    onChange={handlePreviewVideoChange}
                                    className="w-full px-4 py-3 border-2 border-gray-200 rounded-xl file:mr-4 file:py-2 file:px-4 file:rounded-md file:border-0 file:text-sm file:font-medium file:bg-[#002147] file:text-white hover:file:bg-primary-600"
                                />
                                <p className="text-xs text-gray-500 mt-2">MP4, MOV, AVI, or WMV (max 50MB)</p>
                                {errors?.preview_video && (
                                    <p className="mt-2 text-sm text-red-600">{errors.preview_video}</p>
                                )}
                            </div>

                            {/* Pricing */}
                            <div>
                                <label className="block text-sm font-semibold text-gray-700 mb-3">Course Pricing *</label>
                                <div className="flex items-center mb-3">
                                    <input
                                        type="checkbox"
                                        id="is_free"
                                        name="is_free"
                                        checked={formData.is_free}
                                        onChange={handleInputChange}
                                        className="w-4 h-4 text-[#002147] border-gray-300 rounded focus:ring-[#002147]"
                                    />
                                    <label htmlFor="is_free" className="ml-2 text-sm text-gray-700">
                                        This is a free course
                                    </label>
                                </div>

                                {!formData.is_free && (
                                    <div>
                                        <label htmlFor="price" className="block text-sm font-medium text-gray-700 mb-2">
                                            Price (USD) *
                                        </label>
                                        <input
                                            type="number"
                                            id="price"
                                            name="price"
                                            value={formData.price}
                                            onChange={handleInputChange}
                                            step="0.01"
                                            min="0"
                                            max="9999.99"
                                            className="w-full px-4 py-3 border-2 border-gray-200 rounded-xl shadow-sm focus:outline-none focus:ring-2 focus:ring-[#002147] focus:border-[#002147]"
                                            required
                                        />
                                        {errors?.price && (
                                            <p className="mt-2 text-sm text-red-600">{errors.price}</p>
                                        )}
                                    </div>
                                )}
                            </div>

                            {/* Submit Button */}
                            <div className="pt-6 border-t border-gray-200 flex flex-col sm:flex-row justify-end gap-3">
                                <Link
                                    href={route('admin.courses.index')}
                                    className="px-5 py-2.5 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-lg hover:bg-gray-50 text-center"
                                >
                                    Cancel
                                </Link>
                                <button
                                    type="submit"
                                    disabled={isSubmitting}
                                    className="px-6 py-2.5 text-sm font-medium text-white bg-[#002147] rounded-lg hover:bg-primary-600 disabled:opacity-50 disabled:cursor-not-allowed"
                                >
                                    {isSubmitting ? 'Creating...' : 'Create Course & Continue to Builder'}
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </DashboardLayout>
    );
}
