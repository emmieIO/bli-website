<?php

use App\Enums\UserRoles;
use Illuminate\Database\Migrations\Migration;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Spatie\Permission\PermissionRegistrar;

return new class extends Migration
{
    public function up(): void
    {
        app(PermissionRegistrar::class)->forgetCachedPermissions();

        $mentorPermissions = [
            'track-applications',
            'view-own-transaction-history',
            'view-own-invitations',
            'mentorship-manage-assigned',
            'event-view',
            'event-register',
            'event-view-own-registration',
            'event-apply-to-speak',
        ];

        foreach ($mentorPermissions as $permission) {
            Permission::firstOrCreate([
                'name' => $permission,
                'guard_name' => 'web',
            ]);
        }

        $mentor = Role::firstOrCreate([
            'name' => UserRoles::MENTOR->value,
            'guard_name' => 'web',
        ]);
        $mentor->syncPermissions($mentorPermissions);

        $instructor = Role::query()->where('name', UserRoles::INSTRUCTOR->value)->first();
        $legacyPermission = Permission::query()
            ->where('name', 'mentorship-view-instructor')
            ->where('guard_name', 'web')
            ->first();
        if ($instructor && $legacyPermission && $instructor->hasPermissionTo($legacyPermission)) {
            $instructor->revokePermissionTo($legacyPermission);
        }

        $superAdmin = Role::firstOrCreate([
            'name' => UserRoles::SUPER_ADMIN->value,
            'guard_name' => 'web',
        ]);
        $superAdmin->syncPermissions(Permission::query()->pluck('name')->all());

        app(PermissionRegistrar::class)->forgetCachedPermissions();
    }

    public function down(): void
    {
        app(PermissionRegistrar::class)->forgetCachedPermissions();

        Role::query()->where('name', UserRoles::MENTOR->value)->delete();
        Permission::query()->where('name', 'mentorship-manage-assigned')->delete();

        $legacyPermission = Permission::query()->where('name', 'mentorship-view-instructor')->first();
        $instructor = Role::query()->where('name', UserRoles::INSTRUCTOR->value)->first();
        if ($legacyPermission && $instructor) {
            $instructor->givePermissionTo($legacyPermission);
        }

        app(PermissionRegistrar::class)->forgetCachedPermissions();
    }
};
