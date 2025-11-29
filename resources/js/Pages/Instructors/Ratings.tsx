import { Head, Link } from '@inertiajs/react';
import GuestLayout from '@/Layouts/GuestLayout';
import { Star } from 'lucide-react';
import Pagination from '@/Components/Pagination';

interface Rating {
    id: number;
    rating: number;
    review: string;
    user: {
        name: string;
    };
    course: {
        title: string;
    };
    created_at: string;
}

interface Instructor {
    id: number;
    name: string;
}

interface RatingsProps {
    instructor: Instructor;
    ratings: {
        data: Rating[];
        links: any[]; // Adjust this type based on your pagination links structure
    };
    averageRating: number;
    totalRatings: number;
}

export default function Ratings({ instructor, ratings, averageRating, totalRatings }: RatingsProps) {
    return (
        <GuestLayout>
            <Head title={`Ratings for ${instructor.name}`} />

            <div className="py-12">
                <div className="max-w-7xl mx-auto sm:px-6 lg:px-8">
                    <div className="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                        <div className="p-6 bg-white border-b border-gray-200">
                            <h1 className="text-2xl font-bold mb-4">Ratings for {instructor.name}</h1>
                            <div className="flex items-center mb-4">
                                <Star className="w-6 h-6 text-yellow-400" />
                                <span className="ml-2 text-xl font-bold">{averageRating.toFixed(1)}</span>
                                <span className="ml-2 text-gray-500">({totalRatings} ratings)</span>
                            </div>
                            <div className="space-y-4">
                                {ratings.data.map(rating => (
                                    <div key={rating.id} className="border-b border-gray-200 pb-4">
                                        <div className="flex items-center mb-2">
                                            {[...Array(5)].map((_, i) => (
                                                <Star
                                                    key={i}
                                                    className={`w-5 h-5 ${i < rating.rating ? 'text-yellow-400' : 'text-gray-300'}`}
                                                />
                                            ))}
                                            <span className="ml-2 font-bold">{rating.user.name}</span>
                                        </div>
                                        <p className="text-gray-600 mb-2">{rating.review}</p>
                                        <div className="text-sm text-gray-500">
                                            <span>{new Date(rating.created_at).toLocaleDateString()}</span>
                                            <span className="mx-2">|</span>
                                            <span>Course: {rating.course.title}</span>
                                        </div>
                                    </div>
                                ))}
                            </div>
                            <Pagination links={ratings.links} />
                        </div>
                    </div>
                </div>
            </div>
        </GuestLayout>
    );
}
