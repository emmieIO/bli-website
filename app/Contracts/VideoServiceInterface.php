<?php

namespace App\Contracts;

interface VideoServiceInterface
{
    public function  uploadVideo($file): string;
    public function getuploadStatus($file): string;

}
