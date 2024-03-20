<?php

namespace App\Events;

class ThemeEnabling
{
    /**
     * @var array|string
     */
    public $theme;

    /**
     * @param $theme
     */
    public function __construct($theme)
    {
        $this->theme = $theme;
    }
}
