<x-app-layout>
    <div class="">
        <!-- Page Header -->
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 mb-8">
            <div class="flex items-center gap-3">
                <div>
                    <h1 class="text-2xl font-extrabold">Create New Event</h1>
                    <p class="text-sm text-gray-500 mt-1">Fill in the details to create a new event.</p>
                </div>
            </div>

            <a href="{{ route('admin.events.index') }}"
                class="inline-flex items-center px-4 py-2 border border-gray-300 rounded-lg text-sm font-medium bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-orange-600 transition shadow-sm">
                <i data-lucide="arrow-left" class="w-4 h-4 mr-2"></i>
                Back to Events
            </a>
        </div>

        <!-- Form Card -->
        <div class="bg-white border border-gray-100 rounded-xl shadow-sm p-6 md:p-8">
            <form action="{{ route('admin.events.store') }}" method="POST" enctype="multipart/form-data"
                class="space-y-8">
                @csrf

                <!-- Section: Basic Event Info -->
                <div>
                    <h3 class="text-lg font-semibold text-gray-900 mb-4 pb-2 border-b border-gray-200">Event Basics
                    </h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 md:gap-2">
                        <x-input name="title" label="Event Title" icon="type" :value="old('title')" />
                        <x-select name="mode" label="Event Mode" required :options="[
                            'online' => 'Online',
                            'offline' => 'Offline',
                            'hybrid' => 'Hybrid'
                        ]" :selected="old('mode')" icon="activity" />
                    </div>
                </div>

                <!-- Section: Date & Location -->
                <div>
                    <h3 class="text-lg font-semibold text-gray-900 mb-4 pb-2 border-b border-gray-200">Date &
                        Location</h3>
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                        <x-input name="start_date" label="Start Date & Time" type="datetime-local" icon="calendar"
                            :value="old('start_date')" />
                        <x-input name="end_date" label="End Date & Time" type="datetime-local" icon="calendar-check"
                            :value="old('end_date')" />
                        <x-input name="location" label="Location (Meeting Link or Venue Name)" icon="map-pin"
                            :value="old('location')" placeholder="Paste meeting link" />
                        <x-input name="physical_address" label="Full Physical Address (if offline/hybrid)"
                            icon="map" :value="old('physical_address')" placeholder="Street, City, State, Country" />
                    </div>
                </div>

                <!-- Section: Contact & Pricing -->
                <div>
                    <h3 class="text-lg font-semibold text-gray-900 mb-4 pb-2 border-b border-gray-200">✉️ Contact &
                        Pricing</h3>
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                        <x-input name="contact_email" label="Contact Email" type="email" icon="mail"
                            :value="old('contact_email')" placeholder="organizer@event.com" />
                        <x-input name="entry_fee" label="Entry Fee (₦)" type="number" min="0" step="0.01"
                            icon="credit-card" :value="old('entry_fee', 0)" placeholder="0.00" />
                    </div>
                </div>

                <!-- Section: Media -->
                <div>
                    <h3 class="text-lg font-semibold text-gray-900 mb-4 pb-2 border-b border-gray-200">Cover Image
                    </h3>
                    <div>
                        <label for="program_cover" class="block text-sm font-medium text-gray-700 mb-2">Upload Cover
                            Image (JPG/PNG)</label>
                        <input type="file" name="program_cover" id="program_cover" accept=".jpg,.jpeg,.png,.JPG,.PNG"
                            class="block w-full text-sm text-gray-900 bg-gray-50 rounded-lg border border-gray-300 focus:ring-2 focus:ring-orange-600 focus:border-orange-600
                            file:mr-4 file:py-2 file:px-4 file:rounded-md file:border-0 file:text-sm file:font-medium
                            file:bg-orange-600 file:text-white hover:file:bg-orange-700 transition" />
                        <p class="mt-2 text-xs text-gray-500">Recommended size: 1200x600px. Max file size: 5MB.</p>
                    </div>
                </div>

                <!-- Section: Description -->
                <div>
                    <h3 class="text-lg font-semibold text-gray-900 mb-4 pb-2 border-b border-gray-200">📝 Event
                        Description</h3>
                    <div>
                        <label for="description"
                            class="block text-sm font-medium text-gray-700 mb-2">Description</label>
                        <textarea name="description" id="description" rows="6"
                            class="block w-full p-3 text-sm text-gray-900 bg-gray-50 rounded-lg border border-gray-300 focus:ring-2 focus:ring-orange-600 focus:border-orange-600 resize-none"
                            placeholder="Describe your event, audience, highlights, and what attendees can expect...">{{ old('description') }}</textarea>
                        <x-input-error :messages="$errors->get('description')" class="mt-1" />
                        <p class="mt-2 text-xs text-gray-500">Use clear, engaging language. Markdown or HTML not
                            supported.</p>
                    </div>
                </div>

                <!-- Section: Options -->
                <div>
                    <h3 class="text-lg font-semibold text-gray-900 mb-4 pb-2 border-b border-gray-200">Event Options
                    </h3>
                    <div class="flex flex-wrap items-center gap-6 p-4 bg-gray-50 rounded-lg">
                        <label class="inline-flex items-center">
                            <input type="checkbox" name="is_active"
                                class="rounded border-gray-300 text-orange-600 focus:ring-orange-600"
                                {{ old('is_active') ? 'checked' : '' }}>
                            <span class="ml-2 text-sm font-medium text-gray-700">Active</span>
                        </label>

                        <label class="inline-flex items-center">
                            <input type="checkbox" name="is_published"
                                class="rounded border-gray-300 text-orange-600 focus:ring-orange-600"
                                {{ old('is_published') ? 'checked' : '' }}>
                            <span class="ml-2 text-sm font-medium text-gray-700">Published</span>
                        </label>

                        <label class="inline-flex items-center">
                            <input type="checkbox" name="is_allowing_application"
                                class="rounded border-gray-300 text-orange-600 focus:ring-orange-600"
                                {{ old('is_allowing_application') ? 'checked' : '' }}>
                            <span class="ml-2 text-sm font-medium text-gray-700">Allow Speaker Applications</span>
                        </label>
                    </div>
                    <p class="mt-2 text-xs text-gray-500">Active = visible internally. Published = visible to public.
                        Speaker applications can be managed separately.</p>
                </div>

                <!-- Hidden Fields -->
                <input type="hidden" name="creator_id" value="{{ auth()->id() }}" />

                <!-- Submit Button -->
                <div class="pt-6 border-t border-gray-200 flex flex-col sm:flex-row justify-end gap-3">
                    <a href="{{ route('admin.events.index') }}"
                        class="px-5 py-2.5 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-lg hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-300 transition">
                        Cancel
                    </a>
                    <button type="submit"
                        class="px-6 py-2.5 inline-flex items-center text-sm font-medium text-white bg-orange-600 rounded-lg hover:bg-orange-700 focus:ring-4 focus:ring-blue-300 focus:outline-none transition">
                        <i data-lucide="save" class="w-4 h-4 mr-2"></i>
                        Save Event
                    </button>
                </div>
            </form>
        </div>
    </div>

    <script>
        const mode = document.getElementById('mode');
        const locationInput = document.getElementById('location');
        const physicalAddressInput = document.getElementById('physical_address');
        // Helper function to toggle visibility and enable/disable inputs
        function toggleInputs(modeValue) {
            // Hide and disable both inputs initially
            locationInput.hidden = true;
            locationInput.querySelector('input').disabled = true;
            physicalAddressInput.hidden = true;
            physicalAddressInput.querySelector('input').disabled = true;

            // Show and enable inputs based on the selected mode
            if (modeValue === 'offline') {
                physicalAddressInput.hidden = false;
                physicalAddressInput.querySelector('input').disabled = false;
            } else if (modeValue === 'online') {
                locationInput.hidden = false;
                locationInput.querySelector('input').disabled = false;
            } else if (modeValue === 'hybrid') {
                locationInput.hidden = false;
                locationInput.querySelector('input').disabled = false;
                physicalAddressInput.hidden = false;
                physicalAddressInput.querySelector('input').disabled = false;
            }
        }

        // Set initial state based on the pre-selected mode
        toggleInputs(mode.value);

        // Add event listener for mode changes
        mode.addEventListener('change', () => {
            toggleInputs(mode.value);
        });
    </script>
</x-app-layout>
