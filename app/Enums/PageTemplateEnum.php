<?php

namespace App\Enums;

use OCMS\Enum\Contracts\LocalizedEnum;
use OCMS\Enum\Enum;

/**
 * @method static static DEFAULT()
 * @method static static HOME()
 * @method static static BLANK()
 * @method static static BLOG()
 */
final class PageTemplateEnum extends Enum implements LocalizedEnum
{
    const DEFAULT =   0;
    const HOME    =   1;
    const BLANK   =   2;
    const BLOG   =   3;
}
