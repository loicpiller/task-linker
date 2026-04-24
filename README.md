# TaskLinker

Application de gestion de projets et de taches, developpee avec Symfony 7.3.

## Prerequis

- PHP >= 8.2
- Composer
- Une base de donnees compatible Doctrine (MySQL, PostgreSQL, SQLite, etc.)
- Symfony CLI (recommande)

## Installation

1. Cloner le depot :

```bash
git clone https://github.com/loicpiller/task-linker.git
cd task-linker
```

2. Installer les dependances :

```bash
composer install
```

3. Configurer l'environnement :

Copier le fichier `.env` en `.env.local` et renseigner les variables d'environnement, notamment la connexion a la base de donnees :

```bash
cp .env .env.local
```

Exemple avec MySQL :

```dotenv
DATABASE_URL="mysql://utilisateur:motdepasse@127.0.0.1:3306/tasklinker?serverVersion=8.0&charset=utf8mb4"
```

Exemple avec PostgreSQL :

```dotenv
DATABASE_URL="postgresql://utilisateur:motdepasse@127.0.0.1:5432/tasklinker?serverVersion=16&charset=utf8"
```

4. Creer la base de donnees et executer les migrations :

```bash
php bin/console doctrine:database:create
php bin/console doctrine:migrations:migrate
```

5. (Optionnel) Charger les fixtures :

```bash
php bin/console doctrine:fixtures:load
```

6. Lancer le serveur de developpement :

```bash
symfony server:start
```

## Qualite de code

Verifier le respect des standards de codage Symfony :

```bash
composer cs
```

Corriger automatiquement :

```bash
composer cs-fix
```

## Choix techniques

### Statuts des projets

Le modele de donnees a ete concu pour permettre la creation de statuts personnalises pour chaque projet (relation `OneToMany` entre `Project` et `Status`). Cependant, pour cette premiere version, cette fonctionnalite ne fait pas partie des specifications techniques et aucune maquette n'a ete realisee pour la gestion des statuts personnalises. Par consequent, trois statuts par defaut (**To Do**, **Doing**, **Done**) sont automatiquement crees a la creation de chaque nouveau projet.
