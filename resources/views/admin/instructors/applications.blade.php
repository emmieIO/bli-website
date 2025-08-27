<x-app-layout>
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 mb-6">
            <div>
            <h2 class="text-xl font-semibold text-gray-800">Instructor Applications</h2>
            <p class="text-sm text-gray-500 mt-1">Manage and review all instructor applications submitted to the platform.</p>
            </div>
            <div class="flex flex-col sm:flex-row gap-3 w-full sm:w-auto">
                <div class="relative w-full sm:w-64">
                    <input type="text" placeholder="Search logs..." id="searchInput"
                        class="w-full pl-10 pr-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 text-sm"
                        onkeyup="filterLogs()">
                    <i data-lucide="search" class="absolute left-3 top-2.5 text-gray-400 w-5 h-5"></i>
                </div>
            </div>
        </div>
    <x-instructor-dashbord-layout>
        <div class="relative overflow-x-auto my-5">
            <table class="w-full text-sm text-left rtl:text-right text-gray-500">
                <thead class="text-xs text-gray-700 uppercase bg-gray-50">
                    <tr>
                        <th scope="col" class="p-4">
                            <div class="flex items-center">
                                <input id="checkbox-all-search" type="checkbox"
                                    class="w-4 h-4 text-teal-600 bg-gray-100 border-gray-300 rounded-sm focus:ring-teal-500 focus:ring-2">
                                <label for="checkbox-all-search" class="sr-only">checkbox</label>
                            </div>
                        </th>
                        <th scope="col" class="px-6 py-3">
                            Fullname
                        </th>
                        <th scope="col" class="px-6 py-3">
                            Profile ID
                        </th>
                        <th scope="col" class="px-6 py-3">
                            Headline
                        </th>
                        <th scope="col" class="px-6 py-3">
                            email
                        </th>
                        <th scope="col" class="px-6 py-3">
                            Phone
                        </th>
                        <th scope="col" class="px-6 py-3 whitespace-nowrap">
                            Application Status
                        </th>
                        <th scope="col" class="px-6 py-3">
                            Actions
                        </th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($instructorProfiles as $instructorProfile)
                        <tr class="bg-white border-b border-gray-200 hover:bg-gray-50 whitespace-nowrap">
                            <td class="w-4 p-4">
                                <div class="flex items-center">
                                    <input id="checkbox-table-search-1" type="checkbox"
                                        class="w-4 h-4 text-teal-600 bg-gray-100 border-gray-300 rounded-sm focus:ring-teal-500 focus:ring-2">
                                    <label for="checkbox-table-search-1" class="sr-only">checkbox</label>
                                </div>
                            </td>
                            <th scope="row" class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap">
                                {{ $instructorProfile->user->name }}
                            </th>
                            <th scope="row" class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap">
                                {{ $instructorProfile->application_id }}
                            </th>
                            <td class="px-6 py-4">
                                {{ $instructorProfile->headline ?? 'Not set' }}
                            </td>
                            <td class="px-6 py-4">
                                {{ $instructorProfile->user->email }}
                            </td>
                            <td class="px-6 py-4">
                                {{ $instructorProfile->user->phone ?? 'Not set' }}
                            </td>
                            <td class="px-6 py-4">
                                {{ $instructorProfile->status }}
                            </td>
                            <td class="px-6 py-4">
                                <div class="flex items-center justify-center gap-2">
                                    <button data-modal-target="popup-modal" data-modal-toggle="popup-modal"
                                        data-action-url="{{ route('admin.instructors.applications.approve', $instructorProfile) }}"
                                        title="Approve" @if ($instructorProfile->is_approved || $instructorProfile->status == 'draft') disabled @endif type="button"
                                        onclick="confirmApproval(this, {{ $instructorProfile }})"
                                        class="text-green-600 hover:text-green-800">
                                        <i class="size-4" data-lucide='circle-check-big'></i>
                                    </button>

                                    <a href="{{ route('admin.instructors.applications.view', $instructorProfile->id) }}"
                                        title="View Application" class=" text-blue-600 hover:text-blue-800">
                                        <i data-lucide="view" class="size-4"></i>
                                    </a>
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

    </x-instructor-dashbord-layout>
    <div id="popup-modal" tabindex="-1"
        class="hidden overflow-y-auto overflow-x-hidden fixed top-0 right-0 left-0 z-50 justify-center items-center w-full md:inset-0 h-[calc(100%-1rem)] max-h-full">
        <div class="relative p-4 w-full max-w-md max-h-full">
            <div class="relative bg-white rounded-lg shadow-sm">
                <button type="button"
                    class="absolute top-3 end-2.5 text-gray-400 bg-transparent rounded-lg text-sm w-8 h-8 ms-auto inline-flex justify-center items-center hover:bg-gray-600 hover:text-white"
                    data-modal-hide="popup-modal">
                    <i data-lucide="x" class="w-3 h-3"></i>
                    <span class="sr-only">Close modal</span>
                </button>
                <div class="p-4 md:p-5 text-center">
                    <svg class="mx-auto mb-4 text-gray-400 w-12 h-12" aria-hidden="true"
                        xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 20 20">
                        <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M10 11V6m0 8h.01M19 10a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
                    </svg>
                    <h3 class="mb-5 text-lg font-normal text-gray-500" id="confirmText"></h3>
                    <div class="flex items-center justify-center">
                        <form id='modal-form' method="post">
                            @csrf
                            @method("PATCH")
                            <button data-modal-hide="popup-modal" type="submit"
                                class="text-white bg-green-600 hover:bg-green-800 focus:ring-4 focus:outline-none focus:ring-red-300 font-medium rounded-lg text-sm inline-flex items-center px-5 py-2.5 text-center">
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
    <script>
        const approvalForm = document.getElementById("modal-form");
        const confirmText = document.getElementById("confirmText");
        function confirmApproval(button, profile) {

            confirmText.innerText = `Are you sure you want to approve the application for ${profile.application_id}?`;
            approvalForm.action = button.getAttribute('data-action-url');
        }
    </script>
</x-app-layout>
