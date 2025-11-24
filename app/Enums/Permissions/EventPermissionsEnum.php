<?php

namespace App\Enums\Permissions;

enum EventPermissionsEnum: string
{
    // Admin Event Permissions
    case VIEW_ANY = 'event-view-any';
    case CREATE = 'event-create';
    case UPDATE_ANY = 'event-update-any';
    case DELETE_ANY = 'event-delete-any';
    case MANAGE_ATTENDEES = 'event-manage-attendees';
    case MANAGE_SPEAKERS = 'event-manage-speakers';
    case CANCEL = 'event-cancel';

    // User Event Permissions
    case VIEW = 'event-view';
    case REGISTER = 'event-register';
    case APPLY_TO_SPEAK = 'event-apply-to-speak';
}
