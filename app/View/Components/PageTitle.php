<?php

declare(strict_types=1);

namespace App\View\Components;

use Illuminate\Support\Facades\View;
use Illuminate\View\Component;

class PageTitle extends Component
{
    /**
     * The page title.
     */
    public string $title;

    /**
     * Create the component instance.
     */
    public function __construct($title = null, $withAppName = true, $separator = '-', $invert = false)
    {
        if (View::hasSection('title')) {
            $title = View::getSection('title');
        }

        if (! empty($title) && $withAppName) {
            if ($invert) {
                $this->title = $title . ' ' . trim(e($separator)) . ' ' . config('app.name');
            } else {
                $this->title = config('app.name') . ' ' . trim(e($separator)) . ' ' . $title;
            }
        } else {
            $this->title = config('app.name');
        }
    }

    /**
     * Get the view / contents that represents the component.
     */
    public function render(): \Illuminate\View\View
    {
        return view('themes-manager.components.page-title');
    }
}