<x-app-layout>
    <div class="py-10 px-4 sm:px-6 lg:px-8">

        <!-- Event Card -->
        <div
            class="bg-white rounded-3xl shadow-xl border border-gray-50 overflow-hidden mb-10 transition-all duration-300 hover:shadow-2xl">
            <div class="p-8">
                <div class="flex items-start sm:items-center space-x-4 mb-6">
                    <div class="p-3 bg-indigo-50 rounded-xl">
                        <i data-lucide="calendar" class="w-6 h-6 text-indigo-600" aria-hidden="true"></i>
                    </div>
                    <div>
                        <h1 class="text-3xl font-extrabold text-gray-900 tracking-tight leading-tight">
                            {{ $event->title }}
                        </h1>
                        @if ($event->mode === 'offline')
                            <span
                                class="inline-flex items-center mt-2 px-3 py-1 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                <i data-lucide="map-pin" class="w-3 h-3 mr-1" aria-hidden="true"></i>
                                In-Person Event
                            </span>
                        @else
                            <span
                                class="inline-flex items-center mt-2 px-3 py-1 rounded-full text-xs font-medium bg-purple-100 text-purple-800">
                                <i data-lucide="globe" class="w-3 h-3 mr-1" aria-hidden="true"></i>
                                Online Event
                            </span>
                        @endif
                    </div>
                </div>

                <p class="text-gray-700 leading-relaxed mb-8 text-lg">
                    {{ Str::limit($event->description, 280) }}
                </p>

                <div class="grid sm:grid-cols-2 lg:grid-cols-3 gap-5 text-sm">
                    <div class="flex items-start space-x-3 p-4 bg-gray-50 rounded-xl">
                        <i data-lucide="map-pin" class="w-5 h-5 text-gray-500 mt-0.5 flex-shrink-0"
                            aria-hidden="true"></i>
                        <div>
                            <p class="font-medium text-gray-900">Location</p>
                            <p class="text-gray-600 mt-1">
                                {{ $event->mode === 'offline' ? $event->physical_address : $event->location }}
                            </p>
                        </div>
                    </div>

                    <div class="flex items-start space-x-3 p-4 bg-gray-50 rounded-xl">
                        <i data-lucide="mail" class="w-5 h-5 text-gray-500 mt-0.5 flex-shrink-0" aria-hidden="true"></i>
                        <div>
                            <p class="font-medium text-gray-900">Contact</p>
                            <p class="text-gray-600 mt-1 break-all">{{ $event->contact_email }}</p>
                        </div>
                    </div>

                    <div class="flex items-start space-x-3 p-4 bg-gray-50 rounded-xl">
                        <i data-lucide="clock" class="w-5 h-5 text-gray-500 mt-0.5 flex-shrink-0"
                            aria-hidden="true"></i>
                        <div>
                            <p class="font-medium text-gray-900">Date & Time</p>
                            <p class="text-gray-600 mt-1">
                                {{ \Carbon\Carbon::parse($event->start_date)->format('M j, Y • g:i A') }}<br>
                                <span class="text-xs text-gray-500">to</span><br>
                                {{ \Carbon\Carbon::parse($event->end_date)->format('M j, Y • g:i A') }}
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Invitation Card -->
        <div
            class="bg-white rounded-3xl shadow-xl border border-gray-50 overflow-hidden transition-all duration-300 hover:shadow-2xl">
            <div class="p-8">
                <div class="flex items-start sm:items-center space-x-4 mb-8">
                    <div class="p-3 bg-indigo-50 rounded-xl">
                        <i data-lucide="user" class="w-6 h-6 text-indigo-600" aria-hidden="true"></i>
                    </div>
                    <div>
                        <h2 class="text-2xl font-bold text-gray-900">Your Invitation Details</h2>
                        <p class="text-gray-600 mt-1">Please review the details below before responding.</p>
                    </div>
                </div>

                <div class="space-y-6 mb-8">
                    @php
                        $fields = [
                            'Suggested Topic' => $invite->suggested_topic,
                            'Suggested Duration' => $invite->suggested_duration . ' minutes',
                            'Audience Expectations' => $invite->audience_expectations,
                            'Expected Format' => $invite->expected_format,
                            'Special Instructions' => $invite->special_instructions,
                        ];
                    @endphp

                    @foreach ($fields as $label => $value)
                        <div class="border-l-4 border-indigo-200 pl-5 py-2">
                            <p class="text-sm font-semibold text-gray-500 uppercase tracking-wide">{{ $label }}
                            </p>
                            <p class="text-gray-800 mt-1 leading-relaxed">{{ $value }}</p>
                        </div>
                    @endforeach

                    <div class="pt-4 border-t border-gray-100">
                        <div class="flex items-center space-x-2">
                            <i data-lucide="timer" class="w-4 h-4 text-amber-500" aria-hidden="true"></i>
                            <span class="text-sm font-medium text-gray-700">
                                Expires {{ \Carbon\Carbon::parse($invite->expires_at)->diffForHumans() }}
                            </span>
                            @if (\Carbon\Carbon::parse($invite->expires_at)->isPast())
                                <span
                                    class="ml-2 inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800">
                                    Expired
                                </span>
                            @endif
                        </div>
                    </div>
                </div>

                <!-- Action Buttons -->
                @php
                    $isExpired = \Carbon\Carbon::parse($invite->expires_at)->isPast();
                @endphp

                @if ($isExpired)
                    <div class="bg-amber-50 border border-amber-200 rounded-xl p-5 text-center">
                        <i data-lucide="alert-triangle" class="w-10 h-10 text-amber-500 mx-auto mb-3"
                            aria-hidden="true"></i>
                        <h3 class="font-semibold text-amber-800">This invitation has expired</h3>
                        <p class="text-amber-700 mt-1 text-sm">You can no longer respond to this invitation.</p>
                    </div>
                @else
                    <div
                        class="flex flex-col sm:flex-row justify-center sm:justify-start gap-4 pt-6 border-t border-gray-100">
                        <button 
                            type="button"
                            data-modal-target="action-modal"
                            data-modal-toggle="action-modal"
                            data-action=""
                            data-method="POST"
                            data-title="Accept Invitation"
                            data-message="Are you sure you want to accept this invitation? This action cannot be undone."
                            data-icon='<i data-lucide="check-circle" class="h-6 w-6 text-green-600"></i>'
                            class="inline-flex items-center justify-center px-8 py-3.5 rounded-2xl bg-green-600 hover:bg-green-700 text-white font-semibold shadow-lg hover:shadow-xl transform hover:-translate-y-0.5 transition-all duration-200"
                            aria-label="Accept invitation">
                            <i data-lucide="check" class="w-4 h-4 mr-2 transition-transform group-hover:scale-110"
                                aria-hidden="true"></i>
                            Accept Invitation
                        </button>

                        <button type="button"
                            data-modal-target="feedback-modal"
                            data-modal-toggle="feedback-modal"
                            data-action=""
                            data-method="POST" data-spoofMethod="PATCH"
                            data-title="Decline Invitation"
                            data-message="We appreciate your consideration. Kindly share your reason for declining this invitation:"
                            data-confirm-text="Decline Invitation"
                            class="inline-flex items-center justify-center px-8 py-3.5 rounded-2xl bg-red-600 hover:bg-red-700 text-white font-semibold shadow-lg hover:shadow-xl transform hover:-translate-y-0.5 transition-all duration-200"
                            aria-label="Decline invitation">
                            <i data-lucide="x" class="w-4 h-4 mr-2" aria-hidden="true"></i>
                            Decline Invitation
                        </button>
                    </div>
                @endif
            </div>
        </div>

    </div>

</x-app-layout>
