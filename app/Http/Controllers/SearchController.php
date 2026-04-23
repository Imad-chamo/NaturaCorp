<?php

namespace App\Http\Controllers;

use App\Models\Commande;
use App\Models\Pharmacie;
use Illuminate\Http\Request;

class SearchController extends Controller
{
    public function index(Request $request)
    {
        $q = trim($request->get('q', ''));

        if (strlen($q) < 2) {
            return view('search.index', ['q' => $q, 'pharmacies' => collect(), 'commandes' => collect()]);
        }

        $pharmacies = Pharmacie::where('nom', 'like', "%{$q}%")
            ->orWhere('email', 'like', "%{$q}%")
            ->orWhere('ville', 'like', "%{$q}%")
            ->orWhere('code_postal', 'like', "%{$q}%")
            ->limit(10)->get();

        $commandes = Commande::with(['pharmacie', 'produit'])
            ->whereHas('pharmacie', fn($query) => $query->where('nom', 'like', "%{$q}%"))
            ->orWhere('id', is_numeric($q) ? $q : 0)
            ->latest()->limit(10)->get();

        return view('search.index', compact('q', 'pharmacies', 'commandes'));
    }
}
