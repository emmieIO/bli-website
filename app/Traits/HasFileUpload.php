<?php

namespace App\Traits;

use Illuminate\Http\Request;
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
    public function uploadfile(Request $request, string $request_name, string $path, $disk_type='public')
    {
        $file_path = null;

        try {
            if ($request->hasFile($request_name)) {
            $file = $request->file($request_name);
            $file_path = $file->store($path, $disk_type);
            }

            return $file_path;
        } catch (\Exception $e) {
            if (!empty($file_path) && Storage::disk($disk_type)->exists($file_path)) {
            Storage::disk($disk_type)->delete($file_path);
            }

            Log::error('File upload failed', [
            'error' => $e->getMessage(),
            'request_name' => $request_name,
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
