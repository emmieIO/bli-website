<x-app-layout>
    <section class="max-w-4xl mx-auto py-12" x-data="{ tab: 'profile' }">
        <!-- Profile Header -->
        <div class="relative bg-gradient-to-r from-teal-800 to-teal-600 rounded-xl shadow-md p-6 text-white mb-10">
            <div class="flex items-center gap-6">
                <div>
                    <h2 class="text-2xl font-bold">{{ __(auth()->user()->name) }}</h2>
                    <p class="text-sm opacity-90">{{ __(auth()->user()->email) }}</p>
                    <p class="text-xs text-white/70 mt-1">Member since {{\Carbon\Carbon::parse(auth()->user()->created_at)->format("M d Y") }}</p>
                </div>
            </div>
        </div>

        <!-- Tab Navigation -->
        <div class="mb-6 border-b border-gray-200">
            <nav class="-mb-px flex space-x-6">
                <button @click="tab = 'profile'"
                    :class="tab === 'profile' ? 'border-teal-600 text-teal-700' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300'"
                    class="whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm">
                    Profile
                </button>
                <button @click="tab = 'security'"
                    :class="tab === 'security' ? 'border-teal-600 text-teal-700' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300'"
                    class="whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm">
                    Security
                </button>
                <button @click="tab = 'notifications'"
                    :class="tab === 'notifications' ? 'border-teal-600 text-teal-700' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300'"
                    class="whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm">
                    Notifications
                </button>
            </nav>
        </div>

        <!-- Profile Tab -->
        <div x-show="tab === 'profile'" x-transition>
            <!-- Your profile form code here -->
            @include('user_dashboard.partials.update-profile-information-form')
        </div>

        <!-- Security Tab -->
        <div x-show="tab === 'security'" x-transition>
            <!-- Your security form code here -->
            @include('user_dashboard.partials.update-password-form')

        </div>

        <!-- Notifications Tab -->
        <div x-show="tab === 'notifications'" x-transition>
            <!-- Your notifications form code here -->
            @include('user_dashboard.partials.notifications-settings')
        </div>

        <!-- Danger Zone -->
        <div class="mt-10">
            <div class="bg-white border border-red-200 rounded-xl shadow-sm p-6">
                <h3 class="text-lg font-semibold text-red-700 mb-4 flex items-center gap-2">
                    <i data-lucide="alert-triangle" class="w-5 h-5"></i>
                    Danger Zone
                </h3>
                <p class="text-sm text-gray-600 mb-4">Permanently delete your account. This action cannot be undone.</p>
                <button
                    class="text-red-700 border border-red-500 hover:bg-red-500 hover:text-white font-semibold px-5 py-2 rounded-md transition">
                    <i data-lucide="trash" class="w-4 h-4 inline-block mr-2"></i>
                    Deactivate Account
                </button>
            </div>
        </div>
    </section>

</x-app-layout>
