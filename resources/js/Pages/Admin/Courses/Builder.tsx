import { useState, FormEvent, ChangeEvent } from 'react';
import { Head, Link, router, usePage } from '@inertiajs/react';
import DashboardLayout from '@/Layouts/DashboardLayout';
import ConfirmModal from '@/Components/ConfirmModal';

interface Category {
    id: number;
    name: string;
}

interface Instructor {
    id: number;
    name: string;
}

interface Requirement {
    id: number;
    requirement: string;
}

interface Outcome {
    id: number;
    outcome: string;
}

interface Lesson {
    id: number;
    slug: string;
    title: string;
    type: 'video' | 'pdf' | 'link';
    content_path: string;
}

interface CourseModule {
    id: number;
    slug: string;
    title: string;
    lessons: Lesson[];
}

interface Course {
    id: number;
    slug: string;
    title: string;
    description: string;
    level: string;
    thumbnail_path: string;
    category: Category;
    instructor: Instructor;
    requirements: Requirement[];
    outcomes: Outcome[];
    modules: CourseModule[];
}

interface BuilderProps {
    course: Course;
}

export default function Builder({ course }: BuilderProps) {
    const { sideLinks, errors } = usePage().props as any;
    const [newRequirement, setNewRequirement] = useState('');
    const [newOutcome, setNewOutcome] = useState('');
    const [newModuleTitle, setNewModuleTitle] = useState('');
    const [expandedModules, setExpandedModules] = useState<number[]>([]);
    const [isSubmitting, setIsSubmitting] = useState(false);

    // Delete modal state
    const [deleteModal, setDeleteModal] = useState<{
        show: boolean;
        type: 'requirement' | 'outcome' | 'module' | 'lesson' | null;
        id: number | string | null;
        moduleSlug?: string;
        lessonSlug?: string;
        title?: string;
    }>({
        show: false,
        type: null,
        id: null
    });

    const toggleModule = (moduleId: number) => {
        setExpandedModules(prev =>
            prev.includes(moduleId)
                ? prev.filter(id => id !== moduleId)
                : [...prev, moduleId]
        );
    };

    const handleAddRequirement = (e: FormEvent) => {
        e.preventDefault();
        if (!newRequirement.trim()) return;

        setIsSubmitting(true);
        router.post(route('admin.requirements.store', course.slug), {
            requirement: newRequirement,
        }, {
            preserveScroll: true,
            onSuccess: () => {
                setNewRequirement('');
            },
            onFinish: () => {
                setIsSubmitting(false);
            },
        });
    };

    const handleDeleteRequirement = (requirementId: number) => {
        setDeleteModal({
            show: true,
            type: 'requirement',
            id: requirementId,
            title: 'Delete Requirement'
        });
    };

    const handleAddOutcome = (e: FormEvent) => {
        e.preventDefault();
        if (!newOutcome.trim()) return;

        setIsSubmitting(true);
        router.post(route('admin.outcomes.store', course.slug), {
            outcome: newOutcome,
        }, {
            preserveScroll: true,
            onSuccess: () => {
                setNewOutcome('');
            },
            onFinish: () => {
                setIsSubmitting(false);
            },
        });
    };

    const handleDeleteOutcome = (outcomeId: number) => {
        setDeleteModal({
            show: true,
            type: 'outcome',
            id: outcomeId,
            title: 'Delete Learning Outcome'
        });
    };

    const handleAddModule = (e: FormEvent) => {
        e.preventDefault();
        if (!newModuleTitle.trim()) return;

        setIsSubmitting(true);
        router.post(route('admin.modules.store', course.slug), {
            title: newModuleTitle,
        }, {
            preserveScroll: true,
            onSuccess: () => {
                setNewModuleTitle('');
            },
            onFinish: () => {
                setIsSubmitting(false);
            },
        });
    };

    const handleDeleteLesson = (moduleSlug: string, lessonSlug: string, lessonTitle: string) => {
        setDeleteModal({
            show: true,
            type: 'lesson',
            id: lessonSlug,
            moduleSlug: moduleSlug,
            lessonSlug: lessonSlug,
            title: `Delete Lesson: ${lessonTitle}`
        });
    };

    const handleEditModule = (moduleSlug: string, currentTitle: string) => {
        const newTitle = prompt('Edit Module Title:', currentTitle);
        if (!newTitle || newTitle.trim() === '' || newTitle === currentTitle) return;

        setIsSubmitting(true);
        router.put(route('admin.modules.update', [course.slug, moduleSlug]), {
            title: newTitle.trim(),
        }, {
            preserveScroll: true,
            onFinish: () => {
                setIsSubmitting(false);
            },
        });
    };

    const handleDeleteModule = (moduleSlug: string, moduleTitle: string) => {
        setDeleteModal({
            show: true,
            type: 'module',
            id: moduleSlug,
            moduleSlug: moduleSlug,
            title: `Delete Module: ${moduleTitle}`
        });
    };

    const confirmDelete = () => {
        if (!deleteModal.id || !deleteModal.type) return;

        setIsSubmitting(true);

        switch (deleteModal.type) {
            case 'requirement':
                router.delete(route('admin.requirements.destroy', [course.slug, deleteModal.id]), {
                    preserveScroll: true,
                    onFinish: () => {
                        setIsSubmitting(false);
                        setDeleteModal({ show: false, type: null, id: null });
                    }
                });
                break;
            case 'outcome':
                router.delete(route('admin.outcomes.destroy', [course.slug, deleteModal.id]), {
                    preserveScroll: true,
                    onFinish: () => {
                        setIsSubmitting(false);
                        setDeleteModal({ show: false, type: null, id: null });
                    }
                });
                break;
            case 'module':
                router.delete(route('admin.modules.destroy', [course.slug, deleteModal.moduleSlug]), {
                    preserveScroll: true,
                    onFinish: () => {
                        setIsSubmitting(false);
                        setDeleteModal({ show: false, type: null, id: null });
                    }
                });
                break;
            case 'lesson':
                if (deleteModal.moduleSlug && deleteModal.lessonSlug) {
                    router.delete(route('admin.lessons.destroy', [deleteModal.moduleSlug, deleteModal.lessonSlug]), {
                        preserveScroll: true,
                        onFinish: () => {
                            setIsSubmitting(false);
                            setDeleteModal({ show: false, type: null, id: null });
                        }
                    });
                }
                break;
        }
    };

    const getLessonIcon = (type: string) => {
        switch (type) {
            case 'video':
                return '🎥';
            case 'pdf':
                return '📄';
            case 'link':
                return '🔗';
            default:
                return '📝';
        }
    };

    return (
        <DashboardLayout sideLinks={sideLinks}>
            <Head title={`Course Builder - ${course.title}`} />

            <div className="px-4 md:px-6 py-6">
                {/* Course Metadata Header */}
                <div className="mb-8 p-6 bg-white border border-gray-200 rounded-lg shadow-sm">
                    <h1 className="text-2xl font-bold text-gray-800 mb-2">{course.title}</h1>
                    <p className="text-gray-600 mb-4">{course.description}</p>

                    <div className="grid grid-cols-1 md:grid-cols-2 gap-4 text-sm text-gray-700">
                        <div>
                            <span className="font-medium">Level:</span> {course.level || 'Beginner'}
                        </div>
                        <div>
                            <span className="font-medium">Category:</span> {course.category?.name || 'Uncategorized'}
                        </div>
                        <div>
                            <span className="font-medium">Instructor:</span> {course.instructor?.name || 'N/A'}
                        </div>
                        {course.thumbnail_path && (
                            <div>
                                <img
                                    src={`/storage/${course.thumbnail_path}`}
                                    alt="Thumbnail"
                                    className="h-24 w-24 object-cover rounded mt-2"
                                />
                            </div>
                        )}
                    </div>
                </div>

                {/* Back Button */}
                <div className="mb-6">
                    <Link
                        href={route('admin.courses.index')}
                        className="inline-flex items-center px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-lg hover:bg-gray-100 focus:ring-4 focus:ring-gray-200"
                    >
                        ← Back to Courses
                    </Link>
                </div>

                {/* Requirements & Learning Objectives */}
                <div className="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
                    {/* Requirements */}
                    <div className="bg-white border border-gray-200 rounded-lg shadow-sm p-5 self-start">
                        <h3 className="text-lg font-semibold text-gray-800 mb-3">Course Requirements</h3>

                        <ul className="space-y-2 mb-4">
                            {course.requirements && course.requirements.length > 0 ? (
                                course.requirements.map((requirement) => (
                                    <li key={requirement.id} className="flex items-center justify-between p-2 bg-gray-50 rounded">
                                        <span className="text-gray-700 text-sm">{requirement.requirement}</span>
                                        <button
                                            onClick={() => handleDeleteRequirement(requirement.id)}
                                            className="text-gray-400 text-sm cursor-pointer flex items-center space-x-2 hover:text-red-600"
                                        >
                                            <i className="fas fa-times w-4 h-4"></i>
                                        </button>
                                    </li>
                                ))
                            ) : (
                                <li className="text-sm text-gray-500 italic">No requirements yet.</li>
                            )}
                        </ul>

                        <form onSubmit={handleAddRequirement} className="flex space-x-2">
                            <input
                                type="text"
                                value={newRequirement}
                                onChange={(e) => setNewRequirement(e.target.value)}
                                placeholder="Add new requirement"
                                className="flex-1 rounded-lg border focus:ring-2 focus:ring-primary border-primary p-2 text-sm text-gray-900"
                            />
                            <button
                                type="submit"
                                disabled={isSubmitting || !newRequirement.trim()}
                                className="px-4 py-2 text-sm font-medium text-white bg-primary hover:bg-primary-600 rounded-lg disabled:opacity-50 disabled:cursor-not-allowed"
                            >
                                + Add
                            </button>
                        </form>
                        {errors?.requirement && (
                            <span className="text-red-600 text-xs mt-1 block">{errors.requirement}</span>
                        )}
                    </div>

                    {/* Learning Outcomes */}
                    <div className="bg-white border border-gray-200 rounded-lg shadow-sm p-5 self-start">
                        <h3 className="text-lg font-semibold text-gray-800 mb-3">Learning Outcomes</h3>

                        <ul className="space-y-2 mb-4">
                            {course.outcomes && course.outcomes.length > 0 ? (
                                course.outcomes.map((outcome) => (
                                    <li key={outcome.id} className="flex items-center justify-between p-2 bg-gray-50 rounded">
                                        <span className="text-gray-700 text-sm">{outcome.outcome}</span>
                                        <button
                                            onClick={() => handleDeleteOutcome(outcome.id)}
                                            className="text-gray-400 text-sm cursor-pointer flex items-center space-x-2 hover:text-red-600"
                                        >
                                            <i className="fas fa-times w-4 h-4"></i>
                                        </button>
                                    </li>
                                ))
                            ) : (
                                <li className="text-sm text-gray-500 italic">No outcomes yet.</li>
                            )}
                        </ul>

                        <form onSubmit={handleAddOutcome} className="flex space-x-2">
                            <input
                                type="text"
                                value={newOutcome}
                                onChange={(e) => setNewOutcome(e.target.value)}
                                placeholder="Add learning outcome"
                                className="flex-1 rounded-lg border focus:ring-2 focus:ring-primary border-primary p-2 text-sm text-gray-900"
                            />
                            <button
                                type="submit"
                                disabled={isSubmitting || !newOutcome.trim()}
                                className="px-4 py-2 text-sm font-medium text-white bg-primary hover:bg-primary-600 rounded-lg disabled:opacity-50 disabled:cursor-not-allowed"
                            >
                                + Add
                            </button>
                        </form>
                        {errors?.outcome && (
                            <span className="text-red-600 text-xs mt-1 block">{errors.outcome}</span>
                        )}
                    </div>
                </div>

                {/* Add Module */}
                <div className="mb-6">
                    <form onSubmit={handleAddModule} className="flex flex-col sm:flex-row sm:items-center sm:space-x-3 space-y-3 sm:space-y-0">
                        <input
                            type="text"
                            value={newModuleTitle}
                            onChange={(e) => setNewModuleTitle(e.target.value)}
                            placeholder="New Module Title"
                            className="w-full sm:w-auto flex-1 rounded-lg border border-gray-300 bg-gray-50 p-2.5 text-sm text-gray-900 focus:border-primary focus:ring-primary"
                        />
                        <button
                            type="submit"
                            disabled={isSubmitting || !newModuleTitle.trim()}
                            className="inline-flex items-center justify-center px-4 py-2 text-sm font-medium text-white bg-primary rounded-lg disabled:opacity-50 disabled:cursor-not-allowed"
                        >
                            + Add Module
                        </button>
                    </form>
                    {errors?.title && (
                        <small className="mt-2 text-xs text-red-500 block">{errors.title}</small>
                    )}
                </div>

                {/* Modules */}
                <div className="space-y-6">
                    {course.modules && course.modules.length > 0 ? (
                        course.modules.map((module) => (
                            <div key={module.id} className="bg-white border border-gray-200 rounded-lg shadow-sm">
                                {/* Accordion Header */}
                                <div className="flex items-center justify-between p-4 cursor-pointer" onClick={() => toggleModule(module.id)}>
                                    <h2 className="text-lg font-semibold text-gray-800">{module.title}</h2>
                                    <div className="flex items-center space-x-2">
                                        {/* Toggle Icon */}
                                        {expandedModules.includes(module.id) ? (
                                            <i className="fas fa-minus w-5 h-5 text-gray-500"></i>
                                        ) : (
                                            <i className="fas fa-plus w-5 h-5 text-gray-500"></i>
                                        )}

                                        {/* Action Buttons */}
                                        <div className="flex space-x-2" onClick={(e) => e.stopPropagation()}>
                                            <Link
                                                href={route('admin.lessons.create', module.slug)}
                                                className="inline-flex cursor-pointer items-center px-3 py-1.5 text-sm font-medium text-gray-500 hover:text-primary rounded-lg"
                                            >
                                                + Add Lesson
                                            </Link>
                                            <button
                                                onClick={() => handleEditModule(module.slug, module.title)}
                                                className="inline-flex cursor-pointer items-center px-3 py-1.5 text-sm font-medium text-blue-600 hover:text-blue-800 rounded-lg"
                                                title="Edit Module"
                                            >
                                                <i className="fas fa-edit"></i>
                                            </button>
                                            <button
                                                onClick={() => handleDeleteModule(module.slug, module.title)}
                                                className="inline-flex cursor-pointer items-center px-3 py-1.5 text-sm font-medium text-red-600 hover:text-red-800 rounded-lg"
                                                title="Delete Module"
                                            >
                                                <i className="fas fa-trash"></i>
                                            </button>
                                        </div>
                                    </div>
                                </div>

                                {/* Accordion Content */}
                                {expandedModules.includes(module.id) && (
                                    <div className="px-4 pb-4">
                                        <ul className="space-y-2">
                                            {module.lessons && module.lessons.length > 0 ? (
                                                module.lessons.map((lesson) => (
                                                    <li key={lesson.id} className="flex items-center justify-between p-3 bg-gray-50 rounded-lg">
                                                        <span className="text-sm text-gray-700 font-bold">
                                                            {getLessonIcon(lesson.type)} {lesson.type.charAt(0).toUpperCase() + lesson.type.slice(1)}: {lesson.title}
                                                        </span>
                                                        <div className="flex items-center gap-2">
                                                            {lesson.type === 'video' && (
                                                                <a
                                                                    href={`/storage/${lesson.content_path}`}
                                                                    className="text-green-500 hover:text-green-700 text-sm"
                                                                >
                                                                    View
                                                                </a>
                                                            )}
                                                            {lesson.type === 'pdf' && (
                                                                <a
                                                                    href={`/storage/${lesson.content_path}`}
                                                                    target="_blank"
                                                                    rel="noopener noreferrer"
                                                                    className="inline-flex items-center text-primary hover:underline text-sm"
                                                                    title="View PDF"
                                                                >
                                                                    <i className="fas fa-file-pdf w-4 h-4"></i>
                                                                </a>
                                                            )}
                                                            {lesson.type === 'link' && (
                                                                <a
                                                                    href={lesson.content_path}
                                                                    target="_blank"
                                                                    rel="noopener noreferrer"
                                                                    className="inline-flex items-center text-primary hover:underline text-sm"
                                                                >
                                                                    <i className="fas fa-external-link-alt w-4 h-4"></i>
                                                                </a>
                                                            )}
                                                            <button
                                                                onClick={() => handleDeleteLesson(module.slug, lesson.slug, lesson.title)}
                                                                className="inline-flex items-center text-red-600 hover:text-red-800"
                                                                title="Delete Lesson"
                                                            >
                                                                <i className="fas fa-trash w-4 h-4"></i>
                                                            </button>
                                                        </div>
                                                    </li>
                                                ))
                                            ) : (
                                                <li className="text-sm text-gray-500 italic">No lessons yet.</li>
                                            )}
                                        </ul>
                                    </div>
                                )}
                            </div>
                        ))
                    ) : (
                        <div className="bg-white border border-gray-200 rounded-lg shadow-sm p-6 text-center text-gray-500">
                            No modules yet. Add your first module above.
                        </div>
                    )}
                </div>
            </div>

            {/* Delete Confirmation Modal */}
            <ConfirmModal
                show={deleteModal.show}
                onClose={() => setDeleteModal({ show: false, type: null, id: null })}
                onConfirm={confirmDelete}
                title={deleteModal.title || 'Confirm Delete'}
                message={
                    deleteModal.type === 'module'
                        ? 'Are you sure you want to delete this module? All lessons in this module will be deleted, including any video lessons which will be permanently removed from Vimeo. This action cannot be undone.'
                        : deleteModal.type === 'lesson'
                        ? 'Are you sure you want to delete this lesson? If this is a video lesson, the video will also be removed from Vimeo. This action cannot be undone.'
                        : 'Are you sure you want to delete this item? This action cannot be undone.'
                }
                confirmText="Delete"
                cancelText="Cancel"
                confirmButtonClass="bg-red-600 hover:bg-red-700 text-white"
                icon={
                    <div className="mx-auto flex items-center justify-center h-12 w-12 rounded-full bg-red-100">
                        <i className="fas fa-trash text-red-600 text-xl"></i>
                    </div>
                }
                isProcessing={isSubmitting}
                processingText="Deleting..."
            />
        </DashboardLayout>
    );
}
