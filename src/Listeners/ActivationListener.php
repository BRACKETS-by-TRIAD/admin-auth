<?php

namespace Brackets\AdminAuth\Listeners;

use Brackets\AdminAuth\Contracts\Auth\CanActivate as CanActivateContract;
use Brackets\AdminAuth\Facades\Activation;
use Brackets\AdminAuth\Services\ActivationService;
use Illuminate\Events\Dispatcher;

class ActivationListener
{
    /**
     * Register the listeners for the subscriber.
     *
     * @param Illuminate\Events\Dispatcher|Dispatcher $events
     * @param CanActivateContract $user
     */
    public function subscribe(Dispatcher $events)
    {
        $events->listen(
            'eloquent.created: ' . Activation::broker()->getUserModelClass(),
            ActivationService::class
        );

        //TODO listen on user edit and if email has changed, deactivate user and send email again
    }
}
