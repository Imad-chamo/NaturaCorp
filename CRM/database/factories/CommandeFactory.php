<?php

namespace Database\Factories;

use App\Models\Commande;
use App\Models\Pharmacie;
use App\Models\Produit;
use App\Enums\StatutCommande;
use Illuminate\Database\Eloquent\Factories\Factory;

class CommandeFactory extends Factory
{
    protected $model = Commande::class;

    public function definition(): array
    {
        return [
            'pharmacie_id' => \App\Models\Pharmacie::factory(),
            'produit_id' => \App\Models\Produit::factory(),
            'user_id' => \App\Models\User::factory(), // ← ligne obligatoire
            'quantite' => $this->faker->numberBetween(1, 10),
            'statut' => \App\Enums\StatutCommande::EN_COURS->value,
            'tarif_unitaire' => $this->faker->randomFloat(2, 10, 100),
            'date_commande' => now(),
        ];
    }
}
