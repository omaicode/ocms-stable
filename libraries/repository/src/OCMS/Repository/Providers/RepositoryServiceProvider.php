<?php
namespace OCMS\Repository\Providers;

use Illuminate\Support\ServiceProvider;

/**
 * Class RepositoryServiceProvider
 * @package OCMS\Repository\Providers
 * @author Anderson Andrade <contato@andersonandra.de>
 */
class RepositoryServiceProvider extends ServiceProvider
{

    /**
     * Indicates if loading of the provider is deferred.
     *
     * @var bool
     */
    protected $defer = false;


    /**
     *
     * @return void
     */
    public function boot()
    {
        $this->publishes([
            __DIR__ . '/../../../resources/config/repository.php' => config_path('repository.php')
        ]);

        $this->mergeConfigFrom(__DIR__ . '/../../../resources/config/repository.php', 'repository');

        $this->loadTranslationsFrom(__DIR__ . '/../../../resources/lang', 'repository');
    }


    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register()
    {
        $this->commands('OCMS\Repository\Generators\Commands\RepositoryCommand');
        $this->commands('OCMS\Repository\Generators\Commands\TransformerCommand');
        $this->commands('OCMS\Repository\Generators\Commands\PresenterCommand');
        $this->commands('OCMS\Repository\Generators\Commands\EntityCommand');
        $this->commands('OCMS\Repository\Generators\Commands\ValidatorCommand');
        $this->commands('OCMS\Repository\Generators\Commands\ControllerCommand');
        $this->commands('OCMS\Repository\Generators\Commands\BindingsCommand');
        $this->commands('OCMS\Repository\Generators\Commands\CriteriaCommand');
        $this->app->register('OCMS\Repository\Providers\EventServiceProvider');
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
