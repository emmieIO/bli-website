<x-app-layout>
    <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
        <!-- Page Header -->
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 mb-6">
            <div class="flex items-center gap-3">
                <div class="p-2 rounded-lg bg-[#00275E]/10">
                    <i data-lucide="calendar-days" class="w-6 h-6 text-[#00275E]"></i>
                </div>
                <div>
                    <h1 class="text-2xl font-bold text-gray-900">Events Management</h1>
                    <p class="text-sm text-gray-500">Organize and manage all your conference events</p>
                </div>
            </div>
            <div class="flex flex-col sm:flex-row gap-3 w-full sm:w-auto">
                <div class="relative w-full sm:w-64">
                    <input type="text" placeholder="Search events..."
                        class="w-full pl-10 pr-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#00275E] focus:border-[#00275E]">
                    <i data-lucide="search" class="absolute left-3 top-2.5 text-gray-400 w-5 h-5"></i>
                </div>
                <a href="{{ route('admin.events.create') }}"
                    class="inline-flex items-center justify-center px-4 py-2 bg-[#00275E] text-white text-sm font-medium rounded-lg hover:bg-[#00275E]/90 transition-all shadow-sm hover:shadow-md whitespace-nowrap">
                    <i data-lucide="plus-circle" class="w-4 h-4 mr-2"></i>
                    Create Event
                </a>
            </div>
        </div>

        <!-- Status Tabs -->
        <div class="mb-6 border-b border-gray-200">
            <nav class="-mb-px flex space-x-8 overflow-x-auto scrollbar-thin scrollbar-thumb-[#00275E]/20 scrollbar-track-gray-100">
                <x-tab-link label="All Events" icon="list" :to="route('admin.events.index').'?status=all'"
                    :isActive="request()->get('status') === 'all' || !request()->has('status')" />
                <x-tab-link label="Upcoming" icon="clock" :to="route('admin.events.index').'?status=upcoming'"
                    :isActive="request()->get('status') === 'upcoming'" />
                <x-tab-link label="Past Events" icon="archive" :to="route('admin.events.index').'?status=past'"
                    :isActive="request()->get('status') === 'past'" />
                <x-tab-link label="Drafts" icon="file-text" :to="route('admin.events.index').'?status=draft'"
                    :isActive="request()->get('status') === 'draft'" />
            </nav>
        </div>

        <!-- Filters -->
        <div class="bg-white rounded-lg shadow-sm border border-gray-100 p-4 mb-6">
            <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                <div>
                    <label for="event-type" class="block text-sm font-medium text-gray-700 mb-1">Event Type</label>
                    <select id="event-type" class="block w-full pl-3 pr-10 py-2 text-base border-gray-300 focus:outline-none focus:ring-[#00275E] focus:border-[#00275E] sm:text-sm rounded-md">
                        <option value="all">All Types</option>
                        <option value="online">Online</option>
                        <option value="offline">In-Person</option>
                        <option value="hybrid">Hybrid</option>
                    </select>
                </div>
                <div>
                    <label for="date-range" class="block text-sm font-medium text-gray-700 mb-1">Date Range</label>
                    <select id="date-range" class="block w-full pl-3 pr-10 py-2 text-base border-gray-300 focus:outline-none focus:ring-[#00275E] focus:border-[#00275E] sm:text-sm rounded-md">
                        <option value="all">All Dates</option>
                        <option value="today">Today</option>
                        <option value="week">This Week</option>
                        <option value="month">This Month</option>
                        <option value="quarter">This Quarter</option>
                        <option value="year">This Year</option>
                        <option value="custom">Custom Range</option>
                    </select>
                </div>
                <div>
                    <label for="sort-by" class="block text-sm font-medium text-gray-700 mb-1">Sort By</label>
                    <select id="sort-by" class="block w-full pl-3 pr-10 py-2 text-base border-gray-300 focus:outline-none focus:ring-[#00275E] focus:border-[#00275E] sm:text-sm rounded-md">
                        <option value="newest">Newest First</option>
                        <option value="oldest">Oldest First</option>
                        <option value="title-asc">Title (A-Z)</option>
                        <option value="title-desc">Title (Z-A)</option>
                        <option value="date-asc">Date (Soonest)</option>
                        <option value="date-desc">Date (Latest)</option>
                    </select>
                </div>
                <div class="flex items-end">
                    <button type="button" class="inline-flex items-center px-4 py-2 border border-gray-300 shadow-sm text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-[#00275E]">
                        <i data-lucide="filter" class="w-4 h-4 mr-2"></i>
                        Apply Filters
                    </button>
                </div>
            </div>
        </div>

        <!-- Mass Actions Bar -->
        <div id="massActionsBar" class="hidden bg-gray-50 px-6 py-3 border-b border-gray-200 rounded-t-lg">
            <div class="flex items-center justify-between">
                <p class="text-sm text-gray-600"><span id="selectedCount">0</span> events selected</p>
                <div class="flex gap-3">
                    <button id="massPublishBtn"
                        class="text-green-600 hover:text-green-800 text-sm font-medium inline-flex items-center gap-2 disabled:opacity-50 disabled:cursor-not-allowed"
                        disabled>
                        <i data-lucide="send" class="w-4 h-4"></i> Publish Selected
                    </button>
                    <button id="massArchiveBtn"
                        class="text-gray-600 hover:text-gray-800 text-sm font-medium inline-flex items-center gap-2 disabled:opacity-50 disabled:cursor-not-allowed"
                        disabled>
                        <i data-lucide="archive" class="w-4 h-4"></i> Archive Selected
                    </button>
                    <button id="massDeleteBtn"
                        class="text-[#FF0000] hover:text-red-800 text-sm font-medium inline-flex items-center gap-2 disabled:opacity-50 disabled:cursor-not-allowed"
                        disabled>
                        <i data-lucide="trash-2" class="w-4 h-4"></i> Delete Selected
                    </button>
                </div>
            </div>
        </div>

        <!-- Events Table -->
        <div class="bg-white rounded-lg shadow border border-gray-100 overflow-x-scroll">
            <form id="massActionForm" action="" method="POST">
                @csrf
                <input type="hidden" name="action" id="massActionType" value="">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider w-4">
                                <input type="checkbox" id="selectAll"
                                    class="rounded border-gray-300 text-[#00275E] focus:ring-[#00275E]">
                            </th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Event</th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Date & Time</th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Speakers</th>
                            <th scope="col" class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @forelse($events as $event)
                            <tr class="hover:bg-gray-50 transition-colors">
                                <!-- Checkbox Column -->
                                <td class="px-6 py-4">
                                    <input type="checkbox" name="selected_events[]" value="{{ $event->id }}"
                                        class="rowCheckbox rounded border-gray-300 text-[#00275E] focus:ring-[#00275E]">
                                </td>

                                <!-- Event Column -->
                                <td class="px-6 py-4">
                                    <div class="flex items-center gap-3">
                                        @if($event->image)
                                            <div class="flex-shrink-0 h-10 w-10">
                                                <img class="h-10 w-10 rounded-md object-cover"
                                                    src="{{ asset('storage/' . $event->image) }}"
                                                    alt="{{ $event->title }}">
                                            </div>
                                        @endif
                                        <div>
                                            <div class="font-medium text-gray-900 whitespace-nowrap">{{ $event->title }}</div>
                                            <div class="text-sm text-gray-500">{{ $event->location ?? 'Online Event' }}</div>
                                        </div>
                                    </div>
                                </td>

                                <!-- Date Column -->
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm text-gray-900">{{ sweet_date($event->start_date) }}</div>
                                    <div class="text-xs text-gray-500">
                                        {{ sweet_date($event->start_date) }} - {{ sweet_date($event->end_date) }}
                                    </div>
                                </td>

                                <!-- Status Column -->
                                <td class="px-6 py-4 whitespace-nowrap">
                                    @if($event->is_published)
                                        @if($event->end_date < now())
                                            <span class="px-2 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-gray-100 text-gray-800">
                                                Event Ended
                                            </span>
                                        @elseif($event->start_date > now())
                                            <span class="px-2 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">
                                                Upcoming
                                            </span>
                                        @else
                                            <span class="px-2 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-blue-100 text-blue-800">
                                                Live Now
                                            </span>
                                        @endif
                                    @else
                                        <span class="px-2 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-yellow-100 text-yellow-800">
                                            Draft
                                        </span>
                                    @endif
                                    <div class="mt-1">
                                        <span class="px-2 py-1 inline-flex text-xs font-semibold rounded-full
                                            {{ $event->mode === 'online' ? 'bg-blue-100 text-blue-800' :
                                                ($event->mode === 'offline' ? 'bg-green-100 text-green-800' : 'bg-purple-100 text-purple-800') }}">
                                            {{ ucfirst($event->mode) }}
                                        </span>
                                    </div>
                                </td>

                                <!-- Speakers Column -->
                                <td class="px-6 py-4">
                                    @if($event->speakers_count > 0)
                                        <div class="flex -space-x-2">
                                            @foreach($event->speakers->take(3) as $speaker)
                                                <img class="inline-block h-8 w-8 rounded-full ring-2 ring-white"
                                                    src="{{ $speaker->photo ? asset('storage/' . $speaker->photo) : 'https://i.pravatar.cc/40?u=' . $speaker->email }}"
                                                    alt="{{ $speaker->name }}" title="{{ $speaker->name }}">
                                            @endforeach
                                            @if($event->speakers_count > 3)
                                                <span class="inline-flex items-center justify-center h-8 w-8 rounded-full bg-gray-200 text-xs font-medium text-gray-600 ring-2 ring-white">
                                                    +{{ $event->speakers_count - 3 }}
                                                </span>
                                            @endif
                                        </div>
                                    @else
                                        <span class="text-sm text-gray-400">No speakers</span>
                                    @endif
                                </td>

                                <!-- Actions Column -->
                                <td class="px-6 py-4 text-right">
                                    <div class="inline-flex gap-2">
                                        <a href="{{ route('admin.events.assign-speakers', $event) }}"
                                            title="Manage Speakers"
                                            class="p-1.5 text-purple-600 hover:text-purple-800 rounded-full hover:bg-purple-50 transition-colors"
                                            aria-label="Manage speakers">
                                            <i data-lucide="users" class="w-5 h-5"></i>
                                        </a>
                                        <a href="{{ route('admin.events.show', $event) }}"
                                            title="View"
                                            class="p-1.5 text-gray-500 hover:text-gray-700 rounded-full hover:bg-gray-100 transition-colors"
                                            aria-label="View event">
                                            <i data-lucide="eye" class="w-5 h-5"></i>
                                        </a>
                                        <a href="{{ route('admin.events.edit', $event->slug) }}"
                                            title="Edit"
                                            class="p-1.5 text-blue-600 hover:text-blue-800 rounded-full hover:bg-blue-50 transition-colors"
                                            aria-label="Edit event">
                                            <i data-lucide="edit" class="w-5 h-5"></i>
                                        </a>
                                        <button
                                            type="button"
                                            data-delete-route="{{ route('admin.events.destroy', $event) }}"
                                            data-modal-target="delete-event-modal"
                                            data-modal-toggle="delete-event-modal"
                                            onclick="confirmEventDelete(this, '{{ $event->title }}')"
                                            title="Delete"
                                            class="p-1.5 text-[#FF0000] hover:text-red-800 rounded-full hover:bg-red-50 transition-colors"
                                            aria-label="Delete event">
                                            <i data-lucide="trash-2" class="w-5 h-5"></i>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="px-6 py-12 text-center">
                                    <div class="flex flex-col items-center justify-center space-y-4 text-gray-400">
                                        <i data-lucide="calendar-off" class="w-12 h-12"></i>
                                        <h3 class="text-lg font-medium text-gray-900">No events found</h3>
                                        <p class="max-w-md text-center">Create your first event to get started</p>
                                        <a href="{{ route('admin.events.create') }}"
                                            class="mt-2 inline-flex items-center px-4 py-2 bg-[#00275E] text-white text-sm font-medium rounded-lg hover:bg-[#00275E]/90 transition shadow-sm">
                                            <i data-lucide="plus-circle" class="w-4 h-4 mr-2"></i>
                                            Create Event
                                        </a>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </form>

            <!-- Pagination -->
            @if($events->hasPages())
                <div class="px-6 py-4 border-t border-gray-200 bg-gray-50">
                    {{ $events->links() }}
                </div>
            @endif
        </div>
    </div>

    <!-- Delete Confirmation Modal -->
    <div id="delete-event-modal" tabindex="-1" aria-hidden="true"
        class="hidden overflow-y-auto overflow-x-hidden fixed top-0 right-0 left-0 z-50 justify-center items-center w-full md:inset-0 h-[calc(100%-1rem)] max-h-full">
        <div class="relative p-4 w-full max-w-md max-h-full">
            <div class="relative bg-white rounded-lg shadow dark:bg-gray-700">
                <!-- Modal header -->
                <div class="flex items-center justify-between p-4 md:p-5 border-b rounded-t dark:border-gray-600">
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-white">
                        Confirm Event Deletion
                    </h3>
                    <button type="button"
                        class="text-gray-400 bg-transparent hover:bg-gray-200 hover:text-gray-900 rounded-lg text-sm w-8 h-8 ms-auto inline-flex justify-center items-center dark:hover:bg-gray-600 dark:hover:text-white"
                        data-modal-hide="delete-event-modal">
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
                        <h3 class="text-lg font-medium text-gray-900 mb-2" id="event-delete-title">Delete Event</h3>
                        <div class="text-sm text-gray-500">
                            <p>Are you sure you want to delete <span id="event-name" class="font-semibold text-gray-900"></span>?</p>
                            <p class="mt-1">This will also remove all associated sessions and speaker assignments.</p>
                        </div>
                    </div>
                </div>
                <!-- Modal footer -->
                <div class="flex items-center justify-end p-4 md:p-5 border-t border-gray-200 rounded-b dark:border-gray-600 gap-3">
                    <button data-modal-hide="delete-event-modal" type="button"
                        class="py-2.5 px-5 text-sm font-medium text-gray-900 focus:outline-none bg-white rounded-lg border border-gray-200 hover:bg-gray-100 focus:z-10 focus:ring-4 focus:ring-gray-100 dark:focus:ring-gray-700 dark:bg-gray-800 dark:text-gray-400 dark:border-gray-600 dark:hover:text-white dark:hover:bg-gray-700">
                        Cancel
                    </button>
                    <form method="POST" id="delete-event-form" class="inline">
                        @csrf
                        @method('DELETE')
                        <button type="submit"
                            class="text-white bg-[#FF0000] hover:bg-red-700 focus:ring-4 focus:outline-none focus:ring-red-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center inline-flex items-center dark:bg-red-500 dark:hover:bg-red-600 dark:focus:ring-red-900">
                            <i data-lucide="trash-2" class="w-4 h-4 mr-2"></i>
                            Delete Event
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            // Mass selection functionality
            const selectAll = document.getElementById('selectAll');
            const rowCheckboxes = document.querySelectorAll('.rowCheckbox');
            const massActionsBar = document.getElementById('massActionsBar');
            const selectedCount = document.getElementById('selectedCount');
            const massPublishBtn = document.getElementById('massPublishBtn');
            const massArchiveBtn = document.getElementById('massArchiveBtn');
            const massDeleteBtn = document.getElementById('massDeleteBtn');
            const massActionForm = document.getElementById('massActionForm');
            const massActionType = document.getElementById('massActionType');

            function updateMassActionsBar() {
                const selected = [...rowCheckboxes].filter(cb => cb.checked).length;
                if (selected > 0) {
                    massActionsBar.classList.remove('hidden');
                    selectedCount.textContent = selected;
                    massPublishBtn.disabled = false;
                    massArchiveBtn.disabled = false;
                    massDeleteBtn.disabled = false;
                } else {
                    massActionsBar.classList.add('hidden');
                    massPublishBtn.disabled = true;
                    massArchiveBtn.disabled = true;
                    massDeleteBtn.disabled = true;
                }
            }

            selectAll.addEventListener('change', () => {
                rowCheckboxes.forEach(cb => cb.checked = selectAll.checked);
                updateMassActionsBar();
            });

            rowCheckboxes.forEach(cb => {
                cb.addEventListener('change', () => {
                    selectAll.checked = [...rowCheckboxes].every(cb => cb.checked);
                    updateMassActionsBar();
                });
            });

            massPublishBtn.addEventListener('click', (e) => {
                e.preventDefault();
                massActionType.value = 'publish';
                if (confirm('Publish selected events?')) {
                    massActionForm.submit();
                }
            });

            massArchiveBtn.addEventListener('click', (e) => {
                e.preventDefault();
                massActionType.value = 'archive';
                if (confirm('Archive selected events?')) {
                    massActionForm.submit();
                }
            });

            massDeleteBtn.addEventListener('click', (e) => {
                e.preventDefault();
                massActionType.value = 'delete';
                if (confirm('Delete selected events? This action cannot be undone.')) {
                    massActionForm.submit();
                }
            });

            // Event deletion modal
            const eventDeleteForm = document.getElementById('delete-event-form');

            window.confirmEventDelete = function(button, eventName) {
                const actionRoute = button.getAttribute('data-delete-route');
                eventDeleteForm.action = actionRoute;
                document.getElementById('event-name').textContent = eventName;
                document.getElementById('event-delete-title').textContent = `Delete ${eventName}`;
            }
        });
    </script>
</x-app-layout>
