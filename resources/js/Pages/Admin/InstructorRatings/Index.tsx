import { Head, Link, router, usePage } from '@inertiajs/react';
import DashboardLayout from '@/Layouts/DashboardLayout';
import { Star, Trash2, Search, X } from 'lucide-react';
import type { ReactNode } from 'react';
import { useState, FormEvent } from 'react';

interface User {
    id: number;
    name: string;
}

interface Rating {
    id: number;
    rating: number;
    review: string | null;
    instructor: User;
    user: User;
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

    const hasFilters = instructorId || ratingFilter || search;

    const handleDelete = (ratingId: number) => {
        if (confirm('Are you sure you want to delete this rating?')) {
            router.delete(route('admin.ratings.destroy', ratingId), { preserveScroll: true });
        }
    };

    const handleFilter = (e: FormEvent) => {
        e.preventDefault();
        router.get(route('admin.ratings.index'), {
            instructor_id: instructorId || undefined,
            rating: ratingFilter || undefined,
            search: search || undefined,
        }, { preserveState: true, preserveScroll: true });
    };

    const clearFilters = () => {
        setInstructorId('');
        setRatingFilter('');
        setSearch('');
        router.get(route('admin.ratings.index'));
    };

    const formatDate = (dateString: string) => {
        return new Date(dateString).toLocaleDateString('en-US', { year: 'numeric', month: 'short', day: 'numeric' });
    };

    const renderStars = (rating: number) => {
        return (
            <div className="flex items-center gap-0.5">
                {[1, 2, 3, 4, 5].map((star) => (
                    <Star
                        key={star}
                        size={14}
                        className={star <= rating ? 'fill-amber-400 text-amber-400' : 'text-slate-200'}
                    />
                ))}
            </div>
        );
    };

