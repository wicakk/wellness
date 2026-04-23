<?php

namespace App\Policies;

use App\Models\EducationContent;
use App\Models\User;

class EducationPolicy
{
    public function create(User $user): bool
    {
        return in_array($user->role->name, ['admin', 'wellness_warrior']);
    }

    public function update(User $user, EducationContent $content): bool
    {
        return in_array($user->role->name, ['admin', 'wellness_warrior']);
    }

    public function delete(User $user, EducationContent $content): bool
    {
        return in_array($user->role->name, ['admin', 'wellness_warrior']);
    }
}
