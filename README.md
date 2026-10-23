# BiblioNet — Bibliothèque de l'INPTIC

Application web de gestion d'une bibliothèque (livres, adhérents, emprunts) en **PHP 8 orienté objet,
architecture MVC, sans framework**, avec MySQL/MariaDB via PDO.

Mini-projet — Module Développement web (PHP / MySQL) — Enseignant : Charly OBAME.

## Avancement

| Jour | Contenu | État |
|------|---------|------|
| J1 | Analyse, base de données + données de test, socle MVC (Router, Database, Controller, layout), page d'accueil | ✅ |
| J2 | Authentification, CRUD livres, CRUD adhérents | ✅ |
| J3 | Catalogue public avec recherche, emprunts et retours, préparation de la mise en ligne | ✅ |
| J4 | Bonus, tests complets, README, rapport, soutenance | ✅ |

## Installation locale

**Prérequis** : PHP 8.x (extensions `pdo_mysql` et `mbstring`), MySQL ou MariaDB (XAMPP, WAMP ou Laragon conviennent).

1. Cloner le dépôt puis copier la configuration :
   ```bash
   git clone <url-du-depot> biblionet
   cd biblionet
   cp .env.example .env
   ```
   Adapter `DB_USER` et `DB_PASS` dans `.env` à votre installation.
2. Créer la base et importer le script :
   ```bash
   mysql -u root -p -e "CREATE DATABASE biblionet CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci"
   mysql -u root -p biblionet < database/biblionet.sql
   ```
   Avec phpMyAdmin : créer la base `biblionet`, la sélectionner, puis importer `database/biblionet.sql`.
3. Lancer le serveur intégré de PHP :
   ```bash
   php -S localhost:8000 -t public public/index.php
   ```
   puis ouvrir <http://localhost:8000>.

   Avec Apache (XAMPP/WAMP) : placer le dossier dans `htdocs` ou `www` et ouvrir `http://localhost/biblionet/public/`
   (`mod_rewrite` activé).

## Compte de démonstration

- Email : `bibliothecaire@inptic.ga`
- Mot de passe : `Biblio@2026`

Trois autres comptes bibliothécaires existent (Robert, André, Khadi, adresses du type `prenom@inptic.ga`). Leurs mots de passe ne sont pas publiés dans le dépôt.

## Fonctionnalités

- **Visiteur** : consultation du catalogue avec la mention « Disponible / Indisponible ».
- **Visiteur** : recherche d'un livre par titre ou auteur, avec pagination.
- **Bibliothécaire** (connexion par session, mot de passe haché) : CRUD des livres et des adhérents.
- **Emprunts** : enregistrement d'un emprunt et du retour, refus automatique si aucun exemplaire n'est disponible, liste des emprunts en cours.
- **Bonus** : tableau de bord chiffré, liste des retards (plus de 14 jours), filtres du catalogue, export CSV des emprunts, jeton CSRF, pagination.
- **Comptes multiples** : 4 comptes bibliothécaires. Chaque emprunt et chaque retour mémorise le bibliothécaire connecté.
- **Historique des emprunts** : recherche par adhérent, livre, période, statut ou bibliothécaire, avec export CSV des résultats.
- **Fiche adhérent** : tous les emprunts d'un adhérent, retards en rouge.
- **Limite d'emprunts** : un adhérent ne peut pas avoir plus de 3 livres non rendus.
- **Fiche de prêt imprimable** avec date limite de retour et signatures.
- **Sécurité de la connexion** : après 5 échecs, nouvelle tentative possible au bout de 30 secondes.

Règle métier : `exemplaires disponibles = nb_exemplaires − emprunts dont date_retour est NULL`.

## Routes

| URL | Accès | Rôle |
|-----|-------|------|
| `/` et `/catalogue` | public | Catalogue |
| `/auth/login`, `/auth/logout` | public | Connexion et déconnexion |
| `/livres`, `/livres/create`, `/livres/edit/{id}` | connecté | CRUD des livres |
| `/adherents`, `/adherents/create`, `/adherents/edit/{id}` | connecté | CRUD des adhérents |
| `/emprunts`, `/emprunts/create` | connecté | Emprunts en cours, nouvel emprunt |
| `/emprunts/retards`, `/emprunts/historique`, `/emprunts/export` | connecté | Retards, historique filtrable, export CSV |
| `/emprunts/fiche/{id}` | connecté | Fiche de prêt imprimable |
| `/adherents/show/{id}` | connecté | Fiche d'un adhérent et historique de ses emprunts |
| `/dashboard` | connecté | Tableau de bord |

## Architecture

```
biblionet/
├── app/
│   ├── controllers/   Catalogue, Auth, Livre, Adherent, Emprunt, Dashboard
│   ├── models/        Livre, User, Adherent, Emprunt
│   ├── views/         layouts/main.php, catalogue/, auth/, livres/, adherents/,
│   │                  emprunts/, dashboard/
│   └── core/          Router, Controller, Database, helpers
├── config/config.php  paramètres lus depuis .env
├── public/            seul dossier exposé au web (index.php, .htaccess, assets/)
├── database/          biblionet.sql (création + données de test)
├── .env.example
└── .gitignore         (exclut .env)
```

Flux d'une requête : `/livres` → `.htaccess` → `public/index.php` → `Router` → `LivreController::index()`
→ `Livre::search()` → vue `views/livres/index.php`.

## Règles du projet

- Aucune requête SQL dans les vues, aucun HTML dans les modèles.
- Accès à la base uniquement par PDO et requêtes préparées.
- Toute donnée affichée est échappée avec `e()` (`htmlspecialchars`).
- Le fichier `.env` n'est jamais versionné.


## Mise en ligne (hébergement mutualisé)

1. Créer la base et l'utilisateur MySQL depuis le panneau d'hébergement.
2. Importer `database/biblionet.sql` via phpMyAdmin, la base étant sélectionnée.
3. Transférer le projet (FTP avec FileZilla, ou `git clone` si SSH).
4. Faire pointer la racine du site vers `public/`. Sinon, le `.htaccess` placé à la racine redirige vers `public/`.
5. Créer `.env` sur le serveur avec `APP_ENV=production` et les identifiants de production.
6. Tester toutes les fonctionnalités en ligne avec le compte de démonstration.

## Équipe — Groupe 4

| Rôle | Membre(s) | Responsabilités |
|------|-----------|-----------------|
| Membre 1 — Socle et déploiement | Emmanuel (chef de groupe), Robert | Arborescence, Database (PDO), Router, Controller de base, layout, authentification, dépôt GitHub, mise en ligne |
| Membre 2 — Livres et catalogue | André | Modèle, contrôleur et vues des livres (CRUD), catalogue public avec recherche et disponibilité |
| Membre 3 — Adhérents et emprunts | Khadi | CRUD des adhérents, emprunts et retours, règle de disponibilité, script SQL et données de test |

Chaque membre commite son propre travail sur le dépôt : l'historique Git (`git log`, onglet *Commits* sur GitHub) montre qui a fait quoi.
