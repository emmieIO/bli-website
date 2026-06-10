<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Setting;
use Illuminate\Http\Request;
use Inertia\Inertia;

class SettingsController extends Controller
{
    public function edit()
    {
        return Inertia::render('Admin/Settings', [
            'settings' => [
                'contact_email' => Setting::get('contact_email', env('CONTACT_EMAIL', '')),
                'contact_phone' => Setting::get('contact_phone', env('CONTACT_PHONE', '')),
                'contact_address' => Setting::get('contact_address', env('CONTACT_ADDRESS', '')),
            ],
        ]);
    }

    public function update(Request $request)
    {
        $validated = $request->validate([
            'contact_email' => 'required|email|max:255',
            'contact_phone' => 'required|string|max:50',
            'contact_address' => 'required|string|max:500',
        ]);

        foreach ($validated as $key => $value) {
            Setting::set($key, $value);
        }

        return back()->with([
            'type' => 'success',
            'message' => 'Settings updated successfully.',
        ]);
    }
}
