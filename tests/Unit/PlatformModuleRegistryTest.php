<?php

namespace Tests\Unit;

use App\Support\PlatformModuleRegistry;
use Database\Seeders\RoleAndPermissionsSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Route;
use Spatie\Permission\Models\Permission;
use Tests\TestCase;

class PlatformModuleRegistryTest extends TestCase
{
    use RefreshDatabase;

    public function test_baseline_sidebar_modules_are_visible_without_exclusion_rules(): void
    {
        $sideLinks = collect(app(PlatformModuleRegistry::class)->sideLinks(null));

        $this->assertTrue($sideLinks->contains(fn (array $link) => $link['title'] === 'Dashboard'));
        $this->assertTrue($sideLinks->contains(fn (array $link) => $link['title'] === 'Events'));
        $this->assertTrue($sideLinks->contains(fn (array $link) => $link['title'] === 'Support'));
    }

    public function test_active_module_routes_are_registered(): void
    {
        foreach (config('platform_modules.modules') as $module) {
            if (($module['status'] ?? 'active') === 'planned') {
                continue;
            }

            foreach (['route', 'admin_route'] as $routeKey) {
                if (! empty($module[$routeKey])) {
                    $this->assertTrue(
                        Route::has($module[$routeKey]),
                        "Missing route [{$module[$routeKey]}] for module [{$module['key']}]."
                    );
                }
            }

            foreach ($module['children'] ?? [] as $child) {
                $this->assertTrue(
                    Route::has($child['route']),
                    "Missing child route [{$child['route']}] for module [{$module['key']}]."
                );
            }
        }
    }

    public function test_planned_modules_are_visible_on_dashboard_but_not_sidebar(): void
    {
        $registry = app(PlatformModuleRegistry::class);

        $modules = collect($registry->visibleModules(null));
        $sideLinks = collect($registry->sideLinks(null));

        $this->assertTrue($modules->contains(fn (array $module) => $module['key'] === 'lms' && $module['status'] === 'planned'));
        $this->assertFalse($sideLinks->contains(fn (array $link) => $link['title'] === 'LMS'));
    }

    public function test_active_module_permissions_are_seeded(): void
    {
        $this->seed(RoleAndPermissionsSeeder::class);

        $seededPermissions = Permission::query()->pluck('name');
        $modulePermissions = collect(config('platform_modules.modules'))
            ->reject(fn (array $module) => ($module['status'] ?? 'active') === 'planned')
            ->flatMap(function (array $module) {
                return collect([
                    $module['permission'] ?? null,
                    $module['admin_permission'] ?? null,
                    $module['exclude_permission'] ?? null,
                    ...collect($module['children'] ?? [])
                        ->flatMap(fn (array $child) => [
                            $child['permission'] ?? null,
                            $child['exclude_permission'] ?? null,
                        ])
                        ->all(),
                ]);
            })
            ->flatMap(fn ($permission) => (array) $permission)
            ->filter()
            ->unique()
            ->values();

        foreach ($modulePermissions as $permission) {
            $this->assertContains($permission, $seededPermissions, "Permission [{$permission}] is used by the module registry but is not seeded.");
        }
    }
}
