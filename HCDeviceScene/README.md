# HCDeviceScenes
Einbindung der Szenen von Legrand in IP-Symcon.

### Inhaltsverzeichnis

1. [Funktionsumfang](#1-funktionsumfang)
2. [Voraussetzungen](#2-voraussetzungen)
3. [Einrichten der Instanzen in IP-Symcon](#4-einrichten-der-instanzen-in-ip-symcon)
5. [Statusvariablen und Profile](#5-statusvariablen-und-profile)
6. [WebFront](#6-webfront)
7. [PHP-Befehlsreferenz](#7-php-befehlsreferenz)

### 1. Funktionsumfang

* Aufrufen der definierten Szenen aus der Home Control App

### 2. Vorraussetzungen

- IP-Symcon ab Version 5.2

### 4. Einrichten der Instanzen in IP-Symcon

Über den Configurator kann diese Instanz angelegt werden.

__Konfigurationsseite__:

Keine Einstellungsmöglichkeiten vorhanden.

### 5. Statusvariablen und Profile

Die Statusvariablen/Kategorien werden automatisch angelegt. Das Löschen einzelner kann zu Fehlfunktionen führen.

#### Statusvariablen

Name   | Typ     | Beschreibung
------ | ------- | ------------
Szenen|Integer| Zeigt an welche Szenen es gibt und diese können über diese Variable ausgewählt werden

#### Profile

HC.Scenes+InstanzID wird benötigt um die Szenen als Profil darzustellen.

### 6. WebFront

Über das Webfront kann eine Szene ausgewählt werden.

### 7. PHP-Befehlsreferenz

`RequestAction($VariablenID, $Value);`
Aktiviert eine Szene, Value ist in diesem Fall der Wert im Profil.

Beispiel:

`RequestAction(12345, 1);`

`RequestAction(12345, 2);`