@props([
    'id' => 'feedback-modal',
    'title' => 'Provide Feedback',
    'action' => '#',
    'message' => 'Please provide feedback below:',
    'confirmText' => 'Submit Feedback',
])

<div id="{{ $id }}" tabindex="-1" aria-hidden="true"
    class="hidden fixed inset-0 z-50 items-center justify-center p-4 bg-black/40 backdrop-blur-sm transition-opacity duration-300">
    <div class="relative w-full max-w-md mx-auto my-8 animate-fade-in-up">
        <div class="relative bg-white rounded-3xl shadow-2xl border border-gray-100 overflow-hidden">

            <!-- Modal Header -->
            <div class="flex items-center justify-between p-6 bg-gray-50 border-b border-gray-100">
                <h3 id="{{ $id }}-title" class="text-xl font-bold text-gray-800 tracking-tight">
                    {{ $title }}
                </h3>
                <button type="button"
                    class="text-gray-400 hover:text-gray-600 hover:bg-gray-200 transition-all duration-200 rounded-full p-2 focus:outline-none focus:ring-2 focus:ring-gray-300"
                    data-modal-hide="{{ $id }}">
                    <i data-lucide="x" class="w-5 h-5"></i>
                    <span class="sr-only">Close modal</span>
                </button>
            </div>

            <!-- Modal Body -->
            <div class="p-8">
                <form method="POST" action="{{ $action }}" id="{{ $id }}-form" class="space-y-6">
                    @csrf
                    @method('POST')

                    <p id="{{ $id }}-message" class="text-base text-gray-600 leading-relaxed">
                        {{ $message }}
                    </p>

                    <div>
                        <textarea name="feedback" id="{{ $id }}-feedback" required rows="5"
                            class="w-full border border-gray-300 rounded-xl p-4 text-sm text-gray-700 placeholder-gray-400 focus:ring-2 focus:ring-blue-600 focus:border-blue-600 focus:outline-none transition-all duration-200 resize-none"
                            placeholder="Share your thoughts...">{{ old('feedback') }}</textarea>
                        <x-input-error :messages="$errors->get('feedback')" class="mt-2" />
                    </div>

                    <!-- Modal Footer -->
                    <div class="flex flex-col sm:flex-row gap-3 pt-2">
                        <button type="button" data-modal-hide="{{ $id }}"
                            class="flex-1 sm:flex-none py-3 px-6 text-sm font-semibold text-white bg-gradient-to-r from-red-600 to-red-700 rounded-xl hover:from-red-700 hover:to-red-800 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500 shadow-md hover:shadow-lg transition-all duration-200">
                            Cancel
                        </button>
                        <button id="{{ $id }}-confirm" type="submit"
                            class="flex-1 sm:flex-none py-3 px-6 text-sm font-semibold text-white bg-gradient-to-r from-blue-800 to-blue-900 rounded-xl hover:from-blue-900 hover:to-blue-950 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-600 shadow-md hover:shadow-lg transition-all duration-200">
                            {{ $confirmText }}
                        </button>
                    </div>
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