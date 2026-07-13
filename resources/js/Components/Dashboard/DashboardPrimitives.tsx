import type { LucideIcon } from 'lucide-react';
import type { PropsWithChildren } from 'react';

export function Popover({ children, className = '' }: PropsWithChildren<{ className?: string }>) {
    return (
        <div className={`absolute top-full z-50 mt-1.5 overflow-hidden rounded-lg border border-slate-200 bg-white shadow-lg ${className}`}>
            {children}
        </div>
    );
}

export function EmptyState({ icon: Icon, title }: { icon: LucideIcon; title: string }) {
    return (
        <div className="px-4 py-8 text-center">
            <Icon size={24} className="mx-auto text-slate-300" />
            <p className="mt-2 text-xs text-slate-500">{title}</p>
        </div>
    );
}
