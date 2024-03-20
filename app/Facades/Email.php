<?php
namespace App\Facades;

use Illuminate\Support\Facades\Facade;
use App\Supports\Email as SupportsEmail;

class Email extends Facade
{
    /**
     * @return string
     */
    protected static function getFacadeAccessor()
    {
        return SupportsEmail::class;
    }
}
