@php
    use Carbon\Carbon;
@endphp
<x-guest-layout>
    <!-- Breadcrumb Navigation -->
    <section class="py-4 bg-gray-50 border-b">
        <div class="container mx-auto px-4">
            <nav class="breadcrumb">
                <ul class="flex items-center space-x-2 text-sm font-lato">
                    <li class="inline">
                        <a href="{{ route('homepage') }}"
                            class="text-secondary hover:text-primary transition-colors font-medium">Home</a>
                    </li>
                    <li class="inline">
                        <span class="text-gray-400">/</span>
                    </li>
                    <li class="inline">
                        <a href="{{ route('events.index') }}"
                            class="text-secondary hover:text-primary transition-colors font-medium">Events</a>
                    </li>
                    <li class="inline">
                        <span class="text-gray-400">/</span>
                    </li>
                    <li class="inline">
                        <span
                            class="text-gray-600 truncate max-w-[120px] sm:max-w-[200px] font-medium">{{ $event->title }}</span>
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
                    <!-- Event Header -->
                    <div class="space-y-3 md:space-y-4">
                        <div class="space-y-1">
                            <h1
                                class="text-xl md:text-3xl lg:text-4xl font-extrabold text-primary leading-tight font-montserrat">
                                {{ $event->title }}
                            </h1>
                            <h3 class="text-lg md:text-xl lg:text-xl text-accent font-montserrat font-bold">
                                {{ $event->theme }}</h3>
                        </div>
                        <!-- Event Status Badge -->
                        <div class="flex flex-wrap items-center gap-3">
                            @php
                                $now = now();
                                $startDate = Carbon::parse($event->start_date);
                                $endDate = Carbon::parse($event->end_date);
                                $isLive = $now->between($startDate, $endDate);
                                $isUpcoming = $now->lt($startDate);
                                $isPast = $now->gt($endDate);
                            @endphp

                            @if ($isLive)
                                <span
                                    class="bg-secondary/20 text-secondary px-3 py-2 rounded-full text-sm font-semibold flex items-center gap-2 font-montserrat shadow-sm">
                                    <span class="w-2 h-2 bg-secondary rounded-full animate-pulse"></span>
                                    🔴 Live Now
                                </span>
                            @elseif($isUpcoming)
                                <span
                                    class="bg-primary/20 text-primary px-3 py-2 rounded-full text-sm font-semibold font-montserrat shadow-sm">
                                    Coming Soon
                                </span>
                            @else
                                <span
                                    class="bg-gray-100 text-gray-800 px-3 py-2 rounded-full text-sm font-semibold font-montserrat shadow-sm">
                                    Event Ended
                                </span>
                            @endif

                            <!-- Countdown Timer -->
                            @if ($isUpcoming)
                                <div class="flex items-center gap-2 text-sm text-gray-600 font-lato">
                                    <i data-lucide="clock" class="w-4 h-4 text-secondary"></i>
                                    <span id="countdown" class="font-semibold text-xs sm:text-sm"></span>
                                </div>
                            @endif
                        </div>
                    </div>

                    <!-- Event Cover Image -->
                    <div class="rounded-2xl overflow-hidden shadow-xl border border-gray-200">
                        <img src="{{ asset('storage/' . $event->program_cover) }}" alt="{{ $event->title }}"
                            class="w-full h-48 sm:h-80 lg:h-150 object-cover">
                    </div>

                    <!-- Event Description -->
                    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 md:p-8">
                        <h2
                            class="text-xl md:text-2xl font-bold text-primary mb-4 md:mb-6 flex items-center gap-3 font-montserrat">
                            <div class="w-1 h-6 md:h-8 bg-secondary rounded-full"></div>
                            Event Description
                        </h2>
                        <div class="prose max-w-none text-gray-700 leading-relaxed font-lato event-description">
                            {!! $event->description !!}
                        </div>
                    </div>

                    <!-- Event Speakers -->
                    @if ($event->speakers && $event->speakers->count())
                        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 md:p-8">
                            <h2
                                class="text-xl md:text-2xl font-bold text-primary mb-4 md:mb-6 flex items-center gap-3 font-montserrat">
                                <div class="w-1 h-6 md:h-8 bg-secondary rounded-full"></div>
                                Event Speakers
                            </h2>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 md:gap-6">
                                @foreach ($event->speakers as $speaker)
                                    <div
                                        class="flex items-start gap-4 p-4 bg-gray-50 rounded-xl border border-gray-200 hover:shadow-md transition-all duration-300 group">
                                        <div class="flex-shrink-0">
                                            @if (!empty($speaker->photo) && file_exists(public_path('storage/' . $speaker->photo)))
                                                <img src="{{ asset('storage/' . $speaker->photo) }}"
                                                    alt="{{ $speaker->user->name }}"
                                                    class="w-12 h-12 md:w-16 md:h-16 rounded-full object-cover border-2 border-white shadow-sm group-hover:scale-105 transition-transform duration-300">
                                            @else
                                                <img src="https://ui-avatars.com/api/?name={{ urlencode($speaker->user->name) }}&background=00275E&color=fff"
                                                    alt="{{ $speaker->user->name }}"
                                                    class="w-12 h-12 md:w-16 md:h-16 rounded-full object-cover border-2 border-white shadow-sm group-hover:scale-105 transition-transform duration-300">
                                            @endif
                                        </div>
                                        <div class="flex-1 min-w-0">
                                            <h3
                                                class="font-bold text-primary text-base md:text-lg mb-1 font-montserrat">
                                                {{ $speaker->user->name }}
                                            </h3>
                                            @if ($speaker->title)
                                                <p
                                                    class="text-secondary font-semibold text-xs md:text-sm mb-2 font-montserrat">
                                                    {{ $speaker->title }}
                                                </p>
                                            @endif
                                            @if ($speaker->bio)
                                                <p
                                                    class="text-gray-600 text-xs md:text-sm leading-relaxed line-clamp-2 font-lato">
                                                    {{ $speaker->bio }}
                                                </p>
                                            @endif
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

                <!-- Sidebar -->
                <div class="space-y-6">
                    <!-- Registration Card -->
                    <div class="bg-gradient-to-br from-primary to-secondary rounded-2xl shadow-xl p-6 text-white">
                        <h3 class="text-xl font-bold text-center mb-6 text-white font-montserrat">Register For Event
                        </h3>

                        <div class="space-y-4 mb-6">
                            <div class="flex justify-between items-center py-3 border-b border-white/30">
                                <span class="text-white/90 font-medium text-sm font-lato">Slots Remainng</span>
                                <span
                                    class="font-bold text-lg text-white font-montserrat">{{ $event->slotsRemaining() }}</span>
                            </div>
                            <div class="flex justify-between items-center py-3 border-b border-white/30">
                                <span class="text-white/90 font-medium text-sm font-lato">Cost</span>
                                <span class="font-bold text-2xl text-white font-montserrat">Free</span>
                            </div>
                        </div>

                        <button @if ($event->isRegistered() || $event->getRevokeCount() == 4) disabled @endif data-modal-target="popup-modal"
                            data-modal-toggle="popup-modal"
                            class="w-full bg-white text-primary py-4 px-6 rounded-xl font-bold text-lg hover:bg-gray-100 disabled:opacity-50 disabled:cursor-not-allowed transition-all duration-300 transform hover:-translate-y-1 shadow-lg hover:shadow-xl flex items-center justify-center gap-3 font-montserrat">
                            @if ($event->isRegistered())
                                <i data-lucide="check-circle" class="w-5 h-5 text-accent"></i>
                                <span>Already Registered</span>
                            @elseif($event->getRevokeCount() == 4)
                                <i data-lucide="users" class="w-5 h-5 text-gray-500"></i>
                                <span>Max Registrations</span>
                            @else
                                <i data-lucide="handshake" class="w-5 h-5 text-secondary"></i>
                                <span>Register Now</span>
                            @endif
                        </button>

                        @if ($event->is_allowing_application)
                            <a href="{{ URL::signedRoute('event.speakers.apply', [$event]) }}"
                                class="w-full border border-white text-white py-3 px-6 rounded-xl font-semibold hover:bg-white hover:text-primary transition-all duration-300 flex items-center justify-center gap-3 mt-4 font-montserrat">
                                <i data-lucide="mic" class="w-5 h-5"></i>
                                Apply as Speaker
                            </a>
                        @endif

                        <p class="text-white/80 text-sm text-center mt-4 font-lato">You must be logged in to register
                            for this event</p>
                    </div>

                    <!-- Event Info Card -->
                    <div class="bg-white rounded-2xl shadow-lg border border-gray-100 p-6">
                        <h4 class="font-bold text-primary text-lg mb-4 font-montserrat">Event Details</h4>
                        <div class="space-y-4">
                            <!-- Start Time -->
                            <div class="flex items-start gap-3">
                                <div
                                    class="w-8 h-8 bg-primary/10 rounded-lg flex items-center justify-center flex-shrink-0">
                                    <i data-lucide="clock" class="w-4 h-4 text-primary"></i>
                                </div>
                                <div class="flex-1 min-w-0">
                                    <p class="font-semibold text-primary text-sm font-montserrat">Start Time</p>
                                    <p class="text-gray-700 text-sm font-lato">
                                        {{ Carbon::parse($event->start_date)->format('g:i A') }}
                                    </p>
                                    <p class="text-gray-600 text-xs font-lato">
                                        {{ Carbon::parse($event->start_date)->format('F j, Y') }}
                                    </p>
                                </div>
                            </div>

                            <!-- End Time -->
                            <div class="flex items-start gap-3">
                                <div
                                    class="w-8 h-8 bg-primary/10 rounded-lg flex items-center justify-center flex-shrink-0">
                                    <i data-lucide="flag" class="w-4 h-4 text-primary"></i>
                                </div>
                                <div class="flex-1 min-w-0">
                                    <p class="font-semibold text-primary text-sm font-montserrat">End Time</p>
                                    <p class="text-gray-700 text-sm font-lato">
                                        {{ Carbon::parse($event->end_date)->format('g:i A') }}
                                    </p>
                                    <p class="text-gray-600 text-xs font-lato">
                                        {{ Carbon::parse($event->end_date)->format('F j, Y') }}
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
                                                <a href="{{ $event->location }}"
                                                    class="text-secondary font-semibold hover:underline break-all text-sm font-lato"
                                                    target="_blank">
                                                    Click to Join Meeting
                                                </a>
                                            @elseif($event->mode == 'hybrid')
                                                <p class="text-gray-700 text-sm break-words font-lato">
                                                    {{ $event->physical_address }}
                                                </p>
                                                <a href="{{ $event->location }}"
                                                    class="text-secondary font-semibold hover:underline break-all text-sm font-lato"
                                                    target="_blank">
                                                    Click to Join Meeting
                                                </a>
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

                    <!-- Quick Actions Card -->
                    <div class="bg-gradient-to-br from-primary to-secondary rounded-2xl shadow-lg p-6 text-white">
                        <h4 class="font-bold text-lg mb-4 text-white font-montserrat">Quick Actions</h4>
                        <div class="space-y-3">
                            <a href="{{ route('events.index') }}"
                                class="flex items-center gap-3 p-3 bg-white/10 rounded-lg hover:bg-white/20 transition-all duration-300 font-lato group">
                                <i data-lucide="calendar"
                                    class="w-5 h-5 text-accent group-hover:scale-110 transition-transform"></i>
                                <span class="font-medium">View All Events</span>
                            </a>
                            <a href="{{ route('homepage') }}"
                                class="flex items-center gap-3 p-3 bg-white/10 rounded-lg hover:bg-white/20 transition-all duration-300 font-lato group">
                                <i data-lucide="home"
                                    class="w-5 h-5 text-accent group-hover:scale-110 transition-transform"></i>
                                <span class="font-medium">Back to Home</span>
                            </a>
                            @auth
                                <a href="{{ route('user.events') }}"
                                    class="flex items-center gap-3 p-3 bg-white/10 rounded-lg hover:bg-white/20 transition-all duration-300 font-lato group">
                                    <i data-lucide="list"
                                        class="w-5 h-5 text-accent group-hover:scale-110 transition-transform"></i>
                                    <span class="font-medium">My Events</span>
                                </a>
                            @endauth
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Registration Modal -->
    <div id="popup-modal" tabindex="-1"
        class="hidden overflow-y-auto overflow-x-hidden fixed top-0 right-0 left-0 z-50 justify-center items-center w-full md:inset-0 h-[calc(100%-1rem)] max-h-full bg-black/50 backdrop-blur-sm">
        <div class="relative p-4 w-full max-w-md max-h-full">
            <div class="relative bg-white rounded-2xl shadow-xl border border-gray-100">
                <button type="button"
                    class="absolute top-3 end-2.5 text-gray-400 bg-transparent hover:bg-gray-100 hover:text-gray-900 rounded-lg text-sm w-8 h-8 ms-auto inline-flex justify-center items-center transition-colors"
                    data-modal-hide="popup-modal">
                    <i data-lucide="x" class="w-4 h-4"></i>
                    <span class="sr-only">Close modal</span>
                </button>
                <div class="p-6 text-center">
                    <div class="w-16 h-16 bg-secondary/20 rounded-full flex items-center justify-center mx-auto mb-4">
                        <i data-lucide="handshake" class="w-8 h-8 text-secondary"></i>
                    </div>
                    <h3 class="mb-5 text-lg font-normal text-gray-600 font-lato leading-relaxed">
                        You're about to register for:
                        <span class="font-semibold text-primary block mt-2 font-montserrat">{{ $event->title }}</span>
                        We look forward to seeing you at the event!
                    </h3>
                    <form action="{{ route('events.join', $event->slug) }}" x-data="{ loading: false }"
                        x-on:submit="loading=true" method="post">
                        @csrf
                        <div class="flex flex-col sm:flex-row gap-3 justify-center">
                            <button data-modal-hide="popup-modal" type="submit" :disabled="loading"
                                class="text-white bg-secondary hover:bg-primary focus:ring-4 focus:outline-none focus:ring-secondary/50 font-medium rounded-lg text-sm px-5 py-2.5 text-center transition-all duration-300 font-montserrat flex items-center justify-center gap-2 min-w-[140px]">
                                <span x-show="!loading" class="flex items-center gap-2">
                                    <i data-lucide="check" class="w-4 h-4"></i>
                                    Yes, register me
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
                                class="py-2.5 px-5 text-sm font-medium text-gray-900 focus:outline-none bg-white rounded-lg border border-gray-200 hover:bg-gray-100 hover:text-gray-700 transition-colors font-montserrat">
                                No, cancel
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
