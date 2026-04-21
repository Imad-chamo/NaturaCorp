<?php

use function Pest\Laravel\{get, post, put, delete};
use App\Models\User;
use App\Models\Commande;
use App\Models\Pharmacie;
use App\Models\Produit;
use App\Enums\StatutCommande;
use Spatie\Permission\Models\Role;

beforeEach(function () {
    Role::findOrCreate('admin'); // garantit l'existence du rôle pour le guard 'web'

    $this->user = User::factory()->create();
    $this->user->assignRole('admin');
    $this->actingAs($this->user);
});

it('permet d\'accéder à la liste des commandes', function () {
    get('/commandes')->assertOk();
});

it('enregistre une nouvelle commande', function () {
    $produit = Produit::factory()->create(['stock' => 50]);
    $pharmacie = Pharmacie::factory()->create();

    $data = [
        'pharmacie_id' => $pharmacie->id,
        'produit_id' => $produit->id,
        'quantite' => 5,
        'statut' => StatutCommande::VALIDEE->value,
        'tarif_unitaire' => $produit->tarif_unitaire,
        'date_commande' => now()->format('Y-m-d'),
    ];

    $response = post('/commandes', $data);
    $response->assertRedirect();

    expect(Commande::where('pharmacie_id', $pharmacie->id)
        ->where('produit_id', $produit->id)
        ->where('quantite', 5)
        ->exists())->toBeTrue();
});

it('met à jour une commande', function () {
    $commande = Commande::factory()->create();

    $response = put('/commandes/' . $commande->id, [
        'quantite' => 11, // <-- valeur explicite ici
        'statut' => $commande->statut->value,
        'pharmacie_id' => $commande->pharmacie_id,
        'produit_id' => $commande->produit_id,
        'tarif_unitaire' => $commande->tarif_unitaire,
        'date_commande' => $commande->date_commande->format('Y-m-d'),
    ]);

    $response->assertRedirect();
    expect(Commande::find($commande->id)->quantite)->toBe(11);

});

it('supprime une commande', function () {
    $commande = Commande::factory()->create();

    $response = delete('/commandes/' . $commande->id);

    $response->assertRedirect();
    expect(Commande::find($commande->id))->toBeNull();
});
