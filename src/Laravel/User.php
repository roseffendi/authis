<?php namespace Roseffendi\Authis\Laravel;

use Roseffendi\Authis\User as UserContract;
use Illuminate\Contracts\Auth\Guard;

class User implements UserContract
{
    /**
     * @var Illuminate\Auth\Guard
     */
    protected $auth;

    /**
     * Create new User instance
     * 
     * @param Guard $auth
     */
    public function __construct(Guard $auth)
    {
        $this->auth = $auth;
    }

    /**
     * Retrieve user unique identifier
     * 
     * @return unique
     */
    public function id()
    {
        $user = $this->getUser();

        return is_null($user) ? null : $user->id();
    }

    /**
     * Retrieve user abilities
     * 
     * @return array
     */
    public function abilities()
    {
        $user = $this->getUser();

        return is_null($user) ? [] : $user->abilities();
    }

    /**
     * Retrive current user
     * 
     * @return User
     */
    protected function getUser()
    {
        return $this->auth->user();
    }
}