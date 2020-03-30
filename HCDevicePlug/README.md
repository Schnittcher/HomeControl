# HCDeviceLight
Einbindung des Plugs von Legrand in IP-Symcon.

### Inhaltsverzeichnis

1. [Funktionsumfang](#1-funktionsumfang)
2. [Voraussetzungen](#2-voraussetzungen)
3. [Einrichten der Instanzen in IP-Symcon](#4-einrichten-der-instanzen-in-ip-symcon)
5. [Statusvariablen und Profile](#5-statusvariablen-und-profile)
6. [WebFront](#6-webfront)
7. [PHP-Befehlsreferenz](#7-php-befehlsreferenz)

### 1. Funktionsumfang

* Schalten einer Steckdose von Legrand

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
Status|Boolean| Schaltet die Steckdose ein bzw. aus

#### Profile

Keine Vorhanden.

### 6. WebFront

Über das Webfront kann die Steckdose ein- bzw. ausgeschaltet werden.

### 7. PHP-Befehlsreferenz

`RequestAction($VariablenID, $Value);`
Schaltet die Steckdose ein bzw. aus.

Beispiel:

`RequestAction(12345, false);`

`RequestAction(12345, true);`