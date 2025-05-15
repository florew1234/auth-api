<?php

namespace App\Models;

use JsonSerializable;

class User implements JsonSerializable
{
    public $id;
    private $first_name;
    private $last_name;
    private $email;
    public $password;


    public function __construct($id, $first_name, $last_name, $email, $password)
    {
        $this->id = $id;
        $this->first_name = $first_name;
        $this->last_name = $last_name;
        $this->email = $email;
        $this->password = $password;
    }

    public function jsonSerialize(): array
    {
        return [
            'first_name' => $this->first_name,
            'last_name' => $this->last_name,
            'email' => $this->email,
        ];
    }

    public static function create($first_name, $last_name, $email, $password)
    {
        $db = (new \App\Core\Database())->connect();
        $stmt = $db->prepare("INSERT INTO users (first_name, last_name, email, password) VALUES (:first_name, :last_name, :email, :password)");

        $hashedPassword = password_hash($password, PASSWORD_BCRYPT);

        $stmt->bindParam(':first_name', $first_name);
        $stmt->bindParam(':last_name', $last_name);
        $stmt->bindParam(':email', $email);
        $stmt->bindParam(':password', $hashedPassword);

        $stmt->execute();
    }

    public static function getByEmail($email)
    {
        $db = (new \App\Core\Database())->connect();
        $stmt = $db->prepare("SELECT * FROM users WHERE email = :email");
        $stmt->bindParam(':email', $email);
        $stmt->execute();
        $user = $stmt->fetch();

        if ($user) {
            return new self($user['id'], $user['first_name'], $user['last_name'], $user['email'], $user['password']);
        }
        return null;
    }

    public static function updateUserInfo($id, $first_name, $last_name)
    {
        $db = (new \App\Core\Database())->connect();
        $stmt = $db->prepare("UPDATE users SET first_name = :first_name, last_name = :last_name WHERE id = :id");
        $stmt->bindParam(':first_name', $first_name);
        $stmt->bindParam(':last_name', $last_name);
        $stmt->bindParam(':id', $id);

        return $stmt->execute();
    }
}
