<x-guest-layout>
    <!-- Thank You Hero Section -->
    <section class="relative min-h-screen flex items-center justify-center overflow-hidden"
        style="background: linear-gradient(135deg, #002147 0%, #003875 50%, #002147 100%);">
        <!-- Animated Background Elements -->
        <div class="absolute inset-0 opacity-10">
            <div class="absolute top-10 left-10 w-32 h-32 rounded-full animate-pulse"
                style="background: linear-gradient(135deg, #00a651, #ed1c24);"></div>
            <div class="absolute bottom-10 right-10 w-24 h-24 rounded-full animate-bounce"
                style="background: linear-gradient(135deg, #ed1c24, #00a651);"></div>
            <div class="absolute top-1/2 left-1/4 w-16 h-16 rounded-full animate-ping"
                style="background: rgba(0, 166, 81, 0.3);"></div>
        </div>

        <div class="relative z-10 container mx-auto px-4">
            <div class="max-w-4xl mx-auto text-center">
                <!-- Success Animation Icon -->
                <div class="flex justify-center mb-8" data-aos="zoom-in">
                    <div class="relative">
                        <div class="w-32 h-32 rounded-3xl flex items-center justify-center"
                            style="background: linear-gradient(135deg, #00a651 0%, #15803d 100%);">
                            <i class="fas fa-check-circle text-white text-6xl animate-pulse"></i>
                        </div>
                        <!-- Animated Ring -->
                        <div class="absolute inset-0 rounded-3xl animate-ping"
                            style="background: rgba(0, 166, 81, 0.3);"></div>
                    </div>
                </div>

                <!-- Success Message -->
                <div class="mb-12" data-aos="fade-up" data-aos-delay="200">
                    <h1 class="text-4xl lg:text-6xl font-bold text-white mb-6 font-montserrat leading-tight">
                        Application Submitted!
                    </h1>
                    <p class="text-2xl text-blue-200 mb-6 font-lato">
                        Thank you for applying to speak at
                    </p>
                    <div class="inline-block px-8 py-4 rounded-2xl mb-8"
                        style="background: rgba(237, 28, 36, 0.2); border: 1px solid rgba(237, 28, 36, 0.3);">
                        <h2 class="text-2xl lg:text-3xl font-bold font-montserrat" style="color: #ed1c24;">
                            {{ $event->title }}
                        </h2>
                    </div>
                </div>

                <!-- Confirmation Card -->
                <div class="bg-white rounded-3xl shadow-2xl border border-gray-100 overflow-hidden mb-12 max-w-3xl mx-auto"
                    data-aos="fade-up" data-aos-delay="400">
                    <!-- Card Header -->
                    <div class="p-8 text-center" style="background: linear-gradient(135deg, #f8fafc 0%, #e2e8f0 100%);">
                        <div class="w-16 h-16 rounded-2xl flex items-center justify-center mx-auto mb-4"
                            style="background: linear-gradient(135deg, #002147 0%, #003875 100%);">
                            <i class="fas fa-file-check text-white text-2xl"></i>
                        </div>
                        <h3 class="text-2xl font-bold font-montserrat mb-2" style="color: #002147;">Application Received
                        </h3>
                        <p class="text-gray-600 font-lato">Your speaker proposal is now under review</p>
                    </div>

                    <!-- Card Content -->
                    <div class="p-8 space-y-8">
                        <!-- Status Message -->
                        <div class="text-center">
                            <p class="text-lg text-gray-700 font-lato leading-relaxed">
                                Your proposal has been successfully received and will be reviewed as we finalize the
                                speaker lineup for
                                <span class="font-bold font-montserrat"
                                    style="color: #002147;">{{ $event->title }}</span>.
                            </p>
                        </div>

                        <!-- Next Steps -->
                        <div class="p-6 rounded-2xl"
                            style="background: linear-gradient(135deg, rgba(0, 166, 81, 0.1) 0%, rgba(0, 166, 81, 0.05) 100%);">
                            <div class="flex items-start gap-4">
                                <div class="w-12 h-12 rounded-2xl flex items-center justify-center flex-shrink-0"
                                    style="background: rgba(0, 166, 81, 0.2);">
                                    <i class="fas fa-route text-lg" style="color: #00a651;"></i>
                                </div>
                                <div class="text-left">
                                    <h4 class="text-lg font-bold font-montserrat mb-2" style="color: #002147;">What
                                        Happens Next?</h4>
                                    <ul class="space-y-2 text-gray-700 font-lato">
                                        <li class="flex items-center">
                                            <i class="fas fa-circle text-xs mr-3" style="color: #00a651;"></i>
                                            Our team will review your application within 3-5 business days
                                        </li>
                                        <li class="flex items-center">
                                            <i class="fas fa-circle text-xs mr-3" style="color: #00a651;"></i>
                                            Selected speakers will be notified via email
                                        </li>
                                        <li class="flex items-center">
                                            <i class="fas fa-circle text-xs mr-3" style="color: #00a651;"></i>
                                            Additional details will be provided upon selection
                                        </li>
                                    </ul>
                                </div>
                            </div>
                        </div>

                        <!-- Timeline -->
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                            <div class="text-center p-4">
                                <div class="w-16 h-16 rounded-2xl flex items-center justify-center mx-auto mb-3"
                                    style="background: rgba(0, 33, 71, 0.1);">
                                    <i class="fas fa-paper-plane text-2xl" style="color: #002147;"></i>
                                </div>
                                <h5 class="font-bold font-montserrat mb-1" style="color: #002147;">Application Sent</h5>
                                <p class="text-sm text-gray-600 font-lato">✓ Completed</p>
                            </div>

                            <div class="text-center p-4">
                                <div class="w-16 h-16 rounded-2xl flex items-center justify-center mx-auto mb-3"
                                    style="background: rgba(237, 28, 36, 0.1);">
                                    <i class="fas fa-search text-2xl" style="color: #ed1c24;"></i>
                                </div>
                                <h5 class="font-bold font-montserrat mb-1" style="color: #ed1c24;">Under Review</h5>
                                <p class="text-sm text-gray-600 font-lato">In Progress</p>
                            </div>

                            <div class="text-center p-4">
                                <div class="w-16 h-16 rounded-2xl flex items-center justify-center mx-auto mb-3"
                                    style="background: rgba(0, 166, 81, 0.1);">
                                    <i class="fas fa-envelope text-2xl" style="color: #00a651;"></i>
                                </div>
                                <h5 class="font-bold font-montserrat mb-1" style="color: #00a651;">Notification</h5>
                                <p class="text-sm text-gray-600 font-lato">Pending</p>
                            </div>
                        </div>

                        <!-- Contact Information -->
                        <div class="border-t border-gray-200 pt-6">
                            <p class="text-gray-600 font-lato leading-relaxed">
                                Have questions? Contact our team at
                                <a href="mailto:{{ $event->contact_email }}"
                                    class="font-semibold hover:underline transition-colors duration-300"
                                    style="color: #002147;">
                                    {{ $event->contact_email }}
                                </a>
                            </p>
                        </div>
                    </div>
                </div>

                <!-- Action Buttons -->
                <div class="flex flex-col sm:flex-row gap-4 justify-center" data-aos="fade-up" data-aos-delay="600">
                    <a href="/"
                        class="inline-flex items-center px-8 py-4 rounded-2xl font-semibold font-montserrat transition-all duration-300 transform hover:scale-105 shadow-lg hover:shadow-xl"
                        style="background: linear-gradient(135deg, #002147 0%, #ed1c24 100%); color: white;">
                        <i class="fas fa-home mr-3"></i>
                        Return to Homepage
                    </a>

                    <a href="{{ route('events.show', $event->slug) }}"
                        class="inline-flex items-center px-8 py-4 border-2 rounded-2xl font-semibold font-montserrat transition-all duration-300 hover:bg-white"
                        style="border-color: white; color: white; background: rgba(255, 255, 255, 0.1);">
                        <i class="fas fa-calendar mr-3"></i>
                        View Event Details
                    </a>
                </div>
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