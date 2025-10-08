<x-auth-layout title="Email Verification"
    description="A verification link has been sent to your email. Please check your inbox and click the link to continue.">
    <x-slot name="pageTitle">Email Verification - Beacon Leadership Institute</x-slot>

    @if (session('status') == 'verification-link-sent')
        <div class="bg-accent-50 border border-accent-200 text-accent-800 text-sm rounded-lg p-4 mb-6 flex items-start gap-3"
            data-aos="fade-down" data-aos-duration="600">
            <i data-lucide="check-circle" class="w-5 h-5 mt-0.5 text-accent-600"></i>
            <span class="font-lato">
                A new verification link has been sent to your email address.
            </span>
        </div>
    @endif

    <form method="POST" action="{{ route('verification.send') }}" class="space-y-6">
        @csrf

        <!-- Resend Button -->
        <div data-aos="fade-up" data-aos-duration="600" data-aos-delay="200">
            <button type="submit"
                class="w-full flex items-center justify-center gap-2 py-3 px-4 border border-transparent rounded-lg shadow-sm text-sm font-medium text-white bg-accent hover:bg-accent-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-accent-500 transition-all duration-200 transform hover:scale-105 font-montserrat">
                <i data-lucide="refresh-ccw" class="w-4 h-4"></i>
                Resend Verification Email
            </button>
        </div>
    </form>

    <form method="POST" action="{{ route('logout') }}" class="mt-4 text-center" data-aos="fade" data-aos-duration="600"
        data-aos-delay="400">
        @csrf
        <button type="submit" class="text-sm text-gray-500 hover:text-gray-700 underline transition font-lato">
            <i data-lucide="log-out" class="inline-block w-4 h-4 mr-1 -mt-1"></i>
            Logout
        </button>
    </form>

    <script>
        lucide.createIcons();
    </script>

</x-auth-layout>
