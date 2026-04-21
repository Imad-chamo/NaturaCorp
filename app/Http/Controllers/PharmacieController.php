<?php

namespace App\Http\Controllers;

use App\Models\Pharmacie;
use App\Models\Produit;
use App\Models\User;
use App\Services\JournalService;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use App\Services\GeocodageService;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class PharmacieController extends Controller
{
    public function index()
    {
        $user = auth()->user();

        if ($user->hasRole('admin')) {
            $pharmacies = Pharmacie::with('zone')->paginate(50);
        } elseif ($user->hasRole('commercial')) {
            $zoneIds = $user->zones->pluck('id');
            $pharmacies = Pharmacie::with('zone')
                ->whereIn('zone_id', $zoneIds)
                ->paginate(50);
        } else {
            abort(403);
        }

        $commerciaux = User::role('commercial')->get();

        return view('pharmacies.index', [
            'pharmacies' => $pharmacies,
            'commerciaux' => $commerciaux,
        ]);
    }

    public function store(Request $request)
    {
        $this->authorize('create', Pharmacie::class);

        $validated = $request->validate([
            'nom'                    => 'required|string|max:255',
            'siret'                  => 'nullable|digits:14|unique:pharmacies,siret',
            'email'                  => 'nullable|email|max:255',
            'telephone'              => 'nullable|string|max:20',
            'adresse'                => 'required|string|max:255',
            'code_postal'            => 'required|string|max:10',
            'ville'                  => 'required|string|max:100',
            'statut'                 => 'required|in:prospect,client_actif,client_inactif',
            'derniere_prise_contact' => 'nullable|date',
            'commercial_id'          => ['nullable', 'exists:users,id', function ($attr, $value, $fail) {
                if ($value && !User::find($value)?->hasRole('commercial')) {
                    $fail("L'utilisateur sélectionné n'est pas un commercial.");
                }
            }],
        ]);

        $pharmacie = Pharmacie::create($validated);

        app(GeocodageService::class)->geocoder($pharmacie);
        $pharmacie->save();

        JournalService::log('create', "Création d'une pharmacie #{$pharmacie->id}");

        return redirect()->route('pharmacies.index');
    }

    public function show(Pharmacie $pharmacy)
    {
        $this->authorize('view', $pharmacy);

        $user = auth()->user();
        if ($user->hasRole('commercial')) {
            $zoneIds = $user->zones->pluck('id');
            if (!in_array($pharmacy->zone_id, $zoneIds->toArray())) {
                abort(403, 'Cette pharmacie ne fait pas partie de votre zone.');
            }
        }

        $pharmacy->load(['commercial', 'documents', 'commandes']);

        $produits = Produit::all();

        return view('pharmacies.show', compact('pharmacy', 'produits'));
    }

    public function update(Request $request, Pharmacie $pharmacie)
    {
        $this->authorize('update', $pharmacie);

        $user = auth()->user();
        if ($user->hasRole('commercial')) {
            $zoneIds = $user->zones->pluck('id');
            if (!in_array($pharmacie->zone_id, $zoneIds->toArray())) {
                abort(403, 'Cette pharmacie ne fait pas partie de votre zone.');
            }
        }

        $validated = $request->validate([
            'nom'                    => 'required|string|max:255',
            'siret'                  => ['nullable', 'digits:14', Rule::unique('pharmacies', 'siret')->ignore($pharmacie->id)],
            'email'                  => 'nullable|email|max:255',
            'telephone'              => 'nullable|string|max:20',
            'adresse'                => 'required|string|max:255',
            'code_postal'            => 'required|string|max:10',
            'ville'                  => 'required|string|max:100',
            'statut'                 => 'required|in:prospect,client_actif,client_inactif',
            'derniere_prise_contact' => 'nullable|date',
            'commercial_id'          => ['nullable', 'exists:users,id', function ($attr, $value, $fail) {
                if ($value && !User::find($value)?->hasRole('commercial')) {
                    $fail("L'utilisateur sélectionné n'est pas un commercial.");
                }
            }],
        ]);

        $adresseModifiee =
            $pharmacie->adresse !== $validated['adresse'] ||
            $pharmacie->ville !== $validated['ville'] ||
            $pharmacie->code_postal !== $validated['code_postal'];

        $pharmacie->update($validated);
        JournalService::log('update', "Modification d'une pharmacie #{$pharmacie->id}");

        if ($adresseModifiee) {
            app(GeocodageService::class)->geocoder($pharmacie);
            $pharmacie->save();
        }

        return redirect()->back()->with('success', 'Pharmacie mise à jour avec succès.');
    }

    public function destroy(Pharmacie $pharmacie)
    {
        $this->authorize('delete', $pharmacie);

        $user = auth()->user();
        if ($user->hasRole('commercial')) {
            $zoneIds = $user->zones->pluck('id');
            if (!in_array($pharmacie->zone_id, $zoneIds->toArray())) {
                abort(403, 'Cette pharmacie ne fait pas partie de votre zone.');
            }
        }

        DB::transaction(function () use ($pharmacie) {
            foreach ($pharmacie->documents as $doc) {
                try {
                    Storage::delete('public/' . $doc->chemin);
                } catch (\Exception $e) {
                    \Illuminate\Support\Facades\Log::warning("Impossible de supprimer le fichier doc #{$doc->id}: {$e->getMessage()}");
                }
                $doc->delete();
            }

            $pharmacie->commandes()->delete();
            $pharmacieId = $pharmacie->id;
            $pharmacie->delete();

            JournalService::log('delete', "Suppression d'une pharmacie #{$pharmacieId}");
        });

        return redirect()->route('pharmacies.index')->with('success', 'Pharmacie supprimée avec succès.');
    }
}
