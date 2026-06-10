import { Head, useForm, usePage } from '@inertiajs/react';
import DashboardLayout from '@/Layouts/DashboardLayout';
import RichTextEditor from '@/Components/RichTextEditor';
import { FormEvent } from 'react';
import { ArrowLeft, Save } from 'lucide-react';

export default function CreatePost() {
    const { sideLinks } = usePage().props as any;
    const { data, setData, post, processing, errors } = useForm({
        title: '',
        slug: '',
        excerpt: '',
        content: '',
        featured_image: null as File | null,
        status: 'draft' as 'draft' | 'published',
    });

    const handleSubmit = (e: FormEvent) => {
        e.preventDefault();
        post(route('admin.posts.store'));
    };

    const generateSlug = () => {
        const slug = data.title.toLowerCase().replace(/[^a-z0-9]+/g, '-').replace(/^-+|-+$/g, '');
        setData('slug', slug);
    };

    return (
        <DashboardLayout sideLinks={sideLinks}>
            <Head title="Create Blog Post" />

            <div className="mx-auto max-w-4xl space-y-6">
                <div className="flex items-center justify-between">
                    <div>
                        <h1 className="text-xl font-semibold tracking-tight text-slate-900">Create Blog Post</h1>
                        <p className="mt-1 text-sm text-slate-500">Write and publish a new article for your audience.</p>
                    </div>
                    <a href={route('admin.posts.index')} className="inline-flex items-center gap-1.5 rounded-md border border-slate-200 bg-white px-3.5 py-2 text-sm font-medium text-slate-600 transition hover:bg-slate-50">
                        <ArrowLeft size={16} /> Back
                    </a>
                </div>

                <form onSubmit={handleSubmit}>
                    <div className="rounded-lg border border-slate-200 bg-white">
                        <div className="border-b border-slate-100 px-6 py-4">
                            <h2 className="text-sm font-semibold tracking-tight text-slate-900">Post Content</h2>
                            <p className="mt-0.5 text-[13px] text-slate-500">The main body of your blog post.</p>
                        </div>

                        <div className="p-6 space-y-5">
                            <div>
                                <label htmlFor="title" className="block text-[13px] font-medium text-slate-700 mb-1.5">
                                    Title <span className="text-accent">*</span>
                                </label>
                                <input
                                    type="text" id="title"
                                    value={data.title}
                                    onChange={(e) => setData('title', e.target.value)}
                                    onBlur={generateSlug}
                                    placeholder="Enter post title..."
                                    required
                                    className="w-full rounded-md border border-slate-300 px-3.5 py-2.5 text-sm text-slate-900 placeholder:text-slate-400 outline-none transition focus:border-primary-300 focus:ring-2 focus:ring-primary-500/10"
                                />
                                {errors.title && <p className="mt-1.5 text-[13px] text-accent">{errors.title}</p>}
                            </div>

                            <div className="grid grid-cols-1 sm:grid-cols-2 gap-5">
                                <div>
                                    <label htmlFor="slug" className="block text-[13px] font-medium text-slate-700 mb-1.5">
                                        Slug
                                    </label>
                                    <input
                                        type="text" id="slug"
                                        value={data.slug}
                                        onChange={(e) => setData('slug', e.target.value)}
                                        placeholder="auto-generated-from-title"
                                        className="w-full rounded-md border border-slate-300 px-3.5 py-2.5 text-sm font-mono text-slate-900 placeholder:text-slate-400 outline-none transition focus:border-primary-300 focus:ring-2 focus:ring-primary-500/10"
                                    />
                                    <p className="mt-1 text-[11px] text-slate-400">Leave blank to auto-generate from title.</p>
                                    {errors.slug && <p className="mt-1.5 text-[13px] text-accent">{errors.slug}</p>}
                                </div>

                                <div>
                                    <label htmlFor="status" className="block text-[13px] font-medium text-slate-700 mb-1.5">
                                        Status <span className="text-accent">*</span>
                                    </label>
                                    <select
                                        id="status"
                                        value={data.status}
                                        onChange={(e) => setData('status', e.target.value as 'draft' | 'published')}
                                        required
                                        className="w-full rounded-md border border-slate-300 px-3.5 py-2.5 text-sm outline-none transition focus:border-primary-300 focus:ring-2 focus:ring-primary-500/10"
                                    >
                                        <option value="draft">Draft — save without publishing</option>
                                        <option value="published">Published — visible to everyone</option>
                                    </select>
                                    {errors.status && <p className="mt-1.5 text-[13px] text-accent">{errors.status}</p>}
                                </div>
                            </div>

                            <div>
                                <label htmlFor="excerpt" className="block text-[13px] font-medium text-slate-700 mb-1.5">
                                    Excerpt
                                </label>
                                <textarea
                                    id="excerpt"
                                    value={data.excerpt}
                                    onChange={(e) => setData('excerpt', e.target.value)}
                                    rows={3}
                                    placeholder="A brief summary of the post — appears in previews and social cards..."
                                    className="w-full rounded-md border border-slate-300 px-3.5 py-2.5 text-sm text-slate-900 placeholder:text-slate-400 outline-none transition focus:border-primary-300 focus:ring-2 focus:ring-primary-500/10 resize-none"
                                />
                                {errors.excerpt && <p className="mt-1.5 text-[13px] text-accent">{errors.excerpt}</p>}
                            </div>

                            <div>
                                <RichTextEditor
                                    label="Content"
                                    value={data.content}
                                    onChange={(value) => setData('content', value)}
                                    error={errors.content}
                                    placeholder="Write your blog post content here..."
                                    required
                                />
                            </div>
                        </div>
                    </div>

                    <div className="mt-5 rounded-lg border border-slate-200 bg-white">
                        <div className="border-b border-slate-100 px-6 py-4">
                            <h2 className="text-sm font-semibold tracking-tight text-slate-900">Featured Image</h2>
                            <p className="mt-0.5 text-[13px] text-slate-500">Add a cover image for your post.</p>
                        </div>
                        <div className="p-6">
                            <label htmlFor="featured_image" className="block text-[13px] font-medium text-slate-700 mb-1.5">
                                Upload Image
                            </label>
                            <input
                                type="file" id="featured_image"
                                onChange={(e) => setData('featured_image', e.target.files?.[0] || null)}
                                accept="image/*"
                                className="w-full text-sm text-slate-500 file:mr-3 file:rounded-md file:border-0 file:bg-primary file:px-4 file:py-2.5 file:text-xs file:font-medium file:text-white hover:file:bg-primary-600 cursor-pointer"
                            />
                            <p className="mt-2 text-[11px] text-slate-400">Recommended: 1200 x 630px. JPG or PNG. Max 5 MB.</p>
                            {errors.featured_image && <p className="mt-1.5 text-[13px] text-accent">{errors.featured_image}</p>}
                        </div>
                    </div>

                    <div className="mt-5 flex items-center justify-end gap-3">
                        <a href={route('admin.posts.index')} className="rounded-md border border-slate-200 bg-white px-5 py-2.5 text-sm font-medium text-slate-600 transition hover:bg-slate-50">
                            Cancel
                        </a>
                        <button type="submit" disabled={processing} className="inline-flex items-center gap-2 rounded-md bg-primary px-5 py-2.5 text-sm font-medium text-white transition hover:bg-primary-600 disabled:opacity-50 shadow-sm">
                            <Save size={15} />
                            {processing ? 'Publishing...' : 'Publish Post'}
                        </button>
                    </div>
                </form>
            </div>
        </DashboardLayout>
    );
}
