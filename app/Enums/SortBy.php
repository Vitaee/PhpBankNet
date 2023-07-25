<?php

namespace App\Enums;

use Illuminate\Validation\Rules\Enum;

/*
    Enum of the course status
*/

class SortBy extends Enum
{
    const UNDEFINED = -1;
    const LASTUPDATED = 0;
    const FIRSTUPDATED = 1;
    const ATOZ = 2;
    const ZTOA = 3;
    const CREATIONDATE_DESC = 4;
    const CREATIONDATE_ASC = 5;
}
