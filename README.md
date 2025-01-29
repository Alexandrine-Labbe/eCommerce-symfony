# eCommerce Symfony

## Prérequis
- PHP 8.3
- Node 22 (LTS)
- Composer 2
- SQLite 3

## Installation
```bash
composer install
npm ci
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

## API
Liste des produits
```bash
curl http://localhost:8000/api/products.json
```
