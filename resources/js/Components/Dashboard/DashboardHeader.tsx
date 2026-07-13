import { Link } from '@inertiajs/react';
import { ExternalLink, Menu, Search } from 'lucide-react';
import { useState } from 'react';
import { route } from 'ziggy-js';
import GlobalSearch from './GlobalSearch';
import NotificationMenu from './NotificationMenu';
import ProfileMenu from './ProfileMenu';
import type { DashboardUser } from './types';

export default function DashboardHeader({ pageTitle, user, onOpenSidebar }: {
    pageTitle: string;
    user?: DashboardUser | null;
    onOpenSidebar: () => void;
}) {
    const [mobileSearchOpen, setMobileSearchOpen] = useState(false);

    return (
        <header className="sticky top-0 z-30 border-b border-slate-200 bg-white/80 backdrop-blur-lg">
            <div className="flex h-16 items-center justify-between gap-4 px-4 sm:px-6 lg:px-8">
                <div className="flex min-w-0 items-center gap-3">
                    <button type="button" aria-label="Open navigation" className="rounded-md border border-slate-200 p-2 text-slate-500 hover:bg-slate-50 hover:text-slate-700 lg:hidden" onClick={onOpenSidebar}><Menu size={18} /></button>
                    <h1 className="truncate text-base font-semibold tracking-tight text-slate-900">{pageTitle}</h1>
                </div>

                <GlobalSearch mobileOpen={false} onMobileClose={() => setMobileSearchOpen(false)} />

                <div className="flex items-center gap-1.5">
                    <button type="button" aria-label="Toggle search" className="rounded-md border border-slate-200 p-2 text-slate-500 hover:bg-slate-50 hover:text-slate-700 lg:hidden" onClick={() => setMobileSearchOpen((open) => !open)}><Search size={18} /></button>
                    <Link href={route('homepage')} className="hidden items-center gap-1.5 rounded-md border border-slate-200 px-3 py-2 text-xs font-medium text-slate-600 transition hover:border-primary-200 hover:bg-primary-50 hover:text-primary md:inline-flex"><ExternalLink size={14} />Website</Link>
                    <NotificationMenu />
                    <ProfileMenu user={user} />
                </div>
            </div>

            {/* Mobile search stays in the header so its results are anchored below the toolbar. */}
            {mobileSearchOpen && <GlobalSearch mobileOpen onMobileClose={() => setMobileSearchOpen(false)} />}
        </header>
    );
}
