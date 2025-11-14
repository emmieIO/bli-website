<x-guest-layout>
    <!-- Hero Section - Udemy Style -->
    <div class="bg-gray-900 text-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
            <!-- Breadcrumb -->
            <nav class="text-sm text-purple-300 mb-4">
                <div class="flex items-center space-x-2">
                    <a href="{{ route('homepage') }}" class="hover:text-white transition-colors">Home</a>
                    <span class="text-gray-400">&gt;</span>
                    <a href="{{ route('courses.index') }}" class="hover:text-white transition-colors">Courses</a>
                    <span class="text-gray-400">&gt;</span>
                    <span class="text-gray-300">{{ $course->category->category ?? 'Development' }}</span>
                </div>
            </nav>

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                <!-- Left Content -->
                <div class="lg:col-span-2">
                    <!-- Course Title -->
                    <h1 class="text-2xl lg:text-4xl font-bold mb-4 leading-tight">
                        {{ $course->title }}
                    </h1>

                    <!-- Course Subtitle -->
                    @if($course->subtitle)
                    <p class="text-lg text-gray-300 mb-6 leading-relaxed">
                        {{ $course->subtitle }}
                    </p>
                    @endif

                    <!-- Course Meta Information -->
                    <div class="flex flex-wrap items-center gap-4 mb-6 text-sm">
                        <div class="flex items-center">
                            <span class="text-yellow-400 font-bold mr-1">4.6</span>
                            <div class="flex mr-2">
                                @for($i = 1; $i <= 5; $i++)
                                    <svg class="w-4 h-4 {{ $i <= 4 ? 'text-yellow-400' : 'text-gray-400' }}" fill="currentColor" viewBox="0 0 20 20">
                                        <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                                    </svg>
                                @endfor
                            </div>
                            <span class="text-purple-300">({{ number_format($course->students ? $course->students->count() : 0) }} ratings)</span>
                        </div>
                        <span class="text-gray-300">{{ number_format($course->students ? $course->students->count() : 0) }} students</span>
                    </div>

                    <div class="flex flex-wrap items-center gap-4 text-sm text-gray-300">
                        <div class="flex items-center">
                            <span>Created by</span>
                            <a href="#instructor" class="text-purple-300 hover:text-white ml-1 underline">{{ $course->instructor->name }}</a>
                        </div>
                        <div class="flex items-center">
                            <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"/>
                            </svg>
                            <span>Last updated {{ $course->updated_at->format('M Y') }}</span>
                        </div>
                        <div class="flex items-center">
                            <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M7 2a1 1 0 011 1v1h3a1 1 0 110 2H9.578a18.87 18.87 0 01-1.724 4.78c.29.354.596.696.914 1.026a1 1 0 11-1.44 1.389c-.188-.196-.373-.396-.554-.6a26.86 26.86 0 01-3.622 2.91 1 1 0 11-.927-1.771 24.86 24.86 0 003.156-2.54 21.07 21.07 0 01-2.357-3.615A1 1 0 014 5h3V3a1 1 0 011-1z" clip-rule="evenodd"/>
                            </svg>
                            <span>English</span>
                        </div>
                    </div>
                </div>

                <!-- Course Video Preview (Hidden on mobile, shown as thumbnail) -->
                <div class="lg:col-span-1 hidden lg:block">
                    <div class="relative">
                        <div class="aspect-video bg-black rounded-lg overflow-hidden">
                            @if($course->preview_video_id)
                                <iframe src="https://player.vimeo.com/video/{{ $course->preview_video_id }}?h=0&title=0&byline=0&portrait=0" 
                                    class="w-full h-full" frameborder="0" allowfullscreen></iframe>
                            @else
                                <div class="w-full h-full bg-gray-800 flex items-center justify-center">
                                    <div class="text-center">
                                        <svg class="w-16 h-16 text-white mx-auto mb-4" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM9.555 7.168A1 1 0 008 8v4a1 1 0 001.555.832l3-2a1 1 0 000-1.664l-3-2z" clip-rule="evenodd"/>
                                        </svg>
                                        <p class="text-white text-sm">Preview this course</p>
                                    </div>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Main Content -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            <!-- Left Content -->
            <div class="lg:col-span-2 space-y-8">

                <!-- Mobile Video Preview -->
                <div class="lg:hidden">
                    <div class="aspect-video bg-black rounded-lg overflow-hidden">
                        @if($course->preview_video_id)
                            <iframe src="https://player.vimeo.com/video/{{ $course->preview_video_id }}?h=0&title=0&byline=0&portrait=0" 
                                class="w-full h-full" frameborder="0" allowfullscreen></iframe>
                        @else
                            <div class="w-full h-full bg-gray-800 flex items-center justify-center">
                                <div class="text-center">
                                    <svg class="w-16 h-16 text-white mx-auto mb-4" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM9.555 7.168A1 1 0 008 8v4a1 1 0 001.555.832l3-2a1 1 0 000-1.664l-3-2z" clip-rule="evenodd"/>
                                    </svg>
                                    <p class="text-white text-sm">Preview this course</p>
                                </div>
                            </div>
                        @endif
                    </div>
                </div>

                <!-- What you'll learn -->
                <div class="bg-gray-50 border border-gray-200 rounded-lg p-6">
                    <h2 class="text-xl font-bold text-gray-900 mb-4">What you'll learn</h2>
                    @if($course->outcomes && $course->outcomes->count())
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
                            @foreach($course->outcomes as $outcome)
                            <div class="flex items-start">
                                <svg class="w-4 h-4 text-gray-600 mt-1 mr-3 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
                                </svg>
                                <span class="text-sm text-gray-700">{{ $outcome->outcome }}</span>
                            </div>
                            @endforeach
                        </div>
                    @else
                        <p class="text-gray-600">Learning outcomes will be updated soon.</p>
                    @endif
                </div>

                <!-- Course Content -->
                <div class="space-y-8">
                    <!-- Course Description -->
                    <div>
                        <h2 class="text-xl font-bold text-gray-900 mb-4">Description</h2>
                        <div class="prose prose-gray max-w-none">
                            <p class="text-gray-700 leading-relaxed">{{ $course->description }}</p>
                        </div>
                    </div>

                    <!-- Course Curriculum -->
                    <div>
                        <div class="flex items-center justify-between mb-4">
                            <h2 class="text-xl font-bold text-gray-900">Course content</h2>
                            <span class="text-sm text-gray-600">{{ $course->modules ? $course->modules->count() : 0 }} sections • {{ $course->modules ? $course->modules->sum(fn($m) => $m->lessons ? $m->lessons->count() : 0) : 0 }} lectures</span>
                        </div>

                        <!-- Udemy-style Curriculum -->
                        <div class="border border-gray-200 rounded-lg">
                            @forelse($course->modules as $module)
                            <div class="border-b border-gray-200 last:border-b-0">
                                <button class="w-full text-left px-6 py-4 hover:bg-gray-50 focus:bg-gray-50 transition-colors" 
                                        onclick="toggleModule({{ $module->id }})">
                                    <div class="flex items-center justify-between">
                                        <div class="flex items-center">
                                            <svg class="w-4 h-4 text-gray-600 mr-3 transform transition-transform" id="chevron-{{ $module->id }}">
                                                <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                                            </svg>
                                            <h3 class="font-medium text-gray-900">{{ $module->title }}</h3>
                                        </div>
                                        <span class="text-sm text-gray-500">{{ $module->lessons ? $module->lessons->count() : 0 }} lectures</span>
                                    </div>
                                </button>
                                <div id="module-{{ $module->id }}-collapse" class="hidden border-t border-gray-100">
                                    <div class="px-6 py-4 bg-gray-50">
                                        @if($module->lessons && $module->lessons->count())
                                            <ul class="space-y-2">
                                                @foreach($module->lessons as $lesson)
                                                <li class="flex items-center justify-between text-sm">
                                                    <div class="flex items-center">
                                                        <div class="w-6 h-6 rounded bg-white border border-gray-200 flex items-center justify-center mr-3">
                                                            @switch($lesson->type)
                                                                @case('video')
                                                                    <svg class="w-3 h-3 text-gray-600" fill="currentColor" viewBox="0 0 20 20">
                                                                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM9.555 7.168A1 1 0 008 8v4a1 1 0 001.555.832l3-2a1 1 0 000-1.664l-3-2z" clip-rule="evenodd"/>
                                                                    </svg>
                                                                @break
                                                                @case('pdf')
                                                                    <svg class="w-3 h-3 text-gray-600" fill="currentColor" viewBox="0 0 20 20">
                                                                        <path fill-rule="evenodd" d="M4 4a2 2 0 012-2h4.586A2 2 0 0112 2.586L15.414 6A2 2 0 0116 7.414V16a2 2 0 01-2 2H6a2 2 0 01-2-2V4z" clip-rule="evenodd"/>
                                                                    </svg>
                                                                @break
                                                                @default
                                                                    <svg class="w-3 h-3 text-gray-600" fill="currentColor" viewBox="0 0 20 20">
                                                                        <path fill-rule="evenodd" d="M3 4a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zm0 4a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zm0 4a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1z" clip-rule="evenodd"/>
                                                                    </svg>
                                                            @endswitch
                                                        </div>
                                                        <span class="text-gray-700">{{ $lesson->title }}</span>
                                                    </div>
                                                    @if($lesson->duration)
                                                        <span class="text-gray-500">{{ $lesson->duration }}</span>
                                                    @endif
                                                </li>
                                                @endforeach
                                            </ul>
                                        @else
                                            <p class="text-gray-500 text-sm">No lessons available</p>
                                        @endif
                                    </div>
                                </div>
                            </div>
                            @empty
                            <div class="p-6 text-center">
                                <p class="text-gray-500">Course curriculum coming soon</p>
                            </div>
                            @endforelse
                        </div>
                    </div>

                    <!-- Requirements -->
                    @if($course->requirements && $course->requirements->count())
                    <div>
                        <h2 class="text-xl font-bold text-gray-900 mb-4">Requirements</h2>
                        <ul class="space-y-2">
                            @foreach($course->requirements as $requirement)
                            <li class="flex items-start">
                                <span class="w-2 h-2 bg-gray-400 rounded-full mt-2 mr-3 flex-shrink-0"></span>
                                <span class="text-gray-700">{{ $requirement->requirement }}</span>
                            </li>
                            @endforeach
                        </ul>
                    </div>
                    @endif

                    <!-- Instructor -->
                    <div id="instructor">
                        <h2 class="text-xl font-bold text-gray-900 mb-4">Instructor</h2>
                        <div class="border border-gray-200 rounded-lg p-6">
                            <div class="flex items-start space-x-4">
                                <img src="{{ $course->instructor->avatar_url ?? 'https://ui-avatars.com/api/?name='.urlencode($course->instructor->name).'&background=6366f1&color=fff&size=128' }}" 
                                     alt="{{ $course->instructor->name }}" 
                                     class="w-16 h-16 rounded-full object-cover">
                                <div class="flex-1">
                                    <h3 class="text-lg font-semibold text-gray-900 mb-1">{{ $course->instructor->name }}</h3>
                                    @if($course->instructor->title)
                                    <p class="text-purple-600 font-medium mb-2">{{ $course->instructor->title }}</p>
                                    @endif
                                    
                                    <div class="flex items-center space-x-4 text-sm text-gray-600 mb-3">
                                        <div class="flex items-center">
                                            <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                                <path fill-rule="evenodd" d="M11.49 3.17c-.38-1.56-2.6-1.56-2.98 0a1.532 1.532 0 01-2.286.948c-1.372-.836-2.942.734-2.106 2.106.54.886.061 2.042-.947 2.287-1.561.379-1.561 2.6 0 2.978a1.532 1.532 0 01.947 2.287c-.836 1.372.734 2.942 2.106 2.106a1.532 1.532 0 012.287.947c.379 1.561 2.6 1.561 2.978 0a1.533 1.533 0 012.287-.947c1.372.836 2.942-.734 2.106-2.106a1.533 1.533 0 01.947-2.287c1.561-.379 1.561-2.6 0-2.978a1.532 1.532 0 01-.947-2.287c.836-1.372-.734-2.942-2.106-2.106a1.532 1.532 0 01-2.287-.947zM10 13a3 3 0 100-6 3 3 0 000 6z" clip-rule="evenodd"/>
                                            </svg>
                                            <span>Instructor Rating: 4.6</span>
                                        </div>
                                        <div class="flex items-center">
                                            <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                                <path d="M13 6a3 3 0 11-6 0 3 3 0 016 0zM18 8a2 2 0 11-4 0 2 2 0 014 0zM14 15a4 4 0 00-8 0v3h8v-3z"/>
                                            </svg>
                                            <span>{{ number_format($course->students->count() ?? 0) }} Students</span>
                                        </div>
                                        <div class="flex items-center">
                                            <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                                <path fill-rule="evenodd" d="M3 4a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zm0 4a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zm0 4a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1z" clip-rule="evenodd"/>
                                            </svg>
                                            <span>1 Course</span>
                                        </div>
                                    </div>
                                    
                                    <p class="text-gray-700 text-sm leading-relaxed">
                                        {{ $course->instructor->bio ?? 'Experienced instructor passionate about sharing knowledge and helping students achieve their learning goals.' }}
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Udemy-style Sidebar -->
            <div class="lg:col-span-1">
                <div class="sticky top-8">
                    <div class="bg-white border border-gray-200 rounded-lg shadow-lg p-6">
                        <!-- Course Price -->
                        <div class="text-center mb-6">
                            <div class="flex items-baseline justify-center mb-2">
                                <span class="text-3xl font-bold text-gray-900">₦{{ number_format($course->price, 0) }}</span>
                                @if($course->price > 0)
                                <span class="text-lg text-gray-500 line-through ml-2">₦{{ number_format($course->price * 1.5, 0) }}</span>
                                @endif
                            </div>
                            @if($course->price > 0)
                            <div class="flex items-center justify-center">
                                <span class="bg-yellow-200 text-yellow-800 text-xs font-semibold px-2 py-1 rounded-full">83% off</span>
                                <span class="text-red-600 text-sm font-medium ml-2">2 days left at this price!</span>
                            </div>
                            @else
                            <span class="text-green-600 font-semibold">Free</span>
                            @endif
                        </div>

                        <!-- CTA Buttons -->
                        <div class="space-y-3 mb-6">
                            <button class="w-full bg-primary hover:bg-primary/90 text-white font-bold py-3 px-4 rounded transition-colors">
                                Add to cart
                            </button>
                            <button class="w-full border border-gray-300 hover:bg-gray-50 text-gray-900 font-bold py-3 px-4 rounded transition-colors">
                                Buy now
                            </button>
                        </div>

                        <!-- Course Includes -->
                        <div class="space-y-3 text-sm">
                            <p class="font-semibold text-gray-900 mb-3">This course includes:</p>
                            <div class="space-y-2">
                                <div class="flex items-center">
                                    <svg class="w-4 h-4 text-gray-600 mr-3" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-12a1 1 0 10-2 0v4a1 1 0 00.293.707l2.828 2.829a1 1 0 101.415-1.415L11 9.586V6z" clip-rule="evenodd"/>
                                    </svg>
                                    <span class="text-gray-700">{{ $course->modules ? $course->modules->sum(fn($m) => $m->lessons ? $m->lessons->count() : 0) : 0 }} on-demand videos</span>
                                </div>
                                <div class="flex items-center">
                                    <svg class="w-4 h-4 text-gray-600 mr-3" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M3 17a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zm3.293-7.707a1 1 0 011.414 0L9 10.586V3a1 1 0 112 0v7.586l1.293-1.293a1 1 0 111.414 1.414l-3 3a1 1 0 01-1.414 0l-3-3a1 1 0 010-1.414z" clip-rule="evenodd"/>
                                    </svg>
                                    <span class="text-gray-700">Downloadable resources</span>
                                </div>
                                <div class="flex items-center">
                                    <svg class="w-4 h-4 text-gray-600 mr-3" fill="currentColor" viewBox="0 0 20 20">
                                        <path d="M2 6a2 2 0 012-2h6a2 2 0 012 2v6a2 2 0 01-2 2H4a2 2 0 01-2-2V6zM14.553 7.106A1 1 0 0014 8v4a1 1 0 00.553.894l2 1A1 1 0 0018 13V7a1 1 0 00-1.447-.894l-2 1z"/>
                                    </svg>
                                    <span class="text-gray-700">Access on mobile and TV</span>
                                </div>
                                <div class="flex items-center">
                                    <svg class="w-4 h-4 text-gray-600 mr-3" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                                    </svg>
                                    <span class="text-gray-700">Full lifetime access</span>
                                </div>
                                <div class="flex items-center">
                                    <svg class="w-4 h-4 text-gray-600 mr-3" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M6.267 3.455a3.066 3.066 0 001.745-.723 3.066 3.066 0 013.976 0 3.066 3.066 0 001.745.723 3.066 3.066 0 012.812 2.812c.051.643.304 1.254.723 1.745a3.066 3.066 0 010 3.976 3.066 3.066 0 00-.723 1.745 3.066 3.066 0 01-2.812 2.812 3.066 3.066 0 00-1.745.723 3.066 3.066 0 01-3.976 0 3.066 3.066 0 00-1.745-.723 3.066 3.066 0 01-2.812-2.812 3.066 3.066 0 00-.723-1.745 3.066 3.066 0 010-3.976 3.066 3.066 0 00.723-1.745 3.066 3.066 0 012.812-2.812zm7.44 5.252a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                                    </svg>
                                    <span class="text-gray-700">Certificate of completion</span>
                                </div>
                            </div>
                        </div>

                        <!-- 30-day guarantee -->
                        <div class="mt-6 pt-6 border-t border-gray-200 text-center">
                            <p class="text-xs text-gray-600">30-Day Money-Back Guarantee</p>
                        </div>
                    </div>

                    <!-- Course Stats -->
                    <div class="bg-white border border-gray-200 rounded-lg shadow-lg p-6 mt-6">
                        <h3 class="font-semibold text-gray-900 mb-4">Course Stats</h3>
                        <div class="space-y-3 text-sm">
                            <div class="flex justify-between">
                                <span class="text-gray-600">Skill level</span>
                                <span class="font-medium">{{ ucfirst($course->level->value ?? 'All Levels') }}</span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-gray-600">Students</span>
                                <span class="font-medium">{{ number_format($course->students ? $course->students->count() : 0) }}</span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-gray-600">Languages</span>
                                <span class="font-medium">English</span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-gray-600">Captions</span>
                                <span class="font-medium">Yes</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Udemy-style JavaScript -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Module toggle functionality
            window.toggleModule = function(moduleId) {
                const collapse = document.getElementById(`module-${moduleId}-collapse`);
                const chevron = document.getElementById(`chevron-${moduleId}`);
                
                if (collapse && chevron) {
                    const isHidden = collapse.classList.contains('hidden');
                    
                    if (isHidden) {
                        collapse.classList.remove('hidden');
                        chevron.style.transform = 'rotate(90deg)';
                    } else {
                        collapse.classList.add('hidden');
                        chevron.style.transform = 'rotate(0deg)';
                    }
                }
            };
        });
    </script>
</x-guest-layout>
