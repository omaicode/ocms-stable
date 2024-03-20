<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class RepositoryServiceProvider extends ServiceProvider
{
    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register()
    {
        $this->app->bind(\App\Contracts\AdminPage::class, \App\Supports\AdminPage::class);
        $this->app->bind(\App\Models\Repositories\Interfaces\AdminActivityRepository::class, \App\Models\Repositories\Eloquents\AdminActivityRepositoryEloquent::class);
        $this->app->bind(\App\Models\Repositories\Interfaces\MenuRepository::class, \App\Models\Repositories\Eloquents\MenuRepositoryEloquent::class);
        $this->app->bind(\App\Models\Repositories\Interfaces\ContactRepository::class, \App\Models\Repositories\Eloquents\ContactRepositoryEloquent::class);
        $this->app->bind(\App\Models\Repositories\Interfaces\LanguageRepository::class, \App\Models\Repositories\Eloquents\LanguageRepositoryEloquent::class);
        $this->app->bind(\App\Models\Repositories\Interfaces\TranslateRepository::class, \App\Models\Repositories\Eloquents\TranslateRepositoryEloquent::class);
        $this->app->bind(\App\Models\Repositories\Interfaces\PageRepository::class, \App\Models\Repositories\Eloquents\PageRepositoryEloquent::class);
        $this->app->bind(\App\Models\Repositories\Interfaces\CategoryRepository::class, \App\Models\Repositories\Eloquents\CategoryRepositoryEloquent::class);
        $this->app->bind(\App\Models\Repositories\Interfaces\PostRepository::class, \App\Models\Repositories\Eloquents\PostRepositoryEloquent::class);
        $this->app->bind(\App\Models\Repositories\Interfaces\PopupRepository::class, \App\Models\Repositories\Eloquents\PopupRepositoryEloquent::class);
        $this->app->bind(\App\Models\Repositories\Interfaces\ClientRepository::class, \App\Models\Repositories\Eloquents\ClientRepositoryEloquent::class);
        $this->app->bind(\App\Models\Repositories\Interfaces\BulletinBoardRepository::class, \App\Models\Repositories\Eloquents\BulletinBoardRepositoryEloquent::class);
        $this->app->bind(\App\Models\Repositories\Interfaces\BulletinBoardPostRepository::class, \App\Models\Repositories\Eloquents\BulletinBoardPostRepositoryEloquent::class);
        //:end-bindings:

        $this->app->bind("Popup", function() { 
            return app(\App\Models\Repositories\Interfaces\PopupRepository::class); 
        });
    }

    /**
     * Get the services provided by the provider.
     *
     * @return array
     */
    public function provides()
    {
        return [];
    }
}
