<x-app-layout>
    @if ($errors->any())
        <div class="mb-4 p-4 bg-red-100 text-red-700 rounded">
            <strong>Whoops!</strong> Please fix the following issues:
            <ul class="mt-2 list-disc list-inside text-sm">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif
    <div class="max-w-5xl mx-auto">
        <h2 class="text-2xl font-bold text-teal-800 flex items-center gap-2 mb-6">
            <i data-lucide="calendar-plus" class="w-6 h-6"></i>
            Create New Event
        </h2>

        <form action="{{ route('admin.events.store') }}" method="POST" enctype="multipart/form-data"
              class="bg-white border border-gray-100 rounded-xl shadow p-6 space-y-6">
            @csrf

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                <x-input name="title" label="Event Title" required icon="type" :value="old('title')" />

                <x-select name="mode" label="Event Mode" required :options="[
                    'online' => 'Online',
                    'offline' => 'Offline',
                    'hybrid' => 'Hybrid'
                ]" :selected="old('mode')" icon="activity" />

                <x-input name="start_date" label="Start Date" type="datetime-local" required icon="calendar" :value="old('start_date')" />
                <x-input name="end_date" label="End Date" type="datetime-local" required icon="calendar-check" :value="old('end_date')" />

                <x-input name="location" label="Location (URL or Venue)" required icon="map-pin" :value="old('location')" />
                <x-input name="physical_address" label="Physical Address" icon="map" :value="old('physical_address')" />
                <x-input name="contact_email" label="Contact Email" type="email" icon="mail" :value="old('contact_email')" />
                <x-input name="entry_fee" label="Entry Fee (₦)" type="number" min="0" step="0.01" icon="credit-card" :value="old('entry_fee')" />
                {{-- <x-input name="slug" label="Slug" required icon="link" :value="old('slug')" /> --}}
                {{-- <x-input name="uuid" label="UUID" required icon="fingerprint" :value="old('uuid', \Illuminate\Support\Str::uuid())" readonly /> --}}

                <x-input name="program_cover" label="Cover Image" type="file" icon="image" />
            </div>

            <div>
                <label for="description" class="block text-sm font-medium text-gray-700">Description</label>
                <textarea name="description" id="description" rows="6"
                          class="mt-1 block border p-3 w-full border-gray-300 rounded-md shadow-sm focus:ring-teal-500 focus:border-teal-500 sm:text-sm"
                          placeholder="Write about the event...">{{ old('description') }}</textarea>
                <x-input-error :messages="$errors->get('description')" class="mt-1" />
            </div>

            <div class="flex items-center gap-4">
                <label class="inline-flex items-center">
                    <input type="checkbox" name="is_active" class="rounded text-teal-600" {{ old('is_active') ? 'checked' : '' }}>
                    <span class="ml-2 text-sm text-gray-700">Active</span>
                </label>

                <label class="inline-flex items-center">
                    <input type="checkbox" name="is_published" class="rounded text-teal-600" {{ old('is_published') ? 'checked' : '' }}>
                    <span class="ml-2 text-sm text-gray-700">Published</span>
                </label>
            </div>

            <!-- Assuming creator_id is set in controller -->
            <input type="hidden" name="creator_id" value="{{ auth()->id() }}">

            <div class="pt-4 flex items-center justify-between">
                <a href="{{ route('admin.events.index') }}" class="text-sm text-gray-500 hover:underline">← Back to Events</a>
                <button type="submit"
                        class="inline-flex items-center px-4 py-2 bg-teal-600 text-white text-sm font-medium rounded hover:bg-teal-700 transition shadow-sm">
                    <i data-lucide="save" class="w-4 h-4 mr-2"></i> Save Event
                </button>
            </div>
        </form>
    </div>
</x-app-layout>
