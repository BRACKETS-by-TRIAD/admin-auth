<?php

namespace Brackets\AdminAuth\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * @see \Brackets\AdminAuth\Auth\Activations\ActivationBroker
 */
class Activation extends Facade
{
    /**
     * Constant representing a successfully sent reminder.
     *
     * @var string
     */
    const ACTIVATION_LINK_SENT = 'admin-auth::activations.sent';

    /**
     * Constant representing a successfully reset password.
     *
     * @var string
     */
    const ACTIVATED = 'admin-auth::activations.activated';

    /**
     * Constant representing the user not found response.
     *
     * @var string
     */
    const INVALID_USER = 'admin-auth::activations.user';

    /**
     * Constant representing an invalid token.
     *
     * @var string
     */
    const INVALID_TOKEN = 'admin-auth::activations.token';

    /**
     * Constant representing a successfully sent reminder.
     *
     * @var string
     */
    const ACTIVATION_DISABLED = 'admin-auth::activations.disabled';

    /**
     * Get the registered name of the component.
     *
     * @return string
     */
    protected static function getFacadeAccessor()
    {
        return 'auth.activation';
    }
}
