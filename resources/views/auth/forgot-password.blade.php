<x-auth-layout title="Forgot Password?" description="No worries! Enter your email and we’ll send you a reset link.">

    @if (session('status'))
        <div class="bg-green-50 border border-green-200 text-green-800 text-sm rounded-md p-4 mb-6 flex items-start gap-3">
            <i data-lucide="check-circle" class="w-5 h-5 mt-0.5"></i>
            <span>{{ session('status') }}</span>
        </div>
    @endif

    <form method="POST" action="{{ route('password.email') }}" class="space-y-6">
        @csrf

        <!-- Email Input -->
         <x-input label="Email" type="email" name="email" icon="mail" autofocus />

        <!-- Submit Button -->
        <div>
            <button type="submit"
                class="w-full flex items-center justify-center gap-2 py-2 px-4 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-teal-700 hover:bg-teal-800 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-teal-600">
                <i data-lucide="send" class="w-4 h-4"></i>
                Send Password Reset Link
            </button>
        </div>
    </form>

    <div class="mt-6 text-center">
        <a href="{{ route('login') }}"
            class="text-sm text-teal-700 hover:underline flex justify-center items-center gap-1">
            <i data-lucide="arrow-left" class="w-4 h-4"></i>
            Back to Login
        </a>
    </div>

    <script>
        lucide.createIcons();
    </script>

</x-auth-layout>
