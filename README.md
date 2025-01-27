# eCommerce Symfony

## Prérequis
- PHP 8.3
- Composer 2
- SQLite 3

## Installation
```bash
composer install
```

## Démarrage
```bash
symfony serve
```

## Base de données
Exécuter les migrations
```bash
php bin/console doctrine:migrations:migrate
```

Charger les fixtures 
```bash
php bin/console doctrine:fixtures:load
```