import { router } from '@inertiajs/react';
import axios from 'axios';
import { Bell, X } from 'lucide-react';
import { useEffect, useRef, useState } from 'react';
import { route } from 'ziggy-js';
import { EmptyState, Popover } from './DashboardPrimitives';
import type { DashboardNotification } from './types';

export default function NotificationMenu() {
    const [open, setOpen] = useState(false);
    const [notifications, setNotifications] = useState<DashboardNotification[]>([]);
    const [unreadCount, setUnreadCount] = useState(0);
    const menuRef = useRef<HTMLDivElement>(null);

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
            setNotifications((items) => items.map((item) => item.id === notificationId ? { ...item, read_at: new Date().toISOString() } : item));
        } catch {
            // The menu remains usable if a background notification request fails.
        }
    };

    const markAllAsRead = async () => {
        try {
            await axios.post(route('notifications.mark-all-as-read'));
            setUnreadCount(0);
            setNotifications((items) => items.map((item) => ({ ...item, read_at: new Date().toISOString() })));
        } catch {}
    };

    const clearAll = async () => {
        try {
            await axios.delete(route('notifications.clear-all'));
            setNotifications([]);
            setUnreadCount(0);
        } catch {}
    };

    const remove = async (notification: DashboardNotification) => {
        try {
            await axios.delete(route('notifications.destroy', notification.id));
            setNotifications((items) => items.filter((item) => item.id !== notification.id));
            if (!notification.read_at) setUnreadCount((count) => Math.max(0, count - 1));
        } catch {}
    };

    const select = (notification: DashboardNotification) => {
        if (!notification.read_at) void markAsRead(notification.id);
        if (notification.action_url) router.visit(notification.action_url);
        setOpen(false);
    };

    useEffect(() => { void fetchNotifications(); }, []);
    useEffect(() => {
        const close = (event: MouseEvent) => {
            if (menuRef.current && !menuRef.current.contains(event.target as Node)) setOpen(false);
        };
        document.addEventListener('mousedown', close);
        return () => document.removeEventListener('mousedown', close);
    }, []);

    return (
        <div className="relative" ref={menuRef}>
            <button type="button" aria-label="Notifications" className="relative rounded-md border border-slate-200 p-2 text-slate-500 transition hover:bg-slate-50 hover:text-slate-700" onClick={() => {
                setOpen((value) => !value);
                if (!open) void fetchNotifications();
            }}>
                <Bell size={18} />
                {unreadCount > 0 && <span className="absolute -right-1 -top-1 flex h-4.5 min-w-4.5 items-center justify-center rounded-full bg-accent px-1 text-[10px] font-semibold leading-none text-white">{unreadCount > 9 ? '9+' : unreadCount}</span>}
            </button>

            {open && (
                <Popover className="right-0 w-80">
                    <div className="flex items-center justify-between border-b border-slate-100 px-4 py-3">
                        <p className="text-sm font-semibold text-slate-900">Notifications</p>
                        <div className="flex items-center gap-3">
                            {unreadCount > 0 && <button type="button" className="text-xs font-medium text-slate-500 hover:text-primary" onClick={() => void markAllAsRead()}>Mark all read</button>}
                            {notifications.length > 0 && <button type="button" className="text-xs font-medium text-slate-400 hover:text-accent" onClick={() => void clearAll()}>Clear all</button>}
                        </div>
                    </div>
                    <div className="max-h-80 overflow-y-auto">
                        {notifications.length === 0 ? <EmptyState icon={Bell} title="No notifications" /> : notifications.map((notification) => (
                            <div key={notification.id} className={`group flex w-full items-start border-b border-slate-50 last:border-b-0 transition hover:bg-slate-50 ${!notification.read_at ? 'bg-primary-50/40' : ''}`}>
                                <button type="button" className="flex flex-1 gap-2.5 px-4 py-3 text-left" onClick={() => select(notification)}>
                                    <span className={`mt-1 h-1.5 w-1.5 shrink-0 rounded-full ${notification.read_at ? 'bg-slate-200' : 'bg-primary'}`} />
                                    <span className="min-w-0"><span className="line-clamp-2 text-xs text-slate-700">{notification.message}</span><span className="mt-1 block text-[11px] text-slate-400">{notification.created_at}</span></span>
                                </button>
                                <button type="button" aria-label="Remove notification" className="shrink-0 px-3 py-3 text-slate-300 opacity-0 transition hover:text-accent group-hover:opacity-100" onClick={() => void remove(notification)}><X size={13} /></button>
                            </div>
                        ))}
                    </div>
                </Popover>
            )}
        </div>
    );
}
