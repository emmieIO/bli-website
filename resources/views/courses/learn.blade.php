<x-app-layout>
    <!-- Course Learning Interface -->
    <div class="min-h-screen bg-gray-100">
        <!-- Course Header -->
        <div class="bg-white shadow-sm border-b">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="flex items-center justify-between h-16">
                    <!-- Course Title & Progress -->
                    <div class="flex items-center space-x-4">
                        <a href="{{ route('courses.show', $course) }}" class="text-gray-600 hover:text-primary transition-colors">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
                            </svg>
                        </a>
                        <div>
                            <h1 class="text-lg font-semibold text-gray-900 truncate max-w-md">{{ $course->title }}</h1>
                            <div class="flex items-center mt-1">
                                <div class="w-32 bg-gray-200 rounded-full h-2 mr-3">
                                    <div class="bg-primary h-2 rounded-full" style="width: {{ $progress ?? 0 }}%"></div>
                                </div>
                                <span class="text-sm text-gray-600">{{ $progress ?? 0 }}% complete</span>
                            </div>
                        </div>
                    </div>

                    <!-- Action Buttons -->
                    <div class="flex items-center space-x-4">
                        <button id="toggleNotes" class="p-2 text-gray-600 hover:text-primary transition-colors">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                            </svg>
                        </button>
                        <button id="toggleSidebar" class="p-2 text-gray-600 hover:text-primary transition-colors lg:hidden">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/>
                            </svg>
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <div class="flex h-[calc(100vh-4rem)]">
            <!-- Main Content Area -->
            <div class="flex-1 flex flex-col">
                <!-- Video Player -->
                            <!-- Video Player Area -->
            <div class="bg-gray-900 rounded-lg overflow-hidden">
                @if($currentLesson && $currentLesson->video_url)
                    <div class="aspect-video relative" id="video-container">
                        <video 
                            id="lesson-video"
                            class="w-full h-full"
                            controls
                            preload="metadata"
                            data-lesson-id="{{ $currentLesson->id }}"
                            poster="/images/video-placeholder.jpg">
                            <source src="{{ $currentLesson->video_url }}" type="video/mp4">
                            Your browser does not support the video tag.
                        </video>
                        
                        <!-- Custom Video Controls Overlay -->
                        <div id="video-controls-overlay" class="absolute inset-0 bg-black bg-opacity-50 flex items-center justify-center opacity-0 hover:opacity-100 transition-opacity pointer-events-none">
                            <div class="flex items-center space-x-4 pointer-events-auto">
                                <!-- Playback Speed -->
                                <div class="relative group">
                                    <button id="speed-button" class="text-white bg-black bg-opacity-50 px-3 py-2 rounded-lg text-sm">
                                        1x
                                    </button>
                                    <div id="speed-menu" class="absolute bottom-full left-0 mb-2 bg-black rounded-lg overflow-hidden opacity-0 group-hover:opacity-100 transition-opacity">
                                        <button class="speed-option block w-full text-left px-4 py-2 text-white hover:bg-gray-700 text-sm" data-speed="0.5">0.5x</button>
                                        <button class="speed-option block w-full text-left px-4 py-2 text-white hover:bg-gray-700 text-sm" data-speed="0.75">0.75x</button>
                                        <button class="speed-option block w-full text-left px-4 py-2 text-white hover:bg-gray-700 text-sm" data-speed="1" selected>1x</button>
                                        <button class="speed-option block w-full text-left px-4 py-2 text-white hover:bg-gray-700 text-sm" data-speed="1.25">1.25x</button>
                                        <button class="speed-option block w-full text-left px-4 py-2 text-white hover:bg-gray-700 text-sm" data-speed="1.5">1.5x</button>
                                        <button class="speed-option block w-full text-left px-4 py-2 text-white hover:bg-gray-700 text-sm" data-speed="2">2x</button>
                                    </div>
                                </div>
                                
                                <!-- Quality Selector -->
                                <div class="relative group">
                                    <button id="quality-button" class="text-white bg-black bg-opacity-50 px-3 py-2 rounded-lg text-sm">
                                        Auto
                                    </button>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Progress Bar -->
                        <div class="absolute bottom-0 left-0 right-0 h-1 bg-gray-600">
                            <div id="watch-progress" class="h-full bg-blue-500 transition-all duration-300" style="width: 0%"></div>
                        </div>
                    </div>
                @else
                    <div class="aspect-video flex items-center justify-center text-gray-400">
                        <div class="text-center">
                            <svg class="w-16 h-16 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14.828 14.828a4 4 0 01-5.656 0M9 10h1m4 0h1m-6 4h8m2 4H7a2 2 0 01-2-2V8a2 2 0 012 2v8a2 2 0 01-2 2z"></path>
                            </svg>
                            <p>No video available for this lesson</p>
                        </div>
                    </div>
                @endif
            </div>

            <!-- Lesson Actions -->
            @if($currentLesson)
            <div class="flex items-center justify-between mt-4 px-6 py-4 bg-gray-50 rounded-lg">
                <div class="flex items-center space-x-4">
                    <!-- Mark Complete Button -->
                    <button id="mark-complete-btn" 
                            class="flex items-center px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 transition-colors disabled:opacity-50 disabled:cursor-not-allowed"
                            data-lesson-id="{{ $currentLesson->id }}">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                        </svg>
                        <span id="complete-btn-text">Mark as Complete</span>
                    </button>
                    
                    <!-- Bookmark Button -->
                    <button id="bookmark-btn" class="flex items-center px-4 py-2 bg-yellow-600 text-white rounded-lg hover:bg-yellow-700 transition-colors">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 5a2 2 0 012-2h10a2 2 0 012 2v16l-7-3.5L5 21V5z"></path>
                        </svg>
                        Bookmark
                    </button>
                </div>
                
                <!-- Lesson Duration -->
                @if($currentLesson->duration)
                    <div class="text-sm text-gray-500">
                        Duration: {{ gmdate('H:i:s', $currentLesson->duration) }}
                    </div>
                @endif
            </div>
            @endif

                <!-- Lesson Content -->
                <div class="flex-1 bg-white overflow-hidden">
                    <!-- Lesson Navigation -->
                    <div class="border-b border-gray-200 px-6 py-4">
                        <div class="flex items-center justify-between">
                            <div>
                                @if($previousLesson)
                                <a href="{{ route('courses.learn', ['course' => $course, 'lesson' => $previousLesson]) }}" 
                                   class="inline-flex items-center text-sm text-gray-600 hover:text-primary transition-colors">
                                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
                                    </svg>
                                    Previous
                                </a>
                                @endif
                            </div>
                            <div class="text-center">
                                <h2 class="text-xl font-semibold text-gray-900">{{ $currentLesson ? $currentLesson->title : 'Course Overview' }}</h2>
                                @if($currentLesson && $currentLesson->subtitle)
                                <p class="text-sm text-gray-600 mt-1">{{ $currentLesson->subtitle }}</p>
                                @endif
                            </div>
                            <div>
                                @if($nextLesson)
                                <a href="{{ route('courses.learn', ['course' => $course, 'lesson' => $nextLesson]) }}" 
                                   class="inline-flex items-center text-sm text-gray-600 hover:text-primary transition-colors">
                                    Next
                                    <svg class="w-4 h-4 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                                    </svg>
                                </a>
                                @endif
                            </div>
                        </div>
                    </div>

                    <!-- Tabs -->
                    <div class="border-b border-gray-200">
                        <nav class="-mb-px flex px-6">
                            <button class="tab-button active py-4 px-1 border-b-2 font-medium text-sm mr-8" data-tab="overview">
                                Overview
                            </button>
                            <button class="tab-button py-4 px-1 border-b-2 font-medium text-sm mr-8" data-tab="notes">
                                Notes
                            </button>
                            <button class="tab-button py-4 px-1 border-b-2 font-medium text-sm mr-8" data-tab="resources">
                                Resources
                            </button>
                            <button class="tab-button py-4 px-1 border-b-2 font-medium text-sm" data-tab="discussion">
                                Discussion
                            </button>
                        </nav>
                    </div>

                    <!-- Tab Content -->
                    <div class="flex-1 overflow-y-auto p-6">
                        <!-- Overview Tab -->
                        <div id="overview" class="tab-content">
                            @if($currentLesson)
                                <div class="prose max-w-none">
                                    <h3>Lesson Description</h3>
                                    <p>{{ $currentLesson->description ?? 'No description available for this lesson.' }}</p>
                                    
                                    @if($currentLesson->duration)
                                    <div class="bg-blue-50 p-4 rounded-lg mt-4">
                                        <div class="flex items-center">
                                            <svg class="w-5 h-5 text-blue-600 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-12a1 1 0 10-2 0v4a1 1 0 00.293.707l2.828 2.829a1 1 0 101.415-1.415L11 9.586V6z" clip-rule="evenodd"/>
                                            </svg>
                                            <span class="text-blue-800 font-medium">Duration: {{ $currentLesson->duration }}</span>
                                        </div>
                                    </div>
                                    @endif
                                </div>
                            @else
                                <div class="text-center py-12">
                                    <h3 class="text-lg font-semibold text-gray-900 mb-4">Welcome to {{ $course->title }}</h3>
                                    <p class="text-gray-600 mb-6">{{ $course->description }}</p>
                                    <div class="bg-green-50 p-6 rounded-lg">
                                        <h4 class="font-semibold text-green-800 mb-2">What you'll learn:</h4>
                                        @if($course->outcomes && $course->outcomes->count())
                                            <ul class="text-left text-green-700 space-y-1">
                                                @foreach($course->outcomes->take(5) as $outcome)
                                                <li class="flex items-start">
                                                    <svg class="w-4 h-4 text-green-600 mt-0.5 mr-2 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                                                        <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
                                                    </svg>
                                                    {{ $outcome->outcome }}
                                                </li>
                                                @endforeach
                                            </ul>
                                        @endif
                                    </div>
                                </div>
                            @endif
                        </div>

                        <!-- Notes Tab -->
                        <div id="notes" class="tab-content hidden">
                            <div class="max-w-4xl">
                                <div class="flex items-center justify-between mb-6">
                                    <h3 class="text-lg font-semibold text-gray-900">My Notes</h3>
                                    <button class="bg-primary text-white px-4 py-2 rounded-lg font-medium hover:bg-primary/90 transition-colors">
                                        Add Note
                                    </button>
                                </div>
                                
                                <!-- Notes List -->
                                <div class="space-y-4">
                                    <div class="bg-yellow-50 border-l-4 border-yellow-400 p-4 rounded-r-lg">
                                        <div class="flex items-start justify-between">
                                            <div>
                                                <p class="text-gray-800">This is an example note about the current lesson content.</p>
                                                <div class="flex items-center mt-2 text-sm text-gray-500">
                                                    <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-12a1 1 0 10-2 0v4a1 1 0 00.293.707l2.828 2.829a1 1 0 101.415-1.415L11 9.586V6z" clip-rule="evenodd"/>
                                                    </svg>
                                                    <span>5:30 • 2 hours ago</span>
                                                </div>
                                            </div>
                                            <button class="text-gray-400 hover:text-gray-600">
                                                <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                                    <path d="M6 10a2 2 0 11-4 0 2 2 0 014 0zM12 10a2 2 0 11-4 0 2 2 0 014 0zM16 12a2 2 0 100-4 2 2 0 000 4z"/>
                                                </svg>
                                            </button>
                                        </div>
                                    </div>
                                </div>

                                <!-- Empty State -->
                                <div class="text-center py-12 text-gray-500">
                                    <svg class="w-12 h-12 mx-auto mb-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                    </svg>
                                    <p>No notes yet. Start taking notes as you learn!</p>
                                </div>
                            </div>
                        </div>

                        <!-- Resources Tab -->
                        <div id="resources" class="tab-content hidden">
                            <div class="max-w-4xl">
                                <h3 class="text-lg font-semibold text-gray-900 mb-6">Course Resources</h3>
                                
                                @if($currentLesson && $currentLesson->resources)
                                <div class="space-y-4">
                                    <!-- Example resources -->
                                    <div class="border border-gray-200 rounded-lg p-4">
                                        <div class="flex items-center justify-between">
                                            <div class="flex items-center">
                                                <svg class="w-8 h-8 text-red-500 mr-3" fill="currentColor" viewBox="0 0 20 20">
                                                    <path fill-rule="evenodd" d="M4 4a2 2 0 012-2h4.586A2 2 0 0112 2.586L15.414 6A2 2 0 0116 7.414V16a2 2 0 01-2 2H6a2 2 0 01-2-2V4z" clip-rule="evenodd"/>
                                                </svg>
                                                <div>
                                                    <h4 class="font-medium text-gray-900">Lesson Slides</h4>
                                                    <p class="text-sm text-gray-600">PDF • 2.5 MB</p>
                                                </div>
                                            </div>
                                            <button class="bg-primary text-white px-4 py-2 rounded-lg font-medium hover:bg-primary/90 transition-colors">
                                                Download
                                            </button>
                                        </div>
                                    </div>
                                </div>
                                @else
                                <div class="text-center py-12 text-gray-500">
                                    <svg class="w-12 h-12 mx-auto mb-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M9 19l3 3m0 0l3-3m-3 3V10"/>
                                    </svg>
                                    <p>No resources available for this lesson.</p>
                                </div>
                                @endif
                            </div>
                        </div>

                        <!-- Discussion Tab -->
                        <div id="discussion" class="tab-content hidden">
                            <div class="max-w-4xl">
                                <h3 class="text-lg font-semibold text-gray-900 mb-6">Discussion & Q&A</h3>
                                <div class="text-center py-12 text-gray-500">
                                    <svg class="w-12 h-12 mx-auto mb-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="17 8h2a2 2 0 012 2v6a2 2 0 01-2 2h-2v4l-4-4H9a1.994 1.994 0 01-1.414-.586m0 0L11 14h4a2 2 0 002-2V6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2v4l.586-.586z"/>
                                    </svg>
                                    <p>Discussion feature coming soon!</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Course Navigation Sidebar -->
            <div class="w-80 bg-white border-l border-gray-200 flex flex-col" id="courseSidebar">
                <!-- Course Progress -->
                <div class="p-6 border-b border-gray-200">
                    <h3 class="font-semibold text-gray-900 mb-4">Course Progress</h3>
                    <div class="space-y-2">
                        <div class="flex justify-between text-sm">
                            <span>{{ $completedLessons ?? 0 }}/{{ $totalLessons ?? 0 }} lessons</span>
                            <span>{{ $progress ?? 0 }}%</span>
                        </div>
                        <div class="w-full bg-gray-200 rounded-full h-2">
                            <div class="bg-primary h-2 rounded-full transition-all" style="width: {{ $progress ?? 0 }}%"></div>
                        </div>
                    </div>
                </div>

                <!-- Course Curriculum -->
                <div class="flex-1 overflow-y-auto">
                    @if($course->modules && $course->modules->count())
                        @foreach($course->modules as $moduleIndex => $module)
                        <div class="border-b border-gray-200">
                            <button class="w-full text-left p-4 hover:bg-gray-50 transition-colors module-toggle" 
                                    data-module="{{ $module->id }}">
                                <div class="flex items-center justify-between">
                                    <div>
                                        <h4 class="font-medium text-gray-900">{{ $module->title }}</h4>
                                        <p class="text-sm text-gray-600 mt-1">
                                            {{ $module->lessons ? $module->lessons->count() : 0 }} lessons
                                        </p>
                                    </div>
                                    <svg class="w-4 h-4 text-gray-400 transform transition-transform module-chevron" data-module="{{ $module->id }}">
                                        <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                                    </svg>
                                </div>
                            </button>
                            
                            <div class="module-content hidden" id="module-{{ $module->id }}">
                                @if($module->lessons && $module->lessons->count())
                                    @foreach($module->lessons as $lessonIndex => $lesson)
                                    <a href="{{ route('courses.learn', ['course' => $course, 'lesson' => $lesson]) }}"
                                       class="block px-6 py-3 border-b border-gray-100 hover:bg-gray-50 transition-colors {{ $currentLesson && $currentLesson->id === $lesson->id ? 'bg-blue-50 border-l-4 border-l-primary' : '' }}">
                                        <div class="flex items-center justify-between">
                                            <div class="flex items-center">
                                                <div class="w-6 h-6 rounded-full border-2 {{ $lesson->completed ?? false ? 'bg-green-500 border-green-500' : 'border-gray-300' }} flex items-center justify-center mr-3">
                                                    @if($lesson->completed ?? false)
                                                        <svg class="w-3 h-3 text-white" fill="currentColor" viewBox="0 0 20 20">
                                                            <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
                                                        </svg>
                                                    @else
                                                        <span class="text-xs text-gray-600">{{ $lessonIndex + 1 }}</span>
                                                    @endif
                                                </div>
                                                <div>
                                                    <h5 class="text-sm font-medium text-gray-900">{{ $lesson->title }}</h5>
                                                    @if($lesson->duration)
                                                    <p class="text-xs text-gray-500">{{ $lesson->duration }}</p>
                                                    @endif
                                                </div>
                                            </div>
                                            
                                            <div class="flex items-center space-x-2">
                                                @switch($lesson->type)
                                                    @case('video')
                                                        <svg class="w-4 h-4 text-gray-400" fill="currentColor" viewBox="0 0 20 20">
                                                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM9.555 7.168A1 1 0 008 8v4a1 1 0 001.555.832l3-2a1 1 0 000-1.664l-3-2z" clip-rule="evenodd"/>
                                                        </svg>
                                                    @break
                                                    @case('pdf')
                                                        <svg class="w-4 h-4 text-gray-400" fill="currentColor" viewBox="0 0 20 20">
                                                            <path fill-rule="evenodd" d="M4 4a2 2 0 012-2h4.586A2 2 0 0112 2.586L15.414 6A2 2 0 0116 7.414V16a2 2 0 01-2 2H6a2 2 0 01-2-2V4z" clip-rule="evenodd"/>
                                                        </svg>
                                                    @break
                                                    @default
                                                        <svg class="w-4 h-4 text-gray-400" fill="currentColor" viewBox="0 0 20 20">
                                                            <path fill-rule="evenodd" d="M3 4a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zm0 4a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zm0 4a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1z" clip-rule="evenodd"/>
                                                        </svg>
                                                @endswitch
                                            </div>
                                        </div>
                                    </a>
                                    @endforeach
                                @else
                                    <div class="px-6 py-3 text-sm text-gray-500">No lessons available</div>
                                @endif
                            </div>
                        </div>
                        @endforeach
                    @else
                        <div class="p-6 text-center text-gray-500">
                            <p>No course content available</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- JavaScript for interactivity -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Tab switching
            const tabButtons = document.querySelectorAll('.tab-button');
            const tabContents = document.querySelectorAll('.tab-content');

            tabButtons.forEach(button => {
                button.addEventListener('click', () => {
                    const tabName = button.dataset.tab;
                    
                    // Update button states
                    tabButtons.forEach(btn => {
                        btn.classList.remove('active', 'border-primary', 'text-primary');
                        btn.classList.add('border-transparent', 'text-gray-500');
                    });
                    button.classList.add('active', 'border-primary', 'text-primary');
                    button.classList.remove('border-transparent', 'text-gray-500');
                    
                    // Update content visibility
                    tabContents.forEach(content => {
                        content.classList.add('hidden');
                    });
                    document.getElementById(tabName).classList.remove('hidden');
                });
            });

            // Video player functionality
            const video = document.getElementById('lesson-video');
            const markCompleteBtn = document.getElementById('mark-complete-btn');
            const bookmarkBtn = document.getElementById('bookmark-btn');
            const watchProgress = document.getElementById('watch-progress');
            const speedButton = document.getElementById('speed-button');
            const speedOptions = document.querySelectorAll('.speed-option');

            if (video) {
                let progressUpdateInterval;
                const lessonId = video.dataset.lessonId;

                // Load saved progress
                loadLessonProgress(lessonId);

                // Video event listeners
                video.addEventListener('timeupdate', function() {
                    updateWatchProgress();
                    
                    // Auto-save progress every 10 seconds
                    clearTimeout(progressUpdateInterval);
                    progressUpdateInterval = setTimeout(() => {
                        saveProgress(false);
                    }, 10000);
                });

                video.addEventListener('ended', function() {
                    saveProgress(true);
                    if (markCompleteBtn && !markCompleteBtn.disabled) {
                        markAsComplete();
                    }
                });

                // Playback speed controls
                speedOptions.forEach(option => {
                    option.addEventListener('click', function() {
                        const speed = parseFloat(this.dataset.speed);
                        video.playbackRate = speed;
                        speedButton.textContent = speed + 'x';
                        
                        // Update selected state
                        speedOptions.forEach(opt => opt.removeAttribute('selected'));
                        this.setAttribute('selected', 'true');
                    });
                });

                // Keyboard shortcuts
                document.addEventListener('keydown', function(e) {
                    if (e.target.tagName.toLowerCase() !== 'input' && e.target.tagName.toLowerCase() !== 'textarea') {
                        switch(e.code) {
                            case 'Space':
                                e.preventDefault();
                                video.paused ? video.play() : video.pause();
                                break;
                            case 'ArrowLeft':
                                e.preventDefault();
                                video.currentTime = Math.max(0, video.currentTime - 10);
                                break;
                            case 'ArrowRight':
                                e.preventDefault();
                                video.currentTime = Math.min(video.duration, video.currentTime + 10);
                                break;
                            case 'KeyM':
                                e.preventDefault();
                                video.muted = !video.muted;
                                break;
                        }
                    }
                });

                function updateWatchProgress() {
                    if (video.duration) {
                        const progress = (video.currentTime / video.duration) * 100;
                        if (watchProgress) {
                            watchProgress.style.width = progress + '%';
                        }
                    }
                }

                function saveProgress(isCompleted = false) {
                    if (!lessonId) return;

                    fetch(`/api/lessons/${lessonId}/progress`, {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                        },
                        body: JSON.stringify({
                            current_time: video.currentTime,
                            duration: video.duration,
                            is_completed: isCompleted
                        })
                    }).catch(console.error);
                }

                function loadLessonProgress(lessonId) {
                    if (!lessonId) return;

                    fetch(`/api/lessons/${lessonId}/progress`)
                    .then(response => response.json())
                    .then(data => {
                        if (data.progress) {
                            if (data.progress.is_completed && markCompleteBtn) {
                                updateCompleteButton();
                            }
                            
                            if (data.progress.last_position && video.duration) {
                                video.currentTime = data.progress.last_position;
                            }
                        }
                    })
                    .catch(console.error);
                }

                function markAsComplete() {
                    if (!lessonId || !markCompleteBtn || markCompleteBtn.disabled) return;

                    fetch(`/api/lessons/${lessonId}/complete`, {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                        }
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            updateCompleteButton();
                            
                            // Auto-advance to next lesson
                            const nextBtn = document.getElementById('next-lesson-btn');
                            if (nextBtn) {
                                setTimeout(() => {
                                    if (confirm('Lesson completed! Would you like to proceed to the next lesson?')) {
                                        nextBtn.click();
                                    }
                                }, 2000);
                            } else {
                                // Last lesson - check for course completion
                                setTimeout(() => {
                                    checkCourseCompletion();
                                }, 1000);
                            }
                        }
                    })
                    .catch(console.error);
                }

                function updateCompleteButton() {
                    if (!markCompleteBtn) return;
                    
                    markCompleteBtn.innerHTML = `
                        <svg class="w-4 h-4 mr-2" fill="currentColor" viewBox="0 0 24 24">
                            <path d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                        Completed
                    `;
                    markCompleteBtn.classList.add('bg-gray-500');
                    markCompleteBtn.classList.remove('bg-green-600', 'hover:bg-green-700');
                    markCompleteBtn.disabled = true;
                }
            }

            // Mark complete button
            if (markCompleteBtn) {
                markCompleteBtn.addEventListener('click', function() {
                    const lessonId = this.dataset.lessonId;
                    if (lessonId && !this.disabled) {
                        if (video) {
                            saveProgress(true);
                        }
                        markAsComplete();
                    }
                });
            }

            // Bookmark functionality
            if (bookmarkBtn) {
                bookmarkBtn.addEventListener('click', function() {
                    const isBookmarked = this.classList.contains('bookmarked');
                    
                    if (isBookmarked) {
                        this.classList.remove('bookmarked', 'bg-yellow-700');
                        this.classList.add('bg-yellow-600');
                        this.innerHTML = `
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 5a2 2 0 012-2h10a2 2 0 012 2v16l-7-3.5L5 21V5z"></path>
                            </svg>
                            Bookmark
                        `;
                    } else {
                        this.classList.add('bookmarked', 'bg-yellow-700');
                        this.classList.remove('bg-yellow-600');
                        this.innerHTML = `
                            <svg class="w-4 h-4 mr-2" fill="currentColor" viewBox="0 0 24 24">
                                <path d="M5 5a2 2 0 012-2h10a2 2 0 012 2v16l-7-3.5L5 21V5z"/>
                            </svg>
                            Bookmarked
                        `;
                    }
                });
            }

            // Module toggle
            document.querySelectorAll('.module-toggle').forEach(toggle => {
                toggle.addEventListener('click', () => {
                    const moduleId = toggle.dataset.module;
                    const content = document.getElementById(`module-${moduleId}`);
                    const chevron = document.querySelector(`.module-chevron[data-module="${moduleId}"]`);
                    
                    if (content.classList.contains('hidden')) {
                        content.classList.remove('hidden');
                        chevron.style.transform = 'rotate(90deg)';
                    } else {
                        content.classList.add('hidden');
                        chevron.style.transform = 'rotate(0deg)';
                    }
                });
            });

            // Initialize - open first module
            const firstModule = document.querySelector('.module-toggle');
            if (firstModule) {
                firstModule.click();
            }

            // Sidebar toggle for mobile
            document.getElementById('toggleSidebar')?.addEventListener('click', () => {
                document.getElementById('courseSidebar').classList.toggle('hidden');
            });

            // Initialize active tab styles
            const activeTab = document.querySelector('.tab-button.active');
            if (activeTab) {
                activeTab.classList.add('border-primary', 'text-primary');
                activeTab.classList.remove('border-transparent', 'text-gray-500');
            }

            // Course completion functionality
            function checkCourseCompletion() {
                const courseId = {{ $course->id }};
                
                fetch(`/api/courses/${courseId}/completion-status`)
                .then(response => response.json())
                .then(data => {
                    if (data.isCompleted) {
                        showCourseCompletionModal();
                    } else {
                        alert('Congratulations! You have completed all available lessons!');
                    }
                })
                .catch(() => {
                    alert('Congratulations! You have completed this course!');
                });
            }

            function showCourseCompletionModal() {
                const modal = document.createElement('div');
                modal.className = 'fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50';
                modal.innerHTML = `
                    <div class="bg-white rounded-lg p-8 max-w-md mx-4 text-center">
                        <div class="w-16 h-16 bg-green-100 rounded-full flex items-center justify-center mx-auto mb-4">
                            <svg class="w-8 h-8 text-green-600" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                            </svg>
                        </div>
                        <h3 class="text-2xl font-bold text-gray-900 mb-2">🎉 Congratulations!</h3>
                        <p class="text-gray-600 mb-6">You have successfully completed the entire course!</p>
                        <div class="space-y-3">
                            <button onclick="generateCertificate()" class="w-full bg-[#002147] text-white px-6 py-3 rounded-lg hover:bg-blue-900 transition-colors font-semibold">
                                Download Certificate
                            </button>
                            <button onclick="closeModal()" class="w-full bg-gray-200 text-gray-700 px-6 py-3 rounded-lg hover:bg-gray-300 transition-colors">
                                Continue Learning
                            </button>
                        </div>
                    </div>
                `;
                document.body.appendChild(modal);
                
                window.closeModal = () => modal.remove();
                
                window.generateCertificate = () => {
                    window.open('/certificates/generate/{{ $course->id }}', '_blank');
                    modal.remove();
                };
            }
        });
    </script>

    <style>
        .tab-button:not(.active) {
            @apply border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300;
        }
        
        .tab-button.active {
            @apply border-primary text-primary;
        }
    </style>
</x-app-layout>