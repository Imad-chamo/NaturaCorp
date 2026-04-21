<?php

namespace App\Http\Controllers;

use App\Models\Produit;
use Illuminate\Http\Request;

class ProduitController extends Controller
{
    public function publicIndex()
    {
        $produits = Produit::where('is_actif', true)
            ->orderBy('nom')
            ->get(['id', 'nom', 'description', 'categorie', 'tarif_unitaire', 'stock'])
            ->map(fn($p) => array_merge($p->toArray(), ['image_url' => $p->image_url]));

        return response()->json($produits)
            ->header('Access-Control-Allow-Origin', '*')
            ->header('Access-Control-Allow-Methods', 'GET, OPTIONS')
            ->header('Access-Control-Allow-Headers', 'Content-Type, Accept');
    }

    public function index()
    {
        $produits = Produit::orderBy('nom')->get();

        $stats = [
            'total'        => $produits->count(),
            'actifs'       => $produits->where('is_actif', true)->count(),
            'ruptures'     => $produits->filter(fn($p) => $p->stock === 0)->count(),
            'stock_faible' => $produits->filter(fn($p) => $p->stock > 0 && $p->stock <= $p->stock_alerte)->count(),
            'valeur_stock' => $produits->sum(fn($p) => $p->stock * $p->tarif_unitaire),
        ];

        $categories = $produits->pluck('categorie')->filter()->unique()->sort()->values();

        return view('produits.index', compact('produits', 'stats', 'categories'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'nom'            => 'required|string|max:255',
            'description'    => 'nullable|string|max:500',
            'categorie'      => 'nullable|string|max:100',
            'tarif_unitaire' => 'required|numeric|min:0',
            'stock'          => 'required|integer|min:0',
            'stock_alerte'   => 'required|integer|min:0',
            'is_actif'       => 'boolean',
        ]);

        $data['is_actif'] = $request->boolean('is_actif');

        Produit::create($data);
        return redirect()->route('produits.index')->with('success', 'Produit créé avec succès.');
    }

    public function update(Request $request, Produit $produit)
    {
        $data = $request->validate([
            'nom'            => 'required|string|max:255',
            'description'    => 'nullable|string|max:500',
            'categorie'      => 'nullable|string|max:100',
            'tarif_unitaire' => 'required|numeric|min:0',
            'stock'          => 'required|integer|min:0',
            'stock_alerte'   => 'required|integer|min:0',
            'is_actif'       => 'boolean',
        ]);

        $data['is_actif'] = $request->boolean('is_actif');

        $produit->update($data);
        return redirect()->route('produits.index')->with('success', 'Produit mis à jour.');
    }

    public function destroy(Produit $produit)
    {
        $produit->delete();
        return redirect()->route('produits.index')->with('success', 'Produit supprimé.');
    }
}
