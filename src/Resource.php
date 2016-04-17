<?php

namespace Roseffendi\Authis;

interface Resource
{
    /**
     * Determine if resource belong to user
     * @param  User   $user
     * @return boolean
     */
    public function isBelongsTo(User $user);
}