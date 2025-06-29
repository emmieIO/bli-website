<x-guest-layout>
    <!-- Courses Listing Page -->
    <section class="py-16 bg-gray-50 min-h-screen">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-12">
                <h2 class="text-4xl font-bold text-teal-800">Our Courses</h2>
                <p class="text-gray-600 mt-2 max-w-2xl mx-auto">
                    Explore our range of transformative leadership programs designed for emerging and established
                    leaders.
                </p>
            </div>

            <div class="grid md:grid-cols-3 gap-8">
                <!-- Course Card 1 -->
                <div
                    class="bg-white border border-teal-100 rounded-xl overflow-hidden shadow hover:shadow-lg transition">
                    <img src="{{ asset('images/logo.jpg') }}" alt="Foundations of Leadership"
                        class="w-full h-40 object-cover">
                    <div class="p-5 space-y-3">
                        <div class="flex items-center gap-2 text-teal-600">
                            <i data-lucide="compass" class="w-5 h-5"></i>
                            <h3 class="text-lg font-semibold text-gray-800">Foundations of Leadership</h3>
                        </div>
                        <p class="text-sm text-gray-600">
                            Learn the core principles of faith-driven leadership and spiritual influence.
                        </p>
                        <div class="flex justify-between items-center">
                            <span class="text-xs bg-teal-100 text-teal-800 px-2 py-1 rounded-full">Beginner</span>
                            <a href="#"
                                class="inline-flex items-center text-teal-700 text-sm font-medium hover:underline">
                                View Details
                                <i data-lucide="arrow-right" class="w-4 h-4 ml-1"></i>
                            </a>
                        </div>
                    </div>
                </div>

                <!-- Course Card 2 -->
                <div
                    class="bg-white border border-teal-100 rounded-xl overflow-hidden shadow hover:shadow-lg transition">
                    <img src="{{ asset('images/logo.jpg') }}" alt="Marketplace Leadership"
                        class="w-full h-40 object-cover">
                    <div class="p-5 space-y-3">
                        <div class="flex items-center gap-2 text-teal-600">
                            <i data-lucide="briefcase" class="w-5 h-5"></i>
                            <h3 class="text-lg font-semibold text-gray-800">Marketplace Leadership</h3>
                        </div>
                        <p class="text-sm text-gray-600">
                            Practical training to help you lead with excellence in business and society.
                        </p>
                        <div class="flex justify-between items-center">
                            <span class="text-xs bg-teal-100 text-teal-800 px-2 py-1 rounded-full">Intermediate</span>
                            <a href="#"
                                class="inline-flex items-center text-teal-700 text-sm font-medium hover:underline">
                                View Details
                                <i data-lucide="arrow-right" class="w-4 h-4 ml-1"></i>
                            </a>
                        </div>
                    </div>
                </div>

                <!-- Course Card 3 -->
                <div
                    class="bg-white border border-teal-100 rounded-xl overflow-hidden shadow hover:shadow-lg transition">
                    <img src="{{ asset('images/logo.jpg') }}" alt="Vision Clarity Bootcamp"
                        class="w-full h-40 object-cover">
                    <div class="p-5 space-y-3">
                        <div class="flex items-center gap-2 text-teal-600">
                            <i data-lucide="eye" class="w-5 h-5"></i>
                            <h3 class="text-lg font-semibold text-gray-800">Vision Clarity Bootcamp</h3>
                        </div>
                        <p class="text-sm text-gray-600">
                            Gain spiritual focus and actionable vision to lead in your God-given calling.
                        </p>
                        <div class="flex justify-between items-center">
                            <span class="text-xs bg-teal-100 text-teal-800 px-2 py-1 rounded-full">Advanced</span>
                            <a href="#"
                                class="inline-flex items-center text-teal-700 text-sm font-medium hover:underline">
                                View Details
                                <i data-lucide="arrow-right" class="w-4 h-4 ml-1"></i>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</x-guest-layout>
