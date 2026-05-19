import { Head, Link, router, usePage } from '@inertiajs/react';
import DashboardLayout from '@/Layouts/DashboardLayout';
import { Star, Trash2, Search } from 'lucide-react';
import type { ReactNode } from 'react';
import { useState, FormEvent } from 'react';

interface User {
    id: number;
    name: string;
}

interface Course {
    id: number;
    title: string;
}

interface Rating {
    id: number;
    rating: number;
    review: string | null;
    instructor: User;
    user: User;
    course: Course | null;
    created_at: string;
}

interface Stats {
    total: number;
    average_rating: number;
    five_star: number;
    one_star: number;
}

interface RatingsProps {
    ratings: {
        data: Rating[];
        links?: any;
        meta?: any;
    };
    instructors: User[];
    stats: Stats;
    filters: {
        instructor_id?: number;
        rating?: number;
        search?: string;
    };
}

export default function InstructorRatingsIndex({ ratings, instructors, stats, filters }: RatingsProps) {
    const { sideLinks } = usePage().props as any;
    const ratingsList = ratings.data || [];

    const [instructorId, setInstructorId] = useState(filters.instructor_id || '');
    const [ratingFilter, setRatingFilter] = useState(filters.rating || '');
    const [search, setSearch] = useState(filters.search || '');

    const handleDelete = (ratingId: number) => {
        if (confirm('Are you sure you want to delete this rating?')) {
            router.delete(route('admin.ratings.destroy', ratingId), {
                preserveScroll: true,
            });
        }
    };

    const handleFilter = (e: FormEvent) => {
        e.preventDefault();
        router.get(route('admin.ratings.index'), {
            instructor_id: instructorId || undefined,
            rating: ratingFilter || undefined,
            search: search || undefined,
        }, {
            preserveState: true,
            preserveScroll: true,
        });
    };

    const clearFilters = () => {
        setInstructorId('');
        setRatingFilter('');
        setSearch('');
        router.get(route('admin.ratings.index'));
    };

    const formatDate = (dateString: string) => {
        return new Date(dateString).toLocaleDateString('en-US', {
            year: 'numeric',
            month: 'short',
            day: 'numeric',
        });
    };

    const renderStars = (rating: number) => {
        return (
            <div className="flex items-center gap-1">
                {[1, 2, 3, 4, 5].map((star) => (
                    <Star
                        key={star}
                        size={16}
                        className={star <= rating ? 'fill-yellow-400 text-yellow-400' : 'text-gray-300'}
                    />
                ))}
            </div>
        );
    };

    return (
        <DashboardLayout sideLinks={sideLinks}>
            <Head title="Instructor Ratings" />

            <div className="workspace-stack">
                <section className="workspace-header-card px-6 py-6 lg:px-8">
                    <div className="max-w-3xl">
                        <p className="workspace-muted-label">Quality Oversight</p>
                        <h1 className="mt-3 text-3xl font-semibold tracking-tight text-slate-900">Instructor Ratings</h1>
                        <p className="mt-3 text-sm leading-7 text-slate-600">
                            Review student feedback, monitor instructor quality signals, and moderate ratings from one consistent workspace.
                        </p>
                    </div>
                </section>

                <div className="grid grid-cols-1 gap-4 md:grid-cols-2 xl:grid-cols-4">
                    <MetricCard
                        label="Total Ratings"
                        value={stats.total.toString()}
                        tone="text-slate-900"
                        icon={<Star className="text-slate-700" size={20} />}
                        iconWrap="bg-slate-100"
                    />
                    <MetricCard
                        label="Average Rating"
                        value={stats.average_rating.toString()}
                        tone="text-yellow-600"
                        icon={<Star className="fill-yellow-500 text-yellow-500" size={20} />}
                        iconWrap="bg-yellow-100"
                    />
                    <MetricCard
                        label="5-Star Ratings"
                        value={stats.five_star.toString()}
                        tone="text-green-600"
                        icon={<Star className="fill-green-600 text-green-600" size={20} />}
                        iconWrap="bg-green-100"
                    />
                    <MetricCard
                        label="1-Star Ratings"
                        value={stats.one_star.toString()}
                        tone="text-red-600"
                        icon={<Star className="text-red-600" size={20} />}
                        iconWrap="bg-red-100"
                    />
                </div>

                <section className="workspace-card p-5 lg:p-6">
                    <form onSubmit={handleFilter} className="grid grid-cols-1 gap-4 md:grid-cols-4">
                        <div>
                            <label className="mb-2 block text-sm font-medium text-slate-700">
                                Instructor
                            </label>
                            <select
                                value={instructorId}
                                onChange={(e) => setInstructorId(e.target.value)}
                                className="w-full border border-slate-200 bg-slate-50 px-4 py-3 text-sm text-slate-900 focus:border-emerald-300 focus:bg-white focus:outline-none focus:ring-4 focus:ring-emerald-100"
                            >
                                <option value="">All Instructors</option>
                                {instructors.map((instructor) => (
                                    <option key={instructor.id} value={instructor.id}>
                                        {instructor.name}
                                    </option>
                                ))}
                            </select>
                        </div>
                        <div>
                            <label className="mb-2 block text-sm font-medium text-slate-700">
                                Rating
                            </label>
                            <select
                                value={ratingFilter}
                                onChange={(e) => setRatingFilter(e.target.value)}
                                className="w-full border border-slate-200 bg-slate-50 px-4 py-3 text-sm text-slate-900 focus:border-emerald-300 focus:bg-white focus:outline-none focus:ring-4 focus:ring-emerald-100"
                            >
                                <option value="">All Ratings</option>
                                <option value="5">5 Stars</option>
                                <option value="4">4 Stars</option>
                                <option value="3">3 Stars</option>
                                <option value="2">2 Stars</option>
                                <option value="1">1 Star</option>
                            </select>
                        </div>
                        <div>
                            <label className="mb-2 block text-sm font-medium text-slate-700">
                                Search
                            </label>
                            <input
                                type="text"
                                value={search}
                                onChange={(e) => setSearch(e.target.value)}
                                placeholder="Search reviews..."
                                className="w-full border border-slate-200 bg-slate-50 px-4 py-3 text-sm text-slate-900 focus:border-emerald-300 focus:bg-white focus:outline-none focus:ring-4 focus:ring-emerald-100"
                            />
                        </div>
                        <div className="flex items-end gap-2">
                            <button
                                type="submit"
                                className="flex flex-1 items-center justify-center gap-2 rounded-lg bg-slate-900 px-4 py-3 text-sm font-semibold text-white transition hover:bg-slate-800"
                            >
                                <Search size={18} />
                                Filter
                            </button>
                            <button
                                type="button"
                                onClick={clearFilters}
                                className="rounded-lg border border-slate-200 px-4 py-3 text-sm font-medium text-slate-700 transition hover:bg-slate-50"
                            >
                                Clear
                            </button>
                        </div>
                    </form>
                </section>

                <section className="workspace-card overflow-hidden">
                    {ratingsList.length === 0 ? (
                        <div className="py-14 text-center">
                            <i className="fas fa-inbox mb-4 text-6xl text-gray-300"></i>
                            <p className="text-xl text-slate-600">No ratings found.</p>
                        </div>
                    ) : (
                        <div className="overflow-x-auto">
                            <table className="min-w-full divide-y divide-slate-200">
                                <thead className="bg-slate-50/90">
                                    <tr>
                                        <th className="px-6 py-3 text-left text-xs font-semibold uppercase tracking-[0.14em] text-slate-500">
                                            Instructor
                                        </th>
                                        <th className="px-6 py-3 text-left text-xs font-semibold uppercase tracking-[0.14em] text-slate-500">
                                            Student
                                        </th>
                                        <th className="px-6 py-3 text-left text-xs font-semibold uppercase tracking-[0.14em] text-slate-500">
                                            Course
                                        </th>
                                        <th className="px-6 py-3 text-left text-xs font-semibold uppercase tracking-[0.14em] text-slate-500">
                                            Rating
                                        </th>
                                        <th className="px-6 py-3 text-left text-xs font-semibold uppercase tracking-[0.14em] text-slate-500">
                                            Review
                                        </th>
                                        <th className="px-6 py-3 text-left text-xs font-semibold uppercase tracking-[0.14em] text-slate-500">
                                            Date
                                        </th>
                                        <th className="px-6 py-3 text-right text-xs font-semibold uppercase tracking-[0.14em] text-slate-500">
                                            Actions
                                        </th>
                                    </tr>
                                </thead>
                                <tbody className="divide-y divide-slate-100 bg-white">
                                    {ratingsList.map((rating) => (
                                        <tr key={rating.id} className="hover:bg-slate-50">
                                            <td className="px-6 py-4 whitespace-nowrap">
                                                <div className="text-sm font-medium text-slate-900">
                                                    {rating.instructor.name}
                                                </div>
                                            </td>
                                            <td className="px-6 py-4 whitespace-nowrap">
                                                <div className="text-sm text-slate-900">{rating.user.name}</div>
                                            </td>
                                            <td className="px-6 py-4">
                                                <div className="max-w-xs truncate text-sm text-slate-900">
                                                    {rating.course?.title || 'N/A'}
                                                </div>
                                            </td>
                                            <td className="px-6 py-4 whitespace-nowrap">
                                                <div className="flex items-center gap-2">
                                                    {renderStars(rating.rating)}
                                                    <span className="text-sm font-medium text-slate-900">
                                                        {rating.rating}
                                                    </span>
                                                </div>
                                            </td>
                                            <td className="px-6 py-4">
                                                <div className="max-w-md truncate text-sm text-slate-900">
                                                    {rating.review || '-'}
                                                </div>
                                            </td>
                                            <td className="px-6 py-4 whitespace-nowrap text-sm text-slate-500">
                                                {formatDate(rating.created_at)}
                                            </td>
                                            <td className="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                                <div className="flex items-center justify-end gap-2">
                                                    <button
                                                        onClick={() => handleDelete(rating.id)}
                                                        className="rounded-lg p-2 text-red-600 transition-colors hover:bg-red-50 hover:text-red-900"
                                                        title="Delete"
                                                    >
                                                        <Trash2 size={18} />
                                                    </button>
                                                </div>
                                            </td>
                                        </tr>
                                    ))}
                                </tbody>
                            </table>
                        </div>
                    )}
                </section>

                {ratings.links && ratings.links.length > 3 && (
                    <div className="flex justify-center">
                        <nav className="flex items-center gap-2">
                            {ratings.links.map((link: any, index: number) => (
                                <Link
                                    key={index}
                                    href={link.url || '#'}
                                    className={`rounded-lg border px-4 py-2 transition-all ${
                                        link.active
                                            ? 'border-slate-900 bg-slate-900 text-white'
                                            : 'border-slate-300 bg-white text-gray-700 hover:border-slate-900 hover:text-slate-900'
                                    } ${!link.url ? 'cursor-not-allowed opacity-50' : ''}`}
                                    preserveState
                                    dangerouslySetInnerHTML={{ __html: link.label }}
                                />
                            ))}
                        </nav>
                    </div>
                )}
            </div>
        </DashboardLayout>
    );
}

function MetricCard({
    label,
    value,
    tone,
    icon,
    iconWrap,
}: {
    label: string;
    value: string;
    tone: string;
    icon: ReactNode;
    iconWrap: string;
}) {
    return (
        <div className="workspace-card p-6">
            <div className="flex items-center justify-between">
                <div>
                    <p className="text-sm text-slate-500">{label}</p>
                    <p className={`mt-2 text-3xl font-bold ${tone}`}>{value}</p>
                </div>
                <div className={`rounded-lg p-3 ${iconWrap}`}>
                    {icon}
                </div>
            </div>
        </div>
    );
}
