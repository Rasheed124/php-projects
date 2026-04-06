<?php
namespace App\Repository\Auth;

use PDO;

class AuthPagesRepository
{
    public function __construct(private PDO $pdo)
    {}

    // Check if email or username is already taken
    public function isEmailOrUsernameTaken($email, $user_name): bool
    {
        $stmt = $this->pdo->prepare("SELECT * FROM users WHERE email = :email OR username = :username");
        $stmt->bindValue(':email', $email);
        $stmt->bindValue(':username', $user_name);
        $stmt->execute();
        return $stmt->rowCount() > 0;
    }

    // Handle user signup
    public function handleSignUp($user_name, $email, $password): bool
    {
        $stmt = $this->pdo->prepare('INSERT INTO `users` (`username`, `email`, `password`) VALUES (:username, :email, :password)');
        $stmt->bindValue(':username', $user_name);
        $stmt->bindValue(':email', $email);
        $stmt->bindValue(':password', $password);
        return $stmt->execute();
    }

    public function getUserByEmail(string $email): ?array
    {
        $stmt = $this->pdo->prepare("SELECT * FROM users WHERE email = :email LIMIT 1");
        $stmt->bindValue(':email', $email);
        $stmt->execute();

        $result = $stmt->fetch(PDO::FETCH_ASSOC);

        return $result === false ? null : $result;
    }
}
