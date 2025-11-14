@php
    use Carbon\Carbon;

    $start = $event->start_date;
    $end = $event->end_date;
@endphp
<x-guest-layout>
    <!-- Enhanced Breadcrumb Navigation -->
    <section class="py-6 bg-gradient-to-br from-gray-50 to-white border-b border-gray-100">
        <div class="container mx-auto px-6">
            <nav class="breadcrumb" data-aos="fade-right" data-aos-duration="600">
                <ul class="flex items-center space-x-3 text-sm font-lato">
                    <li class="inline">
                        <a href="{{ route('homepage') }}"
                            class="flex items-center gap-2 font-semibold transition-colors duration-300 hover:scale-105"
                            style="color: #00a651;">
                            <i class="fas fa-home text-xs"></i>
                            <span>Home</span>
                        </a>
                    </li>
                    <li class="inline">
                        <i class="fas fa-chevron-right text-xs text-gray-400"></i>
                    </li>
                    <li class="inline">
                        <a href="{{ route('events.index') }}"
                            class="flex items-center gap-2 font-semibold transition-colors duration-300 hover:scale-105"
                            style="color: #00a651;">
                            <i class="fas fa-calendar-alt text-xs"></i>
                            <span>Events</span>
                        </a>
                    </li>
                    <li class="inline">
                        <i class="fas fa-chevron-right text-xs text-gray-400"></i>
                    </li>
                    <li class="inline">
                        <span class="text-gray-600 truncate max-w-[200px] sm:max-w-[300px] font-medium">{{ $event->title }}</span>
                    </li>
                </ul>
            </nav>
        </div>
    </section>

    <!-- Event Details Section -->
    <section class="py-8 md:py-12">
        <div class="container mx-auto px-4">
            <div class="grid grid-cols-1 lg:grid-cols-4 gap-6 lg:gap-8">
                <!-- Main Content -->
                <div class="lg:col-span-3 space-y-6 md:space-y-8">
                    <!-- Enhanced Event Header -->
                    <div class="space-y-6 md:space-y-8" data-aos="fade-up" data-aos-delay="200">
                        <div class="space-y-4">
                            <!-- Event Title -->
                            <h1 class="text-2xl md:text-4xl lg:text-5xl font-extrabold leading-tight font-montserrat" 
                                style="color: #002147;">
                                {{ $event->title }}
                            </h1>
                            
                            <!-- Event Theme -->
                            <h2 class="text-lg md:text-xl lg:text-2xl font-bold font-montserrat" 
                                style="color: #00a651;">
                                {{ $event->theme }}
                            </h2>
                        </div>
                        
                        <!-- Enhanced Event Status Section -->
                        <div class="flex flex-wrap items-center gap-4">
                            @php
                                $now = now();
                                $startDate = Carbon::parse($event->start_date);
                                $endDate = Carbon::parse($event->end_date);
                                $isLive = $now->between($startDate, $endDate);
                                $isUpcoming = $now->lt($startDate);
                                $isPast = $now->gt($endDate);
                            @endphp

                            <!-- Status Badge -->
                            @if ($isLive)
                                <span class="px-4 py-2 rounded-full text-sm font-semibold flex items-center gap-2 font-montserrat shadow-lg text-white"
                                      style="background-color: #ed1c24;">
                                    <span class="w-2.5 h-2.5 bg-white rounded-full animate-pulse"></span>
                                    <i class="fas fa-broadcast-tower text-xs"></i>
                                    Live Now
                                </span>
                            @elseif($isUpcoming)
                                <span class="px-4 py-2 rounded-full text-sm font-semibold font-montserrat shadow-lg text-white"
                                      style="background-color: #00a651;">
                                    <i class="fas fa-clock text-xs mr-1"></i>
                                    Coming Soon
                                </span>
                            @else
                                <span class="px-4 py-2 rounded-full text-sm font-semibold font-montserrat shadow-lg bg-gray-500 text-white">
                                    <i class="fas fa-history text-xs mr-1"></i>
                                    Event Ended
                                </span>
                            @endif

                            <!-- Event Mode Badge -->
                            <span class="px-4 py-2 rounded-full text-sm font-semibold font-montserrat shadow-md border-2 capitalize"
                                  style="color: #002147; border-color: #002147; background-color: rgba(0, 33, 71, 0.1);">
                                <i class="fas fa-{{ $event->mode == 'online' ? 'globe' : ($event->mode == 'offline' ? 'map-marker-alt' : 'laptop') }} text-xs mr-1"></i>
                                {{ $event->mode ?? 'Hybrid' }}
                            </span>

                            <!-- Countdown Timer -->
                            @if ($isUpcoming)
                                <div class="flex items-center gap-3 px-4 py-2 bg-white rounded-full shadow-md border border-gray-200">
                                    <i class="fas fa-stopwatch text-sm" style="color: #00a651;"></i>
                                    <span id="countdown" class="font-semibold text-sm font-lato" style="color: #002147;"></span>
                                </div>
                            @endif
                        </div>
                    </div>

                    <!-- Enhanced Event Cover Image -->
                    <div class="relative rounded-2xl overflow-hidden shadow-2xl border border-gray-200 group" 
                         data-aos="fade-up" data-aos-delay="400">
                        <img src="{{ asset('storage/' . $event->program_cover) }}" 
                             alt="{{ $event->title }}"
                             class="w-full h-64 sm:h-96 lg:h-[32rem] object-cover group-hover:scale-105 transition-transform duration-700">
                        
                        <!-- Image Overlay for Better Text Visibility -->
                        <div class="absolute inset-0 bg-gradient-to-t from-black/20 to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-500"></div>
                        
                        <!-- Optional Image Caption -->
                        <div class="absolute bottom-4 left-4 right-4">
                            <div class="bg-white/10 backdrop-blur-sm rounded-lg p-3 opacity-0 group-hover:opacity-100 transition-opacity duration-500">
                                <p class="text-white text-sm font-lato">{{ $event->title }}</p>
                            </div>
                        </div>
                    </div>

                    <!-- Enhanced Event Description -->
                    <div class="bg-white rounded-2xl shadow-lg border border-gray-100 p-8 md:p-10" 
                         data-aos="fade-up" data-aos-delay="500">
                        <div class="flex items-center gap-4 mb-6 md:mb-8">
                            <div class="w-12 h-12 rounded-2xl flex items-center justify-center" 
                                 style="background: linear-gradient(135deg, #002147 0%, #003875 100%);">
                                <i class="fas fa-info-circle text-white text-xl"></i>
                            </div>
                            <h2 class="text-2xl md:text-3xl font-bold font-montserrat" style="color: #002147;">
                                Event Description
                            </h2>
                        </div>
                        
                        <div class="prose prose-lg max-w-none text-gray-700 leading-relaxed font-lato event-description">
                            {!! $event->description !!}
                        </div>
                    </div>

                    <!-- Enhanced Event Speakers -->
                    @if ($event->speakers && $event->speakers->count())
                        <div class="bg-white rounded-2xl shadow-lg border border-gray-100 p-8 md:p-10" 
                             data-aos="fade-up" data-aos-delay="600">
                            <div class="flex items-center gap-4 mb-8">
                                <div class="w-12 h-12 rounded-2xl flex items-center justify-center" 
                                     style="background: linear-gradient(135deg, #00a651 0%, #15803d 100%);">
                                    <i class="fas fa-microphone text-white text-xl"></i>
                                </div>
                                <h2 class="text-2xl md:text-3xl font-bold font-montserrat" style="color: #002147;">
                                    Featured Speakers
                                </h2>
                            </div>
                            
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 md:gap-8">
                                @foreach ($event->speakers as $speaker)
                                    <div class="group bg-gradient-to-br from-gray-50 to-white rounded-2xl border border-gray-200 hover:border-accent/30 hover:shadow-xl transition-all duration-500 p-6"
                                         data-aos="fade-up" data-aos-delay="{{ 700 + ($loop->index * 100) }}">
                                        <div class="flex items-start gap-5">
                                            <!-- Speaker Avatar -->
                                            <div class="flex-shrink-0 relative">
                                                @if (!empty($speaker->user->photo) && file_exists(public_path('storage/' . $speaker->user->photo)))
                                                    <img src="{{ asset('storage/' . $speaker->user->photo) }}"
                                                        alt="{{ $speaker->user->name }}"
                                                        class="w-16 h-16 md:w-20 md:h-20 rounded-2xl object-cover shadow-lg group-hover:scale-110 transition-transform duration-500">
                                                @else
                                                    <img src="https://ui-avatars.com/api/?name={{ urlencode($speaker->user->name) }}&background=002147&color=fff&size=128&font-size=0.35&bold=true"
                                                        alt="{{ $speaker->user->name }}"
                                                        class="w-16 h-16 md:w-20 md:h-20 rounded-2xl object-cover shadow-lg group-hover:scale-110 transition-transform duration-500">
                                                @endif
                                                
                                                <!-- Speaker Badge -->
                                                <div class="absolute -top-2 -right-2 w-6 h-6 rounded-full flex items-center justify-center shadow-sm" 
                                                     style="background-color: #00a651;">
                                                    <i class="fas fa-star text-white text-xs"></i>
                                                </div>
                                            </div>
                                            
                                            <!-- Speaker Info -->
                                            <div class="flex-1 min-w-0">
                                                <a href="{{ route("speakers.profile", $speaker) }}"
                                                    class="font-bold text-lg md:text-xl mb-2 font-montserrat block group-hover:text-accent transition-colors duration-300"
                                                    style="color: #002147;">
                                                    {{ $speaker->user->name }}
                                                </a>

                                                @if ($speaker->user->headline)
                                                    <p class="font-semibold text-sm md:text-base mb-3 font-montserrat line-clamp-2"
                                                       style="color: #00a651;">
                                                        {{ $speaker->user->headline }}
                                                    </p>
                                                @endif

                                                @if ($speaker->bio)
                                                    <p class="text-gray-600 text-sm leading-relaxed line-clamp-3 font-lato">
                                                        {{ $speaker->bio }}
                                                    </p>
                                                @elseif(!$speaker->user->headline)
                                                    <p class="text-gray-400 text-sm italic font-lato">
                                                        Professional speaker and industry expert
                                                    </p>
                                                @endif
                                                
                                                <!-- View Profile Link -->
                                                <div class="mt-4">
                                                    <a href="{{ route("speakers.profile", $speaker) }}" 
                                                       class="inline-flex items-center gap-2 text-sm font-semibold transition-all duration-300 hover:gap-3"
                                                       style="color: #00a651;">
                                                        <span>View Profile</span>
                                                        <i class="fas fa-arrow-right text-xs"></i>
                                                    </a>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    @endif

                    <!-- Share Section -->
                    @auth
                        @if ($event->isRegistered() && $event->resources && count($event->resources))
                            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 md:p-8">
                                <h2
                                    class="text-xl md:text-2xl font-bold text-primary mb-4 md:mb-6 flex items-center gap-3 font-montserrat">
                                    <div class="w-1 h-6 md:h-8 bg-secondary rounded-full"></div>
                                    Event Resources
                                </h2>
                                <ul class="space-y-3">
                                    @foreach ($event->resources as $resource)
                                        <li>
                                            @if ($resource->type === 'file')
                                                <a href="{{ asset('storage/' . $resource->file_path) }}" target="_blank"
                                                    class="flex items-center gap-3 p-3 bg-gray-50 rounded-lg border border-gray-200 hover:bg-gray-100 transition-all duration-300 font-lato">
                                                    <i data-lucide="file-text" class="w-5 h-5 text-secondary"></i>
                                                    <span class="font-medium">{{ $resource->title }}</span>
                                                </a>
                                            @elseif ($resource->type === 'link')
                                                <a href="{{ $resource->external_link }}" target="_blank"
                                                    class="flex items-center gap-3 p-3 bg-gray-50 rounded-lg border border-gray-200 hover:bg-gray-100 transition-all duration-300 font-lato">
                                                    <i data-lucide="link" class="w-5 h-5 text-secondary"></i>
                                                    <span class="font-medium">{{ $resource->title }}</span>
                                                </a>
                                            @endif
                                        </li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif
                    @endauth
                </div>

                <!-- Enhanced Sidebar -->
                <div class="space-y-8" data-aos="fade-left" data-aos-delay="300">
                    <!-- Enhanced Registration Card -->
                    <div class="relative overflow-hidden rounded-2xl shadow-2xl">
                        <!-- Gradient Background -->
                        <div class="absolute inset-0" style="background: linear-gradient(135deg, #002147 0%, #003875 50%, #00a651 100%);"></div>
                        
                        <!-- Content -->
                        <div class="relative p-8 text-white">
                            <!-- Header -->
                            <div class="text-center mb-8">
                                <div class="w-16 h-16 bg-white/20 backdrop-blur-sm rounded-2xl flex items-center justify-center mx-auto mb-4">
                                    <i class="fas fa-ticket-alt text-2xl text-white"></i>
                                </div>
                                <h3 class="text-2xl font-bold font-montserrat">Event Registration</h3>
                                <p class="text-white/80 font-lato mt-2">Secure your spot today</p>
                            </div>

                            <!-- Event Details -->
                            <div class="space-y-4 mb-8">
                                <div class="bg-white/10 backdrop-blur-sm rounded-xl p-4 border border-white/20">
                                    <div class="flex justify-between items-center">
                                        <span class="text-white/90 font-medium font-lato flex items-center gap-2">
                                            <i class="fas fa-users text-sm"></i>
                                            Slots Remaining
                                        </span>
                                        <span class="font-bold text-xl text-white font-montserrat">{{ $event->slotsRemaining() }}</span>
                                    </div>
                                </div>
                                
                                <div class="bg-white/10 backdrop-blur-sm rounded-xl p-4 border border-white/20">
                                    <div class="flex justify-between items-center">
                                        <span class="text-white/90 font-medium font-lato flex items-center gap-2">
                                            <i class="fas fa-tag text-sm"></i>
                                            Registration Fee
                                        </span>
                                        @if ($event->entry_fee > 0)
                                            <span class="font-bold text-xl text-white font-montserrat">₦{{ number_format($event->entry_fee, 0) }}</span>
                                        @else
                                            <span class="font-bold text-xl text-white font-montserrat">FREE</span>
                                        @endif
                                    </div>
                                </div>
                            </div>

                            <!-- Registration Button -->
                            <button @if ($event->isRegistered() || $event->getRevokeCount() == 4) disabled @endif 
                                    data-modal-target="popup-modal" data-modal-toggle="popup-modal"
                                    class="w-full bg-white text-primary py-4 px-6 rounded-xl font-bold text-lg hover:bg-gray-100 disabled:opacity-50 disabled:cursor-not-allowed transition-all duration-300 transform hover:scale-105 shadow-lg hover:shadow-xl flex items-center justify-center gap-3 font-montserrat mb-4">
                                @if ($event->isRegistered())
                                    <i class="fas fa-check-circle text-xl" style="color: #00a651;"></i>
                                    <span>Already Registered</span>
                                @elseif($event->getRevokeCount() == 4)
                                    <i class="fas fa-users text-xl text-gray-500"></i>
                                    <span>Max Registrations</span>
                                @else
                                    <i class="fas fa-hand-paper text-xl" style="color: #002147;"></i>
                                    <span>Register Now</span>
                                @endif
                            </button>

                            <!-- Speaker Application -->
                            @if ($event->is_allowing_application)
                                <a href="{{ URL::signedRoute('event.speakers.apply', [$event]) }}"
                                    class="w-full border-2 border-white text-white py-3 px-6 rounded-xl font-semibold hover:bg-white transition-all duration-300 flex items-center justify-center gap-3 font-montserrat group"
                                    style="hover:color: #002147;">
                                    <i class="fas fa-microphone group-hover:scale-110 transition-transform"></i>
                                    Apply as Speaker
                                </a>
                            @endif
                            
                            <!-- Login Notice -->
                            @guest()
                                <div class="mt-6 p-4 bg-white/10 backdrop-blur-sm rounded-xl border border-white/20">
                                    <p class="text-white/90 text-sm text-center font-lato flex items-center justify-center gap-2">
                                        <i class="fas fa-info-circle"></i>
                                        You must be logged in to register for this event
                                    </p>
                                </div>
                            @endguest
                        </div>
                    </div>

                    <!-- Enhanced Event Info Card -->
                    <div class="bg-white rounded-2xl shadow-lg border border-gray-100 p-6 hover:shadow-xl transition-shadow duration-300">
                        <div class="flex items-center gap-3 mb-6">
                            <div class="w-10 h-10 rounded-xl flex items-center justify-center" 
                                 style="background-color: rgba(0, 33, 71, 0.1);">
                                <i class="fas fa-info-circle text-lg" style="color: #002147;"></i>
                            </div>
                            <h4 class="font-bold text-xl font-montserrat" style="color: #002147;">Event Details</h4>
                        </div>
                        
                        <div class="space-y-5">
                            <!-- Start Time -->
                            <div class="flex items-start gap-4 p-4 bg-gray-50 rounded-xl hover:bg-gray-100 transition-colors duration-300">
                                <div class="w-12 h-12 rounded-xl flex items-center justify-center flex-shrink-0" 
                                     style="background-color: rgba(0, 166, 81, 0.1);">
                                    <i class="fas fa-play-circle text-xl" style="color: #00a651;"></i>
                                </div>
                                <div class="flex-1 min-w-0">
                                    <p class="font-bold text-sm font-montserrat mb-1" style="color: #002147;">Event Starts</p>
                                    <p class="text-gray-800 font-semibold font-lato">
                                        {{ Carbon::parse($event->start_date)->format('g:i A') }}
                                    </p>
                                    <p class="text-gray-600 text-sm font-lato">
                                        {{ Carbon::parse($event->start_date)->format('l, F j, Y') }}
                                    </p>
                                </div>
                            </div>

                            <!-- End Time -->
                            <div class="flex items-start gap-4 p-4 bg-gray-50 rounded-xl hover:bg-gray-100 transition-colors duration-300">
                                <div class="w-12 h-12 rounded-xl flex items-center justify-center flex-shrink-0" 
                                     style="background-color: rgba(237, 28, 36, 0.1);">
                                    <i class="fas fa-flag-checkered text-xl" style="color: #ed1c24;"></i>
                                </div>
                                <div class="flex-1 min-w-0">
                                    <p class="font-bold text-sm font-montserrat mb-1" style="color: #002147;">Event Ends</p>
                                    <p class="text-gray-800 font-semibold font-lato">
                                        {{ Carbon::parse($event->end_date)->format('g:i A') }}
                                    </p>
                                    <p class="text-gray-600 text-sm font-lato">
                                        {{ Carbon::parse($event->end_date)->format('l, F j, Y') }}
                                    </p>
                                </div>
                            </div>

                            <!-- Location -->
                            @auth
                                @if ($event->isRegistered())
                                    <div class="flex items-start gap-3">
                                        <div
                                            class="w-8 h-8 bg-primary/10 rounded-lg flex items-center justify-center flex-shrink-0">
                                            <i data-lucide="map-pin" class="w-4 h-4 text-primary"></i>
                                        </div>
                                        <div class="flex-1 min-w-0">
                                            <p class="font-semibold text-primary text-sm font-montserrat">
                                                @if ($event->mode == 'offline')
                                                    Venue Address
                                                @elseif($event->mode == 'online')
                                                    Meeting Link
                                                @endif
                                            </p>
                                            @if ($event->mode == 'offline')
                                                <p class="text-gray-700 text-sm break-words font-lato">
                                                    {{ $event->physical_address }}
                                                </p>
                                            @elseif($event->mode == 'online')
                                                @if ($end->isPast())
                                                    <span class="text-red-600 font-semibold text-sm font-lato">Meeting link
                                                        has expired.</span>
                                                @elseif ($start->isToday())
                                                    <a href="{{ $event->location }}"
                                                        class="text-secondary font-semibold hover:underline break-all text-sm font-lato"
                                                        target="_blank">
                                                        Click to Join Meeting
                                                    </a>
                                                @elseif ($start->isFuture())
                                                    <span class="text-gray-600 text-sm font-lato">
                                                        Meeting link will be available on
                                                        {{ $start->format('F j, Y \a\t g:i A') }}.
                                                    </span>
                                                @else
                                                    <a href="{{ $event->location }}"
                                                        class="text-secondary font-semibold hover:underline break-all text-sm font-lato"
                                                        target="_blank">
                                                        Click to Join Meeting
                                                    </a>
                                                @endif
                                            @elseif($event->mode == 'hybrid')
                                                <p class="text-gray-700 text-sm break-words font-lato">
                                                    {{ $event->physical_address }}
                                                </p>
                                                @if (Carbon::parse($event->start_date)->isNowOrPast())
                                                    <a href="{{ $event->location }}"
                                                        class="text-secondary font-semibold hover:underline break-all text-sm font-lato"
                                                        target="_blank">
                                                        Click to Join Meeting
                                                    </a>
                                                @endif
                                            @endif
                                        </div>
                                    </div>
                                @endif
                            @endauth
                            <div class="flex items-start gap-3">
                                <div
                                    class="w-8 h-8 bg-primary/10 rounded-lg flex items-center justify-center flex-shrink-0">
                                    <i data-lucide="globe" class="w-4 h-4 text-primary"></i>
                                </div>
                                <div class="flex-1 min-w-0">
                                    <span class="pill font-light">{{ $event->mode }}</span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Enhanced Quick Actions Card -->
                    <div class="relative overflow-hidden rounded-2xl shadow-lg">
                        <!-- Gradient Background -->
                        <div class="absolute inset-0" style="background: linear-gradient(135deg, #00a651 0%, #15803d 100%);"></div>
                        
                        <!-- Content -->
                        <div class="relative p-6 text-white">
                            <div class="flex items-center gap-3 mb-6">
                                <div class="w-10 h-10 bg-white/20 backdrop-blur-sm rounded-xl flex items-center justify-center">
                                    <i class="fas fa-bolt text-lg text-white"></i>
                                </div>
                                <h4 class="font-bold text-xl text-white font-montserrat">Quick Actions</h4>
                            </div>
                            
                            <div class="space-y-3">
                                <a href="{{ route('events.index') }}"
                                    class="flex items-center gap-3 p-4 bg-white/10 backdrop-blur-sm rounded-xl hover:bg-white/20 transition-all duration-300 font-lato group border border-white/20">
                                    <i class="fas fa-calendar-alt text-lg group-hover:scale-110 transition-transform duration-300" style="color: #ffffff;"></i>
                                    <span class="font-semibold">View All Events</span>
                                    <i class="fas fa-arrow-right text-sm ml-auto opacity-0 group-hover:opacity-100 group-hover:translate-x-1 transition-all duration-300"></i>
                                </a>
                                
                                <a href="{{ route('homepage') }}"
                                    class="flex items-center gap-3 p-4 bg-white/10 backdrop-blur-sm rounded-xl hover:bg-white/20 transition-all duration-300 font-lato group border border-white/20">
                                    <i class="fas fa-home text-lg group-hover:scale-110 transition-transform duration-300" style="color: #ffffff;"></i>
                                    <span class="font-semibold">Back to Home</span>
                                    <i class="fas fa-arrow-right text-sm ml-auto opacity-0 group-hover:opacity-100 group-hover:translate-x-1 transition-all duration-300"></i>
                                </a>
                                
                                @auth
                                    <a href="{{ route('user.events') }}"
                                        class="flex items-center gap-3 p-4 bg-white/10 backdrop-blur-sm rounded-xl hover:bg-white/20 transition-all duration-300 font-lato group border border-white/20">
                                        <i class="fas fa-list text-lg group-hover:scale-110 transition-transform duration-300" style="color: #ffffff;"></i>
                                        <span class="font-semibold">My Events</span>
                                        <i class="fas fa-arrow-right text-sm ml-auto opacity-0 group-hover:opacity-100 group-hover:translate-x-1 transition-all duration-300"></i>
                                    </a>
                                @endauth
                                
                                <a href="{{ route('courses.index') }}"
                                    class="flex items-center gap-3 p-4 bg-white/10 backdrop-blur-sm rounded-xl hover:bg-white/20 transition-all duration-300 font-lato group border border-white/20">
                                    <i class="fas fa-graduation-cap text-lg group-hover:scale-110 transition-transform duration-300" style="color: #ffffff;"></i>
                                    <span class="font-semibold">Browse Courses</span>
                                    <i class="fas fa-arrow-right text-sm ml-auto opacity-0 group-hover:opacity-100 group-hover:translate-x-1 transition-all duration-300"></i>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Enhanced Registration Modal -->
    <div id="popup-modal" tabindex="-1"
        class="hidden overflow-y-auto overflow-x-hidden fixed top-0 right-0 left-0 z-50 justify-center items-center w-full md:inset-0 h-[calc(100%-1rem)] max-h-full bg-black/60 backdrop-blur-md">
        <div class="relative p-4 w-full max-w-lg max-h-full">
            <div class="relative bg-white rounded-2xl shadow-2xl border border-gray-100 overflow-hidden">
                <!-- Modal Header with Gradient -->
                <div class="relative p-6 text-center" style="background: linear-gradient(135deg, #002147 0%, #003875 100%);">
                    <!-- Close Button -->
                    <button type="button"
                        class="absolute top-4 right-4 text-white/80 hover:text-white hover:bg-white/20 rounded-xl text-sm w-8 h-8 flex justify-center items-center transition-all duration-300"
                        data-modal-hide="popup-modal">
                        <i class="fas fa-times w-4 h-4"></i>
                        <span class="sr-only">Close modal</span>
                    </button>
                    
                    <!-- Modal Icon -->
                    <div class="w-20 h-20 bg-white/20 backdrop-blur-sm rounded-2xl flex items-center justify-center mx-auto mb-4">
                        <i class="fas fa-ticket-alt text-3xl text-white"></i>
                    </div>
                    
                    <!-- Modal Title -->
                    <h3 class="text-xl font-bold text-white font-montserrat mb-2">
                        Event Registration
                    </h3>
                    <p class="text-white/90 text-sm font-lato">
                        Secure your spot at this transformational event
                    </p>
                </div>

                <!-- Modal Content -->
                <div class="p-8 text-center">
                    <div class="mb-6">
                        <h4 class="text-lg font-semibold mb-2 font-montserrat" style="color: #002147;">
                            You're about to register for:
                        </h4>
                        <div class="p-4 rounded-xl border-2 border-dashed mb-4" style="border-color: #00a651; background-color: rgba(0, 166, 81, 0.05);">
                            <span class="font-bold text-lg block font-montserrat" style="color: #002147;">{{ $event->title }}</span>
                            <span class="text-sm font-lato" style="color: #00a651;">{{ $event->theme }}</span>
                        </div>
                        <p class="text-gray-600 font-lato">
                            We're excited to have you join us for this transformational experience!
                        </p>
                    </div>
                    
                    <form action="{{ route('events.join', $event->slug) }}" x-data="{ loading: false }"
                        x-on:submit="loading=true" method="post">
                        @csrf
                        <div class="flex flex-col sm:flex-row gap-4 justify-center">
                            <button data-modal-hide="popup-modal" type="submit" :disabled="loading"
                                class="text-white font-semibold rounded-xl text-sm px-6 py-3 text-center transition-all duration-300 font-montserrat flex items-center justify-center gap-2 min-w-[160px] shadow-lg hover:shadow-xl transform hover:scale-105"
                                style="background-color: #00a651;"
                                onmouseover="this.style.backgroundColor='#15803d'"
                                onmouseout="this.style.backgroundColor='#00a651'">
                                <span x-show="!loading" class="flex items-center gap-2">
                                    <i class="fas fa-check"></i>
                                    Yes, Register Me
                                </span>
                                <span x-show="loading" class="flex items-center gap-2">
                                    <svg class="animate-spin h-4 w-4 text-white" fill="none" viewBox="0 0 24 24">
                                        <circle class="opacity-25" cx="12" cy="12" r="10"
                                            stroke="currentColor" stroke-width="4"></circle>
                                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v8z"></path>
                                    </svg>
                                    Registering...
                                </span>
                            </button>
                            <button data-modal-hide="popup-modal" type="button"
                                class="py-3 px-6 text-sm font-semibold border-2 rounded-xl transition-all duration-300 font-montserrat hover:bg-gray-50"
                                style="color: #002147; border-color: #002147;">
                                <i class="fas fa-times mr-2"></i>
                                Cancel
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script>
        // Initialize Lucide icons
        document.addEventListener('DOMContentLoaded', function() {
            if (typeof lucide !== 'undefined') {
                lucide.createIcons();
            }

            // Only call if the function exists
            startCountdown("{{ $event->start_date }}", "countdown", "{{ $event->end_date }}");
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
    </style>
</x-guest-layout>
