<x-guest-layout>
    <!-- Hero Section -->
    <section class="py-16 bg-gradient-to-br from-gray-50 to-white" data-aos="fade-down" data-aos-duration="900">
        <div class="container mx-auto px-6">
            <div class="text-center max-w-4xl mx-auto">
                <!-- Badge -->
                <div class="inline-flex items-center gap-2 border rounded-full px-6 py-3 mb-8"
                    style="background-color: rgba(0, 166, 81, 0.1); border-color: #00a651;">
                    <i class="fas fa-graduation-cap text-sm" style="color: #00a651;"></i>
                    <span class="font-medium font-montserrat text-sm tracking-wide" style="color: #002147;">
                        Professional Development
                    </span>
                </div>

                <!-- Main Heading -->
                <h1 class="font-bold font-montserrat text-4xl md:text-5xl lg:text-6xl mb-6 leading-tight"
                    style="color: #002147;">
                    Explore Our
                    <span style="color: #00a651;">Courses</span>
                </h1>

                <p class="text-xl text-gray-600 mb-8 max-w-3xl mx-auto leading-relaxed font-lato">
                    Browse our curated selection of courses designed to upskill, learn new technologies, and advance
                    your leadership career. New courses added regularly.
                </p>

                <!-- Search and Filter Section -->
                <div class="bg-white rounded-2xl shadow-lg p-6 border border-gray-100 max-w-2xl mx-auto">
                    <div class="flex flex-col md:flex-row gap-4">
                        <div class="flex-1 relative">
                            <i
                                class="fas fa-search absolute left-4 top-1/2 transform -translate-y-1/2 text-gray-400"></i>
                            <input type="text" placeholder="Search courses..."
                                class="w-full pl-12 pr-4 py-3 rounded-xl border border-gray-200 focus:outline-none focus:ring-2 focus:border-transparent font-lato"
                                style="focus:ring-color: #00a651;">
                        </div>
                        <select
                            class="px-4 py-3 rounded-xl border border-gray-200 focus:outline-none focus:ring-2 focus:border-transparent font-lato md:w-48"
                            style="focus:ring-color: #00a651;">
                            <option>All Categories</option>
                            <option>Leadership</option>
                            <option>Business</option>
                            <option>Technology</option>
                        </select>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Courses Grid Section -->
    <section class="py-20 bg-white">
        <div class="container mx-auto px-6">
            @if($courses->count())
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-8">
                    @foreach ($courses as $course)
                        <div class="group bg-white rounded-2xl shadow-lg hover:shadow-2xl transition-all duration-500 overflow-hidden border border-gray-100 hover:border-accent/20"
                            data-aos="fade-up" data-aos-delay="{{ $loop->index * 100 }}">

                            <!-- Course Image -->
                            <div class="relative h-48 overflow-hidden">
                                <img class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-500"
                                    src="{{ asset('storage/' . $course->thumbnail_path) }}" alt="{{ $course->title }}">

                                <!-- Category Badge -->
                                <div class="absolute top-4 left-4">
                                    <span class="px-3 py-1.5 text-xs font-semibold rounded-full text-white font-montserrat"
                                        style="background-color: #00a651;">
                                        {{ $course->category->name }}
                                    </span>
                                </div>

                                <!-- Price Badge -->
                                <div class="absolute top-4 right-4">
                                    <span class="px-3 py-1.5 text-sm font-bold rounded-full bg-white shadow-md font-montserrat"
                                        style="color: #002147;">
                                        ₦{{ number_format($course->price, 0) }}
                                    </span>
                                </div>
                            </div>

                            <!-- Course Content -->
                            <div class="p-6">
                                <!-- Course Title -->
                                <h3 class="text-xl font-bold mb-3 font-montserrat line-clamp-2 group-hover:text-accent transition-colors duration-300"
                                    style="color: #002147;">
                                    <a href="{{ route('courses.show', $course) }}">{{ $course->title }}</a>
                                </h3>

                                <!-- Course Stats -->
                                <div class="flex items-center justify-between mb-4 text-sm text-gray-600">
                                    <div class="flex items-center gap-1">
                                        <i class="fas fa-star text-yellow-400"></i>
                                        <span class="font-semibold">4.8</span>
                                        <span class="text-gray-500">(1.2k)</span>
                                    </div>
                                    <div class="flex items-center gap-1">
                                        <i class="fas fa-users text-gray-400"></i>
                                        <span>12.5k students</span>
                                    </div>
                                </div>

                                <!-- Instructor -->
                                <div class="flex items-center gap-3 mb-4">
                                    <div class="w-8 h-8 rounded-full overflow-hidden">
                                        <img src="{{ $course->instructor->avatar_url ?? 'https://ui-avatars.com/api/?name=' . urlencode($course->instructor->name ?? 'Instructor') . '&background=00a651&color=fff&size=32' }}"
                                            alt="Instructor" class="w-full h-full object-cover">
                                    </div>
                                    <span
                                        class="text-sm text-gray-600 font-lato">{{ $course->instructor->name ?? 'BLI Instructor' }}</span>
                                </div>

                                <!-- Enroll Button -->
                                <a href="{{ route('courses.show', $course) }}"
                                    class="w-full flex items-center justify-center gap-2 font-semibold py-3 px-4 rounded-xl transition-all duration-300 shadow-md hover:shadow-lg transform hover:scale-105 text-white font-montserrat"
                                    style="background-color: #00a651;" onmouseover="this.style.backgroundColor='#15803d'"
                                    onmouseout="this.style.backgroundColor='#00a651'">
                                    <span>Enroll Now</span>
                                    <i class="fas fa-arrow-right text-sm group-hover:translate-x-1 transition-transform"></i>
                                </a>
                            </div>
                        </div>
                    @endforeach
                </div>

                <!-- Load More Section -->
                <div class="text-center mt-16">
                    <button
                        class="font-semibold px-8 py-3 rounded-xl border-2 transition-all duration-300 hover:shadow-lg transform hover:scale-105 font-montserrat"
                        style="color: #002147; border-color: #002147;"
                        onmouseover="this.style.backgroundColor='#002147'; this.style.color='white';"
                        onmouseout="this.style.backgroundColor='transparent'; this.style.color='#002147';">
                        Load More Courses
                    </button>
                </div>
            @else
                <!-- No Courses State -->
                <div class="text-center py-20">
                    <div class="mb-8">
                        <i class="fas fa-graduation-cap text-6xl text-gray-300"></i>
                    </div>
                    <h3 class="text-2xl font-bold mb-4 font-montserrat" style="color: #002147;">No Courses Available</h3>
                    <p class="text-gray-600 font-lato mb-8">We're working hard to bring you amazing courses. Check back
                        soon!</p>
                    <a href="{{ route('homepage') }}"
                        class="inline-flex items-center gap-2 font-semibold px-6 py-3 rounded-xl transition-all duration-300 shadow-md hover:shadow-lg text-white font-montserrat"
                        style="background-color: #00a651;">
                        <i class="fas fa-home"></i>
                        <span>Back to Home</span>
                    </a>
                </div>
            @endif
        </div>
    </section>
</x-guest-layout>