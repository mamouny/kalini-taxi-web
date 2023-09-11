<?php

namespace App\Http\Enums;

enum DriverStateEnum : int{
    case INITIAL = 1;
    case VALIDATED = 2;
    case VALIDATED_ON_RIDE = 3;
}
