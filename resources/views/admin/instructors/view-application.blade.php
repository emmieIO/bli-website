<x-app-layout>
    <x-instructor-dashbord-layout>
        <div class="container mx-auto px-4 py-8">
            <!-- Application Header with Stats -->
            <div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-8 gap-4">
                <div>
                    <h1 class="text-3xl font-bold text-teal-800">Instructor Application Review</h1>
                    <p class="text-gray-600">Submitted on {{ $application->created_at->format('M d, Y') }}</p>
                </div>

                <div class="flex flex-col md:flex-row items-start md:items-center gap-4">
                    <!-- Status Badge -->
                    <span
                        class="px-4 py-2 rounded-full text-sm font-semibold 
                                {{ $application->status === 'approved'
                                    ? 'bg-green-100 text-green-800'
                                    : ($application->status === 'draft'
                                        ? 'bg-yellow-100 text-yellow-800'
                                        : 'bg-red-100 text-red-800') }}">
                        {{ ucfirst($application->status) }}
                    </span>

                    <!-- Stats Cards -->
                    <div class="flex gap-2">
                        <div class="bg-teal-50 px-3 py-1 rounded-lg flex items-center">
                            <svg class="w-4 h-4 text-teal-600 mr-1" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                            <span class="text-xs text-teal-800">{{ $application->experience_years }} yrs exp</span>
                        </div>
                        <div class="bg-teal-50 px-3 py-1 rounded-lg flex items-center">
                            <svg class="w-4 h-4 text-teal-600 mr-1" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z">
                                </path>
                            </svg>
                            <span
                                class="text-xs text-teal-800">{{ count(explode(',', $application->area_of_expertise)) }}
                                skills</span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Main Application Card -->
            <div class="bg-white rounded-xl shadow-lg overflow-hidden mb-8 border border-gray-100">
                <div class="bg-white p-4 rounded-lg shadow-sm border border-gray-200 w-full md:w-auto">
                    <div class="flex items-center space-x-4">
                        <div class="bg-teal-100 p-3 rounded-full">
                            <svg class="w-6 h-6 text-teal-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                            </svg>
                        </div>
                        <div>
                            <h3 class="font-medium text-gray-900">{{ $application->user->name }}</h3>
                            <div class="flex items-center text-sm text-gray-500 mt-1">
                                <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z">
                                    </path>
                                </svg>
                                {{ $application->user->email }}
                            </div>
                            @if ($application->user->phone)
                                <div class="flex items-center text-sm text-gray-500 mt-1">
                                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z">
                                        </path>
                                    </svg>
                                    {{ $application->user->phone }}
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
            <!-- Basic Information Section -->
            <div class="border-b border-gray-200 px-6 py-4">
                <h2 class="text-xl font-semibold text-teal-700 mb-4 flex items-center">
                    <svg class="w-5 h-5 mr-2 text-teal-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                    </svg>
                    Basic Information
                </h2>
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                    <div>
                        <p class="text-sm text-gray-500 font-medium">User ID</p>
                        <p class="font-medium text-gray-800">{{ $application->user_id }}</p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-500 font-medium">Professional Headline</p>
                        <p class="font-medium text-gray-800">{{ $application->headline }}</p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-500 font-medium">Years of Experience</p>
                        <div class="flex items-center">
                            <div class="w-full bg-gray-200 rounded-full h-2.5 mr-2">
                                <div class="bg-teal-600 h-2.5 rounded-full"
                                    style="width: {{ min(100, ($application->experience_years / 10) * 100) }}%"></div>
                            </div>
                            <span class="font-medium text-gray-800">{{ $application->experience_years }}</span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Expertise Section -->
            <div class="border-b border-gray-200 px-6 py-4">
                <h2 class="text-xl font-semibold text-teal-700 mb-4 flex items-center">
                    <svg class="w-5 h-5 mr-2 text-teal-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z">
                        </path>
                    </svg>
                    Areas of Expertise
                </h2>
                <div class="flex flex-wrap gap-2">
                    @foreach (explode(',', $application->area_of_expertise) as $expertise)
                        <span class="px-3 py-1 bg-teal-50 text-teal-800 text-sm rounded-full flex items-center">
                            <svg class="w-3 h-3 mr-1 text-teal-600" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M5 13l4 4L19 7"></path>
                            </svg>
                            {{ trim($expertise) }}
                        </span>
                    @endforeach
                </div>
            </div>

            <!-- Bio Section -->
            <div class="border-b border-gray-200 px-6 py-4">
                <h2 class="text-xl font-semibold text-teal-700 mb-4 flex items-center">
                    <svg class="w-5 h-5 mr-2 text-teal-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                    </svg>
                    Professional Bio
                </h2>
                <p class="whitespace-pre-line text-gray-700 leading-relaxed">{{ $application->bio }}</p>
            </div>

            <!-- Teaching History Section -->
            <div class="border-b border-gray-200 px-6 py-4">
                <h2 class="text-xl font-semibold text-teal-700 mb-4 flex items-center">
                    <svg class="w-5 h-5 mr-2 text-teal-600" fill="none" stroke="currentColor"
                        viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253">
                        </path>
                    </svg>
                    Teaching Philosophy & History
                </h2>
                {{-- <div class="bg-teal-50 p-4 rounded-lg mb-4">
                    <p class="whitespace-pre-line text-gray-700 italic leading-relaxed">
                        "{{ Str::limit($application->teaching_history, 200) }}"</p>
                </div> --}}
                <p class="whitespace-pre-line text-gray-700 leading-relaxed">{{ $application->teaching_history }}</p>
            </div>

            <!-- Documents Section -->
            <div class="px-6 py-4">
                <h2 class="text-xl font-semibold text-teal-700 mb-4 flex items-center">
                    <svg class="w-5 h-5 mr-2 text-teal-600" fill="none" stroke="currentColor"
                        viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z">
                        </path>
                    </svg>
                    Supporting Documents
                </h2>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    @if ($application->resume_path)
                        <div class="border border-gray-200 rounded-lg p-4 hover:border-teal-300 transition">
                            <div class="flex items-start">
                                <div class="p-2 bg-teal-100 rounded-lg mr-4">
                                    <svg class="w-6 h-6 text-teal-600" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z">
                                        </path>
                                    </svg>
                                </div>
                                <div>
                                    <h3 class="font-medium text-gray-800">Professional Resume</h3>
                                    <p class="text-sm text-gray-500 mb-2">PDF document</p>
                                    <a href="{{ asset('storage/' . $application->resume_path) }}" download
                                        class="text-sm text-teal-600 hover:text-teal-800 font-medium inline-flex items-center">
                                        Download
                                        <svg class="w-4 h-4 ml-1" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4">
                                            </path>
                                        </svg>
                                    </a>
                                </div>
                            </div>
                        </div>
                    @endif

                    @if ($application->intro_video_url)
                        <div class="border border-gray-200 rounded-lg p-4 hover:border-teal-300 transition">
                            <div class="flex items-start">
                                <div class="p-2 bg-teal-100 rounded-lg mr-4">
                                    <svg class="w-6 h-6 text-teal-600" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M15 10l4.553-2.276A1 1 0 0121 8.618v6.764a1 1 0 01-1.447.894L15 14M5 18h8a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z">
                                        </path>
                                    </svg>
                                </div>
                                <div>
                                    <h3 class="font-medium text-gray-800">Introduction Video</h3>
                                    <p class="text-sm text-gray-500 mb-2">YouTube link</p>
                                    <a href="{{ $application->intro_video_url }}" target="_blank"
                                        class="text-sm text-teal-600 hover:text-teal-800 font-medium inline-flex items-center">
                                        Watch Video
                                        <svg class="w-4 h-4 ml-1" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14">
                                            </path>
                                        </svg>
                                    </a>
                                </div>
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Action Buttons -->
        <div class="flex flex-col sm:flex-row justify-end gap-4 mt-8">
            @if ($application->status === 'approved')
                <button onclick="showRejectModal()"
                    class="px-6 py-3 border border-red-500 text-red-500 rounded-lg hover:bg-red-50 transition flex items-center justify-center">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M6 18L18 6M6 6l12 12">
                        </path>
                    </svg>
                    Reject Application
                </button>
            @endif
            @if (!$application->is_approved)
                <button type="button" data-modal-target="approve-modal" data-modal-toggle="approve-modal"
                    data-action-url = "{{ route('admin.instructors.applications.approve', $application) }}"
                    onclick="confirmApproval(this, {{ $application }})"
                    title="Approve"
                    class=" px-6 py-3 bg-teal-600 text-white rounded-lg hover:bg-teal-700 transition flex items-center justify-center">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7">
                        </path>
                    </svg>
                    Approve Application
                </button>
            @endif
        </div>

        <!-- Rejection Modal -->
        <div id="rejectModal"
            class="{{ $errors->any() ? 'fixed inset-0 bg-gray-600/40 bg-opacity-50 flex items-center justify-center z-50 px-4' : 'hide-modal fixed inset-0 bg-gray-600/40 bg-opacity-50 flex items-center justify-center z-50 px-4' }}">
            <div class="bg-white rounded-xl shadow-xl w-full max-w-md">
                <div class="p-6">
                    <div class="flex justify-between items-center mb-4">
                        <h3 class="text-xl font-semibold text-gray-900">Reject Application</h3>
                        <button onclick="hideRejectModal()" class="text-gray-400 hover:text-gray-500">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M6 18L18 6M6 6l12 12"></path>
                            </svg>
                        </button>
                    </div>

                    <form method="POST"
                        action="{{ route('admin.instructors.applications.deny', $application->id) }}">
                        @csrf
                        <div class="mb-6">
                            <label for="rejection_reason" class="block text-sm font-medium text-gray-700 mb-2">
                                Please provide a reason for rejection
                            </label>
                            <textarea id="rejection_reason" name="rejection_reason" rows="4"
                                class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-teal-500 focus:border-teal-500"></textarea>
                            <x-input-error :messages="$errors->get('rejection_reason')" />
                        </div>

                        <div class="flex justify-end gap-3">
                            <button type="button" onclick="hideRejectModal()"
                                class="px-4 py-2 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50">
                                Cancel
                            </button>
                            <button type="submit"
                                class="px-4 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700 flex items-center">
                                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M6 18L18 6M6 6l12 12"></path>
                                </svg>
                                Confirm Rejection
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        </div>

        {{-- approval Modal --}}
        <div id="approve-modal" tabindex="-1"
            class="hidden overflow-y-auto overflow-x-hidden fixed top-0 right-0 left-0 z-50 justify-center items-center w-full md:inset-0 h-[calc(100%-1rem)] max-h-full">
            <div class="relative p-4 w-full max-w-md max-h-full">
                <div class="relative bg-white rounded-lg shadow-sm">
                    <button type="button"
                        class="absolute top-3 end-2.5 text-gray-400 bg-transparent rounded-lg text-sm w-8 h-8 ms-auto inline-flex justify-center items-center hover:bg-gray-600 hover:text-white"
                        data-modal-hide="approve-modal">
                        <i data-lucide="x" class="w-3 h-3"></i>
                        <span class="sr-only">Close modal</span>
                    </button>
                    <div class="p-4 md:p-5 text-center">
                        <svg class="mx-auto mb-4 text-gray-400 w-12 h-12" aria-hidden="true"
                            xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 20 20">
                            <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"
                                stroke-width="2" d="M10 11V6m0 8h.01M19 10a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
                        </svg>
                        <h3 class="mb-5 text-lg font-normal text-gray-500" id="confirmText"></h3>
                        <div class="flex items-center justify-center">
                            <form id='modal-form' method="post">
                                @csrf
                                @method('PATCH')
                                <button data-modal-hide="approve-modal" type="submit"
                                    class="text-white bg-green-600 hover:bg-green-800 focus:ring-4 focus:outline-none focus:ring-red-300 font-medium rounded-lg text-sm inline-flex items-center px-5 py-2.5 text-center">
                                    Yes, I'm sure
                                </button>
                            </form>
                            <button data-modal-hide="approve-modal" type="button"
                                class="py-2.5 px-5 ms-3 text-sm font-medium text-gray-900 focus:outline-none bg-white rounded-lg border border-gray-200 hover:bg-gray-100 hover:text-blue-700 focus:z-10 focus:ring-4 focus:ring-gray-100">
                                No, cancel
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <script>
            const approvalForm = document.getElementById("modal-form");
            const confirmText = document.getElementById("confirmText");

            function confirmApproval(button, profile) {
                console.log(button)
                confirmText.innerText = `Are you sure you want to approve the application for ${profile.application_id}?`;
                approvalForm.action = button.getAttribute('data-action-url');
            }

            function showRejectModal() {
                document.getElementById('rejectModal').classList.remove('hide-modal');
                document.body.classList.add('overflow-hidden');
            }

            function hideRejectModal() {
                document.getElementById('rejectModal').classList.add('hide-modal');
                document.body.classList.remove('overflow-hidden');
            }
        </script>
    </x-instructor-dashbord-layout>
</x-app-layout>
