<x-app-layout>
    <div class="py-8 px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between items-center mb-6">
            <h2 class="text-2xl font-bold text-teal-800 flex items-center gap-2">
                <i data-lucide="mic" class="w-6 h-6 text-teal-600"></i>
                Event Speakers
            </h2>
            <a href="{{ route('admin.speakers.create') }}"
                class="inline-flex items-center px-4 py-2 bg-teal-600 text-white text-sm font-medium rounded hover:bg-teal-700 transition">
                <i data-lucide="plus" class="w-4 h-4 mr-2"></i>
                <span>Add Speaker</span>
            </a>
        </div>

        <div class="overflow-x-auto bg-white shadow rounded-lg">
            <table class="min-w-full divide-y divide-gray-200 text-sm">
                <thead class="bg-gray-50 text-gray-600 uppercase text-xs font-medium">
                    <tr>
                        <th class="px-6 py-3 text-left">Speaker</th>
                        <th class="px-6 py-3 text-left">Title</th>
                        <th class="px-6 py-3 text-left">Organization</th>
                        <th class="px-6 py-3 text-right">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100 text-gray-700">
                    @forelse ($speakers as $speaker)
                        <tr class="hover:bg-gray-50">
                            <td class="px-6 py-4 flex items-center gap-3">
                                <img src="{{ $speaker->photo ? asset("storage/$speaker->photo") : 'https://i.pravatar.cc/40?u=' . $speaker->email }}"
                                    alt="{{ $speaker->name }}" class="w-10 h-10 rounded-full object-cover">
                                <div>
                                    <div class="font-semibold">{{ $speaker->name }}</div>
                                    <div class="text-sm text-gray-500">{{ $speaker->email }}</div>
                                </div>
                            </td>
                            <td class="px-6 py-4">{{ $speaker->title ?? '—' }}</td>
                            <td class="px-6 py-4">{{ $speaker->organization ?? '—' }}</td>
                            <td class="px-6 py-4 text-right">
                                <div class="inline-flex gap-2">
                                    <a href="{{ route('admin.speakers.show', $speaker) }}" title="View"
                                        class="text-gray-600 hover:text-gray-800">
                                        <i data-lucide="eye" class="w-5 h-5"></i>
                                    </a>
                                    <a href="{{ route('admin.speakers.edit', $speaker) }}" title="Edit"
                                        class="text-teal-600 hover:text-teal-800">
                                        <i data-lucide="edit-3" class="w-5 h-5"></i>
                                    </a>
                                    <form action="{{ route('admin.speakers.destroy', $speaker) }}" method="POST"
                                        onsubmit="return confirm('Are you sure you want to delete this speaker?')"
                                        class="inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" title="Delete" class="text-red-600 hover:text-red-800">
                                            <i data-lucide="trash-2" class="w-5 h-5"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="px-6 py-12 text-center text-gray-500">
                                <div class="flex flex-col items-center space-y-2">
                                    <i data-lucide="mic-off" class="w-8 h-8 text-gray-400"></i>
                                    <p class="text-sm">No speakers available yet</p>
                                    <a href="{{ route('admin.speakers.create') }}"
                                        class="mt-2 inline-flex items-center px-4 py-2 bg-teal-600 text-white text-sm font-medium rounded hover:bg-teal-700 transition">
                                        <i data-lucide="plus" class="w-4 h-4 mr-2"></i> Add Speaker
                                    </a>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</x-app-layout>
