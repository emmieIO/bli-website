<x-guest-layout>
    <div class="lg:grid grid-cols-3 gap-4 w-[90%] mx-auto py-10 min-h-screen">
        @foreach ($programmes as $program)
            <div class="flex flex-col bg-white shadow-lg rounded-lg p-6 transition-transform transform hover:scale-105">
                <img src="{{ $program->image_url ?? (asset('images/think.png')) }}" alt="{{ $program->title }}"
                class="w-inherit h-40 object-cover rounded-lg mb-4 border-4 border-teal-800 shadow">
                <h2 class="text-xl font-bold text-gray-800 mb-2">{{ $program->theme }}</h2>
                <p class="text-gray-800 mb-4 text-justify">
                    {{ \Illuminate\Support\Str::limit($program->description, 50) }}
                    @if(strlen($program->description) > 50)
                        <a href="#" class="text-teal-600 hover:underline">see more</a>
                    @endif
                </p>
                <div class="flex items-center justify-between w-full mt-auto">
                    <span class="text-sm text-teal-800 font-semibold">
                        <span class="font-bold">Start Date:</span>
                        {{ \Carbon\Carbon::parse($program->start_date)->format('D, d M Y · g:i A') }}
                    </span>
                </div>
                <a href="" class="bg-teal-800 text-white px-4 py-2 rounded hover:bg-teal-800 transition flex gap-2">
                    <span>Event Details</span>
                    <i data-lucide="move-right"></i></a>
            </div>
        @endforeach
    </div>
</x-guest-layout>
