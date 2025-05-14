<?php

require_once __DIR__ . '/../vendor/autoload.php';

use App\Core\Database;

// Connexion à la base de données
$database = new Database();
$db = $database->connect();

// Inclure le fichier de routes
$router = require_once __DIR__ . '/../routes/api.php';

// Récupérer la méthode HTTP et l'URL de la requête
$requestMethod = $_SERVER['REQUEST_METHOD'];
$requestUri = $_SERVER['REQUEST_URI'];

// Traiter la requête via le routeur
$router->handle($requestMethod, $requestUri);
