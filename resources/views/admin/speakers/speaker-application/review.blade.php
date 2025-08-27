<x-app-layout>
    <div class="px-4 mx-auto max-w-7xl">
        <!-- Header Section -->
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 mb-6">
            <div class="flex items-center gap-3">
                <a href="{{ route('admin.speakers.index') }}"
                    class="inline-flex items-center justify-center overflow-hidden w-10 h-10 bg-white border rounded-full border-gray-300 text-gray-700 text-sm font-medium hover:bg-gray-50 transition shadow-sm">
                    <i data-lucide="arrow-left" class="w-6 h-6"></i>
                </a>
                <div class="p-2 rounded-lg bg-yellow-50">
                    <i data-lucide="clipboard-list" class="w-6 h-6 text-yellow-600"></i>
                </div>
                <div>
                    <h1 class="text-2xl font-bold text-gray-900">Review Application</h1>
                    <p class="text-sm text-gray-500">Review speaker session proposal details</p>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
            <!-- Application Header -->
            <div class="px-6 py-4 border-b border-gray-200">
                <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
                    <div>
                        <h2 class="text-xl font-semibold text-gray-900">
                            {{ $application->topic_title ?? 'Untitled Session' }}
                        </h2>
                        <div class="flex flex-wrap items-center gap-2 mt-1">
                            <span class="text-sm text-gray-500">Submitted
                                {{ $application->created_at->format('M j, Y') ?? 'N/A' }}</span>
                            @if(isset($application->event))
                                <span
                                    class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                    <i data-lucide="calendar" class="w-3 h-3 mr-1"></i>
                                    {{ $application->event->title ?? 'Unknown Event' }}
                                </span>
                            @endif
                        </div>
                    </div>
                </div>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 p-6">
                <!-- Session Details -->
                <div class="lg:col-span-2 space-y-6">
                    <div class="border border-gray-200 rounded-lg p-5">
                        <h3 class="text-lg font-medium text-gray-900 mb-4">Session Information</h3>
                        <div class="space-y-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Topic Title</label>
                                <p class="text-gray-900">{{ $application->topic_title ?? 'N/A' }}</p>
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Description</label>
                                <p class="text-gray-900 whitespace-pre-line">
                                    {{ $application->topic_description ?? 'No description provided' }}
                                </p>
                            </div>

                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Session Format</label>
                                    <p class="text-gray-900 capitalize">{{ $application->session_format ?? 'N/A' }}</p>
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Status</label>
                                    <p class="text-gray-900 capitalize">{{ $application->status ?? 'N/A' }}</p>
                                </div>
                            </div>

                            @if(isset($application->event))
                                <div class="border-t border-gray-200 pt-4">
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Event Information</label>
                                    <div class="flex items-center text-sm text-gray-600">
                                        <i data-lucide="calendar" class="w-4 h-4 mr-2 text-gray-400"></i>
                                        <span>{{ $application->event->title ?? 'Unknown Event' }}</span>
                                        @if(isset($application->event->start_date))
                                            <span class="mx-2">•</span>
                                            <span>{{ \Carbon\Carbon::parse($application->event->start_date)->format('M j, Y') }}</span>
                                        @endif
                                    </div>
                                </div>
                            @endif
                        </div>
                    </div>

                    <div class="border border-gray-200 rounded-lg p-5">
                        <h3 class="text-lg font-medium text-gray-900 mb-4">Speaker Information</h3>
                        <div class="flex flex-col sm:flex-row gap-4">
                            <div class="flex-shrink-0">
                                @if(isset($application->speaker->photo))
                                    <img class="h-16 w-16 rounded-full object-cover border-2 border-gray-200"
                                        src="{{ asset('storage/' . $application->speaker->photo) }}"
                                        alt="{{ $application->speaker->name ?? 'Speaker' }}">
                                @else
                                    <div class="h-16 w-16 rounded-full bg-gray-200 flex items-center justify-center">
                                        <i data-lucide="user" class="w-8 h-8 text-gray-400"></i>
                                    </div>
                                @endif
                            </div>
                            <div class="flex-grow">
                                <h4 class="text-lg font-medium text-gray-900">
                                    {{ $application->speaker->name ?? 'Unknown Speaker' }}
                                </h4>
                                <p class="text-teal-600">
                                    {{ $application->speaker->title ?? 'N/A' }}
                                    @if(isset($application->speaker->organization))
                                        at {{ $application->speaker->organization }}
                                    @endif
                                </p>

                                <div class="mt-3 grid grid-cols-1 md:grid-cols-2 gap-3">
                                    <div class="flex items-center text-sm text-gray-600">
                                        <i data-lucide="mail" class="w-4 h-4 mr-2 text-gray-400"></i>
                                        {{ $application->speaker->email ?? 'No email provided' }}
                                    </div>
                                    <div class="flex items-center text-sm text-gray-600">
                                        <i data-lucide="phone" class="w-4 h-4 mr-2 text-gray-400"></i>
                                        {{ $application->speaker->phone ?? 'No phone provided' }}
                                    </div>
                                </div>

                                <div class="mt-3">
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Bio</label>
                                    <p class="text-gray-900">{{ $application->speaker->bio ?? 'No bio provided' }}</p>
                                </div>

                                <div class="mt-3 flex flex-wrap gap-2">
                                    @if(isset($application->speaker->linkedin))
                                        <a href="{{ $application->speaker->linkedin }}" target="_blank"
                                            class="inline-flex items-center px-2.5 py-1 border border-gray-300 text-xs font-medium rounded-full text-gray-700 bg-white hover:bg-gray-50">
                                            <i data-lucide="linkedin" class="w-3 h-3 mr-1"></i>
                                            LinkedIn
                                        </a>
                                    @endif

                                    @if(isset($application->speaker->website))
                                        <a href="{{ $application->speaker->website }}" target="_blank"
                                            class="inline-flex items-center px-2.5 py-1 border border-gray-300 text-xs font-medium rounded-full text-gray-700 bg-white hover:bg-gray-50">
                                            <i data-lucide="globe" class="w-3 h-3 mr-1"></i>
                                            Website
                                        </a>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                </div>


                <!-- Review Panel -->
                <div class="space-y-6">
                    <div class="border border-gray-200 rounded-lg p-5">
                        <h3 class="text-lg font-medium text-gray-900 mb-4">Review Actions</h3>
                        <div class="space-y-3">
                            @if($application->status == 'pending' || $application->status == 'rejected')
                                <button type="button" data-modal-target="action-modal" data-modal-toggle="action-modal"
                                    data-action="{{ route('admin.speakers.application.approve', $application) }}"
                                    data-method="POST" data-title="Approve Speaker Application"
                                    data-message="Please ensure you have thoroughly reviewed this application before approving. "
                                    data-icon='<i data-lucide="check-circle" class="h-6 w-6 text-teal-600"></i>'
                                    class="w-full inline-flex items-center justify-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-teal-600 hover:bg-teal-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-teal-500">
                                    <i data-lucide="check-circle" class="w-4 h-4 mr-2"></i>
                                    Approve Application
                                </button>
                                <button type="button"
                                type="button" data-modal-target="feedback-modal" data-modal-toggle="feedback-modal"
                                    data-action="{{ route("admin.speakers.application.reject", $application) }}"
                                    data-method="post"
                                    data-spoofMethod='patch'
                                    data-title="Reject Speaker Application Approval"
                                    data-message="Please provide a reason for rejecting {{ $application->speaker->name }}’s application approval:"
                                    data-confirm-text="Reject Application"
                                    class="w-full inline-flex items-center cursor-pointer justify-center px-4 py-2 border border-red-300 text-sm font-medium rounded-md shadow-sm text-white bg-red-700 hover:bg-red-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-teal-500">
                                    <i data-lucide="x-circle" class="w-4 h-4 mr-2"></i>
                                    Reject Application
                                </button>
                            @else
                                <button type="button" data-modal-target="feedback-modal" data-modal-toggle="feedback-modal"
                                    data-action="{{ route("admin.speakers.application.reject", $application) }}"
                                    data-method="post"
                                    data-spoofMethod='patch'
                                    data-title="Revoke Speaker Application Approval"
                                    data-message="Please provide a reason for revoking {{ $application->speaker->name }}’s application approval:"
                                    data-confirm-text="Revoke Approval"
                                    class="w-full inline-flex items-center cursor-pointer justify-center px-4 py-2 border border-red-300 text-sm font-medium rounded-md shadow-sm text-white bg-red-700 hover:bg-red-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-teal-500">
                                    <i data-lucide="x-circle" class="w-4 h-4 mr-2"></i>
                                    Revoke Approval
                                </button>
                            @endif
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>

</x-app-layout>
