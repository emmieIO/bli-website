import { Head, Link } from '@inertiajs/react';
import GuestLayout from '@/Layouts/GuestLayout';
import { Calendar, Clock } from 'lucide-react';

interface Author { id: number; name: string; }

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
    links: Array<{ url: string | null; label: string; active: boolean; }>;
}

interface BlogIndexProps { posts: PaginatedPosts; }

export default function BlogIndex({ posts }: BlogIndexProps) {
    const formatDate = (dateString: string) => {
        return new Date(dateString).toLocaleDateString('en-US', { month: 'long', day: 'numeric', year: 'numeric' });
    };

    const getReadTime = (content: string) => {
        const minutes = Math.ceil(content.split(/\s+/).length / 200);
        return `${minutes} min read`;
    };

    return (
        <GuestLayout>
            <Head title="Blog" />

            <section className="bg-white py-3 md:py-8">
                <div className="section-shell">
                    <div className="mb-14">
                        <div className="inline-flex items-center gap-1.5 rounded-md bg-accent-50 px-2.5 py-1 text-[11px] font-semibold uppercase tracking-wider text-accent">
                            <i className="fas fa-blog text-[10px]" /> Insights & Stories
                        </div>
                        <h1 className="mt-3 text-3xl font-bold tracking-tight text-primary md:text-4xl">
                            Our Blog
                        </h1>
                        <p className="mt-3 max-w-xl text-sm leading-7 text-slate-500 md:text-base">
                            Discover insights, stories, and perspectives from our community of learners and educators.
                        </p>
                    </div>

                    {posts.data.length === 0 ? (
                        <div className="py-16 text-center">
                            <div className="mx-auto flex h-14 w-14 items-center justify-center rounded-lg bg-slate-100 text-slate-300">
                                <i className="fas fa-inbox text-lg" />
                            </div>
                            <p className="mt-4 text-sm text-slate-500">No blog posts available yet. Check back soon!</p>
                        </div>
                    ) : (
                        <>
                            <div className="grid gap-6 md:grid-cols-2 lg:grid-cols-3">
                                {posts.data.map((post) => (
                                    <Link
                                        key={post.id}
                                        href={route('blog.show', post.slug)}
                                        className="group flex flex-col overflow-hidden rounded-lg border border-slate-200 bg-white transition hover:-translate-y-1 hover:border-slate-300 hover:shadow-sm"
                                    >
                                        <div className="relative h-48 overflow-hidden bg-slate-100">
                                            {post.featured_image ? (
                                                <img src={`/storage/${post.featured_image}`} alt={post.title}
                                                    className="h-full w-full object-cover transition duration-500 group-hover:scale-105" />
                                            ) : (
                                                <div className="flex h-full w-full items-center justify-center text-slate-300">
                                                    <i className="fas fa-file-alt text-3xl" />
                                                </div>
                                            )}
                                        </div>
                                        <div className="flex flex-1 flex-col p-6">
                                            <div className="flex items-center gap-3 text-[11px] font-semibold uppercase tracking-wider text-slate-400 mb-3">
                                                <span className="flex items-center gap-1.5">
                                                    <Calendar size={12} /> {formatDate(post.published_at)}
                                                </span>
                                                <span className="text-slate-300">·</span>
                                                <span className="flex items-center gap-1.5">
                                                    <Clock size={12} /> {getReadTime(post.content)}
                                                </span>
                                            </div>

                                            <h2 className="text-lg font-semibold leading-snug text-slate-900 transition group-hover:text-accent line-clamp-2">
                                                {post.title}
                                            </h2>

                                            <p className="mt-2.5 text-sm leading-relaxed text-slate-500 line-clamp-3">
                                                {post.excerpt || post.content.replace(/<[^>]*>/g, '').substring(0, 150) + '...'}
                                            </p>

                                            <div className="mt-auto flex items-center justify-between border-t border-slate-100 pt-4">
                                                <div className="flex items-center gap-2">
                                                    <div className="flex h-7 w-7 items-center justify-center rounded-full bg-accent-50 text-accent text-[11px] font-bold">
                                                        {post.author.name.charAt(0)}
                                                    </div>
                                                    <span className="text-[13px] font-medium text-slate-600">{post.author.name}</span>
                                                </div>
                                                <span className="text-[13px] font-medium text-accent group-hover:translate-x-1 transition-transform">
                                                    Read article &rarr;
                                                </span>
                                            </div>
                                        </div>
                                    </Link>
                                ))}
                            </div>

                            {posts.last_page > 1 && (
                                <div className="mt-12 flex justify-center">
                                    <nav className="flex items-center gap-1.5">
                                        {posts.links.map((link, index) => (
                                            <Link
                                                key={index}
                                                href={link.url || '#'}
                                                className={`rounded-md px-3 py-1.5 text-xs font-medium transition ${
                                                    link.active
                                                        ? 'bg-accent text-white'
                                                        : link.url
                                                            ? 'border border-slate-200 bg-white text-slate-600 hover:bg-slate-50'
                                                            : 'text-slate-300 cursor-not-allowed'
                                                }`}
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
