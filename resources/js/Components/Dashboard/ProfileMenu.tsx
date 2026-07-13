import { Link, router } from '@inertiajs/react';
import { ChevronDown, ExternalLink, LogOut, User } from 'lucide-react';
import type { LucideIcon } from 'lucide-react';
import { useEffect, useRef, useState } from 'react';
import { route } from 'ziggy-js';
import { Popover } from './DashboardPrimitives';
import type { DashboardUser } from './types';

export default function ProfileMenu({ user }: { user?: DashboardUser | null }) {
    const [open, setOpen] = useState(false);
    const menuRef = useRef<HTMLDivElement>(null);
    const avatarUrl = user?.photo
        ? `/storage/${user.photo}`
        : `https://ui-avatars.com/api/?name=${encodeURIComponent(user?.name || 'BLI')}&background=002147&color=fff`;

    useEffect(() => {
        const close = (event: MouseEvent) => {
            if (menuRef.current && !menuRef.current.contains(event.target as Node)) setOpen(false);
        };
        document.addEventListener('mousedown', close);
        return () => document.removeEventListener('mousedown', close);
    }, []);

    return (
        <div className="relative" ref={menuRef}>
            <button type="button" aria-label="Open profile menu" className="flex items-center gap-2 rounded-md border border-slate-200 p-1 pr-2 text-left transition hover:bg-slate-50" onClick={() => setOpen((value) => !value)}>
                <img src={avatarUrl} alt={user?.name || 'User'} className="h-7 w-7 rounded-md object-cover" />
                <ChevronDown size={14} className="hidden text-slate-400 md:block" />
            </button>
            {open && (
                <Popover className="right-0 w-56">
                    <div className="border-b border-slate-100 px-4 py-3">
                        <p className="truncate text-sm font-semibold text-slate-900">{user?.name}</p>
                        <p className="truncate text-xs text-slate-500">{user?.email}</p>
                    </div>
                    <div className="p-1">
                        <MenuLink href={route('homepage')} icon={ExternalLink} label="Visit website" />
                        <MenuLink href={route('profile.edit')} icon={User} label="Profile settings" />
                        <button type="button" className="flex w-full items-center gap-2.5 rounded-md px-3 py-2 text-xs text-slate-600 transition hover:bg-accent-50 hover:text-accent" onClick={() => router.post(route('logout'))}>
                            <LogOut size={14} /> Sign out
                        </button>
                    </div>
                </Popover>
            )}
        </div>
    );
}

function MenuLink({ href, icon: Icon, label }: { href: string; icon: LucideIcon; label: string }) {
    return <Link href={href} className="flex items-center gap-2.5 rounded-md px-3 py-2 text-xs text-slate-600 transition hover:bg-slate-50 hover:text-slate-900"><Icon size={14} />{label}</Link>;
}
