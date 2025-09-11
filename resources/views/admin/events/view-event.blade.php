<x-app-layout>
    <div class="py-8">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <!-- Header with back button and actions -->
            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 mb-6">
                <div class="flex items-center gap-4">
                    <a href="/admin/events" class="text-[#00275E] hover:text-teal-600 transition-colors">
                        <i data-lucide="arrow-left" class="w-5 h-5"></i>
                    </a>
                    <h1 class="text-2xl font-bold text-gray-900">{{ $event->title }}</h1>
                </div>

                <div class="flex items-center gap-3">
                    <a href="{{ route('admin.events.edit', $event->slug) }}"
                        class="inline-flex items-center px-4 py-2 bg-white border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-[#00275E]">
                        <i data-lucide="edit" class="w-4 h-4 mr-2"></i>
                        Edit Event
                    </a>

                    <form action="{{ route('admin.events.destroy', $event) }}" method="POST">
                        @csrf
                        @method('DELETE')

                        <button type="submit"
                            class="inline-flex items-center px-4 py-2 bg-[#FF0000] border border-transparent rounded-md shadow-sm text-sm font-medium text-white hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500"
                            onclick="return confirm('Are you sure you want to delete this event?')">
                            <i data-lucide="trash-2" class="w-4 h-4 mr-2"></i>
                            Delete Event
                        </button>
                    </form>
                </div>
            </div>

            <!-- Main content grid -->
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                <!-- Left column - Event details -->
                <div class="lg:col-span-2 space-y-8">
                    <!-- Event card -->
                    <div class="bg-white shadow rounded-lg overflow-hidden">
                        <!-- Event cover image -->
                        <div class="h-48 bg-gray-100 overflow-hidden">
                            <img src="{{ asset('storage/' . $event->program_cover) }}" alt="{{ $event->title }}"
                                class="w-full h-full object-cover">
                        </div>

                        <div class="p-6">
                            <!-- Event status badge -->
                            <div class="flex justify-between items-start mb-6">
                                <span
                                    class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-green-100 text-green-800">
                                    {{ $event->is_active ? 'Published' : 'Not published' }}
                                </span>

                                <span
                                    class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-blue-100 text-blue-800">
                                    {{ ucfirst($event->mode) }}
                                </span>
                            </div>

                            <!-- Event description -->
                            <div class="prose max-w-none text-gray-600 mb-6">
                                <p>{{ $event->description }}</p>
                            </div>

                            <!-- Details grid -->
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 border-t border-gray-200 pt-6 mt-6">
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

                                @if ($event->mode == 'hybrid' || $event->mode == 'offline')
                                    <div class="flex items-start gap-3">
                                        <i data-lucide="map-pin" class="w-5 h-5 text-gray-400 mt-0.5 flex-shrink-0"></i>
                                        <div>
                                            <h3 class="text-sm font-medium text-gray-700">Location/Meeting Link</h3>
                                            <p class="text-sm text-gray-900">
                                                {{ $event->location ?? 'N/A' }}<br>
                                                <span
                                                    class="text-gray-500">{{ $event->physical_address ?? 'N/A' }}</span>
                                            </p>
                                        </div>
                                    </div>
                                @endif

                                @if ($event->mode == 'online' || $event->mode == 'hybrid')
                                    <div class="flex items-start gap-3">
                                        <i data-lucide="link" class="w-5 h-5 text-gray-400 mt-0.5 flex-shrink-0"></i>
                                        <div>
                                            <h3 class="text-sm font-medium text-gray-700">Conference Meeting Link</h3>
                                            <a href="{{ $event->location }}" target="_blank"
                                                class="text-sm text-[#00275E] hover:text-teal-800">
                                                Event Link
                                            </a>
                                        </div>
                                    </div>
                                @endif

                                <div class="flex items-start gap-3">
                                    <i data-lucide="mail" class="w-5 h-5 text-gray-400 mt-0.5 flex-shrink-0"></i>
                                    <div>
                                        <h3 class="text-sm font-medium text-gray-700">Contact</h3>
                                        <a href="mailto:{{ $event->contact_email }}"
                                            class="text-sm text-[#00275E] hover:text-teal-800">
                                            {{ $event->contact_email }}
                                        </a>
                                    </div>
                                </div>

                                <!-- Entry Fee -->
                                <div class="flex items-start gap-3">
                                    <i data-lucide="credit-card" class="w-5 h-5 text-gray-400 mt-0.5 flex-shrink-0"></i>
                                    <div>
                                        <h3 class="text-sm font-medium text-gray-700">Entry Fee</h3>
                                        <p class="text-sm text-gray-900">
                                            {{ $event->entry_fee > 0 ? '₦' . number_format($event->entry_fee, 2) : 'Free' }}
                                        </p>
                                    </div>
                                </div>

                                <!-- Creator -->
                                <div class="flex items-start gap-3">
                                    <i data-lucide="user-circle" class="w-5 h-5 text-gray-400 mt-0.5 flex-shrink-0"></i>
                                    <div>
                                        <h3 class="text-sm font-medium text-gray-700">Created By</h3>
                                        <p class="text-sm text-gray-900">
                                            {{ $event->creator?->name ?? 'Unknown' }}
                                            <span
                                                class="block text-xs text-gray-500">{{ $event->creator?->email }}</span>
                                        </p>
                                    </div>
                                </div>

                                <!-- Created / Updated -->
                                <div class="flex items-start gap-3">
                                    <i data-lucide="clock" class="w-5 h-5 text-gray-400 mt-0.5 flex-shrink-0"></i>
                                    <div>
                                        <h3 class="text-sm font-medium text-gray-700">Timestamps</h3>
                                        <p class="text-sm text-gray-900">
                                            Created: {{ $event->created_at->diffForHumans() }} <br>
                                            Updated: {{ $event->updated_at->diffForHumans() }}
                                        </p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Speakers section -->
                    <div class="bg-white shadow rounded-lg overflow-hidden">
                        <div class="px-6 py-4 border-b border-gray-200 flex justify-between items-center">
                            <h2 class="text-lg font-medium text-gray-900 flex items-center gap-2">
                                <i data-lucide="mic" class="w-5 h-5 text-[#00275E]"></i> Speakers
                            </h2>
                            {{-- <a href="{{ route('admin.events.assign-speakers', $event) }}"
                                class="text-sm text-[#00275E] hover:text-[#FF0000] inline-flex items-center gap-1 font-medium">
                                <i data-lucide="user-plus" class="w-4 h-4"></i>
                                Assign Speakers
                            </a> --}}
                            <button data-modal-target="crud-modal" data-modal-toggle="crud-modal"
                                class="block text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center"
                                type="button">
                                Invite Speaker
                            </button>
                        </div>

                        <div class="divide-y divide-gray-200">
                            @if ($event->speakers->count())
                                @foreach ($event->speakers as $speaker)
                                    <div class="p-6 flex items-start gap-4">
                                        <div
                                            class="h-20 w-20 rounded-full border-2 border-gray-200 overflow-hidden flex-shrink-0">
                                            @if (!empty($speaker->photo) && file_exists(public_path('storage/' . $speaker->photo)))
                                                <img src="{{ asset('storage/' . $speaker->photo) }}"
                                                    alt="{{ $speaker->name }}"
                                                    class="w-full h-full rounded-full object-cover">
                                            @else
                                                <img src="https://ui-avatars.com/api/?name={{ urlencode($speaker->name) }}"
                                                    alt="{{ $speaker->name }}"
                                                    class="w-full h-full rounded-full object-cover">
                                            @endif
                                        </div>
                                        <div>
                                            <h3 class="font-medium text-gray-900">{{ $speaker->name }}</h3>
                                            <p class="text-sm text-gray-500">{{ $speaker->title }}</p>
                                            <p class="mt-2 text-sm text-gray-600">{{ $speaker->bio }}</p>
                                        </div>
                                    </div>
                                @endforeach
                            @else
                                <div class="p-6 text-center text-gray-500 text-sm">
                                    No speakers found for this event.
                                </div>
                            @endif
                        </div>
                    </div>
                </div>

                <!-- Right column - Stats and attendees -->
                <div class="space-y-8">
                    <!-- Stats card -->
                    <div class="bg-white shadow rounded-lg overflow-hidden">
                        <div class="px-6 py-4 border-b border-gray-200">
                            <h2 class="text-lg font-medium text-gray-900">Event Statistics</h2>
                        </div>
                        <div class="p-6 space-y-4">
                            <div class="flex items-center justify-between">
                                <div class="text-sm font-medium text-gray-500">Total Registrations</div>
                                <div class="text-lg font-semibold text-gray-900">{{ $event->attendeesCount() }}</div>
                            </div>
                        </div>
                    </div>

                    <!-- Recent attendees -->
                    <div class="bg-white shadow rounded-lg overflow-hidden">
                        <div class="px-6 py-4 border-b border-gray-200 flex justify-between items-center">
                            <h2 class="text-lg font-medium text-gray-900">Recent Registrations</h2>
                            <a href="/admin/events/{{ $event->slug }}/registrations"
                                class="text-sm text-[#00275E] hover:text-teal-800">View All</a>
                        </div>
                        <div class="divide-y divide-gray-200">
                            @if ($event->recentRegistrations()->count() > 0)
                                @foreach ($event->recentRegistrations() as $recent)
                                    <div class="p-4 flex items-center gap-3">
                                        <div class="flex-shrink-0">
                                            <img class="h-10 w-10 rounded-full"
                                                src="https://ui-avatars.com/api/?name={{ urlencode($recent->name) }}"
                                                alt="{{ $recent->name }}">
                                        </div>
                                        <div class="min-w-0 flex-1">
                                            <p class="text-sm font-medium text-gray-900 truncate">{{ $recent->name }}
                                            </p>
                                            <p class="text-sm text-gray-500 truncate">{{ $recent->email }}</p>
                                        </div>
                                        <div class="text-xs text-gray-500">
                                            {{ \Carbon\Carbon::parse($recent->created_at)->diffForHumans() }}
                                        </div>
                                    </div>
                                @endforeach
                            @else
                                <div class="p-4 text-center text-gray-500 text-sm">
                                    No recent registrations found.
                                </div>
                            @endif
                        </div>
                    </div>

                    <!-- Event resources -->
                    <div class="bg-white shadow rounded-lg overflow-hidden">
                        <div class="px-6 py-4 border-b border-gray-200 flex justify-between items-center">
                            <h2 class="text-lg font-medium text-gray-900">Event Resources</h2>
                            <a href="{{ route('admin.events.resources.create', $event) }}"
                                class="inline-flex items-center px-2 py-1 bg-[#00275E] text-white text-xs font-medium rounded-md shadow hover:bg-teal-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-[#00275E]">
                                <i data-lucide="plus" class="w-4 h-4 mr-1"></i>
                                Add Resource
                            </a>
                        </div>
                        <div class="divide-y divide-gray-200">
                            @forelse ($event->resources as $resource)
                                <div class="p-4">
                                    <div class="flex items-center gap-3">
                                        <div class="flex-shrink-0">
                                            @php
                                                $extension = pathinfo($resource->file_path, PATHINFO_EXTENSION);
                                            @endphp
                                            @if (Str::endsWith($extension, 'pdf'))
                                                <i data-lucide="file-text" class="w-5 h-5 text-red-500"></i>
                                            @elseif(Str::endsWith($extension, 'zip'))
                                                <i data-lucide="archive" class="w-5 h-5 text-blue-500"></i>
                                            @elseif(Str::endsWith($extension, ['doc', 'docx']))
                                                <i data-lucide="file" class="w-5 h-5 text-gray-500"></i>
                                            @else
                                                <i data-lucide="file" class="w-5 h-5 text-gray-400"></i>
                                            @endif
                                        </div>

                                        <div class="min-w-0 flex-1">
                                            <p class="text-sm font-medium text-gray-900 truncate">
                                                {{ $resource->title }}</p>
                                            @if ($resource->type == 'link')
                                                <a href="{{ $resource->external_link }}" target="_blank"
                                                    class="text-xs text-[#00275E] hover:text-teal-800 break-all">
                                                    View link resource
                                                </a>
                                            @endif
                                            <p class="text-xs text-gray-500 uppercase">{{ $extension }}</p>
                                        </div>

                                        <div class="flex items-center gap-2 ml-auto">
                                            @if ($resource->file_path && file_exists(public_path('storage/' . $resource->file_path)))
                                                <a href="{{ asset('storage/' . $resource->file_path) }}" download
                                                    class="text-[#00275E] hover:text-teal-800 flex items-center">
                                                    <i data-lucide="download" class="w-5 h-5"></i>
                                                </a>
                                            @else
                                                <span class="text-xs text-gray-400 italic">No file</span>
                                            @endif

                                            <form
                                                action="{{ route('admin.events.resources.destroy', [$event, $resource]) }}"
                                                method="POST"
                                                onsubmit="return confirm('Are you sure you want to delete this resource?')"
                                                class="inline">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit"
                                                    class="text-[#FF0000] hover:text-red-800 flex items-center">
                                                    <i data-lucide="trash-2" class="w-5 h-5"></i>
                                                </button>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            @empty
                                <div class="p-4 text-center text-gray-500 text-sm">
                                    No resources available for this event.
                                </div>
                            @endforelse
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- speaker invitation modal --}}
        {{-- <div id="crud-modal" tabindex="-1" aria-hidden="true"
            class="hidden overflow-y-auto overflow-x-hidden fixed top-0 right-0 left-0 z-50 justify-center items-center w-full md:inset-0 h-[calc(100%-1rem)] max-h-full">
            <div class="relative p-4 w-full max-w-[700px] max-h-full">
                <!-- Modal content -->
                <div class="relative bg-white rounded-lg shadow-sm">
                    <!-- Modal header -->
                    <div class="flex items-center justify-between p-4 md:p-5 border-b border-gray-200 rounded-t">
                        <h3 class="text-lg font-semibold text-gray-900">
                            Invitation to speak at {{ $event->title }}
                        </h3>
                        <button type="button"
                            class="text-gray-400 bg-transparent hover:bg-gray-200 hover:text-gray-900 rounded-lg text-sm w-8 h-8 ms-auto inline-flex justify-center items-center"
                            data-modal-toggle="crud-modal">
                            <svg class="w-3 h-3" aria-hidden="true" xmlns="http://www.w3.org/2000/svg"
                                fill="none" viewBox="0 0 14 14">
                                <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"
                                    stroke-width="2" d="m1 1 6 6m0 0 6 6M7 7l6-6M7 7l-6 6" />
                            </svg>
                            <span class="sr-only">Close modal</span>
                        </button>
                    </div>
                    <!-- Modal body -->
                    <form class="p-4 md:p-5">
                        <div class="grid gap-4 mb-4 grid-cols-2">
                            <div class="col-span-2 h-[30vh] rounded-lg overflow-scroll bg-gray-200">
                                @foreach ($speakers as $speaker)
                                    <div class="flex items-center gap-3 px-4 py-2 border-b border-gray-300">
                                        <input type="radio" name="speaker_id" value="{{ $speaker->id }}" id="speaker-{{ $speaker->id }}"
                                            class="form-radio h-4 w-4 text-blue-600">
                                        <label for="speaker-{{ $speaker->id }}" class="flex items-center gap-2 cursor-pointer w-full">
                                            <div class="h-8 w-8 rounded-full overflow-hidden flex-shrink-0">
                                                @if (!empty($speaker->photo) && file_exists(public_path('storage/' . $speaker->photo)))
                                                    <img src="{{ asset('storage/' . $speaker->photo) }}" alt="{{ $speaker->name }}"
                                                        class="w-full h-full object-cover">
                                                @else
                                                    <img src="https://ui-avatars.com/api/?name={{ urlencode($speaker->name) }}"
                                                        alt="{{ $speaker->name }}" class="w-full h-full object-cover">
                                                @endif
                                            </div>
                                            <span class="text-sm font-medium text-gray-900">{{ $speaker->name }}</span>
                                            <span class="text-xs text-gray-500">{{ $speaker->title }}</span>
                                        </label>
                                    </div>
                                @endforeach
                            </div>

                            <div class="col-span-2">
                                <label for="invitation-message"
                                    class="block mb-2 text-sm font-medium text-gray-900">Invitation Message
                                    </label>
                                <textarea id="invitation-message" rows="10"
                                    class="block p-2.5 w-full text-sm text-gray-900 bg-gray-50 rounded-lg border border-gray-300 focus:ring-blue-500 focus:border-blue-500 resize-none"
                                    placeholder=""></textarea>
                        </div>
                        <button type="submit"
                            class="text-white flex items-center bg-[#00275E] hover:bg-[#FF0000] focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center">
                            <i data-lucide="plus"></i>
                            <span>Send Invitation</span>
                        </button>
                    </form>
                </div>
            </div>
        </div> --}}
        <div id="crud-modal" tabindex="-1" aria-hidden="true"
            class="hidden overflow-y-auto overflow-x-hidden fixed top-0 right-0 left-0 z-50 justify-center items-center w-full md:inset-0 h-[calc(100%-1rem)] max-h-full">
            <div class="relative p-4 w-full max-w-[700px] max-h-full">
                <!-- Modal content -->
                <div class="relative bg-white rounded-lg shadow-sm">
                    <!-- Modal header -->
                    <div class="flex items-center justify-between p-4 md:p-5 border-b border-gray-200 rounded-t">
                        <h3 class="text-lg font-semibold text-gray-900">
                            Invitation to speak at {{ $event->title }}
                        </h3>
                        <button type="button"
                            class="text-gray-400 bg-transparent hover:bg-gray-200 hover:text-gray-900 rounded-lg text-sm w-8 h-8 ms-auto inline-flex justify-center items-center"
                            data-modal-toggle="crud-modal">
                            <svg class="w-3 h-3" aria-hidden="true" xmlns="http://www.w3.org/2000/svg"
                                fill="none" viewBox="0 0 14 14">
                                <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"
                                    stroke-width="2" d="m1 1 6 6m0 0 6 6M7 7l6-6M7 7l-6 6" />
                            </svg>
                            <span class="sr-only">Close modal</span>
                        </button>
                    </div>

                    <!-- Modal body -->
                    <form class="p-4 md:p-5" method="POST" action="{{ route("admin.events.invite-speaker", $event) }}">
                        @csrf
                        <input type="hidden" name="event_id" value="{{ $event->id }}">
                        <div class="grid gap-4 mb-4 grid-cols-2">

                            <!-- Speakers list -->
                            <div class="col-span-2 h-[30vh] rounded-lg overflow-scroll bg-gray-200">
                                @foreach ($speakers as $speaker)
                                    <div
                                        class="flex items-center justify-between gap-3 px-4 py-2 border-b border-gray-300">
                                        <div class="flex items-center gap-3">
                                            <input type="radio" name="speaker_id" value="{{ $speaker->id }}"
                                                id="speaker-{{ $speaker->id }}"
                                                class="form-radio h-4 w-4 text-blue-600">
                                            <label for="speaker-{{ $speaker->id }}"
                                                class="flex items-center gap-2 cursor-pointer">
                                                <div class="h-8 w-8 rounded-full overflow-hidden flex-shrink-0">
                                                    @if (!empty($speaker->photo) && file_exists(public_path('storage/' . $speaker->photo)))
                                                        <img src="{{ asset('storage/' . $speaker->photo) }}"
                                                            alt="{{ $speaker->name }}"
                                                            class="w-full h-full object-cover">
                                                    @else
                                                        <img src="https://ui-avatars.com/api/?name={{ urlencode($speaker->name) }}"
                                                            alt="{{ $speaker->name }}"
                                                            class="w-full h-full object-cover">
                                                    @endif
                                                </div>
                                                <div>
                                                    <span
                                                        class="text-sm font-medium text-gray-900">{{ $speaker->name }}</span>
                                                    <span
                                                        class="text-xs text-gray-500 block">{{ $speaker->title }}</span>
                                                </div>
                                            </label>
                                        </div>
                                        <a href="{{ route('admin.speakers.show', $speaker) }}" target="_blank"
                                            class="text-xs text-blue-600 hover:text-blue-800 underline whitespace-nowrap">
                                            View Profile
                                        </a>
                                    </div>
                                @endforeach
                            </div>



                            <!-- Suggested topic -->
                            <div>
                                <label for="suggested_topic"
                                    class="block mb-2 text-sm font-medium text-gray-900">Suggested Topic</label>
                                <input type="text" id="suggested_topic" name="suggested_topic"
                                    class="block p-2.5 w-full text-sm text-gray-900 bg-gray-50 rounded-lg border border-gray-300 focus:ring-blue-500 focus:border-blue-500"
                                    placeholder="Enter suggested topic">
                            </div>

                            <!-- Suggested duration -->
                            <div>
                                <label for="suggested_duration"
                                    class="block mb-2 text-sm font-medium text-gray-900">Suggested Duration
                                    (minutes)</label>
                                <input type="number" id="suggested_duration" name="suggested_duration"
                                    class="block p-2.5 w-full text-sm text-gray-900 bg-gray-50 rounded-lg border border-gray-300 focus:ring-blue-500 focus:border-blue-500"
                                    placeholder="e.g. 45">
                            </div>

                            <!-- Audience expectations -->
                            <div class="col-span-2">
                                <label for="audience_expectations"
                                    class="block mb-2 text-sm font-medium text-gray-900">Audience Expectations</label>
                                <textarea id="audience_expectations" name="audience_expectations" rows="3"
                                    class="block p-2.5 w-full text-sm text-gray-900 bg-gray-50 rounded-lg border border-gray-300 focus:ring-blue-500 focus:border-blue-500 resize-none"
                                    placeholder="Describe what the audience expects from this talk..."></textarea>
                            </div>

                            <!-- Expected format -->
                            <div class="col-span-2">
                                <label for="expected_format"
                                    class="block mb-2 text-sm font-medium text-gray-900">Expected Format</label>
                                <input type="text" id="expected_format" name="expected_format"
                                    class="block p-2.5 w-full text-sm text-gray-900 bg-gray-50 rounded-lg border border-gray-300 focus:ring-blue-500 focus:border-blue-500"
                                    placeholder="Workshop, keynote, panel, etc.">
                            </div>

                            <!-- Special instructions -->
                            <div class="col-span-2">
                                <label for="special_instructions"
                                    class="block mb-2 text-sm font-medium text-gray-900">Special Instructions</label>
                                <textarea id="special_instructions" name="special_instructions" rows="3"
                                    class="block p-2.5 w-full text-sm text-gray-900 bg-gray-50 rounded-lg border border-gray-300 focus:ring-blue-500 focus:border-blue-500 resize-none"
                                    placeholder="Any other instructions for the speaker..."></textarea>
                            </div>


                        </div>

                        <button type="submit"
                            class="text-white flex items-center bg-[#00275E] hover:bg-[#FF0000] focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center">
                            <i data-lucide="plus"></i>
                            <span>Send Invitation</span>
                        </button>
                    </form>
                </div>
            </div>
        </div>


    </div>
</x-app-layout>
