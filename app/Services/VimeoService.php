<?php

namespace App\Services;

use Vimeo\Vimeo;

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
}
