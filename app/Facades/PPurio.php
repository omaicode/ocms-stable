<?php
namespace App\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * @method static bool sendSMS(array $sms_data)
 */
class PPurio extends Facade
{
    /**
     * @return string
     */
    protected static function getFacadeAccessor()
    {
        return 'ppurio';
    }
}
