<x-guest-layout>
    <!-- Banner Section -->
    <section class="relative flex items-center justify-center h-[70vh] mb-20 bg-cover bg-center"
        style="background-image: url({{ asset('images/learning-platform/banner.png') }}); 
    background-position: center 10px;
    background-repeat:no-repeat;
    ">
        <div class="absolute inset-0 bg-gradient-to-r from-orange-900/70 via-orange-800/60 to-orange-700/40"></div>
        <div class="relative z-10 w-full max-w-3xl px-6 text-center">
            <h6 class="text-lg md:text-xl font-semibold text-orange-100 mb-6 tracking-wide uppercase">Empowering Leaders
                for Influence & Impact</h6>
            <h1 class="font-extrabold text-3xl md:text-5xl text-white mb-8 leading-tight drop-shadow-lg">
                Developing visionary leaders to drive positive change in organizations and communities.
            </h1>
            <form class="flex flex-col md:flex-row items-center gap-4 md:gap-6 justify-center w-full mt-6">
                <div class="relative w-full md:w-56">
                    <button type="button" id="categoriesBtn"
                        class="flex items-center gap-2 w-full bg-white/90 px-4 py-3 rounded-lg shadow hover:bg-orange-50 transition font-medium text-gray-800 justify-center">
                        <i class="fas fa-bars text-xl"></i>
                        <span>Categories</span>
                        <i class="fas fa-chevron-down ml-2 text-sm"></i>
                    </button>
                    <div id="categoriesPopup"
                        class="absolute left-0 top-full mt-2 w-68 bg-white shadow-xl rounded-lg z-50 overflow-hidden hidden">
                        <div class="p-4 border-b">
                            <h6 class="text-base font-semibold mb-1">Course Categories</h6>
                            <p class="text-xs text-gray-500">Pick a subject and start learning</p>
                        </div>
                        <ul class="p-2 space-y-2">
                            @foreach ($categories as $category)
                                <li>
                                    <a href="courses-list.html"
                                        class="flex items-center gap-3 p-3 rounded-lg hover:bg-orange-50 transition">
                                        <img alt="{{ $category->name }} icon"
                                            src="{{ asset('storage/' . $category->image) }}" class="w-8 h-8">
                                        <span class="font-medium">{{ Str::ucfirst($category->name) }}</span>
                                        <span
                                            class="ml-auto text-xs text-gray-400 whitespace-nowrap">{{ $category->courses->count() }}
                                            Courses</span>
                                    </a>
                                </li>
                            @endforeach
                            <li>
                                <a href="{{ route('courses.index') }}"
                                    class="flex items-center gap-3 p-3 rounded-lg hover:bg-orange-50 transition font-semibold text-orange-600">
                                    <i class="fas fa-th-large text-xl"></i>
                                    <span class="font-medium">All Categories</span>
                                </a>
                            </li>
                        </ul>
                    </div>
                </div>
                <div class="relative flex-grow w-full max-w-md">
                    <input type="text" placeholder="What do you want to learn?" aria-label="Search courses"
                        class="w-full px-5 py-3 pl-12 rounded-lg border border-gray-300 focus:outline-none focus:ring-2 focus:ring-orange-500 transition-all duration-300 bg-white/90 text-gray-800 shadow">
                </div>
                <button type="submit"
                    class="bg-orange-600 hover:bg-orange-700 text-white font-semibold px-6 py-3 rounded-lg shadow transition-all duration-300 w-full md:w-auto">
                    Search
                </button>
            </form>
        </div>
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                const btn = document.getElementById('categoriesBtn');
                const popup = document.getElementById('categoriesPopup');
                document.addEventListener('click', function(e) {
                    if (btn.contains(e.target)) {
                        popup.classList.toggle('hidden');
                    } else if (!popup.contains(e.target)) {
                        popup.classList.add('hidden');
                    }
                });
            });
        </script>
    </section>


    <!-- CTA: View All Categories Section -->
    <section class="mb-20">
        <div class="container mx-auto px-4 flex flex-col items-center justify-center">
            <h2 class="font-bold text-3xl text-center mb-6">Explore All Course Categories</h2>
            <p class="text-lg text-gray-600 text-center mb-10 max-w-2xl">
                Discover a wide range of categories to find the perfect course for your learning journey. Browse all
                categories and start learning today!
            </p>
            <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-5 gap-5">
                @if ($categories->count())
                    @foreach ($categories as $category)
                        <a href="courses-list.html"
                            class="bg-white rounded-lg shadow-sm p-8 text-center transition-all duration-300 hover:shadow-md hover:transform hover:-translate-y-1">
                            <img src="{{ asset('storage/' . $category->image) }}" alt="occupations icon"
                                class="mx-auto mb-5 w-16 h-16">
                            <h5 class="font-bold text-xl mb-4">{{ Str::ucfirst($category->name) }}</h5>
                            <p class="font-medium">{{ $category->courses->count() }} Courses</p>
                        </a>
                    @endforeach
                @endif

            </div>
            <a href="#"
                class="bg-orange-600 my-5 hover:bg-orange-700 text-white font-semibold py-4 px-10 rounded-lg shadow transition-all duration-300 text-sm">
                View All Categories
            </a>
        </div>
    </section>

    <!-- Skills Section -->
    <section class="py-20 bg-gray-50">
        <div class="container mx-auto px-4">
            <h2 class="font-bold text-3xl text-center mb-10">Courses Focused On Building Strong Foundational Skills For
                Career Growth</h2>
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-7">
                <div class="text-center">
                    <div class="mb-5">
                        <img alt="Industry Expert" src="../images/learning-platform/skills-01.png"
                            class="mx-auto w-20 h-20">
                    </div>
                    <h5 class="font-bold text-xl mb-4">Industry Expert</h5>
                    <p class="text-lg text-gray-600">Comprehensive self-paced courses created with top practitioners</p>
                </div>
                <div class="text-center">
                    <div class="mb-5">
                        <img alt="Free Resources" src="../images/learning-platform/skills-02.png"
                            class="mx-auto w-20 h-20">
                    </div>
                    <h5 class="font-bold text-xl mb-4">Free Resources</h5>
                    <p class="text-lg text-gray-600">Free guides on career paths, salaries, interview tips, and more</p>
                </div>
                <div class="text-center">
                    <div class="mb-5">
                        <img alt="Skill-based Learning" src="../images/learning-platform/skills-03.png"
                            class="mx-auto w-20 h-20">
                    </div>
                    <h5 class="font-bold text-xl mb-4">Skill-based Learning</h5>
                    <p class="text-lg text-gray-600">600+ job-ready skills on offer in today's most in-demand domains
                    </p>
                </div>
                <div class="text-center">
                    <div class="mb-5">
                        <img alt="Anytime, Anywhere" src="../images/learning-platform/skills-04.png"
                            class="mx-auto w-20 h-20">
                    </div>
                    <h5 class="font-bold text-xl mb-4">Anytime, Anywhere</h5>
                    <p class="text-lg text-gray-600">Learn while working or studying from any place, across any device
                    </p>
                </div>
            </div>
        </div>
    </section>

    <!-- Upcoming Events Section -->
    <section class="my-20">
        <div class="container mx-auto px-4">
            <div class="flex flex-wrap items-center justify-between mb-10">
                <div class="mb-5 md:mb-0">
                    <h2 class="font-bold text-3xl"><span class="text-orange-600">Upcoming Events</span></h2>
                </div>
                <p>
                    <a href="{{ route('events.index') }}"
                        class="font-semibold text-orange-600 transition-colors hover:text-orange-800">View all
                        Events</a>
                </p>
            </div>
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-5">
                @if ($events)
                    @foreach ($events as $event)
                        <article
                            class="bg-white rounded-lg shadow-sm overflow-hidden transition-all duration-300 hover:shadow-md">
                            <div class="relative">
                                <figure>
                                    <a href="{{ route('events.show', $event->slug) }}">
                                        <img alt="" src="{{ asset('storage/' . $event->program_cover) }}"
                                            class="w-full h-48 object-cover">
                                        <figcaption
                                            class="absolute top-3 left-3 bg-orange-600 text-white text-sm px-2 py-1 rounded font-medium">
                                            {{ sweet_date($event->start_date) }}
                                        </figcaption>
                                    </a>
                                </figure>
                            </div>
                            <div class="p-5">
                                <h6 class="font-semibold text-lg mb-4">
                                    <a href="{{ route('events.show', $event->slug) }}"
                                        class="transition-colors hover:text-orange-600">
                                        {{ $event->title }}
                                    </a>
                                </h6>
                                <p class="line-clamp-2 mb-3 text-gray-600">
                                    {{ $event->description }}
                                </p>
                                <div class="flex items-center gap-3 mb-3">
                                    <div class="flex items-center gap-1 text-sm text-gray-600">
                                        <i class="far fa-sun text-lg"></i>
                                        <span>{{ $event->mode }}</span>
                                    </div>
                                    <a href="{{ route('events.show', $event->slug) }}"
                                        class="font-semibold text-orange-600 transition-colors hover:text-orange-800 ml-auto">Read
                                        More</a>
                                </div>
                            </div>
                        </article>
                    @endforeach
                @endif
            </div>
        </div>
    </section>

    <!-- Partner Section -->
    <section class="py-20 bg-gray-50">
        <div class="container mx-auto px-4">
            <h2 class="font-bold text-3xl text-center mb-10">More Than 1,300 Customers Trust EDUMA</h2>
            <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-6 gap-7 justify-items-center">
                <div class="text-center md:text-left">
                    <img src="{{ asset('images/partner/demo-partner-01.png') }}" alt="partner"
                        class="mx-auto md:mx-0">
                </div>
                <div class="text-center md:text-left">
                    <img src="{{ asset('images/partner/demo-partner-02.png') }}" alt="partner"
                        class="mx-auto md:mx-0">
                </div>
                <div class="text-center md:text-left">
                    <img src="{{ asset('images/partner/demo-partner-03.png') }}" alt="partner"
                        class="mx-auto md:mx-0">
                </div>
                <div class="text-center md:text-left">
                    <img src="{{ asset('images/partner/demo-partner-04.png') }}" alt="partner"
                        class="mx-auto md:mx-0">
                </div>
                <div class="text-center md:text-left">
                    <img src="{{ asset('images/partner/demo-partner-05.png') }}" alt="partner"
                        class="mx-auto md:mx-0">
                </div>
                <div class="text-center md:text-left">
                    <img src="{{ asset('images/partner/demo-partner-06.png') }}" alt="partner"
                        class="mx-auto md:mx-0">
                </div>
            </div>
        </div>
    </section>

    <!-- Reviews Section -->
    <section class="py-20">
        <div class="container mx-auto px-4">
            <h2 class="font-bold text-3xl text-center mb-10">Why Students Love US?</h2>
            <div class="relative">
                <div class="swiper-container overflow-hidden">
                    <div class="swiper-wrapper flex">
                        <div class="swiper-slide flex-shrink-0 w-full md:w-1/2">
                            <div class="bg-white rounded-lg shadow-sm p-10 mx-2">
                                <i class="fas fa-quote-left text-5xl text-orange-600 mb-5 block text-center"></i>
                                <p class="mb-5 text-center text-gray-700">" I have an understanding that, even if the
                                    work is not perfect, it's a work in progress. And the reason why I'm on Skillshare
                                    is to develop a skill. I feel that it's a safe space. "</p>
                                <div class="flex items-center justify-center gap-4">
                                    <img src="{{ asset('images/learning-platform/reviews-01.png') }}" alt="reviewer"
                                        class="w-12 h-12 rounded-full">
                                    <div>
                                        <h6 class="font-semibold">DeVeor R</h6>
                                        <p class="text-gray-600">Business course</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- Additional review slides would follow the same pattern -->
                    </div>
                </div>
                <div class="hidden md:flex">
                    <div
                        class="absolute right-5 top-1/2 transform -translate-y-1/2 bg-white rounded-full w-10 h-10 flex items-center justify-center shadow-md cursor-pointer transition-all duration-300 hover:bg-orange-600 hover:text-white">
                        <i class="fas fa-chevron-right"></i>
                    </div>
                    <div
                        class="absolute left-5 top-1/2 transform -translate-y-1/2 bg-white rounded-full w-10 h-10 flex items-center justify-center shadow-md cursor-pointer transition-all duration-300 hover:bg-orange-600 hover:text-white">
                        <i class="fas fa-chevron-left"></i>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Learning Section -->
    <section class="bg-gray-50">
        <div class="container mx-auto px-4">
            <div class="flex flex-col md:flex-row items-center">
                <div class="md:w-1/2 py-12">
                    <h2 class="font-bold text-3xl mb-5">Online Learning Now In Your Fingertips</h2>
                    <p class="mb-10 text-gray-700">
                        Lorem Ipsum is simply dummy text of the printing and typesetting industry.
                        Lorem Ipsum has been the industry's standard dummy text ever since the 1500s, ...
                    </p>
                    <!-- App store buttons would go here -->
                </div>
                <div class="md:w-1/2 flex justify-center pt-10 md:pt-20">
                    <img src="{{ asset('images/logo.jpg') }}" alt="Learning platform" class="max-w-full h-auto">
                </div>
            </div>
        </div>
    </section>
</x-guest-layout>
