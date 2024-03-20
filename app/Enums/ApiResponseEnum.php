<?php

namespace App\Enums;

use OCMS\Enum\Enum;

/**
 * @method static static SUCCESS( )
 * @method static static VALIDATION( )
 * @method static static SERVER_ERROR()
 * @method static static UNAUTHORIZED()
 */
final class ApiResponseEnum extends Enum
{
    const SUCCESS      =  0;
    const VALIDATION   =  1;
    const SERVER_ERROR = -1;
    const UNAUTHORIZED =  2;
}
