<?php

namespace App\Http\Middleware;

use App\Models\Setting;
use App\Support\PlatformModuleRegistry;
use Illuminate\Http\Request;
use Inertia\Middleware;
use Tighten\Ziggy\Ziggy;

class HandleInertiaRequests extends Middleware
{
    /**
     * The root template that's loaded on the first page visit.
     *
     * @see https://inertiajs.com/server-side-setup#root-template
     *
     * @var string
     */
    protected $rootView = 'app';

    /**
     * Determines the current asset version.
     *
     * @see https://inertiajs.com/asset-versioning
     */
    public function version(Request $request): ?string
    {
        return parent::version($request);
    }

    /**
     * Defines the props that are shared by default.
     *
     * @see https://inertiajs.com/shared-data
     */
    public function share(Request $request): array
    {
        return array_merge(parent::share($request), [
            'auth' => [
                'user' => $request->user() ? [
                    'id' => $request->user()->id,
                    'name' => $request->user()->name,
                    'email' => $request->user()->email,
                    'photo' => $request->user()->photo,
                    'roles' => $request->user()->getRoleNames(),
                    'permissions' => $request->user()->getAllPermissions()->pluck('name'),
                ] : null,
            ],
            'flash' => [
                'message' => fn () => $request->session()->get('message'),
                'type' => fn () => $request->session()->get('type'),
            ],
            'contact' => [
                'email' => Setting::get('contact_email', env('CONTACT_EMAIL', 'info@beaconleadership.org')),
                'phone' => Setting::get('contact_phone', env('CONTACT_PHONE', '+234-706-442-5639')),
                'address' => Setting::get('contact_address', env('CONTACT_ADDRESS', '123 Beacon Avenue, Lagos, Nigeria')),
            ],
            'ziggy' => fn () => array_merge((new Ziggy)->toArray(), [
                'location' => $request->url(),
            ]),
            'sideLinks' => fn () => app(PlatformModuleRegistry::class)->sideLinks($request->user()),
        ]);
    }
}
