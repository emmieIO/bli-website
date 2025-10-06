<?php

namespace App\Providers;

use GuzzleHttp\Client;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\ServiceProvider;
use MuxPhp\Api\AssetsApi;
use MuxPhp\Api\DirectUploadsApi;
use MuxPhp\Configuration;
class MuxServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        $this->app->singleton(DirectUploadsApi::class, function () {
            $config = Configuration::getDefaultConfiguration()
                ->setUsername(config('mux.mux_token_id'))
                ->setPassword(config('mux.mux_token_secret'));

            $client = new Client();

            return new DirectUploadsApi($client, $config);
        });
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {


    }
}
