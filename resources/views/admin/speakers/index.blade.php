<x-app-layout>
    <div class="px-4 mx-auto max-w-7xl">
        <!-- Header Section -->
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 mb-8">
            <div class="flex items-center gap-3">
                <a href="{{ url()->previous() }}"
                    class="inline-flex items-center justify-center overflow-hidden w-10 h-10 bg-white border rounded-full border-gray-300 text-gray-700 text-sm font-medium hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-[#00275E] transition shadow-sm">
                    <i data-lucide="arrow-left" class="w-5 h-5"></i>
                </a>
                <div class="p-2.5 rounded-lg bg-[#00275E]/10">
                    <i data-lucide="mic" class="w-6 h-6 text-[#00275E]"></i>
                </div>
                <div>
                    <h1 class="text-2xl font-extrabold text-[#00275E]">Event Speakers</h1>
                    <p class="text-sm text-gray-500">Manage all conference speakers and their details</p>
                </div>
            </div>

            <a href="{{ route('admin.speakers.create') }}"
                class="inline-flex items-center px-4 py-2 bg-[#00275E] text-white text-sm font-medium rounded-lg hover:bg-[#FF0000] focus:ring-4 focus:ring-blue-300 transition shadow-sm">
                <i data-lucide="plus" class="w-4 h-4 mr-2"></i>
                Add New Speaker
            </a>
        </div>

        <!-- Status Tabs -->
        <x-speakers-applications-tabs />

        <!-- Main Content -->
        <div class="bg-white shadow rounded-xl border border-gray-100 overflow-hidden">
            <!-- Table Header with Stats -->
            <div class="px-6 py-4 border-b border-gray-200 bg-gray-50 flex flex-wrap items-center justify-between gap-4">
                <div class="flex items-center gap-4">
                    <span class="text-sm font-medium text-gray-700">
                        Showing <span class="font-semibold">{{ $speakers->firstItem() }}–{{ $speakers->lastItem() }}</span>
                        of <span class="font-semibold">{{ $speakers->total() }}</span> speakers
                    </span>
                    @if(request()->has('search'))
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-[#00275E]/10 text-[#00275E]">
                            Filtered
                            <button type="button"
                                class="ml-1.5 flex-shrink-0 h-4 w-4 rounded-full inline-flex items-center justify-center text-[#00275E] hover:bg-[#00275E]/20 hover:text-[#00275E]">
                                <i data-lucide="x" class="w-3 h-3"></i>
                            </button>
                        </span>
                    @endif
                </div>
                <div class="flex items-center gap-2">
                    <label for="sort" class="text-sm font-medium text-gray-700 whitespace-nowrap">Sort by:</label>
                    <select id="sort"
                        class="block w-full pl-3 pr-10 py-2 text-sm border-gray-300 focus:outline-none focus:ring-2 focus:ring-[#00275E] focus:border-[#00275E] rounded-md">
                        <option>Name (A-Z)</option>
                        <option>Name (Z-A)</option>
                        <option>Recently Added</option>
                        <option>Recently Updated</option>
                    </select>
                </div>
            </div>

            <!-- Speakers Table -->
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Speaker</th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Title & Organization</th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                            <th scope="col" class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @forelse ($speakers as $speaker)
                            <tr class="hover:bg-gray-50 transition-colors">
                                <!-- Speaker Column -->
                                <td class="px-6 py-4">
                                    <div class="flex items-center gap-3">
                                        <div class="flex-shrink-0 h-10 w-10 relative">
                                            <img class="h-10 w-10 rounded-full object-cover"
                                                src="{{ $speaker->photo ? asset('storage/' . $speaker->photo) : 'https://i.pravatar.cc/40?u=' . $speaker->email }}"
                                                alt="{{ $speaker->name }}">
                                            @if($speaker->is_featured)
                                                <span class="absolute -top-1 -right-1 bg-amber-500 text-white rounded-full p-0.5">
                                                    <i data-lucide="star" class="w-3 h-3 fill-current"></i>
                                                </span>
                                            @endif
                                        </div>
                                        <div>
                                            <div class="font-medium text-gray-900">{{ $speaker->name }}</div>
                                            <div class="text-sm text-gray-500 flex items-center gap-1">
                                                <i data-lucide="mail" class="w-3 h-3"></i>
                                                {{ $speaker->email }}
                                            </div>
                                            @if($speaker->phone)
                                                <div class="text-sm text-gray-500 flex items-center gap-1">
                                                    <i data-lucide="phone" class="w-3 h-3"></i>
                                                    {{ $speaker->phone }}
                                                </div>
                                            @endif
                                        </div>
                                    </div>
                                </td>

                                <!-- Title & Organization Column -->
                                <td class="px-6 py-4">
                                    @if($speaker->title || $speaker->organization)
                                        <div class="text-sm font-medium text-gray-900">{{ $speaker->title ?? '—' }}</div>
                                        <div class="text-sm text-gray-500">{{ $speaker->organization ?? '—' }}</div>
                                    @else
                                        <span class="text-sm text-gray-400">Not specified</span>
                                    @endif
                                </td>

                                <!-- Status Column -->
                                <td class="px-6 py-4">
                                    @if($speaker->status == 'active')
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                            Active
                                        </span>
                                    @else
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-800">
                                            Inactive
                                        </span>
                                    @endif
                                </td>

                                <!-- Actions Column -->
                                <td class="px-6 py-4 text-right">
                                    <div class="inline-flex gap-2">
                                        <a href="{{ route('admin.speakers.show', $speaker) }}" title="View details"
                                            class="p-1.5 text-gray-500 hover:text-gray-700 rounded-full hover:bg-gray-100 transition-colors"
                                            aria-label="View speaker details">
                                            <i data-lucide="eye" class="w-5 h-5"></i>
                                        </a>
                                        <a href="{{ route('admin.speakers.edit', $speaker) }}" title="Edit"
                                            class="p-1.5 text-[#00275E] hover:text-[#FF0000] rounded-full hover:bg-[#00275E]/10 transition-colors"
                                            aria-label="Edit speaker">
                                            <i data-lucide="edit-3" class="w-5 h-5"></i>
                                        </a>
                                        {{-- @if($speaker->application_status === 'pending') --}}
                                            <a href=""
                                                title="Review Application"
                                                class="p-1.5 text-amber-600 hover:text-amber-800 rounded-full hover:bg-amber-50 transition-colors"
                                                aria-label="Review application">
                                                <i data-lucide="clipboard-check" class="w-5 h-5"></i>
                                            </a>
                                        {{-- @endif --}}
                                        <button data-delete-route="{{ route('admin.speakers.destroy', $speaker) }}"
                                            data-modal-target="delete-speaker-modal"
                                            data-modal-toggle="delete-speaker-modal"
                                            onclick="confirmSpeakerDelete(this, {{ $speaker }})" title="Delete"
                                            class="p-1.5 text-red-600 hover:text-red-800 rounded-full hover:bg-red-50 transition-colors"
                                            aria-label="Delete speaker">
                                            <i data-lucide="trash-2" class="w-5 h-5"></i>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="px-6 py-16 text-center">
                                    <div class="flex flex-col items-center justify-center space-y-4 text-gray-400">
                                        <i data-lucide="mic-off" class="w-12 h-12"></i>
                                        <h3 class="text-lg font-medium text-gray-900">No speakers found</h3>
                                        <p class="max-w-md text-center">Get started by adding your first speaker to the event.</p>
                                        <div class="flex flex-col sm:flex-row gap-3 mt-4">
                                            <a href="{{ route('admin.speakers.applications.pending') }}"
                                                class="inline-flex items-center px-4 py-2 bg-white border border-gray-300 text-gray-700 text-sm font-medium rounded-lg hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-[#00275E] transition shadow-sm">
                                                <i data-lucide="list-checks" class="w-4 h-4 mr-2"></i>
                                                View Pending Applications
                                            </a>
                                            <a href="{{ route('admin.speakers.create') }}"
                                                class="inline-flex items-center px-4 py-2 bg-[#00275E] text-white text-sm font-medium rounded-lg hover:bg-[#FF0000] focus:ring-4 focus:ring-blue-300 transition shadow-sm">
                                                <i data-lucide="plus" class="w-4 h-4 mr-2"></i>
                                                Add Speaker
                                            </a>
                                        </div>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            @if($speakers->hasPages())
                <div class="px-6 py-4 border-t border-gray-200 bg-gray-50">
                    {{ $speakers->links() }}
                </div>
            @endif
        </div>
    </div>

    <!-- Delete Confirmation Modal -->
    <div id="delete-speaker-modal" tabindex="-1" aria-hidden="true"
        class="hidden overflow-y-auto overflow-x-hidden fixed top-0 right-0 left-0 z-50 justify-center items-center w-full md:inset-0 h-[calc(100%-1rem)] max-h-full">
        <div class="relative p-4 w-full max-w-md max-h-full">
            <div class="relative bg-white rounded-xl shadow-sm border border-gray-200">
                <!-- Modal header -->
                <div class="flex items-center justify-between p-4 md:p-5 border-b border-gray-200 rounded-t">
                    <h3 class="text-lg font-semibold text-gray-900">
                        Confirm Speaker Deletion
                    </h3>
                    <button type="button"
                        class="text-gray-400 bg-transparent hover:bg-gray-200 hover:text-gray-900 rounded-lg text-sm w-8 h-8 ms-auto inline-flex justify-center items-center"
                        data-modal-hide="delete-speaker-modal">
                        <i data-lucide="x" class="w-4 h-4"></i>
                        <span class="sr-only">Close modal</span>
                    </button>
                </div>
                <!-- Modal body -->
                <div class="p-4 md:p-5 space-y-4">
                    <div class="flex flex-col items-center text-center">
                        <div class="mx-auto flex-shrink-0 flex items-center justify-center h-12 w-12 rounded-full bg-red-100 mb-4">
                            <i data-lucide="alert-triangle" class="h-6 w-6 text-red-600"></i>
                        </div>
                        <h3 class="text-lg font-medium text-gray-900 mb-2" id="speaker-delete-title">Delete Speaker</h3>
                        <div class="text-sm text-gray-500">
                            <p>Are you sure you want to delete <span id="speaker-name" class="font-semibold text-gray-900"></span>?</p>
                            <p class="mt-1">This action cannot be undone.</p>
                        </div>
                    </div>
                </div>
                <!-- Modal footer -->
                <div class="flex items-center justify-end p-4 md:p-5 border-t border-gray-200 rounded-b gap-3">
                    <button data-modal-hide="delete-speaker-modal" type="button"
                        class="py-2.5 px-5 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-lg hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-300 transition">
                        Cancel
                    </button>
                    <form method="POST" id="delete-speaker-form" class="inline">
                        @csrf
                        @method('DELETE')
                        <button type="submit"
                            class="px-5 py-2.5 text-sm font-medium text-white bg-red-600 rounded-lg hover:bg-red-700 focus:ring-4 focus:outline-none focus:ring-red-300 transition inline-flex items-center">
                            <i data-lucide="trash-2" class="w-4 h-4 mr-2"></i>
                            Delete Speaker
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script>
        const speakerDeleteForm = document.getElementById('delete-speaker-form');

        function confirmSpeakerDelete(button, speaker) {
            const actionRoute = button.getAttribute('data-delete-route');
            speakerDeleteForm.action = actionRoute;

            // Update modal content with speaker details
            document.getElementById('speaker-name').textContent = speaker.name;
            document.getElementById('speaker-delete-title').textContent = `Delete ${speaker.name}`;
        }

        // Optional: Initialize tooltips if you're using Bootstrap or similar
        // document.addEventListener('DOMContentLoaded', function () {
        //     const tooltipTriggerList = [].slice.call(document.querySelectorAll('[title]'));
        //     tooltipTriggerList.map(function (tooltipTriggerEl) {
        //         return new Tooltip(tooltipTriggerEl, {
        //             placement: 'top',
        //             trigger: 'hover focus'
        //         });
        //     });
        // });
    </script>
</x-app-layout>