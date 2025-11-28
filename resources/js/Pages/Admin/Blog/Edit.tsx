import { Head, useForm, usePage } from '@inertiajs/react';
import DashboardLayout from '@/Layouts/DashboardLayout';
import RichTextEditor from '@/Components/RichTextEditor';
import { FormEvent } from 'react';

interface Author {
    id: number;
    name: string;
}

interface Post {
    id: number;
    title: string;
    slug: string;
    excerpt: string | null;
    content: string;
    featured_image: string | null;
    author: Author;
    status: 'draft' | 'published';
}

interface EditPostProps {
    post: Post;
}

export default function EditPost({ post }: EditPostProps) {
    const { sideLinks } = usePage().props as any;
    const { data, setData, post: submit, processing, errors } = useForm({
        title: post.title,
        slug: post.slug,
        excerpt: post.excerpt || '',
        content: post.content,
        featured_image: null as File | null,
        status: post.status,
        _method: 'PUT',
    });

    const handleSubmit = (e: FormEvent) => {
        e.preventDefault();
        submit(route('admin.posts.update', post.id), {
            forceFormData: true,
        });
    };

    const generateSlug = () => {
        const slug = data.title
            .toLowerCase()
            .replace(/[^a-z0-9]+/g, '-')
            .replace(/^-+|-+$/g, '');
        setData('slug', slug);
    };

    return (
        <DashboardLayout sideLinks={sideLinks}>
            <Head title={`Edit: ${post.title}`} />

            <div className="py-8">
                <div className="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
                    <div className="mb-6">
                        <h1 className="text-3xl font-bold text-gray-900">Edit Blog Post</h1>
                        <p className="mt-1 text-sm text-gray-600">
                            Update your blog post
                        </p>
                    </div>

                    <form onSubmit={handleSubmit} className="space-y-6">
                        <div className="bg-white shadow-md rounded-lg p-6 space-y-6">
                            {/* Title */}
                            <div>
                                <label htmlFor="title" className="block text-sm font-medium text-gray-700 mb-2">
                                    Title *
                                </label>
                                <input
                                    type="text"
                                    id="title"
                                    value={data.title}
                                    onChange={(e) => setData('title', e.target.value)}
                                    onBlur={generateSlug}
                                    className="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-accent focus:border-transparent"
                                    required
                                />
                                {errors.title && <p className="mt-1 text-sm text-red-600">{errors.title}</p>}
                            </div>

                            {/* Slug */}
                            <div>
                                <label htmlFor="slug" className="block text-sm font-medium text-gray-700 mb-2">
                                    Slug
                                </label>
                                <input
                                    type="text"
                                    id="slug"
                                    value={data.slug}
                                    onChange={(e) => setData('slug', e.target.value)}
                                    className="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-accent focus:border-transparent"
                                    placeholder="auto-generated-from-title"
                                />
                                <p className="mt-1 text-xs text-gray-500">URL: /blog/{data.slug}</p>
                                {errors.slug && <p className="mt-1 text-sm text-red-600">{errors.slug}</p>}
                            </div>

                            {/* Excerpt */}
                            <div>
                                <label htmlFor="excerpt" className="block text-sm font-medium text-gray-700 mb-2">
                                    Excerpt
                                </label>
                                <textarea
                                    id="excerpt"
                                    value={data.excerpt}
                                    onChange={(e) => setData('excerpt', e.target.value)}
                                    rows={3}
                                    className="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-accent focus:border-transparent"
                                    placeholder="A brief summary of the post..."
                                />
                                {errors.excerpt && <p className="mt-1 text-sm text-red-600">{errors.excerpt}</p>}
                            </div>

                            {/* Content */}
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

                            {/* Current Featured Image */}
                            {post.featured_image && (
                                <div>
                                    <label className="block text-sm font-medium text-gray-700 mb-2">
                                        Current Featured Image
                                    </label>
                                    <img
                                        src={`/storage/${post.featured_image}`}
                                        alt={post.title}
                                        className="w-64 h-auto rounded-lg shadow-md"
                                    />
                                </div>
                            )}

                            {/* Featured Image */}
                            <div>
                                <label htmlFor="featured_image" className="block text-sm font-medium text-gray-700 mb-2">
                                    {post.featured_image ? 'Replace Featured Image' : 'Featured Image'}
                                </label>
                                <input
                                    type="file"
                                    id="featured_image"
                                    onChange={(e) => setData('featured_image', e.target.files?.[0] || null)}
                                    accept="image/*"
                                    className="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-accent focus:border-transparent"
                                />
                                {errors.featured_image && <p className="mt-1 text-sm text-red-600">{errors.featured_image}</p>}
                            </div>

                            {/* Status */}
                            <div>
                                <label htmlFor="status" className="block text-sm font-medium text-gray-700 mb-2">
                                    Status *
                                </label>
                                <select
                                    id="status"
                                    value={data.status}
                                    onChange={(e) => setData('status', e.target.value as 'draft' | 'published')}
                                    className="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-accent focus:border-transparent"
                                    required
                                >
                                    <option value="draft">Draft</option>
                                    <option value="published">Published</option>
                                </select>
                                {errors.status && <p className="mt-1 text-sm text-red-600">{errors.status}</p>}
                            </div>
                        </div>

                        {/* Action Buttons */}
                        <div className="flex items-center justify-end gap-4">
                            <a
                                href={route('admin.posts.index')}
                                className="px-6 py-2 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50 transition-colors"
                            >
                                Cancel
                            </a>
                            <button
                                type="submit"
                                disabled={processing}
                                className="px-6 py-2 bg-accent text-white rounded-lg hover:bg-accent/90 transition-colors disabled:opacity-50 disabled:cursor-not-allowed"
                            >
                                {processing ? 'Updating...' : 'Update Post'}
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </DashboardLayout>
    );
}
