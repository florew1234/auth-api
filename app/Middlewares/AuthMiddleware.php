<?php

namespace App\Middlewares;

use App\Models\User;
use Firebase\JWT\JWT;
use Exception;
use Firebase\JWT\Key;

class AuthMiddleware
{

    public function authorize()
    {
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

            // Récupérer l'utilisateur via son id
            $userId = $decoded->sub;
            $user = User::getById($userId);

            if (!$user) {
                http_response_code(401);
                echo json_encode(['message' => 'User not found']);
                exit;
            }

            // Injection de l'utilisateur connecté dans une variable globale
            $_REQUEST['authenticated_user'] = $user;
        } catch (Exception $e) {
            http_response_code(401);
            echo json_encode(['message' => 'Invalid token', 'details' => $e->getMessage()]);
            exit;
        }
    }
}
