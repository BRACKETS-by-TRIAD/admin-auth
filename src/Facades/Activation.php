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
    const ACTIVATION_LINK_SENT = 'sent';

    /**
     * Constant representing a successfully reset password.
     *
     * @var string
     */
    const ACTIVATED = 'activated';

    /**
     * Constant representing the user not found response.
     *
     * @var string
     */
    const INVALID_USER = 'invalid-user';

    /**
     * Constant representing an invalid token.
     *
     * @var string
     */
    const INVALID_TOKEN = 'invalid-token';

    /**
     * Constant representing a disabled activation.
     *
     * @var string
     */
    const ACTIVATION_DISABLED = 'disabled';

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
