# HCDeviceAutomations
Einbindung des Rolladen Aktors von Legrand in IP-Symcon.

### Inhaltsverzeichnis

1. [Funktionsumfang](#1-funktionsumfang)
2. [Voraussetzungen](#2-voraussetzungen)
3. [Einrichten der Instanzen in IP-Symcon](#4-einrichten-der-instanzen-in-ip-symcon)
5. [Statusvariablen und Profile](#5-statusvariablen-und-profile)
6. [WebFront](#6-webfront)
7. [PHP-Befehlsreferenz](#7-php-befehlsreferenz)

### 1. Funktionsumfang

* Ansteuern eines Rolladen Aktors

### 2. Vorraussetzungen

- IP-Symcon ab Version 5.2

### 4. Einrichten der Instanzen in IP-Symcon

Über den Configurator kann diese Instanz angelegt werden.

__Konfigurationsseite__:

Name     | Beschreibung
-------- | ------------------
Module ID| Die ID des Aktors, wird beim Anlegen über den Configurator gefüllt.

### 5. Statusvariablen und Profile

Die Statusvariablen/Kategorien werden automatisch angelegt. Das Löschen einzelner kann zu Fehlfunktionen führen.

#### Statusvariablen

Name   | Typ     | Beschreibung
------ | ------- | ------------
Reachable|Boolean| Zeigt an, ob das Gerät erreichbar ist oder nicht
Level|Integer| Über diese Variable lässt sich der Rolladen Aktor auf eine bestimmte Position fahren

#### Profile

Keine Vorhanden.

### 6. WebFront

Über das Webfront kann der Rolladen Aktor auf eine bestimmte Position gefahren werden.

### 7. PHP-Befehlsreferenz

`RequestAction($VariablenID, $Value);`
Fährt den Rolladen Aktor auf eine vorgegebene Position.

Beispiel:
`RequestAction(12345, 50);`