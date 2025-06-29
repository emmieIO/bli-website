{{-- resources/views/profile/partials/notifications-settings.blade.php --}}
<div class="bg-white border border-teal-100 rounded-xl shadow-sm p-6">
    <h3 class="text-lg font-semibold text-gray-800 mb-4 flex items-center gap-2">
        <i data-lucide="bell" class="w-5 h-5 text-teal-600"></i>
        Notification Preferences
    </h3>

    <form action="#" method="POST" class="space-y-4">
        @csrf
        <div class="flex items-center justify-between">
            <label class="text-sm text-gray-700">Email Notifications</label>
            <input type="checkbox" class="form-checkbox text-teal-600 rounded">
        </div>
        <div class="flex items-center justify-between">
            <label class="text-sm text-gray-700">SMS Notifications</label>
            <input type="checkbox" class="form-checkbox text-teal-600 rounded">
        </div>
        <div class="pt-4">
            <button type="submit"
                class="inline-flex items-center gap-2 bg-teal-700 hover:bg-teal-800 text-white font-medium px-6 py-2 rounded-md transition">
                <i data-lucide="bell-ring" class="w-4 h-4"></i>
                Save Preferences
            </button>
        </div>
    </form>
</div>
