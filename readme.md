# CharbonVélo - Projet Dev PHP

## Installation du projet

- cloner le repo

- Installer les dépendances

    ```bash
    composer install
    ```

- Créer la base de données dockerisée

    ```bash
    cd docker/
    docker compose up -d
    ```

- Executer les migrations

    ```bash
    php bin/console d:m:m
    ```