<?php

namespace App\Enums;

use OCMS\Enum\Enum;

/**
 * @method static static PRIMARY_MENU()
 * @method static static SECONDARY_MENU()
 * @method static static TOP_MENU()
 * @method static static FOOTER_MENU_1()
 * @method static static FOOTER_MENU_2()
 * @method static static FOOTER_MENU_3()
 * @method static static FOOTER_MENU_4()
 */
final class MenuPositionEnum extends Enum
{
    const PRIMARY_MENU     =   0;
    const SECONDARY_MENU   =   1;
    const TOP_MENU         =   2;
    const FOOTER_MENU_1    =   3;
    const FOOTER_MENU_2    =   4;
    const FOOTER_MENU_3    =   5;
    const FOOTER_MENU_4    =   6;
}
