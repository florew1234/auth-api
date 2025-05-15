<?php

namespace App\Middlewares       ;

use Firebase\JWT\JWT;
use Exception;
use Firebase\JWT\Key;

class AuthMiddleware {

    public function authorize() {
    $headers = getallheaders();
    if (!isset($headers['Authorization'])) {
        http_response_code(401);
        echo json_encode(['message' => 'Authorization header missing']);
        exit;
    }

    $authHeader = $headers['Authorization'];
    if (!preg_match('/Bearer\s(\S+)/', $authHeader, $matches)) {
        http_response_code(401);
        echo json_encode(['message' => 'Invalid authorization format']);
        exit;
    }

    $jwt = $matches[1];
    $key = $_ENV['JWT_SECRET_KEY'] ?? null;

    try {
        $decoded = JWT::decode($jwt, new Key($key, 'HS256')); 
        return $decoded->sub; 
    } catch (Exception $e) {
        http_response_code(401);
        echo json_encode(['message' => $e->getMessage(), 'key' => $key]);
        exit;
    }
}


}



