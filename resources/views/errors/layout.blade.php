<x-guest-layout>
    <section class="min-h-screen flex items-center justify-center bg-blue-50">
        <div class="text-center max-w-lg mx-auto px-4">
            <div class="flex justify-center">
                <i data-lucide="alert-circle" class="w-20 h-20 mb-6 text-blue-900" ></i>
            </div>
            <h1 class="text-6xl font-extrabold text-blue-900">@yield('code')</h1>
            <h2 class="mt-4 text-2xl font-bold text-red-700">@yield('title')</h2>
            <p class="mt-2 text-blue-800 text-sm">@yield('message')</p>
            <div class="mt-6 flex items-center justify-center gap-3">
                <a href="{{ url('/') }}"
                    class="px-5 py-2 bg-blue-900 text-white rounded-lg hover:bg-blue-950 transition">
                    Go Home
                </a>
                <a href="{{ url()->previous() }}"
                    class="px-5 py-2 border border-red-700 text-red-700 rounded-lg hover:bg-red-50 transition">
                    Go Back
                </a>
            </div>
        </div>
    </section>
</x-guest-layout>