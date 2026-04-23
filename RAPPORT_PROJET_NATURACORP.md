# NaturaCorp — Rapport technique complet du projet
**Date de génération :** 23 avril 2026  
**Projet :** Bachelor développement web — CRM B2B + Vitrine publique  
**Auteur :** Imad Chamkhi

---

## Stack technologique

| Couche | Technologies |
|--------|-------------|
| **Backend** | Laravel 11, PHP 8.2, Eloquent ORM |
| **Auth & Permissions** | Laravel Breeze + Spatie laravel-permission (rôles : admin, commercial, logistique) |
| **Base de données** | SQLite (développement) / PostgreSQL (production compatible) |
| **Frontend CRM** | Blade templates, Alpine.js, Tailwind CDN, ApexCharts |
| **Carte interactive** | Leaflet.js + Leaflet.MarkerCluster, tuiles CartoDB Light |
| **Génération PDF** | DomPDF (barrethcat/dompdf) |
| **Vitrine publique** | HTML/CSS/JS vanilla, Tailwind CDN, Cormorant Garamond + DM Sans |
| **Monitoring** | Laravel Telescope |
| **Stockage fichiers** | Laravel Storage (disque local) |

---

## 1. Architecture du projet

```
Naturacorp/
├── app/
│   ├── Console/Commands/        # Commandes Artisan (geocodage, zones)
│   ├── Enums/StatutCommande.php # Enum statuts commande
│   ├── Http/Controllers/        # 17 controllers
│   ├── Models/                  # 9 modèles Eloquent
│   ├── Policies/                # Policies commandes & pharmacies
│   ├── Providers/               # AppServiceProvider, AuthServiceProvider
│   └── Services/                # GeocodageService, JournalService
├── database/
│   ├── migrations/              # 20+ migrations
│   └── seeders/                 # Seeders rôles, produits, pharmacies
├── resources/views/
│   ├── layouts/                 # app.blade.php, sidebar, navbar
│   ├── components/              # Modaux, cartes, cloche notifications
│   ├── dashboard.blade.php
│   ├── carte/                   # Carte Leaflet interactive
│   ├── commandes/
│   ├── pharmacies/
│   ├── produits/
│   ├── rapports/
│   ├── relances/
│   ├── demandes/
│   └── users/
├── public/vitrine/              # 12 pages HTML vitrine publique
└── routes/web.php               # Routes CRM + API publique
```

---

## 2. Controllers — rôle et fonctions clés

### Controllers CRM

| Controller | Rôle | Méthodes principales |
|-----------|------|---------------------|
| `DashboardController` | Tableau de bord | `index` (stats globales, top 5 pharmacies, CA total/mois), `chartCommandes` (JSON ApexCharts) |
| `PharmacieController` | CRUD pharmacies | `index`, `store`, `update`, `destroy` + geocodage automatique (lat/lng via Nominatim) |
| `CommandeController` | CRUD commandes | `index`, `store`, `update`, `destroy`, `pdf` (DomPDF), `updateStatut` (PATCH) |
| `ProduitController` | CRUD produits (admin) + API publique | `index`, `store`, `update`, `destroy`, `publicIndex` (sans auth, pour la vitrine) |
| `CarteController` | Carte Leaflet | `index` avec `withCount('commandes')`, filtres statut/commercial/ville passés en JSON à Alpine.js |
| `RapportController` | Génération CSV + historique | `index` (6 KPIs), `generate` (4 types CSV), `show` (download fichier), `destroy` |
| `RelanceController` | Pharmacies à relancer | `index` : pharmacies actives sans commande depuis 30+ jours |
| `DemandePartenaireController` | Prospects vitrine | `index`, `show`, `accept` (→ client_actif), `reject` (suppression), `export` CSV |
| `UserController` | Gestion utilisateurs (admin) | `index`, `store`, `update`, `destroy`, assignation rôles + zones géographiques |
| `NotificationInterneController` | Notifications temps réel | `fetch`, `markAsRead`, `markAllAsRead`, `destroy` |
| `JournalActiviteController` | Journal admin | `index` paginé avec filtres utilisateur/action/date |
| `SearchController` | Recherche globale | `index` : recherche cross-entités (pharmacies, commandes, produits) |
| `DocumentJointController` | Pièces jointes | `index`, `store` (upload), `destroy` (fichier + DB) |

### Controllers API publique (sans authentification)

