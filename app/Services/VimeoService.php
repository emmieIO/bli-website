<?php

namespace App\Services;

use Vimeo\Vimeo;
use Vimeo\Exceptions\VimeoUploadException;
use Illuminate\Support\Facades\Log;

class VimeoService
{
    protected Vimeo $client;

    /**
     * Create a new class instance.
     */
    public function __construct()
    {
        $this->client = new Vimeo(
            config('vimeo.connections.main.client_id'),
            config('vimeo.connections.main.client_secret'),
            config('vimeo.connections.main.access_token')
        );
    }

    public function getClient(): Vimeo
    {
        return $this->client;
    }

    /**
     * Upload video to Vimeo with error handling and privacy settings
     *
     * @param string $filePath Path to the video file
     * @param array $metadata Video metadata (name, description)
     * @param array $privacy Privacy settings
     * @return array ['success' => bool, 'uri' => string|null, 'video_id' => string|null, 'error' => string|null]
     */
    public function uploadVideo(string $filePath, array $metadata = [], array $privacy = []): array
    {
        try {
            // Validate file exists
            if (!file_exists($filePath)) {
                throw new \Exception("Video file not found at path: {$filePath}");
            }

            // Validate file size (check against Vimeo account limits)
            $fileSize = filesize($filePath);
            if ($fileSize === false) {
                throw new \Exception("Unable to determine file size");
            }

            // Default privacy settings: private video, not downloadable
            $defaultPrivacy = [
                'view' => 'disable', // Options: anybody, nobody, disable, unlisted, password, users, contacts
                'embed' => 'whitelist', // Options: public, private, whitelist
                'download' => false,
                'add' => false,
                'comments' => 'nobody',
            ];

            $privacySettings = array_merge($defaultPrivacy, $privacy);

            Log::info("Starting Vimeo upload", [
                'file_path' => $filePath,
                'file_size' => $fileSize,
                'metadata' => $metadata,
            ]);

            // Upload the video
            $uri = $this->client->upload($filePath, $metadata);

            if (!$uri) {
                throw new \Exception("Upload failed: No URI returned from Vimeo");
            }

            // Extract video ID from URI (e.g., "/videos/123456789" => "123456789")
            $videoId = $this->extractVideoId($uri);

            // Set privacy settings
            $response = $this->client->request($uri, $privacySettings, 'PATCH');

            if ($response['status'] !== 200) {
                Log::warning("Failed to set privacy settings for Vimeo video", [
                    'uri' => $uri,
                    'response' => $response,
                ]);
            }

            // Add allowed domains if whitelist embed is enabled
            if ($privacySettings['embed'] === 'whitelist') {
                $this->setAllowedDomains($uri);
            }

            Log::info("Vimeo upload successful", [
                'uri' => $uri,
                'video_id' => $videoId,
            ]);

            return [
                'success' => true,
                'uri' => $uri,
                'video_id' => $videoId,
                'error' => null,
            ];

        } catch (VimeoUploadException $e) {
            Log::error("Vimeo upload exception", [
                'file_path' => $filePath,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);

            return [
                'success' => false,
                'uri' => null,
                'video_id' => null,
                'error' => "Upload failed: {$e->getMessage()}",
            ];

        } catch (\Exception $e) {
            Log::error("Error uploading to Vimeo", [
                'file_path' => $filePath,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);

            return [
                'success' => false,
                'uri' => null,
                'video_id' => null,
                'error' => $e->getMessage(),
            ];
        }
    }

    /**
     * Extract video ID from Vimeo URI
     *
     * @param string $uri Vimeo URI (e.g., "/videos/123456789")
     * @return string Video ID
     */
    protected function extractVideoId(string $uri): string
    {
        $parts = explode('/', $uri);
        return end($parts);
    }

    /**
     * Set allowed domains for video embedding
     *
     * @param string $uri Video URI
     * @return void
     */
    protected function setAllowedDomains(string $uri): void
    {
        $allowedDomains = config('vimeo.allowed_domains', []);

        if (empty($allowedDomains)) {
            return;
        }

        foreach ($allowedDomains as $domain) {
            $response = $this->client->request(
                "{$uri}/privacy/domains/{$domain}",
                [],
                'PUT'
            );

            if ($response['status'] !== 204) {
                Log::warning("Failed to whitelist domain for Vimeo video", [
                    'uri' => $uri,
                    'domain' => $domain,
                    'response' => $response,
                ]);
            }
        }
    }

    /**
     * Get video information from Vimeo
     *
     * @param string $videoId Vimeo video ID
     * @return array|null
     */
    public function getVideoInfo(string $videoId): ?array
    {
        try {
            $response = $this->client->request("/videos/{$videoId}");

            if ($response['status'] === 200) {
                return $response['body'];
            }

            return null;
        } catch (\Exception $e) {
            Log::error("Error fetching Vimeo video info", [
                'video_id' => $videoId,
                'error' => $e->getMessage(),
            ]);

            return null;
        }
    }

    /**
     * Delete video from Vimeo
     *
     * @param string $videoId Vimeo video ID
     * @return bool
     */
    public function deleteVideo(string $videoId): bool
    {
        try {
            $response = $this->client->request("/videos/{$videoId}", [], 'DELETE');

            if ($response['status'] === 204) {
                Log::info("Vimeo video deleted successfully", ['video_id' => $videoId]);
                return true;
            }

            Log::warning("Failed to delete Vimeo video", [
                'video_id' => $videoId,
                'response' => $response,
            ]);

            return false;
        } catch (\Exception $e) {
            Log::error("Error deleting Vimeo video", [
                'video_id' => $videoId,
                'error' => $e->getMessage(),
            ]);

            return false;
        }
    }
}
