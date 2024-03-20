<?php

namespace App\Enums;

use OCMS\Enum\Enum;

/**
 * @method static static UNKNOWN()
 */
final class ActivityActionEnum extends Enum
{
    const UNKNOWN     = 'unknown';

    public function getText($admin)
    {
        return __('enums.'.$this->value, ['name' => $admin->name]);
    }
}
