import type { SideLink, User } from '@/types';

export type DashboardUser = Pick<User, 'name' | 'email' | 'photo' | 'permissions'>;

export interface DashboardShellProps {
    sideLinks: SideLink[];
    user?: DashboardUser | null;
}

export interface SearchResult {
    id: number;
    slug: string;
    title: string;
    subtitle?: string;
    meta?: string;
    thumbnail?: string;
    type: 'event' | 'speaker';
}

export interface SearchResults {
    events: SearchResult[];
    speakers: SearchResult[];
}

export interface DashboardNotification {
    id: string;
    type: string;
    message: string;
    action_url: string | null;
    read_at: string | null;
    created_at: string;
    data: unknown;
}
