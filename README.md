# Vote électronique

## Table des matières :

1. [Ressources](README.md#1-ressources-)  
2. [Projet](README.md#2-projet-)  
   a. [Contexte](README.md#a-contexte-)  
   b. [Dates](README.md#b-dates-)  
3. [Environnement](README.md#3-environnement-)  
4. [Utilisation](README.md#4-utilisation-)

## 1. Ressources :

**Projet :** *https://github.com/ThomasSEGALEN/online_vote/projects*  
**Wiki :** *https://github.com/ThomasSEGALEN/online_vote/wiki*  
**GitHub :** *https://github.com/ThomasSEGALEN/online_vote*  
**Trello :** *https://trello.com/invite/b/dh02LK3O/ATTI5bd163cf05c39aacd5d37e03e4f57651F126CBFB/ivotes*

## 2. Projet :

### a. Contexte :

Le projet « Vote électronique » a été créé pour répondre aux besoins des collectivités, aussi bien en France qu’en Europe. Ce projet est proposé par la Communauté d’Agglomération de Rochefort Océan. L’idée est de créer une application permettant aux collectivités de créer des séances de votes en ligne. Pour répondre à ce besoin, nous avons décidé d’utiliser le framework Laravel couplé à une base de données MySQL. Ce projet a aussi pour but d’initier le développeur junior aux missions de développeur informatique.

### b. Dates :

La période d'alternance se déroule du 26 septembre 2022 au 15 septembre 2023.  
L'alternant est présent deux semaines sur trois au sein de l'entreprise.

-   26 et 27 septembre 2022 - Présentation du projet
-   26 août 2023 - Soutenance

## 3. Environnement :

|   _Nom_    | _Version_ |
| :--------: | :-------: |
|    PHP     |  ^8.2.0   |
|  Laravel   |  ^10.13.5 |
|   VueJS    |  ^3.2.41  |
| TypeScript |  ^5.1.3   |
|    Vite    |  ^4.0.0   |

## 4. Utilisation :

Installation du projet :  
`git clone https://github.com/ThomasSEGALEN/online_vote.git` (Ajouter le fichier *.env* en modifiant si nécessaire la configuration du *.env.example*)  
Installation des dépendances :  
`npm install`  
`composer install`  
Migration de la base de données :  
`php artisan migrate`  
Remplissage de la base de données :  
`php artisan db:seed`  
Lancement du projet dans un environnement de développement :  
`npm run dev`  
`php artisan serve`  
Lancement du projet dans un environnement de production :  
`npm run build` (Dossier de production disponible dans */public/build*)
