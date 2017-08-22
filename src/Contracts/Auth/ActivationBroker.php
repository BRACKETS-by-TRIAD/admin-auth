<?php

namespace Brackets\AdminAuth\Contracts\Auth;

use Closure;

interface ActivationBroker
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
     * Send a password reset link to a user.
     *
     * @param  array  $credentials
     * @return string
     */
    public function sendActivationLink(array $credentials);

    /**
     * Reset the password for the given token.
     *
     * @param  array     $credentials
     * @param  \Closure  $callback
     * @return mixed
     */
    public function activate(array $credentials, Closure $callback);
}
