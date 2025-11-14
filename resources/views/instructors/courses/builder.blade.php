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
                    Build your course content by adding modules and lessons. Structure your course to create an engaging learning experience.
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
            <span class="px-3 py-1 rounded-full text-xs font-semibold font-montserrat flex items-center bg-blue-100 text-blue-800">
                <i data-lucide="layers" class="w-3 h-3 mr-1"></i>
                {{ $course->modules->count() }} Module{{ $course->modules->count() !== 1 ? 's' : '' }}
            </span>
        </div>
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            <div class="text-center p-4 bg-gray-50 rounded-xl">
                <div class="w-12 h-12 rounded-xl bg-blue-100 flex items-center justify-center mx-auto mb-3">
                    <i data-lucide="play-circle" class="w-6 h-6 text-blue-600"></i>
                </div>
                <p class="text-2xl font-bold font-montserrat" style="color: #002147;">
                    {{ $course->modules->sum(function($module) { return $module->lessons->count(); }) }}
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
                <p class="text-2xl font-bold font-montserrat" style="color: #002147;">{{ $course->students->count() }}</p>
                <p class="text-sm text-gray-600 font-lato">Enrolled Students</p>
            </div>
        </div>
    </div>

    <!-- Course Content Builder -->
    <div class="bg-white rounded-2xl shadow-xl border border-gray-100 overflow-hidden" data-aos="fade-up" data-aos-delay="200">
        <div class="p-6 border-b border-gray-100 bg-gray-50">
            <h3 class="text-xl font-bold font-montserrat" style="color: #002147;">Course Content</h3>
            <p class="text-sm text-gray-600 font-lato mt-1">Organize your course into modules and lessons</p>
        </div>

        <div class="p-6" id="course-content">
            @forelse($course->modules as $module)
                <div class="module-container border border-gray-200 rounded-xl mb-6 last:mb-0" data-module-id="{{ $module->id }}">
                    <!-- Module Header -->
                    <div class="bg-gray-50 p-4 rounded-t-xl border-b border-gray-200">
                        <div class="flex items-center justify-between">
                            <div class="flex items-center flex-1">
                                <div class="w-8 h-8 rounded-lg bg-primary flex items-center justify-center mr-3">
                                    <i data-lucide="folder" class="w-4 h-4 text-white"></i>
                                </div>
                                <div class="flex-1">
                                    <h4 class="font-bold font-montserrat" style="color: #002147;">
                                        Module {{ $loop->iteration }}: {{ $module->title }}
                                    </h4>
                                    <p class="text-sm text-gray-600 font-lato">{{ $module->lessons->count() }} lesson{{ $module->lessons->count() !== 1 ? 's' : '' }}</p>
                                </div>
                            </div>
                            <div class="flex items-center gap-2">
                                <button type="button" 
                                    class="p-2 text-gray-400 hover:text-blue-600 transition-colors"
                                    onclick="editModule({{ $module->id }})">
                                    <i data-lucide="edit" class="w-4 h-4"></i>
                                </button>
                                <button type="button" 
                                    class="p-2 text-gray-400 hover:text-red-600 transition-colors"
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
                            <div class="flex items-center justify-between p-3 bg-white border border-gray-100 rounded-lg mb-3 last:mb-0 hover:border-gray-200 transition-colors">
                                <div class="flex items-center">
                                    <div class="w-6 h-6 rounded-lg bg-blue-100 flex items-center justify-center mr-3">
                                        <i data-lucide="play" class="w-3 h-3 text-blue-600"></i>
                                    </div>
                                    <div>
                                        <p class="font-semibold font-montserrat" style="color: #002147;">{{ $lesson->title }}</p>
                                        <p class="text-xs text-gray-500 font-lato">{{ $lesson->type ?? 'Video' }} • {{ $lesson->duration ?? '0' }} min</p>
                                    </div>
                                </div>
                                <div class="flex items-center gap-2">
                                    <button type="button" 
                                        class="p-1 text-gray-400 hover:text-blue-600 transition-colors"
                                        onclick="editLesson({{ $lesson->id }})">
                                        <i data-lucide="edit" class="w-3 h-3"></i>
                                    </button>
                                    <button type="button" 
                                        class="p-1 text-gray-400 hover:text-red-600 transition-colors"
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
                        Start building your course by creating your first module. Modules help organize your content into logical sections.
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

    <script>
        function addModule() {
            // Implementation for adding a new module
            alert('Add Module functionality will be implemented');
        }

        function editModule(moduleId) {
            // Implementation for editing a module
            alert('Edit Module functionality will be implemented for module: ' + moduleId);
        }

        function deleteModule(moduleId) {
            if (confirm('Are you sure you want to delete this module? All lessons in this module will also be deleted.')) {
                // Implementation for deleting a module
                alert('Delete Module functionality will be implemented for module: ' + moduleId);
            }
        }

        function addLesson(moduleId) {
            // Implementation for adding a lesson to a module
            alert('Add Lesson functionality will be implemented for module: ' + moduleId);
        }

        function editLesson(lessonId) {
            // Implementation for editing a lesson
            alert('Edit Lesson functionality will be implemented for lesson: ' + lessonId);
        }

        function deleteLesson(lessonId) {
            if (confirm('Are you sure you want to delete this lesson?')) {
                // Implementation for deleting a lesson
                alert('Delete Lesson functionality will be implemented for lesson: ' + lessonId);
            }
        }

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

        // Initialize AOS and Lucide
        document.addEventListener('DOMContentLoaded', function () {
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