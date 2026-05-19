import { Head, Link } from '@inertiajs/react';
import GuestLayout from '@/Layouts/GuestLayout';
import { Calendar, Clock, User, ArrowLeft } from 'lucide-react';

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

interface BlogShowProps {
    post: Post;
    relatedPosts: Post[];
}

export default function BlogShow({ post, relatedPosts }: BlogShowProps) {
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
            <Head title={post.title} />

            {/* Article Header */}
            <article className="public-hero public-hero-compact">
                <div className="section-shell max-w-4xl">
                    <Link
                        href={route('blog.index')}
                        className="inline-flex items-center gap-2 text-accent hover:text-primary transition-colors mb-8 font-medium"
                    >
                        <ArrowLeft size={18} />
                        Back to Blog
                    </Link>

                    <h1 className="public-hero-title mb-6 text-left">
                        {post.title}
                    </h1>

                    {post.excerpt && (
                        <p className="public-hero-copy mb-8">
                            {post.excerpt}
                        </p>
                    )}

                    <div className="flex flex-wrap items-center gap-6 text-sm text-gray-600 pb-8 border-b border-gray-200">
                        <div className="flex items-center gap-2">
                            <User size={16} className="text-accent" />
                            <span className="font-medium">{post.author.name}</span>
                        </div>
                        <div className="flex items-center gap-2">
                            <Calendar size={16} className="text-accent" />
                            <span>{formatDate(post.published_at)}</span>
                        </div>
                        <div className="flex items-center gap-2">
                            <Clock size={16} className="text-accent" />
                            <span>{getReadTime(post.content)}</span>
                        </div>
                    </div>
                </div>
            </article>

            {/* Featured Image */}
            {post.featured_image && (
                <div className="section-shell max-w-4xl -mt-6 mb-12">
                    <div className="public-card overflow-hidden p-3">
                        <img
                            src={`/storage/${post.featured_image}`}
                            alt={post.title}
                            className="w-full h-auto"
                        />
                    </div>
                </div>
            )}

            {/* Article Content */}
            <section className="public-section bg-white">
                <div className="section-shell max-w-4xl">
                    <div
                        className="prose prose-lg max-w-none
                            prose-headings:font-sans prose-headings:text-primary
                            prose-a:text-accent prose-a:no-underline hover:prose-a:underline
                            prose-strong:text-primary
                            prose-img:rounded-lg prose-img:shadow-md"
                        dangerouslySetInnerHTML={{ __html: post.content }}
                    />
                </div>
            </section>

            {/* Related Posts */}
            {relatedPosts.length > 0 && (
                <section className="public-section bg-gray-50">
                    <div className="section-shell max-w-6xl">
                        <h2 className="mb-8 text-3xl font-bold text-primary">
                            Related Articles
                        </h2>

                        <div className="grid md:grid-cols-3 gap-6">
                            {relatedPosts.map((relatedPost) => (
                                <Link
                                    key={relatedPost.id}
                                    href={route('blog.show', relatedPost.slug)}
                                    className="public-card group overflow-hidden transition-all duration-300 hover:-translate-y-1 hover:shadow-xl"
                                >
                                    <div className="relative h-40 bg-gray-200 overflow-hidden">
                                        {relatedPost.featured_image ? (
                                            <img
                                                src={`/storage/${relatedPost.featured_image}`}
                                                alt={relatedPost.title}
                                                className="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300"
                                            />
                                        ) : (
                                            <div className="flex h-full w-full items-center justify-center bg-gray-100">
                                                <i className="fas fa-file-alt text-3xl text-primary/30"></i>
                                            </div>
                                        )}
                                    </div>

                                    <div className="p-4">
                                        <h3 className="mb-2 line-clamp-2 text-lg font-bold text-primary transition-colors group-hover:text-accent">
                                            {relatedPost.title}
                                        </h3>
                                        <p className="text-sm text-gray-500">
                                            {formatDate(relatedPost.published_at)}
                                        </p>
                                    </div>
                                </Link>
                            ))}
                        </div>
                    </div>
                </section>
            )}
        </GuestLayout>
    );
}
