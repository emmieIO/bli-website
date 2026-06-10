import { Head, useForm, usePage } from '@inertiajs/react';
import DashboardLayout from '@/Layouts/DashboardLayout';
import RichTextEditor from '@/Components/RichTextEditor';
import { FormEvent } from 'react';

interface Author { id: number; name: string; }
interface Post { id: number; title: string; slug: string; excerpt: string | null; content: string; featured_image: string | null; author: Author; status: 'draft' | 'published'; }
interface EditPostProps { post: Post; }

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
    submit(route('admin.posts.update', post.id), { forceFormData: true });
  };

  const generateSlug = () => {
    const slug = data.title.toLowerCase().replace(/[^a-z0-9]+/g, '-').replace(/^-+|-+$/g, '');
    setData('slug', slug);
  };

  return (
    <DashboardLayout sideLinks={sideLinks}>
      <Head title={`Edit: ${post.title}`} />

      <div className="mx-auto max-w-3xl space-y-5">
        <div>
          <h1 className="text-xl font-semibold tracking-tight text-slate-900">Edit Blog Post</h1>
          <p className="mt-1 text-sm text-slate-500">Update your blog post</p>
        </div>

        <form onSubmit={handleSubmit} className="space-y-5">
          <div className="rounded-lg border border-slate-200 bg-white p-6 space-y-5">
            <div>
              <label htmlFor="title" className="block text-[13px] font-medium text-slate-700 mb-1.5">Title *</label>
              <input type="text" id="title" value={data.title} onChange={(e) => setData('title', e.target.value)} onBlur={generateSlug} required
                className="w-full rounded-md border border-slate-300 px-3.5 py-2.5 text-sm outline-none transition focus:border-primary-300 focus:ring-2 focus:ring-primary-500/10" />
              {errors.title && <p className="mt-1.5 text-[13px] text-accent">{errors.title}</p>}
            </div>

            <div>
              <label htmlFor="slug" className="block text-[13px] font-medium text-slate-700 mb-1.5">Slug</label>
              <input type="text" id="slug" value={data.slug} onChange={(e) => setData('slug', e.target.value)}
                className="w-full rounded-md border border-slate-300 px-3.5 py-2.5 text-sm outline-none transition focus:border-primary-300 focus:ring-2 focus:ring-primary-500/10" placeholder="auto-generated-from-title" />
              <p className="mt-1 text-[11px] text-slate-400">URL: /blog/{data.slug}</p>
              {errors.slug && <p className="mt-1.5 text-[13px] text-accent">{errors.slug}</p>}
            </div>

            <div>
              <label htmlFor="excerpt" className="block text-[13px] font-medium text-slate-700 mb-1.5">Excerpt</label>
              <textarea id="excerpt" value={data.excerpt} onChange={(e) => setData('excerpt', e.target.value)} rows={3}
                className="w-full rounded-md border border-slate-300 px-3.5 py-2.5 text-sm outline-none transition focus:border-primary-300 focus:ring-2 focus:ring-primary-500/10 resize-none" placeholder="A brief summary of the post..." />
              {errors.excerpt && <p className="mt-1.5 text-[13px] text-accent">{errors.excerpt}</p>}
            </div>

            <div>
              <RichTextEditor label="Content" value={data.content} onChange={(value) => setData('content', value)} error={errors.content} placeholder="Write your blog post content here..." required />
            </div>

            {post.featured_image && (
              <div>
                <label className="block text-[13px] font-medium text-slate-700 mb-1.5">Current Featured Image</label>
                <img src={`/storage/${post.featured_image}`} alt={post.title} className="w-48 h-auto rounded-md shadow-sm" />
              </div>
            )}

            <div>
              <label htmlFor="featured_image" className="block text-[13px] font-medium text-slate-700 mb-1.5">
                {post.featured_image ? 'Replace Featured Image' : 'Featured Image'}
              </label>
              <input type="file" id="featured_image" onChange={(e) => setData('featured_image', e.target.files?.[0] || null)} accept="image/*"
                className="w-full text-sm text-slate-500 file:mr-3 file:rounded-md file:border-0 file:bg-primary file:px-3.5 file:py-2 file:text-xs file:font-medium file:text-white hover:file:bg-primary-600" />
              {errors.featured_image && <p className="mt-1.5 text-[13px] text-accent">{errors.featured_image}</p>}
            </div>

            <div>
              <label htmlFor="status" className="block text-[13px] font-medium text-slate-700 mb-1.5">Status *</label>
              <select id="status" value={data.status} onChange={(e) => setData('status', e.target.value as 'draft' | 'published')} required
                className="w-full rounded-md border border-slate-300 px-3.5 py-2.5 text-sm outline-none transition focus:border-primary-300 focus:ring-2 focus:ring-primary-500/10">
                <option value="draft">Draft</option>
                <option value="published">Published</option>
              </select>
              {errors.status && <p className="mt-1.5 text-[13px] text-accent">{errors.status}</p>}
            </div>
          </div>

          <div className="flex items-center justify-end gap-3">
            <a href={route('admin.posts.index')} className="rounded-md border border-slate-200 bg-white px-4 py-2.5 text-sm font-medium text-slate-700 transition hover:bg-slate-50">Cancel</a>
            <button type="submit" disabled={processing} className="rounded-md bg-primary px-5 py-2.5 text-sm font-medium text-white transition hover:bg-primary-600 disabled:opacity-50 shadow-sm">
              {processing ? 'Updating...' : 'Update Post'}
            </button>
          </div>
        </form>
      </div>
    </DashboardLayout>
  );
}
