<?php

namespace App\Http\Controllers;

use App\Models\Pharmacie;
use App\Models\Commande;
use App\Models\Produit;
use App\Models\NotificationInterne;
use App\Models\User;
use App\Enums\StatutCommande;
use Illuminate\Http\Request;

class ContactPublicController extends Controller
{
    public function store(Request $request)
    {
        $data = $request->validate([
            'pharmacie'   => 'required|string|max:255',
            'email'       => 'required|email|max:255',
            'telephone'   => 'nullable|string|max:20',
            'code_postal' => 'required|string|max:5',
            'prenom'      => 'nullable|string|max:100',
            'nom'         => 'nullable|string|max:100',
            'sujet'       => 'nullable|string|max:100',
            'produit_ref' => 'nullable|string|max:20',
            'quantite'    => 'nullable|integer|min:1',
            'message'     => 'nullable|string|max:2000',
        ]);

        $pharmacie = Pharmacie::create([
            'nom'                    => $data['pharmacie'],
            'email'                  => $data['email'],
            'telephone'              => $data['telephone'] ?? null,
            'code_postal'            => $data['code_postal'],
            'statut'                 => 'prospect',
            'derniere_prise_contact' => now(),
        ]);

        // Si une commande produit est jointe, la créer dans le CRM
        if (!empty($data['produit_ref'])) {
            $produit = Produit::where('is_actif', true)->first();

            if ($produit) {
                $obs = 'Commande passée depuis le site vitrine.';
                if (!empty($data['message'])) {
                    $obs .= ' Message : ' . $data['message'];
                }

                Commande::create([
                    'pharmacie_id'   => $pharmacie->id,
                    'produit_id'     => $produit->id,
                    'quantite'       => $data['quantite'] ?? 1,
                    'tarif_unitaire' => $produit->tarif_unitaire,
                    'date_commande'  => now()->toDateString(),
                    'statut'         => StatutCommande::EN_COURS,
                    'observations'   => $obs,
                    'user_id'        => null,
                ]);
            }
        }

        // Notifier tous les admins et commerciaux
        $destinataires = User::whereHas('roles', fn($q) =>
            $q->whereIn('name', ['admin', 'commercial'])
        )->get();

        foreach ($destinataires as $user) {
            NotificationInterne::create([
                'user_id' => $user->id,
                'titre'   => '🤝 Nouvelle demande partenaire',
                'contenu' => "« {$pharmacie->nom} » ({$pharmacie->code_postal}) souhaite rejoindre le réseau.",
                'est_lu'  => false,
            ]);
        }

        return response()->json(['success' => true, 'message' => 'Demande enregistrée.'])
            ->header('Access-Control-Allow-Origin', '*')
            ->header('Access-Control-Allow-Methods', 'POST, OPTIONS')
            ->header('Access-Control-Allow-Headers', 'Content-Type, Accept');
    }
}
