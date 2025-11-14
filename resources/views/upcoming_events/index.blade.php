    @php
        use Carbon\Carbon;
    @endphp
    <x-guest-layout>
        <!-- Enhanced Hero Section -->
        <section id="upcoming-events" class="py-16 md:py-20 bg-gradient-to-br from-white to-gray-50" data-aos="fade-down" data-aos-duration="900">
            <div class="container mx-auto px-6 text-center">
                <!-- Badge -->
                <div class="inline-flex items-center gap-2 border rounded-full px-6 py-3 mb-8" 
                     style="background-color: rgba(0, 166, 81, 0.1); border-color: #00a651;">
                    <i class="fas fa-calendar-alt text-sm" style="color: #00a651;"></i>
                    <span class="font-medium font-montserrat text-sm tracking-wide" style="color: #002147;">
                        Transformational Gatherings
                    </span>
                </div>

                <!-- Main Heading -->
                <h1 class="font-bold font-montserrat text-4xl md:text-5xl lg:text-6xl mb-6 leading-tight" style="color: #002147;">
                    Upcoming 
                    <span style="color: #00a651;">Events</span>
                </h1>
                
                <p class="text-xl text-gray-600 mb-12 max-w-3xl mx-auto leading-relaxed font-lato">
                    Join our transformational programs, prophetic workshops, and kingdom-focused gatherings designed to equip and empower leaders.
                </p>

                <!-- Enhanced Search -->
                <form action="{{ route('events.index') }}" method="GET" class="max-w-2xl mx-auto mb-12">
                    <div class="bg-white rounded-2xl shadow-lg p-6 border border-gray-100">
                        <div class="flex flex-col md:flex-row gap-4">
                            <div class="flex-1 relative">
                                <i class="fas fa-search absolute left-4 top-1/2 transform -translate-y-1/2 text-gray-400"></i>
                                <input id="q" name="q" type="search" value="{{ request('q') }}"
                                    placeholder="Search events, topics, or locations..."
                                    class="w-full pl-12 pr-4 py-3 rounded-xl border border-gray-200 focus:outline-none focus:ring-2 focus:border-transparent font-lato"
                                    style="focus:ring-color: #00a651;">
                            </div>
                            <button type="submit"
                                class="px-8 py-3 rounded-xl font-semibold transition-all duration-300 shadow-md hover:shadow-lg transform hover:scale-105 text-white font-montserrat"
                                style="background-color: #00a651;"
                                onmouseover="this.style.backgroundColor='#15803d'"
                                onmouseout="this.style.backgroundColor='#00a651'">
                                <i class="fas fa-search mr-2"></i>
                                Search
                            </button>
                        </div>
                    </div>
                </form>

                <!-- Enhanced Stats -->
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6 max-w-4xl mx-auto" data-aos="fade-up" data-aos-delay="200">
                    <div class="bg-white rounded-2xl shadow-lg border border-gray-100 p-6 hover:shadow-xl transition-all duration-300">
                        <div class="flex items-center justify-center gap-3 mb-4">
                            <div class="w-12 h-12 rounded-2xl flex items-center justify-center" style="background-color: rgba(237, 28, 36, 0.1);">
                                <i class="fas fa-broadcast-tower text-xl" style="color: #ed1c24;"></i>
                            </div>
                        </div>
                        <div class="font-bold text-3xl mb-2 font-montserrat" style="color: #ed1c24;">{{ $ongoingEvents }}</div>
                        <div class="text-gray-600 font-lato font-semibold">Happening Now</div>
                    </div>

                    <div class="bg-white rounded-2xl shadow-lg border border-gray-100 p-6 hover:shadow-xl transition-all duration-300">
                        <div class="flex items-center justify-center gap-3 mb-4">
                            <div class="w-12 h-12 rounded-2xl flex items-center justify-center" style="background-color: rgba(0, 166, 81, 0.1);">
                                <i class="fas fa-calendar-alt text-xl" style="color: #00a651;"></i>
                            </div>
                        </div>
                        <div class="font-bold text-3xl mb-2 font-montserrat" style="color: #00a651;">{{ $upcomingEvents }}</div>
                        <div class="text-gray-600 font-lato font-semibold">Coming Soon</div>
                    </div>

                    <div class="bg-white rounded-2xl shadow-lg border border-gray-100 p-6 hover:shadow-xl transition-all duration-300">
                        <div class="flex items-center justify-center gap-3 mb-4">
                            <div class="w-12 h-12 rounded-2xl flex items-center justify-center" style="background-color: rgba(0, 33, 71, 0.1);">
                                <i class="fas fa-history text-xl" style="color: #002147;"></i>
                            </div>
                        </div>
                        <div class="font-bold text-3xl mb-2 font-montserrat" style="color: #002147;">{{ $expiredEvents }}</div>
                        <div class="text-gray-600 font-lato font-semibold">Past Events</div>
                    </div>
                </div>
            </div>
        </section>

        <!-- Enhanced Events Section -->
        <section class="py-20 bg-white" id="events-grid">
            <div class="container mx-auto px-6">
                @if ($events->count())
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                        @foreach ($events as $event)
                            <div class="group bg-white rounded-2xl shadow-lg hover:shadow-2xl transition-all duration-500 overflow-hidden border border-gray-100 hover:border-accent/20" 
                                 data-aos="fade-up" data-aos-delay="{{ $loop->index * 100 }}">
                                
                                <!-- Event Image -->
                                <div class="relative h-48 overflow-hidden">
                                    <img src="{{ asset('storage/' . $event->program_cover) }}" 
                                         alt="{{ $event->title }}"
                                         class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-500">
                                    
                                    <!-- Event Status Badge -->
                                    @php
                                        $now = now();
                                        $startDate = Carbon::parse($event->start_date);
                                        $endDate = Carbon::parse($event->end_date);
                                        $isLive = $now->between($startDate, $endDate);
                                        $isUpcoming = $now->lt($startDate);
                                    @endphp
                                    
                                    <div class="absolute top-4 left-4">
                                        @if ($isLive)
                                            <span class="px-3 py-1.5 text-xs font-semibold rounded-full text-white font-montserrat flex items-center gap-2"
                                                  style="background-color: #ed1c24;">
                                                <span class="w-2 h-2 bg-white rounded-full animate-pulse"></span>
                                                Live Now
                                            </span>
                                        @elseif($isUpcoming)
                                            <span class="px-3 py-1.5 text-xs font-semibold rounded-full text-white font-montserrat"
                                                  style="background-color: #00a651;">
                                                Coming Soon
                                            </span>
                                        @else
                                            <span class="px-3 py-1.5 text-xs font-semibold rounded-full bg-gray-600 text-white font-montserrat">
                                                Past Event
                                            </span>
                                        @endif
                                    </div>
                                    
                                    <!-- Price Badge -->
                                    <div class="absolute top-4 right-4">
                                        @if ($event->entry_fee > 0)
                                            <span class="px-3 py-1.5 text-sm font-bold rounded-full bg-white shadow-md font-montserrat"
                                                  style="color: #002147;">
                                                ₦{{ number_format($event->entry_fee, 0) }}
                                            </span>
                                        @else
                                            <span class="px-3 py-1.5 text-sm font-bold rounded-full bg-white shadow-md font-montserrat"
                                                  style="color: #ed1c24;">
                                                FREE
                                            </span>
                                        @endif
                                    </div>
                                </div>

                                <!-- Event Content -->
                                <div class="p-6">
                                    <!-- Event Title -->
                                    <h3 class="text-xl font-bold mb-3 font-montserrat line-clamp-2 group-hover:text-accent transition-colors duration-300" 
                                        style="color: #002147;">
                                        <a href="{{ route('events.show', $event->slug) }}">{{ $event->title }}</a>
                                    </h3>

                                    <!-- Event Theme -->
                                    <p class="font-semibold text-sm mb-4 line-clamp-2 font-montserrat" 
                                       style="color: #00a651;">
                                        {{ $event->theme }}
                                    </p>

                                    <!-- Event Details -->
                                    <div class="space-y-3 mb-6">
                                        <div class="flex items-center gap-3 text-sm text-gray-600">
                                            <i class="fas fa-calendar text-sm" style="color: #00a651;"></i>
                                            <span class="font-lato">
                                                {{ Carbon::parse($event->start_date)->format('M j, Y') }} 
                                                - {{ Carbon::parse($event->end_date)->format('M j, Y') }}
                                            </span>
                                        </div>
                                        
                                        <div class="flex items-center gap-3 text-sm text-gray-600">
                                            @if($event->mode == 'online')
                                                <i class="fas fa-globe text-sm" style="color: #00a651;"></i>
                                                <span class="font-lato capitalize">{{ $event->mode }}</span>
                                            @elseif($event->mode == 'offline')
                                                <i class="fas fa-map-marker-alt text-sm" style="color: #00a651;"></i>
                                                <span class="font-lato capitalize">{{ $event->mode }}</span>
                                            @else
                                                <i class="fas fa-laptop text-sm" style="color: #00a651;"></i>
                                                <span class="font-lato capitalize">{{ $event->mode ?? 'Hybrid' }}</span>
                                            @endif
                                        </div>

                                        <div class="flex items-center gap-3 text-sm text-gray-600">
                                            <i class="fas fa-users text-sm" style="color: #00a651;"></i>
                                            <span class="font-lato">{{ $event->slotsRemaining() }} slots remaining</span>
                                        </div>
                                    </div>

                                    <!-- Action Button -->
                                    <a href="{{ route('events.show', $event->slug) }}" 
                                       class="w-full flex items-center justify-center gap-2 font-semibold py-3 px-4 rounded-xl transition-all duration-300 shadow-md hover:shadow-lg transform hover:scale-105 text-white font-montserrat"
                                       style="background-color: #002147;"
                                       onmouseover="this.style.backgroundColor='#1e3a8a'"
                                       onmouseout="this.style.backgroundColor='#002147'">
                                        <span>View Details</span>
                                        <i class="fas fa-arrow-right text-sm group-hover:translate-x-1 transition-transform"></i>
                                    </a>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @else
                    <!-- Enhanced Empty State -->
                    <div class="text-center py-20" data-aos="fade-up">
                        <div class="max-w-md mx-auto">
                            <div class="mb-8">
                                <i class="fas fa-calendar-alt text-6xl text-gray-300"></i>
                            </div>
                            <h3 class="text-2xl font-bold mb-4 font-montserrat" style="color: #002147;">No Events Available</h3>
                            <p class="text-gray-600 font-lato mb-8 leading-relaxed">
                                We're preparing new transformative gatherings and kingdom-focused events. Check back soon for exciting opportunities to grow and connect!
                            </p>
                            <a href="{{ route('homepage') }}" 
                               class="inline-flex items-center gap-2 font-semibold px-6 py-3 rounded-xl transition-all duration-300 shadow-md hover:shadow-lg text-white font-montserrat"
                               style="background-color: #00a651;">
                                <i class="fas fa-home"></i>
                                <span>Back to Home</span>
                            </a>
                        </div>
                    </div>
                @endif
            </div>
        </section>

        <!-- Enhanced Newsletter CTA Section -->
        <section class="py-20" style="background: linear-gradient(135deg, #002147 0%, #003875 100%);">
            <div class="container mx-auto px-6">
                <div class="max-w-4xl mx-auto text-center text-white" data-aos="fade-up">
                    <!-- Section Icon -->
                    <div class="mb-8">
                        <div class="w-20 h-20 rounded-2xl mx-auto flex items-center justify-center" style="background-color: rgba(0, 166, 81, 0.2);">
                            <i class="fas fa-bell text-3xl" style="color: #00a651;"></i>
                        </div>
                    </div>

                    <h2 class="text-3xl md:text-4xl font-bold mb-6 font-montserrat">
                        Never Miss a Transformational Event
                    </h2>
                    <p class="text-xl text-white/90 mb-12 font-lato leading-relaxed max-w-3xl mx-auto">
                        Subscribe to our newsletter and be the first to know about upcoming programs, prophetic workshops, and kingdom leadership gatherings.
                    </p>

                    <!-- Newsletter Signup -->
                    <div class="bg-white/10 backdrop-blur-sm rounded-2xl p-8 border border-white/20 max-w-2xl mx-auto">
                        <form class="flex flex-col md:flex-row gap-4">
                            <div class="flex-1 relative">
                                <i class="fas fa-envelope absolute left-4 top-1/2 transform -translate-y-1/2 text-gray-400"></i>
                                <input type="email" placeholder="Enter your email address"
                                    class="w-full pl-12 pr-4 py-4 rounded-xl border border-white/30 bg-white text-gray-800 focus:outline-none focus:ring-2 focus:border-transparent font-lato"
                                    style="focus:ring-color: #00a651;">
                            </div>
                            <button type="submit"
                                class="px-8 py-4 rounded-xl font-semibold transition-all duration-300 shadow-lg hover:shadow-xl transform hover:scale-105 text-white font-montserrat whitespace-nowrap"
                                style="background-color: #00a651;"
                                onmouseover="this.style.backgroundColor='#15803d'"
                                onmouseout="this.style.backgroundColor='#00a651'">
                                <i class="fas fa-paper-plane mr-2"></i>
                                Subscribe Now
                            </button>
                        </form>

                        <div class="flex items-center justify-center gap-6 mt-8 text-sm text-white/80">
                            <div class="flex items-center gap-2">
                                <i class="fas fa-users text-sm" style="color: #00a651;"></i>
                                <span class="font-lato">12,000+ Leaders</span>
                            </div>
                            <div class="flex items-center gap-2">
                                <i class="fas fa-shield-alt text-sm" style="color: #00a651;"></i>
                                <span class="font-lato">No Spam Promise</span>
                            </div>
                            <div class="flex items-center gap-2">
                                <i class="fas fa-times text-sm" style="color: #00a651;"></i>
                                <span class="font-lato">Unsubscribe Anytime</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>

    </x-guest-layout>
