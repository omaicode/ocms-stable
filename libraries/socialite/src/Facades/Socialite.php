<?php

namespace OCMS\Socialite\Facades;

use Illuminate\Support\Facades\Facade;
use OCMS\Socialite\Contracts\Factory;

/**
 * @method static \OCMS\Socialite\Contracts\Provider driver(string $driver = null)
 * @method static \OCMS\Socialite\Two\AbstractProvider buildProvider($provider, $config)
 *
 * @see \OCMS\Socialite\SocialiteManager
 */
class Socialite extends Facade
{
    /**
     * Get the registered name of the component.
     *
     * @return string
     */
    protected static function getFacadeAccessor()
    {
        return Factory::class;
    }
}
