<?php

namespace App\Enums;

use OCMS\Enum\Enum;

/**
 * @method static static DEFAULT()
 * @method static static PARTIAL()
 */
final class ContentTypeEnum extends Enum
{
    const DEFAULT = 'default';
    const PARTIAL = 'partial';
}
