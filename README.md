# auth-api

# API d’authentification PHP avec JWT

Une API simple d’authentification construite avec PHP natif, utilisant **JWT (JSON Web Token)** pour la gestion des sessions côté serveur.

---

## Fonctionnalités

- Enregistrement d’un nouvel utilisateur (`/register`)
- Connexion d’un utilisateur (`/login`)
- Récupération de l’utilisateur connecté via token (`/me`)
- Modification des infos de l’utilisateur connecté via token (`/me/update`)
- Authentification sécurisée avec **X-Auth-Token**
- Encodage du mot de passe avec `password_hash`
- Vérification avec `password_verify`
- JWT signé avec une clé secrète

---

## Technologies utilisées

- PHP 8.3
- JWT (via un service custom)
- MySQL 8.0
- HTTP natif (`getallheaders`, `json_encode`)

---

## Packages utilisés

- firebase/php-jwt : pour la gestion des tokens,
- vlucas/phpdotenv : pour chrager les variables depuis le .env dans d'autre fichiers ,
- zircote/swagger-php : pour la documentation de l'API,


---

## Organisation minimale des fichiers

```
├── app
│   ├── Controllers
│   │   └── AuthController.php
│   ├── Core
│   │   ├── Database.php
│   │   └── Router.php
│   ├── Middlewares
│   │   └── AuthMiddleware.php
│   └── Models
│       └── User.php

```

---

## Pré-requis

- PHP ≥ 8.0
- Serveur web local (Apache, Nginx, ou PHP Built-in Server)
- Base de données MySQL

---

## Lancer le projet en local

1. Clone ce repo :
   ```bash
   git clone https://github.com/florew1234/auth-api.git
   cd auth-api
   ```

2. Commande à exécuter :
   ```
  composer install : pour installer toutes les dépendances du projet.
  ./vendor/bin/openapi --output swagger.json ./docs ./app : pour générer la documentation du projet.
   ```



2. Configure ta base de données :
   - Crée une base `auth_api`
   - Exécute ce SQL :
     ```sql
     CREATE TABLE users (
         id INT AUTO_INCREMENT PRIMARY KEY,
         first_name VARCHAR(100),
         last_name VARCHAR(100),
         email VARCHAR(255) UNIQUE,
         password TEXT
     );
     ```

3. Crée un fichier `.env` en remplacant ses valeurs par tes propres données :
   ```env
   DB_HOST=localhost
   DB_NAME=auth_api
   DB_USER=root
   DB_PASS=
   JWT_SECRET=ton_super_secret
   ```

4. Lance le serveur :
Déplace toi dans le dossier public et fais:
   ```bash
   php -S localhost:8000
   ```

---

## Endpoints disponibles

| Méthode | Endpoint  | Description                   |
|--------:|-----------|-------------------------------|
| POST    | /register | Enregistrement d’un utilisateur |
| POST    | /login    | Connexion                     |
| GET     | /me       | Infos de l'utilisateur (token requis) |
| GET     | /me /update      | Modifier infos de l'utilisateur (token requis) |

---

## Authentification

Le token JWT est envoyé via l’en-tête HTTP suivant :

```http
X-AUTH-TOKEN: {votre_token}
```

---

## Exemple de requêtes avec `curl`

### Enregistrer un utilisateur

```bash
curl -X POST http://localhost:8000/register \
-H "Content-Type: application/json" \
-d '{
  "first_name": "Alice",
  "last_name": "Dupont",
  "email": "alice@example.com",
  "password": "secret123"
}'
```

### Se connecter

```bash
curl -X POST http://localhost:8000/login \
-H "Content-Type: application/json" \
-d '{
  "email": "alice@example.com",
  "password": "secret123"
}'
```

> Cette commande retourne un token JWT à utiliser avec `/me`.

### Récupérer les infos de l'utilisateur connecté

```bash
curl -X GET http://localhost:8000/me \
-H "X-AUTH-TOKEN: {votre_token_ici}"
```

### Modifier les infos de l'utilisateur connecté
```bash
curl -X PUT http://localhost:8000/me/update \
-H "X-AUTH-TOKEN: {votre_token_ici}" \
-H "Content-Type: application/json" \
-d '{
  "first_name": "Alice1",
  "last_name": "Dupont",
  "email": "alice@example.com",
  "password": "secret123"
}'
```

---
## Lien vers la Documentation
http://localhost:8000/swagger-ui/dist/index.html

## Auteur

- **Florette AHOLOU**
- [LinkedIn](www.linkedin.com/in/florette-aholou-16969b304)
- [Portfolio](https://portfolio-florew1234s-projects.vercel.app)
- Email : ahlflorew@gmail.com
