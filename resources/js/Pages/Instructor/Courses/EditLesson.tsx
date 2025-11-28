import { useState, FormEvent, ChangeEvent } from 'react';
import { Head, Link, router, usePage } from '@inertiajs/react';
import DashboardLayout from '@/Layouts/DashboardLayout';

interface CourseModule {
    id: number;
    title: string;
    course: {
        id: number;
        slug: string;
    };
}

interface Lesson {
    id: number;
    title: string;
    type: string;
    description: string;
    content_path: string | null;
    is_preview: boolean;
    assignment_instructions: string | null;
    vimeo_id: string | null;
}

interface LessonType {
    label: string;
    value: string;
}

interface EditLessonProps {
    module: CourseModule;
    lesson: Lesson;
    lessontypes: LessonType[];
}

interface FormData {
    title: string;
    type: string;
    description: string;
    is_preview: boolean;
    has_instruction: boolean;
    assignment_instructions: string;
    link_url: string;
}

export default function EditLesson({ module, lesson, lessontypes }: EditLessonProps) {
    const { sideLinks, errors } = usePage().props as any;
    const [isSubmitting, setIsSubmitting] = useState(false);
    const [formData, setFormData] = useState<FormData>({
        title: lesson.title || '',
        type: lesson.type || '',
        description: lesson.description || '',
        is_preview: lesson.is_preview || false,
        has_instruction: !!lesson.assignment_instructions,
        assignment_instructions: lesson.assignment_instructions || '',
        link_url: lesson.type === 'link' ? (lesson.content_path || '') : '',
    });
    const [videoFile, setVideoFile] = useState<File | null>(null);
    const [pdfFile, setPdfFile] = useState<File | null>(null);

    const handleInputChange = (e: ChangeEvent<HTMLInputElement | HTMLTextAreaElement | HTMLSelectElement>) => {
        const { name, value, type } = e.target;

        if (type === 'checkbox') {
            const checked = (e.target as HTMLInputElement).checked;
            setFormData(prev => ({ ...prev, [name]: checked }));
        } else {
            setFormData(prev => ({ ...prev, [name]: value }));
        }
    };

    const handleVideoChange = (e: ChangeEvent<HTMLInputElement>) => {
        if (e.target.files && e.target.files[0]) {
            setVideoFile(e.target.files[0]);
        }
    };

    const handlePdfChange = (e: ChangeEvent<HTMLInputElement>) => {
        if (e.target.files && e.target.files[0]) {
            setPdfFile(e.target.files[0]);
        }
    };

    const handleSubmit = (e: FormEvent) => {
        e.preventDefault();
        setIsSubmitting(true);

        const data = new FormData();

        // Append all form fields
        data.append('title', formData.title);
        data.append('type', formData.type);
        data.append('description', formData.description);
        data.append('_method', 'PUT');

        if (formData.is_preview) {
            data.append('is_preview', '1');
        }

        if (formData.has_instruction && formData.assignment_instructions) {
            data.append('has_instruction', '1');
            data.append('assignment_instructions', formData.assignment_instructions);
        }

        if (formData.link_url) {
            data.append('link_url', formData.link_url);
        }

        // Append files based on type (if changed)
        if (formData.type === 'video' && videoFile) {
            data.append('video_field', videoFile);
        }

        if (formData.type === 'pdf' && pdfFile) {
            data.append('content_path', pdfFile);
        }

        router.post(route('instructor.courses.lessons.update', [module.course.slug, module.id, lesson.id]), data, {
            preserveScroll: true,
            onFinish: () => {
                setIsSubmitting(false);
            },
        });
    };

    const showVideoField = formData.type === 'video';
    const showPdfField = formData.type === 'pdf';
    const showLinkField = formData.type === 'link';

    return (
        <DashboardLayout sideLinks={sideLinks}>
            <Head title={`Edit Lesson: ${lesson.title}`} />

            <header className="mb-6">
                <h1 className="text-3xl font-bold mb-2">Edit Lesson: {lesson.title}</h1>
                <p className="text-gray-600">Update lesson information and content.</p>
                <Link
                    href={route('instructor.courses.builder', module.course.slug)}
                    className="bg-white p-2.5 text-gray-800 border rounded-md my-1 inline-block hover:bg-gray-50 transition-colors"
                >
                    <i className="fas fa-arrow-left mr-2"></i>
                    Go back to builder
                </Link>
            </header>

            <div className="bg-white rounded-lg shadow p-6">
                <form onSubmit={handleSubmit} className="space-y-4">
                    <div>
                        <label htmlFor="title" className="block mb-2 text-sm font-medium text-gray-900">
                            Lesson Title
                        </label>
                        <input
                            type="text"
                            name="title"
                            id="title"
                            value={formData.title}
                            onChange={handleInputChange}
                            className="bg-gray-50 border border-gray-300 focus:ring-primary focus:border-primary text-gray-900 text-sm rounded-lg block w-full p-2.5"
                            required
                        />
                        {errors?.title && (
                            <p className="text-sm text-red-500 mt-1">{errors.title}</p>
                        )}
                    </div>

                    <div>
                        <label htmlFor="type" className="block mb-2 text-sm font-medium text-gray-900">
                            Lesson Type
                        </label>
                        <select
                            id="type"
                            name="type"
                            value={formData.type}
                            onChange={handleInputChange}
                            className="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary focus:border-primary block w-full p-2.5"
                            required
                            disabled
                        >
                            <option value="">Choose a lesson type</option>
                            {lessontypes.map((lessontype) => (
                                <option key={lessontype.value} value={lessontype.value}>
                                    {lessontype.label}
                                </option>
                            ))}
                        </select>
                        <p className="text-xs text-gray-500 mt-1">Lesson type cannot be changed after creation.</p>
                        {errors?.type && (
                            <p className="text-sm text-red-500 mt-1">{errors.type}</p>
                        )}
                    </div>

                    <div>
                        <label htmlFor="description" className="block mb-2 text-sm font-medium text-gray-900">
                            Description
                        </label>
                        <textarea
                            name="description"
                            id="description"
                            rows={10}
                            value={formData.description}
                            onChange={handleInputChange}
                            className="bg-gray-50 border border-gray-300 resize-none text-gray-900 text-sm focus:ring-primary focus:border-primary rounded-lg block w-full p-2.5"
                        />
                        {errors?.description && (
                            <p className="text-sm text-red-500 mt-1">{errors.description}</p>
                        )}
                    </div>

                    {/* Video Field */}
                    {showVideoField && (
                        <div>
                            <label htmlFor="video_lesson" className="block mb-2 text-sm font-medium text-gray-900">
                                Video Resource {lesson.vimeo_id && <span className="text-xs text-gray-500">(Currently: Vimeo video uploaded)</span>}
                            </label>
                            <input
                                type="file"
                                accept=".mp4,.mov,.mkv"
                                name="video_field"
                                id="video_lesson"
                                onChange={handleVideoChange}
                                className="bg-gray-50 border border-gray-300 file:bg-primary text-gray-900 text-sm rounded-lg block w-full"
                            />
                            <p className="text-xs text-gray-500 mt-1">Leave empty to keep the current video. Uploading a new video will replace the existing one.</p>
                            {errors?.video_field && (
                                <p className="text-sm text-red-500 mt-1">{errors.video_field}</p>
                            )}
                            <div className="flex items-center mt-3 px-1">
                                <input
                                    id="is_preview"
                                    type="checkbox"
                                    name="is_preview"
                                    checked={formData.is_preview}
                                    onChange={handleInputChange}
                                    className="w-4 h-4 border border-gray-300 rounded bg-gray-50 focus:ring-primary text-primary"
                                />
                                <label htmlFor="is_preview" className="ml-2 text-sm font-medium text-gray-900">
                                    Is Preview?
                                </label>
                            </div>
                        </div>
                    )}

                    {/* PDF Field */}
                    {showPdfField && (
                        <div>
                            <label htmlFor="content_path" className="block mb-2 text-sm font-medium text-gray-900">
                                PDF Resource File {lesson.content_path && <span className="text-xs text-gray-500">(Current file exists)</span>}
                            </label>
                            <input
                                type="file"
                                name="content_path"
                                id="content_path"
                                accept=".pdf"
                                onChange={handlePdfChange}
                                className="bg-gray-50 border border-gray-300 text-gray-900 focus:ring-primary focus:border-primary file:bg-primary text-sm rounded-lg block w-full"
                            />
                            <p className="text-xs text-gray-500 mt-1">Leave empty to keep the current PDF.</p>
                            {errors?.content_path && (
                                <p className="text-sm text-red-500 mt-1">{errors.content_path}</p>
                            )}
                        </div>
                    )}

                    {/* Link Field */}
                    {showLinkField && (
                        <div>
                            <label htmlFor="url" className="block mb-2 text-sm font-medium text-gray-900">
                                Resource Link
                            </label>
                            <input
                                type="url"
                                name="link_url"
                                id="url"
                                value={formData.link_url}
                                onChange={handleInputChange}
                                className="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg block w-full p-2.5"
                            />
                            {errors?.link_url && (
                                <p className="text-sm text-red-500 mt-1">{errors.link_url}</p>
                            )}
                        </div>
                    )}

                    {/* Has Instructions Toggle */}
                    <div className="flex items-center mt-3 px-1">
                        <input
                            id="has_instruction"
                            type="checkbox"
                            name="has_instruction"
                            checked={formData.has_instruction}
                            onChange={handleInputChange}
                            className="w-4 h-4 border border-gray-300 rounded bg-gray-50 focus:ring-primary text-primary"
                        />
                        <label htmlFor="has_instruction" className="ml-2 text-sm font-medium text-gray-900">
                            Has Instructions
                        </label>
                    </div>

                    {/* Assignment Instructions */}
                    {formData.has_instruction && (
                        <div>
                            <label htmlFor="assignment_instructions" className="block mb-2 text-sm font-medium text-gray-900">
                                Assignment Instructions
                            </label>
                            <textarea
                                name="assignment_instructions"
                                id="assignment_instructions"
                                rows={4}
                                value={formData.assignment_instructions}
                                onChange={handleInputChange}
                                className="bg-gray-50 border resize-none border-gray-300 text-gray-900 text-sm rounded-lg block w-full p-2.5"
                            />
                            {errors?.assignment_instructions && (
                                <p className="text-sm text-red-500 mt-1">{errors.assignment_instructions}</p>
                            )}
                        </div>
                    )}

                    <div className="flex gap-3 pt-4">
                        <Link
                            href={route('instructor.courses.builder', module.course.slug)}
                            className="flex-1 text-gray-700 bg-white border border-gray-300 hover:bg-gray-50 focus:ring-4 focus:outline-none focus:ring-gray-200 font-medium rounded-lg text-sm px-5 py-2.5 text-center transition-colors"
                        >
                            Cancel
                        </Link>
                        <button
                            type="submit"
                            disabled={isSubmitting}
                            className="flex-1 text-white bg-primary hover:bg-primary-600 focus:ring-4 focus:outline-none focus:ring-primary font-medium rounded-lg text-sm px-5 py-2.5 text-center disabled:opacity-50 disabled:cursor-not-allowed transition-colors"
                        >
                            {isSubmitting ? (
                                <>
                                    <i className="fas fa-spinner fa-spin mr-2"></i>
                                    Updating...
                                </>
                            ) : (
                                'Update Lesson'
                            )}
                        </button>
                    </div>
                </form>
            </div>
        </DashboardLayout>
    );
}
