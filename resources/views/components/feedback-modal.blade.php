@props([
    'id' => 'feedback-modal',
    'title' => 'Provide Feedback',
    'action' => '#',
    'message' => 'Please provide feedback below:',
    'confirmText' => 'Submit Feedback',
])

<div id="{{ $id ?? 'feedback-modal' }}" tabindex="-1" aria-hidden="true"
    class="hidden overflow-y-auto overflow-x-hidden fixed top-0 right-0 left-0 z-50 justify-center items-center w-full md:inset-0 h-[calc(100%-1rem)] max-h-full">
    <div class="relative p-4 w-full max-w-md max-h-full">
        <div class="relative bg-white rounded-lg shadow dark:bg-gray-700">

            <!-- Header -->
            <div class="flex items-center justify-between p-4 md:p-5 border-b rounded-t dark:border-gray-600">
                <h3 id="{{ $id }}-title" class="text-lg font-semibold text-gray-900 dark:text-white">
                    {{ $title ?? 'Provide Feedback' }}
                </h3>
                <button type="button"
                    class="text-gray-400 bg-transparent hover:bg-gray-200 hover:text-gray-900 rounded-lg text-sm w-8 h-8 ms-auto inline-flex justify-center items-center dark:hover:bg-gray-600 dark:hover:text-white"
                    data-modal-hide="{{ $id ?? 'feedback-modal' }}">
                    <i data-lucide="x" class="w-4 h-4"></i>
                    <span class="sr-only">Close modal</span>
                </button>
            </div>

            <!-- Body -->
            <div class="p-4 md:p-5 space-y-4">
                <form method="POST" action="{{ $action ?? '#' }}" id="{{ $id }}-form">
                    @csrf
                    @method('POST')

                    <p id="{{ $id }}-message" class="text-sm text-gray-500 mb-2">
                        {{ $message ?? 'Please provide feedback below:' }}
                    </p>
                    <div class="">
                        <textarea name="feedback" id="{{ $id }}-feedback" required rows="4"
                            class="w-full border rounded-lg p-2 text-sm text-gray-700 focus:ring focus:ring-red-500 dark:bg-gray-800 dark:text-gray-200 dark:border-gray-600"></textarea>
                            <x-input-error :messages="$errors->get('feedback')" />
                    </div>

                    <!-- Footer -->
                    <div class="flex justify-end gap-2 mt-4">
                        <button type="button" data-modal-hide="{{ $id ?? 'feedback-modal' }}"
                            class="px-4 py-2 bg-gray-200 rounded-lg hover:bg-gray-300 dark:bg-gray-700 dark:text-gray-300 dark:hover:bg-gray-600">
                            Cancel
                        </button>
                        <button id="{{ $id }}-confirm" type="submit"
                            class="px-4 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700">
                            {{ $confirmText ?? 'Submit Feedback' }}
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
