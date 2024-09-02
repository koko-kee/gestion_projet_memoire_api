# Gestion-Projet - Backend (Laravel)

## Description

Cette partie du projet est le backend de l'application, développé avec Laravel 11. Il gère l'API RESTful utilisée par le frontend pour les opérations côté serveur.

## Prérequis

Avant de commencer, assurez-vous d'avoir installé les éléments suivants :

- PHP (version 8.3 ou plus)
- Composer
- MySQL ou un autre système de gestion de base de données compatible

## Installation

### 1. Cloner le dépôt

1.1. Pour cloner le dépôt Git, ouvrez votre terminal ou votre invite de commandes et exécutez la commande suivante : 

`https://github.com/koko-kee/gestion_projet_memoire_api.git`.

1.2. Après le clonage, déplacez-vous dans le répertoire du projet en utilisant la commande suivante : `cd nom-du-repo-backend`.

### 2. Installer les dépendances

2.1. Pour installer les dépendances nécessaires au projet, exécutez la commande suivante : `composer install`.

### 3. Configurer l'environnement

3.1. Copiez le fichier de configuration d'environnement exemple en un nouveau fichier `.env` en utilisant cette commande : `cp .env.example .env`.

3.2. Ensuite, ouvrez le fichier `.env` avec un éditeur de texte de votre choix et mettez à jour les lignes suivantes avec les informations de votre base de données MySQL :

    DB_CONNECTION=mysql
    DB_HOST=127.0.0.1
    DB_PORT=3306
    DB_DATABASE=gestion-projet
    DB_USERNAME=root
    DB_PASSWORD=

3.3. Générez ensuite la clé d'application Laravel en exécutant la commande suivante : `php artisan key:generate`.

### 4. Lancer le serveur de développement

4.1. Lancez le serveur de développement Laravel pour vérifier que tout fonctionne correctement en exécutant la commande : `php artisan serve`.

L'application sera alors accessible à l'adresse `http://localhost:8000`.
