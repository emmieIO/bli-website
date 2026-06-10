import { PropsWithChildren, useEffect, useMemo, useRef, useState } from 'react';
import { Link, router, usePage } from '@inertiajs/react';
import { route } from 'ziggy-js';
import axios from 'axios';
import {
    Bell,
    BriefcaseBusiness,
    Calendar,
    CalendarHeart,
    ChevronDown,
    ExternalLink,
    GraduationCap,
    LayoutDashboard,
    LifeBuoy,
    LogOut,
    Menu,
    Mic,
    Receipt,
    Search,
    Send,
    Settings,
    ShieldCheck,
    Star,
    Ticket,
    User,
    UserRound,
    Users,
    X,
} from 'lucide-react';
import { ToastContainer, useToastNotifications } from '@/Components/Toast';
import FlashMessage from '@/Components/FlashMessage';

interface SideLink {
    section?: string;
    title: string;
    icon: string;
    route?: string;
    permission?: string | string[];
    exclude_permission?: string | string[];
    variant?: string;
    children?: Array<{
        title: string;
        route: string;
        permission?: string | string[];
    }>;
}

interface DashboardLayoutProps extends PropsWithChildren {
    sideLinks?: SideLink[];
    user?: {
        name?: string;
        email?: string;
        photo?: string | null;
        permissions?: string[];
    } | null;
}

interface SearchResult {
    id: number;
    slug: string;
    title: string;
    subtitle?: string;
    meta?: string;
    thumbnail?: string;
    type: 'event' | 'speaker';
}

interface SearchResults {
    events: SearchResult[];
    speakers: SearchResult[];
}

interface Notification {
    id: string;
    type: string;
    message: string;
    action_url: string | null;
    read_at: string | null;
    created_at: string;
    data: any;
}

