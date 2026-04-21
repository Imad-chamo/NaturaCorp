<?php

namespace App\Providers;

use App\Models\Pharmacie;
use App\Models\Commande;
use App\Policies\PharmaciePolicy;
use App\Policies\CommandePolicy;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Gate;

class AuthServiceProvider extends ServiceProvider
{
    protected $policies = [
        Pharmacie::class => PharmaciePolicy::class,
        Commande::class => CommandePolicy::class,
    ];

    public function boot(): void
    {
        Gate::before(function ($user, $ability) {
            return $user->hasRole('admin') ? true : null;
        });
    }
}
