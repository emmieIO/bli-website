import { Head, Link, usePage } from '@inertiajs/react';
import DashboardLayout from '@/Layouts/DashboardLayout';
import { BookOpen, CheckCircle2, CircleDollarSign, Eye, FileText, Layers3, PlayCircle, Users } from 'lucide-react';
import type { ReactNode } from 'react';

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
    title: string;
    type: 'video' | 'pdf' | 'link';
    description?: string | null;
    video_url?: string | null;
    resource_url?: string | null;
}

interface CourseModule {
    id: number;
    title: string;
    description?: string | null;
    lessons: Lesson[];
}

interface Course {
    id: number;
    slug: string;
    title: string;
    subtitle?: string | null;
    description: string;
    price?: string | number | null;
    is_free: boolean;
    status: string;
    language?: string | null;
    level?: string | { value?: string } | null;
    thumbnail_path?: string | null;
    category?: {
        id: number;
        name: string;
    } | null;
    students?: Array<{ id: number }>;
    requirements: Requirement[];
    outcomes: Outcome[];
    modules: CourseModule[];
}

interface ShowProps {
    course: Course;
}

const statusTone: Record<string, string> = {
    draft: 'bg-slate-100 text-slate-700',
    pending: 'bg-amber-100 text-amber-700',
    under_review: 'bg-blue-100 text-blue-700',
    approved: 'bg-emerald-100 text-emerald-700',
    rejected: 'bg-rose-100 text-rose-700',
};

