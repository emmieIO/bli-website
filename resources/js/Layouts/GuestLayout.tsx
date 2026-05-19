import React, { PropsWithChildren, useEffect, useRef, useState } from 'react';
import { Link, usePage } from '@inertiajs/react';
import axios from 'axios';
import { ToastContainer, useToastNotifications } from '@/Components/Toast';
import { PageProps } from '@/types';

export default function GuestLayout({ children }: PropsWithChildren) {
    const { auth } = usePage<PageProps>().props;
    const [mobileMenuOpen, setMobileMenuOpen] = useState(false);
    const [joinUsDropdownOpen, setJoinUsDropdownOpen] = useState(false);
    const [cartCount, setCartCount] = useState(0);
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

    useEffect(() => {
        async function fetchCartCount() {
            if (!auth.user) {
                setCartCount(0);
                return;
            }

            try {
                const response = await axios.get(route('cart.count'));
                setCartCount(response.data.count || 0);
            } catch {
                setCartCount(0);
            }
        }

        fetchCartCount();
    }, [auth.user]);

    const navLinkClass = 'text-sm font-medium text-slate-600 transition-colors hover:text-slate-950';
    const mobileNavLinkClass = 'block rounded-xl px-3 py-2 text-sm font-medium text-slate-700 transition hover:bg-slate-100 hover:text-slate-950';

    return (
        <div className="app-shell flex min-h-screen flex-col overflow-x-hidden">
            <ToastContainer />

            <nav className="sticky top-0 z-50 border-b border-slate-200/80 bg-white/90 backdrop-blur-xl">
                <div className="section-shell flex items-center justify-between py-4">
                    <Link href={route('homepage')} className="flex items-center gap-3">
                        <div className="app-panel p-1">
                            <img src="/images/logo.jpg" alt="BLI Logo" className="h-11 w-11 rounded-md object-cover" />
                        </div>
                        <p className="text-sm font-bold text-primary">Beacon Leadership Institute</p>
                    </Link>

                    <div className="hidden items-center gap-8 lg:flex">
                        <Link href={route('homepage')} className={navLinkClass}>Home</Link>
                        <Link href={route('events.index')} className={navLinkClass}>Events</Link>
                        <Link href={route('courses.index')} className={navLinkClass}>Courses</Link>
                        <Link href={route('blog.index')} className={navLinkClass}>Insights</Link>
                        <li className="relative list-none" ref={joinUsDropdownRef}>
                            <button
                                onClick={() => setJoinUsDropdownOpen((value) => !value)}
                                className="flex items-center gap-2 text-sm font-medium text-slate-600 transition-colors hover:text-slate-950"
                            >
                                Join Us
                                <i className={`fas fa-chevron-down text-xs transition-transform ${joinUsDropdownOpen ? 'rotate-180' : ''}`}></i>
                            </button>

                            {joinUsDropdownOpen && (
                                <div className="absolute left-0 mt-3 w-64 overflow-hidden rounded-2xl border border-slate-200 bg-white p-2 shadow-[0_18px_50px_rgba(15,23,42,0.12)]">
                                    <Link href={route('instructors.become-an-instructor')} className="block rounded-xl px-4 py-3 transition hover:bg-slate-50">
                                        <p className="text-sm font-semibold text-slate-900">Become an Instructor</p>
                                        <p className="mt-1 text-xs text-slate-500">Teach through structured programs.</p>
                                    </Link>
                                    <Link href={route('become-a-speaker')} className="block rounded-xl px-4 py-3 transition hover:bg-slate-50">
                                        <p className="text-sm font-semibold text-slate-900">Become a Speaker</p>
                                        <p className="mt-1 text-xs text-slate-500">Contribute to event experiences.</p>
                                    </Link>
                                </div>
                            )}
                        </li>
                    </div>

                    <div className="hidden items-center gap-3 lg:flex">
                        <Link
                            href={route('cart.index')}
                            className="relative inline-flex items-center gap-2 rounded-2xl border border-slate-200 bg-white px-4 py-2.5 text-sm font-semibold text-slate-700 transition hover:border-slate-300 hover:bg-slate-50"
                        >
                            <i className="fas fa-shopping-cart text-sm"></i>
                            <span>Cart</span>
                            {cartCount > 0 && (
                                <span className="absolute -right-2 -top-2 flex h-5 min-w-5 items-center justify-center rounded-full bg-accent-500 px-1 text-[11px] font-bold text-white">
                                    {cartCount > 9 ? '9+' : cartCount}
                                </span>
                            )}
                        </Link>

                        {auth.user ? (
                            <>
                                <Link
                                    href={route('user_dashboard')}
                                    className="inline-flex items-center rounded-2xl bg-slate-950 px-4 py-2.5 text-sm font-semibold text-white transition hover:bg-slate-800"
                                >
                                    Workspace
                                </Link>
                                <Link
                                    href={route('logout')}
                                    method="post"
                                    as="button"
                                    className="inline-flex items-center rounded-2xl border border-slate-200 bg-white px-4 py-2.5 text-sm font-semibold text-slate-700 transition hover:border-slate-300 hover:bg-slate-50"
                                >
                                    Sign out
                                </Link>
                            </>
                        ) : (
                            <>
                                <Link
                                    href={route('login')}
                                    className="inline-flex items-center rounded-2xl border border-slate-200 bg-white px-4 py-2.5 text-sm font-semibold text-slate-700 transition hover:border-slate-300 hover:bg-slate-50"
                                >
                                    Sign in
                                </Link>
                                <Link
                                    href={route('register')}
                                    className="inline-flex items-center rounded-2xl bg-slate-950 px-4 py-2.5 text-sm font-semibold text-white transition hover:bg-slate-800"
                                >
                                    Get Started
                                </Link>
                            </>
                        )}
                    </div>

                    <button
                        onClick={() => setMobileMenuOpen((value) => !value)}
                        className="inline-flex h-10 w-10 items-center justify-center rounded-xl border border-slate-200 bg-white text-slate-900 lg:hidden"
                    >
                        <i className={`fas ${mobileMenuOpen ? 'fa-times' : 'fa-bars'}`}></i>
                    </button>
                </div>

                {mobileMenuOpen && (
                    <div className="border-t border-slate-200 bg-white lg:hidden">
                        <div className="section-shell space-y-2 py-4">
                            <Link href={route('homepage')} className={mobileNavLinkClass}>Home</Link>
                            <Link href={route('events.index')} className={mobileNavLinkClass}>Events</Link>
                            <Link href={route('courses.index')} className={mobileNavLinkClass}>Courses</Link>
                            <Link href={route('blog.index')} className={mobileNavLinkClass}>Insights</Link>
                            <Link href={route('instructors.become-an-instructor')} className={mobileNavLinkClass}>Become an Instructor</Link>
                            <Link href={route('become-a-speaker')} className={mobileNavLinkClass}>Become a Speaker</Link>
                            <Link href={route('cart.index')} className={mobileNavLinkClass}>Cart</Link>
                            {auth.user ? (
                                <>
                                    <Link href={route('user_dashboard')} className={mobileNavLinkClass}>Workspace</Link>
                                    <Link href={route('logout')} method="post" as="button" className={`${mobileNavLinkClass} w-full text-left`}>
                                        Sign out
                                    </Link>
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

            <footer className="border-t border-slate-200 bg-slate-50">
                <div className="section-shell flex flex-col gap-3 py-6 text-sm text-slate-600 md:flex-row md:items-center md:justify-between">
                    <p className="font-medium text-slate-900">Beacon Leadership Institute</p>
                    <div className="flex items-center gap-5">
                        <Link href={route('privacy-policy')} className="transition hover:text-slate-950">Privacy</Link>
                        <Link href={route('terms-of-service')} className="transition hover:text-slate-950">Terms</Link>
                        <Link href={route('contact-us')} className="transition hover:text-slate-950">Contact</Link>
                    </div>
                </div>
            </footer>
        </div>
    );
}
