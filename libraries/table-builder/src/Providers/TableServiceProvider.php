<?php
namespace OCMS\TableBuilder\Providers;

use Illuminate\Support\Facades\Config;
use Illuminate\Support\ServiceProvider;

class TableServiceProvider extends ServiceProvider
{
    public function boot()
    {
        $this->registerConfig();
        $this->registerTranslations();
        $this->registerViews();
        $this->registerAssets();
    }

    public function register()
    {
        $this->commands([
            \OCMS\TableBuilder\Commands\MakeTable::class
        ]);
    }

    /**
     * Register package's namespaces.
     */
    protected function registerConfig()
    {
        $configPath = __DIR__ . '/../../config/config.php';

        $this->publishes([
            $configPath => config_path('table_builder.php'),
        ], 'config');

        $this->mergeConfigFrom($configPath, 'table_builder');
    }    

    /**
     * Register translations.
     *
     * @return void
     */
    public function registerTranslations()
    {
        $langPath = resource_path('lang/table-builder');

        if (is_dir($langPath)) {
            $this->loadTranslationsFrom($langPath, 'omc');
        } else {
            $this->loadTranslationsFrom(__DIR__ . '/../../resources/lang', 'omc');
        }
    }    

    /**
     * Register views.
     *
     * @return void
     */
    public function registerViews()
    {
        $viewPath = resource_path('vendor/views/table-builder');
        $sourcePath = __DIR__ . '/../../resources/views';

        $this->publishes([
            $sourcePath => $viewPath
        ], ['views', 'table-builder-view']);

        $this->loadViewsFrom($sourcePath, 'omc');
    }    

    public function registerAssets()
    {
        $this->publishes([
            __DIR__.'/../../resources/assets' => public_path('vendor/table-builder'),
        ], ['laravel-assets', 'table-builder-assets']);        
    }
}