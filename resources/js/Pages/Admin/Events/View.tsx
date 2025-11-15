import { Head, Link, router, usePage } from '@inertiajs/react';
import DashboardLayout from '@/Layouts/DashboardLayout';
import { useState } from 'react';

interface Speaker {
    id: number;
    name: string;
    user: {
        name: string;
        email: string;
    };
}

interface Resource {
    id: number;
    title: string;
    description?: string;
    file_path: string;
    file_type: string;
}

interface Event {
    id: number;
    title: string;
    slug: string;
    description: string;
    mode: 'online' | 'offline' | 'hybrid';
    location?: string;
    physical_address?: string;
    start_date: string;
    end_date: string;
    is_active: boolean;
    program_cover?: string;
    contact_email?: string;
    max_attendees?: number;
    speakers: Speaker[];
    resources: Resource[];
}

interface ViewEventProps {
    event: Event;
    speakers: Speaker[];
}

export default function ViewEvent({ event, speakers }: ViewEventProps) {
    const { sideLinks } = usePage().props as any;
    const [showDeleteModal, setShowDeleteModal] = useState(false);
    const [isDeleting, setIsDeleting] = useState(false);

    const handleDelete = () => {
        setIsDeleting(true);
        router.delete(route('admin.events.destroy', event.id), {
            onFinish: () => {
                setIsDeleting(false);
                setShowDeleteModal(false);
            },
        });
    };

    const formatDate = (dateString: string) => {
        const date = new Date(dateString);
        return date.toLocaleDateString('en-US', {
            year: 'numeric',
            month: 'short',
            day: 'numeric',
            hour: 'numeric',
            minute: '2-digit',
        });
    };

    return (
        <DashboardLayout sideLinks={sideLinks}>
            <Head title={`${event.title} - Event Details`} />

            <div className="py-8">
                <div className="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                    {/* Header */}
                    <div className="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 mb-6">
                        <div className="flex items-center gap-4">
                            <Link
                                href={route('admin.events.index')}
                                className="text-primary hover:text-primary-600 transition-colors"
                            >
                                <i className="fas fa-arrow-left w-5 h-5"></i>
                            </Link>
                            <h1 className="text-xl font-bold text-primary font-montserrat">{event.title}</h1>
                        </div>

                        <div className="flex items-center gap-3">
                            <Link
                                href={route('admin.events.edit', event.slug)}
                                className="flex items-center whitespace-nowrap px-4 py-2 bg-white border border-gray-300 rounded-lg shadow-sm text-sm font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary transition-colors font-montserrat"
                            >
                                <i className="fas fa-edit w-4 h-4 mr-2"></i>
                                Edit Event
                            </Link>
                            <button
                                type="button"
                                onClick={() => setShowDeleteModal(true)}
                                className="flex whitespace-nowrap items-center px-4 py-2 bg-red-600 border border-transparent rounded-lg shadow-sm text-sm font-medium text-white hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500 transition-colors font-montserrat"
                            >
                                <i className="fas fa-trash w-4 h-4 mr-2"></i>
                                Delete Event
                            </button>
                        </div>
                    </div>

                    {/* Main content */}
                    <div className="grid grid-cols-1 lg:grid-cols-3 gap-8">
                        {/* Left column - Event details */}
                        <div className="lg:col-span-2 space-y-8">
                            {/* Event card */}
                            <div className="bg-white shadow rounded-lg overflow-hidden border border-gray-200">
                                {/* Event cover image */}
                                {event.program_cover && (
                                    <div className="h-48 bg-gray-100 overflow-hidden">
                                        <img
                                            src={`/storage/${event.program_cover}`}
                                            alt={event.title}
                                            className="w-full h-full object-cover"
                                        />
                                    </div>
                                )}

                                <div className="p-6">
                                    {/* Event status badges */}
                                    <div className="flex justify-between items-start mb-6">
                                        <span
                                            className={`inline-flex items-center px-3 py-1 rounded-full text-sm font-medium font-montserrat ${
                                                event.is_active
                                                    ? 'bg-green-100 text-green-800 border border-green-200'
                                                    : 'bg-gray-100 text-gray-800 border border-gray-200'
                                            }`}
                                        >
                                            {event.is_active ? 'Published' : 'Not Published'}
                                        </span>

                                        <span className="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-primary-100 text-primary-800 border border-primary-200 font-montserrat">
                                            {event.mode.charAt(0).toUpperCase() + event.mode.slice(1)}
                                        </span>
                                    </div>

                                    {/* Description */}
                                    <div className="prose max-w-none text-gray-600 mb-6 font-lato">
                                        <p>{event.description}</p>
                                    </div>

                                    {/* Details grid */}
                                    <div className="grid grid-cols-1 md:grid-cols-2 gap-6 border-t border-gray-200 pt-6 mt-6">
                                        <div className="flex items-start gap-3">
                                            <i className="fas fa-calendar w-5 h-5 text-primary mt-0.5 flex-shrink-0"></i>
                                            <div>
                                                <h3 className="text-sm font-medium text-gray-500 font-montserrat">
                                                    Dates
                                                </h3>
                                                <p className="text-sm text-gray-900 font-lato">
                                                    {formatDate(event.start_date)}
                                                    <span className="text-gray-500"> to</span>
                                                    <br />
                                                    {formatDate(event.end_date)}
                                                </p>
                                            </div>
                                        </div>

                                        {(event.mode === 'hybrid' || event.mode === 'offline') && (
                                            <div className="flex items-start gap-3">
                                                <i className="fas fa-map-marker-alt w-5 h-5 text-primary mt-0.5 flex-shrink-0"></i>
                                                <div>
                                                    <h3 className="text-sm font-medium text-gray-700 font-montserrat">
                                                        Location
                                                    </h3>
                                                    <p className="text-sm text-gray-900 font-lato">
                                                        {event.location || 'N/A'}
                                                        <br />
                                                        <span className="text-gray-500">
                                                            {event.physical_address || 'N/A'}
                                                        </span>
                                                    </p>
                                                </div>
                                            </div>
                                        )}

                                        {(event.mode === 'online' || event.mode === 'hybrid') && event.location && (
                                            <div className="flex items-start gap-3">
                                                <i className="fas fa-link w-5 h-5 text-primary mt-0.5 flex-shrink-0"></i>
                                                <div>
                                                    <h3 className="text-sm font-medium text-gray-700 font-montserrat">
                                                        Meeting Link
                                                    </h3>
                                                    <a
                                                        href={event.location}
                                                        target="_blank"
                                                        rel="noopener noreferrer"
                                                        className="text-sm text-primary hover:text-primary-600 transition-colors font-lato"
                                                    >
                                                        Event Link
                                                    </a>
                                                </div>
                                            </div>
                                        )}

                                        {event.contact_email && (
                                            <div className="flex items-start gap-3">
                                                <i className="fas fa-envelope w-5 h-5 text-primary mt-0.5 flex-shrink-0"></i>
                                                <div>
                                                    <h3 className="text-sm font-medium text-gray-700 font-montserrat">
                                                        Contact Email
                                                    </h3>
                                                    <p className="text-sm text-gray-900 font-lato">
                                                        {event.contact_email}
                                                    </p>
                                                </div>
                                            </div>
                                        )}

                                        {event.max_attendees && (
                                            <div className="flex items-start gap-3">
                                                <i className="fas fa-users w-5 h-5 text-primary mt-0.5 flex-shrink-0"></i>
                                                <div>
                                                    <h3 className="text-sm font-medium text-gray-700 font-montserrat">
                                                        Max Attendees
                                                    </h3>
                                                    <p className="text-sm text-gray-900 font-lato">
                                                        {event.max_attendees}
                                                    </p>
                                                </div>
                                            </div>
                                        )}
                                    </div>
                                </div>
                            </div>

                            {/* Resources Section */}
                            {event.resources && event.resources.length > 0 && (
                                <div className="bg-white shadow rounded-lg border border-gray-200 p-6">
                                    <h2 className="text-lg font-semibold text-gray-900 mb-4 font-montserrat">
                                        Event Resources
                                    </h2>
                                    <div className="space-y-3">
                                        {event.resources.map((resource) => (
                                            <div
                                                key={resource.id}
                                                className="flex items-center justify-between p-3 bg-gray-50 rounded-lg"
                                            >
                                                <div className="flex items-center gap-3">
                                                    <i className="fas fa-file w-4 h-4 text-gray-500"></i>
                                                    <div>
                                                        <p className="text-sm font-medium text-gray-900 font-montserrat">
                                                            {resource.title}
                                                        </p>
                                                        {resource.description && (
                                                            <p className="text-xs text-gray-500 font-lato">
                                                                {resource.description}
                                                            </p>
                                                        )}
                                                    </div>
                                                </div>
                                                <a
                                                    href={`/storage/${resource.file_path}`}
                                                    target="_blank"
                                                    rel="noopener noreferrer"
                                                    className="text-primary hover:text-primary-600 text-sm font-medium font-montserrat"
                                                >
                                                    Download
                                                </a>
                                            </div>
                                        ))}
                                    </div>
                                </div>
                            )}
                        </div>

                        {/* Right column - Speakers */}
                        <div className="space-y-6">
                            <div className="bg-white shadow rounded-lg border border-gray-200 p-6">
                                <h2 className="text-lg font-semibold text-gray-900 mb-4 font-montserrat">
                                    Event Speakers ({event.speakers.length})
                                </h2>
                                {event.speakers.length > 0 ? (
                                    <div className="space-y-3">
                                        {event.speakers.map((speaker) => (
                                            <div key={speaker.id} className="flex items-center gap-3 p-3 bg-gray-50 rounded-lg">
                                                <div className="w-10 h-10 bg-primary-100 rounded-full flex items-center justify-center flex-shrink-0">
                                                    <span className="text-sm font-semibold text-primary-700 font-montserrat">
                                                        {speaker.user.name.charAt(0).toUpperCase()}
                                                    </span>
                                                </div>
                                                <div className="flex-1 min-w-0">
                                                    <p className="text-sm font-medium text-gray-900 truncate font-montserrat">
                                                        {speaker.user.name}
                                                    </p>
                                                    <p className="text-xs text-gray-500 truncate font-lato">
                                                        {speaker.user.email}
                                                    </p>
                                                </div>
                                            </div>
                                        ))}
                                    </div>
                                ) : (
                                    <p className="text-sm text-gray-500 text-center py-4 font-lato">
                                        No speakers assigned yet
                                    </p>
                                )}
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {/* Delete Modal */}
            {showDeleteModal && (
                <div className="fixed inset-0 z-50 flex items-center justify-center bg-black/30 backdrop-blur-sm">
                    <div className="relative bg-white rounded-xl shadow-xl p-6 w-full max-w-md mx-4 border border-gray-200">
                        <button
                            type="button"
                            onClick={() => setShowDeleteModal(false)}
                            className="absolute top-3 right-3 text-gray-400 hover:bg-gray-200 hover:text-gray-900 rounded-lg text-sm w-8 h-8 inline-flex justify-center items-center"
                        >
                            <i className="fas fa-times w-3 h-3"></i>
                        </button>

                        <div className="text-center mb-6">
                            <i className="fas fa-exclamation-triangle w-12 h-12 text-red-600 mx-auto mb-4"></i>
                            <h3 className="text-lg font-semibold text-gray-800 font-montserrat">Delete Event</h3>
                        </div>

                        <p className="text-gray-700 mb-6 font-lato">
                            Are you sure you want to delete <span className="font-semibold">{event.title}</span>? This
                            action cannot be undone.
                        </p>

                        <div className="flex justify-end gap-3">
                            <button
                                onClick={() => setShowDeleteModal(false)}
                                className="py-2.5 px-5 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-lg hover:bg-gray-100 transition-colors font-lato"
                            >
                                Cancel
                            </button>
                            <button
                                onClick={handleDelete}
                                disabled={isDeleting}
                                className="px-5 py-2.5 text-sm font-medium text-white bg-red-600 hover:bg-red-700 rounded-lg transition-colors inline-flex items-center font-montserrat disabled:opacity-50"
                            >
                                {isDeleting ? (
                                    <>
                                        <i className="fas fa-spinner fa-spin w-4 h-4 mr-2"></i>
                                        Deleting...
                                    </>
                                ) : (
                                    <>
                                        <i className="fas fa-trash w-4 h-4 mr-2"></i>
                                        Delete Event
                                    </>
                                )}
                            </button>
                        </div>
                    </div>
                </div>
            )}
        </DashboardLayout>
    );
}
