<x-app-layout>
    <x-instructor-dashbord-layout>
          

<div class="relative overflow-x-auto shadow-md sm:rounded-lg">
     <table class="w-full text-sm text-left rtl:text-right text-gray-500">
          <thead class="text-xs text-gray-700 uppercase bg-gray-50">
                <tr>
                     <th scope="col" class="p-4">
                          <div class="flex items-center">
                                <input id="checkbox-all-search" type="checkbox" class="w-4 h-4 text-teal-600 bg-gray-100 border-gray-300 rounded-sm focus:ring-teal-500 focus:ring-2">
                                <label for="checkbox-all-search" class="sr-only">checkbox</label>
                          </div>
                     </th>
                     <th scope="col" class="px-6 py-3">
                          Profile Id
                     </th>
                     <th scope="col" class="px-6 py-3">
                          FullName
                     </th>
                     <th scope="col" class="px-6 py-3">
                          Email
                     </th>
                     <th scope="col" class="px-6 py-3">
                          Phone
                     </th>
                     <th scope="col" class="px-6 py-3">
                          Headline
                     </th>
                     <th scope="col" class="px-6 py-3">
                          Action
                     </th>
                </tr>
          </thead>
          <tbody>
            @foreach ($instructors as $instructor)
            <tr class="bg-white border-b border-gray-200 hover:bg-gray-50">
                 <td class="w-4 p-4">
                      <div class="flex items-center">
                            <input id="checkbox-table-search-1" type="checkbox" class="w-4 h-4 text-teal-600 bg-gray-100 border-gray-300 rounded-sm focus:ring-teal-500 focus:ring-2">
                            <label for="checkbox-table-search-1" class="sr-only">checkbox</label>
                      </div>
                 </td>
                 <th scope="row" class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap">
                      {{ $instructor->application_id }}
                 </th>
                 <th scope="row" class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap">
                      {{ $instructor->user->name }}
                 </th>
                 <td class="px-6 py-4">
                      {{ $instructor->user->email }}
                 </td>
                 <td class="px-6 py-4">
                      {{ $instructor->user->phone }}
                 </td>
                 <td class="px-6 py-4">
                      {{ $instructor->headline }}
                 </td>
                 <td class="flex items-center px-6 py-4">
                      <a href="#" class="font-medium text-teal-600 hover:underline">Edit</a>
                      <a href="#" class="font-medium text-red-600 hover:underline ms-3">Remove</a>
                 </td>
            </tr>
            @endforeach
          </tbody>
     </table>
</div>

    </x-instructor-dashbord-layout>
</x-app-layout>
