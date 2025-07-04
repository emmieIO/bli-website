@props([
    "title" => "Are you sure?",
    "description" => "This Action cannot be undone.",
    'event' => "open-modal"
])

<div
    x-data="{
        open: false,
        payload: {},
        init() {
            console.log('Listening for {{ $event }}');
            window.addEventListener('{{ $event }}', (e) => {
                this.payload = e.detail?.payload ?? {};
                this.open = true;
                console.log('Modal opened with payload:', this.payload);
            });
        }
    }"
    x-show="open"
    x-transition
    x-cloak
    class="fixed inset-0 bg-black/50 flex items-center justify-center z-[10000000]"
>
    <div class="bg-white p-4 rounded-lg shadow-xl max-w-sm w-full mx-4">
        <div class="flex items-center justify-between mb-6">
            <div>
                <h2 class="text-lg font-semibold">{{ $title }}</h2>
                <p class="text-sm text-gray-600">{{ $description }}</p>
            </div>
            <button @click="open = false" class="text-gray-500 hover:text-gray-700">
                <i data-lucide="circle-x" class=""></i>
            </button>
        </div>

        {{-- ✅ Wrap slot in Alpine scope --}}
        <div class="mb-6" x-data>
            {{ $slot }}
        </div>
    </div>
</div>
