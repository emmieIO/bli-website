<x-app-layout>
    <div class="grid lg:grid-cols-3 md:grid-cols-1 gap-6" x-data="{ showModal: false, selectedEvent: null }">
        @if (count($events))
            @foreach ($events as $event)
                <div
                    class="bg-white border border-blue-100 shadow rounded-xl p-5 flex flex-col justify-between space-y-4 hover:shadow-md transition">
                    <!-- Status badge -->
                    <span class="text-xs font-semibold px-2 py-1 rounded-full w-fit
                                {{ $event->status === 'Upcoming' ? 'bg-blue-900 text-white' : 'bg-red-100 text-red-700' }}">
                        {{ $event->status }}
                    </span>

                    <!-- Title -->
                    <h3 class="text-lg font-bold text-blue-900">{{ $event->title }}</h3>

                    <!-- Date & Location -->
                    <div class="text-sm space-y-1">
                        <p class="text-blue-800"><strong>Date:</strong> {{ sweet_date($event->start_date) }}</p>
                        <p class="text-blue-800">
                            <strong>Location:</strong> {{ $event->location ?? 'Not Determined Yet' }}
                        </p>
                    </div>

                    <!-- Actions -->
                    <div class="flex justify-between items-center pt-3 border-t border-blue-100 mt-auto">
                        <a href="{{ route('events.show', $event->slug) }}"
                            class="text-sm text-blue-900 hover:underline font-medium">
                            View Details
                        </a>

                        <button @if ($event->status === 'Ended') disabled @endif @click="selectedEvent = {
                                        slug: '{{ $event->slug }}',
                                        title: '{{ $event->title }}',
                                        start_date: '{{ $event->start_date }}',
                                        end_date: '{{ $event->end_date }}'
                                    }; showModal = true"
                            class="text-sm font-medium text-red-600 hover:underline disabled:text-gray-400 disabled:cursor-not-allowed transition">
                            Cancel Attendance
                        </button>
                    </div>
                </div>
            @endforeach
        @else
            <div class="col-span-3 text-center py-12 text-blue-800 flex flex-col items-center justify-center">
                <i data-lucide="calendar-x" class="w-10 h-10 mb-3 text-blue-400"></i>
                <p>You haven’t registered for any events yet.</p>
                <a href="{{ route('events.index') }}" class="mt-4 inline-block text-blue-900 hover:underline font-medium">
                    Browse Events
                </a>
            </div>
        @endif

        <!-- Cancel RSVP Modal -->
        <div x-show="showModal" x-transition x-cloak
            class="fixed inset-0 z-50 bg-black/50 flex items-center justify-center"
            @click.outside="showModal = false; selectedEvent = null"
            @keydown.escape.window="showModal = false; selectedEvent = null">
            <div class="bg-white rounded-xl p-6 w-full max-w-md mx-4 shadow-lg">
                <div class="flex justify-between items-center mb-4">
                    <h2 class="text-lg font-bold text-blue-900">Cancel Attendance</h2>
                    <button @click="showModal = false; selectedEvent = null" class="text-blue-400 hover:text-blue-600">
                        <i data-lucide="x" class="w-5 h-5"></i>
                    </button>
                </div>
                <p class="text-sm text-blue-800 mb-2">
                    Are you sure you want to cancel your attendance for:
                    <span class="font-medium text-blue-900" x-text="selectedEvent?.title"></span>?
                </p>
                <p class="text-sm text-blue-700 mb-4">
                    Start Date: <span x-text="selectedEvent?.start_date"></span>
                </p>

                <form :action="`/events/user/${selectedEvent.slug}/revoke-rsvp`" method="POST">
                    @csrf
                    @method('DELETE')
                    <button type="submit"
                        class="w-full bg-red-600 text-white px-4 py-2 rounded hover:bg-red-700 transition">
                        Confirm Cancellation
                    </button>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>
