import { Head, Link, router } from '@inertiajs/react';
import { useState, useRef, useEffect } from 'react';

// ... (interfaces remain the same)

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
    const videoRef = useRef<HTMLVideoElement>(null);
    const progressIntervalRef = useRef<NodeJS.Timeout | undefined>(undefined);

    useEffect(() => {
        if (course.modules && course.modules.length > 0 && expandedModules.length === 0) {
            const currentModule = course.modules.find(m => m.lessons?.some(l => l.id === currentLesson?.id));
            if (currentModule) {
                setExpandedModules([currentModule.id]);
            } else {
                setExpandedModules([course.modules[0].id]);
            }
        }
    }, [course.modules, currentLesson]);

    useEffect(() => {
        if (currentLesson) {
            setIsCompleted(currentLesson.completed || false);
        }
    }, [currentLesson]);

    const toggleModule = (moduleId: number) => {
        setExpandedModules((prev) =>
            prev.includes(moduleId) ? prev.filter((id) => id !== moduleId) : [...prev, moduleId]
        );
    };

    const markAsComplete = () => {
        if (!currentLesson || isCompleted) return;
        router.post(
            route('courses.lessons.complete', { course: course.slug, lesson: currentLesson.id }),
            {},
            {
                preserveScroll: true,
                onSuccess: () => {
                    setIsCompleted(true);
                    if (nextLesson) {
                        setTimeout(() => {
                            if (confirm('Lesson completed! Would you like to proceed to the next lesson?')) {
                                router.visit(route('courses.learn', { course: course.slug, lesson: nextLesson.id }));
                            }
                        }, 1000);
                    }
                }
            }
        );
    };

    const formatDuration = (duration: string | number | undefined) => {
        if (!duration) return '...';
        if (typeof duration === 'string') return duration;
        const minutes = Math.floor(duration / 60);
        const seconds = Math.floor(duration % 60);
        return `${minutes}:${seconds.toString().padStart(2, '0')}`;
    };

    const getLessonIcon = (type: string) => {
        // ... (getLessonIcon remains the same)
    };

    return (
        <>
            <Head title={`${currentLesson?.title || 'Learn'} - ${course.title}`} />

            <div className="min-h-screen bg-gray-800 text-white">
                {/* Course Header */}
                <div className="bg-gray-900 shadow-lg sticky top-0 z-50">
                    <div className="max-w-[1920px] mx-auto px-4 sm:px-6">
                        <div className="flex items-center justify-between h-16">
                            <div className="flex items-center space-x-4 flex-1 min-w-0">
                                <Link href={route('courses.show', course.slug)} className="text-gray-300 hover:text-white">
                                    <svg className="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path strokeLinecap="round" strokeLinejoin="round" strokeWidth="2" d="M15 19l-7-7 7-7" /></svg>
                                </Link>
                                <div className="border-l border-gray-700 h-8 mx-4"></div>
                                <div className="flex-1 min-w-0">
                                    <h1 className="text-lg font-bold text-white truncate">{course.title}</h1>
                                </div>
                            </div>

                            <div className="flex items-center space-x-6">
                                <div className="flex items-center space-x-2">
                                    <div className="w-32 bg-gray-700 rounded-full h-2">
                                        <div className="bg-primary h-2 rounded-full" style={{ width: `${progress}%` }}></div>
                                    </div>
                                    <span className="text-sm font-medium text-gray-300">{Math.round(progress)}% complete</span>
                                </div>
                                <div className="flex items-center space-x-4">
                                    {previousLesson && <Link href={route('courses.learn', { course: course.slug, lesson: previousLesson.id })} className="px-4 py-2 text-sm font-medium bg-gray-700 hover:bg-gray-600 rounded-md">Previous</Link>}
                                    {nextLesson && <Link href={route('courses.learn', { course: course.slug, lesson: nextLesson.id })} className="px-4 py-2 text-sm font-medium bg-primary hover:bg-primary/90 rounded-md">Next lesson</Link>}
                                </div>
                                <button onClick={() => setSidebarOpen(!sidebarOpen)} className="p-2 text-gray-400 hover:text-white lg:hidden">
                                    <svg className="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path strokeLinecap="round" strokeLinejoin="round" strokeWidth="2" d="M4 6h16M4 12h16M4 18h16" /></svg>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>

                <div className="flex h-[calc(100vh-4rem)]">
                    {/* Main Content Area */}
                    <div className="flex-1 flex flex-col overflow-hidden">
                        <div className="flex-1 flex flex-col p-6">
                            <div className="bg-black aspect-video rounded-lg overflow-hidden shadow-2xl relative">
                                {currentLesson && currentLesson.video_url ? (
                                    <div className="aspect-video relative">
                                        {currentLesson.video_url.includes('vimeo.com') || currentLesson.video_url.includes('player.vimeo.com') ? (
                                            // Vimeo iframe player
                                            <iframe
                                                src={`${currentLesson.video_url}?badge=0&autopause=0&player_id=0&app_id=58479`}
                                                className="w-full h-full"
                                                frameBorder="0"
                                                allow="autoplay; fullscreen; picture-in-picture; clipboard-write"
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
                                    <div className="w-full h-full flex items-center justify-center text-gray-400">Select a lesson to begin.</div>
                                )}
                            </div>

                            <div className="py-6">
                                <h1 className="text-3xl font-bold mb-2">{currentLesson?.title}</h1>
                                <p className="text-gray-400">{currentLesson?.subtitle}</p>
                            </div>

                            {/* Tabs */}
                            <div className="border-b border-gray-700">
                                <nav className="-mb-px flex space-x-8">
                                    {['overview', 'notes', 'resources', 'discussion'].map((tab) => (
                                        <button
                                            key={tab}
                                            onClick={() => setActiveTab(tab)}
                                            className={`py-4 px-1 border-b-2 font-medium text-sm capitalize ${
                                                activeTab === tab
                                                    ? 'border-primary text-primary'
                                                    : 'border-transparent text-gray-400 hover:text-gray-200 hover:border-gray-500'
                                            }`}
                                        >
                                            {tab}
                                        </button>
                                    ))}
                                </nav>
                            </div>

                            {/* Tab Content */}
                            <div className="py-6 flex-1 overflow-y-auto">
                                {/* ... (Tab content remains the same) ... */}
                            </div>
                        </div>
                    </div>

                    {/* Course Navigation Sidebar */}
                    <div className={`w-96 bg-gray-900 border-l border-gray-700 flex-col ${!sidebarOpen ? 'hidden' : 'flex'} lg:flex`}>
                        <div className="p-5 border-b border-gray-700">
                            <h3 className="font-bold text-lg">Course content</h3>
                        </div>
                        <div className="flex-1 overflow-y-auto">
                            {course.modules?.map((module, moduleIndex) => (
                                <div key={module.id} className="border-b border-gray-700 last:border-b-0">
                                    <button onClick={() => toggleModule(module.id)} className="w-full text-left p-5 hover:bg-gray-800/50">
                                        <div className="flex items-center justify-between">
                                            <div className="flex-1">
                                                <h4 className="font-bold text-base">{`Section ${moduleIndex + 1}: ${module.title}`}</h4>
                                                <p className="text-xs text-gray-400 mt-1">{module.lessons?.length || 0} lessons</p>
                                            </div>
                                            <svg className={`w-5 h-5 transform transition-transform ${expandedModules.includes(module.id) ? 'rotate-90' : ''}`} viewBox="0 0 20 20" fill="currentColor"><path fillRule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clipRule="evenodd" /></svg>
                                        </div>
                                    </button>
                                    {expandedModules.includes(module.id) && module.lessons?.map((lesson) => (
                                        <Link
                                            key={lesson.id}
                                            href={route('courses.learn', { course: course.slug, lesson: lesson.id })}
                                            className={`block pl-8 pr-4 py-3 text-sm border-l-4 ${
                                                currentLesson?.id === lesson.id
                                                    ? 'border-primary bg-gray-800 text-white'
                                                    : 'border-transparent text-gray-300 hover:bg-gray-800/50 hover:border-primary/50'
                                            }`}
                                        >
                                            <div className="flex items-center justify-between">
                                                <div className="flex items-center min-w-0">
                                                    <input type="checkbox" readOnly checked={lesson.completed} className="form-checkbox h-4 w-4 text-primary bg-gray-700 border-gray-600 rounded mr-3" />
                                                    <span className="truncate">{lesson.title}</span>
                                                </div>
                                                <span className="text-xs text-gray-400 shrink-0">{formatDuration(lesson.duration)}</span>
                                            </div>
                                        </Link>
                                    ))}
                                </div>
                            ))}
                        </div>
                    </div>
                </div>
            </div>
        </>
    );
}
