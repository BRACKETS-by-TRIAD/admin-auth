<?php namespace Brackets\AdminAuth;

use Brackets\AdminAuth\Auth\Activations\ActivationServiceProvider;
use Brackets\AdminAuth\Console\Commands\AdminAuthInstall;
use Brackets\AdminAuth\Facades\Activation;
use Brackets\AdminAuth\Http\Middleware\Admin;
use Brackets\AdminAuth\Http\Middleware\ApplyUserLocale;
use Brackets\AdminAuth\Providers\EventServiceProvider;
use Illuminate\Support\ServiceProvider;

class AdminAuthServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        $this->commands([
            AdminAuthInstall::class,
        ]);

        $this->loadTranslationsFrom(__DIR__ . '/../resources/lang', 'brackets/admin-auth');
        $this->loadMigrationsFrom(__DIR__ . '/../database/migrations');
        $this->loadViewsFrom(__DIR__ . '/../resources/views', 'brackets/admin-auth');

        if ($this->app->runningInConsole()) {
            $this->publishes([
                __DIR__ . '/../install-stubs/config/admin-auth.php' => config_path('admin-auth.php'),
            ], 'config');

            if (!glob(base_path('database/migrations/*_modify_users_table.php'))) {
                $this->publishes([
                    __DIR__ . '/../install-stubs/database/migrations/modify_users_table.php' => database_path('migrations').'/2017_08_24_000000_modify_users_table.php',
                ], 'migrations');
            }

            $this->publishes([
                __DIR__ . '/../install-stubs/resources/lang' => resource_path('lang/vendor/admin-auth'),
            ], 'lang');
        }

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
            __DIR__ . '/../install-stubs/config/admin-auth.php', 'admin-auth'
        );

        if(config('admin-auth.use_routes', true)) {
            $this->loadRoutesFrom(__DIR__ . '/../routes/web.php');
        }

        if(config('admin-auth.use_routes', true) && config('admin-auth.activations.self_activation_form_enabled', true)) {
            $this->loadRoutesFrom(__DIR__ . '/../routes/activation-form.php');
        }

        $loader = \Illuminate\Foundation\AliasLoader::getInstance();
        $loader->alias('Activation', Activation::class);

        app(\Illuminate\Routing\Router::class)->pushMiddlewareToGroup('web', ApplyUserLocale::class);
        app(\Illuminate\Routing\Router::class)->pushMiddlewareToGroup('admin', Admin::class);
    }
}
