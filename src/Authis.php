<?php

namespace Roseffendi\Authis;

use Closure;

class Authis
{
    /**
     * @var User
     */
    protected $user;

    /**
     * @var Resource
     */
    protected $resource;

    /**
     * @var array
     */
    protected $aliases = [];

    /**
     * @var array
     */
    protected $intercepts = [];

    /**
     * @var Precondition
     */
    protected $precondition;

    /**
     * @var array
     */
    protected $ablityPreconditions = [];

    /**
     * Create new Authis instance
     * 
     * @param User $user
     */
    public function __construct(
        User $user, 
        Resource $resource = null, 
        Precondition $precondition = null,
        $intercepts = [], 
        $aliases = [],
        $abilityPrecondtions = []
    ){
        $this->user = $user;
        $this->resource = $resource;
        $this->precondition = $precondition;
        $this->intercepts = $intercepts;
        $this->aliases = $aliases;
        $this->abilityPrecondtions = $abilityPrecondtions;
    }

    /**
     * Check if user granted an ability
     * 
     * @param  string $ability
     * @return boolean
     */
    public function check($ability)
    {
        $ability = $this->applyAlias($ability);

        if(!$this->applyPrecondition($ability)) {
            return false;
        }

        // Intercept was applied and return true
        if($this->applyIntercept($ability)) {
            return true;
        }

        // Checking if user has ability and the owner of the resource
        if(( in_array($ability, $this->user->abilities()) ) && ($this->applyForResource())) {
            return true;
        }

        return false;
    }

    /**
     * Register global preconditon
     * 
     * @param  Precondition $precondition
     * @return self
     */
    public function registerPrecondition(Precondition $precondition)
    {
        $this->precondition = $precondition;

        return $this;
    }

    /**
     * Register precondtion for specific ability
     * This precondition will override global precondition
     * 
     * @param  string       $ability
     * @param  Precondition $precondition
     * @return self
     */
    public function registerAbilityPrecondition($ability, Precondition $precondition)
    {
        $this->abilityPrecondtions[$ability] = $precondition;
    }

    /**
     * Intercept checking for special case
     * 
     * @param  string  $ability
     * @param  Closure $intercept
     * @return self
     */
    public function intercept($ability, Closure $intercept)
    {
        $this->intercepts[$ability] = $intercept;

        return $this;
    }

    /**
     * Give alias for given ability
     * 
     * @param  string $ability
     * @param  string $anotherAbility
     * @return self
     */
    public function alias($ability, $anotherAbility)
    {
        if(!isset($this->aliases[$ability])) {
            $this->aliases[$ability] = [$anotherAbility];
        } else {
            $this->aliases[$ability][] = $anotherAbility;
        }

        return $this;
    }

    /**
     * Apply for resouce owner checking
     * 
     * @param  Resource $resource
     * @return self
     */
    public function forResource(Resource $resource)
    {
        return new self($this->user, $resource, $this->intercepts, $this->precondition, $this->aliases, $this->abilityPrecondtions);
    }

    /**
     * Apply for different user
     * 
     * @param  User   $user
     * @return self
     */
    public function forUser(User $user)
    {
        return new self($user, $this->resource, $this->intercepts, $this->precondition, $this->aliases, $this->abilityPrecondtions);
    }

    /**
     * Apply intercept
     * 
     * @param  string $ability
     * @return boolean
     */
    protected function applyIntercept($ability)
    {
        if( !isset($this->intercepts[$ability])) {
            return false;
        }

        return call_user_func_array($this->intercepts[$ability], [$this->user, $ability]);
    }

    /**
     * Apply alias to get actual desired ability
     * 
     * @param  string $ability
     * @return string
     */
    protected function applyAlias($ability)
    {
        foreach ($this->aliases as $key => $value) {
            if(in_array($ability, $value)) {
                return $key;
            }
        }

        return $ability;
    }

    /**
     * Apply resource ownership
     * 
     * @return boolean
     */
    protected function applyForResource()
    {
        if(is_null($this->resource)) {
            return true;
        }

        return $this->resource->isBelongsTo($this->user);
    }

    /**
     * Apply precondition
     * 
     * @param  string   $ability
     * @return boolean
     */
    protected function applyPrecondition($ability)
    {
        if(is_null($this->precondition)) {
            return true;
        }

        if(!isset($this->abilityPrecondtions[$ability])) {
            return $this->precondition->pass();
        }else {
            return $this->precondition->pass() && $this->abilityPrecondtions[$ability]->pass();
        }
    }
}