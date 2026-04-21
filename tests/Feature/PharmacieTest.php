<?php

use function Pest\Laravel\{get, post, put, delete};
use App\Models\User;
use App\Models\Pharmacie;
use Spatie\Permission\Models\Role;

beforeEach(function () {
    Role::findOrCreate('admin');
    Role::findOrCreate('commercial');

    $this->user = User::factory()->create();
    $this->user->assignRole('admin');
    $this->actingAs($this->user);
});

it('permet d\'accéder à la liste des pharmacies', function () {
    $response = get('/pharmacies');
    $response->assertStatus(200); // ou ->assertOk()
});

it('affiche une fiche pharmacie', function () {
    $pharmacie = Pharmacie::factory()->create();
    get('/pharmacies/' . $pharmacie->id)->assertOk();
});

it('enregistre une nouvelle pharmacie', function () {
    $data = Pharmacie::factory()->make()->toArray();

    $response = post('/pharmacies', $data);
    $response->assertRedirect('/pharmacies');

    expect(Pharmacie::where('nom', $data['nom'])->exists())->toBeTrue();
});

it('met à jour une pharmacie', function () {
    $pharmacie = Pharmacie::factory()->create();

    $updateData = [
        'nom' => 'Pharmacie Test Modifiée',
        'siret' => $pharmacie->siret,
        'adresse' => $pharmacie->adresse,
        'code_postal' => $pharmacie->code_postal,
        'ville' => $pharmacie->ville,
        'statut' => $pharmacie->statut,
        'email' => $pharmacie->email,
        'telephone' => $pharmacie->telephone,
        'derniere_prise_contact' => $pharmacie->derniere_prise_contact?->format('Y-m-d'),
    ];

    $response = put('/pharmacies/' . $pharmacie->id, $updateData);
    $response->assertRedirect();

    expect(Pharmacie::find($pharmacie->id)->nom)->toBe('Pharmacie Test Modifiée');
});

it('supprime une pharmacie', function () {
    $pharmacie = Pharmacie::factory()->create([
        'commercial_id' => null,
        'zone_id' => null,
    ]);

    $response = delete('/pharmacies/' . $pharmacie->id);
    $response->assertRedirect('/pharmacies');

    $this->assertDatabaseMissing('pharmacies', [
        'id' => $pharmacie->id,
    ]);
});
