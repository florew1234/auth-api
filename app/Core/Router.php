<?php

namespace App\Core;
use App\Controllers\AuthController;
use App\Middleware\AuthMiddleware;

class Router {
    public $routes = [];
    public $test = [];

    // Ajouter une route
    public function add($method, $uri, $controller, $middleware = null) {
        $this->routes[] = [
            'method' => $method,
            'uri' => $uri,
            'controller' => $controller,
            'middleware' => $middleware
        ];
    }

    // Gérer la requête
    public function handle($method, $uri) {
        
        foreach ($this->routes as $route) {
            if ($route['method'] === $method && $route['uri'] === $uri) {
                if ($route['middleware']) {
                   $route['middleware']->authorize();
                
                }

                // Appeler le contrôleur et la méthode correspondante
                $controller = $route['controller'];
                //  (new $controller[0])();
                $controller[0]->{$controller[1]}();

                return;
            }

            $this->test [] = $route['method'] == $method && $route['uri'] == $uri;

        }


        // Si aucune route ne correspond
        http_response_code(404);
        echo json_encode(['message' => 'Route not found', 'route' => $this->test]);


        
    }
}
