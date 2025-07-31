<x-app-layout>
    <x-instructor-dashbord-layout>
        <div class="relative overflow-x-auto shadow-md sm:rounded-lg my-5">
            <div class="flex flex-column sm:flex-row flex-wrap space-y-4 sm:space-y-0 items-center justify-between p-4">
                <div>
                    <button id="dropdownRadioButton" data-dropdown-toggle="dropdownRadio"
                        class="inline-flex items-center text-gray-500 bg-white border border-gray-300 focus:outline-none hover:bg-gray-100 focus:ring-4 focus:ring-teal-100 font-medium rounded-lg text-sm px-3 py-1.5"
                        type="button">
                        <svg class="w-3 h-3 text-gray-500 me-3" aria-hidden="true" xmlns="http://www.w3.org/2000/svg"
                            fill="currentColor" viewBox="0 0 20 20">
                            <path
                                d="M10 0a10 10 0 1 0 10 10A10.011 10.011 0 0 0 10 0Zm3.982 13.982a1 1 0 0 1-1.414 0l-3.274-3.274A1.012 1.012 0 0 1 9 10V6a1 1 0 0 1 2 0v3.586l2.982 2.982a1 1 0 0 1 0 1.414Z" />
                        </svg>
                        Filter by status
                        <svg class="w-2.5 h-2.5 ms-2.5" aria-hidden="true" xmlns="http://www.w3.org/2000/svg"
                            fill="none" viewBox="0 0 10 6">
                            <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="m1 1 4 4 4-4" />
                        </svg>
                    </button>
                    <!-- Dropdown menu -->
                    <div id="dropdownRadio"
                        class="z-10 hidden w-48 bg-white divide-y divide-gray-100 rounded-lg shadow-sm">
                        <ul class="p-3 space-y-1 text-sm text-gray-700" aria-labelledby="dropdownRadioButton">
                            <li>
                                <div class="flex items-center p-2 rounded-sm hover:bg-gray-100">
                                    <input id="filter-radio-example-1" type="radio" value="" name="filter-radio"
                                        class="w-4 h-4 text-teal-600 bg-gray-100 border-gray-300 focus:ring-teal-500 focus:ring-2">
                                    <label for="filter-radio-example-1"
                                        class="w-full ms-2 text-sm font-medium text-gray-900 rounded-sm">Approved</label>
                                </div>
                            </li>
                            <li>
                                <div class="flex items-center p-2 rounded-sm hover:bg-gray-100">
                                    <input checked="" id="filter-radio-example-2" type="radio" value=""
                                        name="filter-radio"
                                        class="w-4 h-4 text-teal-600 bg-gray-100 border-gray-300 focus:ring-teal-500 focus:ring-2">
                                    <label for="filter-radio-example-2"
                                        class="w-full ms-2 text-sm font-medium text-gray-900 rounded-sm">Draft</label>
                                </div>
                            </li>
                            <li>
                                <div class="flex items-center p-2 rounded-sm hover:bg-gray-100">
                                    <input id="filter-radio-example-3" type="radio" value="" name="filter-radio"
                                        class="w-4 h-4 text-teal-600 bg-gray-100 border-gray-300 focus:ring-teal-500 focus:ring-2">
                                    <label for="filter-radio-example-3"
                                        class="w-full ms-2 text-sm font-medium text-gray-900 rounded-sm">Submitted</label>
                                </div>
                            </li>
                        </ul>
                    </div>
                </div>
                <label for="table-search" class="sr-only">Search</label>
                <div class="relative">
                    <div
                        class="absolute inset-y-0 left-0 rtl:inset-r-0 rtl:right-0 flex items-center ps-3 pointer-events-none">
                        <svg class="w-5 h-5 text-gray-500" aria-hidden="true" fill="currentColor" viewBox="0 0 20 20"
                            xmlns="http://www.w3.org/2000/svg">
                            <path fill-rule="evenodd"
                                d="M8 4a4 4 0 100 8 4 4 0 000-8zM2 8a6 6 0 1110.89 3.476l4.817 4.817a1 1 0 01-1.414 1.414l-4.816-4.816A6 6 0 012 8z"
                                clip-rule="evenodd"></path>
                        </svg>
                    </div>
                    <input type="text" id="table-search"
                        class="block p-2 ps-10 text-sm text-gray-900 border border-gray-300 rounded-lg w-80 bg-gray-50 focus:ring-teal-500 focus:border-teal-500"
                        placeholder="Search application by name,email or phone">
                </div>
            </div>
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
                                <div class="flex items-center gap-4">
                                    @if ($instructorProfile->status === 'submitted')
                                        <form action="{{ route('admin.instructors.applications.approve', $instructorProfile) }}" method="POST">
                                            @csrf
                                            @method("PATCH")
                                            <button 
                                                title="Approve"
                                                type="submit"
                                                class="inline-flex items-center text-green-600 hover:text-green-800">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20"
                                                    viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                                    stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                                                    class="lucide lucide-check">
                                                    <path d="M20 6 9 17l-5-5" />
                                                </svg>
                                            </button>
                                        </form>

                                            <a href="{{ route('admin.instructors.applications.view', $instructorProfile->id) }}"
                                                title="View Application"
                                                class="inline-flex items-center text-blue-600 hover:text-blue-800">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20"
                                                    viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                                    stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                                                    class="lucide lucide-eye">
                                                    <path d="M2 12s3-7 10-7 10 7 10 7-3 7-10 7-10-7-10-7Z" />
                                                    <circle cx="12" cy="12" r="3" />
                                                </svg>
                                            </a>

                                            <form
                                                action="{{ route('admin.instructors.applications.deny', $instructorProfile->id) }}"
                                                method="POST" class="inline">
                                                @csrf
                                                @method('PATCH')
                                                <button type="submit" title="Deny"
                                                    class="inline-flex items-center text-red-600 hover:text-red-800">
                                                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20"
                                                        viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                                        stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                                                        class="lucide lucide-x">
                                                        <path d="M18 6 6 18" />
                                                        <path d="m6 6 12 12" />
                                                    </svg>
                                                </button>
                                            </form>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

    </x-instructor-dashbord-layout>
</x-app-layout>
