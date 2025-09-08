<x-app-layout>
    <div class="py-8 px-4 sm:px-6 lg:px-8 max-w-7xl mx-auto">
        <!-- Header with Back Button and Actions -->
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 mb-8">
            <div class="flex items-center gap-4">
                <a href="{{ route('admin.speakers.index') }}"
                    class="inline-flex items-center justify-center w-10 h-10 bg-white border border-gray-300 rounded-full text-gray-700 hover:bg-gray-50 hover:text-[#00275E] focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-[#00275E] transition shadow-sm"
                    title="Back to Speakers">
                    <i data-lucide="arrow-left" class="w-5 h-5"></i>
                </a>
                <div>
                    <h1 class="text-2xl md:text-3xl font-extrabold text-[#00275E]">{{ $speaker->name }}</h1>
                    <p class="text-sm text-gray-500">Speaker Profile Details</p>
                </div>
            </div>

            <div class="flex items-center gap-3">
                <a href="{{ route('admin.speakers.edit', $speaker) }}"
                    class="inline-flex items-center px-4 py-2.5 bg-white border border-gray-300 rounded-lg shadow-sm text-sm font-medium text-[#00275E] hover:bg-gray-50 hover:text-[#FF0000] focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-[#00275E] transition">
                    <i data-lucide="edit" class="w-4 h-4 mr-2"></i>
                    Edit Profile
                </a>

                <form action="{{ route('admin.speakers.destroy', $speaker) }}" method="POST" class="inline">
                    @csrf
                    @method('DELETE')
                    <button type="submit"
                        onclick="return confirm('Are you sure you want to permanently delete {{ $speaker->name }}? This action cannot be undone.');"
                        class="inline-flex items-center px-4 py-2.5 bg-red-600 text-white text-sm font-medium rounded-lg hover:bg-red-700 focus:ring-4 focus:ring-red-300 focus:outline-none transition shadow-sm">
                        <i data-lucide="trash-2" class="w-4 h-4 mr-2"></i>
                        Delete Speaker
                    </button>
                </form>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <!-- Left Column: Speaker Info -->
            <div class="lg:col-span-2 space-y-6">
                <!-- Speaker Bio & Profile Card -->
                <div class="bg-white border border-gray-100 rounded-xl shadow-sm overflow-hidden">
                    <div class="p-6 flex flex-col sm:flex-row items-start sm:items-center gap-6">
                        <div class="flex-shrink-0">
                            <img src="{{ $speaker->photo ? asset('storage/' . $speaker->photo) : 'https://ui-avatars.com/api/?name=' . urlencode($speaker->name) . '&background=00275E&color=fff' }}"
                                class="w-24 h-24 rounded-full object-cover border-2 border-gray-200 shadow-sm" alt="{{ $speaker->name }}">
                        </div>
                        <div class="flex-1 min-w-0">
                            <h2 class="text-xl font-bold text-gray-900">{{ $speaker->name }}</h2>
                            @if($speaker->title || $speaker->organization)
                                <p class="text-sm text-gray-600 mt-1">
                                    {{ $speaker->title ?? '—' }} @ {{ $speaker->organization ?? '—' }}
                                </p>
                            @endif
                            @if($speaker->email)
                                <p class="mt-2 flex items-center gap-1.5 text-sm text-[#00275E] font-medium">
                                    <i data-lucide="mail" class="w-4 h-4"></i>
                                    {{ $speaker->email }}
                                </p>
                            @endif
                            @if($speaker->phone)
                                <p class="mt-1 flex items-center gap-1.5 text-sm text-gray-600">
                                    <i data-lucide="phone" class="w-4 h-4"></i>
                                    {{ $speaker->phone }}
                                </p>
                            @endif
                        </div>
                    </div>

                    <div class="px-6 pb-6 border-t border-gray-100">
                        <h3 class="text-sm font-semibold text-gray-700 mb-3 flex items-center">
                            <i data-lucide="file-text" class="w-4 h-4 mr-2 text-[#00275E]"></i>
                            Speaker Bio
                        </h3>
                        <div class="prose prose-sm max-w-none text-gray-700 leading-relaxed">
                            {!! nl2br(e($speaker->bio ?? '<span class="text-gray-400 italic">No bio available.</span>')) !!}
                        </div>
                    </div>
                </div>

                <!-- Contact & Social Links -->
                <div class="bg-white border border-gray-100 rounded-xl shadow-sm p-6">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4 flex items-center">
                        <i data-lucide="link" class="w-5 h-5 mr-2 text-[#00275E]"></i>
                        Contact & Social Profiles
                    </h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        @if($speaker->phone)
                            <div class="flex items-center gap-3 p-3 bg-gray-50 rounded-lg hover:bg-gray-100 transition">
                                <div class="p-2 bg-[#00275E]/10 rounded-lg">
                                    <i data-lucide="phone" class="w-4 h-4 text-[#00275E]"></i>
                                </div>
                                <div>
                                    <div class="text-xs text-gray-500">Phone</div>
                                    <div class="font-medium text-gray-900">{{ $speaker->phone }}</div>
                                </div>
                            </div>
                        @endif

                        @if($speaker->linkedin)
                            <div class="flex items-center gap-3 p-3 bg-gray-50 rounded-lg hover:bg-gray-100 transition">
                                <div class="p-2 bg-blue-50 rounded-lg">
                                    <i data-lucide="linkedin" class="w-4 h-4 text-blue-600"></i>
                                </div>
                                <div>
                                    <div class="text-xs text-gray-500">LinkedIn</div>
                                    <a href="{{ $speaker->linkedin }}" target="_blank"
                                        class="font-medium text-blue-600 hover:underline hover:text-blue-800">{{ Str::limit($speaker->linkedin, 30) }}</a>
                                </div>
                            </div>
                        @endif

                        @if($speaker->website)
                            <div class="flex items-center gap-3 p-3 bg-gray-50 rounded-lg hover:bg-gray-100 transition">
                                <div class="p-2 bg-gray-100 rounded-lg">
                                    <i data-lucide="globe" class="w-4 h-4 text-gray-600"></i>
                                </div>
                                <div>
                                    <div class="text-xs text-gray-500">Website</div>
                                    <a href="{{ $speaker->website }}" target="_blank"
                                        class="font-medium text-gray-900 hover:underline hover:text-[#00275E]">{{ Str::limit($speaker->website, 30) }}</a>
                                </div>
                            </div>
                        @endif

                        @if(!$speaker->phone && !$speaker->linkedin && !$speaker->website)
                            <div class="col-span-full text-center py-6 text-gray-400">
                                <i data-lucide="link-off" class="w-8 h-8 mx-auto mb-2"></i>
                                <p>No contact or social links added yet.</p>
                            </div>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Right Column: Future Features / Stats -->
            <div class="space-y-6">
                <!-- Status & Metadata -->
                <div class="bg-white border border-gray-100 rounded-xl shadow-sm p-6">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">Profile Status</h3>
                    <div class="space-y-4">
                        <div class="flex justify-between items-center">
                            <span class="text-sm text-gray-600">Status</span>
                            @if($speaker->status == 'active')
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                    Active
                                </span>
                            @else
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-800">
                                    Inactive
                                </span>
                            @endif
                        </div>
                        <div class="flex justify-between items-center">
                            <span class="text-sm text-gray-600">Created</span>
                            <span class="text-sm font-medium text-gray-900">{{ $speaker->created_at->format('M d, Y') }}</span>
                        </div>
                        <div class="flex justify-between items-center">
                            <span class="text-sm text-gray-600">Last Updated</span>
                            <span class="text-sm font-medium text-gray-900">{{ $speaker->updated_at->format('M d, Y') }}</span>
                        </div>
                        @if($speaker->is_featured)
                            <div class="flex justify-between items-center">
                                <span class="text-sm text-gray-600">Featured</span>
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-amber-100 text-amber-800">
                                    <i data-lucide="star" class="w-3 h-3 mr-1"></i>
                                    Yes
                                </span>
                            </div>
                        @endif
                    </div>
                </div>

                <!-- Future Features Placeholder -->
                <div class="bg-white border border-gray-100 rounded-xl shadow-sm p-6">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4 flex items-center">
                        <i data-lucide="rocket" class="w-5 h-5 mr-2 text-[#00275E]"></i>
                        Upcoming Features
                    </h3>
                    <p class="text-sm text-gray-600 mb-4">This space can be used for:</p>
                    <ul class="text-sm text-gray-600 space-y-2 list-disc list-inside">
                        <li>Session history & recordings</li>
                        <li>Speaker documents & contracts</li>
                        <li>Event participation timeline</li>
                        <li>Attendee feedback & ratings</li>
                    </ul>
                </div>

                <!-- Quick Actions -->
                <div class="bg-white border border-gray-100 rounded-xl shadow-sm p-6">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">Quick Actions</h3>
                    <div class="space-y-3">
                        <a href="{{ route('admin.speakers.edit', $speaker) }}"
                            class="flex items-center gap-3 w-full p-3 text-sm text-[#00275E] bg-gray-50 rounded-lg hover:bg-gray-100 hover:text-[#FF0000] transition">
                            <i data-lucide="edit" class="w-4 h-4"></i>
                            Edit Speaker Profile
                        </a>
                        <a href="{{ route('admin.speakers.index') }}"
                            class="flex items-center gap-3 w-full p-3 text-sm text-gray-700 bg-gray-50 rounded-lg hover:bg-gray-100 transition">
                            <i data-lucide="list" class="w-4 h-4"></i>
                            View All Speakers
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>