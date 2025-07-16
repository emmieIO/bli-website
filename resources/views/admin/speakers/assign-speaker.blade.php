<x-app-layout>
    <div class="py-8">
        <div class="">
            <div class="flex items-center justify-between mb-6">
                <h2 class="text-2xl font-bold text-gray-800 flex items-center gap-2">
                    <i data-lucide="user-plus" class="w-6 h-6 text-teal-600"></i>
                    Assign Speakers to {{ $event->title }}
                </h2>
                <a href="{{ route('admin.events.show', $event) }}"
                    class="inline-flex items-center px-4 py-2 bg-gray-100 text-gray-700 text-sm rounded hover:bg-gray-200">
                    <i data-lucide="arrow-left" class="w-4 h-4 mr-2"></i> Back to Event
                </a>
            </div>

            <div class="bg-white shadow rounded-lg p-6">
                <form action="{{ route('admin.events.assign-speaker', $event) }}" method="POST">
                    @csrf
                    @method('POST')

                    <div class="mb-6">
                        <label class="block text-sm font-medium text-gray-700 mb-2">Select Speakers</label>
                        <div class="border border-gray-200 rounded-lg max-h-60 overflow-y-auto divide-y">
                            @forelse($speakers as $speaker)
                                <label class="flex items-center gap-3 p-4 hover:bg-gray-50 cursor-pointer">
                                    <input type="checkbox" name="speaker_ids[]" value="{{ $speaker->id }}"
                                        class="text-teal-600 rounded focus:ring-teal-500"
                                        {{ in_array($speaker->id, $assignedSpeakerIds ?? []) ? 'checked' : '' }}>
                                    <img src="{{ $speaker->photo ? asset("storage/$speaker->photo") : 'https://ui-avatars.com/api/?name=' . urlencode($speaker->name) }}"
                                        class="w-10 h-10 rounded-full object-cover">
                                    <div class="flex-1">
                                        <div class="font-medium text-gray-800">{{ $speaker->name }}</div>
                                        <div class="text-sm text-gray-500">
                                            {{ $speaker->organization ?? 'Independent' }}
                                            · {{ $speaker->title ?? 'Speaker' }}
                                        </div>
                                    </div>
                                </label>
                            @empty
                                <div class="p-4 text-center text-gray-500 text-sm">
                                    No available speakers found.
                                </div>
                            @endforelse
                        </div>
                    </div>

                    <div class="flex justify-end">
                        <button type="submit"
                            class="bg-teal-600 flex items-center gap-3 hover:bg-teal-700 text-white px-6 py-2 rounded-md text-sm font-medium shadow-sm transition">
                            <i data-lucide="link" class="w-4 h-4 mr-2"></i>
                            <span>Assign Selected</span>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>
