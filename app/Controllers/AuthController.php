<?php

namespace App\Controllers;

use App\Models\User;
use Firebase\JWT\JWT;
use Dotenv\Dotenv;

use OpenApi\Annotations as OA;

/**
 * @OA\Info(
 *     title="API Simple en PHP",
 *     version="1.0.0",
 *     description="Une API sans framework avec documentation Swagger"
 * )
 *
 * @OA\Server(
 *     url="http://localhost:8000",
 *     description="Serveur local"
 * )
 *
 * @OA\SecurityScheme(
 *     securityScheme="X-AUTH-TOKEN",
 *     type="apiKey",
 *     in="header",
 *     name="X-AUTH-TOKEN"
 * )
 */



class AuthController
{
    /**
     * @OA\Post(
     *     path="/register",
     *     summary="Inscription d'un utilisateur",
     *     description="Permet à un nouvel utilisateur de s'inscrire en fournissant son prénom, nom, email et mot de passe.",
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"first_name", "last_name", "email", "password"},
     *             @OA\Property(property="first_name", type="string", example="Alice"),
     *             @OA\Property(property="last_name", type="string", example="Dupont"),
     *             @OA\Property(property="email", type="string", example="alice@example.com"),
     *             @OA\Property(property="password", type="string", example="secret123")
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Utilisateur bien ajouté"
     *     ),
     *     @OA\Response(
     *         response=400,
     *         description="Données invalides ou manquantes"
     *     )
     * )
     */

    // Méthode pour l'inscription d'un utilisateur
    public function register()
    {
        $data = json_decode(file_get_contents("php://input"), true);

        // Validation des champs
        if (
            empty($data['first_name']) || !is_string($data['first_name']) ||
            empty($data['last_name']) || !is_string($data['last_name']) ||
            empty($data['email']) || !filter_var($data['email'], FILTER_VALIDATE_EMAIL) ||
            empty($data['password']) || strlen($data['password']) < 6
        ) {
            http_response_code(400);
            echo json_encode(['error' => 'Invalid input data. Make sure first_name and last_name are strings, email is valid and password is at least 6 characters.']);
            return;
        }

        $first_name = trim($data['first_name']);
        $last_name = trim($data['last_name']);
        $email = trim($data['email']);
        $password = $data['password'];

        $user = User::getByEmail($email);
        if ($user) {
            http_response_code(400);
            echo json_encode(['error' => 'Email already in use.']);
            return;
        }

        User::create($first_name, $last_name, $email, $password);

        echo json_encode(['message' => 'User created successfully']);
    }



    /**
     * @OA\Post(
     *     path="/login",
     *     summary="Connexion d'un utilisateur",
     *     description="Permet à un utilisateur de se connecter en fournissant son email et mot de passe.",
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"email", "password"},
     *             @OA\Property(property="email", type="string", example="alice@example.com"),
     *             @OA\Property(property="password", type="string", example="secret123")
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Connexion réussie, token JWT retourné"
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="Email ou mot de passe incorrect"
     *     )
     * )
     */
    // Méthode pour la connexion d'un utilisateur
    public function login()
    {
        $dotenv = Dotenv::createImmutable(__DIR__ . '/../../');
        $dotenv->load();

        $data = json_decode(file_get_contents("php://input"), true);

        // Validation de l'email
        if (empty($data['email']) || !filter_var($data['email'], FILTER_VALIDATE_EMAIL)) {
            http_response_code(400);
            echo json_encode(['error' => 'Invalid email']);
            return;
        }

        // Validation du mot de passe
        if (empty($data['password']) || !is_string($data['password'])) {
            http_response_code(400);
            echo json_encode(['error' => 'Invalid password']);
            return;
        }

        $email = trim($data['email']);
        $password = $data['password'];

        // Recherche de l'utilisateur
        $user = User::getByEmail($email);
        if (!$user) {
            http_response_code(404);
            echo json_encode(['error' => 'User not found']);
            return;
        }

        // Vérification du mot de passe
        if (!password_verify($password, $user->password)) {
            http_response_code(401);
            echo json_encode(['error' => 'Incorrect password']);
            return;
        }

        // Vérification de la clé JWT
        $key = $_ENV['JWT_SECRET_KEY'] ?? null;
        if (!$key || !is_string($key)) {
            http_response_code(500);
            echo json_encode(['error' => 'Server configuration error: JWT secret key is missing or invalid']);
            return;
        }

        // Création du token JWT
        $issuedAt = time();
        $expirationTime = $issuedAt + 300; // 5 minutes

        $payload = [
            'iat' => $issuedAt,
            'exp' => $expirationTime,
            'sub' => $user->id
        ];

        $jwt = JWT::encode($payload, $key, 'HS256');
        http_response_code(200);
        echo json_encode(['token' => $jwt]);
    }



    /**
     * @OA\Get(
     *     path="/me",
     *     summary="Récupérer les informations de l'utilisateur connecté",
     *     description="Retourne les données de l'utilisateur authentifié via le token JWT",
     *     security={{"X-AUTH-TOKEN":{}}},
     *     @OA\Response(
     *         response=200,
     *         description="Informations de l'utilisateur récupérées avec succès",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="id", type="integer", example=1),
     *             @OA\Property(property="first_name", type="string", example="Alice"),
     *             @OA\Property(property="last_name", type="string", example="Dupont"),
     *             @OA\Property(property="email", type="string", example="alice@example.com")
     *         )
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="Token manquant ou invalide"
     *     )
     * )
     */


    public function getUserInfo()
    {
        $data = json_decode(file_get_contents("php://input"), true);

        if (empty($data['email']) || !filter_var($data['email'], FILTER_VALIDATE_EMAIL)) {
            http_response_code(400);
            echo json_encode(['error' => 'Invalid email']);
            return;
        }

        $email = trim($data['email']);
        $user = User::getByEmail($email);
        if ($user) {
            http_response_code(200);
            echo json_encode(['message' => 'Success', 'user' => $user]);
        } else {
            http_response_code(404);
            echo json_encode(['error' => 'User not found']);
        }
    }



    /**
     * @OA\Put(
     *     path="/me/update",
     *     summary="Modifier les informations de l'utilisateur connecté",
     *     description="Met à jour les données de l'utilisateur authentifié via le token JWT",
     *     security={{"X-AUTH-TOKEN":{}}},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"first_name", "last_name", "email", "password"},
     *             @OA\Property(property="first_name", type="string", example="Alice"),
     *             @OA\Property(property="last_name", type="string", example="Dupont"),
     *             @OA\Property(property="email", type="string", example="alice@example.com"),
     *             @OA\Property(property="password", type="string", example="secret123")
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Utilisateur modifié avec succès"
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="Non autorisé"
     *     )
     * )
     */

    // Mettre à jour les informations d'un utilisateur
    public function updateUserInfo()
    {
        $user = $_REQUEST['authenticated_user'];

        $data = json_decode(file_get_contents("php://input"), true);

        // Empêcher la modification de l'email
        if (isset($data['email'])) {
            http_response_code(403);
            echo json_encode(['error' => 'Email modification is not allowed']);
            return;
        }

        // Validation des champs first_name et last_name
        if (
            empty($data['first_name']) || !is_string($data['first_name']) ||
            empty($data['last_name']) || !is_string($data['last_name'])
        ) {
            http_response_code(400);
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
            http_response_code(400);
            echo json_encode(['error' => 'Failed to update user']);
        }
    }
}