| Controller | Rôle |
|-----------|------|
| `CommandePublicController` | `pharmacies` (liste pour formulaire commande vitrine), `store` (création commande anonyme) |
| `ContactPublicController` | `store` : crée une `NotificationInterne` CRM + demande partenaire |

---

## 3. Modèles Eloquent

| Modèle | Table | Relations clés |
|--------|-------|----------------|
| `User` | `users` | `hasMany(Commande)`, `belongsToMany(Zone)`, rôles Spatie |
| `Pharmacie` | `pharmacies` | `hasMany(Commande)`, `belongsTo(User)` commercial, `hasMany(DocumentJoint)`, lat/lng |
| `Commande` | `commandes` | `belongsTo(Pharmacie)`, `belongsTo(Produit)`, `belongsTo(User)`, enum `StatutCommande` |
| `Produit` | `produits` | `hasMany(Commande)`, champs nom/description/catégorie/tarif/stock/image |
| `Rapport` | `rapports` | `belongsTo(User)`, titre/type/chemin_fichier/filtres (JSON) |
| `Zone` | `zones` | `belongsToMany(User)` — départements assignés aux commerciaux |
| `DocumentJoint` | `document_joints` | `belongsTo(Pharmacie)`, `belongsTo(Commande)` (nullable) |
| `NotificationInterne` | `notification_internes` | `belongsTo(User)` destinataire, lu/non-lu |
| `JournalActivite` | `journal_activites` | `belongsTo(User)`, entite/action/details |

### Enum `StatutCommande`
```
en_attente → validee → en_cours → livree → annulee
```

---

## 4. Base de données — migrations principales

| Migration | Description |
|-----------|-------------|
| `create_users_table` | Table utilisateurs (Breeze) |
| `create_permission_tables` | Tables Spatie (roles, permissions, model_has_roles) |
| `create_pharmacies_table` | nom, siret, email, tel, adresse, ville, cp, statut, lat, lng, user_id |
| `create_commandes_table` | pharmacie_id, produit_id, user_id, quantite, tarif_unitaire, statut |
| `create_document_joints_table` | chemin, nom, type, pharmacie_id, commande_id (nullable) |
| `create_rapports_table` | titre, type, chemin_fichier, user_id, filtres (JSON) |
| `create_zones_table` + `create_user_zone_table` | Zones géographiques par département |
| `create_produits_table` | nom, description, categorie, tarif_unitaire, stock, image_path |
| `create_notification_internes_table` | message, type, lu, user_id |
| `create_journal_activites_table` | user_id, action, entite, entite_id, details |
| `add_coordinates_to_pharmacies` | Ajout colonnes lat/lng |
| `add_description_categorie_to_produits` | Ajout colonnes description/catégorie |

---

## 5. Routes

```
GET  /                           → redirect vitrine
GET  /api/produits               → ProduitController@publicIndex (public)
GET  /api/pharmacies             → CommandePublicController@pharmacies (public)
POST /api/contact                → ContactPublicController@store (public)
POST /api/commande               → CommandePublicController@store (public)

[auth]
GET  /dashboard                  → DashboardController@index
GET  /dashboard/data/commandes   → DashboardController@chartCommandes

[auth + role:admin]
RESOURCE /produits               → ProduitController
RESOURCE /users                  → UserController
GET  /logs                       → JournalActiviteController@index
GET  /rapports                   → RapportController@index
POST /rapports/generate          → RapportController@generate
GET  /rapports/{rapport}/download → RapportController@show
DELETE /rapports/{rapport}       → RapportController@destroy

[auth + role:admin|commercial]
RESOURCE /pharmacies             → PharmacieController
RESOURCE /commandes              → CommandeController
GET  /commandes/{commande}/pdf   → CommandeController@pdf
PATCH /commandes/{commande}/statut → CommandeController@updateStatut
RESOURCE /documents              → DocumentJointController
GET  /relances                   → RelanceController@index
GET  /demandes                   → DemandePartenaireController@index
PATCH /demandes/{pharmacie}/accept → DemandePartenaireController@accept
DELETE /demandes/{pharmacie}/reject → DemandePartenaireController@reject

[auth]
GET  /carte                      → CarteController@index
GET  /search                     → SearchController@index
GET|POST /notifications/*        → NotificationInterneController
GET|PATCH|DELETE /profile        → ProfileController
```

---

## 6. Vues Blade — CRM

### Layouts

