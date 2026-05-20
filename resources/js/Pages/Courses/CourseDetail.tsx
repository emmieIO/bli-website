import { Head, Link, router, usePage } from '@inertiajs/react';
import GuestLayout from '@/Layouts/GuestLayout';
import VimeoPlayer from '@/Components/VimeoPlayer';
import { useState } from 'react';

interface Instructor {
    id: number;
    name: string;
    title?: string;
    bio?: string;
    avatar_url?: string;
}

interface Category {
    id: number;
    name: string;
    category?: string;
}

interface Lesson {
    id: number;
    title: string;
    type: 'video' | 'pdf' | 'text';
    duration?: string;
    order: number;
}

interface Module {
    id: number;
    title: string;
    lessons?: Lesson[];
}

interface Outcome {
    id: number;
    outcome: string;
}

interface Requirement {
    id: number;
    requirement: string;
}

interface Student {
    id: number;
    name: string;
}

interface Course {
    id: number;
    slug: string;
    title: string;
    subtitle?: string;
    description: string;
    price: number;
    is_free: boolean;
    preview_video_id?: string;
    thumbnail_path?: string;
    level?: {
        value: string;
    };
    language?: string;
    updated_at: string;
    category: Category;
    instructor: Instructor;
    modules?: Module[];
    outcomes?: Outcome[];
    requirements?: Requirement[];
    students?: Student[];
}

interface CourseDetailProps {
    course: Course;
    isEnrolled: boolean;
    courseRating: number;
    ratingCount: number;
    instructorRating: number;
}

