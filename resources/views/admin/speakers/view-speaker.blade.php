<x-app-layout>
    <div class="py-6">
        <div class="sm:px-6">
            <!-- Header with back button and actions -->
            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 mb-6">
                <div class="flex items-center gap-4">
                    <a href="{{ route('admin.speakers.index') }}"
                        class="text-gray-400 hover:text-teal-600 transition-colors">
                        <i data-lucide="arrow-left" class="w-5 h-5"></i>
                    </a>
                    <h1 class="text-2xl font-bold text-gray-900">{{ $speaker->name }}</h1>
                </div>

                <div class="flex items-center gap-3">
                    <a href="{{ route('admin.speakers.edit', $speaker) }}"
                        class="inline-flex items-center px-4 py-2 bg-white border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 hover:bg-gray-50">
                        <i data-lucide="edit" class="w-4 h-4 mr-2"></i>
                        Edit Profile
                    </a>

                    <form action="{{ route('admin.speakers.destroy', $speaker) }}" method="POST">
                        @csrf
                        @method('DELETE')
                        <button type="submit"
                            class="inline-flex items-center px-4 py-2 bg-red-600 text-white text-sm font-medium rounded-md hover:bg-red-700"
                            onclick="return confirm('Are you sure you want to delete this speaker?')">
                            <i data-lucide="trash-2" class="w-4 h-4 mr-2"></i>
                            Delete
                        </button>
                    </form>
                </div>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                <!-- Left Column -->
                <div class="lg:col-span-2 space-y-6">
                    <!-- Speaker Bio Card -->
                    <div class="bg-white shadow rounded-lg overflow-hidden">
                        <div class="px-6 py-6 flex items-start gap-6">
                            <img src="{{ $speaker->photo ? asset('storage/' . $speaker->photo) : 'https://ui-avatars.com/api/?name=' . urlencode($speaker->name) }}"
                                class="w-24 h-24 rounded-full object-cover" alt="{{ $speaker->name }}">
                            <div>
                                <h2 class="text-xl font-bold text-gray-900">{{ $speaker->name }}</h2>
                                <p class="text-sm text-gray-500">{{ $speaker->title }} @ {{ $speaker->organization }}
                                </p>
                                @if($speaker->email)
                                    <p class="mt-2 text-sm text-teal-600">{{ $speaker->email }}</p>
                                @endif
                            </div>
                        </div>
                        <div class="px-6 pb-6 text-gray-700">
                            <h3 class="text-sm font-medium text-gray-500 mb-1">Bio</h3>
                            <p class="text-sm leading-relaxed">{{ $speaker->bio ?? 'No bio available.' }}</p>
                        </div>
                    </div>

                    <!-- Contact and Socials -->
                    <div class="bg-white shadow rounded-lg p-6 space-y-4">
                        <h3 class="text-lg font-medium text-gray-900">Contact & Socials</h3>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            @if($speaker->phone)
                                <div class="flex items-center gap-2 text-sm text-gray-600">
                                    <i data-lucide="phone" class="w-4 h-4 text-gray-400"></i> {{ $speaker->phone }}
                                </div>
                            @endif
                            @if($speaker->linkedin)
                                <div class="flex items-center gap-2 text-sm text-gray-600">
                                    <i data-lucide="linkedin" class="w-4 h-4 text-blue-600"></i>
                                    <a href="{{ $speaker->linkedin }}" target="_blank" class="hover:underline">LinkedIn</a>
                                </div>
                            @endif
                            @if($speaker->website)
                                <div class="flex items-center gap-2 text-sm text-gray-600">
                                    <i data-lucide="globe" class="w-4 h-4 text-gray-400"></i>
                                    <a href="{{ $speaker->website }}" target="_blank" class="hover:underline">Website</a>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>

                <!-- Right Column - Reserved for Future Use -->
                <div class="space-y-6">
                    <!-- Placeholder Card -->
                    <div class="bg-white shadow rounded-lg p-6">
                        <h3 class="text-lg font-medium text-gray-900 mb-2">Upcoming Features</h3>
                        <p class="text-sm text-gray-600">This space can be used to show session history, speaker
                            documents, or upcoming events.</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
