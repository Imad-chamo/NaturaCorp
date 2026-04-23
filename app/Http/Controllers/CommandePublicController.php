<?php

namespace App\Http\Controllers;

use App\Models\Pharmacie;
use App\Models\Commande;
use App\Models\Produit;
use App\Enums\StatutCommande;
use Illuminate\Http\Request;

class CommandePublicController extends Controller
{
    public function pharmacies()
    {
        $cors = ['Access-Control-Allow-Origin' => '*'];

        $pharmacies = Pharmacie::whereIn('statut', ['client_actif', 'prospect'])
            ->orderBy('nom')
            ->get(['id', 'nom', 'ville', 'code_postal', 'email', 'telephone'])
            ->map(fn($p) => [
                'id'          => $p->id,
                'nom'         => $p->nom,
                'ville'       => $p->ville ?? '',
                'code_postal' => $p->code_postal ?? '',
                'email'       => $p->email ?? '',
                'telephone'   => $p->telephone ?? '',
            ]);

        return response()->json($pharmacies, 200, $cors);
    }

    public function store(Request $request)
    {
        $cors = [
            'Access-Control-Allow-Origin'  => '*',
            'Access-Control-Allow-Methods' => 'POST, OPTIONS',
            'Access-Control-Allow-Headers' => 'Content-Type, Accept',
        ];

        $data = $request->validate([
            'pharmacie_id' => 'required|integer|exists:pharmacies,id',
            'pharmacie'    => 'required|string|max:255',
            'email'        => 'required|email|max:255',
            'telephone'    => 'nullable|string|max:20',
            'code_postal'  => 'required|string|max:5',
            'quantite'     => 'required|integer|min:1|max:999',
            'message'      => 'nullable|string|max:1000',
        ]);

        $produit = Produit::where('is_actif', true)->firstOrFail();

        $pharmacie = Pharmacie::findOrFail($data['pharmacie_id']);

        $obs = 'Commande passée depuis le site vitrine.';
        if (!empty($data['message'])) {
            $obs .= ' Note : ' . $data['message'];
        }

        $commande = Commande::create([
            'pharmacie_id'   => $pharmacie->id,
            'produit_id'     => $produit->id,
            'quantite'       => $data['quantite'],
            'tarif_unitaire' => $produit->tarif_unitaire,
            'date_commande'  => now()->toDateString(),
            'statut'         => StatutCommande::EN_COURS,
            'observations'   => $obs,
            'user_id'        => null,
        ]);

        return response()->json([
            'success'      => true,
            'commande_id'  => $commande->id,
            'produit'      => $produit->nom,
            'quantite'     => $commande->quantite,
            'total'        => number_format($commande->tarif_unitaire * $commande->quantite, 2, ',', ' '),
        ], 201, $cors);
    }
}
