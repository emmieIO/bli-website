<x-auth-layout title="Reset Password" description="Almost there! Set your new password to regain access.">
    <x-slot name="pageTitle">Password Reset - Beacon Leadership Institute</x-slot>

    <div x-data="{
        processing: false,
        showPassword: false,
        showConfirmPassword: false,
        password: '',
        password_confirmation: '',
        passwordStrength: 0,
        
        calculatePasswordStrength() {
            const password = this.password;
            let strength = 0;
            
            if (password.length >= 8) strength += 25;
            if (/[a-z]/.test(password)) strength += 25;
            if (/[A-Z]/.test(password)) strength += 25;
            if (/[0-9!@#$%^&*(),.?\" :{}|<>]/.test(password)) strength += 25;

        this.passwordStrength = strength;
        }
        }" x-init="$watch('password', () => calculatePasswordStrength())">

        <!-- Progress Steps -->
        <div class="mb-6 flex items-center justify-center gap-2">
            <div class="flex items-center gap-2 text-green-600">
                <div
                    class="w-8 h-8 rounded-full bg-green-100 border-2 border-green-300 flex items-center justify-center">
                    <i data-lucide="check" class="w-4 h-4"></i>
                </div>
                <span class="text-sm font-lato">Email Verified</span>
            </div>
            <div class="w-12 h-0.5 bg-gray-300"></div>
            <div class="flex items-center gap-2 text-primary">
                <div
                    class="w-8 h-8 rounded-full bg-primary/10 border-2 border-primary flex items-center justify-center">
                    <i data-lucide="lock" class="w-4 h-4"></i>
                </div>
                <span class="text-sm font-lato">New Password</span>
            </div>
        </div>

        <form method="post" action="{{ route('password.update') }}" class="space-y-6" x-on:submit="processing = true">
            @csrf

            <!-- Hidden Fields -->
            <input type="hidden" name="token" value="{{ request()->route('token') }}">
            <input type="hidden" name="email" value="{{ request()->query('email') }}">

            <!-- Email Display -->
            <div class="p-4 bg-blue-50 rounded-xl border border-blue-200">
                <div class="flex items-center gap-2 text-blue-800 text-sm font-lato">
                    <i data-lucide="mail" class="w-4 h-4"></i>
                    <span>Resetting password for: <strong>{{ request()->query('email') }}</strong></span>
                </div>
            </div>

            <!-- New Password -->
            <div class="space-y-2">
                <label class="block text-sm font-medium text-gray-700 font-lato">New Password</label>
                <div class="relative">
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                        <i data-lucide="lock" class="h-5 w-5 text-gray-400"></i>
                    </div>
                    <input :type="showPassword ? 'text' : 'password'" name="password" x-model="password" required
                        class="block w-full pl-10 pr-12 py-3 bg-gray-50 border border-gray-300 rounded-xl text-gray-900 placeholder-gray-500 focus:outline-none focus:ring-2 focus:ring-primary/20 focus:border-primary transition-all duration-200 font-lato"
                        placeholder="Enter your new password">
                    <button type="button" @click="showPassword = !showPassword"
                        class="absolute inset-y-0 right-0 pr-3 flex items-center text-gray-400 hover:text-gray-600 transition-colors">
                        <i :data-lucide="showPassword ? 'eye-off' : 'eye'" class="h-5 w-5"></i>
                    </button>
                </div>

                <!-- Password Strength Indicator -->
                <div x-show="password.length > 0" class="mt-2">
                    <div class="flex items-center gap-2">
                        <div class="flex-1 bg-gray-200 rounded-full h-2">
                            <div class="h-2 rounded-full transition-all duration-300" :class="{
                                     'bg-red-500': passwordStrength < 50,
                                     'bg-yellow-500': passwordStrength >= 50 && passwordStrength < 75,
                                     'bg-green-500': passwordStrength >= 75
                                 }" :style="`width: ${passwordStrength}%`"></div>
                        </div>
                        <span class="text-xs text-gray-600 font-lato"
                            x-text="passwordStrength < 50 ? 'Weak' : passwordStrength < 75 ? 'Good' : 'Strong'"></span>
                    </div>
                </div>
                @error('password')
                    <p class="text-sm text-red-500 font-lato">{{ $message }}</p>
                @enderror
            </div>

            <!-- Confirm Password -->
            <div class="space-y-2">
                <label class="block text-sm font-medium text-gray-700 font-lato">Confirm New Password</label>
                <div class="relative">
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                        <i data-lucide="lock" class="h-5 w-5 text-gray-400"></i>
                    </div>
                    <input :type="showConfirmPassword ? 'text' : 'password'" name="password_confirmation"
                        x-model="password_confirmation" required
                        class="block w-full pl-10 pr-12 py-3 bg-gray-50 border border-gray-300 rounded-xl text-gray-900 placeholder-gray-500 focus:outline-none focus:ring-2 focus:ring-primary/20 focus:border-primary transition-all duration-200 font-lato"
                        placeholder="Confirm your new password">
                    <button type="button" @click="showConfirmPassword = !showConfirmPassword"
                        class="absolute inset-y-0 right-0 pr-3 flex items-center text-gray-400 hover:text-gray-600 transition-colors">
                        <i :data-lucide="showConfirmPassword ? 'eye-off' : 'eye'" class="h-5 w-5"></i>
                    </button>
                </div>
                @error('password_confirmation')
                    <p class="text-sm text-red-500 font-lato">{{ $message }}</p>
                @enderror
            </div>

            <!-- Password Requirements -->
            <div class="p-4 bg-gray-50 rounded-xl border border-gray-200">
                <h4 class="text-sm font-semibold text-gray-800 mb-3 font-montserrat flex items-center gap-2">
                    <i data-lucide="shield-check" class="w-4 h-4"></i>
                    Password Requirements
                </h4>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-2 text-xs font-lato">
                    <div class="flex items-center gap-2"
                        :class="password.length >= 8 ? 'text-green-600' : 'text-gray-500'">
                        <i :data-lucide="password.length >= 8 ? 'check' : 'x'" class="w-3 h-3"></i>
                        <span>At least 8 characters</span>
                    </div>
                    <div class="flex items-center gap-2"
                        :class="/[a-z]/.test(password) ? 'text-green-600' : 'text-gray-500'">
                        <i :data-lucide="/[a-z]/.test(password) ? 'check' : 'x'" class="w-3 h-3"></i>
                        <span>Lowercase letter</span>
                    </div>
                    <div class="flex items-center gap-2"
                        :class="/[A-Z]/.test(password) ? 'text-green-600' : 'text-gray-500'">
                        <i :data-lucide="/[A-Z]/.test(password) ? 'check' : 'x'" class="w-3 h-3"></i>
                        <span>Uppercase letter</span>
                    </div>
                    <div class="flex items-center gap-2" :class="/[0-9!@#$%^&*(),.?\" :{}|<>]/.test(password) ?
                        'text-green-600' : 'text-gray-500'">
                        <i :data-lucide="/[0-9!@#$%^&*(),.?\" :{}|<>]/.test(password) ? 'check' : 'x'" class="w-3
                            h-3"></i>
                        <span>Number or symbol</span>
                    </div>
                </div>
            </div>

            <!-- Submit Button -->
            <button type="submit"
                x-bind:disabled="processing || passwordStrength < 50 || password !== password_confirmation"
                class="w-full group relative overflow-hidden bg-gradient-to-r from-primary to-primary-600 hover:from-primary-600 hover:to-primary-700 text-white font-semibold py-3.5 px-6 rounded-xl transition-all duration-300 transform hover:scale-[1.01] focus:outline-none focus:ring-2 focus:ring-primary/20 focus:ring-offset-2 disabled:opacity-50 disabled:cursor-not-allowed disabled:hover:scale-100 font-montserrat shadow-lg hover:shadow-xl">
                <!-- Loading State -->
                <div x-show="processing" class="flex items-center justify-center">
                    <i data-lucide="loader-2" class="w-5 h-5 animate-spin mr-2"></i>
                    <span>Updating Password...</span>
                </div>
                <!-- Normal State -->
                <div x-show="!processing" class="flex items-center justify-center">
                    <i data-lucide="shield-check" class="w-5 h-5 mr-2 group-hover:scale-110 transition-transform"></i>
                    <span>Update Password</span>
                </div>
            </button>
        </form>

        <!-- Security Notice -->
        <div class="mt-6 p-4 bg-amber-50 rounded-xl border border-amber-200">
            <div class="flex items-start gap-3">
                <i data-lucide="info" class="w-5 h-5 text-amber-600 mt-0.5"></i>
                <div class="text-sm text-amber-800 font-lato">
                    <p class="font-semibold mb-1">Security Notice:</p>
                    <p class="text-amber-700">After resetting your password, you'll be automatically signed out from all
                        other devices for security reasons.</p>
                </div>
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