<?php
namespace App\Facades;

use Illuminate\Support\Facades\Facade;
use App\Supports\AdminAsset as SupportsAdminAsset;

class AdminAsset extends Facade
{
    /**
     * @return string
     */
    protected static function getFacadeAccessor()
    {
        return SupportsAdminAsset::class;
    }
}
