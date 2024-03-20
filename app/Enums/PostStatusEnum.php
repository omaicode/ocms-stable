<?php

namespace App\Enums;

use OCMS\Enum\Contracts\LocalizedEnum;
use OCMS\Enum\Enum;

/**
 * @method static static PENDING()
 * @method static static PUBLISHED()
 * @method static static REJECTED()
 */
final class PostStatusEnum extends Enum implements LocalizedEnum
{
    const PENDING = 'pending';
    const PUBLISHED = 'published';
    const REJECTED = 'rejected';

    public function toHtml() : string
    {
        $name = self::getDescription($this->value);
        switch($this->value) {
            case PostStatusEnum::PENDING:
                return "<span class='text-info'>{$name}</span>";
            case PostStatusEnum::PUBLISHED:
                return "<span class='text-success'>{$name}</span>";
            case PostStatusEnum::REJECTED:
                return "<span class='text-danger'>{$name}</span>";
            default:
                return "<span class='text-dark'>N/A</span>";
        }
    }
}
