<x-guest-layout>
    <section class="relative bg-white">
        <!-- Cover Image with Overlay -->
        <div class="relative h-72 overflow-hidden">
            <img src="{{ asset("storage/$programme->program_cover") ?? asset('images/logo.jpg') }}"
                alt="{{ $programme->title }}" class="absolute inset-0 w-full h-full object-cover brightness-75">
            <div class="relative z-10 flex items-center h-full px-6 max-w-6xl mx-auto">
                <div>
                    <h1 class="text-3xl md:text-4xl font-bold text-white mb-2">{{ $programme->title }}</h1>
                    <p class="text-white text-sm">{{ sweet_date($programme->start_date) }}
                        @if ($programme->end_date)
                            to {{ sweet_date($programme->end_date) }}
                        @endif
                    </p>
                </div>
            </div>
        </div>

        <div class="max-w-6xl mx-auto px-6 py-12 grid grid-cols-1 lg:grid-cols-3 gap-10">
            <!-- Main Content -->
            <div class="lg:col-span-2 space-y-8">
                <!-- About -->
                <div>
                    <h2 class="text-xl font-semibold text-teal-800 flex items-center gap-2 mb-3">
                        <i data-lucide="info"></i> About this Event
                    </h2>
                    <p class="text-gray-700 leading-relaxed text-base">{{ $programme->description }}</p>
                </div>

                <!-- Details Grid -->
                <div class="bg-white rounded-xl shadow border border-teal-100 p-6 space-y-6 mt-6">
                    <h3 class="text-lg font-bold text-teal-800 flex items-center gap-2">
                        <i data-lucide="info" class="w-5 h-5"></i> Event Details
                    </h3>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                        <!-- Start Date -->
                        <div class="flex items-start gap-3">
                            <i data-lucide="calendar" class="text-teal-700 mt-1"></i>
                            <div>
                                <h4 class="font-semibold text-teal-700 text-sm">Start Date</h4>
                                <p class="text-gray-800">{{ sweet_date($programme->start_date) }}</p>
                            </div>
                        </div>

                        <!-- End Date -->
                        <div class="flex items-start gap-3">
                            <i data-lucide="calendar-check" class="text-teal-700 mt-1"></i>
                            <div>
                                <h4 class="font-semibold text-teal-700 text-sm">End Date</h4>
                                <p class="text-gray-800">{{ sweet_date($programme->end_date) }}</p>
                            </div>
                        </div>

                        <!-- Host -->
                        <div class="flex items-start gap-3">
                            <i data-lucide="user" class="text-teal-700 mt-1"></i>
                            <div>
                                <h4 class="font-semibold text-teal-700 text-sm">Host</h4>
                                <p class="text-gray-800">{{ $programme->creator->name }}</p>
                            </div>
                        </div>

                        <!-- Mode -->
                        <div class="flex items-start gap-3">
                            <i data-lucide="broadcast" class="text-teal-700 mt-1"></i>
                            <div>
                                <h4 class="font-semibold text-teal-700 text-sm">Mode</h4>
                                <p class="text-gray-800 capitalize">{{ $programme->mode }}</p>
                            </div>
                        </div>

                        <!-- Meeting Link (only for registered users) -->
                        @if (($programme->mode === 'online' || $programme->mode === 'hybrid') && $programme->attendees->contains(auth()->id()))
                            <div class="flex items-start gap-3">
                                <i data-lucide="link" class="text-teal-700 mt-1"></i>
                                <div>
                                    <h4 class="font-semibold text-teal-700 text-sm">Meeting Link</h4>
                                    <a href="{{ $programme->location }}"
                                        class="text-teal-700 hover:underline break-all" target="_blank" rel="noopener">
                                        Join Event
                                    </a>
                                </div>
                            </div>
                        @endif

                        <!-- Venue (if applicable) -->
                        @if ($programme->mode === 'offline' || $programme->mode === 'hybrid')
                            <div class="flex items-start gap-3">
                                <i data-lucide="map-pin" class="text-teal-700 mt-1"></i>
                                <div>
                                    <h4 class="font-semibold text-teal-700 text-sm">Venue</h4>
                                    <p class="text-gray-800">
                                        {{ $programme->venue }}<br>
                                        <span class="text-gray-500">{{ $programme->physical_address }}</span>
                                    </p>
                                </div>
                            </div>
                        @endif

                        <!-- Contact -->
                        @if ($programme->contact_email)
                            <div class="flex items-start gap-3">
                                <i data-lucide="mail" class="text-teal-700 mt-1"></i>
                                <div>
                                    <h4 class="font-semibold text-teal-700 text-sm">Contact</h4>
                                    <a href="mailto:{{ $programme->contact_email }}"
                                        class="text-teal-700 hover:underline">{{ $programme->contact_email }}</a>
                                </div>
                            </div>
                        @endif

                        <!-- Entry Fee -->
                        @if ($programme->entry_fee)
                            <div class="flex items-start gap-3">
                                <i data-lucide="wallet" class="text-teal-700 mt-1"></i>
                                <div>
                                    <h4 class="font-semibold text-teal-700 text-sm">Entry Fee</h4>
                                    <p class="text-gray-800">₦{{ number_format($programme->entry_fee, 2) }}</p>
                                </div>
                            </div>
                        @endif
                    </div>
                </div>


                <!-- Speakers -->
                @if ($programme->speakers->count())
                    <div class="bg-white rounded-xl shadow border border-teal-100 p-6 space-y-6">
                        <h3 class="text-lg font-bold text-teal-800 flex items-center gap-2">
                            <i data-lucide="mic" class="w-5 h-5"></i> Featured Speakers
                        </h3>
                        <div class="space-y-4">
                            @foreach ($programme->speakers as $speaker)
                                <div class="flex items-start gap-4 p-4 bg-gray-50 rounded-lg border border-gray-200">
                                    <div class="h-20 w-20 rounded-full overflow-hidden flex-shrink-0 border">
                                        @if (!empty($speaker->photo) && file_exists(public_path('storage/' . $speaker->photo)))
                                            <img src="{{ asset('storage/' . $speaker->photo) }}"
                                                alt="{{ $speaker->name }}"
                                                class="w-full h-full object-cover rounded-full">
                                        @else
                                            <img src="https://ui-avatars.com/api/?name={{ urlencode($speaker->name) }}"
                                                alt="{{ $speaker->name }}"
                                                class="w-full h-full object-cover rounded-full">
                                        @endif
                                    </div>
                                    <div>
                                        <h4 class="font-semibold text-gray-900">{{ $speaker->name }}</h4>
                                        <p class="text-sm text-teal-700 font-medium">{{ $speaker->title }}</p>
                                        <p class="text-sm text-gray-600 mt-1">{{ $speaker->bio }}</p>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endif

                <!-- Event Resources (visible to registered users only) -->
                @if ($programme->attendees->contains(auth()->id()) && $programme->resources->count())
                    <div class="bg-white rounded-xl shadow border border-teal-100 p-6 space-y-6">
                        <h3 class="text-lg font-bold text-teal-800 flex items-center gap-2">
                            <i data-lucide="folder" class="w-5 h-5"></i> Event Resources
                        </h3>
                        <ul class="space-y-4 text-sm text-gray-800">
                            @foreach ($programme->resources as $resource)
                                <li class="border-b pb-3 last:border-none last:pb-0">
                                    <div class="flex items-center justify-between">
                                        <div>
                                            <p class="font-semibold">{{ $resource->title }}</p>
                                            @if ($resource->description)
                                                <p class="text-gray-600">{{ $resource->description }}</p>
                                            @endif
                                            @if ($resource->type === 'file' && $resource->file_path)
                                                <a href="{{ asset('storage/' . $resource->file_path) }}"
                                                    target="_blank"
                                                    class="text-teal-700 hover:underline inline-flex items-center gap-1 mt-1">
                                                    <i data-lucide="file" class="w-4 h-4"></i> Download File
                                                </a>
                                            @elseif ($resource->external_link)
                                                <a href="{{ $resource->external_link }}" target="_blank"
                                                    class="text-teal-700 hover:underline inline-flex items-center gap-1 mt-1">
                                                    @if ($resource->type === 'link')
                                                        <i data-lucide="external-link" class="w-4 h-4"></i> Visit Link
                                                    @elseif ($resource->type === 'video')
                                                        <i data-lucide="play-circle" class="w-4 h-4"></i> Watch Video
                                                    @elseif ($resource->type === 'slide')
                                                        <i data-lucide="sliders" class="w-4 h-4"></i> View Slides
                                                    @endif
                                                </a>
                                            @endif
                                        </div>
                                        <span class="text-xs bg-gray-100 text-gray-600 px-2 py-1 rounded">
                                            {{ ucfirst($resource->type) }}
                                        </span>
                                    </div>
                                </li>
                            @endforeach
                        </ul>
                    </div>
                @endif
            </div>

            <!-- Sidebar Actions -->
            <div class="bg-white border border-teal-600 rounded-xl shadow p-6 space-y-6 sticky top-20 h-fit">
                <div>
                    <h4 class="text-lg font-bold text-teal-700 flex items-center gap-2 mb-1">
                        <i data-lucide="party-popper" class="w-5 h-5"></i> Join the Event
                    </h4>
                    <p class="text-gray-600 text-sm">Let us know you're coming. We'll keep you updated.</p>
                </div>

                <!-- RSVP Button -->
                <button onclick="window.dispatchEvent(new Event('{{ $programme->slug }}'))"
                    @if ($programme->isRegistered() || $programme->getRevokeCount() == 4) disabled @endif
                    class="w-full bg-teal-700 text-white py-2 px-4 rounded-lg hover:bg-teal-800 transition flex justify-center items-center gap-2 disabled:opacity-70 disabled:cursor-not-allowed">
                    @if ($programme->isCanceled())
                        <i data-lucide="check-circle" class="text-green-500"></i>
                        Register Again
                    @elseif ($programme->isRegistered())
                        <i data-lucide="handshake"></i> You're already registered
                    @elseif($programme->getRevokeCount() == 4)
                        <i data-lucide="check-circle" class="text-green-500"></i>
                        You have reached the maximum number of registrations for this event.
                    @else
                        <i data-lucide="handshake"></i> RSVP Now
                    @endif
                </button>



                @auth
                    <div class="pt-2">
                        <a href="{{ route('user.events') }}"
                            class="flex items-center justify-center text-center text-teal-700 font-medium hover:underline text-sm">
                            <i data-lucide="external-link" class="h-4"></i>
                            <span>View My Events</span>
                        </a>
                    </div>
                @endauth
            </div>
        </div>
    </section>

    <!-- Modal -->
    <x-modal event="{{ $programme->slug }}" title="Confirm RSVP"
        description="Please confirm your attendance for this event.">
        <form method="POST" action="{{ route('events.join', $programme->slug) }}">
            @csrf
            <button type="submit"
                class="w-full mt-3 bg-teal-700 text-white py-2 px-4 rounded-lg hover:bg-teal-800 transition flex justify-center items-center gap-2">
                <i data-lucide="handshake"></i> Confirm Attendance
            </button>
        </form>
    </x-modal>
</x-guest-layout>
