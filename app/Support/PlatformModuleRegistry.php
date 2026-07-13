<?php

namespace App\Support;

use App\Models\User;
use Illuminate\Support\Arr;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Route;

class PlatformModuleRegistry
{
    public function visibleModules(?User $user): array
    {
        return $this->modules()
            ->filter(fn (array $module) => $this->isVisible($module, $user))
            ->map(fn (array $module) => $this->normalizeModule($module, $user))
            ->values()
            ->all();
    }

    public function sideLinks(?User $user): array
    {
        return $this->modules()
            ->filter(fn (array $module) => $module['status'] !== 'planned')
            ->filter(fn (array $module) => $this->isVisible($module, $user))
            ->map(fn (array $module) => $this->moduleToSideLink($module, $user))
            ->filter()
            ->values()
            ->all();
    }

    private function modules(): Collection
    {
        return collect(config('platform_modules.modules', []));
    }

    private function normalizeModule(array $module, ?User $user): array
    {
        $entryRoute = $this->firstAvailableRoute([
            $module['route'] ?? null,
            $module['admin_route'] ?? null,
        ]);

        return [
            'key' => $module['key'],
            'title' => $module['title'],
            'section' => $module['section'] ?? 'General',
            'icon' => $module['icon'] ?? 'chart-area',
            'status' => $module['status'] ?? 'active',
            'stage' => $module['stage'] ?? 'Operate',
            'audience' => $module['audience'] ?? 'Everyone',
            'purpose' => $module['purpose'] ?? '',
            'actions' => array_values($module['actions'] ?? []),
            'entry_route' => $entryRoute,
            'entry_url' => $entryRoute ? route($entryRoute) : null,
            'lms_bridge' => $module['lms_bridge'] ?? null,
            'planned_permissions' => array_values($module['planned_permissions'] ?? []),
            'planned_routes' => array_values($module['planned_routes'] ?? []),
            'can_access' => $this->isVisible($module, $user),
        ];
    }

    private function moduleToSideLink(array $module, ?User $user): ?array
    {
        $children = collect($module['children'] ?? [])
            ->filter(fn (array $child) => $this->canShowChild($child, $user))
            ->map(fn (array $child) => Arr::only($child, ['title', 'route', 'permission']))
            ->values()
            ->all();

        if ($children !== []) {
            return [
                'section' => $module['section'] ?? 'General',
                'title' => $module['title'],
                'icon' => $module['icon'] ?? 'chart-area',
                'children' => $children,
            ];
        }

        $route = $this->firstAvailableRoute([$module['route'] ?? null, $module['admin_route'] ?? null]);

        if (! $route) {
            return null;
        }

        return array_filter([
            'section' => $module['section'] ?? 'General',
            'title' => $module['title'],
            'icon' => $module['icon'] ?? 'chart-area',
            'route' => $route,
            'permission' => $module['permission'] ?? null,
            'exclude_permission' => $module['exclude_permission'] ?? null,
            'variant' => $module['variant'] ?? null,
        ], fn ($value) => $value !== null);
    }

    private function canShowChild(array $child, ?User $user): bool
    {
        return isset($child['route'])
            && Route::has($child['route'])
            && $this->hasPermission($user, $child['permission'] ?? null)
            && ! $this->hasExcludedPermission($user, $child['exclude_permission'] ?? null);
    }

    private function isVisible(array $module, ?User $user): bool
    {
        if (($module['status'] ?? 'active') === 'planned') {
            return true;
        }

        $hasRoute = $this->firstAvailableRoute([
            $module['route'] ?? null,
            $module['admin_route'] ?? null,
        ]) !== null || ! empty($module['children']);

        return $hasRoute
            && $this->hasPermission($user, $module['permission'] ?? null)
            && ! $this->hasExcludedPermission($user, $module['exclude_permission'] ?? null);
    }

    private function hasPermission(?User $user, string|array|null $permission): bool
    {
        if (! $permission) {
            return true;
        }

        if (! $user) {
            return false;
        }

        return collect((array) $permission)->contains(fn (string $item) => $user->can($item));
    }

    private function hasExcludedPermission(?User $user, string|array|null $permission): bool
    {
        if (! $permission || ! $user) {
            return false;
        }

        return collect((array) $permission)->contains(fn (string $item) => $user->can($item));
    }

    private function firstAvailableRoute(array $routes): ?string
    {
        foreach ($routes as $route) {
            if ($route && Route::has($route)) {
                return $route;
            }
        }

        return null;
    }
}
