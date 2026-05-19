import { ChangeEvent, FormEvent, ReactNode } from 'react';
import { Link, useForm, usePage } from '@inertiajs/react';
import { BookOpen, CircleDollarSign, Film, ImageIcon, Layers3 } from 'lucide-react';

interface Category {
    id: number;
    name: string;
}

interface Level {
    value: string;
    label: string;
}

interface CourseFormData {
    title: string;
    subtitle: string;
    description: string;
    language: string;
    level: string;
    category_id: string;
    is_free: boolean;
    price: string;
    thumbnail: File | null;
    preview_video: File | null;
}

interface CourseCreateFormProps {
    categories: Category[];
    levels: Level[];
    submitRoute: string;
    backRoute: string;
    eyebrow: string;
    heading: string;
    description: string;
    priceLabel: string;
    priceHint: string;
}

interface ValidationPageProps {
    errors?: Partial<Record<keyof CourseFormData, string>>;
    [key: string]: unknown;
}

const fieldClassName =
    'mt-2 w-full rounded-2xl border border-slate-200 bg-slate-50 px-4 py-3 text-sm text-slate-900 outline-none transition focus:border-emerald-300 focus:bg-white focus:ring-4 focus:ring-emerald-100';

export default function CourseCreateForm({
    categories,
    levels,
    submitRoute,
    backRoute,
    eyebrow,
    heading,
    description,
    priceLabel,
    priceHint,
}: CourseCreateFormProps) {
    const { errors: pageErrors = {} } = usePage<ValidationPageProps>().props;
    const { data, setData, post, processing, errors } = useForm<CourseFormData>({
        title: '',
        subtitle: '',
        description: '',
        language: 'English',
        level: '',
        category_id: '',
        is_free: true,
        price: '0',
        thumbnail: null,
        preview_video: null,
    });
    const validationErrors = { ...pageErrors, ...errors };

    const handleInputChange = (e: ChangeEvent<HTMLInputElement | HTMLTextAreaElement | HTMLSelectElement>) => {
        const { name, value, type } = e.target;

        if (type === 'checkbox') {
            const checked = (e.target as HTMLInputElement).checked;
            setData((prev) => ({
                ...prev,
                [name]: checked,
                price: checked ? '0' : prev.price === '0' ? '' : prev.price,
            }));
            return;
        }

        setData(name as keyof CourseFormData, value as never);
    };

    const handleSubmit = (e: FormEvent) => {
        e.preventDefault();
        post(submitRoute, {
            preserveScroll: true,
            forceFormData: true,
        });
    };

    return (
        <div className="workspace-stack">
            <section className="workspace-header-card px-6 py-6 lg:px-8">
                <div className="flex flex-col gap-5 lg:flex-row lg:items-end lg:justify-between">
                    <div className="max-w-3xl">
                        <p className="workspace-muted-label">{eyebrow}</p>
                        <h1 className="mt-3 text-3xl font-semibold tracking-tight text-slate-900">{heading}</h1>
                        <p className="mt-3 text-sm leading-7 text-slate-600">{description}</p>
                    </div>

                    <Link
                        href={backRoute}
                        className="inline-flex items-center gap-2 rounded-2xl border border-slate-200 bg-white px-4 py-3 text-sm font-semibold text-slate-700 transition hover:border-slate-300 hover:bg-slate-50"
                    >
                        <i className="fas fa-arrow-left text-xs"></i>
                        Back to Courses
                    </Link>
                </div>
            </section>

            <div className="grid gap-4 md:grid-cols-3">
                <InfoCard
                    icon={<BookOpen className="h-5 w-5 text-slate-700" />}
                    iconWrap="bg-slate-100"
                    label="Course setup"
                    text="Define the title, promise, and learning path with language that feels deliberate."
                />
                <InfoCard
                    icon={<Layers3 className="h-5 w-5 text-emerald-700" />}
                    iconWrap="bg-emerald-100"
                    label="Structure"
                    text={`Choose from ${categories.length} categories and ${levels.length} difficulty levels before building modules.`}
                />
                <InfoCard
                    icon={<CircleDollarSign className="h-5 w-5 text-amber-700" />}
                    iconWrap="bg-amber-100"
                    label="Pricing"
                    text="Free courses can launch quickly, while paid ones need a clear value signal and a clean price point."
                />
            </div>

            <form onSubmit={handleSubmit} noValidate className="grid gap-6 xl:grid-cols-[minmax(0,1.3fr)_24rem]">
                <section className="workspace-card p-6 lg:p-7">
                    <div className="border-b border-slate-200 pb-5">
                        <h2 className="text-lg font-semibold text-slate-900">Core details</h2>
                        <p className="mt-2 text-sm leading-6 text-slate-600">
                            Write the course the way a prospective learner will experience it: what it is, who it serves, and why it matters.
                        </p>
                    </div>

                    <div className="mt-6 space-y-6">
                        <Field
                            label="Course title"
                            helper="Lead with the clearest transformation or outcome."
                            error={validationErrors.title}
                            required
                        >
                            <input
                                type="text"
                                id="title"
                                name="title"
                                value={data.title}
                                onChange={handleInputChange}
                                className={fieldClassName}
                                placeholder="e.g., Kingdom Leadership Principles"
                                maxLength={255}
                            />
                        </Field>

                        <Field
                            label="Subtitle"
                            helper="Use one sentence to sharpen the promise and give the title more texture."
                            error={validationErrors.subtitle}
                        >
                            <input
                                type="text"
                                id="subtitle"
                                name="subtitle"
                                value={data.subtitle}
                                onChange={handleInputChange}
                                className={fieldClassName}
                                placeholder="A practical framework for leading people with clarity and conviction"
                                maxLength={500}
                            />
                        </Field>

                        <Field
                            label="Description"
                            helper="Aim for a concrete overview of topics, audience, and expected outcomes. Minimum 100 characters."
                            error={validationErrors.description}
                            required
                        >
                            <textarea
                                id="description"
                                name="description"
                                value={data.description}
                                onChange={handleInputChange}
                                rows={10}
                                className={`${fieldClassName} resize-y`}
                                placeholder="Describe what students will learn, the problems this course helps them solve, and the kind of learner it was built for."
                            />
                        </Field>
                    </div>
                </section>

                <div className="space-y-6">
                    <section className="workspace-card p-6">
                        <div className="flex items-start gap-3">
                            <div className="rounded-2xl bg-slate-100 p-3 text-slate-700">
                                <Layers3 className="h-5 w-5" />
                            </div>
                            <div>
                                <h2 className="text-lg font-semibold text-slate-900">Settings</h2>
                                <p className="mt-2 text-sm leading-6 text-slate-600">
                                    Set the discovery metadata before you move into lessons and curriculum.
                                </p>
                            </div>
                        </div>

                        <div className="mt-6 space-y-5">
                            <Field label="Language" error={validationErrors.language} required>
                                <select
                                    id="language"
                                    name="language"
                                    value={data.language}
                                    onChange={handleInputChange}
                                    className={fieldClassName}
                                >
                                    <option value="English">English</option>
                                    <option value="Spanish">Spanish</option>
                                    <option value="French">French</option>
                                    <option value="German">German</option>
                                    <option value="Portuguese">Portuguese</option>
                                    <option value="Arabic">Arabic</option>
                                </select>
                            </Field>

                            <Field label="Level" error={validationErrors.level} required>
                                <select
                                    id="level"
                                    name="level"
                                    value={data.level}
                                    onChange={handleInputChange}
                                    className={fieldClassName}
                                >
                                    <option value="">Select course level</option>
                                    {levels.map((level) => (
                                        <option key={level.value} value={level.value}>
                                            {level.label}
                                        </option>
                                    ))}
                                </select>
                            </Field>

                            <Field label="Category" error={validationErrors.category_id} required>
                                <select
                                    id="category_id"
                                    name="category_id"
                                    value={data.category_id}
                                    onChange={handleInputChange}
                                    className={fieldClassName}
                                >
                                    <option value="">Select course category</option>
                                    {categories.map((category) => (
                                        <option key={category.id} value={category.id}>
                                            {category.name}
                                        </option>
                                    ))}
                                </select>
                            </Field>
                        </div>
                    </section>

                    <section className="workspace-card p-6">
                        <div className="flex items-start gap-3">
                            <div className="rounded-2xl bg-emerald-100 p-3 text-emerald-700">
                                <ImageIcon className="h-5 w-5" />
                            </div>
                            <div>
                                <h2 className="text-lg font-semibold text-slate-900">Media</h2>
                                <p className="mt-2 text-sm leading-6 text-slate-600">
                                    Add the first visual impression learners will see before opening the syllabus.
                                </p>
                            </div>
                        </div>

                        <div className="mt-6 space-y-5">
                            <Field
                                label="Thumbnail"
                                helper="Accepted: JPG, PNG, WEBP up to 2MB."
                                error={validationErrors.thumbnail}
                                required
                            >
                                <label
                                    htmlFor="thumbnail"
                                    className="mt-2 flex cursor-pointer flex-col gap-3 rounded-2xl border border-dashed border-slate-300 bg-slate-50 px-4 py-5 transition hover:border-emerald-300 hover:bg-white"
                                >
                                    <span className="text-sm font-medium text-slate-700">
                                        {data.thumbnail ? data.thumbnail.name : 'Choose a thumbnail image'}
                                    </span>
                                    <span className="text-xs text-slate-500">This image appears in course listings and previews.</span>
                                    <input
                                        type="file"
                                        id="thumbnail"
                                        name="thumbnail"
                                        accept=".jpg,.jpeg,.png,.webp"
                                        onChange={(e) => setData('thumbnail', e.target.files?.[0] ?? null)}
                                        className="hidden"
                                    />
                                </label>
                            </Field>

                            <Field
                                label="Preview video"
                                helper="Optional. Accepted: MP4, MOV, AVI, WMV up to 50MB."
                                error={validationErrors.preview_video}
                            >
                                <label
                                    htmlFor="preview_video"
                                    className="mt-2 flex cursor-pointer flex-col gap-3 rounded-2xl border border-dashed border-slate-300 bg-slate-50 px-4 py-5 transition hover:border-emerald-300 hover:bg-white"
                                >
                                    <span className="inline-flex items-center gap-2 text-sm font-medium text-slate-700">
                                        <Film className="h-4 w-4 text-slate-400" />
                                        {data.preview_video ? data.preview_video.name : 'Upload an optional preview video'}
                                    </span>
                                    <span className="text-xs text-slate-500">A short preview can improve confidence before enrolment.</span>
                                    <input
                                        type="file"
                                        id="preview_video"
                                        name="preview_video"
                                        accept=".mp4,.mov,.avi,.wmv"
                                        onChange={(e) => setData('preview_video', e.target.files?.[0] ?? null)}
                                        className="hidden"
                                    />
                                </label>
                            </Field>
                        </div>
                    </section>

                    <section className="workspace-card p-6">
                        <div className="flex items-start gap-3">
                            <div className="rounded-2xl bg-amber-100 p-3 text-amber-700">
                                <CircleDollarSign className="h-5 w-5" />
                            </div>
                            <div>
                                <h2 className="text-lg font-semibold text-slate-900">Pricing</h2>
                                <p className="mt-2 text-sm leading-6 text-slate-600">
                                    Keep pricing straightforward and aligned with the course depth you’re promising.
                                </p>
                            </div>
                        </div>

                        <div className="mt-6 space-y-5">
                            <label className="flex items-start gap-3 rounded-2xl border border-slate-200 bg-slate-50 px-4 py-4">
                                <input
                                    type="checkbox"
                                    id="is_free"
                                    name="is_free"
                                    checked={data.is_free}
                                    onChange={handleInputChange}
                                    className="mt-1 h-4 w-4 rounded border-slate-300 text-emerald-600 focus:ring-emerald-500"
                                />
                                <span>
                                    <span className="block text-sm font-semibold text-slate-900">Offer this course for free</span>
                                    <span className="mt-1 block text-xs leading-5 text-slate-500">
                                        Free courses default to `0` and skip the paid pricing field.
                                    </span>
                                </span>
                            </label>

                            {!data.is_free && (
                                <Field label={priceLabel} helper={priceHint} error={validationErrors.price} required>
                                    <input
                                        type="number"
                                        id="price"
                                        name="price"
                                        value={data.price}
                                        onChange={handleInputChange}
                                        step="0.01"
                                        min="0"
                                        className={fieldClassName}
                                        placeholder="0.00"
                                    />
                                </Field>
                            )}
                        </div>
                    </section>
                </div>

                <section className="workspace-card flex items-center justify-between gap-4 p-5 xl:col-span-2">
                    <div>
                        <p className="text-sm font-semibold text-slate-900">Ready to continue?</p>
                        <p className="mt-1 text-sm text-slate-600">
                            After creation, you’ll move to the builder to add modules, lessons, outcomes, and requirements.
                        </p>
                    </div>

                    <div className="flex flex-col gap-3 sm:flex-row">
                        <Link
                            href={backRoute}
                            className="inline-flex items-center justify-center rounded-2xl border border-slate-200 bg-white px-4 py-3 text-sm font-semibold text-slate-700 transition hover:border-slate-300 hover:bg-slate-50"
                        >
                            Cancel
                        </Link>
                        <button
                            type="submit"
                            disabled={processing}
                            className="inline-flex items-center justify-center rounded-2xl bg-slate-900 px-5 py-3 text-sm font-semibold text-white transition hover:bg-slate-800 disabled:cursor-not-allowed disabled:opacity-60"
                        >
                            {processing ? 'Creating course...' : 'Create Course and Open Builder'}
                        </button>
                    </div>
                </section>
            </form>
        </div>
    );
}

function Field({
    label,
    helper,
    error,
    required = false,
    children,
}: {
    label: string;
    helper?: string;
    error?: string;
    required?: boolean;
    children: ReactNode;
}) {
    return (
        <div>
            <label className="block text-sm font-semibold text-slate-900">
                {label}
                {required ? <span className="ml-1 text-rose-500">*</span> : null}
            </label>
            {helper ? <p className="mt-1 text-xs leading-5 text-slate-500">{helper}</p> : null}
            {children}
            {error ? <p className="mt-2 text-sm text-rose-600">{error}</p> : null}
        </div>
    );
}

function InfoCard({
    icon,
    iconWrap,
    label,
    text,
}: {
    icon: ReactNode;
    iconWrap: string;
    label: string;
    text: string;
}) {
    return (
        <div className="workspace-card p-5">
            <div className={`inline-flex rounded-2xl p-3 ${iconWrap}`}>{icon}</div>
            <h3 className="mt-4 text-sm font-semibold text-slate-900">{label}</h3>
            <p className="mt-2 text-sm leading-6 text-slate-600">{text}</p>
        </div>
    );
}
