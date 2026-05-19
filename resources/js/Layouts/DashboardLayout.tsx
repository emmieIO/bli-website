import { PropsWithChildren, useState, useEffect, useRef, useMemo, useLayoutEffect } from 'react';
import { Link, router, usePage } from '@inertiajs/react';
import { ToastContainer, useToastNotifications } from '@/Components/Toast';
import FlashMessage from '@/Components/FlashMessage';
import { route } from 'ziggy-js';
import {
    LayoutDashboard,
    BookOpen,
    CalendarHeart,
    Send,
    Calendar,
    Mic,
    GraduationCap,
    Users,
    Settings,
    ChevronDown,
    Menu,
    X,
    Search,
    ExternalLink,
    User,
    LogOut,
    Bell,
    ShoppingCart,
    LifeBuoy,
    Banknote,
    Receipt,
    Ticket,
    Star,
    BriefcaseBusiness,
    UserRound,
    ShieldCheck,
} from 'lucide-react';
import axios from 'axios';

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
    } | null;
}

interface SearchResult {
    id: number;
    slug: string;
    title: string;
    subtitle?: string;
    meta?: string;
    thumbnail?: string;
    type: 'event' | 'course' | 'speaker';
}

interface SearchResults {
    events: SearchResult[];
    courses: SearchResult[];
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

export default function DashboardLayout({ children, sideLinks: propSideLinks, user: propUser }: DashboardLayoutProps) {
    const page = usePage() as any;
    const { auth, sideLinks } = page.props;
    const currentUrl = page.url as string;
    const user = propUser ?? auth?.user;

    // Toast notifications
    useToastNotifications();

    // Fallback to empty array if sideLinks is undefined
    const links: SideLink[] = propSideLinks || sideLinks || [];
    const [isSidebarOpen, setIsSidebarOpen] = useState(false);
    const [expandedMenus, setExpandedMenus] = useState<string[]>([]);
    const [showMobileSearch, setShowMobileSearch] = useState(false);

    // Search state
    const [searchQuery, setSearchQuery] = useState('');
    const [searchResults, setSearchResults] = useState<SearchResults | null>(null);
    const [isSearching, setIsSearching] = useState(false);
    const [showResults, setShowResults] = useState(false);
    const searchRef = useRef<HTMLDivElement>(null);
    const searchTimeoutRef = useRef<NodeJS.Timeout | undefined>(undefined);

    // Profile dropdown state
    const [showProfileDropdown, setShowProfileDropdown] = useState(false);
    const profileDropdownRef = useRef<HTMLDivElement>(null);

    // Notification state
    const [showNotifications, setShowNotifications] = useState(false);
    const [notifications, setNotifications] = useState<Notification[]>([]);
    const [unreadCount, setUnreadCount] = useState(0);
    const notificationRef = useRef<HTMLDivElement>(null);
    const sidebarNavRef = useRef<HTMLElement>(null);

    // Cart state
    const [cartCount, setCartCount] = useState(0);

    const getPageTitle = () => {
        const flattenedChildren = links.flatMap((link) => link.children ?? []);
        const activeChild = flattenedChildren.find((child) => route().current(child.route));

        if (activeChild) {
            return activeChild.title;
        }

        const activeLink = links.find((link) => link.route && route().current(link.route));

        return activeLink?.title || 'Dashboard';
    };

    const toggleMenu = (title: string) => {
        setExpandedMenus(prev =>
            prev.includes(title)
                ? prev.filter(item => item !== title)
                : [...prev, title]
        );
    };

    const hasPermission = (permission?: string | string[]) => {
        if (!permission) return true;
        if (!user?.permissions) return false;

        const permissions = Array.isArray(permission) ? permission : [permission];
        return permissions.some(p => user.permissions.includes(p));
    };

    const hasExcludedPermission = (excludePermission?: string | string[]) => {
        if (!excludePermission) return false;
        if (!user?.permissions) return false;

        const excludePermissions = Array.isArray(excludePermission) ? excludePermission : [excludePermission];
        return excludePermissions.some(p => user.permissions.includes(p));
    };

    const shouldShowLink = (link: SideLink) => {
        // Check if user has required permissions
        if (!hasPermission(link.permission)) return false;

        // Check if user has excluded permissions (should hide if they do)
        if (hasExcludedPermission(link.exclude_permission)) return false;

        return true;
    };

    const isLinkActive = (link: SideLink) => {
        if (link.route && route().current(link.route)) {
            return true;
        }

        return link.children?.some((child) => route().current(child.route)) ?? false;
    };

    const logout = (e: React.FormEvent) => {
        e.preventDefault();
        router.post(route('logout'));
    };

    const getAvatarUrl = () => {
        if (user?.photo) {
            return `/storage/${user.photo}`;
        }
        return `https://ui-avatars.com/api/?name=${encodeURIComponent(user?.name || '')}&background=1f2937&color=fff`;
    };

    // Handle search with debouncing
    const handleSearch = (query: string) => {
        setSearchQuery(query);

        // Clear previous timeout
        if (searchTimeoutRef.current) {
            clearTimeout(searchTimeoutRef.current);
        }

        // Don't search if query is too short
        if (query.trim().length < 2) {
            setSearchResults(null);
            setShowResults(false);
            return;
        }

        setIsSearching(true);
        setShowResults(true);

        // Debounce search by 300ms
        searchTimeoutRef.current = setTimeout(async () => {
            try {
                const response = await axios.get(route('search'), {
                    params: { q: query }
                });
                setSearchResults(response.data);
            } catch (error) {
                // Search failed - show no results
                setSearchResults(null);
            } finally {
                setIsSearching(false);
            }
        }, 300);
    };

    // Navigate to result
    const navigateToResult = (result: SearchResult) => {
        let url = '';
        switch (result.type) {
            case 'event':
                url = route('events.show', result.slug);
                break;
            case 'course':
                url = route('courses.show', result.slug);
                break;
            case 'speaker':
                url = route('speakers.show', result.slug);
                break;
        }
        router.visit(url);
        setShowResults(false);
        setSearchQuery('');
    };

    // Fetch notifications
    const fetchNotifications = async () => {
        try {
            const response = await axios.get(route('notifications.index'));
            setNotifications(response.data.notifications || []);
            setUnreadCount(response.data.unread_count || 0);
        } catch (error) {
            // Failed to fetch notifications - silently fail, notifications are not critical
            setNotifications([]);
            setUnreadCount(0);
        }
    };

    // Mark notification as read
    const markAsRead = async (notificationId: string) => {
        try {
            const response = await axios.post(route('notifications.mark-as-read', notificationId));
            setUnreadCount(response.data.unread_count);
            // Update local state
            setNotifications(prev => prev.map(n =>
                n.id === notificationId ? { ...n, read_at: new Date().toISOString() } : n
            ));
        } catch (error) {
            // Failed to mark as read - silently fail
        }
    };

    // Mark all as read
    const markAllAsRead = async () => {
        try {
            await axios.post(route('notifications.mark-all-as-read'));
            setUnreadCount(0);
            setNotifications(prev => prev.map(n => ({ ...n, read_at: new Date().toISOString() })));
        } catch (error) {
            // Failed to mark all as read - silently fail
        }
    };

    // Handle notification click
    const handleNotificationClick = (notification: Notification) => {
        if (!notification.read_at) {
            markAsRead(notification.id);
        }
        if (notification.action_url) {
            // Use Inertia router for proper navigation
            router.visit(notification.action_url);
        }
        setShowNotifications(false);
    };

    // Fetch cart count
    const fetchCartCount = async () => {
        try {
            const response = await axios.get(route('cart.count'));
            setCartCount(response.data.count || 0);
        } catch (error) {
            // Failed to fetch cart count - silently fail
            setCartCount(0);
        }
    };

    // Fetch notifications and cart count on mount
    useEffect(() => {
        fetchNotifications();
        fetchCartCount();
    }, []);

    // Close dropdowns when clicking outside
    useEffect(() => {
        const handleClickOutside = (event: MouseEvent) => {
            if (searchRef.current && !searchRef.current.contains(event.target as Node)) {
                setShowResults(false);
            }
            if (profileDropdownRef.current && !profileDropdownRef.current.contains(event.target as Node)) {
                setShowProfileDropdown(false);
            }
            if (notificationRef.current && !notificationRef.current.contains(event.target as Node)) {
                setShowNotifications(false);
            }
        };

        document.addEventListener('mousedown', handleClickOutside);
        return () => document.removeEventListener('mousedown', handleClickOutside);
    }, []);

    // Cleanup timeout on unmount
    useEffect(() => {
        return () => {
            if (searchTimeoutRef.current) {
                clearTimeout(searchTimeoutRef.current);
            }
        };
    }, []);

    useEffect(() => {
        const activeMenus = links
            .filter((link) => link.children && isLinkActive(link))
            .map((link) => link.title);

        if (activeMenus.length > 0) {
            setExpandedMenus((prev) => Array.from(new Set([...prev, ...activeMenus])));
        }
    }, [links]);

    useEffect(() => {
        const sidebarNav = sidebarNavRef.current;
        if (!sidebarNav) return;

        const storageKey = 'dashboard-sidebar-scroll-top';
        const handleScroll = () => {
            sessionStorage.setItem(storageKey, String(sidebarNav.scrollTop));
        };

        sidebarNav.addEventListener('scroll', handleScroll, { passive: true });
        return () => {
            sidebarNav.removeEventListener('scroll', handleScroll);
        };
    }, []);

    useLayoutEffect(() => {
        const sidebarNav = sidebarNavRef.current;
        if (!sidebarNav) return;

        const savedScrollTop = sessionStorage.getItem('dashboard-sidebar-scroll-top');
        if (!savedScrollTop) return;

        sidebarNav.scrollTop = Number(savedScrollTop);
    }, [currentUrl]);

    // Map icon names to Lucide components
    const getIcon = (iconName: string) => {
        const iconMap: Record<string, any> = {
            'chart-area': LayoutDashboard,
            'book-open': BookOpen,
            'calendar-heart': CalendarHeart,
            'send': Send,
            'calendar': Calendar,
            'mic': Mic,
            'graduation-cap': GraduationCap,
            'users': Users,
            'settings': Settings,
            'life-buoy': LifeBuoy,
            'banknote': Banknote,
            'wallet': Banknote,
            'receipt': Receipt,
            'ticket': Ticket,
            'star': Star,
            'blog': BriefcaseBusiness,
            'user-group': UserRound,
            'academic-cap': GraduationCap,
            'users-cog': ShieldCheck,
        };
        return iconMap[iconName] || LayoutDashboard;
    };

    const visibleLinks = useMemo(() => links.filter((link) => shouldShowLink(link)), [links, user]);

    const groupedLinks = useMemo(() => {
        return visibleLinks.reduce<Record<string, SideLink[]>>((groups, link) => {
            const section = link.section || 'General';

            if (!groups[section]) {
                groups[section] = [];
            }

            groups[section].push(link);

            return groups;
        }, {});
    }, [visibleLinks]);

    const pageTitle = getPageTitle();

    return (
        <div className="hide-scrollbar min-h-screen overflow-y-auto bg-[linear-gradient(180deg,#f8fafc_0%,#eef4f7_100%)] text-slate-900">
            {/* Toast Notifications */}
            <ToastContainer />
            <FlashMessage />

            {isSidebarOpen && (
                <button
                    type="button"
                    aria-label="Close sidebar overlay"
                    className="fixed inset-0 z-30 bg-slate-950/45 backdrop-blur-[1px] sm:hidden"
                    onClick={() => setIsSidebarOpen(false)}
                />
            )}

            {/* Sidebar */}
            <aside
                className={`fixed inset-y-0 left-0 z-40 w-[17rem] border-r border-slate-200/70 bg-white/92 shadow-[0_20px_60px_rgba(15,23,42,0.08)] backdrop-blur transition-transform ${
                    isSidebarOpen ? 'translate-x-0' : '-translate-x-full'
                } sm:translate-x-0`}
            >
                <div className="flex h-full flex-col overflow-hidden">
                    {/* Logo */}
                    <div className="border-b border-slate-200 px-5 pb-5 pt-6">
                        <div className="flex items-center justify-between">
                            <Link href={route('user_dashboard')} className="group flex items-center gap-3">
                                <img
                                    src="/images/logo.jpg"
                                    className="h-11 w-11 rounded-xl border border-slate-200 object-cover shadow-sm transition-all duration-300 group-hover:shadow-md"
                                    alt="BLI Logo"
                                />
                                <div>
                                    <span className="block text-sm font-semibold tracking-tight text-slate-900">BLI Workspace</span>
                                <span className="block text-xs text-slate-500">Clean daily operations</span>
                                </div>
                            </Link>
                            <button
                                onClick={() => setIsSidebarOpen(false)}
                            className="inline-flex items-center rounded-lg p-2 text-slate-500 transition hover:bg-slate-100 hover:text-slate-900 sm:hidden"
                            >
                                <X size={18} />
                            </button>
                        </div>
                    </div>

                    {/* Navigation */}
                    <nav ref={sidebarNavRef} className="hide-scrollbar flex-1 overflow-y-auto px-4 py-5">
                        <div className="space-y-6">
                            {Object.entries(groupedLinks).map(([section, sectionLinks]) => (
                                <div key={section}>
                                    <p className="px-2 text-[11px] font-semibold uppercase tracking-[0.18em] text-slate-400">
                                        {section}
                                    </p>
                                    <ul className="mt-2 space-y-1.5">
                                        {sectionLinks.map((link: SideLink) => {
                                            const isExpanded = expandedMenus.includes(link.title);
                                            const isActive = isLinkActive(link);
                                            const IconComponent = getIcon(link.icon);

                                            if (link.children) {
                                                return (
                                                    <li key={link.title}>
                                                        <button
                                                            onClick={() => toggleMenu(link.title)}
                                                        className={`group flex w-full items-center gap-3 rounded-xl px-3 py-3 text-sm transition-all ${
                                                                isActive
                                                                    ? 'bg-slate-900 text-white shadow-sm'
                                                                    : 'text-slate-600 hover:bg-slate-100 hover:text-slate-900'
                                                            }`}
                                                        >
                                                            <span className={`flex h-9 w-9 shrink-0 items-center justify-center rounded-lg ${
                                                                isActive ? 'bg-white/12 text-white' : 'bg-slate-100 text-slate-500 group-hover:bg-white'
                                                            }`}>
                                                                <IconComponent size={18} />
                                                            </span>
                                                            <span className="flex-1 text-left font-medium">{link.title}</span>
                                                            <ChevronDown size={16} className={`transition-transform ${isExpanded ? 'rotate-180' : ''}`} />
                                                        </button>

                                                        {isExpanded && (
                                                            <ul className="ml-6 mt-2 space-y-1 border-l border-slate-200 pl-4">
                                                                {link.children.map((child: { title: string; route: string; permission?: string | string[] }) => {
                                                                    if (!hasPermission(child.permission)) return null;

                                                                    const childActive = route().current(child.route);

                                                                    return (
                                                                        <li key={child.title}>
                                                                            <Link
                                                                                href={route(child.route)}
                                                                            className={`flex items-center gap-3 rounded-lg px-3 py-2.5 text-sm transition ${
                                                                                    childActive
                                                                                        ? 'bg-slate-200 font-medium text-slate-900'
                                                                                        : 'text-slate-500 hover:bg-slate-50 hover:text-slate-800'
                                                                                }`}
                                                                            >
                                                                                <span className={`h-1.5 w-1.5 rounded-full ${childActive ? 'bg-slate-700' : 'bg-slate-300'}`}></span>
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
                                                <li key={link.title}>
                                                    <Link
                                                        href={route(link.route!)}
                                                        className={`group flex items-center gap-3 rounded-xl px-3 py-3 text-sm transition-all ${
                                                            isActive
                                                                ? 'bg-slate-900 text-white shadow-sm'
                                                                : 'text-slate-600 hover:bg-slate-100 hover:text-slate-900'
                                                        }`}
                                                    >
                                                        <span className={`flex h-9 w-9 shrink-0 items-center justify-center rounded-lg ${
                                                            isActive ? 'bg-white/12 text-white' : 'bg-slate-100 text-slate-500 group-hover:bg-white'
                                                        }`}>
                                                            <IconComponent size={18} />
                                                        </span>
                                                        <span className="flex-1 font-medium">{link.title}</span>
                                                    </Link>
                                                </li>
                                            );
                                        })}
                                    </ul>
                                </div>
                            ))}
                        </div>
                    </nav>

                    <div className="border-t border-slate-200 px-5 py-4">
                        <div className="rounded-lg bg-slate-100 px-4 py-3">
                            <p className="text-sm font-semibold text-slate-900">{user?.name}</p>
                            <p className="mt-1 truncate text-xs text-slate-500">{user?.email}</p>
                        </div>
                    </div>
                </div>
            </aside>

            {/* Main Content Area */}
            <div className="sm:ml-[17rem]">
                {/* Top Navbar */}
                <nav className="sticky top-0 z-30 border-b border-slate-200/80 bg-white/85 backdrop-blur">
                    <div className="px-4 py-3 lg:px-6 xl:px-8">
                        <div className="flex items-center justify-between">
                            {/* Left: Mobile menu + Breadcrumb */}
                            <div className="flex items-center gap-4">
                                <button
                                    onClick={() => setIsSidebarOpen(!isSidebarOpen)}
                                    className="inline-flex items-center rounded-xl border border-slate-200 bg-white p-2 text-slate-600 transition hover:bg-slate-900 hover:text-white sm:hidden"
                                >
                                    <Menu size={20} />
                                </button>

                                <div className="hidden sm:block">
                                    <p className="text-[11px] font-semibold uppercase tracking-[0.18em] text-slate-400">Workspace</p>
                                    <p className="mt-1 text-lg font-semibold text-slate-900">{pageTitle}</p>
                                </div>
                            </div>

                            {/* Center: Search (Desktop) */}
                            <div className="mx-8 hidden max-w-xl flex-1 lg:flex" ref={searchRef}>
                                <div className="relative w-full">
                                    <div className="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
                                        <Search size={16} className="text-slate-400" />
                                    </div>
                                    <input
                                        type="search"
                                        value={searchQuery}
                                        onChange={(e) => handleSearch(e.target.value)}
                                        onFocus={() => searchQuery.length >= 2 && setShowResults(true)}
                                        className="block w-full rounded-lg border border-slate-200 bg-slate-50 py-3 pl-10 pr-4 text-sm text-slate-900 transition-all focus:border-accent-300 focus:bg-white focus:ring-4 focus:ring-accent-100"
                                        placeholder="Search events, courses, speakers..."
                                    />

                                    {/* Search Results Dropdown */}
                                    {showResults && (
                                        <div className="app-panel absolute left-0 right-0 top-full z-50 mt-2 max-h-96 overflow-y-auto">
                                            {isSearching ? (
                                                <div className="p-8 text-center text-slate-500">
                                                    <div className="inline-block animate-spin rounded-full h-8 w-8 border-b-2 border-primary-600"></div>
                                                    <p className="mt-2 text-sm">Searching...</p>
                                                </div>
                                            ) : searchResults && (
                                                <SearchResultsDisplay
                                                    results={searchResults}
                                                    onSelect={navigateToResult}
                                                />
                                            )}
                                        </div>
                                    )}
                                </div>
                            </div>

                            {/* Right: Actions + Profile */}
                            <div className="flex items-center gap-3">
                                <Link
                                    href={route('homepage')}
                                    className="hidden items-center gap-2 rounded-lg border border-slate-200 bg-white px-3 py-2 text-sm font-medium text-slate-600 transition hover:border-slate-300 hover:text-slate-900 md:inline-flex"
                                >
                                    <ExternalLink size={16} />
                                    <span className="hidden lg:inline">Website</span>
                                </Link>

                                <button className="rounded-lg p-2 text-slate-600 transition hover:bg-slate-100 lg:hidden" onClick={() => setShowMobileSearch(!showMobileSearch)}>
                                    <Search size={20} />
                                </button>

                                {/* Shopping Cart */}
                                <Link
                                    href={route('cart.index')}
                                    className="relative rounded-lg border border-slate-200 bg-white p-2 text-slate-600 transition hover:border-slate-300 hover:text-slate-900"
                                >
                                    <ShoppingCart size={20} />
                                    {cartCount > 0 && (
                                            <span className="absolute right-0 top-0 flex h-5 w-5 items-center justify-center rounded-full bg-accent-500 text-xs font-bold text-white">
                                            {cartCount > 9 ? '9+' : cartCount}
                                        </span>
                                    )}
                                </Link>

                                {/* Notification Bell */}
                                <div className="relative" ref={notificationRef}>
                                    <button
                                        onClick={() => {
                                            setShowNotifications(!showNotifications);
                                            if (!showNotifications) {
                                                fetchNotifications();
                                            }
                                        }}
                                        className="relative rounded-lg border border-slate-200 bg-white p-2 text-slate-600 transition hover:border-slate-300 hover:text-slate-900"
                                    >
                                        <Bell size={20} />
                                        {unreadCount > 0 && (
                                            <span className="absolute right-0 top-0 flex h-5 w-5 items-center justify-center rounded-full bg-slate-900 text-xs font-bold text-white">
                                                {unreadCount > 9 ? '9+' : unreadCount}
                                            </span>
                                        )}
                                    </button>

                                    {/* Notification Dropdown */}
                                    {showNotifications && (
                                        <div className="app-panel absolute right-0 z-50 mt-2 max-h-128 w-96 overflow-hidden">
                                            <div className="flex items-center justify-between border-b border-gray-200 bg-gray-50 px-4 py-3">
                                                <h3 className="text-sm font-semibold text-slate-800">Notifications</h3>
                                                {unreadCount > 0 && (
                                                    <button
                                                        onClick={markAllAsRead}
                                                        className="text-xs font-medium text-slate-600 hover:text-slate-900"
                                                    >
                                                        Mark all as read
                                                    </button>
                                                )}
                                            </div>
                                            <div className="max-h-96 overflow-y-auto">
                                                {notifications.length === 0 ? (
                                                    <div className="px-4 py-8 text-center text-slate-500">
                                                        <Bell size={32} className="mx-auto mb-2 text-slate-300" />
                                                        <p className="text-sm">No notifications yet</p>
                                                    </div>
                                                ) : (
                                                    <ul>
                                                        {notifications.map((notification) => (
                                                            <li
                                                                key={notification.id}
                                                                className={`border-b border-slate-100 last:border-b-0 ${!notification.read_at ? 'bg-slate-100/80' : ''}`}
                                                            >
                                                                <button
                                                                    onClick={() => handleNotificationClick(notification)}
                                                                    className="w-full px-4 py-3 text-left hover:bg-slate-50 transition-colors"
                                                                >
                                                                    <div className="flex items-start gap-3">
                                                                        <div className="flex-1 min-w-0">
                                                                            <p className="text-sm text-slate-800 line-clamp-2">
                                                                                {notification.message}
                                                                            </p>
                                                                            <p className="text-xs text-slate-500 mt-1">
                                                                                {notification.created_at}
                                                                            </p>
                                                                        </div>
                                                                        {!notification.read_at && (
                                                                            <div className="mt-1 h-2 w-2 shrink-0 rounded-full bg-slate-700"></div>
                                                                        )}
                                                                    </div>
                                                                </button>
                                                            </li>
                                                        ))}
                                                    </ul>
                                                )}
                                            </div>
                                        </div>
                                    )}
                                </div>

                                {/* User Dropdown */}
                                <div className="relative" ref={profileDropdownRef}>
                                    <button
                                        onClick={() => setShowProfileDropdown(!showProfileDropdown)}
                                        className="flex items-center gap-2 rounded-xl border border-slate-200 bg-white p-1.5 text-sm transition hover:border-slate-300"
                                    >
                                        <img
                                            className="h-8 w-8 rounded-lg border border-slate-200 object-cover"
                                            src={getAvatarUrl()}
                                            alt={user?.name}
                                            onError={(e) => {
                                                (e.target as HTMLImageElement).src = `https://ui-avatars.com/api/?name=${encodeURIComponent(user?.name || '')}&background=1f2937&color=fff`;
                                            }}
                                        />
                                        <div className="hidden md:block text-left">
                                                <p className="text-sm font-semibold leading-tight text-slate-700">
                                                    {user?.name?.substring(0, 12)}
                                                </p>
                                                <p className="text-xs leading-tight text-slate-500">
                                                    {user?.email?.substring(0, 20)}
                                                </p>
                                            </div>
                                        <ChevronDown size={16} className={`text-slate-400 transition-transform duration-200 ${showProfileDropdown ? 'rotate-180' : ''}`} />
                                    </button>

                                    {/* Dropdown Menu */}
                                    {showProfileDropdown && (
                                        <div className="app-panel absolute right-0 z-50 mt-2 w-56 overflow-hidden">
                                            <div className="border-b border-gray-200 bg-gray-50 px-4 py-3">
                                                <p className="text-sm font-semibold text-slate-800">{user?.name}</p>
                                                <p className="text-sm text-slate-600 truncate">{user?.email}</p>
                                            </div>
                                            <ul className="py-2">
                                                <li>
                                                    <Link
                                                        href={route('homepage')}
                                                        className="flex items-center gap-3 px-4 py-2.5 text-sm text-slate-700 transition-colors hover:bg-slate-50 hover:text-slate-900"
                                                    >
                                                        <ExternalLink size={16} className="text-slate-400" />
                                                        Visit Website
                                                    </Link>
                                                </li>
                                                <li>
                                                    <Link
                                                        href={route('profile.edit')}
                                                        className="flex items-center gap-3 px-4 py-2.5 text-sm text-slate-700 transition-colors hover:bg-slate-50 hover:text-slate-900"
                                                    >
                                                        <User size={16} className="text-slate-400" />
                                                        Profile Settings
                                                    </Link>
                                                </li>
                                                <li className="border-t border-slate-100 mt-1 pt-1">
                                                    <button
                                                        onClick={logout}
                                                        className="flex items-center gap-3 w-full px-4 py-2.5 text-sm text-slate-700 hover:bg-slate-50 transition-colors"
                                                    >
                                                        <LogOut size={16} className="text-slate-400" />
                                                        Sign out
                                                    </button>
                                                </li>
                                            </ul>
                                        </div>
                                    )}
                                </div>
                            </div>
                        </div>

                        {/* Mobile Search */}
                        {showMobileSearch && (
                            <div className="lg:hidden mt-4 pt-4 border-t border-slate-200">
                                <div className="relative">
                                    <div className="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
                                        <Search size={16} className="text-slate-400" />
                                    </div>
                                    <input
                                        type="search"
                                        value={searchQuery}
                                        onChange={(e) => handleSearch(e.target.value)}
                                        onFocus={() => searchQuery.length >= 2 && setShowResults(true)}
                                        className="block w-full rounded-lg border border-slate-200 bg-slate-50 py-3 pl-10 pr-4 text-sm text-slate-900 focus:border-accent-300 focus:bg-white focus:ring-4 focus:ring-accent-100"
                                        placeholder="Search events, courses, speakers..."
                                    />

                                    {/* Mobile Search Results */}
                                    {showResults && (
                                        <div className="app-panel absolute left-0 right-0 top-full z-50 mt-2 max-h-96 overflow-y-auto">
                                            {isSearching ? (
                                                <div className="p-8 text-center text-slate-500">
                                                    <div className="inline-block animate-spin rounded-full h-8 w-8 border-b-2 border-primary-600"></div>
                                                    <p className="mt-2 text-sm">Searching...</p>
                                                </div>
                                            ) : searchResults && (
                                                <SearchResultsDisplay
                                                    results={searchResults}
                                                    onSelect={navigateToResult}
                                                />
                                            )}
                                        </div>
                                    )}
                                </div>
                            </div>
                        )}
                    </div>
                </nav>

                {/* Page Content */}
                <main className="px-4 py-6 lg:px-6 xl:px-8">
                    <div className="dashboard-page-shell">
                        {children}
                    </div>
                </main>
            </div>
        </div>
    );
}

// Search Results Display Component
function SearchResultsDisplay({
    results,
    onSelect
}: {
    results: SearchResults;
    onSelect: (result: SearchResult) => void;
}) {
    const totalResults = results.events.length + results.courses.length + results.speakers.length;

    if (totalResults === 0) {
        return (
            <div className="p-8 text-center text-slate-500">
                <Search size={32} className="mx-auto text-slate-300 mb-2" />
                <p className="text-sm font-medium">No results found</p>
                <p className="text-xs mt-1">Try a different search term</p>
            </div>
        );
    }

    return (
        <div className="py-2">
            {/* Events Section */}
            {results.events.length > 0 && (
                <div className="mb-2">
                    <div className="px-4 py-2 text-xs font-semibold text-slate-500 uppercase tracking-wider">
                        Events ({results.events.length})
                    </div>
                    {results.events.map((event) => (
                        <button
                            key={`event-${event.id}`}
                            onClick={() => onSelect(event)}
                            className="w-full px-4 py-3 hover:bg-slate-50 transition-colors text-left border-b border-slate-100 last:border-0"
                        >
                            <div className="flex items-start gap-3">
                                <Calendar size={16} className="mt-1 shrink-0 text-secondary" />
                                <div className="flex-1 min-w-0">
                                    <p className="font-medium text-sm text-slate-900 truncate">{event.title}</p>
                                    {event.subtitle && (
                                        <p className="text-xs text-slate-600 truncate">{event.subtitle}</p>
                                    )}
                                    {event.meta && (
                                        <p className="text-xs text-slate-500 mt-0.5">{event.meta}</p>
                                    )}
                                </div>
                            </div>
                        </button>
                    ))}
                </div>
            )}

            {/* Courses Section */}
            {results.courses.length > 0 && (
                <div className="mb-2">
                    <div className="px-4 py-2 text-xs font-semibold text-slate-500 uppercase tracking-wider">
                        Courses ({results.courses.length})
                    </div>
                    {results.courses.map((course) => (
                        <button
                            key={`course-${course.id}`}
                            onClick={() => onSelect(course)}
                            className="w-full px-4 py-3 hover:bg-slate-50 transition-colors text-left border-b border-slate-100 last:border-0"
                        >
                            <div className="flex items-start gap-3">
                                {course.thumbnail ? (
                                    <img
                                        src={`/storage/${course.thumbnail}`}
                                        alt={course.title}
                                        className="w-12 h-12 rounded object-cover shrink-0"
                                        onError={(e) => {
                                            (e.target as HTMLImageElement).src = 'https://placehold.co/100x100?text=No+Img';
                                        }}
                                    />
                                ) : (
                                    <div className="flex h-12 w-12 shrink-0 items-center justify-center rounded bg-secondary-50">
                                        <BookOpen size={20} className="text-slate-600" />
                                    </div>
                                )}
                                <div className="flex-1 min-w-0">
                                    <p className="font-medium text-sm text-slate-900 truncate">{course.title}</p>
                                    {course.subtitle && (
                                        <p className="text-xs text-slate-600 truncate">By {course.subtitle}</p>
                                    )}
                                    {course.meta && (
                                        <p className="text-xs text-slate-500 mt-0.5">{course.meta}</p>
                                    )}
                                </div>
                            </div>
                        </button>
                    ))}
                </div>
            )}

            {/* Speakers Section */}
            {results.speakers.length > 0 && (
                <div>
                    <div className="px-4 py-2 text-xs font-semibold text-slate-500 uppercase tracking-wider">
                        Speakers ({results.speakers.length})
                    </div>
                    {results.speakers.map((speaker) => (
                        <button
                            key={`speaker-${speaker.id}`}
                            onClick={() => onSelect(speaker)}
                            className="w-full px-4 py-3 hover:bg-slate-50 transition-colors text-left border-b border-slate-100 last:border-0"
                        >
                            <div className="flex items-start gap-3">
                                {speaker.thumbnail ? (
                                    <img
                                        src={`/storage/${speaker.thumbnail}`}
                                        alt={speaker.title}
                                        className="w-12 h-12 rounded-full object-cover shrink-0"
                                        onError={(e) => {
                                            (e.target as HTMLImageElement).src = 'https://placehold.co/100x100?text=No+Img';
                                        }}
                                    />
                                ) : (
                                    <div className="flex h-12 w-12 shrink-0 items-center justify-center rounded-full bg-accent-50">
                                        <Mic size={20} className="text-accent" />
                                    </div>
                                )}
                                <div className="flex-1 min-w-0">
                                    <p className="font-medium text-sm text-slate-900 truncate">{speaker.title}</p>
                                    {speaker.subtitle && (
                                        <p className="text-xs text-slate-600 truncate">{speaker.subtitle}</p>
                                    )}
                                    {speaker.meta && (
                                        <p className="text-xs text-slate-500 mt-0.5 line-clamp-2">{speaker.meta}</p>
                                    )}
                                </div>
                            </div>
                        </button>
                    ))}
                </div>
            )}
        </div>
    );
}
