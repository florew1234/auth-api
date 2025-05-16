🇫🇷 [Lire ce fichier en anglais](README.md)


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
- Composer 2.8.5
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

2. Installer les dépendances PHP :
   ```bash
  composer install
   ```

3. Copier le fichier .env d'exemple
   ```bash
cp .env.example .env
    ```

4. Configure ta base de données :
   - Crée une base de donnée 
   - Exécute cette requête
     ```sql

      CREATE TABLE users (
         id INT AUTO_INCREMENT PRIMARY KEY,
         first_name VARCHAR(100),
         last_name VARCHAR(100),
         email VARCHAR(255) UNIQUE,
         password TEXT
     ); 

     ```      

4. Copier le fichier .env d'exemple
  ```bash
  cp .env.example .env
  ```
Modifie le fichier .env en y mettant tes propres accès.
  ```env
   DB_HOST=ton localhost
   DB_NAME=le nom de ta db
   DB_USER=l'utilisateur de la db
   DB_PASS=le mot de passe de ton mysql 
   JWT_SECRET=ton_super_secret
    ```

5. Lance le serveur :
Déplace toi dans le dossier public et fais:
   ```bash
   php -S localhost:8000
   ```

---


# Documentation Technique.


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

Cette commande retourne un token JWT à utiliser avec `/me`.

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
## Tests API avec Postman

Toutes les routes ont été testées avec [Postman](https://www.postman.com/).  
La collection contient les principales opérations CRUD, avec des tests automatisés pour chaque requête.

- Requêtes GET, POST, PUT
- Authentification (login)
- Utilisation de variables (`base_url`)
- Tests de réponse HTTP (200, 201, 400, 401.)

- Télécharger la collection Postman ici: postman/auth_api.postman_collection.json


### Générer ou mettre à jour la documentation :

1. Assures-toi d’avoir installé swagger (swagger-php et swagger-ui).
  - Commande pour installer swagger-php
```bash
  composer require zircote/swagger-php
```
- Commande pour installer swagger-ui
```bash
  npm install swagger-ui-dist
```

2. Commente tes routes selon la syntaxe OpenAPI (annotations dans les fichiers PHP).
3. Exécute la commande suivante à la racine du projet:
```bash
./vendor/bin/openapi --output swagger.json ./app,  
``` 
Un fichier swagger.json est généré à la racine du projet.

4. Ensuite, déplace ce fichier dans le dossier public/swagger-ui/dist, si il y avait déja un fichier swagger.json, remplace le.

### Consulter la documentation
Accède à la documentation sur ce lien 
http://localhost:8000/swagger-ui/dist/index.html


## Auteur

- **Florette AHOLOU**
- [LinkedIn](www.linkedin.com/in/florette-aholou-16969b304)
- [Portfolio](https://portfolio-florew1234s-projects.vercel.app)
- Email : ahlflorew@gmail.com
