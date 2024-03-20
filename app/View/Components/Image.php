<?php

declare(strict_types=1);

namespace App\View\Components;

use App\Facades\ThemesManager;
use Illuminate\View\Component;

class Image extends Component
{
    /**
     * The style source url.
     */
    public string $source;

    /**
     * Create the component instance.
     */
    public function __construct(string $src, bool $absolute = true)
    {
        $this->source = ThemesManager::asset($src, $absolute);
    }

    /**
     * Get the view / contents that represents the component.
     */
    public function render(): \Illuminate\View\View
    {
        return view('themes-manager.components.image');
    }
}