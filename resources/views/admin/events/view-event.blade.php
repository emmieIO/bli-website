<x-app-layout>
    <div class="py-8">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <!-- Header with back button and actions -->
            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 mb-6">
                <div class="flex items-center gap-4">
                    <a href="{{ route('admin.events.index') }}"
                        class="text-primary hover:text-primary-600 transition-colors">
                        <i data-lucide="arrow-left" class="w-5 h-5"></i>
                    </a>
                    <h1 class="text-xl font-bold text-primary">{{ $event->title }}</h1>
                </div>

                <div class="flex items-center gap-3">
                    <a href="{{ route('admin.events.edit', $event->slug) }}"
                        class="flex items-center whitespace-nowrap px-4 py-2 bg-white border border-gray-300 rounded-lg shadow-sm text-sm font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary transition-colors">
                        <i data-lucide="edit" class="w-4 h-4 mr-2"></i>
                        Edit Event
                    </a>
                    <button type="button" data-delete-route="{{ route('admin.events.destroy', $event) }}"
                        data-modal-target="delete-event-modal" data-modal-toggle="delete-event-modal"
                        onclick="confirmEventDelete(this, '{{ $event->title }}')"
                        class="flex whitespace-nowrap items-center px-4 py-2 bg-secondary border border-transparent rounded-lg shadow-sm text-sm font-medium text-white hover:bg-secondary-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-secondary transition-colors">
                        <i data-lucide="trash-2" class="w-4 h-4 mr-2"></i>
                        Delete Event
                    </button>
                </div>
            </div>

            <!-- Main content grid -->
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                <!-- Left column - Event details -->
                <div class="lg:col-span-2 space-y-8">
                    <!-- Event card -->
                    <div class="bg-white shadow rounded-lg overflow-hidden border border-primary-100">
                        <!-- Event cover image -->
                        <div class="h-48 bg-gray-100 overflow-hidden">
                            <img src="{{ asset('storage/' . $event->program_cover) }}" alt="{{ $event->title }}"
                                class="w-full h-full object-cover">
                        </div>

                        <div class="p-6">
                            <!-- Event status badge -->
                            <div class="flex justify-between items-start mb-6">
                                <span
                                    class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-accent-100 text-accent-800 border border-accent-200">
                                    {{ $event->is_active ? 'Published' : 'Not published' }}
                                </span>

                                <span
                                    class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-primary-100 text-primary-800 border border-primary-200">
                                    {{ ucfirst($event->mode) }}
                                </span>
                            </div>

                            <!-- Event description -->
                            <div class="prose max-w-none text-gray-600 mb-6">
                                <p>{!! $event->description !!}</p>
                            </div>

                            <!-- Details grid -->
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 border-t border-gray-200 pt-6 mt-6">
                                <div class="flex items-start gap-3">
                                    <i data-lucide="calendar" class="w-5 h-5 text-primary mt-0.5 flex-shrink-0"></i>
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
                                        <i data-lucide="map-pin" class="w-5 h-5 text-primary mt-0.5 flex-shrink-0"></i>
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
                                        <i data-lucide="link" class="w-5 h-5 text-primary mt-0.5 flex-shrink-0"></i>
                                        <div>
                                            <h3 class="text-sm font-medium text-gray-700">Conference Meeting Link</h3>
                                            <a href="{{ $event->location }}" target="_blank"
                                                class="text-sm text-primary hover:text-primary-600 transition-colors">
                                                Event Link
                                            </a>
                                        </div>
                                    </div>
                                @endif

                                <div class="flex items-start gap-3">
                                    <i data-lucide="mail" class="w-5 h-5 text-primary mt-0.5 flex-shrink-0"></i>
                                    <div>
                                        <h3 class="text-sm font-medium text-gray-700">Contact</h3>
                                        <a href="mailto:{{ $event->contact_email }}"
                                            class="text-sm text-primary hover:text-primary-600 transition-colors">
                                            {{ $event->contact_email }}
                                        </a>
                                    </div>
                                </div>

                                <!-- Entry Fee -->
                                <div class="flex items-start gap-3">
                                    <i data-lucide="credit-card" class="w-5 h-5 text-primary mt-0.5 flex-shrink-0"></i>
                                    <div>
                                        <h3 class="text-sm font-medium text-gray-700">Entry Fee</h3>
                                        <p class="text-sm text-gray-900">
                                            {{ $event->entry_fee > 0 ? '₦' . number_format($event->entry_fee, 2) : 'Free' }}
                                        </p>
                                    </div>
                                </div>

                                <!-- Creator -->
                                <div class="flex items-start gap-3">
                                    <i data-lucide="user-circle" class="w-5 h-5 text-primary mt-0.5 flex-shrink-0"></i>
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
                                    <i data-lucide="clock" class="w-5 h-5 text-primary mt-0.5 flex-shrink-0"></i>
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
                    <div class="bg-white shadow rounded-lg overflow-hidden border border-primary-100">
                        <div class="px-6 py-4 border-b border-gray-200 flex justify-between items-center">
                            <h2 class="text-lg font-medium text-primary flex items-center gap-2">
                                <i data-lucide="mic" class="w-5 h-5 text-primary"></i> Speakers
                            </h2>
                            <button data-modal-target="crud-modal" data-modal-toggle="crud-modal"
                                class="flex items-center gap-2 bg-primary hover:bg-primary-600 text-white text-sm font-medium px-4 py-2 rounded-lg transition-colors">
                                <i data-lucide="user-plus" class="w-4 h-4"></i>
                                Invite Speaker
                            </button>
                        </div>

                        <div class="divide-y divide-gray-200">
                            @if ($event->speakers->count())
                                @foreach ($event->speakers as $speaker)
                                    <div class="p-6 flex items-start gap-4">
                                        <div
                                            class="h-20 w-20 rounded-full border-2 border-primary-100 overflow-hidden flex-shrink-0">
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
                                            <h3 class="font-medium text-primary">{{ $speaker->name }}</h3>
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
                    <div class="bg-white shadow rounded-lg overflow-hidden border border-primary-100">
                        <div class="px-6 py-4 border-b border-gray-200">
                            <h2 class="text-lg font-medium text-primary">Event Statistics</h2>
                        </div>
                        <div class="p-6 space-y-4">
                            <div class="flex items-center justify-between">
                                <div class="text-sm font-medium text-gray-500">Total Registrations</div>
                                <div class="text-lg font-semibold text-primary">{{ $event->attendeesCount() }}</div>
                            </div>
                        </div>
                    </div>

                    <!-- Recent attendees -->
                    <div class="bg-white shadow rounded-lg overflow-hidden border border-primary-100">
                        <div class="px-6 py-4 border-b border-gray-200 flex justify-between items-center">
                            <h2 class="text-lg font-medium text-primary">Recent Registrations</h2>
                            <a href="/admin/events/{{ $event->slug }}/registrations"
                                class="text-sm text-primary hover:text-primary-600 transition-colors">View All</a>
                        </div>
                        <div class="divide-y divide-gray-200">
                            @if ($event->recentRegistrations()->count() > 0)
                                @foreach ($event->recentRegistrations() as $recent)
                                    <div class="p-4 flex items-center gap-3">
                                        <div class="flex-shrink-0">
                                            <img class="h-10 w-10 rounded-full border border-primary-100"
                                                src="https://ui-avatars.com/api/?name={{ urlencode($recent->name) }}"
                                                alt="{{ $recent->name }}">
                                        </div>
                                        <div class="min-w-0 flex-1">
                                            <p class="text-sm font-medium text-primary truncate">{{ $recent->name }}
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
                    <div class="bg-white shadow rounded-lg overflow-hidden border border-primary-100">
                        <div class="px-6 py-4 border-b border-gray-200 flex justify-between items-center">
                            <h2 class="text-lg font-medium text-primary">Event Resources</h2>
                            <a href="{{ route('admin.events.resources.create', $event) }}"
                                class="inline-flex items-center px-3 py-1 bg-primary text-white text-xs font-medium rounded-lg shadow hover:bg-primary-600 transition-colors">
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
                                                <i data-lucide="file-text" class="w-5 h-5 text-secondary"></i>
                                            @elseif(Str::endsWith($extension, 'zip'))
                                                <i data-lucide="archive" class="w-5 h-5 text-primary"></i>
                                            @elseif(Str::endsWith($extension, ['doc', 'docx']))
                                                <i data-lucide="file" class="w-5 h-5 text-gray-500"></i>
                                            @else
                                                <i data-lucide="file" class="w-5 h-5 text-gray-400"></i>
                                            @endif
                                        </div>

                                        <div class="min-w-0 flex-1">
                                            <p class="text-sm font-medium text-primary truncate">
                                                {{ $resource->title }}</p>
                                            @if ($resource->type == 'link')
                                                <a href="{{ $resource->external_link }}" target="_blank"
                                                    class="text-xs text-primary hover:text-primary-600 break-all transition-colors">
                                                    View link resource
                                                </a>
                                            @endif
                                            <p class="text-xs text-gray-500 uppercase">{{ $extension }}</p>
                                        </div>

                                        <div class="flex items-center gap-2 ml-auto">
                                            @if ($resource->file_path && file_exists(public_path('storage/' . $resource->file_path)))
                                                <a href="{{ asset('storage/' . $resource->file_path) }}" download
                                                    class="text-primary hover:text-primary-600 flex items-center transition-colors">
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
                                                    class="text-secondary hover:text-secondary-600 flex items-center transition-colors">
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

        <!-- Speaker invitation modal -->
        <div id="crud-modal" tabindex="-1" aria-hidden="true"
            class="hidden overflow-y-auto overflow-x-hidden fixed top-0 right-0 left-0 z-50 justify-center items-center w-full md:inset-0 h-[calc(100%-1rem)] max-h-full bg-black/50 backdrop-blur-sm">
            <div class="relative p-4 w-full max-w-[700px] max-h-full">
                <div class="relative bg-white rounded-lg shadow border border-primary-100">
                    <!-- Modal header -->
                    <div class="flex items-center justify-between p-4 md:p-5 border-b border-gray-200 rounded-t">
                        <h3 class="text-lg font-semibold text-primary">
                            Invitation to speak at {{ $event->title }}
                        </h3>
                        <button type="button" data-modal-toggle="crud-modal"
                            class="text-gray-400 bg-transparent hover:bg-gray-200 hover:text-primary rounded-lg text-sm w-8 h-8 inline-flex justify-center items-center transition-colors">
                            <i data-lucide="x" class="w-4 h-4"></i>
                            <span class="sr-only">Close modal</span>
                        </button>
                    </div>

                    <!-- Modal body -->
                    <form class="p-4 md:p-5" method="POST"
                        action="{{ route('admin.events.invite-speaker', $event) }}">
                        @csrf
                        <input type="hidden" name="event_id" value="{{ $event->id }}">
                        <div class="grid gap-4 mb-4 grid-cols-2">

                            <!-- Speakers list -->
                            <div
                                class="col-span-2 h-[30vh] rounded-lg overflow-scroll bg-gray-50 border border-gray-200">
                                @foreach ($speakers as $speaker)
                                    <div
                                        class="flex items-center justify-between gap-3 px-4 py-2 border-b border-gray-200">
                                        <div class="flex items-center gap-3">
                                            <input type="radio" name="speaker_id" value="{{ $speaker->id }}"
                                                id="speaker-{{ $speaker->id }}"
                                                class="form-radio h-4 w-4 text-primary focus:ring-primary">
                                            <label for="speaker-{{ $speaker->id }}"
                                                class="flex items-center gap-2 cursor-pointer">
                                                <div
                                                    class="h-8 w-8 rounded-full overflow-hidden flex-shrink-0 border border-primary-100">
                                                    @if (!empty($speaker->photo) && file_exists(public_path('storage/' . $speaker->photo)))
                                                        <img src="{{ asset('storage/' . $speaker->photo) }}"
                                                            alt="{{ $speaker->user->name }}"
                                                            class="w-full h-full object-cover">
                                                    @else
                                                        <img src="https://ui-avatars.com/api/?name={{ urlencode($speaker->name) }}"
                                                            alt="{{ $speaker->name }}"
                                                            class="w-full h-full object-cover">
                                                    @endif
                                                </div>
                                                <div>
                                                    <span
                                                        class="text-sm font-medium text-primary">{{ $speaker->user->name }}</span>
                                                    <span
                                                        class="text-xs text-gray-500 block">{{ $speaker->title }}</span>
                                                </div>
                                            </label>
                                        </div>
                                        <a href="{{ route('admin.speakers.show', $speaker) }}" target="_blank"
                                            class="text-xs text-primary hover:text-primary-600 underline whitespace-nowrap transition-colors">
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
                                    class="block p-2.5 w-full text-sm text-gray-900 bg-gray-50 rounded-lg border border-gray-300 focus:ring-primary focus:border-primary"
                                    placeholder="Enter suggested topic">
                            </div>

                            <!-- Suggested duration -->
                            <div>
                                <label for="suggested_duration"
                                    class="block mb-2 text-sm font-medium text-gray-900">Suggested Duration
                                    (minutes)</label>
                                <input type="number" id="suggested_duration" name="suggested_duration"
                                    class="block p-2.5 w-full text-sm text-gray-900 bg-gray-50 rounded-lg border border-gray-300 focus:ring-primary focus:border-primary"
                                    placeholder="e.g. 45">
                            </div>

                            <!-- Audience expectations -->
                            <div class="col-span-2">
                                <label for="audience_expectations"
                                    class="block mb-2 text-sm font-medium text-gray-900">Audience Expectations</label>
                                <textarea id="audience_expectations" name="audience_expectations" rows="3"
                                    class="block p-2.5 w-full text-sm text-gray-900 bg-gray-50 rounded-lg border border-gray-300 focus:ring-primary focus:border-primary resize-none"
                                    placeholder="Describe what the audience expects from this talk..."></textarea>
                            </div>

                            <!-- Expected format -->
                            <div class="col-span-2">
                                <label for="expected_format"
                                    class="block mb-2 text-sm font-medium text-gray-900">Expected Format</label>
                                <input type="text" id="expected_format" name="expected_format"
                                    class="block p-2.5 w-full text-sm text-gray-900 bg-gray-50 rounded-lg border border-gray-300 focus:ring-primary focus:border-primary"
                                    placeholder="Workshop, keynote, panel, etc.">
                            </div>

                            <!-- Special instructions -->
                            <div class="col-span-2">
                                <label for="special_instructions"
                                    class="block mb-2 text-sm font-medium text-gray-900">Special Instructions</label>
                                <textarea id="special_instructions" name="special_instructions" rows="3"
                                    class="block p-2.5 w-full text-sm text-gray-900 bg-gray-50 rounded-lg border border-gray-300 focus:ring-primary focus:border-primary resize-none"
                                    placeholder="Any other instructions for the speaker..."></textarea>
                            </div>
                        </div>

                        <button type="submit"
                            class="flex items-center gap-2 bg-primary hover:bg-primary-600 text-white font-medium rounded-lg text-sm px-5 py-2.5 transition-colors">
                            <i data-lucide="send" class="w-4 h-4"></i>
                            Send Invitation
                        </button>
                    </form>
                </div>
            </div>
        </div>

        <!-- Delete event modal -->
        <div id="delete-event-modal" tabindex="-1" aria-hidden="true"
            class="hidden overflow-y-auto overflow-x-hidden fixed top-0 right-0 left-0 z-50 justify-center items-center w-full md:inset-0 h-[calc(100%-1rem)] max-h-full bg-black/50 backdrop-blur-sm">
            <div class="relative p-4 w-full max-w-md max-h-full">
                <div class="relative bg-white rounded-lg shadow border border-secondary-100">
                    <!-- Modal header -->
                    <div class="flex items-center justify-between p-4 md:p-5 border-b rounded-t">
                        <h3 class="text-lg font-semibold text-secondary">
                            Confirm Event Deletion
                        </h3>
                        <button type="button" data-modal-hide="delete-event-modal"
                            class="text-gray-400 bg-transparent hover:bg-gray-200 hover:text-secondary rounded-lg text-sm w-8 h-8 inline-flex justify-center items-center transition-colors">
                            <i data-lucide="x" class="w-4 h-4"></i>
                            <span class="sr-only">Close modal</span>
                        </button>
                    </div>
                    <!-- Modal body -->
                    <div class="p-4 md:p-5 space-y-4">
                        <div class="flex flex-col items-center text-center">
                            <div class="w-12 h-12 bg-secondary-100 rounded-full flex items-center justify-center mb-4">
                                <i data-lucide="alert-triangle" class="w-6 h-6 text-secondary"></i>
                            </div>
                            <h3 class="text-lg font-medium text-secondary mb-2">Delete Event</h3>
                            <div class="text-sm text-gray-500">
                                <p>Are you sure you want to delete <span id="event-name"
                                        class="font-semibold text-primary"></span>?</p>
                                <p class="mt-1">This will also remove all associated sessions and speaker
                                    assignments.</p>
                            </div>
                        </div>
                    </div>
                    <!-- Modal footer -->
                    <div class="flex items-center justify-end p-4 md:p-5 border-t border-gray-200 rounded-b gap-3">
                        <button data-modal-hide="delete-event-modal" type="button"
                            class="py-2.5 px-5 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-lg hover:bg-gray-50 transition-colors">
                            Cancel
                        </button>
                        <form method="POST" id="delete-event-form" class="inline">
                            @csrf
                            @method('DELETE')
                            <button type="submit"
                                class="py-2.5 px-5 text-sm font-medium text-white bg-secondary hover:bg-secondary-600 rounded-lg transition-colors inline-flex items-center gap-2">
                                <i data-lucide="trash-2" class="w-4 h-4"></i>
                                Delete Event
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        // Event deletion modal
        window.confirmEventDelete = function(button, eventName) {
            const actionRoute = button.getAttribute('data-delete-route');
            document.getElementById('delete-event-form').action = actionRoute;
            document.getElementById('event-name').textContent = eventName;
        }
    </script>
</x-app-layout>
