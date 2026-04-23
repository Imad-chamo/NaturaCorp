<?php
namespace App\Http\Controllers;

use App\Models\{Commande, NotificationInterne, Pharmacie, User};
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        $stats = array_merge(
            $this->getStatsPharmacies(),
            $this->getStatsCommandes(),
            $this->getStatsTemporaires()
        );

        $demandes = Pharmacie::where('statut', 'prospect')
            ->orderByDesc('created_at')
            ->limit(5)
            ->get();

        $commandes_recentes = \App\Models\Commande::with(['pharmacie', 'produit'])
            ->orderByDesc('created_at')
            ->limit(6)
            ->get();

        return view('dashboard', compact('stats', 'demandes', 'commandes_recentes'));
    }

    private function getStatsPharmacies(): array
    {
        return [
            'pharmacies_total' => Pharmacie::count(),
            'pharmacies_par_statut' => Pharmacie::select('statut', DB::raw('count(*) as total'))
                ->groupBy('statut')->pluck('total', 'statut'),
            'prospects_mois' => Pharmacie::where('statut', 'prospect')
                ->whereMonth('created_at', now()->month)->count(),
            'demandes_attente' => Pharmacie::where('statut', 'prospect')->count(),
            'pharmacies_sans_commandes' => Pharmacie::where('statut', 'client_actif')
                ->whereDoesntHave('commandes')->count(),
            'pharmacies_inactives' => Pharmacie::where('statut', 'client_actif')
                ->whereDoesntHave('commandes', fn($q) =>
                $q->where('created_at', '>=', now()->subDays(60))
                )->count(),
        ];
    }

    private function getStatsCommandes(): array
    {
        $caTotal = Commande::selectRaw('SUM(tarif_unitaire * quantite) as ca')->value('ca') ?? 0;
        $caMois  = Commande::whereMonth('created_at', now()->month)
            ->selectRaw('SUM(tarif_unitaire * quantite) as ca')->value('ca') ?? 0;

        $topPharmacies = Pharmacie::withCount('commandes')
            ->withSum('commandes', DB::raw('tarif_unitaire * quantite'))
            ->where('statut', 'client_actif')
            ->orderByDesc('commandes_count')
            ->limit(5)
            ->get();

        return [
            'commandes_total' => Commande::count(),
            'commandes_par_statut' => Commande::select('statut', DB::raw('count(*) as total'))
                ->groupBy('statut')->pluck('total', 'statut'),
            'commandes_mois' => Commande::whereMonth('created_at', now()->month)->count(),
            'commandes_jour' => Commande::select(DB::raw("DATE(created_at) as date"), DB::raw("count(*) as total"))
                ->groupBy('date')->orderBy('date')->pluck('total', 'date'),
            'commande_moyenne_par_pharmacie' => Pharmacie::count() > 0 ?
                round(Commande::count() / Pharmacie::count(), 2) : 0,
            'commandes_retard' => Commande::where('statut', 'en_cours')
                ->where('created_at', '<=', now()->subDays(10))->count(),
            'ca_total' => round($caTotal, 2),
            'ca_mois'  => round($caMois, 2),
            'top_pharmacies' => $topPharmacies,
        ];
    }

    private function getStatsTemporaires(): array
    {
        $moisExpr = DB::connection()->getDriverName() === 'pgsql'
            ? "to_char(created_at, 'YYYY-MM')"
            : "strftime('%Y-%m', created_at)";

        return [
            'evolution_commandes' => Commande::select(DB::raw("{$moisExpr} as mois"), DB::raw("count(*) as total"))
                ->groupBy('mois')->orderBy('mois')->pluck('total', 'mois'),
            'evolution_pharmacies' => Pharmacie::select(DB::raw("{$moisExpr} as mois"), DB::raw("count(*) as total"))
                ->groupBy('mois')->orderBy('mois')->pluck('total', 'mois'),
        ];
    }


}
