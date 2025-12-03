import { Head, Link, router } from '@inertiajs/react';
import { useState, useEffect, useMemo, useCallback } from 'react';
import VimeoPlayer from '@/Components/VimeoPlayer';
import { ChevronLeft, ChevronRight, Menu, CheckCircle, PlayCircle, FileText } from 'lucide-react';

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

    const markAsComplete = useCallback(() => {
        if (!currentLesson || isCompleted) return;
        
        router.post(
            route('courses.lessons.complete', { course: course.slug, lesson: currentLesson.id }),
            {},
            {
                preserveScroll: true,
                onSuccess: () => {
                    setIsCompleted(true);
                }
            }
        );
    }, [currentLesson, isCompleted, course.slug]);

    const getResourceUrl = (path: string | null) => {
        if (!path) return '';
        if (path.startsWith('http')) return path;
        return `/storage/${path}`;
    };

    const lessonContent = useMemo(() => {
        if (!currentLesson) {
            return (
                <div className="w-full h-full flex flex-col items-center justify-center text-slate-400 bg-slate-900 rounded-xl border border-slate-800">
                    <PlayCircle className="w-16 h-16 mb-4 opacity-20" />
                    <p>Select a lesson to begin your learning journey.</p>
                </div>
            );
        }

        // Handle PDF
        if (currentLesson.type === 'pdf' && currentLesson.content_path) {
            return (
                <iframe 
                    src={getResourceUrl(currentLesson.content_path)}
                    className="w-full h-full rounded-xl border border-slate-700"
                    title={currentLesson.title}
                />
            );
        }

        // Handle Text
        if (currentLesson.type === 'text') {
            return (
                <div className="w-full h-full flex flex-col items-center justify-center bg-slate-900 text-slate-300 p-12 text-center rounded-xl border border-slate-800">
                    <FileText className="w-20 h-20 mb-6 text-slate-600" />
                    <h3 className="text-2xl font-semibold text-white mb-3">{currentLesson.title}</h3>
                    <p className="max-w-md text-slate-400">This lesson contains reading material. Please refer to the description below.</p>
                </div>
            );
        }

        // Handle Video (Vimeo or Standard)
        if (currentLesson.vimeo_id || (currentLesson.video_url && (currentLesson.video_url.includes('vimeo.com') || currentLesson.video_url.includes('player.vimeo.com')))) {
            return (
                <div className="w-full h-full rounded-xl overflow-hidden bg-black shadow-2xl border border-slate-800">
                    <VimeoPlayer 
                        videoId={currentLesson.vimeo_id}
                        videoUrl={currentLesson.video_url}
                        onEnded={markAsComplete}
                        autoplay={false}
                    />
                </div>
            );
        }

        // Fallback Standard Video
        if (currentLesson.video_url) {
            return (
                <video
                    className="w-full h-full rounded-xl bg-black shadow-2xl border border-slate-800"
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
            <div className="w-full h-full flex flex-col items-center justify-center text-slate-400 bg-slate-900 rounded-xl border border-slate-800">
                <AlertCircle className="w-12 h-12 mb-3 text-yellow-500/50" />
                <p>No media content available for this lesson.</p>
            </div>
        );
    }, [currentLesson, markAsComplete]);

    return (
        <>
            <Head title={`${currentLesson?.title || 'Learn'} - ${course.title}`} />

            <div className="min-h-screen bg-gray-100 text-gray-900 font-sans selection:bg-indigo-500 selection:text-white">
                {/* Header */}
                <header className="bg-white/95 backdrop-blur-sm border-b border-gray-200 sticky top-0 z-50 h-16">
                    <div className="h-full px-4 sm:px-6 lg:px-8 flex items-center justify-between">
                        <div className="flex items-center gap-4 min-w-0 flex-1">
                            <Link 
                                href={route('courses.show', course.slug)} 
                                className="p-2 -ml-2 text-gray-500 hover:text-gray-700 hover:bg-gray-100 rounded-full transition-colors"
                                title="Back to Course Details"
                            >
                                <ChevronLeft className="w-5 h-5" />
                            </Link>
                            <div className="h-6 w-px bg-gray-300 hidden sm:block" />
                            <h1 className="text-sm sm:text-base font-semibold text-gray-900 truncate tracking-tight">{course.title}</h1>
                        </div>

                        <div className="flex items-center gap-4 sm:gap-6">
                             {/* Progress Bar (Desktop) */}
                            <div className="hidden sm:flex items-center gap-3">
                                <div className="w-32 h-2 bg-gray-300 rounded-full overflow-hidden">
                                    <div 
                                        className="h-full bg-indigo-500 transition-all duration-500 ease-out rounded-full" 
                                        style={{ width: `${progress}%` }} 
                                    />
                                </div>
                                <span className="text-xs font-medium text-indigo-600 whitespace-nowrap">{Math.round(progress)}%</span>
                            </div>

                            <div className="flex items-center gap-2">
                                <button 
                                    onClick={() => setSidebarOpen(!sidebarOpen)} 
                                    className={`p-2 text-gray-500 hover:text-gray-700 hover:bg-gray-100 rounded-lg transition-colors ${sidebarOpen ? 'bg-gray-100 text-gray-700' : ''}`}
                                    aria-label="Toggle Sidebar"
                                >
                                    <Menu className="w-5 h-5" />
                                </button>
                            </div>
                        </div>
                    </div>
                </header>

                <div className="flex h-[calc(100vh-4rem)] overflow-hidden">
                    {/* Main Content */}
                    <main className="flex-1 overflow-y-auto scrollbar-thin scrollbar-thumb-gray-300 scrollbar-track-transparent">
                        <div className="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
                            {/* Video Player Container */}
                            <div className="aspect-video w-full mb-8">
                                {lessonContent}
                            </div>

                            {/* Lesson Header */}
                            <div className="flex flex-col sm:flex-row sm:items-start sm:justify-between gap-4 mb-8 border-b border-gray-200 pb-8">
                                <div className="flex-1">
                                    <h2 className="text-2xl sm:text-3xl font-bold text-gray-900 mb-2 tracking-tight leading-tight">
                                        {currentLesson?.title}
                                    </h2>
                                    {currentLesson?.subtitle && (
                                        <p className="text-lg text-gray-600">{currentLesson.subtitle}</p>
                                    )}
                                </div>
                                <div className="flex items-center gap-3 shrink-0">
                                    {previousLesson && (
                                        <Link 
                                            href={route('courses.learn', { course: course.slug, lesson: previousLesson.id })} 
                                            className="flex items-center gap-2 px-4 py-2 text-sm font-medium text-gray-700 bg-gray-100 hover:bg-gray-200 hover:text-gray-900 rounded-lg transition-all border border-gray-300"
                                        >
                                            <ChevronLeft className="w-4 h-4" />
                                            <span className="hidden sm:inline">Previous</span>
                                        </Link>
                                    )}
                                    {nextLesson && (
                                        <Link 
                                            href={route('courses.learn', { course: course.slug, lesson: nextLesson.id })} 
                                            className="flex items-center gap-2 px-4 py-2 text-sm font-medium text-white bg-indigo-600 hover:bg-indigo-700 shadow-lg shadow-indigo-500/20 rounded-lg transition-all"
                                        >
                                            <span className="hidden sm:inline">Next Lesson</span>
                                            <span className="sm:hidden">Next</span>
                                            <ChevronRight className="w-4 h-4" />
                                        </Link>
                                    )}
                                </div>
                            </div>

                            {/* Tabs & Description */}
                            <div>
                                <div className="flex gap-6 border-b border-gray-200 mb-6">
                                    {['overview', 'resources', 'notes'].map((tab) => (
                                        <button
                                            key={tab}
                                            onClick={() => setActiveTab(tab)}
                                            className={`pb-4 text-sm font-medium border-b-2 transition-colors capitalize ${
                                                activeTab === tab
                                                    ? 'border-indigo-500 text-indigo-600'
                                                    : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300'
                                            }`}
                                        >
                                            {tab}
                                        </button>
                                    ))}
                                </div>

                                <div className="prose max-w-none">
                                    {activeTab === 'overview' && (
                                        currentLesson?.description ? (
                                            <div dangerouslySetInnerHTML={{ __html: currentLesson.description }} />
                                        ) : (
                                            <p className="text-gray-500 italic">No description provided for this lesson.</p>
                                        )
                                    )}
                                    {activeTab === 'resources' && (
                                        <div className="bg-white rounded-lg border border-gray-200 p-8 text-center">
                                            <p className="text-gray-700">No resources attached to this lesson.</p>
                                        </div>
                                    )}
                                    {activeTab === 'notes' && (
                                         <div className="bg-white rounded-lg border border-gray-200 p-8 text-center">
                                            <p className="text-gray-700">Notes feature coming soon.</p>
                                        </div>
                                    )}
                                </div>
                            </div>
                        </div>
                    </main>            
                                {/* Sidebar */}
                                <aside 
                                    className={`
                                        fixed inset-y-0 right-0 z-40 w-80 bg-white border-l border-gray-200 transform transition-transform duration-300 ease-in-out lg:relative lg:translate-x-0 flex flex-col
                                        ${sidebarOpen ? 'translate-x-0' : 'translate-x-full lg:hidden'}
                                    `}
                                >
                                    <div className="p-5 border-b border-gray-200 flex items-center justify-between bg-white">
                                        <h3 className="font-semibold text-gray-900 tracking-tight">Course Content</h3>
                                        <button onClick={() => setSidebarOpen(false)} className="lg:hidden text-gray-500 hover:text-gray-700">
                                            <ChevronRight className="w-5 h-5" />
                                        </button>
                                    </div>
                                    
                                    <div className="flex-1 overflow-y-auto scrollbar-thin scrollbar-thumb-gray-300 scrollbar-track-transparent">
                                        {course.modules?.map((module, moduleIndex) => (
                                            <div key={module.id} className="border-b border-gray-200/50 last:border-0">
                                                <button 
                                                    onClick={() => toggleModule(module.id)} 
                                                    className="w-full text-left px-5 py-4 hover:bg-gray-50 transition-colors group"
                                                >
                                                    <div className="flex items-start gap-3">
                                                        <div className="flex-1 min-w-0">
                                                            <p className="text-xs font-medium text-gray-500 uppercase tracking-wider mb-0.5">Section {moduleIndex + 1}</p>
                                                            <h4 className="text-sm font-medium text-gray-800 group-hover:text-gray-900 transition-colors line-clamp-2">{module.title}</h4>
                                                        </div>
                                                        <ChevronRight 
                                                            className={`w-4 h-4 text-gray-500 transition-transform duration-200 mt-1 ${expandedModules.includes(module.id) ? 'rotate-90' : ''}`}
                                                        />
                                                    </div>
                                                </button>
                                                
                                                <div 
                                                    className={`overflow-hidden transition-all duration-300 ease-in-out ${
                                                        expandedModules.includes(module.id) ? 'max-h-[1000px] opacity-100' : 'max-h-0 opacity-0'
                                                    }`}
                                                >
                                                    <div className="bg-gray-50/30 pb-2">
                                                        {module.lessons?.map((lesson) => {
                                                            const isActive = currentLesson?.id === lesson.id;
                                                            return (
                                                                <Link
                                                                    key={lesson.id}
                                                                    href={route('courses.learn', { course: course.slug, lesson: lesson.id })}
                                                                    className={`
                                                                        group flex items-start gap-3 px-5 py-3 text-sm transition-all border-l-2
                                                                        ${isActive 
                                                                            ? 'bg-indigo-500/10 border-indigo-500 text-indigo-700' 
                                                                            : 'border-transparent text-gray-600 hover:bg-gray-100 hover:text-gray-900'
                                                                        }
                                                                    `}
                                                                >
                                                                    <div className={`mt-0.5 shrink-0 ${lesson.completed ? 'text-emerald-600' : isActive ? 'text-indigo-600' : 'text-gray-400'}`}>
                                                                        {lesson.completed ? (
                                                                            <CheckCircle className="w-4 h-4" />
                                                                        ) : isActive ? (
                                                                            <div className="w-4 h-4 rounded-full border-2 border-current relative">
                                                                                <div className="absolute inset-0 m-auto w-1.5 h-1.5 rounded-full bg-current" />
                                                                            </div>
                                                                        ) : (
                                                                            <div className="w-4 h-4 rounded-full border-2 border-current" />
                                                                        )}
                                                                    </div>
                                                                    <div className="flex-1 min-w-0">
                                                                        <p className={`truncate ${isActive ? 'font-medium text-indigo-700' : 'group-hover:text-gray-900'}`}>
                                                                            {lesson.title}
                                                                        </p>
                                                                        <p className="text-xs text-gray-500 mt-0.5 flex items-center gap-1">
                                                                            {lesson.type === 'video' ? <PlayCircle className="w-3 h-3" /> : <FileText className="w-3 h-3" />}
                                                                            <span>{formatDuration(lesson.duration)}</span>
                                                                        </p>
                                                                    </div>
                                                                </Link>
                                                            );
                                                        })}
                                                    </div>
                                                </div>
                                            </div>
                                        ))}
                                    </div>
                                </aside>
                            </div>
                        </div>
                    </>
                );
            }
