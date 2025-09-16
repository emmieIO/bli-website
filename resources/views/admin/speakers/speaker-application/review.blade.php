<x-app-layout>
    <div class="px-4 mx-auto max-w-7xl py-8">
        <!-- Header Section -->
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 mb-8">
            <div class="flex items-center gap-3">
                <a href="{{ route('admin.speakers.index') }}"
                    class="inline-flex items-center justify-center overflow-hidden w-10 h-10 bg-white border rounded-full border-gray-300 text-gray-700 text-sm font-medium hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-[#00275E] transition shadow-sm"
                    title="Back to Speakers">
                    <i data-lucide="arrow-left" class="w-5 h-5"></i>
                </a>
                <div class="p-2.5 rounded-lg bg-[#00275E]/10">
                    <i data-lucide="clipboard-list" class="w-6 h-6 text-[#00275E]"></i>
                </div>
                <div>
                    <h1 class="text-2xl font-extrabold text-[#00275E]">Review Speaker Application</h1>
                    <p class="text-sm text-gray-500">Review speaker session proposal details</p>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
            <!-- Application Header -->
            <div class="px-6 py-5 border-b border-gray-200 bg-gray-50">
                <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
                    <div>
                        <h2 class="text-xl font-bold text-gray-900">
                            {{ $application->topic_title ?? 'Untitled Session' }}
                        </h2>
                        <div class="flex flex-wrap items-center gap-3 mt-2">
                            <span
                                class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-800">
                                Submitted {{ $application->created_at->format('M j, Y') }}
                            </span>
                            @if ($application->status == 'approved')
                                <span
                                    class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                    <i data-lucide="check" class="w-3 h-3 mr-1"></i>
                                    Approved
                                </span>
                            @elseif($application->status == 'rejected')
                                <span
                                    class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800">
                                    <i data-lucide="x" class="w-3 h-3 mr-1"></i>
                                    Rejected
                                </span>
                            @else
                                <span
                                    class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">
                                    <i data-lucide="clock" class="w-3 h-3 mr-1"></i>
                                    Pending
                                </span>
                            @endif

                            @if (isset($application->event))
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
                    <!-- Session Information Card -->
                    <div class="border border-gray-200 rounded-xl p-6 bg-gray-50">
                        <h3 class="text-lg font-semibold text-gray-900 mb-4 flex items-center">
                            <i data-lucide="presentation" class="w-5 h-5 mr-2 text-[#00275E]"></i>
                            Session Information
                        </h3>
                        <div class="space-y-5">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Topic Title</label>
                                <p class="text-gray-900 text-base font-medium">{{ $application->topic_title ?? 'N/A' }}
                                </p>
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Description</label>
                                <div class="prose prose-sm max-w-none text-gray-800 bg-white p-3 rounded-lg border">
                                    {!! nl2br(
                                        e($application->topic_description ?? '<span class="text-gray-400 italic">No description provided.</span>'),
                                    ) !!}
                                </div>
                            </div>

                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Session Format</label>
                                    <span
                                        class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-medium bg-blue-100 text-blue-800 capitalize">
                                        {{ $application->session_format->value ?? 'N/A' }}
                                    </span>
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Application
                                        Status</label>
                                    @if ($application->status == 'approved')
                                        <span
                                            class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                            Approved
                                        </span>
                                    @elseif($application->status == 'rejected')
                                        <span
                                            class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-medium bg-red-100 text-red-800">
                                            Rejected
                                        </span>
                                    @else
                                        <span
                                            class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">
                                            Pending
                                        </span>
                                    @endif
                                </div>
                            </div>

                            @if (isset($application->event))
                                <div class="border-t border-gray-200 pt-4">
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Event
                                        Information</label>
                                    <div class="flex flex-col sm:flex-row sm:items-center gap-2 text-sm text-gray-600">
                                        <div class="flex items-center">
                                            <i data-lucide="calendar" class="w-4 h-4 mr-2 text-gray-400"></i>
                                            <span>{{ $application->event->title ?? 'Unknown Event' }}</span>
                                        </div>
                                        @if (isset($application->event->start_date))
                                            <div class="flex items-center">
                                                <span class="mx-2 hidden sm:inline">•</span>
                                                <i data-lucide="clock" class="w-4 h-4 mr-2 text-gray-400"></i>
                                                <span>{{ sweet_date($application->event->start_date) }}</span>
                                            </div>
                                        @endif
                                    </div>
                                </div>
                            @endif
                        </div>
                    </div>

                    <!-- Speaker Information Card -->
                    <div class="border border-gray-200 rounded-xl p-6 bg-gray-50">
                        <h3 class="text-lg font-semibold text-gray-900 mb-4 flex items-center">
                            <i data-lucide="mic" class="w-5 h-5 mr-2 text-[#00275E]"></i>
                            Speaker Information
                        </h3>
                        <div class="flex flex-col sm:flex-row gap-6">
                            <div class="flex-shrink-0">
                                @if (isset($application->speaker->photo))
                                    <img class="h-20 w-20 rounded-full object-cover border-2 border-gray-200 shadow-sm"
                                        src="{{ asset('storage/' . $application->speaker->photo) }}"
                                        alt="{{ $application->user->name ?? 'Speaker' }}">
                                @else
                                    <div
                                        class="h-20 w-20 rounded-full bg-gray-200 flex items-center justify-center border-2 border-dashed border-gray-300">
                                        <span class="text-lg font-bold text-gray-500">
                                            {{ strtoupper(substr($application->user->name ?? 'S', 0, 1)) }}
                                        </span>
                                    </div>
                                @endif
                            </div>
                            <div class="flex-grow">
                                <h4 class="text-xl font-bold text-gray-900 mb-1">
                                    {{ $application->user->name ?? 'Unknown Speaker' }}
                                </h4>
                                <p class="text-[#00275E] font-medium mb-4">
                                    {{ $application->speaker->title ?? 'N/A' }}
                                    @if (isset($application->speaker->organization))
                                        at <span class="text-gray-700">{{ $application->speaker->organization }}</span>
                                    @endif
                                </p>

                                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                                    <div class="flex items-center text-sm text-gray-600 p-2 bg-white rounded-lg border">
                                        <i data-lucide="mail" class="w-4 h-4 mr-2 text-[#00275E]"></i>
                                        <span>{{ $application->user->email ?? 'No email provided' }}</span>
                                    </div>
                                    <div class="flex items-center text-sm text-gray-600 p-2 bg-white rounded-lg border">
                                        <i data-lucide="phone" class="w-4 h-4 mr-2 text-[#00275E]"></i>
                                        <span>{{ $application->user->phone ?? 'No phone provided' }}</span>
                                    </div>
                                </div>

                                <div class="mb-4">
                                    <label class="block text-sm font-medium text-gray-700 mb-2">Bio</label>
                                    <div class="prose prose-sm max-w-none text-gray-800 bg-white p-3 rounded-lg border">
                                        @if (!empty($application->speaker->bio))
                                            {!! nl2br(e($application->speaker->bio)) !!}
                                        @else
                                            <span class="text-gray-400 italic">No bio provided.</span>
                                        @endif
                                    </div>
                                </div>

                                <div class="flex flex-wrap gap-2">
                                    @if (isset($application->speaker->linkedin))
                                        <a href="{{ $application->speaker->linkedin }}" target="_blank"
                                            class="inline-flex items-center px-3 py-1.5 border border-gray-300 text-xs font-medium rounded-lg text-gray-700 bg-white hover:bg-gray-50 hover:text-[#00275E] transition">
                                            <i data-lucide="linkedin" class="w-3 h-3 mr-1.5"></i>
                                            LinkedIn
                                        </a>
                                    @endif

                                    @if (isset($application->speaker->website))
                                        <a href="{{ $application->speaker->website }}" target="_blank"
                                            class="inline-flex items-center px-3 py-1.5 border border-gray-300 text-xs font-medium rounded-lg text-gray-700 bg-white hover:bg-gray-50 hover:text-[#00275E] transition">
                                            <i data-lucide="globe" class="w-3 h-3 mr-1.5"></i>
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
                    <!-- Review Actions Card -->
                    <div class="border border-gray-200 rounded-xl p-6 bg-gray-50">
                        <h3 class="text-lg font-semibold text-gray-900 mb-4 flex items-center">
                            <i data-lucide="clipboard-check" class="w-5 h-5 mr-2 text-[#00275E]"></i>
                            Review Actions
                        </h3>
                        <div class="space-y-3">
                            @if ($application->status == 'pending')
                                <!-- Approve -->
                                <button type="button" data-modal-target="action-modal"
                                    data-modal-toggle="action-modal"
                                    data-action="{{ route('admin.speakers.application.approve', $application) }}"
                                    data-method="POST" data-title="Approve Speaker Application"
                                    data-message="Please ensure you have thoroughly reviewed this application before approving."
                                    data-icon='<i data-lucide="check-circle" class="h-6 w-6 text-green-600"></i>'
                                    class="w-full inline-flex items-center justify-center px-4 py-2.5 border border-transparent text-sm font-medium rounded-lg shadow-sm text-white bg-[#00275E] hover:bg-[#08387b] focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-[#00275E] transition">
                                    <i data-lucide="check-circle" class="w-4 h-4 mr-2"></i>
                                    Approve Application
                                </button>

                                <!-- Reject -->
                                <button type="button" data-modal-target="feedback-modal"
                                    data-modal-toggle="feedback-modal"
                                    data-action="{{ route('admin.speakers.application.reject', $application) }}"
                                    data-method="POST" data-spoofMethod="PATCH"
                                    data-title="Reject Speaker Application"
                                    data-message="Please provide a reason for rejecting {{ $application->user->name }}’s application:"
                                    data-confirm-text="Reject Application"
                                    class="w-full inline-flex items-center justify-center px-4 py-2.5 border border-red-300 text-sm font-medium rounded-lg shadow-sm text-white bg-red-600 hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500 transition">
                                    <i data-lucide="x-circle" class="w-4 h-4 mr-2"></i>
                                    Reject Application
                                </button>
                            @elseif($application->status == 'approved')
                                <!-- Revoke -->
                                <button type="button"
                                    data-modal-target="feedback-modal"
                                    data-modal-toggle="feedback-modal"
                                    data-action="{{ route('admin.speakers.application.reject', $application) }}"
                                    data-method="POST"
                                    data-spoofMethod="PATCH"
                                    data-title="Revoke Speaker Approval"
                                    data-message="Please provide a reason for revoking approval for {{ $application->speaker->name }}’s application:"
                                    data-confirm-text="Revoke Approval"
                                    class="w-full inline-flex items-center justify-center px-4 py-2.5 border border-red-300 text-sm font-medium rounded-lg shadow-sm text-white bg-red-600 hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500 transition">
                                    <i data-lucide="rotate-ccw" class="w-4 h-4 mr-2"></i>
                                    Revoke Approval
                                </button>
                            @elseif($application->status == 'rejected')
                                <!-- Reconsider / Resubmit? -->
                                <div class="text-center py-4 text-gray-500 text-sm">
                                    This application was previously rejected.
                                    <br><br>
                                    To reconsider, ask the speaker to resubmit or update their application.
                                </div>
                            @endif
                        </div>
                    </div>

                    <!-- Application Metadata -->
                    <div class="border border-gray-200 rounded-xl p-6 bg-gray-50">
                        <h3 class="text-lg font-semibold text-gray-900 mb-4">Application Timeline</h3>
                        <div class="space-y-3 text-sm">
                            <div class="flex justify-between">
                                <span class="text-gray-600">Submitted</span>
                                <span
                                    class="font-medium text-gray-900">{{ sweet_date($application->created_at) }}</span>
                            </div>
                            @if ($application->approved_at)
                                <div class="flex justify-between">
                                    <span class="text-gray-600">Approved</span>
                                    <span
                                        class="font-medium text-green-700">{{ sweet_date($application->approved_at) }}</span>
                                </div>
                            @endif
                            @if ($application->rejected_at)
                                <div class="flex justify-between">
                                    <span class="text-gray-600">Rejected</span>
                                    <span
                                        class="font-medium text-red-700">{{ sweet_date($application->rejected_at) }}</span>
                                </div>
                            @endif
                            @if ($application->updated_at && $application->updated_at != $application->created_at)
                                <div class="flex justify-between">
                                    <span class="text-gray-600">Last Updated</span>
                                    <span
                                        class="font-medium text-gray-900">{{ sweet_date($application->updated_at) }}</span>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- You may include modals here if needed -->
    <!-- Example: Feedback Modal, Action Confirmation Modal -->
    <!-- Ensure modals match brand colors if implemented -->

</x-app-layout>
