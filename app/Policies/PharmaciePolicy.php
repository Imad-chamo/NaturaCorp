<?php

namespace App\Policies;

use App\Models\Pharmacie;
use App\Models\User;

class PharmaciePolicy
{
    public function viewAny(User $user): bool
    {
        return $user->hasRole(['admin', 'commercial']);
    }

    public function view(User $user, Pharmacie $pharmacie): bool
    {
        return $this->viewAny($user);
    }

    public function create(User $user): bool
    {
        return $user->hasRole(['admin', 'commercial']);
    }

    public function update(User $user, Pharmacie $pharmacie): bool
    {
        return $user->hasRole(['admin', 'commercial']);
    }

    public function delete(User $user, Pharmacie $pharmacie): bool
    {
        return $user->hasRole(['admin', 'commercial']);
    }

    public function restore(User $user, Pharmacie $pharmacie): bool
    {
        return $this->update($user, $pharmacie);
    }

    public function forceDelete(User $user, Pharmacie $pharmacie): bool
    {
        return $this->delete($user, $pharmacie);
    }
}