const iconMap: Record<string, any> = {
    'academic-cap': GraduationCap,
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

export default function DashboardLayout({ children, sideLinks: propSideLinks, user: propUser }: DashboardLayoutProps) {
    const page = usePage() as any;
    const { auth, sideLinks } = page.props;
    const user = propUser ?? auth?.user;
    const links: SideLink[] = propSideLinks || sideLinks || [];

    useToastNotifications();

    const [sidebarOpen, setSidebarOpen] = useState(false);
    const [expandedMenus, setExpandedMenus] = useState<string[]>([]);
    const [mobileSearchOpen, setMobileSearchOpen] = useState(false);
    const [searchQuery, setSearchQuery] = useState('');
    const [searchResults, setSearchResults] = useState<SearchResults | null>(null);
    const [isSearching, setIsSearching] = useState(false);
    const [showResults, setShowResults] = useState(false);
    const [showProfileMenu, setShowProfileMenu] = useState(false);
    const [showNotifications, setShowNotifications] = useState(false);
    const [notifications, setNotifications] = useState<Notification[]>([]);
    const [unreadCount, setUnreadCount] = useState(0);

    const searchRef = useRef<HTMLDivElement>(null);
    const profileRef = useRef<HTMLDivElement>(null);
    const notificationsRef = useRef<HTMLDivElement>(null);
    const searchTimeoutRef = useRef<ReturnType<typeof setTimeout>>(undefined);

    const hasPermission = (permission?: string | string[]) => {
        if (!permission) return true;
        if (!user?.permissions) return false;

        const permissions = Array.isArray(permission) ? permission : [permission];
        return permissions.some((item) => user.permissions.includes(item));
    };

    const hasExcludedPermission = (permission?: string | string[]) => {
        if (!permission) return false;
        if (!user?.permissions) return false;

        const permissions = Array.isArray(permission) ? permission : [permission];
        return permissions.some((item) => user.permissions.includes(item));
    };

    const isActive = (link: SideLink) => {
        if (link.route && route().current(link.route)) return true;
        return link.children?.some((child) => route().current(child.route)) ?? false;
    };

    const visibleLinks = useMemo(
        () => links.filter((link) => hasPermission(link.permission) && !hasExcludedPermission(link.exclude_permission)),
        [links, user]
    );

    const groupedLinks = useMemo(() => {
        return visibleLinks.reduce<Record<string, SideLink[]>>((groups, link) => {
            const section = link.section || 'General';
            groups[section] = groups[section] || [];
            groups[section].push(link);
            return groups;
        }, {});
    }, [visibleLinks]);

    const pageTitle = useMemo(() => {
        const child = visibleLinks.flatMap((link) => link.children ?? []).find((item) => route().current(item.route));
        if (child) return child.title;

        return visibleLinks.find((link) => link.route && route().current(link.route))?.title || 'Dashboard';
    }, [visibleLinks]);

    const avatarUrl = user?.photo
        ? `/storage/${user.photo}`
        : `https://ui-avatars.com/api/?name=${encodeURIComponent(user?.name || 'BLI')}&background=002147&color=fff`;

    const fetchNotifications = async () => {
        try {
            const response = await axios.get(route('notifications.index'));
            setNotifications(response.data.notifications || []);
            setUnreadCount(response.data.unread_count || 0);
        } catch {
            setNotifications([]);
            setUnreadCount(0);
        }
    };

    const markAsRead = async (notificationId: string) => {
        try {
            const response = await axios.post(route('notifications.mark-as-read', notificationId));
            setUnreadCount(response.data.unread_count);
            setNotifications((items) => items.map((item) => (
                item.id === notificationId ? { ...item, read_at: new Date().toISOString() } : item
            )));
        } catch {
        }
    };

    const markAllAsRead = async () => {
        try {
            await axios.post(route('notifications.mark-all-as-read'));
            setUnreadCount(0);
            setNotifications((items) => items.map((item) => ({ ...item, read_at: new Date().toISOString() })));
        } catch {
        }
    };

    const clearAllNotifications = async () => {
        try {
            await axios.delete(route('notifications.clear-all'));
            setNotifications([]);
            setUnreadCount(0);
        } catch {
        }
    };

    const handleNotificationClick = (notification: Notification) => {
        if (!notification.read_at) {
            markAsRead(notification.id);
        }

        if (notification.action_url) {
            router.visit(notification.action_url);
        }

        setShowNotifications(false);
    };

    const handleSearch = (query: string) => {
        setSearchQuery(query);

        if (searchTimeoutRef.current) {
            clearTimeout(searchTimeoutRef.current);
        }

        if (query.trim().length < 2) {
            setSearchResults(null);
            setShowResults(false);
            return;
        }

        setIsSearching(true);
        setShowResults(true);
        searchTimeoutRef.current = setTimeout(async () => {
            try {
                const response = await axios.get(route('search'), { params: { q: query } });
                setSearchResults(response.data);
            } catch {
                setSearchResults(null);
            } finally {
                setIsSearching(false);
            }
        }, 250);
    };

    const navigateToResult = (result: SearchResult) => {
        const url = result.type === 'event'
            ? route('events.show', result.slug)
            : route('speakers.show', result.slug);

        router.visit(url);
        setSearchQuery('');
        setShowResults(false);
        setMobileSearchOpen(false);
    };

    const logout = (e: React.FormEvent) => {
        e.preventDefault();
        router.post(route('logout'));
    };

    useEffect(() => {
        fetchNotifications();
    }, []);

    useEffect(() => {
        const activeMenus = links
            .filter((link) => link.children && isActive(link))
            .map((link) => link.title);

        if (activeMenus.length) {
            setExpandedMenus((menus) => Array.from(new Set([...menus, ...activeMenus])));
        }
    }, [links]);

    useEffect(() => {
        const closePopovers = (event: MouseEvent) => {
            const target = event.target as Node;

            if (searchRef.current && !searchRef.current.contains(target)) setShowResults(false);
            if (profileRef.current && !profileRef.current.contains(target)) setShowProfileMenu(false);
            if (notificationsRef.current && !notificationsRef.current.contains(target)) setShowNotifications(false);
        };

        document.addEventListener('mousedown', closePopovers);
        return () => document.removeEventListener('mousedown', closePopovers);
    }, []);

    useEffect(() => {
        return () => {
            if (searchTimeoutRef.current) clearTimeout(searchTimeoutRef.current);
        };
    }, []);

    return (
        <div className="min-h-screen bg-slate-50 text-slate-900">
            <ToastContainer />
            <FlashMessage />

            {sidebarOpen && (
                <button
                    type="button"
                    aria-label="Close navigation"
                    className="fixed inset-0 z-40 bg-primary/25 backdrop-blur-sm lg:hidden"
                    onClick={() => setSidebarOpen(false)}
                />
            )}

            <aside className={`fixed inset-y-0 left-0 z-50 w-64 border-r border-primary-700/50 bg-primary transition-transform duration-200 lg:translate-x-0 ${sidebarOpen ? 'translate-x-0' : '-translate-x-full'}`}>
                <div className="flex h-full flex-col">
                    <div className="flex h-16 items-center justify-between border-b border-primary-600/40 px-5">
                        <Link href={route('user_dashboard')} className="flex items-center gap-2.5">
                            <img src="/assets/img/logo.jpg" alt="BLI" className="h-10 w-10 rounded-md object-cover" />
                            <span className="text-sm font-semibold tracking-tight text-white">Beacon Leadership</span>
                        </Link>
                        <button
                            type="button"
                            className="rounded-md p-1.5 text-primary-300 hover:bg-primary-600 hover:text-white lg:hidden"
                            onClick={() => setSidebarOpen(false)}
                        >
                            <X size={16} />
                        </button>
                    </div>

                    <nav className="flex-1 overflow-y-auto px-3 py-4">
                        <div className="space-y-6">
                            {Object.entries(groupedLinks).map(([section, sectionLinks]) => (
                                <div key={section}>
                                    <p className="px-3 pb-1.5 text-[10px] font-semibold uppercase tracking-[0.2em] text-primary-300/70">
                                        {section}
                                    </p>
                                    <ul className="space-y-0.5">
                                        {sectionLinks.map((link) => (
                                            <NavItem
                                                key={link.title}
                                                link={link}
                                                active={isActive(link)}
                                                expanded={expandedMenus.includes(link.title)}
                                                hasPermission={hasPermission}
                                                onToggle={() => {
                                                    setExpandedMenus((menus) => (
                                                        menus.includes(link.title)
                                                            ? menus.filter((item) => item !== link.title)
                                                            : [...menus, link.title]
                                                    ));
                                                }}
                                                onNavigate={() => setSidebarOpen(false)}
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

            <div className="lg:pl-64">
                <header className="sticky top-0 z-30 border-b border-slate-200 bg-white/80 backdrop-blur-lg">
                    <div className="flex h-16 items-center justify-between gap-4 px-4 sm:px-6 lg:px-8">
                        <div className="flex min-w-0 items-center gap-3">
                            <button
                                type="button"
                                className="rounded-md border border-slate-200 p-2 text-slate-500 hover:bg-slate-50 hover:text-slate-700 lg:hidden"
                                onClick={() => setSidebarOpen(true)}
                            >
                                <Menu size={18} />
                            </button>
                            <div className="min-w-0">
                                <h1 className="truncate text-base font-semibold tracking-tight text-slate-900">{pageTitle}</h1>
                            </div>
                        </div>

                        <div className="hidden max-w-md flex-1 lg:block" ref={searchRef}>
                            <SearchBox
                                value={searchQuery}
                                onChange={handleSearch}
                                isSearching={isSearching}
                                showResults={showResults}
                                results={searchResults}
                                onSelect={navigateToResult}
                            />
                        </div>

                        <div className="flex items-center gap-1.5">
                            <button
                                type="button"
                                className="rounded-md border border-slate-200 p-2 text-slate-500 hover:bg-slate-50 hover:text-slate-700 lg:hidden"
                                onClick={() => setMobileSearchOpen((open) => !open)}
                            >
                                <Search size={18} />
                            </button>

                            <Link
                                href={route('homepage')}
                                className="hidden items-center gap-1.5 rounded-md border border-slate-200 px-3 py-2 text-xs font-medium text-slate-600 transition hover:border-primary-200 hover:bg-primary-50 hover:text-primary md:inline-flex"
                            >
                                <ExternalLink size={14} />
                                Website
                            </Link>

                            <div className="relative" ref={notificationsRef}>
                                <button
                                    type="button"
                                    className="relative rounded-md border border-slate-200 p-2 text-slate-500 transition hover:bg-slate-50 hover:text-slate-700"
                                    onClick={() => {
                                        setShowNotifications((open) => !open);
                                        if (!showNotifications) fetchNotifications();
                                    }}
                                >
                                    <Bell size={18} />
                                    {unreadCount > 0 && (
                                        <span className="absolute -right-1 -top-1 flex h-4.5 min-w-4.5 items-center justify-center rounded-full bg-accent px-1 text-[10px] font-semibold leading-none text-white">
                                            {unreadCount > 9 ? '9+' : unreadCount}
                                        </span>
                                    )}
                                </button>

                                {showNotifications && (
                                    <Popover className="right-0 w-80">
                                        <div className="flex items-center justify-between border-b border-slate-100 px-4 py-3">
                                            <p className="text-sm font-semibold text-slate-900">Notifications</p>
                                            <div className="flex items-center gap-3">
                                                {unreadCount > 0 && (
                                                    <button type="button" className="text-xs font-medium text-slate-500 hover:text-primary" onClick={markAllAsRead}>
                                                        Mark all read
                                                    </button>
                                                )}
                                                {notifications.length > 0 && (
                                                    <button type="button" className="text-xs font-medium text-slate-400 hover:text-accent" onClick={clearAllNotifications}>
                                                        Clear all
                                                    </button>
                                                )}
                                            </div>
                                        </div>
                                        <div className="max-h-80 overflow-y-auto">
                                            {notifications.length === 0 ? (
                                                <EmptyState icon={Bell} title="No notifications" />
                                            ) : notifications.map((notification) => (
                                                <div
                                                    key={notification.id}
                                                    className={`group flex w-full items-start border-b border-slate-50 last:border-b-0 transition hover:bg-slate-50 ${!notification.read_at ? 'bg-primary-50/40' : ''}`}
                                                >
                                                    <button
                                                        type="button"
                                                        className="flex flex-1 gap-2.5 px-4 py-3 text-left"
                                                        onClick={() => handleNotificationClick(notification)}
                                                    >
                                                        <span className={`mt-1 h-1.5 w-1.5 shrink-0 rounded-full ${notification.read_at ? 'bg-slate-200' : 'bg-primary'}`} />
                                                        <span className="min-w-0">
                                                            <span className="line-clamp-2 text-xs text-slate-700">{notification.message}</span>
                                                            <span className="mt-1 block text-[11px] text-slate-400">{notification.created_at}</span>
                                                        </span>
                                                    </button>
                                                    <button
                                                        type="button"
                                                        className="shrink-0 px-3 py-3 text-slate-300 opacity-0 transition hover:text-accent group-hover:opacity-100"
                                                        onClick={(e) => {
                                                            e.stopPropagation();
                                                            markAsRead(notification.id);
                                                            setNotifications((items) => items.filter((item) => item.id !== notification.id));
                                                            try { axios.delete(route('notifications.destroy', notification.id)); } catch {}
                                                        }}
                                                    >
                                                        <X size={13} />
                                                    </button>
                                                </div>
                                            ))}
                                        </div>
                                    </Popover>
                                )}
                            </div>

                            <div className="relative" ref={profileRef}>
                                <button
                                    type="button"
                                    className="flex items-center gap-2 rounded-md border border-slate-200 p-1 pr-2 text-left transition hover:bg-slate-50"
                                    onClick={() => setShowProfileMenu((open) => !open)}
                                >
                                    <img src={avatarUrl} alt={user?.name || 'User'} className="h-7 w-7 rounded-md object-cover" />
                                    <ChevronDown size={14} className="hidden text-slate-400 md:block" />
                                </button>

                                {showProfileMenu && (
                                    <Popover className="right-0 w-56">
                                        <div className="border-b border-slate-100 px-4 py-3">
                                            <p className="truncate text-sm font-semibold text-slate-900">{user?.name}</p>
                                            <p className="truncate text-xs text-slate-500">{user?.email}</p>
                                        </div>
                                        <div className="p-1">
                                            <MenuLink href={route('homepage')} icon={ExternalLink} label="Visit website" />
                                            <MenuLink href={route('profile.edit')} icon={User} label="Profile settings" />
                                            <button
                                                type="button"
                                                className="flex w-full items-center gap-2.5 rounded-md px-3 py-2 text-xs text-slate-600 transition hover:bg-accent-50 hover:text-accent"
                                                onClick={logout}
                                            >
                                                <LogOut size={14} />
                                                Sign out
                                            </button>
                                        </div>
                                    </Popover>
                                )}
                            </div>
                        </div>
                    </div>

                    {mobileSearchOpen && (
                        <div className="border-t border-slate-100 px-4 py-3 lg:hidden" ref={searchRef}>
                            <SearchBox
                                value={searchQuery}
                                onChange={handleSearch}
                                isSearching={isSearching}
                                showResults={showResults}
                                results={searchResults}
                                onSelect={navigateToResult}
                            />
                        </div>
                    )}
                </header>

                <main className="px-4 py-6 sm:px-6 lg:px-8">
                    <div className="mx-auto max-w-7xl">
                        {children}
                    </div>
                </main>
            </div>
        </div>
    );
}

function NavItem({
    link,
    active,
    expanded,
    hasPermission,
    onToggle,
    onNavigate,
}: {
    link: SideLink;
    active: boolean;
    expanded: boolean;
    hasPermission: (permission?: string | string[]) => boolean;
    onToggle: () => void;
    onNavigate: () => void;
}) {
    const Icon = iconMap[link.icon] || LayoutDashboard;
    const baseClass = `flex w-full items-center gap-2.5 rounded-md px-3 py-2 text-sm font-medium transition ${
        active ? 'bg-white/15 text-white' : 'text-primary-200 hover:bg-primary-600/40 hover:text-white'
    }`;

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
                        {link.children.map((child) => {
                            if (!hasPermission(child.permission)) return null;
                            const childActive = route().current(child.route);

                            return (
                                <li key={child.title}>
                                    <Link
                                        href={route(child.route)}
                                        className={`block rounded-md px-3 py-1.5 text-[13px] transition ${
                                            childActive ? 'bg-white/15 font-medium text-white' : 'text-primary-300 hover:bg-primary-600/40 hover:text-white'
                                        }`}
                                        onClick={onNavigate}
                                    >
                                        {child.title}
                                    </Link>
                                </li>
                            );
                        })}
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

function SearchBox({
    value,
    onChange,
    isSearching,
    showResults,
    results,
    onSelect,
}: {
    value: string;
    onChange: (value: string) => void;
    isSearching: boolean;
    showResults: boolean;
    results: SearchResults | null;
    onSelect: (result: SearchResult) => void;
}) {
    return (
        <div className="relative mx-auto w-full max-w-md">
            <Search size={15} className="pointer-events-none absolute left-3 top-1/2 -translate-y-1/2 text-slate-400" />
            <input
                type="search"
                value={value}
                onChange={(event) => onChange(event.target.value)}
                onFocus={() => value.length >= 2 && onChange(value)}
                placeholder="Search events and speakers..."
                className="h-9 w-full rounded-md border border-slate-200 bg-slate-50 pl-9 pr-3 text-sm text-slate-900 placeholder:text-slate-400 outline-none transition focus:border-primary-300 focus:bg-white focus:ring-2 focus:ring-primary-500/10"
            />
            {showResults && (
                <Popover className="left-0 right-0 top-11">
                    {isSearching ? (
                        <div className="px-4 py-6 text-center text-xs text-slate-500">Searching...</div>
                    ) : results ? (
                        <SearchResultsDisplay results={results} onSelect={onSelect} />
                    ) : (
                        <EmptyState icon={Search} title="No results" />
                    )}
                </Popover>
            )}
        </div>
    );
}

function SearchResultsDisplay({ results, onSelect }: { results: SearchResults; onSelect: (result: SearchResult) => void }) {
    const total = results.events.length + results.speakers.length;

    if (total === 0) {
        return <EmptyState icon={Search} title="No results found" />;
    }

    return (
        <div className="py-1">
            <ResultGroup title="Events" items={results.events} icon={Calendar} onSelect={onSelect} />
            <ResultGroup title="Speakers" items={results.speakers} icon={Mic} onSelect={onSelect} />
        </div>
    );
}

function ResultGroup({
    title,
    items,
    icon: Icon,
    onSelect,
}: {
    title: string;
    items: SearchResult[];
    icon: any;
    onSelect: (result: SearchResult) => void;
}) {
    if (!items.length) return null;

    return (
        <div>
            <p className="px-4 py-2 text-[10px] font-semibold uppercase tracking-[0.18em] text-slate-400">{title}</p>
            {items.map((item) => (
                <button
                    key={`${item.type}-${item.id}`}
                    type="button"
                    className="flex w-full items-start gap-3 px-4 py-2.5 text-left transition hover:bg-slate-50"
                    onClick={() => onSelect(item)}
                >
                    <span className="flex h-8 w-8 shrink-0 items-center justify-center rounded-md bg-slate-100 text-slate-500">
                        <Icon size={15} />
                    </span>
                    <span className="min-w-0">
                        <span className="block truncate text-xs font-medium text-slate-900">{item.title}</span>
                        {item.subtitle && <span className="block truncate text-[11px] text-slate-500">{item.subtitle}</span>}
                        {item.meta && <span className="mt-0.5 block truncate text-[11px] text-slate-400">{item.meta}</span>}
                    </span>
                </button>
            ))}
        </div>
    );
}

function Popover({ children, className = '' }: PropsWithChildren<{ className?: string }>) {
    return (
        <div className={`absolute top-full z-50 mt-1.5 overflow-hidden rounded-lg border border-slate-200 bg-white shadow-lg ${className}`}>
            {children}
        </div>
    );
}

function MenuLink({ href, icon: Icon, label }: { href: string; icon: any; label: string }) {
    return (
        <Link href={href} className="flex items-center gap-2.5 rounded-md px-3 py-2 text-xs text-slate-600 transition hover:bg-slate-50 hover:text-slate-900">
            <Icon size={14} />
            {label}
        </Link>
    );
}

function EmptyState({ icon: Icon, title }: { icon: any; title: string }) {
    return (
        <div className="px-4 py-8 text-center">
            <Icon size={24} className="mx-auto text-slate-300" />
            <p className="mt-2 text-xs text-slate-500">{title}</p>
        </div>
    );
}
