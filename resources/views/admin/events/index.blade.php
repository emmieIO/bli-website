<x-app-layout>
    <div class="py-8 px-4 sm:px-6 lg:px-8">
        <!-- Page Header -->
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 mb-6">
            <h2 class="text-2xl font-bold text-teal-800 flex items-center gap-2">
                <i data-lucide="calendar-days" class="w-6 h-6"></i>
                Events
            </h2>
            <a href="{{ route('admin.events.create') }}"
                class="inline-flex items-center px-4 py-2 bg-teal-600 text-white text-sm font-medium rounded hover:bg-teal-700 transition shadow-sm">
                <i data-lucide="plus-circle" class="w-4 h-4 mr-2"></i> Create Event
            </a>
        </div>

        <!-- Filter Placeholder -->
        <div class="bg-white border border-gray-100 rounded-xl shadow p-5 mb-8">
            <!-- Add filters here if needed -->
            <p class="text-sm text-gray-500">Filter controls coming soon...</p>
        </div>

        <!-- Mass Actions Bar -->
        <div id="massActionsBar" class="hidden bg-gray-50 px-6 py-3 border border-b border-gray-200 rounded-t-xl">
            <div class="flex items-center justify-between">
                <p class="text-sm text-gray-600"><span id="selectedCount">0</span> selected</p>
                <button id="massDeleteBtn"
                    class="text-red-600 hover:text-red-800 text-sm font-medium inline-flex items-center gap-2 disabled:opacity-50 disabled:cursor-not-allowed"
                    disabled>
                    <i data-lucide="trash-2" class="w-4 h-4"></i> Delete Selected
                </button>
            </div>
        </div>

        <!-- Events Table -->
        <!-- Events Table -->
        <div class="bg-white rounded-xl shadow border border-gray-100 overflow-x-auto">
            <form id="massActionForm" action="{{ route('admin.events.massDelete') }}" method="POST">
                @csrf
                @method('DELETE')
                <table class="min-w-full text-sm text-gray-700 divide-y divide-gray-200">
                    <thead class="bg-gray-50 text-xs font-semibold text-gray-500 uppercase">
                        <tr>
                            <th class="px-4 py-3 text-left w-4">
                                <input type="checkbox" id="selectAll"
                                    class="rounded border-gray-300 text-teal-600 focus:ring-teal-500">
                            </th>
                            <th class="px-4 py-3 text-left">Event</th>
                            <th class="px-4 py-3 text-left">Date</th>
                            <th class="px-4 py-3 text-left">Type</th>
                            <th class="px-4 py-3 text-right">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        @forelse($events as $event)
                                            <tr class="hover:bg-gray-50 transition duration-150">
                                                <td class="px-4 py-4">
                                                    <input type="checkbox" name="selected_events[]" value="{{ $event->id }}"
                                                        class="rowCheckbox rounded border-gray-300 text-teal-600 focus:ring-teal-500">
                                                </td>
                                                <td class="px-4 py-4 font-medium text-teal-800 whitespace-nowrap max-w-[180px] truncate">
                                                    {{ $event->title }}
                                                </td>
                                                <td class="px-4 py-4 whitespace-nowrap">
                                                    <span class="block">{{ sweet_date($event->start_date) }}</span>
                                                    <span class="text-xs text-gray-400">to {{ sweet_date($event->end_date) }}</span>
                                                </td>
                                                <td class="px-4 py-4">
                                                    <span class="px-2 py-1 inline-flex text-xs font-semibold rounded-full {{
                            $event->mode === 'online' ? 'bg-blue-100 text-blue-800' :
                            ($event->mode === 'offline' ? 'bg-green-100 text-green-800' : 'bg-purple-100 text-purple-800')
                                                }}">
                                                        {{ ucfirst($event->mode) }}
                                                    </span>
                                                </td>
                                                <td class="px-4 py-4 text-right">
                                                    <div class="flex items-center justify-end gap-3">
                                                        <a href="{{ route('admin.events.assign-speakers', $event) }}"
                                                            class="text-purple-600 hover:text-purple-800" title="Assign Speaker">
                                                            <i data-lucide="user-plus" class="w-4 h-4"></i>
                                                        </a>
                                                        <a href="{{ route('admin.events.show', $event) }}" class="text-teal-600 hover:text-teal-800"
                                                            title="View Event">
                                                            <i data-lucide="eye" class="w-4 h-4"></i>
                                                        </a>
                                                        <a href="{{ route('admin.events.edit', $event->slug) }}"
                                                            class="text-blue-600 hover:text-blue-800" title="Edit Event">
                                                            <i data-lucide="edit" class="w-4 h-4"></i>
                                                        </a>
                                                        <button type="button" title="Delete" class="text-red-600 hover:text-red-800"
                                                            onclick="if(confirm('Are you sure?')) { document.getElementById('deleteForm-{{ $event->id }}').submit(); }">
                                                            <i data-lucide="trash-2" class="w-4 h-4"></i>
                                                        </button>
                                                        <form id="deleteForm-{{ $event->id }}" action="{{ route('admin.events.destroy', $event) }}"
                                                            method="POST" class="hidden">
                                                            @csrf
                                                            @method('DELETE')
                                                        </form>
                                                    </div>
                                                </td>
                                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="text-center py-12 text-gray-500">
                                    <div class="flex flex-col items-center gap-3">
                                        <i data-lucide="calendar-off" class="w-8 h-8 text-gray-300"></i>
                                        <p class="text-sm">No events found. Create one to get started.</p>
                                        <a href="{{ route('admin.events.create') }}"
                                            class="inline-flex items-center px-4 py-2 bg-teal-600 text-white text-sm font-medium rounded hover:bg-teal-700 transition shadow-sm">
                                            <i data-lucide="plus-circle" class="w-4 h-4 mr-2"></i> Create Event
                                        </a>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </form>
        </div>

    </div>

    <!-- Scripts -->
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const selectAll = document.getElementById('selectAll');
            const rowCheckboxes = document.querySelectorAll('.rowCheckbox');
            const massActionsBar = document.getElementById('massActionsBar');
            const selectedCount = document.getElementById('selectedCount');
            const massDeleteBtn = document.getElementById('massDeleteBtn');
            const massActionForm = document.getElementById('massActionForm');

            function updateMassActionsBar() {
                const selected = [...rowCheckboxes].filter(cb => cb.checked).length;
                if (selected > 0) {
                    massActionsBar.classList.remove('hidden');
                    selectedCount.textContent = selected;
                    massDeleteBtn.disabled = false;
                } else {
                    massActionsBar.classList.add('hidden');
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

            massDeleteBtn.addEventListener('click', (e) => {
                e.preventDefault();
                if (confirm('Are you sure you want to delete selected events?')) {
                    massActionForm.submit();
                }
            });
        });
    </script>
</x-app-layout>
