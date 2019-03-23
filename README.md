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

| Endpoint (classe) |  Uri vers la ressource                       | Méthode | Body de la requête (JSON)                                                                                   | Header                          | Liste de paramètres (Path Param)                          | Description                                                                                                             | 
|-------------------|----------------------------------------------|---------|-------------------------------------------------------------------------------------------------------------|---------------------------------|-----------------------------------------------------------|-------------------------------------------------------------------------------------------------------------------------| 
| Users             | /api/login                                   | POST    | username : "username" password : "password"                                                                 | .                               | .                                                         | Vérifie l'existance de l'utilisateur, et renvoi le token si ce dernier existe.                                          | 
| Users             | /api/register                                | POST    | name: "name" username : "username" password : "password" c_password: "password" picture: (FILE .jpg/.png)   | .                               | .                                                         | Vérifie si le username est déjà pris et renvoi le token si ce dernier n'existe pas.                                     | 
| Users             | /api/logout                                  | POST    | .                                                                                                           | Authorization: "Bearer {token}" | .                                                         | Vérifie si le token existe et le supprime                                                                               | 
| Users             | /api/users                                   | GET     | .                                                                                                           | Authorization: "Bearer {token}" | .                                                         | Renvoie la liste des utilisateurs inscris                                                                               | 
| Users             | /api/users/my                                | GET     | .                                                                                                           | Authorization: "Bearer {token}" |                                                           | Renvoie les informations du user faisant la requête                                                                     | 
| Users             | /api/users/{id}                              | GET     | .                                                                                                           | Authorization: "Bearer {token}" | id: l'id du user à rechercher                             | Renvoie le user recherché si l'id correspond                                                                            | 
| Users             | /api/users/following                         | GET     | .                                                                                                           | Authorization: "Bearer {token}" | .                                                         | Renvoie la liste des utilisateurs que le user faisant la requete follow                                                 | 
| Users             | /api/users/followers                         | GET     | .                                                                                                           | Authorization: "Bearer {token}" | .                                                         | Renvoie la liste des utilisateurs qui follow le user faisant la requete                                                 | 
| Users             | /api/users/{id}/following                    | GET     | .                                                                                                           | Authorization: "Bearer {token}" | id: l'id du user                                          | Renvoie la liste des utilisateurs que le user passé en paramètre follow                                                 | 
| Users             | /api/users/{id}/followers                    | GET     | .                                                                                                           | Authorization: "Bearer {token}" | id: l'id du user                                          | Renvoie la liste des utilisateurs qui follow le user passé en paramètre                                                 | 
| Users             | /api/users/search/{username}                 | GET     | .                                                                                                           | Authorization: "Bearer {token}" | username: Le pattern username à rechercher                | Renvoie la liste des utilisateurs dont le username match avec le pattern passé en paramètre                             | 
| Pictures          | /api/pictures                                | POST    | description : "description of the picture" geolocation : "location of the upload" picture: (FILE .jpg/.png) | Authorization: "Bearer {token}" | .                                                         | Upload une photo                                                                                                        | 
| Pictures          | /api/pictures                                | GET     | .                                                                                                           | Authorization: "Bearer {token}" | .                                                         | Renvoie les informations MINIMALES de toutes les photos                                                                 | 
| Pictures          | /api/pictures/{id}                           | GET     | .                                                                                                           | Authorization: "Bearer {token}" | id: id de la photo                                        | Renvoie les informations MINIMALES de la photo passé en id                                                              | 
| Pictures          | /api/pictures/news                           | GET     | .                                                                                                           | Authorization: "Bearer {token}" | .                                                         | Renvoie les informations COMPLETES de toutes les dernières photos des abonnements de l'utilisateur qui fais la requête. | 
| Pictures          | /api/pictures/news/{id}                      | GET     | .                                                                                                           | Authorization: "Bearer {token}" | id: id du user                                            | Renvoie les informations COMPLETES de toutes les dernières photos des abonnements de l'utilisateur passé en paramètre   | 
| Pictures          | /api/pictures/users                          | GET     | .                                                                                                           | Authorization: "Bearer {token}" | .                                                         | Renvoie les informations complètes de toutes les  photos  de l'utilisateur faisant la requête                           | 
| Pictures          | /api/pictures/users/{id}                     | GET     | .                                                                                                           | Authorization: "Bearer {token}" | id: id du user                                            | Renvoie les informations complètes de toutes les  photos  de l'utilisateur passé en paramètre                           | 
| Pictures          | /api/pictures/users/quantity/{quantity}      | GET     | .                                                                                                           | Authorization: "Bearer {token}" | quantity: nombre maximale de photo renvoyé                | Renvoie les informations complètes des {quantity} dernières  photos  de l'utilisateur faisant la requête                | 
| Pictures          | /api/pictures/users/{id}/quantity/{quantity} | GET     | .                                                                                                           | Authorization: "Bearer {token}" | id: id du user quantity: nombre maximale de photo renvoyé | Renvoie les informations complètes des {quantity} dernières  photos  de l'utilisateur passé en paramètre                | 
| Follow            | /api/follow/{id}                             | POST    | .                                                                                                           | Authorization: "Bearer {token}" | id: id du user à follow                                   | Le user faisant la requête follow le user passé en paramètre                                                            | 
| Follow            | /api/follow/{id}                             | DELETE  | .                                                                                                           | Authorization: "Bearer {token}" | id: id du user à unfollow                                 | Le user faisant la requête unfollow le user passé en paramètre                                                          | 
| Likes             | /api/likes/{id}                              | POST    | .                                                                                                           | Authorization: "Bearer {token}" | id: id de la photo à liker                                | Le user faisant la requête like la photo passée en paramètre                                                            | 
| Likes             | /api/likes/{id}                              | DELETE  | .                                                                                                           | Authorization: "Bearer {token}" | id: id de la photo à liker                                | Le user faisant la requête like la photo passée en paramètre                                                            | 
| Comments          | /api/comments/{id}                           | POST    | content: "le commentaire"                                                                                   | Authorization: "Bearer {token}" | id: id de la photo à commenter                            | Le user faisant la requête commente la photo passée en paramètre                                                        | 
| Comments          | /api/comments/{id}                           | DELETE  | .                                                                                                           | Authorization: "Bearer {token}" | id: id du commentaire à supprimer                         | Le user faisant la requête supprime le commentaire passé en paramètre                                                   | 
