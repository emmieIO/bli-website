<?php

namespace App\Traits;


use Illuminate\Http\Request;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

trait HasFileUpload
{
     /* Upload a file and store it in the specified path on the public disk.
     *
     * @param UploadedFile $file The file to upload
     * @param string $path The storage path
     * @param string $disk_type The disk type (default: 'public')
     * @param array $allowedMimes Optional array of allowed MIME types
     * @param int $maxSizeMB Maximum file size in MB (default: 10)
     * @return string|false The file path or false on failure
     */
    public function uploadFile(
        UploadedFile $file,
        string $path,
        $disk_type = 'public',
        array $allowedMimes = [],
        int $maxSizeMB = 10
    ) {
        $file_path = null;

        try {
            if (!$file) {
                return false;
            }

            // Security: Validate file size
            $maxSizeBytes = $maxSizeMB * 1024 * 1024;
            if ($file->getSize() > $maxSizeBytes) {
                throw new \Exception("File size exceeds maximum allowed size of {$maxSizeMB}MB");
            }

            // Security: Validate MIME type if specified
            if (!empty($allowedMimes)) {
                $mimeType = $file->getMimeType();
                if (!in_array($mimeType, $allowedMimes)) {
                    throw new \Exception("File type not allowed. Allowed types: " . implode(', ', $allowedMimes));
                }
            }

            // Security: Validate file extension
            $extension = strtolower($file->getClientOriginalExtension());
            $dangerousExtensions = ['php', 'phtml', 'php3', 'php4', 'php5', 'sh', 'exe', 'bat', 'cmd', 'jar', 'jsp'];
            if (in_array($extension, $dangerousExtensions)) {
                throw new \Exception("File extension '{$extension}' is not allowed for security reasons");
            }

            // Generate safe filename to prevent directory traversal attacks
            $safeFilename = Str::random(40) . '.' . $extension;
            $file_path = $file->storeAs($path, $safeFilename, $disk_type);

            return $file_path;
        } catch (\Exception $e) {
            // Clean up if file was partially uploaded
            if (!empty($file_path) && Storage::disk($disk_type)->exists($file_path)) {
                Storage::disk($disk_type)->delete($file_path);
            }

            Log::error('File upload failed', [
                'error' => $e->getMessage(),
                'file' => $file->getClientOriginalName(),
                'mime_type' => $file->getMimeType() ?? 'unknown',
                'size' => $file->getSize() ?? 0,
            ]);

            return false;
        }
    }

    public function deleteFile(string $file_path, $disk_type = 'public')
    {
        if (!empty($file_path) && Storage::disk($disk_type)->exists($file_path)) {
            Storage::disk($disk_type)->delete($file_path);
            return true;
        }
        Log::error('File deletion failed', [
            'file_path' => $file_path,
            'disk_type' => $disk_type,
        ]);
        return false;
    }
}
