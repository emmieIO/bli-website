<!DOCTYPE html>
<html lang="en" x-data="{ sidebarOpen: false }" class="h-full">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Beacon Leadership Institute - Dashboard</title>
    <script src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js" defer></script>
    <script src="https://unpkg.com/lucide@latest"></script>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
</head>

<body class="h-full bg-gray-50 text-gray-800">
    <div class="flex h-screen overflow-hidden">
        <!-- Sidebar -->
        <div x-show="sidebarOpen" @click.away="sidebarOpen = false" class="fixed inset-0 flex z-40 md:hidden">
            <div class="fixed inset-0 bg-black bg-opacity-25"></div>
            <div class="relative flex-1 flex flex-col max-w-xs w-full bg-white">
                <div class="absolute top-0 right-0 -mr-12 pt-2">
                    <button @click="sidebarOpen = false"
                        class="ml-1 flex items-center justify-center h-10 w-10 rounded-full focus:outline-none">
                        <i data-lucide="x" class="w-6 h-6 text-gray-600"></i>
                    </button>
                </div>
                <div class="p-6">
                    <h2 class="text-2xl font-bold text-teal-600">Beacon</h2>
                </div>
                <nav class="flex-1 px-4 space-y-2">
                    <a href="#" class="flex items-center px-4 py-2 text-gray-700 hover:bg-teal-50 rounded transition">
                        <i data-lucide="layout-dashboard" class="w-5 h-5 mr-3 text-teal-600"></i> Dashboard
                    </a>
                    <a href="#" class="flex items-center px-4 py-2 text-gray-700 hover:bg-teal-50 rounded transition">
                        <i data-lucide="users" class="w-5 h-5 mr-3 text-teal-600"></i> Users
                    </a>
                    <a href="#" class="flex items-center px-4 py-2 text-gray-700 hover:bg-teal-50 rounded transition">
                        <i data-lucide="book" class="w-5 h-5 mr-3 text-teal-600"></i> Courses
                    </a>
                    <a href="#" class="flex items-center px-4 py-2 text-gray-700 hover:bg-teal-50 rounded transition">
                        <i data-lucide="settings" class="w-5 h-5 mr-3 text-teal-600"></i> Settings
                    </a>
                </nav>
            </div>
        </div>

        <!-- Static sidebar for desktop -->
        <div class="hidden md:flex md:flex-shrink-0 bg-white border-r border-gray-200 w-64">
            <div class="flex flex-col w-full">
                <div class="p-6">
                    <h2 class="text-2xl font-bold text-teal-600">Beacon</h2>
                </div>
                <nav class="flex-1 px-4 space-y-2">
                    <a href="#" class="flex items-center px-4 py-2 text-gray-700 hover:bg-teal-50 rounded transition">
                        <i data-lucide="layout-dashboard" class="w-5 h-5 mr-3 text-teal-600"></i> Dashboard
                    </a>
                    <a href="#" class="flex items-center px-4 py-2 text-gray-700 hover:bg-teal-50 rounded transition">
                        <i data-lucide="users" class="w-5 h-5 mr-3 text-teal-600"></i> Users
                    </a>
                    <a href="#" class="flex items-center px-4 py-2 text-gray-700 hover:bg-teal-50 rounded transition">
                        <i data-lucide="book" class="w-5 h-5 mr-3 text-teal-600"></i> Courses
                    </a>
                    <a href="#" class="flex items-center px-4 py-2 text-gray-700 hover:bg-teal-50 rounded transition">
                        <i data-lucide="settings" class="w-5 h-5 mr-3 text-teal-600"></i> Settings
                    </a>
                </nav>
            </div>
        </div>

        <!-- Main content -->
        <div class="flex flex-col flex-1 w-0 overflow-hidden">
            <!-- Top bar -->
            <div class="relative z-10 flex-shrink-0 flex h-16 bg-white shadow">
                <button @click="sidebarOpen = true"
                    class="px-4 border-r border-gray-200 text-gray-500 focus:outline-none md:hidden">
                    <i data-lucide="menu" class="w-6 h-6"></i>
                </button>
                <div class="flex-1 px-4 flex justify-between">
                    <div class="flex-1 flex">
                        <input type="text" placeholder="Search..."
                            class="w-full max-w-md px-4 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-teal-500">
                    </div>
                    <div class="ml-4 flex items-center gap-4">
                        <button class="text-gray-500 hover:text-teal-600 focus:outline-none">
                            <i data-lucide="bell" class="w-5 h-5"></i>
                        </button>
                        <div class="relative">
                            <img class="h-8 w-8 rounded-full object-cover" src="https://i.pravatar.cc/100?img=12"
                                alt="User avatar">
                        </div>
                    </div>
                </div>
            </div>

            <!-- Main content area -->
            <main class="flex-1 relative overflow-y-auto focus:outline-none p-6">
                <h1 class="text-3xl font-semibold text-gray-900 mb-6">Welcome to Beacon Dashboard</h1>
                <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-6">
                    <div class="bg-white p-5 rounded-lg shadow border border-gray-100">
                        <div class="flex items-center justify-between">
                            <div class="text-sm font-medium text-gray-600">Total Students</div>
                            <i data-lucide="user" class="w-6 h-6 text-teal-500"></i>
                        </div>
                        <div class="mt-2 text-2xl font-bold text-gray-800">1,204</div>
                    </div>
                    <div class="bg-white p-5 rounded-lg shadow border border-gray-100">
                        <div class="flex items-center justify-between">
                            <div class="text-sm font-medium text-gray-600">Active Courses</div>
                            <i data-lucide="book-open" class="w-6 h-6 text-teal-500"></i>
                        </div>
                        <div class="mt-2 text-2xl font-bold text-gray-800">28</div>
                    </div>
                    <div class="bg-white p-5 rounded-lg shadow border border-gray-100">
                        <div class="flex items-center justify-between">
                            <div class="text-sm font-medium text-gray-600">Upcoming Sessions</div>
                            <i data-lucide="calendar" class="w-6 h-6 text-teal-500"></i>
                        </div>
                        <div class="mt-2 text-2xl font-bold text-gray-800">5</div>
                    </div>
                </div>
            </main>
        </div>
    </div>

    <script>
        window.addEventListener('DOMContentLoaded', () => {
            lucide.createIcons();
        });
    </script>
</body>

</html>
