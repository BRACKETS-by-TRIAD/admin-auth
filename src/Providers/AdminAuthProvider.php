<?php namespace Brackets\AdminAuth\Providers;

use Brackets\AdminAuth\Console\Generate\GenerateUser;
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

        $this->publishes([
            __DIR__.'/../../install-stubs/config/admin-auth.php' => config_path('admin-auth.php'),
        ]);

//        $this->loadMigrationsFrom(__DIR__ . '/../../install-stubs/database/migrations');
        $this->loadRoutesFrom(__DIR__.'/../../routes/web.php');
        $this->loadViewsFrom(__DIR__ . '/../../resources/views', 'brackets/admin-auth');

        $this->commands([
            GenerateUser::class,
        ]);
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        $this->mergeConfigFrom(
            __DIR__.'/../../install-stubs/config/admin-auth.php', 'admin-auth'
        );
    }
}
