<?php

namespace App\Services;

use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\Request;

class Misc
{
    /**
     * Upload a file and store it in the specified path on the public disk.
     *
     * @param Request $request
     * @param string $request_name
     * @param string $path
     * @return string|false
     */
    public function uploadfile(Request $request, string $request_name, string $path)
    {
        $file_path = null;

        try {
            if ($request->hasFile($request_name)) {
                $file = $request->file($request_name);
                $file_path = $file->store($path, 'public');
            }

            return $file_path;
        } catch (\Exception $e) {
            if (!empty($file_path) && Storage::disk('public')->exists($file_path)) {
                Storage::disk('public')->delete($file_path);
            }

            Log::error('File upload failed', [
                'error' => $e->getMessage(),
                'request_name' => $request_name,
            ]);

            return false;
        }
    }
}
