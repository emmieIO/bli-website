<x-guest-layout>
    <div class="min-h-screen bg-gray-50 py-12">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-8">
                <h1 class="text-3xl font-bold text-gray-900 mb-4">Certificate Verification</h1>
                <p class="text-gray-600">Verify the authenticity of BLI Academy certificates</p>
            </div>

            @if(isset($error))
                <div class="bg-red-50 border border-red-200 rounded-md p-4 mb-6">
                    <div class="flex">
                        <div class="ml-3">
                            <h3 class="text-sm font-medium text-red-800">Certificate Not Found</h3>
                            <div class="mt-2 text-sm text-red-700">{{ $error }}</div>
                        </div>
                    </div>
                </div>
            @endif

            @if($certificate)
                <div class="bg-white shadow rounded-lg overflow-hidden mb-8">
                    <div class="px-6 py-4 bg-green-50 border-b border-green-200">
                        <div class="flex items-center">
                            <svg class="w-5 h-5 text-green-600 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd"
                                    d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z"
                                    clip-rule="evenodd" />
                            </svg>
                            <h3 class="text-lg font-medium text-green-800">Certificate Verified</h3>
                        </div>
                    </div>

                    <div class="p-6">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <h4 class="font-medium text-gray-900 mb-2">Student Information</h4>
                                <div class="space-y-2 text-sm">
                                    <div><strong>Name:</strong> {{ $certificate->user->name }}</div>
                                    <div><strong>Email:</strong> {{ $certificate->user->email }}</div>
                                </div>
                            </div>

                            <div>
                                <h4 class="font-medium text-gray-900 mb-2">Course Information</h4>
                                <div class="space-y-2 text-sm">
                                    <div><strong>Course:</strong> {{ $certificate->course->title }}</div>
                                    <div><strong>Instructor:</strong>
                                        {{ $certificate->course->instructor->name ?? 'BLI Academy' }}</div>
                                </div>
                            </div>

                            <div>
                                <h4 class="font-medium text-gray-900 mb-2">Certificate Details</h4>
                                <div class="space-y-2 text-sm">
                                    <div><strong>Certificate Number:</strong> {{ $certificate->certificate_number }}</div>
                                    <div><strong>Issued Date:</strong> {{ $certificate->completion_date->format('F j, Y') }}
                                    </div>
                                </div>
                            </div>

                            <div>
                                <h4 class="font-medium text-gray-900 mb-2">Verification</h4>
                                <div class="space-y-2 text-sm">
                                    <div><strong>Status:</strong> <span class="text-green-600 font-medium">Valid</span>
                                    </div>
                                    <div><strong>Verified:</strong> {{ now()->format('F j, Y \a\t g:i A') }}</div>
                                </div>
                            </div>
                        </div>

                        @if($certificate->certificate_url)
                            <div class="mt-6 pt-6 border-t border-gray-200">
                                <a href="{{ $certificate->certificate_url }}" target="_blank"
                                    class="inline-flex items-center px-4 py-2 bg-[#002147] text-white rounded-lg hover:bg-blue-900 transition-colors">
                                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                    </svg>
                                    View Certificate
                                </a>
                            </div>
                        @endif
                    </div>
                </div>
            @endif

            <!-- Verification Form -->
            <div class="bg-white shadow rounded-lg p-6">
                <h2 class="text-xl font-semibold text-gray-900 mb-4">Verify Another Certificate</h2>
                <form action="{{ route('certificates.verify', '') }}" method="GET" class="flex gap-4">
                    <div class="flex-1">
                        <label for="certificate_number" class="block text-sm font-medium text-gray-700 mb-2">
                            Certificate Number
                        </label>
                        <input type="text" id="certificate_number" name="certificate_number"
                            class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                            placeholder="Enter certificate number (e.g., CERT-ABC123DEF4)"
                            value="{{ request('certificate_number') }}" required>
                    </div>
                    <div class="flex-shrink-0">
                        <button type="submit"
                            class="mt-7 px-6 py-2 bg-[#002147] text-white rounded-lg hover:bg-blue-900 transition-colors">
                            Verify
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        // Auto-submit form when certificate number is in URL
        document.addEventListener('DOMContentLoaded', function () {
            const urlPath = window.location.pathname;
            const certNumberMatch = urlPath.match(/\/certificates\/verify\/([A-Z0-9-]+)$/);

            if (certNumberMatch) {
                const certNumber = certNumberMatch[1];
                document.getElementById('certificate_number').value = certNumber;
            }
        });
    </script>
</x-guest-layout>