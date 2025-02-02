# eCommerce Symfony

Demo de eCommerce symfony, vous pouvez la consulter ici : https://demo.alexandrine-labbe.fr/
Pour accéder à l'admin : https://demo.alexandrine-labbe.fr/admin

## Prérequis
- PHP 8.3
- Node 22 (LTS)
- Composer 2
- SQLite 3

## Développement
### Dépendances
```shell
composer install
npm ci
```

### Base de données
La base de donnée est créer selon la configuation de `DATABASE_URL`, par défaut elle est configurée pour sqlite dans le dossier `var/app.db`
```bash
php bin/console doctrine:database:create # créer la BDD
php bin/console doctrine:migrations:migrate # exécuter les migrations
php bin/console doctrine:fixtures:load # charger les fixtures
```

### Démarrage
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
```

## Tests
Nécessite PHPUnit
```bash
php bin/phpunit
```



## Utilisation
### Docker
Lancer le container :
 ```shell
docker compose up --build
```
http://localhost:8000/

### API
Liste des produits
```bash
curl http://localhost:8000/api/products.json
```

### Export CSV
```bash
php bin/console app:export-csv [<filename>]
```

### Accès admin
http://localhost:8000/admin
https://demo.alexandrine-labbe.fr/admin # Demo avec des données "jolies"