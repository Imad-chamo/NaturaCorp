<?php

namespace App\Http\Controllers;

use App\Models\DocumentJoint;
use App\Models\Pharmacie;
use App\Services\JournalService;
use Illuminate\Http\Request;

class DocumentJointController extends Controller
{
    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'pharmacie_id' => 'required|exists:pharmacies,id',
            'type' => 'required|string',
            'fichier' => 'required|file|max:5120', // 5MB
        ]);

        $fichier = $request->file('fichier');
        $nom = 'Doc_' . uniqid() . '.' . $fichier->getClientOriginalExtension();
        $chemin = $fichier->storeAs('documents', $nom, 'public');

        DocumentJoint::create([
            'pharmacie_id' => $request->pharmacie_id,
            'nom_fichier' => $fichier->getClientOriginalName(),
            'chemin' => $chemin,
            'type' => $request->type,
        ]);

        JournalService::log('create', "Création d'un fichier de type #{$request->type}. Nom : {$fichier->getClientOriginalName()}");

        return back()->with('success', 'Document ajouté avec succès.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(DocumentJoint $documentJoint)
    {
        $documentJoint->delete();
        return redirect()->route('documents.index');
    }
}
