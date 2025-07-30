<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;

class RoleAndPermissionsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $roles = [
            "student",
            "instructor",
            "admin"
        ];

        $rolesPermissions = [
            'admin' => ['manage events', "create-speaker",'view-speaker', "edit-speaker", 'delete-speaker', 'assign-speaker',"manage-instructor-applications"],
            "instructor" => ["view-speaker", 'assign-speaker'],
            'student' => []
        ];

        // Create permissions
        $allPermissions = collect($rolesPermissions)->flatten()->unique();
        foreach ($allPermissions as $permission) {
            \Spatie\Permission\Models\Permission::firstOrCreate(['name' => $permission]);
        }



        foreach ($rolesPermissions as $role => $permissions) {
            $roleModel = Role::firstOrCreate([
                "name" => $role
            ]);
            if (!empty($permissions)) {
                $roleModel->syncPermissions($permissions);
            }
        }
    }
}
