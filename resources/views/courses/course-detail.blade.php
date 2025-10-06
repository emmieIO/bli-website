<x-guest-layout>
    <!-- Breadcrumb Navigation -->
    <section class="py-5 border-b border-gray-200">
        <div class="container mx-auto px-4">
            <nav class="breadcrumb">
                <ul class="flex items-center space-x-2 text-sm text-gray-600">
                    <li class="inline-flex items-center">
                        <a href="{{ route('homepage') }}" class="text-blue-600 hover:text-blue-800 transition-colors">Home</a>
                    </li>
                    <li class="inline-flex items-center">
                        <i data-lucide="chevron-right" class="w-4 h-4 mx-1 text-gray-400"></i>
                    </li>
                    <li class="inline-flex items-center">
                        <a href="{{ route('courses.index') }}" class="text-blue-600 hover:text-blue-800 transition-colors">Courses</a>
                    </li>
                    <li class="inline-flex items-center">
                        <i data-lucide="chevron-right" class="w-4 h-4 mx-1 text-gray-400"></i>
                    </li>
                    <li class="inline-flex items-center">
                        <span class="text-gray-600 truncate max-w-xs">{{ $course->title }}</span>
                    </li>
                </ul>
            </nav>
        </div>
    </section>

    <!-- Course Details Section -->
    <section class="py-12">
        <div class="container mx-auto px-4">
            <!-- Course Title -->
            <h1 class="text-3xl md:text-4xl font-bold text-gray-900 mb-8 line-clamp-2 leading-tight">
                {{ $course->title }}
            </h1>

            <div class="grid grid-cols-1 lg:grid-cols-4 gap-8">
                <!-- Sidebar -->
                <div class="lg:col-span-1 space-y-6">
                    <!-- Video Player -->
                    <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
                        <div class="courses-details__video">
                            <iframe
                                src="https://player.mux.com/rR8P8mSaKDzz02TsftugTUdI00cQPJX00oy?metadata-video-title=Test+Video&video-title=Test+Video"
                                class="w-full border-none aspect-video"
                                allow="accelerometer; gyroscope; autoplay; encrypted-media; picture-in-picture;"
                                allowfullscreen>
                            </iframe>
                        </div>
                    </div>

                    <!-- Course Info Card -->
                    <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                        <h5 class="text-2xl font-semibold text-gray-900 mb-6">
                            &#8358;{{ number_format($course->price, 2) }}
                        </h5>

                        <div class="space-y-4">
                            <!-- Reviews -->
                            <div class="flex items-center gap-3 text-gray-600">
                                <i data-lucide="thumbs-up" class="w-5 h-5 text-green-500"></i>
                                <span class="text-sm">100% positive reviews</span>
                            </div>

                            <!-- Students -->
                            <div class="flex items-center gap-3 text-gray-600">
                                <i data-lucide="users" class="w-5 h-5 text-blue-500"></i>
                                <span class="text-sm">391 students</span>
                            </div>

                            <!-- Lessons -->
                            <div class="flex items-center gap-3 text-gray-600">
                                <i data-lucide="file-text" class="w-5 h-5 text-purple-500"></i>
                                <span class="text-sm">15 lessons</span>
                            </div>

                            <!-- Language -->
                            <div class="flex items-center gap-3 text-gray-600">
                                <i data-lucide="globe" class="w-5 h-5 text-orange-500"></i>
                                <span class="text-sm">Language: English</span>
                            </div>

                            <!-- Quizzes -->
                            <div class="flex items-center gap-3 text-gray-600">
                                <i data-lucide="help-circle" class="w-5 h-5 text-red-500"></i>
                                <span class="text-sm">1 quiz</span>
                            </div>

                            <!-- Assessments -->
                            <div class="flex items-center gap-3 text-gray-600">
                                <i data-lucide="clipboard-check" class="w-5 h-5 text-green-600"></i>
                                <span class="text-sm">Assessments: Yes</span>
                            </div>

                            <!-- Mobile App -->
                            <div class="flex items-center gap-3 text-gray-600">
                                <i data-lucide="smartphone" class="w-5 h-5 text-indigo-500"></i>
                                <span class="text-sm">Available on the app</span>
                            </div>

                            <!-- Access -->
                            <div class="flex items-center gap-3 text-gray-600">
                                <i data-lucide="infinity" class="w-5 h-5 text-purple-600"></i>
                                <span class="text-sm">Unlimited access forever</span>
                            </div>

                            <!-- Skill Level -->
                            <div class="flex items-center gap-3 text-gray-600">
                                <i data-lucide="trending-up" class="w-5 h-5 text-blue-600"></i>
                                <span class="text-sm">Skill level: </span>
                                <span class="text-blue-600 font-medium">All levels</span>
                            </div>
                        </div>

                        <!-- Start Button -->
                        <a href=""
                            class="w-full bg-blue-600 hover:bg-blue-700 text-white font-semibold py-3 px-6 rounded-lg transition-colors flex items-center justify-center gap-2 mt-6">
                            <i data-lucide="play" class="w-5 h-5"></i>
                            <span>Start Now</span>
                        </a>
                    </div>
                </div>

                <!-- Main Content -->
                <div class="lg:col-span-3 space-y-8">
                    <!-- Course Description -->
                    <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                        <p class="text-gray-700 leading-relaxed text-lg">
                            {{ $course->description }}
                        </p>
                    </div>

                    <!-- Tabs Navigation -->
                    <div class="border-b border-gray-200">
                        <div class="flex flex-wrap gap-8 -mb-px">
                            <button class="tab-btn active text-blue-600 font-semibold py-3 border-b-2 border-blue-600 transition-colors" data-tab="description">
                                Description
                            </button>
                            <button class="tab-btn text-gray-600 hover:text-blue-600 font-semibold py-3 border-b-2 border-transparent transition-colors" data-tab="curriculum">
                                Curriculum
                            </button>
                            <button class="tab-btn text-gray-600 hover:text-blue-600 font-semibold py-3 border-b-2 border-transparent transition-colors" data-tab="instructor">
                                Instructor
                            </button>
                        </div>
                    </div>

                    <!-- Tab Contents -->
                    <div class="space-y-8">
                        <!-- Description Tab -->
                        <div class="tab-content active" id="description">
                            <!-- What You'll Learn -->
                            <div class="bg-blue-50 rounded-xl p-6 border border-blue-100">
                                <h5 class="text-xl font-semibold text-gray-900 mb-6">What You'll Learn</h5>
                                <div class="space-y-4">
                                    @if ($course->outcomes()->count())
                                        @foreach ($course->outcomes as $outcome)
                                            <div class="flex gap-4">
                                                <i data-lucide="check-circle" class="w-5 h-5 text-green-500 mt-1 flex-shrink-0"></i>
                                                <p class="text-gray-700 leading-relaxed">
                                                    {{ $outcome->outcome }}
                                                </p>
                                            </div>
                                        @endforeach
                                    @endif
                                </div>
                            </div>

                            <!-- Detailed Description -->
                            <div class="bg-white rounded-xl p-6 space-y-6">
                                <p class="text-gray-700 leading-relaxed">
                                    A series of Videos from ThimPress, give you a detailed
                                    tutorial to create an LMS Website with LearnPress – LMS &
                                    Education WordPress Plugin.
                                </p>
                                <p class="text-gray-700 leading-relaxed">
                                    This course is a detailed and easy tutorial to get you all
                                    setup and going with the use of LearnPress LMS Plugin. It is
                                    a free and simple plugin to help you create an Online
                                    Courses Website step by step. The tutorial guides you
                                    through the configuration of the plugin, creation of
                                    Courses, Lessons, Quizzes, and finally guides you on how to
                                    boost up your Website with Premium LearnPress Add-ons
                                    brought to you by ThimPress (creator of LearnPress). It also
                                    shows how you could configure additional items like the
                                    course layouts and featured images …
                                </p>
                                <img src="{{ asset('images/courses/courses-desc-img-01.png') }}" 
                                     alt="Course description" 
                                     class="w-full rounded-lg shadow-sm">
                                <p class="text-gray-700 leading-relaxed">
                                    A series of Videos from ThimPress, give you a detailed
                                    tutorial to create an LMS Website with LearnPress – LMS &
                                    Education WordPress Plugin.
                                </p>
                            </div>
                        </div>

                        <!-- Curriculum Tab -->
                        <div class="tab-content hidden" id="curriculum">
                            <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
                                <!-- Curriculum Sections -->
                                <div class="space-y-2">
                                    <!-- Section 1 -->
                                    <div class="curriculum-section border-b border-gray-200 last:border-b-0">
                                        <div class="flex items-center justify-between p-6 cursor-pointer hover:bg-gray-50 transition-colors">
                                            <div class="flex items-center gap-4">
                                                <i data-lucide="chevron-down" class="w-5 h-5 text-gray-400 transition-transform"></i>
                                                <h6 class="font-semibold text-gray-900">LearnPress Getting Started</h6>
                                            </div>
                                            <span class="bg-gray-100 text-gray-700 px-3 py-1 rounded-full text-sm font-semibold">3</span>
                                        </div>
                                        
                                        <!-- Section Content -->
                                        <div class="curriculum-content hidden px-6 pb-6 space-y-4">
                                            <!-- Lesson 1 -->
                                            <div class="flex items-center justify-between p-4 bg-gray-50 rounded-lg">
                                                <div class="flex items-center gap-4">
                                                    <i data-lucide="file-text" class="w-5 h-5 text-blue-500"></i>
                                                    <p class="text-gray-700 font-medium">What is LearnPress?</p>
                                                </div>
                                                <div class="flex items-center gap-4">
                                                    <span class="text-gray-500 text-sm">20 minutes</span>
                                                    <a href="#" class="text-blue-600 hover:text-blue-700">
                                                        <i data-lucide="eye" class="w-5 h-5"></i>
                                                    </a>
                                                </div>
                                            </div>

                                            <!-- Lesson 2 -->
                                            <div class="flex items-center justify-between p-4 bg-gray-50 rounded-lg">
                                                <div class="flex items-center gap-4">
                                                    <i data-lucide="file-text" class="w-5 h-5 text-blue-500"></i>
                                                    <p class="text-gray-700 font-medium">How to use LearnPress?</p>
                                                </div>
                                                <div class="flex items-center gap-4">
                                                    <span class="text-gray-500 text-sm">60 minutes</span>
                                                    <a href="#" class="text-blue-600 hover:text-blue-700">
                                                        <i data-lucide="eye" class="w-5 h-5"></i>
                                                    </a>
                                                </div>
                                            </div>

                                            <!-- Quiz -->
                                            <div class="flex items-center justify-between p-4 bg-gray-50 rounded-lg">
                                                <div class="flex items-center gap-4">
                                                    <i data-lucide="help-circle" class="w-5 h-5 text-purple-500"></i>
                                                    <p class="text-gray-700 font-medium">Demo the Quiz of LearnPress</p>
                                                </div>
                                                <div class="flex items-center gap-4">
                                                    <span class="text-gray-500 text-sm">4 questions</span>
                                                    <span class="text-gray-500 text-sm">10 minutes</span>
                                                    <a href="#" class="text-gray-400 cursor-not-allowed">
                                                        <i data-lucide="lock" class="w-5 h-5"></i>
                                                    </a>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Add more sections as needed -->
                                </div>
                            </div>
                        </div>

                        <!-- Instructor Tab -->
                        <div class="tab-content hidden" id="instructor">
                            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-8">
                                <div class="flex flex-col md:flex-row gap-8">
                                    <img src="{{ asset('images/avatar/user.png') }}" 
                                         alt="Instructor" 
                                         class="w-24 h-24 rounded-full object-cover flex-shrink-0">
                                    <div class="flex-1">
                                        <a href="#">
                                            <h5 class="text-xl font-semibold text-gray-900 mb-2">Keny White</h5>
                                        </a>
                                        <span class="text-gray-500 text-sm block mb-6">Professor</span>
                                        <p class="text-gray-700 leading-relaxed">
                                            Lorem ipsum dolor sit amet. Qui incidunt dolores non
                                            similique ducimus et debitis molestiae. Et autem quia
                                            eum reprehenderit voluptates est reprehenderit illo est
                                            enim perferendis est neque sunt. Nam amet sunt aut vero
                                            mollitia ut ipsum corporis vel facere eius et quia
                                            aspernatur qui fugiat repudiandae. Et officiis inventore
                                            et quis enim ut quaerat corporis sed reprehenderit odit
                                            sit saepe distinctio et accusantium repellendus ea enim
                                            harum.
                                        </p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Reviews Section -->
                    {{-- <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-8">
                        <h5 class="text-xl font-semibold text-gray-900 mb-8">Reviews</h5>
                        
                        <!-- Overall Rating -->
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-8 mb-8">
                            <div class="text-center">
                                <h4 class="text-5xl font-medium text-gray-900 mb-2">5</h4>
                                <div class="flex justify-center gap-1 mb-2">
                                    @for($i = 0; $i < 5; $i++)
                                        <i data-lucide="star" class="w-5 h-5 text-yellow-400 fill-current"></i>
                                    @endfor
                                </div>
                                <p class="text-gray-600">1 rating</p>
                            </div>

                            <!-- Rating Breakdown -->
                            <div class="space-y-3">
                                @foreach([5,4,3,2,1] as $rating)
                                <div class="flex items-center gap-4">
                                    <span class="text-sm font-semibold text-gray-700 w-4">{{ $rating }}</span>
                                    <div class="flex-1 bg-gray-200 rounded-full h-2">
                                        <div class="bg-yellow-400 h-2 rounded-full {{ $rating === 5 ? 'w-full' : 'w-0' }}"></div>
                                    </div>
                                    <span class="text-sm text-gray-600 w-12">{{ $rating === 5 ? '100%' : '0%' }}</span>
                                </div>
                                @endforeach
                            </div>
                        </div>

                        <!-- Write Review Button -->
                        <button class="write-review-btn bg-blue-600 hover:bg-blue-700 text-white font-semibold py-3 px-6 rounded-lg transition-colors mb-8">
                            Write A Review
                        </button>

                        <!-- Review Form (Hidden by default) -->
                        <div class="write-review-form hidden bg-gray-50 rounded-xl p-6 border border-gray-200 mb-8">
                            <div class="flex justify-between items-center mb-6">
                                <h6 class="text-lg font-semibold text-gray-900">Write A Review</h6>
                                <button class="close-review-form text-gray-400 hover:text-gray-600">
                                    <i data-lucide="x" class="w-6 h-6"></i>
                                </button>
                            </div>
                            <form class="space-y-4">
                                <div>
                                    <label for="review-title" class="block text-sm font-semibold text-gray-700 mb-2">
                                        Title <span class="text-red-500">*</span>
                                    </label>
                                    <input type="text" id="review-title" required 
                                           class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                                </div>
                                <div>
                                    <label for="review-content" class="block text-sm font-semibold text-gray-700 mb-2">
                                        Content <span class="text-red-500">*</span>
                                    </label>
                                    <textarea id="review-content" rows="3" required
                                              class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 resize-none"></textarea>
                                </div>
                                <div>
                                    <label class="block text-sm font-semibold text-gray-700 mb-2">
                                        Rating <span class="text-red-500">*</span>
                                    </label>
                                    <div class="flex gap-1" id="star-rating">
                                        @for($i = 1; $i <= 5; $i++)
                                            <i data-lucide="star" class="star-rating w-6 h-6 text-gray-300 cursor-pointer hover:text-yellow-400" data-rating="{{ $i }}"></i>
                                        @endfor
                                    </div>
                                </div>
                                <div class="flex gap-4">
                                    <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white font-semibold py-2 px-6 rounded-lg transition-colors">
                                        Add Review
                                    </button>
                                    <button type="button" class="cancel-review-form text-gray-600 hover:text-gray-800 font-semibold py-2 px-6 rounded-lg transition-colors">
                                        Cancel
                                    </button>
                                </div>
                            </form>
                        </div>

                        <!-- Sample Review -->
                        <div class="border-t border-gray-200 pt-8">
                            <div class="flex flex-col md:flex-row gap-6 mb-4">
                                <img src="{{ asset('images/avatar/user.png') }}" 
                                     alt="Reviewer" 
                                     class="w-12 h-12 rounded-full object-cover">
                                <div>
                                    <p class="font-semibold text-gray-900">Keith Son</p>
                                    <div class="flex gap-1 mb-2">
                                        @for($i = 0; $i < 5; $i++)
                                            <i data-lucide="star" class="w-4 h-4 text-yellow-400 fill-current"></i>
                                        @endfor
                                    </div>
                                </div>
                            </div>
                            <div>
                                <h6 class="text-lg font-semibold text-gray-900 mb-3">Courses Review</h6>
                                <p class="text-gray-700 leading-relaxed">
                                    is simply dummy text of the printing and typesetting
                                    industry. Lorem Ipsum has been the industry's standard
                                    dummy text ever since the 1500s, when an unknown printer
                                    took a galley of type and scrambled it to make a type
                                    specimen book. It has survived not only five centuries,
                                </p>
                            </div>
                        </div>
                    </div> --}}
                </div>
            </div>
        </div>
    </section>

    <!-- Related Courses -->
    <section class="py-16 bg-gray-50">
        <div class="container mx-auto px-4">
            <div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-12 gap-4">
                <h5 class="text-2xl md:text-3xl font-bold text-gray-900">
                    Courses You Might Be Interested In
                </h5>
                <a href="{{ route('courses.index') }}"
                   class="bg-blue-600 hover:bg-blue-700 text-white font-semibold py-3 px-6 rounded-lg transition-colors flex items-center gap-2">
                    <span>View All Courses</span>
                    <i data-lucide="arrow-right" class="w-4 h-4"></i>
                </a>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
                <!-- Sample Related Course Cards -->
                @for($i = 0; $i < 4; $i++)
                <article class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden hover:shadow-lg transition-all duration-300">
                    <div class="relative">
                        <a href="#">
                            <img src="{{ asset('images/courses/courses-large-0' . ($i+1) . '.png') }}" 
                                 alt="Related Course" 
                                 class="w-full h-48 object-cover">
                        </a>
                        <div class="absolute top-3 left-3">
                            <span class="bg-blue-600 text-white text-xs px-2 py-1 rounded">
                                {{ ['Language Learning', 'Coaching', 'Online Business', '3D & Animation'][$i] }}
                            </span>
                        </div>
                    </div>
                    <div class="p-4">
                        <h6 class="font-semibold text-gray-900 mb-2 line-clamp-2 leading-tight">
                            <a href="#" class="hover:text-blue-600 transition-colors">
                                {{ ['Introduction LearnPress – LMS plugin', 'Create an LMS Website with LearnPress', 'How To Create An Online Course', 'The Complete Online Teaching Masterclass'][$i] }}
                            </a>
                        </h6>
                        <div class="flex items-center gap-4 mb-3 text-sm text-gray-600">
                            <span class="flex items-center gap-1">
                                <i data-lucide="users" class="w-4 h-4"></i>
                                <span>365 Students</span>
                            </span>
                            <span class="flex items-center gap-1">
                                <i data-lucide="clock" class="w-4 h-4"></i>
                                <span>10 Weeks</span>
                            </span>
                        </div>
                        <p class="text-gray-600 text-sm mb-4 line-clamp-2 leading-relaxed">
                            {{ ['A WordPress LMS Plugin to create WordPress Learning Management System...', 'Lorem ipsum dolor sit amet. Qui incidunt dolores non similique ducimus...', 'The jQuery team knows all about cross-browser issues, and they have written...', 'In this course, We\'ll learn how to create websites by structuring and styling...'][$i] }}
                        </p>
                        <div class="flex items-center justify-between">
                            <div class="flex items-center gap-2">
                                <span class="text-gray-400 text-sm line-through">$39.00</span>
                                <span class="font-semibold text-gray-900">$69.00</span>
                            </div>
                            <a href="#" class="bg-blue-600 hover:bg-blue-700 text-white text-sm font-semibold px-4 py-2 rounded-lg transition-colors">
                                Start Learning
                            </a>
                        </div>
                    </div>
                </article>
                @endfor
            </div>
        </div>
    </section>

    <script>
        // Initialize Lucide icons
        document.addEventListener('DOMContentLoaded', function() {
            if (typeof lucide !== 'undefined') {
                lucide.createIcons();
            }

            // Tab functionality
            const tabButtons = document.querySelectorAll('.tab-btn');
            const tabContents = document.querySelectorAll('.tab-content');

            tabButtons.forEach(button => {
                button.addEventListener('click', function() {
                    const targetTab = this.getAttribute('data-tab');
                    
                    // Update active tab button
                    tabButtons.forEach(btn => {
                        btn.classList.remove('active', 'text-blue-600', 'border-blue-600');
                        btn.classList.add('text-gray-600', 'border-transparent');
                    });
                    this.classList.add('active', 'text-blue-600', 'border-blue-600');
                    this.classList.remove('text-gray-600', 'border-transparent');
                    
                    // Show target tab content
                    tabContents.forEach(content => {
                        content.classList.add('hidden');
                        content.classList.remove('active');
                    });
                    document.getElementById(targetTab).classList.remove('hidden');
                    document.getElementById(targetTab).classList.add('active');
                });
            });

            // Curriculum section toggle
            const curriculumSections = document.querySelectorAll('.curriculum-section');
            curriculumSections.forEach(section => {
                const header = section.querySelector('.flex.items-center.justify-between');
                const content = section.querySelector('.curriculum-content');
                const icon = section.querySelector('[data-lucide="chevron-down"]');
                
                header.addEventListener('click', function() {
                    content.classList.toggle('hidden');
                    icon.style.transform = content.classList.contains('hidden') ? 'rotate(0deg)' : 'rotate(180deg)';
                });
            });

            // Review functionality
            const writeReviewBtn = document.querySelector('.write-review-btn');
            const reviewForm = document.querySelector('.write-review-form');
            const closeReviewBtn = document.querySelector('.close-review-form');
            const cancelReviewBtn = document.querySelector('.cancel-review-form');
            const starRatings = document.querySelectorAll('.star-rating');

            writeReviewBtn?.addEventListener('click', function() {
                reviewForm.classList.remove('hidden');
            });

            closeReviewBtn?.addEventListener('click', function() {
                reviewForm.classList.add('hidden');
            });

            cancelReviewBtn?.addEventListener('click', function() {
                reviewForm.classList.add('hidden');
            });

            // Star rating functionality
            starRatings.forEach(star => {
                star.addEventListener('click', function() {
                    const rating = parseInt(this.getAttribute('data-rating'));
                    
                    // Update all stars
                    starRatings.forEach(s => {
                        const starRating = parseInt(s.getAttribute('data-rating'));
                        if (starRating <= rating) {
                            s.classList.remove('text-gray-300');
                            s.classList.add('text-yellow-400', 'fill-current');
                        } else {
                            s.classList.remove('text-yellow-400', 'fill-current');
                            s.classList.add('text-gray-300');
                        }
                    });
                });
            });
        });

        // Add custom styles
        const style = document.createElement('style');
        style.textContent = `
            .line-clamp-2 {
                display: -webkit-box;
                -webkit-line-clamp: 2;
                -webkit-box-orient: vertical;
                overflow: hidden;
            }
            
            .transition-all {
                transition: all 0.3s ease;
            }
            
            .curriculum-content {
                transition: all 0.3s ease;
            }
        `;
        document.head.appendChild(style);
    </script>
</x-guest-layout>