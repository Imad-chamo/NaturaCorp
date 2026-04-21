<?php

namespace Database\Factories;

use App\Models\NotificationInterne;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class NotificationInterneFactory extends Factory
{
    protected $model = NotificationInterne::class;

    public function definition(): array
    {
        return [
            'user_id' => User::factory(),
            'titre' => $this->faker->sentence(3),
            'contenu' => $this->faker->sentence(6),
            'est_lu' => false,
        ];
    }
}
