<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Pharmacie;
use App\Models\Zone;

class LierZonesPharmacies extends Command
{
    protected $signature = 'pharmacies:lier-zones';
    protected $description = 'Associe chaque pharmacie existante à une zone de prospection via le code postal';

    public function handle(): void
    {
        $pharmacies = Pharmacie::all();
        $i = 0;
        $x = 0;

        foreach ($pharmacies as $pharmacie) {
            if (!$pharmacie->code_postal) {
                $x++;
                continue;
            }

            $dep = substr($pharmacie->code_postal, 0, 2);

            $zone = Zone::where('code_departement', $dep)->first();

            if ($zone) {
                $pharmacie->zone_id = $zone->id;
                $pharmacie->save();
                $i++;
            } else {
                $x++;
            }
        }

        $this->info("Pharmacies liées à une zone : {$i}");
        $this->warn("Pharmacies sans zone trouvée : {$x}");
    }
}
