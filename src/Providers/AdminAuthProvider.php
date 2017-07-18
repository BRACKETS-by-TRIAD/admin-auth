<?php namespace Brackets\AdminAuth\Providers;

use Brackets\AdminAuth\Auth\Activations\ActivationServiceProvider;
use Brackets\AdminAuth\Console\Generate\GenerateUser;
use Brackets\AdminAuth\Facades\Activation;
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

        $this->publishes([
            __DIR__.'/../../install-stubs/resources/lang' => resource_path('lang/vendor/admin-auth'),
        ]);

        $this->loadTranslationsFrom(__DIR__.'/../../resources/lang', 'admin-auth');
        $this->loadMigrationsFrom(__DIR__ . '/../../database/migrations');
        $this->loadRoutesFrom(__DIR__.'/../../routes/web.php');
        $this->loadViewsFrom(__DIR__ . '/../../resources/views', 'brackets/admin-auth');

        $this->commands([
            GenerateUser::class,
        ]);

        $this->app->register(ActivationServiceProvider::class);
        $this->app->register(EventServiceProvider::class);
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->bind(
            'App\Http\Middleware\RedirectIfAuthenticated',
            'Brackets\AdminAuth\Http\Middleware\RedirectIfAuthenticated'
        );

        $this->app->bind(
            'Illuminate\Auth\Notifications\ResetPassword',
            'Brackets\AdminAuth\Notifications\ResetPassword'
        );

        $this->mergeConfigFrom(
            __DIR__.'/../../install-stubs/config/admin-auth.php', 'admin-auth'
        );

        $loader = \Illuminate\Foundation\AliasLoader::getInstance();
        $loader->alias('Activation', Activation::class);
    }
}
