# trt-conseil

Ce projet permet de mettre en contact facilement des employeurs et des candidats via la mise en ligne d'offres d'emplois pour les employeurs et de CV pour les candidats.
Le tout géré via différents compte : un compte administrateur et un compte consultant.

## Requirements

Il faut :
- composer (v2.1.8)
- symfony (v4.26.3)
- un serveur (ex: MAMP)
- mailgun

créer un host pour le projet

## Installation

La première chose à faire est d'installer le dépôt sur votre machine

`git clone git@github.com:juliaDebray/trt_conseil.git`

Puis dnas l'invit de commande :

```bash
cd trt_conseil
composer install
```

A la ligne 30 du fichier .env, rentrez votre identifiant, mot de passe, port que vous utiliserez et nom de votre base de donnée. 

créer le vhost pour le projet

Puis exécutez dans l'invit de commande :

```bash
php bin/console doctrine:database:create 
php bin/console make:migration
php bin/console doctrine:migrations:migrate
chmod 777 public/uploads/booksCover
```

dans le fichier .env, rajouter votre clé secrète Mailgun à la ligne 22.

Pour tester le projet :
Vous disposez des comptes suivants : 

| Accout   | Login                | Password |
|----------|----------------------|----------|
| Admin    | admin@admin.fr       | admin    |
| Candidat | candidat@candidat.fr | candidat |
| Consultant | consultant@consultant.fr | consultant |
| Recruteur  | recruteur@recruteur.fr | recruteur |

Pour vérifier toutes les fonctionalités : 

-sur localhost, se connecter avec le compte administrateur et créer un compte consultant.
(Vous pouvez aussi tester les autres fonctionnalités du panneau d'administrateur)

-créer ensuite un compte de recruteur(avec une adresse mail valide) et un compte de candidat en cliquant sur "créer un compte"

-se connecter sur le compte consultant et valider les deux comptes créés.

-créer une offre d'emploi sur le compte recruteur

-valider l'offre avec le consultant

-candidater avec le compte candidat 
(vous serez obligé d'ajouter un CV à cette étape)

-valider la candidature avec le consultant 
(si mailgun est bien paramétré, un mail est envoyé au recruteur à cet instant, 
sinon vous aurez une erreur mais qui ne bloque pas la validation de la candidature)

-consulter la candidature avec le recruteur

-Enfin vous pouvez vérifier si le recruteur peut ajouter son nom d'entreprise 
et l'adresse de son entreprise dans son profil


