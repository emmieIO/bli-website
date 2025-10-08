<x-auth-layout title="Reset Password" description="Set your new password and regain access to your account.">

    <form method="post" action="{{ route('password.update') }}" class="space-y-6">
        @csrf

        <!-- Hidden Token -->
        <input type="hidden" name="token" value="{{ request()->route('token') }}">

        <!-- Hidden Email -->
        <input type="hidden" name="email" value="{{ request()->query('email') }}">

        <!-- New Password -->
        <x-input label="New Password" type="password" name="password" icon="lock" />
        <x-input label="Confirm Password" type="password" name="password_confirmation" icon="lock" />

        <!-- Info Graphic -->
        <div class="bg-primary-50 border border-primary-200 rounded-lg p-4 text-sm text-primary-800 flex items-start gap-3"
             data-aos="fade-up"
             data-aos-duration="600">
            <i data-lucide="info" class="w-5 h-5 mt-0.5 text-primary-600"></i>
            <span class="font-lato">
                Use at least 8 characters. Stronger passwords include upper and lowercase letters, numbers, and symbols.
            </span>
        </div>

        <!-- Submit Button -->
        <div data-aos="fade-up" data-aos-duration="600" data-aos-delay="200">
            <button type="submit"
                class="w-full flex justify-center items-center gap-2 py-3 px-4 border border-transparent rounded-lg shadow-sm text-sm font-medium text-white bg-secondary hover:bg-secondary-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-secondary-500 transition-all duration-200 transform hover:scale-105 font-montserrat">
                <i data-lucide="rotate-ccw" class="w-4 h-4"></i>
                Reset Password
            </button>
        </div>
    </form>

    <script>
        lucide.createIcons();
    </script>

</x-auth-layout>