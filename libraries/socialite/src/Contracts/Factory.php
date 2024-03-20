<?php

namespace OCMS\Socialite\Contracts;

interface Factory
{
    /**
     * Get an OAuth provider implementation.
     *
     * @param  string  $driver
     * @return \OCMS\Socialite\Contracts\Provider
     */
    public function driver($driver = null);
}
