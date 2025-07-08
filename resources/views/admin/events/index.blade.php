<x-app-layout>
    <div class="mb-8">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 mb-6">
            <h2 class="text-2xl font-semibold text-teal-800 flex items-center gap-2">
                <i data-lucide="calendar-days" class="w-5 h-5"></i>
                Events
            </h2>
            <a href="{{ route("admin.events.create") }}"
                class="bg-teal-600 hover:bg-teal-700 text-white py-2 px-4 rounded-lg inline-flex items-center gap-2 transition-colors shadow-sm">
                <i data-lucide="plus-circle" class="w-4 h-4"></i>
                <span class="text-sm">Create Event</span>
            </a>
        </div>

        <!-- Filter & Search Controls -->
        <!-- Enhanced Filter & Search Controls -->
        <div class="bg-white p-5 rounded-xl shadow-xs border border-gray-100 mb-8">
            <div class="flex flex-col md:flex-row md:items-end gap-5">
                <!-- Search Input -->
                <div class="flex-1">
                    <label for="event-search" class="block text-sm font-medium text-gray-700 mb-1">Search Events</label>
                    <div class="relative rounded-md shadow-sm">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <i data-lucide="search" class="h-4 w-4 text-gray-400"></i>
                        </div>
                        <input type="text" id="event-search" placeholder="Search by event name, location..."
                            class="block w-full pl-10 pr-4 py-2.5 border border-gray-200 rounded-lg focus:ring-2 focus:ring-teal-500 focus:border-teal-500 transition-all duration-200">
                    </div>
                </div>

                <!-- Mode Filter -->
                <div class="w-full md:w-64">
                    <label for="event-mode" class="block text-sm font-medium text-gray-700 mb-1">Event Type</label>
                    <div class="relative">
                        <select id="event-mode"
                            class="appearance-none block w-full px-4 py-2.5 border border-gray-200 rounded-lg focus:ring-2 focus:ring-teal-500 focus:border-teal-500 transition-all duration-200 bg-white pr-10">
                            <option value="">All Event Types</option>
                            <option value="online">Online</option>
                            <option value="offline">In-Person</option>
                            <option value="hybrid">Hybrid</option>
                        </select>
                        <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center pr-3">
                            <i data-lucide="chevron-down" class="h-4 w-4 text-gray-400"></i>
                        </div>
                    </div>
                </div>

                <!-- Date Filter (Optional) -->
                <div class="w-full md:w-48">
                    <label for="event-date" class="block text-sm font-medium text-gray-700 mb-1">Date Range</label>
                    <div class="relative">
                        <select id="event-date"
                            class="appearance-none block w-full px-4 py-2.5 border border-gray-200 rounded-lg focus:ring-2 focus:ring-teal-500 focus:border-teal-500 transition-all duration-200 bg-white pr-10">
                            <option value="">All Dates</option>
                            <option value="today">Today</option>
                            <option value="week">This Week</option>
                            <option value="month">This Month</option>
                            <option value="upcoming">Upcoming</option>
                        </select>
                        <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center pr-3">
                            <i data-lucide="calendar" class="h-4 w-4 text-gray-400"></i>
                        </div>
                    </div>
                </div>

                <!-- Reset Button -->
                <button
                    class="h-[42px] px-4 flex items-center text-sm text-gray-600 hover:text-teal-600 transition-colors">
                    <i data-lucide="rotate-ccw" class="h-4 w-4 mr-2"></i>
                    Reset
                </button>
            </div>
        </div>
    </div>

    <!-- Events Grid -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
        @forelse($events as $event)
            <div
                class="bg-white rounded-lg shadow-sm border border-gray-100 hover:shadow-md transition overflow-hidden flex flex-col h-full relative">
                <!-- Card Header with Mode Badge -->
                <div class="p-4 border-b border-gray-100 relative">
                    <!-- Mode Badge positioned absolutely in header -->
                    <span class="absolute -top-2 -left-2 inline-flex items-center px-3 py-1 rounded-full text-xs font-medium {{
            $event->mode === 'online' ? 'bg-blue-100 text-blue-800' :
            ($event->mode === 'offline' ? 'bg-green-100 text-green-800' : 'bg-purple-100 text-purple-800')
                                                            }} capitalize shadow-sm">
                        {{ $event->mode }}
                    </span>

                    <div x-data="{action :false}" class="flex justify-between items-start">
                        <h3 class="font-medium text-teal-800 text-lg pr-6 line-clamp-2">{{ $event->title }}</h3>
                        <button x-on:click="action = !action" class="text-gray-400 hover:text-gray-600 transition-colors">
                            <i data-lucide="more-vertical" class="w-5 h-5"></i>
                        </button>
                        <div x-show="action" @click.away="action = false" x-transition
                            class="absolute right-0 mt-2 w-40 bg-white shadow-teal-800 rounded shadow z-10">
                            <a href="" class="flex w-full items-center px-2 space-x-3 py-2 text-sm hover:bg-gray-100">
                                <i data-lucide="pen" class="size-4"></i>
                                <span>Edit</span>
                            </a>
                            <button class="flex w-full text-red-600 items-center px-2 space-x-3 py-2 text-sm hover:bg-gray-100">
                                <i data-lucide="trash" class="size-4"></i>
                                <span>Delete</span>
                            </button>

                        </div>
                    </div>
                </div>

                <!-- Card Body -->
                <div class="p-4 flex-grow">
                    <div class="flex items-start gap-3 text-sm text-gray-600 mb-4">
                        <i data-lucide="map-pin" class="w-4 h-4 text-gray-400 mt-0.5 flex-shrink-0"></i>
                        <span class="line-clamp-2">{{ $event->location }}</span>
                    </div>

                    <div class="space-y-3">
                        <div class="flex items-start gap-3 text-sm text-gray-500">
                            <i data-lucide="calendar" class="w-4 h-4 text-gray-400 mt-0.5 flex-shrink-0"></i>
                            <div>
                                <div class="text-xs text-gray-400">Start Date</div>
                                <div>{{ sweet_date($event->start_date) }}</div>
                            </div>
                        </div>
                        <div class="flex items-start gap-3 text-sm text-gray-500">
                            <i data-lucide="calendar" class="w-4 h-4 text-gray-400 mt-0.5 flex-shrink-0"></i>
                            <div>
                                <div class="text-xs text-gray-400">End Date</div>
                                <div>{{ sweet_date($event->end_date) }}</div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Card Footer -->
                <div class="px-4 py-3 bg-gray-50 border-t border-gray-100 flex justify-between items-center">
                    <span class="text-xs text-gray-500">
                        Created {{ $event->created_at->diffForHumans() }}
                    </span>
                    <a href="#"
                        class="text-sm font-medium text-teal-600 hover:text-teal-800 transition-colors inline-flex items-center gap-1">
                        View Details
                        <i data-lucide="arrow-right" class="w-4 h-4"></i>
                    </a>
                </div>
            </div>
        @empty
            <div class="col-span-full bg-white p-8 rounded-lg shadow-sm border border-gray-100 text-center">
                <div class="mx-auto w-16 h-16 rounded-full bg-gray-100 flex items-center justify-center mb-4">
                    <i data-lucide="calendar-off" class="w-8 h-8 text-gray-400"></i>
                </div>
                <h3 class="text-lg font-medium text-gray-900 mb-1">No events found</h3>
                <p class="text-gray-500 mb-4">Create your first event to get started</p>
                <a href="#"
                    class="bg-teal-600 hover:bg-teal-700 text-white py-2 px-4 rounded-lg inline-flex items-center gap-2 transition-colors shadow-sm">
                    <i data-lucide="plus-circle" class="w-4 h-4"></i>
                    <span>Create Event</span>
                </a>
            </div>
        @endforelse

    </div>
</x-app-layout>
