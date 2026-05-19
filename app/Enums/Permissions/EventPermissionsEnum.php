<?php

namespace App\Enums\Permissions;

enum EventPermissionsEnum: string
{
    // Public and user event permissions
    case VIEW = 'event-view';
    case REGISTER = 'event-register';
    case VIEW_OWN_REGISTRATION = 'event-view-own-registration';
    case JOIN_WAITLIST = 'event-join-waitlist';
    case APPLY_TO_SPEAK = 'event-apply-to-speak';

    // Event manager and admin permissions
    case VIEW_ANY = 'event-view-any';
    case CREATE = 'event-create';
    case UPDATE_ANY = 'event-update-any';
    case DELETE_ANY = 'event-delete-any';
    case PUBLISH = 'event-publish';
    case MANAGE_ATTENDEES = 'event-manage-attendees';
    case MANAGE_WAITLIST = 'event-manage-waitlist';
    case MANAGE_SPEAKERS = 'event-manage-speakers';
    case MANAGE_RESOURCES = 'event-manage-resources';
    case VIEW_PAYMENTS = 'event-view-payments';
    case SEND_UPDATES = 'event-send-updates';
    case CANCEL = 'event-cancel';
    case ARCHIVE = 'event-archive';
}
