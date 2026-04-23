<?php

namespace App\Policies;

use App\Models\Screening;
use App\Models\User;

class ScreeningPolicy
{
    public function view(User $user, Screening $screening): bool
    {
        return $user->id === $screening->user_id || $user->canManageCases();
    }

    public function update(User $user, Screening $screening): bool
    {
        return $user->id === $screening->user_id;
    }
}
