# NaturaCorp CRM

Application web interne de gestion de la relation client (CRM) pour la société NaturaCorp.

Ce projet permet de gérer les pharmacies clientes/prospects, leurs commandes, les rapports d'activité, une carte interactive, ainsi que la gestion des utilisateurs selon leurs rôles.

---

## Technologies utilisées

- **Laravel 11** (Back-end)
- **Blade** (Front-end)
- **PostgreSQL** (Base de données)
- **Leaflet.js + OpenStreetMap** (Carte interactive)
- **Vite** / **npm** pour les assets (JS, CSS)

---

## Fonctionnalités principales

- Authentification / gestion de session
- Gestion des pharmacies (CRUD)
- Gestion des commandes (CRUD)
- Carte interactive géolocalisant les pharmacies
- Notifications internes
- Journalisation des actions critiques
- Gestion des utilisateurs (admin)
- Gestion documentaire (liens vers contrats, fichiers, etc.)

---

## Installation locale (environnement de développement)

### 1. Prérequis

- PHP 8.2+
- Composer
- PostgreSQL 14+
- Node.js 18+, npm 9+
- Git

---

### 2. Clonage du projet

```bash
git clone https://github.com/LucasLH1/NaturaCorp-CRM.git
cd naturacorp-crm
```

---

### 3. Configuration de l'environnement

```bash
cp .env.example .env
```

Modifier les variables suivantes dans le fichier `.env` :

```dotenv
APP_NAME=NaturaCorp CRM
APP_URL=http://localhost:8000

DB_CONNECTION=pgsql
DB_HOST=127.0.0.1
DB_PORT=5432
DB_DATABASE=DB_NaturaCorp
DB_USERNAME="votre_utilisateur"
DB_PASSWORD="votre_mot_de_passe"
```

---

### 4. Installation des dépendances

```bash
composer install
npm install
```

---

### 5. Génération de la clé d'application

```bash
php artisan key:generate
```

---

### 6. Migration de la base de données + seed

```bash
php artisan migrate --seed
```

---

### 7. Compilation des assets

```bash
npm run dev
```

---

### 8. Lancement du serveur local

```bash
php artisan serve
```

Application disponible sur : [http://localhost:8000](http://localhost:8000)

---

### 9. Comptes de test préinstallés

| Rôle        | Email                  | Mot de passe |
|-------------|------------------------|--------------|
| Admin       | admin@naturacorp.com   | password     |

---

### 10. Exécution des tests unitaires

```bash
php artisan test
```

---

### 11. Problèmes fréquents

- `could not find driver (pgsql)` → installer l’extension `pdo_pgsql`
- Erreur 500 / page blanche → vérifier `.env` et clé `APP_KEY`
- CSS/JS non chargés → relancer `npm install && npm run dev`
- Base de données non valide / utilisateur inconnu → vérifier la présence de la bdd et du user conforme au .env

---

## Licence

Projet développé dans le cadre d’un projet étudiant. Usage interne uniquement.

---

