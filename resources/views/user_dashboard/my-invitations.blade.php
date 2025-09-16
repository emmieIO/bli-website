<x-app-layout>
    <div class="max-w-7xl mx-auto py-10 px-4 sm:px-6 lg:px-8">

        <div class="mb-10">
            <h1 class="text-3xl font-extrabold text-gray-900 tracking-tight">Your Event Invitations</h1>
            <p class="mt-2 text-lg text-gray-600">Review and manage invitations you've received.</p>
        </div>

        @if (empty($invitations))
            <div class="bg-gray-50 rounded-2xl p-12 text-center">
                <i data-lucide="inbox" class="w-16 h-16 text-gray-400 mx-auto mb-4" aria-hidden="true"></i>
                <h3 class="text-xl font-medium text-gray-900">No invitations yet</h3>
                <p class="mt-2 text-gray-500">You’ll see invitations here when you receive them.</p>
            </div>
        @else
            <div class="grid gap-8 sm:grid-cols-2 lg:grid-cols-3">
                @foreach ($invitations as $invite)
                    @php
                        $isExpired = \Carbon\Carbon::parse($invite->expires_at)->isPast();
                        $status = $isExpired ? 'expired' : $invite->status;
                        $eventDate = \Carbon\Carbon::parse($invite->event_date);
                        $expiresIn = \Carbon\Carbon::parse($invite->expires_at)->diffForHumans();
                    @endphp

                    <div
                        class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden hover:shadow-xl transition-all duration-300 hover:-translate-y-1 flex flex-col">
                        <div class="p-6 flex-grow">
                            <div class="flex items-start justify-between mb-4">
                                <div class="p-2 bg-indigo-50 rounded-lg">
                                    <i data-lucide="{{ $invite->event->mode === 'offline' ? 'map-pin' : 'globe' }}"
                                        class="w-5 h-5 text-indigo-600" aria-hidden="true"></i>
                                </div>

                                <!-- Status Badge -->
                                <span
                                    class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                                    @if ($status === 'accepted') bg-green-100 text-green-800
                                    @elseif($status === 'declined') bg-red-100 text-red-800
                                    @elseif($status === 'expired') bg-gray-100 text-gray-800
                                    @else bg-amber-100 text-amber-800 @endif">
                                    {{ ucfirst($status) }}
                                </span>
                            </div>

                            <h3 class="text-xl font-bold text-gray-900 mb-2 leading-tight">
                                {{ Str::limit($invite->event->title, 50) }}
                            </h3>

                            <div class="flex items-center text-sm text-gray-600 mt-2 mb-3">
                                <i data-lucide="calendar" class="w-4 h-4 mr-1.5" aria-hidden="true"></i>
                                {{ $eventDate->format('M j, Y • g:i A') }}
                            </div>

                            <div class="flex items-start text-sm text-gray-600 mb-4">
                                <i data-lucide="map-pin" class="w-4 h-4 mr-1.5 mt-0.5 flex-shrink-0"
                                    aria-hidden="true"></i>
                                <span
                                    class="break-words">{{ $invite->event->mode === 'online' ? $invite->event->location : $invite->event->physical_address }}</span>
                            </div>

                            <div class="mt-auto">
                                <div class="flex items-center text-xs text-gray-500 mb-3">
                                    <i data-lucide="timer" class="w-3.5 h-3.5 mr-1" aria-hidden="true"></i>
                                    Expires {{ $expiresIn }}
                                </div>

                                <div class="text-sm text-gray-700 mb-4 line-clamp-2">
                                    <span class="font-medium">Topic:</span>
                                    {{ Str::limit($invite->suggested_topic, 100) }}
                                </div>
                            </div>
                        </div>

                        <div class="px-6 pb-6 pt-2 border-t border-gray-100">
                            <a href="{{ URL::signedRoute('invitations.show',$invite) }}"
                                class="w-full inline-flex items-center justify-center px-4 py-2.5 rounded-xl bg-indigo-600 hover:bg-indigo-700 text-white font-medium text-sm shadow transition-colors">
                                <i data-lucide="eye" class="w-4 h-4 mr-2" aria-hidden="true"></i>
                                View Details
                            </a>
                        </div>
                    </div>
                @endforeach
            </div>
        @endif

    </div>
</x-app-layout>
