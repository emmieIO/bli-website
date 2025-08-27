@props([
    'id' => 'modal-id',
    'bodyTitle' => '',
    'message'=> '',
    'title' => null,
    'icon' => null,
    'footer' => null,
])

<div id="{{ $id ?? 'modal-id' }}" tabindex="-1" aria-hidden="true"
    class="hidden overflow-y-auto overflow-x-hidden fixed top-0 right-0 left-0 z-50 justify-center items-center w-full md:inset-0 h-[calc(100%-1rem)] max-h-full">
    <div class="relative p-4 w-full max-w-md max-h-full">
        <div class="relative bg-white rounded-lg shadow dark:bg-gray-700">

            <!-- Modal header -->
            <div class="flex items-center justify-between p-4 md:p-5 border-b rounded-t dark:border-gray-600">
                <h3 class="text-lg font-semibold text-gray-900 dark:text-white" id="{{ $id }}-title">
                    {{ $title ?? 'Confirm Action' }}
                </h3>
                <button type="button"
                    class="text-gray-400 bg-transparent hover:bg-gray-200 hover:text-gray-900 rounded-lg text-sm w-8 h-8 ms-auto inline-flex justify-center items-center dark:hover:bg-gray-600 dark:hover:text-white"
                    data-modal-hide="{{ $id ?? 'modal-id' }}">
                    <i data-lucide="x" class="w-4 h-4"></i>
                    <span class="sr-only">Close modal</span>
                </button>
            </div>

            <!-- Modal body -->
            <div class="p-4 md:p-5 space-y-4">
                <div class="flex flex-col items-center text-center">
                    <div id="{{ $id }}-icon" class="mx-auto flex-shrink-0 flex items-center justify-center h-12 w-12 rounded-full bg-gray-100 mb-4 hidden">
                    </div>
                    <p id="{{ $id }}-message" class="text-sm text-gray-500">
                        {{ $message ?? '' }}
                    </p>
                </div>
            </div>

            <!-- Modal footer -->
            <div class="flex items-center justify-end p-4 md:p-5 border-t border-gray-200 rounded-b dark:border-gray-600 gap-3">
                <button data-modal-hide="{{ $id ?? 'modal-id' }}" type="button"
                    class="cancel-btn py-2.5 px-5 text-sm font-medium text-gray-900 focus:outline-none bg-white rounded-lg border border-gray-200 hover:bg-gray-100 focus:z-10 focus:ring-4 focus:ring-gray-100 dark:focus:ring-gray-700 dark:bg-gray-800 dark:text-gray-400 dark:border-gray-600 dark:hover:text-white dark:hover:bg-gray-700">
                    Cancel
                </button>
                <form method="POST" id="{{ $id }}-form" class="inline">
                    @csrf
                    @method('POST')
                    <button type="submit"
                        class="confirm-btn text-white bg-teal-600 hover:bg-teal-700 focus:ring-4 focus:outline-none focus:ring-teal-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center inline-flex items-center dark:bg-teal-500 dark:hover:bg-teal-600 dark:focus:ring-teal-900">
                        Confirm
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>
