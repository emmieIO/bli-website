<x-app-layout>
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 mb-8">
        <div>
            <h1 class="text-3xl font-bold text-slate-800 mb-2">Role & Permission Management</h1>
            <p class="text-slate-600">Configure roles and their associated permissions to control system access.</p>
        </div>
        <div class="flex items-center gap-3">
            <a href="{{ route('admin.users.index') }}"
                class="inline-flex items-center gap-2 bg-slate-600 hover:bg-slate-700 py-3 px-6 rounded-lg font-medium text-white transition-all duration-200 shadow-sm">
                <i data-lucide="users" class="w-4 h-4"></i>
                <span>Manage Users</span>
            </a>
        </div>
    </div>

    <!-- Roles Overview Cards -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
        @foreach($roles as $role)
            @php
                $roleColors = [
                    'admin' => ['bg' => 'bg-red-50', 'icon' => 'bg-red-100', 'text' => 'text-red-800', 'iconColor' => 'text-red-600'],
                    'instructor' => ['bg' => 'bg-green-50', 'icon' => 'bg-green-100', 'text' => 'text-green-800', 'iconColor' => 'text-green-600'],
                    'student' => ['bg' => 'bg-blue-50', 'icon' => 'bg-blue-100', 'text' => 'text-blue-800', 'iconColor' => 'text-blue-600'],
                ];
                $colors = $roleColors[$role->name] ?? ['bg' => 'bg-slate-50', 'icon' => 'bg-slate-100', 'text' => 'text-slate-800', 'iconColor' => 'text-slate-600'];
            @endphp
            <div class="bg-white rounded-xl border border-slate-200 shadow-sm {{ $colors['bg'] }}">
                <div class="p-6">
                    <div class="flex items-center justify-between mb-4">
                        <div class="flex items-center">
                            <div class="p-2 {{ $colors['icon'] }} rounded-lg">
                                <i data-lucide="shield" class="w-6 h-6 {{ $colors['iconColor'] }}"></i>
                            </div>
                            <div class="ml-3">
                                <h3 class="text-lg font-semibold {{ $colors['text'] }}">{{ ucfirst($role->name) }} Role</h3>
                                <p class="text-sm text-slate-600">{{ $role->permissions->count() }} permissions</p>
                            </div>
                        </div>
                        <button onclick="editRole('{{ $role->name }}')" class="text-slate-400 hover:text-slate-600">
                            <i data-lucide="edit-3" class="w-4 h-4"></i>
                        </button>
                    </div>
                    <div class="space-y-2">
                        <h4 class="text-sm font-medium text-slate-700">Key Permissions:</h4>
                        <div class="flex flex-wrap gap-1">
                            @foreach($role->permissions->take(4) as $permission)
                                <span
                                    class="inline-flex items-center px-2 py-1 rounded text-xs font-medium bg-white text-slate-600">
                                    {{ str_replace('-', ' ', $permission->name) }}
                                </span>
                            @endforeach
                            @if($role->permissions->count() > 4)
                                <span
                                    class="inline-flex items-center px-2 py-1 rounded text-xs font-medium bg-slate-200 text-slate-600">
                                    +{{ $role->permissions->count() - 4 }} more
                                </span>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        @endforeach
    </div>

    <!-- Detailed Permission Management -->
    <div class="bg-white rounded-xl border border-slate-200 shadow-lg">
        <div class="p-6 border-b border-slate-200">
            <h2 class="text-xl font-semibold text-slate-900">Permission Matrix</h2>
            <p class="text-slate-600 mt-1">Manage detailed permissions for each role</p>
        </div>

        <div class="p-6">
            @foreach($permissions as $group => $groupPermissions)
                <div class="mb-8 last:mb-0">
                    <h3 class="text-lg font-semibold text-slate-800 mb-4 capitalize">
                        {{ str_replace(['_', '-'], ' ', $group) }} Permissions
                    </h3>

                    <div class="overflow-x-auto">
                        <table class="w-full text-sm">
                            <thead>
                                <tr class="border-b border-slate-200">
                                    <th class="text-left py-3 px-4 font-medium text-slate-600 w-1/3">Permission</th>
                                    @foreach($roles as $role)
                                        <th class="text-center py-3 px-4 font-medium text-slate-600">
                                            {{ ucfirst($role->name) }}
                                        </th>
                                    @endforeach
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($groupPermissions as $permission)
                                    <tr class="border-b border-slate-100 hover:bg-slate-50">
                                        <td class="py-3 px-4">
                                            <div>
                                                <span class="font-medium text-slate-900">
                                                    {{ str_replace(['_', '-'], ' ', Str::title($permission->name)) }}
                                                </span>
                                                <p class="text-xs text-slate-500 mt-1">{{ $permission->name }}</p>
                                            </div>
                                        </td>
                                        @foreach($roles as $role)
                                            <td class="py-3 px-4 text-center">
                                                <form method="POST" action="{{ route('admin.roles.toggle-permission') }}"
                                                    class="inline">
                                                    @csrf
                                                    <input type="hidden" name="role" value="{{ $role->name }}">
                                                    <input type="hidden" name="permission" value="{{ $permission->name }}">
                                                    <input type="hidden" name="action"
                                                        value="{{ $role->hasPermissionTo($permission->name) ? 'remove' : 'add' }}">
                                                    <button type="submit"
                                                        class="w-6 h-6 rounded {{ $role->hasPermissionTo($permission->name) ? 'bg-green-500 text-white' : 'bg-slate-200 text-slate-400' }} hover:scale-110 transition-all duration-200"
                                                        title="{{ $role->hasPermissionTo($permission->name) ? 'Remove permission' : 'Grant permission' }}">
                                                        <i data-lucide="{{ $role->hasPermissionTo($permission->name) ? 'check' : 'x' }}"
                                                            class="w-4 h-4"></i>
                                                    </button>
                                                </form>
                                            </td>
                                        @endforeach
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            @endforeach
        </div>
    </div>

    <!-- Quick Actions -->
    <div class="mt-8 bg-slate-50 rounded-xl p-6 border border-slate-200">
        <h3 class="text-lg font-semibold text-slate-800 mb-4">Quick Actions</h3>
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
            <button onclick="resetToDefaults()"
                class="flex items-center justify-center gap-2 px-4 py-3 bg-yellow-500 hover:bg-yellow-600 text-white rounded-lg transition-colors">
                <i data-lucide="rotate-ccw" class="w-4 h-4"></i>
                Reset to Defaults
            </button>
            <button onclick="exportPermissions()"
                class="flex items-center justify-center gap-2 px-4 py-3 bg-blue-500 hover:bg-blue-600 text-white rounded-lg transition-colors">
                <i data-lucide="download" class="w-4 h-4"></i>
                Export Configuration
            </button>
            <button onclick="showAuditLog()"
                class="flex items-center justify-center gap-2 px-4 py-3 bg-purple-500 hover:bg-purple-600 text-white rounded-lg transition-colors">
                <i data-lucide="clock" class="w-4 h-4"></i>
                View Audit Log
            </button>
        </div>
    </div>

    <script>
        function editRole(roleName) {
            alert(`Edit role: ${roleName}\n\nThis would open a detailed role editor modal.`);
        }

        function resetToDefaults() {
            if (confirm('This will reset all role permissions to their default configuration. Are you sure?')) {
                // Implementation would go here
                alert('This would reset permissions to defaults');
            }
        }

        function exportPermissions() {
            // This would generate and download a JSON/CSV file of current permissions
            alert('This would export the current permission configuration');
        }

        function showAuditLog() {
            // This would show a modal or redirect to audit log page
            alert('This would show recent permission changes');
        }

        // Add confirmation for permission changes
        document.querySelectorAll('form').forEach(form => {
            form.addEventListener('submit', function (e) {
                const action = form.querySelector('input[name="action"]').value;
                const role = form.querySelector('input[name="role"]').value;
                const permission = form.querySelector('input[name="permission"]').value;

                if (!confirm(`${action === 'add' ? 'Grant' : 'Remove'} "${permission}" permission ${action === 'add' ? 'to' : 'from'} ${role} role?`)) {
                    e.preventDefault();
                }
            });
        });

    </script>
</x-app-layout>