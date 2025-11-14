<x-app-layout>
    <!-- Success/Error Messages -->
    @if (session('message'))
        <div
            class="mb-4 p-4 rounded-lg {{ session('type') === 'success' ? 'bg-green-50 text-green-800 border border-green-200' : 'bg-red-50 text-red-800 border border-red-200' }}">
            <div class="flex items-center">
                <i data-lucide="{{ session('type') === 'success' ? 'check-circle' : 'alert-circle' }}"
                    class="w-5 h-5 mr-2"></i>
                {{ session('message') }}
            </div>
        </div>
    @endif

    <div>
        <div class="lg:flex justify-between items-start mb-8">
            <div class="flex-1">
                <div class="flex items-center gap-4 mb-4">
                    <a href="{{ route('instructor.courses.index') }}"
                        class="inline-flex items-center text-gray-600 hover:text-primary transition-colors">
                        <i data-lucide="arrow-left" class="w-4 h-4 mr-1"></i>
                        Back to Courses
                    </a>
                    <span class="text-gray-300">•</span>
                    <span
                        class="px-3 py-1 text-xs font-medium rounded-full 
                        {{ $course->status->value === 'draft' ? 'bg-gray-100 text-gray-700' :
    ($course->status->value === 'under_review' ? 'bg-yellow-100 text-yellow-700' :
        ($course->status->value === 'approved' ? 'bg-green-100 text-green-700' : 'bg-red-100 text-red-700')) }}">
                        {{ ucfirst(str_replace('_', ' ', $course->status->value)) }}
                    </span>
                </div>

                <h1 class="text-3xl font-bold text-primary font-montserrat mb-2">
                    {{ $course->title }}
                </h1>

                @if($course->subtitle)
                    <p class="text-lg text-gray-600 mb-4">{{ $course->subtitle }}</p>
                @endif

                <div class="flex flex-wrap gap-4 text-sm text-gray-500">
                    <span class="flex items-center">
                        <i data-lucide="globe" class="w-4 h-4 mr-1"></i>
                        {{ $course->language }}
                    </span>
                    <span class="flex items-center">
                        <i data-lucide="signal" class="w-4 h-4 mr-1"></i>
                        {{ ucfirst($course->level->value) }}
                    </span>
                    <span class="flex items-center">
                        <i data-lucide="tag" class="w-4 h-4 mr-1"></i>
                        {{ $course->category->name }}
                    </span>
                    <span class="flex items-center">
                        <i data-lucide="dollar-sign" class="w-4 h-4 mr-1"></i>
                        {{ $course->is_free ? 'Free' : '$' . number_format($course->price, 2) }}
                    </span>
                </div>
            </div>

            <div class="flex gap-3 mt-4 lg:mt-0">
                <a href="{{ route('instructor.courses.edit', $course) }}"
                    class="inline-flex items-center px-4 py-2 bg-gray-100 hover:bg-gray-200 text-gray-700 rounded-lg transition-colors">
                    <i data-lucide="edit" class="w-4 h-4 mr-2"></i>
                    Edit Course
                </a>
                <a href="{{ route('instructor.courses.builder', $course) }}"
                    class="inline-flex items-center px-4 py-2 bg-primary hover:bg-secondary text-white rounded-lg transition-colors">
                    <i data-lucide="layout-grid" class="w-4 h-4 mr-2"></i>
                    Course Builder
                </a>
            </div>
        </div>

        <!-- Course Overview Grid -->
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            <!-- Main Content -->
            <div class="lg:col-span-2 space-y-8">
                <!-- Course Thumbnail -->
                @if($course->thumbnail_path)
                    <div>
                        <h3 class="text-lg font-semibold text-gray-900 mb-4">Course Thumbnail</h3>
                        <img src="{{ asset('storage/' . $course->thumbnail_path) }}" alt="{{ $course->title }}"
                            class="w-full h-64 object-cover rounded-xl shadow-sm border">
                    </div>
                @endif

                <!-- Course Description -->
                <div>
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">Course Description</h3>
                    <div class="prose max-w-none text-gray-700">
                        {!! nl2br(e($course->description)) !!}
                    </div>
                </div>

                <!-- Preview Video -->
                @if($course->preview_video_id)
                    <div>
                        <h3 class="text-lg font-semibold text-gray-900 mb-4">Preview Video</h3>
                        <video controls class="w-full rounded-xl shadow-sm">
                            <source src="{{ asset('storage/' . $course->preview_video_id) }}" type="video/mp4">
                            Your browser does not support the video tag.
                        </video>
                    </div>
                @endif

                <!-- Course Content Structure -->
                <div>
                    <div class="flex items-center justify-between mb-6">
                        <h3 class="text-lg font-semibold text-gray-900">Course Content</h3>
                        <a href="{{ route('instructor.courses.builder', $course) }}"
                            class="text-primary hover:text-secondary font-medium text-sm">
                            Manage Content →
                        </a>
                    </div>

                    @if($course->modules->count() > 0)
                        <div class="space-y-4">
                            @foreach($course->modules as $module)
                                <div class="border border-gray-200 rounded-lg p-4">
                                    <div class="flex items-center justify-between">
                                        <h4 class="font-medium text-gray-900">{{ $module->title }}</h4>
                                        <span class="text-sm text-gray-500">
                                            {{ $module->lessons->count() }} lesson(s)
                                        </span>
                                    </div>
                                    @if($module->description)
                                        <p class="text-sm text-gray-600 mt-2">{{ $module->description }}</p>
                                    @endif
                                </div>
                            @endforeach
                        </div>
                    @else
                        <div class="text-center py-12 bg-gray-50 rounded-xl">
                            <i data-lucide="book-open" class="w-12 h-12 text-gray-400 mx-auto mb-4"></i>
                            <h4 class="text-lg font-medium text-gray-900 mb-2">No content added yet</h4>
                            <p class="text-gray-500 mb-6">Start building your course by adding modules and lessons</p>
                            <a href="{{ route('instructor.courses.builder', $course) }}"
                                class="inline-flex items-center px-4 py-2 bg-primary text-white rounded-lg hover:bg-secondary transition-colors">
                                <i data-lucide="plus" class="w-4 h-4 mr-2"></i>
                                Add Content
                            </a>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Sidebar -->
            <div class="space-y-6">
                <!-- Course Stats -->
                <div class="bg-white rounded-xl border border-gray-200 p-6">
                    <h4 class="font-semibold text-gray-900 mb-4">Course Statistics</h4>
                    <div class="space-y-4">
                        <div class="flex justify-between">
                            <span class="text-gray-600">Students Enrolled</span>
                            <span class="font-medium">{{ $course->students->count() }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-gray-600">Total Modules</span>
                            <span class="font-medium">{{ $course->modules->count() }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-gray-600">Total Lessons</span>
                            <span
                                class="font-medium">{{ $course->modules->sum(fn($module) => $module->lessons->count()) }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-gray-600">Requirements</span>
                            <span class="font-medium">{{ $course->requirements->count() }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-gray-600">Outcomes</span>
                            <span class="font-medium">{{ $course->outcomes->count() }}</span>
                        </div>
                    </div>
                </div>

                <!-- Requirements -->
                @if($course->requirements->count() > 0)
                    <div class="bg-white rounded-xl border border-gray-200 p-6">
                        <h4 class="font-semibold text-gray-900 mb-4">Requirements</h4>
                        <ul class="space-y-2">
                            @foreach($course->requirements as $requirement)
                                <li class="flex items-start text-sm">
                                    <i data-lucide="check" class="w-4 h-4 text-green-500 mr-2 mt-0.5 flex-shrink-0"></i>
                                    {{ $requirement->requirement }}
                                </li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <!-- Learning Outcomes -->
                @if($course->outcomes->count() > 0)
                    <div class="bg-white rounded-xl border border-gray-200 p-6">
                        <h4 class="font-semibold text-gray-900 mb-4">What You'll Learn</h4>
                        <ul class="space-y-2">
                            @foreach($course->outcomes as $outcome)
                                <li class="flex items-start text-sm">
                                    <i data-lucide="target" class="w-4 h-4 text-primary mr-2 mt-0.5 flex-shrink-0"></i>
                                    {{ $outcome->outcome }}
                                </li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <!-- Actions -->
                <div class="bg-white rounded-xl border border-gray-200 p-6">
                    <h4 class="font-semibold text-gray-900 mb-4">Course Actions</h4>
                    <div class="space-y-3">
                        @if($course->status->value === 'draft')
                            <button
                                class="w-full text-left px-4 py-2 text-sm text-blue-600 hover:bg-blue-50 rounded-lg transition-colors">
                                Submit for Review
                            </button>
                        @endif
                        <button
                            class="w-full text-left px-4 py-2 text-sm text-gray-600 hover:bg-gray-50 rounded-lg transition-colors">
                            Download Reports
                        </button>
                        <button
                            class="w-full text-left px-4 py-2 text-sm text-red-600 hover:bg-red-50 rounded-lg transition-colors">
                            Delete Course
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>