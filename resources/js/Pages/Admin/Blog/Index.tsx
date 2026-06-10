import { Head, Link, router, usePage } from '@inertiajs/react';
import DashboardLayout from '@/Layouts/DashboardLayout';
import { Plus, Edit, Trash2, Eye } from 'lucide-react';

interface Author { id: number; name: string; }

interface Post {
  id: number;
  title: string;
  slug: string;
  status: 'draft' | 'published';
  author: Author;
  published_at: string | null;
  created_at: string;
  updated_at: string;
}

interface PostsProps {
  posts: { data: Post[]; links?: any; meta?: any; };
}

export default function PostsIndex({ posts }: PostsProps) {
  const { sideLinks } = usePage().props as any;
  const postsList = posts.data || [];

  const handleDelete = (postId: number) => {
    if (confirm('Are you sure you want to delete this post?')) {
      router.delete(route('admin.posts.destroy', postId), { preserveScroll: true });
    }
  };

  const formatDate = (dateString: string | null) => {
    if (!dateString) return 'N/A';
    return new Date(dateString).toLocaleDateString('en-US', { year: 'numeric', month: 'short', day: 'numeric' });
  };

  return (
    <DashboardLayout sideLinks={sideLinks}>
      <Head title="Blog Posts" />

      <div className="space-y-5">
        <div className="flex flex-col gap-3 sm:flex-row sm:items-end sm:justify-between">
          <div>
            <h1 className="text-xl font-semibold tracking-tight text-slate-900">Blog Posts</h1>
            <p className="mt-1 text-sm text-slate-500">Manage your blog posts</p>
          </div>
          <Link href={route('admin.posts.create')} className="inline-flex items-center gap-2 rounded-md bg-primary px-4 py-2.5 text-sm font-medium text-white transition hover:bg-primary-600 shadow-sm">
            <Plus size={16} /> New Post
          </Link>
        </div>

        <div className="overflow-hidden rounded-lg border border-slate-200 bg-white">
          {postsList.length === 0 ? (
            <div className="p-12 text-center">
              <div className="mx-auto flex h-14 w-14 items-center justify-center rounded-lg bg-slate-100 text-slate-400">
                <i className="fas fa-inbox text-lg"></i>
              </div>
              <p className="mt-4 text-sm text-slate-500">No blog posts yet. Create your first post!</p>
              <Link href={route('admin.posts.create')} className="mt-4 inline-flex items-center gap-2 rounded-md bg-primary px-4 py-2.5 text-sm font-medium text-white transition hover:bg-primary-600 shadow-sm">
                <Plus size={16} /> Create Post
              </Link>
            </div>
          ) : (
            <table className="min-w-full divide-y divide-slate-200">
              <thead className="bg-slate-50/80">
                <tr>
                  <th className="px-5 py-3 text-left text-[11px] font-semibold uppercase tracking-[0.14em] text-slate-500">Title</th>
                  <th className="px-5 py-3 text-left text-[11px] font-semibold uppercase tracking-[0.14em] text-slate-500">Author</th>
                  <th className="px-5 py-3 text-left text-[11px] font-semibold uppercase tracking-[0.14em] text-slate-500">Status</th>
                  <th className="px-5 py-3 text-left text-[11px] font-semibold uppercase tracking-[0.14em] text-slate-500">Published</th>
                  <th className="px-5 py-3 text-right text-[11px] font-semibold uppercase tracking-[0.14em] text-slate-500">Actions</th>
                </tr>
              </thead>
              <tbody className="divide-y divide-slate-100 bg-white">
                {postsList.map((post) => (
                  <tr key={post.id} className="transition hover:bg-slate-50/70">
                    <td className="px-5 py-3.5">
                      <div className="text-sm font-medium text-slate-900">{post.title}</div>
                      <div className="text-[13px] text-slate-400">/blog/{post.slug}</div>
                    </td>
                    <td className="px-5 py-3.5 whitespace-nowrap text-sm text-slate-700">{post.author.name}</td>
                    <td className="px-5 py-3.5 whitespace-nowrap">
                      <span className={`inline-flex rounded-md px-2 py-0.5 text-[11px] font-semibold ${post.status === 'published' ? 'bg-lime-100 text-lime-700' : 'bg-slate-100 text-slate-600'}`}>
                        {post.status.charAt(0).toUpperCase() + post.status.slice(1)}
                      </span>
                    </td>
                    <td className="px-5 py-3.5 whitespace-nowrap text-sm text-slate-500">{formatDate(post.published_at)}</td>
                    <td className="px-5 py-3.5 whitespace-nowrap text-right">
                      <div className="flex items-center justify-end gap-1">
                        {post.status === 'published' && (
                          <Link href={route('blog.show', post.slug)} target="_blank" className="rounded-md p-2 text-slate-400 transition hover:bg-primary-50 hover:text-primary" title="View">
                            <Eye size={16} />
                          </Link>
                        )}
                        <Link href={route('admin.posts.edit', post.id)} className="rounded-md p-2 text-slate-400 transition hover:bg-slate-100 hover:text-slate-700" title="Edit">
                          <Edit size={16} />
                        </Link>
                        <button onClick={() => handleDelete(post.id)} className="rounded-md p-2 text-slate-400 transition hover:bg-accent-50 hover:text-accent" title="Delete">
                          <Trash2 size={16} />
                        </button>
                      </div>
                    </td>
                  </tr>
                ))}
              </tbody>
            </table>
          )}
        </div>

        {posts.links && posts.links.length > 3 && (
          <div className="flex justify-center">
            <nav className="flex items-center gap-1.5">
              {posts.links.map((link: any, index: number) => (
                <Link
                  key={index}
                  href={link.url || '#'}
                  className={`rounded-md px-3 py-1.5 text-xs font-medium transition ${
                    link.active
                      ? 'bg-primary text-white'
                      : link.url
                        ? 'border border-slate-200 bg-white text-slate-700 hover:bg-slate-50'
                        : 'bg-slate-50 text-slate-300 cursor-not-allowed'
                  }`}
                  preserveState
                  dangerouslySetInnerHTML={{ __html: link.label }}
                />
              ))}
            </nav>
          </div>
        )}
      </div>
    </DashboardLayout>
  );
}
