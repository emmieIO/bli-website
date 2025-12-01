import { Head, Link, router } from '@inertiajs/react';
import { useState, useEffect } from 'react';
import VimeoPlayer from '@/Components/VimeoPlayer';

interface Lesson {
    id: number;
    title: string;
    subtitle: string | null;
    video_url: string | null;
    vimeo_id: string | null;
    content_path: string | null;
    description: string | null;
    duration: string | number;
    completed: boolean;
    type: string;
}

interface Module {
    id: number;
    title: string;
    lessons: Lesson[];
}

interface Course {
    id: number;
    title: string;
    slug: string;
    modules: Module[];
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
}: LearnProps) {
    const [activeTab, setActiveTab] = useState('overview');
    const [expandedModules, setExpandedModules] = useState<number[]>([]);
    const [sidebarOpen, setSidebarOpen] = useState(true);
    const [isCompleted, setIsCompleted] = useState(currentLesson?.completed || false);

    useEffect(() => {
        if (course.modules && course.modules.length > 0 && expandedModules.length === 0) {
            const currentModule = course.modules.find(m => m.lessons?.some(l => l.id === currentLesson?.id));
            if (currentModule) {
                setExpandedModules([currentModule.id]);
            } else if (course.modules[0]) {
                setExpandedModules([course.modules[0].id]);
            }
        }
    }, [course.modules, currentLesson]);

    useEffect(() => {
        setIsCompleted(currentLesson?.completed || false);
    }, [currentLesson]);

    const toggleModule = (moduleId: number) => {
        setExpandedModules((prev) =>
            prev.includes(moduleId) ? prev.filter((id) => id !== moduleId) : [...prev, moduleId]
        );
    };

    const formatDuration = (duration: string | number | undefined) => {
        if (!duration) return '...';
        if (typeof duration === 'string') return duration;
        const minutes = Math.floor(duration / 60);
        const seconds = Math.floor(duration % 60);
        return `${minutes}:${seconds.toString().padStart(2, '0')}`;
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
                    // Optional: Auto-advance or show toast
                }
            }
        );
    };

    const getResourceUrl = (path: string | null) => {
        if (!path) return '';
        if (path.startsWith('http')) return path;
        return `/storage/${path}`;
    };

    const renderLessonContent = () => {
        if (!currentLesson) {
            return (
                <div className="w-full h-full flex items-center justify-center text-gray-400">
                    Select a lesson to begin.
                </div>
            );
        }

        // Handle PDF
        if (currentLesson.type === 'pdf' && currentLesson.content_path) {
            return (
                <iframe 
                    src={getResourceUrl(currentLesson.content_path)}
                    className="w-full h-full"
                    title={currentLesson.title}
                />
            );
        }

        // Handle Text (Optional: you might want to hide the player area for text-only lessons, 
        // but keeping a banner or image maintains layout consistency)
        if (currentLesson.type === 'text') {
            return (
                <div className="w-full h-full flex flex-col items-center justify-center bg-gray-800 text-gray-300 p-8 text-center">
                    <svg className="w-16 h-16 mb-4 opacity-50" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path strokeLinecap="round" strokeLinejoin="round" strokeWidth="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                    </svg>
                    <h3 className="text-xl font-semibold mb-2">{currentLesson.title}</h3>
                    <p>Read the lesson content below.</p>
                </div>
            );
        }

        // Handle Video (Vimeo or Standard)
        if (currentLesson.vimeo_id || (currentLesson.video_url && (currentLesson.video_url.includes('vimeo.com') || currentLesson.video_url.includes('player.vimeo.com')))) {
            return (
                <VimeoPlayer 
                    videoId={currentLesson.vimeo_id}
                    videoUrl={currentLesson.video_url}
                    onEnded={markAsComplete}
                    autoplay={false}
                />
            );
        }

        // Fallback Standard Video
        if (currentLesson.video_url) {
            return (
                <video
                    className="w-full h-full"
                    controls
                    preload="metadata"
                    poster="/images/video-placeholder.jpg"
                    onEnded={markAsComplete}
                >
                    <source src={currentLesson.video_url} type="video/mp4" />
                    Your browser does not support the video tag.
                </video>
            );
        }

        // Fallback if no content
        return (
            <div className="w-full h-full flex items-center justify-center text-gray-400">
                No media content available.
            </div>
        );
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
                    {/* Main Content Area - Now scrolls independently */}
                    <div className="flex-1 flex flex-col overflow-y-auto">
                        <div className="p-6">
                            {/* Media Player / Content Viewer */}
                            <div className="bg-black aspect-video rounded-lg overflow-hidden shadow-2xl relative">
                                {renderLessonContent()}
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
                            <div className="py-6">
                                {activeTab === 'overview' && currentLesson?.description ? (
                                    <div 
                                        className="prose prose-invert max-w-none prose-a:text-blue-400 hover:prose-a:text-blue-300"
                                        dangerouslySetInnerHTML={{ __html: currentLesson.description }}
                                    />
                                ) : activeTab === 'overview' ? (
                                    <p className="text-gray-500">No description available for this lesson.</p>
                                ) : (
                                    <p className="text-gray-500">Content coming soon...</p>
                                )}
                            </div>
                        </div>
                    </div>

                    {/* Course Navigation Sidebar */}
                    <div className={`w-96 bg-gray-900 border-l border-gray-700 flex-col ${!sidebarOpen ? 'hidden' : 'flex'} lg:flex overflow-y-auto`}>
                        <div className="p-5 border-b border-gray-700 sticky top-0 bg-gray-900 z-10">
                            <h3 className="font-bold text-lg">Course content</h3>
                        </div>
                        <div className="flex-1">
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
