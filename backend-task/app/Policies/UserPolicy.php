<?php

namespace App\Policies;

use App\Models\User;

class UserPolicy
{
    public function isAdmin()
    {
        return auth()->user()->is_admin;
    }
}
