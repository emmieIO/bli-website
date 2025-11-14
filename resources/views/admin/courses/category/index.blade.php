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
                    class="btn inline-flex items-center gap-2 bg-primary-600 py-2 px-4 rounded-lg font-medium text-white hover:bg-primary-700 transition-all duration-200 shadow-sm">
                    <i data-lucide="folder-plus"></i>
                    <span>Create Category</span>
                </a>
            </div>
        </div>
    </div>

    <div class="relative overflow-x-auto shadow-lg rounded-xl my-6 bg-white border border-slate-200">
        <table class="w-full text-sm text-left rtl:text-right text-slate-600">
            <thead class="text-xs text-slate-600 uppercase bg-slate-50 border-b border-slate-200">
                <tr>
                    <th scope="col" class="px-6 py-4 font-semibold whitespace-nowrap">
                        Category Name
                    </th>
                    <th scope="col" class="px-6 py-4 font-semibold whitespace-nowrap">
                        Description
                    </th>
                    <th scope="col" class="px-6 py-4 font-semibold whitespace-nowrap">
                        Thumbnail Photo
                    </th>

                    <th scope="col" class="px-6 py-4 font-semibold whitespace-nowrap">
                        Actions
                    </th>
                </tr>
            </thead>
            <tbody>
                {{-- Loop through categories and display them here --}}
                @if (!$categories->isEmpty())
                    @foreach ($categories as $category)
                                <tr
                                    class="bg-white border-b border-slate-100 hover:bg-slate-50 transition-colors duration-150 whitespace-nowrap ">
                                    <th scope="row" class="px-6 py-4 font-medium text-slate-900 whitespace-nowrap">
                                        {{ Str::ucfirst($category->name) }}
                                    </th>
                                    <td class="px-6 py-4 text-slate-600">
                                        {{ Str::limit($category->description, 50) ?? 'N/A' }}
                                    </td>
                                    <td class="px-6 py-4">
                                        @if ($category->image)
                                            <img src="{{ asset('storage/' . $category->image) }}" alt="{{ $category->name }}"
                                                class="h-12 w-12 object-cover rounded-lg shadow-sm border border-slate-200" />
                                        @else
                                            <div class="h-12 w-12 bg-slate-100 rounded-lg flex items-center justify-center">
                                                <i data-lucide="image" class="h-5 w-5 text-slate-400"></i>
                                            </div>
                                        @endif
                                    </td>

                                    <td class="flex items-center gap-2 px-6 py-4">
                                        <button data-modal-target="update-category-modal" data-modal-toggle="update-category-modal"
                                            data-action="{{ route('admin.category.update', $category) }}" data-model="{{ json_encode([
                            'name' => $category->name,
                            'description' => $category->description,
                            'image' => asset('storage/' . $category->image),
                        ]) }}" onclick="populateUpdateModal(this)" title="Edit Category"
                                            class="inline-flex items-center justify-center w-8 h-8 text-primary-600 hover:text-primary-700 hover:bg-primary-50 rounded-lg transition-colors duration-200">
                                            <i data-lucide="edit" class="w-4 h-4"></i>
                                        </button>
                                        <button data-modal-target="delete-category-modal" data-modal-toggle="delete-category-modal"
                                            data-delete-route-action="{{ route('admin.category.destroy', $category) }}"
                                            onclick="populateDeleteForm(this)" title="Delete Category"
                                            class="inline-flex items-center justify-center w-8 h-8 text-red-600 hover:text-red-700 hover:bg-red-50 rounded-lg transition-colors duration-200">
                                            <i data-lucide="trash-2" class="w-4 h-4"></i>
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
                <div class="flex items-center justify-between p-4 md:p-5 border-b rounded-t border-slate-200">
                    <h3 class="text-lg font-semibold text-slate-900">
                        Create New Course Category
                    </h3>
                    <button type="button"
                        class="text-slate-400 bg-transparent hover:bg-slate-100 hover:text-slate-600 rounded-lg text-sm w-8 h-8 ms-auto inline-flex justify-center items-center transition-colors duration-200"
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
                            <label for="name" class="block mb-2 text-sm font-medium text-slate-700">Category
                                Name</label>
                            <input type="text" name="name" id="name"
                                class="bg-slate-50 border border-slate-300 text-slate-900 text-sm rounded-lg focus:ring-primary-500 focus:border-primary-500 focus:outline-none block w-full p-3 transition-colors duration-200"
                                placeholder="Enter category name" required="" style="text-transform: lowercase"
                                oninput="this.value = this.value.toLowerCase();" />
                        </div>
                        <div class="col-span-2">
                            <label class="block mb-2 text-sm font-medium text-slate-700" for="category_image">
                                Category Image
                            </label>
                            <input
                                class="block w-full text-sm text-slate-900 border border-slate-300 rounded-lg cursor-pointer bg-slate-50 focus:outline-none file:bg-primary-600 file:text-white file:border-0 file:py-2 file:px-4 file:rounded-l-lg file:hover:bg-primary-700 transition-colors duration-200"
                                id="category_image" name="category_image" type="file" accept="image/*" />
                            <p class="mt-1 text-sm text-slate-500" id="category_image_help">
                                Upload a category image (PNG, JPG, JPEG, or GIF,
                                max 800x400px).
                            </p>
                        </div>
                        <div class="col-span-2">
                            <label for="description" class="block mb-2 text-sm font-medium text-slate-700">Category
                                Description</label>
                            <textarea id="description" rows="4" name="description"
                                class="block p-3 w-full text-sm text-slate-900 bg-slate-50 rounded-lg border border-slate-300 focus:ring-primary-500 resize-none focus:border-primary-500 transition-colors duration-200"
                                placeholder="Enter category description..."></textarea>
                        </div>
                    </div>
                    <button type="submit"
                        class="text-white inline-flex items-center bg-primary-600 hover:bg-primary-700 focus:ring-4 focus:outline-none focus:ring-primary-200 font-medium rounded-lg text-sm px-6 py-3 text-center transition-colors duration-200">
                        <i data-lucide="circle-plus" class="me-2 w-4 h-4"></i>
                        Create Category
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
                <div class="flex items-center justify-between p-4 md:p-5 border-b rounded-t border-slate-200">
                    <h3 class="text-lg font-semibold text-slate-900">
                        Update Course Category
                    </h3>
                    <button type="button"
                        class="text-slate-400 bg-transparent hover:bg-slate-100 hover:text-slate-600 rounded-lg text-sm w-8 h-8 ms-auto inline-flex justify-center items-center transition-colors duration-200"
                        data-modal-toggle="update-category-modal">
                        <svg class="w-3 h-3" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none"
                            viewBox="0 0 14 14">
                            <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="m1 1 6 6m0 0 6 6M7 7l6-6M7 7l-6 6" />
                        </svg>
                        <span class="sr-only">Close modal</span>
                    </button>
                </div>
                <!-- Modal body -->
                <form action="" method="post" class="p-4 md:p-5" enctype="multipart/form-data">
                    @csrf @method('PUT')
                    <div class="grid gap-4 mb-4 grid-cols-2">
                        <div class="col-span-2">
                            <label for="name" class="block mb-2 text-sm font-medium text-slate-700">Category
                                Name</label>
                            <input type="text" name="name" id="name"
                                class="bg-slate-50 border border-slate-300 text-slate-900 text-sm rounded-lg focus:ring-primary-500 focus:border-primary-500 focus:outline-none block w-full p-3 transition-colors duration-200"
                                placeholder="Enter category name" required="" style="text-transform: lowercase"
                                oninput="this.value = this.value.toLowerCase();" />
                        </div>
                        <div class="col-span-2">
                            <label class="block mb-2 text-sm font-medium text-slate-700" for="category_image">
                                Category Image
                            </label>
                            <input
                                class="block w-full text-sm text-slate-900 border border-slate-300 rounded-lg cursor-pointer bg-slate-50 focus:outline-none file:bg-primary-600 file:text-white file:border-0 file:py-2 file:px-4 file:rounded-l-lg file:hover:bg-primary-700 transition-colors duration-200"
                                id="category_image" name="category_image" type="file" accept="image/*" />
                            <p class="mt-1 text-sm text-slate-500" id="category_image_help">
                                Upload a category image (PNG, JPG, JPEG, or GIF,
                                max 800x400px).
                            </p>
                            <small><a class="font-medium text-primary-600 hover:text-primary-700"
                                    id="catgory_current_image"></a></small>
                        </div>
                        <div class="col-span-2">
                            <label for="description" class="block mb-2 text-sm font-medium text-slate-700">Category
                                Description</label>
                            <textarea id="description" rows="4" name="description"
                                class="block p-3 w-full text-sm text-slate-900 bg-slate-50 rounded-lg border border-slate-300 focus:ring-primary-500 resize-none focus:border-primary-500 transition-colors duration-200"
                                placeholder="Enter category description..."></textarea>
                        </div>
                    </div>
                    <button type="submit"
                        class="text-white inline-flex items-center bg-primary-600 hover:bg-primary-700 focus:ring-4 focus:outline-none focus:ring-primary-200 font-medium rounded-lg text-sm px-6 py-3 text-center transition-colors duration-200">
                        <i data-lucide="edit" class="me-2 w-4 h-4"></i>
                        Update Category
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
                    class="absolute top-3 end-2.5 text-slate-400 bg-transparent hover:bg-slate-100 hover:text-slate-600 rounded-lg text-sm w-8 h-8 ms-auto inline-flex justify-center items-center transition-colors duration-200"
                    data-modal-hide="delete-category-modal">
                    <svg class="w-3 h-3" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 14 14">
                        <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="m1 1 6 6m0 0 6 6M7 7l6-6M7 7l-6 6" />
                    </svg>
                    <span class="sr-only">Close modal</span>
                </button>
                <div class="p-4 md:p-5 text-center">
                    <svg class="mx-auto mb-4 w-12 h-12 text-red-500" aria-hidden="true"
                        xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 20 20">
                        <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M10 11V6m0 8h.01M19 10a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
                    </svg>
                    <h3 id="title" class="mb-5 text-md font-medium text-slate-700">
                        Are you sure you want to delete this category? This action cannot be undone.
                    </h3>
                    <form id="delete-category-form" method="post">
                        @csrf
                        @method('DELETE')
                        <button data-modal-hide="delete-category-modal" type="submit"
                            class="text-white bg-red-600 hover:bg-red-700 focus:ring-4 focus:outline-none focus:ring-red-200 font-medium rounded-lg text-sm inline-flex items-center px-6 py-3 text-center transition-colors duration-200">
                            Yes, Delete Category
                        </button>
                        <button data-modal-hide="delete-category-modal" type="button" aria-hidden="true"
                            class="py-3 px-6 ms-3 text-sm font-medium text-slate-700 focus:outline-none bg-white rounded-lg border border-slate-300 hover:bg-slate-50 hover:text-slate-800 focus:z-10 focus:ring-4 focus:ring-slate-100 transition-colors duration-200">
                            Cancel
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