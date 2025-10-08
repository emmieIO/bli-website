<x-auth-layout title="Forgot Password?" description="No worries! Enter your email and we’ll send you a reset link.">

    @if (session('status'))
        <div class="bg-primary-50 border border-primary-200 text-primary-800 text-sm rounded-lg p-4 mb-6 flex items-start gap-3"
             data-aos="fade-down"
             data-aos-duration="600">
            <i data-lucide="check-circle" class="w-5 h-5 mt-0.5 text-accent-600"></i>
            <span class="font-lato">{{ session('status') }}</span>
        </div>
    @endif

    <form method="POST" action="{{ route('password.email') }}" class="space-y-6">
        @csrf

        <!-- Email Input -->
        <x-input label="Email" type="email" name="email" icon="mail" autofocus />

        <!-- Submit Button -->
        <div data-aos="fade-up" data-aos-duration="600" data-aos-delay="200">
            <button type="submit"
                class="w-full flex items-center justify-center gap-2 py-3 px-4 border border-transparent rounded-lg shadow-sm text-sm font-medium text-white bg-secondary hover:bg-secondary-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-secondary-500 transition-all duration-200 transform hover:scale-105 font-montserrat">
                <i data-lucide="send" class="w-4 h-4"></i>
                Send Password Reset Link
            </button>
        </div>
    </form>

    <div class="mt-6 text-center"
         data-aos="fade" data-aos-duration="600" data-aos-delay="400">
        <a href="{{ route('login') }}"
            class="text-sm text-secondary hover:text-secondary-600 hover:underline flex justify-center items-center gap-1 transition-colors font-lato">
            <i data-lucide="arrow-left" class="w-4 h-4"></i>
            Back to Login
        </a>
    </div>

    <script>
        lucide.createIcons();
    </script>

</x-auth-layout>