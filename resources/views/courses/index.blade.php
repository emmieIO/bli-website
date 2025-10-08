<x-guest-layout>
    <!-- Breadcrumb Navigation -->
    <section class="py-5 border-b border-gray-200">
        <div class="container mx-auto px-4">
            <nav class="breadcrumb">
                <ul class="flex items-center space-x-2 text-sm text-gray-600">
                    <li class="inline-flex items-center">
                        <a href="{{ route('homepage') }}"
                            class="text-primary hover:text-primary-600 transition-colors">Home</a>
                    </li>
                    <li class="inline-flex items-center">
                        <i data-lucide="chevron-right" class="w-4 h-4 mx-1 text-gray-400"></i>
                    </li>
                    <li class="inline-flex items-center">
                        <a href="{{ route('courses.index') }}"
                            class="text-primary hover:text-primary-600 transition-colors">Courses</a>
                    </li>
                </ul>
            </nav>
        </div>
    </section>

    <!-- Page Header -->
    <div class="container mx-auto px-4 mt-10">
        <div class="w-full">
            <h1 class="text-4xl font-bold text-gray-900 mb-4">Courses</h1>
        </div>
    </div>

    <!-- Courses Section -->
    <section class="py-12 bg-gray-50">
        <div class="container mx-auto px-4">
            <!-- Introduction Text -->
            <div class="mb-8 max-w-4xl">
                <p class="text-lg text-gray-600 leading-relaxed">
                    Unlock your potential with our curated online course marketplace. Explore a wide range of expert-led
                    courses designed to help you achieve your personal and professional goals. Whether you're looking to
                    advance your career or pursue a new passion, our diverse selection offers something for every
                    learner.
                </p>
            </div>

            <!-- Filter and Sort Section -->
            <div class="mb-8">
                <div class="flex flex-col lg:flex-row justify-between items-start lg:items-center gap-6 mb-8">
                    <!-- Filter Button and Panel -->
                    <div class="relative">
                        <button id="filterToggle"
                            class="bg-secondary hover:bg-secondary-600 text-white font-semibold px-6 py-3 rounded-lg flex items-center gap-2 transition-colors">
                            <i data-lucide="filter" class="w-5 h-5"></i>
                            <span>Filter</span>
                        </button>

                        <!-- Filter Panel -->
                        <div id="filterPanel"
                            class="absolute top-full left-0 mt-2 bg-white rounded-lg shadow-xl border border-gray-200 p-6 min-w-80 z-50 hidden">
                            <!-- Categories -->
                            <div class="mb-6">
                                <div class="flex justify-between items-center mb-4">
                                    <h6 class="font-semibold text-gray-900">Categories</h6>
                                </div>
                                <div class="space-y-3">
                                    {{-- @foreach ($categories as $category)
                                        <label
                                            class="flex items-center justify-between cursor-pointer hover:bg-gray-50 p-2 rounded">
                                            <span class="text-gray-700">{{ $category->name }}</span>
                                            <input type="checkbox"
                                                class="w-4 h-4 text-primary rounded focus:ring-primary">
                                        </label>
                                    @endforeach --}}
                                </div>
                            </div>

                            <!-- Author -->
                            <div class="mb-6">
                                <div class="flex justify-between items-center mb-4">
                                    <h6 class="font-semibold text-gray-900">Author</h6>
                                </div>
                                <div class="space-y-3">
                                    <label
                                        class="flex items-center justify-between cursor-pointer hover:bg-gray-50 p-2 rounded">
                                        <span class="text-gray-700">Keny White</span>
                                        <input type="checkbox"
                                            class="w-4 h-4 text-primary rounded focus:ring-primary">
                                    </label>
                                </div>
                            </div>

                            <!-- Price -->
                            <div class="mb-6">
                                <div class="flex justify-between items-center mb-4">
                                    <h6 class="font-semibold text-gray-900">Price</h6>
                                </div>
                                <div class="space-y-3">
                                    <label
                                        class="flex items-center justify-between cursor-pointer hover:bg-gray-50 p-2 rounded">
                                        <span class="text-gray-700">Free</span>
                                        <input type="checkbox"
                                            class="w-4 h-4 text-primary rounded focus:ring-primary">
                                    </label>
                                    <label
                                        class="flex items-center justify-between cursor-pointer hover:bg-gray-50 p-2 rounded">
                                        <span class="text-gray-700">Paid</span>
                                        <input type="checkbox"
                                            class="w-4 h-4 text-primary rounded focus:ring-primary">
                                    </label>
                                </div>
                            </div>

                            <!-- Filter Actions -->
                            <div class="flex justify-between items-center gap-4 pt-4 border-t border-gray-200">
                                <button class="text-primary hover:text-primary-600 font-semibold transition-colors">
                                    Reset
                                </button>
                                <button
                                    class="bg-secondary hover:bg-secondary-600 text-white font-semibold px-6 py-2 rounded-lg transition-colors">
                                    Apply Filters
                                </button>
                            </div>
                        </div>
                    </div>

                    <!-- Results and Sort -->
                    <div class="flex flex-col sm:flex-row items-start sm:items-center gap-4">
                        <p class="text-gray-600">Showing {{ $courses->firstItem() }} - {{ $courses->lastItem() }} of
                            {{ $courses->total() }} results</p>

                        <!-- Sort Dropdown -->
                        <div class="relative">
                            <select
                                class="appearance-none bg-white border border-gray-300 rounded-lg px-4 py-2 pr-8 focus:ring-2 focus:ring-primary focus:border-primary cursor-pointer">
                                <option value="">Newly published</option>
                                <option value="x">Title a-z</option>
                                <option value="xx">Title z-a</option>
                                <option value="xxx">Price high to low</option>
                                <option value="xxxx">Price low to high</option>
                                <option value="xxxxx">Popular</option>
                                <option value="xxxxxx">Average Ratings</option>
                            </select>
                            <i data-lucide="chevron-down"
                                class="absolute right-2 top-1/2 transform -translate-y-1/2 w-4 h-4 text-gray-400 pointer-events-none"></i>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Courses Grid -->
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
                @if ($courses->count())
                    @foreach ($courses as $course)
                        <article
                            class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden hover:shadow-lg transition-all duration-300">
                            <!-- Course Image -->
                            <div class="relative">
                                <a href="{{ route('courses.show', $course) }}">
                                    <img src="{{ asset('storage/' . $course->thumbnail_path) }}"
                                        alt="{{ $course->title }}" class="w-full h-48 object-cover">
                                </a>
                                <div class="absolute top-3 left-3">
                                    <span class="bg-primary text-white text-xs px-2 py-1 rounded">
                                        {{ $course->category->name }}
                                    </span>
                                </div>
                            </div>

                            <!-- Course Content -->
                            <div class="p-4">
                                <h6 class="font-semibold text-gray-900 mb-2 line-clamp-2 leading-tight">
                                    <a href="{{ route('courses.show', $course) }}"
                                        class="hover:text-primary transition-colors">
                                        {{ $course->title }}
                                    </a>
                                </h6>

                                <!-- Course Stats -->
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

                                <!-- Course Description -->
                                <p class="text-gray-600 text-sm mb-4 line-clamp-2 leading-relaxed">
                                    {{ $course->description }}
                                </p>

                                <!-- Price and CTA -->
                                <div class="flex items-center justify-between">
                                    <div class="flex items-center gap-2">
                                        <span class="text-gray-400 text-sm line-through">$39.00</span>
                                        <span class="font-semibold text-gray-900">$69.00</span>
                                    </div>
                                    <a href="{{ route('courses.show', $course) }}"
                                        class="bg-secondary hover:bg-secondary-600 text-white text-sm font-semibold px-4 py-2 rounded-lg transition-colors">
                                        Start Learning
                                    </a>
                                </div>
                            </div>
                        </article>
                    @endforeach
                @else
                    <div class="col-span-full text-center py-12">
                        <i data-lucide="book-open" class="w-16 h-16 text-gray-400 mx-auto mb-4"></i>
                        <h3 class="text-xl font-semibold text-gray-900 mb-2">No courses found</h3>
                        <p class="text-gray-600">Check back later for new courses.</p>
                    </div>
                @endif
            </div>

            <!-- Pagination -->
            @if ($courses->hasPages())
                <div class="mt-12 flex justify-center">
                    <div class="flex items-center gap-2">
                        <!-- Previous Page -->
                        @if ($courses->onFirstPage())
                            <span class="flex items-center justify-center w-10 h-10 text-gray-400 cursor-not-allowed">
                                <i data-lucide="chevron-left" class="w-5 h-5"></i>
                            </span>
                        @else
                            <a href="{{ $courses->previousPageUrl() }}"
                                class="flex items-center justify-center w-10 h-10 text-gray-600 hover:text-primary transition-colors">
                                <i data-lucide="chevron-left" class="w-5 h-5"></i>
                            </a>
                        @endif

                        <!-- Page Numbers -->
                        @foreach (range(1, min(5, $courses->lastPage())) as $page)
                            <a href="{{ $courses->url($page) }}"
                                class="flex items-center justify-center w-10 h-10 rounded-lg font-semibold transition-colors
                              {{ $courses->currentPage() == $page ? 'bg-primary text-white' : 'text-gray-600 hover:text-primary' }}">
                                {{ $page }}
                            </a>
                        @endforeach

                        <!-- Next Page -->
                        @if ($courses->hasMorePages())
                            <a href="{{ $courses->nextPageUrl() }}"
                                class="flex items-center justify-center w-10 h-10 text-gray-600 hover:text-primary transition-colors">
                                <i data-lucide="chevron-right" class="w-5 h-5"></i>
                            </a>
                        @else
                            <span class="flex items-center justify-center w-10 h-10 text-gray-400 cursor-not-allowed">
                                <i data-lucide="chevron-right" class="w-5 h-5"></i>
                            </span>
                        @endif
                    </div>
                </div>
            @endif
        </div>
    </section>

    <script>
        // Initialize Lucide icons
        document.addEventListener('DOMContentLoaded', function() {
            if (typeof lucide !== 'undefined') {
                lucide.createIcons();
            }

            // Filter toggle functionality
            const filterToggle = document.getElementById('filterToggle');
            const filterPanel = document.getElementById('filterPanel');

            if (filterToggle && filterPanel) {
                filterToggle.addEventListener('click', function(e) {
                    e.stopPropagation();
                    filterPanel.classList.toggle('hidden');
                });

                // Close filter panel when clicking outside
                document.addEventListener('click', function(e) {
                    if (!filterPanel.contains(e.target) && !filterToggle.contains(e.target)) {
                        filterPanel.classList.add('hidden');
                    }
                });
            }

            // Close filter panel when clicking on filter button again
            filterPanel.addEventListener('click', function(e) {
                e.stopPropagation();
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
            
            select {
                background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' fill='none' viewBox='0 0 20 20'%3e%3cpath stroke='%236b7280' stroke-linecap='round' stroke-linejoin='round' stroke-width='1.5' d='m6 8 4 4 4-4'/%3e%3c/svg%3e");
                background-position: right 0.5rem center;
                background-repeat: no-repeat;
                background-size: 1.5em 1.5em;
                padding-right: 2.5rem;
                -webkit-print-color-adjust: exact;
                print-color-adjust: exact;
            }
        `;
        document.head.appendChild(style);
    </script>
</x-guest-layout>