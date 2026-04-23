# Déploiement NaturaCorp sur Railway

## Étape 1 — Créer un dépôt GitHub

Dans le terminal, depuis le dossier du projet :

```bash
git init
git add .
git commit -m "Initial commit — NaturaCorp CRM + Vitrine"
```

Puis sur github.com :
1. Créer un nouveau dépôt (ex: `naturacorp`)
2. Copier les commandes "push existing repo" affichées par GitHub :
```bash
git remote add origin https://github.com/TON-USERNAME/naturacorp.git
git branch -M main
git push -u origin main
```

---

## Étape 2 — Créer le projet Railway

1. Aller sur [railway.app](https://railway.app) et se connecter avec GitHub
2. Cliquer **New Project → Deploy from GitHub repo**
3. Sélectionner le dépôt `naturacorp`
4. Railway détecte automatiquement PHP via `nixpacks.toml`

---

## Étape 3 — Ajouter une base de données PostgreSQL

Dans le projet Railway :
1. Cliquer **+ New** → **Database** → **PostgreSQL**
2. Railway crée la DB et expose automatiquement les variables `PGHOST`, `PGPORT`, etc.

---

## Étape 4 — Configurer les variables d'environnement

Dans Railway → ton service → onglet **Variables**, ajouter :

| Variable | Valeur |
|----------|--------|
| `APP_NAME` | NaturaCorp |
| `APP_ENV` | production |
| `APP_KEY` | *(générer ci-dessous)* |
| `APP_DEBUG` | false |
| `APP_URL` | https://TON-APP.railway.app |
| `APP_LOCALE` | fr |
| `DB_CONNECTION` | pgsql |
| `DB_HOST` | `${{Postgres.PGHOST}}` |
| `DB_PORT` | `${{Postgres.PGPORT}}` |
| `DB_DATABASE` | `${{Postgres.PGDATABASE}}` |
| `DB_USERNAME` | `${{Postgres.PGUSER}}` |
| `DB_PASSWORD` | `${{Postgres.PGPASSWORD}}` |
| `SESSION_DRIVER` | file |
| `CACHE_STORE` | file |
| `QUEUE_CONNECTION` | sync |
| `FILESYSTEM_DISK` | local |
| `LOG_LEVEL` | error |

**Générer APP_KEY** — lancer en local :
```bash
php artisan key:generate --show
```
Copier la valeur `base64:...` et la coller dans la variable `APP_KEY`.

---

## Étape 5 — Déployer

Railway lance automatiquement le build au push. La séquence exécutée :

```
1. composer install --no-dev --optimize-autoloader
2. php artisan config:cache
3. php artisan route:cache
4. php artisan view:cache
5. php artisan migrate --force          ← au démarrage
6. php artisan db:seed RoleAndUserSeeder ← crée admin/commercial/logistique
7. php artisan storage:link
8. php artisan serve --host=0.0.0.0 --port=$PORT
```

---

## Étape 6 — Vérifier que ça marche

1. Railway affiche l'URL publique (ex: `naturacorp-production.railway.app`)
2. Aller sur `https://naturacorp-production.railway.app/vitrine/index.html` → vitrine
3. Aller sur `https://naturacorp-production.railway.app/login` → CRM

**Comptes créés par le seeder :**
| Email | Mot de passe | Rôle |
|-------|-------------|------|
| admin@naturacorp.fr | password | Admin |
| commercial@naturacorp.fr | password | Commercial |
| logistique@naturacorp.fr | password | Logistique |

> ⚠️ Changer les mots de passe après la première connexion !

---

## Problèmes courants

**Build échoue — extension PHP manquante**
→ Vérifier `nixpacks.toml`, ajouter l'extension dans `nixPkgs`

**500 Error après déploiement**
→ Vérifier que `APP_KEY` est bien défini dans les variables Railway

**Migrations échouent**
→ Vérifier que les variables `DB_*` pointent bien vers la base PostgreSQL Railway

**Fichiers uploadés disparaissent après redéploiement**
→ Normal sur Railway (disque éphémère). Pour production réelle : configurer S3/Cloudflare R2.

---

## Mise à jour du code

```bash
git add .
git commit -m "Description des changements"
git push origin main
```
Railway redéploie automatiquement.
