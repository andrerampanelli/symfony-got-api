# GOT API

A simple repository to learn a bit more about API Platform and Symfony

## Technologies

This simple project that uses PHP as language with Symfony as Framework.
It has Postgres as DB, but it has elasticsearch almost ready to use for filtering.

Everything is containerized.

## Spin up the project

The project has a Makefile with easier methods to spin up the project and helper commands.

| Command            |  Description                      |
|--------------------|-----------------------------------|
| cache-clear        | Clear cache                       |
| first-start        | Spin-up first time app            |

| Command            |  Description                      |
|--------------------|-----------------------------------|
| tests              | Run all tests                     |
| coverage           | Run all tests with coverage       |
| lint               | Run all linters                   |
| database-init-test | Init database for tests           |

| Command            |  Description                      |
|--------------------|-----------------------------------|
| build              | Build app with fresh images       |
| start              | Start app                         |
| build-start        | Build and start app               |
| stop               | Stop app                          |
| php-bash           | Use PHP container bash            |

| Command              |  Description                    |
|----------------------|---------------------------------|
| composer-install     | Install dependencies            |
| composer-update      | Update dependencies             |
| composer-clear-cache | clear-cache dependencies        |

| Command                |  Description                  |
|------------------------|-------------------------------|
| database-init          | Init database                 |
| database-drop          | Drop database                 |
| database-create        | Create database               |
| database-remove        | Drop database                 |
| database-migration     | Make migration                |
| migration              | Alias: database-migration     |
| database-migrate       | Migrate migrations            |
| migrate                | Alias: database-migrate       |
| database-fixtures-load | Load fixtures                 |
| fixtures               | Alias: database-fixtures-load |

## Endpoints

| Method | Endpoint          | Description                  |
|--------|-------------------|------------------------------|
| GET    | /characters       | List all characters          |
| GET    | /characters/{id}  | Get a specific character     |
| POST   | /characters       | Create a new character       |
| PATCH  | /characters/{id}  | Update a specific character  |
| DELETE | /characters/{id}  | Delete a specific character  |


| Method | Endpoint      | Description              |
|--------|---------------|--------------------------|
| GET    | /houses       | List all houses          |
| GET    | /houses/{id}  | Get a specific house     |
| POST   | /houses       | Create a new house       |
| PATCH  | /houses/{id}  | Update a specific house  |
| DELETE | /houses/{id}  | Delete a specific house  |


| Method | Endpoint      | Description              |
|--------|---------------|--------------------------|
| GET    | /actors       | List all actors          |
| GET    | /actors/{id}  | Get a specific actor     |
| POST   | /actors       | Create a new actor       |
| PATCH  | /actors/{id}  | Update a specific actor  |
| DELETE | /actors/{id}  | Delete a specific actor  |

