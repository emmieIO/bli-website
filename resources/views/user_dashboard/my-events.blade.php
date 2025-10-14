<x-app-layout>
    <div class="space-y-8">
        <!-- Header Section -->
        <div class="text-center space-y-4" data-aos="fade-down" data-aos-duration="800">
            <h1 class="text-3xl font-bold text-primary font-montserrat">My Events</h1>
            <p class="text-lg text-gray-600 max-w-2xl mx-auto font-lato">
                Manage your event registrations and stay updated with your schedule
            </p>
        </div>

        <!-- Events Grid -->
        <div class="grid lg:grid-cols-2 xl:grid-cols-3 gap-8" x-data="{ showModal: false, selectedEvent: null }">
            @if (count($events))
                @foreach ($events as $event)
                    <div class="group bg-white border border-primary-50 shadow-sm rounded-2xl p-6 flex flex-col justify-between space-y-5 hover:shadow-2xl hover:border-primary-100 transition-all duration-300 transform hover:-translate-y-1"
                        data-aos="fade-up" data-aos-duration="600" data-aos-delay="{{ $loop->index * 100 }}">
                        <!-- Header with Status -->
                        <div class="space-y-4">
                            <div class="flex items-center justify-between">
                                <span
                                    class="text-xs font-semibold px-3 py-1.5 rounded-full font-montserrat
                                            {{ $event->status === 'Upcoming' ? 'bg-accent text-white shadow-md' : 'bg-secondary-100 text-secondary-700' }}">
                                    {{ $event->status }}
                                </span>
                                <div
                                    class="w-2 h-2 rounded-full 
                                            {{ $event->status === 'Upcoming' ? 'bg-accent animate-pulse' : 'bg-secondary-400' }}">
                                </div>
                            </div>

                            <!-- Title -->
                            <h3
                                class="text-xl font-bold text-primary font-montserrat leading-tight group-hover:text-primary-600 transition-colors">
                                {{ $event->title }}
                            </h3>
                        </div>

                        <!-- Event Details -->
                        <div class="space-y-3">
                            <div class="flex items-center gap-3 text-primary-700">
                                <i data-lucide="calendar" class="w-4 h-4 text-primary-500"></i>
                                <span class="font-lato font-medium">{{ sweet_date($event->start_date) }}</span>
                            </div>
                            <div class="flex items-center gap-3 text-primary-700">
                                <i data-lucide="map-pin" class="w-4 h-4 text-primary-500"></i>
                                <span class="font-lato">{{ $event->location ?? 'Location TBA' }}</span>
                            </div>
                        </div>

                        <!-- Actions -->
                        <div class="flex justify-between items-center pt-4 border-t border-primary-100 mt-2">
                            <a href="{{ route('events.show', $event->slug) }}"
                                class="inline-flex items-center gap-2 text-sm text-primary hover:text-primary-600 font-medium font-lato transition-colors group-hover:underline">
                                <i data-lucide="external-link" class="w-4 h-4"></i>
                                View Details
                            </a>

                            <button data-modal-target="action-modal" data-modal-toggle="action-modal"
                                data-action="{{ route('user.revoke.event', $event->slug) }}" data-method="DELETE"
                                data-title="Cancel Attendance"
                                data-message="Are you sure you want to cancel your attendance for '{{ $event->title }}'? You can re-register later, but you have only {{ 5 - ($event->pivot->revoke_count ?? 0) }} chances remaining."
                                data-icon='<i data-lucide="alert-triangle" class="h-8 w-8 text-secondary"></i>'
                                @if ($event->status === 'Ended')
                                disabled
                                @endif
                                @click="selectedEvent = {
                                    slug: '{{ $event->slug }}',
                                    title: '{{ $event->title }}',
                                    start_date: '{{ $event->start_date }}',
                                    end_date: '{{ $event->end_date }}'
                                }; showModal = true"
                class="inline-flex items-center gap-2 text-sm font-medium text-secondary hover:text-secondary-600 disabled:text-gray-400 disabled:cursor-not-allowed transition-all duration-200 font-lato group-hover:scale-105">
                <i data-lucide="x-circle" class="w-4 h-4"></i>
                Cancel
                </button>
        </div>
    </div>
    @endforeach
@else
    <!-- Empty State -->
    <div class="col-span-3 text-center py-16 px-8 bg-gradient-to-br from-primary-50 to-white rounded-2xl border-2 border-dashed border-primary-100"
        data-aos="fade-up" data-aos-duration="800">
        <div class="max-w-md mx-auto space-y-6">
            <div class="w-20 h-20 mx-auto bg-primary-100 rounded-2xl flex items-center justify-center">
                <i data-lucide="calendar-x" class="w-10 h-10 text-primary-400"></i>
            </div>
            <div class="space-y-3">
                <h3 class="text-2xl font-bold text-primary font-montserrat">No Events Yet</h3>
                <p class="text-gray-600 font-lato leading-relaxed">
                    You haven't registered for any events yet. Explore our upcoming events and join the community!
                </p>
            </div>
            <a href="{{ route('events.index') }}"
                class="inline-flex items-center gap-3 bg-primary hover:bg-primary-600 text-white font-semibold px-8 py-4 rounded-xl transition-all duration-200 transform hover:scale-105 shadow-lg hover:shadow-xl font-montserrat">
                <i data-lucide="calendar-plus" class="w-5 h-5"></i>
                Browse All Events
            </a>
        </div>
    </div>
    @endif
    </div>

    <!-- Browse More Events CTA -->
    @if (count($events))
        <div class="text-center pt-8 border-t border-primary-50" data-aos="fade-up" data-aos-duration="600"
            data-aos-delay="300">
            <div class="max-w-md mx-auto space-y-4">
                <p class="text-gray-600 font-lato">Looking for more events to join?</p>
                <a href="{{ route('events.index') }}"
                    class="inline-flex items-center gap-3 bg-gradient-to-r from-primary to-primary-600 hover:from-primary-600 hover:to-primary-700 text-white font-semibold px-8 py-4 rounded-xl transition-all duration-200 transform hover:scale-105 shadow-lg hover:shadow-xl font-montserrat">
                    <i data-lucide="sparkles" class="w-5 h-5"></i>
                    Explore More Events
                </a>
            </div>
        </div>
    @endif
    </div>

    <script>
        lucide.createIcons();
    </script>
</x-app-layout>
