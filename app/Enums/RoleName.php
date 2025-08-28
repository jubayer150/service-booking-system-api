<?php

declare(strict_types=1);

namespace App\Enums;

enum RoleName: string
{
    /*
     * Changing any role name requires manual change of rows in roles table or running fresh migration
    */
    case ADMIN = 'admin';

    case CUSTOMER = 'customer';
}
