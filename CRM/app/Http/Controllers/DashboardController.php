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

        return view('dashboard', compact('stats'));
    }

    private function getStatsPharmacies(): array
    {
        return [
            'pharmacies_total' => Pharmacie::count(),
            'pharmacies_par_statut' => Pharmacie::select('statut', DB::raw('count(*) as total'))
                ->groupBy('statut')->pluck('total', 'statut'),
            'prospects_mois' => Pharmacie::where('statut', 'prospect')
                ->whereMonth('created_at', now()->month)->count(),
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
