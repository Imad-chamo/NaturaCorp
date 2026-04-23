<?php

namespace App\Http\Controllers;

use App\Models\Pharmacie;

class RelanceController extends Controller
{
    public function index()
    {
        // Pharmacies actives sans commande depuis 30+ jours (ou jamais)
        $relances = Pharmacie::where('statut', 'client_actif')
            ->with(['commandes' => fn($q) => $q->latest()->limit(1)])
            ->get()
            ->filter(function ($pharmacie) {
                $derniere = $pharmacie->commandes->first();
                if (!$derniere) return true;
                return $derniere->created_at->lt(now()->subDays(30));
            })
            ->sortByDesc(function ($pharmacie) {
                $derniere = $pharmacie->commandes->first();
                return $derniere ? $derniere->created_at->timestamp : 0;
            })
            ->values();

        return view('relances.index', compact('relances'));
    }
}
