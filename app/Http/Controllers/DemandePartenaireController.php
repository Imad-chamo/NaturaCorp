<?php

namespace App\Http\Controllers;

use App\Models\Pharmacie;
use Illuminate\Http\Request;

class DemandePartenaireController extends Controller
{
    public function index()
    {
        $demandes = Pharmacie::where('statut', 'prospect')
            ->orderByDesc('created_at')
            ->paginate(20);

        $total = Pharmacie::where('statut', 'prospect')->count();

        return view('demandes.index', compact('demandes', 'total'));
    }

    public function show(Pharmacie $pharmacie)
    {
        abort_if($pharmacie->statut !== 'prospect', 404);
        return view('demandes.show', compact('pharmacie'));
    }

    public function export()
    {
        $demandes = Pharmacie::where('statut', 'prospect')
            ->orderByDesc('created_at')
            ->get();

        $lines = ["Pharmacie,Email,Téléphone,Code postal,Date de demande"];
        foreach ($demandes as $d) {
            $lines[] = implode(',', [
                '"' . str_replace('"', '""', $d->nom) . '"',
                '"' . str_replace('"', '""', $d->email ?? '') . '"',
                '"' . ($d->telephone ?? '') . '"',
                '"' . ($d->code_postal ?? '') . '"',
                '"' . $d->created_at->format('d/m/Y H:i') . '"',
            ]);
        }

        return response(implode("\n", $lines))
            ->header('Content-Type', 'text/csv; charset=UTF-8')
            ->header('Content-Disposition', 'attachment; filename="demandes-partenaires-' . now()->format('Y-m-d') . '.csv"');
    }

    public function accept(Pharmacie $pharmacie)
    {
        $pharmacie->update(['statut' => 'client_actif']);

        return redirect()->route('demandes.index')
            ->with('success', "« {$pharmacie->nom} » acceptée comme pharmacie partenaire.");
    }

    public function reject(Pharmacie $pharmacie)
    {
        $nom = $pharmacie->nom;
        $pharmacie->delete();

        return redirect()->route('demandes.index')
            ->with('success', "Demande de « {$nom} » supprimée.");
    }
}
