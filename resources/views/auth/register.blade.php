<x-auth-layout :title="'Create Account'" :description="'Start your leadership journey with us today'">
    <x-slot name="pageTitle">Register - Beacon Leadership Institute</x-slot>

    <div x-data="{ 
        processing: false,
        showPassword: false,
        showConfirmPassword: false
    }">

        <form method="POST" action="{{ route('register.store') }}" class="space-y-6" x-on:submit="processing = true">
            @csrf

            <!-- Personal Information Grid -->
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                <!-- Full Name -->
                <div class="space-y-2" data-aos="fade-up" data-aos-delay="100">
                    <label class="block text-sm font-medium text-gray-700 font-lato">Full Name</label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none input-icon">
                            <i data-lucide="user" class="h-5 w-5 text-gray-400"></i>
                        </div>
                        <input type="text" name="name" value="{{ old('name') }}" required
                            class="block w-full pl-10 pr-3 py-3 bg-gray-50 border border-gray-300 rounded-xl text-gray-900 placeholder-gray-500 focus:outline-none focus:ring-2 focus:ring-primary/20 focus:border-primary transition-all duration-200 font-lato"
                            placeholder="Enter your full name">
                    </div>
                    @error('name')
                        <p class="text-sm text-red-500 font-lato">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Email -->
                <div class="space-y-2" data-aos="fade-up" data-aos-delay="150">
                    <label class="block text-sm font-medium text-gray-700 font-lato">Email Address</label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none input-icon">
                            <i data-lucide="mail" class="h-5 w-5 text-gray-400"></i>
                        </div>
                        <input type="email" name="email" value="{{ old('email') }}" required
                            class="block w-full pl-10 pr-3 py-3 bg-gray-50 border border-gray-300 rounded-xl text-gray-900 placeholder-gray-500 focus:outline-none focus:ring-2 focus:ring-primary/20 focus:border-primary transition-all duration-200 font-lato"
                            placeholder="example@domain.com">
                    </div>
                    @error('email')
                        <p class="text-sm text-red-500 font-lato">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <!-- Phone Number (Full Width) -->
            <div class="space-y-2" data-aos="fade-up" data-aos-delay="200">
                <label class="block text-sm font-medium text-gray-700 font-lato">Phone Number</label>
                <div class="relative">
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none input-icon">
                        <i data-lucide="phone" class="h-5 w-5 text-gray-400"></i>
                    </div>
                    <input type="text" name="phone" value="{{ old('phone') }}"
                        class="block w-full pl-10 pr-3 py-3 bg-gray-50 border border-gray-300 rounded-xl text-gray-900 placeholder-gray-500 focus:outline-none focus:ring-2 focus:ring-primary/20 focus:border-primary transition-all duration-200 font-lato"
                        placeholder="+234 801 234 5678">
                </div>
                <p class="text-xs text-gray-500 font-lato">Enter your phone number (optional)</p>
                @error('phone')
                    <p class="text-sm text-red-500 font-lato">{{ $message }}</p>
                @enderror
            </div>

            <!-- Password Grid -->
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                <!-- Password -->
                <div class="space-y-2" data-aos="fade-up" data-aos-delay="250">
                    <label class="block text-sm font-medium text-gray-700 font-lato">Password</label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none input-icon">
                            <i data-lucide="lock" class="h-5 w-5 text-gray-400"></i>
                        </div>
                        <input :type="showPassword ? 'text' : 'password'" name="password" required
                            class="block w-full pl-10 pr-12 py-3 bg-gray-50 border border-gray-300 rounded-xl text-gray-900 placeholder-gray-500 focus:outline-none focus:ring-2 focus:ring-primary/20 focus:border-primary transition-all duration-200 font-lato"
                            placeholder="Enter a strong password">
                        <button type="button" @click="showPassword = !showPassword"
                            class="absolute inset-y-0 right-0 pr-3 flex items-center text-gray-400 hover:text-gray-600 transition-colors duration-200">
                            <i :data-lucide="showPassword ? 'eye-off' : 'eye'" class="h-4 w-4"></i>
                        </button>
                    </div>
                    @error('password')
                        <p class="text-sm text-red-500 font-lato">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Confirm Password -->
                <div class="space-y-2" data-aos="fade-up" data-aos-delay="300">
                    <label class="block text-sm font-medium text-gray-700 font-lato">Confirm Password</label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none input-icon">
                            <i data-lucide="lock" class="h-5 w-5 text-gray-400"></i>
                        </div>
                        <input :type="showConfirmPassword ? 'text' : 'password'" name="password_confirmation" required
                            class="block w-full pl-10 pr-12 py-3 bg-gray-50 border border-gray-300 rounded-xl text-gray-900 placeholder-gray-500 focus:outline-none focus:ring-2 focus:ring-primary/20 focus:border-primary transition-all duration-200 font-lato"
                            placeholder="Re-enter your password">
                        <button type="button" @click="showConfirmPassword = !showConfirmPassword"
                            class="absolute inset-y-0 right-0 pr-3 flex items-center text-gray-400 hover:text-gray-600 transition-colors duration-200">
                            <i :data-lucide="showConfirmPassword ? 'eye-off' : 'eye'" class="h-4 w-4"></i>
                        </button>
                    </div>
                    @error('password_confirmation')
                        <p class="text-sm text-red-500 font-lato">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <!-- Terms and Marketing -->
            <div class="space-y-4 bg-gray-50 p-6 rounded-xl border border-gray-200" data-aos="fade-up"
                data-aos-delay="350">
                <div class="flex items-start space-x-3">
                    <input type="checkbox" name="agree_terms" id="agree_terms" required
                        class="rounded border-gray-300 text-primary focus:ring-primary/20 focus:ring-offset-0 mt-1 flex-shrink-0">
                    <label for="agree_terms" class="text-sm text-gray-600 font-lato">
                        I agree to the
                        <a href="{{ route('privacy-policy') }}" target="_blank"
                            class="text-primary font-semibold underline underline-offset-2 hover:text-primary-600 transition-colors">Privacy
                            Policy</a>
                        and
                        <a href="{{ route('terms-of-service') }}" target="_blank"
                            class="text-primary font-semibold underline underline-offset-2 hover:text-primary-600 transition-colors">Terms
                            of Service</a>
                    </label>
                </div>

                <div class="flex items-start space-x-3">
                    <input type="checkbox" name="marketing_consent" id="marketing_consent"
                        class="rounded border-gray-300 text-primary focus:ring-primary/20 focus:ring-offset-0 mt-1 flex-shrink-0">
                    <label for="marketing_consent" class="text-sm text-gray-600 font-lato">
                        I would like to receive updates about new programs and events
                    </label>
                </div>
            </div>

            <!-- Submit Button -->
            <div class="pt-2" data-aos="fade-up" data-aos-delay="400">
                <button type="submit" x-bind:disabled="processing"
                    class="w-full group relative overflow-hidden bg-gradient-to-r from-primary to-primary-600 hover:from-primary-600 hover:to-primary-700 text-white font-semibold py-4 px-6 rounded-xl transition-all duration-300 transform hover:scale-[1.01] focus:outline-none focus:ring-2 focus:ring-primary/20 focus:ring-offset-2 disabled:opacity-50 disabled:cursor-not-allowed disabled:hover:scale-100 font-montserrat shadow-lg hover:shadow-xl">
                    <!-- Loading State -->
                    <div x-show="processing" class="flex items-center justify-center">
                        <i data-lucide="loader-2" class="w-5 h-5 animate-spin mr-2"></i>
                        <span>Creating Account...</span>
                    </div>
                    <!-- Normal State -->
                    <div x-show="!processing" class="flex items-center justify-center">
                        <i data-lucide="user-plus" class="w-5 h-5 mr-2 group-hover:scale-110 transition-transform"></i>
                        <span>Create Account</span>
                    </div>
                </button>
            </div>
        </form>

        <!-- Divider -->
        <div class="relative my-8" data-aos="fade-up" data-aos-delay="450">
            <div class="absolute inset-0 flex items-center">
                <div class="w-full border-t border-gray-200"></div>
            </div>
            <div class="relative flex justify-center text-sm">
                <span class="px-4 bg-white text-gray-500 font-lato">or continue with</span>
            </div>
        </div>

        <!-- Social Login Grid -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-4" data-aos="fade-up" data-aos-delay="500">
            <button type="button"
                class="flex items-center justify-center gap-3 border border-gray-300 bg-white text-gray-700 py-3.5 px-4 rounded-xl font-medium hover:bg-gray-50 hover:border-gray-400 transition-all duration-200 font-lato shadow-sm hover:shadow-md">
                <svg class="w-5 h-5" viewBox="0 0 24 24" fill="currentColor">
                    <path
                        d="M22.56 12.25c0-.78-.07-1.53-.2-2.25H12v4.26h5.92c-.26 1.37-1.04 2.53-2.21 3.31v2.77h3.57c2.08-1.92 3.28-4.74 3.28-8.09z" />
                    <path
                        d="M12 23c2.97 0 5.46-.98 7.28-2.66l-3.57-2.77c-.98.66-2.23 1.06-3.71 1.06-2.86 0-5.29-1.93-6.16-4.53H2.18v2.84C3.99 20.53 7.7 23 12 23z" />
                    <path
                        d="M5.84 14.09c-.22-.66-.35-1.36-.35-2.09s.13-1.43.35-2.09V7.07H2.18C1.43 8.55 1 10.22 1 12s.43 3.45 1.18 4.93l2.85-2.22.81-.62z" />
                    <path
                        d="M12 5.38c1.62 0 3.06.56 4.21 1.64l3.15-3.15C17.45 2.09 14.97 1 12 1 7.7 1 3.99 3.47 2.18 7.07l3.66 2.84c.87-2.6 3.3-4.53 6.16-4.53z" />
                </svg>
                <span class="hidden sm:inline">Continue with Google</span>
                <span class="sm:hidden">Google</span>
            </button>

            <button type="button"
                class="flex items-center justify-center gap-3 border border-gray-300 bg-white text-gray-700 py-3.5 px-4 rounded-xl font-medium hover:bg-gray-50 hover:border-gray-400 transition-all duration-200 font-lato shadow-sm hover:shadow-md">
                <i data-lucide="github" class="w-5 h-5"></i>
                <span class="hidden sm:inline">Continue with GitHub</span>
                <span class="sm:hidden">GitHub</span>
            </button>
        </div>

        <!-- Login Link -->
        <div class="text-center pt-8 border-t border-gray-100 mt-8" data-aos="fade-up" data-aos-delay="550">
            <p class="text-gray-600 font-lato">
                Already have an account?
                <a href="{{ route('login') }}"
                    class="text-primary font-semibold hover:text-primary-600 transition-colors font-montserrat ml-1">
                    Sign in
                </a>
            </p>
        </div>
    </div>

    @push('scripts')
        <script>
            // Initialize Lucide icons when page loads
            document.addEventListener('DOMContentLoaded', function () {
                if (typeof lucide !== 'undefined') {
                    lucide.createIcons();
                }
            });

            // Reinitialize icons after Alpine.js updates
            document.addEventListener('alpine:updated', function () {
                if (typeof lucide !== 'undefined') {
                    lucide.createIcons();
                }
            });
        </script>
    @endpush
</x-auth-layout>