<?php

namespace App\Enums;

enum EventModeEnum: string
{
    case ONLINE = 'online';
    case OFFLINE = 'offline';
    case HYBRID = 'hybrid';
}

