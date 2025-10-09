<x-auth-layout :title="'Create Account'" :description="'Start your leadership journey with us'">
    <x-slot name="pageTitle">Register - Beacon Leadership Institute</x-slot>

    <form method="POST" action="{{ route('register.store') }}" class="space-y-6" x-data="{ processing: false }"
        x-on:submit="processing=true">
        @csrf

        <!-- Full Name -->
        <x-input name="name" label="Full Name" placeholder="Enter your full name" icon="user" autofocus />

        <!-- Email and Phone Number -->
        <div class="grid grid-cols-1 gap-2 md:grid-cols-2">
            <!-- Email -->
            <x-input name="email" type="email" label="Email Address" placeholder="example@domain.com"
                icon="mail" />

            <!-- Phone Number -->
            <x-input name="phone" type="text" label="Phone Number" placeholder="e.g. +2348012345678" icon="phone"
                autocomplete="tel" description="Enter your Nigerian phone number in international format." />
        </div>

        <!-- Password and Confirm Password -->
        <div class="grid grid-cols-1 gap-2 md:grid-cols-2">
            <!-- Password -->
            <x-input name="password" type="password" label="Password" placeholder="Enter a strong password"
                icon="lock" />

            <!-- Confirm Password -->
            <x-input name="password_confirmation" type="password" label="Confirm Password"
                placeholder="Re-enter your password" icon="lock" />
        </div>

        <!-- Privacy Policy and Terms of Service -->
        <div class="flex items-start space-x-2 text-sm">
            <input type="checkbox" name="agree_terms" id="agree_terms"
                class="rounded border-gray-300 text-secondary focus:ring-secondary shadow-sm mt-0.5" required>
            <label for="agree_terms" class="text-gray-700 font-lato">
                I agree to the
                <a href="{{ route('privacy-policy') }}"
                    class="text-secondary hover:text-secondary-600 hover:underline font-medium">Privacy Policy</a>
                and
                <a href="{{ route('terms-of-service') }}"
                    class="text-secondary hover:text-secondary-600 hover:underline font-medium">Terms of Service</a>.
            </label>
        </div>

        <!-- Marketing Consent (Optional) -->
        <div class="flex items-start space-x-2 text-sm">
            <input type="checkbox" name="marketing_consent" id="marketing_consent"
                class="rounded border-gray-300 text-secondary focus:ring-secondary shadow-sm mt-0.5">
            <label for="marketing_consent" class="text-gray-700 font-lato">
                I agree to receive marketing emails and updates.
            </label>
        </div>

        <!-- Submit Button -->
        <div>
            <button type="submit" x-bind:disabled="processing"
                class="w-full bg-secondary hover:bg-secondary-600 text-white text-lg font-semibold py-3 px-4 rounded-lg transition-all duration-200 transform hover:scale-105 flex items-center justify-center gap-2 font-montserrat">
                <span x-show="!processing">
                    <i data-lucide="user-plus" class="w-5 h-5"></i>
                </span>
                <span x-show="processing">
                    <i data-lucide="loader" class="w-5 h-5 animate-spin"></i>
                </span>
                <span x-text="processing ? 'Creating Account...' : 'Create Account'"></span>
            </button>
        </div>

        <!-- Login Redirect -->
        <p class="text-center text-sm mt-6 text-gray-600 font-lato">
            Already have an account?
            <a href="{{ route('login') }}"
                class="text-secondary hover:text-secondary-600 hover:underline font-medium font-montserrat">
                Sign in
            </a>
        </p>
    </form>
</x-auth-layout>
