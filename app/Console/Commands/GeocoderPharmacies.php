<?php

namespace App\Console\Commands;

use App\Models\Pharmacie;
use App\Services\GeocodageService;
use Illuminate\Console\Command;

class GeocoderPharmacies extends Command
{
    protected $signature = 'pharmacies:geocode';
    protected $description = 'Géocoder les pharmacies avec adresse et sans coordonnées GPS';

    public function handle(): void
    {
        $pharmacies = Pharmacie::whereNull('latitude')
            ->orWhereNull('longitude')
            ->get();

        if ($pharmacies->isEmpty()) {
            $this->info('Toutes les pharmacies ont déjà des coordonnées.');
            return;
        }

        $service = new GeocodageService();

        foreach ($pharmacies as $pharmacie) {
            $this->info("Géocodage : {$pharmacie->nom}");

            if (! $service->geocoder($pharmacie)) {
                $this->warn("Aucune coordonnée trouvée.");
                continue;
            }

            $pharmacie->save();
            $this->info("OK -> lat: {$pharmacie->latitude} / lon: {$pharmacie->longitude}");

            sleep(1);
        }

        $this->info('Géocodage terminé.');
    }
}