export default function InstructorCourseShow({ course }: ShowProps) {
    const { sideLinks } = usePage().props as any;
    const totalLessons = course.modules.reduce((sum, module) => sum + (module.lessons?.length || 0), 0);
    const statusClass = statusTone[course.status] || 'bg-slate-100 text-slate-700';
    const levelLabel = typeof course.level === 'string' ? course.level : course.level?.value || 'Not set';
    const priceLabel = course.is_free ? 'Free' : formatCurrency(course.price);

    return (
        <DashboardLayout sideLinks={sideLinks}>
            <Head title={course.title} />

            <div className="workspace-stack">
                <section className="workspace-header-card px-6 py-6 lg:px-8">
                    <div className="flex flex-col gap-5 xl:flex-row xl:items-end xl:justify-between">
                        <div className="max-w-4xl">
                            <p className="workspace-muted-label">Instructor Workspace</p>
                            <div className="mt-3 flex flex-wrap items-center gap-3">
                                <h1 className="text-3xl font-semibold tracking-tight text-slate-900">{course.title}</h1>
                                <span className={`inline-flex rounded-full px-3 py-1 text-xs font-semibold ${statusClass}`}>
                                    {formatStatus(course.status)}
                                </span>
                            </div>
                            {course.subtitle ? (
                                <p className="mt-3 text-base leading-7 text-slate-700">{course.subtitle}</p>
                            ) : null}
                            <p className="mt-3 text-sm leading-7 text-slate-600">
                                Review the learner-facing setup, confirm structure, and move into the builder when you are ready to refine modules and lessons.
                            </p>
                        </div>

                        <div className="flex flex-wrap gap-3">
                            <Link
                                href={route('instructor.courses.index')}
                                className="inline-flex items-center gap-2 rounded-2xl border border-slate-200 bg-white px-4 py-3 text-sm font-semibold text-slate-700 transition hover:border-slate-300 hover:bg-slate-50"
                            >
                                <i className="fas fa-arrow-left text-xs"></i>
                                Back to Courses
                            </Link>
                            <Link
                                href={route('instructor.courses.edit', course.slug)}
                                className="inline-flex items-center gap-2 rounded-2xl border border-slate-200 bg-white px-4 py-3 text-sm font-semibold text-slate-700 transition hover:border-slate-300 hover:bg-slate-50"
                            >
                                <i className="fas fa-cog text-xs"></i>
                                Edit Settings
                            </Link>
                            <Link
                                href={route('instructor.courses.builder', course.slug)}
                                className="inline-flex items-center gap-2 rounded-2xl bg-slate-900 px-4 py-3 text-sm font-semibold text-white transition hover:bg-slate-800"
                            >
                                <Layers3 className="h-4 w-4" />
                                Open Builder
                            </Link>
                        </div>
                    </div>
                </section>

                <div className="grid gap-4 sm:grid-cols-2 xl:grid-cols-4">
                    <MetricCard icon={<Users className="h-5 w-5 text-slate-700" />} iconWrap="bg-slate-100" label="Enrolled learners" value={(course.students?.length || 0).toString()} />
                    <MetricCard icon={<BookOpen className="h-5 w-5 text-emerald-700" />} iconWrap="bg-emerald-100" label="Modules" value={course.modules.length.toString()} />
                    <MetricCard icon={<PlayCircle className="h-5 w-5 text-blue-700" />} iconWrap="bg-blue-100" label="Lessons" value={totalLessons.toString()} />
                    <MetricCard icon={<CircleDollarSign className="h-5 w-5 text-amber-700" />} iconWrap="bg-amber-100" label="Price" value={priceLabel} />
                </div>

                <div className="grid gap-6 xl:grid-cols-[minmax(0,1.35fr)_24rem]">
                    <section className="workspace-card p-6 lg:p-7">
                        <div className="border-b border-slate-200 pb-5">
                            <h2 className="text-lg font-semibold text-slate-900">Course overview</h2>
                            <p className="mt-2 text-sm leading-6 text-slate-600">
                                This is the summary learners will ultimately experience once the course is published.
                            </p>
                        </div>

                        <div className="mt-6 space-y-6">
                            <div className="grid gap-4 sm:grid-cols-2">
                                <MetaCard label="Category" value={course.category?.name || 'Unassigned'} />
                                <MetaCard label="Level" value={levelLabel} />
                                <MetaCard label="Language" value={course.language || 'English'} />
                                <MetaCard label="Pricing" value={priceLabel} />
                            </div>

                            <div>
                                <h3 className="text-sm font-semibold text-slate-900">Description</h3>
                                <div className="mt-2 rounded-2xl border border-slate-200 bg-slate-50 p-5 text-sm leading-7 text-slate-700 whitespace-pre-line">
                                    {course.description}
                                </div>
                            </div>

                            <div className="grid gap-6 lg:grid-cols-2">
                                <ListCard
                                    title="Learning outcomes"
                                    icon={<CheckCircle2 className="h-4 w-4 text-emerald-600" />}
                                    items={course.outcomes.map((item) => item.outcome)}
                                    emptyText="No learning outcomes added yet."
                                />
                                <ListCard
                                    title="Requirements"
                                    icon={<FileText className="h-4 w-4 text-slate-500" />}
                                    items={course.requirements.map((item) => item.requirement)}
                                    emptyText="No requirements added yet."
                                />
                            </div>
                        </div>
                    </section>

                    <section className="workspace-card p-6">
                        <div className="flex items-start gap-3">
                            <div className="rounded-2xl bg-slate-100 p-3 text-slate-700">
                                <Eye className="h-5 w-5" />
                            </div>
                            <div>
                                <h2 className="text-lg font-semibold text-slate-900">Curriculum snapshot</h2>
                                <p className="mt-2 text-sm leading-6 text-slate-600">
                                    A quick view of the module structure before you jump into detailed editing.
                                </p>
                            </div>
                        </div>

                        <div className="mt-6 space-y-4">
                            {course.modules.length > 0 ? (
                                course.modules.map((module, index) => (
                                    <div key={module.id} className="rounded-2xl border border-slate-200 bg-slate-50 p-4">
                                        <div className="flex items-start justify-between gap-3">
                                            <div>
                                                <p className="text-xs font-semibold uppercase tracking-[0.14em] text-slate-500">Module {index + 1}</p>
                                                <h3 className="mt-1 text-sm font-semibold text-slate-900">{module.title}</h3>
                                                {module.description ? (
                                                    <p className="mt-2 text-sm leading-6 text-slate-600">{module.description}</p>
                                                ) : null}
                                            </div>
                                            <span className="rounded-full bg-white px-2.5 py-1 text-xs font-medium text-slate-600">
                                                {module.lessons.length} lesson{module.lessons.length === 1 ? '' : 's'}
                                            </span>
                                        </div>

                                        <ul className="mt-4 space-y-2">
                                            {module.lessons.map((lesson) => (
                                                <li key={lesson.id} className="flex items-center justify-between gap-3 rounded-xl bg-white px-3 py-2.5 text-sm text-slate-700">
                                                    <span className="min-w-0 truncate">
                                                        <span className="font-medium text-slate-900">{lesson.title}</span>
                                                        <span className="ml-2 text-slate-500">({lesson.type})</span>
                                                    </span>
                                                    {lesson.resource_url || lesson.video_url ? (
                                                        <a
                                                            href={lesson.video_url || lesson.resource_url || '#'}
                                                            target="_blank"
                                                            rel="noopener noreferrer"
                                                            className="shrink-0 font-medium text-primary hover:underline"
                                                        >
                                                            Open
                                                        </a>
                                                    ) : null}
                                                </li>
                                            ))}
                                        </ul>
                                    </div>
                                ))
                            ) : (
                                <div className="rounded-2xl border border-dashed border-slate-300 bg-slate-50 px-5 py-10 text-center text-sm text-slate-500">
                                    No modules yet. Open the builder to start structuring the curriculum.
                                </div>
                            )}
                        </div>
                    </section>
                </div>
            </div>
        </DashboardLayout>
    );
}

