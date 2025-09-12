<x-app-layout>
    <div class="">
        {{-- {!! $invites !!} --}}
        <div class="">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <h1 class="text-2xl font-bold mb-6 text-blue-900">Event Invitations</h1>

                    <!-- Invitation List -->
                    <div class="space-y-4">
                        <!-- Invitation Card 1 -->
                        <div class="p-4 bg-blue-100 rounded-lg shadow">
                            <div class="flex justify-between items-center">
                                <div>
                                    <h2 class="text-lg font-semibold text-blue-900">Tech Conference 2024</h2>
                                    <p class="text-sm text-blue-700">Date: March 15, 2024</p>
                                    <p class="text-sm text-blue-700">Location: New York, USA</p>
                                </div>
                                <div class="flex space-x-2">
                                    <button class="px-3 py-1 bg-red-500 text-white rounded hover:bg-red-600 transition">
                                        Decline
                                    </button>
                                    <button class="px-3 py-1 bg-blue-900 text-white rounded hover:bg-blue-800 transition">
                                        Accept
                                    </button>
                                </div>
                            </div>
                        </div>

                        <!-- Invitation Card 2 -->
                        <div class="p-4 bg-blue-100 rounded-lg shadow">
                            <div class="flex justify-between items-center">
                                <div>
                                    <h2 class="text-lg font-semibold text-blue-900">AI Summit 2024</h2>
                                    <p class="text-sm text-blue-700">Date: April 10, 2024</p>
                                    <p class="text-sm text-blue-700">Location: San Francisco, USA</p>
                                </div>
                                <div class="flex space-x-2">
                                    <button class="px-3 py-1 bg-red-500 text-white rounded hover:bg-red-600 transition">
                                        Decline
                                    </button>
                                    <button class="px-3 py-1 bg-blue-900 text-white rounded hover:bg-blue-800 transition">
                                        Accept
                                    </button>
                                </div>
                            </div>
                        </div>

                        <!-- Invitation Card 3 -->
                        <div class="p-4 bg-blue-100 rounded-lg shadow">
                            <div class="flex justify-between items-center">
                                <div>
                                    <h2 class="text-lg font-semibold text-blue-900">Blockchain Expo 2024</h2>
                                    <p class="text-sm text-blue-700">Date: May 5, 2024</p>
                                    <p class="text-sm text-blue-700">Location: London, UK</p>
                                </div>
                                <div class="flex space-x-2">
                                    <button class="px-3 py-1 bg-red-500 text-white rounded hover:bg-red-600 transition">
                                        Decline
                                    </button>
                                    <button class="px-3 py-1 bg-blue-900 text-white rounded hover:bg-blue-800 transition">
                                        Accept
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>