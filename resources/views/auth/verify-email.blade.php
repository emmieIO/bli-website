<x-auth-layout title="Verify Your Email"
    description="We've sent you a verification link. Check your inbox to activate your account.">
    <x-slot name="pageTitle">Email Verification - Beacon Leadership Institute</x-slot>

    <div x-data="{ 
        processing: false, 
        resendCooldown: 0,
        userEmail: '{{ auth()->user()->email ?? '' }}',
        
        startCooldown() {
            this.resendCooldown = 60;
            const timer = setInterval(() => {
                this.resendCooldown--;
                if (this.resendCooldown <= 0) {
                    clearInterval(timer);
                }
            }, 1000);
        }
    }">

        @if (session('status') == 'verification-link-sent')
            <div class="bg-green-50 border border-green-200 text-green-800 text-sm rounded-xl p-4 mb-6 flex items-start gap-3"
                data-aos="fade-down" data-aos-duration="600" x-init="startCooldown()">
                <i data-lucide="check-circle" class="w-5 h-5 mt-0.5 text-green-600"></i>
                <div>
                    <p class="font-semibold font-montserrat">Email Sent Successfully!</p>
                    <p class="font-lato">A new verification link has been sent to your email address.</p>
                </div>
            </div>
        @endif

        <!-- Email Status -->
        <div class="mb-6 p-6 bg-yellow-50 rounded-xl border border-yellow-200">
            <div class="flex items-center gap-3 mb-4">
                <div
                    class="w-10 h-10 rounded-full bg-yellow-100 border-2 border-yellow-300 flex items-center justify-center">
                    <i data-lucide="mail" class="w-5 h-5 text-yellow-600"></i>
                </div>
                <div>
                    <h3 class="font-semibold text-yellow-800 font-montserrat">Verification Required</h3>
                    <p class="text-sm text-yellow-700 font-lato">Check your email: <strong x-text="userEmail"></strong>
                    </p>
                </div>
            </div>

            <!-- Steps -->
            <div class="grid gap-2 text-sm font-lato">
                <div class="flex items-center gap-2 text-yellow-700">
                    <span
                        class="w-5 h-5 rounded-full bg-yellow-200 flex items-center justify-center text-xs font-semibold text-yellow-800">1</span>
                    <span>Open your email application</span>
                </div>
                <div class="flex items-center gap-2 text-yellow-700">
                    <span
                        class="w-5 h-5 rounded-full bg-yellow-200 flex items-center justify-center text-xs font-semibold text-yellow-800">2</span>
                    <span>Look for our verification email</span>
                </div>
                <div class="flex items-center gap-2 text-yellow-700">
                    <span
                        class="w-5 h-5 rounded-full bg-yellow-200 flex items-center justify-center text-xs font-semibold text-yellow-800">3</span>
                    <span>Click the "Verify Email Address" button</span>
                </div>
            </div>
        </div>

        <!-- Troubleshooting -->
        <div class="mb-6 p-4 bg-blue-50 rounded-xl border border-blue-200">
            <h4 class="text-sm font-semibold text-blue-800 mb-3 font-montserrat flex items-center gap-2">
                <i data-lucide="help-circle" class="w-4 h-4"></i>
                Can't find the email?
            </h4>
            <ul class="text-xs text-blue-700 space-y-1 font-lato">
                <li>• Check your spam or junk mail folder</li>
                <li>• Make sure you entered the correct email address</li>
                <li>• Wait a few minutes - emails can be delayed</li>
                <li>• Add our email to your safe senders list</li>
            </ul>
        </div>

        <!-- Resend Form -->
        <form method="POST" action="{{ route('verification.send') }}" x-on:submit="processing = true; startCooldown()">
            @csrf
            <button type="submit" x-bind:disabled="processing || resendCooldown > 0"
                class="w-full group relative overflow-hidden bg-gradient-to-r from-primary to-primary-600 hover:from-primary-600 hover:to-primary-700 text-white font-semibold py-3.5 px-6 rounded-xl transition-all duration-300 transform hover:scale-[1.01] focus:outline-none focus:ring-2 focus:ring-primary/20 focus:ring-offset-2 disabled:opacity-50 disabled:cursor-not-allowed disabled:hover:scale-100 font-montserrat shadow-lg hover:shadow-xl">
                <!-- Loading State -->
                <div x-show="processing" class="flex items-center justify-center">
                    <i data-lucide="loader-2" class="w-5 h-5 animate-spin mr-2"></i>
                    <span>Sending Email...</span>
                </div>
                <!-- Cooldown State -->
                <div x-show="!processing && resendCooldown > 0" class="flex items-center justify-center">
                    <i data-lucide="clock" class="w-5 h-5 mr-2"></i>
                    <span>Resend in <span x-text="resendCooldown"></span>s</span>
                </div>
                <!-- Normal State -->
                <div x-show="!processing && resendCooldown <= 0" class="flex items-center justify-center">
                    <i data-lucide="refresh-ccw"
                        class="w-5 h-5 mr-2 group-hover:rotate-180 transition-transform duration-300"></i>
                    <span>Resend Verification Email</span>
                </div>
            </button>
        </form>

        <!-- Alternative Actions -->
        <div class="mt-8 space-y-4">
            <!-- Update Email Address -->
            <div class="text-center">
                <p class="text-sm text-gray-600 font-lato mb-2">Wrong email address?</p>
                <a href="{{ route('profile.edit') }}"
                    class="inline-flex items-center gap-2 text-primary hover:text-primary-600 transition-colors font-lato text-sm">
                    <i data-lucide="edit" class="w-4 h-4"></i>
                    Update Email Address
                </a>
            </div>

            <div class="relative">
                <div class="absolute inset-0 flex items-center">
                    <div class="w-full border-t border-gray-200"></div>
                </div>
                <div class="relative flex justify-center text-sm">
                    <span class="px-2 bg-white text-gray-500 font-lato">or</span>
                </div>
            </div>

            <!-- Logout -->
            <form method="POST" action="{{ route('logout') }}" class="text-center">
                @csrf
                <button type="submit"
                    class="inline-flex items-center gap-2 text-gray-500 hover:text-gray-700 transition-colors font-lato text-sm">
                    <i data-lucide="log-out" class="w-4 h-4"></i>
                    Sign out and try a different account
                </button>
            </form>
        </div>

        <!-- Contact Support -->
        <div class="mt-8 text-center p-4 bg-gray-50 rounded-xl border border-gray-200">
            <p class="text-sm text-gray-600 font-lato mb-2">Still having issues?</p>
            <a href="mailto:support@beaconleadershipinstitute.org"
                class="inline-flex items-center gap-2 text-primary font-semibold hover:text-primary-600 transition-colors">
                <i data-lucide="mail" class="w-4 h-4"></i>
                Contact Support
            </a>
        </div>
    </div>

    @push('scripts')
        <script>
            // Reinitialize icons after Alpine updates
            document.addEventListener('alpine:updated', () => {
                lucide.createIcons();
            });
        </script>
    @endpush
</x-auth-layout>