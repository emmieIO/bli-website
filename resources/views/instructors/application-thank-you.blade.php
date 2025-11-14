<x-guest-layout>
    <!-- Enhanced Thank You Page -->
    <section
        class="min-h-screen flex items-center justify-center py-20 bg-gradient-to-br from-gray-50 via-white to-gray-100">
        <div class="max-w-4xl mx-auto px-4 text-center">
            <!-- Success Animation Container -->
            <div class="mb-12" data-aos="zoom-in" data-aos-duration="1000">
                <!-- Animated Success Icon -->
                <div class="relative inline-flex items-center justify-center w-32 h-32 mx-auto mb-8">
                    <!-- Background Circle with Animation -->
                    <div class="absolute inset-0 rounded-full opacity-20 animate-ping" style="background: #00a651;">
                    </div>
                    <div class="absolute inset-0 rounded-full opacity-30"
                        style="background: radial-gradient(circle, #00a651 0%, transparent 70%);"></div>

                    <!-- Main Icon Container -->
                    <div class="relative w-20 h-20 rounded-full flex items-center justify-center"
                        style="background: linear-gradient(135deg, #00a651 0%, #15803d 100%);">
                        <i class="fas fa-check text-4xl text-white"></i>
                    </div>
                </div>

                <!-- Main Success Message -->
                <h1 class="text-4xl md:text-5xl lg:text-6xl font-bold mb-6 font-montserrat" style="color: #002147;"
                    data-aos="fade-up" data-aos-delay="200">
                    Application Submitted!
                </h1>

                <div class="inline-flex items-center px-6 py-3 rounded-full mb-8"
                    style="background: rgba(0, 166, 81, 0.1);" data-aos="fade-up" data-aos-delay="300">
                    <i class="fas fa-paper-plane mr-3" style="color: #00a651;"></i>
                    <span class="font-semibold font-montserrat" style="color: #00a651;">Thank You for Your
                        Interest!</span>
                </div>
            </div>

            <!-- Information Cards -->
            <div class="grid md:grid-cols-2 gap-8 mb-12 max-w-3xl mx-auto">
                <!-- Review Process Card -->
                <div class="bg-white p-8 rounded-2xl shadow-lg border border-gray-100 hover:shadow-xl transition-all duration-300"
                    data-aos="fade-right" data-aos-delay="400">
                    <div class="w-16 h-16 rounded-2xl flex items-center justify-center mx-auto mb-6"
                        style="background: rgba(0, 33, 71, 0.1);">
                        <i class="fas fa-clock text-2xl" style="color: #002147;"></i>
                    </div>
                    <h3 class="text-xl font-bold mb-4 font-montserrat" style="color: #002147;">Review Timeline</h3>
                    <p class="text-gray-600 font-lato leading-relaxed">
                        We typically review applications within <strong style="color: #00a651;">5-7 business
                            days</strong>.
                        Our team carefully evaluates each submission to ensure the best fit.
                    </p>
                </div>

                <!-- Next Steps Card -->
                <div class="bg-white p-8 rounded-2xl shadow-lg border border-gray-100 hover:shadow-xl transition-all duration-300"
                    data-aos="fade-left" data-aos-delay="500">
                    <div class="w-16 h-16 rounded-2xl flex items-center justify-center mx-auto mb-6"
                        style="background: rgba(237, 28, 36, 0.1);">
                        <i class="fas fa-envelope text-2xl" style="color: #ed1c24;"></i>
                    </div>
                    <h3 class="text-xl font-bold mb-4 font-montserrat" style="color: #002147;">What's Next?</h3>
                    <p class="text-gray-600 font-lato leading-relaxed">
                        You'll receive an <strong style="color: #ed1c24;">email notification</strong> once a decision
                        has been made. Check your spam folder just in case!
                    </p>
                </div>
            </div>

            <!-- Application Details Summary -->
            <div class="bg-white p-8 rounded-2xl shadow-lg border border-gray-100 mb-12 max-w-2xl mx-auto"
                data-aos="fade-up" data-aos-delay="600">
                <h3 class="text-xl font-bold mb-6 font-montserrat flex items-center justify-center"
                    style="color: #002147;">
                    <i class="fas fa-clipboard-list mr-3" style="color: #00a651;"></i>
                    Application Confirmed
                </h3>

                <div class="space-y-4">
                    <div class="flex items-center justify-between py-3 px-4 rounded-xl"
                        style="background: rgba(0, 166, 81, 0.1);">
                        <div class="flex items-center">
                            <i class="fas fa-user-check mr-3" style="color: #00a651;"></i>
                            <span class="font-medium font-lato" style="color: #002147;">Profile Submitted</span>
                        </div>
                        <i class="fas fa-check-circle" style="color: #00a651;"></i>
                    </div>

                    <div class="flex items-center justify-between py-3 px-4 rounded-xl"
                        style="background: rgba(0, 166, 81, 0.1);">
                        <div class="flex items-center">
                            <i class="fas fa-file-alt mr-3" style="color: #00a651;"></i>
                            <span class="font-medium font-lato" style="color: #002147;">Documents Received</span>
                        </div>
                        <i class="fas fa-check-circle" style="color: #00a651;"></i>
                    </div>

                    <div class="flex items-center justify-between py-3 px-4 rounded-xl"
                        style="background: rgba(237, 28, 36, 0.1);">
                        <div class="flex items-center">
                            <i class="fas fa-search mr-3" style="color: #ed1c24;"></i>
                            <span class="font-medium font-lato" style="color: #002147;">Under Review</span>
                        </div>
                        <i class="fas fa-clock" style="color: #ed1c24;"></i>
                    </div>
                </div>
            </div>

            <!-- Action Buttons -->
            <div class="flex flex-col sm:flex-row gap-4 justify-center items-center" data-aos="fade-up"
                data-aos-delay="700">
                <a href="{{ route('homepage') }}"
                    class="inline-flex items-center px-8 py-4 rounded-xl font-bold text-lg transition-all duration-300 transform hover:scale-105 hover:shadow-xl font-montserrat"
                    style="background: linear-gradient(135deg, #002147 0%, #003875 100%); color: white;"
                    onmouseover="this.style.boxShadow='0 20px 40px rgba(0, 33, 71, 0.3)'"
                    onmouseout="this.style.boxShadow='0 10px 30px rgba(0, 33, 71, 0.2)'">
                    <i class="fas fa-home mr-3"></i>
                    Back to Homepage
                </a>

                <a href="{{ route('courses.index') }}"
                    class="inline-flex items-center px-8 py-4 rounded-xl font-bold text-lg border-2 transition-all duration-300 hover:shadow-lg font-montserrat"
                    style="color: #00a651; border-color: #00a651;"
                    onmouseover="this.style.backgroundColor='rgba(0, 166, 81, 0.1)'"
                    onmouseout="this.style.backgroundColor='transparent'">
                    <i class="fas fa-book-open mr-3"></i>
                    Browse Courses
                </a>
            </div>

            <!-- Contact Support -->
            <div class="mt-12 p-6 rounded-xl max-w-lg mx-auto" style="background: rgba(237, 28, 36, 0.1);"
                data-aos="fade-up" data-aos-delay="800">
                <h4 class="font-bold mb-2 font-montserrat" style="color: #002147;">Questions about your application?
                </h4>
                <p class="text-sm text-gray-600 mb-4 font-lato">Our support team is here to help you through the process
                </p>
                <a href="mailto:instructors@example.com"
                    class="inline-flex items-center text-sm font-semibold hover:underline font-montserrat"
                    style="color: #ed1c24;">
                    <i class="fas fa-envelope mr-2"></i>
                    Contact Support Team
                </a>
            </div>
        </div>
    </section>

    <!-- AOS Animation Library -->
    <link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet">
    <script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>

    <script>
        // Initialize AOS
        document.addEventListener('DOMContentLoaded', function () {
            AOS.init({
                duration: 800,
                easing: 'ease-out-cubic',
                once: true,
                offset: 100
            });
        });
    </script>
</x-guest-layout>