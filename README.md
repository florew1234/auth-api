🇫🇷 [Lire ce fichier en français](README.fr.md)

# auth-api
# PHP authentication API with JWT
A simple authentication API built with native PHP, using **JWT (JSON Web Token)** for server-side session management.

---

## Features
- New user registration (`/register`)
- User login (`/login`)
- Recover logged-in user via token (`/me`)
- Modify token user info (`/me/update`)
- Secure authentication with **X-Auth-Token**.
- Encryption of password with `password_hash`.
- Verify password with `password_verify
- JWT signed with secret key


---

## Technologies used
- PHP 8.3
- JWT (via a custom service)
- MySQL 8.0
- Native HTTP (`getallheaders`, `json_encode`)

---


## Packages used
- firebase/php-jwt : for token management,
- vlucas/phpdotenv: for chraging variables from .env into other files,
- zircote/swagger-php: for API documentation,

---


## Minimal file organization
```
├── app
│ ├── Controllers
│ │ └── AuthController.php
│ ├── Core
│ │ ├── Database. php
│ │ └── Router.php
│ ├── Middlewares
│ │ └── AuthMiddleware.php
│ └── Models
│ └── User.php
````
---


## Prerequisites
- PHP ≥ 8.0
- Local web server (Apache, Nginx, or PHP Built-in Server)
- MySQL database
---


## Launch project locally
1. Clone this repo:
 ```bash
 git clone https://github.com/florew1234/auth-api.git
 cd auth-api
 ```
2. Command to run:
 ````
 compose install: to install all project dependencies.
./vendor/bin/openapi --output swagger.json ./docs ./app: to generate project documentation.
   ```


3. Configure your database:
   - Create an `auth_api` database
   - Execute this SQL:

 ```sql
 CREATE TABLE users (
 id INT AUTO_INCREMENT PRIMARY KEY,
 first_name VARCHAR(100),
 last_name VARCHAR(100),
 email VARCHAR(255) UNIQUE,
 password TEXT
 );
     
     or
     run the migration file with these scripts at the project root if you're using zsh:
./scripts/createMigration.zsh users : to create the migration file. Adapt the contents of the file to include this query:
 CREATE TABLE users (
 id INT AUTO_INCREMENT PRIMARY KEY,
 first_name VARCHAR(100),
 last_name VARCHAR(100),
 email VARCHAR(255) UNIQUE,
 password TEXT
 ); 
 ```
 
 then run ./scripts/runMigrations.zsh : to launch your migration

 4. Create an `.env` file, replacing its values with your own data:
 ```env
 DB_HOST=localhost
 DB_NAME=auth_api
 DB_USER=root
 DB_PASS=
 JWT_SECRET=ton_super_secret
 ```
5. Run the server:
Go to the public folder and do:
 ```bash
 php -S localhost:8000
 ````


---


## Endpoints available
| Method | Endpoint | Description |
|--------:|-----------|-------------------------------|
| POST | /register | User registration |
| POST | /login | Connection |
| GET | /me | User info (token required) |
| GET | /me /update | Modify user info (token required) |


---


## Authentication
The JWT token is sent via the following HTTP header:
```http
X-AUTH-TOKEN: {your_token}
````


---


## Example of `curl` requests
### Register a user
```bash
curl -X POST http://localhost:8000/register \
-H "Content-Type: application/json" \
-d '{
 "first_name": "Alice",
 "last_name": "Dupont",
 "email": "alice@example.com",
 "password": "secret123"
}'
````
### Log in
```bash
curl -X POST http://localhost:8000/login \
-H "Content-Type: application/json" \
-d '{
 "email": "alice@example.com",
 "password": "secret123"
}'
```
This command returns a JWT token for use with `/me`.


### Retrieve info from logged-in user
```bash
curl -X GET http://localhost:8000/me \
H "X-AUTH-TOKEN: {your_token_here}"
```

### Modify logged-in user info
```bash
curl -X PUT http://localhost:8000/me/update \
-H "X-AUTH-TOKEN: {your_token_here}" \
-H "Content-Type: application/json" \
-d '{
 "first_name": "Alice1",
 "last_name": "Dupont",
 "email": "alice@example.com",
 "password": "secret123"
}'
```


---


## API testing with Postman
All routes have been tested with [Postman](https://www.postman.com/).  
The collection contains the main CRUD operations, with automated tests for each request.
- GET, POST, PUT requests
- Authentication (login)
- Use of variables (`base_url`)
- HTTP response tests (200, 201, 400, 401.)
- Download the Postman collection here: postman/auth_api.postman_collection.json



## Technical documentation.


### How to generate or update documentation:
1. Make sure you have installed swagger (swagger-php and swagger-ui).
  - Command to install swagger-php
```bash
 composer require zircote/swagger-php
```
- Command to inst

Translated with DeepL.com (free version)