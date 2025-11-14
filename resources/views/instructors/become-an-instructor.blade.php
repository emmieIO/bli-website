<x-guest-layout>
    <!-- Enhanced Hero Section -->
    <section class="relative min-h-screen flex items-center overflow-hidden" 
        style="background: linear-gradient(135deg, #002147 0%, #003875 50%, #002147 100%);">
        
        <!-- Background Pattern -->
        <div class="absolute inset-0 opacity-10">
            <div class="absolute inset-0" style="background-image: url('{{ asset('images/pattern-bg.jpg') }}'); background-size: cover; background-position: center;"></div>
        </div>
        
        <!-- Animated Background Elements -->
        <div class="absolute inset-0 overflow-hidden">
            <div class="absolute -top-1/2 -right-1/2 w-96 h-96 rounded-full opacity-20" 
                style="background: radial-gradient(circle, #00a651 0%, transparent 70%); animation: float 6s ease-in-out infinite;"></div>
            <div class="absolute -bottom-1/2 -left-1/2 w-80 h-80 rounded-full opacity-15" 
                style="background: radial-gradient(circle, #ed1c24 0%, transparent 70%); animation: float 8s ease-in-out infinite reverse;"></div>
        </div>
        
        <div class="container mx-auto px-4 relative z-10">
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-12 items-center">
                <!-- Left Content -->
                <div class="text-white" data-aos="fade-right" data-aos-duration="1000">
                    <div class="inline-flex items-center px-4 py-2 rounded-full mb-6" 
                        style="background: rgba(0, 166, 81, 0.2); backdrop-filter: blur(10px);">
                        <i class="fas fa-chalkboard-teacher mr-2" style="color: #00a651;"></i>
                        <span class="text-sm font-semibold font-montserrat" style="color: #00a651;">Join Our Teaching Community</span>
                    </div>
                    
                    <h1 class="text-4xl md:text-5xl lg:text-7xl font-bold mb-6 font-montserrat leading-tight">
                        Become an 
                        <span class="relative">
                            <span class="relative z-10" style="color: #00a651;">Instructor</span>
                            <div class="absolute -bottom-2 left-0 w-full h-4 opacity-30" 
                                style="background: #00a651; transform: skew(-12deg);"></div>
                        </span>
                    </h1>
                    
                    <p class="text-xl md:text-2xl mb-8 text-gray-200 leading-relaxed font-lato max-w-2xl">
                        Transform lives through education. Share your expertise with a global audience and 
                        <strong style="color: #00a651;">earn meaningful income</strong> doing what you love.
                    </p>
                    
                    <div class="flex flex-col sm:flex-row gap-4 mb-8">
                        <a href="#apply-now" 
                            class="inline-flex items-center justify-center px-8 py-4 rounded-xl font-bold text-lg transition-all duration-300 transform hover:scale-105 hover:shadow-2xl font-montserrat"
                            style="background: #00a651; color: white;"
                            onmouseover="this.style.background='#15803d'"
                            onmouseout="this.style.background='#00a651'">
                            <i class="fas fa-rocket mr-3"></i>
                            Start Your Journey
                        </a>
                        <a href="#how-it-works" 
                            class="inline-flex items-center justify-center px-8 py-4 rounded-xl font-bold text-lg border-2 border-white/30 backdrop-blur-sm transition-all duration-300 hover:bg-white/10 font-montserrat text-white">
                            <i class="fas fa-play-circle mr-3"></i>
                            Learn How
                        </a>
                    </div>
                    
                    <div class="flex items-center space-x-6 text-sm font-lato">
                        <div class="flex items-center">
                            <i class="fas fa-clock mr-2" style="color: #00a651;"></i>
                            <span>5-7 day review process</span>
                        </div>
                        <div class="flex items-center">
                            <i class="fas fa-globe mr-2" style="color: #00a651;"></i>
                            <span>190+ countries reach</span>
                        </div>
                    </div>
                </div>
                
                <!-- Right Content - Stats Cards -->
                <div class="relative" data-aos="fade-left" data-aos-duration="1000" data-aos-delay="200">
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                        <!-- Stat Card 1 -->
                        <div class="p-6 rounded-2xl backdrop-blur-md border border-white/20" 
                            style="background: rgba(255, 255, 255, 0.1);" data-aos="zoom-in" data-aos-delay="300">
                            <div class="flex items-center justify-between mb-4">
                                <div class="w-12 h-12 rounded-xl flex items-center justify-center" 
                                    style="background: rgba(0, 166, 81, 0.2);">
                                    <i class="fas fa-dollar-sign text-2xl" style="color: #00a651;"></i>
                                </div>
                            </div>
                            <h3 class="text-2xl font-bold mb-2 font-montserrat">$10K+</h3>
                            <p class="text-gray-200 font-lato">Top instructors earn monthly</p>
                        </div>
                        
                        <!-- Stat Card 2 -->
                        <div class="p-6 rounded-2xl backdrop-blur-md border border-white/20" 
                            style="background: rgba(255, 255, 255, 0.1);" data-aos="zoom-in" data-aos-delay="400">
                            <div class="flex items-center justify-between mb-4">
                                <div class="w-12 h-12 rounded-xl flex items-center justify-center" 
                                    style="background: rgba(237, 28, 36, 0.2);">
                                    <i class="fas fa-users text-2xl" style="color: #ed1c24;"></i>
                                </div>
                            </div>
                            <h3 class="text-2xl font-bold mb-2 font-montserrat">50K+</h3>
                            <p class="text-gray-200 font-lato">Students worldwide</p>
                        </div>
                        
                        <!-- Stat Card 3 -->
                        <div class="p-6 rounded-2xl backdrop-blur-md border border-white/20" 
                            style="background: rgba(255, 255, 255, 0.1);" data-aos="zoom-in" data-aos-delay="500">
                            <div class="flex items-center justify-between mb-4">
                                <div class="w-12 h-12 rounded-xl flex items-center justify-center" 
                                    style="background: rgba(0, 166, 81, 0.2);">
                                    <i class="fas fa-star text-2xl" style="color: #00a651;"></i>
                                </div>
                            </div>
                            <h3 class="text-2xl font-bold mb-2 font-montserrat">4.8/5</h3>
                            <p class="text-gray-200 font-lato">Average rating</p>
                        </div>
                        
                        <!-- Stat Card 4 -->
                        <div class="p-6 rounded-2xl backdrop-blur-md border border-white/20" 
                            style="background: rgba(255, 255, 255, 0.1);" data-aos="zoom-in" data-aos-delay="600">
                            <div class="flex items-center justify-between mb-4">
                                <div class="w-12 h-12 rounded-xl flex items-center justify-center" 
                                    style="background: rgba(237, 28, 36, 0.2);">
                                    <i class="fas fa-percentage text-2xl" style="color: #ed1c24;"></i>
                                </div>
                            </div>
                            <h3 class="text-2xl font-bold mb-2 font-montserrat">70%</h3>
                            <p class="text-gray-200 font-lato">Revenue share</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    
    <style>
        @keyframes float {
            0%, 100% { transform: translateY(0px) rotate(0deg); }
            50% { transform: translateY(-20px) rotate(5deg); }
        }
    </style>

    <!-- Enhanced How It Works -->
    <section id="how-it-works" class="py-20 bg-gradient-to-br from-gray-50 to-white">
        <div class="container mx-auto px-4">
            <div class="text-center mb-16" data-aos="fade-up">
                <div class="inline-flex items-center px-4 py-2 rounded-full mb-6" 
                    style="background: rgba(0, 33, 71, 0.1);">
                    <i class="fas fa-route mr-2" style="color: #002147;"></i>
                    <span class="text-sm font-semibold font-montserrat" style="color: #002147;">Simple Process</span>
                </div>
                <h2 class="text-3xl md:text-4xl lg:text-5xl font-bold mb-4 font-montserrat" style="color: #002147;">
                    How to Become an Instructor
                </h2>
                <p class="text-gray-600 text-lg max-w-2xl mx-auto font-lato">
                    Just 6 simple steps. No technical skills needed — we'll guide you every step of the way.
                </p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8 max-w-7xl mx-auto">
                @foreach ([
                    ['step' => 1, 'title' => 'Create Your Account', 'desc' => 'No account needed to start—just enter your email below. Already have an account? Scroll to the application section and send your link!', 'icon' => 'user-plus'], 
                    ['step' => 2, 'title' => 'Start Instructor Application', 'desc' => 'Click "Become an Instructor" or scroll down to the application form to begin.', 'icon' => 'edit'], 
                    ['step' => 3, 'title' => 'Check Your Email', 'desc' => 'We\'ll send a secure link to finish your profile.', 'icon' => 'envelope'], 
                    ['step' => 4, 'title' => 'Complete Your Profile', 'desc' => 'Add your bio, expertise, and intro video.', 'icon' => 'user-cog'], 
                    ['step' => 5, 'title' => 'Submit & Get Confirmation', 'desc' => 'You\'ll get an email confirming receipt.', 'icon' => 'check-circle'], 
                    ['step' => 6, 'title' => 'Wait for Review', 'desc' => 'Our team reviews in 5–7 business days.', 'icon' => 'clock']
                ] as $stepData)
                    <div class="step-card group relative" data-aos="fade-up" data-aos-delay="{{ $stepData['step'] * 100 }}">
                        <!-- Connection Line (hidden on small screens) -->
                        @if($stepData['step'] < 6)
                            <div class="hidden lg:block absolute top-8 left-full w-8 h-0.5 z-0" 
                                style="background: linear-gradient(to right, #00a651, transparent);"></div>
                        @endif
                        
                        <div class="relative bg-white p-8 rounded-2xl shadow-lg hover:shadow-2xl transition-all duration-500 hover:-translate-y-2 border border-gray-100 group-hover:border-gray-200">
                            <!-- Step Number Badge -->
                            <div class="absolute -top-4 -left-4 w-12 h-12 rounded-xl flex items-center justify-center font-bold text-white text-lg shadow-lg" 
                                style="background: linear-gradient(135deg, #00a651 0%, #15803d 100%);">
                                {{ $stepData['step'] }}
                            </div>
                            
                            <!-- Icon -->
                            <div class="w-16 h-16 rounded-2xl flex items-center justify-center mb-6 transition-all duration-300 group-hover:scale-110" 
                                style="background: linear-gradient(135deg, rgba(0, 33, 71, 0.1) 0%, rgba(0, 166, 81, 0.1) 100%);">
                                <i class="fas fa-{{ $stepData['icon'] }} text-2xl" style="color: #002147;"></i>
                            </div>
                            
                            <!-- Content -->
                            <h3 class="text-xl font-bold mb-4 font-montserrat group-hover:text-green-600 transition-colors" style="color: #002147;">
                                {{ $stepData['title'] }}
                            </h3>
                            <p class="text-gray-600 leading-relaxed font-lato">{{ $stepData['desc'] }}</p>
                            
                            <!-- Hover Effect -->
                            <div class="absolute inset-0 rounded-2xl opacity-0 group-hover:opacity-100 transition-all duration-300 pointer-events-none" 
                                style="background: linear-gradient(135deg, rgba(0, 166, 81, 0.02) 0%, rgba(0, 33, 71, 0.02) 100%);"></div>
                        </div>
                    </div>
                @endforeach
            </div>

            <div class="text-center mt-16" data-aos="fade-up" data-aos-delay="700">
                <div class="bg-white p-8 rounded-2xl shadow-lg max-w-md mx-auto border border-gray-100">
                    <div class="w-16 h-16 rounded-full flex items-center justify-center mx-auto mb-4" 
                        style="background: rgba(237, 28, 36, 0.1);">
                        <i class="fas fa-question-circle text-2xl" style="color: #ed1c24;"></i>
                    </div>
                    <p class="text-gray-600 mb-4 font-lato">Have questions about the process?</p>
                    <a href="#faq" class="inline-flex items-center px-6 py-3 rounded-xl font-semibold transition-all duration-300 hover:shadow-lg transform hover:scale-105 font-montserrat"
                        style="background: #ed1c24; color: white;"
                        onmouseover="this.style.background='#dc2626'"
                        onmouseout="this.style.background='#ed1c24'">
                        <i class="fas fa-arrow-down mr-2"></i>
                        See our FAQ
                    </a>
                </div>
            </div>
        </div>
    </section>

    <!-- Enhanced Benefits -->
    <section class="py-20 relative overflow-hidden" style="background: linear-gradient(135deg, #002147 0%, #003875 100%);">
        <!-- Background Pattern -->
        <div class="absolute inset-0 opacity-5">
            <div class="absolute inset-0" style="background-image: url('{{ asset('images/pattern-bg.jpg') }}'); background-size: cover;"></div>
        </div>
        
        <div class="container mx-auto px-4 relative">
            <div class="text-center mb-16" data-aos="fade-up">
                <div class="inline-flex items-center px-4 py-2 rounded-full mb-6" 
                    style="background: rgba(0, 166, 81, 0.2); backdrop-filter: blur(10px);">
                    <i class="fas fa-heart mr-2" style="color: #00a651;"></i>
                    <span class="text-sm font-semibold font-montserrat" style="color: #00a651;">Instructor Benefits</span>
                </div>
                <h2 class="text-3xl md:text-4xl lg:text-5xl font-bold text-white mb-4 font-montserrat">
                    Why Instructors Love Teaching With Us
                </h2>
                <p class="text-gray-200 text-lg max-w-2xl mx-auto font-lato">
                    Join thousands of successful instructors who have transformed their expertise into thriving businesses
                </p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-8 max-w-6xl mx-auto">
                <!-- Benefit 1 -->
                <div class="benefit-card group" data-aos="zoom-in" data-aos-delay="200">
                    <div class="bg-white/10 backdrop-blur-md p-8 rounded-2xl text-center border border-white/20 hover:bg-white/15 transition-all duration-500 hover:-translate-y-3 hover:shadow-2xl">
                        <div class="w-20 h-20 rounded-2xl flex items-center justify-center mx-auto mb-6 transition-all duration-300 group-hover:scale-110" 
                            style="background: rgba(0, 166, 81, 0.2);">
                            <i class="fas fa-dollar-sign text-3xl" style="color: #00a651;"></i>
                        </div>
                        <h3 class="text-2xl font-bold text-white mb-4 font-montserrat group-hover:text-green-300 transition-colors">
                            Earn Meaningful Income
                        </h3>
                        <p class="text-gray-200 leading-relaxed font-lato mb-6">
                            Inspire learners, advance careers, and earn meaningful income by sharing your expertise with a global audience.
                        </p>
                        <div class="flex items-center justify-center space-x-4 text-sm">
                            <div class="flex items-center">
                                <i class="fas fa-percentage mr-1" style="color: #00a651;"></i>
                                <span class="text-green-300 font-semibold">70% revenue share</span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Benefit 2 -->
                <div class="benefit-card group" data-aos="zoom-in" data-aos-delay="400">
                    <div class="bg-white/10 backdrop-blur-md p-8 rounded-2xl text-center border border-white/20 hover:bg-white/15 transition-all duration-500 hover:-translate-y-3 hover:shadow-2xl">
                        <div class="w-20 h-20 rounded-2xl flex items-center justify-center mx-auto mb-6 transition-all duration-300 group-hover:scale-110" 
                            style="background: rgba(237, 28, 36, 0.2);">
                            <i class="fas fa-globe-americas text-3xl" style="color: #ed1c24;"></i>
                        </div>
                        <h3 class="text-2xl font-bold text-white mb-4 font-montserrat group-hover:text-red-300 transition-colors">
                            Global Audience
                        </h3>
                        <p class="text-gray-200 leading-relaxed font-lato mb-6">
                            Reach learners in 190+ countries and build your personal brand while making a worldwide impact.
                        </p>
                        <div class="flex items-center justify-center space-x-4 text-sm">
                            <div class="flex items-center">
                                <i class="fas fa-users mr-1" style="color: #ed1c24;"></i>
                                <span class="text-red-300 font-semibold">50K+ students</span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Benefit 3 -->
                <div class="benefit-card group" data-aos="zoom-in" data-aos-delay="600">
                    <div class="bg-white/10 backdrop-blur-md p-8 rounded-2xl text-center border border-white/20 hover:bg-white/15 transition-all duration-500 hover:-translate-y-3 hover:shadow-2xl">
                        <div class="w-20 h-20 rounded-2xl flex items-center justify-center mx-auto mb-6 transition-all duration-300 group-hover:scale-110" 
                            style="background: rgba(0, 166, 81, 0.2);">
                            <i class="fas fa-headset text-3xl" style="color: #00a651;"></i>
                        </div>
                        <h3 class="text-2xl font-bold text-white mb-4 font-montserrat group-hover:text-green-300 transition-colors">
                            Full Support & Tools
                        </h3>
                        <p class="text-gray-200 leading-relaxed font-lato mb-6">
                            From course design to marketing — we provide comprehensive support and cutting-edge tools for your success.
                        </p>
                        <div class="flex items-center justify-center space-x-4 text-sm">
                            <div class="flex items-center">
                                <i class="fas fa-tools mr-1" style="color: #00a651;"></i>
                                <span class="text-green-300 font-semibold">24/7 support</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Additional Benefits Grid -->
            <div class="grid grid-cols-2 md:grid-cols-4 gap-6 mt-16 max-w-4xl mx-auto" data-aos="fade-up" data-aos-delay="800">
                <div class="text-center">
                    <div class="text-3xl font-bold text-white mb-2 font-montserrat" style="color: #00a651;">95%</div>
                    <p class="text-gray-300 text-sm font-lato">Instructor satisfaction</p>
                </div>
                <div class="text-center">
                    <div class="text-3xl font-bold text-white mb-2 font-montserrat" style="color: #ed1c24;">24/7</div>
                    <p class="text-gray-300 text-sm font-lato">Support available</p>
                </div>
                <div class="text-center">
                    <div class="text-3xl font-bold text-white mb-2 font-montserrat" style="color: #00a651;">$0</div>
                    <p class="text-gray-300 text-sm font-lato">Setup fees</p>
                </div>
                <div class="text-center">
                    <div class="text-3xl font-bold text-white mb-2 font-montserrat" style="color: #ed1c24;">∞</div>
                    <p class="text-gray-300 text-sm font-lato">Earning potential</p>
                </div>
            </div>
        </div>
    </section>

    <!-- Enhanced Application CTA -->
    <section id="apply-now" class="py-20 bg-gradient-to-br from-gray-50 to-white">
        <div class="container mx-auto px-4">
            <div class="max-w-5xl mx-auto">
                <div class="bg-white rounded-3xl shadow-2xl overflow-hidden border border-gray-100" data-aos="zoom-in">
                    <!-- Header Section -->
                    <div class="relative p-8 md:p-12 text-center" 
                        style="background: linear-gradient(135deg, #002147 0%, #003875 100%);">
                        <div class="absolute inset-0 opacity-10">
                            <div class="absolute inset-0" style="background-image: url('{{ asset('images/pattern-bg.jpg') }}'); background-size: cover;"></div>
                        </div>
                        
                        <div class="relative z-10">
                            <div class="w-20 h-20 rounded-2xl flex items-center justify-center mx-auto mb-6" 
                                style="background: rgba(0, 166, 81, 0.2); backdrop-filter: blur(10px);">
                                <i class="fas fa-graduation-cap text-3xl" style="color: #00a651;"></i>
                            </div>
                            
                            <h2 class="text-3xl md:text-4xl font-bold text-white mb-4 font-montserrat">
                                Ready to Inspire Learners?
                            </h2>
                            <p class="text-gray-200 text-lg max-w-2xl mx-auto font-lato">
                                Join thousands of successful instructors. Get your secure application link and start your teaching journey today.
                            </p>
                        </div>
                    </div>

                    <!-- Form Section -->
                    <div class="p-8 md:p-12">
                        <form method="POST" action="{{ route('instructors.start-application') }}" class="max-w-md mx-auto">
                            @csrf
                            @auth
                                <input type="hidden" name="email" value="{{ auth()->user()->email }}">
                                <div class="p-6 rounded-2xl mb-8 border-2 border-dashed" 
                                    style="border-color: #00a651; background: rgba(0, 166, 81, 0.05);">
                                    <div class="flex items-center justify-center mb-3">
                                        <i class="fas fa-user-check text-2xl" style="color: #00a651;"></i>
                                    </div>
                                    <p class="text-center font-lato" style="color: #002147;">
                                        You're logged in as <strong style="color: #00a651;">{{ auth()->user()->email }}</strong>
                                    </p>
                                </div>
                            @endauth

                            @guest
                                <div class="space-y-6 mb-8">
                                    <div>
                                        <label class="block text-sm font-semibold mb-2 font-montserrat" style="color: #002147;">
                                            <i class="fas fa-user mr-2" style="color: #00a651;"></i>Full Name
                                        </label>
                                        <input type="text" name="name" required
                                            class="w-full px-4 py-4 rounded-xl border-2 border-gray-200 focus:outline-none transition-all duration-300 font-lato"
                                            style="focus:border-color: #00a651;"
                                            placeholder="Enter your full name"
                                            onfocus="this.style.borderColor='#00a651'"
                                            onblur="this.style.borderColor='#e5e7eb'">
                                        @error('name')
                                            <div class="text-red-500 text-sm mt-2 flex items-center">
                                                <i class="fas fa-exclamation-circle mr-1"></i>
                                                {{ $message }}
                                            </div>
                                        @enderror
                                    </div>
                                    
                                    <div>
                                        <label class="block text-sm font-semibold mb-2 font-montserrat" style="color: #002147;">
                                            <i class="fas fa-envelope mr-2" style="color: #ed1c24;"></i>Email Address
                                        </label>
                                        <input type="email" name="email" required
                                            class="w-full px-4 py-4 rounded-xl border-2 border-gray-200 focus:outline-none transition-all duration-300 font-lato"
                                            placeholder="your.email@example.com"
                                            onfocus="this.style.borderColor='#ed1c24'"
                                            onblur="this.style.borderColor='#e5e7eb'">
                                        @error('email')
                                            <div class="text-red-500 text-sm mt-2 flex items-center">
                                                <i class="fas fa-exclamation-circle mr-1"></i>
                                                {{ $message }}
                                            </div>
                                        @enderror
                                    </div>
                                </div>
                            @endguest

                            <button type="submit" 
                                class="w-full py-4 px-8 rounded-xl font-bold text-lg text-white transition-all duration-300 transform hover:scale-105 hover:shadow-xl font-montserrat flex items-center justify-center gap-3"
                                style="background: linear-gradient(135deg, #00a651 0%, #15803d 100%);"
                                onmouseover="this.style.transform='scale(1.05)'; this.style.boxShadow='0 20px 40px rgba(0, 166, 81, 0.3)'"
                                onmouseout="this.style.transform='scale(1)'; this.style.boxShadow='0 10px 30px rgba(0, 166, 81, 0.2)'">
                                <i class="fas fa-paper-plane"></i>
                                Send My Application Link
                            </button>

                            <!-- Trust Indicators -->
                            <div class="mt-8 space-y-4">
                                <div class="flex items-center justify-center space-x-6 text-sm text-gray-500">
                                    <div class="flex items-center">
                                        <i class="fas fa-shield-alt mr-1" style="color: #00a651;"></i>
                                        <span>Secure Process</span>
                                    </div>
                                    <div class="flex items-center">
                                        <i class="fas fa-clock mr-1" style="color: #ed1c24;"></i>
                                        <span>5-7 Day Review</span>
                                    </div>
                                </div>
                                
                                <p class="text-center text-gray-500 text-sm font-lato">
                                    By applying, you agree to our 
                                    <a href="#" class="font-medium hover:underline" style="color: #002147;">Instructor Terms</a> and 
                                    <a href="#" class="font-medium hover:underline" style="color: #002147;">Privacy Policy</a>.
                                    <br>Check your spam folder if you don't receive our email.
                                </p>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Enhanced Testimonials -->
    <section class="py-20 bg-white">
        <div class="container mx-auto px-4">
            <div class="text-center mb-16" data-aos="fade-up">
                <div class="inline-flex items-center px-4 py-2 rounded-full mb-6" 
                    style="background: rgba(237, 28, 36, 0.1);">
                    <i class="fas fa-quote-left mr-2" style="color: #ed1c24;"></i>
                    <span class="text-sm font-semibold font-montserrat" style="color: #ed1c24;">Success Stories</span>
                </div>
                <h2 class="text-3xl md:text-4xl lg:text-5xl font-bold mb-4 font-montserrat" style="color: #002147;">
                    Instructor Success Stories
                </h2>
                <p class="text-gray-600 text-lg max-w-2xl mx-auto font-lato">
                    Hear from successful instructors who have transformed their expertise into thriving careers
                </p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8 max-w-6xl mx-auto">
                <!-- Testimonial 1 -->
                <div class="group" data-aos="fade-up" data-aos-delay="200">
                    <div class="bg-white p-8 rounded-2xl shadow-lg hover:shadow-2xl transition-all duration-500 border border-gray-100 group-hover:border-gray-200 h-full">
                        <!-- Rating -->
                        <div class="flex items-center mb-4">
                            @for($i = 0; $i < 5; $i++)
                                <i class="fas fa-star text-yellow-400"></i>
                            @endfor
                        </div>
                        
                        <!-- Quote -->
                        <blockquote class="text-gray-700 italic mb-6 font-lato leading-relaxed">
                            "Teaching here changed my career completely. I now reach students in 30+ countries and earn more than my previous full-time corporate job. The support team is incredible!"
                        </blockquote>
                        
                        <!-- Author -->
                        <div class="flex items-center">
                            <img src="https://randomuser.me/api/portraits/women/42.jpg" alt="Sarah Johnson"
                                class="w-16 h-16 rounded-full object-cover mr-4 border-4 border-gray-100">
                            <div>
                                <h4 class="text-lg font-bold font-montserrat" style="color: #002147;">Sarah Johnson</h4>
                                <div class="text-gray-500 text-sm font-lato">Data Science Instructor</div>
                                <div class="flex items-center mt-1">
                                    <i class="fas fa-users text-xs mr-1" style="color: #00a651;"></i>
                                    <span class="text-xs" style="color: #00a651;">12,000+ students</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Testimonial 2 -->
                <div class="group" data-aos="fade-up" data-aos-delay="400">
                    <div class="bg-white p-8 rounded-2xl shadow-lg hover:shadow-2xl transition-all duration-500 border border-gray-100 group-hover:border-gray-200 h-full">
                        <!-- Rating -->
                        <div class="flex items-center mb-4">
                            @for($i = 0; $i < 5; $i++)
                                <i class="fas fa-star text-yellow-400"></i>
                            @endfor
                        </div>
                        
                        <!-- Quote -->
                        <blockquote class="text-gray-700 italic mb-6 font-lato leading-relaxed">
                            "The platform made it incredibly easy to create professional courses. The tools are intuitive, and now I teach full-time doing what I'm passionate about."
                        </blockquote>
                        
                        <!-- Author -->
                        <div class="flex items-center">
                            <img src="https://randomuser.me/api/portraits/men/32.jpg" alt="Michael Chen"
                                class="w-16 h-16 rounded-full object-cover mr-4 border-4 border-gray-100">
                            <div>
                                <h4 class="text-lg font-bold font-montserrat" style="color: #002147;">Michael Chen</h4>
                                <div class="text-gray-500 text-sm font-lato">Web Development Instructor</div>
                                <div class="flex items-center mt-1">
                                    <i class="fas fa-users text-xs mr-1" style="color: #00a651;"></i>
                                    <span class="text-xs" style="color: #00a651;">8,500+ students</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Testimonial 3 -->
                <div class="group" data-aos="fade-up" data-aos-delay="600">
                    <div class="bg-white p-8 rounded-2xl shadow-lg hover:shadow-2xl transition-all duration-500 border border-gray-100 group-hover:border-gray-200 h-full">
                        <!-- Rating -->
                        <div class="flex items-center mb-4">
                            @for($i = 0; $i < 5; $i++)
                                <i class="fas fa-star text-yellow-400"></i>
                            @endfor
                        </div>
                        
                        <!-- Quote -->
                        <blockquote class="text-gray-700 italic mb-6 font-lato leading-relaxed">
                            "From idea to launch in just 3 weeks! The community support and mentorship program helped me create my first course. Already planning my second one!"
                        </blockquote>
                        
                        <!-- Author -->
                        <div class="flex items-center">
                            <img src="https://randomuser.me/api/portraits/women/68.jpg" alt="Priya Patel"
                                class="w-16 h-16 rounded-full object-cover mr-4 border-4 border-gray-100">
                            <div>
                                <h4 class="text-lg font-bold font-montserrat" style="color: #002147;">Priya Patel</h4>
                                <div class="text-gray-500 text-sm font-lato">Marketing Strategy Instructor</div>
                                <div class="flex items-center mt-1">
                                    <i class="fas fa-users text-xs mr-1" style="color: #00a651;"></i>
                                    <span class="text-xs" style="color: #00a651;">3,200+ students</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Call to Action -->
            <div class="text-center mt-16" data-aos="fade-up" data-aos-delay="800">
                <div class="bg-gradient-to-r from-gray-50 to-gray-100 p-8 rounded-2xl max-w-2xl mx-auto">
                    <h3 class="text-2xl font-bold mb-4 font-montserrat" style="color: #002147;">Ready to Share Your Success Story?</h3>
                    <p class="text-gray-600 mb-6 font-lato">Join thousands of successful instructors who are making a difference</p>
                    <a href="#apply-now" 
                        class="inline-flex items-center px-8 py-4 rounded-xl font-bold text-white transition-all duration-300 hover:shadow-lg transform hover:scale-105 font-montserrat"
                        style="background: #ed1c24;"
                        onmouseover="this.style.background='#dc2626'"
                        onmouseout="this.style.background='#ed1c24'">
                        <i class="fas fa-arrow-up mr-2"></i>
                        Apply Now
                    </a>
                </div>
            </div>
        </div>
    </section>

    <!-- Enhanced FAQ -->
    <section id="faq" class="py-20 bg-gradient-to-br from-gray-50 to-white">
        <div class="container mx-auto px-4 max-w-4xl">
            <div class="text-center mb-16" data-aos="fade-up">
                <div class="inline-flex items-center px-4 py-2 rounded-full mb-6" 
                    style="background: rgba(0, 33, 71, 0.1);">
                    <i class="fas fa-question-circle mr-2" style="color: #002147;"></i>
                    <span class="text-sm font-semibold font-montserrat" style="color: #002147;">Got Questions?</span>
                </div>
                <h2 class="text-3xl md:text-4xl lg:text-5xl font-bold mb-4 font-montserrat" style="color: #002147;">
                    Frequently Asked Questions
                </h2>
                <p class="text-gray-600 text-lg max-w-2xl mx-auto font-lato">
                    Everything you need to know about becoming an instructor with us
                </p>
            </div>

            <div class="space-y-6">
                @foreach ([
                    ['title' => 'What qualifications do I need?', 'body' => 'We look for real-world expertise—whether from professional work, previous teaching experience, or significant projects. While degrees and certifications are helpful, they\'re not always required. What matters most is your ability to teach and share valuable knowledge.', 'icon' => 'graduation-cap'], 
                    ['title' => 'How much can I earn?', 'body' => 'Top instructors earn $10K+/month, but earnings vary based on course quality, demand, and marketing efforts. We offer a generous 70% revenue share, meaning you keep the majority of what you earn. Many instructors start part-time and grow into full-time income.', 'icon' => 'dollar-sign'], 
                    ['title' => 'How long is the review process?', 'body' => 'We review applications within 5–7 business days. You\'ll receive email updates at every stage of the process, including detailed feedback if we need additional information. Our team reviews each application thoroughly to ensure the best fit.', 'icon' => 'clock'], 
                    ['title' => 'What if I\'m not approved?', 'body' => 'Don\'t worry! We provide personalized feedback explaining areas for improvement. You can reapply after 30 days with updates based on our feedback. Many of our most successful instructors weren\'t accepted on their first try but improved and came back stronger.', 'icon' => 'redo-alt'],
                    ['title' => 'What support do you provide?', 'body' => 'We offer comprehensive support including course creation guidance, marketing tools, technical assistance, and ongoing mentorship. Our instructor success team is available 24/7 to help you succeed and grow your teaching business.', 'icon' => 'headset'],
                    ['title' => 'Do I need technical skills?', 'body' => 'Not at all! Our platform is designed to be user-friendly for instructors of all technical levels. We provide step-by-step guides, video tutorials, and personal support to help you create professional courses without any technical expertise.', 'icon' => 'tools']
                ] as $index => $faq)
                    <div class="faq-item group" data-aos="fade-up" data-aos-delay="{{ $index * 100 }}">
                        <div class="bg-white rounded-2xl shadow-lg border border-gray-100 overflow-hidden hover:shadow-xl transition-all duration-300">
                            <button
                                class="faq-question w-full px-8 py-6 text-left font-semibold hover:bg-gray-50 transition-all duration-300 flex justify-between items-center group"
                                onclick="toggleFAQ({{ $index }})">
                                <div class="flex items-center">
                                    <div class="w-12 h-12 rounded-xl flex items-center justify-center mr-4 transition-all duration-300" 
                                        style="background: rgba(0, 166, 81, 0.1);" id="faq-icon-bg-{{ $index }}">
                                        <i class="fas fa-{{ $faq['icon'] }} text-lg transition-all duration-300" 
                                            style="color: #00a651;" id="faq-main-icon-{{ $index }}"></i>
                                    </div>
                                    <span class="text-lg font-montserrat transition-colors duration-300" 
                                        style="color: #002147;" id="faq-title-{{ $index }}">
                                        {{ $faq['title'] }}
                                    </span>
                                </div>
                                <i class="fas fa-chevron-down text-lg transition-transform duration-300 group-hover:scale-110" 
                                    style="color: #00a651;" id="faq-chevron-{{ $index }}"></i>
                            </button>
                            <div id="faq-answer-{{ $index }}"
                                class="faq-answer px-8 pb-6 text-gray-600 border-t border-gray-100 hidden">
                                <div class="pt-4 font-lato leading-relaxed">{{ $faq['body'] }}</div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>

            <!-- Additional Help -->
            <div class="text-center mt-16" data-aos="fade-up" data-aos-delay="700">
                <div class="bg-white p-8 rounded-2xl shadow-lg border border-gray-100 max-w-2xl mx-auto">
                    <div class="w-16 h-16 rounded-full flex items-center justify-center mx-auto mb-6" 
                        style="background: rgba(237, 28, 36, 0.1);">
                        <i class="fas fa-comments text-2xl" style="color: #ed1c24;"></i>
                    </div>
                    <h3 class="text-xl font-bold mb-4 font-montserrat" style="color: #002147;">Still Have Questions?</h3>
                    <p class="text-gray-600 mb-6 font-lato">Our instructor success team is here to help you get started</p>
                    <div class="flex flex-col sm:flex-row gap-4 justify-center">
                        <a href="mailto:instructors@example.com" 
                            class="inline-flex items-center px-6 py-3 rounded-xl font-semibold transition-all duration-300 hover:shadow-lg font-montserrat"
                            style="background: #002147; color: white;"
                            onmouseover="this.style.background='#003875'"
                            onmouseout="this.style.background='#002147'">
                            <i class="fas fa-envelope mr-2"></i>
                            Email Us
                        </a>
                        <a href="#apply-now" 
                            class="inline-flex items-center px-6 py-3 rounded-xl font-semibold border-2 transition-all duration-300 hover:bg-gray-50 font-montserrat"
                            style="color: #00a651; border-color: #00a651;">
                            <i class="fas fa-rocket mr-2"></i>
                            Apply Now
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- AOS Animation Library -->
    <link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet">
    <script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>

    <script>
        // Initialize AOS
        document.addEventListener('DOMContentLoaded', function() {
            AOS.init({
                duration: 800,
                easing: 'ease-out-cubic',
                once: true,
                offset: 100
            });
        });

        // FAQ toggle functionality with enhanced animations
        function toggleFAQ(index) {
            const answer = document.getElementById(`faq-answer-${index}`);
            const chevron = document.getElementById(`faq-chevron-${index}`);
            const iconBg = document.getElementById(`faq-icon-bg-${index}`);
            const mainIcon = document.getElementById(`faq-main-icon-${index}`);
            const title = document.getElementById(`faq-title-${index}`);

            if (answer.classList.contains('hidden')) {
                // Open
                answer.classList.remove('hidden');
                chevron.style.transform = 'rotate(180deg)';
                iconBg.style.background = 'rgba(237, 28, 36, 0.1)';
                mainIcon.style.color = '#ed1c24';
                title.style.color = '#ed1c24';
            } else {
                // Close
                answer.classList.add('hidden');
                chevron.style.transform = 'rotate(0deg)';
                iconBg.style.background = 'rgba(0, 166, 81, 0.1)';
                mainIcon.style.color = '#00a651';
                title.style.color = '#002147';
            }
        }
    </script>
</x-guest-layout>
