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
            <section className="py-16 md:py-20 bg-gradient-to-br from-white to-gray-50">
                <div className="container mx-auto px-6 text-center">
                    <div className="inline-flex items-center gap-2 border rounded-full px-6 py-3 mb-8 bg-accent/10 border-accent">
                        <i className="fas fa-blog text-sm text-accent"></i>
                        <span className="font-medium font-montserrat text-sm tracking-wide text-primary">
                            Insights & Stories
                        </span>
                    </div>

                    <h1 className="font-bold font-montserrat text-4xl md:text-5xl lg:text-6xl mb-6 leading-tight text-primary">
                        Our <span className="text-accent">Blog</span>
                    </h1>

                    <p className="text-xl text-gray-600 mb-12 max-w-3xl mx-auto leading-relaxed font-lato">
                        Discover insights, stories, and perspectives from our community of learners and educators.
                    </p>
                </div>
            </section>

            {/* Blog Posts Grid */}
            <section className="py-16 bg-white">
                <div className="container mx-auto px-6">
                    {posts.data.length === 0 ? (
                        <div className="text-center py-12">
                            <i className="fas fa-inbox text-6xl text-gray-300 mb-4"></i>
                            <p className="text-xl text-gray-600 font-lato">No blog posts available yet. Check back soon!</p>
                        </div>
                    ) : (
                        <>
                            <div className="grid md:grid-cols-2 lg:grid-cols-3 gap-8">
                                {posts.data.map((post) => (
                                    <Link
                                        key={post.id}
                                        href={route('blog.show', post.slug)}
                                        className="group bg-white rounded-xl overflow-hidden shadow-md hover:shadow-xl transition-all duration-300 border border-gray-100"
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
                                                <div className="w-full h-full flex items-center justify-center bg-gradient-to-br from-accent/20 to-primary/20">
                                                    <i className="fas fa-file-alt text-4xl text-accent/40"></i>
                                                </div>
                                            )}
                                        </div>

                                        {/* Content */}
                                        <div className="p-6">
                                            <h2 className="font-bold font-montserrat text-xl mb-3 text-primary group-hover:text-accent transition-colors line-clamp-2">
                                                {post.title}
                                            </h2>

                                            <p className="text-gray-600 font-lato mb-4 line-clamp-3">
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