export default function CourseDetail({
    course,
    isEnrolled,
    courseRating,
    ratingCount,
    instructorRating,
}: CourseDetailProps) {
    const { auth } = usePage().props as any;
    const [expandedModules, setExpandedModules] = useState<number[]>([]);
    const [isEnrolling, setIsEnrolling] = useState(false);

    const toggleModule = (moduleId: number) => {
        setExpandedModules((prev) =>
            prev.includes(moduleId) ? prev.filter((id) => id !== moduleId) : [...prev, moduleId]
        );
    };

    const formatDate = (dateString: string) =>
        new Date(dateString).toLocaleDateString('en-US', { month: 'short', year: 'numeric' });

    const handleEnroll = () => {
        if (!auth.user) {
            router.visit(route('login'));
            return;
        }

        setIsEnrolling(true);
        router.post(
            route('courses.enroll', course.slug),
            {},
            {
                preserveScroll: true,
                onFinish: () => {
                    setIsEnrolling(false);
                },
            }
        );
    };

    const handleAddToCart = () => {
        if (!auth.user) {
            router.visit(route('login'));
            return;
        }

        router.post(
            route('cart.add', course.slug),
            {},
            {
                preserveScroll: true,
            }
        );
    };

    const handleBuyNow = () => {
        if (!auth.user) {
            router.visit(route('login'));
            return;
        }

        router.visit(route('payment.checkout', course.slug));
    };

    const getLessonIcon = (type: string) => {
        switch (type) {
            case 'video':
                return <i className="fas fa-play text-xs text-gray-600"></i>;
            case 'pdf':
                return <i className="fas fa-file-pdf text-xs text-gray-600"></i>;
            default:
                return <i className="fas fa-align-left text-xs text-gray-600"></i>;
        }
    };

    const totalLessons = course.modules?.reduce((acc, module) => acc + (module.lessons?.length || 0), 0) || 0;
    const totalStudents = course.students?.length || 0;
    const totalModules = course.modules?.length || 0;
    const priceLabel = course.is_free || Number(course.price) === 0 ? 'Free' : `₦${Number(course.price).toLocaleString()}`;
    const levelLabel = course.level?.value
        ? `${course.level.value.charAt(0).toUpperCase()}${course.level.value.slice(1)}`
        : 'All levels';

    return (
        <GuestLayout>
            <Head title={course.title} />

            <section className="bg-white py-14 md:py-18">
                <div className="section-shell">
                    <div className="grid gap-10 lg:grid-cols-[minmax(0,1.25fr)_360px]">
                        <div className="space-y-8">
                            <nav className="flex flex-wrap items-center gap-2 text-sm text-gray-500">
                                <Link href={route('homepage')} className="hover:text-primary">
                                    Home
                                </Link>
                                <span>/</span>
                                <Link href={route('courses.index')} className="hover:text-primary">
                                    Courses
                                </Link>
                                <span>/</span>
                                <span className="text-primary">{course.category?.category || course.category?.name}</span>
                            </nav>

                            <div className="space-y-4">
                                <div className="inline-flex items-center rounded-full bg-accent/10 px-4 py-2 text-accent">
                                    <span className="text-sm font-semibold font-montserrat">{course.category?.name || 'Course'}</span>
                                </div>
                                <h1 className="max-w-4xl text-4xl font-bold leading-tight text-primary font-montserrat md:text-5xl">
                                    {course.title}
                                </h1>
                                {course.subtitle && (
                                    <p className="max-w-3xl text-lg leading-8 text-gray-600 font-lato md:text-xl">
                                        {course.subtitle}
                                    </p>
                                )}
                            </div>

                            <div className="grid gap-4 border-t border-gray-200 pt-6 text-sm text-gray-600 sm:grid-cols-2 xl:grid-cols-4">
                                <div>
                                    <p className="font-semibold text-primary">Level</p>
                                    <p className="mt-1">{levelLabel}</p>
                                </div>
                                <div>
                                    <p className="font-semibold text-primary">Course content</p>
                                    <p className="mt-1">
                                        {totalModules} sections, {totalLessons} lessons
                                    </p>
                                </div>
                                <div>
                                    <p className="font-semibold text-primary">Language</p>
                                    <p className="mt-1">{course.language || 'English'}</p>
                                </div>
                                <div>
                                    <p className="font-semibold text-primary">Last updated</p>
                                    <p className="mt-1">{formatDate(course.updated_at)}</p>
                                </div>
                            </div>

                            <div className="flex flex-wrap items-center gap-4 text-sm text-gray-600">
                                <span>
                                    By <a href="#instructor" className="font-semibold text-primary hover:text-accent">{course.instructor.name}</a>
                                </span>
                                {courseRating > 0 && ratingCount > 0 && (
                                    <span>{courseRating.toFixed(1)} rating from {ratingCount} review{ratingCount !== 1 ? 's' : ''}</span>
                                )}
                                <span>{totalStudents} enrolled student{totalStudents !== 1 ? 's' : ''}</span>
                            </div>
                        </div>

                        <aside className="lg:pt-8">
                            <div className="sticky top-8 overflow-hidden rounded-[28px] border border-gray-200 bg-white shadow-[0_16px_40px_rgba(15,23,42,0.08)]">
                                <div className="aspect-video overflow-hidden bg-gray-100">
                                    {course.preview_video_id ? (
                                        <VimeoPlayer videoId={course.preview_video_id} className="h-full w-full" />
                                    ) : course.thumbnail_path ? (
                                        <img
                                            src={`/storage/${course.thumbnail_path}`}
                                            alt={course.title}
                                            className="h-full w-full object-cover"
                                        />
                                    ) : (
                                        <div className="flex h-full items-center justify-center bg-primary/5 text-primary">
                                            <i className="fas fa-book-open text-4xl"></i>
                                        </div>
                                    )}
                                </div>

                                <div className="space-y-6 p-6">
                                    <div>
                                        <p className="text-3xl font-bold text-primary font-montserrat">{priceLabel}</p>
                                        <p className="mt-2 text-sm text-gray-600">
                                            {course.is_free || Number(course.price) === 0
                                                ? 'Enroll directly and start learning.'
                                                : 'Purchase this course to gain access.'}
                                        </p>
                                    </div>

                                    <div className="space-y-3">
                                        {isEnrolled ? (
                                            <Link
                                                href={route('courses.learn', course.slug)}
                                                className="enterprise-button enterprise-button-primary w-full justify-center rounded-xl py-3"
                                            >
                                                <i className="fas fa-play-circle"></i>
                                                Continue Learning
                                            </Link>
                                        ) : course.is_free || course.price === 0 ? (
                                            <button
                                                onClick={handleEnroll}
                                                disabled={isEnrolling}
                                                className="enterprise-button enterprise-button-primary w-full justify-center rounded-xl py-3 disabled:cursor-not-allowed disabled:opacity-50"
                                            >
                                                {isEnrolling ? (
                                                    <>
                                                        <i className="fas fa-spinner fa-spin"></i>
                                                        Enrolling...
                                                    </>
                                                ) : (
                                                    <>
                                                        <i className="fas fa-graduation-cap"></i>
                                                        Enroll for Free
                                                    </>
                                                )}
                                            </button>
                                        ) : (
                                            <>
                                                <button
                                                    onClick={handleAddToCart}
                                                    className="enterprise-button enterprise-button-primary w-full justify-center rounded-xl py-3"
                                                >
                                                    <i className="fas fa-shopping-cart"></i>
                                                    Add to Cart
                                                </button>
                                                <button
                                                    onClick={handleBuyNow}
                                                    className="enterprise-button enterprise-button-outline w-full justify-center rounded-xl py-3"
                                                >
                                                    <i className="fas fa-bolt"></i>
                                                    Buy Now
                                                </button>
                                            </>
                                        )}
                                    </div>

                                    <div className="rounded-2xl bg-gray-50 p-4">
                                        <p className="text-sm font-semibold text-primary">Course summary</p>
                                        <div className="mt-3 space-y-3 text-sm text-gray-600">
                                            <div className="flex items-center justify-between gap-4">
                                                <span>Sections</span>
                                                <span className="font-medium text-primary">{totalModules}</span>
                                            </div>
                                            <div className="flex items-center justify-between gap-4">
                                                <span>Lessons</span>
                                                <span className="font-medium text-primary">{totalLessons}</span>
                                            </div>
                                            <div className="flex items-center justify-between gap-4">
                                                <span>Level</span>
                                                <span className="font-medium text-primary">{levelLabel}</span>
                                            </div>
                                            <div className="flex items-center justify-between gap-4">
                                                <span>Language</span>
                                                <span className="font-medium text-primary">{course.language || 'English'}</span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </aside>
                    </div>
                </div>
            </section>

            <section className="public-section bg-gray-50">
                <div className="section-shell">
                    <div className="grid gap-8 lg:grid-cols-[minmax(0,1.25fr)_360px]">
                        <div className="space-y-8">
                            <div className="rounded-[26px] border border-gray-200 bg-white p-6 shadow-[0_16px_40px_rgba(15,23,42,0.05)] md:p-8">
                                <h2 className="text-2xl font-bold text-primary font-montserrat">What you will learn</h2>
                                {course.outcomes && course.outcomes.length > 0 ? (
                                    <div className="mt-6 grid gap-4 md:grid-cols-2">
                                        {course.outcomes.map((outcome) => (
                                            <div key={outcome.id} className="flex items-start gap-3 text-sm text-gray-700">
                                                <span className="mt-0.5 flex h-6 w-6 items-center justify-center rounded-full bg-primary/10 text-primary">
                                                    <i className="fas fa-check text-[11px]"></i>
                                                </span>
                                                <span>{outcome.outcome}</span>
                                            </div>
                                        ))}
                                    </div>
                                ) : (
                                    <p className="mt-4 text-gray-600">Learning outcomes will be added to this course soon.</p>
                                )}
                            </div>

                            <div className="rounded-[26px] border border-gray-200 bg-white p-6 shadow-[0_16px_40px_rgba(15,23,42,0.05)] md:p-8">
                                <h2 className="text-2xl font-bold text-primary font-montserrat">Description</h2>
                                <div className="mt-4 prose prose-gray max-w-none">
                                    <p className="leading-8 text-gray-700">{course.description}</p>
                                </div>
                            </div>

                            <div className="rounded-[26px] border border-gray-200 bg-white p-6 shadow-[0_16px_40px_rgba(15,23,42,0.05)] md:p-8">
                                <div className="flex flex-col gap-2 sm:flex-row sm:items-end sm:justify-between">
                                    <div>
                                        <h2 className="text-2xl font-bold text-primary font-montserrat">Course content</h2>
                                        <p className="mt-1 text-sm text-gray-600">
                                            {totalModules} section{totalModules !== 1 ? 's' : ''} and {totalLessons} lesson{totalLessons !== 1 ? 's' : ''}
                                        </p>
                                    </div>
                                </div>

                                <div className="mt-6 overflow-hidden rounded-2xl border border-gray-200">
                                    {course.modules && course.modules.length > 0 ? (
                                        course.modules.map((module) => (
                                            <div key={module.id} className="border-b border-gray-200 last:border-b-0">
                                                <button
                                                    type="button"
                                                    className="flex w-full items-center justify-between px-5 py-4 text-left transition hover:bg-gray-50"
                                                    onClick={() => toggleModule(module.id)}
                                                >
                                                    <div className="flex items-center gap-3">
                                                        <i
                                                            className={`fas fa-chevron-right text-xs text-gray-500 transition ${
                                                                expandedModules.includes(module.id) ? 'rotate-90' : ''
                                                            }`}
                                                        ></i>
                                                        <span className="font-semibold text-primary">{module.title}</span>
                                                    </div>
                                                    <span className="text-sm text-gray-500">
                                                        {module.lessons?.length || 0} lesson{module.lessons?.length === 1 ? '' : 's'}
                                                    </span>
                                                </button>

                                                {expandedModules.includes(module.id) && (
                                                    <div className="border-t border-gray-100 bg-gray-50 px-5 py-4">
                                                        {module.lessons && module.lessons.length > 0 ? (
                                                            <ul className="space-y-3">
                                                                {module.lessons.map((lesson) => (
                                                                    <li key={lesson.id} className="flex items-center justify-between gap-4 text-sm">
                                                                        <div className="flex min-w-0 items-center gap-3 text-gray-700">
                                                                            <span className="flex h-7 w-7 shrink-0 items-center justify-center rounded-full bg-white border border-gray-200">
                                                                                {getLessonIcon(lesson.type)}
                                                                            </span>
                                                                            <span className="truncate">{lesson.title}</span>
                                                                        </div>
                                                                        {lesson.duration && (
                                                                            <span className="shrink-0 text-gray-500">{lesson.duration}</span>
                                                                        )}
                                                                    </li>
                                                                ))}
                                                            </ul>
                                                        ) : (
                                                            <p className="text-sm text-gray-500">No lessons have been added to this section yet.</p>
                                                        )}
                                                    </div>
                                                )}
                                            </div>
                                        ))
                                    ) : (
                                        <div className="px-5 py-8 text-sm text-gray-500">Course curriculum will be published here.</div>
                                    )}
                                </div>
                            </div>

                            {course.requirements && course.requirements.length > 0 && (
                                <div className="rounded-[26px] border border-gray-200 bg-white p-6 shadow-[0_16px_40px_rgba(15,23,42,0.05)] md:p-8">
                                    <h2 className="text-2xl font-bold text-primary font-montserrat">Requirements</h2>
                                    <ul className="mt-5 space-y-3">
                                        {course.requirements.map((requirement) => (
                                            <li key={requirement.id} className="flex items-start gap-3 text-gray-700">
                                                <span className="mt-2 h-2 w-2 shrink-0 rounded-full bg-gray-400"></span>
                                                <span>{requirement.requirement}</span>
                                            </li>
                                        ))}
                                    </ul>
                                </div>
                            )}

                            <div
                                id="instructor"
                                className="rounded-[26px] border border-gray-200 bg-white p-6 shadow-[0_16px_40px_rgba(15,23,42,0.05)] md:p-8"
                            >
                                <h2 className="text-2xl font-bold text-primary font-montserrat">Instructor</h2>
                                <div className="mt-6 flex flex-col gap-5 sm:flex-row">
                                    <img
                                        src={
                                            course.instructor.avatar_url ||
                                            `https://ui-avatars.com/api/?name=${encodeURIComponent(course.instructor.name)}&background=002147&color=fff&size=128`
                                        }
                                        alt={`${course.instructor.name} - Course Instructor`}
                                        className="h-20 w-20 rounded-full object-cover"
                                    />

                                    <div className="space-y-3">
                                        <div>
                                            <h3 className="text-xl font-bold text-primary font-montserrat">{course.instructor.name}</h3>
                                            {course.instructor.title && (
                                                <p className="mt-1 text-sm font-medium text-accent">{course.instructor.title}</p>
                                            )}
                                        </div>

                                        <div className="flex flex-wrap gap-4 text-sm text-gray-600">
                                            {instructorRating > 0 && (
                                                <span>
                                                    Instructor rating: {instructorRating.toFixed(1)}
                                                    <Link
                                                        href={route('instructors.ratings', { instructor: course.instructor.id })}
                                                        className="ml-2 font-medium text-primary hover:text-accent"
                                                    >
                                                        View ratings
                                                    </Link>
                                                </span>
                                            )}
                                            <span>{totalStudents} enrolled student{totalStudents !== 1 ? 's' : ''}</span>
                                        </div>

                                        <p className="max-w-3xl leading-7 text-gray-700">
                                            {course.instructor.bio ||
                                                'Instructor profile details will be shared here when available.'}
                                        </p>
                                    </div>
                                </div>
                            </div>

                            {isEnrolled && (
                                <div className="rounded-[26px] border border-gray-200 bg-white p-6 shadow-[0_16px_40px_rgba(15,23,42,0.05)] md:p-8">
                                    <h2 className="text-2xl font-bold text-primary font-montserrat">Leave a review</h2>
                                    <form
                                        className="mt-6"
                                        onSubmit={(e) => {
                                            e.preventDefault();
                                            const formData = new FormData(e.target as HTMLFormElement);
                                            router.post(route('instructors.rate', { instructor: course.instructor.id }), formData);
                                        }}
                                    >
                                        <input type="hidden" name="course_id" value={course.id} />
                                        <div className="mb-5">
                                            <label htmlFor="rating" className="mb-2 block text-sm font-medium text-gray-700">
                                                Your rating
                                            </label>
                                            <div className="star-rating flex items-center gap-1">
                                                {[1, 2, 3, 4, 5].map((star) => (
                                                    <button
                                                        key={star}
                                                        type="button"
                                                        className="text-2xl text-gray-300 hover:text-yellow-400"
                                                        onClick={() => {
                                                            const ratingInput = document.getElementById('rating') as HTMLInputElement;
                                                            if (ratingInput) {
                                                                ratingInput.value = star.toString();
                                                            }
                                                            const stars = document.querySelectorAll('.star-rating button');
                                                            stars.forEach((button, index) => {
                                                                if (index < star) {
                                                                    button.classList.add('text-yellow-400');
                                                                } else {
                                                                    button.classList.remove('text-yellow-400');
                                                                }
                                                            });
                                                        }}
                                                    >
                                                        &#9733;
                                                    </button>
                                                ))}
                                            </div>
                                            <input type="hidden" name="rating" id="rating" />
                                        </div>

                                        <div className="mb-5">
                                            <label htmlFor="review" className="mb-2 block text-sm font-medium text-gray-700">
                                                Your review
                                            </label>
                                            <textarea
                                                id="review"
                                                name="review"
                                                rows={4}
                                                className="w-full rounded-xl border-gray-300 shadow-sm focus:border-primary focus:ring-primary"
                                            ></textarea>
                                        </div>

                                        <button
                                            type="submit"
                                            className="enterprise-button enterprise-button-primary w-full justify-center rounded-xl py-3"
                                        >
                                            Submit Review
                                        </button>
                                    </form>
                                </div>
                            )}
                        </div>

                        <div className="space-y-6">
                            <div className="rounded-[26px] border border-gray-200 bg-white p-6 shadow-[0_16px_40px_rgba(15,23,42,0.05)]">
                                <h3 className="text-lg font-bold text-primary font-montserrat">Need help deciding?</h3>
                                <p className="mt-3 text-sm leading-6 text-gray-600">
                                    Review the curriculum, requirements, and instructor profile above before enrolling.
                                </p>
                                <div className="mt-5">
                                    <Link
                                        href={route('courses.index')}
                                        className="inline-flex items-center gap-2 text-sm font-semibold text-accent hover:text-accent/80"
                                    >
                                        Browse more courses
                                        <i className="fas fa-arrow-right text-xs"></i>
                                    </Link>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </section>
        </GuestLayout>
    );
}
