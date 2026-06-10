import { Head, Link } from '@inertiajs/react';
import GuestLayout from '@/Layouts/GuestLayout';
import { Calendar, Clock, User, ArrowLeft } from 'lucide-react';

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

interface BlogShowProps { post: Post; relatedPosts: Post[]; }

export default function BlogShow({ post, relatedPosts }: BlogShowProps) {
    const formatDate = (dateString: string) => {
        return new Date(dateString).toLocaleDateString('en-US', { month: 'long', day: 'numeric', year: 'numeric' });
    };

    const getReadTime = (content: string) => {
        return `${Math.ceil(content.split(/\s+/).length / 200)} min read`;
    };

    return (
        <GuestLayout>
            <Head title={post.title} />

            <section className="bg-white py-12 md:py-20">
                <div className="section-shell max-w-3xl">
                    <Link href={route('blog.index')} className="inline-flex items-center gap-1.5 text-sm font-medium text-slate-500 transition hover:text-accent mb-8">
                        <ArrowLeft size={16} /> Back to Blog
                    </Link>

                    <h1 className="text-3xl font-bold tracking-tight text-primary md:text-4xl">
                        {post.title}
                    </h1>

                    {post.excerpt && (
                        <p className="mt-4 text-lg leading-relaxed text-slate-500">{post.excerpt}</p>
                    )}

                    <div className="mt-6 flex flex-wrap items-center gap-5 text-sm text-slate-400 pb-8 border-b border-slate-200">
                        <span className="flex items-center gap-1.5">
                            <User size={15} className="text-accent" />
                            <span className="font-medium text-slate-600">{post.author.name}</span>
                        </span>
                        <span className="flex items-center gap-1.5">
                            <Calendar size={15} className="text-accent" />
                            {formatDate(post.published_at)}
                        </span>
                        <span className="flex items-center gap-1.5">
                            <Clock size={15} className="text-accent" />
                            {getReadTime(post.content)}
                        </span>
                    </div>

                    {post.featured_image && (
                        <div className="mt-8 overflow-hidden rounded-lg border border-slate-200 shadow-sm">
                            <img src={`/storage/${post.featured_image}`} alt={post.title} className="w-full" />
                        </div>
                    )}

                    <div className="mt-10 prose prose-slate max-w-none
                        prose-headings:font-semibold prose-headings:tracking-tight prose-headings:text-primary
                        prose-a:text-accent prose-a:no-underline hover:prose-a:underline
                        prose-strong:text-slate-900
                        prose-img:rounded-lg"
                        dangerouslySetInnerHTML={{ __html: post.content }}
                    />
                </div>
            </section>

            {relatedPosts.length > 0 && (
                <section className="border-t border-slate-200 bg-slate-50 py-16 md:py-20">
                    <div className="section-shell">
                        <h2 className="text-2xl font-bold tracking-tight text-primary mb-8">Related Articles</h2>
                        <div className="grid gap-5 md:grid-cols-3">
                            {relatedPosts.map((related) => (
                                <Link key={related.id} href={route('blog.show', related.slug)}
                                    className="group flex flex-col overflow-hidden rounded-lg border border-slate-200 bg-white transition hover:-translate-y-1 hover:border-slate-300 hover:shadow-sm">
                                    <div className="relative h-40 overflow-hidden bg-slate-100">
                                        {related.featured_image ? (
                                            <img src={`/storage/${related.featured_image}`} alt={related.title}
                                                className="h-full w-full object-cover transition duration-500 group-hover:scale-105" />
                                        ) : (
                                            <div className="flex h-full w-full items-center justify-center text-slate-300">
                                                <i className="fas fa-file-alt text-2xl" />
                                            </div>
                                        )}
                                    </div>
                                    <div className="p-4">
                                        <h3 className="text-sm font-semibold leading-snug text-slate-900 transition group-hover:text-accent line-clamp-2">
                                            {related.title}
                                        </h3>
                                        <p className="mt-2 text-[13px] text-slate-400">{formatDate(related.published_at)}</p>
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
