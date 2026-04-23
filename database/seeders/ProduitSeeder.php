<?php

namespace Database\Seeders;

use App\Models\Produit;
use Illuminate\Database\Seeder;

class ProduitSeeder extends Seeder
{
    public function run(): void
    {
        Produit::firstOrCreate(
            ['nom' => 'Oméga-3 Premium'],
            [
                'description' => 'EPA 600mg + DHA 400mg par capsule. Huile de poisson sauvage de qualité pharmaceutique, certification IFOS 5 étoiles. Encapsulation à froid, absence de goût de poisson garanti.',
                'categorie'   => 'immunite',
                'tarif_unitaire' => 28.90,
                'stock'       => 145,
                'stock_alerte' => 20,
                'is_actif'    => true,
            ]
        );
    }
}
