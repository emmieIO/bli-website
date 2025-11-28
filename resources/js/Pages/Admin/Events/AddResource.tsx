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

        router.post(route('admin.events.resources.store', event.slug), data, {
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
                            <div className="relative">
                                <div className="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                    <i className="fas fa-layer-group text-gray-400"></i>
                                </div>
                                <select
                                    id="type"
                                    name="type"
                                    value={formData.type}
                                    onChange={handleInputChange}
                                    required
                                    className={`block w-full pl-10 pr-10 py-3 border rounded-xl shadow-sm focus:ring-2 focus:outline-none text-sm font-lato bg-gray-50 focus:bg-white transition-all ${
                                        errors?.type ? 'border-red-500 focus:ring-red-500' : 'border-gray-300 focus:ring-primary focus:border-primary'
                                    }`}
                                    style={errors?.type ? {} : { '--tw-ring-color': '#002147' } as any}
                                >
                                    <option value="">Select resource type</option>
                                    <option value="file">📎 File Upload</option>
                                    <option value="link">🔗 External Link</option>
                                </select>
                            </div>
                            {errors?.type && (
                                <p className="text-sm text-red-500 mt-1 font-lato">{errors.type}</p>
                            )}
                        </div>
                    </div>

                    {/* Conditional Input Based on Type */}
                    {formData.type && (
                        <div className="border-2 border-dashed rounded-xl p-6 transition-all duration-300" style={{ borderColor: formData.type === 'file' ? '#002147' : '#00a651' }}>
                            {formData.type === 'link' ? (
                                <div className="space-y-3">
                                    <div className="flex items-center gap-2 mb-4">
                                        <div className="w-10 h-10 rounded-lg flex items-center justify-center" style={{ backgroundColor: '#e6f7ed' }}>
                                            <i className="fas fa-link text-lg" style={{ color: '#00a651' }}></i>
                                        </div>
                                        <div>
                                            <h3 className="text-sm font-semibold text-gray-900 font-montserrat">External Link</h3>
                                            <p className="text-xs text-gray-500 font-lato">Provide a URL to an external resource</p>
                                        </div>
                                    </div>
                                    <Input
                                        label="Resource URL"
                                        name="external_link"
                                        type="url"
                                        value={formData.external_link}
                                        onChange={handleInputChange}
                                        error={errors?.external_link}
                                        icon="link"
                                        placeholder="https://example.com/resource"
                                        required
                                    />
                                </div>
                            ) : (
                                <div className="space-y-3">
                                    <div className="flex items-center gap-2 mb-4">
                                        <div className="w-10 h-10 rounded-lg flex items-center justify-center" style={{ backgroundColor: '#e6f0ff' }}>
                                            <i className="fas fa-cloud-upload-alt text-lg" style={{ color: '#002147' }}></i>
                                        </div>
                                        <div>
                                            <h3 className="text-sm font-semibold text-gray-900 font-montserrat">File Upload</h3>
                                            <p className="text-xs text-gray-500 font-lato">Upload a file from your computer</p>
                                        </div>
                                    </div>
                                    <div>
                                        <label htmlFor="file_path" className="block text-sm font-medium text-gray-700 mb-2 font-lato">
                                            Choose File <span className="text-red-500">*</span>
                                        </label>
                                        <div className="relative">
                                            <style>{`
                                                input[type="file"]::file-selector-button {
                                                    background-color: #002147;
                                                }
                                                input[type="file"]::-webkit-file-upload-button {
                                                    background-color: #002147;
                                                }
                                            `}</style>
                                            <input
                                                id="file_path"
                                                name="file_path"
                                                type="file"
                                                onChange={handleFileChange}
                                                required={formData.type === 'file'}
                                                accept=".jpeg,.jpg,.png,.gif,.svg,.pdf,.mp4,.doc,.docx,.xls,.xlsx,.ppt,.pptx,.zip"
                                                className={`block w-full text-sm text-gray-900 bg-white rounded-xl border-2 border-dashed focus:ring-2 focus:outline-none p-4
                                                    file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-sm file:font-semibold
                                                    file:text-white hover:file:opacity-90 file:transition cursor-pointer hover:border-primary transition-all ${
                                                        errors?.file_path ? 'border-red-500 focus:ring-red-500' : 'border-gray-300 focus:ring-primary'
                                                    }`}
                                                style={{ '--tw-ring-color': '#002147' } as any}
                                            />
                                            {file && (
                                                <div className="mt-3 flex items-center gap-2 text-sm text-gray-700 bg-gray-50 p-3 rounded-lg">
                                                    <i className="fas fa-file text-primary"></i>
                                                    <span className="font-medium font-lato">{file.name}</span>
                                                    <span className="text-gray-500 font-lato">({(file.size / 1024).toFixed(2)} KB)</span>
                                                </div>
                                            )}
                                        </div>
                                        {errors?.file_path && (
                                            <p className="text-sm text-red-500 mt-2 font-lato">{errors.file_path}</p>
                                        )}
                                        <div className="mt-3 bg-blue-50 border-l-4 p-3 rounded-r-lg" style={{ borderColor: '#002147' }}>
                                            <p className="text-xs text-gray-600 font-lato">
                                                <i className="fas fa-info-circle mr-1" style={{ color: '#002147' }}></i>
                                                <strong>Allowed formats:</strong> Images (JPEG, PNG, GIF, SVG), Documents (PDF, DOC, DOCX, XLS, XLSX, PPT, PPTX), Archives (ZIP), Videos (MP4)
                                            </p>
                                            <p className="text-xs text-gray-600 font-lato mt-1">
                                                <strong>Max file size:</strong> 10MB
                                            </p>
                                        </div>
                                    </div>
                                </div>
                            )}
                        </div>
                    )}

                    {/* Show message when no type selected */}
                    {!formData.type && (
                        <div className="bg-gray-50 border-2 border-dashed border-gray-300 rounded-xl p-8 text-center">
                            <i className="fas fa-hand-pointer text-4xl text-gray-400 mb-3"></i>
                            <p className="text-gray-500 font-lato">
                                Please select a resource type above to continue
                            </p>
                        </div>
                    )}

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
                    <div className="bg-linear-to-r from-blue-50 to-green-50 rounded-xl p-6 border-2 transition-all duration-300" style={{ borderColor: formData.is_downloadable ? '#00a651' : '#e5e7eb' }}>
                        <div className="flex items-start gap-4">
                            <div className="flex items-center h-5">
                                <input
                                    id="is_downloadable"
                                    name="is_downloadable"
                                    type="checkbox"
                                    checked={formData.is_downloadable}
                                    onChange={handleInputChange}
                                    className="h-5 w-5 rounded-lg border-2 border-gray-300 transition-all duration-200 cursor-pointer focus:ring-2 focus:ring-offset-2"
                                    style={{
                                        accentColor: '#00a651',
                                        '--tw-ring-color': '#00a651'
                                    } as any}
                                />
                            </div>
                            <div className="flex-1">
                                <label htmlFor="is_downloadable" className="block text-sm font-semibold text-gray-900 mb-1 font-montserrat cursor-pointer">
                                    <i className="fas fa-download mr-2" style={{ color: formData.is_downloadable ? '#00a651' : '#6b7280' }}></i>
                                    Make Resource Downloadable for Attendees
                                </label>
                                <p className="text-xs text-gray-600 font-lato leading-relaxed">
                                    {formData.is_downloadable ? (
                                        <span className="text-green-700 font-medium">
                                            ✓ This resource will be available for download on the public event page
                                        </span>
                                    ) : (
                                        <span>
                                            When enabled, registered attendees will be able to download this resource from the event page
                                        </span>
                                    )}
                                </p>
                            </div>
                        </div>
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
