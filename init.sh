#!/bin/bash
set -e
php bin/console doctrine:database:create || true
php bin/console doctrine:migrations:migrate --no-interaction
