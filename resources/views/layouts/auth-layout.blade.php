@props(['title' => null, 'description' => null])
<!DOCTYPE html>
<html lang="en" data-theme="light">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $pageTitle ?? "BLI Auth" }}</title>
    <link rel="icon" type="image/png" href="{{ asset('images/logo.jpg') }}">
    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link
        href="https://fonts.googleapis.com/css2?family=Montserrat:wght@300;400;500;600;700&family=Lato:wght@300;400;700&display=swap"
        rel="stylesheet">

    <!-- Styles and Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <!-- AOS Animation Library -->
    <link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet">

    <!-- Modern Auth Styles -->
    <style>
        /* Ensure Lucide icons are visible and properly styled */
        [data-lucide] {
            display: inline-block !important;
            stroke-width: 2 !important;
            stroke: currentColor !important;
            fill: none !important;
            color: inherit !important;
            opacity: 1 !important;
            visibility: visible !important;
            width: 1em !important;
            height: 1em !important;
        }

        /* Enhanced visibility for input icons */
        .input-icon [data-lucide] {
            opacity: 0.7 !important;
            min-width: 1.25rem !important;
            min-height: 1.25rem !important;
        }

        /* Password toggle button styling */
        .password-toggle {
            background: transparent !important;
            border: none !important;
            padding: 0.25rem !important;
        }

        .password-toggle:hover {
            background: rgba(0, 0, 0, 0.05) !important;
        }

        /* Hero section background */
        .auth-hero {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            position: relative;
            overflow: hidden;
        }

        /* Fixed positioning for large screens */
        @media (min-width: 1024px) {
            .auth-hero {
                position: fixed !important;
                left: 0;
                top: 0;
                width: 50% !important;
                height: 100vh !important;
                z-index: 10;
            }
        }

        .auth-hero::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: url('data:image/svg+xml,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 100 100"><defs><pattern id="grain" width="100" height="100" patternUnits="userSpaceOnUse"><circle cx="20" cy="20" r="1" fill="white" opacity="0.1"/><circle cx="80" cy="80" r="1" fill="white" opacity="0.1"/><circle cx="40" cy="70" r="1" fill="white" opacity="0.05"/><circle cx="90" cy="30" r="1" fill="white" opacity="0.1"/><circle cx="10" cy="90" r="1" fill="white" opacity="0.05"/></pattern></defs><rect width="100" height="100" fill="url(%23grain)"/></svg>');
        }

        /* Form side styling */
        .auth-form-side {
            background: #ffffff;
            min-height: 100vh;
        }

        /* Custom scrollbar for form side */
        .auth-form-side::-webkit-scrollbar {
            width: 6px;
        }

        .auth-form-side::-webkit-scrollbar-track {
            background: #f1f5f9;
        }

        .auth-form-side::-webkit-scrollbar-thumb {
            background: #cbd5e1;
            border-radius: 3px;
        }

        /* Animated elements in hero */
        .floating-element {
            animation: float 6s ease-in-out infinite;
        }

        .floating-element:nth-child(2) {
            animation-delay: -2s;
        }

        .floating-element:nth-child(3) {
            animation-delay: -4s;
        }

        @keyframes float {

            0%,
            100% {
                transform: translateY(0px);
            }

            50% {
                transform: translateY(-20px);
            }
        }

        /* Mobile responsiveness */
        @media (max-width: 1024px) {
            .auth-hero {
                display: none;
            }
        }
    </style>
</head>

