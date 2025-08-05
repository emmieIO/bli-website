<x-app-layout>
    <x-instructor-dashbord-layout>


        <div class="relative overflow-x-auto shadow-md sm:rounded-lg">
            <table class="w-full text-sm text-left rtl:text-right text-gray-500">
                <thead class="text-xs text-gray-700 uppercase bg-gray-50">
                    <tr>

                        <th scope="col" class="px-6 py-3">
                            Profile Id
                        </th>
                        <th scope="col" class="px-6 py-3">
                            Action
                        </th>

                        <th scope="col" class="px-6 py-3 whitespace-nowrap">
                            Performed By
                        </th>

                        <th scope="col" class="px-6 py-3">
                            Action
                        </th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($logs as $log)
                        <tr class="bg-white border-b border-gray-200 hover:bg-gray-50">
                            <th scope="row" class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap">
                                {{ $log->application_id }}
                            </th>

                            <td class="px-6 py-4">
                                {{ $log->action }}
                            </td>

                            <td class="px-6 py-4">
                                {{ $log->user->name }}
                            </td>
                            <td class="flex items-center px-6 py-4">
                                <a href="">
                                    <i data-lucide="eye" class="size-4 stroke-blue-800"></i>
                                </a>

                                <button data-modal-target="popup-modal" data-modal-toggle="popup-modal" onclick="confirmDelete('{{ route('admin.instructors.application-logs.delete', $log) }}')"
                                    class="font-medium text-red-600 hover:underline ms-3 flex items-center cursor-pointer">
                                    <i data-lucide="trash" class="size-4"></i>
                                </button>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>




            <div id="popup-modal" tabindex="-1"
                class="hidden overflow-y-auto overflow-x-hidden fixed top-0 right-0 left-0 z-50 justify-center items-center w-full md:inset-0 h-[calc(100%-1rem)] max-h-full">
                <div class="relative p-4 w-full max-w-md max-h-full">
                    <div class="relative bg-white rounded-lg shadow-sm">
                        <button type="button"
                            class="absolute top-3 end-2.5 text-gray-400 bg-transparent rounded-lg text-sm w-8 h-8 ms-auto inline-flex justify-center items-center hover:bg-gray-600 hover:text-white"
                            data-modal-hide="popup-modal">
                            <svg class="w-3 h-3" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none"
                                viewBox="0 0 14 14">
                                <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"
                                    stroke-width="2" d="m1 1 6 6m0 0 6 6M7 7l6-6M7 7l-6 6" />
                            </svg>
                            <span class="sr-only">Close modal</span>
                        </button>
                        <div class="p-4 md:p-5 text-center">
                            <svg class="mx-auto mb-4 text-gray-400 w-12 h-12" aria-hidden="true"
                                xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 20 20">
                                <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"
                                    stroke-width="2" d="M10 11V6m0 8h.01M19 10a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
                            </svg>
                            <h3 class="mb-5 text-lg font-normal text-gray-500" id="confirmText">Are you sure you want to delete log?</h3>
                            <div class="flex items-center justify-center">
                                <form id='delete-form' method="post">
                                    @csrf
                                    @method("DELETE")
                                    <button data-modal-hide="popup-modal" type="submit"
                                        class="text-white bg-red-600 hover:bg-red-800 focus:ring-4 focus:outline-none focus:ring-red-300 font-medium rounded-lg text-sm inline-flex items-center px-5 py-2.5 text-center">
                                        Yes, I'm sure
                                    </button>
                                </form>
                                <button data-modal-hide="popup-modal" type="button"
                                    class="py-2.5 px-5 ms-3 text-sm font-medium text-gray-900 focus:outline-none bg-white rounded-lg border border-gray-200 hover:bg-gray-100 hover:text-blue-700 focus:z-10 focus:ring-4 focus:ring-gray-100">
                                    No, cancel
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

        </div>

    </x-instructor-dashbord-layout>

    <script>
        const deleteForm = document.getElementById('delete-form');
        function confirmDelete(deleteRoute){
            if (deleteForm) {
                deleteForm.action = deleteRoute;
            }
        }


    </script>
</x-app-layout>
