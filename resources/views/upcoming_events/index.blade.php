    @php
        use Carbon\Carbon;
    @endphp
    <x-guest-layout>
        <!-- Compact Hero Section -->
        <section id="upcoming-events" class="py-8 bg-primary text-white">
            <div class="container mx-auto px-4 text-center">
                <h1 class="text-2xl md:text-3xl font-bold mb-2 font-montserrat">Events</h1>
                <p class="text-xs md:text-sm text-white/90 mb-4 font-bold font-lato">
                    Join our programs, workshops and transformative gatherings.
                </p>
                <form action="{{ route('events.index') }}" method="GET" class="max-w-xl mx-auto mb-6">
                    <label for="q" class="sr-only">Search events</label>
                    <div class="flex items-center bg-white/10 rounded-lg p-1.5">
                        <input id="q" name="q" type="search" value="{{ request('q') }}"
                            placeholder="Search events, enter title or mode, physical address"
                            class="flex-1 bg-transparent placeholder-white/80 text-white text-sm px-3 py-2 focus:outline-none font-lato"
                            aria-label="Search events" />
                        <button type="submit"
                            class="ml-2 bg-accent hover:bg-accent/90 text-white px-4 py-2 rounded-md text-sm font-semibold font-montserrat">
                            Search
                        </button>
                    </div>
                </form>


                <div class="flex justify-center gap-4 mb-4">
                    <div class="bg-white/10 px-3 py-1.5 rounded-full flex items-center gap-2">
                        <i class="fas fa-broadcast-tower text-accent text-sm"></i>
                        <div class="text-left">
                            <div class="font-bold text-base font-montserrat">{{ $ongoingEvents }}</div>
                            <div class="text-xs text-white/80 font-lato">Happening Now</div>
                        </div>
                    </div>

                    <div class="bg-white/10 px-3 py-1.5 rounded-full flex items-center gap-2">
                        <i class="fas fa-calendar-alt text-accent text-sm"></i>
                        <div class="text-left">
                            <div class="font-bold text-base font-montserrat">{{ $upcomingEvents }}</div>
                            <div class="text-xs text-white/80 font-lato">Coming Soon</div>
                        </div>
                    </div>

                    <div class="bg-white/10 px-3 py-1.5 rounded-full flex items-center gap-2">
                        <i class="fas fa-history text-accent text-sm"></i>
                        <div class="text-left">
                            <div class="font-bold text-base font-montserrat">{{ $expiredEvents }}</div>
                            <div class="text-xs text-white/80 font-lato">Past Events</div>
                        </div>
                    </div>
                </div>

                <a href="#events-grid"
                    class="inline-block bg-white/20 hover:bg-white/30 text-white/95 px-4 py-1.5 rounded-lg text-xs font-semibold font-montserrat transition">
                    View Events
                </a>
            </div>
        </section>

        <!-- Compact Events Section -->
        <section class="py-12 bg-gray-50" id="events-grid">
            <div class="container mx-auto px-4">
                <!-- Section Header -->
                {{-- <div class="text-center mb-10" data-aos="fade-up">
                    <h2 class="font-bold text-3xl mb-3 font-montserrat text-primary">Transformational Events</h2>
                    <p class="text-base text-gray-600 max-w-2xl mx-auto font-lato leading-relaxed">
                        Join our kingdom-focused gatherings designed to equip, empower, and transform leaders for impact
                    </p>
                </div> --}}

                @if ($events->count())
                    <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-5 mb-10" data-aos="fade-up"
                        data-aos-delay="150">
                        @foreach ($events as $event)
                            <!-- Compact Event Card -->
                            <div
                                class="bg-white rounded-xl shadow-sm overflow-hidden transition-all duration-300 hover:shadow-md border border-gray-200 hover:border-primary/20 group">
                                <div class="relative">
                                    <img src="{{ asset('storage/' . $event->program_cover) }}" alt="{{ $event->title }}"
                                        class="w-full h-40 object-cover group-hover:scale-105 transition-transform duration-500">
                                </div>

                                <div class="p-4">
                                    <h3
                                        class="text-base font-bold text-primary mb-2 line-clamp-2 font-montserrat group-hover:text-secondary transition-colors">
                                        <a href="{{ route('events.show', $event->slug) }}">{{ $event->title }}</a>
                                    </h3>

                                    <p
                                        class="font-bold text-accent mb-3 line-clamp-2 text-xs leading-relaxed font-montserrat">
                                        {{ $event->theme }}</p>

                                    <div class="space-y-2 mb-3">
                                        <div class="flex items-center gap-2 text-gray-600 text-xs font-lato">
                                            <span class="font-bold text-secondary text-xs">Start Date:</span>
                                            <span>{{ Carbon::parse($event->start_date)->format('F j M Y') }}</span>
                                        </div>
                                        <div class="flex items-center gap-2 text-gray-600 text-xs font-lato">
                                            <span class="font-bold text-secondary text-xs">End Date:</span>
                                            <span>{{ Carbon::parse($event->end_date)->format('F j M Y') }}</span>
                                        </div>
                                        <div class="flex items-center gap-2 text-gray-600 text-xs font-lato">
                                            <span class=" text-secondary text-xs font-bold">Mode:</span>
                                            <span class="capitalize">{{ $event->mode ?? 'TBA' }}</span>
                                        </div>
                                        <div class="flex items-center gap-2 text-gray-600 text-xs font-lato">
                                            <span class=" text-secondary text-xs font-bold">Slots Remaining:</span>
                                            <span class="capitalize">{{ $event->slotsRemaining() }}</span>
                                        </div>

                                        @if (method_exists($event, 'isRegistered') && $event->isRegistered())
                                            @if ($event->mode == 'hybrid')
                                                <div class="flex items-center gap-2 text-gray-600 text-xs font-lato">
                                                    @if (Carbon::parse($event->start_date)->isNowOrPast())
                                                        <i class="fas fa-link text-secondary text-xs"></i>
                                                        <span class="capitalize">
                                                            <a href="{{ $event->location }}" target="_blank"
                                                                class="hover:underline">{{ $event->location }}</a>
                                                        </span>
                                                    @endif
                                                </div>
                                                <div class="flex items-center gap-2 text-gray-600 text-xs font-lato">
                                                    <i class="fas fa-location-arrow text-secondary text-xs"></i>
                                                    <span class="capitalize">{{ $event->physical_address }}</span>
                                                </div>
                                            @elseif($event->mode == 'offline')
                                                <div class="flex items-center gap-2 text-gray-600 text-xs font-lato">
                                                    <i class="fas fa-location-arrow text-secondary text-xs"></i>
                                                    <span class="capitalize">{{ $event->physical_address }}</span>
                                                </div>
                                            @elseif($event->mode == 'online')
                                                <div class="flex items-center gap-2 text-gray-600 text-xs font-lato">
                                                    @if (Carbon::parse($event->start_date)->isNowOrPast())
                                                        <i class="fas fa-link text-secondary text-xs"></i>
                                                        <span class="capitalize">
                                                            <a href="{{ $event->location }}" target="_blank"
                                                                class="hover:underline">{{ $event->location }}</a>
                                                        </span>
                                                    @endif
                                                </div>
                                            @endif
                                        @endif
                                    </div>

                                    <div class="flex items-center justify-between pt-3 border-t border-gray-100">
                                        @if ($event->entry_fee > 0)
                                            <span
                                                class="text-base font-bold text-accent font-montserrat">₦{{ number_format($event->entry_fee, 2) }}</span>
                                        @else
                                            <span class="text-base font-bold text-secondary font-montserrat">Free</span>
                                        @endif
                                        <a href="{{ route('events.show', $event->slug) }}"
                                            class="bg-primary hover:bg-secondary text-white px-3 py-1.5 rounded text-xs font-semibold transition-colors font-montserrat">
                                            View Details
                                        </a>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @else
                    <div class="text-center py-10" data-aos="fade-up">
                        <div class="bg-white rounded-xl shadow-sm p-8 max-w-md mx-auto border border-gray-100">
                            <i class="fas fa-calendar-times text-5xl text-gray-300 mb-4"></i>
                            <h3 class="text-xl font-bold text-gray-700 mb-3 font-montserrat">No Events Available</h3>
                            <p class="text-gray-600 mb-4 font-lato">We're preparing new transformative gatherings. Check
                                back soon!</p>
                        </div>
                    </div>
                @endif

                <!-- Compact CTA Section -->
                <section class="py-12 bg-gradient-to-r from-primary to-secondary text-white">
                    <div class="container mx-auto px-4">
                        <div class="max-w-3xl mx-auto text-center">
                            <h2 class="text-2xl md:text-3xl font-bold mb-4 font-montserrat">Never Miss a
                                Transformational
                                Event
                            </h2>
                            <p class="text-base text-white/90 mb-6 font-lato leading-relaxed">Subscribe to our
                                newsletter and
                                be the first to know about upcoming programs, prophetic workshops, and leadership
                                gatherings.</p>
                            <div class="flex flex-col sm:flex-row gap-3 max-w-md mx-auto">
                                <input type="email" placeholder="Enter your email"
                                    class="flex-1 px-3 py-2 rounded text-gray-800 focus:outline-none focus:ring-2 focus:ring-accent font-lato text-sm">
                                <button
                                    class="bg-accent hover:bg-white text-white hover:text-primary px-4 py-2 rounded font-semibold transition-all duration-300 shadow-sm hover:shadow font-montserrat whitespace-nowrap text-sm">
                                    Subscribe Now
                                </button>
                            </div>
                            <p class="text-white/70 text-xs mt-3 font-lato">Join thousands of leaders staying informed
                                about
                                kingdom events</p>
                        </div>
                    </div>
                </section>

    </x-guest-layout>
