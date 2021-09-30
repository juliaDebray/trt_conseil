# trt-conseil

Ce projet permet de mettre en contact facilement des employeurs et des candidats via la mise en ligne d'offres d'emplois pour les employeurs et de CV pour les candidats.
Le tout géré via différents compte d'administration.

## Prérequis

Il faut :
- composer (v2.1.8)
- symfony (v4.26.3)
- server (ex: MAMP)
- mailgun

créer un host 

## Installation

La premier chose à faire est d'installer le dépôt sur votre machine

`git clone git@github.com:juliaDebray/trt_conseil.git`

Ensuite copier le `.env.example` et le nommer en `.env`. 

A la ligne 30 du fichier .env, rentrer votre identifiant, mot de passe, port que vous utiliserez et nom de votre base de donnée. 

créer un vhost

lancer la commande `composer install`

lancer la commande php bin/console doctrine:database:create

lancer la commande php bin/console make:migration

lancer la commande php bin/console doctrine:migrations:migrate

lancer la commande composer require symfony/mailer

lancer la commande composer require symfony/mailgun-mailer

dans le fichier .env, décommenter la ligne 22 et rajouter votre clé secrète Mailgun.

Créer un compte administrateur :

lancer la commande php bin/console security:hash-password pour créer un mot de passe hashé.

choisir l'option 0 correspondant à la classe User et rentrer votre mot de passe.

Copier le password hash.

Aller dans la base de donnée, dans la table user et rentrer 1 dans l'id, choisir un email pour l'email, rentrer ["ROLE_ADMIN"] dans roles, mettre le mot de passe hashé dans password(attention il ne doit pas y avoir d'espace à la fin), et enfin écrire 'validated' dans status. éxecuter la requête.

Pour tester le projet :
-sur localhost, se connecter avec le compte administrateur et créer un compte consultant.

-créer ensuite un compte de recruteur et un compte de candidat en cliquant sur "créer un compte"

-se connecter sur le compte consultant et valider les deux créés.

-créer une offre d'emploi sur le compte recruteur

-valider l'offre avec le consultant

-candidater avec le compte candidat

-valider la candidature avec le consultant

-consulter la candidature avec le recruteur

