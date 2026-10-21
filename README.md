# BiblioNet — Bibliothèque de l'INPTIC

Application web de gestion d'une bibliothèque (livres, adhérents, emprunts) en
**PHP 8 orienté objet, architecture MVC, sans framework**, avec MySQL/MariaDB (PDO).

Mini-projet — Module Développement web (PHP / MySQL) — Enseignant : Charly OBAME.

## État d'avancement

| Jour | Contenu | État |
|------|---------|------|
| J1 | Socle MVC (Router, Database, Controller, layout), base + données de test, page d'accueil | ✅ |
| J2 | Authentification, CRUD livres, CRUD adhérents | ✅ |
| J3 | Catalogue + recherche ✅ (fait en avance), emprunts/retours ⏳, mise en ligne ⏳ | ⏳ |
| J4 | Finalisation, rapport, soutenance | ⏳ |

## Installation locale

**Prérequis** : PHP 8.x (extensions `pdo_mysql` et `mbstring`), MySQL ou MariaDB (XAMPP / WAMP / Laragon conviennent).

1. **Cloner le dépôt**
   ```bash
   git clone <url-du-depot> biblionet
   cd biblionet
   ```
2. **Créer la base** : importer `database/biblionet.sql` (phpMyAdmin, ou en ligne de commande) :
   ```bash
   mysql -u root -p < database/biblionet.sql
   ```
3. **Configurer l'environnement** : copier `.env.example` en `.env` puis adapter
   `DB_USER` / `DB_PASS` à votre installation locale.
   ```bash
   cp .env.example .env
   ```
4. **Lancer l'application** avec le serveur intégré de PHP :
   ```bash
   php -S localhost:8000 -t public public/index.php
   ```
   puis ouvrir <http://localhost:8000>.

   Avec Apache (XAMPP/WAMP), placer le projet dans `htdocs`/`www` et ouvrir
   `http://localhost/biblionet/public/` (`mod_rewrite` activé).

## Compte de démonstration

- Email : `bibliothecaire@inptic.ga`
- Mot de passe : `Biblio@2026`

## Routes

| URL | Accès | Rôle |
|-----|-------|------|
| `/` et `/catalogue` | public | Catalogue, recherche par titre ou auteur (`/catalogue/search?q=` en JSON) |
| `/login`, `/logout` | public | Connexion / déconnexion du bibliothécaire |
| `/livres`, `/livres/create`, `/livres/edit/{id}` | connecté | CRUD des livres |
| `/adherents`, `/adherents/create`, `/adherents/edit/{id}` | connecté | CRUD des adhérents |
| `/emprunts` | connecté | À développer (J3) |

## Architecture

```
biblionet/
├── app/
│   ├── controllers/   AuthController, LivreController, AdherentController,
│   │                  EmpruntController, CatalogueController
│   ├── models/        User, Livre, Adherent, Emprunt
│   ├── views/         layouts/main.php, auth/, livres/, adherents/, emprunts/, catalogue/
│   └── core/          Router, Controller, Database
├── config/config.php  paramètres lus depuis .env
├── public/            SEUL dossier exposé au web (index.php = front controller)
├── database/          biblionet.sql (création + données de test)
├── .env.example
└── .gitignore         (exclut .env)
```

Flux d'une requête : `/livres` → `.htaccess` → `public/index.php` → `Router`
→ `LivreController::index()` → `Livre::all()` → vue `views/livres/index.php`.

## Règles du projet

- Aucune requête SQL dans les vues, aucun HTML dans les modèles.
- Requêtes PDO préparées uniquement.
- Toute donnée affichée est échappée (`$this->e()` = `htmlspecialchars`).
- Le fichier `.env` n'est **jamais** versionné.

## Équipe

| Membre | Responsabilités |
|--------|-----------------|
| Membre 1 | Socle, authentification, GitHub, mise en ligne |
| Membre 2 | Livres (CRUD) et catalogue public |
| Membre 3 | Adhérents, emprunts, script SQL et données de test |
