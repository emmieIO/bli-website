<x-guest-layout>
    <section class="bg-gray-50 py-12">
        <div class="max-w-6xl mx-auto px-6 grid grid-cols-1 lg:grid-cols-3 gap-10">

            <!-- Main Content -->
            <div class="lg:col-span-2 space-y-6">
                <!-- Description -->
                <div>
                    <h2 class="text-sm font-bold text-teal-800 mb-2 flex items-center gap-2">
                        <i data-lucide="info"></i>
                        About this Event
                    </h2>
                    <h3 class="text-teal-800 text-xl leading-relaxed font-bold">{{ $programme->title }}</h3>

                    <div class="my-5">
                        <img src="{{ asset("storage/$programme->program_cover") ?? asset('images/logo.jpg') }}"
                             alt="{{ $programme->title }}"
                             class="w-full h-full object-cover border border-teal-800 rounded-lg group-hover:brightness-95 transition">
                    </div>

                    <p class="text-gray-700 leading-relaxed">{{ $programme->description }}</p>
                </div>

                <!-- Details -->
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-6 mt-6">
                    <div class="flex items-start gap-3">
                        <i data-lucide="calendar-days" class="text-teal-700 mt-1"></i>
                        <div>
                            <h3 class="text-teal-700 font-semibold">Start Date</h3>
                            <p class="text-gray-800">{{ sweet_date($programme->start_date) }}</p>
                        </div>
                    </div>
                    <div class="flex items-start gap-3">
                        <i data-lucide="calendar-check" class="text-teal-700 mt-1"></i>
                        <div>
                            <h3 class="text-teal-700 font-semibold">End Date</h3>
                            <p class="text-gray-800">{{ sweet_date($programme->end_date) }}</p>
                        </div>
                    </div>
                    <div class="flex items-start gap-3">
                        <i data-lucide="user" class="text-teal-700 mt-1"></i>
                        <div>
                            <h3 class="text-teal-700 font-semibold">Host</h3>
                            <p class="text-gray-800">{{ $programme->creator->name }}</p>
                        </div>
                    </div>
                    <div class="flex items-start gap-3">
                        <i data-lucide="broadcast" class="text-teal-700 mt-1"></i>
                        <div>
                            <h3 class="text-teal-700 font-semibold">Mode</h3>
                            <p class="capitalize text-gray-800">{{ $programme->mode }}</p>
                        </div>
                    </div>
                    <div class="flex items-start gap-3">
                        <i data-lucide="map-pin" class="text-teal-700 mt-1"></i>
                        <div>
                            <h3 class="text-teal-700 font-semibold text-sm">Location</h3>
                            <a href="{{ $programme->location }}" class="text-gray-800 break-words">
                                {{ $programme->location }}
                            </a>
                        </div>
                    </div>
                </div>

                <!-- Metadata -->
                @if (!empty($programme->metadata) && is_array($programme->metadata))
                    <div class="space-y-4">
                        <h3 class="text-teal-800 font-bold text-lg flex items-center gap-2">
                            <i data-lucide="file-text"></i>
                            Additional Information
                        </h3>
                        <div class="lg:grid lg:grid-cols-2 gap-4">
                            @foreach ($programme->metadata as $key => $value)
                                <div>
                                    <strong class="block text-teal-700">
                                        {{ ucfirst(str_replace('_', ' ', $key)) }}:
                                    </strong>
                                    @if (is_array($value))
                                        <ul class="list-disc pl-5 text-gray-700">
                                            @foreach ($value as $item)
                                                <li>{{ $item }}</li>
                                            @endforeach
                                        </ul>
                                    @else
                                        <p class="text-gray-700">{{ $value }}</p>
                                    @endif
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endif
            </div>

            <!-- Sidebar Actions -->
            <div class="bg-white border border-teal-700 rounded-xl shadow-lg p-6 space-y-6 h-fit">
                <div class="space-y-1">
                    <h4 class="text-teal-700 font-bold text-lg flex items-center gap-2">
                        <i data-lucide="party-popper"></i>
                        Ready to Join?
                    </h4>
                    <p class="text-gray-700">
                        Let us know you’re coming. Stay updated and receive reminders.
                    </p>
                </div>

                <!-- Modal -->
                <x-modal
                    event="{{ $programme->slug }}"
                    title="Confirm RSVP"
                    description="Please confirm that you'll be attending this event.">
                    <form method="POST" action="{{ route('events.join', $programme->slug) }}">
                        @csrf
                        <button type="submit"
                                @if ($programme->attendees->contains(auth()->id()))
                                    disabled
                                @endif
                                class="w-full bg-teal-700 text-white py-2 cursor-pointer px-4 rounded-lg hover:bg-teal-800 transition-all flex items-center justify-center gap-2">
                            <i data-lucide="handshake"></i>
                            Proceed
                        </button>
                    </form>
                </x-modal>

                <!-- Trigger Button -->
                <button
                    onclick="window.dispatchEvent(new Event('{{ $programme->slug }}'))"
                    @if ($programme->attendees->contains(auth()->id()))
                        disabled
                    @endif
                    class="w-full bg-teal-700 text-white py-2 cursor-pointer px-4 rounded-lg hover:bg-teal-800 transition-all flex items-center justify-center gap-2">
                    @if ($programme->attendees->contains(auth()->id()))
                    <span class="flex items-center gap-2 text-sm text-teal-100 font-semibold">
                        <i data-lucide="check-circle" class="text-green-600"></i>
                        You are registered for this event
                    </span>
                    @else
                    <i data-lucide="handshake"></i>
                    I’ll be attending
                    @endif
                </button>



                @auth
                    <a href="{{ route('user.events') }}"
                       class="text-center text-teal-700 font-medium hover:underline flex items-center justify-center gap-2">
                        <i data-lucide="external-link"></i>
                        View My Events
                    </a>
                @endauth
            </div>
        </div>
    </section>
</x-guest-layout>
