import { PropsWithChildren } from 'react';
import { Head, Link } from '@inertiajs/react';
import { ToastContainer, useToastNotifications } from '@/Components/Toast';
import { ArrowLeft } from 'lucide-react';

interface AuthLayoutProps extends PropsWithChildren {
    title?: string;
    description?: string;
    wide?: boolean;
}

export default function AuthLayout({ title, description, wide = false, children }: AuthLayoutProps) {
    useToastNotifications();

    return (
        <>
            <Head title={title || 'Authentication'} />
            <ToastContainer />
            <div className="lg:flex h-screen overflow-hidden">
                {/* Left: Brand Panel */}
                <div className="fixed inset-y-0 left-0 hidden w-1/2 flex-col justify-between bg-primary p-12 lg:flex">
                    <div
                        className="absolute inset-0 bg-cover bg-center opacity-15"
                        style={{ backgroundImage: 'url(/images/auth.jpg)' }}
                    />
                    <div className="absolute -right-20 -top-20 h-72 w-72 rounded-full bg-secondary-400/15 blur-3xl" />
                    <div className="absolute -bottom-32 -left-20 h-60 w-60 rounded-full bg-white/10 blur-3xl" />
                    <div className="absolute left-1/2 top-1/3 h-40 w-40 -translate-x-1/2 -translate-y-1/2 rounded-full bg-slate-200/10 blur-3xl" />

                    <div className="relative z-10">
                        <Link href={route('homepage')} className="flex items-center gap-3">
                            <img
                                src="/images/logo.jpg"
                                alt="BLI"
                                className="h-11 w-11 rounded-md object-cover ring-2 ring-white/20"
                            />
                            <p className="text-base font-bold text-white">Beacon Leadership Institute</p>
                        </Link>
                    </div>

                    <div className="relative z-10">
                        <h2 className="text-4xl font-bold leading-tight text-secondary">
                            Shape the next generation of leaders.
                        </h2>
                        <div className="my-5 h-1 w-16 rounded-full bg-sky-300" />
                        <p className="mt-4 text-base leading-relaxed text-white/80">
                            Access structured courses, live events, and a community focused on developing leaders
                            with spiritual depth and practical clarity.
                        </p>
                    </div>

                    <div className="relative z-10 border-t border-white/10 pt-6">
                        <p className="text-sm text-white/50">
                            &copy; {new Date().getFullYear()} Beacon Leadership Institute
                        </p>
                    </div>
                </div>

                {/* Right: Form Panel */}
                <div className="flex w-full flex-col overflow-y-auto bg-white lg:ml-[50%] lg:w-1/2">
                    <header className="flex items-center justify-end border-b border-accent-100 bg-white/80 px-8 py-4 backdrop-blur-sm">
                        <Link
                            href={route('homepage')}
                            className="inline-flex items-center gap-1.5 text-sm font-medium text-primary transition hover:text-accent"
                        >
                            <ArrowLeft className="h-4 w-4" />
                            Back to site
                        </Link>
                    </header>

                    <main className="flex flex-1 items-center justify-center px-8 py-12">
                        <div className={`w-full ${wide ? 'max-w-xl' : 'max-w-sm'}`}>
                            {title && (
                                <div className="mb-8">
                                    <h1 className="text-2xl font-bold tracking-tight text-primary">
                                        {title}
                                    </h1>
                                    {description && (
                                        <p className="mt-2 text-sm leading-6 text-primary-400">{description}</p>
                                    )}
                                    <div className="mt-4 h-1 w-10 rounded-full bg-accent" />
                                </div>
                            )}

                            {children}
                        </div>
                    </main>
                </div>
            </div>
        </>
    );
}
