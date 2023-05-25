# FIGYELMEZTETÉS/WARNING!
    
    Mielőtt végigolvasná bárki, ez csak egy iskolai vizsga projekt. Miután értékelték, valószínű, hogy törölve lesz a Githubról

    Before anyone would read this, this is just an exam project. After the project has been rated, propably it will be removed from Github

## Üdvözlöm a Teeminkall webapplikáció README Fájlában!

A teeminkall webapplikáció egy online chatapplikáció csoportok,csapatok,cégek és vállalatok számára.

### Használt leirónyelvek :
 - HTML

### Használt Lekérdezőnyelvek a projekt készitése során :
 - MySQL

 ### Használt Stilusleírónyelvek a projekt készitése során :
 - CSS

### Használt Programnyelvek a projekt készitése során :
 - JavaScript
 - PHP

### Használt keretrendszer a projekt készitése során :
 - Laravel
 - Jquery
 - W3.CSS
 - Ajax
 - Boostrap v5.3

### A Projekt készitőinek névlistája :
 Lisztes Livius (Backend része a projektnek)
 Pfeiffer István (Frontend és SQL adatbázis része a projektnek)

### Szerver elérhetőségért felelős publikáló szerver :
- Laravel Forge

### Github fiók :
- [Pfeiffer István](https://github.com/Istvan987)
- [Lisztes Líviusz](https://github.com/RidingUndead)


### Az oldal
- Ami szükséges:
    - [XAMPP](https://www.apachefriends.org/download.html) az adatbázishoz (Legalábbis, én ezt használom)
    - [Composer](https://getcomposer.org/download/) a Laravel alapfeltétele
    - A Laravel telepítése (a Parancssor alkalmazással):

            composer global require "laravel/installer"

- Előkészület:
    - A XAMPP alkalmazásban az Apache és a MySQL elindítása
    - [Localhostban](localhost/phpmyadmin) egy teeminkall nevű adatbázis létrehozása
    - Parancssor megnyitása az alkalmazás mappájában
    - A következő parancsok beírása:

            php artisan migrate

- Futtatás: 
    - A Parancssor alkalmazásban (ami az projekt mappájában lett megnyitva) beírjuk a parancsot:

            php artisan serve
