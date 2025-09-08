<x-guest-layout>
    <div class="max-w-lg sm:max-w-2xl mx-auto px-4 py-16 sm:py-20 text-center">
        <!-- Icon -->
        <div class="flex justify-center mb-8">
            <div class="bg-[#00275E]/10 text-[#00275E] p-4 sm:p-5 rounded-full">
                <i data-lucide="mic" class="w-10 h-10 sm:w-12 sm:h-12"></i>
            </div>
        </div>

        <h1 class="text-2xl sm:text-3xl font-extrabold text-[#00275E] mb-6 leading-tight">
            Thank You for Applying to Speak at {{ $event->title }}!
        </h1>

        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6 sm:p-8 mb-8">
            <p class="text-gray-700 text-lg mb-4">
                Your proposal has been received and will be reviewed as we finalize the speaker lineup for
                <strong class="text-[#00275E]">{{ $event->title }}</strong>.
            </p>

            <div class="border-l-4 border-[#00275E] bg-[#00275E]/5 p-4 rounded-r-lg text-left">
                <p class="text-sm text-gray-600">
                    <strong class="font-medium text-[#00275E]">What happens next?</strong><br>
                    We’ll notify all applicants via email once selections are finalized.
                </p>
            </div>

            <p class="text-gray-600 mt-6 px-2 sm:px-0 leading-relaxed">
                If you have any questions in the meantime, feel free to reach out to our team at
                <a href="mailto:{{ $event->contact_email }}" class="font-medium text-[#00275E] hover:text-[#FF0000] hover:underline transition">
                    {{$event->contact_email}}
                </a>.
            </p>
        </div>

        <div class="mt-6">
            <a href="/"
                class="inline-flex items-center px-6 py-3 bg-[#00275E] border border-transparent rounded-lg font-semibold text-white hover:bg-[#FF0000] focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-[#00275E] transition shadow-sm">
                <i data-lucide="home" class="w-4 h-4 mr-2"></i>
                Return to Home
            </a>
        </div>
    </div>
</x-guest-layout>