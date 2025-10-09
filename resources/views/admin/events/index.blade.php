<x-app-layout>
    <div class="">
        <!-- Page Header -->
        <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4 mb-6">
            <div class="flex items-center gap-3">
                <div>
                    <h1 class="text-2xl font-bold text-primary font-montserrat">Events Management</h1>
                    <p class="text-sm text-gray-500 font-lato">Organize and manage all your conference events</p>
                </div>
            </div>
        </div>

        <!-- Status Tabs -->
        <div class="mb-6 border-b border-gray-200">
            <nav class="-mb-px flex space-x-8 overflow-x-auto scrollbar-track-gray-100">
                <x-tab-link label="All Events" icon="list" :to="route('admin.events.index') . '?status=all'" :isActive="request()->get('status') === 'all' || !request()->has('status')" />
                <x-tab-link label="Upcoming" icon="clock" :to="route('admin.events.index') . '?status=upcoming'" :isActive="request()->get('status') === 'upcoming'" />
                <x-tab-link label="Past Events" icon="archive" :to="route('admin.events.index') . '?status=past'" :isActive="request()->get('status') === 'past'" />
                <x-tab-link label="Drafts" icon="file-text" :to="route('admin.events.index') . '?status=draft'" :isActive="request()->get('status') === 'draft'" />
                <x-tab-link label="Create Event" icon="plus" :to="route('admin.events.create')" />
            </nav>
        </div>

        <!-- Mass Actions Bar -->
        <div id="massActionsBar" class="hidden bg-primary-50 px-6 py-3 border-b border-primary-200 rounded-t-lg">
            <div class="flex items-center justify-between">
                <p class="text-sm text-primary-700 font-lato"><span id="selectedCount">0</span> events selected</p>
                <div class="flex gap-3">
                    <button id="massPublishBtn"
                        class="text-accent hover:text-accent-600 text-sm font-medium inline-flex items-center gap-2 disabled:opacity-50 disabled:cursor-not-allowed font-lato transition-colors"
                        disabled>
                        <i data-lucide="send" class="w-4 h-4"></i> Publish Selected
                    </button>
                    <button id="massArchiveBtn"
                        class="text-primary hover:text-primary-600 text-sm font-medium inline-flex items-center gap-2 disabled:opacity-50 disabled:cursor-not-allowed font-lato transition-colors"
                        disabled>
                        <i data-lucide="archive" class="w-4 h-4"></i> Archive Selected
                    </button>
                    <button id="massDeleteBtn"
                        class="text-secondary hover:text-secondary-600 text-sm font-medium inline-flex items-center gap-2 disabled:opacity-50 disabled:cursor-not-allowed font-lato transition-colors"
                        disabled>
                        <i data-lucide="trash-2" class="w-4 h-4"></i> Delete Selected
                    </button>
                </div>
            </div>
        </div>

        <!-- Events Table -->
        <div class="bg-white rounded-lg shadow border border-primary-100 overflow-x-scroll">
            <form id="massActionForm" action="" method="POST">
                @csrf
                <input type="hidden" name="action" id="massActionType" value="">
                <table class="min-w-full divide-y divide-primary-100">
                    <thead class="bg-primary-50">
                        <tr>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-semibold text-primary uppercase tracking-wider font-montserrat w-4">
                                <input type="checkbox" id="selectAll"
                                    class="rounded border-gray-300 text-primary focus:ring-primary">
                            </th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-semibold text-primary uppercase tracking-wider font-montserrat">
                                Event
                            </th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-semibold text-primary uppercase tracking-wider font-montserrat">
                                Date & Time
                            </th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-semibold text-primary uppercase tracking-wider font-montserrat">
                                Status
                            </th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-semibold text-primary uppercase tracking-wider font-montserrat">
                                Speakers
                            </th>
                            <th scope="col" class="px-6 py-3 text-right text-xs font-semibold text-primary uppercase tracking-wider font-montserrat">
                                Actions
                            </th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-primary-100">
                        @forelse($events as $event)
                            <tr class="hover:bg-primary-50 transition-colors">
                                <!-- Checkbox Column -->
                                <td class="px-6 py-4">
                                    <input type="checkbox" name="selected_events[]" value="{{ $event->id }}"
                                        class="rowCheckbox rounded border-gray-300 text-primary focus:ring-primary">
                                </td>

                                <!-- Event Column -->
                                <td class="px-6 py-4">
                                    <div class="flex items-center gap-3">
                                        @if ($event->program_cover)
                                            <div class="flex-shrink-0 h-10 w-10">
                                                <img class="h-10 w-10 rounded-md object-cover"
                                                    src="{{ asset('storage/'. $event->program_cover) }}"
                                                    alt="{{ $event->title }}"/>
                                            </div>
                                        @endif
                                        <div>
                                            <div class="font-bold text-sm text-primary font-montserrat whitespace-nowrap">{{ $event->title }}</div>
                                            <div class="text-sm text-gray-500 font-lato">{{ $event->location ?? 'Online Event' }}</div>
                                        </div>
                                    </div>
                                </td>

                                <!-- Date Column -->
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm text-primary font-montserrat">{{ sweet_date($event->start_date) }}</div>
                                    <div class="text-xs text-gray-500 font-lato">
                                        {{ sweet_date($event->start_date) }} - {{ sweet_date($event->end_date) }}
                                    </div>
                                </td>

                                <!-- Status Column -->
                                <td class="px-6 py-4 whitespace-nowrap">
                                    @if ($event->is_published)
                                        @if ($event->end_date < now())
                                            <span class="px-2 py-1 inline-flex text-xs font-semibold rounded-full bg-gray-100 text-gray-800 font-montserrat">
                                                Event Ended
                                            </span>
                                        @elseif($event->start_date > now())
                                            <span class="px-2 py-1 inline-flex text-xs font-semibold rounded-full bg-accent-100 text-accent-800 font-montserrat">
                                                Upcoming
                                            </span>
                                        @else
                                            <span class="px-2 py-1 inline-flex text-xs font-semibold rounded-full bg-primary-100 text-primary-800 font-montserrat">
                                                Live Now
                                            </span>
                                        @endif
                                    @else
                                        <span class="px-2 py-1 inline-flex text-xs font-semibold rounded-full bg-secondary-100 text-secondary-800 font-montserrat">
                                            Draft
                                        </span>
                                    @endif
                                    <div class="mt-1">
                                        <span class="px-2 py-1 inline-flex text-xs font-semibold rounded-full bg-primary-50 text-primary-700 font-montserrat">
                                            {{ ucfirst($event->mode) }}
                                        </span>
                                    </div>
                                </td>

                                <!-- Speakers Column -->
                                <td class="px-6 py-4">
                                    @if (count($event->speakers) > 0)
                                        <div class="flex -space-x-2">
                                            @foreach ($event->speakers->take(3) as $speaker)
                                                <img class="inline-block h-8 w-8 rounded-full ring-2 ring-white border border-primary-100"
                                                    src="{{ $speaker->photo ? asset('storage/' . $speaker->photo) : 'https://i.pravatar.cc/40?u=' . $speaker->email }}"
                                                    alt="{{ $speaker->name }}" title="{{ $speaker->name }}">
                                            @endforeach
                                            @if ($event->speakers_count > 3)
                                                <span class="inline-flex items-center justify-center h-8 w-8 rounded-full bg-primary-100 text-xs font-medium text-primary-700 ring-2 ring-white font-montserrat">
                                                    +{{ $event->speakers_count - 3 }}
                                                </span>
                                            @endif
                                        </div>
                                    @else
                                        <span class="text-sm text-gray-400 font-lato">No speakers</span>
                                    @endif
                                </td>

                                <!-- Actions Column -->
                                <td class="px-6 py-4 text-right">
                                    <div class="inline-flex gap-2">
                                        <a href="{{ route('admin.events.assign-speakers', $event) }}"
                                            title="Manage Speakers"
                                            class="p-1.5 text-primary hover:text-primary-600 rounded-full hover:bg-primary-50 transition-colors"
                                            aria-label="Manage speakers">
                                            <i data-lucide="users" class="w-5 h-5"></i>
                                        </a>
                                        <a href="{{ route('admin.events.show', $event) }}" title="View"
                                            class="p-1.5 text-gray-500 hover:text-gray-700 rounded-full hover:bg-gray-100 transition-colors"
                                            aria-label="View event">
                                            <i data-lucide="eye" class="w-5 h-5"></i>
                                        </a>
                                        <a href="{{ route('admin.events.edit', $event->slug) }}" title="Edit"
                                            class="p-1.5 text-primary hover:text-primary-600 rounded-full hover:bg-primary-50 transition-colors"
                                            aria-label="Edit event">
                                            <i data-lucide="edit" class="w-5 h-5"></i>
                                        </a>
                                        <button type="button"
                                            data-delete-route="{{ route('admin.events.destroy', $event) }}"
                                            data-modal-target="delete-event-modal"
                                            data-modal-toggle="delete-event-modal"
                                            onclick="confirmEventDelete(this, '{{ $event->title }}')" title="Delete"
                                            class="p-1.5 text-secondary hover:text-secondary-600 rounded-full hover:bg-secondary-50 transition-colors"
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
                                        <h3 class="text-lg font-medium text-primary font-montserrat">No events found</h3>
                                        <p class="max-w-md text-center font-lato">Create your first event to get started</p>
                                        <a href="{{ route('admin.events.create') }}"
                                            class="mt-2 inline-flex items-center px-4 py-2 bg-primary text-white text-sm font-medium rounded-lg hover:bg-primary-600 transition shadow-sm font-montserrat">
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
            @if ($events->hasPages())
                <div class="px-6 py-4 border-t border-primary-100 bg-primary-50">
                    {{ $events->links() }}
                </div>
            @endif
        </div>
    </div>

    <!-- Delete Confirmation Modal -->
    <div id="delete-event-modal" tabindex="-1" aria-hidden="true"
        class="hidden overflow-y-auto overflow-x-hidden fixed top-0 right-0 left-0 z-50 justify-center items-center w-full md:inset-0 h-[calc(100%-1rem)] max-h-full bg-black/50 backdrop-blur-sm">
        <div class="relative p-4 w-full max-w-md max-h-full">
            <div class="relative bg-white rounded-lg shadow border border-secondary-100">
                <!-- Modal header -->
                <div class="flex items-center justify-between p-4 md:p-5 border-b rounded-t">
                    <h3 class="text-lg font-semibold text-secondary font-montserrat">
                        Confirm Event Deletion
                    </h3>
                    <button type="button" data-modal-hide="delete-event-modal"
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
                        <h3 class="text-lg font-medium text-secondary font-montserrat mb-2">Delete Event</h3>
                        <div class="text-sm text-gray-500 font-lato">
                            <p>Are you sure you want to delete <span id="event-name" class="font-semibold text-primary"></span>?</p>
                            <p class="mt-1">This will also remove all associated sessions and speaker assignments.</p>
                        </div>
                    </div>
                </div>
                <!-- Modal footer -->
                <div class="flex items-center justify-end p-4 md:p-5 border-t border-gray-200 rounded-b gap-3">
                    <button data-modal-hide="delete-event-modal" type="button"
                        class="py-2.5 px-5 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-lg hover:bg-gray-100 transition-colors font-lato">
                        Cancel
                    </button>
                    <form method="POST" id="delete-event-form" class="inline">
                        @csrf
                        @method('DELETE')
                        <button type="submit"
                            class="py-2.5 px-5 text-sm font-medium text-white bg-secondary hover:bg-secondary-600 rounded-lg transition-colors inline-flex items-center gap-2 font-montserrat">
                            <i data-lucide="trash-2" class="w-4 h-4"></i>
                            Delete Event
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
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
            window.confirmEventDelete = function(button, eventName) {
                const actionRoute = button.getAttribute('data-delete-route');
                document.getElementById('delete-event-form').action = actionRoute;
                document.getElementById('event-name').textContent = eventName;
            }
        });
    </script>
</x-app-layout>