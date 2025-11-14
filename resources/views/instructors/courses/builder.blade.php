<x-app-layout>
    <!-- Header Section -->
    <div class="bg-blue-50 rounded-2xl p-8 mb-8" data-aos="fade-down">
        <div class="lg:flex justify-between items-start">
            <div class="flex-1">
                <div class="flex items-center mb-4">
                    <div class="w-12 h-12 rounded-2xl bg-primary flex items-center justify-center mr-4">
                        <i data-lucide="hammer" class="w-6 h-6 text-white"></i>
                    </div>
                    <div>
                        <h2 class="text-3xl font-bold font-montserrat" style="color: #002147;">
                            Course Builder
                        </h2>
                        <p class="text-gray-600 font-lato">{{ $course->title }}</p>
                    </div>
                </div>
                <p class="text-gray-600 font-lato leading-relaxed max-w-2xl">
                    Build your course content by adding modules and lessons. Structure your course to create an engaging
                    learning experience.
                </p>
            </div>
            <div class="mt-6 lg:mt-0 flex gap-3">
                <a href="{{ route('instructor.courses.show', $course) }}"
                    class="inline-flex items-center px-4 py-2 border-2 border-gray-300 rounded-xl text-sm font-semibold font-montserrat hover:border-gray-400 transition-all duration-300"
                    style="color: #002147;">
                    <i data-lucide="arrow-left" class="w-4 h-4 mr-2"></i>
                    Back to Course
                </a>
                <button type="button"
                    class="inline-flex items-center px-6 py-3 rounded-xl font-semibold font-montserrat bg-primary hover:bg-secondary text-white transition-colors duration-300 shadow-lg hover:shadow-xl"
                    onclick="addModule()">
                    <i data-lucide="plus" class="w-5 h-5 mr-2"></i>
                    Add Module
                </button>
            </div>
        </div>
    </div>

    <!-- Course Overview Card -->
    <div class="bg-white rounded-2xl shadow-xl border border-gray-100 p-6 mb-8" data-aos="fade-up">
        <div class="flex items-center justify-between mb-4">
            <h3 class="text-xl font-bold font-montserrat" style="color: #002147;">Course Overview</h3>
            <span
                class="px-3 py-1 rounded-full text-xs font-semibold font-montserrat flex items-center bg-blue-100 text-blue-800">
                <i data-lucide="layers" class="w-3 h-3 mr-1"></i>
                <span class="stats-modules">{{ $course->modules->count() }}</span>
                Module{{ $course->modules->count() !== 1 ? 's' : '' }}
            </span>
        </div>
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            <div class="text-center p-4 bg-gray-50 rounded-xl">
                <div class="w-12 h-12 rounded-xl bg-blue-100 flex items-center justify-center mx-auto mb-3">
                    <i data-lucide="play-circle" class="w-6 h-6 text-blue-600"></i>
                </div>
                <p class="text-2xl font-bold font-montserrat stats-lessons" style="color: #002147;">
                    {{ $course->modules->sum(function ($module) {
    return $module->lessons->count(); }) }}
                </p>
                <p class="text-sm text-gray-600 font-lato">Total Lessons</p>
            </div>
            <div class="text-center p-4 bg-gray-50 rounded-xl">
                <div class="w-12 h-12 rounded-xl bg-green-100 flex items-center justify-center mx-auto mb-3">
                    <i data-lucide="clock" class="w-6 h-6 text-green-600"></i>
                </div>
                <p class="text-2xl font-bold font-montserrat" style="color: #002147;">0h 0m</p>
                <p class="text-sm text-gray-600 font-lato">Total Duration</p>
            </div>
            <div class="text-center p-4 bg-gray-50 rounded-xl">
                <div class="w-12 h-12 rounded-xl bg-purple-100 flex items-center justify-center mx-auto mb-3">
                    <i data-lucide="users" class="w-6 h-6 text-purple-600"></i>
                </div>
                <p class="text-2xl font-bold font-montserrat" style="color: #002147;">{{ $course->students->count() }}
                </p>
                <p class="text-sm text-gray-600 font-lato">Enrolled Students</p>
            </div>
        </div>
    </div>

    <!-- Course Content Builder -->
    <div class="bg-white rounded-2xl shadow-xl border border-gray-100 overflow-hidden" data-aos="fade-up"
        data-aos-delay="200">
        <div class="p-6 border-b border-gray-100 bg-gray-50">
            <h3 class="text-xl font-bold font-montserrat" style="color: #002147;">Course Content</h3>
            <p class="text-sm text-gray-600 font-lato mt-1">Organize your course into modules and lessons</p>
        </div>

        <div class="p-6" id="course-content">
            @forelse($course->modules as $module)
                <div class="module-container border border-gray-200 rounded-xl mb-6 last:mb-0"
                    data-module-id="{{ $module->id }}">
                    <!-- Module Header -->
                    <div class="bg-gray-50 p-4 rounded-t-xl border-b border-gray-200">
                        <div class="flex items-center justify-between">
                            <div class="flex items-center flex-1">
                                <div class="w-8 h-8 rounded-lg bg-primary flex items-center justify-center mr-3">
                                    <i data-lucide="folder" class="w-4 h-4 text-white"></i>
                                </div>
                                <div class="flex-1">
                                    <h4 class="font-bold font-montserrat module-title" style="color: #002147;">
                                        Module {{ $loop->iteration }}: {{ $module->title }}
                                    </h4>
                                    <p class="text-sm text-gray-600 font-lato">{{ $module->lessons->count() }}
                                        lesson{{ $module->lessons->count() !== 1 ? 's' : '' }}</p>
                                </div>
                            </div>
                            <div class="flex items-center gap-2">
                                <button type="button" class="p-2 text-gray-400 hover:text-blue-600 transition-colors"
                                    onclick="editModule({{ $module->id }})">
                                    <i data-lucide="edit" class="w-4 h-4"></i>
                                </button>
                                <button type="button" class="p-2 text-gray-400 hover:text-red-600 transition-colors"
                                    onclick="deleteModule({{ $module->id }})">
                                    <i data-lucide="trash-2" class="w-4 h-4"></i>
                                </button>
                                <button type="button"
                                    class="p-2 text-gray-400 hover:text-gray-600 transition-colors module-toggle"
                                    onclick="toggleModule(this)">
                                    <i data-lucide="chevron-down" class="w-4 h-4"></i>
                                </button>
                            </div>
                        </div>
                    </div>

                    <!-- Module Content -->
                    <div class="module-content p-4">
                        @forelse($module->lessons as $lesson)
                            <div class="flex items-center justify-between p-3 bg-white border border-gray-100 rounded-lg mb-3 last:mb-0 hover:border-gray-200 transition-colors"
                                data-lesson-id="{{ $lesson->id }}" data-lesson-type="{{ $lesson->type }}">
                                <div class="flex items-center">
                                    <div class="w-6 h-6 rounded-lg bg-blue-100 flex items-center justify-center mr-3">
                                        @if($lesson->type === 'video')
                                            <i data-lucide="play" class="w-3 h-3 text-blue-600"></i>
                                        @elseif($lesson->type === 'pdf')
                                            <i data-lucide="file-text" class="w-3 h-3 text-red-600"></i>
                                        @elseif($lesson->type === 'link')
                                            <i data-lucide="link" class="w-3 h-3 text-green-600"></i>
                                        @else
                                            <i data-lucide="file" class="w-3 h-3 text-gray-600"></i>
                                        @endif
                                    </div>
                                    <div>
                                        <p class="font-semibold font-montserrat lesson-title" style="color: #002147;">
                                            {{ $lesson->title }}
                                        </p>
                                        <p class="text-xs text-gray-500 font-lato">{{ ucfirst($lesson->type ?? 'Video') }} •
                                            {{ $lesson->duration ?? '0' }} min
                                        </p>
                                    </div>
                                </div>
                                <div class="flex items-center gap-2">
                                    <button type="button" class="p-1 text-gray-400 hover:text-blue-600 transition-colors"
                                        onclick="editLesson({{ $lesson->id }})">
                                        <i data-lucide="edit" class="w-3 h-3"></i>
                                    </button>
                                    <button type="button" class="p-1 text-gray-400 hover:text-red-600 transition-colors"
                                        onclick="deleteLesson({{ $lesson->id }})">
                                        <i data-lucide="trash-2" class="w-3 h-3"></i>
                                    </button>
                                </div>
                            </div>
                        @empty
                            <div class="text-center py-8 text-gray-500">
                                <i data-lucide="video" class="w-12 h-12 mx-auto mb-3 text-gray-300"></i>
                                <p class="font-lato">No lessons in this module yet</p>
                                <button type="button"
                                    class="mt-3 inline-flex items-center px-4 py-2 text-sm font-semibold text-blue-600 hover:text-blue-700 transition-colors"
                                    onclick="addLesson({{ $module->id }})">
                                    <i data-lucide="plus" class="w-4 h-4 mr-2"></i>
                                    Add First Lesson
                                </button>
                            </div>
                        @endforelse

                        @if($module->lessons->count() > 0)
                            <div class="pt-3 border-t border-gray-100 mt-4">
                                <button type="button"
                                    class="inline-flex items-center px-4 py-2 text-sm font-semibold text-blue-600 hover:text-blue-700 transition-colors"
                                    onclick="addLesson({{ $module->id }})">
                                    <i data-lucide="plus" class="w-4 h-4 mr-2"></i>
                                    Add Lesson
                                </button>
                            </div>
                        @endif
                    </div>
                </div>
            @empty
                <!-- Empty State -->
                <div class="text-center py-16">
                    <div class="w-32 h-32 rounded-3xl bg-blue-50 flex items-center justify-center mx-auto mb-6">
                        <i data-lucide="layers" class="w-16 h-16" style="color: #002147; opacity: 0.6;"></i>
                    </div>
                    <h4 class="text-2xl font-bold font-montserrat mb-3" style="color: #002147;">No Modules Yet</h4>
                    <p class="text-gray-600 font-lato mb-8 max-w-md mx-auto leading-relaxed">
                        Start building your course by creating your first module. Modules help organize your content into
                        logical sections.
                    </p>
                    <button type="button"
                        class="inline-flex items-center px-6 py-3 rounded-xl font-semibold font-montserrat bg-primary hover:bg-secondary text-white transition-colors duration-300 shadow-lg hover:shadow-xl"
                        onclick="addModule()">
                        <i data-lucide="plus" class="w-5 h-5 mr-2"></i>
                        Create Your First Module
                    </button>
                </div>
            @endforelse
        </div>
    </div>

    <!-- Module/Lesson Modals will go here -->
    <!-- These would be implemented with Alpine.js or JavaScript -->

    <!-- Module Modal -->
    <div id="moduleModal" class="hidden fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full z-50">
        <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white">
            <div class="mt-3">
                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-lg font-bold text-gray-900 font-montserrat" id="moduleModalTitle">Add Module</h3>
                    <button type="button" onclick="closeModuleModal()" class="text-gray-400 hover:text-gray-600">
                        <i data-lucide="x" class="w-6 h-6"></i>
                    </button>
                </div>
                <form id="moduleForm">
                    <div class="mb-4">
                        <label for="moduleTitle" class="block text-sm font-medium text-gray-700 mb-2">Module Title
                            *</label>
                        <input type="text" id="moduleTitle" name="title" required
                            class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                    </div>
                    <div class="mb-4">
                        <label for="moduleDescription"
                            class="block text-sm font-medium text-gray-700 mb-2">Description</label>
                        <textarea id="moduleDescription" name="description" rows="3"
                            class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"></textarea>
                    </div>
                    <div class="flex justify-end gap-3">
                        <button type="button" onclick="closeModuleModal()"
                            class="px-4 py-2 bg-gray-300 text-gray-700 rounded-md hover:bg-gray-400">Cancel</button>
                        <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700">Save
                            Module</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Lesson Modal -->
    <div id="lessonModal" class="hidden fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full z-50">
        <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white">
            <div class="mt-3">
                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-lg font-bold text-gray-900 font-montserrat" id="lessonModalTitle">Add Lesson</h3>
                    <button type="button" onclick="closeLessonModal()" class="text-gray-400 hover:text-gray-600">
                        <i data-lucide="x" class="w-6 h-6"></i>
                    </button>
                </div>
                <form id="lessonForm">
                    <div class="mb-4">
                        <label for="lessonTitle" class="block text-sm font-medium text-gray-700 mb-2">Lesson Title
                            *</label>
                        <input type="text" id="lessonTitle" name="title" required
                            class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                    </div>
                    <div class="mb-4">
                        <label for="lessonType" class="block text-sm font-medium text-gray-700 mb-2">Lesson Type
                            *</label>
                        <select id="lessonType" name="type" required
                            class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                            <option value="">Select type</option>
                            <option value="video">Video</option>
                            <option value="pdf">PDF Document</option>
                            <option value="link">External Link</option>
                        </select>
                    </div>
                    <div class="mb-4">
                        <label for="lessonDescription"
                            class="block text-sm font-medium text-gray-700 mb-2">Description</label>
                        <textarea id="lessonDescription" name="description" rows="3"
                            class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"></textarea>
                    </div>
                    <div class="mb-4" id="contentPathField" style="display: none;">
                        <label for="lessonContentPath" class="block text-sm font-medium text-gray-700 mb-2">Content
                            URL/Path</label>
                        <input type="text" id="lessonContentPath" name="content_path"
                            class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                    </div>
                    <div class="flex justify-end gap-3">
                        <button type="button" onclick="closeLessonModal()"
                            class="px-4 py-2 bg-gray-300 text-gray-700 rounded-md hover:bg-gray-400">Cancel</button>
                        <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700">Save
                            Lesson</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        const courseId = {{ $course->id }};
        let currentModuleId = null;
        let currentLessonId = null;
        let isEditingModule = false;
        let isEditingLesson = false;

        // CSRF token for API requests
        const csrfTokenElement = document.querySelector('meta[name="csrf-token"]');
        const csrfToken = csrfTokenElement ? csrfTokenElement.getAttribute('content') : '{{ csrf_token() }}';

        // Module Functions
        function addModule() {
            isEditingModule = false;
            document.getElementById('moduleModalTitle').textContent = 'Add Module';
            document.getElementById('moduleForm').reset();
            document.getElementById('moduleModal').classList.remove('hidden');
        }

        function editModule(moduleId) {
            isEditingModule = true;
            currentModuleId = moduleId;
            document.getElementById('moduleModalTitle').textContent = 'Edit Module';

            // Get module data from the DOM
            const moduleContainer = document.querySelector(`[data-module-id="${moduleId}"]`);
            const title = moduleContainer.querySelector('.module-title').textContent.split(': ')[1];

            document.getElementById('moduleTitle').value = title;
            document.getElementById('moduleModal').classList.remove('hidden');
        }

        function deleteModule(moduleId) {
            if (confirm('Are you sure you want to delete this module? All lessons in this module will also be deleted.')) {
                fetch(`/instructor/courses/${courseId}/modules/${moduleId}`, {
                    method: 'DELETE',
                    headers: {
                        'X-CSRF-TOKEN': csrfToken,
                        'Content-Type': 'application/json',
                    }
                })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            document.querySelector(`[data-module-id="${moduleId}"]`).remove();
                            showMessage(data.message, 'success');
                            updateCourseStats();
                        } else {
                            showMessage(data.message, 'error');
                        }
                    })
                    .catch(error => {
                        console.error('Error:', error);
                        showMessage('An error occurred while deleting the module.', 'error');
                    });
            }
        }

        function closeModuleModal() {
            document.getElementById('moduleModal').classList.add('hidden');
            document.getElementById('moduleForm').reset();
        }

        // Lesson Functions
        function addLesson(moduleId) {
            isEditingLesson = false;
            currentModuleId = moduleId;
            document.getElementById('lessonModalTitle').textContent = 'Add Lesson';
            document.getElementById('lessonForm').reset();
            document.getElementById('contentPathField').style.display = 'none';
            document.getElementById('lessonModal').classList.remove('hidden');
        }

        function editLesson(lessonId) {
            isEditingLesson = true;
            currentLessonId = lessonId;
            document.getElementById('lessonModalTitle').textContent = 'Edit Lesson';

            // Get lesson data from the DOM
            const lessonContainer = document.querySelector(`[data-lesson-id="${lessonId}"]`);
            const title = lessonContainer.querySelector('.lesson-title').textContent;
            const type = lessonContainer.dataset.lessonType;

            document.getElementById('lessonTitle').value = title;
            document.getElementById('lessonType').value = type;

            // Show content path field if needed
            if (type === 'link') {
                document.getElementById('contentPathField').style.display = 'block';
            }

            document.getElementById('lessonModal').classList.remove('hidden');
        }

        function deleteLesson(lessonId) {
            if (confirm('Are you sure you want to delete this lesson?')) {
                const lessonContainer = document.querySelector(`[data-lesson-id="${lessonId}"]`);
                const moduleId = lessonContainer.closest('.module-container').dataset.moduleId;

                fetch(`/instructor/courses/${courseId}/modules/${moduleId}/lessons/${lessonId}`, {
                    method: 'DELETE',
                    headers: {
                        'X-CSRF-TOKEN': csrfToken,
                        'Content-Type': 'application/json',
                    }
                })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            lessonContainer.remove();
                            showMessage(data.message, 'success');
                            updateCourseStats();
                        } else {
                            showMessage(data.message, 'error');
                        }
                    })
                    .catch(error => {
                        console.error('Error:', error);
                        showMessage('An error occurred while deleting the lesson.', 'error');
                    });
            }
        }

        function closeLessonModal() {
            document.getElementById('lessonModal').classList.add('hidden');
            document.getElementById('lessonForm').reset();
            document.getElementById('contentPathField').style.display = 'none';
        }

        // Toggle module visibility
        function toggleModule(button) {
            const moduleContent = button.closest('.module-container').querySelector('.module-content');
            const icon = button.querySelector('i');

            if (moduleContent.style.display === 'none') {
                moduleContent.style.display = 'block';
                icon.style.transform = 'rotate(0deg)';
            } else {
                moduleContent.style.display = 'none';
                icon.style.transform = 'rotate(-90deg)';
            }
        }

        // Utility functions
        function showMessage(message, type) {
            // Create and show notification
            const notification = document.createElement('div');
            notification.className = `fixed top-4 right-4 p-4 rounded-lg text-white z-50 ${type === 'success' ? 'bg-green-500' : 'bg-red-500'}`;
            notification.textContent = message;
            document.body.appendChild(notification);

            setTimeout(() => {
                notification.remove();
            }, 3000);
        }

        function updateCourseStats() {
            // Recalculate course statistics
            const modules = document.querySelectorAll('.module-container');
            const totalLessons = document.querySelectorAll('[data-lesson-id]').length;

            // Update stats in the overview section
            document.querySelector('.stats-lessons').textContent = totalLessons;
            document.querySelector('.stats-modules').textContent = modules.length;
        }

        // Event Listeners
        document.addEventListener('DOMContentLoaded', function () {
            // Module form submission
            document.getElementById('moduleForm').addEventListener('submit', function (e) {
                e.preventDefault();

                const formData = new FormData(this);
                const data = {
                    title: formData.get('title'),
                    description: formData.get('description')
                };

                const url = isEditingModule
                    ? `/instructor/courses/${courseId}/modules/${currentModuleId}`
                    : `/instructor/courses/${courseId}/modules`;

                const method = isEditingModule ? 'PUT' : 'POST';

                fetch(url, {
                    method: method,
                    headers: {
                        'X-CSRF-TOKEN': csrfToken,
                        'Content-Type': 'application/json',
                    },
                    body: JSON.stringify(data)
                })
                    .then(response => {
                        console.log('Response status:', response.status);
                        console.log('Response headers:', response.headers);

                        if (!response.ok) {
                            throw new Error(`HTTP error! status: ${response.status}`);
                        }

                        return response.text().then(text => {
                            console.log('Response text:', text);
                            try {
                                return JSON.parse(text);
                            } catch (e) {
                                console.error('Failed to parse JSON:', text);
                                throw new Error('Server returned non-JSON response: ' + text.substring(0, 200));
                            }
                        });
                    })
                    .then(data => {
                        if (data.success) {
                            closeModuleModal();
                            showMessage(data.message, 'success');

                            if (!isEditingModule) {
                                // Add new module to the page
                                location.reload(); // Simple reload for now
                            } else {
                                // Update existing module
                                location.reload();
                            }
                        } else {
                            showMessage(data.message, 'error');
                        }
                    })
                    .catch(error => {
                        console.error('Error:', error);
                        showMessage('Error: ' + error.message, 'error');
                    });
            });

            // Lesson form submission
            document.getElementById('lessonForm').addEventListener('submit', function (e) {
                e.preventDefault();

                const formData = new FormData(this);
                const data = {
                    title: formData.get('title'),
                    type: formData.get('type'),
                    description: formData.get('description'),
                    content_path: formData.get('content_path')
                };

                const moduleId = currentModuleId;
                const url = isEditingLesson
                    ? `/instructor/courses/${courseId}/modules/${moduleId}/lessons/${currentLessonId}`
                    : `/instructor/courses/${courseId}/modules/${moduleId}/lessons`;

                const method = isEditingLesson ? 'PUT' : 'POST';

                fetch(url, {
                    method: method,
                    headers: {
                        'X-CSRF-TOKEN': csrfToken,
                        'Content-Type': 'application/json',
                    },
                    body: JSON.stringify(data)
                })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            closeLessonModal();
                            showMessage(data.message, 'success');
                            location.reload(); // Simple reload for now
                        } else {
                            showMessage(data.message, 'error');
                        }
                    })
                    .catch(error => {
                        console.error('Error:', error);
                        showMessage('An error occurred while saving the lesson.', 'error');
                    });
            });

            // Lesson type change handler
            document.getElementById('lessonType').addEventListener('change', function () {
                const contentPathField = document.getElementById('contentPathField');
                if (this.value === 'link') {
                    contentPathField.style.display = 'block';
                } else {
                    contentPathField.style.display = 'none';
                }
            });

            // Initialize AOS and Lucide
            AOS.init({
                duration: 800,
                easing: 'ease-out-cubic',
                once: true,
                offset: 100
            });

            // Initialize Lucide icons after AOS animations
            setTimeout(function () {
                if (typeof lucide !== 'undefined') {
                    lucide.createIcons();
                }
            }, 100);
        });
    </script>

    <!-- AOS Animation Library -->
    <link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet">
    <script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>
</x-app-layout>