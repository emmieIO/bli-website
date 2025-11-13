<x-guest-layout>
    <div class="max-w-xl mx-auto py-20 text-center">
        {{-- Icon --}}
        <div class="flex justify-center mb-6">
            <div class="bg-primary-300 text-primary p-4 rounded-full">
                <i data-lucide="check-circle" class="w-12 h-12"></i>
            </div>
        </div>

        <h1 class="text-3xl font-bold text-primary mb-4">Thank You for Your Application!</h1>

        <p class="text-gray-600 mb-6">
            Your application has been successfully submitted and is now under review.
        </p>
        <p class="text-gray-500">
            We typically review applications within 3–5 business days. You'll receive an email notification once a decision has been made.
        </p>
        <a class="px-3 bg-primary hover:bg-secondary text-white py-2 inline-block my-4 rounded-lg" href="{{ route('homepage') }}">Go back home</a>
    </div>

</x-guest-layout>
