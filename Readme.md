# Coffreo : Recruitment Challenge

## Objectif

Le but de cet exercice est de reprendre le code source de l'application fournie et de l'améliorer en utilisant les bonnes pratiques de développement.
Le projet est composé de plusieurs worker PHP utilisant RabbitMQ pour la communication asynchrone.

### Premier worker

Le premier worker consommera des messages contenant le nom du pays.
Il utilisera l’API REST https://restcountries.com/ pour récupérer la capitale du pays spécifié.
Il publiera le résultat pour un second worker.

### Deuxième worker

Le second worker consommera le message publié par le premier worker contenant la capitale du pays.
Il utilisera l'API REST https://restcountries.com/ pour récupérer des informations sur la capitale.

## Piste d'améliorations

- Refactoring : Améliorer la qualité du code en refactorisant les parties qui en ont besoin.
- Gestion des Erreurs : Gérer les erreurs et les scénarios d’échec de manière robuste et réfléchie.
- Tests Unitaires : Ajouter des tests unitaires pour les workers.
- Documentation : Ajouter des commentaires et de la documentation pour expliquer le fonctionnement de l’application.
- Sécurité : Assurer que l’application est sécurisée et qu’elle ne présente pas de vulnérabilités.
- Performances : Optimiser les performances de l’application.
- Scalabilité : Assurer que l’application est capable de gérer un grand nombre de requêtes simultanées.
- Extensibilité : Assurer que l’application est facile à étendre et à maintenir.
- Docker : Modifier le dockerfile ou le docker-compose pour faciliter le déploiement de l’application.
- CI/CD : Ajouter un pipeline CI/CD pour automatiser les tests et le déploiement de l’application.
- Toute autre amélioration que vous jugez pertinente.

## Bonus

Créer des nouveaux workers pour compléter le processus et ajouter des fonctionnalités à l'application.

## Livrable

- Le code source de l’application, y compris les fichiers de configuration.
- Une documentation comprenant au moins la manière de configurer et de lancer l’application.
– Une description des composants externes utilisés et la justification de leur choix.

## Évaluation

Les candidats seront évalués sur leur capacité à :

- Implémenter une communication efficace entre les workers via RabbitMQ.
- Interagir correctement avec une API externe et traiter les données reçues.
- Gérer les erreurs et les scénarios d’échec de manière robuste et réfléchie.
- Organiser et documenter leur code pour faciliter la maintenance et la compréhension.
- Opter pour des bibliothèques ou des composants qui sont strictement nécessaires pour accomplir les objectifs de l’exercice.
- Justifier ses décisions techniques prises, en particulier le choix des bibliothèques.
- Fournir un livrable facilement exécutable avec les instructions pour tester son application.

## Message au candidat

Alors que vous vous apprêtez à relever ce défi, nous tenons à vous exprimer notre soutien et notre encouragement.

Cet exercice est une opportunité pour vous de montrer vos compétences uniques et votre capacité à innover dans la résolution de problèmes.

Ne voyez pas cela seulement comme un test, mais comme une occasion de partager votre passion pour le développement.

Soyez assurés que nous recherchons plus qu’une solution fonctionnelle ; nous cherchons à comprendre votre approche, votre manière de penser et la façon dont vous relevez les défis.

Nous vous remercions d’avance pour votre engagement et pour jouer le jeu avec sérieux et créativité.

# Getting Started
## Requirements

- Linux distribution
- Docker version 27.3.1
- [just](https://github.com/casey/just)

### Installation

- In root folder type :
```
just setup-app
```

### Usage
- In root folder type following command to see all commands available in project :
```
just
```
- For test workflow use this command :
```
just send-code-country {CODE_COUNTRY}
```

### Explications

#### Symfony
Le framework symfony est tout à fait adapté au besoin demandé grâce à sa librairie messenger il permet de mettre en place facilement et de façon solide une architecture asynchrone.

#### Architecture
Je suis partie sur une architecture hexagonal même si cela ne serait pas nécessaire pour le besoin ça permet de facilement isoler les responsabilités.

#### Psalm et Cs fixer
C'est les deux outils que j'ai l'habitude d'utiliser pour l'analyse statique et l'uniformisation du code. Avec du temps, j'essayerais phpStan.

#### Phpunit
Je n'ai pas pu mettre la dernière version de phpunit à cause de psalm.
Les deux librairie partage un paquet avec des prérequis de versions non compatibles.

#### Tests 
J'ai volontairement pas mis de tests sur l'ensembles des services pour plusieurs raisons.

Au vue de la simplicité du projet, les services les plus critiques sont testés.

Pour faire un test sur ApiClient, il aurait fallu mocker HttpClientInterface qui n'est pas le plus simple à mocker.

En outre, je considère que rajouter trop de mock dans les tests perd l'intérêt du test, on ne test plus vraiment ce qu'on test.

#### CI/CD
A titre personnel, je suis plus familier avec la ci de gitlab mais elle est moins accessible à ma connaissance lorsque l'on a pas ses propres runners.

#### Docker
J'ai fait le choix de passer par les images bitnami qui sont plus simples à mettre en place et demande moins de configuration.

Elles sont en root et pose moins de problèmes de gestion de droit de fichiers.

#### Just vs Makefile
Je trouve que Just est plus adapté que les makeFile dans l'utilisation que l'on en fait. 

Il rend son fichier de commande plus digeste et la syntaxe est plus lisible selon moi.

#### Supervisor
Supervisor est tout à fait pertinent dans la gestion des workers, il donne plus de contrôle que de passer par des cron.

#### RabbitMQ / Messenger
J'ai fait le choix de mettre les deux messages sur une seule queue rabbit.

Avec messenger c'est très simple de scaler si le nombre de messages devenait important.

Si à l'utilisation l'un des messages devenait trop lent (Ex. si l'api répondait avec un temps beaucoup plus long sur l'un des deux endpoint) la question d'isoler ce dernier sur une queue dédiée est assez simple à faire.

### Conclusions
J'ai passer environ une journée pour réaliser cet exercice. 
Si j'avais eu plus de temps j'aurais rajouter les éléments suivants :
- Du cache avec redis pour limiter les appels à l'api afin d'accélérer les traitements et limiter la charge serveur.
- Un endpoint en rest avec un serveur nginx afin de pouvoir envoyer des codes country.
- Sauvegarde en base des résultats et endpoint pour récupérer le résultat

