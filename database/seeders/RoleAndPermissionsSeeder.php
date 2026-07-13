<?php

namespace Database\Seeders;

use App\Enums\Permissions\EventPermissionsEnum;
use App\Enums\UserRoles;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class RoleAndPermissionsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $eventAdminPermissions = [
            EventPermissionsEnum::VIEW_ANY->value,
            EventPermissionsEnum::CREATE->value,
            EventPermissionsEnum::UPDATE_ANY->value,
            EventPermissionsEnum::DELETE_ANY->value,
            EventPermissionsEnum::PUBLISH->value,
            EventPermissionsEnum::MANAGE_ATTENDEES->value,
            EventPermissionsEnum::MANAGE_SPEAKERS->value,
            EventPermissionsEnum::MANAGE_RESOURCES->value,
            EventPermissionsEnum::VIEW_PAYMENTS->value,
            EventPermissionsEnum::SEND_UPDATES->value,
            EventPermissionsEnum::CANCEL->value,
            EventPermissionsEnum::ARCHIVE->value,
        ];

        $eventUserPermissions = [
            EventPermissionsEnum::VIEW->value,
            EventPermissionsEnum::REGISTER->value,
            EventPermissionsEnum::VIEW_OWN_REGISTRATION->value,
            EventPermissionsEnum::APPLY_TO_SPEAK->value,
        ];

        $rolesPermissions = [
            UserRoles::ADMIN->value => [
                'analytics-view-system',
                ...$eventAdminPermissions,
                EventPermissionsEnum::APPLY_TO_SPEAK->value,
                'create-speaker',
                'view-speaker',
                'edit-speaker',
                'delete-speaker',
                'assign-speaker',
                'approve-speaker-applications',
                'manage-instructor-applications',
                'mentorship-manage-any',
                'manage-blog',
                'manage-ratings',
                'create-user',
                'view-user',
                'edit-user',
                'delete-user',
                'view-user-list',
                'assign-role',
                'remove-role',
                'create-role',
                'edit-role',
                'delete-role',
                'view-role-list',
                'assign-permission',
                'remove-permission',
                'view-permission-list',
                'track-applications',
                'manage-activity-log',
                'manage-tickets',
                'view-transaction-audit',
            ],
            UserRoles::STUDENT->value => [
                'track-applications',
                'view-own-transaction-history',
                'view-own-invitations',
                'mentorship-view-own',
                ...$eventUserPermissions,
            ],
            UserRoles::INSTRUCTOR->value => [
                'track-applications',
                'view-own-transaction-history',
                'view-own-invitations',
                ...$eventUserPermissions,
            ],
            UserRoles::SPEAKER->value => [
                'track-applications',
                'view-own-invitations',
                ...$eventUserPermissions,
            ],
            UserRoles::MENTOR->value => [
                'track-applications',
                'view-own-transaction-history',
                'view-own-invitations',
                'mentorship-manage-assigned',
                ...$eventUserPermissions,
            ],
        ];

        $rolesPermissions[UserRoles::SUPER_ADMIN->value] = collect($rolesPermissions)
            ->flatten()
            ->unique()
            ->values()
            ->all();

        // Create permissions
        $allPermissions = collect($rolesPermissions)->flatten()->unique();
        foreach ($allPermissions as $permission) {
            Permission::firstOrCreate([
                'name' => $permission,
                'guard_name' => 'web',
            ]);
        }

        foreach ($rolesPermissions as $role => $permissions) {
            $roleModel = Role::firstOrCreate([
                'name' => $role,
            ]);
            $roleModel->syncPermissions($permissions);
        }
    }
}
