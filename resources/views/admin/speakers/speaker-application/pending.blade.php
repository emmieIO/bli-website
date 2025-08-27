<x-app-layout>
    <div class="px-4 mx-auto">
        <div class="px-4 mx-auto">
            <!-- Header Section -->
            <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 mb-6">
                <div class="flex items-center gap-3">
                    <a href="{{ url()->previous() }}"
                        class="inline-flex items-center justify-center overflow-hidden w-10 h-10 bg-white border rounded-full border-gray-300 text-gray-700 text-sm font-medium hover:bg-gray-50 transition shadow-sm">
                        <i data-lucide="arrow-left" class="w-6 h-6 mr-2"></i>
                    </a>
                    <div class="p-2 rounded-lg bg-yellow-50">
                        <i data-lucide="clock" class="w-6 h-6 text-yellow-600"></i>
                    </div>
                    <div>
                        <h1 class="text-2xl font-bold text-gray-900">Pending Speaker Applications</h1>
                        <p class="text-sm text-gray-500">Review and manage pending speaker applications</p>
                    </div>
                </div>
            </div>

            <!-- Status Tabs -->
            <x-speakers-applications-tabs />

            <div class="relative overflow-x-auto shadow-md sm:rounded-lg">
                <table class="w-full text-sm text-left rtl:text-right text-gray-500 dark:text-gray-400">
                    <thead class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
                        <tr class="whitespace-nowrap">
                            <th scope="col" class="px-6 py-3">Topic Title</th>
                            <th scope="col" class="px-6 py-3">Speaker ID</th>
                            <th scope="col" class="px-6 py-3">Session Format</th>
                            <th scope="col" class="px-6 py-3">Submitted At</th>
                            <th scope="col" class="px-6 py-3"><span class="sr-only">Review</span></th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($applications as $application)
                            <tr class="bg-white border-b dark:bg-gray-800 dark:border-gray-700 border-gray-200 whitespace-nowrap">
                                <td class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap dark:text-white">
                                    {{ $application->topic_title }}
                                </td>
                                <td class="px-6 py-4">
                                    {{ $application->speaker->name }}
                                </td>
                                <td class="px-6 py-4">
                                    {{ ucfirst($application->session_format->value) }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    {{ \Carbon\Carbon::parse($application->created_at)->format('Y-m-d H:i') }}
                                </td>
                                <td class="px-6 py-4 text-right">
                                    <a href="{{ route('admin.speakers.application.review', $application) }}"
                                        class="font-medium text-blue-600 dark:text-blue-500 hover:underline">Review</a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="px-6 py-4 text-center text-gray-500">
                                    No pending applications found.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

        </div>
</x-app-layout>
