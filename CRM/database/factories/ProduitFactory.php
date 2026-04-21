<?php

namespace Database\Factories;

use App\Models\Produit;
use Illuminate\Database\Eloquent\Factories\Factory;

class ProduitFactory extends Factory
{
    protected $model = Produit::class;

    public function definition(): array
    {
        return [
            'nom' => $this->faker->word(),
            'tarif_unitaire' => $this->faker->randomFloat(2, 10, 100),
            'stock' => $this->faker->numberBetween(10, 100),
            'is_actif' => true,
        ];
    }
}
