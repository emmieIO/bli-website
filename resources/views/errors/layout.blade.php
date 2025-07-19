<x-guest-layout>
    <section class="min-h-screen flex items-center justify-center bg-gray-50">
        <div class="text-center max-w-lg mx-auto px-4">
            <div class="flex justify-center">
                <i data-lucide="alert-circle" class="w-20 h-20 mb-6 text-teal-600" ></i>
            </div>
            <h1 class="text-6xl font-extrabold text-teal-600">@yield('code')</h1>
            <h2 class="mt-4 text-2xl font-bold text-gray-800">@yield('title')</h2>
            <p class="mt-2 text-gray-600 text-sm">@yield('message')</p>
            <div class="mt-6 flex items-center justify-center gap-3">
                <a href="{{ url('/') }}"
                    class="px-5 py-2 bg-teal-600 text-white rounded-lg hover:bg-teal-700 transition">
                    Go Home
                </a>
                <a href="{{ url()->previous() }}"
                    class="px-5 py-2 border border-teal-500 text-teal-600 rounded-lg hover:bg-teal-50 transition">
                    Go Back
                </a>
            </div>
        </div>
    </section>
</x-guest-layout>