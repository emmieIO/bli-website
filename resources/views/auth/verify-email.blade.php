<x-auth-layout title="Email Verification"
    description="A verification link has been sent to your email. Please check your inbox and click the link to continue.">

    @if (session('status') == 'verification-link-sent')
        <div class="bg-teal-50 border border-teal-200 text-teal-800 text-sm rounded-md p-4 mb-6 flex items-start gap-3">
            <i data-lucide="check-circle" class="w-5 h-5 mt-0.5"></i>
            <span>
                A new verification link has been sent to your email address.
            </span>
        </div>
    @endif

    <form method="POST" action="{{ route('verification.send') }}" class="space-y-6">
        @csrf

        <!-- Resend Button -->
        <div>
            <button type="submit"
                class="w-full flex items-center justify-center gap-2 py-2 px-4 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-teal-700 hover:bg-teal-800 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-teal-600">
                <i data-lucide="refresh-ccw" class="w-4 h-4"></i>
                Resend Verification Email
            </button>
        </div>
    </form>

    <form method="POST" action="{{ route("logout") }}" class="mt-4 text-center">
        @csrf
        <button type="submit" class="text-sm text-gray-500 hover:text-gray-700 underline transition">
            <i data-lucide="log-out" class="inline-block w-4 h-4 mr-1 -mt-1"></i>
            Logout
        </button>
    </form>

    <script>
        lucide.createIcons();
    </script>

</x-auth-layout>
