<?php

use App\Controllers\AuthController;
use App\Middlewares\AuthMiddleware;

$router = new \App\Core\Router();

// Routes publiques
$router->add('POST', '/register', [new AuthController(), 'register']);
$router->add('POST', '/login', [new AuthController(), 'login']);

// Routes protégées (requièrent un JWT)
$router->add('GET', '/me', [new AuthController(), 'getUserInfo'], new AuthMiddleware());
$router->add('PUT', '/me/update', [new AuthController(), 'updateUserInfo'], new AuthMiddleware());

return $router;
