<x-auth-layout :title="'Welcome Back'" :description="'Sign in to your account to continue'">
    <x-slot name="pageTitle">Login - Beacon Leadership Institute</x-slot>

    <div x-data="{ 
        processing: false,
        showPassword: false,
        email: '',
        password: '',
        rememberMe: false
    }">
        <form method="POST" action="{{ route('login.store') }}" class="space-y-6" x-on:submit="processing = true">
            @csrf

            <!-- Email Field -->
            <div class="space-y-2">
                <label class="block text-sm font-medium text-gray-700 font-lato">Email Address</label>
                <div class="relative">
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none input-icon">
                        <i data-lucide="mail" class="h-5 w-5 text-gray-400"></i>
                    </div>
                    <input type="email" name="email" x-model="email" required
                        class="block w-full pl-10 pr-3 py-3 border border-gray-300 rounded-xl text-gray-900 placeholder-gray-500 focus:outline-none focus:ring-2 focus:ring-primary focus:border-primary transition-all duration-200 bg-gray-50 focus:bg-white"
                        placeholder="Enter your email address">
                </div>
                @error('email')
                    <p class="text-sm text-red-600 font-lato">{{ $message }}</p>
                @enderror
            </div>

            <!-- Password Field -->
            <div class="space-y-2">
                <label class="block text-sm font-medium text-gray-700 font-lato">Password</label>
                <div class="relative">
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none input-icon">
                        <i data-lucide="lock" class="h-5 w-5 text-gray-400"></i>
                    </div>
                    <input :type="showPassword ? 'text' : 'password'" name="password" x-model="password" required
                        class="block w-full pl-10 pr-12 py-3 border border-gray-300 rounded-xl text-gray-900 placeholder-gray-500 focus:outline-none focus:ring-2 focus:ring-primary focus:border-primary transition-all duration-200 bg-gray-50 focus:bg-white"
                        placeholder="Enter your password">
                    <button type="button" @click="showPassword = !showPassword"
                        class="absolute inset-y-0 right-0 pr-3 flex items-center text-gray-400 hover:text-gray-600 transition-colors password-toggle">
                        <i :data-lucide="showPassword ? 'eye-off' : 'eye'" class="h-5 w-5"></i>
                    </button>
                </div>
                @error('password')
                    <p class="text-sm text-red-600 font-lato">{{ $message }}</p>
                @enderror
            </div>

            <!-- Remember Me & Forgot Password -->
            <div class="flex items-center justify-between">
                <label class="inline-flex items-center">
                    <input type="checkbox" name="remember" x-model="rememberMe"
                        class="rounded border-gray-300 text-primary focus:ring-primary focus:ring-offset-0">
                    <span class="ml-2 text-sm text-gray-600 font-lato">Remember me</span>
                </label>

                <a href="{{ route('password.request') }}"
                    class="text-sm text-primary hover:text-primary-600 transition-colors font-lato font-medium">
                    Forgot password?
                </a>
            </div>

            <!-- Submit Button -->
            <div class="pt-2">
                <button type="submit" x-bind:disabled="processing"
                    class="w-full group relative overflow-hidden bg-gradient-to-r from-primary to-primary-600 hover:from-primary-600 hover:to-primary-700 text-white font-semibold py-3 px-6 rounded-xl transition-all duration-300 transform hover:scale-[1.02] focus:outline-none focus:ring-2 focus:ring-primary/50 focus:ring-offset-2 disabled:opacity-50 disabled:cursor-not-allowed disabled:hover:scale-100 font-montserrat shadow-lg hover:shadow-xl">
                    <!-- Loading State -->
                    <div x-show="processing" class="flex items-center justify-center">
                        <i data-lucide="loader-2" class="w-5 h-5 animate-spin mr-2"></i>
                        <span>Signing In...</span>
                    </div>
                    <!-- Normal State -->
                    <div x-show="!processing" class="flex items-center justify-center">
                        <i data-lucide="log-in" class="w-5 h-5 mr-2 group-hover:translate-x-1 transition-transform"></i>
                        <span>Sign In</span>
                    </div>
                </button>
            </div>
        </form>

        <!-- Divider -->
        <div class="relative my-6">
            <div class="absolute inset-0 flex items-center">
                <div class="w-full border-t border-gray-200"></div>
            </div>
            <div class="relative flex justify-center text-sm">
                <span class="px-2 bg-white text-gray-500 font-lato">or</span>
            </div>
        </div>

        <!-- Social Login -->
        <div class="space-y-3">
            <button type="button"
                class="w-full flex items-center justify-center gap-3 border border-gray-300 bg-white text-gray-700 py-3 px-4 rounded-xl font-medium hover:bg-gray-50 transition-all duration-200 font-lato">
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
                <span>Continue with Google</span>
            </button>
        </div>

        <!-- Register Link -->
        <div class="text-center pt-6">
            <p class="text-gray-600 font-lato">
                Don't have an account?
                <a href="{{ route('register') }}"
                    class="text-primary font-semibold hover:text-primary-600 transition-colors font-montserrat">
                    Sign up
                </a>
            </p>
        </div>
    </div>

    @push('scripts')
        <script>
            // Reinitialize icons after Alpine updates
            document.addEventListener('alpine:updated', () => {
                lucide.createIcons();
            });
        </script>
    @endpush
</x-auth-layout>