<?php

namespace App\Controllers;

use App\Models\User;
use Firebase\JWT\JWT;
use Dotenv\Dotenv;

class AuthController
{

    private function getAuthenticatedUser()
{
    $dotenv = Dotenv::createImmutable(__DIR__ . '/../../');
    $dotenv->load();

    $headers = getallheaders();
    $token = $headers['X-AUTH-TOKEN'] ?? null;

    if (!$token) {
        http_response_code(401);
        echo json_encode(['error' => 'Missing token']);
        exit;
    }

    try {
        $decoded = JWT::decode($token, new \Firebase\JWT\Key($_ENV['JWT_SECRET_KEY'], 'HS256'));
        $userId = $decoded->sub;
        $user = User::getById($userId);

        if (!$user) {
            http_response_code(401);
            echo json_encode(['error' => 'User not found']);
            exit;
        }

        return $user;
    } catch (\Exception $e) {
        http_response_code(401);
        echo json_encode(['error' => 'Invalid token', 'details' => $e->getMessage()]);
        exit;
    }
}


    // Méthode pour l'inscription d'un utilisateur
    public function register()
    {
        $data = json_decode(file_get_contents("php://input"), true);

        // Validation des champs
        if (
            empty($data['first_name']) || !is_string($data['first_name']) ||
            empty($data['last_name']) || !is_string($data['last_name']) ||
            empty($data['email']) || !filter_var($data['email'], FILTER_VALIDATE_EMAIL) ||
            empty($data['password']) || strlen($data['password']) < 12
        ) {
            echo json_encode(['error' => 'Invalid input data. Make sure first_name and last_name are strings, email is valid and password is at least 6 characters.']);
            return;
        }

        $first_name = trim($data['first_name']);
        $last_name = trim($data['last_name']);
        $email = trim($data['email']);
        $password = $data['password'];

        $user = User::getByEmail($email);
        if ($user) {
            echo json_encode(['error' => 'Email already in use.']);
            return;
        }

        User::create($first_name, $last_name, $email, $password);

        echo json_encode(['message' => 'User created successfully']);
    }

    // Méthode pour la connexion d'un utilisateur
    public function login()
    {
        $dotenv = Dotenv::createImmutable(__DIR__ . '/../../');
        $dotenv->load();

        $data = json_decode(file_get_contents("php://input"), true);

        if (empty($data['email']) || !filter_var($data['email'], FILTER_VALIDATE_EMAIL)) {
            echo json_encode(['error' => 'Invalid email']);
            return;
        }

        if (empty($data['password']) || !is_string($data['password'])) {
            echo json_encode(['error' => 'Invalid password']);
            return;
        }

        $email = trim($data['email']);
        $password = $data['password'];

        $user = User::getByEmail($email);
        if (!$user) {
            echo json_encode(['message' => 'User not found']);
            return;
        }

        if (!password_verify($password, $user->password)) {
            echo json_encode(['message' => 'Incorrect password']);
            return;
        }

        $key = $_ENV['JWT_SECRET_KEY'] ?? null;

        if (!$key || !is_string($key)) {
            http_response_code(500);
            echo json_encode(['error' => 'JWT secret key is not configured properly.', 'key' => $key]);
            exit;
        }

        $issuedAt = time();
        $expirationTime = $issuedAt + 300;

        $payload = [
            'iat' => $issuedAt,
            'exp' => $expirationTime,
            'sub' => $user->id
        ];

        $jwt = JWT::encode($payload, $key, 'HS256');
        echo json_encode(['token' => $jwt]);
    }

    public function getUserInfo()
    {
        $data = json_decode(file_get_contents("php://input"), true);

        if (empty($data['email']) || !filter_var($data['email'], FILTER_VALIDATE_EMAIL)) {
            echo json_encode(['error' => 'Invalid email']);
            return;
        }

        $email = trim($data['email']);
        $user = User::getByEmail($email);
        if ($user) {
            echo json_encode(['message' => 'Success', 'user' => $user]);
        } else {
            echo json_encode(['error' => 'User not found']);
        }
    }

   public function updateUserInfo()
{
    $user = $this->getAuthenticatedUser();

    $data = json_decode(file_get_contents("php://input"), true);

    if (
        empty($data['first_name']) || !is_string($data['first_name']) ||
        empty($data['last_name']) || !is_string($data['last_name'])
    ) {
        echo json_encode(['error' => 'Invalid input data']);
        return;
    }

    $first_name = trim($data['first_name']);
    $last_name = trim($data['last_name']);

    if (User::updateUserInfo($user->id, $first_name, $last_name)) {
        $updatedUser = User::getById($user->id);
        echo json_encode([
            'message' => 'Success, user updated',
            'user' => $updatedUser
        ]);
    } else {
        echo json_encode(['error' => 'Failed to update user']);
    }
}

}
