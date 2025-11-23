import { PropsWithChildren, useState } from 'react';
import { Link, router, usePage } from '@inertiajs/react';
import { ToastContainer, useToastNotifications } from '@/Components/Toast';
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
    LogOut
} from 'lucide-react';

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
                className={`fixed top-0 left-0 z-40 w-64 h-screen transition-transform ${
                    isSidebarOpen ? 'translate-x-0' : '-translate-x-full'
                } sm:translate-x-0 bg-gradient-to-b from-slate-900 to-slate-800 shadow-2xl`}
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
                                                <IconComponent size={18} className="text-slate-400 transition-colors duration-200 group-hover:text-blue-400 mr-3 flex-shrink-0" />
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
                                            <IconComponent size={18} className="text-slate-400 transition-colors duration-200 group-hover:text-blue-400 mr-3 flex-shrink-0" />
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
                            <div className="hidden lg:flex flex-1 max-w-lg mx-8">
                                <div className="relative w-full">
                                    <div className="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
                                        <Search size={16} className="text-slate-400" />
                                    </div>
                                    <input
                                        type="search"
                                        className="block w-full pl-10 pr-4 py-2.5 text-sm text-slate-900 bg-slate-50 border border-slate-200 rounded-xl focus:ring-2 focus:border-primary-500 transition-all"
                                        style={{ '--tw-ring-color': '#002147' } as any}
                                        placeholder="Search events, courses, speakers..."
                                    />
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

                                {/* User Dropdown */}
                                <div className="relative group">
                                    <button className="flex items-center gap-2 p-1.5 text-sm bg-slate-50 rounded-xl hover:bg-slate-100 transition-all">
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
                                        <ChevronDown size={16} className="text-slate-400" />
                                    </button>

                                    {/* Dropdown Menu */}
                                    <div className="absolute hidden group-hover:block right-0 mt-2 w-56 bg-white rounded-xl shadow-xl border border-slate-200 overflow-hidden">
                                        <div className="px-4 py-3 bg-gradient-to-r from-primary-50 to-slate-50">
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
                                        className="block w-full pl-10 pr-4 py-2.5 text-sm text-slate-900 bg-slate-50 border border-slate-200 rounded-xl focus:ring-2 focus:border-primary-500"
                                        placeholder="Search events, courses, speakers..."
                                    />
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
