<x-guest-layout>
    <div class="max-w-lg sm:max-w-2xl mx-auto px-4 py-16 sm:py-20 text-center">
        {{-- Icon --}}
        <div class="flex justify-center mb-6">
            <div class="bg-green-100 text-green-700 p-4 rounded-full">
                <i data-lucide="mic" class="w-10 h-10 sm:w-12 sm:h-12"></i>
            </div>
        </div>

        <h1 class="text-2xl sm:text-2xl font-bold text-green-700 mb-4 leading-snug">
            Thank You for Applying to Speak at {{ $event->title }}!
        </h1>

        <p class="text-gray-700 mb-5">
            Your proposal has been received and will be reviewed as we finalize the speaker lineup for
            {{ $event->title }}.
        </p>

        <p class="text-gray-500 px-2 sm:px-0">
            We’ll confirm selected speakers before the event date.
            You’ll receive an email once a decision has been made. If you have any questions in the meantime,
            reach us at
            <a href="mailto:{{ config('app.support_mail') }}" class="text-blue-600 hover:underline">
                {{ config('app.support_mail') }}
            </a>.
        </p>

        <div class="mt-8">
            <a href="/"
                class="inline-flex items-center px-4 py-2 bg-green-600 border border-transparent rounded-md font-semibold text-white hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500 transition">
                Return to Home
            </a>
        </div>
    </div>
</x-guest-layout>
