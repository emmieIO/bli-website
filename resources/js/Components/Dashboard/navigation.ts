import type { SideLink } from '@/types';
import { route } from 'ziggy-js';

type Permission = string | string[] | undefined;

export function hasPermission(userPermissions: string[] = [], permission?: Permission): boolean {
    if (!permission) return true;

    const requiredPermissions = Array.isArray(permission) ? permission : [permission];
    return requiredPermissions.some((item) => userPermissions.includes(item));
}

export function visibleSideLinks(links: SideLink[], userPermissions: string[] = []): SideLink[] {
    return links.filter((link) => (
        hasPermission(userPermissions, link.permission)
        && !hasExcludedPermission(userPermissions, link.exclude_permission)
    ));
}

export function isActiveSideLink(link: SideLink): boolean {
    if (link.route && route().current(link.route)) return true;
    return link.children?.some((child) => route().current(child.route)) ?? false;
}

export function currentPageTitle(links: SideLink[]): string {
    const child = links.flatMap((link) => link.children ?? []).find((item) => route().current(item.route));
    if (child) return child.title;

    return links.find((link) => link.route && route().current(link.route))?.title ?? 'Dashboard';
}

function hasExcludedPermission(userPermissions: string[], permission?: Permission): boolean {
    // An absent exclusion means "exclude nobody", which is intentionally the
    // inverse default of a normal permission check.
    if (!permission) return false;

    const excludedPermissions = Array.isArray(permission) ? permission : [permission];
    return excludedPermissions.some((item) => userPermissions.includes(item));
}
