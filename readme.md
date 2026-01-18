# Scratchy

Minimal PHP framework for generating MVC-style pages, using Docker Compose for both PHP and MySQL.

## Overview

Scratchy provides simple helpers to automate routing, build HTML markup from PHP, and manage database
schema and data. It is designed for rapid development while keeping presentation and data logic separate.

## Requirements

- Git
- Docker + Docker Compose (Compose v2)

No local PHP or MySQL installation is required.

## Features

- Programmatic construction of HTML
- Automatic database management and modification
- Fully containerized PHP + MySQL environment

## Installation

Clone the repository:

```bash
git clone https://github.com/johnkellyphotos/scratchy.git
cd scratchy
```

## Start the stack (PHP + MySQL)
Start the stack (PHP + MySQL)
```bash
docker compose build
docker compose up -d
```
Verify containers are running:
```bash
docker compose ps
```

## Access the application
Open in your browser [http://127.0.0.1:8000](http://127.0.0.1:8000).

he PHP built-in server runs inside Docker and serves from: html/index.php

## Database configuration (important)
The PHP container connects to MySQL using Docker service names.

The following environment variables are already configured in docker-compose.yml:
- DB_HOST=mysql
- DB_PORT=3306
- DB_NAME=app_db
- DB_USER=app_user
- DB_PASS=app_pass

Do not change these to 127.0.0.1 or 3307 when running inside Docker.

## Routing
By default, requests to / are routed to:
```php
HomeController::index()
```
Routes follow the pattern: `/{controller}/{action}`.
Example:
`/Buy-Stuff/a-new-car` dispatches `BuyStuffController::aNewCar()`

## Stopping the stack
```bash
Stopping the stack
```
Remove containers and database data (destructive):
```bash
docker compose down -v
rm -rf mysql-data
```