<?php namespace Brackets\AdminAuth;

use Brackets\AdminAuth\Auth\Activations\ActivationServiceProvider;
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
        $this->loadTranslationsFrom(__DIR__ . '/../resources/lang', 'brackets/admin-auth');
        $this->loadMigrationsFrom(__DIR__ . '/../database/migrations');
        $this->loadViewsFrom(__DIR__ . '/../resources/views', 'brackets/admin-auth');

        if ($this->app->runningInConsole()) {
            $this->publishes([
                __DIR__ . '/../install-stubs/config/admin-auth.php' => config_path('admin-auth.php'),
            ], 'config');

            if (! class_exists('ModifyUsersTable')) {
                $timestamp = date('Y_m_d_His', time());

                $this->publishes([
                    __DIR__ . '/../install-stubs/database/migrations/modify_users_table.php' => database_path('migrations').'/'.$timestamp.'_modify_users_table.php',
                ], 'migrations');
            }

            $this->publishes([
                __DIR__ . '/../install-stubs/resources/lang' => resource_path('lang/vendor/admin-auth'),
            ], 'lang');
        }

        $this->app->register(ActivationServiceProvider::class);
        $this->app->register(EventServiceProvider::class);
        app(\Illuminate\Routing\Router::class)->aliasMiddleware('admin', Admin::class);
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

        if(config('admin-auth.use-routes', true)) {
            $this->loadRoutesFrom(__DIR__ . '/../routes/web.php');
        }

        if(config('admin-auth.use-routes', true) && config('admin-auth.activations.self-activation-form-enabled', true)) {
            $this->loadRoutesFrom(__DIR__ . '/../routes/activation-form.php');
        }

        $loader = \Illuminate\Foundation\AliasLoader::getInstance();
        $loader->alias('Activation', Activation::class);

        app(\Illuminate\Routing\Router::class)->pushMiddlewareToGroup('web', ApplyUserLocale::class);
    }
}
