<?php

namespace App\Enums;

enum RecStatus: string
{
    case Pending = 'pending';
    case Done = 'done';
    case Dismissed = 'dismissed';
}
