<x-auth-layout title="Reset Password" description="Set your new password and regain access to your account.">

    <form method="POST" action="" class="space-y-6">
        @csrf

        <!-- Hidden Token -->
        <input type="hidden" name="token" value="{{ request()->route('token') }}">

        <!-- Email -->
        <div>
            <label for="email" class="block text-sm font-medium text-gray-700">Email address</label>
            <div class="mt-1 relative">
                <span class="absolute left-3 top-2.5 text-gray-400">
                    <i data-lucide="mail" class="w-4 h-4"></i>
                </span>
                <input id="email" name="email" type="email" value="{{ old('email', request('email')) }}" required
                    class="pl-10 pr-4 py-2 block w-full border border-gray-300 rounded-md shadow-sm focus:ring-teal-500 focus:border-teal-500">
            </div>
        </div>

        <!-- New Password -->
        <div>
            <label for="password" class="block text-sm font-medium text-gray-700">New Password</label>
            <div class="mt-1 relative">
                <span class="absolute left-3 top-2.5 text-gray-400">
                    <i data-lucide="lock" class="w-4 h-4"></i>
                </span>
                <input id="password" name="password" type="password" required
                    class="pl-10 pr-4 py-2 block w-full border border-gray-300 rounded-md shadow-sm focus:ring-teal-500 focus:border-teal-500">
            </div>
        </div>

        <!-- Confirm Password -->
        <div>
            <label for="password_confirmation" class="block text-sm font-medium text-gray-700">Confirm Password</label>
            <div class="mt-1 relative">
                <span class="absolute left-3 top-2.5 text-gray-400">
                    <i data-lucide="lock-keyhole" class="w-4 h-4"></i>
                </span>
                <input id="password_confirmation" name="password_confirmation" type="password" required
                    class="pl-10 pr-4 py-2 block w-full border border-gray-300 rounded-md shadow-sm focus:ring-teal-500 focus:border-teal-500">
            </div>
        </div>

        <!-- Info Graphic -->
        <div class="bg-teal-50 border border-teal-200 rounded-md p-3 text-sm text-teal-800 flex items-start gap-2">
            <i data-lucide="info" class="w-5 h-5 mt-0.5"></i>
            <span>
                Use at least 8 characters. Stronger passwords include upper and lowercase letters, numbers, and symbols.
            </span>
        </div>

        <!-- Submit Button -->
        <div>
            <button type="submit"
                class="w-full flex justify-center items-center gap-2 py-2 px-4 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-teal-700 hover:bg-teal-800 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-teal-600">
                <i data-lucide="rotate-ccw" class="w-4 h-4"></i>
                Reset Password
            </button>
        </div>
    </form>

    <script>
        lucide.createIcons();
    </script>

</x-auth-layout>
