# Carpeta de lliurament — Automatització Industrial (UPC)

Aquesta carpeta conté els **6 fitxers** que demana l'enunciat (`Lliurament.pdf`) per entregar la pràctica en format ZIP.

## Contingut

| Fitxer | Descripció |
|--------|------------|
| `Memoria.pdf` | Memòria descriptiva (equip: Arnau Genover, Pol Coll) |
| `index-1.html` | Pàgina índex amb enllaços a lista i formulari |
| `estilo.css` | Fulla d'estils |
| `lista.php` | Llistat de sensors (JOIN Sensor + Magnitud) |
| `form-sensor.php` | Formulari d'alta (GET → nuevo-sensor.php) |
| `nuevo-sensor.php` | Processament de la inserció |

## Com crear el ZIP

Des d'aquesta carpeta (`lliurament/`), comprimeix **només** aquests 6 fitxers:

```bash
cd lliurament
zip ../Practica_Automatitzacio.zip Memoria.pdf index-1.html estilo.css lista.php form-sensor.php nuevo-sensor.php
```

## Aplicació web desplegada

**URL:** https://monitoritzacio-upc.gt.tc/

## Notes

- Els comentaris dels fitxers PHP d'aquesta carpeta estan en **català**, com demana el professor.
- Aquests fitxers utilitzen connexió directa a MariaDB (`localhost`, `phpmyadmin`, `12345`) segons l'enunciat.
- El projecte complet de desenvolupament (amb `index.php`, CRUD ampliat, `config.php`, etc.) es troba a l'arrel del repositori.

## Regenerar Memoria.pdf

Si modifiqueu `memoria.tex` a l'arrel del projecte:

```bash
pdflatex -output-directory=lliurament memoria.tex
mv lliurament/memoria.pdf lliurament/Memoria.pdf
```
