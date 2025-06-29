<x-guest-layout>
<section class="bg-gray-50 py-12">
    <div class="max-w-6xl mx-auto px-6 grid grid-cols-1 lg:grid-cols-3 gap-10">

        <!-- Main Content -->
        <div class="lg:col-span-2 space-y-6">
            <!-- Description -->
            <div>
                <h2 class="text-2xl font-bold text-teal-800 mb-2 flex items-center gap-2">
                    <i data-lucide="info"></i>
                    About this Event
                </h2>
                <p class="text-gray-700 leading-relaxed">{{ $programme->description }}</p>
            </div>

            <!-- Details -->
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-6 mt-6">
                <div class="flex items-start gap-3">
                    <i data-lucide="calendar-days" class="text-teal-700 mt-1"></i>
                    <div>
                        <h3 class="text-teal-700 font-semibold">Start Date</h3>
                        <p class="text-gray-800">
                            {{ \Carbon\Carbon::parse($programme->start_date)->format('D, d M Y · g:i A') }}</p>
                    </div>
                </div>
                <div class="flex items-start gap-3">
                    <i data-lucide="calendar-check" class="text-teal-700 mt-1"></i>
                    <div>
                        <h3 class="text-teal-700 font-semibold">End Date</h3>
                        <p class="text-gray-800">
                            {{ \Carbon\Carbon::parse($programme->end_date)->format('D, d M Y · g:i A') }}</p>
                    </div>
                </div>
                <div class="flex items-start gap-3">
                    <i data-lucide="user" class="text-teal-700 mt-1"></i>
                    <div>
                        <h3 class="text-teal-700 font-semibold">Host</h3>
                        <p class="text-gray-800">{{ $programme->host }}</p>
                    </div>
                </div>
                <div class="flex items-start gap-3">
                    <i data-lucide="broadcast" class="text-teal-700 mt-1"></i>
                    <div>
                        <h3 class="text-teal-700 font-semibold">Mode</h3>
                        <p class="capitalize text-gray-800">{{ $programme->mode }}</p>
                    </div>
                </div>
            </div>

            <!-- Ministers -->
            @php
                $ministers = json_decode($programme->ministers, true);
            @endphp
            @if(!empty($ministers))
                <div>
                    <h3 class="text-2xl font-bold text-teal-800 mb-2 flex items-center gap-2">
                        <i data-lucide="users"></i>
                        Ministers
                    </h3>
                    <ul class="space-y-2">
                        @foreach($ministers as $minister)
                            <li class="text-gray-800 flex items-center gap-2">
                                <i data-lucide="user-circle" class="text-teal-700"></i>
                                <span class="font-semibold">{{ $minister['name'] }}</span> — {{ $minister['title'] }}
                            </li>
                        @endforeach
                    </ul>
                </div>
            @endif
        </div>

        <!-- Sidebar Actions -->
        <div class="bg-white border border-teal-700 rounded-xl shadow-lg p-6 space-y-6 h-fit">
            <div class="space-y-1">
                <h4 class="text-teal-700 font-bold text-lg flex items-center gap-2">
                    <i data-lucide="party-popper"></i>
                    Ready to Join?
                </h4>
                <p class="text-gray-700">Let us know you’re coming. Stay updated and receive reminders.</p>
            </div>

            <form method="POST" action="#">
                @csrf
                <button type="submit"
                    class="w-full bg-teal-700 text-white py-2 px-4 rounded-lg hover:bg-teal-800 transition-all flex items-center justify-center gap-2">
                    <i data-lucide="handshake"></i>
                    I’ll be attending
                </button>
            </form>

            <a href="{{ route('events.join', $programme->slug) }}"
                class="text-center text-teal-700 font-medium hover:underline flex items-center justify-center gap-2">
                <i data-lucide="external-link"></i>
                Go to Event Page
            </a>
        </div>

    </div>
</section>

</x-guest-layout>
