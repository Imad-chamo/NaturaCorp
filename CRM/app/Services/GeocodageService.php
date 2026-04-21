<?php

namespace App\Services;

use App\Models\Pharmacie;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class GeocodageService
{
    public function geocoder(Pharmacie $pharmacie): bool
    {
        $adresse = "{$pharmacie->adresse}, {$pharmacie->code_postal} {$pharmacie->ville}, France";
        $cacheKey = 'geocode_' . md5($adresse);

        $coords = Cache::remember($cacheKey, now()->addDays(30), function () use ($adresse) {
            try {
                // Nominatim impose 1 requête/seconde
                usleep(1_100_000);

                $response = Http::timeout(10)->withHeaders([
                    'User-Agent' => 'NaturaCorpCRM/1.0 (contact@naturacorp.test)',
                ])->get('https://nominatim.openstreetmap.org/search', [
                    'q'      => $adresse,
                    'format' => 'json',
                    'limit'  => 1,
                ]);

                if ($response->failed() || empty($response->json()[0])) {
                    return null;
                }

                $data = $response->json()[0];
                return ['lat' => (float) $data['lat'], 'lon' => (float) $data['lon']];
            } catch (\Exception $e) {
                Log::warning("Géocodage échoué pour « {$adresse} » : {$e->getMessage()}");
                return null;
            }
        });

        if ($coords === null) {
            return false;
        }

        $pharmacie->latitude  = $coords['lat'];
        $pharmacie->longitude = $coords['lon'];

        return true;
    }
}
