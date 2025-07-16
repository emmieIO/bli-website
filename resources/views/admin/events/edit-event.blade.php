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

    <div class="py-8 px-4 sm:px-6 lg:px-8 max-w-5xl mx-auto">
        <h2 class="text-2xl font-bold text-teal-800 flex items-center gap-2 mb-6">
            <i data-lucide="calendar-check" class="w-6 h-6"></i>
            Update Event
        </h2>

        <form action="{{ route('admin.events.update', $event) }}" method="POST" enctype="multipart/form-data"
              class="bg-white border border-gray-100 rounded-xl shadow p-6 space-y-6">
            @csrf
            @method('PUT')

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                <x-input name="title" label="Event Title" required icon="type" :value="old('title', $event->title)" />

                <x-select name="mode" label="Event Mode" required :options="[
                    'online' => 'Online',
                    'offline' => 'Offline',
                    'hybrid' => 'Hybrid'
                ]" :selected="old('mode', $event->mode)" icon="activity" />

                <x-input name="start_date" label="Start Date" type="datetime-local" required icon="calendar"
                         :value="old('start_date', \Carbon\Carbon::parse($event->start_date)->format('Y-m-d\TH:i'))" />

                <x-input name="end_date" label="End Date" type="datetime-local" required icon="calendar-check"
                         :value="old('end_date', \Carbon\Carbon::parse($event->end_date)->format('Y-m-d\TH:i'))" />

                <x-input name="location" label="Location (URL or Venue)" required icon="map-pin" :value="old('location', $event->location)" />
                <x-input name="physical_address" label="Physical Address" icon="map" :value="old('physical_address', $event->physical_address)" />
                <x-input name="contact_email" label="Contact Email" type="email" icon="mail" :value="old('contact_email', $event->contact_email)" />
                <x-input name="entry_fee" label="Entry Fee (₦)" type="number" min="0" step="0.01" icon="credit-card"
                         :value="old('entry_fee', $event->entry_fee)" />

                <x-input name="program_cover" label="Cover Image" type="file" icon="image" />
                @if ($event->program_cover)
                    <p class="text-sm text-gray-500">
                        Current file:
                        <a href="{{ asset('storage/' . $event->program_cover) }}" class="underline text-teal-600" target="_blank">
                            View cover image
                        </a>
                    </p>
                @endif
            </div>

            <div>
                <label for="description" class="block text-sm font-medium text-gray-700">Description</label>
                <textarea name="description" id="description" rows="6"
                          class="mt-1 block border p-3 w-full border-gray-300 rounded-md shadow-sm focus:ring-teal-500 focus:border-teal-500 sm:text-sm"
                          placeholder="Write about the event...">{{ old('description', $event->description) }}</textarea>
                <x-input-error :messages="$errors->get('description')" class="mt-1" />
            </div>

            <div class="flex items-center gap-4">
                <label class="inline-flex items-center">
                    <input type="checkbox" name="is_active" class="rounded text-teal-600"
                           {{ old('is_active', $event->is_active) ? 'checked' : '' }}>
                    <span class="ml-2 text-sm text-gray-700">Active</span>
                </label>

                <label class="inline-flex items-center">
                    <input type="checkbox" name="is_published" class="rounded text-teal-600"
                           {{ old('is_published', $event->is_published) ? 'checked' : '' }}>
                    <span class="ml-2 text-sm text-gray-700">Published</span>
                </label>
            </div>

            <!-- creator_id is optional here; could be locked/hidden -->
            <input type="hidden" name="creator_id" value="{{ $event->creator_id }}">

            <div class="pt-4 flex items-center justify-between">
                <a href="{{ route('admin.events.index') }}" class="text-sm text-gray-500 hover:underline">← Back to Events</a>
                <button type="submit"
                        class="inline-flex items-center px-4 py-2 bg-teal-600 text-white text-sm font-medium rounded hover:bg-teal-700 transition shadow-sm">
                    <i data-lucide="save" class="w-4 h-4 mr-2"></i> Update Event
                </button>
            </div>
        </form>
    </div>
</x-app-layout>
