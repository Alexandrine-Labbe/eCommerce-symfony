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
php bin/console doctrine:database:create # créer la BDD
php bin/console doctrine:migrations:migrate # exécuter les migrations
php bin/console doctrine:fixtures:load # charger les fixtures
```

## Démarrage
```bash
npm run dev # Compiler les assets
symfony serve # Démarrer le serveur local
```
http://localhost:8000/

### Compiler les assets
```bash
npm run build # Compiler les assets, créer un build de production
npm run dev # Compiler les assets une fois
npm run watch # Compiler les assets en watchant les fichiers
php bin/console asset-map:compile # Copie physiquement les assets dans public/assets/ pour la production
```

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
## Avec docker
Build :
 ```shell
docker build . -t ecommerce-symfony -f .cloud/Dockerfile
```
Lancer le container : 
 ```shell
docker run --name ecommerce-symfony -e APP_ENV=dev -it -p 8000:80 --rm ecommerce-symfony
```

