<?php

namespace App\Http\Controllers\Events;

use App\Enums\EventRegistrationStatus;
use App\Http\Controllers\Controller;
use App\Models\Event;
use Illuminate\Support\Facades\Auth;

class OpenEventLinkController extends Controller
{
    public function __invoke(Event $event)
    {
        $user = Auth::user();

        if ($user) {
            $hasWorkspaceAccess = $user->events()
                ->where('events.id', $event->id)
                ->wherePivotIn('status', EventRegistrationStatus::workspaceAccessibleValues())
                ->exists();

            if ($hasWorkspaceAccess) {
                return redirect()->route('user.events.show', $event->slug);
            }
        }

        return redirect()->route('events.show', $event->slug);
    }
}
