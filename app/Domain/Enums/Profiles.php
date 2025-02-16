<?php

namespace App\Domain\Enums;

enum Profiles: int
{
    case ADMIN = 1;
    case CLIENT = 2;
    case COLABS = 3;
}