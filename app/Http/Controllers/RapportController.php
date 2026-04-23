<?php

namespace App\Http\Controllers;

use App\Models\{Commande, Pharmacie, Rapport};
use Illuminate\Http\Request;
use Illuminate\Support\Facades\{Auth, DB, Storage};

class RapportController extends Controller
{
    public function index()
    {
        $rapports = Rapport::with('utilisateur')->latest()->get();

        $stats = [
            'commandes_total'  => Commande::count(),
            'ca_total'         => round(Commande::selectRaw('SUM(tarif_unitaire * quantite) as ca')->value('ca') ?? 0, 2),
            'pharmacies_total' => Pharmacie::where('statut', 'client_actif')->count(),
            'relances_total'   => Pharmacie::where('statut', 'client_actif')
                ->with(['commandes' => fn($q) => $q->latest()->limit(1)])
                ->get()
                ->filter(fn($p) => !$p->commandes->first() || $p->commandes->first()->created_at->lt(now()->subDays(30)))
                ->count(),
            'ca_mois'          => round(Commande::whereMonth('created_at', now()->month)
                ->selectRaw('SUM(tarif_unitaire * quantite) as ca')->value('ca') ?? 0, 2),
            'commandes_mois'   => Commande::whereMonth('created_at', now()->month)->count(),
        ];

        return view('rapports.index', compact('rapports', 'stats'));
    }

    public function generate(Request $request)
    {
        $request->validate(['type' => 'required|in:commandes,pharmacies,relances,ca_mensuel']);

        $type     = $request->input('type');
        $date     = now()->format('Y-m-d_His');
        $filename = "{$type}_{$date}.csv";
        $path     = "rapports/{$filename}";

        Storage::makeDirectory('rapports');

        $handle = fopen('php://temp', 'r+');

        match ($type) {
            'commandes'  => $this->buildCommandesCsv($handle),
            'pharmacies' => $this->buildPharmaciesCsv($handle),
            'relances'   => $this->buildRelancesCsv($handle),
            'ca_mensuel' => $this->buildCaMensuelCsv($handle),
        };

        rewind($handle);
        Storage::put($path, stream_get_contents($handle));
        fclose($handle);

        $labels = [
            'commandes'  => 'Export commandes',
            'pharmacies' => 'Export pharmacies',
            'relances'   => 'Rapport relances commerciales',
            'ca_mensuel' => 'Rapport CA mensuel',
        ];

        Rapport::create([
            'titre'         => $labels[$type] . ' — ' . now()->translatedFormat('d M Y'),
            'type'          => $type,
            'chemin_fichier' => $path,
            'user_id'       => Auth::id(),
            'filtres'       => [],
        ]);

        return redirect()->route('rapports.index')
            ->with('success', 'Rapport généré avec succès.');
    }

    public function show(Rapport $rapport)
    {
        abort_unless(Storage::exists($rapport->chemin_fichier), 404, 'Fichier introuvable.');
        return Storage::download($rapport->chemin_fichier, basename($rapport->chemin_fichier));
    }

    public function destroy(Rapport $rapport)
    {
        if (Storage::exists($rapport->chemin_fichier)) {
            Storage::delete($rapport->chemin_fichier);
        }
        $rapport->delete();
        return redirect()->route('rapports.index')->with('success', 'Rapport supprimé.');
    }

    // ── CSV builders ──────────────────────────────────────────

    private function buildCommandesCsv($handle): void
    {
        fputcsv($handle, ['Référence', 'Pharmacie', 'Ville', 'Produit', 'Quantité', 'Tarif unitaire (€)', 'Total (€)', 'Statut', 'Date'], ';');

        Commande::with(['pharmacie', 'produit'])->orderByDesc('created_at')->each(function ($c) use ($handle) {
            fputcsv($handle, [
                'NC-' . $c->created_at->format('Y') . '-' . str_pad($c->id, 4, '0', STR_PAD_LEFT),
                $c->pharmacie?->nom ?? '',
                $c->pharmacie?->ville ?? '',
                $c->produit?->nom ?? '',
                $c->quantite,
                number_format($c->tarif_unitaire, 2, '.', ''),
                number_format($c->tarif_unitaire * $c->quantite, 2, '.', ''),
                $c->statut?->value ?? $c->statut,
                $c->created_at->format('d/m/Y'),
            ], ';');
        });
    }

    private function buildPharmaciesCsv($handle): void
    {
        fputcsv($handle, ['Nom', 'SIRET', 'Email', 'Téléphone', 'Adresse', 'Code postal', 'Ville', 'Statut', 'Commercial', 'Nb commandes', 'Dernière commande'], ';');

        Pharmacie::with(['commercial', 'commandes' => fn($q) => $q->latest()->limit(1)])
            ->withCount('commandes')
            ->orderBy('nom')
            ->each(function ($p) use ($handle) {
                fputcsv($handle, [
                    $p->nom,
                    $p->siret ?? '',
                    $p->email ?? '',
                    $p->telephone ?? '',
                    $p->adresse ?? '',
                    $p->code_postal ?? '',
                    $p->ville ?? '',
                    $p->statut,
                    $p->commercial?->name ?? '',
                    $p->commandes_count,
                    $p->commandes->first()?->created_at->format('d/m/Y') ?? 'Jamais',
                ], ';');
            });
    }

    private function buildRelancesCsv($handle): void
    {
        fputcsv($handle, ['Pharmacie', 'Email', 'Téléphone', 'Ville', 'Dernière commande', 'Jours inactif', 'Commercial'], ';');

        $relances = Pharmacie::where('statut', 'client_actif')
            ->with(['commercial', 'commandes' => fn($q) => $q->latest()->limit(1)])
            ->get()
            ->filter(fn($p) => !$p->commandes->first() || $p->commandes->first()->created_at->lt(now()->subDays(30)))
            ->sortByDesc(fn($p) => $p->commandes->first() ? $p->commandes->first()->created_at->diffInDays(now()) : 9999);

        foreach ($relances as $p) {
            $derniere = $p->commandes->first();
            $jours    = $derniere ? (int) $derniere->created_at->diffInDays(now()) : 'N/A';
            fputcsv($handle, [
                $p->nom,
                $p->email ?? '',
                $p->telephone ?? '',
                $p->ville ?? '',
                $derniere?->created_at->format('d/m/Y') ?? 'Jamais',
                $jours,
                $p->commercial?->name ?? '',
            ], ';');
        }
    }

    private function buildCaMensuelCsv($handle): void
    {
        fputcsv($handle, ['Mois', 'Nb commandes', 'CA (€)', 'CA moyen / commande (€)'], ';');

        $driver = DB::connection()->getDriverName();
        $moisExpr = $driver === 'pgsql'
            ? "to_char(created_at, 'YYYY-MM')"
            : "strftime('%Y-%m', created_at)";

        $rows = Commande::selectRaw("{$moisExpr} as mois, COUNT(*) as nb, SUM(tarif_unitaire * quantite) as ca")
            ->groupBy('mois')
            ->orderBy('mois')
            ->get();

        foreach ($rows as $row) {
            fputcsv($handle, [
                $row->mois,
                $row->nb,
                number_format($row->ca, 2, '.', ''),
                number_format($row->nb > 0 ? $row->ca / $row->nb : 0, 2, '.', ''),
            ], ';');
        }
    }
}
