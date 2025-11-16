import { Head, Link } from '@inertiajs/react';
import GuestLayout from '@/Layouts/GuestLayout';

interface Category {
    id: number;
    name: string;
    image: string;
    courses_count?: number;
    courses?: any[];
}

interface Event {
    id: number;
    slug: string;
    title: string;
    theme?: string;
    program_cover: string;
    start_date: string;
    end_date: string;
    mode?: 'online' | 'offline' | 'hybrid';
    physical_address?: string;
    location?: string;
    entry_fee: number;
    slots_remaining?: number;
    is_registered?: boolean;
}

interface IndexProps {
    events: Event[];
    categories: Category[];
}

export default function Index({ events, categories }: IndexProps) {
    const formatDate = (dateString: string) => {
        const date = new Date(dateString);
        return date.toLocaleDateString('en-US', { year: 'numeric', month: 'long', day: 'numeric' });
    };

    const isEventStarted = (startDate: string) => {
        return new Date(startDate) <= new Date();
    };

    return (
        <GuestLayout>
            <Head title="Transforming Leadership for Kingdom Impact" />

            {/* Hero Section */}
            <section className="py-16 md:py-20 lg:py-24 bg-white">
                <div className="container mx-auto px-6">
                    <div className="grid grid-cols-1 lg:grid-cols-2 gap-12 items-center">
                        {/* Text Content - Left Side */}
                        <div className="order-2 lg:order-1">
                            {/* Badge */}
                            <div className="inline-flex items-center gap-2 border rounded-full px-6 py-3 mb-8 bg-accent/10 border-accent">
                                <div className="w-2 h-2 rounded-full animate-pulse bg-accent"></div>
                                <span className="font-medium font-montserrat text-sm tracking-wide text-primary">
                                    Empowering Leaders for Kingdom Impact
                                </span>
                            </div>

                            {/* Main Heading */}
                            <h1 className="font-bold font-montserrat text-3xl md:text-4xl lg:text-5xl mb-6 leading-tight text-primary">
                                Transform Your{' '}
                                <span className="text-accent">Leadership Journey</span>
                            </h1>

                            <p className="text-lg md:text-xl text-gray-600 mb-8 leading-relaxed font-lato">
                                Developing visionary leaders to drive positive change in organizations and communities through
                                kingdom-based principles.
                            </p>

                            {/* CTA Buttons */}
                            <div className="flex flex-col sm:flex-row gap-4">
                                <Link
                                    href={route('register')}
                                    className="group font-semibold py-3 px-6 rounded-lg shadow-lg hover:shadow-xl transition-all duration-300 transform hover:scale-105 font-montserrat flex items-center justify-center gap-2 text-sm text-white bg-accent hover:bg-accent-700"
                                >
                                    <span>Start Your Journey</span>
                                    <i className="fas fa-arrow-right group-hover:translate-x-1 transition-transform text-sm"></i>
                                </Link>
                                <Link
                                    href={route('events.index')}
                                    className="group border-2 font-semibold py-3 px-6 rounded-lg transition-all duration-300 font-montserrat flex items-center justify-center gap-2 text-sm hover:bg-gray-50 text-primary border-primary"
                                >
                                    <span>Explore Events</span>
                                    <i className="fas fa-calendar group-hover:scale-110 transition-transform text-sm"></i>
                                </Link>
                            </div>
                        </div>

                        {/* Image - Right Side */}
                        <div className="order-1 lg:order-2">
                            <div className="relative">
                                <img
                                    src="/images/learning-platform/banner.png"
                                    alt="BLI Leadership Training"
                                    className="w-full h-auto rounded-2xl shadow-2xl"
                                />

                                {/* Decorative elements */}
                                <div className="absolute -top-4 -left-4 w-20 h-20 rounded-full opacity-20 bg-accent"></div>
                                <div className="absolute -bottom-4 -right-4 w-16 h-16 rounded-full opacity-20 bg-secondary"></div>
                            </div>
                        </div>
                    </div>
                </div>
            </section>

            {/* Trust Indicators */}
            <section className="py-12 bg-white border-b border-gray-100">
                <div className="container mx-auto px-4">
                    <div className="flex flex-col md:flex-row items-center justify-center gap-8 md:gap-16 text-center">
                        <div className="flex items-center gap-3">
                            <div className="text-2xl font-bold font-montserrat text-primary">1,300+</div>
                            <div className="text-gray-600 font-lato">Organizations Trust BLI</div>
                        </div>
                        <div className="flex items-center gap-3">
                            <div className="text-2xl font-bold font-montserrat text-secondary">50+</div>
                            <div className="text-gray-600 font-lato">Countries Worldwide</div>
                        </div>
                        <div className="flex items-center gap-3">
                            <div className="text-2xl font-bold font-montserrat text-accent">15+</div>
                            <div className="text-gray-600 font-lato">Years of Excellence</div>
                        </div>
                    </div>
                </div>
            </section>

            {/* Course Categories Section */}
            <section className="py-20 bg-gradient-to-b from-white to-gray-50">
                <div className="container mx-auto px-4">
                    {/* Section Header */}
                    <div className="text-center mb-16">
                        <div className="inline-flex items-center gap-2 rounded-full px-6 py-3 mb-6 bg-primary/10">
                            <i className="fas fa-graduation-cap text-primary"></i>
                            <span className="font-medium font-montserrat text-sm text-primary">Leadership Development</span>
                        </div>
                        <h2 className="font-bold text-4xl lg:text-5xl text-center mb-6 font-montserrat text-gray-900">
                            Explore Course Categories
                        </h2>
                        <p className="text-xl text-gray-600 text-center mb-8 max-w-3xl mx-auto leading-relaxed font-lato">
                            Discover specialized leadership tracks designed to equip you for impact in every sphere of society.
                        </p>
                    </div>

                    {/* Categories Grid */}
                    {categories.length > 0 ? (
                        <div className="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-5 gap-8 mb-16">
                            {categories.map((category, index) => (
                                <Link
                                    key={category.id}
                                    href={route('courses.index')}
                                    className="group bg-white rounded-2xl shadow-sm hover:shadow-xl p-8 text-center transition-all duration-500 hover:transform hover:-translate-y-4 border border-gray-100 hover:border-primary/20 relative overflow-hidden"
                                >
                                    {/* Background Gradient */}
                                    <div className="absolute inset-0 bg-gradient-to-br from-primary/5 to-secondary/5 opacity-0 group-hover:opacity-100 transition-opacity duration-500"></div>

                                    {/* Content */}
                                    <div className="relative z-10">
                                        {/* Icon Container */}
                                        <div className="mb-6 p-4 bg-gradient-to-br from-gray-50 to-gray-100 rounded-2xl inline-block group-hover:bg-gradient-to-br group-hover:from-primary/10 group-hover:to-secondary/10 transition-all duration-500 group-hover:scale-110">
                                            <img
                                                src={`/storage/${category.image}`}
                                                alt={`${category.name} icon`}
                                                className="mx-auto w-16 h-16 object-contain group-hover:scale-110 transition-transform duration-500"
                                            />
                                        </div>

                                        {/* Category Name */}
                                        <h5 className="font-bold text-xl mb-4 text-gray-800 group-hover:text-primary transition-colors duration-500 font-montserrat">
                                            {category.name.charAt(0).toUpperCase() + category.name.slice(1)}
                                        </h5>

                                        {/* Course Count */}
                                        <div className="inline-flex items-center gap-2 bg-gray-100 group-hover:bg-primary/10 rounded-full px-4 py-2 transition-colors duration-500">
                                            <span className="font-semibold text-gray-600 group-hover:text-primary text-sm font-montserrat">
                                                {category.courses?.length || category.courses_count || 0}
                                            </span>
                                            <span className="text-gray-500 group-hover:text-primary/80 text-sm font-lato">
                                                course{(category.courses?.length || category.courses_count || 0) !== 1 ? 's' : ''}
                                            </span>
                                        </div>

                                        {/* Hover Arrow */}
                                        <div className="mt-4 opacity-0 group-hover:opacity-100 transition-opacity duration-500">
                                            <i className="fas fa-arrow-right text-primary"></i>
                                        </div>
                                    </div>
                                </Link>
                            ))}
                        </div>
                    ) : (
                        <div className="col-span-full text-center py-16 mb-16">
                            <div className="max-w-md mx-auto">
                                <i className="fas fa-book-open text-6xl text-gray-300 mb-6"></i>
                                <h3 className="font-bold text-2xl text-gray-500 mb-4 font-montserrat">Categories Coming Soon</h3>
                                <p className="text-gray-400 font-lato leading-relaxed">
                                    We're preparing amazing course categories for your leadership journey.
                                </p>
                            </div>
                        </div>
                    )}

                    {/* CTA Section */}
                    <div className="text-center">
                        <Link
                            href={route('courses.index')}
                            className="group inline-flex items-center gap-4 text-white font-bold py-5 px-10 rounded-2xl shadow-xl hover:shadow-2xl transition-all duration-500 transform hover:-translate-y-2 text-lg font-montserrat bg-gradient-to-r from-primary to-secondary hover:from-primary-dark hover:to-secondary-dark"
                        >
                            <span>Explore All Categories</span>
                            <i className="fas fa-arrow-right group-hover:translate-x-2 transition-transform duration-300"></i>
                        </Link>
                    </div>
                </div>
            </section>

            {/* What Makes BLI Stand Out Section */}
            <section className="py-20 bg-gradient-to-b from-gray-50 to-white relative overflow-hidden">
                {/* Background Elements */}
                <div className="absolute inset-0 overflow-hidden">
                    <div className="absolute -top-24 -right-24 w-96 h-96 bg-primary/5 rounded-full"></div>
                    <div className="absolute -bottom-24 -left-24 w-96 h-96 bg-secondary/5 rounded-full"></div>
                </div>

                <div className="container mx-auto px-4 relative z-10">
                    {/* Section Header */}
                    <div className="text-center mb-20">
                        <div className="inline-flex items-center gap-2 rounded-full px-6 py-3 mb-6 bg-primary/10">
                            <i className="fas fa-crown text-primary"></i>
                            <span className="font-medium font-montserrat text-sm text-primary">Kingdom Excellence</span>
                        </div>
                        <h2 className="font-bold text-4xl lg:text-5xl text-center mb-6 font-montserrat text-gray-900">
                            What Makes BLI <span className="text-secondary">Stand Out</span>
                        </h2>
                        <p className="text-xl text-gray-600 text-center max-w-3xl mx-auto leading-relaxed font-lato">
                            Experience transformational leadership development that integrates spiritual depth with practical excellence.
                        </p>
                    </div>

                    {/* Features Grid */}
                    <div className="grid grid-cols-1 lg:grid-cols-2 xl:grid-cols-3 gap-8">
                        {/* Kingdom-Based Leadership */}
                        <div className="group bg-white rounded-2xl shadow-lg hover:shadow-2xl transition-all duration-500 p-8 border border-gray-100 hover:border-primary/20 relative overflow-hidden">
                            <div className="absolute inset-0 bg-gradient-to-br from-primary/5 to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-500"></div>
                            <div className="relative z-10">
                                <div className="mb-6 p-4 rounded-2xl inline-block group-hover:scale-110 transition-transform duration-500 bg-primary/10">
                                    <i className="fas fa-crown text-4xl group-hover:scale-110 transition-transform duration-300 text-primary"></i>
                                </div>
                                <h5 className="font-bold text-2xl mb-4 font-montserrat text-gray-900 group-hover:text-primary transition-colors duration-300">
                                    Kingdom-Based Leadership
                                </h5>
                                <p className="text-gray-600 font-lato leading-relaxed text-lg group-hover:text-gray-700 transition-colors duration-300">
                                    Spiritually grounded, marketplace relevant. Thrive like Daniel, Joseph, and Esther—integrating
                                    faith with bold, strategic leadership in business, politics, education, and ministry.
                                </p>
                            </div>
                        </div>

                        {/* Transformational Mentorship */}
                        <div className="group bg-white rounded-2xl shadow-lg hover:shadow-2xl transition-all duration-500 p-8 border border-gray-100 hover:border-secondary/20 relative overflow-hidden">
                            <div className="absolute inset-0 bg-gradient-to-br from-secondary/5 to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-500"></div>
                            <div className="relative z-10">
                                <div className="mb-6 p-4 rounded-2xl bg-secondary/10 inline-block group-hover:scale-110 transition-transform duration-500">
                                    <i className="fas fa-hands-helping text-4xl text-secondary group-hover:scale-110 transition-transform duration-300"></i>
                                </div>
                                <h5 className="font-bold text-2xl mb-4 font-montserrat text-gray-900 group-hover:text-secondary transition-colors duration-300">
                                    Transformational Mentorship
                                </h5>
                                <p className="text-gray-600 font-lato leading-relaxed text-lg group-hover:text-gray-700 transition-colors duration-300">
                                    Students matched with seasoned mentors for growth checkpoints, leadership challenges, and
                                    personal formation. Virtual coaching pods and check-ins ensure students flourish.
                                </p>
                            </div>
                        </div>

                        {/* Problem-Solving Curriculum */}
                        <div className="group bg-white rounded-2xl shadow-lg hover:shadow-2xl transition-all duration-500 p-8 border border-gray-100 hover:border-accent/20 relative overflow-hidden lg:col-span-2 xl:col-span-1">
                            <div className="absolute inset-0 bg-gradient-to-br from-accent/5 to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-500"></div>
                            <div className="relative z-10">
                                <div className="mb-6 p-4 rounded-2xl bg-accent/10 inline-block group-hover:scale-110 transition-transform duration-500">
                                    <i className="fas fa-puzzle-piece text-4xl text-accent group-hover:scale-110 transition-transform duration-300"></i>
                                </div>
                                <h5 className="font-bold text-2xl mb-6 font-montserrat text-gray-900 group-hover:text-accent transition-colors duration-300">
                                    Problem-Solving Curriculum
                                </h5>

                                {/* Skills List */}
                                <div className="space-y-3 mb-6">
                                    {[
                                        'Leading under pressure',
                                        'Navigating toxic environments',
                                        'Strategic planning with prophetic insight',
                                        'Rebuilding broken systems',
                                        'Sustaining influence with integrity'
                                    ].map((skill, index) => (
                                        <div key={index} className="flex items-center gap-3 group/item hover:bg-accent/5 rounded-lg p-2 -m-2 transition-colors duration-300">
                                            <div className="w-2 h-2 bg-accent rounded-full group-hover/item:scale-125 transition-transform duration-300"></div>
                                            <span className="text-gray-600 font-lato group-hover/item:text-gray-800 transition-colors duration-300">
                                                {skill}
                                            </span>
                                        </div>
                                    ))}
                                </div>
                            </div>
                        </div>

                        {/* Prophetic Leadership Integration */}
                        <div className="group bg-white rounded-2xl shadow-lg hover:shadow-2xl transition-all duration-500 p-8 border border-gray-100 hover:border-primary/20 relative overflow-hidden">
                            <div className="absolute inset-0 bg-gradient-to-br from-primary/5 to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-500"></div>
                            <div className="relative z-10">
                                <div className="mb-6 p-4 rounded-2xl bg-primary/10 inline-block group-hover:scale-110 transition-transform duration-500">
                                    <i className="fas fa-eye text-4xl text-primary group-hover:scale-110 transition-transform duration-300"></i>
                                </div>
                                <h5 className="font-bold text-2xl mb-4 font-montserrat text-gray-900 group-hover:text-primary transition-colors duration-300">
                                    Prophetic Leadership
                                </h5>
                                <p className="text-gray-600 font-lato leading-relaxed text-lg group-hover:text-gray-700 transition-colors duration-300">
                                    Integrates prophetic insight with leadership training—sharpening discernment, interpreting
                                    divine seasons, and leading with Spirit-led accuracy.
                                </p>
                            </div>
                        </div>

                        {/* Global Vision, Local Expression */}
                        <div className="group bg-white rounded-2xl shadow-lg hover:shadow-2xl transition-all duration-500 p-8 border border-gray-100 hover:border-secondary/20 relative overflow-hidden">
                            <div className="absolute inset-0 bg-gradient-to-br from-secondary/5 to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-500"></div>
                            <div className="relative z-10">
                                <div className="mb-6 p-4 rounded-2xl bg-secondary/10 inline-block group-hover:scale-110 transition-transform duration-500">
                                    <i className="fas fa-globe-americas text-4xl text-secondary group-hover:scale-110 transition-transform duration-300"></i>
                                </div>
                                <h5 className="font-bold text-2xl mb-4 font-montserrat text-gray-900 group-hover:text-secondary transition-colors duration-300">
                                    Global Vision, Local Expression
                                </h5>
                                <p className="text-gray-600 font-lato leading-relaxed text-lg group-hover:text-gray-700 transition-colors duration-300">
                                    Hybrid learning (virtual + in-person), summits, bootcamps, and global collaborations amplify
                                    graduates' influence worldwide.
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            </section>

            {/* Upcoming Events Section */}
            <section className="my-20">
                <div className="container mx-auto px-4">
                    <div className="flex flex-wrap items-center justify-between mb-12">
                        <div className="mb-5 md:mb-0">
                            <h2 className="font-bold text-4xl font-montserrat text-primary">Featured Events</h2>
                            <p className="text-gray-600 mt-2 font-lato">
                                Join our transformative events and leadership gatherings
                            </p>
                        </div>
                        <Link
                            href={route('events.index')}
                            className="group font-semibold text-secondary hover:text-primary transition-all duration-300 font-montserrat flex items-center gap-2"
                        >
                            View all Events
                            <i className="fas fa-arrow-right group-hover:translate-x-1 transition-transform duration-300"></i>
                        </Link>
                    </div>

                    {events.length > 0 ? (
                        <div className="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
                            {events.map((event) => (
                                <div
                                    key={event.id}
                                    className="bg-white rounded-xl shadow-sm overflow-hidden transition-all duration-300 hover:shadow-md border border-gray-200 hover:border-primary/20 group"
                                >
                                    <div className="relative">
                                        <img
                                            src={`/storage/${event.program_cover}`}
                                            alt={event.title}
                                            className="w-full h-40 object-cover group-hover:scale-105 transition-transform duration-500"
                                        />
                                    </div>

                                    <div className="p-4">
                                        <h3 className="text-base font-bold text-primary mb-2 line-clamp-2 font-montserrat group-hover:text-secondary transition-colors">
                                            <Link href={route('events.show', event.slug)}>{event.title}</Link>
                                        </h3>

                                        {event.theme && (
                                            <p className="font-bold text-accent mb-3 line-clamp-2 text-xs leading-relaxed font-montserrat">
                                                {event.theme}
                                            </p>
                                        )}

                                        <div className="space-y-2 mb-3">
                                            <div className="flex items-center gap-2 text-gray-600 text-xs font-lato">
                                                <span className="font-bold text-secondary text-xs">Start Date:</span>
                                                <span>{formatDate(event.start_date)}</span>
                                            </div>
                                            <div className="flex items-center gap-2 text-gray-600 text-xs font-lato">
                                                <span className="font-bold text-secondary text-xs">End Date:</span>
                                                <span>{formatDate(event.end_date)}</span>
                                            </div>
                                            <div className="flex items-center gap-2 text-gray-600 text-xs font-lato">
                                                <span className="text-secondary text-xs font-bold">Mode:</span>
                                                <span className="capitalize">{event.mode || 'TBA'}</span>
                                            </div>
                                            {event.slots_remaining !== undefined && (
                                                <div className="flex items-center gap-2 text-gray-600 text-xs font-lato">
                                                    <span className="text-secondary text-xs font-bold">Slots Remaining:</span>
                                                    <span className="capitalize">{event.slots_remaining}</span>
                                                </div>
                                            )}

                                            {event.mode === 'hybrid' && (
                                                <>
                                                    {event.is_registered && event.location && isEventStarted(event.start_date) && (
                                                        <div className="flex items-center gap-2 text-gray-600 text-xs font-lato">
                                                            <i className="fas fa-link text-secondary text-xs"></i>
                                                            <span className="capitalize">
                                                                <a href={event.location} target="_blank" rel="noopener noreferrer" className="hover:underline">
                                                                    {event.location}
                                                                </a>
                                                            </span>
                                                        </div>
                                                    )}
                                                    {event.physical_address && (
                                                        <div className="flex items-center gap-2 text-gray-600 text-xs font-lato">
                                                            <i className="fas fa-location-arrow text-secondary text-xs"></i>
                                                            <span className="capitalize">{event.physical_address}</span>
                                                        </div>
                                                    )}
                                                </>
                                            )}
                                            {event.mode === 'offline' && event.physical_address && (
                                                <div className="flex items-center gap-2 text-gray-600 text-xs font-lato">
                                                    <i className="fas fa-location-arrow text-secondary text-xs"></i>
                                                    <span className="capitalize">{event.physical_address}</span>
                                                </div>
                                            )}
                                            {event.mode === 'online' && event.is_registered && event.location && isEventStarted(event.start_date) && (
                                                <div className="flex items-center gap-2 text-gray-600 text-xs font-lato">
                                                    <i className="fas fa-link text-secondary text-xs"></i>
                                                    <span className="capitalize">
                                                        <a href={event.location} target="_blank" rel="noopener noreferrer" className="hover:underline">
                                                            {event.location}
                                                        </a>
                                                    </span>
                                                </div>
                                            )}
                                        </div>

                                        <div className="flex items-center justify-between pt-3 border-t border-gray-100">
                                            {event.entry_fee > 0 ? (
                                                <span className="text-base font-bold text-accent font-montserrat">
                                                    ₦{event.entry_fee.toLocaleString('en-NG', { minimumFractionDigits: 2 })}
                                                </span>
                                            ) : (
                                                <span className="text-base font-bold text-secondary font-montserrat">Free</span>
                                            )}
                                            <Link
                                                href={route('events.show', event.slug)}
                                                className="bg-primary hover:bg-secondary text-white px-3 py-1.5 rounded text-xs font-semibold transition-colors font-montserrat"
                                            >
                                                View Details
                                            </Link>
                                        </div>
                                    </div>
                                </div>
                            ))}
                        </div>
                    ) : (
                        <div className="col-span-full text-center py-16">
                            <div className="max-w-md mx-auto">
                                <i className="fas fa-calendar-day text-6xl text-gray-300 mb-4"></i>
                                <h3 className="font-bold text-xl text-gray-500 mb-2 font-montserrat">No Upcoming Events</h3>
                                <p className="text-gray-400 font-lato">Check back later for new events and leadership gatherings.</p>
                            </div>
                        </div>
                    )}
                </div>
            </section>

            {/* Partners Section */}
            <section className="py-20 bg-gray-50">
                <div className="container mx-auto px-4">
                    <div className="text-center mb-16">
                        <h2 className="font-bold text-4xl text-center mb-4 font-montserrat text-primary">
                            Trusted by Leading Organizations
                        </h2>
                        <p className="text-lg text-gray-600 max-w-2xl mx-auto font-lato leading-relaxed">
                            Join over 1,300 organizations worldwide who trust BLI for leadership development and transformation
                        </p>
                    </div>

                    {/* Stats Section */}
                    <div className="mt-16 pt-12 border-t border-gray-200">
                        <div className="grid grid-cols-1 md:grid-cols-3 gap-8 max-w-4xl mx-auto">
                            <div className="text-center">
                                <div className="text-4xl font-bold text-primary mb-2 font-montserrat">1,300+</div>
                                <div className="text-gray-600 font-lato">Organizations</div>
                            </div>
                            <div className="text-center">
                                <div className="text-4xl font-bold text-secondary mb-2 font-montserrat">50+</div>
                                <div className="text-gray-600 font-lato">Countries</div>
                            </div>
                            <div className="text-center">
                                <div className="text-4xl font-bold text-accent mb-2 font-montserrat">15+</div>
                                <div className="text-gray-600 font-lato">Years of Excellence</div>
                            </div>
                        </div>
                    </div>
                </div>
            </section>

            {/* Reviews Section */}
            <section className="py-20 bg-gray-50">
                <div className="container mx-auto px-4">
                    <div className="text-center mb-16">
                        <h2 className="font-bold text-4xl text-center mb-4 font-montserrat text-primary">
                            Why Students Love BLI
                        </h2>
                        <p className="text-lg text-gray-600 max-w-2xl mx-auto font-lato">
                            Hear from our community of transformational leaders
                        </p>
                    </div>

                    <div className="grid grid-cols-1 lg:grid-cols-3 gap-8 max-w-6xl mx-auto">
                        {/* Main Review Card */}
                        <div className="lg:col-span-2 bg-white rounded-2xl shadow-sm p-8 relative">
                            <div className="absolute -top-4 -left-4 w-8 h-8 bg-secondary rounded-full flex items-center justify-center">
                                <i className="fas fa-quote-left text-white text-sm"></i>
                            </div>
                            <i className="fas fa-quote-left text-5xl text-secondary/20 mb-6 block"></i>
                            <p className="mb-6 text-gray-700 text-lg leading-relaxed font-lato">
                                "The transformational mentorship at BLI completely reshaped my leadership approach. I've learned to integrate spiritual wisdom with
                                practical business strategy in ways I never thought possible. The kingdom-based framework gave me tools to lead with integrity and purpose."
                            </p>
                            <div className="flex items-center gap-4 pt-6 border-t border-gray-100">
                                <img
                                    src="/images/learning-platform/reviews-01.png"
                                    alt="DeVeor R"
                                    className="w-14 h-14 rounded-full border-2 border-secondary"
                                />
                                <div>
                                    <h6 className="font-bold text-lg font-montserrat text-primary">DeVeor R.</h6>
                                    <p className="text-gray-600 font-lato">Business Leadership Track</p>
                                    <div className="flex items-center gap-1 mt-1">
                                        {[1, 2, 3, 4, 5].map((star) => (
                                            <i key={star} className="fas fa-star text-yellow-400 text-sm"></i>
                                        ))}
                                    </div>
                                </div>
                            </div>
                        </div>

                        {/* Side Review Cards */}
                        <div className="space-y-6">
                            {/* Review 1 */}
                            <div className="bg-white rounded-xl shadow-sm p-6 relative">
                                <i className="fas fa-quote-left text-xl text-accent/30 mb-3 block"></i>
                                <p className="mb-4 text-gray-600 text-sm leading-relaxed font-lato">
                                    "The prophetic leadership integration helped me discern God's direction for our organization. I'm now leading with confidence and clarity."
                                </p>
                                <div className="flex items-center gap-3">
                                    <img
                                        src="/images/learning-platform/reviews-02.png"
                                        alt="Sarah M"
                                        className="w-10 h-10 rounded-full"
                                    />
                                    <div>
                                        <h6 className="font-semibold text-sm font-montserrat text-primary">Sarah M.</h6>
                                        <p className="text-gray-500 text-xs font-lato">Ministry Leadership</p>
                                    </div>
                                </div>
                            </div>

                            {/* Review 2 */}
                            <div className="bg-white rounded-xl shadow-sm p-6 relative">
                                <i className="fas fa-quote-left text-xl text-secondary/30 mb-3 block"></i>
                                <p className="mb-4 text-gray-600 text-sm leading-relaxed font-lato">
                                    "The problem-solving curriculum equipped me to navigate complex challenges in education. I'm rebuilding systems with kingdom principles."
                                </p>
                                <div className="flex items-center gap-3">
                                    <img
                                        src="/images/learning-platform/reviews-02.png"
                                        alt="James K"
                                        className="w-10 h-10 rounded-full"
                                    />
                                    <div>
                                        <h6 className="font-semibold text-sm font-montserrat text-primary">James K.</h6>
                                        <p className="text-gray-500 text-xs font-lato">Education Track</p>
                                    </div>
                                </div>
                            </div>

                            {/* CTA Card */}
                            <div className="bg-gradient-to-r from-primary to-secondary rounded-xl p-6 text-white">
                                <h6 className="font-bold text-lg mb-2 font-montserrat">Ready to Transform Your Leadership?</h6>
                                <p className="text-white/90 text-sm mb-4 font-lato">Join thousands of leaders experiencing transformation</p>
                                <Link
                                    href={route('register')}
                                    className="inline-flex items-center gap-2 bg-white text-primary font-semibold px-4 py-2 rounded-lg text-sm hover:bg-gray-100 transition-colors duration-300 font-montserrat"
                                >
                                    Start Your Journey
                                    <i className="fas fa-arrow-right text-xs"></i>
                                </Link>
                            </div>
                        </div>
                    </div>

                    {/* Stats Row */}
                    <div className="grid grid-cols-2 md:grid-cols-4 gap-6 max-w-4xl mx-auto mt-16">
                        <div className="text-center">
                            <div className="text-3xl font-bold text-primary mb-1 font-montserrat">98%</div>
                            <div className="text-gray-600 text-sm font-lato">Satisfaction Rate</div>
                        </div>
                        <div className="text-center">
                            <div className="text-3xl font-bold text-secondary mb-1 font-montserrat">4.9/5</div>
                            <div className="text-gray-600 text-sm font-lato">Average Rating</div>
                        </div>
                        <div className="text-center">
                            <div className="text-3xl font-bold text-accent mb-1 font-montserrat">2K+</div>
                            <div className="text-gray-600 text-sm font-lato">Leaders Trained</div>
                        </div>
                        <div className="text-center">
                            <div className="text-3xl font-bold text-primary mb-1 font-montserrat">50+</div>
                            <div className="text-gray-600 text-sm font-lato">Countries Reached</div>
                        </div>
                    </div>
                </div>
            </section>

            {/* Learning Section */}
            <section className="bg-gradient-to-br from-white to-gray-50 py-20">
                <div className="container mx-auto px-4">
                    <div className="flex flex-col lg:flex-row items-center gap-12">
                        {/* Content Section */}
                        <div className="lg:w-1/2">
                            <div className="max-w-lg">
                                <h2 className="font-bold text-4xl lg:text-5xl mb-6 font-montserrat text-primary leading-tight">
                                    Transformational Learning
                                    <br />
                                    <span className="text-secondary">At Your Fingertips</span>
                                </h2>
                                <p className="text-lg text-gray-600 mb-8 font-lato leading-relaxed">
                                    Access world-class leadership training anytime, anywhere. BLI's hybrid learning platform
                                    combines spiritual depth with practical leadership skills to equip you for impact in every sphere of society.
                                </p>

                                {/* Features List */}
                                <div className="space-y-4 mb-8">
                                    {[
                                        'Flexible online courses with in-person intensives',
                                        'Prophetic insight integrated with leadership training',
                                        'Global community of kingdom-minded leaders'
                                    ].map((feature, index) => (
                                        <div key={index} className="flex items-center gap-3">
                                            <div className="w-6 h-6 bg-accent rounded-full flex items-center justify-center flex-shrink-0">
                                                <i className="fas fa-check text-white text-xs"></i>
                                            </div>
                                            <span className="text-gray-700 font-lato">{feature}</span>
                                        </div>
                                    ))}
                                </div>

                                {/* CTA Buttons */}
                                <div className="flex flex-col sm:flex-row gap-4">
                                    <Link
                                        href={route('register')}
                                        className="bg-secondary hover:bg-primary text-white font-bold py-4 px-8 rounded-lg shadow-md hover:shadow-lg transition-all duration-300 transform hover:-translate-y-1 text-center font-montserrat"
                                    >
                                        Start Learning Today
                                    </Link>
                                    <Link
                                        href={route('courses.index')}
                                        className="border-2 border-primary text-primary hover:bg-primary hover:text-white font-bold py-4 px-8 rounded-lg transition-all duration-300 text-center font-montserrat"
                                    >
                                        Explore Courses
                                    </Link>
                                </div>
                            </div>
                        </div>

                        {/* Image/Visual Section */}
                        <div className="lg:w-1/2">
                            <div className="relative">
                                {/* Main Image */}
                                <div className="bg-white rounded-2xl shadow-xl p-6 transform rotate-2 hover:rotate-0 transition-transform duration-500">
                                    <img
                                        src="/images/logo.jpg"
                                        alt="BLI Learning Platform"
                                        className="rounded-xl w-full h-auto shadow-md"
                                    />
                                </div>

                                {/* Floating Elements */}
                                <div className="absolute -top-4 -left-4 bg-accent text-white p-4 rounded-xl shadow-lg">
                                    <div className="text-center">
                                        <div className="font-bold text-2xl font-montserrat">2K+</div>
                                        <div className="text-sm font-lato">Leaders Trained</div>
                                    </div>
                                </div>

                                <div className="absolute -bottom-4 -right-4 bg-primary text-white p-4 rounded-xl shadow-lg">
                                    <div className="text-center">
                                        <div className="font-bold text-2xl font-montserrat">98%</div>
                                        <div className="text-sm font-lato">Success Rate</div>
                                    </div>
                                </div>

                                {/* Background Decoration */}
                                <div className="absolute -z-10 top-1/2 left-1/2 transform -translate-x-1/2 -translate-y-1/2 w-64 h-64 bg-secondary/10 rounded-full blur-3xl"></div>
                            </div>
                        </div>
                    </div>

                    {/* Bottom Stats */}
                    <div className="grid grid-cols-2 md:grid-cols-4 gap-6 mt-20 pt-12 border-t border-gray-200">
                        <div className="text-center">
                            <div className="text-3xl font-bold text-primary mb-2 font-montserrat">24/7</div>
                            <div className="text-gray-600 font-lato">Course Access</div>
                        </div>
                        <div className="text-center">
                            <div className="text-3xl font-bold text-secondary mb-2 font-montserrat">50+</div>
                            <div className="text-gray-600 font-lato">Countries</div>
                        </div>
                        <div className="text-center">
                            <div className="text-3xl font-bold text-accent mb-2 font-montserrat">15+</div>
                            <div className="text-gray-600 font-lato">Years Experience</div>
                        </div>
                        <div className="text-center">
                            <div className="text-3xl font-bold text-primary mb-2 font-montserrat">100%</div>
                            <div className="text-gray-600 font-lato">Kingdom Focused</div>
                        </div>
                    </div>
                </div>
            </section>
        </GuestLayout>
    );
}
