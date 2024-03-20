<?php

namespace OCMS\Larinfo;

use Illuminate\Support\Facades\Facade;

class LarinfoFacade extends Facade
{
    protected static function getFacadeAccessor()
    {
        return LarinfoContract::class;
    }
}
