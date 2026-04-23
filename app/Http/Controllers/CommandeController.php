<?php

namespace App\Http\Controllers;

use App\Enums\StatutCommande;
use App\Models\Commande;
use App\Models\NotificationInterne;
use App\Models\Pharmacie;
use App\Models\Produit;
use App\Models\User;
use App\Services\JournalService;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use App\Models\DocumentJoint;

class CommandeController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        if ($user->hasRole('admin')) {
            $commandes = Commande::with(['pharmacie', 'user'])->latest()->paginate(100);
            $pharmacies = Pharmacie::with('zone')->get();
        } elseif ($user->hasRole('commercial')) {
            $zoneIds = $user->zones->pluck('id');

            $commandes = Commande::with(['pharmacie', 'user'])
                ->whereHas('pharmacie', fn ($query) => $query->whereIn('zone_id', $zoneIds))
                ->latest()
                ->paginate(100);

            $pharmacies = Pharmacie::with('zone')
                ->whereIn('zone_id', $zoneIds)
                ->get();
        } else {
            abort(403);
        }

        $statuts = StatutCommande::cases();
        $produits = Produit::all();

        return view('commandes.index', compact('commandes', 'pharmacies', 'statuts', 'produits'));
    }

    public function store(Request $request)
    {
        $this->authorize('create', Commande::class);

        $data = $request->validate([
            'pharmacie_id'     => 'required|exists:pharmacies,id',
            'produit_id'       => 'required|exists:produits,id',
            'date_commande'    => 'required|date',
            'statut'           => 'required|in:' . implode(',', array_column(StatutCommande::cases(), 'value')),
            'quantite'         => 'required|integer|min:1',
            'tarif_unitaire'   => 'required|numeric|min:0',
            'observations'     => 'nullable|string',
        ]);

        $data['user_id'] = auth()->id();

        return DB::transaction(function () use ($data) {
            $produit = Produit::lockForUpdate()->findOrFail($data['produit_id']);

            if ($produit->stock < $data['quantite']) {
                return back()->withErrors(['quantite' => 'Stock insuffisant pour ce produit.']);
            }

            $commande = Commande::create($data);

            $produit->decrement('stock', $data['quantite']);

            JournalService::log('create', "Création d'une commande #{$commande->id} pour la pharmacie #{$commande->pharmacie_id}");

            $utilisateurs = User::role(['commercial', 'logistique', 'admin'])->get();

            foreach ($utilisateurs as $utilisateur) {
                NotificationInterne::create([
                    'user_id' => $utilisateur->id,
                    'titre' => 'Nouvelle commande créée',
                    'contenu' => "Commande pour la pharmacie « {$commande->pharmacie->nom} », créée par {$commande->user->name}.",
                    'est_lu' => false,
                ]);
            }

            $pdf = Pdf::loadView('pdfs.commande', ['commande' => $commande]);
            $filename = 'commande_' . $commande->id . '_' . now()->format('Ymd_His') . '.pdf';
            $path = 'documents/' . $filename;

            Storage::disk('public')->put($path, $pdf->output());

            DocumentJoint::create([
                'pharmacie_id' => $commande->pharmacie_id,
                'commande_id'  => $commande->id,
                'nom_fichier'  => 'Commande PDF - ' . $commande->id,
                'chemin'       => $path,
                'type'         => 'rapport_commande',
            ]);

            return redirect()->back()->with('success', 'Commande créée avec succès.');
        });
    }

    public function update(Request $request, Commande $commande)
    {
        $this->authorize('update', $commande);

        $user = Auth::user();
        if ($user->hasRole('commercial')) {
            $zoneIds = $user->zones->pluck('id');
            if (!$commande->pharmacie()->whereIn('zone_id', $zoneIds)->exists()) {
                abort(403, 'Cette commande ne fait pas partie de votre zone.');
            }
        }

        $data = $request->validate([
            'pharmacie_id'     => 'required|exists:pharmacies,id',
            'produit_id'       => 'required|exists:produits,id',
            'date_commande'    => 'nullable|date',
            'statut'           => 'required|in:' . implode(',', array_column(StatutCommande::cases(), 'value')),
            'quantite'         => 'required|integer|min:1',
            'tarif_unitaire'   => 'required|numeric|min:0',
            'observations'     => 'nullable|string',
        ]);

        if (empty($data['date_commande'])) {
            $data['date_commande'] = $commande->date_commande;
        }

        $ancienStatut = $commande->statut->value;

        return DB::transaction(function () use ($commande, $data, $ancienStatut) {
            $produit = Produit::lockForUpdate()->findOrFail($data['produit_id']);

            $quantiteDisponible = $produit->stock + $commande->quantite;

            if ($data['quantite'] > $quantiteDisponible) {
                return back()->withErrors(['quantite' => 'Stock insuffisant pour cette quantité.']);
            }

            if ($commande->quantite != $data['quantite']) {
                $difference = $data['quantite'] - $commande->quantite;
                $produit->stock -= $difference;
                $produit->save();
            }

            $commande->update($data);

            $details = "Modification commande #{$commande->id}";
            if ($ancienStatut !== $data['statut']) {
                $details .= " | statut: {$ancienStatut} → {$data['statut']}";
            }
            if ($commande->wasChanged('quantite')) {
                $details .= " | quantité modifiée";
            }
            JournalService::log('update', $details);

            return redirect()->back()->with('success', 'Commande mise à jour avec succès.');
        });
    }

    public function pdf(Commande $commande)
    {
        $commande->load(['pharmacie', 'produit']);
        $pdf = Pdf::loadView('pdfs.commande', compact('commande'));
        $filename = 'NC-' . $commande->created_at->format('Y') . '-' . str_pad($commande->id, 4, '0', STR_PAD_LEFT) . '.pdf';
        return $pdf->download($filename);
    }

    public function updateStatut(Request $request, Commande $commande)
    {
        $data = $request->validate([
            'statut' => 'required|in:' . implode(',', array_column(StatutCommande::cases(), 'value')),
        ]);

        $ancien = $commande->statut->value;
        $commande->update(['statut' => $data['statut']]);
        JournalService::log('update', "Statut commande #{$commande->id} : {$ancien} → {$data['statut']}");

        return response()->json(['success' => true, 'statut' => $data['statut']]);
    }

    public function destroy(Commande $commande)
    {
        $this->authorize('delete', $commande);

        $user = Auth::user();
        if ($user->hasRole('commercial')) {
            $zoneIds = $user->zones->pluck('id');
            if (!$commande->pharmacie()->whereIn('zone_id', $zoneIds)->exists()) {
                abort(403, 'Cette commande ne fait pas partie de votre zone.');
            }
        }

        DB::transaction(function () use ($commande) {
            $commande->load(['document', 'produit']);

            if ($commande->document) {
                $path = storage_path('app/public/' . $commande->document->chemin);
                try {
                    if (file_exists($path)) {
                        unlink($path);
                    }
                } catch (\Exception $e) {
                    \Illuminate\Support\Facades\Log::warning("Impossible de supprimer le PDF commande #{$commande->id}: {$e->getMessage()}");
                }
                $commande->document->delete();
            }

            if ($commande->produit) {
                $commande->produit->increment('stock', $commande->quantite);
            }

            $commandeId = $commande->id;
            $commande->delete();

            JournalService::log('delete', "Suppression d'une commande #{$commandeId}");
        });

        return redirect()->back()->with('success', 'Commande supprimée avec succès.');
    }
}
