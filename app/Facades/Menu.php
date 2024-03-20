<?php
namespace App\Facades;

use Illuminate\Support\Facades\Facade;
use App\Supports\Menu as SupportsMenu;

class Menu extends Facade
{
    /**
     * @return string
     */
    protected static function getFacadeAccessor()
    {
        return SupportsMenu::class;
    }
}
