<?php namespace Brackets\AdminAuth\Providers;

use Brackets\AdminGenerator\Generate\ModelFactory;
use Illuminate\Support\ServiceProvider;

class AdminAuthProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
//        $this->publishes([
//            __DIR__.'/../../install-stubs/resources/assets' => resource_path('assets')
//        ], 'assets');
//
//        $this->publishes([
//            __DIR__.'/../../install-stubs/resources/views' => resource_path('views')
//        ], 'views');

        //TODO publish or load?
        $this->publishes([
            __DIR__.'/../../install-stubs/database/migrations' => database_path('migrations')
        ]);

//        $this->loadMigrationsFrom(__DIR__ . '/../../install-stubs/database/migrations');
//        $this->loadRoutesFrom(__DIR__.'/../../routes/web.php');
//        $this->loadViewsFrom(__DIR__ . '/../../resources/views', 'brackets/admin-auth');
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {

    }
}
