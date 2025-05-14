<?php

namespace App\Models;

class User
{
    public $id;
    private $first_name;
    private $email;
    public $password;


    public function __construct($id, $first_name, $email, $password)
    {
        $this->id = $id;
        $this->first_name = $first_name;
        $this->email = $email;
        $this->password = $password;
    }

    public static function create($first_name, $email, $password)
    {
        $db = (new \App\Core\Database())->connect();
        $stmt = $db->prepare("INSERT INTO users (first_name, email, password) VALUES (:first_name, :email, :password)");

        $hashedPassword = password_hash($password, PASSWORD_BCRYPT);

        $stmt->bindParam(':first_name', $first_name);
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
            return new self($user['id'], $user['first_name'], $user['email'], $user['password']);
        }
        return null;
    }

    public static function updateUserInfo()
    {

        $data = json_decode(file_get_contents("php://input"));

        if (!isset($data->first_name, $data->last_name, $data->id)) {
            echo json_encode(['error' => 'Missing required fields']);
            return;
        }

        $db = (new \App\Core\Database())->connect();
        $stmt = $db->prepare("UPDATE users SET first_name = :first_name, last_name = :last_name WHERE id = :id");
        $stmt->bindParam(':first_name', $data->first_name);
        $stmt->bindParam(':last_name', $data->last_name);
        $stmt->bindParam(':id', $data->id);

        if ($stmt->execute()) {
            echo json_encode(['message' => 'User updated successfully']);
        } else {
            echo json_encode(['error' => 'Failed to update user']);
        }
    }
}
