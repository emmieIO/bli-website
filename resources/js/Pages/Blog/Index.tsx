import { Head, Link } from '@inertiajs/react';
import GuestLayout from '@/Layouts/GuestLayout';
import { Calendar, Clock, User } from 'lucide-react';

interface Author {
    id: number;
    name: string;
}

interface Post {
    id: number;
    title: string;
    slug: string;
    excerpt: string;
    content: string;
    featured_image: string | null;
    author: Author;
    published_at: string;
}

interface PaginatedPosts {
    data: Post[];
    current_page: number;
    last_page: number;
    per_page: number;
    total: number;
    links: Array<{
        url: string | null;
        label: string;
        active: boolean;
    }>;
}

interface BlogIndexProps {
    posts: PaginatedPosts;
}

export default function BlogIndex({ posts }: BlogIndexProps) {
    const formatDate = (dateString: string) => {
        const date = new Date(dateString);
        return date.toLocaleDateString('en-US', { month: 'long', day: 'numeric', year: 'numeric' });
    };

    const getReadTime = (content: string) => {
        const wordsPerMinute = 200;
        const words = content.split(/\s+/).length;
        const minutes = Math.ceil(words / wordsPerMinute);
        return `${minutes} min read`;
    };

    return (
        <GuestLayout>
            <Head title="Blog" />

            {/* Hero Section */}
            <section className="public-hero">
                <div className="section-shell text-center">
                    <div className="inline-flex items-center gap-2 rounded-full border border-accent/15 bg-accent/5 px-6 py-3 mb-8">
                        <i className="fas fa-blog text-sm text-accent"></i>
                        <span className="text-sm font-medium tracking-wide text-primary">
                            Insights & Stories
                        </span>
                    </div>

                    <h1 className="public-hero-title mb-6">
                        Our <span className="text-accent">Blog</span>
                    </h1>

                    <p className="public-hero-copy mx-auto mb-12">
                        Discover insights, stories, and perspectives from our community of learners and educators.
                    </p>
                </div>
            </section>

            {/* Blog Posts Grid */}
            <section className="public-section bg-white">
                <div className="section-shell">
                    {posts.data.length === 0 ? (
                        <div className="text-center py-12">
                            <i className="fas fa-inbox text-6xl text-gray-300 mb-4"></i>
                            <p className="text-xl text-gray-600">No blog posts available yet. Check back soon!</p>
                        </div>
                    ) : (
                        <>
                            <div className="grid md:grid-cols-2 lg:grid-cols-3 gap-8">
                                {posts.data.map((post) => (
                                    <Link
                                        key={post.id}
                                        href={route('blog.show', post.slug)}
                                        className="public-card group overflow-hidden transition-all duration-300 hover:-translate-y-1 hover:shadow-xl"
                                    >
                                        {/* Featured Image */}
                                        <div className="relative h-48 bg-gray-200 overflow-hidden">
                                            {post.featured_image ? (
                                                <img
                                                    src={`/storage/${post.featured_image}`}
                                                    alt={post.title}
                                                    className="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300"
                                                />
                                            ) : (
                                                <div className="flex h-full w-full items-center justify-center bg-gray-100">
                                                    <i className="fas fa-file-alt text-4xl text-primary/30"></i>
                                                </div>
                                            )}
                                        </div>

                                        {/* Content */}
                                        <div className="p-6">
                                            <h2 className="mb-3 line-clamp-2 text-xl font-bold text-primary transition-colors group-hover:text-accent">
                                                {post.title}
                                            </h2>

                                            <p className="mb-4 line-clamp-3 text-gray-600">
                                                {post.excerpt || post.content.substring(0, 150) + '...'}
                                            </p>

                                            {/* Meta Info */}
                                            <div className="flex items-center gap-4 text-sm text-gray-500 pt-4 border-t border-gray-100">
                                                <div className="flex items-center gap-1">
                                                    <User size={14} />
                                                    <span>{post.author.name}</span>
                                                </div>
                                                <div className="flex items-center gap-1">
                                                    <Calendar size={14} />
                                                    <span>{formatDate(post.published_at)}</span>
                                                </div>
                                                <div className="flex items-center gap-1">
                                                    <Clock size={14} />
                                                    <span>{getReadTime(post.content)}</span>
                                                </div>
                                            </div>
                                        </div>
                                    </Link>
                                ))}
                            </div>

                            {/* Pagination */}
                            {posts.last_page > 1 && (
                                <div className="mt-12 flex justify-center">
                                    <nav className="flex items-center gap-2">
                                        {posts.links.map((link, index) => (
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
                        </>
                    )}
                </div>
            </section>
        </GuestLayout>
    );
}
