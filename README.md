move:elevator Testaufgabe Florian Weber
=======================================

# Aufgabenstellung

Für die Anzeige eines virtuellen Veranstaltungskalenders soll es möglich sein, einzelne Veranstaltungen zu verwalten. Dazu benötigt die Applikation folgende Einstiegspunkte:
Eintragen einer Veranstaltung, hier soll per Request mit folgenden Daten eine Veranstaltungsdatensatz angelegt werden. Eine Veranstaltung enthält folgende Daten: Titel, Beschreibung, Ort, PLZ, Adresse, E-Mail-Adresse des Ansprechpartners, Datum/Uhrzeit Beginn, Datum/Uhrzeit Ende.
Die so angelegten Veranstaltungen sollen über eine weitere API als Liste abgefragt werden. Die Liste soll folgende Daten der Veranstaltung enthalten: Titel, Ort, Datum/Uhrzeit Beginn.
Anhand der Daten aus der Liste solle eine weitere API angefragt werden können, die für eine Detailansicht alle Informationen der Veranstaltung zurückgibt.
Um auf Änderungen reagieren zu können muss es möglich sein, Veranstaltungen zu löschen oder zu aktualisieren.

Je nachdem wieviel Zeit zur Verfügung steht, wären folgende Erweiterungen sinnvoll:
Filterung der Listenansicht nach bestimmten Daten
Validierung der Eingaben beim Anlegen und Löschen
Dokumentation der APIs über Swagger/OpenAPi

Technische Aspekte:
PHP-Application als BE-Api (Rest-API) zur Verwaltung von Veranstaltungen (CRUD-Funktionalitäten)
Optional mit einem Framework seiner Wahl
Bereitstellung mit einer lokalen Umgebung, z.B. Docker-Container
Geliefert in einem Git-Repo (z.B. Github) mit Installation-Manual

# Docker Container Cluster starten

Die docker-compose Datei ist aktuell so konfiguriert dass ein zufälliger Host-Port für den Web-Container sowie den PhpMyAdmin Container ausgewählt wird.
Der Container ist nicht production-ready, daher müssen noch ein paar manuelle Schritte ausgeführt werden s.u..

## .env.local Datei erstellen und anpassen

Die .env Datei kopieren und eine .env.local daraus machen. Danach ggf. die Einstellungen anpassen, aber die defaults dürften passen.

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

# Zeitefassung

Folgende Zeitbuchungen sind für das Projekt erfasst worden:
