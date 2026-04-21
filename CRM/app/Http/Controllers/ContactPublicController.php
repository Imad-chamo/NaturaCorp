<?php

namespace App\Http\Controllers;

use App\Models\Pharmacie;
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
        ]);

        Pharmacie::create([
            'nom'                    => $data['pharmacie'],
            'email'                  => $data['email'],
            'telephone'              => $data['telephone'] ?? null,
            'code_postal'            => $data['code_postal'],
            'statut'                 => 'prospect',
            'derniere_prise_contact' => now(),
        ]);

        return response()->json(['success' => true, 'message' => 'Demande enregistrée.'])
            ->header('Access-Control-Allow-Origin', '*')
            ->header('Access-Control-Allow-Methods', 'POST, OPTIONS')
            ->header('Access-Control-Allow-Headers', 'Content-Type, Accept');
    }
}
