
# Invoice Parser

Ce projet est une application Symfony qui permet de parser des fichiers de type JSON et CSV contenant des informations sur des factures. Les données extraites sont ensuite mises à jour dans la base de données via des requêtes SQL.

## Prérequis

- Docker
- Docker Compose

## Installation

1. Clone le projet dans ton répertoire local.

   ```bash
   git clone https://github.com/ton-repository/invoice-parser.git
   cd invoice-parser
   ```

2. Construis et lance les containers Docker.

   ```bash
   make docker-run
   ```

   Cela créera les containers pour l'application Symfony et la base de données PostgreSQL.

3. Installe les dépendances PHP via Composer.

   ```bash
   make docker-cpi
   ```

4. Créer la base de données et exécute les migrations.

   ```bash
   make migrate
   ```

## Lancer l'application

### 1. Exécuter la commande de parsing

Pour parser les fichiers de factures (`json` ou `csv`), exécute la commande suivante :

   ```bash
  make app-parse
   ```

Cette commande va charger et parser les fichiers, puis mettre à jour les enregistrements dans la base de données.

### 2. Lancer les tests unitaires

Pour vérifier le bon fonctionnement de l'application, tu peux exécuter les tests unitaires via PHPUnit. Pour cela, utilise la commande suivante :

   ```bash
  make phpunit-test-parse
   ```

Pour exécuter tous les tests dans le projet, tu peux utiliser :

   ```bash
  make phpunit
   ```
