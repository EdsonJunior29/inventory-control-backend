<?php

namespace App\Enums;

enum RoleType: int
{
    case ADMIN = 12;
    case COLABS = 4;
    case CLIENT = 1;
}