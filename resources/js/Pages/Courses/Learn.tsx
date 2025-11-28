import { Head, Link, router } from '@inertiajs/react';
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
            // Failed to load lesson progress - continue with default state
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
            // Failed to save progress - will retry on next interaction
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
            // Failed to mark lesson as complete - user can retry manually
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
        <>
            <Head title={`${course.title} - Learn`} />

            <div className="min-h-screen bg-gradient-to-br from-gray-50 to-gray-100">
                {/* Course Header */}
                <div className="bg-white shadow-md border-b border-gray-200 sticky top-0 z-50">
                    <div className="max-w-[1920px] mx-auto px-4 sm:px-6 lg:px-8">
                        <div className="flex items-center justify-between h-16">
                            {/* Course Title & Progress */}
                            <div className="flex items-center space-x-4 flex-1 min-w-0">
                                <Link
                                    href={route('courses.show', course.slug)}
                                    className="flex-shrink-0 p-2 text-gray-600 hover:text-primary hover:bg-primary/5 rounded-lg transition-all duration-200"
                                    title="Back to course details"
                                >
                                    <svg className="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path strokeLinecap="round" strokeLinejoin="round" strokeWidth="2" d="M15 19l-7-7 7-7" />
                                    </svg>
                                </Link>
                                <div className="flex-1 min-w-0">
                                    <h1 className="text-base sm:text-lg font-bold text-gray-900 truncate">{course.title}</h1>
                                    <div className="flex items-center mt-1">
                                        <div className="w-24 sm:w-32 bg-gray-200 rounded-full h-1.5 mr-3">
                                            <div
                                                className="bg-gradient-to-r from-primary to-primary-600 h-1.5 rounded-full transition-all duration-500"
                                                style={{ width: `${progress}%` }}
                                            ></div>
                                        </div>
                                        <span className="text-xs sm:text-sm font-medium text-gray-600">{progress}%</span>
                                    </div>
                                </div>
                            </div>

                            {/* Action Buttons */}
                            <div className="flex items-center space-x-2 sm:space-x-4">
                                <button
                                    onClick={() => setSidebarOpen(!sidebarOpen)}
                                    className="p-2 text-gray-600 hover:text-primary hover:bg-primary/5 rounded-lg transition-all duration-200 lg:hidden"
                                    title="Toggle sidebar"
                                >
                                    <svg className="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path strokeLinecap="round" strokeLinejoin="round" strokeWidth="2" d="M4 6h16M4 12h16M4 18h16" />
                                    </svg>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>

                <div className="flex h-[calc(100vh-4rem)] max-w-[1920px] mx-auto">
                    {/* Main Content Area */}
                    <div className="flex-1 flex flex-col overflow-hidden p-4 lg:p-6">
                        {/* Video Player */}
                        <div className="bg-black rounded-xl overflow-hidden shadow-2xl relative mb-6">
                            {currentLesson && currentLesson.video_url ? (
                                <div className="aspect-video relative">
                                    {currentLesson.video_url.includes('vimeo.com') || currentLesson.video_url.includes('player.vimeo.com') ? (
                                        // Vimeo iframe player
                                        <iframe
                                            src={currentLesson.video_url}
                                            className="w-full h-full"
                                            frameBorder="0"
                                            allow="autoplay; fullscreen; picture-in-picture"
                                            allowFullScreen
                                            title={currentLesson.title}
                                        ></iframe>
                                    ) : (
                                        // Regular HTML5 video player
                                        <>
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
                                            <div className="absolute bottom-0 left-0 right-0 h-1 bg-gray-700">
                                                <div
                                                    className="h-full bg-gradient-to-r from-primary to-accent transition-all duration-300"
                                                    style={{ width: `${watchProgress}%` }}
                                                ></div>
                                            </div>
                                        </>
                                    )}
                                </div>
                            ) : (
                                <div className="aspect-video flex items-center justify-center bg-gradient-to-br from-gray-800 to-gray-900 text-gray-400">
                                    <div className="text-center p-8">
                                        <div className="w-20 h-20 mx-auto mb-4 bg-gray-700/50 rounded-full flex items-center justify-center">
                                            <svg className="w-10 h-10 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path
                                                    strokeLinecap="round"
                                                    strokeLinejoin="round"
                                                    strokeWidth="1.5"
                                                    d="M15 10l4.553-2.276A1 1 0 0121 8.618v6.764a1 1 0 01-1.447.894L15 14M5 18h8a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z"
                                                />
                                            </svg>
                                        </div>
                                        <p className="text-lg font-medium">No video available for this lesson</p>
                                        <p className="text-sm text-gray-500 mt-2">Content coming soon</p>
                                    </div>
                                </div>
                            )}
                        </div>

                        {/* Lesson Actions */}
                        {currentLesson && (
                            <div className="flex flex-col sm:flex-row items-stretch sm:items-center justify-between gap-4 px-6 py-4 bg-white rounded-xl shadow-md border border-gray-200">
                                <div className="flex flex-wrap items-center gap-3">
                                    {/* Mark Complete Button */}
                                    <button
                                        onClick={markAsComplete}
                                        disabled={isCompleted}
                                        className={`flex items-center px-5 py-2.5 rounded-lg font-medium transition-all duration-200 shadow-sm ${
                                            isCompleted
                                                ? 'bg-gradient-to-r from-green-500 to-green-600 text-white cursor-not-allowed opacity-75'
                                                : 'bg-gradient-to-r from-green-600 to-green-700 text-white hover:from-green-700 hover:to-green-800 hover:shadow-md transform hover:-translate-y-0.5'
                                        }`}
                                    >
                                        <svg className="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path strokeLinecap="round" strokeLinejoin="round" strokeWidth="2" d="M5 13l4 4L19 7" />
                                        </svg>
                                        <span className="text-sm">{isCompleted ? 'Completed' : 'Mark Complete'}</span>
                                    </button>

                                    {/* Bookmark Button */}
                                    <button
                                        onClick={toggleBookmark}
                                        className={`flex items-center px-5 py-2.5 rounded-lg font-medium transition-all duration-200 shadow-sm ${
                                            isBookmarked
                                                ? 'bg-gradient-to-r from-amber-500 to-amber-600 text-white'
                                                : 'bg-white border-2 border-amber-500 text-amber-600 hover:bg-amber-50 hover:shadow-md transform hover:-translate-y-0.5'
                                        }`}
                                    >
                                        <svg className="w-4 h-4 mr-2" fill={isBookmarked ? 'currentColor' : 'none'} stroke="currentColor" viewBox="0 0 24 24">
                                            <path strokeLinecap="round" strokeLinejoin="round" strokeWidth="2" d="M5 5a2 2 0 012-2h10a2 2 0 012 2v16l-7-3.5L5 21V5z" />
                                        </svg>
                                        <span className="text-sm">{isBookmarked ? 'Saved' : 'Bookmark'}</span>
                                    </button>
                                </div>

                                {/* Lesson Duration */}
                                {currentLesson.duration && (
                                    <div className="flex items-center text-sm text-gray-600 font-medium">
                                        <svg className="w-4 h-4 mr-2 text-primary" fill="currentColor" viewBox="0 0 20 20">
                                            <path fillRule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-12a1 1 0 10-2 0v4a1 1 0 00.293.707l2.828 2.829a1 1 0 101.415-1.415L11 9.586V6z" clipRule="evenodd" />
                                        </svg>
                                        <span>{formatDuration(currentLesson.duration)}</span>
                                    </div>
                                )}
                            </div>
                        )}

                        {/* Lesson Content */}
                        <div className="flex-1 bg-white overflow-hidden flex flex-col rounded-xl shadow-lg mt-6">
                            {/* Lesson Navigation */}
                            <div className="border-b border-gray-200 px-6 py-5 bg-gradient-to-r from-gray-50 to-white">
                                <div className="flex items-center justify-between">
                                    <div className="w-24">
                                        {previousLesson && (
                                            <Link
                                                href={route('courses.learn', { course: course.slug, lesson: previousLesson.id })}
                                                className="inline-flex items-center gap-2 px-4 py-2 text-sm font-medium text-gray-700 hover:text-primary bg-white hover:bg-primary/5 border border-gray-300 hover:border-primary rounded-lg transition-all duration-200 shadow-sm hover:shadow"
                                            >
                                                <svg className="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path strokeLinecap="round" strokeLinejoin="round" strokeWidth="2" d="M15 19l-7-7 7-7" />
                                                </svg>
                                                <span className="hidden sm:inline">Previous</span>
                                            </Link>
                                        )}
                                    </div>
                                    <div className="text-center flex-1 px-4">
                                        <h2 className="text-lg sm:text-xl font-bold text-gray-900">
                                            {currentLesson ? currentLesson.title : 'Course Overview'}
                                        </h2>
                                        {currentLesson?.subtitle && <p className="text-sm text-gray-600 mt-1">{currentLesson.subtitle}</p>}
                                    </div>
                                    <div className="w-24 flex justify-end">
                                        {nextLesson && (
                                            <Link
                                                href={route('courses.learn', { course: course.slug, lesson: nextLesson.id })}
                                                className="inline-flex items-center gap-2 px-4 py-2 text-sm font-medium text-white bg-gradient-to-r from-primary to-primary-600 hover:from-primary-600 hover:to-primary-700 rounded-lg transition-all duration-200 shadow-sm hover:shadow-md transform hover:-translate-y-0.5"
                                            >
                                                <span className="hidden sm:inline">Next</span>
                                                <svg className="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path strokeLinecap="round" strokeLinejoin="round" strokeWidth="2" d="M9 5l7 7-7 7" />
                                                </svg>
                                            </Link>
                                        )}
                                    </div>
                                </div>
                            </div>

                            {/* Tabs */}
                            <div className="border-b border-gray-200 bg-gray-50/50">
                                <nav className="-mb-px flex px-6 overflow-x-auto">
                                    {['overview', 'notes', 'resources', 'discussion'].map((tab) => (
                                        <button
                                            key={tab}
                                            onClick={() => setActiveTab(tab)}
                                            className={`py-4 px-6 border-b-2 font-semibold text-sm capitalize whitespace-nowrap transition-all duration-200 ${
                                                activeTab === tab
                                                    ? 'border-primary text-primary bg-white'
                                                    : 'border-transparent text-gray-600 hover:text-primary hover:border-gray-300 hover:bg-white/50'
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
                    <div className={`w-80 xl:w-96 bg-white border-l border-gray-200 flex flex-col ${!sidebarOpen ? 'hidden' : ''} lg:flex shadow-xl`}>
                        {/* Course Progress */}
                        <div className="p-6 border-b border-gray-200 bg-gradient-to-br from-primary/5 to-accent/5">
                            <h3 className="font-bold text-gray-900 mb-4 text-lg">Course Progress</h3>
                            <div className="space-y-3">
                                <div className="flex justify-between text-sm font-medium">
                                    <span className="text-gray-700">
                                        {completedLessons} of {totalLessons} lessons
                                    </span>
                                    <span className="text-primary font-bold">{progress}%</span>
                                </div>
                                <div className="w-full bg-gray-200 rounded-full h-3 shadow-inner overflow-hidden">
                                    <div
                                        className="bg-gradient-to-r from-primary via-primary-600 to-accent h-3 rounded-full transition-all duration-500 shadow-sm"
                                        style={{ width: `${progress}%` }}
                                    ></div>
                                </div>
                                {progress === 100 && (
                                    <div className="flex items-center gap-2 text-sm text-green-700 bg-green-50 px-3 py-2 rounded-lg mt-2">
                                        <svg className="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                            <path fillRule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clipRule="evenodd" />
                                        </svg>
                                        <span className="font-medium">Course Completed!</span>
                                    </div>
                                )}
                            </div>
                        </div>

                        {/* Course Curriculum */}
                        <div className="flex-1 overflow-y-auto scrollbar-thin scrollbar-thumb-gray-300 scrollbar-track-gray-100">
                            {course.modules && course.modules.length > 0 ? (
                                course.modules.map((module, moduleIndex) => (
                                    <div key={module.id} className="border-b border-gray-200 last:border-b-0">
                                        <button
                                            onClick={() => toggleModule(module.id)}
                                            className="w-full text-left p-5 hover:bg-gradient-to-r hover:from-gray-50 hover:to-transparent transition-all duration-200"
                                        >
                                            <div className="flex items-center justify-between">
                                                <div className="flex-1">
                                                    <div className="flex items-center gap-2 mb-1">
                                                        <span className="text-xs font-bold text-primary bg-primary/10 px-2 py-1 rounded">
                                                            Module {moduleIndex + 1}
                                                        </span>
                                                    </div>
                                                    <h4 className="font-bold text-gray-900 text-base">{module.title}</h4>
                                                    <p className="text-xs text-gray-600 mt-1 flex items-center gap-1">
                                                        <svg className="w-3 h-3" fill="currentColor" viewBox="0 0 20 20">
                                                            <path d="M9 4.804A7.968 7.968 0 005.5 4c-1.255 0-2.443.29-3.5.804v10A7.969 7.969 0 015.5 14c1.669 0 3.218.51 4.5 1.385A7.962 7.962 0 0114.5 14c1.255 0 2.443.29 3.5.804v-10A7.968 7.968 0 0014.5 4c-1.255 0-2.443.29-3.5.804V12a1 1 0 11-2 0V4.804z" />
                                                        </svg>
                                                        {module.lessons?.length || 0} lessons
                                                    </p>
                                                </div>
                                                <svg
                                                    className={`w-5 h-5 text-primary transform transition-transform duration-200 ${
                                                        expandedModules.includes(module.id) ? 'rotate-90' : ''
                                                    }`}
                                                    viewBox="0 0 20 20"
                                                    fill="currentColor"
                                                >
                                                    <path fillRule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clipRule="evenodd" />
                                                </svg>
                                            </div>
                                        </button>

                                        {expandedModules.includes(module.id) && module.lessons && module.lessons.length > 0 && (
                                            <div className="bg-gray-50/50">
                                                {module.lessons.map((lesson, index) => (
                                                    <Link
                                                        key={lesson.id}
                                                        href={route('courses.learn', { course: course.slug, lesson: lesson.id })}
                                                        className={`block px-6 py-3.5 border-t border-gray-100 hover:bg-white transition-all duration-200 group ${
                                                            currentLesson && currentLesson.id === lesson.id
                                                                ? 'bg-gradient-to-r from-primary/10 to-transparent border-l-4 border-l-primary shadow-sm'
                                                                : ''
                                                        }`}
                                                    >
                                                        <div className="flex items-center justify-between gap-3">
                                                            <div className="flex items-center flex-1 min-w-0 gap-3">
                                                                <div
                                                                    className={`w-7 h-7 rounded-full border-2 flex-shrink-0 flex items-center justify-center transition-all duration-200 ${
                                                                        lesson.completed
                                                                            ? 'bg-gradient-to-br from-green-400 to-green-600 border-green-600 shadow-sm'
                                                                            : currentLesson && currentLesson.id === lesson.id
                                                                            ? 'border-primary bg-primary/10'
                                                                            : 'border-gray-300 group-hover:border-primary'
                                                                    }`}
                                                                >
                                                                    {lesson.completed ? (
                                                                        <svg className="w-4 h-4 text-white" fill="currentColor" viewBox="0 0 20 20">
                                                                            <path fillRule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clipRule="evenodd" />
                                                                        </svg>
                                                                    ) : (
                                                                        <span className={`text-xs font-bold ${currentLesson && currentLesson.id === lesson.id ? 'text-primary' : 'text-gray-600'}`}>
                                                                            {index + 1}
                                                                        </span>
                                                                    )}
                                                                </div>
                                                                <div className="flex-1 min-w-0">
                                                                    <h5 className={`text-sm font-semibold truncate ${currentLesson && currentLesson.id === lesson.id ? 'text-primary' : 'text-gray-900 group-hover:text-primary'}`}>
                                                                        {lesson.title}
                                                                    </h5>
                                                                    {lesson.duration && (
                                                                        <p className="text-xs text-gray-500 flex items-center gap-1 mt-0.5">
                                                                            <svg className="w-3 h-3" fill="currentColor" viewBox="0 0 20 20">
                                                                                <path fillRule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-12a1 1 0 10-2 0v4a1 1 0 00.293.707l2.828 2.829a1 1 0 101.415-1.415L11 9.586V6z" clipRule="evenodd" />
                                                                            </svg>
                                                                            {formatDuration(lesson.duration)}
                                                                        </p>
                                                                    )}
                                                                </div>
                                                            </div>

                                                            <div className="flex items-center gap-2 flex-shrink-0">
                                                                {getLessonIcon(lesson.type)}
                                                                {currentLesson && currentLesson.id === lesson.id && (
                                                                    <div className="w-2 h-2 bg-primary rounded-full animate-pulse"></div>
                                                                )}
                                                            </div>
                                                        </div>
                                                    </Link>
                                                ))}
                                            </div>
                                        )}
                                    </div>
                                ))
                            ) : (
                                <div className="p-8 text-center">
                                    <div className="w-16 h-16 mx-auto mb-4 bg-gray-100 rounded-full flex items-center justify-center">
                                        <svg className="w-8 h-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path strokeLinecap="round" strokeLinejoin="round" strokeWidth="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" />
                                        </svg>
                                    </div>
                                    <p className="text-gray-500 font-medium">No course content available</p>
                                </div>
                            )}
                        </div>
                    </div>
                </div>
            </div>
        </>
    );
}
