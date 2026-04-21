<?php

namespace App\Policies;

use App\Models\Commande;
use App\Models\User;

class CommandePolicy
{
    public function viewAny(User $user): bool
    {
        return $user->hasRole(['admin', 'commercial', 'logistique']);
    }

    public function view(User $user, Commande $commande): bool
    {
        return $this->viewAny($user);
    }

    public function create(User $user): bool
    {
        return $user->hasRole(['admin', 'commercial']);
    }

    public function update(User $user, Commande $commande): bool
    {
        return $user->hasRole(['admin', 'commercial', 'logistique']);
    }

    public function delete(User $user, Commande $commande): bool
    {
        return $user->hasRole(['admin', 'commercial']);
    }

    public function restore(User $user, Commande $commande): bool
    {
        return $this->update($user, $commande);
    }

    public function forceDelete(User $user, Commande $commande): bool
    {
        return $this->delete($user, $commande);
    }
}
