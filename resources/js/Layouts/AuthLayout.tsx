import { PropsWithChildren } from 'react';
import { Head, Link } from '@inertiajs/react';
import { ToastContainer, useToastNotifications } from '@/Components/Toast';
import { ArrowLeft, CheckCircle2 } from 'lucide-react';

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
                {/* One shared photographic shell keeps every auth screen connected to the public BLI experience. */}
                <div className="relative hidden flex-col justify-between overflow-hidden bg-primary p-10 lg:flex lg:p-14">
                    <img
                        src="/assets/img/leadership-workshop.png"
                        alt="Leaders participating in a focused BLI-style workshop"
                        className="absolute inset-0 h-full w-full object-cover object-[58%_center] saturate-[0.9]"
                    />
                    <div className="absolute inset-0 bg-primary/65" />
                    <div className="absolute inset-0 bg-slate-950/10" />

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
                        <div className="inline-flex items-center gap-2 border-l-2 border-accent-300 pl-3 text-[11px] font-semibold uppercase text-accent-200">
                            <CheckCircle2 size={14} />
                            Leadership Formation
                        </div>
                        <h2 className="mt-6 text-3xl font-bold leading-tight text-white lg:text-4xl">
                            Leadership grows through focused practice.
                        </h2>
                        <p className="mt-4 max-w-sm text-sm leading-relaxed text-primary-200">
                            Return to your events, mentorship goals, resources, and the next action in your development journey.
                        </p>

                        <div className="mt-10 grid grid-cols-2 gap-px overflow-hidden border-y border-white/20 bg-white/20">
                            {['Character and clarity', 'Competence and capacity'].map((focus) => (
                                <div key={focus} className="bg-primary/70 px-4 py-4 text-xs font-semibold uppercase text-white backdrop-blur-sm">
                                    {focus}
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
                    {/* Mobile still gets a human visual cue without pushing the form below the fold. */}
                    <div className="relative h-36 overflow-hidden bg-primary lg:hidden">
                        <img src="/assets/img/leadership-workshop.png" alt="Leaders learning together" className="h-full w-full object-cover object-center" />
                        <div className="absolute inset-0 bg-primary/55" />
                        <Link href={route('homepage')} className="absolute inset-x-5 bottom-4 flex items-center gap-2.5 text-white">
                            <img src="/assets/img/bli-mark.png" alt="" className="h-9 w-9 rounded-sm bg-white object-contain" />
                            <span className="text-sm font-semibold">Beacon Leadership Institute</span>
                        </Link>
                    </div>

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