    return (
        <DashboardLayout sideLinks={sideLinks}>
            <Head title="Instructor Ratings" />

            <div className="space-y-5">
                <div>
                    <h1 className="text-xl font-semibold tracking-tight text-slate-900">Instructor Ratings</h1>
                    <p className="mt-1 text-sm text-slate-500">Review student feedback, monitor quality signals, and moderate ratings.</p>
                </div>

                <div className="grid grid-cols-1 gap-3 sm:grid-cols-2 lg:grid-cols-4">
                    <MetricCard
                        label="Total Ratings"
                        value={stats.total.toLocaleString()}
                        icon={<Star size={18} />}
                        tone="default"
                    />
                    <MetricCard
                        label="Average Rating"
                        value={stats.average_rating.toFixed(1)}
                        icon={<Star size={18} className="fill-amber-400 text-amber-400" />}
                        tone="amber"
                    />
                    <MetricCard
                        label="5-Star"
                        value={stats.five_star.toString()}
                        icon={<Star size={18} className="fill-amber-400 text-amber-400" />}
                        tone="amber"
                    />
                    <MetricCard
                        label="1-Star"
                        value={stats.one_star.toString()}
                        icon={<Star size={18} />}
                        tone="accent"
                    />
                </div>

                <div className="rounded-lg border border-slate-200 bg-white p-4">
                    <form onSubmit={handleFilter}>
                        <div className="grid grid-cols-1 gap-3 sm:grid-cols-2 lg:grid-cols-4">
                            <div>
                                <label className="mb-1 block text-[11px] font-semibold uppercase tracking-wider text-slate-400">Instructor</label>
                                <select
                                    value={instructorId}
                                    onChange={(e) => setInstructorId(e.target.value)}
                                    className="h-9 w-full rounded-md border border-slate-200 bg-white px-3 text-sm text-slate-900 outline-none transition focus:border-primary-300 focus:ring-2 focus:ring-primary-500/10"
                                >
                                    <option value="">All Instructors</option>
                                    {instructors.map((instructor) => (
                                        <option key={instructor.id} value={instructor.id}>{instructor.name}</option>
                                    ))}
                                </select>
                            </div>
                            <div>
                                <label className="mb-1 block text-[11px] font-semibold uppercase tracking-wider text-slate-400">Rating</label>
                                <select
                                    value={ratingFilter}
                                    onChange={(e) => setRatingFilter(e.target.value)}
                                    className="h-9 w-full rounded-md border border-slate-200 bg-white px-3 text-sm text-slate-900 outline-none transition focus:border-primary-300 focus:ring-2 focus:ring-primary-500/10"
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
                                <label className="mb-1 block text-[11px] font-semibold uppercase tracking-wider text-slate-400">Search</label>
                                <input
                                    type="text"
                                    value={search}
                                    onChange={(e) => setSearch(e.target.value)}
                                    placeholder="Search reviews..."
                                    className="h-9 w-full rounded-md border border-slate-200 bg-white px-3 text-sm text-slate-900 placeholder:text-slate-400 outline-none transition focus:border-primary-300 focus:ring-2 focus:ring-primary-500/10"
                                />
                            </div>
                            <div className="flex items-end gap-2">
                                <button type="submit" className="inline-flex h-9 flex-1 items-center justify-center gap-1.5 rounded-md bg-primary px-4 text-sm font-medium text-white transition hover:bg-primary-600 shadow-sm">
                                    <Search size={15} /> Filter
                                </button>
                                {hasFilters && (
                                    <button type="button" onClick={clearFilters} className="inline-flex h-9 items-center justify-center gap-1 rounded-md border border-slate-200 bg-white px-3 text-sm font-medium text-slate-600 transition hover:bg-slate-50">
                                        <X size={15} /> Clear
                                    </button>
                                )}
                            </div>
                        </div>
                    </form>
                </div>

                <div className="overflow-hidden rounded-lg border border-slate-200 bg-white">
                    {ratingsList.length === 0 ? (
                        <div className="p-16 text-center">
                            <div className="mx-auto flex h-14 w-14 items-center justify-center rounded-lg bg-slate-100">
                                <Star size={24} className="text-slate-300" />
                            </div>
                            <h3 className="mt-4 text-sm font-semibold text-slate-900">No ratings found</h3>
                            <p className="mt-1 text-sm text-slate-500">
                                {hasFilters ? 'Try adjusting your filters.' : 'Student ratings will appear here.'}
                            </p>
                        </div>
                    ) : (
                        <div className="overflow-x-auto">
                            <table className="min-w-full divide-y divide-slate-200">
                                <thead className="bg-slate-50/80">
                                    <tr>
                                        <th className="px-5 py-3 text-left text-[11px] font-semibold uppercase tracking-[0.14em] text-slate-500">Instructor</th>
                                        <th className="px-5 py-3 text-left text-[11px] font-semibold uppercase tracking-[0.14em] text-slate-500">Student</th>
                                        <th className="px-5 py-3 text-left text-[11px] font-semibold uppercase tracking-[0.14em] text-slate-500">Rating</th>
                                        <th className="px-5 py-3 text-left text-[11px] font-semibold uppercase tracking-[0.14em] text-slate-500">Review</th>
                                        <th className="px-5 py-3 text-left text-[11px] font-semibold uppercase tracking-[0.14em] text-slate-500">Date</th>
                                        <th className="px-5 py-3 text-right text-[11px] font-semibold uppercase tracking-[0.14em] text-slate-500"></th>
                                    </tr>
                                </thead>
                                <tbody className="divide-y divide-slate-100 bg-white">
                                    {ratingsList.map((rating) => (
                                        <tr key={rating.id} className="transition hover:bg-slate-50/70">
                                            <td className="px-5 py-3.5 whitespace-nowrap">
                                                <p className="text-sm font-medium text-slate-900">{rating.instructor.name}</p>
                                            </td>
                                            <td className="px-5 py-3.5 whitespace-nowrap">
                                                <p className="text-sm text-slate-700">{rating.user.name}</p>
                                            </td>
                                            <td className="px-5 py-3.5 whitespace-nowrap">
                                                <div className="flex items-center gap-2">
                                                    {renderStars(rating.rating)}
                                                    <span className="text-sm font-semibold text-slate-900">{rating.rating}</span>
                                                </div>
                                            </td>
                                            <td className="px-5 py-3.5 max-w-xs">
                                                <p className="truncate text-sm text-slate-600">{rating.review || '—'}</p>
                                            </td>
                                            <td className="px-5 py-3.5 whitespace-nowrap text-sm text-slate-500">
                                                {formatDate(rating.created_at)}
                                            </td>
                                            <td className="px-5 py-3.5 whitespace-nowrap text-right">
                                                <button
                                                    onClick={() => handleDelete(rating.id)}
                                                    className="rounded-md p-1.5 text-slate-400 transition hover:bg-accent-50 hover:text-accent"
                                                    title="Delete"
                                                >
                                                    <Trash2 size={15} />
                                                </button>
                                            </td>
                                        </tr>
                                    ))}
                                </tbody>
                            </table>
                        </div>
                    )}
                </div>

                {ratings.links && ratings.links.length > 3 && (
                    <div className="flex justify-center">
                        <nav className="flex items-center gap-1.5">
                            {ratings.links.map((link: any, index: number) => (
                                <Link
                                    key={index}
                                    href={link.url || '#'}
                                    className={`rounded-md px-3 py-1.5 text-xs font-medium transition ${
                                        link.active
                                            ? 'bg-primary text-white'
                                            : link.url
                                                ? 'border border-slate-200 bg-white text-slate-700 hover:bg-slate-50'
                                                : 'bg-slate-50 text-slate-300 cursor-not-allowed'
                                    }`}
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
}: {
    label: string;
    value: string;
    tone: 'default' | 'amber' | 'accent';
    icon: ReactNode;
}) {
    const toneStyles = {
        default: { bg: 'bg-slate-100 text-slate-600', text: 'text-slate-900' },
        amber: { bg: 'bg-amber-50 text-amber-600', text: 'text-amber-600' },
        accent: { bg: 'bg-accent-50 text-accent', text: 'text-accent' },
    };

    const style = toneStyles[tone];

    return (
        <div className="rounded-lg border border-slate-200 bg-white p-4">
            <div className="flex items-start justify-between">
                <div>
                    <p className="text-[11px] font-semibold uppercase tracking-wider text-slate-400">{label}</p>
                    <p className={`mt-2 text-2xl font-semibold tracking-tight ${style.text}`}>{value}</p>
                </div>
                <span className={`flex h-9 w-9 items-center justify-center rounded-lg ${style.bg}`}>
                    {icon}
                </span>
            </div>
        </div>
    );
}
