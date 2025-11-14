<x-app-layout>
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 mb-8">
        <div>
            <h1 class="text-3xl font-bold text-slate-800 mb-2">User Management</h1>
            <p class="text-slate-600">Manage user accounts and assign roles to control access permissions.</p>
        </div>
        <div class="flex items-center gap-3">
            <button onclick="showUserStats()"
                class="inline-flex items-center gap-2 bg-slate-600 hover:bg-slate-700 py-3 px-6 rounded-lg font-medium text-white transition-all duration-200 shadow-sm">
                <i data-lucide="bar-chart-3" class="w-4 h-4"></i>
                <span>User Statistics</span>
            </button>
            <a href="{{ route('admin.roles.index') }}"
                class="inline-flex items-center gap-2 bg-primary-600 hover:bg-primary-700 py-3 px-6 rounded-lg font-medium text-white transition-all duration-200 shadow-sm">
                <i data-lucide="shield-check" class="w-4 h-4"></i>
                <span>Manage Roles</span>
            </a>
        </div>
    </div>

    <!-- User Statistics Cards -->
    <div id="user-stats" class="hidden">
        <div class="bg-white rounded-xl p-6 shadow-sm border border-slate-200">
            <div class="flex items-center">
                <div class="p-2 bg-blue-100 rounded-lg">
                    <i data-lucide="users" class="w-6 h-6 text-blue-600"></i>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-slate-600">Total Users</p>
                    <p id="total-users" class="text-2xl font-semibold text-slate-900">-</p>
                </div>
            </div>
        </div>
        <div class="bg-white rounded-xl p-6 shadow-sm border border-slate-200">
            <div class="flex items-center">
                <div class="p-2 bg-red-100 rounded-lg">
                    <i data-lucide="shield" class="w-6 h-6 text-red-600"></i>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-slate-600">Admins</p>
                    <p id="admin-count" class="text-2xl font-semibold text-slate-900">-</p>
                </div>
            </div>
        </div>
        <div class="bg-white rounded-xl p-6 shadow-sm border border-slate-200">
            <div class="flex items-center">
                <div class="p-2 bg-green-100 rounded-lg">
                    <i data-lucide="graduation-cap" class="w-6 h-6 text-green-600"></i>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-slate-600">Instructors</p>
                    <p id="instructor-count" class="text-2xl font-semibold text-slate-900">-</p>
                </div>
            </div>
        </div>
        <div class="bg-white rounded-xl p-6 shadow-sm border border-slate-200">
            <div class="flex items-center">
                <div class="p-2 bg-purple-100 rounded-lg">
                    <i data-lucide="book-open" class="w-6 h-6 text-purple-600"></i>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-slate-600">Students</p>
                    <p id="student-count" class="text-2xl font-semibold text-slate-900">-</p>
                </div>
            </div>
        </div>
        <div class="bg-white rounded-xl p-6 shadow-sm border border-slate-200">
            <div class="flex items-center">
                <div class="p-2 bg-yellow-100 rounded-lg">
                    <i data-lucide="user-x" class="w-6 h-6 text-yellow-600"></i>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-slate-600">No Role</p>
                    <p id="no-role-count" class="text-2xl font-semibold text-slate-900">-</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Users Table -->
    <div class="relative overflow-x-auto shadow-lg rounded-xl bg-white border border-slate-200">
        <table class="w-full text-sm text-left rtl:text-right text-slate-600">
            <thead class="text-xs text-slate-600 uppercase bg-slate-50 border-b border-slate-200">
                <tr>
                    <th scope="col" class="px-6 py-4 font-semibold">User Details</th>
                    <th scope="col" class="px-6 py-4 font-semibold">Current Role</th>
                    <th scope="col" class="px-6 py-4 font-semibold">Registration Date</th>
                    <th scope="col" class="px-6 py-4 font-semibold">Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($users as $user)
                    <tr class="bg-white border-b border-slate-100 hover:bg-slate-50 transition-colors duration-150">
                        <td class="px-6 py-4">
                            <div class="flex items-center gap-3">
                                <div class="w-10 h-10 bg-primary-100 rounded-full flex items-center justify-center">
                                    <span class="text-sm font-semibold text-primary-700">
                                        {{ substr($user->name, 0, 1) }}
                                    </span>
                                </div>
                                <div>
                                    <h3 class="font-semibold text-slate-900">{{ $user->name }}</h3>
                                    <p class="text-sm text-slate-500">{{ $user->email }}</p>
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-4">
                            @if($user->roles->isNotEmpty())
                                @foreach($user->roles as $role)
                                    @php
                                        $roleColors = [
                                            'admin' => 'bg-red-100 text-red-800',
                                            'instructor' => 'bg-green-100 text-green-800',
                                            'student' => 'bg-blue-100 text-blue-800',
                                        ];
                                        $colorClass = $roleColors[$role->name] ?? 'bg-slate-100 text-slate-800';
                                    @endphp
                                    <span
                                        class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $colorClass }}">
                                        {{ ucfirst($role->name) }}
                                    </span>
                                @endforeach
                            @else
                                <span
                                    class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">
                                    No Role
                                </span>
                            @endif
                        </td>
                        <td class="px-6 py-4">
                            <span class="text-slate-700">{{ $user->created_at->format('M d, Y') }}</span>
                        </td>
                        <td class="px-6 py-4">
                            <div class="flex items-center gap-2">
                                <button
                                    onclick="openRoleModal({{ $user->id }}, '{{ $user->name }}', '{{ $user->roles->first()->name ?? '' }}')"
                                    class="inline-flex items-center justify-center w-8 h-8 text-primary-600 hover:text-primary-700 hover:bg-primary-50 rounded-lg transition-colors duration-200"
                                    title="Assign Role">
                                    <i data-lucide="user-cog" class="w-4 h-4"></i>
                                </button>
                                @if($user->roles->isNotEmpty())
                                    <form method="POST"
                                        action="{{ route('admin.users.remove-role', [$user, $user->roles->first()->name]) }}"
                                        class="inline" onsubmit="return confirm('Remove role from this user?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit"
                                            class="inline-flex items-center justify-center w-8 h-8 text-red-600 hover:text-red-700 hover:bg-red-50 rounded-lg transition-colors duration-200"
                                            title="Remove Role">
                                            <i data-lucide="user-minus" class="w-4 h-4"></i>
                                        </button>
                                    </form>
                                @endif
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="4" class="px-6 py-12 text-center text-slate-500">
                            <div class="flex flex-col items-center">
                                <i data-lucide="users" class="w-12 h-12 text-slate-300 mb-4"></i>
                                <p>No users found</p>
                            </div>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <!-- Pagination -->
    @if($users->hasPages())
        <div class="mt-6">
            {{ $users->links() }}
        </div>
    @endif

    <!-- Role Assignment Modal -->
    <div id="role-modal" class="hidden fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full z-50">
        <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white">
            <div class="mt-3">
                <h3 class="text-lg font-semibold text-slate-900 mb-4">Assign Role</h3>
                <form id="role-form" method="POST">
                    @csrf
                    <div class="mb-4">
                        <label class="block text-sm font-medium text-slate-700 mb-2">User</label>
                        <p id="selected-user" class="text-slate-900 font-semibold"></p>
                    </div>
                    <div class="mb-4">
                        <label for="role" class="block text-sm font-medium text-slate-700 mb-2">Role</label>
                        <select name="role" id="role" required
                            class="w-full px-3 py-2 border border-slate-300 rounded-lg focus:ring-primary-500 focus:border-primary-500">
                            <option value="">Select a role</option>
                            @foreach($roles as $role)
                                <option value="{{ $role->name }}">{{ ucfirst($role->name) }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="flex justify-end gap-3">
                        <button type="button" onclick="closeRoleModal()"
                            class="px-4 py-2 text-sm font-medium text-slate-700 bg-slate-100 hover:bg-slate-200 rounded-lg transition-colors">
                            Cancel
                        </button>
                        <button type="submit"
                            class="px-4 py-2 text-sm font-medium text-white bg-primary-600 hover:bg-primary-700 rounded-lg transition-colors">
                            Assign Role
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        async function showUserStats() {
            const statsDiv = document.getElementById('user-stats');

            if (statsDiv.classList.contains('hidden')) {
                try {
                    const response = await fetch('{{ route("admin.users.statistics") }}');
                    const stats = await response.json();

                    document.getElementById('total-users').textContent = stats.total_users;
                    document.getElementById('admin-count').textContent = stats.admins;
                    document.getElementById('instructor-count').textContent = stats.instructors;
                    document.getElementById('student-count').textContent = stats.students;
                    document.getElementById('no-role-count').textContent = stats.users_without_roles;

                    statsDiv.classList.remove('hidden');
                    statsDiv.classList.add('grid', 'grid-cols-1', 'md:grid-cols-5', 'gap-4', 'mb-6');
                } catch (error) {
                    console.error('Failed to load statistics:', error);
                }
            } else {
                statsDiv.classList.add('hidden');
                statsDiv.classList.remove('grid', 'grid-cols-1', 'md:grid-cols-5', 'gap-4', 'mb-6');
            }
        }

        function openRoleModal(userId, userName, currentRole) {
            document.getElementById('selected-user').textContent = userName;
            document.getElementById('role').value = currentRole;
            document.getElementById('role-form').action = `/admin/users/${userId}/assign-role`;
            document.getElementById('role-modal').classList.remove('hidden');
        }

        function closeRoleModal() {
            document.getElementById('role-modal').classList.add('hidden');
        }

        // Close modal when clicking outside
        document.getElementById('role-modal').addEventListener('click', function (e) {
            if (e.target === this) {
                closeRoleModal();
            }
        });

    </script>
</x-app-layout>