import { useState, FormEvent, ChangeEvent } from 'react';
import { Head, Link, router, usePage } from '@inertiajs/react';
import DashboardLayout from '@/Layouts/DashboardLayout';
import Input from '@/Components/Input';
import Textarea from '@/Components/Textarea';

interface Event {
    id: number;
    title: string;
    slug: string;
}

interface FormData {
    title: string;
    type: 'file' | 'link' | '';
    external_link: string;
    description: string;
    is_downloadable: boolean;
}

interface AddResourceProps {
    event: Event;
}

export default function AddResource({ event }: AddResourceProps) {
    const { sideLinks, errors } = usePage().props as any;
    const [isSubmitting, setIsSubmitting] = useState(false);
    const [formData, setFormData] = useState<FormData>({
        title: '',
        type: '',
        external_link: '',
        description: '',
        is_downloadable: false,
    });
    const [file, setFile] = useState<File | null>(null);

    const handleInputChange = (e: ChangeEvent<HTMLInputElement | HTMLTextAreaElement | HTMLSelectElement>) => {
        const { name, value, type } = e.target;

        if (type === 'checkbox') {
            const checked = (e.target as HTMLInputElement).checked;
            setFormData(prev => ({ ...prev, [name]: checked }));
        } else {
            setFormData(prev => ({ ...prev, [name]: value }));
        }
    };

    const handleFileChange = (e: ChangeEvent<HTMLInputElement>) => {
        if (e.target.files && e.target.files[0]) {
            setFile(e.target.files[0]);
        }
    };

    const handleSubmit = (e: FormEvent) => {
        e.preventDefault();
        setIsSubmitting(true);

        const data = new FormData();

        // Append all form fields
        Object.entries(formData).forEach(([key, value]) => {
            if (typeof value === 'boolean') {
                if (value) data.append(key, '1');
            } else if (value !== '') {
                data.append(key, value);
            }
        });

        // Append file if selected
        if (file) {
            data.append('file_path', file);
        }

        router.post(route('admin.events.resources.store', event.id), data, {
            preserveScroll: true,
            onFinish: () => {
                setIsSubmitting(false);
            },
        });
    };

    return (
        <DashboardLayout sideLinks={sideLinks}>
            <Head title="Add Event Resource" />

            <div className="p-6">
                {/* Page Header */}
                <div className="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 mb-8 pb-4 border-b border-primary-100">
                    <h2 className="text-3xl font-bold text-primary flex items-center gap-3">
                        <i className="fas fa-folder-plus w-8 h-8"></i>
                        Add Event Resource
                    </h2>
                    <Link
                        href={route('admin.events.show', event.slug)}
                        className="inline-flex items-center gap-2 text-primary hover:text-primary-600 font-medium transition-colors"
                    >
                        <i className="fas fa-arrow-left w-4 h-4"></i>
                        Back to Event
                    </Link>
                </div>

                {/* Form Card */}
                <form
                    onSubmit={handleSubmit}
                    className="bg-white border border-primary-100 rounded-2xl shadow-lg p-8 space-y-8"
                >
                    {/* Basic Information */}
                    <div className="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <Input
                            label="Title"
                            name="title"
                            value={formData.title}
                            onChange={handleInputChange}
                            error={errors?.title}
                            icon="file-alt"
                            placeholder="Resource title"
                            required
                        />

                        <div>
                            <label htmlFor="type" className="block text-sm font-medium text-gray-700 mb-1 font-lato">
                                Resource Type <span className="text-red-500 ml-1">*</span>
                            </label>
                            <select
                                id="type"
                                name="type"
                                value={formData.type}
                                onChange={handleInputChange}
                                required
                                className={`block w-full px-3 py-2 border rounded-lg shadow-sm focus:ring-primary-500 focus:border-primary-500 focus:outline-none text-sm font-lato ${
                                    errors?.type ? 'border-red-500' : 'border-gray-300'
                                }`}
                            >
                                <option value="">Select type</option>
                                <option value="file">File</option>
                                <option value="link">Link</option>
                            </select>
                            {errors?.type && (
                                <p className="text-sm text-red-500 mt-1 font-lato">{errors.type}</p>
                            )}
                        </div>
                    </div>

                    {/* External Link */}
                    <Input
                        label="External Link"
                        name="external_link"
                        type="url"
                        value={formData.external_link}
                        onChange={handleInputChange}
                        error={errors?.external_link}
                        icon="link"
                        placeholder="https://example.com/resource"
                    />

                    {/* File Upload */}
                    <div className="space-y-3">
                        <label htmlFor="file_path" className="block text-sm font-medium text-gray-700">
                            Upload File
                        </label>
                        <input
                            id="file_path"
                            name="file_path"
                            type="file"
                            onChange={handleFileChange}
                            accept=".jpeg,.jpg,.png,.gif,.svg,.pdf,.mp4"
                            className={`block w-full text-sm text-gray-900 bg-gray-50 rounded-lg border focus:ring-2 focus:ring-primary focus:border-primary
                                file:mr-4 file:py-2 file:px-4 file:rounded-md file:border-0 file:text-sm file:font-medium
                                file:bg-primary file:text-white hover:file:bg-[#003366] transition ${
                                    errors?.file_path ? 'border-red-500' : 'border-gray-300'
                                }`}
                        />
                        {errors?.file_path && (
                            <p className="text-sm text-red-500 mt-1 font-lato">{errors.file_path}</p>
                        )}
                        <p className="text-xs text-gray-500 mt-2">
                            Allowed file types: JPEG, PNG, GIF, SVG, PDF, MP4. Max file size: 2MB.
                        </p>
                    </div>

                    {/* Description */}
                    <Textarea
                        label="Description"
                        name="description"
                        value={formData.description}
                        onChange={handleInputChange}
                        error={errors?.description}
                        placeholder="Brief description about this resource..."
                        rows={5}
                    />

                    {/* Downloadable Option */}
                    <div className="flex items-center gap-3 p-4 bg-primary-50 rounded-xl border border-primary-100">
                        <input
                            id="is_downloadable"
                            name="is_downloadable"
                            type="checkbox"
                            checked={formData.is_downloadable}
                            onChange={handleInputChange}
                            className="h-4 w-4 text-primary border-gray-300 rounded focus:ring-primary"
                        />
                        <label htmlFor="is_downloadable" className="text-sm text-gray-700 font-medium">
                            Make resource downloadable for attendees
                        </label>
                    </div>

                    {/* Submit Button */}
                    <div className="pt-6 border-t border-primary-100 mt-6 flex justify-end">
                        <button
                            type="submit"
                            disabled={isSubmitting}
                            className="inline-flex items-center gap-3 px-6 py-3 bg-primary text-white text-sm font-semibold rounded-xl hover:bg-primary-600 focus:outline-none focus:ring-2 focus:ring-primary focus:ring-offset-2 transition-all duration-200 transform hover:scale-105 disabled:opacity-50 disabled:cursor-not-allowed disabled:transform-none"
                        >
                            <i className="fas fa-save w-5 h-5"></i>
                            {isSubmitting ? 'Saving...' : 'Save Resource'}
                        </button>
                    </div>
                </form>
            </div>
        </DashboardLayout>
    );
}
