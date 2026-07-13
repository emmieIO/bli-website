import { PropsWithChildren } from 'react';
import { Head, Link } from '@inertiajs/react';
import { ToastContainer, useToastNotifications } from '@/Components/Toast';
import { ArrowLeft, GraduationCap } from 'lucide-react';

interface AuthLayoutProps extends PropsWithChildren {
    title?: string;
    description?: string;
    wide?: boolean;
}

export default function AuthLayout({ title, description, wide, children }: AuthLayoutProps) {
    useToastNotifications();

    return (
        <>
            <Head title={title || 'Authentication'} />
            <ToastContainer />

            <div className="grid min-h-screen lg:grid-cols-2">
                {/* Left: Brand Panel */}
                <div className="relative hidden flex-col justify-between overflow-hidden bg-primary p-10 lg:flex lg:p-14">
                    <div className="absolute -right-32 -top-32 h-96 w-96 rounded-full bg-accent/10 blur-3xl" />
                    <div className="absolute -bottom-20 -left-20 h-72 w-72 rounded-full bg-accent/8 blur-3xl" />
                    <div className="absolute left-1/3 top-1/2 h-64 w-64 -translate-y-1/2 rounded-full bg-white/5 blur-3xl" />

                    <div className="relative z-10">
                        <Link href={route('homepage')} className="flex items-center gap-3">
                            <img
                                src="/assets/img/bli-mark.png"
                                alt="BLI"
                                className="h-12 w-12 rounded-md bg-white object-contain ring-1 ring-white/15"
                            />
                            <span className="text-sm font-semibold tracking-tight text-white">Beacon Leadership Institute</span>
                        </Link>
                    </div>

                    <div className="relative z-10">
                        <div className="inline-flex items-center gap-2 rounded-full border border-accent/20 bg-accent/10 px-4 py-1.5 text-[11px] font-semibold uppercase tracking-wider text-accent-200 backdrop-blur-sm">
                            <GraduationCap size={14} />
                            Leadership Formation
                        </div>
                        <h2 className="mt-6 text-3xl font-bold leading-tight text-white lg:text-4xl">
                            Shape the next generation of leaders.
                        </h2>
                        <p className="mt-4 max-w-sm text-sm leading-relaxed text-primary-200">
                            Access live events and a community focused on developing leaders
                            with spiritual depth and practical clarity.
                        </p>

                        <div className="mt-10 grid grid-cols-3 gap-6 border-t border-white/10 pt-8">
                            {[
                                { value: '500+', label: 'Leaders enrolled' },
                                { value: '20+', label: 'Active events' },
                                { value: '15+', label: 'Expert instructors' },
                            ].map((stat) => (
                                <div key={stat.label}>
                                    <p className="text-2xl font-bold tracking-tight text-white">{stat.value}</p>
                                    <p className="mt-1 text-xs font-medium uppercase tracking-wider text-primary-200">{stat.label}</p>
                                </div>
                            ))}
                        </div>
                    </div>

                    <div className="relative z-10">
                        <p className="text-xs text-primary-300">
                            &copy; {new Date().getFullYear()} Beacon Leadership Institute. All rights reserved.
                        </p>
                    </div>
                </div>

                {/* Right: Form Panel */}
                <div className="flex flex-col bg-white">
                    <div className="flex items-center justify-between px-6 py-4 lg:px-10">
                        <Link
                            href={route('homepage')}
                            className="inline-flex items-center gap-1.5 text-sm font-medium text-slate-500 transition hover:text-primary"
                        >
                            <ArrowLeft size={16} />
                            Back to site
                        </Link>
                        <span className="text-xs text-slate-400">BLI</span>
                    </div>

                    <div className="flex flex-1 items-center justify-center px-6 py-8 lg:px-16">
                        <div className={`w-full ${wide ? 'max-w-md' : 'max-w-sm'}`}>
                            {title && (
                                <div className="mb-8">
                                    <h1 className="text-2xl font-bold tracking-tight text-primary">
                                        {title}
                                    </h1>
                                    {description && (
                                        <p className="mt-2 text-sm leading-relaxed text-slate-500">{description}</p>
                                    )}
                                    <div className="mt-4 h-1 w-10 rounded-full bg-accent" />
                                </div>
                            )}

                            {children}
                        </div>
                    </div>
                </div>
            </div>
        </>
    );
}
