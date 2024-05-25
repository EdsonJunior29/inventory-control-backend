<?php

namespace App\Enums;

enum RoleType: int
{
    case ADMIN = 1;
    case CLIENT = 2;
    case COLABS = 3;
    
}