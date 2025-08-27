<x-guest-layout>
    <!-- Hero Section -->
    <section class="relative bg-white py-10 sm:py-16 text-center border-b border-[#FF0000]/20">
        <div class="max-w-3xl mx-auto px-4 sm:px-6">
            <h1
                class="text-2xl sm:text-4xl font-semibold mb-2 sm:mb-3 flex items-center justify-center gap-2 text-[#00275E]">
                <i data-lucide="books" class="w-6 sm:w-7 h-6 sm:h-7 text-[#FF0000]"></i>
                <span class="leading-tight">Explore Our Courses</span>
            </h1>
            <p class="text-sm sm:text-lg text-gray-600">
                Discover courses to empower your leadership, faith, and personal growth.
            </p>
        </div>
    </section>

    <!-- Courses Listing -->
    <section class="py-16 bg-gray-50 min-h-screen">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid md:grid-cols-3 gap-8">
                <!-- Course Card 1 -->
                <div
                    class="bg-white border border-[#FF0000]/20 rounded-xl overflow-hidden shadow-md hover:shadow-xl hover:scale-[1.02] transition-transform duration-300">
                    <img src="{{ asset('images/logo.jpg') }}" alt="Foundations of Leadership"
                        class="w-full h-40 object-cover">
                    <div class="p-5 space-y-3">
                        <div class="flex items-center gap-2 text-[#FF0000]">
                            <i data-lucide="compass" class="w-5 h-5"></i>
                            <h3 class="text-lg font-semibold text-[#00275E]">Foundations of Leadership</h3>
                        </div>
                        <p class="text-sm text-gray-600">
                            Learn the core principles of faith-driven leadership and spiritual influence.
                        </p>
                        <div class="flex justify-between items-center">
                            <span class="text-xs bg-[#FF0000]/20 text-[#00275E] px-2 py-1 rounded-full">Beginner</span>
                            <span class="text-sm font-medium text-[#00275E]">Free</span>
                        </div>
                        <div class="pt-2">
                            <a href="#"
                                class="inline-flex items-center text-[#FF0000] text-sm font-medium hover:underline">
                                View Details
                                <i data-lucide="arrow-right" class="w-4 h-4 ml-1"></i>
                            </a>
                        </div>
                    </div>
                </div>

                <!-- Course Card 2 -->
                <div
                    class="bg-white border border-[#FF0000]/20 rounded-xl overflow-hidden shadow-md hover:shadow-xl hover:scale-[1.02] transition-transform duration-300">
                    <img src="{{ asset('images/logo.jpg') }}" alt="Marketplace Leadership"
                        class="w-full h-40 object-cover">
                    <div class="p-5 space-y-3">
                        <div class="flex items-center gap-2 text-[#FF0000]">
                            <i data-lucide="briefcase" class="w-5 h-5"></i>
                            <h3 class="text-lg font-semibold text-[#00275E]">Marketplace Leadership</h3>
                        </div>
                        <p class="text-sm text-gray-600">
                            Practical training to help you lead with excellence in business and society.
                        </p>
                        <div class="flex justify-between items-center">
                            <span
                                class="text-xs bg-[#FF0000]/20 text-[#00275E] px-2 py-1 rounded-full">Intermediate</span>
                            <span class="text-sm font-medium text-[#00275E]">$49</span>
                        </div>
                        <div class="pt-2">
                            <a href="#"
                                class="inline-flex items-center text-[#FF0000] text-sm font-medium hover:underline">
                                View Details
                                <i data-lucide="arrow-right" class="w-4 h-4 ml-1"></i>
                            </a>
                        </div>
                    </div>
                </div>

                <!-- Course Card 3 -->
                <div
                    class="bg-white border border-[#FF0000]/20 rounded-xl overflow-hidden shadow-md hover:shadow-xl hover:scale-[1.02] transition-transform duration-300">
                    <img src="{{ asset('images/logo.jpg') }}" alt="Vision Clarity Bootcamp"
                        class="w-full h-40 object-cover">
                    <div class="p-5 space-y-3">
                        <div class="flex items-center gap-2 text-[#FF0000]">
                            <i data-lucide="eye" class="w-5 h-5"></i>
                            <h3 class="text-lg font-semibold text-[#00275E]">Vision Clarity Bootcamp</h3>
                        </div>
                        <p class="text-sm text-gray-600">
                            Gain spiritual focus and actionable vision to lead in your God-given calling.
                        </p>
                        <div class="flex justify-between items-center">
                            <span class="text-xs bg-[#FF0000]/20 text-[#00275E] px-2 py-1 rounded-full">Advanced</span>
                            <span class="text-sm font-medium text-[#00275E]">$79</span>
                        </div>
                        <div class="pt-2">
                            <a href="#"
                                class="inline-flex items-center text-[#FF0000] text-sm font-medium hover:underline">
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
