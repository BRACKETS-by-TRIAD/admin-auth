<?php

namespace Brackets\AdminAuth\Auth\Activations;

use Closure;
use Illuminate\Support\Arr;
use UnexpectedValueException;
use Illuminate\Contracts\Auth\UserProvider;
use Brackets\AdminAuth\Contracts\Auth\ActivationBroker as ActivationBrokerContract;
use Brackets\AdminAuth\Contracts\Auth\CanActivate as CanActivateContract;

class ActivationBroker implements ActivationBrokerContract
{
    /**
     * The activation token repository.
     *
     * @var \Brackets\AdminAuth\Auth\Activations\TokenRepositoryInterface
     */
    protected $tokens;

    /**
     * The user provider implementation.
     *
     * @var \Illuminate\Contracts\Auth\UserProvider
     */
    protected $users;

    /**
     * Create a new password broker instance.
     *
     * @param  \Brackets\AdminAuth\Auth\Activations\TokenRepositoryInterface  $tokens
     * @param  \Illuminate\Contracts\Auth\UserProvider  $users
     * @return void
     */
    public function __construct(TokenRepositoryInterface $tokens,
                                UserProvider $users)
    {
        $this->users = $users;
        $this->tokens = $tokens;
    }

    /**
     * Send a activation link to a user.
     *
     * @param  array  $credentials
     * @return string
     */
    public function sendActivationLink(array $credentials)
    {
        // First we will check to see if we found a user at the given credentials and
        // if we did not we will redirect back to this current URI with a piece of
        // "flash" data in the session to indicate to the developers the errors.
        $user = $this->getUser($credentials);

        if (is_null($user)) {
            return static::INVALID_USER;
        }

        // Once we have the activation token, we are ready to send the message out to this
        // user with a link to activate their account. We will then redirect back to
        // the current URI having nothing set in the session to indicate errors.
        $user->sendActivationNotification(
            $this->tokens->createOrGet($user)
        );

        return static::ACTIVATION_LINK_SENT;
    }

    /**
     * Activate account for the given token.
     *
     * @param  array  $credentials
     * @param  \Closure  $callback
     * @return mixed
     */
    public function activate(array $credentials, Closure $callback)
    {
        // If the responses from the validate method is not a user instance, we will
        // assume that it is a redirect and simply return it from this method and
        // the user is properly redirected having an error message on the post.
        $user = $this->validateActivation($credentials);

        if (! $user instanceof CanActivateContract) {
            return $user;
        }

        // Once the token has been validated, we'll call the given callback.
        // This gives the user an opportunity to change flag
        // in their persistent storage. Then we'll flag the token as used and return.
        $callback($user);

        $this->tokens->markAsUsed($user, $credentials['token']);

        return static::ACTIVATED;
    }

    /**
     * Validate an activation for the given credentials.
     *
     * @param  array  $credentials
     * @return CanActivateContract|string
     */
    protected function validateActivation(array $credentials)
    {
        if (empty($tokenRecord = $this->tokens->getByToken($credentials['token']))) {
            return static::INVALID_TOKEN;
        }

        if (is_null($user = $this->getUser(['email' => $tokenRecord['email']]))) {
            return static::INVALID_USER;
        }

        return $user;
    }

    /**
     * Get the user for the given credentials.
     *
     * @param  array  $credentials
     * @return \Brackets\AdminAuth\Contracts\Auth\CanActivate
     *
     * @throws \UnexpectedValueException
     */
    public function getUser(array $credentials)
    {
        $credentials = Arr::except($credentials, ['token']);

        $user = $this->users->retrieveByCredentials($credentials);

        if ($user && ! $user instanceof CanActivateContract) {
            throw new UnexpectedValueException('User must implement CanActivateContract interface.');
        }

        return $user;
    }

    /**
     * Create a new password reset token for the given user.
     *
     * @param  \Brackets\AdminAuth\Contracts\Auth\CanActivate $user
     * @return string
     */
    public function createToken(CanActivateContract $user)
    {
        return $this->tokens->create($user);
    }

    /**
     * Delete password reset tokens of the given user.
     *
     * @param  \Brackets\AdminAuth\Contracts\Auth\CanActivate $user
     * @return void
     */
    public function deleteToken(CanActivateContract $user)
    {
        $this->tokens->delete($user);
    }

    /**
     * Validate the given password reset token.
     *
     * @param  \Brackets\AdminAuth\Contracts\Auth\CanActivate $user
     * @param  string $token
     * @return bool
     */
    public function tokenExists(CanActivateContract $user, $token)
    {
        return $this->tokens->exists($user, $token);
    }

    /**
     * Get the activation token repository implementation.
     *
     * @return \Brackets\AdminAuth\Auth\Activations\TokenRepositoryInterface
     */
    public function getRepository()
    {
        return $this->tokens;
    }

    /**
     * Get the user model class implementation.
     *
     * @return \Brackets\AdminAuth\Auth\Activations\TokenRepositoryInterface
     */
    public function getUserModelClass()
    {
        return $this->users->getModel();
    }
}
