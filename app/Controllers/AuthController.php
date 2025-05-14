<?php

namespace App\Controllers;



use App\Models\User;
use Firebase\JWT\JWT;
use Dotenv\Dotenv;


class AuthController
{

    // Méthode pour l'inscription d'un utilisateur
    public function register()
    {
        $data = json_decode(file_get_contents("php://input"), true);
        $username = $data['username'];
        $email = $data['email'];
        $password = $data['password'];

        User::create($username, $email, $password);

        echo json_encode(['message' => 'User created successfully']);
    }

    // Méthode pour la connexion d'un utilisateur

    public function login()
    {


        $dotenv = Dotenv::createImmutable(__DIR__.'/../../');
        $dotenv->load();

        $data = json_decode(file_get_contents("php://input"), true);
        $email = $data['email'];
        $password = $data['password'];

        $user = User::getByEmail($email);
        if (!$user || !password_verify($password, $user->password)) {
            echo json_encode(['message' => 'Invalid credentials']);
            return;
        }

        // Générer un JWT
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

    public function getUserInfo() {
        $data = json_decode(file_get_contents("php://input"), true);
        $email = $data['email'];
        $user = User::getByEmail($email);
        if ($user) {
            echo json_encode(['message' => 'Succes']);
        }


    }

    public function updateUserInfo() {
        
    }
}