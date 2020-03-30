[![Version](https://img.shields.io/badge/Symcon-PHPModul-red.svg)](https://www.symcon.de/service/dokumentation/entwicklerbereich/sdk-tools/sdk-php/)
![Version](https://img.shields.io/badge/Symcon%20Version-5.2%20%3E-blue.svg)
[![License](https://img.shields.io/badge/License-CC%20BY--NC--SA%204.0-green.svg)](https://creativecommons.org/licenses/by-nc-sa/4.0/)
[![Check Style](https://github.com/Schnittcher/HomeControl/workflows/Check%20Style/badge.svg)](https://github.com/Schnittcher/HomeControl/actions)

# HomeControl
   Dieses IP-Symcon Modul stellt eine Verbindung zu der Legrand Cloud her um die Legrand Geräte in IP-Symcon einzubinden.
 
   ## Inhaltverzeichnis
   1. [Voraussetzungen](#1-voraussetzungen)
   2. [Enthaltene Module](#2-enthaltene-module)
   3. [Installation](#3-installation)
   4. [Konfiguration in IP-Symcon](#4-konfiguration-in-ip-symcon)
   5. [Spenden](#5-spenden)
   6. [Lizenz](#6-lizenz)
   
## 1. Voraussetzungen

* mindestens IPS Version 5.2
* eine gültige IP-Symcon Subscription

## 2. Enthaltene Module

* [HCCloud](HCCloud/README.md)
* [HCConfigurator](HCConfigurator/README.md)
* [HCDeviceAutomations](HCDeviceAutomations/README.md)
* [HCDeviceLight](HCDeviceLight/README.md)
* [HCDevicePlug](HCDevicePlug/README.md)
* [HCDeviceRemote](HCDeviceRemote/README.md)
* [HCDeviceScenes](HCDeviceScenes/README.md)
* [HCdiscovery](HCdiscovery/README.md)
* [HCSplitter](HCSplitter/README.md)

## 3. Installation
Installation über den IP-Symcon Module Store.

## 4. Konfiguration in IP-Symcon
Als erstes muss die Discovery Instanz erstellt werden.
![Discovery Instanz erstellen](img/discovery-erstellen.png)
Die Discovery Instanz erstellt die HCCloud Instanz, innerhalb dieser Instanz muss die Verbindung zu der Legrand Clound hergestellt werden.
Um Verbidnung mit der Legrand Cloud herzustellen muss der Button "Registrieren" 
betätigt werden, daraufhin öffent sich der Registrierungsprozess mit der Cloud.

![Legrand Cloud registrieren](img/hccloud.png)

Nach erfolgriecher Registrierung, werden in der Discovery Instanz alle Gateways angezeigt, welche mit dem Legrand Account verknüpft sind.

![Discovery Instanz](img/discovery.png)

Diese Gateways können über die Discovery Instanz angelegt werden, in IP-Symcon wird dann ein Configurator für das ausgerwählte Gateway angelegt.

![Configurator Instanz](img/configurator.png)

Mithilfe des Configurators können die einzelnen Instanzen (Light, Plug, Remote Automations und Szenen) angelegt werden.

Innerhalb der Splitter Insatnz kann eingestellt werden, in welchem Intervall der Status der Geräte abgefragt werden soll.

![Splitter Instanz](img/splitter.png)

## 5. Spenden

Dieses Modul ist für die nicht kommzerielle Nutzung kostenlos, Schenkungen als Unterstützung für den Autor werden hier akzeptiert:    

<a href="https://www.paypal.com/cgi-bin/webscr?cmd=_s-xclick&hosted_button_id=EK4JRP87XLSHW" target="_blank"><img src="https://www.paypalobjects.com/de_DE/DE/i/btn/btn_donate_LG.gif" border="0" /></a>

## 6. Lizenz

[CC BY-NC-SA 4.0](https://creativecommons.org/licenses/by-nc-sa/4.0/)