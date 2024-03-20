<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use App\Contracts\AdminPage;
use App\Supports\Config;
use Theme;

class ThemeController extends Controller
{
    protected $request;
    protected $adminPage;

    public function __construct(Request $request, AdminPage $adminPage)
    {
        $this->request   = $request;
        $this->adminPage = $adminPage;
    }

    /**
     * Display a listing of the resource.
     * @return Renderable
     */
    public function index()
    {
        $themes = Theme::all();
        $current_theme = Theme::current();

        $breadcrumb = [
            [
                'title'  => __('messages.appearance'), 
                'url'    => '#',
            ],
            [
                'title'  => __('messages.themes'), 
                'url'    => route('admin.appearance.themes'),
            ]
        ];           

        return $this->adminPage
        ->title(__('messages.themes'))
        ->breadcrumb($breadcrumb)
        ->body('admin.pages.themes', compact('themes', 'current_theme'));
    }

    public function setTheme()
    {
        $theme = $this->request->get('theme');

        if(!Theme::has($theme)) {
            return redirect()->route('admin.appearance.themes')->with('toast_error', __('messages.theme_not_exists'));
        }

        Config::set('appearance__currentTheme', $theme);
        Theme::set($theme);

        return redirect()->route('admin.appearance.themes')->with('toast_success', __('messages.changed_theme_success'));
    }
}
