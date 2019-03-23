# SharePic API

## Pré-requis
Avoir installer un serveur mysql, php, composer et laravel

## Installation

* Cloner le projet et rentrer dedans
* Récupérer le script SQL "**share-pic.sql**" et l'importer dans votre serveur MySQL
* Rentrer dans le dossier racine de l'api `$> cd sharepic/`
* Créer le fichier "**.env**" `$> mv .env.example .env`
* Éditer le fichier "**.env**", notamment les champs concernant la database `$> nano .env`
* Installer les dépendances `$> composer install`
* Générer une application key `$> php artisan key:generate`
* Générer une clé passeport `$> php artisan passport:install`
* Lancer le serveur `$> php artisan serve` (Ne pas oublier de lancer votre serveur MySQL au préalable)

Vous pouvez des à présent utiliser notre API!!

## Routes

Lire le fichier Excel "**WebServices.xlsx**"
