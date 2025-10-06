<x-app-layout>
    <div class="px-4 md:px-6 py-6">
        <!-- Course Metadata Header -->
        <div class="mb-8 p-6 bg-white border border-gray-200 rounded-lg shadow-sm">
            <h1 class="text-2xl font-bold text-gray-800 mb-2">{{ $course->title }}</h1>
            <p class="text-gray-600 mb-4">{{ $course->description }}</p>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 text-sm text-gray-700">
                <div>
                    <span class="font-medium">Level:</span> {{ $course->level ?? 'Beginner' }}
                </div>
                <div>
                    <span class="font-medium">Category:</span> {{ $course->category->name ?? 'Uncategorized' }}
                </div>
                <div>
                    <span class="font-medium">Instructor:</span> {{ $course->instructor->name ?? 'N/A' }}
                </div>
                @if ($course->thumbnail_path)
                    <div>
                        <img src="{{ asset('storage/' . $course->thumbnail_path) }}" alt="Thumbnail"
                            class="h-24 w-24 object-cover rounded mt-2">
                    </div>
                @endif
            </div>
        </div>

        <!-- Back Button -->
        <div class="mb-6">
            <a href="{{ route('admin.courses.index') }}"
                class="inline-flex items-center px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-lg hover:bg-gray-100 focus:ring-4 focus:ring-gray-200">
                ← Back to Courses
            </a>
        </div>

        <!-- Requirements & Learning Objectives (Static UI Only) -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
            <!-- Requirements -->
            <div class="bg-white border border-gray-200 rounded-lg shadow-sm p-5 self-start">
                <div class="flex justify-between">
                    <h3 class="text-lg font-semibold text-gray-800 mb-3">Course Requirements</h3>
                </div>

                <ul class="space-y-2 mb-4">
                    @if ($course->requirements->count())
                        @foreach ($course->requirements as $requirement)
                            <li class="flex items-center justify-between p-2 bg-gray-50 rounded">
                                <span class="text-gray-700 text-sm">{{ $requirement->requirement }}</span>
                                <div class="flex items-center space-x-2">
                                    <form action="{{ route('admin.requirements.destroy', [$course, $requirement]) }}"
                                        method="post" x-data="{ loading: false }">
                                        @csrf
                                        @method('DELETE')
                                        <button x-bind:disabled="loading"
                                            class="text-gray-400 text-sm cursor-pointer flex items-center space-x-2">
                                            <i data-lucide="x" class="w-4 h-4 mr-1"></i>
                                        </button>
                                    </form>
                                </div>
                            </li>
                        @endforeach
                    @endif
                </ul>
                <form action="{{ route('admin.requirements.store', $course) }}" method="post" id="requirements-form"
                    x-data="{ loading: false }" x-on:submit="loading = true" class="flex space-x-2">
                    @csrf
                    <input type="text" name="requirement" placeholder="Add new requirement"
                        class="flex-1 rounded-lg border focus:ring-2 focus:ring-orange-600 border-orange-600 p-2 text-sm text-gray-900">
                    <button type="submit" x-bind:disabled="loading"
                        class="px-4 py-2 text-sm font-medium disabled:bg-orange-600/50 text-white bg-orange-600 hover:bg-orange-700 rounded-lg">
                        <span x-show="!loading">+ Add</span>
                        <span x-show="loading">Please wait…</span>
                    </button>
                </form>
                <div>
                    @error('requirement')
                        <span class="text-red-600 text-xs">{{ $message }}</span>
                    @enderror
                </div>
            </div>

            <!-- What You'll Learn -->
            <div class="bg-white border border-gray-200 rounded-lg shadow-sm p-5 self-start">
                <h3 class="text-lg font-semibold text-gray-800 mb-3">Learning Outcomes</h3>
                <ul class="space-y-2 mb-4">
                    @if ($course->outcomes->count())
                        @foreach ($course->outcomes as $outcome)
                            <li class="flex items-center justify-between p-2 bg-gray-50 rounded">
                                <span class="text-gray-700 text-sm">{{ $outcome->outcome }}</span>
                                <div class="flex items-center space-x-2">
                                    <form action="{{ route('admin.outcomes.destroy', [$course, $outcome]) }}"
                                        method="post" x-data="{ loading: false }">
                                        @csrf
                                        @method('DELETE')
                                        <button x-bind:disabled="loading"
                                            class="text-gray-400 text-sm cursor-pointer flex items-center space-x-2">
                                            <i data-lucide="x" class="w-4 h-4 mr-1"></i>
                                        </button>
                                    </form>
                                </div>
                            </li>
                        @endforeach
                    @endif
                </ul>
                <form action="{{ route('admin.outcomes.store', $course) }}" method="post" id="outcomes-form"
                    x-data="{ loading: false }" x-on:submit="loading = true" class="flex space-x-2">
                    @csrf
                    <input type="text" name="outcome" placeholder="Add new outcome"
                        class="flex-1 rounded-lg border focus:ring-2 focus:ring-orange-600 border-orange-600 p-2 text-sm text-gray-900">
                    <button type="submit" x-bind:disabled="loading"
                        class="px-4 py-2 text-sm font-medium disabled:bg-orange-600/50 text-white bg-orange-600 hover:bg-orange-700 rounded-lg">
                        <span x-show="!loading">+ Add</span>
                        <span x-show="loading">Please wait…</span>
                    </button>
                </form>
                <div>
                    @error('outcome')
                        <span class="text-red-600 text-xs">{{ $message }}</span>
                    @enderror
                </div>
            </div>
        </div>

        <!-- Add Module (Static Form - No Action) -->
        <div class="mb-6">
            <form action="{{ route('admin.modules.store', $course) }}" method="POST" x-data="{ loading: false }"
                x-on:submit="loading = true"
                class="flex flex-col sm:flex-row sm:items-center sm:space-x-3 space-y-3 sm:space-y-0">
                @csrf
                <input type="text" name="title" placeholder="New Module Title"
                    class="block w-full sm:w-auto flex-1 rounded-lg border border-gray-300 bg-gray-50 p-2.5 text-sm text-gray-900 focus:border-orange-600 focus:ring-orange-600">
                <button type="submit" x-bind:disabled="loading"
                    class="inline-flex items-center justify-center px-4 py-2 text-sm font-medium text-white bg-orange-600 rounded-lg cursor-pointer disabled:cursor-not-allowed">
                    <span x-show="!loading">+ Add Module</span>
                    <span x-show="loading">Please wait…</span>
                </button>
            </form>
            @error('title')
                <small class="mt-2 text-xs text-red-500">{{ $message }}</small>
            @enderror
        </div>

        <!-- Static Modules (Placeholder Content) -->
        <div class="space-y-6">
            @if ($course->modules->count())
                @foreach ($course->modules as $module)
                    {{-- section --}}
                    <div x-data="{ open: false }" class="bg-white border border-gray-200 rounded-lg shadow-sm">
                        <!-- Accordion Header -->
                        <div class="flex items-center justify-between p-4 cursor-pointer" @click="open = !open">
                            <h2 class="text-lg font-semibold text-gray-800">{{ $module->title }}</h2>
                            <div class="flex items-center space-x-2">
                                <!-- Toggle Icon -->
                                <svg x-show="!open" xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-gray-500"
                                    fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                                </svg>
                                <svg x-show="open" xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-gray-500"
                                    fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M18 12H6" />
                                </svg>

                                <!-- Action Buttons (always visible) -->
                                <div class="flex space-x-2">
                                    <a href="{{ route('admin.lessons.create', $module) }}"
                                        class="inline-flex cursor-pointer items-center px-3 py-1.5 text-sm font-medium text-gray-500 hover:text-orange-600 rounded-lg">
                                        + Add Lesson
                                    </a>
                                    <button
                                        class="px-3 py-1.5 text-sm text-white bg-green-700 rounded-lg">Edit</button>
                                    <button
                                        class="px-3 py-1.5 text-sm text-white bg-red-700 rounded-lg cursor-not-allowed"
                                        disabled>
                                        Delete
                                    </button>
                                </div>
                            </div>
                        </div>

                        <!-- Accordion Content -->
                        <div x-show="open" x-collapse class="px-4 pb-4">
                            <ul class="space-y-2">
                                @if ($module->lessons->count())
                                    @foreach ($module->lessons as $lesson)
                                        @if ($lesson->type == 'video')
                                            <li class="flex items-center justify-between p-3 bg-gray-50 rounded-lg">
                                                <span class="text-sm text-gray-700 font-bold">🎥 Video:
                                                    {{ $lesson->title }}</span>
                                                <span class="text-gray-400 text-sm">View (disabled)</span>
                                            </li>
                                        @elseif ($lesson->type == 'pdf')
                                            <li class="flex items-center justify-between p-3 bg-gray-50 rounded-lg">
                                                <span class="text-sm text-gray-700 font-bold">📄 PDF:
                                                    {{ $lesson->title }}</span>
                                                <div class="flex items-center gap-1">
                                                    <a href="{{ asset('storage/' . $lesson->content_path) }}"
                                                        target="_blank" rel="noopener noreferrer"
                                                        class="inline-flex items-center text-orange-600 hover:underline text-sm transition-colors duration-150"
                                                        title="View PDF">
                                                        <i data-lucide="file-text" class="w-4 h-4 mr-1"></i>
                                                    </a>
                                                    <form
                                                        action="{{ route('admin.lessons.destroy', [$module, $lesson]) }}"
                                                        method="POST"
                                                        onsubmit="return confirm('Are you sure you want to delete this lesson?');"
                                                        class="inline">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" class="inline-flex items-center"
                                                            title="Delete Lesson">
                                                            <i data-lucide="trash"
                                                                class="w-4 h-4 mr-1 text-red-600"></i>
                                                        </button>
                                                    </form>
                                                </div>
                                            </li>
                                        @elseif ($lesson->type == 'link')
                                            <li class="flex items-center justify-between p-3 bg-gray-50 rounded-lg">
                                                <span class="text-sm text-gray-700 font-bold">🔗 Link:
                                                    {{ $lesson->title }}</span>
                                                <div class="flex items-center gap-1">
                                                    <a href="{{ $lesson->content_path }}" target="_blank"
                                                        rel="noopener noreferrer"
                                                        class="inline-flex items-center text-orange-600 hover:underline text-sm transition-colors duration-150">
                                                        <i data-lucide="external-link" class="w-4 h-4 mr-1"></i>
                                                    </a>
                                                    <form
                                                        action="{{ route('admin.lessons.destroy', [$module, $lesson]) }}"
                                                        method="POST"
                                                        onsubmit="return confirm('Are you sure you want to delete this lesson?');"
                                                        class="inline">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" class="inline-flex items-center"
                                                            title="Delete Lesson">
                                                            <i data-lucide="trash"
                                                                class="w-4 h-4 mr-1 text-red-600"></i>
                                                        </button>
                                                    </form>
                                                </div>
                                            </li>
                                        @endif
                                    @endforeach
                                @else
                                    <li class="text-sm text-gray-500 italic">No lessons yet.</li>
                                @endif
                            </ul>
                        </div>
                    </div>
                @endforeach
            @endif
        </div>
    </div>
</x-app-layout>
