<?php

namespace App\Traits;


use Illuminate\Http\Request;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

trait HasFileUpload
{
     /* Upload a file and store it in the specified path on the public disk.
     *
     * @param Request $request
     * @param string $request_name
     * @param string $path
     * @return string|false
     */
    public function uploadFile(UploadedFile $file, string $path, $disk_type='public')
    {
        $file_path = null;

        try {
            if ($file) {
            $file_path = $file->store($path, $disk_type);
            }
            return $file_path;
        } catch (\Exception $e) {
            if (!empty($file_path) && Storage::disk($disk_type)->exists($file_path)) {
            Storage::disk($disk_type)->delete($file_path);
            }

            Log::error('File upload failed', [
            'error' => $e->getMessage(),
            'file' => $file->getClientOriginalName(),
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
