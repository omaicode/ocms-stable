<?php

namespace App\Enums;

use OCMS\Enum\Contracts\LocalizedEnum;
use OCMS\Enum\Enum;

/**
 * @method static static DRAFT()
 * @method static static PUBLISH()
 */
final class PageStatusEnum extends Enum implements LocalizedEnum
{
    const DRAFT   =   0;
    const PUBLISH =   1;
}