function MetricCard({
    icon,
    iconWrap,
    label,
    value,
}: {
    icon: ReactNode;
    iconWrap: string;
    label: string;
    value: string;
}) {
    return (
        <div className="workspace-card p-6">
            <div className={`inline-flex rounded-2xl p-3 ${iconWrap}`}>{icon}</div>
            <p className="mt-4 text-sm text-slate-500">{label}</p>
            <p className="mt-2 text-2xl font-semibold text-slate-900">{value}</p>
        </div>
    );
}

function MetaCard({ label, value }: { label: string; value: string }) {
    return (
        <div className="rounded-2xl border border-slate-200 bg-slate-50 p-4">
            <p className="text-xs font-semibold uppercase tracking-[0.14em] text-slate-500">{label}</p>
            <p className="mt-2 text-sm font-medium text-slate-900">{value}</p>
        </div>
    );
}

function ListCard({
    title,
    icon,
    items,
    emptyText,
}: {
    title: string;
    icon: ReactNode;
    items: string[];
    emptyText: string;
}) {
    return (
        <div className="rounded-2xl border border-slate-200 bg-slate-50 p-5">
            <div className="flex items-center gap-2">
                {icon}
                <h3 className="text-sm font-semibold text-slate-900">{title}</h3>
            </div>
            {items.length > 0 ? (
                <ul className="mt-4 space-y-2">
                    {items.map((item, index) => (
                        <li key={`${title}-${index}`} className="rounded-xl bg-white px-3 py-2.5 text-sm leading-6 text-slate-700">
                            {item}
                        </li>
                    ))}
                </ul>
            ) : (
                <p className="mt-4 text-sm text-slate-500">{emptyText}</p>
            )}
        </div>
    );
}

function formatStatus(status: string) {
    return status
        .split('_')
        .map((part) => part.charAt(0).toUpperCase() + part.slice(1))
        .join(' ');
}

function formatCurrency(price?: string | number | null) {
    const value = typeof price === 'string' ? Number(price) : price ?? 0;

    return new Intl.NumberFormat('en-US', {
        style: 'currency',
        currency: 'USD',
    }).format(Number.isFinite(value) ? value : 0);
}
