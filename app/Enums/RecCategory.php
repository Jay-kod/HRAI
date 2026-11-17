<?php

namespace App\Enums;

enum RecCategory: string
{
    case Activity = 'activity';
    case Nutrition = 'nutrition';
    case Sleep = 'sleep';
    case Medical = 'medical';
    case Monitoring = 'monitoring';
}
