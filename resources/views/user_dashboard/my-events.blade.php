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

                            <button
                                class="inline-flex items-center gap-2 text-sm font-medium text-secondary hover:text-secondary-600 disabled:text-gray-400 disabled:cursor-not-allowed transition-all duration-200 font-lato group-hover:scale-105"
                                data-modal-target="cancel-event" data-modal-toggle="cancel-event"
                                data-action-url="{{ route('user.revoke.event', $event->slug) }}"
                                data-event-title="{{ $event->title }}"
                                data-message="Are you sure you want to cancel your attendance for '{{ $event->title }}'? You can re-register later, but you have only {{ 5 - ($event->pivot->revoke_count ?? 0) }} chances remaining."
                                data-icon='<i data-lucide="alert-triangle" class="h-8 w-8 text-secondary"></i>'
                                onclick="populateCancelEventModal(this)" @if ($event->status === 'Ended')
                                disabled
                @endif> <i data-lucide="x-circle" class="w-4 h-4"></i> Cancel
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

    <div id="cancel-event" tabindex="-1"
        class="hidden overflow-y-auto overflow-x-hidden fixed top-0 right-0 left-0 z-50 justify-center items-center w-full md:inset-0 h-[calc(100%-1rem)] max-h-full">
        <div class="relative p-4 w-full max-w-md max-h-full">
            <div class="relative bg-white rounded-lg shadow-sm">
                <button type="button"
                    class="absolute top-3 end-2.5 text-gray-400 bg-transparent hover:bg-gray-200 hover:text-gray-900 rounded-lg text-sm w-8 h-8 ms-auto inline-flex justify-center items-center "
                    data-modal-hide="cancel-event">
                    <svg class="w-3 h-3" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none"
                        viewBox="0 0 14 14">
                        <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="m1 1 6 6m0 0 6 6M7 7l6-6M7 7l-6 6" />
                    </svg>
                    <span class="sr-only">Close modal</span>
                </button>
                <div class="p-4 md:p-5 text-center">
                    <svg class="mx-auto mb-4 text-gray-400 w-12 h-12 " aria-hidden="true"
                        xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 20 20">
                        <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M10 11V6m0 8h.01M19 10a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
                    </svg>
                    <div class="mb-5">
                        <h3 class="text-lg text-gray-800 font-montserrat font-semibold">Are you sure you want to
                            unregister from: <span id="event-title" class="font-bold text-primary">event</span> ?
                        </h3>
                        <p class="font-lato text-sm text-accent font-bold">Staying registered enables us send you
                            notifications & reminders on this
                            event.</p>
                    </div>

                    <form id="event-cancel-form" method="post" x-data="{ processing: false }"
                        x-on-submit="processing = true">
                        @csrf
                        @method('DELETE')
                        <button type="submit" x-disabled="processing"
                            class="text-white bg-red-600 hover:bg-red-800 focus:ring-4 focus:outline-none focus:ring-red-300  font-medium rounded-lg text-sm inline-flex items-center px-5 py-2.5 text-center">
                            <span x-show="processing" data-lucide="loader"></span>
                            Yes, I'm sure
                        </button>
                        <button data-modal-hide="cancel-event" type="button"
                            class="py-2.5 px-5 ms-3 text-sm font-medium text-gray-900 focus:outline-none bg-white rounded-lg border border-gray-200 hover:bg-gray-100 hover:text-blue-700 focus:z-10 focus:ring-4 focus:ring-gray-100">No,
                            cancel</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script>
        const eventTitle = document.getElementById('event-title');
        const eventCancelForm = document.getElementById('event-cancel-form');

        function populateCancelEventModal(button) {
            const actionUrl = button.dataset.actionUrl;
            const eventTitleText = button.dataset.eventTitle; // Assuming you add data-event-title to your button

            eventCancelForm.action = actionUrl;
            eventTitle.textContent = eventTitleText;

            console.log('Action URL:', actionUrl);
            console.log('Event Title:', eventTitleText)


        }
    </script>
</x-app-layout>
