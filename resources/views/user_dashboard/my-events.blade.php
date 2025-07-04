<x-app-layout>

    <div class="grid md:grid-cols-3 gap-3">
        @if(count($events))
            @foreach ($events as $event)
                <div class="bg-white rounded-lg shadow border border-teal-100 p-5 flex flex-col justify-between">
                    <div>
                        <span class="text-xs rounded-full inline-block px-2 py-1 {{ $event->status == 'Upcoming' ? 'bg-blue-100 text-blue-700' : 'bg-red-100 text-red-700' }}">
                            {{ $event->status}}
                        </span>
                        <div class="flex items-center justify-between">
                            <h3 class="text-lg font-semibold text-gray-800">{{ $event->theme }}</h3>
                        </div>
                        <!-- <p class="text-sm text-gray-600 mt-2">
                                            {{ $event->description ?? 'No description available.' }}
                                        </p> -->
                        <p class="text-sm text-gray-500 mt-1">
                            <strong>Date:</strong> {{$event->start_date }}
                        </p>
                        <p class="text-sm text-gray-500">
                            <strong>Location:</strong> {{ $event->location ?? 'Not Determined Yet' }}
                        </p>
                    </div>
                    <div class="mt-4 flex justify-between items-center">
                        <a href="{{ route('events.show', $event->slug) }}"
                            class="text-sm text-teal-700 hover:underline font-medium">
                            View Details
                        </a>
                        <button
                        @if($event->status == 'ended')
                        disabled
                        class="text-gray-600 text-xs italic font-mono"
                        @endif
                        onclick="window.dispatchEvent(new CustomEvent('revoke-event', {
                                detail: {
                                payload: {{ $event }}
                                }
                                }))" class="text-sm text-red-600 hover:underline font-medium">
                            Cancel Attendance
                        </button>
                    </div>
                </div>
            @endforeach
        @else
        <div class="col-span-3 text-center py-10 text-gray-500 flex flex-col items-center justify-center">
            <svg xmlns="http://www.w3.org/2000/svg" class="mx-auto mb-3" width="40" height="40" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
            <circle cx="12" cy="12" r="10"/>
            <path d="M9 10h.01"/>
            <path d="M15 10h.01"/>
            <path d="M8 15c1.333-1 2.667-1 4 0"/>
            </svg>
            You have not registered for any events yet.
            <a href="{{ route('events.index') }}" class="mt-4 inline-block text-teal-700 hover:underline font-medium">
            View Available Events
            </a>
        </div>
        @endif
        <x-modal event='revoke-event' title="Cancel Attendance" description="Are you sure you no longer want to attend this event?">
            <form :action="`/events/user/${payload.slug}/revoke-rsvp`" method="POST">
            @csrf
            @method('DELETE')
            <div class="my-3">
                <p class="text-sm text-gray-700">
                    <strong>Event:</strong> <span id="event-theme" x-text="payload.theme"></span>
                </p>
                <p class="text-sm text-gray-700">
                    <strong>Start Date:</strong> <span id="event-date" x-text="payload.start_date"></span>
                </p>
            </div>
            </script>
            <button type="submit" class="bg-red-600 cursor-pointer text-white block w-full px-4 py-2 rounded-lg hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-red-500 focus:ring-offset-2">
                Confirm Cancellation
            </button>
            </form>
        </x-modal>
    </div>
</x-app-layout>
