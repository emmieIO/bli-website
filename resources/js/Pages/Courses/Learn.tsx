import { Head, Link, router } from '@inertiajs/react';
import DashboardLayout from '@/Layouts/DashboardLayout';
import { useState, useRef, useEffect } from 'react';

interface Lesson {
    id: number;
    title: string;
    subtitle?: string;
    description?: string;
    type: 'video' | 'pdf' | 'text';
    video_url?: string;
    duration?: string | number;
    order: number;
    completed?: boolean;
    resources?: any[];
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

interface Course {
    id: number;
    slug: string;
    title: string;
    description: string;
    modules?: Module[];
    outcomes?: Outcome[];
}

interface LearnProps {
    course: Course;
    currentLesson: Lesson | null;
    previousLesson: Lesson | null;
    nextLesson: Lesson | null;
    progress: number;
    totalLessons: number;
    completedLessons: number;
}

export default function Learn({
    course,
    currentLesson,
    previousLesson,
    nextLesson,
    progress,
    totalLessons,
    completedLessons,
}: LearnProps) {
    const [activeTab, setActiveTab] = useState('overview');
    const [expandedModules, setExpandedModules] = useState<number[]>([]);
    const [sidebarOpen, setSidebarOpen] = useState(true);
    const [isCompleted, setIsCompleted] = useState(currentLesson?.completed || false);
    const [watchProgress, setWatchProgress] = useState(0);
    const [playbackSpeed, setPlaybackSpeed] = useState(1);
    const [isBookmarked, setIsBookmarked] = useState(false);

    const videoRef = useRef<HTMLVideoElement>(null);
    const progressIntervalRef = useRef<NodeJS.Timeout>();

    useEffect(() => {
        // Open first module by default
        if (course.modules && course.modules.length > 0 && expandedModules.length === 0) {
            setExpandedModules([course.modules[0].id]);
        }
    }, [course.modules]);

    useEffect(() => {
        // Load lesson progress
        if (currentLesson) {
            loadLessonProgress(currentLesson.id);
            setIsCompleted(currentLesson.completed || false);
        }
    }, [currentLesson]);

    useEffect(() => {
        const video = videoRef.current;
        if (!video) return;

        const handleTimeUpdate = () => {
            if (video.duration) {
                const progress = (video.currentTime / video.duration) * 100;
                setWatchProgress(progress);

                // Auto-save progress every 10 seconds
                clearTimeout(progressIntervalRef.current);
                progressIntervalRef.current = setTimeout(() => {
                    saveProgress(false);
                }, 10000);
            }
        };

        const handleEnded = () => {
            saveProgress(true);
            if (!isCompleted) {
                markAsComplete();
            }
        };

        video.addEventListener('timeupdate', handleTimeUpdate);
        video.addEventListener('ended', handleEnded);

        return () => {
            video.removeEventListener('timeupdate', handleTimeUpdate);
            video.removeEventListener('ended', handleEnded);
            if (progressIntervalRef.current) {
                clearTimeout(progressIntervalRef.current);
            }
        };
    }, [isCompleted, currentLesson]);

    const toggleModule = (moduleId: number) => {
        setExpandedModules((prev) =>
            prev.includes(moduleId) ? prev.filter((id) => id !== moduleId) : [...prev, moduleId]
        );
    };

    const loadLessonProgress = async (lessonId: number) => {
        try {
            const response = await fetch(`/api/lessons/${lessonId}/progress`);
            const data = await response.json();

            if (data.progress) {
                if (data.progress.is_completed) {
                    setIsCompleted(true);
                }

                if (data.progress.last_position && videoRef.current) {
                    videoRef.current.currentTime = data.progress.last_position;
                }
            }
        } catch (error) {
            console.error('Failed to load lesson progress:', error);
        }
    };

    const saveProgress = async (isCompletedFlag: boolean) => {
        if (!currentLesson || !videoRef.current) return;

        try {
            await fetch(`/api/lessons/${currentLesson.id}/progress`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector<HTMLMetaElement>('meta[name="csrf-token"]')?.content || '',
                },
                body: JSON.stringify({
                    current_time: videoRef.current.currentTime,
                    duration: videoRef.current.duration,
                    is_completed: isCompletedFlag,
                }),
            });
        } catch (error) {
            console.error('Failed to save progress:', error);
        }
    };

    const markAsComplete = async () => {
        if (!currentLesson || isCompleted) return;

        try {
            const response = await fetch(`/api/lessons/${currentLesson.id}/complete`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector<HTMLMetaElement>('meta[name="csrf-token"]')?.content || '',
                },
            });

            const data = await response.json();

            if (data.success) {
                setIsCompleted(true);

                if (nextLesson) {
                    setTimeout(() => {
                        if (confirm('Lesson completed! Would you like to proceed to the next lesson?')) {
                            router.visit(route('courses.learn', { course: course.slug, lesson: nextLesson.id }));
                        }
                    }, 2000);
                }
            }
        } catch (error) {
            console.error('Failed to mark lesson as complete:', error);
        }
    };

    const changePlaybackSpeed = (speed: number) => {
        setPlaybackSpeed(speed);
        if (videoRef.current) {
            videoRef.current.playbackRate = speed;
        }
    };

    const toggleBookmark = () => {
        setIsBookmarked(!isBookmarked);
        // TODO: Implement API call to save bookmark
    };

    const formatDuration = (duration: string | number | undefined) => {
        if (!duration) return '';
        if (typeof duration === 'string') return duration;

        const hours = Math.floor(duration / 3600);
        const minutes = Math.floor((duration % 3600) / 60);
        const seconds = duration % 60;

        return hours > 0
            ? `${hours}:${minutes.toString().padStart(2, '0')}:${seconds.toString().padStart(2, '0')}`
            : `${minutes}:${seconds.toString().padStart(2, '0')}`;
    };

    const getLessonIcon = (type: string) => {
        switch (type) {
            case 'video':
                return (
                    <svg className="w-4 h-4 text-gray-400" fill="currentColor" viewBox="0 0 20 20">
                        <path fillRule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM9.555 7.168A1 1 0 008 8v4a1 1 0 001.555.832l3-2a1 1 0 000-1.664l-3-2z" clipRule="evenodd" />
                    </svg>
                );
            case 'pdf':
                return (
                    <svg className="w-4 h-4 text-gray-400" fill="currentColor" viewBox="0 0 20 20">
                        <path fillRule="evenodd" d="M4 4a2 2 0 012-2h4.586A2 2 0 0112 2.586L15.414 6A2 2 0 0116 7.414V16a2 2 0 01-2 2H6a2 2 0 01-2-2V4z" clipRule="evenodd" />
                    </svg>
                );
            default:
                return (
                    <svg className="w-4 h-4 text-gray-400" fill="currentColor" viewBox="0 0 20 20">
                        <path fillRule="evenodd" d="M3 4a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zm0 4a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zm0 4a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1z" clipRule="evenodd" />
                    </svg>
                );
        }
    };

    return (
        <DashboardLayout>
            <Head title={`${course.title} - Learn`} />

            <div className="min-h-screen bg-gray-100">
                {/* Course Header */}
                <div className="bg-white shadow-sm border-b">
                    <div className="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                        <div className="flex items-center justify-between h-16">
                            {/* Course Title & Progress */}
                            <div className="flex items-center space-x-4">
                                <Link
                                    href={route('courses.show', course.slug)}
                                    className="text-gray-600 hover:text-primary transition-colors"
                                >
                                    <svg className="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path strokeLinecap="round" strokeLinejoin="round" strokeWidth="2" d="M15 19l-7-7 7-7" />
                                    </svg>
                                </Link>
                                <div>
                                    <h1 className="text-lg font-semibold text-gray-900 truncate max-w-md">{course.title}</h1>
                                    <div className="flex items-center mt-1">
                                        <div className="w-32 bg-gray-200 rounded-full h-2 mr-3">
                                            <div className="bg-primary h-2 rounded-full" style={{ width: `${progress}%` }}></div>
                                        </div>
                                        <span className="text-sm text-gray-600">{progress}% complete</span>
                                    </div>
                                </div>
                            </div>

                            {/* Action Buttons */}
                            <div className="flex items-center space-x-4">
                                <button
                                    onClick={() => setSidebarOpen(!sidebarOpen)}
                                    className="p-2 text-gray-600 hover:text-primary transition-colors lg:hidden"
                                >
                                    <svg className="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path strokeLinecap="round" strokeLinejoin="round" strokeWidth="2" d="M4 6h16M4 12h16M4 18h16" />
                                    </svg>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>

                <div className="flex h-[calc(100vh-4rem)]">
                    {/* Main Content Area */}
                    <div className="flex-1 flex flex-col overflow-hidden">
                        {/* Video Player */}
                        <div className="bg-gray-900 rounded-lg overflow-hidden relative">
                            {currentLesson && currentLesson.video_url ? (
                                <div className="aspect-video relative">
                                    <video
                                        ref={videoRef}
                                        className="w-full h-full"
                                        controls
                                        preload="metadata"
                                        poster="/images/video-placeholder.jpg"
                                    >
                                        <source src={currentLesson.video_url} type="video/mp4" />
                                        Your browser does not support the video tag.
                                    </video>

                                    {/* Progress Bar */}
                                    <div className="absolute bottom-0 left-0 right-0 h-1 bg-gray-600">
                                        <div
                                            className="h-full bg-blue-500 transition-all duration-300"
                                            style={{ width: `${watchProgress}%` }}
                                        ></div>
                                    </div>
                                </div>
                            ) : (
                                <div className="aspect-video flex items-center justify-center text-gray-400">
                                    <div className="text-center">
                                        <svg className="w-16 h-16 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path
                                                strokeLinecap="round"
                                                strokeLinejoin="round"
                                                strokeWidth="2"
                                                d="M14.828 14.828a4 4 0 01-5.656 0M9 10h1m4 0h1m-6 4h8m2 4H7a2 2 0 01-2-2V8a2 2 0 012-2h10a2 2 0 012 2v8a2 2 0 01-2 2z"
                                            />
                                        </svg>
                                        <p>No video available for this lesson</p>
                                    </div>
                                </div>
                            )}
                        </div>

                        {/* Lesson Actions */}
                        {currentLesson && (
                            <div className="flex items-center justify-between mt-4 px-6 py-4 bg-gray-50 rounded-lg">
                                <div className="flex items-center space-x-4">
                                    {/* Mark Complete Button */}
                                    <button
                                        onClick={markAsComplete}
                                        disabled={isCompleted}
                                        className={`flex items-center px-4 py-2 rounded-lg transition-colors ${
                                            isCompleted
                                                ? 'bg-gray-500 text-white cursor-not-allowed'
                                                : 'bg-green-600 text-white hover:bg-green-700'
                                        }`}
                                    >
                                        <svg className="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path strokeLinecap="round" strokeLinejoin="round" strokeWidth="2" d="M5 13l4 4L19 7" />
                                        </svg>
                                        <span>{isCompleted ? 'Completed' : 'Mark as Complete'}</span>
                                    </button>

                                    {/* Bookmark Button */}
                                    <button
                                        onClick={toggleBookmark}
                                        className={`flex items-center px-4 py-2 rounded-lg transition-colors ${
                                            isBookmarked ? 'bg-yellow-700 text-white' : 'bg-yellow-600 text-white hover:bg-yellow-700'
                                        }`}
                                    >
                                        <svg className="w-4 h-4 mr-2" fill={isBookmarked ? 'currentColor' : 'none'} stroke="currentColor" viewBox="0 0 24 24">
                                            <path strokeLinecap="round" strokeLinejoin="round" strokeWidth="2" d="M5 5a2 2 0 012-2h10a2 2 0 012 2v16l-7-3.5L5 21V5z" />
                                        </svg>
                                        {isBookmarked ? 'Bookmarked' : 'Bookmark'}
                                    </button>
                                </div>

                                {/* Lesson Duration */}
                                {currentLesson.duration && (
                                    <div className="text-sm text-gray-500">Duration: {formatDuration(currentLesson.duration)}</div>
                                )}
                            </div>
                        )}

                        {/* Lesson Content */}
                        <div className="flex-1 bg-white overflow-hidden flex flex-col">
                            {/* Lesson Navigation */}
                            <div className="border-b border-gray-200 px-6 py-4">
                                <div className="flex items-center justify-between">
                                    <div>
                                        {previousLesson && (
                                            <Link
                                                href={route('courses.learn', { course: course.slug, lesson: previousLesson.id })}
                                                className="inline-flex items-center text-sm text-gray-600 hover:text-primary transition-colors"
                                            >
                                                <svg className="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path strokeLinecap="round" strokeLinejoin="round" strokeWidth="2" d="M15 19l-7-7 7-7" />
                                                </svg>
                                                Previous
                                            </Link>
                                        )}
                                    </div>
                                    <div className="text-center">
                                        <h2 className="text-xl font-semibold text-gray-900">
                                            {currentLesson ? currentLesson.title : 'Course Overview'}
                                        </h2>
                                        {currentLesson?.subtitle && <p className="text-sm text-gray-600 mt-1">{currentLesson.subtitle}</p>}
                                    </div>
                                    <div>
                                        {nextLesson && (
                                            <Link
                                                href={route('courses.learn', { course: course.slug, lesson: nextLesson.id })}
                                                className="inline-flex items-center text-sm text-gray-600 hover:text-primary transition-colors"
                                            >
                                                Next
                                                <svg className="w-4 h-4 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path strokeLinecap="round" strokeLinejoin="round" strokeWidth="2" d="M9 5l7 7-7 7" />
                                                </svg>
                                            </Link>
                                        )}
                                    </div>
                                </div>
                            </div>

                            {/* Tabs */}
                            <div className="border-b border-gray-200">
                                <nav className="-mb-px flex px-6">
                                    {['overview', 'notes', 'resources', 'discussion'].map((tab) => (
                                        <button
                                            key={tab}
                                            onClick={() => setActiveTab(tab)}
                                            className={`py-4 px-1 border-b-2 font-medium text-sm mr-8 capitalize ${
                                                activeTab === tab
                                                    ? 'border-primary text-primary'
                                                    : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300'
                                            }`}
                                        >
                                            {tab}
                                        </button>
                                    ))}
                                </nav>
                            </div>

                            {/* Tab Content */}
                            <div className="flex-1 overflow-y-auto p-6">
                                {/* Overview Tab */}
                                {activeTab === 'overview' && (
                                    <div>
                                        {currentLesson ? (
                                            <div className="prose max-w-none">
                                                <h3>Lesson Description</h3>
                                                <p>{currentLesson.description || 'No description available for this lesson.'}</p>

                                                {currentLesson.duration && (
                                                    <div className="bg-blue-50 p-4 rounded-lg mt-4">
                                                        <div className="flex items-center">
                                                            <svg className="w-5 h-5 text-blue-600 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                                                <path fillRule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-12a1 1 0 10-2 0v4a1 1 0 00.293.707l2.828 2.829a1 1 0 101.415-1.415L11 9.586V6z" clipRule="evenodd" />
                                                            </svg>
                                                            <span className="text-blue-800 font-medium">Duration: {formatDuration(currentLesson.duration)}</span>
                                                        </div>
                                                    </div>
                                                )}
                                            </div>
                                        ) : (
                                            <div className="text-center py-12">
                                                <h3 className="text-lg font-semibold text-gray-900 mb-4">Welcome to {course.title}</h3>
                                                <p className="text-gray-600 mb-6">{course.description}</p>
                                                {course.outcomes && course.outcomes.length > 0 && (
                                                    <div className="bg-green-50 p-6 rounded-lg">
                                                        <h4 className="font-semibold text-green-800 mb-2">What you'll learn:</h4>
                                                        <ul className="text-left text-green-700 space-y-1 max-w-2xl mx-auto">
                                                            {course.outcomes.slice(0, 5).map((outcome) => (
                                                                <li key={outcome.id} className="flex items-start">
                                                                    <svg className="w-4 h-4 text-green-600 mt-0.5 mr-2 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                                                                        <path fillRule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clipRule="evenodd" />
                                                                    </svg>
                                                                    {outcome.outcome}
                                                                </li>
                                                            ))}
                                                        </ul>
                                                    </div>
                                                )}
                                            </div>
                                        )}
                                    </div>
                                )}

                                {/* Notes Tab */}
                                {activeTab === 'notes' && (
                                    <div className="max-w-4xl">
                                        <div className="flex items-center justify-between mb-6">
                                            <h3 className="text-lg font-semibold text-gray-900">My Notes</h3>
                                            <button className="bg-primary text-white px-4 py-2 rounded-lg font-medium hover:bg-primary/90 transition-colors">
                                                Add Note
                                            </button>
                                        </div>

                                        {/* Empty State */}
                                        <div className="text-center py-12 text-gray-500">
                                            <svg className="w-12 h-12 mx-auto mb-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path strokeLinecap="round" strokeLinejoin="round" strokeWidth="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                            </svg>
                                            <p>No notes yet. Start taking notes as you learn!</p>
                                        </div>
                                    </div>
                                )}

                                {/* Resources Tab */}
                                {activeTab === 'resources' && (
                                    <div className="max-w-4xl">
                                        <h3 className="text-lg font-semibold text-gray-900 mb-6">Course Resources</h3>

                                        <div className="text-center py-12 text-gray-500">
                                            <svg className="w-12 h-12 mx-auto mb-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path strokeLinecap="round" strokeLinejoin="round" strokeWidth="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M9 19l3 3m0 0l3-3m-3 3V10" />
                                            </svg>
                                            <p>No resources available for this lesson.</p>
                                        </div>
                                    </div>
                                )}

                                {/* Discussion Tab */}
                                {activeTab === 'discussion' && (
                                    <div className="max-w-4xl">
                                        <h3 className="text-lg font-semibold text-gray-900 mb-6">Discussion & Q&A</h3>
                                        <div className="text-center py-12 text-gray-500">
                                            <svg className="w-12 h-12 mx-auto mb-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path strokeLinecap="round" strokeLinejoin="round" strokeWidth="2" d="M17 8h2a2 2 0 012 2v6a2 2 0 01-2 2h-2v4l-4-4H9a1.994 1.994 0 01-1.414-.586m0 0L11 14h4a2 2 0 002-2V6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2v4l.586-.586z" />
                                            </svg>
                                            <p>Discussion feature coming soon!</p>
                                        </div>
                                    </div>
                                )}
                            </div>
                        </div>
                    </div>

                    {/* Course Navigation Sidebar */}
                    <div className={`w-80 bg-white border-l border-gray-200 flex flex-col ${!sidebarOpen ? 'hidden' : ''} lg:flex`}>
                        {/* Course Progress */}
                        <div className="p-6 border-b border-gray-200">
                            <h3 className="font-semibold text-gray-900 mb-4">Course Progress</h3>
                            <div className="space-y-2">
                                <div className="flex justify-between text-sm">
                                    <span>
                                        {completedLessons}/{totalLessons} lessons
                                    </span>
                                    <span>{progress}%</span>
                                </div>
                                <div className="w-full bg-gray-200 rounded-full h-2">
                                    <div className="bg-primary h-2 rounded-full transition-all" style={{ width: `${progress}%` }}></div>
                                </div>
                            </div>
                        </div>

                        {/* Course Curriculum */}
                        <div className="flex-1 overflow-y-auto">
                            {course.modules && course.modules.length > 0 ? (
                                course.modules.map((module) => (
                                    <div key={module.id} className="border-b border-gray-200">
                                        <button
                                            onClick={() => toggleModule(module.id)}
                                            className="w-full text-left p-4 hover:bg-gray-50 transition-colors"
                                        >
                                            <div className="flex items-center justify-between">
                                                <div>
                                                    <h4 className="font-medium text-gray-900">{module.title}</h4>
                                                    <p className="text-sm text-gray-600 mt-1">{module.lessons?.length || 0} lessons</p>
                                                </div>
                                                <svg
                                                    className={`w-4 h-4 text-gray-400 transform transition-transform ${
                                                        expandedModules.includes(module.id) ? 'rotate-90' : ''
                                                    }`}
                                                    viewBox="0 0 16 16"
                                                    fill="none"
                                                    stroke="currentColor"
                                                >
                                                    <path strokeLinecap="round" strokeLinejoin="round" strokeWidth="2" d="M6 4l4 4-4 4" />
                                                </svg>
                                            </div>
                                        </button>

                                        {expandedModules.includes(module.id) && module.lessons && module.lessons.length > 0 && (
                                            <div>
                                                {module.lessons.map((lesson, index) => (
                                                    <Link
                                                        key={lesson.id}
                                                        href={route('courses.learn', { course: course.slug, lesson: lesson.id })}
                                                        className={`block px-6 py-3 border-b border-gray-100 hover:bg-gray-50 transition-colors ${
                                                            currentLesson && currentLesson.id === lesson.id
                                                                ? 'bg-blue-50 border-l-4 border-l-primary'
                                                                : ''
                                                        }`}
                                                    >
                                                        <div className="flex items-center justify-between">
                                                            <div className="flex items-center">
                                                                <div
                                                                    className={`w-6 h-6 rounded-full border-2 ${
                                                                        lesson.completed
                                                                            ? 'bg-green-500 border-green-500'
                                                                            : 'border-gray-300'
                                                                    } flex items-center justify-center mr-3`}
                                                                >
                                                                    {lesson.completed ? (
                                                                        <svg className="w-3 h-3 text-white" fill="currentColor" viewBox="0 0 20 20">
                                                                            <path fillRule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clipRule="evenodd" />
                                                                        </svg>
                                                                    ) : (
                                                                        <span className="text-xs text-gray-600">{index + 1}</span>
                                                                    )}
                                                                </div>
                                                                <div>
                                                                    <h5 className="text-sm font-medium text-gray-900">{lesson.title}</h5>
                                                                    {lesson.duration && (
                                                                        <p className="text-xs text-gray-500">{formatDuration(lesson.duration)}</p>
                                                                    )}
                                                                </div>
                                                            </div>

                                                            <div className="flex items-center space-x-2">{getLessonIcon(lesson.type)}</div>
                                                        </div>
                                                    </Link>
                                                ))}
                                            </div>
                                        )}
                                    </div>
                                ))
                            ) : (
                                <div className="p-6 text-center text-gray-500">
                                    <p>No course content available</p>
                                </div>
                            )}
                        </div>
                    </div>
                </div>
            </div>
        </DashboardLayout>
    );
}
