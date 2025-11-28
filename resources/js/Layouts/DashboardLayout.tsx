import { PropsWithChildren, useState, useEffect, useRef } from 'react';
import { Link, router, usePage } from '@inertiajs/react';
import { ToastContainer, useToastNotifications } from '@/Components/Toast';
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
    Home,
    Search,
    ExternalLink,
    User,
    LogOut,
    Bell,
} from 'lucide-react';
import axios from 'axios';

interface SideLink {
    title: string;
    icon: string;
    route?: string;
    permission?: string | string[];
    exclude_permission?: string | string[];
    variant?: string;
    children?: Array<{
        title: string;
        route: string;
        permission?: string;
    }>;
}

interface DashboardLayoutProps extends PropsWithChildren {
    sideLinks: SideLink[];
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

export default function DashboardLayout({ children, sideLinks }: DashboardLayoutProps) {
    const { auth } = usePage().props as any;
    const user = auth?.user;

    // Toast notifications
    useToastNotifications();

    // Fallback to empty array if sideLinks is undefined
    const links = sideLinks || [];
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

    const logout = (e: React.FormEvent) => {
        e.preventDefault();
        router.post(route('logout'));
    };

    const getAvatarUrl = () => {
        if (user?.photo) {
            return `/storage/${user.photo}`;
        }
        return `https://ui-avatars.com/api/?name=${encodeURIComponent(user?.name || '')}&background=002147&color=fff`;
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

    // Fetch notifications on mount
    useEffect(() => {
        fetchNotifications();
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
        };
        return iconMap[iconName] || LayoutDashboard;
    };

    return (
        <div className="min-h-screen bg-gray-50">
            {/* Toast Notifications */}
            <ToastContainer />

            {/* Sidebar */}
            <aside
                className={`fixed top-0 left-0 z-40 w-64 h-screen transition-transform ${isSidebarOpen ? 'translate-x-0' : '-translate-x-full'
                    } sm:translate-x-0 bg-linear-to-b from-slate-900 to-slate-800 shadow-2xl`}
            >
                <div className="h-full px-4 py-6 overflow-y-auto">
                    {/* Logo */}
                    <div className="flex items-center justify-between mb-8 pb-6 border-b border-slate-700/50">
                        <Link href={route('user_dashboard')} className="flex items-center space-x-3 group">
                            <div className="relative">
                                <img
                                    src="/images/logo.jpg"
                                    className="h-10 w-10 rounded-xl shadow-lg ring-2 ring-white/10 group-hover:ring-white/20 transition-all duration-300"
                                    alt="BLI Logo"
                                />
                            </div>
                            <span className="text-white font-bold text-lg tracking-tight group-hover:text-blue-300 transition-colors duration-300">
                                BLI
                            </span>
                        </Link>
                        <button
                            onClick={() => setIsSidebarOpen(false)}
                            className="inline-flex items-center p-2 text-sm text-slate-400 rounded-lg sm:hidden hover:bg-slate-700/50 hover:text-white"
                        >
                            <X size={20} />
                        </button>
                    </div>

                    {/* Navigation */}
                    <nav className="text-slate-300">
                        <ul className="space-y-1">
                            {links.map((link, index) => {
                                if (!shouldShowLink(link)) return null;

                                if (link.children) {
                                    const isExpanded = expandedMenus.includes(link.title);
                                    const IconComponent = getIcon(link.icon);
                                    return (
                                        <li key={index}>
                                            <button
                                                onClick={() => toggleMenu(link.title)}
                                                className="flex items-center w-full p-3 text-sm font-medium text-slate-300 transition-all duration-200 rounded-xl group hover:bg-slate-700/50 hover:text-white"
                                            >
                                                <IconComponent size={18} className="text-slate-400 transition-colors duration-200 group-hover:text-blue-400 mr-3 shrink-0" />
                                                <span className="flex-1 text-left whitespace-nowrap">{link.title}</span>
                                                <ChevronDown size={16} className={`text-slate-400 transition-all duration-200 ${isExpanded ? 'rotate-180' : ''}`} />
                                            </button>
                                            {isExpanded && (
                                                <ul className="mt-2 space-y-1 ml-4">
                                                    {link.children.map((child, childIndex) => {
                                                        if (!hasPermission(child.permission)) return null;
                                                        return (
                                                            <li key={childIndex}>
                                                                <Link
                                                                    href={route(child.route)}
                                                                    className="flex items-center w-full p-2.5 text-sm text-slate-400 transition-all duration-200 rounded-lg hover:bg-slate-700/30 hover:text-white hover:translate-x-1 border-l-2 border-slate-600 hover:border-blue-400 pl-4"
                                                                >
                                                                    <span className="w-2 h-2 bg-slate-500 rounded-full mr-3"></span>
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

                                const IconComponent = getIcon(link.icon);
                                return (
                                    <li key={index}>
                                        <Link
                                            href={route(link.route!)}
                                            className="flex items-center p-3 text-sm font-medium text-slate-300 rounded-xl transition-all duration-200 hover:bg-slate-700/50 hover:text-white group"
                                        >
                                            <IconComponent size={18} className="text-slate-400 transition-colors duration-200 group-hover:text-blue-400 mr-3 shrink-0" />
                                            <span className="flex-1 whitespace-nowrap">{link.title}</span>
                                        </Link>
                                    </li>
                                );
                            })}
                        </ul>
                    </nav>
                </div>
            </aside>

            {/* Main Content Area */}
            <div className="sm:ml-64">
                {/* Top Navbar */}
                <nav className="bg-white/95 backdrop-blur-md border-b border-slate-200 shadow-lg sticky top-0 z-30">
                    <div className="px-4 lg:px-6 py-3">
                        <div className="flex items-center justify-between">
                            {/* Left: Mobile menu + Breadcrumb */}
                            <div className="flex items-center gap-4">
                                <button
                                    onClick={() => setIsSidebarOpen(!isSidebarOpen)}
                                    className="inline-flex items-center p-2 text-slate-600 rounded-xl sm:hidden hover:bg-primary-600 hover:text-white"
                                >
                                    <Menu size={20} />
                                </button>

                                <nav className="hidden sm:flex items-center space-x-2 text-sm">
                                    <Link
                                        href={route('user_dashboard')}
                                        className="flex items-center text-slate-500 hover:text-primary-600 transition-colors"
                                        style={{ color: route().current('user_dashboard') ? '#002147' : undefined }}
                                    >
                                        <Home size={16} className="mr-1" />
                                        <span className="font-medium">Dashboard</span>
                                    </Link>
                                </nav>
                            </div>

                            {/* Center: Search (Desktop) */}
                            <div className="hidden lg:flex flex-1 max-w-lg mx-8" ref={searchRef}>
                                <div className="relative w-full">
                                    <div className="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
                                        <Search size={16} className="text-slate-400" />
                                    </div>
                                    <input
                                        type="search"
                                        value={searchQuery}
                                        onChange={(e) => handleSearch(e.target.value)}
                                        onFocus={() => searchQuery.length >= 2 && setShowResults(true)}
                                        className="block w-full pl-10 pr-4 py-2.5 text-sm text-slate-900 bg-slate-50 border border-slate-200 rounded-xl focus:ring-2 focus:border-primary-500 transition-all"
                                        style={{ '--tw-ring-color': '#002147' } as any}
                                        placeholder="Search events, courses, speakers..."
                                    />

                                    {/* Search Results Dropdown */}
                                    {showResults && (
                                        <div className="absolute top-full left-0 right-0 mt-2 bg-white rounded-xl shadow-2xl border border-slate-200 max-h-96 overflow-y-auto z-50">
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
                                    className="hidden md:inline-flex items-center gap-2 px-3 py-2 text-sm font-medium text-slate-600 bg-slate-100 rounded-lg hover:bg-primary-600 hover:text-white transition-all"
                                >
                                    <ExternalLink size={16} />
                                    <span className="hidden lg:inline">Website</span>
                                </Link>

                                <button className="lg:hidden p-2 text-slate-600 rounded-lg hover:bg-slate-100" onClick={() => setShowMobileSearch(!showMobileSearch)}>
                                    <Search size={20} />
                                </button>

                                {/* Notification Bell */}
                                <div className="relative" ref={notificationRef}>
                                    <button
                                        onClick={() => {
                                            setShowNotifications(!showNotifications);
                                            if (!showNotifications) {
                                                fetchNotifications();
                                            }
                                        }}
                                        className="relative p-2 text-slate-600 rounded-lg hover:bg-slate-100 transition-all"
                                    >
                                        <Bell size={20} />
                                        {unreadCount > 0 && (
                                            <span className="absolute top-0 right-0 flex items-center justify-center w-5 h-5 text-xs font-bold text-white bg-red-500 rounded-full">
                                                {unreadCount > 9 ? '9+' : unreadCount}
                                            </span>
                                        )}
                                    </button>

                                    {/* Notification Dropdown */}
                                    {showNotifications && (
                                        <div className="absolute right-0 mt-2 w-96 max-h-128 bg-white rounded-xl shadow-xl border border-slate-200 overflow-hidden z-50">
                                            <div className="px-4 py-3 bg-linear-to-r from-primary-50 to-slate-50 flex items-center justify-between">
                                                <h3 className="text-sm font-semibold text-slate-800">Notifications</h3>
                                                {unreadCount > 0 && (
                                                    <button
                                                        onClick={markAllAsRead}
                                                        className="text-xs text-primary-600 hover:text-primary-700 font-medium"
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
                                                                className={`border-b border-slate-100 last:border-b-0 ${!notification.read_at ? 'bg-blue-50/50' : ''
                                                                    }`}
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
                                                                            <div className="w-2 h-2 bg-blue-500 rounded-full mt-1 shrink-0"></div>
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
                                        className="flex items-center gap-2 p-1.5 text-sm bg-slate-50 rounded-xl hover:bg-slate-100 transition-all"
                                    >
                                        <img
                                            className="w-8 h-8 rounded-lg object-cover border border-slate-200"
                                            src={getAvatarUrl()}
                                            alt={user?.name}
                                        />
                                        <div className="hidden md:block text-left">
                                            <p className="text-sm font-semibold text-slate-700 leading-tight">
                                                {user?.name?.substring(0, 12)}
                                            </p>
                                            <p className="text-xs text-slate-500 leading-tight">
                                                {user?.email?.substring(0, 20)}
                                            </p>
                                        </div>
                                        <ChevronDown size={16} className={`text-slate-400 transition-transform duration-200 ${showProfileDropdown ? 'rotate-180' : ''}`} />
                                    </button>

                                    {/* Dropdown Menu */}
                                    {showProfileDropdown && (
                                        <div className="absolute right-0 mt-2 w-56 bg-white rounded-xl shadow-xl border border-slate-200 overflow-hidden z-50">
                                            <div className="px-4 py-3 bg-linear-to-r from-primary-50 to-slate-50">
                                                <p className="text-sm font-semibold text-slate-800">{user?.name}</p>
                                                <p className="text-sm text-slate-600 truncate">{user?.email}</p>
                                            </div>
                                            <ul className="py-2">
                                                <li>
                                                    <Link
                                                        href={route('homepage')}
                                                        className="flex items-center gap-3 px-4 py-2.5 text-sm text-slate-700 hover:bg-slate-50 hover:text-primary-600 transition-colors"
                                                    >
                                                        <ExternalLink size={16} className="text-slate-400" />
                                                        Visit Website
                                                    </Link>
                                                </li>
                                                <li>
                                                    <Link
                                                        href={route('profile.edit')}
                                                        className="flex items-center gap-3 px-4 py-2.5 text-sm text-slate-700 hover:bg-slate-50 hover:text-primary-600 transition-colors"
                                                    >
                                                        <User size={16} className="text-slate-400" />
                                                        Profile Settings
                                                    </Link>
                                                </li>
                                                <li className="border-t border-slate-100 mt-1 pt-1">
                                                    <button
                                                        onClick={logout}
                                                        className="flex items-center gap-3 w-full px-4 py-2.5 text-sm text-red-600 hover:bg-red-50 transition-colors"
                                                    >
                                                        <LogOut size={16} className="text-red-500" />
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
                                        className="block w-full pl-10 pr-4 py-2.5 text-sm text-slate-900 bg-slate-50 border border-slate-200 rounded-xl focus:ring-2 focus:border-primary-500"
                                        placeholder="Search events, courses, speakers..."
                                    />

                                    {/* Mobile Search Results */}
                                    {showResults && (
                                        <div className="absolute top-full left-0 right-0 mt-2 bg-white rounded-xl shadow-2xl border border-slate-200 max-h-96 overflow-y-auto z-50">
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
                <main className="p-4 lg:p-6">
                    {children}
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
                                <Calendar size={16} className="text-blue-500 mt-1 shrink-0" />
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
                                    />
                                ) : (
                                    <div className="w-12 h-12 rounded bg-linear-to-br from-green-100 to-green-50 flex items-center justify-center shrink-0">
                                        <BookOpen size={20} className="text-green-600" />
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
                                    />
                                ) : (
                                    <div className="w-12 h-12 rounded-full bg-linear-to-br from-purple-100 to-purple-50 flex items-center justify-center shrink-0">
                                        <Mic size={20} className="text-purple-600" />
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
