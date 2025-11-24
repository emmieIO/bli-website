import { useState, FormEvent, ChangeEvent } from 'react';
import { Head, Link, router, usePage } from '@inertiajs/react';
import DashboardLayout from '@/Layouts/DashboardLayout';

interface Category {
    id: number;
    slug: string;
    name: string;
    description?: string;
    image?: string;
}

interface CategoryIndexProps {
    categories: Category[];
}

interface CreateFormData {
    name: string;
    description: string;
}

interface UpdateFormData {
    name: string;
    description: string;
}

export default function CategoryIndex({ categories }: CategoryIndexProps) {
    const { sideLinks, errors } = usePage().props as any;

    // Create modal state
    const [showCreateModal, setShowCreateModal] = useState(false);
    const [createFormData, setCreateFormData] = useState<CreateFormData>({
        name: '',
        description: '',
    });
    const [createImageFile, setCreateImageFile] = useState<File | null>(null);
    const [isCreating, setIsCreating] = useState(false);

    // Update modal state
    const [showUpdateModal, setShowUpdateModal] = useState(false);
    const [selectedCategory, setSelectedCategory] = useState<Category | null>(null);
    const [updateFormData, setUpdateFormData] = useState<UpdateFormData>({
        name: '',
        description: '',
    });
    const [updateImageFile, setUpdateImageFile] = useState<File | null>(null);
    const [isUpdating, setIsUpdating] = useState(false);

    // Delete modal state
    const [showDeleteModal, setShowDeleteModal] = useState(false);
    const [categoryToDelete, setCategoryToDelete] = useState<Category | null>(null);
    const [isDeleting, setIsDeleting] = useState(false);

    // Create handlers
    const handleCreateInputChange = (e: ChangeEvent<HTMLInputElement | HTMLTextAreaElement>) => {
        const { name, value } = e.target;
        setCreateFormData(prev => ({ ...prev, [name]: value.toLowerCase() }));
    };

    const handleCreateImageChange = (e: ChangeEvent<HTMLInputElement>) => {
        if (e.target.files && e.target.files[0]) {
            setCreateImageFile(e.target.files[0]);
        }
    };

    const handleCreateSubmit = (e: FormEvent) => {
        e.preventDefault();
        setIsCreating(true);

        const data = new FormData();
        data.append('name', createFormData.name);
        data.append('description', createFormData.description);

        if (createImageFile) {
            data.append('category_image', createImageFile);
        }

        router.post(route('admin.category.store'), data, {
            preserveScroll: true,
            onSuccess: () => {
                setShowCreateModal(false);
                setCreateFormData({ name: '', description: '' });
                setCreateImageFile(null);
            },
            onFinish: () => {
                setIsCreating(false);
            },
        });
    };

    // Update handlers
    const openUpdateModal = (category: Category) => {
        setSelectedCategory(category);
        setUpdateFormData({
            name: category.name,
            description: category.description || '',
        });
        setUpdateImageFile(null);
        setShowUpdateModal(true);
    };

    const handleUpdateInputChange = (e: ChangeEvent<HTMLInputElement | HTMLTextAreaElement>) => {
        const { name, value } = e.target;
        setUpdateFormData(prev => ({ ...prev, [name]: value.toLowerCase() }));
    };

    const handleUpdateImageChange = (e: ChangeEvent<HTMLInputElement>) => {
        if (e.target.files && e.target.files[0]) {
            setUpdateImageFile(e.target.files[0]);
        }
    };

    const handleUpdateSubmit = (e: FormEvent) => {
        e.preventDefault();
        if (!selectedCategory) return;

        setIsUpdating(true);

        const data = new FormData();
        data.append('name', updateFormData.name);
        data.append('description', updateFormData.description);
        data.append('_method', 'PUT');

        if (updateImageFile) {
            data.append('category_image', updateImageFile);
        }

        router.post(route('admin.category.update', selectedCategory.slug), data, {
            preserveScroll: true,
            onSuccess: () => {
                setShowUpdateModal(false);
                setSelectedCategory(null);
                setUpdateImageFile(null);
            },
            onFinish: () => {
                setIsUpdating(false);
            },
        });
    };

    // Delete handlers
    const openDeleteModal = (category: Category) => {
        setCategoryToDelete(category);
        setShowDeleteModal(true);
    };

    const handleDelete = (e: FormEvent) => {
        e.preventDefault();
        if (!categoryToDelete) return;

        setIsDeleting(true);
        router.delete(route('admin.category.destroy', categoryToDelete.slug), {
            preserveScroll: true,
            onSuccess: () => {
                setShowDeleteModal(false);
                setCategoryToDelete(null);
            },
            onFinish: () => {
                setIsDeleting(false);
            },
        });
    };

    return (
        <DashboardLayout sideLinks={sideLinks}>
            <Head title="Course Categories Administration" />

            {/* Header */}
            <div>
                <h1 className="text-3xl font-bold mb-4 text-gray-800">
                    Course Categories Administration
                </h1>
                <p className="text-gray-600 mb-6">
                    Manage all course categories, add new ones, or update existing category details.
                </p>
                <div>
                    <button
                        onClick={() => setShowCreateModal(true)}
                        className="inline-flex items-center gap-2 bg-primary hover:bg-primary-600 py-2 px-4 rounded-lg font-medium text-white transition-all duration-200 shadow-sm"
                    >
                        <i className="fas fa-folder-plus w-4 h-4"></i>
                        <span>Create Category</span>
                    </button>
                </div>
            </div>

            {/* Categories Table */}
            <div className="relative overflow-x-auto shadow-lg rounded-xl my-6 bg-white border border-gray-200">
                <table className="w-full text-sm text-left text-gray-600">
                    <thead className="text-xs text-gray-600 uppercase bg-gray-50 border-b border-gray-200">
                        <tr>
                            <th scope="col" className="px-6 py-4 font-semibold whitespace-nowrap">
                                Category Name
                            </th>
                            <th scope="col" className="px-6 py-4 font-semibold whitespace-nowrap">
                                Description
                            </th>
                            <th scope="col" className="px-6 py-4 font-semibold whitespace-nowrap">
                                Thumbnail Photo
                            </th>
                            <th scope="col" className="px-6 py-4 font-semibold whitespace-nowrap">
                                Actions
                            </th>
                        </tr>
                    </thead>
                    <tbody>
                        {categories.length > 0 ? (
                            categories.map((category) => (
                                <tr
                                    key={category.id}
                                    className="bg-white border-b border-gray-100 hover:bg-gray-50 transition-colors duration-150"
                                >
                                    <th scope="row" className="px-6 py-4 font-medium text-gray-900 whitespace-nowrap">
                                        {category.name.charAt(0).toUpperCase() + category.name.slice(1)}
                                    </th>
                                    <td className="px-6 py-4 text-gray-600">
                                        {category.description
                                            ? category.description.substring(0, 50) + (category.description.length > 50 ? '...' : '')
                                            : 'N/A'}
                                    </td>
                                    <td className="px-6 py-4">
                                        {category.image ? (
                                            <img
                                                src={`/storage/${category.image}`}
                                                alt={category.name}
                                                className="h-12 w-12 object-cover rounded-lg shadow-sm border border-gray-200"
                                            />
                                        ) : (
                                            <div className="h-12 w-12 bg-gray-100 rounded-lg flex items-center justify-center">
                                                <i className="fas fa-image w-5 h-5 text-gray-400"></i>
                                            </div>
                                        )}
                                    </td>
                                    <td className="px-6 py-4">
                                        <div className="flex items-center gap-2">
                                            <button
                                                onClick={() => openUpdateModal(category)}
                                                title="Edit Category"
                                                className="inline-flex items-center justify-center w-8 h-8 text-primary-600 hover:text-primary-700 hover:bg-primary-50 rounded-lg transition-colors duration-200"
                                            >
                                                <i className="fas fa-edit w-4 h-4"></i>
                                            </button>
                                            <button
                                                onClick={() => openDeleteModal(category)}
                                                title="Delete Category"
                                                className="inline-flex items-center justify-center w-8 h-8 text-red-600 hover:text-red-700 hover:bg-red-50 rounded-lg transition-colors duration-200"
                                            >
                                                <i className="fas fa-trash-alt w-4 h-4"></i>
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                            ))
                        ) : (
                            <tr>
                                <td colSpan={4} className="px-6 py-16 text-center">
                                    <div className="flex flex-col items-center justify-center space-y-4 text-gray-400">
                                        <i className="fas fa-folder-open w-12 h-12 text-gray-300"></i>
                                        <h3 className="text-lg font-medium text-gray-900">No categories found</h3>
                                        <p className="max-w-md text-center">
                                            Get started by creating your first category.
                                        </p>
                                    </div>
                                </td>
                            </tr>
                        )}
                    </tbody>
                </table>
            </div>

            {/* Create Category Modal */}
            {showCreateModal && (
                <div className="fixed inset-0 z-50 flex items-center justify-center bg-black/30 backdrop-blur-sm">
                    <div className="relative bg-white rounded-lg shadow-xl p-4 w-full max-w-md mx-4 border border-gray-200">
                        {/* Modal header */}
                        <div className="flex items-center justify-between p-4 border-b rounded-t border-gray-200">
                            <h3 className="text-lg font-semibold text-gray-900">Create New Course Category</h3>
                            <button
                                type="button"
                                onClick={() => setShowCreateModal(false)}
                                className="text-gray-400 bg-transparent hover:bg-gray-100 hover:text-gray-600 rounded-lg text-sm w-8 h-8 inline-flex justify-center items-center transition-colors"
                            >
                                <i className="fas fa-times w-4 h-4"></i>
                            </button>
                        </div>

                        {/* Modal body */}
                        <form onSubmit={handleCreateSubmit} className="p-4">
                            <div className="grid gap-4 mb-4 grid-cols-2">
                                <div className="col-span-2">
                                    <label htmlFor="create-name" className="block mb-2 text-sm font-medium text-gray-700">
                                        Category Name
                                    </label>
                                    <input
                                        type="text"
                                        name="name"
                                        id="create-name"
                                        value={createFormData.name}
                                        onChange={handleCreateInputChange}
                                        className="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-500 focus:border-primary-500 block w-full p-3"
                                        placeholder="Enter category name"
                                        required
                                    />
                                    {errors?.name && (
                                        <p className="mt-2 text-sm text-red-600">{errors.name}</p>
                                    )}
                                </div>
                                <div className="col-span-2">
                                    <label htmlFor="create-category-image" className="block mb-2 text-sm font-medium text-gray-700">
                                        Category Image
                                    </label>
                                    <input
                                        type="file"
                                        id="create-category-image"
                                        onChange={handleCreateImageChange}
                                        accept="image/*"
                                        className="block w-full text-sm text-gray-900 border border-gray-300 rounded-lg cursor-pointer bg-gray-50 file:bg-primary-600 file:text-white file:border-0 file:py-2 file:px-4 file:rounded-l-lg file:hover:bg-primary-700"
                                        required
                                    />
                                    <p className="mt-1 text-sm text-gray-500">
                                        Upload a category image (PNG, JPG, JPEG, or GIF, max 1MB).
                                    </p>
                                    {errors?.category_image && (
                                        <p className="mt-2 text-sm text-red-600">{errors.category_image}</p>
                                    )}
                                </div>
                                <div className="col-span-2">
                                    <label htmlFor="create-description" className="block mb-2 text-sm font-medium text-gray-700">
                                        Category Description
                                    </label>
                                    <textarea
                                        id="create-description"
                                        name="description"
                                        rows={4}
                                        value={createFormData.description}
                                        onChange={handleCreateInputChange}
                                        className="block p-3 w-full text-sm text-gray-900 bg-gray-50 rounded-lg border border-gray-300 focus:ring-primary-500 resize-none focus:border-primary-500"
                                        placeholder="Enter category description..."
                                    />
                                    {errors?.description && (
                                        <p className="mt-2 text-sm text-red-600">{errors.description}</p>
                                    )}
                                </div>
                            </div>
                            <button
                                type="submit"
                                disabled={isCreating}
                                className="text-white inline-flex items-center bg-primary-600 hover:bg-primary-700 focus:ring-4 focus:outline-none focus:ring-primary-200 font-medium rounded-lg text-sm px-6 py-3 text-center disabled:opacity-50 disabled:cursor-not-allowed"
                            >
                                <i className="fas fa-plus-circle w-4 h-4 mr-2"></i>
                                {isCreating ? 'Creating...' : 'Create Category'}
                            </button>
                        </form>
                    </div>
                </div>
            )}

            {/* Update Category Modal */}
            {showUpdateModal && selectedCategory && (
                <div className="fixed inset-0 z-50 flex items-center justify-center bg-black/30 backdrop-blur-sm">
                    <div className="relative bg-white rounded-lg shadow-xl p-4 w-full max-w-lg mx-4 border border-gray-200">
                        {/* Modal header */}
                        <div className="flex items-center justify-between p-4 border-b rounded-t border-gray-200">
                            <h3 className="text-lg font-semibold text-gray-900">Update Course Category</h3>
                            <button
                                type="button"
                                onClick={() => setShowUpdateModal(false)}
                                className="text-gray-400 bg-transparent hover:bg-gray-100 hover:text-gray-600 rounded-lg text-sm w-8 h-8 inline-flex justify-center items-center transition-colors"
                            >
                                <i className="fas fa-times w-4 h-4"></i>
                            </button>
                        </div>

                        {/* Modal body */}
                        <form onSubmit={handleUpdateSubmit} className="p-4">
                            <div className="grid gap-4 mb-4 grid-cols-2">
                                <div className="col-span-2">
                                    <label htmlFor="update-name" className="block mb-2 text-sm font-medium text-gray-700">
                                        Category Name
                                    </label>
                                    <input
                                        type="text"
                                        name="name"
                                        id="update-name"
                                        value={updateFormData.name}
                                        onChange={handleUpdateInputChange}
                                        className="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-500 focus:border-primary-500 block w-full p-3"
                                        placeholder="Enter category name"
                                        required
                                    />
                                    {errors?.name && (
                                        <p className="mt-2 text-sm text-red-600">{errors.name}</p>
                                    )}
                                </div>
                                <div className="col-span-2">
                                    <label htmlFor="update-category-image" className="block mb-2 text-sm font-medium text-gray-700">
                                        Category Image
                                    </label>
                                    <input
                                        type="file"
                                        id="update-category-image"
                                        onChange={handleUpdateImageChange}
                                        accept="image/*"
                                        className="block w-full text-sm text-gray-900 border border-gray-300 rounded-lg cursor-pointer bg-gray-50 file:bg-primary-600 file:text-white file:border-0 file:py-2 file:px-4 file:rounded-l-lg file:hover:bg-primary-700"
                                    />
                                    <p className="mt-1 text-sm text-gray-500">
                                        Upload a category image (PNG, JPG, JPEG, or GIF, max 1MB).
                                    </p>
                                    {selectedCategory.image && (
                                        <small>
                                            <a
                                                href={`/storage/${selectedCategory.image}`}
                                                target="_blank"
                                                rel="noopener noreferrer"
                                                className="font-medium text-primary-600 hover:text-primary-700"
                                            >
                                                See current image
                                            </a>
                                        </small>
                                    )}
                                    {errors?.category_image && (
                                        <p className="mt-2 text-sm text-red-600">{errors.category_image}</p>
                                    )}
                                </div>
                                <div className="col-span-2">
                                    <label htmlFor="update-description" className="block mb-2 text-sm font-medium text-gray-700">
                                        Category Description
                                    </label>
                                    <textarea
                                        id="update-description"
                                        name="description"
                                        rows={4}
                                        value={updateFormData.description}
                                        onChange={handleUpdateInputChange}
                                        className="block p-3 w-full text-sm text-gray-900 bg-gray-50 rounded-lg border border-gray-300 focus:ring-primary-500 resize-none focus:border-primary-500"
                                        placeholder="Enter category description..."
                                    />
                                    {errors?.description && (
                                        <p className="mt-2 text-sm text-red-600">{errors.description}</p>
                                    )}
                                </div>
                            </div>
                            <button
                                type="submit"
                                disabled={isUpdating}
                                className="text-white inline-flex items-center bg-primary-600 hover:bg-primary-700 focus:ring-4 focus:outline-none focus:ring-primary-200 font-medium rounded-lg text-sm px-6 py-3 text-center disabled:opacity-50 disabled:cursor-not-allowed"
                            >
                                <i className="fas fa-edit w-4 h-4 mr-2"></i>
                                {isUpdating ? 'Updating...' : 'Update Category'}
                            </button>
                        </form>
                    </div>
                </div>
            )}

            {/* Delete Category Modal */}
            {showDeleteModal && categoryToDelete && (
                <div className="fixed inset-0 z-50 flex items-center justify-center bg-black/30 backdrop-blur-sm">
                    <div className="relative bg-white rounded-lg shadow-xl p-4 w-full max-w-md mx-4 border border-gray-200">
                        <button
                            type="button"
                            onClick={() => setShowDeleteModal(false)}
                            className="absolute top-3 right-3 text-gray-400 bg-transparent hover:bg-gray-100 hover:text-gray-600 rounded-lg text-sm w-8 h-8 inline-flex justify-center items-center transition-colors"
                        >
                            <i className="fas fa-times w-4 h-4"></i>
                        </button>
                        <div className="p-4 text-center">
                            <div className="mx-auto mb-4 w-12 h-12 text-red-500 flex items-center justify-center">
                                <i className="fas fa-exclamation-circle w-12 h-12"></i>
                            </div>
                            <h3 className="mb-5 text-md font-medium text-gray-700">
                                Are you sure you want to delete this category? This action cannot be undone.
                            </h3>
                            <form onSubmit={handleDelete}>
                                <button
                                    type="submit"
                                    disabled={isDeleting}
                                    className="text-white bg-red-600 hover:bg-red-700 focus:ring-4 focus:outline-none focus:ring-red-200 font-medium rounded-lg text-sm inline-flex items-center px-6 py-3 text-center disabled:opacity-50 disabled:cursor-not-allowed"
                                >
                                    {isDeleting ? 'Deleting...' : 'Yes, Delete Category'}
                                </button>
                                <button
                                    type="button"
                                    onClick={() => setShowDeleteModal(false)}
                                    className="py-3 px-6 ml-3 text-sm font-medium text-gray-700 focus:outline-none bg-white rounded-lg border border-gray-300 hover:bg-gray-50 hover:text-gray-800"
                                >
                                    Cancel
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            )}
        </DashboardLayout>
    );
}
