<?php

namespace Roseffendi\Authis\Laravel\Facades;

use Illuminate\Support\Facades\Facade;

class Authis extends Facade
{
    /**
     * Get the registered name of the component.
     *
     * @return string
     */
    protected static function getFacadeAccessor()
    {
        return 'authis';
    }
}