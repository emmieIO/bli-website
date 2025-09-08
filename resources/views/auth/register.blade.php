<x-auth-layout :title="'Create Account'" :description="'Start your leadership journey with us'">
    <form method="POST" action="{{ route('register.store') }}" class="space-y-6">
        @csrf

        <!-- Full Name -->
        <x-input name="name" label="Full Name" placeholder="Enter your full name" icon="user" autofocus required />

        <!-- Email and Phone Number -->
        <div class="grid grid-cols-1 gap-2 md:grid-cols-2">
            <!-- Email -->
            <x-input name="email" type="email" label="Email Address" placeholder="example@domain.com" icon="mail"
                required />

            <!-- Phone Number -->
            <x-input name="phone" type="text" label="Phone Number" placeholder="123-456-7890" icon="phone"
                pattern="[0-9]{3}-[0-9]{3}-[0-9]{4}" autocomplete="tel" required />
        </div>

        <!-- Password and Confirm Password -->
        <div class="grid grid-cols-1 gap-2 md:grid-cols-2">
            <!-- Password -->
            <x-input name="password" type="password" label="Password" placeholder="Enter a strong password" icon="lock"
                required />

            <!-- Confirm Password -->
            <x-input name="password_confirmation" type="password" label="Confirm Password"
                placeholder="Re-enter your password" icon="lock" required />
        </div>

        <!-- Privacy Policy and Terms of Service -->
        <div class="flex items-center space-x-2 text-sm text-white">
            <input type="checkbox" name="agree_terms" id="agree_terms"
                class="rounded border-gray-300 text-[#FF0000] shadow-sm focus:ring-[#FF0000]" required>
            <label for="agree_terms" class="text-white">
                I agree to the
                <a href="{{ route('privacy-policy') }}" class="text-[#FF0000] hover:underline">Privacy Policy</a>
                and
                <a href="{{ route('terms-of-service') }}" class="text-[#FF0000] hover:underline">Terms of Service</a>.
            </label>
        </div>

        <!-- Marketing Consent (Optional) -->
        <div class="flex items-center space-x-2 text-sm text-white">
            <input type="checkbox" name="marketing_consent" id="marketing_consent"
                class="rounded border-gray-300 text-[#FF0000] shadow-sm focus:ring-[#FF0000]">
            <label for="marketing_consent" class="text-white">
                I agree to receive marketing emails and updates.
            </label>
        </div>

        <!-- Submit Button -->
        <div>
            <button type="submit"
                class="w-full bg-[#FF0000] hover:bg-[#cc0000] text-white font-semibold py-3 px-4 rounded-md transition flex items-center justify-center gap-2">
                <i data-lucide="user-plus" class="w-5 h-5"></i>
                Create Account
            </button>
        </div>

        <!-- Social Registration -->
        <div class="mt-6 text-center">
            <p class="text-sm text-white mb-4">or sign up with</p>
            <div class="flex justify-center gap-4">
                <!-- Google -->
                <a href=""
                    class="flex items-center gap-2 bg-white hover:bg-gray-100 text-[#00275E] px-4 py-2 rounded-md text-sm font-medium transition">
                    <i data-lucide="globe" class="w-4 h-4"></i> Google
                </a>

                <!-- Facebook -->
                <a href=""
                    class="flex items-center gap-2 bg-white hover:bg-gray-100 text-[#00275E] px-4 py-2 rounded-md text-sm font-medium transition">
                    <i data-lucide="facebook" class="w-4 h-4"></i> Facebook
                </a>
            </div>
        </div>

        <!-- Login Redirect -->
        <p class="text-center text-sm mt-6 text-white">
            Already have an account?
            <a href="{{ route('login') }}" class="text-[#FF0000] hover:underline font-medium">
                Sign in
            </a>
        </p>
    </form>
</x-auth-layout>