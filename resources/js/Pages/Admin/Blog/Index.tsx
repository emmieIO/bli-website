import { Head, Link, router, usePage } from '@inertiajs/react';
import DashboardLayout from '@/Layouts/DashboardLayout';
import { Plus, Edit, Trash2, Eye } from 'lucide-react';

interface Author {
    id: number;
    name: string;
}

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
    posts: {
        data: Post[];
        links?: any;
        meta?: any;
    };
}

export default function PostsIndex({ posts }: PostsProps) {
    const { sideLinks } = usePage().props as any;
    const postsList = posts.data || [];

    const handleDelete = (postId: number) => {
        if (confirm('Are you sure you want to delete this post?')) {
            router.delete(route('admin.posts.destroy', postId), {
                preserveScroll: true,
            });
        }
    };

    const formatDate = (dateString: string | null) => {
        if (!dateString) return 'N/A';
        return new Date(dateString).toLocaleDateString('en-US', {
            year: 'numeric',
            month: 'short',
            day: 'numeric',
        });
    };

    const getStatusBadge = (status: 'draft' | 'published') => {
        if (status === 'published') {
            return 'bg-green-100 text-green-800';
        }
        return 'bg-gray-100 text-gray-800';
    };

    return (
        <DashboardLayout sideLinks={sideLinks}>
            <Head title="Blog Posts" />

            <div className="py-8">
                <div className="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                    {/* Header */}
                    <div className="flex justify-between items-center mb-6">
                        <div>
                            <h1 className="text-3xl font-bold text-gray-900">Blog Posts</h1>
                            <p className="mt-1 text-sm text-gray-600">
                                Manage your blog posts
                            </p>
                        </div>
                        <Link
                            href={route('admin.posts.create')}
                            className="inline-flex items-center gap-2 px-4 py-2 bg-accent text-white rounded-lg hover:bg-accent/90 transition-colors"
                        >
                            <Plus size={20} />
                            New Post
                        </Link>
                    </div>

                    {/* Posts Table */}
                    <div className="bg-white shadow-md rounded-lg overflow-hidden">
                        {postsList.length === 0 ? (
                            <div className="text-center py-12">
                                <i className="fas fa-inbox text-6xl text-gray-300 mb-4"></i>
                                <p className="text-xl text-gray-600">No blog posts yet. Create your first post!</p>
                                <Link
                                    href={route('admin.posts.create')}
                                    className="inline-flex items-center gap-2 px-6 py-3 mt-6 bg-accent text-white rounded-lg hover:bg-accent/90 transition-colors"
                                >
                                    <Plus size={20} />
                                    Create Post
                                </Link>
                            </div>
                        ) : (
                            <table className="min-w-full divide-y divide-gray-200">
                                <thead className="bg-gray-50">
                                    <tr>
                                        <th className="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            Title
                                        </th>
                                        <th className="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            Author
                                        </th>
                                        <th className="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            Status
                                        </th>
                                        <th className="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            Published
                                        </th>
                                        <th className="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            Actions
                                        </th>
                                    </tr>
                                </thead>
                                <tbody className="bg-white divide-y divide-gray-200">
                                    {postsList.map((post) => (
                                        <tr key={post.id} className="hover:bg-gray-50">
                                            <td className="px-6 py-4">
                                                <div className="text-sm font-medium text-gray-900">
                                                    {post.title}
                                                </div>
                                                <div className="text-sm text-gray-500">/blog/{post.slug}</div>
                                            </td>
                                            <td className="px-6 py-4 whitespace-nowrap">
                                                <div className="text-sm text-gray-900">{post.author.name}</div>
                                            </td>
                                            <td className="px-6 py-4 whitespace-nowrap">
                                                <span className={`px-3 py-1 inline-flex text-xs leading-5 font-semibold rounded-full ${getStatusBadge(post.status)}`}>
                                                    {post.status.charAt(0).toUpperCase() + post.status.slice(1)}
                                                </span>
                                            </td>
                                            <td className="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                                {formatDate(post.published_at)}
                                            </td>
                                            <td className="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                                <div className="flex items-center justify-end gap-2">
                                                    {post.status === 'published' && (
                                                        <Link
                                                            href={route('blog.show', post.slug)}
                                                            target="_blank"
                                                            className="text-blue-600 hover:text-blue-900 p-2 hover:bg-blue-50 rounded-lg transition-colors"
                                                            title="View"
                                                        >
                                                            <Eye size={18} />
                                                        </Link>
                                                    )}
                                                    <Link
                                                        href={route('admin.posts.edit', post.id)}
                                                        className="text-indigo-600 hover:text-indigo-900 p-2 hover:bg-indigo-50 rounded-lg transition-colors"
                                                        title="Edit"
                                                    >
                                                        <Edit size={18} />
                                                    </Link>
                                                    <button
                                                        onClick={() => handleDelete(post.id)}
                                                        className="text-red-600 hover:text-red-900 p-2 hover:bg-red-50 rounded-lg transition-colors"
                                                        title="Delete"
                                                    >
                                                        <Trash2 size={18} />
                                                    </button>
                                                </div>
                                            </td>
                                        </tr>
                                    ))}
                                </tbody>
                            </table>
                        )}
                    </div>

                    {/* Pagination */}
                    {posts.links && posts.links.length > 3 && (
                        <div className="mt-6 flex justify-center">
                            <nav className="flex items-center gap-2">
                                {posts.links.map((link: any, index: number) => (
                                    <Link
                                        key={index}
                                        href={link.url || '#'}
                                        className={`px-4 py-2 rounded-lg border transition-all ${
                                            link.active
                                                ? 'bg-accent text-white border-accent'
                                                : 'bg-white text-gray-700 border-gray-300 hover:border-accent hover:text-accent'
                                        } ${!link.url ? 'opacity-50 cursor-not-allowed' : ''}`}
                                        preserveState
                                        dangerouslySetInnerHTML={{ __html: link.label }}
                                    />
                                ))}
                            </nav>
                        </div>
                    )}
                </div>
            </div>
        </DashboardLayout>
    );
}