<body class="min-h-screen bg-gray-50 antialiased">
    <!-- 2-Column Layout -->
    <div class="min-h-screen flex">

        <!-- Left Column - Hero Section (Hidden on mobile) -->
        <div class="hidden lg:flex lg:w-1/2 auth-hero relative">
            <!-- Floating Elements -->
            <div class="absolute inset-0 z-10">
                <div
                    class="floating-element absolute top-20 left-20 w-32 h-32 bg-white/10 rounded-full backdrop-blur-sm">
                </div>
                <div
                    class="floating-element absolute top-1/2 right-16 w-24 h-24 bg-white/20 rounded-lg backdrop-blur-sm transform rotate-12">
                </div>
                <div
                    class="floating-element absolute bottom-24 left-1/3 w-20 h-20 bg-white/15 rounded-full backdrop-blur-sm">
                </div>
            </div>

            <!-- Hero Content -->
            <div class="relative z-20 flex flex-col justify-center px-16 w-full">
                <div class="max-w-md" data-aos="fade-right" data-aos-duration="800">
                    <!-- Logo -->
                    <div class="mb-8">
                        <div class="flex items-center space-x-3 mb-6">
                            <div
                                class="w-12 h-12 bg-white/20 backdrop-blur-sm rounded-xl flex items-center justify-center">
                                <img src="{{ asset('images/logo.jpg') }}" alt="BLI Logo" class="w-8 h-8 rounded-lg">
                            </div>
                            <span class="text-white text-xl font-bold font-montserrat">BLI</span>
                        </div>
                    </div>

                    <!-- Hero Text -->
                    <h1 class="text-4xl lg:text-5xl font-bold text-white mb-6 font-montserrat leading-tight">
                        Transform Your Leadership Journey
                    </h1>
                    <p class="text-white/90 text-lg mb-8 font-lato leading-relaxed">
                        Join thousands of leaders who have elevated their skills and impact through our comprehensive
                        programs and community.
                    </p>

                    <!-- Features List -->
                    <div class="space-y-4">
                        <div class="flex items-center space-x-3 text-white/90">
                            <div
                                class="w-6 h-6 bg-white/20 rounded-full flex items-center justify-center backdrop-blur-sm">
                                <i data-lucide="check" class="w-3 h-3"></i>
                            </div>
                            <span class="font-lato">Expert-led workshops and courses</span>
                        </div>
                        <div class="flex items-center space-x-3 text-white/90">
                            <div
                                class="w-6 h-6 bg-white/20 rounded-full flex items-center justify-center backdrop-blur-sm">
                                <i data-lucide="check" class="w-3 h-3"></i>
                            </div>
                            <span class="font-lato">Global community of leaders</span>
                        </div>
                        <div class="flex items-center space-x-3 text-white/90">
                            <div
                                class="w-6 h-6 bg-white/20 rounded-full flex items-center justify-center backdrop-blur-sm">
                                <i data-lucide="check" class="w-3 h-3"></i>
                            </div>
                            <span class="font-lato">Personalized learning paths</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Right Column - Form Section -->
        <div class="w-full lg:w-1/2 lg:ml-auto auth-form-side flex flex-col">
            <!-- Mobile Logo (visible only on mobile) -->
            <div class="lg:hidden flex items-center justify-center pt-8 pb-6">
                <div class="flex items-center space-x-3">
                    <div class="w-10 h-10 bg-primary/10 rounded-xl flex items-center justify-center">
                        <img src="{{ asset('images/logo.jpg') }}" alt="BLI Logo" class="w-6 h-6 rounded-lg">
                    </div>
                    <span class="text-gray-900 text-lg font-bold font-montserrat">BLI</span>
                </div>
            </div>

            <!-- Form Container -->
            <div class="flex-1 flex items-center justify-center px-6 lg:px-16 py-8">
                <div class="w-full max-w-md lg:max-w-2xl">
                    <!-- Form Header -->
                    <div class="mb-8" data-aos="fade-up" data-aos-duration="600">
                        <h2 class="text-3xl font-bold text-gray-900 mb-2 font-montserrat">{{ $title ?? 'Welcome' }}</h2>
                        @if($description)
                            <p class="text-gray-600 font-lato">{{ $description }}</p>
                        @endif
                    </div>

                    <!-- Form Content -->
                    <div data-aos="fade-up" data-aos-duration="600" data-aos-delay="100">
                        {{ $slot }}
                    </div>
                </div>
            </div>

            <!-- Footer -->
            <div class="px-6 lg:px-16 py-6 border-t border-gray-100">
                <div class="flex flex-col sm:flex-row items-center justify-between space-y-4 sm:space-y-0">
                    <div class="flex items-center space-x-6 text-sm text-gray-500">
                        <a href="{{ route('homepage') }}"
                            class="hover:text-gray-900 transition-colors flex items-center space-x-1">
                            <i data-lucide="home" class="w-4 h-4"></i>
                            <span>Home</span>
                        </a>
                        <a href="#" class="hover:text-gray-900 transition-colors">Help</a>
                        <a href="#" class="hover:text-gray-900 transition-colors">Privacy</a>
                    </div>
                    <p class="text-xs text-gray-400 font-lato">
                        &copy; 2025 Beacon Leadership Institute
                    </p>
                </div>
            </div>
        </div>
    </div>

    <!-- Toast Notifications -->
    <x-toast /> <!-- Preload Scripts for faster loading -->
    <link rel="preload" href="https://unpkg.com/lucide@latest" as="script">

    <!-- Scripts -->
    <script src="https://unpkg.com/lucide@latest"></script>
    <script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>
    <script>
        // Function to initialize Lucide icons with error handling
        function initializeLucideIcons() {
            try {
                if (typeof lucide !== 'undefined' && lucide.createIcons) {
                    lucide.createIcons();
                }
            } catch (error) {
                console.warn('Lucide icon initialization failed:', error);
            }
        }

        // Immediate initialization (synchronous)
        initializeLucideIcons();

        // Initialize when DOM is ready
        document.addEventListener('DOMContentLoaded', function () {
            initializeLucideIcons();
        });

        // Early initialization before Alpine
        document.addEventListener('alpine:init', function () {
            initializeLucideIcons();
        });

        // Initialize after Alpine is ready
        document.addEventListener('alpine:initialized', function () {
            // Small delay to ensure Alpine has processed all x-data
            setTimeout(initializeLucideIcons, 50);
        });

        // Reinitialize icons after Alpine.js mutations
        document.addEventListener('alpine:updated', function () {
            // Delay to ensure DOM updates are complete
            setTimeout(initializeLucideIcons, 100);
        });

        // Initialize AOS with better settings
        document.addEventListener('DOMContentLoaded', function () {
            AOS.init({
                duration: 600,
                easing: 'ease-out-cubic',
                once: true,
                offset: 50,
                delay: 0,
                disable: false,
                startEvent: 'DOMContentLoaded',
                initClassName: 'aos-init',
                animatedClassName: 'aos-animate',
                useClassNames: false,
                disableMutationObserver: false,
                debounceDelay: 50,
                throttleDelay: 99
            });
        });

        // Initialize Lucide Icons immediately
        initializeLucideIcons();

        // Add custom animations
        const animationClasses = {
            'animation-delay-2000': 'animation-delay: 2s;',
            'animation-delay-4000': 'animation-delay: 4s;'
        };

        // Apply animation delays
        document.querySelectorAll('[class*="animation-delay"]').forEach(el => {
            const delayClass = Array.from(el.classList).find(cls => cls.includes('animation-delay'));
            if (delayClass && animationClasses[delayClass]) {
                el.style.cssText += animationClasses[delayClass];
            }
        });

        // Mutation observer to catch dynamically added icons
        if (typeof MutationObserver !== 'undefined') {
            const observer = new MutationObserver(function (mutations) {
                let shouldReinitialize = false;
                mutations.forEach(function (mutation) {
                    if (mutation.type === 'childList' && mutation.addedNodes.length > 0) {
                        mutation.addedNodes.forEach(function (node) {
                            if (node.nodeType === 1 && (node.hasAttribute('data-lucide') || node.querySelector('[data-lucide]'))) {
                                shouldReinitialize = true;
                            }
                        });
                    }
                    // Also check for attribute changes (like Alpine.js :data-lucide updates)
                    if (mutation.type === 'attributes' && mutation.attributeName === 'data-lucide') {
                        shouldReinitialize = true;
                    }
                });
                if (shouldReinitialize) {
                    setTimeout(initializeLucideIcons, 50);
                }
            });

            observer.observe(document.body, {
                childList: true,
                subtree: true,
                attributes: true,
                attributeFilter: ['data-lucide']
            });
        }

        // Window load event for final initialization
        window.addEventListener('load', function () {
            initializeLucideIcons();
        });

        // Immediate and repeated initialization to prevent flash
        let iconInitAttempts = 0;
        const forceInitInterval = setInterval(function () {
            iconInitAttempts++;
            initializeLucideIcons();
            if (iconInitAttempts >= 3) {
                clearInterval(forceInitInterval);
            }
        }, 200); // More frequent but shorter duration

        // Initialize immediately when script loads
        (function () {
            initializeLucideIcons();
        })();
    </script>
</body>

</html>