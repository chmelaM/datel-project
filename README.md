# datel-project
# nainstalovat Wamp/Lamp/Mamp

# nainstalovat Composer z 
https://getcomposer.org/download


# v prikazovem radku ve slozce pro projekty spusit prikaz:
composer create-project --prefer-dist laravel/laravel datel-project

# otevrit slozku a spustit prikaz:
php artisan storage:link

# odnastavit prava "jen pro cteni" slozky public a vsech podslozek 

# vytvorit virtual host ve wamp ktery bude ukazovat do slozky projektu/public
# (c:/wamp/www/datel-project/public)

# restartovat dns server ve wamp (prave tlacitko na ikonu wamp serveru vpravo dole -> tools -> restart DNS)

# instalace Git
https://git-scm.com/download/win

# vytvorit si ucet na github.com a poslat mi uzivatelske jmeno abych vas pridal do projektu
# pote pokracovat dalsimi kroky

# ve slozce projektu spustit:
git init

git remote add origin https://github.com/pisko999/datel-project.git

git fetch

git checkout origin/master -ft

# prejmenovat soubor .env.setup na .env (stary se muze smazat)

# ve slozce projektu spustit:
php artisan key:generate

composer require barryvdh/laravel-debugbar --dev

composer require laravelcollective/html

composer require phpoffice/phpspreadsheet

composer update

----------

# prace s projektem

# controllers se nachazeji ve slozce app/http/controllers
# objekty ve slozce app/objects
# modely ve slozce app/models
# repositories ve slozce app/repositories
# routes jsou v souboru routes/web.php
# views ve slozce resources/views a pouzivaji koncovku .blade.php


----------
# prace s git:
# pri vytvoreni noveho souboru spustit prikaz (v slozce kde se soubor nachazi):
git add "jmeno_souboru.php"

# stazeni zmen:
git pull

# pro odeslani zmen:
git pull // dulezite pred kazdym odesilanim odesilanim mit aktualni verzi ze serveru

git commit -m "message"

git push

