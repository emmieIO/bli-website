<x-app-layout>
    <!-- Header and Controls -->
    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 mb-6">
        <div>
            <h2 class="text-2xl font-bold text-primary font-montserrat">Instructor Management</h2>
            <p class="text-sm text-gray-500 mt-1 font-lato">Manage all instructor profiles and applications</p>
        </div>
    </div>
    
    <x-instructor-dashbord-layout>
        <div class="space-y-6">
            <!-- Mass Actions Bar -->
            <div id="massActionsBar" class="hidden bg-primary-50 px-6 py-3 border-b border-primary-200 rounded-t-lg">
                <div class="flex items-center justify-between">
                    <p class="text-sm text-primary-700 font-lato"><span id="selectedCount">0</span> instructors selected</p>
                    <button id="massDeleteBtn"
                        class="text-secondary hover:text-secondary-600 text-sm font-medium inline-flex items-center gap-2 disabled:opacity-50 disabled:cursor-not-allowed font-lato transition-colors"
                        disabled>
                        <i data-lucide="trash-2" class="w-4 h-4"></i> Delete Selected
                    </button>
                </div>
            </div>

            <!-- Instructors Table -->
            <div class="bg-white rounded-lg shadow border border-primary-100 overflow-hidden">
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-primary-100 text-sm">
                        <thead class="bg-primary-50">
                            <tr>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-semibold text-primary uppercase tracking-wider font-montserrat w-4">
                                    <input type="checkbox" id="selectAll"
                                        class="rounded border-gray-300 text-primary focus:ring-primary">
                                </th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-semibold text-primary uppercase tracking-wider font-montserrat">
                                    <div class="flex items-center gap-1">
                                        Profile ID
                                        <button type="button" class="text-primary/60 hover:text-primary">
                                            <i data-lucide="arrow-up-down" class="w-3 h-3"></i>
                                        </button>
                                    </div>
                                </th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-semibold text-primary uppercase tracking-wider font-montserrat">
                                    Instructor
                                </th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-semibold text-primary uppercase tracking-wider font-montserrat">
                                    Contact
                                </th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-semibold text-primary uppercase tracking-wider font-montserrat">
                                    Headline
                                </th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-semibold text-primary uppercase tracking-wider font-montserrat">
                                    Deleted At
                                </th>
                                <th scope="col" class="px-6 py-3 text-right text-xs font-semibold text-primary uppercase tracking-wider font-montserrat">
                                    Actions
                                </th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-primary-100">
                            @forelse ($instructors as $instructor)
                                <tr class="hover:bg-primary-50 transition-colors">
                                    <!-- Checkbox -->
                                    <td class="px-6 py-4">
                                        <input type="checkbox" name="selected_instructors[]"
                                            value="{{ $instructor->id }}"
                                            class="rowCheckbox rounded border-gray-300 text-primary focus:ring-primary">
                                    </td>

                                    <!-- Profile ID -->
                                    <td class="px-6 py-4 font-medium text-primary whitespace-nowrap font-montserrat">
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-primary-100 text-primary-800 font-montserrat">
                                            {{ $instructor->application_id }}
                                        </span>
                                    </td>

                                    <!-- Instructor Info -->
                                    <td class="px-6 py-4">
                                        <div class="flex items-center gap-3">
                                            <div>
                                                <div class="font-medium text-primary font-montserrat">{{ $instructor->user->name }}</div>
                                                <div class="text-xs text-gray-500 font-lato">
                                                    Joined {{ $instructor->created_at->format('M Y') }}
                                                </div>
                                            </div>
                                        </div>
                                    </td>

                                    <!-- Contact Info -->
                                    <td class="px-6 py-4">
                                        <div class="text-sm text-primary font-montserrat">{{ $instructor->user->email }}</div>
                                        <div class="text-sm text-gray-500 font-lato">{{ $instructor->user->phone ?? 'No phone' }}</div>
                                    </td>

                                    <!-- Headline -->
                                    <td class="px-6 py-4">
                                        <div class="text-sm text-primary font-montserrat line-clamp-2">{{ $instructor->headline }}</div>
                                    </td>
                                    
                                    <!-- Deleted At -->
                                    <td class="px-6 py-4">
                                        <div class="text-sm text-primary font-montserrat line-clamp-2">{{ sweet_date($instructor->deleted_at) }}</div>
                                    </td>

                                    <!-- Actions -->
                                    <td class="px-6 py-4 text-right">
                                        <div class="inline-flex gap-2">
                                            @if ($instructor->trashed())
                                                <button type="button"
                                                    data-restore-route="{{ route('admin.instructors.restore', $instructor) }}"
                                                    data-modal-target="restore-instructor"
                                                    data-modal-toggle="restore-instructor"
                                                    onclick="confirmInstructorRestore(this, '{{ $instructor->user->name }}')"
                                                    class="p-1.5 text-accent hover:text-accent-600 rounded-full hover:bg-accent-100 transition-colors"
                                                    title="Restore instructor" aria-label="Restore instructor">
                                                    <i data-lucide="rotate-ccw" class="size-4"></i>
                                                </button>
                                            @else
                                                <a href="{{ route('admin.instructors.edit', $instructor) }}"
                                                    title="Edit"
                                                    class="p-1.5 text-primary hover:text-primary-600 rounded-full hover:bg-primary-100 transition-colors"
                                                    aria-label="Edit instructor">
                                                    <i data-lucide="edit" class="size-4"></i>
                                                </a>
                                                <a href="{{ route('admin.instructors.view', $instructor) }}" title="View"
                                                    class="p-1.5 text-primary hover:text-primary-600 rounded-full hover:bg-primary-100 transition-colors"
                                                    aria-label="View instructor">
                                                    <i data-lucide="eye" class="size-4"></i>
                                                </a>
                                                <button
                                                    data-delete-route="{{ route('admin.instructors.destroy', $instructor) }}"
                                                    data-modal-target="delete-instructor-modal"
                                                    data-modal-toggle="delete-instructor-modal"
                                                    onclick="confirmInstructorDelete(this, '{{ $instructor->user->name }}')"
                                                    title="Delete"
                                                    class="p-1.5 text-secondary hover:text-secondary-600 rounded-full hover:bg-secondary-100 transition-colors"
                                                    aria-label="Delete instructor">
                                                    <i data-lucide="trash" class="size-4"></i>
                                                </button>
                                            @endif
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="7" class="px-6 py-12 text-center">
                                        <div class="flex flex-col items-center justify-center space-y-4 text-gray-400">
                                            <i data-lucide="users" class="w-12 h-12"></i>
                                            <h3 class="text-lg font-medium text-primary font-montserrat">No instructors found</h3>
                                            <p class="max-w-md text-center font-lato">Add your first instructor to get started</p>
                                            <a href=""
                                                class="mt-2 inline-flex items-center px-4 py-2 bg-primary text-white text-sm font-medium rounded-lg hover:bg-primary-600 transition shadow-sm font-montserrat">
                                                <i data-lucide="plus" class="size-4 mr-2"></i>
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
                @if ($instructors->hasPages())
                    <div class="px-6 py-4 border-t border-primary-100 bg-primary-50">
                        {{ $instructors->links() }}
                    </div>
                @endif
            </div>
        </div>

        <!-- Delete Confirmation Modal -->
        <div id="delete-instructor-modal" tabindex="-1" aria-hidden="true"
            class="hidden overflow-y-auto overflow-x-hidden fixed top-0 right-0 left-0 z-50 justify-center items-center w-full md:inset-0 h-[calc(100%-1rem)] max-h-full bg-black/50 backdrop-blur-sm">
            <div class="relative p-4 w-full max-w-md max-h-full">
                <div class="relative bg-white rounded-lg shadow border border-secondary-100">
                    <div class="flex items-center justify-between p-4 md:p-5 border-b rounded-t">
                        <h3 class="text-lg font-semibold text-secondary font-montserrat">
                            Confirm Instructor Removal
                        </h3>
                        <button type="button" data-modal-hide="delete-instructor-modal"
                            class="text-gray-400 bg-transparent hover:bg-gray-200 hover:text-secondary rounded-lg text-sm w-8 h-8 inline-flex justify-center items-center transition-colors">
                            <i data-lucide="x" class="w-4 h-4"></i>
                            <span class="sr-only">Close modal</span>
                        </button>
                    </div>
                    <div class="p-4 md:p-5 space-y-4">
                        <div class="flex flex-col items-center text-center">
                            <div class="w-12 h-12 bg-secondary-100 rounded-full flex items-center justify-center mb-4">
                                <i data-lucide="alert-triangle" class="w-6 h-6 text-secondary"></i>
                            </div>
                            <h3 class="text-lg font-medium text-secondary font-montserrat mb-2">Remove Instructor</h3>
                            <div class="text-sm text-gray-500 font-lato">
                                <p>Are you sure you want to remove <span id="instructor-name" class="font-semibold text-primary"></span>?</p>
                                <p class="mt-1">This will revoke their instructor privileges but keep their user account.</p>
                            </div>
                        </div>
                    </div>
                    <div class="flex items-center justify-end p-4 md:p-5 border-t border-gray-200 rounded-b gap-3">
                        <button data-modal-hide="delete-instructor-modal" type="button"
                            class="py-2.5 px-5 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-lg hover:bg-gray-100 transition-colors font-lato">
                            Cancel
                        </button>
                        <form method="POST" id="delete-instructor-form" class="inline">
                            @csrf
                            @method('DELETE')
                            <button type="submit"
                                class="text-white bg-secondary hover:bg-secondary-600 font-medium rounded-lg text-sm px-5 py-2.5 text-center inline-flex items-center transition-colors font-montserrat">
                                <i data-lucide="trash" class="size-4 mr-2"></i>
                                Remove Instructor
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <!-- Restore Instructor Profile -->
        <div id="restore-instructor" tabindex="-1" aria-hidden="true"
            class="hidden overflow-y-auto overflow-x-hidden fixed top-0 right-0 left-0 z-50 justify-center items-center w-full md:inset-0 h-[calc(100%-1rem)] max-h-full bg-black/50 backdrop-blur-sm">
            <div class="relative p-4 w-full max-w-md max-h-full">
                <div class="relative bg-white rounded-lg shadow border border-accent-100">
                    <div class="flex items-center justify-between p-4 md:p-5 border-b rounded-t">
                        <h3 class="text-lg font-semibold text-accent font-montserrat">
                            Confirm Instructor Restore
                        </h3>
                        <button type="button" data-modal-hide="restore-instructor"
                            class="text-gray-400 bg-transparent hover:bg-gray-200 hover:text-accent rounded-lg text-sm w-8 h-8 inline-flex justify-center items-center transition-colors">
                            <i data-lucide="x" class="w-4 h-4"></i>
                            <span class="sr-only">Close modal</span>
                        </button>
                    </div>
                    <div class="p-4 md:p-5 space-y-4">
                        <div class="flex flex-col items-center text-center">
                            <div class="w-12 h-12 bg-accent-100 rounded-full flex items-center justify-center mb-4">
                                <i data-lucide="rotate-ccw" class="w-6 h-6 text-accent"></i>
                            </div>
                            <h3 class="text-lg font-medium text-accent font-montserrat mb-2">Restore Instructor</h3>
                            <div class="text-sm text-gray-500 font-lato">
                                <p>Are you sure you want to restore <span id="instructor-restore-name" class="font-semibold text-primary"></span>?</p>
                                <p class="mt-1">This will restore their instructor privileges and reactivate their profile.</p>
                            </div>
                        </div>
                    </div>
                    <div class="flex items-center justify-end p-4 md:p-5 border-t border-gray-200 rounded-b gap-3">
                        <button data-modal-hide="restore-instructor" type="button"
                            class="py-2.5 px-5 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-lg hover:bg-gray-100 transition-colors font-lato">
                            Cancel
                        </button>
                        <form method="POST" id="restore-instructor-form" class="inline">
                            @csrf
                            @method('PUT')
                            <button type="submit"
                                class="text-white bg-accent hover:bg-accent-600 font-medium rounded-lg text-sm px-5 py-2.5 text-center inline-flex items-center transition-colors font-montserrat">
                                <i data-lucide="rotate-ccw" class="size-4 mr-2"></i>
                                Restore Instructor
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
                const instructorRestoreForm = document.getElementById('restore-instructor-form');

                window.confirmInstructorDelete = function(button, instructorName) {
                    const actionRoute = button.getAttribute('data-delete-route');
                    instructorDeleteForm.action = actionRoute;
                    document.getElementById('instructor-name').textContent = instructorName;
                    document.getElementById('instructor-delete-title').textContent = `Remove ${instructorName}`;
                }

                // Instructor Restore Modal
                window.confirmInstructorRestore = function(button, instructorName){
                    const actionRoute = button.getAttribute('data-restore-route');
                    instructorRestoreForm.action = actionRoute;
                    document.getElementById('instructor-restore-name').textContent = instructorName;
                    document.getElementById('instructor-restore-title').textContent = `Restore ${instructorName}`;
                }
            });
        </script>
    </x-instructor-dashbord-layout>
</x-app-layout>