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

            <div className="overflow-x-hidden min-h-screen">
                {/* Mobile/Tablet Header */}
                <nav className="lg:hidden bg-white shadow-lg sticky top-0 z-50 border-b border-gray-100">
                    <div className="max-w-7xl mx-auto flex justify-between items-center py-3 px-4 md:px-8">
                        {/* Logo */}
                        <Link href={route('homepage')} className="flex items-center space-x-3 group">
                            <div className="relative">
                                <img
                                    src="/images/logo.jpg"
                                    alt="BLI Logo"
                                    className="w-12 h-12 rounded-xl object-cover shadow-md"
                                />
                                <div
                                    className="absolute -top-1 -right-1 w-3 h-3 rounded-full"
                                    style={{ backgroundColor: '#00a651' }}
                                />
                            </div>
                            <div>
                                <div className="flex items-center gap-2">
                                    <span
                                        className="text-lg font-bold tracking-tight font-montserrat leading-tight"
                                        style={{ color: '#002147' }}
                                    >
                                        Beacon Leadership
                                    </span>
                                </div>
                                <span
                                    className="text-xs font-semibold tracking-wide font-lato block"
                                    style={{ color: '#00a651' }}
                                >
                                    Institute
                                </span>
                            </div>
                        </Link>

                        <Link
                            href={route('homepage')}
                            className="text-sm font-semibold hover:underline"
                            style={{ color: '#002147' }}
                        >
                            <i className="fas fa-arrow-left mr-2" style={{ color: '#00a651' }}></i>
                            Back to Home
                        </Link>
                    </div>
                </nav>

                {/* Main Content - 2 Column Grid for Large Screens */}
                <main className="min-h-screen lg:grid lg:grid-cols-2">
                    {/* Left Column - Branding & Visual (Hidden on Mobile/Tablet) */}
                    <div
                        className="hidden lg:flex flex-col justify-center items-center p-12 relative overflow-hidden"
                        style={{ backgroundColor: '#002147' }}
                    >
                        {/* Background Pattern */}
                        <div className="absolute inset-0 opacity-5">
                            <div className="absolute inset-0" style={{
                                backgroundImage: 'radial-gradient(circle at 2px 2px, white 1px, transparent 0)',
                                backgroundSize: '40px 40px'
                            }}></div>
                        </div>

                        {/* Content */}
                        <div className="relative z-10 max-w-lg text-center">
                            {/* Logo */}
                            <Link href={route('homepage')} className="inline-block mb-8 group">
                                <div className="flex flex-col items-center">
                                    <div className="relative mb-4">
                                        <img
                                            src="/images/logo.jpg"
                                            alt="BLI Logo"
                                            className="w-32 h-32 rounded-3xl object-cover shadow-2xl group-hover:shadow-green-500/50 transition-all duration-500 transform group-hover:scale-105"
                                        />
                                        <div
                                            className="absolute -top-2 -right-2 w-6 h-6 rounded-full animate-pulse"
                                            style={{ backgroundColor: '#00a651' }}
                                        ></div>
                                    </div>
                                    <div className="text-white">
                                        <div className="flex items-center justify-center gap-3 mb-2">
                                            <h1 className="text-4xl font-bold tracking-tight font-montserrat">
                                                Beacon Leadership
                                            </h1>
                                            <i
                                                className="fas fa-lightbulb text-2xl group-hover:text-yellow-400 transition-colors duration-300"
                                                style={{ color: '#00a651' }}
                                            ></i>
                                        </div>
                                        <p className="text-xl font-semibold tracking-wider font-lato" style={{ color: '#00a651' }}>
                                            Institute
                                        </p>
                                    </div>
                                </div>
                            </Link>

                            {/* Welcome Message */}
                            <div className="space-y-6 text-white">
                                <h2 className="text-3xl font-bold font-montserrat leading-tight">
                                    Empowering Tomorrow's Leaders
                                </h2>
                                <p className="text-lg text-gray-300 font-lato leading-relaxed">
                                    Join a community dedicated to developing exceptional leadership skills through
                                    world-class courses, events, and mentorship programs.
                                </p>

                                {/* Features */}
                                <div className="grid grid-cols-1 gap-4 mt-8">
                                    <div className="flex items-center gap-4 p-4 bg-white/5 rounded-xl backdrop-blur-sm border border-white/10">
                                        <div className="flex-shrink-0 w-12 h-12 rounded-lg flex items-center justify-center" style={{ backgroundColor: '#00a651' }}>
                                            <i className="fas fa-graduation-cap text-white text-xl"></i>
                                        </div>
                                        <div className="text-left">
                                            <h3 className="font-semibold font-montserrat">Expert-Led Courses</h3>
                                            <p className="text-sm text-gray-300 font-lato">Learn from industry leaders</p>
                                        </div>
                                    </div>
                                    <div className="flex items-center gap-4 p-4 bg-white/5 rounded-xl backdrop-blur-sm border border-white/10">
                                        <div className="flex-shrink-0 w-12 h-12 rounded-lg flex items-center justify-center" style={{ backgroundColor: '#00a651' }}>
                                            <i className="fas fa-users text-white text-xl"></i>
                                        </div>
                                        <div className="text-left">
                                            <h3 className="font-semibold font-montserrat">Global Community</h3>
                                            <p className="text-sm text-gray-300 font-lato">Connect with like-minded leaders</p>
                                        </div>
                                    </div>
                                    <div className="flex items-center gap-4 p-4 bg-white/5 rounded-xl backdrop-blur-sm border border-white/10">
                                        <div className="flex-shrink-0 w-12 h-12 rounded-lg flex items-center justify-center" style={{ backgroundColor: '#00a651' }}>
                                            <i className="fas fa-certificate text-white text-xl"></i>
                                        </div>
                                        <div className="text-left">
                                            <h3 className="font-semibold font-montserrat">Certified Programs</h3>
                                            <p className="text-sm text-gray-300 font-lato">Earn recognized credentials</p>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            {/* Quick Links */}
                            <div className="mt-12 flex items-center justify-center gap-6 text-sm">
                                <Link
                                    href={route('homepage')}
                                    className="text-gray-300 hover:text-white transition-colors font-lato flex items-center gap-2"
                                >
                                    <i className="fas fa-home"></i>
                                    <span>Home</span>
                                </Link>
                                <span className="text-gray-600">•</span>
                                <Link
                                    href={route('courses.index')}
                                    className="text-gray-300 hover:text-white transition-colors font-lato flex items-center gap-2"
                                >
                                    <i className="fas fa-book-open"></i>
                                    <span>Courses</span>
                                </Link>
                                <span className="text-gray-600">•</span>
                                <Link
                                    href={route('events.index')}
                                    className="text-gray-300 hover:text-white transition-colors font-lato flex items-center gap-2"
                                >
                                    <i className="fas fa-calendar-alt"></i>
                                    <span>Events</span>
                                </Link>
                            </div>
                        </div>
                    </div>

                    {/* Right Column - Auth Form */}
                    <div className="flex items-center justify-center px-4 py-12 lg:px-12 bg-gray-50">
                        <div className="w-full max-w-lg">
                            {/* Auth Card */}
                            <div className="bg-white rounded-2xl shadow-2xl overflow-hidden border border-gray-100">
                                {/* Header */}
                                {title && (
                                    <div className="px-8 lg:px-10 pt-8 pb-6 text-center border-b border-gray-100">
                                        <div className="inline-flex items-center justify-center w-16 h-16 rounded-2xl mb-4" style={{ backgroundColor: '#f0f9ff' }}>
                                            <i className="fas fa-user-circle text-3xl" style={{ color: '#002147' }}></i>
                                        </div>
                                        <h2 className="text-3xl font-bold font-montserrat mb-2" style={{ color: '#002147' }}>
                                            {title}
                                        </h2>
                                        {description && (
                                            <p className="text-gray-600 font-lato text-base">{description}</p>
                                        )}
                                    </div>
                                )}

                                {/* Content */}
                                <div className="px-8 lg:px-10 py-8">
                                    {children}
                                </div>
                            </div>

                            {/* Footer Text */}
                            <p className="text-center text-gray-600 text-sm mt-6 font-lato">
                                © {new Date().getFullYear()} Beacon Leadership Institute. All rights reserved.
                            </p>
                        </div>
                    </div>
                </main>
            </div>
        </>
    );
}
