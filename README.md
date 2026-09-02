# SamaDocs

SamaDocs est une application web qui permet de stocker, organiser et retrouver facilement tous vos documents depuis n'importe quel appareil. Construite avec Laravel et SQLite, elle est conçue pour être simple, rapide et utilisable en priorité sur mobile.

## Fonctionnalités

- Tableau de bord avec statistiques et date/heure en temps réel
- Gestion complète des documents (ajout, consultation, téléchargement, suppression)
- Catégories et favoris pour organiser vos fichiers
- Corbeille avec restauration et suppression définitive
- Recherche et filtres (par catégorie et par type)
- Paramètres du compte : photo de profil, prénom/nom, changement de mot de passe
- Authentification (inscription et connexion) avec prénom et nom séparés
- Mode sombre / clair
- Interface responsive, adaptée aux téléphones
- Application web progressive (PWA) installable et utilisable hors-ligne

## Technologies

- Laravel 10 (PHP 8.1+)
- SQLite
- Blade + CSS personnalisé
- Font Awesome
- Service Worker + Web App Manifest (PWA)

## Installation

### Prérequis

- PHP 8.1 ou plus récent
- Composer
- Node.js (optionnel, pour les assets)
- Extension PHP SQLite

### Étapes

```bash
# 1. Cloner le dépôt
git clone git@github.com:MouhamadouMoustaphaFall21/samadocs.git
cd samadocs

# 2. Installer les dépendances
composer install

# 3. Configurer l'environnement
cp .env.example .env
php artisan key:generate

# 4. Créer la base de données SQLite
touch database/database.sqlite

# 5. Lancer les migrations et le seed (données de démonstration)
php artisan migrate --seed

# 6. Créer le lien de stockage public
php artisan storage:link

# 7. Démarrer le serveur
php artisan serve
```

L'application est alors disponible à l'adresse http://localhost:8000

### Compilation des assets (optionnel)

Si vous modifiez les fichiers CSS/JS sources :

```bash
npm install
npm run dev
```

## Compte de démonstration

| Email | Mot de passe |
|-------|--------------|
| demo@samadocs.com | password |

## Structure

- `app/Http/Controllers` : contrôleurs de l'application
- `resources/views` : vues Blade
- `resources/css` et `resources/js` : assets sources
- `database` : migrations et seeders
- `public` : fichiers publics (CSS, JS, manifest PWA, icônes)

## PWA

L'application est une PWA : elle peut être installée sur un téléphone ou un ordinateur et fonctionne même hors-ligne. Pour installer :

- Sur Android : menu du navigateur puis "Ajouter à l'écran d'accueil"
- Sur iOS : bouton Partager puis "Sur l'écran d'accueil"
- Sur ordinateur : icône d'installation dans la barre d'adresse (HTTPS requis en production)

## Licence

Ce projet est publié sous licence MIT.
