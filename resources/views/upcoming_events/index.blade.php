<x-guest-layout>
    <!-- Hero Section -->
    <section class="bg-teal-50 text-teal-900 py-12 text-center border-b border-teal-200">
        <div class="max-w-3xl mx-auto px-4">
            <h1 class="text-3xl sm:text-4xl font-semibold mb-3 flex items-center justify-center gap-2">
                <i data-lucide="calendar-days" class="w-7 h-7 text-teal-600"></i>
                Discover Events
            </h1>
            <p class="text-base sm:text-lg font-normal text-teal-700">Stay informed and join us for our latest programs.</p>
        </div>
    </section>

    <!-- Events Section -->
    <section class="py-14 bg-gray-50 min-h-screen relative z-1">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">

            @if(count($events))
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-10">
                @foreach ($events as $event)
                    <div
                        class="flex flex-col bg-white border border-teal-700 shadow-md rounded-xl overflow-hidden transition-transform hover:scale-[1.015] duration-300 group text-sm">

                        <!-- Cover Image -->
                        <img src="{{ asset('storage/' . $event->program_cover) }}" alt="{{ $event->title }}"
                            class="w-full h-36 object-cover group-hover:brightness-95 transition" />

                        <!-- Content -->
                        <div class="p-4 flex flex-col flex-grow">
                            <h3 class="text-lg font-semibold text-gray-900 mb-1">
                                {{ $event->title }}
                            </h3>

                            <p class="text-gray-700 mb-3 flex-grow leading-snug">
                                {{ limit_str($event->description, 70) }}
                                @if(strlen($event->description) > 70)
                                    <a href="{{ route('events.show', $event->slug) }}" class="text-teal-600 hover:underline ml-1">see more</a>
                                @endif
                            </p>

                            <div class="text-xs text-gray-600 space-y-1 mb-3">
                                <div class="flex items-center gap-1">
                                    <i data-lucide="calendar-clock" class="w-4 h-4 text-teal-700"></i>
                                    <span><strong class="text-teal-800">Start:</strong> {{ sweet_date($event->start_date) }}</span>
                                </div>
                                <div class="flex items-center gap-1">
                                    <i data-lucide="calendar-check-2" class="w-4 h-4 text-teal-700"></i>
                                    <span><strong class="text-teal-800">End:</strong> {{ sweet_date($event->end_date) }}</span>
                                </div>
                            </div>

                            <a href="{{ route('events.show', $event->slug) }}"
                                class="mt-auto inline-flex items-center gap-1 bg-teal-700 text-white px-3 py-1.5 rounded-md hover:bg-teal-800 transition text-sm">
                                <span>Details</span>
                                <i data-lucide="move-right" class="w-4 h-4"></i>
                            </a>
                        </div>
                    </div>
                @endforeach

                </div>
            @else
                <div class="text-center py-20">
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
