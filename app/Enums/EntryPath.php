<?php

namespace App\Enums;

enum EntryPath: string
{
    case APPLICATION = 'application';
    case INVITATION = 'invitation';
    case BOTH = 'both';
}
