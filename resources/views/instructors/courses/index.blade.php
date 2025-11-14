<x-app-layout>
    <!-- Enhanced Header Section -->
    <div class="bg-blue-50 rounded-2xl p-8 mb-8" data-aos="fade-down">
        <div class="lg:flex justify-between items-center">
            <div>
                <div class="flex items-center mb-4">
                    <div class="w-12 h-12 rounded-2xl bg-primary flex items-center justify-center mr-4">
                        <i data-lucide="graduation-cap" class="w-6 h-6 text-white"></i>
                    </div>
                    <div>
                        <h2 class="text-3xl font-bold font-montserrat" style="color: #002147;">
                            My Courses
                        </h2>
                        <p class="text-gray-600 font-lato">Welcome back, {{ auth()->user()->name }}!</p>
                    </div>
                </div>
                <p class="text-gray-600 font-lato leading-relaxed max-w-2xl">
                    Manage your course library, track student progress, and create engaging learning experiences.
                    Your expertise shapes the future of professional development.
                </p>
            </div>
            <div class="mt-6 lg:mt-0">
                <a href="{{ route('instructor.courses.create') }}"
                    class="inline-flex items-center px-6 py-3 rounded-xl font-semibold font-montserrat bg-primary hover:bg-secondary text-white transition-colors duration-300 shadow-lg hover:shadow-xl">
                    <i data-lucide="plus" class="w-5 h-5 mr-2"></i>
                    Create New Course
                </a>
            </div>
        </div>
    </div>

    <!-- Enhanced Stats Dashboard -->
    <div class="mb-8">
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
            <!-- Total Courses -->
            <div class="bg-white rounded-2xl shadow-lg border border-gray-100 overflow-hidden group hover:shadow-xl transition-all duration-300"
                data-aos="fade-up" data-aos-delay="100">
                <div class="p-6">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm font-medium text-gray-600 font-lato mb-1">Total Courses</p>
                            <p class="text-3xl font-bold font-montserrat" style="color: #002147;">
                                {{ $instructorStats['total_courses'] }}
                            </p>
                            <p class="text-xs text-gray-500 mt-1 font-lato">Published & Draft</p>
                        </div>
                        <div
                            class="w-16 h-16 rounded-2xl bg-blue-100 flex items-center justify-center group-hover:scale-110 transition-transform duration-300">
                            <i data-lucide="book-open" class="w-8 h-8" style="color: #002147;"></i>
                        </div>
                    </div>
                </div>
                <div class="h-1 bg-blue-500"></div>
            </div>

            <!-- Total Students -->
            <div class="bg-white rounded-2xl shadow-lg border border-gray-100 overflow-hidden group hover:shadow-xl transition-all duration-300"
                data-aos="fade-up" data-aos-delay="200">
                <div class="p-6">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm font-medium text-gray-600 font-lato mb-1">Total Students</p>
                            <p class="text-3xl font-bold font-montserrat" style="color: #ed1c24;">
                                {{ $instructorStats['total_students'] }}
                            </p>
                            <p class="text-xs text-gray-500 mt-1 font-lato">Active Learners</p>
                        </div>
                        <div
                            class="w-16 h-16 rounded-2xl bg-red-100 flex items-center justify-center group-hover:scale-110 transition-transform duration-300">
                            <i data-lucide="users" class="w-8 h-8" style="color: #ed1c24;"></i>
                        </div>
                    </div>
                </div>
                <div class="h-1 bg-red-500"></div>
            </div>

            <!-- Average Rating -->
            <div class="bg-white rounded-2xl shadow-lg border border-gray-100 overflow-hidden group hover:shadow-xl transition-all duration-300"
                data-aos="fade-up" data-aos-delay="300">
                <div class="p-6">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm font-medium text-gray-600 font-lato mb-1">Average Rating</p>
                            <div class="flex items-center">
                                <p class="text-3xl font-bold font-montserrat" style="color: #00a651;">
                                    {{ $instructorStats['average_rating'] }}
                                </p>
                                <div class="flex ml-2">
                                    @for($i = 1; $i <= 5; $i++)
                                        <i data-lucide="star"
                                            class="w-4 h-4 {{ $i <= floor($instructorStats['average_rating']) ? 'text-yellow-400' : 'text-gray-300' }}"></i>
                                    @endfor
                                </div>
                            </div>
                            <p class="text-xs text-gray-500 mt-1 font-lato">Student Feedback</p>
                        </div>
                        <div
                            class="w-16 h-16 rounded-2xl bg-green-100 flex items-center justify-center group-hover:scale-110 transition-transform duration-300">
                            <i data-lucide="star" class="w-8 h-8" style="color: #00a651;"></i>
                        </div>
                    </div>
                </div>
                <div class="h-1 bg-green-500"></div>
            </div>

            <!-- Wallet Balance -->
            <div class="bg-white rounded-2xl shadow-lg border border-gray-100 overflow-hidden group hover:shadow-xl transition-all duration-300"
                data-aos="fade-up" data-aos-delay="400">
                <div class="p-6">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm font-medium text-gray-600 font-lato mb-1">Wallet Balance</p>
                            <p class="text-3xl font-bold font-montserrat" style="color: #00a651;">
                                ₦{{ number_format($instructorStats['total_earnings'], 2) }}</p>
                            <p class="text-xs text-gray-500 mt-1 font-lato">Course Earnings</p>
                        </div>
                        <div
                            class="w-16 h-16 rounded-2xl bg-green-100 flex items-center justify-center group-hover:scale-110 transition-transform duration-300">
                            <i data-lucide="wallet" class="w-8 h-8" style="color: #00a651;"></i>
                        </div>
                    </div>
                </div>
                <div class="h-1 bg-emerald-500"></div>
            </div>
        </div>
    </div>

    <!-- Enhanced Courses Section -->
    <div class="bg-white rounded-2xl shadow-xl border border-gray-100 overflow-hidden" data-aos="fade-up"
        data-aos-delay="500">
        <div class="p-6 border-b border-gray-100 bg-gray-50">
            <div class="flex items-center justify-between">
                <div class="flex items-center">
                    <div class="w-10 h-10 rounded-2xl bg-primary flex items-center justify-center mr-3">
                        <i data-lucide="list" class="w-5 h-5 text-white"></i>
                    </div>
                    <div>
                        <h3 class="text-xl font-bold font-montserrat" style="color: #002147;">All Courses</h3>
                        <p class="text-sm text-gray-600 font-lato">Manage and track your course portfolio</p>
                    </div>
                </div>
                <div class="text-sm text-gray-500 font-lato">
                    {{ $courses->count() }} course{{ $courses->count() !== 1 ? 's' : '' }}
                </div>
            </div>
        </div>

        <div class="p-6">
            @forelse ($courses as $course)
                <div class="mb-6 last:mb-0">
                    <div
                        class="bg-white border-2 border-gray-100 rounded-2xl p-6 hover:border-gray-200 hover:shadow-lg transition-all duration-300 group">
                        <div class="flex flex-col lg:flex-row lg:items-center gap-6">
                            <!-- Course Image -->
                            <div class="flex-shrink-0">
                                <img class="w-24 h-24 lg:w-20 lg:h-20 rounded-2xl object-cover shadow-lg group-hover:scale-105 transition-transform duration-300"
                                    src="{{ Storage::url($course->thumbnail_path) ?? 'https://via.placeholder.com/300x200/002147/ffffff?text=' . urlencode(substr($course->title, 0, 2)) }}"
                                    alt="{{ $course->title }}">
                            </div>

                            <!-- Course Details -->
                            <div class="flex-1 min-w-0">
                                <div class="flex flex-col lg:flex-row lg:items-start justify-between gap-4">
                                    <div class="flex-1">
                                        <h4 class="text-lg font-bold font-montserrat group-hover:text-blue-600 transition-colors"
                                            style="color: #002147;">
                                            {{ $course->title }}
                                        </h4>
                                        <p class="text-sm text-gray-600 font-lato mt-1">
                                            {{ $course->category->name }}
                                        </p>
                                        @if($course->description)
                                            <p class="text-sm text-gray-500 font-lato mt-2 line-clamp-2">
                                                {{ Str::limit(strip_tags($course->description), 120) }}
                                            </p>
                                        @endif
                                    </div>

                                    <!-- Course Stats -->
                                    <div class="flex flex-wrap gap-4 lg:flex-col lg:items-end">
                                        <!-- Status Badge -->
                                        <div class="flex items-center">
                                            @php
                                                $statusConfig = match ($course->status->value) {
                                                    'approved' => ['bg' => 'bg-green-100', 'text' => 'text-green-800', 'icon' => 'check-circle'],
                                                    'draft' => ['bg' => 'bg-yellow-100', 'text' => 'text-yellow-800', 'icon' => 'clock'],
                                                    'pending' => ['bg' => 'bg-blue-100', 'text' => 'text-blue-800', 'icon' => 'hourglass'],
                                                    'under_review' => ['bg' => 'bg-purple-100', 'text' => 'text-purple-800', 'icon' => 'search'],
                                                    'rejected' => ['bg' => 'bg-red-100', 'text' => 'text-red-800', 'icon' => 'x-circle'],
                                                    default => ['bg' => 'bg-gray-100', 'text' => 'text-gray-800', 'icon' => 'help-circle']
                                                };
                                            @endphp
                                            <span
                                                class="px-3 py-1 rounded-full text-xs font-semibold font-montserrat flex items-center {{ $statusConfig['bg'] }} {{ $statusConfig['text'] }}">
                                                <i data-lucide="{{ $statusConfig['icon'] }}" class="w-3 h-3 mr-1"></i>
                                                @php
                                                    $statusText = match ($course->status->value) {
                                                        'draft' => 'Draft',
                                                        'pending' => 'Pending Review',
                                                        'under_review' => 'Under Review',
                                                        'approved' => 'Approved',
                                                        'rejected' => 'Rejected',
                                                        default => ucfirst($course->status->value)
                                                    };
                                                @endphp
                                                {{ $statusText }}
                                            </span>
                                        </div>

                                        <!-- Students Count -->
                                        <div class="flex items-center text-sm text-gray-600">
                                            <i data-lucide="users" class="w-4 h-4 mr-2 text-blue-500"></i>
                                            <span class="font-semibold">{{ $course->students->count() }}</span>
                                            <span class="ml-1 font-lato">students</span>
                                        </div>

                                        <!-- Rating -->
                                        <div class="flex items-center text-sm text-gray-600">
                                            <i data-lucide="star" class="w-4 h-4 mr-2 text-yellow-500"></i>
                                            <span
                                                class="font-semibold">{{ number_format($course->average_rating, 1) }}</span>
                                            <span class="ml-1 font-lato">rating</span>
                                        </div>

                                        <!-- Price -->
                                        <div class="flex items-center text-sm text-gray-600">
                                            <i data-lucide="tag" class="w-4 h-4 mr-2 text-green-500"></i>
                                            <span class="font-semibold">
                                                {{ $course->price ? '₦' . number_format($course->price, 2) : 'Free' }}
                                            </span>
                                        </div>
                                    </div>
                                </div>

                                <!-- Action Buttons -->
                                <div class="flex flex-wrap gap-3 mt-4 pt-4 border-t border-gray-100">
                                    <!-- Edit Course - Always available for draft courses -->
                                    @if($course->status->value === 'draft')
                                        <a href="{{ route('instructor.courses.edit', $course) }}"
                                            class="inline-flex items-center px-4 py-2 border-2 border-gray-200 rounded-xl text-sm font-semibold font-montserrat hover:border-blue-300 hover:text-blue-600 transition-all duration-300"
                                            style="color: #002147;">
                                            <i data-lucide="edit" class="w-4 h-4 mr-2"></i>
                                            Edit Course
                                        </a>
                                    @endif

                                    <!-- Build Course - Available for draft and approved courses -->
                                    @if(in_array($course->status->value, ['draft', 'approved']))
                                        <a href="{{ route('instructor.courses.builder', $course) }}"
                                            class="inline-flex items-center px-4 py-2 border-2 rounded-xl text-sm font-semibold font-montserrat transition-all duration-300 hover:scale-105"
                                            style="border-color: #002147; color: #002147; background: rgba(0, 33, 71, 0.05);">
                                            <i data-lucide="hammer" class="w-4 h-4 mr-2"></i>
                                            Build Course
                                        </a>
                                    @endif

                                    <!-- Submit for Review - Only for draft courses -->
                                    @if($course->status->value === 'draft')
                                        <form method="POST"
                                            action="{{ route('instructor.courses.submit-for-review', $course) }}"
                                            class="inline-block">
                                            @csrf
                                            <button type="submit"
                                                class="inline-flex items-center px-4 py-2 border-2 rounded-xl text-sm font-semibold font-montserrat transition-all duration-300 hover:scale-105"
                                                style="border-color: #ed1c24; color: #ed1c24; background: rgba(237, 28, 36, 0.05);"
                                                onclick="return confirm('Are you sure you want to submit this course for review? You won\'t be able to edit it until it\'s reviewed.')">
                                                <i data-lucide="send" class="w-4 h-4 mr-2"></i>
                                                Submit for Review
                                            </button>
                                        </form>
                                    @endif

                                    <!-- View Course - Always available -->
                                    <a href="{{ route('instructor.courses.show', $course) }}"
                                        class="inline-flex items-center px-4 py-2 border-2 rounded-xl text-sm font-semibold font-montserrat transition-all duration-300 hover:scale-105"
                                        style="border-color: #00a651; color: #00a651; background: rgba(0, 166, 81, 0.05);">
                                        <i data-lucide="eye" class="w-4 h-4 mr-2"></i>
                                        View Course
                                    </a>

                                    <!-- Analytics - Available for approved/published courses -->
                                    @if(in_array($course->status->value, ['approved', 'published']))
                                        <a href="#"
                                            class="inline-flex items-center px-4 py-2 border-2 rounded-xl text-sm font-semibold font-montserrat transition-all duration-300 hover:scale-105"
                                            style="border-color: #00a651; color: #00a651; background: rgba(0, 166, 81, 0.05);">
                                            <i data-lucide="bar-chart" class="w-4 h-4 mr-2"></i>
                                            Analytics
                                        </a>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            @empty
                <!-- Enhanced Empty State -->
                <div class="text-center py-16">
                    <div class="w-32 h-32 rounded-3xl bg-blue-50 flex items-center justify-center mx-auto mb-6">
                        <i data-lucide="book-open" class="w-16 h-16" style="color: #002147; opacity: 0.6;"></i>
                    </div>
                    <h4 class="text-2xl font-bold font-montserrat mb-3" style="color: #002147;">No Courses Yet</h4>
                    <p class="text-gray-600 font-lato mb-8 max-w-md mx-auto leading-relaxed">
                        Ready to share your expertise? Create your first course and start building your online teaching
                        portfolio.
                    </p>
                    <a href="{{ route('instructor.courses.create') }}"
                        class="inline-flex items-center px-6 py-3 rounded-xl font-semibold font-montserrat bg-primary hover:bg-secondary text-white transition-colors duration-300 shadow-lg hover:shadow-xl">
                        <i data-lucide="plus" class="w-5 h-5 mr-2"></i>
                        Create Your First Course
                    </a>
                </div>
            @endforelse
        </div>
    </div>
    </div>

    <!-- AOS Animation Library -->
    <link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet">
    <script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>

    <style>
        .line-clamp-2 {
            display: -webkit-box;
            -webkit-line-clamp: 2;
            -webkit-box-orient: vertical;
            overflow: hidden;
        }
    </style>

    <script>
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
</x-app-layout>