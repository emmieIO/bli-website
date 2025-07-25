<x-guest-layout>
    <!-- Hero Section -->
    <section class="bg-teal-600 text-white py-12 md:py-20">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
            <h1 class="text-4xl md:text-5xl font-bold mb-6">Become an Instructor</h1>
            <p class="text-xl md:text-2xl max-w-3xl mx-auto mb-8">
                Share your expertise, inspire learners worldwide, and earn money teaching what you love
            </p>
            <div class="flex justify-center gap-4">
                <a href="#apply-now"
                    class="bg-white text-teal-600 px-6 py-3 rounded-lg font-medium hover:bg-teal-50 transition-colors">
                    Start Application
                </a>
                <a href="#benefits"
                    class="bg-teal-700 text-white px-6 py-3 rounded-lg font-medium hover:bg-teal-800 transition-colors">
                    Why Teach With Us?
                </a>
            </div>
        </div>
    </section>

    <!-- Benefits Section -->
    <section id="benefits" class="py-12 md:py-20 bg-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <h2 class="text-3xl font-bold text-center text-gray-900 mb-12">Why Become an Instructor?</h2>

            <div class="grid md:grid-cols-3 gap-8">
                <!-- Benefit 1 -->
                <div class="bg-teal-50 p-6 rounded-xl">
                    <div class="w-12 h-12 bg-teal-100 rounded-full flex items-center justify-center mb-4">
                        <svg class="w-6 h-6 text-teal-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                        </svg>
                    </div>
                    <h3 class="text-xl font-semibold mb-2">Earn Money</h3>
                    <p class="text-gray-600">Get paid monthly for the courses you create with competitive revenue
                        sharing.</p>
                </div>

                <!-- Benefit 2 -->
                <div class="bg-teal-50 p-6 rounded-xl">
                    <div class="w-12 h-12 bg-teal-100 rounded-full flex items-center justify-center mb-4">
                        <svg class="w-6 h-6 text-teal-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M3.055 11H5a2 2 0 012 2v1a2 2 0 002 2 2 2 0 012 2v2.945M8 3.935V5.5A2.5 2.5 0 0010.5 8h.5a2 2 0 012 2 2 2 0 104 0 2 2 0 012-2h1.064M15 20.488V18a2 2 0 012-2h3.064M21 12a9 9 0 11-18 0 9 9 0 0118 0z">
                            </path>
                        </svg>
                    </div>
                    <h3 class="text-xl font-semibold mb-2">Global Reach</h3>
                    <p class="text-gray-600">Teach students from around the world with our platform's international
                        audience.</p>
                </div>

                <!-- Benefit 3 -->
                <div class="bg-teal-50 p-6 rounded-xl">
                    <div class="w-12 h-12 bg-teal-100 rounded-full flex items-center justify-center mb-4">
                        <svg class="w-6 h-6 text-teal-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z">
                            </path>
                        </svg>
                    </div>
                    <h3 class="text-xl font-semibold mb-2">Support & Resources</h3>
                    <p class="text-gray-600">Access our instructor resources, teaching guides, and 24/7 support team.
                    </p>
                </div>
            </div>
        </div>
    </section>

    <!-- Application Form Section -->
    <section id="apply-now" class="py-12 md:py-20 bg-gray-50">
        <div class="max-w-2xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="bg-white p-8 rounded-xl shadow-sm">
                <h2 class="md:text-2xl text-xl font-bold text-center text-gray-900 mb-6 ">Start Your Instructor Application</h2>
                <p class="text-gray-600 text-center mb-8">
                    Begin your journey by entering your email address. We'll send you a secure link to complete your
                    application.
                </p>

                <form method="POST" action="{{ route('instructors.start-application') }}" class="space-y-6">
                    @csrf

                    <div>
                        <label for="email" class="block text-sm font-medium text-gray-700 mb-1">Email Address</label>
                        <input type="email" id="email" name="email" required
                            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-teal-500 focus:border-teal-500"
                            placeholder="your@email.com">
                        @error('email')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <button type="submit"
                            class="w-full bg-teal-600 text-white px-6 py-3 rounded-lg font-medium hover:bg-teal-700 transition-colors">
                            Begin Application
                        </button>
                    </div>

                    <p class="text-sm text-gray-500 text-center">
                        By applying, you agree to our <a href="#" class="text-teal-600 hover:underline">Instructor
                            Terms</a>.
                        We'll email you a secure link to complete your application.
                    </p>
                </form>
            </div>

            <div class="mt-8 text-center">
                <p class="text-gray-600">
                    Already started your application? <a href=""
                        class="text-teal-600 hover:underline">Resend your application link</a>
                </p>
            </div>
        </div>
    </section>

    <!-- Testimonials Section -->
    <section class="py-12 md:py-20 bg-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <h2 class="text-3xl font-bold text-center text-gray-900 mb-12">What Our Instructors Say</h2>

            <div class="grid md:grid-cols-2 gap-8">
                <!-- Testimonial 1 -->
                <div class="bg-gray-50 p-6 rounded-xl">
                    <div class="flex items-center mb-4">
                        <img src="https://randomuser.me/api/portraits/women/42.jpg" alt="Sarah Johnson"
                            class="w-12 h-12 rounded-full mr-4">
                        <div>
                            <h4 class="font-semibold">Sarah Johnson</h4>
                            <p class="text-teal-600 text-sm">Data Science Instructor</p>
                        </div>
                    </div>
                    <p class="text-gray-600 italic">"Teaching on this platform has allowed me to reach students in over
                        30 countries. The support team is incredible and the revenue share is very fair."</p>
                </div>

                <!-- Testimonial 2 -->
                <div class="bg-gray-50 p-6 rounded-xl">
                    <div class="flex items-center mb-4">
                        <img src="https://randomuser.me/api/portraits/men/32.jpg" alt="Michael Chen"
                            class="w-12 h-12 rounded-full mr-4">
                        <div>
                            <h4 class="font-semibold">Michael Chen</h4>
                            <p class="text-teal-600 text-sm">Web Development Instructor</p>
                        </div>
                    </div>
                    <p class="text-gray-600 italic">"I've been able to turn my passion for coding into a full-time
                        income. The course creation tools make it easy to produce professional content."</p>
                </div>
            </div>
        </div>
    </section>

    <!-- FAQ Section -->
    <section class="py-12 md:py-20 bg-gray-50">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
            <h2 class="text-3xl font-bold text-center text-gray-900 mb-12">Frequently Asked Questions</h2>

            <div class="space-y-4">
                <!-- FAQ Item 1 -->
                <div class="bg-white p-6 rounded-lg shadow-sm">
                    <button class="flex justify-between items-center w-full text-left">
                        <h3 class="font-medium text-gray-900">What qualifications do I need to become an instructor?
                        </h3>
                        <svg class="w-5 h-5 text-teal-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7">
                            </path>
                        </svg>
                    </button>
                    <div class="mt-3 text-gray-600">
                        <p>We look for instructors with proven expertise in their field. This can be through
                            professional experience, teaching experience, or specialized training. Formal qualifications
                            are helpful but not always required.</p>
                    </div>
                </div>

                <!-- FAQ Item 2 -->
                <div class="bg-white p-6 rounded-lg shadow-sm">
                    <button class="flex justify-between items-center w-full text-left">
                        <h3 class="font-medium text-gray-900">How much can I earn as an instructor?</h3>
                        <svg class="w-5 h-5 text-teal-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7">
                            </path>
                        </svg>
                    </button>
                    <div class="mt-3 text-gray-600">
                        <p>Earnings vary based on course popularity and quality. Our top instructors earn over
                            $10,000/month. We offer a competitive revenue share model and provide guidance on pricing
                            strategies.</p>
                    </div>
                </div>

                <!-- FAQ Item 3 -->
                <div class="bg-white p-6 rounded-lg shadow-sm">
                    <button class="flex justify-between items-center w-full text-left">
                        <h3 class="font-medium text-gray-900">What's the application process like?</h3>
                        <svg class="w-5 h-5 text-teal-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7">
                            </path>
                        </svg>
                    </button>
                    <div class="mt-3 text-gray-600">
                        <p>1. Start by entering your email address<br>
                            2. Complete our multi-step application form<br>
                            3. Our team will review your application<br>
                            4. If approved, you'll receive account activation instructions</p>
                    </div>
                </div>
            </div>
        </div>
    </section>
</x-guest-layout>
