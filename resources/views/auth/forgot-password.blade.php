<x-auth-layout title="Forgot Password?"
    description="No worries! We'll help you reset your password in just a few steps.">
    <x-slot name="pageTitle">Forgot Password - Beacon Leadership Institute</x-slot>

    <div x-data="{ processing: false, email: '', showSuccess: false }">

        @if (session('status'))
            <div class="bg-green-50 border border-green-200 text-green-800 text-sm rounded-xl p-4 mb-6 flex items-start gap-3"
                data-aos="fade-down" data-aos-duration="600">
                <i data-lucide="check-circle" class="w-5 h-5 mt-0.5 text-green-600"></i>
                <div>
                    <p class="font-semibold font-montserrat">Email Sent Successfully!</p>
                    <p class="font-lato">{{ session('status') }}</p>
                </div>
            </div>
        @endif

        <!-- Instructions -->
        <div class="mb-6 p-6 bg-blue-50 rounded-xl border border-blue-200" data-aos="fade-up" data-aos-delay="100">
            <div class="flex items-start gap-3">
                <i data-lucide="info" class="w-5 h-5 text-blue-600 mt-0.5"></i>
                <div class="text-sm text-gray-700 font-lato">
                    <p class="font-semibold mb-2 text-gray-900">How it works:</p>
                    <ol class="list-decimal list-inside space-y-1 text-gray-600">
                        <li>Enter your email address below</li>
                        <li>Check your inbox for a reset link</li>
                        <li>Click the link to create a new password</li>
                        <li>Sign in with your new password</li>
                    </ol>
                </div>
            </div>
        </div>

        <form method="POST" action="{{ route('password.email') }}" class="space-y-6" x-on:submit="processing = true">
            @csrf

            <!-- Email Input -->
            <div class="space-y-2" data-aos="fade-up" data-aos-delay="200">
                <label class="block text-sm font-medium text-gray-700 font-lato">Email Address</label>
                <div class="relative">
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none input-icon">
                        <i data-lucide="mail" class="h-5 w-5 text-gray-400"></i>
                    </div>
                    <input type="email" name="email" x-model="email" required
                        class="block w-full pl-10 pr-3 py-3 bg-gray-50 border border-gray-300 rounded-xl text-gray-900 placeholder-gray-500 focus:outline-none focus:ring-2 focus:ring-primary/20 focus:border-primary transition-all duration-200 font-lato"
                        placeholder="Enter your registered email address">
                </div>
                @error('email')
                    <p class="text-sm text-red-500 font-lato">{{ $message }}</p>
                @enderror
            </div>

            <!-- Submit Button -->
            <button type="submit" x-bind:disabled="processing || !email" data-aos="fade-up" data-aos-delay="300"
                class="w-full group relative overflow-hidden bg-gradient-to-r from-primary to-primary-600 hover:from-primary-600 hover:to-primary-700 text-white font-semibold py-3.5 px-6 rounded-xl transition-all duration-300 transform hover:scale-[1.01] focus:outline-none focus:ring-2 focus:ring-primary/20 focus:ring-offset-2 disabled:opacity-50 disabled:cursor-not-allowed disabled:hover:scale-100 font-montserrat shadow-lg hover:shadow-xl">
                <!-- Loading State -->
                <div x-show="processing" class="flex items-center justify-center">
                    <i data-lucide="loader-2" class="w-5 h-5 animate-spin mr-2"></i>
                    <span>Sending Reset Link...</span>
                </div>
                <!-- Normal State -->
                <div x-show="!processing" class="flex items-center justify-center">
                    <i data-lucide="send" class="w-5 h-5 mr-2 group-hover:translate-x-1 transition-transform"></i>
                    <span>Send Password Reset Link</span>
                </div>
            </button>
        </form>

        <!-- Alternative Options -->
        <div class="mt-8 space-y-6" data-aos="fade-up" data-aos-delay="400">
            <!-- Security Tips -->
            <div class="p-4 bg-amber-50 rounded-xl border border-amber-200">
                <h4 class="text-sm font-semibold text-amber-800 mb-2 font-montserrat flex items-center gap-2">
                    <i data-lucide="shield-check" class="w-4 h-4"></i>
                    Security Tips
                </h4>
                <ul class="text-xs text-amber-700 space-y-1 font-lato">
                    <li>• The reset link will expire in 60 minutes</li>
                    <li>• Check your spam/junk folder if you don't see the email</li>
                    <li>• Use a strong, unique password when resetting</li>
                </ul>
            </div>

            <!-- Back to Login -->
            <div class="text-center pt-4 border-t border-gray-100">
                <a href="{{ route('login') }}"
                    class="inline-flex items-center gap-2 text-gray-600 hover:text-primary transition-colors font-lato text-sm">
                    <i data-lucide="arrow-left" class="w-4 h-4"></i>
                    Back to Login
                </a>
            </div>
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