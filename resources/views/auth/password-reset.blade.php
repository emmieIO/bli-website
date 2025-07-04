<x-auth-layout title="Reset Password" description="Set your new password and regain access to your account.">

    <form method="post" action="{{ route("password.update") }}" class="space-y-6">
        @csrf

        <!-- Hidden Token -->
        <input type="hidden" name="token" value="{{ request()->route('token') }}">

        <!-- New Password -->
        <x-input label="Email" type="email" name="email" icon="mail" autofocus />
        <x-input label="New Password" type="password" name="password" icon="lock" />
        <x-input label="Confirm Password" type="password" name="password_confirmation" icon="lock" />


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