<x-guest-layout>
    <!-- Hero Section -->
    <section id="upcoming-events" class="relative bg-gradient-to-r from-orange-900 to-orange-700 py-20 overflow-hidden">
        <!-- Add a dark overlay for better contrast -->
        <div class="absolute inset-0 bg-black bg-opacity-40 z-0"></div>
        <div class="container mx-auto px-4 relative z-10">
            <div class="max-w-3xl mx-auto text-center">
                <h1 class="text-4xl md:text-5xl font-extrabold mb-4 tracking-tight drop-shadow-lg text-white">
                    Discover Upcoming Events
                </h1>
                <p class="text-xl md:text-2xl text-orange-50 mb-10">
                    Be part of our vibrant community—explore inspiring programs, workshops, and gatherings.
                </p>

                <!-- Stats -->
                <div class="flex flex-wrap justify-center gap-10 mt-12">
                    <div class="text-center">
                        <div class="text-4xl font-extrabold text-white flex items-center justify-center gap-2">
                            <i class="fas fa-broadcast-tower text-orange-300"></i>
                            {{ count($ongoingEvents) }}
                        </div>
                        <div class="text-orange-200 text-base mt-2">Happening Now</div>
                    </div>
                    <div class="text-center">
                        <div class="text-4xl font-extrabold text-white flex items-center justify-center gap-2">
                            <i class="fas fa-calendar-alt text-orange-300"></i>
                            {{ count($upcomingEvents) }}
                        </div>
                        <div class="text-orange-200 text-base mt-2">Coming Soon</div>
                    </div>
                    <div class="text-center">
                        <div class="text-4xl font-extrabold text-white flex items-center justify-center gap-2">
                            <i class="fas fa-history text-orange-300"></i>
                            {{ count($expiredEvents) }}
                        </div>
                        <div class="text-orange-200 text-base mt-2">Past Events</div>
                    </div>
                </div>
            </div>
        </div>
        <!-- Decorative SVG -->
        <svg class="absolute bottom-0 left-0 w-full h-24 md:h-32 text-orange-800 opacity-60" viewBox="0 0 1440 320" fill="none" xmlns="http://www.w3.org/2000/svg">
            <path fill="currentColor" d="M0,224L48,213.3C96,203,192,181,288,186.7C384,192,480,224,576,229.3C672,235,768,213,864,197.3C960,181,1056,171,1152,181.3C1248,192,1344,224,1392,240L1440,256L1440,320L1392,320C1344,320,1248,320,1152,320C1056,320,960,320,864,320C768,320,672,320,576,320C480,320,384,320,288,320C192,320,96,320,48,320L0,320Z"/>
        </svg>
    </section>

    <!-- Events Section -->
    <section class="py-16 bg-gray-50">
        <div class="container mx-auto px-4">
            <!-- Tab Navigation -->
            <div class="flex flex-wrap justify-center gap-4 mb-12">
                <button
                    class="tab-btn active px-6 py-3 rounded-full bg-white shadow-md border border-orange-100 font-semibold text-orange-600 flex items-center gap-2 transition-all duration-300 hover:shadow-lg"
                    data-tab="happening">
                    <i class="fas fa-play-circle"></i>
                    Happening Now
                    <span
                        class="bg-orange-100 text-orange-800 text-xs px-2 py-1 rounded-full">{{ count($ongoingEvents) }}</span>
                </button>
                <button
                    class="tab-btn px-6 py-3 rounded-full bg-white shadow-md border border-gray-200 font-semibold text-gray-600 flex items-center gap-2 transition-all duration-300 hover:shadow-lg"
                    data-tab="upcoming">
                    <i class="fas fa-clock"></i>
                    Coming Soon
                    <span
                        class="bg-orange-100 text-orange-800 text-xs px-2 py-1 rounded-full">{{ count($upcomingEvents) }}</span>
                </button>
                <button
                    class="tab-btn px-6 py-3 rounded-full bg-white shadow-md border border-gray-200 font-semibold text-gray-600 flex items-center gap-2 transition-all duration-300 hover:shadow-lg"
                    data-tab="expired">
                    <i class="fas fa-history"></i>
                    Past Events
                    <span
                        class="bg-gray-100 text-gray-800 text-xs px-2 py-1 rounded-full">{{ count($expiredEvents) }}</span>
                </button>
            </div>

            <!-- Happening Events Tab -->
            <div class="tab-content active" id="happening">
                @if (count($ongoingEvents))
                    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
                        @foreach ($ongoingEvents as $event)
                            <div
                                class="bg-white rounded-xl shadow-lg overflow-hidden transition-transform duration-300 hover:scale-[1.02] border border-orange-100">
                                <div class="flex flex-col md:flex-row">
                                    <!-- Event Image -->
                                    <div class="md:w-2/5 relative">
                                        <img src="{{ asset('storage/' . $event->program_cover) }}"
                                            alt="{{ $event->title }}" class="w-full h-48 md:h-full object-cover">
                                        <div
                                            class="absolute top-4 left-4 bg-red-500 text-white px-3 py-1 rounded-full text-sm font-semibold">
                                            Live Now
                                        </div>
                                    </div>

                                    <!-- Event Content -->
                                    <div class="md:w-3/5 p-6">
                                        <div class="flex items-start justify-between mb-3">
                                            <h3 class="text-xl font-bold text-gray-800 line-clamp-2 flex-1 mr-4">
                                                <a href="{{ route('events.show', $event->slug) }}"
                                                    class="hover:text-orange-600 transition-colors">{{ $event->title }}</a>
                                            </h3>
                                            @if ($event->entry_fee > 0)
                                                <span
                                                    class="bg-green-100 text-green-800 px-3 py-1 rounded-full text-sm font-semibold whitespace-nowrap">
                                                    ₦{{ number_format($event->entry_fee, 2) }}
                                                </span>
                                            @else
                                                <span
                                                    class="bg-orange-100 text-orange-800 px-3 py-1 rounded-full text-sm font-semibold whitespace-nowrap">
                                                    Free
                                                </span>
                                            @endif
                                        </div>

                                        <p class="text-gray-600 mb-4 line-clamp-3">{{ $event->description }}</p>

                                        <div class="space-y-2 mb-4">
                                            <div class="flex items-center gap-2 text-gray-600">
                                                <i class="far fa-calendar text-orange-500"></i>
                                                <span>{{ \Carbon\Carbon::parse($event->start_date)->format('F j, Y') }}</span>
                                            </div>
                                            <div class="flex items-center gap-2 text-gray-600">
                                                <i class="far fa-clock text-orange-500"></i>
                                                <span>{{ \Carbon\Carbon::parse($event->start_date)->format('g:i A') }}</span>
                                            </div>
                                            <div class="flex items-center gap-2 text-gray-600">
                                                <i class="fas fa-map-marker-alt text-orange-500"></i>
                                                <span>{{ $event->mode }}</span>
                                            </div>
                                        </div>

                                        <div class="flex items-center justify-between">
                                            <a href="{{ route('events.show', $event->slug) }}"
                                                class="bg-orange-600 text-white px-6 py-2 rounded-lg font-semibold hover:bg-orange-700 transition-colors flex items-center gap-2">
                                                Join Now
                                                <i class="fas fa-arrow-right"></i>
                                            </a>
                                            <span class="text-sm text-green-600 font-semibold flex items-center gap-1">
                                                <i class="fas fa-circle animate-pulse"></i>
                                                Live
                                            </span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @else
                    <div class="text-center py-16">
                        <div class="bg-white rounded-2xl shadow-sm p-12 max-w-md mx-auto">
                            <i class="fas fa-calendar-times text-6xl text-gray-300 mb-6"></i>
                            <h3 class="text-2xl font-bold text-gray-700 mb-4">No Events Happening Now</h3>
                            <p class="text-gray-600 mb-6">Check back later for upcoming events or browse our past
                                events.</p>
                            <button
                                class="tab-switch bg-orange-600 text-white px-6 py-3 rounded-lg font-semibold hover:bg-orange-700 transition-colors"
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
                                class="bg-white rounded-xl shadow-md overflow-hidden transition-all duration-300 hover:shadow-lg border border-gray-200">
                                <!-- Event Image -->
                                <div class="relative">
                                    <img src="{{ asset('storage/' . $event->program_cover) }}"
                                        alt="{{ $event->title }}" class="w-full h-48 object-cover">
                                    <div
                                        class="absolute top-4 left-4 bg-orange-500 text-white px-3 py-1 rounded-full text-sm font-semibold">
                                        Coming Soon
                                    </div>
                                    <div
                                        class="absolute top-4 right-4 bg-white/90 backdrop-blur-sm rounded-lg p-2 text-center">
                                        <div class="text-xl font-bold text-gray-800">
                                            {{ \Carbon\Carbon::parse($event->start_date)->format('d') }}</div>
                                        <div class="text-xs text-gray-600 uppercase">
                                            {{ \Carbon\Carbon::parse($event->start_date)->format('M') }}</div>
                                    </div>
                                </div>

                                <!-- Event Content -->
                                <div class="p-6">
                                    <h3 class="text-lg font-bold text-gray-800 mb-3 line-clamp-2">
                                        <a href="{{ route('events.show', $event->slug) }}"
                                            class="hover:text-orange-600 transition-colors">{{ $event->title }}</a>
                                    </h3>

                                    <p class="text-gray-600 mb-4 line-clamp-3 text-sm">{{ $event->description }}</p>

                                    <div class="space-y-2 mb-4">
                                        <div class="flex items-center gap-2 text-gray-600 text-sm">
                                            <i class="far fa-clock text-orange-500"></i>
                                            <span>{{ \Carbon\Carbon::parse($event->start_date)->format('g:i A') }}</span>
                                        </div>
                                        <div class="flex items-center gap-2 text-gray-600 text-sm">
                                            <i class="fas fa-map-marker-alt text-orange-500"></i>
                                            <span>{{ $event->mode }}</span>
                                        </div>
                                    </div>

                                    <div class="flex items-center justify-between">
                                        @if ($event->entry_fee > 0)
                                            <span
                                                class="text-lg font-bold text-green-600">₦{{ number_format($event->entry_fee, 2) }}</span>
                                        @else
                                            <span class="text-lg font-bold text-orange-600">Free</span>
                                        @endif
                                        <a href="{{ route('events.show', $event->slug) }}"
                                            class="bg-gray-800 text-white px-4 py-2 rounded-lg text-sm font-semibold hover:bg-gray-900 transition-colors">
                                            Details
                                        </a>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @else
                    <div class="text-center py-16">
                        <div class="bg-white rounded-2xl shadow-sm p-12 max-w-md mx-auto">
                            <i class="fas fa-calendar-plus text-6xl text-gray-300 mb-6"></i>
                            <h3 class="text-2xl font-bold text-gray-700 mb-4">No Upcoming Events</h3>
                            <p class="text-gray-600">We're working on new events. Stay tuned for updates!</p>
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
                                class="bg-white rounded-xl shadow-sm overflow-hidden border border-gray-200 opacity-75">
                                <div class="flex flex-col md:flex-row">
                                    <!-- Event Image -->
                                    <div class="md:w-2/5 relative">
                                        <img src="{{ asset('storage/' . $event->program_cover) }}"
                                            alt="{{ $event->title }}"
                                            class="w-full h-48 md:h-full object-cover grayscale">
                                        <div
                                            class="absolute top-4 left-4 bg-gray-500 text-white px-3 py-1 rounded-full text-sm font-semibold">
                                            Completed
                                        </div>
                                    </div>

                                    <!-- Event Content -->
                                    <div class="md:w-3/5 p-6">
                                        <h3 class="text-xl font-bold text-gray-500 mb-3 line-clamp-2">
                                            {{ $event->title }}
                                        </h3>

                                        <p class="text-gray-500 mb-4 line-clamp-3">{{ $event->description }}</p>

                                        <div class="space-y-2 mb-4">
                                            <div class="flex items-center gap-2 text-gray-500">
                                                <i class="far fa-calendar text-gray-400"></i>
                                                <span>{{ \Carbon\Carbon::parse($event->start_date)->format('F j, Y') }}</span>
                                            </div>
                                            <div class="flex items-center gap-2 text-gray-500">
                                                <i class="fas fa-map-marker-alt text-gray-400"></i>
                                                <span>{{ $event->mode }}</span>
                                            </div>
                                        </div>

                                        <div class="text-sm text-gray-500 italic">
                                            This event has ended
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @else
                    <div class="text-center py-16">
                        <div class="bg-white rounded-2xl shadow-sm p-12 max-w-md mx-auto">
                            <i class="fas fa-calendar-check text-6xl text-gray-300 mb-6"></i>
                            <h3 class="text-2xl font-bold text-gray-700 mb-4">No Past Events</h3>
                            <p class="text-gray-600">Our event history will appear here once we have completed events.
                            </p>
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </section>

    <!-- CTA Section -->
    <section class="py-16 bg-orange-900 text-white">
        <div class="container mx-auto px-4">
            <div class="max-w-4xl mx-auto text-center">
                <h2 class="text-3xl md:text-4xl font-bold mb-6">Never Miss an Event</h2>
                <p class="text-xl text-orange-200 mb-8">Subscribe to our newsletter and be the first to know about
                    upcoming programs and workshops.</p>
                <div class="flex flex-col sm:flex-row gap-4 max-w-md mx-auto">
                    <input type="email" placeholder="Enter your email"
                        class="flex-1 px-4 py-3 rounded-lg text-gray-800 focus:outline-none focus:ring-2 focus:ring-orange-500">
                    <button
                        class="bg-orange-500 text-white px-6 py-3 rounded-lg font-semibold hover:bg-orange-600 transition-colors whitespace-nowrap">
                        Subscribe Now
                    </button>
                </div>
            </div>
        </div>
    </section>

    <script>
        // Enhanced Tab Functionality
        const tabButtons = document.querySelectorAll(".tab-btn");
        const tabContents = document.querySelectorAll(".tab-content");
        const tabSwitchButtons = document.querySelectorAll(".tab-switch");

        // Get last active tab from localStorage or default to 'happening'
        let activeTab = localStorage.getItem('activeEventTab') || 'happening';

        function setActiveTab(tabName) {
            // Update tab buttons
            tabButtons.forEach(btn => {
                if (btn.dataset.tab === tabName) {
                    btn.classList.add('active', 'text-orange-600', 'border-orange-200', 'shadow-lg');
                    btn.classList.remove('text-gray-600', 'border-gray-200');
                } else {
                    btn.classList.remove('active', 'text-orange-600', 'border-orange-200', 'shadow-lg');
                    btn.classList.add('text-gray-600', 'border-gray-200');
                }
            });

            // Update tab contents
            tabContents.forEach(content => {
                if (content.id === tabName) {
                    content.classList.remove('hidden');
                    content.classList.add('active');
                } else {
                    content.classList.add('hidden');
                    content.classList.remove('active');
                }
            });

            localStorage.setItem('activeEventTab', tabName);
        }

        // Initial activation
        setActiveTab(activeTab);

        // Tab button click events
        tabButtons.forEach(btn => {
            btn.addEventListener('click', function() {
                setActiveTab(this.dataset.tab);
            });
        });

        // Tab switch buttons (from empty states)
        tabSwitchButtons.forEach(btn => {
            btn.addEventListener('click', function() {
                setActiveTab(this.dataset.tab);
            });
        });

        // Add smooth scrolling to tabs
        tabButtons.forEach(btn => {
            btn.addEventListener('click', function() {
                document.getElementById('upcoming-events').scrollIntoView({
                    behavior: 'smooth',
                    block: 'start'
                });
            });
        });
    </script>

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

        .line-clamp-4 {
            display: -webkit-box;
            -webkit-line-clamp: 4;
            -webkit-box-orient: vertical;
            overflow: hidden;
        }

        .grayscale {
            filter: grayscale(100%);
        }
    </style>
</x-guest-layout>
