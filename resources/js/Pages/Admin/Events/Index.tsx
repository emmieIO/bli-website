import { Head, Link, router, usePage } from '@inertiajs/react';
import DashboardLayout from '@/Layouts/DashboardLayout';
import { useState } from 'react';

interface Event {
    id: number;
    title: string;
    slug: string;
    location?: string;
    start_date: string;
    end_date: string;
    is_published: boolean;
    program_cover?: string;
    speakers_count?: number;
}

interface EventsProps {
    events: {
        data: Event[];
        links?: any;
        meta?: any;
    };
}

export default function EventsIndex({ events }: EventsProps) {
    const { sideLinks } = usePage().props as any;
    const [selectedEvents, setSelectedEvents] = useState<number[]>([]);
    const currentStatus = new URLSearchParams(window.location.search).get('status') || 'all';

    const eventsList = events.data || [];

    const handleSelectAll = (checked: boolean) => {
        if (checked) {
            setSelectedEvents(eventsList.map((e) => e.id));
        } else {
            setSelectedEvents([]);
        }
    };

    const handleSelectEvent = (eventId: number, checked: boolean) => {
        if (checked) {
            setSelectedEvents([...selectedEvents, eventId]);
        } else {
            setSelectedEvents(selectedEvents.filter((id) => id !== eventId));
        }
    };

    const getEventStatus = (event: Event) => {
        const now = new Date();
        const startDate = new Date(event.start_date);
        const endDate = new Date(event.end_date);

        if (!event.is_published) return 'draft';
        if (endDate < now) return 'ended';
        if (startDate > now) return 'upcoming';
        return 'ongoing';
    };

    const getStatusBadge = (status: string) => {
        const badges = {
            draft: 'bg-gray-100 text-gray-800',
            ended: 'bg-gray-100 text-gray-800',
            upcoming: 'bg-green-100 text-green-800',
            ongoing: 'bg-blue-100 text-blue-800',
        };
        const labels = {
            draft: 'Draft',
            ended: 'Event Ended',
            upcoming: 'Upcoming',
            ongoing: 'Ongoing',
        };
        return { class: badges[status as keyof typeof badges], label: labels[status as keyof typeof labels] };
    };

    const formatDate = (dateString: string) => {
        return new Date(dateString).toLocaleDateString('en-US', {
            year: 'numeric',
            month: 'short',
            day: 'numeric',
        });
    };

    return (
        <DashboardLayout sideLinks={sideLinks}>
            <Head title="Events Management - Beacon Leadership Institute" />

            {/* Page Header */}
            <div className="flex flex-col md:flex-row justify-between items-start md:items-center gap-4 mb-6">
                <div>
                    <h1 className="text-2xl font-bold text-primary font-montserrat">Events Management</h1>
                    <p className="text-sm text-gray-500 font-lato">Organize and manage all your conference events</p>
                </div>
            </div>

            {/* Status Tabs */}
            <div className="mb-6 border-b border-gray-200">
                <nav className="-mb-px flex space-x-8 overflow-x-auto">
                    <TabLink label="All Events" href={route('admin.events.index') + '?status=all'} isActive={currentStatus === 'all'} />
                    <TabLink label="Upcoming" href={route('admin.events.index') + '?status=upcoming'} isActive={currentStatus === 'upcoming'} />
                    <TabLink label="Past Events" href={route('admin.events.index') + '?status=past'} isActive={currentStatus === 'past'} />
                    <TabLink label="Drafts" href={route('admin.events.index') + '?status=draft'} isActive={currentStatus === 'draft'} />
                    <Link
                        href={route('admin.events.create')}
                        className="inline-flex items-center gap-2 px-4 py-2 border-b-2 border-transparent text-sm font-medium text-gray-500 hover:text-primary hover:border-primary transition-colors font-montserrat"
                    >
                        <i className="fas fa-plus w-4 h-4"></i>
                        Create Event
                    </Link>
                </nav>
            </div>

            {/* Mass Actions Bar */}
            {selectedEvents.length > 0 && (
                <div className="bg-primary-50 px-6 py-3 border-b border-primary-200 rounded-t-lg mb-0">
                    <div className="flex items-center justify-between">
                        <p className="text-sm text-primary-700 font-lato">{selectedEvents.length} events selected</p>
                        <div className="flex gap-3">
                            <button className="text-green-600 hover:text-green-700 text-sm font-medium inline-flex items-center gap-2 font-lato">
                                <i className="fas fa-paper-plane w-4 h-4"></i> Publish Selected
                            </button>
                            <button className="text-primary hover:text-primary-600 text-sm font-medium inline-flex items-center gap-2 font-lato">
                                <i className="fas fa-archive w-4 h-4"></i> Archive Selected
                            </button>
                            <button className="text-red-600 hover:text-red-700 text-sm font-medium inline-flex items-center gap-2 font-lato">
                                <i className="fas fa-trash w-4 h-4"></i> Delete Selected
                            </button>
                        </div>
                    </div>
                </div>
            )}

            {/* Events Table */}
            <div className="bg-white rounded-lg shadow border border-primary-100 overflow-x-auto">
                <table className="min-w-full divide-y divide-primary-100">
                    <thead className="bg-primary-50">
                        <tr>
                            <th className="px-6 py-3 text-left text-xs font-semibold text-primary uppercase tracking-wider font-montserrat w-4">
                                <input
                                    type="checkbox"
                                    checked={selectedEvents.length === eventsList.length && eventsList.length > 0}
                                    onChange={(e) => handleSelectAll(e.target.checked)}
                                    className="rounded border-gray-300 text-primary focus:ring-primary"
                                />
                            </th>
                            <th className="px-6 py-3 text-left text-xs font-semibold text-primary uppercase tracking-wider font-montserrat">
                                Event
                            </th>
                            <th className="px-6 py-3 text-left text-xs font-semibold text-primary uppercase tracking-wider font-montserrat">
                                Date & Time
                            </th>
                            <th className="px-6 py-3 text-left text-xs font-semibold text-primary uppercase tracking-wider font-montserrat">
                                Status
                            </th>
                            <th className="px-6 py-3 text-left text-xs font-semibold text-primary uppercase tracking-wider font-montserrat">
                                Speakers
                            </th>
                            <th className="px-6 py-3 text-right text-xs font-semibold text-primary uppercase tracking-wider font-montserrat">
                                Actions
                            </th>
                        </tr>
                    </thead>
                    <tbody className="bg-white divide-y divide-primary-100">
                        {eventsList.length > 0 ? (
                            eventsList.map((event) => {
                                const status = getEventStatus(event);
                                const badge = getStatusBadge(status);
                                return (
                                    <tr key={event.id} className="hover:bg-primary-50 transition-colors">
                                        <td className="px-6 py-4">
                                            <input
                                                type="checkbox"
                                                checked={selectedEvents.includes(event.id)}
                                                onChange={(e) => handleSelectEvent(event.id, e.target.checked)}
                                                className="rounded border-gray-300 text-primary focus:ring-primary"
                                            />
                                        </td>
                                        <td className="px-6 py-4">
                                            <div className="flex items-center gap-3">
                                                {event.program_cover && (
                                                    <img
                                                        className="h-10 w-10 rounded-md object-cover"
                                                        src={`/storage/${event.program_cover}`}
                                                        alt={event.title}
                                                        onError={(e) => {
                                                            (e.target as HTMLImageElement).src = 'https://placehold.co/600x400?text=Cover+Image+Missing';
                                                        }}
                                                    />
                                                )}
                                                <div>
                                                    <div className="font-bold text-sm text-primary font-montserrat">
                                                        {event.title}
                                                    </div>
                                                    <div className="text-sm text-gray-500 font-lato">
                                                        {event.location || 'Online Event'}
                                                    </div>
                                                </div>
                                            </div>
                                        </td>
                                        <td className="px-6 py-4 whitespace-nowrap">
                                            <div className="text-sm text-primary font-montserrat">
                                                {formatDate(event.start_date)}
                                            </div>
                                            <div className="text-xs text-gray-500 font-lato">
                                                {formatDate(event.start_date)} - {formatDate(event.end_date)}
                                            </div>
                                        </td>
                                        <td className="px-6 py-4 whitespace-nowrap">
                                            <span
                                                className={`px-2 py-1 inline-flex text-xs font-semibold rounded-full ${badge.class} font-montserrat`}
                                            >
                                                {badge.label}
                                            </span>
                                        </td>
                                        <td className="px-6 py-4 whitespace-nowrap">
                                            <span className="text-sm text-gray-700 font-lato">
                                                {event.speakers_count || 0} speakers
                                            </span>
                                        </td>
                                        <td className="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                            <div className="flex items-center justify-end gap-2">
                                                <Link
                                                    href={route('admin.events.show', event.slug)}
                                                    className="text-primary hover:text-primary-600 transition-colors"
                                                    title="View"
                                                >
                                                    <i className="fas fa-eye w-4 h-4"></i>
                                                </Link>
                                                <Link
                                                    href={route('admin.events.edit', event.slug)}
                                                    className="text-blue-600 hover:text-blue-700 transition-colors"
                                                    title="Edit"
                                                >
                                                    <i className="fas fa-edit w-4 h-4"></i>
                                                </Link>
                                                <button
                                                    onClick={() => {
                                                        if (confirm(`Delete event "${event.title}"?`)) {
                                                            router.delete(route('admin.events.destroy', event.id));
                                                        }
                                                    }}
                                                    className="text-red-600 hover:text-red-700 transition-colors"
                                                    title="Delete"
                                                >
                                                    <i className="fas fa-trash w-4 h-4"></i>
                                                </button>
                                            </div>
                                        </td>
                                    </tr>
                                );
                            })
                        ) : (
                            <tr>
                                <td colSpan={6} className="px-6 py-12 text-center text-gray-500">
                                    <div className="flex flex-col items-center">
                                        <i className="fas fa-calendar-times w-12 h-12 text-gray-300 mb-4"></i>
                                        <p className="font-lato">No events found</p>
                                    </div>
                                </td>
                            </tr>
                        )}
                    </tbody>
                </table>
            </div>
        </DashboardLayout>
    );
}

function TabLink({ label, href, isActive }: { label: string; href: string; isActive: boolean }) {
    return (
        <Link
            href={href}
            className={`inline-flex items-center px-1 py-4 border-b-2 text-sm font-medium transition-colors font-montserrat ${
                isActive
                    ? 'border-primary text-primary'
                    : 'border-transparent text-gray-500 hover:text-primary hover:border-primary'
            }`}
        >
            {label}
        </Link>
    );
}
