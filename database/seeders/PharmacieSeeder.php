<?php

namespace Database\Seeders;

use App\Models\Pharmacie;
use App\Models\Produit;
use App\Models\Zone;
use Illuminate\Database\Seeder;

class PharmacieSeeder extends Seeder
{
    public function run(): void
    {
        // Produits de base si aucun n'existe
        if (Produit::count() === 0) {
            $produits = [
                ['nom' => 'Oméga-3 Premium',        'tarif_unitaire' => 18.50, 'stock' => 200, 'is_actif' => true],
                ['nom' => 'Vitamine D3 + K2',        'tarif_unitaire' => 14.90, 'stock' => 300, 'is_actif' => true],
                ['nom' => 'Curcuma & Poivre Noir',   'tarif_unitaire' => 16.00, 'stock' => 150, 'is_actif' => true],
                ['nom' => 'Magnésium Marin',          'tarif_unitaire' => 12.50, 'stock' => 250, 'is_actif' => true],
                ['nom' => 'Mélatonine & Passiflore', 'tarif_unitaire' => 11.90, 'stock' => 180, 'is_actif' => true],
                ['nom' => 'Probiotiques Pro 50',      'tarif_unitaire' => 24.90, 'stock' => 120, 'is_actif' => true],
                ['nom' => 'Vitamine C Liposomale',   'tarif_unitaire' => 22.00, 'stock' => 160, 'is_actif' => true],
                ['nom' => 'Zinc & Sélénium',          'tarif_unitaire' => 9.90,  'stock' => 200, 'is_actif' => true],
                ['nom' => 'Complexe B Actif',         'tarif_unitaire' => 13.50, 'stock' => 140, 'is_actif' => true],
            ];
            foreach ($produits as $p) {
                Produit::create($p);
            }
        }

        $pharmacies = [
            // Paris (zone 75)
            ['nom' => 'Pharmacie du Panthéon',        'siret' => '12345678901234', 'email' => 'pantheon@pharma.fr',    'telephone' => '01 43 26 11 22', 'adresse' => '15 Rue Soufflot',            'code_postal' => '75005', 'ville' => 'Paris',      'statut' => 'client_actif',   'commercial_id' => 2, 'latitude' => 48.8462, 'longitude' => 2.3441],
            ['nom' => 'Pharmacie de la Bastille',     'siret' => '23456789012345', 'email' => 'bastille@pharma.fr',    'telephone' => '01 43 07 31 02', 'adresse' => '5 Place de la Bastille',     'code_postal' => '75011', 'ville' => 'Paris',      'statut' => 'client_actif',   'commercial_id' => 2, 'latitude' => 48.8533, 'longitude' => 2.3692],
            ['nom' => 'Pharmacie Opéra',              'siret' => '34567890123456', 'email' => 'opera@pharma.fr',       'telephone' => '01 47 42 49 40', 'adresse' => '6 Boulevard des Capucines',  'code_postal' => '75009', 'ville' => 'Paris',      'statut' => 'prospect',       'commercial_id' => 2, 'latitude' => 48.8712, 'longitude' => 2.3311],
            ['nom' => 'Grande Pharmacie de Paris',    'siret' => '45678901234567', 'email' => 'gpp@pharma.fr',         'telephone' => '01 48 05 63 19', 'adresse' => '22 Rue du Faubourg',         'code_postal' => '75010', 'ville' => 'Paris',      'statut' => 'client_inactif', 'commercial_id' => 2, 'latitude' => 48.8720, 'longitude' => 2.3560],

            // Lyon (zone 69)
            ['nom' => 'Pharmacie Bellecour',          'siret' => '56789012345678', 'email' => 'bellecour@pharma.fr',   'telephone' => '04 78 42 12 12', 'adresse' => '2 Place Bellecour',          'code_postal' => '69002', 'ville' => 'Lyon',       'statut' => 'client_actif',   'commercial_id' => 3, 'latitude' => 45.7580, 'longitude' => 4.8320],
            ['nom' => 'Pharmacie de la Croix-Rousse', 'siret' => '67890123456789', 'email' => 'croixrousse@pharma.fr','telephone' => '04 78 28 44 21', 'adresse' => '18 Boulevard de la CR',      'code_postal' => '69004', 'ville' => 'Lyon',       'statut' => 'client_actif',   'commercial_id' => 3, 'latitude' => 45.7742, 'longitude' => 4.8272],
            ['nom' => 'Pharmacie Part-Dieu',          'siret' => '78901234567890', 'email' => 'partdieu@pharma.fr',    'telephone' => '04 72 34 78 90', 'adresse' => '17 Rue du Docteur Bouchut',  'code_postal' => '69003', 'ville' => 'Lyon',       'statut' => 'prospect',       'commercial_id' => 3, 'latitude' => 45.7600, 'longitude' => 4.8590],

            // Marseille (zone 13)
            ['nom' => 'Pharmacie du Vieux-Port',      'siret' => '89012345678901', 'email' => 'vieuxport@pharma.fr',   'telephone' => '04 91 33 45 67', 'adresse' => '4 Quai du Port',             'code_postal' => '13002', 'ville' => 'Marseille',  'statut' => 'client_actif',   'commercial_id' => 4, 'latitude' => 43.2965, 'longitude' => 5.3698],
            ['nom' => 'Pharmacie Castellane',         'siret' => '90123456789012', 'email' => 'castellane@pharma.fr',  'telephone' => '04 91 53 21 43', 'adresse' => '12 Rue de Rome',             'code_postal' => '13006', 'ville' => 'Marseille',  'statut' => 'client_actif',   'commercial_id' => 4, 'latitude' => 43.2907, 'longitude' => 5.3750],

            // Toulouse (zone 31)
            ['nom' => 'Pharmacie du Capitole',        'siret' => '01234567890123', 'email' => 'capitole@pharma.fr',    'telephone' => '05 61 21 33 44', 'adresse' => '1 Place du Capitole',        'code_postal' => '31000', 'ville' => 'Toulouse',   'statut' => 'client_actif',   'commercial_id' => 5, 'latitude' => 43.6047, 'longitude' => 1.4442],
            ['nom' => 'Pharmacie Saint-Cyprien',      'siret' => '11223344556677', 'email' => 'stcyprien@pharma.fr',   'telephone' => '05 61 53 77 12', 'adresse' => '28 Allées Charles-de-Fitte', 'code_postal' => '31300', 'ville' => 'Toulouse',   'statut' => 'prospect',       'commercial_id' => 5, 'latitude' => 43.5980, 'longitude' => 1.4275],

            // Bordeaux (zone 33)
            ['nom' => 'Pharmacie des Quinconces',     'siret' => '22334455667788', 'email' => 'quinconces@pharma.fr',  'telephone' => '05 56 44 21 33', 'adresse' => '5 Place des Quinconces',     'code_postal' => '33000', 'ville' => 'Bordeaux',   'statut' => 'client_actif',   'commercial_id' => 5, 'latitude' => 44.8456, 'longitude' => -0.5736],

            // Nice (zone 06)
            ['nom' => 'Pharmacie de la Promenade',    'siret' => '33445566778899', 'email' => 'promenade@pharma.fr',   'telephone' => '04 93 87 22 11', 'adresse' => '10 Promenade des Anglais',   'code_postal' => '06000', 'ville' => 'Nice',       'statut' => 'client_actif',   'commercial_id' => 4, 'latitude' => 43.6960, 'longitude' => 7.2660],

            // Nantes (zone 44)
            ['nom' => 'Pharmacie du Commerce',        'siret' => '44556677889900', 'email' => 'commerce@pharma.fr',    'telephone' => '02 40 35 11 22', 'adresse' => '3 Place du Commerce',        'code_postal' => '44000', 'ville' => 'Nantes',     'statut' => 'client_actif',   'commercial_id' => 3, 'latitude' => 47.2120, 'longitude' => -1.5551],
            ['nom' => 'Pharmacie Bouffay',            'siret' => '55667788990011', 'email' => 'bouffay@pharma.fr',     'telephone' => '02 40 47 66 33', 'adresse' => '7 Rue de la Bâclerie',       'code_postal' => '44000', 'ville' => 'Nantes',     'statut' => 'prospect',       'commercial_id' => 3, 'latitude' => 47.2152, 'longitude' => -1.5534],

            // Strasbourg (zone 67)
            ['nom' => 'Pharmacie de la Cathédrale',   'siret' => '66778899001122', 'email' => 'cathedrale@pharma.fr',  'telephone' => '03 88 32 21 44', 'adresse' => '2 Place de la Cathédrale',   'code_postal' => '67000', 'ville' => 'Strasbourg', 'statut' => 'client_actif',   'commercial_id' => 2, 'latitude' => 48.5839, 'longitude' => 7.7455],

            // Lille (zone 59)
            ['nom' => 'Pharmacie du Grand Place',     'siret' => '77889900112233', 'email' => 'grandplace@pharma.fr',  'telephone' => '03 20 54 33 11', 'adresse' => '8 Grand Place',              'code_postal' => '59000', 'ville' => 'Lille',           'statut' => 'client_actif',   'commercial_id' => 2, 'latitude' => 50.6372, 'longitude' => 3.0630],
            ['nom' => 'Pharmacie Vieux-Lille',        'siret' => '88990011223344', 'email' => 'vieuxlille@pharma.fr',  'telephone' => '03 20 74 12 56', 'adresse' => '14 Rue de la Monnaie',       'code_postal' => '59800', 'ville' => 'Lille',           'statut' => 'prospect',       'commercial_id' => 2, 'latitude' => 50.6428, 'longitude' => 3.0699],

            // Montpellier (zone 34)
            ['nom' => 'Pharmacie de la Comédie',      'siret' => '10203040506070', 'email' => 'comedie@pharma.fr',     'telephone' => '04 67 58 10 11', 'adresse' => '1 Place de la Comédie',      'code_postal' => '34000', 'ville' => 'Montpellier',     'statut' => 'client_actif',   'commercial_id' => 4, 'latitude' => 43.6083, 'longitude' => 3.8800],
            ['nom' => 'Pharmacie Antigone',           'siret' => '20304050607080', 'email' => 'antigone@pharma.fr',    'telephone' => '04 67 64 22 33', 'adresse' => '14 Place du Nombre d Or',    'code_postal' => '34000', 'ville' => 'Montpellier',     'statut' => 'prospect',       'commercial_id' => 4, 'latitude' => 43.6095, 'longitude' => 3.8930],

            // Rennes (zone 35)
            ['nom' => 'Pharmacie du Parlement',       'siret' => '30405060708090', 'email' => 'parlement@pharma.fr',   'telephone' => '02 99 79 10 10', 'adresse' => '2 Place du Parlement',       'code_postal' => '35000', 'ville' => 'Rennes',          'statut' => 'client_actif',   'commercial_id' => 3, 'latitude' => 48.1127, 'longitude' => -1.6745],
            ['nom' => 'Pharmacie Sainte-Anne',        'siret' => '40506070809010', 'email' => 'saintanne@pharma.fr',   'telephone' => '02 99 38 55 66', 'adresse' => '9 Place Sainte-Anne',        'code_postal' => '35000', 'ville' => 'Rennes',          'statut' => 'client_actif',   'commercial_id' => 3, 'latitude' => 48.1143, 'longitude' => -1.6797],

            // Grenoble (zone 38)
            ['nom' => 'Pharmacie Victor Hugo',        'siret' => '50607080901020', 'email' => 'victorhugo@pharma.fr',  'telephone' => '04 76 47 10 22', 'adresse' => '10 Place Victor Hugo',       'code_postal' => '38000', 'ville' => 'Grenoble',        'statut' => 'client_actif',   'commercial_id' => 3, 'latitude' => 45.1875, 'longitude' => 5.7268],

            // Toulon (zone 83)
            ['nom' => 'Pharmacie du Port',            'siret' => '60708090102030', 'email' => 'port@pharma.fr',        'telephone' => '04 94 92 21 31', 'adresse' => '7 Avenue de la République', 'code_postal' => '83000', 'ville' => 'Toulon',          'statut' => 'client_actif',   'commercial_id' => 4, 'latitude' => 43.1258, 'longitude' => 5.9306],

            // Dijon (zone 21)
            ['nom' => 'Pharmacie des Ducs',           'siret' => '70809010203040', 'email' => 'ducs@pharma.fr',        'telephone' => '03 80 30 33 44', 'adresse' => '1 Place de la Libération',   'code_postal' => '21000', 'ville' => 'Dijon',           'statut' => 'client_actif',   'commercial_id' => 2, 'latitude' => 47.3220, 'longitude' => 5.0415],
            ['nom' => 'Pharmacie Darcy',              'siret' => '80901020304050', 'email' => 'darcy@pharma.fr',       'telephone' => '03 80 30 48 59', 'adresse' => '5 Place Darcy',              'code_postal' => '21000', 'ville' => 'Dijon',           'statut' => 'prospect',       'commercial_id' => 2, 'latitude' => 47.3240, 'longitude' => 5.0385],

            // Reims (zone 51)
            ['nom' => 'Pharmacie de la Cathédrale',   'siret' => '91020304050607', 'email' => 'cathedrale51@pharma.fr','telephone' => '03 26 47 11 22', 'adresse' => '3 Place du Cardinal Luçon',  'code_postal' => '51100', 'ville' => 'Reims',           'statut' => 'client_actif',   'commercial_id' => 2, 'latitude' => 49.2534, 'longitude' => 4.0317],

            // Rouen (zone 76)
            ['nom' => 'Pharmacie du Vieux-Marché',    'siret' => '11020304050607', 'email' => 'vieuxmarche@pharma.fr', 'telephone' => '02 35 71 20 30', 'adresse' => '4 Place du Vieux-Marché',    'code_postal' => '76000', 'ville' => 'Rouen',           'statut' => 'client_actif',   'commercial_id' => 2, 'latitude' => 49.4419, 'longitude' => 1.0920],
            ['nom' => 'Pharmacie Saint-Maclou',       'siret' => '21030405060708', 'email' => 'stmaclou@pharma.fr',    'telephone' => '02 35 88 42 11', 'adresse' => '10 Place Barthélémy',        'code_postal' => '76000', 'ville' => 'Rouen',           'statut' => 'prospect',       'commercial_id' => 2, 'latitude' => 49.4440, 'longitude' => 1.0951],

            // Clermont-Ferrand (zone 63)
            ['nom' => 'Pharmacie Jaude',              'siret' => '31040506070809', 'email' => 'jaude@pharma.fr',       'telephone' => '04 73 92 11 22', 'adresse' => '2 Place de Jaude',           'code_postal' => '63000', 'ville' => 'Clermont-Ferrand','statut' => 'client_actif',   'commercial_id' => 5, 'latitude' => 45.7797, 'longitude' => 3.0873],

            // Nancy (zone 54)
            ['nom' => 'Pharmacie Stanislas',          'siret' => '41050607080910', 'email' => 'stanislas@pharma.fr',   'telephone' => '03 83 35 12 23', 'adresse' => '6 Place Stanislas',          'code_postal' => '54000', 'ville' => 'Nancy',           'statut' => 'client_actif',   'commercial_id' => 2, 'latitude' => 48.6937, 'longitude' => 6.1823],

            // Bordeaux 2ème (zone 33)
            ['nom' => 'Pharmacie Sainte-Croix',       'siret' => '51060708091011', 'email' => 'stecroix@pharma.fr',    'telephone' => '05 56 52 33 44', 'adresse' => '11 Place Pierre Renaudel',   'code_postal' => '33800', 'ville' => 'Bordeaux',        'statut' => 'prospect',       'commercial_id' => 5, 'latitude' => 44.8374, 'longitude' => -0.5648],

            // Angers (zone 49)
            ['nom' => 'Pharmacie du Ralliement',      'siret' => '61070809101112', 'email' => 'ralliement@pharma.fr',  'telephone' => '02 41 88 10 20', 'adresse' => '3 Place du Ralliement',      'code_postal' => '49000', 'ville' => 'Angers',          'statut' => 'client_actif',   'commercial_id' => 3, 'latitude' => 47.4712, 'longitude' => -0.5527],

            // Metz (zone 57)
            ['nom' => 'Pharmacie de la République',   'siret' => '71080910111213', 'email' => 'republique57@pharma.fr','telephone' => '03 87 75 22 11', 'adresse' => '5 Place de la République',   'code_postal' => '57000', 'ville' => 'Metz',            'statut' => 'client_actif',   'commercial_id' => 2, 'latitude' => 49.1196, 'longitude' => 6.1757],

            // Perpignan (zone 66)
            ['nom' => 'Pharmacie de la Loge',         'siret' => '81090101112131', 'email' => 'loge@pharma.fr',        'telephone' => '04 68 34 11 22', 'adresse' => '7 Place de la Loge',         'code_postal' => '66000', 'ville' => 'Perpignan',       'statut' => 'prospect',       'commercial_id' => 4, 'latitude' => 42.6986, 'longitude' => 2.8950],

            // Aix-en-Provence (zone 13)
            ['nom' => 'Pharmacie du Cours Mirabeau',  'siret' => '91101112131415', 'email' => 'mirabeau@pharma.fr',    'telephone' => '04 42 38 10 20', 'adresse' => '20 Cours Mirabeau',          'code_postal' => '13100', 'ville' => 'Aix-en-Provence', 'statut' => 'client_actif',   'commercial_id' => 4, 'latitude' => 43.5265, 'longitude' => 5.4474],

            // Caen (zone 14)
            ['nom' => 'Pharmacie de l Abbaye',        'siret' => '10111213141516', 'email' => 'abbaye@pharma.fr',      'telephone' => '02 31 86 10 20', 'adresse' => '4 Place Reine Mathilde',     'code_postal' => '14000', 'ville' => 'Caen',            'statut' => 'client_actif',   'commercial_id' => 3, 'latitude' => 49.1829, 'longitude' => -0.3704],

            // Tours (zone 37)
            ['nom' => 'Pharmacie de la Place Plum',   'siret' => '20121314151617', 'email' => 'plumereau@pharma.fr',   'telephone' => '02 47 64 11 22', 'adresse' => '2 Place Plumereau',          'code_postal' => '37000', 'ville' => 'Tours',           'statut' => 'client_actif',   'commercial_id' => 3, 'latitude' => 47.3934, 'longitude' => 0.6848],
        ];

        foreach ($pharmacies as $data) {
            Pharmacie::create($data);
        }
    }
}
