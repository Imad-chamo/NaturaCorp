<?php

use App\Models\User;
use App\Models\NotificationInterne;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

it('retourne le bon nombre de notifications non lues', function () {
    $user = User::factory()->create();

    // Créer 3 notifications non lues
    NotificationInterne::factory()->count(3)->create([
        'user_id' => $user->id,
        'est_lu' => false,
    ]);

    // Créer 2 notifications lues
    NotificationInterne::factory()->count(2)->create([
        'user_id' => $user->id,
        'est_lu' => true,
    ]);

    expect($user->notificationsNonLuesCount())->toBe(3);
});

it('injecte la variable unreadCount dans la vue', function () {
    $user = User::factory()->create();

    NotificationInterne::factory()->count(5)->create([
        'user_id' => $user->id,
        'est_lu' => false,
    ]);

    $this->actingAs($user);

    $response = $this->get('/dashboard'); // adapter la route si besoin

    $response->assertSee((string) $user->notificationsNonLuesCount());
});
