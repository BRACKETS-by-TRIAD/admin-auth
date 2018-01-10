<?php

namespace Brackets\AdminAuth\Listeners;

use Brackets\AdminAuth\Facades\Activation;
use Brackets\AdminAuth\Services\ActivationService;
use Illuminate\Events\Dispatcher;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Schema;

class ActivationListener
{
    /**
     * Register the listeners for the subscriber.
     *
     * @param Illuminate\Events\Dispatcher|Dispatcher $events
     */
    public function subscribe(Dispatcher $events)
    {
        $userClass = Activation::broker()->getUserModelClass();
        $interfaces = class_implements($userClass);
        if(($interfaces && in_array(\Brackets\AdminAuth\Contracts\Auth\CanActivate::class, $interfaces))) {
            $events->listen(
                'eloquent.created: ' . $userClass,
                ActivationService::class
            );
        }

        //TODO listen on user edit and if email has changed, deactivate user and send email again
    }
}
