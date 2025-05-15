<?php

namespace App\Middlewares;

use Firebase\JWT\JWT;
use Exception;
use Firebase\JWT\Key;

class AuthMiddleware {

    public function authorize() {
        $headers = getallheaders();

        if (!isset($headers['X-AUTH-TOKEN'])) {
            http_response_code(401);
            echo json_encode(['message' => 'X-AUTH-TOKEN header missing']);
            exit;
        }

        $jwt = $headers['X-AUTH-TOKEN'];
        $key = $_ENV['JWT_SECRET_KEY'] ?? null;

        if (!$key) {
            http_response_code(500);
            echo json_encode(['message' => 'JWT secret key is not configured']);
            exit;
        }

        try {
            $decoded = JWT::decode($jwt, new Key($key, 'HS256'));
            return $decoded->sub; // Retourne l'ID utilisateur
        } catch (Exception $e) {
            http_response_code(401);
            echo json_encode(['message' => 'Invalid token', 'details' => $e->getMessage()]);
            exit;
        }
    }
}
