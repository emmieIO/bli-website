<x-app-layout>
    <div class="py-6">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <!-- Header with back button and actions -->
            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 mb-6">
                <div class="flex items-center gap-4">
                    <a href="/admin/events" class="text-gray-400 hover:text-teal-600 transition-colors">
                        <i data-lucide="arrow-left" class="w-5 h-5"></i>
                    </a>
                    <h1 class="text-2xl font-bold text-gray-900">{{ $event->title }}</h1>
                </div>

                <div class="flex items-center gap-3">
                    <a href="{{ route('admin.events.edit', $event->slug) }}"
                        class="inline-flex items-center px-4 py-2 bg-white border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-teal-500">
                        <i data-lucide="edit" class="w-4 h-4 mr-2"></i>
                        Edit Event
                    </a>

                    <form action="{{ route('admin.events.destroy', $event) }}" method="POST">
                        @csrf
                        @method("DELETE")

                        <button type="submit"
                            class="inline-flex items-center px-4 py-2 bg-red-600 border border-transparent rounded-md shadow-sm text-sm font-medium text-white hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500"
                            onclick="return confirm('Are you sure you want to delete this event?')">
                            <i data-lucide="trash-2" class="w-4 h-4 mr-2"></i>
                            Delete Event
                        </button>
                    </form>
                </div>
            </div>

            <!-- Main content grid -->
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                <!-- Left column - Event details -->
                <div class="lg:col-span-2 space-y-6">
                    <!-- Event card -->
                    <div class="bg-white shadow rounded-lg overflow-hidden">
                        <!-- Event cover image -->
                        <div class="h-48 bg-gray-100 overflow-hidden">
                            <img src="{{ asset('storage/'.$event->program_cover) }}"
                                alt="Leadership Conference" class="w-full h-full object-cover">
                        </div>

                        <div class="p-6">
                            <!-- Event status badge -->
                            <div class="flex justify-between items-start mb-4">
                                <span
                                    class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-green-100 text-green-800">
                                    {{ $event->is_active ? 'Published' : "Not published" }}
                                </span>

                                <span
                                    class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-blue-100 text-blue-800">
                                    {{ $event->mode }}
                                </span>
                            </div>

                            <!-- Event description -->
                            <div class="prose max-w-none text-gray-600 mb-6">
                                <p>{{$event->description}}</p>
                            </div>

                            <!-- Details grid -->
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 border-t border-gray-200 pt-4 mt-4">
                                <div class="flex items-start gap-3">
                                    <i data-lucide="calendar" class="w-5 h-5 text-gray-400 mt-0.5 flex-shrink-0"></i>
                                    <div>
                                        <h3 class="text-sm font-medium text-gray-500">Dates</h3>
                                        <p class="text-sm text-gray-900">
                                            {{ sweet_date($event->start_date) }}
                                            <span class="text-gray-500">to</span><br>
                                            {{ sweet_date($event->end_date) }}
                                        </p>
                                    </div>
                                </div>
                                
                                @if($event->mode == 'hybrid' || $event->mode == 'offline')
                                <div class="flex items-start gap-3">
                                    <i data-lucide="map-pin" class="w-5 h-5 text-gray-400 mt-0.5 flex-shrink-0"></i>
                                    <div>
                                        <h3 class="text-sm font-medium text-gray-700">Location/Meeting Link</h3>
                                        <p class="text-sm text-gray-900">
                                            {{ $event->venue }}<br>
                                            <span class="text-gray-500">{{ $event->physical_address }}</span>
                                        </p>
                                    </div>
                                </div>
                                @endif
                                @if($event->mode =='online' || $event->mode == 'hybrid')
                                <div class="flex items-start gap-3">
                                    <i data-lucide="link" class="w-5 h-5 text-gray-400 mt-0.5 flex-shrink-0"></i>
                                    <div>
                                        <h3 class="text-sm font-medium text-gray-700">Conference Meeting Link</h3>
                                        <a href="https://eventbrite.com/leadership-conf-2023" target="_blank"
                                            class="text-sm text-teal-600 hover:text-teal-800">
                                            Event Link
                                        </a>
                                    </div>
                                </div>
                                @endif

                                <div class="flex items-start gap-3">
                                    <i data-lucide="mail" class="w-5 h-5 text-gray-400 mt-0.5 flex-shrink-0"></i>
                                    <div>
                                        <h3 class="text-sm font-medium text-gray-700">Contact</h3>
                                        <a href="mailto:events@leadership.org"
                                            class="text-sm text-teal-600 hover:text-teal-800">
                                            {{ $event->contact_email }}
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Speakers section -->
                    <div class="bg-white shadow rounded-lg overflow-hidden">
                        <div class="px-6 py-4 border-b border-gray-200">
                            <h2 class="text-lg font-medium text-gray-900">Speakers</h2>
                        </div>
                        <div class="divide-y divide-gray-200">
                            <div class="p-6 flex items-start gap-4">
                                <img src="https://randomuser.me/api/portraits/women/44.jpg" alt="Sarah Johnson"
                                    class="w-16 h-16 rounded-full object-cover">
                                <div>
                                    <h3 class="font-medium text-gray-900">Sarah Johnson</h3>
                                    <p class="text-sm text-gray-500">CEO, FutureVision Inc.</p>
                                    <p class="mt-2 text-sm text-gray-600">Sarah has transformed three startups into
                                        billion-dollar companies and will share her insights on disruptive leadership in
                                        changing markets.</p>
                                </div>
                            </div>
                            <div class="p-6 flex items-start gap-4">
                                <img src="https://randomuser.me/api/portraits/men/32.jpg" alt="Michael Chen"
                                    class="w-16 h-16 rounded-full object-cover">
                                <div>
                                    <h3 class="font-medium text-gray-900">Michael Chen</h3>
                                    <p class="text-sm text-gray-500">Head of Product, TechGlobal</p>
                                    <p class="mt-2 text-sm text-gray-600">Michael leads product innovation for one of
                                        the fastest growing tech companies and will discuss building high-performance
                                        teams.</p>
                                </div>
                            </div>
                            <div class="p-6 flex items-start gap-4">
                                <div
                                    class="w-16 h-16 rounded-full bg-gray-200 flex items-center justify-center text-gray-400">
                                    <i data-lucide="user" class="w-8 h-8"></i>
                                </div>
                                <div>
                                    <h3 class="font-medium text-gray-900">Alexis Rodriguez</h3>
                                    <p class="text-sm text-gray-500">Organizational Psychologist</p>
                                    <p class="mt-2 text-sm text-gray-600">Specializing in workplace dynamics and
                                        leadership development with 15 years of consulting experience.</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Right column - Stats and attendees -->
                <div class="space-y-6">
                    <!-- Stats card -->
                    <div class="bg-white shadow rounded-lg overflow-hidden">
                        <div class="px-6 py-4 border-b border-gray-200">
                            <h2 class="text-lg font-medium text-gray-900">Event Statistics</h2>
                        </div>
                        <div class="p-6 space-y-4">
                            <div class="flex items-center justify-between">
                                <div class="text-sm font-medium text-gray-500">Total Registrations</div>
                                <div class="text-lg font-semibold text-gray-900">1,248</div>
                            </div>
                            <div class="flex items-center justify-between">
                                <div class="text-sm font-medium text-gray-500">Attended</div>
                                <div class="text-lg font-semibold text-gray-900">987</div>
                            </div>
                            {{-- <div class="flex items-center justify-between">
                                <div class="text-sm font-medium text-gray-500">Check-in Rate</div>
                                <div class="text-lg font-semibold text-gray-900">79%</div>
                            </div> --}}
                        </div>
                    </div>

                    <!-- Recent attendees -->
                    <div class="bg-white shadow rounded-lg overflow-hidden">
                        <div class="px-6 py-4 border-b border-gray-200 flex justify-between items-center">
                            <h2 class="text-lg font-medium text-gray-900">Recent Registrations</h2>
                            <a href="/admin/events/leadership-conference-2023/registrations"
                                class="text-sm text-teal-600 hover:text-teal-800">View All</a>
                        </div>
                        <div class="divide-y divide-gray-200">
                            <div class="p-4 flex items-center gap-3">
                                <div class="flex-shrink-0">
                                    <img class="h-10 w-10 rounded-full"
                                        src="https://randomuser.me/api/portraits/women/12.jpg" alt="">
                                </div>
                                <div class="min-w-0 flex-1">
                                    <p class="text-sm font-medium text-gray-900 truncate">Emily Parker</p>
                                    <p class="text-sm text-gray-500 truncate">emily.parker@example.com</p>
                                </div>
                                <div class="text-xs text-gray-500">
                                    2 hours ago
                                </div>
                            </div>
                            <div class="p-4 flex items-center gap-3">
                                <div class="flex-shrink-0">
                                    <img class="h-10 w-10 rounded-full"
                                        src="https://randomuser.me/api/portraits/men/45.jpg" alt="">
                                </div>
                                <div class="min-w-0 flex-1">
                                    <p class="text-sm font-medium text-gray-900 truncate">David Wilson</p>
                                    <p class="text-sm text-gray-500 truncate">d.wilson@business.com</p>
                                </div>
                                <div class="text-xs text-gray-500">
                                    5 hours ago
                                </div>
                            </div>
                            <div class="p-4 flex items-center gap-3">
                                <div class="flex-shrink-0">
                                    <div
                                        class="h-10 w-10 rounded-full bg-gray-200 flex items-center justify-center text-gray-400">
                                        <i data-lucide="user" class="w-5 h-5"></i>
                                    </div>
                                </div>
                                <div class="min-w-0 flex-1">
                                    <p class="text-sm font-medium text-gray-900 truncate">Robert Chen</p>
                                    <p class="text-sm text-gray-500 truncate">robert.chen@tech.org</p>
                                </div>
                                <div class="text-xs text-gray-500">
                                    1 day ago
                                </div>
                            </div>
                            <div class="p-4 flex items-center gap-3">
                                <div class="flex-shrink-0">
                                    <img class="h-10 w-10 rounded-full"
                                        src="https://randomuser.me/api/portraits/women/28.jpg" alt="">
                                </div>
                                <div class="min-w-0 flex-1">
                                    <p class="text-sm font-medium text-gray-900 truncate">Sophia Martinez</p>
                                    <p class="text-sm text-gray-500 truncate">s.martinez@company.net</p>
                                </div>
                                <div class="text-xs text-gray-500">
                                    1 day ago
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Event resources -->
                    <div class="bg-white shadow rounded-lg overflow-hidden">
                        <div class="px-6 py-4 border-b border-gray-200">
                            <h2 class="text-lg font-medium text-gray-900">Event Resources</h2>
                        </div>
                        <div class="divide-y divide-gray-200">
                            <div class="p-4">
                                <div class="flex items-center gap-3">
                                    <div class="flex-shrink-0">
                                        <i data-lucide="file-text" class="w-5 h-5 text-red-500"></i>
                                    </div>
                                    <div class="min-w-0 flex-1">
                                        <p class="text-sm font-medium text-gray-900 truncate">Conference Program</p>
                                        <p class="text-xs text-gray-500">PDF • 2.4 MB</p>
                                    </div>
                                    <a href="#" download class="text-teal-600 hover:text-teal-800">
                                        <i data-lucide="download" class="w-5 h-5"></i>
                                    </a>
                                </div>
                            </div>
                            <div class="p-4">
                                <div class="flex items-center gap-3">
                                    <div class="flex-shrink-0">
                                        <i data-lucide="image" class="w-5 h-5 text-blue-500"></i>
                                    </div>
                                    <div class="min-w-0 flex-1">
                                        <p class="text-sm font-medium text-gray-900 truncate">Speaker Headshots</p>
                                        <p class="text-xs text-gray-500">ZIP • 8.7 MB</p>
                                    </div>
                                    <a href="#" download class="text-teal-600 hover:text-teal-800">
                                        <i data-lucide="download" class="w-5 h-5"></i>
                                    </a>
                                </div>
                            </div>
                            <div class="p-4">
                                <div class="flex items-center gap-3">
                                    <div class="flex-shrink-0">
                                        <i data-lucide="file" class="w-5 h-5 text-gray-500"></i>
                                    </div>
                                    <div class="min-w-0 flex-1">
                                        <p class="text-sm font-medium text-gray-900 truncate">Workshop Materials</p>
                                        <p class="text-xs text-gray-500">DOCX • 1.2 MB</p>
                                    </div>
                                    <a href="#" download class="text-teal-600 hover:text-teal-800">
                                        <i data-lucide="download" class="w-5 h-5"></i>
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>