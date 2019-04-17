<?php

namespace App\Entity;

use App\Annotation\InternalUser;

class Employee extends User
{
    /**
     * @var string
     */
    const ROLE_EMPLOYEE = 'ROLE_EMPLOYEE';
}
