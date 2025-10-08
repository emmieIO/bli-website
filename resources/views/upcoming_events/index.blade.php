<x-guest-layout>
    <!-- Hero Section -->
    <section id="upcoming-events"
        class="relative bg-gradient-to-br from-primary via-primary to-secondary py-20 overflow-hidden">
        <!-- Add a dark overlay for better contrast -->
        <div class="absolute inset-0 bg-black/20 z-0"></div>

        <!-- Background Pattern -->
        <div class="absolute inset-0 z-0 opacity-10">
            <div class="absolute top-10 left-10 w-20 h-20 bg-white rounded-full"></div>
            <div class="absolute top-1/3 right-20 w-16 h-16 bg-accent rounded-full"></div>
            <div class="absolute bottom-20 left-1/4 w-12 h-12 bg-white rounded-full"></div>
            <div class="absolute bottom-1/3 right-10 w-24 h-24 bg-accent/50 rounded-full"></div>
        </div>

        <div class="container mx-auto px-4 relative z-10">
            <div class="max-w-4xl mx-auto text-center">
                <div class="mb-8" data-aos="fade-down">
                    <span
                        class="inline-block bg-white/20 text-white px-4 py-2 rounded-full text-sm font-semibold font-montserrat mb-4 backdrop-blur-sm">
                        Transformational Gatherings
                    </span>
                </div>

                <h1 class="text-4xl md:text-6xl font-bold mb-6 tracking-tight text-white font-montserrat leading-tight drop-shadow-lg"
                    data-aos="fade-up">
                    Discover Kingdom<br>
                    <span class="text-accent">Events & Gatherings</span>
                </h1>

                <p class="text-xl md:text-2xl text-white/90 mb-12 max-w-2xl mx-auto font-lato leading-relaxed"
                    data-aos="fade-up" data-aos-delay="100">
                    Join our vibrant community of leaders for inspiring programs, prophetic workshops, and
                    transformative gatherings.
                </p>

                <!-- Stats -->
                <div class="flex flex-wrap justify-center gap-8 md:gap-12 mt-16" data-aos="fade-up"
                    data-aos-delay="200">
                    <div class="text-center group" data-aos="zoom-in" data-aos-delay="300">
                        <div
                            class="bg-white/10 backdrop-blur-sm rounded-2xl p-6 border border-white/20 group-hover:bg-white/20 transition-all duration-300">
                            <div
                                class="text-3xl md:text-4xl font-bold text-white flex items-center justify-center gap-3 font-montserrat">
                                <i class="fas fa-broadcast-tower text-accent text-2xl"></i>
                                {{ count($ongoingEvents) }}
                            </div>
                            <div class="text-accent font-semibold text-base mt-3 font-lato">Happening Now</div>
                        </div>
                    </div>

                    <div class="text-center group" data-aos="zoom-in" data-aos-delay="400">
                        <div
                            class="bg-white/10 backdrop-blur-sm rounded-2xl p-6 border border-white/20 group-hover:bg-white/20 transition-all duration-300">
                            <div
                                class="text-3xl md:text-4xl font-bold text-white flex items-center justify-center gap-3 font-montserrat">
                                <i class="fas fa-calendar-alt text-accent text-2xl"></i>
                                {{ count($upcomingEvents) }}
                            </div>
                            <div class="text-accent font-semibold text-base mt-3 font-lato">Coming Soon</div>
                        </div>
                    </div>

                    <div class="text-center group" data-aos="zoom-in" data-aos-delay="500">
                        <div
                            class="bg-white/10 backdrop-blur-sm rounded-2xl p-6 border border-white/20 group-hover:bg-white/20 transition-all duration-300">
                            <div
                                class="text-3xl md:text-4xl font-bold text-white flex items-center justify-center gap-3 font-montserrat">
                                <i class="fas fa-history text-accent text-2xl"></i>
                                {{ count($expiredEvents) }}
                            </div>
                            <div class="text-accent font-semibold text-base mt-3 font-lato">Past Events</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Decorative SVG -->
        <svg class="absolute bottom-0 left-0 w-full h-24 md:h-32 text-primary opacity-80" viewBox="0 0 1440 320"
            fill="none" xmlns="http://www.w3.org/2000/svg">
            <path fill="currentColor"
                d="M0,224L48,213.3C96,203,192,181,288,186.7C384,192,480,224,576,229.3C672,235,768,213,864,197.3C960,181,1056,171,1152,181.3C1248,192,1344,224,1392,240L1440,256L1440,320L1392,320C1344,320,1248,320,1152,320C1056,320,960,320,864,320C768,320,672,320,576,320C480,320,384,320,288,320C192,320,96,320,48,320L0,320Z" />
        </svg>

        <!-- Scroll Indicator -->
        <div class="absolute bottom-8 left-1/2 transform -translate-x-1/2 z-20" data-aos="fade-up" data-aos-delay="800">
            <a href="#events-grid"
                class="animate-bounce inline-block bg-white/20 backdrop-blur-sm rounded-full p-3 border border-white/30">
                <i class="fas fa-chevron-down text-white text-lg"></i>
            </a>
        </div>
    </section>

    <!-- Events Section -->
    <section class="py-20 bg-gray-50" id="events-grid">
        <div class="container mx-auto px-4">
            <!-- Section Header -->
            <div class="text-center mb-16" data-aos="fade-up">
                <h2 class="font-bold text-4xl mb-4 font-montserrat text-primary">Transformational Events</h2>
                <p class="text-lg text-gray-600 max-w-2xl mx-auto font-lato leading-relaxed">
                    Join our kingdom-focused gatherings designed to equip, empower, and transform leaders for impact
                </p>
            </div>

            <!-- Tab Navigation -->
            <div class="flex flex-wrap justify-center gap-4 mb-16" data-aos="fade-up" data-aos-delay="100">
                <button
                    class="tab-btn active px-6 py-3 rounded-full bg-white shadow-md border border-primary/20 font-semibold text-primary flex items-center gap-2 transition-all duration-300 hover:shadow-lg hover:border-primary/40 font-montserrat group"
                    data-tab="happening">
                    <i class="fas fa-play-circle text-secondary group-hover:scale-110 transition-transform"></i>
                    Happening Now
                    <span
                        class="bg-primary text-white text-xs px-2 py-1 rounded-full min-w-6">{{ count($ongoingEvents) }}</span>
                </button>
                <button
                    class="tab-btn px-6 py-3 rounded-full bg-white shadow-md border border-gray-200 font-semibold text-gray-600 flex items-center gap-2 transition-all duration-300 hover:shadow-lg hover:border-primary/40 font-montserrat group"
                    data-tab="upcoming">
                    <i class="fas fa-clock text-secondary group-hover:scale-110 transition-transform"></i>
                    Coming Soon
                    <span
                        class="bg-secondary text-white text-xs px-2 py-1 rounded-full min-w-6">{{ count($upcomingEvents) }}</span>
                </button>
                <button
                    class="tab-btn px-6 py-3 rounded-full bg-white shadow-md border border-gray-200 font-semibold text-gray-600 flex items-center gap-2 transition-all duration-300 hover:shadow-lg hover:border-primary/40 font-montserrat group"
                    data-tab="expired">
                    <i class="fas fa-history text-secondary group-hover:scale-110 transition-transform"></i>
                    Past Events
                    <span
                        class="bg-gray-500 text-white text-xs px-2 py-1 rounded-full min-w-6">{{ count($expiredEvents) }}</span>
                </button>
            </div>

            <!-- Happening Events Tab -->
            <div class="tab-content active" id="happening" data-aos="fade-up" data-aos-delay="200">
                @if (count($ongoingEvents))
                    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
                        @foreach ($ongoingEvents as $event)
                            <div
                                class="bg-white rounded-2xl shadow-lg overflow-hidden transition-all duration-300 hover:shadow-xl border border-secondary/20 hover:border-secondary/40 group">
                                <div class="flex flex-col md:flex-row h-full">
                                    <!-- Event Image -->
                                    <div class="md:w-2/5 relative">
                                        <img src="{{ asset('storage/' . $event->program_cover) }}"
                                            alt="{{ $event->title }}"
                                            class="w-full h-48 md:h-full object-cover group-hover:scale-105 transition-transform duration-500">
                                        <div
                                            class="absolute top-4 left-4 bg-secondary text-white px-3 py-2 rounded-full text-sm font-semibold font-montserrat shadow-lg animate-pulse">
                                            🔴 Live Now
                                        </div>
                                        <div
                                            class="absolute inset-0 bg-gradient-to-t from-black/20 to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-300">
                                        </div>
                                    </div>

                                    <!-- Event Content -->
                                    <div class="md:w-3/5 p-6 flex flex-col">
                                        <div class="flex items-start justify-between mb-3">
                                            <h3
                                                class="text-xl font-bold text-primary line-clamp-2 flex-1 mr-4 font-montserrat group-hover:text-secondary transition-colors">
                                                <a href="{{ route('events.show', $event->slug) }}">
                                                    {{ $event->title }}
                                                </a>
                                            </h3>
                                            @if ($event->entry_fee > 0)
                                                <span
                                                    class="bg-accent/20 text-accent px-3 py-1 rounded-full text-sm font-semibold whitespace-nowrap font-montserrat border border-accent/30">
                                                    ₦{{ number_format($event->entry_fee, 2) }}
                                                </span>
                                            @else
                                                <span
                                                    class="bg-secondary/20 text-secondary px-3 py-1 rounded-full text-sm font-semibold whitespace-nowrap font-montserrat border border-secondary/30">
                                                    Free
                                                </span>
                                            @endif
                                        </div>

                                        <p class="text-gray-600 mb-4 line-clamp-3 font-lato leading-relaxed flex-grow">
                                            {{ $event->description }}</p>

                                        <div class="space-y-3 mb-4">
                                            <div class="flex items-center gap-3 text-gray-600 font-lato">
                                                <i class="far fa-calendar text-secondary"></i>
                                                <span>{{ \Carbon\Carbon::parse($event->start_date)->format('F j, Y') }}</span>
                                            </div>
                                            <div class="flex items-center gap-3 text-gray-600 font-lato">
                                                <i class="far fa-clock text-secondary"></i>
                                                <span>{{ \Carbon\Carbon::parse($event->start_date)->format('g:i A') }}</span>
                                            </div>
                                            <div class="flex items-center gap-3 text-gray-600 font-lato">
                                                <i class="fas fa-map-marker-alt text-secondary"></i>
                                                <span class="capitalize">{{ $event->mode }}</span>
                                            </div>
                                        </div>

                                        <div
                                            class="flex items-center justify-between mt-auto pt-4 border-t border-gray-100">
                                            <a href="{{ route('events.show', $event->slug) }}"
                                                class="bg-secondary hover:bg-primary text-white px-6 py-3 rounded-lg font-semibold transition-all duration-300 transform hover:-translate-y-1 shadow-md hover:shadow-lg flex items-center gap-2 font-montserrat">
                                                Join Now
                                                <i
                                                    class="fas fa-arrow-right group-hover:translate-x-1 transition-transform"></i>
                                            </a>
                                            <span
                                                class="text-sm text-secondary font-semibold flex items-center gap-2 font-montserrat">
                                                <i class="fas fa-circle animate-pulse"></i>
                                                Live Event
                                            </span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @else
                    <div class="text-center py-16" data-aos="fade-up">
                        <div class="bg-white rounded-2xl shadow-sm p-12 max-w-md mx-auto border border-gray-100">
                            <i class="fas fa-calendar-times text-6xl text-gray-300 mb-6"></i>
                            <h3 class="text-2xl font-bold text-gray-700 mb-4 font-montserrat">No Live Events</h3>
                            <p class="text-gray-600 mb-6 font-lato">Check back later for ongoing events or explore our
                                upcoming gatherings.</p>
                            <button
                                class="tab-switch bg-secondary hover:bg-primary text-white px-6 py-3 rounded-lg font-semibold transition-colors font-montserrat"
                                data-tab="upcoming">
                                View Upcoming Events
                            </button>
                        </div>
                    </div>
                @endif
            </div>

            <!-- Upcoming Events Tab -->
            <div class="tab-content hidden" id="upcoming">
                @if (count($upcomingEvents))
                    <div class="grid grid-cols-1 lg:grid-cols-2 xl:grid-cols-3 gap-8">
                        @foreach ($upcomingEvents as $event)
                            <div
                                class="bg-white rounded-2xl shadow-md overflow-hidden transition-all duration-300 hover:shadow-xl border border-gray-200 hover:border-primary/30 group">
                                <!-- Event Image -->
                                <div class="relative">
                                    <img src="{{ asset('storage/' . $event->program_cover) }}"
                                        alt="{{ $event->title }}"
                                        class="w-full h-48 object-cover group-hover:scale-105 transition-transform duration-500">
                                    <div
                                        class="absolute top-4 left-4 bg-primary text-white px-3 py-2 rounded-full text-sm font-semibold font-montserrat shadow-lg">
                                        Coming Soon
                                    </div>
                                    <div
                                        class="absolute top-4 right-4 bg-white/90 backdrop-blur-sm rounded-xl p-3 text-center border border-white/20">
                                        <div class="text-xl font-bold text-primary font-montserrat">
                                            {{ \Carbon\Carbon::parse($event->start_date)->format('d') }}</div>
                                        <div class="text-xs text-gray-600 uppercase font-montserrat tracking-wide">
                                            {{ \Carbon\Carbon::parse($event->start_date)->format('M') }}</div>
                                    </div>
                                    <div
                                        class="absolute inset-0 bg-gradient-to-t from-black/10 to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-300">
                                    </div>
                                </div>

                                <!-- Event Content -->
                                <div class="p-6">
                                    <h3
                                        class="text-lg font-bold text-primary mb-3 line-clamp-2 font-montserrat group-hover:text-secondary transition-colors">
                                        <a href="{{ route('events.show', $event->slug) }}">
                                            {{ $event->title }}
                                        </a>
                                    </h3>

                                    <p class="text-gray-600 mb-4 line-clamp-3 text-sm font-lato leading-relaxed">
                                        {{ $event->description }}</p>

                                    <div class="space-y-3 mb-4">
                                        <div class="flex items-center gap-3 text-gray-600 text-sm font-lato">
                                            <i class="far fa-clock text-secondary"></i>
                                            <span>{{ \Carbon\Carbon::parse($event->start_date)->format('g:i A') }}</span>
                                        </div>
                                        <div class="flex items-center gap-3 text-gray-600 text-sm font-lato">
                                            <i class="fas fa-map-marker-alt text-secondary"></i>
                                            <span class="capitalize">{{ $event->mode }}</span>
                                        </div>
                                    </div>

                                    <div class="flex items-center justify-between pt-4 border-t border-gray-100">
                                        @if ($event->entry_fee > 0)
                                            <span
                                                class="text-lg font-bold text-accent font-montserrat">₦{{ number_format($event->entry_fee, 2) }}</span>
                                        @else
                                            <span class="text-lg font-bold text-secondary font-montserrat">Free</span>
                                        @endif
                                        <a href="{{ route('events.show', $event->slug) }}"
                                            class="bg-primary hover:bg-secondary text-white px-4 py-2 rounded-lg text-sm font-semibold transition-colors font-montserrat">
                                            View Details
                                        </a>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @else
                    <div class="text-center py-16">
                        <div class="bg-white rounded-2xl shadow-sm p-12 max-w-md mx-auto border border-gray-100">
                            <i class="fas fa-calendar-plus text-6xl text-gray-300 mb-6"></i>
                            <h3 class="text-2xl font-bold text-gray-700 mb-4 font-montserrat">No Upcoming Events</h3>
                            <p class="text-gray-600 font-lato">We're preparing new transformative gatherings. Stay
                                tuned for updates!</p>
                        </div>
                    </div>
                @endif
            </div>

            <!-- Expired Events Tab -->
            <div class="tab-content hidden" id="expired">
                @if (count($expiredEvents))
                    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
                        @foreach ($expiredEvents as $event)
                            <div
                                class="bg-white rounded-2xl shadow-sm overflow-hidden border border-gray-200 opacity-80 hover:opacity-100 transition-opacity duration-300 group">
                                <div class="flex flex-col md:flex-row">
                                    <!-- Event Image -->
                                    <div class="md:w-2/5 relative">
                                        <img src="{{ asset('storage/' . $event->program_cover) }}"
                                            alt="{{ $event->title }}"
                                            class="w-full h-48 md:h-full object-cover grayscale group-hover:grayscale-0 transition-all duration-500">
                                        <div
                                            class="absolute top-4 left-4 bg-gray-500 text-white px-3 py-2 rounded-full text-sm font-semibold font-montserrat">
                                            Completed
                                        </div>
                                    </div>

                                    <!-- Event Content -->
                                    <div class="md:w-3/5 p-6">
                                        <h3 class="text-xl font-bold text-gray-500 mb-3 line-clamp-2 font-montserrat">
                                            {{ $event->title }}
                                        </h3>

                                        <p class="text-gray-500 mb-4 line-clamp-3 font-lato leading-relaxed">
                                            {{ $event->description }}</p>

                                        <div class="space-y-3 mb-4">
                                            <div class="flex items-center gap-3 text-gray-500 font-lato">
                                                <i class="far fa-calendar text-gray-400"></i>
                                                <span>{{ \Carbon\Carbon::parse($event->start_date)->format('F j, Y') }}</span>
                                            </div>
                                            <div class="flex items-center gap-3 text-gray-500 font-lato">
                                                <i class="fas fa-map-marker-alt text-gray-400"></i>
                                                <span class="capitalize">{{ $event->mode }}</span>
                                            </div>
                                        </div>

                                        <div
                                            class="text-sm text-gray-500 italic font-lato border-t border-gray-200 pt-4">
                                            This transformative event has concluded
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @else
                    <div class="text-center py-16">
                        <div class="bg-white rounded-2xl shadow-sm p-12 max-w-md mx-auto border border-gray-100">
                            <i class="fas fa-calendar-check text-6xl text-gray-300 mb-6"></i>
                            <h3 class="text-2xl font-bold text-gray-700 mb-4 font-montserrat">No Past Events</h3>
                            <p class="text-gray-600 font-lato">Our event history will appear here once we have
                                completed gatherings.</p>
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </section>

    <!-- CTA Section -->
    <section class="py-20 bg-gradient-to-r from-primary to-secondary text-white">
        <div class="container mx-auto px-4">
            <div class="max-w-4xl mx-auto text-center">
                <h2 class="text-3xl md:text-4xl font-bold mb-6 font-montserrat">Never Miss a Transformational Event</h2>
                <p class="text-xl text-white/90 mb-8 font-lato leading-relaxed">Subscribe to our newsletter and be the first to know about
                    upcoming programs, prophetic workshops, and leadership gatherings.</p>
                <div class="flex flex-col sm:flex-row gap-4 max-w-md mx-auto">
                    <input type="email" placeholder="Enter your email"
                        class="flex-1 px-4 py-3 rounded-lg text-gray-800 focus:outline-none focus:ring-2 focus:ring-accent font-lato">
                    <button
                        class="bg-accent hover:bg-white text-white hover:text-primary px-6 py-3 rounded-lg font-semibold transition-all duration-300 transform hover:-translate-y-1 shadow-md hover:shadow-lg font-montserrat whitespace-nowrap">
                        Subscribe Now
                    </button>
                </div>
                <p class="text-white/70 text-sm mt-4 font-lato">Join thousands of leaders staying informed about kingdom events</p>
            </div>
        </div>
    </section>

    <style>
        .line-clamp-2 {
            display: -webkit-box;
            -webkit-line-clamp: 2;
            -webkit-box-orient: vertical;
            overflow: hidden;
        }

        .line-clamp-3 {
            display: -webkit-box;
            -webkit-line-clamp: 3;
            -webkit-box-orient: vertical;
            overflow: hidden;
        }

        .grayscale {
            filter: grayscale(100%);
        }

        /* Smooth transitions for tab content */
        .tab-content {
            transition: opacity 0.3s ease, transform 0.3s ease;
        }
    </style>
</x-guest-layout>