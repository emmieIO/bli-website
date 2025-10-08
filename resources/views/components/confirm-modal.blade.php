@props([
    'id' => 'action-modal',
    'bodyTitle' => '',
    'message' => '',
    'title' => null,
    'icon' => null,
    'footer' => null,
])

<div id="{{ $id }}" tabindex="-1" aria-hidden="true"
    class="hidden fixed inset-0 z-50 items-center justify-center p-4 bg-black/40 backdrop-blur-sm transition-opacity duration-300">
    <div class="relative w-full max-w-md mx-auto my-8 animate-fade-in-up">
        <div class="relative bg-white rounded-3xl shadow-2xl border border-gray-100 overflow-hidden">

            <!-- Modal Header -->
            <div class="flex items-center justify-between p-6 bg-gray-50 border-b border-gray-100">
                <h3 class="text-xl font-bold text-gray-800 tracking-tight" id="{{ $id }}-title">
                    {{ $title ?? 'Confirm Action' }}
                </h3>
                <button type="button"
                    class="text-gray-400 hover:text-gray-600 hover:bg-gray-200 transition-all duration-200 rounded-full p-2 focus:outline-none focus:ring-2 focus:ring-gray-300"
                    data-modal-hide="{{ $id }}">
                    <i data-lucide="x" class="w-5 h-5"></i>
                    <span class="sr-only">Close modal</span>
                </button>
            </div>

            <!-- Modal Body -->
            <div class="p-8 space-y-6 text-center">
                @if ($icon)
                    <div id="{{ $id }}-icon"
                        class="mx-auto flex items-center justify-center h-16 w-16 rounded-2xl bg-gradient-to-br from-blue-50 to-blue-100 text-blue-600 shadow-sm">
                        {!! $icon !!}
                    </div>
                @endif

                @if ($bodyTitle)
                    <h4 class="text-lg font-semibold text-gray-800">{{ $bodyTitle }}</h4>
                @endif

                <p id="{{ $id }}-message" class="text-base leading-relaxed text-gray-600 max-w-xs mx-auto">
                    {{ $message }}
                </p>
            </div>

            <!-- Modal Footer -->
            <div class="flex flex-col sm:flex-row items-stretch sm:items-center justify-end gap-3 p-6 bg-gray-50 border-t border-gray-100">
                <button data-modal-hide="{{ $id }}" type="button"
                    class="flex-1 sm:flex-none py-3 px-6 text-sm font-semibold text-white bg-gradient-to-r from-red-600 to-red-700 rounded-xl hover:from-red-700 hover:to-red-800 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500 shadow-md hover:shadow-lg transition-all duration-200">
                    Cancel
                </button>
                <form method="POST" id="{{ $id }}-form" class="w-full sm:w-auto">
                    @csrf
                    @method('POST')
                    <button type="submit"
                        class="flex-1 sm:flex-none w-full sm:w-auto inline-flex items-center justify-center px-6 py-3 text-sm font-semibold text-white bg-gradient-to-r from-blue-800 to-blue-900 rounded-lg hover:from-blue-900 hover:to-blue-950 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-600 shadow-md hover:shadow-lg transition-all duration-200">
                        Confirm
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>

<style>
    @keyframes fadeInUp {
        from {
            opacity: 0;
            transform: translateY(20px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }
    .animate-fade-in-up {
        animation: fadeInUp 0.3s ease-out forwards;
    }
</style>