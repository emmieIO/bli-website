import { useEffect, useRef, memo } from 'react';
import Player from '@vimeo/player';

interface VimeoPlayerProps {
    videoUrl?: string | null;
    videoId?: string | number | null;
    onEnded?: () => void;
    onProgress?: (seconds: number) => void;
    autoplay?: boolean;
    className?: string;
}

function VimeoPlayer({ 
    videoUrl, 
    videoId, 
    onEnded, 
    onProgress, 
    autoplay = false,
    className = "w-full h-full bg-black rounded-lg overflow-hidden shadow-lg"
}: VimeoPlayerProps) {
    const containerRef = useRef<HTMLDivElement>(null);
    const playerRef = useRef<Player | null>(null);
    
    // Refs to store latest callbacks to avoid re-initializing player on callback change
    const onEndedRef = useRef(onEnded);
    const onProgressRef = useRef(onProgress);

    useEffect(() => {
        onEndedRef.current = onEnded;
        onProgressRef.current = onProgress;
    }, [onEnded, onProgress]);

    useEffect(() => {
        if (!containerRef.current) return;
        if (!videoUrl && !videoId) return;

        const options: Record<string, any> = {
            autoplay: autoplay,
            responsive: true,
            title: false,
            byline: false,
            portrait: false,
        };

        if (videoId) {
            options.id = Number(videoId);
        } else if (videoUrl) {
            options.url = videoUrl;
        }

        // Initialize Vimeo Player
        playerRef.current = new Player(containerRef.current, options);

        // Attach Event Listeners using refs
        playerRef.current.on('ended', () => {
            onEndedRef.current?.();
        });

        playerRef.current.on('timeupdate', (data) => {
            onProgressRef.current?.(data.seconds);
        });

        // Cleanup
        return () => {
            if (playerRef.current) {
                playerRef.current.destroy();
                playerRef.current = null;
            }
        };
    }, [videoUrl, videoId, autoplay]); // Removed callbacks from dependencies

    return (
        <div 
            ref={containerRef} 
            className={className}
        />
    );
}

export default memo(VimeoPlayer);