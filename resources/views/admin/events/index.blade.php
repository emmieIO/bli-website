<x-app-layout>
    <div class="mb-8">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 mb-6">
            <h2 class="text-2xl font-semibold text-teal-800 flex items-center gap-2">
                <i data-lucide="calendar-days" class="w-5 h-5"></i>
                Events
            </h2>
            <a href="{{ route('admin.events.create') }}"
                class="bg-teal-600 hover:bg-teal-700 text-white py-2 px-4 rounded-lg inline-flex items-center gap-2 transition-colors shadow-sm">
                <i data-lucide="plus-circle" class="w-4 h-4"></i>
                <span class="text-sm">Create Event</span>
            </a>
        </div>

        <!-- Filter & Search Controls -->
        <div class="bg-white p-5 rounded-xl shadow-xs border border-gray-100 mb-8">
            <!-- ... (keep your existing filter controls) ... -->
        </div>
    </div>

    <!-- Events Table with Mass Actions -->
    <div class="bg-white rounded-xl shadow-xs border border-gray-100 overflow-hidden">
        <!-- Mass Action Bar (hidden by default, shows when checkboxes are selected) -->
        <div id="massActionsBar" class="hidden bg-gray-50 px-6 py-3 border-b border-gray-200">
            <div class="flex items-center justify-between">
                <div class="text-sm text-gray-600">
                    <span id="selectedCount">0</span> selected
                </div>
                <div>
                    <button id="massDeleteBtn"
                        class="text-red-600 hover:text-red-800 text-sm font-medium flex items-center gap-2 disabled:opacity-50 disabled:cursor-not-allowed"
                        disabled>
                        <i data-lucide="trash-2" class="w-4 h-4"></i>
                        Delete Selected
                    </button>
                </div>
            </div>
        </div>

        <div class="overflow-x-auto">
            <form id="massActionForm" action="{{ route("admin.events.massDelete") }}" method="POST">
                @csrf
                @method('DELETE')
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th scope="col"
                                class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                <input type="checkbox" id="selectAll"
                                    class="rounded border-gray-300 text-teal-600 focus:ring-teal-500">
                            </th>
                            <th scope="col"
                                class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Event</th>
                            <th scope="col"
                                class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Location</th>
                            <th scope="col"
                                class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Dates</th>
                            <th scope="col"
                                class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Type</th>
                            <th scope="col"
                                class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Created</th>
                            <th scope="col"
                                class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Actions</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @forelse($events as $event)
                                            <tr class="hover:bg-gray-50 transition-colors">
                                                <td class="px-6 py-4 whitespace-nowrap">
                                                    <input type="checkbox" name="selected_events[]" value="{{ $event->id }}"
                                                        class="rowCheckbox rounded border-gray-300 text-teal-600 focus:ring-teal-500">
                                                </td>
                                                <td class="px-6 py-4 whitespace-nowrap">
                                                    <div class="flex items-center">
                                                        <div class="text-sm font-medium text-teal-800">{{ $event->title }}</div>
                                                    </div>
                                                </td>
                                                <td class="px-6 py-4 whitespace-nowrap">
                                                    <div class="flex items-center gap-2 text-sm text-gray-600">
                                                        <i data-lucide="map-pin" class="w-4 h-4 text-gray-400 flex-shrink-0"></i>
                                                        <span class="truncate max-w-[200px]">{{ $event->location }}</span>
                                                    </div>
                                                </td>
                                                <td class="px-6 py-4 whitespace-nowrap">
                                                    <div class="text-sm text-gray-600">
                                                        <div>{{ sweet_date($event->start_date) }}</div>
                                                        <div class="text-xs text-gray-400">to {{ sweet_date($event->end_date) }}</div>
                                                    </div>
                                                </td>
                                                <td class="px-6 py-4 whitespace-nowrap">
                                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full {{
                            $event->mode === 'online' ? 'bg-blue-100 text-blue-800' :
                            ($event->mode === 'offline' ? 'bg-green-100 text-green-800' : 'bg-purple-100 text-purple-800')
                                                    }}">
                                                        {{ $event->mode }}
                                                    </span>
                                                </td>
                                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                                    {{ $event->created_at->diffForHumans() }}
                                                </td>
                                                <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                                    <div class="flex items-center justify-end space-x-3">
                                                        <a href="{{ route('admin.events.show', $event) }}" class="text-teal-600 hover:text-teal-900 transition-colors"
                                                            title="View">
                                                            <i data-lucide="eye" class="w-4 h-4"></i>
                                                        </a>
                                                        <a href="{{ route('admin.events.edit', $event->slug) }}"
                                                            class="text-blue-600 hover:text-blue-900 transition-colors" title="Edit">
                                                            <i data-lucide="edit" class="w-4 h-4"></i>
                                                        </a>
                                                        <button type="button" class="text-red-600 hover:text-red-900 transition-colors"
                                                            title="Delete"
                                                            onclick="if(confirm('Are you sure you want to delete this event?')) { document.getElementById('deleteForm-{{ $event->id }}').submit(); }">
                                                            <i data-lucide="trash-2" class="w-4 h-4"></i>
                                                        </button>
                                                        <form id="deleteForm-{{ $event->id }}"
                                                            action="{{ route('admin.events.destroy', $event) }}" method="POST"
                                                            class="hidden">
                                                            @csrf
                                                            @method('DELETE')
                                                        </form>
                                                    </div>
                                                </td>
                                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="px-6 py-12 text-center">
                                    <div
                                        class="mx-auto w-16 h-16 rounded-full bg-gray-100 flex items-center justify-center mb-4">
                                        <i data-lucide="calendar-off" class="w-8 h-8 text-gray-400"></i>
                                    </div>
                                    <h3 class="text-lg font-medium text-gray-900 mb-1">No events found</h3>
                                    <p class="text-gray-500 mb-4">Create your first event to get started</p>
                                    <a href="{{ route('admin.events.create') }}"
                                        class="bg-teal-600 hover:bg-teal-700 text-white py-2 px-4 rounded-lg inline-flex items-center gap-2 transition-colors shadow-sm">
                                        <i data-lucide="plus-circle" class="w-4 h-4"></i>
                                        <span>Create Event</span>
                                    </a>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </form>
        </div>

        <!-- Pagination -->
        {{-- @if($events->hasPages())
        <div class="px-6 py-4 border-t border-gray-200">
            {{ $events->links() }}
        </div>
        @endif --}}
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const selectAll = document.getElementById('selectAll');
            const rowCheckboxes = document.querySelectorAll('.rowCheckbox');
            const massActionsBar = document.getElementById('massActionsBar');
            const selectedCount = document.getElementById('selectedCount');
            const massDeleteBtn = document.getElementById('massDeleteBtn');
            const massActionForm = document.getElementById('massActionForm');

            // Select/Deselect all checkboxes
            selectAll.addEventListener('change', function () {
                rowCheckboxes.forEach(checkbox => {
                    checkbox.checked = selectAll.checked;
                });
                updateMassActionsBar();
            });

            // Update select all checkbox when individual checkboxes change
            rowCheckboxes.forEach(checkbox => {
                checkbox.addEventListener('change', function () {
                    selectAll.checked = [...rowCheckboxes].every(cb => cb.checked);
                    updateMassActionsBar();
                });
            });

            // Update the mass actions bar
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

            // Mass delete button handler
            massDeleteBtn.addEventListener('click', function (e) {
                e.preventDefault();
                if (confirm('Are you sure you want to delete the selected events?')) {
                    massActionForm.submit();
                }
            });
        });
    </script>
</x-app-layout>