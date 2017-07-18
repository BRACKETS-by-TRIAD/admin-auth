<?php

namespace Brackets\AdminAuth\Auth\Activations;

use Brackets\AdminAuth\Contracts\Auth\CanActivate as CanActivateContract;

interface TokenRepositoryInterface
{
    /**
     * Get a token record by user if exists and is valid.
     *
     * @param  \Brackets\AdminAuth\Contracts\Auth\CanActivate  $user
     * @return array|null
     */
    public function getByUser(CanActivateContract $user);

    /**
     * Get a token record by token if exists and is valid.
     *
     * @param  string  $token
     * @return array|null
     */
    public function getByToken($token);

    /**
     * Create a new token.
     *
     * @param  \Brackets\AdminAuth\Contracts\Auth\CanActivate  $user
     * @return string
     */
    public function create(CanActivateContract $user);

    /**
     * Create a new token or get existing not expired and not used.
     *
     * @param  \Brackets\AdminAuth\Contracts\Auth\CanActivate  $user
     * @return string
     */
    public function createOrGet(CanActivateContract $user);

    /**
     * Mark all token records as used by user.
     *
     * @param  \Brackets\AdminAuth\Contracts\Auth\CanActivate $user
     * @param $token
     * @return void
     */
    public function markAsUsed(CanActivateContract $user, $token);

    /**
     * Determine if a token record exists and is valid.
     *
     * @param  \Brackets\AdminAuth\Contracts\Auth\CanActivate  $user
     * @param  string  $token
     * @return bool
     */
    public function exists(CanActivateContract $user, $token);

    /**
     * Delete a token record.
     *
     * @param  \Brackets\AdminAuth\Contracts\Auth\CanActivate  $user
     * @return void
     */
    public function delete(CanActivateContract $user);

    /**
     * Delete expired tokens.
     *
     * @return void
     */
    public function deleteExpired();
}
