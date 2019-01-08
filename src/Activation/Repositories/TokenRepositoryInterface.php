<?php

namespace Brackets\AdminAuth\Activation\Repositories;

use Brackets\AdminAuth\Activation\Contracts\CanActivate as CanActivateContract;

interface TokenRepositoryInterface
{
    /**
     * Get a token record by user if exists and is valid.
     *
     * @param CanActivateContract $user
     * @return array|null
     */
    public function getByUser(CanActivateContract $user);

    /**
     * Get a token record by token if exists and is valid.
     *
     * @param  string $token
     * @return array|null
     */
    public function getByToken($token);

    /**
     * Create a new token.
     *
     * @param CanActivateContract $user
     * @return string
     */
    public function create(CanActivateContract $user);

    /**
     * Create a new token or get existing not expired and not used.
     *
     * @param CanActivateContract $user
     * @return string
     */
    public function createOrGet(CanActivateContract $user);

    /**
     * Mark all token records as used by user.
     *
     * @param CanActivateContract $user
     * @param $token
     * @return void
     */
    public function markAsUsed(CanActivateContract $user, $token);

    /**
     * Determine if a token record exists and is valid.
     *
     * @param CanActivateContract $user
     * @param  string $token
     * @return bool
     */
    public function exists(CanActivateContract $user, $token);

    /**
     * Delete a token record.
     *
     * @param CanActivateContract $user
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
