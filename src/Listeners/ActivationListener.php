<?php

namespace Brackets\AdminAuth\Listeners;

use Brackets\AdminAuth\Services\ActivationService;
use Illuminate\Events\Dispatcher;

class ActivationListener
{
    /**
     * Register the listeners for the subscriber.
     *
     * @param Illuminate\Events\Dispatcher|Dispatcher $events
     */
    public function subscribe(Dispatcher $events)
    {
        $events->listen(
            'eloquent.created: App\Models\User',
            ActivationService::class
        );
    }
}
