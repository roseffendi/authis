<?php

namespace Roseffendi\Authis;

interface User
{
    /**
     * Retrieve user unique identifier
     * @return unique
     */
    public function id();

    /**
     * Retrieve user abilities
     * @return array
     */
    public function abilities();
}