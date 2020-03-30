# HCSplitter
Ist für die Verbindung zwischen HCCloud und den Geräten / Configuratoren zuständig.

### Inhaltsverzeichnis

1. [Funktionsumfang](#1-funktionsumfang)
2. [Voraussetzungen](#2-voraussetzungen)
3. [Einrichten der Instanzen in IP-Symcon](#4-einrichten-der-instanzen-in-ip-symcon)
4. [Statusvariablen und Profile](#5-statusvariablen-und-profile)
5. [PHP-Befehlsreferenz](#7-php-befehlsreferenz)

### 1. Funktionsumfang

* Verbindet HCCloud und die Geräte / Konfiguratoren miteinander
* Ruft in vorgegebenem Intervall den Status der Geräte ab

### 2. Vorraussetzungen

- IP-Symcon ab Version 5.2

### 3. Einrichten der Instanzen in IP-Symcon

Der Splitter wird automatisch über den Configurator angelegt, sollte dieser noch nicht vorhanden sein.

__Konfigurationsseite__:

Name     | Beschreibung
-------- | ------------------
Plant ID| Die ID des Plants, für welchen der Splitter verwendet werden soll.
Intervall| Gibt an in welchem Sekundentakt der Status abgefragt werden soll.

### 4. Statusvariablen und Profile

Keine Variablen und Profile vorhanden.

### 5. PHP-Befehlsreferenz

`HC.UpdateStatus($InstanceID);`
Schaltet die Steckdose ein bzw. aus.

Beispiel:

`HC.UpdateStatus(12345);`
Ruft den Status ab und schickt diesen an alle Child Instanzen.