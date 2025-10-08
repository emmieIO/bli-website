<x-app-layout>
    <div class="max-w-7xl mx-auto py-10 px-4 sm:px-6 lg:px-8">
        <!-- Header Section -->
        <div class="mb-12 text-center" data-aos="fade-down">
            <h1 class="text-4xl font-bold text-primary font-montserrat tracking-tight">Your Event Invitations</h1>
            <p class="mt-4 text-lg text-gray-600 max-w-2xl mx-auto font-lato leading-relaxed">
                Review and manage the exclusive event invitations you've received from Beacon Leadership Institute
            </p>
        </div>

        @if (empty($invitations))
            <!-- Empty State -->
            <div class="bg-white rounded-2xl shadow-sm border border-primary-100 p-16 text-center max-w-2xl mx-auto" data-aos="fade-up">
                <div class="w-20 h-20 bg-primary/10 rounded-2xl flex items-center justify-center mx-auto mb-6">
                    <i data-lucide="inbox" class="w-10 h-10 text-primary" aria-hidden="true"></i>
                </div>
                <h3 class="text-2xl font-bold text-primary font-montserrat mb-3">No invitations yet</h3>
                <p class="text-gray-600 font-lato leading-relaxed mb-6">
                    You'll see exclusive event invitations here when organizers send them your way.
                </p>
                <a href="{{ route('events.index') }}" 
                   class="inline-flex items-center gap-2 bg-primary hover:bg-primary-600 text-white font-semibold px-6 py-3 rounded-xl transition-all duration-300 transform hover:scale-105 font-montserrat">
                    <i data-lucide="calendar" class="w-4 h-4"></i>
                    Browse Public Events
                </a>
            </div>
        @else
            <!-- Invitations Grid -->
            <div class="grid gap-8 sm:grid-cols-2 lg:grid-cols-3">
                @foreach ($invitations as $invite)
                    @php
                        $isExpired = \Carbon\Carbon::parse($invite->expires_at)->isPast();
                        $status = $isExpired ? 'expired' : $invite->status;
                        $eventDate = \Carbon\Carbon::parse($invite->event_date);
                        $expiresIn = \Carbon\Carbon::parse($invite->expires_at)->diffForHumans();
                    @endphp

                    <div class="bg-white rounded-2xl shadow-lg border border-primary-100 overflow-hidden hover:shadow-2xl transition-all duration-300 hover:-translate-y-2 flex flex-col group"
                         data-aos="fade-up" data-aos-delay="{{ $loop->index * 100 }}">
                        <!-- Card Header -->
                        <div class="p-6 flex-grow">
                            <div class="flex items-start justify-between mb-4">
                                <!-- Event Type Icon -->
                                <div class="p-3 rounded-xl bg-primary/10 text-primary group-hover:bg-primary/20 transition-colors">
                                    <i data-lucide="{{ $invite->event->mode === 'offline' ? 'map-pin' : 'globe' }}"
                                       class="w-5 h-5" aria-hidden="true"></i>
                                </div>

                                <!-- Status Badge -->
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold font-montserrat
                                    @if ($status === 'accepted') bg-accent/10 text-accent border border-accent/20
                                    @elseif($status === 'declined') bg-secondary/10 text-secondary border border-secondary/20
                                    @elseif($status === 'expired') bg-gray-100 text-gray-600 border border-gray-200
                                    @else bg-primary/10 text-primary border border-primary/20 @endif">
                                    <i data-lucide="
                                        @if ($status === 'accepted') check-circle
                                        @elseif($status === 'declined') x-circle
                                        @elseif($status === 'expired') clock
                                        @else mail @endif" 
                                        class="w-3 h-3 mr-1.5">
                                    </i>
                                    {{ ucfirst($status) }}
                                </span>
                            </div>

                            <!-- Event Title -->
                            <h3 class="text-xl font-bold text-primary font-montserrat mb-3 leading-tight group-hover:text-primary-600 transition-colors">
                                {{ Str::limit($invite->event->title, 50) }}
                            </h3>

                            <!-- Event Date -->
                            <div class="flex items-center text-sm text-gray-600 mb-3 font-lato">
                                <i data-lucide="calendar" class="w-4 h-4 mr-2 text-primary/60" aria-hidden="true"></i>
                                {{ $eventDate->format('M j, Y • g:i A') }}
                            </div>

                            <!-- Event Location -->
                            <div class="flex items-start text-sm text-gray-600 mb-4 font-lato">
                                <i data-lucide="map-pin" class="w-4 h-4 mr-2 mt-0.5 flex-shrink-0 text-primary/60"
                                   aria-hidden="true"></i>
                                <span class="break-words leading-relaxed">
                                    {{ $invite->event->mode === 'online' ? $invite->event->location : $invite->event->physical_address }}
                                </span>
                            </div>

                            <!-- Additional Info -->
                            <div class="mt-auto space-y-3">
                                <!-- Expiration -->
                                <div class="flex items-center text-xs text-gray-500 font-lato">
                                    <i data-lucide="timer" class="w-3.5 h-3.5 mr-1.5 text-primary/50" aria-hidden="true"></i>
                                    Expires {{ $expiresIn }}
                                </div>

                                <!-- Suggested Topic -->
                                @if($invite->suggested_topic)
                                    <div class="text-sm text-gray-700 font-lato leading-relaxed bg-primary/5 rounded-lg p-3 border border-primary/10">
                                        <span class="font-semibold text-primary font-montserrat">Suggested Topic:</span>
                                        <span class="block mt-1">{{ Str::limit($invite->suggested_topic, 100) }}</span>
                                    </div>
                                @endif
                            </div>
                        </div>

                        <!-- Action Button -->
                        <div class="px-6 pb-6 pt-4 border-t border-primary-100 bg-gray-50/50">
                            <a href="{{ URL::signedRoute('invitations.show', $invite) }}"
                               class="w-full inline-flex items-center justify-center px-5 py-3 rounded-xl bg-primary hover:bg-primary-600 text-white font-semibold text-sm shadow-md hover:shadow-lg transition-all duration-300 transform hover:scale-105 font-montserrat group/btn">
                                <i data-lucide="eye" class="w-4 h-4 mr-2 group-hover/btn:scale-110 transition-transform" aria-hidden="true"></i>
                                View Invitation Details
                            </a>
                        </div>
                    </div>
                @endforeach
            </div>

            <!-- Footer Stats -->
            <div class="mt-12 bg-white rounded-2xl shadow-sm border border-primary-100 p-6" data-aos="fade-up">
                <div class="grid grid-cols-1 md:grid-cols-4 gap-6 text-center">
                    <div>
                        <div class="text-2xl font-bold text-primary font-montserrat">{{ count($invitations) }}</div>
                        <div class="text-sm text-gray-600 font-lato">Total Invitations</div>
                    </div>
                    <div>
                        <div class="text-2xl font-bold text-accent font-montserrat">
                            {{ $invitations->where('status', 'accepted')->count() }}
                        </div>
                        <div class="text-sm text-gray-600 font-lato">Accepted</div>
                    </div>
                    <div>
                        <div class="text-2xl font-bold text-secondary font-montserrat">
                            {{ $invitations->where('status', 'declined')->count() }}
                        </div>
                        <div class="text-sm text-gray-600 font-lato">Declined</div>
                    </div>
                    <div>
                        <div class="text-2xl font-bold text-primary font-montserrat">
                            {{ $invitations->where('status', 'pending')->count() }}
                        </div>
                        <div class="text-sm text-gray-600 font-lato">Pending</div>
                    </div>
                </div>
            </div>
        @endif
    </div>

    <script>
        lucide.createIcons();
    </script>
</x-app-layout>