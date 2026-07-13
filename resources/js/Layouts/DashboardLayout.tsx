import { useMemo, useState } from 'react';
import type { PropsWithChildren } from 'react';
import { usePage } from '@inertiajs/react';
import DashboardHeader from '@/Components/Dashboard/DashboardHeader';
import DashboardSidebar from '@/Components/Dashboard/DashboardSidebar';
import { currentPageTitle, visibleSideLinks } from '@/Components/Dashboard/navigation';
import type { DashboardUser } from '@/Components/Dashboard/types';
import FlashMessage from '@/Components/FlashMessage';
import { ToastContainer, useToastNotifications } from '@/Components/Toast';
import type { PageProps, SideLink } from '@/types';

interface DashboardLayoutProps extends PropsWithChildren {
    sideLinks?: SideLink[];
    user?: DashboardUser | null;
}

export default function DashboardLayout({ children, sideLinks: providedLinks, user: providedUser }: DashboardLayoutProps) {
    const page = usePage<PageProps>();
    const user = providedUser ?? page.props.auth?.user;
    const sideLinks = providedLinks ?? page.props.sideLinks ?? [];
    const [sidebarOpen, setSidebarOpen] = useState(false);

    // The server already filters navigation, but keeping this final client-side
    // guard prevents stale Inertia props from briefly exposing restricted links.
    const visibleLinks = useMemo(
        () => visibleSideLinks(sideLinks, user?.permissions ?? []),
        [sideLinks, user?.permissions],
    );
    const pageTitle = useMemo(() => currentPageTitle(visibleLinks), [visibleLinks]);

    useToastNotifications();

    return (
        <div className="min-h-screen bg-slate-50 text-slate-900">
            <ToastContainer />
            <FlashMessage />

            <DashboardSidebar sideLinks={visibleLinks} user={user} open={sidebarOpen} onClose={() => setSidebarOpen(false)} />

            <div className="lg:pl-64">
                <DashboardHeader pageTitle={pageTitle} user={user} onOpenSidebar={() => setSidebarOpen(true)} />
                <main className="px-4 py-6 sm:px-6 lg:px-8">
                    <div className="mx-auto max-w-7xl">{children}</div>
                </main>
            </div>
        </div>
    );
}
