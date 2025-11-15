import { useState, FormEvent, ChangeEvent } from 'react';
import { Head, Link, router, usePage } from '@inertiajs/react';
import DashboardLayout from '@/Layouts/DashboardLayout';
import Input from '@/Components/Input';
import Textarea from '@/Components/Textarea';
import RichTextEditor from '@/Components/RichTextEditor';

interface Event {
    id: number;
    title: string;
    mode: 'online' | 'offline' | 'hybrid';
    attendee_slots: number;
    theme?: string;
    start_date: string;
    end_date: string;
    location?: string;
    physical_address?: string;
    contact_email?: string;
    entry_fee: number;
    description?: string;
    program_cover?: string;
    is_active: boolean;
    is_published: boolean;
    is_allowing_application: boolean;
    is_featured: boolean;
    creator_id: number;
}

interface Props {
    event: Event;
}

interface FormData {
    title: string;
    mode: 'online' | 'offline' | 'hybrid';
    attendee_slots: string;
    theme: string;
    start_date: string;
    end_date: string;
    location: string;
    physical_address: string;
    contact_email: string;
    entry_fee: string;
    description: string;
    is_active: boolean;
    is_published: boolean;
    is_allowing_application: boolean;
    is_featured: boolean;
}

export default function EditEvent({ event }: Props) {
    const { sideLinks } = usePage().props as any;
    const [isSubmitting, setIsSubmitting] = useState(false);
    const [formData, setFormData] = useState<FormData>({
        title: event.title || '',
        mode: event.mode || 'online',
        attendee_slots: event.attendee_slots?.toString() || '',
        theme: event.theme || '',
        start_date: event.start_date ? new Date(event.start_date).toISOString().slice(0, 16) : '',
        end_date: event.end_date ? new Date(event.end_date).toISOString().slice(0, 16) : '',
        location: event.location || '',
        physical_address: event.physical_address || '',
        contact_email: event.contact_email || '',
        entry_fee: event.entry_fee?.toString() || '0',
        description: event.description || '',
        is_active: event.is_active || false,
        is_published: event.is_published || false,
        is_allowing_application: event.is_allowing_application || false,
        is_featured: event.is_featured || false,
    });
    const [programCover, setProgramCover] = useState<File | null>(null);

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
            setProgramCover(e.target.files[0]);
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
                // Only append non-empty values
                data.append(key, value);
            }
        });

        // Add creator_id (preserve original creator)
        data.append('creator_id', event.creator_id.toString());

        // Append file if selected
        if (programCover) {
            data.append('program_cover', programCover);
        }

        // Add _method for PUT request
        data.append('_method', 'PUT');

        router.post(route('admin.events.update', event.id), data, {
            preserveScroll: true,
            onFinish: () => {
                setIsSubmitting(false);
            },
        });
    };

    const shouldShowLocation = formData.mode === 'online' || formData.mode === 'hybrid';
    const shouldShowPhysicalAddress = formData.mode === 'offline' || formData.mode === 'hybrid';

    return (
        <DashboardLayout sideLinks={sideLinks}>
            <Head title="Update Event" />

            <div className="p-6">
                {/* Page Header */}
                <div className="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 mb-8">
                    <div className="flex items-center gap-3">
                        <div>
                            <h1 className="text-2xl font-extrabold text-gray-900 font-montserrat">Update Event</h1>
                            <p className="text-sm text-gray-500 mt-1">Edit event details and manage its settings.</p>
                        </div>
                    </div>

                    <Link
                        href={route('admin.events.index')}
                        className="inline-flex items-center px-4 py-2 border border-gray-300 rounded-lg text-sm font-medium bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary transition shadow-sm"
                    >
                        <i className="fas fa-arrow-left w-4 h-4 mr-2"></i>
                        Back to Events
                    </Link>
                </div>

                {/* Form Card */}
                <div className="bg-white border border-gray-100 rounded-xl shadow-sm p-6 md:p-8">
                    <form onSubmit={handleSubmit} className="space-y-8">
                        {/* Section: Basic Event Info */}
                        <div>
                            <h3 className="text-lg font-semibold text-gray-900 mb-4 pb-2 border-b border-gray-200">
                                Event Basics
                            </h3>
                            <div className="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <Input
                                    label="Event Title"
                                    name="title"
                                    value={formData.title}
                                    onChange={handleInputChange}
                                    required
                                />

                                <div>
                                    <label htmlFor="mode" className="block text-sm font-medium text-gray-700 mb-2">
                                        Event Mode <span className="text-red-500">*</span>
                                    </label>
                                    <select
                                        id="mode"
                                        name="mode"
                                        value={formData.mode}
                                        onChange={handleInputChange}
                                        required
                                        className="block w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-primary focus:border-primary"
                                    >
                                        <option value="online">Online</option>
                                        <option value="offline">Offline</option>
                                        <option value="hybrid">Hybrid</option>
                                    </select>
                                </div>

                                <Input
                                    label="Attendee Slots"
                                    name="attendee_slots"
                                    type="number"
                                    min="1"
                                    value={formData.attendee_slots}
                                    onChange={handleInputChange}
                                    placeholder="Enter no. of available slots"
                                />

                                <div className="md:col-span-2">
                                    <Input
                                        label="Event Theme"
                                        name="theme"
                                        value={formData.theme}
                                        onChange={handleInputChange}
                                        required
                                    />
                                </div>
                            </div>
                        </div>

                        {/* Section: Date & Location */}
                        <div>
                            <h3 className="text-lg font-semibold text-gray-900 mb-4 pb-2 border-b border-gray-200">
                                Date & Location
                            </h3>
                            <div className="grid grid-cols-1 sm:grid-cols-2 gap-6">
                                <Input
                                    label="Start Date & Time"
                                    name="start_date"
                                    type="datetime-local"
                                    value={formData.start_date}
                                    onChange={handleInputChange}
                                    required
                                />

                                <Input
                                    label="End Date & Time"
                                    name="end_date"
                                    type="datetime-local"
                                    value={formData.end_date}
                                    onChange={handleInputChange}
                                    required
                                />

                                {shouldShowLocation && (
                                    <Input
                                        label="Location (Meeting Link)"
                                        name="location"
                                        value={formData.location}
                                        onChange={handleInputChange}
                                        placeholder="Paste meeting link"
                                    />
                                )}

                                {shouldShowPhysicalAddress && (
                                    <Input
                                        label="Full Physical Address (if offline/hybrid)"
                                        name="physical_address"
                                        value={formData.physical_address}
                                        onChange={handleInputChange}
                                        placeholder="Street, City, State, Country"
                                    />
                                )}
                            </div>
                        </div>

                        {/* Section: Contact & Pricing */}
                        <div>
                            <h3 className="text-lg font-semibold text-gray-900 mb-4 pb-2 border-b border-gray-200">
                                Contact & Pricing
                            </h3>
                            <div className="grid grid-cols-1 sm:grid-cols-2 gap-6">
                                <Input
                                    label="Contact Email"
                                    name="contact_email"
                                    type="email"
                                    value={formData.contact_email}
                                    onChange={handleInputChange}
                                    placeholder="organizer@event.com"
                                />

                                <Input
                                    label="Entry Fee (₦)"
                                    name="entry_fee"
                                    type="number"
                                    min="0"
                                    step="0.01"
                                    value={formData.entry_fee}
                                    onChange={handleInputChange}
                                    placeholder="0.00"
                                />
                            </div>
                        </div>

                        {/* Section: Media */}
                        <div>
                            <h3 className="text-lg font-semibold text-gray-900 mb-4 pb-2 border-b border-gray-200">
                                Cover Image
                            </h3>
                            <div>
                                <label htmlFor="program_cover" className="block text-sm font-medium text-gray-700 mb-2">
                                    Upload New Cover Image (JPG/PNG)
                                </label>
                                <input
                                    type="file"
                                    name="program_cover"
                                    id="program_cover"
                                    accept=".jpg,.jpeg,.png,.JPG,.PNG"
                                    onChange={handleFileChange}
                                    className="block w-full text-sm text-gray-900 bg-gray-50 rounded-lg border border-gray-300 focus:ring-2 focus:ring-primary focus:border-primary
                                        file:mr-4 file:py-2 file:px-4 file:rounded-md file:border-0 file:text-sm file:font-medium
                                        file:bg-primary file:text-white hover:file:bg-[#003366] transition"
                                />
                                <p className="mt-2 text-xs text-gray-500">Recommended size: 1200x600px. Max file size: 5MB.</p>

                                {event.program_cover && (
                                    <div className="mt-4 p-3 bg-gray-50 rounded-lg border border-dashed border-gray-300">
                                        <p className="text-sm font-medium text-gray-700 mb-2">Current Cover Image:</p>
                                        <div className="flex items-center gap-3">
                                            <img
                                                src={`/storage/${event.program_cover}`}
                                                alt="Event Cover"
                                                className="h-16 w-24 object-cover rounded border"
                                            />
                                            <a
                                                href={`/storage/${event.program_cover}`}
                                                target="_blank"
                                                rel="noopener noreferrer"
                                                className="text-sm text-primary font-medium hover:underline flex items-center"
                                            >
                                                <i className="fas fa-eye w-4 h-4 mr-1"></i>
                                                View Image
                                            </a>
                                        </div>
                                    </div>
                                )}
                            </div>
                        </div>

                        {/* Section: Description */}
                        <div>
                            <h3 className="text-lg font-semibold text-gray-900 mb-4 pb-2 border-b border-gray-200">
                                Event Description
                            </h3>
                            <RichTextEditor
                                label="Description"
                                value={formData.description}
                                onChange={(value) => setFormData(prev => ({ ...prev, description: value }))}
                                placeholder="Describe your event, audience, highlights, and what attendees can expect..."
                                required
                            />
                        </div>

                        {/* Section: Options */}
                        <div>
                            <h3 className="text-lg font-semibold text-gray-900 mb-4 pb-2 border-b border-gray-200">
                                Event Options
                            </h3>
                            <div className="flex flex-wrap items-center gap-6 p-4 bg-gray-50 rounded-lg">
                                <label className="inline-flex items-center">
                                    <input
                                        type="checkbox"
                                        name="is_active"
                                        checked={formData.is_active}
                                        onChange={handleInputChange}
                                        className="rounded border-gray-300 text-primary focus:ring-primary"
                                    />
                                    <span className="ml-2 text-sm font-medium text-gray-700">Active</span>
                                </label>

                                <label className="inline-flex items-center">
                                    <input
                                        type="checkbox"
                                        name="is_published"
                                        checked={formData.is_published}
                                        onChange={handleInputChange}
                                        className="rounded border-gray-300 text-primary focus:ring-primary"
                                    />
                                    <span className="ml-2 text-sm font-medium text-gray-700">Published</span>
                                </label>

                                <label className="inline-flex items-center">
                                    <input
                                        type="checkbox"
                                        name="is_allowing_application"
                                        checked={formData.is_allowing_application}
                                        onChange={handleInputChange}
                                        className="rounded border-gray-300 text-primary focus:ring-primary"
                                    />
                                    <span className="ml-2 text-sm font-medium text-gray-700">Allow Speaker Applications</span>
                                </label>

                                <label className="inline-flex items-center">
                                    <input
                                        type="checkbox"
                                        name="is_featured"
                                        checked={formData.is_featured}
                                        onChange={handleInputChange}
                                        className="rounded border-gray-300 text-primary focus:ring-primary"
                                    />
                                    <span className="ml-2 text-sm font-medium text-gray-700">Is featured</span>
                                </label>
                            </div>
                            <p className="mt-2 text-xs text-gray-500">
                                Active — event is enabled in the admin dashboard. Published — event is visible to the public.
                                Enable speaker applications to accept submissions; manage them from the Applications section.
                                Is featured — highlights this event in featured lists and homepage sections to increase visibility.
                            </p>
                        </div>

                        {/* Submit Button */}
                        <div className="pt-6 border-t border-gray-200 flex flex-col sm:flex-row justify-end gap-3">
                            <Link
                                href={route('admin.events.index')}
                                className="px-5 py-2.5 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-lg hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-300 transition text-center"
                            >
                                Cancel
                            </Link>
                            <button
                                type="submit"
                                disabled={isSubmitting}
                                className="px-6 py-2.5 inline-flex items-center justify-center text-sm font-medium text-white bg-accent rounded-lg hover:bg-[#008f47] focus:ring-4 focus:ring-accent focus:outline-none transition disabled:opacity-50 disabled:cursor-not-allowed"
                            >
                                <i className="fas fa-save w-4 h-4 mr-2"></i>
                                {isSubmitting ? 'Updating...' : 'Update Event'}
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </DashboardLayout>
    );
}
