<x-app-layout>
    <div class="max-w-5xl mx-auto py-8 px-4 sm:px-6 lg:px-8">
        <!-- Page Header -->
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 mb-8">
            <div class="flex items-center gap-3">
                <div class="p-2.5 rounded-lg bg-[#00275E]/10">
                    <i data-lucide="calendar-check" class="w-6 h-6 text-[#00275E]"></i>
                </div>
                <div>
                    <h1 class="text-2xl font-extrabold text-[#00275E]">Update Event</h1>
                    <p class="text-sm text-gray-500 mt-1">Edit event details and manage its settings.</p>
                </div>
            </div>

            <a href="{{ route('admin.events.index') }}"
                class="inline-flex items-center px-4 py-2 border border-gray-300 rounded-lg text-sm font-medium text-[#00275E] bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-[#00275E] transition shadow-sm">
                <i data-lucide="arrow-left" class="w-4 h-4 mr-2"></i>
                Back to Events
            </a>
        </div>

        <!-- Form Card -->
        <div class="bg-white border border-gray-100 rounded-xl shadow-sm p-6 md:p-8">
            <form action="{{ route('admin.events.update', $event) }}" method="POST" enctype="multipart/form-data"
                class="space-y-8">
                @csrf
                @method('PUT')

                <!-- Section: Basic Event Info -->
                <div>
                    <h3 class="text-lg font-semibold text-gray-900 mb-4 pb-2 border-b border-gray-200">📅 Event Basics</h3>
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                        <x-input name="title" label="Event Title" required icon="type" :value="old('title', $event->title)" />
                        <x-select name="mode" label="Event Mode" required :options="[
                            'online' => 'Online',
                            'offline' => 'Offline',
                            'hybrid' => 'Hybrid'
                        ]" :selected="old('mode', $event->mode)" icon="activity" />
                    </div>
                </div>

                <!-- Section: Date & Location -->
                <div>
                    <h3 class="text-lg font-semibold text-gray-900 mb-4 pb-2 border-b border-gray-200">📍 Date & Location</h3>
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                        <x-input name="start_date" label="Start Date & Time" type="datetime-local" required icon="calendar"
                            :value="old('start_date', \Carbon\Carbon::parse($event->start_date)->format('Y-m-d\TH:i'))" />
                        <x-input name="end_date" label="End Date & Time" type="datetime-local" required icon="calendar-check"
                            :value="old('end_date', \Carbon\Carbon::parse($event->end_date)->format('Y-m-d\TH:i'))" />
                        <x-input name="location" label="Location (URL or Venue Name)" required icon="map-pin"
                            :value="old('location', $event->location)" placeholder="e.g. Zoom Link or Conference Hall" />
                        <x-input name="physical_address" label="Full Physical Address (if offline/hybrid)" icon="map"
                            :value="old('physical_address', $event->physical_address)" placeholder="Street, City, State, Country" />
                    </div>
                </div>

                <!-- Section: Contact & Pricing -->
                <div>
                    <h3 class="text-lg font-semibold text-gray-900 mb-4 pb-2 border-b border-gray-200">✉️ Contact & Pricing</h3>
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                        <x-input name="contact_email" label="Contact Email" type="email" icon="mail"
                            :value="old('contact_email', $event->contact_email)" placeholder="organizer@event.com" />
                        <x-input name="entry_fee" label="Entry Fee (₦)" type="number" min="0" step="0.01" icon="credit-card"
                            :value="old('entry_fee', $event->entry_fee)" placeholder="0.00" />
                    </div>
                </div>

                <!-- Section: Media -->
                <div>
                    <h3 class="text-lg font-semibold text-gray-900 mb-4 pb-2 border-b border-gray-200">🖼️ Cover Image</h3>
                    <div>
                        <label for="program_cover" class="block text-sm font-medium text-gray-700 mb-2">Upload New Cover Image (JPG/PNG)</label>
                        <input type="file" name="program_cover" id="program_cover" accept=".jpg,.jpeg,.png,.JPG,.PNG"
                            class="block w-full text-sm text-gray-900 bg-gray-50 rounded-lg border border-gray-300 focus:ring-2 focus:ring-[#00275E] focus:border-[#00275E]
                            file:mr-4 file:py-2 file:px-4 file:rounded-md file:border-0 file:text-sm file:font-medium
                            file:bg-[#00275E] file:text-white hover:file:bg-[#FF0000] transition" />
                        <p class="mt-2 text-xs text-gray-500">Recommended size: 1200x600px. Max file size: 5MB.</p>

                        @if($event->program_cover)
                            <div class="mt-4 p-3 bg-gray-50 rounded-lg border border-dashed border-gray-300">
                                <p class="text-sm font-medium text-gray-700 mb-2">Current Cover Image:</p>
                                <div class="flex items-center gap-3">
                                    <img src="{{ asset('storage/' . $event->program_cover) }}" alt="Event Cover"
                                        class="h-16 w-24 object-cover rounded border">
                                    <a href="{{ asset('storage/' . $event->program_cover) }}" target="_blank"
                                        class="text-sm text-[#00275E] font-medium hover:underline flex items-center">
                                        <i data-lucide="eye" class="w-4 h-4 mr-1"></i>
                                        View Image
                                    </a>
                                </div>
                            </div>
                        @endif
                    </div>
                </div>

                <!-- Section: Description -->
                <div>
                    <h3 class="text-lg font-semibold text-gray-900 mb-4 pb-2 border-b border-gray-200">📝 Event Description</h3>
                    <div>
                        <label for="description" class="block text-sm font-medium text-gray-700 mb-2">Description</label>
                        <textarea name="description" id="description" rows="6"
                            class="block w-full p-3 text-sm text-gray-900 bg-gray-50 rounded-lg border border-gray-300 focus:ring-2 focus:ring-[#00275E] focus:border-[#00275E] resize-none"
                            placeholder="Describe your event, audience, highlights, and what attendees can expect...">{{ old('description', $event->description) }}</textarea>
                        <x-input-error :messages="$errors->get('description')" class="mt-1" />
                        <p class="mt-2 text-xs text-gray-500">Use clear, engaging language. Markdown or HTML not supported.</p>
                    </div>
                </div>

                <!-- Section: Options -->
                <div>
                    <h3 class="text-lg font-semibold text-gray-900 mb-4 pb-2 border-b border-gray-200">⚙️ Event Options</h3>
                    <div class="flex flex-wrap items-center gap-6 p-4 bg-gray-50 rounded-lg">
                        <label class="inline-flex items-center">
                            <input type="checkbox" name="is_active" class="rounded border-gray-300 text-[#00275E] focus:ring-[#00275E]"
                                {{ old('is_active', $event->is_active) ? 'checked' : '' }}>
                            <span class="ml-2 text-sm font-medium text-gray-700">Active</span>
                        </label>

                        <label class="inline-flex items-center">
                            <input type="checkbox" name="is_published" class="rounded border-gray-300 text-[#00275E] focus:ring-[#00275E]"
                                {{ old('is_published', $event->is_published) ? 'checked' : '' }}>
                            <span class="ml-2 text-sm font-medium text-gray-700">Published</span>
                        </label>

                        <label class="inline-flex items-center">
                            <input type="checkbox" name="is_allowing_application" class="rounded border-gray-300 text-[#00275E] focus:ring-[#00275E]"
                                {{ old('is_allowing_application', $event->is_allowing_application) ? 'checked' : '' }}>
                            <span class="ml-2 text-sm font-medium text-gray-700">Allow Speaker Applications</span>
                        </label>
                    </div>
                    <p class="mt-2 text-xs text-gray-500">Active = visible internally. Published = visible to public. Speaker applications can be managed separately.</p>
                </div>

                <!-- Hidden Fields -->
                <input type="hidden" name="creator_id" value="{{ $event->creator_id }}" />

                <!-- Submit Button -->
                <div class="pt-6 border-t border-gray-200 flex flex-col sm:flex-row justify-end gap-3">
                    <a href="{{ route('admin.events.index') }}"
                        class="px-5 py-2.5 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-lg hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-300 transition">
                        Cancel
                    </a>
                    <button type="submit"
                        class="px-6 py-2.5 inline-flex items-center text-sm font-medium text-white bg-[#00275E] rounded-lg hover:bg-[#FF0000] focus:ring-4 focus:ring-blue-300 focus:outline-none transition">
                        <i data-lucide="save" class="w-4 h-4 mr-2"></i>
                        Update Event
                    </button>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>