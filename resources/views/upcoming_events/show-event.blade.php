@php
    use Carbon\Carbon;
@endphp
<x-guest-layout>
    <!-- Breadcrumb Navigation -->
    <section class="py-5 bg-gray-50 border-b">
        <div class="container mx-auto px-4">
            <nav class="breadcrumb">
                <ul class="flex items-center space-x-2 text-sm">
                    <li class="inline">
                        <a href="{{ route('homepage') }}"
                            class="text-orange-600 hover:text-orange-800 transition-colors">Home</a>
                    </li>
                    <li class="inline">
                        <span class="text-gray-400">/</span>
                    </li>
                    <li class="inline">
                        <a href="{{ route('events.index') }}"
                            class="text-orange-600 hover:text-orange-800 transition-colors">Events</a>
                    </li>
                    <li class="inline">
                        <span class="text-gray-400">/</span>
                    </li>
                    <li class="inline">
                        <span class="text-gray-600 truncate max-w-[200px]">{{ $event->title }}</span>
                    </li>
                </ul>
            </nav>
        </div>
    </section>

    <!-- Event Details Section -->
    <section class="py-16">
        <div class="container mx-auto px-4">
            <div class="grid grid-cols-1 lg:grid-cols-4 gap-8">
                <!-- Main Content -->
                <div class="lg:col-span-3 space-y-8">
                    <!-- Event Header -->
                    <div class="space-y-4">
                        <h1 class="text-3xl lg:text-4xl font-bold text-gray-900 leading-tight">{{ $event->title }}</h1>

                        <!-- Event Status Badge -->
                        <div class="flex flex-wrap items-center gap-4">
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
                                    class="bg-red-100 text-red-800 px-3 py-1 rounded-full text-sm font-semibold flex items-center gap-2">
                                    <span class="w-2 h-2 bg-red-500 rounded-full animate-pulse"></span>
                                    Live Now
                                </span>
                            @elseif($isUpcoming)
                                <span
                                    class="bg-orange-100 text-orange-800 px-3 py-1 rounded-full text-sm font-semibold">
                                    Coming Soon
                                </span>
                            @else
                                <span class="bg-gray-100 text-gray-800 px-3 py-1 rounded-full text-sm font-semibold">
                                    Event Ended
                                </span>
                            @endif

                            <!-- Countdown Timer -->
                            @if ($isUpcoming)
                                <div class="flex items-center gap-2 text-sm text-gray-600">
                                    <i class="far fa-clock text-orange-500"></i>
                                    <span id="countdown" class="font-semibold"></span>
                                </div>
                            @endif
                        </div>
                    </div>

                    <!-- Event Cover Image -->
                    <div class="rounded-2xl overflow-hidden shadow-lg">
                        <img src="{{ asset('storage/' . $event->program_cover) }}" alt="{{ $event->title }}"
                            class="w-full h-64 lg:h-96 object-cover">
                    </div>

                    <!-- Event Description -->
                    <div class="bg-white rounded-2xl shadow-sm border p-8">
                        <h2 class="text-2xl font-bold text-gray-900 mb-6 flex items-center gap-3">
                            <div class="w-1 h-8 bg-orange-600 rounded-full"></div>
                            Event Description
                        </h2>
                        <div class="prose max-w-none text-gray-700 leading-relaxed">
                            <p class="text-lg">{{ $event->description }}</p>
                        </div>
                    </div>

                    <!-- Event Speakers -->
                    @if ($event->speakers && $event->speakers->count())
                        <div class="bg-white rounded-2xl shadow-sm border p-8">
                            <h2 class="text-2xl font-bold text-gray-900 mb-6 flex items-center gap-3">
                                <div class="w-1 h-8 bg-orange-600 rounded-full"></div>
                                Event Speakers
                            </h2>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                @foreach ($event->speakers as $speaker)
                                    <div
                                        class="flex items-start gap-4 p-4 bg-gray-50 rounded-xl border border-gray-200 hover:shadow-md transition-shadow">
                                        <div class="flex-shrink-0">
                                            @if (!empty($speaker->photo) && file_exists(public_path('storage/' . $speaker->photo)))
                                                <img src="{{ asset('storage/' . $speaker->photo) }}"
                                                    alt="{{ $speaker->user->name }}"
                                                    class="w-16 h-16 rounded-full object-cover border-2 border-white shadow-sm">
                                            @else
                                                <img src="https://ui-avatars.com/api/?name={{ urlencode($speaker->user->name) }}&background=00275E&color=fff"
                                                    alt="{{ $speaker->user->name }}"
                                                    class="w-16 h-16 rounded-full object-cover border-2 border-white shadow-sm">
                                            @endif
                                        </div>
                                        <div class="flex-1 min-w-0">
                                            <h3 class="font-bold text-gray-900 text-lg mb-1">{{ $speaker->user->name }}
                                            </h3>
                                            @if ($speaker->title)
                                                <p class="text-orange-600 font-semibold text-sm mb-2">
                                                    {{ $speaker->title }}</p>
                                            @endif
                                            @if ($speaker->bio)
                                                <p class="text-gray-600 text-sm leading-relaxed">{{ $speaker->bio }}
                                                </p>
                                            @endif
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    @endif

                    <!-- Share Section -->
                    <div class="bg-white rounded-2xl shadow-sm border p-8">
                        <div class="flex flex-wrap items-center gap-4">
                            <span class="text-gray-700 font-semibold">Share this event:</span>
                            <div class="flex items-center gap-3">
                                <a href="#"
                                    class="w-10 h-10 bg-orange-600 text-white rounded-full flex items-center justify-center hover:bg-orange-700 transition-colors">
                                    <i class="fab fa-facebook-f"></i>
                                </a>
                                <a href="#"
                                    class="w-10 h-10 bg-black text-white rounded-full flex items-center justify-center hover:bg-gray-800 transition-colors">
                                    <i class="fab fa-twitter"></i>
                                </a>
                                <a href="#"
                                    class="w-10 h-10 bg-orange-500 text-white rounded-full flex items-center justify-center hover:bg-orange-600 transition-colors">
                                    <i class="fab fa-linkedin-in"></i>
                                </a>
                                <a href="#"
                                    class="w-10 h-10 bg-green-500 text-white rounded-full flex items-center justify-center hover:bg-green-600 transition-colors">
                                    <i class="fab fa-whatsapp"></i>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Sidebar -->
                <div class="space-y-6">
                    <!-- Registration Card -->
                    <div
                        class="bg-gradient-to-br from-orange-700 via-orange-800 to-orange-900 rounded-2xl shadow-xl p-6 text-white">
                        <h3 class="text-xl font-bold text-center mb-6 text-white">Register For Event</h3>

                        <div class="space-y-4 mb-6">
                            <div class="flex justify-between items-center py-3 border-b border-orange-400/50">
                                <span class="text-orange-100 font-medium">Attendees</span>
                                <span class="font-bold text-lg text-white">{{ count($event->attendees) }}</span>
                            </div>
                            <div class="flex justify-between items-center py-3 border-b border-orange-400/50">
                                <span class="text-orange-100 font-medium">Cost</span>
                                <span class="font-bold text-2xl text-white">Free</span>
                            </div>
                        </div>

                        <button @if ($event->isRegistered() || $event->getRevokeCount() == 4) disabled @endif data-modal-target="registerEventModal"
                            class="w-full bg-white text-orange-700 py-4 px-6 rounded-xl font-bold text-lg hover:bg-gray-100 disabled:opacity-50 disabled:cursor-not-allowed transition-all duration-300 flex items-center justify-center gap-3 shadow-lg hover:shadow-xl">
                            @if ($event->isRegistered())
                                <i class="fas fa-handshake text-green-500"></i>
                                Already Registered
                            @elseif($event->getRevokeCount() == 4)
                                <i class="fas fa-check-circle text-green-500"></i>
                                Max Registrations
                            @else
                                <i class="fas fa-handshake text-orange-600"></i>
                                Register Now
                            @endif
                        </button>

                        @if ($event->is_allowing_application)
                            <a href="{{ URL::signedRoute('event.speakers.apply', [$event]) }}"
                                class="w-full border-2 border-white text-white py-3 px-6 rounded-xl font-semibold hover:bg-white hover:text-orange-700 transition-all duration-300 flex items-center justify-center gap-3 mt-4">
                                <i class="fas fa-microphone"></i>
                                Apply as Speaker
                            </a>
                        @endif

                        <p class="text-orange-200 text-sm text-center mt-4 font-medium">You must be logged in to
                            register for this event</p>
                    </div>

                    <!-- Event Info Card -->
                    <div class="bg-white rounded-2xl shadow-lg border p-6">
                        <h4 class="font-bold text-gray-900 text-lg mb-4">Event Details</h4>
                        <div class="space-y-4">
                            <!-- Start Time -->
                            <div class="flex items-start gap-3">
                                <div
                                    class="w-8 h-8 bg-orange-100 rounded-lg flex items-center justify-center flex-shrink-0">
                                    <i class="far fa-clock text-orange-600 text-sm"></i>
                                </div>
                                <div>
                                    <p class="font-semibold text-gray-900 text-sm">Start Time</p>
                                    <p class="text-gray-700">{{ Carbon::parse($event->start_date)->format('g:i A') }}
                                    </p>
                                    <p class="text-gray-600 text-sm">
                                        {{ Carbon::parse($event->start_date)->format('F j, Y') }}</p>
                                </div>
                            </div>

                            <!-- End Time -->
                            <div class="flex items-start gap-3">
                                <div
                                    class="w-8 h-8 bg-orange-100 rounded-lg flex items-center justify-center flex-shrink-0">
                                    <i class="fas fa-flag text-orange-600 text-sm"></i>
                                </div>
                                <div>
                                    <p class="font-semibold text-gray-900 text-sm">End Time</p>
                                    <p class="text-gray-700">{{ Carbon::parse($event->end_date)->format('g:i A') }}
                                    </p>
                                    <p class="text-gray-600 text-sm">
                                        {{ Carbon::parse($event->end_date)->format('F j, Y') }}</p>
                                </div>
                            </div>

                            <!-- Location -->
                            <div class="flex items-start gap-3">
                                <div
                                    class="w-8 h-8 bg-orange-100 rounded-lg flex items-center justify-center flex-shrink-0">
                                    <i class="fas fa-map-marker-alt text-orange-600 text-sm"></i>
                                </div>
                                <div>
                                    <p class="font-semibold text-gray-900 text-sm">
                                        @if ($event->mode == 'offline')
                                            Venue Address
                                        @else
                                            Meeting Link
                                        @endif
                                    </p>
                                    @if ($event->mode == 'offline')
                                        <p class="text-gray-700">{{ $event->physical_address }}</p>
                                    @elseif($event->mode == 'online')
                                        <a href="{{ $event->location }}"
                                            class="text-orange-600 font-semibold hover:underline break-words"
                                            target="_blank">
                                            Click to Join Meeting
                                        </a>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Quick Actions Card -->
                    <div class="bg-gradient-to-br from-orange-700 via-orange-800 to-orange-900 rounded-2xl shadow-lg p-6 text-white">
                        <h4 class="font-bold text-lg mb-4 text-white">Quick Actions</h4>
                        <div class="space-y-3">
                            <a href="{{ route('events.index') }}"
                                class="flex items-center gap-3 p-3 bg-white/10 rounded-lg hover:bg-white/20 transition-all duration-300">
                                <i class="fas fa-calendar-alt text-orange-300"></i>
                                <span class="font-medium">View All Events</span>
                            </a>
                            <a href="{{ route('homepage') }}"
                                class="flex items-center gap-3 p-3 bg-white/10 rounded-lg hover:bg-white/20 transition-all duration-300">
                                <i class="fas fa-home text-orange-300"></i>
                                <span class="font-medium">Back to Home</span>
                            </a>
                            @auth
                                <a href="{{ route('user.events') }}"
                                    class="flex items-center gap-3 p-3 bg-white/10 rounded-lg hover:bg-white/20 transition-all duration-300">
                                    <i class="fas fa-list text-orange-300"></i>
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
    <div id="registerEventModal"
        class="fixed inset-0 bg-black bg-opacity-50 items-center justify-center z-50 hidden">
        <div class="bg-white rounded-2xl shadow-2xl max-w-md w-full mx-4">
            <form method="POST" action="{{ route('events.join', $event->slug) }}">
                @csrf
                <div class="p-6 border-b border-gray-200">
                    <h3 class="text-xl font-bold text-gray-900">Confirm Attendance</h3>
                </div>
                <div class="p-6">
                    <p class="text-gray-600 mb-4">Are you sure you want to confirm your attendance for <strong
                            class="text-gray-900">{{ $event->title }}</strong>?</p>
                </div>
                <div class="p-6 border-t border-gray-200 flex gap-3">
                    <button type="button" onclick="closeModal('registerEventModal')"
                        class="flex-1 bg-gray-500 text-white py-3 px-6 rounded-lg font-semibold hover:bg-gray-600 transition-colors">
                        Cancel
                    </button>
                    <button type="submit"
                        class="flex-1 bg-orange-600 text-white py-3 px-6 rounded-lg font-semibold hover:bg-orange-700 transition-colors">
                        Confirm Attendance
                    </button>
                </div>
            </form>
        </div>
    </div>

    <script>
        // Modal Functions
        function openModal(modalId) {
            document.getElementById(modalId).classList.remove('hidden');
            document.body.style.overflow = 'hidden';
        }

        function closeModal(modalId) {
            document.getElementById(modalId).classList.add('hidden');
            document.body.style.overflow = 'auto';
        }

        // Countdown Timer
        function startCountdown(targetStartDateTime, targetId, targetEndDateTime) {
            const countdownElement = document.getElementById(targetId);

            function updateCountdown() {
                const target = new Date(targetStartDateTime).getTime();
                const now = new Date().getTime();
                let diff = target - now;

                if (now > new Date(targetEndDateTime).getTime()) {
                    countdownElement.textContent = "Event has ended!";
                    return;
                }

                if (diff <= 0) {
                    countdownElement.textContent = "Event has started!";
                    return;
                }

                const days = Math.floor(diff / (1000 * 60 * 60 * 24));
                diff %= (1000 * 60 * 60 * 24);

                const hours = Math.floor(diff / (1000 * 60 * 60));
                diff %= (1000 * 60 * 60);

                const minutes = Math.floor(diff / (1000 * 60));
                diff %= (1000 * 60);

                const seconds = Math.floor(diff / 1000);

                countdownElement.textContent = `${days}d ${hours}h ${minutes}m ${seconds}s`;
            }

            updateCountdown();
            const timer = setInterval(updateCountdown, 1000);
        }

        // Event Listeners
        document.addEventListener('DOMContentLoaded', () => {
            startCountdown("{{ $event->start_date }}", "countdown", "{{ $event->end_date }}");

            // Modal triggers
            document.querySelectorAll('[data-modal-target]').forEach(button => {
                button.addEventListener('click', (e) => {
                    if (!button.disabled) {
                        openModal(button.getAttribute('data-modal-target'));
                    }
                });
            });

            // Close modal when clicking outside
            document.querySelectorAll('.fixed.inset-0').forEach(modal => {
                modal.addEventListener('click', (e) => {
                    if (e.target === modal) {
                        closeModal(modal.id);
                    }
                });
            });
        });

        // Close modal with Escape key
        document.addEventListener('keydown', (e) => {
            if (e.key === 'Escape') {
                document.querySelectorAll('.fixed.inset-0').forEach(modal => {
                    if (!modal.classList.contains('hidden')) {
                        closeModal(modal.id);
                    }
                });
            }
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
    </style>
</x-guest-layout>
