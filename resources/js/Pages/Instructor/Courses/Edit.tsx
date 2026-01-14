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

interface Course {
    id: number;
    slug: string;
    title: string;
    subtitle: string;
    description: string;
    language: string;
    level: string;
    category_id: number;
    is_free: boolean;
    price: string;
    thumbnail_path?: string;
    preview_video_id?: string;
}

interface EditCourseProps {
    course: Course;
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

export default function EditCourse({ course, categories, levels }: EditCourseProps) {
    const { sideLinks, errors } = usePage().props as any;
    const [isSubmitting, setIsSubmitting] = useState(false);
    const [formData, setFormData] = useState<FormData>({
        title: course.title || '',
        subtitle: course.subtitle || '',
        description: course.description || '',
        language: course.language || 'English',
        level: course.level || '',
        category_id: String(course.category_id) || '',
        is_free: course.is_free ?? true,
        price: course.price || '0',
    });
    const [thumbnailFile, setThumbnailFile] = useState<File | null>(null);
    const [previewVideoFile, setPreviewVideoFile] = useState<File | null>(null);

    const handleInputChange = (e: ChangeEvent<HTMLInputElement | HTMLTextAreaElement | HTMLSelectElement>) => {
        const { name, value, type } = e.target;

        if (type === 'checkbox') {
            const checked = (e.target as HTMLInputElement).checked;
            setFormData(prev => ({
                ...prev,
                [name]: checked,
                price: checked ? '0' : prev.price,
            }));
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
        data.append('_method', 'PUT');

        // Append all form fields
        Object.entries(formData).forEach(([key, value]) => {
            data.append(key, String(value));
        });

        // Append files only if new ones are selected
        if (thumbnailFile) {
            data.append('thumbnail', thumbnailFile);
        }

        if (previewVideoFile) {
            data.append('preview_video', previewVideoFile);
        }

        router.post(route('instructor.courses.update', course.slug), data, {
            preserveScroll: true,
            onFinish: () => {
                setIsSubmitting(false);
            },
        });
    };

    return (
        <DashboardLayout sideLinks={sideLinks}>
            <Head title="Edit Course" />

            {/* Header */}
            <div className="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 mb-6">
                <div>
                    <h2 className="text-3xl font-bold text-gray-800 font-montserrat">Edit Course</h2>
                    <p className="text-sm text-gray-600 mt-2">Update your course information</p>
                </div>
                <div className="flex gap-3">
                    <Link
                        href={route('instructor.courses.index')}
                        className="bg-gray-100 hover:bg-gray-200 text-gray-700 px-4 py-2 rounded-lg flex items-center gap-2 transition duration-200"
                    >
                        <i className="fas fa-arrow-left w-4 h-4"></i>
                        Back to Courses
                    </Link>
                </div>
            </div>

            {/* Form */}
            <form onSubmit={handleSubmit} className="max-w-4xl mx-auto bg-white shadow-lg rounded-xl border border-gray-200 overflow-hidden">
                <div className="bg-primary px-6 py-8 text-white">
                    <h3 className="text-xl font-bold font-montserrat">Course Information</h3>
                    <p className="text-blue-100 mt-2">Update your course details</p>
                </div>

                <div className="p-8 space-y-6">
                    {/* Course Title */}
                    <div>
                        <label htmlFor="title" className="block text-sm font-semibold text-gray-700 mb-2">
                            Course Title *
                        </label>
                        <input
                            type="text"
                            id="title"
                            name="title"
                            value={formData.title}
                            onChange={handleInputChange}
                            className="w-full px-4 py-3 border-2 border-gray-200 rounded-xl focus:ring-2 focus:ring-primary focus:border-primary"
                            placeholder="e.g., Kingdom Leadership Principles"
                            maxLength={255}
                            required
                        />
                        {errors?.title && <p className="mt-2 text-sm text-red-600">{errors.title}</p>}
                    </div>

                    {/* Course Subtitle */}
                    <div>
                        <label htmlFor="subtitle" className="block text-sm font-semibold text-gray-700 mb-2">
                            Course Subtitle
                        </label>
                        <input
                            type="text"
                            id="subtitle"
                            name="subtitle"
                            value={formData.subtitle}
                            onChange={handleInputChange}
                            className="w-full px-4 py-3 border-2 border-gray-200 rounded-xl focus:ring-2 focus:ring-primary focus:border-primary"
                            placeholder="e.g., Build modern web applications from scratch"
                            maxLength={500}
                        />
                        {errors?.subtitle && <p className="mt-2 text-sm text-red-600">{errors.subtitle}</p>}
                    </div>

                    {/* Course Description */}
                    <div>
                        <label htmlFor="description" className="block text-sm font-semibold text-gray-700 mb-2">
                            Course Description *
                        </label>
                        <textarea
                            id="description"
                            name="description"
                            value={formData.description}
                            onChange={handleInputChange}
                            rows={8}
                            className="w-full px-4 py-3 border-2 border-gray-200 rounded-xl focus:ring-2 focus:ring-primary focus:border-primary resize-none"
                            placeholder="Provide a comprehensive description..."
                            minLength={100}
                            required
                        />
                        {errors?.description && <p className="mt-2 text-sm text-red-600">{errors.description}</p>}
                    </div>

                    {/* Language & Level */}
                    <div className="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label htmlFor="language" className="block text-sm font-semibold text-gray-700 mb-2">
                                Course Language *
                            </label>
                            <select
                                id="language"
                                name="language"
                                value={formData.language}
                                onChange={handleInputChange}
                                className="w-full px-4 py-3 border-2 border-gray-200 rounded-xl focus:ring-2 focus:ring-primary focus:border-primary"
                                required
                            >
                                <option value="English">English</option>
                                <option value="Spanish">Spanish</option>
                                <option value="French">French</option>
                                <option value="German">German</option>
                                <option value="Chinese">Chinese</option>
                                <option value="Arabic">Arabic</option>
                            </select>
                            {errors?.language && <p className="mt-2 text-sm text-red-600">{errors.language}</p>}
                        </div>

                        <div>
                            <label htmlFor="level" className="block text-sm font-semibold text-gray-700 mb-2">
                                Course Level *
                            </label>
                            <select
                                id="level"
                                name="level"
                                value={formData.level}
                                onChange={handleInputChange}
                                className="w-full px-4 py-3 border-2 border-gray-200 rounded-xl focus:ring-2 focus:ring-primary focus:border-primary"
                                required
                            >
                                <option value="">Select Level</option>
                                {levels.map(level => (
                                    <option key={level.value} value={level.value}>
                                        {level.label}
                                    </option>
                                ))}
                            </select>
                            {errors?.level && <p className="mt-2 text-sm text-red-600">{errors.level}</p>}
                        </div>
                    </div>

                    {/* Category */}
                    <div>
                        <label htmlFor="category_id" className="block text-sm font-semibold text-gray-700 mb-2">
                            Course Category *
                        </label>
                        <select
                            id="category_id"
                            name="category_id"
                            value={formData.category_id}
                            onChange={handleInputChange}
                            className="w-full px-4 py-3 border-2 border-gray-200 rounded-xl focus:ring-2 focus:ring-primary focus:border-primary"
                            required
                        >
                            <option value="">Select Category</option>
                            {categories.map(category => (
                                <option key={category.id} value={category.id}>
                                    {category.name.charAt(0).toUpperCase() + category.name.slice(1)}
                                </option>
                            ))}
                        </select>
                        {errors?.category_id && <p className="mt-2 text-sm text-red-600">{errors.category_id}</p>}
                    </div>

                    {/* Thumbnail */}
                    <div>
                        <label htmlFor="thumbnail" className="block text-sm font-semibold text-gray-700 mb-2">
                            Course Thumbnail
                        </label>
                        <input
                            type="file"
                            id="thumbnail"
                            onChange={handleThumbnailChange}
                            accept="image/*"
                            className="w-full px-4 py-3 border-2 border-gray-200 rounded-xl file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:bg-primary file:text-white hover:file:bg-primary-600"
                        />
                        {course.thumbnail_path && (
                            <p className="mt-2 text-xs text-gray-600">
                                Current: <a href={`/storage/${course.thumbnail_path}`} target="_blank" className="text-primary hover:underline">View current thumbnail</a>
                            </p>
                        )}
                        {errors?.thumbnail_path && <p className="mt-2 text-sm text-red-600">{errors.thumbnail_path}</p>}
                    </div>

                    {/* Preview Video */}
                    <div>
                        <label htmlFor="preview_video" className="block text-sm font-semibold text-gray-700 mb-2">
                            Preview Video (Optional)
                        </label>
                        <input
                            type="file"
                            id="preview_video"
                            onChange={handlePreviewVideoChange}
                            accept="video/*"
                            className="w-full px-4 py-3 border-2 border-gray-200 rounded-xl file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:bg-primary file:text-white hover:file:bg-primary-600"
                        />
                        {course.preview_video_id && (
                            <p className="mt-2 text-xs text-gray-600">Current preview video exists</p>
                        )}
                        {errors?.preview_video && <p className="mt-2 text-sm text-red-600">{errors.preview_video}</p>}
                    </div>

                    {/* Pricing */}
                    <div className="border-t border-gray-200 pt-6">
                        <h4 className="text-lg font-semibold text-gray-800 mb-4">Pricing</h4>
                        <div className="space-y-4">
                            <div className="flex items-center">
                                <input
                                    type="checkbox"
                                    id="is_free"
                                    name="is_free"
                                    checked={formData.is_free}
                                    onChange={handleInputChange}
                                    className="w-4 h-4 text-primary border-gray-300 rounded focus:ring-primary"
                                />
                                <label htmlFor="is_free" className="ml-2 text-sm text-gray-700">
                                    This is a free course
                                </label>
                            </div>

                            {!formData.is_free && (
                                <div>
                                    <label htmlFor="price" className="block text-sm font-semibold text-gray-700 mb-2">
                                        Course Price (₦) *
                                    </label>
                                    <input
                                        type="number"
                                        id="price"
                                        name="price"
                                        value={formData.price}
                                        onChange={handleInputChange}
                                        className="w-full px-4 py-3 border-2 border-gray-200 rounded-xl focus:ring-2 focus:ring-primary focus:border-primary"
                                        min="0"
                                        step="0.01"
                                        required={!formData.is_free}
                                    />
                                    {errors?.price && <p className="mt-2 text-sm text-red-600">{errors.price}</p>}
                                </div>
                            )}
                        </div>
                    </div>

                    {/* Submit Button */}
                    <div className="flex justify-end gap-3 pt-6 border-t border-gray-200">
                        <Link
                            href={route('instructor.courses.index')}
                            className="px-6 py-3 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50 transition"
                        >
                            Cancel
                        </Link>
                        <button
                            type="submit"
                            disabled={isSubmitting}
                            className="px-6 py-3 bg-primary text-white rounded-lg hover:bg-primary-600 transition disabled:opacity-50 disabled:cursor-not-allowed"
                        >
                            {isSubmitting ? 'Updating...' : 'Update Course'}
                        </button>
                    </div>
                </div>
            </form>
        </DashboardLayout>
    );
}
