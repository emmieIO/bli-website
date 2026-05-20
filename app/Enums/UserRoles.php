<?php

namespace App\Enums;

enum UserRoles : string
{
    case SUPER_ADMIN = 'super-admin';
    case ADMIN = 'admin';
    case INSTRUCTOR = 'instructor';
    case SPEAKER = 'speaker';
    case STUDENT = 'student';
}