| Fichier | Rôle |
|---------|------|
| `layouts/app.blade.php` | Layout principal : sidebar + navbar + zone contenu |
| `layouts/sidebar.blade.php` | Navigation gauche avec liens conditionnels par rôle (`@role` Spatie) |
| `layouts/navbar.blade.php` | Barre haute : breadcrumb dynamique, recherche globale, cloche notifications, avatar |

### Vues principales

| Vue | Fonctionnalités implémentées |
|-----|------------------------------|
| `dashboard.blade.php` | Header avec boutons actions rapides, bannière demandes partenaires avec gradient |
| `components/dashboard-global-stats.blade.php` | 4 KPI cards (CA Total, CA mois, Pharmacies actives, Retards), 2 donuts ApexCharts (statuts pharmacies + commandes), Top 5 pharmacies avec barres progression colorées par rang, area chart évolution commandes (2 séries), tableau commandes récentes avec avatars initiales + totaux cliquables, panel mini-stats + 3 liens action rapide |
| `carte/index.blade.php` | Barre de stats globales (Actifs/Prospects/Inactifs/Total), grid 300px panel gauche + carte pleine hauteur, filtres (texte + statut + commercial + ville), liste pharmacies avec point coloré par statut + avertissement sans coordonnées, `flyTo()` sur clic, clustering vert MarkerCluster, popups riches avec infos complètes |
| `rapports/index.blade.php` | 4 KPI cards (commandes totales, CA, pharmacies actives, à relancer), 4 cartes de génération CSV avec description + preview colonnes + badge format, tableau historique avec icône colorée par type + boutons télécharger/supprimer, état vide |
| `components/user-form-modal.blade.php` | Avatar initial dynamique, champ nom + email, password avec eye toggle + 4 barres de force + label, 3 cartes rôle visuelles (Admin/Commercial/Logistique), toggle switch CSS-only actif/inactif, checkboxes zones animées, bouton suppression avec confirm |

---

## 7. Vitrine publique

### Pages

| Page | Contenu |
|------|---------|
| `index.html` | Hero (stats 30+ produits, 500+ pharmacies, 15 ans), valeurs, produits phares, CTA partenariat |
| `produits.html` | 7 fiches produit avec visuels colorés par catégorie, filtres pills, compteur live (API CRM), modal détail, conditions de référencement (commande min, J+2, -15%) |
| `partenaire.html` | Formulaire demande partenariat → `POST /api/contact` → crée demande dans le CRM |
| `commander.html` | Formulaire commande → `POST /api/commande` → crée commande dans le CRM |
| `contact.html` | Formulaire de contact |
| `a-propos.html` | Présentation entreprise, équipe, certifications |
| `mentions-legales.html` | Mentions légales |
| `cgv.html` | Conditions générales de vente |
| `confidentialite.html` | Politique RGPD |

### Catalogue produits vitrine

| Ref | Produit | Catégorie | Prix HT |
|-----|---------|-----------|---------|
| REF-001 | Oméga-3 Triglycérides 1000mg | Immunité / Cardio | 24,90 € |
| REF-002 | Magnésium Bisglycinate 300mg | Énergie / Stress | 19,90 € |
| REF-003 | Vitamine D3 2000 UI + K2 | Ossature / Immunité | 22,90 € |
| REF-004 | Probiotiques 10 souches 10 Mrd UFC | Digestion / Immunité | 28,90 € |
| REF-005 | Curcuma Liposomal BioActif | Articulaire / Anti-inflam. | 26,90 € |
| REF-006 | Zinc + Sélénium Organique | Beauté / Immunité | 18,90 € |
| REF-007 | Mélatonine 1mg + Passiflore | Sommeil | 16,90 € |

---

## 8. Fonctionnalités backend notables

### Génération CSV (`RapportController`)
- 4 types de rapports : Commandes (avec ref NC-YYYY-XXXX), Pharmacies (withCount commandes + dernière commande), Relances (pharmacies actives inactives 30+ jours triées par ancienneté), CA Mensuel (groupBy mois avec moyenne/commande)
- Séparateur `;` (standard France pour Excel)
- Fichier généré en mémoire via `php://temp` + `fputcsv` + `rewind()` + `stream_get_contents()`
- Stocké via `Storage::put()` dans `storage/app/rapports/`
- Entrée créée en base avec titre, type, chemin, user_id, date

