import { Head, Link, router, usePage } from '@inertiajs/react';
import DashboardLayout from '@/Layouts/DashboardLayout';
import { Star, Trash2, Eye, Search } from 'lucide-react';
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

            <div className="py-8">
                <div className="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                    {/* Header */}
                    <div className="mb-6">
                        <h1 className="text-3xl font-bold text-gray-900">Instructor Ratings</h1>
                        <p className="mt-1 text-sm text-gray-600">
                            View and manage instructor ratings and reviews
                        </p>
                    </div>

                    {/* Stats */}
                    <div className="grid grid-cols-1 md:grid-cols-4 gap-6 mb-6">
                        <div className="bg-white shadow-md rounded-lg p-6">
                            <div className="flex items-center justify-between">
                                <div>
                                    <p className="text-sm text-gray-600">Total Ratings</p>
                                    <p className="text-3xl font-bold text-gray-900 mt-1">{stats.total}</p>
                                </div>
                                <div className="p-3 bg-blue-100 rounded-lg">
                                    <Star className="text-blue-600" size={24} />
                                </div>
                            </div>
                        </div>
                        <div className="bg-white shadow-md rounded-lg p-6">
                            <div className="flex items-center justify-between">
                                <div>
                                    <p className="text-sm text-gray-600">Average Rating</p>
                                    <p className="text-3xl font-bold text-yellow-600 mt-1">{stats.average_rating}</p>
                                </div>
                                <div className="p-3 bg-yellow-100 rounded-lg">
                                    <Star className="text-yellow-600 fill-yellow-600" size={24} />
                                </div>
                            </div>
                        </div>
                        <div className="bg-white shadow-md rounded-lg p-6">
                            <div className="flex items-center justify-between">
                                <div>
                                    <p className="text-sm text-gray-600">5-Star Ratings</p>
                                    <p className="text-3xl font-bold text-green-600 mt-1">{stats.five_star}</p>
                                </div>
                                <div className="p-3 bg-green-100 rounded-lg">
                                    <Star className="text-green-600 fill-green-600" size={24} />
                                </div>
                            </div>
                        </div>
                        <div className="bg-white shadow-md rounded-lg p-6">
                            <div className="flex items-center justify-between">
                                <div>
                                    <p className="text-sm text-gray-600">1-Star Ratings</p>
                                    <p className="text-3xl font-bold text-red-600 mt-1">{stats.one_star}</p>
                                </div>
                                <div className="p-3 bg-red-100 rounded-lg">
                                    <Star className="text-red-600" size={24} />
                                </div>
                            </div>
                        </div>
                    </div>

                    {/* Filters */}
                    <div className="bg-white shadow-md rounded-lg p-6 mb-6">
                        <form onSubmit={handleFilter} className="grid grid-cols-1 md:grid-cols-4 gap-4">
                            <div>
                                <label className="block text-sm font-medium text-gray-700 mb-2">
                                    Instructor
                                </label>
                                <select
                                    value={instructorId}
                                    onChange={(e) => setInstructorId(e.target.value)}
                                    className="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-accent focus:border-transparent"
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
                                <label className="block text-sm font-medium text-gray-700 mb-2">
                                    Rating
                                </label>
                                <select
                                    value={ratingFilter}
                                    onChange={(e) => setRatingFilter(e.target.value)}
                                    className="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-accent focus:border-transparent"
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
                                <label className="block text-sm font-medium text-gray-700 mb-2">
                                    Search
                                </label>
                                <input
                                    type="text"
                                    value={search}
                                    onChange={(e) => setSearch(e.target.value)}
                                    placeholder="Search reviews..."
                                    className="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-accent focus:border-transparent"
                                />
                            </div>
                            <div className="flex items-end gap-2">
                                <button
                                    type="submit"
                                    className="flex-1 px-4 py-2 bg-accent text-white rounded-lg hover:bg-accent/90 transition-colors flex items-center justify-center gap-2"
                                >
                                    <Search size={18} />
                                    Filter
                                </button>
                                <button
                                    type="button"
                                    onClick={clearFilters}
                                    className="px-4 py-2 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50 transition-colors"
                                >
                                    Clear
                                </button>
                            </div>
                        </form>
                    </div>

                    {/* Ratings Table */}
                    <div className="bg-white shadow-md rounded-lg overflow-hidden">
                        {ratingsList.length === 0 ? (
                            <div className="text-center py-12">
                                <i className="fas fa-inbox text-6xl text-gray-300 mb-4"></i>
                                <p className="text-xl text-gray-600">No ratings found.</p>
                            </div>
                        ) : (
                            <table className="min-w-full divide-y divide-gray-200">
                                <thead className="bg-gray-50">
                                    <tr>
                                        <th className="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            Instructor
                                        </th>
                                        <th className="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            Student
                                        </th>
                                        <th className="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            Course
                                        </th>
                                        <th className="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            Rating
                                        </th>
                                        <th className="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            Review
                                        </th>
                                        <th className="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            Date
                                        </th>
                                        <th className="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            Actions
                                        </th>
                                    </tr>
                                </thead>
                                <tbody className="bg-white divide-y divide-gray-200">
                                    {ratingsList.map((rating) => (
                                        <tr key={rating.id} className="hover:bg-gray-50">
                                            <td className="px-6 py-4 whitespace-nowrap">
                                                <div className="text-sm font-medium text-gray-900">
                                                    {rating.instructor.name}
                                                </div>
                                            </td>
                                            <td className="px-6 py-4 whitespace-nowrap">
                                                <div className="text-sm text-gray-900">{rating.user.name}</div>
                                            </td>
                                            <td className="px-6 py-4">
                                                <div className="text-sm text-gray-900 max-w-xs truncate">
                                                    {rating.course?.title || 'N/A'}
                                                </div>
                                            </td>
                                            <td className="px-6 py-4 whitespace-nowrap">
                                                <div className="flex items-center gap-2">
                                                    {renderStars(rating.rating)}
                                                    <span className="text-sm font-medium text-gray-900">
                                                        {rating.rating}
                                                    </span>
                                                </div>
                                            </td>
                                            <td className="px-6 py-4">
                                                <div className="text-sm text-gray-900 max-w-md truncate">
                                                    {rating.review || '-'}
                                                </div>
                                            </td>
                                            <td className="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                                {formatDate(rating.created_at)}
                                            </td>
                                            <td className="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                                <div className="flex items-center justify-end gap-2">
                                                    <button
                                                        onClick={() => handleDelete(rating.id)}
                                                        className="text-red-600 hover:text-red-900 p-2 hover:bg-red-50 rounded-lg transition-colors"
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
                        )}
                    </div>

                    {/* Pagination */}
                    {ratings.links && ratings.links.length > 3 && (
                        <div className="mt-6 flex justify-center">
                            <nav className="flex items-center gap-2">
                                {ratings.links.map((link: any, index: number) => (
                                    <Link
                                        key={index}
                                        href={link.url || '#'}
                                        className={`px-4 py-2 rounded-lg border transition-all ${
                                            link.active
                                                ? 'bg-accent text-white border-accent'
                                                : 'bg-white text-gray-700 border-gray-300 hover:border-accent hover:text-accent'
                                        } ${!link.url ? 'opacity-50 cursor-not-allowed' : ''}`}
                                        preserveState
                                        dangerouslySetInnerHTML={{ __html: link.label }}
                                    />
                                ))}
                            </nav>
                        </div>
                    )}
                </div>
            </div>
        </DashboardLayout>
    );
}
