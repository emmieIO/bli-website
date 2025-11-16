import { Head, Link } from '@inertiajs/react';
import GuestLayout from '@/Layouts/GuestLayout';
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
}

export default function CourseDetail({ course }: CourseDetailProps) {
    const [expandedModules, setExpandedModules] = useState<number[]>([]);

    const toggleModule = (moduleId: number) => {
        setExpandedModules(prev =>
            prev.includes(moduleId)
                ? prev.filter(id => id !== moduleId)
                : [...prev, moduleId]
        );
    };

    const formatDate = (dateString: string) => {
        const date = new Date(dateString);
        return date.toLocaleDateString('en-US', { month: 'short', year: 'numeric' });
    };

    const getLessonIcon = (type: string) => {
        switch (type) {
            case 'video':
                return (
                    <svg className="w-3 h-3 text-gray-600" fill="currentColor" viewBox="0 0 20 20">
                        <path fillRule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM9.555 7.168A1 1 0 008 8v4a1 1 0 001.555.832l3-2a1 1 0 000-1.664l-3-2z" clipRule="evenodd" />
                    </svg>
                );
            case 'pdf':
                return (
                    <svg className="w-3 h-3 text-gray-600" fill="currentColor" viewBox="0 0 20 20">
                        <path fillRule="evenodd" d="M4 4a2 2 0 012-2h4.586A2 2 0 0112 2.586L15.414 6A2 2 0 0116 7.414V16a2 2 0 01-2 2H6a2 2 0 01-2-2V4z" clipRule="evenodd" />
                    </svg>
                );
            default:
                return (
                    <svg className="w-3 h-3 text-gray-600" fill="currentColor" viewBox="0 0 20 20">
                        <path fillRule="evenodd" d="M3 4a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zm0 4a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zm0 4a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1z" clipRule="evenodd" />
                    </svg>
                );
        }
    };

    const totalLessons = course.modules?.reduce((acc, module) => acc + (module.lessons?.length || 0), 0) || 0;
    const totalStudents = course.students?.length || 0;

    return (
        <GuestLayout>
            <Head title={course.title} />

            {/* Hero Section */}
            <div className="bg-gray-900 text-white">
                <div className="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
                    {/* Breadcrumb */}
                    <nav className="text-sm text-purple-300 mb-4">
                        <div className="flex items-center space-x-2">
                            <Link href={route('homepage')} className="hover:text-white transition-colors">
                                Home
                            </Link>
                            <span className="text-gray-400">&gt;</span>
                            <Link href={route('courses.index')} className="hover:text-white transition-colors">
                                Courses
                            </Link>
                            <span className="text-gray-400">&gt;</span>
                            <span className="text-gray-300">{course.category?.category || course.category?.name || 'Development'}</span>
                        </div>
                    </nav>

                    <div className="grid grid-cols-1 lg:grid-cols-3 gap-8">
                        {/* Left Content */}
                        <div className="lg:col-span-2">
                            {/* Course Title */}
                            <h1 className="text-2xl lg:text-4xl font-bold mb-4 leading-tight">{course.title}</h1>

                            {/* Course Subtitle */}
                            {course.subtitle && (
                                <p className="text-lg text-gray-300 mb-6 leading-relaxed">{course.subtitle}</p>
                            )}

                            {/* Course Meta Information */}
                            <div className="flex flex-wrap items-center gap-4 mb-6 text-sm">
                                <div className="flex items-center">
                                    <span className="text-yellow-400 font-bold mr-1">4.6</span>
                                    <div className="flex mr-2">
                                        {[1, 2, 3, 4, 5].map((star) => (
                                            <svg
                                                key={star}
                                                className={`w-4 h-4 ${star <= 4 ? 'text-yellow-400' : 'text-gray-400'}`}
                                                fill="currentColor"
                                                viewBox="0 0 20 20"
                                            >
                                                <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
                                            </svg>
                                        ))}
                                    </div>
                                    <span className="text-purple-300">({totalStudents.toLocaleString()} ratings)</span>
                                </div>
                                <span className="text-gray-300">{totalStudents.toLocaleString()} students</span>
                            </div>

                            <div className="flex flex-wrap items-center gap-4 text-sm text-gray-300">
                                <div className="flex items-center">
                                    <span>Created by</span>
                                    <a href="#instructor" className="text-purple-300 hover:text-white ml-1 underline">
                                        {course.instructor.name}
                                    </a>
                                </div>
                                <div className="flex items-center">
                                    <svg className="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                        <path fillRule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clipRule="evenodd" />
                                    </svg>
                                    <span>Last updated {formatDate(course.updated_at)}</span>
                                </div>
                                <div className="flex items-center">
                                    <svg className="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                        <path fillRule="evenodd" d="M7 2a1 1 0 011 1v1h3a1 1 0 110 2H9.578a18.87 18.87 0 01-1.724 4.78c.29.354.596.696.914 1.026a1 1 0 11-1.44 1.389c-.188-.196-.373-.396-.554-.6a26.86 26.86 0 01-3.622 2.91 1 1 0 11-.927-1.771 24.86 24.86 0 003.156-2.54 21.07 21.07 0 01-2.357-3.615A1 1 0 014 5h3V3a1 1 0 011-1z" clipRule="evenodd" />
                                    </svg>
                                    <span>{course.language || 'English'}</span>
                                </div>
                            </div>
                        </div>

                        {/* Course Video Preview (Hidden on mobile) */}
                        <div className="lg:col-span-1 hidden lg:block">
                            <div className="relative">
                                <div className="aspect-video bg-black rounded-lg overflow-hidden">
                                    {course.preview_video_id ? (
                                        <iframe
                                            src={`https://player.vimeo.com/video/${course.preview_video_id}?h=0&title=0&byline=0&portrait=0`}
                                            className="w-full h-full"
                                            frameBorder="0"
                                            allowFullScreen
                                        ></iframe>
                                    ) : (
                                        <div className="w-full h-full bg-gray-800 flex items-center justify-center">
                                            <div className="text-center">
                                                <svg className="w-16 h-16 text-white mx-auto mb-4" fill="currentColor" viewBox="0 0 20 20">
                                                    <path fillRule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM9.555 7.168A1 1 0 008 8v4a1 1 0 001.555.832l3-2a1 1 0 000-1.664l-3-2z" clipRule="evenodd" />
                                                </svg>
                                                <p className="text-white text-sm">Preview this course</p>
                                            </div>
                                        </div>
                                    )}
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {/* Main Content */}
            <div className="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
                <div className="grid grid-cols-1 lg:grid-cols-3 gap-8">
                    {/* Left Content */}
                    <div className="lg:col-span-2 space-y-8">
                        {/* Mobile Video Preview */}
                        <div className="lg:hidden">
                            <div className="aspect-video bg-black rounded-lg overflow-hidden">
                                {course.preview_video_id ? (
                                    <iframe
                                        src={`https://player.vimeo.com/video/${course.preview_video_id}?h=0&title=0&byline=0&portrait=0`}
                                        className="w-full h-full"
                                        frameBorder="0"
                                        allowFullScreen
                                    ></iframe>
                                ) : (
                                    <div className="w-full h-full bg-gray-800 flex items-center justify-center">
                                        <div className="text-center">
                                            <svg className="w-16 h-16 text-white mx-auto mb-4" fill="currentColor" viewBox="0 0 20 20">
                                                <path fillRule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM9.555 7.168A1 1 0 008 8v4a1 1 0 001.555.832l3-2a1 1 0 000-1.664l-3-2z" clipRule="evenodd" />
                                            </svg>
                                            <p className="text-white text-sm">Preview this course</p>
                                        </div>
                                    </div>
                                )}
                            </div>
                        </div>

                        {/* What you'll learn */}
                        <div className="bg-gray-50 border border-gray-200 rounded-lg p-6">
                            <h2 className="text-xl font-bold text-gray-900 mb-4">What you'll learn</h2>
                            {course.outcomes && course.outcomes.length > 0 ? (
                                <div className="grid grid-cols-1 md:grid-cols-2 gap-3">
                                    {course.outcomes.map((outcome) => (
                                        <div key={outcome.id} className="flex items-start">
                                            <svg className="w-4 h-4 text-gray-600 mt-1 mr-3 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                                                <path fillRule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clipRule="evenodd" />
                                            </svg>
                                            <span className="text-sm text-gray-700">{outcome.outcome}</span>
                                        </div>
                                    ))}
                                </div>
                            ) : (
                                <p className="text-gray-600">Learning outcomes will be updated soon.</p>
                            )}
                        </div>

                        {/* Course Content */}
                        <div className="space-y-8">
                            {/* Course Description */}
                            <div>
                                <h2 className="text-xl font-bold text-gray-900 mb-4">Description</h2>
                                <div className="prose prose-gray max-w-none">
                                    <p className="text-gray-700 leading-relaxed">{course.description}</p>
                                </div>
                            </div>

                            {/* Course Curriculum */}
                            <div>
                                <div className="flex items-center justify-between mb-4">
                                    <h2 className="text-xl font-bold text-gray-900">Course content</h2>
                                    <span className="text-sm text-gray-600">
                                        {course.modules?.length || 0} sections • {totalLessons} lectures
                                    </span>
                                </div>

                                {/* Curriculum */}
                                <div className="border border-gray-200 rounded-lg">
                                    {course.modules && course.modules.length > 0 ? (
                                        course.modules.map((module) => (
                                            <div key={module.id} className="border-b border-gray-200 last:border-b-0">
                                                <button
                                                    className="w-full text-left px-6 py-4 hover:bg-gray-50 focus:bg-gray-50 transition-colors"
                                                    onClick={() => toggleModule(module.id)}
                                                >
                                                    <div className="flex items-center justify-between">
                                                        <div className="flex items-center">
                                                            <svg
                                                                className={`w-4 h-4 text-gray-600 mr-3 transform transition-transform ${
                                                                    expandedModules.includes(module.id) ? 'rotate-90' : ''
                                                                }`}
                                                                viewBox="0 0 16 16"
                                                                fill="none"
                                                                stroke="currentColor"
                                                            >
                                                                <path strokeLinecap="round" strokeLinejoin="round" strokeWidth="2" d="M6 4l4 4-4 4" />
                                                            </svg>
                                                            <h3 className="font-medium text-gray-900">{module.title}</h3>
                                                        </div>
                                                        <span className="text-sm text-gray-500">{module.lessons?.length || 0} lectures</span>
                                                    </div>
                                                </button>
                                                {expandedModules.includes(module.id) && (
                                                    <div className="border-t border-gray-100">
                                                        <div className="px-6 py-4 bg-gray-50">
                                                            {module.lessons && module.lessons.length > 0 ? (
                                                                <ul className="space-y-2">
                                                                    {module.lessons.map((lesson) => (
                                                                        <li key={lesson.id} className="flex items-center justify-between text-sm">
                                                                            <div className="flex items-center">
                                                                                <div className="w-6 h-6 rounded bg-white border border-gray-200 flex items-center justify-center mr-3">
                                                                                    {getLessonIcon(lesson.type)}
                                                                                </div>
                                                                                <span className="text-gray-700">{lesson.title}</span>
                                                                            </div>
                                                                            {lesson.duration && <span className="text-gray-500">{lesson.duration}</span>}
                                                                        </li>
                                                                    ))}
                                                                </ul>
                                                            ) : (
                                                                <p className="text-gray-500 text-sm">No lessons available</p>
                                                            )}
                                                        </div>
                                                    </div>
                                                )}
                                            </div>
                                        ))
                                    ) : (
                                        <div className="p-6 text-center">
                                            <p className="text-gray-500">Course curriculum coming soon</p>
                                        </div>
                                    )}
                                </div>
                            </div>

                            {/* Requirements */}
                            {course.requirements && course.requirements.length > 0 && (
                                <div>
                                    <h2 className="text-xl font-bold text-gray-900 mb-4">Requirements</h2>
                                    <ul className="space-y-2">
                                        {course.requirements.map((requirement) => (
                                            <li key={requirement.id} className="flex items-start">
                                                <span className="w-2 h-2 bg-gray-400 rounded-full mt-2 mr-3 flex-shrink-0"></span>
                                                <span className="text-gray-700">{requirement.requirement}</span>
                                            </li>
                                        ))}
                                    </ul>
                                </div>
                            )}

                            {/* Instructor */}
                            <div id="instructor">
                                <h2 className="text-xl font-bold text-gray-900 mb-4">Instructor</h2>
                                <div className="border border-gray-200 rounded-lg p-6">
                                    <div className="flex items-start space-x-4">
                                        <img
                                            src={
                                                course.instructor.avatar_url ||
                                                `https://ui-avatars.com/api/?name=${encodeURIComponent(course.instructor.name)}&background=6366f1&color=fff&size=128`
                                            }
                                            alt={`${course.instructor.name} - Course Instructor`}
                                            className="w-16 h-16 rounded-full object-cover"
                                        />
                                        <div className="flex-1">
                                            <h3 className="text-lg font-semibold text-gray-900 mb-1">{course.instructor.name}</h3>
                                            {course.instructor.title && (
                                                <p className="text-purple-600 font-medium mb-2">{course.instructor.title}</p>
                                            )}

                                            <div className="flex items-center space-x-4 text-sm text-gray-600 mb-3">
                                                <div className="flex items-center">
                                                    <svg className="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                                        <path fillRule="evenodd" d="M11.49 3.17c-.38-1.56-2.6-1.56-2.98 0a1.532 1.532 0 01-2.286.948c-1.372-.836-2.942.734-2.106 2.106.54.886.061 2.042-.947 2.287-1.561.379-1.561 2.6 0 2.978a1.532 1.532 0 01.947 2.287c-.836 1.372.734 2.942 2.106 2.106a1.532 1.532 0 012.287.947c.379 1.561 2.6 1.561 2.978 0a1.533 1.533 0 012.287-.947c1.372.836 2.942-.734 2.106-2.106a1.533 1.533 0 01.947-2.287c1.561-.379 1.561-2.6 0-2.978a1.532 1.532 0 01-.947-2.287c.836-1.372-.734-2.942-2.106-2.106a1.532 1.532 0 01-2.287-.947zM10 13a3 3 0 100-6 3 3 0 000 6z" clipRule="evenodd" />
                                                    </svg>
                                                    <span>Instructor Rating: 4.6</span>
                                                </div>
                                                <div className="flex items-center">
                                                    <svg className="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                                        <path d="M13 6a3 3 0 11-6 0 3 3 0 016 0zM18 8a2 2 0 11-4 0 2 2 0 014 0zM14 15a4 4 0 00-8 0v3h8v-3z" />
                                                    </svg>
                                                    <span>{totalStudents.toLocaleString()} Students</span>
                                                </div>
                                                <div className="flex items-center">
                                                    <svg className="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                                        <path fillRule="evenodd" d="M3 4a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zm0 4a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zm0 4a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1z" clipRule="evenodd" />
                                                    </svg>
                                                    <span>1 Course</span>
                                                </div>
                                            </div>

                                            <p className="text-gray-700 text-sm leading-relaxed">
                                                {course.instructor.bio ||
                                                    'Experienced instructor passionate about sharing knowledge and helping students achieve their learning goals.'}
                                            </p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    {/* Sidebar */}
                    <div className="lg:col-span-1">
                        <div className="sticky top-8">
                            <div className="bg-white border border-gray-200 rounded-lg shadow-lg p-6">
                                {/* Course Price */}
                                <div className="text-center mb-6">
                                    <div className="flex items-baseline justify-center mb-2">
                                        <span className="text-3xl font-bold text-gray-900">₦{course.price.toLocaleString()}</span>
                                        {course.price > 0 && (
                                            <span className="text-lg text-gray-500 line-through ml-2">
                                                ₦{(course.price * 1.5).toLocaleString()}
                                            </span>
                                        )}
                                    </div>
                                    {course.price > 0 ? (
                                        <div className="flex items-center justify-center">
                                            <span className="bg-yellow-200 text-yellow-800 text-xs font-semibold px-2 py-1 rounded-full">83% off</span>
                                            <span className="text-red-600 text-sm font-medium ml-2">2 days left at this price!</span>
                                        </div>
                                    ) : (
                                        <span className="text-green-600 font-semibold">Free</span>
                                    )}
                                </div>

                                {/* CTA Buttons */}
                                <div className="space-y-3 mb-6">
                                    <button className="w-full bg-primary hover:bg-primary/90 text-white font-bold py-3 px-4 rounded transition-colors">
                                        Add to cart
                                    </button>
                                    <button className="w-full border border-gray-300 hover:bg-gray-50 text-gray-900 font-bold py-3 px-4 rounded transition-colors">
                                        Buy now
                                    </button>
                                </div>

                                {/* Course Includes */}
                                <div className="space-y-3 text-sm">
                                    <p className="font-semibold text-gray-900 mb-3">This course includes:</p>
                                    <div className="space-y-2">
                                        <div className="flex items-center">
                                            <svg className="w-4 h-4 text-gray-600 mr-3" fill="currentColor" viewBox="0 0 20 20">
                                                <path fillRule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-12a1 1 0 10-2 0v4a1 1 0 00.293.707l2.828 2.829a1 1 0 101.415-1.415L11 9.586V6z" clipRule="evenodd" />
                                            </svg>
                                            <span className="text-gray-700">{totalLessons} on-demand videos</span>
                                        </div>
                                        <div className="flex items-center">
                                            <svg className="w-4 h-4 text-gray-600 mr-3" fill="currentColor" viewBox="0 0 20 20">
                                                <path fillRule="evenodd" d="M3 17a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zm3.293-7.707a1 1 0 011.414 0L9 10.586V3a1 1 0 112 0v7.586l1.293-1.293a1 1 0 111.414 1.414l-3 3a1 1 0 01-1.414 0l-3-3a1 1 0 010-1.414z" clipRule="evenodd" />
                                            </svg>
                                            <span className="text-gray-700">Downloadable resources</span>
                                        </div>
                                        <div className="flex items-center">
                                            <svg className="w-4 h-4 text-gray-600 mr-3" fill="currentColor" viewBox="0 0 20 20">
                                                <path d="M2 6a2 2 0 012-2h6a2 2 0 012 2v6a2 2 0 01-2 2H4a2 2 0 01-2-2V6zM14.553 7.106A1 1 0 0014 8v4a1 1 0 00.553.894l2 1A1 1 0 0018 13V7a1 1 0 00-1.447-.894l-2 1z" />
                                            </svg>
                                            <span className="text-gray-700">Access on mobile and TV</span>
                                        </div>
                                        <div className="flex items-center">
                                            <svg className="w-4 h-4 text-gray-600 mr-3" fill="currentColor" viewBox="0 0 20 20">
                                                <path fillRule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clipRule="evenodd" />
                                            </svg>
                                            <span className="text-gray-700">Full lifetime access</span>
                                        </div>
                                        <div className="flex items-center">
                                            <svg className="w-4 h-4 text-gray-600 mr-3" fill="currentColor" viewBox="0 0 20 20">
                                                <path fillRule="evenodd" d="M6.267 3.455a3.066 3.066 0 001.745-.723 3.066 3.066 0 013.976 0 3.066 3.066 0 001.745.723 3.066 3.066 0 012.812 2.812c.051.643.304 1.254.723 1.745a3.066 3.066 0 010 3.976 3.066 3.066 0 00-.723 1.745 3.066 3.066 0 01-2.812 2.812 3.066 3.066 0 00-1.745.723 3.066 3.066 0 01-3.976 0 3.066 3.066 0 00-1.745-.723 3.066 3.066 0 01-2.812-2.812 3.066 3.066 0 00-.723-1.745 3.066 3.066 0 010-3.976 3.066 3.066 0 00.723-1.745 3.066 3.066 0 012.812-2.812zm7.44 5.252a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clipRule="evenodd" />
                                            </svg>
                                            <span className="text-gray-700">Certificate of completion</span>
                                        </div>
                                    </div>
                                </div>

                                {/* 30-day guarantee */}
                                <div className="mt-6 pt-6 border-t border-gray-200 text-center">
                                    <p className="text-xs text-gray-600">30-Day Money-Back Guarantee</p>
                                </div>
                            </div>

                            {/* Course Stats */}
                            <div className="bg-white border border-gray-200 rounded-lg shadow-lg p-6 mt-6">
                                <h3 className="font-semibold text-gray-900 mb-4">Course Stats</h3>
                                <div className="space-y-3 text-sm">
                                    <div className="flex justify-between">
                                        <span className="text-gray-600">Skill level</span>
                                        <span className="font-medium">{course.level?.value ? course.level.value.charAt(0).toUpperCase() + course.level.value.slice(1) : 'All Levels'}</span>
                                    </div>
                                    <div className="flex justify-between">
                                        <span className="text-gray-600">Students</span>
                                        <span className="font-medium">{totalStudents.toLocaleString()}</span>
                                    </div>
                                    <div className="flex justify-between">
                                        <span className="text-gray-600">Languages</span>
                                        <span className="font-medium">{course.language || 'English'}</span>
                                    </div>
                                    <div className="flex justify-between">
                                        <span className="text-gray-600">Captions</span>
                                        <span className="font-medium">Yes</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </GuestLayout>
    );
}
