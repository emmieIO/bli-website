<?php

namespace App\Services;

use MuxPhp\Api\DirectUploadsApi;
use MuxPhp\Models\CreateUploadRequest;
use MuxPhp\Models\CreateAssetRequest;
use MuxPhp\Models\PlaybackPolicy;

class MuxVideoService
{
    /**
     * Create a new class instance.
     */
    public function __construct(private DirectUploadsApi $directUploadsApi)
    {
        //
    }

    public function createDirectUpload()
    {
        // Correct: flat array of playback policy values
        $createAssetRequest = new CreateAssetRequest([
            "playback_policy" => [PlaybackPolicy::_PUBLIC],
        ]);

        $request = new CreateUploadRequest([
            "timeout" => 3600,
            "new_asset_settings" => $createAssetRequest,
            "cors_origin" => "*",
        ]);

        // Call the SDK method
        $upload = $this->directUploadsApi->createDirectUpload($request);

        return $upload->getData(); // usually returns object with id + upload_url
    }
}
