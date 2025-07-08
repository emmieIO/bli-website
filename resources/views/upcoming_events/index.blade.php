<x-guest-layout>
    <!-- Hero Section -->
    <section class="relative bg-teal-700 text-white py-20 text-center overflow-hidden">
        <div class="relative z-10 max-w-4xl mx-auto px-4">
            <h1 class="text-4xl sm:text-5xl font-extrabold mb-4 flex items-center justify-center gap-3">
                <i data-lucide="sparkles" class="w-8 h-8 text-white"></i>
                Discover Upcoming Events
            </h1>
            <p class="text-lg sm:text-xl font-light">Get inspired. Stay updated. Join us on amazing journeys.</p>
        </div>
        <div class="absolute inset-0 bg-teal-800/30"></div>
    </section>

    <!-- Events Section -->
    <section class="py-14 bg-gray-50 min-h-screen">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">

            @if(count($events))
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-10">
                    @foreach ($events as $event)
                        <div
                            class="flex flex-col bg-white border border-teal-700 shadow-lg rounded-2xl overflow-hidden transition-transform hover:scale-[1.02] duration-300 group">
                            <!-- Cover Image -->
                            <img src="{{asset("storage/".$event->program_cover)}}" alt="{{ $event->title }}"
                                class="w-full h-48 object-cover group-hover:brightness-95 transition" />

                            <!-- Content -->
                            <div class="p-6 flex flex-col flex-grow">
                                <h3 class="text-xl font-bold text-gray-900 mb-2 flex items-center gap-2">
                                    <!-- <i data-lucide="mic" class="w-5 h-5 text-teal-700"></i> -->
                                    {{ $event->title }}
                                </h3>

                                <p class="text-gray-700 text-sm mb-4 flex-grow">
                                    {{ limit_str($event->description, 70) }}
                                    @if(strlen($event->description) > 70)
                                        <a href="{{ route('events.show', $event->slug) }}"
                                            class="text-teal-600 hover:underline ml-1">see more</a>
                                    @endif
                                </p>

                                <!-- Start Date -->
                                <div class="text-sm text-gray-600 mb-1 flex items-center gap-2">
                                    <i data-lucide="calendar-clock" class="w-4 h-4 text-teal-700"></i>
                                    <span><strong class="text-teal-800">Start:</strong>
                                        {{ sweet_date($event->start_date) }}</span>
                                </div>

                                <!-- End Date -->
                                <div class="text-sm text-gray-600 mb-4 flex items-center gap-2">
                                    <i data-lucide="calendar-check-2" class="w-4 h-4 text-teal-700"></i>
                                    <span><strong class="text-teal-800">End:</strong>
                                        {{ sweet_date($event->end_date) }}</span>
                                </div>

                                <!-- Event Details Button -->
                                <a href="{{ route('events.show', $event->slug) }}"
                                    class="mt-auto inline-flex items-center gap-2 bg-teal-700 text-white px-4 py-2 rounded-lg hover:bg-teal-800 transition">
                                    <span>Event Details</span>
                                    <i data-lucide="move-right"></i>
                                </a>
                            </div>
                        </div>
                    @endforeach
                </div>
            @else
                <div class="text-center py-20 bg-red-500">
                    <i data-lucide="alert-circle" class="mx-auto mb-4 text-gray-400 w-12 h-12"></i>
                    <p class="text-gray-600 text-xl font-medium">No upcoming events found</p>
                </div>
            @endif
            <div class="mt-10 flex justify-center">
                {{ $events->links() }}
            </div>
        </div>
    </section>
</x-guest-layout>
