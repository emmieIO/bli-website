import { Link } from '@inertiajs/react';
import {
    BookOpen, BriefcaseBusiness, Calendar, CalendarHeart, ChevronDown,
    GraduationCap, LayoutDashboard, LifeBuoy, Mic, Receipt, Send, Settings,
    ShieldCheck, Star, Ticket, UserRound, Users, X,
} from 'lucide-react';
import type { LucideIcon } from 'lucide-react';
import { useEffect, useMemo, useState } from 'react';
import { route } from 'ziggy-js';
import type { SideLink } from '@/types';
import type { DashboardShellProps } from './types';
import { hasPermission, isActiveSideLink, visibleSideLinks } from './navigation';

const iconMap: Record<string, LucideIcon> = {
    'academic-cap': GraduationCap,
    'book-open': BookOpen,
    'blog': BriefcaseBusiness,
    'calendar': Calendar,
    'calendar-heart': CalendarHeart,
    'chart-area': LayoutDashboard,
    'life-buoy': LifeBuoy,
    'mic': Mic,
    'receipt': Receipt,
    'send': Send,
    'settings': Settings,
    'star': Star,
    'ticket': Ticket,
    'user-group': UserRound,
    'users': Users,
    'users-cog': ShieldCheck,
};

interface DashboardSidebarProps extends DashboardShellProps {
    open: boolean;
    onClose: () => void;
}

export default function DashboardSidebar({ sideLinks, user, open, onClose }: DashboardSidebarProps) {
    const permissions = user?.permissions ?? [];
    const links = useMemo(() => visibleSideLinks(sideLinks, permissions), [sideLinks, permissions]);
    const [expandedMenus, setExpandedMenus] = useState<string[]>([]);

    const groupedLinks = useMemo(() => links.reduce<Record<string, SideLink[]>>((groups, link) => {
        const section = link.section || 'General';
        groups[section] = groups[section] || [];
        groups[section].push(link);
        return groups;
    }, {}), [links]);

    useEffect(() => {
        // Keep the current child visible after direct navigation or a refresh.
        const activeMenus = links
            .filter((link) => link.children && isActiveSideLink(link))
            .map((link) => link.title);

        setExpandedMenus((menus) => Array.from(new Set([...menus, ...activeMenus])));
    }, [links]);

    const avatarUrl = user?.photo
        ? `/storage/${user.photo}`
        : `https://ui-avatars.com/api/?name=${encodeURIComponent(user?.name || 'BLI')}&background=002147&color=fff`;

    return (
        <>
            {open && (
                <button type="button" aria-label="Close navigation" className="fixed inset-0 z-40 bg-primary/25 backdrop-blur-sm lg:hidden" onClick={onClose} />
            )}

            <aside className={`fixed inset-y-0 left-0 z-50 w-64 border-r border-primary-700/50 bg-primary transition-transform duration-200 lg:translate-x-0 ${open ? 'translate-x-0' : '-translate-x-full'}`}>
                <div className="flex h-full flex-col">
                    <div className="flex h-16 items-center justify-between border-b border-primary-600/40 px-5">
                        <Link href={route('user_dashboard')} className="flex items-center gap-2.5">
                            <img src="/assets/img/bli-mark.png" alt="Beacon Leadership Institute" className="h-10 w-10 rounded-md bg-white object-contain" />
                            <span className="text-sm font-semibold tracking-tight text-white">Beacon Leadership</span>
                        </Link>
                        <button type="button" aria-label="Close navigation" className="rounded-md p-1.5 text-primary-300 hover:bg-primary-600 hover:text-white lg:hidden" onClick={onClose}>
                            <X size={16} />
                        </button>
                    </div>

                    <nav className="flex-1 overflow-y-auto px-3 py-4">
                        <div className="space-y-6">
                            {Object.entries(groupedLinks).map(([section, sectionLinks]) => (
                                <div key={section}>
                                    <p className="px-3 pb-1.5 text-[10px] font-semibold uppercase tracking-[0.2em] text-primary-300/70">{section}</p>
                                    <ul className="space-y-0.5">
                                        {sectionLinks.map((link) => (
                                            <NavItem
                                                key={link.title}
                                                link={link}
                                                active={isActiveSideLink(link)}
                                                expanded={expandedMenus.includes(link.title)}
                                                permissions={permissions}
                                                onToggle={() => setExpandedMenus((menus) => menus.includes(link.title)
                                                    ? menus.filter((item) => item !== link.title)
                                                    : [...menus, link.title])}
                                                onNavigate={onClose}
                                            />
                                        ))}
                                    </ul>
                                </div>
                            ))}
                        </div>
                    </nav>

                    <div className="border-t border-primary-600/40 p-3">
                        <div className="flex items-center gap-3 rounded-lg bg-primary-600/30 px-3 py-2.5">
                            <img src={avatarUrl} alt="" className="h-8 w-8 rounded-md object-cover ring-1 ring-white/15" />
                            <div className="min-w-0 flex-1">
                                <p className="truncate text-sm font-medium text-white">{user?.name}</p>
                                <p className="truncate text-xs text-primary-300">{user?.email}</p>
                            </div>
                        </div>
                    </div>
                </div>
            </aside>
        </>
    );
}

function NavItem({ link, active, expanded, permissions, onToggle, onNavigate }: {
    link: SideLink;
    active: boolean;
    expanded: boolean;
    permissions: string[];
    onToggle: () => void;
    onNavigate: () => void;
}) {
    const Icon = iconMap[link.icon] || LayoutDashboard;
    const baseClass = `flex w-full items-center gap-2.5 rounded-md px-3 py-2 text-sm font-medium transition ${active ? 'bg-white/15 text-white' : 'text-primary-200 hover:bg-primary-600/40 hover:text-white'}`;

    if (link.children) {
        return (
            <li>
                <button type="button" className={baseClass} onClick={onToggle}>
                    <Icon size={16} className="shrink-0" />
                    <span className="flex-1 text-left text-[13px]">{link.title}</span>
                    <ChevronDown size={14} className={`shrink-0 transition ${expanded ? 'rotate-180' : ''}`} />
                </button>
                {expanded && (
                    <ul className="ml-4 mt-0.5 space-y-0.5 border-l border-primary-600/40 py-0.5 pl-4">
                        {link.children.map((child) => hasPermission(permissions, child.permission) && (
                            <li key={child.title}>
                                <Link href={route(child.route)} className={`block rounded-md px-3 py-1.5 text-[13px] transition ${route().current(child.route) ? 'bg-white/15 font-medium text-white' : 'text-primary-300 hover:bg-primary-600/40 hover:text-white'}`} onClick={onNavigate}>
                                    {child.title}
                                </Link>
                            </li>
                        ))}
                    </ul>
                )}
            </li>
        );
    }

    return (
        <li>
            <Link href={route(link.route!)} className={baseClass} onClick={onNavigate}>
                <Icon size={16} className="shrink-0" />
                <span className="text-[13px]">{link.title}</span>
            </Link>
        </li>
    );
}
