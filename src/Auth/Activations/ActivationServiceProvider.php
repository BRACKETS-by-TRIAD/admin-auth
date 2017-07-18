<?php

namespace Brackets\AdminAuth\Auth\Activations;

use Illuminate\Support\ServiceProvider;

class ActivationServiceProvider extends ServiceProvider
{
    /**
     * Indicates if loading of the provider is deferred.
     *
     * @var bool
     */
    protected $defer = true;

    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register()
    {
        $this->registerActivationBroker();
    }

    /**
     * Register the password broker instance.
     *
     * @return void
     */
    protected function registerActivationBroker()
    {
        $this->app->singleton('auth.activation', function ($app) {
            return new ActivationBrokerManager($app);
        });

        $this->app->bind('auth.activation.broker', function ($app) {
            return $app->make('auth.activation')->broker();
        });
    }

    /**
     * Get the services provided by the provider.
     *
     * @return array
     */
    public function provides()
    {
        return ['auth.activation', 'auth.activation.broker'];
    }
}
