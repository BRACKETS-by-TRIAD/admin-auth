<?php

namespace Brackets\AdminAuth\Auth\Activations;

use Carbon\Carbon;
use Illuminate\Support\Str;
use Illuminate\Database\ConnectionInterface;
use Illuminate\Contracts\Hashing\Hasher as HasherContract;
use Brackets\AdminAuth\Contracts\Auth\CanActivate as CanActivateContract;

class DatabaseTokenRepository implements TokenRepositoryInterface
{
    /**
     * The database connection instance.
     *
     * @var \Illuminate\Database\ConnectionInterface
     */
    protected $connection;

    /**
     * The Hasher implementation.
     *
     * @var \Illuminate\Contracts\Hashing\Hasher
     */
    protected $hasher;

    /**
     * The token database table.
     *
     * @var string
     */
    protected $table;

    /**
     * The hashing key.
     *
     * @var string
     */
    protected $hashKey;

    /**
     * The number of seconds a token should last.
     *
     * @var int
     */
    protected $expires;

    /**
     * Create a new token repository instance.
     *
     * @param  \Illuminate\Database\ConnectionInterface  $connection
     * @param  \Illuminate\Contracts\Hashing\Hasher  $hasher
     * @param  string  $table
     * @param  string  $hashKey
     * @param  int  $expires
     * @return void
     */
    public function __construct(ConnectionInterface $connection, HasherContract $hasher,
                                $table, $hashKey, $expires = 60)
    {
        $this->table = $table;
        $this->hasher = $hasher;
        $this->hashKey = $hashKey;
        $this->expires = $expires * 60;
        $this->connection = $connection;
    }

    /**
     * Get a token record by user if exists and is valid.
     *
     * @param  \Brackets\AdminAuth\Contracts\Auth\CanActivate  $user
     * @return array|null
     */
    public function getByUser(CanActivateContract $user)
    {
        return (array) $this->getTable()
            ->where(['email' => $user->getEmailForActivation(), 'used' => false ])
            ->where('created_at', '>=', Carbon::now()->subSeconds($this->expires))
            ->first();
    }

    /**
     * Get a token record by token if exists and is valid.
     *
     * @param  string  $token
     * @return array|null
     */
    public function getByToken($token)
    {
        return (array) $this->getTable()
            ->where(['token' => $token, 'used' => false ])
            ->where('created_at', '>=', Carbon::now()->subSeconds($this->expires))
            ->first();
    }

    /**
     * Create a new token record.
     *
     * @param  \Brackets\AdminAuth\Contracts\Auth\CanActivate  $user
     * @return string
     */
    public function create(CanActivateContract $user)
    {
        $email = $user->getEmailForActivation();

        // We will create a new, random token for the user so that we can e-mail them
        // a safe link to activate. Then we will insert a record in
        // the database so that we can verify the token within the actual activation.
        $token = $this->createNewToken();

        $this->getTable()->insert($this->getPayload($email, $token));

        return $token;
    }

    /**
     * Create a new token or get existing not expired and not used.
     *
     * @param  \Brackets\AdminAuth\Contracts\Auth\CanActivate  $user
     * @return string
     */
    public function createOrGet(CanActivateContract $user)
    {
        $email = $user->getEmailForActivation();

        if(!empty($record = $this->getByUser($user))) {
            $token = $record['token'];
        } else {
            // We will create a new, random token for the user so that we can e-mail them
            // a safe link to activate. Then we will insert a record in
            // the database so that we can verify the token within the actual activation.
            $token = $this->createNewToken();

            $this->getTable()->insert($this->getPayload($email, $token));
        }

        return $token;
    }

    /**
     * Mark all token records as used by user.
     *
     * @param  \Brackets\AdminAuth\Contracts\Auth\CanActivate $user
     * @param string|null $token
     * @return void
     */
    public function markAsUsed(CanActivateContract $user, $token = null)
    {
        $query = $this->getTable()
            ->where('email', $user->getEmailForActivation());
        if(!is_null($token)) {
            $query = $query->where('token', $token);
        }
        $query->update(['used' => true]);
    }

    /**
     * Delete all existing activation tokens from the database.
     *
     * @param  \Brackets\AdminAuth\Contracts\Auth\CanActivate  $user
     * @return int
     */
    protected function deleteExisting(CanActivateContract $user)
    {
        return $this->getTable()->where('email', $user->getEmailForPasswordReset())->delete();
    }

    /**
     * Build the record payload for the table.
     *
     * @param  string  $email
     * @param  string  $token
     * @return array
     */
    protected function getPayload($email, $token)
    {
        return ['email' => $email, 'token' => $token, 'created_at' => new Carbon];
    }

    /**
     * Determine if a token record exists and is valid.
     *
     * @param  \Brackets\AdminAuth\Contracts\Auth\CanActivate  $user
     * @param  string  $token
     * @return bool
     */
    public function exists(CanActivateContract $user, $token)
    {
        $record = (array) $this->getTable()->where(
            ['email' => $user->getEmailForActivation(), 'used' => false ]
        )->first();

        return $record &&
               ! $this->tokenExpired($record['created_at']) &&
                 $token === $record['token'];
    }

    /**
     * Determine if the token has expired.
     *
     * @param  string  $createdAt
     * @return bool
     */
    protected function tokenExpired($createdAt)
    {
        return Carbon::parse($createdAt)->addSeconds($this->expires)->isPast();
    }

    /**
     * Delete a token record by user.
     *
     * @param  \Brackets\AdminAuth\Contracts\Auth\CanActivate  $user
     * @return void
     */
    public function delete(CanActivateContract $user)
    {
        $this->deleteExisting($user);
    }

    /**
     * Delete expired tokens.
     *
     * @return void
     */
    public function deleteExpired()
    {
        $expiredAt = Carbon::now()->subSeconds($this->expires);

        $this->getTable()->where('created_at', '<', $expiredAt)->delete();
    }

    /**
     * Create a new token for the user.
     *
     * @return string
     */
    public function createNewToken()
    {
        return hash_hmac('sha256', Str::random(40), $this->hashKey);
    }

    /**
     * Get the database connection instance.
     *
     * @return \Illuminate\Database\ConnectionInterface
     */
    public function getConnection()
    {
        return $this->connection;
    }

    /**
     * Begin a new database query against the table.
     *
     * @return \Illuminate\Database\Query\Builder
     */
    protected function getTable()
    {
        return $this->connection->table($this->table);
    }

    /**
     * Get the hasher instance.
     *
     * @return \Illuminate\Contracts\Hashing\Hasher
     */
    public function getHasher()
    {
        return $this->hasher;
    }
}
