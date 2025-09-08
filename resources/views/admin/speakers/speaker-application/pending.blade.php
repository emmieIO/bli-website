<x-app-layout>
    <div class="px-4 mx-auto max-w-7xl py-8">
        <!-- Header Section -->
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 mb-8">
            <div class="flex items-center gap-3">
                <a href="{{ url()->previous() }}"
                    class="inline-flex items-center justify-center overflow-hidden w-10 h-10 bg-white border rounded-full border-gray-300 text-gray-700 text-sm font-medium hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-[#00275E] transition shadow-sm"
                    title="Back">
                    <i data-lucide="arrow-left" class="w-5 h-5"></i>
                </a>
                <div class="p-2.5 rounded-lg bg-[#00275E]/10">
                    <i data-lucide="clock" class="w-6 h-6 text-[#00275E]"></i>
                </div>
                <div>
                    <h1 class="text-2xl font-extrabold text-[#00275E]">Pending Speaker Applications</h1>
                    <p class="text-sm text-gray-500">Review and manage pending speaker applications</p>
                </div>
            </div>
        </div>

        <!-- Status Tabs -->
        <x-speakers-applications-tabs class="mb-8" />

        <!-- Applications Table -->
        <div class="bg-white border border-gray-100 rounded-xl shadow-sm overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-200 bg-gray-50">
                <h2 class="text-lg font-medium text-gray-900">Applications Awaiting Review</h2>
                <p class="text-sm text-gray-500 mt-1">Click “Review” to evaluate and approve or reject each application.</p>
            </div>

            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Topic Title</th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Speaker</th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Session Format</th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Submitted At</th>
                            <th scope="col" class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Action</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @forelse($applications as $application)
                            <tr class="hover:bg-gray-50 transition-colors">
                                <td class="px-6 py-4">
                                    <div class="font-medium text-gray-900">{{ $application->topic_title }}</div>
                                    @if($application->topic_description)
                                        <div class="text-xs text-gray-500 mt-1 line-clamp-1">{{ Str::limit($application->topic_description, 60) }}</div>
                                    @endif
                                </td>
                                <td class="px-6 py-4">
                                    <div class="flex items-center gap-2">
                                        <div class="w-8 h-8 rounded-full bg-gray-200 flex items-center justify-center text-xs font-medium text-gray-700">
                                            {{ strtoupper(substr($application->speaker->name, 0, 1)) }}
                                        </div>
                                        <span class="text-sm text-gray-900">{{ $application->speaker->name }}</span>
                                    </div>
                                </td>
                                <td class="px-6 py-4">
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                        {{ ucfirst(str_replace('_', ' ', $application->session_format->value)) }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 text-sm text-gray-500 whitespace-nowrap">
                                    {{ $application->created_at->format('M d, Y \a\t g:i A') }}
                                </td>
                                <td class="px-6 py-4 text-right">
                                    <a href="{{ route('admin.speakers.application.review', $application) }}"
                                        class="inline-flex items-center px-3 py-1.5 bg-[#00275E] text-white text-xs font-medium rounded-lg hover:bg-[#FF0000] focus:ring-2 focus:ring-offset-2 focus:ring-[#00275E] transition shadow-sm">
                                        <i data-lucide="clipboard-check" class="w-4 h-4 mr-1.5"></i>
                                        Review
                                    </a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="px-6 py-16 text-center">
                                    <div class="flex flex-col items-center space-y-4 text-gray-400">
                                        <i data-lucide="inbox" class="w-12 h-12"></i>
                                        <h3 class="text-lg font-medium text-gray-900">No pending applications</h3>
                                        <p class="max-w-md text-center text-gray-500">All speaker applications have been reviewed. Check back later or invite new speakers.</p>
                                        <a href="{{ route('admin.speakers.create') }}"
                                            class="mt-4 inline-flex items-center px-4 py-2 bg-[#00275E] text-white text-sm font-medium rounded-lg hover:bg-[#FF0000] focus:ring-4 focus:ring-blue-300 transition shadow-sm">
                                            <i data-lucide="plus" class="w-4 h-4 mr-2"></i>
                                            Invite New Speaker
                                        </a>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</x-app-layout>