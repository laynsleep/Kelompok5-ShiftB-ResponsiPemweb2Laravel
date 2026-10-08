<?php

namespace App\Policies;

use App\Models\Aspiration;
use App\Models\User;

class AspirationPolicy
{
    public function update(User $user, Aspiration $aspiration): bool
    {
        return $user->isAdmin() || $aspiration->user_id === $user->id;
    }

    public function delete(User $user, Aspiration $aspiration): bool
    {
        return $user->isAdmin() || $aspiration->user_id === $user->id;
    }

    public function updateStatus(User $user, Aspiration $aspiration): bool
    {
        return $user->isAdmin();
    }
}
