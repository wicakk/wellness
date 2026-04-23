<?php

namespace App\Policies;

use App\Models\User;

class UserPolicy
{
    public function viewReports(User $user): bool
    {
        return $user->canManageCases();
    }
}
