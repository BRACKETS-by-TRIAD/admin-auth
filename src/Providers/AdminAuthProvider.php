<?php namespace Brackets\AdminAuth\Providers;

use Brackets\AdminAuth\Auth\Activations\ActivationServiceProvider;
use Brackets\AdminAuth\Console\Generate\GenerateProfile;
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
        $this->publishes([
            __DIR__.'/../../install-stubs/config/admin-auth.php' => config_path('admin-auth.php'),
        ]);

        //TODO publish or load?
        if (! class_exists('ModifyUsersTable')) {
            $timestamp = date('Y_m_d_His', time());

            $this->publishes([
                __DIR__.'/../../install-stubs/database/migrations/modify_users_table.php' => database_path('migrations').'/'.$timestamp.'_modify_users_table.php',
            ], 'migrations');
        }

        $this->publishes([
            __DIR__.'/../../install-stubs/resources/lang' => resource_path('lang/vendor/admin-auth'),
        ]);

//        $this->publishes([
//            __DIR__.'/../../install-stubs/resources/assets' => resource_path('assets')
//        ], 'assets');
//
//        $this->publishes([
//            __DIR__.'/../../install-stubs/resources/views' => resource_path('views')
//        ], 'views');

        $this->loadTranslationsFrom(__DIR__.'/../../resources/lang', 'admin-auth');
        $this->loadMigrationsFrom(__DIR__ . '/../../database/migrations');
        $this->loadViewsFrom(__DIR__ . '/../../resources/views', 'brackets/admin-auth');
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

        if(config('admin-auth.use-routes', true)) {
            $this->loadRoutesFrom(__DIR__.'/../../routes/web.php');
        }

        if(config('admin-auth.use-routes', true) && config('admin-auth.activation-form-visible', true)) {
            $this->loadRoutesFrom(__DIR__.'/../../routes/activation-form.php');
        }

        $loader = \Illuminate\Foundation\AliasLoader::getInstance();
        $loader->alias('Activation', Activation::class);
    }
}
