<?php

namespace App\Providers;

use App\Supports\PPurio;
use Illuminate\Support\ServiceProvider;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\Event;
use Illuminate\Routing\Events\RouteMatched;

use App\Facades\Email;
use App\Facades\Menu;
use Illuminate\Support\Facades\File;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {

    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        \App\Supports\Config::load();

        $this->registerEmailTemplates();
        $this->loadMenus();

        Paginator::useBootstrap();

        $this->app->bind(\App\Media\Abstracts\MediaAbstract::class, \App\Media\Support\Retriever::class);
        $this->app->bind(\App\Media\Abstracts\MediaAbstract::class, \App\Media\Support\Uploader::class);

        $this->app->singleton('ppurio', function () {
            $base_url = $this->app['config']->get('ppurio.base_url');
            $phone_from = $this->app['config']->get('ppurio.phone_from');
            $phone_to = $this->app['config']->get('ppurio.phone_to');
            $account = $this->app['config']->get('ppurio.account');
            $access_key = $this->app['config']->get('ppurio.access_key');

            return new PPurio(
                $base_url,
                $phone_from,
                $phone_to,
                $account,
                $access_key
            );
        });

        if(!config('app.debug')) {
            \Debugbar::disable();
        }
    }

    public function registerEmailTemplates()
    {
        $templates_path = base_path("settings/email_templates.php");

        if(File::exists($templates_path)) {
            $templates = include $templates_path;
            foreach($templates as $name => $data) {
                if(is_string($name) && !blank($data) && is_array($data)) {
                    Email::addTemplate($name, $data);
                }
            }
        }
    }

    public function loadMenus()
    {
        Event::listen(RouteMatched::class, function() {
            $menu_path = base_path("settings/menu.php");
            if(File::exists($menu_path)) {
                $menus = include $menu_path;
                foreach($menus as $menu) {
                    Menu::add($menu);
                }
            }
        });
    }
}
