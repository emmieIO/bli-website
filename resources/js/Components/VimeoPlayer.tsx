import { useEffect, useRef, useState } from 'react';
import Player from '@vimeo/player';

interface VimeoPlayerProps {
    videoId: string;
    className?: string;
    autoplay?: boolean;
    onReady?: (player: Player) => void;
    onPlay?: () => void;
    onPause?: () => void;
    onEnded?: () => void;
    onTimeUpdate?: (data: { seconds: number; percent: number; duration: number }) => void;
    onError?: (error: Error) => void;
}

export default function VimeoPlayer({
    videoId,
    className = '',
    autoplay = false,
    onReady,
    onPlay,
    onPause,
    onEnded,
    onTimeUpdate,
    onError,
}: VimeoPlayerProps) {
    const containerRef = useRef<HTMLDivElement>(null);
    const playerRef = useRef<Player | null>(null);
    const [isLoading, setIsLoading] = useState(true);
    const [error, setError] = useState<string | null>(null);

    useEffect(() => {
        if (!containerRef.current) return;

        try {
            // Initialize Vimeo Player
            const player = new Player(containerRef.current, {
                id: parseInt(videoId),
                width: 640,
                responsive: true,
                autoplay: autoplay,
                byline: false,
                portrait: false,
                title: false,
                transparent: false,
            });

            playerRef.current = player;

            // Setup event listeners
            player.on('loaded', () => {
                setIsLoading(false);
                if (onReady) {
                    onReady(player);
                }
            });

            player.on('play', () => {
                if (onPlay) {
                    onPlay();
                }
            });

            player.on('pause', () => {
                if (onPause) {
                    onPause();
                }
            });

            player.on('ended', () => {
                if (onEnded) {
                    onEnded();
                }
            });

            player.on('timeupdate', (data) => {
                if (onTimeUpdate) {
                    onTimeUpdate({
                        seconds: data.seconds,
                        percent: data.percent,
                        duration: data.duration,
                    });
                }
            });

            player.on('error', (err) => {
                setError(err.message || 'Failed to load video');
                setIsLoading(false);
                if (onError) {
                    onError(err);
                }
            });

            // Cleanup on unmount
            return () => {
                player.destroy();
            };
        } catch (err) {
            const errorMessage = err instanceof Error ? err.message : 'Failed to initialize player';
            setError(errorMessage);
            setIsLoading(false);
            if (onError && err instanceof Error) {
                onError(err);
            }
        }
    }, [videoId, autoplay, onReady, onPlay, onPause, onEnded, onTimeUpdate, onError]);

    return (
        <div className={`relative ${className}`}>
            {isLoading && (
                <div className="absolute inset-0 flex items-center justify-center bg-black bg-opacity-50 rounded-lg">
                    <div className="flex flex-col items-center gap-3">
                        <div className="w-12 h-12 border-4 border-white border-t-transparent rounded-full animate-spin"></div>
                        <p className="text-white text-sm font-medium">Loading video...</p>
                    </div>
                </div>
            )}

            {error && (
                <div className="absolute inset-0 flex items-center justify-center bg-red-50 rounded-lg border-2 border-red-200">
                    <div className="text-center p-6">
                        <i className="fas fa-exclamation-circle text-4xl text-red-500 mb-3"></i>
                        <p className="text-red-700 font-semibold mb-2">Failed to Load Video</p>
                        <p className="text-red-600 text-sm">{error}</p>
                    </div>
                </div>
            )}

            <div ref={containerRef} className="w-full"></div>
        </div>
    );
}