### Génération PDF (`CommandeController@pdf`)
- DomPDF : bon de commande avec logo NaturaCorp, informations pharmacie, lignes produit, sous-total, TVA, total TTC
- Téléchargement direct via `response()->streamDownload()`

### Geocodage (`GeocodageService`)
- Appel API Nominatim (OpenStreetMap) : adresse complète → lat/lng
- Commande Artisan `geocoder:pharmacies` pour batch sur toutes les pharmacies sans coordonnées
- Stockage direct sur le modèle Pharmacie

### API publique avec CORS
```php
Route::options('{any}', fn() => response()->json([], 204)
    ->header('Access-Control-Allow-Origin', '*')
    ->header('Access-Control-Allow-Methods', 'GET, POST, OPTIONS')
    ->header('Access-Control-Allow-Headers', 'Content-Type, Accept')
)->where('any', '.*');
```

---

## 9. Fonctionnalités frontend notables

### Carte Leaflet interactive
- Tuiles CartoDB Positron (plus professionnel qu'OpenStreetMap)
- Icônes custom SVG via `L.divIcon()` : point coloré par statut + halo + bordure blanche
- Clustering vert avec `L.MarkerClusterGroup` et `iconCreateFunction` personnalisée
- Synchronisation Alpine.js : `get filtered()` computed + `$watch` sur 4 filtres → `updateMarkers()`
- `flyTo()` depuis la liste : `map.flyTo()` + `clusterGroup.zoomToShowLayer(m, () => m.openPopup())`

### Dashboard ApexCharts
- Donut pharmacies par statut (actif/prospect/inactif)
- Donut commandes par statut
- Area chart évolution commandes : courbe volume + courbe CA sur 12 mois
- Top 5 pharmacies : barres de progression avec couleurs dégradées or → vert → bleu par rang

### Alpine.js réactif
- Toutes les listes (pharmacies, commandes, produits) filtrables sans rechargement
- Modaux de création/édition avec état partagé
- Cloche notifications : polling `setInterval` toutes les 30s

---

## 10. Problèmes résolus

| Problème | Solution appliquée |
|---------|-------------------|
| Conflit routes GET `rapports/{rapport}` vs POST `rapports/generate` | Route show renommée en `rapports/{rapport}/download` |
| Leaflet clusters empêchant `openPopup()` direct | `clusterGroup.zoomToShowLayer(marker, () => marker.openPopup())` |
| Synchronisation filtres ↔ liste ↔ carte sans rechargement | Un seul composant Alpine.js avec `get filtered()` + `$watch` sur chaque filtre |
| CSV sans fichier temporaire sur disque | `php://temp` + `rewind()` + `stream_get_contents()` + `Storage::put()` |
| CORS vitrine → CRM | Route `OPTIONS` wildcard avec headers Allow sur préfixe `/api` |
| Contrainte FK suppression pharmacie | `onDelete('set null')` sur FK de commandes → pharmacies |
| Colonnes manquantes en migration | Migrations additives `alter table` pour nullable sur `adresse`/`ville` (pharmacies) et `user_id` (commandes) |
| Force du password sans librairie | Score calculé JS : longueur + majuscule + chiffre + spécial → 0-4 → 4 barres colorées |

---

## 11. État actuel du projet

### Fonctionnel et complet
- CRM complet : dashboard, pharmacies, commandes, produits, relances, carte, rapports, utilisateurs, demandes partenaires, documents joints
- Authentification + rôles + permissions sur toutes les routes
- API publique REST consommée par la vitrine (CORS)
- Génération PDF bons de commande
- Génération CSV 4 types avec historique
- Vitrine 7 pages avec design professionnel
- Catalogue 7 produits avec filtres, modal, connexion live API CRM
- Formulaire partenariat → création demande en CRM
- Notifications internes temps réel (polling)
- Journal d'activité admin
- Carte Leaflet interactive avec clustering et synchronisation Alpine.js

### Non implémenté / Améliorable
- Envoi d'emails réels (Mailable Laravel non branché — relances visuelles uniquement)
- Pas de tests automatisés (Feature/Unit)
- Paiement non implémenté (commandes passées manuellement)
- `index.html` vitrine : sections Domaines de santé, Témoignages, FAQ, Newsletter non intégrées
- Optimisation mobile partielle sur certaines vues CRM complexes

---

*Rapport généré le 23 avril 2026 — NaturaCorp Bachelor Project*
