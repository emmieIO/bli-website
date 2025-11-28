<x-guest-layout>
    <div class="min-h-screen bg-linear-to-br from-primary-50 to-white flex items-center justify-center p-4">
        <div class="max-w-4xl mx-auto text-center">
            <!-- Main Content -->
            <div class="mb-16">
                <h2 class="text-5xl md:text-6xl lg:text-7xl font-bold text-primary mb-6 font-montserrat"
                    data-aos="fade-down" data-aos-duration="1000" data-aos-delay="200">
                    Coming Soon
                </h2>
                <p class="text-xl md:text-2xl text-gray-600 mb-8 max-w-2xl mx-auto font-lato leading-relaxed"
                    data-aos="fade-up" data-aos-duration="1000" data-aos-delay="400">
                    Something amazing is on the way. We're working hard to bring you an incredible experience.
                </p>
                <a href="{{ route('homepage') }}"
                    class="inline-block text-center mb-5 bg-secondary hover:bg-secondary-600 text-white font-semibold py-3 px-6 rounded-lg transition-all duration-200 transform hover:scale-105 font-montserrat"
                    data-aos="fade-left" data-aos-duration="600" data-aos-delay="1100">
                    Go back Home
                </a>
            </div>

            <!-- Newsletter Signup -->
            {{-- <div class="bg-white rounded-2xl shadow-xl p-8 md:p-10 max-w-md mx-auto mb-12"
                 data-aos="zoom-in"
                 data-aos-duration="800"
                 data-aos-delay="600">
                <h3 class="text-2xl font-bold text-primary mb-4 font-montserrat"
                    data-aos="fade-down"
                    data-aos-duration="600"
                    data-aos-delay="800">
                    Get Notified
                </h3>
                <p class="text-gray-600 mb-6 font-lato"
                   data-aos="fade-up"
                   data-aos-duration="600"
                   data-aos-delay="900">
                    Be the first to know when we launch
                </p>

                <form class="space-y-4">
                    <div data-aos="fade-right"
                         data-aos-duration="600"
                         data-aos-delay="1000">
                        <input type="email" placeholder="Enter your email"
                            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-transparent transition-all duration-200 font-lato"
                            required>
                    </div>
                    <button type="submit"
                            class="w-full bg-secondary hover:bg-secondary-600 text-white font-semibold py-3 px-6 rounded-lg transition-all duration-200 transform hover:scale-105 font-montserrat"
                            data-aos="fade-left"
                            data-aos-duration="600"
                            data-aos-delay="1100">
                        Notify Me
                    </button>
                </form>
            </div> --}}

            <!-- Social Links -->
            <div class="flex justify-center space-x-6 mb-12" data-aos="fade-up" data-aos-duration="800"
                data-aos-delay="1200">
                <a href="#" class="text-gray-400 hover:text-primary transition-colors duration-200"
                    aria-label="Twitter" data-aos="zoom-in" data-aos-duration="400" data-aos-delay="1300">
                    <i data-lucide="twitter" class="w-6 h-6"></i>
                </a>
                <a href="#" class="text-gray-400 hover:text-primary transition-colors duration-200"
                    aria-label="Facebook" data-aos="zoom-in" data-aos-duration="400" data-aos-delay="1400">
                    <i data-lucide="facebook" class="w-6 h-6"></i>
                </a>
                <a href="#" class="text-gray-400 hover:text-primary transition-colors duration-200"
                    aria-label="LinkedIn" data-aos="zoom-in" data-aos-duration="400" data-aos-delay="1500">
                    <i data-lucide="instagram" class="w-6 h-6"></i>
                </a>
            </div>

            <!-- Footer -->
            <div class="text-gray-500 text-sm font-lato" data-aos="fade" data-aos-duration="800" data-aos-delay="1700">
                <p>&copy; {{ date('Y') }} {{ config('app.name') }}. All rights reserved.</p>
            </div>
        </div>
    </div>

    <style>
        /* Smooth animations for countdown */
        .countdown-item {
            transition: all 0.3s ease;
        }

        .countdown-item:hover {
            transform: translateY(-5px);
        }

        /* Gradient animation for background */
        @keyframes gradientShift {
            0% {
                background-position: 0% 50%;
            }

            50% {
                background-position: 100% 50%;
            }

            100% {
                background-position: 0% 50%;
            }
        }

        .bg-linear-to-br {
            background-size: 200% 200%;
            animation: gradientShift 15s ease infinite;
        }
    </style>
</x-guest-layout>
