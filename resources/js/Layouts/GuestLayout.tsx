import { PropsWithChildren, useEffect, useRef, useState } from 'react';
import { Link, usePage } from '@inertiajs/react';
import { ToastContainer, useToastNotifications } from '@/Components/Toast';
import { PageProps } from '@/types';

export default function GuestLayout({ children }: PropsWithChildren) {
    const { auth } = usePage<PageProps>().props;
    const [mobileMenuOpen, setMobileMenuOpen] = useState(false);
    const [joinUsDropdownOpen, setJoinUsDropdownOpen] = useState(false);
    const joinUsDropdownRef = useRef<HTMLLIElement>(null);

    useToastNotifications();

    useEffect(() => {
        function handleClickOutside(event: MouseEvent) {
            if (joinUsDropdownRef.current && !joinUsDropdownRef.current.contains(event.target as Node)) {
                setJoinUsDropdownOpen(false);
            }
        }
        document.addEventListener('mousedown', handleClickOutside);
        return () => document.removeEventListener('mousedown', handleClickOutside);
    }, []);

    const navLinkClass = 'text-sm font-medium text-slate-600 transition-colors hover:text-slate-950';
    const mobileNavLinkClass = 'block rounded-lg px-3 py-2 text-sm font-medium text-slate-700 transition hover:bg-slate-100 hover:text-slate-950';

    return (
        <div className="flex min-h-screen flex-col overflow-x-hidden bg-slate-50">
            <ToastContainer />

            <nav className="sticky top-0 z-50 border-b border-slate-200/80 bg-white/90 backdrop-blur-xl">
                <div className="section-shell flex items-center justify-between py-3.5">
                    <Link href={route('homepage')} className="flex items-center gap-2.5">
                        <img src="/assets/img/bli-mark.png" alt="Beacon Leadership Institute" className="h-12 w-12 rounded-md object-contain" />
                        <span className="text-sm font-semibold tracking-tight text-primary">Beacon Leadership Institute</span>
                    </Link>

                    <div className="hidden items-center gap-6 lg:flex">
                        <Link href={route('homepage')} className={navLinkClass}>Home</Link>
                        <Link href={route('events.index')} className={navLinkClass}>Events</Link>
                        <Link href={route('mentorship.index')} className={navLinkClass}>Mentorship</Link>
                        <Link href={route('blog.index')} className={navLinkClass}>Insights</Link>
                        <Link href={route('contact-us')} className={navLinkClass}>Contact</Link>
                        <li className="relative list-none" ref={joinUsDropdownRef}>
                            <button
                                onClick={() => setJoinUsDropdownOpen((value) => !value)}
                                className="flex items-center gap-1.5 text-sm font-medium text-slate-600 transition-colors hover:text-slate-950"
                            >
                                Join Us
                                <i className={`fas fa-chevron-down text-[10px] transition-transform ${joinUsDropdownOpen ? 'rotate-180' : ''}`}></i>
                            </button>
                            {joinUsDropdownOpen && (
                                <div className="absolute left-0 mt-2 w-60 overflow-hidden rounded-lg border border-slate-200 bg-white p-1.5 shadow-lg">
                                    <Link href={route('instructors.become-an-instructor')} className="block rounded-md px-3.5 py-2.5 transition hover:bg-slate-50">
                                        <p className="text-sm font-semibold text-slate-900">Become an Instructor</p>
                                        <p className="mt-0.5 text-xs text-slate-500">Teach through structured programs.</p>
                                    </Link>
                                    <Link href={route('become-a-speaker')} className="block rounded-md px-3.5 py-2.5 transition hover:bg-slate-50">
                                        <p className="text-sm font-semibold text-slate-900">Become a Speaker</p>
                                        <p className="mt-0.5 text-xs text-slate-500">Contribute to event experiences.</p>
                                    </Link>
                                </div>
                            )}
                        </li>
                    </div>

                    <div className="hidden items-center gap-2.5 lg:flex">
                        {auth.user ? (
                            <>
                                <Link href={route('user_dashboard')} className="inline-flex items-center rounded-lg bg-primary px-4 py-2.5 text-sm font-semibold text-white transition hover:bg-primary-600 shadow-sm">
                                    Workspace
                                </Link>
                                <Link href={route('logout')} method="post" as="button" className="inline-flex items-center rounded-lg border border-slate-200 bg-white px-4 py-2.5 text-sm font-semibold text-slate-700 transition hover:bg-slate-50">
                                    Sign out
                                </Link>
                            </>
                        ) : (
                            <>
                                <Link href={route('login')} className="inline-flex items-center rounded-lg border border-slate-200 bg-white px-4 py-2.5 text-sm font-semibold text-slate-700 transition hover:bg-slate-50">
                                    Sign in
                                </Link>
                                <Link href={route('register')} className="inline-flex items-center rounded-lg bg-primary px-4 py-2.5 text-sm font-semibold text-white transition hover:bg-primary-600 shadow-sm">
                                    Get Started
                                </Link>
                            </>
                        )}
                    </div>

                    <button onClick={() => setMobileMenuOpen((value) => !value)} className="inline-flex h-10 w-10 items-center justify-center rounded-lg border border-slate-200 bg-white text-slate-900 lg:hidden">
                        <i className={`fas ${mobileMenuOpen ? 'fa-times' : 'fa-bars'}`}></i>
                    </button>
                </div>

                {mobileMenuOpen && (
                    <div className="border-t border-slate-200 bg-white lg:hidden">
                        <div className="section-shell space-y-1.5 py-4">
                            <Link href={route('homepage')} className={mobileNavLinkClass}>Home</Link>
                            <Link href={route('events.index')} className={mobileNavLinkClass}>Events</Link>
                            <Link href={route('mentorship.index')} className={mobileNavLinkClass}>Mentorship</Link>
                            <Link href={route('blog.index')} className={mobileNavLinkClass}>Insights</Link>
                            <Link href={route('contact-us')} className={mobileNavLinkClass}>Contact</Link>
                            <Link href={route('instructors.become-an-instructor')} className={mobileNavLinkClass}>Become an Instructor</Link>
                            <Link href={route('become-a-speaker')} className={mobileNavLinkClass}>Become a Speaker</Link>
                            {auth.user ? (
                                <>
                                    <Link href={route('user_dashboard')} className={mobileNavLinkClass}>Workspace</Link>
                                    <Link href={route('logout')} method="post" as="button" className={`${mobileNavLinkClass} w-full text-left`}>Sign out</Link>
                                </>
                            ) : (
                                <>
                                    <Link href={route('login')} className={mobileNavLinkClass}>Sign in</Link>
                                    <Link href={route('register')} className={mobileNavLinkClass}>Get started</Link>
                                </>
                            )}
                        </div>
                    </div>
                )}
            </nav>

            <main className="flex-1">{children}</main>

            <footer className="border-t border-slate-200 bg-white">
                <div className="section-shell py-10">
                    <div className="grid gap-8 sm:grid-cols-2 lg:grid-cols-4">
                        <div>
                            <div className="flex items-center gap-2.5 mb-3">
                                <img src="/assets/img/bli-mark.png" alt="Beacon Leadership Institute" className="h-10 w-10 rounded-md object-contain" />
                                <span className="text-sm font-semibold text-primary">Beacon Leadership Institute</span>
                            </div>
                            <p className="text-sm leading-relaxed text-slate-500">Forming leaders with spiritual depth and practical clarity across ministry, business, education, and public service.</p>
                        </div>
                        <div>
                            <h4 className="text-xs font-semibold uppercase tracking-wider text-slate-400 mb-3">Programs</h4>
                            <div className="space-y-2">
                                <Link href={route('events.index')} className="block text-sm text-slate-600 transition hover:text-primary">Events</Link>
                                <Link href={route('mentorship.index')} className="block text-sm text-slate-600 transition hover:text-primary">Mentorship</Link>
                                <Link href={route('blog.index')} className="block text-sm text-slate-600 transition hover:text-primary">Insights</Link>
                            </div>
                        </div>
                        <div>
                            <h4 className="text-xs font-semibold uppercase tracking-wider text-slate-400 mb-3">Join</h4>
                            <div className="space-y-2">
                                <Link href={route('register')} className="block text-sm text-slate-600 transition hover:text-primary">Create Account</Link>
                                <Link href={route('instructors.become-an-instructor')} className="block text-sm text-slate-600 transition hover:text-primary">Become an Instructor</Link>
                                <Link href={route('become-a-speaker')} className="block text-sm text-slate-600 transition hover:text-primary">Become a Speaker</Link>
                            </div>
                        </div>
                        <div>
                            <h4 className="text-xs font-semibold uppercase tracking-wider text-slate-400 mb-3">Legal</h4>
                            <div className="space-y-2">
                                <Link href={route('privacy-policy')} className="block text-sm text-slate-600 transition hover:text-primary">Privacy Policy</Link>
                                <Link href={route('terms-of-service')} className="block text-sm text-slate-600 transition hover:text-primary">Terms of Service</Link>
                                <Link href={route('contact-us')} className="block text-sm text-slate-600 transition hover:text-primary">Contact</Link>
                            </div>
                        </div>
                    </div>
                    <div className="mt-8 border-t border-slate-100 pt-6 text-center text-xs text-slate-400">
                        &copy; {new Date().getFullYear()} Beacon Leadership Institute. All rights reserved.
                    </div>
                </div>
            </footer>
        </div>
    );
}
