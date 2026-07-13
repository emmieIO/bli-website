import { router } from '@inertiajs/react';
import axios from 'axios';
import { Calendar, Mic, Search } from 'lucide-react';
import type { LucideIcon } from 'lucide-react';
import { useEffect, useRef, useState } from 'react';
import { route } from 'ziggy-js';
import { EmptyState, Popover } from './DashboardPrimitives';
import type { SearchResult, SearchResults } from './types';

export default function GlobalSearch({ mobileOpen, onMobileClose }: { mobileOpen: boolean; onMobileClose: () => void }) {
    const [query, setQuery] = useState('');
    const [results, setResults] = useState<SearchResults | null>(null);
    const [isSearching, setIsSearching] = useState(false);
    const [showResults, setShowResults] = useState(false);
    const desktopRef = useRef<HTMLDivElement>(null);
    const mobileRef = useRef<HTMLDivElement>(null);
    const searchTimeoutRef = useRef<ReturnType<typeof setTimeout>>(undefined);

    const search = (value: string) => {
        setQuery(value);
        if (searchTimeoutRef.current) clearTimeout(searchTimeoutRef.current);

        if (value.trim().length < 2) {
            setResults(null);
            setShowResults(false);
            return;
        }

        setIsSearching(true);
        setShowResults(true);
        // A short debounce prevents one request per keystroke while keeping search responsive.
        searchTimeoutRef.current = setTimeout(async () => {
            try {
                const response = await axios.get(route('search'), { params: { q: value } });
                setResults(response.data);
            } catch {
                setResults(null);
            } finally {
                setIsSearching(false);
            }
        }, 250);
    };

    const selectResult = (result: SearchResult) => {
        const url = result.type === 'event'
            ? route('events.show', result.slug)
            : route('speakers.show', result.slug);

        router.visit(url);
        setQuery('');
        setShowResults(false);
        onMobileClose();
    };

    useEffect(() => {
        const closeResults = (event: MouseEvent) => {
            const target = event.target as Node;
            const insideDesktop = desktopRef.current?.contains(target);
            const insideMobile = mobileRef.current?.contains(target);
            if (!insideDesktop && !insideMobile) setShowResults(false);
        };

        document.addEventListener('mousedown', closeResults);
        return () => document.removeEventListener('mousedown', closeResults);
    }, []);

    useEffect(() => () => {
        if (searchTimeoutRef.current) clearTimeout(searchTimeoutRef.current);
    }, []);

    const box = (
        <SearchBox value={query} onChange={search} isSearching={isSearching} showResults={showResults} results={results} onSelect={selectResult} />
    );

    return (
        <>
            <div className="hidden max-w-md flex-1 lg:block" ref={desktopRef}>{box}</div>
            {mobileOpen && <div className="border-t border-slate-100 px-4 py-3 lg:hidden" ref={mobileRef}>{box}</div>}
        </>
    );
}

function SearchBox({ value, onChange, isSearching, showResults, results, onSelect }: {
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
                    {isSearching
                        ? <div className="px-4 py-6 text-center text-xs text-slate-500">Searching...</div>
                        : results ? <SearchResultsDisplay results={results} onSelect={onSelect} /> : <EmptyState icon={Search} title="No results" />}
                </Popover>
            )}
        </div>
    );
}

function SearchResultsDisplay({ results, onSelect }: { results: SearchResults; onSelect: (result: SearchResult) => void }) {
    if (results.events.length + results.speakers.length === 0) return <EmptyState icon={Search} title="No results found" />;

    return (
        <div className="py-1">
            <ResultGroup title="Events" items={results.events} icon={Calendar} onSelect={onSelect} />
            <ResultGroup title="Speakers" items={results.speakers} icon={Mic} onSelect={onSelect} />
        </div>
    );
}

function ResultGroup({ title, items, icon: Icon, onSelect }: {
    title: string;
    items: SearchResult[];
    icon: LucideIcon;
    onSelect: (result: SearchResult) => void;
}) {
    if (!items.length) return null;

    return (
        <div>
            <p className="px-4 py-2 text-[10px] font-semibold uppercase tracking-[0.18em] text-slate-400">{title}</p>
            {items.map((item) => (
                <button key={`${item.type}-${item.id}`} type="button" className="flex w-full items-start gap-3 px-4 py-2.5 text-left transition hover:bg-slate-50" onClick={() => onSelect(item)}>
                    <span className="flex h-8 w-8 shrink-0 items-center justify-center rounded-md bg-slate-100 text-slate-500"><Icon size={15} /></span>
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
