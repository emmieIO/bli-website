import { useEffect, useRef } from 'react';
import Player from '@vimeo/player';

interface VimeoPlayerProps {
    videoUrl?: string | null;
    videoId?: string | number | null;
    onEnded?: () => void;
    onProgress?: (seconds: number) => void;
    autoplay?: boolean;
    className?: string;
}

export default function VimeoPlayer({ 
    videoUrl, 
    videoId, 
    onEnded, 
    onProgress, 
    autoplay = false,
    className = "w-full h-full bg-black rounded-lg overflow-hidden shadow-lg"
}: VimeoPlayerProps) {
    const containerRef = useRef<HTMLDivElement>(null);
    const playerRef = useRef<Player | null>(null);

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

        // Attach Event Listeners
        if (onEnded) {
            playerRef.current.on('ended', onEnded);
        }

        if (onProgress) {
            playerRef.current.on('timeupdate', (data) => {
                onProgress(data.seconds);
            });
        }

        // Cleanup
        return () => {
            if (playerRef.current) {
                playerRef.current.destroy();
                playerRef.current = null;
            }
        };
    }, [videoUrl, videoId, autoplay, onEnded, onProgress]);

    return (
        <div 
            ref={containerRef} 
            className={className}
        />
    );
}