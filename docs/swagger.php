<?php
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
