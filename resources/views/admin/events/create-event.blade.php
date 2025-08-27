<x-app-layout>
    <div class="max-w-5xl mx-auto">
        <!-- Page Header -->
        <h2 class="text-2xl font-bold text-[#00275E] flex items-center gap-2 mb-6">
            <i data-lucide="calendar-plus" class="w-6 h-6"></i>
            Create New Event
        </h2>

        <!-- Form -->
        <form action="{{ route('admin.events.store') }}" method="POST" enctype="multipart/form-data"
            class="bg-white border border-gray-100 rounded-xl shadow p-6 space-y-6">
            @csrf

            <!-- Grid Layout for Inputs -->
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                <!-- Event Title -->
                <x-input name="title" label="Event Title" required icon="type" :value="old('title')" />

                <!-- Event Mode -->
                <x-select name="mode" label="Event Mode" required :options="[
                    'online' => 'Online',
                    'offline' => 'Offline',
                    'hybrid' => 'Hybrid'
                ]" :selected="old('mode')" icon="activity" />

                <!-- Start Date -->
                <x-input name="start_date" label="Start Date" type="datetime-local" required icon="calendar"
                    :value="old('start_date')" />

                <!-- End Date -->
                <x-input name="end_date" label="End Date" type="datetime-local" required icon="calendar-check"
                    :value="old('end_date')" />

                <!-- Location (URL or Venue) -->
                <x-input name="location" label="Location (URL or Venue)" required icon="map-pin"
                    :value="old('location')" />

                <!-- Physical Address -->
                <x-input name="physical_address" label="Physical Address" icon="map" :value="old('physical_address')" />

                <!-- Contact Email -->
                <x-input name="contact_email" label="Contact Email" type="email" icon="mail"
                    :value="old('contact_email')" />

                <!-- Entry Fee -->
                <x-input name="entry_fee" label="Entry Fee (₦)" type="number" min="0" step="0.01" icon="credit-card"
                    :value="old('entry_fee')" />

                <!-- Cover Image -->
                <x-input name="program_cover" label="Cover Image" type="file" icon="image" />
            </div>

            <!-- Description -->
            <div>
                <label for="description" class="block text-sm font-medium text-gray-700">Description</label>
                <textarea name="description" id="description" rows="6"
                    class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm focus:ring-[#00275E] focus:border-[#00275E] sm:text-sm p-3"
                    placeholder="Write about the event...">{{ old('description') }}</textarea>
                <x-input-error :messages="$errors->get('description')" class="mt-1" />
            </div>

            <!-- Checkbox Options -->
            <div class="flex flex-wrap gap-4">
                <label class="inline-flex items-center">
                    <input type="checkbox" name="is_active" class="rounded text-[#00275E]" {{ old('is_active') ? 'checked' : '' }}>
                    <span class="ml-2 text-sm text-gray-700">Active</span>
                </label>

                <label class="inline-flex items-center">
                    <input type="checkbox" name="is_published" class="rounded text-[#00275E]" {{ old('is_published') ? 'checked' : '' }}>
                    <span class="ml-2 text-sm text-gray-700">Published</span>
                </label>

                <label class="inline-flex items-center">
                    <input type="checkbox" name="is_allowing_application" class="rounded text-[#00275E]" {{ old('is_allowing_application') ? 'checked' : '' }}>
                    <span class="ml-2 text-sm text-gray-700">Allow Speaker Application</span>
                </label>
            </div>

            <!-- Hidden Creator ID -->
            <input type="hidden" name="creator_id" value="{{ auth()->id() }}">

            <!-- Footer Actions -->
            <div class="pt-4 flex items-center justify-between">
                <a href="{{ route('admin.events.index') }}"
                    class="text-sm text-[#00275E] hover:underline transition-colors">
                    ← Back to Events
                </a>
                <button type="submit"
                    class="inline-flex items-center px-4 py-2 bg-[#00275E] text-white text-sm font-medium rounded hover:bg-[#00275E]/90 transition shadow-sm">
                    <i data-lucide="save" class="w-4 h-4 mr-2"></i>
                    Save Event
                </button>
            </div>
        </form>
    </div>
</x-app-layout>
