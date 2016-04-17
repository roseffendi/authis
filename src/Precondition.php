<?php

namespace Roseffendi\Authis;

interface Precondition
{
    /**
     * Determine if precondition is passing
     * 
     * @return void
     */
    public function pass();
}