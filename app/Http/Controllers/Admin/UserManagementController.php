<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class UserManagementController extends Controller
{
    use AuthorizesRequests;

    public function __construct()
    {
        // Middleware will be handled in routes
    }

    /**
     * Display users management interface
     */
    public function index()
    {
        $users = User::with('roles')->paginate(15);
        $roles = Role::all();

        return \Inertia\Inertia::render('Admin/Users/Index', compact('users', 'roles'));
    }

    /**
     * Assign role to user
     */
    public function assignRole(Request $request, User $user)
    {
        $request->validate([
            'role' => 'required|exists:roles,name',
        ]);

        $user->syncRoles([$request->role]);

        return redirect()->back()->with([
            'message' => "Role '{$request->role}' assigned to {$user->name} successfully",
            'type' => 'success',
        ]);
    }

    /**
     * Remove role from user
     */
    public function removeRole(User $user, $role)
    {
        // Validate that the role exists
        if (! Role::where('name', $role)->exists()) {
            return redirect()->back()->with([
                'message' => "Role '{$role}' does not exist",
                'type' => 'error',
            ]);
        }

        // Validate that the user actually has this role
        if (! $user->hasRole($role)) {
            return redirect()->back()->with([
                'message' => "{$user->name} does not have the '{$role}' role",
                'type' => 'warning',
            ]);
        }

        $user->removeRole($role);

        return redirect()->back()->with([
            'message' => "Role '{$role}' removed from {$user->name} successfully",
            'type' => 'success',
        ]);
    }

    /**
     * Display roles management interface
     */
    public function roles()
    {
        $roles = Role::with('permissions')->get();
        $permissions = Permission::all()->groupBy(function ($permission) {
            return explode('-', $permission->name)[0]; // Group by prefix (course, category, etc.)
        });

        return \Inertia\Inertia::render('Admin/Roles/Index', compact('roles', 'permissions'));
    }

    /**
     * Update role permissions
     */
    public function updateRolePermissions(Request $request, Role $role)
    {
        $request->validate([
            'permissions' => 'array',
            'permissions.*' => 'exists:permissions,name',
        ]);

        $role->syncPermissions($request->permissions ?? []);

        return redirect()->back()->with([
            'message' => "Permissions updated for role '{$role->name}' successfully",
            'type' => 'success',
        ]);
    }

    /**
     * Get user statistics
     */
    public function statistics()
    {
        $stats = [
            'total_users' => User::count(),
            'admins' => User::role('admin')->count(),
            'instructors' => User::role('instructor')->count(),
            'students' => User::role('student')->count(),
            'users_without_roles' => User::doesntHave('roles')->count(),
        ];

        return response()->json($stats);
    }

    /**
     * Toggle permission for a role
     */
    public function togglePermission(Request $request)
    {
        $request->validate([
            'role' => 'required|exists:roles,name',
            'permission' => 'required|exists:permissions,name',
            'action' => 'required|in:add,remove',
        ]);

        $role = Role::findByName($request->role);

        if ($request->action === 'add') {
            $role->givePermissionTo($request->permission);
            $message = "Permission '{$request->permission}' granted to {$role->name} role";
        } else {
            $role->revokePermissionTo($request->permission);
            $message = "Permission '{$request->permission}' removed from {$role->name} role";
        }

        return redirect()->back()->with([
            'message' => $message,
            'type' => 'success',
        ]);
    }

    /**
     * Reset role permissions to defaults
     */
    public function resetToDefaults()
    {
        // Default permission mappings
        $defaults = [
            'admin' => Permission::all()->pluck('name')->toArray(),
            'instructor' => [
                'course-create',
                'course-edit',
                'course-view',
                'course-delete',
                'lesson-create',
                'lesson-edit',
                'lesson-view',
                'lesson-delete',
            ],
            'student' => [
                'course-view',
                'lesson-view',
            ],
        ];

        foreach ($defaults as $roleName => $permissions) {
            $role = Role::findByName($roleName);
            if ($role) {
                $role->syncPermissions($permissions);
            }
        }

        return redirect()->back()->with([
            'message' => 'All roles have been reset to default permissions successfully',
            'type' => 'success',
        ]);
    }

    /**
     * Export role configuration
     */
    public function exportConfiguration()
    {
        $roles = Role::with('permissions')->get();

        $config = $roles->map(function ($role) {
            return [
                'role' => $role->name,
                'permissions' => $role->permissions->pluck('name')->toArray(),
            ];
        });

        $fileName = 'roles-permissions-' . now()->format('Y-m-d-His') . '.json';

        return response()->json($config, 200, [
            'Content-Type' => 'application/json',
            'Content-Disposition' => "attachment; filename=\"{$fileName}\"",
        ]);
    }

    /**
     * View audit log for role/permission changes
     */
    public function auditLog()
    {
        // For now, return a simple message. In production, you'd integrate with
        // spatie/laravel-activitylog or similar package
        return redirect()->back()->with([
            'message' => 'Audit log feature coming soon. Integrate spatie/laravel-activitylog for full audit trail functionality.',
            'type' => 'info',
        ]);
    }
}
