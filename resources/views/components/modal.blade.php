<div  x-data="{
            open: false,
            payload: {},
            init() {
                console.log('Listening for {{ $event }}');
                window.addEventListener('{{ $event }}', (e) => {
                    this.payload = e.detail?.payload ?? {};
                    this.open = true;
                    console.log('Modal opened with payload:', this.payload);
                    document.body.classList.add('overflow-hidden'); // prevent background scroll
                });
            }
        }" >

    <div x-show="open" x-transition x-cloak class="fixed inset-0 z-[999999] flex items-center justify-center bg-black/50"
        @click.outside="open = false; document.body.classList.remove('overflow-hidden')"
        @keydown.escape.window="open = false; document.body.classList.remove('overflow-hidden')">
        <div class="bg-white w-full max-w-md mx-4 p-6 rounded-lg shadow-xl relative" @click.stop>
            <!-- Header -->
            <div class="mb-4 space-y-2">
                <div class="flex items-center justify-between">
                    <h2 class="text-lg font-semibold text-teal-800">{{ $title }}</h2>
                    <button @click="open = false; document.body.classList.remove('overflow-hidden')"
                        class="text-gray-500 hover:text-gray-700">
                        <i data-lucide="x-circle" class="w-5 h-5"></i>
                    </button>
                </div>
                <p class="text-sm text-gray-600">{{ $description }}</p>
            </div>

            <!-- Body -->
            <div class="space-y-4">
                {{ $slot }}
            </div>
        </div>
    </div>
</div>
