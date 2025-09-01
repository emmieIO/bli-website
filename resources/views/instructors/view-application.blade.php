<x-guest-layout>
    <div class="max-w-3xl mx-auto py-12 px-4 sm:px-6 lg:px-8">
        <h1 class="text-3xl font-extrabold text-gray-900 mb-8 text-center">Your Instructor Application</h1>

        <div class="bg-white shadow-lg rounded-lg overflow-hidden border border-gray-200">
            <div class="px-6 py-5 bg-gray-50 border-b border-gray-200">
                <h2 class="text-xl font-semibold text-gray-800">Application Details</h2>
                <p class="mt-1 text-sm text-gray-500">Review your submitted information below.</p>
            </div>
            <div class="px-6 py-8">
                <dl class="space-y-6">
                    <!-- Full Name -->
                    <div class="sm:flex sm:items-center sm:justify-between">
                        <dt class="text-sm font-medium text-gray-500">Full Name</dt>
                        <dd class="mt-1 text-sm text-gray-900 sm:mt-0">{{ $application->user->name }}</dd>
                    </div>

                    <!-- Email Address -->
                    <div class="sm:flex sm:items-center sm:justify-between">
                        <dt class="text-sm font-medium text-gray-500">Email Address</dt>
                        <dd class="mt-1 text-sm text-gray-900 sm:mt-0">{{ $application->user->email }}</dd>
                    </div>

                    <!-- Phone Number -->
                    <div class="sm:flex sm:items-center sm:justify-between">
                        <dt class="text-sm font-medium text-gray-500">Phone Number</dt>
                        <dd class="mt-1 text-sm text-gray-900 sm:mt-0">{{ $application->user->phone }}</dd>
                    </div>

                    <!-- LinkedIn Profile -->
                    <div class="sm:flex sm:items-center sm:justify-between">
                        <dt class="text-sm font-medium text-gray-500">LinkedIn Profile</dt>
                        <dd class="mt-1 text-sm text-gray-900 sm:mt-0">
                            @if($application->linkedin_profile)
                                <a href="{{ $application->linkedin_profile }}" target="_blank" class="text-blue-600 hover:text-blue-700 hover:underline flex items-center gap-1">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6a2 2 0 110-4 2 2 0 010 4zm0 8a2 2 0 110-4 2 2 0 010 4zm-2 6a2 2 0 104 0 2 2 0 00-4 0z"></path>
                                    </svg>
                                    View Profile
                                </a>
                            @else
                                <span class="text-gray-400">N/A</span>
                            @endif
                        </dd>
                    </div>

                    <!-- Areas of Expertise -->
                    <div class="sm:flex sm:items-center sm:justify-between">
                        <dt class="text-sm font-medium text-gray-500">Areas of Expertise</dt>
                        <dd class="mt-1 text-sm text-gray-900 sm:mt-0">
                            @foreach(explode(',', $application->area_of_expertise) as $expertise)
                                <span class="inline-block bg-gray-100 text-gray-800 px-2 py-1 rounded mr-2 mb-1">{{ trim($expertise) }}</span>
                            @endforeach
                        </dd>
                    </div>

                    <!-- Brief Bio -->
                    <div class="sm:flex sm:items-start sm:justify-between">
                        <dt class="text-sm font-medium text-gray-500">Brief Bio</dt>
                        <dd class="mt-1 text-sm text-gray-900 sm:mt-0">{{ $application->bio }}</dd>
                    </div>

                    <!-- Resume/CV -->
                    <div class="sm:flex sm:items-center sm:justify-between">
                        <dt class="text-sm font-medium text-gray-500">Resume/CV</dt>
                        <dd class="mt-1 text-sm text-gray-900 sm:mt-0">
                            @if($application->resume_path)
                                <a href="{{ asset($application->resume_path) }}" download class="text-blue-600 hover:text-blue-700 hover:underline flex items-center gap-1">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"></path>
                                    </svg>
                                    Download Resume/CV
                                </a>
                            @else
                                <span class="text-gray-400">N/A</span>
                            @endif
                        </dd>
                    </div>

                    <!-- Submitted At -->
                    <div class="sm:flex sm:items-center sm:justify-between">
                        <dt class="text-sm font-medium text-gray-500">Submitted At</dt>
                        <dd class="mt-1 text-sm text-gray-900 sm:mt-0">{{ $application->created_at->format('F j, Y, g:i a') }}</dd>
                    </div>
                </dl>
            </div>
        </div>
    </div>
</x-guest-layout>