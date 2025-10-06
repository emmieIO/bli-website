<x-app-layout>
    <div>
        <h1 class="text-3xl font-bold mb-4">
            Course Categories Administration
        </h1>
        <p class="text-gray-600 mb-6">
            Manage all course categories, add new ones, or update existing
            category details.
        </p>
        <div>
            <div class="">
                <a href="#" data-modal-target="create-category-modal" data-modal-toggle="create-category-modal"
                    class="btn inline-flex items-center gap-2 bg-orange-500 py-2 px-2 rounded-md font-medium text-white hover:bg-orange-800 transition-all">
                    <i data-lucide="folder-plus"></i>
                    <span>Create category</span>
                </a>
            </div>
        </div>
    </div>

    <div class="relative overflow-x-auto shadow-md sm:rounded-lg my-5">
        <table class="w-full text-sm text-left rtl:text-right text-gray-700">
            <thead class="text-xs text-gray-700 uppercase bg-gray-100">
                <tr>
                    <th scope="col" class="px-6 py-3 whitespace-nowrap">
                        Category Name
                    </th>
                    <th scope="col" class="px-6 py-3 whitespace-nowrap">
                        Description
                    </th>
                    <th scope="col" class="px-6 py-3 whitespace-nowrap">
                        Thumbnail Photo
                    </th>

                    <th scope="col" class="px-6 py-3 whitespace-nowrap">
                        Action
                    </th>
                </tr>
            </thead>
            <tbody>
                {{-- Loop through categories and display them here --}}
                @if (!$categories->isEmpty())
                    @foreach ($categories as $category)
                        <tr class="bg-white border-b border-gray-200 hover:bg-gray-50 whitespace-nowrap ">
                            <th scope="row" class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap">
                                {{ Str::ucfirst($category->name) }}
                            </th>
                            <td class="px-6 py-4">
                                {{ Str::limit($category->description, 50) ?? 'N/A' }}
                            </td>
                            <td class="px-6 py-4">
                                @if ($category->image)
                                    <img src="{{ asset('storage/' . $category->image) }}" alt="{{ $category->name }}"
                                        class="h-10 w-10 object-cover rounded" />
                                @else
                                    N/A
                                @endif
                            </td>

                            <td class="flex items-center px-6 py-4">
                                <button data-modal-target="update-category-modal"
                                    data-modal-toggle="update-category-modal"
                                    data-action="{{ route('admin.category.update', $category) }}"
                                    data-model="{{ json_encode([
                                        'name' => $category->name,
                                        'description' => $category->description,
                                        'image' => asset('storage/' . $category->image),
                                    ]) }}"
                                    onclick="populateUpdateModal(this)" title="Edit"
                                    class="text-blue-600 text-sm hover:text-blue-800 mr-2">
                                    <i data-lucide="edit" class="size-5"></i>
                                </button>
                                <button data-modal-target="delete-category-modal"
                                    data-modal-toggle="delete-category-modal"
                                    data-delete-route-action="{{ route('admin.category.destroy', $category) }}"
                                    onclick="populateDeleteForm(this)" title="Remove"
                                    class="text-red-600 hover:text-red-800">
                                    <i data-lucide="trash-2" class="size-5"></i>
                                </button>
                            </td>
                        </tr>
                    @endforeach
                @endif
            </tbody>
        </table>
    </div>

    <!-- create-category-modal -->
    <div id="create-category-modal" tabindex="-1" aria-hidden="true"
        class="hidden overflow-y-auto overflow-x-hidden fixed top-0 right-0 left-0 z-50 justify-center items-center w-full md:inset-0 h-[calc(100%-1rem)] max-h-full">
        <div class="relative p-4 w-full max-w-md max-h-full">
            <!-- Modal content -->
            <div class="relative bg-white rounded-lg shadow-sm">
                <!-- Modal header -->
                <div class="flex items-center justify-between p-4 md:p-5 border-b rounded-t border-gray-200">
                    <h3 class="text-lg font-semibold text-gray-900">
                        Create a new course category
                    </h3>
                    <button type="button"
                        class="text-gray-400 bg-transparent hover:bg-gray-200 hover:text-gray-900 rounded-lg text-sm w-8 h-8 ms-auto inline-flex justify-center items-center"
                        data-modal-toggle="create-category-modal">
                        <svg class="w-3 h-3" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none"
                            viewBox="0 0 14 14">
                            <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="m1 1 6 6m0 0 6 6M7 7l6-6M7 7l-6 6" />
                        </svg>
                        <span class="sr-only">Close modal</span>
                    </button>
                </div>
                <!-- Modal body -->
                <form action="{{ route('admin.category.store') }}" method="post" class="p-4 md:p-5"
                    enctype="multipart/form-data">
                    @csrf
                    <div class="grid gap-4 mb-4 grid-cols-2">
                        <div class="col-span-2">
                            <label for="name" class="block mb-2 text-sm font-medium text-gray-900">Category
                                Name</label>
                            <input type="text" name="name" id="name"
                                class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-600 focus:border-orange-600 focus:outline-none block w-full p-2.5"
                                placeholder="Enter category name" required="" style="text-transform: lowercase"
                                oninput="this.value = this.value.toLowerCase();" />
                        </div>
                        <div class="col-span-2">
                            <label class="block mb-2 text-sm font-medium text-gray-900" for="category_image">
                                Category Image
                            </label>
                            <input
                                class="block w-full text-sm text-gray-900 border border-gray-300 rounded-lg cursor-pointer bg-gray-50 focus:outline-none file:bg-orange-600"
                                id="category_image" name="category_image" type="file" accept="image/*" />
                            <p class="mt-1 text-sm text-gray-500" id="category_image_help">
                                Upload a category image (PNG, JPG, JPEG, or GIF,
                                max 800x400px).
                            </p>
                        </div>
                        <div class="col-span-2">
                            <label for="description" class="block mb-2 text-sm font-medium text-gray-900">Category
                                description</label>
                            <textarea id="description" rows="6" name="description"
                                class="block p-2.5 w-full text-sm text-gray-900 bg-gray-50 rounded-lg border border-gray-300 focus:ring-blue-500 resize-none focus:border-blue-500"
                                placeholder="enter category description..."></textarea>
                        </div>
                    </div>
                    <button type="submit"
                        class="text-white inline-flex items-center bg-orange-600 hover:bg-orange-800 focus:ring-4 focus:outline-none focus:ring-orange-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center">
                        <i data-lucide="circle-plus" class="me-1 -ms-1 w-5 h-5"></i>
                        Add new category
                    </button>
                </form>
            </div>
        </div>
    </div>

    <!-- update-category-modal -->
    <div id="update-category-modal" tabindex="-1" aria-hidden="true"
        class="hidden overflow-y-auto overflow-x-hidden fixed top-0 right-0 left-0 z-50 justify-center items-center w-full md:inset-0 h-[calc(100%-1rem)] max-h-full">
        <div class="relative p-4 w-full max-w-lg max-h-full">
            <!-- Modal content -->
            <div class="relative bg-white rounded-lg shadow-sm">
                <!-- Modal header -->
                <div class="flex items-center justify-between p-4 md:p-5 border-b rounded-t border-gray-200">
                    <h3 class="text-lg font-semibold text-gray-900">
                        Update course category
                    </h3>
                    <button type="button"
                        class="text-gray-400 bg-transparent hover:bg-gray-200 hover:text-gray-900 rounded-lg text-sm w-8 h-8 ms-auto inline-flex justify-center items-center"
                        data-modal-toggle="update-category-modal">
                        <svg class="w-3 h-3" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none"
                            viewBox="0 0 14 14">
                            <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"
                                stroke-width="2" d="m1 1 6 6m0 0 6 6M7 7l6-6M7 7l-6 6" />
                        </svg>
                        <span class="sr-only">Close modal</span>
                    </button>
                </div>
                <!-- Modal body -->
                <form action="" method="post" class="p-4 md:p-5" enctype="multipart/form-data">
                    @csrf @method('PUT')
                    <div class="grid gap-4 mb-4 grid-cols-2">
                        <div class="col-span-2">
                            <label for="name" class="block mb-2 text-sm font-medium text-gray-900">Category
                                Name</label>
                            <input type="text" name="name" id="name"
                                class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-600 focus:border-orange-600 focus:outline-none block w-full p-2.5"
                                placeholder="Enter category name" required="" style="text-transform: lowercase"
                                oninput="this.value = this.value.toLowerCase();" />
                        </div>
                        <div class="col-span-2">
                            <label class="block mb-2 text-sm font-medium text-gray-900" for="category_image">
                                Category Image
                            </label>
                            <input
                                class="block w-full text-sm text-gray-900 border border-gray-300 rounded-lg cursor-pointer bg-gray-50 focus:outline-none file:bg-orange-600"
                                id="category_image" name="category_image" type="file" accept="image/*" />
                            <p class="mt-1 text-sm text-gray-500" id="category_image_help">
                                Upload a category image (PNG, JPG, JPEG, or GIF,
                                max 800x400px).
                            </p>
                            <small><a class="font-bold text-orange-600" id="catgory_current_image"></a></small>
                        </div>
                        <div class="col-span-2">
                            <label for="description" class="block mb-2 text-sm font-medium text-gray-900">Category
                                description</label>
                            <textarea id="description" rows="6" name="description"
                                class="block p-2.5 w-full text-sm text-gray-900 bg-gray-50 rounded-lg border border-gray-300 focus:ring-blue-500 resize-none focus:border-blue-500"
                                placeholder="enter category description..."></textarea>
                        </div>
                    </div>
                    <button type="submit"
                        class="text-white inline-flex items-center bg-orange-600 hover:bg-orange-800 focus:ring-4 focus:outline-none focus:ring-orange-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center">
                        <i data-lucide="circle-plus" class="me-1 -ms-1 w-5 h-5"></i>
                        Update category
                    </button>
                </form>
            </div>
        </div>
    </div>

    {{-- delete category modal --}}
    <div id="delete-category-modal" tabindex="-1"
        class="hidden overflow-y-auto overflow-x-hidden fixed top-0 right-0 left-0 z-50 justify-center items-center w-full md:inset-0 h-[calc(100%-1rem)] max-h-full">
        <div class="relative p-4 w-full max-w-md max-h-full">
            <div class="relative bg-white rounded-lg shadow-sm">
                <button type="button"
                    class="absolute top-3 end-2.5 text-gray-400 bg-transparent hover:bg-gray-200 hover:text-gray-900 rounded-lg text-sm w-8 h-8 ms-auto inline-flex justify-center items-center"
                    data-modal-hide="delete-category-modal">
                    <svg class="w-3 h-3" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 14 14">
                        <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="m1 1 6 6m0 0 6 6M7 7l6-6M7 7l-6 6" />
                    </svg>
                    <span class="sr-only">Close modal</span>
                </button>
                <div class="p-4 md:p-5 text-center">
                    <svg class="mx-auto mb-4 w-12 h-12  text-orange-500" aria-hidden="true"
                        xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 20 20">
                        <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M10 11V6m0 8h.01M19 10a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
                    </svg>
                    <h3 id="title" class="mb-5 text-md font-normal text-gray-700">
                        Are you sure you want to delete this category? This action cannot be undone.
                    </h3>
                    <form id="delete-category-form" method="post">
                        @csrf
                        @method('DELETE')
                        <button data-modal-hide="delete-category-modal" type="submit"
                            class="text-white bg-red-600 hover:bg-red-800 focus:ring-4 focus:outline-none focus:ring-red-300 font-medium rounded-lg text-sm inline-flex items-center px-5 py-2.5 text-center">
                            Yes, I'm sure
                        </button>
                        <button data-modal-hide="delete-category-modal" type="button" aria-hidden="true"
                            class="py-2.5 px-5 ms-3 text-sm font-medium text-gray-900 focus:outline-none bg-white rounded-lg border border-gray-200 hover:bg-gray-100 hover:text-blue-700 focus:z-10 focus:ring-4 focus:ring-gray-100">
                            No, cancel
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script>
        // populate delete form action
        function populateDeleteForm(deleteButton) {
            // Get the data-delete-route-action attribute from the button
            const actionRoute = deleteButton.getAttribute("data-delete-route-action");

            const delete_form = document.querySelector("#delete-category-modal #delete-category-form")
            delete_form.action = actionRoute
        }

        // Function to populate the update modal with category data
        function populateUpdateModal(button) {
            // Get category data from button attributes
            const actionRoute = button.getAttribute("data-action");

            const model = JSON.parse(button.getAttribute("data-model"));

            const categoryName = model.name;
            const categoryDescription = model.description;
            const categoryImage = model.image;
            // Set form action
            document.querySelector("#update-category-modal form").action =
                actionRoute;

            // Set input values
            document.querySelector("#update-category-modal #name").value =
                categoryName || "";
            document.querySelector(
                "#update-category-modal #description"
            ).value = categoryDescription || "";

            // Set current image link/text
            const currentImageElem = document.querySelector(
                "#catgory_current_image"
            );
            if (categoryImage) {
                currentImageElem.textContent = "see current image";
                currentImageElem.href = `${categoryImage}`;
                currentImageElem.target = "_blank";
            } else {
                currentImageElem.textContent = "No image available";
                currentImageElem.removeAttribute("href");
                currentImageElem.removeAttribute("target");
            }
        }
    </script>
</x-app-layout>
