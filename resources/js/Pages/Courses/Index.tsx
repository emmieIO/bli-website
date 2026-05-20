import { Head, Link } from '@inertiajs/react';
import GuestLayout from '@/Layouts/GuestLayout';
import { useState } from 'react';

interface Instructor {
    id: number;
    name: string;
    avatar_url?: string;
    ratings_received_avg_rating?: number | null;
    ratings_received_count?: number;
}

interface Category {
    id: number;
    name: string;
}

interface Lesson {
    id: number;
}

interface Module {
    id: number;
    lessons?: Lesson[];
}

interface Course {
    id: number;
    slug: string;
    title: string;
    subtitle?: string;
    description?: string;
    price: number;
    is_free?: boolean;
    language?: string;
    updated_at?: string;
    thumbnail_path?: string;
    level?: {
        value: string;
    };
    category: Category;
    instructor: Instructor;
    modules?: Module[];
    students?: { id: number }[];
}

interface CoursesIndexProps {
    courses: Course[];
}

export default function CoursesIndex({ courses }: CoursesIndexProps) {
    const [searchQuery, setSearchQuery] = useState('');
    const [selectedCategory, setSelectedCategory] = useState('');

    const categories = Array.from(new Set(courses.map((course) => course.category.name))).sort();

    const filteredCourses = courses.filter((course) => {
        const query = searchQuery.trim().toLowerCase();
        const matchesSearch =
            !query ||
            course.title.toLowerCase().includes(query) ||
            course.subtitle?.toLowerCase().includes(query) ||
            course.description?.toLowerCase().includes(query) ||
            course.instructor.name.toLowerCase().includes(query) ||
            course.category.name.toLowerCase().includes(query);

        const matchesCategory = !selectedCategory || course.category.name === selectedCategory;

        return matchesSearch && matchesCategory;
    });

    const getLessonCount = (course: Course) =>
        course.modules?.reduce((total, module) => total + (module.lessons?.length || 0), 0) || 0;

    const formatPrice = (course: Course) => {
        if (course.is_free || Number(course.price) === 0) {
            return 'Free';
        }

        return `₦${Number(course.price).toLocaleString()}`;
    };

    const formatLevel = (course: Course) => {
        if (!course.level?.value) {
            return 'All levels';
        }

        return `${course.level.value.charAt(0).toUpperCase()}${course.level.value.slice(1)}`;
    };

    const formatDate = (dateString?: string) => {
        if (!dateString) {
            return 'Recently updated';
        }

        return new Date(dateString).toLocaleDateString('en-US', {
            month: 'short',
            year: 'numeric',
        });
    };

    return (
        <GuestLayout>
            <Head title="Courses" />

            <section className="bg-white py-16 md:py-20">
                <div className="section-shell">
                    <div className="mx-auto max-w-4xl space-y-8">
                        <div className="inline-flex items-center rounded-full bg-primary/10 px-4 py-2 text-primary">
                            <i className="fas fa-book-open mr-2 text-accent"></i>
                            <span className="text-sm font-semibold font-montserrat">Courses</span>
                        </div>

                        <div className="space-y-4">
                            <h1 className="max-w-3xl text-4xl font-bold leading-tight text-primary font-montserrat md:text-5xl">
                                Explore available courses
                            </h1>
                            <p className="max-w-2xl text-lg leading-8 text-gray-600 font-lato md:text-xl">
                                Browse published courses, review the curriculum, and choose the one that fits your learning goals.
                            </p>
                        </div>

                        <div className="grid gap-4 rounded-2xl border border-gray-200 bg-gray-50 p-5 md:grid-cols-[minmax(0,1fr)_220px]">
                            <div className="relative">
                                <i className="fas fa-search pointer-events-none absolute left-4 top-1/2 -translate-y-1/2 text-gray-400"></i>
                                <input
                                    type="text"
                                    placeholder="Search by title, topic, instructor, or category"
                                    value={searchQuery}
                                    onChange={(e) => setSearchQuery(e.target.value)}
                                    className="public-input pl-12"
                                />
                            </div>
                            <select
                                value={selectedCategory}
                                onChange={(e) => setSelectedCategory(e.target.value)}
                                className="public-input"
                            >
                                <option value="">All categories</option>
                                {categories.map((category) => (
                                    <option key={category} value={category}>
                                        {category}
                                    </option>
                                ))}
                            </select>
                        </div>

                        <div className="flex flex-wrap items-center gap-4 border-t border-gray-200 pt-6 text-sm text-gray-600">
                            <span>
                                <span className="font-semibold text-primary">{filteredCourses.length}</span>{' '}
                                {filteredCourses.length === 1 ? 'course' : 'courses'}
                            </span>
                            <span className="hidden h-1 w-1 rounded-full bg-gray-300 sm:block"></span>
                            <span>Search and filter use live course data only</span>
                        </div>
                    </div>
                </div>
            </section>

            <section className="public-section bg-gray-50">
                <div className="section-shell">
                    {filteredCourses.length > 0 ? (
                        <div className="grid grid-cols-1 gap-6 md:grid-cols-2 xl:grid-cols-3">
                            {filteredCourses.map((course) => {
                                const lessonCount = getLessonCount(course);
                                const studentCount = course.students?.length || 0;
                                const instructorRating = course.instructor.ratings_received_avg_rating;
                                const ratingCount = course.instructor.ratings_received_count || 0;

                                return (
                                    <article
                                        key={course.id}
                                        className="overflow-hidden rounded-[26px] border border-gray-200 bg-white shadow-[0_16px_40px_rgba(15,23,42,0.06)] transition duration-200 hover:-translate-y-1 hover:shadow-[0_20px_50px_rgba(15,23,42,0.09)]"
                                    >
                                        <Link href={route('courses.show', course.slug)} className="block">
                                            <div className="aspect-[16/10] overflow-hidden bg-gray-100">
                                                {course.thumbnail_path ? (
                                                    <img
                                                        className="h-full w-full object-cover transition duration-300 hover:scale-[1.03]"
                                                        src={`/storage/${course.thumbnail_path}`}
                                                        alt={course.title}
                                                    />
                                                ) : (
                                                    <div className="flex h-full items-center justify-center bg-primary/5 text-primary">
                                                        <i className="fas fa-book-open text-3xl"></i>
                                                    </div>
                                                )}
                                            </div>
                                        </Link>

                                        <div className="space-y-5 p-6">
                                            <div className="flex items-start justify-between gap-3">
                                                <span className="rounded-full bg-accent/10 px-3 py-1 text-xs font-semibold text-accent">
                                                    {course.category.name}
                                                </span>
                                                <span className="text-sm font-semibold text-primary">{formatPrice(course)}</span>
                                            </div>

                                            <div className="space-y-2">
                                                <h2 className="text-xl font-bold leading-snug text-primary font-montserrat">
                                                    <Link href={route('courses.show', course.slug)}>{course.title}</Link>
                                                </h2>
                                                <p className="line-clamp-3 text-sm leading-6 text-gray-600 font-lato">
                                                    {course.subtitle || course.description || 'Course details available on the course page.'}
                                                </p>
                                            </div>

                                            <div className="grid grid-cols-2 gap-3 rounded-2xl bg-gray-50 p-4 text-sm text-gray-600">
                                                <div>
                                                    <p className="font-semibold text-primary">Level</p>
                                                    <p className="mt-1">{formatLevel(course)}</p>
                                                </div>
                                                <div>
                                                    <p className="font-semibold text-primary">Lessons</p>
                                                    <p className="mt-1">{lessonCount}</p>
                                                </div>
                                                <div>
                                                    <p className="font-semibold text-primary">Students</p>
                                                    <p className="mt-1">{studentCount}</p>
                                                </div>
                                                <div>
                                                    <p className="font-semibold text-primary">Updated</p>
                                                    <p className="mt-1">{formatDate(course.updated_at)}</p>
                                                </div>
                                            </div>

                                            <div className="flex items-center justify-between gap-4">
                                                <div className="flex min-w-0 items-center gap-3">
                                                    <div className="h-9 w-9 overflow-hidden rounded-full bg-gray-100">
                                                        {course.instructor.avatar_url ? (
                                                            <img
                                                                src={course.instructor.avatar_url}
                                                                alt={course.instructor.name}
                                                                className="h-full w-full object-cover"
                                                            />
                                                        ) : (
                                                            <img
                                                                src={`https://ui-avatars.com/api/?name=${encodeURIComponent(
                                                                    course.instructor.name || 'Instructor'
                                                                )}&background=002147&color=fff&size=36`}
                                                                alt={course.instructor.name}
                                                                className="h-full w-full object-cover"
                                                            />
                                                        )}
                                                    </div>
                                                    <div className="min-w-0">
                                                        <p className="truncate text-sm font-semibold text-primary">{course.instructor.name}</p>
                                                        <p className="text-xs text-gray-500">
                                                            {instructorRating && ratingCount > 0
                                                                ? `${instructorRating.toFixed(1)} instructor rating`
                                                                : course.language || 'English'}
                                                        </p>
                                                    </div>
                                                </div>

                                                <Link
                                                    href={route('courses.show', course.slug)}
                                                    className="inline-flex items-center gap-2 text-sm font-semibold text-accent hover:text-accent/80"
                                                >
                                                    View course
                                                    <i className="fas fa-arrow-right text-xs"></i>
                                                </Link>
                                            </div>
                                        </div>
                                    </article>
                                );
                            })}
                        </div>
                    ) : (
                        <div className="rounded-[28px] border border-gray-200 bg-white px-6 py-16 text-center shadow-[0_16px_40px_rgba(15,23,42,0.06)]">
                            <div className="mx-auto mb-6 flex h-16 w-16 items-center justify-center rounded-full bg-primary/10 text-primary">
                                <i className="fas fa-book-open text-2xl"></i>
                            </div>
                            <h2 className="text-2xl font-bold text-primary font-montserrat">
                                {courses.length === 0 ? 'No courses available yet' : 'No courses match your search'}
                            </h2>
                            <p className="mx-auto mt-3 max-w-xl text-gray-600 font-lato">
                                {courses.length === 0
                                    ? 'Published courses will appear here when they are ready.'
                                    : 'Try a different keyword or clear the category filter.'}
                            </p>
                            <div className="mt-8">
                                <Link href={route('homepage')} className="enterprise-button enterprise-button-primary rounded-xl px-6 py-3">
                                    <i className="fas fa-home"></i>
                                    Back to Home
                                </Link>
                            </div>
                        </div>
                    )}
                </div>
            </section>
        </GuestLayout>
    );
}
