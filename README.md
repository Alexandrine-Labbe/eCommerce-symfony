# eCommerce Symfony
**Work in progress**

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
### Base de données
```bash
php bin/console doctrine:migrations:migrate # exécuter les migrations
php bin/console doctrine:fixtures:load # charger les fixtures
```

## Démarrage
```bash
npm run dev # Compiler les assets
symfony serve # Démarrer le serveur local
```
http://localhost:8000/

## Tests
Nécessite PHPUnit
```bash
php bin/phpunit
```

## API
Liste des produits
```bash
curl http://localhost:8000/api/products.json
```
