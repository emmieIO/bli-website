<x-guest-layout>
    <!-- Hero Section -->
    <section class="relative py-12 md:py-16 text-white overflow-hidden">
        <div class="absolute inset-0 bg-gradient-to-r from-black/40 to-black/60 bg-cover bg-center bg-no-repeat"
            style="background-image: url('{{ asset('images/banner-become-instructors.png') }}')">
        </div>
        <div class="container mx-auto px-4 relative">
            <div class="max-w-7xl mx-auto text-center md:text-left">
                <h1 class="text-4xl md:text-5xl lg:text-6xl font-bold mb-6 text-white">Become an Instructor</h1>
                <p class="text-xl md:text-2xl mb-8 text-gray-200 leading-relaxed">
                    Share your passion, inspire learners worldwide, and earn income doing what you love.
                </p>
                <a href="#apply-now"
                    class="inline-block bg-secondary hover:bg-secondary-600 text-white font-semibold px-8 py-4 rounded-lg transition-all duration-300 transform hover:scale-105 mb-4">
                    Begin process
                </a>
                <p class="text-gray-300 text-sm">We'll review your proposal within 5–7 business days.</p>
            </div>
        </div>
    </section>

    <!-- How It Works -->
    <section id="how-it-works" class="py-12 md:py-16 bg-white">
        <div class="container mx-auto px-4">
            <div class="text-center mb-12">
                <h2 class="text-3xl md:text-4xl lg:text-5xl font-bold text-primary mb-4">How to Become an Instructor
                </h2>
                <p class="text-gray-600 text-lg max-w-2xl mx-auto">
                    Just 6 simple steps. No tech skills needed — we'll guide you every step of the way.
                </p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                @foreach ([['step' => 1, 'title' => 'Create Your Account', 'desc' => 'No account needed to start—just enter your email below. Already have an account? Scroll to the application section and send your link!'], ['step' => 2, 'title' => 'Start Instructor Application', 'desc' => 'Click "Become an Instructor" or scroll down to the application form to begin.'], ['step' => 3, 'title' => 'Check Your Email', 'desc' => 'We\'ll send a secure link to finish your profile.'], ['step' => 4, 'title' => 'Complete Your Profile', 'desc' => 'Add your bio, expertise, and intro video.'], ['step' => 5, 'title' => 'Submit & Get Confirmation', 'desc' => 'You\'ll get an email confirming receipt.'], ['step' => 6, 'title' => 'Wait for Review', 'desc' => 'Our team reviews in 5–7 business days.']] as $stepData)
                    <div
                        class="step-card bg-primary-50 p-6 rounded-2xl shadow-sm hover:shadow-xl transition-all duration-300 hover:-translate-y-1">
                        <div class="flex items-center mb-4">
                            <div
                                class="flex-shrink-0 w-10 h-10 bg-secondary text-white rounded-full flex items-center justify-center font-bold mr-4">
                                {{ $stepData['step'] }}
                            </div>
                            <h3 class="text-lg font-bold text-primary">{{ $stepData['title'] }}</h3>
                        </div>
                        <p class="text-gray-600 text-sm leading-relaxed">{{ $stepData['desc'] }}</p>
                    </div>
                @endforeach
            </div>

            <div class="text-center mt-12">
                <p class="text-gray-600 mb-3">Have questions?</p>
                <a href="#faq"
                    class="font-semibold text-primary hover:text-primary-600 transition-colors inline-flex items-center">
                    See our FAQ
                    <i data-lucide="arrow-right" class="w-4 h-4 ml-2"></i>
                </a>
            </div>
        </div>
    </section>

    <!-- Benefits -->
    <section class="py-12 md:py-16 bg-gray-50">
        <div class="container mx-auto px-4">
            <div class="text-center mb-12">
                <h2 class="text-3xl md:text-4xl lg:text-5xl font-bold text-primary mb-4">Why Instructors Love Teaching
                    With Us</h2>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                <div
                    class="benefit-card bg-white p-8 rounded-2xl text-center shadow-sm hover:shadow-xl transition-all duration-300 hover:-translate-y-1">
                    <div class="w-16 h-16 bg-accent-100 rounded-full flex items-center justify-center mx-auto mb-6">
                        <i data-lucide="dollar-sign" class="w-8 h-8 text-accent"></i>
                    </div>
                    <h3 class="text-xl font-bold text-primary mb-4">Earn Meaningful Income</h3>
                    <p class="text-gray-600">Get paid monthly with one of the industry's best revenue shares.</p>
                </div>

                <div
                    class="benefit-card bg-white p-8 rounded-2xl text-center shadow-sm hover:shadow-xl transition-all duration-300 hover:-translate-y-1">
                    <div class="w-16 h-16 bg-accent-100 rounded-full flex items-center justify-center mx-auto mb-6">
                        <i data-lucide="globe" class="w-8 h-8 text-accent"></i>
                    </div>
                    <h3 class="text-xl font-bold text-primary mb-4">Global Audience</h3>
                    <p class="text-gray-600">Reach learners in 190+ countries and build your personal brand.</p>
                </div>

                <div
                    class="benefit-card bg-white p-8 rounded-2xl text-center shadow-sm hover:shadow-xl transition-all duration-300 hover:-translate-y-1">
                    <div class="w-16 h-16 bg-accent-100 rounded-full flex items-center justify-center mx-auto mb-6">
                        <i data-lucide="life-buoy" class="w-8 h-8 text-accent"></i>
                    </div>
                    <h3 class="text-xl font-bold text-primary mb-4">Full Support & Tools</h3>
                    <p class="text-gray-600">From course design to marketing — we've got your back.</p>
                </div>
            </div>
        </div>
    </section>

    <!-- Application CTA -->
    <section id="apply-now" class="py-12 md:py-16 bg-white">
        <div class="container mx-auto px-4">
            <div class="max-w-4xl mx-auto">
                <div class="bg-white rounded-2xl shadow-lg p-8 md:p-12 text-center border-t-4 border-secondary">
                    <h2 class="text-2xl md:text-3xl font-bold text-primary mb-4">Ready to Inspire Learners?</h2>
                    <p class="text-gray-600 text-lg mb-8">
                        Enter your email and we'll send a secure link to start your instructor application.
                    </p>

                    <form method="POST" action="{{ route('instructors.start-application') }}" class="max-w-md mx-auto">
                        @csrf
                        @auth
                            <input type="hidden" name="email" value="{{ auth()->user()->email }}">
                            <p class="mb-6 text-gray-700">You're logged in as <strong
                                    class="text-primary">{{ auth()->user()->email }}</strong></p>
                        @endauth

                        @guest
                            <div class="mb-6">
                                <input type="email" name="email" required
                                    class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-primary transition-colors"
                                    placeholder="Your email address">
                                @error('email')
                                    <div class="text-red-600 text-sm mt-2">{{ $message }}</div>
                                @enderror
                            </div>
                        @endguest

                        <button type="submit"
                            class="w-full bg-secondary hover:bg-secondary-600 text-white font-bold py-4 px-8 rounded-full transition-all duration-300 transform hover:scale-105">
                            Send My Application Link
                        </button>

                        <p class="mt-6 text-gray-500 text-sm">
                            By applying, you agree to our <a href="#"
                                class="text-primary hover:text-primary-600 font-medium">Instructor Terms</a>.
                            Check your spam folder if you don't receive our email.
                        </p>
                    </form>
                </div>
            </div>
        </div>
    </section>

    <!-- Testimonials -->
    <section class="py-12 md:py-16 bg-gray-50">
        <div class="container mx-auto px-4">
            <div class="text-center mb-12">
                <h2 class="text-3xl md:text-4xl lg:text-5xl font-bold text-primary mb-4">Instructor Success Stories</h2>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-8 max-w-4xl mx-auto">
                <div class="bg-white p-6 rounded-2xl shadow-sm h-full">
                    <div class="flex items-center mb-4">
                        <img src="https://randomuser.me/api/portraits/women/42.jpg" alt="Sarah Johnson"
                            class="w-14 h-14 rounded-full object-cover mr-4">
                        <div>
                            <h4 class="text-lg font-bold text-primary">Sarah Johnson</h4>
                            <div class="text-gray-500 text-sm">Data Science Instructor</div>
                        </div>
                    </div>
                    <p class="text-gray-600 italic">
                        "Teaching here changed my career. I now reach students in 30+ countries and earn more than my
                        full-time job."
                    </p>
                </div>
                <div class="bg-white p-6 rounded-2xl shadow-sm h-full">
                    <div class="flex items-center mb-4">
                        <img src="https://randomuser.me/api/portraits/men/32.jpg" alt="Michael Chen"
                            class="w-14 h-14 rounded-full object-cover mr-4">
                        <div>
                            <h4 class="text-lg font-bold text-primary">Michael Chen</h4>
                            <div class="text-gray-500 text-sm">Web Development Instructor</div>
                        </div>
                    </div>
                    <p class="text-gray-600 italic">
                        "The platform made it easy to create professional courses. Now I teach full-time and love every
                        minute."
                    </p>
                </div>
            </div>
        </div>
    </section>

    <!-- FAQ -->
    <section id="faq" class="py-12 md:py-16 bg-white">
        <div class="container mx-auto px-4 max-w-4xl">
            <div class="text-center mb-12">
                <h2 class="text-3xl md:text-4xl lg:text-5xl font-bold text-primary mb-4">Frequently Asked Questions
                </h2>
            </div>

            <div class="space-y-4">
                @foreach ([['title' => 'What qualifications do I need?', 'body' => 'We look for real-world expertise—whether from work, teaching, or projects. Degrees help but aren\'t required.'], ['title' => 'How much can I earn?', 'body' => 'Top instructors earn $10K+/month. Earnings depend on course quality, demand, and your marketing. We offer 70% revenue share.'], ['title' => 'How long is the review process?', 'body' => 'We review applications in 5–7 business days. You\'ll get email updates at every stage.'], ['title' => 'What if I\'m not approved?', 'body' => 'We provide personalized feedback. You can reapply in 30 days with improvements—many successful instructors weren\'t accepted the first time!']] as $index => $faq)
                    <div class="bg-white rounded-2xl shadow-sm border border-gray-200 overflow-hidden">
                        <button
                            class="faq-question w-full px-6 py-4 text-left font-semibold text-primary hover:bg-gray-50 transition-colors flex justify-between items-center"
                            onclick="toggleFAQ({{ $index }})">
                            {{ $faq['title'] }}
                            <i data-lucide="chevron-down" class="w-5 h-5 transition-transform duration-300"
                                id="faq-icon-{{ $index }}"></i>
                        </button>
                        <div id="faq-answer-{{ $index }}"
                            class="faq-answer px-6 py-4 text-gray-600 border-t border-gray-200 hidden">
                            {{ $faq['body'] }}
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </section>

    <script>
        // Initialize Lucide icons
        document.addEventListener('DOMContentLoaded', function() {
            if (typeof lucide !== 'undefined') {
                lucide.createIcons();
            }
        });

        // FAQ toggle functionality
        function toggleFAQ(index) {
            const answer = document.getElementById(`faq-answer-${index}`);
            const icon = document.getElementById(`faq-icon-${index}`);

            if (answer.classList.contains('hidden')) {
                answer.classList.remove('hidden');
                icon.style.transform = 'rotate(180deg)';
            } else {
                answer.classList.add('hidden');
                icon.style.transform = 'rotate(0deg)';
            }
        }
    </script>
</x-guest-layout>
