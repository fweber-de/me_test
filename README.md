move:elevator Testaufgabe Florian Weber
=======================================

# Aufgabenstellung

# Docker Container Cluster starten

## .env.local Datei erstellen und anpassen

## Container starten
```bash 
docker-compose --profile dev --env-file .env.local up -d --build
```

## Composer deps installieren

```bash
docker-compose exec -w /var/www/html/app app_dev composer install
```

## Datenbank anlegen

```bash
docker-compose exec -w /var/www/html/app app_dev php bin/console doctrine:database:create
```

## Migrations ausführen

```bash
docker-compose exec -w /var/www/html/app app_dev php bin/console doctrine:migrations:migrate
```

## Fixtures laden

Funktioniert nur wenn noch keine Einträge in der DB sind!

```bash
docker-compose exec -w /var/www/html/app app_dev php bin/console app:load-fixtures
```
