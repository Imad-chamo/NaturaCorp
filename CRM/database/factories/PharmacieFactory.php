<?php

namespace Database\Factories;

use App\Models\Pharmacie;
use Illuminate\Database\Eloquent\Factories\Factory;

class PharmacieFactory extends Factory
{
    protected $model = Pharmacie::class;

    public function definition(): array
    {
        return [
            'nom' => $this->faker->company(),
            'siret' => $this->faker->unique()->numerify('### ### ### #####'),
            'email' => $this->faker->safeEmail(),
            'telephone' => $this->faker->phoneNumber(),
            'adresse' => $this->faker->address(),
            'code_postal' => $this->faker->postcode(),
            'ville' => $this->faker->city(),
            'statut' => 'prospect',
        ];
    }
}
