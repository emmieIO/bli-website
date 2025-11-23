import { Head, Link } from '@inertiajs/react';
import GuestLayout from '@/Layouts/GuestLayout';
import { useState } from 'react';

interface Instructor {
    id: number;
    name: string;
    avatar_url?: string;
}

interface Category {
    id: number;
    name: string;
}

interface Course {
    id: number;
    slug: string;
    title: string;
    description?: string;
    price: number;
    thumbnail_path: string;
    category: Category;
    instructor: Instructor;
}

interface CoursesIndexProps {
    courses: Course[];
}

export default function CoursesIndex({ courses }: CoursesIndexProps) {
    const [searchQuery, setSearchQuery] = useState('');
    const [selectedCategory, setSelectedCategory] = useState('');

    // Extract unique categories from courses
    const categories = Array.from(
        new Set(courses.map(course => course.category.name))
    );

    // Filter courses based on search and category
    const filteredCourses = courses.filter(course => {
        // If no search query, show all courses (only filter by category)
        const matchesSearch = !searchQuery.trim() ||
            course.title.toLowerCase().includes(searchQuery.toLowerCase()) ||
            (course.description && course.description.toLowerCase().includes(searchQuery.toLowerCase())) ||
            (course.instructor.name && course.instructor.name.toLowerCase().includes(searchQuery.toLowerCase())) ||
            course.category.name.toLowerCase().includes(searchQuery.toLowerCase());

        const matchesCategory = !selectedCategory || course.category.name === selectedCategory;

        return matchesSearch && matchesCategory;
    });

    return (
        <GuestLayout>
            <Head title="Explore Our Courses" />

            {/* Hero Section */}
            <section className="py-16 bg-gradient-to-br from-gray-50 to-white">
                <div className="container mx-auto px-6">
                    <div className="text-center max-w-4xl mx-auto">
                        {/* Badge */}
                        <div className="inline-flex items-center gap-2 border rounded-full px-6 py-3 mb-8 bg-accent/10 border-accent">
                            <i className="fas fa-graduation-cap text-sm text-accent"></i>
                            <span className="font-medium font-montserrat text-sm tracking-wide text-primary">
                                Professional Development
                            </span>
                        </div>

                        {/* Main Heading */}
                        <h1 className="font-bold font-montserrat text-4xl md:text-5xl lg:text-6xl mb-6 leading-tight text-primary">
                            Explore Our <span className="text-accent">Courses</span>
                        </h1>

                        <p className="text-xl text-gray-600 mb-8 max-w-3xl mx-auto leading-relaxed font-lato">
                            Browse our curated selection of courses designed to upskill, learn new technologies, and advance
                            your leadership career. New courses added regularly.
                        </p>

                        {/* Search and Filter Section */}
                        <div className="bg-white rounded-2xl shadow-lg p-6 border border-gray-100 max-w-2xl mx-auto">
                            <div className="flex flex-col md:flex-row gap-4">
                                <div className="flex-1 relative">
                                    <i className="fas fa-search absolute left-4 top-1/2 transform -translate-y-1/2 text-gray-400"></i>
                                    <input
                                        type="text"
                                        placeholder="Search courses..."
                                        value={searchQuery}
                                        onChange={(e) => setSearchQuery(e.target.value)}
                                        className="w-full pl-12 pr-4 py-3 rounded-xl border border-gray-200 focus:outline-none focus:ring-2 focus:ring-accent focus:border-transparent font-lato"
                                    />
                                </div>
                                <select
                                    value={selectedCategory}
                                    onChange={(e) => setSelectedCategory(e.target.value)}
                                    className="px-4 py-3 rounded-xl border border-gray-200 focus:outline-none focus:ring-2 focus:ring-accent focus:border-transparent font-lato md:w-48"
                                >
                                    <option value="">All Categories</option>
                                    {categories.map((category, index) => (
                                        <option key={index} value={category}>
                                            {category}
                                        </option>
                                    ))}
                                </select>
                            </div>
                        </div>
                    </div>
                </div>
            </section>

            {/* Courses Grid Section */}
            <section className="py-20 bg-white">
                <div className="container mx-auto px-6">
                    {filteredCourses.length > 0 ? (
                        <>
                            <div className="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-8">
                                {filteredCourses.map((course, index) => (
                                    <div
                                        key={course.id}
                                        className="group bg-white rounded-2xl shadow-lg hover:shadow-2xl transition-all duration-500 overflow-hidden border border-gray-100 hover:border-accent/20"
                                    >
                                        {/* Course Image */}
                                        <div className="relative h-48 overflow-hidden">
                                            <img
                                                className="w-full h-full object-cover group-hover:scale-110 transition-transform duration-500"
                                                src={`/storage/${course.thumbnail_path}`}
                                                alt={course.title}
                                            />

                                            {/* Category Badge */}
                                            <div className="absolute top-4 left-4">
                                                <span className="px-3 py-1.5 text-xs font-semibold rounded-full text-white font-montserrat bg-accent">
                                                    {course.category.name}
                                                </span>
                                            </div>

                                            {/* Price Badge */}
                                            <div className="absolute top-4 right-4">
                                                <span className="px-3 py-1.5 text-sm font-bold rounded-full bg-white shadow-md font-montserrat text-primary">
                                                    ₦{course.price.toLocaleString()}
                                                </span>
                                            </div>
                                        </div>

                                        {/* Course Content */}
                                        <div className="p-6">
                                            {/* Course Title */}
                                            <h3 className="text-xl font-bold mb-3 font-montserrat line-clamp-2 group-hover:text-accent transition-colors duration-300 text-primary">
                                                <Link href={route('courses.show', course.slug)}>
                                                    {course.title}
                                                </Link>
                                            </h3>

                                            {/* Course Stats */}
                                            <div className="flex items-center justify-between mb-4 text-sm text-gray-600">
                                                <div className="flex items-center gap-1">
                                                    <i className="fas fa-star text-yellow-400"></i>
                                                    <span className="font-semibold">4.8</span>
                                                    <span className="text-gray-500">(1.2k)</span>
                                                </div>
                                                <div className="flex items-center gap-1">
                                                    <i className="fas fa-users text-gray-400"></i>
                                                    <span>12.5k students</span>
                                                </div>
                                            </div>

                                            {/* Instructor */}
                                            <div className="flex items-center gap-3 mb-4">
                                                <div className="w-8 h-8 rounded-full overflow-hidden">
                                                    <img
                                                        src={
                                                            course.instructor.avatar_url ||
                                                            `https://ui-avatars.com/api/?name=${encodeURIComponent(
                                                                course.instructor.name || 'Instructor'
                                                            )}&background=00a651&color=fff&size=32`
                                                        }
                                                        alt="Instructor"
                                                        className="w-full h-full object-cover"
                                                    />
                                                </div>
                                                <span className="text-sm text-gray-600 font-lato">
                                                    {course.instructor.name || 'BLI Instructor'}
                                                </span>
                                            </div>

                                            {/* Enroll Button */}
                                            <Link
                                                href={route('courses.show', course.slug)}
                                                className="w-full flex items-center justify-center gap-2 font-semibold py-3 px-4 rounded-xl transition-all duration-300 shadow-md hover:shadow-lg transform hover:scale-105 text-white font-montserrat bg-accent hover:bg-accent-700"
                                            >
                                                <span>Enroll Now</span>
                                                <i className="fas fa-arrow-right text-sm group-hover:translate-x-1 transition-transform"></i>
                                            </Link>
                                        </div>
                                    </div>
                                ))}
                            </div>
                        </>
                    ) : (
                        /* No Courses State */
                        <div className="text-center py-20">
                            <div className="mb-8">
                                <i className="fas fa-graduation-cap text-6xl text-gray-300"></i>
                            </div>
                            <h3 className="text-2xl font-bold mb-4 font-montserrat text-primary">
                                {courses.length === 0 ? 'No Courses Available' : 'No Courses Found'}
                            </h3>
                            <p className="text-gray-600 font-lato mb-8">
                                {courses.length === 0
                                    ? "We're working hard to bring you amazing courses. Check back soon!"
                                    : 'Try adjusting your search or filters.'}
                            </p>
                            <Link
                                href={route('homepage')}
                                className="inline-flex items-center gap-2 font-semibold px-6 py-3 rounded-xl transition-all duration-300 shadow-md hover:shadow-lg text-white font-montserrat bg-accent"
                            >
                                <i className="fas fa-home"></i>
                                <span>Back to Home</span>
                            </Link>
                        </div>
                    )}
                </div>
            </section>
        </GuestLayout>
    );
}
