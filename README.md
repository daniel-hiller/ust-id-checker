# USt-IdNr. Prüfer

Ein modernes Web-Tool zur schnellen und zuverlässigen Prüfung von Umsatzsteuer-Identifikationsnummern (USt-IdNr.) über die offizielle BZSt XML-RPC-Schnittstelle.

## Features

- 🚀 Schnelle und einfache USt-IdNr.-Prüfung
- 🔒 Sichere Verbindung über HTTPS
- 🏛️ Nutzung der offiziellen BZSt XML-RPC-Schnittstelle
- 📱 Responsive Design für alle Geräte
- 🎨 Modernes und benutzerfreundliches Interface
- 📋 Qualifizierte Prüfung mit zusätzlichen Firmendaten

## Installation

1. Klonen Sie das Repository:
```bash
git clone https://github.com/daniel-hiller/ust-id-checker.git
```

2. Stellen Sie sicher, dass PHP und die erforderlichen Erweiterungen installiert sind:
   - PHP 7.4 oder höher
   - cURL-Erweiterung
   - XML-Erweiterung

3. Konfigurieren Sie einen Webserver (Apache/Nginx) mit PHP-Unterstützung

4. Kopieren Sie die Dateien in das Webroot-Verzeichnis

## Verwendung

1. Öffnen Sie die Anwendung in Ihrem Browser
2. Geben Sie Ihre deutsche USt-IdNr. ein
3. Geben Sie die zu prüfende USt-IdNr. ein
4. Optional: Fügen Sie zusätzliche Firmendaten für eine qualifizierte Prüfung hinzu
5. Klicken Sie auf "USt-IdNr. prüfen"

## Technische Details

Die Anwendung besteht aus zwei Hauptkomponenten:

1. Frontend (index.html):
   - Modernes, responsives Design mit Bootstrap 5
   - Font Awesome Icons
   - Asynchrone API-Anfragen
   - Benutzerfreundliche Fehlerbehandlung

2. Backend (check_vat.php):
   - PHP-Proxy für die BZSt-API
   - XML-RPC-Verarbeitung
   - Fehlerbehandlung und Validierung
   - JSON-Response-Formatierung

## Fehlercodes

Die Anwendung unterstützt alle offiziellen BZSt-Fehlercodes:

| Code | Beschreibung |
|------|--------------|
| 200 | Die angefragte USt-IdNr. ist gültig. |
| 201 | Die angefragte USt-IdNr. ist ungültig. |
| 202 | Die angefragte USt-IdNr. ist ungültig. Sie ist nicht in der Unternehmerdatei des betreffenden EU-Mitgliedstaates registriert. |
| 203 | Die angefragte USt-IdNr. ist ungültig. Sie ist erst ab einem bestimmten Datum gültig (siehe Feld 'Gueltig_ab'). |
| 204 | Die angefragte USt-IdNr. ist ungültig. Sie war nur in einem bestimmten Zeitraum gültig (siehe Felder 'Gueltig_ab' und 'Gueltig_bis'). |
| 205 | Ihre Anfrage kann derzeit durch den angefragten EU-Mitgliedstaat oder aus anderen Gründen nicht beantwortet werden. |
| 206 | Ihre deutsche USt-IdNr. ist ungültig. Eine Bestätigungsanfrage ist daher nicht möglich. |
| 208 | Für die von Ihnen angefragte USt-IdNr. läuft gerade eine Anfrage von einem anderen Nutzer. |
| 209 | Die angefragte USt-IdNr. ist ungültig. Sie entspricht nicht dem Aufbau der für diesen EU-Mitgliedstaat gilt. |
| 210 | Die angefragte USt-IdNr. ist ungültig. Sie entspricht nicht den Prüfziffernregeln die für diesen EU-Mitgliedstaat gelten. |
| 211 | Die angefragte USt-IdNr. ist ungültig. Sie enthält unzulässige Zeichen (wie z.B. Leerzeichen oder Punkt oder Bindestrich usw.). |
| 212 | Die angefragte USt-IdNr. ist ungültig. Sie enthält ein unzulässiges Länderkennzeichen. |
| 213 | Sie sind nicht zur Abfrage einer deutschen USt-IdNr. berechtigt. |
| 214 | Ihre deutsche USt-IdNr. ist fehlerhaft. Sie beginnt mit 'DE' gefolgt von 9 Ziffern. |
| 215 | Ihre Anfrage enthält nicht alle notwendigen Angaben für eine einfache Bestätigungsanfrage. |
| 216 | Ihre Anfrage enthält nicht alle notwendigen Angaben für eine qualifizierte Bestätigungsanfrage. |
| 217 | Bei der Verarbeitung der Daten aus dem angefragten EU-Mitgliedstaat ist ein Fehler aufgetreten. |
| 218 | Eine qualifizierte Bestätigung ist zur Zeit nicht möglich. Es wurde eine einfache Bestätigungsanfrage durchgeführt. |
| 219 | Bei der Durchführung der qualifizierten Bestätigungsanfrage ist ein Fehler aufgetreten. |
| 221 | Die Anfragedaten enthalten nicht alle notwendigen Parameter oder einen ungültigen Datentyp. |
| 223 | Die angefragte USt-IdNr. ist gültig. Die Druckfunktion steht nicht mehr zur Verfügung. |
| 999 | Eine Bearbeitung Ihrer Anfrage ist zurzeit nicht möglich. |

## Lizenz

Dieses Projekt ist unter der MIT-Lizenz lizenziert. Siehe [LICENSE](LICENSE) für Details.

## Autor

Made with ❤️ by Daniel Hiller

## GitHub

[https://github.com/daniel-hiller/ust-id-checker](https://github.com/daniel-hiller/ust-id-checker) 