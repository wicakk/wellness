<?php

namespace App\Policies;

use App\Models\Cases;
use App\Models\User;

class CasePolicy
{
    public function viewAny(User $user): bool
    {
        return $user->canManageCases();
    }

    public function view(User $user, Cases $case): bool
    {
        return $user->canManageCases() || $user->id === $case->user_id;
    }

    public function create(User $user): bool
    {
        return $user->canManageCases();
    }

    public function update(User $user, Cases $case): bool
    {
        return $user->canManageCases();
    }

    public function delete(User $user, Cases $case): bool
    {
        return $user->isAdmin();
    }
}
