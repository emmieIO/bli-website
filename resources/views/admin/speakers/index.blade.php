<x-app-layout>
    <div class="">
        <!-- Header Section -->
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 mb-8">
            <div class="flex items-center gap-3">
                <div>
                    <h1 class="text-2xl font-bold text-primary font-montserrat">Active Speakers</h1>
                    <p class="text-sm text-gray-500 font-lato">Manage all conference speakers and their details</p>
                </div>
            </div>
        </div>

        <!-- Status Tabs -->
        <x-speakers-applications-tabs />

        <!-- Main Content -->
        <div class="bg-white shadow rounded-xl border border-primary-100 overflow-hidden">
            <!-- Table Header with Stats -->
            <div class="px-6 py-4 border-b border-primary-100 bg-primary-50 flex flex-wrap items-center justify-between gap-4">
                <div class="flex items-center gap-4">
                    <span class="text-sm font-medium text-primary-700 font-lato">
                        Showing <span class="font-semibold font-montserrat">{{ $speakers->firstItem() }}–{{ $speakers->lastItem() }}</span>
                        of <span class="font-semibold font-montserrat">{{ $speakers->total() }}</span> speakers
                    </span>
                    @if(request()->has('search'))
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-primary/10 text-primary font-montserrat">
                            Filtered
                            <button type="button"
                                class="ml-1.5 flex-shrink-0 h-4 w-4 rounded-full inline-flex items-center justify-center text-primary hover:bg-primary/20 hover:text-primary">
                                <i data-lucide="x" class="w-3 h-3"></i>
                            </button>
                        </span>
                    @endif
                </div>
                <div class="flex items-center gap-2">
                    <label for="sort" class="text-sm font-medium text-primary-700 whitespace-nowrap font-lato">Sort by:</label>
                    <select id="sort"
                        class="block w-full pl-3 pr-10 py-2 text-sm border-primary-100 focus:outline-none focus:ring-2 focus:ring-primary focus:border-primary rounded-md font-lato">
                        <option>Name (A-Z)</option>
                        <option>Name (Z-A)</option>
                        <option>Recently Added</option>
                        <option>Recently Updated</option>
                    </select>
                </div>
            </div>

            <!-- Speakers Table -->
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-primary-100">
                    <thead class="bg-primary-50">
                        <tr>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-semibold text-primary uppercase tracking-wider font-montserrat">Speaker</th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-semibold text-primary uppercase tracking-wider font-montserrat">Title & Organization</th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-semibold text-primary uppercase tracking-wider font-montserrat">Status</th>
                            <th scope="col" class="px-6 py-3 text-right text-xs font-semibold text-primary uppercase tracking-wider font-montserrat">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-primary-100">
                        @forelse ($speakers as $speaker)
                            <tr class="hover:bg-primary-50 transition-colors">
                                <!-- Speaker Column -->
                                <td class="px-6 py-4">
                                    <div class="flex items-center gap-3">
                                        <div class="flex-shrink-0 h-10 w-10 relative">
                                            <img class="h-10 w-10 rounded-full object-cover border border-primary-100"
                                                src="{{ asset('storage/' . $speaker->user->photo)}}"
                                                alt="{{ $speaker->name }}">
                                            @if($speaker->is_featured)
                                                <span class="absolute -top-1 -right-1 bg-accent text-white rounded-full p-0.5">
                                                    <i data-lucide="star" class="w-3 h-3 fill-current"></i>
                                                </span>
                                            @endif
                                        </div>
                                        <div>
                                            <div class="font-medium text-primary font-montserrat">{{ $speaker->user->name }}</div>
                                            <div class="text-sm text-gray-500 flex items-center gap-1 font-lato">
                                                <i data-lucide="mail" class="w-3 h-3"></i>
                                                {{ $speaker->user->email }}
                                            </div>
                                            @if($speaker->user->phone)
                                                <div class="text-sm text-gray-500 flex items-center gap-1 font-lato">
                                                    <i data-lucide="phone" class="w-3 h-3"></i>
                                                    {{ $speaker->user->phone }}
                                                </div>
                                            @endif
                                        </div>
                                    </div>
                                </td>

                                <!-- Title & Organization Column -->
                                <td class="px-6 py-4">
                                    @if($speaker->title || $speaker->organization)
                                        <div class="text-sm font-medium text-primary font-montserrat">{{ $speaker->user->headline ?? '—' }}</div>
                                        <div class="text-sm text-gray-500 font-lato">{{ $speaker->organization ?? '—' }}</div>
                                    @else
                                        <span class="text-sm text-gray-400 font-lato">Not specified</span>
                                    @endif
                                </td>

                                <!-- Status Column -->
                                <td class="px-6 py-4">
                                    @if($speaker->status->value == 'active')
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-accent-100 text-accent-800 font-montserrat">
                                            Active
                                        </span>
                                    @else
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-secondary-100 text-secondary-800 font-montserrat">
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
                                            class="p-1.5 text-primary hover:text-primary-600 rounded-full hover:bg-primary-50 transition-colors"
                                            aria-label="Edit speaker">
                                            <i data-lucide="edit-3" class="w-5 h-5"></i>
                                        </a>
                                        @if($speaker->application_status === 'pending')
                                            <a href=""
                                                title="Review Application"
                                                class="p-1.5 text-accent hover:text-accent-600 rounded-full hover:bg-accent-50 transition-colors"
                                                aria-label="Review application">
                                                <i data-lucide="clipboard-check" class="w-5 h-5"></i>
                                            </a>
                                        @endif
                                        <button 
                                            data-modal-target="delete-speaker-modal"
                                            data-modal-toggle="delete-speaker-modal"
                                            data-delete-route="{{ route('admin.speakers.destroy', $speaker) }}"
                                            onclick="confirmSpeakerDelete(this, {{ $speaker->user }})" title="Delete"
                                            class="p-1.5 text-secondary hover:text-secondary-600 rounded-full hover:bg-secondary-50 transition-colors"
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
                                        <h3 class="text-lg font-medium text-primary font-montserrat">No speakers found</h3>
                                        <p class="max-w-md text-center font-lato">Get started by adding your first speaker to the event.</p>
                                        <div class="flex flex-col sm:flex-row gap-3 mt-4">
                                            <a href="{{ route('admin.speakers.applications.pending') }}"
                                                class="inline-flex items-center px-4 py-2 bg-white border border-primary-100 text-primary text-sm font-medium rounded-lg hover:bg-primary-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary transition shadow-sm font-montserrat">
                                                <i data-lucide="list-checks" class="w-4 h-4 mr-2"></i>
                                                View Pending Applications
                                            </a>
                                            <a href="{{ route('admin.speakers.create') }}"
                                                class="inline-flex items-center px-4 py-2 bg-primary text-white text-sm font-medium rounded-lg hover:bg-primary-600 focus:ring-4 focus:ring-primary-300 transition shadow-sm font-montserrat">
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
                <div class="px-6 py-4 border-t border-primary-100 bg-primary-50">
                    {{ $speakers->links() }}
                </div>
            @endif
        </div>
    </div>

    <!-- Delete Confirmation Modal -->
    <div id="delete-speaker-modal" tabindex="-1" aria-hidden="true"
        class="hidden overflow-y-auto overflow-x-hidden fixed top-0 right-0 left-0 z-50 justify-center items-center w-full md:inset-0 h-[calc(100%-1rem)] max-h-full bg-black/50 backdrop-blur-sm">
        <div class="relative p-4 w-full max-w-md max-h-full">
            <div class="relative bg-white rounded-xl shadow-sm border border-secondary-100">
                <!-- Modal header -->
                <div class="flex items-center justify-between p-4 md:p-5 border-b border-secondary-100 rounded-t">
                    <h3 class="text-lg font-semibold text-secondary font-montserrat">
                        Confirm Speaker Deletion
                    </h3>
                    <button type="button" data-modal-hide="delete-speaker-modal"
                        class="text-gray-400 bg-transparent hover:bg-gray-200 hover:text-secondary rounded-lg text-sm w-8 h-8 inline-flex justify-center items-center transition-colors">
                        <i data-lucide="x" class="w-4 h-4"></i>
                        <span class="sr-only">Close modal</span>
                    </button>
                </div>
                <!-- Modal body -->
                <div class="p-4 md:p-5 space-y-4">
                    <div class="flex flex-col items-center text-center">
                        <div class="w-12 h-12 bg-secondary-100 rounded-full flex items-center justify-center mb-4">
                            <i data-lucide="alert-triangle" class="w-6 h-6 text-secondary"></i>
                        </div>
                        <h3 class="text-lg font-medium text-secondary font-montserrat mb-2">Delete Speaker</h3>
                        <div class="text-sm text-gray-500 font-lato">
                            <p>Are you sure you want to delete <span id="speaker-name" class="font-semibold text-primary"></span>?</p>
                            <p class="mt-1">This action cannot be undone.</p>
                        </div>
                    </div>
                </div>
                <!-- Modal footer -->
                <div class="flex items-center justify-end p-4 md:p-5 border-t border-gray-200 rounded-b gap-3">
                    <button data-modal-hide="delete-speaker-modal" type="button"
                        class="py-2.5 px-5 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-lg hover:bg-gray-100 transition-colors font-lato">
                        Cancel
                    </button>
                    <form method="POST" id="delete-speaker-form" class="inline">
                        @csrf
                        @method('DELETE')
                        <button type="submit"
                            class="px-5 py-2.5 text-sm font-medium text-white bg-secondary hover:bg-secondary-600 rounded-lg transition-colors inline-flex items-center font-montserrat">
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
    </script>
</x-app-layout>