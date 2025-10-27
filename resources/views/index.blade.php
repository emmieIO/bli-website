@php
    use Carbon\Carbon;
@endphp
<x-guest-layout>
    <!-- Banner Section -->
    <section class="flex relative items-center justify-center h-[80vh] mb-20 bg-cover bg-center rounded-lg"
        style="background-image: url({{ asset('images/learning-platform/banner.png') }}); 
        background-position: center 10px;
        background-repeat:no-repeat;"
        data-aos="fade-down" data-aos-duration="900" data-aos-once="true">
        <div class="absolute rounded-lg inset-0 bg-gradient-to-r from-primary/70 via-primary/60 to-primary/40"></div>
        <div class="relative z-10 w-full max-w-3xl px-6 text-center rounded-lg" data-aos="fade-up" data-aos-delay="200">
            <h6 class="text-md text-primary font-[800] font-montserrat mb-6 tracking-wide uppercase">
                Empowering Leaders for Influence & Impact
            </h6>
            <h1
                class="font-extrabold font-montserrat text-white text-3xl md:text-4xl mb-8 leading-tight drop-shadow-lg">
                Developing visionary leaders to drive positive change in organizations and communities.
            </h1>
            <form class="flex flex-col md:flex-row items-center gap-4 md:gap-6 justify-center w-full mt-6"
                data-aos="fade-up" data-aos-delay="400">
                <div class="relative w-full md:w-56">
                    <button type="button" id="categoriesBtn"
                        class="flex items-center gap-2 w-full bg-white/90 px-4 py-3 rounded-lg shadow hover:bg-orange-50 transition font-medium text-gray-800 justify-center">
                        <i class="fas fa-bars text-xl"></i>
                        <span>Categories</span>
                        <i class="fas fa-chevron-down ml-2 text-sm"></i>
                    </button>
                    <div id="categoriesPopup"
                        class="absolute left-0 top-full mt-2 w-68 bg-white shadow-xl rounded-lg z-50 overflow-hidden hidden">
                        <div class="p-4 border-b">
                            <h6 class="text-base font-semibold mb-1">Course Categories</h6>
                            <p class="text-xs text-gray-500">Pick a subject and start learning</p>
                        </div>
                        <ul class="p-2 space-y-2">
                            @if ($categories->count())
                                @foreach ($categories as $category)
                                    <li data-aos="fade-up" data-aos-delay="{{ $loop->index * 100 }}">
                                        <a href="courses-list.html"
                                            class="flex items-center gap-3 p-3 rounded-lg hover:bg-blue-100 transition">
                                            <img alt="{{ $category->name }} icon"
                                                src="{{ asset('storage/' . $category->image) }}" class="w-8 h-8">
                                            <span class="font-medium">{{ Str::ucfirst($category->name) }}</span>
                                            <span
                                                class="ml-auto text-xs text-gray-800 whitespace-nowrap">{{ $category->courses->count() }}
                                                Courses</span>
                                        </a>
                                    </li>
                                @endforeach
                            @endif
                        </ul>
                    </div>
                </div>
                <div class="relative flex-grow w-full max-w-md">
                    <input type="text" placeholder="What do you want to learn?" aria-label="Search courses"
                        class="w-full px-5 py-3 pl-12 rounded-lg border border-gray-300 focus:outline-none focus:ring-2 focus:ring-orange-500 transition-all duration-300 bg-white/90 text-gray-800 shadow">
                </div>
                <button type="submit"
                    class="bg-primary hover:bg-secondary/90 text-white font-semibold px-6 py-3 rounded-lg shadow transition-all duration-300 w-full md:w-auto">
                    Search
                </button>
            </form>
        </div>
    </section>

    <!-- CTA: View All Categories Section -->
    <section class="mb-20" data-aos="fade-up" data-aos-duration="900" data-aos-once="true">
        <div class="container mx-auto px-4 flex flex-col items-center justify-center">
            <h2 class="font-bold text-4xl text-center mb-6 font-montserrat text-gray-800" data-aos="fade-up">
                Explore All Course Categories
            </h2>
            <p class="text-lg text-gray-600 text-center mb-12 max-w-2xl leading-relaxed" data-aos="fade-up"
                data-aos-delay="150">
                Discover a wide range of categories to find the perfect course for your learning journey and
                professional development.
            </p>

            <div class="w-full">
                <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-5 gap-6 mb-12">
                    @if ($categories->count())
                        @foreach ($categories as $category)
                            <a href="courses-list.html"
                                class="group bg-white rounded-xl shadow-sm p-6 text-center transition-all duration-300 hover:shadow-lg hover:transform hover:-translate-y-2 border border-gray-100"
                                data-aos="zoom-in" data-aos-delay="{{ $loop->index * 100 }}">
                                <div
                                    class="mb-4 p-3 bg-gray-50 rounded-lg inline-block group-hover:bg-primary/5 transition-colors duration-300">
                                    <img src="{{ asset('storage/' . $category->image) }}"
                                        alt="{{ $category->name }} icon" class="mx-auto w-14 h-14 object-contain">
                                </div>
                                <h5
                                    class="font-bold text-lg mb-3 text-gray-800 group-hover:text-primary transition-colors duration-300">
                                    {{ Str::ucfirst($category->name) }}
                                </h5>
                                <p class="font-medium text-gray-500 text-sm">
                                    {{ $category->courses->count() }}
                                    Course{{ $category->courses->count() !== 1 ? 's' : '' }}
                                </p>
                            </a>
                        @endforeach
                    @else
                        <!-- Fallback content if no categories -->
                        <div class="col-span-full text-center py-12">
                            <p class="text-gray-500 text-lg">No categories available at the moment.</p>
                        </div>
                    @endif
                </div>

                <!-- Centered CTA Button -->
                <div class="flex justify-center" data-aos="fade-up" data-aos-delay="300">
                    <a href="#"
                        class="bg-primary hover:bg-primary-dark text-white font-bold py-4 px-12 rounded-lg shadow-md hover:shadow-lg transition-all duration-300 transform hover:-translate-y-1 text-base">
                        View All Categories
                    </a>
                </div>
            </div>
        </div>
    </section>

    <!-- What Makes BLI Stand Out Section -->
    <section class="py-20 bg-gray-50" data-aos="fade-up" data-aos-once="true">
        <div class=" mx-auto px-4">
            <h2 class="font-bold text-4xl text-center mb-16 font-montserrat text-primary">
                What Makes BLI Stand Out
            </h2>
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                <!-- Kingdom-Based Leadership -->
                <div class="text-center bg-white rounded-xl shadow-sm hover:shadow-md transition-all duration-300 p-6"
                    data-aos="fade-up" data-aos-delay="100">
                    <div class="mb-5 p-3 rounded-full bg-primary/10 inline-block">
                        <i class="fas fa-chess-king text-4xl text-primary"></i>
                    </div>
                    <h5 class="font-bold text-xl mb-4 font-montserrat text-primary">
                        Kingdom-Based Leadership
                    </h5>
                    <p class="text-gray-600 font-lato leading-relaxed">
                        Spiritually grounded, marketplace relevant. Thrive like Daniel, Joseph, and Esther—integrating
                        faith with bold, strategic leadership in business, politics, education, and ministry.
                    </p>
                </div>
                <!-- Transformational Mentorship -->
                <div class="text-center bg-white rounded-xl shadow-sm hover:shadow-md transition-all duration-300 p-6"
                    data-aos="fade-up" data-aos-delay="200">
                    <div class="mb-5 p-3 rounded-full bg-secondary/10 inline-block">
                        <i class="fas fa-user-friends text-4xl text-secondary"></i>
                    </div>
                    <h5 class="font-bold text-xl mb-4 font-montserrat text-primary">
                        Transformational Mentorship
                    </h5>
                    <p class="text-gray-600 font-lato leading-relaxed">
                        Students matched with seasoned mentors for growth checkpoints, leadership challenges, and
                        personal formation. Virtual coaching pods and check-ins ensure students flourish.
                    </p>
                </div>
                <!-- Problem-Solving Curriculum -->
                <div class="text-center bg-white rounded-xl shadow-sm hover:shadow-md transition-all duration-300 p-6"
                    data-aos="fade-up" data-aos-delay="300">
                    <div class="mb-5 p-3 rounded-full bg-accent/10 inline-block">
                        <i class="fas fa-tools text-4xl text-accent"></i>
                    </div>
                    <h5 class="font-bold text-xl mb-4 font-montserrat text-primary">
                        Problem-Solving Curriculum
                    </h5>
                    <ul class="text-gray-600 font-lato text-left mx-auto max-w-xs list-none space-y-2">
                        <li class="flex items-start">
                            <span class="text-primary mr-2 mt-1">•</span>
                            <span>Leading under pressure</span>
                        </li>
                        <li class="flex items-start">
                            <span class="text-primary mr-2 mt-1">•</span>
                            <span>Navigating toxic environments</span>
                        </li>
                        <li class="flex items-start">
                            <span class="text-primary mr-2 mt-1">•</span>
                            <span>Strategic planning with prophetic insight</span>
                        </li>
                        <li class="flex items-start">
                            <span class="text-primary mr-2 mt-1">•</span>
                            <span>Rebuilding broken systems</span>
                        </li>
                        <li class="flex items-start">
                            <span class="text-primary mr-2 mt-1">•</span>
                            <span>Sustaining influence with integrity</span>
                        </li>
                    </ul>
                </div>
                <!-- Prophetic Leadership Integration -->
                <div class="text-center bg-white rounded-xl shadow-sm hover:shadow-md transition-all duration-300 p-6"
                    data-aos="fade-up" data-aos-delay="400">
                    <div class="mb-5 p-3 rounded-full bg-primary/10 inline-block">
                        <i class="fas fa-eye text-4xl text-primary"></i>
                    </div>
                    <h5 class="font-bold text-xl mb-4 font-montserrat text-primary">
                        Prophetic Leadership
                    </h5>
                    <p class="text-gray-600 font-lato leading-relaxed">
                        Integrates prophetic insight with leadership training—sharpening discernment, interpreting
                        divine seasons, and leading with Spirit-led accuracy.
                    </p>
                </div>
                <!-- Global Vision, Local Expression -->
                <div class="text-center bg-white rounded-xl shadow-sm hover:shadow-md transition-all duration-300 p-6"
                    data-aos="fade-up" data-aos-delay="500">
                    <div class="mb-5 p-3 rounded-full bg-secondary/10 inline-block">
                        <i class="fas fa-globe text-4xl text-secondary"></i>
                    </div>
                    <h5 class="font-bold text-xl mb-4 font-montserrat text-primary">
                        Global Vision, Local Expression
                    </h5>
                    <p class="text-gray-600 font-lato leading-relaxed">
                        Hybrid learning (virtual + in-person), summits, bootcamps, and global collaborations amplify
                        graduates' influence worldwide.
                    </p>
                </div>
            </div>
        </div>
    </section>

    <!-- Upcoming Events Section -->
    <section class="my-20" data-aos="fade-right" data-aos-duration="900" data-aos-once="true">
        <div class="container mx-auto px-4">
            <div class="flex flex-wrap items-center justify-between mb-12">
                <div class="mb-5 md:mb-0">
                    <h2 class="font-bold text-4xl font-montserrat text-primary">
                        Featured Events
                    </h2>
                    <p class="text-gray-600 mt-2 font-lato">
                        Join our transformative events and leadership gatherings
                    </p>
                </div>
                <a href="{{ route('events.index') }}"
                    class="group font-semibold text-secondary hover:text-primary transition-all duration-300 font-montserrat flex items-center gap-2">
                    View all Events
                    <i class="fas fa-arrow-right group-hover:translate-x-1 transition-transform duration-300"></i>
                </a>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
                @foreach ($events as $event)
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

                            <p class="font-bold text-accent mb-3 line-clamp-2 text-xs leading-relaxed font-montserrat">
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

                            <div class="flex  items-center justify-between pt-3 border-t border-gray-100">
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

                @if ($events->count() === 0)
                    <div class="col-span-full text-center py-16">
                        <div class="max-w-md mx-auto">
                            <i class="fas fa-calendar-day text-6xl text-gray-300 mb-4"></i>
                            <h3 class="font-bold text-xl text-gray-500 mb-2 font-montserrat">No Upcoming Events</h3>
                            <p class="text-gray-400 font-lato">Check back later for new events and leadership
                                gatherings.</p>
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </section>

    <!-- Partners Section -->
    <section class="py-20 bg-gray-50" data-aos="fade-up" data-aos-duration="900" data-aos-once="true">
        <div class="container mx-auto px-4">
            <div class="text-center mb-16" data-aos="fade-up">
                <h2 class="font-bold text-4xl text-center mb-4 font-montserrat text-primary">
                    Trusted by Leading Organizations
                </h2>
                <p class="text-lg text-gray-600 max-w-2xl mx-auto font-lato leading-relaxed">
                    Join over 1,300 organizations worldwide who trust BLI for leadership development and transformation
                </p>
            </div>

            {{-- <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-6 gap-8 justify-items-center items-center">
                @foreach (range(1, 6) as $i)
                    <div class="group" data-aos="fade-in" data-aos-delay="{{ $i * 100 }}">
                        <div
                            class="bg-white rounded-xl p-6 shadow-sm transition-all duration-300 hover:shadow-md hover:transform hover:-translate-y-1 border border-gray-100 h-auto flex items-center justify-center">
                            <img src="{{ asset('images/logo.jpg') }}"
                                alt="Partner organization {{ $i }}"
                                class="mx-auto opacity-70 group-hover:opacity-100 transition-opacity duration-300 grayscale group-hover:grayscale-0 h-auto object-contain">
                        </div>
                    </div>
                @endforeach
            </div> --}}

            <!-- Stats Section -->
            <div class="mt-16 pt-12 border-t border-gray-200" data-aos="fade-up" data-aos-delay="300">
                <div class="grid grid-cols-1 md:grid-cols-3 gap-8 max-w-4xl mx-auto">
                    <div class="text-center">
                        <div class="text-4xl font-bold text-primary mb-2 font-montserrat">1,300+</div>
                        <div class="text-gray-600 font-lato">Organizations</div>
                    </div>
                    <div class="text-center">
                        <div class="text-4xl font-bold text-secondary mb-2 font-montserrat">50+</div>
                        <div class="text-gray-600 font-lato">Countries</div>
                    </div>
                    <div class="text-center">
                        <div class="text-4xl font-bold text-accent mb-2 font-montserrat">15+</div>
                        <div class="text-gray-600 font-lato">Years of Excellence</div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Reviews Section -->
    <section class="py-20 bg-gray-50" data-aos="zoom-in-up" data-aos-duration="900" data-aos-once="true">
        <div class="container mx-auto px-4">
            <div class="text-center mb-16" data-aos="fade-up">
                <h2 class="font-bold text-4xl text-center mb-4 font-montserrat text-primary">
                    Why Students Love BLI
                </h2>
                <p class="text-lg text-gray-600 max-w-2xl mx-auto font-lato">
                    Hear from our community of transformational leaders
                </p>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8 max-w-6xl mx-auto">
                <!-- Main Review Card -->
                <div class="lg:col-span-2 bg-white rounded-2xl shadow-sm p-8 relative" data-aos="fade-up"
                    data-aos-delay="200">
                    <div
                        class="absolute -top-4 -left-4 w-8 h-8 bg-secondary rounded-full flex items-center justify-center">
                        <i class="fas fa-quote-left text-white text-sm"></i>
                    </div>
                    <i class="fas fa-quote-left text-5xl text-secondary/20 mb-6 block"></i>
                    <p class="mb-6 text-gray-700 text-lg leading-relaxed font-lato">"The transformational mentorship at
                        BLI completely reshaped my leadership approach. I've learned to integrate spiritual wisdom with
                        practical business strategy in ways I never thought possible. The kingdom-based framework gave
                        me tools to lead with integrity and purpose."</p>
                    <div class="flex items-center gap-4 pt-6 border-t border-gray-100">
                        <img src="{{ asset('images/learning-platform/reviews-01.png') }}" alt="DeVeor R"
                            class="w-14 h-14 rounded-full border-2 border-secondary">
                        <div>
                            <h6 class="font-bold text-lg font-montserrat text-primary">DeVeor R.</h6>
                            <p class="text-gray-600 font-lato">Business Leadership Track</p>
                            <div class="flex items-center gap-1 mt-1">
                                @foreach (range(1, 5) as $star)
                                    <i class="fas fa-star text-yellow-400 text-sm"></i>
                                @endforeach
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Side Review Cards -->
                <div class="space-y-6 ">
                    <!-- Review 1 -->
                    <div class="bg-white rounded-xl shadow-sm p-6 relative" data-aos="fade-up" data-aos-delay="300">
                        <i class="fas fa-quote-left text-xl text-accent/30 mb-3 block"></i>
                        <p class="mb-4 text-gray-600 text-sm leading-relaxed font-lato">"The prophetic leadership
                            integration helped me discern God's direction for our organization. I'm now leading with
                            confidence and clarity."</p>
                        <div class="flex items-center gap-3">
                            <img src="{{ asset('images/learning-platform/reviews-02.png') }}" alt="Sarah M"
                                class="w-10 h-10 rounded-full">
                            <div>
                                <h6 class="font-semibold text-sm font-montserrat text-primary">Sarah M.</h6>
                                <p class="text-gray-500 text-xs font-lato">Ministry Leadership</p>
                            </div>
                        </div>
                    </div>

                    <!-- Review 2 -->
                    <div class="bg-white rounded-xl shadow-sm p-6 relative" data-aos="fade-up" data-aos-delay="400">
                        <i class="fas fa-quote-left text-xl text-secondary/30 mb-3 block"></i>
                        <p class="mb-4 text-gray-600 text-sm leading-relaxed font-lato">"The problem-solving curriculum
                            equipped me to navigate complex challenges in education. I'm rebuilding systems with kingdom
                            principles."</p>
                        <div class="flex items-center gap-3">
                            <img src="{{ asset('images/learning-platform/reviews-02.png') }}" alt="James K"
                                class="w-10 h-10 rounded-full">
                            <div>
                                <h6 class="font-semibold text-sm font-montserrat text-primary">James K.</h6>
                                <p class="text-gray-500 text-xs font-lato">Education Track</p>
                            </div>
                        </div>
                    </div>

                    <!-- CTA Card -->
                    <div class="bg-gradient-to-r from-primary to-secondary rounded-xl p-6 text-white"
                        data-aos="fade-up" data-aos-delay="500">
                        <h6 class="font-bold text-lg mb-2 font-montserrat">Ready to Transform Your Leadership?</h6>
                        <p class="text-white/90 text-sm mb-4 font-lato">Join thousands of leaders experiencing
                            transformation</p>
                        <a href="{{ route('register') }}"
                            class="inline-flex items-center gap-2 bg-white text-primary font-semibold px-4 py-2 rounded-lg text-sm hover:bg-gray-100 transition-colors duration-300 font-montserrat">
                            Start Your Journey
                            <i class="fas fa-arrow-right text-xs"></i>
                        </a>
                    </div>
                </div>
            </div>

            <!-- Stats Row -->
            <div class="grid grid-cols-2 md:grid-cols-4 gap-6 max-w-4xl mx-auto mt-16" data-aos="fade-up"
                data-aos-delay="600">
                <div class="text-center">
                    <div class="text-3xl font-bold text-primary mb-1 font-montserrat">98%</div>
                    <div class="text-gray-600 text-sm font-lato">Satisfaction Rate</div>
                </div>
                <div class="text-center">
                    <div class="text-3xl font-bold text-secondary mb-1 font-montserrat">4.9/5</div>
                    <div class="text-gray-600 text-sm font-lato">Average Rating</div>
                </div>
                <div class="text-center">
                    <div class="text-3xl font-bold text-accent mb-1 font-montserrat">2K+</div>
                    <div class="text-gray-600 text-sm font-lato">Leaders Trained</div>
                </div>
                <div class="text-center">
                    <div class="text-3xl font-bold text-primary mb-1 font-montserrat">50+</div>
                    <div class="text-gray-600 text-sm font-lato">Countries Reached</div>
                </div>
            </div>
        </div>
    </section>

    <!-- Learning Section -->
    <section class="bg-gradient-to-br from-white to-gray-50 py-20" data-aos="fade-up" data-aos-once="true">
        <div class="container mx-auto px-4">
            <div class="flex flex-col lg:flex-row items-center gap-12">
                <!-- Content Section -->
                <div class="lg:w-1/2" data-aos="fade-right" data-aos-delay="200">
                    <div class="max-w-lg">
                        <h2 class="font-bold text-4xl lg:text-5xl mb-6 font-montserrat text-primary leading-tight">
                            Transformational Learning<br>
                            <span class="text-secondary">At Your Fingertips</span>
                        </h2>
                        <p class="text-lg text-gray-600 mb-8 font-lato leading-relaxed">
                            Access world-class leadership training anytime, anywhere. BLI's hybrid learning platform
                            combines spiritual depth with practical leadership skills to equip you for impact in every
                            sphere of society.
                        </p>

                        <!-- Features List -->
                        <div class="space-y-4 mb-8">
                            <div class="flex items-center gap-3">
                                <div
                                    class="w-6 h-6 bg-accent rounded-full flex items-center justify-center flex-shrink-0">
                                    <i class="fas fa-check text-white text-xs"></i>
                                </div>
                                <span class="text-gray-700 font-lato">Flexible online courses with in-person
                                    intensives</span>
                            </div>
                            <div class="flex items-center gap-3">
                                <div
                                    class="w-6 h-6 bg-accent rounded-full flex items-center justify-center flex-shrink-0">
                                    <i class="fas fa-check text-white text-xs"></i>
                                </div>
                                <span class="text-gray-700 font-lato">Prophetic insight integrated with leadership
                                    training</span>
                            </div>
                            <div class="flex items-center gap-3">
                                <div
                                    class="w-6 h-6 bg-accent rounded-full flex items-center justify-center flex-shrink-0">
                                    <i class="fas fa-check text-white text-xs"></i>
                                </div>
                                <span class="text-gray-700 font-lato">Global community of kingdom-minded leaders</span>
                            </div>
                        </div>

                        <!-- CTA Buttons -->
                        <div class="flex flex-col sm:flex-row gap-4">
                            <a href="#"
                                class="bg-secondary hover:bg-primary text-white font-bold py-4 px-8 rounded-lg shadow-md hover:shadow-lg transition-all duration-300 transform hover:-translate-y-1 text-center font-montserrat">
                                Start Learning Today
                            </a>
                            <a href="#"
                                class="border-2 border-primary text-primary hover:bg-primary hover:text-white font-bold py-4 px-8 rounded-lg transition-all duration-300 text-center font-montserrat">
                                Explore Courses
                            </a>
                        </div>
                    </div>
                </div>

                <!-- Image/Visual Section -->
                <div class="lg:w-1/2" data-aos="fade-left" data-aos-delay="300">
                    <div class="relative">
                        <!-- Main Image -->
                        <div
                            class="bg-white rounded-2xl shadow-xl p-6 transform rotate-2 hover:rotate-0 transition-transform duration-500">
                            <img src="{{ asset('images/logo.jpg') }}" alt="BLI Learning Platform"
                                class="rounded-xl w-full h-auto shadow-md">
                        </div>

                        <!-- Floating Elements -->
                        <div class="absolute -top-4 -left-4 bg-accent text-white p-4 rounded-xl shadow-lg"
                            data-aos="zoom-in" data-aos-delay="500">
                            <div class="text-center">
                                <div class="font-bold text-2xl font-montserrat">2K+</div>
                                <div class="text-sm font-lato">Leaders Trained</div>
                            </div>
                        </div>

                        <div class="absolute -bottom-4 -right-4 bg-primary text-white p-4 rounded-xl shadow-lg"
                            data-aos="zoom-in" data-aos-delay="700">
                            <div class="text-center">
                                <div class="font-bold text-2xl font-montserrat">98%</div>
                                <div class="text-sm font-lato">Success Rate</div>
                            </div>
                        </div>

                        <!-- Background Decoration -->
                        <div
                            class="absolute -z-10 top-1/2 left-1/2 transform -translate-x-1/2 -translate-y-1/2 w-64 h-64 bg-secondary/10 rounded-full blur-3xl">
                        </div>
                    </div>
                </div>
            </div>

            <!-- Bottom Stats -->
            <div class="grid grid-cols-2 md:grid-cols-4 gap-6 mt-20 pt-12 border-t border-gray-200" data-aos="fade-up"
                data-aos-delay="400">
                <div class="text-center">
                    <div class="text-3xl font-bold text-primary mb-2 font-montserrat">24/7</div>
                    <div class="text-gray-600 font-lato">Course Access</div>
                </div>
                <div class="text-center">
                    <div class="text-3xl font-bold text-secondary mb-2 font-montserrat">50+</div>
                    <div class="text-gray-600 font-lato">Countries</div>
                </div>
                <div class="text-center">
                    <div class="text-3xl font-bold text-accent mb-2 font-montserrat">15+</div>
                    <div class="text-gray-600 font-lato">Years Experience</div>
                </div>
                <div class="text-center">
                    <div class="text-3xl font-bold text-primary mb-2 font-montserrat">100%</div>
                    <div class="text-gray-600 font-lato">Kingdom Focused</div>
                </div>
            </div>
        </div>
    </section>
</x-guest-layout>
