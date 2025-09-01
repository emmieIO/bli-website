<x-app-layout>
                <!-- Header and Controls -->
                <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
                    <div>
                        <h2 class="text-xl font-semibold text-gray-800">Instructor Management</h2>
                        <p class="text-sm text-gray-500 mt-1">Manage all instructor profiles and applications</p>
                    </div>
                    <div class="flex flex-col sm:flex-row gap-3 w-full sm:w-auto">
                        <div class="relative w-full sm:w-64">
                            <input type="text" placeholder="Search instructors..."
                                class="w-full pl-10 pr-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-teal-500 focus:border-teal-500 text-sm">
                            <i data-lucide="search" class="absolute left-3 top-2.5 text-gray-400 w-5 h-5"></i>
                        </div>
                        <button type="button"
                            class="inline-flex items-center px-4 py-2 bg-teal-600 text-white text-sm font-medium rounded-lg hover:bg-teal-700 transition shadow-sm">
                            <i data-lucide="plus" class="w-4 h-4 mr-2"></i> Add Instructor
                        </button>
                    </div>
                </div>
    <x-instructor-dashbord-layout>
        <div class="space-y-6">


            <!-- Mass Actions Bar -->
            <div id="massActionsBar" class="hidden bg-gray-50 px-6 py-3 border border-b border-gray-200 rounded-t-lg">
                <div class="flex items-center justify-between">
                    <p class="text-sm text-gray-600"><span id="selectedCount">0</span> instructors selected</p>
                    <button id="massDeleteBtn"
                        class="text-red-600 hover:text-red-800 text-sm font-medium inline-flex items-center gap-2 disabled:opacity-50 disabled:cursor-not-allowed"
                        disabled>
                        <i data-lucide="trash-2" class="w-4 h-4"></i> Delete Selected
                    </button>
                </div>
            </div>

            <!-- Instructors Table -->
            <div class="bg-white rounded-lg shadow border border-gray-100 overflow-hidden">
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200 text-sm">
                        <thead class="bg-gray-50">
                            <tr>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider w-4">
                                    <input type="checkbox" id="selectAll"
                                        class="rounded border-gray-300 text-teal-600 focus:ring-teal-500">
                                </th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    <div class="flex items-center gap-1">
                                        Profile ID
                                        <button type="button" class="text-gray-400 hover:text-gray-500">
                                            <i data-lucide="arrow-up-down" class="w-3 h-3"></i>
                                        </button>
                                    </div>
                                </th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Instructor
                                </th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Contact
                                </th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Headline
                                </th>
                                <th scope="col" class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Actions
                                </th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @forelse ($instructors as $instructor)
                            <tr class="hover:bg-gray-50 transition-colors">
                                <!-- Checkbox -->
                                <td class="px-6 py-4">
                                    <input type="checkbox" name="selected_instructors[]" value="{{ $instructor->id }}"
                                        class="rowCheckbox rounded border-gray-300 text-teal-600 focus:ring-teal-500">
                                </td>

                                <!-- Profile ID -->
                                <td class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap">
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-800">
                                        {{ $instructor->application_id }}
                                    </span>
                                </td>

                                <!-- Instructor Info -->
                                <td class="px-6 py-4">
                                    <div class="flex items-center gap-3">
                                        <div class="flex-shrink-0 h-10 w-10">
                                            <img class="h-10 w-10 rounded-full object-cover"
                                                src="{{ $instructor->user->profile_photo_url }}"
                                                alt="{{ $instructor->user->name }}">
                                        </div>
                                        <div>
                                            <div class="font-medium text-gray-900">{{ $instructor->user->name }}</div>
                                            <div class="text-xs text-gray-500">
                                                Joined {{ $instructor->created_at->format('M Y') }}
                                            </div>
                                        </div>
                                    </div>
                                </td>

                                <!-- Contact Info -->
                                <td class="px-6 py-4">
                                    <div class="text-sm text-gray-900">{{ $instructor->user->email }}</div>
                                    <div class="text-sm text-gray-500">{{ $instructor->user->phone ?? 'No phone' }}</div>
                                </td>

                                <!-- Headline -->
                                <td class="px-6 py-4">
                                    <div class="text-sm text-gray-900 line-clamp-2">{{ $instructor->headline }}</div>
                                </td>

                                <!-- Actions -->
                                <td class="px-6 py-4 text-right">
                                    <div class="inline-flex gap-2">
                                        <a href="{{ route('admin.instructors.edit', $instructor) }}"
                                            title="Edit"
                                            class="p-1.5 text-blue-600 hover:text-blue-800 rounded-full hover:bg-blue-50 transition-colors"
                                            aria-label="Edit instructor">
                                            <i data-lucide="edit" class="w-5 h-5"></i>
                                        </a>
                                        <a href=""
                                            title="View"
                                            class="p-1.5 text-teal-600 hover:text-teal-800 rounded-full hover:bg-teal-50 transition-colors"
                                            aria-label="View instructor">
                                            <i data-lucide="eye" class="w-5 h-5"></i>
                                        </a>
                                        <button
                                            data-delete-route=""
                                            data-modal-target="delete-instructor-modal"
                                            data-modal-toggle="delete-instructor-modal"
                                            onclick="confirmInstructorDelete(this, '{{ $instructor->user->name }}')"
                                            title="Delete"
                                            class="p-1.5 text-red-600 hover:text-red-800 rounded-full hover:bg-red-50 transition-colors"
                                            aria-label="Delete instructor">
                                            <i data-lucide="trash-2" class="w-5 h-5"></i>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="6" class="px-6 py-12 text-center">
                                    <div class="flex flex-col items-center justify-center space-y-4 text-gray-400">
                                        <i data-lucide="users" class="w-12 h-12"></i>
                                        <h3 class="text-lg font-medium text-gray-900">No instructors found</h3>
                                        <p class="max-w-md text-center">Add your first instructor to get started</p>
                                        <a href=""
                                            class="mt-2 inline-flex items-center px-4 py-2 bg-teal-600 text-white text-sm font-medium rounded-lg hover:bg-teal-700 transition shadow-sm">
                                            <i data-lucide="plus" class="w-4 h-4 mr-2"></i>
                                            Add Instructor
                                        </a>
                                    </div>
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <!-- Pagination -->
                @if($instructors->hasPages())
                    <div class="px-6 py-4 border-t border-gray-200 bg-gray-50">
                        {{ $instructors->links() }}
                    </div>
                @endif
            </div>
        </div>

        <!-- Delete Confirmation Modal -->
        <div id="delete-instructor-modal" tabindex="-1" aria-hidden="true"
            class="hidden overflow-y-auto overflow-x-hidden fixed top-0 right-0 left-0 z-50 justify-center items-center w-full md:inset-0 h-[calc(100%-1rem)] max-h-full">
            <div class="relative p-4 w-full max-w-md max-h-full">
                <div class="relative bg-white rounded-lg shadow">
                    <div class="flex items-center justify-between p-4 md:p-5 border-b rounded-t ">
                        <h3 class="text-lg font-semibold text-gray-900 ">
                            Confirm Instructor Removal
                        </h3>
                        <button type="button"
                            class="text-gray-400 bg-transparent hover:bg-gray-200 hover:text-gray-900 rounded-lg text-sm w-8 h-8 ms-auto inline-flex justify-center items-center"
                            data-modal-hide="delete-instructor-modal">
                            <i data-lucide="x" class="w-4 h-4"></i>
                            <span class="sr-only">Close modal</span>
                        </button>
                    </div>
                    <div class="p-4 md:p-5 space-y-4">
                        <div class="flex flex-col items-center text-center">
                            <div class="mx-auto flex-shrink-0 flex items-center justify-center h-12 w-12 rounded-full bg-red-100 mb-4">
                                <i data-lucide="alert-triangle" class="h-6 w-6 text-red-600"></i>
                            </div>
                            <h3 class="text-lg font-medium text-gray-900 mb-2" id="instructor-delete-title">Remove Instructor</h3>
                            <div class="text-sm text-gray-500">
                                <p>Are you sure you want to remove <span id="instructor-name" class="font-semibold text-gray-900"></span>?</p>
                                <p class="mt-1">This will revoke their instructor privileges but keep their user account.</p>
                            </div>
                        </div>
                    </div>
                    <div class="flex items-center justify-end p-4 md:p-5 border-t border-gray-200 rounded-b gap-3">
                        <button data-modal-hide="delete-instructor-modal" type="button"
                            class="py-2.5 px-5 text-sm font-medium text-gray-900 focus:outline-none bg-white rounded-lg border border-gray-200 hover:bg-gray-100 focus:z-10 focus:ring-4 focus:ring-gray-100 ">
                            Cancel
                        </button>
                        <form method="POST" id="delete-instructor-form" class="inline">
                            @csrf
                            @method('DELETE')
                            <button type="submit"
                                class="text-white bg-red-600 hover:bg-red-700 focus:ring-4 focus:outline-none focus:ring-red-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center inline-flex items-center">
                                <i data-lucide="trash-2" class="w-4 h-4 mr-2"></i>
                                Remove Instructor
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

                // Instructor deletion modal
                const instructorDeleteForm = document.getElementById('delete-instructor-form');

                window.confirmInstructorDelete = function(button, instructorName) {
                    const actionRoute = button.getAttribute('data-delete-route');
                    instructorDeleteForm.action = actionRoute;
                    document.getElementById('instructor-name').textContent = instructorName;
                    document.getElementById('instructor-delete-title').textContent = `Remove ${instructorName}`;
                }
            });
        </script>
    </x-instructor-dashbord-layout>
</x-app-layout>
