<x-auth-layout :title="'Welcome Back'" :description="'Sign in to your account'">
    <form method="POST" action="{{ route('login') }}" class="space-y-6">
        @csrf

        <!-- Email -->
        <div>
            <label for="email" class="block text-sm font-medium text-gray-700">Email address</label>
            <div class="mt-1 relative">
                <input id="email" type="email" name="email" required autofocus
                    class="block w-full pl-10 pr-4 py-2 border border-gray-300 rounded-md shadow-sm focus:ring-teal-500 focus:border-teal-500" />
                <i data-lucide="mail" class="w-4 h-4 absolute left-3 top-2.5 text-gray-400"></i>
            </div>
            @error('email')
                <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
            @enderror
        </div>

        <!-- Password -->
        <div>
            <label for="password" class="block text-sm font-medium text-gray-700">Password</label>
            <div class="mt-1 relative">
                <input id="password" type="password" name="password" required
                    class="block w-full pl-10 pr-4 py-2 border border-gray-300 rounded-md shadow-sm focus:ring-teal-500 focus:border-teal-500" />
                <i data-lucide="lock" class="w-4 h-4 absolute left-3 top-2.5 text-gray-400"></i>
            </div>
            @error('password')
                <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
            @enderror
        </div>

        <!-- Remember Me & Forgot -->
        <div class="flex items-center justify-between">
            <label class="inline-flex items-center text-sm">
                <input type="checkbox" name="remember"
                    class="rounded border-gray-300 text-teal-600 shadow-sm focus:ring-teal-500">
                <span class="ml-2 text-gray-700">Remember me</span>
            </label>

            <a href="{{ route('password.request') }}" class="text-sm text-teal-700 hover:underline">Forgot password?</a>
        </div>

        <!-- Submit -->
        <div>
            <button type="submit"
                class="w-full bg-teal-700 hover:bg-teal-600 text-white font-semibold py-2 px-4 rounded-md transition flex items-center justify-center gap-2">
                <i data-lucide="log-in" class="w-5 h-5"></i>
                Sign In
            </button>
        </div>

        <!-- Social Login -->
        <div class="mt-6 text-center">
            <p class="text-sm text-gray-500 mb-4">or sign in with</p>
            <div class="flex justify-center gap-4">
                <a href=""
                    class="flex items-center gap-2 bg-red-500 hover:bg-red-600 text-white px-4 py-2 rounded-md text-sm font-medium">
                    <i data-lucide="globe" class="w-4 h-4"></i> Google
                </a>
                <a href=""
                    class="flex items-center gap-2 bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-md text-sm font-medium">
                    <i data-lucide="facebook" class="w-4 h-4"></i> Facebook
                </a>
            </div>
        </div>

        <!-- Register -->
        <p class="text-center text-sm mt-6">
            Don’t have an account?
            <a href="{{ route('register') }}" class="text-teal-700 hover:underline font-medium">Sign up</a>
        </p>
    </form>
</x-auth-layout>
