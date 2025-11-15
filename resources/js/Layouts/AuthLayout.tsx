import { PropsWithChildren } from 'react';
import { Head, Link } from '@inertiajs/react';

interface AuthLayoutProps extends PropsWithChildren {
    title?: string;
    description?: string;
}

export default function AuthLayout({ title, description, children }: AuthLayoutProps) {
    return (
        <>
            <Head title={title || 'Authentication'} />

            <div className="overflow-x-hidden min-h-screen flex flex-col">
                {/* Navbar */}
                <nav className="bg-white shadow-lg sticky top-0 z-50 border-b border-gray-100">
                    <div className="max-w-7xl mx-auto flex justify-between items-center py-3 px-4 md:px-8">
                        {/* Logo */}
                        <Link href={route('homepage')} className="flex items-center space-x-3 group">
                            <div className="relative">
                                <img
                                    src="/images/logo.jpg"
                                    alt="BLI Logo"
                                    className="w-14 h-14 rounded-xl object-cover shadow-md group-hover:shadow-lg transition-all duration-300 transform group-hover:scale-105"
                                />
                                <div
                                    className="absolute -top-1 -right-1 w-3 h-3 rounded-full opacity-80 group-hover:opacity-100 transition-opacity duration-300"
                                    style={{ backgroundColor: '#00a651' }}
                                />
                            </div>

                            <div className="hidden sm:block">
                                <div className="flex items-center gap-2">
                                    <span
                                        className="text-xl font-bold tracking-tight font-montserrat leading-tight"
                                        style={{ color: '#002147' }}
                                    >
                                        Beacon Leadership
                                    </span>
                                    <i
                                        className="fas fa-lightbulb text-sm group-hover:text-yellow-500 transition-colors duration-300"
                                        style={{ color: '#00a651' }}
                                    />
                                </div>
                                <span
                                    className="text-sm font-semibold tracking-wide font-lato block"
                                    style={{ color: '#00a651' }}
                                >
                                    Institute
                                </span>
                            </div>
                        </Link>

                        {/* Desktop Navigation */}
                        <ul className="hidden lg:flex gap-x-8 items-center font-semibold text-sm">
                            <li>
                                <Link
                                    href={route('homepage')}
                                    className="flex items-center gap-2 relative py-2 px-1 transition-all duration-300 group"
                                    style={{ color: '#002147' }}
                                >
                                    <i
                                        className="fas fa-home text-sm group-hover:scale-110 transition-transform duration-300"
                                        style={{ color: '#00a651' }}
                                    />
                                    <span>Home</span>
                                    <span
                                        className="absolute bottom-0 left-0 w-0 h-0.5 transition-all duration-300 group-hover:w-full"
                                        style={{ backgroundColor: '#00a651' }}
                                    />
                                </Link>
                            </li>
                            <li>
                                <Link
                                    href={route('events.index')}
                                    className="flex items-center gap-2 relative py-2 px-1 transition-all duration-300 group"
                                    style={{ color: '#002147' }}
                                >
                                    <i
                                        className="fas fa-calendar-alt text-sm group-hover:scale-110 transition-transform duration-300"
                                        style={{ color: '#00a651' }}
                                    />
                                    <span>Events</span>
                                    <span
                                        className="absolute bottom-0 left-0 w-0 h-0.5 transition-all duration-300 group-hover:w-full"
                                        style={{ backgroundColor: '#00a651' }}
                                    />
                                </Link>
                            </li>
                            <li>
                                <Link
                                    href={route('courses.index')}
                                    className="flex items-center gap-2 relative py-2 px-1 transition-all duration-300 group"
                                    style={{ color: '#002147' }}
                                >
                                    <i
                                        className="fas fa-book-open text-sm group-hover:scale-110 transition-transform duration-300"
                                        style={{ color: '#00a651' }}
                                    />
                                    <span>Courses</span>
                                    <span
                                        className="absolute bottom-0 left-0 w-0 h-0.5 transition-all duration-300 group-hover:w-full"
                                        style={{ backgroundColor: '#00a651' }}
                                    />
                                </Link>
                            </li>
                        </ul>

                        {/* Auth Buttons */}
                        <div className="flex items-center gap-4">
                            <Link
                                href={route('login')}
                                className="hidden sm:inline-block px-6 py-2 rounded-full border-2 font-semibold transition-all duration-300 hover:shadow-lg"
                                style={{
                                    borderColor: '#002147',
                                    color: '#002147'
                                }}
                            >
                                Login
                            </Link>
                            <Link
                                href={route('register')}
                                className="px-6 py-2 rounded-full font-semibold text-white transition-all duration-300 hover:shadow-lg hover:scale-105"
                                style={{ backgroundColor: '#00a651' }}
                            >
                                Sign Up
                            </Link>
                        </div>
                    </div>
                </nav>

                {/* Main Content */}
                <main className="flex-1 flex items-center justify-center px-4 py-12" style={{ backgroundColor: '#f8f9fa' }}>
                    <div className="w-full max-w-md">
                        {/* Auth Card */}
                        <div className="bg-white rounded-2xl shadow-2xl overflow-hidden">
                            {/* Header */}
                            {title && (
                                <div className="px-8 pt-8 pb-6 text-center border-b border-gray-100">
                                    <h2 className="text-3xl font-bold font-montserrat" style={{ color: '#002147' }}>
                                        {title}
                                    </h2>
                                    {description && (
                                        <p className="text-gray-600 mt-2 font-lato">{description}</p>
                                    )}
                                </div>
                            )}

                            {/* Content */}
                            <div className="px-8 py-8">
                                {children}
                            </div>
                        </div>

                        {/* Footer Text */}
                        <p className="text-center text-gray-600 text-sm mt-6 font-lato">
                            © {new Date().getFullYear()} Beacon Leadership Institute. All rights reserved.
                        </p>
                    </div>
                </main>
            </div>
        </>
    );
}
