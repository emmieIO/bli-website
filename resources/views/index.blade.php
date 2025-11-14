@php
    use Carbon\Carbon;
@endphp
<x-guest-layout>
    <!-- Hero Section -->
    <section class="py-16 md:py-20 lg:py-24 bg-white" data-aos="fade-down" data-aos-duration="900" data-aos-once="true">

        <div class="container mx-auto px-6">
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-12 items-center">

                <!-- Text Content - Left Side -->
                <div class="order-2 lg:order-1" data-aos="fade-right" data-aos-delay="200">
                    <!-- Badge -->
                    <div class="inline-flex items-center gap-2 border rounded-full px-6 py-3 mb-8"
                        style="background-color: rgba(0, 166, 81, 0.1); border-color: #00a651;">
                        <div class="w-2 h-2 rounded-full animate-pulse" style="background-color: #00a651;"></div>
                        <span class="font-medium font-montserrat text-sm tracking-wide" style="color: #002147;">
                            Empowering Leaders for Kingdom Impact
                        </span>
                    </div>

                    <!-- Main Heading -->
                    <h1 class="font-bold font-montserrat text-3xl md:text-4xl lg:text-5xl mb-6 leading-tight"
                        style="color: #002147;">
                        Transform Your
                        <span style="color: #00a651;">
                            Leadership Journey
                        </span>
                    </h1>

                    <p class="text-lg md:text-xl text-gray-600 mb-8 leading-relaxed font-lato">
                        Developing visionary leaders to drive positive change in organizations and communities through
                        kingdom-based principles.
                    </p>

                    <!-- CTA Buttons -->
                    <div class="flex flex-col sm:flex-row gap-4">
                        <a href="{{ route('register') }}"
                            class="group font-semibold py-3 px-6 rounded-lg shadow-lg hover:shadow-xl transition-all duration-300 transform hover:scale-105 font-montserrat flex items-center justify-center gap-2 text-sm text-white"
                            style="background-color: #00a651;" onmouseover="this.style.backgroundColor='#15803d'"
                            onmouseout="this.style.backgroundColor='#00a651'">
                            <span>Start Your Journey</span>
                            <i class="fas fa-arrow-right group-hover:translate-x-1 transition-transform text-sm"></i>
                        </a>
                        <a href="{{ route('events.index') }}"
                            class="group border-2 font-semibold py-3 px-6 rounded-lg transition-all duration-300 font-montserrat flex items-center justify-center gap-2 text-sm hover:bg-gray-50"
                            style="color: #002147; border-color: #002147;">
                            <span>Explore Events</span>
                            <i class="fas fa-calendar group-hover:scale-110 transition-transform text-sm"></i>
                        </a>
                    </div>
                </div>

                <!-- Image - Right Side -->
                <div class="order-1 lg:order-2" data-aos="fade-left" data-aos-delay="400">
                    <div class="relative">
                        <img src="{{ asset('images/learning-platform/banner.png') }}" alt="BLI Leadership Training"
                            class="w-full h-auto rounded-2xl shadow-2xl">

                        <!-- Optional decorative elements -->
                        <div class="absolute -top-4 -left-4 w-20 h-20 rounded-full opacity-20"
                            style="background-color: #00a651;"></div>
                        <div class="absolute -bottom-4 -right-4 w-16 h-16 rounded-full opacity-20"
                            style="background-color: #ed1c24;"></div>
                    </div>
                </div>

            </div>
        </div>
    </section>

    <!-- Trust Indicators -->
    <section class="py-12 bg-white border-b border-gray-100">
        <div class="container mx-auto px-4">
            <div class="flex flex-col md:flex-row items-center justify-center gap-8 md:gap-16 text-center">
                <div class="flex items-center gap-3">
                    <div class="text-2xl font-bold font-montserrat" style="color: #002147;">1,300+</div>
                    <div class="text-gray-600 font-lato">Organizations Trust BLI</div>
                </div>
                <div class="flex items-center gap-3">
                    <div class="text-2xl font-bold font-montserrat" style="color: #ed1c24;">50+</div>
                    <div class="text-gray-600 font-lato">Countries Worldwide</div>
                </div>
                <div class="flex items-center gap-3">
                    <div class="text-2xl font-bold font-montserrat" style="color: #00a651;">15+</div>
                    <div class="text-gray-600 font-lato">Years of Excellence</div>
                </div>
            </div>
        </div>
        </div>
    </section>

    <!-- Course Categories Section -->
    <section class="py-20 bg-gradient-to-b from-white to-gray-50" data-aos="fade-up" data-aos-duration="900"
        data-aos-once="true">
        <div class="container mx-auto px-4">
            <!-- Section Header -->
            <div class="text-center mb-16" data-aos="fade-up">
                <div class="inline-flex items-center gap-2 rounded-full px-6 py-3 mb-6"
                    style="background-color: rgba(0, 33, 71, 0.1);">
                    <i class="fas fa-graduation-cap" style="color: #002147;"></i>
                    <span class="font-medium font-montserrat text-sm" style="color: #002147;">Leadership
                        Development</span>
                </div>
                <h2 class="font-bold text-4xl lg:text-5xl text-center mb-6 font-montserrat text-gray-900">
                    Explore Course Categories
                </h2>
                <p class="text-xl text-gray-600 text-center mb-8 max-w-3xl mx-auto leading-relaxed font-lato">
                    Discover specialized leadership tracks designed to equip you for impact in every sphere of society.
                </p>
            </div>

            <!-- Categories Grid -->
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-5 gap-8 mb-16">
                @if ($categories->count())
                    @foreach ($categories as $category)
                        <a href="courses-list.html"
                            class="group bg-white rounded-2xl shadow-sm hover:shadow-xl p-8 text-center transition-all duration-500 hover:transform hover:-translate-y-4 border border-gray-100 hover:border-primary/20 relative overflow-hidden"
                            data-aos="zoom-in" data-aos-delay="{{ $loop->index * 150 }}">

                            <!-- Background Gradient -->
                            <div
                                class="absolute inset-0 bg-gradient-to-br from-primary/5 to-secondary/5 opacity-0 group-hover:opacity-100 transition-opacity duration-500">
                            </div>

                            <!-- Content -->
                            <div class="relative z-10">
                                <!-- Icon Container -->
                                <div
                                    class="mb-6 p-4 bg-gradient-to-br from-gray-50 to-gray-100 rounded-2xl inline-block group-hover:bg-gradient-to-br group-hover:from-primary/10 group-hover:to-secondary/10 transition-all duration-500 group-hover:scale-110">
                                    <img src="{{ asset('storage/' . $category->image) }}" alt="{{ $category->name }} icon"
                                        class="mx-auto w-16 h-16 object-contain group-hover:scale-110 transition-transform duration-500">
                                </div>

                                <!-- Category Name -->
                                <h5
                                    class="font-bold text-xl mb-4 text-gray-800 group-hover:text-primary transition-colors duration-500 font-montserrat">
                                    {{ Str::ucfirst($category->name) }}
                                </h5>

                                <!-- Course Count -->
                                <div
                                    class="inline-flex items-center gap-2 bg-gray-100 group-hover:bg-primary/10 rounded-full px-4 py-2 transition-colors duration-500">
                                    <span class="font-semibold text-gray-600 group-hover:text-primary text-sm font-montserrat">
                                        {{ $category->courses->count() }}
                                    </span>
                                    <span class="text-gray-500 group-hover:text-primary/80 text-sm font-lato">
                                        course{{ $category->courses->count() !== 1 ? 's' : '' }}
                                    </span>
                                </div>

                                <!-- Hover Arrow -->
                                <div class="mt-4 opacity-0 group-hover:opacity-100 transition-opacity duration-500">
                                    <i class="fas fa-arrow-right text-primary"></i>
                                </div>
                            </div>
                        </a>
                    @endforeach
                @else
                    <!-- Fallback content -->
                    <div class="col-span-full text-center py-16">
                        <div class="max-w-md mx-auto">
                            <i class="fas fa-book-open text-6xl text-gray-300 mb-6"></i>
                            <h3 class="font-bold text-2xl text-gray-500 mb-4 font-montserrat">Categories Coming Soon</h3>
                            <p class="text-gray-400 font-lato leading-relaxed">We're preparing amazing course categories for
                                your leadership journey.</p>
                        </div>
                    </div>
                @endif
            </div>

            <!-- CTA Section -->
            <div class="text-center" data-aos="fade-up" data-aos-delay="400">
                <a href="#"
                    class="group inline-flex items-center gap-4 text-white font-bold py-5 px-10 rounded-2xl shadow-xl hover:shadow-2xl transition-all duration-500 transform hover:-translate-y-2 text-lg font-montserrat"
                    style="background: linear-gradient(135deg, #002147 0%, #ed1c24 100%);"
                    onmouseover="this.style.background='linear-gradient(135deg, #001a39 0%, #dc2626 100%)'"
                    onmouseout="this.style.background='linear-gradient(135deg, #002147 0%, #ed1c24 100%)'">
                    <span>Explore All Categories</span>
                    <i class="fas fa-arrow-right group-hover:translate-x-2 transition-transform duration-300"></i>
                </a>
            </div>
        </div>
    </section>

    <!-- What Makes BLI Stand Out Section -->
    <section class="py-20 bg-gradient-to-b from-gray-50 to-white relative overflow-hidden" data-aos="fade-up"
        data-aos-once="true">
        <!-- Background Elements -->
        <div class="absolute inset-0 overflow-hidden">
            <div class="absolute -top-24 -right-24 w-96 h-96 bg-primary/5 rounded-full"></div>
            <div class="absolute -bottom-24 -left-24 w-96 h-96 bg-secondary/5 rounded-full"></div>
        </div>

        <div class="container mx-auto px-4 relative z-10">
            <!-- Section Header -->
            <div class="text-center mb-20" data-aos="fade-up">
                <div class="inline-flex items-center gap-2 rounded-full px-6 py-3 mb-6"
                    style="background-color: rgba(0, 33, 71, 0.1);">
                    <i class="fas fa-crown" style="color: #002147;"></i>
                    <span class="font-medium font-montserrat text-sm" style="color: #002147;">Kingdom Excellence</span>
                </div>
                <h2 class="font-bold text-4xl lg:text-5xl text-center mb-6 font-montserrat text-gray-900">
                    What Makes BLI
                    <span style="color: #ed1c24;">Stand Out</span>
                </h2>
                <p class="text-xl text-gray-600 text-center max-w-3xl mx-auto leading-relaxed font-lato">
                    Experience transformational leadership development that integrates spiritual depth with practical
                    excellence.
                </p>
            </div>
            <!-- Features Grid -->
            <div class="grid grid-cols-1 lg:grid-cols-2 xl:grid-cols-3 gap-8">
                <!-- Kingdom-Based Leadership -->
                <div class="group bg-white rounded-2xl shadow-lg hover:shadow-2xl transition-all duration-500 p-8 border border-gray-100 hover:border-primary/20 relative overflow-hidden"
                    data-aos="fade-up" data-aos-delay="100">
                    <!-- Background Gradient -->
                    <div
                        class="absolute inset-0 bg-gradient-to-br from-primary/5 to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-500">
                    </div>

                    <!-- Content -->
                    <div class="relative z-10">
                        <div class="mb-6 p-4 rounded-2xl inline-block group-hover:scale-110 transition-transform duration-500"
                            style="background: linear-gradient(135deg, rgba(0, 33, 71, 0.1) 0%, rgba(0, 33, 71, 0.05) 100%);">
                            <i class="fas fa-crown text-4xl group-hover:scale-110 transition-transform duration-300"
                                style="color: #002147;"></i>
                        </div>
                        <h5 class="font-bold text-2xl mb-4 font-montserrat text-gray-900 transition-colors duration-300"
                            onmouseover="this.style.color='#002147'" onmouseout="this.style.color='#111827'">
                            Kingdom-Based Leadership
                        </h5>
                        <p
                            class="text-gray-600 font-lato leading-relaxed text-lg group-hover:text-gray-700 transition-colors duration-300">
                            Spiritually grounded, marketplace relevant. Thrive like Daniel, Joseph, and
                            Esther—integrating
                            faith with bold, strategic leadership in business, politics, education, and ministry.
                        </p>

                        <!-- Learn More Link -->
                        <div class="mt-6 opacity-0 group-hover:opacity-100 transition-opacity duration-500">
                            <a href="#"
                                class="inline-flex items-center gap-2 font-semibold font-montserrat hover:gap-4 transition-all duration-300"
                                style="color: #002147;">
                                <span>Learn More</span>
                                <i class="fas fa-arrow-right"></i>
                            </a>
                        </div>
                    </div>
                </div>
                <!-- Transformational Mentorship -->
                <div class="group bg-white rounded-2xl shadow-lg hover:shadow-2xl transition-all duration-500 p-8 border border-gray-100 hover:border-secondary/20 relative overflow-hidden"
                    data-aos="fade-up" data-aos-delay="200">
                    <!-- Background Gradient -->
                    <div
                        class="absolute inset-0 bg-gradient-to-br from-secondary/5 to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-500">
                    </div>

                    <!-- Content -->
                    <div class="relative z-10">
                        <div
                            class="mb-6 p-4 rounded-2xl bg-gradient-to-br from-secondary/10 to-secondary/5 inline-block group-hover:scale-110 transition-transform duration-500">
                            <i
                                class="fas fa-hands-helping text-4xl text-secondary group-hover:scale-110 transition-transform duration-300"></i>
                        </div>
                        <h5
                            class="font-bold text-2xl mb-4 font-montserrat text-gray-900 group-hover:text-secondary transition-colors duration-300">
                            Transformational Mentorship
                        </h5>
                        <p
                            class="text-gray-600 font-lato leading-relaxed text-lg group-hover:text-gray-700 transition-colors duration-300">
                            Students matched with seasoned mentors for growth checkpoints, leadership challenges, and
                            personal formation. Virtual coaching pods and check-ins ensure students flourish.
                        </p>

                        <!-- Learn More Link -->
                        <div class="mt-6 opacity-0 group-hover:opacity-100 transition-opacity duration-500">
                            <a href="#"
                                class="inline-flex items-center gap-2 font-semibold font-montserrat hover:gap-4 transition-all duration-300"
                                style="color: #ed1c24;">
                                <span>Learn More</span>
                                <i class="fas fa-arrow-right"></i>
                            </a>
                        </div>
                    </div>
                </div>
                <!-- Problem-Solving Curriculum -->
                <div class="group bg-white rounded-2xl shadow-lg hover:shadow-2xl transition-all duration-500 p-8 border border-gray-100 hover:border-accent/20 relative overflow-hidden lg:col-span-2 xl:col-span-1"
                    data-aos="fade-up" data-aos-delay="300">
                    <!-- Background Gradient -->
                    <div
                        class="absolute inset-0 bg-gradient-to-br from-accent/5 to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-500">
                    </div>

                    <!-- Content -->
                    <div class="relative z-10">
                        <div
                            class="mb-6 p-4 rounded-2xl bg-gradient-to-br from-accent/10 to-accent/5 inline-block group-hover:scale-110 transition-transform duration-500">
                            <i
                                class="fas fa-puzzle-piece text-4xl text-accent group-hover:scale-110 transition-transform duration-300"></i>
                        </div>
                        <h5
                            class="font-bold text-2xl mb-6 font-montserrat text-gray-900 group-hover:text-accent transition-colors duration-300">
                            Problem-Solving Curriculum
                        </h5>

                        <!-- Skills List -->
                        <div class="space-y-3 mb-6">
                            <div
                                class="flex items-center gap-3 group/item hover:bg-accent/5 rounded-lg p-2 -m-2 transition-colors duration-300">
                                <div
                                    class="w-2 h-2 bg-accent rounded-full group-hover/item:scale-125 transition-transform duration-300">
                                </div>
                                <span
                                    class="text-gray-600 font-lato group-hover/item:text-gray-800 transition-colors duration-300">Leading
                                    under pressure</span>
                            </div>
                            <div
                                class="flex items-center gap-3 group/item hover:bg-accent/5 rounded-lg p-2 -m-2 transition-colors duration-300">
                                <div
                                    class="w-2 h-2 bg-accent rounded-full group-hover/item:scale-125 transition-transform duration-300">
                                </div>
                                <span
                                    class="text-gray-600 font-lato group-hover/item:text-gray-800 transition-colors duration-300">Navigating
                                    toxic environments</span>
                            </div>
                            <div
                                class="flex items-center gap-3 group/item hover:bg-accent/5 rounded-lg p-2 -m-2 transition-colors duration-300">
                                <div
                                    class="w-2 h-2 bg-accent rounded-full group-hover/item:scale-125 transition-transform duration-300">
                                </div>
                                <span
                                    class="text-gray-600 font-lato group-hover/item:text-gray-800 transition-colors duration-300">Strategic
                                    planning with prophetic insight</span>
                            </div>
                            <div
                                class="flex items-center gap-3 group/item hover:bg-accent/5 rounded-lg p-2 -m-2 transition-colors duration-300">
                                <div
                                    class="w-2 h-2 bg-accent rounded-full group-hover/item:scale-125 transition-transform duration-300">
                                </div>
                                <span
                                    class="text-gray-600 font-lato group-hover/item:text-gray-800 transition-colors duration-300">Rebuilding
                                    broken systems</span>
                            </div>
                            <div
                                class="flex items-center gap-3 group/item hover:bg-accent/5 rounded-lg p-2 -m-2 transition-colors duration-300">
                                <div
                                    class="w-2 h-2 bg-accent rounded-full group-hover/item:scale-125 transition-transform duration-300">
                                </div>
                                <span
                                    class="text-gray-600 font-lato group-hover/item:text-gray-800 transition-colors duration-300">Sustaining
                                    influence with integrity</span>
                            </div>
                        </div>

                        <!-- Learn More Link -->
                        <div class="opacity-0 group-hover:opacity-100 transition-opacity duration-500">
                            <a href="#"
                                class="inline-flex items-center gap-2 font-semibold font-montserrat hover:gap-4 transition-all duration-300"
                                style="color: #00a651;">
                                <span>View Curriculum</span>
                                <i class="fas fa-arrow-right"></i>
                            </a>
                        </div>
                    </div>
                </div>
                <!-- Prophetic Leadership Integration -->
                <div class="group bg-white rounded-2xl shadow-lg hover:shadow-2xl transition-all duration-500 p-8 border border-gray-100 hover:border-primary/20 relative overflow-hidden"
                    data-aos="fade-up" data-aos-delay="400">
                    <!-- Background Gradient -->
                    <div
                        class="absolute inset-0 bg-gradient-to-br from-primary/5 to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-500">
                    </div>

                    <!-- Content -->
                    <div class="relative z-10">
                        <div
                            class="mb-6 p-4 rounded-2xl bg-gradient-to-br from-primary/10 to-primary/5 inline-block group-hover:scale-110 transition-transform duration-500">
                            <i
                                class="fas fa-eye text-4xl text-primary group-hover:scale-110 transition-transform duration-300"></i>
                        </div>
                        <h5
                            class="font-bold text-2xl mb-4 font-montserrat text-gray-900 group-hover:text-primary transition-colors duration-300">
                            Prophetic Leadership
                        </h5>
                        <p
                            class="text-gray-600 font-lato leading-relaxed text-lg group-hover:text-gray-700 transition-colors duration-300">
                            Integrates prophetic insight with leadership training—sharpening discernment, interpreting
                            divine seasons, and leading with Spirit-led accuracy.
                        </p>

                        <!-- Learn More Link -->
                        <div class="mt-6 opacity-0 group-hover:opacity-100 transition-opacity duration-500">
                            <a href="#"
                                class="inline-flex items-center gap-2 font-semibold font-montserrat hover:gap-4 transition-all duration-300"
                                style="color: #002147;">
                                <span>Learn More</span>
                                <i class="fas fa-arrow-right"></i>
                            </a>
                        </div>
                    </div>
                </div>

                <!-- Global Vision, Local Expression -->
                <div class="group bg-white rounded-2xl shadow-lg hover:shadow-2xl transition-all duration-500 p-8 border border-gray-100 hover:border-secondary/20 relative overflow-hidden"
                    data-aos="fade-up" data-aos-delay="500">
                    <!-- Background Gradient -->
                    <div
                        class="absolute inset-0 bg-gradient-to-br from-secondary/5 to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-500">
                    </div>

                    <!-- Content -->
                    <div class="relative z-10">
                        <div
                            class="mb-6 p-4 rounded-2xl bg-gradient-to-br from-secondary/10 to-secondary/5 inline-block group-hover:scale-110 transition-transform duration-500">
                            <i
                                class="fas fa-globe-americas text-4xl text-secondary group-hover:scale-110 transition-transform duration-300"></i>
                        </div>
                        <h5
                            class="font-bold text-2xl mb-4 font-montserrat text-gray-900 group-hover:text-secondary transition-colors duration-300">
                            Global Vision, Local Expression
                        </h5>
                        <p
                            class="text-gray-600 font-lato leading-relaxed text-lg group-hover:text-gray-700 transition-colors duration-300">
                            Hybrid learning (virtual + in-person), summits, bootcamps, and global collaborations amplify
                            graduates' influence worldwide.
                        </p>

                        <!-- Learn More Link -->
                        <div class="mt-6 opacity-0 group-hover:opacity-100 transition-opacity duration-500">
                            <a href="#"
                                class="inline-flex items-center gap-2 font-semibold font-montserrat hover:gap-4 transition-all duration-300"
                                style="color: #ed1c24;">
                                <span>Learn More</span>
                                <i class="fas fa-arrow-right"></i>
                            </a>
                        </div>
                    </div>
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
                                {{ $event->theme }}
                            </p>

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


                                @if ($event->mode == 'hybrid')
                                    <div class="flex items-center gap-2 text-gray-600 text-xs font-lato">
                                        @if ($event->isRegistered() && Carbon::parse($event->start_date)->isNowOrPast())
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
                                        @if ($event->isRegistered() && Carbon::parse($event->start_date)->isNowOrPast())
                                            <i class="fas fa-link text-secondary text-xs"></i>
                                            <span class="capitalize">
                                                <a href="{{ $event->location }}" target="_blank"
                                                    class="hover:underline">{{ $event->location }}</a>
                                            </span>
                                        @endif
                                    </div>
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
                        <img src="{{ asset('images/logo.jpg') }}" alt="Partner organization {{ $i }}"
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
                    <div class="bg-gradient-to-r from-primary to-secondary rounded-xl p-6 text-white" data-aos="fade-up"
                        data-aos-delay="500">
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