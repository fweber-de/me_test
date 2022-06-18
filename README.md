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

# Benutzung

Auf der Index Site befindet sich ein kleines Frontend mit den Events, Details und einer Kalenderansicht.
`http://localhost:<port>/`  
Die Rest-Api ist auf der Url `http://localhost:<port>/api/events/` zu finden.  
Die Swagger Api Doc ist auf der Url `http://localhost:<port>/api/doc` zu finden.

# Zeiterfassung

Folgende Zeitbuchungen sind für das Projekt erfasst worden:

| Description                       | Start date | Start time | End date | End time | Duration |
| --------------------------------- | ---------- | ---------- | -------- | -------- | -------- |
| Bootstrapping                     | 11.06.22   | 09:22:45   | 11.06.22 | 10:02:45 | 00:40:00 |
| Bootstrapping                     | 11.06.22   | 21:16:33   | 11.06.22 | 22:23:47 | 01:07:14 |
| App                               | 12.06.22   | 09:40:12   | 12.06.22 | 10:01:43 | 00:21:31 |
| App                               | 12.06.22   | 10:24:42   | 12.06.22 | 10:47:27 | 00:22:45 |
| App                               | 12.06.22   | 18:07:48   | 12.06.22 | 18:25:42 | 00:17:54 |
| App                               | 13.06.22   | 20:44:38   | 13.06.22 | 21:32:15 | 00:47:37 |
| App                               | 15.06.22   | 20:59:38   | 15.06.22 | 21:51:05 | 00:51:27 |
| App                               | 17.06.22   | 14:50:16   | 17.06.22 | 15:57:43 | 01:07:27 |
| Fertigstellung, Doku, Distri Test | 18.06.22   | 13:43:34   | 18.06.22 | 16:24:32 | 02:40:58 |

Total Time: 8:16:53